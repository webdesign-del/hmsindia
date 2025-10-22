<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New_purchase_orders extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('New_purchase_order_model');
        $this->load->model('Stock_model');
        $this->load->model('Accounts_model');
        $this->load->model('Vendors_model');
        $this->load->model('Center_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper('myhelper');
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
            $data['consumables'] = $this->get_consumables();
            // Get centers
            $data['centers'] = $this->get_centers();
            
            // Generate PO number
            $data['po_number'] = $this->New_purchase_order_model->generate_po_number();
            
            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
            $this->load->view('new_purchase_orders/add', $data);
            $this->load->view($template['footer']);
        } else {
            header("location:" .base_url(). "");
            die;
        }
    }

    // Save new purchase order
    public function save() {
        $logg = checklogin();
        if($logg['status'] == true) {
            if ($this->input->post()) {
                $po_data = [
                    'po_number' => $this->input->post('po_number'),
                    'vendor_number' => $this->input->post('vendor_number'),
                    'ship_to' => $this->input->post('ship_to'),
                    'bill_to' => $this->input->post('bill_to'),
                    'department' => $this->input->post('department'),
                    'created_by' => $this->session->userdata('user_id'),
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
                    'quantity' => $this->input->post('consumables_quantity_' . $i),
                    'batch_number' => $this->input->post('consumables_batch_number_' . $i),
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
                
                // Debug logging - remove this after testing
                error_log("PO Item Data - PO ID: $po_id, Item $i: " . json_encode($item_data));
                
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
            $data['consumables'] = $this->get_consumables();
            
            // Get centers
            $data['centers'] = $this->get_centers();
            
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
                // Check if purchase order can be updated (only pending or rejected orders)
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
            
            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
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
        $this->db->select('vendor_number, name');
        $this->db->from('hms_vendors');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get consumables/items (you may need to adjust this based on your existing structure)
    private function get_consumables() {
        // Include vendor_number for client-side/vendor-based filtering
        $this->db->select('item_number, item_name, batch_number, quantity, price, vendor_price, mrp, pack_size, gstrate, hsn, gstdivision, company, brand_name, vendor_number');
        $this->db->from('hms_stocks');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    // AJAX: Get consumables/items filtered by vendor
    public function items_by_vendor() {
        $logg = checklogin();
        if($logg['status'] != true) {
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

        $this->db->select('item_number, item_name, batch_number, quantity, price, vendor_price, mrp, pack_size, gstrate, hsn, gstdivision, company, brand_name, vendor_number');
        $this->db->from('hms_stocks');
        $this->db->where('status', '1');
        $this->db->where('vendor_number', $vendor_number);
        $items = $this->db->get()->result_array();

        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(['status' => 'success', 'data' => $items]));
    }

    // Get centers (you may need to adjust this based on your existing center structure)
    private function get_centers() {
        // This is a placeholder - adjust based on your center table structure
        $this->db->select('center_number, center_name, center_address');
        $this->db->from('hms_centers');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->result_array();
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
            $vendor_data = $this->Vendors_model->get_vendor_data_by_vendor_number($data['purchase_order']['vendor_number']);
            $data['vendor_data'] = $vendor_data[0];
            
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
    public function new_add_stock($id) {
        $logg = checklogin();
        if($logg['status'] == true) {
            $data['purchase_order'] = $this->New_purchase_order_model->get_purchase_order_by_id($id);
            $data['purchase_order_items'] = $this->New_purchase_order_model->get_purchase_order_items($id);
            
            if (!$data['purchase_order']) {
                $this->session->set_flashdata('error', 'Purchase order not found!');
                redirect('new_purchase_orders');
            }
            if ($data['purchase_order']['status'] != 'completed') {
                $this->session->set_flashdata('error', 'Only completed purchase orders can be processed for stock addition!');
                redirect('new_purchase_orders');
            }
            $this->load->model('Vendors_model');
            $vendor_data = $this->Vendors_model->get_vendor_data_by_vendor_number($data['purchase_order']['vendor_number']);
            $data['vendor_data'] = $vendor_data[0];
            $this->load->model('Center_model');
            $bill_to_center = $this->Center_model->get_item_data($data['purchase_order']['bill_to']);
            $ship_to_center = $this->Center_model->get_item_data($data['purchase_order']['ship_to']);
            $data['bill_to_address'] = $bill_to_center ? $bill_to_center['center_name'] . ', ' . $bill_to_center['center_location'] : 'N/A';
            $data['ship_to_address'] = $ship_to_center ? $ship_to_center['center_name'] . ', ' . $ship_to_center['center_location'] : 'N/A';
            // Get vendors and consumables for the form
            $data['vendors'] = $this->get_vendors();
            $data['consumables'] = $this->get_consumables();
            
            // Load existing uploaded files
            $data['uploaded_files'] = $this->get_uploaded_files($data['purchase_order']['po_number']);
            
            $data['title'] = 'Purchase Order Receipt - Inventory';
            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
            // var_dump($data);
            $this->load->view('new_purchase_orders/new_add_stock', $data);
            $this->load->view($template['footer']);
        } else {
            header("location:" .base_url(). "");
            die;
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
    public function save_add_stock() {
        $logg = checklogin();
        if($logg['status'] == true) {
            if ($this->input->post()) {
                $po_id = $this->input->get('id');
                if (!$po_id) {
                    $this->session->set_flashdata('error', 'Purchase Order ID is required!');
                    redirect('new_purchase_orders');
                    return;
                }
                
                // Handle file uploads
                $uploaded_files = [];
                if (!empty($_FILES['receipt_files']['name'][0])) {
                    $uploaded_files = $this->handleFileUploads();
                }
                $purchase_order = $this->New_purchase_order_model->get_purchase_order_by_id($po_id);
                if (!$purchase_order) {
                    $this->session->set_flashdata('error', 'Purchase order not found!');
                    redirect('new_purchase_orders');
                    return;
                }
                if ($purchase_order['status'] != 'completed') {
                    $this->session->set_flashdata('error', 'Only completed purchase orders can be processed for stock addition!');
                    redirect('new_purchase_orders');
                    return;
                }
                $success_count = 0;
                $total_items = 0;
                $vendor_billing_data = [];
                $i = 1;
                while ($this->input->post('product_' . $i)) {
                    $product_id = $this->input->post('product_' . $i);
                    $qty_receiving = floatval($this->input->post('qty_receiving_' . $i)) ?: 0;
                    $qty_rejected = floatval($this->input->post('qty_rejected_' . $i)) ?: 0;
                    $free_qty = floatval($this->input->post('free_qty_' . $i)) ?: 0;
                    if ($qty_receiving > 0) {
                        $total_items++;
                        $po_items = $this->New_purchase_order_model->get_purchase_order_items($po_id);
                        $item_details = null;
                        foreach ($po_items as $item) {
                            if ($item['item_number'] == $product_id) {
                                $item_details = $item;
                                break;
                            }
                        }
                        if ($item_details) {
                            $stock_data = [
                                'item_name' => $item_details['item_name'],
                                'company' => $item_details['company'],
                                'brand_name' => $item_details['brand_name'],
                                'generic_name' => $item_details['generic_name'] ?? '',
                                'vendor_number' => $purchase_order['vendor_number'],
                                'batch_number' => $this->input->post('batch_number_' . $i) ?: $item_details['batch_number'],
                                'quantity' => $qty_receiving + $free_qty,
                                'price' => $item_details['price'],
                                'vendor_price' => $this->input->post('unit_price_' . $i) ?: $item_details['vendor_price'],
                                'mrp' => $item_details['mrp'],
                                'hsn' => $item_details['hsn'],
                                'pack_size' => $item_details['pack_size'],
                                'gstrate' => intval($item_details['tax_percentage']),
                                'gstdivision' => $item_details['gst_division'] ?? 0,
                                'expiry' => $this->input->post('expiry_date_' . $i),
                                'expiry_day' => $this->input->post('notify_expiry_' . $i),
                                'date_of_purchase' => $this->input->post('po_date'),
                                'invoice_no' => $this->input->post('reference') ?: 'N/A',
                                'no_of_item' => '1',
                                'product_id' => 0,
                                'lots' => 1.0,
                                'units' => $qty_receiving + $free_qty,
                                'safety_stock' => 0,
                                'order_qty' => 0,
                                'category' => 0,
                                'pack' => 1,
                                'type' => 'medicine',
                                'medicine_type' => null,
                                'status' => 1
                            ];
                            $existing_stock = $this->New_purchase_order_model->check_existing_stock_item($item_details['item_name'], $stock_data['batch_number'], $purchase_order['vendor_number']);
                            if ($existing_stock) {
                                $update_result = $this->New_purchase_order_model->update_stock_quantity($existing_stock['ID'], $stock_data['quantity'], $stock_data);
                                if ($update_result) {
                                    $success_count++;
                                }
                            } else {
                                $insert_result = $this->New_purchase_order_model->insert_stock_item($stock_data);
                                if ($insert_result) {
                                    $success_count++;
                                }
                            }
                            $vendor_billing_data[] = [
                                'purchase_po_no' => $purchase_order['po_number'],
                                'po_date' => $this->input->post('po_date'),
                                'vendor_name' => $this->get_vendor_name($purchase_order['vendor_number']),
                                'vendor_code' => $purchase_order['vendor_number'],
                                'order_number' => $purchase_order['po_number'],
                                'upload_date' => date("Y-m-d H:i:s"),
                                'invoice_no' => $this->input->post('reference') ?: 'N/A',
                                'brand_name' => $item_details['brand_name'],
                                'mrp' => floatval($item_details['mrp']),
                                'hsn' => $item_details['hsn'],
                                'category' => $item_details['company'],
                                'date_of_purchase' => $this->input->post('po_date'),
                                'batch_number' => $stock_data['batch_number'],
                                'centre_location' => $this->input->post('ship_to'),
                                'received_by' => $this->input->post('receive_by'),
                                'date_of_receiving' => $this->input->post('receipt_date'),
                                'item_number' => $product_id,
                                'item_name' => $item_details['item_name'],
                                'company' => $item_details['company'],
                                'quantity' => $qty_receiving,
                                'expiry' => $stock_data['expiry'],
                                'vendor_price' => $stock_data['vendor_price'],
                                'gstrate' => floatval($item_details['tax_percentage']),
                                'discount_amt' => $this->input->post('discount_' . $i) ?: 0,
                                'free_quantity' => $free_qty,
                                'total_purchase_value_excl_gst' => ($qty_receiving * $stock_data['vendor_price']),
                                'freight_forwarding_charges' => 0,
                                'comment' => $this->input->post('comments_' . $i) ?: '',
                                'vendor_billing' => json_encode($uploaded_files),
                                'rate_per_unit' => $stock_data['vendor_price'],
                                'total_purchase_after_discount_exculding_gst' => ($qty_receiving * $stock_data['vendor_price']),
                                'total_purchase_value_incl_gst' => ($qty_receiving * $stock_data['vendor_price'] * (1 + floatval($item_details['tax_percentage']) / 100)),
                                'monetary_value' => 'INR',
                                'discount_rate' => '0',
                                'entry_date_in_tally' => null,
                                'msme_applicability' => 'No',
                                'medicine_type' => null
                            ];
                        }
                    }
                    $i++;
                }
                if (!empty($vendor_billing_data)) {
                    foreach ($vendor_billing_data as $billing_data) {
                        $this->New_purchase_order_model->insert_vendor_billing($billing_data);
                    }
                }
                $this->New_purchase_order_model->update_purchase_order_status($po_id, 'completed');
                if ($success_count == $total_items && $total_items > 0) {
                    $this->session->set_flashdata('success', "Stock added successfully for all {$total_items} item(s)!");
                } elseif ($success_count > 0) {
                    $this->session->set_flashdata('warning', "Stock added for {$success_count} out of {$total_items} item(s). Please check failed items.");
                } else {
                    $this->session->set_flashdata('error', 'Failed to add stock for any items. Please check your data.');
                }

                redirect('new_purchase_orders');
            }
        } else {
            header("location:" .base_url(). "");
            die;
        }
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
    private function handleFileUploads() {
        $uploaded_files = [];
        $upload_path = './uploads/receipts/';
        
        // Create upload directory if it doesn't exist
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        
        $files = $_FILES['receipt_files'];
        $file_count = count($files['name']);
        
        for ($i = 0; $i < $file_count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $file_name = $files['name'][$i];
                $file_tmp = $files['tmp_name'][$i];
                $file_size = $files['size'][$i];
                $file_type = $files['type'][$i];
                
                // Generate unique filename
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $unique_filename = time() . '_' . $i . '_' . uniqid() . '.' . $file_extension;
                $file_path = $upload_path . $unique_filename;
                
                // Validate file type
                $allowed_types = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($file_type, $allowed_types)) {
                    continue; // Skip invalid files
                }
                
                // Validate file size (5MB max)
                if ($file_size > 5 * 1024 * 1024) {
                    continue; // Skip files that are too large
                }
                
                // Move uploaded file
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $uploaded_files[] = [
                        'original_name' => $file_name,
                        'stored_name' => $unique_filename,
                        'file_path' => $file_path,
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'upload_date' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }
        
        return $uploaded_files;
    }
    
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
}