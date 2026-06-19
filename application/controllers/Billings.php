<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billings extends CI_Controller {

	public function __construct()
	{
		// Load parent's constructor.
       	parent::__construct();
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
		$this->load->library('session');
		$this->load->model('billings_model');
		$this->load->model('doctors_model');
		$this->load->model('investigation_model');
		$this->load->model('procedures_model');
		$this->load->model('accounts_model');
		$this->load->model('stock_model');
		$this->load->model('center_model');
		$this->load->model('patients_model');
		$this->load->model('employee_model');
		$this->load->helper('myhelper');
		$this->load->library("pagination");
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
			$patient_id = $this->input->get('patient_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->billings_model->export_consultation_billings($employee_number, $start_date, $end_date,  $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Consultation-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Receipt Number,IIC ID, Total Package,Discount Amount, Payment Done,Remaining Amount,, Payment Method,  Center, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['receipt_number'],$val['patient_id'], $val['totalpackage'], $val['discount_amount'], $val['payment_done'],$val['remaining_amount'], $val['payment_method'],  $val['billing_at'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "billings/consultation_billings";
        	$config["total_rows"] = $this->billings_model->consultation_billings_count($employee_number, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['consultation_result'] = $this->billings_model->consultation_billings_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id);
			
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billings/consultation_billings', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
}

public function registation_billings(){
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
			$patient_id = $this->input->get('patient_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->billings_model->export_registation_billings($employee_number, $start_date, $end_date,  $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Registion-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Receipt Number,IIC ID,Patient Name,  Total Package,Discount Amount, Payment Done,Remaining Amount,, Payment Method,  Center, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['receipt_number'],$val['patient_id'], $val['wife_name'], $val['totalpackage'], $val['discount_amount'], $val['payment_done'],$val['remaining_amount'], $val['payment_method'],  $val['billing_at'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "billings/registation_billings";
        	$config["total_rows"] = $this->billings_model->registation_billings_count($employee_number, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['registation_result'] = $this->billings_model->registation_billings_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id);
			
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billings/registation_billings', $data);
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
			$patient_id = $this->input->get('patient_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->billings_model->export_investigations_billings($employee_number, $start_date, $end_date,  $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Investigation-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Receipt Number,IIC ID, Total Package,Discount Amount, Payment Done,Remaining Amount,, Payment Method,  Center, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['receipt_number'],$val['patient_id'], $val['totalpackage'], $val['discount_amount'], $val['payment_done'],$val['remaining_amount'], $val['payment_method'],  $val['billing_at'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "billings/investigation_billings";
        	$config["total_rows"] = $this->billings_model->investigation_billings_count($employee_number, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->billings_model->investigation_billings_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id);
			
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billings/investigation_billings', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
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
			$patient_id = $this->input->get('patient_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->billings_model->export_procedure_billings($employee_number, $start_date, $end_date,  $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Investigation-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Receipt Number,IIC ID, Total Package,Discount Amount, Payment Done,Remaining Amount,, Payment Method,  Center, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['receipt_number'],$val['patient_id'], $val['totalpackage'], $val['discount_amount'], $val['payment_done'],$val['remaining_amount'], $val['payment_method'],  $val['billing_at'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "billings/procedure_billings";
        	$config["total_rows"] = $this->billings_model->procedure_billings_count($employee_number, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->billings_model->procedure_billings_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id);
			
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billings/procedure_billings', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
}
	
	public function iic_share_update($receipt){
		$type = $_GET['t'];
		$share = $_GET['s'];
		$update_billing = $this->billings_model->iic_share_update($receipt, $type, $share);
		if($update_billing > 0){
			header("location:" .base_url(). "billings/procedure_billings?m=".base64_encode('IIC share updated successfully !').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "billings/procedure_billings?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	public function disapproved($receipt){
		$data = array();
		$logg = checklogin();
		if($logg['status'] == true){
			$data = $data_arr = array();
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_disapproved_billing'){
				unset($_POST['action']);
				if(isset($_POST['discount_code']) && !empty($_POST['discount_code'])){
					$discount_code = $_POST['discount_code'];unset($_POST['discount_code']);
					$update_disapproved_billing = $this->billings_model->update_discount_avail($discount_code);	
				}
				if($_POST['type'] == 'consultation'){
					$update_billing = $this->update_consultation_disapproved_billing($_POST, $_FILES);
				}else if($_POST['type'] == 'investigation'){
					$update_billing = $this->update_investigation_disapproved_billing($_POST, $_FILES);
				}else if($_POST['type'] == 'procedure'){
					$update_billing = $this->update_procedure_disapproved_billing($_POST, $_FILES);
				}else{
					header("location:" .base_url(). "billings/billings?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
				if($update_billing > 0){
					$update_disapproved_billing = $this->billings_model->update_disapproved_billing($receipt, $_POST['type']);
					header("location:" .base_url(). "billings/billings?m=".base64_encode('Billing updated successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "billings/billings?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			if(isset($_GET['t']) && !empty($_GET['t'])){
				$data = $this->billings_model->get_billings_details($receipt, $_GET['t']);
				if(empty($data)){
					header("location:" .base_url(). "billings/billings?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
				$data['data'] = $data;
				$check_discound_applied = $this->billings_model->check_discound_applied($receipt);
				$data['discound_applied'] = $check_discound_applied;
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				if($_GET['t'] == 'consultation'){
					$data['doctors'] = $this->doctors_model->get_doctors_list();
					$this->load->view('billings/consultation_disapproved', $data);
				}else if($_GET['t'] == 'investigation'){
					$data['investigations'] = $this->investigation_model->get_investigations_list();
					$this->load->view('billings/investigation_disapproved', $data);
				}else if($_GET['t'] == 'procedure'){
					$data['procedure'] = $this->procedures_model->get_procedures_list();
					$data['consumables'] = $this->stock_model->get_consumbles_list();
					$data['injections'] = $this->stock_model->get_injection_list();
					$data['medicine'] = $this->stock_model->get_medicine_list();
					$this->load->view('billings/procedure_disapproved', $data);
				}else{
					header("location:" .base_url(). "billings/billings?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "billings/billings?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	// update consultation rebilling
	function update_consultation_disapproved_billing($posts, $files){
		$paitent_id = $posts['patient_id'];
		$receipt_number = $posts['receipt_number'];
		
		/******************* WIFE DETAILS *********************/
		//wife pan
		$wife_pan_card = '';
		if(!empty($files['wife_pan_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $files['wife_pan_card']['name'];
				$wife_pan_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($files['wife_pan_card']['tmp_name'], $destination.$NewImageName);
				$posts['wife_pan_card'] = $wife_pan_card;
		}
		//wife adhar
		$wife_adhar_card = '';
		if(!empty($files['wife_adhar_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $files['wife_adhar_card']['name'];
				$wife_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($files['wife_adhar_card']['tmp_name'], $destination.$NewImageName);
				$posts['wife_adhar_card'] = $wife_adhar_card;
		}
		//wife photo
		$wife_photo = '';
		if(!empty($files['wife_photo']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $files['wife_photo']['name'];
			$wife_photo = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['wife_photo']['tmp_name'], $destination.$NewImageName);
			$posts['wife_photo'] = $wife_photo;
		}
		/******************* WIFE DETAILS *********************/
					
		/******************* HUSBAND DETAILS *********************/
		//husband pan
		$husband_pan_card = '';
		if(!empty($files['husband_pan_card']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $files['husband_pan_card']['name'];
			$husband_pan_card = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['husband_pan_card']['tmp_name'], $destination.$NewImageName);
			$posts['husband_pan_card'] = $husband_pan_card;
		}
		//husband adhar
		$husband_adhar_card = '';
		if(!empty($files['husband_adhar_card']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $files['husband_adhar_card']['name'];
			$husband_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['husband_adhar_card']['tmp_name'], $destination.$NewImageName);
			$posts['husband_adhar_card'] = $husband_adhar_card;
		}
		//husband photo
		$husband_photo = '';
		if(!empty($files['husband_photo']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $files['husband_photo']['name'];
			$husband_photo = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['husband_photo']['tmp_name'], $destination.$NewImageName);
			$posts['husband_photo'] = $husband_photo;
		}
		/******************* HUSBAND DETAILS *********************/
		$transaction_img = '';
		if(!empty($files['transaction_img']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-". $files['transaction_img']['name'];
			$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['transaction_img']['tmp_name'], $destination.$NewImageName);
			$posts['transaction_img'] = $transaction_img;
		}
	   unset($posts['type']);unset($posts['patient_id']);unset($posts['receipt_number']);
  	   
	   $posts = array_filter($posts);
	   $patient_fields = $this->get_patient_fields();

	   $patient_arr = array();
	   foreach($posts as $eky => $vls){
	   		if(in_array($eky, $patient_fields)){
		   		$patient_arr[$eky] = $posts[$eky];
				unset($posts[$eky]);
			}
	   }

	   $patient_arr['modified_on'] = date("Y-m-d H:i:s");
	   $posts['modified_on'] = date("Y-m-d H:i:s");
	   $update_patients = $this->billings_model->update_patients($patient_arr, $paitent_id);
	   $update_consultation = $this->billings_model->update_consultation($posts, $receipt_number);
	   return $update_consultation;
	}
	// update investigation rebilling
	function update_investigation_disapproved_billing($posts, $files){
		//var_dump($posts);die;
		// echo '<br/><br/><br/>---------------------------------------<br/><br/><br/>';
		// var_dump($files);die;
		
		$paitent_id = $posts['patient_id'];
		$receipt_number = $posts['receipt_number'];
		
		/******************* WIFE DETAILS *********************/
		//wife pan
		$wife_pan_card = '';
		if(!empty($files['wife_pan_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $files['wife_pan_card']['name'];
				$wife_pan_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($files['wife_pan_card']['tmp_name'], $destination.$NewImageName);
				$posts['wife_pan_card'] = $wife_pan_card;
		}
		//wife adhar
		$wife_adhar_card = '';
		if(!empty($files['wife_adhar_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $files['wife_adhar_card']['name'];
				$wife_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($files['wife_adhar_card']['tmp_name'], $destination.$NewImageName);
				$posts['wife_adhar_card'] = $wife_adhar_card;
		}
		//wife photo
		$wife_photo = '';
		if(!empty($files['wife_photo']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $files['wife_photo']['name'];
			$wife_photo = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['wife_photo']['tmp_name'], $destination.$NewImageName);
			$posts['wife_photo'] = $wife_photo;
		}
		/******************* WIFE DETAILS *********************/
					
		/******************* HUSBAND DETAILS *********************/
		//husband pan
		$husband_pan_card = '';
		if(!empty($files['husband_pan_card']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $files['husband_pan_card']['name'];
			$husband_pan_card = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['husband_pan_card']['tmp_name'], $destination.$NewImageName);
			$posts['husband_pan_card'] = $husband_pan_card;
		}
		//husband adhar
		$husband_adhar_card = '';
		if(!empty($files['husband_adhar_card']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $files['husband_adhar_card']['name'];
			$husband_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['husband_adhar_card']['tmp_name'], $destination.$NewImageName);
			$posts['husband_adhar_card'] = $husband_adhar_card;
		}
		//husband photo
		$husband_photo = '';
		if(!empty($files['husband_photo']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $files['husband_photo']['name'];
			$husband_photo = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['husband_photo']['tmp_name'], $destination.$NewImageName);
			$posts['husband_photo'] = $husband_photo;
		}
		/******************* HUSBAND DETAILS *********************/
		$transaction_img = '';
		if(!empty($files['transaction_img']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-". $files['transaction_img']['name'];
			$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['transaction_img']['tmp_name'], $destination.$NewImageName);
			$posts['transaction_img'] = $transaction_img;
		}
	   unset($posts['type']);unset($posts['patient_id']);unset($posts['receipt_number']);
  	   
	   $posts = array_filter($posts);
	   $patient_fields = $this->get_patient_fields();
	   
	   $patient_arr = array();
	   foreach($posts as $eky => $vls){
	   		if(in_array($eky, $patient_fields)){
		   		$patient_arr[$eky] = $posts[$eky];
				unset($posts[$eky]);
			}
	   }

	   $patient_arr['modified_on'] = date("Y-m-d H:i:s");
	   $posts['modified_on'] = date("Y-m-d H:i:s");
	   
	   $update_patients = $this->billings_model->update_patients($patient_arr, $paitent_id);
	   $update_investigation = $this->billings_model->update_investigation($posts, $receipt_number);
	   return $update_investigation;
	}
	// update procedure rebilling
	function update_procedure_disapproved_billing($posts, $files){
		// var_dump($posts);die;
		// echo '<br/><br/><br/>---------------------------------------<br/><br/><br/>';
		// var_dump($files);die;
		
		$paitent_id = $posts['patient_id'];
		$receipt_number = $posts['receipt_number'];
		
		/******************* WIFE DETAILS *********************/
		//wife pan
		$wife_pan_card = '';
		if(!empty($files['wife_pan_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $files['wife_pan_card']['name'];
				$wife_pan_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($files['wife_pan_card']['tmp_name'], $destination.$NewImageName);
				$posts['wife_pan_card'] = $wife_pan_card;
		}
		//wife adhar
		$wife_adhar_card = '';
		if(!empty($files['wife_adhar_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $files['wife_adhar_card']['name'];
				$wife_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($files['wife_adhar_card']['tmp_name'], $destination.$NewImageName);
				$posts['wife_adhar_card'] = $wife_adhar_card;
		}
		//wife photo
		$wife_photo = '';
		if(!empty($files['wife_photo']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $files['wife_photo']['name'];
			$wife_photo = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['wife_photo']['tmp_name'], $destination.$NewImageName);
			$posts['wife_photo'] = $wife_photo;
		}
		/******************* WIFE DETAILS *********************/
					
		/******************* HUSBAND DETAILS *********************/
		//husband pan
		$husband_pan_card = '';
		if(!empty($files['husband_pan_card']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $files['husband_pan_card']['name'];
			$husband_pan_card = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['husband_pan_card']['tmp_name'], $destination.$NewImageName);
			$posts['husband_pan_card'] = $husband_pan_card;
		}
		//husband adhar
		$husband_adhar_card = '';
		if(!empty($files['husband_adhar_card']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $files['husband_adhar_card']['name'];
			$husband_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['husband_adhar_card']['tmp_name'], $destination.$NewImageName);
			$posts['husband_adhar_card'] = $husband_adhar_card;
		}
		//husband photo
		$husband_photo = '';
		if(!empty($files['husband_photo']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $files['husband_photo']['name'];
			$husband_photo = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['husband_photo']['tmp_name'], $destination.$NewImageName);
			$posts['husband_photo'] = $husband_photo;
		}
		/******************* HUSBAND DETAILS *********************/
		$transaction_img = '';
		if(!empty($files['transaction_img']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-". $files['transaction_img']['name'];
			$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['transaction_img']['tmp_name'], $destination.$NewImageName);
			$posts['transaction_img'] = $transaction_img;
		}
		
		$package_form_old = '';
		if(!empty($files['package_form_old']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-". $files['package_form_old']['name'];
			$package_form_old = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['package_form_old']['tmp_name'], $destination.$NewImageName);
			$posts['package_form_old'] = $package_form_old;
		}
		
		$package_form = '';
		if(!empty($files['package_form']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'patient_files/';
			$NewImageName = rand(4,10000)."-".$paitent_id."-". $files['package_form']['name'];
			$package_form = base_url().'assets/patient_files/'.$NewImageName;
			move_uploaded_file($files['package_form']['tmp_name'], $destination.$NewImageName);
			$posts['package_form'] = $package_form;
		}
	   unset($posts['type']);unset($posts['patient_id']);unset($posts['receipt_number']);
  	   
	   $posts = array_filter($posts);
	   $patient_fields = $this->get_patient_fields();
		
	   $patient_arr = array();
	   foreach($posts as $eky => $vls){
	   		if(in_array($eky, $patient_fields)){
		   		$patient_arr[$eky] = $posts[$eky];
				unset($posts[$eky]);
			}
	   }
	   
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

				// var_dump($_POST); 
				// echo '<br/><br/><br/><br/>';
				// die;

				if(!empty($c_counter)){
					foreach($c_counter as $key => $ccounte){
						if($_POST['sub_procedure_'.$ccounte] == ''){
							unset($_POST['sub_procedure_'.$ccounte]);
							unset($_POST['sub_procedures_code_'.$ccounte]);
							unset($_POST['sub_procedures_price_'.$ccounte]);
							unset($_POST['sub_procedures_discount_'.$ccounte]);
							unset($_POST['sub_procedures_paid_price_'.$ccounte]);
						}else{
							$c_counte[] = array('sub_procedure'=> $_POST['sub_procedure_'.$ccounte],'sub_procedures_code'=> $_POST['sub_procedures_code_'.$ccounte],'sub_procedures_price'=> $_POST['sub_procedures_price_'.$ccounte],'sub_procedures_discount'=> $_POST['sub_procedures_discount_'.$ccounte],'sub_procedures_paid_price'=> $_POST['sub_procedures_paid_price_'.$ccounte]);
						}
					}
				}
								
				$details = array();
				$details['patient_procedures'] = $c_counte;
				$posts['data'] = serialize($details);
		$patient_arr['modified_on'] = date("Y-m-d H:i:s");
	    $posts['modified_on'] = date("Y-m-d H:i:s");
	    unset($posts['sub_procedure_1']);
        unset($posts['sub_procedures_code_1']);
        unset($posts['sub_procedures_price_1']);
        unset($posts['sub_procedures_discount_1']);
		 unset($posts['sub_procedures_paid_price_1']);
	    $update_patients = $this->billings_model->update_patients($patient_arr, $paitent_id);
		
		$update_procedure = $this->billings_model->update_procedure($posts, $receipt_number);
		
	    return $update_procedure;
	}
	
	function get_patient_fields(){
		$result = array();
		$sql = "SHOW COLUMNS from ".$this->config->item('db_prefix')."patients";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
			$fields = array();
			foreach($result as $key => $val){
				$fields[] = $val['Field'];
			}
            return $fields;
        }
        else
        {
            return $result;
        }
	}

	public function add()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_billing'){
				unset($_POST['action']);
				
				$paitent_id = $_POST['paitent_id'];
				if(!empty($paitent_id)){
					$paitent_type = $nationality = '';
					$paitent_type = $_POST['paitent_type'];
					if($paitent_type == 'new_patient'){
						$patient_arr = array();
						$patient_arr['patient_id'] = $_POST['paitent_id'];
						$patient_arr['patient_phone'] = $_POST['wife_phone'];
						/******************* WIFE DETAILS *********************/
						$patient_arr['wife_name'] = $_POST['wife_name'];
						$patient_arr['wife_phone'] = $_POST['wife_phone'];
						$patient_arr['wife_email'] = $_POST['wife_email'];
						//wife pan
						$patient_arr['wife_pan_number'] = $_POST['wife_pan_number'];
						$wife_pan_card = '';
						if(!empty($_FILES['wife_pan_card']['tmp_name'])){
							$dest_path = $this->config->item('upload_path');
							$destination = $dest_path.'patient_files/';
							$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $_FILES['wife_pan_card']['name'];
							$wife_pan_card = base_url().'assets/patient_files/'.$NewImageName;
							move_uploaded_file($_FILES['wife_pan_card']['tmp_name'], $destination.$NewImageName);
							$patient_arr['wife_pan_card'] = $wife_pan_card;
						}
						//wife adhar
						$patient_arr['wife_adhar_number'] = $_POST['wife_adhar_number'];
						$wife_adhar_card = '';
						if(!empty($_FILES['wife_adhar_card']['tmp_name'])){
							$dest_path = $this->config->item('upload_path');
							$destination = $dest_path.'patient_files/';
							$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $_FILES['wife_adhar_card']['name'];
							$wife_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
							move_uploaded_file($_FILES['wife_adhar_card']['tmp_name'], $destination.$NewImageName);
							$patient_arr['wife_adhar_card'] = $wife_adhar_card;
						}
						//wife photo
						$wife_photo = '';
						if(!empty($_FILES['wife_photo']['tmp_name'])){
							$dest_path = $this->config->item('upload_path');
							$destination = $dest_path.'patient_files/';
							$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $_FILES['wife_photo']['name'];
							$wife_photo = base_url().'assets/patient_files/'.$NewImageName;
							move_uploaded_file($_FILES['wife_photo']['tmp_name'], $destination.$NewImageName);
							$patient_arr['wife_photo'] = $wife_photo;
						}
						$patient_arr['wife_age'] = $_POST['wife_age'];
						$patient_arr['wife_address'] = $_POST['wife_address'];
						/******************* WIFE DETAILS *********************/
						
						/******************* HUSBAND DETAILS *********************/
						$patient_arr['husband_name'] = $_POST['husband_name'];
						$patient_arr['husband_phone'] = $_POST['husband_phone'];
						$patient_arr['husband_email'] = $_POST['husband_email'];
						//husband pan
						$patient_arr['husband_pan_number'] = $_POST['husband_pan_number'];
						$husband_pan_card = '';
						if(!empty($_FILES['husband_pan_card']['tmp_name'])){
							$dest_path = $this->config->item('upload_path');
							$destination = $dest_path.'patient_files/';
							$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $_FILES['husband_pan_card']['name'];
							$husband_pan_card = base_url().'assets/patient_files/'.$NewImageName;
							move_uploaded_file($_FILES['husband_pan_card']['tmp_name'], $destination.$NewImageName);
							$patient_arr['husband_pan_card'] = $husband_pan_card;
						}
						//husband adhar
						$patient_arr['husband_adhar_number'] = $_POST['husband_adhar_number'];
						$husband_adhar_card = '';
						if(!empty($_FILES['husband_adhar_card']['tmp_name'])){
							$dest_path = $this->config->item('upload_path');
							$destination = $dest_path.'patient_files/';
							$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $_FILES['husband_adhar_card']['name'];
							$husband_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
							move_uploaded_file($_FILES['husband_adhar_card']['tmp_name'], $destination.$NewImageName);
							$patient_arr['husband_adhar_card'] = $husband_adhar_card;
						}
						//husband photo
						$husband_photo = '';
						if(!empty($_FILES['husband_photo']['tmp_name'])){
							$dest_path = $this->config->item('upload_path');
							$destination = $dest_path.'patient_files/';
							$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $_FILES['husband_photo']['name'];
							$husband_photo = base_url().'assets/patient_files/'.$NewImageName;
							move_uploaded_file($_FILES['husband_photo']['tmp_name'], $destination.$NewImageName);
							$patient_arr['husband_photo'] = $husband_photo;
						}
						$patient_arr['husband_age'] = $_POST['husband_age'];
						$patient_arr['husband_address'] = $_POST['husband_address'];
						/******************* HUSBAND DETAILS *********************/
						
						$patient_arr['patient_source'] = $_POST['patient_source'];
						$patient_arr['reference_from'] = $_POST['reference_from'];
						$patient_arr['nationality'] = $_POST['nationality'];
						$nationality = $_POST['nationality'];
						
						$paitent_insert = $this->billings_model->paitent_insert($patient_arr);
						//var_dump($patient_arr);die;
						if($paitent_insert > 0){}else{
							header("location:" .base_url(). "billings/add?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
							die();
						}
					}
					else{
						$patient_data = self::get_patient_data($paitent_id);
						$nationality = $patient_data['nationality'];
					}

					$billing_for = $_POST['billing_for'];					
					$_SESSION['investigation_session'] = array(); $_SESSION['consultation_session'] = array(); $_SESSION['procedure_session'] = array();
					if($billing_for == 'consultation'){
						$_SESSION['consultation_session'] = array('paitent_id' => $_POST['paitent_id'], 'paitent_type' => $_POST['paitent_type'],'hospital_id' => $_POST['hospital_id'], 'reason_of_visit' => $_POST['reason_of_visit'],'billing_for' => $_POST['billing_for'],'billing_from' => $_POST['billing_from'], 'nationality'=>$nationality);	
						 header("location:" .base_url(). "billings/consultation");
						 die();
					}
					if($billing_for == 'investigation'){
						$_SESSION['investigation_session'] = array('paitent_id' => $_POST['paitent_id'], 'paitent_type' => $_POST['paitent_type'],'hospital_id' => $_POST['hospital_id'], 'reason_of_visit' => $_POST['reason_of_visit'],'billing_for' => $_POST['billing_for'],'billing_from' => $_POST['billing_from'], 'nationality'=>$nationality);
					 	header("location:" .base_url(). "billings/investigation");
						 die();
					}
					if($billing_for == 'procedure'){ 
						//var_dump($_POST);die;
						 $_SESSION['procedure_session'] = array('paitent_id' => $_POST['paitent_id'], 'paitent_type' => $_POST['paitent_type'],'patient_name' => $_POST['wife_name'],'patient_phone' => $_POST['wife_phone'],'hospital_id' => $_POST['hospital_id'], 'reason_of_visit' => $_POST['reason_of_visit'],'billing_for' => $_POST['billing_for'],'billing_from' => $_POST['billing_from'], 'nationality'=>$nationality);
						 header("location:" .base_url(). "billings/procedure");
						 die();
					}					
				}else{
					 header("location:" .base_url(). "billings/add?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					 die();
				}
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billings/add_billing', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	function get_code($type){
		$codes = $this->billings_model->get_code($type);
		return $codes;
	}
	
	function consultation(){
    $data = array();
    $logg = checklogin();
    if($logg['status'] == true){
        if(isset($_POST['action']) && $_POST['action'] == 'add_consultation'){
            unset($_POST['action']);
            
            $transaction_img = '';
            if(!empty($_FILES['transaction_img']['tmp_name'])){
                $dest_path = $this->config->item('upload_path');
                $destination = $dest_path.'patient_files/';
                $NewImageName = rand(4,10000)."-".$_POST['patient_id']."-". $_FILES['transaction_img']['name'];
                $transaction_img = base_url().'assets/patient_files/'.$NewImageName;
                move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
                $_POST['transaction_img'] = $transaction_img;
            }

            $_POST['status'] = 'pending';

            // --- NAYA LOGIC YAHAN SE SHURU ---
            // Agar JS se 'approve_by_status' bhej rahe hain (yani Under Package free case)
            if(isset($_POST['approve_by_status'])) {
                $_POST['approve_by_status'] = 2; // Pending for CEO
            } else {
                $_POST['approve_by_status'] = 1; // Normal Approved
            }
            // --- NAYA LOGIC YAHAN KHATAM ---

            if($_POST['discount_amount'] == ''){                    
                $_POST['discount_amount'] = 0;
            }

            // Model ko update kiya hua $_POST bhej rahe hain
            $consult = $this->billings_model->consultation_insert($_POST);

            if($consult > 0){
                $this->send_billing_receipt($_POST['patient_id'], $_POST['on_date'], $_POST['billing_from'], $_POST['receipt_number'], 'consultation');
                $receipt_number = $_POST['receipt_number'];
                header("location:" .base_url(). "accounts/details/$receipt_number?m=".base64_encode('Billing added successfully').'&t=consultation');
                die();
            } else {
                header("location:" .base_url(). "billings/add?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
                die();
            }
        }
    
        $template = get_header_template($logg['role']);
        $data['doctors'] = $this->doctors_model->get_doctors_list();
        $data['session_data'] = $_SESSION['consultation_session'];
        
        $this->load->view($template['header']);
        $this->load->view('billings/consultation', $data);
        $this->load->view($template['footer']);
    } else {
        header("location:" .base_url(). "");
        die();
    }
}
		
	function investigation(){
			
		$data = array();
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_investigation'){
				unset($_POST['action']);
				//echo '<pre>';
				//var_dump($_POST); echo '<br/><br/>-----------------------------------------------------------------------------------------------<br/><br/>';die;
				$post_arr = array();
				$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);
				$post_arr['reason_of_visit'] = $_POST['reason_of_visit'];unset($_POST['reason_of_visit']);
				$post_arr['billing_from'] = $_POST['billing_from'];unset($_POST['billing_from']);
				$post_arr['billing_at'] = $_POST['billing_at'];unset($_POST['billing_at']);
				$post_arr['paramedic_name'] = $_POST['paramedic_name'];unset($_POST['paramedic_name']);
				$post_arr['on_date'] = $_POST['on_date'];unset($_POST['on_date']);
				$post_arr['receipt_number'] = $_POST['receipt_number'];unset($_POST['receipt_number']);
				$post_arr['billing_id'] = isset($_POST['billing_id'])?$_POST['billing_id']:''; unset($_POST['billing_id']);
				$post_arr['biller_id'] = $_POST['biller_id']; unset($_POST['biller_id']);
				$post_arr['transaction_id'] = ($_POST['transaction_id'])?$_POST['transaction_id']:0; unset($_POST['transaction_id']);
				$post_arr['hospital_id'] = isset($_POST['hospital_id'])?$_POST['hospital_id']:''; unset($_POST['hospital_id']);
				$post_arr['fees'] = $_POST['fees'];unset($_POST['fees']);
				$post_arr['totalpackage'] = $_POST['totalpackage'];unset($_POST['totalpackage']);
				$post_arr['discount_amount'] = $_POST['discount_amount'];unset($_POST['discount_amount']);
				$post_arr['payment_done'] = $_POST['payment_done'];unset($_POST['payment_done']);
				$post_arr['remaining_amount'] = $_POST['remaining_amount'];unset($_POST['remaining_amount']);
				$post_arr['payment_method'] = $_POST['payment_method'];unset($_POST['payment_method']);
				$post_arr['status'] = 'pending';
				
				$number = array();
				foreach($_POST as $key => $val){
						$explode = explode('_', $key);
						$number[] = $explode[2];
				}				
				$number = array_unique($number);
				
				foreach($number as $key){
					$s_pcounte[] = array('investigation_name'=> $_POST['investigation_name_'.$key],'investigation_code'=> $_POST['investigation_code_'.$key],'investigation_price'=> $_POST['investigation_price_'.$key],'investigation_discount'=> $_POST['investigation_discount_'.$key]);	
				}

				$post_arr['investigations'] = serialize($s_pcounte);
				
				$transaction_img = '';
				if(!empty($_FILES['transaction_img']['tmp_name'])){
					$dest_path = $this->config->item('upload_path');
					$destination = $dest_path.'patient_files/';
					$NewImageName = rand(4,10000)."-".$post_arr['patient_id']."-". $_FILES['transaction_img']['name'];
					$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
					move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
					$post_arr['transaction_img'] = $transaction_img;
				}
				//var_dump($post_arr); echo '<br/><br/>-----------------------------------------------------------------------------------------------<br/><br/>';die;
				$investg = $this->billings_model->investigation_insert($post_arr);
				if($investg > 0){
					$this->send_billing_receipt($post_arr['patient_id'], $post_arr['on_date'], $post_arr['billing_from'], $post_arr['receipt_number'], 'investigation');
					//header("location:" .base_url(). "billings/add?m=".base64_encode('Billing request added successfully').'&t='.base64_encode('success'));
					$receipt_number = $post_arr['receipt_number'];
					header("location:" .base_url(). "accounts/details/$receipt_number?m=".base64_encode('Billing added successfully').'&t=investigation');
					die();
				}else{
					header("location:" .base_url(). "billings/add?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
		
			$template = get_header_template($logg['role']);
			$data['investigations'] = $this->investigation_model->get_investigations_list();
			//var_dump($_SESSION['investigation_session']);die;
			$data['session_data'] = $_SESSION['investigation_session'];
			
			$this->load->view($template['header']);
			$this->load->view('billings/investigation', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	function procedure(){
			
		$data = array();
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_procedure'){
				unset($_POST['action']);
				//var_dump($_POST);die;
				/*echo '<br/><br/>-----------------------------------------------------------------------------------------------<br/><br/>';
				var_dump($_FILES);die;*/
				
				
				$post_arr = array();
				$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);
				$post_arr['center_share'] = 0;
				$post_arr['reason_of_visit'] = $_POST['reason_of_visit'];unset($_POST['reason_of_visit']);
				$post_arr['billing_from'] = $_POST['billing_from'];unset($_POST['billing_from']);
				$post_arr['procedure_parent'] = $_POST['procedure_parent'];unset($_POST['procedure_parent']);
				$post_arr['billing_at'] = $_POST['billing_at'];unset($_POST['billing_at']);
				$post_arr['on_date'] = date("Y-m-d H:i:s");
				$post_arr['receipt_number'] = $_POST['receipt_number'];unset($_POST['receipt_number']);
				$post_arr['billing_id'] = isset($_POST['billing_id'])?$_POST['billing_id']:''; unset($_POST['billing_id']);
				$post_arr['biller_id'] = $_POST['biller_id'];unset($_POST['biller_id']);
				$post_arr['transaction_id'] = ($_POST['transaction_id'])?$_POST['transaction_id']:0; unset($_POST['transaction_id']);
				$post_arr['hospital_id'] = isset($_POST['hospital_id'])?$_POST['hospital_id']:''; unset($_POST['hospital_id']);
				$post_arr['fees'] = $_POST['fees'];unset($_POST['fees']);
				$post_arr['totalpackage'] = $_POST['totalpackage'];unset($_POST['totalpackage']);
				$post_arr['discount_amount'] = $_POST['discount_amount'];unset($_POST['discount_amount']);
				$post_arr['payment_done'] = $_POST['payment_done'];unset($_POST['payment_done']);
				$post_arr['remaining_amount'] = $_POST['remaining_amount'];unset($_POST['remaining_amount']);
				$post_arr['payment_method'] = $_POST['payment_method'];unset($_POST['payment_method']);
				$post_arr['status'] = 'pending';
				
				$consent_file = '';
				if(!empty($_FILES['package_form']['tmp_name'])){
					$dest_path = $this->config->item('upload_path');
					$destination = $dest_path.'package_form/';
					$NewImageName = rand(4,10000)."-".$post_arr['receipt_number']."-". $_FILES['package_form']['name'];
					$package_form = base_url().'assets/package_form/'.$NewImageName;
					move_uploaded_file($_FILES['package_form']['tmp_name'], $destination.$NewImageName);
				}
				$post_arr['package_form'] = $package_form;
				
				$transaction_img = '';
				if(!empty($_FILES['transaction_img']['tmp_name'])){
					$dest_path = $this->config->item('upload_path');
					$destination = $dest_path.'patient_files/';
					$NewImageName = rand(4,10000)."-".$post_arr['patient_id']."-". $_FILES['transaction_img']['name'];
					$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
					move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
					$post_arr['transaction_img'] = $transaction_img;
				}
				
				//echo '<pre>';
				//var_dump($_POST); echo '<br/><br/>-----------------------------------------------------------------------------------------------<br/><br/>';die;
				
				$number = array();
				$count = 1;
				foreach($_POST as $key => $val){
					$explode = explode('_', $key);
					if($count == 1){
						$number[] = $explode[2];
					}else{
						$number[] = $explode[3];
					}
					$count++;
					if($count > 4){ $count = 1; }
				}
				$number = array_unique($number);

				foreach($number as $val => $key){
					$s_pcounte[] = array('sub_procedure'=> $_POST['sub_procedure_'.$key],'sub_procedures_code'=> $_POST['sub_procedures_code_'.$key],'sub_procedures_price'=> $_POST['sub_procedures_price_'.$key],'sub_procedures_discount'=> $_POST['sub_procedures_discount_'.$key]);	
				}
				
				$post_arr['data'] = serialize($s_pcounte);
				$p_procd = $this->billings_model->patient_procedure_insert($post_arr);
				if($p_procd > 0){
					$this->send_billing_receipt($post_arr['patient_id'], $post_arr['on_date'], $post_arr['billing_from'], $post_arr['receipt_number'], 'procedure');
					$receipt_number = $post_arr['receipt_number'];
					header("location:" .base_url(). "accounts/details/$receipt_number?m=".base64_encode('Billing added successfully').'&t=procedure');
					//header("location:" .base_url(). "billings/add?m=".base64_encode('Billing request added successfully').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "billings/add?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
		
			$template = get_header_template($logg['role']);
			$data['procedure'] = $this->procedures_model->get_procedures_list();
			$data['consumables'] = $this->stock_model->get_consumbles_list();
			$data['injections'] = $this->stock_model->get_injection_list();
			$data['medicine'] = $this->stock_model->get_medicine_list();
			$data['session_data'] = $_SESSION['procedure_session'];
			
			$this->load->view($template['header']);
			$this->load->view('billings/procedure', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	function send_billing_receipt($patient_id, $date, $billing_from, $receipt, $type){
		$patient_data = $this->get_patient_data($patient_id);
		$currency = '';
		if($patient_data['nationality'] == 'indian'){ $currency = 'Rs.'; }else { $currency = 'USD'; }
		//var_dump($patient_data);die;
		$mail_html = '';
		$mail_html = '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/fontawesome.min.css" rel="stylesheet"></link><table style="width: 100%;" border="0">
						<tbody>
							<tr style="height: 23px;">
								<td style="width: 50%;"><strong><u>Patient details</u></strong></td>
								<td style="width: 50%;"><strong><u>Billing details</u></strong></td>
							</tr>
							<tr style="height: 23px;">
								<td style="width: 50%;"><strong>Name :</strong> '.$patient_data['wife_name'].'</td>
								<td style="width: 50%;"><strong>Billing date :</strong> '.date('d-m-Y', strtotime($date)).'</td>
							</tr>
							<tr style="height: 23px;">
								<td style="width: 50%;"><strong>Email :</strong> '.$patient_data['wife_email'].'</td>
								<td style="width: 50%;"><strong>Billing option :</strong> '.ucwords($type).'</td>
							</tr>
							<tr style="height: 23px;">
								<td style="width: 50%;"><strong>Phone :</strong> '.$patient_data['patient_phone'].'</td>';
								if($billing_from == 'IndiaIVF'){
		$mail_html .= '<td style="width: 50%;"><strong>Billing source :</strong> IndiaIVF</td>';
								}else{
						$center_name = $this->get_center_name($billing_from);
		$mail_html .= '<td style="width: 50%;"><strong>Billing source :</strong> '.$center_name.'</td>';
								}
		$mail_html .= '</tr>
						</tbody></table>';

		if($type == 'consultation'){
			$mail_html .= $this->consultation_billing_receipt($receipt, $type, $currency);
		}else if($type == 'investigation'){
			$mail_html .= $this->investigation_billing_receipt($receipt, $type, $currency);
		}else if($type == 'procedure'){
			$mail_html .= $this->procedure_billing_receipt($receipt, $type, $currency);
		}else{
			return 'not sent';
		}
		$mail_html .= '<table></tbody>
					   <tr style="height: 23px;">
							<td style="width: 50%;"><strong>Documents received: </strong> <br/> <a href="'.$patient_data['wife_pan_card'].'" download>Patient pancard</a> <br/> <a href="'.$patient_data['wife_adhar_card'].'" download>Patient aadhaar card</a> <br/> <a href="'.$patient_data['wife_photo'].'" download>Patient photo</a> <br/> <a href="'.$patient_data['husband_pan_card'].'" download>Husband pancard</a> <br/> <a href="'.$patient_data['husband_adhar_card'].'" download>Husband aadhaar card</a> <br/> <a href="'.$patient_data['husband_photo'].'" download>Husband photo</a> <br/></td>
							<td style="width: 50%;"></td>
						</tr>
						</tbody></table>';
		// $sent = send_mail('himanshu@ichelon.in|'.$patient_data['wife_email'].'', 'IndiaIVF Billing Receipt', $mail_html);
		return ;
		// return $sent;
	}
	
	function consultation_billing_receipt($receipt, $type, $currency){
		$data = $this->billings_model->get_billings_details($receipt, $type);
		$doctor_data = $this->doctors_model->get_doctor_data($data['doctor_id']);

		$html = '';
		$html = '<table style="width: 100%;">
					<tbody>
						<tr>
							<td style="width: 50%;"><strong>Doctor name :</strong> Dr. '.$doctor_data['name'].'</td>
						</tr>
						<tr>
							<td style="width: 50%;"><strong>Package total :</strong> '.$currency.''.$data['totalpackage'].'</td>
						</tr>';
						if($data['discount_amount'] > 0){
		$html .= '<tr><td style="width: 50%;"><strong>Discount :</strong> '.$currency.''.$data['discount_amount'].'</td>
  				  </tr>';
						}
		$html .= '<tr><td style="width: 50%;"><strong>Total :</strong> '.$currency.''.$data['fees'].'</td></tr>';
						
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
		return $html;		
	}
	
	function investigation_billing_receipt($receipt, $type, $currency){
		$data = $this->billings_model->get_billings_details($receipt, $type);
		$investigation_data = unserialize($data['investigations']);
		
		$html = '';
		$html = '<table style="width: 100%;">
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

		//var_dump($investigation_data);die;
		$total_fees = 0;
		$html .= '<h3>Investigation details</h3><table  border="1" style="width: 100%; height: auto;margin-top:20px;">
					<tbody>
					<tr>
					<td>Investigation name</td>
					<td>Investigation code</td>
					<td>Investigation price</td>
					<td>Investigation discount</td>
					</tr>';
		foreach($investigation_data as $key => $val){
			$name_investigation = $this->get_investigation_name($val['investigation_name']);
			$html .= '<tr>
						<td>'.$name_investigation.'</td>
						<td>'.$val['investigation_code'].'</td>
						<td>'.$currency.''.$val['investigation_price'].'</td>
						<td>'.$currency.''.$val['investigation_discount'].'</td>
					  </tr>';
		}
		$html .= '</tbody></table>';
		return $html;		
	}
	
	function procedure_billing_receipt($receipt, $type, $currency){
		$data = $this->billings_model->get_billings_details($receipt, $type);
		$procedure_data = unserialize($data['data']);
		
		$html = '';
		$html = '<table style="width: 100%;">
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
					</tr>';
		foreach($procedure_data as $key => $val){
			$name_procedure = $this->get_procedure_name($val['sub_procedure']);
			$html .= '<tr>
						<td>'.$name_procedure.'</td>
						<td>'.$val['sub_procedures_code'].'</td>
						<td>'.$currency.''.$val['sub_procedures_price'].'</td>
						<td>'.$currency.''.$val['sub_procedures_discount'].'</td>
					  </tr>';
		}
		$html .= '</tbody></table>';
		return $html;		
	}
	
	public function payments(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billings/patient_payments', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	function doctor_fees(){
		$doctor_id = $biller_id = '';
		$doctor_id = $_POST['doctor_id'];
		$patient_id = $_POST['patient_id'];
 		$patient_data = self::get_patient_data($patient_id);
		$nationality =  isset($_SESSION['investigation_session']['nationality'])?$_SESSION['investigation_session']['nationality']:$patient_data['nationality'];
		$biller_id = $_POST['biller_id'];
		
		$fees = $this->doctors_model->doctor_fees($doctor_id, $nationality);
		$allowed_discount = $this->employee_model->allowed_discount($biller_id, $nationality);
		$response = array();
		$response = array('fees' => $fees, 'allowed_discount' => $allowed_discount);
		echo json_encode($response);
		die;
	}
	
	function investigation_price(){
		$investigation_id = '';
		$investigation_id = $_POST['investigation_id'];
		$patient_id = $_POST['patient_id'];
		$biller_id = $_POST['biller_id'];
		$patient_data = self::get_patient_data($patient_id);
		$nationality =  isset($_SESSION['investigation_session']['nationality'])?$_SESSION['investigation_session']['nationality']:$patient_data['nationality'];
		$price = $this->investigation_model->investigation_price($investigation_id, $nationality);
		$allowed_discount = $this->employee_model->allowed_discount($biller_id, $nationality);
		$response = array();
		$response = array('price' => $price['price'], 'code' => $price['code'], 'allowed_discount' => $allowed_discount);
		echo json_encode($response);
		die;
	}
		

	function ajax_accounts_billing_filter(){
		if($_POST['type'] == 'billing_from'){
			$center = $_SESSION['logged_accountant']['center'];
			$billing_from = $_POST['billing_from'];
			$data = $this->billings_model->ajax_billing_source_data($billing_from, $center);
		}else if($_POST['type'] == 'billing_at'){
			$center = $_SESSION['logged_accountant']['center'];
			$billing_at = $_POST['billing_at'];
			$data = $this->billings_model->ajax_billing_from_data($billing_at, $center);
		}else if($_POST['type'] == 'date_wise'){
			$center = $_SESSION['logged_accountant']['center'];
			$start = $_POST['start'];
			$end = $_POST['end'];
			$data = $this->billings_model->ajax_billing_date_data($start, $end, $center);
		}else{
			$response = array('consultant_html'=>"", 'investigation_html'=>"", 'procedure_html' => "", 'payment_html' => "", 'result'=> 0);
			echo json_encode($response);
			die;
		}
		//var_dump($data);die;
		
		if(!empty($data['consultation_result']) || !empty($data['investigate_result']) || !empty($data['procedure_result']) || !empty($data['patient_payment_result'])){
			$consultant_html = $investigation_html = $procedure_html = $payment_html = "";
			if(!empty($data['consultation_result'])){
				$count=1; 
				foreach($data['consultation_result'] as $ky => $vl){                
					$patient_name = $this->get_patient_name($vl['patient_id']);
					$employee_details = employee_detail_number($vl['biller_id']);
					$consultant_html .= '<tr class="odd gradeX">
										  <td>'.$count.'</td>
										  <td><a href="'.base_url().'accounts/patient_details/'.$vl['patient_id'].'">'.$vl['patient_id'].'</a></td>
										  <td>'.strtoupper($patient_name).'</td>
										  <td><a href="'.base_url().'accounts/details/'.$vl['receipt_number'].'?t=consultation">'.$vl['receipt_number'].'</a></td>
										  <td>'.$vl['on_date'].'</td>
										  <td>'.$vl['fees'].'</td>
										  <td>'.$vl['discount_amount'].'</td>
										  <td>'.$vl['remaining_amount'].'</td>
										  <td>'.$employee_details['name'].'</td>
										  <td>'.ucwords($vl['status']).'</td><td>';
										  if($this->discount_applied($vl['receipt_number']) > 0){
											$consultant_html .= 'Discount requested';  
										  }else{		  
											$discount_status = $this->check_billing_discount($vl['patient_id'], $vl['receipt_number']);
											if($discount_status == 1){
												$consultant_html .= 'pending for admin discount approval.';
											}else{  
												if($vl['status'] == 'pending'){		  
													$consultant_html .= '<a href="javascript:void(0)" link="'.base_url().'accounts/approve/'.$vl['ID'].'?t=consultation&u=approved" class="xyx btn btn-large" >Approve</a> | <a href="javascript:void(0);" type="consultation" bill="'.$vl['ID'].'" class="disaprove_first btn btn-large" >Disapprove</a>';
											}else {		  
												$consultant_html .= ucwords($vl['status']);
												if($vl['status'] == 'approved'){
													if($vl['remaining_amount'] < 0){
														$consultant_html .= '<a href="'.base_url().'accounts/patient_reconcile/'.$vl['receipt_number'].'?t=consultation" class="btn btn-large" >Reconcile to patient</a>';
													}		  
												}		  
												if($vl['status'] == 'disapproved'){
													$consultant_html .= '<i class="fa fa-exclamation-circle" aria-hidden="true" title="'.$vl['reason_of_disapprove'].'"></i>';
												}
											}
		  								  }
		  								}
										$consultant_html .= '</td></tr>';
					$count++;
              	}
			}
			if(!empty($data['investigate_result'])){
				$count=1; 
				foreach($data['investigate_result'] as $ky => $vl){ 
					$patient_name = $this->get_patient_name($vl['patient_id']);
					$investigation_html .= '<tr class="odd gradeX">
										  <td>'.$count.'</td>
										  <td><a href="'.base_url().'accounts/patient_details/'.$vl['patient_id'].'">'.$vl['patient_id'].'</a></td>
										  <td>'.strtoupper($patient_name).'</td>
										  <td><a href="'.base_url().'accounts/details/'.$vl['receipt_number'].'?t=investigation">'.$vl['receipt_number'].'</a></td>
										  <td>'.$vl['on_date'].'</td>
										  <td>'.$vl['fees'].'</td>
										  <td>'.$vl['discount_amount'].'</td>
										  <td>'.$vl['remaining_amount'].'</td>
										  <td>'.$employee_details['name'].'</td>
										  <td>'.ucwords($vl['status']).'</td><td>';
										  if($this->discount_applied($vl['receipt_number']) > 0){
											$investigation_html .= 'Discount requested';  
										  }else{		  
											$discount_status = $this->check_billing_discount($vl['patient_id'], $vl['receipt_number']);
											if($discount_status == 1){
												$investigation_html .= 'pending for admin discount approval.';
											}else{  
												if($vl['status'] == 'pending'){		  
													$investigation_html .= '<a href="javascript:void(0)" link="'.base_url().'accounts/approve/'.$vl['ID'].'?t=investigation&u=approved" class="xyx btn btn-large" >Approve</a> | <a href="javascript:void(0);" type="investigation" bill="'.$vl['ID'].'" class="disaprove_first btn btn-large" >Disapprove</a>';
											}else {		  
												$investigation_html .= ucwords($vl['status']);
												if($vl['status'] == 'approved'){
													if($vl['remaining_amount'] < 0){
														$investigation_html .= '<a href="'.base_url().'accounts/patient_reconcile/'.$vl['receipt_number'].'?t=investigation" class="btn btn-large" >Reconcile to patient</a>';
													}		  
												}		  
												if($vl['status'] == 'disapproved'){
													$investigation_html .= '<i class="fa fa-exclamation-circle" aria-hidden="true" title="'.$vl['reason_of_disapprove'].'"></i>';
												}
											}
		  								  }
		  								}
										$investigation_html .= '</td></tr>';
					$count++;
              	}
			}
			if(!empty($data['procedure_result'])){
				$count=1; 
				foreach($data['procedure_result'] as $ky => $vl){                
					$patient_name = $this->get_patient_name($vl['patient_id']);
					$procedure_parent = $this->get_procedure_name($vl['procedure_parent']);
					$procedure_html .= '<tr class="odd gradeX">
										  <td>'.$count.'</td>
										  <td><a href="'.base_url().'accounts/patient_details/'.$vl['patient_id'].'">'.$vl['patient_id'].'</a></td>
										  <td>'.strtoupper($patient_name).'</td>
										  <td><a href="'.base_url().'accounts/details/'.$vl['receipt_number'].'?t=procedure">'.$vl['receipt_number'].'</a></td>
										  <td>'.$vl['on_date'].'</td>
										  <td>'.$vl['fees'].'</td>
										  <td>'.$vl['discount_amount'].'</td>
										  <td>'.$vl['remaining_amount'].'</td>
										  <td>'.$employee_details['name'].'</td>
										  <td>'.ucwords($vl['status']).'</td><td>';
										  if($this->discount_applied($vl['receipt_number']) > 0){
											$procedure_html .= 'Discount requested';  
										  }else{		  
											$discount_status = $this->check_billing_discount($vl['patient_id'], $vl['receipt_number']);
											if($discount_status == 1){
												$procedure_html .= 'pending for admin discount approval.';
											}else{  
												if($vl['status'] == 'pending'){		  
													$procedure_html .= '<a href="javascript:void(0)" link="'.base_url().'accounts/approve/'.$vl['ID'].'?t=procedure&u=approved" class="xyx btn btn-large" >Approve</a> | <a href="javascript:void(0);" type="procedure" bill="'.$vl['ID'].'" class="disaprove_first btn btn-large" >Disapprove</a>';
											}else {		  
												$procedure_html .= ucwords($vl['status']);
												if($vl['status'] == 'approved'){
													if($vl['remaining_amount'] < 0){
														$procedure_html .= '<a href="'.base_url().'accounts/patient_reconcile/'.$vl['receipt_number'].'?t=procedure" class="btn btn-large" >Reconcile to patient</a>';
													}		  
												}		  
												if($vl['status'] == 'disapproved'){
													$procedure_html .= '<i class="fa fa-exclamation-circle" aria-hidden="true" title="'.$vl['reason_of_disapprove'].'"></i>';
												}
											}
		  								  }
		  								}
										$procedure_html .= '</td></tr>';
					$count++;
              	}
			}
			if(!empty($data['patient_payment_result'])){
			    $currency = ""; $count=1; 
				foreach($data['patient_payment_result'] as $key => $val){ //var_dump($val);die;
				    $patient_data = 
					$payment_html .= '<tr><td>'.$count.'</td>';
					
					$payment_html .= '<td><a target="_blank" class="btn btn-large" href="'.base_url().'accounts/details/'.$val['billing_id'].'?t='.$val['type'].'">'.$val['billing_id'].'</a></td>';
				    $payment_html .= '<td><a target="_blank" href="'.base_url().'partial-payment-receipt/'.$val['refrence_number'].'">'.$val['refrence_number'].'</a></td>';
				    $payment_html .= '<td><a target="_blank" href="'.base_url().'accounts/patient_details/'.$val['patient_id'].'">'.$val['patient_id'].'</a></td>';
				    $payment_html .= '<td>'.$val['on_date'].'</td>';
				    $payment_html .= '<td>'.$currency.$val['payment_done'].'</td>';
				    $payment_html .= '<td>'.$this->get_center_name($val['billing_at']).'</td>';
				    if($val['billing_from'] == 'IndiaIVF'){ $payment_html .= '<td>'.$val['billing_from'].'</td>'; }
					else{$payment_html .= '<td>'.$this->get_center_name($val['billing_from']).'</td>';}							
					if($val['status'] == 1){ $payment_html .= '<td>Approved</td>'; }
					else if($val['status'] == 2){ $payment_html .= '<td>Disapproved</td>'; }
					else{$payment_html .= '<td>Pending</td>';}
					$payment_html .= '</tr>';
					$count++;
				}
			}
			
			$response = array('consultant_html'=>$consultant_html, 'investigation_html'=>$investigation_html, 'procedure_html' => $procedure_html, 'payment_html' => $payment_html);
			//var_dump($response);die;
			echo json_encode($response);
			die;
		}else{
			$response = array('consultant_html'=>"", 'investigation_html'=>"", 'procedure_html' => "", 'payment_html' => "", 'result'=> 0);
			echo json_encode($response);
			die;
		}
	}
	
	function ajax_billing_filter(){
		if($_POST['type'] == 'billing_from'){
			$billing_from = $_POST['billing_from'];
			$data = $this->billings_model->ajax_billing_from_data($billing_from);
		}else if($_POST['type'] == 'date_wise'){
			$start = $_POST['start'];
			$end = $_POST['end'];
			$data = $this->billings_model->ajax_billing_date_data($start, $end);
		}else{
			$response = array('consultant_html'=>"", 'investigation_html'=>"", 'procedure_html' => "", 'result'=> 0);
			echo json_encode($response);
			die;
		}
		
		if(!empty($data['consultation_result']) || !empty($data['investigate_result']) || !empty($data['procedure_result'])){
			$consultant_html = $investigation_html = $procedure_html = "";
			if(!empty($data['consultation_result'])){
				$count=1; 
				foreach($data['consultation_result'] as $ky => $vl){                
					$patient_name = $this->get_patient_name($vl['patient_id']);
					$consultant_html .= '<tr class="odd gradeX">
										  <td>'.$count.'</td>
										  <td><a href="'.base_url().'accounts/details/'.$vl['receipt_number'].'?t=consultation">'.$vl['receipt_number'].'</a></td>
                  						  <td><a href="'.base_url().'accounts/patient_details/'.$vl['patient_id'].'">'.$vl['patient_id'].'</a></td>
										  <td>'.$patient_name.'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['totalpackage'].'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['fees'].'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['remaining_amount'].'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['payment_done'].'</td>
										  <td>'.$vl['on_date'].'</td>
										  <td>'.ucfirst($vl['status']).'</td><td>';
										  if($vl['status'] == 'approved'){
					$consultant_html .='<a href="javascript:void(0);" type="consultation" bill="'.$vl['ID'].'" class="billing_disaprove_first btn btn-large" >Disapprove Billing</a>';  
										  }
										  
                    $consultant_html .= '</td></tr>';             	
					$count++;
              	}
			}
			if(!empty($data['investigate_result'])){
				$count=1; 
				foreach($data['investigate_result'] as $ky => $vl){                
					$patient_name = $this->get_patient_name($vl['patient_id']);
										
						$investigation_html .= '<tr class="odd gradeX">
										  <td>'.$count.'</td>
										  <td><a href="'.base_url().'accounts/details/'.$vl['receipt_number'].'?t=consultation">'.$vl['receipt_number'].'</a></td>
                  						  <td><a href="'.base_url().'accounts/patient_details/'.$vl['patient_id'].'">'.$vl['patient_id'].'</a></td>
										  <td>'.$patient_name.'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['totalpackage'].'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['fees'].'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['remaining_amount'].'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['payment_done'].'</td>
										  <td>'.$vl['on_date'].'</td>
										  <td>'.ucfirst($vl['status']).'</td><td>';
										  if($vl['status'] == 'approved'){
				    	$investigation_html .='<a href="javascript:void(0);" type="investigation" bill="'.$vl['ID'].'" class="billing_disaprove_first btn btn-large" >Disapprove Billing</a>';  
										  }
										  
                    $investigation_html .= '</td></tr>';
					$count++;
              	}
			}
			if(!empty($data['procedure_result'])){
				$count=1; 
				foreach($data['procedure_result'] as $ky => $vl){                
					$patient_name = $this->get_patient_name($vl['patient_id']);
					$procedure_parent = $this->get_procedure_name($vl['procedure_parent']);
					
					$procedure_html .= '<tr class="odd gradeX">
										  <td>'.$count.'</td>
										  <td><a href="'.base_url().'accounts/details/'.$vl['receipt_number'].'?t=consultation">'.$vl['receipt_number'].'</a></td>
                  						  <td><a href="'.base_url().'accounts/patient_details/'.$vl['patient_id'].'">'.$vl['patient_id'].'</a></td>
										  <td>'.$patient_name.'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['totalpackage'].'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['fees'].'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['remaining_amount'].'</td>
										  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$vl['payment_done'].'</td>
										  <td>'.$vl['on_date'].'</td>';
					$procedure_html .='<td>';
										  if($vl['status'] == 'approved'){
										      if($vl['share'] == '0'){
				    $procedure_html .='<a href="javascript:void(0);" type="procedure" bill="'.$vl['receipt_number'].'" class="disaprove_first btn btn-large">IIC share</a>';  
										    }else{
					$procedure_html .= '<i class="fa fa-inr" aria-hidden="true"></i> '.$vl['center_share'];    
										    }
										 }
                    $procedure_html .= '</td>';				  
										  
					$procedure_html .='<td>'.ucfirst($vl['status']).'</td>';
				    $procedure_html .='<td>';
										  if($vl['status'] == 'approved'){
				    $procedure_html .='<a href="javascript:void(0);" type="procedure" bill="'.$vl['ID'].'" class="billing_disaprove_first btn btn-large" >Disapprove Billing</a>';  
										  }
                    $procedure_html .= '</td>';
                    $procedure_html .= '</tr>';
					
					$count++;
              	}
			}
			
			$response = array('consultant_html'=>$consultant_html, 'investigation_html'=>$investigation_html, 'procedure_html' => $procedure_html);
			echo json_encode($response);
			die;
		}else{
			$response = array('consultant_html'=>"", 'investigation_html'=>"", 'procedure_html' => "", 'result'=> 0);
			echo json_encode($response);
			die;
		}
	}
	
	public function search_patient_payment(){
		$data = $this->billings_model->patient_payments($_POST);
		
		$html = '';	
		if(isset($data['remaining_billing']) && !empty($data['remaining_billing'])){
			$patient_data = get_patient_detail($data['patient_id']);
			$currency = '';
			foreach($data['remaining_billing'] as $key => $val){
				//$html2 = '';
				$data['current_balance'] = 0;
				$html .= '<td>'.$currency.$val['discounted_package'].'</td>';		  
				$data_arr = array();
				
				$procedure_data = $val['data'];
				
				$html .= '<tr><td><a class="btn btn-large" target="_blank" href="'.base_url().'accounts/details/'.$val['billing_id'].'?t='.$val['type'].'">'.$val['billing_id'].'</a><a class="btn btn-large" target="_blank" href="'.base_url().'accounts/partial_procedure_billing/'.$val['billing_id'].'?t='.$val['type'].'">'.'Add Payments'.'</a></td><td>'.get_center_name($val['billing_at']).'</td>';
				if($val['billing_from'] == 'IndiaIVF'){ 
					$html .= '<td>'.$val['billing_from'].'</td>'; 
				}else { 
					$html .= '<td>'.get_center_name($val['billing_from']).'</td>'; 
				}
				$html .= '<td>'.$currency.$val['discounted_package'].'</td><td>'.$currency.$val['amount_paid'].'</td><td>'.$currency.$val['balance'].'</td><td>'.ucfirst($val['type']).'</td><td><input type="text" balance="'.$val['balance'].'" placeholder="Enter amount" class="'.ucfirst($val['type']).'form-control payment_input" name="'.$val['type'].'_'.$val['billing_id'].'" class="form-control" /> </td></tr>';
			    //$html .='<tr><td colspan="8">'.$html2.'</td></tr>';
			}
			
			$data['current_balance'] = 1;
		} 
		unset($data['remaining_billing']);
		$data['remaining_billing'] = $html;
		//echo '<pre>';
		//print_r($data);
		//echo '</pre>';
		echo json_encode($data);
		die;
	}
	
	public function add_patient_payment(){
		if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_patient_payment'){
			unset($_POST['action']);
			$_POST = array_filter($_POST);
			
			$post_arr = array();
			$post_arr['on_date'] = date("Y-m-d H:i:s");
			$post_arr['billing_at'] = $_SESSION['logged_billing_manager']['center'];
			$post_arr['patient_id'] = $_POST['patient_id']; unset($_POST['patient_id']);
			$post_arr['transaction_id'] = isset($_POST['transaction_id'])?$_POST['transaction_id']:""; unset($_POST['transaction_id']);
			$post_arr['billing_from'] = $_POST['billing_from']; unset($_POST['billing_from']);
			$post_arr['payment_method'] = $_POST['payment_method']; unset($_POST['payment_method']);
			
			$transaction_img = '';
			if(!empty($_FILES['transaction_img']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$unique_id = uniqid();
				$NewImageName = $unique_id."-".$post_arr['patient_id']."-". $_FILES['transaction_img']['name'];
				$transaction_img = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
				$post_arr['transaction_img'] = $transaction_img;
			}
			
			$patient_data = get_patient_detail($post_arr['patient_id']);
			$patial_total = 0;
			foreach($_POST as $key => $val){
				$explode = explode('_', $key);
				$type = $explode[0];
				$billing_id = $explode[1];
				$post_arr['refrence_number'] = getGUID();
				$post_arr['type'] = $type;
				$post_arr['billing_id'] = $billing_id;
				$post_arr['payment_done'] = $val;
				$patial_total += $val;
				
				$payment = $this->billings_model->insert_patient_payment($post_arr);
				sleep(1);
			}
			
			$sms_html = '';
	        $sms_html = "Thank you for your billing payment. Payment Details - Amt : Rs. ".$patial_total." Date : ".dateformat(date('Y-m-d H:i'))." - Warm Regards, India IVF";
	        send_sms($patient_data['wife_phone'], $sms_html);
			
			if($payment > 0){
				header("location:" .base_url(). "billings/payments?m=".base64_encode('Payment done successfully!').'&t='.base64_encode('success'));
				die();	
			}else{
				header("location:" .base_url(). "billings/payments?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
				die();
			}
		}else{
			header("location:" .base_url(). "billings/payments?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function search_patient(){ 
		$response = array();
		$phone_number = '';
		$search_this = $_POST['search_this'];
		$search_by = $_POST['search_by'];
		
			
		if($search_this != ''){
			$patient = $this->billings_model->check_patient($search_this, $search_by);
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
		}else{
			$response = array('status' => 0, 'message'=> 'Phone number is required');
			echo json_encode($response);
			die;
		}
		//var_dump($_POST);die;		
	}
	
	function get_patient_name($patient_id){
		$name = $this->billings_model->get_patient_name($patient_id);
		return $name;
	}
	
	function get_patient_details($patient_id){
		$name = $this->billings_model->get_patient_details($patient_id);
		return $name;
	}
	
	function get_patient_data($patient_id){
		$patient_data = $this->billings_model->get_patient_data($patient_id);
		return $patient_data;
	}
	
	
	function get_procedure_name($procedure){
		$name = $this->billings_model->get_procedure_name($procedure);
		return $name;
	}
	
	function get_sub_procedures(){
		$patient_id = $_POST['patient_id'];
		$biller_id = $_POST['biller_id'];
		$parent_procedure = $_POST['parent_parents'];
		$patient_data = self::get_patient_data($patient_id);
		$nationality =  isset($_SESSION['investigation_session']['nationality'])?$_SESSION['investigation_session']['nationality']:$patient_data['nationality'];
		$sub = $this->billings_model->get_sub_procedures($parent_procedure);
		$allowed_discount = $this->employee_model->allowed_discount($biller_id, $nationality);
		$html = '';
		$html = '<option value="">Select</option>';
		if(count($sub) > 0){ 
			foreach($sub as $key => $val){ $fees = ''; if($nationality == 'indian'){$fees = $val['price'];}else{$fees = $val['usd_price'];}
				$html .= '<option value="'.$val['ID'].'" code="'.$val['code'].'" fees="'.$fees.'">'.$val['procedure_name'].'</option>';
			}
		}
		$response = array();
		$response = array('html' => $html, 'allowed_discount'=>$allowed_discount);
		echo json_encode($response);
		die;
	}
	
	function get_sub_procedures_html($parent_procedure, $selected_sub){
		$sub = $this->billings_model->get_sub_procedures($parent_procedure);
		$html = '';
		$html = '<option value="">Select</option>';
		if(count($sub) > 0){
			foreach($sub as $key => $val){ //var_dump($sub);echo '-------------'; var_dump($selected_sub);die;
				$select = "";
				if($selected_sub == $val['ID']){$select = 'selected="selected"';}
				$html .= '<option value="'.$val['ID'].'" '.$select.' code="'.$val['code'].'" fees="'.$val['price'].'">'.$val['procedure_name'].'</option>';
			}
		}
		return $html;
	}
	
	public function billing_discount(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->get_billing_discount();
			$this->load->view($template['header']);
			$this->load->view('billings/billing-discount', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url()."");
			die();
		}
	}
	
	public function discount_lists(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$biller = $_SESSION['logged_billing_manager']['username'];
			$template = get_header_template($logg['role']);
			$data['data'] = $this->billings_model->discount_lists($biller);
			$this->load->view($template['header']);
			$this->load->view('billings/discount-lists', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url()."");
			die();
		}
	}
	
	public function patients(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$data = array();
			$data['patients'] = $this->patients_model->get_patients();
			$this->load->view($template['header']);
			$this->load->view('patients/patients', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url()."");
			die();
		}
	}
	
	public function check_patient(){
	    return "";
		$get_patients = $this->patients_model->get_patients();
		$complete_arr = $patient_consultation = array();
		
		foreach($get_patients as $patients){
		    $complete = patient_profile($patients['patient_id']);
		    if($complete == false){
		        $patient_details = get_patient_detail($patients['patient_id']);
		        $complete_arr[] = $patient_details;
		    }    
		}
		//var_dump($complete_arr);die;
		
		if(!empty($complete_arr)){
		    foreach($complete_arr as $complete){
		        $this->db->select('*');
		        $this->db->where('patient_id', $complete['patient_id']);
        		$this->db->from('hms_doctor_consultation');
        		$query = $this->db->get();
        		$consultion = $query->result();
        		
        		$patient_consultation[] = array('patient_details' => $complete, 'consultation_data' => $consultion);
		    }
		}
		
		if(!empty($patient_consultation)){
		  //  echo '<pre>';
		  //  var_dump($patient_consultation);
		  //  echo '</pre>';
		  //  die;
		    
		     echo '<table style="width:100%; border:1px solid #000;" id="" border="1">
                    <thead>
                        <tbody id=""><tr><th>Wife Name</th><th>Wife Age</th><th>Email</th><th>Phone</th><th>Husband Name</th><th>Husband Age</th><th>History</th><th>Male Medicine</th><th>Female Medicine</th><th>Male Inverstigation</th><th>Female Investigation</th><th>Procedure</th></tr>'; 
                        
		    foreach($patient_consultation as $patient){
                $patient_data = $patient['patient_details'];
                $patient_id = $patient_data['patient_id'];
                $initial_history_link = $this->generate_history_pdf($patient_id);
                
                foreach($patient['consultation_data'] as $patient_result){
                    
                $patient_result = (array) $patient_result;    
                // var_dump($patient_result);$die;
	            $procedure_suggestion = $patient_result['procedure_suggestion'];
                $sub_procedure_suggestion_list = unserialize($patient_result['sub_procedure_suggestion_list']);

                $procedure_html = "";
                if($procedure_suggestion == 1){
                    $procedure_html = "<ul>";
                    if(!empty($sub_procedure_suggestion_list)){
                        foreach($sub_procedure_suggestion_list as $key => $vals){
                            $sub_procedure_data = get_procedure_data($vals);
                            $procedure_html .= "<li>".$sub_procedure_data."</li>";
                        }
                    }
                    $procedure_html .= "</ul>";
                }
            
                $medicine_suggestion = $patient_result['medicine_suggestion'];
            	//var_dump($parent_proce$procedure_suggestiondure_data);die;
                $male_medicine_html = $female_medicine_html = "";
                if($medicine_suggestion == 1){        
            	    $male_medicine_suggestion_list = unserialize($patient_result['male_medicine_suggestion_list']);
                    $female_medicine_suggestion_list = unserialize($patient_result['female_medicine_suggestion_list']);
                    if(!empty($male_medicine_suggestion_list)){
                        $male_medicine_html = '<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
                                                    <thead>
                                                        <tbody id="male_medicine_suggestion_table">';                                           
                                                        $male_med_count = 1;
                                                        foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $vals){
                                                            //var_dump($vals);die;
                                                            $male_take = isset($vals['male_medicine_take'])?$vals['male_medicine_take']:"";
                                                            
                                                            $male_medicine_html .='<tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Medicine '.$male_med_count.':-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['male_medicine_name']).'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Dosage:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_dosage'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Start on:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_when_start'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Days:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_days'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Route:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_route'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Frequency:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_frequency'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Timing:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['male_medicine_timing'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Take:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$male_take.'</td>
                                                                                    </tr>';
                                                        $male_med_count++;
                                                        }                                   
            
                        $male_medicine_html .= '</tbody> </thead> </table>';
                    }
            
                    if(!empty($female_medicine_suggestion_list)){
                        $female_medicine_html .= '<table style="width:100%; border:1px solid #000;" id="male_medicine_table" border="1">
                                                    <thead>
                                                        <tbody id="male_medicine_suggestion_table">';                                           
                                                        $fmale_med_count = 1;
                                                        foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $vals){
                                                            $female_take = isset($vals['female_medicine_take'])?$vals['female_medicine_take']:"";
                                                            $female_medicine_html .='<tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Medicine '.$fmale_med_count.':-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.get_medicine_name($vals['female_medicine_name']).'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Dosage:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_dosage'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Start on:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_when_start'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Days:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_days'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Route:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_route'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Frequency:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_frequency'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Timing:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$vals['female_medicine_timing'].'</td>
                                                                                    </tr>
                                                                                    <tr style="border:1px solid #000; width:40%;">    
                                                                                       <td style="border:1px solid #000; width:80%;">Take:-</td>
                                                                                       <td style="border:1px solid #000; width:20%;">'.$female_take.'</td>
                                                                                    </tr>';
                                                        $fmale_med_count++;    
                                                        }                                                            
                        $female_medicine_html .= '</tbody> </thead> </table>';
                    }
            
                }
            
                $investation_suggestion = $patient_result['investation_suggestion'];
            	//var_dump($parent_proce$procedure_suggestiondure_data);die;
                $male_investation_html = $female_investation_html = "";
                if($investation_suggestion == 1){        
                    $male_investigation_suggestion_list = unserialize($patient_result['male_investigation_suggestion_list']);
                    $female_investigation_suggestion_list = unserialize($patient_result['female_investigation_suggestion_list']);
            
                    $male_investation_html = "<ul>";
                    if(!empty($male_investigation_suggestion_list)){
                        foreach($male_investigation_suggestion_list as $key => $vals){//var_dump($vals);die;
            
                            $investigation_name = get_investigation_name($vals);
            
                            $male_investation_html .= "<li>".$investigation_name."</li>";
            
                        }
            
                    }
            
                    $male_investation_html .= "</ul>";
                    $female_investation_html = "<ul>";
                    if(!empty($female_investigation_suggestion_list)){
                        foreach($female_investigation_suggestion_list as $key => $vals){
                            $investigation_name = get_investigation_name($vals);
                            $female_investation_html .= "<li>".$investigation_name."</li>";
                        }
                    }
                    $female_investation_html .= "</ul>";
                }
                
                echo '<tr><td>'.$patient_data['wife_name'].'</td><td>'.$patient_data['wife_age'].'</td><td>'.$patient_data['wife_email'].'</td><td>'.$patient_data['wife_phone'].'</td><td>'.$patient_data['husband_name'].'</td><td>'.$patient_data['husband_age'].'</td><td>'.$initial_history_link.'</td><td>'.$male_medicine_html.'</td><td>'.$female_medicine_html.'</td><td>'.$male_investation_html.'</td><td>'.$female_investation_html.'</td><td>'.$procedure_html.'</td></tr>';
		        
                }
		    }
            echo'</tbody> </thead> </table>';                                          
		    
		}
	}
	
	
	
	
	public function generate_history_pdf($patient_id){
	    $dic = dirname(__DIR__);
	    require_once($dic.'/vendor/autoload.php');
	    ob_start();
	    $history_html = print_pdf_patient_medical_info($patient_id);
	    if($history_html != "reportnotfound"){
	        $dest_path = $this->config->item('upload_path').'initial-history/';
	        $filename = $patient_id."_initial_history.pdf";
    	    $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($history_html);
            ob_end_clean();//End Output Buffering
            $mpdf->Output($dest_path.$filename, 'F');
            
            return "https://indiaivf.website/assets/initial-history/".$filename;
	    }
	    return "";
	}
	
	public function discount_approved(){
		$this->load->view('templates/header');
		$this->load->view('billings/discount-approved');
		$this->load->view('templates/footer');
	}
	
	function get_center(){
		if(isset($_SESSION['logged_billing_manager'])){ 
			$username = $_SESSION['logged_billing_manager']['username'];
			$center = $this->billings_model->get_center($username);
			return $center;
		}
	}
	
	function get_all_centers(){
		$all_centers = $this->center_model->get_centers();
		return $all_centers;
	}
	
	function get_center_name($center){
		$name = $this->accounts_model->get_center_name($center);
		return $name;
	}
	
	function get_investigation_name($investig){
		$name = $this->billings_model->get_investigation_name($investig);
		return $name;
	}

	function get_medicine_name($medicine){
		$name = $this->billings_model->get_medicine_name($medicine);
		return $name;
	}
	
	function check_discount_code(){
		
		$discount_code = $_POST['discount_code'];
		$patient_id = $_POST['patient_id'];
		$receipt_number = $_POST['receipt_number'];
		$type = $_POST['type'];
		$discount = $this->billings_model->check_discount_code($discount_code,$patient_id,$receipt_number,$type);
		$response = array();
		if(!empty($discount)){
			$response = array('message' => 'Discount applied!', 'amount' => $discount, 'status' => 1);
		}else{
			$response = array('message' => 'Invalid code!', 'amount' => 0, 'status' => 0);
		}
		echo json_encode($response);
		die;
	}
	function discount_applied($receipt){
		$check_discound_applied = $this->accounts_model->check_discound_applied($receipt);
		return $check_discound_applied;
	}
	function check_billing_discount($patient, $receipt_number){
		$discount = $this->accounts_model->check_billing_discount($patient, $receipt_number);
		return $discount;
	}	

	function approve_purchase_order($ID){
		$approved = $this->billings_model->approve_purchase_order($ID);
		if($approved > 0){
			header("location:" .base_url(). "billings/procedure_billings?m=".base64_encode('Package Change request!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "billings/procedure_billings?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
// --- 1. MAIN INVOICE FUNCTION (Saves data and triggers Mail) ---
    public function forma_invoice($ID) {
        $logg = checklogin();
        if ($logg['status'] == true) {
            if (isset($_POST['action']) && $_POST['action'] == 'addprocedure') {
                
                // --- APPROVAL LOGIC CONFIG ---
                $force_approval_codes = ['IP206', 'IP21','IP55','IP175','IP176','IP56','IP177','IP178','IP38']; // In codes par hamesha mail jayegi
                $auto_trigger_mail = false;
                $requires_mail_manual = isset($_POST['send_approval_mail']) ? true : false;

                // Safai: Remove unnecessary keys
                $exclude_keys = ['action', 'send_approval_mail', 'requires_approval', 'total_min_price_val'];
                foreach ($exclude_keys as $ex_key) { unset($_POST[$ex_key]); }

                // Map Main Fields
                $fields = [
                    'total_after_discount', 'booking_amount', 'booking_amount_40', 
                    'booking_amount_50', 'counsellor_signature', 'coordinator_signature', 
                    'add_on', 'center_number', 'package_name', 'package_date', 
                    'booking_date', 'appointment_id', 'patient_id', 'employee_number'
                ];

                $post_arr = [];
                foreach ($fields as $field) {
                    $post_arr[$field] = $_POST[$field] ?? '';
                    unset($_POST[$field]); 
                }

                // Extract Dynamic Procedures & Check for Approval Triggers
                $c_counte = array();
                $c_counter = array();
                foreach ($_POST as $key => $val) {
                    if (strpos($key, 'procedure_ID_') !== false) {
                        $c_counter[] = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
                    }
                }

                if (!empty($c_counter)) {
                    foreach ($c_counter as $pid) {
                        if (isset($_POST['procedure_ID_' . $pid]) && $_POST['procedure_ID_' . $pid] != '') {
                            
                            $code = $_POST['code_' . $pid] ?? '';
                            $price_after = (float)($_POST['after_discount_' . $pid] ?? 0);
                            $min_p = (float)($_POST['min_price_display_' . $pid] ?? 0);

                            // Trigger logic: Specific codes OR Price < Min
                            if (in_array($code, $force_approval_codes) || $price_after < $min_p) {
                                $auto_trigger_mail = true;
                            }

                            $c_counte[] = array(
                                'procedure_ID'   => $_POST['procedure_ID_' . $pid],
                                'procedure_name' => $_POST['procedure_name_' . $pid] ?? '',
                                'code'           => $code,
                                'price'          => $_POST['price_' . $pid] ?? 0,
                                'min_price'      => $min_p,
                                'discount'       => $_POST['discount_' . $pid] ?? 0,
                                'after_discount' => $price_after
                            );
                        }
                    }
                }

                // Final Decision: Should we send mail?
                $should_send_mail = ($requires_mail_manual || $auto_trigger_mail);
                $post_arr['status'] = $should_send_mail ? 2 : ($_POST['status'] ?? 1);

                $details['consumables'] = $c_counte;
                $post_arr['procedure'] = serialize($details);

                // 4. Send Approval Email if triggered
                if ($should_send_mail) {
                    $this->send_approval_email_to_multiple($post_arr, $c_counte, $ID);
                }

                // 5. Update Database
                $result = $this->billings_model->update_product($post_arr, $ID);

                if ($result > 0) {
                    header("Location: " . base_url() . "billings/forma_invoice/" . $ID . "?success");
                    die();
                }
            }

            // View Loading
            $data['data'] = $this->billings_model->get_transfer_data($ID);
            $template = get_header_template($logg['role']);
            $this->load->view($template['header']);
            $this->load->view('billings/forma_invoice', $data);
            $this->load->view($template['footer']);
        } else {
            header("location:" . base_url());
            die();
        }
    }

// Helper Function for cURL
private function trigger_external_api($data) {
    $jsonData = json_encode($data, JSON_UNESCAPED_SLASHES);
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://flertility.in/lead/consultations/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ],
    ]);
    curl_exec($curl);
    curl_close($curl);
}

private function send_approval_email_to_multiple($post_data, $procedures, $ID) {
    $this->load->library('email');
    
    // 1. Emails Setup
    $counselor_email = $_SESSION['logged_counselor']['email'] ?? 'webdesign@indiaivf.in'; 
    $ceo_email       = 'ceo@indiaivf.in';
    $director_email  = 'director@indiaivf.in';

    $info_cc_list = array(
        'accounts@indiaivf.in', 
        'pan.singh@indiaivf.in', 
        'deepa.mishra@indiaivf.in',
		'shanky.malhotra@indiaivf.in', 
        $counselor_email
    );

    // 2. Fetch Data
    $pt_sql = "SELECT wife_name, husband_name FROM hms_patients WHERE patient_id = '{$post_data['patient_id']}'";
    $pt_res = $this->db->query($pt_sql)->row_array();
    $wife_name = $pt_res['wife_name'] ?? 'N/A';

    $cen_sql = "SELECT center_name FROM hms_centers WHERE center_number = '{$post_data['center_number']}'";
    $cen_res = $this->db->query($cen_sql)->row_array();
    $center_name = $cen_res['center_name'] ?? 'N/A';

    // 3. Threading & URL Safe ID
    $unique_msg_id = "<" . time() . "." . uniqid() . "@indiaivf.in>";
    $this->db->where('ID', $ID)->update('hms_doctor_consultation', ['last_email_msg_id' => $unique_msg_id]);
    $encoded_id = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($ID));
    
    $subject = 'APPROVAL REQUEST: Discounted Package - ' . $wife_name . ' (ID: ' . $post_data['patient_id'] . ') - ' . $center_name;
    $config = array('mailtype' => 'html', 'charset' => 'utf-8', 'priority' => '1');

    // --- PROCEDURE TABLE GENERATION ---
    // --- PROCEDURE TABLE GENERATION ---
$procedure_table = "<h4>Procedures:</h4><table border='1' style='border-collapse:collapse; width:100%;' cellpadding='5'>";
$procedure_table .= "<tr style='background:#eee;'><th>Code</th><th>Name</th><th>Max</th><th>Min</th><th>Offered Price</th><th>Disc %</th></tr>";

$total_actual = 0; $total_min = 0;
foreach($procedures as $p) {
    $row_price = (float)($p['price'] ?? 0);
    $row_after = (float)($p['after_discount'] ?? 0);
    $total_actual += $row_price;
    $total_min += (float)($p['min_price'] ?? 0);
    $row_disc = ($row_price > 0) ? round((($row_price - $row_after) / $row_price) * 100, 2) : 0;
    
    // Formatting numbers with commas
    $fmt_actual = number_format($row_price);
    $fmt_min    = number_format((float)$p['min_price']);
    $fmt_after  = number_format($row_after);

    $procedure_table .= "<tr style='text-align:center;'>
        <td>{$p['code']}</td>
        <td>{$p['procedure_name']}</td>
        <td>{$fmt_actual}</td>
        <td>{$fmt_min}</td>
        <td>{$fmt_after}</td>
        <td>{$row_disc}%</td>
    </tr>";
}

$total_after = (float)$post_data['total_after_discount'];
$grand_disc = ($total_actual > 0) ? round((($total_actual - $total_after) / $total_actual) * 100, 2) : 0;

// Formatting Totals
$fmt_total_actual = number_format($total_actual);
$fmt_total_min    = number_format($total_min);
$fmt_total_after  = number_format($total_after);

$procedure_table .= "<tr style='font-weight:bold; background:#f9f9f9; text-align:center;'>
    <td colspan='2'>TOTAL:</td>
    <td>{$fmt_total_actual}</td>
    <td>{$fmt_total_min}</td>
    <td style='color:green;'>{$fmt_total_after}</td>
    <td>{$grand_disc}%</td>
</tr></table>";
    // --- SHARED HTML PARTS ---
   // --- SHARED HTML PARTS ---
	$header_html = "<html><body style='font-family: Arial, sans-serif;'>";

	// Patient Details ko Table format mein convert kiya gaya hai
	$patient_details = "<h4>Patient Details:</h4>";
	$patient_details .= "<table border='1' style='border-collapse:collapse; width:100%; margin-bottom:20px;' cellpadding='8'>";
	$patient_details .= "<tr><td style='background:#f9f9f9; width:30%;'><strong>Patient Name:</strong></td><td>{$wife_name}</td></tr>";
	$patient_details .= "<tr><td style='background:#f9f9f9;'><strong>Patient ID:</strong></td><td>{$post_data['patient_id']}</td></tr>";
	$patient_details .= "<tr><td style='background:#f9f9f9;'><strong>Center:</strong></td><td>{$center_name}</td></tr>";
	$patient_details .= "<tr><td style='background:#f9f9f9;'><strong>Requested By:</strong></td><td>" . ($_SESSION['logged_counselor']['name']) . "</td></tr>";
	$patient_details .= "</table>";

	$footer_html = "<p style='font-size: 11px; color: #999; margin-top: 20px;'>HMS Billing System</p></body></html>";

    // --- 1. CEO MAIL (Approve + Reject) ---
    $this->email->initialize($config);
    $this->email->set_header('Message-ID', $unique_msg_id);
    $this->email->from($counselor_email, 'HMS Billing - ' . $_SESSION['logged_counselor']['name']);
    $this->email->to($ceo_email);
    $this->email->subject($subject);
    
    $ceo_action = "<div style='margin-top:25px; text-align:center; padding:15px; border:2px solid #28a745;'>";
    $ceo_action .= "<a href='".base_url()."billings/decision_form/approve/{$encoded_id}/ceo' style='background:green; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>APPROVE</a> &nbsp; ";
    $ceo_action .= "<a href='".base_url()."billings/decision_form/disapprove/{$encoded_id}/ceo' style='background:red; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>REJECT</a></div>";
    
    $this->email->message($header_html . $patient_details . $procedure_table . $ceo_action . $footer_html);
    $this->email->send();

    // --- 2. DIRECTOR MAIL (Approve + Reject) ---
    $this->email->clear();
    $this->email->initialize($config);
    $this->email->set_header('In-Reply-To', $unique_msg_id);
    $this->email->from($counselor_email, 'HMS Billing - ' . $_SESSION['logged_counselor']['name']);
    $this->email->to($director_email);
    $this->email->subject($subject);
    
    $dir_action = "<div style='margin-top:25px; text-align:center; padding:15px; border:2px solid #007bff;'>";
    $dir_action .= "<a href='".base_url()."billings/decision_form/approve/{$encoded_id}/director' style='background:#007bff; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>APPROVE</a> &nbsp; ";
    $dir_action .= "<a href='".base_url()."billings/decision_form/disapprove/{$encoded_id}/director' style='background:red; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>REJECT</a></div>";
    
    $this->email->message($header_html . $patient_details . $procedure_table . $dir_action . $footer_html);
    $this->email->send();

    // --- 3. TEAM INFO MAIL (Procedure List ONLY, No Buttons) ---
    $this->email->clear();
    $this->email->initialize($config);
    $this->email->from('billing-system@indiaivf.in', 'HMS Billing Info');
    $this->email->to('accounts@indiaivf.in'); 
    $this->email->cc($info_cc_list);
    $this->email->subject($subject);

    $info_msg = "<div style='background:#fff3cd; padding:10px; border:1px solid #ffeeba;'><strong>Status:</strong> Pending Approval from CEO/Director.</div>";
    
    // Yahan procedure_table add kiya hai taaki team ko details dikhen
    $this->email->message($header_html . $info_msg . $patient_details . $procedure_table . $footer_html);
    $this->email->send();
}

// --- 2. SUBMIT DECISION & REPLY TO THREAD ---
public function submit_decision() {
        $ID = $this->input->post('ID');
        $action = $this->input->post('action');
        $reason = $this->input->post('reason');
        $role = $this->input->post('role');

        // Fetch data for threading
        $this->db->select('hms_doctor_consultation.*, hms_patients.wife_name');
        $this->db->from('hms_doctor_consultation');
        $this->db->join('hms_patients', 'hms_doctor_consultation.patient_id = hms_patients.patient_id');
        $this->db->where('hms_doctor_consultation.ID', $ID);
        $original_data = $this->db->get()->row_array();

        if (!$original_data) { die("Invalid ID"); }

        // Fetch Center Name
        $cen_res = $this->db->query("SELECT center_name FROM hms_centers WHERE center_number = '{$original_data['center_number']}'")->row_array();
        $center_name = $cen_res['center_name'] ?? 'N/A';

        // Set Approver Details
        $approver_email = ($role == 'ceo') ? 'ceo@indiaivf.in' : 'director@indiaivf.in';
        $approver_title = strtoupper($role) . " (" . $approver_email . ")";
        
        // STATUS LOGIC: Approve = 1, Reject = 3
        $status = ($action == 'approve' ? '1' : '3');
        
        // Update Database
        $this->db->where('ID', $ID)->update('hms_doctor_consultation', [
            'status' => $status,
            'approved_by' => $approver_title,
            'approval_remarks' => "[".ucfirst($action)." by $role]: $reason"
        ]);

        // 3. Trigger Notification Reply
        $this->send_final_notification($original_data, $action, $approver_title, $reason, $center_name);

        echo "<h2 style='text-align:center; font-family: Arial; margin-top: 50px;'>Decision Processed!<br><small>Window will close shortly.</small></h2>";
        echo "<script>setTimeout(function(){ window.close(); }, 2000);</script>";
    }

// Billings.php mein is function ko update karein
public function decision_form($action, $encoded_id, $role = '') {
        // URL Safe Decoding
        $clean_id = str_replace(['-', '_'], ['+', '/'], $encoded_id);
        $data['ID'] = base64_decode($clean_id);
        $data['action'] = $action;
        $data['role'] = $role; 
        
        $check = $this->db->get_where('hms_doctor_consultation', array('ID' => $data['ID']))->row_array();

        if (!$check) {
            die("<h2 style='text-align:center; margin-top:50px;'>Record not found!</h2>");
        }

        // VALIDATION: Status 1 (Approved) ya 3 (Rejected) hai toh link disable karein
        if($check['status'] == 1 || $check['status'] == 3) {
            $msg = ($check['status'] == 1) ? "APPROVED" : "REJECTED";
            $color = ($check['status'] == 1) ? "#28a745" : "#dc3545";
            
            die("<div style='text-align:center; margin-top:50px; font-family:Arial;'>
                    <h2 style='color:{$color};'>Action Already Taken</h2>
                    <p>This package has already been <strong>{$msg}</strong> by {$check['approved_by']}.</p>
                    <p>Remarks: {$check['approval_remarks']}</p>
                    <button onclick='window.close()' style='padding:10px 20px; cursor:pointer;'>Close Window</button>
                 </div>");
        }

        $this->load->view('billings/approval_feedback', $data);
    }

// 4. FINAL NOTIFICATION (AS A REPLY THREAD)
// 2. FINAL NOTIFICATION (MAINTAINING THE TRAIL MAIL)
    private function send_final_notification($original_data, $action, $approver, $reason) {
        $this->load->library('email');

        // A. Fetch Counselor Email (Lookup by Employee Number)
        $emp_num = $original_data['employee_number'];
        $emp_res = $this->db->query("SELECT email FROM hms_employees WHERE employee_number = '$emp_num'")->row_array();
        $counselor_email = (!empty($emp_res['email'])) ? $emp_res['email'] : 'webdesign@indiaivf.in'; 
        
        // B. Fetch Center Name for exact subject match [cite: 21, 27]
        $c_id = $original_data['center_number'];
        $c_res = $this->db->query("SELECT center_name FROM hms_centers WHERE center_number = '$c_id'")->row_array();
        $center_name = $c_res['center_name'] ?? 'N/A';

        // C. Setup Recipients
        $primary_to = $counselor_email; 
        $cc_list = array(
            'shanky.malhotra@indiaivf.in', 
            'deepa.mishra@indiaivf.in', 
            'pan.singh@indiaivf.in',
            'accounts@indiaivf.in',
            'ceo@indiaivf.in',
			'director@indiaivf.in'
        );

        $config = array('mailtype' => 'html', 'charset' => 'utf-8', 'priority' => '1');
        $this->email->initialize($config);

        // D. Threading Headers - Links back to the original message
        if(!empty($original_data['last_email_msg_id'])) {
            $this->email->set_header('In-Reply-To', $original_data['last_email_msg_id']);
            $this->email->set_header('References', $original_data['last_email_msg_id']);
        }

        // E. Subject Sync (Ensures response stays in the same trail mail)
        $subject = "Re: APPROVAL REQUEST: Discounted Package - " . $original_data['wife_name'] . " (ID: " . $original_data['patient_id'] . ") - " . $center_name;

        $status_color = ($action == 'approve') ? '#28a745' : '#dc3545';
        $status_text  = ($action == 'approve') ? 'Approved' : 'Rejected';

        // F. HTML Body Construction
        $html = "<html><body style='font-family: Arial, sans-serif;'>";
        $html .= "<div style='border-left: 5px solid {$status_color}; padding: 15px; background: #fdfdfd;'>";
        $html .= "<h2 style='color: {$status_color};'>Decision: {$status_text}</h2>";
        $html .= "<p><strong>Patient:</strong> {$original_data['wife_name']} (ID: {$original_data['patient_id']})</p>";
        $html .= "<p><strong>Center:</strong> {$center_name}</p>";
        $html .= "<p><strong>Processed By:</strong> {$approver}</p>";
        $html .= "<p><strong>Remarks:</strong> {$reason}</p>";
        $html .= "</div></body></html>";

        // G. Dispatch Email
        $this->email->from('billing-system@indiaivf.in', 'HMS Billing Info');
        $this->email->to($primary_to); 
        $this->email->cc($cc_list);
        $this->email->subject($subject);
        $this->email->message($html);
        
        if (!$this->email->send()) {
            log_message('error', $this->email->print_debugger());
        }
    }

public function generate_pdf($id) {
    $this->load->library('pdf'); // Assuming you have Dompdf library loaded
    
    // Fetch your data
    $data['data'] = $this->db->get_where('hms_doctor_consultation', ['ID' => $id])->row_array();
    
    // Load the same view but pass a 'is_pdf' flag to hide buttons
    $html = $this->load->view('billings/forma_invoice_pdf_template', $data, true);
    
    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'portrait');
    $this->pdf->render();
    $this->pdf->stream("invoice_".$id.".pdf", array("Attachment" => 0));
}

	public function procedure_package($ID){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_product'){
				unset($_POST['action']);
				
				$post_arr['total_after_discount'] = isset($_POST['total_after_discount']) ? $_POST['total_after_discount'] : '';unset($_POST['total_after_discount']);
				$post_arr['booking_amount'] = isset($_POST['booking_amount']) ? $_POST['booking_amount'] : '';unset($_POST['booking_amount']);
				$post_arr['booking_amount_40'] = isset($_POST['booking_amount_40']) ? $_POST['booking_amount_40'] : '';unset($_POST['booking_amount_40']);
				$post_arr['booking_amount_50'] = isset($_POST['booking_amount_50']) ? $_POST['booking_amount_50'] : '';unset($_POST['booking_amount_50']);
				$post_arr['counsellor_signature'] = isset($_POST['counsellor_signature']) ? $_POST['counsellor_signature'] : '';unset($_POST['counsellor_signature']);
				$post_arr['coordinator_signature'] = isset($_POST['coordinator_signature']) ? $_POST['coordinator_signature'] : '';unset($_POST['coordinator_signature']);
				$post_arr['add_on'] = isset($_POST['add_on']) ? $_POST['add_on'] : '';unset($_POST['add_on']);
				$post_arr['center_number'] = isset($_POST['center_number']) ? $_POST['center_number'] : '';unset($_POST['center_number']);
				$post_arr['package_name'] = isset($_POST['package_name']) ? $_POST['package_name'] : '';unset($_POST['package_name']);
				$post_arr['status'] = isset($_POST['status']) ? $_POST['status'] : '';unset($_POST['status']);
				$post_arr['package_date'] = isset($_POST['package_date']) ? $_POST['package_date'] : '';unset($_POST['package_date']);
				$post_arr['booking_date'] = isset($_POST['booking_date']) ? $_POST['booking_date'] : '';unset($_POST['booking_date']);
				$post_arr['patient_id'] = isset($_POST['patient_id']) ? $_POST['patient_id'] : '';unset($_POST['patient_id']);
				$post_arr['appointment_id'] = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : '';unset($_POST['appointment_id']);
				$post_arr['employee_number'] = isset($_POST['employee_number']) ? $_POST['employee_number'] : '';unset($_POST['employee_number']);
                
				$icounte = $mcounte = $ccounte = $spcounte = 1;
				$i_counte = $m_counte = $c_counte = $s_pcounte = array();
				$i_counter = $m_counter = $c_counter = $s_pcounter = array();
				foreach($_POST as $key => $val){
					$pos_c = strpos($key, 'procedure_name_');
					if ($pos_c === false) {} else {
						$cid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$c_counter[] = $cid;
					}	
				}

				if(!empty($c_counter)){
					foreach($c_counter as $key => $ccounte){
						if($_POST['procedure_name_'.$ccounte] == ''){
							unset($_POST['procedure_ID_'.$ccounte]);
							unset($_POST['procedure_name_'.$ccounte]);
							unset($_POST['code_'.$ccounte]);
							unset($_POST['price_'.$ccounte]);
							unset($_POST['discount_'.$ccounte]);
							unset($_POST['after_discount_'.$ccounte]);
						}else{
							// insert query
							$c_counte[] = array('procedure_ID'=> $_POST['procedure_ID_'.$ccounte],'procedure_name'=> $_POST['procedure_name_'.$ccounte],'code'=> $_POST['code_'.$ccounte],'price'=> $_POST['price_'.$ccounte],'discount'=> $_POST['discount_'.$ccounte],'after_discount' =>$_POST['after_discount_'.$ccounte]);
						}
					}
				}
								
				$details = array();
				$details['consumables'] = $c_counte;
				$post_arr['package'] = serialize($details);
				$result = $this->billings_model->update_product($post_arr, $ID);
				
				//echo "<pre>";
				//print_r($post_arr);
				//echo "</pre>";
				//die();
				
				$fosql = "SELECT * FROM hms_appointments WHERE paitent_id = '{$post_arr['patient_id']}' AND paitent_type = 'new_patient'";
				$fo_result = run_select_query($fosql);
				$lead_id = is_array($fo_result) ? $fo_result['crm_id'] : '';
				
				$center_sql = "SELECT * FROM hms_centers WHERE center_number = '".$fo_result['appoitment_for']."'";
				$center_result = run_select_query($center_sql);
				
					$data = [
						"wife_phone" => $fo_result['wife_phone'],
						"patient_id" => $post_arr['patient_id'],
						"appointment_id" => $post_arr['appointment_id'],
						"ch_fc_name" => $post_arr['counsellor_signature'],
						"centre_booking" => $center_result['center_name'],
						"lead_id" => $lead_id
					];

					$urls = [
						'lead_1' => 'https://flertility.in/lead/consultations/',
						'lead_2' => 'https://staging.flertility.in/lead/consultations/'
					];

					foreach ($urls as $key => $url) {
						$curl = curl_init();

						curl_setopt_array($curl, array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => '',
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 10,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => 'POST',
							CURLOPT_POSTFIELDS => json_encode($data),
							CURLOPT_HTTPHEADER => array(
								'Content-Type: application/json'
							),
						));

						$response = curl_exec($curl);

						if (curl_errno($curl)) {
							echo "[$key] Error: " . curl_error($curl) . "\n";
						} else {
							echo "[$key] Response: " . $response . "\n";
						}

						curl_close($curl);
					}


				if($result > 0){
					header("Location: " . base_url() . "billings/procedure_package/" . $ID . "?success");
					die();
				}else{
					header("location:" .base_url(). "billings/procedure_package/" . $ID . "?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			$data['data'] = $this->billings_model->get_transfer_data($ID);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billings/procedure_package', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	
	public function forma_invoice_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center_number = $this->input->get('center_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->billings_model->export_all_center_stocks($center_number, $start_date, $end_date, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Forma-Invoice-list-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Patient Id, Wife Name,Wife Age, Procedure Name,Code,Price,Discount,After Discount,Procedure Name,Code,Price,Discount,After Discount,Procedure Name,Code,Price,Discount,After Discount,Procedure Name,Code,Price,Discount,After Discount, add_on';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$lead_arr = array($val['patient_id'], $val['wife_name'],$val['wife_age'], $val['procedure_name_1'], $val['code_1'], $val['price_1'], $val['discount_1'], $val['after_discount_1'], $val['procedure_name_2'], $val['code_2'], $val['price_2'], $val['discount_2'], $val['after_discount_2'], $val['procedure_name_3'],$val['code_3'], $val['price_3'],$val['discount_3'],$val['after_discount_3'],$val['procedure_name_4'],$val['code_4'], $val['price_4'],$val['discount_4'],$val['after_discount_4'], $val['add_on']);
					fputcsv($fp, $lead_arr);
				}
				
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "billings/forma_invoice_list";
        	$config["total_rows"] = $this->billings_model->get_all_center_stocks($center_number, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->billings_model->get_all_center_stocks_patination($config["per_page"], $per_page, $center_number, $start_date, $end_date, $patient_id);
			//var_dump($data);die;
			$data["center_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
            $template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billings/forma_invoice_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function withdrawl_prescription($patient_id){
    $logg = checklogin();
    if($logg['status'] == true){        
        $data = array();
        $template = get_header_template($logg['role']);
        $data['data'] = $this->accounts_model->patient_details($patient_id);
        
        // FIX: Assign this to the $data array so the view can read it
        $data['master_investigations'] = $this->investigation_model->get_master_investigations_list();
        
        $this->load->view($template['header']);
        $this->load->view('billings/withdrawl_prescription', $data);
       // $this->load->view($template['footer']);
    }else{
        header("location:" .base_url(). "");
        die();
    }
}
	
	function doctor_name($doctor_id){
		$doctor_name = $this->doctors_model->get_doctor_data($doctor_id);
		if(!empty($doctor_name)){
		    return $doctor_name['name'];
		}else {return "";}
	}

	public function update_consultation_status() {
    // AJAX se aayi hui ID receive karein
    $id = $this->input->post('id');
    
    if(!empty($id)) {
        // 1. Database se current status fetch karein
        $this->db->select('status');
        $this->db->from('hms_doctor_consultation');
        $this->db->where('ID', $id);
        $query = $this->db->get();
        $record = $query->row();
        
        if($record) {
            // 2. Status Toggle Logic 
            // (Agar pending hai toh approved kar do, approved hai toh pending kar do)
            // Aap in values ko apni requirement ke hisaab se badal sakte hain.
            $current_status = $record->status;
            $new_status = ($current_status == '3') ? '0' : '3';
            
            // 3. Database mein naya status update karein
            $this->db->where('ID', $id);
            $this->db->update('hms_doctor_consultation', ['status' => $new_status]);
            
            // 4. Success response bhejein
            echo json_encode([
                'status' => 'success', 
                'new_status' => $new_status
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Record not found in database.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID.']);
    }
}


public function request_ceo_approval() {
    $patient_id   = $this->input->post('patient_id');
    
    // FIX 1: $vl hata diya gaya hai, aur direct $patient_id use kiya hai
    $patient_data = get_patient_detail($patient_id);
    
    // Safety check: Agar data na mile toh "N/A" print ho
    $patient_name = (!empty($patient_data) && isset($patient_data['wife_name'])) ? $patient_data['wife_name'] : 'N/A';

	$session_biller_name = $_SESSION['logged_billing_manager']['name'];
	
	$sql4 = "SELECT * FROM hms_centers WHERE center_number='" . $_SESSION['logged_billing_manager']['center'] . "'";
    $session_center_name = run_select_query($sql4);
    
    $reason       = $this->input->post('reason');
    $biller_id    = $this->input->post('biller_id');
    $billing_from = $this->input->post('billing_from');
    $ceo_email    = 'ceo@indiaivf.in';

    // SMTP Configuration
    $config = array(
        'protocol'    => 'smtp',
        'smtp_host'   => 'smtp.gmail.com',
        'smtp_port'   => 587,
        'smtp_crypto' => 'tls',
        'smtp_user'   => 'noreply@indiaivf.in', 
        'smtp_pass'   => 'fvve bzgn oesk tava', // Google App Password
        'mailtype'    => 'html',
        'charset'     => 'utf-8',
        'newline'     => "\r\n",
        'wordwrap'    => TRUE
    );

    // Unique Token/Link for Approval
    $approve_link = base_url("Billings/approve_consultation/" . $patient_id);

    // FIX 2: Email body mein $patient_name variable safely add kiya gaya hai
    $message = "<h3>Consultation Approval Required</h3>
                <p><b>Patient ID:</b> $patient_id</p>
                <p><b>Patient Name:</b> $patient_name</p>
                <p><b>Reason:</b> $reason</p>
                <p><b>Biller :</b> " . $session_biller_name . "</p>
                <p><b>Billing Center:</b>". $session_center_name['center_name'] ." </p>
                <p>No paid procedure found. Please approve to clear this billing.</p>
                <br>
                <a href='$approve_link' style='background:green;color:white;padding:10px;text-decoration:none;border-radius:5px;'>APPROVE BILLING</a>";

    // Email library load aur initialize karein
    $this->load->library('email');
    $this->email->initialize($config); 
    
    $this->email->from('noreply@indiaivf.in', 'HMS Billing');
    $this->email->to($ceo_email);
    
    // Subject line mein bhi naam add kar diya hai, taaki mail inbox mein jaldi samajh aaye
    $this->email->subject("Approval Required - $patient_id - $patient_name");
    $this->email->message($message);

    if($this->email->send()) { 
        echo "success"; 
    } else { 
        echo "error"; 
    }
}

// CEO click karega toh ye function chalega
public function approve_consultation($patient_id) {
    // Sirf us patient ka latest record update karein jiska status abhi 0 hai
    $this->db->where('patient_id', $patient_id);
    $this->db->where('approve_by_status', 2); 
    $this->db->order_by('id', 'DESC');
    $this->db->limit(1);
    
    $update = $this->db->update('hms_consultation', array('approve_by_status' => 1));

    if($update) {
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
                <h1 style='color:green;'>✔️ Approved!</h1>
                <p>Billing for Patient ID <b>$patient_id</b> has been approved (Status 1).</p>
              </div>";
    } else {
        echo "Error: Record not found or already approved.";
    }
}

// Application Controller File (e.g., Counselor.php)
public function save_preview_log() {
    // AJAX request data sanitization
    $patient_id = $this->input->post('patient_id');
    $appointment_id = $this->input->post('appointment_id');
    $consultation_id = $this->input->post('consultation_id');
    $logged_user = $_SESSION["logged_counselor"]["employee_number"] ?? 'SYSTEM';
    
    // Unique Preview ID Generate Karna (Format: PRV-2026-XXXXX)
    $unique_preview_id = 'PRV-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 5));
    
    // Array ready karna database log ke liye
    $log_data = array(
        'preview_id'     => $unique_preview_id,
        'patient_id'     => $patient_id,
        'appointment_id' => $appointment_id,
        'consultation_id'=> $consultation_id,
        'generated_by'   => $logged_user,
        'created_at'     => date('Y-m-d H:i:s'),
        'ip_address'     => $this->input->ip_address()
    );
    
    // Database me log record insert karna
    // Note: 'hms_preview_logs' table aapko DB me create karna hoga
    $this->db->insert('hms_preview_logs', $log_data);
    
    if ($this->db->affected_rows() > 0) {
        echo json_encode(array('status' => 'success', 'preview_id' => $unique_preview_id));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to write log row.'));
    }
    exit;
}
	
} 