<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order_model extends CI_Model {

    public function insert_purchase_order($data) {
        return $this->db->insert('hms_purchase_orders', $data);
    }

    // Count purchase orders with filters
    public function purchase_order_count($filters = [])
    {
        $this->apply_filters($filters);
        return $this->db->count_all_results('hms_purchase_orders');
    }
    public function get_by_token($token)
    {
        try {
            return $this->db->where('approval_token', $token)->get('hms_purchase_orders')->row_array();
        } catch (Exception $e) {
            log_message('error', 'Fetch by Token Failed: ' . $e->getMessage());
            return null;
        }
    }
    public function update_status_by_token($token, $status)
    {
        try {
            return $this->db->where('approval_token', $token)
                            ->update('hms_purchase_orders', ['status' => $status]);
        } catch (Exception $e) {
            log_message('error', 'Update Status Failed: ' . $e->getMessage());
            return false;
        }
    }

    // Get paginated purchase orders with filters
    public function purchase_order_pagination($limit, $start, $filters = [])
    {
        $this->apply_filters($filters);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('hms_purchase_orders', $limit, $start);
        return $query->result_array();
    }

    private function apply_filters($filters = [])
    {
        if ($filters['status'] !== '' && $filters['status'] !== null) {
            $this->db->where('status', $filters['status']);
        }


        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('DATE(created_at) >=', $filters['start_date']);
            $this->db->where('DATE(created_at) <=', $filters['end_date']);
        }

        if (!empty($filters['po_centre'])) {
            $this->db->like('po_centre', $filters['po_centre']);
        }

        if (!empty($filters['po_department'])) {
            $this->db->like('po_department', $filters['po_department']);
        }

        if (!empty($filters['po_nature_of_expenditure'])) {
            $this->db->like('po_nature_of_expenditure', $filters['po_nature_of_expenditure']);
        }

        if (!empty($filters['po_budget_head'])) {
            $this->db->like('po_budget_head', $filters['po_budget_head']);
        }

        if (!empty($filters['po_budget_item'])) {
            $this->db->like('po_budget_item', $filters['po_budget_item']);
        }

        if (!empty($filters['approval_status'])) {
            // Handle approval status filtering based on approver_tokens JSON field
            switch ($filters['approval_status']) {
                case 'all_approved':
                    // Find POs where all approvers have approved (no pending, no rejected)
                    $this->db->where("JSON_LENGTH(JSON_EXTRACT(approver_tokens, '$[*].status')) > 0");
                    $this->db->where("JSON_SEARCH(approver_tokens, 'one', 'pending') IS NULL");
                    $this->db->where("JSON_SEARCH(approver_tokens, 'one', 'rejected') IS NULL");
                    break;
                    
                case 'pending':
                    // Find POs that have at least one pending approver
                    $this->db->where("JSON_SEARCH(approver_tokens, 'one', 'pending') IS NOT NULL");
                    break;
                    
                case 'rejected':
                    // Find POs that have at least one rejected approver
                    $this->db->where("JSON_SEARCH(approver_tokens, 'one', 'rejected') IS NOT NULL");
                    break;
                    
                case 'partial':
                    // Find POs that have some approved but not all (mixed status)
                    $this->db->where("JSON_LENGTH(JSON_EXTRACT(approver_tokens, '$[*].status')) > 0");
                    $this->db->where("JSON_SEARCH(approver_tokens, 'one', 'pending') IS NOT NULL");
                    $this->db->where("JSON_SEARCH(approver_tokens, 'one', 'rejected') IS NULL");
                    break;
            }
        }

        if (!empty($filters['po_name_of_vendor'])) {
            $this->db->like('po_name_of_vendor', $filters['po_name_of_vendor']);
        }
    }
    public function update_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update('hms_purchase_orders', ['status' => $status]);
    }
    public function generate_po_number()
    {
        $this->db->select('po_number');
        $this->db->from('hms_purchase_orders');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_po = $query->row()->po_number;
            $number = (int) str_replace("PO-", "", $last_po);
            $new_number = $number + 1;
        } else {
            $new_number = 1;
        }
        return "PO-" . str_pad($new_number, 6, "0", STR_PAD_LEFT);
    }

    public function get_purchase_order_by_id($po_number)
    {
        return $this->db->where('po_number', $po_number)
                        ->get('hms_purchase_orders') 
                        ->row_array();
    }
    public function get_purchase_order_by_print($po_id)
    {
        return $this->db->where('id', $po_id)
                        ->get('hms_purchase_orders') 
                        ->row_array();
    }
    public function save_purchase_order_payment($data)
    {
        return $this->db->insert('hms_purchase_order_payments', $data);
    }

    public function update_purchase_order($po_number, $amount_paid)
    {
        $purchase_order = $this->db->where('po_number', $po_number)
                                ->get('hms_purchase_orders')
                                ->row_array();
        if (!$purchase_order) {
            return false;
        }
        $new_paid   = $purchase_order['amount_paid'] + $amount_paid; 
        $new_balance = $purchase_order['po_po_total'] - $amount_paid;
        $this->db->where('po_number', $po_number)
                ->update('hms_purchase_orders', [
                    'amount_paid' => $new_paid,
                    'balance'     => $new_balance,
                    'po_po_total' => $new_balance
                ]);
        return true;
    }

    /**
     * Store approver tokens as JSON in the purchase order record
     * @param string $po_number Purchase order number
     * @param array $approver_tokens Array of approver tokens with email and token
     * @return bool Success status
     */
    public function store_approver_tokens($po_number, $approver_tokens)
    {
        try {
            $tokens_json = json_encode($approver_tokens);
            return $this->db->where('po_number', $po_number)
                            ->update('hms_purchase_orders', [
                                'approver_tokens' => $tokens_json
                            ]);
        } catch (Exception $e) {
            log_message('error', 'Failed to store approver tokens: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get approver token details by token from JSON field
     * @param string $token Approver token
     * @return array|null Token details or null if not found
     */
    public function get_approver_token_details($token)
    {
        try {
            $this->db->select('po_number, approver_tokens');
            $this->db->from('hms_purchase_orders');
            $this->db->like('approver_tokens', $token);
            $result = $this->db->get()->row_array();
            
            if (!$result || empty($result['approver_tokens'])) {
                return null;
            }
            
            $tokens = json_decode($result['approver_tokens'], true);
            if (!$tokens) {
                return null;
            }
            
            foreach ($tokens as $token_data) {
                if ($token_data['token'] === $token) {
                    return [
                        'po_number' => $result['po_number'],
                        'approver_email' => $token_data['email'],
                        'token' => $token_data['token'],
                        'status' => isset($token_data['status']) ? $token_data['status'] : 'pending'
                    ];
                }
            }
            
            return null;
        } catch (Exception $e) {
            log_message('error', 'Failed to get approver token details: ' . $e->getMessage());
            return null;
        }
    }

    public function add_po_items($items_batch_data)
    {
        if (empty($items_batch_data)) {
            return true; 
        }
        return $this->db->insert_batch('hms_purchase_order_items', $items_batch_data);
    }

    /**
     * Update approver token status in JSON field
     * @param string $token Approver token
     * @param string $status New status
     * @param string $remarks Optional remarks
     * @return bool Success status
     */
    public function update_approver_token_status($token, $status, $remarks = '')
    {
        try {
            $token_details = $this->get_approver_token_details($token);
            if (!$token_details) {
                return false;
            }
            
            $po = $this->get_purchase_order_by_id($token_details['po_number']);
            if (!$po || empty($po['approver_tokens'])) {
                return false;
            }
            
            $tokens = json_decode($po['approver_tokens'], true);
            if (!$tokens) {
                return false;
            }
            
            // Update the specific token status
            foreach ($tokens as &$token_data) {
                if ($token_data['token'] === $token) {
                    $token_data['status'] = $status;
                    $token_data['approved_at'] = date('Y-m-d H:i:s');
                    if ($remarks) {
                        $token_data['remarks'] = $remarks;
                    }
                    break;
                }
            }
            
            // Update the database
            $tokens_json = json_encode($tokens);
            return $this->db->where('po_number', $token_details['po_number'])
                            ->update('hms_purchase_orders', [
                                'approver_tokens' => $tokens_json
                            ]);
        } catch (Exception $e) {
            log_message('error', 'Failed to update approver token status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get pending purchase orders for a specific user
     * @param string $user_email User email to check pending orders for
     * @return array Array of pending purchase orders
     */
    public function get_pending_orders_for_user($user_email)
    {
        try {
            $this->db->select('*');
            $this->db->from('hms_purchase_orders');
            $this->db->like('approver_tokens', $user_email);
            $this->db->where('status', '2'); // Pending status
            $query = $this->db->get();
            $all_pos = $query->result_array();
            
            $pending_orders = [];
            
            foreach ($all_pos as $po) {
                if (!empty($po['approver_tokens'])) {
                    $approver_tokens = json_decode($po['approver_tokens'], true);
                    if ($approver_tokens) {
                        foreach ($approver_tokens as $token_data) {
                            if ($token_data['email'] === $user_email && $token_data['status'] === 'pending') {
                                $pending_orders[] = $po;
                                break;
                            }
                        }
                    }
                }
            }
            
            return $pending_orders;
        } catch (Exception $e) {
            log_message('error', 'Failed to get pending orders for user: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get count of pending purchase orders for a specific user
     * @param string $user_email User email to check pending orders for
     * @return int Count of pending orders
     */
    public function get_pending_orders_count_for_user($user_email)
    {
        $pending_orders = $this->get_pending_orders_for_user($user_email);
        return count($pending_orders);
    }

    /**
     * Get user approval statistics - approved, disapproved, pending counts
     * @param string $user_email User email to get stats for
     * @param array $filters Additional filters (status_filter, po_number)
     * @return array User approval statistics
     */
    public function get_user_approval_stats($user_email, $filters = [])
    {
        // try {
            $stats = [
                'user_email' => $user_email,
                'total_approved' => 0,
                'total_disapproved' => 0,
                'total_pending' => 0,
                'total_involved' => 0,
                'po_approved' => 0,
                'po_disapproved' => 0,
                'po_pending' => 0,
                'approval_details' => []
            ];
            $this->db->select('*');
            $this->db->from('hms_purchase_orders');
            $this->db->like('approver_tokens', $user_email);
            if (!empty($filters['po_number'])) {
                $this->db->like('po_number', $filters['po_number']);
            }
            $query = $this->db->get();
            $all_pos = $query->result_array();
            foreach ($all_pos as $po) {
                if (!empty($po['approver_tokens'])) {
                    $approver_tokens = json_decode($po['approver_tokens'], true);
                    if ($approver_tokens) {
                        foreach ($approver_tokens as $token_data) {
                            if ($token_data['email'] === $user_email) {
                                if (!empty($filters['status_filter']) && $token_data['status'] !== $filters['status_filter']) {
                                    continue;
                                }
                                $stats['total_involved']++;
                                $po_info = [
                                    'po_number' => $po['po_number'],
                                    'po_centre' => $po['po_centre'],
                                    'po_department' => $po['po_department'],
                                    'po_name_of_vendor' => $po['po_name_of_vendor'],
                                    'po_po_total' => $po['po_po_total'],
                                    'created_at' => $po['created_at'],
                                    'status' => $token_data['status'], 
                                    'po_status' => $po['status'], 
                                    'approved_at' => isset($token_data['approved_at']) ? $token_data['approved_at'] : null,
                                    'remarks' => isset($token_data['remarks']) ? $token_data['remarks'] : ''
                                ];
                                switch ($token_data['status']) {
                                    case 'approved':
                                        $stats['total_approved']++;
                                        break;
                                    case 'rejected':
                                        $stats['total_disapproved']++;
                                        break;
                                    case 'pending':
                                        $stats['total_pending']++;
                                        break;
                                }
                                switch ($po['status']) {
                                    case '1':
                                        $stats['po_approved']++;
                                        break;
                                    case '0':
                                        $stats['po_disapproved']++;
                                        break;
                                    case '2':
                                    default:
                                        $stats['po_pending']++;
                                        break;
                                }
                                $stats['approval_details'][] = $po_info;
                                break; 
                            }
                        }
                    }
                }
            }
            usort($stats['approval_details'], function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
            return $stats;
        // } catch (Exception $e) {
        //     log_message('error', 'Failed to get user approval stats: ' . $e->getMessage());
        //     return [
        //         'user_email' => $user_email,
        //         'total_approved' => 0,
        //         'total_disapproved' => 0,
        //         'total_pending' => 0,
        //         'total_involved' => 0,
        //         'po_approved' => 0,
        //         'po_disapproved' => 0,
        //         'po_pending' => 0,
        //         'approval_details' => []
        //     ];
        // }
    }

    public function get_all_purchase_orders($filters = [])
    {
        $this->apply_filters($filters); 
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('hms_purchase_orders');
        return $query->result_array();
    }
    // public function get_purchase_order_by_id($po_id)
    // {
    //     $this->db->where('id', $po_id);
    //     $query = $this->db->get('hms_purchase_orders');
    //     return $query->row_array(); // Returns a single associative array
    // }
    // public function get_purchase_order_items($po_id)
    // {
    //     $this->db->where('po_id', $po_id);
    //     $this->db->order_by('id', 'ASC'); 
    //     $query = $this->db->get('hms_purchase_order_items'); 
    //     return $query->result_array();
    // }


}
