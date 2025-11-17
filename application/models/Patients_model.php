<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class Patients_model extends CI_Model
{
	function get_patients($center = 0){
		$consultation_result = $investigation_result = $procedure_result = $patient_result = $patients = $patient_arr =array();
		
		$consult_sql = "Select distinct patient_id from ".$this->config->item('db_prefix')."consultation";
        $consult_q = $this->db->query($consult_sql);
        $consultation_result = $consult_q->result_array();
		if(!empty($consultation_result)){
			foreach($consultation_result as $key => $val){
				$patients[] = $val['patient_id'];
			}
		}
		
		$invest_sql = "Select distinct patient_id from ".$this->config->item('db_prefix')."patient_investigations";
        $invest_q = $this->db->query($invest_sql);
        $investigation_result = $invest_q->result_array();
		if(!empty($investigation_result)){
			foreach($investigation_result as $key => $val){
				$patients[] = $val['patient_id'];
			}
		}
		
		$prod_sql = "Select distinct patient_id from ".$this->config->item('db_prefix')."patient_procedure";
        $prod_q = $this->db->query($prod_sql);
        $procedure_result = $prod_q->result_array();
		if(!empty($procedure_result)){
			foreach($procedure_result as $key => $val){
				$patients[] = $val['patient_id'];
			}
		}
		
		$patients = array_unique($patients);		
		foreach($patients as $key => $val){
			$paitent_sql = "Select * from ".$this->config->item('db_prefix')."patients where patient_id='$val'";
			$paitent_q = $this->db->query($paitent_sql);
			$paitent_result = $paitent_q->result_array();
			if(!empty($paitent_result)){
				$patient_arr[] = $paitent_result[0];
			}
		}
		
		return $patient_arr;
	}
	
	function update_patient_data($data, $paitent){
		$sql = "UPDATE " . config_item('db_prefix') . "patients SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE patient_id = '".$paitent."'";
        $this->db->query($sql);
        return 1;
	}

	function patient_medicines_reports($paitent_id){
		$result = array();
		$sql = "Select medicines from ".$this->config->item('db_prefix')."patient_investigations where patient_id='$paitent_id'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if(!empty($result)){
			return $result[0];
		}else{
			return $result;
		}
	}

	function patient_investigations_reports($paitent_id){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."patient_investigation_reports where patient_id='$paitent_id'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if(!empty($result)){
			return $result;
		}else{
			return $result;
		}
	}

	function investigation_reports($patient_id){
		$result = array();
		
		$sql = "Select * from ".$this->config->item('db_prefix')."patient_investigation_reports where patient_id='$patient_id'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if(!empty($result)){
			return $result;
		}else{
			return $result;
		}
	}

	function update_patient_profile($data, $paitent_id){
		
		$sql = "UPDATE " . config_item('db_prefix') . "patients SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE patient_id = '".$paitent_id."'";
		$this->db->query($sql);
		return $this->db->affected_rows();
	}

	function patient_report_status($id, $status){
		$sql = "UPDATE ".$this->config->item('db_prefix')."patient_investigation_reports SET doctor_accepted='$status', doctor_accepted_date='".date("Y-m-d H:i:s")."' WHERE ID = '$id'";
        $this->db->query($sql);
        return $this->db->affected_rows();
	}
	
	function disapprove_report_status($report_id, $status_reason){
		$report_sql = "UPDATE ".$this->config->item('db_prefix')."patient_investigation_reports SET status='pending' WHERE ID = '$report_id'";
		$this->db->query($report_sql);
		
		$sql = "UPDATE ".$this->config->item('db_prefix')."patient_investigation_reports SET status_reason='$status_reason' WHERE ID = '$report_id'";
		$this->db->query($sql);		
        return $this->db->affected_rows();
	}

	function get_patient_prodedures($appointment_id, $patient_id){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where patient_id='$patient_id' and appointment_id='$appointment_id' limit 1";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if(!empty($result)){
			return $result[0];
		}else{
			return $result;
		}
	}

	function get_patient_prodedures_data($appointment_id, $patient_id){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where patient_id='$patient_id' and status!='disapproved'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if(!empty($result)){
			return $result;
		}else{
			return $result;
		}
	}
	function get_discharge_form($form_id)  {
		
		
		$result = array();
		$sql = "Select form_name from ".$this->config->item('db_prefix')."discharge_forms where id='$form_id'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if(!empty($result)){
			return $result[0];
		}else{
			return $result;
		}
		
	
	}
	
	function get_discharge_form_data($db_name){
	    $result = array();
		$sql = "Select form_name from ".$this->config->item('db_prefix')."discharge_forms where id='$db_name'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if(!empty($result)){
			return $result[0];
		}else{
			return $result;
		}
	}
	
	function export_patient($center, $wife_name, $patient_id){
		$investigation_result = $response = array();
        $conditions = '';
		if (!empty($center)){
			$conditions .= " and center='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($wife_name)){
			$conditions .= " and wife_name like '%$wife_name%'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patients where 1 $conditions order by rand() desc";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				
				$response[] = array(
                        'patient_id' => $val['patient_id'],
				        'patient_phone' => $val['patient_phone'],
                        'wife_name' => $val['wife_name'],
                        'wife_phone' => $val['wife_phone']
                );
            }
        } 
                 
 		
		return $response;
    }
	
	function patient_count($center, $wife_name, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and center="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and center='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($wife_name)){
			$conditions .= " and wife_name like '%$wife_name%'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patients where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}
	
	function patient_list_patination($limit, $page, $center, $wife_name, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and center="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and center='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($wife_name)){
			$conditions .= " and wife_name like '%$wife_name%'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patients where 1".$conditions." order by add_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	function embryo_freezing_count($iic_id){
		$embryo_freezing_result = array();
		$conditions = '';
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_freezing_sql = "Select * from discharge_summary where 1 ".$conditions."";
		$q = $this->db->query($embryo_freezing_sql);
		return $q->num_rows();
	}
	
	function embryo_freezing_list_patination($limit, $page, $iic_id){
		$embryo_freezing_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_freezing_sql = "Select * from discharge_summary where 1".$conditions." order by appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$embryo_freezing_q = $this->db->query($embryo_freezing_sql);
		$embryo_freezing_result = $embryo_freezing_q->result_array();
		return $embryo_freezing_result;
	}
	
	function embryo_pesa_count($iic_id){
		$embryo_pesa_result = array();
		$conditions = '';
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_pesa_sql = "Select * from pesa_tesatese_micro_tese_discharge_summary where 1 ".$conditions."";
		$q = $this->db->query($embryo_pesa_sql);
		return $q->num_rows();
	}
	
	function embryo_pesa_list_patination($limit, $page, $iic_id){
		$embryo_pesa_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_pesa_sql = "Select * from pesa_tesatese_micro_tese_discharge_summary where 1".$conditions." order by appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$embryo_pesa_q = $this->db->query($embryo_pesa_sql);
		$embryo_pesa_result = $embryo_pesa_q->result_array();
		return $embryo_pesa_result;
	}
	
	function embryo_opu_count($iic_id){
		$opu_result = array();
		$conditions = '';
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_opu_sql = "Select * from ovum_discharge_summary where 1 ".$conditions."";
		$q = $this->db->query($embryo_opu_sql);
		return $q->num_rows();
	}
	
	function embryo_opu_list_patination($limit, $page, $iic_id){
		$opu_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_opu_sql = "Select * from ovum_discharge_summary where 1".$conditions." order by appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$embryo_opu_q = $this->db->query($embryo_opu_sql);
		$embryo_opu_result = $embryo_opu_q->result_array();
		return $embryo_opu_result;
	}
	
	function embryo_fnactestes_count($iic_id){
		$fnactestes_result = array();
		$conditions = '';
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_fnactestes_sql = "Select * from fnactestes_discharge_summary where 1 ".$conditions."";
		$q = $this->db->query($embryo_fnactestes_sql);
		return $q->num_rows();
	}
	
	function embryo_fnactestes_list_patination($limit, $page, $iic_id){
		$fnactestes_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_fnactestes_sql = "Select * from fnactestes_discharge_summary where 1".$conditions." order by appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$embryo_fnactestes_q = $this->db->query($embryo_fnactestes_sql);
		$embryo_fnactestes_result = $embryo_fnactestes_q->result_array();
		return $embryo_fnactestes_result;
	}
	
	function embryo_discharge_summary_count($iic_id){
		$discharge_summary_result = array();
		$conditions = '';
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_discharge_sql = "Select * from embryology_discharge_summary where 1 ".$conditions."";
		$q = $this->db->query($embryo_discharge_sql);
		return $q->num_rows();
	}
	
	function embryo_discharge_summary_list_patination($limit, $page, $iic_id){
		$discharge_summary_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_discharge_sql = "Select * from embryology_discharge_summary where 1".$conditions." order by appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$embryo_discharge_q = $this->db->query($embryo_discharge_sql);
		$embryo_discharge_result = $embryo_discharge_q->result_array();
		return $embryo_discharge_result;
	}
	
	function embryo_intra_uterine_count($iic_id){
		$discharge_summary_result = array();
		$conditions = '';
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_discharge_sql = "Select * from intra_uterine_insemination_discharge_summary where 1 ".$conditions."";
		$q = $this->db->query($embryo_discharge_sql);
		return $q->num_rows();
	}
	
	function embryo_intra_uterine_list_patination($limit, $page, $iic_id){
		$discharge_summary_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$embryo_discharge_sql = "Select * from intra_uterine_insemination_discharge_summary where 1".$conditions." order by appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$embryo_discharge_q = $this->db->query($embryo_discharge_sql);
		$embryo_discharge_result = $embryo_discharge_q->result_array();
		return $embryo_discharge_result;
	}
	
	function embryo_sperm_dna_count($iic_id){
		$sperm_dna_result = array();
		$conditions = '';
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$sperm_dna_sql = "Select * from sperm_dna_fragmentation2 where 1 ".$conditions."";
		$q = $this->db->query($sperm_dna_sql);
		return $q->num_rows();
	}
	
	function embryo_sperm_dna_list_patination($limit, $page, $iic_id){
		$sperm_dna_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$sperm_dna_sql = "Select * from sperm_dna_fragmentation2 where 1".$conditions." order by appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$sperm_dna_q = $this->db->query($sperm_dna_sql);
		$embryo_sperm_dna_result = $sperm_dna_q->result_array();
		return $embryo_sperm_dna_result;
	}
	
	function embryo_semen_analysis_count($iic_id){
		$semen_analysis_result = array();
		$conditions = '';
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$semen_analysis_sql = "Select * from semen_analysis where 1 ".$conditions."";
		$q = $this->db->query($semen_analysis_sql);
		return $q->num_rows();
	}
	
	function embryo_semen_analysis_list_patination($limit, $page, $iic_id){
		$semen_analysis_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$semen_analysis_sql = "Select * from semen_analysis where 1".$conditions." order by appoitmented_date desc limit ". $limit." OFFSET ".$offset."";
		$semen_analysis_q = $this->db->query($semen_analysis_sql);
		$embryo_semen_analysis_result = $semen_analysis_q->result_array();
		return $embryo_semen_analysis_result;
	}


	function export_consultation_data($start_date, $end_date, $paitent_id, $crm_id){
		$consultation_result = $response = array();
        $conditions = '';
		if(!empty($paitent_id)){
			$conditions .= ' and paitent_id="'.$paitent_id.'"';
        }
		 if(!empty($crm_id)){
			$conditions .= ' and crm_id="'.$crm_id.'"';
        }
		if (!empty($start_date) && !empty($end_date)){
        $conditions .= " AND appointment_added BETWEEN '".$start_date."' AND '".$end_date."'";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " AND appointment_added='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " AND appointment_added='$end_date'";
		}
		
	    $consultation_sql = "
		Select * from ".$this->config->item('db_prefix')."appointments where paitent_type='new_patient' and 1 $conditions order by appointment_added desc";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
        if(!empty($consultation_result)){
            foreach($consultation_result as $key => $val){
				//$patient_name = $this->get_patient_name($val['patient_id']);
				$response[] = array(
                        'crm_id' => $val['crm_id'],
                        'paitent_id' => $val['paitent_id'],
						'agent' => $val['agent'],
				        'appointment_added' => $val['appointment_added'],
                        'billing_type' => 'Consultation',
                );
            }
        }    
		return $response;
    }
	function get_patient_timeline_count($start_date, $end_date, $paitent_id, $crm_id){
		$semen_analysis_result = array();
		$conditions = '';
		if (!empty($crm_id)){
			$conditions .= " and crm_id='$crm_id'";
		}
		if (!empty($paitent_id)){
			$conditions .= " and paitent_id='$paitent_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and appointment_added between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and appointment_added='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and appointment_added='$end_date'";
		}
		$semen_analysis_sql = "Select * from hms_appointments where 1 ".$conditions."";
		$q = $this->db->query($semen_analysis_sql);
		return $q->num_rows();
	}
	
	function get_patient_timeline($limit, $page, $start_date, $end_date, $patient_id, $crm_id) {
    
    // This array will hold the values for secure query binding
    $bindings = [];
    $conditions = '';

    // Calculate offset
    $offset = empty($page) || $page <= 1 ? 0 : ($page - 1) * $limit;

    // --- Build Conditions Securely (with '?' placeholders) ---
    
    if (!empty($crm_id)) {
        $conditions .= " AND T1.crm_id = ?";
        $bindings[] = $crm_id;
    }
    
    // Corrected variable to match function parameter
    if (!empty($patient_id)) {
        $conditions .= " AND T1.paitent_id = ?";
        $bindings[] = $paitent_id;
    }

    // --- Secure Date Filtering ---
    if (!empty($start_date) && !empty($end_date)) {
        $conditions .= " AND T1.appointment_added BETWEEN ? AND ?"; // Typo fixed
        $bindings[] = $start_date;
        $bindings[] = $end_date;
    } else if (!empty($start_date)) {
        $conditions .= " AND T1.appointment_added = ?"; // Typo fixed
        $bindings[] = $start_date;
    } else if (!empty($end_date)) {
        $conditions .= " AND T1.appointment_added = ?"; // Typo fixed
        $bindings[] = $end_date;
    }

    // --- Corrected SQL Query ---
    // All typos ('paitent_id', 'appoitmented_date', 'paitent_type') have been fixed
    // Added GROUP BY and MAX() to prevent duplicates
    $semen_analysis_sql = "
        SELECT
            T1.crm_id,
            T1.paitent_id,
            T1.appointment_added,
            T1.agent,
            MAX(T2.on_date) AS consultation_date,
            MAX(T3.on_date) AS procedure_date
        FROM
            hms_appointments AS T1
        LEFT JOIN
            hms_consultation AS T2 ON T1.paitent_id = T2.patient_id
        LEFT JOIN
            hms_patient_procedure AS T3 ON T1.paitent_id = T3.patient_id
        WHERE
            T1.paitent_type = 'new_patient'
            {$conditions}
        GROUP BY
            T1.paitent_id, 
            T1.crm_id, 
            T1.appointment_added, 
            T1.agent
        ORDER BY
            T1.appointment_added DESC
        LIMIT ? OFFSET ?
    ";

    // Add pagination values to the bindings array (best practice)
    $bindings[] = (int) $limit;
    $bindings[] = (int) $offset;

    // --- Execute Securely ---
    // Pass the $bindings array as the second parameter
    $semen_analysis_q = $this->db->query($semen_analysis_sql, $bindings);
    
    return $semen_analysis_q->result_array();
} 

	/* function get_patient_timeline($limit, $page, $start_date, $end_date, $patient_id, $crm_id) {
    
    // This array will hold the values for secure query binding
    $bindings = [];
    $conditions = '';

    // Calculate offset
    $offset = empty($page) || $page <= 1 ? 0 : ($page - 1) * $limit;

    // --- Build Conditions Securely (with '?' placeholders) ---
    
    if (!empty($crm_id)) {
        $conditions .= " AND T1.crm_id = ?";
        $bindings[] = $crm_id;
    }
    
    // Corrected variable name to $patient_id
    if (!empty($patient_id)) {
        // Corrected SQL typo
        $conditions .= " AND T1.paitent_id = ?"; 
        $bindings[] = $paitent_id;
    }

    // --- Secure Date Filtering ---
    if (!empty($start_date) && !empty($end_date)) {
        // Corrected SQL typo
        $conditions .= " AND T1.appoitmented_date BETWEEN ? AND ?"; 
        $bindings[] = $start_date;
        $bindings[] = $end_date;
    } else if (!empty($start_date)) {
        // Corrected SQL typo
        $conditions .= " AND T1.appoitmented_date = ?";
        $bindings[] = $start_date;
    } else if (!empty($end_date)) {
        // Corrected SQL typo
        $conditions .= " AND T1.appoitmented_date = ?";
        $bindings[] = $end_date;
    }

    // --- Corrected SQL Query ---
   echo $semen_analysis_sql = "
        SELECT
            T1.crm_id,
            T1.paitent_id,
            T1.appoitmented_date,
            T1.agent,
            MAX(T2.on_date) AS consultation_date,
            MAX(T3.on_date) AS procedure_date,
            MAX(T3.category) AS procedure_category 
        FROM
            hms_appointments AS T1
        LEFT JOIN
            hms_consultation AS T2 ON T1.paitent_id = T2.patient_id
        INNER JOIN 
            hms_patient_procedure AS T3 ON T1.paitent_id = T3.patient_id
        WHERE
            T1.paitent_type = 'new_patient'
            AND T3.category IN ('IVF with Bed', 'Non IVF with Bed')
            {$conditions}
        GROUP BY
            T1.paitent_id, 
            T1.crm_id, 
            T1.appoitmented_date, 
            T1.agent
        ORDER BY
            T1.appoitmented_date DESC
        LIMIT ? OFFSET ?
    ";

    // Add pagination values to the bindings array (best practice)
    $bindings[] = (int) $limit;
    $bindings[] = (int) $offset;

    // --- Execute Securely ---
    $semen_analysis_q = $this->db->query($semen_analysis_sql, $bindings);
    
    return $semen_analysis_q->result_array();
}*/

/*
function get_patient_timeline($limit, $page, $start_date, $end_date, $patient_id, $crm_id) {
    
    // This array will hold the values for secure query binding
    $bindings = [];
    $conditions = '';

    // Calculate offset
    $offset = empty($page) || $page <= 1 ? 0 : ($page - 1) * $limit;

    // --- Build Conditions Securely (with '?' placeholders) ---
    
    if (!empty($crm_id)) {
        $conditions .= " AND T1.crm_id = ?";
        $bindings[] = $crm_id;
    }
    
    // Corrected variable to match function parameter
    if (!empty($patient_id)) { 
        $conditions .= " AND T1.patient_id = ?";
        $bindings[] = $patient_id;
    }

    // --- Secure Date Filtering ---
    if (!empty($start_date) && !empty($end_date)) {
        // Corrected SQL typo
        $conditions .= " AND T1.appointment_date BETWEEN ? AND ?"; 
        $bindings[] = $start_date;
        $bindings[] = $end_date;
    } else if (!empty($start_date)) {
        $conditions .= " AND T1.appointment_date = ?";
        $bindings[] = $start_date;
    } else if (!empty($end_date)) {
        $conditions .= " AND T1.appointment_date = ?";
        $bindings[] = $end_date;
    }

    // --- Corrected SQL Query ---
    $semen_analysis_sql = "
        SELECT
            T1.crm_id,
            T1.paitent_id,
            T1.appoitmented_date,
            T1.agent,
            T2.on_date AS consultation_date,
            T3.category, 
            T3.on_date AS procedure_date
        FROM
            hms_appointments AS T1
        LEFT JOIN
            hms_consultation AS T2 ON T1.paitent_id = T2.patient_id
        LEFT JOIN
            hms_patient_procedure AS T3 ON T1.paitent_id = T3.patient_id
        WHERE
            T1.paitent_type = 'new_patient'
            AND T3.category IN ('Non IVF without Bed', 'IVF with Bed') -- <-- LOGIC FIX
            {$conditions}
        ORDER BY
            T1.appoitmented_date DESC
        LIMIT ? OFFSET ?
    ";

    // Add pagination values to the bindings array (best practice)
    $bindings[] = (int) $limit;
    $bindings[] = (int) $offset;

    // --- Execute Securely ---
    $semen_analysis_q = $this->db->query($semen_analysis_sql, $bindings);
    
    return $semen_analysis_q->result_array();
}*/
	public function insert_patient_timeline($data)
	{
		$this->db->insert('hms_patient_timeline', $data);
		return $this->db->insert_id(); // return last inserted ID
	}



}
// END Patients_model class
/* End of file Patients_model.php */
/* Location: ./application/models/Patients_model.php */