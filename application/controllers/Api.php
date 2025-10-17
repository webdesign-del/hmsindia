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
			    $appoint_arr['wife_email'] = !empty($_POST['email'])?$_POST['email']:"";
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
		$crm_id = $data['LeadID'];
		$first_name = $data['FirstName'];
		$husband_name = $data['HusbandName'];
		$mobile = str_replace("+91-", "", $data['Phone']);
		$appointment_time = $data['ActivityDateTime'];
		$extra_fields = $data['Fields'];
		$lead_source = $data['lead_source'];
		$camp_selection = $data['mx_Camp_Location'];
		$agent = $data['agent'];
		$councellor = $data['councellor'];
		
		if(!empty($first_name) && !empty($mobile)){
			
			$appointments = $this->billingmodel_model->search_appointment($mobile, "phone_number");
			if(count($appointments) > 0){
				$response = array('status' => 0, 'message'=> 'Appointment already booked');
				echo json_encode($response);
				die;
			}else{
				$appoint_arr = array();
			    $patient_id = $patient_status = "";
				$patient = $this->billingmodel_model->search_patient($mobile, "phone_number");
			    // $patient = $this->billingmodel_model->([$mobile, "phone_number"]);
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
				$appoint_arr['wife_name'] = $first_name;
				$appoint_arr['husband_name'] = $husband_name;
				$appoint_arr['wife_phone'] = $mobile;
				$appoint_arr['wife_email'] = !empty($email)? $email: "";
				$appoint_arr['nationality'] = "indian";
				$appoint_arr['crm_id'] = $crm_id;
				$appoint_arr['reason_of_visit'] = $note;
				$appoint_arr['lead_source'] = $lead_source;
				$appoint_arr['agent'] = $agent;
				$appoint_arr['councellor'] = $councellor;
				$appointment_for = "";
				$appoitmented_doctor = "";
				$appoitmented_date = "";
				$appoitmented_slot = "";
				foreach ($extra_fields as $row){
					if($row['SchemaName'] == "mx_Centre_Location"){
						$appointment_for = $row['Value']; 
					}
					if($row['SchemaName'] == "mx_Camp_Location"){
						$camp_selection = $row['Value']; 
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
				$centerArr = array(
					"101"=>"16249589462327",
					"102"=>"16266778858144",
					"103"=>"16267558222750",
					"104"=>"16098223739590",
					"105"=>"16133769531637",
					"106"=>"16133769691598",
					"107"=>"1650433762851",
					"108"=>"16504338296792",
					"109"=>"16376560997772",
					"110"=>"16133769903168",
					"111"=>"16133770059184",
					"112"=>"16133772549576",
					"113"=>"16134712848837",
					"114"=>"16298890995787",
					"115"=>"16474253421459",
					"116"=>"1581156221",
					"117"=>"1581156245",
					"118"=>"1581156261",
					"119"=>"1581157290",
					"120"=>"1593015013",
				);
				if(!empty($appointment_for)){
					foreach ($centerArr as $key => $val){
						if($appointment_for == $key){
							$appointment_for = $val;
							break;
						}
					}
				}
				$appoint_arr['appoitment_for'] = $appointment_for;
				$appoint_arr['camp_selection'] = $camp_selection;
				$appoint_arr['appoitmented_doctor'] = $appoitmented_doctor;
				$appoint_arr['appoitmented_date'] = $appoitmented_date;
				$appoint_arr['appoitmented_slot'] = $appoitmented_slot;
				$appoint_arr['appointment_added'] = date('Y-m-d H:i:s');
				$appointment = $this->billingmodel_model->insert_appointments($appoint_arr);

				
				/*$data = "\n".date("d-m-y H:i:s")."-------------".json_encode($appointment)."\n";
                        $fp = fopen('app_data.txt', 'a');
                        fwrite($fp, json_encode($data));
                        fclose($fp);*/
				
					if($appointment > 0){
						if (!empty($camp_selection) && $camp_selection > 0) {
							$centre_details = get_camp_centre_details($camp_selection);
							$appointwhatmsg = array();
							$appointwhatmsg = array($appoint_arr['wife_name'], $centre_details['camp_name'], date("d-m-Y", strtotime($appoint_arr['appoitmented_date'])), $appoint_arr['appoitmented_slot'], isset($centre_details['location'])?$centre_details['location']:"");
							$appointsendmsg = whatsappappointment(
							$appoint_arr['wife_phone'], 
							$appoint_arr['wife_name'],
							$centre_details['camp_name'],
							date("d-m-Y", strtotime($appoint_arr['appoitmented_date'])),
							$appoint_arr['appoitmented_slot'],
							isset($centre_details['location'])?$centre_details['location']:"",
						"appointment_confirmation");
						
						$response = array('status' => 1, 'message'=> 'Appointment has been booked!');
						echo json_encode($response);
						die;
						} else {
							// Camp selection 0 hai, normal centre lena hai
							$centre_details = get_centre_details($appointment_for);
							$appointwhatmsg = array();
							$appointwhatmsg = array($appoint_arr['wife_name'], $centre_details['center_name'], date("d-m-Y", strtotime($appoint_arr['appoitmented_date'])), $appoint_arr['appoitmented_slot'], isset($centre_details['center_location'])?$centre_details['center_location']:"");
							$appointsendmsg = whatsappappointment(
							$appoint_arr['wife_phone'], 
							$appoint_arr['wife_name'],
							$centre_details['center_name'],
							date("d-m-Y", strtotime($appoint_arr['appoitmented_date'])),
							$appoint_arr['appoitmented_slot'],
							isset($centre_details['center_location'])?$centre_details['center_location']:"",
						"appointment_confirmation");
						
						$response = array('status' => 1, 'message'=> 'Appointment has been booked!');
						echo json_encode($response);
						die;
						}
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
	
	private $auth_token = "312e1bf40ab1b75601b6f1a7a57f589c";

	private function authenticate() {
        $headers = $this->input->request_headers();
        if (isset($headers['auth-token']) && $headers['auth-token'] === $this->auth_token) {
            return true;
        }
        return false;
    }
	
	public function appointment_status_update(){

    	if (!$this->authenticate()) {
            echo json_encode([
                'status' => false,
                'message' => 'Unauthorized access.'
            ]);
            http_response_code(401);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['wife_phone'], $input['center'], $input['new_status'])) {
            $wife_phone = $input['wife_phone'];
            $center = $input['center'];
            $new_status = $input['new_status'];
            $appointed_date = isset($input['appointed_date']) ? $input['appointed_date'] : date('Y-m-d H:i:s'); // Use current date if not provided

            // Call the model function to update status and appointed_date
            $result = $this->appointment_model->updateStatusAndDate($wife_phone, $center, $new_status, $appointed_date);

            if ($result) {
                $response = [
                    'status' => true,
                    'message' => 'Status and appointed date updated successfully.',
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => 'No record found or update failed.',
                ];
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'Invalid input data.',
            ];
        }

        echo json_encode($response);

    }
	
	public function appointment_status_crm_id() {
		$input = json_decode(file_get_contents("php://input"), true);

		// Validate input
		if (empty($input['crm_id']) || empty($input['wife_phone'])) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Missing crm_id or wife_phone'
			]);
			exit;
		}
		// Sanitize inputs
		$crm_id = htmlspecialchars(trim($input['crm_id']));
		$wife_phone = htmlspecialchars(trim($input['wife_phone']));

		// Run the update logic
		$status = $this->api_model->appointment_status_crm_id($crm_id, $wife_phone);

		if ($status > 0) {
			$response = [
				'status' => 'crm_id_updated',
				'message'=> 'CRM ID Updated'
			];
		} else {
			$response = [
				'status' => 'status_same',
				'message'=> 'No update performed'
			];
		}

		echo json_encode($response);
		exit;
	}

public function crm_lead() {
    header('Content-Type: application/json');
    try {
        $json_input = file_get_contents("php://input");
        if (empty($json_input)) throw new Exception('No input data received');

        $lead_data = json_decode($json_input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON format');
        }

        // Rest of your method remains the same...
        if (empty($lead_data['lead_id'])) {
            $lead_data['lead_id'] = 'lead_' . bin2hex(random_bytes(8));
        }

        $status = $this->api_model->crm_lead($lead_data);

        $response = [
            'status' => ($status > 0) ? 'success' : 'no_change',
            'message' => ($status > 0) ? 'Lead processed' : 'No changes made',
            'lead_id' => $lead_data['lead_id']
        ];

        http_response_code(($status > 0) ? 201 : 200);
        
    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => $e->getMessage(),
            'error_code' => 400
        ];
        http_response_code(400);
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}



public function patient_journey() {
	
	
$investigation_sql = "SELECT * FROM " . $this->config->item('db_prefix') . "patient_journey6";
$investigation_q = $this->db->query($investigation_sql);
$investigation_result = $investigation_q->result_array();

foreach ($investigation_result as $ky => $vl) {
    // Process each $vl here
    // For example:
   // echo "Patient Name: " . $vl['patients_name'] . "\n";
	
	
	$payload = [
        'visit_month'=> $vl['visit_month'],
        'first_visit_date'=> $vl['first_visit_date'],
        'booking_month'=> $vl['booking_month'],
        'booking_date'=> $vl['booking_date'],
        'ch_fc_name'=> $vl['ch_fc_name'],
        'doctor_consulted'=> $vl['doctor_consulted'],
        'patient_id'=> $vl['patient_id'],
        'patients_name'=> $vl['patients_name'],
        'centre_booking'=> $vl['centre_booking'],
        'centre_procedure'=> $vl['centre_procedure'],
        'patients_source'=> $vl['patients_source'],
        'patient_type'=> $vl['patient_type'],
        'procedure_type'=> $vl['procedure_type_name'],
        'status_of_booking'=> $vl['status_of_booking'],
        'procedure_type_name' => $vl['sub_procedure_1'] . ', ' . (new DateTime($vl['booking_date']))->format('Y-m-d'),
		'procedure_code'=> $vl['code'],
        'package_amount'=> $vl['package_amount'],
        'discount_amount'=> $vl['discount_amount'],
        'package_after_discount'=> $vl['package_after_discount'],
        'payment_received'=> $vl['payment_received'],
        'balance_amount'=> $vl['balance_amount'],
        'status'=> $vl['status'],
        'comment'=> $vl['comment'],
        'follow_up'=> $vl['follow_up'],
        'std_withdrawal_date'=> $vl['std_withdrawal_date'],
        'actual_withdrawal_date'=> $vl['actual_withdrawal_date'],
        'withdrawl_status'=> $vl['withdrawl_status'],
        'std_stimulation_date'=> $vl['std_stimulation_date'],
        'actual_stimulation_start_date'=> $vl['actual_stimulation_start_date'],
        'stimulation_start_status'=> $vl['stimulation_start_status'],
        'amount_stimulation_date'=> $vl['amount_stimulation_date'],
        'std_trigger_date'=> $vl['std_trigger_date'],
        'actual_trigger_date'=> $vl['actual_trigger_date'],
        'amount_billing_date'=> $vl['amount_billing_date'],
        'trigger_status'=> $vl['trigger_status'],
        'std_opu_date'=> $vl['std_opu_date'],
        'actual_opu_date'=> $vl['actual_opu_date'],
        'opu_amount_billing_date'=> $vl['opu_amount_billing_date'],
        'opu_status'=> $vl['opu_status'],
        'emb_transfer_date'=> $vl['transfer_date'],
        'cardiac_activity_no'=> $vl['cardiac_activity_no2'],
        'cardiac_activity_date'=> $vl['cardiac_activity_date'],
        'no_of_gestational'=> $vl['no_of_gestational'],
        'lead_id' => $vl['crm_id']
    ];
	
	// echo json_encode($payload); exit();

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://staging.flertility.in/lead/lead-journey/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
        ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    echo "Response: $response\n\n";
	
}

return $investigation_result;
} 



public function patient_cons() {
	
	
$investigation_sql = "SELECT * FROM " . $this->config->item('db_prefix') . "cons";
$investigation_q = $this->db->query($investigation_sql);
$con_result = $investigation_q->result_array();


foreach ($con_result as $ky => $vl) {
    // Process each $vl here
    // For example:
   // echo "Patient Name: " . $vl['patients_name'] . "\n";
   
   
/*   $input = '31-05-2025 04:57:08';
$date = DateTime::createFromFormat('d-m-Y H:i:s', $vl['created_at'], new DateTimeZone('Asia/Kolkata'));
$formatted = $date->format('Y-m-d\TH:i:s.uP'); // ISO 8601 with microseconds and timezone*/

//$vl['created_at'] = '2025-07-12 10:12:08';

$date = DateTime::createFromFormat('Y-m-d H:i:s', $vl['created_at'], new DateTimeZone('Asia/Kolkata'));

    $formatted = $date->format('Y-m-d\TH:i:s.uP'); // ISO 8601 with microseconds and timezone
    echo $formatted;
	
	
	$payload = [
        'created_at'=> $formatted,
        'updated_at'=> $formatted,
        'doctor'=> $vl['doctor'],
        'wife_phone'=> $vl['wife_phone'],
        'patient_id'=> $vl['patient_id'],
        'appointment_id'=> $vl['appointment_id'],
		'ch_fc_name'=> $vl['counsellor_signature'],
        'female_investigation_suggestion_list'=> $vl['female_investigation_suggestion_list'],
        'male_investigation_suggestion_list'=> $vl['male_investigation_suggestion_list'],
        'package_suggestion_list'=> $vl['package_suggestion_list'],
        'sub_procedure_suggestion_list'=> $vl['sub_procedure_suggestion_list'],
        'female_medicine_suggestion_list'=> $vl['female_medicine_suggestion_list'],
        'male_medicine_suggestion_list'=> $vl['male_medicine_suggestion_list'],
        'lead_id' => $vl['crm_id']
    ];
	
	// echo json_encode($payload); exit();

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://staging.flertility.in/lead/consultations/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
        ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    echo "Response: $response\n\n";
	
}

return $con_result;
} 



public function embryo_freezing() {
		
$investigation_sql = "SELECT * FROM " . $this->config->item('db_prefix') . "embryo_freezing";
$investigation_q = $this->db->query($investigation_sql);
$investigation_result = $investigation_q->result_array();

foreach ($investigation_result as $ky => $vl) {
    
$select_query = "SELECT * FROM `hms_appointments` WHERE paitent_id='" .$vl['patient_id']."'";
$select_result = run_select_query($select_query);  

$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result['wife_phone']."' and paitent_type='new_patient'";
$select_result4 = run_select_query($sql4);
	
$select_query_pro = "SELECT * FROM `hms_patient_procedure` WHERE receipt_number='" .$vl['receipt_number']."'";
$select_result_pro = run_select_query($select_query_pro); 

$select_query_center = "SELECT * FROM `hms_centers` WHERE center_number='" .$select_result_pro['billing_at']."'";
$select_center = run_select_query($select_query_center); 

$select_center_sql = "SELECT * FROM `hms_centers` WHERE center_number='" .$select_result4['appoitment_for']."'";
$result_center = run_select_query($select_center_sql);

$select_procedure = "SELECT * FROM `hms_procedures` WHERE ID='" .$vl['procedure_id']."'";
$result_pro = run_select_query($select_procedure); 
	
	$payload = json_encode([
			"patient_id" => $vl['patient_id'],
			"receipt_number" => $vl['receipt_number'],
			"wife_name" => $select_result['wife_name'],
			"payment_freezing" => $select_result_pro['fees'],
			"spoke" => $select_center['center_name'],
			"hub" => $result_center['center_name'],
			"date" => $vl['date'],
			"no_of_straw_0" => $vl['no_of_embryos_frozen'],
			"procedure_id" => $result_pro['code'],
			"lead_id" => $vl['crm_id']
	]);
	
	// echo json_encode($payload); exit();

    $curl = curl_init();
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://staging.flertility.in/lead/embryo-freezing/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));
    $response = curl_exec($curl);
    curl_close($curl);

    echo "Response: $response\n\n";
	
}

return $investigation_result;
}


public function lead_update() {
    $investigation_sql = "SELECT ID, crm_id, paitent_id FROM hms_appointments WHERE crm_id > 0 ORDER BY ID DESC";
	$investigation_q = $this->db->query($investigation_sql);
    $investigation_lead = $investigation_q->result_array();

    foreach ($investigation_lead as $vl) {
        // Validate CRM ID before using it in the URL
        if (empty($vl['crm_id']) || empty($vl['paitent_id'])) {
            continue;
        }
		
		$select_query = "SELECT * FROM `hms_patient_medical_info` WHERE patient_id='" .$vl['paitent_id']."'";
		$select_result = run_select_query($select_query); 

        $payload = json_encode([
            "hms_patient_id" => $vl['paitent_id'],
			"primary_occupation" => $select_result['female_occupation'],
			"secondary_occupation" => $select_result['male_occupation'],
			"primary_education" => $select_result['female_education'],
			"secondary_education" => $select_result['male_education'],
			"primary_name_gender" => "female"
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://flertility.in/lead/lead-update/' . $vl['crm_id'] . '/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        echo "Sending to CRM ID: {$vl['crm_id']} | Payload: " . $payload . "\n\n";
    }

    return $investigation_lead;
}


public function semen_freezing() {
		
$investigation_sql = "SELECT * FROM semen_freezing";
$investigation_q = $this->db->query($investigation_sql);
$investigation_result = $investigation_q->result_array();

foreach ($investigation_result as $ky => $vl) {
    
$select_query = "SELECT * FROM `hms_appointments` WHERE paitent_id='" .$vl['patient_id']."'";
$select_result = run_select_query($select_query);  

$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result['wife_phone']."' and paitent_type='new_patient'";
$select_result4 = run_select_query($sql4); 

$select_query_pro = "SELECT * FROM `hms_patient_procedure` WHERE receipt_number='" .$vl['receipt_number']."'";
$select_result_pro = run_select_query($select_query_pro); 

$select_query_center = "SELECT * FROM `hms_centers` WHERE center_number='" .$select_result_pro['billing_at']."'";
$select_center = run_select_query($select_query_center); 

$select_center_sql = "SELECT * FROM `hms_centers` WHERE center_number='" .$select_result4['appoitment_for']."'";
$result_center = run_select_query($select_center_sql);

$select_procedure = "SELECT * FROM `hms_procedures` WHERE ID='" .$vl['procedure_id']."'";
$result_pro = run_select_query($select_procedure); 

$dateOnly = date('Y-m-d', strtotime($vl['date1']));

if ($select_result4['crm_id'] !== "0") {
    $lead = $select_result4['crm_id'];
}


	
	$payload = json_encode([
			"patient_id" => $vl['patient_id'],
			"receipt_number" => $vl['receipt_number'],
			"wife_name" => $select_result['wife_name'],
			"payment_freezing" => $vl['after_discount'],
			"spoke" => $select_center['center_name'],
			"hub" => $result_center['center_name'],
			"date" => $dateOnly,
			"no_0" => $vl['frozen'],
			"procedure_id" => $vl['procedure_code'],
			"witness2" => $vl['procedure_name'],
			"lead" => $lead
			
	]);
	
	 //echo json_encode($payload); exit();

    $curl = curl_init();
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://staging.flertility.in/lead/semen-freezing/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));
    $response = curl_exec($curl);
    curl_close($curl);

    echo "Response: $response\n\n";
	
}

return $investigation_result;
}

public function egg_freezing() {
		
$investigation_sql = "SELECT * FROM egg_freezing";
$investigation_q = $this->db->query($investigation_sql);
$investigation_result = $investigation_q->result_array();

foreach ($investigation_result as $ky => $vl) {
    
$select_query = "SELECT * FROM `hms_appointments` WHERE paitent_id='" .$vl['patient_id']."'";
$select_result = run_select_query($select_query);  

$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result['wife_phone']."' and paitent_type='new_patient'";
$select_result4 = run_select_query($sql4); 

$select_query_pro = "SELECT * FROM `hms_patient_procedure` WHERE receipt_number='" .$vl['receipt_number']."'";
$select_result_pro = run_select_query($select_query_pro); 

$select_query_center = "SELECT * FROM `hms_centers` WHERE center_number='" .$select_result_pro['billing_at']."'";
$select_center = run_select_query($select_query_center); 

$select_center_sql = "SELECT * FROM `hms_centers` WHERE center_number='" .$select_result4['appoitment_for']."'";
$result_center = run_select_query($select_center_sql);

$select_procedure = "SELECT * FROM `hms_procedures` WHERE ID='" .$vl['procedure_id']."'";
$result_pro = run_select_query($select_procedure); 

$dateOnly = date('Y-m-d', strtotime($vl['date1']));

if ($select_result4['crm_id'] !== "0") {
    $lead = $select_result4['crm_id'];
}

	
	$payload = json_encode([
			"patient_id" => $vl['patient_id'],
			"receipt_number" => $vl['receipt_number'],
			"wife_name" => $select_result['wife_name'],
			"payment_freezing" => $vl['after_discount'],
			"spoke" => $select_center['center_name'],
			"hub" => $result_center['center_name'],
			"date1" => $dateOnly,
			"no_of_straw1" => "",
			"procedure_id" => $vl['procedure_code'],
			"witness2" => $vl['procedure_name'],
			"lead" => $lead
			
	]);
	
	 //echo json_encode($payload); exit();

    $curl = curl_init();
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://staging.flertility.in/lead/oocyte-freezing/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));
    $response = curl_exec($curl);
    curl_close($curl);

    echo "Response: $response\n\n";
	
}

return $investigation_result;
}
}