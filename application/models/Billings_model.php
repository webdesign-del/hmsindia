<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

date_default_timezone_set('Asia/Calcutta');



class Billings_model extends CI_Model

{

/*	function get_billings_list(){

		$consultation_result = $investigate_result = $procedure_result = array();
		$condition = "";
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
		}

		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation $condition order by on_date desc";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();

		$investigate_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations $condition order by on_date desc";
        $investigate_q = $this->db->query($investigate_sql);
        $investigate_result = $investigate_q->result_array();

		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure $condition order by on_date desc";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();

		$response = array();
		$response = array('consultation_result' => $consultation_result, 'investigate_result' => $investigate_result, 'procedure_result' => $procedure_result);
		return $response;
	}
*/
	
/*************Consultation Billings***************/
	function export_consultation_billings($center, $start_date, $end_date, $patient_id){

		$consultation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
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
        if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		
	    $consultation_sql = "Select DISTINCT receipt_number,patient_id, totalpackage,discount_amount, payment_done,remaining_amount, payment_method, billing_at, on_date as date, status from ".$this->config->item('db_prefix')."consultation where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1 $conditions order by on_date desc";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
        if(!empty($consultation_result)){
            foreach($consultation_result as $key => $val){
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
                $response[] = array(
				        'receipt_number' => $val['receipt_number'],
                        'patient_id' => $val['patient_id'],
						'totalpackage' => $val['totalpackage'],
						'discount_amount' => $val['discount_amount'],
                        'payment_done' => $val['payment_done'],
						'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_at' => $val['billing_at'],
						'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Consultation',
                );
            }
        }    
		return $response;
    }

	function consultation_billings_count($center, $start_date, $end_date, $patient_id){
		$consultation_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
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
        if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
	    $consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1 ".$conditions."";
		$q = $this->db->query($consultation_sql);
		return $q->num_rows();
		
	}
	
function consultation_billings_patination($limit, $page, $center, $start_date, $end_date, $patient_id){
		$consultation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
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
	    $consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$consultation_q = $this->db->query($consultation_sql);
		$consultation_result = $consultation_q->result_array();
		return $consultation_result;
}

/*************Consultation Billings***************/
	function export_registation_billings($center, $start_date, $end_date, $patient_id){

		$consultation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
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
        if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		
	    $consultation_sql = "Select DISTINCT receipt_number,patient_id, totalpackage,discount_amount, payment_done,remaining_amount, payment_method, billing_at, on_date as date, status from ".$this->config->item('db_prefix')."registation where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1 $conditions order by on_date desc";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
        if(!empty($consultation_result)){
            foreach($consultation_result as $key => $val){
				$patient_name = $this->get_patient_name($val['patient_id']);
				//$patient_name1 = strtoupper($patient_name);
                $response[] = array(
				        'receipt_number' => $val['receipt_number'],
                        'patient_id' => $val['patient_id'],
						'wife_name' => $patient_name,
						'totalpackage' => $val['totalpackage'],
						'discount_amount' => $val['discount_amount'],
                        'payment_done' => $val['payment_done'],
						'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_at' => $val['billing_at'],
						'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Consultation',
                );
            }
        }    
		return $response;
    }

	function registation_billings_count($center, $start_date, $end_date, $patient_id){
		$consultation_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
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
        if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
	    $consultation_sql = "Select * from ".$this->config->item('db_prefix')."registation where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1 ".$conditions."";
		$q = $this->db->query($consultation_sql);
		return $q->num_rows();
		
	}
	
function registation_billings_patination($limit, $page, $center, $start_date, $end_date, $patient_id){
		$consultation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
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
	    $consultation_sql = "Select * from ".$this->config->item('db_prefix')."registation where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$consultation_q = $this->db->query($consultation_sql);
		$consultation_result = $consultation_q->result_array();
		return $consultation_result;
}
/*************Investigations Billings***************/

