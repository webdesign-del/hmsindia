<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends CI_Controller {
	public function __construct()
    {
		// Load parent's constructor.
       	parent::__construct();
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
	    $this->load->library('session');
		$this->load->model(array('api_model', 'billingmodel_model', 'billings_model', 'doctors_model', 'investigation_model', 'procedures_model', 'accounts_model', 
        'stock_model',  'center_model',  'patients_model',  'employee_model',  'appointment_model'));
		$this->load->helper('myhelper');
	}

    public function get_doctors(){
        $data = $doctors = array();
        $doctors = $this->api_model->get_list_doctors();
        if(!empty($doctors)){
            echo json_encode(array('data' => $doctors, 'status' => 1));
            die;
        }else{
            echo json_encode(array('data' => 'No doctor found', 'status' => 0));
            die;
        }
    }
    
    public function get_centres(){
        $data = $centres = array();
        $centres = $this->api_model->get_list_centres();
        if(!empty($centres)){
            echo json_encode(array('data' => $centres, 'status' => 1));
            die;
        }else{
            echo json_encode(array('data' => 'No center found', 'status' => 0));
            die;
        }
    }
    
    public function appointmentbooking(){
        $response = array();
		$phone_number = '';
		$search_this = isset($_POST['search'])?$_POST['search']:"";
		$search_by = isset($_POST['search_by'])?$_POST['search_by']:"";

		if($search_this != ''){
		    $appointments = $this->billingmodel_model->search_appointment($search_this, $search_by);
			if(count($appointments) > 0){
				$response = array('status' => 0, 'message'=> 'Appointment already booked');
				echo json_encode($response);
				die;
			}else{
			    
			    $appoint_arr = array();
			    $patient_id = $patient_status = "";
			    $patient = $this->billingmodel_model->search_patient($search_this, $search_by);

				if(count($patient) > 0){
					$patient_id = $patient['patient_id'];
					$patient_status = "exist_patient";
				}else{
					$patient_id = getGUID();
					$patient_status = "new_patient";
				}
			    $appoint_arr['paitent_id'] = $patient_id;
			    $appoint_arr['paitent_type'] = $patient_status;
			    $appoint_arr['wife_name'] = isset($_POST['name'])?$_POST['name']:"";
			    $appoint_arr['wife_phone'] = isset($_POST['phone'])?$_POST['phone']:"";
			    $appoint_arr['wife_email'] = !empty($_POST['email'])?$_POST['email']:"indiaivfhms@gmail.com";
			    $appoint_arr['nationality'] = isset($_POST['nationality'])?$_POST['nationality']:"indian";
			    $appoint_arr['reason_of_visit'] = isset($_POST['reason_of_visit'])?$_POST['reason_of_visit']:"";
			    $appoint_arr['appoitment_for'] = isset($_POST['appointment_centre'])?$_POST['appointment_centre']:"";
			    $appoint_arr['appoitmented_doctor'] = isset($_POST['appointment_doctor'])?$_POST['appointment_doctor']:"";
			    $appoint_arr['appoitmented_date'] = isset($_POST['appointment_date'])?date("Y-m-d H:i", strtotime($_POST['appointment_date'])):"";
			    $appoint_arr['appoitmented_slot'] = isset($_POST['appointment_slot'])?$_POST['appointment_slot']:"";
			    $appoint_arr['appointment_added'] = date('Y-m-d H:i:s');

    			if($patient_status == "exist_patient" && !empty($patient_id)){
    				$check_patient_medical_info = check_patient_medical_info($patient_id);
    				if($check_patient_medical_info == 1){
    					$appoint_arr['follow_up_appointment'] = 1;
    				}
     			}
     			
     			if(empty($appoint_arr['wife_name']) ||
     			    empty($appoint_arr['wife_phone']) || 
     			    empty($appoint_arr['appoitment_for']) || 
     			    empty($appoint_arr['appoitmented_doctor']) ||
     			    empty($appoint_arr['appoitmented_date']) || 
     			    empty($appoint_arr['appoitmented_slot'])){
         			    $response = array('status' => 0, 'message'=> 'Enter required fields');
            			echo json_encode($response);
            			die;
     			}else{
     			    $appointment = $this->billingmodel_model->insert_appointments($appoint_arr);
			        if($appointment > 0){
     		    	    $response = array('status' => 1, 'message'=> 'Appointment has been booked!');
            			echo json_encode($response);
            			die;
			        }else{
				        $response = array('status' => 0, 'message'=> 'Appointment booking failed');
            			echo json_encode($response);
            			die;
			        }
     			}
			}
		}else{
		    $response = array('status' => 0, 'message'=> 'Phone number/IIC ID is required');
			echo json_encode($response);
			die;
		}
    }
    
    public function advisories(){
         $response = array();
		$phone_number = '';
		$search_this = isset($_POST['search'])?$_POST['search']:"";
		$search_by = isset($_POST['search_by'])?$_POST['search_by']:"";

		if($search_this != ''){
		        $appoint_arr = array();
			    $patient_id = $patient_status = "";
			    $patient = $this->billingmodel_model->search_patient($search_this, $search_by);

				if(count($patient) > 0){
				    if(empty($_POST['phone'])|| 
     			        !isset($_FILES['advisories']['name'])){
				        $response = array('status' => 0, 'message'=> 'Enter required fields');
            			echo json_encode($response);
            			die;
				    }else{
    				    $response = array('status' => 1, 'phone'=> $_POST['phone'], 'advisories' => $_FILES['advisories']['name']);
            			echo json_encode($response);
            			die;
				    }
				}else{
					$response = array('status' => 0, 'message'=> 'patient not found!');
        			echo json_encode($response);
        			die;
				}
		}else{
		    $response = array('status' => 0, 'message'=> 'Phone number/IIC ID is required');
			echo json_encode($response);
			die;
		}
    }
	
	public function myappointment_leadsqure(){
		$response = array();
		$data = json_decode(file_get_contents('php://input'), true);
		$email = $data['EmailAddress'];
		$note = $data['ActivityNote'];
		$first_name = $data['FirstName'];
		$last_name = $data['LastName'];
		$mobile = $data['Phone'];
		$appointment_time = $data['ActivityDateTime'];
		$extra_fields = $data['Fields'];
		
		if(!empty($first_name) && !empty($last_name) && !empty($mobile)){
			
			$appointments = $this->billingmodel_model->search_appointment($mobile, "phone_number");
			if(count($appointments) > 0){
				$response = array('status' => 0, 'message'=> 'Appointment already booked');
				echo json_encode($response);
				die;
			}else{
				$appoint_arr = array();
			    $patient_id = $patient_status = "";
			    $patient = $this->billingmodel_model->search_patient($mobile, "phone_number");
				if(count($patient) > 0){
					$patient_id = $patient['patient_id'];
					$patient_status = "exist_patient";
				}else{
					$patient_id = getGUID();
					$patient_status = "new_patient";
				}
				//save code in database
				$appoint_arr = array();
				$appoint_arr['paitent_id'] = $patient_id;
				$appoint_arr['paitent_type'] = $patient_status;
				$appoint_arr['wife_name'] = $first_name. ' '. $last_name;
				$appoint_arr['wife_phone'] = $mobile;
				$appoint_arr['wife_email'] = !empty($email)? $email: "indiaivfhms@gmail.com";
				$appoint_arr['nationality'] = "indian";
				$appoint_arr['reason_of_visit'] = $note;
				$appointment_for = "";
				$appoitmented_doctor = "";
				$appoitmented_date = "";
				$appoitmented_slot = "";
				foreach ($extra_fields as $row){
					if($row['SchemaName'] == "mx_Centre_Location"){
						$appointment_for = $row['Value']; 
					}
					if($row['SchemaName'] == "mx_Doctor"){
						$appoitmented_doctor = $row['Value']; 
					}
					if($row['SchemaName'] == "mx_appoitmented_date"){
						$appoitmented_date = $row['Value']; 
					}
					if($row['SchemaName'] == "mx_appoitmented_slot"){
						$appoitmented_slot = $row['Value']; 
					}
				}
				$appoint_arr['appoitment_for'] = $appointment_for;
				$appoint_arr['appoitmented_doctor'] = $appoitmented_doctor;
				$appoint_arr['appoitmented_date'] = $appoitmented_date;
				$appoint_arr['appoitmented_slot'] = $appoitmented_slot;
				$appoint_arr['appointment_added'] = date('Y-m-d H:i:s');
				$appointment = $this->billingmodel_model->insert_appointments($appoint_arr);
				if($appointment > 0){
					$response = array('status' => 1, 'message'=> 'Appointment has been booked!');
					echo json_encode($response);
					die;
				}else{
					$response = array('status' => 0, 'message'=> 'Appointment booking failed');
					echo json_encode($response);
					die;
				}
			}
			
		}else{
			$response = array('status' => 0, 'message'=> 'Please enter valid data.');
		}
		echo json_encode($response);
		die;
    }
}
