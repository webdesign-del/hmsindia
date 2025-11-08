<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New_purchase_order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Insert new purchase order
    public function insert_purchase_order($data) {
        $this->db->insert('hms_new_purchase_orders', $data);
        return $this->db->insert_id();
    }

    // Insert purchase order items
    public function insert_purchase_order_items($data) {
        return $this->db->insert('hms_new_purchase_order_items', $data);
    }

    // Insert PO number tracking
    public function insert_po_number($data) {
        return $this->db->insert('hms_new_ponumber', $data);
    }

    // Get last PO number for the same month
    public function get_last_po_number($prefix) {
        $this->db->select('po_number');
        $this->db->from('hms_new_ponumber');
        $this->db->like('po_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return null;
    }

    // Get all purchase orders with pagination
    public function get_purchase_orders($limit = 10, $start = 0, $filters = []) {
        $this->db->flush_cache(); // Reset query builder
        $this->db->select('*');
        $this->db->from('hms_new_purchase_orders');
        $this->apply_filters($filters);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Count purchase orders with filters
    public function count_purchase_orders($filters = []) {
        $this->db->flush_cache(); // Reset query builder
        $this->db->from('hms_new_purchase_orders');
        $this->apply_filters($filters);
        return $this->db->count_all_results();
    }

    // Get total count without any filters (for debugging)
    public function get_total_count() {
        $this->db->flush_cache(); // Reset query builder
        return $this->db->count_all('hms_new_purchase_orders');
    }

    // Get purchase order by ID
    public function get_purchase_order_by_id($id) {
        $this->db->where('id', $id);
       
        $query = $this->db->get('hms_new_purchase_orders');
        return $query->row_array();
    }

    // Get purchase order items by PO ID
    public function get_purchase_order_items($po_id) {
        $this->db->where('po_id', $po_id);
        $query = $this->db->get('hms_new_purchase_order_items');
        return $query->result_array();
    }

    // Update purchase order status
    public function update_purchase_order_status($id, $status, $approved_by = null) {
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($approved_by && $status == 'approved') {
            $data['approved_by'] = $approved_by;
            $data['approved_at'] = date('Y-m-d H:i:s');
        }
        
        $this->db->where('id', $id);
        return $this->db->update('hms_new_purchase_orders', $data);
    }

    // Update status (alias for update_purchase_order_status for compatibility)
    public function update_status($id, $status, $remarks = null) {
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Map numeric status to string status
        if ($status == '1') {
            $data['status'] = 'approved';
            $data['approved_by'] = isset($_SESSION['logged_administrator']['name']) ? $_SESSION['logged_administrator']['name'] : 'Administrator';
            $data['approved_at'] = date('Y-m-d H:i:s');
        } elseif ($status == '0') {
            $data['status'] = 'rejected';
            // Note: rejected_by and rejected_at fields don't exist in the table
            // We could add them later if needed
        }
        
        $this->db->where('id', $id);
        return $this->db->update('hms_new_purchase_orders', $data);
    }

    // Update purchase order
    public function update_purchase_order($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('hms_new_purchase_orders', $data);
    }

    // Delete purchase order items
    public function delete_purchase_order_items($po_id) {
        $this->db->where('po_id', $po_id);
        return $this->db->delete('hms_new_purchase_order_items');
    }

    // Delete purchase order
    public function delete_purchase_order($id) {
        // First delete items, then delete order
        $this->delete_purchase_order_items($id);
        $this->db->where('id', $id);
        return $this->db->delete('hms_new_purchase_orders');
    }

    // Get purchase order by PO number
    public function get_purchase_order_by_po_number($po_number) {
        $this->db->where('po_number', $po_number);
        $query = $this->db->get('hms_new_purchase_orders');
        return $query->row_array();
    }

    // Get purchase orders by vendor
    public function get_purchase_orders_by_vendor($vendor_number) {
        $this->db->where('vendor_number', $vendor_number);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('hms_new_purchase_orders');
        return $query->result_array();
    }

    // Get purchase orders by center

    // Get purchase orders by status
    public function get_purchase_orders_by_status($status) {
        $this->db->where('status', $status);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('hms_new_purchase_orders');
        return $query->result_array();
    }

    // Get purchase orders by date range
    public function get_purchase_orders_by_date_range($start_date, $end_date) {
        $this->db->where('DATE(created_at) >=', $start_date);
        $this->db->where('DATE(created_at) <=', $end_date);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('hms_new_purchase_orders');
        return $query->result_array();
    }

    // Calculate total amount for purchase order
    public function calculate_total_amount($po_id) {
        $this->db->select('quantity, vendor_price, tax_percentage');
        $this->db->where('po_id', $po_id);
        $query = $this->db->get('hms_new_purchase_order_items');
        $items = $query->result_array();
        
        $total = 0;
        foreach ($items as $item) {
            $quantity = floatval($item['quantity']) ?: 0;
            $vendor_price = floatval($item['vendor_price']) ?: 0;
            $tax = floatval($item['tax_percentage']) ?: 0;
            
            $item_total = ($quantity * $vendor_price) * (1 + $tax / 100);
            $total += $item_total;
            
            // Debug logging removed for performance
        }
        
        // Debug logging removed for performance
        return $total;
    }

    // Update total amount in purchase order
    public function update_total_amount($po_id) {
        $total = $this->calculate_total_amount($po_id);
        $this->db->where('id', $po_id);
        return $this->db->update('hms_new_purchase_orders', ['total_amount' => $total]);
    }

    // Apply filters for search
    private function apply_filters($filters = []) {
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        
        if (!empty($filters['vendor_number'])) {
            $this->db->where('vendor_number', $filters['vendor_number']);
        }
        
        if (!empty($filters['center'])) {
            $this->db->where('center', $filters['center']);
        }
        
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(created_at) >=', $filters['start_date']);
            $this->db->where('DATE(created_at) <=', $filters['end_date']);
        }
        
        if (!empty($filters['po_number'])) {
            $this->db->like('po_number', $filters['po_number']);
        }
    }

    // Get financial year
    public function get_financial_year() {
        $date = date_create("now");
        $year = date_format($date, "y");
        $month = date_format($date, "m");
        
        if ($month >= 4) {
            return $year . '-' . ($year + 1);
        } else {
            return ($year - 1) . '-' . $year;
        }
    }

    // Get month name
    public function get_month_name() {
        $date = date_create("now");
        return date_format($date, "F");
    }

    // Generate PO number
    public function generate_po_number() {
        $financial_year = $this->get_financial_year();
        $month = $this->get_month_name();
        $psno = "PSPL/" . $financial_year . "/" . $month . "/";
        
        $last_po = $this->get_last_po_number($psno);
        $last_number = 0;
        
        if ($last_po) {
            $last_number = (int) substr($last_po['po_number'], strrpos($last_po['po_number'], "/") + 1);
        }
        
        $new_number = $last_number + 1;
        return $psno . $new_number;
    }

    // Generate POR number
    public function generate_por_number() {
        $financial_year = $this->get_financial_year();
        $month = $this->get_month_name();
        $psno = "POR/" . $financial_year . "/" . $month . "/";
        
        $last_por = $this->get_last_por_number($psno);
        $last_number = 0;
        
        if ($last_por) {
            $last_number = (int) substr($last_por['por_number'], strrpos($last_por['por_number'], "/") + 1);
        }
        
        $new_number = $last_number + 1;
        return $psno . $new_number;
    }

    // Get last POR number for the same month
    public function get_last_por_number($prefix) {
        $this->db->select('por_number');
        $this->db->from('purchase_order_receipts');
        $this->db->like('por_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return null;
    }

    // Generate transfer number
    public function generate_transfer_number() {
        $financial_year = $this->get_financial_year();
        $month = $this->get_month_name();
        $psno = "STT" . date('ym') . "/";
        
        $last_transfer = $this->get_last_transfer_number($psno);
        $last_number = 0;
        
        if ($last_transfer) {
            $last_number = (int) substr($last_transfer['transfer_number'], strrpos($last_transfer['transfer_number'], "/") + 1);
        }
        
        $new_number = str_pad($last_number + 1, 3, '0', STR_PAD_LEFT);
        return $psno . $new_number;
    }

    // Get last transfer number for the same month
    public function get_last_transfer_number($prefix) {
        $this->db->select('transfer_number');
        $this->db->from('stock_transfers');
        $this->db->like('transfer_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return null;
    }

    // Insert purchase order receipt
    public function insert_purchase_order_receipt($data) {
        $this->db->insert('purchase_order_receipts', $data);
        return $this->db->insert_id();
    }

    // Insert receipt items
    public function insert_receipt_items($data) {
        return $this->db->insert_batch('purchase_order_receipt_items', $data);
    }

    // Get purchase order receipt by ID
    public function get_purchase_order_receipt_by_id($id) {
        $this->db->select('*');
        $this->db->from('purchase_order_receipts');
        $this->db->where('id', $id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return null;
    }

    // Get receipt items
    public function get_receipt_items($receipt_id) {
        $this->db->select('*');
        $this->db->from('purchase_order_receipt_items');
        $this->db->where('receipt_id', $receipt_id);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    // Insert stock transfer
    public function insert_stock_transfer($data) {
        $this->db->insert('stock_transfers', $data);
        return $this->db->insert_id();
    }

    // Insert stock transfer items
    public function insert_stock_transfer_items($data) {
        return $this->db->insert_batch('stock_transfer_items', $data);
    }

    // Get stock transfer by ID
    public function get_stock_transfer_by_id($id) {
        $this->db->select('*');
        $this->db->from('stock_transfers');
        $this->db->where('id', $id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return null;
    }

    // Get stock transfer items
    public function get_stock_transfer_items($transfer_id) {
        $this->db->select('*');
        $this->db->from('stock_transfer_items');
        $this->db->where('transfer_id', $transfer_id);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    // Get all purchase order receipts with pagination
    public function get_purchase_order_receipts($limit = 10, $start = 0, $filters = []) {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('purchase_order_receipts');
        $this->apply_receipt_filters($filters);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    // Count purchase order receipts
    public function count_purchase_order_receipts($filters = []) {
        $this->db->flush_cache();
        $this->db->from('purchase_order_receipts');
        $this->apply_receipt_filters($filters);
        return $this->db->count_all_results();
    }

    // Apply filters for receipts
    private function apply_receipt_filters($filters) {
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        if (!empty($filters['supplier_name'])) {
            $this->db->like('supplier_name', $filters['supplier_name']);
        }
        if (!empty($filters['po_number'])) {
            $this->db->like('po_number', $filters['po_number']);
        }
        if (!empty($filters['start_date'])) {
            $this->db->where('por_date >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $this->db->where('por_date <=', $filters['end_date']);
        }
    }

    // Get all stock transfers with pagination
    public function get_stock_transfers($limit = 10, $start = 0, $filters = []) {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('stock_transfers');
        $this->apply_transfer_filters($filters);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    // Count stock transfers
    public function count_stock_transfers($filters = []) {
        $this->db->flush_cache();
        $this->db->from('stock_transfers');
        $this->apply_transfer_filters($filters);
        return $this->db->count_all_results();
    }

    // Apply filters for transfers
    private function apply_transfer_filters($filters) {
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        if (!empty($filters['from_location'])) {
            $this->db->like('from_location', $filters['from_location']);
        }
        if (!empty($filters['to_location'])) {
            $this->db->like('to_location', $filters['to_location']);
        }
        if (!empty($filters['start_date'])) {
            $this->db->where('doc_date >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $this->db->where('doc_date <=', $filters['end_date']);
        }
    }

    // Insert vendor billing record (similar to Order_model method)
    public function insert_vendor_billing($data) {
        $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "vendor_billing` SET ";
        $sqlArr = array();
        foreach($data as $key => $value) {
            $sqlArr[] = " $key = '" . addslashes($value) . "'";
        }
        $sql .= implode(',', $sqlArr);
        $res = $this->db->query($sql);
        if ($res) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    // Check if stock item exists (by batch number and vendor)
    public function check_existing_stock_item($item_name, $batch_number, $vendor_number) {
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
    }

    // Update existing stock quantity
    public function update_stock_quantity($stock_id, $quantity, $stock_data = []) {
        $sql = "UPDATE `" . $this->config->item('db_prefix') . "stocks` SET `quantity` = `quantity` + {$quantity}";
        foreach ($stock_data as $key => $value) {
            if ($key != 'quantity' && $key != 'add_date' && $key != 'status') {
                $sql .= ", `{$key}` = '" . addslashes($value) . "'";
            }
        }
        $sql .= " WHERE `ID` = '{$stock_id}'";
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    // Insert new stock item
    public function insert_stock_item($stock_data) {
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
    }
    /**
     * Gets distinct active medicines that have been purchased from a specific vendor.
     * Maps new schema columns to the structure expected by the old 'items_by_vendor' AJAX call.
     */
    public function get_vendor_id_by_number($vendor_number)
    {
        $query = $this->db->select('ID')
                          ->where('vendor_number', $vendor_number)
                          ->limit(1)
                          ->get('hms_vendors');
        if ($query->num_rows() > 0) {
            return $query->row()->ID;
        }
        return false; // Vendor not found
    }
    public function get_items_by_id($id) 
    {
        $query = $this->db->select('ID,name')
                          ->where('ID', $id)
                          ->limit(1)
                          ->get('hms_vendors');
        if ($query->num_rows() > 0) {
            return $query->row()->ID;
        }
        return false; // Vendor not found
    }
    public function is_po_fully_received($po_id)
    {
        // try {
            // We select the sum of all remaining quantities
            $this->db->select('SUM(quantity - quantity_received) as total_remaining');
            $this->db->from('hms_new_purchase_order_items');
            $this->db->where('po_id', $po_id);
            $query = $this->db->get();
            $result = $query->row();
            if ($result && $result->total_remaining <= 0) {
                return true;
            } else {
                return false;
            }
        // } catch (Exception $e) {
        //     log_message('error', 'Error in is_po_fully_received: ' . $e->getMessage());
        //     return false; // Fail safe: assume it's not received if error occurs
        // }
    }
    public function get_received_stock_report($filters = []) {
        // try {
            $this->db->select('
                sm.receive_date as received_date,
                sm.quantity_change,
                sm.unit_price,
                sm.total_value,
                sm.receive_by,
                sm.uploaded_files,
                sm.reference_number as po_number,
                po.vendor_number,
                v.name as vendor_name,
                c.center_name,
                m.medicine_name,
                m.medicine_code as item_number,
                mb.batch_number,
                mb.purchase_price as vendor_price_with_tax
            ');
            $this->db->from('stock_movements sm');
            // This is the main logic for your report
            $this->db->where('sm.movement_type', 'PURCHASE');
            // Joins to get the details
            $this->db->join('medicine_batches mb', 'sm.batch_id = mb.id', 'left');
            $this->db->join('medicines m', 'mb.medicine_id = m.id', 'left');
            $this->db->join('hms_new_purchase_orders po', 'sm.reference_id = po.id AND sm.reference_type = "PURCHASE_ORDER"', 'left');
            $this->db->join('hms_vendors v', 'po.vendor_number = v.ID', 'left');
            $this->db->join('hms_centers c', 'sm.to_location_id = c.ID AND sm.to_location_type = "CENTER"', 'left');
            if (!empty($filters['po_number'])) {
                $this->db->like('sm.reference_number', $filters['po_number'], 'both');
            }
            if (!empty($filters['vendor_id'])) {
                $this->db->where('po.vendor_number', $filters['vendor_id']);
            }
            if (!empty($filters['start_date'])) {
                $this->db->where('DATE(sm.created_at) >=', $filters['start_date']);
            }
            if (!empty($filters['end_date'])) {
                $this->db->where('DATE(sm.created_at) <=', $filters['end_date']);
            }

            $this->db->order_by('sm.created_at', 'DESC');
            return $this->db->get()->result();

        // } catch (Exception $e) {
        //     log_message('error', 'Error in get_received_stock_report: ' . $e->getMessage());
        //     return [];
        // }
    }
    // public function get_items_by_vendor($vendor_id) {
    //     if (empty($vendor_id)) {
    //         return [];
    //     }
    //     try {
    //         $this->db->distinct(); 
    //         $this->db->select([
    //             'm.id as item_number',          // medicines.id
    //             'm.medicine_name as item_name', // medicines.medicine_name
    //             'm.pack_size',                  // medicines.pack_size
    //             'm.gst_rate as gstrate',        // medicines.gst_rate
    //             'm.hsn_code as hsn',            // medicines.hsn_code
    //             'b.brand_name',                 // medicine_brands.brand_name
    //             'b.manufacturer as company',    // medicine_brands.manufacturer
    //             'v.vendor_number',              // hms_vendors.vendor_number
    //             // --- Fields NOT directly available on master medicine record ---
    //             // Set defaults or NULLs if your JS absolutely needs them
    //             'NULL as batch_number',         // No single batch number applies
    //             '0 as quantity',                // Master record has no quantity
    //             'm.selling_price as price',     // Use selling price from medicine as default 'price'
    //             'm.selling_price as mrp',       // Use selling price from medicine as default 'mrp'
    //              // Use last purchase price as vendor_price? Requires complex subquery/join. NULL is safer.
    //             'NULL as vendor_price',
    //              // Set to 0 or null if gstdivision isn't in your new schema
    //             '0 as gstdivision'
    //         ]);
    //         $this->db->from('medicines m');
    //         // Join batches to filter by vendor
    //         $this->db->join('medicine_batches mb', 'm.id = mb.medicine_id', 'inner');
    //         // Join vendors to confirm vendor_number and filter by vendor_id
    //         $this->db->join('hms_vendors v', 'mb.vendor_id = v.ID', 'inner');
    //         // Join brands to get brand name and company (manufacturer)
    //         $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
    //         // --- Filters ---
    //         $this->db->where('m.status', 'active');      // Only active medicines
    //         $this->db->where('mb.vendor_id', $vendor_id); // Filter by the specific vendor ID
    //         $this->db->order_by('m.medicine_name', 'ASC');
    //         $query = $this->db->get();
    //         return $query->result_array();
    //     } catch (Exception $e) {
    //         log_message('error', 'Error in get_medicines_by_vendor: ' . $e->getMessage());
    //         return []; // Return empty array on error
    //     }
    // }
    /**
     * Gets distinct active medicines that have been purchased from a specific vendor.
     * Maps new schema columns to the structure expected by the old 'items_by_vendor' AJAX call.
     */
   /**
     * Gets distinct active medicines that have been purchased from a specific vendor.
     * Maps new schema columns to the structure expected by the 'items_by_vendor' AJAX call.
     */
//    public function get_medicines_by_vendor($vendor_id) 
//    {
//         if (empty($vendor_id) || !is_numeric($vendor_id)) {
//             log_message('error', 'Invalid vendor_id provided to get_medicines_by_vendor.');
//             return [];
//         }
//         $this->db->select([
//             'm.id as item_number',
//             'm.medicine_name as item_name',
//             'm.pack_size',
//             'm.gst_rate as gstrate',
//             'm.hsn_code as hsn',
//             'b.brand_name',
//             'b.manufacturer as company',
//             'v.vendor_number',
//             '0 as quantity', // Use 0 instead of NULL if quantity is expected to be numeric
//             'MAX(mb.purchase_price) as vendor_price', // Get representative price
//             'MAX(mb.purchase_price) as price',
//             'MAX(mb.purchase_price) as mrp',
//             '0 as gstdivision'
//         ]);
//         $this->db->select('NULL as batch_number', FALSE); // Add FALSE here
//         // --- 3. FROM and JOINs ---
//         $this->db->from('medicines m');
//         $this->db->join('medicine_batches mb', 'm.id = mb.medicine_id', 'inner');
//         $this->db->join('hms_vendors v', 'mb.vendor_id = v.ID', 'inner');
//         $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');
//         // --- 4. Filters ---
//         $this->db->where('m.status', 'active');
//         $this->db->where('mb.vendor_id', $vendor_id);
//         // --- 5. Grouping ---
//         $this->db->group_by([
//             'm.id', 'm.medicine_name', 'm.pack_size', 'm.gst_rate', 'm.hsn_code',
//             'b.brand_name', 'b.manufacturer', 'v.vendor_number'
//         ]);
//         // --- 6. Ordering ---
//         $this->db->order_by('m.medicine_name', 'ASC');
//         // --- 7. Execute and Return ---
//         $query = $this->db->get();
//         return $query->result_array();
       
//     }
public function get_medicines_by_vendor($vendor_id)
{
    if (empty($vendor_id) || !is_numeric($vendor_id)) {
        log_message('error', 'Invalid vendor_id provided to get_medicines_by_vendor.');
        return [];
    }

    $vendor_id = (int) $vendor_id;

    // Subquery to get the latest batch ID per medicine for this vendor
    $subquery = "
        SELECT medicine_id, MAX(id) AS latest_batch_id
        FROM medicine_batches
        WHERE vendor_id = {$vendor_id}
        GROUP BY medicine_id
    ";

    $this->db->select([
        'm.id AS item_number',
        'm.medicine_name AS item_name',
        'm.pack_size',
        'm.gst_rate AS gstrate',
        'm.hsn_code AS hsn',
        'b.brand_name',
        'b.manufacturer AS company',
        'v.vendor_number',
        'mb.batch_number',
        'mb.purchase_price AS vendor_price',
        'mb.purchase_price AS price',
        'mb.mrp AS mrp',
        '0 AS quantity',
        '0 AS gstdivision'
    ]);

    $this->db->from('medicines m');

    // Join only medicines that have batches by this vendor (INNER JOIN)
    $this->db->join("({$subquery}) latest_batch", 'm.id = latest_batch.medicine_id', 'inner');
    $this->db->join('medicine_batches mb', 'mb.id = latest_batch.latest_batch_id', 'inner');
    $this->db->join('hms_vendors v', 'mb.vendor_id = v.ID', 'inner');
    $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left');

    // Only active medicines
    $this->db->where('m.status', 'active');

    // Optional ordering
    $this->db->order_by('m.medicine_name', 'ASC');

    $query = $this->db->get();
    return $query->result_array();
}

}
