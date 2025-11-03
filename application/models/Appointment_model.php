<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class Appointment_model extends CI_Model
{
	function my_appointments($center){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments ORDER by appoitmented_date DESC";
		$q = $this->db->query($sql);
		$result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
	
	
	function export_my_appointments($start_date, $end_date, $center, $patient_id, $doctor, $patient_name, $paitent_type, $crm_id, $lead_source){
	    $appointments_result = $response = array();
        $conditions = '';
		if(empty($patient_name)){
            if(!empty($center)){
                $conditions .= " and appoitment_for='$center' and camp_selection=0";
            }
        }
		if(!empty($paitent_type)){
                $conditions .= " and paitent_type='$paitent_type'";
        }
        if(!empty($patient_id)){
            $conditions .= " and paitent_id='$patient_id'";
        }
        if(!empty($patient_name)){
            $conditions .= " and (wife_name like '%$patient_name%' or wife_phone like '%$patient_name%')";
        }
        if(!empty($status)){
            $conditions .= " and status='$status'";
        }
        if(!empty($doctor)){
            $conditions .= " and appoitmented_doctor='$doctor'";
        }
        if(!empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date between '".$start_date."' AND '".$end_date."' ";
        }
        if(!empty($start_date) && empty($end_date)){
            $conditions .= " and appoitmented_date='$start_date'";
        }
        if(empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date='$end_date'";
        }
		if(!empty($crm_id)){
                $conditions .= " and crm_id='$crm_id'";
        }
		if(!empty($lead_source)){
                $conditions .= " and lead_source='$lead_source'";
        }
	    $appointments_sql = "Select DISTINCT crm_id, paitent_id, wife_name, husband_name, wife_email, nationality,reason_of_visit, appoitmented_date,paitent_type,lead_source, status from ".$this->config->item('db_prefix')."appointments where 1 $conditions order by appoitmented_date desc";
        $appointments_q = $this->db->query($appointments_sql);
        $appointments_result = $appointments_q->result_array();
        if(!empty($appointments_result)){
            foreach($appointments_result as $key => $val){
				//$patient_name = $this->get_patient_name($val['paitent_id']);
				//$all_method->doctor_name($vl['appoitmented_doctor']);
				$response[] = array(
                        'crm_id' => $val['crm_id'],
                        'lead_source' => $val['lead_source'],
						'paitent_id' => $val['paitent_id'],
                        'wife_name' => $val['wife_name'],
				        'husband_name' => $val['husband_name'],
                        'nationality' => $val['nationality'],
                        'reason_of_visit' => $val['reason_of_visit'],
                        'appoitmented_date' => $val['appoitmented_date'],
						'paitent_type' => $val['paitent_type'],
                        'status' => $val['status'],
                        'billing_type' => 'Consultation',
                );
            }
        }    
		return $response;
    }

    function my_appointments_count($center, $start_date, $end_date, $patient_id, $patient_name, $status, $doctor, $paitent_type, $crm_id, $lead_source){
		$conditions = "";
       if(empty($patient_name)){
            if(!empty($center)){
                $conditions .= " and appoitment_for='$center' and camp_selection=0";
            }
        }
		if(!empty($paitent_type)){
                $conditions .= " and paitent_type='$paitent_type'";
        }
        if(!empty($patient_id)){
            $conditions .= " and paitent_id='$patient_id'";
        }
        if(!empty($patient_name)){
            $conditions .= " and (wife_name like '%$patient_name%' or wife_phone like '%$patient_name%')";
        }
        if(!empty($status)){
            $conditions .= " and status='$status'";
        }
        if(!empty($doctor)){
            $conditions .= " and appoitmented_doctor='$doctor'";
        }
        if(!empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date between '".$start_date."' AND '".$end_date."' ";
        }
        if(!empty($start_date) && empty($end_date)){
            $conditions .= " and appoitmented_date='$start_date'";
        }
        if(empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date='$end_date'";
        }
		if(!empty($crm_id)){
                $conditions .= " and crm_id='$crm_id'";
        }
		if(!empty($lead_source)){
                $conditions .= " and lead_source='$lead_source'";
        }
        $sql = "Select a.* from ".$this->config->item('db_prefix')."appointments a LEFT JOIN ".$this->config->item('db_prefix')."camps c ON a.camp_selection = c.camp_number where 1 ".$conditions."";
		$q = $this->db->query($sql);
		return $q->num_rows();
	}

    function my_appointments_pagination($limit, $page, $center, $start_date, $end_date, $patient_id, $patient_name, $status, $doctor, $paitent_type,$crm_id, $lead_source){
        if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
        $conditions = "";
        if(empty($patient_name)){
            if(!empty($center)){
                $conditions .= " and appoitment_for='$center'";
            }
        }
		if(!empty($paitent_type)){
                $conditions .= " and paitent_type='$paitent_type'";
        }
        if(!empty($patient_id)){
            $conditions .= " and paitent_id='$patient_id'";
        }
        if(!empty($patient_name)){
            $conditions .= " and (wife_name like '%$patient_name%' or wife_phone like '%$patient_name%')";
        }
        if(!empty($status)){
            $conditions .= " and status='$status'";
        }
        if(!empty($doctor)){
            $conditions .= " and appoitmented_doctor='$doctor'";
        }
        if(!empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date between '".$start_date."' AND '".$end_date."' ";
        }
        if(!empty($start_date) && empty($end_date)){
            $conditions .= " and appoitmented_date='$start_date'";
        }
        if(empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date='$end_date'";
        }
		if(!empty($crm_id)){
                $conditions .= " and crm_id='$crm_id'";
        }
		if(!empty($lead_source)){
                $conditions .= " and lead_source='$lead_source'";
        }
        $result = array();
$sql = "SELECT 
    a.*, 
    c.camp_name, 
    d.doctor_name
FROM 
    ".$this->config->item('db_prefix')."appointments AS a
LEFT JOIN 
    ".$this->config->item('db_prefix')."camps AS c 
    ON a.camp_selection = c.id
LEFT JOIN 
    ".$this->config->item('db_prefix')."doctor_referral AS d 
    ON a.sub_lead_source = d.ID
WHERE 
    1 ".$conditions."
ORDER BY 
    a.appoitmented_date limit ". $limit." OFFSET ".$offset."";


       // $sql = "Select a.*, c.camp_name from ".$this->config->item('db_prefix')."appointments a LEFT JOIN ".$this->config->item('db_prefix')."camps c ON a.camp_selection = c.camp_number where 1 ".$conditions." ORDER by a.appoitmented_date DESC limit ". $limit." OFFSET ".$offset."";
		
		// $sql = "Select * from ".$this->config->item('db_prefix')."appointments where 1 ".$conditions." ORDER by appoitmented_date DESC limit ". $limit." OFFSET ".$offset."";
        $q = $this->db->query($sql);
		$result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
    function export_my_appointments_in_camp($start_date, $end_date, $center, $patient_id, $doctor, $patient_name, $paitent_type, $crm_id, $lead_source, $camp_id = ''){
	    $appointments_result = $response = array();
        $conditions = '';
		if(empty($patient_name)){
            if(!empty($center)){
                $conditions .= " and appoitment_for='$center' and camp_selection != 0 and camp_selection IS NOT NULL";
                // Additional check: ensure the camp exists for this center
                $conditions .= " and EXISTS (SELECT 1 FROM ".$this->config->item('db_prefix')."camps c WHERE c.camp_number = a.camp_selection AND c.center_id = (SELECT ID FROM ".$this->config->item('db_prefix')."centers WHERE center_number = '$center'))";
            }
        } else {
            // Even when searching by patient name, only show camp appointments
            $conditions .= " and camp_selection != 0 and camp_selection IS NOT NULL";
            if(!empty($center)){
                // Additional check: ensure the camp exists for this center
                $conditions .= " and EXISTS (SELECT 1 FROM ".$this->config->item('db_prefix')."camps c WHERE c.camp_number = a.camp_selection AND c.center_id = (SELECT ID FROM ".$this->config->item('db_prefix')."centers WHERE center_number = '$center'))";
            }
        }
		if(!empty($paitent_type)){
                $conditions .= " and paitent_type='$paitent_type'";
        }
        if(!empty($patient_id)){
            $conditions .= " and paitent_id='$patient_id'";
        }
        if(!empty($patient_name)){
            $conditions .= " and (wife_name like '%$patient_name%' or wife_phone like '%$patient_name%')";
        }
        if(!empty($status)){
            $conditions .= " and status='$status'";
        }
        if(!empty($doctor)){
            $conditions .= " and appoitmented_doctor='$doctor'";
        }
        if(!empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date between '".$start_date."' AND '".$end_date."' ";
        }
        if(!empty($start_date) && empty($end_date)){
            $conditions .= " and appoitmented_date='$start_date'";
        }
        if(empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date='$end_date'";
        }
		if(!empty($crm_id)){
                $conditions .= " and crm_id='$crm_id'";
        }
		if(!empty($lead_source)){
                $conditions .= " and lead_source='$lead_source'";
        }
		if(!empty($camp_id)){
                $conditions .= " and camp_selection='$camp_id'";
        }
	    $appointments_sql = "Select DISTINCT a.crm_id, a.paitent_id, a.wife_name, a.husband_name, a.wife_email, a.nationality, a.reason_of_visit, a.appoitmented_date, a.paitent_type, a.lead_source, a.status, c.camp_name from ".$this->config->item('db_prefix')."appointments a LEFT JOIN ".$this->config->item('db_prefix')."camps c ON a.camp_selection = c.camp_number where 1 $conditions order by a.appoitmented_date desc";
        $appointments_q = $this->db->query($appointments_sql);
        $appointments_result = $appointments_q->result_array();
        if(!empty($appointments_result)){
            foreach($appointments_result as $key => $val){
				//$patient_name = $this->get_patient_name($val['paitent_id']);
				//$all_method->doctor_name($vl['appoitmented_doctor']);
				$response[] = array(
                        'crm_id' => $val['crm_id'],
                        'lead_source' => $val['lead_source'],
						'paitent_id' => $val['paitent_id'],
                        'wife_name' => $val['wife_name'],
				        'husband_name' => $val['husband_name'],
                        'nationality' => $val['nationality'],
                        'reason_of_visit' => $val['reason_of_visit'],
                        'appoitmented_date' => $val['appoitmented_date'],
						'paitent_type' => $val['paitent_type'],
                        'status' => $val['status'],
                        'billing_type' => 'Consultation',
                );
            }
        }    
		return $response;
    }

    function my_appointments_count_in_camp($center, $start_date, $end_date, $patient_id, $patient_name, $status, $doctor, $paitent_type, $crm_id, $lead_source, $camp_id = ''){
		$conditions = "";
       /*if(empty($patient_name)){
            if(!empty($center)){
                $conditions .= " and appoitment_for='$center' and camp_selection != 0 and camp_selection IS NOT NULL";
                // Additional check: ensure the camp exists for this center
                $conditions .= " and EXISTS (SELECT 1 FROM ".$this->config->item('db_prefix')."camps c WHERE c.camp_number = a.camp_selection AND c.center_id = (SELECT ID FROM ".$this->config->item('db_prefix')."centers WHERE center_number = '$center'))";
            }
        } else {
            // Even when searching by patient name, only show camp appointments
            $conditions .= " and camp_selection != 0 and camp_selection IS NOT NULL";
            if(!empty($center)){
                // Additional check: ensure the camp exists for this center
                $conditions .= " and EXISTS (SELECT 1 FROM ".$this->config->item('db_prefix')."camps c WHERE c.camp_number = a.camp_selection AND c.center_id = (SELECT ID FROM ".$this->config->item('db_prefix')."centers WHERE center_number = '$center'))";
            }
        }*/
		if(!empty($paitent_type)){
                $conditions .= " and paitent_type='$paitent_type'";
        }
        if(!empty($patient_id)){
            $conditions .= " and paitent_id='$patient_id'";
        }
        if(!empty($patient_name)){
            $conditions .= " and (wife_name like '%$patient_name%' or wife_phone like '%$patient_name%')";
        }
        if(!empty($status)){
            $conditions .= " and status='$status'";
        }
        if(!empty($doctor)){
            $conditions .= " and appoitmented_doctor='$doctor'";
        }
        if(!empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date between '".$start_date."' AND '".$end_date."' ";
        }
        if(!empty($start_date) && empty($end_date)){
            $conditions .= " and appoitmented_date='$start_date'";
        }
        if(empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date='$end_date'";
        }
		if(!empty($crm_id)){
                $conditions .= " and crm_id='$crm_id'";
        }
		if(!empty($lead_source)){
                $conditions .= " and lead_source='$lead_source'";
        }
		/*if(!empty($camp_id)){
                $conditions .= " and camp_selection='$camp_id'";
        }*/
        $sql = "Select a.* from ".$this->config->item('db_prefix')."appointments a LEFT JOIN ".$this->config->item('db_prefix')."camps c ON a.camp_selection = c.camp_number where 1 ".$conditions."";
		$q = $this->db->query($sql);
		return $q->num_rows();
	}

    function my_appointments_pagination_in_camp($limit, $page, $center, $start_date, $end_date, $patient_id, $patient_name, $status, $doctor, $paitent_type,$crm_id, $lead_source, $camp_id = ''){
        if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
        $conditions = "";
        if(empty($patient_name)){
            if(!empty($center)){
                $conditions .= " and appoitment_for='$center' and camp_selection != 0 and camp_selection IS NOT NULL";
                // Additional check: ensure the camp exists for this center
                $conditions .= " and EXISTS (SELECT 1 FROM ".$this->config->item('db_prefix')."camps c WHERE c.camp_number = a.camp_selection AND c.center_id = (SELECT ID FROM ".$this->config->item('db_prefix')."centers WHERE center_number = '$center'))";
            }
        } else {
            // Even when searching by patient name, only show camp appointments
            $conditions .= " and camp_selection != 0 and camp_selection IS NOT NULL";
            if(!empty($center)){
                // Additional check: ensure the camp exists for this center
                $conditions .= " and EXISTS (SELECT 1 FROM ".$this->config->item('db_prefix')."camps c WHERE c.camp_number = a.camp_selection AND c.center_id = (SELECT ID FROM ".$this->config->item('db_prefix')."centers WHERE center_number = '$center'))";
            }
        }
		if(!empty($paitent_type)){
                $conditions .= " and paitent_type='$paitent_type'";
        }
        if(!empty($patient_id)){
            $conditions .= " and paitent_id='$patient_id'";
        }
        if(!empty($patient_name)){
            $conditions .= " and (wife_name like '%$patient_name%' or wife_phone like '%$patient_name%')";
        }
        if(!empty($status)){
            $conditions .= " and status='$status'";
        }
        if(!empty($doctor)){
            $conditions .= " and appoitmented_doctor='$doctor'";
        }
        if(!empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date between '".$start_date."' AND '".$end_date."' ";
        }
        if(!empty($start_date) && empty($end_date)){
            $conditions .= " and appoitmented_date='$start_date'";
        }
        if(empty($start_date) && !empty($end_date)){
            $conditions .= " and appoitmented_date='$end_date'";
        }
		if(!empty($crm_id)){
                $conditions .= " and crm_id='$crm_id'";
        }
		if(!empty($lead_source)){
                $conditions .= " and lead_source='$lead_source'";
        }
		if(!empty($camp_id)){
                $conditions .= " and camp_selection='$camp_id'";
        }
        $result = array();
		$sql = "Select a.*, c.camp_name from ".$this->config->item('db_prefix')."appointments a LEFT JOIN ".$this->config->item('db_prefix')."camps c ON a.camp_selection = c.camp_number where 1 ".$conditions." ORDER by a.appoitmented_date DESC limit ". $limit." OFFSET ".$offset."";
		$q = $this->db->query($sql);
		$result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
	
	function get_daily_appointments(){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments WHERE date(appoitmented_date)='".date('Y-m-d')."' ORDER by ID DESC";
		$q = $this->db->query($sql);
		$result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
	
/*	function pending_consultation_billing($center){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where billed='0' and status!='cancelled' ORDER by appoitmented_date DESC";
		$q = $this->db->query($sql);
		$result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	} */
	
	function pending_consultation_billing($center, $wife_name){
		$investigation_result = array();
		$conditions = '';
		if (!empty($wife_name)){
			$conditions .= " and wife_name like '%$wife_name%'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."appointments where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}
	
		function pending_consultation_billing_patination($limit, $page, $center, $wife_name){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($wife_name)){
			$conditions .= " and wife_name like '%$wife_name%'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."appointments where billed='0' and status!='cancelled' AND 1".$conditions." order by appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	function doctor_appointment_details($appointment_id){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where ID='$appointment_id'";
		$q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0];
        }
        else
        {
            return $result;
        }
	}
	
	function appointment_status($appointment_status, $appointment_id){
		$sql = "UPDATE `".config_item('db_prefix')."appointments` SET `status`='$appointment_status' WHERE ID='$appointment_id'";
        $this->db->query($sql);
        return $this->db->affected_rows();
	}
	
	
	function reschedule_appointment_update($data, $appointment_id){
		$sql = "UPDATE " . config_item('db_prefix') . "appointments SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$appointment_id."'";
        $this->db->query($sql);
        return $this->db->affected_rows();
	}
		
	function patient_procedure($appointment_id){
		$result = array();
		$sql = "Select patient_id, receipt_number, appointment_id, ID as patient_procedure_id, data from ".$this->config->item('db_prefix')."patient_procedure where appointment_id='$appointment_id' order by ID desc limit 5";
		$q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0];
        }else
        {
            return $result;
        }
	}
	
	//Ajax
	function ajax_appointment_status_data($status){
		$condition = "";
		if(isset($_SESSION['logged_billing_manager'])){ 
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " appoitment_for='$center' and ";
		}
		
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $condition status='$status' ORDER by appoitmented_date DESC";
		$q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
	
	function ajax_appointment_doctor_data($doctor){
		$condition = "";
		if(isset($_SESSION['logged_billing_manager'])){ 
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " appoitment_for='$center' and ";
		}
		
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $condition appoitmented_doctor='$doctor' ORDER by appoitmented_date DESC";
		$q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
	
	function ajax_appointment_date_wise_data($start, $end){
		$condition = "";
		if(isset($_SESSION['logged_billing_manager'])){ 
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " appoitment_for='$center' and ";
		}
	
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $condition appoitmented_date between '".$start."' AND '".$end."'";
		$q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
	
	function ajax_appointment_particular_date_data($appointment_date){
		$condition = "";
		if(isset($_SESSION['logged_billing_manager'])){ 
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " appoitment_for='$center' and ";
		}
		
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where $condition appoitmented_date='$appointment_date' ORDER by appoitmented_date DESC";
		$q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}
	
	function get_all_lead_sources(){
		$result = array();
		$sql = "SELECT DISTINCT lead_source FROM ".$this->config->item('db_prefix')."appointments WHERE lead_source IS NOT NULL AND lead_source != '' ORDER BY lead_source ASC";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		if (!empty($result))
		{
			return $result;
		}
		else
		{
			return array();
		}
	}
	
	function updateStatusAndDate($wife_phone, $center, $new_status, $appoitmented_date) {
        $this->db->where('wife_phone', $wife_phone);
        $this->db->where('appoitment_for', $center);
        $this->db->where('status', 'booked');
        $this->db->update('appointments', [
            'status' => $new_status,
            'appoitmented_date' => $appoitmented_date
        ]);
        return $this->db->affected_rows() > 0;
    }

public function get_counselors_grouped_by_center()
{
    // Initialize center_id
    $center_id = null;

    // 1. Try to get the center from the counselor session data
    if (isset($_SESSION['logged_counselor']['center'])) {
        $center_id = $_SESSION['logged_counselor']['center'];
    } 
    // 2. If not found, try to get it from the billing manager session data
    elseif (isset($_SESSION['logged_billing_manager']['center'])) {
        $center_id = $_SESSION['logged_billing_manager']['center'];
    }
    
    // Validate center_id
    if (empty($center_id)) {
        return []; // Return empty array if no valid center_id is found
    }
    
    // Database Query
    $this->db->select('center_id, id, name');
    $this->db->from('hms_employees');
    $this->db->where('role', 'counselor');
    $this->db->where('status', '1');
    $this->db->where('center_id', $center_id); 
    $this->db->order_by('name');
    
    $query = $this->db->get();
    $result = $query->result_array();

    // Grouping the result (as requested by the function name)
    $grouped = [];
    if (!empty($result)) {
        // Group all results under the single retrieved center_id key
        $grouped[$center_id] = $result;
    }
    
    return $grouped;
}

public function update_appointment($appointment_id, $data) {
    // 1. Sets the WHERE clause: WHERE `ID` = '20025'
    $this->db->where('ID', $appointment_id); 
    
    // 2. Executes the UPDATE: SET `councellor` = 'Harse'
    return $this->db->update('hms_appointments', $data); 
}  

// ... existing code in Appointment_model.php

public function log_change($log_data) {
    // Generates: INSERT INTO `hms_appointment_logs` (...) VALUES (...)
    return $this->db->insert('hms_appointment_logs', $log_data);
}



// ... rest of the model

}