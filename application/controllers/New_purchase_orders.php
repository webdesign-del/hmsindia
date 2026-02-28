<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New_purchase_orders extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('New_purchase_order_model');
        $this->load->model('Stock_model');
        $this->load->model('Stock_model_new');
        $this->load->model('Accounts_model');
        $this->load->library("form_validation");
        $this->load->model('Vendors_model');
        $this->load->model('Center_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper('myhelper');
    }
    function employee_detail_number($biller_id)
    {
        $ci= &get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM hms_employees WHERE employee_number  = '".$biller_id."'";
        $q   = $ci->db->query($sql);
        $result = $q->result_array();    
        if(count($result) > 0)
        {
            return $result[0];    	
        }
        return $result;
    }
    // Index page - List all purchase orders
    public function index() {
        error_reporting(0);
        $logg = checklogin();
        if($logg['status'] == true) {
            $data['title'] = 'New Purchase Orders';
            $data['user_role'] = $logg['role']; // Pass user role to view
            
            // Get filters from request
            $filters = [
                'status' => $this->input->get('status'),
                'vendor_number' => $this->input->get('vendor_number'),
                'center' => $this->input->get('center'),
                'start_date' => $this->input->get('start_date'),
                'end_date' => $this->input->get('end_date'),
                'po_number' => $this->input->get('po_number')
            ];
            
            // Pagination
            $page = $this->input->get('page') ? $this->input->get('page') : 1;
            $limit = 20;
            $start = ($page - 1) * $limit;
            
            $data['purchase_orders'] = $this->New_purchase_order_model->get_purchase_orders($limit, $start, $filters);
            $data['total_count'] = $this->New_purchase_order_model->count_purchase_orders($filters);
                        $data['filters'] = $filters;
            $data['current_page'] = $page;
            $data['total_pages'] = ceil($data['total_count'] / $limit);
            $data['vendors'] = $this->get_vendors();
            
            // Get pending count for administrators
            if ($logg['role'] == 'administrator') {
                $data['pending_count'] = $this->New_purchase_order_model->count_purchase_orders(['status' => 'pending']);
                $data['approved_count'] = $this->New_purchase_order_model->count_purchase_orders(['status' => 'approved']);
                $data['rejected_count'] = $this->New_purchase_order_model->count_purchase_orders(['status' => 'rejected']);
                $data['completed_count'] = $this->New_purchase_order_model->count_purchase_orders(['status' => 'completed']);
            }
            
            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
            $this->load->view('new_purchase_orders/index', $data);
            $this->load->view($template['footer']);
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Add new purchase order form
    public function add() {
        $logg = checklogin();
        if($logg['status'] == true) {
            $data['title'] = 'Add New Purchase Order';
            // Get vendors
            $data['vendors'] = $this->get_vendors();
            // Get consumables/items
            $data['consumables'] = $this->get_medicines_list();
            // Get centers
            $data['centers'] = $this->get_centers();
            
            // Get departments from new stocks module
            $data['departments'] = $this->get_departments();
            
            // Generate PO number
            $data['po_number'] = $this->New_purchase_order_model->generate_po_number();
            
            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
            $data["departments"] = $this->get_departments_by_center();
            $this->load->view('new_purchase_orders/add', $data);
            $this->load->view($template['footer']);
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }
    function get_departments_by_center()
    {
        $result = [];
        $sql_condition = "";
        $sql =
            "Select DISTINCT department from " .
            $this->config->item("db_prefix") .
            "employees where status='1' and department != '' ORDER BY department ASC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)) {
            return $result;
        }
    }

    // Save new purchase order
    public function save() {
        $logg = checklogin();
        if($logg['status'] == true) {
            if ($this->input->post()) {
                // Validate max stock levels before creating PO
                $validation_errors = $this->validate_max_stock_levels();
                if (!empty($validation_errors)) {
                    $error_message = 'Cannot create purchase order. Max stock level exceeded for: ' . implode(', ', $validation_errors);
                    $this->session->set_flashdata('error', $error_message);
                    redirect('new_purchase_orders/add');
                    return;
                }

                $po_data = [
                    'po_number' => $this->input->post('po_number'),
                    'vendor_number' => $this->input->post('vendor_number'),
                    'ship_to' => $this->input->post('ship_to'),
                    'bill_to' => $this->input->post('bill_to'),
                    'department' => $this->input->post('department'),
                    'created_by' => $_SESSION['logged_central_stock_manager']['employee_number'] ?? null,
                    'status' => 'pending'
                ];
                $po_id = $this->New_purchase_order_model->insert_purchase_order($po_data);
                if ($po_id) {
                    $po_number_data = [
                        'po_number' => $po_data['po_number'],
                        'financial_year' => $this->New_purchase_order_model->get_financial_year(),
                        'month' => $this->New_purchase_order_model->get_month_name(),
                        'sequence_number' => (int) substr($po_data['po_number'], strrpos($po_data['po_number'], "/") + 1)
                    ];
                    $this->New_purchase_order_model->insert_po_number($po_number_data);
                    $this->save_purchase_order_items($po_id, $po_data['po_number']);
                    $this->New_purchase_order_model->update_total_amount($po_id);
                    $this->session->set_flashdata('success', 'Purchase order created successfully!');
                    redirect('new_purchase_orders');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create purchase order!');
                    redirect('new_purchase_orders/add');
                }
            }
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    /**
     * Validate that PO quantities don't exceed max stock levels
     * @return array Array of error messages for items that exceed max stock
     */
    private function validate_max_stock_levels() {
        $errors = [];
        $i = 1;

        // Get center and department from POST data
        $ship_to_center_id = $this->input->post('ship_to');
        $department = $this->input->post('department');

        // Get center ID if ship_to is provided
        $center_id = null;
        if (!empty($ship_to_center_id)) {
            $center_data = $this->get_center_by_id($ship_to_center_id);
            $center_id = $center_data ? $center_data->ID : null;
        }

        while ($this->input->post('consumables_name_' . $i) && !empty($this->input->post('consumables_name_' . $i))) {
            $medicine_id = $this->input->post('consumables_name_' . $i);
            $po_quantity = (int) $this->input->post('consumables_quantity_' . $i);
            $item_name = $this->input->post('consumables_item_name_' . $i);

            if (!empty($medicine_id) && $po_quantity > 0) {
                // Get current stock and max stock level
                $stock_info = $this->Stock_model_new->get_medicine_stock_info($medicine_id, $center_id, $department);
                
                if ($stock_info) {
                    $current_stock = (int) $stock_info->current_stock;
                    $max_stock = (int) $stock_info->max_stock_level;
                    
                    // Check if max_stock_level is set (greater than 0)
                    if ($max_stock > 0) {
                        $total_after_po = $current_stock + $po_quantity;
                        
                        if ($total_after_po > $max_stock) {
                            $errors[] = $item_name . " (Current: {$current_stock}, Max: {$max_stock}, PO Qty: {$po_quantity})";
                        }
                    }
                }
            }
            $i++;
        }
        
        return $errors;
    }

    // Save purchase order items
    private function save_purchase_order_items($po_id, $po_number) {
        $items = [];
        $i = 1;
        while ($this->input->post('consumables_name_' . $i) && !empty($this->input->post('consumables_name_' . $i))) {
            $item_number = $this->input->post('consumables_name_' . $i);
            if (!empty($item_number)) {
                $item_data = [
                    'po_id' => $po_id,
                    'po_number' => $po_number,
                    'serial_number' => $i,
                    'item_name' => $this->input->post('consumables_item_name_' . $i),
                    'item_number' => $item_number,
                    'quantity' => $this->input->post('consumables_quantity_' . $i) ,
                    'batch_number' => $this->input->post
                    
                    ('consumables_batch_number_' . $i),
                    'price' => $this->input->post('consumables_price_' . $i),
                    'vendor_price' => $this->input->post('consumables_vendor_price_' . $i),
                    'pack_size' => $this->input->post('consumables_pack_size_' . $i),
                    'mrp' => $this->input->post('consumables_mrp_' . $i),
                    'tax_percentage' => $this->input->post('consumables_gstrate_' . $i),
                    'company' => $this->input->post('consumables_company_' . $i),
                    'hsn' => $this->input->post('consumables_hsn_' . $i),
                    'gst_division' => $this->input->post('consumables_gstdivision_' . $i),
                    'brand_name' => $this->input->post('consumables_brand_name_' . $i)
                ];
                $this->New_purchase_order_model->insert_purchase_order_items($item_data);
            }
            $i++;
        }
    }
    public function edit($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            $data['title'] = 'Edit Purchase Order';
            
            $data['purchase_order'] = $this->New_purchase_order_model->get_purchase_order_by_id($id);
            $data['purchase_order_items'] = $this->New_purchase_order_model->get_purchase_order_items($id);
            
            if (!$data['purchase_order']) {
                $this->session->set_flashdata('error', 'Purchase order not found!');
                redirect('new_purchase_orders');
            }
            // Check if purchase order can be edited (only pending or rejected orders)
            if ($data['purchase_order']['status'] == 'approved' || $data['purchase_order']['status'] == 'completed') {
                $this->session->set_flashdata('error', 'Cannot edit purchase order that is ' . $data['purchase_order']['status'] . '!');
                redirect('new_purchase_orders');
            }
            // Get vendors
            $data['vendors'] = $this->get_vendors();
            // Get consumables/items
            $data['consumables'] = $this->items_by_vendor_data($data['purchase_order']['vendor_number']);
            // Get centers
            $data['centers'] = $this->get_centers();
            $data['departments'] = $this->get_departments_by_center();
            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
            $this->load->view('new_purchase_orders/edit', $data);
            $this->load->view($template['footer']);
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Update purchase order
    public function update($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            if ($this->input->post()) {
                $po = $this->New_purchase_order_model->get_purchase_order_by_id($id);
                if ($po && ($po['status'] == 'approved' || $po['status'] == 'completed')) {
                    $this->session->set_flashdata('error', 'Cannot update purchase order that is ' . $po['status'] . '!');
                    redirect('new_purchase_orders');
                    return;
                }
                
                $po_data = [
                    'vendor_number' => $this->input->post('vendor_number'),
                    'ship_to' => $this->input->post('ship_to'),
                    'bill_to' => $this->input->post('bill_to'),
                    'department' => $this->input->post('department')
                ];
                // Update purchase order
                if ($this->New_purchase_order_model->update_purchase_order($id, $po_data)) {
                    // Delete existing items
                    $this->New_purchase_order_model->delete_purchase_order_items($id);
                    // Get PO number
                    $po = $this->New_purchase_order_model->get_purchase_order_by_id($id);
                    // Insert new items
                    $this->save_purchase_order_items($id, $po['po_number']);
                    // Update total amount
                    $this->New_purchase_order_model->update_total_amount($id);
                    $this->session->set_flashdata('success', 'Purchase order updated successfully!');
                    redirect('new_purchase_orders');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update purchase order!');
                    redirect('new_purchase_orders/edit/' . $id);
                }
            }
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // View purchase order
    public function view($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            $data['title'] = 'View Purchase Order';

            $data['purchase_order'] = $this->New_purchase_order_model->get_purchase_order_by_id($id);
            $data['purchase_order_items'] = $this->New_purchase_order_model->get_purchase_order_items($id);

            if (!$data['purchase_order']) {
                $this->session->set_flashdata('error', 'Purchase order not found!');
                redirect('new_purchase_orders');
            }
            // Get current stock levels for each PO item
            if (!empty($data['purchase_order_items'])) {
                $po_center = isset($data['purchase_order']['ship_to']) ? $data['purchase_order']['ship_to'] : null;
                $center_id=$this->get_center_id_from_number($po_center);
                $po_department = isset($data['purchase_order']['department']) ? $data['purchase_order']['department'] : null;
                foreach ($data['purchase_order_items'] as &$item) {
                    $medicine_id = $item['item_number'];
                    $medicine_item = $this->Stock_model_new->get_medicine_by_id($medicine_id,$center_id,$po_department,$po_center);
                    $item['unit'] =$medicine_item->unit ?? null;
                    //$item['pack_size'] =$medicine_item->pack_size ?? 1;
                    $item['min_stock_level'] =$medicine_item->min_stock_level ?? null;
                    $item['max_stock_level'] =$medicine_item->max_stock_level ?? null;
                    if (is_null($center_id)) {
                        $item['current_quantity'] = $medicine_item->current_stock;
                    } else {
                        $item['current_quantity'] =
                            $this->Stock_model_new->get_center_stock_quantity_for_po(
                                $center_id,
                                $medicine_id,
                                'ACTIVE',
                                $po_department
                            ) ?? $medicine_item->current_stock;
                    }
                }
            }

            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
            $data['employee_detail_number'] = $this->employee_detail_number($data['purchase_order']['created_by']);
            $this->load->view('new_purchase_orders/view', $data);
            $this->load->view($template['footer']);
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Delete purchase order
    public function delete($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            if ($this->New_purchase_order_model->delete_purchase_order($id)) {
                $this->session->set_flashdata('success', 'Purchase order deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete purchase order!');
            }
            redirect('new_purchase_orders');
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Approve purchase order
    public function approve($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            // Check if user is administrator
            if ($logg['role'] != 'administrator') {
                $this->session->set_flashdata('error', 'Only administrators can approve purchase orders!');
                redirect('new_purchase_orders');
                return;
            }
            
            $approved_by = $this->session->userdata('user_id');
            
            if ($this->New_purchase_order_model->update_purchase_order_status($id, 'approved', $approved_by)) {
                $this->session->set_flashdata('success', 'Purchase order approved successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to approve purchase order!');
            }
            redirect('new_purchase_orders');
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Selective approve purchase order - approve only selected items
    public function selective_approve($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            if ($logg['role'] != 'administrator') {
                $this->session->set_flashdata('error', 'Only administrators can approve purchase orders!');
                redirect('new_purchase_orders/view/' . $id);
                return;
            }
            $selected_items = $this->input->post('selected_items');

            if (empty($selected_items)) {
                $this->session->set_flashdata('error', 'Please select at least one item to approve!');
                redirect('new_purchase_orders/view/' . $id);
                return;
            }
            $all_items = $this->New_purchase_order_model->get_purchase_order_items($id);
            $all_item_ids = array_column($all_items, 'id');
            $deselected_items = array_diff($all_item_ids, $selected_items);
            if (!empty($deselected_items)) {
                foreach ($deselected_items as $item_id) {
                    $this->New_purchase_order_model->delete_purchase_order_item($item_id);
                }
            }
            $approved_by = $this->session->userdata('user_id');
            if ($this->New_purchase_order_model->update_purchase_order_status($id, 'approved', $approved_by)) {
                $this->New_purchase_order_model->update_total_amount($id);
                $selected_count = count($selected_items);
                $deselected_count = count($deselected_items);
                $message = 'Purchase order approved successfully!';
                if ($selected_count > 0) {
                    $message .= ' ' . $selected_count . ' item(s) approved.';
                }
                if ($deselected_count > 0) {
                    $message .= ' ' . $deselected_count . ' deselected item(s) removed.';
                }

                $this->session->set_flashdata('success', $message);
            } else {
                $this->session->set_flashdata('error', 'Failed to approve purchase order!');
            }
            redirect('new_purchase_orders/view/' . $id);
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Reject purchase order
    public function reject($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            // Check if user is administrator
            if ($logg['role'] != 'administrator') {
                $this->session->set_flashdata('error', 'Only administrators can reject purchase orders!');
                redirect('new_purchase_orders');
                return;
            }
            
            if ($this->New_purchase_order_model->update_purchase_order_status($id, 'rejected')) {
                $this->session->set_flashdata('success', 'Purchase order rejected successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to reject purchase order!');
            }
            redirect('new_purchase_orders');
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Complete purchase order
    public function complete($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            if ($this->New_purchase_order_model->update_purchase_order_status($id, 'completed')) {
                $this->session->set_flashdata('success', 'Purchase order marked as completed!');
            } else {
                $this->session->set_flashdata('error', 'Failed to complete purchase order!');
            }
            redirect('new_purchase_orders');
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Get vendors (you may need to adjust this based on your existing vendor structure)
    private function get_vendors() {
        // This is a placeholder - adjust based on your vendor table structure
        $this->db->select('vendor_number, name,ID',);
        $this->db->from('hms_vendors');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get consumables/items (you may need to adjust this based on your existing structure)
    /**
     * Gets a list of all active medicines for selection.
     * Replaces the old get_consumables() function.
     */
    private function get_medicines_list() { // Renamed for clarity
        try {
            $this->db->select([
                'm.id as item_number', 
                'm.medicine_name as item_name',
                'm.medicine_code', 
                'm.generic_name',
                'm.pack_size',
                'm.gst_rate as gstrate',
                'm.hsn_code as hsn',
                'b.brand_name',
                'b.manufacturer as company'
            ]);
            $this->db->from('medicines m');
            $this->db->join('medicine_brands b', 'm.brand_id = b.id', 'left'); 
            $this->db->where('m.status', 'active'); // Filter by active status
            $this->db->order_by('m.medicine_name', 'ASC');
            $query = $this->db->get();
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error fetching medicines list: ' . $e->getMessage());
            return []; // Return empty array on error
        }
    }

    // AJAX: Get consumables/items filtered by vendor
    // public function items_by_vendor() {
    //     $logg = checklogin();
    //     if($logg['status'] != true) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(401)
    //             ->set_output(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
    //     }

    //     $vendor_number = $this->input->get('vendor_number');
    //     if (empty($vendor_number)) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => 'error', 'message' => 'vendor_number is required']));
    //     }

    //     $this->db->select('item_number, item_name, batch_number, quantity, price, vendor_price, mrp, pack_size, gstrate, hsn, gstdivision, company, brand_name, vendor_number');
    //     $this->db->from('hms_stocks');
    //     $this->db->where('status', '1');
    //     $this->db->where('vendor_number', $vendor_number);
    //     $items = $this->db->get()->result_array();

    //     return $this->output->set_content_type('application/json')
    //         ->set_status_header(200)
    //         ->set_output(json_encode(['status' => 'success', 'data' => $items]));
    // }
    /**
     * AJAX endpoint to fetch distinct medicines associated with a vendor
     * based on purchase history (medicine_batches).
     * Uses the NEW schema.
     */
  /**
     * AJAX endpoint to fetch distinct medicines associated with a vendor
     * based on purchase history (medicine_batches).
     * Uses the NEW schema.
     */
    // public function items_by_vendor() {
    //     $logg = checklogin();
    //     if($logg['status'] != true) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(401) // Unauthorized
    //             ->set_output(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
    //     }
    //     $vendor_number = $this->input->get('vendor_number'); // Get vendor number from GET request
    //     if (empty($vendor_number)) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(400) // Bad Request
    //             ->set_output(json_encode(['status' => 'error', 'message' => 'vendor_number is required']));
    //     }
    //     $this->load->model('New_purchase_order_model'); // Load your model
    //     $items = $this->New_purchase_order_model->get_medicines_by_vendor($vendor_number);
    //     // 3. Return JSON response
    //     return $this->output->set_content_type('application/json')
    //         ->set_status_header(200) // OK
    //         ->set_output(json_encode(['status' => 'success', 'data' => $items]));
    // }
    public function items_by_vendor_data($vendor_number = null)
    {
        $logg = checklogin();
        if ($logg['status'] != true) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
        }
        if (empty($vendor_number)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'error', 'message' => 'vendor_number is required']));
        }

        $this->load->model('New_purchase_order_model');
        $items = $this->New_purchase_order_model->get_medicines_by_vendor($vendor_number);
        return $items;
    }
    /**
     * AJAX endpoint to check if adding quantity to PO would exceed max stock level
     */
    public function check_stock_level()
    {
        $logg = checklogin();
        if ($logg['status'] !== true) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ]));
        }
        $medicine_id       = (int) $this->input->get('medicine_id');
        $quantity          = (int) $this->input->get('quantity');
        $ship_to_center_id = $this->input->get('ship_to_center_id');
        $department        = strtoupper(trim($this->input->get('department')));
        if ($ship_to_center_id === 'CENTRAL_WAREHOUSE_NOIDA') {
                $this->db->select('COALESCE(SUM(cs.available_quantity), 0) AS current_stock');
                $this->db->from('central_stocks AS cs');
                $this->db->join(
                        'medicine_batches AS mb',
                        'cs.batch_id = mb.id
                        AND mb.medicine_id = ' . (int)$medicine_id,
                        'LEFT'   
                );
                $this->db->where('cs.status', 'ACTIVE');
                $this->db->where_in('mb.batch_status', ['ACTIVE', 'EXPIRED']);
                $stock_result = $this->db->get()->row();
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => 'success',
                    'can_order' => true,
                    'current_stock' => $stock_result->current_stock,
                    'min_stock' => 100,
                    'max_stock' => 1000,
                    'reorder_level' => 500,
                    'message' => 'No max stock limit set'
                ]));
        }

        if (empty($medicine_id) || $quantity <= 0 || empty($ship_to_center_id) || empty($department)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'medicine_id, quantity, center and department are required'
                ]));
        }
        $center_data = $this->get_center_by_id($ship_to_center_id);
        if (!$center_data) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid center'
                ]));
        }
        $center_id = (int) $center_data->ID;
        $stock_info = $this->Stock_model_new->get_medicine_stock_info($medicine_id, $center_id, $department);
        if (!$stock_info) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => 'success',
                    'can_order' => true,
                    'message' => 'Stock configuration not found for this center/department'
                ]));
        }
        $current_stock = (int) $stock_info->current_stock;
        $min_stock     = (int) $stock_info->min_stock_level;
        $max_stock     = (int) $stock_info->max_stock_level;
        $reorder_level = (int) $stock_info->reorder_level;
        $total_after_po = $current_stock + $quantity;
        // No max limit set
        if ($max_stock <= 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => 'success',
                    'can_order' => true,
                    'current_stock' => $current_stock,
                    'min_stock' => $min_stock,
                    'max_stock' => $max_stock,
                    'reorder_level' => $reorder_level,
                    'message' => 'No max stock limit set'
                ]));
        }

        $can_order = ($total_after_po <= $max_stock);

        $message = $can_order
            ? "OK – Stock after PO: {$total_after_po} (Max: {$max_stock})"
            : "Max stock exceeded! Current: {$current_stock}, Max: {$max_stock}, After PO: {$total_after_po}";

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'can_order' => $can_order,
                'current_stock' => $current_stock,
                'min_stock' => $min_stock,
                'max_stock' => $max_stock,
                'reorder_level' => $reorder_level,
                'po_quantity' => $quantity,
                'total_after_po' => $total_after_po,
                'reorder_warning' => ($reorder_level > 0 && $current_stock <= $reorder_level),
                'critical_min_warning' => ($min_stock > 0 && $current_stock < $min_stock),
                'message' => $message
            ]));
    }

    // public function check_stock_level() {
    //     $logg = checklogin();
    //     if ($logg['status'] != true) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(401)
    //             ->set_output(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
    //     }

    //     $medicine_id = $this->input->get('medicine_id');
    //     $quantity = (int) $this->input->get('quantity');
    //     $ship_to_center_id = $this->input->get('ship_to_center_id');
    //     $center_data =$this->get_center_by_id($ship_to_center_id);
    //     $center_id =$center_data->ID;
    //     $department = $this->input->get('department');

    //     if (empty($medicine_id) || $quantity <= 0) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => 'error', 'message' => 'medicine_id and quantity are required']));
    //     }

    //     $stock_info = $this->Stock_model_new->get_medicine_stock_info($medicine_id, $center_id, $department);
        
    //     if (!$stock_info) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode([
    //                 'status' => 'success',
    //                 'can_order' => true,
    //                 'message' => 'Stock information not available'
    //             ]));
    //     }
        
    //     $current_stock = (int) $stock_info->current_stock;
    //     $max_stock = (int) $stock_info->max_stock_level;
    //     $total_after_po = $current_stock + $quantity;
        
    //     // If max_stock_level is 0 or null, allow ordering (no limit set)
    //     if ($max_stock <= 0) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode([
    //                 'status' => 'success',
    //                 'can_order' => true,
    //                 'current_stock' => $current_stock,
    //                 'max_stock' => $max_stock,
    //                 'message' => 'No max stock limit set'
    //             ]));
    //     }
        
    //     $can_order = $total_after_po <= $max_stock;
    //     $message = $can_order 
    //         ? "OK - Stock after PO: {$total_after_po} (Max: {$max_stock})"
    //         : "Max stock exceeded! Current: {$current_stock}, Max: {$max_stock}, After PO: {$total_after_po}";
        
    //     return $this->output->set_content_type('application/json')
    //         ->set_status_header(200)
    //         ->set_output(json_encode([
    //             'status' => 'success',
    //             'can_order' => $can_order,
    //             'current_stock' => $current_stock,
    //             'max_stock' => $max_stock,
    //             'po_quantity' => $quantity,
    //             'total_after_po' => $total_after_po,
    //             'message' => $message
    //         ]));
    // }

    public function items_by_vendor()
    {
        $logg = checklogin();
        if ($logg['status'] != true) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
        }
        $vendor_number = $this->input->get('vendor_number');
        if (empty($vendor_number)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'error', 'message' => 'vendor_number is required']));
        }

        $this->load->model('New_purchase_order_model');
        $items = $this->New_purchase_order_model->get_medicines_by_vendor($vendor_number);
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(['status' => 'success', 'data' => $items]));
    }


    // Get departments from new stocks module
    private function get_departments() {
        $this->db->select('department');
        $this->db->from($this->config->item('db_prefix') . 'employees');
        $this->db->where('status', '1');
        $this->db->where('department !=', '');
        $this->db->group_by('department');
        $this->db->order_by('department', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get centers from new stocks module
    private function get_centers() {
        $centers = $this->Stock_model_new->get_all_centers();
        // Convert objects to arrays for consistency
        $centers_array = array();
        foreach ($centers as $center) {
            $centers_array[] = array(
                'center_number' =>  $center->center_number,
                'center_name' => $center->center_name ?? $center->name ?? '',
                'center_address' => $center->center_address ?? $center->address ?? ''
            );
        }
        return $centers_array;
    }

    public function status() {
        $logg = checklogin();
        if($logg['status'] == true) {
            // Check if user is administrator
            if ($logg['role'] != 'administrator') {
                $this->session->set_flashdata('error', 'Only administrators can access this page!');
                redirect('new_purchase_orders');
                return;
            }
            
            $data['title'] = 'Pending Purchase Orders - Administrator';
            $data['user_role'] = $logg['role'];
            
            // Get only pending purchase orders
            $filters = ['status' => 'pending'];
            
            // Pagination
            $page = $this->input->get('page') ? $this->input->get('page') : 1;
            $limit = 20;
            $start = ($page - 1) * $limit;
            
            $data['purchase_orders'] = $this->New_purchase_order_model->get_purchase_orders($limit, $start, $filters);
            $data['total_count'] = $this->New_purchase_order_model->count_purchase_orders($filters);
            $data['current_page'] = $page;
            $data['total_pages'] = ceil($data['total_count'] / $limit);
            
            // Get counts for dashboard
            $data['pending_count'] = $this->New_purchase_order_model->count_purchase_orders(['status' => 'pending']);
            $data['approved_count'] = $this->New_purchase_order_model->count_purchase_orders(['status' => 'approved']);
            $data['rejected_count'] = $this->New_purchase_order_model->count_purchase_orders(['status' => 'rejected']);
            
            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
            $this->load->view('new_purchase_orders/status', $data);
            $this->load->view($template['footer']);
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Get center name by center number
    function get_center_name($center){
        $name = $this->Accounts_model->get_center_name($center);
        return $name;
    }

    public function update_status()
    {
        $this->load->model('New_purchase_order_model');
        $id     = $this->input->post('id');
        $status = $this->input->post('status');
        $remarks = $this->input->post('remarks');
        
        if (empty($id) || !in_array($status, ['0', '1'])) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request!']));
        }
        
        $updated = $this->New_purchase_order_model->update_status($id, $status, $remarks);
        if ($updated) {
            $message = 'Purchase Order status updated successfully!';
        } else {
            $message = 'Failed to update status. Please try again.';
        }
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(['status' => $updated ? 'success' : 'error', 'message' => $message]));
    }

    public function bulk_update_status()
    {
        $this->load->model('New_purchase_order_model');
        $ids    = $this->input->post('ids');
        $status = $this->input->post('status');
        $remarks = $this->input->post('remarks');
        
        if (empty($ids) || !is_array($ids) || !in_array($status, ['0', '1'])) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request!']));
        }
        
        $success_count = 0;
        $total_count = count($ids);
        
        foreach ($ids as $id) {
            if ($this->New_purchase_order_model->update_status($id, $status, $remarks)) {
                $success_count++;
            }
        }
        
        if ($success_count == $total_count) {
            $message = "All {$total_count} purchase order(s) updated successfully!";
            $response_status = 'success';
        } elseif ($success_count > 0) {
            $message = "{$success_count} out of {$total_count} purchase order(s) updated successfully!";
            $response_status = 'warning';
        } else {
            $message = "Failed to update any purchase orders. Please try again.";
            $response_status = 'error';
        }
        
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(['status' => $response_status, 'message' => $message]));
    }

    // Print purchase order
    public function print_po($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            $data['purchase_order'] = $this->New_purchase_order_model->get_purchase_order_by_id($id);
            $data['purchase_order_items'] = $this->New_purchase_order_model->get_purchase_order_items($id);
            
            if (!$data['purchase_order']) {
                $this->session->set_flashdata('error', 'Purchase order not found!');
                redirect('new_purchase_orders');
            }
            
            // Check if purchase order is approved or completed
            if ($data['purchase_order']['status'] != 'approved' && $data['purchase_order']['status'] != 'completed') {
                $this->session->set_flashdata('error', 'Only approved or completed purchase orders can be printed!');
                redirect('new_purchase_orders');
            }
            
            // Get vendor data
            $this->load->model('Vendors_model');
            $data['vendor_data'] = $this->Vendors_model->get_vendor_name_by_vendor_id($data['purchase_order']['vendor_number']);
            // Get center addresses
            $this->load->model('Center_model');
            $bill_to_center = $this->Center_model->get_item_data($data['purchase_order']['bill_to']);
            $ship_to_center = $this->Center_model->get_item_data($data['purchase_order']['ship_to']);
            $data['bill_to_address'] = $bill_to_center ? $bill_to_center['center_name'] . ', ' . $bill_to_center['center_location'] : 'N/A';
            $data['ship_to_address'] = $ship_to_center ? $ship_to_center['center_name'] . ', ' . $ship_to_center['center_location'] : 'N/A';
            $this->load->view('new_purchase_orders/print', $data);
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Purchase Order Receipt page
    // public function new_add_stock($id) {
    //     $logg = checklogin();
    //     if($logg['status'] == true) {
    //         $data['purchase_order'] = $this->New_purchase_order_model->get_purchase_order_by_id($id);
    //         $data['purchase_order_items'] = $this->New_purchase_order_model->get_purchase_order_items($id);
            
    //         if (!$data['purchase_order']) {
    //             $this->session->set_flashdata('error', 'Purchase order not found!');
    //             redirect('new_purchase_orders');
    //         }
    //         if ($data['purchase_order']['status'] != 'completed') {
    //             $this->session->set_flashdata('error', 'Only completed purchase orders can be processed for stock addition!');
    //             redirect('new_purchase_orders');
    //         }
    //         $this->load->model('Vendors_model');
    //         $vendor_data = $this->Vendors_model->get_vendor_data_by_vendor_number($data['purchase_order']['vendor_number']);
    //         $data['vendor_data'] = $vendor_data[0];
    //         $this->load->model('Center_model');
    //         $bill_to_center = $this->Center_model->get_item_data($data['purchase_order']['bill_to']);
    //         $ship_to_center = $this->Center_model->get_item_data($data['purchase_order']['ship_to']);
    //         $data['bill_to_address'] = $bill_to_center ? $bill_to_center['center_name'] . ', ' . $bill_to_center['center_location'] : 'N/A';
    //         $data['ship_to_address'] = $ship_to_center ? $ship_to_center['center_name'] . ', ' . $ship_to_center['center_location'] : 'N/A';
    //         // Get vendors and consumables for the form
    //         $data['vendors'] = $this->get_vendors();
    //         $data['consumables'] = $this->get_consumables();
            
            
    //         // Load existing uploaded files
    //         $data['uploaded_files'] = $this->get_uploaded_files($data['purchase_order']['po_number']);
            
    //         $data['title'] = 'Purchase Order Receipt - Inventory';
    //         $template = get_header_template($logg['role']);
    //         $this->load->view($template['header']);
    //         $this->load->view('new_purchase_orders/new_add_stock', $data);
    //         $this->load->view($template['footer']);
    //     } else {
    //         header("location:" .base_url(). "");
    //         die;
    //     }
    // }
    public function new_add_stock($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            $data['purchase_order'] = $this->New_purchase_order_model->get_purchase_order_by_id($id);
            $data['purchase_order_items'] = $this->New_purchase_order_model->get_purchase_order_items($id);
            if (!$data['purchase_order']) {
                $this->session->set_flashdata('error', 'Purchase order not found!');
                redirect('new_purchase_orders'); 
                return; 
            }
            $data['vendor_id'] = $data['purchase_order']['vendor_number'] ?? null;
            $bill_to_id = $data['purchase_order']['bill_to'] ?? null;
            $ship_to_id = $data['purchase_order']['ship_to'] ?? null;
            
            // Check if it's Central Warehouse Noida
            $is_central_warehouse = ($ship_to_id === 'CENTRAL_WAREHOUSE_NOIDA');
            $data['is_central_warehouse'] = $is_central_warehouse;
            
            $bill_to_center = ($bill_to_id && $bill_to_id !== 'CENTRAL_WAREHOUSE_NOIDA') ? $this->get_center_by_id($bill_to_id) : null;
            $ship_to_center = ($ship_to_id && !$is_central_warehouse) ? $this->get_center_by_id($ship_to_id) : null;
            
            $data['bill_to_address'] = $bill_to_center ? ($bill_to_center->center_name . ', ' . $bill_to_center->center_address) : ($bill_to_id === 'CENTRAL_WAREHOUSE_NOIDA' ? 'Central Warehouse Noida' : 'N/A');
            $data['ship_to_address'] = $is_central_warehouse ? 'Central Warehouse Noida' : ($ship_to_center ? ($ship_to_center->center_name . ', ' . $ship_to_center->center_address) : 'N/A');
            $data['ship_to_center_id'] = $is_central_warehouse ? null : ($ship_to_center ? $ship_to_center->ID : null);
            if (!$is_central_warehouse && empty($data['ship_to_center_id'])) {
                 $this->session->set_flashdata('error', 'Valid destination center (Ship To) not found for this PO!');
                 // Optionally redirect back, or let the view handle showing an error
                 // redirect('new_purchase_orders/view/' . $id);
                 // return;
            }

            $data['vendors']=$this->get_vendor_list();
            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
            $this->load->view('new_purchase_orders/new_add_stock', $data); 
            $this->load->view($template['footer']);

        } else {
            redirect(base_url()); // Redirect if not logged in
        }
    }
    /**
     * Fetches details for a single vendor from hms_vendors based on vendor_number.
     */
    /**
     * Fetches a list of all vendors from hms_vendors.
     * Returns an array of vendor objects.
     */
    public function get_vendor_list(){
    {
        try {
            $this->db->select('ID, name, company_name, company_address, gst_number'); // Add more if needed
            $this->db->from('hms_vendors');
            $query = $this->db->get();
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error fetching vendor list: ' . $e->getMessage());
            return [];
        }
    }

    }
    public function get_vendor_by_number($vendor_number)
    {
        if (empty($vendor_number)) {
            return null;
        }
        try {
            // Select desired columns (e.g., ID, name, company_name)
            $this->db->select('ID, name, company_name, company_address, gst_number'); // Add more if needed
            $this->db->from('hms_vendors');
            $this->db->where('vendor_number', $vendor_number);
            $this->db->where('status', 1); // Optional: only active vendors
            $this->db->limit(1);
            $query = $this->db->get();
            return $query->row(); // Return single object or null
        } catch (Exception $e) {
            log_message('error', 'Error in get_vendor_by_number: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetches details for a single center from hms_centers based on primary key ID.
     */
    public function get_center_by_id($center_code)
    {
        if (empty($center_code) || !is_numeric($center_code)) {
            return null;
        }
        try {
            // Select desired columns
            $this->db->select('ID, center_name, center_address, center_location'); // Add more if needed
            $this->db->from('hms_centers');
            $this->db->where('center_number', $center_code);
            $this->db->where('status', 1); // Optional: only active centers
            $this->db->limit(1);
            $query = $this->db->get();
            return $query->row(); // Return single object or null
        } catch (Exception $e) {
            log_message('error', 'Error in get_center_by_id: ' . $e->getMessage());
            return null;
        }
    }

    // Save Purchase Order Receipt
    public function save_receipt() {
        $logg = checklogin();
        if($logg['status'] == true) {
            if ($this->input->post()) {
                $receipt_data = [
                    'por_number' => $this->input->post('por_number'),
                    'supplier_name' => $this->input->post('supplier_name'),
                    'po_number' => $this->input->post('po_number'),
                    'reference' => $this->input->post('reference'),
                    'credit_term' => $this->input->post('credit_term'),
                    'ship_to' => $this->input->post('ship_to'),
                    'reference_amount' => $this->input->post('reference_amount'),
                    'por_date' => $this->input->post('por_date'),
                    'po_date' => $this->input->post('po_date'),
                    'reference_date' => $this->input->post('reference_date'),
                    'created_by' => $this->session->userdata('user_id'),
                    'status' => 'pending'
                ];
                
                $receipt_id = $this->New_purchase_order_model->insert_purchase_order_receipt($receipt_data);
                if ($receipt_id) {
                    $this->save_receipt_items($receipt_id);
                    $this->session->set_flashdata('success', 'Purchase order receipt created successfully!');
                    redirect('new_purchase_orders/purchase_order_receipt');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create purchase order receipt!');
                    redirect('new_purchase_orders/purchase_order_receipt');
                }
            }
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Save receipt items
    private function save_receipt_items($receipt_id) {
        $items = [];
        $i = 1;
        
        while ($this->input->post('product_' . $i)) {
            if ($this->input->post('receive_item_' . $i)) {
                $items[] = [
                    'receipt_id' => $receipt_id,
                    'product_id' => $this->input->post('product_' . $i),
                    'description' => $this->input->post('description_' . $i),
                    'uom' => $this->input->post('uom_' . $i),
                    'qty_remain' => $this->input->post('qty_remain_' . $i),
                    'receive_all' => $this->input->post('receive_all_' . $i) ? 1 : 0,
                    'qty_receiving' => $this->input->post('qty_receiving_' . $i),
                    'qty_rejected' => $this->input->post('qty_rejected_' . $i),
                    'discount' => $this->input->post('discount_' . $i),
                    'include_tax' => $this->input->post('include_tax_' . $i) ? 1 : 0,
                    'tax_amount' => $this->input->post('tax_amount_' . $i),
                    'amount' => $this->input->post('amount_' . $i)
                ];
            }
            $i++;
        }
        
        if (!empty($items)) {
            $this->New_purchase_order_model->insert_receipt_items($items);
        }
    }

    // Stock Transfer page
    public function stock_transfer() {
        $logg = checklogin();
        if($logg['status'] == true) {
            $data['title'] = 'Stock Transfer';
            
            // Get centers for location selection
            $data['centers'] = $this->get_centers();
            
            // Generate transfer number
            $data['transfer_number'] = $this->New_purchase_order_model->generate_transfer_number();
            
            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
            $this->load->view('new_purchase_orders/stock_transfer', $data);
            $this->load->view($template['footer']);
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Save Stock Transfer
    public function save_stock_transfer() {
        $logg = checklogin();
        if($logg['status'] == true) {
            if ($this->input->post()) {
                $transfer_data = [
                    'from_location' => $this->input->post('from_location'),
                    'from_project' => $this->input->post('from_project'),
                    'to_location' => $this->input->post('to_location'),
                    'to_project' => $this->input->post('to_project'),
                    'description' => $this->input->post('description'),
                    'transfer_number' => $this->input->post('transfer_number'),
                    'ref_number' => $this->input->post('ref_number'),
                    'doc_date' => $this->input->post('doc_date'),
                    'created_by' => $this->session->userdata('user_id'),
                    'status' => 'pending'
                ];
                
                $transfer_id = $this->New_purchase_order_model->insert_stock_transfer($transfer_data);
                if ($transfer_id) {
                    $this->save_stock_transfer_items($transfer_id);
                    $this->session->set_flashdata('success', 'Stock transfer created successfully!');
                    redirect('new_purchase_orders/stock_transfer');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create stock transfer!');
                    redirect('new_purchase_orders/stock_transfer');
                }
            }
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Save Add Stock - Main method for processing stock addition from purchase order receipt
    // public function save_add_stock() {
    //     $logg = checklogin();
    //     if($logg['status'] == true) {
    //         if ($this->input->post()) {
    //             $po_id = $this->input->get('id');
    //             if (!$po_id) {
    //                 $this->session->set_flashdata('error', 'Purchase Order ID is required!');
    //                 redirect('new_purchase_orders');
    //                 return;
    //             }
                
    //             // Handle file uploads
    //             $uploaded_files = [];
    //             if (!empty($_FILES['receipt_files']['name'][0])) {
    //                 $uploaded_files = $this->handleFileUploads();
    //             }
    //             $purchase_order = $this->New_purchase_order_model->get_purchase_order_by_id($po_id);
    //             if (!$purchase_order) {
    //                 $this->session->set_flashdata('error', 'Purchase order not found!');
    //                 redirect('new_purchase_orders');
    //                 return;
    //             }
    //             if ($purchase_order['status'] != 'completed') {
    //                 $this->session->set_flashdata('error', 'Only completed purchase orders can be processed for stock addition!');
    //                 redirect('new_purchase_orders');
    //                 return;
    //             }
    //             $success_count = 0;
    //             $total_items = 0;
    //             $vendor_billing_data = [];
    //             $i = 1;
    //             while ($this->input->post('product_' . $i)) {
    //                 $product_id = $this->input->post('product_' . $i);
    //                 $qty_receiving = floatval($this->input->post('qty_receiving_' . $i)) ?: 0;
    //                 $qty_rejected = floatval($this->input->post('qty_rejected_' . $i)) ?: 0;
    //                 $free_qty = floatval($this->input->post('free_qty_' . $i)) ?: 0;
    //                 if ($qty_receiving > 0) {
    //                     $total_items++;
    //                     $po_items = $this->New_purchase_order_model->get_purchase_order_items($po_id);
    //                     $item_details = null;
    //                     foreach ($po_items as $item) {
    //                         if ($item['item_number'] == $product_id) {
    //                             $item_details = $item;
    //                             break;
    //                         }
    //                     }
    //                     if ($item_details) {
    //                         $stock_data = [
    //                             'item_name' => $item_details['item_name'],
    //                             'company' => $item_details['company'],
    //                             'brand_name' => $item_details['brand_name'],
    //                             'generic_name' => $item_details['generic_name'] ?? '',
    //                             'vendor_number' => $purchase_order['vendor_number'],
    //                             'batch_number' => $this->input->post('batch_number_' . $i) ?: $item_details['batch_number'],
    //                             'quantity' => $qty_receiving + $free_qty,
    //                             'price' => $item_details['price'],
    //                             'vendor_price' => $this->input->post('unit_price_' . $i) ?: $item_details['vendor_price'],
    //                             'mrp' => $item_details['mrp'],
    //                             'hsn' => $item_details['hsn'],
    //                             'pack_size' => $item_details['pack_size'],
    //                             'gstrate' => intval($item_details['tax_percentage']),
    //                             'gstdivision' => $item_details['gst_division'] ?? 0,
    //                             'expiry' => $this->input->post('expiry_date_' . $i),
    //                             'expiry_day' => $this->input->post('notify_expiry_' . $i),
    //                             'date_of_purchase' => $this->input->post('po_date'),
    //                             'invoice_no' => $this->input->post('reference') ?: 'N/A',
    //                             'no_of_item' => '1',
    //                             'product_id' => 0,
    //                             'lots' => 1.0,
    //                             'units' => $qty_receiving + $free_qty,
    //                             'safety_stock' => 0,
    //                             'order_qty' => 0,
    //                             'category' => 0,
    //                             'pack' => 1,
    //                             'type' => 'medicine',
    //                             'medicine_type' => null,
    //                             'status' => 1
    //                         ];
    //                         $existing_stock = $this->New_purchase_order_model->check_existing_stock_item($item_details['item_name'], $stock_data['batch_number'], $purchase_order['vendor_number']);
    //                         if ($existing_stock) {
    //                             $update_result = $this->New_purchase_order_model->update_stock_quantity($existing_stock['ID'], $stock_data['quantity'], $stock_data);
    //                             if ($update_result) {
    //                                 $success_count++;
    //                             }
    //                         } else {
    //                             $insert_result = $this->New_purchase_order_model->insert_stock_item($stock_data);
    //                             if ($insert_result) {
    //                                 $success_count++;
    //                             }
    //                         }
    //                         $vendor_billing_data[] = [
    //                             'purchase_po_no' => $purchase_order['po_number'],
    //                             'po_date' => $this->input->post('po_date'),
    //                             'vendor_name' => $this->get_vendor_name($purchase_order['vendor_number']),
    //                             'vendor_code' => $purchase_order['vendor_number'],
    //                             'order_number' => $purchase_order['po_number'],
    //                             'upload_date' => date("Y-m-d H:i:s"),
    //                             'invoice_no' => $this->input->post('reference') ?: 'N/A',
    //                             'brand_name' => $item_details['brand_name'],
    //                             'mrp' => floatval($item_details['mrp']),
    //                             'hsn' => $item_details['hsn'],
    //                             'category' => $item_details['company'],
    //                             'date_of_purchase' => $this->input->post('po_date'),
    //                             'batch_number' => $stock_data['batch_number'],
    //                             'centre_location' => $this->input->post('ship_to'),
    //                             'received_by' => $this->input->post('receive_by'),
    //                             'date_of_receiving' => $this->input->post('receipt_date'),
    //                             'item_number' => $product_id,
    //                             'item_name' => $item_details['item_name'],
    //                             'company' => $item_details['company'],
    //                             'quantity' => $qty_receiving,
    //                             'expiry' => $stock_data['expiry'],
    //                             'vendor_price' => $stock_data['vendor_price'],
    //                             'gstrate' => floatval($item_details['tax_percentage']),
    //                             'discount_amt' => $this->input->post('discount_' . $i) ?: 0,
    //                             'free_quantity' => $free_qty,
    //                             'total_purchase_value_excl_gst' => ($qty_receiving * $stock_data['vendor_price']),
    //                             'freight_forwarding_charges' => 0,
    //                             'comment' => $this->input->post('comments_' . $i) ?: '',
    //                             'vendor_billing' => json_encode($uploaded_files),
    //                             'rate_per_unit' => $stock_data['vendor_price'],
    //                             'total_purchase_after_discount_exculding_gst' => ($qty_receiving * $stock_data['vendor_price']),
    //                             'total_purchase_value_incl_gst' => ($qty_receiving * $stock_data['vendor_price'] * (1 + floatval($item_details['tax_percentage']) / 100)),
    //                             'monetary_value' => 'INR',
    //                             'discount_rate' => '0',
    //                             'entry_date_in_tally' => null,
    //                             'msme_applicability' => 'No',
    //                             'medicine_type' => null
    //                         ];
    //                     }
    //                 }
    //                 $i++;
    //             }
    //             if (!empty($vendor_billing_data)) {
    //                 foreach ($vendor_billing_data as $billing_data) {
    //                     $this->New_purchase_order_model->insert_vendor_billing($billing_data);
    //                 }
    //             }
    //             $this->New_purchase_order_model->update_purchase_order_status($po_id, 'completed');
    //             if ($success_count == $total_items && $total_items > 0) {
    //                 $this->session->set_flashdata('success', "Stock added successfully for all {$total_items} item(s)!");
    //             } elseif ($success_count > 0) {
    //                 $this->session->set_flashdata('warning', "Stock added for {$success_count} out of {$total_items} item(s). Please check failed items.");
    //             } else {
    //                 $this->session->set_flashdata('error', 'Failed to add stock for any items. Please check your data.');
    //             }

    //             redirect('new_purchase_orders');
    //         }
    //     } else {
    //         header("location:" .base_url(). "");
    //         die;
    //     }
    // }

    // Function to handle file uploads (keep your existing logic or adapt this)
  
    /**
     * Receives stock against a PO using the NEW inventory system.
     */
    // public function save_add_stock() {
    //     $logg = checklogin();
    //     if($logg['status'] == true) {
    //         if ($this->input->post()) {
    //             $po_id_from_get = $this->input->get('id'); 
    //             $po_id_from_post = $this->input->post('po_id');
    //             if (!$po_id_from_get && !$po_id_from_post) {
    //                 $this->session->set_flashdata('error', 'Purchase Order ID is missing!');
    //                 redirect('new_purchase_orders'); 
    //                 return;
    //             }
    //             $po_id = $po_id_from_post ?: $po_id_from_get;
    //             $this->form_validation->set_rules('receipt_date', 'Receipt Date', 'required|trim');
    //             $this->form_validation->set_rules('reference', 'Invoice/Reference Number', 'required|trim');
    //             $this->form_validation->set_rules('product_1', 'First Item', 'required');
    //             if ($this->form_validation->run() == FALSE) {
    //                  $this->session->set_flashdata('error', validation_errors());
    //                  redirect('new_purchase_orders/new_add_stock/' . $po_id);
    //                  return;
    //             }
                
    //             $purchase_order = $this->New_purchase_order_model->get_purchase_order_by_id($po_id);
    //             if (!$purchase_order) {
    //                 $this->session->set_flashdata('error', 'Purchase order not found!');
    //                 redirect('new_purchase_orders'); return;
    //             }
    //             $vendor_id = $purchase_order['vendor_number'];
    //              if (!$vendor_id) {
    //                  $this->session->set_flashdata('error', 'Vendor details not found in the new system for vendor number: ' . $purchase_order['vendor_number']);
    //                  redirect('new_purchase_orders/new_add_stock/' . $po_id); return;
    //              }
    //              $center_id = (int)$this->input->post('center_id');
    //             if(isset($_SESSION['logged_central_stock_manager']['employee_number'])) {
    //              $created_by_id = $this->employee_detail_number($_SESSION['logged_central_stock_manager']['employee_number'])['ID'] ?? null; // Use ID directly if available
    //              if (!$created_by_id) {
    //                   $this->session->set_flashdata('error', 'Could not determine user ID.');
    //                   redirect('new_purchase_orders/new_add_stock/' . $po_id); return;
    //              }
    //             } else {
    //                  $this->session->set_flashdata('error', 'User not logged in as central stock manager.');
    //                  redirect('new_purchase_orders/new_add_stock/' . $po_id); return;
    //             }

    //             // --- Handle File Uploads ---
    //             if(!empty($_FILES['receipt_files']['name'][0])) {
    //                 $uploaded_files_info = $this->handleFileUploads();
    //             }
    //             $file_names_string = !empty($uploaded_files_info) ? json_encode($uploaded_files_info) : null;
    //             // --- Start Transaction ---
    //             $this->db->trans_start();

    //             $success_count = 0;
    //             $processed_items = 0;
    //             $error_messages = [];
    //             $i = 1;
    //             while ($this->input->post('product_' . $i)) { 
    //                 $medicine_id = (int)$this->input->post('product_' . $i); 
    //                 $qty_receiving = (float)($this->input->post('qty_receiving_' . $i) ?: 0);
    //                 $free_qty = (float)($this->input->post('free_qty_' . $i) ?: 0);
    //                 $batch_number = trim($this->input->post('batch_number_' . $i));
    //                 $expiry_date = trim($this->input->post('expiry_date_' . $i));
    //                 $purchase_price = (float)($this->input->post('unit_price_' . $i) ?: 0);
    //                 $tax_percentage = (float)($this->input->post('tax_percentage_' . $i) ?: 0);
    //                 $mrp = (float)($this->input->post('mrp_' . $i) ?: 0); 
    //                 $purchase_price_with_tax = $purchase_price + ($purchase_price * ($tax_percentage / 100));
    //                 $selling_price = (float)($this->input->post('selling_price_' . $i) ?: $mrp); 
    //                 $total_received = $qty_receiving + $free_qty;

    //                 if ($medicine_id > 0 && $total_received > 0 && !empty($batch_number) && !empty($expiry_date) && $purchase_price >= 0) {
    //                     $processed_items++;

    //                     // Prepare data for medicine_batches table
    //                     $batch_data = [
    //                         "medicine_id"       => $medicine_id,
    //                         "vendor_id"         => $vendor_id,
    //                         "batch_number"      => $batch_number,
    //                         "manufacturing_date"=> $this->input->post("mfg_date_" . $i) ?: NULL, // Add if you have this field
    //                         "expiry_date"       => $expiry_date,
    //                         "purchase_price"    => $purchase_price_with_tax,
    //                         "selling_price"     => $selling_price, // Ensure you have this value
    //                         "mrp"               => $mrp,           // Ensure you have this value
    //                         "quantity_purchased"=> $total_received,
    //                         "quantity_remaining"=> $total_received, // Initially, remaining = purchased
    //                         "purchase_date"     => $this->input->post('receipt_date'), // Date stock was received
    //                         "invoice_number"    => $this->input->post('reference'),    // Invoice/Ref number
    //                         "invoice_date"      => $this->input->post('invoice_date') ?: $this->input->post('receipt_date'), // Add invoice date field if possible
    //                         "quality_status"    => "APPROVED", // Or 'PENDING' if QC needed
    //                         "batch_status"      => "ACTIVE",
    //                         "remarks"           => $this->input->post('comments_' . $i), // Item specific comments
    //                         "created_by"        => $created_by_id,
    //                         "created_at"        => date("Y-m-d H:i:s")
    //                     ];
    //                     $add_batch_result = $this->Stock_model_new->add_purchase_batch($batch_data);
    //                     if ($add_batch_result['status'] === 'success') {
    //                         $new_batch_id = $add_batch_result['batch_id'];
    //                         $stock_data = [
    //                             'batch_id' => $new_batch_id,
    //                             'center_id' => $center_id,
    //                             'quantity' => $total_received,
    //                             'department' => $this->input->post('department'),
    //                             'status'   => 'ACTIVE' // from center_stocks table
    //                         ];
    //                         $add_stock_result = $this->Stock_model_new->add_stock_to_location($stock_data);
    //                         if ($add_stock_result['status'] === 'success') {
    //                             // Log the stock movement
    //                             $movement_data = [
    //                                 "batch_id"           => $new_batch_id,
    //                                 "movement_type"      => "PURCHASE",
    //                                 "from_location_type" => "VENDOR",
    //                                 "from_location_id"   => $vendor_id,
    //                                 "to_location_type"   => "CENTER", 
    //                                 "to_location_id"     => $center_id,
    //                                 "quantity_before"    => 0, 
    //                                 "quantity_change"    => $total_received,
    //                                 "quantity_after"     => $add_stock_result['quantity_after'], 
    //                                 "unit_price"         => $purchase_price,
    //                                 "total_value"        => $purchase_price * $total_received,
    //                                 "reference_type"     => "PURCHASE_RECEIPT",
    //                                 "reference_id"       => $po_id, 
    //                                 "reference_number"   => $this->input->post('reference'),
    //                                 "remarks"            => ($i == 1 && $file_names_string) ? "Files: ".$file_names_string : null,
    //                                 "created_by"         => $created_by_id,
    //                                 "created_at"         => date("Y-m-d H:i:s")
    //                             ];
    //                             if ($this->Stock_model_new->log_stock_movement($movement_data)) {
    //                                 $success_count++;
    //                             } else {
    //                                  $error_messages[] = "Item #{$i}: Failed to log stock movement for Batch ID {$new_batch_id}.";
    //                                  $this->db->trans_rollback(); // Critical error, stop everything
    //                                  break; // Exit the loop
    //                             }
    //                         } else {
    //                              $error_messages[] = "Item #{$i}: Failed to add stock to center for Batch ID {$new_batch_id}. Error: " . ($add_stock_result['message'] ?? 'Unknown');
    //                              $this->db->trans_rollback(); break;
    //                         }
    //                     } else {
    //                         // Batch insertion failed (e.g., unique key violation)
    //                          $error_messages[] = "Item #{$i} (Batch: {$batch_number}): Failed to add batch record. Error: " . ($add_batch_result['message'] ?? 'Unknown');
    //                          // Decide if you want to stop or continue processing other items
    //                          // $this->db->trans_rollback(); break; // Uncomment to stop on first batch error
    //                     }
    //                 } else {
    //                      // Invalid data for this item row
    //                      if ($medicine_id > 0 && $total_received > 0) { // Only log error if it seemed like a valid attempt
    //                         $error_messages[] = "Item #{$i}: Invalid data provided (Batch No, Expiry, or Price missing/invalid).";
    //                      }
    //                 }
    //                 $i++;
    //             } // End while loop

    //             // --- Complete Transaction ---
    //             if (!empty($error_messages)) {
    //                  // Rollback already happened if it was critical, otherwise maybe commit successful ones?
    //                  // For simplicity now, if any error occurred that didn't stop the loop, we roll back.
    //                  $this->db->trans_rollback();
    //                  $this->session->set_flashdata('error', "Stock addition failed. Errors: <br>" . implode("<br>", $error_messages));
    //             } else {
    //                 $this->db->trans_complete();
    //                 if ($this->db->trans_status() === FALSE) {
    //                     $this->session->set_flashdata('error', 'Database transaction failed during stock addition.');
    //                      if (!empty($uploaded_files_info)) {
    //                          foreach($uploaded_files_info as $file) {
    //                              if(file_exists($upload_path . $file['stored_name'])) unlink($upload_path . $file['stored_name']);
    //                          }
    //                      }
    //                 } else {
    //                     if ($success_count == $processed_items && $processed_items > 0) {
    //                          $this->session->set_flashdata('success', "Stock added successfully for all {$processed_items} received item(s)!");
    //                          // Optionally update the OLD PO status here if needed
    //                          // $this->New_purchase_order_model->update_purchase_order_status($po_id, 'received');
    //                     } elseif ($success_count > 0) {
    //                          $this->session->set_flashdata('warning', "Stock added successfully for {$success_count} out of {$processed_items} received item(s). Some items may have failed.");
    //                     } else {
    //                          $this->session->set_flashdata('error', 'Failed to add stock for any items processed. Please check errors.');
    //                     }
    //                 }
    //             }
    //             redirect('stocks_new/batches'); // Redirect to batch list or PO list
    //         } else {
    //              // Not a POST request
    //              redirect('new_purchase_orders');
    //         }
    //     } else {
    //         // Not logged in
    //         redirect(base_url());
    //     }
    // }
      private function handleFileUploads() {
        $uploaded_files_info = [];
        $upload_path = './uploads/receipts/'; 
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }
        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['max_size']      = 5120; // 5MB
        $config['encrypt_name']  = TRUE;
        $this->load->library('upload', $config);
        $files = $_FILES['receipt_files']; 
        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            $_FILES['userfile']['name']     = $files['name'][$i];
            $_FILES['userfile']['type']     = $files['type'][$i];
            $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['userfile']['error']    = $files['error'][$i];
            $_FILES['userfile']['size']     = $files['size'][$i];
            if ($this->upload->do_upload('userfile')) {
                $upload_data = $this->upload->data();
                $uploaded_files_info[] = [
                    'path'          => $upload_path . $upload_data['file_name'],
                    'original_name' => $files['name'][$i],
                    'stored_name'   => $upload_data['file_name'],
                    'file_type'     => $upload_data['file_type'],
                    'file_size'     => $upload_data['file_size'] * 1024, // Size in bytes
                    'upload_date'   => date('Y-m-d H:i:s')
                 ];
            } else {
                 log_message('error', 'File Upload Error: '.$this->upload->display_errors('',''));
                 $this->session->set_flashdata('error', 'Error uploading file: ' . $files['name'][$i] . ' - ' . $this->upload->display_errors('',''));
                 redirect('stocks_new/new_add_stock/' . $this->input->get('id'));
                 return;
            }
        }
        return $uploaded_files_info; 
    }

    public function save_add_stock() 
    {
        $logg = checklogin();
        if (!$logg["status"] == true) {
            $this->session->set_flashdata('error_message', 'You must be logged in to perform this action.');
            redirect('login'); 
            return;
        }
        $po_id = $this->input->post('po_id');
        $po_number = $this->input->post('po_number');
        $vendor_id = $this->input->post('vendor_number'); // This is the hms_vendors.vendor_number
        $center_id = $this->input->post('center_id'); // This is the hms_centers.ID
        $receive_by = $this->input->post('receive_by');
        $receipt_date = $this->input->post('receipt_date');
        $receipt_number = $this->input->post('receipt_number');
        $created_by_id = $this->Stock_model_new->get_employee_id_from_number($_SESSION['logged_central_stock_manager']['employee_number']); // Placeholder - FIX THIS
        
        // Get purchase order to check if it's for Central Warehouse Noida
        $purchase_order = $this->New_purchase_order_model->get_purchase_order_by_id($po_id);
        $is_central_warehouse = false;
        if ($purchase_order && $purchase_order['ship_to'] === 'CENTRAL_WAREHOUSE_NOIDA') {
            $is_central_warehouse = true;
            $center_id = null; // No center_id for central warehouse
        }
        
        if (empty($po_id)) {
            $this->session->set_flashdata('error', 'Error: Missing PO ID.');
            redirect('new_purchase_orders');
            return;
        }
        
        // Only require center_id if it's not central warehouse
        if (!$is_central_warehouse && empty($center_id)) {
            $this->session->set_flashdata('error', 'Error: Missing Center ID.');
            redirect('new_purchase_orders');
            return;
        }
        if (!$vendor_id) {
            $this->session->set_flashdata('error', 'Error: Invalid Vendor.');
            redirect('new_purchase_orders/save_add_stock/' . $po_id);
            return;
        }
        // --- Handle File Uploads ---
      /*  $uploaded_files_info = [];
        if(!empty($_FILES['receipt_files']['name'][0])) {
            $uploaded_files_info = $this->handleFileUploads(); 
            if(isset($uploaded_files_info['error'])) {
                $this->session->set_flashdata('error', $uploaded_files_info['error']);
                redirect('new_purchase_orders/save_add_stock/' . $po_id);
                return;
            }
        }
        $file_paths = [];
        if (!empty($uploaded_files_info)) {
            foreach ($uploaded_files_info as $file_info) {
                $file_paths[] = $file_info['path']; // store only the path or full info if you prefer
            }
        }*/

        $uploaded_files = [];

        $upload_path = FCPATH . 'uploads/receipts/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        if (!empty($_FILES['receipt_files']['name'][0])) {

            $this->load->library('upload');

            $files = $_FILES['receipt_files'];

            for ($i = 0; $i < count($files['name']); $i++) {

                $_FILES['single_file']['name']     = $files['name'][$i];
                $_FILES['single_file']['type']     = $files['type'][$i];
                $_FILES['single_file']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['single_file']['error']    = $files['error'][$i];
                $_FILES['single_file']['size']     = $files['size'][$i];

                $config = [
                    'upload_path'   => $upload_path,
                    'allowed_types' => 'pdf|jpg|jpeg|png',
                    'max_size'      => 10000,
                    'encrypt_name'  => TRUE
                ];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('single_file')) {

                    $upload_data = $this->upload->data();
                    $uploaded_files[] = 'uploads/receipts/' . $upload_data['file_name'];

                } else {

                    echo $this->upload->display_errors();
                    exit;

                }
            }
        }    

        $file_names = !empty($file_paths) ? json_encode($file_paths) : null;
        $items_processed = 0;
        $items_failed = 0;
        $error_messages = [];
        $row_counter = 1;
        $max_rows = 20;   
        while ($row_counter <= $max_rows) {
            $product_id = $this->input->post('product_' . $row_counter);
            $po_item_id = $this->input->post('po_item_id_' . $row_counter);
            if (empty($product_id) && empty($po_item_id)) {
                $next_exists = $this->input->post('product_' . ($row_counter + 1));
                if (empty($next_exists)) {
                    break;
                } else {
                    $row_counter++;
                    continue;
                }
            }
            $qty_receiving = (float)$this->input->post('qty_receiving_' . $row_counter);
            if ($qty_receiving <= 0) {
                $row_counter++;
                continue; 
            }
            if ($qty_receiving > 0) {
                $item_data = [
                    'po_item_id'       => $po_item_id,
                    'medicine_id'      => $this->input->post('product_' . $row_counter),
                    'batch_number'     => $this->input->post('batch_number_' . $row_counter),
                    'expiry_date'      => $this->input->post('expiry_date_' . $row_counter),
                    'freight_charges'  => $this->input->post('freight_charges_'. $row_counter),
                    'quantity'         => $qty_receiving * $this->input->post('uom_' . $row_counter),
                    'free_qty'         => (float)$this->input->post('free_qty_' . $row_counter) * $this->input->post('uom_' . $row_counter),
                    'purchase_price'   => (float)$this->input->post('unit_price_' . $row_counter),
                    'selling_price'    => 0, // This will be set by the model
                    'mrp'              => (float)$this->input->post('mrp_' . $row_counter),
                    'discount_percent' => (float)$this->input->post('discount_' . $row_counter),
                    'tax_percent'      => (float)$this->input->post('tax_percentage_' . $row_counter),
                    'tax_amount'       => (float)$this->input->post('tax_amount_' . $row_counter),
                    'total_amount'     => (float)$this->input->post('amount_' . $row_counter),
                    'vendor_id'        => $vendor_id,
                    'po_id'            => $po_id,
                    'po_number'        => $po_number,
                    'invoice_number'   => $this->input->post('reference'), 
                    'department'       => $is_central_warehouse ? null : $this->input->post('department'), // No department for central warehouse
                    'invoice_date'     => $receipt_date,
                    'receive_date'     => $this->input->post('date_receiving'),
                    'remarks'          => $this->input->post('comments_' . $row_counter),
                    'created_by'       => $created_by_id,
                    'center_id'        => $center_id, // null for central warehouse
                    'receive_by'       => $receive_by,
                    'receipt_number'   => $receipt_number,
                    'uploaded_files'   => ($row_counter == 1) ? $file_names : null, // Attach files to first item's log
                    'is_central_warehouse' => $is_central_warehouse // Flag to indicate central warehouse
                ];
                // *** THIS IS THE FIX ***
                // Call the new, smart function from Stock_model_new
               $item_data['uploaded_files'] = json_encode($uploaded_files);
                $result = $this->Stock_model_new->receive_stock_item($item_data);
               
                if ($result['status'] == 'success') {
                    $items_processed++;
                } else {
                    $items_failed++;
                    $error_messages[] = "Item (Medicine ID {$item_data['medicine_id']}): " . $result['message'];
                    log_message('error', 'Failed to process item: ' . $item_data['medicine_id'] . '. Error: ' . $result['message']);
                }
            }
            $row_counter++;
        }
        if ($items_processed > 0 && $items_failed == 0) {
            $this->session->set_flashdata('success', "Successfully received {$items_processed} items.");
        } elseif ($items_processed > 0 && $items_failed > 0) {
            $this->session->set_flashdata('error', "Partially processed: {$items_processed} items succeeded, {$items_failed} items failed. Errors: <br>" . implode("<br>", $error_messages));
        } elseif ($items_failed > 0) {
            $this->session->set_flashdata('error', "Error: All {$items_failed} items failed to process. Errors: <br>" . implode("<br>", $error_messages));
        } else {
            $this->session->set_flashdata('error', 'No items were marked for receiving.');
        }

        redirect('new_purchase_orders');
    }
    

    public function received_stock_report() {
        $logg = checklogin();
        if (!$logg["status"] == true) {
             redirect(base_url());
             die();
        }

        // Get filters from URL
        $filters = [
            'po_number'      => $this->input->get('po_number'),
            'invoice_number' => $this->input->get('invoice_number'),
            'vendor_id'      => $this->input->get('vendor_id'),
            'has_file'       => $this->input->get('has_file'),
            'start_date'     => $this->input->get('start_date'),
            'end_date'       => $this->input->get('end_date')
        ];

        // Get data from model
        $data['received_items'] = $this->New_purchase_order_model->get_received_stock_report($filters);
        // Get data for filter dropdowns
        $data['vendors'] = $this->get_vendors();
        $data['filters'] = $filters; // Pass filters to the view

        $template = get_header_template($logg["role"]);
        $this->load->view($template["header"]);
        $this->load->view("new_purchase_orders/received_stock_report", $data); 
        $this->load->view($template["footer"]);
    }

    /**
     * Export received stock report to Excel (CSV format)
     */
    public function export_received_stock_report() {
        $logg = checklogin();
        if (!$logg["status"] == true) {
             redirect(base_url());
             die();
        }

        // Get filters from URL (same as received_stock_report)
        $filters = [
            'po_number'      => $this->input->get('po_number'),
            'invoice_number' => $this->input->get('invoice_number'),
            'vendor_id'      => $this->input->get('vendor_id'),
            'has_file'       => $this->input->get('has_file'),
            'start_date'     => $this->input->get('start_date'),
            'end_date'       => $this->input->get('end_date')
        ];

        // Get data from model
        $received_items = $this->New_purchase_order_model->get_received_stock_report($filters);

        if (empty($received_items)) {
            $this->session->set_flashdata('error', 'No data found to export.');
            redirect('new_purchase_orders/received_stock_report');
            return;
        }

        // Set headers for Excel download
        $filename = 'Received_Stock_Report_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // Create file pointer
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8 (Excel compatibility)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        $headers = [
            'PO Number',
            'Vendor',
            'Center',
            'Item Name',
            'Item Code',
            'Batch Number',
            'Quantity Received',
            'Vendor Price',
            'Vendor Price With GST',
            'Receive By',
            'Invoice Number',
            'Received Date',
            'Files',
            'Total Value',
            'Freight Charges'
        ];
        fputcsv($output, $headers);

        // Add data rows
        foreach ($received_items as $item) {
            // Process files
            $files_display = 'No files';
            if (!empty($item->uploaded_files)) {
                $files = json_decode($item->uploaded_files);
                if (!empty($files) && is_array($files)) {
                    $file_names = [];
                    foreach ($files as $file) {
                        $file_names[] = basename($file);
                    }
                    $files_display = implode(', ', $file_names);
                }
            }

            $row = [
                $item->po_number,
                $item->vendor_name,
                $item->center_name,
                $item->medicine_name,
                $item->item_number,
                $item->batch_number,
                $item->quantity_change,
                number_format($item->unit_price, 2),
                number_format($item->vendor_price_with_tax, 2),
                $item->receive_by,
                $item->receipt_number,
                date('d-m-Y H:i', strtotime($item->received_date)),
                $files_display,
                number_format($item->total_value, 2),
                $item->freight_charges
            ];
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }


    public function items_by_vendor_json() {
        $logg = checklogin();
        if($logg['status'] != true) {
            return $thisoutput->set_content_type('application/json')
                ->set_status_header(401) // Unauthorized
                ->set_output(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
        }
        $vendor_id = $this.input->get('vendor_number'); // This is the VENDOR ID (e.g. 73)
        if (empty($vendor_id)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400) // Bad Request
                ->set_output(json_encode(['status' => 'error', 'message' => 'vendor_id is required']));
        }
        $items = $this->New_purchase_order_model->get_medicines_by_vendor_for_po($vendor_id);
        return $this->output->set_content_type('application/json')
            ->set_status_header(200) // OK
            ->set_output(json_encode(['status' => 'success', 'data' => $items]));
    }
    
    /**
     * This function handles the file uploads.

    /**
     * Get or create medicine in stocks_new module
     */
    private function get_or_create_medicine($item_details, $purchase_order) {
        // Check if medicine already exists
        $existing_medicine = $this->Stock_model_new->get_medicine_by_name_and_brand($item_details['item_name'], $item_details['brand_name']);
        
        if ($existing_medicine) {
            return $existing_medicine->id;
        }
        
        // Get or create brand
        $brand_id = $this->get_or_create_brand($item_details['brand_name']);
        
        // Create new medicine
        $medicine_data = [
            'medicine_name' => $item_details['item_name'],
            'medicine_code' => $item_details['item_number'],
            'generic_name' => $item_details['generic_name'] ?? '',
            'brand_id' => $brand_id,
            'pack_size' => $item_details['pack_size'] ?? 'PCS',
            'hsn_code' => $item_details['hsn'] ?? '',
            'mrp' => floatval($item_details['mrp']),
            'status' => 'ACTIVE',
            'created_by' => $this->get_employee_id_from_session(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->Stock_model_new->add_medicine($medicine_data);
        return $result ? $this->db->insert_id() : false;
    }
    
    /**
     * Get or create brand in stocks_new module
     */
    private function get_or_create_brand($brand_name) {
        // Check if brand already exists
        $existing_brand = $this->Stock_model_new->get_brand_by_name($brand_name);
        
        if ($existing_brand) {
            return $existing_brand->id;
        }
        
        // Create new brand
        $brand_data = [
            'brand_name' => $brand_name,
            'brand_code' => $this->generate_brand_code(),
            'status' => 'ACTIVE',
            'created_by' => $this->get_employee_id_from_session(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->Stock_model_new->add_medicine_brand($brand_data);
        return $result ? $this->db->insert_id() : false;
    }
    
    /**
     * Get center ID from center number
     */
    private function get_center_id_from_number($center_number) {
        if (empty($center_number)) {
            return null;
        }
        
        $this->db->select('ID, center_name');
        $this->db->from($this->config->item('db_prefix') . 'centers');
        $this->db->where('center_number', $center_number);
        $this->db->where('status', '1');
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $result = $query->row();
            log_message('debug', 'Found center: ' . $result->center_name . ' (ID: ' . $result->ID . ') for center_number: ' . $center_number);
            return $result->ID;
        }
        
        log_message('debug', 'Center not found for center_number: ' . $center_number);
        return null;
    }
    
    /**
     * Create batch from purchase order data and add directly to center
     */
    private function create_batch_from_po($medicine_id, $item_details, $purchase_order, $row_index) {
        // Get vendor ID from stocks_new module
        $vendor_id = $this->get_or_create_vendor($purchase_order['vendor_number']);
        
        if (!$vendor_id) {
            return false;
        }
        
        // Get center ID from ship_to field (convert center_number to center_id)
        $center_id = $this->get_center_id_from_number($purchase_order['ship_to']);
        
        if (!$center_id) {
            // If center not found, try to get the first available center as fallback
            $this->db->select('ID');
            $this->db->from($this->config->item('db_prefix') . 'centers');
            $this->db->where('status', '1');
            $this->db->limit(1);
            $fallback_query = $this->db->get();
            
            if ($fallback_query->num_rows() > 0) {
                $center_id = $fallback_query->row()->ID;
                $this->session->set_flashdata('warning', 'Center not found for ship_to: ' . $purchase_order['ship_to'] . '. Using fallback center ID: ' . $center_id);
            } else {
                $this->session->set_flashdata('error', 'No centers available in the system!');
                return false;
            }
        }
        
        $batch_number = $this->input->post('batch_number_' . $row_index) ?: $item_details['batch_number'];
        
        // Check if batch already exists for this medicine
        $existing_batch = $this->check_existing_batch($medicine_id, $batch_number);
        
        if ($existing_batch) {
            // Update existing batch quantity
            return $this->update_existing_batch($existing_batch->id, $row_index, $center_id, $vendor_id, $purchase_order);
        } else {
            // Create new batch
            return $this->create_new_batch($medicine_id, $item_details, $purchase_order, $row_index, $batch_number, $vendor_id, $center_id);
        }
    }
    
    /**
     * Check if batch already exists for medicine
     */
    private function check_existing_batch($medicine_id, $batch_number) {
        $this->db->select('*');
        $this->db->from('medicine_batches');
        $this->db->where('medicine_id', $medicine_id);
        $this->db->where('batch_number', $batch_number);
        $this->db->where('batch_status', 'ACTIVE');
        return $this->db->get()->row();
    }
    
    /**
     * Update existing batch quantity
     */
    private function update_existing_batch($batch_id, $row_index, $center_id, $vendor_id, $purchase_order) {
        $additional_qty = floatval($this->input->post('qty_receiving_' . $row_index)) + floatval($this->input->post('free_qty_' . $row_index));
            // Update batch quantity
        $this->db->where('id', $batch_id);
        $this->db->set('quantity_purchased', 'quantity_purchased + ' . $additional_qty, FALSE);
        $this->db->set('quantity_remaining', 'quantity_remaining + ' . $additional_qty, FALSE);
        $this->db->update('medicine_batches');
        
        // Check if center stock exists for this batch
        $this->db->where('batch_id', $batch_id);
        $this->db->where('center_id', $center_id);
        $existing_center_stock = $this->db->get('center_stocks')->row();
        
        if ($existing_center_stock) {
            // Update existing center stock
            $this->db->where('batch_id', $batch_id);
            $this->db->where('center_id', $center_id);
            $this->db->set('quantity', 'quantity + ' . $additional_qty, FALSE);
            $this->db->update('center_stocks');
        } else {
            // Create new center stock record
            $center_stock_data = [
                'batch_id' => $batch_id,
                'center_id' => $center_id,
                'quantity' => $additional_qty,
                'status' => 'ACTIVE',
                'last_movement_date' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('center_stocks', $center_stock_data);
        }
        
        // Log stock movement
        $movement_data = [
            'batch_id' => $batch_id,
            'movement_type' => 'PURCHASE',
            'from_location_type' => 'VENDOR',
            'from_location_id' => $vendor_id,
            'to_location_type' => 'CENTER',
            'to_location_id' => $center_id,
            'quantity_change' => $additional_qty,
            'quantity_after' => $existing_center_stock ? ($existing_center_stock->quantity + $additional_qty) : $additional_qty,
            'unit_price' => $this->input->post('unit_price_' . $row_index) ?: 0,
            'total_value' => $additional_qty * ($this->input->post('unit_price_' . $row_index) ?: 0),
            'reference_type' => 'PURCHASE_RECEIPT',
            'reference_id' => $purchase_order['id'],
            'reference_number' => $this->input->post('reference') ?: 'N/A',
            'created_by' => $this->get_employee_id_from_session()
        ];
        
        $this->db->insert('stock_movements', $movement_data);
        
        return true;
    }
    
    /**
     * Create new batch
     */
    private function create_new_batch($medicine_id, $item_details, $purchase_order, $row_index, $batch_number, $vendor_id, $center_id) {
        $batch_data = [
            'medicine_id' => $medicine_id,
            'vendor_id' => $vendor_id,
            'batch_number' => $batch_number,
            'manufacturing_date' => $this->input->post('manufacturing_date_' . $row_index) ?: null,
            'expiry_date' => $this->input->post('expiry_date_' . $row_index),
            'purchase_price' => $this->input->post('unit_price_' . $row_index) ?: $item_details['vendor_price'],
            'selling_price' => floatval($item_details['mrp']),
            'mrp' => floatval($item_details['mrp']),
            'quantity_purchased' => floatval($this->input->post('qty_receiving_' . $row_index)) + floatval($this->input->post('free_qty_' . $row_index)),
            'quantity_remaining' => floatval($this->input->post('qty_receiving_' . $row_index)) + floatval($this->input->post('free_qty_' . $row_index)),
            'purchase_date' => $this->input->post('po_date'),
            'invoice_number' => $this->input->post('reference') ?: 'N/A',
            'invoice_date' => $this->input->post('receipt_date'),
            'quality_status' => 'APPROVED',
            'batch_status' => 'ACTIVE',
            'remarks' => $this->input->post('comments_' . $row_index) ?: '',
            'created_by' => $this->get_employee_id_from_session()
        ];
        
        // Create batch
        $batch_id = $this->Stock_model_new->add_batch_only($batch_data);
        
        if ($batch_id) {
            // Add directly to center stock instead of central warehouse
            $center_stock_data = [
                'batch_id' => $batch_id,
                'center_id' => $center_id,
                'quantity' => floatval($this->input->post('qty_receiving_' . $row_index)) + floatval($this->input->post('free_qty_' . $row_index)),
                'status' => 'ACTIVE',
                'last_movement_date' => date('Y-m-d H:i:s')
            ];
            
            $center_stock_result = $this->db->insert('center_stocks', $center_stock_data);
            
            if ($center_stock_result) {
                // Log stock movement directly to center
                $movement_data = [
                    'batch_id' => $batch_id,
                    'movement_type' => 'PURCHASE',
                    'from_location_type' => 'VENDOR',
                    'from_location_id' => $vendor_id,
                    'to_location_type' => 'CENTER',
                    'to_location_id' => $center_id,
                    'quantity_change' => floatval($this->input->post('qty_receiving_' . $row_index)) + floatval($this->input->post('free_qty_' . $row_index)),
                    'quantity_after' => floatval($this->input->post('qty_receiving_' . $row_index)) + floatval($this->input->post('free_qty_' . $row_index)),
                    'unit_price' => $this->input->post('unit_price_' . $row_index) ?: $item_details['vendor_price'],
                    'total_value' => (floatval($this->input->post('qty_receiving_' . $row_index)) + floatval($this->input->post('free_qty_' . $row_index))) * ($this->input->post('unit_price_' . $row_index) ?: $item_details['vendor_price']),
                    'reference_type' => 'PURCHASE_RECEIPT',
                    'reference_id' => $purchase_order['id'],
                    'reference_number' => $this->input->post('reference') ?: 'N/A',
                    'created_by' => $this->get_employee_id_from_session()
                ];
                
                $this->db->insert('stock_movements', $movement_data);
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get or create vendor in stocks_new module
     */
    private function get_or_create_vendor($vendor_number) {
        // Check if vendor already exists
        $existing_vendor = $this->Stock_model_new->get_vendor_by_number($vendor_number);
        
        if ($existing_vendor) {
            return $existing_vendor->ID;
        }
        
        // Get vendor details from purchase order module
        $this->load->model('Vendors_model');
        $vendor_data = $this->Vendors_model->get_vendor_data_by_vendor_number($vendor_number);
        
        if (empty($vendor_data)) {
            return false;
        }
        
        $vendor = $vendor_data[0];
        
        // Create vendor in stocks_new module
        $vendor_insert_data = [
            'vendor_number' => $vendor_number,
            'name' => $vendor['name'] ?? 'Unknown Vendor',
            'contact_person' => $vendor['contact_person'] ?? '',
            'phone' => $vendor['phone'] ?? '',
            'email' => $vendor['email'] ?? '',
            'address' => $vendor['address'] ?? '',
            'status' => 'ACTIVE',
            'created_by' => $this->get_employee_id_from_session(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->Stock_model_new->add_vendor($vendor_insert_data);
        return $result ? $this->db->insert_id() : false;
    }
    
    /**
     * Generate brand code
     */
    private function generate_brand_code() {
        return 'BR' . date('Ymd') . rand(1000, 9999);
    }
    
    /**
     * Get employee ID from session
     */
    private function get_employee_id_from_session() {
        if (isset($_SESSION['logged_central_stock_manager']['employee_number'])) {
            return $this->get_employee_id_from_number($_SESSION['logged_central_stock_manager']['employee_number']);
        }
        return 1; // Default employee ID
    }
    
    /**
     * Get employee ID from employee number
     */
    private function get_employee_id_from_number($employee_number) {
        if (empty($employee_number)) {
            return null;
        }
        
        $this->db->select('ID');
        $this->db->from('hms_employees');
        $this->db->where('employee_number', $employee_number);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->ID;
        }
        
        return null;
    }

    // Helper method to get vendor name
    private function get_vendor_name($vendor_number) {
        if ($vendor_number == 'Cash Purchase') {
            return 'Cash Purchase';
        }
        
        $this->db->select('name');
        $this->db->from('hms_vendors');
        $this->db->where('vendor_number', $vendor_number);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->row()->name;
        }
        return $vendor_number;
    }

    private function save_stock_transfer_items($transfer_id) {
        $items = [];
        $i = 1;
        
        while ($this->input->post('stock_number_' . $i)) {
            $items[] = [
                'transfer_id' => $transfer_id,
                'stock_number' => $this->input->post('stock_number_' . $i),
                'description' => $this->input->post('description_' . $i),
                'quantity' => $this->input->post('quantity_' . $i),
                'uom' => $this->input->post('uom_' . $i),
                'price' => $this->input->post('price_' . $i),
                'amount' => $this->input->post('amount_' . $i)
            ];
            $i++;
        }
        
        if (!empty($items)) {
            $this->New_purchase_order_model->insert_stock_transfer_items($items);
        }
    }
    
    // Handle file uploads for receipts
    // private function handleFileUploads() {
    //     $uploaded_files = [];
    //     $upload_path = './uploads/receipts/';
        
    //     // Create upload directory if it doesn't exist
    //     if (!is_dir($upload_path)) {
    //         mkdir($upload_path, 0755, true);
    //     }
        
    //     $files = $_FILES['receipt_files'];
    //     $file_count = count($files['name']);
        
    //     for ($i = 0; $i < $file_count; $i++) {
    //         if ($files['error'][$i] === UPLOAD_ERR_OK) {
    //             $file_name = $files['name'][$i];
    //             $file_tmp = $files['tmp_name'][$i];
    //             $file_size = $files['size'][$i];
    //             $file_type = $files['type'][$i];
                
    //             // Generate unique filename
    //             $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    //             $unique_filename = time() . '_' . $i . '_' . uniqid() . '.' . $file_extension;
    //             $file_path = $upload_path . $unique_filename;
                
    //             // Validate file type
    //             $allowed_types = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    //             if (!in_array($file_type, $allowed_types)) {
    //                 continue; // Skip invalid files
    //             }
                
    //             // Validate file size (5MB max)
    //             if ($file_size > 5 * 1024 * 1024) {
    //                 continue; // Skip files that are too large
    //             }
                
    //             // Move uploaded file
    //             if (move_uploaded_file($file_tmp, $file_path)) {
    //                 $uploaded_files[] = [
    //                     'original_name' => $file_name,
    //                     'stored_name' => $unique_filename,
    //                     'file_path' => $file_path,
    //                     'file_size' => $file_size,
    //                     'file_type' => $file_type,
    //                     'upload_date' => date('Y-m-d H:i:s')
    //                 ];
    //             }
    //         }
    //     }
        
    //     return $uploaded_files;
    // }
    
    // Get uploaded files for a purchase order
    public function get_uploaded_files($po_id) {
        $this->db->select('vendor_billing');
        $this->db->from($this->config->item('db_prefix') . 'vendor_billing');
        $this->db->where('purchase_po_no', $po_id);
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $files = json_decode($result['vendor_billing'], true);
            return is_array($files) ? $files : [];
        }
        
        return [];
    }

    public function export_csv() {
        $logg = checklogin();
        if ($logg['status'] == true) {
            // 1. Get filters from request (same as index method)
            $filters = [
                'status' => $this->input->get('status'),
                'vendor_number' => $this->input->get('vendor_number'),
                'center' => $this->input->get('center'), // Make sure this filter is used if needed
                'start_date' => $this->input->get('start_date'),
                'end_date' => $this->input->get('end_date'),
                'po_number' => $this->input->get('po_number')
            ];
            // 2. Fetch ALL matching data (pass null for limit/start)
            $all_purchase_orders = $this->New_purchase_order_model->get_purchase_orders(null, null, $filters);
            // 3. Set CSV headers for download
            $filename = "purchase_orders_" . date('Y-m-d') . ".csv";
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            // 4. Open file handle to PHP output stream
            $fp = fopen('php://output', 'w');

            // 5. Add CSV header row
            $header = [
                'PO Number',
                'Vendor',
                'Bill To',
                'Ship To',
                'Department',
                'Total Amount',
                'Status',
                'Created Date'
            ];
            fputcsv($fp, $header);
            // 6. Load necessary models (as done in the view)
            $this->load->model('Vendors_model');
            // 7. Loop through data and write to CSV
            if (!empty($all_purchase_orders)) {
                foreach ($all_purchase_orders as $po) {
                    if (!is_array($po)) continue;
                    // --- Replicate the data lookup logic from your view ---
                    // Get Vendor Name
                    $vendor_data = $this->Vendors_model->get_vendor_name_by_vendor_id($po['vendor_number']);
                    // Robust check (as your view's commented code was safer)
                    $vendor_name = (is_object($vendor_data) && isset($vendor_data->name)) ? $vendor_data->name : 'N/A';
                    // Get Bill To / Ship To Names
                    $ship_to = $this->get_center_name($po['ship_to']); // Assumes get_center_name is in this controller
                    $bill_to = $this->get_center_name($po['bill_to']);
                    $ship_to = !empty($ship_to) ? $ship_to : 'N/A';
                    $bill_to = !empty($bill_to) ? $bill_to : 'N/A';

                    // Get Status Text
                    $status_text = 'Unknown';
                    $status = !empty($po['status']) ? $po['status'] : 'pending';
                    switch ($status) {
                        case 'pending': $status_text = 'Pending'; break;
                        case 'approved': $status_text = 'Approved'; break;
                        case 'rejected': $status_text = 'Rejected'; break;
                        case 'completed': $status_text = 'Completed'; break;
                    }
                    // --- Build the row array ---
                    $row = [
                        !empty($po['po_number']) ? $po['po_number'] : 'N/A',
                        $vendor_name,
                        $bill_to,
                        $ship_to,
                        !empty($po['department']) ? $po['department'] : 'N/A',
                        !empty($po['total_amount']) ? number_format($po['total_amount'], 2) : '0.00',
                        $status_text,
                        !empty($po['created_at']) ? date('d/m/Y H:i', strtotime($po['created_at'])) : 'N/A'
                    ];

                    // 8. Write row to CSV
                    fputcsv($fp, $row);
                }
            }
            // 9. Close file handle and exit
            fclose($fp);
            exit;

        } else {
            header("location:" . base_url() . "");
            die;
        }
    }
}