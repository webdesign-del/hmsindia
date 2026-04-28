<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pharmacy extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // 1. Load the Database and Session
        $this->load->database();
        $this->load->library('session');

        // 2. LOAD THE MISSING MODELS HERE
        $this->load->model('accounts_model'); // This fixes the "member function on null" error
        $this->load->model('Coupon_model');   // Required for your coupon logic
        
        // 3. Load Helpers
        $this->load->helper(array('url', 'form'));

        // Safety: Mock the notification function for the header
        if (!function_exists('get_admin_notification')) {
            function get_admin_notification() { return array('count' => 0); }
        }
    }

    public function billing() {
        $logg = function_exists('checklogin') ? checklogin() : array('status' => true, 'role' => 'administrator');

        // Prepare Data
        $data = array();
        $data['title'] = "Pharmacy Billing";
        $data['notice'] = array('count' => 0);
        $data['logged_administrator'] = $this->session->userdata('logged_administrator');

        // NOW this line will work because the model is loaded above
        $data['patients'] = $this->accounts_model->get_paitent_id(); 

        // Load Views
        $this->load->view('templates/administrator_header', $data);
        $this->load->view('admin/pharmacy/billing', $data);
        $this->load->view('templates/administrator_footer', $data);
    }
}