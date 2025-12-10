<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {
	public function __construct()
	{

		// Load parent's constructor.

       	parent::__construct();
        
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
	    $this->load->library('session');
		$this->load->model('accounts_model');
		$this->load->model('billings_model');
		$this->load->model('Purchase_order_model');
		$this->load->model('billingmodel_model');
		$this->load->model('center_model');
		$this->load->model('stock_model');
		$this->load->helper('myhelper');
		$this->load->library("pagination");
		
	}
		
    public function purchase_order()
	{	
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/purchase_order', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function save_purchase_order()
	{
		$logg = checklogin();
		if ($this->input->post('action') == 'add_purchase_orders') {
			$approved_by = $this->input->post('approved_by');
			$approved_by_str = !empty($approved_by) ? implode(", ", $approved_by) : null;
			$uploaded_file = null;
			if (!empty($_FILES['po_supporting_documents']['name'])) {
				// Use the configured upload path from config
				$dest_path = $this->config->item('upload_path');
				$config['upload_path']   = $dest_path . 'purchase_orders/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png|webp|gif|bmp';
				$config['max_size']      = 10240; // 10 MB
				$config['file_ext_tolower'] = TRUE;
				$config['remove_spaces'] = TRUE;
				$config['overwrite']     = FALSE;
				
				// Log upload path for debugging
				log_message('info', 'Upload path: ' . $config['upload_path']);
				log_message('info', 'File info: ' . print_r($_FILES['po_supporting_documents'], true));
				
				// Create directory if it doesn't exist
				if (!is_dir($config['upload_path'])) {
					mkdir($config['upload_path'], 0777, true);
					log_message('info', 'Created directory: ' . $config['upload_path']);
				}
				
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('po_supporting_documents')) {
					$error = $this->upload->display_errors('', '');
					// Log the error for debugging
					log_message('error', 'PO Supporting Documents upload failed: ' . $error . ' for file: ' . $_FILES['po_supporting_documents']['name']);
					log_message('error', 'Upload path: ' . $config['upload_path']);
					log_message('error', 'Directory exists: ' . (is_dir($config['upload_path']) ? 'Yes' : 'No'));
					log_message('error', 'Directory writable: ' . (is_writable($config['upload_path']) ? 'Yes' : 'No'));
					$this->session->set_flashdata('error', 'File upload failed: ' . $error);
					redirect('accounts/purchase_order');
					return;
				}
				
				$upload_data  = $this->upload->data();
				$uploaded_file = $upload_data['file_name'];
				log_message('info', 'File uploaded successfully: ' . $uploaded_file);
			}
			$approval_token = bin2hex(random_bytes(16));
			$po_number = $this->Purchase_order_model->generate_po_number();
			$data = [
				'po_number'                          => $po_number,
				'po_centre'                          => $this->input->post('po_centre'),
				'po_department'                      => $this->input->post('po_department'),
				'po_nature_of_expenditure'           => $this->input->post('po_nature_of_expenditure'),
				'po_budget_head'                     => $this->input->post('po_budget_head'),
				'po_budget_item'                     => $this->input->post('po_budget_item'),
				'po_approved_by'                     => $approved_by_str,
				'po_name_of_vendor'                  => $this->input->post('po_name_of_vendor'),
				'po_remarks_or_comment_or_narration' => $this->input->post('po_remarks_or_comment_or_narration'),
				'po_basic_amount'                    => $this->input->post('po_basic_amount'),
				'po_gst_amount'                      => $this->input->post('po_gst_amount'),
				'po_other_charges_and_taxes'         => $this->input->post('po_other_charges_and_taxes'),
				'po_po_total'                        => $this->input->post('po_po_total'),
				'po_others_name'                     => $this->input->post('po_others_name'),
				'po_supporting_documents'            => $uploaded_file, // save uploaded filename
				'approval_token'                     => $approval_token, // Store the approval token
				'created_by'                         => $this->session->userdata['logged_administrator']['employee_number'],
				'created_at'                         => date('Y-m-d H:i:s'),
				'status'                             => '0'
			];
			$this->load->model('Purchase_order_model');
			$po_id = $this->Purchase_order_model->insert_purchase_order($data);
			
			if ($po_id) {
				$email_sent_count = 0;
				$total_approvers = count($approved_by);
				$approver_tokens = [];
				foreach ($approved_by as $approver) {
					$approver_token = bin2hex(random_bytes(16));
					$approver_tokens[] = [
						'email' => $approver,
						'token' => $approver_token,
						'status' => 'pending',
						'created_at' => date('Y-m-d H:i:s')
					];
					$email_sent = $this->_send_po_email($approver, $po_number, $approver_token, $data);
					if ($email_sent) {
						$email_sent_count++;
					}
				}
				
				// Store all approver tokens as JSON in the purchase order
				$this->Purchase_order_model->store_approver_tokens($po_number, $approver_tokens);
				
				if ($email_sent_count > 0) {
					if ($email_sent_count == $total_approvers) {
						$this->session->set_flashdata('success', "Purchase Order added successfully! Approval emails sent to all {$total_approvers} approver(s).");
					} else {
						$this->session->set_flashdata('warning', "Purchase Order added successfully! Approval emails sent to {$email_sent_count} out of {$total_approvers} approver(s).");
					}
				} else {
					$this->session->set_flashdata('warning', 'Purchase Order added successfully! However, approval emails could not be sent. Please contact administrator.');
				}
			} else {
				$this->session->set_flashdata('error', 'Failed to save Purchase Order. Please try again.');
			}
			redirect('accounts/purchase-orders-list');
		}
	}
	private function _send_po_email($approver_email, $po_number, $token, $data)
	{
		try {
			$review_url = base_url("accounts/review_po/{$token}");
			$message = "
			<!DOCTYPE html>
			<html>
			<head>
				<meta charset='UTF-8'>
				<meta name='viewport' content='width=device-width, initial-scale=1.0'>
				<title>Purchase Order Review Required</title>
				<style>
					body { 
						font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
						line-height: 1.6; 
						color: #333; 
						background-color: #f8f9fa; 
						margin: 0; 
						padding: 20px; 
					}
					.container { 
						max-width: 600px; 
						margin: 0 auto; 
						background: white; 
						border-radius: 12px; 
						box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
						overflow: hidden; 
					}
					.header { 
						background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
						color: white; 
						padding: 30px; 
						text-align: center; 
					}
					.header h1 { 
						margin: 0 0 10px 0; 
						font-size: 28px; 
						font-weight: 300; 
					}
					.header .subtitle { 
						font-size: 16px; 
						opacity: 0.9; 
					}
					.content { 
						padding: 30px; 
					}
					.po-summary { 
						background: #f8f9fa; 
						border-radius: 8px; 
						padding: 20px; 
						margin: 20px 0; 
						border-left: 4px solid #667eea; 
					}
					.po-grid { 
						display: grid; 
						grid-template-columns: 1fr 1fr; 
						gap: 15px; 
						margin: 20px 0; 
					}
					.po-item { 
						background: white; 
						padding: 15px; 
						border-radius: 6px; 
						border: 1px solid #e9ecef; 
					}
					.po-label { 
						font-size: 12px; 
						color: #6c757d; 
						text-transform: uppercase; 
						letter-spacing: 0.5px; 
						margin-bottom: 5px; 
					}
					.po-value { 
						font-size: 16px; 
						font-weight: 600; 
						color: #333; 
					}
					.total-amount { 
						background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
						color: white; 
						padding: 20px; 
						border-radius: 8px; 
						text-align: center; 
						margin: 20px 0; 
					}
					.total-amount .amount { 
						font-size: 32px; 
						font-weight: bold; 
						margin: 10px 0; 
					}
					.action-section { 
						background: #fff3cd; 
						border: 1px solid #ffeaa7; 
						border-radius: 8px; 
						padding: 20px; 
						margin: 20px 0; 
						text-align: center; 
					}
					.action-section h3 { 
						color: #856404; 
						margin: 0 0 15px 0; 
					}
					.btn-review { 
						display: inline-block; 
						padding: 16px 32px; 
						background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); 
						color: white; 
						text-decoration: none; 
						border-radius: 8px; 
						font-weight: 600; 
						font-size: 16px; 
						box-shadow: 0 4px 15px rgba(0,123,255,0.3); 
						transition: all 0.3s ease; 
					}
					.btn-review:hover { 
						transform: translateY(-2px); 
						box-shadow: 0 6px 20px rgba(0,123,255,0.4); 
					}
					.footer { 
						background: #f8f9fa; 
						padding: 20px; 
						text-align: center; 
						color: #6c757d; 
						font-size: 14px; 
					}
					@media (max-width: 600px) {
						.po-grid { 
							grid-template-columns: 1fr; 
						}
						.container { 
							margin: 10px; 
						}
					}
				</style>
			</head>
			<body>
				<div class='container'>
					<div class='header'>
						<h1> Purchase Order Review Required</h1>
						<div class='subtitle'>New PO submitted and awaiting your approval</div>
					</div>
					
					<div class='content'>
						<div class='po-summary'>
							<h3 style='margin: 0 0 15px 0; color: #667eea;'>PO Summary</h3>
							<div class='po-grid'>
								<div class='po-item'>
									<div class='po-label'>PO Number</div>
									<div class='po-value'>{$po_number}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Centre/Cluster/Region</div>
									<div class='po-value'>{$data['po_centre']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Department</div>
									<div class='po-value'>{$data['po_department']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Nature of Expenditure</div>
									<div class='po-value'>{$data['po_nature_of_expenditure']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Budget Head</div>
									<div class='po-value'>{$data['po_budget_head']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Vendor</div>
									<div class='po-value'>{$data['po_name_of_vendor']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Budget Item</div>
									<div class='po-value'>{$data['po_budget_item']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Basic Amount</div>
									<div class='po-value'>{$data['po_basic_amount']}</div>
								</div>	
								<div class='po-item'>
									<div class='po-label'>GST Amount</div>
									<div class='po-value'>{$data['po_gst_amount']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Other Charges and Taxes</div>
									<div class='po-value'>{$data['po_other_charges_and_taxes']}</div>
								</div>
							</div>
						</div>
						
						<div class='total-amount'>
							<div class='po-label' style='color: rgba(255,255,255,0.8);'>Total Amount</div>
							<div class='amount'>&#8377;" . number_format($data['po_po_total'], 2) . "</div>
							<div style='font-size: 14px; opacity: 0.9;'>Including GST & All Charges</div>
						</div>
						
						<div class='action-section'>
							<h3>Action Required</h3>
							<p style='margin: 0 0 20px 0; color:#e5e5e5;'>Please review all details and make your approval decision</p>
							<a href='{$review_url}' class='btn-review'>
							   Review Purchase Order Details
							</a>
						</div>
						
						<div style='background: #e9ecef; padding: 15px; border-radius: 6px; margin: 20px 0;'>
							<p style='margin: 0; font-size: 14px; color: #495057;'>
								<strong>Note:</strong> You will be able to see all form details before making your approval decision.
							</p>
						</div>
					</div>
					
					<div class='footer'>
						This is an automated message from the Purchase Order System<br>
						<small>Please do not reply to this email</small>
					</div>
				</div>
			</body>
			</html>";
			$subject = "Approval Needed: Purchase Order #{$po_number}";
			$to_email = !empty($approver_email) ? $approver_email : 'accounts@indiaivf.in';
			$sent = send_mail($to_email, $subject, $message);
			if (!$sent) {
				log_message('error', 'PO Approval Email Failed for PO: ' . $po_number);
				return false;
			}
			return true;
		} catch (Exception $e) {
			log_message('error', 'PO Email Exception: ' . $e->getMessage());
			return false;
		}
	}
	/**
	 * Send status update email to PO creator
	 */
	private function _send_po_status_update_email($po_data, $status, $status_text = null)
	{
		// echo "<pre>";
		// print_r($po_data);
		// echo "<br>";
		// print_r($status);
		// echo "<br>";
		// print_r($status_text);
		// echo "<br>";
		// die();
		try {
			if ($status_text === null) {
				// Use the status text passed from the main function
				// This ensures consistency between the approval page and email
				$status_text = 'Status Update';
			}
			// Better status color and icon mapping for email
			if ($status == '1') {
				$status_color = 'green';
				$status_icon = '✓';
			} elseif ($status == '0') {
				$status_color = 'red';
				$status_icon = '✗';
			} else {
				$status_color = '#007bff'; // Blue for "Awaiting Approval"
				$status_icon = '⏳';
			}
			
			$message = "
			<!DOCTYPE html>
			<html>
			<head>
				<meta charset='UTF-8'>
				<meta name='viewport' content='width=device-width, initial-scale=1.0'>
				<title>Purchase Order {$status_text}</title>
				<style>
					body { 
						font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
						line-height: 1.6; 
						color: #333; 
						background-color: #f8f9fa; 
						margin: 0; 
						padding: 20px; 
					}
					.container { 
						max-width: 600px; 
						margin: 0 auto; 
						background: white; 
						border-radius: 12px; 
						box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
						overflow: hidden; 
					}
					.header { 
						background: linear-gradient(135deg, " . ($status == '1' ? '#28a745 0%, #20c997 100%' : (($status == '0') ? '#dc3545 0%, #c82333 100%' : '#007bff 0%, #0056b3 100%')) . "); 
						color: white; 
						padding: 30px; 
						text-align: center; 
					}
					.header h1 { 
						margin: 0 0 10px 0; 
						font-size: 28px; 
						font-weight: 300; 
					}
					.header .subtitle { 
						font-size: 16px; 
						opacity: 0.9; 
					}
					.content { 
						padding: 30px; 
					}
					.status-badge { 
						display: inline-block; 
						padding: 8px 16px; 
						background: " . ($status == '1' ? '#28a745' : (($status == '0') ? '#dc3545' : '#007bff')) . "; 
						color: white; 
						border-radius: 20px; 
						font-weight: 600; 
						font-size: 14px; 
						margin: 10px 0; 
					}
					.po-summary { 
						background: #f8f9fa; 
						border-radius: 8px; 
						padding: 20px; 
						margin: 20px 0; 
						border-left: 4px solid " . ($status == '1' ? '#28a745' : (($status == '0') ? '#dc3545' : '#007bff')) . "; 
					}
					.po-grid { 
						display: grid; 
						grid-template-columns: 1fr 1fr; 
						gap: 15px; 
						margin: 20px 0; 
					}
					.po-item { 
						background: white; 
						padding: 15px; 
						border-radius: 6px; 
						border: 1px solid #e9ecef; 
					}
					.po-label { 
						font-size: 12px; 
						color: #6c757d; 
						text-transform: uppercase; 
						letter-spacing: 0.5px; 
						margin-bottom: 5px; 
					}
					.po-value { 
						font-size: 16px; 
						font-weight: 600; 
						color: #333; 
					}
					.total-amount { 
						background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
						color: white; 
						padding: 20px; 
						border-radius: 8px; 
						text-align: center; 
						margin: 20px 0; 
					}
					.total-amount .amount { 
						font-size: 32px; 
						font-weight: bold; 
						margin: 10px 0; 
					}
					.financial-details { 
						background: #e9ecef; 
						border-radius: 8px; 
						padding: 20px; 
						margin: 20px 0; 
					}
					.financial-grid { 
						display: grid; 
						grid-template-columns: 1fr 1fr; 
						gap: 15px; 
						margin: 15px 0; 
					}
					.financial-item { 
						background: white; 
						padding: 12px; 
						border-radius: 6px; 
						text-align: center; 
					}
					.financial-label { 
						font-size: 11px; 
						color: #6c757d; 
						text-transform: uppercase; 
						letter-spacing: 0.5px; 
						margin-bottom: 5px; 
					}
					.financial-value { 
						font-size: 18px; 
						font-weight: 600; 
						color: #333; 
					}
					.footer { 
						background: #f8f9fa; 
						padding: 20px; 
						text-align: center; 
						color: #6c757d; 
						font-size: 14px; 
					}
					@media (max-width: 600px) {
						.po-grid, .financial-grid { 
							grid-template-columns: 1fr; 
						}
						.container { 
							margin: 10px; 
						}
					}
				</style>
			</head>
			<body>
				<div class='container'>
					<div class='header'>
						<h1>Purchase Order {$status_text}</h1>
						<div class='subtitle'>Your purchase order has been <strong>{$status_text}</strong></div>
						<div class='status-badge'>{$status_text}</div>
					</div>
					
					<div class='content'>
						<div class='po-summary'>
							<h3 style='margin: 0 0 15px 0; color: " . ($status == '1' ? '#28a745' : (($status == '0') ? '#dc3545' : '#ffc107')) . ";'>PO Summary</h3>
							<div class='po-grid'>
								<div class='po-item'>
									<div class='po-label'>PO Number</div>
									<div class='po-value'>{$po_data['po_number']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Centre/Cluster/Region</div>
									<div class='po-value'>{$po_data['po_centre']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Department</div>
									<div class='po-value'>{$po_data['po_department']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Nature of Expenditure</div>
									<div class='po-value'>{$po_data['po_nature_of_expenditure']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Budget Head</div>
									<div class='po-value'>{$po_data['po_budget_head']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Vendor</div>
									<div class='po-value'>{$po_data['po_name_of_vendor']}</div>
								</div>
								<div class='po-item'>
									<div class='po-label'>Budget Item</div>
									<div class='po-value'>{$po_data['po_budget_item']}</div>
								</div>
							</div>
						</div>
						
						<div class='total-amount'>
							<div class='po-label' style='color: rgba(255,255,255,0.8);'>PO Total Amount</div>
							<div class='amount'>&#8377;" . number_format($po_data['po_po_total'], 2) . "</div>
							<div style='font-size: 14px; opacity: 0.9;'>Including GST & All Charges</div>
						</div>
						
						<div class='financial-details'>
							<h4 style='margin: 0 0 15px 0; color: #495057;'>Financial Breakdown</h4>
							<div class='financial-grid'>
								<div class='financial-item'>
									<div class='financial-label'>Basic Amount (Ex GST)</div>
									<div class='financial-value'>&#8377;" . number_format($po_data['po_basic_amount'], 2) . "</div>
								</div>
								<div class='financial-item'>
									<div class='financial-label'>GST Amount</div>
									<div class='financial-value'>&#8377;" . number_format($po_data['po_gst_amount'], 2) . "</div>
								</div>
								<div class='financial-item'>
									<div class='financial-label'>Other Charges & Taxes</div>
									<div class='financial-value'>&#8377;" . number_format($po_data['po_other_charges_and_taxes'], 2) . "</div>
								</div>
								<div class='financial-item'>
									<div class='financial-label'>Total Amount</div>
									<div class='financial-value' style='color: #28a745;'>&#8377;" . number_format($po_data['po_po_total'], 2) . "</div>
								</div>
							</div>
						</div>
						
						<div style='background: #e9ecef; padding: 15px; border-radius: 6px; margin: 20px 0;'>
							<h4 style='margin: 0 0 10px 0; color: #495057;'>Additional Details</h4>
							<p style='margin: 0 0 10px 0;'><strong>Remarks/Comment/Narration:</strong> {$po_data['po_remarks_or_comment_or_narration']}</p>
							<p style='margin: 0 0 10px 0;'><strong>Status Updated:</strong> " . date('Y-m-d H:i:s') . "</p>
							" . (!empty($po_data['po_supporting_documents']) ? "
							<p style='margin: 0;'><strong>Supporting Documents:</strong> <a href='" . base_url("assets/purchase_orders/{$po_data['po_supporting_documents']}") . "' target='_blank' style='color: #007bff; text-decoration: underline;'> View Document</a></p>" : "") . "
						</div>
						
						<div style='background: #d4edda; padding: 15px; border-radius: 6px; margin: 20px 0; border: 1px solid #c3e6cb;'>
							<p style='margin: 0; font-size: 14px; color: #155724;'>
								<strong>Next Steps:</strong> You can view the updated status in the Purchase Order system.
							</p>
						</div>
					</div>
					
					<div class='footer'>
						This is an automated message from the Purchase Order System<br>
						<small>Please do not reply to this email</small>
					</div>
				</div>
			</body>
			</html>";
			
			$subject = "Purchase Order #{$po_data['po_number']} - {$status_text}";
			// Get creator email from session or use default
			$creator_email = 'accounts@indiaivf.in'; // Default fallback
			$sent = send_mail($creator_email, $subject, $message);
			if (!$sent) {
				log_message('error', 'PO Status Update Email Failed for PO: ' . $po_data['po_number']);
				return false;
			}
			return true;
		} catch (Exception $e) {
			log_message('error', 'PO Status Update Email Exception: ' . $e->getMessage());
			return false;
		}
	}


    public function approve_po($token, $action)
    {
        try {
            $this->load->model('Purchase_order_model');
            
            // Get the approver token details from the JSON field
            $approver_token = $this->Purchase_order_model->get_approver_token_details($token);
            if (!$approver_token) {
                throw new Exception("Invalid or expired approval link.");
            }
            
            // Get the purchase order details using the PO number from the approver token
            $po = $this->Purchase_order_model->get_purchase_order_by_id($approver_token['po_number']);
            if (!$po) {
                throw new Exception("Purchase order not found.");
            }
            // Update the approver token status
            $approver_status = ($action == 'Approved') ? 'approved' : 'rejected';
            $approver_updated = $this->Purchase_order_model->update_approver_token_status(
                $token, 
                $approver_status, 
                $action == 'Approved' ? 'Approved by ' . $approver_token['approver_email'] : 'Rejected by ' . $approver_token['approver_email']
            );
            if (!$approver_updated) {
				throw new Exception("Failed to update approver status.");
            }
            
            // Check if all approvers have responded by parsing the JSON field
            $all_tokens = json_decode($po['approver_tokens'], true);
            if (!$all_tokens) {
                throw new Exception("Failed to parse approver tokens.");
            }
            
            $all_approved = true;
            $any_rejected = false;
            
            foreach ($all_tokens as $token_data) {
                if ($token_data['status'] === 'pending') {
                    $all_approved = false;
                } elseif ($token_data['status'] === 'rejected') {
                    $any_rejected = true;
                }
            }
            
            // Update main PO status based on approver responses
            $total_approvers = count($all_tokens);
            $approved_count = 0;
            $rejected_count = 0;
            
            foreach ($all_tokens as $token_data) {
                if ($token_data['status'] === 'approved') {
                    $approved_count++;
                } elseif ($token_data['status'] === 'rejected') {
                    $rejected_count++;
                }
            }
            // Fixed logic: Handle single approver and multiple approver scenarios properly
            if ($total_approvers == 1) {
                // Single approver scenario - direct decision
                if ($approved_count == 1) {
                    $status = '1'; // Approved
                } elseif ($rejected_count == 1) {
                    $status = '0'; // Rejected
                } else {
                    // For single approver, use status '2' to indicate "Awaiting Decision"
                    // This distinguishes it from "new PO" (status '0') and "approved/rejected"
                    $status = '2'; // Awaiting decision from single approver
                }
            } else {
                // Multiple approvers scenario - majority rule
                $majority_threshold = ceil($total_approvers / 2);
                
                if ($approved_count >= $majority_threshold) {
                    $status = '1'; // Approved (majority approved)
                } elseif ($rejected_count >= $majority_threshold) {
                    $status = '0'; // Rejected (majority rejected)
                } else {
                    $status = '2'; // Partially approved (waiting for more responses or mixed)
                }
            }
            
            // Update the main PO status
            $po_updated = $this->Purchase_order_model->update_status($po['po_number'], $status);
            if (!$po_updated) {
                throw new Exception("Failed to update PO status.");
            }
            // Fixed status text mapping based on scenario
            if ($total_approvers == 1) {
                // Single approver - clear status mapping
                if ($status == '1') {
                    $status_text = 'Approved';
                } elseif ($status == '0') {
                    $status_text = 'Rejected';
                } else {
                    $status_text = 'Awaiting Decision'; // Single approver waiting for decision
                }
            } else {
                // Multiple approvers - use standard status
                $status_text = ($status == '1') ? 'Approved' : (($status == '0') ? 'Rejected' : 'Partially Approved');
            }
            log_message('info', "PO #{$po['po_number']} has been {$status_text} by token: {$token}");
            $this->_send_po_status_update_email($po, $status, $status_text);
            // Fixed status color mapping based on scenario
            if ($total_approvers == 1 && $status == '2') {
                $status_color = '#007bff'; // Blue for "Awaiting Decision" in single approver scenario
            } else {
                $status_color = ($status == '1') ? 'green' : (($status == '0') ? 'red' : 'orange');
            }
            // Fixed icon mapping based on scenario
            if ($total_approvers == 1 && $status == '2') {
                $icon = '⏳'; // Hourglass for "Awaiting Decision" in single approver scenario
            } else {
                $icon = ($status == '1') ? '✓' : (($status == '0') ? '✗' : '⏳');
            }
            log_message('info', 'About to output HTML for PO approval');
            if (ob_get_level()) {
                ob_end_clean();
            }
            echo "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset=\"UTF-8\">
                <title>Purchase Order " . ($status == '1' ? 'Approved' : (($status == '0') ? 'Rejected' : ($total_approvers == 1 ? 'Awaiting Decision' : 'Partially Approved'))) . "</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                    .status-box { 
                        border: 2px solid {$status_color}; 
                        border-radius: 10px; 
                        padding: 30px; 
                        margin: 20px auto; 
                        max-width: 500px;
                        background-color: #f9f9f9;
                    }
                    .status-icon { font-size: 48px; color: {$status_color}; }
                    .status-text { color: {$status_color}; font-weight: bold; font-size: 24px; }
                    .po-details { margin: 20px 0; text-align: left; }
                    .po-details table { width: 100%; border-collapse: collapse; margin: 10px 0; border: 1px solid #dee2e6; }
                    .po-details td { padding: 8px; border: 1px solid #dee2e6; }
                    .po-details tr:nth-child(even) { background-color: #f8f9fa; }
                    .po-details tr:first-child { background-color: #e9ecef; }
                    .po-details .total-row { background-color: #e9ecef; font-weight: bold; color: #28a745; }
                    .back-link { margin-top: 20px; }
                    .back-link a { 
                        color: #007bff; 
                        text-decoration: none; 
                        padding: 10px 20px; 
                        border: 1px solid #007bff; 
                        border-radius: 5px;
                    }
                    .back-link a:hover { background-color: #007bff; color: white; }
                </style>
            </head>
            <body>
                <div class='status-box'>
                    <div class='status-icon'>{$icon}</div>
                                         <div class='status-text'>Purchase Order " . ($status == '1' ? 'Approved' : (($status == '0') ? 'Rejected' : ($total_approvers == 1 ? 'Awaiting Decision' : 'Partially Approved'))) . "</div>
                    <div style='text-align: center; margin: 10px 0; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 14px; color: #6c757d;'>
                        <strong>Approval Details:</strong><br>
                        Total Approvers: {$total_approvers}<br>
                        Approved: {$approved_count} | Rejected: {$rejected_count}<br>
                        Final Status: {$status_text} (Code: {$status})
                    </div>
                    <div class='po-details'>
                        <h4 style='margin-bottom: 15px; color: #333;'>Complete Purchase Order Details:</h4>
                        <table style='width: 100%; border-collapse: collapse; margin: 10px 0; border: 1px solid #dee2e6;'>
                            <tr style='background-color: #e9ecef;'>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold; width: 40%;'>PO Number:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$po['po_number']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Centre/Cluster/Region:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$po['po_centre']}</td>
                            </tr>
                            <tr style='background-color: #f8f9fa;'>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Department:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$po['po_department']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Nature of Expenditure:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$po['po_nature_of_expenditure']}</td>
                            </tr>
                            <tr style='background-color: #f8f9fa;'>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Budget Head:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$po['po_budget_head']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Budget Item:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$po['po_budget_item']}</td>
                            </tr>
                            <tr style='background-color: #f8f9fa;'>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Vendor Name:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$po['po_name_of_vendor']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Basic Amount (Ex GST):</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>&#8377;" . number_format($po['po_basic_amount'], 2) . "</td>
                            </tr>
                            <tr style='background-color: #f8f9fa;'>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>GST Amount:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>&#8377;" . number_format($po['po_gst_amount'], 2) . "</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Other Charges & Taxes:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>&#8377;" . number_format($po['po_other_charges_and_taxes'], 2) . "</td>
                            </tr>
                            <tr style='background-color: #e9ecef;'>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Total Amount (Inc GST & All Charges):</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold; color: #28a745;'>&#8377;" . number_format($po['po_po_total'], 2) . "</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Remarks/Comment/Narration:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$po['po_remarks_or_comment_or_narration']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Status Updated:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>" . date('Y-m-d H:i:s') . "</td>
                            </tr>
                            " . (!empty($po['po_supporting_documents']) ? "
                            <tr style='background-color: #f8f9fa;'>
                                <td style='padding: 8px; border: 1px solid #dee2e6; font-weight: bold;'>Supporting Documents:</td>
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>Attached</td>
                            </tr>" : "") . "
                        </table>
                    </div>
                    <div class='back-link'>
                        <a href='" . base_url('/') . "'>Back to Home Page</a>
                    </div>
                </div>
            </body>
            </html>";
        } catch (Exception $e) {
            log_message('error', 'PO Approval Failed: ' . $e->getMessage());
            
            // Debug output
            log_message('info', 'About to output HTML for PO error');
            
            // Ensure no output buffering issues
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            echo "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset=\"UTF-8\">
                <title>Approval Error</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                    .error-box { 
                        border: 2px solid #dc3545; 
                        border-radius: 10px; 
                        padding: 30px; 
                        margin: 20px auto; 
                        max-width: 500px;
                        background-color: #f9f9f9;
                    }
                    .error-icon { font-size: 48px; color: #dc3545; }
                    .error-text { color: #dc3545; font-weight: bold; font-size: 24px; }
                    .back-link { margin-top: 20px; }
                    .back-link a { 
                        color: #007bff; 
                        text-decoration: none; 
                        padding: 10px 20px; 
                        border: 1px solid #007bff; 
                        border-radius: 5px;
                    }
                    .back-link a:hover { background-color: #007bff; color: white; }
                </style>
            </head>
            <body>
                <div class='error-box'>
                    <div class='error-icon'>⚠</div>
                    <div class='error-text'>Approval Error</div>
                    <p>{$e->getMessage()}</p>
                    <div class='back-link'>
                        <a href='" . base_url('accounts/purchase-orders-list') . "'>Back to Purchase Orders</a>
                    </div>
                </div>
            </body>
            </html>";
        }
    }

    public function review_po($token)
    {
        try {
            $this->load->model('Purchase_order_model');
            
            // Get the approver token details from the JSON field
            $approver_token = $this->Purchase_order_model->get_approver_token_details($token);
            if (!$approver_token) {
                throw new Exception("Invalid or expired approval link.");
            }
            
            // Get the purchase order details using the PO number from the approver token
            $po = $this->Purchase_order_model->get_purchase_order_by_id($approver_token['po_number']);
            if (!$po) {
                throw new Exception("Purchase order not found.");
            }
            
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            echo "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset=\"UTF-8\">
                <title>Review Purchase Order #{$po['po_number']}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5; }
                    .container { max-width: 1000px; margin: 0 auto; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .header h1 { color: #333; margin-bottom: 10px; }
                    .header .po-number { color: #007bff; font-size: 18px; font-weight: bold; }
                    .po-details { 
                        background: white; 
                        border-radius: 10px; 
                        padding: 30px; 
                        margin-bottom: 30px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    }
                    .po-details h2 { color: #333; margin-bottom: 20px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
                    .po-details table { width: 100%; border-collapse: collapse; margin: 10px 0; border: 1px solid #dee2e6; }
                    .po-details td { padding: 12px; border: 1px solid #dee2e6; }
                    .po-details tr:nth-child(even) { background-color: #f8f9fa; }
                    .po-details tr:first-child { background-color: #e9ecef; }
                    .po-details .total-row { background-color: #e9ecef; font-weight: bold; color: #28a745; }
                    .action-buttons { 
                        text-align: center; 
                        margin: 30px 0; 
                        padding: 20px;
                        background: white;
                        border-radius: 10px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    }
                    .btn { 
                        display: inline-block;
                        padding: 15px 30px; 
                        margin: 0 15px;
                        border: none; 
                        border-radius: 5px; 
                        font-size: 16px;
                        font-weight: bold;
                        text-decoration: none;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    }
                    .btn-approve { 
                        background-color: #28a745; 
                        color: white; 
                    }
                    .btn-approve:hover { background-color: #218838; }
                    .btn-reject { 
                        background-color: #dc3545; 
                        color: white; 
                    }
                    .btn-reject:hover { background-color: #c82333; }
                    .btn-cancel { 
                        background-color: #6c757d; 
                        color: white; 
                    }
                    .btn-cancel:hover { background-color: #5a6268; }
                    .warning { 
                        background-color: #fff3cd; 
                        border: 1px solid #ffeaa7; 
                        color: #856404; 
                        padding: 15px; 
                        border-radius: 5px; 
                        margin: 20px 0;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Purchase Order Review</h1>
                        <div class='po-number'>PO Number: {$po['po_number']}</div>
                    </div>
                    
                    <div class='po-details'>
                        <h2>Complete Purchase Order Details</h2>
                        <table>
                            <tr style='background-color: #e9ecef;'>
                                <td style='font-weight: bold; width: 40%;'>PO Number:</td>
                                <td>{$po['po_number']}</td>
                            </tr>
                            <tr>
                                <td style='font-weight: bold;'>Centre/Cluster/Region:</td>
                                <td>{$po['po_centre']}</td>
                            </tr>
                            <tr style='background-color: #f8f9fa;'>
                                <td style='font-weight: bold;'>Department:</td>
                                <td>{$po['po_department']}</td>
                            </tr>
                            <tr>
                                <td style='font-weight: bold;'>Nature of Expenditure (Capex/Opex):</td>
                                <td>{$po['po_nature_of_expenditure']}</td>
                            </tr>
                            <tr style='background-color: #f8f9fa;'>
                                <td style='font-weight: bold;'>Budget Head:</td>
                                <td>{$po['po_budget_head']}</td>
                            </tr>
                            <tr style='background-color: #f8f9fa;'>
                                <td style='font-weight: bold;'>Name of Vendor:</td>
                                <td>{$po['po_name_of_vendor']}</td>
                            </tr>
                            <tr>
                                <td style='font-weight: bold;'>Budget Item:</td>
                                <td>{$po['po_budget_item']}</td>
                            </tr>
                            <tr style='background-color: #f8f9fa;'>
                                <td style='font-weight: bold;'>Remarks/Comment/Narration:</td>
                                <td>{$po['po_remarks_or_comment_or_narration']}</td>
                            </tr>
                            <tr>
                                <td style='font-weight: bold;'>Basic Amount (Ex GST):</td>
                                <td>&#8377;" . number_format($po['po_basic_amount'], 2) . "</td>
                            </tr>
                            <tr style='background-color: #f8f9fa;'>
                                <td style='font-weight: bold;'>GST Amount:</td>
                                <td>&#8377;" . number_format($po['po_gst_amount'], 2) . "</td>
                            </tr>
                            <tr>
                                <td style='font-weight: bold;'>Other Charges & Taxes:</td>
                                <td>&#8377;" . number_format($po['po_other_charges_and_taxes'], 2) . "</td>
                            </tr>
                            <tr style='background-color: #e9ecef;'>
                                <td style='font-weight: bold;'>PO Total (Inc GST & All Charges):</td>
                                <td style='font-weight: bold; color: #28a745;'>&#8377;" . number_format($po['po_po_total'], 2) . "</td>
                            </tr>
                            " . (!empty($po['po_supporting_documents']) ? "
                            <tr style='background-color: #f8f9fa;'>
                                <td style='font-weight: bold;'>Supporting Documents:</td>
                                <td><a href='" . base_url("assets/purchase_orders/{$po['po_supporting_documents']}") . "' target='_blank' style='color: #007bff; text-decoration: underline;'> View Document</a></td>
                            </tr>" : "") . "
                        </table>
                    </div>
                    
                    <div class='warning'>
                        <strong>⚠️ Important:</strong> Please review all details carefully before making your decision. 
                        This action cannot be undone.
                    </div>
                    
                    <div class='action-buttons'>
                        <a href='" . base_url("accounts/approve_po/{$token}/Approved") . "' class='btn btn-approve' onclick='return confirm(\"Are you sure you want to APPROVE this Purchase Order?\")'>
                            ✓ Approve Purchase Order
                        </a>
                        <a href='" . base_url("accounts/approve_po/{$token}/Rejected") . "' class='btn btn-reject' onclick='return confirm(\"Are you sure you want to REJECT this Purchase Order?\")'>
                            ✗ Reject Purchase Order
                        </a>
                        <a href='" . base_url('/') . "' class='btn btn-cancel'>
                            Cancel
                        </a>
                    </div>
                </div>
            </body>
            </html>";
            
        } catch (Exception $e) {
            log_message('error', 'PO Review Failed: ' . $e->getMessage());
            
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            echo "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset=\"UTF-8\">
                <title>Review Error</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                    .error-box { 
                        border: 2px solid #dc3545; 
                        border-radius: 10px; 
                        padding: 30px; 
                        margin: 20px auto; 
                        max-width: 500px;
                        background-color: #f9f9fa;
                    }
                    .error-icon { font-size: 48px; color: #dc3545; }
                    .error-text { color: #dc3545; font-weight: bold; font-size: 24px; }
                    .back-link { margin-top: 20px; }
                    .back-link a { 
                        color: #007bff; 
                        text-decoration: none; 
                        padding: 10px 20px; 
                        border: 1px solid #007bff; 
                        border-radius: 5px;
                    }
                    .back-link a:hover { background-color: #007bff; color: white; }
                </style>
            </head>
            <body>
                <div class='error-box'>
                    <div class='error-icon'>⚠</div>
                    <div class='error-text'>Review Error</div>
                    <p>{$e->getMessage()}</p>
                    <div class='back-link'>
                        <a href='" . base_url('/') . "'>Back to Home Page</a>
                    </div>
                </div>
            </body>
            </html>";
        }
    }
    
    public function purchase_order_list()
	{
		$this->load->model('Purchase_order_model');
		$filters = [
			'status'                  => $this->input->get('status'),
			'start_date'              => $this->input->get('start_date'),
			'end_date'                => $this->input->get('end_date'),
			'po_centre'               => $this->input->get('po_centre'),
			'po_department'           => $this->input->get('po_department'),
			'po_nature_of_expenditure'=> $this->input->get('po_nature_of_expenditure'),
			'approval_status'         => $this->input->get('approval_status'),
		];
		$per_page = $this->input->get('per_page', true) ?: 0;
		$config["base_url"] = base_url("accounts/purchase-orders-list");
		$config["total_rows"] = $this->Purchase_order_model->purchase_order_count($filters);
		$config["per_page"] = 20;
		$config["page_query_string"] = true;
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
		$data['purchase_order_result'] = $this->Purchase_order_model->purchase_order_pagination($config["per_page"], $per_page, $filters);
		$data['filters'] = $filters; // send back to view
		$template = get_header_template(checklogin()['role']);
		$data['user_role'] =checklogin()['role'];
		$this->load->view($template['header']);
		$this->load->view('accounts/purchase_order_list', $data);
		$this->load->view($template['footer']);
	}

	public function update_status()
	{
		$this->load->model('Purchase_order_model');
		$id     = $this->input->post('id');
		$status = $this->input->post('status');
		if (empty($id) || !in_array($status, ['0', '1'])) {
			return $this->output->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request!']));
		}
		
		$updated = $this->Purchase_order_model->update_status($id, $status);
		if ($updated) {
			$message = 'Purchase Order status updated successfully!';
		} else {
			$message = 'Failed to update status. Please try again.';
		}
		return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(['status' => $updated ? 'success' : 'error', 'message' => $message]));
	}

	public function purchase_order_payment($po_number)
	{
		$this->load->model('Purchase_order_model');
		$purchase_order = $this->Purchase_order_model->get_purchase_order_by_id($po_number);
		$data['purchase_order'] = $purchase_order;
		$template = get_header_template(checklogin()['role']);
		$this->load->view($template['header']);
		$this->load->view('accounts/purchase_order_payment', $data);
		$this->load->view($template['footer']);
	}

	public function save_payment_purchase_order()
	{
		$po_number   = $this->input->post('po_number');
		$amount_paid = $this->input->post('amount_paid');
		$user_id     = $this->session->userdata['logged_administrator']['employee_number'];
		$purchase_order = $this->Purchase_order_model->get_purchase_order_by_id($po_number);
		if (!$purchase_order) {
			$this->session->set_flashdata('error', 'Invalid Purchase Order Number');
			redirect('accounts/purchase-orders-list');
			return;
		}
		// Validate amount paid
		if ($amount_paid <= 0) {
			$this->session->set_flashdata('error', 'Amount Paid must be greater than 0!');
			redirect('accounts/purchase-orders-list');
			return;
		}
		
		// Allow overpayments but warn if excessive
		if ($amount_paid > $purchase_order['po_po_total'] * 1.5) {
			$this->session->set_flashdata('error', 'Overpayment amount is too high (max 50% over PO total)!');
			redirect('accounts/purchase-orders-list');
			return;
		}
		$uploaded_file = null;
		if (!empty($_FILES['payment_proof']['name'])) {
			// Use the configured upload path from config
			$dest_path = $this->config->item('upload_path');
			$config['upload_path']   = $dest_path . 'purchase_orders/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png|webp|gif|bmp';
			$config['max_size']      = 10240; // 10 MB
			$config['file_ext_tolower'] = TRUE;
			$config['remove_spaces'] = TRUE;
			$config['overwrite']     = FALSE;
			
			// Create directory if it doesn't exist
			if (!is_dir($config['upload_path'])) {
				mkdir($config['upload_path'], 0777, true);
			}
			
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('payment_proof')) {
				$error = $this->upload->display_errors('', '');
				// Log the error for debugging
				log_message('error', 'File upload failed: ' . $error . ' for file: ' . $_FILES['payment_proof']['name']);
				$this->session->set_flashdata('error', 'File upload failed: ' . $error);
				redirect('accounts/purchase-orders-list');
				return;
			}
			
			$upload_data   = $this->upload->data();
			$uploaded_file = 'purchase_orders/' . $upload_data['file_name'];
		}
		// Calculate balance and payment status
		$balance = $purchase_order['po_po_total'] - $amount_paid;
		$payment_status = '';
		
		if ($balance < 0) {
			$payment_status = 'overpaid';
		} elseif ($balance == 0) {
			$payment_status = 'fully_paid';
		} else {
			$payment_status = 'partial';
		}
		
		$payment_data = [
			'po_number'       => $po_number,
			'user_id'         => $user_id,
			'amount_paid'     => $amount_paid,
			'balance'  => $balance,
			'payment_status'  => $payment_status,
			'transaction_img' => $uploaded_file,
		];
		$this->Purchase_order_model->save_purchase_order_payment($payment_data);
		$this->Purchase_order_model->update_purchase_order($po_number, $amount_paid);
		// Create appropriate success message based on payment status
		if ($balance < 0) {
			$message = 'Payment saved successfully! Overpayment of &#8377;' . number_format(abs($balance), 2) . ' recorded as credit.';
		} elseif ($balance == 0) {
			$message = 'Payment saved successfully! Purchase Order is now fully paid.';
		} else {
			$message = 'Payment saved successfully! Remaining balance: &#8377;' . number_format($balance, 2);
		}
		
		// Set flash message instead of URL parameters
		$this->session->set_flashdata('success', $message);
		redirect('accounts/purchase-orders-list');
	}



} 