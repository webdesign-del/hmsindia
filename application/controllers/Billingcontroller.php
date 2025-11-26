<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require FCPATH . 'vendor/autoload.php';

use Mpdf\Mpdf;

class Billingcontroller extends CI_Controller {



	public function __construct()

	{

		// Load parent's constructor.

       	parent::__construct();
		
		$this->load->database();

		$this->load->helper('form');

        $this->load->helper('url_helper');

	    $this->load->library('session');

		//New Billing module

		$this->load->model('billingmodel_model');

		//New Billing module

		$this->load->model('billings_model');

		$this->load->model('doctors_model');

		$this->load->model('investigation_model');

		$this->load->model('procedures_model');

		$this->load->model('accounts_model');

		$this->load->model('stock_model');

		$this->load->model('center_model');

		$this->load->model('patients_model');

		$this->load->model('employee_model');
		$this->load->model('appointment_model');
		
		$this->load->library("pagination");

		$this->load->helper('myhelper');
		error_reporting(0);
	}



	public function appointment()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billing_view/appointment', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}


function partial_billing($appointment_id){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_partial_billing'){
				unset($_POST['action']);
				
				if(isset($_POST['medicine_suggestion'])){
					$male_med_array = $female_med_array = array();
					foreach($_POST as $key => $val){
						if (substr( $key, 0, 19 ) === "male_medicine_name_") {
							$male_med_array[] = $key;
						}
						if (substr( $key, 0, 21 ) === "female_medicine_name_") {
							$female_med_array[] = $key;
						}
					}
					$male_med_number = $female_med_number = array();
					foreach($male_med_array as $key => $val){
							$explode = explode('male_medicine_name_', $val);
							$male_med_number[] = $explode[1];
					}
					foreach($female_med_array as $key => $val){
							$explode = explode('female_medicine_name_', $val);
							$female_med_number[] = $explode[1];
					}
					$male_med_number = array_unique($male_med_number);
					$female_med_number = array_unique($female_med_number);
				
					$male_medicine_suggestion_list = $female_medicine_suggestion_list = array();		
					foreach($male_med_number as $key => $val){
						$male_medicine_suggestion_list['male_medicine_suggestion_list'][] = array(
							 'male_medicine_name' => $_POST['male_medicine_name_'.$val],
							 'male_medicine_dosage' => $_POST['male_medicine_dosage_'.$val],
							 'male_medicine_when_start' => $_POST['male_medicine_when_start_'.$val],
							 'male_medicine_days' => $_POST['male_medicine_days_'.$val],
							 'male_medicine_route' => $_POST['male_medicine_route_'.$val],
							 'male_medicine_frequency' => $_POST['male_medicine_frequency_'.$val],
							 'male_medicine_timing' => $_POST['male_medicine_timing_'.$val],
							 'male_medicine_take' => $_POST['male_medicine_take_'.$val]
							);
						unset($_POST['male_medicine_name_'.$val]);
						unset($_POST['male_medicine_dosage_'.$val]);
						unset($_POST['male_medicine_when_start_'.$val]);
						unset($_POST['male_medicine_days_'.$val]);
						unset($_POST['male_medicine_route_'.$val]);
						unset($_POST['male_medicine_frequency_'.$val]);
						unset($_POST['male_medicine_timing_'.$val]);
						unset($_POST['male_medicine_take_'.$val]);
					}
					$male_medicine_suggestion_list = serialize($male_medicine_suggestion_list);
					$_POST['male_medicine_suggestion_list'] = $male_medicine_suggestion_list;
					foreach($female_med_number as $key => $val){
						$female_medicine_suggestion_list['female_medicine_suggestion_list'][] = array(
							 'female_medicine_name' => $_POST['female_medicine_name_'.$val],
							 'female_medicine_dosage' => $_POST['female_medicine_dosage_'.$val],
							 'female_medicine_when_start' => $_POST['female_medicine_when_start_'.$val],
							 'female_medicine_days' => $_POST['female_medicine_days_'.$val],
							 'female_medicine_route' => $_POST['female_medicine_route_'.$val],
							 'female_medicine_frequency' => $_POST['female_medicine_frequency_'.$val],
							 'female_medicine_timing' => $_POST['female_medicine_timing_'.$val],
							 'female_medicine_take' => $_POST['female_medicine_take_'.$val]
						);
						unset($_POST['female_medicine_name_'.$val]);
						unset($_POST['female_medicine_dosage_'.$val]);
						unset($_POST['female_medicine_when_start_'.$val]);
						unset($_POST['female_medicine_days_'.$val]);
						unset($_POST['female_medicine_route_'.$val]);
						unset($_POST['female_medicine_frequency_'.$val]);
						unset($_POST['female_medicine_timing_'.$val]);
						unset($_POST['female_medicine_take_'.$val]);
					}
					$female_medicine_suggestion_list = serialize($female_medicine_suggestion_list);
					$_POST['female_medicine_suggestion_list'] = $female_medicine_suggestion_list;
				}
				
				if(isset($_POST['investigation_suggestion'])){
					if(!empty($_POST['male_investigation_suggestion_list'])){
						$male_investigation_suggestion_list = array();
						$male_investigation_suggestion_list = $_POST['male_investigation_suggestion_list'];unset($_POST['male_investigation_suggestion_list']);
						$_POST['male_investigation_suggestion_list'] = serialize($male_investigation_suggestion_list);
					}
					if(!empty($_POST['female_investigation_suggestion_list'])){
						$female_investigation_suggestion_list = array();
						$female_investigation_suggestion_list = $_POST['female_investigation_suggestion_list'];unset($_POST['female_investigation_suggestion_list']);
						$_POST['female_investigation_suggestion_list'] = serialize($female_investigation_suggestion_list);
					}	
					
				}
				if(isset($_POST['procedure_suggestion'])){
				    if(isset($_POST['sub_procedure_suggestion_list'])){
					    $_POST['sub_procedure_suggestion_list'] = serialize($_POST['sub_procedure_suggestion_list']);
				    }
				}
				$prescription = '';
				if(!empty($_FILES['prescription']['tmp_name'])){
					$dest_path = $this->config->item('upload_path');
					$destination = $dest_path.'patient_files/';
					$NewImageName = rand(4,10000)."-".$_POST['patient_id']."-".$_FILES['prescription']['name'];
					$prescription = base_url().'assets/patient_files/'.$NewImageName;
					move_uploaded_file($_FILES['prescription']['tmp_name'], $destination.$NewImageName);
				}
				//var_dump($_POST);die;
				$consultation_post = array();
				$consultation_post['appointment_id'] = $_POST['appointment_id']; unset($_POST['appointment_id']);
				$consultation_post['patient_id'] = $_POST['patient_id'];
				$consultation_post['wife_phone'] = $_POST['wife_phone'];
				$consultation_post['doctor_id'] = $_POST['doctor_id']; unset($_POST['doctor_id']);
				$consultation_post['female_findings'] = isset($_POST['female_findings'])?$_POST['female_findings']:''; unset($_POST['female_findings']);
				$consultation_post['male_findings'] = isset($_POST['male_findings'])?$_POST['male_findings']:''; unset($_POST['male_findings']);
				$consultation_post['follow_up'] = isset($_POST['follow_up'])?$_POST['follow_up']:""; unset($_POST['follow_up']);
				$consultation_post['follow_up_date'] = isset($_POST['follow_up_date'])?$_POST['follow_up_date']:"";unset($_POST['follow_up_date']);
				$consultation_post['follow_slot'] = isset($_POST['appoitmented_slot'])?$_POST['appoitmented_slot']:"";unset($_POST['appoitmented_slot']);
				$consultation_post['follow_up_purpose'] = isset($_POST['follow_up_purpose'])?$_POST['follow_up_purpose']:""; unset($_POST['follow_up_purpose']);
				
				if(isset($_POST['medicine_suggestion'])){
					$consultation_post['medicine_suggestion'] = $_POST['medicine_suggestion']; unset($_POST['medicine_suggestion']);
					if(isset($male_medicine_suggestion_list) && !empty($male_medicine_suggestion_list)){
						$consultation_post['male_medicine_suggestion_list'] = $male_medicine_suggestion_list;
					} unset($_POST['male_medicine_suggestion_list']);
					if(isset($female_medicine_suggestion_list) && !empty($female_medicine_suggestion_list)){
						$consultation_post['female_medicine_suggestion_list'] = $female_medicine_suggestion_list;
					} unset($_POST['female_medicine_suggestion_list']);
				}
				if(isset($_POST['investigation_suggestion'])){
					$consultation_post['investation_suggestion'] = $_POST['investigation_suggestion']; unset($_POST['investigation_suggestion']);
					if(isset($_POST['male_investigation_suggestion_list']) && !empty($_POST['male_investigation_suggestion_list'])){
						$consultation_post['male_investigation_suggestion_list'] = $_POST['male_investigation_suggestion_list'];
					}unset($_POST['male_investigation_suggestion_list']);
					if(isset($_POST['female_investigation_suggestion_list']) && !empty($_POST['female_investigation_suggestion_list'])){
						$consultation_post['female_investigation_suggestion_list'] = $_POST['female_investigation_suggestion_list'];
					} unset($_POST['female_investigation_suggestion_list']);
				}
				if(isset($_POST['procedure_suggestion'])){
    				$consultation_post['procedure_suggestion'] = $_POST['procedure_suggestion']; unset($_POST['procedure_suggestion']);
    				$consultation_post['procedure_suggestion_list'] = ''; 
    				$consultation_post['sub_procedure_suggestion_list'] = $_POST['sub_procedure_suggestion_list']; unset($_POST['sub_procedure_suggestion_list']);
				}
				$consultation_post['edit_mode'] = 1;
				$consultation_post['final_mode'] = 0;
				$_POST['prescription'] = $prescription;
				$consultation_post['prescription'] = $prescription;
				$consultation_post['consultation_date'] = date("Y-m-d H:i:s");	
				
				//var_dump($consultation_post);die;
				$consultation_done = $this->doctors_model->consultation_done($consultation_post);//var_dump($consultation_done);die;
				if($consultation_done > 0){
					$this->db->where('id', $consultation_post['appointment_id']);
					$update = $this->db->update(config_item('db_prefix').'appointments', array('partial_billing' => 1));
					header("location:" .base_url(). "my_appointments?m=".base64_encode('Partial Consultation Done!').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "my_appointments?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			$data = array();
			$data['appointments'] = $this->appointment_model->doctor_appointment_details($appointment_id);
			$data['consultation_medicine'] = $this->doctors_model->consultation_medicine();
			$data['investigations'] = $this->investigation_model->get_investigations_list();
			$data['procedures'] = $this->procedures_model->get_procedures_list();			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/partial_billing', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function partial_consultation(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$wife_name = $this->input->get('wife_name', true);
			/*$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);*/
			$patient_id = $this->input->get('patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "partial-consultation";
        	$config["total_rows"] = $this->billingmodel_model->partial_consultation_count($wife_name, $patient_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['partial_consultation_billing'] = $this->billingmodel_model->partial_consultation_pagination($config["per_page"], $per_page, $wife_name, $patient_id);
			
			$data["wife_name"] = $wife_name;
			/*$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;*/
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billing_view/partial_consultation', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
} 
	
	public function cancel_partial_consultation($appointments_id){
		$this->db->where('id', $appointments_id);
		$update = $this->db->update(config_item('db_prefix').'appointments', array('partial_billing' => 0));
		if($update > 0){
			header("location:" .base_url(). "partial-consultation?m=".base64_encode('Partial Consultation Cancelled!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "partial-consultation?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
			die();
		}
	}
	
	public function get_consultation($appointments_id){
		$data = $this->billingmodel_model->get_consultation_data($appointments_id);
		return $data;
	}
	

	public function consultation($appointment_id){
     
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_consultation'){
				unset($_POST['action']);				
				// Server-side duplicate check for consultation receipt number
				$raw_receipt = isset($_POST['receipt_number']) ? $_POST['receipt_number'] : '';
				if(!empty($raw_receipt)){
					$exists_q = $this->db->query("SELECT COUNT(*) AS cnt FROM ".config_item('db_prefix')."consultation WHERE receipt_number = ?", array($raw_receipt));
					$row = $exists_q->row();
					if(isset($row->cnt) && (int)$row->cnt > 0){
						header("location:" . base_url() . "billing/consultation/".$appointment_id."?m=".base64_encode('Receipt number already exists!').'&t='.base64_encode('error'));
						die();
					}
				}
				$_POST['receipt_number'] = check_billing_receipt($_POST['receipt_number']);
				$appointment = $_POST['appointment_id'];
				$biller_id = $_POST['biller_id'];
				$uhid = isset($_POST['uhid']) ? $_POST['uhid'] : '';
				$donor_patient_id = isset($_POST['donor_patient_id']) ? $_POST['donor_patient_id'] : '';
				$cash_payment = isset($_POST['cash_payment']) ? $_POST['cash_payment'] : 0;
				$card_payment = isset($_POST['card_payment']) ? $_POST['card_payment'] : 0;
				$upi_payment = isset($_POST['upi_payment']) ? $_POST['upi_payment'] : 0;
				$neft_payment = isset($_POST['neft_payment']) ? $_POST['neft_payment'] : 0;
				$wallet_payment = isset($_POST['wallet_payment']) ? $_POST['wallet_payment'] : 0;
				$appointments = $this->billingmodel_model->check_appointments($appointment);
				if(empty($appointments)){
				    header("location:" .base_url(). "my_appointments?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
					die();
				}
				if(empty($appointments['paitent_type']) && !isset($appointments['paitent_type']) && empty($appointments['wife_phone']) && !isset($appointments['wife_phone'])){
				    header("location:" .base_url(). "my_appointments?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
					die();
				}
				$transaction_img = '';
				if(!empty($_FILES['transaction_img']['tmp_name'])){
					$dest_path = $this->config->item('upload_path');
					$destination = $dest_path.'patient_files/';
					$NewImageName = rand(4,10000)."-".$_FILES['transaction_img']['name'];
					$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
					move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
					$_POST['transaction_img'] = $transaction_img;
				}
				$paitent_id = "";
				if(isset($appointments['paitent_type']) && $appointments['paitent_type'] == 'exist_patient'){
					$paitent_id = $appointments['paitent_id'];
				}else{
					$paitent_id = $_POST['patient_id'];
					$patient_arr = array();
					$patient_arr['patient_id'] = $_POST['patient_id'];
					$patient_arr['patient_phone'] = $appointments['wife_phone'];
					$patient_arr['wife_name'] = $appointments['wife_name'];
					$patient_arr['wife_phone'] = $appointments['wife_phone'];
					$patient_arr['wife_email'] = $appointments['wife_email'];
					$patient_arr['nationality'] = $appointments['nationality'];
					$paitent_insert = $this->billings_model->paitent_insert($patient_arr);
				}
				$_POST['patient_id'] = $paitent_id;
				$_POST['reason_of_visit'] = $appointments['reason_of_visit'];
				$_POST['doctor_id'] = $appointments['appoitmented_doctor'];				
				$_POST['status'] = 'pending';
				if($_POST['discount_amount'] == ''){ $_POST['discount_amount'] = 0; }
				$series_number = $_POST['series_number'];
				$consult = $this->billings_model->consultation_insert($_POST);
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://flertility.in/lead/lead-mobile-no/?mobile_no=" . urlencode(isset($_POST['wife_phone']) ? $_POST['wife_phone'] : ''),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'GET',
				));
				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
				if ($err) {
					echo "cURL Error: $err";
				} else {
					$leadData = json_decode($response, true); // Decode JSON to associative array
					if (!empty($leadData) && isset($leadData[0])) {
						$lead = $leadData[0];
						$this->db->where('wife_phone', $lead['mobile']);
						$this->db->update('hms_appointments', ['crm_id' => $lead['id']]);
					} else {
						// echo "No lead data found.";
					}
				}
				if($consult > 0){
				    $checkpatient_register = get_patient_detail($paitent_id);
    			    if(isset($checkpatient_register) && !empty($checkpatient_register) && $checkpatient_register['whats_registers'] == 0){
    			        $centre_namme = get_center_name($_POST['billing_at']);
    			        whatsappregister($appointments['wife_phone'], json_encode(array("name" => $appointments['wife_name'], "iic_id" => $paitent_id, "center" => $centre_namme)));
        		        $this->db->where('patient_id', $paitent_id);
        		        $this->db->update('hms_patients', array('whats_registers' => 1));
    			    }
					$insert_receipt = insert_receipt_log($_POST['receipt_number']);
					$update_appointment = $this->billingmodel_model->update_appointment($appointment, $uhid);
					$this->send_billing_receipt($biller_id, $paitent_id, $_POST['on_date'], $_POST['billing_from'], $_POST['receipt_number'], 'consultation');
					$receipt_number = $_POST['receipt_number'];
					header("location:" .base_url(). "accounts/details/$receipt_number?m=".base64_encode('Billing added successfully').'&t=consultation');
					die();
				}else{
					header("location:" .base_url(). "my_appointments?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$appointments = $this->billingmodel_model->check_appointments($appointment_id);
			if(!empty($appointments)){
				$data['appointments'] = $appointments;							
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('billing_view/consultation', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "my_appointments?m=".base64_encode('Appointment not found/already billed!').'&t='.base64_encode('error'));
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	public function registation($appointment_id){

		$logg = checklogin();

		if($logg['status'] == true){

			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_registation'){
				unset($_POST['action']);				
				$_POST['receipt_number'] = check_billing_receipt($_POST['receipt_number']);
				$appointment = $_POST['appointment_id'];
				$appointment = $_POST['appointment_id'];
				$biller_id = $_POST['biller_id'];
				$uhid = $_POST['uhid'];
				$crm_id = $_POST['crm_id'];
				$cash_payment = $_POST['cash_payment'];
				$card_payment = $_POST['card_payment'];
				$upi_payment = $_POST['upi_payment'];
				$neft_payment = $_POST['neft_payment'];
				$wallet_payment = $_POST['wallet_payment'];
				$transaction_img = '';
				if(!empty($_FILES['transaction_img']['tmp_name'])){
					$dest_path = $this->config->item('upload_path');
					$destination = $dest_path.'patient_files/';
					$NewImageName = rand(4,10000)."-".$_FILES['transaction_img']['name'];
					$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
					move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
					$_POST['transaction_img'] = $transaction_img;
				}
				$_POST['reason_of_visit'] = $appointments['reason_of_visit'];
				$doctor_id = $_POST['doctor_id'];				
				$_POST['status'] = 'pending';
				if($_POST['discount_amount'] == ''){ $_POST['discount_amount'] = 0; }
				$series_number = $_POST['series_number'];
				$patient_id = $_POST['patient_id'];
				
				//var_dump($_POST);die();

				$consult = $this->billings_model->registation_insert($_POST);
				if($consult > 0){
				    $checkpatient_register = get_patient_detail($patient_id);
    			    if(isset($checkpatient_register) && !empty($checkpatient_register) && $checkpatient_register['whats_registers'] == 0){
    			        $centre_namme = get_center_name($_POST['billing_at']);
    			        whatsappregister($appointments['wife_phone'], json_encode(array("name" => $appointments['wife_name'], "iic_id" => $patient_id, "center" => $centre_namme)));
        		        $this->db->where('patient_id', $patient_id);
        		        $this->db->update('hms_patients', array('whats_registers' => 1));
    			    }
					$this->send_billing_receipt($biller_id, $patient_id, $_POST['on_date'], $_POST['billing_from'], $_POST['receipt_number'], 'registation');
					$receipt_number = $_POST['receipt_number'];
					header("location:" .base_url(). "accounts/details/$receipt_number?m=".base64_encode('Billing added successfully').'&t=registation');
					die();
				}else{
					header("location:" .base_url(). "my_appointments?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$appointments = $this->billingmodel_model->check_appointments($appointment_id);
			if(!empty($appointments)){
				$data['appointments'] = $appointments;							
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('billing_view/registation', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "my_appointments?m=".base64_encode('Appointment not found/already billed!').'&t='.base64_encode('error'));
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}

	}
	

	public function after_consultation(){

		$logg = checklogin();

		if($logg['status'] == true){

			$data = array();

			$template = get_header_template($logg['role']);

			$this->load->view($template['header']);

			$this->load->view('billing_view/after_consultation', $data);

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}

	

	function send_billing_receipt($biller_id, $patient_id, $date, $billing_from, $receipt, $type){

		$patient_data = get_patient_detail($patient_id);

		$currency = '';

		//$currency = 'Rs.';

		//var_dump($patient_data);die;

		$mail_html = '';

		$mail_html = '<table style="width: 100%;" border="0">

						<tbody>

							<tr style="height: 23px;">

								<td style="width: 50%;"><strong><u>Patient details</u></strong></td>

								<td style="width: 50%;"><strong><u>Billing details</u></strong></td>

							</tr>

							<tr style="height: 23px;">

								<td style="width: 50%;"><strong>Name :</strong> '.(isset($patient_data['wife_name']) ? $patient_data['wife_name'] : '').'</td>

								<td style="width: 50%;"><strong>Billing date :</strong> '.date('d-m-Y', strtotime($date)).'</td>

							</tr>

							<tr style="height: 23px;">

								<td style="width: 50%;"><strong>Email :</strong> '.(isset($patient_data['wife_email']) ? $patient_data['wife_email'] : '').'</td>

								<td style="width: 50%;"><strong>Billing option :</strong> '.ucwords($type).'</td>

							</tr>

							<tr style="height: 23px;">

								<td style="width: 50%;"><strong>Phone :</strong> '.(isset($patient_data['patient_phone']) ? $patient_data['patient_phone'] : '').'</td>';

								if($billing_from == 'IndiaIVF'){

		$mail_html .= '<td style="width: 50%;"><strong>Billing source :</strong> IndiaIVF</td>';

								}else{

						$center_name = $this->get_center_name($billing_from);

		$mail_html .= '<td style="width: 50%;"><strong>Billing source :</strong> '.$center_name.'</td>';

								}

		$mail_html .= '</tr>

						</tbody></table>';



		// $mail_html .= '<table></tbody><tr style="height: 23px;">

		// 						<td style="width: 50%;"><strong>Documents received: </strong> <br/> <a href="'.$patient_data['wife_pan_card'].'" download>Patient pancard</a> <br/> <a href="'.$patient_data['wife_adhar_card'].'" download>Patient aadhaar card</a> <br/> <a href="'.$patient_data['wife_photo'].'" download>Patient photo</a> <br/> <a href="'.$patient_data['husband_pan_card'].'" download>Husband pancard</a> <br/> <a href="'.$patient_data['husband_adhar_card'].'" download>Husband aadhaar card</a> <br/> <a href="'.$patient_data['husband_photo'].'" download>Husband photo</a> <br/></td>

		// 						<td style="width: 50%;"></td>

		// 					</tr></tbody></table>';

		if($type == 'consultation'){

			$mail_html .= $this->consultation_billing_receipt($receipt, $type, $currency);

		}else if($type == 'investigation'){

			$mail_html .= $this->investigation_billing_receipt($receipt, $type, $currency);

			$mail_html .= patient_medical_info($patient_id);

		}else if($type == 'procedure'){

			$mail_html .= $this->procedure_billing_receipt($receipt, $type, $currency);

		}

		

		//$mail_html .= "<br/> -- <br/>";

		//$mail_html .= "Thanks & Regards<br/> Email: info@indiaivf.in <br/> Phone: +91-735-387-3538 <br/> Website: https://indiaivf.in<br/> <img src='".base_url('assets/images/india-receipt-logo.png')."' style='max-width:200px;' />";

		

		$sms_html = "";

		if($type == 'consultation'){

			$sms_html = $this->consultation_billing_sms_receipt($receipt, $type, $currency);

		}else if($type == 'investigation'){

			$sms_html = $this->investigation_billing_sms_receipt($receipt, $type, $currency);

		}else if($type == 'procedure'){

			$sms_html = $this->procedure_billing_sms_receipt($receipt, $type, $currency);

		}

		send_sms(isset($patient_data['wife_phone']) ? $patient_data['wife_phone'] : '', $sms_html);

		

		$biller_details = get_biller_details($biller_id);

		$biller_emails = $biller_details['email'];

		// $sent = send_mail($biller_emails.'|'.(isset($patient_data['wife_email']) ? $patient_data['wife_email'] : '').'', 'IndiaIVF Billing Receipt', $mail_html);

		

		$billingdata = $this->billings_model->get_billings_details($receipt, $type);

		if(!empty($billingdata)){

			$billing_key = $billingdata['ID'];

			$account_email = "accounts@indiaivf.in";

			$account_html = "";

			$account_html = $mail_html;

			$account_html .= '<a href="'.base_url().'accounts/front_approve/'.$billing_key.'?t='.$type.'&u=approved" class="xyx btn btn-large">Approve</a>

				<a href="'.base_url().'accounts/front_approve/'.$billing_key.'?t='.$type.'&u=disapproved&r=Wrong billing">Disapprove</a>';
			// $sent = send_mail($account_email, 'IndiaIVF Billing Approval', $account_html);

		}

		

		return ;
		// return $sent;

	}



	function consultation_billing_receipt($receipt, $type, $currency){

		$data = $this->billings_model->get_billings_details($receipt, $type);

		$medicine_data = $consultation_data = array();

				

		$html = '';

		$html = '<hr/><table style="width: 100%;">

					<tbody>						

						<tr>

							<td style="width: 50%;"><strong>Package total :</strong> '.$currency.''.$data['totalpackage'].'</td>

						</tr>';

					if($data['discount_amount'] > 0){

		$html .= '<tr><td style="width: 50%;"><strong>Discount :</strong> '.$currency.''.$data['discount_amount'].'</td></tr>';

					}

		$html .= '<tr><td style="width: 50%;"><strong>Total :</strong> '.$currency.''.$data['fees'].'</td></tr>';

						

		$html .= '<tr><td style="width: 50%;"><strong>Received amount :</strong> '.$currency.''.$data['payment_done'].'</td></tr>

					<tr>

						<td style="width: 50%;"><strong>Remaining amount :</strong> '.$currency.''.$data['remaining_amount'].'</td>

					</tr>

					<tr>

						<td style="width: 50%;"><strong>Payment mode :</strong> '.strtoupper($data['payment_method']).'</td>

					</tr>';

		if($data['transaction_id'] != ""){

			$html .= '<tr><td style="width: 50%;"><strong>Reference No.:</strong> '.$data['transaction_id'].' <a href="'.$data['transaction_img'].'" download>Transaction receipt</a></td></tr>';

		}

		$html .= '</tbody></table>';

		return $html;	

	}



	function investigation_billing_receipt($receipt, $type, $currency){

		$data = $this->billings_model->get_billings_details($receipt, $type);

		$medicine_data = $investigation_data = array();

		if(!empty($data['medicines'])){

			$medicine_data = unserialize($data['medicines']);

		}

		if(!empty($data['investigations'])){

			$investigation_data = unserialize($data['investigations']);

		}

		

		$html = '';

		$html = '<hr/><table style="width: 100%;">

					<tbody>

						<tr>

							<td style="width: 50%;"><strong>Paramedic name :</strong> '.$data['paramedic_name'].'</td>

						</tr>

						<tr>

							<td style="width: 50%;"><strong>Package total :</strong> '.$currency.''.$data['totalpackage'].'</td>

						</tr>';

						if($data['discount_amount'] > 0){

		$html .= '<tr><td style="width: 50%;"><strong>Discount :</strong> '.$currency.''.$data['discount_amount'].'</td>

  				  </tr>';

						}

		$html .= '<tr><td style="width: 50%;"><strong>Total :</strong> '.$currency.''.$data['fees'].'</td>

						</tr>';

						

		$html .= '<tr>

							<td style="width: 50%;"><strong>Received amount :</strong> '.$currency.''.$data['payment_done'].'</td>

						</tr>

						<tr>

							<td style="width: 50%;"><strong>Remaining amount :</strong> '.$currency.''.$data['remaining_amount'].'</td>

						</tr>

						<tr>

							<td style="width: 50%;"><strong>Payment mode :</strong> '.strtoupper($data['payment_method']).'</td>

						</tr>';

		$html .= '<tr><td style="width: 50%;"><strong>Reference No.:</strong> '.$data['transaction_id'].' <a href="'.$data['transaction_img'].'" download>Transaction receipt</a></td></tr>';

		$html .= '</tbody></table>';

		

		if(!empty($medicine_data)){ 

			$html .= '<hr/><h3>Medicine details</h3>';

			if(!empty($medicine_data['male_medicine'])){ 

				$html .= '<h4>Male Medicine</h4><table style="width:100%">

							<tr>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Medicine</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Brand</th>

								

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Dosage</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Route</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Frequency</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Timing</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">When to start</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Days</th>

							</tr>';

				foreach($medicine_data['male_medicine'] as $key => $val){

					$medicine_details = $this->get_medicine_name($val['male_med_name']);//var_dump($medicine_details);die; 

					$html .= '<tr>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role">'.$medicine_details['item_name'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role">'.$this->get_brand_name($medicine_details['brand_name']).'</td>

								

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['male_med_dose'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['male_med_route'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['male_med_frequency'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['male_med_timing'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['male_med_when_start'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['male_med_for'].'</td>

							</tr>';

				}	

				$html .= '</table>';

			}



			if(!empty($medicine_data['female_medicine'])){ 

				$html .= '<h4>Female Medicine</h4><table style="width:100%">

							<tr>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Medicine</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Brand</th>

								

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Dosage</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Route</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Frequency</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Timing</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">When to start</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Days</th>

							</tr>';

				foreach($medicine_data['female_medicine'] as $key => $val){

					$medicine_details = $this->get_medicine_name($val['female_med_name']);//var_dump($medicine_details);die; 

					$html .= '<tr>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role">'.$medicine_details['item_name'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role">'.$this->get_brand_name($medicine_details['brand_name']).'</td>

								

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['female_med_dose'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['female_med_route'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['female_med_frequency'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['female_med_timing'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['female_med_when_start'].'</td>

								<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['female_med_for'].'</td>

							</tr>';

				}	

				$html .= '</table>';

			}

		}



		if(!empty($investigation_data)){ //var_dump($investigation_data);die;

			$html .= '<hr/><h3>Investigation details</h3>';

			if(!empty($investigation_data['male_investigation'])){ 

				$html .= '<h4>Male Investigations</h4><table style="width:100%">

							<tr>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Investigation</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Code</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Price</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discount</th>

							</tr>';

				foreach($investigation_data['male_investigation'] as $key => $val){

						$html .= '<tr>

									<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role">'.$this->get_investigation_name($val['male_investigation_name']).'</td>

									<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['male_investigation_code'].'</td>

									<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['male_investigation_price'].'</td>

									<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['male_investigation_discount'].'</td>

								</tr>';

				}	

				$html .= '</table>';

			}



			if(!empty($investigation_data['female_investigation'])){ 

				$html .= '<h4>Female Investigations</h4><table style="width:100%">

							<tr>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Investigation</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Code</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Price</th>

								<th  style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Discount</th>

							</tr>';

				foreach($investigation_data['female_investigation'] as $key => $val){

						$html .= '<tr>

									<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role">'.$this->get_investigation_name($val['female_investigation_name']).'</td>

									<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['female_investigation_code'].'</td>

									<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['female_investigation_price'].'</td>

									<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">'.$val['female_investigation_discount'].'</td>

								</tr>';

				}	

				$html .= '</table>';

			}

		}

		$html .= '</tbody></table>';

		return $html;		

	}

	

	function procedure_billing_receipt($receipt, $type, $currency){

		$data = $this->billings_model->get_billings_details($receipt, $type);

		$procedure_data = unserialize($data['data']);

		

		$html = '';

		$html = '<hr/><table style="width: 100%;">

					<tbody>

						<tr>

							<td style="width: 50%;"><strong>Package total :</strong> '.$currency.''.$data['totalpackage'].'</td>

						</tr>';

						if($data['discount_amount'] > 0){

		$html .= '<tr><td style="width: 50%;"><strong>Discount :</strong> '.$currency.''.$data['discount_amount'].'</td>

  				  </tr>';

						}

		$html .= '<tr><td style="width: 50%;"><strong>Discounted total :</strong> '.$currency.''.$data['fees'].'</td></tr>';

						

		$html .= '<tr>

							<td style="width: 50%;"><strong>Received amount :</strong> '.$currency.''.$data['payment_done'].'</td>

						</tr>

						<tr>

							<td style="width: 50%;"><strong>Remaining amount :</strong> '.$currency.''.$data['remaining_amount'].'</td>

						</tr>

						<tr>

							<td style="width: 50%;"><strong>Payment mode :</strong> '.strtoupper($data['payment_method']).'</td>

						</tr>';

		$html .= '<tr><td style="width: 50%;"><strong>Reference No.:</strong> '.$data['transaction_id'].' <a href="'.$data['transaction_img'].'" download>Transaction receipt</a></td></tr>';

		$html .= '</tbody></table>';



		//var_dump($investigation_data);die;

		$html .= '<h3>Procedure details</h3><table  border="1" style="width: 100%; height: auto;margin-top:20px;">

					<tbody>

					<tr>

					<td>Procedure name</td>

					<td>Procedure code</td>

					<td>Procedure price</td>

					<td>Procedure discount</td>
					
					<td>Paid Amount</td>

					</tr>';

		foreach($procedure_data['patient_procedures'] as $key => $val){

			$name_procedure = $this->get_procedure_name($val['sub_procedure']);

			$html .= '<tr>

						<td>'.$name_procedure.'</td>

						<td>'.$val['sub_procedures_code'].'</td>

						<td>'.$currency.''.$val['sub_procedures_price'].'</td>

						<td>'.$currency.''.$val['sub_procedures_discount'].'</td>
						
						<td>'.$currency.''.$val['sub_procedures_paid_price'].'</td>

					  </tr>';

		}

		$html .= '</tbody></table>';

		return $html;		

	}

	

	function consultation_billing_sms_receipt($receipt, $type, $currency){

		$data = $this->billings_model->get_billings_details($receipt, $type);

		$medicine_data = $consultation_data = array();

				

		$html = '';

		$html = "Thank you for your billing payment. Payment Details - Amt : Rs. ".$data['payment_done']." Reciept No. ".$receipt." Date : ".dateformat($data['on_date'])." - Warm Regards, India IVF";

		return $html;	

	}



	function investigation_billing_sms_receipt($receipt, $type, $currency){

		$data = $this->billings_model->get_billings_details($receipt, $type);

		$medicine_data = $consultation_data = array();

				

		$html = '';

		$html = "Thank you for your billing payment. Payment Details - Amt : Rs. ".$data['payment_done']." Reciept No. ".$receipt." Date : ".dateformat($data['on_date'])." - Warm Regards, India IVF";

		return $html;

	}

	

	function procedure_billing_sms_receipt($receipt, $type, $currency){

		$data = $this->billings_model->get_billings_details($receipt, $type);

		$medicine_data = $consultation_data = array();

				

		$html = '';

		$html = "Thank you for your billing payment. Payment Details - Amt : Rs. ".$data['payment_done']." Reciept No. ".$receipt." Date : ".dateformat($data['on_date'])." - Warm Regards, India IVF";

		return $html;		

	}


	function receipt_number_exists($receipt_number) {
		$query = $this->db->query("SELECT COUNT(*) AS count FROM hms_patient_procedure WHERE receipt_number = ?", [$receipt_number]);
		$row = $query->row();
		return ($row->count > 0); 
	}

	function investigation_receipt_number_exists($receipt_number) {
		$query = $this->db->query("SELECT COUNT(*) AS count FROM hms_patient_investigations WHERE receipt_number = ?", [$receipt_number]);		
		$row = $query->row();
		return ($row->count > 0);
	}

	public function after_consultation_billing(){
	
		$logg = checklogin();
		if($logg['status'] == true){	
			$medicine_billed = $investigation_billed = $procedure_billed = 0;
			if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == "add_investigation"){
				unset($_POST['action']);

				//var_dump($_POST);die;

				$post_arr = array();

				$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);

				$post_arr['appointment_id'] = $_POST['appointment_id'];unset($_POST['appointment_id']);

				$post_arr['consultation_done'] = $_POST['consultation_done'];unset($_POST['consultation_done']);

				$post_arr['billing_from'] = $_POST['billing_from'];unset($_POST['billing_from']);

				$post_arr['billing_at'] = $_POST['billing_at'];unset($_POST['billing_at']);

				$post_arr['paramedic_name'] = $_POST['paramedic_name'];unset($_POST['paramedic_name']);
				
				$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));  // Gets current time in IST
				$formattedDate = $date->format('Y-m-d H:i:s');  // Formats as 'YYYY-MM-DD HH:MM:SS'

				$post_arr['on_date'] = $formattedDate;

				$post_arr['receipt_number'] = check_billing_receipt($_POST['receipt_number']);unset($_POST['receipt_number']);

				$post_arr['billing_id'] = isset($_POST['billing_id'])?$_POST['billing_id']:''; unset($_POST['billing_id']);

				$post_arr['biller_id'] = $_POST['biller_id']; unset($_POST['biller_id']);

				$post_arr['subvention_charges'] = isset($_POST['subvention_charges'])?$_POST['subvention_charges']:0; unset($_POST['subvention_charges']);

				$post_arr['transaction_id'] = ($_POST['transaction_id'])?$_POST['transaction_id']:0; unset($_POST['transaction_id']);

				$transaction_img = '';

				if(!empty($_FILES['transaction_img']['tmp_name'])){

					$dest_path = $this->config->item('upload_path');

					$destination = $dest_path.'patient_files/';

					$NewImageName = rand(4,10000)."-".$post_arr['patient_id']."-". $_FILES['transaction_img']['name'];

					$transaction_img = base_url().'assets/patient_files/'.$NewImageName;

					move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);

					$post_arr['transaction_img'] = $transaction_img;

				}

				$post_arr['hospital_id'] = isset($_POST['hospital_id'])?$_POST['hospital_id']:''; unset($_POST['hospital_id']);

				//var_dump($_POST);die;
				$post_arr['payment_method'] = $_POST['payment_method'];unset($_POST['payment_method']);
				$post_arr['status'] = 'pending';
				if($_POST['payment_in'] == 'rs_payment'){
					$post_arr['payment_done'] = $_POST['payment_done'];unset($_POST['payment_done']);
					$post_arr['remaining_amount'] = $_POST['remaining_amount'];unset($_POST['remaining_amount']);
					$post_arr['fees'] = ($_POST['rs_fees']);unset($_POST['rs_fees']);
    				$post_arr['totalpackage'] = $_POST['rs_totalpackage'];unset($_POST['rs_totalpackage']);
    				$post_arr['discount_amount'] = $_POST['rs_discount'];unset($_POST['rs_discount']);
				}else{
					$post_arr['usd_totalpackage'] = $_POST['usd_totalpackage'];unset($_POST['usd_totalpackage']);	
					$post_arr['usd_fees'] = $_POST['usd_fees'];unset($_POST['usd_fees']);	
					$post_arr['us_discount'] = $_POST['us_discount'];unset($_POST['us_discount']);	
                    $post_arr['conversion_rate'] = get_converstion_rate();
					$post_arr['payment_done'] = ($_POST['payment_done']*get_converstion_rate());unset($_POST['payment_done']);
					$post_arr['remaining_amount'] = ($_POST['remaining_amount']*get_converstion_rate());unset($_POST['remaining_amount']);
					$post_arr['fees'] = ($_POST['rs_totalpackage']-$_POST['rs_discount']);unset($_POST['rs_fees']);
    				$post_arr['totalpackage'] = $_POST['rs_totalpackage'];unset($_POST['rs_totalpackage']);

    				$post_arr['discount_amount'] = $_POST['rs_discount'];unset($_POST['rs_discount']);

				}
		        //var_dump($post_arr);die;
				$post_arr['payment_in'] = $_POST['payment_in'];unset($_POST['payment_in']);
				$post_arr['origins'] = $_POST['origins'];unset($_POST['origins']);
				$post_arr['series_number'] = $_POST['series_number'] ; unset($_POST['series_number']);
				$male_medicine_array = $female_medicine_array = $medicine_array = $male_invest_array = $female_invest_array = $investigation_array = array();
				// Medicine Start

				if(isset($_POST['medicine_suggestion']) && $_POST['medicine_suggestion'] == 1){
					foreach($_POST as $key => $val){
						if (substr( $key, 0, 14 ) === "male_med_name_") {
							$male_medicine_array[] = $key;
						}

						if (substr( $key, 0, 16 ) === "female_med_name_") {
							$female_medicine_array[] = $key;

						}

					}



					$male_number = $female_number = array();
					foreach($male_medicine_array as $key => $val){
							$explode = explode('male_med_name_', $val);
							$male_number[] = $explode[1];
					}

					foreach($female_medicine_array as $key => $val){
							$explode = explode('female_med_name_', $val);
							$female_number[] = $explode[1];
					}
					$male_number = array_unique($male_number);
					$female_number = array_unique($female_number);
					foreach($male_number as $key => $val){
						$medicine_array['male_medicine'][] = array(
							'male_med_name' => $_POST['male_med_name_'.$val],
							'male_med_unit_price' => $_POST['male_med_unit_price_'.$val],
							'male_med_price' => $_POST['male_med_price_'.$val],
							'male_med_dose' => $_POST['male_med_dose_'.$val],
							'male_med_for' => $_POST['male_med_for_'.$val],
							'male_med_when_start' => $_POST['male_med_when_start_'.$val],
							'male_med_route' => $_POST['male_med_route_'.$val],
							'male_med_frequency' => $_POST['male_med_frequency_'.$val],
							'male_med_timing' => $_POST['male_med_timing_'.$val]
						);
					}
					foreach($female_number as $key => $val){
						$medicine_array['female_medicine'][] = array(
							'female_med_name' => $_POST['female_med_name_'.$val],
							'female_med_unit_price' => $_POST['female_med_unit_price_'.$val], 
							'female_med_price' => $_POST['female_med_price_'.$val],
							'female_med_dose' => $_POST['female_med_dose_'.$val],
							'female_med_for' => $_POST['female_med_for_'.$val],
							'female_med_when_start' => $_POST['female_med_when_start_'.$val],
							'female_med_route' => $_POST['female_med_route_'.$val],
							'female_med_frequency' => $_POST['female_med_frequency_'.$val],
							'female_med_timing' => $_POST['female_med_timing_'.$val]
						);
					}
					$post_arr['medicines'] = serialize($medicine_array);
					$medicine_billed = 1;

				}
				if(isset($_POST['investation_suggestion']) && $_POST['investation_suggestion'] == 1){
					foreach($_POST as $key => $val){
						if (substr( $key, 0, 24 ) === "male_investigation_name_") {
							$male_invest_array[] = $key;
						}
						if (substr( $key, 0, 26 ) === "female_investigation_name_") {
							$female_invest_array[] = $key;
						}

					}
					$male_invst_number = $female_invst_number = array();
					foreach($male_invest_array as $key => $val){
							$explode = explode('male_investigation_name_', $val);
							$male_invst_number[] = $explode[1];

					}
					foreach($female_invest_array as $key => $val){
							$explode = explode('female_investigation_name_', $val);
							$female_invst_number[] = $explode[1];
					}
					$male_invst_number = array_unique($male_invst_number);
					$female_invst_number = array_unique($female_invst_number);
					foreach($male_invst_number as $key => $val){
						$investigation_array['male_investigation'][] = array('male_investigation_name' => $_POST['male_investigation_name_'.$val], 'male_investigation_code' => $_POST['male_investigation_code_'.$val], 'male_investigation_price' => $_POST['male_investigation_price_'.$val], 'male_investigation_discount'=>$_POST['male_investigation_discount_'.$val]);
					}
					foreach($female_invst_number as $key => $val){
						$investigation_array['female_investigation'][] = array('female_investigation_name' => $_POST['female_investigation_name_'.$val], 'female_investigation_code' => $_POST['female_investigation_code_'.$val], 'female_investigation_price' => $_POST['female_investigation_price_'.$val], 'female_investigation_discount'=>$_POST['female_investigation_discount_'.$val]);
					}
					$post_arr['investigations'] = serialize($investigation_array);
					$investigation_billed = 1;

				}
				// Investigation End
                if($medicine_billed == 0){
                    $medicine_billed = check_suggested($post_arr['consultation_done'], 'medicine_suggestion', 'medicine_billed');
                }
                if($investigation_billed == 0){
                    $investigation_billed = check_suggested($post_arr['consultation_done'], 'investation_suggestion', 'investigation_billed');
                }
                if($procedure_billed == 0){
                    $procedure_billed = check_suggested($post_arr['consultation_done'], 'procedure_suggestion', 'procedure_billed');
                }
				$investg = $this->billingmodel_model->investigation_insert($post_arr);
				var_dump($investg);die;
				if($investg > 0){
					$insert_receipt = insert_receipt_log($post_arr['receipt_number']);
					$update_doctor_consultation = $this->billingmodel_model->update_doctor_consultation($post_arr['receipt_number'], $post_arr['consultation_done'], $medicine_billed, $investigation_billed, $procedure_billed);
					$this->send_billing_receipt($post_arr['biller_id'], $post_arr['patient_id'], $post_arr['on_date'], $post_arr['billing_from'], $post_arr['receipt_number'], 'investigation');
					header("location:" .base_url(). "accounts/details/".$post_arr['receipt_number']."?t=investigation");
					die();
				}else{

					header("location:" .base_url(). "after-consultation?m=".base64_encode('something went wrong!').'&t='.base64_encode('error'));

					die();

				}

			}
			if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == "add_investigations"){
				unset($_POST['action']);
				$post_arr = array();
				$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);
				$post_arr['donor_patient_id'] = $_POST['donor_patient_id'];unset($_POST['donor_patient_id']);
				$post_arr['appointment_id'] = $_POST['appointment_id'];unset($_POST['appointment_id']);
				$post_arr['consultation_done'] = $_POST['consultation_done'];unset($_POST['consultation_done']);
				$post_arr['billing_from'] = $_POST['billing_from'];unset($_POST['billing_from']);
				$post_arr['billing_at'] = $_POST['billing_at'];unset($_POST['billing_at']);
				$post_arr['paramedic_name'] = $_POST['paramedic_name'];unset($_POST['paramedic_name']);
				$post_arr['on_date'] = $_POST['on_date'];
				$receipt_number = $_POST['receipt_number'];
				if($this->investigation_receipt_number_exists($receipt_number)) {
					header("location:" . base_url() . "after-consultation-step-2?t=procedure&m=" . base64_encode('Receipt number already exists!'));
					die();
				}
				$post_arr['receipt_number'] = check_billing_receipt($_POST['receipt_number']);unset($_POST['receipt_number']);
				$post_arr['billing_id'] = isset($_POST['billing_id'])?$_POST['billing_id']:''; unset($_POST['billing_id']);
				$post_arr['biller_id'] = $_POST['biller_id']; unset($_POST['biller_id']);
				$post_arr['subvention_charges'] = isset($_POST['subvention_charges'])?$_POST['subvention_charges']:0; unset($_POST['subvention_charges']);
				$post_arr['transaction_id'] = ($_POST['transaction_id'])?$_POST['transaction_id']:0; unset($_POST['transaction_id']);
				$transaction_img = '';
				if(!empty($_FILES['transaction_img']['tmp_name'])){
					$dest_path = $this->config->item('upload_path');
					$destination = $dest_path.'patient_files/';
					$NewImageName = rand(4,10000)."-".$post_arr['patient_id']."-". $_FILES['transaction_img']['name'];
					$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
					move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
					$post_arr['transaction_img'] = $transaction_img;
				}
				$post_arr['hospital_id'] = isset($_POST['hospital_id'])?$_POST['hospital_id']:''; unset($_POST['hospital_id']);
				$post_arr['payment_method'] = $_POST['payment_method'];unset($_POST['payment_method']);
				$post_arr['status'] = 'pending';
				if($_POST['payment_in'] == 'rs_payment'){
					$post_arr['payment_done'] = $_POST['payment_done'];unset($_POST['payment_done']);
					$post_arr['remaining_amount'] = $_POST['remaining_amount'];unset($_POST['remaining_amount']);
					$post_arr['fees'] = ($_POST['rs_fees']);unset($_POST['rs_fees']);
    				$post_arr['totalpackage'] = $_POST['rs_totalpackage'];unset($_POST['rs_totalpackage']);
    				$post_arr['discount_amount'] = $_POST['rs_discount'];unset($_POST['rs_discount']);
				}else{
					$post_arr['usd_totalpackage'] = $_POST['usd_totalpackage'];unset($_POST['usd_totalpackage']);	
					$post_arr['usd_fees'] = $_POST['usd_fees'];unset($_POST['usd_fees']);	
					$post_arr['us_discount'] = $_POST['us_discount'];unset($_POST['us_discount']);	
                    $post_arr['conversion_rate'] = get_converstion_rate();
					$post_arr['payment_done'] = ($_POST['payment_done']*get_converstion_rate());unset($_POST['payment_done']);
					$post_arr['remaining_amount'] = ($_POST['remaining_amount']*get_converstion_rate());unset($_POST['remaining_amount']);
					$post_arr['fees'] = ($_POST['rs_totalpackage']-$_POST['rs_discount']);unset($_POST['rs_fees']);
    				$post_arr['totalpackage'] = $_POST['rs_totalpackage'];unset($_POST['rs_totalpackage']);
    				$post_arr['discount_amount'] = $_POST['rs_discount'];unset($_POST['rs_discount']);
				}
				$post_arr['payment_in'] = $_POST['payment_in'];unset($_POST['payment_in']);
				$post_arr['cash_payment'] = $_POST['cash_payment'];unset($_POST['cash_payment']);
				$post_arr['card_payment'] = $_POST['card_payment'];unset($_POST['card_payment']);
				$post_arr['upi_payment'] = $_POST['upi_payment'];unset($_POST['upi_payment']);
				$post_arr['neft_payment'] = $_POST['neft_payment'];unset($_POST['neft_payment']);
				$post_arr['wallet_payment'] = $_POST['wallet_payment'];unset($_POST['wallet_payment']);
                $post_arr['origins'] = $_POST['origins'];unset($_POST['origins']);
				$post_arr['series_number'] = $_POST['series_number'] ; unset($_POST['series_number']);
				$male_medicine_array = $female_medicine_array = $medicine_array = $male_invest_array = $female_invest_array = $investigation_array = array();
				if(isset($_POST['medicine_suggestion']) && $_POST['medicine_suggestion'] == 1){
					foreach($_POST as $key => $val){
						if (substr( $key, 0, 14 ) === "male_med_name_") {
							$male_medicine_array[] = $key;
						}
						if (substr( $key, 0, 16 ) === "female_med_name_") {
							$female_medicine_array[] = $key;
						}
					}
					$male_number = $female_number = array();
					foreach($male_medicine_array as $key => $val){
							$explode = explode('male_med_name_', $val);
							$male_number[] = $explode[1];
					}
					foreach($female_medicine_array as $key => $val){
							$explode = explode('female_med_name_', $val);
							$female_number[] = $explode[1];
					}
					$male_number = array_unique($male_number);
					$female_number = array_unique($female_number);
					foreach($male_number as $key => $val){
						$medicine_array['male_medicine'][] = array(
							'male_med_name' => $_POST['male_med_name_'.$val],
							'male_med_unit_price' => $_POST['male_med_unit_price_'.$val],
							'male_med_price' => $_POST['male_med_price_'.$val],
							'male_med_dose' => $_POST['male_med_dose_'.$val],
							'male_med_for' => $_POST['male_med_for_'.$val],
							'male_med_when_start' => $_POST['male_med_when_start_'.$val],
							'male_med_route' => $_POST['male_med_route_'.$val],
							'male_med_frequency' => $_POST['male_med_frequency_'.$val],
							'male_med_timing' => $_POST['male_med_timing_'.$val]
						);
					}
					foreach($female_number as $key => $val){
						$medicine_array['female_medicine'][] = array(
							'female_med_name' => $_POST['female_med_name_'.$val],
							'female_med_unit_price' => $_POST['female_med_unit_price_'.$val], 
							'female_med_price' => $_POST['female_med_price_'.$val],
							'female_med_dose' => $_POST['female_med_dose_'.$val],
							'female_med_for' => $_POST['female_med_for_'.$val],
							'female_med_when_start' => $_POST['female_med_when_start_'.$val],
							'female_med_route' => $_POST['female_med_route_'.$val],
							'female_med_frequency' => $_POST['female_med_frequency_'.$val],
							'female_med_timing' => $_POST['female_med_timing_'.$val]
						);
					}
					$post_arr['medicines'] = serialize($medicine_array);
					$medicine_billed = 1;
				}
				if(isset($_POST['investation_suggestion']) && $_POST['investation_suggestion'] == 1){
					foreach($_POST as $key => $val){
						if (substr( $key, 0, 24 ) === "male_investigation_name_") {
							$male_invest_array[] = $key;
						}
						if (substr( $key, 0, 26 ) === "female_investigation_name_") {
							$female_invest_array[] = $key;
						}
					}
					$male_invst_number = $female_invst_number = array();
					foreach($male_invest_array as $key => $val){
							$explode = explode('male_investigation_name_', $val);
							$male_invst_number[] = $explode[1];
					}
					foreach($female_invest_array as $key => $val){
							$explode = explode('female_investigation_name_', $val);
							$female_invst_number[] = $explode[1];
					}
					$male_invst_number = array_unique($male_invst_number);
					$female_invst_number = array_unique($female_invst_number);
					foreach($male_invst_number as $key => $val){
						$investigation_array['male_investigation'][] = array('male_investigation_name' => $_POST['male_investigation_name_'.$val], 'male_investigation_code' => $_POST['male_investigation_code_'.$val], 'male_investigation_price' => $_POST['male_investigation_price_'.$val], 'male_investigation_discount'=>$_POST['male_investigation_discount_'.$val]);
					}
					foreach($female_invst_number as $key => $val){
						$investigation_array['female_investigation'][] = array('female_investigation_name' => $_POST['female_investigation_name_'.$val], 'female_investigation_code' => $_POST['female_investigation_code_'.$val], 'female_investigation_price' => $_POST['female_investigation_price_'.$val], 'female_investigation_discount'=>$_POST['female_investigation_discount_'.$val]);
					}
					$post_arr['investigations'] = serialize($investigation_array);
					$investigation_billed = 1;
				}
                if($medicine_billed == 0){
                    $medicine_billed = check_suggested($post_arr['consultation_done'], 'medicine_suggestion', 'medicine_billed');
                }
                if($investigation_billed == 0){
                    $investigation_billed = check_suggested($post_arr['consultation_done'], 'investation_suggestion', 'investigation_billed');
                }
                if($procedure_billed == 0){
                    $procedure_billed = check_suggested($post_arr['consultation_done'], 'procedure_suggestion', 'procedure_billed');
                }
				$investg = $this->billingmodel_model->investigation_insert($post_arr);
				if($investg > 0){
					$insert_receipt = insert_receipt_log($post_arr['receipt_number']);
					$update_doctor_consultation = $this->billingmodel_model->update_doctor_consultation($post_arr['receipt_number'], $post_arr['consultation_done'], $medicine_billed, $investigation_billed, $procedure_billed);
					$this->send_billing_receipt($post_arr['biller_id'], $post_arr['patient_id'], $post_arr['on_date'], $post_arr['billing_from'], $post_arr['receipt_number'], 'investigation');
					header("location:" .base_url(). "accounts/details/".$post_arr['receipt_number']."?t=investigation");
					die();
				}else{
					header("location:" .base_url(). "after-consultation?m=".base64_encode('something went wrong!').'&t='.base64_encode('error'));
					die();
				}
			}
			if (isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == "add_procedure") {
					unset($_POST['action']);
					
					// --- 1. INITIALIZE COMMON VARIABLES ---
					$post_arr = array();
					$post_arr['patient_id'] = $_POST['patient_id']; unset($_POST['patient_id']);
					$post_arr['appointment_id'] = $_POST['appointment_id']; unset($_POST['appointment_id']);
					$post_arr['consultation_done'] = $_POST['consultation_done']; unset($_POST['consultation_done']);
					$post_arr['billing_from'] = $_POST['billing_from']; unset($_POST['billing_from']);
					$post_arr['billing_at'] = $_POST['billing_at']; unset($_POST['billing_at']);
					$post_arr['on_date'] = $_POST['on_date']; unset($_POST['on_date']);
					$post_arr['billing_id'] = isset($_POST['billing_id']) ? $_POST['billing_id'] : ''; unset($_POST['billing_id']);
					$post_arr['biller_id'] = $_POST['biller_id']; unset($_POST['biller_id']);
					$post_arr['billing_type'] = $_POST['billing_type'];
					$post_arr['transaction_id'] = ($_POST['transaction_id']) ? $_POST['transaction_id'] : 0; unset($_POST['transaction_id']);
					$post_arr['subvention_charges'] = isset($_POST['subvention_charges']) ? $_POST['subvention_charges'] : 0; unset($_POST['subvention_charges']);
					
					// Handle Main Transaction Image (Global)
					$transaction_img = '';
					if (!empty($_FILES['transaction_img']['tmp_name'])) {
						$dest_path = $this->config->item('upload_path');
						$destination = $dest_path . 'patient_files/';
						$NewImageName = rand(4, 10000) . "-" . $post_arr['patient_id'] . "-" . $_FILES['transaction_img']['name'];
						$transaction_img = base_url() . 'assets/patient_files/' . $NewImageName;
						move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination . $NewImageName);
						$post_arr['transaction_img'] = $transaction_img;
					}

					$post_arr['hospital_id'] = isset($_POST['hospital_id']) ? $_POST['hospital_id'] : ''; unset($_POST['hospital_id']);
					
					// Handle Package Form (Global)
					$package_form = '';
					if (!empty($_FILES['package_form']['tmp_name'])) {
						$dest_path = $this->config->item('upload_path');
						$destination = $dest_path . 'package_form/';
						$NewImageName = rand(4, 10000) . "-" . $post_arr['patient_id'] . "-" . $_FILES['package_form']['name']; 
						$package_form = base_url() . 'assets/package_form/' . $NewImageName;
						move_uploaded_file($_FILES['package_form']['tmp_name'], $destination . $NewImageName);
					}
					$post_arr['package_form'] = $package_form;

					// Common Payment Details
					$post_arr['payment_method'] = $_POST['payment_method']; unset($_POST['payment_method']);
					$post_arr['cash_payment'] = $_POST['cash_payment']; unset($_POST['cash_payment']);
					$post_arr['card_payment'] = $_POST['card_payment']; unset($_POST['card_payment']);
					$post_arr['upi_payment'] = $_POST['upi_payment']; unset($_POST['upi_payment']);
					$post_arr['neft_payment'] = $_POST['neft_payment']; unset($_POST['neft_payment']);
					$post_arr['wallet_payment'] = $_POST['wallet_payment']; unset($_POST['wallet_payment']);
					$post_arr['origins'] = $_POST['origins']; unset($_POST['origins']);
					$post_arr['series_number'] = $_POST['series_number']; unset($_POST['series_number']);
					$post_arr['expiry_date'] = $_POST['expiry_date']; unset($_POST['expiry_date']);
					$post_arr['renewal_type'] = $_POST['renewal_type']; unset($_POST['renewal_type']);
					$post_arr['status'] = 'pending';

					// Global Currency Logic (used for center share calculation)
					if ($_POST['payment_in'] == 'rs_payment') {
						$post_arr['payment_done'] = $_POST['payment_done']; unset($_POST['payment_done']);
						$post_arr['remaining_amount'] = $_POST['remaining_amount']; unset($_POST['remaining_amount']);
						$post_arr['fees'] = ($_POST['rs_fees']); unset($_POST['rs_fees']);
						$post_arr['totalpackage'] = $_POST['rs_totalpackage']; unset($_POST['rs_totalpackage']);
						$post_arr['discount_amount'] = $_POST['rs_discount']; unset($_POST['rs_discount']);
					} else {
						$post_arr['usd_totalpackage'] = $_POST['usd_totalpackage']; unset($_POST['usd_totalpackage']);
						$post_arr['usd_fees'] = $_POST['usd_fees']; unset($_POST['usd_fees']);
						$post_arr['us_discount'] = $_POST['us_discount']; unset($_POST['us_discount']);
						$post_arr['conversion_rate'] = get_converstion_rate();
						$post_arr['payment_done'] = ($_POST['payment_done'] * get_converstion_rate()); unset($_POST['payment_done']);
						$post_arr['remaining_amount'] = ($_POST['remaining_amount'] * get_converstion_rate()); unset($_POST['remaining_amount']);
						$post_arr['fees'] = ($_POST['rs_totalpackage'] - $_POST['rs_discount']); unset($_POST['rs_fees']);
						$post_arr['totalpackage'] = $_POST['rs_totalpackage']; unset($_POST['rs_totalpackage']);
						$post_arr['discount_amount'] = $_POST['rs_discount']; unset($_POST['rs_discount']);
					}
					
					$post_arr['center_share'] = $post_arr['fees'];
					$post_arr['share'] = 1;
					$post_arr['payment_in'] = $_POST['payment_in']; unset($_POST['payment_in']);

					// --- 2. FETCH STATIC DATA ONCE (Optimization) ---
					// We fetch Patient, Doctor, and Appointment details here because they don't change inside the loop
					
					// Consultation Data
					$sql_con = "SELECT * FROM " . $this->config->item('db_prefix') . "consultation WHERE patient_id = '" . $post_arr['patient_id'] . "' ORDER BY id ASC";
					$select_con = run_select_query($sql_con);
					$visit_month = date('F y', strtotime($select_con['on_date']));
					$first_visit_date = date('Y-m-d', strtotime($select_con['on_date']));

					// Doctor Data from Consultation
					$sql_doct_con = "SELECT * FROM " . $this->config->item('db_prefix') . "consultation WHERE appointment_id = '" . $post_arr['appointment_id'] . "'";
					$select_doc_con = run_select_query($sql_doct_con);

					// Counsellor Data
					$sql_doctor_consultation = "SELECT * FROM " . $this->config->item('db_prefix') . "doctor_consultation WHERE appointment_id = '" . $post_arr['appointment_id'] . "'";
					$select_doctor_consultation = run_select_query($sql_doctor_consultation);

					// Doctor Name
					$sql3 = "Select * from " . $this->config->item('db_prefix') . "doctors where ID='" . $select_doc_con['doctor_id'] . "'";
					$select_result3 = run_select_query($sql3);

					// Patient Phone
					$sql4 = "Select * from " . $this->config->item('db_prefix') . "appointments where paitent_id='" . $post_arr['patient_id'] . "'";
					$select_result4 = run_select_query($sql4);

					// CRM Lead ID and Name
					$sql5 = "Select * from " . $this->config->item('db_prefix') . "appointments where wife_phone='" . $select_result4['wife_phone'] . "' and paitent_type='new_patient'";
					$select_result5 = run_select_query($sql5);


					// --- 3. PROCESS EACH PROCEDURE (LOOP START) ---
					if (isset($_POST['procedure_suggestion']) && $_POST['procedure_suggestion'] == 1) {
						
						// Identify which sub_procedures were submitted (e.g., 1, 2, 3, 4)
						$extract_procedure_array = array();
						foreach ($_POST as $key => $val) {
							if (substr($key, 0, 14) === "sub_procedure_") {
								$extract_procedure_array[] = $key;
							}
						}
						
						$male_number = array();
						foreach ($extract_procedure_array as $key => $val) {
							$explode = explode('sub_procedure_', $val);
							$male_number[] = $explode[1];
						}
						$male_number = array_unique($male_number);

						// Loop through specific procedures
						foreach ($male_number as $key => $val) {
							
							// A. GET DYNAMIC VALUES FOR THIS ITERATION
							$receipt_number = $_POST['receipt_number_' . $val];
							
							if ($this->receipt_number_exists($receipt_number)) {
								header("location:" . base_url() . "after-consultation-step-2?t=procedure&m=" . base64_encode('Receipt number already exists!'));
								die();
							}

							// Get individual price details
							$loop_totalpackage = $_POST['sub_procedures_price_' . $val];
							$loop_discount_amount = $_POST['sub_procedures_discount_' . $val];
							$loop_fees = $loop_totalpackage - $loop_discount_amount;
							$loop_payment_done = $_POST['sub_procedures_paid_price_' . $val];
							$loop_remaining_amount = $loop_fees - $loop_payment_done;
							
							// Handle Individual Receipt Image
							$receipt_image_path = ''; 
							if (!empty($_FILES['receipt_image_' . $val]['tmp_name'])) {
								$dest_path = $this->config->item('upload_path');
								$destination = $dest_path . 'patient_files/';
								if (!is_dir($destination)) { mkdir($destination, 0755, true); }
								$NewImageName = rand(4, 10000) . "-" . $post_arr['patient_id'] . "-" . $_FILES['receipt_image_' . $val]['name'];
								$receipt_image_path = base_url() . 'assets/patient_files/' . $NewImageName;
								move_uploaded_file($_FILES['receipt_image_' . $val]['tmp_name'], $destination . $NewImageName);
							}
							// If no specific image, use global transaction image, else use specific
							$final_receipt_img = ($receipt_image_path != '') ? $receipt_image_path : $post_arr['transaction_img'];

							// Prepare data serialization for DB
							$procedure_array = [
								'patient_procedures' => [
									[
										'sub_procedure' => $_POST['sub_procedure_' . $val],
										'sub_procedures_code' => $_POST['sub_procedures_code_' . $val],
										'sub_procedures_price' => $loop_totalpackage,
										'sub_procedures_discount' => $loop_discount_amount,
										'sub_procedures_paid_price' => $loop_payment_done
									]
								]
							];
							
							$serialized_data = serialize($procedure_array);
							$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
							$formattedDate = $date->format('Y-m-d H:i:s');

							// B. INSERT INTO DATABASE
							$query = "INSERT INTO `hms_patient_procedure` 
							(appointment_id, consultation_done, patient_id, procedure_parent, on_date, 
							receipt_number,transaction_img, billing_id, biller_id, transaction_id,
							hospital_id, payment_in, data,procedure_id,procedure_name,code,category,procedures, broad_procedure, broad_procedure_count, agent, councellor, center_share, fees, totalpackage, discount_amount, 
							payment_done, wallet_payment, remaining_amount, payment_method, billing_from, 
							billing_at, package_form, status, origins) 
							VALUES 
							(
							'" . $post_arr['appointment_id'] . "',
								'" . $post_arr['consultation_done'] . "',
								'" . $post_arr['patient_id'] . "',
								'" . $post_arr['procedure_parent'] . "',
								'" . $formattedDate . "',
								'" . $receipt_number . "',
								'" . $final_receipt_img . "',
								'" . $post_arr['billing_id'] . "',
								'" . $post_arr['biller_id'] . "',
								'" . $post_arr['transaction_id'] . "',
								'" . $post_arr['hospital_id'] . "',
								'" . $post_arr['payment_in'] . "',
								'" . $serialized_data . "',
								'" . $_POST['sub_procedure_' . $val] . "',
								'" . $_POST['procedure_name_' . $val] . "',
								'" . $_POST['sub_procedures_code_' . $val] . "',
								'" . $_POST['sub_procedures_category_' . $val] . "',
								'" . $_POST['procedures_' . $val] . "',
								'" . $_POST['broad_procedure_' . $val] . "',
								'" . $_POST['broad_procedure_count_' . $val] . "',
								'" . $_POST['agent_' . $val] . "',
								'" . $_POST['councellor_' . $val] . "',
								'" . $loop_fees . "',
								'" . $loop_fees . "',
								'" . $loop_totalpackage . "',
								'" . $loop_discount_amount . "',
								'" . $loop_payment_done . "',
								'" . $post_arr['wallet_payment'] . "',
								'" . $loop_remaining_amount . "',
								'" . $post_arr['payment_method'] . "',
								'" . $post_arr['billing_from'] . "',
								'" . $post_arr['billing_at'] . "',
								'" . $post_arr['package_form'] . "',
								'pending',
								'" . $post_arr['origins'] . "')";
							
							$result = run_form_query($query);

							// ==================================================================
							// C. API INTEGRATION (INSIDE LOOP TO SEND ALL RECORDS)
							// ==================================================================
							
							// 1. Fetch Details for CURRENT Procedure ID
							$current_proc_id = $_POST['sub_procedure_' . $val];
							$procedure_sql = "SELECT ID, procedure_name, category, code FROM hms_procedures WHERE ID = '$current_proc_id'";
							$proc_result = run_select_query($procedure_sql);

							// 2. Prepare API Payload
							$data = array(
								"lead_id" => trim($select_result5['crm_id']),
								"visit_month" => $visit_month,
								"first_visit_date" => $first_visit_date,
								"doctor_consulted" => $select_result3['name'],
								"ch_fc_name" => $select_doctor_consultation['counsellor_signature'],
								"booking_month" => date('F d', strtotime($post_arr['on_date'])),
								"booking_date" => $post_arr['on_date'],
								"patient_id" => $post_arr['patient_id'],
								"patients_name" => $select_result5['wife_name'],
								"patients_source" => $select_result5['lead_source'],
								"centre_booking" => $post_arr['billing_from'],
								
								// Use Fetched Procedure Info
								"procedure_type" => $proc_result['category'],
								"procedure_type_name" => $proc_result['procedure_name'] . ', ' . (new DateTime($post_arr['on_date']))->format('Y-m-d'),
								"procedure_code" => $proc_result['code'],
								
								// Use Local Loop Values (Important!)
								"package_amount" => $loop_totalpackage,
								"discount_amount" => $loop_discount_amount,
								"package_after_discount" => $loop_fees,
								"payment_received" => $loop_payment_done
							);

							$jsonData = json_encode($data);

							//var_dump($data);
							//die();

							// 3. Send to APIs
							$urls = [
								'lead_1' => 'https://flertility.in/lead/lead-journey/',
								'lead_2' => 'https://staging.flertility.in/lead/lead-journey/'
							];

							foreach ($urls as $label => $url) {
								$curl = curl_init();
								curl_setopt_array($curl, array(
									CURLOPT_URL => $url,
									CURLOPT_RETURNTRANSFER => true,
									CURLOPT_ENCODING => '',
									CURLOPT_MAXREDIRS => 10,
									CURLOPT_TIMEOUT => 5, 
									CURLOPT_FOLLOWLOCATION => true,
									CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
									CURLOPT_CUSTOMREQUEST => 'POST',
									CURLOPT_POSTFIELDS => $jsonData,
									CURLOPT_HTTPHEADER => array(
										'Content-Type: application/json',
										'Accept: application/json'
									),
								));
								$response = curl_exec($curl);
								curl_close($curl);
							}
							// ==================================================================

						} // End Foreach Loop
						
						$procedure_billed = 1;
					} // End If Procedure Suggestion

					// --- 4. CLEANUP & REDIRECT ---
					// Logic for flags
					if ($medicine_billed == 0) {
						$medicine_billed = check_suggested($post_arr['consultation_done'], 'medicine_suggestion', 'medicine_billed');
					}
					if ($investigation_billed == 0) {
						$investigation_billed = check_suggested($post_arr['consultation_done'], 'investation_suggestion', 'investigation_billed');
					}
					if ($procedure_billed == 0) {
						$procedure_billed = check_suggested($post_arr['consultation_done'], 'procedure_suggestion', 'procedure_billed');
					}

					// Update Logs and Redirect
					$insert_receipt = insert_receipt_log($post_arr['receipt_number']); // Note: This might log only the last receipt if variable overwritten, usually acceptable in batch logs
					$update_doctor_consultation = $this->billingmodel_model->update_doctor_consultation($post_arr['receipt_number'], $post_arr['consultation_done'], $medicine_billed, $investigation_billed, $procedure_billed);
					$this->send_billing_receipt($post_arr['biller_id'], $post_arr['patient_id'], $post_arr['on_date'], $post_arr['billing_from'], $post_arr['receipt_number'], 'procedure');
					
					header("location:" . base_url() . "accounts/details/" . $post_arr['receipt_number'] . "?t=procedure");
					die();
				}
			if (isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == "add_package") {
				unset($_POST['action']);
					
					// --- 1. INITIALIZE COMMON VARIABLES ---
					$post_arr = array();
					$post_arr['patient_id'] = $_POST['patient_id']; unset($_POST['patient_id']);
					$post_arr['appointment_id'] = $_POST['appointment_id']; unset($_POST['appointment_id']);
					$post_arr['consultation_done'] = $_POST['consultation_done']; unset($_POST['consultation_done']);
					$post_arr['billing_from'] = $_POST['billing_from']; unset($_POST['billing_from']);
					$post_arr['billing_at'] = $_POST['billing_at']; unset($_POST['billing_at']);
					$post_arr['on_date'] = $_POST['on_date']; unset($_POST['on_date']);
					$post_arr['billing_id'] = isset($_POST['billing_id']) ? $_POST['billing_id'] : ''; unset($_POST['billing_id']);
					$post_arr['biller_id'] = $_POST['biller_id']; unset($_POST['biller_id']);
					$post_arr['billing_type'] = $_POST['billing_type'];
					$post_arr['transaction_id'] = ($_POST['transaction_id']) ? $_POST['transaction_id'] : 0; unset($_POST['transaction_id']);
					$post_arr['subvention_charges'] = isset($_POST['subvention_charges']) ? $_POST['subvention_charges'] : 0; unset($_POST['subvention_charges']);
					
					// Handle Main Transaction Image (Global)
					$transaction_img = '';
					if (!empty($_FILES['transaction_img']['tmp_name'])) {
						$dest_path = $this->config->item('upload_path');
						$destination = $dest_path . 'patient_files/';
						$NewImageName = rand(4, 10000) . "-" . $post_arr['patient_id'] . "-" . $_FILES['transaction_img']['name'];
						$transaction_img = base_url() . 'assets/patient_files/' . $NewImageName;
						move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination . $NewImageName);
						$post_arr['transaction_img'] = $transaction_img;
					}

					$post_arr['hospital_id'] = isset($_POST['hospital_id']) ? $_POST['hospital_id'] : ''; unset($_POST['hospital_id']);
					
					// Handle Package Form (Global)
					$package_form = '';
					if (!empty($_FILES['package_form']['tmp_name'])) {
						$dest_path = $this->config->item('upload_path');
						$destination = $dest_path . 'package_form/';
						$NewImageName = rand(4, 10000) . "-" . $post_arr['patient_id'] . "-" . $_FILES['package_form']['name']; 
						$package_form = base_url() . 'assets/package_form/' . $NewImageName;
						move_uploaded_file($_FILES['package_form']['tmp_name'], $destination . $NewImageName);
					}
					$post_arr['package_form'] = $package_form;

					// Common Payment Details
					$post_arr['payment_method'] = $_POST['payment_method']; unset($_POST['payment_method']);
					$post_arr['cash_payment'] = $_POST['cash_payment']; unset($_POST['cash_payment']);
					$post_arr['card_payment'] = $_POST['card_payment']; unset($_POST['card_payment']);
					$post_arr['upi_payment'] = $_POST['upi_payment']; unset($_POST['upi_payment']);
					$post_arr['neft_payment'] = $_POST['neft_payment']; unset($_POST['neft_payment']);
					$post_arr['wallet_payment'] = $_POST['wallet_payment']; unset($_POST['wallet_payment']);
					$post_arr['origins'] = $_POST['origins']; unset($_POST['origins']);
					$post_arr['series_number'] = $_POST['series_number']; unset($_POST['series_number']);
					$post_arr['expiry_date'] = $_POST['expiry_date']; unset($_POST['expiry_date']);
					$post_arr['renewal_type'] = $_POST['renewal_type']; unset($_POST['renewal_type']);
					$post_arr['status'] = 'pending';

					// Global Currency Logic (used for center share calculation)
					if ($_POST['payment_in'] == 'rs_payment') {
						$post_arr['payment_done'] = $_POST['payment_done']; unset($_POST['payment_done']);
						$post_arr['remaining_amount'] = $_POST['remaining_amount']; unset($_POST['remaining_amount']);
						$post_arr['fees'] = ($_POST['rs_fees']); unset($_POST['rs_fees']);
						$post_arr['totalpackage'] = $_POST['rs_totalpackage']; unset($_POST['rs_totalpackage']);
						$post_arr['discount_amount'] = $_POST['rs_discount']; unset($_POST['rs_discount']);
					} else {
						$post_arr['usd_totalpackage'] = $_POST['usd_totalpackage']; unset($_POST['usd_totalpackage']);
						$post_arr['usd_fees'] = $_POST['usd_fees']; unset($_POST['usd_fees']);
						$post_arr['us_discount'] = $_POST['us_discount']; unset($_POST['us_discount']);
						$post_arr['conversion_rate'] = get_converstion_rate();
						$post_arr['payment_done'] = ($_POST['payment_done'] * get_converstion_rate()); unset($_POST['payment_done']);
						$post_arr['remaining_amount'] = ($_POST['remaining_amount'] * get_converstion_rate()); unset($_POST['remaining_amount']);
						$post_arr['fees'] = ($_POST['rs_totalpackage'] - $_POST['rs_discount']); unset($_POST['rs_fees']);
						$post_arr['totalpackage'] = $_POST['rs_totalpackage']; unset($_POST['rs_totalpackage']);
						$post_arr['discount_amount'] = $_POST['rs_discount']; unset($_POST['rs_discount']);
					}
					
					$post_arr['center_share'] = $post_arr['fees'];
					$post_arr['share'] = 1;
					$post_arr['payment_in'] = $_POST['payment_in']; unset($_POST['payment_in']);

					// --- 2. FETCH STATIC DATA ONCE (Optimization) ---
					// We fetch Patient, Doctor, and Appointment details here because they don't change inside the loop
					
					// Consultation Data
					$sql_con = "SELECT * FROM " . $this->config->item('db_prefix') . "consultation WHERE patient_id = '" . $post_arr['patient_id'] . "' ORDER BY id ASC";
					$select_con = run_select_query($sql_con);
					$visit_month = date('F y', strtotime($select_con['on_date']));
					$first_visit_date = date('Y-m-d', strtotime($select_con['on_date']));

					// Doctor Data from Consultation
					$sql_doct_con = "SELECT * FROM " . $this->config->item('db_prefix') . "consultation WHERE appointment_id = '" . $post_arr['appointment_id'] . "'";
					$select_doc_con = run_select_query($sql_doct_con);

					// Counsellor Data
					$sql_doctor_consultation = "SELECT * FROM " . $this->config->item('db_prefix') . "doctor_consultation WHERE appointment_id = '" . $post_arr['appointment_id'] . "'";
					$select_doctor_consultation = run_select_query($sql_doctor_consultation);

					// Doctor Name
					$sql3 = "Select * from " . $this->config->item('db_prefix') . "doctors where ID='" . $select_doc_con['doctor_id'] . "'";
					$select_result3 = run_select_query($sql3);

					// Patient Phone
					$sql4 = "Select * from " . $this->config->item('db_prefix') . "appointments where paitent_id='" . $post_arr['patient_id'] . "'";
					$select_result4 = run_select_query($sql4);

					// CRM Lead ID and Name
					$sql5 = "Select * from " . $this->config->item('db_prefix') . "appointments where wife_phone='" . $select_result4['wife_phone'] . "' and paitent_type='new_patient'";
					$select_result5 = run_select_query($sql5);


					// --- 3. PROCESS EACH PROCEDURE (LOOP START) ---
					if (isset($_POST['procedure_suggestion']) && $_POST['procedure_suggestion'] == 1) {
						
						// Identify which sub_procedures were submitted (e.g., 1, 2, 3, 4)
						$extract_procedure_array = array();
						foreach ($_POST as $key => $val) {
							if (substr($key, 0, 14) === "sub_procedure_") {
								$extract_procedure_array[] = $key;
							}
						}
						
						$male_number = array();
						foreach ($extract_procedure_array as $key => $val) {
							$explode = explode('sub_procedure_', $val);
							$male_number[] = $explode[1];
						}
						$male_number = array_unique($male_number);

						// Loop through specific procedures
						foreach ($male_number as $key => $val) {
							
							// A. GET DYNAMIC VALUES FOR THIS ITERATION
							$receipt_number = $_POST['receipt_number_' . $val];
							
							if ($this->receipt_number_exists($receipt_number)) {
								header("location:" . base_url() . "after-consultation-step-2?t=procedure&m=" . base64_encode('Receipt number already exists!'));
								die();
							}

							// Get individual price details
							$loop_totalpackage = $_POST['sub_procedures_price_' . $val];
							$loop_discount_amount = $_POST['sub_procedures_discount_' . $val];
							$loop_fees = $loop_totalpackage - $loop_discount_amount;
							$loop_payment_done = $_POST['sub_procedures_paid_price_' . $val];
							$loop_remaining_amount = $loop_fees - $loop_payment_done;
							
							// Handle Individual Receipt Image
							$receipt_image_path = ''; 
							if (!empty($_FILES['receipt_image_' . $val]['tmp_name'])) {
								$dest_path = $this->config->item('upload_path');
								$destination = $dest_path . 'patient_files/';
								if (!is_dir($destination)) { mkdir($destination, 0755, true); }
								$NewImageName = rand(4, 10000) . "-" . $post_arr['patient_id'] . "-" . $_FILES['receipt_image_' . $val]['name'];
								$receipt_image_path = base_url() . 'assets/patient_files/' . $NewImageName;
								move_uploaded_file($_FILES['receipt_image_' . $val]['tmp_name'], $destination . $NewImageName);
							}
							// If no specific image, use global transaction image, else use specific
							$final_receipt_img = ($receipt_image_path != '') ? $receipt_image_path : $post_arr['transaction_img'];

							// Prepare data serialization for DB
							$procedure_array = [
								'patient_procedures' => [
									[
										'sub_procedure' => $_POST['sub_procedure_' . $val],
										'sub_procedures_code' => $_POST['sub_procedures_code_' . $val],
										'sub_procedures_price' => $loop_totalpackage,
										'sub_procedures_discount' => $loop_discount_amount,
										'sub_procedures_paid_price' => $loop_payment_done
									]
								]
							];
							
							$serialized_data = serialize($procedure_array);
							$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
							$formattedDate = $date->format('Y-m-d H:i:s');

							// B. INSERT INTO DATABASE
							$query = "INSERT INTO `hms_patient_procedure` 
							(appointment_id, consultation_done, patient_id, procedure_parent, on_date, 
							receipt_number,transaction_img, billing_id, biller_id, transaction_id,
							hospital_id, payment_in, data,procedure_id,procedure_name,code,category,procedures, broad_procedure, broad_procedure_count, agent, councellor, center_share, fees, totalpackage, discount_amount, 
							payment_done, wallet_payment, remaining_amount, payment_method, billing_from, 
							billing_at, package_form, status, origins) 
							VALUES 
							(
							'" . $post_arr['appointment_id'] . "',
								'" . $post_arr['consultation_done'] . "',
								'" . $post_arr['patient_id'] . "',
								'" . $post_arr['procedure_parent'] . "',
								'" . $formattedDate . "',
								'" . $receipt_number . "',
								'" . $final_receipt_img . "',
								'" . $post_arr['billing_id'] . "',
								'" . $post_arr['biller_id'] . "',
								'" . $post_arr['transaction_id'] . "',
								'" . $post_arr['hospital_id'] . "',
								'" . $post_arr['payment_in'] . "',
								'" . $serialized_data . "',
								'" . $_POST['sub_procedure_' . $val] . "',
								'" . $_POST['procedure_name_' . $val] . "',
								'" . $_POST['sub_procedures_code_' . $val] . "',
								'" . $_POST['sub_procedures_category_' . $val] . "',
								'" . $_POST['procedures_' . $val] . "',
								'" . $_POST['broad_procedure_' . $val] . "',
								'" . $_POST['broad_procedure_count_' . $val] . "',
								'" . $_POST['agent_' . $val] . "',
								'" . $_POST['councellor_' . $val] . "',
								'" . $loop_fees . "',
								'" . $loop_fees . "',
								'" . $loop_totalpackage . "',
								'" . $loop_discount_amount . "',
								'" . $loop_payment_done . "',
								'" . $post_arr['wallet_payment'] . "',
								'" . $loop_remaining_amount . "',
								'" . $post_arr['payment_method'] . "',
								'" . $post_arr['billing_from'] . "',
								'" . $post_arr['billing_at'] . "',
								'" . $post_arr['package_form'] . "',
								'pending',
								'" . $post_arr['origins'] . "')";
							
							$result = run_form_query($query);

							// ==================================================================
							// C. API INTEGRATION (INSIDE LOOP TO SEND ALL RECORDS)
							// ==================================================================
							
							// 1. Fetch Details for CURRENT Procedure ID
							$current_proc_id = $_POST['sub_procedure_' . $val];
							$procedure_sql = "SELECT ID, procedure_name, category, code FROM hms_procedures WHERE ID = '$current_proc_id'";
							$proc_result = run_select_query($procedure_sql);

							// 2. Prepare API Payload
							$data = array(
								"lead_id" => trim($select_result5['crm_id']),
								"visit_month" => $visit_month,
								"first_visit_date" => $first_visit_date,
								"doctor_consulted" => $select_result3['name'],
								"ch_fc_name" => $select_doctor_consultation['counsellor_signature'],
								"booking_month" => date('F d', strtotime($post_arr['on_date'])),
								"booking_date" => $post_arr['on_date'],
								"patient_id" => $post_arr['patient_id'],
								"patients_name" => $select_result5['wife_name'],
								"patients_source" => $select_result5['lead_source'],
								"centre_booking" => $post_arr['billing_from'],
								
								// Use Fetched Procedure Info
								"procedure_type" => $proc_result['category'],
								"procedure_type_name" => $proc_result['procedure_name'] . ', ' . (new DateTime($post_arr['on_date']))->format('Y-m-d'),
								"procedure_code" => $proc_result['code'],
								
								// Use Local Loop Values (Important!)
								"package_amount" => $loop_totalpackage,
								"discount_amount" => $loop_discount_amount,
								"package_after_discount" => $loop_fees,
								"payment_received" => $loop_payment_done
							);

							$jsonData = json_encode($data);

							//var_dump($data);
							//die();

							// 3. Send to APIs
							$urls = [
								'lead_1' => 'https://flertility.in/lead/lead-journey/',
								'lead_2' => 'https://staging.flertility.in/lead/lead-journey/'
							];

							foreach ($urls as $label => $url) {
								$curl = curl_init();
								curl_setopt_array($curl, array(
									CURLOPT_URL => $url,
									CURLOPT_RETURNTRANSFER => true,
									CURLOPT_ENCODING => '',
									CURLOPT_MAXREDIRS => 10,
									CURLOPT_TIMEOUT => 5, 
									CURLOPT_FOLLOWLOCATION => true,
									CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
									CURLOPT_CUSTOMREQUEST => 'POST',
									CURLOPT_POSTFIELDS => $jsonData,
									CURLOPT_HTTPHEADER => array(
										'Content-Type: application/json',
										'Accept: application/json'
									),
								));
								$response = curl_exec($curl);
								curl_close($curl);
							}
							// ==================================================================

						} // End Foreach Loop
						
						$procedure_billed = 1;
					} // End If Procedure Suggestion

					// --- 4. CLEANUP & REDIRECT ---
					// Logic for flags
					if ($medicine_billed == 0) {
						$medicine_billed = check_suggested($post_arr['consultation_done'], 'medicine_suggestion', 'medicine_billed');
					}
					if ($investigation_billed == 0) {
						$investigation_billed = check_suggested($post_arr['consultation_done'], 'investation_suggestion', 'investigation_billed');
					}
					if ($procedure_billed == 0) {
						$procedure_billed = check_suggested($post_arr['consultation_done'], 'procedure_suggestion', 'procedure_billed');
					}

					// Update Logs and Redirect
					$insert_receipt = insert_receipt_log($post_arr['receipt_number']); // Note: This might log only the last receipt if variable overwritten, usually acceptable in batch logs
					$update_doctor_consultation = $this->billingmodel_model->update_doctor_consultation($post_arr['receipt_number'], $post_arr['consultation_done'], $medicine_billed, $investigation_billed, $procedure_billed);
					$this->send_billing_receipt($post_arr['biller_id'], $post_arr['patient_id'], $post_arr['on_date'], $post_arr['billing_from'], $post_arr['receipt_number'], 'procedure');
					
					header("location:" . base_url() . "accounts/details/" . $post_arr['receipt_number'] . "?t=procedure");
					die();
				}
			if(isset($_GET['t']) && !empty($_GET['t']) && isset($_GET['i']) && !empty($_GET['i'])){
				$consultation_details = $this->billingmodel_model->after_consultation_billing($_GET['i']);
				if(count($consultation_details) > 0){
					$data['billing_details'] = $consultation_details;
					$data['converstion_rate'] = get_converstion_rate();
					$template = get_header_template($logg['role']);
					$this->load->view($template['header']);
					$this->load->view('billing_view/billing_after_consultation', $data);
					$this->load->view($template['footer']);
				}else{
					header("location:" .base_url(). "after-consultation?m=".base64_encode('Patient not found!').'&t='.base64_encode('error'));
					die();			
				}

			}else{

				header("location:" .base_url(). "after-consultation?m=".base64_encode('consultation not found!').'&t='.base64_encode('error'));

				die();

			}

		}else{
         
			header("location:" .base_url(). "");

			die();

		}

	} 
	
	function search_consultation_done(){ 

		$response = array();

		$phone_number = '';

		$search_this = $_POST['search_this'];

		$search_by = $_POST['search_by'];

			

		if($search_this != ''){

			$patient = $this->billingmodel_model->search_consultation_done($search_this, $search_by);

			if(count($patient) > 0){

				echo json_encode($patient);

				die;

			}else{

				$response = array('status' => 0, 'message'=> 'Consultation not found!');

				echo json_encode($response);

				die;

			}

		}else{

			$response = array('status' => 0, 'message'=> 'Phone number/IIC ID is required');

			echo json_encode($response);

			die;

		}

	}



	function upload_package_form($receipt_number){

		$package_form = '';

		if(!empty($_FILES['package_form']['tmp_name'])){

			$dest_path = $this->config->item('upload_path');

			$destination = $dest_path.'package_form/';

			$NewImageName = rand(4,10000)."-".$receipt_number."-". $_FILES['package_form']['name'];

			$package_form = base_url().'assets/package_form/'.$NewImageName;

			move_uploaded_file($_FILES['package_form']['tmp_name'], $destination.$NewImageName);

			$_POST['package_form'] = $package_form;

		}

		$update_package = $this->billingmodel_model->update_package_form($_POST, $receipt_number);

		if($update_package > 0){

			header("location:" .base_url(). "accounts/details/$receipt_number?t=procedure&m=".base64_encode('Package form updated!'));

			die();

		}else{

			header("location:" .base_url(). "accounts/details/$receipt_number?t=procedure&?m=".base64_encode('Something went wrong !'));

			die();

		}

	}

	

	function search_appointment(){ 

		$response = array();

		$phone_number = '';

		$search_this = $_POST['search_this'];

		$search_by = $_POST['search_by'];

		

			

		if($search_this != ''){

			$appointments = $this->billingmodel_model->search_appointment($search_this, $search_by);

			if(count($appointments) > 0){

				$response = array('status' => 'appointment_booked', 'message'=> 'Appointment already booked. Go to<a href="'.base_url('my_appointments').'"> my appointments</a>');

				echo json_encode($response);

				die;

			}else{

				$patient = $this->billingmodel_model->search_patient($search_this, $search_by);

				if(count($patient) > 0){

					$patient_id = $patient['patient_id'];

					$response = array('status' => 'exist_patient', 'message'=> 'Old Paitent', 'uhid' => $patient_id, 'patient'=>$patient);

					echo json_encode($response);

					die;

				}else{

					$uhid = getGUID();

					$response = array('status' => 'new_patient', 'message'=> 'New Paitent', 'uhid' => $uhid);

					echo json_encode($response);

					die;

				}

			}	

		}else{

			$response = array('status' => 0, 'message'=> 'Phone number/IIC ID is required');

			echo json_encode($response);

			die;

		}

		//var_dump($_POST);die;		

	}

	

	public function booking(){
		$logg = checklogin();
		if($logg['status'] == true){
			unset($_POST['action']);unset($_POST['phone_number']);
			$patient_phone = $_POST['wife_phone'];
			if($_POST['paitent_type'] == "exist_patient" && !empty($_POST['paitent_id'])){
				$check_patient_medical_info = check_patient_medical_info($_POST['paitent_id']);
				if($check_patient_medical_info == 1){
					$_POST['follow_up_appointment'] = 1;
				}
 			}
 			$appointments = $this->billingmodel_model->search_appointment($patient_phone, "phone");
			if(count($appointments) > 0){
				header("location:" .base_url(). "appointment?m=".base64_encode('Appointment already booked. Go to my appointments').'&t='.base64_encode('error'));
				die();
			}
			$_POST['appointment_added'] = date('Y-m-d H:i:s');
			$patient_id =getiic();
			$curl = curl_init();
				if (strpos($_POST['lead_source'], "D/S") === false) {
					$data = [
						"api_key" => "83661358790533838050723166537248941TTR",
						"name" => $_POST['wife_name'],
						"mobile" => $_POST['wife_phone'],
						"country_code" => "+91",
						"lead_source" => $_POST['lead_source'],
						"lead_source_url" => "HMS",
						"from_hms" => "true",
						"city" => "",
						"email" => $_POST['wife_email'],
						"center_id" => $_POST['appoitment_for'],
						"patient_id" => $_POST['paitent_id'],
					];
				} else {
					echo "Lead source contains 'D/S'. Data not sent.";
				}
				$jsonData = json_encode($data);
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://flertility.in/lead/create-lead-api/',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => $jsonData,
					CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json'
					),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				$appointment = $this->billingmodel_model->insert_appointments($_POST);
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://flertility.in/lead/lead-mobile-no/?mobile_no=" . urlencode($_POST['wife_phone']),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'GET',
				));
				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
				if ($err) {
					echo "cURL Error: $err";
				} else {
					$leadData = json_decode($response, true); // Decode JSON to associative array
					if (!empty($leadData) && isset($leadData[0])) {
						$lead = $leadData[0];
						$this->db->where('wife_phone', $lead['mobile']);
						$this->db->update('hms_appointments', ['crm_id' => $lead['id']]);
					} else {
						echo "No lead data found.";
					}
				}
				if($appointment > 0){
						$centre_details = get_centre_details($_POST['appoitment_for']);
						$checkpatient_register = get_patient_detail_by_phone($_POST['wife_phone']);
						if(empty($checkpatient_register)){
						whatsappregister($patient_phone, json_encode(array("name" => $_POST['wife_name'], "iic_id" => $paitent_id, "center" => $centre_details['center_name'], "billed"=> '1')));
					}
					$doctor_details = doctor_details($_POST['appoitmented_doctor']);
					$centre_details = get_centre_details($_POST['appoitment_for']);
					$appointwhatmsg = array();
					$appointwhatmsg = array($_POST['wife_name'], $centre_details['center_name'], date("d-m-Y", strtotime($_POST['appoitmented_date'])), $_POST['appoitmented_slot'], isset($centre_details['center_location'])?$centre_details['center_location']:"");
					$appointsendmsg = whatsappappointment($patient_phone, implode(',', $appointwhatmsg));
					$patient_to = $patient_subject = $patient_message = $doctor_to = $doctor_subject = $doctor_message = "";
					$patient_to = $_POST['wife_email'];
					$patient_subject = "Appointment booked";
					$patient_message = "Hi ".$_POST['wife_name'].",<br/> Your appointment has been scheduled with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($_POST['appoitmented_date']))." at ".$_POST['appoitmented_slot'].".";
					$sms_message = "Hi ".$_POST['wife_name'].", Your appointment has been scheduled with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($_POST['appoitmented_date']))." at ".$_POST['appoitmented_slot'].".";
					// send_mail($patient_to, $patient_subject, $patient_message);
					send_sms($patient_phone, $sms_message);
					$doctor_to = $doctor_details['email'];
					$doctor_subject = "New appointment";
					$doctor_message = "Hi Dr.".$doctor_details['name'].",<br/> Appointment has been scheduled on ".date("d-m-Y", strtotime($_POST['appoitmented_date']))." at ".$_POST['appoitmented_slot'].".";
					// send_mail($doctor_to, $doctor_subject, $doctor_message);
					header("location:" .base_url(). "appointment?m=".base64_encode('Appointment booked!').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "appointment?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	

	/*public function search_doctor(){
		$centre_id = $_POST['centre_id'];
		$doctors = $this->doctors_model->center_doctors($centre_id);
		$option = "";
		$option = "<option value=''>Select</option>";
		if(!empty($doctors)){
			foreach($doctors as $key => $val){
				$option .= "<option value='".$val['ID']."'>".$val['name']."</option>";
			}
		}
		echo json_encode($option);
		die;		
	}*/

	public function search_doctor()
    {
        $centre_id = $this->input->post('centre_id');
        
        if (empty($centre_id)) {
            echo '<option value="">Invalid Centre</option>';
            return;
        }

        $doctors = $this->doctors_model->center_doctors($centre_id);

        $option = '<option value="">Select Doctor</option>';

        if (!empty($doctors)) {
            foreach ($doctors as $val) {
                $option .= '<option value="' . $val['ID'] . '">' . htmlspecialchars($val['name'], ENT_QUOTES, 'UTF-8') . '</option>';
            }
        } else {
            $option .= '<option value="">No doctors found</option>';
        }

        echo $option;
    }

	

	public function doctor_slots(){

		$booking_date = $_POST['selected'];

		$doctor_id = $_POST['appoitmented_doctor'];

		$timestamp = strtotime($booking_date);

		$booking_day = strtolower(date('l', $timestamp));

		$doctor_available = 0;

		$doctor_on_holiday = $this->doctors_model->doctor_on_holiday($doctor_id);

		if($doctor_on_holiday != 0){

			$explode = explode(" - ", $doctor_on_holiday);

			

			$selected_date = date('Y-m-d', strtotime($booking_date));

			$contractDateBegin = date('Y-m-d', strtotime($explode[0]));

			$contractDateEnd = date('Y-m-d', strtotime($explode[1]));

			if (($selected_date >= $contractDateBegin) && ($selected_date <= $contractDateEnd)){

				$doctor_available = 1;

			}

		}

		

		if($doctor_available == 1){

			$option = "<option value=''>No slot - doctor on holiday</option>";

			echo json_encode($option, JSON_UNESCAPED_SLASHES);

			die;

		}else{

			$doctor_slots = $this->doctors_model->doctor_slots($doctor_id, $booking_day);

			$appointmented_slots = array();

			$doctor_appointments = $this->doctors_model->doctor_appointments($doctor_id, $booking_date);

			

			$option = "";

			if(!empty($doctor_slots)){

				if(!empty($doctor_appointments)){

					foreach($doctor_appointments as $key => $vla){

						$appointmented_slots[] = $vla['appoitmented_slot'];

					}

				}

				$option = "<option value=''>--Select slot--</option>";

				foreach($doctor_slots as $key => $vals){

					if(!in_array($vals, $appointmented_slots,true)){

						$option .= "<option value='".$vals."'>".$vals."</option>";

					}

				}

			}else{

				$option = "<option value=''>No available slot</option>";

			}

			echo json_encode($option, JSON_UNESCAPED_SLASHES);

			die;

		}

	}



	//export_billing

	public function export_billing(){

		if(isset($_SESSION['logged_accountant'])){

			$center = $_SESSION['logged_accountant']['center'];

		}else if(isset($_SESSION['logged_billing_manager'])){

			$center = $_SESSION['logged_billing_manager']['center'];

		}else{

			$center = 0;

		}

		$start = isset($_GET['start'])?$_GET['start']:"";

		$end = isset($_GET['end'])?$_GET['end']:"";
        $type = isset($_GET['type'])?$_GET['type']:"";
		
		if(isset($_GET['type']) && !empty($_GET['type']) && $_GET['type'] == "billing_from"){
		    $center = $_GET['billing_from'];
		}
		if(isset($_GET['type']) && !empty($_GET['type']) && $_GET['type'] == "billing_at"){
		    $center = $_GET['billing_at'];
		}


		$data = $this->billingmodel_model->export_billing_data($start, $end, $center, $type);

		//var_dump($data);die;

		//ob_clean();

		header('Content-Type: text/csv; charset=utf-8');

		header('Content-Disposition: attachment; filename=Export-Billing-'.$start.'-'.$end.'.csv');

		$fp = fopen('php://output','w');

		$headers = 'Receipt Number, IIC ID, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type, Date, Status';

		//Add the headers

		fwrite($fp, $headers. "\r\n");

		foreach ($data as $key => $val) {//var_dump($val);die;

			$billing_from = $val['billing_from'];

			if($billing_from != "IndiaIVF"){

				$billing_from = get_center_name($billing_from);

			}

			$billing_at = get_center_name($val['billing_at']);

			$lead_arr = array($val['receipt_number'], $val['patient_id'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);

            fputcsv($fp, $lead_arr);

		}

		//close file

		fclose($fp);

		exit();

	}

/**********Billing Noreceipt Consultation**********/
public function billing_noreceipt(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$receipt_number = $this->input->get('receipt_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "billing-noreceipt";
        	$config["total_rows"] = $this->billingmodel_model->billing_noreceipt_count($receipt_number, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['billing_noreceipt_consultation'] = $this->billingmodel_model->billing_noreceipt_patination($config["per_page"], $per_page, $receipt_number, $start_date, $end_date, $patient_id);
			
			$data["receipt_number"] = $receipt_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billing_view/billing_noreceipt', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
} 

/**********Billing Noreceipt Procedure**********/
public function billing_noreceipt_procedure(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$receipt_number = $this->input->get('receipt_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "billing_noreceipt_procedure";
        	$config["total_rows"] = $this->billingmodel_model->procedure_noreceipt_count($receipt_number, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_billing_noreceipt'] = $this->billingmodel_model->procedure_noreceipt_pagination($config["per_page"], $per_page, $receipt_number, $start_date, $end_date, $patient_id);
			
			$data["receipt_number"] = $receipt_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billing_view/billing_noreceipt_procedure', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
} 

/**********Billing Noreceipt Investigation**********/
public function billing_noreceipt_investigation(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$receipt_number = $this->input->get('receipt_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "billing_noreceipt_investigation";
        	$config["total_rows"] = $this->billingmodel_model->investigation_noreceipt_count($receipt_number, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigation_billing_noreceipt'] = $this->billingmodel_model->investigation_noreceipt_pagination($config["per_page"], $per_page, $receipt_number, $start_date, $end_date, $patient_id);
			
			$data["receipt_number"] = $receipt_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billing_view/billing_noreceipt_investigation', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
} 

/**********Billing Noreceipt Patient Payments**********/
public function billing_noreceipt_patient_payments(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$billing_id = $this->input->get('billing_id', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "billing_noreceipt_patient_payments";
        	$config["total_rows"] = $this->billingmodel_model->patient_payments_noreceipt_count($billing_id, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['patient_payments_billing_noreceipt'] = $this->billingmodel_model->patient_payments_noreceipt_pagination($config["per_page"], $per_page, $billing_id, $start_date, $end_date, $patient_id);
			
			$data["billing_id"] = $billing_id;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billing_view/billing_noreceipt_patient_payments', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
} 

	//Upload Receipt

	public function upload_receipt($patient_id, $receipt_number, $billing_type, $payment_method,$record_id=''){

		$logg = checklogin();

		if($logg['status'] == true){

			

			if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == "upload_receipt"){

				$post_arr = array();

				unset($_POST['action']);

				

				$transaction_id = $_POST['transaction_id'];

				$transaction_img = '';

				if(!empty($_FILES['transaction_img']['tmp_name'])){

					$dest_path = $this->config->item('upload_path');

					$destination = $dest_path.'patient_files/';

					$NewImageName = rand(4,10000).time()."-".$_FILES['transaction_img']['name'];

					$transaction_img = base_url().'assets/patient_files/'.$NewImageName;

					move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);

				}



				$upload_receipt = $this->billingmodel_model->upload_billing_receipt($patient_id, $receipt_number, $billing_type, $transaction_id, $transaction_img, $_POST['record_id']);

				if($upload_receipt > 0){

					header("location:" .base_url(). "billing-noreceipt?m=".base64_encode('Receipt Uploaded Successfully').'&t='.base64_encode('success'));

					die();

				}else{

					header("location:" .base_url(). "billing-noreceipt?m=".base64_encode('something went wrong!').'&t='.base64_encode('error'));

					die();

				}

			}

			$data = array();

			$data['patient_id'] = $patient_id;

			$data['receipt_number'] = $receipt_number;

			$data['billing_type'] = $billing_type;

			$data['payment_method'] = $payment_method;
			$data['record_id'] = $record_id;



			$template = get_header_template($logg['role']);

			$this->load->view($template['header']);

			$this->load->view('billing_view/upload_receipt', $data);

			$this->load->view($template['footer']);

		}else{

			header("location:" .base_url(). "");

			die();

		}

	}

		

	//QUERY

	function doctor_name($doctor_id){

		$doctor_name = $this->doctors_model->get_doctor_data($doctor_id);

		return $doctor_name['name'];

	}

	

	function doctor_fees($doctor_id, $nation){

		$doctor_fees = $this->doctors_model->doctor_fees($doctor_id, $nation);

		return $doctor_fees;

	}

	

	function get_code($type){

		$codes = $this->billings_model->get_code($type);

		return $codes;

	}

	

	function get_center_list(){

		$center = $this->center_model->get_centers();

		return $center;

	}

	

	function get_sub_procedures(){

		$patient_id = $_POST['patient_id'];

		$parent_procedure = $_POST['parent_parents'];

		$patient_data =  get_patient_detail($patient_id);

		$nationality = $patient_data['nationality'];

		$sub = $this->billings_model->get_sub_procedures($parent_procedure);

		$html = '';

		if(count($sub) > 0){ 

			foreach($sub as $key => $val){ $fees = ''; if($nationality == 'indian'){$fees = $val['price'];}else{$fees = $val['usd_price'];}

				$html .= '<option value="'.$val['ID'].'" code="'.$val['code'].'" fees="'.$fees.'">'.$val['procedure_name'].'</option>';

			}

		}

		$response = array();

		$response = array('html' => $html);

		echo json_encode($response);

		die;

	}

	function get_center(){

		if(isset($_SESSION['logged_billing_manager'])){ 

			$username = $_SESSION['logged_billing_manager']['username'];

			$center = $this->billings_model->get_center($username);

			return $center;

		}

	}	

	function get_medicine_details($medicine){

		$details = $this->billingmodel_model->get_medicine_details($medicine);

		return $details;

	}	

	function get_brand_details($brand){

		$details = $this->billingmodel_model->get_brand_details($brand);

		return $details;

	}

	function get_investigation_details($investigation){

		$details = $this->billingmodel_model->get_investigation_details($investigation);

		return $details;

	}
	
	function get_master_investigation_details($investigation){
		$details = $this->billingmodel_model->get_master_investigation_details($investigation);
		return $details;

	}

	

	function get_procedure_details($procedure){
		$details = $this->billingmodel_model->get_procedure_details($procedure);
		return $details;

	}

	function get_center_name($center){
		$name = $this->accounts_model->get_center_name($center);
		return $name;
	}



	function get_medicine_name($medicine){

		$name = $this->billings_model->get_medicine_name($medicine);

		return $name;

	}

	function get_brand_name($brand){

		$name = $this->billings_model->get_brand_name($brand);

		return $name;

	}

	function get_investigation_name($investig){

		$name = $this->accounts_model->get_investigation_name($investig);

		return $name;

	}

	function get_procedure_name($procedure){

		$name = $this->billings_model->get_procedure_name($procedure);

		return $name;

	}
	
	function get_patient_name($patient_id){

		$name = $this->billings_model->get_patient_name($patient_id);

		return $name;

	}
	
	function doctor_referral_list(){
		$doctor_referral = $this->billings_model->get_doctor_referral();
		
		return $doctor_referral;
	}

	function get_counsellor_list(){
		$counsellor_name = $this->billings_model->get_counsellor();
		
		return $counsellor_name;
	}
	
	public function print(){
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML('<h1>Hello world!</h1>');
		$mpdf->Output();
	}
	
	function check_procedure_exit_in_center($procedure_code, $center_id)
	{
		$ci = &get_instance();
		$ci->load->database();
		$sql = "SELECT 1 
				FROM hms_procedures 
				WHERE code = '".$procedure_code."'
				AND FIND_IN_SET('".$center_id."', center_id)
				LIMIT 1";
		$q = $ci->db->query($sql);
		return ($q->num_rows() > 0);
	}

	function get_hub_center_id_from_spoke($center_id)
	{
		$ci = &get_instance();
		$ci->load->database();
		$sql = "SELECT hub_center_id 
				FROM hms_hub_spoke_relationships 
				WHERE spoke_center_id = '".$center_id."' 
				LIMIT 1";
		$q = $ci->db->query($sql);
		if ($q->num_rows() > 0) {
			return $q->row()->hub_center_id;
		}
		return null;
	}

public function get_camps_by_center() {
        $center_id = $this->input->post('center_id');
        if (!$center_id) {
            echo '<option value="">Invalid Center</option>';
            return;
        }

        $sql = "SELECT c.camp_number, c.camp_name 
                FROM ".$this->config->item('db_prefix')."camps c 
                LEFT JOIN ".$this->config->item('db_prefix')."centers cen ON c.center_id = cen.ID 
                WHERE cen.center_number=? AND c.status='1' 
                ORDER BY c.camp_name ASC";

        $q = $this->db->query($sql, [$center_id]);
        $result = $q->result_array();

        echo '<option value="">Select Camp</option>';
        if (!empty($result)) {
            foreach ($result as $camp) {
                echo '<option value="'.$camp['camp_number'].'">'.$camp['camp_name'].'</option>';
            }
        } else {
            echo '<option value="">No camps found</option>';
        }
    }



}