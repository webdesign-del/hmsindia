<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
	{
		// Load parent's constructor.
       	parent::__construct();
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
	    $this->load->library('session');
		$this->load->model(array('user_model', 'patients_model', 'doctors_model'));
		$this->load->helper('myhelper');
		
		// if (session_status() == PHP_SESSION_NONE) {
		// 	session_start();
		// }
	}	
	
	public function index()
	{
		$logg = checklogin();	
		if($logg['status'] == true){
			// Debug: Log successful checklogin
			log_message('info', 'User already logged in, redirecting to dashboard');
			header("location:" .base_url(). "dashboard");
			die;
		}else{
			if(isset($_POST['login']) && !empty($_POST['login']) && $_POST['login'] == 'login'){
				unset($_POST['login']);
				$logged = $this->user_model->userlogin($_POST);
				if($logged['status'] == 1){
					// Debug: Log successful login
					log_message('info', 'Login successful for user: ' . $_POST['email']);
					// Check session after login
					$logg_after = checklogin();
					log_message('info', 'checklogin after login: ' . json_encode($logg_after));
					header("location:" .base_url(). "dashboard");
					die();
				}else{
					// Debug: Log failed login
					log_message('info', 'Login failed for user: ' . $_POST['email']);
					header("location:" .base_url(). "");
					die();
				}
			}else{
				$this->load->view('templates/header');
				$this->load->view('welcome');
				$this->load->view('templates/footer');
			}
		}
	}
	
	public function dashboard(){

		$logg = checklogin();
		if($logg['status'] == true){
			log_message('info', 'Dashboard access granted for role: ' . $logg['role']);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view($template['dashboard']);
			$this->load->view($template['footer']);
		}else{
			// Debug: Log why dashboard access was denied
			log_message('info', 'Dashboard access denied - redirecting to login. Session status: ' . session_status());
			log_message('info', 'Available sessions: ' . json_encode($_SESSION));
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function password()
    {
        $logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'change_password'){
				unset($_POST['action']);
				$password = md5($_POST['password']);
				$username = $_POST['username'];
				$update_password = $this->user_model->change_password($username, $password);
				if($update_password > 0){
					header("location:" .base_url(). "password?m=".base64_encode('Password changed successfully!').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "password?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			
			$data = array();
			$data['username'] = $_SESSION['logged_administrator']['username'];
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('password', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
    }
    
	public function logout(){
		unset($_SESSION['logged_administrator']);
		unset($_SESSION['logged_accountant']);
		unset($_SESSION['logged_stock_manager']);
		unset($_SESSION['logged_billing_manager']);
		unset($_SESSION['logged_central_stock_manager']);
		unset($_SESSION['logged_doctor']);
		unset($_SESSION['logged_telecaller']);
		unset($_SESSION['logged_investigation_manager']);
		unset($_SESSION['logged_embryologist']);
		unset($_SESSION['logged_counselor']);
		unset($_SESSION['logged_center_head']);
		unset($_SESSION['logged_liason']);
		unset($_SESSION['logged_mrd']);
		unset($_SESSION['logged_viewer']);
		header("location:" .base_url(). "");
		die();
	}
	
	public function not_found()
	{
		die('Page not found');
	}

	public function settings(){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_setting'){
				unset($_POST['action']);
				$conversion_rate = $_POST['conversion_rate'];
				$update_setting = $this->user_model->update_setting_data($conversion_rate);	
				//var_dump($update_setting);die;
				if($update_setting > 0){
					header("location:" .base_url(). "settings?m=".base64_encode('Settings updated successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "settings?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}

			}
			$data = array();
			$data['data'] = $this->user_model->setting_data();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('settings', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}


	public function patient_profile(){
		if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'profile_data'){
			unset($_POST['action']);unset($_POST['paitent_type']);unset($_POST['phone_number']);unset($_POST['wife_phone']);

			$paitent_id = $_POST['paitent_id'];unset($_POST['paitent_id']);
			//wife pan
			$wife_pan_card = '';
			if(!empty($_FILES['wife_pan_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $_FILES['wife_pan_card']['name'];
				$wife_pan_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($_FILES['wife_pan_card']['tmp_name'], $destination.$NewImageName);
				$_POST['wife_pan_card'] = $wife_pan_card;
			}
			//wife adhar
			$wife_adhar_card = '';
			if(!empty($_FILES['wife_adhar_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $_FILES['wife_adhar_card']['name'];
				$wife_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($_FILES['wife_adhar_card']['tmp_name'], $destination.$NewImageName);
				$_POST['wife_adhar_card'] = $wife_adhar_card;
			}
			//wife back adhar
			$wife_back_adhar_card = '';
			if(!empty($_FILES['wife_back_adhar_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $_FILES['wife_back_adhar_card']['name'];
				$wife_back_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($_FILES['wife_back_adhar_card']['tmp_name'], $destination.$NewImageName);
				$_POST['wife_back_adhar_card'] = $wife_back_adhar_card;
			}
			//wife photo
			$wife_photo = '';
			if(!empty($_FILES['wife_photo']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-wife-". $_FILES['wife_photo']['name'];
				$wife_photo = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($_FILES['wife_photo']['tmp_name'], $destination.$NewImageName);
				$_POST['wife_photo'] = $wife_photo;
			}
			/******************* WIFE DETAILS *********************/
			
			/******************* HUSBAND DETAILS *********************/
			//husband pan
			$husband_pan_card = '';
			if(!empty($_FILES['husband_pan_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $_FILES['husband_pan_card']['name'];
				$husband_pan_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($_FILES['husband_pan_card']['tmp_name'], $destination.$NewImageName);
				$_POST['husband_pan_card'] = $husband_pan_card;
			}
			//husband adhar
			$husband_adhar_card = '';
			if(!empty($_FILES['husband_adhar_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $_FILES['husband_adhar_card']['name'];
				$husband_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($_FILES['husband_adhar_card']['tmp_name'], $destination.$NewImageName);
				$_POST['husband_adhar_card'] = $husband_adhar_card;
			}
			//husband back adhar
			$husband_back_adhar_card = '';
			if(!empty($_FILES['husband_back_adhar_card']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $_FILES['husband_back_adhar_card']['name'];
				$husband_back_adhar_card = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($_FILES['husband_back_adhar_card']['tmp_name'], $destination.$NewImageName);
				$_POST['husband_back_adhar_card'] = $husband_back_adhar_card;
			}
			//husband photo
			$husband_photo = '';
			if(!empty($_FILES['husband_photo']['tmp_name'])){
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path.'patient_files/';
				$NewImageName = rand(4,10000)."-".$paitent_id."-husband-". $_FILES['husband_photo']['name'];
				$husband_photo = base_url().'assets/patient_files/'.$NewImageName;
				move_uploaded_file($_FILES['husband_photo']['tmp_name'], $destination.$NewImageName);
				$_POST['husband_photo'] = $husband_photo;
			}
			/******************* HUSBAND DETAILS *********************/
			$_POST['modified_on'] = date("Y-m-d H:i:s");
			
			// var_dump($_POST); echo '<br/><br/><br/><br/><br/>';
			// var_dump($_FILES);die;
			$update_patient_profile = $this->patients_model->update_patient_profile($_POST, $paitent_id);
			if($update_patient_profile > 0){
				header("location:" .base_url(). "patient-profile?m=".base64_encode('Profile updated successfully !').'&t='.base64_encode('success'));
				die();
			}else{
				header("location:" .base_url(). "patient-profile?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
				die();
			}

		}
		$this->load->view('templates/header');
		$this->load->view('patients/patient_profile');
		$this->load->view('templates/footer');
	}
	
	/********** Filter Data **********/
	
	public function week_month_filter(){
		$filter_value = $_POST['filter_value'];
		$filter_type = $_POST['filter_type'];
		$logged = $this->user_model->get_week_month_data($filter_value, $filter_type);
		
		var_dump($_POST);die;
	}

	/********** Filter Data **********/

	public function doctor_appointments(){
		$data = array();
		$data['appointments'] = array();
		if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'wl_doctor_appointments'){
			unset($_POST['action']);
			if(isset($_POST['doctor_username']) && !empty($_POST['doctor_username'])){
				$appointment_date = $_POST['appointment_date'];
				$doctor_data = $this->doctors_model->doctor_data_by_username($_POST['doctor_username']);
				if(!empty($doctor_data)){
					$data['appointments'] = $this->doctors_model->wl_doctor_appointment_lists($doctor_data->ID, $appointment_date);
				}else{
					header("location:" .base_url(). "doctor-appointments?m=".base64_encode('Doctor not found!').'&t='.base64_encode('error'));
					die();
				}
			}else{
				header("location:" .base_url(). "doctor-appointments?m=".base64_encode('Enter valid Doctor Username!').'&t='.base64_encode('error'));
				die();
			}
			//var_dump($data['appointments']);die;
		}
		$this->load->view('templates/header');
		$this->load->view('doctors/wl_doctor_appointments', $data);
		$this->load->view('templates/footer');
	}
}
