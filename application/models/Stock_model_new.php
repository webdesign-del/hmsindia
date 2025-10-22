<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model_new extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ===============================================
    // HELPER FUNCTIONS
    // ===============================================

    /**
     * Calculate expiry days for a batch
     * @param string $expiry_date
     * @return int
     */
    public function calculate_expiry_days($expiry_date) {
        $expiry = new DateTime($expiry_date);
        $today = new DateTime();
        $diff = $today->diff($expiry);
        return $expiry > $today ? $diff->days : -$diff->days;
    }

    /**
     * Update expiry_days for all batches
     * Call this method periodically to keep expiry_days current
     */
    public function update_expiry_days() {
        $this->db->query("
            UPDATE medicine_batches 
            SET expiry_days = DATEDIFF(expiry_date, CURDATE())
            WHERE batch_status = 'ACTIVE'
        ");
    }

    // ===============================================
    // DASHBOARD FUNCTIONS
    // ===============================================

    public function get_dashboard_summary() {
        try {
            // Get total medicines
            $this->db->select('COUNT(*) as total_medicines');
            $this->db->from('medicines');
            $this->db->where('status', 'active');
            $medicines = $this->db->get()->row();
            
            // Get total active batches
            $this->db->select('COUNT(*) as total_batches');
            $this->db->from('medicine_batches');
            $this->db->where('batch_status', 'ACTIVE');
            $this->db->where('quantity_remaining >', 0);
            $batches = $this->db->get()->row();
            
            // Get low stock count
            $this->db->select('COUNT(*) as low_stock_count');
            $this->db->from('medicines m');
            $this->db->join('medicine_batches mb', 'm.id = mb.medicine_id');
            $this->db->where('m.status', 'active');
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('mb.quantity_remaining <=', 'm.min_stock_level', false);
            $low_stock = $this->db->get()->row();
            
            // Get expiring soon count (within 30 days)
            $this->db->select('COUNT(*) as expiring_soon_count');
            $this->db->from('medicine_batches');
            $this->db->where('batch_status', 'ACTIVE');
            $this->db->where('quantity_remaining >', 0);
            $this->db->where('DATEDIFF(expiry_date, CURDATE()) <=', 30);
            $this->db->where('DATEDIFF(expiry_date, CURDATE()) >=', 0);
            $expiring = $this->db->get()->row();
            
            // Get expired count
            $this->db->select('COUNT(*) as expired_count');
            $this->db->from('medicine_batches');
            $this->db->where('batch_status', 'ACTIVE');
            $this->db->where('quantity_remaining >', 0);
            $this->db->where('DATEDIFF(expiry_date, CURDATE()) <', 0);
            $expired = $this->db->get()->row();
            
            // Get total stock value
            $this->db->select('SUM(quantity_remaining * selling_price) as total_stock_value');
            $this->db->from('medicine_batches');
            $this->db->where('batch_status', 'ACTIVE');
            $this->db->where('quantity_remaining >', 0);
            $stock_value = $this->db->get()->row();
            
            // Get expiring soon items (for the second card)
            $this->db->select('COUNT(*) as expiring_soon_items');
            $this->db->from('medicine_batches');
            $this->db->where('batch_status', 'ACTIVE');
            $this->db->where('quantity_remaining >', 0);
            $this->db->where('DATEDIFF(expiry_date, CURDATE()) <=', 7);
            $this->db->where('DATEDIFF(expiry_date, CURDATE()) >=', 0);
            $expiring_soon = $this->db->get()->row();
            
            return (object) [
                'total_medicines' => $medicines->total_medicines ?? 0,
                'total_batches' => $batches->total_batches ?? 0,
                'low_stock_count' => $low_stock->low_stock_count ?? 0,
                'expiring_soon_count' => $expired->expired_count ?? 0,
                'expired_count' => $expired->expired_count ?? 0,
                'total_stock_value' => $stock_value->total_stock_value ?? 0,
                'expiring_soon_items' => $expiring_soon->expiring_soon_items ?? 0
            ];
        } catch (Exception $e) {
            return (object) [
                'total_medicines' => 0,
                'total_batches' => 0,
                'low_stock_count' => 0,
                'expiring_soon_count' => 0,
                'expired_count' => 0,
                'total_stock_value' => 0,
                'expiring_soon_items' => 0
            ];
        }
    }

    public function get_low_stock_alerts() {
        try {
            $this->db->select('
                m.id as medicine_id,
                m.medicine_name,
                m.medicine_code,
                m.generic_name,
                m.min_stock_level,
                m.max_stock_level,
                m.reorder_level,
                COALESCE(SUM(mb.quantity_remaining), 0) as current_stock,
                CASE 
                    WHEN COALESCE(SUM(mb.quantity_remaining), 0) = 0 THEN "OUT_OF_STOCK"
                    WHEN COALESCE(SUM(mb.quantity_remaining), 0) <= m.min_stock_level THEN "LOW_STOCK"
                    ELSE "NORMAL"
                END as stock_status,
                b.name as brand_name
            ');
            $this->db->from('medicines m');
            $this->db->join('medicine_batches mb', 'm.id = mb.medicine_id', 'left');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID', 'left');
            $this->db->where('m.status', 'active');
            $this->db->where('m.min_stock_level >', 0);
            $this->db->where('(mb.batch_status = "ACTIVE" OR mb.batch_status IS NULL)');
            $this->db->where('(mb.quantity_remaining > 0 OR mb.quantity_remaining IS NULL)');
            $this->db->group_by('m.id, m.medicine_name, m.medicine_code, m.generic_name, m.min_stock_level, m.max_stock_level, m.reorder_level, b.name');
            $this->db->having('current_stock <= m.min_stock_level');
            $this->db->order_by('(current_stock - m.min_stock_level)', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_expiry_alerts() {
        try {
            $this->db->select('
                mb.id as batch_id,
                m.medicine_name,
                m.medicine_code,
                m.generic_name,
                mb.batch_number,
                mb.expiry_date,
                mb.quantity_remaining as central_quantity,
                mb.quantity_remaining as center_quantity,
                "Central" as center_name,
                b.name as brand_name,
                DATEDIFF(mb.expiry_date, CURDATE()) as days_to_expiry,
                CASE 
                    WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
                    WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 7 THEN "CRITICAL"
                    WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "WARNING"
                    ELSE "OK"
                END as alert_level
            ');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID', 'left');
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('mb.quantity_remaining >', 0);
            $this->db->where('mb.expiry_date <=', date('Y-m-d', strtotime('+30 days')));
            $this->db->order_by('mb.expiry_date', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_recent_sales($limit = 10) {
        try {
            $this->db->select('s.*, c.center_name');
            $this->db->from('sales s');
            $this->db->join('hms_centers c', 's.center_id = c.ID', 'left');
            $this->db->where('s.status', 'CONFIRMED');
            $this->db->order_by('s.created_at', 'DESC');
            $this->db->limit($limit);
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_recent_transfers($limit = 10) {
        try {
            $this->db->select('st.*, fc.center_name as from_center, tc.center_name as to_center');
            $this->db->from('stock_transfers st');
            $this->db->join('hms_centers fc', 'st.from_center_id = fc.ID', 'left');
            $this->db->join('hms_centers tc', 'st.to_center_id = tc.ID', 'left');
            $this->db->where('st.status', 'COMPLETED');
            $this->db->order_by('st.created_at', 'DESC');
            $this->db->limit($limit);
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_sales_analytics($days = 30) {
        try {
            $this->db->select('
                DATE(sale_date) as sale_date,
                COUNT(*) as total_sales,
                SUM(subtotal) as total_revenue,
                AVG(subtotal) as avg_sale_amount
            ');
            $this->db->from('sales');
            $this->db->where('sale_date >=', date('Y-m-d', strtotime("-{$days} days")));
            $this->db->where('status', 'CONFIRMED');
            $this->db->group_by('DATE(sale_date)');
            $this->db->order_by('sale_date', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_transfer_analytics($days = 30) {
        try {
            $this->db->select('
                DATE(transfer_date) as transfer_date,
                COUNT(*) as total_transfers,
                SUM(total_value) as total_value,
                AVG(total_value) as avg_transfer_value
            ');
            $this->db->from('stock_transfers');
            $this->db->where('transfer_date >=', date('Y-m-d', strtotime("-{$days} days")));
            $this->db->where('status', 'COMPLETED');
            $this->db->group_by('DATE(transfer_date)');
            $this->db->order_by('transfer_date', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_top_selling_medicines($limit = 10) {
        try {
            $this->db->select('m.medicine_name, mb.name as brand_name, SUM(si.quantity_sold) as total_sold, SUM(si.total) as total_revenue');
            $this->db->from('sale_items si');
            $this->db->join('medicine_batches mb2', 'si.batch_id = mb2.id');
            $this->db->join('medicines m', 'mb2.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands mb', 'm.brand_id = mb.ID');
            $this->db->join('sales s', 'si.sale_id = s.id');
            $this->db->where('s.status', 'CONFIRMED');
            $this->db->group_by('m.id, m.medicine_name, mb.name');
            $this->db->order_by('total_sold', 'DESC');
            $this->db->limit($limit);
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_center_stock_summary() {
        try {
            $this->db->select('c.center_name, COUNT(DISTINCT ccs.batch_id) as total_batches, SUM(ccs.quantity) as total_quantity');
            $this->db->from('centers c');
            $this->db->join('center_stocks ccs', 'c.id = ccs.center_id', 'left');
            $this->db->where('c.status', 'active');
            $this->db->where('ccs.status', 'ACTIVE');
            $this->db->group_by('c.id, c.center_name');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    // ===============================================
    // MEDICINE BRANDS FUNCTIONS
    // ===============================================

    public function get_medicine_brands() {
        $this->db->order_by('ID', 'ASC');
        return $this->db->get($this->config->item('db_prefix') . 'brands')->result();
    }

    public function add_medicine_brand($data) {
        return $this->db->insert($this->config->item('db_prefix') . 'brands', $data);
    }

    public function update_medicine_brand($id, $data) {
        $this->db->where('ID', $id);
        return $this->db->update($this->config->item('db_prefix') . 'brands', $data);
    }

    public function get_medicine_brand_by_id($id) {
        $this->db->where('ID', $id);
        return $this->db->get($this->config->item('db_prefix') . 'brands')->row();
    }

    // ===============================================
    // VENDORS FUNCTIONS
    // ===============================================

    public function get_vendors() {
        $this->db->order_by('ID', 'ASC');
        return $this->db->get($this->config->item('db_prefix') . 'vendors')->result();
    }

    public function add_vendor($data) {
        // Generate vendor number if not provided
        if (!isset($data['vendor_number']) || empty($data['vendor_number'])) {
            $data['vendor_number'] = $this->generate_vendor_number();
        }
        
        // Set created date
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->db->insert($this->config->item('db_prefix') . 'vendors', $data);
    }

    public function update_vendor($id, $data) {
        // Set updated date
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->where('ID', $id);
        return $this->db->update($this->config->item('db_prefix') . 'vendors', $data);
    }

    public function generate_vendor_number() {
        // Get the last vendor number
        $this->db->select('vendor_number');
        $this->db->from($this->config->item('db_prefix') . 'vendors');
        $this->db->order_by('ID', 'DESC');
        $this->db->limit(1);
        $result = $this->db->get()->row();
        
        if ($result && !empty($result->vendor_number)) {
            // Extract number from existing vendor_number
            $last_number = intval(preg_replace('/[^0-9]/', '', $result->vendor_number));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return 'VEND' . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    public function handle_vendor_file_upload($file_input_name, $vendor_id = null) {
        if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $file = $_FILES[$file_input_name];
        $allowed_types = array('pdf', 'jpg', 'jpeg', 'png');
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_extension, $allowed_types)) {
            return false; // Invalid file type
        }
        
        // Create upload directory if it doesn't exist
        $upload_dir = 'uploads/vendors/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Generate unique filename
        $filename = $file_input_name . '_' . ($vendor_id ? $vendor_id : 'new') . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            return $filename;
        }
        
        return false;
    }

    public function get_vendor_by_id($id) {
        $this->db->where('ID', $id);
        return $this->db->get($this->config->item('db_prefix') . 'vendors')->row();
    }

    // ===============================================
    // MEDICINES FUNCTIONS
    // ===============================================

    public function get_all_medicines() {
        $this->db->select('m.*, mb.name as brand_name');
        $this->db->from('medicines m');
        $this->db->join($this->config->item('db_prefix') . 'brands mb', 'm.brand_id = mb.ID');
        $this->db->where('m.status', 'active');
        $this->db->order_by('m.medicine_name', 'ASC');
        return $this->db->get()->result();
    }

    public function add_medicine($data) {
        return $this->db->insert('medicines', $data);
    }

    public function update_medicine($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('medicines', $data);
    }

    public function get_medicine_by_id($id) {
        $this->db->select('m.*, mb.name as brand_name');
        $this->db->from('medicines m');
        $this->db->join($this->config->item('db_prefix') . 'brands mb', 'm.brand_id = mb.ID');
        $this->db->where('m.id', $id);
        return $this->db->get()->row();
    }

    // ===============================================
    // BATCHES FUNCTIONS
    // ===============================================

    public function get_all_batches($medicine_id = null, $vendor_id = null, $batch_number = null, $batch_status = null) {
        // Try to get batches with a simple query first
        try {
            $this->db->select('mb.*, m.medicine_name, m.medicine_code, mb2.name as brand_name, v.name as vendor_name, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days, COALESCE(mb.quality_status, "PENDING") as quality_status');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands mb2', 'm.brand_id = mb2.ID');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID');
            
            if($batch_status && $batch_status != '') {
                $this->db->where('mb.batch_status', $batch_status);
            } else {
                $this->db->where('mb.batch_status', 'ACTIVE');
            }
            
            if($medicine_id && $medicine_id != '') {
                $this->db->where('mb.medicine_id', $medicine_id);
            }
            
            if($vendor_id && $vendor_id != '') {
                $this->db->where('mb.vendor_id', $vendor_id);
            }
            
            if($batch_number && $batch_number != '') {
                $this->db->like('mb.batch_number', $batch_number);
            }
            
            $this->db->order_by('mb.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If table doesn't exist or has issues, return empty array
            return array();
        }
    }

    public function add_batch($data) {
        $this->db->trans_start();
        
        // Calculate expiry days
        if (isset($data['expiry_date'])) {
            $data['expiry_days'] = $this->calculate_expiry_days($data['expiry_date']);
        }
        
        // Insert batch
        $this->db->insert('medicine_batches', $data);
        $batch_id = $this->db->insert_id();
        
        // Add to central stock
        $central_stock_data = [
            'batch_id' => $batch_id,
            'quantity' => $data['quantity_purchased'],
            'last_movement_date' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('central_stocks', $central_stock_data);
        
        // Log stock movement
        $movement_data = [
            'batch_id' => $batch_id,
            'movement_type' => 'PURCHASE',
            'from_location_type' => 'VENDOR',
            'from_location_id' => $data['vendor_id'],
            'to_location_type' => 'CENTRAL',
            'quantity_change' => $data['quantity_purchased'],
            'quantity_after' => $data['quantity_purchased'],
            'unit_price' => $data['purchase_price'],
            'total_value' => $data['quantity_purchased'] * $data['purchase_price'],
            'reference_type' => 'PURCHASE_RECEIPT',
            'reference_id' => $batch_id,
            'reference_number' => $data['invoice_number'],
            'created_by' => $data['created_by']
        ];
        $this->db->insert('stock_movements', $movement_data);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_batch_by_id($id) {
        $this->db->select('mb.*, m.medicine_name, m.medicine_code, mb2.name as brand_name, v.name as vendor_name, COALESCE(mb.quality_status, "PENDING") as quality_status, mb.quantity_remaining as current_stock');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->join($this->config->item('db_prefix') . 'brands mb2', 'm.brand_id = mb2.ID');
        $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID');
        $this->db->where('mb.id', $id);
        return $this->db->get()->row();
    }

    // ===============================================
    // CENTRAL STOCKS FUNCTIONS
    // ===============================================
    
    public function get_central_stocks($medicine_id = null, $batch_number = null, $status = null) {
        try {
            $this->db->select('cs.*, mb.batch_number, mb.expiry_date, mb.purchase_price, mb.selling_price, m.medicine_name, m.medicine_code, b.name as brand_name, v.name as vendor_name, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days');
            $this->db->from('central_stocks cs');
            $this->db->join('medicine_batches mb', 'cs.batch_id = mb.id');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID');
            
            if($medicine_id && $medicine_id != '') {
                $this->db->where('mb.medicine_id', $medicine_id);
            }
            
            if($batch_number && $batch_number != '') {
                $this->db->like('mb.batch_number', $batch_number);
            }
            
            if($status && $status != '') {
                $this->db->where('cs.status', $status);
            }
            
            $this->db->order_by('mb.expiry_date', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }
    
    public function update_central_stock_status($stock_id, $status) {
        $this->db->where('id', $stock_id);
        return $this->db->update('central_stocks', ['status' => $status]);
    }
    
    // ===============================================
    // CENTER STOCKS FUNCTIONS
    // ===============================================
    
    public function get_center_stocks($center_id = null, $medicine_id = null, $batch_number = null, $status = null) {
        try {
            $this->db->select('ccs.*, mb.batch_number, mb.expiry_date, mb.purchase_price, mb.selling_price, m.medicine_name, m.medicine_code, b.name as brand_name, v.name as vendor_name, c.center_name, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days');
            $this->db->from('center_stocks ccs');
            $this->db->join('medicine_batches mb', 'ccs.batch_id = mb.id');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID');
            $this->db->join('hms_centers c', 'ccs.center_id = c.ID');
            
            if($center_id && $center_id != '') {
                $this->db->where('ccs.center_id', $center_id);
            }
            
            if($medicine_id && $medicine_id != '') {
                $this->db->where('mb.medicine_id', $medicine_id);
            }
            
            if($batch_number && $batch_number != '') {
                $this->db->like('mb.batch_number', $batch_number);
            }
            
            if($status && $status != '') {
                $this->db->where('ccs.status', $status);
            }
            
            $this->db->order_by('mb.expiry_date', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }
    
    public function update_center_stock_status($stock_id, $status) {
        $this->db->where('id', $stock_id);
        return $this->db->update('center_stocks', ['status' => $status]);
    }
    
    // ===============================================
    // BATCH STATUS MANAGEMENT FUNCTIONS
    // ===============================================
    
    public function update_batch_status($batch_id, $status) {
        $this->db->where('id', $batch_id);
        return $this->db->update('medicine_batches', ['batch_status' => $status]);
    }
    
    public function get_batch_status_options() {
        return [
            'ACTIVE' => 'Active',
            'INACTIVE' => 'Inactive', 
            'EXPIRED' => 'Expired',
            'DAMAGED' => 'Damaged',
            'QUARANTINE' => 'Quarantine',
            'RETURNED' => 'Returned',
            'DISPOSED' => 'Disposed'
        ];
    }

    // ===============================================
    // STOCK LEVELS FUNCTIONS
    // ===============================================

    public function get_current_stock_levels($center_id = null, $medicine_name = null, $stock_status = null) {
        try {
            // First try to use the view
            $this->db->select('*');
            $this->db->from('v_current_stock_levels');
            
            if($center_id && $center_id != '') {
                $this->db->where('center_id', $center_id);
            }
            
            if($medicine_name && $medicine_name != '') {
                $this->db->like('medicine_name', $medicine_name);
            }
            
            if($stock_status && $stock_status != '') {
                $this->db->where('expiry_status', $stock_status);
            }
            
            $this->db->order_by('medicine_name', 'ASC');
            $result = $this->db->get()->result();
            
            // If view returns empty or has issues, fall back to table method
            if(empty($result)) {
                return $this->get_stock_levels_from_tables($center_id, $medicine_name, $stock_status);
            }
            
            return $result;
        } catch (Exception $e) {
            // Log the error and fall back to table method
            log_message('error', 'Stock levels view error: ' . $e->getMessage());
            return $this->get_stock_levels_from_tables($center_id, $medicine_name, $stock_status);
        }
    }
    
    public function get_stock_levels_from_tables($center_id = null, $medicine_name = null, $stock_status = null) {
        try {
            // Get aggregated stock data by medicine and batch
            $this->db->select('
                m.id as medicine_id,
                m.medicine_name,
                m.medicine_code,
                mb.batch_number,
                mb.expiry_date,
                DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                SUM(COALESCE(cs.quantity, 0)) as central_quantity,
                SUM(COALESCE(ccs.quantity, 0)) as center_quantity,
                SUM(COALESCE(cs.quantity, 0) + COALESCE(ccs.quantity, 0)) as total_quantity,
                GROUP_CONCAT(DISTINCT COALESCE(c.center_name, "Central") SEPARATOR ", ") as center_names,
                GROUP_CONCAT(DISTINCT ccs.center_id SEPARATOR ",") as center_ids,
                ROW_NUMBER() OVER (ORDER BY mb.expiry_date ASC, mb.created_at ASC) as fifo_rank,
                CASE 
                    WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
                    WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "EXPIRING_SOON"
                    ELSE "FRESH"
                END as expiry_status,
                mb.id as batch_id,
                COALESCE(b.name, "Unknown") as brand_name,
                mb.purchase_price,
                mb.selling_price,
                mb.mrp,
                mb.quantity_remaining
            ');
            $this->db->from('medicines m');
            $this->db->join('medicine_batches mb', 'm.id = mb.medicine_id');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID', 'left');
            $this->db->join('central_stocks cs', 'mb.id = cs.batch_id', 'left');
            $this->db->join('center_stocks ccs', 'mb.id = ccs.batch_id', 'left');
            $this->db->join($this->config->item('db_prefix') . 'centers c', 'ccs.center_id = c.ID', 'left');
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('(COALESCE(cs.quantity, 0) > 0 OR COALESCE(ccs.quantity, 0) > 0)');
            
            if($center_id && $center_id != '') {
                $this->db->where('(ccs.center_id = ' . $center_id . ' OR ccs.center_id IS NULL)');
            }
            
            if($medicine_name && $medicine_name != '') {
                $this->db->like('m.medicine_name', $medicine_name);
            }
            
            if($stock_status && $stock_status != '') {
                if($stock_status == 'EXPIRED') {
                    $this->db->where('DATEDIFF(mb.expiry_date, CURDATE()) <', 0);
                } elseif($stock_status == 'EXPIRING_SOON') {
                    $this->db->where('DATEDIFF(mb.expiry_date, CURDATE()) <=', 30);
                    $this->db->where('DATEDIFF(mb.expiry_date, CURDATE()) >=', 0);
                } elseif($stock_status == 'FRESH') {
                    $this->db->where('DATEDIFF(mb.expiry_date, CURDATE()) >', 30);
                }
            }
            
            $this->db->group_by('m.id, mb.id');
            $this->db->order_by('mb.expiry_date', 'ASC');
            $this->db->order_by('m.medicine_name', 'ASC');
            
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return array();
        }
    }

    public function get_medicine_stock_summary() {
        try {
            $this->db->select('
                m.id as medicine_id,
                m.medicine_name,
                m.medicine_code,
                m.generic_name,
                b.name as brand_name,
                COUNT(mb.id) as total_batches,
                SUM(mb.quantity_remaining) as total_quantity,
                AVG(mb.selling_price) as avg_price,
                SUM(mb.quantity_remaining * mb.selling_price) as total_value,
                MIN(mb.expiry_date) as earliest_expiry,
                MAX(mb.expiry_date) as latest_expiry,
                COUNT(CASE WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN 1 END) as expiring_soon_count,
                COUNT(CASE WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN 1 END) as expired_count
            ');
            $this->db->from('medicines m');
            $this->db->join('medicine_batches mb', 'm.id = mb.medicine_id');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID', 'left');
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('mb.quantity_remaining >', 0);
            $this->db->group_by('m.id, m.medicine_name, m.medicine_code, m.generic_name, b.name');
            $this->db->order_by('m.medicine_name', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_available_stock($batch_id, $center_id) {
        $this->db->select('cs.quantity as central_stock, ccs.quantity as center_stock');
        $this->db->from('central_stocks cs');
        $this->db->join('center_stocks ccs', 'cs.batch_id = ccs.batch_id AND ccs.center_id = ' . $center_id, 'left');
        $this->db->where('cs.batch_id', $batch_id);
        $this->db->where('cs.status', 'ACTIVE');
        return $this->db->get()->row();
    }

    // ===============================================
    // STOCK TRANSFERS FUNCTIONS
    // ===============================================

    public function get_all_transfers() {
        try {
            $this->db->select('
                st.*, 
                fc.center_name as from_center, 
                tc.center_name as to_center,
                COUNT(sti.id) as total_items,
                COALESCE(SUM(sti.quantity_transferred), 0) as total_quantity,
                COALESCE(SUM(sti.total_price), 0) as total_value
            ');
            $this->db->from('stock_transfers st');
            $this->db->join('hms_centers fc', 'st.from_center_id = fc.ID', 'left');
            $this->db->join('hms_centers tc', 'st.to_center_id = tc.ID', 'left');
            $this->db->join('stock_transfer_items sti', 'st.id = sti.transfer_id', 'left');
            $this->db->group_by('st.id');
            $this->db->order_by('st.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return array();
        }
    }

    public function add_transfer($data) {
        // Generate transfer number
        $data['transfer_number'] = 'TRF' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        if($this->db->insert('stock_transfers', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function get_transfer_by_id($id) {
        $this->db->select('
            st.*, 
            fc.center_name as from_center, 
            tc.center_name as to_center,
            COUNT(sti.id) as total_items,
            COALESCE(SUM(sti.quantity_transferred), 0) as total_quantity,
            COALESCE(SUM(sti.total_price), 0) as total_value
        ');
        $this->db->from('stock_transfers st');
        $this->db->join('hms_centers fc', 'st.from_center_id = fc.ID', 'left');
        $this->db->join('hms_centers tc', 'st.to_center_id = tc.ID', 'left');
        $this->db->join('stock_transfer_items sti', 'st.id = sti.transfer_id', 'left');
        $this->db->where('st.id', $id);
        $this->db->group_by('st.id');
        return $this->db->get()->row();
    }

    public function get_transfer_items($transfer_id) {
        $this->db->select('sti.*, m.medicine_name, m.medicine_code, mb.name as brand_name, mb2.batch_number, mb2.expiry_date');
        $this->db->from('stock_transfer_items sti');
        $this->db->join('medicine_batches mb2', 'sti.batch_id = mb2.id');
        $this->db->join('medicines m', 'mb2.medicine_id = m.id');
        $this->db->join($this->config->item('db_prefix') . 'brands mb', 'm.brand_id = mb.ID');
        $this->db->where('sti.transfer_id', $transfer_id);
        return $this->db->get()->result();
    }

    public function add_transfer_item($data) {
        if($this->db->insert('stock_transfer_items', $data)) {
            $item_id = $this->db->insert_id();
            
            // Update transfer totals
            $this->update_transfer_totals($data['transfer_id']);
            
            return $item_id;
        }
        return false;
    }

    public function remove_transfer_item($item_id) {
        // Get transfer_id before deleting
        $this->db->select('transfer_id');
        $this->db->from('stock_transfer_items');
        $this->db->where('id', $item_id);
        $item = $this->db->get()->row();
        
        if($item) {
            // Delete the item
            $this->db->where('id', $item_id);
            $result = $this->db->delete('stock_transfer_items');
            
            if($result) {
                // Update transfer totals
                $this->update_transfer_totals($item->transfer_id);
                return true;
            }
        }
        
        return false;
    }

    public function update_transfer_totals($transfer_id) {
        // Get totals from transfer items
        $this->db->select('
            COUNT(id) as total_items,
            SUM(quantity_transferred) as total_quantity,
            SUM(total_price) as total_value
        ');
        $this->db->from('stock_transfer_items');
        $this->db->where('transfer_id', $transfer_id);
        $totals = $this->db->get()->row();
        
        // Update transfer record with calculated totals
        $this->db->where('id', $transfer_id);
        $this->db->update('stock_transfers', [
            'total_items' => $totals->total_items ?: 0,
            'total_quantity' => $totals->total_quantity ?: 0,
            'total_value' => $totals->total_value ?: 0
        ]);
        
        return true;
    }

    public function approve_transfer($id, $approved_by) {
        $this->db->trans_start();
        
        // Check if transfer has items before approval
        $items = $this->get_transfer_items($id);
        if(empty($items)) {
            $this->db->trans_rollback();
            return false; // Cannot approve transfer without items
        }
        
        // Update transfer status
        $this->db->where('id', $id);
        $this->db->update('stock_transfers', [
            'status' => 'APPROVED',
            'approved_by' => $approved_by
        ]);
        
        // Get transfer items
        $transfer = $this->get_transfer_by_id($id);
        
        foreach($items as $item) {
            // Reduce from source location
            if($transfer->transfer_type == 'CENTRAL_TO_CENTER') {
                $this->db->where('batch_id', $item->batch_id);
                $this->db->set('quantity', 'quantity - ' . $item->quantity_transferred, FALSE);
                $this->db->update('central_stocks');
            } else {
                $this->db->where('batch_id', $item->batch_id);
                $this->db->where('center_id', $transfer->from_center_id);
                $this->db->set('quantity', 'quantity - ' . $item->quantity_transferred, FALSE);
                $this->db->update('center_stocks');
            }
            
            // Add to destination location
            $this->db->where('batch_id', $item->batch_id);
            $this->db->where('center_id', $transfer->to_center_id);
            $existing = $this->db->get('center_stocks')->row();
            
            if($existing) {
                $this->db->where('batch_id', $item->batch_id);
                $this->db->where('center_id', $transfer->to_center_id);
                $this->db->set('quantity', 'quantity + ' . $item->quantity_transferred, FALSE);
                $this->db->update('center_stocks');
            } else {
                $this->db->insert('center_stocks', [
                    'batch_id' => $item->batch_id,
                    'center_id' => $transfer->to_center_id,
                    'quantity' => $item->quantity_transferred,
                    'last_movement_date' => date('Y-m-d H:i:s')
                ]);
            }
            
            // Log stock movement
            $movement_data = [
                'batch_id' => $item->batch_id,
                'movement_type' => 'TRANSFER_OUT',
                'from_location_type' => $transfer->transfer_type == 'CENTRAL_TO_CENTER' ? 'CENTRAL' : 'CENTER',
                'from_location_id' => $transfer->from_center_id,
                'to_location_type' => 'CENTER',
                'to_location_id' => $transfer->to_center_id,
                'quantity_change' => -$item->quantity_transferred,
                'unit_price' => $item->unit_price,
                'total_value' => $item->total_price,
                'reference_type' => 'STOCK_TRANSFER',
                'reference_id' => $id,
                'reference_number' => $transfer->transfer_number,
                'created_by' => $approved_by
            ];
            $this->db->insert('stock_movements', $movement_data);
        }
        
        // Update transfer status to COMPLETED after successful stock movement
        $this->db->where('id', $id);
        $update_data = ['status' => 'COMPLETED'];
        
        // Check if approved_date column exists before trying to update it
        $columns = $this->db->list_fields('stock_transfers');
        if(in_array('approved_date', $columns)) {
            $update_data['approved_date'] = date('Y-m-d H:i:s');
        }
        
        $this->db->update('stock_transfers', $update_data);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function bulk_approve_transfers($transfer_ids, $approved_by) {
        $success_count = 0;
        $failed_count = 0;
        $results = [];
        
        foreach($transfer_ids as $transfer_id) {
            $result = $this->approve_transfer($transfer_id, $approved_by);
            if($result) {
                $success_count++;
                $results[] = ['id' => $transfer_id, 'status' => 'success'];
            } else {
                $failed_count++;
                $results[] = ['id' => $transfer_id, 'status' => 'failed'];
            }
        }
        
        return [
            'success_count' => $success_count,
            'failed_count' => $failed_count,
            'results' => $results
        ];
    }

    public function get_available_batches_for_transfer($center_id, $department = null, $employee_number = null) {
        if($center_id) {
            // Center to center transfer
            $this->db->select('mb.*, m.medicine_name, m.medicine_code, mb2.name as brand_name, ccs.quantity as available_quantity');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands mb2', 'm.brand_id = mb2.ID');
            $this->db->join('center_stocks ccs', 'mb.id = ccs.batch_id');
            $this->db->where('ccs.center_id', $center_id);
            $this->db->where('ccs.quantity >', 0);
            $this->db->where('mb.batch_status', 'ACTIVE');
            
            if($department) {
                $this->db->where('mb.department', $department);
            }
            
            if($employee_number) {
                $this->db->where('mb.employee_number', $employee_number);
            }
        } else {
            // Central to center transfer
            $this->db->select('mb.*, m.medicine_name, m.medicine_code, mb2.name as brand_name, cs.quantity as available_quantity');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands mb2', 'm.brand_id = mb2.ID');
            $this->db->join('central_stocks cs', 'mb.id = cs.batch_id');
            $this->db->where('cs.quantity >', 0);
            $this->db->where('mb.batch_status', 'ACTIVE');
        }
        
        // Order by FEFO (First Expiry First Out)
        $this->db->order_by('mb.expiry_date', 'ASC');
        $this->db->order_by('m.medicine_name', 'ASC');
        return $this->db->get()->result();
    }

    // ===============================================
    // MULTI-ITEM TRANSFER FUNCTIONS
    // ===============================================

    public function process_multi_transfer($data) {
        $this->db->trans_start();
        
        try {
            // Generate transfer number
            $transfer_number = 'MTR' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // 1. Create main transfer record
            $transfer_data = [
                'transfer_number' => $transfer_number,
                'transfer_type' => 'CENTER_TO_CENTER',
                'from_center_id' => $data['from_center_id'],
                'to_center_id' => $data['to_center_id'],
                'transfer_date' => $data['transfer_date'],
                'remarks' => $data['remarks'],
                'created_by' => $data['transferred_by'],
                'status' => 'COMPLETED',
                'total_value' => 0
            ];
            
            $this->db->insert('stock_transfers', $transfer_data);
            $transfer_id = $this->db->insert_id();
            
            $total_value = 0;
            
            // 2. Process each transfer item
            foreach($data['transfer_items'] as $item) {
                if(empty($item['batch_id']) || empty($item['quantity']) || $item['quantity'] <= 0) {
                    continue;
                }
                
                // Get batch details
                $batch = $this->get_batch_by_id($item['batch_id']);
                if(!$batch) {
                    throw new Exception('Batch not found: ' . $item['batch_id']);
                }
                
                // Check if sufficient quantity is available at source location (center + department + employee)
                $source_stock = $this->db->select('available_quantity')
                    ->from('medicine_batches')
                    ->where('id', $item['batch_id'])
                    ->where('center_id', $data['from_center_id'])
                    ->where('department', $data['from_department'])
                    ->where('employee_number', $data['from_employee_number'])
                    ->where('batch_status', 'ACTIVE')
                    ->get()
                    ->row();
                
                if(!$source_stock || $source_stock->available_quantity < $item['quantity']) {
                    throw new Exception('Insufficient quantity for batch: ' . $batch->batch_number . ' at source location (Center: ' . $data['from_center_id'] . ', Dept: ' . $data['from_department'] . ', Emp: ' . $data['from_employee_number'] . ')');
                }
                
                // Calculate item value
                $item_value = $item['quantity'] * $batch->selling_price;
                $total_value += $item_value;
                
                // Record transfer item
                $item_data = [
                    'transfer_id' => $transfer_id,
                    'batch_id' => $item['batch_id'],
                    'quantity_transferred' => $item['quantity'],
                    'unit_price' => $batch->selling_price,
                    'total_price' => $item_value,
                    'remarks' => isset($item['remarks']) ? $item['remarks'] : ''
                ];
                
                $this->db->insert('stock_transfer_items', $item_data);
                
                // Reduce from source center stock
                $this->db->where('batch_id', $item['batch_id']);
                $this->db->where('center_id', $data['from_center_id']);
                $this->db->set('quantity', 'quantity - ' . $item['quantity'], FALSE);
                $this->db->update('center_stocks');
                
                // Add to destination center stock
                $this->db->where('batch_id', $item['batch_id']);
                $this->db->where('center_id', $data['to_center_id']);
                $existing_dest = $this->db->get('center_stocks')->row();
                
                if($existing_dest) {
                    $this->db->where('batch_id', $item['batch_id']);
                    $this->db->where('center_id', $data['to_center_id']);
                    $this->db->set('quantity', 'quantity + ' . $item['quantity'], FALSE);
                    $this->db->update('center_stocks');
                } else {
                    $this->db->insert('center_stocks', [
                        'batch_id' => $item['batch_id'],
                        'center_id' => $data['to_center_id'],
                        'quantity' => $item['quantity'],
                        'status' => 'ACTIVE',
                        'last_movement_date' => date('Y-m-d H:i:s')
                    ]);
                }
                
                // Log stock movement
                $movement_data = [
                    'batch_id' => $item['batch_id'],
                    'movement_type' => 'TRANSFER_OUT',
                    'from_location_type' => 'CENTER',
                    'from_location_id' => $data['from_center_id'],
                    'to_location_type' => 'CENTER',
                    'to_location_id' => $data['to_center_id'],
                    'quantity_change' => -$item['quantity'],
                    'unit_price' => $batch->selling_price,
                    'total_value' => $item_value,
                    'reference_type' => 'STOCK_TRANSFER',
                    'reference_id' => $transfer_id,
                    'reference_number' => $transfer_number,
                    'created_by' => $data['transferred_by']
                ];
                $this->db->insert('stock_movements', $movement_data);
            }
            
            // Update transfer total value
            $this->db->where('id', $transfer_id);
            $this->db->update('stock_transfers', ['total_value' => $total_value]);
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            return true;
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            error_log('Multi-transfer error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function get_employees_by_location($center_id, $department) {
        $result = array();
        $sql_condition = '';
        
        // First try to get employees for specific center and department
        $sql = "Select employee_number, name from ".$this->config->item('db_prefix')."employees where center_id='".$center_id."' and department='".$department."' and status='1' ORDER BY name ASC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        
        // If no employees found for this center and department, get all employees for this center
        if (empty($result)) {
            $sql = "Select employee_number, name from ".$this->config->item('db_prefix')."employees where center_id='".$center_id."' and status='1' ORDER BY name ASC";
            $q = $this->db->query($sql);
            $result = $q->result_array();
        }
        
        // If still no employees found, get all active employees
        if (empty($result)) {
            $sql = "Select employee_number, name from ".$this->config->item('db_prefix')."employees where status='1' ORDER BY name ASC";
            $q = $this->db->query($sql);
            $result = $q->result_array();
        }
        
        return $result;
    }
    
    public function get_departments_by_center($center_id) {
        $result = array();
        $sql_condition = '';
        
        // Get all unique departments regardless of center
        $sql = "Select DISTINCT department from ".$this->config->item('db_prefix')."employees where status='1' and department != '' ORDER BY department ASC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        
        return $result;
    }
    
    public function get_stocks_by_location($center_id, $department, $employee_number) {
        $this->db->select('mb.*, m.medicine_name, m.medicine_code, mb2.name as brand_name, v.vendor_name');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->join($this->config->item('db_prefix') . 'brands mb2', 'm.brand_id = mb2.ID');
        $this->db->join('vendors v', 'mb.vendor_id = v.id', 'left');
        $this->db->where('mb.center_id', $center_id);
        $this->db->where('mb.department', $department);
        $this->db->where('mb.employee_number', $employee_number);
        $this->db->where('mb.batch_status', 'ACTIVE');
        $this->db->where('mb.available_quantity >', 0);
        $this->db->order_by('m.medicine_name', 'ASC');
        return $this->db->get()->result();
    }
    
    public function add_or_update_destination_batch($source_batch, $transfer_data, $quantity) {
        // Check if batch already exists at destination
        $existing_batch = $this->get_batch_by_location($source_batch->id, $transfer_data['to_center_id'], $transfer_data['to_department'], $transfer_data['to_employee_number']);
        
        if($existing_batch) {
            // Update existing batch
            $this->db->where('id', $existing_batch->id);
            $this->db->set('available_quantity', 'available_quantity + ' . $quantity, FALSE);
            $this->db->set('total_quantity', 'total_quantity + ' . $quantity, FALSE);
            $this->db->update('medicine_batches');
        } else {
            // Create new batch at destination
            $new_batch_data = [
                'medicine_id' => $source_batch->medicine_id,
                'batch_number' => $source_batch->batch_number,
                'vendor_id' => $source_batch->vendor_id,
                'purchase_price' => $source_batch->purchase_price,
                'selling_price' => $source_batch->selling_price,
                'available_quantity' => $quantity,
                'total_quantity' => $quantity,
                'expiry_date' => $source_batch->expiry_date,
                'center_id' => $transfer_data['to_center_id'],
                'department' => $transfer_data['to_department'],
                'employee_number' => $transfer_data['to_employee_number'],
                'batch_status' => 'ACTIVE',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('medicine_batches', $new_batch_data);
        }
    }
    
    public function get_batch_by_location($original_batch_id, $center_id, $department, $employee_number) {
        $this->db->select('mb.*');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicine_batches mb2', 'mb.medicine_id = mb2.medicine_id AND mb.batch_number = mb2.batch_number');
        $this->db->where('mb2.id', $original_batch_id);
        $this->db->where('mb.center_id', $center_id);
        $this->db->where('mb.department', $department);
        $this->db->where('mb.employee_number', $employee_number);
        $this->db->where('mb.batch_status', 'ACTIVE');
        return $this->db->get()->row();
    }
    
    public function update_center_stock($medicine_id, $center_id, $department, $quantity, $operation) {
        // Check if record exists
        $this->db->where('medicine_id', $medicine_id);
        $this->db->where('center_id', $center_id);
        $this->db->where('department', $department);
        $existing = $this->db->get('center_stocks')->row();
        
        if($existing) {
            // Update existing record
            if($operation == 'ADD') {
                $this->db->set('quantity', 'quantity + ' . $quantity, FALSE);
            } else {
                $this->db->set('quantity', 'quantity - ' . $quantity, FALSE);
            }
            $this->db->where('id', $existing->id);
            $this->db->update('center_stocks');
        } else if($operation == 'ADD') {
            // Create new record
            $stock_data = [
                'medicine_id' => $medicine_id,
                'center_id' => $center_id,
                'department' => $department,
                'quantity' => $quantity,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('center_stocks', $stock_data);
        }
    }

    public function get_central_batches() {
        $this->db->select('mb.*, m.medicine_name, m.medicine_code, mb2.name as brand_name, v.vendor_name, cs.quantity as available_quantity, DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days');
        $this->db->from('medicine_batches mb');
        $this->db->join('central_stocks cs', 'mb.id = cs.batch_id');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->join($this->config->item('db_prefix') . 'brands mb2', 'm.brand_id = mb2.ID');
        $this->db->join('vendors v', 'mb.vendor_id = v.id', 'left');
        $this->db->where('mb.batch_status', 'ACTIVE');
        $this->db->where('cs.quantity >', 0);
        $this->db->where('cs.status', 'ACTIVE');
        
        // Order by FEFO (First Expiry First Out)
        $this->db->order_by('mb.expiry_date', 'ASC');
        $this->db->order_by('m.medicine_name', 'ASC');
        
        return $this->db->get()->result();
    }

    public function process_department_transfer($data) {
        $this->db->trans_start();
        
        try {
            // Generate transfer number
            $transfer_number = 'DTR' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // 1. Create main transfer record
            $transfer_data = [
                'transfer_number' => $transfer_number,
                'transfer_type' => 'CENTER_TO_CENTER',
                'from_center_id' => $data['from_center_id'],
                'to_center_id' => $data['to_center_id'],
                'transfer_date' => $data['transfer_date'],
                'remarks' => $data['remarks'],
                'created_by' => $data['transferred_by'],
                'status' => 'COMPLETED',
                'total_value' => 0
            ];
            
            $this->db->insert('stock_transfers', $transfer_data);
            $transfer_id = $this->db->insert_id();
            
            $total_value = 0;
            
            // 2. Process each transfer item
            foreach($data['transfer_items'] as $item) {
                if(empty($item['batch_id']) || empty($item['quantity']) || $item['quantity'] <= 0) {
                    continue;
                }
                
                // Get batch details
                $batch = $this->get_batch_by_id($item['batch_id']);
                if(!$batch) {
                    throw new Exception('Batch not found: ' . $item['batch_id']);
                }
                
                // Check if sufficient quantity is available at source location
                $source_batch = $this->get_batch_by_location($item['batch_id'], $data['from_center_id'], $data['from_department'], $data['from_employee_number']);
                if(!$source_batch || $source_batch->available_quantity < $item['quantity']) {
                    throw new Exception('Insufficient quantity for batch: ' . $batch->batch_number);
                }
                
                // Calculate item value
                $item_value = $item['quantity'] * $batch->purchase_price;
                $total_value += $item_value;
                
                // Record transfer item
                $item_data = [
                    'transfer_id' => $transfer_id,
                    'batch_id' => $item['batch_id'],
                    'quantity_transferred' => $item['quantity'],
                    'unit_price' => $batch->purchase_price,
                    'total_price' => $item_value,
                    'remarks' => isset($item['remarks']) ? $item['remarks'] : ''
                ];
                
                $this->db->insert('stock_transfer_items', $item_data);
                
                // Deduct from source location
                $this->db->where('id', $item['batch_id']);
                $this->db->where('center_id', $data['from_center_id']);
                $this->db->where('department', $data['from_department']);
                $this->db->where('employee_number', $data['from_employee_number']);
                $this->db->set('available_quantity', 'available_quantity - ' . $item['quantity'], FALSE);
                $this->db->update('medicine_batches');
                
                // Add to destination location
                $this->add_or_update_destination_batch($batch, $data, $item['quantity']);
                
                // Update center stocks
                $this->update_center_stock($batch->medicine_id, $data['from_center_id'], $data['from_department'], $item['quantity'], 'SUBTRACT');
                $this->update_center_stock($batch->medicine_id, $data['to_center_id'], $data['to_department'], $item['quantity'], 'ADD');
            }
            
            // 3. Update transfer totals
            $this->db->where('id', $transfer_id);
            $this->db->update('stock_transfers', [
                'total_items' => count($data['transfer_items']),
                'total_quantity' => array_sum(array_column($data['transfer_items'], 'quantity')),
                'total_value' => $total_value
            ]);
            
            $this->db->trans_complete();
            
            if($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            return true;
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            error_log('Department transfer error: ' . $e->getMessage());
            return false;
        }
    }

    // ===============================================
    // SALES FUNCTIONS
    // ===============================================

    public function get_all_sales() {
        try {
            $this->db->select('s.*, c.center_name');
            $this->db->from('sales s');
            $this->db->join('hms_centers c', 's.center_id = c.ID');
            $this->db->order_by('s.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return array();
        }
    }

    public function add_sale($data) {
        try {
            // Generate sale number
            $data['sale_number'] = 'SALE' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            $result = $this->db->insert('sales', $data);
            if($result) {
                return $this->db->insert_id();
            }
            return false;
        } catch (Exception $e) {
            // If table doesn't exist or has issues, return false
            return false;
        }
    }

    public function get_sale_by_id($id) {
        $this->db->select('s.*, c.center_name');
        $this->db->from('sales s');
        $this->db->join('hms_centers c', 's.center_id = c.ID');
        $this->db->where('s.id', $id);
        return $this->db->get()->row();
    }

    public function get_sale_items($sale_id) {
        $this->db->select('si.*, m.medicine_name, m.medicine_code, mb.name as brand_name, mb2.batch_number, mb2.expiry_date');
        $this->db->from('sale_items si');
        $this->db->join('medicine_batches mb2', 'si.batch_id = mb2.id');
        $this->db->join('medicines m', 'mb2.medicine_id = m.id');
        $this->db->join($this->config->item('db_prefix') . 'brands mb', 'm.brand_id = mb.ID');
        $this->db->where('si.sale_id', $sale_id);
        return $this->db->get()->result();
    }

    public function add_sale_item($data) {
        return $this->db->insert('sale_items', $data);
    }

    public function confirm_sale($id) {
        $this->db->trans_start();
        
        // Update sale status
        $this->db->where('id', $id);
        $this->db->update('sales', ['status' => 'CONFIRMED']);
        
        // Get sale items
        $items = $this->get_sale_items($id);
        $sale = $this->get_sale_by_id($id);
        
        foreach($items as $item) {
            // Reduce center stock using FIFO
            $this->db->where('batch_id', $item->batch_id);
            $this->db->where('center_id', $sale->center_id);
            $this->db->set('quantity', 'quantity - ' . $item->quantity_sold, FALSE);
            $this->db->update('center_stocks');
            
            // Log stock movement
            $movement_data = [
                'batch_id' => $item->batch_id,
                'movement_type' => 'SALE',
                'from_location_type' => 'CENTER',
                'from_location_id' => $sale->center_id,
                'to_location_type' => 'SALE',
                'quantity_change' => -$item->quantity_sold,
                'unit_price' => $item->unit_price,
                'total_value' => $item->total,
                'reference_type' => 'SALES_BILL',
                'reference_id' => $id,
                'reference_number' => $sale->sale_number,
                'patient_id' => $sale->patient_id,
                'patient_name' => $sale->patient_name,
                'created_by' => $sale->created_by
            ];
            $this->db->insert('stock_movements', $movement_data);
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_available_batches_for_sale($center_id) {
        $this->db->select('mb.*, m.medicine_name, m.medicine_code, mb2.name as brand_name, ccs.quantity as available_quantity');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicines m', 'mb.medicine_id = m.id');
        $this->db->join($this->config->item('db_prefix') . 'brands mb2', 'm.brand_id = mb2.ID');
        $this->db->join('center_stocks ccs', 'mb.id = ccs.batch_id');
        $this->db->where('ccs.center_id', $center_id);
        $this->db->where('ccs.quantity >', 0);
        $this->db->where('mb.batch_status', 'ACTIVE');
        $this->db->where('mb.expiry_date >', date('Y-m-d'));
        $this->db->order_by('mb.expiry_date', 'ASC');
        $this->db->order_by('m.medicine_name', 'ASC');
        return $this->db->get()->result();
    }

    // ===============================================
    // REPORTS FUNCTIONS
    // ===============================================

    public function get_sales_report($start_date, $end_date, $center_id = null) {
        try {
            $this->db->select('s.*, c.center_name');
            $this->db->from('sales s');
            $this->db->join('hms_centers c', 's.center_id = c.ID');
            $this->db->where('s.sale_date >=', $start_date);
            $this->db->where('s.sale_date <=', $end_date);
            $this->db->where('s.status', 'CONFIRMED');
            
            if($center_id) {
                $this->db->where('s.center_id', $center_id);
            }
            
            $this->db->order_by('s.sale_date', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return array();
        }
    }

    public function get_transfer_report($start_date, $end_date, $transfer_type = null, $from_center_id = null, $to_center_id = null) {
        try {
            $this->db->select('st.*, fc.center_name as from_center, tc.center_name as to_center');
            $this->db->from('stock_transfers st');
            $this->db->join('hms_centers fc', 'st.from_center_id = fc.ID', 'left');
            $this->db->join('hms_centers tc', 'st.to_center_id = tc.ID');
            $this->db->where('st.transfer_date >=', $start_date);
            $this->db->where('st.transfer_date <=', $end_date);
            $this->db->where('st.status', 'COMPLETED');
            
            if($transfer_type) {
                $this->db->where('st.transfer_type', $transfer_type);
            }
            
            if($from_center_id) {
                $this->db->where('st.from_center_id', $from_center_id);
            }
            
            if($to_center_id) {
                $this->db->where('st.to_center_id', $to_center_id);
            }
            
            $this->db->order_by('st.transfer_date', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return array();
        }
    }

    public function get_available_batches_for_return() {
        try {
            $this->db->select('
                mb.id as batch_id,
                mb.batch_number,
                mb.expiry_date,
                DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                mb.quantity_remaining,
                mb.selling_price,
                m.medicine_name,
                m.medicine_code,
                b.name as brand_name,
                v.name as vendor_name,
                c.center_name
            ');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID');
            $this->db->join('hms_centers c', 'mb.center_id = c.ID', 'left');
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('mb.quantity_remaining >', 0);
            $this->db->order_by('mb.expiry_date', 'ASC');
            $this->db->order_by('m.medicine_name', 'ASC');
            
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return array();
        }
    }

    public function get_available_batches_for_audit() {
        try {
            $this->db->select('
                mb.id as batch_id,
                mb.batch_number,
                mb.expiry_date,
                DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                mb.quantity_remaining,
                mb.selling_price,
                m.medicine_name,
                m.medicine_code,
                b.name as brand_name,
                v.name as vendor_name,
                c.center_name
            ');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID');
            $this->db->join('hms_centers c', 'mb.center_id = c.ID', 'left');
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('mb.quantity_remaining >', 0);
            $this->db->order_by('mb.expiry_date', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return array();
        }
    }

    public function get_available_batches_for_disposal() {
        try {
            $this->db->select('
                mb.id as batch_id,
                mb.batch_number,
                mb.expiry_date,
                DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                mb.quantity_remaining,
                mb.selling_price,
                m.medicine_name,
                m.medicine_code,
                b.name as brand_name,
                v.name as vendor_name,
                c.center_name
            ');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID');
            $this->db->join('hms_centers c', 'mb.center_id = c.ID', 'left');
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('mb.quantity_remaining >', 0);
            $this->db->order_by('mb.expiry_date', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            // If tables don't exist or have issues, return empty array
            return array();
        }
    }

    public function get_audit_reports() {
        try {
            $this->db->select('ar.*, c.center_name');
            $this->db->from('audit_reports ar');
            $this->db->join('hms_centers c', 'ar.center_id = c.ID');
            $this->db->order_by('ar.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_disposal_reports() {
        try {
            $this->db->select('dr.*, c.center_name');
            $this->db->from('disposal_reports dr');
            $this->db->join('hms_centers c', 'dr.center_id = c.ID');
            $this->db->order_by('dr.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_invoices() {
        try {
            $this->db->select('i.*, v.name as vendor_name, c.center_name');
            $this->db->from('invoices i');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'i.vendor_id = v.ID');
            $this->db->join('hms_centers c', 'i.center_id = c.ID', 'left');
            $this->db->order_by('i.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_available_batches_for_invoice() {
        try {
            $this->db->select('
                mb.id as batch_id,
                mb.batch_number,
                mb.expiry_date,
                DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                mb.quantity_remaining,
                mb.selling_price,
                m.medicine_name,
                m.medicine_code,
                b.name as brand_name,
                v.name as vendor_name,
                c.center_name
            ');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID');
            $this->db->join('hms_centers c', 'mb.center_id = c.ID', 'left');
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('mb.quantity_remaining >', 0);
            $this->db->order_by('mb.expiry_date', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_categories() {
        try {
            $this->db->where('status', 'active');
            $this->db->order_by('category_name', 'ASC');
            return $this->db->get('medicine_categories')->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_generic_names() {
        try {
            $this->db->select('
                gn.id,
                gn.generic_name,
                gn.generic_code,
                gn.description,
                gn.therapeutic_class,
                gn.status,
                gn.created_at,
                gn.updated_at,
                mc.category_name,
                COALESCE(med_count.medicines_count, 0) as medicines_count
            ');
            $this->db->from('generic_names gn');
            $this->db->join('medicine_categories mc', 'gn.category_id = mc.id', 'left');
            $this->db->join('(
                SELECT generic_id, COUNT(*) as medicines_count 
                FROM medicines 
                WHERE status = "active" 
                GROUP BY generic_id
            ) med_count', 'gn.id = med_count.generic_id', 'left');
            $this->db->order_by('gn.generic_name', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_vendor_returns() {
        try {
            $this->db->select('vr.*, v.name as vendor_name, c.center_name');
            $this->db->from('vendor_returns vr');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'vr.vendor_id = v.ID');
            $this->db->join('hms_centers c', 'vr.center_id = c.ID', 'left');
            $this->db->order_by('vr.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_vendor_return_reports($vendor_id = null, $status = null, $from_date = null, $to_date = null) {
        try {
            $this->db->select('vr.*, v.name as vendor_name, c.center_name');
            $this->db->from('vendor_returns vr');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'vr.vendor_id = v.ID');
            $this->db->join('hms_centers c', 'vr.center_id = c.ID', 'left');
            
            // Apply filters
            if($vendor_id) {
                $this->db->where('vr.vendor_id', $vendor_id);
            }
            if($status) {
                $this->db->where('vr.status', $status);
            }
            if($from_date) {
                $this->db->where('vr.return_date >=', $from_date);
            }
            if($to_date) {
                $this->db->where('vr.return_date <=', $to_date);
            }
            
            $this->db->order_by('vr.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_vendor_return_summary_stats($vendor_id = null, $status = null, $from_date = null, $to_date = null) {
        try {
            $this->db->select('
                COUNT(*) as total_returns,
                SUM(CASE WHEN status = "PENDING" THEN 1 ELSE 0 END) as pending_returns,
                SUM(CASE WHEN status = "APPROVED" THEN 1 ELSE 0 END) as approved_returns,
                SUM(CASE WHEN status = "COMPLETED" THEN 1 ELSE 0 END) as completed_returns,
                SUM(CASE WHEN status = "REJECTED" THEN 1 ELSE 0 END) as rejected_returns,
                SUM(total_items) as total_items_returned,
                SUM(total_quantity) as total_quantity_returned,
                SUM(total_value) as total_value_returned,
                AVG(total_value) as avg_return_value
            ');
            $this->db->from('vendor_returns');
            
            // Apply filters
            if($vendor_id) {
                $this->db->where('vendor_id', $vendor_id);
            }
            if($status) {
                $this->db->where('status', $status);
            }
            if($from_date) {
                $this->db->where('return_date >=', $from_date);
            }
            if($to_date) {
                $this->db->where('return_date <=', $to_date);
            }
            
            return $this->db->get()->row();
        } catch (Exception $e) {
            return (object) array(
                'total_returns' => 0,
                'pending_returns' => 0,
                'approved_returns' => 0,
                'completed_returns' => 0,
                'rejected_returns' => 0,
                'total_items_returned' => 0,
                'total_quantity_returned' => 0,
                'total_value_returned' => 0,
                'avg_return_value' => 0
            );
        }
    }

    public function get_available_batches_for_vendor_return() {
        try {
            $this->db->select('
                mb.id as batch_id,
                mb.batch_number,
                mb.expiry_date,
                DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                mb.quantity_remaining,
                mb.selling_price,
                m.medicine_name,
                m.medicine_code,
                b.name as brand_name,
                v.name as vendor_name,
                c.center_name
            ');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID');
            $this->db->join('hms_centers c', 'mb.center_id = c.ID', 'left');
            $this->db->where('mb.batch_status', 'ACTIVE');
            $this->db->where('mb.quantity_remaining >', 0);
            $this->db->order_by('mb.expiry_date', 'ASC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    // ===============================================
    // PURCHASE ORDER BATCH TRACKING METHODS
    // ===============================================

    public function get_purchase_order_details($po_id) {
        try {
            $this->db->select('*');
            $this->db->from('purchase_orders');
            $this->db->where('id', $po_id);
            return $this->db->get()->row();
        } catch (Exception $e) {
            return null;
        }
    }

    public function get_batches_from_purchase_order($po_id) {
        try {
            $this->db->select('
                mb.*,
                m.medicine_name,
                m.medicine_code,
                v.name as vendor_name,
                vb.purchase_po_no,
                vb.po_date,
                vb.invoice_no,
                vb.date_of_purchase
            ');
            $this->db->from('medicine_batches mb');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join('vendors v', 'mb.vendor_id = v.id');
            $this->db->join('vendor_billing vb', 'mb.batch_number = vb.batch_number AND mb.vendor_id = vb.vendor_code', 'left');
            $this->db->where('vb.purchase_po_no', $po_id);
            $this->db->order_by('mb.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_po_batch_summary($po_id) {
        try {
            $batches = $this->get_batches_from_purchase_order($po_id);
            
            $summary = array(
                'total_batches' => count($batches),
                'total_quantity_received' => 0,
                'total_quantity_remaining' => 0,
                'total_quantity_distributed' => 0,
                'total_value' => 0,
                'batches_in_central' => 0,
                'batches_transferred' => 0,
                'batches_sold' => 0
            );
            
            foreach($batches as $batch) {
                $summary['total_quantity_received'] += $batch->quantity_purchased;
                $summary['total_quantity_remaining'] += $batch->quantity_remaining;
                $summary['total_quantity_distributed'] += ($batch->quantity_purchased - $batch->quantity_remaining);
                $summary['total_value'] += ($batch->quantity_purchased * $batch->purchase_price);
                
                // Check where this batch is located
                $movements = $this->get_stock_movements_by_batch($batch->id);
                $has_transfers = false;
                $has_sales = false;
                
                foreach($movements as $movement) {
                    if($movement->movement_type == 'Out' && $movement->to_center) {
                        $has_transfers = true;
                    }
                    if($movement->patient_name) {
                        $has_sales = true;
                    }
                }
                
                if($has_sales) {
                    $summary['batches_sold']++;
                } elseif($has_transfers) {
                    $summary['batches_transferred']++;
                } else {
                    $summary['batches_in_central']++;
                }
            }
            
            return $summary;
        } catch (Exception $e) {
            return array();
        }
    }

    // ===============================================
    // STOCK TRACKING METHODS
    // ===============================================

    public function get_stock_movements() {
        try {
            $this->db->select('
                sm.*,
                m.medicine_name,
                m.medicine_code,
                mb.batch_number,
                fc.center_name as from_center,
                tc.center_name as to_center
            ');
            $this->db->from('stock_movements sm');
            $this->db->join('medicine_batches mb', 'sm.batch_id = mb.id');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join('hms_centers fc', 'sm.from_location_id = fc.ID', 'left');
            $this->db->join('hms_centers tc', 'sm.to_location_id = tc.ID', 'left');
            $this->db->order_by('sm.created_at', 'DESC');
            $this->db->limit(100);
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_stock_movements_by_batch($batch_id) {
        try {
            $this->db->select('
                sm.*,
                m.medicine_name,
                m.medicine_code,
                mb.batch_number,
                mb.expiry_date,
                fc.center_name as from_center,
                tc.center_name as to_center
            ');
            $this->db->from('stock_movements sm');
            $this->db->join('medicine_batches mb', 'sm.batch_id = mb.id');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join('hms_centers fc', 'sm.from_location_id = fc.ID', 'left');
            $this->db->join('hms_centers tc', 'sm.to_location_id = tc.ID', 'left');
            $this->db->where('sm.batch_id', $batch_id);
            $this->db->order_by('sm.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function search_stock_movements($filters) {
        try {
            $this->db->select('
                sm.*,
                m.medicine_name,
                m.medicine_code,
                mb.batch_number,
                fc.center_name as from_center,
                tc.center_name as to_center
            ');
            $this->db->from('stock_movements sm');
            $this->db->join('medicine_batches mb', 'sm.batch_id = mb.id');
            $this->db->join('medicines m', 'mb.medicine_id = m.id');
            $this->db->join('hms_centers fc', 'sm.from_location_id = fc.ID', 'left');
            $this->db->join('hms_centers tc', 'sm.to_location_id = tc.ID', 'left');
            
            if(!empty($filters['medicine_id'])) {
                $this->db->where('m.id', $filters['medicine_id']);
            }
            if(!empty($filters['batch_id'])) {
                $this->db->where('mb.id', $filters['batch_id']);
            }
            if(!empty($filters['center_id'])) {
                $this->db->where('(sm.from_location_id = ' . $filters['center_id'] . ' OR sm.to_location_id = ' . $filters['center_id'] . ')');
            }
            if(!empty($filters['date_from'])) {
                $this->db->where('sm.created_at >=', $filters['date_from']);
            }
            if(!empty($filters['date_to'])) {
                $this->db->where('sm.created_at <=', $filters['date_to']);
            }
            
            $this->db->order_by('sm.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_summary_stats() {
        try {
            $stats = array();
            
            // Total transfers
            $this->db->select('COUNT(*) as total');
            $this->db->from('stock_transfers');
            $this->db->where('status', 'COMPLETED');
            $result = $this->db->get()->row();
            $stats['total_transfers'] = $result ? $result->total : 0;
            
            // Total sales
            $this->db->select('COUNT(*) as total');
            $this->db->from('sales');
            $this->db->where('status', 'CONFIRMED');
            $result = $this->db->get()->row();
            $stats['total_sales'] = $result ? $result->total : 0;
            
            // Active batches
            $this->db->select('COUNT(*) as total');
            $this->db->from('medicine_batches');
            $this->db->where('batch_status', 'ACTIVE');
            $this->db->where('quantity_remaining >', 0);
            $result = $this->db->get()->row();
            $stats['active_batches'] = $result ? $result->total : 0;
            
            // Expiring batches (within 30 days)
            $this->db->select('COUNT(*) as total');
            $this->db->from('medicine_batches');
            $this->db->where('batch_status', 'ACTIVE');
            $this->db->where('quantity_remaining >', 0);
            $this->db->where('DATEDIFF(expiry_date, CURDATE()) <=', 30);
            $this->db->where('DATEDIFF(expiry_date, CURDATE()) >=', 0);
            $result = $this->db->get()->row();
            $stats['expiring_batches'] = $result ? $result->total : 0;
            
            return $stats;
        } catch (Exception $e) {
            return array(
                'total_transfers' => 0,
                'total_sales' => 0,
                'active_batches' => 0,
                'expiring_batches' => 0
            );
        }
    }

    public function export_stock_report($filters) {
        try {
            $movements = $this->search_stock_movements($filters);
            
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="stock_movements_report_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($output, array(
                'Date',
                'Medicine Name',
                'Medicine Code',
                'Batch Number',
                'Movement Type',
                'From Center',
                'To Center',
                'Quantity Change',
                'Unit Price',
                'Total Value',
                'Reference Number',
                'Status'
            ));
            
            // CSV data
            foreach($movements as $movement) {
                fputcsv($output, array(
                    $movement->movement_date,
                    $movement->medicine_name,
                    $movement->medicine_code,
                    $movement->batch_number,
                    $movement->movement_type,
                    $movement->from_center,
                    $movement->to_center,
                    $movement->quantity_change,
                    $movement->unit_price,
                    $movement->total_value,
                    $movement->reference_number,
                    $movement->status
                ));
            }
            
            fclose($output);
        } catch (Exception $e) {
            echo "Error exporting report: " . $e->getMessage();
        }
    }

    public function process_medicine_return($return_data, $return_items) {
        try {
            $this->db->trans_start();
            
            // Insert return record
            $return_data['return_number'] = 'RET' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $this->db->insert('medicine_returns', $return_data);
            $return_id = $this->db->insert_id();
            
            if($return_id && !empty($return_items)) {
                // Insert return items
                foreach($return_items as $item) {
                    $item_data = [
                        'return_id' => $return_id,
                        'batch_id' => $item['batch_id'],
                        'quantity_returned' => $item['quantity'],
                        'return_price' => $item['price'],
                        'total_amount' => $item['quantity'] * $item['price']
                    ];
                    $this->db->insert('medicine_return_items', $item_data);
                    
                    // Update batch quantity
                    $this->db->set('quantity_remaining', 'quantity_remaining + ' . $item['quantity'], FALSE);
                    $this->db->where('id', $item['batch_id']);
                    $this->db->update('medicine_batches');
                }
            }
            
            $this->db->trans_complete();
            return $this->db->trans_status();
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return false;
        }
    }

    public function get_medicine_returns() {
        try {
            $this->db->select('mr.*, c.center_name');
            $this->db->from('medicine_returns mr');
            $this->db->join('hms_centers c', 'mr.center_id = c.ID');
            $this->db->order_by('mr.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }

    // ===============================================
    // CENTERS FUNCTIONS
    // ===============================================

    public function get_all_centers() {
        try {
            $this->db->where('status', '1');
            $this->db->order_by('center_name', 'ASC');
            return $this->db->get($this->config->item('db_prefix') . 'centers')->result();
        } catch (Exception $e) {
            // If centers table doesn't exist, return empty array
            return array();
        }
    }

    public function add_center($data) {
        return $this->db->insert($this->config->item('db_prefix') . 'centers', $data);
    }

    public function update_center($id, $data) {
        $this->db->where('ID', $id);
        return $this->db->update($this->config->item('db_prefix') . 'centers', $data);
    }

    public function get_center_by_id($id) {
        $this->db->where('ID', $id);
        return $this->db->get($this->config->item('db_prefix') . 'centers')->row();
    }

    // ===============================================
    // MISSING MODEL METHODS
    // ===============================================

    public function add_category($data) {
        try {
            return $this->db->insert('medicine_categories', $data);
        } catch (Exception $e) {
            return false;
        }
    }

    public function update_category($id, $data) {
        try {
            $this->db->where('id', $id);
            return $this->db->update('medicine_categories', $data);
        } catch (Exception $e) {
            return false;
        }
    }

    public function update_category_status($id, $status) {
        try {
            $this->db->where('id', $id);
            return $this->db->update('medicine_categories', array('status' => $status));
        } catch (Exception $e) {
            return false;
        }
    }

    public function add_generic_name($data) {
        try {
            return $this->db->insert('generic_names', $data);
        } catch (Exception $e) {
            return false;
        }
    }

    public function update_generic_name($id, $data) {
        try {
            $this->db->where('id', $id);
            return $this->db->update('generic_names', $data);
        } catch (Exception $e) {
            return false;
        }
    }

    public function update_generic_name_status($id, $status) {
        try {
            $this->db->where('id', $id);
            return $this->db->update('generic_names', array('status' => $status));
        } catch (Exception $e) {
            return false;
        }
    }

    public function add_invoice($data) {
        try {
            return $this->db->insert('invoices', $data);
        } catch (Exception $e) {
            return false;
        }
    }

    public function add_vendor_return($data) {
        try {
            return $this->db->insert('vendor_returns', $data);
        } catch (Exception $e) {
            return false;
        }
    }

    // ===============================================
    // ADDITIONAL BRAND MANAGEMENT METHODS
    // ===============================================

    public function get_all_brands() {
        try {
            $this->db->order_by('brand_name', 'ASC');
            return $this->db->get('medicine_brands')->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_active_brands() {
        try {
            $this->db->where('status', 'active');
            $this->db->order_by('brand_name', 'ASC');
            return $this->db->get('medicine_brands')->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function search_brands($search_term) {
        try {
            $this->db->like('brand_name', $search_term);
            $this->db->or_like('manufacturer', $search_term);
            $this->db->or_like('contact_person', $search_term);
            $this->db->where('status', 'active');
            $this->db->order_by('brand_name', 'ASC');
            return $this->db->get('medicine_brands')->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_brand_statistics() {
        try {
            $stats = array();
            
            // Total brands
            $this->db->select('COUNT(*) as total');
            $this->db->from('medicine_brands');
            $result = $this->db->get()->row();
            $stats['total_brands'] = $result ? $result->total : 0;
            
            // Active brands
            $this->db->select('COUNT(*) as total');
            $this->db->from('medicine_brands');
            $this->db->where('status', 'active');
            $result = $this->db->get()->row();
            $stats['active_brands'] = $result ? $result->total : 0;
            
            // Inactive brands
            $this->db->select('COUNT(*) as total');
            $this->db->from('medicine_brands');
            $this->db->where('status', 'inactive');
            $result = $this->db->get()->row();
            $stats['inactive_brands'] = $result ? $result->total : 0;
            
            return $stats;
        } catch (Exception $e) {
            return array(
                'total_brands' => 0,
                'active_brands' => 0,
                'inactive_brands' => 0
            );
        }
    }

    // ===============================================
    // ADDITIONAL VENDOR MANAGEMENT METHODS
    // ===============================================

    public function get_all_vendors() {
        try {
            $this->db->order_by('vendor_name', 'ASC');
            return $this->db->get('vendors')->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_active_vendors() {
        try {
            $this->db->where('status', 'active');
            $this->db->order_by('vendor_name', 'ASC');
            return $this->db->get('vendors')->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function search_vendors($search_term) {
        try {
            $this->db->like('vendor_name', $search_term);
            $this->db->or_like('contact_person', $search_term);
            $this->db->or_like('email', $search_term);
            $this->db->where('status', 'active');
            $this->db->order_by('vendor_name', 'ASC');
            return $this->db->get('vendors')->result();
        } catch (Exception $e) {
            return array();
        }
    }

    public function get_vendor_statistics() {
        try {
            $stats = array();
            
            // Total vendors
            $this->db->select('COUNT(*) as total');
            $this->db->from('vendors');
            $result = $this->db->get()->row();
            $stats['total_vendors'] = $result ? $result->total : 0;
            
            // Active vendors
            $this->db->select('COUNT(*) as total');
            $this->db->from('vendors');
            $this->db->where('status', 'active');
            $result = $this->db->get()->row();
            $stats['active_vendors'] = $result ? $result->total : 0;
            
            // Inactive vendors
            $this->db->select('COUNT(*) as total');
            $this->db->from('vendors');
            $this->db->where('status', 'inactive');
            $result = $this->db->get()->row();
            $stats['inactive_vendors'] = $result ? $result->total : 0;
            
            // Total credit limit
            $this->db->select('SUM(credit_limit) as total');
            $this->db->from('vendors');
            $this->db->where('status', 'active');
            $result = $this->db->get()->row();
            $stats['total_credit_limit'] = $result ? $result->total : 0;
            
            return $stats;
        } catch (Exception $e) {
            return array(
                'total_vendors' => 0,
                'active_vendors' => 0,
                'inactive_vendors' => 0,
                'total_credit_limit' => 0
            );
        }
    }

    public function get_vendor_purchase_summary($vendor_id, $start_date = null, $end_date = null) {
        try {
            $this->db->select('
                v.name as vendor_name,
                COUNT(mb.id) as total_batches,
                SUM(mb.quantity_purchased) as total_quantity,
                SUM(mb.quantity_purchased * mb.purchase_price) as total_value,
                AVG(mb.purchase_price) as avg_purchase_price
            ');
            $this->db->from('vendors v');
            $this->db->join('medicine_batches mb', 'v.ID = mb.vendor_id');
            $this->db->where('v.id', $vendor_id);
            $this->db->where('mb.batch_status', 'ACTIVE');
            
            if($start_date) {
                $this->db->where('mb.purchase_date >=', $start_date);
            }
            if($end_date) {
                $this->db->where('mb.purchase_date <=', $end_date);
            }
            
            return $this->db->get()->row();
        } catch (Exception $e) {
            return null;
        }
    }

    public function get_brand_medicine_count($brand_id) {
        try {
            $this->db->select('COUNT(*) as total');
            $this->db->from('medicines');
            $this->db->where('brand_id', $brand_id);
            $this->db->where('status', 'active');
            $result = $this->db->get()->row();
            return $result ? $result->total : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    // ==================== PURCHASE ORDER INTEGRATION METHODS ====================
    
    /**
     * Get purchase orders ready for stock addition (approved/completed status)
     */
    public function get_purchase_orders_for_stock_addition() {
        try {
            $this->db->select('po.*, v.name as vendor_name');
            $this->db->from('purchase_orders po');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'po.vendor_number = v.vendor_number', 'left');
            $this->db->where('po.status', 'completed');
            $this->db->where('po.stock_added', 0); // Not yet added to stock
            $this->db->order_by('po.created_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }
    
    /**
     * Get purchase order items for stock addition
     */
    public function get_purchase_order_items($po_id) {
        try {
            $this->db->select('poi.*, m.medicine_name, m.medicine_code, m.generic_name, b.name as brand_name');
            $this->db->from('purchase_order_items poi');
            $this->db->join('medicines m', 'poi.item_number = m.medicine_code', 'left');
            $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID', 'left');
            $this->db->where('poi.po_id', $po_id);
            $this->db->where('poi.quantity >', 0);
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }
    
    /**
     * Add stock from purchase order (main method) - Following original logic
     */
    public function add_stock_from_purchase_order($po_id, $stock_items) {
        try {
            $this->db->trans_start();
            
            $success_count = 0;
            $total_items = count($stock_items);
            
            foreach ($stock_items as $item) {
                // Prepare stock data following original structure
                $stock_data = array(
                    'item_name' => $item['item_name'],
                    'company' => $item['company'],
                    'brand_name' => $item['brand_name'],
                    'generic_name' => $item['generic_name'] ?: '',
                    'vendor_number' => $item['vendor_number'],
                    'batch_number' => $item['batch_number'],
                    'quantity' => $item['quantity_received'] + ($item['free_quantity'] ?: 0),
                    'price' => $item['purchase_price'],
                    'vendor_price' => $item['purchase_price'],
                    'mrp' => $item['mrp'],
                    'hsn' => $item['hsn'],
                    'pack_size' => $item['pack_size'],
                    'gstrate' => intval($item['tax_percentage']),
                    'gstdivision' => 0,
                    'expiry' => $item['expiry_date'],
                    'expiry_day' => $item['manufacturing_date'],
                    'date_of_purchase' => $item['receipt_date'],
                    'invoice_no' => $item['invoice_number'] ?: 'N/A',
                    'no_of_item' => '1',
                    'product_id' => 0,
                    'lots' => 1.0,
                    'units' => $item['quantity_received'] + ($item['free_quantity'] ?: 0),
                    'safety_stock' => 0,
                    'order_qty' => 0,
                    'category' => 0,
                    'pack' => 1,
                    'type' => 'medicine',
                    'medicine_type' => null,
                    'status' => 1
                );
                
                // Check for existing stock item (following original logic)
                $existing_stock = $this->check_existing_stock_item($item['item_name'], $item['batch_number'], $item['vendor_number']);
                
                if ($existing_stock) {
                    // Update existing stock quantity
                    $update_result = $this->update_stock_quantity($existing_stock['ID'], $stock_data['quantity'], $stock_data);
                    if ($update_result) {
                        $success_count++;
                    }
                } else {
                    // Insert new stock item
                    $insert_result = $this->insert_stock_item($stock_data);
                    if ($insert_result) {
                        $success_count++;
                    }
                }
                
                // Create vendor billing record
                $this->create_vendor_billing_record($po_id, $item, 0);
            }
            
            // Mark purchase order as stock added
            $this->db->where('id', $po_id);
            $this->db->update('purchase_orders', array('stock_added' => 1, 'stock_added_at' => date('Y-m-d H:i:s')));
            
            $this->db->trans_complete();
            
            return array(
                'success' => $this->db->trans_status(),
                'success_count' => $success_count,
                'total_items' => $total_items
            );
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array(
                'success' => false,
                'error' => $e->getMessage(),
                'success_count' => 0,
                'total_items' => 0
            );
        }
    }
    
    /**
     * Check if stock item exists (by batch number and vendor) - Following original logic
     */
    public function check_existing_stock_item($item_name, $batch_number, $vendor_number) {
        try {
            $this->db->select('*');
            $this->db->from($this->config->item('db_prefix') . 'stocks');
            $this->db->where('item_name', $item_name);
            $this->db->where('batch_number', $batch_number);
            $this->db->where('vendor_number', $vendor_number);
            $query = $this->db->get();
            
            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Update existing stock quantity - Following original logic
     */
    public function update_stock_quantity($stock_id, $quantity, $stock_data = []) {
        try {
            $sql = "UPDATE `" . $this->config->item('db_prefix') . "stocks` SET `quantity` = `quantity` + {$quantity}";
            foreach ($stock_data as $key => $value) {
                if ($key != 'quantity' && $key != 'add_date' && $key != 'status') {
                    $sql .= ", `{$key}` = '" . addslashes($value) . "'";
                }
            }
            $sql .= " WHERE `ID` = '{$stock_id}'";
            $this->db->query($sql);
            return $this->db->affected_rows() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Insert new stock item - Following original logic
     */
    public function insert_stock_item($stock_data) {
        try {
            $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "stocks` SET ";
            $sqlArr = array();
            
            foreach ($stock_data as $key => $value) {
                $sqlArr[] = " $key = '" . addslashes($value) . "'";
            }
            
            $date = date("Y-m-d H:i:s");
            $sqlArr[] = " add_date = '" . addslashes($date) . "'";
            $sqlArr[] = " item_number = '" . addslashes(getGUID()) . "'";
            
            $sql .= implode(',', $sqlArr);
            $res = $this->db->query($sql);
            
            if ($res) {
                return $this->db->insert_id();
            } else {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Create vendor billing record - Following original logic
     */
    private function create_vendor_billing_record($po_id, $item, $batch_id) {
        try {
            $billing_data = array(
                'purchase_po_no' => $po_id,
                'po_date' => $item['receipt_date'],
                'vendor_name' => $this->get_vendor_name($item['vendor_number']),
                'vendor_code' => $item['vendor_number'],
                'order_number' => $po_id,
                'upload_date' => date("Y-m-d H:i:s"),
                'invoice_no' => $item['invoice_number'] ?: 'N/A',
                'brand_name' => $item['brand_name'],
                'mrp' => floatval($item['mrp']),
                'hsn' => $item['hsn'],
                'category' => $item['company'],
                'date_of_purchase' => $item['receipt_date'],
                'batch_number' => $item['batch_number'],
                'centre_location' => 'Central',
                'received_by' => $item['received_by'],
                'date_of_receiving' => $item['receipt_date'],
                'item_number' => $item['item_number'],
                'item_name' => $item['item_name'],
                'company' => $item['company'],
                'quantity' => $item['quantity_received'],
                'expiry' => $item['expiry_date'],
                'vendor_price' => $item['purchase_price'],
                'gstrate' => floatval($item['tax_percentage']),
                'discount_amt' => $item['discount_amount'] ?: 0,
                'free_quantity' => $item['free_quantity'] ?: 0,
                'total_purchase_value_excl_gst' => ($item['quantity_received'] * $item['purchase_price']),
                'freight_forwarding_charges' => 0,
                'comment' => $item['comments'] ?: '',
                'vendor_billing' => '',
                'rate_per_unit' => $item['purchase_price'],
                'total_purchase_after_discount_exculding_gst' => ($item['quantity_received'] * $item['purchase_price']),
                'total_purchase_value_incl_gst' => ($item['quantity_received'] * $item['purchase_price'] * (1 + floatval($item['tax_percentage']) / 100)),
                'monetary_value' => 'INR',
                'discount_rate' => '0',
                'entry_date_in_tally' => null,
                'msme_applicability' => 'No',
                'medicine_type' => null
            );
            
            return $this->db->insert('vendor_billing', $billing_data);
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Helper method to get vendor name - Following original logic
     */
    private function get_vendor_name($vendor_number) {
        try {
            if ($vendor_number == 'Cash Purchase') {
                return 'Cash Purchase';
            }
            
            $this->db->select('name');
            $this->db->from($this->config->item('db_prefix') . 'vendors');
            $this->db->where('vendor_number', $vendor_number);
            $query = $this->db->get();
            
            if ($query->num_rows() > 0) {
                return $query->row()->name;
            }
            return $vendor_number;
        } catch (Exception $e) {
            return $vendor_number;
        }
    }
    
    /**
     * Get purchase order details for stock addition
     */
    public function get_purchase_order_for_stock_addition($po_id) {
        try {
            $this->db->select('po.*, v.name as vendor_name');
            $this->db->from('purchase_orders po');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'po.vendor_number = v.vendor_number', 'left');
            $this->db->where('po.id', $po_id);
            $this->db->where('po.status', 'completed');
            return $this->db->get()->row();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Check if purchase order items exist
     */
    public function check_purchase_order_items_exist($po_id) {
        try {
            $this->db->where('po_id', $po_id);
            $this->db->where('quantity >', 0);
            $count = $this->db->count_all_results('purchase_order_items');
            return $count > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get processed purchase orders (for history)
     */
    public function get_processed_purchase_orders() {
        try {
            $this->db->select('po.*, v.name as vendor_name');
            $this->db->from('purchase_orders po');
            $this->db->join($this->config->item('db_prefix') . 'vendors v', 'po.vendor_number = v.vendor_number', 'left');
            $this->db->where('po.stock_added', 1);
            $this->db->order_by('po.stock_added_at', 'DESC');
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }
    
    /**
     * Get available stocks for transfer based on transfer type and source location
     */
    public function get_available_stocks_for_transfer($transfer_type, $from_center_id = null, $from_department = null, $from_employee_number = null) {
        try {
            if($transfer_type == 'CENTRAL_TO_CENTER') {
                $this->db->select('
                    mb.id as batch_id,
                    mb.batch_number,
                    mb.expiry_date,
                    cs.quantity as quantity_remaining,
                    mb.batch_status,
                    mb.purchase_price,
                    mb.selling_price,
                    DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                    m.id as medicine_id,
                    m.medicine_name,
                    m.medicine_code,
                    b.name as brand_name,
                    v.name as vendor_name,
                    "CENTRAL" as center_name,
                    CASE
                        WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
                        WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "EXPIRING_SOON"
                        ELSE "FRESH"
                    END as expiry_status
                ');
                
                $this->db->from('medicine_batches mb');
                $this->db->join('central_stocks cs', 'mb.id = cs.batch_id');
                $this->db->join('medicines m', 'mb.medicine_id = m.id');
                $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID', 'left');
                $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID', 'left');
                
                // Only show available central stocks
                $this->db->where('mb.batch_status', 'ACTIVE');
                $this->db->where('cs.quantity >', 0);
                $this->db->where('cs.status', 'ACTIVE');
                
            } elseif($transfer_type == 'CENTER_TO_CENTER') {
                // Center stocks - join with center_stocks table
                $this->db->select('
                    mb.id as batch_id,
                    mb.batch_number,
                    mb.expiry_date,
                    ccs.quantity as quantity_remaining,
                    mb.batch_status,
                    mb.purchase_price,
                    mb.selling_price,
                    DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                    m.id as medicine_id,
                    m.medicine_name,
                    m.medicine_code,
                    b.name as brand_name,
                    v.name as vendor_name,
                    c.center_name,
                    CASE
                        WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
                        WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "EXPIRING_SOON"
                        ELSE "FRESH"
                    END as expiry_status
                ');
                
                $this->db->from('medicine_batches mb');
                $this->db->join('center_stocks ccs', 'mb.id = ccs.batch_id');
                $this->db->join('medicines m', 'mb.medicine_id = m.id');
                $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID', 'left');
                $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID', 'left');
                $this->db->join('hms_centers c', 'ccs.center_id = c.ID', 'left');
                
                // Filter by source center
                if($from_center_id) {
                    $this->db->where('ccs.center_id', $from_center_id);
                }
                
                // Only show available center stocks
                $this->db->where('mb.batch_status', 'ACTIVE');
                $this->db->where('ccs.quantity >', 0);
                $this->db->where('ccs.status', 'ACTIVE');
                
            } elseif($transfer_type == 'CENTER_TO_CENTRAL') {
                // Center stocks to return to central - join with center_stocks table
                $this->db->select('
                    mb.id as batch_id,
                    mb.batch_number,
                    mb.expiry_date,
                    ccs.quantity as quantity_remaining,
                    mb.batch_status,
                    mb.purchase_price,
                    mb.selling_price,
                    DATEDIFF(mb.expiry_date, CURDATE()) as expiry_days,
                    m.id as medicine_id,
                    m.medicine_name,
                    m.medicine_code,
                    b.name as brand_name,
                    v.name as vendor_name,
                    c.center_name,
                    CASE
                        WHEN DATEDIFF(mb.expiry_date, CURDATE()) < 0 THEN "EXPIRED"
                        WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 30 THEN "EXPIRING_SOON"
                        ELSE "FRESH"
                    END as expiry_status
                ');
                
                $this->db->from('medicine_batches mb');
                $this->db->join('center_stocks ccs', 'mb.id = ccs.batch_id');
                $this->db->join('medicines m', 'mb.medicine_id = m.id');
                $this->db->join($this->config->item('db_prefix') . 'brands b', 'm.brand_id = b.ID', 'left');
                $this->db->join($this->config->item('db_prefix') . 'vendors v', 'mb.vendor_id = v.ID', 'left');
                $this->db->join('hms_centers c', 'ccs.center_id = c.ID', 'left');
                
                // Filter by source center
                if($from_center_id) {
                    $this->db->where('ccs.center_id', $from_center_id);
                }
                
                // Only show available center stocks
                $this->db->where('mb.batch_status', 'ACTIVE');
                $this->db->where('ccs.quantity >', 0);
                $this->db->where('ccs.status', 'ACTIVE');
            }
            
            // Order by FEFO (First Expiry, First Out)
            $this->db->order_by('mb.expiry_date', 'ASC');
            $this->db->order_by('m.medicine_name', 'ASC');
            
            return $this->db->get()->result();
        } catch (Exception $e) {
            return array();
        }
    }
}
