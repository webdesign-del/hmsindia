<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Email Configuration Controller
 * Provides endpoints for testing and managing email configuration
 */
class Email_config extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Load required helpers
        $this->load->helper('email');
        
        // Check if user is logged in (you may need to adjust this based on your auth system)
        // $this->check_auth();
    }

    /**
     * Display email configuration status
     */
    public function index() {
        $data = array();
        
        // Get configuration summary
        $data['config_summary'] = get_email_config_summary();
        
        // Validate configuration
        $data['validation'] = validate_email_config();
        
        // Check if configuration is complete
        $data['is_complete'] = is_email_config_complete();
        
        // Load view
        $this->load->view('email_config/status', $data);
    }

    /**
     * Test SMTP connection
     */
    public function test_connection() {
        // Check if this is a POST request
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        // Get test email from POST data
        $test_email = $this->input->post('test_email');
        
        // Test the connection
        $result = test_smtp_connection($test_email);
        
        // Return JSON response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    /**
     * Send test email
     */
    public function send_test() {
        // Check if this is a POST request
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        $test_email = $this->input->post('test_email');
        $subject = $this->input->post('subject') ?: 'Test Email from ' . ENVIRONMENT;
        $message = $this->input->post('message') ?: 'This is a test email to verify email configuration.';
        
        // Validate email
        if (!filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
            $result = array(
                'success' => false,
                'message' => 'Invalid email address provided'
            );
        } else {
            // Send test email
            $sent = send_mail($test_email, $subject, $message);
            
            $result = array(
                'success' => $sent,
                'message' => $sent ? 'Test email sent successfully' : 'Failed to send test email',
                'test_email' => $test_email,
                'timestamp' => date('Y-m-d H:i:s')
            );
        }
        
        // Return JSON response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    /**
     * Get configuration details
     */
    public function get_config() {
        $config = get_email_config_summary();
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($config));
    }

    /**
     * Validate configuration
     */
    public function validate() {
        $validation = validate_email_config();
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($validation));
    }

    /**
     * Check if configuration is complete
     */
    public function is_complete() {
        $is_complete = is_email_config_complete();
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('complete' => $is_complete)));
    }

    /**
     * Display test form
     */
    public function test_form() {
        $data = array();
        $data['config_summary'] = get_email_config_summary();
        
        $this->load->view('email_config/test_form', $data);
    }

    /**
     * Display configuration form
     */
    public function config_form() {
        $data = array();
        $data['config_summary'] = get_email_config_summary();
        $data['validation'] = validate_email_config();
        
        $this->load->view('email_config/config_form', $data);
    }



   

    /**
     * Send Orderbook Summary via Email
     */
    public function send_orderbook_summary() {
        // Check if this is a POST request
        if ($this->input->method() !== 'post') {
            show_404();
        }

        // Get recipient email from POST data
        $recipient_email = $this->input->post('recipient_email');
        
        // Validate email
        if (!filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
            $result = array(
                'success' => false,
                'message' => 'Invalid recipient email address provided'
            );
        } else {
            // Generate orderbook summary HTML with actual data
            $email_content = $this->generate_orderbook_email_content();
            
            // Send email using your existing send_mail function
            $subject = "Daily Orderbook Summary - " . $all_method->get_center_name($vl['billing_at']); .'-'. date('Y-m-d');
            $sent = send_mail($recipient_email, $subject, $email_content);
            
            $result = array(
                'success' => $sent,
                'message' => $sent ? 'Orderbook summary email sent successfully' : 'Failed to send orderbook summary email',
                'recipient' => $recipient_email,
                'timestamp' => date('Y-m-d H:i:s')
            );
        }
        
        // Return JSON response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    /**
     * Generate Orderbook Email HTML Content with REAL DATA
     */
 private function generate_orderbook_email_content() {
    // Fetch the same data as your daily_sales_reporting page
    // You need to replicate the data loading from your sales reporting controller
    
    $procedure_daily_result = $this->get_procedure_data();
    $medicine_daily_result = $this->get_medicine_data();
    $investigations_daily_result = $this->get_investigations_data();
    $consultation_daily_result = $this->get_consultation_data();
    $registration_daily_result = $this->get_registration_data();

    // Fetch detailed patient data
    $patient_procedure_daily_result = $this->get_patient_procedure_data();
    $patient_partial_daily_result = $this->get_patient_partial_data();
    $patient_medicine_daily_result = $this->get_patient_medicine_data();
    $patient_diagnostic_daily_result = $this->get_patient_diagnostic_data();
    $patient_consultation_daily_result = $this->get_patient_consultation_data();

    // Calculate totals
    $procedure_net = 0;
    $procedure_receive = 0;
    $procedure_total = 0;
    $procedure_discount = 0;
    
    $medicine_net = 0;
    $medicine_receive = 0;
    $medicine_total = 0;
    $medicine_discount = 0;
    
    $investigations_net = 0;
    $investigations_receive = 0;
    $investigations_total = 0;
    $investigations_discount = 0;
    
    $consultation_net = 0;
    $consultation_receive = 0;
    $consultation_total = 0;
    $consultation_discount = 0;
    $registration_payment = 0;

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f5f5f5; }
            .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .card { border: 1px solid #ddd; border-radius: 8px; margin: 20px 0; }
            .card-header { background: #f8f9fa; padding: 15px; border-bottom: 1px solid #ddd; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
            .card-content { padding: 15px; }
            .summary-stats { display: flex; margin-bottom: 20px; gap: 20px; }
            .stat { flex: 1; text-align: center; padding: 10px; background: #f8f9fa; border-radius: 5px; }
            .stat-label { font-size: 12px; color: #666; margin-bottom: 5px; }
            .stat-value { font-size: 18px; font-weight: bold; color: #333; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background: #f8f9fa; font-weight: bold; color: #333; }
            .numeric { text-align: right; }
            .total-row { font-weight: bold; background: #f0f0f0; }
            .sub-header { background: #e9ecef; font-weight: bold; }
            .approver-item { margin-bottom: 8px; padding: 8px; border-radius: 4px; border-left: 3px solid #ffc107; background-color: #f8f9fa; }
            .status-icon { margin-right: 8px; }
            .footer { margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; font-size: 12px; color: #666; }
            .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #007bff; }
            .detailed-table { font-size: 11px; }
            .detailed-table th { background: #e9ecef; padding: 8px; }
            .detailed-table td { padding: 8px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>Orderbook Summary Report</h2>
                <p><strong>HMS India - Accounts Department</strong></p>
                <p><strong>Date:</strong> ' . date('Y-m-d') . ' | <strong>Generated:</strong> ' . date('Y-m-d H:i:s') . '</p>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <span>Orderbook Summary</span>
                    <span>📊</span>
                </div>
                <div class="card-content">
                    <div class="summary-stats">
                        <div class="stat">
                            <div class="stat-label">Customer Count</div>
                            <div class="stat-value">3</div>
                        </div>
                        <div class="stat">
                            <div class="stat-label">Bill Count / Cycles Sold</div>
                            <div class="stat-value">4</div>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Type of procedures</th>
                                <th>Customer Count</th>
                                <th>Bill Count</th>
                                <th>Amount (Rs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>IVF Cycles Sold</td>
                                <td></td>
                                <td></td>
                                <td class="numeric">-</td>
                            </tr>
                            <tr>
                                <td>IVF with Bed</td>
                                <td></td>
                                <td></td>
                                <td class="numeric">-</td>
                            </tr>
                            <tr>
                                <td>Non IVF with Bed</td>
                                <td></td>
                                <td>-</td>
                                <td class="numeric">-</td>
                            </tr>
                            <tr>
                                <td>Non IVF without Bed</td>
                                <td></td>
                                <td>-</td>
                                <td class="numeric">-</td>
                            </tr>
                            <tr>
                                <td>(Not Tagged)</td>
                                <td>-</td>
                                <td>-</td>
                                <td class="numeric">-</td>
                            </tr>';

    // Procedure Data
    foreach($procedure_daily_result as $ky => $vl){
        $procedure_net += round($vl['total_patients'],2);
        $procedure_receive += round($vl['payment_done'],2);
        $procedure_total += round($vl['fees'],2);
        $procedure_discount += round($vl['discount_amount'],2);
        
        $html .= '
                            <tr class="sub-header">
                                <td>A. Package Revenue Total</td>
                                <td>' . round($vl['total_patients'],2) . '</td>
                                <td>' . round($vl['total_fees'],2) . '</td>
                                <td class="numeric">' . round($vl['total_patients'],2) . '</td>
                            </tr>';
    }

    // Medicine Data
    foreach($medicine_daily_result as $ky => $vl){
        $medicine_net += round($vl['total_patients'],2);
        $medicine_receive += round($vl['payment_done'],2);
        $medicine_total += round($vl['fees'],2);
        $medicine_discount += round($vl['discount_amount'],2);
        
        $html .= '
                            <tr>
                                <td>Medicine</td>
                                <td>' . round($vl['total_patients'],2) . '</td>
                                <td>' . round($vl['total_payment'],2) . '</td>
                                <td class="numeric">' . round($vl['total_patients'],2) . '</td>
                            </tr>';
    }

    // Investigations Data
    foreach($investigations_daily_result as $ky => $vl){
        $investigations_net += round($vl['total_patients'],2);
        $investigations_receive += round($vl['payment_done'],2);
        $investigations_total += round($vl['fees'],2);
        $investigations_discount += round($vl['discount_amount'],2);
        
        $html .= '
                            <tr>
                                <td>Diagnosis</td>
                                <td>' . round($vl['total_patients'],2) . '</td>
                                <td>' . round($vl['total_payment'],2) . '</td>
                                <td class="numeric">' . round($vl['total_patients'],2) . '</td>
                            </tr>';
    }

    // Consultation Data
    foreach($registration_daily_result as $ky => $vl){
        $registration_payment = round($vl['total_payment'],2);
    }
    
    foreach($consultation_daily_result as $ky => $vl){
        $consultation_net += round($vl['total_patients'],2);
        $consultation_receive += round($vl['payment_done'],2);
        $consultation_total += round($vl['fees'],2);
        $consultation_discount += round($vl['discount_amount'],2);
        
        $html .= '
                            <tr>
                                <td>Consultation / Registration - Paid</td>
                                <td>' . round($vl['total_patients'],2) . '</td>
                                <td>' . (round($vl['total_payment'],2) + $registration_payment) . '</td>
                                <td class="numeric">' . round($vl['total_patients'],2) . '</td>
                            </tr>';
    }

    $html .= '
                            <tr>
                                <td>Fellowship</td>
                                <td></td>
                                <td></td>
                                <td class="numeric"></td>
                            </tr>
                            <tr class="total-row">
                                <td>Total Revenue</td>
                                <td></td>
                                <td></td>
                                <td class="numeric"></td>
                            </tr>
                            <tr class="total-row">
                                <td>Status</td>
                                <td></td>
                                <td colspan="2">
                                <div class="approver-item">
                                    <div style="display: flex; align-items: center; margin-bottom: 4px;">
                                        <span class="status-icon">⏳</span>
                                        <span class="status-text">Pending</span>
                                    </div>
                                    <div class="approver-email">ghanshyam.it.kr@gmail.com</div>
                                </div>
                                <div class="approver-item">
                                    <div style="display: flex; align-items: center; margin-bottom: 4px;">
                                        <span class="status-icon">⏳</span>
                                        <span class="status-text">Pending</span>
                                    </div>
                                    <div class="approver-email">ghanshyam.it.kr@gmail.com</div>
                                </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <!-- Detailed Patient Transactions Table -->
                    <div style="margin-top: 30px;">
                        <div class="card-header">
                            <span>Detailed Patient Transactions</span>
                            <span>📋</span>
                        </div>
                        <table class="detailed-table">
                            <thead>
                                <tr>
                                    <th>S No</th>
                                    <th>IIC ID</th>
                                    <th>Patient Name</th>
                                    <th>Category</th>
                                    <th>Pkg Code</th>
                                    <th>Pkg Description</th>
                                    <th>Type</th>
                                    <th>Mode</th>
                                    <th>Collection Amount Inc GST</th>
                                    <th>Date</th>
                                    <th>Receipts No / Adjustment No</th>
                                    <th>Fresh/Partial/Advance</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>';

    // Add Procedure Transactions
    $sno = 1;
    if (!empty($patient_procedure_daily_result)) {
        foreach($patient_procedure_daily_result as $ky => $vl){
            $patient_name = $this->get_patient_name($vl['patient_id']);
            $html .= '
                                <tr>
                                    <td>' . $sno++ . '</td>
                                    <td>' . $vl['patient_id'] . '</td>
                                    <td>' . $patient_name . '</td>
                                    <td>Package</td>
                                    <td>' . $vl['code'] . '</td>
                                    <td>' . $vl['procedure_name'] . '</td>
                                    <td>Booking</td>
                                    <td>' . $vl['payment_method'] . '</td>
                                    <td class="numeric">' . $vl['payment_done'] . '</td>
                                    <td>' . $vl['on_date'] . '</td>
                                    <td>' . $vl['receipt_number'] . '</td>
                                    <td>Fresh</td>
                                    <td></td>
                                </tr>';
        }
    }

    // Add Partial Payment Transactions
    if (!empty($patient_partial_daily_result)) {
        foreach($patient_partial_daily_result as $ky => $vl){
            $patient_name = $this->get_patient_name($vl['patient_id']);
            $html .= '
                                <tr>
                                    <td>' . $sno++ . '</td>
                                    <td>' . $vl['patient_id'] . '</td>
                                    <td>' . $patient_name . '</td>
                                    <td>Partial</td>
                                    <td></td>
                                    <td></td>
                                    <td>Booking</td>
                                    <td>' . $vl['payment_method'] . '</td>
                                    <td class="numeric">' . $vl['payment_done'] . '</td>
                                    <td>' . $vl['on_date'] . '</td>
                                    <td>' . $vl['refrence_number'] . '</td>
                                    <td>Partial</td>
                                    <td></td>
                                </tr>';
        }
    }

    // Add Medicine Transactions
    if (!empty($patient_medicine_daily_result)) {
        foreach($patient_medicine_daily_result as $ky => $vl){
            $html .= '
                                <tr>
                                    <td>' . $sno++ . '</td>
                                    <td>' . $vl['patient_id'] . '</td>
                                    <td>' . $vl['patient_detail_name'] . '</td>
                                    <td>OPD Medicines</td>
                                    <td></td>
                                    <td></td>
                                    <td>Sale Receipts</td>
                                    <td>' . $vl['payment_method'] . '</td>
                                    <td class="numeric">' . $vl['payment_done'] . '</td>
                                    <td>' . $vl['on_date'] . '</td>
                                    <td>' . $vl['receipt_number'] . '</td>
                                    <td>Fresh</td>
                                    <td></td>
                                </tr>';
        }
    }

    // Add Diagnostic Transactions (removed duplicate loop)
    if (!empty($patient_diagnostic_daily_result)) {
        foreach($patient_diagnostic_daily_result as $ky => $vl){
            $patient_name = $this->get_patient_name($vl['patient_id']);
            $html .= '
                                <tr>
                                    <td>' . $sno++ . '</td>
                                    <td>' . $vl['patient_id'] . '</td>
                                    <td>' . $patient_name . '</td>
                                    <td>DIAGNOSTIC</td>
                                    <td></td>
                                    <td></td>
                                    <td>Sale Receipts</td>
                                    <td>' . $vl['payment_method'] . '</td>
                                    <td class="numeric">' . $vl['payment_done'] . '</td>
                                    <td>' . $vl['on_date'] . '</td>
                                    <td>' . $vl['receipt_number'] . '</td>
                                    <td>Fresh</td>
                                    <td></td>
                                </tr>';
        }
    }

    // Add Consultation Transactions
    if (!empty($patient_consultation_daily_result)) {
        foreach($patient_consultation_daily_result as $ky => $vl){
            $patient_name = $this->get_patient_name($vl['patient_id']);
            $html .= '
                                <tr>
                                    <td>' . $sno++ . '</td>
                                    <td>' . $vl['patient_id'] . '</td>
                                    <td>' . $patient_name . '</td>
                                    <td>OPD consultation</td>
                                    <td></td>
                                    <td></td>
                                    <td>Sale Receipts</td>
                                    <td>' . $vl['payment_method'] . '</td>
                                    <td class="numeric">' . $vl['payment_done'] . '</td>
                                    <td>' . $vl['on_date'] . '</td>
                                    <td>' . $vl['receipt_number'] . '</td>
                                    <td>Fresh</td>
                                    <td></td>
                                </tr>';
        }
    }

    $html .= '
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="footer">
                        <p><strong>Summary:</strong></p>
                        <p>Procedures: ' . count($procedure_daily_result) . ' | Medicine: ' . count($medicine_daily_result) . ' | Investigations: ' . count($investigations_daily_result) . '</p>
                        <p>Detailed Transactions: ' . ($sno - 1) . ' records</p>
                        <p><em>This is an automated orderbook summary report generated from HMS India system.</em></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';

    return $html;
}

/**
 * ADD THESE METHODS FOR PATIENT DETAILED DATA
 */
private function get_patient_procedure_data() {
    // Replace with your actual data fetching
    return array(); // Your actual data
}

private function get_patient_partial_data() {
    // Replace with your actual data fetching
    return array(); // Your actual data
}

private function get_patient_medicine_data() {
    // Replace with your actual data fetching
    return array(); // Your actual data
}

private function get_patient_diagnostic_data() {
    // Replace with your actual data fetching
    return array(); // Your actual data
}

private function get_patient_consultation_data() {
    // Replace with your actual data fetching
    return array(); // Your actual data
}

private function get_patient_name($patient_id) {
    // Implement your patient name lookup logic here
    // This should match your existing get_patient_name method
    // Example: return $this->Your_model->get_patient_name_by_id($patient_id);
    return "Patient Name"; // Replace with actual implementation
}

/**
 * REPLACE THESE METHODS WITH YOUR ACTUAL DATA FETCHING LOGIC
 * These should match how you get data in your daily_sales_reporting
 */
private function get_procedure_data() {
    // Replace with your actual data fetching
    return array(); // Your actual data
}

private function get_medicine_data() {
    // Replace with your actual data fetching
    return array(); // Your actual data
}

private function get_investigations_data() {
    // Replace with your actual data fetching
    return array(); // Your actual data
}

private function get_consultation_data() {
    // Replace with your actual data fetching
    return array(); // Your actual data
}

private function get_registration_data() {
    // Replace with your actual data fetching
    return array(); // Your actual data
}


}
