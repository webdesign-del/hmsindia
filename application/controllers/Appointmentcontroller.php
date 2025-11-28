<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointmentcontroller extends CI_Controller {

	public function __construct()
	{
		// Load parent's constructor.
       	parent::__construct();
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
	    $this->load->library('session');
		$this->load->model(array('billingmodel_model', 'appointment_model', 'doctors_model', 'center_model'));		
		$this->load->helper('myhelper');
		$this->load->library("pagination");
	}
	
	function daily_appointments(){
		$data = array();
		$data['appointments'] = $this->appointment_model->get_daily_appointments();
		$this->load->view('templates/appointment-header');
		$this->load->view('appointments/daily_appointments', $data);
		$this->load->view('templates/footer');
	}

	public function my_appointments()
	{
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			if (!empty($_SESSION['logged_billing_manager']['center'])) {
				$center = $_SESSION['logged_billing_manager']['center'];
			} elseif (!empty($_SESSION['logged_counselor']['center'])) {
				$center = $_SESSION['logged_counselor']['center'];
			} else {
				$center = null; 
			}
			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}			
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$doctor = $this->input->get('doctor', true);
			$status = $this->input->get('status', true);
			$patient_id = $this->input->get('patient_id', true);
			$patient_name = $this->input->get('patient_name', true);
			$paitent_type = $this->input->get('paitent_type', true);
			$crm_id = $this->input->get('crm_id', true);
			$lead_source = $this->input->get('lead_source', true);
			// Decode URL encoded values to handle spaces and special characters
			if(!empty($lead_source)) {
				$lead_source = urldecode($lead_source);
			}
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->appointment_model->export_my_appointments($start_date, $end_date, $center, $patient_id, $doctor, $patient_name, $paitent_type,$crm_id, $lead_source);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Appointments-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'CRM ID,Lead Source, IIC ID, Patient Name, Husband Name, Nationality,Reason of Visit,  Appoitmented Date,Paitent Type, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['crm_id'],$val['lead_source'], $val['paitent_id'], $val['wife_name'], $val['husband_name'], $val['nationality'], $val['reason_of_visit'], $val['appoitmented_date'],$val['paitent_type'], $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "my_appointments";
        	$config["total_rows"] = $this->appointment_model->my_appointments_count($center, $start_date, $end_date, $patient_id, $patient_name, $status, $doctor, $paitent_type, $crm_id, $lead_source);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['appointments'] = $this->appointment_model->my_appointments_pagination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $patient_name, $status, $doctor, $paitent_type, $crm_id, $lead_source);
			$data["status"] = $status;
			$data["doctor"] = $doctor;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["patient_name"] = $patient_name;
			$data["paitent_type"] = $paitent_type;
			$data["crm_id"] = $crm_id;
			$data["lead_source"] = $lead_source;
			$data["lead_sources"] = $this->appointment_model->get_all_lead_sources();
			$data["counselors_grouped"] = $this->appointment_model->get_counselors_grouped_by_center();
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/appointment', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	public function my_appointments_in_camp()
	{
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			if (!empty($_SESSION['logged_billing_manager']['center'])) {
				$center = $_SESSION['logged_billing_manager']['center'];
			} elseif (!empty($_SESSION['logged_counselor']['center'])) {
				$center = $_SESSION['logged_counselor']['center'];
			} elseif (!empty($_SESSION['logged_telecaller']['center'])) {
				$center = $_SESSION['logged_telecaller']['center'];
			}elseif (!empty($_SESSION['logged_doctor']['center'])) {
				$center = $_SESSION['logged_doctor']['center'];
			} else {
				$center = null; 
			}
			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}			
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$doctor = $this->input->get('doctor', true);
			$status = $this->input->get('status', true);
			$patient_id = $this->input->get('patient_id', true);
			$patient_name = $this->input->get('patient_name', true);
			$paitent_type = $this->input->get('paitent_type', true);
			$crm_id = $this->input->get('crm_id', true);
			$lead_source = $this->input->get('lead_source', true);
			$camp_id = $this->input->get('camp_id', true);
			if(!empty($lead_source)) {
				$lead_source = urldecode($lead_source);
			}
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->appointment_model->export_my_appointments_in_camp($start_date, $end_date, $center, $patient_id, $doctor, $patient_name, $paitent_type,$crm_id, $lead_source, $camp_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Appointments-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'CRM ID,Lead Source, IIC ID, Patient Name, Husband Name, Nationality,Reason of Visit,  Appoitmented Date,Paitent Type, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['crm_id'],$val['lead_source'], $val['paitent_id'], $val['wife_name'], $val['husband_name'], $val['nationality'], $val['reason_of_visit'], $val['appoitmented_date'],$val['paitent_type'], $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "my_appointments_camp";
			$config["total_rows"] = $this->appointment_model->my_appointments_count_in_camp($center, $start_date, $end_date, $patient_id, $patient_name, $status, $doctor, $paitent_type, $crm_id, $lead_source, $camp_id);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['appointments'] = $this->appointment_model->my_appointments_pagination_in_camp($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id, $patient_name, $status, $doctor, $paitent_type, $crm_id, $lead_source, $camp_id);
			$data["status"] = $status;
			$data["doctor"] = $doctor;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["patient_name"] = $patient_name;
			$data["paitent_type"] = $paitent_type;
			$data["crm_id"] = $crm_id;
			$data["lead_source"] = $lead_source;
			$data["camp_id"] = $camp_id;
			$data["lead_sources"] = $this->appointment_model->get_all_lead_sources();
			
			// Load camps for the current center
			$this->load->model('camp_model');
			$data["camps"] = $this->camp_model->get_camps_by_center($center);
			
			// Only show appointments if the center has camps
			if(empty($data["camps"])) {
				$data['appointments'] = array();
				$data["links"] = '';
			}
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/appointment_in_camp', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function telecaller_appointments()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$center = $_SESSION['logged_telecaller']['center'];
			$data['appointments'] = $this->appointment_model->my_appointments($center);
			$this->load->view($template['header']);
			$this->load->view('appointments/telecaller_appointments', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	
	
/*	public function pending_consultation_billing()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$center = $_SESSION['logged_billing_manager']['center'];
			$data['appointments'] = $this->appointment_model->pending_consultation_billing($center);
			$this->load->view($template['header']);
			$this->load->view('appointments/pending_consultation_billing', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	} */
	
	   public function pending_consultation_billing(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$wife_name = $this->input->get('wife_name', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "pending-consultation-billing";
        	$config["total_rows"] = $this->appointment_model->pending_consultation_billing($center, $wife_name);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->appointment_model->pending_consultation_billing_patination($config["per_page"], $per_page, $center, $wife_name);
			
			$data["wife_name"] = $wife_name;
			$template = get_header_template($logg['role']);
			$center = $_SESSION['logged_billing_manager']['center'];
			$this->load->view($template['header']);
			$this->load->view('appointments/pending_consultation_billing', $data);
			$this->load->view($template['footer']);
			//var_dump($html);
			//die;
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function appointment_status()
	{
		$appointment_status = $_POST['appointment_status'];
		$appointment_id = $_POST['appointment_id'];
		$status = $this->appointment_model->appointment_status($appointment_status, $appointment_id);
		if($status > 0){
		    $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where ID='".$appointment_id."' limit 1"; 
			$query2 = $this->db->query($sql2);
            $select_result2 = $query2->result(); 
			$paitent_id = $wife_phone = $appoitment_for = "";
			foreach ($select_result2 as $res_val){
				$paitent_id =  $res_val->paitent_id;
				$appoitment_for = $res_val->appoitment_for;
				$wife_phone = $res_val->wife_phone;
			}
		    $url="https://flertility.in/appointment/hms-appointment-status/?accessKey=AKIA3OFKVR3DZWGD7HSGKTER001";
			$data= array(
				'patient_id'=>$res_val->paitent_id,
				'appointment_id'=>$appointment_id,
				'appointment_status' => $appointment_status,
				'patient_mobile' => $wife_phone,
				'appoitment_for' => $appoitment_for,
				'hms_username' => $this->session->userdata['logged_billing_manager']['username'],
				'hms_employee_id' => $this->session->userdata['logged_billing_manager']['employee_number'],
				'hms_employee_name' => $this->session->userdata['logged_billing_manager']['name']
			);
			$ch = curl_init(); // Initialize cURL
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_exec($ch);
			curl_close($ch);
			$response = array('status' => 'status_updated', 'message'=> 'Appointment updated');
			echo json_encode($response);
			die;
		}else{
			$response = array('status' => 'status_same', 'message'=> '');
			echo json_encode($response);
			die;
		}
	}
	
	public function reschedule_appointment(){
		if(isset($_POST['reschedule_appointment']) && isset($_POST['reschedule_appointment']) && $_POST['reschedule_appointment'] == 'reschedule_appointment'){
			unset($_POST['reschedule_appointment']);
			
			$_POST['status'] = 'rescheduled';
			$reschedule_appointment_id = $_POST['reschedule_appointment_id'];unset($_POST['reschedule_appointment_id']);
			$update_appointment = $this->appointment_model->reschedule_appointment_update($_POST, $reschedule_appointment_id);
			if($update_appointment > 0){
				$appointment_details = doctor_appointment($reschedule_appointment_id);
				$doctor_details = doctor_details($appointment_details['appoitmented_doctor']);
				$patient_to = $patient_subject = $patient_message = $doctor_to = $doctor_subject = $doctor_message = "";
				//Patient emails
				$patient_to = $appointment_details['wife_email'];
				$patient_subject = "Appointment rescheduled";
				$patient_message = "Hi ".$appointment_details['wife_name'].",<br/> Your appointment is rescheduled with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($_POST['appoitmented_date']))." at ".$_POST['appoitmented_slot'].".";
				// send_mail($patient_to, $patient_subject, $patient_message);
				$patient_phone = $appointment_details['wife_phone'];
				$sms_message = "Hi ".$appointment_details['wife_name'].", Your appointment is rescheduled with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($_POST['appoitmented_date']))." at ".$_POST['appoitmented_slot'].".";
				send_sms($patient_phone, $sms_message);
				//Doctor emails
				$doctor_to = $doctor_details['email'];
				$doctor_subject = "Appointment rescheduled";
				$doctor_message = "Hi Dr.".$doctor_details['name'].",<br/> Appointment is rescheduled on ".date("d-m-Y", strtotime($_POST['appoitmented_date']))." at ".$_POST['appoitmented_slot'].".";
				// send_mail($doctor_to, $doctor_subject, $doctor_message);

				header("location:" .base_url(). "my_appointments?m=".base64_encode('Appointment rescheduled!').'&t='.base64_encode('success'));
				die();
			}else{
				header("location:" .base_url(). "my_appointments?m=".base64_encode('Opps, something went wrong!').'&t='.base64_encode('error'));
				die();
			}
		}else{
			header("location:" .base_url(). "my_appointments?m=".base64_encode('Opps, something went wrong!').'&t='.base64_encode('error'));
			die();
		}
	}
	
	public function telecaller_reschedule_appointment(){
		if(isset($_POST['reschedule_appointment']) && isset($_POST['reschedule_appointment']) && $_POST['reschedule_appointment'] == 'reschedule_appointment'){
			unset($_POST['reschedule_appointment']);
			
			$_POST['status'] = 'rescheduled';
			$reschedule_appointment_id = $_POST['reschedule_appointment_id'];unset($_POST['reschedule_appointment_id']);
			$update_appointment = $this->appointment_model->reschedule_appointment_update($_POST, $reschedule_appointment_id);
			if($update_appointment > 0){
				$appointment_details = doctor_appointment($reschedule_appointment_id);
				$doctor_details = doctor_details($appointment_details['appoitmented_doctor']);
				$patient_to = $patient_subject = $patient_message = $doctor_to = $doctor_subject = $doctor_message = "";
				//Patient emails
				$patient_to = $appointment_details['wife_email'];
				$patient_subject = "Appointment rescheduled";
				$patient_message = "Hi ".$appointment_details['wife_name'].",<br/> Your appointment is rescheduled with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($_POST['appoitmented_date']))." at ".$_POST['appoitmented_slot'].".";
				// send_mail($patient_to, $patient_subject, $patient_message);
				$patient_phone = $appointment_details['wife_phone'];
				$sms_message = "Hi ".$appointment_details['wife_name'].", Your appointment is rescheduled with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($_POST['appoitmented_date']))." at ".$_POST['appoitmented_slot'].".";
				send_sms($patient_phone, $sms_message);
				//Doctor emails
				$doctor_to = $doctor_details['email'];
				$doctor_subject = "Appointment rescheduled";
				$doctor_message = "Hi Dr.".$doctor_details['name'].",<br/> Appointment is rescheduled on ".date("d-m-Y", strtotime($_POST['appoitmented_date']))." at ".$_POST['appoitmented_slot'].".";
				// send_mail($doctor_to, $doctor_subject, $doctor_message);
				header("location:" .base_url(). "telecaller-appointments?m=".base64_encode('Appointment rescheduled!').'&t='.base64_encode('success'));
				die();
			}else{
				header("location:" .base_url(). "telecaller-appointments?m=".base64_encode('Opps, something went wrong!').'&t='.base64_encode('error'));
				die();
			}
		}else{
			header("location:" .base_url(). "telecaller-appointments?m=".base64_encode('Opps, something went wrong!').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function ajax_appointment_filter(){
		if($_POST['type'] == 'by_doctor'){
			$doctor = $_POST['doctor'];
			$data = $this->appointment_model->ajax_appointment_doctor_data($doctor);
		}else if($_POST['type'] == 'appointment_status'){
			$status = $_POST['status'];
			$data = $this->appointment_model->ajax_appointment_status_data($status);
		}else if($_POST['type'] == 'date_wise'){
			$start = $_POST['start'];
			$end = $_POST['end'];
			$data = $this->appointment_model->ajax_appointment_date_wise_data($start, $end);
		}else if($_POST['type'] == 'particular_date_filter'){
			$appointment_date = $_POST['appointment_date'];
			$data = $this->appointment_model->ajax_appointment_particular_date_data($appointment_date);
		}else{
			$response = array('appointment_html'=>"", 'result'=> 0);
			echo json_encode($response);
			die;
		}
		if(!empty($data)){
			$appointment_html = "";
			$count = 1;			
			foreach($data as $key => $vl){
				$cancelled_select = $rescheduled_select = $no_show_select = $patient_in = $billing_done = $inclinic_select = "";
				if($vl['status'] == 'cancelled'){ $cancelled_select = "selected='selected'"; }
				if($vl['status'] == 'rescheduled'){ $rescheduled_select = "selected='selected'"; }
				if($vl['status'] == 'no_show'){ $no_show_select = "selected='selected'"; }
				if($vl['status'] == 'visited'){ $billing_done = "selected='selected'"; }
				if($vl['status'] == 'consultation'){ $patient_in = "selected='selected'"; }
				if($vl['status'] == 'in_clinic'){ $inclinic_select = "selected='selected'"; }
				$appointment_html .= '<tr class="odd gradeX"><td>'.$count.'</td>';
				if($vl['paitent_type'] == 'exist_patient'){
					 $appointment_html .= '<td><a target="_blank" href="'.base_url().'patient_details/'.$vl['paitent_id'].'">'.$vl['wife_name'].'</a></td>';
				} else {
					$appointment_html .= '<td>'.$vl['wife_name'].'</td>';
				}
					$appointment_html .= '<td>Dr. '.$this->doctor_name($vl['appoitmented_doctor']).'</td><td>'.$vl['appoitmented_date'].'</td><td>'.$vl['appoitmented_slot'].'</td><td>'.$vl['reason_of_visit'].'</td>';
				if($vl['status'] == 'booked' || $vl['status'] == 'rescheduled' || $vl['status'] == 'in_clinic'){			
					$appointment_html .= '<td class="role" id="appint_td_'.$vl['ID'].'"><div id="appoint_'.$vl['ID'].'"><select appointment_id="'.$vl['ID'].'" doctor_id="'.$vl['appoitmented_doctor'].'" class="appointment_status"><option value="">--Select status--</option><option value="in_clinic" '.$inclinic_select.'>In clinic</option><option value="cancelled" '.$cancelled_select.'>Cancelled</option><option value="rescheduled" '.$rescheduled_select.'>Rescheduled</option><option value="no_show" '.$no_show_select.'>No show</option></select></div></td>';
				}else if($vl['billed'] == '1'){
						if($vl['status'] == 'consultation_done'){$appointment_html .= '<td class="role">Consultation done</td>';}else{
						 $appointment_html .= '<td class="role"><select appointment_id="'.$vl['ID'].'" doctor_id="'.$vl['appoitmented_doctor'].'" class="appointment_status">
                                    <option value="visited" '.$billing_done.'>Biling done</option>
                                    <option value="consultation" '.$patient_in.'>Patient in</option>
                                </select></td>';
								}
						}else{ $appointment_html .= '<td>'.strtoupper($vl['status']).'<td>';}
				if($vl['billed'] == '0'){ if($vl['status'] == "in_clinic"){
					$appointment_html .= '<td><a href="'.base_url('consultation/'.$vl['ID']).'" class="btn btn-primary" id="billing_link_'.$vl['ID'].'">Consultation billing</a></td>';
				}else{$appointment_html .= '<td></td>';} }else{ $appointment_html .= '<td>BILLED</td>'; }
            	$appointment_html .= '</tr>';
				$count++;
			}
			$response = array('appointment_html'=>$appointment_html, 'result'=> 1);
			echo json_encode($response);
			die;
		}else{
			$response = array('appointment_html'=>"", 'result'=> 0);
			echo json_encode($response);
			die;
		}
	}
	
	public function followup_appointment(){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_followup'){
				unset($_POST['action']);
				$patient_details  = get_patient_detail($_POST['paitent_id']);
				if(empty($_POST['appoitment_for']) && empty($_POST['appoitmented_date']) && empty($_POST['appoitmented_doctor']) && empty($_POST['appoitmented_slot'])){
				    header("location:" .base_url()."follow-up-appointment?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
					die();
				}
				$appointments = $this->billingmodel_model->search_appointment($patient_details['wife_phone'], "phone");
    			if(count($appointments) > 0){
    				header("location:" .base_url(). "follow-up-appointment?m=".base64_encode('Appointment already booked. Go to my appointments').'&t='.base64_encode('error'));
    				die();
    			}
				// var_dump($_POST);die;
				$doctor_details = doctor_details($_POST['appoitmented_doctor']);
				$appointment_arr = array();
				$appointment_arr['paitent_type'] = 'exist_patient';
				$appointment_arr['paitent_id'] = $_POST['paitent_id'];
				$appointment_arr['wife_name'] = $patient_details['wife_name'];
				$appointment_arr['wife_phone'] = $patient_details['wife_phone'];
				$appointment_arr['wife_email'] = $patient_details['wife_email'];;
				$appointment_arr['nationality'] = $patient_details['nationality'];;
				$appointment_arr['reason_of_visit'] = $_POST['reason_of_visit'];
				$appointment_arr['appoitment_for'] = $_POST['appoitment_for'];unset($_POST['appoitment_for']);
				$appointment_arr['appoitmented_date'] = $_POST['appoitmented_date'];
				$appointment_arr['appoitmented_doctor'] = $_POST['appoitmented_doctor'];unset($_POST['appoitmented_doctor']);
				$appointment_arr['appoitmented_slot'] = $_POST['appoitmented_slot'];
				$appointment_arr['follow_up_appointment'] = 1;
				$appointment_arr['previous_appointment'] = 0;
				$appointment_arr['appointment_added'] = date('Y-m-d H:i:s');
				$appointment = $this->billingmodel_model->insert_appointments($appointment_arr);
				if($appointment > 0){
				    $patient_phone = $patient_details['wife_phone'];
				    $centre_details = get_centre_details($appointment_arr['appoitment_for']);
    				$appointwhatmsg = array();
    				$appointwhatmsg = array($appointment_arr['wife_name'], $centre_details['center_name'], date("d-m-Y", strtotime($appointment_arr['appoitmented_date'])), $appointment_arr['appoitmented_slot'], isset($centre_details['center_location'])?$centre_details['center_location']:"");
                    $appointsendmsg = whatsappappointment($patient_phone, $appointment_arr['wife_name'], $centre_details['center_name'], date("d-m-Y", strtotime($appointment_arr['appoitmented_date'])), $appointment_arr['appoitmented_slot'], isset($centre_details['center_location'])?$centre_details['center_location']:"");
					$doctor_details = doctor_details($appointment_arr['appoitmented_doctor']);
					$patient_to = $patient_subject = $patient_message = $doctor_to = $doctor_subject = $doctor_message = "";
					//Patient emails
					$patient_to = $patient_details['wife_email'];
					$patient_subject = "Followup appointment booked";
					$patient_message = "Hi ".$patient_details['wife_name'].",<br/> Your followup appointment is booked with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($appointment_arr['appoitmented_date']))." at ".$appointment_arr['appoitmented_slot'].".";
					// send_mail($patient_to, $patient_subject, $patient_message);
					$sms_message = "Hi ".$patient_details['wife_name'].", Your followup appointment is booked with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($appointment_arr['appoitmented_date']))." at ".$appointment_arr['appoitmented_slot'].".";
					send_sms($patient_phone, $sms_message);
					//Doctor emails
					$doctor_to = $doctor_details['email'];
					$doctor_subject = "Followup appointment";
					$doctor_message = "Hi Dr.".$doctor_details['name'].",<br/> Followup Appointment is booked on ".date("d-m-Y", strtotime($appointment_arr['appoitmented_date']))." at ".$appointment_arr['appoitmented_slot'].".";
					// send_mail($doctor_to, $doctor_subject, $doctor_message);
					header("location:" .base_url()."follow-up-appointment?m=".base64_encode('Followup booked successfully!').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url()."follow-up-appointment?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('billing_view/follow-up-appointment', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	//QUERY
	function center_doctors(){
		$center = $_SESSION['logged_billing_manager']['center'];
		$doctor_list = $this->doctors_model->get_center_doctors($center);
		return $doctor_list;
	}
	function doctor_name($doctor_id){
		$doctor_name = $this->doctors_model->get_doctor_data($doctor_id);
		if(!empty($doctor_name)){
		    return $doctor_name['name'];
		}else { return "";};
	}
	
	function get_center_list(){
		$center = $this->center_model->get_centers();
		return $center;
	}
	
	function all_appointments()
	{
	    try{
               $data = $this->input->get();
                if (!empty($data['first_name'])) 
                {
                   if (!empty($data['last_name'])) 
                   {
                        if (!empty($data['mobile_number'])) 
                        {
                            if (!empty($data['email'])) 
                            {
                            $Json['status']=200;
                            $Json['message']='Data Fetched successfully';
                            return $this->output
                                    ->set_content_type('application/json')
                                    ->set_status_header(200)
                                    ->set_output(json_encode($Json));
                    
                            }else
                            {
                                $Json['status']=400;
                                $Json['message']='Email id is Required';
                                return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(200)
                                        ->set_output(json_encode($Json));
                            } 

                        }else
                        {
                            $Json['status']=400;
                            $Json['message']='Mobile no is Required';
                            return $this->output
                                    ->set_content_type('application/json')
                                    ->set_status_header(200)
                                    ->set_output(json_encode($Json));
                        } 
                    
                    }else
                    {
                        $Json['status']=400;
                        $Json['message']='Last name is Required';
                        return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode($Json));
                    } 

                }else
                {
                    $Json['status']=400;
                    $Json['message']='First name is Required';
                    return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($Json));
                } 
            
            } catch(Throwable $e)
            {
                $msg = 'Server Down Something went wrong';
                $description = $e->getMessage(); 
                $Json['status'] = 400;
                $Json['msg'] = $msg; 
                return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($Json));
            }
	}

	/**
	 * Modern Appointments Page
	 */
	public function modern_appointments()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/index', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/**
	 * Get Appointments via AJAX
	 */
	public function getAppointments()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			try {
				$filters = $this->getFilters();
				$page = $this->input->get('page', TRUE) ?: 1;
				$limit = $this->input->get('limit', TRUE) ?: 10;
				$center = $this->getCenter();
				// Convert page to offset for the existing model (which expects 0-based offset)
				$offset = ($page - 1) * $limit;
				$appointments = $this->appointment_model->my_appointments_pagination(
					$limit, 
					$offset, 
					$center, 
					$filters['start_date'], 
					$filters['end_date'], 
					$filters['patient_id'], 
					$filters['patient_name'], 
					$filters['status'], 
					$filters['doctor_id'], 
					$filters['patient_type'], 
					$filters['crm_id']
				);
				// Add doctor names to appointments
				foreach($appointments as $key => $appointment) {
					$appointments[$key]['doctor_name'] = $this->doctor_name($appointment['appoitmented_doctor']);
				}
				$total = $this->appointment_model->my_appointments_count(
					$center, 
					$filters['start_date'], 
					$filters['end_date'], 
					$filters['patient_id'], 
					$filters['patient_name'], 
					$filters['status'], 
					$filters['doctor_id'], 
					$filters['patient_type'], 
					$filters['crm_id']
				);
				$paginationData = $this->getPaginationData($total, $limit, $page);
				// Debug logging
				error_log("Pagination Debug - Page: $page, Limit: $limit, Total: $total, Offset: $offset");
				error_log("Pagination Data: " . json_encode($paginationData));
				error_log("Appointments Count: " . count($appointments));
				$response = [
					'status' => true, 
					'message' => 'Appointments retrieved successfully',
					'data' => [
						'appointments' => $appointments,
						'pagination' => $paginationData,
						'filters' => $filters
					]
				];
			} catch (Exception $e) {
				$response = ['status' => false, 'message' => 'Failed to retrieve appointments: ' . $e->getMessage()];
			}
		} else {
			$response = ['status' => false, 'message' => 'Authentication required'];
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	/**
	 * Create new appointment
	 */
	public function create()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if ($this->input->method() === 'post') {
				try {
					$appointmentData = $this->prepareAppointmentData();
					$appointmentId = $this->billingmodel_model->insert_appointments($appointmentData);
					if ($appointmentId) {
						$response = ['status' => true, 'message' => 'Appointment created successfully', 'data' => ['appointment_id' => $appointmentId]];
					} else {
						$response = ['status' => false, 'message' => 'Failed to create appointment'];
					}
				} catch (Exception $e) {
					$response = ['status' => false, 'message' => 'Error creating appointment: ' . $e->getMessage()];
				}
			} else {
				$response = ['status' => false, 'message' => 'Invalid request method'];
			}
		} else {
			$response = ['status' => false, 'message' => 'Authentication required'];
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	/**
	 * Update appointment status
	 */
	public function updateStatus()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			try {
				$appointmentId = $this->input->post('appointment_id');
				$status = $this->input->post('status');
				if (!$appointmentId || !$status) {
					$response = ['status' => false, 'message' => 'Appointment ID and status are required'];
				} else {
					$result = $this->appointment_model->appointment_status($status, $appointmentId);
					
					if ($result) {
						$response = ['status' => true, 'message' => 'Appointment status updated successfully'];
					} else {
						$response = ['status' => false, 'message' => 'Failed to update appointment status'];
					}
				}
				
			} catch (Exception $e) {
				$response = ['status' => false, 'message' => 'Error updating status: ' . $e->getMessage()];
			}
		} else {
			$response = ['status' => false, 'message' => 'Authentication required'];
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	/**
	 * Reschedule appointment
	 */
	public function reschedule()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			try {
				$appointmentId = $this->input->post('appointment_id');
				$newDate = $this->input->post('new_date');
				$newTime = $this->input->post('new_time');
				$reason = $this->input->post('reason');
				if (!$appointmentId || !$newDate || !$newTime) {
					$response = ['status' => false, 'message' => 'Appointment ID, new date, and new time are required'];
				} else {
					$updateData = [
						'appoitmented_date' => $newDate,
						'appoitmented_slot' => $newTime,
						'status' => 'rescheduled',
						'reschedule_reason' => $reason
					];
					
					$result = $this->appointment_model->reschedule_appointment_update($updateData, $appointmentId);
					
					if ($result) {
						$response = ['status' => true, 'message' => 'Appointment rescheduled successfully'];
					} else {
						$response = ['status' => false, 'message' => 'Failed to reschedule appointment'];
					}
				}
				
			} catch (Exception $e) {
				$response = ['status' => false, 'message' => 'Error rescheduling appointment: ' . $e->getMessage()];
			}
		} else {
			$response = ['status' => false, 'message' => 'Authentication required'];
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	/**
	 * Cancel appointment
	 */
	public function cancel()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			try {
				$appointmentId = $this->input->post('appointment_id');
				$reason = $this->input->post('reason');
				
				if (!$appointmentId || !$reason) {
					$response = ['status' => false, 'message' => 'Appointment ID and reason are required'];
				} else {
					$updateData = [
						'status' => 'cancelled',
						'cancellation_reason' => $reason
					];
					
					$result = $this->appointment_model->reschedule_appointment_update($updateData, $appointmentId);
					
					if ($result) {
						$response = ['status' => true, 'message' => 'Appointment cancelled successfully'];
					} else {
						$response = ['status' => false, 'message' => 'Failed to cancel appointment'];
					}
				}
				
			} catch (Exception $e) {
				$response = ['status' => false, 'message' => 'Error cancelling appointment: ' . $e->getMessage()];
			}
		} else {
			$response = ['status' => false, 'message' => 'Authentication required'];
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	/**
	 * Get appointment details
	 */
	public function getDetails($appointmentId)
	{
		$logg = checklogin();
		if($logg['status'] == true){
			try {
				$appointment = $this->appointment_model->doctor_appointment_details($appointmentId);
				
				if ($appointment) {
					$response = ['status' => true, 'message' => 'Appointment details retrieved successfully', 'data' => $appointment];
				} else {
					$response = ['status' => false, 'message' => 'Appointment not found'];
				}
				
			} catch (Exception $e) {
				$response = ['status' => false, 'message' => 'Error retrieving appointment: ' . $e->getMessage()];
			}
		} else {
			$response = ['status' => false, 'message' => 'Authentication required'];
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	/**
	 * Export appointments to CSV
	 */
	public function export()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			try {
				$filters = $this->getFilters();
				$center = $this->getCenter();
				
				$appointments = $this->appointment_model->export_my_appointments(
					$filters['start_date'], 
					$filters['end_date'], 
					$center, 
					$filters['patient_id'], 
					$filters['doctor_id'], 
					$filters['patient_name'], 
					$filters['patient_type'], 
					$filters['crm_id']
				);
				
				$this->exportToCSV($appointments);
				
			} catch (Exception $e) {
				$response = ['status' => false, 'message' => 'Error exporting appointments: ' . $e->getMessage()];
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode($response));
			}
		} else {
			redirect(base_url());
		}
	}

	/**
	 * Get available time slots
	 */
	public function getAvailableSlots()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$doctorId = $this->input->get('doctor_id', TRUE);
			$date = $this->input->get('date', TRUE);
			
			if (!$doctorId || !$date) {
				$response = ['status' => false, 'message' => 'Doctor ID and date are required'];
			} else {
				try {
					$slots = $this->getTimeSlots();
					$response = ['status' => true, 'message' => 'Available slots retrieved successfully', 'data' => $slots];
				} catch (Exception $e) {
					$response = ['status' => false, 'message' => 'Error retrieving slots: ' . $e->getMessage()];
				}
			}
		} else {
			$response = ['status' => false, 'message' => 'Authentication required'];
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	// Private helper methods

	private function getFilters()
	{
		return [
			'status' => $this->input->get('status', TRUE),
			'doctor_id' => $this->input->get('doctor_id', TRUE),
			'center_id' => $this->input->get('center_id', TRUE),
			'patient_name' => $this->input->get('patient_name', TRUE),
			'patient_phone' => $this->input->get('patient_phone', TRUE),
			'start_date' => $this->input->get('start_date', TRUE),
			'end_date' => $this->input->get('end_date', TRUE),
			'patient_type' => $this->input->get('patient_type', TRUE),
			'crm_id' => $this->input->get('crm_id', TRUE),
			'patient_id' => $this->input->get('patient_id', TRUE) ?: null,
			'lead_source' => $this->input->get('lead_source', TRUE) ? urldecode($this->input->get('lead_source', TRUE)) : null
		];
	}

	private function getCenter()
	{
		if (!empty($_SESSION['logged_billing_manager']['center'])) {
			return $_SESSION['logged_billing_manager']['center'];
		} elseif (!empty($_SESSION['logged_counselor']['center'])) {
			return $_SESSION['logged_counselor']['center'];
		}
		return null;
	}

	private function getPaginationData($total, $limit, $page)
	{
		return [
			'total' => $total,
			'per_page' => $limit,
			'current_page' => $page,
			'total_pages' => ceil($total / $limit)
		];
	}

	private function prepareAppointmentData()
	{
		return [
			'wife_name' => $this->input->post('patient_name'),
			'wife_phone' => $this->input->post('patient_phone'),
			'wife_email' => $this->input->post('patient_email'),
			'appoitmented_date' => $this->input->post('appointment_date'),
			'appoitmented_slot' => $this->input->post('appointment_time'),
			'appoitmented_doctor' => $this->input->post('doctor_id'),
			'appoitment_for' => $this->input->post('center_id'),
			'reason_of_visit' => $this->input->post('reason'),
			'paitent_type' => $this->input->post('patient_type') ?: 'new_patient',
			'appointment_added' => date('Y-m-d H:i:s')
		];
	}

	private function getTimeSlots()
	{
		return [
			['value' => '09:00', 'label' => '9:00 AM'],
			['value' => '09:30', 'label' => '9:30 AM'],
			['value' => '10:00', 'label' => '10:00 AM'],
			['value' => '10:30', 'label' => '10:30 AM'],
			['value' => '11:00', 'label' => '11:00 AM'],
			['value' => '11:30', 'label' => '11:30 AM'],
			['value' => '12:00', 'label' => '12:00 PM'],
			['value' => '12:30', 'label' => '12:30 PM'],
			['value' => '14:00', 'label' => '2:00 PM'],
			['value' => '14:30', 'label' => '2:30 PM'],
			['value' => '15:00', 'label' => '3:00 PM'],
			['value' => '15:30', 'label' => '3:30 PM'],
			['value' => '16:00', 'label' => '4:00 PM'],
			['value' => '16:30', 'label' => '4:30 PM'],
			['value' => '17:00', 'label' => '5:00 PM'],
			['value' => '17:30', 'label' => '5:30 PM']
		];
	}

	private function exportToCSV($appointments)
	{
		$filename = 'appointments_' . date('Y-m-d_H-i-s') . '.csv';
		
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		
		$output = fopen('php://output', 'w');
		
		// CSV headers
		fputcsv($output, [
			'ID', 'Patient Name', 'Phone', 'Email', 'Doctor', 'Center', 
			'Date', 'Time', 'Status', 'Reason', 'Created Date'
		]);
		
		// CSV data
		foreach ($appointments as $appointment) {
			fputcsv($output, [
				$appointment['ID'] ?? '',
				$appointment['wife_name'] ?? '',
				$appointment['wife_phone'] ?? '',
				$appointment['wife_email'] ?? '',
				$appointment['doctor_name'] ?? '',
				$appointment['center_name'] ?? '',
				$appointment['appoitmented_date'] ?? '',
				$appointment['appoitmented_slot'] ?? '',
				$appointment['status'] ?? '',
				$appointment['reason_of_visit'] ?? '',
				$appointment['appointment_added'] ?? ''
			]);
		}
		
		fclose($output);
		exit;
	}

	// ===========================================
	// MODERN APPOINTMENTS EXTENDED MODULES
	// ===========================================

	/**
	 * Modern Create Appointment Page
	 */
	public function modern_create()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['doctors'] = $this->center_doctors();
			$data['centers'] = $this->get_center_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/modern_create', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/**
	 * Modern Calendar View
	 */
	public function modern_calendar()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['doctors'] = $this->center_doctors();
			$data['centers'] = $this->get_center_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/modern_calendar', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/**
	 * Modern Reports Page
	 */
	public function modern_reports()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['doctors'] = $this->center_doctors();
			$data['centers'] = $this->get_center_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/modern_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/**
	 * Modern Settings Page
	 */
	public function modern_settings()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['doctors'] = $this->center_doctors();
			$data['centers'] = $this->get_center_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/modern_settings', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/**
	 * Modern Analytics Page
	 */
	public function modern_analytics()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['doctors'] = $this->center_doctors();
			$data['centers'] = $this->get_center_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/modern_analytics', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/**
	 * Modern Notifications Page
	 */
	public function modern_notifications()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['doctors'] = $this->center_doctors();
			$data['centers'] = $this->get_center_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/modern_notifications', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/**
	 * Modern Templates Page
	 */
	public function modern_templates()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['doctors'] = $this->center_doctors();
			$data['centers'] = $this->get_center_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/modern_templates', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/**
	 * Modern Integrations Page
	 */
	public function modern_integrations()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['doctors'] = $this->center_doctors();
			$data['centers'] = $this->get_center_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/modern_integrations', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

public function update_counselor() {
    // 1. Get data from the AJAX payload
    $appointment_id = $this->input->post('ID');        
    $counselor_name = $this->input->post('councellor');  

    // 2. Check for missing data
    if (empty($appointment_id) || empty($counselor_name)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing data.']);
        return;
    }

    // 3. First, get the current appointment data for comparison
    $this->db->select('ID, crm_id, councellor');
    $this->db->from('hms_appointments');
    $this->db->where('ID', $appointment_id);
    $appointment = $this->db->get()->row_array();

    if (empty($appointment)) {
        echo json_encode(['status' => 'error', 'message' => 'Appointment not found.']);
        return;
    }

    // Capture old counselor name for logging
    $old_counselor_name = $appointment['councellor'];

    // CHECK: If the counselor name is the same, stop the update to save resources
    if ($old_counselor_name === $counselor_name) {
        echo json_encode([
            'status' => 'info',
            'message' => 'Counselor name is already set to ' . $counselor_name . '. No update needed.'
        ]);
        return;
    }

    // 4. Data array for the update model
    $data_to_update = ['councellor' => $counselor_name];

    // 5. Call the model to run the update query
    $result = $this->appointment_model->update_appointment($appointment_id, $data_to_update);

    // 6. If update successful, run logging and API call
    if ($result) {
        // --- LOGGING ACTION ---
        $log_data = [
            'appointment_id' => $appointment_id,
            'field_changed'  => 'councellor',
            'old_value'      => $old_counselor_name,
            'new_value'      => $counselor_name,
            'updated_by'     => $this->session->userdata('user_id') ?? 'System/Unknown', // Use logged-in user ID or a default
            'created_at'     => date('Y-m-d H:i:s')
        ];
        $this->appointment_model->log_change($log_data); // Insert log record
        // ----------------------

        // Send to external API
        $api_response = $this->send_to_external_api($appointment['crm_id'], $counselor_name);
        
        // Final Success Response
        echo json_encode([
            'status' => 'success', 
            'message' => 'Counselor updated and log recorded.',
            'api_response' => $api_response
        ]);
    } else {
        // Database Error/No Rows Affected handling
        $message = ($this->db->affected_rows() == 0) ? 
                    'Update query ran, but no changes were made.' : 
                    'Database error occurred.';
        echo json_encode(['status' => 'error', 'message' => $message]);
    }
}
// The send_to_external_api function remains the same.

// Function to send data to external API
private function send_to_external_api($crm_id, $coordinator_name) {
    $curl = curl_init();

    $post_data = json_encode([
        "lead_id" => $crm_id,
        "coordinator_name" => $coordinator_name
    ]);

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://flertility.in/appointment/hms-update-coordinator/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($post_data)
        ),
    ));



    $response = curl_exec($curl);
	print_r($response);die();
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($curl);
    
    curl_close($curl);

    return [
        'http_code' => $http_code,
        'response' => $response,
        'curl_error' => $curl_error
    ];
}





} 