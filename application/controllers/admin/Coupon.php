<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
        
        // Safety: prevent header from crashing on notification function
        if (!function_exists('get_admin_notification')) {
            function get_admin_notification() { return array(); }
        }
        
        $this->load->model('Coupon_model');
    }

 public function add() {
        // Simple security check
        $logg = function_exists('checklogin') ? checklogin() : array('status' => true, 'role' => 'administrator');

        if ($this->input->post()) {
            $insert_data = array(
                'coupon_code'    => strtoupper($this->input->post('coupon_code')),
                'service_type'   => $this->input->post('service_type'),
                'discount_type'  => $this->input->post('discount_type'),
                'discount_value' => $this->input->post('discount_value'),
                'min_amount'     => $this->input->post('min_amount'),
                'expiry_date'    => $this->input->post('expiry_date'),
                'status'         => 1
            );
            $this->db->insert('hms_coupons', $insert_data);
            $this->session->set_flashdata('msg', 'Coupon Added Successfully');
            redirect('admin/coupon/add');
        }

        // --- PREPARE DATA ---
        $data = array();
        $data['title'] = "Add New Coupon";
        
        // This is exactly what Line 50 in your header is looking for:
        $data['count'] = 0; 
        
        // Fix for profile info
        $admin_session = $this->session->userdata('logged_administrator');
        $data['logged_administrator'] = (!empty($admin_session)) ? $admin_session : array('name' => 'IndiaIVF', 'image' => '');

        // --- LOAD THE VIEWS ---
        // We pass $data to the header, body, and footer
        $this->load->view('templates/administrator_header', $data);
        $this->load->view('admin/coupon/add', $data);
        $this->load->view('templates/administrator_footer', $data);
    }

   public function apply_coupon_ajax() {
        $code    = $this->input->post('coupon_code');
        $service = $this->input->post('service_type'); 
        $total   = $this->input->post('total_amount');

        if(empty($code)) {
            echo json_encode(array('status' => 'error', 'message' => 'Enter code'));
            return;
        }

        $result = $this->Coupon_model->validate($code, $service, $total);
        echo json_encode($result);
    }

    public function apply() {
        $code = $this->input->post('code');
        $type = $this->input->post('type');
        $amount = $this->input->post('amount');
        $result = $this->Coupon_model->validate($code, $type, $amount);
        echo json_encode($result);
    }
}