	function export_investigations_billings($center, $start_date, $end_date, $patient_id){

		$investigation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
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
        if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		
	    $investigation_sql = "Select DISTINCT receipt_number,patient_id, totalpackage,discount_amount, payment_done,remaining_amount, payment_method, billing_at, on_date as date, status from ".$this->config->item('db_prefix')."patient_investigations where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1 $conditions order by on_date desc";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
                $response[] = array(
				        'receipt_number' => $val['receipt_number'],
                        'patient_id' => $val['patient_id'],
						'totalpackage' => $val['totalpackage'],
						'discount_amount' => $val['discount_amount'],
                        'payment_done' => $val['payment_done'],
						'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_at' => $val['billing_at'],
						'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Investigation',
                );
            }
        }    
		return $response;
    }

	function investigation_billings_count($center, $start_date, $end_date, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
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
        if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}
	
function investigation_billings_patination($limit, $page, $center, $start_date, $end_date, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
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
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
}

/*************Procedure Billings***************/

	function export_procedure_billings($center, $start_date, $end_date, $patient_id){

		$procedure_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
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
        if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		
	    $procedure_sql = "Select DISTINCT receipt_number,patient_id, totalpackage,discount_amount, payment_done,remaining_amount, payment_method, billing_at, on_date as date, status from ".$this->config->item('db_prefix')."patient_procedure where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1 $conditions order by on_date desc";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
        if(!empty($procedure_result)){
            foreach($procedure_result as $key => $val){
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
                $response[] = array(
				        'receipt_number' => $val['receipt_number'],
                        'patient_id' => $val['patient_id'],
						'totalpackage' => $val['totalpackage'],
						'discount_amount' => $val['discount_amount'],
                        'payment_done' => $val['payment_done'],
						'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_at' => $val['billing_at'],
						'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Procedure',
                );
            }
        }    
		return $response;
    }

	function procedure_billings_count($center, $start_date, $end_date, $patient_id){
		$procedure_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
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
        if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
	    $procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}
	
