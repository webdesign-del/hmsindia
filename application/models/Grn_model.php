<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grn_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Generate GRN number
    public function generate_grn_number() {
        $year = date('Y');
        $month = date('m');
        $prefix = "GRN-" . $year . $month . "-";
        
        $this->db->select('grn_number');
        $this->db->from('hms_grn');
        $this->db->like('grn_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $last_grn = $query->row()->grn_number;
            $last_number = (int) substr($last_grn, strrpos($last_grn, "-") + 1);
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, "0", STR_PAD_LEFT);
    }

    // Insert GRN
    public function insert_grn($data) {
        return $this->db->insert('hms_grn', $data);
    }

    // Insert GRN items
    public function insert_grn_items($items_batch_data) {
        if (empty($items_batch_data)) {
            return true;
        }
        return $this->db->insert_batch('hms_grn_items', $items_batch_data);
    }

    // Get GRN by ID
    public function get_grn_by_id($grn_id) {
        return $this->db->where('id', $grn_id)
                        ->get('hms_grn')
                        ->row_array();
    }

    // Get GRN by GRN number
    public function get_grn_by_number($grn_number) {
        return $this->db->where('grn_number', $grn_number)
                        ->get('hms_grn')
                        ->row_array();
    }

    // Get GRN items
    public function get_grn_items($grn_id) {
        return $this->db->where('grn_id', $grn_id)
                        ->order_by('id', 'ASC')
                        ->get('hms_grn_items')
                        ->result_array();
    }

    // Get purchase order items
    public function get_po_items($po_number) {
        // Reset query builder to avoid conflicts
        $this->db->flush_cache();
        
        // Get purchase order by po_number
        $po = $this->db->select('id')
                       ->where('po_number', $po_number)
                       ->get('hms_purchase_orders')
                       ->row_array();
        
        if (!$po || !isset($po['id'])) {
            return [];
        }
        
        // Reset query builder again before second query
        $this->db->flush_cache();
        
        // Get items by po_id
        return $this->db->where('po_id', $po['id'])
                        ->order_by('id', 'ASC')
                        ->get('hms_purchase_order_items')
                        ->result_array();
    }

    // Count GRN with filters
    public function grn_count($filters = []) {
        $this->apply_filters($filters);
        return $this->db->count_all_results('hms_grn');
    }

    // Get paginated GRN with filters
    public function grn_pagination($limit, $start, $filters = []) {
        $this->apply_filters($filters);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('hms_grn', $limit, $start);
        return $query->result_array();
    }

    // Apply filters
    private function apply_filters($filters = []) {
        if (!empty($filters['grn_number'])) {
            $this->db->like('grn_number', $filters['grn_number']);
        }
        
        if (!empty($filters['po_number'])) {
            $this->db->like('po_number', $filters['po_number']);
        }
        
        if (!empty($filters['vendor_name'])) {
            $this->db->like('vendor_name', $filters['vendor_name']);
        }
        
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(grn_date) >=', $filters['start_date']);
            $this->db->where('DATE(grn_date) <=', $filters['end_date']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
    }

    // Get all GRN
    public function get_all_grn($filters = []) {
        $this->apply_filters($filters);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('hms_grn');
        return $query->result_array();
    }

    // Check if GRN exists for PO
    public function grn_exists_for_po($po_number) {
        $count = $this->db->where('po_number', $po_number)
                          ->count_all_results('hms_grn');
        return $count > 0;
    }

    // Update GRN status
    public function update_grn_status($grn_id, $status) {
        $this->db->where('id', $grn_id);
        return $this->db->update('hms_grn', ['status' => $status]);
    }
}

