<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctors extends CI_Controller {

	public function __construct()
	{
		// Load parent's constructor.
       	parent::__construct();
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
	    $this->load->library('session');
		$this->load->model(array('doctors_model', 'patients_model', 'center_model', 'employee_model', 'appointment_model', 'billingmodel_model', 'investigation_model', 'procedures_model', 'stock_model','accounts_model'));
		$this->load->helper('myhelper');
		$this->load->library("pagination");
	}	
	
	public function doctors()
	{
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$data['data'] = $this->doctors_model->get_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/doctors', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}


	public function junior_doctors(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/junior_doctors', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function ipd_admission_form(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/ipd_admission_form', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function pcp_ndt_update(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/pcp_ndt_update', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function ot_consent_form(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/ot_consent_form', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
		
	public function anaethesia_consent_form(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/anaethesia_consent_form', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_form(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_form', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function intrauterine_insemination(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/intrauterine_insemination', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function form8(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/form8', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function form8_single_woman(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/form8_single_woman', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
	public function form9(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/form9', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function form10(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/form10', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
		
	public function form11(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/form11', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

    public function form12(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/form12', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

    public function consent_for_embryo_transfer(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_embryo_transfer', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	

	public function form13(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/form13', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

    public function form15(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/form15', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function cfpros(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/cfpros', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	

	public function form18(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/form18', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
		
	public function risk_consent(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/risk_consent', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
		
	public function couple_donor_egg(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/couple_donor_egg', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

    public function consent_for_semen_collection(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_semen_collection', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

    public function micro_tese(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/micro_tese', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

    public function ovarian_platelet_rich_plasma(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/ovarian_platelet_rich_plasma', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

    public function uterine_platelet_rich_plasma(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/uterine_platelet_rich_plasma', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}		
		
	public function testicular_platelet_rich_plasma(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/testicular_platelet_rich_plasma', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	

	public function patient_testimonial(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/patient_testimonial', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

    public function low_ovarian_reserve_females(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/low_ovarian_reserve_females', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}		
		
	public function divorce_ewidow(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/divorce_ewidow', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	

	// New ED Affidavit and OD Affidavit
	public function donor_sperm_affidavit(){
		$logg = checklogin();		
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();		
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/donor_sperm_affidavit', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();	
		}
	}

	public function new_ed_affidavit(){
		$logg = checklogin();	
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/new_ed_affidavit', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	public function od_affidavit(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/od_affidavit', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
    public function agreement_for_surrogacy(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/agreement_for_surrogacy', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

    public function couple_for_availing_surrogacy(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/couple_for_availing_surrogacy', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	

    public function fitness_of_surrogate_mother(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/fitness_of_surrogate_mother', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	

    public function consent_form_for_withdrawal(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_form_for_withdrawal', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
        
    public function screening_of_the_surrogate(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/screening_of_the_surrogate', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	

    public function acknowledgment(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_junior_doctors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/acknowledgment', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
		
	public function informed_consent_for_psychological_counselling(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/informed_consent_for_psychological_counselling', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}		

	public function freezingmo(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_freezing();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/freezingmo', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function discard(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_freezing();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/discard', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function update_freezingmo(){
		
		$data = json_decode(file_get_contents('php://input'), true);
		$id = $data["id"];
		$first_intimation_date = $data["first_intimation_date"];
		$mode = $data["mode"];
		$second_intimation_date = $data["second_intimation_date"];
		$mode2 = $data["mode2"];
		$cause_for_discard = $data["cause_for_discard"];
		$consent_form_signed = $data["consent_form_signed"];
		$status_of_discarded = $data["status_of_discarded"];
		$no_of_straws = $data["no_of_straws"];
		$no_of_embryo = $data["no_of_embryo"];
		$embryo_grade = $data["embryo_grade"];
		$straws_colour = $data["straws_colour"];
		$visotube = $data["visotube"];
		$goblet = $data["goblet"];
		$g_location = $data["g_location"];
		$dewar = $data["dewar"];
		$tank = $data["tank"];
		$freezing_done_by = $data["freezing_done_by"];
		$thawed_On = $data["thawed_On"];
		$thawed_by = $data["thawed_by"];
		$remarks = $data["remarks"];
		$no_ofoocytes_retrieved = $data["no_ofoocytes_retrieved"];
		$discard_embryo = $data["discard_embryo"];
		$remain_embryo = $data["remain_embryo"];
		$discard_date = $data["discard_date"];
		$discard_status = $data["discard_status"];

		$sql = "UPDATE freezing SET first_intimation_date = '$first_intimation_date', mode = '$mode', second_intimation_date = '$second_intimation_date', mode2 = '$mode2', cause_for_discard = '$cause_for_discard', consent_form_signed = '$consent_form_signed', status_of_discarded = '$status_of_discarded', no_of_straws = '$no_of_straws', no_of_embryo = '$no_of_embryo', embryo_grade = '$embryo_grade', straws_colour = '$straws_colour', visotube = '$visotube', goblet = '$goblet', g_location = '$g_location', dewar = '$dewar', tank = '$tank', freezing_done_by = '$freezing_done_by', thawed_On = '$thawed_On', thawed_by = '$thawed_by', remarks = '$remarks', no_ofoocytes_retrieved = '$no_ofoocytes_retrieved', discard_embryo = '$discard_embryo', remain_embryo = '$remain_embryo', discard_date = '$discard_date', discard_status = '$discard_status' where id='$id'";
		$this->db->query($sql);
        return $this->db->affected_rows();

		$response = array("success"=> "success", "message" => "Records has been updated");
		echo json_encode($response);
		exit();
	}

	public function add()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_doctor'){
				unset($_POST['action']);
				//var_dump($_POST);die;
				$username = $_POST['username'];
				$check_doctor = $this->doctors_model->check_doctor($username);
				if($check_doctor > 0){
					//var_dump($check_doctor);die;
					header("location:" .base_url(). "doctors/add?m=".base64_encode('Doctor username already exist!').'&t='.base64_encode('error'));
					die();
				}
				
				$post_arr = $monday_slots = $tuesday_slots = $wednesday_slots = $thursday_slots = $friday_slots = $saturday_slots = $sunday_slots = array();
			    
				//Monday Slots
				if(!isset($_POST['monday_off'])){
					// Monday First Half
					if(!isset($_POST['monday_morning_off'])){
						$monday_morning_start_time = $monday_morning_start_slot = strtotime($_POST['monday_morning_start_time']);
						$monday_morning_end_time = strtotime($_POST['monday_morning_end_time']);
						
						while($monday_morning_start_slot <= $monday_morning_end_time) {
							if($monday_morning_start_slot == $monday_morning_end_time){break;}
							$monday_morning_end_slot = $monday_morning_start_slot+(60*5);
							$slot = date('H:i', $monday_morning_start_slot).'-'.date('H:i', $monday_morning_end_slot);
							$monday_slots[] = $slot;
							$monday_morning_start_slot = $monday_morning_end_slot;
						}
					}

					// Monday Second Half
					if(!isset($_POST['monday_evening_off'])){
						$monday_evening_start_time = $monday_evening_start_slot = strtotime($_POST['monday_evening_start_time']);
						$monday_evening_end_time = strtotime($_POST['monday_evening_end_time']);
						while($monday_evening_start_slot <= $monday_evening_end_time) {
							if($monday_evening_start_slot == $monday_evening_end_time){break;}
							$monday_evening_end_slot = $monday_evening_start_slot+(60*5);
							$slot = date('H:i', $monday_evening_start_slot).'-'.date('H:i', $monday_evening_end_slot);
							$monday_slots[] = $slot;
							$monday_evening_start_slot = $monday_evening_end_slot;
						}
					}
				}	

				//Tuesday Slots
				if(!isset($_POST['tuesday_off'])){
					// Tuesday First Half
					if(!isset($_POST['tuesdy_morning_off'])){
						$tuesday_morning_start_time = $tuesday_morning_start_slot = strtotime($_POST['tuesday_morning_start_time']);
						$tuesday_morning_end_time = strtotime($_POST['tuesday_morning_end_time']);
						while($tuesday_morning_start_slot <= $tuesday_morning_end_time) {
							if($tuesday_morning_start_slot == $tuesday_morning_end_time){break;}
							$tuesday_morning_end_slot = $tuesday_morning_start_slot+(60*5);
							$slot = date('H:i', $tuesday_morning_start_slot).'-'.date('H:i', $tuesday_morning_end_slot);
							$tuesday_slots[] = $slot;
							$tuesday_morning_start_slot = $tuesday_morning_end_slot;
						}
					}
					// Tuesday Second Half
					if(!isset($_POST['tuesdy_evening_off'])){
						$tuesday_evening_start_time = $tuesday_evening_start_slot = strtotime($_POST['tuesday_evening_start_time']);
						$tuesday_evening_end_time = strtotime($_POST['tuesday_evening_end_time']);
						while($tuesday_evening_start_slot <= $tuesday_evening_end_time) {
							if($tuesday_evening_start_slot == $tuesday_evening_end_time){break;}
							$tuesday_evening_end_slot = $tuesday_evening_start_slot+(60*5);
							$slot = date('H:i', $tuesday_evening_start_slot).'-'.date('H:i', $tuesday_evening_end_slot);
							$tuesday_slots[] = $slot;
							$tuesday_evening_start_slot = $tuesday_evening_end_slot;
						}
					}
				}				
				//Wednesday Slots
				if(!isset($_POST['wednesday_off'])){
					// Wednesday First Half
					if(!isset($_POST['wednesday_morning_off'])){
						$wednesday_morning_start_time = $wednesday_morning_start_slot = strtotime($_POST['wednesday_morning_start_time']);
						$wednesday_morning_end_time = strtotime($_POST['wednesday_morning_end_time']);
						while($wednesday_morning_start_slot <= $wednesday_morning_end_time) {
							if($wednesday_morning_start_slot == $wednesday_morning_end_time){break;}
							$wednesday_morning_end_slot = $wednesday_morning_start_slot+(60*5);
							$slot = date('H:i', $wednesday_morning_start_slot).'-'.date('H:i', $wednesday_morning_end_slot);
							$wednesday_slots[] = $slot;
							$wednesday_morning_start_slot = $wednesday_morning_end_slot;
						}
					}
					// Wednesday Second Half
					if(!isset($_POST['wednesday_evening_off'])){
						$wednesday_evening_start_time = $wednesday_evening_start_slot = strtotime($_POST['wednesday_evening_start_time']);
						$wednesday_evening_end_time = strtotime($_POST['wednesday_evening_end_time']);
						while($wednesday_evening_start_slot <= $wednesday_evening_end_time) {
							if($wednesday_evening_start_slot == $wednesday_evening_end_time){break;}
							$wednesday_evening_end_slot = $wednesday_evening_start_slot+(60*5);
							$slot = date('H:i', $wednesday_evening_start_slot).'-'.date('H:i', $wednesday_evening_end_slot);
							$wednesday_slots[] = $slot;
							$wednesday_evening_start_slot = $wednesday_evening_end_slot;
						}
					}
				}				
				//thursday Slots
				if(!isset($_POST['thursday_off'])){
					// thursday First Half
					if(!isset($_POST['thursday_morning_off'])){
						$thursday_morning_start_time = $thursday_morning_start_slot = strtotime($_POST['thursday_morning_start_time']);
						$thursday_morning_end_time = strtotime($_POST['thursday_morning_end_time']);
						while($thursday_morning_start_slot <= $thursday_morning_end_time) {
							if($thursday_morning_start_slot == $thursday_morning_end_time){break;}
							$thursday_morning_end_slot = $thursday_morning_start_slot+(60*5);
							$slot = date('H:i', $thursday_morning_start_slot).'-'.date('H:i', $thursday_morning_end_slot);
							$thursday_slots[] = $slot;
							$thursday_morning_start_slot = $thursday_morning_end_slot;
						}
					}
					// thursday Second Half
					if(!isset($_POST['thursday_evening_off'])){
						$thursday_evening_start_time = $thursday_evening_start_slot = strtotime($_POST['thursday_evening_start_time']);
						$thursday_evening_end_time = strtotime($_POST['thursday_evening_end_time']);
						while($thursday_evening_start_slot <= $thursday_evening_end_time) {
							if($thursday_evening_start_slot == $thursday_evening_end_time){break;}
							$thursday_evening_end_slot = $thursday_evening_start_slot+(60*5);
							$slot = date('H:i', $thursday_evening_start_slot).'-'.date('H:i', $thursday_evening_end_slot);
							$thursday_slots[] = $slot;
							$thursday_evening_start_slot = $thursday_evening_end_slot;
						}
					}
				}				
				//Friday Slots
				if(!isset($_POST['friday_off'])){
					// Friday First Half
					if(!isset($_POST['friday_morning_off'])){
						$friday_morning_start_time = $friday_morning_start_slot = strtotime($_POST['friday_morning_start_time']);
						$friday_morning_end_time = strtotime($_POST['friday_morning_end_time']);
						while($friday_morning_start_slot <= $friday_morning_end_time) {
							if($friday_morning_start_slot == $friday_morning_end_time){break;}
							$friday_morning_end_slot = $friday_morning_start_slot+(60*5);
							$slot = date('H:i', $friday_morning_start_slot).'-'.date('H:i', $friday_morning_end_slot);
							$friday_slots[] = $slot;
							$friday_morning_start_slot = $friday_morning_end_slot;
						}
					}
					// Friday Second Half
					if(!isset($_POST['friday_evening_off'])){
						$friday_evening_start_time = $friday_evening_start_slot = strtotime($_POST['friday_evening_start_time']);
						$friday_evening_end_time = strtotime($_POST['friday_evening_end_time']);
						while($friday_evening_start_slot <= $friday_evening_end_time) {
							if($friday_evening_start_slot == $friday_evening_end_time){break;}
							$friday_evening_end_slot = $friday_evening_start_slot+(60*5);
							$slot = date('H:i', $friday_evening_start_slot).'-'.date('H:i', $friday_evening_end_slot);
							$friday_slots[] = $slot;
							$friday_evening_start_slot = $friday_evening_end_slot;
						}
					}
				}				
				//Saturday Slots
				if(!isset($_POST['saturday_off'])){
					// Saturday First Half
					if(!isset($_POST['saturday_morning_off'])){
						$saturday_morning_start_time = $saturday_morning_start_slot = strtotime($_POST['saturday_morning_start_time']);
						$saturday_morning_end_time = strtotime($_POST['saturday_morning_end_time']);
						while($saturday_morning_start_slot <= $saturday_morning_end_time) {
							if($saturday_morning_start_slot == $saturday_morning_end_time){break;}
							$saturday_morning_end_slot = $saturday_morning_start_slot+(60*5);;
							$slot = date('H:i', $saturday_morning_start_slot).'-'.date('H:i', $saturday_morning_end_slot);
							$saturday_slots[] = $slot;
							$saturday_morning_start_slot = $saturday_morning_end_slot;
						}
					}
					// Saturday Second Half
					if(!isset($_POST['saturday_evening_off'])){
						$saturday_evening_start_time = $saturday_evening_start_slot = strtotime($_POST['saturday_evening_start_time']);
						$saturday_evening_end_time = strtotime($_POST['saturday_evening_end_time']);
						while($saturday_evening_start_slot <= $saturday_evening_end_time) {
							if($saturday_evening_start_slot == $saturday_evening_end_time){break;}
							$saturday_evening_end_slot = $saturday_evening_start_slot+(60*5);
							$slot = date('H:i', $saturday_evening_start_slot).'-'.date('H:i', $saturday_evening_end_slot);
							$saturday_slots[] = $slot;
							$saturday_evening_start_slot = $saturday_evening_end_slot;
						}
					}
				}				
				//Sunday Slots
				if(!isset($_POST['sunday_off'])){
					// Sunday First Half
					if(!isset($_POST['sunday_morning_off'])){
						$sunday_morning_start_time = $sunday_morning_start_slot = strtotime($_POST['sunday_morning_start_time']);
						$sunday_morning_end_time = strtotime($_POST['sunday_morning_end_time']);
						while($sunday_morning_start_slot <= $sunday_morning_end_time) {
							if($sunday_morning_start_slot == $sunday_morning_end_time){break;}
							$sunday_morning_end_slot = $sunday_morning_start_slot+(60*5);
							$slot = date('H:i', $sunday_morning_start_slot).'-'.date('H:i', $sunday_morning_end_slot);
							$sunday_slots[] = $slot;
							$sunday_morning_start_slot = $sunday_morning_end_slot;
						}
					}
					// Sunday Second Half
					if(!isset($_POST['sunday_evening_off'])){
						$sunday_evening_start_time = $sunday_evening_start_slot = strtotime($_POST['sunday_evening_start_time']);
						$sunday_evening_end_time = strtotime($_POST['sunday_evening_end_time']);
						while($sunday_evening_start_slot <= $sunday_evening_end_time) {
							if($sunday_evening_start_slot == $sunday_evening_end_time){break;}
							$sunday_evening_end_slot = $sunday_evening_start_slot+(60*5);
							$slot = date('H:i', $sunday_evening_start_slot).'-'.date('H:i', $sunday_evening_end_slot);
							$sunday_slots[] = $slot;
							$sunday_evening_start_slot = $sunday_evening_end_slot;
						}
					}
				}
				$_POST['monday_slots'] = !empty($monday_slots)?serialize($monday_slots):'';
				$_POST['tuesday_slots'] = !empty($tuesday_slots)?serialize($tuesday_slots):'';
				$_POST['wednesday_slots'] = !empty($wednesday_slots)?serialize($wednesday_slots):'';
				$_POST['thursday_slots'] = !empty($thursday_slots)?serialize($thursday_slots):'';
				$_POST['friday_slots'] = !empty($friday_slots)?serialize($friday_slots):'';
				$_POST['saturday_slots'] = !empty($saturday_slots)?serialize($saturday_slots):'';
				$_POST['sunday_slots'] = !empty($sunday_slots)?serialize($sunday_slots):'';
				if(!isset($_POST['on_holiday'])){$_POST['on_holiday_daterange'] == "";}
				if(isset($_POST['password']) && !empty($_POST['password'])){$_POST['password'] = md5($_POST['password']);}
				
				
				/*var_dump($_POST);echo '<br/>';echo '<br/>';echo '<br/>';
				var_dump($monday_slots); echo '<br/><br/>';
				var_dump($tuesday_slots); echo '<br/><br/>';
				var_dump($wednesday_slots); echo '<br/><br/>';
				var_dump($thursday_slots); echo '<br/><br/>';
				var_dump($friday_slots); echo '<br/><br/>';
				var_dump($saturday_slots); echo '<br/><br/>';
				var_dump($sunday_slots); echo '<br/><br/>';
				die;*/
				
				$data = $this->doctors_model->add_doctor($_POST);
				if($data > 0){
					header("location:" .base_url(). "doctors/add?m=".base64_encode('Doctor added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "doctors/add?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$data['centers'] = $this->center_model->get_centers();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/add_doctor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function add_junior_doctors()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_junior_doctors'){
				unset($_POST['action']);

				$username = $_POST['username'];
				$check_doctor = $this->doctors_model->check_doctor($username);
				//var_dump($check_doctor);die;
				if($check_doctor > 0){
					//var_dump($check_doctor);die;
					header("location:" .base_url(). "junior-doctors/add?m=".base64_encode('Username already exist!').'&t='.base64_encode('error'));
					die();
				}
				$_POST['junior_doctor'] = 1;
				if(isset($_POST['password']) && !empty($_POST['password'])){$_POST['password'] = md5($_POST['password']);}

				// var_dump($_POST);
				// echo '<br/>';echo '<br/>';echo '<br/>';
				// die;
				$doctor_lists = $_POST['doctors']; unset($_POST['doctors']);
				$data = $this->doctors_model->add_junior_doctors($_POST);
				if($data > 0){					
					$doctor_relationship = $this->doctors_model->doctor_relationship($data, $doctor_lists);
					header("location:" .base_url(). "junior-doctors?m=".base64_encode('Junior Doctor added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "junior-doctors/add?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$data['doctors'] = $this->doctors_model->get_doctors_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/add_junior_doctors', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function edit()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			
			if(isset($_GET['id'])){
				if(isset($_GET['id'])){ $item_id = $_GET['id']; }
				if(isset($_POST['id'])) { $item_id = $_POST['id']; }
				
				if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_doctor'){
						unset($_POST['action']);
						$post_arr = $monday_slots = $tuesday_slots = $wednesday_slots = $thursday_slots = $friday_slots = $saturday_slots = $sunday_slots = array();
						
					//Monday Slots
					if(!isset($_POST['monday_off'])){
						$_POST['monday_off'] = 0;
						// Monday First Half
						if(!isset($_POST['monday_morning_off'])){
							$_POST['monday_morning_off'] = 0;
							$monday_morning_start_time = $monday_morning_start_slot = strtotime($_POST['monday_morning_start_time']);
							$monday_morning_end_time = strtotime($_POST['monday_morning_end_time']);
							
							while($monday_morning_start_slot <= $monday_morning_end_time) {
								if($monday_morning_start_slot == $monday_morning_end_time){break;}
								$monday_morning_end_slot = $monday_morning_start_slot+(60*20);
								$slot = date('H:i', $monday_morning_start_slot).'-'.date('H:i', $monday_morning_end_slot);
								$monday_slots[] = $slot;
								$monday_morning_start_slot = $monday_morning_end_slot;
							}
						}
	
						// Monday Second Half
						if(!isset($_POST['monday_evening_off'])){
							$_POST['monday_evening_off'] = 0;
							$monday_evening_start_time = $monday_evening_start_slot = strtotime($_POST['monday_evening_start_time']);
							$monday_evening_end_time = strtotime($_POST['monday_evening_end_time']);
							while($monday_evening_start_slot <= $monday_evening_end_time) {
								if($monday_evening_start_slot == $monday_evening_end_time){break;}
								$monday_evening_end_slot = $monday_evening_start_slot+(60*20);
								$slot = date('H:i', $monday_evening_start_slot).'-'.date('H:i', $monday_evening_end_slot);
								$monday_slots[] = $slot;
								$monday_evening_start_slot = $monday_evening_end_slot;
							}
						}
					}	
						
					//Tuesday Slots
					if(!isset($_POST['tuesday_off'])){
							$_POST['tuesday_off'] = 0;
						// Tuesday First Half
						if(!isset($_POST['tuesdy_morning_off'])){
							$_POST['tuesdy_morning_off'] = 0;
							$tuesday_morning_start_time = $tuesday_morning_start_slot = strtotime($_POST['tuesday_morning_start_time']);
							$tuesday_morning_end_time = strtotime($_POST['tuesday_morning_end_time']);
							while($tuesday_morning_start_slot <= $tuesday_morning_end_time) {
								if($tuesday_morning_start_slot == $tuesday_morning_end_time){break;}
								$tuesday_morning_end_slot = $tuesday_morning_start_slot+(60*20);
								$slot = date('H:i', $tuesday_morning_start_slot).'-'.date('H:i', $tuesday_morning_end_slot);
								$tuesday_slots[] = $slot;
								$tuesday_morning_start_slot = $tuesday_morning_end_slot;
							}
						}
						// Tuesday Second Half
						if(!isset($_POST['tuesdy_evening_off'])){
							$_POST['tuesdy_evening_off'] = 0;
							$tuesday_evening_start_time = $tuesday_evening_start_slot = strtotime($_POST['tuesday_evening_start_time']);
							$tuesday_evening_end_time = strtotime($_POST['tuesday_evening_end_time']);
							while($tuesday_evening_start_slot <= $tuesday_evening_end_time) {
								if($tuesday_evening_start_slot == $tuesday_evening_end_time){break;}
								$tuesday_evening_end_slot = $tuesday_evening_start_slot+(60*20);
								$slot = date('H:i', $tuesday_evening_start_slot).'-'.date('H:i', $tuesday_evening_end_slot);
								$tuesday_slots[] = $slot;
								$tuesday_evening_start_slot = $tuesday_evening_end_slot;
							}
						}
					}				
					//Wednesday Slots
					if(!isset($_POST['wednesday_off'])){
							$_POST['wednesday_off'] = 0;
						// Wednesday First Half
						if(!isset($_POST['wednesday_morning_off'])){
							$_POST['wednesday_morning_off'] = 0;
							$wednesday_morning_start_time = $wednesday_morning_start_slot = strtotime($_POST['wednesday_morning_start_time']);
							$wednesday_morning_end_time = strtotime($_POST['wednesday_morning_end_time']);
							while($wednesday_morning_start_slot <= $wednesday_morning_end_time) {
								if($wednesday_morning_start_slot == $wednesday_morning_end_time){break;}
								$wednesday_morning_end_slot = $wednesday_morning_start_slot+(60*20);
								$slot = date('H:i', $wednesday_morning_start_slot).'-'.date('H:i', $wednesday_morning_end_slot);
								$wednesday_slots[] = $slot;
								$wednesday_morning_start_slot = $wednesday_morning_end_slot;
							}
						}
						// Wednesday Second Half
						if(!isset($_POST['wednesday_evening_off'])){
							$_POST['wednesday_evening_off'] = 0;
							$wednesday_evening_start_time = $wednesday_evening_start_slot = strtotime($_POST['wednesday_evening_start_time']);
							$wednesday_evening_end_time = strtotime($_POST['wednesday_evening_end_time']);
							while($wednesday_evening_start_slot <= $wednesday_evening_end_time) {
								if($wednesday_evening_start_slot == $wednesday_evening_end_time){break;}
								$wednesday_evening_end_slot = $wednesday_evening_start_slot+(60*20);
								$slot = date('H:i', $wednesday_evening_start_slot).'-'.date('H:i', $wednesday_evening_end_slot);
								$wednesday_slots[] = $slot;
								$wednesday_evening_start_slot = $wednesday_evening_end_slot;
							}
						}
					}				
					//thursday Slots
					if(!isset($_POST['thursday_off'])){
							$_POST['thursday_off'] = 0;
						// thursday First Half
						if(!isset($_POST['thursday_morning_off'])){
							$_POST['thursday_morning_off'] = 0;
							$thursday_morning_start_time = $thursday_morning_start_slot = strtotime($_POST['thursday_morning_start_time']);
							$thursday_morning_end_time = strtotime($_POST['thursday_morning_end_time']);
							while($thursday_morning_start_slot <= $thursday_morning_end_time) {
								if($thursday_morning_start_slot == $thursday_morning_end_time){break;}
								$thursday_morning_end_slot = $thursday_morning_start_slot+(60*20);
								$slot = date('H:i', $thursday_morning_start_slot).'-'.date('H:i', $thursday_morning_end_slot);
								$thursday_slots[] = $slot;
								$thursday_morning_start_slot = $thursday_morning_end_slot;
							}
						}
						// thursday Second Half
						if(!isset($_POST['thursday_evening_off'])){
							$_POST['thursday_evening_off'] = 0;
							$thursday_evening_start_time = $thursday_evening_start_slot = strtotime($_POST['thursday_evening_start_time']);
							$thursday_evening_end_time = strtotime($_POST['thursday_evening_end_time']);
							while($thursday_evening_start_slot <= $thursday_evening_end_time) {
								if($thursday_evening_start_slot == $thursday_evening_end_time){break;}
								$thursday_evening_end_slot = $thursday_evening_start_slot+(60*20);
								$slot = date('H:i', $thursday_evening_start_slot).'-'.date('H:i', $thursday_evening_end_slot);
								$thursday_slots[] = $slot;
								$thursday_evening_start_slot = $thursday_evening_end_slot;
							}
						}
					}				
					//Friday Slots
					if(!isset($_POST['friday_off'])){
							$_POST['friday_off'] = 0;
						// Friday First Half
						if(!isset($_POST['friday_morning_off'])){
							$_POST['friday_morning_off'] = 0;
							$friday_morning_start_time = $friday_morning_start_slot = strtotime($_POST['friday_morning_start_time']);
							$friday_morning_end_time = strtotime($_POST['friday_morning_end_time']);
							while($friday_morning_start_slot <= $friday_morning_end_time) {
								if($friday_morning_start_slot == $friday_morning_end_time){break;}
								$friday_morning_end_slot = $friday_morning_start_slot+(60*20);
								$slot = date('H:i', $friday_morning_start_slot).'-'.date('H:i', $friday_morning_end_slot);
								$friday_slots[] = $slot;
								$friday_morning_start_slot = $friday_morning_end_slot;
							}
						}
						// Friday Second Half
						if(!isset($_POST['friday_evening_off'])){
							$_POST['friday_evening_off'] = 0;
							$friday_evening_start_time = $friday_evening_start_slot = strtotime($_POST['friday_evening_start_time']);
							$friday_evening_end_time = strtotime($_POST['friday_evening_end_time']);
							while($friday_evening_start_slot <= $friday_evening_end_time) {
								if($friday_evening_start_slot == $friday_evening_end_time){break;}
								$friday_evening_end_slot = $friday_evening_start_slot+(60*20);
								$slot = date('H:i', $friday_evening_start_slot).'-'.date('H:i', $friday_evening_end_slot);
								$friday_slots[] = $slot;
								$friday_evening_start_slot = $friday_evening_end_slot;
							}
						}
					}				
					//Saturday Slots
					if(!isset($_POST['saturday_off'])){
							$_POST['saturday_off'] = 0;
						// Saturday First Half
						if(!isset($_POST['saturday_morning_off'])){
							$_POST['saturday_morning_off'] = 0;
							$saturday_morning_start_time = $saturday_morning_start_slot = strtotime($_POST['saturday_morning_start_time']);
							$saturday_morning_end_time = strtotime($_POST['saturday_morning_end_time']);
							while($saturday_morning_start_slot <= $saturday_morning_end_time) {
								if($saturday_morning_start_slot == $saturday_morning_end_time){break;}
								$saturday_morning_end_slot = $saturday_morning_start_slot+(60*5);;
								$slot = date('H:i', $saturday_morning_start_slot).'-'.date('H:i', $saturday_morning_end_slot);
								$saturday_slots[] = $slot;
								$saturday_morning_start_slot = $saturday_morning_end_slot;
							}
						}
						// Saturday Second Half
						if(!isset($_POST['saturday_evening_off'])){
							$_POST['saturday_evening_off'] = 0;
							$saturday_evening_start_time = $saturday_evening_start_slot = strtotime($_POST['saturday_evening_start_time']);
							$saturday_evening_end_time = strtotime($_POST['saturday_evening_end_time']);
							while($saturday_evening_start_slot <= $saturday_evening_end_time) {
								if($saturday_evening_start_slot == $saturday_evening_end_time){break;}
								$saturday_evening_end_slot = $saturday_evening_start_slot+(60*5);
								$slot = date('H:i', $saturday_evening_start_slot).'-'.date('H:i', $saturday_evening_end_slot);
								$saturday_slots[] = $slot;
								$saturday_evening_start_slot = $saturday_evening_end_slot;
							}
						}
					}				
					//Sunday Slots
					if(!isset($_POST['sunday_off'])){
							$_POST['sunday_off'] = 0;
						// Sunday First Half
						if(!isset($_POST['sunday_morning_off'])){
							$_POST['sunday_morning_off'] = 0;
							$sunday_morning_start_time = $sunday_morning_start_slot = strtotime($_POST['sunday_morning_start_time']);
							$sunday_morning_end_time = strtotime($_POST['sunday_morning_end_time']);
							while($sunday_morning_start_slot <= $sunday_morning_end_time) {
								if($sunday_morning_start_slot == $sunday_morning_end_time){break;}
								$sunday_morning_end_slot = $sunday_morning_start_slot+(60*5);
								$slot = date('H:i', $sunday_morning_start_slot).'-'.date('H:i', $sunday_morning_end_slot);
								$sunday_slots[] = $slot;
								$sunday_morning_start_slot = $sunday_morning_end_slot;
							}
						}
						// Sunday Second Half
						if(!isset($_POST['sunday_evening_off'])){
							$_POST['sunday_evening_off'] = 0;
							$sunday_evening_start_time = $sunday_evening_start_slot = strtotime($_POST['sunday_evening_start_time']);
							$sunday_evening_end_time = strtotime($_POST['sunday_evening_end_time']);
							while($sunday_evening_start_slot <= $sunday_evening_end_time) {
								if($sunday_evening_start_slot == $sunday_evening_end_time){break;}
								$sunday_evening_end_slot = $sunday_evening_start_slot+(60*5);
								$slot = date('H:i', $sunday_evening_start_slot).'-'.date('H:i', $sunday_evening_end_slot);
								$sunday_slots[] = $slot;
								$sunday_evening_start_slot = $sunday_evening_end_slot;
							}
						}
					}
					$_POST['monday_slots'] = !empty($monday_slots)?serialize($monday_slots):'';
					$_POST['tuesday_slots'] = !empty($tuesday_slots)?serialize($tuesday_slots):'';
					$_POST['wednesday_slots'] = !empty($wednesday_slots)?serialize($wednesday_slots):'';
					$_POST['thursday_slots'] = !empty($thursday_slots)?serialize($thursday_slots):'';
					$_POST['friday_slots'] = !empty($friday_slots)?serialize($friday_slots):'';
					$_POST['saturday_slots'] = !empty($saturday_slots)?serialize($saturday_slots):'';
					$_POST['sunday_slots'] = !empty($sunday_slots)?serialize($sunday_slots):'';
					if(!isset($_POST['on_holiday'])){$_POST['on_holiday_daterange'] == "";}
					if(isset($_POST['password']) && !empty($_POST['password'])){$_POST['password'] = md5($_POST['password']);}
					else{unset($_POST['password']);}
					if(!isset($_POST['is_primary'])){$_POST['is_primary'] = 0;}
					
					/*var_dump($_POST);echo '<br/>';echo '<br/>';echo '<br/>';
					var_dump($monday_slots); echo '<br/><br/>';
					var_dump($tuesday_slots); echo '<br/><br/>';
					var_dump($wednesday_slots); echo '<br/><br/>';
					var_dump($thursday_slots); echo '<br/><br/>';
					var_dump($friday_slots); echo '<br/><br/>';
					var_dump($saturday_slots); echo '<br/><br/>';
					var_dump($sunday_slots); echo '<br/><br/>';
					die;*/
					
					$data = $this->doctors_model->update_doctor_data($_POST, $item_id);
					if($data > 0){
						header("location:" .base_url(). "doctors/edit?m=".base64_encode('Doctor updated successfully !').'&t='.base64_encode('success').'&id='.$item_id);
						die();
					}else{
						header("location:" .base_url(). "doctors/edit?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&id='.$item_id);
						die();
					}				
				}
				$data['data'] = $this->doctors_model->get_doctor_data($item_id);
				$data['centers'] = $this->center_model->get_centers();
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('doctors/edit_doctor', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "doctors");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function edit_junior_doctors()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			
			if(isset($_GET['id'])){
				if(isset($_GET['id'])){ $item_id = $_GET['id']; }
				if(isset($_POST['id'])) { $item_id = $_POST['id']; }
				
				if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_junior_doctors'){
					unset($_POST['action']);
					
					if(isset($_POST['password']) && !empty($_POST['password'])){$_POST['password'] = md5($_POST['password']);}
					else{unset($_POST['password']);}
					
					$doctor_lists = $_POST['doctors']; unset($_POST['doctors']);
					$data = $this->doctors_model->update_doctor_data($_POST, $item_id);
					if($data > 0){
						$update_doctor_relationship = $this->doctors_model->update_doctor_relationship($item_id, $doctor_lists);
						header("location:" .base_url(). "junior-doctors/edit?m=".base64_encode('Junior Doctor updated successfully !').'&t='.base64_encode('success').'&id='.$item_id);
						die();
					}else{
						header("location:" .base_url(). "junior-doctors/edit?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&id='.$item_id);
						die();
					}				
				}
				$data['data'] = $this->doctors_model->get_doctor_data($item_id);
				$data['doctors'] = $this->doctors_model->get_doctors_list();
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('doctors/edit_junior_doctors', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "doctors");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function delete()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_GET['id'])){	
				$item = $_GET['id'];
				if( $item > 0 )
				{
					if( $this->doctors_model->delete_doctor_data($item) !== 0)
					{
						header("location:" .base_url(). "doctors?m=".base64_encode('Doctor deleted successfully !').'&t='.base64_encode('success'));
						die();
					}
					else
					{
						header("location:" .base_url(). "doctors?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
						die();
					}
				}
				header("location:" .base_url(). "doctors?m=".base64_encode('Doctor not found !').'&t='.base64_encode('error'));
				die();
			}else{
				header("location:" .base_url(). "doctors");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function delete_junior_doctors()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_GET['id'])){	
				$item = $_GET['id'];
				if( $item > 0 )
				{
					if( $this->doctors_model->delete_junior_doctor_data($item) !== 0)
					{
						header("location:" .base_url(). "junior-doctors?m=".base64_encode('Junior Doctor deleted successfully !').'&t='.base64_encode('success'));
						die();
					}
					else
					{
						header("location:" .base_url(). "junior-doctors?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
						die();
					}
				}
				header("location:" .base_url(). "junior-doctors?m=".base64_encode('Junior Doctor not found !').'&t='.base64_encode('error'));
				die();
			}else{
				header("location:" .base_url(). "junior-doctors");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function doctor_consultations(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->get_doctor_consultations();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/doctor_consultations', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function disapprove_consultation_done($id){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
	    	$disapprove_reason = isset($_GET['t'])?$_GET['t']:"";
			$status = $this->doctors_model->disapprove_consultation_done($id, $disapprove_reason);
			if($status > 0){
				header("location:" .base_url()."doctor-consultations?m=".base64_encode('Doctor Consultation disapproved!').'&t='.base64_encode('success'));
				die();
			}else{
				header("location:" .base_url()."doctor-consultations?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	// Dashbaord START

	public function login(){
		$logg = checklogin();
		if($logg['status'] == true){
			header("location:" .base_url(). "dashboard");
			die;
		}else{
			if(isset($_POST['login']) && !empty($_POST['login']) && $_POST['login'] == 'login'){
				unset($_POST['login']);
				$logged = $this->doctors_model->login($_POST);
	
				if($logged['status'] == 1){
					header("location:" .base_url(). "dashboard");
					die();
				}else{
					header("location:" .base_url(). "");
					die();
				}
			}else{
				$this->load->view('templates/header');
				$this->load->view('doctors/login');
				$this->load->view('templates/footer');
			}
		}
	}
	
	public function doctor_appointments(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){
			$data = array(); $doctor_id = "";
			// Get Parameter from front end
			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$status = $this->input->get('status', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$patient_name = $this->input->get('patient_name', true);

			$doctor_id = $_SESSION['logged_doctor']['doctor_id'];
			$config = array();
        	$config["base_url"] = base_url() . "doctor_appointments";
        	$config["total_rows"] = $this->doctors_model->get_doctor_count($doctor_id, $status, $start_date, $end_date, $patient_id, $patient_name);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
			
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			
			//$data['appointments'] = $this->doctors_model->doctor_appointment_lists($doctor_id);
			$data['appointments'] = $this->doctors_model->doctor_appointment_lists_pagination($doctor_id, $config["per_page"], $per_page, $status, $start_date, $end_date, $patient_id, $patient_name);
			$data["status"] = $status;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_name"] = $patient_name;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/appointment', $data);
			$this->load->view($template['footer']);
			
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function junior_doctor_appointments(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$junior_id = 0;
			$junior_id = $_SESSION['logged_doctor']['doctor_id'];
			$data['appointments'] = $this->doctors_model->junior_doctor_appointment_lists($junior_id);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/junior_doctor_appointments', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function get_consultation($appointments_id){
		$data = $this->billingmodel_model->get_consultation_data($appointments_id);
		return $data;
	}
	
	public function consultation_done($appointment_id){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_consultation_done'){
				unset($_POST['action']);
				$redirect_url = "doctor_appointments";
				if($_SESSION['logged_doctor']['junior_doctor'] == 1){
					$redirect_url = "jd_appointments";
				}
				if(isset($_POST['medicine_suggestion'])){
					$male_med_array = $female_med_array = array();
					foreach($_POST as $key => $val){
						if (substr( $key, 0, 19 ) === "male_medicine_name_" && substr( $key, 19, 4 ) !== "ipd_") {
							$male_med_array[] = $key;
						}
						if (substr( $key, 0, 21 ) === "female_medicine_name_" && substr( $key, 21, 4 ) !== "ipd_") {
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
				if(isset($_POST['medicine_suggestion_ipd'])){
					$male_med_array_ipd = $female_med_array_ipd = array();
					foreach($_POST as $key => $val){
						if (substr( $key, 0, 23 ) === "male_medicine_name_ipd_") {
							$male_med_array_ipd[] = $key;
						}
						if (substr( $key, 0, 25 ) === "female_medicine_name_ipd_") {
							$female_med_array_ipd[] = $key;
						}
					}
					$male_med_number_ipd = $female_med_number_ipd = array();
					foreach($male_med_array_ipd as $key => $val){
							$explode = explode('male_medicine_name_ipd_', $val);
							$male_med_number_ipd[] = $explode[1];
					}
					foreach($female_med_array_ipd as $key => $val){
							$explode = explode('female_medicine_name_ipd_', $val);
							$female_med_number_ipd[] = $explode[1];
					}
					$male_med_number_ipd = array_unique($male_med_number_ipd);
					$female_med_number_ipd = array_unique($female_med_number_ipd);
					
					$male_medicine_suggestion_list_ipd = $female_medicine_suggestion_list_ipd = array();
					
					foreach($male_med_number_ipd as $key => $val){
						$male_medicine_suggestion_list_ipd[] = array(
							 'male_medicine_name' => $_POST['male_medicine_name_ipd_'.$val],
							 'male_medicine_dosage' => $_POST['male_medicine_dosage_ipd_'.$val],
							 'male_medicine_when_start' => $_POST['male_medicine_when_start_ipd_'.$val],
							 'male_medicine_days' => $_POST['male_medicine_days_ipd_'.$val],
							 'male_medicine_route' => $_POST['male_medicine_route_ipd_'.$val],
							 'male_medicine_frequency' => $_POST['male_medicine_frequency_ipd_'.$val],
							 'male_medicine_timing' => $_POST['male_medicine_timing_ipd_'.$val],
							 'male_medicine_take' => $_POST['male_medicine_take_ipd_'.$val]
							);
						unset($_POST['male_medicine_name_ipd_'.$val]);
						unset($_POST['male_medicine_dosage_ipd_'.$val]);
						unset($_POST['male_medicine_when_start_ipd_'.$val]);
						unset($_POST['male_medicine_days_ipd_'.$val]);
						unset($_POST['male_medicine_route_ipd_'.$val]);
						unset($_POST['male_medicine_frequency_ipd_'.$val]);
						unset($_POST['male_medicine_timing_ipd_'.$val]);
						unset($_POST['male_medicine_take_ipd_'.$val]);
					}
					$male_medicine_suggestion_list_ipd = serialize($male_medicine_suggestion_list_ipd);
					$_POST['male_medicine_suggestion_list_ipd'] = $male_medicine_suggestion_list_ipd;
					foreach($female_med_number_ipd as $key => $val){
						$female_medicine_suggestion_list_ipd[] = array(
							 'female_medicine_name' => $_POST['female_medicine_name_ipd_'.$val],
							 'female_medicine_dosage' => $_POST['female_medicine_dosage_ipd_'.$val],
							 'female_medicine_when_start' => $_POST['female_medicine_when_start_ipd_'.$val],
							 'female_medicine_days' => $_POST['female_medicine_days_ipd_'.$val],
							 'female_medicine_route' => $_POST['female_medicine_route_ipd_'.$val],
							 'female_medicine_frequency' => $_POST['female_medicine_frequency_ipd_'.$val],
							 'female_medicine_timing' => $_POST['female_medicine_timing_ipd_'.$val],
							 'female_medicine_take' => $_POST['female_medicine_take_ipd_'.$val]
						);
						unset($_POST['female_medicine_name_ipd_'.$val]);
						unset($_POST['female_medicine_dosage_ipd_'.$val]);
						unset($_POST['female_medicine_when_start_ipd_'.$val]);
						unset($_POST['female_medicine_days_ipd_'.$val]);
						unset($_POST['female_medicine_route_ipd_'.$val]);
						unset($_POST['female_medicine_frequency_ipd_'.$val]);
						unset($_POST['female_medicine_timing_ipd_'.$val]);
						unset($_POST['female_medicine_take_ipd_'.$val]);
					}
					$female_medicine_suggestion_list_ipd = serialize($female_medicine_suggestion_list_ipd);
					$_POST['female_medicine_suggestion_list_ipd'] = $female_medicine_suggestion_list_ipd;
				}
				
				if(isset($_POST['provisional_diagnosis'])){
					if(!empty($_POST['male_provisional_diagnosis_list'])){
						$male_provisional_diagnosis_list = array();
						$male_provisional_diagnosis_list = $_POST['male_provisional_diagnosis_list'];unset($_POST['male_provisional_diagnosis_list']);
						$_POST['male_provisional_diagnosis_list'] = serialize($male_provisional_diagnosis_list);
					}
					if(!empty($_POST['female_provisional_diagnosis_list'])){
						$female_provisional_diagnosis_list = array();
						$female_provisional_diagnosis_list = $_POST['female_provisional_diagnosis_list'];unset($_POST['female_provisional_diagnosis_list']);
						$_POST['female_provisional_diagnosis_list'] = serialize($female_provisional_diagnosis_list);
					}	
					
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
					if(!empty($_POST['male_minvestigation_suggestion_list'])){
						$male_minvestigation_suggestion_list = array();
						$male_minvestigation_suggestion_list = $_POST['male_minvestigation_suggestion_list'];unset($_POST['male_minvestigation_suggestion_list']);
						$_POST['male_minvestigation_suggestion_list'] = serialize($male_minvestigation_suggestion_list);
					}
					if(!empty($_POST['female_minvestigation_suggestion_list'])){
						$female_minvestigation_suggestion_list = array();
						$female_minvestigation_suggestion_list = $_POST['female_minvestigation_suggestion_list'];unset($_POST['female_minvestigation_suggestion_list']);
						$_POST['female_minvestigation_suggestion_list'] = serialize($female_minvestigation_suggestion_list);
					}	
				}
				if(isset($_POST['procedure_suggestion'])){
				    if(isset($_POST['sub_procedure_suggestion_list']) && !empty($_POST['sub_procedure_suggestion_list'])){
					    $_POST['sub_procedure_suggestion_list'] = serialize($_POST['sub_procedure_suggestion_list']);
				    }
				}
				
				if(isset($_POST['package_suggestion'])){
					$_POST['package_suggestion_list'] = serialize($_POST['package_suggestion_list']);
				}

				$prescription = '';
				if(!empty($_FILES['prescription']['tmp_name'])){
					$dest_path = $this->config->item('upload_path');
					$destination = $dest_path.'patient_files/';
					$NewImageName = rand(4,10000)."-".$_POST['patient_id']."-".$_FILES['prescription']['name'];
					$prescription = base_url().'assets/patient_files/'.$NewImageName;
					move_uploaded_file($_FILES['prescription']['tmp_name'], $destination.$NewImageName);
				}
				$consultation_post = array();
				if($_POST['submit_type'] == "save_exit"){
					$consultation_post['edit_mode'] = 1;
					$consultation_post['final_mode'] = 0;
					$consultation_post['disapproval_reason'] = isset($_POST['disapproval_reason'])?$_POST['disapproval_reason']:''; unset($_POST['disapproval_reason']);
				}
				if($_POST['submit_type'] == "exit"){
					$consultation_post['edit_mode'] = 1;
					$consultation_post['final_mode'] = 1;
					$consultation_post['disapproval_reason'] = ''; unset($_POST['disapproval_reason']);
				}
				unset($_POST['submit_type']);
				$consultation_post['appointment_id'] = $_POST['appointment_id'];
				$consultation_post['patient_id'] = $_POST['patient_id'];
				$consultation_post['wife_phone'] = $_POST['wife_phone'];
				$consultation_post['doctor_id'] = $_POST['doctor_id']; unset($_POST['doctor_id']);
				$consultation_post['urosurgeon_findings'] = isset($_POST['urosurgeon_findings'])?$_POST['urosurgeon_findings']:''; unset($_POST['urosurgeon_findings']);
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
					}unset($_POST['female_medicine_suggestion_list']);
					
				}
				if(isset($_POST['medicine_suggestion_ipd'])){
					$consultation_post['medicine_suggestion_ipd'] = $_POST['medicine_suggestion_ipd']; unset($_POST['medicine_suggestion_ipd']);
					if(isset($male_medicine_suggestion_list_ipd) && !empty($male_medicine_suggestion_list_ipd)){
						$consultation_post['male_medicine_suggestion_list_ipd'] = $male_medicine_suggestion_list_ipd;
					} unset($_POST['male_medicine_suggestion_list_ipd']);
					if(isset($female_medicine_suggestion_list_ipd) && !empty($female_medicine_suggestion_list_ipd)){
						$consultation_post['female_medicine_suggestion_list_ipd'] = $female_medicine_suggestion_list_ipd; 
					}unset($_POST['female_medicine_suggestion_list_ipd']);
				}
				if(isset($_POST['investigation_suggestion'])){
					$consultation_post['investation_suggestion'] = $_POST['investigation_suggestion']; unset($_POST['investigation_suggestion']);
					if(isset($_POST['male_investigation_suggestion_list']) && !empty($_POST['male_investigation_suggestion_list'])){
						$consultation_post['male_investigation_suggestion_list'] = $_POST['male_investigation_suggestion_list'];
					} unset($_POST['male_investigation_suggestion_list']);
					if(isset($_POST['female_investigation_suggestion_list']) && !empty($_POST['female_investigation_suggestion_list'])){
						$consultation_post['female_investigation_suggestion_list'] = $_POST['female_investigation_suggestion_list'];
					} unset($_POST['female_investigation_suggestion_list']);
					if(isset($_POST['male_minvestigation_suggestion_list']) && !empty($_POST['male_minvestigation_suggestion_list'])){
						$consultation_post['male_minvestigation_suggestion_list'] = $_POST['male_minvestigation_suggestion_list'];
					} unset($_POST['male_minvestigation_suggestion_list']);
					if(isset($_POST['female_minvestigation_suggestion_list']) && !empty($_POST['female_minvestigation_suggestion_list'])){
						$consultation_post['female_minvestigation_suggestion_list'] = $_POST['female_minvestigation_suggestion_list'];
					} unset($_POST['female_minvestigation_suggestion_list']);
				}
				if(isset($_POST['procedure_suggestion'])){
					$consultation_post['procedure_suggestion'] = $_POST['procedure_suggestion']; unset($_POST['procedure_suggestion']);
					$consultation_post['procedure_suggestion_list'] = ''; 
					$consultation_post['sub_procedure_suggestion_list'] = $_POST['sub_procedure_suggestion_list']; unset($_POST['sub_procedure_suggestion_list']);
				}
				if(isset($_POST['package_suggestion'])){
					$consultation_post['package_suggestion'] = $_POST['package_suggestion']; unset($_POST['package_suggestion']);
					$consultation_post['package_suggestion_list'] = $_POST['package_suggestion_list']; unset($_POST['package_suggestion_list']);
				}
				$_POST['prescription'] = $prescription;
				$consultation_post['prescription'] = $prescription;
				$consultation_post['consultation_date'] = date("Y-m-d H:i:s");
				if($consultation_post['follow_up'] == 1){
				    if(empty($_POST['appoitment_for']) && empty($consultation_post['follow_up_date']) && empty($_POST['appoitmented_doctor']) && empty($consultation_post['follow_slot'])){
    				    header("location:" .base_url().$redirect_url."?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
    					die();
    				}
					$patient_details  = get_patient_detail($consultation_post['patient_id']);
					$doctor_details = doctor_details($consultation_post['doctor_id']);
					$appointment_arr = array();
					$appointment_arr['paitent_type'] = 'exist_patient';
					$appointment_arr['paitent_id'] = $consultation_post['patient_id'];
					$appointment_arr['wife_name'] = $patient_details['wife_name'];
					$appointment_arr['wife_phone'] = $consultation_post['wife_phone'];
					$appointment_arr['wife_email'] = $patient_details['wife_email'];;
					$appointment_arr['nationality'] = $patient_details['nationality'];;
					$appointment_arr['reason_of_visit'] = $consultation_post['follow_up_purpose'];
					$appointment_arr['appoitment_for'] = $_POST['appoitment_for'];unset($_POST['appoitment_for']);
					$appointment_arr['appoitmented_date'] = $consultation_post['follow_up_date'];
					$appointment_arr['appoitmented_doctor'] = $_POST['appoitmented_doctor'];unset($_POST['appoitmented_doctor']);
					$appointment_arr['appoitmented_slot'] = $consultation_post['follow_slot'];
					$appointment_arr['follow_up_appointment'] = 1;
					$appointment_arr['previous_appointment'] = $consultation_post['appointment_id'];
					$appointment_arr['appointment_added'] = date('Y-m-d H:i:s');
					$appointment = $this->billingmodel_model->insert_appointments($appointment_arr);
					if($appointment > 0){
						$doctor_details = doctor_details($appointment_arr['appoitmented_doctor']);
						$patient_to = $patient_subject = $patient_message = $doctor_to = $doctor_subject = $doctor_message = "";
						//Patient emails
						$patient_to = $patient_details['wife_email'];
						$patient_subject = "Followup appointment booked";
						$patient_message = "Hi ".$patient_details['wife_name'].",<br/> Your followup appointment is booked with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($consultation_post['follow_up_date']))." at ".$consultation_post['follow_slot'].".";
						// send_mail($patient_to, $patient_subject, $patient_message);
						
						$patient_phone = $patient_details['wife_phone'];
						$sms_message = "Hi ".$patient_details['wife_name'].", Your followup appointment is booked with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($consultation_post['follow_up_date']))." at ".$consultation_post['follow_slot'].".";
						send_sms($patient_phone, $sms_message);
						
						//Doctor emails
						$doctor_to = $doctor_details['email'];
						$doctor_subject = "Followup appointment";
						$doctor_message = "Hi Dr.".$doctor_details['name'].",<br/> Followup Appointment is booked on ".date("d-m-Y", strtotime($consultation_post['follow_up_date']))." at ".$consultation_post['follow_slot'].".";
						// send_mail($doctor_to, $doctor_subject, $doctor_message);
					}

				}
				$consultation_done = $this->doctors_model->consultation_done($consultation_post);
				$female_ids_serialized = $consultation_post['female_minvestigation_suggestion_list'];
				$unserializedArray = unserialize($female_ids_serialized);
				$investigation_names = [];
				if (is_array($unserializedArray)) {
					foreach ($unserializedArray as $key => $value) {
						$value = (int)$value; // ensure it's safe
						$sql2 = "SELECT * FROM `hms_master_investigations` WHERE ID = $value";
						$select_result9 = run_select_query($sql2);
						if (!empty($select_result9) && isset($select_result9['investigation_name'])) {
							$investigation_names[] = $select_result9['investigation_name'];
						}
					}
				}
				$male_ids_serialized = $consultation_post['male_minvestigation_suggestion_list'];
				$unserializedArray = unserialize($male_ids_serialized);
				$investigation_male_names = [];
				if (is_array($unserializedArray)) {
					foreach ($unserializedArray as $key => $value) {
						$value = (int)$value; // ensure it's safe
						$sql_male = "SELECT * FROM `hms_master_investigations` WHERE ID = $value";
						$select_male_result = run_select_query($sql_male);
						if (!empty($select_male_result) && isset($select_male_result['investigation_name'])) {
							$investigation_male_names[] = $select_male_result['investigation_name'];
						}
					}
				}
				$procedure_ids_serialized = $consultation_post['sub_procedure_suggestion_list'];
				$unserializedArray = unserialize($procedure_ids_serialized);
				$procedure_name = [];
				if (is_array($unserializedArray)) {
					foreach ($unserializedArray as $key => $value) {
						$value = (int)$value; // ensure it's safe
						$sql_procedure = "SELECT * FROM `hms_procedures` WHERE ID = $value";
						$select_procedure_result = run_select_query($sql_procedure);

						if (!empty($select_procedure_result) && isset($select_procedure_result['procedure_name'])) {
							$procedure_name[] = $select_procedure_result['procedure_name'];
						}
					}
				}
				
				$male_medicine_ids_serialized = $consultation_post['male_medicine_suggestion_list'];
				$unserializedArray = unserialize($male_medicine_ids_serialized);
				
				$item_name = [];

				foreach ($unserializedArray as $valueGroup) {
					foreach ($valueGroup as $value) {
						if (!empty($value['male_medicine_name']) && is_numeric($value['male_medicine_name'])) {
							$item_number = (int)$value['male_medicine_name'];

							$sql_medicine = "SELECT * FROM hms_stocks WHERE item_number = $item_number";
							$select_medicine_result = run_select_query($sql_medicine);

							if (!empty($select_medicine_result) && isset($select_medicine_result['item_name'])) {
								$item_name[] = $select_medicine_result['item_name'];
							}
						}
					}
				}
				
				$female_medicine_ids_serialized = $consultation_post['female_medicine_suggestion_list'];
				$unserializedArray = unserialize($female_medicine_ids_serialized);
				
				$item_name_female = [];

				foreach ($unserializedArray as $valueGroup) {
					foreach ($valueGroup as $value) {
						if (!empty($value['female_medicine_name']) && is_numeric($value['female_medicine_name'])) {
							$item_number = (int)$value['female_medicine_name'];

							$sql_medicine = "SELECT * FROM hms_stocks WHERE item_number = $item_number";
							$select_femedicine_result = run_select_query($sql_medicine);

							if (!empty($select_femedicine_result) && isset($select_femedicine_result['item_name'])) {
								$item_name_female[] = $select_femedicine_result['item_name'];
							}
						}
					}
				}
				$female_investigation =  implode(', ', $investigation_names);
				$male_investigation =  implode(', ', $investigation_male_names);
				
				
				$package_ids_string = $consultation_post['package_suggestion_list'];
				$package_ids_array = explode(',', $package_ids_string);

				$package_names = [];

				foreach ($package_ids_array as $id) {
					$id = (int)trim($id);

					if ($id > 0) {
						$sql_package = "SELECT * FROM hms_procedure_package WHERE procedure_id = $id";
						$select_package_result = run_select_query($sql_package);

						if (!empty($select_package_result) && isset($select_package_result['package_name'])) {
							$package_names[] = $select_package_result['package_name'];
						}
					}
				}

				$package_names = array_unique($package_names);
				
				$patient_id = $consultation_post['patient_id'];
				$fosql = "SELECT * FROM hms_appointments WHERE paitent_id = '$patient_id'";
				$fo_result = run_select_query($fosql);
				$lead_id = is_array($fo_result) ? $fo_result['crm_id'] : '';
				
				$doctor_id = $consultation_post['doctor_id'];
				$dosql = "SELECT * FROM hms_doctors WHERE ID = '$doctor_id'";
				$do_result = run_select_query($dosql);

				// Step 5: Final data array
				
				$curl = curl_init();
				$data = [
					"doctor" => $do_result['name'],
					"wife_phone" => $consultation_post['wife_phone'],
					"patient_id" => $patient_id,
					"appointment_id" => $consultation_post['appointment_id'],
					"female_investigation_suggestion_list" => $female_investigation,
					"male_minvestigation_suggestion_list" => $male_investigation,
					"package_suggestion_list" => implode(', ', $package_names),
					"sub_procedure_suggestion_list" => implode(', ', $procedure_name),
					"female_medicine_suggestion_list" => implode(', ', $item_name_female),
					"male_medicine_suggestion_list" => implode(', ', $item_name),
					"lead_id" => $lead_id
				];
					
						curl_setopt_array($curl, array(
						CURLOPT_URL => 'https://flertility.in/lead/consultations/',
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
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
						echo 'Error: ' . curl_error($curl);
					}

					curl_close($curl);
				
				$patient_medical_info['doctor_id'] = $_POST['doctor_id'];
				if($consultation_done > 0){
					if(isset($_POST['management_intervention'])){
						$_POST['management_intervention'] = serialize($_POST['management_intervention']);
					}
					//var_dump($patient_medical_info = $this->doctors_model->patient_medical_info($_POST));//die();
					
				$data = [
				    "hms_patient_id" => $patient_id,
					"primary_name_age" => $_POST['female_age'],
					"secondary_name_age" => $_POST['male_age'],
					"primary_name_gender" => "female",
					"secondary_name_gender" => "male",
					"primary_occupation" => $_POST['female_occupation'],
					"secondary_occupation" => $_POST['male_occupation'],
					"primary_education" => $_POST['female_education'],
					"secondary_education" => $_POST['male_education']
				];

				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://flertility.in/lead/lead-update/'.$lead_id.'/',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'PATCH',
				  CURLOPT_POSTFIELDS => json_encode($data),
				  CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				  ),
				));

				$response = curl_exec($curl);

				curl_close($curl);
				//echo $response;
				
				//var_dump($response); die();
				
				
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

					// Display lead info
					// echo "Lead ID: " . $lead['id'] . "<br>";
					// echo "Name: " . $lead['primary_name'] . "<br>";
					// echo "Mobile: " . $lead['mobile_country_code'] . " " . $lead['mobile'] . "<br>";
					// echo "Priority: " . $lead['priority'] . "<br>";
					// echo "Status: " . $lead['status'] . "<br>";

					// Update local DB (CodeIgniter style)
					$this->db->where('wife_phone', $lead['mobile']);
					$this->db->update('hms_appointments', ['crm_id' => $lead['id']]);

					// echo "CRM ID updated successfully.";
				} else {
					// echo "No lead data found.";
				}
			}
					
					$patient_medical_info = $this->doctors_model->patient_medical_info($_POST, $doctor_id);
					
					
					
					$appointment_status = $this->appointment_model->appointment_status('consultation_done', $consultation_post['appointment_id']);
					
					header("location:" .base_url().$redirect_url."?m=".base64_encode('Consultation done!').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url().$redirect_url."?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			
			$data = array();
			$data['appointments'] = $this->appointment_model->doctor_appointment_details($appointment_id);
			$data['consultation_medicine'] = $this->doctors_model->consultation_medicine();
		    $data['consultation_medicine_ipd'] = $this->doctors_model->consultation_medicine_ipd();
			$data['consultation_provisional_diagnosis'] = $this->doctors_model->consultation_provisional_diagnosis();
			$data['investigations'] = $this->investigation_model->get_investigations_list();
			$data['master_investigations'] = $this->investigation_model->get_master_investigations_list();
			$data['procedures'] = $this->procedures_model->get_procedures_list();
			$data['package'] = $this->procedures_model->get_procedure_package_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/consultation_done', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/**
	 * ORIGINAL FOLLOW-UP CONSULTATION FUNCTION
	 * 
	 * This function handles the follow-up consultation form submission and processing.
	 * It processes medicine suggestions, investigation suggestions, procedure suggestions,
	 * package suggestions, and follow-up appointment creation.
	 * 
	 * FUNCTIONALITY BREAKDOWN:
	 * 1. Medicine Processing (Lines 2040-2093):
	 *    - Extracts dynamic medicine fields (male_medicine_name_1, female_medicine_name_1, etc.)
	 *    - Builds medicine suggestion arrays with dosage, remarks, timing, etc.
	 *    - Serializes medicine data for database storage
	 * 
	 * 2. Investigation Processing (Lines 209    W5-2117):
	 *    - Handles male/female investigation suggestions	
	 *    - Handles master investigation suggestions (IIC investigations)
	 * 	 *    - Serializes investigation data

	 * 3. Procedure & Package Processing (Lines 2118-2124):
	 *    - Processes procedure suggestions
	 *    - Processes package suggestions
	 *    - Serializes data for storage
	 * 
	 * 4. Prescription Upload (Lines 2127-2133):
	 *    - Handles prescription file upload
	 *    - No validation for file type or size
	 * 
	 * 5. Follow-up Appointment (Lines 2190-2251):
	 *    - Creates follow-up appointments if requested
	 *    - Sends notifications via email, SMS, and WhatsApp
	 * 
	 * 6. External API Integration (Lines 2376-2423):
	 *    - Sends consultation data to external lead management systems
	 *    - Processes investigation, medicine, procedure, and package names
	 * 
	 * SECURITY ISSUES:
	 * - SQL injection vulnerabilities in database queries
	 * - No input validation for form data
	 * - No file upload validation
	 * - Uses addslashes() instead of prepared statements
	 * 
	 * NOTE: This function has security vulnerabilities and should be replaced with follow_up_enhanced()
	 * 
	 * @param int $appointment_id - The appointment ID for the consultation
	 * @return void
	 */
	function follow_up_old($appointment_id){
		// Check if user is logged in
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_consultation_done'){
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
							 'male_medicine_remarks' => $_POST['male_medicine_remarks_'.$val],
							 'male_medicine_when_start' => $_POST['male_medicine_when_start_'.$val],
							 'male_medicine_days' => $_POST['male_medicine_days_'.$val],
							 'male_medicine_route' => $_POST['male_medicine_route_'.$val],
							 'male_medicine_frequency' => $_POST['male_medicine_frequency_'.$val],
							 'male_medicine_timing' => $_POST['male_medicine_timing_'.$val],
							 'male_medicine_take' => $_POST['male_medicine_take_'.$val]
							);
						unset($_POST['male_medicine_name_'.$val]);
						unset($_POST['male_medicine_dosage_'.$val]);
						unset($_POST['male_medicine_remarks_'.$val]);
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
							 'female_medicine_remarks' => $_POST['female_medicine_remarks_'.$val],
							 'female_medicine_when_start' => $_POST['female_medicine_when_start_'.$val],
							 'female_medicine_days' => $_POST['female_medicine_days_'.$val],
							 'female_medicine_route' => $_POST['female_medicine_route_'.$val],
							 'female_medicine_frequency' => $_POST['female_medicine_frequency_'.$val],
							 'female_medicine_timing' => $_POST['female_medicine_timing_'.$val],
							 'female_medicine_take' => $_POST['female_medicine_take_'.$val]
						);
						unset($_POST['female_medicine_name_'.$val]);
						unset($_POST['female_medicine_dosage_'.$val]);
						unset($_POST['female_medicine_remarks_'.$val]);
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
					if(!empty($_POST['male_minvestigation_suggestion_list'])){
						$male_minvestigation_suggestion_list = array();
						$male_minvestigation_suggestion_list = $_POST['male_minvestigation_suggestion_list'];unset($_POST['male_minvestigation_suggestion_list']);
						$_POST['male_minvestigation_suggestion_list'] = serialize($male_minvestigation_suggestion_list);
					}
					if(!empty($_POST['female_minvestigation_suggestion_list'])){
						$female_minvestigation_suggestion_list = array();
						$female_minvestigation_suggestion_list = $_POST['female_minvestigation_suggestion_list'];unset($_POST['female_minvestigation_suggestion_list']);
						$_POST['female_minvestigation_suggestion_list'] = serialize($female_minvestigation_suggestion_list);
					}	
					
				}
				if(isset($_POST['procedure_suggestion'])){
					$_POST['sub_procedure_suggestion_list'] = serialize($_POST['sub_procedure_suggestion_list']);
				}
				
				if(isset($_POST['package_suggestion'])){
					$_POST['package_suggestion_list'] = serialize($_POST['package_suggestion_list']);
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
				$consultation_post['center_number'] = $_POST['center_number']; unset($_POST['center_number']);
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
				
				// Process IPD Medicine Suggestions for consultation_post (second occurrence)
				if(isset($_POST['medicine_suggestion_ipd'])){
					$consultation_post['medicine_suggestion_ipd'] = $_POST['medicine_suggestion_ipd']; unset($_POST['medicine_suggestion_ipd']);
					if(isset($male_medicine_suggestion_list_ipd) && !empty($male_medicine_suggestion_list_ipd)){
						$consultation_post['male_medicine_suggestion_list_ipd'] = $male_medicine_suggestion_list_ipd;
					} unset($_POST['male_medicine_suggestion_list_ipd']);
					if(isset($female_medicine_suggestion_list_ipd) && !empty($female_medicine_suggestion_list_ipd)){
						$consultation_post['female_medicine_suggestion_list_ipd'] = $female_medicine_suggestion_list_ipd; 
					}unset($_POST['female_medicine_suggestion_list_ipd']);
				}
				
				if(isset($_POST['investigation_suggestion'])){
					$consultation_post['investation_suggestion'] = $_POST['investigation_suggestion']; unset($_POST['investigation_suggestion']);
					if(isset($_POST['male_investigation_suggestion_list']) && !empty($_POST['male_investigation_suggestion_list'])){
						$consultation_post['male_investigation_suggestion_list'] = $_POST['male_investigation_suggestion_list'];
					} unset($_POST['male_investigation_suggestion_list']);
					if(isset($_POST['female_investigation_suggestion_list']) && !empty($_POST['female_investigation_suggestion_list'])){
						$consultation_post['female_investigation_suggestion_list'] = $_POST['female_investigation_suggestion_list'];
					} unset($_POST['female_investigation_suggestion_list']);
					if(isset($_POST['male_minvestigation_suggestion_list']) && !empty($_POST['male_minvestigation_suggestion_list'])){
						$consultation_post['male_minvestigation_suggestion_list'] = $_POST['male_minvestigation_suggestion_list'];
					}unset($_POST['male_minvestigation_suggestion_list']);
					if(isset($_POST['female_minvestigation_suggestion_list']) && !empty($_POST['female_minvestigation_suggestion_list'])){
						$consultation_post['female_minvestigation_suggestion_list'] = $_POST['female_minvestigation_suggestion_list'];
					} unset($_POST['female_minvestigation_suggestion_list']);
				}
				if(isset($_POST['procedure_suggestion'])){
    				$consultation_post['procedure_suggestion'] = $_POST['procedure_suggestion']; unset($_POST['procedure_suggestion']);
    				$consultation_post['procedure_suggestion_list'] = ''; 
    				$consultation_post['sub_procedure_suggestion_list'] = $_POST['sub_procedure_suggestion_list']; unset($_POST['sub_procedure_suggestion_list']);
				}
				if(isset($_POST['package_suggestion'])){
    				$consultation_post['package_suggestion'] = $_POST['package_suggestion']; unset($_POST['package_suggestion']);
    				//$consultation_post['package_suggestion_list'] = ''; 
    				$consultation_post['package_suggestion_list'] = $_POST['package_suggestion_list']; unset($_POST['package_suggestion_list']);
				}
				
				$consultation_post['final_mode'] = 1;

				$_POST['prescription'] = $prescription;
				$consultation_post['prescription'] = $prescription;
				$consultation_post['consultation_date'] = date("Y-m-d H:i:s");
				
				if($consultation_post['follow_up'] == 1){
				    if(empty($_POST['appoitment_for']) && empty($consultation_post['follow_up_date']) && empty($_POST['appoitmented_doctor']) && empty($consultation_post['follow_slot'])){
    				    header("location:" .base_url(). "doctor_appointments?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
    					die();
    				}
					$patient_details  = get_patient_detail($consultation_post['patient_id']);
					$doctor_details = doctor_details($consultation_post['doctor_id']);
					$appointment_arr = array();
					$appointment_arr['paitent_type'] = 'exist_patient';
					$appointment_arr['paitent_id'] = $consultation_post['patient_id'];
					$appointment_arr['wife_name'] = $patient_details['wife_name'];
					$appointment_arr['wife_phone'] = $consultation_post['wife_phone'];
					$appointment_arr['wife_email'] = $patient_details['wife_email'];;
					$appointment_arr['nationality'] = $patient_details['nationality'];;
					$appointment_arr['reason_of_visit'] = $consultation_post['follow_up_purpose'];
					$appointment_arr['appoitment_for'] = $_POST['appoitment_for'];unset($_POST['appoitment_for']);
					$appointment_arr['appoitmented_date'] = $consultation_post['follow_up_date'];
					$appointment_arr['appoitmented_doctor'] = $_POST['appoitmented_doctor'];unset($_POST['appoitmented_doctor']);
					$appointment_arr['appoitmented_slot'] = $consultation_post['follow_slot'];
					$appointment_arr['follow_up_appointment'] = 1;
					$appointment_arr['previous_appointment'] = $consultation_post['appointment_id'];
					$appointment_arr['appointment_added'] = date('Y-m-d H:i:s');
					$appointment = $this->billingmodel_model->insert_appointments($appointment_arr);
					if($appointment > 0){
						$centre_details = get_centre_details($appointment_arr['appoitment_for']);
						$appointwhatmsg = array();
						$appointwhatmsg = array($appointment_arr['wife_name'], $centre_details['center_name'], date("d-m-Y", strtotime($appointment_arr['appoitmented_date'])), $appointment_arr['appoitmented_slot'], isset($centre_details['center_location'])?$centre_details['center_location']:"");
						$appointsendmsg = whatsappappointment(
						$appointment_arr['wife_phone'], 
						$appointment_arr['wife_name'],
						$centre_details['center_name'],
						date("d-m-Y", strtotime($appointment_arr['appoitmented_date'])),
						$appointment_arr['appoitmented_slot'],
						isset($centre_details['center_location'])?$centre_details['center_location']:"",
					"appointment_confirmation");
						
						$doctor_details = doctor_details($appointment_arr['appoitmented_doctor']);
						$patient_to = $patient_subject = $patient_message = $doctor_to = $doctor_subject = $doctor_message = "";
						//Patient emails
						$patient_to = $patient_details['wife_email'];
						$patient_subject = "Followup appointment booked";
						$patient_message = "Hi ".$patient_details['wife_name'].",<br/> Your followup appointment is booked with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($consultation_post['follow_up_date']))." at ".$consultation_post['follow_slot'].".";
						// send_mail($patient_to, $patient_subject, $patient_message);
						
						$patient_phone = $patient_details['wife_phone'];
						$sms_message = "Hi ".$patient_details['wife_name'].", Your followup appointment is booked with Dr.".$doctor_details['name']." on ".date("d-m-Y", strtotime($consultation_post['follow_up_date']))." at ".$consultation_post['follow_slot'].".";
						send_sms($patient_phone, $sms_message);
						
						//Doctor emails
						$doctor_to = $doctor_details['email'];
						$doctor_subject = "Followup appointment";
						$doctor_message = "Hi Dr.".$doctor_details['name'].",<br/> Followup Appointment is booked on ".date("d-m-Y", strtotime($consultation_post['follow_up_date']))." at ".$consultation_post['follow_slot'].".";
						// send_mail($doctor_to, $doctor_subject, $doctor_message);
					}
				}
				$consultation_done = $this->doctors_model->consultation_done($consultation_post);//var_dump($consultation_done);die;
				$female_ids_serialized = $consultation_post['female_minvestigation_suggestion_list'];
				$unserializedArray = unserialize($female_ids_serialized);
				$investigation_names = [];
				if (is_array($unserializedArray)) {
					foreach ($unserializedArray as $key => $value) {
						$value = (int)$value; // ensure it's safe
						$sql2 = "SELECT * FROM `hms_master_investigations` WHERE ID = $value";
						$select_result9 = run_select_query($sql2);

						if (!empty($select_result9) && isset($select_result9['investigation_name'])) {
							$investigation_names[] = $select_result9['investigation_name'];
						}
					}
				}
				
				$male_ids_serialized = $consultation_post['male_minvestigation_suggestion_list'];
				$unserializedArray = unserialize($male_ids_serialized);

				$investigation_male_names = [];

				if (is_array($unserializedArray)) {
					foreach ($unserializedArray as $key => $value) {
						$value = (int)$value; // ensure it's safe
						$sql_male = "SELECT * FROM `hms_master_investigations` WHERE ID = $value";
						$select_male_result = run_select_query($sql_male);

						if (!empty($select_male_result) && isset($select_male_result['investigation_name'])) {
							$investigation_male_names[] = $select_male_result['investigation_name'];
						}
					}
				}
				
				$procedure_ids_serialized = $consultation_post['sub_procedure_suggestion_list'];
				$unserializedArray = unserialize($procedure_ids_serialized);

				$procedure_name = [];

				if (is_array($unserializedArray)) {
					foreach ($unserializedArray as $key => $value) {
						$value = (int)$value; // ensure it's safe
						$sql_procedure = "SELECT * FROM `hms_procedures` WHERE ID = $value";
						$select_procedure_result = run_select_query($sql_procedure);

						if (!empty($select_procedure_result) && isset($select_procedure_result['procedure_name'])) {
							$procedure_name[] = $select_procedure_result['procedure_name'];
						}
					}
				}
				
				$male_medicine_ids_serialized = $consultation_post['male_medicine_suggestion_list'];
				$unserializedArray = unserialize($male_medicine_ids_serialized);
				
				$item_name = [];

				foreach ($unserializedArray as $valueGroup) {
					foreach ($valueGroup as $value) {
						if (!empty($value['male_medicine_name']) && is_numeric($value['male_medicine_name'])) {
							$item_number = (int)$value['male_medicine_name'];

							$sql_medicine = "SELECT * FROM hms_stocks WHERE item_number = $item_number";
							$select_medicine_result = run_select_query($sql_medicine);

							if (!empty($select_medicine_result) && isset($select_medicine_result['item_name'])) {
								$item_name[] = $select_medicine_result['item_name'];
							}
						}
					}
				}
				
				$female_medicine_ids_serialized = $consultation_post['female_medicine_suggestion_list'];
				$unserializedArray = unserialize($female_medicine_ids_serialized);
				
				$item_name_female = [];

				foreach ($unserializedArray as $valueGroup) {
					foreach ($valueGroup as $value) {
						if (!empty($value['female_medicine_name']) && is_numeric($value['female_medicine_name'])) {
							$item_number = (int)$value['female_medicine_name'];

							$sql_medicine = "SELECT * FROM hms_stocks WHERE item_number = $item_number";
							$select_femedicine_result = run_select_query($sql_medicine);

							if (!empty($select_femedicine_result) && isset($select_femedicine_result['item_name'])) {
								$item_name_female[] = $select_femedicine_result['item_name'];
							}
						}
					}
				}
				$female_investigation =  implode(', ', $investigation_names);
				$male_investigation =  implode(', ', $investigation_male_names);
				
				
				$package_ids_string = $consultation_post['package_suggestion_list'];
				$package_ids_array = explode(',', $package_ids_string);

				$package_names = [];

				foreach ($package_ids_array as $id) {
					$id = (int)trim($id);

					if ($id > 0) {
						$sql_package = "SELECT * FROM hms_procedure_package WHERE procedure_id = $id";
						$select_package_result = run_select_query($sql_package);

						if (!empty($select_package_result) && isset($select_package_result['package_name'])) {
							$package_names[] = $select_package_result['package_name'];
						}
					}
				}

				$package_names = array_unique($package_names);
				
				$patient_id = $consultation_post['patient_id'];
				$fosql = "SELECT * FROM hms_appointments WHERE paitent_id = '$patient_id'";
				$fo_result = run_select_query($fosql);
				$lead_id = is_array($fo_result) ? $fo_result['crm_id'] : '';
				
				$doctor_id = $consultation_post['doctor_id'];
				$dosql = "SELECT * FROM hms_doctors WHERE ID = '$doctor_id'";
				$do_result = run_select_query($dosql);

				// Step 5: Final data array
				
				$curl = curl_init();
				$data = [
					"doctor" => $do_result['name'],
					"wife_phone" => $consultation_post['wife_phone'],
					"patient_id" => $patient_id,
					"appointment_id" => $consultation_post['appointment_id'],
					"female_investigation_suggestion_list" => $female_investigation,
					"male_minvestigation_suggestion_list" => $male_investigation,
					"package_suggestion_list" => implode(', ', $package_names),
					"sub_procedure_suggestion_list" => implode(', ', $procedure_name),
					"female_medicine_suggestion_list" => implode(', ', $item_name_female),
					"male_medicine_suggestion_list" => implode(', ', $item_name),
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
					
					//echo ($response);die();
				
				if($consultation_done > 0){
				    if(isset($_POST['advisory_templates']) && !empty($_POST['advisory_templates'])){
						$patient_details  = get_patient_detail($consultation_post['patient_id']);
						$patient_to = $patient_details['wife_email'];
						$patient_name = $patient_details['wife_name'];

						$advisory_subject = "IVF related advisory";
						$advisory_message = "Hi ".$patient_name.", Hope you are doing well!<br/>Here are some suggestion you can follow for successfull IVF. Please find the attached instruction below.<br/> Thanks & Regards<br/> IndiaIVF";
						$advisory_templates = implode(',', $_POST['advisory_templates']);
						// send_mail($patient_to, $advisory_subject, $advisory_message, $advisory_templates);
					}
					$appointment_status = $this->appointment_model->appointment_status('consultation_done', $consultation_post['appointment_id']);
					header("location:" .base_url(). "doctor_appointments?m=".base64_encode('Consultation done!').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "doctor_appointments?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}

			$data = array();
			$data['appointments'] = $this->appointment_model->doctor_appointment_details($appointment_id);
			$data['consultation_medicine'] = $this->doctors_model->consultation_medicine();
			$data['investigations'] = $this->investigation_model->get_investigations_list();
			$data['master_investigations'] = $this->investigation_model->get_master_investigations_list();
			$data['procedures'] = $this->procedures_model->get_procedures_list();
			$data['package'] = $this->procedures_model->get_procedure_package_list();			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('appointments/follow_up', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/**
	 * Enhanced follow-up consultation processing with proper validation and error handling
	 * This function maintains the same functionality as follow_up() but with improved data processing
	 * 
	 * @param int $appointment_id - The appointment ID for the consultation
	 * @return void
	 */
	
	// function follow_up($appointment_id){
	// 	$logg = checklogin();
	// 	if($logg['status'] == true){
	// 		if(isset($_POST['action']) && $_POST['action'] == 'add_consultation_done'){
	// 			// Process consultation data with validation
	// 			$consultation_data = $this->process_consultation_data();
	// 			if($consultation_data['status'] == 'error') {
	// 				header("location:" .base_url(). "doctor_appointments?m=".base64_encode($consultation_data['message']).'&t='.base64_encode('error'));
	// 				die();
	// 			}
	// 			// Process follow-up appointment if required
	// 			if($consultation_data['data']['follow_up'] == 1) {
	// 				$followup_result = $this->process_followup_appointment($consultation_data['data']);
	// 				if($followup_result['status'] == 'error') {
	// 					header("location:" .base_url(). "doctor_appointments?m=".base64_encode($followup_result['message']).'&t='.base64_encode('error'));
	// 					die();
	// 				}
	// 			}
	// 			// Save consultation data
	// 			$consultation_done = $this->doctors_model->consultation_done($consultation_data['data']);
	// 			if($consultation_done > 0) {
	// 				// Process external API calls
	// 				// $this->send_consultation_to_external_apis($consultation_data['data']);
	// 				// Send advisory emails if templates selected
	// 				$this->send_advisory_emails($consultation_data['data']);
	// 				// Update appointment status
	// 				$this->appointment_model->appointment_status('consultation_done', $consultation_data['data']['appointment_id']);
	// 				header("location:" .base_url(). "doctor_appointments?m=".base64_encode('Consultation done!').'&t='.base64_encode('success'));
	// 				die();
	// 			} else {
	// 				header("location:" .base_url(). "doctor_appointments?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
	// 				die();
	// 			}
	// 		}
	// 		// Load view data (same as original)
	// 		$data = array();
	// 		$data['appointments'] = $this->appointment_model->doctor_appointment_details($appointment_id);
	// 		$data['consultation_medicine'] = $this->doctors_model->consultation_medicine();
	// 		$data['investigations'] = $this->investigation_model->get_investigations_list();
	// 		$data['master_investigations'] = $this->investigation_model->get_master_investigations_list();
	// 		$data['procedures'] = $this->procedures_model->get_procedures_list();
	// 		$data['package'] = $this->procedures_model->get_procedure_package_list();			
	// 		$template = get_header_template($logg['role']);
	// 		$this->load->view($template['header']);
	// 		$this->load->view('appointments/follow_up', $data);
	// 		$this->load->view($template['footer']);
	// 	} else {
	// 		header("location:" .base_url(). "");
	// 		die();
	// 	}
	// }
	// private function process_consultation_data() {
	// 	try {
	// 		// Debug: Log all POST data
	// 		log_message('debug', 'Follow-up POST data: ' . print_r($_POST, true));
			
	// 		// Remove action from POST data
	// 		unset($_POST['action']);
	// 		// Initialize consultation data array
	// 		$consultation_post = array();
	// 		// Process basic consultation data
	// 		$consultation_post['appointment_id'] = $this->input->post('appointment_id', TRUE);
	// 		$consultation_post['patient_id'] = $this->input->post('patient_id', TRUE);
	// 		$consultation_post['wife_phone'] = $this->input->post('wife_phone', TRUE);
	// 		$consultation_post['doctor_id'] = $this->input->post('doctor_id', TRUE);
	// 		$consultation_post['center_number'] = $this->input->post('center_number', TRUE);
	// 		$consultation_post['female_findings'] = $this->input->post('female_findings', TRUE) ?: '';
	// 		$consultation_post['male_findings'] = $this->input->post('male_findings', TRUE) ?: '';
	// 		$consultation_post['follow_up'] = $this->input->post('follow_up', TRUE) ?: '';
	// 		$consultation_post['follow_up_date'] = $this->input->post('follow_up_date', TRUE) ?: '';
	// 		$consultation_post['follow_slot'] = $this->input->post('appoitmented_slot', TRUE) ?: '';
	// 		$consultation_post['follow_up_purpose'] = $this->input->post('follow_up_purpose', TRUE) ?: '';
			
	// 		// Debug: Log processed basic data
	// 		log_message('debug', 'Processed basic consultation data: ' . print_r($consultation_post, true));
	// 		// Validate required fields
	// 		$required_fields = ['appointment_id', 'patient_id', 'doctor_id', 'center_number'];
	// 		foreach($required_fields as $field) {
	// 			if(empty($consultation_post[$field])) {
	// 				return ['status' => 'error', 'message' => 'Required field missing: ' . $field];
	// 			}
	// 		}
	// 		// Process medicine suggestions
	// 		if($this->input->post('medicine_suggestion')) {
	// 			$medicine_data = $this->process_medicine_suggestions();
	// 			if($medicine_data['status'] == 'error') {
	// 				return $medicine_data;
	// 			}
	// 			$consultation_post = array_merge($consultation_post, $medicine_data['data']);
	// 		}
	// 		// Process investigation suggestions
	// 		if($this->input->post('investigation_suggestion')) {
	// 			$investigation_data = $this->process_investigation_suggestions();
	// 			if($investigation_data['status'] == 'error') {
	// 				return $investigation_data;
	// 			}
	// 			$consultation_post = array_merge($consultation_post, $investigation_data['data']);
	// 		}
	// 		// Process procedure suggestions
	// 		if($this->input->post('procedure_suggestion')) {
	// 			$procedure_data = $this->process_procedure_suggestions();
	// 			if($procedure_data['status'] == 'error') {
	// 				return $procedure_data;
	// 			}
	// 			$consultation_post = array_merge($consultation_post, $procedure_data['data']);
	// 		}
	// 		// Process package suggestions
	// 		if($this->input->post('package_suggestion')) {
	// 			$package_data = $this->process_package_suggestions();
	// 			if($package_data['status'] == 'error') {
	// 				return $package_data;
	// 			}
	// 			$consultation_post = array_merge($consultation_post, $package_data['data']);
	// 		}
	// 		// Process prescription file upload
	// 		$prescription = $this->process_prescription_upload();
	// 		$consultation_post['prescription'] = $prescription;
	// 		// Set final consultation data
	// 		$consultation_post['final_mode'] = 1;
	// 		$consultation_post['consultation_date'] = date("Y-m-d H:i:s");
	// 		return ['status' => 'success', 'data' => $consultation_post];
			
	// 	} catch (Exception $e) {
	// 		return ['status' => 'error', 'message' => 'Error processing consultation data: ' . $e->getMessage()];
	// 	}
	// }
	
	
	// private function process_medicine_suggestions() {
	// 		$medicine_data = array();
	// 		$medicine_data['medicine_suggestion'] = 1;
	// 		$male_multiselect_data = $this->input->post('male_medicine_suggestion_list');
	// 		$female_multiselect_data = $this->input->post('female_medicine_suggestion_list');
			
	// 		if($male_multiselect_data) {
	// 			$medicine_data['male_medicine_suggestion_list'] = serialize($male_multiselect_data);
	// 		}
	// 		if($female_multiselect_data) {
	// 			$medicine_data['female_medicine_suggestion_list'] = serialize($female_multiselect_data);
	// 		}
	// 		// Process OPD male medicine suggestions (detailed with dosage, timing, etc.)
	// 		$male_medicine_data = $this->extract_medicine_data('male');
	// 		if($male_medicine_data['status'] == 'error') {
	// 			return $male_medicine_data;
	// 		}
	// 		// Only overwrite if we have detailed data, otherwise create from multiselect data
	// 		if($male_medicine_data['data'] && $male_medicine_data['data'] !== 'a:0:{}') {
	// 			$medicine_data['male_medicine_suggestion_list'] = $male_medicine_data['data'];
	// 		} elseif($male_multiselect_data) {
	// 			// Create detailed data from multiselect values
	// 			$medicine_data['male_medicine_suggestion_list'] = $this->create_medicine_data_from_multiselect($male_multiselect_data, 'male');
	// 		}
			
	// 		// Process OPD female medicine suggestions (detailed with dosage, timing, etc.)
	// 		$female_medicine_data = $this->extract_medicine_data('female');
	// 		if($female_medicine_data['status'] == 'error') {
	// 			return $female_medicine_data;
	// 		}
	// 		// Only overwrite if we have detailed data, otherwise create from multiselect data
	// 		if($female_medicine_data['data'] && $female_medicine_data['data'] !== 'a:0:{}') {
	// 			$medicine_data['female_medicine_suggestion_list'] = $female_medicine_data['data'];
	// 		} elseif($female_multiselect_data) {
	// 			// Create detailed data from multiselect values
	// 			$medicine_data['female_medicine_suggestion_list'] = $this->create_medicine_data_from_multiselect($female_multiselect_data, 'female');
	// 		}
			
	// 		// Process IPD medicine suggestions if present
	// 		if($this->input->post('medicine_suggestion_ipd')) {
	// 			$medicine_data['medicine_suggestion_ipd'] = 1;
	// 			// Process basic multiselect IPD medicine values
	// 			$male_ipd_multiselect_data = $this->input->post('male_medicine_suggestion_list_ipd');
	// 			$female_ipd_multiselect_data = $this->input->post('female_medicine_suggestion_list_ipd');
				
	// 			if($male_ipd_multiselect_data) {
	// 				$medicine_data['male_medicine_suggestion_list_ipd'] = serialize($male_ipd_multiselect_data);
	// 			}
	// 			if($female_ipd_multiselect_data) {
	// 				$medicine_data['female_medicine_suggestion_list_ipd'] = serialize($female_ipd_multiselect_data);
	// 			}
				
	// 			// Process IPD male medicine suggestions (detailed with dosage, timing, etc.)
	// 			$male_medicine_ipd_data = $this->extract_medicine_data('male', '_ipd');
	// 			if($male_medicine_ipd_data['status'] == 'error') {
	// 				return $male_medicine_ipd_data;
	// 			}
	// 			if($male_medicine_ipd_data['data'] && $male_medicine_ipd_data['data'] !== 'a:0:{}') {
	// 				$medicine_data['male_medicine_suggestion_list_ipd'] = $male_medicine_ipd_data['data'];
	// 			} elseif($male_ipd_multiselect_data) {
	// 				// Create detailed data from multiselect values
	// 				$medicine_data['male_medicine_suggestion_list_ipd'] = $this->create_medicine_data_from_multiselect($male_ipd_multiselect_data, 'male');
	// 			}
	// 			$female_medicine_ipd_data = $this->extract_medicine_data('female', '_ipd');
	// 			if($female_medicine_ipd_data['status'] == 'error') {
	// 				return $female_medicine_ipd_data;
	// 			}
	// 			if($female_medicine_ipd_data['data'] && $female_medicine_ipd_data['data'] !== 'a:0:{}') {
	// 				$medicine_data['female_medicine_suggestion_list_ipd'] = $female_medicine_ipd_data['data'];
	// 			} elseif($female_ipd_multiselect_data) {
	// 				// Create detailed data from multiselect values
	// 				$medicine_data['female_medicine_suggestion_list_ipd'] = $this->create_medicine_data_from_multiselect($female_ipd_multiselect_data, 'female');
	// 			}
	// 		}
	// 		return ['status' => 'success', 'data' => $medicine_data];
		
	// }
	
	
	// private function extract_medicine_data($gender, $suffix = '') {
	// 		$medicine_array = array();
	// 		$medicine_numbers = array();
	// 		$field_prefix = $gender . '_medicine_name' . $suffix;

	// 		$pattern = '/^' . preg_quote($field_prefix, '/') . '_(\d+)$/';
	// 		foreach($_POST as $key => $val) {
	// 			if (preg_match($pattern, $key, $matches)) {
	// 				$medicine_array[] = $key;
	// 				$medicine_numbers[] = $matches[1];
	// 			}
	// 		}
	// 		$medicine_numbers = array_unique($medicine_numbers);
	// 		if(empty($medicine_numbers)) {
	// 			return ['status' => 'success', 'data' => serialize(array())];
	// 		}
			
	// 		// Build medicine suggestion list
	// 		$medicine_suggestion_list = array();
	// 		foreach($medicine_numbers as $key => $val) {
	// 			$medicine_suggestion_list[$gender . '_medicine_suggestion_list'][] = array(
	// 				$gender . '_medicine_name' => $this->input->post($gender . '_medicine_name' . $suffix . '_' . $val, TRUE),
	// 				$gender . '_medicine_dosage' => $this->input->post($gender . '_medicine_dosage' . $suffix . '_' . $val, TRUE),
	// 				$gender . '_medicine_remarks' => $this->input->post($gender . '_medicine_remarks' . $suffix . '_' . $val, TRUE),
	// 				$gender . '_medicine_when_start' => $this->input->post($gender . '_medicine_when_start' . $suffix . '_' . $val, TRUE),
	// 				$gender . '_medicine_days' => $this->input->post($gender . '_medicine_days' . $suffix . '_' . $val, TRUE),
	// 				$gender . '_medicine_route' => $this->input->post($gender . '_medicine_route' . $suffix . '_' . $val, TRUE),
	// 				$gender . '_medicine_frequency' => $this->input->post($gender . '_medicine_frequency' . $suffix . '_' . $val, TRUE),
	// 				$gender . '_medicine_timing' => $this->input->post($gender . '_medicine_timing' . $suffix . '_' . $val, TRUE),
	// 				$gender . '_medicine_take' => $this->input->post($gender . '_medicine_take' . $suffix . '_' . $val, TRUE)
	// 			);
	// 		}
	// 		return ['status' => 'success', 'data' => serialize($medicine_suggestion_list)];
	// }
	
	/**
	 * Create medicine data from multiselect values when detailed data is not available
	 * 
	 * @param array $multiselect_data - Array of medicine IDs from multiselect
	 * @param string $gender - 'male' or 'female'
	 * @return string - Serialized medicine data
	 */
	// private function create_medicine_data_from_multiselect($multiselect_data, $gender) {
	// 	if(empty($multiselect_data) || !is_array($multiselect_data)) {
	// 		return serialize(array());
	// 	}
		
	// 	$medicine_suggestion_list = array();
	// 	foreach($multiselect_data as $index => $medicine_id) {
	// 		if($medicine_id && $medicine_id !== '0') {
	// 			$medicine_suggestion_list[$gender . '_medicine_suggestion_list'][] = array(
	// 				$gender . '_medicine_name' => $medicine_id,
	// 				$gender . '_medicine_dosage' => '',
	// 				$gender . '_medicine_remarks' => '',
	// 				$gender . '_medicine_when_start' => '',
	// 				$gender . '_medicine_days' => '',
	// 				$gender . '_medicine_route' => 'PO',
	// 				$gender . '_medicine_frequency' => 'OD',
	// 				$gender . '_medicine_timing' => 'AFTER MEAL',
	// 				$gender . '_medicine_take' => 'Daily'
	// 			);
	// 		}
	// 	}
	// 	return serialize($medicine_suggestion_list);
	// }
	
	/**
	 * Process investigation suggestions
	 * 
	 * @return array - Investigation data with status
	 */
	private function process_investigation_suggestions() {
		try {
			$investigation_data = array();
			$investigation_data['investation_suggestion'] = 1; // Note: keeping original typo for compatibility
			// Process male investigation suggestions
			if($this->input->post('male_investigation_suggestion_list')) {
				$investigation_data['male_investigation_suggestion_list'] = serialize($this->input->post('male_investigation_suggestion_list'));
			}
			// Process female investigation suggestions
			if($this->input->post('female_investigation_suggestion_list')) {
				$investigation_data['female_investigation_suggestion_list'] = serialize($this->input->post('female_investigation_suggestion_list'));
			}
			// Process male master investigation suggestions
			if($this->input->post('male_minvestigation_suggestion_list')) {
				$investigation_data['male_minvestigation_suggestion_list'] = serialize($this->input->post('male_minvestigation_suggestion_list'));
			}
			// Process female master investigation suggestions
			if($this->input->post('female_minvestigation_suggestion_list')) {
				$investigation_data['female_minvestigation_suggestion_list'] = serialize($this->input->post('female_minvestigation_suggestion_list'));
			}
			return ['status' => 'success', 'data' => $investigation_data];
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => 'Error processing investigation suggestions: ' . $e->getMessage()];
		}
	}
	
	/**
	 * Process procedure suggestions
	 * 
	 * @return array - Procedure data with status
	 */
	private function process_procedure_suggestions() {
		try {
			$procedure_data = array();
			$procedure_data['procedure_suggestion'] = 1;
			$procedure_data['procedure_suggestion_list'] = '';
			$procedure_data['sub_procedure_suggestion_list'] = serialize($this->input->post('sub_procedure_suggestion_list'));
			return ['status' => 'success', 'data' => $procedure_data];
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => 'Error processing procedure suggestions: ' . $e->getMessage()];
		}
	}
	
	/**
	 * Process package suggestions
	 * 
	 * @return array - Package data with status
	 */
	private function process_package_suggestions() {
		try {
			$package_data = array();
			$package_data['package_suggestion'] = 1;
			$package_data['package_suggestion_list'] = serialize($this->input->post('package_suggestion_list'));
			return ['status' => 'success', 'data' => $package_data];
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => 'Error processing package suggestions: ' . $e->getMessage()];
		}
	}
	
	/**
	 * Process prescription file upload with validation
	 * 
	 * @return string - Prescription file path or empty string
	 */
	private function process_prescription_upload() {
		try {
			if(!empty($_FILES['prescription']['tmp_name'])) {
				// Validate file type
				$allowed_types = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
				$file_extension = strtolower(pathinfo($_FILES['prescription']['name'], PATHINFO_EXTENSION));
				if(!in_array($file_extension, $allowed_types)) {
					throw new Exception('Invalid file type. Allowed types: ' . implode(', ', $allowed_types));
				}
				// Validate file size (max 5MB)
				if($_FILES['prescription']['size'] > 5 * 1024 * 1024) {
					throw new Exception('File size too large. Maximum size is 5MB.');
				}
				$dest_path = $this->config->item('upload_path');
				$destination = $dest_path . 'patient_files/';
				// Create directory if it doesn't exist
				if(!is_dir($destination)) {
					mkdir($destination, 0755, true);
				}
				$NewImageName = rand(4, 10000) . "-" . $this->input->post('patient_id') . "-" . $_FILES['prescription']['name'];
				$prescription = base_url() . 'assets/patient_files/' . $NewImageName;
				if(move_uploaded_file($_FILES['prescription']['tmp_name'], $destination . $NewImageName)) {
					return $prescription;
				} else {
					throw new Exception('Failed to upload prescription file.');
				}
			}
			
			return '';
			
		} catch (Exception $e) {
			// Log error but don't stop processing
			log_message('error', 'Prescription upload error: ' . $e->getMessage());
			return '';
		}
	}
	
	/**
	 * Process follow-up appointment creation
	 * 
	 * @param array $consultation_data - Consultation data
	 * @return array - Follow-up result with status
	 */
	private function process_followup_appointment($consultation_data) {
		try {
			// Validate follow-up appointment data
			if(empty($this->input->post('appoitment_for')) || 
			   empty($consultation_data['follow_up_date']) || 
			   empty($this->input->post('appoitmented_doctor')) || 
			   empty($consultation_data['follow_slot'])) {
				return ['status' => 'error', 'message' => 'Follow-up appointment data incomplete'];
			}
			$patient_details = get_patient_detail($consultation_data['patient_id']);
			$doctor_details = doctor_details($consultation_data['doctor_id']);
			$appointment_arr = array();
			$appointment_arr['paitent_type'] = 'exist_patient';
			$appointment_arr['paitent_id'] = $consultation_data['patient_id'];
			$appointment_arr['wife_name'] = $patient_details['wife_name'];
			$appointment_arr['wife_phone'] = $consultation_data['wife_phone'];
			$appointment_arr['wife_email'] = $patient_details['wife_email'];
			$appointment_arr['nationality'] = $patient_details['nationality'];
			$appointment_arr['reason_of_visit'] = $consultation_data['follow_up_purpose'];
			$appointment_arr['appoitment_for'] = $this->input->post('appoitment_for');
			$appointment_arr['appoitmented_date'] = $consultation_data['follow_up_date'];
			$appointment_arr['appoitmented_doctor'] = $this->input->post('appoitmented_doctor');
			$appointment_arr['appoitmented_slot'] = $consultation_data['follow_slot'];
			$appointment_arr['follow_up_appointment'] = 1;
			$appointment_arr['previous_appointment'] = $consultation_data['appointment_id'];
			$appointment_arr['appointment_added'] = date('Y-m-d H:i:s');
			$appointment = $this->billingmodel_model->insert_appointments($appointment_arr);
			if($appointment > 0) {
				// Send notifications
				$this->send_followup_notifications($appointment_arr, $patient_details, $doctor_details);
				return ['status' => 'success', 'message' => 'Follow-up appointment created successfully'];
			} else {
				return ['status' => 'error', 'message' => 'Failed to create follow-up appointment'];
			}
			
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => 'DONE'];
		}
	}
	
	/**
	 * Send follow-up appointment notifications
	 * 
	 * @param array $appointment_arr - Appointment data
	 * @param array $patient_details - Patient details
	 * @param array $doctor_details - Doctor details
	 */
	private function send_followup_notifications($appointment_arr, $patient_details, $doctor_details) {
		try {
			$centre_details = get_centre_details($appointment_arr['appoitment_for']);
			// Send WhatsApp notification
			whatsappappointment(
				$appointment_arr['wife_phone'], 
				$appointment_arr['wife_name'],
				$centre_details['center_name'],
				date("d-m-Y", strtotime($appointment_arr['appoitmented_date'])),
				$appointment_arr['appoitmented_slot'],
				isset($centre_details['center_location']) ? $centre_details['center_location'] : "",
				"appointment_confirmation"
			);
			// Send patient email
			$patient_to = $patient_details['wife_email'];
			$patient_subject = "Followup appointment booked";
			$patient_message = "Hi " . $patient_details['wife_name'] . ",<br/> Your followup appointment is booked with Dr." . $doctor_details['name'] . " on " . date("d-m-Y", strtotime($appointment_arr['appoitmented_date'])) . " at " . $appointment_arr['appoitmented_slot'] . ".";
			// send_mail($patient_to, $patient_subject, $patient_message);
			// Send patient SMS
			$patient_phone = $patient_details['wife_phone'];
			$sms_message = "Hi " . $patient_details['wife_name'] . ", Your followup appointment is booked with Dr." . $doctor_details['name'] . " on " . date("d-m-Y", strtotime($appointment_arr['appoitmented_date'])) . " at " . $appointment_arr['appoitmented_slot'] . ".";
			send_sms($patient_phone, $sms_message);
			// Send doctor email
			$doctor_to = $doctor_details['email'];
			$doctor_subject = "Followup appointment";
			$doctor_message = "Hi Dr." . $doctor_details['name'] . ",<br/> Followup Appointment is booked on " . date("d-m-Y", strtotime($appointment_arr['appoitmented_date'])) . " at " . $appointment_arr['appoitmented_slot'] . ".";
			// send_mail($doctor_to, $doctor_subject, $doctor_message);
		} catch (Exception $e) {
			log_message('error', 'Error sending follow-up notifications: ' . $e->getMessage());
		}
	}
	
	/**
	 * Send consultation data to external APIs
	 * 
	 * @param array $consultation_data - Consultation data
	 */
	private function send_consultation_to_external_apis($consultation_data) {
		try {
			// Get investigation names for external API
			$investigation_data = $this->get_investigation_names_for_api($consultation_data);
			$medicine_data = $this->get_medicine_names_for_api($consultation_data);
			$procedure_data = $this->get_procedure_names_for_api($consultation_data);
			$package_data = $this->get_package_names_for_api($consultation_data);
			// Get lead ID
			$patient_id = $consultation_data['patient_id'];
			$fosql = "SELECT * FROM hms_appointments WHERE paitent_id = ?";
			$fo_result = $this->db->query($fosql, [$patient_id])->row_array();
			$lead_id = isset($fo_result['crm_id']) ? $fo_result['crm_id'] : '';
			// Get doctor details
			$doctor_id = $consultation_data['doctor_id'];
			$dosql = "SELECT * FROM hms_doctors WHERE ID = ?";
			$do_result = $this->db->query($dosql, [$doctor_id])->row_array();
			$data = [
				"doctor" => isset($do_result['name']) ? $do_result['name'] : '',
				"wife_phone" => isset($consultation_data['wife_phone']) ? $consultation_data['wife_phone'] : '',
				"patient_id" => $patient_id,
				"appointment_id" => $consultation_data['appointment_id'],
				"female_investigation_suggestion_list" => isset($investigation_data['female']) ? $investigation_data['female'] : [],
				"male_minvestigation_suggestion_list" => isset($investigation_data['male']) ? $investigation_data['male'] : [],
				"package_suggestion_list" => $package_data,
				"sub_procedure_suggestion_list" => $procedure_data,
				"female_medicine_suggestion_list" => isset($medicine_data['female']) ? $medicine_data['female'] : [],
				"male_medicine_suggestion_list" => isset($medicine_data['male']) ? $medicine_data['male'] : [],
				"lead_id" => $lead_id
			];
			$urls = [
				'lead_1' => 'https://flertility.in/lead/consultations/',
				'lead_2' => 'https://staging.flertility.in/lead/consultations/'
			];
			foreach ($urls as $key => $url) {
				$this->send_api_request($url, $data, $key);
			}
		} catch (Exception $e) {
			log_message('error', 'Error sending consultation to external APIs: ' . $e->getMessage());
		}
	}
	
	/**
	 * Send API request to external service
	 * 
	 * @param string $url - API URL
	 * @param array $data - Data to send
	 * @param string $key - API key identifier
	 */
	private function send_api_request($url, $data, $key) {
		try {
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
				log_message('error', "[$key] API Error: " . curl_error($curl));
			} else {
				log_message('info', "[$key] API Response: " . $response);
			}
			curl_close($curl);
			
		} catch (Exception $e) {
			log_message('error', "[$key] API Exception: " . $e->getMessage());
		}
	}
	/**
	 * Send advisory emails if templates are selected
	 * 
	 * @param array $consultation_data - Consultation data
	 */
	private function send_advisory_emails($consultation_data) {
		try {
			if($this->input->post('advisory_templates')) {
				$patient_details = get_patient_detail($consultation_data['patient_id']);
				$patient_to = $patient_details['wife_email'];
				$patient_name = $patient_details['wife_name'];
				$advisory_subject = "IVF related advisory";
				$advisory_message = "Hi " . $patient_name . ", Hope you are doing well!<br/>Here are some suggestion you can follow for successfull IVF. Please find the attached instruction below.<br/> Thanks & Regards<br/> IndiaIVF";
				$advisory_templates = implode(',', $this->input->post('advisory_templates'));
				// send_mail($patient_to, $advisory_subject, $advisory_message, $advisory_templates);
			}
		} catch (Exception $e) {
			log_message('error', 'Error sending advisory emails: ' . $e->getMessage());
		}
	}
	
	/**
	 * Get investigation names for external API
	 * 
	 * @param array $consultation_data - Consultation data
	 * @return array - Investigation names
	 */
	private function get_investigation_names_for_api($consultation_data) {
		$result = ['female' => '', 'male' => ''];
		try {
			// Female investigations
			if(isset($consultation_data['female_minvestigation_suggestion_list'])) {
				$female_ids = unserialize($consultation_data['female_minvestigation_suggestion_list']);
				$investigation_names = [];
				if (is_array($female_ids)) {
					foreach ($female_ids as $value) {
						$value = (int)$value;
						$sql = "SELECT * FROM `hms_master_investigations` WHERE ID = ?";
						$select_result = $this->db->query($sql, [$value])->row_array();
						if (!empty($select_result) && isset($select_result['investigation_name'])) {
							$investigation_names[] = $select_result['investigation_name'];
						}
					}
				}
				$result['female'] = implode(', ', $investigation_names);
			}
			// Male investigations
			if(isset($consultation_data['male_minvestigation_suggestion_list'])) {
				$male_ids = unserialize($consultation_data['male_minvestigation_suggestion_list']);
				$investigation_names = [];
				if (is_array($male_ids)) {
					foreach ($male_ids as $value) {
						$value = (int)$value;
						$sql = "SELECT * FROM `hms_master_investigations` WHERE ID = ?";
						$select_result = $this->db->query($sql, [$value])->row_array();
						if (!empty($select_result) && isset($select_result['investigation_name'])) {
							$investigation_names[] = $select_result['investigation_name'];
						}
					}
				}
				$result['male'] = implode(', ', $investigation_names);
			}
			
		} catch (Exception $e) {
			log_message('error', 'Error getting investigation names: ' . $e->getMessage());
		}
		return $result;
	}
	
	/**
	 * Get medicine names for external API
	 * 
	 * @param array $consultation_data - Consultation data
	 * @return array - Medicine names
	 */
	private function get_medicine_names_for_api($consultation_data) {
		$result = ['female' => '', 'male' => ''];
		try {
			if(isset($consultation_data['female_medicine_suggestion_list'])) {
				$female_medicines = unserialize($consultation_data['female_medicine_suggestion_list']);
				$item_names = [];
				foreach ($female_medicines as $valueGroup) {
					foreach ($valueGroup as $value) {
						if (!empty($value['female_medicine_name']) && is_numeric($value['female_medicine_name'])) {
							$item_number = (int)$value['female_medicine_name'];
							$sql = "SELECT * FROM hms_stocks WHERE item_number = ?";
							$select_result = $this->db->query($sql, [$item_number])->row_array();
							if (!empty($select_result) && isset($select_result['item_name'])) {
								$item_names[] = $select_result['item_name'];
							}
						}
					}
				}
				$result['female'] = implode(', ', $item_names);
			}
			if(isset($consultation_data['male_medicine_suggestion_list'])) {
				$male_medicines = unserialize($consultation_data['male_medicine_suggestion_list']);
				$item_names = [];
				foreach ($male_medicines as $valueGroup) {
					foreach ($valueGroup as $value) {
						if (!empty($value['male_medicine_name']) && is_numeric($value['male_medicine_name'])) {
							$item_number = (int)$value['male_medicine_name'];
							$sql = "SELECT * FROM hms_stocks WHERE item_number = ?";
							$select_result = $this->db->query($sql, [$item_number])->row_array();
							if (!empty($select_result) && isset($select_result['item_name'])) {
								$item_names[] = $select_result['item_name'];
							}
						}
					}
				}
				$result['male'] = implode(', ', $item_names);
			}
		} catch (Exception $e) {
			log_message('error', 'Error getting medicine names: ' . $e->getMessage());
		}
		return $result;
	}
	
	/**
	 * Get procedure names for external API
	 * 
	 * @param array $consultation_data - Consultation data
	 * @return string - Procedure names
	 */
	private function get_procedure_names_for_api($consultation_data) {
		try {
			if(isset($consultation_data['sub_procedure_suggestion_list'])) {
				$procedure_ids = unserialize($consultation_data['sub_procedure_suggestion_list']);
				$procedure_names = [];
				if (is_array($procedure_ids)) {
					foreach ($procedure_ids as $value) {
						$value = (int)$value;
						$sql = "SELECT * FROM `hms_procedures` WHERE ID = ?";
						$select_result = $this->db->query($sql, [$value])->row_array();
						if (!empty($select_result) && isset($select_result['procedure_name'])) {
							$procedure_names[] = $select_result['procedure_name'];
						}
					}
				}
				return implode(', ', $procedure_names);
			}
		} catch (Exception $e) {
			log_message('error', 'Error getting procedure names: ' . $e->getMessage());
		}
		return '';
	}
	
	/**
	 * Get package names for external API
	 * 
	 * @param array $consultation_data - Consultation data
	 * @return string - Package names
	 */
	private function get_package_names_for_api($consultation_data) {
		try {
			if(isset($consultation_data['package_suggestion_list'])) {
				$package_ids_string = $consultation_data['package_suggestion_list'];
				$package_ids_array = explode(',', $package_ids_string);
				$package_names = [];
				foreach ($package_ids_array as $id) {
					$id = (int)trim($id);
					if ($id > 0) {
						$sql = "SELECT * FROM hms_procedure_package WHERE procedure_id = ?";
						$select_result = $this->db->query($sql, [$id])->row_array();
						if (!empty($select_result) && isset($select_result['package_name'])) {
							$package_names[] = $select_result['package_name'];
						}
					}
				}
				return implode(', ', array_unique($package_names));
			}
		} catch (Exception $e) {
			log_message('error', 'Error getting package names: ' . $e->getMessage());
		}
		
		return '';
	}
	// end of follow_up function
	
	// Appointment Filter
	function ajax_appointment_filter(){
		if($_POST['type'] == 'appointment_status'){
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
				$cancelled_select = $rescheduled_select = $no_show_select = $patient_in = $billing_done = "";
				if($vl['status'] == 'cancelled'){ $cancelled_select = "selected='selected'"; }
				if($vl['status'] == 'rescheduled'){ $rescheduled_select = "selected='selected'"; }
				if($vl['status'] == 'no_show'){ $no_show_select = "selected='selected'"; }
				if($vl['status'] == 'visited'){ $billing_done = "selected='selected'"; }
				if($vl['status'] == 'consultation'){ $patient_in = "selected='selected'"; }
				
				$appointment_html .= '<tr class="odd gradeX"><td>'.$count.'</td>';
				if($vl['paitent_type'] == 'exist_patient'){
					 $appointment_html .= '<td><a target="_blank" href="'.base_url().'patient_details/'.$vl['paitent_id'].'">'.$vl['wife_name'].'</a></td>';
				} else {
					$appointment_html .= '<td>'.$vl['wife_name'].'</td>';
				}
					$appointment_html .= '<td>'.$vl['appoitmented_date'].'</td><td>'.$vl['appoitmented_slot'].'</td><td>'.$vl['reason_of_visit'].'</td>';
				if($vl['status'] == 'consultation'){$appointment_html .= '<td><a class="btn btn-primary" href="'.base_url('consultation_done/'.$vl['ID']).'">Initiate Consultation</a></td>';}else{ $appointment_html .= '<td>'.ucwords($vl['status']).'</td>';}
				//$appointment_html .= '<td> <a target="_blank" class="btn btn-primary" href="'.base_url().'patient_reports/'.$vl['paitent_id'].'">Check Reports</a> </td>';
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

	public function procedure_reports($appointment_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['appointment_id'] = $appointment_id;
			$data['procedures'] = $this->appointment_model->patient_procedure($appointment_id);

			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/procedure_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}	
	}

	public function check_procedure_form($form_id, $patient_procedure_id, $appointment_id, $procedure_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$procedure_form_data = $this->doctors_model->check_procedure_form($form_id, $patient_procedure_id, $procedure_id);
			if(count($procedure_form_data) > 0){
				$form_data = get_prodecure_form($form_id);
				$data['form_data'] = $form_data;
				$data['form_id'] = $form_id;
				$data['procedure_id'] = $procedure_id;
				$data['patient_procedure_id'] = $patient_procedure_id;
				$data['appointment_id'] = $appointment_id;
				$data['procedure_form_data'] = $procedure_form_data[0];
				
				$data['updated_by'] = "";
    			$data['updated_type'] = "";
    			$data['updated_at'] = date('Y-m-d H:i:s');
    
    			if(isset($_SESSION['logged_doctor'])){
    				$data['updated_by'] = $_SESSION['logged_doctor']['username'];
    				$data['updated_type'] = "doctor";
    			}else if(isset($_SESSION['logged_embryologist'])){
    				$data['updated_by'] = $_SESSION['logged_embryologist']['username'];
    				$data['updated_type'] = "embryologist";
    			}
			
				
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('doctors/check_form', $data);
				$this->load->view($template['footer']);	
			}else{
				header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Form data not uploaded yet!').'&t='.base64_encode('error'));
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function procedure_upload(){
		$procedure_id = $_POST['procedure_id'];
		$appointment_id = $_POST['appointment_id'];
		$patient_procedure_id = $_POST['patient_procedure_id'];
		$patient_id = $_POST['patient_id'];
        $html = "";
        
		$patient_procedure_datas = $this->get_patient_prodedures_data($appointment_id, $patient_id);
		//var_dump($patient_procedure_datas);die;
		if(!empty($patient_procedure_datas)){
		$procedures_forms = get_prodecure_forms($procedure_id);
		
		if(count($procedures_forms) > 0){
		    
		    foreach($patient_procedure_datas as $ky => $patient_procedure_data)
		    $count=1;
			foreach($procedures_forms as $key => $val){
				$form_details = get_prodecure_form($val['form_id']);
				//var_dump($form_details);die;
				if(isset($_SESSION['logged_doctor'])){
					if($form_details['form_for'] != "lab_procedure"){
						$check_form_data = check_form_data($patient_id, $patient_procedure_data['receipt_number'], $form_details['form_area']);
						if(count($check_form_data) == 0){
							$html .= "<a target='_blank' count='".$count."' href='".base_url('procedure_form/'.$val['form_id'].'/'.$procedure_id.'/'.$appointment_id.'/'.$patient_procedure_id)."'>".$form_details['form_name']."</a> | ";
						}else if($form_details['type'] == "multiple"){
							$html .= "<a target='_blank' count='".$count."' href='".base_url('procedure_form/'.$val['form_id'].'/'.$procedure_id.'/'.$appointment_id.'/'.$patient_procedure_id)."'>".$form_details['form_name']."</a> | ";
						}
					}
				}if(isset($_SESSION['logged_embryologist'])){
					if($form_details['form_for'] == "lab_procedure"){
						$check_form_data = check_form_data($patient_id, $patient_procedure_data['receipt_number'], $form_details['form_area']);
						if(count($check_form_data) == 0){
							$html .= "<a target='_blank' count='".$count."' href='".base_url('procedure_form/'.$val['form_id'].'/'.$procedure_id.'/'.$appointment_id.'/'.$patient_procedure_id)."'>".$form_details['form_name']."</a> | ";
						}else if($form_details['type'] == "multiple"){
							$html .= "<a target='_blank' count='".$count."' href='".base_url('procedure_form/'.$val['form_id'].'/'.$procedure_id.'/'.$appointment_id.'/'.$patient_procedure_id)."'>".$form_details['form_name']."</a> | ";
						}
					}
				}
				$count++;
			}
			
			$html = substr($html, 0, -3);
			if(empty($html)){
				$html = "<p class='error'>NA!</p>   ";
			}
		}else{
			if(empty($html)){
				$html = "<p class='error'>Procedure Form not assigned!</p>   ";
			}
		}
		}else{
		   $html = "<p class='error'>Procedure billing disapproved!</p>   ";
		}
		echo json_encode($html);
		die;
	}
	
	public function procedure_upload_donor(){
		$procedure_id = $_POST['procedure_id'];
		$appointment_id = $_POST['appointment_id'];
		$patient_id = $_POST['patient_id'];
        $html = "";
        
		$patient_procedure_datas = $this->get_patient_prodedures_data($appointment_id, $patient_id);
		//var_dump($patient_procedure_datas);die;
		if(!empty($patient_procedure_datas)){
		$procedures_forms = get_prodecure_forms($procedure_id);
		
		if(count($procedures_forms) > 0){
		    
		    foreach($patient_procedure_datas as $ky => $patient_procedure_data)
		    $count=1;
			foreach($procedures_forms as $key => $val){
				$form_details = get_prodecure_form($val['form_id']);
				if(isset($_SESSION['logged_doctor'])){
					if($form_details['form_for'] != "lab_procedure"){
						$check_form_data = check_form_data($patient_id, $patient_procedure_data['receipt_number'], $form_details['form_area']);
						if(count($check_form_data) == 0){
							$html .= "<a target='_blank' count='".$count."' href='".base_url('procedure_form_donor/'.$val['form_id'].'/'.$procedure_id.'/'.$appointment_id)."'>".$form_details['form_name']."</a> | ";
						}else if($form_details['type'] == "multiple"){
							$html .= "<a target='_blank' count='".$count."' href='".base_url('procedure_form_donor/'.$val['form_id'].'/'.$procedure_id.'/'.$appointment_id)."'>".$form_details['form_name']."</a> | ";
						}
					}
				}if(isset($_SESSION['logged_embryologist'])){
					if($form_details['form_for'] == "lab_procedure"){
						$check_form_data = check_form_data($patient_id, $patient_procedure_data['receipt_number'], $form_details['form_area']);
						if(count($check_form_data) == 0){
							$html .= "<a target='_blank' count='".$count."' href='".base_url('procedure_form_donor/'.$val['form_id'].'/'.$procedure_id.'/'.$appointment_id)."'>".$form_details['form_name']."</a> | ";
						}else if($form_details['type'] == "multiple"){
							$html .= "<a target='_blank' count='".$count."' href='".base_url('procedure_form_donor/'.$val['form_id'].'/'.$procedure_id.'/'.$appointment_id)."'>".$form_details['form_name']."</a> | ";
						}
					}
				}
				$count++;
			}
			
			$html = substr($html, 0, -3);
			if(empty($html)){
				$html = "<p class='error'>NA!</p>   ";
			}
		}else{
			if(empty($html)){
				$html = "<p class='error'>Procedure Form not assigned!</p>   ";
			}
		}
		}else{
		   $html = "<p class='error'>Procedure billing disapproved!</p>   ";
		}
		echo json_encode($html);
		die;
	}

	public function procedure_form($form_id, $procedure_id, $appointment_id, $patient_procedure_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$patient_procedure_data = get_procedure($patient_procedure_id);
			$patient_id = $patient_procedure_data['patient_id'];
			$receipt_number = $patient_procedure_data['receipt_number'];

			if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action']=="prodedure_form"){
				unset($_POST['action']);
				if(count($patient_procedure_data) > 0){
					$_POST['patient_id'] = $patient_id;
					$_POST['receipt_number'] = $receipt_number;
					$form_from = $_POST['form_from'];unset($_POST['form_from']);
					$path = base_url(). "ipd-records/".$patient_id."/".$patient_procedure_id;
					if($form_from == "lab"){
						$path = base_url(). "procedure_reports/".$appointment_id;
					}
					$form_area = $_POST['form_area']; unset($_POST['form_area']);
					$insert_form_data = $this->doctors_model->procedure_form_insert($_POST, $form_area);
					if($insert_form_data > 0){
						header("location:" .$path."?m=".base64_encode('Procedure uploaded successfully!').'&t='.base64_encode('success'));
						die();
					}else{
						header("location:" .$path."?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
						die();
					}
				}else{
					header("location:" .$path."?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			
			$data = array();
			$form_data = get_prodecure_form($form_id);
			
			$form_name = $form_data['form_name'];
			$form_name = str_replace(" ", "-", $form_name);
			$data['form_id'] = $form_id;
			$data['form_name'] = $form_name;
			$data['procedure_id'] = $procedure_id;
			$data['appointment_id'] = $appointment_id;
			$data['patient_procedure_id'] = $patient_procedure_id;
			$data['patient_id'] = $patient_id;
			$data['receipt_number'] = $receipt_number;
			$data['form_name'] = $form_name;
			$data['form_from'] = isset($_GET['t'])?$_GET['t']:"";
			
			$data['updated_by'] = "";
			$data['updated_type'] = "";
			$data['updated_at'] = date('Y-m-d H:i:s');

			if(isset($_SESSION['logged_doctor'])){
				$data['updated_by'] = $_SESSION['logged_doctor']['username'];
				$data['updated_type'] = "doctor";
			}else if(isset($_SESSION['logged_embryologist'])){
				$data['updated_by'] = $_SESSION['logged_embryologist']['username'];
				$data['updated_type'] = "embryologist";
			}
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/procedure_upload', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	public function procedure_form_donor($form_id, $procedure_id, $appointment_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$patient_procedure_data = get_procedure($patient_procedure_id);
			$patient_id = $patient_procedure_data['patient_id'];
			$receipt_number = $patient_procedure_data['receipt_number'];

			if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action']=="prodedure_form"){
				unset($_POST['action']);
				if(count($patient_procedure_data) > 0){
					$_POST['patient_id'] = $patient_id;
					$_POST['receipt_number'] = $receipt_number;
					$form_from = $_POST['form_from'];unset($_POST['form_from']);
					$path = base_url(). "ipd-records/".$patient_id;
					if($form_from == "lab"){
						$path = base_url(). "procedure_reports/".$appointment_id;
					}
					$form_area = $_POST['form_area']; unset($_POST['form_area']);
					$insert_form_data = $this->doctors_model->procedure_form_insert($_POST, $form_area);
					if($insert_form_data > 0){
						header("location:" .$path."?m=".base64_encode('Procedure uploaded successfully!').'&t='.base64_encode('success'));
						die();
					}else{
						header("location:" .$path."?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
						die();
					}
				}else{
					header("location:" .$path."?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			
			$data = array();
			$form_data = get_prodecure_form($form_id);
			
			$form_name = $form_data['form_name'];
			$form_name = str_replace(" ", "-", $form_name);
			$data['form_id'] = $form_id;
			$data['form_name'] = $form_name;
			$data['procedure_id'] = $procedure_id;
			$data['appointment_id'] = $appointment_id;
			$data['patient_id'] = $patient_id;
			$data['receipt_number'] = $receipt_number;
			$data['form_name'] = $form_name;
			$data['form_from'] = isset($_GET['t'])?$_GET['t']:"";
			
			$data['updated_by'] = "";
			$data['updated_type'] = "";
			$data['updated_at'] = date('Y-m-d H:i:s');

			if(isset($_SESSION['logged_doctor'])){
				$data['updated_by'] = $_SESSION['logged_doctor']['username'];
				$data['updated_type'] = "doctor";
			}else if(isset($_SESSION['logged_embryologist'])){
				$data['updated_by'] = $_SESSION['logged_embryologist']['username'];
				$data['updated_type'] = "embryologist";
			}
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/procedure_upload', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function my_ipd(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$wife_name = $this->input->get('wife_name', true);
			$paitent_id = $this->input->get('paitent_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "doctors/my_ipd";
        	$config["total_rows"] = $this->doctors_model->ipd_data_count($wife_name, $paitent_id);
        	$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['ipd_data'] = $this->doctors_model->ipd_data_list_patination($config["per_page"], $per_page, $wife_name, $paitent_id);
			$data["wife_name"] = $wife_name;
			$data["paitent_id"] = $paitent_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/my_ipd', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function my_reports(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$doctor_id = $_SESSION['logged_doctor']['doctor_id'];
			
			// Add caching for better performance
			$cache_key = 'my_reports_' . $doctor_id;
			$data['data'] = $this->doctors_model->doctor_ipd_lists($doctor_id);
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/my_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}		
	}

	public function lab_reports($patient_id, $appointment_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['patient_id'] = $patient_id;	
			$data['appointment_id'] = $appointment_id;	
			$investigations_reports = $this->patients_model->patient_investigations_reports($patient_id);
			$data['investigations_reports'] = $investigations_reports;

			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/lab_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}		
	}

	public function procedure_report_status($form_id, $patient_procedure_id, $appointment_id, $data_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$form_data = get_prodecure_form($form_id);
			$patient_procedure_data = get_procedure($patient_procedure_id);
			$form_area = $form_data['form_area'];
			$patient_id = $patient_procedure_data['patient_id'];
			$receipt_number = $patient_procedure_data['receipt_number'];
			$reason = "";
			if($_GET['s'] == "disapproved"){
				$reason = $_POST['status_reason'];
			}
			$update_status = $this->doctors_model->update_procedure_report_status($_GET['s'], $reason, $patient_id, $receipt_number, $form_area, $data_id);
			if($update_status > 0){
				header("location:" .base_url().'my-reports/'.$patient_id.'/'.$appointment_id."?m=".base64_encode('Report '.$_GET['s']).'&t='.base64_encode('success'));
			}else{
				header("location:" .base_url().'my-reports/'.$patient_id.'/'.$appointment_id."?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}	
	}
	// Dashbaord END


    public function pcp_ndt(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('center', true);
			//var_dump($center);die;
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$iic_id = $this->input->get('iic_id', true);
			$type = $this->input->get('type', true);
			$ID = $this->input->get('ID', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->doctors_model->export_pcpndt_data($start_date, $end_date, $center, $iic_id, $type,$ID);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Pcp-Ndt-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Wife Name,Husband Name, Wife Phone, Wife Age, Female Issues, Wife Address, Details Management Advised, IVF Consultant, Procedure Done, Outcome Of Tretment,Investigation,Test Type, Date';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$lead_arr = array($val['iic_id'], $val['wife_name'], $val['husband_name'], $val['wife_phone'], $val['wife_age'], $val['female_issues'], $val['wife_address'], $val['details_management_advised'], $val['IVF_Consultant'],$val['procedure_done'], $val['outcome_of_tretment'],$val['investigation'],$val['type'],  $val['date']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "doctors/pcp_ndt";
        	$config["total_rows"] = $this->doctors_model->patient_pcpndt_count($center, $start_date, $end_date, $iic_id, $type, $ID);
        	//var_dump($patient_pcpndt_count);die;
			$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->doctors_model->patient_pcpndt_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $iic_id, $type, $ID);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["type"] = $type; 
			$data["ID"] = $ID; 
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/pcp_ndt', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
    public function patient_details(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/patient_details', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	public function add_pcp_ndt()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_pcp_ndt'){
				unset($_POST['action']);
                $data = $this->doctors_model->add_pcp_ndt($_POST);
				if($data > 0){
					header("location:" .base_url(). "doctors/add_pcp_ndt?m=".base64_encode('Pcp Ndt Report added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "doctors/add_pcp_ndt?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/add_pcp_ndt', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	 public function doctor_patient(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$template = get_header_template($logg['role']);
			if (!empty($_SESSION['logged_billing_manager']['center'])) {
				$center_number = $_SESSION['logged_billing_manager']['center'];
			} elseif (!empty($_SESSION['logged_counselor']['center'])) {
				$center_number = $_SESSION['logged_counselor']['center'];
			} else {
				$center_number = null; // Default if neither exists
			}
			
			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$male_investigation = $this->input->get('export-patient', true);
			if (isset($male_investigation)){
				$data = $this->accounts_model->export_male_investigation($start_date, $end_date, $center_number, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=dailypatient-Report-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Date, IIC ID, Patient Name, Age, Husband Name, Purpose, Male Investigation, Female Investigation, Male Medicine';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$lead_arr = array($val['consultation_date'], $val['patient_id'], $val['follow_up_purpose'], $val['male_investigation_code'], $val['male_investigation_price'], $val['male_investigation_discount'], $total);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}			
			$config = array();
        	$config["base_url"] = base_url() . "doctors/doctor_patient";
        	$config["total_rows"] = $this->doctors_model->get_doctor_patient_count($start_date, $end_date, $patient_id, $center_number);
        	$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['app_result'] = $this->doctors_model->doctor_patient_lists_pagination($config["per_page"], $per_page, $start_date, $end_date, $patient_id, $center_number);
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			//$data["center_number"] = $center_number;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/doctor_patient', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	 public function patient_duration(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$patient_id = $this->input->get('patient_id', true);
			$center = $this->input->get('center', true);
			$doctor = $this->input->get('doctor', true);

			$config = array();
        	$config["base_url"] = base_url() . "doctors/patient_duration";
        	$config["total_rows"] = $this->doctors_model->patient_duration_count($patient_id, $center, $doctor);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['app_result'] = $this->doctors_model->patient_duration_pagination($config["per_page"], $per_page, $patient_id, $center, $doctor);
			$data["patient_id"] = $patient_id;
			$data["center"] = $center;
			$data["doctor"] = $doctor;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/patient_duration', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function ovarian_stem_cell(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/ovarian_stem_cell', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_for_blastocyst(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_blastocyst', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_for_embryo_glue(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_embryo_glue', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_for_icsi(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_icsi', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_for_lah(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_lah', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_for_microfluidics_sperm(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_microfluidics_sperm', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_for_pgt(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_pgt', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_for_sperm_mobil(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_sperm_mobil', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_for_thawing_of_gametes(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_thawing_of_gametes', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
		/************* Liason *************/
	
	public function consent_book(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
		if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_consent_book'){
		unset($_POST['action']);
		$_POST['on_date'] = date("Y-m-d H:i:s");
		$transaction_img = '';
		if(!empty($_FILES['transaction_img']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'consent_book/';
			$NewImageName = rand(4,10000)."-". $_FILES['transaction_img']['name'];
			$transaction_img = base_url().'assets/consent_book/'.$NewImageName;
			move_uploaded_file($_FILES['transaction_img']['tmp_name'], $destination.$NewImageName);
			$_POST['transaction_img'] = $transaction_img;
		}

		$settle = $this->doctors_model->insert_add_consent_book($_POST);
		if($settle > 0){
			header("location:" .base_url(). "doctors/consent_book?m=".base64_encode('Consent book successfully Add!').'&t='.base64_encode('success'));
			die();
		}else{
				header("location:" .base_url(). "doctors/consent_book?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
				die();
			}
		}
		$template = get_header_template($logg['role']);
		$this->load->view($template['header']);
		$this->load->view('doctors/consent_book', $data);
		$this->load->view($template['footer']);
		}else{
		header("location:" .base_url(). "");
		die();
			}
	}
	
	public function consent_book_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$consent_book_name = $this->input->get('consent_book_name', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "doctors/consent_book_list";
        	$config["total_rows"] = $this->doctors_model->consent_book_count($consent_book_name, $start_date, $end_date);
        	$config["per_page"] = 40;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['procedure_result'] = $this->doctors_model->consent_book_patination($config["per_page"], $per_page, $consent_book_name, $start_date, $end_date);
			$data["consent_book_name"] = $consent_book_name;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_book_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function patient_general_instructions(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/patient_general_instructions', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function ovarian_prp($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->patient_details($patient_id);
			$this->load->view($template['header']);
			$this->load->view('doctors/ovarian_prp', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function after_embryo_transfer($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->patient_details($patient_id);
			$this->load->view($template['header']);
			$this->load->view('doctors/after_embryo_transfer', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function before_starting_ivf_cycle($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->patient_details($patient_id);
			$this->load->view($template['header']);
			$this->load->view('doctors/before_starting_ivf_cycle', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function post_opu_instructions($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->patient_details($patient_id);
			$this->load->view($template['header']);
			$this->load->view('doctors/post_opu_instructions', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function general_instructions_prior($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->patient_details($patient_id);
			$this->load->view($template['header']);
			$this->load->view('doctors/general_instructions_prior', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function general_instructions_prior_to_embryo_transfer($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->patient_details($patient_id);
			$this->load->view($template['header']);
			$this->load->view('doctors/general_instructions_prior_to_embryo_transfer', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function during_the_ivf_cycle($patient_id){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$data['data'] = $this->accounts_model->patient_details($patient_id);
			$this->load->view($template['header']);
			$this->load->view('doctors/during_the_ivf_cycle', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function testicular_stem_cell()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_testicular_stem_cell'){
				unset($_POST['action']);
				
				$data = $this->doctors_model->testicular_stem_add($_POST);
				//var_dump($this->doctors_model->testicular_stem_add($_POST));die;
				if($data > 0){
					header("Location: " . base_url() . "doctors/testicular_stem_details/" . $_POST['receipt_number']);
					die();
				}else{
					header("location:" .base_url(). "doctors/testicular_stem_cell?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/testicular_stem_cell', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function testicular_stem_details($receipt_number){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->testicular_stem_details($receipt_number);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/testicular_stem_cell', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_for_oocyte_activation()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_oocyte_activation'){
				unset($_POST['action']);
				
				$data = $this->doctors_model->oocyte_activation_add($_POST);
				//var_dump($this->doctors_model->testicular_stem_add($_POST));die;
				if($data > 0){
					header("Location: " . base_url() . "doctors/consent_for_oocyte_details/" . $_POST['receipt_number']);
					die();
				}else{
					header("location:" .base_url(). "doctors/consent_for_oocyte_activation?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_oocyte_activation', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consent_for_oocyte_details($receipt_number){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->doctors_model->consent_for_oocyte_details($receipt_number);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/consent_for_oocyte_activation', $data);
			$this->load->view($template['footer']);
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

	public function get_center($center){
		$data = $this->employee_model->get_center_data($center);
		return $data;
	}

	function get_patient_prodedures($appointment_id, $patient_id){
		$data = $this->patients_model->get_patient_prodedures($appointment_id, $patient_id);
		return $data;
	}
	
	function get_patient_prodedures_data($appointment_id, $patient_id){
		$data = $this->patients_model->get_patient_prodedures_data($appointment_id, $patient_id);
		return $data;
	}
	
	function doctor_appointment($appointment_id){
		$data = $this->doctors_model->get_doctor_appointment($appointment_id);
		return $data;
	}

	function get_center_list(){
		$center = $this->center_model->get_centers();
		return $center;
	}
	
	function get_employee_list(){
		$employee = $this->doctors_model->get_employee_list();
		return $employee;	
	}
	
	function get_test_type(){
		$testtype = $this->doctors_model->get_test_type();
		return $testtype;	
	}
	
	function get_center_name(){
		$name = $this->doctors_model->get_center_name();
		return $name;
	}

	function center_doctors(){
		$center = $_SESSION['logged_billing_manager']['center'];
		$doctor_list = $this->doctors_model->get_center_doctors($center);
		return $doctor_list;
	}

	function get_all_centers(){
		$all_centers = $this->stock_model->get_centers();
		return $all_centers;
	}

	/************* Discharge Forms Management *************/
	
	public function manage_discharge_forms(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			
			// Handle form submission
			if(isset($_POST['action']) && $_POST['action'] == 'add_discharge_form'){
				unset($_POST['action']);
				$_POST['status'] = 'active';
				// Automatically set role based on logged-in user
				$_POST['role'] = $logg['role'];
				
				// Save form name in both form_name and name columns
				if(isset($_POST['form_name'])) {
					$_POST['name'] = $_POST['form_name'];
				}
				
				// Extract columns data
				$columns = array();
				if(isset($_POST['columns']) && is_array($_POST['columns'])) {
					$columns = $_POST['columns'];
					unset($_POST['columns']); // Remove from main data
				}
				
				// Add form to database
				$result = $this->accounts_model->add_discharge_form($_POST);
				
				if($result > 0) {
					// Create the database table
					$table_name = $_POST['db_name'];
					$table_result = $this->accounts_model->create_discharge_form_table($table_name, $columns);
					
					if($table_result['success']) {
						header("location:" .base_url(). "doctors/manage_discharge_forms?m=".base64_encode('Discharge form and table created successfully!').'&t='.base64_encode('success'));
					} else {
						// If table creation failed, delete the form record and show error
						$this->accounts_model->delete_discharge_form($result);
						header("location:" .base_url(). "doctors/manage_discharge_forms?m=".base64_encode($table_result['message']).'&t='.base64_encode('error'));
					}
					die();
				}
			}
			
			// Get existing discharge forms
			$data['discharge_forms'] = $this->accounts_model->get_discharge_form_data();
			$data['logg'] = $logg; // Pass login data to view
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/manage_discharge_forms', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function edit_discharge_form($form_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			// Handle form update
			if(isset($_POST['action']) && $_POST['action'] == 'update_discharge_form'){
				unset($_POST['action']);
				$_POST['updated_by'] = $_SESSION['logged_doctor']['id'];
				$_POST['updated_at'] = date("Y-m-d H:i:s");
				// Keep the original role, don't allow changes
				unset($_POST['role']);
				
				// Save form name in both form_name and name columns
				if(isset($_POST['form_name'])) {
					$_POST['name'] = $_POST['form_name'];
				}
				
				$result = $this->accounts_model->update_discharge_form($form_id, $_POST);
				if($result > 0){
					header("location:" .base_url(). "doctors/manage_discharge_forms?m=".base64_encode('Discharge form updated successfully!').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "doctors/manage_discharge_forms?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
					die();
				}
			}
			
			// Get form data for editing
			$data['form_data'] = $this->accounts_model->get_discharge_form_by_id($form_id);
			$data['logg'] = $logg; // Pass login data to view
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/edit_discharge_form', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function delete_discharge_form($form_id){
		$logg = checklogin();
		if($logg['status'] == true){
			// Get form data before deletion for better feedback
			$form_data = $this->accounts_model->get_discharge_form_by_id($form_id);
			$form_name = !empty($form_data) ? $form_data['form_name'] : 'Unknown Form';
			$table_name = !empty($form_data) ? $form_data['db_name'] : 'Unknown Table';
			
			
			$result = $this->accounts_model->delete_discharge_form($form_id);
			if($result > 0){
				$message = "Discharge form '{$form_name}' and its associated table '{$table_name}' deleted successfully!";
				header("location:" .base_url(). "doctors/manage_discharge_forms?m=".base64_encode($message).'&t='.base64_encode('success'));
			}else{
				header("location:" .base_url(). "doctors/manage_discharge_forms?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
			}
			die();
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function update_discharge_form_status(){
		ob_clean();
		$logg = checklogin();
		if($logg['status'] == true){
			if($this->input->is_ajax_request()){
				$form_id = $this->input->post('form_id');
				$status = $this->input->post('status');
				if($form_id && $status){
					$update_data = array('status' => $status);
					$result = $this->accounts_model->update_discharge_form($form_id, $update_data);
					if($result > 0){
						$response = array(
							'success' => true,
							'message' => 'Status updated successfully'
						);
					}else{
						$response = array(
							'success' => false,
							'message' => 'Failed to update status'
						);
					}
				}else{
					$response = array(
						'success' => false,
						'message' => 'Missing required parameters'
					);
				}
				header('Content-Type: application/json');
				header('Cache-Control: no-cache, must-revalidate');
				echo json_encode($response);
				exit();
			}else{
				header("location:" .base_url(). "doctors/manage_discharge_forms");
				exit();
			}
		}else{
			$response = array(
				'success' => false,
				'message' => 'Not authorized'
			);
			header('Content-Type: application/json');
			header('Cache-Control: no-cache, must-revalidate');
			echo json_encode($response);
			exit();
		}
	}
	
	/************* Embryo Record Discharge Summary Management *************/
	
	public function save_embryo_record_discharge_summary(){
		ob_clean();
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['submit'])){
				unset($_POST['submit']);
				
				// Load the model
				$this->load->model('embryo_record_discharge_summary_model');
				
				// Validate form data using model
				$validation_errors = $this->embryo_record_discharge_summary_model->validate($_POST);
				
				if (!empty($validation_errors)) {
					$error_message = implode(', ', $validation_errors);
					header("location:" . $_SERVER['HTTP_REFERER'] . "?m=" . base64_encode($error_message) . '&t=' . base64_encode('error'));
					die();
				}
				
				// Save data using model
				$result = $this->embryo_record_discharge_summary_model->save($_POST);
				
				if($result['status']){
					header("location:" . $_SERVER['HTTP_REFERER'] . "?m=" . base64_encode($result['message']) . '&t=' . base64_encode('success'));
				} else {
					header("location:" . $_SERVER['HTTP_REFERER'] . "?m=" . base64_encode($result['message']) . '&t=' . base64_encode('error'));
				}
				die();
			}
			header("location:" . base_url() . "doctors/manage_discharge_forms");
			die();
				
		}else{
			header("location:" . base_url() . "");
			die();
		}
	}
	
	public function get_embryo_record_discharge_summary($iic_id, $appoitmented_date = ''){
		ob_clean();
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			
			// Load the model
			$this->load->model('embryo_record_discharge_summary_model');
			
			// Get embryo record using model
			$data['embryo_record'] = $this->embryo_record_discharge_summary_model->get_by_iic_id($iic_id, $appoitmented_date);
			
			// Get appointment and patient data
			$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$iic_id."'";
			$data['appointment_data'] = $this->db->query($sql1)->row_array();
			if(!empty($data['appointment_data'])){
				$wife_phone = $data['appointment_data']['wife_phone'];
				$sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$wife_phone."' and paitent_type='new_patient'";
				$data['patient_appointment'] = $this->db->query($sql2)->row_array();
				if(!empty($data['patient_appointment'])){
					$appoitment_for = $data['patient_appointment']['appoitment_for'];
					$sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$appoitment_for."'";
					$data['center_data'] = $this->db->query($sql3)->row_array();
				}
			}
			$data['all_centers'] = $this->center_model->get_all_centers();
			$data['logg'] = $logg;
			$data['iic_id'] = $iic_id;
			$data['appoitmented_date'] = $appoitmented_date;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/embryo_record_discharge_summery', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function list_embryo_record_discharge_summary(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			
			// Load the model
			$this->load->model('embryo_record_discharge_summary_model');
			
			// Get all records using model
			$data['embryo_records'] = $this->embryo_record_discharge_summary_model->get_all_with_patient_info();
			
			$data['logg'] = $logg;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('doctors/list_embryo_record_discharge_summary', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function delete_embryo_record_discharge_summary($iic_id, $appoitmented_date = ''){
		$logg = checklogin();
		if($logg['status'] == true){
			// Load the model
			$this->load->model('embryo_record_discharge_summary_model');
			
			// Delete record using model
			$result = $this->embryo_record_discharge_summary_model->delete($iic_id, $appoitmented_date);
			
			if($result['status']){
				$message = $result['message'];
				header("location:" . base_url() . "doctors/list_embryo_record_discharge_summary?m=" . base64_encode($message) . '&t=' . base64_encode('success'));
			} else {
				header("location:" . base_url() . "doctors/list_embryo_record_discharge_summary?m=" . base64_encode($result['message']) . '&t=' . base64_encode('error'));
			}
			die();
		}else{
			header("location:" . base_url() . "");
			die();
		}
	}
	

	function follow_up_clean($appointment_id) {
		header('Content-Type: application/json');
		$logg = checklogin();
		if($logg['status'] == true) {
			if(isset($_POST['action']) && $_POST['action'] == 'add_consultation_done') {
				try {
					$result = $this->process_structured_consultation_data();
					if($result['status'] == 'success') {
						echo json_encode([
							'status' => 'success',
							'message' => 'Follow-up consultation completed successfully!',
							'redirect_url' => base_url('doctor_appointments')
						]);
					} else {
						echo json_encode([
							'status' => 'error',
							'message' => $result['message']
						]);
					}
				} catch (Exception $e) {
					echo json_encode([
						'status' => 'error',
						'message' => 'An error occurred while processing the consultation: ' . $e->getMessage()
					]);
				}
			} else {
				// For non-AJAX requests, return error
				echo json_encode([
					'status' => 'error',
					'message' => 'Invalid request method or missing action parameter'
				]);
			}
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Authentication required'
			]);
		}
	}
	function follow_up_form($appointment_id) {
		$logg = checklogin();
		if($logg['status'] == true) {
			$this->load_follow_up_form($appointment_id);
		} else {
			header("location:" . base_url() . "");
			die();
		}
	}
	
	/**
	 * Process structured consultation data from the new form submission
	 * 
	 * @return array - Processing result with status and message
	 */
	private function process_structured_consultation_data() {
		try {
			// Get basic consultation data
			$consultation_data = [
				'appointment_id' => $this->input->post('appointment_id', TRUE),
				'patient_id' => $this->input->post('patient_id', TRUE),
				'wife_phone' => $this->input->post('wife_phone', TRUE),
				'doctor_id' => $this->input->post('doctor_id', TRUE),
				'center_number' => $this->input->post('center_number', TRUE),
				'female_findings' => $this->input->post('female_findings', TRUE) ?: '',
				'male_findings' => $this->input->post('male_findings', TRUE) ?: '',
				'follow_up' => $this->input->post('follow_up', TRUE) ?: 0,
				'follow_up_date' => $this->input->post('follow_up_date', TRUE) ?: '',
				'follow_slot' => $this->input->post('follow_slot', TRUE) ?: '',
				'follow_up_purpose' => $this->input->post('follow_up_purpose', TRUE) ?: '',
				'consultation_date' => date("Y-m-d H:i:s")
			];
			$required_fields = ['appointment_id', 'patient_id', 'doctor_id', 'center_number'];
			foreach($required_fields as $field) {
				if(empty($consultation_data[$field])) {
					return ['status' => 'error', 'message' => 'Required field missing: ' . $field];
				}
			}
			$sections_data = $this->input->post('sections');
			if($sections_data) {
				if(isset($sections_data['investigations']) && $sections_data['investigations']['enabled']) {
					$consultation_data['investation_suggestion'] = 1;
					$consultation_data['female_minvestigation_suggestion_list'] = serialize(isset($sections_data['investigations']['female_minvestigation_suggestion_list']) ? $sections_data['investigations']['female_minvestigation_suggestion_list'] : []);
					$consultation_data['male_minvestigation_suggestion_list'] = serialize(isset($sections_data['investigations']['male_minvestigation_suggestion_list']) ? $sections_data['investigations']['male_minvestigation_suggestion_list'] : []);
				}
				if(isset($sections_data['medicines_opd']) && $sections_data['medicines_opd']['enabled']) {
					$consultation_data['medicine_suggestion'] = 1;
					$female_medicines = isset($sections_data['medicines_opd']['female_medicines']) ? $sections_data['medicines_opd']['female_medicines'] : [];
					$male_medicines = isset($sections_data['medicines_opd']['male_medicines']) ? $sections_data['medicines_opd']['male_medicines'] : [];
					$consultation_data['female_medicine_suggestion_list'] = serialize($this->format_medicine_data($female_medicines, 'female'));
					$consultation_data['male_medicine_suggestion_list'] = serialize($this->format_medicine_data($male_medicines, 'male'));
				}
				if(isset($sections_data['medicines_ipd']) && $sections_data['medicines_ipd']['enabled']) {
					$consultation_data['medicine_suggestion_ipd'] = 1;
					$female_medicines_ipd = isset($sections_data['medicines_ipd']['female_medicines']) ? $sections_data['medicines_ipd']['female_medicines'] : [];
					$male_medicines_ipd = isset($sections_data['medicines_ipd']['male_medicines']) ? $sections_data['medicines_ipd']['male_medicines'] : [];
					$consultation_data['female_medicine_suggestion_list_ipd'] = serialize($this->format_medicine_data($female_medicines_ipd, 'female'));
					$consultation_data['male_medicine_suggestion_list_ipd'] = serialize($this->format_medicine_data($male_medicines_ipd, 'male'));
				}
				
				// Process procedures
				if(isset($sections_data['procedures']) && $sections_data['procedures']['enabled']) {
					$consultation_data['procedure_suggestion'] = 1;
					$consultation_data['sub_procedure_suggestion_list'] = serialize($sections_data['procedures']['sub_procedure_suggestion_list']);
				}
				
				// Process packages
				if(isset($sections_data['packages']) && $sections_data['packages']['enabled']) {
					$consultation_data['package_suggestion'] = 1;
					$consultation_data['package_suggestion_list'] = serialize($sections_data['packages']['package_suggestion_list']);
				}
			}
			
			// Process follow-up appointment if required
			if($consultation_data['follow_up'] == 1) {
				$followup_result = $this->process_followup_appointment_clean($consultation_data);
				if($followup_result['status'] == 'error') {
					return $followup_result;
				}
			}
			// Save consultation data
			$consultation_done = $this->doctors_model->consultation_done($consultation_data);
			if($consultation_done > 0) {
				$this->send_consultation_to_external_apis($consultation_data);
				$this->appointment_model->appointment_status('consultation_done', $consultation_data['appointment_id']);
				if(isset($sections_data['advisory_templates']) && !empty($sections_data['advisory_templates'])) {
					$this->send_advisory_emails_clean($consultation_data, $sections_data['advisory_templates']);
				}
				
				return ['status' => 'success', 'message' => 'Consultation completed successfully'];
			} else {
				return ['status' => 'error', 'message' => 'Failed to save consultation data'];
			}
			
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => 'Error processing consultation: ' . $e->getMessage()];
		}
	}
	
	private function format_medicine_data($medicines, $gender) {
		$formatted_data = [];
		$formatted_data[$gender . '_medicine_suggestion_list'] = [];
		
		// Check if medicines is an array and not null
		if(is_array($medicines) && !empty($medicines)) {
			foreach($medicines as $medicine) {
				$formatted_data[$gender . '_medicine_suggestion_list'][] = [
					$gender . '_medicine_name' => isset($medicine['medicine_id']) ? $medicine['medicine_id'] : '',
					$gender . '_medicine_dosage' => isset($medicine['dosage']) ? $medicine['dosage'] : '',
					$gender . '_medicine_remarks' => isset($medicine['remarks']) ? $medicine['remarks'] : '',
					$gender . '_medicine_when_start' => isset($medicine['when_start']) ? $medicine['when_start'] : '',
					$gender . '_medicine_days' => isset($medicine['days']) ? $medicine['days'] : '',
					$gender . '_medicine_route' => isset($medicine['route']) ? $medicine['route'] : '',
					$gender . '_medicine_frequency' => isset($medicine['frequency']) ? $medicine['frequency'] : '',
					$gender . '_medicine_timing' => isset($medicine['timing']) ? $medicine['timing'] : '',
					$gender . '_medicine_take' => isset($medicine['take']) ? $medicine['take'] : ''
				];
			}
		}
		
		return $formatted_data;
	}
	
	private function process_followup_appointment_clean($consultation_data) {
		try {
			if(empty($this->input->post('appoitment_for')) || 
			   empty($consultation_data['follow_up_date']) || 
			   empty($this->input->post('appoitmented_doctor')) || 
			   empty($consultation_data['follow_slot'])) {
				return ['status' => 'error', 'message' => 'Follow-up appointment data incomplete'];
			}
			
			$patient_details = get_patient_detail($consultation_data['patient_id']);
			$appointment_arr = [
				'paitent_type' => 'exist_patient',
				'paitent_id' => $consultation_data['patient_id'],
				'wife_name' => isset($patient_details['wife_name']) ? $patient_details['wife_name'] : '',
				'wife_phone' => $consultation_data['wife_phone'],
				'wife_email' => isset($patient_details['wife_email']) ? $patient_details['wife_email'] : '',
				'nationality' => isset($patient_details['nationality']) ? $patient_details['nationality'] : '',
				'reason_of_visit' => $consultation_data['follow_up_purpose'],
				'appoitment_for' => $this->input->post('appoitment_for'),
				'appoitmented_date' => $consultation_data['follow_up_date'],
				'appoitmented_doctor' => $this->input->post('appoitmented_doctor'),
				'appoitmented_slot' => $consultation_data['follow_slot'],
				'follow_up_appointment' => 1,
				'previous_appointment' => $consultation_data['appointment_id'],
				'appointment_added' => date('Y-m-d H:i:s')
			];
			$appointment = $this->billingmodel_model->insert_appointments($appointment_arr);
			if($appointment > 0) {
				$doctor_details = doctor_details($consultation_data['doctor_id']);
				$this->send_followup_notifications($appointment_arr, $patient_details, $doctor_details);
				return ['status' => 'success', 'message' => 'Follow-up appointment created successfully'];
			} else {
				return ['status' => 'error', 'message' => 'Failed to create follow-up appointment'];
			}
			
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => 'Error creating follow-up appointment: ' . $e->getMessage()];
		}
	}
	private function send_advisory_emails_clean($consultation_data) {
		try {
			if($this->input->post('advisory_templates')) {
				$patient_details = get_patient_detail($consultation_data['patient_id']);
				$patient_to = $patient_details['wife_email'];
				$patient_name = $patient_details['wife_name'];
				$advisory_subject = "IVF related advisory";
				$advisory_message = "Hi " . $patient_name . ", Hope you are doing well!<br/>Here are some suggestion you can follow for successfull IVF. Please find the attached instruction below.<br/> Thanks & Regards<br/> IndiaIVF";
				$advisory_templates = implode(',', $this->input->post('advisory_templates'));
				send_mail($patient_to, $advisory_subject, $advisory_message, $advisory_templates);
			}
		} catch (Exception $e) {
			log_message('error', 'Error sending advisory emails: ' . $e->getMessage());
		}
	}


	private function load_follow_up_form($appointment_id) {
		$appointments = $this->appointment_model->doctor_appointment_details($appointment_id);
		if(!$appointments) {
			header("location:" . base_url() . "doctor_appointments?m=" . base64_encode('Appointment not found') . '&t=' . base64_encode('error'));
			die();
		}
		$consultation_data = $this->get_consultation($appointment_id);
		$patient_data = get_patient_detail($consultation_data['patient_id']);
		$patient_doctor_consultation = patient_doctor_consultation_data($appointment_id, $consultation_data['patient_id']);
		// Load master data using correct model methods
		$master_investigations = $this->investigation_model->get_master_investigations_list();
		$consultation_medicine = $this->doctors_model->consultation_medicine();
		$consultation_medicine_ipd = $this->doctors_model->consultation_medicine_ipd();
		$procedures = $this->procedures_model->get_procedures_list();
		$package = $this->procedures_model->get_procedure_package_list();
		// Load view data
		$data = [
			'appointments' => $appointments,
			'consultation_data' => $consultation_data,
			'patient_data' => $patient_data,
			'patient_doctor_consultation' => $patient_doctor_consultation,
			'master_investigations' => $master_investigations,
			'consultation_medicine' => $consultation_medicine,
			'consultation_medicine_ipd'=>$consultation_medicine_ipd,
			'procedures' => $procedures,
			'package' => $package
		];
		
		// Load template and view
		$logg = checklogin();
		$template = get_header_template($logg['role']);
		$this->load->view($template['header']);
		$this->load->view('appointments/follow_up', $data);
		$this->load->view($template['footer']);
	}
	
	function follow_up_print($appointment_id) {
		$logg = checklogin();
		if($logg['status'] == true) {
			// Load appointment data
			$appointments = $this->appointment_model->doctor_appointment_details($appointment_id);
			if(!$appointments) {
				header("location:" . base_url() . "doctor_appointments?m=" . base64_encode('Appointment not found') . '&t=' . base64_encode('error'));
				die();
			}
			
			// Load consultation data
			$consultation_data = $this->get_consultation($appointment_id);
			$patient_data = get_patient_detail($consultation_data['patient_id']);
			$patient_doctor_consultation = patient_doctor_consultation_data($appointment_id, $consultation_data['patient_id']);
			
			// Load master data
			$master_investigations = $this->investigation_model->get_master_investigations_list();
			$consultation_medicine = $this->doctors_model->consultation_medicine();
			$procedures = $this->procedures_model->get_procedures_list();
			$package = $this->procedures_model->get_procedure_package_list();
			
			// Prepare data for print view
			$data = [
				'appointments' => $appointments,
				'consultation_data' => $consultation_data,
				'patient_data' => $patient_data,
				'patient_doctor_consultation' => $patient_doctor_consultation,
				'master_investigations' => $master_investigations,
				'consultation_medicine' => $consultation_medicine,
				'procedures' => $procedures,
				'package' => $package,
				'print_mode' => true
			];
			// Load print view
			$this->load->view('appointments/follow_up_print', $data);
		} else {
			header("location:" . base_url() . "");
			die();
		}
	}
	
	function print_submitted_consultation($appointment_id) {
		$logg = checklogin();
		if($logg['status'] == true) {
			// Load appointment data
			$appointments = $this->appointment_model->doctor_appointment_details($appointment_id);
			if(!$appointments) {
				header("location:" . base_url() . "doctor_appointments?m=" . base64_encode('Appointment not found') . '&t=' . base64_encode('error'));
				die();
			}
			$consultation_data = $this->get_consultation($appointment_id);
			$patient_data = get_patient_detail($consultation_data['patient_id']);
			// Process the submitted data for printing
			$print_data = $this->process_submitted_data_for_print($consultation_data);
			$data = [
				'appointments' => $appointments,
				'consultation_data' => $consultation_data,
				'patient_data' => $patient_data,
				'print_data' => $print_data,
				'print_mode' => true
			];
			
			// Load print view
			$this->load->view('appointments/submitted_consultation_print', $data);
		} else {
			header("location:" . base_url() . "");
			die();
		}
	}
	private function process_submitted_data_for_print($consultation_data) {
		$print_data = [
			'patient_info' => [],
			'clinical_findings' => [],
			'investigations' => [],
			'medicines_opd' => [],
			'medicines_ipd' => [],
			'procedures' => [],
			'packages' => [],
			'follow_up' => []
		];
		if(isset($consultation_data['investation_suggestion']) && $consultation_data['investation_suggestion'] == 1) {
			$print_data['investigations']['enabled'] = true;
			if(!empty($consultation_data['female_minvestigation_suggestion_list'])) {
				$print_data['investigations']['female'] = unserialize($consultation_data['female_minvestigation_suggestion_list']);
			}
			
			if(!empty($consultation_data['male_minvestigation_suggestion_list'])) {
				$print_data['investigations']['male'] = unserialize($consultation_data['male_minvestigation_suggestion_list']);
			}
		}
		if(isset($consultation_data['medicine_suggestion']) && $consultation_data['medicine_suggestion'] == 1) {
			$print_data['medicines_opd']['enabled'] = true;
			
			if(!empty($consultation_data['female_medicine_suggestion_list'])) {
				$print_data['medicines_opd']['female'] = unserialize($consultation_data['female_medicine_suggestion_list']);
			}
			
			if(!empty($consultation_data['male_medicine_suggestion_list'])) {
				$print_data['medicines_opd']['male'] = unserialize($consultation_data['male_medicine_suggestion_list']);
			}
		}
		if(isset($consultation_data['medicine_suggestion_ipd']) && $consultation_data['medicine_suggestion_ipd'] == 1) {
			$print_data['medicines_ipd']['enabled'] = true;
			
			if(!empty($consultation_data['female_medicine_suggestion_list_ipd'])) {
				$print_data['medicines_ipd']['female'] = unserialize($consultation_data['female_medicine_suggestion_list_ipd']);
			}
			if(!empty($consultation_data['male_medicine_suggestion_list_ipd'])) {
				$print_data['medicines_ipd']['male'] = unserialize($consultation_data['male_medicine_suggestion_list_ipd']);
			}
		}
		if(isset($consultation_data['procedure_suggestion']) && $consultation_data['procedure_suggestion'] == 1) {
			$print_data['procedures']['enabled'] = true;
			
			if(!empty($consultation_data['sub_procedure_suggestion_list'])) {
				$print_data['procedures']['list'] = unserialize($consultation_data['sub_procedure_suggestion_list']);
			}
		}
		if(isset($consultation_data['package_suggestion']) && $consultation_data['package_suggestion'] == 1) {
			$print_data['packages']['enabled'] = true;
			
			if(!empty($consultation_data['package_suggestion_list'])) {
				$print_data['packages']['list'] = unserialize($consultation_data['package_suggestion_list']);
			}
		}
		
		// Process follow-up
		if(isset($consultation_data['follow_up']) && $consultation_data['follow_up'] == 1) {
			$print_data['follow_up']['enabled'] = true;
			$print_data['follow_up']['date'] = $consultation_data['follow_up_date'] ?? '';
			$print_data['follow_up']['slot'] = $consultation_data['follow_slot'] ?? '';
			$print_data['follow_up']['purpose'] = $consultation_data['follow_up_purpose'] ?? '';
		}
		
		return $print_data;
	}
}