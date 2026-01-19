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
		$this->load->model('procedures_model');
		$this->load->model('Purchase_order_model');
		$this->load->model('billingmodel_model');
		$this->load->model('center_model');
		$this->load->model('stock_model');
		$this->load->helper('myhelper');
		$this->load->library("pagination");
		
	}	
	
		function htmltopdf(){
        	$formname = $_POST['formname'];
            $paitent_id = $_POST['iic_id'];
            $paitent_html = $_POST['html'];
            
            $dest_path = $this->config->item('upload_path');
            $destination = $dest_path.'whatsapp-pdf/';
            $NewImageName = $formname."-".$paitent_id;
            
            require_once $dest_path. '/mpdf/vendor/autoload.php';
            $filename= $dest_path."whatsapp-pdf/".$NewImageName.".pdf";
			//$filename= "https://indiaivf.website/assets/whatsapp-pdf/".$NewImageName.".pdf";
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($paitent_html);
            $mpdf->Output($filename,'F');
    		
    		$patient_data = get_patient_detail($paitent_id);
    		if($patient_data['whats_registers'] == 0){
    		    $register_patient = whatsappregister($patient_data['wife_phone']);
    		    if($register_patient > 0){
    		        $this->db->where('patient_id', $paitent_id);
    		        $this->db->update('hms_patients', array('whats_registers' => 1));
    		    }
    		}
    		$sendprep = whatsappfileprep($patient_data['wife_phone'], $filename);
    	    if($sendprep > 0){
    	        echo json_encode(array('status' => 1));
    	        die;
    	    }else{
    	        echo json_encode(array('status' => 0));
    	        die;
    	    }	
        }
        
    function billhtmltopdf(){
		    $receipt_number = $_POST['receipt_number'];
            $paitent_id = $_POST['iic_id'];
            $paitent_html = $_POST['html'];
			$dateTime = date("Ymd-His");
            
            $dest_path = $this->config->item('upload_path');
            $destination = $dest_path.'whatsapp-pdf/';
            $NewImageName = "Billing-" . $paitent_id . "-" . $dateTime;
            
            require_once $dest_path. '/mpdf/vendor/autoload.php';
            $filename= $dest_path."whatsapp-pdf/".$NewImageName.".pdf";
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($paitent_html);
            $mpdf->Output($filename,'F');
    		
    		$patient_data = get_patient_detail($paitent_id);
    		if($patient_data['whats_registers'] == 0){
    		    $register_patient = whatsappregister($patient_data['wife_phone']);
    		    if($register_patient > 0){
    		        $this->db->where('patient_id', $paitent_id);
    		        $this->db->update('hms_patients', array('whats_registers' => 1));
    		    }
    		}
    		$sendprep = whatsappfileprep($patient_data['wife_phone'], $filename, 'Billing Receipt', 'india_ivf_bill_sent');
    	    if($sendprep > 0){
    	        echo json_encode(array('status' => 1));
    	        die;
    	    }else{
    	        echo json_encode(array('status' => 0));
    	        die;
    	    }	
        }
        
    function prephtmltopdf(){
        $paitent_id = $_POST['iic_id'];
        $paitent_html = $_POST['html'];
        
        $dest_path = $this->config->item('upload_path');
        $destination = $dest_path.'whatsapp-pdf/';
        $NewImageName = "Billing-".$paitent_id;
        
        require_once $dest_path. '/mpdf/vendor/autoload.php';
        $filename= $dest_path."whatsapp-pdf/".$NewImageName.".pdf";
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($paitent_html);
        $mpdf->Output($filename,'F');
		
		$patient_data = get_patient_detail($paitent_id);
    
		if($patient_data['whats_registers'] == 0){
		    $register_patient = whatsappregister($patient_data['wife_phone']);
		    if($register_patient > 0){
		        $this->db->where('patient_id', $paitent_id);
		        $this->db->update('hms_patients', array('whats_registers' => 1));
		    }else{
		        echo json_encode(array('status' => 0, 'message' => $register_patient['message']));
	            die;
		    }
		}
		$sendprep = whatsappfileprep($patient_data['wife_phone'], $filename, 'Prescription', 'prescription_sent');
	    if($sendprep > 0){
	        echo json_encode(array('status' => 1, 'message' => "Prescription has been sent!"));
	        die;
	    }else{
	        echo json_encode(array('status' => 0, 'message' => $sendprep['message']));
	        die;
	    }	
    }

	public function accounts()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/accounts');
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function freezing()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/freezing');
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function get_patient_data(){
		$search_this = $_POST['data']['search_this'];
		$search_by = $_POST['data']['search_by'];
		if($search_this == ''){
			$response = array();
			$response = array('status' => 0, 'data'=> 'Phone number/IIC ID is required');
			echo json_encode($response);
			die;	
		}
		$consultation_result = $investigate_result = $procedure_result = $procedure_can_result = $patient_result = $medicine_result = $sales_result = $refund_amount_result = array();		
	   $data = $this->accounts_model->get_patient_data_by($search_this, $search_by);
	   	$medicine_result = $data['medicine_result'];
		$sales_result = $data['sales_result'];
	if(!empty($data)){
		$patient_id = $data['patient_result']['patient_id'];
		$patient_data = get_patient_detail($patient_id);
		$currency = '';
		$consultation_result = $data['consultation_result'];
		$investigate_result = $data['investigate_result'];
		$procedure_result = $data['procedure_result'];
		$procedure_can_result = $data['procedure_can_result'];
		$patient_result = $data['patient_result'];
		$medicine_result = $data['medicine_result'];
		$sales_result = $data['sales_result'];
		$refund_amount_result = $data['refund_amount_result'];
		$payments = $data['payments'];	
		$response = array();
		if (!empty($patient_result))
        {
			$html = $payment_html = '';		
			if(count($consultation_result) > 0){
				$type = $consultation_result['type'];
				foreach($consultation_result['data'] as $key => $val){
					$html .= '<tr>';
					$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=consultation">'.$val['receipt_number'].'</a></td>';
					$html .= '<td>'.dateformat($val['on_date']).'</td>';
					$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
					if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
					else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}	
                    $html .= '<td>'.$this->get_employee_name($val['biller_id']).'</td>';					
					$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
					$html .= '<td>'.$currency.$val['fees'].'</td>';
					$html .= '<td>'.$currency.$val['payment_done'].'</td>';
					$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
					$html .= '<td>Consultation</td>';
					$html .= '<td>'.ucwords($val['status']).'</td>';
					$html .= '</tr>';
				}
			}	

			if(count($investigate_result) > 0){
				$type = $investigate_result['type'];
				foreach($investigate_result['data'] as $key => $val){
					$html .= '<tr>';
					$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=investigation">'.$val['receipt_number'].'</a></td>';
					$html .= '<td>'.dateformat($val['on_date']).'</td>';
					$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
					if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
					else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}	
					$html .= '<td>'.$this->get_employee_name($val['biller_id']).'</td>';
					$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
					$html .= '<td>'.$currency.$val['fees'].'</td>';
					$html .= '<td>'.$currency.$val['payment_done'].'</td>';
					$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
					$html .= '<td>Investigation</td>';
					$html .= '<td>'.ucwords($val['status']).'</td>';
					$html .= '</tr>';
				}
			}

			if(count($procedure_result) > 0){
				$type = $procedure_result['type'];
				foreach($procedure_result['data'] as $key => $val){					 		
					$html .= '<tr>';
					$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=procedure">'.$val['receipt_number'].'</a><a class="btn btn-large" target="_blank" href="'.base_url().'accounts/partial_procedure_billing/'.$val['receipt_number'].'?t=procedure">'.'Add Payments'.'</a></td>';
					$html .= '<td>'.dateformat($val['on_date']).'</td>';
					$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
					if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
					else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}	
					$html .= '<td>'.$this->get_employee_name($val['biller_id']).'</td>';
					$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
					$html .= '<td>'.$currency.$val['fees'].'</td>';
					$html .= '<td>'.$currency.$val['payment_done'].'</td>';
					$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
					$html .= '<td>Procedure</td>';
					$html .= '<td>'.ucwords($val['status']).'</td>';
					if(!empty($val['data'])){
					  $procedure_data = unserialize($val['data']);
					  foreach ($procedure_data['patient_procedures'] as $v2_data){
						    $sql1 = "select * from hms_procedures where code='".$v2_data['sub_procedures_code']."'";
                            $query = $this->db->query($sql1);
                            $select_result1 = $query->result(); 
					foreach ($select_result1 as $res_val){
					$html .='<td>'.$res_val->procedure_name.','.$v2_data['sub_procedures_paid_price'].'</td>';
					}}
					 }
					$html .= '</tr>';
				}
			}

			if (!empty($procedure_can_result) && is_array($procedure_can_result) && count($procedure_can_result) > 0) {
				$type = $procedure_can_result['type'];
				foreach($procedure_can_result['data'] as $key => $val){					 		
					$html .= '<tr>';
					$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=procedure">'.$val['receipt_number'].'</a><a class="btn btn-large" target="_blank" href="'.base_url().'accounts/partial_procedure_billing/'.$val['receipt_number'].'?t=procedure">'.$val['cn_invoice'].'</a></td>';
					$html .= '<td>'.dateformat($val['modified_on']).'</td>';
					$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
					if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
					else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}	
					$html .= '<td>'.$this->get_employee_name($val['biller_id']).'</td>';
					$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
					$html .= '<td>'.$currency.$val['fees'].'</td>';
					$html .= '<td>'.$currency.$val['payment_done'].'</td>';
					$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
					$html .= '<td>Procedure</td>';
					$html .= '<td>Credit Notes</td>';
					if(!empty($val['data'])){
					  $procedure_data = unserialize($val['data']);
					  foreach ($procedure_data['patient_procedures'] as $v2_data){
						    $sql1 = "select * from hms_procedures where code='".$v2_data['sub_procedures_code']."'";
                            $query = $this->db->query($sql1);
                            $select_result1 = $query->result(); 
					foreach ($select_result1 as $res_val){
					$html .='<td>'.$res_val->procedure_name.','.$v2_data['sub_procedures_paid_price'].'</td>';
					}}
					 }
					$html .= '</tr>';
				}
			}
			
			if(count($medicine_result) > 0){
				$type = $medicine_result['type'];
				foreach($medicine_result['data'] as $key => $val){
					$html .= '<tr>';
					$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=medicine">'.$val['receipt_number'].'</a></td>';
					$html .= '<td>'.dateformat($val['on_date']).'</td>';
					$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
					if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
					else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}
					$html .= '<td>'.$this->get_employee_name($val['biller_id']).'</td>';
					$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
					$html .= '<td>'.$currency.$val['fees'].'</td>';
					$html .= '<td>'.$currency.$val['payment_done'].'</td>';
					$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
					$html .= '<td>Medicine</td>';
					$html .= '<td>'.ucwords($val['status']).'</td>';
					$html .= '</tr>';
				}
			}

			if(count($sales_result) > 0){
				$type = $sales_result['type'];
				foreach($sales_result['data'] as $key => $val){
					$html .= '<tr>';
					$html .= '<td><a class="btn btn-large" href="'.base_url().'stock/sales/view/'.$val->id.'?t=sales">SALE-'.$val->id.'</a></td>';
					$html .= '<td>'.dateformat($val->sale_date).'</td>';
					$html .= '<td>'.$val->center_name.'</td>';
					$html .= '<td>'.$val->center_name.'</td>';
					$html .= '<td>'.$val->salesperson_name.'</td>';
					$html .= '<td>'.$currency.$val->subtotal.'</td>';
					$html .= '<td>'.$currency.$val->total_amount.'</td>';
					$html .= '<td>'.$currency.$val->total_amount.'</td>';
					$html .= '<td>'.$currency.'0.00</td>';
					$html .= '<td>Medicine</td>';
					$html .= '<td>'.ucwords($val->status).'</td>';
					$html .= '</tr>';
				}
			}

			if(count($refund_amount_result) > 0){
				$type = $refund_amount_result['type'];
				foreach($refund_amount_result['data'] as $key => $val){	
					$html .= '<tr>';
					$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=medicine">'.$val['receipt_number'].'</a></td>';
					$html .= '<td>'.dateformat($val['on_date']).'</td>';
					$html .= '<td>Consultation Fee<br/>'.$currency.$val['consultation_fee'].'</td>';
					$html .= '<td>USG Scan Charge<br/>'.$currency.$val['usg_scan_charge'].'</td>';
					$html .= '<td>Consumable Charges<br/>'.$currency.$val['consumable_charges'].'</td>';
					$html .= '<td>File Registation Charge<br/>'.$currency.$val['file_registation_charge'].'</td>';
					$html .= '<td>Refund Amount<br/>'.$currency.$val['refund_amount'].'</td>';
					$html .= '<td>Refund Amount</td>';
					if($val['status'] == 1){ $html .= '<td>Approved</td>'; }
					else{$html .= '<td>Pending</td>';}	
					$html .= '</tr>';
				}
			}

			if(count($payments) > 0){
				$type = $payments['type'];
				foreach($payments['data'] as $key => $val){ //var_dump($val);die;
					$payment_html .= '<tr>';
					$payment_html .= '<td><a target="_blank" class="btn btn-large" href="'.base_url().'accounts/details/'.$val['billing_id'].'?t='.$val['type'].'">'.$val['billing_id'].'</a></td>';
					$payment_html .= '<td><a target="_blank" href="'.base_url().'partial-payment-receipt/'.$val['refrence_number'].'">'.$val['refrence_number'].'</a></td>';
					$payment_html .= '<td><a target="_blank" href="'.base_url().'accounts/patient_details/'.$patient_id.'">'.$patient_id.'</a></td>';
					$payment_html .= '<td>'.$patient_data['wife_name'].'</td>';
					$payment_html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
					if($val['billing_from'] == 'IndiaIVF'){ $payment_html .= '<td>'.$val['billing_from'].'</td>'; }
					else{$payment_html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}	
					$payment_html .= '<td>'.$this->get_employee_name($val['employee_number']).'</td>';
					$payment_html .= '<td>'.$currency.$val['payment_done'].'</td>';
					$payment_html .= '<td>'.$val['payment_method'].'</td>';
					$payment_html .= '<td>'.$val['transaction_id'];
					if(!empty($val['transaction_img'])){
					  $payment_html .= '(<a href="'.$val['transaction_img'].'" class="hide_print"  target="_blank">Transaction Image</a>) </td>';
					}else{
					    $payment_html .= '</td>';
					}
					
					$payment_html .= '<td>'.$val['on_date'].'</td>';
					if($val['status'] == 1){ $payment_html .= '<td>Approved</td>'; }
					else if($val['status'] == 2){ $payment_html .= '<td>Disapproved</td>'; }
					else if($val['status'] == 3){ $payment_html .= '<td>Cancel</td>'; }
					else{$payment_html .= '<td>Pending</td>';}	
                    if (!empty($val['data'])) {
						$procedure_data = unserialize($val['data']);
						if (isset($procedure_data['patient_procedures'])) {
						foreach ($procedure_data['patient_procedures'] as $v2_data) {
						// Assuming you want to access the "sub_procedure" key
						$code = $v2_data['sub_procedure']; // Corrected key name
						$payment_html .= '<td>' . $code . '</td>';
						}
					}
					}					
					$payment_html .= '</tr>';
				}
			}
			
			$response = array('data' => $html,'current_balance' => patient_balance($patient_id), 'patient_name'=> $patient_result['wife_name'], 'husband_name'=> $patient_result['husband_name'], 'patient_phone'=> sting_masking($patient_result['wife_phone']), 'payment_html' => $payment_html);
			echo json_encode($response);
			die;
        }else{
			$response = array('data' => 'No record found!', 'patient_name'=> '', 'patient_email'=> '', 'patient_phone'=> '', 'payment_html' => '');
			echo json_encode($response);
			die;
		}
	}else{
		$response = array('data' => 'No record found!', 'patient_name'=> '', 'patient_email'=> '', 'patient_phone'=> '', 'payment_html' => '');
		echo json_encode($response);
		die;
	}

	}
	
	public function get_patient_freezing(){
		$search_this = $_POST['data']['search_this'];
		$search_by = $_POST['data']['search_by'];
		if($search_this == ''){
			$response = array();
			$response = array('status' => 0, 'data'=> 'Phone number/IIC ID is required');
			echo json_encode($response);
			die;	
		}
		$consultation_result = $freezing_result  = $investigate_result = $procedure_result = $patient_result = array();		
		
	$data = $this->accounts_model->get_patient_data_by($search_this, $search_by);
	
	
	if(!empty($data)){
		$patient_id = $data['patient_result']['patient_id'];
		$patient_data = get_patient_detail($patient_id);
		$currency = '';
	
		$procedure_result = $data['procedure_result'];
		$patient_result = $data['patient_result'];
		$investigate_result = $data['investigate_result'];
		$payments = $data['payments'];	
		
		$response = array();
		if (!empty($patient_result))
        {
			$html = $payment_html = '';		
			
		if(count($procedure_result) > 0){
			$type = $procedure_result['type'];
			foreach($procedure_result['data'] as $key => $val){
				if(!empty($val['data'])){
					$procedure_data = unserialize($val['data']);
					foreach ($procedure_data['patient_procedures'] as $v2_data){
						$sql1 = "select * from hms_procedures where code='".$v2_data['sub_procedures_code']."'";
                        $query = $this->db->query($sql1);
                        $select_result1 = $query->result(); 
					        foreach ($select_result1 as $res_val){
                                if($v2_data['sub_procedures_code'] == "IP03" ){								
									$html .= '<tr>';
									$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=procedure">'.$val['receipt_number'].'</a></td>';
									$html .= '<td>'.dateformat($val['on_date']).'</td>';
									$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
									if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
									else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}							
									$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
									$html .= '<td>'.$currency.$val['fees'].'</td>';
									$html .= '<td>'.$currency.$val['payment_done'].'</td>';
									$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
									$html .= '<td>Procedure</td>';
									
									$html .='<td>'.$res_val->procedure_name.','.$v2_data['sub_procedures_paid_price'].'</td>';
									
									$html .= '<td>'.ucwords($val['status']).'</td>';
									$html .= '</tr>';
				                }
								if($v2_data['sub_procedures_code'] == "IP14" ){								
									$html .= '<tr>';
									$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=procedure">'.$val['receipt_number'].'</a></td>';
									$html .= '<td>'.dateformat($val['on_date']).'</td>';
									$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
									if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
									else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}							
									$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
									$html .= '<td>'.$currency.$val['fees'].'</td>';
									$html .= '<td>'.$currency.$val['payment_done'].'</td>';
									$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
									$html .= '<td>Procedure</td>';
									
									$html .='<td>'.$res_val->procedure_name.','.$v2_data['sub_procedures_paid_price'].'</td>';
									
									$html .= '<td>'.ucwords($val['status']).'</td>';
									$html .= '</tr>';
								}
								if($v2_data['sub_procedures_code'] == "IP17" ){								
									$html .= '<tr>';
									$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=procedure">'.$val['receipt_number'].'</a></td>';
									$html .= '<td>'.dateformat($val['on_date']).'</td>';
									$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
									if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
									else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}							
									$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
									$html .= '<td>'.$currency.$val['fees'].'</td>';
									$html .= '<td>'.$currency.$val['payment_done'].'</td>';
									$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
									$html .= '<td>Procedure</td>';
									
									$html .='<td>'.$res_val->procedure_name.','.$v2_data['sub_procedures_paid_price'].'</td>';
									
									$html .= '<td>'.ucwords($val['status']).'</td>';
									$html .= '</tr>';
								}
								if($v2_data['sub_procedures_code'] == "IP18" ){								
									$html .= '<tr>';
									$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=procedure">'.$val['receipt_number'].'</a></td>';
									$html .= '<td>'.dateformat($val['on_date']).'</td>';
									$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
									if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
									else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}							
									$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
									$html .= '<td>'.$currency.$val['fees'].'</td>';
									$html .= '<td>'.$currency.$val['payment_done'].'</td>';
									$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
									$html .= '<td>Procedure</td>';
									
									$html .='<td>'.$res_val->procedure_name.','.$v2_data['sub_procedures_paid_price'].'</td>';
									
									$html .= '<td>'.ucwords($val['status']).'</td>';
									$html .= '</tr>';
								}
								if($v2_data['sub_procedures_code'] == "IP12" ){								
									$html .= '<tr>';
									$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=procedure">'.$val['receipt_number'].'</a></td>';
									$html .= '<td>'.dateformat($val['on_date']).'</td>';
									$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
									if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
									else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}							
									$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
									$html .= '<td>'.$currency.$val['fees'].'</td>';
									$html .= '<td>'.$currency.$val['payment_done'].'</td>';
									$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
									$html .= '<td>Procedure</td>';
									
									$html .='<td>'.$res_val->procedure_name.','.$v2_data['sub_procedures_paid_price'].'</td>';
									
									$html .= '<td>'.ucwords($val['status']).'</td>';
									$html .= '</tr>';
								}
								if($v2_data['sub_procedures_code'] == "IP96" ){								
									$html .= '<tr>';
									$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=procedure">'.$val['receipt_number'].'</a></td>';
									$html .= '<td>'.dateformat($val['on_date']).'</td>';
									$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
									if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
									else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}							
									$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
									$html .= '<td>'.$currency.$val['fees'].'</td>';
									$html .= '<td>'.$currency.$val['payment_done'].'</td>';
									$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
									$html .= '<td>Procedure</td>';
									
									$html .='<td>'.$res_val->procedure_name.','.$v2_data['sub_procedures_paid_price'].'</td>';
									
									$html .= '<td>'.ucwords($val['status']).'</td>';
									$html .= '</tr>';
								}
								if($v2_data['sub_procedures_code'] == "IP65" ){								
									$html .= '<tr>';
									$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=procedure">'.$val['receipt_number'].'</a></td>';
									$html .= '<td>'.dateformat($val['on_date']).'</td>';
									$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
									if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
									else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}							
									$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
									$html .= '<td>'.$currency.$val['fees'].'</td>';
									$html .= '<td>'.$currency.$val['payment_done'].'</td>';
									$html .= '<td>'.$currency.$val['remaining_amount'].'</td>';
									$html .= '<td>Procedure</td>';
									
									$html .='<td>'.$res_val->procedure_name.','.$v2_data['sub_procedures_paid_price'].'</td>';
									
									$html .= '<td>'.ucwords($val['status']).'</td>';
									$html .= '</tr>';
								}
							}
					    }
						
					}
					
					
				}
				
			}
			
			if(count($investigate_result) > 0){
				$sql2 = "select * from freezing where patient_id='".$data['patient_result']['patient_id']."'";
						   $query = $this->db->query($sql2);
                            $select_result2 = $query->result(); 
							 foreach ($select_result2 as $res_val2){
								 $html .= '<tr>';	
					
								$html .= '<td>'.$res_val2['freezing_date'].'</td>';
								$html .= '<td>'.$res_val2['expiry_date'].'</td>';
				$type = $investigate_result['type'];
				foreach($investigate_result['data'] as $key => $val){
					$
								// echo "<pre>";
								// print_r($res_val2);
					            // echo "</pre>";
								 //exit();
							 
					$html .= '<td><a class="btn btn-large" href="'.base_url().'accounts/details/'.$val['receipt_number'].'?t=investigation">'.$val['receipt_number'].'</a></td>';
					$html .= '<td>'.dateformat($val['on_date']).'</td>';
					$html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
					if($val['billing_from'] == 'IndiaIVF'){ $html .= '<td>'.$val['billing_from'].'</td>'; }
					else{$html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}							
					$html .= '<td>'.$currency.$val['totalpackage'].'</td>';
					$html .= '<td>'.$currency.$val['fees'].'</td>';
					$html .= '<td>'.$currency.$val['payment_done'].'</td>';
					$html .= '<td>'.$res_val2['freezing_date'].'</td>';
					
				}
				$html .= '<td>Investigation</td>';
				$html .= '<td>'.ucwords($val['status']).'</td>';
				$html .= '</tr>';
				}
			}

			if(count($payments) > 0){
				$type = $payments['type'];
				foreach($payments['data'] as $key => $val){ //var_dump($val);die;
					$payment_html .= '<tr>';
					$payment_html .= '<td><a target="_blank" class="btn btn-large" href="'.base_url().'accounts/details/'.$val['billing_id'].'?t='.$val['type'].'">'.$val['billing_id'].'</a></td>';
					$payment_html .= '<td><a target="_blank" href="'.base_url().'partial-payment-receipt/'.$val['refrence_number'].'">'.$val['refrence_number'].'</a></td>';
					$payment_html .= '<td><a target="_blank" href="'.base_url().'accounts/patient_details/'.$patient_id.'">'.$patient_id.'</a></td>';
					$payment_html .= '<td>'.$patient_data['wife_name'].'</td>';
					$payment_html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
					if($val['billing_from'] == 'IndiaIVF'){ $payment_html .= '<td>'.$val['billing_from'].'</td>'; }
					else{$payment_html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}							
					$payment_html .= '<td>'.$currency.$val['payment_done'].'</td>';
					$payment_html .= '<td>'.$val['payment_method'].'</td>';
					$payment_html .= '<td>'.$val['transaction_id'];
					if(!empty($val['transaction_img'])){
					  $payment_html .= '(<a href="'.$val['transaction_img'].'" class="hide_print"  target="_blank">Transaction Image</a>) </td>';
					}else{
					    $payment_html .= '</td>';
					}
					
					$payment_html .= '<td>'.$val['on_date'].'</td>';
					if($val['status'] == 1){ $payment_html .= '<td>Approved</td>'; }
					else if($val['status'] == 2){ $payment_html .= '<td>Disapproved</td>'; }
					else{$payment_html .= '<td>Pending</td>';}							
					$payment_html .= '</tr>';
				}
			}
			
			$response = array('data' => $html,'current_balance' => patient_balance($patient_id), 'patient_name'=> $patient_result['wife_name'], 'husband_name'=> $patient_result['husband_name'], 'patient_phone'=> sting_masking($patient_result['wife_phone']), 'payment_html' => $payment_html);
			echo json_encode($response);
			die;
        }else{
			$response = array('data' => 'No record found!', 'patient_name'=> '', 'patient_email'=> '', 'patient_phone'=> '', 'payment_html' => '');
			echo json_encode($response);
			die;
		}
	}else{
		$response = array('data' => 'No record found!', 'patient_name'=> '', 'patient_email'=> '', 'patient_phone'=> '', 'payment_html' => '');
		echo json_encode($response);
		die;
	}

	}

	public function freezing_renewal(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$iic_id = $this->input->get('iic_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/freezing_renewal";
        	$config["total_rows"] = $this->accounts_model->freezing_renewal_count($start_date, $end_date, $iic_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['freezing_result'] = $this->accounts_model->freezing_renewal_list_patination($config["per_page"], $per_page, $start_date, $end_date, $iic_id);
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["iic_id"] = $iic_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/freezing_renewal', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	

    public function investigation_patients(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$status = $this->input->get('status', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->accounts_model->export_investigation_data($start_date, $status, $end_date, $center, $patient_id, $payment_method);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Investigation-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type, Date, Status, Origins';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					
					$sql4 = "SELECT * FROM hms_centers WHERE center_number='" . $val['origins'] . "'";
					$select_result4 = run_select_query($sql4);
					
					$origin_from = $select_result4['center_name'];
					
					$billing_at = get_center_name($val['billing_at']);
					
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status'],$origin_from);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/investigation_patients";
        	$config["total_rows"] = $this->accounts_model->patient_investigation_count($center, $status, $start_date, $end_date, $patient_id, $payment_method);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->accounts_model->patient_investigation_list_patination($config["per_page"], $per_page, $center, $status, $start_date, $end_date, $patient_id, $payment_method);
			$data["billing_at"] = $center;
			$data["status"] = $status;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/investigation_patients', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}  	
	
	
	public function procedure_patients(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$status = $this->input->get('status', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->accounts_model->export_procedure_data($start_date, $end_date, $center, $patient_id, $payment_method, $biller_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Procedure-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'], $val['on_date'], $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/procedure_patients";
        	$config["total_rows"] = $this->accounts_model->patient_procedure_count($center, $status, $start_date, $end_date, $patient_id, $payment_method, $biller_id);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->patient_procedure_list_patination($config["per_page"], $per_page, $center, $status, $start_date, $end_date, $patient_id, $payment_method, $biller_id);
			$data["billing_at"] = $center;
			$data["status"] = $status;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/procedure_patients', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
	public function consultation_patients(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$status = $this->input->get('status', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->accounts_model->export_consultation_patients_data($start_date,$status, $end_date, $center, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=consultation-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/consultation_patients";
        	$config["total_rows"] = $this->accounts_model->patient_consultation_count($center,$status, $start_date, $end_date, $patient_id,$doctor_id = null);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['consultation_result'] = $this->accounts_model->patient_consultation_list_patination($config["per_page"], $per_page, $center,$start_date, $end_date, $patient_id, $status);
			$data["billing_at"] = $center;
			$data["status"] = $status;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/consultation_patients', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function registration_patients(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->accounts_model->export_registration_data($start_date, $end_date, $center, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Registration-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/registration_patients";
        	$config["total_rows"] = $this->accounts_model->patient_registration_count($center, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['registration_result'] = $this->accounts_model->patient_registration_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $status);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/registration_patients', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function partialpayments_request(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$status = $this->input->get('status', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->accounts_model->export_partialpayments_data($start_date, $end_date, $center, $patient_id, $status);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Partialpayments-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at,$val['procedure_name'] ,$val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					
					//$lead_arr = array($val['patient_id'], $val['wife_name'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/partialpayments_request";
        	$config["total_rows"] = $this->accounts_model->patient_partialpayments_count($center, $start_date, $end_date, $patient_id, $status, $payment_method);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['partialpayments_result'] = $this->accounts_model->patient_partialpayments_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $status, $payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["status"] = $status;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/partialpayments_request', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}


	   public function partialpayments_report(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$billing_id = $this->input->get('billing_id', true);
			$payment_method = $this->input->get('payment_method', true);
			$status = $this->input->get('status', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->accounts_model->export_partialpayments_report_data($start_date, $end_date, $center, $patient_id, $status);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Partialpaymentsreport-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Lead ID, Lead Source, IIC ID, Patient Name, Package ID, Receipt Number, Paid Amount, Payment Method, Billing At, Billing Type, Date, Payment Type, Center Name,Booking Date, Status,Origin';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$sql2 = "SELECT * FROM hms_patient_procedure WHERE receipt_number='" . $val['billing_id'] . "'";
					$select_result = run_select_query($sql2);
					
					$booking_date = $select_result['on_date'];
					
					$billing_at = get_center_name($val['billing_at']);
					
					$sql = "SELECT * FROM hms_appointments WHERE paitent_id='" . $val['patient_id'] . "'";
					$appoint_result = run_select_query($sql);

					$sql3 = "SELECT * FROM hms_appointments WHERE wife_phone='" . $appoint_result['wife_phone'] . "' and paitent_type='new_patient'";
					$select_result3 = run_select_query($sql3);

					$lead_id = $select_result3['crm_id'];
					$lead_source = $select_result3['lead_source'];
					
					$sql4 = "SELECT * FROM hms_centers WHERE center_number='" . $select_result3['appoitment_for'] . "'";
					$select_result4 = run_select_query($sql4);
					
					$lead_arr = array($lead_id, $lead_source, $val['patient_id'], $val['wife_name'], $val['billing_id'],$val['refrence_number'], $val['payment_done'], $val['payment_method'], $billing_at, $val['type'], date('Y-m-d H:i:s', strtotime($val['date'])),$val['billing_type'],$val['biller'],$booking_date,$val['status'],$select_result4['center_name']);
					
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/partialpayments_report";
        	$config["total_rows"] = $this->accounts_model->patient_partialpayments_count($center, $start_date, $end_date, $patient_id, $status, $payment_method);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['partialpayments_result'] = $this->accounts_model->patient_partialpayments_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $status,$payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["status"] = $status;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/partialpayments_report', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	} 
	
	
	
	
public function investigation_sales(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$payment_method = $this->input->get('payment_method', true);
			$export_billing = $this->input->get('export-billing', true);
			$paid_amount = 0;
			$discounted_package = 0;
			$total_package = 0;
			if (isset($export_billing)){
				$data = $this->accounts_model->export_investigation_data($start_date, $status, $end_date, $center, $patient_id, $payment_method);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Investigation-Sales-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name,Receipt Number, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type,Investigation, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$paid_amount = $paid_amount +  (int)$val['payment_done'];
					$total_package = $total_package +  (int)$val['totalpackage'];
					$discounted_package = $discounted_package +  (int)$val['discounted_package'];
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['receipt_number'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'],$val['investigation'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				$final_arr = array("", "", "", $total_package, $discounted_package, $paid_amount, "", "", "", "", "", "", "");
				fputcsv($fp, $final_arr);
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/investigation_sales";
        	$config["total_rows"] = $this->accounts_model->patient_investigation_count($center, $status, $start_date, $end_date, $patient_id, $payment_method);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->accounts_model->patient_investigation_list_patination($config["per_page"], $per_page, $center, $status, $start_date, $end_date, $patient_id, $payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/investigation_sales', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
	
	
	public function medicie_sales(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
		$center = $this->input->get('billing_at', true);
		$start_date = $this->input->get('start_date', true);
		$end_date = $this->input->get('end_date', true);
		$patient_id = $this->input->get('iic_id', true);
		$payment_method = $this->input->get('payment_method', true);
		$status = $this->input->get('status', true);
		$export_billing = $this->input->get('export-billing', true);
		
		$config = array();
    	$config["base_url"] = base_url() . "accounts/medicie_sales";
    	$config["total_rows"] = $this->accounts_model->patient_investigation_count($center, $status, $start_date, $end_date, $patient_id, $payment_method);
        	$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->accounts_model->patient_investigation_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/medicie_sales', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
		


public function procedure_reports(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$biller_id = $this->input->get('biller_id', true);
			$payment_method = $this->input->get('payment_method', true);
			$export_billing = $this->input->get('export-billing', true);
			$paid_amount = 0;
			$discounted_package = 0;
			$total_package = 0;
			if (isset($export_billing)){
				$data = $this->accounts_model->export_procedure_data($start_date, $end_date, $center, $patient_id, $payment_method, $biller_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Procedure-Reports-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name,Receipt Number, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At,Procedure Name, Type, Billing Type, Date, Status, Billing Source';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$paid_amount = $paid_amount +  (int)$val['payment_done'];
					$total_package = $total_package +  (int)$val['totalpackage'];
					$discounted_package = $discounted_package +  (int)$val['discounted_package'];
					$billing_at = get_center_name($val['billing_at']);
					$sql2 = "SELECT * FROM hms_procedures WHERE procedure_name='" . $val['procedure_name'] . "'";
					$select_result = run_select_query($sql2);
					$category = $select_result['category'];
					$lead_arr = array($val['patient_id'], $val['wife_name'],$val['receipt_number'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at,$val['procedure_name'], $category ,$val['billing_type'], $val['on_date'], $val['status'], $val['hospital_id']);
					fputcsv($fp, $lead_arr);
				}
				$final_arr = array("", "", "", $total_package, $discounted_package, $paid_amount, "", "", "", "", "", "", "");
				fputcsv($fp, $final_arr);
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/procedure_reports";
        	$config["total_rows"] = $this->accounts_model->procedure_reports_count($center, $start_date, $end_date, $patient_id, $payment_method, $biller_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->procedure_reports_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method, $biller_id);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$data["biller_id"] = $biller_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/procedure_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}


	public function procedure_origin(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$payment_method = $this->input->get('payment_method', true);
			$export_consumption = $this->input->get('export-consumption', true);
			$json_data = $this->input->get('json_data', true);
			
			if (isset($export_consumption)){
				$data = $this->accounts_model->export_consumption_medicine($center, $start_date, $end_date, $patient_id, $payment_method, $json_data);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Consumption-Report-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Pkg Booking Date,Pkg Cancel Date, Pkg Month,Pkg Booking Yr,FInancial Year, Receipt Number,IIC ID,Paient Name,Nationality,UHID, Procedures Code, Procedures Name, Category,Sub_Category1,Sub_Category2,Origin Booking Centre,Billing From Centre,billing_at,Lead Id,Lead Source,Agent, Procedures Price, Procedures Discount,Procedure Final Amount, Booking Amount,CN Invoice,Status,Apr 2024,May 2024,Jun 2024,Jul 2024,Aug 2024,Sep 2024,Oct 2024,Nov 2024,Dec 2024,Jan 2025,Feb 2025,Mar 2025,Apr 2025,May 2025,Jun 2025,Jul 2025,Aug 2025,Sep 2025,Oct 2025,Nov 2025,Dec 2025,Jan 2026,Feb 2026,Mar 2026,Apr 2026,May 2026,Jun 2026,Jul 2026,Aug 2026,Sep 2026,Oct 2026,Nov 2026,Dec 2026,Jan 2027,Feb 2027,Mar 2027';
				//Add the headers
				
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}

					$billing_at = $val['billing_at'];
					if($billing_at != "IndiaIVF"){
						$billing_at = get_center_name($billing_at);
					}
					
					$Origin_Booking_Centre = $val['origins'];
					if($Origin_Booking_Centre != "IndiaIVF"){
						$Origin_Booking_Centre = get_center_name($Origin_Booking_Centre);
					}
					
					$employee_number = get_center_name($val['employee_number']);
					
					$customMonths = [
					1 => 'Apr',
					2 => 'May',
					3 => 'June',
					4 => 'July',
					5 => 'August',
					6 => 'Sep',
					7 => 'Oct',
					8 => 'nov',
					9 => 'dec',
					10 => 'jan',
					11 => 'feb',
					12 => 'mar'
				];

				// Get current standard month number (1-12)
				$standardMonth = date('n', strtotime($val['on_date']));

				// Convert to your custom number (April=1 to March=12)
				if ($standardMonth >= 4) {
					$customMonthNumber = $standardMonth - 3; // April(4)=1, May(5)=2,...December(12)=9
				} else {
					$customMonthNumber = $standardMonth + 9; // January(1)=10, February(2)=11, March(3)=12
				}
				
				// Prepare common variables
				$formatted_date = date('Y-m-d', strtotime($val['on_date']));
				$modifiedon = date('Y-m-d', strtotime($val['modified_on']));
				$pkg_month = date("$customMonthNumber.F.y", strtotime($val['on_date']));
				$pkg_booking_year = date("Y", strtotime($val['on_date']));
				$date = strtotime($val['on_date']);
				$year = date("Y", $date);
				$month = date("n", $date);

				$financial_year = ($month >= 4) ? $year . '-' . ($year + 1) : ($year - 1) . '-' . $year;
				
					$sql = "SELECT * FROM hms_appointments WHERE paitent_id='" . $val['patient_id'] . "' and paitent_type='new_patient'";
					$select_result = run_select_query($sql);
					
					//$sql3 = "SELECT * FROM hms_appointments WHERE wife_phone='" . $select_result['wife_phone'] . "' and paitent_type='new_patient'";
					//$select_result3 = run_select_query($sql3);
					
					$sql_patients = "SELECT * FROM hms_patients WHERE patient_id='" . $val['patient_id'] . "'";
					$select_patients_result = run_select_query($sql_patients);
					
					$sql_embryo_transfer = "SELECT * FROM embryo_transfer_discharge_summary WHERE iic_id='" . $val['patient_id'] . "' order by ID ASC";
					$select_embryo_transfer = run_select_query($sql_embryo_transfer);

					$date_of_admission = $select_embryo_transfer['date_of_addmission'];
					$formatted_admission_date = date('Y-m-d', strtotime($date_of_admission));
					$formatted_on_date = date('Y-m-d', strtotime($val['on_date']));

					$type = 'New';
					if (strtotime($date_of_admission) > strtotime($val['on_date'])) {
						$type = 'recycle';
					}
										
					$names = [];
					if (!empty($select_patients_result['wife_name'])) {
						$names[] = $select_patients_result['wife_name'];
					}
					if (!empty($select_patients_result['husband_name'])) {
						$names[] = $select_patients_result['husband_name'];
					}
					$patient_name = implode(' & ', $names);
					
					$nationality = $select_patients_result['nationality'];
					
					$sql4 = "SELECT * FROM hms_centers WHERE center_number='" . $select_result3['appoitment_for'] . "'";
					$select_result4 = run_select_query($sql4);
					
					$uhid = $select_result4['center_code']."/".$select_result3['uhid'];
					
					$sql2 = "SELECT * FROM hms_procedures WHERE ID='" . $val['sub_procedure'] . "'";
					$select_result2 = run_select_query($sql2);
					$name = $select_result2['procedure_name'];
					$category = $select_result2['category'];
					$lead_id = $select_result['crm_id'];
					$agent = $select_result['agent'];
					$lead_source = $select_result['lead_source'];
					
					// Assuming you have an array of records with the same patient_id, appointment_id, and billing date
			$records = [
				['patient_id' => $val['patient_id'], 'appointment_id' => $val['appointment_id'], 'date' => $formatted_date, 'category' => 'IVF with Bed'],
				['patient_id' => $val['patient_id'], 'appointment_id' => $val['appointment_id'], 'date' => $formatted_date, 'category' => 'IVF with Bed'],
				['patient_id' => $val['patient_id'], 'appointment_id' => $val['appointment_id'], 'date' => $formatted_date, 'category' => 'Non IVF without Bed'],
				// ... more records
			];

			// Get all categories for this group
			$categories = array_column($records, 'category');

			// Count occurrences of each category
			$categoryCounts = array_count_values($categories);

			// Determine the package based on your business rules
			if (isset($categoryCounts['IVF with Bed']) && isset($categoryCounts['Non IVF without Bed'])) {
				// Case 1: Both IVF with Bed and Non IVF without Bed exist
				$package = 'Package IVF Bed Inc Add-on';
			} elseif (isset($categoryCounts['IVF with Bed']) && $categoryCounts['IVF with Bed'] > 0) {
				// Case 2: Only IVF with Bed exists
				$package = 'Package IVF with Bed Only';
			} elseif (isset($categoryCounts['Non IVF without Bed']) && $categoryCounts['Non IVF without Bed'] > 0) {
				// Case 3: Only Non IVF without Bed exists
				$package = 'Package of Add-ons Sold Separately';
			} else {
				// Default case
				$package = 'Other Package';
			}

			// Apply the package to all records in this group
			foreach ($records as &$record) {
				$record['package'] = $package;
			}

					$sql5 = "SELECT 
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2024-04' THEN payment_done ELSE 0 END) AS payment_2024_apr,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2024-05' THEN payment_done ELSE 0 END) AS payment_2024_may,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2024-06' THEN payment_done ELSE 0 END) AS payment_2024_jun,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2024-07' THEN payment_done ELSE 0 END) AS payment_2024_jul,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2024-08' THEN payment_done ELSE 0 END) AS payment_2024_aug,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2024-09' THEN payment_done ELSE 0 END) AS payment_2024_sep,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2024-10' THEN payment_done ELSE 0 END) AS payment_2024_oct,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2024-11' THEN payment_done ELSE 0 END) AS payment_2024_nov,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2024-12' THEN payment_done ELSE 0 END) AS payment_2024_dec,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-01' THEN payment_done ELSE 0 END) AS payment_2025_jan,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-02' THEN payment_done ELSE 0 END) AS payment_2025_feb,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-03' THEN payment_done ELSE 0 END) AS payment_2025_mar,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-04' THEN payment_done ELSE 0 END) AS payment_2025_apr,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-05' THEN payment_done ELSE 0 END) AS payment_2025_may,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-06' THEN payment_done ELSE 0 END) AS payment_2025_jun,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-07' THEN payment_done ELSE 0 END) AS payment_2025_jul,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-08' THEN payment_done ELSE 0 END) AS payment_2025_aug,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-09' THEN payment_done ELSE 0 END) AS payment_2025_sep,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-10' THEN payment_done ELSE 0 END) AS payment_2025_oct,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-11' THEN payment_done ELSE 0 END) AS payment_2025_nov,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2025-12' THEN payment_done ELSE 0 END) AS payment_2025_dec,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-01' THEN payment_done ELSE 0 END) AS payment_2026_jan,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-02' THEN payment_done ELSE 0 END) AS payment_2026_feb,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-03' THEN payment_done ELSE 0 END) AS payment_2026_mar,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-04' THEN payment_done ELSE 0 END) AS payment_2026_apr,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-05' THEN payment_done ELSE 0 END) AS payment_2026_may,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-06' THEN payment_done ELSE 0 END) AS payment_2026_jun,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-07' THEN payment_done ELSE 0 END) AS payment_2026_jul,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-08' THEN payment_done ELSE 0 END) AS payment_2026_aug,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-09' THEN payment_done ELSE 0 END) AS payment_2026_sep,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-10' THEN payment_done ELSE 0 END) AS payment_2026_oct,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-11' THEN payment_done ELSE 0 END) AS payment_2026_nov,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2026-12' THEN payment_done ELSE 0 END) AS payment_2026_dec,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2027-01' THEN payment_done ELSE 0 END) AS payment_2027_jan,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2027-02' THEN payment_done ELSE 0 END) AS payment_2027_feb,
			SUM(CASE WHEN DATE_FORMAT(on_date, '%Y-%m') = '2027-03' THEN payment_done ELSE 0 END) AS payment_2027_mar
			FROM hms_patient_payments WHERE billing_id = '" . $val['receipt_number'] . "' AND status = '1'";	
			$select_result5 = run_select_query($sql5);
			
			$total = number_format($val['sub_procedures_price'] - $val['sub_procedures_discount'], 2);
			
    				$lead_arr = array($formatted_date, $val['modified_on'], $pkg_month, $pkg_booking_year, $financial_year, $val['receipt_number'], $val['patient_id'], $patient_name,$nationality, $uhid, $val['sub_procedures_code'], $name, $category, $type,$package, $Origin_Booking_Centre,$billing_from,$billing_at, $lead_id,$lead_source,$agent, $val['sub_procedures_price'], $val['sub_procedures_discount'],$total, $val['sub_procedures_paid_price'], $val['cn_invoice'], $val['status'], $select_result5['payment_2024_apr'],$select_result5['payment_2024_may'],$select_result5['payment_2024_jun'],$select_result5['payment_2024_jul'],$select_result5['payment_2024_aug'],$select_result5['payment_2024_sep'],$select_result5['payment_2024_oct'],$select_result5['payment_2024_nov'],$select_result5['payment_2024_dec'],$select_result5['payment_2025_jan'],$select_result5['payment_2025_feb'],$select_result5['payment_2025_mar'],
					$select_result5['payment_2025_apr'],$select_result5['payment_2025_may'],$select_result5['payment_2025_jun'],$select_result5['payment_2025_jul'],$select_result5['payment_2025_aug'],$select_result5['payment_2025_sep'],$select_result5['payment_2025_oct'],$select_result5['payment_2025_nov'],$select_result5['payment_2025_dec'],$select_result5['payment_2026_jan'],$select_result5['payment_2026_feb'],$select_result5['payment_2026_mar'],$select_result5['payment_2026_apr'],$select_result5['payment_2026_may'],$select_result5['payment_2026_jun'],$select_result5['payment_2026_jul'],$select_result5['payment_2026_aug'],$select_result5['payment_2026_sep'],$select_result5['payment_2026_oct'],$select_result5['payment_2026_nov'],$select_result5['payment_2026_dec'],$select_result5['payment_2027_jan'],$select_result5['payment_2027_feb'],$select_result5['payment_2027_mar']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/procedure_origin";
        	$config["total_rows"] = $this->accounts_model->patient_procedure_origin_count($center, $start_date, $end_date, $patient_id, $payment_method, $json_data);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->patient_procedure_origin_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method, $json_data);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$data["json_data"] = $json_data;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/procedure_origin', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}


	public function patient_financial_clearance(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$biller_id = $this->input->get('biller_id', true);
			$clearance = $this->input->get('clearance', true);
			$consultant = $this->input->get('consultant', true);
			$accclearance = $this->input->get('accclearance', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/patient_financial_clearance";
        	$config["total_rows"] = $this->accounts_model->patient_procedure_reports_count($center, $start_date, $end_date, $patient_id, $biller_id, $clearance, $consultant, $accclearance);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->patient_procedure_reports_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $biller_id, $clearance, $consultant, $accclearance);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$data["biller_id"] = $biller_id;
			$data["clearance"] = $clearance;
			$data["consultant"] = $consultant;
			$data["accclearance"] = $accclearance;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/patient_financial_clearance', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}


/******** Freezing *******/	
	
	public function freezing_reports(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$origins = $this->input->get('origins', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$wife_name = $this->input->get('wife_name', true);
			$paid_amount = 0;
			$discounted_package = 0;
			$total_package = 0;
			$config = array();
        	$config["base_url"] = base_url() . "accounts/freezing_reports";
        	$config["total_rows"] = $this->accounts_model->freezing_reports_count($origins, $start_date, $end_date, $patient_id, $payment_method, $biller_id);
        	$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->freezing_reports_list_patination($config["per_page"], $per_page, $origins, $start_date, $end_date, $patient_id, $payment_method, $biller_id);
			$data["origins"] = $origins;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/freezing_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/******End Freezing******/

/******** Clinical Report *******/	
	
	public function clinical_reports(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('center', true);
			$year = $this->input->get('year', true);
			$patient_id = $this->input->get('patient_id', true);
			$wife_name = $this->input->get('wife_name', true);
			$payment_method = $this->input->get('payment_method', true);
			$paid_amount = 0;
			$discounted_package = 0;
			$total_package = 0;
			$config = array();
        	$config["base_url"] = base_url() . "accounts/clinical_reports";
        	$config["total_rows"] = $this->accounts_model->clinical_reports_count($center, $center, $year, $patient_id, $payment_method);
        	$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->clinical_reports_patination($config["per_page"], $per_page, $center, $year, $end_date, $patient_id, $payment_method);
			$data["center"] = $center;
			$data["year"] = $year;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/clinical_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function updatereports_admin(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('center', true);
			$year = $this->input->get('year', true);
			$patient_id = $this->input->get('patient_id', true);
			$wife_name = $this->input->get('wife_name', true);
			$payment_method = $this->input->get('payment_method', true);
			$paid_amount = 0;
			$discounted_package = 0;
			$total_package = 0;
			$config = array();
        	$config["base_url"] = base_url() . "accounts/updatereports_admin";
        	$config["total_rows"] = $this->accounts_model->clinical_reports_count($center, $center, $year, $patient_id, $payment_method);
        	$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->clinical_reports_patination($config["per_page"], $per_page, $center, $year, $end_date, $patient_id, $payment_method);
			$data["center"] = $center;
			$data["year"] = $year;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/updatereports_admin', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function updatereports_embrology(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/updatereports_embrology', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function updatereports(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/updatereports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function add_clinical_reports(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/add_clinical_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/******End Clinical Report******/

	
	public function psychological_reports(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$payment_method = $this->input->get('payment_method', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_billing = $this->input->get('export-billing', true);
			$paid_amount = 0;
			$discounted_package = 0;
			$total_package = 0;
			if (isset($export_billing)){
				$data = $this->accounts_model->export_psychological_data($start_date, $end_date, $center, $patient_id, $payment_method);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Procedure-Reports-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name,Receipt Number, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At,Procedure Name, Billing Type, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$paid_amount = $paid_amount +  (int)$val['payment_done'];
					$total_package = $total_package +  (int)$val['totalpackage'];
					$discounted_package = $discounted_package +  (int)$val['discounted_package'];
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'],$val['receipt_number'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at,$val['procedure_name'] ,$val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				$final_arr = array("", "", "", $total_package, $discounted_package, $paid_amount, "", "", "", "", "", "", "");
				fputcsv($fp, $final_arr);
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/psychological_reports";
        	$config["total_rows"] = $this->accounts_model->patient_psychological_count($center, $start_date, $end_date, $patient_id, $payment_method);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->patient_psychological_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/psychological_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
	   public function consultation_reports(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$reason_of_visit = $this->input->get('reason_of_visit', true);
			$payment_method = $this->input->get('payment_method', true);
			$export_billing = $this->input->get('export-billing', true);
			$paid_amount = 0;
			$discounted_package = 0;
			$total_package = 0;
			if (isset($export_billing)){
				$data = $this->accounts_model->export_consultation_data($start_date, $end_date, $center, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Consultation-Reports-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name,Receipt number, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$paid_amount = $paid_amount +  (int)$val['payment_done'];
					$total_package = $total_package +  (int)$val['totalpackage'];
					$discounted_package = $discounted_package +  (int)$val['discounted_package'];
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['receipt_number'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				$final_arr = array("", "", "", $total_package, $discounted_package, $paid_amount, "", "", "", "", "", "", "");
				fputcsv($fp, $final_arr);
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/consultation_reports";
        	$config["total_rows"] = $this->accounts_model->patient_consultation_count($center,$status, $start_date, $end_date, $patient_id,$doctor_id = null);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['consultation_result'] = $this->accounts_model->consultation_report_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id,$reason_of_visit,$payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["reason_of_visit"] = $reason_of_visit;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/consultation_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	} 
	
	
	   public function consultation_origin(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$doctor_id = $this->input->get('doctor_id', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$reason_of_visit = $this->input->get('reason_of_visit');
    		$category = $this->input->get('category');
			$procedures = $this->input->get('procedures');
			$agent = $this->input->get('agent');
			$councellor = $this->input->get('councellor');
			$broad_procedure = $this->input->get('broad_procedure');
			$broad_procedure_count = $this->input->get('broad_procedure_count');
			$lead_source = $this->input->get('lead_source');
			$export_billing = $this->input->get('export-billing', true);
			$paid_amount = 0;
			$discounted_package = 0;
			$total_package = 0;
			if (isset($export_billing)){
				$data = $this->accounts_model->export_consultation_origin_data($start_date, $end_date, $center, $patient_id, $reason_of_visit);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Consultation-Reports-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'UHID, IIC ID, Patient Name,Receipt number, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type,Reason OF Visit, Date, Status, Lead ID, Lead Source,Agent,councellor, Doctor Name,Booking Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$paid_amount = $paid_amount +  (int)$val['payment_done'];
					$total_package = $total_package +  (int)$val['totalpackage'];
					$discounted_package = $discounted_package +  (int)$val['discounted_package'];
					$billing_at = get_center_name($val['billing_at']);
					
					$sql = "SELECT * FROM hms_appointments WHERE paitent_id='" . $val['patient_id'] . "'";
                    $select_result = run_select_query($sql);
                    					
                    $sql3 = "SELECT * FROM hms_appointments WHERE wife_phone='" . $select_result['wife_phone'] . "' and paitent_type='new_patient'";
                    $select_result3 = run_select_query($sql3);
                    					
                    $sql4 = "SELECT * FROM hms_centers WHERE center_number='" . $select_result3['appoitment_for'] . "'";
                    $select_result4 = run_select_query($sql4);
                    					
                    $uhid = $select_result4['center_code']."/".$select_result3['uhid'];

					$lead_id = $select_result3['crm_id']; 
					$lead_source = $select_result3['lead_source']; 

					//$crm_id = get_lead($val['patient_id']);
					$agent = $select_result3['agent']; 
					$councellor = $select_result3['councellor']; 

					// 1. INITIALIZE $category BEFORE the main if block to guarantee scope
					$category = 'not booked'; // Default value (safer than 'not found')

					$sql4 = "SELECT * FROM hms_patient_procedure WHERE patient_id='" . $val['patient_id'] . "'";
					$select_result4 = run_select_query($sql4);

					// Check if 'data' exists and is not empty before proceeding
					if (!empty($select_result4['data'])) {
						
						$unserialized_data = unserialize($select_result4['data']);

						// 2. CRITICAL FIX: Access the array structure using index [0] for the sub_procedure
						if (isset($unserialized_data['patient_procedures']['sub_procedure'])) {
							
							$sub_procedure = $unserialized_data['patient_procedures']['sub_procedure'];
							
							// 3. Use the correct SQL variable ($sql5)
							$sql5 = "SELECT * FROM hms_procedures WHERE ID='" . $sub_procedure . "'"; 
							$select_result5 = run_select_query($sql5);
							
							// --- Category Determination ---
							if (!empty($select_result5) && isset($select_result5['category'])) {
								$category = $select_result5['category']; 
							} else {
								$category = 'not found';
							}
							
							// Optional: Echo the booking status if needed
							if($category == 'IVF with Bed'){
								echo ' booked';
							} else {
								echo ' not booked';
							}
							
						} else {
							// Procedure data structure exists but 'sub_procedure' is missing.
							$category = 'data missing';
						}
					} 				
					$sql1 = "Select * from ".$this->config->item('db_prefix')."doctors where ID='".$val['doctor_id']."'";
	                $select_appoint = run_select_query($sql1);

					$doctor = $select_appoint['name'];
					
					$lead_arr = array($uhid, $val['patient_id'], $val['wife_name'], $val['receipt_number'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'],$val['reason_of_visit'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status'], $lead_id, $lead_source, $agent, $councellor, $doctor,$category);
					fputcsv($fp, $lead_arr);
				}
				$final_arr = array("", "", "", "", $total_package, $discounted_package, $paid_amount, "", "", "", "", "", "", "");
				fputcsv($fp, $final_arr);
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/consultation_origin";
        	$config["total_rows"] = $this->accounts_model->patient_consultation_report_count($center, $start_date, $end_date, $patient_id, $reason_of_visit, $doctor_id,$lead_source,$category, $agent, $councellor);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['consultation_result'] = $this->accounts_model->patient_consultation_report_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $reason_of_visit, $doctor_id ,$agent, $councellor, $category,$procedures,$broad_procedure, $broad_procedure_count, $lead_source);
			$data['reason_counts'] = $this->accounts_model->patient_consultation_count_by_reason($center, $start_date, $end_date, $patient_id, $reason_of_visit, $doctor_id, $lead_source);
			$data['patient_counts'] = $this->accounts_model->patient_procedure_consultation_count($center, $start_date, $end_date, $patient_id,$reason_of_visit,$category,$procedures,$broad_procedure,$agent,$councellor,$broad_procedure_count);
			$data['lead_sources'] = $this->accounts_model->get_lead_source_dropdown_data();

			//$data['booked_patient_count'] = $booked_count; 
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["doctor_id"] = $doctor_id;
			$data["lead_source"] = $lead_source;
			//$data["reason_of_visit"] = $reason_of_visit;
			$data["category"] = $category;
			$data["procedures"] = $procedures;
			$data["broad_procedure"] = $broad_procedure;
			$data["broad_procedure_count"] = $broad_procedure_count;
			$data["agent"] = $agent;
			$data["councellor"] = $councellor;
			$data['selectedReason'] = $reason_of_visit;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/consultation_origin', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	} 
	
// In application/controllers/Accounts.php

public function export_consultation_csv() {
    // 1. Get ALL filters from URL (GET request)
    $center = $this->input->get('center');
    $start_date = $this->input->get('start_date');
    $end_date = $this->input->get('end_date');
    $patient_id = $this->input->get('patient_id');
    $reason_of_visit = $this->input->get('reason_of_visit');
    $doctor_id = $this->input->get('doctor_id');
    $agent = $this->input->get('agent');
    $councellor = $this->input->get('councellor');
    $lead_source = $this->input->get('lead_source');
    
    // New Procedure Filters
    $category = $this->input->get('category');
    $procedures = $this->input->get('procedures');
    $broad_procedure = $this->input->get('broad_procedure');
    $broad_procedure_count = $this->input->get('broad_procedure_count');
    
    // Get page parameter or default to 1
    $page = $this->input->get('page') ?: 1;

    // 2. Call Model with High Limit (to get all records)
    $report_data = $this->accounts_model->patient_export_report_patination(
        100000, // Limit
        $page,  // Page
        $center, $start_date, $end_date, $patient_id, $reason_of_visit, $doctor_id, $agent, $councellor, 
        $category, $procedures, $broad_procedure, $broad_procedure_count, $lead_source
    );

    // 3. Debug: Check if data is returned
    if (empty($report_data)) {
        // Add debug headers
        header("Content-Type: text/plain");
        echo "No data found. Check your filters and database.\n";
        echo "SQL Conditions: " . $this->db->last_query() . "\n";
        exit;
    }

    // 4. Create CSV File
    $filename = 'consultation_report_' . date('Ymd') . '.csv';
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: application/csv; ");
    
    $file = fopen('php://output', 'w');

    // 5. Write Headers
    $header = array(
        "Patient ID", "Agent", "Counsellor", "CRM ID", "Lead Source", "Visit Date", 
        "Reason", "Doctor ID", "Total Package", "Payment Done", 
        "Category", "Procedure", "Broad Procedure", "Broad Procedure Count"
    );
    fputcsv($file, $header);

    // 6. Write Data Rows
    foreach ($report_data as $row) {
        $line = array(
            $row['patient_id'] ?? '',
            $row['agent'] ?? '',
            $row['councellor'] ?? '',
            $row['crm_id'] ?? '',
            $row['lead_source'] ?? '',
            $row['on_date'] ?? '',
            $row['reason_of_visit'] ?? '',
            $row['doctor_id'] ?? '',
            $row['totalpackage'] ?? '0',
            $row['payment_done'] ?? '0',
            $row['category'] ?? '',
            $row['procedures'] ?? '',
            $row['broad_procedure'] ?? '',
            $row['broad_procedure_count'] ?? ''
        );
        fputcsv($file, $line);
    }

    fclose($file);
    exit;
}

	public function approve($request = NULL){

		$logg = checklogin();

		if($logg['status'] == true){

			$data = array();

			$type = $_GET['t'];

			$status = $_GET['u'];
			
			$cn_invoice = $_GET['cn'];
			
			$used_amount = $_GET['ua'];

			$reason = '';

			if($status == 'disapproved'){

				$reason = $_GET['r'];

			}
			if($status == 'cancel'){

				$reason_of_cancle = $_GET['c'];
			}
			if($status == 'adjust'){
				$reason_of_cancle = $_GET['c'];
			}
			$subject = 'Billing approval status';
			
			if($type == 'consultation'){			
			    $billing_records  = india_ivf_billing($request, "consultation"); 
			    if(!empty($billing_records)){
    			    $patient_id = $billing_records['patient_id'];
    			    $receipt_number = $billing_records['receipt_number'];
			    }
				$data = $this->accounts_model->approve_billing($request, $type, $status, $reason, $reason_of_cancle, $cn_invoice);
			
			}else if($type == 'registation'){
                $billing_records  = india_ivf_billing($request, "registation"); 
			    if(!empty($billing_records)){
    			    $patient_id = $billing_records['patient_id'];
    			    $receipt_number = $billing_records['receipt_number'];
			    }
				$data = $this->accounts_model->approve_billing($request, $type, $status, $reason, $reason_of_cancle, $cn_invoice);
           

			}else if($type == 'investigation'){
                $billing_records  = india_ivf_billing($request, "patient_investigations"); 
			    if(!empty($billing_records)){
    			    $patient_id = $billing_records['patient_id'];
    			    $receipt_number = $billing_records['receipt_number'];
			    }
				$data = $this->accounts_model->approve_billing($request, $type, $status, $reason, $reason_of_cancle, $cn_invoice);
            
			}else if($type == 'medicine'){
                $billing_records  = india_ivf_billing($request, "patient_medicine"); 
			    if(!empty($billing_records)){
    			    $patient_id = $billing_records['patient_id'];
    			    $receipt_number = $billing_records['receipt_number'];
			    }
			    $data = $this->accounts_model->approve_billing($request, $type, $status, $reason, $reason_of_cancle, $cn_invoice);
			
			}else if($type == 'partialpayments'){
                $billing_records  = india_ivf_billing($request, "patient_payments"); 
			    if(!empty($billing_records)){
    			    $patient_id = $billing_records['patient_id'];
    			    $refrence_number = $billing_records['refrence_number'];
			    }
				
			$data = $this->accounts_model->approve_billing($request, $type, $status, $reason, $reason_of_cancle, $cn_invoice);
			
			}else if($type == 'procedure'){
                $billing_records  = india_ivf_billing($request, "patient_procedure"); 
			    if(!empty($billing_records)){
    			    $patient_id = $billing_records['patient_id'];
    			    $receipt_number = $billing_records['receipt_number'];
			    }
				//print_r($data = $this->accounts_model->approve_procedure_billing($request, $type, $status, $reason, $reason_of_cancle, $cn_invoice, $used_amount));die();
				$data = $this->accounts_model->approve_procedure_billing($request, $type, $status, $reason, $reason_of_cancle, $cn_invoice, $used_amount);

			}
			/*else{
				//header("location:" .base_url(). "accounts/medicine_patients");
				die();
			}*/
			if($patient_id !== 0 && $receipt_number !== 0){
			    $patient_details = get_patient_detail($patient_id);
			    if(isset($_SESSION['logged_accountant']) && !empty($_SESSION['logged_accountant'])){
					$mail_msg = 'Hi IndiaIVF, <br/><br/> '.ucwords($type).' billing receipt ('.$receipt_number.') for patient '.$patient_details['wife_name'].' ('.$patient_id.') has been '.$status.'.';
					$account_email = $_SESSION['logged_accountant']['email']; //echo $mail_msg;die;
					$admin_email = $this->accounts_model->get_admin_email();
					$to_email =  $admin_email."|".$account_email;//echo $to_email;die;
					// $result = send_mail($to_email, $subject, $mail_msg);
					$result = true;
				}
			}
			header("location:" .$_SERVER['HTTP_REFERER']. "?m=".base64_encode('Billing '.$status.' successfully').'&t='.base64_encode('success'));
			die();

		}else{
			header("location:" .base_url(). "");
			die();
		}

	}
	
	function approve_registation($ID){
		$approved = $this->accounts_model->approve_registation($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/registration_patients?m=".base64_encode('Registration approved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/registration_patients?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function approve_consultation($ID){
		$approved = $this->accounts_model->approve_consultation($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/consultation_patients?m=".base64_encode('Consultation approved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/consultation_patients?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	
	function approve_procedure($ID){
		$approved = $this->accounts_model->approve_procedure($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/procedure_patients?m=".base64_encode('Procedure approved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/procedure_patients?m=".base64_encode('Procedure went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function approve_medicine($ID){
		$approved = $this->accounts_model->approve_medicine($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/procedure_patients?m=".base64_encode('Procedure approved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/procedure_patients?m=".base64_encode('Procedure went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}



/*	function clearance_procedure($ID){
		$approved = $this->accounts_model->clearance_procedure($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/patient_financial_clearance?m=".base64_encode('Procedure Clearance!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/patient_financial_clearance?m=".base64_encode('Procedure went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}

	function consultant_procedure($ID){
		$approved = $this->accounts_model->consultant_procedure($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/patient_financial_clearance?m=".base64_encode('Procedure Clearance!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/patient_financial_clearance?m=".base64_encode('Procedure went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}*/

public function clearance_procedure()
{
    $id           = $this->input->post('id', true);
    $counselor_id = $this->input->post('counselor_id', true);
    $emails       = $this->input->post('emails');

    if (empty($id) || empty($emails) || empty($counselor_id)) {
        echo 'invalid_request';
        return;
    }

    $row = $this->db->where('ID', $id)
                    ->get('hms_patient_procedure')
                    ->row_array();

    if (!$row) {
        echo 'record_not_found';
        return;
    }

    if ($row['clearance'] === 'Yes') {
        echo 'already_done';
        return;
    }

    // ✅ CORRECT UPDATE
    $this->db->where('ID', $id)->update('hms_patient_procedure', [
        'clearance'       => 'Yes',
        'counselor_id'    => $counselor_id,
        'clearance_date'  => date('Y-m-d H:i:s')
    ]);

    // ================= EMAIL =================
    $this->load->library('email');
    $this->email->set_mailtype('html');

    $this->email->from('noreply@indiaivf.in', 'India IVF');
    $this->email->to($emails);

    $this->email->subject(
        'Financial Clearance Done - Patient ID: ' . $row['patient_id']
    );

    $message = "
        <h3>Financial Clearance Completed</h3>
        <table border='1' cellpadding='6' width='100%'>
            <tr>
                <th>Patient ID</th>
                <th>Receipt No</th>
                <th>Billing Date</th>
                <th>Package</th>
                <th>Discount</th>
                <th>Received</th>
                <th>Procedure</th>
                <th>Category</th>
                <th>Cleared By</th>
            </tr>
            <tr>
                <td>{$row['patient_id']}</td>
                <td>{$row['receipt_number']}</td>
                <td>{$row['on_date']}</td>
                <td>{$row['totalpackage']}</td>
                <td>{$row['discount_amount']}</td>
                <td>{$row['fees']}</td>
                <td>{$row['procedure_name']}</td>
                <td>{$row['category']}</td>
                <td>{$row['councellor']}</td>
            </tr>
        </table>
        <p>India IVF System</p>
    ";

    $this->email->message($message);

    echo $this->email->send() ? 'success' : $this->email->print_debugger();
}



public function consultant_procedure()
{
    $id     = $this->input->post('id');
    $emails = $this->input->post('emails'); // ARRAY

    if (empty($id) || empty($emails)) {
        echo 'invalid_request';
        return;
    }

    // Fetch row
    $row = $this->db->where('ID', $id)
                    ->get('hms_patient_procedure')
                    ->row_array();

    if (!$row) {
        echo 'record_not_found';
        return;
    }

    // Prevent duplicate
    if ($row['consultant'] === 'Done') {
        echo 'already_done';
        return;
    }

    // Update consultant status
    $this->db->where('ID', $id)
             ->update('hms_patient_procedure', ['consultant' => 'Done']);

    // ---------- SEND MAIL ----------
    $this->load->library('email');
    $this->email->set_mailtype('html');

    $this->email->from('noreply@indiaivf.in', 'India IVF Procedure Financial Clearance');
    $this->email->to($emails); // ✅ ONLY SELECTED EMAILS

    $this->email->subject(
        'Consultant Clearance Done - Patient ID: ' . $row['patient_id']
    );

    $message = "
        <h3>Consultant Clearance Completed</h3>

        <table border='1' cellpadding='6' cellspacing='0' width='100%'>
            <tr>
                <th>Patient ID</th>
                <th>Receipt No</th>
                <th>Billing Date</th>
                <th>Procedure</th>
                <th>Category</th>
                <th>Consultant Status</th>
            </tr>
            <tr>
                <td>{$row['patient_id']}</td>
                <td>{$row['receipt_number']}</td>
                <td>{$row['on_date']}</td>
                <td>{$row['procedure_name']}</td>
                <td>{$row['category']}</td>
                <td>Done</td>
            </tr>
        </table>

        <br>
        <p>India IVF System</p>
    ";

    $this->email->message($message);

    if ($this->email->send()) {
        echo 'success';
    } else {
        echo $this->email->print_debugger();
    }
}


public function update_procedure_date()
{
    $id             = $this->input->post('id', true);
    $doctor_id      = $this->input->post('doctor_id', true);
    $procedure_date = $this->input->post('procedure_date', true);
    $emails         = $this->input->post('emails');

    // ================= VALIDATION =================
    if (empty($id) || empty($procedure_date) || empty($doctor_id) || empty($emails)) {
        echo 'invalid_request';
        return;
    }

    // ================= FETCH RECORD =================
    $row = $this->db->where('ID', $id)
                    ->get('hms_patient_procedure')
                    ->row_array();

    if (!$row) {
        echo 'record_not_found';
        return;
    }

    // ================= UPDATE DATA =================
    $update_data = [
        'procedure_date'     => $procedure_date,              // DATE from input
        'doctor_id'          => $doctor_id,
        'procedure_datetime' => date('Y-m-d H:i:s')           // DB SAFE FORMAT
    ];

    $this->db->where('ID', $id)->update('hms_patient_procedure', $update_data);

    // ================= SEND EMAIL =================
    $this->load->library('email');
    $this->email->set_mailtype('html');

    $this->email->from('noreply@indiaivf.in', 'India IVF');
    $this->email->to($emails); // ARRAY IS OK

    $this->email->subject(
        'Procedure Date Updated - Patient ID: ' . $row['patient_id']
    );

    $message = "
        <h3>Procedure Details</h3>
        <table border='1' cellpadding='6' cellspacing='0' width='100%'>
            <tr>
                <th>Patient ID</th>
                <th>Receipt No</th>
                <th>Billing Date</th>
                <th>Package Amount</th>
                <th>Discount</th>
                <th>Final Amount</th>
                <th>Procedure</th>
                <th>Code</th>
                <th>Category</th>
                <th>Clearance</th>
                <th>Procedure Date</th>
            </tr>
            <tr>
                <td>{$row['patient_id']}</td>
                <td>{$row['receipt_number']}</td>
                <td>{$row['on_date']}</td>
                <td>{$row['totalpackage']}</td>
                <td>{$row['discount_amount']}</td>
                <td>{$row['fees']}</td>
                <td>{$row['procedure_name']}</td>
                <td>{$row['code']}</td>
                <td>{$row['category']}</td>
                <td>{$row['councellor']}</td>
                <td><strong>{$procedure_date}</strong></td>
            </tr>
        </table>
        <br>
        <p>India IVF System</p>
    ";

    $this->email->message($message);

    // ================= RESPONSE =================
    if ($this->email->send()) {
        echo 'success';
    } else {
        echo $this->email->print_debugger();
    }
}


	function nonclearance_procedure($ID){
		$approved = $this->accounts_model->nonclearance_procedure($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/patient_financial_clearance?m=".base64_encode('Procedure Clearance!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/patient_financial_clearance?m=".base64_encode('Procedure went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}

	public function accclearance_procedure()
{
    $id            = $this->input->post('id', true);
    $accountant_id = $this->input->post('accountant_id', true);

    if (empty($id) || empty($accountant_id)) {
        echo 'invalid_request';
        return;
    }

    $row = $this->db->where('ID', $id)
                    ->get('hms_patient_procedure')
                    ->row_array();

    if (!$row) {
        echo 'record_not_found';
        return;
    }

    if ($row['accclearance'] === 'Yes') {
        echo 'already_done';
        return;
    }

    $this->db->where('ID', $id)->update('hms_patient_procedure', [
        'accclearance'       => 'Yes',
        'accclearance_date'  => date('Y-m-d H:i:s'),
        'accountant_id'      => $accountant_id
    ]);

    echo 'success';
}

	function accnonclearance_procedure($ID){
		$approved = $this->accounts_model->accnonclearance_procedure($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/patient_financial_clearance?m=".base64_encode('Procedure Clearance!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/patient_financial_clearance?m=".base64_encode('Procedure went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
public function tally()
{
    // =========================================================================
    // PART 1: MEDICINE SALES
    // =========================================================================

    $sql = "
        SELECT 
            s.id AS sale_id,
            s.patient_id,
            s.patient_name,
            c.center_name,
            s.sale_number,
            s.sale_date,
            s.payment_method,
            s.payment_status,
            s.payment_approved_by_name,
            m.medicine_name,
            mb.batch_number,
            mb.expiry_date,
            m.hsn_code,
            m.gst_rate,
            m.pack_size,
            mb.mrp,
            sm.quantity_change,
            sm.total_value
        FROM sales s
        INNER JOIN hms_centers c ON s.center_id = c.id
        INNER JOIN stock_movements sm ON s.id = sm.reference_id
        INNER JOIN medicine_batches mb ON sm.batch_id = mb.id
        INNER JOIN medicines m ON mb.medicine_id = m.id
        WHERE sm.movement_type = 'SALE'
        AND sm.to_location_type = 'SALE'
        ORDER BY s.updated_at DESC
        LIMIT 50
    ";

    $query = $this->db->query($sql);
    $results = $query->result_array();

    $medicine_sales = [];

    foreach ($results as $row) {
        $saleId = $row['sale_id'];

        $qty     = abs((int) $row['quantity_change']);
        $mrpUnit = $row['mrp'] / ($row['pack_size'] > 0 ? $row['pack_size'] : 1); 

        $gstRate = (float) $row['gst_rate'];
        $total   = (float) $row['total_value'];

        // Calculations
        $mrpValue = number_format($mrpUnit, 2, '.', '') * $qty;
        $taxableValue = ($total * 100) / (100 + $gstRate);
        $gstAmount = $total - $taxableValue;
        $discountAmount = $mrpValue - $total;
        $discountPercent = $mrpValue > 0 ? ($discountAmount / $mrpValue) * 100 : 0;
        $cgst = $gstAmount / 2;
        $sgst = $gstAmount / 2;

        if (!isset($medicine_sales[$saleId])) {
            $medicine_sales[$saleId] = [
                'type'             => 'Medicine',
                'patient_id'       => $row['patient_id'],
                'patient_name'     => $row['patient_name'],
                'billing_center'   => $row['center_name'],
                'receipt_number'   => $row['sale_number'],
                'on_date'          => $row['sale_date'],
                'payment_method'   => $row['payment_method'],
                'status'           => $row['payment_status'],
                'approved_by'      => $row['payment_approved_by_name'],
                'items'            => []
            ];
        }

        $medicine_sales[$saleId]['items'][] = [
            'item_name'       => $row['medicine_name'],
            'hsn_code'        => $row['hsn_code'],
            'pack_size'       => $row['pack_size'],
            'batch_number'    => $row['batch_number'],
            'expiry'          => $row['expiry_date'],
            'quantity'        => $qty,
            'mrp_per_unit'    => number_format($mrpUnit, 2, '.', ''),
            'discount_perc'   => number_format($discountPercent, 0, '.', ''),
            'mrp_value'       => number_format($mrpValue, 2, '.', ''),
            'discount_amt'    => number_format($discountAmount, 2, '.', ''),
            'taxable_value'   => number_format($taxableValue, 2, '.', ''),
            'gst_rate'        => number_format($gstRate, 2, '.', ''),
            'gst_amount'      => number_format($gstAmount, 2, '.', ''),
            'cgst'            => number_format($cgst, 2, '.', ''),
            'sgst'            => number_format($sgst, 2, '.', ''),
            'total_value'     => number_format($total, 2, '.', '')
        ];
    }

    // =========================================================================
    // PART 2: PROCEDURE SALES
    // =========================================================================
    
    $procedure_sales_raw = $this->accounts_model->get_all_sales_for_tally(); 
    $procedure_sales_formatted = [];

 if (!empty($procedure_sales_raw)) {
        foreach ($procedure_sales_raw as $sale) {
            $sql_patients = "SELECT * FROM hms_patients WHERE patient_id = ?";
            $patients_result = $this->db->query($sql_patients, [$sale["patient_id"]])->row_array();

            $sql_centers = "SELECT * FROM hms_centers WHERE center_number = ?";
            $centers_result = $this->db->query($sql_centers, [$sale["origins"]])->row_array();    

            $sql_billing = "SELECT * FROM hms_centers WHERE center_number = ?";
            $billing_result = $this->db->query($sql_billing, [$sale["billing_at"]])->row_array();

            $sql_employees = "SELECT * FROM hms_employees WHERE employee_number = ?";
            $employees_result = $this->db->query($sql_employees, [$sale["biller_id"]])->row_array();

            $formatted = [
                "type"            => "Procedure",
                "patient_id"      => $sale["patient_id"],
                "patient_name"    => ($patients_result['wife_name'] ?? '') . ' W/O ' . ($patients_result['husband_name'] ?? ''),
                "billing_center"  => $billing_result['center_name'] ?? 'N/A',
                "origin_center"   => $centers_result['center_name'] ?? 'N/A',
                "on_date"         => date("d-m-Y", strtotime($sale["on_date"])),
                "receipt_number"  => $sale["receipt_number"],
                "biller_name"     => $employees_result['name'] ?? 'N/A',
                "items"           => [],
                "payment_method"  => $sale["payment_method"] ?? "",
                "status"          => $sale["status"] ?? ""
            ];

            if (!empty($sale['data'])) {
                $unserialized = @unserialize($sale['data']);
                if (isset($unserialized['patient_procedures']) && is_array($unserialized['patient_procedures'])) {
                    foreach($unserialized['patient_procedures'] as $p) {
                         $formatted["items"][] = [
                            "item_name"                   => $sale["procedure_name"],
                            "sub_procedure"               => $p["sub_procedure"] ?? '',
                            "sub_procedures_code"         => $p["sub_procedures_code"] ?? '',
                            "sub_procedures_price"        => $p["sub_procedures_price"] ?? 0,
                            "sub_procedures_discount"     => $p["sub_procedures_discount"] ?? 0,
                            "sub_procedures_after_discount" => ((float)($p["sub_procedures_price"]??0) - (float)($p["sub_procedures_discount"]??0)),
                            "sub_procedures_paid_price"   => $p["sub_procedures_paid_price"] ?? 0
                        ];
 }
                }
            }
            $procedure_sales_formatted[] = $formatted;
        }
    }
        
     

    // =========================================================================
    // PART 3: CONSULTATION SALES
    // =========================================================================

    $q_cons = $this->db->query("SELECT * FROM hms_consultation WHERE status='approved' AND tally_status='1' ORDER BY modified_on DESC LIMIT 20");
    $consultation_rows = $q_cons->result_array();
    $consultation_sales = []; 

    foreach ($consultation_rows as $payment_row) {
        $pt_q = $this->db->query("SELECT * FROM hms_patients WHERE patient_id = ?", [$payment_row['patient_id']]);
        $patient = $pt_q->row_array() ?? [];

        $appointments_q = $this->db->query("SELECT * FROM hms_appointments WHERE paitent_id = ? AND paitent_type = ?",[$payment_row['patient_id'], 'new_patient']);
        $appointments = $appointments_q->row_array() ?? [];

        $org_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["origins"]]);
        $origin = $org_q->row_array() ?? [];

        $bill_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["billing_at"]]);
        $bill_center = $bill_q->row_array() ?? [];

        $book_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["billing_at"]]); 
        $book_center = $book_q->row_array() ?? [];

        $emp_q = $this->db->query("SELECT * FROM hms_employees WHERE employee_number = ?", [$payment_row["biller_id"]]);
        $employee = $emp_q->row_array() ?? [];

        $doctors_q = $this->db->query("SELECT * FROM hms_doctors WHERE ID = ?", [$payment_row["doctor_id"]]);
        $doctors = $doctors_q->row_array() ?? [];
        
        $consultation_sales[] = [
            "type"            => "Consultation",
            "patient_id"      => $payment_row['patient_id'],
            "crm_id"          => $appointments['crm_id'] ?? '',
            "patient_name"    => ($patient['wife_name'] ?? '') . ' W/O ' . ($patient['husband_name'] ?? ''),
            "billing_id"      => $payment_row['billing_id'],
            "payment_done"    => $payment_row['payment_done'],
            "billing_center"  => $bill_center['center_name'] ?? 'N/A',
            "booking_center"  => $book_center['center_name'] ?? 'N/A',
            "origin_center"   => $origin['center_name'] ?? 'N/A',
            "doctor_name"     => $doctors['name'] ?? 'N/A',
            "on_date"         => !empty($payment_row["on_date"]) ? date("d-m-Y H:i:s", strtotime($payment_row["on_date"])) : '',
            "receipt_number"  => $payment_row["receipt_number"] ?? '',
            "biller_name"     => $employee['name'] ?? 'N/A',
            "payment_method"  => $payment_row['payment_method'],
            "status"          => $payment_row['status']
        ];
    }
    
    // =========================================================================
    // PART 4: REGISTRATION SALES (From hms_registation)
    // =========================================================================

    $q_reg = $this->db->query("SELECT * FROM hms_registation WHERE status IN ('approved', 'adjust') AND tally_status='1' ORDER BY modified_on DESC LIMIT 20");
    $registration_rows = $q_reg->result_array();
    $registration_sales = [];
    
    foreach ($registration_rows as $payment_row) {
        $pt_q = $this->db->query("SELECT * FROM hms_patients WHERE patient_id = ?", [$payment_row['patient_id']]);
        $patient = $pt_q->row_array() ?? [];

        $appointments_q = $this->db->query("SELECT * FROM hms_appointments WHERE paitent_id = ? AND paitent_type = ?",[$payment_row['patient_id'], 'new_patient']);
        $appointments = $appointments_q->row_array() ?? [];

        $org_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["origins"]]);
        $origin = $org_q->row_array() ?? [];

        $bill_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["billing_at"]]);
        $bill_center = $bill_q->row_array() ?? [];

        $book_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["billing_at"]]); 
        $book_center = $book_q->row_array() ?? [];

        $emp_q = $this->db->query("SELECT * FROM hms_employees WHERE employee_number = ?", [$payment_row["biller_id"]]);
        $employee = $emp_q->row_array() ?? [];

        $doctors_q = $this->db->query("SELECT * FROM hms_doctors WHERE ID = ?", [$payment_row["doctor_id"]]);
        $doctors = $doctors_q->row_array() ?? [];

        $registration_sales[] = [
            "type"            => "Registration",
            "patient_id"      => $payment_row['patient_id'],
            "crm_id"          => $appointments['crm_id'] ?? '',
            "patient_name"    => ($patient['wife_name'] ?? '') . ' W/O ' . ($patient['husband_name'] ?? ''),
            "billing_id"      => $payment_row['billing_id'],
            "payment_done"    => $payment_row['payment_done'],
            "billing_center"  => $bill_center['center_name'] ?? 'N/A',
            "booking_center"  => $book_center['center_name'] ?? 'N/A',
            "origin_center"   => $origin['center_name'] ?? 'N/A',
            "doctor_name"     => $doctors['name'] ?? 'N/A',
            "on_date"         => !empty($payment_row["on_date"]) ? date("d-m-Y H:i:s", strtotime($payment_row["on_date"])) : '',
            "receipt_number"  => $payment_row["receipt_number"] ?? '',
            "biller_name"     => $employee['name'] ?? 'N/A',
            "payment_method"  => $payment_row['payment_method'],
            "status"          => $payment_row['status']
        ];
    }

    // =========================================================================
    // PART 5: INVESTIGATION SALES (From hms_patient_investigations)
    // =========================================================================

    $sql_invest = "SELECT * FROM hms_patient_investigations 
            WHERE `status`='approved' AND `tally_status`='1'
            ORDER BY ID DESC LIMIT 50";
    $query_invest = $this->db->query($sql_invest);
    $investigation_rows = $query_invest->result_array();
    $investigation_sales = [];

    if (!empty($investigation_rows)) {
        foreach ($investigation_rows as $sale) {
            $pt_q = $this->db->query("SELECT * FROM hms_patients WHERE patient_id = ?", [$sale["patient_id"]]);
            $patient = $pt_q->row_array() ?? [];

            $org_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$sale["origins"]]);
            $origin = $org_q->row_array() ?? [];

            $bill_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$sale["billing_at"]]);
            $bill_center = $bill_q->row_array() ?? [];

            $book_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$sale["billing_at"]]); 
            $book_center = $book_q->row_array() ?? [];

            $emp_q = $this->db->query("SELECT * FROM hms_employees WHERE employee_number = ?", [$sale["biller_id"]]);
            $employee = $emp_q->row_array() ?? [];

            $formatted = [
                "type"            => "Investigation",
                "patient_id"      => $sale["patient_id"],
                "patient_name"    => ($patient['wife_name'] ?? '') . ' W/O ' . ($patient['husband_name'] ?? ''),
                "billing_center"  => $bill_center['center_name'] ?? 'N/A',
                "booking_center"  => $book_center['center_name'] ?? 'N/A',
                "origin_center"   => $origin['center_name'] ?? 'N/A',
                "on_date"         => !empty($sale["on_date"]) ? date("d-m-Y", strtotime($sale["on_date"])) : '',
                "receipt_number"  => $sale["receipt_number"] ?? '',
                "biller_name"     => $employee['name'] ?? 'N/A',
                "items"           => [], 
                "payment_method"  => $sale["payment_method"] ?? "",
                "status"          => $sale["status"] ?? ""
            ];

            if (!empty($sale['investigations'])) {
                $unserialized = @unserialize($sale['investigations']);
                if ($unserialized && is_array($unserialized)) {
                    $groups = [
                        'female_investigation' => ['name' => 'female_investigation_name', 'code' => 'female_investigation_code', 'price'=> 'female_investigation_price', 'disc' => 'female_investigation_discount'],
                        'male_investigation' => ['name' => 'male_investigation_name', 'code' => 'male_investigation_code', 'price'=> 'male_investigation_price', 'disc' => 'male_investigation_discount']
                    ];
                    foreach ($groups as $group_key => $keys) {
                        if (!empty($unserialized[$group_key]) && is_array($unserialized[$group_key])) {
                            foreach ($unserialized[$group_key] as $item) {
                                if (!is_array($item)) continue;
                                $price   = isset($item[$keys['price']]) ? (float)$item[$keys['price']] : 0;
                                $percent = isset($item[$keys['disc']])  ? (float)$item[$keys['disc']]  : 0;

                                $investigation_q = $this->db->query("SELECT * FROM hms_investigation WHERE ID = " . (int)$item[$keys['name']]);
                                $investigation = $investigation_q->row_array() ?? [];
                                $m_investigation_q = $this->db->query("SELECT * FROM hms_master_investigations WHERE ID = " . (int)$investigation['master_id']);
                                $m_investigation = $m_investigation_q->row_array() ?? [];

                                $discount_amount = ($price * $percent) / 100; 
                                $formatted["items"][] = [
                                    "item_name"                   => $m_investigation['investigation_name'] ?? 'Unknown',
                                    "item_code"                   => $m_investigation['code'] ?? '',
                                    "item_price"                  => $price,
                                    "discount_percent"            => $percent . '%', 
                                    "discount_amount"             => $discount_amount,
                                    "paid_price"                  => $price - $discount_amount
                                ];
                            }
                        }
                    }
                }
            }
            $investigation_sales[] = $formatted;
        }
    }

    // =========================================================================
    // PART 6: FELLOWSHIP / TRAINING SALES (From hms_fellowship_training)
    // =========================================================================

    // =========================================================================
    // PART 6: FELLOWSHIP / TRAINING SALES
    // =========================================================================

    $q_fellow = $this->db->query("SELECT `ID`, `studentid`, `name`, `voucherCode`, `fname`, `course`, `code`, `hsn`, `price`, `discount_amount`, `payment_done`, `gst_amount`, `remaining_amount`, `gst`, `payment_method`, `address`, `place_of_supply`, `gst_number`, `on_date`, `cancel_date`, `receipt`, `invoice_no`, `receipt_url`, `status` FROM `hms_fellowship_training` WHERE `status` IN ('1', '3') AND `tally_status`='1' LIMIT 20");
    
    $fellowship_rows = $q_fellow->result_array();
    $fellowship_sales = [];
    
    foreach ($fellowship_rows as $row) {
        
        // Logic to convert Status ID to Text
        $status_text = $row['status']; // Default
        if ($row['status'] == '1') {
            $status_text = 'Approved';
        } elseif ($row['status'] == '3') {
            $status_text = 'Cancel';
        }

        $fellowship_sales[] = [
            "type"            => "Fellowship",
            "student_id"      => $row['studentid'],
            "name"            => $row['name'],
            "father_name"     => $row['fname'],
            "course_name"     => $row['course'],
            "hsn_code"        => $row['hsn'],
            
            // Financials
            "price"           => $row['price'],
            "payment_done"    => $row['payment_done'],
            "gst_amount"      => $row['gst_amount'],
            "discount"        => $row['discount_amount'],
            
            // Billing Info
            "invoice_no"      => $row['invoice_no'],
            "receipt_number"  => $row['receipt'],
            "gst_number"      => $row['gst_number'],      
            "address"         => $row['address'],
            
            // Standard Fields
            "on_date"         => !empty($row["on_date"]) ? date("d-m-Y H:i:s", strtotime($row["on_date"])) : '',
            "payment_method"  => $row['payment_method'],
            
            // USE THE CONVERTED STATUS HERE
            "status"          => $status_text
        ];
    }

    // =========================================================================
    // PART 7: FINAL OUTPUT
    // =========================================================================

    $final_output = [
        'export_date' => date('Y-m-d H:i:s'),
        'counts' => [
            'medicines' => count($medicine_sales),
            'procedures' => count($procedure_sales_formatted),
            'consultations' => count($consultation_sales),
            'registrations' => count($registration_sales),
            'investigations' => count($investigation_sales),
            'fellowships' => count($fellowship_sales)
        ],
        'Medicine_Sales' => array_values($medicine_sales), 
        'Procedure_Sales' => $procedure_sales_formatted,
        'Consultation_Sales' => $consultation_sales,
        'Registration_Sales' => $registration_sales,
        'Investigation_Sales' => $investigation_sales,
        'Fellowship_Sales' => $fellowship_sales
];

    header('Content-Type: application/json');
    echo json_encode($final_output, JSON_PRETTY_PRINT);
    exit;
}

// New update push test

public function procedure_tally() {

    // 1. FIX: Receive 'ids' (matching the JS data key), not 'payment_ids'
    $ids = $this->input->post('ids');

    if (empty($ids)) {
        echo json_encode(['success' => false, 'message' => 'No IDs received.']);
        return;
    }

    $success_count = 0;
    $error_count = 0;
    $already_sent_count = 0;

    foreach ($ids as $id) {

        // 2. Get current status for this ID
        $this->db->select('tally_status');
        $this->db->where('id', $id); // Make sure column name is 'id' or 'ID' matches your DB
        $q = $this->db->get('hms_patient_procedure');
        
        $row = $q->row_array();

        // 3. CHECK: If already sent (status 1), skip it
        if (!empty($row) && $row['tally_status'] == '1') {
            $already_sent_count++;
            continue; 
        }

        // 4. MARK AS SENT
        // Update tally_status to 1 (Sent)
        $this->db->where('id', $id);
        $update = $this->db->update('hms_patient_procedure', ['tally_status' => 1]);

        if ($update) {
            $success_count++;
        } else {
            $error_count++;
        }
    }

    // 5. Build Response
    if ($success_count > 0 || $already_sent_count > 0) {
        
        $msg = "";
        
        if ($success_count > 0) {
            $msg .= "$success_count records marked as Sent. ";
        }
        
        if ($already_sent_count > 0) {
            $msg .= "($already_sent_count were already sent previously). ";
        }

        if ($error_count > 0) {
            $msg .= "($error_count failed).";
        }

        echo json_encode(['success' => true, 'message' => trim($msg)]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update records.']);
    }
}

public function partial_tally() {

    $payment_ids = $this->input->post('payment_ids');

    if (empty($payment_ids)) {
        echo json_encode(['success' => false, 'message' => 'No IDs received.']);
        return;
    }

    $success_count = 0;
    $error_count = 0;
    $already_sent_count = 0; // New counter for duplicates

    foreach ($payment_ids as $id) {

        // 1. Get the current status for THIS SPECIFIC ID
        $this->db->select('tally_status');
        $this->db->where('ID', $id);
        $q = $this->db->get('hms_patient_payments');
        
        $row = $q->row_array();

        // 2. CHECK: Does the row exist? Is it already sent?
        if (!empty($row) && $row['tally_status'] == '1') {
            $already_sent_count++;
            continue; // Skip to the next ID
        }

        // --- Actual Tally Logic would go here ---
        $result = true; 

        if ($result) {
            $success_count++;
            // Update status to 1
            $this->db->where('ID', $id)->update('hms_patient_payments', ['tally_status' => 1]);
        } else {
            $error_count++;
        }
    }

    // 3. Build the response message
    if ($success_count > 0 || $already_sent_count > 0) {
        
        $msg = "";
        
        if ($success_count > 0) {
            $msg .= "$success_count records sent successfully. ";
        }
        
        if ($already_sent_count > 0) {
            $msg .= "($already_sent_count records were already sent). ";
        }

        if ($error_count > 0) {
            $msg .= "($error_count failed).";
        }

        echo json_encode(['success' => true, 'message' => trim($msg)]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send records to Tally.']);
    }
}


public function partialpayment_tally() {
    
    $q = $this->db->query("SELECT * FROM hms_patient_payments WHERE status IN ('1', '3') AND tally_status='1' ORDER BY modified_on DESC LIMIT 20");
	$payment_rows = $q->result_array();

	$sales_data = []; 

    // 2. Loop through the database results
    foreach ($payment_rows as $payment_row) {
        
       $id = $payment_row['ID'];

        // A. Fetch PROCEDURE details linked to this receipt
        $receipt_no = $payment_row['billing_id'];
        
        // REMOVED 'echo' here because it breaks JSON
        $proc_q = $this->db->query("SELECT * FROM hms_patient_procedure WHERE receipt_number = '$receipt_no'");
        $procedures = $proc_q->result_array();

        // Format Procedures Array
        $proc_list = [];
        foreach($procedures as $proc) {
            $proc_list[] = [
                "procedure_name"              => $proc['procedure_name'],
                "category"                    => $proc['category'], 
                "sub_procedure"               => $proc['procedure_id'],
                "sub_procedures_code"         => $proc['code'], 
                "sub_procedures_price"        => $proc['totalpackage'],
                "sub_procedures_discount"     => $proc['discount_amount'],
                "sub_procedures_after_discount" => (float)$proc['totalpackage'] - (float)$proc['discount_amount'],
                "sub_procedures_paid_price"   => $proc['payment_done']
            ];
        }

        // C. Build the Record
        $sales_data[] = [
            "patient_id"      => $payment_row['patient_id'],
			"billing_id" => $payment_row['billing_id'],
			"receipt_number"  => $payment_row['refrence_number'],
			"payment_done"  => $payment_row['payment_done'],
            "on_date"         => date('d-m-Y', strtotime($payment_row['on_date'])),
            "biller_name"     => "N/A", 
            "procedure_type"  => "New",
            "patient_procedures" => $proc_list,
            "payment_method"  => $payment_row['payment_method'],
           "status" => ($payment_row['status'] == 1) ? 'Approved' : (($payment_row['status'] == 3) ? 'Cancel' : 'Pending')
        ];

    }

    // 3. Return JSON Response
    header('Content-Type: application/json');
    echo json_encode(["Sales_Details" => $sales_data], JSON_PRETTY_PRINT);
}


public function investigations_send_tally() {

    $payment_ids = $this->input->post('payment_ids');

    if (empty($payment_ids)) {
        echo json_encode(['success' => false, 'message' => 'No IDs received.']);
        return;
    }

    $success_count = 0;
    $error_count = 0;
    $already_sent_count = 0; // Track how many were skipped

    foreach ($payment_ids as $id) {

        // 1. Get the current status of THIS specific ID
        // We select only tally_status to be efficient
        $this->db->select('tally_status');
        $this->db->where('ID', $id);
        $q = $this->db->get('hms_patient_investigations');
        
        $row = $q->row_array();

        // 2. CHECK: If row exists AND tally_status is already 1, skip it
        if (!empty($row) && $row['tally_status'] == '1') {
            $already_sent_count++;
            continue; // specific keyword to skip to the next ID in the loop
        }

        // --- Tally Logic Here ---
        // Put your actual code to send data to Tally API here.
        // For now, we assume it is successful.
        $result = true; 

        if ($result) {
            $success_count++;
            // Update the status to 1 so it isn't sent again next time
            $this->db->where('ID', $id)->update('hms_patient_investigations', ['tally_status' => 1]);
        } else {
            $error_count++;
        }
    }

    // 3. Build the response message
    if ($success_count > 0 || $already_sent_count > 0) {
        
        $msg = "";
        
        if ($success_count > 0) {
            $msg .= "$success_count records sent successfully. ";
        }
        
        if ($already_sent_count > 0) {
            $msg .= "($already_sent_count records were already sent). ";
        }

        if ($error_count > 0) {
            $msg .= "($error_count failed).";
        }

        echo json_encode(['success' => true, 'message' => trim($msg)]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send records to Tally.']);
    }
}

public function investigation_tally()
{
    // 1. Security Check
    $logg = checklogin(); 

    // 2. Get IDs
    $ids = $this->input->post('ids');
    if (empty($ids)) {
        $ids = $this->input->get('ids');
    }

    $all_sales = [];
    $sales = [];

    // 3. FETCH DATA
    if (!empty($ids)) {
        // Case A: Specific IDs
        $id_array = explode(',', $ids);
        $clean_ids = array_map('intval', $id_array);
        $id_list = implode(",", $clean_ids);
        
        if(!empty($id_list)){
            $sql = "SELECT * FROM hms_patient_investigations WHERE ID IN ($id_list)";
            $query = $this->db->query($sql);
            $sales = $query->result_array();
        }

    } else {
        // Case B: Direct DB Fetch (Last 50)
        $sql = "SELECT * FROM hms_patient_investigations 
                WHERE `status`='approved' AND `tally_status`='1'
                ORDER BY ID DESC LIMIT 50";
        $query = $this->db->query($sql);
        $sales = $query->result_array();
    }

    // 4. Process Data
    if (!empty($sales)) {

        foreach ($sales as $sale) {

            // --- A. Fetch Related Data ---
            $pt_q = $this->db->query("SELECT * FROM hms_patients WHERE patient_id = ?", [$sale["patient_id"]]);
            $patient = $pt_q->row_array() ?? [];

            $org_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$sale["origins"]]);
            $origin = $org_q->row_array() ?? [];

            $bill_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$sale["billing_at"]]);
            $bill_center = $bill_q->row_array() ?? [];

            $book_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$sale["billing_at"]]); 
            $book_center = $book_q->row_array() ?? [];

            $emp_q = $this->db->query("SELECT * FROM hms_employees WHERE employee_number = ?", [$sale["biller_id"]]);
            $employee = $emp_q->row_array() ?? [];


            // --- B. Parent Data ---
            $formatted = [
                "patient_id"      => $sale["patient_id"],
                "patient_name"    => ($patient['wife_name'] ?? '') . ' W/O ' . ($patient['husband_name'] ?? ''),
                "billing_center"  => $bill_center['center_name'] ?? 'N/A',
                "booking_center"  => $book_center['center_name'] ?? 'N/A',
                "origin_center"   => $origin['center_name'] ?? 'N/A',
                "on_date"         => !empty($sale["on_date"]) ? date("d-m-Y", strtotime($sale["on_date"])) : '',
                "receipt_number"  => $sale["receipt_number"] ?? '',
                "biller_name"     => $employee['name'] ?? 'N/A',
                "patient_investigations" => [], 
                "payment_method"  => $sale["payment_method"] ?? "",
                "status"          => $sale["status"] ?? ""
            ];


            // --- C. Process Items (Percentage is from DB) ---
            if (!empty($sale['investigations'])) {
                
                $unserialized = @unserialize($sale['investigations']);

                if ($unserialized && is_array($unserialized)) {

                    $groups = [
                        'female_investigation' => [
                            'name' => 'female_investigation_name',
                            'code' => 'female_investigation_code',
                            'price'=> 'female_investigation_price',
                            'disc' => 'female_investigation_discount' // This is the % (e.g. 20)
                        ],
                        'male_investigation' => [
                            'name' => 'male_investigation_name',
                            'code' => 'male_investigation_code',
                            'price'=> 'male_investigation_price',
                            'disc' => 'male_investigation_discount'
                        ]
                    ];

                    foreach ($groups as $group_key => $keys) {
                        if (!empty($unserialized[$group_key]) && is_array($unserialized[$group_key])) {
                            
                            foreach ($unserialized[$group_key] as $item) {
                                if (!is_array($item)) continue;

                                $price   = isset($item[$keys['price']]) ? (float)$item[$keys['price']] : 0;
                                $percent = isset($item[$keys['disc']])  ? (float)$item[$keys['disc']]  : 0;

								$investigation_q = $this->db->query("SELECT * FROM hms_investigation WHERE ID = " . $item[$keys['name']]);
								$investigation = $investigation_q->row_array() ?? [];

								$m_investigation_q = $this->db->query("SELECT * FROM hms_master_investigations WHERE ID = " . $investigation['master_id']);
								$m_investigation = $m_investigation_q->row_array() ?? [];


                                // CALCULATION: Convert % to Amount
                                $discount_amount = ($price * $percent) / 100; 

                                $formatted["patient_investigations"][] = [
                                    "investigation_name"             => $m_investigation['investigation_name'],
									"investigation_category"             => "",
                                    "investigation_code"             => $m_investigation['code'],
                                    "investigation_price"            => $price,
                                    
                                    // The Percentage (from DB)
                                    "investigation_discount_percent" => $percent . '%', 
                                    
                                    // The Calculated Amount (Price * %)
                                    "investigation_discount_amount"  => $discount_amount,
                                    
                                    "investigation_after_discount"   => $price - $discount_amount,
                                    "investigation_paid_price"       => $price - $discount_amount
                                ];
                            }
                        }
                    }
                }
            }
            
            $all_sales[] = $formatted;
        }
    }

    // 5. Output
    $response = [
        'export_date'   => date('Y-m-d H:i:s'),
        'record_count'  => count($all_sales),
        'Sales_Details' => $all_sales
    ];

    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT));
}

public function consultation_send_tally() {

    $payment_ids = $this->input->post('payment_ids');

    if (empty($payment_ids)) {
        echo json_encode(['success' => false, 'message' => 'No IDs received.']);
        return;
    }

    $success_count = 0;
    $error_count = 0;
    $already_sent_count = 0; // Track how many were skipped

    foreach ($payment_ids as $id) {

        // 1. Get the current status of THIS specific ID
        // We select only tally_status to be efficient
        $this->db->select('tally_status');
        $this->db->where('ID', $id);
        $q = $this->db->get('hms_consultation');
        
        $row = $q->row_array();

        // 2. CHECK: If row exists AND tally_status is already 1, skip it
        if (!empty($row) && $row['tally_status'] == '1') {
            $already_sent_count++;
            continue; // specific keyword to skip to the next ID in the loop
        }

        // --- Tally Logic Here ---
        // Put your actual code to send data to Tally API here.
        // For now, we assume it is successful.
        $result = true; 

        if ($result) {
            $success_count++;
            // Update the status to 1 so it isn't sent again next time
            $this->db->where('ID', $id)->update('hms_consultation', ['tally_status' => 1]);
        } else {
            $error_count++;
        }
    }

    // 3. Build the response message
    if ($success_count > 0 || $already_sent_count > 0) {
        
        $msg = "";
        
        if ($success_count > 0) {
            $msg .= "$success_count records sent successfully. ";
        }
        
        if ($already_sent_count > 0) {
            $msg .= "($already_sent_count records were already sent). ";
        }

        if ($error_count > 0) {
            $msg .= "($error_count failed).";
        }

        echo json_encode(['success' => true, 'message' => trim($msg)]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send records to Tally.']);
    }
}

public function consultation_tally() {
    
    $q = $this->db->query("SELECT * FROM hms_consultation WHERE status='approved' AND tally_status='1' ORDER BY modified_on DESC LIMIT 20");
	$payment_rows = $q->result_array();

	$sales_data = []; 

    // 2. Loop through the database results
    foreach ($payment_rows as $payment_row) {
        
       $id = $payment_row['ID'];

        // A. Fetch PROCEDURE details linked to this receipt
        $receipt_no = $payment_row['receipt_number'];

		$pt_q = $this->db->query("SELECT * FROM hms_patients WHERE patient_id = ?", [$payment_row['patient_id']]);
        $patient = $pt_q->row_array() ?? [];

		$appointments_q = $this->db->query("SELECT * FROM hms_appointments WHERE paitent_id = ? AND paitent_type = ?",[$payment_row['patient_id'], 'new_patient']);
		$appointments = $appointments_q->row_array() ?? [];

		$org_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["origins"]]);
        $origin = $org_q->row_array() ?? [];

        $bill_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["billing_at"]]);
        $bill_center = $bill_q->row_array() ?? [];

        $book_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["billing_at"]]); 
        $book_center = $book_q->row_array() ?? [];

        $emp_q = $this->db->query("SELECT * FROM hms_employees WHERE employee_number = ?", [$payment_row["biller_id"]]);
        $employee = $emp_q->row_array() ?? [];

		$doctors_q = $this->db->query("SELECT * FROM hms_doctors WHERE ID = ?", [$payment_row["doctor_id"]]);
        $doctors = $doctors_q->row_array() ?? [];
        
        // C. Build the Record
        $sales_data[] = [
            "patient_id"      => $payment_row['patient_id'],
			"crm_id"      => $appointments['crm_id'] ?? '',
			"patient_name"    => ($patient['wife_name'] ?? '') . ' W/O ' . ($patient['husband_name'] ?? ''),
			"billing_id" => $payment_row['billing_id'],
			"payment_done"  => $payment_row['payment_done'],
            "billing_center"  => $bill_center['center_name'] ?? 'N/A',
            "booking_center"  => $book_center['center_name'] ?? 'N/A',
            "origin_center"   => $origin['center_name'] ?? 'N/A',
			"doctor_name"   => $doctors['name'] ?? 'N/A',
            "on_date"         => !empty($payment_row["on_date"]) ? date("d-m-Y h:m:s", strtotime($payment_row["on_date"])) : '',
            "receipt_number"  => $payment_row["receipt_number"] ?? '',
            "biller_name"     => $employee['name'] ?? 'N/A',
            "payment_method"  => $payment_row['payment_method'],
           	"status" => $payment_row['status']
		];

    }

    // 3. Return JSON Response
    header('Content-Type: application/json');
    echo json_encode(["Sales_Details" => $sales_data], JSON_PRETTY_PRINT);
}


public function registration_send_tally() {

    $payment_ids = $this->input->post('payment_ids');

    if (empty($payment_ids)) {
        echo json_encode(['success' => false, 'message' => 'No IDs received.']);
        return;
    }

    $success_count = 0;
    $error_count = 0;
    $already_sent_count = 0; // Track how many were skipped

    foreach ($payment_ids as $id) {

        // 1. Get the current status of THIS specific ID
        // We select only tally_status to be efficient
        $this->db->select('tally_status');
        $this->db->where('ID', $id);
        $q = $this->db->get('hms_registation');
        
        $row = $q->row_array();

        // 2. CHECK: If row exists AND tally_status is already 1, skip it
        if (!empty($row) && $row['tally_status'] == '1') {
            $already_sent_count++;
            continue; // specific keyword to skip to the next ID in the loop
        }

        // --- Tally Logic Here ---
        // Put your actual code to send data to Tally API here.
        // For now, we assume it is successful.
        $result = true; 

        if ($result) {
            $success_count++;
            // Update the status to 1 so it isn't sent again next time
            $this->db->where('ID', $id)->update('hms_registation', ['tally_status' => 1]);
        } else {
            $error_count++;
        }
    }

    // 3. Build the response message
    if ($success_count > 0 || $already_sent_count > 0) {
        
        $msg = "";
        
        if ($success_count > 0) {
            $msg .= "$success_count records sent successfully. ";
        }
        
        if ($already_sent_count > 0) {
            $msg .= "($already_sent_count records were already sent). ";
        }

        if ($error_count > 0) {
            $msg .= "($error_count failed).";
        }

        echo json_encode(['success' => true, 'message' => trim($msg)]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send records to Tally.']);
    }
}

public function registration_tally() {
    
    $q = $this->db->query("SELECT * FROM hms_registation WHERE status IN ('approved', 'adjust') AND tally_status='1' ORDER BY modified_on DESC LIMIT 20");
	$payment_rows = $q->result_array();

	$sales_data = []; 

    // 2. Loop through the database results
    foreach ($payment_rows as $payment_row) {
        
       $id = $payment_row['ID'];

        // A. Fetch PROCEDURE details linked to this receipt
        $receipt_no = $payment_row['receipt_number'];

		$pt_q = $this->db->query("SELECT * FROM hms_patients WHERE patient_id = ?", [$payment_row['patient_id']]);
        $patient = $pt_q->row_array() ?? [];

		$appointments_q = $this->db->query("SELECT * FROM hms_appointments WHERE paitent_id = ? AND paitent_type = ?",[$payment_row['patient_id'], 'new_patient']);
		$appointments = $appointments_q->row_array() ?? [];

		$org_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["origins"]]);
        $origin = $org_q->row_array() ?? [];

        $bill_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["billing_at"]]);
        $bill_center = $bill_q->row_array() ?? [];

        $book_q = $this->db->query("SELECT * FROM hms_centers WHERE center_number = ?", [$payment_row["billing_at"]]); 
        $book_center = $book_q->row_array() ?? [];

        $emp_q = $this->db->query("SELECT * FROM hms_employees WHERE employee_number = ?", [$payment_row["biller_id"]]);
        $employee = $emp_q->row_array() ?? [];

		$doctors_q = $this->db->query("SELECT * FROM hms_doctors WHERE ID = ?", [$payment_row["doctor_id"]]);
        $doctors = $doctors_q->row_array() ?? [];
        
        // C. Build the Record
        $sales_data[] = [
            "patient_id"      => $payment_row['patient_id'],
			"crm_id"      => $appointments['crm_id'] ?? '',
			"patient_name"    => ($patient['wife_name'] ?? '') . ' W/O ' . ($patient['husband_name'] ?? ''),
			"billing_id" => $payment_row['billing_id'],
			"payment_done"  => $payment_row['payment_done'],
            "billing_center"  => $bill_center['center_name'] ?? 'N/A',
            "booking_center"  => $book_center['center_name'] ?? 'N/A',
            "origin_center"   => $origin['center_name'] ?? 'N/A',
			"doctor_name"   => $doctors['name'] ?? 'N/A',
            "on_date"         => !empty($payment_row["on_date"]) ? date("d-m-Y h:m:s", strtotime($payment_row["on_date"])) : '',
            "receipt_number"  => $payment_row["receipt_number"] ?? '',
            "biller_name"     => $employee['name'] ?? 'N/A',
            "payment_method"  => $payment_row['payment_method'],
           	"status" => $payment_row['status']
		];

    }

    // 3. Return JSON Response
    header('Content-Type: application/json');
    echo json_encode(["Sales_Details" => $sales_data], JSON_PRETTY_PRINT);
}

public function medicine_tally()
{
    $sql = "
        SELECT 
            s.id AS sale_id,
            s.patient_id,
            s.patient_name,
            c.center_name,
            s.sale_number,
            s.sale_date,
            s.payment_method,
            s.payment_status,
            s.payment_approved_by_name,
            m.medicine_name,
            mb.batch_number,
			mb.expiry_date,
            m.hsn_code,
            m.gst_rate,
            m.pack_size,
            mb.mrp,
            sm.quantity_change,
            sm.total_value

        FROM sales s
        INNER JOIN hms_centers c ON s.center_id = c.id
        INNER JOIN stock_movements sm ON s.id = sm.reference_id
        INNER JOIN medicine_batches mb ON sm.batch_id = mb.id
        INNER JOIN medicines m ON mb.medicine_id = m.id

        WHERE sm.movement_type = 'SALE'
        AND sm.to_location_type = 'SALE'

        ORDER BY s.updated_at DESC
        LIMIT 50
    ";

    $query = $this->db->query($sql);
    $results = $query->result_array();

    $sales = [];
    $recordCount = 0;

    foreach ($results as $row) {
        $saleId = $row['sale_id'];

		$qty       = abs((int) $row['quantity_change']);
		$mrpUnit = $row['mrp'] / $row['pack_size'];

         // MRP IS PER UNIT
		$gstRate  = (float) $row['gst_rate'];
		$total    = (float) $row['total_value'];

// MRP value
$mrpValue = number_format($mrpUnit, 2, '.', '') * $qty;

// Taxable value (GST INCLUDED)
$taxableValue = ($total * 100) / (100 + $gstRate);

// GST amount
$gstAmount = $total - $taxableValue;

// Discount amount
$discountAmount = $mrpValue - $total;

// Discount %
$discountPercent = $mrpValue > 0
    ? ($discountAmount / $mrpValue) * 100
    : 0;

// CGST / SGST
$cgst = $gstAmount / 2;
$sgst = $gstAmount / 2;


        // Create sale record if not exists
        if (!isset($sales[$saleId])) {
            $sales[$saleId] = [
                'patient_id' => $row['patient_id'],
                'patient_name' => $row['patient_name'],
                'billing_center' => $row['center_name'],
                'receipt_number' => $row['sale_number'],
                'on_date' => $row['sale_date'],
                'payment_method' => $row['payment_method'],
                'status' => $row['payment_status'],
                'payment_approved_by' => $row['payment_approved_by_name'],
                'patient_medicine' => []
            ];
            $recordCount++;
        }

        // Append medicine details
        $sales[$saleId]['patient_medicine'][] = [
    'medicine_name'    => $row['medicine_name'],
    'hsn_code'         => $row['hsn_code'],
    'pack_size'        => $row['pack_size'],
    'batch_number'     => $row['batch_number'],
    'expiry'           => $row['expiry_date'],
    'quantity'         => $qty,

    'mrp per unit'     => number_format($mrpUnit, 2, '.', ''),
    'discount %'       => number_format($discountPercent, 0, '.', ''),
    'mrp value'        => number_format($mrpValue, 2, '.', ''),
    'discount_amount'  => number_format($discountAmount, 2, '.', ''),
    'taxable_value'    => number_format($taxableValue, 2, '.', ''),
    'gst_rate'         => number_format($gstRate, 2, '.', ''),
    'gst_amount'       => number_format($gstAmount, 2, '.', ''),
    'igst'             => '',
    'cgst'             => number_format($cgst, 2, '.', ''),
    'sgst'             => number_format($sgst, 2, '.', ''),
    'total_value'      => number_format($total, 2, '.', '')
];

    }

    // Final JSON output
    $output = [
        'export_date' => date('Y-m-d H:i:s'),
        'record_count' => $recordCount,
        'Sales_Details' => array_values($sales)
    ];

    header('Content-Type: application/json');
    echo json_encode($output, JSON_PRETTY_PRINT);
    exit;
}


	public function front_approve($request = NULL){
			$data = array();
			$type = $_GET['t'];
			$status = $_GET['u'];
			$reason = '';
			if($status == 'disapproved'){
				$reason = $_GET['r'];
			}
			if($type == 'consultation'){			
			    $billing_records  = india_ivf_billing($request, "consultation"); 
			    if(!empty($billing_records)){
    			    $patient_id = $billing_records['patient_id'];
    			    $receipt_number = $billing_records['receipt_number'];
			    }
				$data = $this->accounts_model->approve_billing($request, $type, $status, $reason);
			}else if($type == 'investigation'){
                $billing_records  = india_ivf_billing($request, "patient_investigations"); 
			    if(!empty($billing_records)){
    			    $patient_id = $billing_records['patient_id'];
    			    $receipt_number = $billing_records['receipt_number'];
			    }
				$data = $this->accounts_model->approve_billing($request, $type, $status, $reason);
			}else if($type == 'procedure'){
                $billing_records  = india_ivf_billing($request, "patient_procedure"); 
			    if(!empty($billing_records)){
    			    $patient_id = $billing_records['patient_id'];
    			    $receipt_number = $billing_records['receipt_number'];
			    }
				$data = $this->accounts_model->approve_billing($request, $type, $status, $reason);
			}else{
				header("location:" .base_url(). "discount-approval?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
				die();
			}
			header("location:" .base_url(). "discount-approval?m=".base64_encode('Billing '.$status.' successfully').'&t='.base64_encode('success'));
			die();
	}

	function ajax_center_patient_ledger(){
		$center = $_SESSION['logged_accountant']['center'];
		$start = $_POST['start'];
		$end = $_POST['end'];
		$data = $this->accounts_model->ajax_center_patient_ledger_data($start, $end, $center);
		echo json_encode($data);
		die;
	}	

	function download_ledger(){
		$center = $_SESSION['logged_accountant']['center'];
		$start = isset($_GET['start'])?$_GET['start']:"";
		$end = isset($_GET['end'])?$_GET['end']:"";

		$data = $this->accounts_model->download_center_patient_ledger_data($start, $end, $center);

		//ob_clean();		
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=patient-ledger-'.$start.'-'.$end.'.csv');
		$fp = fopen('php://output','w');
		$headers = 'IIC ID, Name of Patient, Discounted Package, Received Amount,Remaining amount, IIC Share, Centre Share, Amount Recevied at IIC, Amount Received at Centre';
		//Add the headers
		fwrite($fp, $headers. "\r\n");
		foreach ($data as $key => $val) {
			fputcsv($fp, $val);
		}    
		//close file
		fclose($fp);
		exit();
	}

	public function accepted(){

		$logg = checklogin();

		if($logg['status'] == true){		

			$data = array();

			$template = get_header_template($logg['role']);

			$data = $this->accounts_model->accept_billing();

			$this->load->view($template['header']);

			$this->load->view('accounts/accepted', $data);

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}

	

	public function center_accepted(){

		$logg = checklogin();

		if($logg['status'] == true){		

			$data = array();

			$template = get_header_template($logg['role']);

			$data = $this->accounts_model->center_accept_billing();

			$this->load->view($template['header']);

			$this->load->view('accounts/center_accepted', $data);

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}

	

	public function accept($receipt = NULL){

		$logg = checklogin();

		if($logg['status'] == true){

			$data = array();

			$type = $_GET['t'];

			if($type == 'consultation'){			

				$data = $this->accounts_model->update_accept_billing($receipt, $type);

			}else if($type == 'investigation'){

				$data = $this->accounts_model->update_accept_billing($receipt, $type);

			}else if($type == 'procedure'){

				$data = $this->accounts_model->update_accept_billing($receipt, $type);

			}else{

				header("location:" .base_url(). "accounts/accepted");

				die();

			}

			header("location:" .base_url(). "accounts/accepted?m=".base64_encode('Billing accepted successfully').'&t='.base64_encode('success'));

			die();

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}

	

	//Admin Reconcilation

	public function reconciliation(){

		$logg = checklogin();

		if($logg['status'] == true){		

			$data = array();

			$template = get_header_template($logg['role']);

			$data['data'] = $this->accounts_model->reconciliation();

			$this->load->view($template['header']);

			$this->load->view('accounts/reconciliation', $data);

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}

	//Center Reconcilation

	public function center_reconciliation(){

		$logg = checklogin();

		if($logg['status'] == true){		

			$data = array();

			$template = get_header_template($logg['role']);

			$data['data'] = $this->accounts_model->center_reconciliation();

			$this->load->view($template['header']);

			$this->load->view('accounts/center_reconciliation', $data);

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}

	//Patient Reconcilation

	public function patient_reconcile($receipt){

		$logg = checklogin();

		if($logg['status'] == true){		

			$data = array();

			if(!isset($_GET['t']) && empty($_GET['t'])){

				header("location:" .base_url(). "accounts/requests?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));

				die();

			}

			

			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_patient_reconcile'){

				unset($_POST['action']);

				$receipt_number =  $_POST['receipt_number'];

				$type =  $_POST['type'];

				$payment_done =  $_POST['payment_done'];				

				$patient_reconcile = $this->accounts_model->update_patient_reconcile($receipt, $type, $payment_done);	

				if($patient_reconcile > 0){

					header("location:" .base_url(). "accounts/requests?m=".base64_encode('Reconciled successfully').'&t='.base64_encode('success'));

					die();

				}else{

					header("location:" .base_url(). "accounts/requests?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));

					die();

				}				

			}

			

			$type = $_GET['t'];

			$template = get_header_template($logg['role']);

			$data['billing_data'] = $this->billings_model->get_billings_details($receipt, $type);

			$patient = $data['billing_data']['patient_id'];

			$data['patient_data'] = $this->accounts_model->patient_details($patient);

			$this->load->view($template['header']);

			$this->load->view('accounts/patient_reconcile', $data);

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}

	

	public function patient_ledger(){

		$logg = checklogin();

		if($logg['status'] == true){		

			$data = array();

			$template = get_header_template($logg['role']);

			$data['data'] = $this->accounts_model->patient_ledger();

			$this->load->view($template['header']);

			$this->load->view('accounts/patient_ledger', $data);

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}

	

	public function ajax_patient_ledger(){ 

		if($_POST['type'] == 'month_wise'){

			$month = $_POST['month'];

			$data = $this->accounts_model->ajax_patient_month_ledger($month);

		}

		if($_POST['type'] == 'custom_wise'){

			$start = $_POST['start'];

			$end = $_POST['end'];

			$data = $this->accounts_model->ajax_patient_custom_ledger($start, $end);

		}

		echo json_encode($data);

		die;

	}

	

	public function patient_details($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->patient_details($patient_id);
			$data['form_data'] = $this->accounts_model->get_discharge_form_data();
			$data['form_data_embrology'] = $this->accounts_model->get_discharge_form_data_embrology();
			$data['formdata'] = $this->accounts_model->get_discharge_data();
			$data['investigation_reports'] = $this->accounts_model->investigation_reports($patient_id);
			$data['procedure_reports'] = $this->accounts_model->procedure_reports($patient_id);
			$data['patient_discharge'] = $this->accounts_model->patient_discharge($patient_id);
			$this->load->view($template['header']);
			$this->load->view('accounts/patient_details', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function patient_update(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/patient_update', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function updatefreezing(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/updatefreezing', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function addfreezing(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/addfreezing', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/************* Mou *************/
	
	public function mou(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/mou', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function add_mou(){
		if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_mou'){

		unset($_POST['action']);
		$_POST['on_date'] = date("Y-m-d H:i:s");
		//$_POST['status'] = '1';
		$transaction_img = '';
		if(!empty($_FILES['transaction_img']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-". $_FILES['transaction_img']['name'];
			$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
			$_POST['transaction_img'] = $transaction_img;
		}

		//$settle = $this->accounts_model->insert_add_mou($_POST);
		 echo "<br><pre>";
            print_r($this->accounts_model->insert_add_mou($_POST)); 
            echo "</pre>";
		//var_dump($settle = $this->accounts_model->insert_add_mou($_POST));die;
		if($settle > 0){
			header("location:" .base_url(). "accounts/mou?m=".base64_encode('Settled successfully!').'&t='.base64_encode('success'));
			die();
		}else{
				header("location:" .base_url(). "accounts/mou?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
				die();
			}
	}
	}
	
	public function update_mou(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			if(isset($_GET['ID'])){ $ID = $_GET['ID']; }
			if(isset($_POST['ID'])) { $ID = $_POST['ID']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_mou_item'){
				unset($_POST['action']);
				
				$transaction_img = '';
		if(!empty($_FILES['transaction_img']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-". $_FILES['transaction_img']['name'];
			$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
			$_POST['transaction_img'] = $transaction_img;
		}
				
				$data = $this->accounts_model->update_mou($_POST,$ID,$transaction_img);
				
				if($data > 0){
					header("location:" .base_url(). "accounts/update_mou?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&ID='.$ID);
					die();
				}else{
					header("location:" .base_url(). "accounts/update_mou?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&ID='.$ID);
					die();
				}				
			}
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/update_mou', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

public function moulist(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$status = $this->input->get('status', true);
			$party_name = $this->input->get('party_name', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/moulist";
        	$config["total_rows"] = $this->accounts_model->mou_count($status, $party_name, $start_date, $end_date);
        	$config["per_page"] = 40;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->mou_patination($config["per_page"], $per_page, $status, $party_name, $start_date, $end_date);
			$data["status"] = $status;
			$data["party_name"] = $party_name;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/moulist', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/***********End Mou **********/

	public function center_patient_ledger(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->center_patient_ledger();
			$this->load->view($template['header']);
			$this->load->view('accounts/center_patient_ledger', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function patient_payments($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->patient_payments($patient_id);
			$this->load->view($template['header']);
			$this->load->view('accounts/patient_payments', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function partial_payments(){

		$logg = checklogin();

		if($logg['status'] == true){		

			$data = array();

			$template = get_header_template($logg['role']);

			$data['data'] = $this->accounts_model->partial_payments();

			$this->load->view($template['header']);

			$this->load->view('accounts/partial_payments', $data);

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}

	

	function approve_payment($id){

		$payment = $this->accounts_model->approve_payment($id);
		if($payment > 0){

			header("location:" .base_url(). "accounts/partial_payments?m=".base64_encode('Payment approved').'&t='.base64_encode('success'));

			die();

		}else{

			header("location:" .base_url(). "accounts/partial_payments?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));

			die();

		}

	}

	

	function disapprove_payment($id){
		$disapprove_reason = isset($_GET['t'])?$_GET['t']:"";
		$payment = $this->accounts_model->disapprove_payment($id, $disapprove_reason);
		if($payment > 0){
			header("location:" .base_url(). "accounts/partial_payments?m=".base64_encode('Payment disapproved').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/partial_payments?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function cancle_payment($id){
		$disapprove_reason = isset($_GET['t'])?$_GET['t']:"";
		$payment = $this->accounts_model->cancle_payment($id, $disapprove_reason);
		if($payment > 0){
			header("location:" .base_url(). "accounts/partial_payments?m=".base64_encode('Payment disapproved').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/partial_payments?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	

	function get_current_balance($patient_id){
		$current_balance = $this->accounts_model->get_current_balance($patient_id);
		return $current_balance;
	}

	

	function get_payment_at($patient_id){

		$payment_at = $this->accounts_model->get_payment_at($patient_id);

		return $payment_at;

	}

		

	function update_billing(){

		$billing = $_POST['billing'];

		$type = $_POST['type'];

		$data = $this->accounts_model->update_billing($billing, $type);

		echo json_encode($data);

		die;

	}

	

	function settle($patient_id){

		$logg = checklogin();

		if($logg['status'] == true){	

			$patient = $this->accounts_model->check_patient($patient_id);

			if(count($patient) > 0){

				$data = array();

				$template = get_header_template($logg['role']);

				$data['patient_id'] = $patient_id;

				$this->load->view($template['header']);

				$this->load->view('accounts/settle', $data);

				$this->load->view($template['footer']);

			}else{

				header("location:" .base_url(). "accounts/center_reconciliation?m=".base64_encode('Patient not found!').'&t='.base64_encode('error'));

				die();

			}

		}else{

				header("location:" .base_url(). "");

				die();

		}	

	}

	

	function add_settle(){

		if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_settle'){

			unset($_POST['action']);

			$_POST['on_date'] = date("Y-m-d H:i:s");

			$_POST['status'] = '1';

			$transaction_img = '';

			if(!empty($_FILES['transaction_img']['tmp_name'])){

				$dest_path = $this->config->item('upload_path');

				$destination = $dest_path.'patient_files/';

				$NewImageName = rand(4,10000)."-".$_POST['patient_id']."-". $_FILES['transaction_img']['name'];

				$transaction_img = base_url().'assets/patient_files/'.$NewImageName;

				move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);

				$_POST['transaction_img'] = $transaction_img;

			}

			//var_dump($_POST);die;

			$settle = $this->accounts_model->insert_settle($_POST);

			if($settle > 0){

				header("location:" .base_url(). "accounts/center_reconciliation?m=".base64_encode('Settled successfully!').'&t='.base64_encode('success'));

				die();

			}else{

				header("location:" .base_url(). "accounts/center_reconciliation?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));

				die();

			}

		}else{

			header("location:" .base_url(). "accounts/center_reconciliation?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));

			die();

		}

	}

	

	function details($receipt = null){
		$logg = checklogin();
		if($logg['status'] == true){
			if(empty($receipt)){
				header("location:" .base_url(). "dashboard?m=".base64_encode('Receipt number is required!').'&t='.base64_encode('error'));
				die();
			}
			$data = array();
			$type = $_GET['t'];
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			if($type == 'consultation'){			
				$data['data'] = $this->accounts_model->get_details($receipt, $type);
				$this->load->view('accounts/consultation_details', $data);
			}else if($type == 'registation'){
				$data['data'] = $this->accounts_model->get_details($receipt, $type);
				$this->load->view('accounts/registation_details', $data);
			}else if($type == 'investigation'){
				$data['data'] = $this->accounts_model->get_details($receipt, $type);
				$this->load->view('accounts/investigation_details', $data);
			}else if($type == 'procedure'){
				$data['data'] = $this->accounts_model->get_details($receipt, $type);
				$this->load->view('accounts/procedure_details', $data);
			}else if($type == 'package'){
				$data['data'] = $this->accounts_model->get_details($receipt, $type);
				$this->load->view('accounts/package_details', $data);	
			}else if($type == 'medicine'){
				$data['data'] = $this->accounts_model->get_details($receipt, $type);
				$this->load->view('accounts/medicine_details', $data);	
			}else{
				header("location:" .base_url(). "dashboard?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
				die();
			}

			if(empty($data['data'])){

				header("location:" .base_url(). "dashboard?m=".base64_encode('Details not found!').'&t='.base64_encode('error'));

				die();

			}

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}



public function financial_clearance_details($receipt = null) {
    // 1. Check Login
    $logg = checklogin();
    if ($logg['status'] !== true) {
        header("location:" . base_url());
        die();
    }

    // 2. Validate Receipt Parameter
    if (empty($receipt)) {
		$msg = base64_encode('Receipt number is required!');
		
		// Corrected Line:
		header("Location: " . base_url() . "dashboard?m=" . $msg . "&t=" . base64_encode('error'));
		
		die();
	}

    // 3. Get Input (Use CodeIgniter input helper for security)
    $type = $this->input->get('t'); 

    // 4. Fetch Data
    $data_result = $this->accounts_model->get_financial_clearance_details($receipt);
	$partial_data_result = $this->accounts_model->get_financial_partial_clearance_details($receipt);

    // 5. Validate Data (Check this BEFORE loading any views)
    if (empty($data_result)) {
        $msg = base64_encode('Details not found!');
        $type = base64_encode('error');
        header("location:" . base_url() . "dashboard?m=" . $msg . "&t=" . $type);
        die();
    }

    // 6. Prepare Data for View
   $view_data['data_result'] = $data_result;
	$view_data['partial_data_result'] = $partial_data_result;

    // 7. Load Views (Only now do we output HTML)
    $template = get_header_template($logg['role']);
    
    $this->load->view($template['header']);
    
    // ** MISSING PART ADDED **: You need to load the actual page content here
    // Change 'accounts/financial_clearance_view' to your actual view file name
    $this->load->view('accounts/financial_clearance_details', $view_data); 
    
    $this->load->view($template['footer']);
}

	

	function check_settle($patient_id){

		$settle = $this->accounts_model->check_settle($patient_id);

		return $settle;

	}

	

	function procedure_name($id){

		$name = $this->accounts_model->get_procedure_name($id);

		return $name;

	}

	

	function get_doctor_name($doctor){

		$name = $this->accounts_model->get_doctor_name($doctor);

		return $name;

	}
	

	

	function get_investigation_name($investig){
		$name = $this->accounts_model->get_investigation_name($investig);
		return $name;
	}
	
	function get_master_investigation_name($investig){
		$name = $this->accounts_model->get_master_investigation_name($investig);
		return $name;
	}
	
	function get_master_investigation_code($investig){
		$name = $this->accounts_model->get_master_investigation_code($investig);
		return $name;
	}
	
	function get_procedure_name($procedure){

		$name = $this->accounts_model->get_procedure_name($procedure);

		return $name;

	}

	

	function get_item_name($item){

		$name = $this->accounts_model->get_item_name($item);

		return $name;

	}

		

	function get_center_name($center){

		$name = $this->accounts_model->get_center_name($center);

		return $name;

	}

	function get_lead($paitent_id){

		$crm_id = $this->accounts_model->get_lead($paitent_id);

		return $crm_id;

	}
/*
	function get_agent_name($paitent_id){

		$name = $this->accounts_model->get_councellor($paitent_id);

		return $name;

	}

	function get_leadsource_list($paitent_id){

		$name = $this->accounts_model->get_leadsource_list($paitent_id);

		return $name;

	}	*/

 	function get_lead_source($paitent_id){
        $name = $this->accounts_model->get_lead_source($paitent_id);
        return $name;
    }

	/*function get_counselor($appointment_id){
        $name = $this->accounts_model->get_counselor_name($appointment_id);
        return $name;
    }*/

	function get_counselor_name($appointment_id){
        $name = $this->accounts_model->get_counselor_name($appointment_id);
        return $name;
    }

/*
	function get_center_list($patient_id){

		$name = $this->accounts_model->get_center_list($patient_id);

		return $name;

	}	*/

	

	function get_patient_name($patient_id){

		$name = $this->billings_model->get_patient_name($patient_id);

		return $name;

	}

	

	function get_patient_items($receipt, $patient_id){

		$lists = $this->stock_model->get_patient_name($receipt, $patient_id);

		return $lists;

	}

	

	public function get_discount_approval(){
		//var_dump($_POST);die;
		$employee = $_POST['accountant'];
		$employee_data = get_employee_detail($employee);
		$discount = $_POST['discount'];
		$receipt_number = $_POST['receipt_number'];
		$patient_id = $_POST['patient_id'];
		$patient_data = get_patient_detail($patient_id);
		$billing_type = $_POST['billing_type'];
		//var_dump($patient_data);die;
		$admin_email = $this->accounts_model->get_admin_email();
		$currency = '';
		$currency = 'Rs.'.$discount;
		$coupon_code = self::coupon_code();
		$date = date('Y-m-d H:i:s');

		$patitnet_detls = "";
		if(!empty($patient_data)){
			$patitnet_detls = $patient_data['wife_name']." (".$patient_data['patient_id'].")";
		}else{
			$patitnet_detls = $patient_id;
		}
		$dscnt_arr = array();
		$dscnt_arr['patient_id'] = $patient_id;
		$dscnt_arr['code'] = $coupon_code;
		$dscnt_arr['amount'] = $discount;
		$dscnt_arr['receipt_number'] = $receipt_number;
		$dscnt_arr['type'] = $billing_type;
		$dscnt_arr['date'] = $date;
		$dscnt_arr['biller'] = $employee;
		$billing_discount = $this->accounts_model->billing_discount($dscnt_arr, $patient_id, $receipt_number, $billing_type);
		$response = array();
		if($billing_discount == 1){		
			$subject = 'Billing discount approval request';
			$mail_msg = 'Hi IndiaIVF, <br/><br/> '.$employee_data['name'].'('.$employee_data['employee_number'].') asked for discount approval of '.$currency.' for patient '.$patitnet_detls.' against '.$billing_type.' billing.<br/><br/><strong>One-time coupon code</strong>: '.$coupon_code.' <br/><br/> Click here for <a href="'.base_url().'discount-request?p='.base64_encode($patient_id).'&r='.base64_encode($receipt_number).'&s='.base64_encode('1').'&t='.base64_encode($billing_type).'&f='.base64_encode('mail').'">Approve</a> or <a href="'.base_url().'discount-request?p='.base64_encode($patient_id).'&r='.base64_encode($receipt_number).'&s='.base64_encode('2').'&t='.base64_encode($billing_type).'&f='.base64_encode('mail').'">Decline</a>';
			$result = send_mail($admin_email, $subject, $mail_msg);
			$response = array('status' => 1);
		}else if($billing_discount == 2){
			$response = array('status' => 2);
		}else{
			$response = array('status' => 0);
		}
		echo json_encode($response);
		die;
	}

	function coupon_code($j = 8){

		$string = "";

		for($i=0;$i < $j;$i++){

			srand((double)microtime()*1234567);

			$x = mt_rand(0,2);

			switch($x){

				case 0:$string.= chr(mt_rand(97,122));break;

				case 1:$string.= chr(mt_rand(65,90));break;

				case 2:$string.= chr(mt_rand(48,57));break;

			}

		}

		return strtoupper($string); //to uppercase

	}

	

	function discount_request(){
		$patient = base64_decode($_GET['p']);
		$receipt = base64_decode($_GET['r']);
		$status = base64_decode($_GET['s']);
		$billing_type = base64_decode($_GET['t']);
		$from = base64_decode($_GET['f']);
		$disapprove_reason = isset($_GET['rs'])?$_GET['rs']:"";
		$disapprove_amount = isset($_GET['da'])?$_GET['da']:"";
		
		$redirect = '';
		if($from == 'dashboard'){
			$redirect = 'billings/billing_discount';
		}else{
			$redirect = 'discount-approval';
		}

		$check_discount_billing = check_discount_billing($patient, $receipt, $billing_type);
		if(empty($check_discount_billing)){
			header("location:" .base_url(). "$redirect?m=".base64_encode('Oops, billing not created yet!').'&t='.base64_encode('error'));
			die();
		}
		
		$billing_discount = $this->accounts_model->discount_request($billing_type, $patient, $status, $receipt, $disapprove_reason, $disapprove_amount);
		if($billing_discount == 2){
			header("location:" .base_url(). "$redirect?m=".base64_encode('Oops, billing not created yet!').'&t='.base64_encode('error'));
			die();
		}
		if($billing_discount == 3){
			header("location:" .base_url(). "$redirect?m=".base64_encode('Oops, something went wrong!').'&t='.base64_encode('error'));
			die();
		}
		if($billing_discount == 1){
			$patient_data = get_patient_detail($patient);
			$currency = "";
			// if($patient_data['nationality'] == "indian"){$currency = "Rs.";}else{$currency = "USD";}
			$discount_data = $this->accounts_model->get_discount_biller($patient, $receipt);
			$text_status = ""; $coupon = ""; if($status == 1){$text_status = 'approved'; $coupon = "Your one-time discount code of amount ".$currency.$discount_data['amount']." :- <strong>".$discount_data['code']."</strong><br/><br/>";}else if($status == 2){$text_status = 'declined';}
			$approve_billing_by_receipt = $this->accounts_model->approve_billing_by_receipt($receipt, $billing_type, 'pending', 'discount '.$text_status.'');
			if(!empty($discount_data)){
				$biller_data = get_employee_detail($discount_data['biller']);
				$mail_msg = "";
				$mail_msg = "Hi ".$biller_data['name']." (".$biller_data['username'].")<br/><br/>";
				$mail_msg .= "Your request for discount against billing receipt number (".$receipt."), has been ".$text_status.".<br/><br/> ".$coupon." Thanks, <br/>IndiaIVF";
				$result = send_mail($biller_data['email'], 'Billing discount status', $mail_msg);
			}
			header("location:" .base_url(). "$redirect?m=".base64_encode('Discount '.$text_status.'!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "$redirect?m=".base64_encode('Oops, discount status already updated/discount request not found!').'&t='.base64_encode('error'));
			die();
		}
	}

	function discount_disapprove_request(){
		$patient = base64_decode($_GET['p']);
		$receipt = base64_decode($_GET['r']);
		$status = base64_decode($_GET['s']);
		$billing_type = base64_decode($_GET['t']);
		$from = base64_decode($_GET['f']);
		$disapprove_reason = isset($_GET['rs'])?$_GET['rs']:"";
		$disapprove_amount = isset($_GET['da'])?$_GET['da']:"";
		
		$redirect = '';
		if($from == 'dashboard'){
			$redirect = 'billings/billing_discount';
		}else{
			$redirect = 'discount-approval';
		}

		$check_discount_billing = check_discount_billing($patient, $receipt, $billing_type);
		if(empty($check_discount_billing)){
			header("location:" .base_url(). "$redirect?m=".base64_encode('Oops, billing not created yet!').'&t='.base64_encode('error'));
			die();
		}
		
		$billing_discount = $this->accounts_model->discount_request($billing_type, $patient, $status, $receipt, $disapprove_reason, $disapprove_amount);
		if($billing_discount == 2){
			header("location:" .base_url(). "$redirect?m=".base64_encode('Oops, billing not created yet!').'&t='.base64_encode('error'));
			die();
		}
		if($billing_discount == 3){
			header("location:" .base_url(). "$redirect?m=".base64_encode('Oops, something went wrong!').'&t='.base64_encode('error'));
			die();
		}
		if($billing_discount == 1){
			$patient_data = get_patient_detail($patient);
			$currency = "";
			// if($patient_data['nationality'] == "indian"){$currency = "Rs.";}else{$currency = "USD";}
			$discount_data = $this->accounts_model->get_discount_biller($patient, $receipt);
			$text_status = ""; $coupon = ""; if($status == 1){$text_status = 'approved'; $coupon = "Your one-time discount code of amount ".$currency.$discount_data['amount']." :- <strong>".$discount_data['code']."</strong><br/><br/>";}else if($status == 2){$text_status = 'declined';}
			$approve_billing_by_receipt = $this->accounts_model->approve_billing_by_receipt($receipt, $billing_type, 'disapproved', 'discount '.$text_status.'');
			if(!empty($discount_data)){
				$biller_data = get_employee_detail($discount_data['biller']);
				$mail_msg = "";
				$mail_msg = "Hi ".$biller_data['name']." (".$biller_data['username'].")<br/><br/>";
				$mail_msg .= "Your request for discount against billing receipt number (".$receipt."), has been ".$text_status.".<br/><br/> ".$coupon." Thanks, <br/>IndiaIVF";
				$result = send_mail($biller_data['email'], 'Billing discount status', $mail_msg);
			}
			header("location:" .base_url(). "$redirect?m=".base64_encode('Discount '.$text_status.'!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "$redirect?m=".base64_encode('Oops, discount status already updated/discount request not found!').'&t='.base64_encode('error'));
			die();
		}
	}

	function check_billing_discount($patient, $receipt_number){
		$discount = $this->accounts_model->check_billing_discount($patient, $receipt_number);
		return $discount;
	}	

	public function billing_discount(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->get_billing_discount();
			$this->load->view($template['header']);
			$this->load->view('accounts/billing-discount', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url()."");
			die();
		}
	}

	public function partial_payment_receipt($payment_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$data = $this->accounts_model->get_partial_payment_receipt($payment_id);
			if(!empty($data)){
				$data['data'] = $data;
				$this->load->view($template['header']);
				$this->load->view('accounts/partial_payment_receipt', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "accounts/accounts?m=".base64_encode('Oops, record not found!').'&t='.base64_encode('error'));
				die();
			}
		}else{
			header("location:" .base_url()."");
			die();
		}
	}
	

	function get_center(){
		if(isset($_SESSION['logged_accountant'])){ 
			$username = $_SESSION['logged_accountant']['username'];
			$center = $this->billings_model->get_center($username);
			return $center;
		}
	}
	
	function discount_applied($receipt){
		$check_discound_applied = $this->accounts_model->check_discound_applied($receipt);
		return $check_discound_applied;
	}
	
	function discount_applied_status($receipt){
		$check_discound_applied = $this->accounts_model->discount_applied_status($receipt);
		return $check_discound_applied;
	}
	
	function get_medicine_name($medicine){
		$name = $this->billings_model->get_medicine_name($medicine);
		return $name;
	}

	function get_brand_name($brand){
		$name = $this->billings_model->get_brand_name($brand);
		return $name;
	}
	
	function get_all_centers(){
		$all_centers = $this->center_model->get_centers();
		return $all_centers;
	}

	function get_all_procedures_category(){
		$all_procedures_category = $this->procedures_model->get_procedures_category();
		return $all_procedures_category;
	}

	function get_all_broad_procedure(){
		$all_broad_procedure = $this->procedures_model->get_broad_procedure();
		return $all_broad_procedure;
	}

	function get_all_procedures(){
		$all_procedures = $this->procedures_model->get_origin_procedures();
		return $all_procedures;
	}

	function get_all_booked_status(){
		$all_booked_status = $this->procedures_model->get_booked_status();
		return $all_booked_status;
	}

	function get_all_counselor(){
		$all_counselor = $this->accounts_model->get_counselor();
		return $all_counselor;
	}
	
	function get_all_status(){
		$all_status = $this->accounts_model->get_status();
		return $all_status;
	}

	function get_all_broad_procedure_count(){
		$all_broad_procedure_count = $this->procedures_model->get_broad_procedure_count();
		return $all_broad_procedure_count;
	}
	
	public function procedure_billings(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_billing = $this->input->get('export-billing', true);
	
			$config = array();
        	$config["base_url"] = base_url() . "accounts/procedure_billings";
        	$config["total_rows"] = $this->accounts_model->procedure_billings_count($center, $start_date, $end_date, $patient_id, $payment_method);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->procedure_billing_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/procedure_billings', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function investigation_billings(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
		$center = $this->input->get('billing_at', true);
		$start_date = $this->input->get('start_date', true);
		$end_date = $this->input->get('end_date', true);
		$patient_id = $this->input->get('iic_id', true);
		$status = $this->input->get('status', true);
		$payment_method = $this->input->get('payment_method', true);
		$export_billing = $this->input->get('export-billing', true);
		
		$config = array();
    	$config["base_url"] = base_url() . "accounts/investigation_billings";
    	$config["total_rows"] = $this->accounts_model->patient_investigation_count($center, $status, $start_date, $end_date, $patient_id, $payment_method);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->accounts_model->investigation_billing_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/investigation_billings', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	} 
	
		public function consultation_billings(){
			$logg = checklogin();
			error_reporting(0);
			if($logg['status'] == true){

				$per_page = $this->input->get('per_page', true);
				if(empty($per_page)){
					$per_page = 0;
				}
				$center = $this->input->get('billing_at', true);
				$start_date = $this->input->get('start_date', true);
				$end_date = $this->input->get('end_date', true);
				$patient_id = $this->input->get('iic_id', true);
				$export_billing = $this->input->get('export-billing', true);
				
				$config = array();
				$config["base_url"] = base_url() . "accounts/consultation_billings";
				$config["total_rows"] = $this->accounts_model->patient_consultation_count($center,$status, $start_date, $end_date, $patient_id,$doctor_id = null);
				$config["per_page"] = 10;
				$config["uri_segment"] = 2;
				$config['use_page_numbers'] = true;
				$config['num_links'] = 5;
				$config['page_query_string'] = true;
				$config['reuse_query_string'] = true;
				$this->pagination->initialize($config);
				$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
				
				$data["links"] = $this->pagination->create_links();
				$data['consultation_result'] = $this->accounts_model->patient_consultation_report_patination($config["per_page"], $per_page, $center,$status, $start_date, $end_date, $patient_id,$type,$doctor_id=null);
				$data["billing_at"] = $center;
				$data["start_date"] = $start_date;
				$data["end_date"] = $end_date;
				$data["patient_id"] = $patient_id;
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('accounts/consultation_billings', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "");
				die();
			}
		} 
		
			/*******************Medicine Report Account Panel****************/
	
	   public function medicine_patients(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->accounts_model->export_allmedicie_data($employee_number, $start_date, $end_date,  $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Medicine-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name, Receipt Number,Total Amount, Discount Amount, Discounted Amount, Paid Amount, Payment Method, Cash Payment,Card Payment,UPI Payment, NEFT Payment, Billing Type, Date, Status,Center Name';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					
					$sql_center = "SELECT * FROM hms_centers WHERE center_number='" . $val['billing_at'] . "'";
					$select_center = run_select_query($sql_center);
					$center_name = $select_center['center_name'];
					
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['receipt_number'], $val['fees'], $val['discount_amount'], $val['fees'] - $val['discount_amount'], $val['payment_done'], $val['payment_method'], $val['cash_payment'],$val['card_payment'],$val['upi_payment'],$val['neft_payment'], $val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status'],$center_name);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/medicine_patients";
        	$config["total_rows"] = $this->accounts_model->patient_investigation_count2($employee_number, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->accounts_model->patient_investigation_list_patination2($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id);
			
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/medicine_patients', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function appointment_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			
			$config = array();
        	$data['appointments_result'] = $this->accounts_model->dashboard_appointment_list($center, $start_date, $end_date);
			
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/appointment_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	

	public function reports(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			//$per_page = $this->input->get('per_page', true);
			$center = $this->input->get('billing_at', true);
			$payment_method = $this->input->get('payment_method', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			
			$config = array();
        	$data['procedure_result'] = $this->accounts_model->dashboard_procedure_list_patination($center, $start_date, $end_date);
			$data['investigation_result'] = $this->accounts_model->dashboard_investigation_list_patination($center, $start_date, $end_date);
			$data['consultation_result'] = $this->accounts_model->dashboard_consultation_report_patination($center, $start_date, $end_date);
			$data['partial_payment'] = $this->accounts_model->dashboard_partial_payment($center, $start_date, $end_date);
			$data['partial_payment_investigation'] = $this->accounts_model->dashboard_partial_payment_investigation($center, $start_date, $end_date);
			$data['registration_payment'] = $this->accounts_model->dashboard_registration_payment($center, $start_date, $end_date);
			$data['medicine_payment'] = $this->accounts_model->dashboard_medicine_payment($center, $start_date, $end_date);
			$data['medicine_return'] = $this->accounts_model->dashboard_medicine_return($center, $start_date, $end_date);
			$data['product_advisory'] = $this->accounts_model->dashboard_product_advisory($start_date, $end_date);
			$data['fellowship_training'] = $this->accounts_model->dashboard_fellowship_training($start_date, $end_date);
			
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
	public function update_investigation(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			if(isset($_GET['ID'])){ $ID = $_GET['ID']; }
			if(isset($_POST['ID'])) { $ID = $_POST['ID']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_investigation_item'){
				unset($_POST['action']);
				$data = $this->accounts_model->update_investigation($_POST,$ID);
				
				if($data > 0){
					header("location:" .base_url(). "accounts/update_investigation?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&ID='.$ID);
					die();
				}else{
					header("location:" .base_url(). "accounts/update_investigation?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&ID='.$ID);
					die();
				}				
			}
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/update_investigation', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
		/************* Liason *************/
	
	public function liason(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
		if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_liason'){
		unset($_POST['action']);
		$_POST['on_date'] = date("Y-m-d H:i:s");
		$transaction_img = '';
		if(!empty($_FILES['transaction_img']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-". $_FILES['transaction_img']['name'];
			$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
			$_POST['transaction_img'] = $transaction_img;
		}

		$settle = $this->accounts_model->insert_add_liason($_POST);
		if($settle > 0){
			header("location:" .base_url(). "accounts/liason?m=".base64_encode('Settled successfully!').'&t='.base64_encode('success'));
			die();
		}else{
				header("location:" .base_url(). "accounts/liason?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
				die();
			}
	}
	$template = get_header_template($logg['role']);
	$this->load->view($template['header']);
	$this->load->view('accounts/liason', $data);
	$this->load->view($template['footer']);
	}else{
	header("location:" .base_url(). "");
	die();
		}
	}
	
	public function update_liason(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			if(isset($_GET['ID'])){ $ID = $_GET['ID']; }
			if(isset($_POST['ID'])) { $ID = $_POST['ID']; }
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_liason_item'){
				unset($_POST['action']);
				$data = $this->accounts_model->update_liason($_POST,$ID);
				if($data > 0){
					header("location:" .base_url(). "accounts/update_liason?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&ID='.$ID);
					die();
				}else{
					header("location:" .base_url(). "accounts/update_liason?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&ID='.$ID);
					die();
				}				
			}
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/update_liason', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

public function liasonlist(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('center', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/liasonlist";
        	$config["total_rows"] = $this->accounts_model->liason_count($center, $start_date, $end_date);
        	$config["per_page"] = 40;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->liason_patination($config["per_page"], $per_page, $center, $start_date, $end_date);
			$data["center"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/liasonlist', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/***********End Liason **********/
	
	public function indiaivf_document(){
		$logg = checklogin();

		if($logg['status'] == true){
			$data = array();
			
		if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_document'){

		unset($_POST['action']);
		$_POST['on_date'] = date("Y-m-d H:i:s");
		$transaction_img = '';
		if(!empty($_FILES['transaction_img']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-". $_FILES['transaction_img']['name'];
			$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
			$_POST['transaction_img'] = $transaction_img;
		}
		
		$settle = $this->accounts_model->insert_add_indiaivf_document($_POST);
		 //echo "<br><pre>";
         //   print_r($this->accounts_model->insert_add_indiaivf_document($_POST)); 
          //  echo "</pre>";
		if($settle > 0){
			header("location:" .base_url(). "accounts/indiaivf_document?m=".base64_encode('Settled successfully!').'&t='.base64_encode('success'));
			die();
		}else{
				header("location:" .base_url(). "accounts/indiaivf_document?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
				die();
			}
	}
	$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/indiaivf_document', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
public function indiaivf_document_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$document_name = $this->input->get('document_name', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/indiaivf_document_list";
        	$config["total_rows"] = $this->accounts_model->indiaivf_document_count($document_name);
        	$config["per_page"] = 40;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->indiaivf_document_patination($config["per_page"], $per_page, $document_name);
			$data["document_name"] = $document_name;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/indiaivf_document_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/**********Investigation Origin**********/
	
    public function investigation_origin(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$payment_method = $this->input->get('payment_method', true);
			$export_investigation = $this->input->get('export-investigation', true);
			$json_data = $this->input->get('json_data', true);
			if (isset($export_investigation)){
				$data = $this->accounts_model->export_investigation_medicine($start_date, $end_date, $center, $patient_id, $origins);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Investigation-Report-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Date, IIC ID, Investigation Name, Investigation Code, Investigation Price, Investigation Discount, Investigation Final Price';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$employee_number = get_center_name($val['employee_number']);
					$total = (int)$val['female_investigation_price'] - (int)$val['female_investigation_discount'];
					$lead_arr = array($val['on_date'], $val['patient_id'], $val['female_investigation_name'], $val['female_investigation_code'], $val['female_investigation_price'], $val['female_investigation_discount'], $total);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
			$male_investigation = $this->input->get('export-male-investigation', true);
			if (isset($male_investigation)){
				$data = $this->accounts_model->export_male_investigation($start_date, $end_date, $center, $patient_id, $origins);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Investigation-Male-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Date, IIC ID, Investigation Name, Investigation Code, Investigation Price, Investigation Discount, Investigation Final Price';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$employee_number = get_center_name($val['employee_number']);
					$total = (int)$val['male_investigation_price'] - (int)$val['male_investigation_discount'];
					$lead_arr = array($val['on_date'], $val['patient_id'], $val['male_investigation_name'], $val['male_investigation_code'], $val['male_investigation_price'], $val['male_investigation_discount'], $total);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
			

			$config = array();
        	$config["base_url"] = base_url() . "accounts/investigation_origin";
        	$config["total_rows"] = $this->accounts_model->patient_investigation_origin_count($center, $start_date, $end_date, $patient_id, $payment_method, $json_data);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigation_result'] = $this->accounts_model->patient_investigation_origin_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method, $json_data);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["payment_method"] = $payment_method;
			$data["json_data"] = $json_data;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/investigation_origin', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
	/***********End Company Document **********/
	
	public function admission_form_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){
			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$iic_id = $this->input->get('iic_id', true);			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/admission_form_list";
        	$config["total_rows"] = $this->accounts_model->admission_form_count($iic_id);
        	$config["per_page"] = 40;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;			
        	$data["links"] = $this->pagination->create_links();
			$data['admission_result'] = $this->accounts_model->admission_form_patination($config["per_page"], $per_page, $iic_id);
			$data["iic_id"] = $iic_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/admission_form_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/*public function partial_procedure_billing()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['item_number'])){ $item_id = $_GET['item_number']; }
			if(isset($_POST['item_number'])) { $item_id = $_POST['item_number']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'partial_procedure_billing'){
				unset($_POST['action']);
				$item_number = $_POST['item_number'];
				$company = $_POST['company'];
				$item_name = $_POST['item_name'];
				$generic_name = $_POST['generic_name'];
				$batch_number = $_POST['batch_number'];
				$vendor_price = $_POST['vendor_price'];
				$quantity = $_POST['quantity'];
				$quantity_in = $_POST['quantity_in'];
				$closingstock = (float) $quantity + (float) $quantity_in;
				$expiry = $_POST['expiry'];
				$mrp = $_POST['mrp'];
				$status = $_POST['status'];
				//echo "<pre>";
				//print_r($this->stock_model->update_item_data($_POST, $item_id));
				//echo "</pre>";
				//exit();
				//var_dump($this->stock_model->update_item_data($_POST, $item_id, $company, $generic_name, $batch_number, $mrp, $vendor_price, $quantity_in, $hsn, $pack_size, $gstrate, $gstdivision, $expiry, $status));die;
				$data = $this->stock_model->update_item_data($_POST, $item_id, $company, $item_name, $generic_name, $batch_number, $mrp, $vendor_price, $quantity_in, $hsn, $pack_size, $gstrate, $gstdivision, $expiry, $status);
				if($data > 0){
					//$data = $this->stock_model->update_central_stock_report($_POST, $item_id, $company, $item_name, $batch_number, $expiry, $expiry_day, $add_date, $vendor_price, $quantity_in, $mrp, $gstrate, $gstdivision, $quantity, $closingstock);
					header("location:" .base_url(). "billing_view/partial_procedure_billing?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&item_number='.$item_id);
					die();
				}else{
					header("location:" .base_url(). "billing_view/partial_procedure_billing?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&item_number='.$item_id);
					die();
				}				
			}
			//$data['data'] = $this->stock_model->get_item_data($item_id);
			//$data['categories'] = $this->stock_model->get_categories();
			//$data['brands'] = $this->stock_model->get_brands();
			$data['data'] = $this->accounts_model->get_details($receipt, $type);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/partial_procedure_billing', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}*/
	
	function partial_procedure_billing($receipt){

		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$type = $_GET['t'];
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);

			if($type == 'procedure'){
				if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_partial_procedure'){
				unset($_POST['action']);
				
				$post_arr = array();

				$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);
				
				$post_arr['billing_id'] = isset($_POST['billing_id'])?$_POST['billing_id']:''; unset($_POST['billing_id']);
				
				$post_arr['refrence_number'] = getGUID();
				
				$post_arr['type'] = 'procedure';
				
				$post_arr['transaction_id'] = $_POST['transaction_id'];unset($_POST['transaction_id']);

				$post_arr['billing_from'] = $_POST['billing_from'];unset($_POST['billing_from']);

				$post_arr['billing_at'] = $_POST['billing_at'];unset($_POST['billing_at']);

				$post_arr['employee_number'] = $_POST['employee_number'];unset($_POST['employee_number']);

				$post_arr['on_date'] = date("Y-m-d H:i:s");unset($_POST['on_date']);

				$transaction_img = '';

				if(!empty($_FILES['transaction_img']['tmp_name'])){

					$dest_path = $this->config->item('upload_path');

					$destination = $dest_path.'patient_files/';

					$NewImageName = rand(4,10000)."-".$post_arr['patient_id']."-". $_FILES['transaction_img']['name'];

					$transaction_img = base_url().'assets/patient_files/'.$NewImageName;

					move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);

					$post_arr['transaction_img'] = $transaction_img;

				}

				$post_arr['payment_method'] = $_POST['payment_method'];unset($_POST['payment_method']);
				
				$post_arr['cash_payment'] = $_POST['cash_payment'];unset($_POST['cash_payment']);
				$post_arr['card_payment'] = $_POST['card_payment'];unset($_POST['card_payment']);
				$post_arr['upi_payment'] = $_POST['upi_payment'];unset($_POST['upi_payment']);
				$post_arr['neft_payment'] = $_POST['neft_payment'];unset($_POST['neft_payment']);
				$post_arr['wallet_payment'] = $_POST['wallet_payment'];unset($_POST['wallet_payment']);
				$post_arr['biller'] = $_POST['biller'];unset($_POST['biller']);
				
				$post_arr['origins'] = $_POST['origins']; unset($_POST['origins']);
				
				$post_arr['series_number'] = $_POST['series_number'] ; unset($_POST['series_number']);

				$post_arr['status'] = '0';
				
				$extract_procedure_array = $procedure_array = array();
				
				$icounte = $mcounte = $ccounte = $spcounte = 1;
				$i_counte = $m_counte = $c_counte = $s_pcounte = array();
				$i_counter = $m_counter = $c_counter = $s_pcounter = array();
				foreach($_POST as $key => $val){
					$pos_c = strpos($key, 'sub_procedure_');
					if ($pos_c === false) {} else {
						$cid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$c_counter[] = $cid;
					}	
				}
				
				if(!empty($c_counter)){
					foreach($c_counter as $key => $ccounte){
						if($_POST['sub_procedure_'.$ccounte] == ''){
							unset($_POST['sub_procedure_'.$ccounte]);
							unset($_POST['sub_procedures_code_'.$ccounte]);
							unset($_POST['sub_procedures_price_'.$ccounte]);
							unset($_POST['sub_procedures_paid_price_'.$ccounte]);
						}else{
							// insert query
							$sub_procedure = $_POST['sub_procedure_'.$ccounte];
							$sub_procedures_code = $_POST['sub_procedures_code_'.$ccounte];
							$sub_procedures_price = $_POST['sub_procedures_price_'.$ccounte];
							$sub_procedures_paid_price = $_POST['sub_procedures_paid_price_'.$ccounte];
							$c_counte[] = array('sub_procedure'=> $_POST['sub_procedure_'.$ccounte],'sub_procedures_code'=> $_POST['sub_procedures_code_'.$ccounte],'sub_procedures_price'=> $_POST['sub_procedures_price_'.$ccounte],'sub_procedures_paid_price'=> $_POST['sub_procedures_paid_price_'.$ccounte]);
							
						}
					}
				}
				$details = array();
				$details['patient_procedures'] = $c_counte;
				$post_arr['data'] = serialize($details);
				
				$post_arr['payment_done'] = $sub_procedures_paid_price = $_POST['sub_procedures_paid_price_1'] + $_POST['sub_procedures_paid_price_2'] + $_POST['sub_procedures_paid_price_3'];
					
				$curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://hms.indiaivf.website/old_reports/patient_payment/',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => json_encode($post_arr),
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                  ),
                ));
                
                $response = curl_exec($curl);
                
                curl_close($curl);
                echo $response;
					
				$p_procd = $this->accounts_model->partial_procedure_insert($post_arr);
				
				

				if($p_procd > 0){

					header("location:" .base_url(). "accounts/partial_procedure_billing/".$post_arr['billing_id']."?t=procedure");

					die();

				}else{

					header("location:" .base_url(). "accounts/partial_procedure_billing?m=".base64_encode('something went wrong!').'&t='.base64_encode('error'));
					die();
				}
			}
				$data['data'] = $this->accounts_model->get_details($receipt, $type);
				$this->load->view('accounts/partial_procedure_billing', $data);
			}
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
		public function add_donor()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_donor'){
				unset($_POST['action']);
				//var_dump($_POST);die;
				//$hsn = $_POST['hsn'];
				
				$post_arr['uhid'] = $_POST['uhid'];unset($_POST['uhid']);
				$post_arr['patient_id'] = $_POST['patient_id']; unset($_POST['patient_id']);
				$post_arr['PatientName'] = $_POST['PatientName'] ; unset($_POST['PatientName']);
				$post_arr['donor_uhid'] = $_POST['donor_uhid'] ; unset($_POST['donor_uhid']);
				$post_arr['donor_patient_id'] = $_POST['donor_patient_id'] ; unset($_POST['donor_patient_id']);
				$post_arr['donor_PatientName'] = $_POST['donor_PatientName'] ; unset($_POST['donor_PatientName']);
				$post_arr['type'] = $_POST['type'] ; unset($_POST['type']);
				$post_arr['date'] = $_POST['date'] = date("Y-m-d H:i:s");
				
				$data = $this->accounts_model->donor_insert($post_arr);
				if($data > 0){
					header("location:" .base_url(). "accounts/add_donor?m=".base64_encode('Item added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "accounts/add_donor?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$data['uhid'] = $this->accounts_model->get_uhid();
			$data['medicines'] = $this->accounts_model->get_paitent_id();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/add_donor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function donor_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$patient_id = $this->input->get('patient_id', true);
			$donor_patient_id = $this->input->get('donor_patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/donor_list";
        	$config["total_rows"] = $this->accounts_model->donor_count($patient_id, $donor_patient_id);
        	$config["per_page"] = 40;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->donor_patination($config["per_page"], $per_page, $patient_id, $donor_patient_id);
			$data["patient_id"] = $patient_id;
			$data["donor_patient_id"] = $donor_patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/donor_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function cancel_procedure_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_cancel_billing = $this->input->get('export-cancel-billing', true);
			if (isset($export_cancel_billing)){
				$data = $this->accounts_model->export_cancel_procedure_data($start_date, $end_date, $center, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Cancel-Procedure-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type, Billing Date,Cancel Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'], $val['on_date'], $val['modified_on'], $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
	
			$config = array();
        	$config["base_url"] = base_url() . "accounts/cancel_procedure_list";
        	$config["total_rows"] = $this->accounts_model->procedure_cancel_count($center, $start_date, $end_date, $patient_id, $payment_method);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->procedure_cancel_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/cancel_procedure_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function cancel_medicine_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_billing = $this->input->get('export-billing', true);
	
			$config = array();
        	$config["base_url"] = base_url() . "accounts/cancel_medicine_list";
        	$config["total_rows"] = $this->accounts_model->medicine_cancel_count($center, $start_date, $end_date, $patient_id, $payment_method);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['medicine_cancel_result'] = $this->accounts_model->medicine_cancel_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/cancel_medicine_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function cancel_consultation_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_billing = $this->input->get('export-billing', true);
	
			$config = array();
        	$config["base_url"] = base_url() . "accounts/cancel_consultation_list";
        	$config["total_rows"] = $this->accounts_model->consultation_cancel_count($center, $start_date, $end_date, $patient_id, $payment_method);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['consultation_cancel_result'] = $this->accounts_model->consultation_cancel_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/cancel_consultation_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
function wallet($receipt_number){

		$logg = checklogin();

		if($logg['status'] == true){

			$data = array();

			$type = $_GET['t'];

			$template = get_header_template($logg['role']);

			$this->load->view($template['header']);

				$data['data'] = $this->accounts_model->get_wallet_details($receipt_number, $type);

				$this->load->view('accounts/wallet', $data);

			if(empty($data['data'])){

				header("location:" .base_url(). "dashboard?m=".base64_encode('Details not found!').'&t='.base64_encode('error'));

				die();

			}

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

}
	
function consultation_wallet($receipt_number){

		$logg = checklogin();

		if($logg['status'] == true){

			$data = array();

			$template = get_header_template($logg['role']);

			$this->load->view($template['header']);

				$data['data'] = $this->accounts_model->get_consultation_wallet($receipt_number);

				$this->load->view('accounts/consultation_wallet', $data);

			if(empty($data['data'])){

				header("location:" .base_url(). "dashboard?m=".base64_encode('Details not found!').'&t='.base64_encode('error'));

				die();

			}

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

}
	
	function get_employee_list(){
		$employee = $this->accounts_model->get_employee_list();
		return $employee;	
	}
	function get_employee_name($employee){
		$name = $this->accounts_model->get_employee_name($employee);
		return $name;	
	}

	function get_stock_user(){
		$user = $this->stock_model->get_stock_user();
		return $user;	
	}

	public function add_training()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_training'){
				unset($_POST['action']);
				$data = $this->accounts_model->training_insert($_POST);
				if($data > 0){
					header("location:" .base_url(). "accounts/add_training?m=".base64_encode('Item added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "accounts/add_training?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/add_training', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function training_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$training_name = $this->input->get('training_name', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/training_list";
        	$config["total_rows"] = $this->accounts_model->training_count($training_name);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['consultation_cancel_result'] = $this->accounts_model->training_pagination($config["per_page"], $per_page, $training_name);
			$data["training_name"] = $training_name;
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/training_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	function approve_training($ID){
		$approved = $this->accounts_model->approve_training($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/training_list?m=".base64_encode('Product Advisory Fee approved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/training_list?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function disapprove_training($ID){
		$approved = $this->accounts_model->disapprove_training($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/training_list?m=".base64_encode('Product Advisory Fee Disapproved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/training_list?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}

	public function add_fellowship()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_fellowship'){
				unset($_POST['action']);
				$data = $this->accounts_model->fellowship_insert($_POST);
				if($data > 0){
					header("location:" .base_url(). "accounts/add_fellowship?m=".base64_encode('Item added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "accounts/add_fellowship?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$data['courses'] = $this->accounts_model->get_courses_name();
			$this->load->view($template['header']);
			$this->load->view('accounts/add_fellowship', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function fellowship_and_training(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$name = $this->input->get('name', true);
			$export_fellowship = $this->input->get('export-billing', true);
			if (isset($export_fellowship)){
				$data = $this->accounts_model->export_fellowship_and_training($name);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=fellowship-reports-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Student ID, Name, Fname, Course, Code, HSN, Price, Discount Amount, Payment Done, Gst Amount, Remaining Amount,GST,Payment Method,Address,Place of Supply,Gst Number,On Date,Receipt,Invoice No, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$lead_arr = array($val['studentid'], $val['name'], $val['fname'], $val['course'], $val['code'], $val['hsn'], $val['price'], $val['discount_amount'], $val['payment_done'], $val['gst_amount'],$val['remaining_amount'],$val['gst'],$val['payment_method'],$val['address'],$val['place_of_supply'],$val['gst_number'],$val['on_date'],$val['receipt'],$val['invoice_no'], $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
			$config = array();
        	$config["base_url"] = base_url() . "accounts/fellowship_and_training";
        	$config["total_rows"] = $this->accounts_model->fellowship_count($name);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['consultation_cancel_result'] = $this->accounts_model->fellowship_pagination($config["per_page"], $per_page, $name);
			$data["name"] = $name;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/fellowship_and_training', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function fellowship_payment(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$name = $this->input->get('name', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/fellowship_payment";
        	$config["total_rows"] = $this->accounts_model->fellowship_payment_count($name);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['consultation_cancel_result'] = $this->accounts_model->fellowship_payment_pagination($config["per_page"], $per_page, $name);
			$data["name"] = $name;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/fellowship_payment', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	function approve_fellowship($ID) {
		// Generate a voucher code
		$voucherCode = generateVoucherCode(12);  // 12-character voucher code

		// Call the model to approve the fellowship and pass the voucher code
		$approved = $this->accounts_model->approve_fellowship($ID, $voucherCode);

		// Check if the fellowship was approved successfully
		if ($approved > 0) {
			header("location:" .base_url(). "accounts/fellowship_and_training?m=".base64_encode('Product Advisory Fee approved! Voucher code: ' . $voucherCode).'&t='.base64_encode('success'));
			die();
		} else {
			header("location:" .base_url(). "accounts/fellowship_and_training?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function disapprove_fellowship($ID){
		$approved = $this->accounts_model->disapprove_fellowship($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/fellowship_and_training?m=".base64_encode('Product Advisory Fee Disapproved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/fellowship_and_training?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function cancel_fellowship($ID){
		$approved = $this->accounts_model->cancel_fellowship($ID);
		if($approved > 0){
			header("location:" .base_url(). "accounts/fellowship_and_training?m=".base64_encode('Product Advisory Fee Disapproved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "accounts/fellowship_and_training?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}

	function fellow_details($receipt){

		$logg = checklogin();

		if($logg['status'] == true){

			$data = array();

			$template = get_header_template($logg['role']);

			$this->load->view($template['header']);

				$data['data'] = $this->accounts_model->get_fellowship_details($receipt);

				$this->load->view('accounts/fellow_details', $data);
			
			$this->load->view($template['footer']);

		}else{

			header("Location: " . base_url() . "accounts/fellowship_and_training");

			die();

		}

	}

	function product_advisory_details($invoice_no){

		$logg = checklogin();

		if($logg['status'] == true){

			$data = array();

			$template = get_header_template($logg['role']);

			$this->load->view($template['header']);

				$data['data'] = $this->accounts_model->get_productadvisory_details($invoice_no);

				$this->load->view('accounts/product_advisory_details', $data);
			
			$this->load->view($template['footer']);

		}else{

			header("Location: " . base_url() . "accounts/training_list");

			die();

		}

	}

	public function wallet_balance($paitent_id)
	{
		$logg = checklogin();
		if($logg['status'] == true){
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'wallet_balance'){
				unset($_POST['action']);
				$data = $this->accounts_model->balancereturn_insert($_POST);
				if($data > 0){
					header("location:" .base_url(). "accounts/wallet_balance?m=".base64_encode('Item added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "accounts/wallet_balance?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$data['patient_data'] = get_patient_detail($paitent_id);
			$this->load->view($template['header']);
			$this->load->view('accounts/wallet_balance', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function wallet_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$patient_id = $this->input->get('patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/wallet_list";
        	$config["total_rows"] = $this->accounts_model->wallet_count($patient_id);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['wallet_result'] = $this->accounts_model->wallet_pagination($config["per_page"], $per_page, $patient_id);
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/wallet_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	function wallet_refund($receipt_number){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$type = $_GET['t'];
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
				$data['data'] = $this->accounts_model->get_wallet_refund($receipt_number);
				$this->load->view('accounts/wallet_refund', $data);
			if(empty($data['data'])){
				header("location:" .base_url(). "dashboard?m=".base64_encode('Details not found!').'&t='.base64_encode('error'));
				die();
			}
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

public function procedure_advice(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$patient_id = $this->input->get('iic_id', true);
			$config = array();
        	$config["per_page"] = 50;
        	$data['app_result'] = $this->accounts_model->doctor_patient_lists_pagination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method, $biller_id);
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/procedure_advice', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/*public function add_fellowship_payments()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_fellowship_payments'){
				unset($_POST['action']);
				$data = $this->accounts_model->fellowship_insert_payment($_POST);
				if($data > 0){
					header("location:" .base_url(). "accounts/add_fellowship_payments?m=".base64_encode('Item added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "accounts/add_fellowship_payments?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$data['courses'] = $this->accounts_model->get_courses_name();
			$this->load->view($template['header']);
			$this->load->view('accounts/add_fellowship_payments', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}*/



function add_fellowship_payments($ID){
	$logg = checklogin();
	if($logg['status'] == true){
		if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_fellowship_payments'){
			unset($_POST['action']);
			$data = $this->accounts_model->fellowship_insert_payment($_POST);
			if($data > 0){
				header("location:" .base_url(). "accounts/add_fellowship_payments?m=".base64_encode('Item added successfully !').'&t='.base64_encode('success'));
				die();
			}else{
				header("location:" .base_url(). "accounts/add_fellowship_payments?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
				die();
			}				
		}
		$data = array();
		$template = get_header_template($logg['role']);
		$this->load->view($template['header']);
		$data['data'] = $this->accounts_model->get_fellowship_payments($ID);
		$this->load->view('accounts/add_fellowship_payments', $data);
		$this->load->view($template['footer']);
	}else{
		header("Location: " . base_url() . "accounts/add_fellowship_payments");
		die();
	}
}


/***********Start Partial Payment***********/
public function partial_procedure(){
	$logg = checklogin();
	error_reporting(0);
	if($logg['status'] == true){

		$per_page = $this->input->get('per_page', true);
		if(empty($per_page)){
			$per_page = 0;
		}
		$center = $this->input->get('billing_at', true);
		$start_date = $this->input->get('start_date', true);
		$end_date = $this->input->get('end_date', true);
		$patient_id = $this->input->get('iic_id', true);
		$payment_method = $this->input->get('payment_method', true);
		$export_billing = $this->input->get('export-consumption', true);
		$json_data = $this->input->get('json_data', true);
		
		$paid_amount = 0;
			$discounted_package = 0;
			$total_package = 0;
			if (isset($export_billing)){
				$data = $this->accounts_model->export_partial_procedure_data($start_date, $end_date, $center, $patient_id, $payment_method, $biller_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Procedure-Reports-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name,Paid Amount, Payment Method, Billing From, Billing At,Procedure Name, Billing Type, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$paid_amount = $paid_amount +  (int)$val['payment_done'];
					$total_package = $total_package +  (int)$val['totalpackage'];
					$discounted_package = $discounted_package +  (int)$val['discounted_package'];
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at,$val['procedure_name'] ,$val['billing_type'], $val['on_date'], $val['status']);
					fputcsv($fp, $lead_arr);
				}
				$final_arr = array("", "", "", $total_package, $discounted_package, $paid_amount, "", "", "", "", "", "", "");
				fputcsv($fp, $final_arr);
				fclose($fp);
				exit();
			}

		$config = array();
		$config["base_url"] = base_url() . "accounts/partial_procedure";
		$config["total_rows"] = $this->accounts_model->partial_procedure_payment_count($center, $start_date, $end_date, $patient_id, $payment_method, $json_data);
		$config["per_page"] = 20;
		$config["uri_segment"] = 2;
		$config['use_page_numbers'] = true;
		$config['num_links'] = 5;
		$config['page_query_string'] = true;
		$config['reuse_query_string'] = true;
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		
		$data["links"] = $this->pagination->create_links();
		$data['procedure_result'] = $this->accounts_model->partial_procedure_payment_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $payment_method, $json_data);
		$data["billing_at"] = $center;
		$data["start_date"] = $start_date;
		$data["end_date"] = $end_date;
		$data["patient_id"] = $patient_id;
		$data["payment_method"] = $payment_method;
		$data["json_data"] = $json_data;
		$template = get_header_template($logg['role']);
		$this->load->view($template['header']);
		$this->load->view('accounts/partial_procedure', $data);
		$this->load->view($template['footer']);
	}else{
		header("location:" .base_url(). "");
		die();
	}
}

 public function revenue_potential(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$center_number = $this->input->get('center_number', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/revenue_potential";
        	$config["total_rows"] = $this->accounts_model->get_doctor_patient_count($start_date, $end_date, $patient_id, $center_number);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['app_result'] = $this->accounts_model->patient_lists_pagination($config["per_page"], $per_page, $start_date, $end_date, $patient_id, $center_number);
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["center_number"] = $center_number;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/revenue_potential', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	public function revenue_potential_details($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->revenue_potential_details($patient_id);
			//$data['form_data'] = $this->accounts_model->get_discharge_form_data();
			//$data['form_data_embrology'] = $this->accounts_model->get_discharge_form_data_embrology();
			//$data['formdata'] = $this->accounts_model->get_discharge_data();
			//$data['investigation_reports'] = $this->accounts_model->investigation_reports($patient_id);
			//$data['procedure_reports'] = $this->accounts_model->procedure_reports($patient_id);
			//$data['patient_discharge'] = $this->accounts_model->patient_discharge($patient_id);
			$this->load->view($template['header']);
			$this->load->view('accounts/revenue_potential_details', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function export_consultation_data($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){
			error_reporting(0);
			ini_set('display_errors', 0);
			$patient_data = get_patient_detail($patient_id);
			$patient_name = 'N/A';
			if (is_array($patient_data)) {
				$patient_name = isset($patient_data['wife_name']) ? $patient_data['wife_name'] : 
							   (isset($patient_data['wife_name']) ? $patient_data['wife_name'] : 'N/A');
			}
			$sql = "SELECT * FROM `hms_doctor_consultation` WHERE `patient_id`='".$patient_id."' ORDER BY `ID` DESC";
			$query = $this->db->query($sql);
			$results = $query->result();
			
			// Set headers for CSV download
			$filename = 'consultation_data_' . $patient_id . '_' . date('Y-m-d_H-i-s') . '.csv';
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			// Create file pointer
			$output = fopen('php://output', 'w');
			// Add patient information header
			fputcsv($output, array('Patient ID: ' . $patient_id));
			fputcsv($output, array('Patient Name: ' . $patient_name));
			fputcsv($output, array('')); // Empty row for spacing
			// CSV headers
			$headers = array(
				'Date',
				'Doctor Name',
				'Investigation Advice',
				'Investigation Billing Price',
				'Procedure Advice',
				'Procedure Billing',
				'Medicine Advice'
			);
			fputcsv($output, $headers);
			
			if (!empty($results)) {
				foreach ($results as $row) {
					// Get doctor name safely
					$doctor_name = 'N/A';
					if (!empty($row->doctor_id)) {
						$doc_sql = "SELECT * FROM `hms_doctors` WHERE ID='".$row->doctor_id."'";
						$doctor_result = run_select_query($doc_sql);
						$doctor_name = !empty($doctor_result) ? $doctor_result['name'] : 'N/A';
					}
					
					// Process investigation advice
					$investigation_advice = '';
					$investigation_billing_price = 0;
					
					// Female investigations
					if (!empty($row->female_investigation_suggestion_list)) {
						$female_investigations = @unserialize($row->female_investigation_suggestion_list);
						if ($female_investigations !== false && is_array($female_investigations)) {
							foreach ($female_investigations as $key => $value) {
								if (!empty($value)) {
									$inv_sql = "SELECT * FROM `hms_investigation` WHERE ID IN ($value)";
									$inv_result = run_select_query($inv_sql);
									if (!empty($inv_result)) {
										$investigation_advice .= "Investigation (Female): " . $inv_result['investigation'] . "; ";
										$investigation_billing_price += (float)$inv_result['price'];
									}
								}
							}
						}
					}
					
					// Male investigations
					if (!empty($row->male_investigation_suggestion_list)) {
						$male_investigations = @unserialize($row->male_investigation_suggestion_list);
						if ($male_investigations !== false && is_array($male_investigations)) {
							foreach ($male_investigations as $key => $value) {
								if (!empty($value)) {
									$inv_sql = "SELECT * FROM `hms_investigation` WHERE ID IN ($value)";
									$inv_result = run_select_query($inv_sql);
									if (!empty($inv_result)) {
										$investigation_advice .= "Investigation (Male): " . $inv_result['investigation'] . "; ";
										$investigation_billing_price += (float)$inv_result['price'];
									}
								}
							}
						}
					}
					
					// Get actual investigation billing from approved investigations
					if (!empty($row->appointment_id)) {
						$inv_billing_sql = "SELECT investigations, totalpackage, discount_amount FROM hms_patient_investigations WHERE appointment_id='" . $row->appointment_id . "' AND patient_id='" . $row->patient_id . "' AND status='approved'";
						$inv_billing_result = run_select_query($inv_billing_sql);
						if ($inv_billing_result && isset($inv_billing_result['totalpackage'])) {
							$investigation_billing_price = $inv_billing_result['totalpackage'] - $inv_billing_result['discount_amount'];
						}
					}
					
					// Process procedure advice
					$procedure_advice = '';
					$procedure_billing = 0;
					
					if (!empty($row->sub_procedure_suggestion_list)) {
						$procedures = @unserialize($row->sub_procedure_suggestion_list);
						if ($procedures !== false && is_array($procedures)) {
							foreach ($procedures as $key => $value) {
								if (!empty($value)) {
									$proc_sql = "SELECT * FROM `hms_procedures` WHERE ID IN ($value)";
									$proc_result = run_select_query($proc_sql);
									if (!empty($proc_result)) {
										$procedure_advice .= "Procedure: " . $proc_result['procedure_name'] . "; ";
										$procedure_billing += (float)$proc_result['price'];
									}
								}
							}
						}
					}
					
					// Get actual procedure billing from approved procedures
					if (!empty($row->appointment_id)) {
						$proc_billing_sql = "SELECT data, totalpackage, discount_amount FROM `hms_patient_procedure` WHERE appointment_id = ? AND patient_id = ? AND status = 'approved'";
						$proc_billing_result = $this->db->query($proc_billing_sql, array($row->appointment_id, $row->patient_id))->row_array();
						if ($proc_billing_result && isset($proc_billing_result['totalpackage'])) {
							$procedure_billing = $proc_billing_result['totalpackage'] - $proc_billing_result['discount_amount'];
						}
					}
					
					// Process medicine advice
					$medicine_advice = '';
					$medicine_total = 0;
					
					// Male medicines
					if (!empty($row->male_medicine_suggestion_list)) {
						$male_medicines = @unserialize($row->male_medicine_suggestion_list);
						if ($male_medicines !== false && isset($male_medicines['male_medicine_suggestion_list']) && is_array($male_medicines['male_medicine_suggestion_list'])) {
							foreach ($male_medicines['male_medicine_suggestion_list'] as $med) {
								if (is_array($med) && isset($med['male_medicine_name'])) {
									// Get medicine details from database
									$medicine_details = $this->get_medicine_details($med['male_medicine_name']);
									$medicine_name = !empty($medicine_details) && isset($medicine_details['item_name']) ? $medicine_details['item_name'] : $med['male_medicine_name'];
									
									// Calculate pricing
									$unit_price = 0;
									$subtotal = 0;
									if (!empty($medicine_details)) {
										$unit_price = product_vendor_cost($medicine_details['product_id'], $medicine_details['brand_name'], $medicine_details['vendor_number']);
										
										// Check if it's syrup type
										$sql = "Select * from ".$this->config->item('db_prefix')."stock_products where ID='".$medicine_details['product_id']."'";
										$select_result = run_select_query($sql);
										
										if ($select_result['type'] == "Cyrup") {
											$subtotal = (1 * 1) * ($unit_price * 1);
										} else {
											$frequency = medical_frequency($med['male_medicine_frequency']);
											$subtotal = ($med['male_medicine_days'] * $frequency) * ($unit_price * $med['male_medicine_dosage']);
										}
										$medicine_total += $subtotal;
									}
									
									$medicine_advice .= "Medicine (Male): " . $medicine_name . " - " . 
													   (isset($med['male_medicine_dosage']) ? $med['male_medicine_dosage'] : 'N/A') . " for " . 
													   (isset($med['male_medicine_days']) ? $med['male_medicine_days'] : 'N/A') . " days - " .
													   "Unit Price: Rs." . round($unit_price, 2) . " - " .
													   "Subtotal: Rs." . round($subtotal, 2) . "\n";
								}
							}
						}
					}
					
					// Female medicines
					if (!empty($row->female_medicine_suggestion_list)) {
						$female_medicines = @unserialize($row->female_medicine_suggestion_list);
						if ($female_medicines !== false && isset($female_medicines['female_medicine_suggestion_list']) && is_array($female_medicines['female_medicine_suggestion_list'])) {
							foreach ($female_medicines['female_medicine_suggestion_list'] as $med) {
								if (is_array($med) && isset($med['female_medicine_name'])) {
									// Get medicine details from database
									$medicine_details = $this->get_medicine_details($med['female_medicine_name']);
									$medicine_name = !empty($medicine_details) && isset($medicine_details['item_name']) ? $medicine_details['item_name'] : $med['female_medicine_name'];
									
									// Calculate pricing
									$unit_price = 0;
									$subtotal = 0;
									if (!empty($medicine_details)) {
										$unit_price = product_vendor_cost($medicine_details['product_id'], $medicine_details['brand_name'], $medicine_details['vendor_number']);
										
										// Check if it's syrup type
										$sql = "Select * from ".$this->config->item('db_prefix')."stock_products where ID='".$medicine_details['product_id']."'";
										$select_result = run_select_query($sql);
										
										if ($select_result['type'] == "Cyrup") {
											$subtotal = (1 * 1) * ($unit_price * 1);
										} else {
											$frequency = medical_frequency($med['female_medicine_frequency']);
											$subtotal = ($med['female_medicine_days'] * $frequency) * ($unit_price * $med['female_medicine_dosage']);
										}
										$medicine_total += $subtotal;
									}
									
									$medicine_advice .= "Medicine (Female): " . $medicine_name . " - " . 
													   (isset($med['female_medicine_dosage']) ? $med['female_medicine_dosage'] : 'N/A') . " for " . 
													   (isset($med['female_medicine_days']) ? $med['female_medicine_days'] : 'N/A') . " days - " .
													   "Unit Price: Rs." . round($unit_price, 2) . " - " .
													   "Subtotal: Rs." . round($subtotal, 2) . "\n";
								}
							}
						}
					}
					
					// Add total medicine cost
					if ($medicine_total > 0) {
						$medicine_advice .= "TOTAL MEDICINE COST: Rs." . round($medicine_total, 2) . "\n";
					}
					
					// Write row to CSV (without patient ID and name)
					$csv_row = array(
						$row->consultation_date,
						$doctor_name,
						$investigation_advice,
						$investigation_billing_price,
						$procedure_advice,
						$procedure_billing,
						$medicine_advice
					);
					fputcsv($output, $csv_row);
				}
			}
			
			fclose($output);
			exit();
		} else {
			header("location:" . base_url() . "");
			die();
		}
	}
	
	public function patient_center_wise_report(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->accounts_model->export_procedure_center_data($start_date, $end_date, $center, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Procedure-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Visit Month, First Visit Date, Booking Month, Booking Date, CH/FC Name, Doctor consulted, IIC ID, Patients Name, Centre Booking, Centre Procedure, Patients Source, Patient Type(New/Follow Up/ Recycle),Procedure Type(Add-on/IVF-procedure),Status of Booking,Procedure name (Same Day all procedure in same Column) IVF with Bed,Package Amount,Discount Amount,Package After Discount,Payment received,Balance Amount,Status(on Track/Delayed/Likely Cancellation),Comment/Current Status of Treatment( Latest Remarks and Date of Doctos),Next Steps - Follow UP,Std Withdrawal Date (T),Actual Withdrawal Date,Withdrawl Status (Done/Pending),Std Stimulation Date (40% of amount) (T+10),Actual Stimulation Start Date,Stimulation Start Status (Done/Pending),Amount (stimulation date),Std Trigger Date (50% of Package)(T+18),Actual Trigger Date,Amount (Billing Date),Trigger Status (Done/Pending),Std OPU Date(T +20),Actual OPU Date,Amount (Billing Date),OPU Status (Done/Pending)';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					
					$sql = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$val['patient_id']."'";
					$select_result = run_select_query($sql);
		
					$sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result['wife_phone']."' and paitent_type='new_patient' and status='consultation_done'";
					$select_result2 = run_select_query($sql2);
					
					$sql3 = "SELECT SUM(payment_done) AS total_payment_done, MAX(on_date) AS last_on_date  FROM hms_patient_payments WHERE billing_id = '" . $val['receipt_number'] . "' AND status = '1'";
					$select_result3 = run_select_query($sql3);
					
					$sql4 = "SELECT * FROM hms_doctor_consultation WHERE appointment_id='" . $val['appointment_id'] . "'";
					$select_result4 = run_select_query($sql4);
					
					$sql5 = "SELECT * from ovulation_induction_protocol WHERE receipt_number='" . $val['receipt_number'] . "'";
					$select_result5 = run_select_query($sql5);
					
					$sql6 = "SELECT * FROM trigger_module WHERE receipt_number='" . $val['receipt_number'] . "'";
					$select_result6 = run_select_query($sql6);
					
					$sql7 = "SELECT * FROM hms_appointments WHERE ID = '" . $val['appointment_id'] . "'";
					$select_result7 = run_select_query($sql7);
					
					$sql8 = "SELECT * FROM hms_doctors WHERE ID='" . $select_result4['doctor_id'] . "'";
					$select_result8 = run_select_query($sql8);
					
					$date_value = $select_result5['date1'] ?? ''; // Get date from array
					if (!empty($date_value)) {
					$sql9 = "SELECT SUM(payment_done) AS total_stdate_payment_done FROM hms_patient_payments WHERE billing_id = '" . $val['receipt_number'] . "' AND status = '1' AND DATE(on_date) <= '".$select_result5['date1']."'";
					$select_result9 = run_select_query($sql9);
					}
					
					$date_value_last_inj_fsh = $select_result6['last_inj_fsh'] ?? ''; // Get date from array
					if (!empty($date_value_last_inj_fsh)) {
					$sql10 = "SELECT SUM(payment_done) AS total_stdate_payment_done FROM hms_patient_payments WHERE billing_id = '" . $val['receipt_number'] . "' AND status = '1' AND DATE(on_date) <= '".$select_result6['last_inj_fsh']."'";
					$select_result10 = run_select_query($sql10);
					}
					
					$date_value_ovum_pickup = $select_result6['ovum_pick_up_on'] ?? ''; // Get date from array
					if (!empty($date_value_ovum_pickup)) {
					$sql11 = "SELECT SUM(payment_done) AS total_stdate_payment_done FROM hms_patient_payments WHERE billing_id = '" . $val['receipt_number'] . "' AND status = '1' AND DATE(on_date) <= '".$select_result6['ovum_pick_up_on']."'";
					$select_result11 = run_select_query($sql11);
					}
					
					$total_payment_done = isset($select_result3['total_payment_done']) ? (float)$select_result3['total_payment_done'] : 0;
					$total = (int)$val['sub_procedures_price'] - (int)$val['sub_procedures_discount'];
					$total_receive = $vl['payment_done'] + $total_payment_done ;
					$pending_amount = $vl['fees'] - $total_receive;

					$type = isset($select_result7['paitent_type']) ? $select_result7['paitent_type'] : null;

					$date = $select_result2['appoitmented_date']; // Original date (YYYY-MM-DD)
					$formatted_date = date("F y", strtotime($date)); // Output: "June 24"
					
					$on_date = $vl['on_date']; // Get the original date
					$new_date = date('Y-m-d', strtotime($on_date . ' +10 days')); // Add 10 days
					
					$on_date = $vl['on_date']; // Get the original date
					$new_date20 = date('Y-m-d', strtotime($on_date . ' +10 days')); // Add 10 days
					
					$appoitmented_date = $select_result2['appoitmented_date'];
					
					$visit_date = $val['on_date']; // Original date (YYYY-MM-DD)
					$formatted_date = date("F y", strtotime($visit_date));
					
					$amount_stimulation = $val['payment_done'] + (float)$select_result['total_stdate_payment_done'];
					
					$amount_billing_date = $val['payment_done'] + $select_result11['total_stdate_payment_done'];
					
					$vl['payment_done'] + $select_result10['total_stdate_payment_done'];
					
					$lead_arr = array($date, $appoitmented_date, $visit_date, $val['on_date'], 'FC NAME', $select_result8['name'], $val['patient_id'], $val['wife_name'], $billing_at, $select_result6['admit_at'],'Telecalling', $type, $v2_data5['sub_procedures_code'], $val['status'],$select_result8['category'], $val['totalpackage'], $val['discount_amount'], $val['fees'], $total_receive, $pending_amount, '', '', $select_result4['follow_up_date'], $val['on_date'], '', 'Done', $new_date, $select_result5['date1'], 'Done', $amount_stimulation, $new_date,'',$select_result6['last_inj_fsh'],'Done', $new_date20, $select_result6['ovum_pick_up_on'],$amount_billing_date,$procedure_name1);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/patient_center_wise_report";
        	$config["total_rows"] = $this->accounts_model->patient_report_count($center, $status, $start_date, $end_date, $patient_id, $payment_method, $biller_id);
        	$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->patient_report_list_patination($config["per_page"], $per_page, $center, $status, $start_date, $end_date, $patient_id, $payment_method, $biller_id);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/patient_center_wise_report', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
	function get_medicine_details($medicine){

		$details = $this->billingmodel_model->get_medicine_details($medicine);

		return $details;
	}
	
	function get_centerlist(){
		$center = $this->center_model->get_centers();
		return $center;
	}
	
	public function package_collections(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$status = $this->input->get('status', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->accounts_model->export_partialpayments_data($start_date, $end_date, $center, $patient_id, $status);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Partialpayments-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at,$val['procedure_name'] ,$val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "accounts/package_collections";
        	$config["total_rows"] = $this->accounts_model->package_collections_count($center, $start_date, $end_date, $patient_id, $status, $payment_method);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['partialpayments_result'] = $this->accounts_model->package_collections_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $status, $payment_method);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["status"] = $status;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/package_collections', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function add_doctor_referral()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_doctor_referral'){
				unset($_POST['action']);
				$post_arr['doctor_name'] = $_POST['doctor_name'];unset($_POST['doctor_name']);
				$post_arr['date'] = $_POST['date'] = date("Y-m-d H:i:s");
				$post_arr['status'] = $_POST['status']; unset($_POST['status']);
				$data = $this->accounts_model->doctor_referral_insert($post_arr);
				if($data > 0){
					header("location:" .base_url(). "accounts/add_doctor_referral?m=".base64_encode('Doctor added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "accounts/add_doctor_referral?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/add_doctor_referral', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function doctor_referral_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){
			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$config = array();
        	$config["base_url"] = base_url() . "accounts/doctor_referral_list";
        	$config["total_rows"] = $this->accounts_model->doctor_referral_count();
        	$config["per_page"] = 40;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->accounts_model->doctor_referral_patination($config["per_page"], $per_page);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/doctor_referral_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function doctor_referral_edit()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			
			if(isset($_GET['id'])){
				if(isset($_GET['id'])){ $item_id = $_GET['id']; }
				if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_doctor_referral'){
					unset($_POST['action']);unset($_POST['id']);
					
					$post_arr['doctor_name'] = $_POST['doctor_name'];unset($_POST['doctor_name']);
					$post_arr['status'] = $_POST['status'];unset($_POST['status']);
					
					$data = $this->accounts_model->update_doctor_referral_data($post_arr, $item_id);
					if($data > 0){
						header("location:" .base_url(). "accounts/doctor_referral_edit?m=".base64_encode('Doctor Name updated successfully !').'&t='.base64_encode('success').'&id='.$item_id);
						die();
					}else{
						header("location:" .base_url(). "accounts/doctor_referral_edit?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&id='.$item_id);
						die();
					}				
				}
				$data['data'] = $this->accounts_model->get_doctor_referral($item_id);
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('accounts/doctor_referral_edit', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "procedures");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
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


	public function get_center_name_by_number($center_number)
	{
		$this->db->select('center_name');
		$this->db->from('hms_centers');
		$this->db->where('center_number', $center_number);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['center_name'];
	}

	public function save_purchase_order()
	{
		$logg = checklogin();
		if ($this->input->post('action') == 'add_purchase_orders') {
			$approved_by = $this->input->post('approved_by');
			$approved_by_str = !empty($approved_by) ? implode(", ", $approved_by) : null;
			$uploaded_file = null;
			$uploaded_files = $this->_handle_multiple_uploads('po_supporting_documents');
			if ($uploaded_files['status'] == 'error') {
				$this->session->set_flashdata('error', $uploaded_files['message']);
				redirect('accounts/purchase_order'); 
				return;
			}
			// if (!empty($_FILES['po_supporting_documents']['name'])) {
			// 	// Use the configured upload path from config
			// 	$dest_path = $this->config->item('upload_path');
			// 	$config['upload_path']   = $dest_path . 'purchase_orders/';
			// 	$config['allowed_types'] = 'pdf|jpg|jpeg|png|webp|gif|bmp';
			// 	$config['max_size']      = 10240; // 10 MB
			// 	$config['file_ext_tolower'] = TRUE;
			// 	$config['remove_spaces'] = TRUE;
			// 	$config['overwrite']     = FALSE;
			// 	if (!is_dir($config['upload_path'])) {
			// 		mkdir($config['upload_path'], 0777, true);
			// 	}
			// 	$this->load->library('upload', $config);
			// 	if (!$this->upload->do_upload('po_supporting_documents')) {
			// 		$error = $this->upload->display_errors('', '');
			// 		$this->session->set_flashdata('error', 'File upload failed: ' . $error);
			// 		redirect('accounts/purchase_order');
			// 		return;
			// 	}
				
			// 	$upload_data  = $this->upload->data();
			// 	$uploaded_file = $upload_data['file_name'];
			// }
			$approval_token = bin2hex(random_bytes(16));
			$created_by = null;
			if (isset($_SESSION['logged_administrator'])) {
				$created_by = $_SESSION['logged_administrator']['employee_number'];
			} elseif (isset($_SESSION['logged_accountant'])) {
				$created_by = $_SESSION['logged_accountant']['employee_number'];
			} elseif (isset($_SESSION['logged_user'])) {
				$created_by = $_SESSION['logged_user']['employee_number'];
			}
			$po_number = $this->Purchase_order_model->generate_po_number();
			$data = [
				'po_number'                          => $po_number,
				'po_centre'                          =>  $this->input->post('po_centre') ?? null,
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
				// 'po_supporting_documents'            => $uploaded_file,
				'po_supporting_documents'            => json_encode($uploaded_files['filenames']),
				'approval_token'                     => $approval_token,
				'created_by'                         => $created_by,
				'created_at'                         => date('Y-m-d H:i:s'),
				'status'                             => '2',
			];
			$this->load->model('Purchase_order_model');
			$po_id = $this->Purchase_order_model->insert_purchase_order($data);
			// --- 4. Save Line Items to 'hms_purchase_order_items' ---
			$item_descriptions = $this->input->post('item_description');
			$item_quantities   = $this->input->post('quantity');
			$item_rates        = $this->input->post('rate');
			$item_gst_rates    = $this->input->post('item_gst_rate');
			$item_basic_totals = $this->input->post('item_basic_total');
			$item_gst_amounts  = $this->input->post('item_gst_amount');

			$items_batch_data = [];
			if (!empty($item_descriptions) && !empty($item_quantities) && !empty($item_rates)) {
				$item_count = count($item_descriptions);
				for ($i = 0; $i < $item_count; $i++) {
					// Check if required array elements exist before accessing them
					if (isset($item_descriptions[$i]) && !empty($item_descriptions[$i]) && 
						isset($item_quantities[$i]) && isset($item_rates[$i])) {
						
						// Get values with defaults
						$quantity = isset($item_quantities[$i]) ? floatval($item_quantities[$i]) : 0;
						$rate = isset($item_rates[$i]) ? floatval($item_rates[$i]) : 0;
						$gst_rate = isset($item_gst_rates[$i]) ? floatval($item_gst_rates[$i]) : 0;
						$basic_total = isset($item_basic_totals[$i]) ? floatval($item_basic_totals[$i]) : ($quantity * $rate);
						$gst_amount = isset($item_gst_amounts[$i]) ? floatval($item_gst_amounts[$i]) : ($basic_total * ($gst_rate / 100));
						
						// Calculate total amount (basic + GST)
						$total_amount = $basic_total + $gst_amount;
						
						$items_batch_data[] = [
							'po_id'             => $po_id,
							'item_description'  => $item_descriptions[$i],
							'quantity'          => $quantity,
							'rate'              => $rate,
							'gst_rate'          => $gst_rate,
							'gst_amount'        => $gst_amount,
							'total_amount'      => $total_amount
						];
					}
				}
			}
			if (!empty($items_batch_data) && $po_id) {
				$insert_result = $this->Purchase_order_model->add_po_items($items_batch_data);
				if (!$insert_result) {
					log_message('error', 'Failed to insert PO items for PO ID: ' . $po_id);
				}
			}
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
	private function _handle_multiple_uploads($field_name)
    {
        $this->load->library('upload');
        $uploaded_filenames = [];
        if (empty($_FILES[$field_name]['name'][0])) {
            return ['status' => 'success', 'filenames' => [], 'message' => 'No files uploaded.'];
        }
        $file_count = count($_FILES[$field_name]['name']);
        for ($i = 0; $i < $file_count; $i++) {
            $_FILES['userfile']['name']     = $_FILES[$field_name]['name'][$i];
            $_FILES['userfile']['type']     = $_FILES[$field_name]['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES[$field_name]['tmp_name'][$i];
            $_FILES['userfile']['error']    = $_FILES[$field_name]['error'][$i];
            $_FILES['userfile']['size']     = $_FILES[$field_name]['size'][$i];
			$dest_path = $this->config->item('upload_path');
			$config['upload_path']   = $dest_path . 'purchase_orders/';
            // $config['upload_path']   = './uploads/po_documents/'; 
            $config['allowed_types'] = 'pdf|jpg|jpeg|png|webp|gif|bmp';
            $config['max_size']      = 10240;
            $config['encrypt_name']  = TRUE; 
            $this->upload->initialize($config);
            if ($this->upload->do_upload('userfile')) {
                $upload_data = $this->upload->data();
                $uploaded_filenames[] = $upload_data['file_name'];
            } else {
                $error_message = 'Failed to upload file: ' . $_FILES['userfile']['name'] . '. Error: ' . $this->upload->display_errors('', '');
                return ['status' => 'error', 'filenames' => $uploaded_filenames, 'message' => $error_message];
            }
        }
        return ['status' => 'success', 'filenames' => $uploaded_filenames, 'message' => 'All files uploaded.'];
    }

	private function _send_po_email($approver_email, $po_number, $token, $data)
	{
		// try {
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
									<div class='po-value'>{$this->get_center_name_by_number($data['po_centre'])}</div>
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
							<p style='margin: 0 0 20px 0; color: #856404;'>Please review all details and make your approval decision</p>
							<a href='{$review_url}' class='btn-review' style='color: white; text-decoration: none;' >
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
		// } catch (Exception $e) {
		// 	log_message('error', 'PO Email Exception: ' . $e->getMessage());
		// 	return false;
		// }
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
									<div class='po-value'>{$this->get_center_name_by_number($po_data['po_centre'])}</div>
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
            
            // Check if this approver has already responded (via dashboard or email)
            if ($approver_token['status'] !== 'pending') {
                $status_text = ucfirst($approver_token['status']);
                $status_color = ($approver_token['status'] === 'approved') ? '#28a745' : '#dc3545';
                $status_icon = ($approver_token['status'] === 'approved') ? '✓' : '✗';
                
                if (ob_get_level()) {
                    ob_end_clean();
                }
                
                echo "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset=\"UTF-8\">
                    <title>Purchase Order Already {$status_text}</title>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background-color: #f5f5f5; }
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
                        <div class='status-icon'>{$status_icon}</div>
                        <div class='status-text'>Purchase Order Already {$status_text}</div>
                        <p>This purchase order has already been {$approver_token['status']} by you.</p>
                        <p>You can no longer change your decision through this email link.</p>
                        <div class='back-link'>
                            <a href='" . base_url() . "'>Go to Dashboard</a>
                        </div>
                    </div>
                </body>
                </html>";
                return;
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
                                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$this->get_center_name_by_number($po['po_centre'])}</td>
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
            
            // Check if this approver has already responded (via dashboard or email)
            if ($approver_token['status'] !== 'pending') {
                $status_text = ucfirst($approver_token['status']);
                $status_color = ($approver_token['status'] === 'approved') ? '#28a745' : '#dc3545';
                $status_icon = ($approver_token['status'] === 'approved') ? '✓' : '✗';
                
                if (ob_get_level()) {
                    ob_end_clean();
                }
                
                echo "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset=\"UTF-8\">
                    <title>Purchase Order Already {$status_text}</title>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background-color: #f5f5f5; }
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
                        <div class='status-icon'>{$status_icon}</div>
                        <div class='status-text'>Purchase Order Already {$status_text}</div>
                        <p>This purchase order has already been {$approver_token['status']} by you.</p>
                        <p>You can no longer change your decision through this email link.</p>
                        <div class='back-link'>
                            <a href='" . base_url() . "'>Go to Dashboard</a>
                        </div>
                    </div>
                </body>
                </html>";
                return;
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
                                <td>{$this->get_center_name_by_number($po['po_centre'])}</td>
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
		
		// Check GRN status for each PO
		$this->load->model('Grn_model');
		$grn_status = [];
		foreach ($data['purchase_order_result'] as $po) {
			$grn_status[$po['po_number']] = $this->Grn_model->grn_exists_for_po($po['po_number']);
		}
		$data['grn_status'] = $grn_status;
		
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

	public function bulk_update_status()
	{
		$this->load->model('Purchase_order_model');
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
			if ($this->Purchase_order_model->update_status($id, $status, $remarks)) {
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
			$message = 'Failed to update any purchase orders. Please try again.';
			$response_status = 'error';
		}
		
		return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(['status' => $response_status, 'message' => $message]));
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
			$dest_path = $this->config->item('upload_path');
			$config['upload_path']   = $dest_path . 'purchase_orders/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png|webp|gif|bmp';
			$config['max_size']      = 10240; // 10 MB
			$config['file_ext_tolower'] = TRUE;
			$config['remove_spaces'] = TRUE;
			$config['overwrite']     = FALSE;
			if (!is_dir($config['upload_path'])) {
				mkdir($config['upload_path'], 0777, true);
			}
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('payment_proof')) {
				$error = $this->upload->display_errors('', '');
				$this->session->set_flashdata('error', 'File upload failed: ' . $error);
				redirect('accounts/purchase-orders-list');
				return;
			}
			$upload_data   = $this->upload->data();
			$uploaded_file = 'purchase_orders/' . $upload_data['file_name'];
		}
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

	/**
	 * Dashboard approval for logged-in users
	 * Allows users to approve/reject POs they are assigned to approve
	 */
	public function dashboard_approve_po($po_number, $action)
	{
		$logg = checklogin();
		if($logg['status'] != true){
			header("location:" .base_url(). "");
			die();
		}

		try {
			$this->load->model('Purchase_order_model');
			
			// Get current user's email from session
			$user_email = $this->_get_current_user_email();
			if (!$user_email) {
				throw new Exception("User email not found in session.");
			}
			
			// Get the purchase order details
			$po = $this->Purchase_order_model->get_purchase_order_by_id($po_number);
			if (!$po) {
				throw new Exception("Purchase order not found.");
			}
			
			// Check if user is assigned as an approver for this PO
			if (empty($po['approver_tokens'])) {
				throw new Exception("No approvers assigned to this purchase order.");
			}
			
			$approver_tokens = json_decode($po['approver_tokens'], true);
			if (!$approver_tokens) {
				throw new Exception("Invalid approver data.");
			}
			
			// Find the user's token in the approver list
			$user_token = null;
			foreach ($approver_tokens as $token_data) {
				if ($token_data['email'] === $user_email) {
					$user_token = $token_data['token'];
					break;
				}
			}
			
			if (!$user_token) {
				throw new Exception("You are not authorized to approve this purchase order.");
			}
			
			// Check if user has already responded
			$user_token_data = $this->Purchase_order_model->get_approver_token_details($user_token);
			if (!$user_token_data) {
				throw new Exception("Invalid approval token.");
			}
			
			if ($user_token_data['status'] !== 'pending') {
				throw new Exception("You have already responded to this purchase order.");
			}
			
			// Process the approval using existing logic
			$approver_status = ($action == 'approve') ? 'approved' : 'rejected';
			$approver_updated = $this->Purchase_order_model->update_approver_token_status(
				$user_token, 
				$approver_status, 
				$action == 'approve' ? 'Approved via dashboard by ' . $user_email : 'Rejected via dashboard by ' . $user_email
			);
			
			if (!$approver_updated) {
				throw new Exception("Failed to update approval status.");
			}
			
			// Update main PO status using existing logic
			$all_tokens = json_decode($po['approver_tokens'], true);
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
			
			// Determine final status
			if ($total_approvers == 1) {
				$status = ($approved_count == 1) ? '1' : (($rejected_count == 1) ? '0' : '2');
			} else {
				$majority_threshold = ceil($total_approvers / 2);
				if ($approved_count >= $majority_threshold) {
					$status = '1';
				} elseif ($rejected_count >= $majority_threshold) {
					$status = '0';
				} else {
					$status = '2';
				}
			}
			
			// Update the main PO status
			$po_updated = $this->Purchase_order_model->update_status($po['po_number'], $status);
			if (!$po_updated) {
				throw new Exception("Failed to update PO status.");
			}
			
			// Send status update email
			$status_text = ($status == '1') ? 'Approved' : (($status == '0') ? 'Rejected' : 'Partially Approved');
			$this->_send_po_status_update_email($po, $status, $status_text);
			
			// Set success message
			$action_text = ($action == 'approve') ? 'approved' : 'rejected';
			$this->session->set_flashdata('success', "Purchase Order #{$po_number} has been {$action_text} successfully!");
			
		} catch (Exception $e) {
			log_message('error', 'Dashboard PO Approval Failed: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
		}
		
		redirect('accounts/purchase_order_list');
	}

	/**
	 * Get current user's email from session
	 */
	private function _get_current_user_email()
	{
		$logg = checklogin();
		if (!$logg['status']) {
			return null;
		}

		$role = $logg['role'];
		$session_key = 'logged_' . $role;
		
		if (isset($_SESSION[$session_key]['email'])) {
			return $_SESSION[$session_key]['email'];
		}
		
		return null;
	}

	/**
	 * Get all users for filter dropdown - only users involved in PO approvals
	 */
	private function get_all_users()
	{
		$users = [];
		
		try {
			// Get users from approver_tokens in purchase orders (only users who have been involved in PO approvals)
			$this->db->select('approver_tokens');
			$this->db->from('hms_purchase_orders');
			$this->db->where('approver_tokens IS NOT NULL');
			$this->db->where('approver_tokens !=', '');
			$query = $this->db->get();
			
			$seen_emails = [];
			foreach ($query->result_array() as $row) {
				$tokens = json_decode($row['approver_tokens'], true);
				if ($tokens) {
					foreach ($tokens as $token_data) {
						if (!empty($token_data['email']) && !in_array($token_data['email'], $seen_emails)) {
							// Try to get the user's name from employees table
							$name = $this->get_user_name_by_email($token_data['email']);
							
							$users[] = [
								'name' => $name ? $name : $token_data['email'], // Use actual name if found, otherwise use email
								'email' => $token_data['email']
							];
							$seen_emails[] = $token_data['email'];
						}
					}
				}
			}
		} catch (Exception $e) {
			log_message('error', 'Failed to get users from purchase orders: ' . $e->getMessage());
		}
		
		// Sort by name
		usort($users, function($a, $b) {
			return strcmp($a['name'], $b['name']);
		});
		
		return $users;
	}

	/**
	 * Get user name by email from employees table
	 */
	private function get_user_name_by_email($email)
	{
		try {
			$query = $this->db->select('name')
				->from('hms_employees')
				->where('email', $email)
				->limit(1)
				->get();
			
			if ($query->num_rows() > 0) {
				$result = $query->row_array();
				return $result['name'];
			}
		} catch (Exception $e) {
			log_message('error', 'Failed to get user name for email ' . $email . ': ' . $e->getMessage());
		}
		
		return null;
	}

	/**
	 * Check pending orders for any specific user
	 */
	public function check_user_pending_orders()
	{
		$user_email = $this->input->get('user_email');
		
		if (empty($user_email)) {
			$this->output->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode([
					'status' => 'error', 
					'message' => 'User email is required'
				]));
			return;
		}
		
		$this->load->model('Purchase_order_model');
		$pending_orders = $this->Purchase_order_model->get_pending_orders_for_user($user_email);
		$pending_count = count($pending_orders);
		
		$this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'status' => 'success',
				'user_email' => $user_email,
				'pending_count' => $pending_count,
				'pending_orders' => $pending_orders
			]));
	}

	/**
	 * Check user approval statistics - standalone feature
	 */
	public function user_approval_stats()
	{
		$logg = checklogin();
		if($logg['status'] != true){
			header("location:" .base_url(). "");
			die();
		}

		$data = array();
		$data['user_stats'] = null;
		$data['searched_user'] = '';
		$current_user_email = $this->_get_current_user_email();
		$filters = [
			'status_filter' => $this->input->get('status_filter'),
			'po_number' => $this->input->get('po_number')
		];
		$data['filters'] = $filters;
		$search_user = $this->input->get('user_email');
		if (empty($search_user) && !empty($current_user_email)) {
			$search_user = $current_user_email;
		}
		if (!empty($search_user)) {
			$this->load->model('Purchase_order_model');
			$data['user_stats'] = $this->Purchase_order_model->get_user_approval_stats($search_user, $filters);
			$data['searched_user'] = $search_user;
		}
		$data['all_users'] = $this->get_all_users();
		$data['current_user_email'] = $current_user_email;
		$template = get_header_template($logg['role']);
		$data['user_role'] = $logg['role'];
		$this->load->view($template['header']);
		$this->load->view('accounts/user_approval_stats', $data);
		$this->load->view($template['footer']);
	}

	/**
	 * Debug method to check database tables
	 */
	public function debug_users()
	{
		$logg = checklogin();
		if($logg['status'] != true){
			header("location:" .base_url(). "");
			die();
		}

		echo "<h3>Debug: User Tables Check</h3>";
		
		// Check hms_employees table
		echo "<h4>hms_employees table:</h4>";
		try {
			$query = $this->db->select('name, email, username, status')
				->from('hms_employees')
				->limit(10)
				->get();
			
			echo "<p>Total rows: " . $query->num_rows() . "</p>";
			if ($query->num_rows() > 0) {
				echo "<table border='1'><tr><th>Name</th><th>Email</th><th>Username</th><th>Status</th></tr>";
				foreach ($query->result_array() as $row) {
					echo "<tr><td>{$row['name']}</td><td>{$row['email']}</td><td>{$row['username']}</td><td>{$row['status']}</td></tr>";
				}
				echo "</table>";
			}
		} catch (Exception $e) {
			echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
		}
		
		// Check purchase orders for approver tokens
		echo "<h4>Purchase Orders with Approver Tokens:</h4>";
		try {
			$query = $this->db->select('po_number, approver_tokens')
				->from('hms_purchase_orders')
				->where('approver_tokens IS NOT NULL')
				->where('approver_tokens !=', '')
				->limit(5)
				->get();
			
			echo "<p>Total POs with tokens: " . $query->num_rows() . "</p>";
			foreach ($query->result_array() as $row) {
				echo "<p><strong>PO:</strong> {$row['po_number']}<br>";
				echo "<strong>Tokens:</strong> " . htmlspecialchars($row['approver_tokens']) . "</p>";
			}
		} catch (Exception $e) {
			echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
		}
		
		// Test get_all_users method
		echo "<h4>get_all_users() method result (PO Approvers Only):</h4>";
		$users = $this->get_all_users();
		echo "<p>Found " . count($users) . " users who have been involved in PO approvals</p>";
		foreach ($users as $user) {
			echo "<p>{$user['name']} - {$user['email']}</p>";
		}
	}

	/**
	 * Get pending approvals for current user
	 */
	public function get_pending_approvals()
	{
		$logg = checklogin();
		if($logg['status'] != true){
			header("location:" .base_url(). "");
			die();
		}

		$user_email = $this->_get_current_user_email();
		if (!$user_email) {
			return [];
		}

		$this->load->model('Purchase_order_model');
		
		// Get all POs where user is an approver and status is pending
		$this->db->select('*');
		$this->db->from('hms_purchase_orders');
		$this->db->like('approver_tokens', $user_email);
		$this->db->where('status', '2'); // Pending status
		$query = $this->db->get();
		$all_pos = $query->result_array();
		
		$pending_approvals = [];
		foreach ($all_pos as $po) {
			if (!empty($po['approver_tokens'])) {
				$approver_tokens = json_decode($po['approver_tokens'], true);
				if ($approver_tokens) {
					foreach ($approver_tokens as $token_data) {
						if ($token_data['email'] === $user_email && $token_data['status'] === 'pending') {
							$pending_approvals[] = $po;
							break;
						}
					}
				}
			}
		}
		
		return $pending_approvals;
	}

	/**
	 * My Approvals page - shows POs pending user's approval
	 */
	public function my_approvals()
	{
		$logg = checklogin();
		if($logg['status'] != true){
			header("location:" .base_url(). "");
			die();
		}

		$user_email = $this->_get_current_user_email();
		if (!$user_email) {
			$this->session->set_flashdata('error', 'User email not found in session.');
			redirect('dashboard');
		}

		// Get pending approvals for current user
		$pending_approvals = $this->get_pending_approvals();
		
		$data = array();
		$data['pending_approvals'] = $pending_approvals;
		$data['user_email'] = $user_email;
		
		$template = get_header_template($logg['role']);
		$this->load->view($template['header']);
		$this->load->view('accounts/my_approvals', $data);
		$this->load->view($template['footer']);
	}
   
	public function daily_sales_reporting(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			//$per_page = $this->input->get('per_page', true);
			$center_number = $this->input->get('billing_at', true);
			$payment_method = $this->input->get('payment_method', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			
			$config = array();
        	$data['medicine_daily_result'] = $this->accounts_model->dashboard_medicine_daily_sales($center, $start_date, $end_date);
			$data['investigations_daily_result'] = $this->accounts_model->dashboard_investigation_daily_sales($center, $start_date, $end_date);
			$data['advance_daily_result'] = $this->accounts_model->dashboard_advance_daily_sales($center, $start_date, $end_date);
			$data['consultation_daily_result'] = $this->accounts_model->dashboard_consultation_daily_sales($center, $start_date, $end_date);
			$data['registration_daily_result'] = $this->accounts_model->dashboard_registration_daily_sales($center, $start_date, $end_date);
			$data['procedure_daily_result'] = $this->accounts_model->dashboard_procedure_daily_sales($center, $start_date, $end_date);
			$data['partial_daily_result'] = $this->accounts_model->dashboard_partial_daily_sales($center, $start_date, $end_date);
			$data['patient_procedure_daily_result'] = $this->accounts_model->dashboard_procedure_reports_list_patination($center, $start_date, $end_date);
			$data['patient_partial_daily_result'] = $this->accounts_model->dashboard_partial_daily_sales_report($center, $start_date, $end_date);
			$data['patient_medicine_daily_result'] = $this->accounts_model->dashboard_medicine_reports_list_patination($center, $start_date, $end_date);
			$data['patient_diagnostic_daily_result'] = $this->accounts_model->dashboard_diagnostic_reports_list_patination($center, $start_date, $end_date);
			$data['patient_consultation_daily_result'] = $this->accounts_model->dashboard_consultation_reports_list_patination($center, $start_date, $end_date);
			$data['patient_registration_daily_result'] = $this->accounts_model->dashboard_registration_daily_result_patination($center, $start_date, $end_date);
			$data['patient_advance_daily_result'] = $this->accounts_model->dashboard_advance_reports_list_patination($center, $start_date, $end_date);
			

			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["payment_method"] = $payment_method;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/daily_sales_reporting', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

// 1. SAVE DRAFT (For Billing Manager)
public function save_draft_report() {
    $logg = checklogin();
    if(!$logg['status']) { echo json_encode(['success'=>false, 'message'=>'Login required']); return; }

    // Prepare Data
    $data = $this->_get_post_report_data(); // Helper function below
    $data['approval_status'] = 'Pending';   // Set status to Pending
    
    if ($this->accounts_model->save_daily_sales_report($data)) {
        echo json_encode(['success' => true, 'message' => 'Report saved as Draft (Pending Approval).']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database Error. Could not save.']);
    }
}

// 2. APPROVE & SEND (For Counsellor)
public function approve_and_send_email() {
    $logg = checklogin();
    
    // Security Check: Only Counsellors can approve
    if (!isset($_SESSION['logged_counselor'])) {
        echo json_encode(['success' => false, 'message' => 'Permission Denied: Only Counsellors can approve.']);
        return;
    }

    // A. Save/Update the Report as APPROVED
    $data = $this->_get_post_report_data();
    $data['approval_status'] = 'Approved';
    $data['approved_by']     = $_SESSION['logged_counselor']['name'];
    $data['approved_at']     = date('Y-m-d H:i:s');

    if (!$this->accounts_model->save_daily_sales_report($data)) {
        echo json_encode(['success' => false, 'message' => 'Database Error during approval.']);
        return;
    }

    // B. Send the Email (Now that it is approved)
    $recipient_email = $this->input->post('recipient_email');
    $email_subject   = $this->input->post('email_subject');
    
    // Get data for email view
    $email_data['report_data']  = $_POST; // Use POST data for the view logic
    $email_data['details_html'] = urldecode($this->input->post('details_html'));

    $email_body = $this->load->view('emails/daily_report_template', $email_data, TRUE);

    $this->load->library('email');
    $this->email->from('ranjeet.kumar@indiaivf.in', 'IndiaIVF Reports'); // Use your config email
    $this->email->to($recipient_email);
    $this->email->subject("APPROVED: " . $email_subject); // Add APPROVED prefix
    $this->email->message($email_body);
    $this->email->set_mailtype("html");

    if ($this->email->send()) {
        echo json_encode(['success' => true, 'message' => 'Report Approved, Saved, and Email Sent!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Approved & Saved, but Email Failed: ' . $this->email->print_debugger()]);
    }
}

// Helper to collect POST data (prevents repeating code)
private function _get_post_report_data() {
    $center = isset($_SESSION['logged_billing_manager']['center']) ? $_SESSION['logged_billing_manager']['center'] : $_SESSION['logged_counselor']['center'];
    return array(
        'report_date' => $this->input->post('start_date') ? $this->input->post('start_date') : date('Y-m-d'),
        'center'      => $center,
        'ivf_cycles_sold_c_count' => $this->input->post('ivf_cycles_sold_c_count'),
        'ivf_cycles_sold_b_count' => $this->input->post('ivf_cycles_sold_b_count'),
        'ivf_cycles_sold_amount'  => $this->input->post('ivf_cycles_sold_amount'),
        'ivf_with_bed_c_count' => $this->input->post('ivf_with_bed_c_count'),
        'ivf_with_bed_b_count' => $this->input->post('ivf_with_bed_b_count'),
        'ivf_with_bed_amount'  => $this->input->post('ivf_with_bed_amount'),
        'non_ivf_with_bed_c_count' => $this->input->post('non_ivf_with_bed_c_count'),
        'non_ivf_with_bed_b_count' => $this->input->post('non_ivf_with_bed_b_count'),
        'non_ivf_with_bed_amount'  => $this->input->post('non_ivf_with_bed_amount'),
        'non_ivf_without_bed_c_count' => $this->input->post('non_ivf_without_bed_c_count'),
        'non_ivf_without_bed_b_count' => $this->input->post('non_ivf_without_bed_b_count'),
        'non_ivf_without_bed_amount'  => $this->input->post('non_ivf_without_bed_amount'),
        'not_tagged_c_count' => $this->input->post('not_tagged_c_count'),
        'not_tagged_b_count' => $this->input->post('not_tagged_b_count'),
        'not_tagged_amount'  => $this->input->post('not_tagged_amount'),
        'package_customer_count' => $this->input->post('package_customer_count'),
        'package_bill_count'     => $this->input->post('package_bill_count'),
        'package_amount'         => $this->input->post('package_amount'),
        'medicine_customer_count' => $this->input->post('medicine_customer_count'),
        'medicine_bill_count'     => $this->input->post('medicine_bill_count'),
        'medicine_amount'         => $this->input->post('medicine_amount'),
        'diagnosis_customer_count' => $this->input->post('diagnosis_customer_count'),
        'diagnosis_bill_count'     => $this->input->post('diagnosis_bill_count'),
        'diagnosis_amount'         => $this->input->post('diagnosis_amount'),
        'consultation_customer_count' => $this->input->post('consultation_customer_count'),
        'consultation_bill_count'     => $this->input->post('consultation_bill_count'),
        'consultation_amount'         => $this->input->post('consultation_amount'),
    );
}	

public function get_doctors_by_center() {
    // Enable CORS if needed
    header('Content-Type: application/json');
    
    $logg = checklogin();
    if ($logg['status'] != true) {
        echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
        exit;
    }
    
    // Get center_id from POST
    $center_id = $this->input->post('center_id');
    
    // Debug: Log the received data
    error_log("Received center_id: " . $center_id);
    
    if (empty($center_id)) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Center ID required',
            'received_data' => $_POST // Debug info
        ]);
        exit;
    }
    
    // Load your model
    $this->load->model('accounts_model');
    
    try {
        $doctors = $this->accounts_model->get_doctors_by_center($center_id);
        
        if ($doctors && count($doctors) > 0) {
            echo json_encode([
                'status' => 'success',
                'doctors' => $doctors,
                'count' => count($doctors)
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'doctors' => [],
                'message' => 'No doctors found for this center',
                'center_id' => $center_id // Debug info
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }
    exit;
}

public function save_advance_payment()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'save_advance_payment'){
				unset($_POST['action']);
				$data = $this->accounts_model->advance_payment_insert($_POST);
				if($data > 0){
					header("location:" .base_url(). "accounts/save_advance_payment?m=".base64_encode('Item added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "accounts/save_advance_payment?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/save_advance_payment', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}


	public function advance_payment_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$training_name = $this->input->get('training_name', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/advance_payment_list";
        	$config["total_rows"] = $this->accounts_model->advance_payment_count($training_name);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['consultation_cancel_result'] = $this->accounts_model->advance_payment_pagination($config["per_page"], $per_page, $training_name);
			$data["training_name"] = $training_name;
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/advance_payment_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	


public function send_daily_report_email()
{
    // 1. Get inputs
    $raw_recipients = $this->input->post('recipient_email');
    $email_subject  = $this->input->post('email_subject');

    // 2. Prepare Data & View (Do this ONCE to save performance)
    $data['report_data']  = $_POST;
    $data['details_html'] = urldecode($this->input->post('details_html'));
    
    // Generate the HTML body once
    $email_body = $this->load->view('emails/daily_report_template', $data, TRUE);

    // 3. Process Recipients
    // Split the string by comma into an array
    $recipient_list = explode(',', $raw_recipients);
    
    $sent_count = 0;
    $failed_emails = [];

    // 4. Loop through each email and send individually
    foreach ($recipient_list as $email) {
        $email = trim($email); // Remove spaces (Important!)
        
        if (!empty($email)) {
            // Send the email to this specific user
            $result = send_mail($email, $email_subject, $email_body);
            
            if ($result) {
                $sent_count++;
            } else {
                $failed_emails[] = $email;
            }
        }
    }

    // 5. Return Response
    if ($sent_count > 0) {
        $response = [
            'success'   => true,
            'message'   => "Report sent to $sent_count recipients.",
            'recipient' => $raw_recipients,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Failed to send email to any recipient.',
            'failed'  => $failed_emails
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

/*
// Add this helper method to get patient names
private function get_patient_name($patient_id) {
    // Implement your patient name lookup logic here
    // This should match your existing get_patient_name method
    // Example: return $this->Your_model->get_patient_name_by_id($patient_id);
    return "Patient Name"; // Replace with actual implementation
}*/
    /**
     * Handles the export of purchase orders to a CSV file.
     * It uses the same filters as the purchase_order_list page.
     */
    public function export_purchase_orders()
    {
        // Check for login, permissions, etc. if necessary
        // if (!checklogin()['status']) {
        //     redirect('login');
        // }

        $this->load->model('Purchase_order_model');
        $filters = [
            'status'                   => $this->input->get('status'),
            'start_date'               => $this->input->get('start_date'),
            'end_date'                 => $this->input->get('end_date'),
            'po_centre'                => $this->input->get('po_centre'),
            'po_department'            => $this->input->get('po_department'),
            'po_nature_of_expenditure' => $this->input->get('po_nature_of_expenditure'),
            'approval_status'          => $this->input->get('approval_status'), // Kept for completeness
        ];
        $purchase_orders = $this->Purchase_order_model->get_all_purchase_orders($filters);
        $filename = "purchase_orders_export_" . date('Y-m-d') . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');
        $headers = [
            'PO Number',
            'Centre',
            'Department',
            'Nature Of Expenditure',
            'Budget Head',
            'Budget Item',
            'Vendor',
            'Basic Amount (Ex GST)',
            'GST Amount',
            'Other Charges & Taxes',
            'PO Total (Inc GST)',
            'Approval Status',
            'Approvers',
            'Final Status (Manual)'
        ];
        fputcsv($output, $headers);
        if (!empty($purchase_orders)) {
            foreach ($purchase_orders as $po) {
                $approval_status_info = $this->_get_approval_status_summary($po['approver_tokens']);
                $approvers_list = $this->_get_approvers_list($po['approver_tokens']);
                $manual_status = $this->_get_manual_status_text($po['status']);

                $row = [
                    $po['po_number'],
                    $po['po_centre'],
                    $po['po_department'],
                    $po['po_nature_of_expenditure'],
                    $po['po_budget_head'],
                    $po['po_budget_item'],
                    $po['po_name_of_vendor'],
                    $po['po_basic_amount'],
                    $po['po_gst_amount'],
                    $po['po_other_charges_and_taxes'],
                    number_format($po['po_po_total'], 2),
                    $approval_status_info, // e.g., "Approved (3/3)"
                    $approvers_list,       // e.g., "user1@example.com (Approved), user2@example.com (Pending)"
                    $manual_status         // e.g., "Approved"
                ];
                fputcsv($output, $row);
            }
        }
        fclose($output);
        exit;
    }
    private function _get_approval_status_summary($approver_tokens_json)
    {
        if (empty($approver_tokens_json)) {
            return 'No Approvers';
        }
        $approvers = json_decode($approver_tokens_json, true);
        if (empty($approvers)) {
            return 'No Approvers';
        }
        $pending_count = 0;
        $approved_count = 0;
        $rejected_count = 0;
        $total_approvers = count($approvers);
        foreach ($approvers as $token_data) {
            if ($token_data['status'] === 'pending') $pending_count++;
            elseif ($token_data['status'] === 'approved') $approved_count++;
            elseif ($token_data['status'] === 'rejected') $rejected_count++;
        }

        if ($rejected_count > 0) {
            return 'Rejected (' . $rejected_count . ' of ' . $total_approvers . ')';
        } elseif ($pending_count > 0) {
            return 'Pending (' . $approved_count . '/' . $total_approvers . ' Approved)';
        } else {
            return 'All Approved (' . $total_approvers . '/' . $total_approvers . ')';
        }
    }
    private function _get_approvers_list($approver_tokens_json)
    {
        if (empty($approver_tokens_json)) {
            return 'N/A';
        }
        $approvers = json_decode($approver_tokens_json, true);
        if (empty($approvers)) {
            return 'N/A';
        }
        
        $list = [];
        foreach ($approvers as $token_data) {
            $list[] = $token_data['email'] . ' (' . ucfirst($token_data['status']) . ')';
        }
        return implode('; ', $list);
    }
    private function _get_manual_status_text($status_code)
    {
        switch ($status_code) {
            case '1': return 'Approved';
            case '0': return 'Disapproved';
            case '2': return 'Pending';
            default: return 'Unknown';
        }
    }

    // ==================== GRN (Goods Receipt Note) Methods ====================

    /**
     * Display GRN list page
     */
    public function grn_list()
    {
        $logg = checklogin();
        if ($logg['status'] != true) {
            header("location:" . base_url() . "");
            die;
        }

        $this->load->model('Grn_model');
        $filters = [
            'grn_number' => $this->input->get('grn_number'),
            'po_number' => $this->input->get('po_number'),
            'vendor_name' => $this->input->get('vendor_name'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'status' => $this->input->get('status'),
        ];

        $per_page = $this->input->get('per_page', true) ?: 0;
        $config["base_url"] = base_url("accounts/grn-list");
        $config["total_rows"] = $this->Grn_model->grn_count($filters);
        $config["per_page"] = 20;
        $config["page_query_string"] = true;
        $this->pagination->initialize($config);
        $data["links"] = $this->pagination->create_links();
        $data['grn_result'] = $this->Grn_model->grn_pagination($config["per_page"], $per_page, $filters);
        $data['filters'] = $filters;
        $template = get_header_template(checklogin()['role']);
        $data['user_role'] = checklogin()['role'];
        $this->load->view($template['header']);
        $this->load->view('accounts/grn_list', $data);
        $this->load->view($template['footer']);
    }

    /**
     * Display form to create GRN from Purchase Order
     */
    public function add_grn($po_number)
    {
        $logg = checklogin();
        if ($logg['status'] != true) {
            header("location:" . base_url() . "");
            die;
        }

        $this->load->model('Purchase_order_model');
        $this->load->model('Grn_model');

        // Get purchase order details
        $purchase_order = $this->Purchase_order_model->get_purchase_order_by_id($po_number);
        if (!$purchase_order) {
            $this->session->set_flashdata('error', 'Purchase Order not found!');
            redirect('accounts/purchase-orders-list');
            return;
        }

        // Check if PO is approved
        $is_approved = false;
        if (!empty($purchase_order['approver_tokens'])) {
            $approver_tokens = json_decode($purchase_order['approver_tokens'], true);
            if ($approver_tokens) {
                $all_approved = true;
                $any_rejected = false;
                foreach ($approver_tokens as $token_data) {
                    if ($token_data['status'] === 'pending') {
                        $all_approved = false;
                    } elseif ($token_data['status'] === 'rejected') {
                        $any_rejected = true;
                    }
                }
                $is_approved = $all_approved && !$any_rejected;
            }
        } else {
            // Fallback: check status field
            $is_approved = ($purchase_order['status'] == '1');
        }

        if (!$is_approved) {
            $this->session->set_flashdata('error', 'GRN can only be created for approved Purchase Orders!');
            redirect('accounts/purchase-orders-list');
            return;
        }

        // Check if GRN already exists for this PO
        if ($this->Grn_model->grn_exists_for_po($po_number)) {
            $this->session->set_flashdata('warning', 'GRN already exists for this Purchase Order!');
            redirect('accounts/grn-list');
            return;
        }

        // Get PO items
        $po_items = $this->Grn_model->get_po_items($po_number);

        $data['purchase_order'] = $purchase_order;
        $data['po_items'] = $po_items;
        $data['grn_number'] = $this->Grn_model->generate_grn_number();

        $template = get_header_template(checklogin()['role']);
        $this->load->view($template['header']);
        $this->load->view('accounts/add_grn', $data);
        $this->load->view($template['footer']);
    }

    /**
     * Save GRN
     */
    public function save_grn()
    {
        $logg = checklogin();
        if ($logg['status'] != true) {
            header("location:" . base_url() . "");
            die;
        }

        $this->load->model('Grn_model');
        $this->load->model('Purchase_order_model');

        $po_number = $this->input->post('po_number');
        $purchase_order = $this->Purchase_order_model->get_purchase_order_by_id($po_number);

        if (!$purchase_order) {
            $this->session->set_flashdata('error', 'Purchase Order not found!');
            redirect('accounts/purchase-orders-list');
            return;
        }

        // Get current user
        $created_by = '';
        $logg = checklogin();
        if ($logg['status']) {
            $role = $logg['role'];
            $session_key = 'logged_' . $role;
            if (isset($_SESSION[$session_key]['employee_number'])) {
                $created_by = $_SESSION[$session_key]['employee_number'];
            }
        }

        // Prepare GRN data
        $grn_data = [
            'grn_number' => $this->input->post('grn_number'),
            'po_number' => $po_number,
            'po_id' => $purchase_order['id'],
            'grn_date' => $this->input->post('grn_date'),
            'vendor_name' => $purchase_order['po_name_of_vendor'],
            'centre' => $purchase_order['po_centre'],
            'department' => $purchase_order['po_department'],
            'received_by' => $this->input->post('received_by'),
            'inspected_by' => $this->input->post('inspected_by'),
            'remarks' => $this->input->post('remarks'),
            'total_items' => $this->input->post('total_items'),
            'total_quantity' => $this->input->post('total_quantity'),
            'status' => '1', // Active
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Insert GRN
        $grn_inserted = $this->Grn_model->insert_grn($grn_data);
         if (!$grn_inserted) {
            $this->session->set_flashdata('error', 'Failed to save GRN. Please try again.');
            redirect('accounts/purchase-orders-list');
            return;
        }

        // Get the inserted GRN ID
        $grn_id = $this->db->insert_id();

        // Save GRN items
        $item_descriptions = $this->input->post('item_description');
        $item_quantities = $this->input->post('quantity');
        $item_received_quantities = $this->input->post('received_quantity');
        $item_rates = $this->input->post('rate');
        $item_gst_rates = $this->input->post('gst_rate');
        $item_basic_totals = $this->input->post('basic_total');
        $item_gst_amounts = $this->input->post('gst_amount');
        $item_conditions = $this->input->post('item_condition');

        $items_batch_data = [];
        if (!empty($item_descriptions)) {
            for ($i = 0; $i < count($item_descriptions); $i++) {
                if (!empty($item_descriptions[$i])) {
                    $items_batch_data[] = [
                        'grn_id' => $grn_id,
                        'po_number' => $po_number,
                        'item_description' => $item_descriptions[$i],
                        'quantity' => $item_quantities[$i] ?? 0,
                        'received_quantity' => $item_received_quantities[$i] ?? 0,
                        'rate' => $item_rates[$i] ?? 0,
                        'gst_rate' => $item_gst_rates[$i] ?? 0,
                        'basic_total' => $item_basic_totals[$i] ?? 0,
                        'gst_amount' => $item_gst_amounts[$i] ?? 0,
                        'item_condition' => $item_conditions[$i] ?? 'Good',
                    ];
                }
            }
        }

        if (!empty($items_batch_data)) {
            $this->Grn_model->insert_grn_items($items_batch_data);
        }

        $this->session->set_flashdata('success', 'GRN created successfully!');
        redirect('accounts/grn-list');
    }

    /**
     * View GRN details
     */
    public function view_grn($grn_id)
    {
        $logg = checklogin();
        if ($logg['status'] != true) {
            header("location:" . base_url() . "");
            die;
        }

        $this->load->model('Grn_model');
        $grn = $this->Grn_model->get_grn_by_id($grn_id);
        
        if (!$grn) {
            $this->session->set_flashdata('error', 'GRN not found!');
            redirect('accounts/grn-list');
            return;
        }

        $grn_items = $this->Grn_model->get_grn_items($grn_id);
        $data['grn'] = $grn;
        $data['grn_items'] = $grn_items;

        $template = get_header_template(checklogin()['role']);
        $this->load->view($template['header']);
        $this->load->view('accounts/view_grn', $data);
        $this->load->view($template['footer']);
    }

    /**
     * Print Purchase Order
     */
    public function print_purchase_order($po_id)
    {
        $logg = checklogin();
        if ($logg['status'] != true) {
            header("location:" . base_url() . "");
            die;
        }

        $this->load->model('Purchase_order_model');
        $po = $this->Purchase_order_model->get_purchase_order_by_print($po_id);
        
        if (!$po) {
            $this->session->set_flashdata('error', 'Purchase Order not found!');
            redirect('accounts/purchase-orders-list');
            return;
        }

        // Check if PO is approved (for printing)
        $can_print = false;
        if (!empty($po['approver_tokens'])) {
            $approver_tokens = json_decode($po['approver_tokens'], true);
            if ($approver_tokens) {
                $all_approved = true;
                $any_rejected = false;
                foreach ($approver_tokens as $token_data) {
                    if ($token_data['status'] === 'pending') {
                        $all_approved = false;
                    } elseif ($token_data['status'] === 'rejected') {
                        $any_rejected = true;
                    }
                }
                $can_print = $all_approved && !$any_rejected;
            }
        } else {
            // Fallback: check status field
            $can_print = ($po['status'] == '1');
        }

        if (!$can_print) {
            $this->session->set_flashdata('error', 'Only approved Purchase Orders can be printed!');
            redirect('accounts/purchase-orders-list');
            return;
        }

        // Get purchase order items
        $po_items = $this->Purchase_order_model->get_purchase_order_items_by_number($po['po_number']);
        
        $data['po'] = $po;
        $data['po_items'] = $po_items;
        $this->load->view('accounts/print_purchase_order_view', $data);
    }

	public function patient_final_billing() 
	{
		$logg = checklogin();
		if($logg['status'] != true){
			header("location:" .base_url(). "");
			die();
		}
		
		$data = array();
		$data['all_centers'] = $this->get_all_centers();
		$template = get_header_template($logg['role']);
		$data['user_role'] = $logg['role'];
		if(isset($_GET['patient_id']) && !empty($_GET['patient_id'])) {
			$patient_id = $_GET['patient_id'];
			$billing_data = $this->accounts_model->get_patient_final_billing_data($patient_id);
			
			if($billing_data) {
				$data['billing_data'] = $billing_data;
				$data['show_bill'] = true;
			} else {
				$data['error'] = 'Patient not found or no billing data available.';
			}
		}
		
		$this->load->view($template['header']);
		$this->load->view('accounts/patient_final_billing', $data);
		$this->load->view($template['footer']);
	}
		/**
	 * Generate final bill for a patient
	 */
	public function generate_final_bill($patient_id) 
	{
		$logg = checklogin();
		if($logg['status'] != true){
			redirect(base_url());
			return;
		}
		
		$billing_data = $this->accounts_model->get_patient_final_billing_data($patient_id);
		
		if(!$billing_data) {
			redirect(base_url() . "accounts/patient_final_billing?error=" . urlencode('Patient not found or no billing data available.'));
			return;
		}
		
		// Check if all procedures are fully paid
		if(!$billing_data['payment_status']['all_paid']) {
			redirect(base_url() . "accounts/patient_final_billing?error=" . urlencode('Cannot generate final bill. Patient has outstanding payments.'));
			return;
		}
		
		$data['billing_data'] = $billing_data;
		$data['show_bill'] = true;
		
		$this->load->view('accounts/patient_final_billing', $data);
	}
	
		/**
	 * Check if patient is eligible for final billing
	 */
	public function check_final_billing_eligibility() 
	{
		$logg = checklogin();
		if($logg['status'] != true){
			echo json_encode(array('status' => false, 'message' => 'Unauthorized'));
			return;
		}
		
		$patient_id = $this->input->post('patient_id');
		
		if(empty($patient_id)) {
			echo json_encode(array('status' => false, 'message' => 'Patient ID is required'));
			return;
		}
		
		$billing_data = $this->accounts_model->get_patient_final_billing_data($patient_id);
		
		if(!$billing_data) {
			echo json_encode(array('status' => false, 'message' => 'Patient not found or no billing data available'));
			return;
		}
		
		$payment_status = $billing_data['payment_status'];
		
		$response = array(
			'status' => true,
			'eligible' => $payment_status['all_paid'],
			'total_amount' => $payment_status['total_amount'],
			'total_paid' => $payment_status['total_paid'],
			'remaining_amount' => $payment_status['total_remaining'],
			'procedures_count' => count($payment_status['procedures']),
			'message' => $payment_status['all_paid'] ? 'Patient is eligible for final billing' : 'Patient has outstanding payments'
		);
		
		echo json_encode($response);
	}
	/**
	 * Search patients for final billing
	 */
	public function search_patients_for_final_billing() 
	{
		$logg = checklogin();
		if($logg['status'] != true){
			echo json_encode(array('status' => false, 'message' => 'Unauthorized'));
			return;
		}
		
		$search_term = $this->input->post('search_term');
		
		if(empty($search_term)) {
			echo json_encode(array('status' => false, 'message' => 'Search term is required'));
			return;
		}
		
		// Search patients by ID, name, or phone
		$this->db->select('patient_id, wife_name, husband_name, wife_phone');
		$this->db->from('hms_patients');
		$this->db->where("(patient_id LIKE '%$search_term%' OR wife_name LIKE '%$search_term%' OR husband_name LIKE '%$search_term%' OR wife_phone LIKE '%$search_term%')");
		$this->db->limit(10);
		$query = $this->db->get();
		
		$patients = $query->result_array();
		
		echo json_encode(array('status' => true, 'patients' => $patients));
	}
	
	
	/**
	 * Get detailed procedure information for a patient
	 */
	public function get_patient_procedure_details() 
	{
		$logg = checklogin();
		if($logg['status'] != true){
			echo json_encode(array('status' => false, 'message' => 'Unauthorized'));
			return;
		}
		
		$patient_id = $this->input->post('patient_id');
		
		if(empty($patient_id)) {
			echo json_encode(array('status' => false, 'message' => 'Patient ID is required'));
			return;
		}
		
		$billing_data = $this->accounts_model->get_patient_final_billing_data($patient_id);
		
		if(!$billing_data) {
			echo json_encode(array('status' => false, 'message' => 'Patient not found or no billing data available'));
			return;
		}
		
        // Get all payments for this patient to calculate actual paid amounts
        $this->db->select('data, payment_done, on_date');
        $this->db->from('hms_patient_payments');
        $this->db->where('patient_id', $patient_id);
        $this->db->where('status', 0); // Only approved payments
        $payments_query = $this->db->get();
        $all_payments = $payments_query->result_array();
        
        // Group payments by procedure code
        $procedure_payments = array();
        foreach ($all_payments as $payment) {
            $payment_data = unserialize($payment['data']);
            if (isset($payment_data['patient_procedures'])) {
                foreach ($payment_data['patient_procedures'] as $proc) {
                    $code = $proc['sub_procedures_code'];
                    if (!isset($procedure_payments[$code])) {
                        $procedure_payments[$code] = array(
                            'total_paid' => 0,
                            'payments' => array()
                        );
                    }
                    $procedure_payments[$code]['total_paid'] += $payment['payment_done'];
                    $procedure_payments[$code]['payments'][] = array(
                        'amount' => $payment['payment_done'],
                        'date' => $payment['on_date']
                    );
                }
            }
        }
        
        $procedures = array();
        foreach ($billing_data['payment_status']['procedures'] as $procedure) {
            $procedure_data = unserialize($procedure['data']);
            if (isset($procedure_data['patient_procedures'])) {
                foreach ($procedure_data['patient_procedures'] as $proc) {
                    // Get procedure name from hms_procedures table
                    $procedure_name = isset($proc['sub_procedure']) ? $proc['sub_procedure'] : 'Unknown Procedure';
                    if (!empty($proc['sub_procedures_code'])) {
                        $this->db->select('procedure_name');
                        $this->db->from('hms_procedures');
                        $this->db->where('code', $proc['sub_procedures_code']);
                        $proc_query = $this->db->get();
                        if ($proc_query->num_rows() > 0) {
                            $proc_result = $proc_query->row_array();
                            $procedure_name = $proc_result['procedure_name'];
                        }
                    }

                    // Handle null/empty values
                    $price = !empty($proc['sub_procedures_price']) ? floatval($proc['sub_procedures_price']) : 0;
                    $discount = !empty($proc['sub_procedures_discount']) ? floatval($proc['sub_procedures_discount']) : 0;
                    
                    // Get actual total paid from payments table
                    $code = $proc['sub_procedures_code'];
                    $actual_paid = isset($procedure_payments[$code]) ? $procedure_payments[$code]['total_paid'] : 0;

                    // Skip procedures with invalid data
                    if ($price == 0 && $discount == 0 && $actual_paid == 0) {
                        continue;
                    }

                    $net_price = $price - $discount;
                    $remaining = $net_price - $actual_paid;

                    $procedures[] = array(
                        'procedure_name' => $procedure_name,
                        'procedure_code' => !empty($proc['sub_procedures_code']) ? $proc['sub_procedures_code'] : 'N/A',
                        'price' => $price,
                        'discount' => $discount,
                        'paid_price' => $actual_paid,
                        'billing_id' => $procedure['billing_id'],
                        'on_date' => $procedure['on_date'],
                        'remaining_amount' => $remaining,
                        'total_package' => $procedure['totalpackage'],
                        'payment_done' => $procedure['payment_done'],
                        'status' => $procedure['status']
                    );
                }
            }
        }
		
		echo json_encode(array('status' => true, 'procedures' => $procedures));
	}


function assessment_form($patient_id = 0){ // Keep the = 0 fix from before
    $logg = checklogin();
    if($logg['status'] == true){
        if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'add_assessment_form'){
            unset($_POST['action']);
            
            // FIX: Pass $patient_id as the first argument
            $data = $this->accounts_model->assessment_form_insert_payment($patient_id, $_POST);
            
            if($data > 0){
                header("location:" .base_url(). "accounts/assessment_form_list/");
                die();
            }else{
                header("location:" .base_url(). "accounts/assessment_form?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
                die();
            }               
        }
        $data = array();
        $template = get_header_template($logg['role']);
        $this->load->view($template['header']);
        
        if($patient_id > 0){
            $data['data'] = $this->accounts_model->get_assessment_appointment_form($patient_id);
        } else {
            $data['data'] = array();
        }
        
        $this->load->view('accounts/assessment_form', $data);
        $this->load->view($template['footer']);
    }else{
        header("Location: " . base_url() . "accounts/assessment_form");
        die();
    }
}

	public function assessment_form_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$patient_id = $this->input->get('patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "accounts/assessment_form_list";
        	$config["total_rows"] = $this->accounts_model->assessment_form_count($patient_id);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['wallet_result'] = $this->accounts_model->assessment_form_pagination($config["per_page"], $per_page, $patient_id);
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/assessment_form_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function assessment_form_details(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('accounts/assessment_form_details', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}


	public function yearly_monthly_details(){
	$logg = checklogin();
    $year   = $this->input->get('year');
 
	if (empty($year)) {
        show_error('Invalid Year');
    }
	// ROLE CHECK
    if (!empty($_SESSION['logged_doctor']['center'])) {
        // Doctor login
        $center = $_SESSION['logged_doctor']['center'];
    } else {
        // Admin login → ALL centers
        $center = null;
    }

    $data['year']   = $year;
    $data['center'] = $center;

    $data['consult_monthly'] = $this->accounts_model->monthly_consultation($center, $year);

	$data['first_appointment_monthly'] = $this->accounts_model->monthly_first_appointment($center, $year);

    $data['stem_monthly'] = $this->accounts_model->monthly_ovarian_stem($center, $year);

	$data['ovarian_prp_monthly'] = $this->accounts_model->monthly_ovarian_prp($center, $year);	

    $data['testi_monthly'] = $this->accounts_model->monthly_testicular_stem($center, $year);

	$data['ovum_pickup_monthly'] = $this->accounts_model->monthly_ovum_pickup($center, $year);
	
	$data['embryo_transfer_monthly'] = $this->accounts_model->monthly_embryo_transfer($center, $year);

	$data['fet_monthly'] = $this->accounts_model->monthly_fet($center, $year);

	$data['iui_monthly'] = $this->accounts_model->iui_monthly($center, $year);

	$data['ivf_monthly'] = $this->accounts_model->ivf_monthly($center, $year);

	$data['icsi_monthly'] = $this->accounts_model->icsi_monthly($center, $year);

	$data['tesa_mtesa_monthly'] = $this->accounts_model->tesa_mtesa_monthly($center, $year);

	$data['testicular_prp_monthly'] = $this->accounts_model->testicular_prp_monthly($center, $year);

	$data['Sperm_Mobil_monthly'] = $this->accounts_model->Sperm_Mobil_monthly($center, $year);

	$data['Blastocyst_monthly'] = $this->accounts_model->Blastocyst_monthly($center, $year);

	$data['lah_monthly'] = $this->accounts_model->lah_monthly($center, $year);

	$data['Embryo_Glue_monthly'] = $this->accounts_model->Embryo_Glue_monthly($center, $year);
	$template = get_header_template($logg['role']);
	$this->load->view($template['header']);
    $this->load->view('accounts/yearly_monthly_details', $data);
	$this->load->view($template['footer']);
}
/*
public function yearly_monthly_details(){
    $logg = checklogin();
    if ($logg['status'] != true) {
        redirect(base_url());
        return;
    }

    $year   = $this->input->get('year');
    $center = $_SESSION['logged_doctor']['center'] ?? null;

    /* ==========================
       MONTHLY CONSULTATION
    ===========================*/
  /*  $sql_consult = "
        SELECT 
            MONTH(a.appoitmented_date) AS month,
            c.center_name,
            COUNT(*) AS total
        FROM hms_appointments a
        JOIN hms_centers c ON c.center_number = a.appoitment_for
        WHERE a.status = 'consultation_done'
        AND YEAR(a.appoitmented_date) = ?
        " . ($center ? "AND a.appoitment_for = ?" : "") . "
        GROUP BY MONTH(a.appoitmented_date), c.center_name
    ";

    $params = [$year];
    if ($center) $params[] = $center;

    $data['consult_monthly'] = $this->db->query($sql_consult, $params)->result_array();

    /* ==========================
       OVARIAN STEM CELL
    ===========================*/
   /* $sql_stem = "
        SELECT 
            MONTH(date_of_procedure) AS month,
            COUNT(*) AS total
        FROM ovarian_prp_discharge_summary
        WHERE procedure_name = 'STEM CELL'
        AND YEAR(date_of_procedure) = ?
        " . ($center ? "AND center = ?" : "") . "
        GROUP BY MONTH(date_of_procedure)
    ";

    $params = [$year];
    if ($center) $params[] = $center;

    $data['stem_monthly'] = $this->db->query($sql_stem, $params)->result_array();

    /* ==========================
       TESTICULAR STEM CELL
    ===========================*/
   /* $sql_testi = "
        SELECT 
            MONTH(date_of_procedure) AS month,
            COUNT(*) AS total
        FROM testicular_prp_discharge_summary
        WHERE procedures = 'Testicular Stem Cell'
        AND YEAR(date_of_procedure) = ?
        " . ($center ? "AND center = ?" : "") . "
        GROUP BY MONTH(date_of_procedure)
    ";

    $params = [$year];
    if ($center) $params[] = $center;

    $data['testi_monthly'] = $this->db->query($sql_testi, $params)->result_array();

    $data['year'] = $year;

    $template = get_header_template($logg['role']);
	$this->load->view($template['header']);
    $this->load->view('accounts/yearly_monthly_details', $data);
	$this->load->view($template['footer']);
}*/

public function yearly_monthly_details_admin()
{
    $logg = checklogin();
    if ($logg['status'] != true) {
        redirect(base_url());
        return;
    }

    $year = $this->input->get('year');

    /* =========================
       MONTHLY CONSULTATIONS
    ==========================*/
    $sql_consult = "
        SELECT 
            MONTH(a.appoitmented_date) AS month,
            c.center_name,
            COUNT(*) AS total_consultations
        FROM hms_appointments a
        JOIN hms_centers c 
            ON c.center_number = a.appoitment_for
        WHERE a.status = 'consultation_done'
        AND YEAR(a.appoitmented_date) = ?
        GROUP BY MONTH(a.appoitmented_date), c.center_name
    ";

    $consult_data = $this->db->query($sql_consult, [$year])->result_array();

    /* =========================
       OVARIAN STEM CELL
    ==========================*/
    $sql_stem = "
        SELECT 
            MONTH(o.date_of_procedure) AS month,
            c.center_name,
            COUNT(*) AS total_ovarian
        FROM ovarian_prp_discharge_summary o
        JOIN hms_centers c 
            ON c.center_number = o.center
        WHERE o.procedure_name = 'STEM CELL'
        AND YEAR(o.date_of_procedure) = ?
        GROUP BY MONTH(o.date_of_procedure), c.center_name
    ";

    $stem_data = $this->db->query($sql_stem, [$year])->result_array();

    /* =========================
       TESTICULAR STEM CELL
    ==========================*/
    $sql_testi = "
        SELECT 
            MONTH(t.date_of_procedure) AS month,
            c.center_name,
            COUNT(*) AS total_testicular
        FROM testicular_prp_discharge_summary t
        JOIN hms_centers c 
            ON c.center_number = t.center
        WHERE t.procedures = 'Testicular Stem Cell'
        AND YEAR(t.date_of_procedure) = ?
        GROUP BY MONTH(t.date_of_procedure), c.center_name
    ";

    $testi_data = $this->db->query($sql_testi, [$year])->result_array();

    $data['consult'] = $consult_data;
    $data['stem']    = $stem_data;
    $data['testi']   = $testi_data;
    $data['year']    = $year;

    $template = get_header_template($logg['role']);
	$this->load->view($template['header']);
    $this->load->view('accounts/yearly_monthly_details_admin', $data);
	$this->load->view($template['footer']);
}

public function clinical_details() {
	 $logg = checklogin();
    if ($logg['status'] != true) {
        redirect(base_url());
        return;
    }

	$center = null;
	 if (!empty($_SESSION['logged_doctor']['center'])) {
        $center = $_SESSION['logged_doctor']['center'];
    } else {
        // Admin login → ALL centers
        $center = null;
    }

	//$this->load->model('all_method');
    $year = $this->input->get('year');
    $type = $this->input->get('type');
	$month = $this->input->get('month');
    
    $data['year'] = $year;
    // Replace underscores with spaces and capitalize for the title (e.g. "ovarian_prp" -> "OVARIAN PRP")
    $data['type_title'] = str_replace('_', ' ', strtoupper($type)); 

    // Fetch records based on the type selected
    switch ($type) {
        case 'consultation':
            $data['records'] = $this->accounts_model->get_yearly_consultations($year, $type, $month, $center);
            break;

		case 'first_visit':
            $data['records'] = $this->accounts_model->get_yearly_first_visit($year, $type, $month, $center);
            break;	

        case 'stem_cell':
            $data['records'] = $this->accounts_model->get_yearly_ovarian_stem($year, $type, $month, $center);
            break;

        case 'testicular_stem_cell':
            $data['records'] = $this->accounts_model->get_yearly_testicular_stem($year, $type, $month, $center);
            break;

        case 'ovarian_prp':
            $data['records'] = $this->accounts_model->get_yearly_ovarian_prp($year, $type, $month, $center);
            break;

        case 'ovum_pickup':
            $data['records'] = $this->accounts_model->get_yearly_ovum_pickup($year, $type, $month, $center);
            break;

        case 'fresh_cycle_et':
            $data['records'] = $this->accounts_model->get_yearly_embryo_transfer($year, $type, $month, $center);
            break;

        case 'fet':
            $data['records'] = $this->accounts_model->get_yearly_fet($year, $type, $month, $center);
            break;

        case 'iui':
            $data['records'] = $this->accounts_model->get_yearly_iui($year, $type, $month, $center);
            break;

        case 'ivf':
            $data['records'] = $this->accounts_model->get_yearly_ivf($year, $type, $month, $center);
            break;

        case 'icsi':
            $data['records'] = $this->accounts_model->get_yearly_icsi($year, $type, $month, $center);
            break;

        case 'tesa_mtesa':
            $data['records'] = $this->accounts_model->get_yearly_tesa_mtesa($year, $type, $month, $center);
            break;

        case 'testicular_prp':
            $data['records'] = $this->accounts_model->get_yearly_testicular_prp($year, $type, $month, $center);
            break;

		case 'sperm_mobil':
			$data['records'] = $this->accounts_model->get_yearly_sperm_mobil($year, $type, $month, $center);
			break;	

		case 'blastocyst':
            $data['records'] = $this->accounts_model->get_yearly_blastocyst($year, $type, $month, $center);
            break;
			
		case 'lah':
            $data['records'] = $this->accounts_model->get_yearly_lah($year, $type, $month, $center);
            break;	

		case 'embryo_glue':
            $data['records'] = $this->accounts_model->get_yearly_embryo_glue($year, $type, $month, $center);
            break;	

        default:
            $data['records'] = [];
            break;
    }
	//$data['all_method'] = $this->all_method;
 	$template = get_header_template($logg['role']);
	$this->load->view($template['header']);
    $this->load->view('accounts/clinical_details_view', $data);
	$this->load->view($template['footer']);
    
}


} // End of class - MAKE SURE THIS IS THE LAST LINE

	

