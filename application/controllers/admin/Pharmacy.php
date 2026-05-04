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
        
        // ==================================================================
        // 1. FORM SUBMISSION LOGIC (When the user clicks "Generate Bill")
        // ==================================================================
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            
            $patient_id      = $this->input->post('patient_id');
            $total_amount    = $this->input->post('total_amount');
            $cashback_amount = (float)$this->input->post('cashback_amount');
            $coupon_code     = $this->input->post('coupon_code');

            // ... [YOUR EXISTING CODE TO SAVE THE PHARMACY BILL GOES HERE] ...

            // --- CASHBACK WALLET DEPOSIT LOGIC ---
            if ($cashback_amount > 0) {
                
                // Fetch current wallet balance
                $wallet = $this->db->get_where('hms_patient_wallets', ['patient_id' => $patient_id])->row();

                if ($wallet) {
                    // Calculate new balances (Adding to Money Wallet - wallet_1)
                    $opening_w1 = $wallet->wallet_1_balance;
                    $closing_w1 = $opening_w1 + $cashback_amount; // ADDING the cashback
                    
                    $opening_w2 = $wallet->wallet_2_balance;
                    $closing_w2 = $wallet->wallet_2_balance; // Unchanged

                    // Update the Wallet Table
                    $this->db->where('patient_id', $patient_id);
                    $this->db->update('hms_patient_wallets', [
                        'wallet_1_balance' => $closing_w1,
                        'updated_at'       => date('Y-m-d H:i:s')
                    ]);

                    // Insert Log History
                    $log_data = [
                        'patient_id'  => $patient_id,
                        'amount'      => $cashback_amount,
                        'action_type' => 'CASHBACK_EARNED',
                        'opening_w1'  => $opening_w1,
                        'closing_w1'  => $closing_w1,
                        'opening_w2'  => $opening_w2,
                        'closing_w2'  => $closing_w2,
                        'mode'        => 'Coupon',
                        'remarks'     => 'Cashback earned from code: ' . $coupon_code,
                        'created_by'  => $this->session->userdata('user_id'),
                        'created_at'  => date('Y-m-d H:i:s'),
                        'status'      => 'approved'
                    ];
                    $this->db->insert('hms_wallet_logs', $log_data);
                }
            }
            
            $this->session->set_flashdata('success', 'Bill Generated & Cashback Claimed Successfully!');
            redirect($_SERVER['HTTP_REFERER']);
            return; // Stop execution here so it doesn't load the view again
        }


        // ==================================================================
        // 2. PAGE LOAD LOGIC (When the user just visits the page to see the form)
        // ==================================================================
        $logg = function_exists('checklogin') ? checklogin() : array('status' => true, 'role' => 'administrator');

        // Prepare Data
        $data = array();
        $data['title'] = "Pharmacy Billing";
        $data['notice'] = array('count' => 0);
        $data['logged_administrator'] = $this->session->userdata('logged_administrator');

        // Fetch patients list
        $data['patients'] = $this->accounts_model->get_paitent_id(); 

        // Load Views
        $this->load->view('templates/administrator_header', $data);
        $this->load->view('admin/pharmacy/billing', $data);
        $this->load->view('templates/administrator_footer', $data);
    }

}