function procedure_billings_patination($limit, $page, $center, $start_date, $end_date, $patient_id){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_billing_manager']['center'])){
			$center = $_SESSION['logged_billing_manager']['center'];
			$condition = " where billing_at='$center' ";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
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
	    $procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where billing_at='".$_SESSION['logged_billing_manager']['center']."' and 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
}

	function get_billings_request_list(){

		$consultation_result = $investigate_result = $procedure_result = array();

		

		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."billing_request where type='consultation'";

        $consultation_q = $this->db->query($consultation_sql);

        $consultation_result = $consultation_q->result_array();

		

		$investigate_sql = "Select * from ".$this->config->item('db_prefix')."billing_request where type='investigation'";

        $investigate_q = $this->db->query($investigate_sql);

        $investigate_result = $investigate_q->result_array();

		

		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."billing_request where type='procedure'";

        $procedure_q = $this->db->query($procedure_sql);

        $procedure_result = $procedure_q->result_array();

		

		$response = array();

		$response = array('consultation_result' => $consultation_result, 'investigate_result' => $investigate_result, 'procedure_result' => $procedure_result);

		return $response;

	}

	

	function patient_payments($data){

		$response = array();

		if($data['search_by'] == 'patient'){
			$sql_condition = " patient_id='".$data['search_this']."'";
		}else if($data['search_by'] == 'phone'){
			$sql_condition = " patient_phone='".$data['search_this']."'";	
		}else{
			return $response;
		}	

		$patient_sql = "Select * from ".$this->config->item('db_prefix')."patients where $sql_condition";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		$patient_id = $patient_result[0]['patient_id'];	

		$consultation_result = $procedure_result = $investigation_result = $remaining_billing = array();	

		$procedure_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_from, billing_at, data from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_id."' and status!='disapproved'";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();

		$consultation_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_from, billing_at from ".$this->config->item('db_prefix')."consultation where patient_id='".$patient_id."' and status!='disapproved'";
        $consultation_q = $this->db->query($consultation_sql);
		$consultation_result = $consultation_q->result_array();
		
		$investigation_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_from, billing_at from ".$this->config->item('db_prefix')."patient_investigations where patient_id='".$patient_id."' and status!='disapproved'";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();

		$total = 0;
		foreach($consultation_result as $key => $val){
			$done_sql = "Select sum(payment_done) as payment_done from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_id."' AND billing_id='".$val['receipt_number']."' AND status!='2'";
			$done_q = $this->db->query($done_sql);
			$done_result = $done_q->result_array();
			//print_r($done_result);die;
			if(count($done_result[0]) > 0){
				$total = (round($val['payment_done'], 2)+round($done_result[0]['payment_done'], 2)); 
				if($total < $val['fees']){
					$remaining_billing[] = array(
						'billing_id' => $val['receipt_number'],
						'billing_at' => $val['billing_at'],
						'billing_from' => $val['billing_from'],
						'discounted_package' => $val['fees'],
						'amount_paid' => $total,
						'balance' => ($val['fees'] - $total),
						'type' => 'consultation'											
					);
				}
			}
			
		}

		$total = 0;
		foreach($investigation_result as $key => $val){
			$done_sql = "Select sum(payment_done) as payment_done from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_id."' AND billing_id='".$val['receipt_number']."' AND status=1";
			$done_q = $this->db->query($done_sql);
			$done_result = $done_q->result_array();
			$total = (round($val['payment_done'], 2)+round($done_result[0]['payment_done'], 2)); 
			if($total < $val['fees']){
				$remaining_billing[] = array(
					'billing_id' => $val['receipt_number'],
					'billing_at' => $val['billing_at'],
					'billing_from' => $val['billing_from'],
					'discounted_package' => $val['fees'],
					'amount_paid' => $total,
					'balance' => ($val['fees'] - $total),
					'type' => 'investigation'
				);
			}
		}	

		$total = 0;
		foreach($procedure_result as $key => $val){
			$done_sql = "Select sum(payment_done) as payment_done from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_id."' AND billing_id='".$val['receipt_number']."' AND status=1";
			$done_q = $this->db->query($done_sql);
			$done_result = $done_q->result_array();
			$total = (round($val['payment_done'], 2)+round($done_result[0]['payment_done'], 2)); 
			if($total < $val['fees']){
				$remaining_billing[] = array(
					'billing_id' => $val['receipt_number'],
					'billing_at' => $val['billing_at'],
					'billing_from' => $val['billing_from'],
					'discounted_package' => $val['fees'],
					'amount_paid' => $total,
					'balance' => ($val['fees'] - $total),
					'type' => 'procedure',
					'data' => $val['data']
				);
			}
		}
		
		if(!empty($patient_result)){
			$response = array('remaining_billing' => $remaining_billing, 'patient_id' =>$patient_id, 'wife_name' =>$patient_result[0]['wife_name'], 'patient_phone' => sting_masking($patient_result[0]['patient_phone']), 'wife_email' =>sting_masking($patient_result[0]['wife_email']), 'nationality' => $patient_result[0]['nationality'], 'status' => 1);
		}else{
			$response = array('status' => 0);
		}
		return $response;
	}

	function insert_patient_payment($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "patient_payments` SET ";
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

	

	function check_patient($search_this, $search_by){

		$result = array();

		$sql_condition = '';

		if($search_by == 'patient'){

			$sql_condition = " patient_id='".$search_this."'";

		}else if($search_by == 'phone'){

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

	

	function paitent_insert($data){

		

		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "patients` SET ";

		$sqlArr = array();

		foreach( $data as $key=> $value )

		{

			$sqlArr[] = " $key = '".addslashes($value)."'";

		}

		$date = date("Y-m-d H:i:s");

		$sqlArr[] = " add_date = '".addslashes($date)."'";

		$sql .= implode(',' , $sqlArr);

       	$res =  $this->db->query($sql);

		if ($res)

		{

			return $this->db->insert_id();

		}

		else

			return 0;

	}

	

	function get_code($type){

		$option = '';

		$sql = "Select * from ".$this->config->item('db_prefix')."ids where type='".$type."'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

			foreach($result as $key => $val){

				$option .= '<option value="'.$val['code'].'">'.$val['code'].'</option>';

			}

            return $option;

        }

        else

        {

            return $option;

        }

	}

	

	function get_billings_details($receipt, $type){

		$result = array();

		$condition = '';

		if($type == 'consultation'){

			$condition = 'consultation';

		}else if($type == 'investigation'){

			$condition = 'patient_investigations';

		}else if($type == 'procedure'){

			$condition = 'patient_procedure';

		}else{

			return $result;

		}

		$sql = "SELECT * FROM `".$this->config->item('db_prefix').$condition."` WHERE `receipt_number`='".$receipt."'";
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

	

	function consultation_insert($data){

		

		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "consultation` SET ";

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
	
	function registation_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "registation` SET ";
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

	

	function get_patient_name($patient_id){	

		$result = array();

		$sql = "Select wife_name from ".$this->config->item('db_prefix')."patients where patient_id='".$patient_id."'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))
        {
            return $result[0]['wife_name'];
        }
        else
        {
            return "";
        }
	}

	

	function get_patient_details($patient_id){

		

		$result = array();

		$sql = "Select * from ".$this->config->item('db_prefix')."patients where patient_id='".$patient_id."'";

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

		

	function get_sub_procedures($parent_procedure){

		

		$result = array();

		$sql = "Select * from ".$this->config->item('db_prefix')."procedures where parent_id='".$parent_procedure."'";

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

	

	function update_stock($serial, $qty){

		$sql = "UPDATE ".config_item('db_prefix')."stocks SET `quantity` = `quantity` - ".$qty." WHERE item_number='".$serial."'";

        $this->db->query($sql);

        return 1;

	}

	

	function update_disapproved_billing($receipt, $type){

		$result = array();

		$condition = '';

		if($type == 'consultation'){

			$condition = 'consultation';

		}else if($type == 'investigation'){

			$condition = 'patient_investigations';

		}else if($type == 'procedure'){

			$condition = 'patient_procedure';

		}else{

			return $result;

		}

		$sql = "UPDATE ".config_item('db_prefix').$condition." SET status='pending', reason_of_disapprove='', accepted='0' WHERE receipt_number='".$receipt."'";

        $this->db->query($sql);

        return 1;

	}

	

	

	function iic_share_update($receipt, $type, $share){

		$result = array();

		$sql = "UPDATE ".config_item('db_prefix')."patient_procedure SET `center_share` = '".$share."', share='1' WHERE receipt_number='".$receipt."'";

        $this->db->query($sql);

        return 1;

	}

	

	

	

	function get_procedure_name($procedure){	

		$result = array();

		$sql = "Select procedure_name from ".$this->config->item('db_prefix')."procedures where ID='".$procedure."'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0]['procedure_name'];

        }

        else

        {

            return $result;

        }

	}
	
	function get_package_name($procedure){	

		$result = array();

		echo $sql = "Select package_name from ".$this->config->item('db_prefix')."procedure_package where procedure_id='".$procedure."'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0]['package_name'];

        }

        else

        {

            return $result;

        }

	}

	

	function ajax_billing_from_data($billing_from){
		$consultation_result = $investigate_result = $procedure_result = array();
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where billing_at='".$billing_from."'";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();

    	$investigate_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where billing_at='".$billing_from."'";
        $investigate_q = $this->db->query($investigate_sql);
        $investigate_result = $investigate_q->result_array();

		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where billing_at='".$billing_from."'";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
        
        $patient_payment_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where billing_at='".$billing_from."'";
        $patient_payment_q = $this->db->query($patient_payment_sql);
        $patient_payment_result = $patient_payment_q->result_array();

		$response = array();
		$response = array('consultation_result' => $consultation_result, 'investigate_result' => $investigate_result, 'procedure_result' => $procedure_result, 'patient_payment_result' => $patient_payment_result);
		return $response;
	}
	
	function ajax_billing_source_data($billing_from){
		$consultation_result = $investigate_result = $procedure_result = array();
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where billing_from='".$billing_from."'";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();

    	$investigate_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where billing_from='".$billing_from."'";
        $investigate_q = $this->db->query($investigate_sql);
        $investigate_result = $investigate_q->result_array();

		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where billing_from='".$billing_from."'";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
        
        $patient_payment_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where billing_from='".$billing_from."'";
        $patient_payment_q = $this->db->query($patient_payment_sql);
        $patient_payment_result = $patient_payment_q->result_array();

		$response = array();
		$response = array('consultation_result' => $consultation_result, 'investigate_result' => $investigate_result, 'procedure_result' => $procedure_result, 'patient_payment_result' => $patient_payment_result);
		return $response;
	}

	

	function ajax_billing_date_data($start, $end){

		$consultation_result = $investigate_result = $procedure_result = array();

		

		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where on_date between '".$start."' AND '".$end."'";

        $consultation_q = $this->db->query($consultation_sql);

        $consultation_result = $consultation_q->result_array();

		if(!empty($consultation_result)){

			$consultation_result = $consultation_result;

		}

		

		$investigate_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where on_date between '".$start."' AND '".$end."'";

        $investigate_q = $this->db->query($investigate_sql);

        $investigate_result = $investigate_q->result_array();

		if(!empty($investigate_result)){

			$investigate_result = $investigate_result;

		}

		

		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where on_date between '".$start."' AND '".$end."'";

        $procedure_q = $this->db->query($procedure_sql);

        $procedure_result = $procedure_q->result_array();

		if(!empty($procedure_result)){

			$procedure_result = $procedure_result;

		}

        $patient_payment_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where on_date between '".$start."' AND '".$end."'";
        $patient_payment_q = $this->db->query($patient_payment_sql);
        $patient_payment_result = $patient_payment_q->result_array();
		if(!empty($patient_payment_result)){
			$patient_payments_result = $patient_payment_result;
		}
		
		$response = array();
		$response = array('consultation_result' => $consultation_result, 'investigate_result' => $investigate_result, 'procedure_result' => $procedure_result,'patient_payments_result' => $patient_payments_result);
		return $response;

	}

	

	function ajax_center_billing_date_data($start, $end, $center){
		$condition = " AND billing_at='".$center."'";
		if($center == 0){
			$condition = "";
		}
		$consultation_result = $investigate_result = $procedure_result = array();
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where on_date between '".$start."' AND '".$end."' $condition";
		//echo $consultation_sql;die;
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
		if(!empty($consultation_result)){
			$consultation_result = $consultation_result;
		}

		$investigate_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where on_date between '".$start."' AND '".$end."' $condition";
        $investigate_q = $this->db->query($investigate_sql);
        $investigate_result = $investigate_q->result_array();
		if(!empty($investigate_result)){
			$investigate_result = $investigate_result;
		}

		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where on_date between '".$start."' AND '".$end."' $condition";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
		if(!empty($procedure_result)){
			$procedure_result = $procedure_result;
		}
		$response = array();
		$response = array('consultation_result' => $consultation_result, 'investigate_result' => $investigate_result, 'procedure_result' => $procedure_result);
		return $response;
	}

	function get_center($username){

		$result = array();

		$sql = "SELECT a.center_name, a.center_number FROM ".config_item('db_prefix')."centers as a, ".config_item('db_prefix')."employees as b WHERE a.center_number = b.center_id and b.username='".$username."'";

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

	

	function get_patient_data($patient){

		$result = array();

		$sql = "SELECT * from ".config_item('db_prefix')."patients where patient_id='".$patient."'";

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

	

	function get_center_name($center){

		$result = array();

		$sql = "Select center_name from ".$this->config->item('db_prefix')."centers where center_number='".$center."'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0]['center_name'];

        }

        else

        {

            return $result;

        }

	}

	

	function get_investigation_name($investig){		

		$result = array();

		$sql = "Select investigation from ".$this->config->item('db_prefix')."investigation where ID='".$investig."'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0]['investigation'];

        }

        else

        {

            return $result;

        }

	}

	

	function get_medicine_name($medicine){		

		$result = array();

		$sql = "Select item_name, company, brand_name from ".$this->config->item('db_prefix')."stocks where item_number='".$medicine."'";

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



	function get_brand_name($brand){		

		$result = array();

		$sql = "Select name from ".$this->config->item('db_prefix')."brands where brand_number='".$brand."'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0]['name'];

        }

        else

        {

            return $result;

        }

	}

	

	function check_discound_applied($receipt){

		$sql = "Select count(*) as discount from ".$this->config->item('db_prefix')."discount_approval where receipt_number='".$receipt."' AND status='1' AND used='0'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0]['discount'];

        }

        else

        {

            return $result;

        }

	}

	

	public function update_patients($data, $patient_id)

    {	

        $sql = "UPDATE " . config_item('db_prefix') . "patients SET ";

		foreach( $data as $key=> $value )

		{

			$sqlArr[] = " $key = '".$value."'"	;

		}

		$sql .= implode(',' , $sqlArr);

		$sql .= " WHERE patient_id = '".$patient_id."'";

        $this->db->query($sql);

        return 1;

    }

	

	public function update_consultation($data, $receipt_number)

    {	

        $sql = "UPDATE " . config_item('db_prefix') . "consultation SET ";

		foreach( $data as $key=> $value )

		{

			$sqlArr[] = " $key = '".$value."'"	;

		}

		$sql .= implode(',' , $sqlArr);

		$sql .= " WHERE receipt_number = '".$receipt_number."'";

        $this->db->query($sql);

        return 1;

    }

	

	public function update_investigation($data, $receipt_number)

    {	

        $sql = "UPDATE " . config_item('db_prefix') . "patient_investigations SET ";

		foreach( $data as $key=> $value )

		{

			$sqlArr[] = " $key = '".$value."'"	;

		}

		$sql .= implode(',' , $sqlArr);

		$sql .= " WHERE receipt_number = '".$receipt_number."'";

        $this->db->query($sql);

        return 1;

    }

	

	public function update_procedure($data, $receipt_number)

    {	

        $sql = "UPDATE " . config_item('db_prefix') . "patient_procedure SET ";

		foreach( $data as $key=> $value )

		{

			$sqlArr[] = " $key = '".$value."'"	;

		}

		$sql .= implode(',' , $sqlArr);

		$sql .= " WHERE receipt_number = '".$receipt_number."'";

        $this->db->query($sql);

        return 1;

    }

	

	function discount_lists($biller){

		$result = array();

		$sql = "Select * from ".$this->config->item('db_prefix')."discount_approval where biller='".$biller."'";

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

	

	function check_discount_code($discount_code,$patient_id,$receipt_number,$type)

	{

		$result = array();

		$sql = "Select amount from ".$this->config->item('db_prefix')."discount_approval where receipt_number='".$receipt_number."' AND patient_id='".$patient_id."' AND type='".$type."' AND code='".$discount_code."' AND used='0' AND status='1'";

        $q = $this->db->query($sql);

        $result = $q->result_array();

        if (!empty($result))

        {

            return $result[0]['amount'];

        }

        else

        {

            return $result;

        }

	}

	

	function update_discount_avail($discount_code){

		$sql = "UPDATE ".config_item('db_prefix')."discount_approval SET `used` = '1' where code='".$discount_code."'";

        $this->db->query($sql);

        return 1;

	}

	function approve_purchase_order($ID){
		// Get the current date and time
		$current_date = date("Y-m-d H:i:s");
	
		// Correctly concatenate the current date and ensure it is wrapped in single quotes
		$sql = "UPDATE `" . $this->config->item('db_prefix') . "patient_procedure` 
				SET `request_date` = '" . $current_date . "', `status` = 'request' 
				WHERE `ID` = '" . $ID . "'";
		// Echo the SQL query for debugging
		echo $sql;
		// Execute the query
		$this->db->query($sql);
	
		return 1;
	}
	
	function get_transfer_data($ID){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where ID='".$ID."'";
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

	/*function add_product($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "package` SET ";
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
	}*/
	
	public function update_product($data, $ID)
{
    $sql = "UPDATE " . config_item('db_prefix') . "doctor_consultation SET ";

    $sqlArr = array();
    foreach ($data as $key => $value) {
        // Skip fields that might not exist in the database or are empty
        if ($key === 'booking_amount_40' || $key === 'booking_amount_50') {
            // Skip these fields if they're empty to avoid NOT NULL constraint errors
            if (empty($value)) {
                continue;
            }
        }
        
        // Use CI's built-in escape function
        $escaped_value = $this->db->escape($value);
        $sqlArr[] = "`$key` = $escaped_value";
    }

    if (empty($sqlArr)) {
        // No valid fields to update
        return 0;
    }

    $sql .= implode(', ', $sqlArr);
    $sql .= " WHERE ID = " . $this->db->escape($ID);

    $this->db->query($sql);
	
	return $this->db->affected_rows();
}

	


	function get_all_center_stocks($center_number, $start_date, $end_date, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if (!empty($center_number)){
			$conditions .= " and center_number='$center_number'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and add_on='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on='$end_date'";
		}
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}

	function get_all_center_stocks_patination($limit, $page, $center_number, $start_date, $end_date, $patient_id){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($center_number)){
			$conditions .= " and center_number='$center_number'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and add_on='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where 1".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	} 

	function export_all_center_stocks($center_number, $start, $end, $patient_id){
		$investigation_result = $response = array();
        $conditions = '';
		if(!empty($center_number)){
			$conditions .= ' and center_number="'.$center_number.'"';
        }
        if(!empty($start) && !empty($end)){
            $conditions .= " and add_on between '".$start."' AND '".$end."' ";
        }
		if(!empty($patient_id)){
			$conditions .= ' and patient_id="'.$patient_id.'"';
        }
		$investigation_sql = "Select DISTINCT patient_id, wife_name,wife_age, procedure_name_1,code_1,price_1,discount_1,after_discount_1,procedure_name_2,code_2,price_2,discount_2,after_discount_2,procedure_name_3,code_3,price_3,discount_3,after_discount_3,procedure_name_4,code_4,price_4,discount_4,after_discount_4, add_on from ".$this->config->item('db_prefix')."doctor_consultation where 1 $conditions order by ID desc ";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
		if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				
				$response[] = array(
                        'patient_id' => $val['patient_id'],
				        'wife_name' => $val['wife_name'],
                        'wife_age' => $val['wife_age'],
                        'procedure_name_1' => $val['procedure_name_1'],
                        'code_1' => $val['code_1'],
                        'price_1' => $val['price_1'],
                        'discount_1' => $val['discount_1'],
						'after_discount_1' => $val['after_discount_1'],
						'procedure_name_2' => $val['procedure_name_2'],
						'code_2' => $val['code_2'],
                        'price_2' => $val['price_2'],
						'discount_2' => $val['discount_2'],
						'after_discount_2' => $val['after_discount_2'],
                        'procedure_name_3' => $val['procedure_name_3'],
                        'code_3' => $val['code_3'],
                        'price_3' => $val['price_3'],
                        'discount_3' => $val['discount_3'],
						'after_discount_3' => $val['after_discount_3'],
						'procedure_name_4' => $val['procedure_name_4'],
                        'code_4' => $val['code_4'],
                        'price_4' => $val['price_4'],
                        'discount_4' => $val['discount_4'],
						'after_discount_4' => $val['after_discount_4'],
						'add_on' => $val['add_on'],
                );
            }
        } 
 		return $response;
    }
	
	function get_doctor_referral(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."doctor_referral where status='1' ORDER by ID DESC";
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

	function get_counsellor(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."employees where role='counselor' and status='1' ORDER by ID DESC";
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

	function receipt_number_exists($receipt_number) {
		$query = $this->db->query("SELECT COUNT(*) AS count FROM hms_patient_procedure WHERE receipt_number = ?", [$receipt_number]);
		$row = $query->row();
		return ($row->count > 0); // true if exists
	}

}