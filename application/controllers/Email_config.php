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


}
