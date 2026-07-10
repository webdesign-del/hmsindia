<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class Billingmodel_model extends CI_Model
{
	function insert_appointments($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "appointments` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
		}		
		$sql .= implode(',' , $sqlArr);
		
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}

	
	function insert_crm_appointments($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "crmappointments` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
		}		
		$sql .= implode(',' , $sqlArr);
		
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}
	
	function check_appointments($appointment_id){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where ID='$appointment_id' and (status='booked' OR status='rescheduled' OR status='in_clinic')";
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
	
	function update_appointment($appointment, $uhid){
		$sql = "UPDATE ".$this->config->item('db_prefix')."appointments SET uhid='$uhid', status='visited', billed='1'  WHERE ID='$appointment'";
        $this->db->query($sql);
        return $this->db->affected_rows();
	}
	
	function search_consultation_done($search_this, $search_by){
		$result = array();
		$sql_condition = '';
		if($search_by == 'patient'){
			$sql_condition = " patient_id='".$search_this."'";
		}else if($search_by == 'phone'){
			$sql_condition = " wife_phone='".$search_this."'";	
		}else{
			return $result;
		}
		
		$sql = "Select ID, medicine_suggestion,procedure_suggestion,investation_suggestion,package_suggestion,medicine_billed,investigation_billed,procedure_billed,package_billed from ".$this->config->item('db_prefix')."doctor_consultation where".$sql_condition." order by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            $billed = false;
            foreach($result as $bill){
                if($bill['medicine_suggestion'] == 1 && $bill['medicine_billed'] == 0){
                    $billed = true;
                }else if($bill['procedure_suggestion'] == 1 && $bill['procedure_billed'] == 0){
                    $billed = true;
				}else if($bill['package_suggestion'] == 1 && $bill['package_billed'] == 0){
                    $billed = true;	
                }else if($bill['investation_suggestion'] == 1 && $bill['investigation_billed'] == 0){
                    $billed = true;
                }
                if($billed == true){
                    return $bill;
                }
            }
            return $result[0];
        }
        else
        {
            return $result;
        }
    }
    
	function after_consultation_billing($id){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where ID='$id' AND (medicine_billed= '0' OR investigation_billed='0' OR procedure_billed='0')";
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
    
    function investigation_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "patient_investigations` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}
		$sql .= implode(',' , $sqlArr);
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
    }
    
	function patient_procedure_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "patient_procedure` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}
		$sql .= implode(',' , $sqlArr);
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}
	
	function partial_consultation_count($wife_name, $patient_id){
		$partial_consultation_billing = array();
		$conditions = '';
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($wife_name)){
			$conditions .= " and wife_name like '$wife_name'";
		}
		/*if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and a.consultation_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and a.consultation_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and a.consultation_date='$end_date'";
		}*/
		$patient_payments_sql = "SELECT d.ID as doctor_consultation_id, d.doctor_id, d.patient_id, a.ID, a.wife_name, a.wife_phone from ".$this->config->item('db_prefix')."appointments a join ".$this->config->item('db_prefix')."doctor_consultation d   where d.appointment_id = a.ID and a.partial_billing=1 and d.medicine_billed=0 and d.investigation_billed = 0 and d.procedure_billed = 0";
		$q = $this->db->query($patient_payments_sql);
		return $q->num_rows();
		
	}
	
	function partial_consultation_pagination($limit, $page, $wife_name, $patient_id){
		$partial_consultation_billing = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($patient_id)){
			$conditions .= " and d.patient_id='$patient_id'";
		}
		if (!empty($wife_name)){
			$conditions .= " and a.wife_name='$wife_name'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and a.consultation_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and a.consultation_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and a.consultation_date='$end_date'";
		}
		
		
		$procedure_sql = "SELECT d.ID as doctor_consultation_id, d.doctor_id, d.patient_id, a.ID, a.wife_name, a.wife_phone from ".$this->config->item('db_prefix')."appointments a join ".$this->config->item('db_prefix')."doctor_consultation d where d.appointment_id = a.ID and a.partial_billing=1 and d.medicine_billed=0 and d.investigation_billed = 0 and d.procedure_billed = 0 ".$conditions." order by d.ID desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_billing_noreceipt = $procedure_q->result_array();
		
		return $procedure_billing_noreceipt;
	}
    
	
	function search_appointment($search_this, $search_by){
		$result = array();
		$sql_condition = '';
		if($search_by == 'patient'){
			$sql_condition = " paitent_id='".$search_this."'";
		}else if($search_by == 'phone'){
			$sql_condition = " wife_phone='".$search_this."'";	
		}else{
			return $result;
		}
		
		$sql = "Select * from ".$this->config->item('db_prefix')."appointments where".$sql_condition." and (status='booked' OR status='rescheduled')";
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
	
	function get_consultation_data($appointments_id){
		$sql = "Select * from ".$this->config->item('db_prefix')."consultation where appointment_id='$appointments_id'";
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
	
	function search_patient($search_this, $search_by){
		$result = array();
		$sql_condition = '';
		if($search_by == 'patient'){
			$sql_condition = " patient_id='".$search_this."'";
		}else if($search_by == 'phone'){
		    $search_this = str_replace('+91', '', $search_this);
			$sql_condition = " patient_phone='".$search_this."'";	
		}else{
			return $result;
		}
		
		$sql = "Select * from ".$this->config->item('db_prefix')."patients where".$sql_condition;
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
	
	function get_medicine_details($medicine){
		$sql = "Select * from ".$this->config->item('db_prefix')."stocks where item_number='$medicine'";
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
	
	function get_brand_details($brand){
		$sql = "Select * from ".$this->config->item('db_prefix')."brands where brand_number='$brand'";
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
	
	function get_investigation_details($investigation){
		$sql = "Select * from ".$this->config->item('db_prefix')."investigation where ID='$investigation'";
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
	
/*	function get_master_investigation_details($investigation){
		//$sql = "Select * from ".$this->config->item('db_prefix')."investigation where ID='$investigation'";
	/*echo $sql = "SELECT inv.investigation, inv.code AS code, inv.ID AS inv_id, master.code AS master_code, inv.price, inv.center_id, inv.master_id, master.investigation_name
FROM hms_investigation AS inv JOIN hms_master_investigations AS master ON inv.master_id = master.id WHERE inv.id IN ( SELECT MIN(id) FROM hms_investigation GROUP BY master_id ='$investigation')";
       */
		/*$sql = "SELECT inv.investigation, inv.code AS code, inv.ID AS inv_id,
       master.code AS master_code, inv.price, inv.center_id,
       inv.master_id, master.investigation_name
FROM hms_investigation AS inv
JOIN hms_master_investigations AS master ON inv.master_id = master.id
WHERE inv.master_id = '$investigation'";
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
	} */
	
	function get_master_investigation_details($investigation){
			$sql = "SELECT inv.investigation, inv.code AS code, inv.ID AS inv_id,
				master.code AS master_code, inv.price, inv.center_id,
				inv.master_id, master.investigation_name
			FROM hms_investigation AS inv
			JOIN hms_master_investigations AS master ON inv.master_id = master.id
			WHERE inv.master_id = '$investigation'";
			$q = $this->db->query($sql);
			$result = $q->result_array();
			return $result; 
		}

	
	function get_procedure_details($procedure){
        $sql = "Select * from ".$this->config->item('db_prefix')."procedures where ID='$procedure'";
        //echo $procedure;die;
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

    function export_billing_data($start, $end, $center, $type){
        $consultation_result = $investigate_result = $procedure_result = $response = array();
        $conditions = '';
        if($type == "date_wise"){
            if($center == 0){
                if($start !== "" && $end !== ""){
                    $conditions = " Where on_date between '".$start."' AND '".$end."' ";
                }
                if($center != 0){ $conditions .= ' AND billing_at="'.$center.'"'; }
            }else{
                if($start !== "" && $end !== ""){
                    $conditions = " Where on_date between '".$start."' AND '".$end."' AND ";
                }else{
                    $conditions = " Where ";   
                }
                if($center != 0){ $conditions .= ' billing_at="'.$center.'"'; }
            }
        }else if($type == "billing_at"){
            $conditions .= ' Where billing_at="'.$center.'"';
        }else if($type == "billing_from"){
            $conditions .= ' Where billing_from="'.$center.'"';
        }
       
		$consultation_sql = "Select receipt_number,patient_id,totalpackage,fees as discounted_package,payment_done,remaining_amount,payment_method,billing_from,billing_at,on_date as date,status from ".$this->config->item('db_prefix')."consultation $conditions order by on_date desc";
        //echo $consultation_sql;die;s
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
        if(!empty($consultation_result)){
            foreach($consultation_result as $key => $val){
                $response[] = array(
                        'receipt_number' => $val['receipt_number'],
                        'patient_id' => $val['patient_id'],
                        'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
                        'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Consultation',
                );
            }
        }

		$investigate_sql = "Select receipt_number,patient_id,totalpackage,fees as discounted_package,payment_done,remaining_amount,payment_method,billing_from,billing_at,on_date as date,status from ".$this->config->item('db_prefix')."patient_investigations $conditions order by on_date desc";
        $investigate_q = $this->db->query($investigate_sql);
        $investigate_result = $investigate_q->result_array();
        if(!empty($investigate_result)){
            foreach($investigate_result as $key => $val){
                $response[] = array(
                        'receipt_number' => $val['receipt_number'],
                        'patient_id' => $val['patient_id'],
                        'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
                        'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Investigation',
                );
            }
        }

		$procedure_sql = "Select receipt_number,patient_id,totalpackage,fees as discounted_package,payment_done,remaining_amount,payment_method,billing_from,billing_at,on_date as date,status from ".$this->config->item('db_prefix')."patient_procedure $conditions order by on_date desc";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
        if(!empty($procedure_result)){
            foreach($procedure_result as $key => $val){
                $response[] = array(
                        'receipt_number' => $val['receipt_number'],
                        'patient_id' => $val['patient_id'],
                        'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
                        'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Procedure',
                );
            }
        }
        
        $partial_sql = "Select billing_id,patient_id,payment_done,payment_method,billing_from,billing_at,on_date as date,status from ".$this->config->item('db_prefix')."patient_payments $conditions order by on_date desc";
        $partial_q = $this->db->query($partial_sql);
        $partial_result = $partial_q->result_array();
        if(!empty($partial_result)){
            foreach($partial_result as $key => $val){
                $status = "";
                if($val['status'] == 1){ $status = 'Approved'; }
				else if($val['status'] == 2){ $status =  'Disapproved'; }
				else{$status =  'Pending';}
                $response[] = array(
                        'receipt_number' => $val['billing_id'],
                        'patient_id' => $val['patient_id'],
                        'totalpackage' => '',
                        'discounted_package' => '',
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => '',
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
                        'date' => $val['date'],
                        'status' => $status,
                        'billing_type' => 'Partial Payment',
                );
            }
        }
		return $response;
    }
    
    function upload_billing_receipt($patient_id, $receipt_number, $billing_type, $transaction_id, $transaction_img,$record_id=''){
        $condition = "receipt_number";
        if($billing_type == "patient_payments"){
            $condition = "billing_id";
        }
        $sql = "UPDATE ".$this->config->item('db_prefix').$billing_type." SET `transaction_id`='$transaction_id',`transaction_img`='$transaction_img' WHERE ID='$record_id' AND patient_id='$patient_id' AND $condition='$receipt_number'";
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    function update_package_form($data, $receipt_number){
        $sql = "UPDATE ".$this->config->item('db_prefix')."patient_procedure SET `package_form`='".$data['package_form']."' WHERE receipt_number='$receipt_number'";
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
	
	function update_doctor_consultation($receipt, $consultation_done, $medicine_billed, $investigation_billed, $procedure_billed){
        $sql_condition = '';
        if($medicine_billed == 1){
            $sql_condition = ", medicine_billed='$medicine_billed'";
        }if($investigation_billed == 1){
            $sql_condition .= ", investigation_billed='$investigation_billed'";
        }if($procedure_billed == 1){
            $sql_condition = ", procedure_billed='$procedure_billed'";
        }
        $sql = "UPDATE ".$this->config->item('db_prefix')."doctor_consultation SET `receipt_number`='$receipt' ".$sql_condition." WHERE ID='$consultation_done'";
        $this->db->query($sql);
        return $this->db->affected_rows();
	}
	
	/**********Billing Noreceipt Consultation**********/
	
	function billing_noreceipt_count($receipt_number, $start_date, $end_date, $patient_id){
		$billing_noreceipt = array();
		$conditions = '';
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($receipt_number)){
			$conditions .= " and receipt_number='$receipt_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."consultation where payment_method !='' AND (transaction_id='' OR transaction_id='0') AND transaction_img=''";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}
	
	function billing_noreceipt_patination($limit, $page, $receipt_number, $start_date, $end_date, $patient_id){
		$billing_noreceipt = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($receipt_number)){
			$conditions .= " and receipt_number='$receipt_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."consultation where payment_method !='' AND (transaction_id='' OR transaction_id='0') AND transaction_img='' and 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$billing_noreceipt = $procedure_q->result_array();
		return $billing_noreceipt;
	}
	
		/**********Billing Noreceipt Procedure**********/
	
	function procedure_noreceipt_count($receipt_number, $start_date, $end_date, $patient_id){
		$procedure_billing_noreceipt = array();
		$conditions = '';
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($receipt_number)){
			$conditions .= " and receipt_number='$receipt_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where payment_method !='' AND (transaction_id='' OR transaction_id='0') AND transaction_img=''";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}
	
	function procedure_noreceipt_pagination($limit, $page, $receipt_number, $start_date, $end_date, $patient_id){
		$procedure_billing_noreceipt = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($receipt_number)){
			$conditions .= " and receipt_number='$receipt_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where payment_method !='' AND (transaction_id='' OR transaction_id='0') AND transaction_img='' and 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_billing_noreceipt = $procedure_q->result_array();
		return $procedure_billing_noreceipt;
	}
	
		/**********Billing Noreceipt Investigation**********/
	
	function investigation_noreceipt_count($receipt_number, $start_date, $end_date, $patient_id){
		$investigation_billing_noreceipt = array();
		$conditions = '';
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($receipt_number)){
			$conditions .= " and receipt_number='$receipt_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where payment_method !='' AND (transaction_id='' OR transaction_id='0') AND transaction_img=''";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}
	
	function investigation_noreceipt_pagination($limit, $page, $receipt_number, $start_date, $end_date, $patient_id){
		$investigation_billing_noreceipt = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($receipt_number)){
			$conditions .= " and receipt_number='$receipt_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where payment_method !='' AND (transaction_id='' OR transaction_id='0') AND transaction_img='' and 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_billing_noreceipt = $investigation_q->result_array();
		return $investigation_billing_noreceipt;
	}
	
		/**********Billing Noreceipt Investigation**********/
	
	function patient_payments_noreceipt_count($billing_id, $start_date, $end_date, $patient_id){
		$patient_payments_billing_noreceipt = array();
		$conditions = '';
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($billing_id)){
			$conditions .= " and billing_id='$billing_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$patient_payments_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where payment_method !='' AND (transaction_id='' OR transaction_id='0') AND transaction_img=''";
		$q = $this->db->query($patient_payments_sql);
		return $q->num_rows();
		
	}
	
	function patient_payments_noreceipt_pagination($limit, $page, $billing_id, $start_date, $end_date, $patient_id){
		$patient_payments_billing_noreceipt = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($billing_id)){
			$conditions .= " and billing_id='$billing_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$patient_payments_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where payment_method !='' AND (transaction_id='' OR transaction_id='0') AND transaction_img='' and 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$patient_payments_q = $this->db->query($patient_payments_sql);
		$patient_payments_billing_noreceipt = $patient_payments_q->result_array();
		return $patient_payments_billing_noreceipt;
	}

	function update_freezing_data($data, $patient_id, $expiry_date, $renewal_type) {
		// Safely escape patient_id, expiry_date, and renewal_type
		$patient_id = $this->db->escape($patient_id);
		$expiry_date = $this->db->escape($expiry_date);
		$renewal_type = $this->db->escape($renewal_type);
	
		// Define the frozen samples that should be updated
		$frozen_samples = ['Semen Freezing', 'Embryo Freezing', 'Egg Freezing'];
	
		// Escape and format the frozen samples for the SQL query
		$frozen_samples = array_map([$this->db, 'escape'], $frozen_samples);
		$frozen_samples_list = implode(',', $frozen_samples); // Join the array into a comma-separated list
	
		// Build and execute the SQL query
		echo $sql = "UPDATE freezing SET `expiry_date` = $expiry_date, `renewal_type` = $renewal_type
				WHERE iic_id = $patient_id 
				AND frozen_sample IN ($frozen_samples_list)";
		
		$this->db->query($sql);
		return $this->db->affected_rows();
	}	
	function patient_journey_data($data){
		if (!isset($data['husband_name']) || empty($data['husband_name'])) {
			if (isset($data['patient_id']) && !empty($data['patient_id'])) {
				$patient_sql = "SELECT husband_name 
								FROM ".$this->config->item('db_prefix')."patients 
								WHERE patient_id='".$data['patient_id']."'";
				$patient_result = run_select_query($patient_sql);
				$data['husband_name'] = isset($patient_result['husband_name']) ? $patient_result['husband_name'] : '';
			} else {
				$data['husband_name'] = '';
			}
		}
		if (empty($data['husband_name'])) {
			$data['husband_name'] = '';
		}
		$receipt_numbers = array();
		foreach ($data as $key => $value) {
			if (preg_match('/^receipt_number_\d+$/', $key) && !empty($value)) {
				$receipt_numbers[] = $value;
			}
		}
		$receipt_numbers = array_unique($receipt_numbers);
		if (!empty($receipt_numbers)) {
			$data['receipt_number'] = json_encode(array_values($receipt_numbers)); 
		} else {
			$data['receipt_number'] = json_encode([]); // empty JSON array
		}
		// Optional: remove the extra receipt_number_1, _2... fields 
		foreach ($data as $key => $value) {
			if (preg_match('/^receipt_number_\d+$/', $key)) {
				unset($data[$key]);
			}
		}
		// Build SQL
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "patient_journey` SET ";
		$sqlArr = array();
		foreach ($data as $key => $value) {
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}
		$sql .= implode(',', $sqlArr);

		$res = $this->db->query($sql);
		if ($res) {
			return $this->db->insert_id();
		} else {
			log_message('error', 'patient_journey_data SQL Error: ' . $this->db->last_query());
			log_message('error', 'patient_journey_data DB Error: ' . $this->db->error()['message']);
			return 0;
		}
	}

	
// वॉलेट से अमाउंट काटने और लॉग्स मेंटेन करने के लिए
public function deduct_wallet_balance($patient_id, $amount, $sale_id, $logged_in_user) {
    // 1. लॉग्स के लिए करंट बैलेंस निकालें
    $this->db->where('patient_id', $patient_id);
    $wallet = $this->db->get('hms_patient_wallets')->row();
    
    if (!$wallet) {
        return false;
    }

    // Opening और Closing बैलेंस कैलकुलेट करें
    $opening_w1 = $wallet->wallet_1_balance;
    $closing_w1 = $opening_w1 - $amount;
    
    $opening_w2 = $wallet->wallet_2_balance; // इसे हम टच नहीं कर रहे, इसलिए same रहेगा
    $closing_w2 = $opening_w2;

    // 2. hms_patient_wallets से अमाउंट काटें (Wallet 1 से)
    $this->db->set('wallet_1_balance', 'wallet_1_balance - ' . (float)$amount, FALSE);
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->where('patient_id', $patient_id);
    $this->db->update('hms_patient_wallets');

    // 3. hms_wallet_logs में ट्रांजैक्शन की एंट्री करें
    $log_data = [
        'patient_id'     => $patient_id,
        'amount'         => $amount,
        'action_type'    => 'Consultation_Billing', // पैसे कट रहे हैं इसलिए debit
        'opening_w1'     => $opening_w1,
        'closing_w1'     => $closing_w1,
        'opening_w2'     => $opening_w2,
        'closing_w2'     => $closing_w2,
        'reference_id'   => $sale_id, // Sale ID ताकि पता रहे किस बिल के लिए पैसे कटे
        'payment_method' => 'wallet',
        'remarks'        => 'Amount deducted for Sale ID: ' . $sale_id,
        'created_by'     => $logged_in_user, // जिसने लॉगिन किया है उसकी ID
        'created_at'     => date('Y-m-d H:i:s'),
        'status'         => 'pending' // आपके DB के अनुसार इसे 1 या 'Success' रखें
    ];     
                    
                   
    
    return $this->db->insert('hms_wallet_logs', $log_data);
}
	
}