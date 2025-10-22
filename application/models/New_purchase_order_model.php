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
}
