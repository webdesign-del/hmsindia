<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class Accounts_model extends CI_Model
{

	function get_patient_data_by($search_this, $search_by){

		$response = array();
		if($search_by == 'patient'){
			$sql_condition = " patient_id='".$search_this."'";
		}else if($search_by == 'phone'){
			$sql_condition = " patient_phone='".$search_this."'";	
		}else{
			return $response;
		}	
		$consultation_result = $investigate_result = $procedure_result = $procedure_can_result = $medicine_result = $refund_amount_result = $consultation =  $investigate =  $procedure = $medicine = $refund_amount = $patient_result = $payment_result = $payments = array();
		
		$patient_sql = "Select * from ".$this->config->item('db_prefix')."patients where $sql_condition";
        $patient_q = $this->db->query($patient_sql);
		$patient_result = $patient_q->result_array();
		if(!empty($patient_result)){
			$patient_id = $patient_result[0]['patient_id'];		
			$consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where patient_id='".$patient_id."'";
			$consultation_q = $this->db->query($consultation_sql);
			$consultation_result = $consultation_q->result_array();
			if (!empty($consultation_result))
			{
				$consultation['data'] =  $consultation_result;
				$consultation['type'] = 'consultation';
			}		
			
			$investigate_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where patient_id='".$patient_id."'";
			$investigate_q = $this->db->query($investigate_sql);
			$investigate_result = $investigate_q->result_array();
			if (!empty($investigate_result))
			{
				$investigate['data'] =  $investigate_result;
				$investigate['type'] = 'investigate';
			}	
			
			$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_id."'";
			$procedure_q = $this->db->query($procedure_sql);
			$procedure_result = $procedure_q->result_array();
			if (!empty($procedure_result))
			{
				$procedure['data'] =  $procedure_result;
				$procedure['type'] = 'procedure';
			}

			$procedure_can_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_id."' and status='cancel'";
			$procedure_can_q = $this->db->query($procedure_can_sql);
			$procedure_can_result = $procedure_can_q->result_array();
			if (!empty($procedure_can_result))
			{
				$procedure_can['data'] =  $procedure_can_result;
				$procedure_can['type'] = 'procedure';
			}

            $medicine_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where patient_id='".$patient_id."'";
			$medicine_q = $this->db->query($medicine_sql);
			$medicine_result = $medicine_q->result_array();
			if (!empty($medicine_result))
			{
				$medicine['data'] =  $medicine_result;
				$medicine['type'] = 'medicine';
			}	
			
			$refund_amount_sql = "Select * from ".$this->config->item('db_prefix')."refund_amount where patient_id='".$patient_id."'";
			$refund_amount_q = $this->db->query($refund_amount_sql);
			$refund_amount_result = $refund_amount_q->result_array();
			if (!empty($refund_amount_result))
			{
				$refund_amount['data'] =  $refund_amount_result;
				$refund_amount['type'] = 'refund_amount';
			}	
			
			$payment_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_id."'";
			$payment_q = $this->db->query($payment_sql);
			$payment_result = $payment_q->result_array();
			if (!empty($payment_result))
			{
				$payments['data'] =  $payment_result;
				$payments['type'] = 'payments';
			}	
			
			if (!empty($patient_result))
			{
				$patient_result = $patient_result[0];
			}
			$response = array();
			$response = array('consultation_result' => $consultation, 'investigate_result' => $investigate, 'procedure_result' => $procedure, 'procedure_can_result' => $procedure_can, 'medicine_result' => $medicine, 'refund_amount_result' => $refund_amount, 'patient_result'=> $patient_result, 'payments'=> $payments);
			return $response;
		}else{
			$response = array();
			return $response;
		}
	}

	function get_patient_data($patient_id){
		$consultation_result = $investigate_result = $procedure_result = $consultation =  $investigate =  $procedure = $patient_result = $payment_result = $payments = array();
		
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where patient_id='".$patient_id."' limit 5";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
		if (!empty($consultation_result))
        {
            $consultation['data'] =  $consultation_result;
			$consultation['type'] = 'consultation';
        }		
		
		$investigate_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where patient_id='".$patient_id."'";
        $investigate_q = $this->db->query($investigate_sql);
        $investigate_result = $investigate_q->result_array();
		if (!empty($investigate_result))
        {
			$investigate['data'] =  $investigate_result;
			$investigate['type'] = 'investigate';
        }	
		
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_id."'";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
		if (!empty($procedure_result))
        {
			$procedure['data'] =  $procedure_result;
			$procedure['type'] = 'procedure';
        }	
		
		$payment_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_id."'";
        $payment_q = $this->db->query($payment_sql);
        $payment_result = $payment_q->result_array();
		if (!empty($payment_result))
        {
			$payments['data'] =  $payment_result;
			$payments['type'] = 'payments';
        }	
		
		$patient_sql = "Select wife_name, wife_phone, wife_email from ".$this->config->item('db_prefix')."patients where patient_id='".$patient_id."'";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		if (!empty($patient_result))
        {
           $patient_result = $patient_result[0];
        }
		$response = array();
		$response = array('consultation_result' => $consultation, 'investigate_result' => $investigate, 'procedure_result' => $procedure, 'patient_result'=> $patient_result, 'payments'=> $payments);
		return $response;
	}
	
	function get_procedure_name($id){
		$result = array();
		$sql = "Select procedure_name from ".$this->config->item('db_prefix')."procedures where ID='".$id."'";
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
	
	function get_item_name($item){
		$result = array();
		$sql = "Select item_name from ".$this->config->item('db_prefix')."stocks where item_number='".$item."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['item_name'];
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
	
	function update_billing($billing, $type){
		$sql = "";
		if($type == 'consultation'){
			$sql = "UPDATE `".$this->config->item('db_prefix')."consultation` SET `status`='accepted' WHERE receipt_number='".$billing."'";
		}
		if($type == 'investigate'){
			$sql = "UPDATE `".$this->config->item('db_prefix')."patient_investigations` SET `status`='accepted' WHERE receipt_number='".$billing."'";
		}
		if($type == 'procedure'){
			 $sql = "UPDATE `".$this->config->item('db_prefix')."patient_procedure` SET `status`='accepted' WHERE receipt_number='".$billing."'";
		}
		
        $this->db->query($sql);
        return $this->db->affected_rows();
	}
	
	function accept_billing(){
		$consultation_result = $investigate_result = $procedure_result = $consultation =  $investigate =  $procedure = array();
		
		$consultation_sql = "Select on_date, receipt_number, fees, remaining_amount, billing_from, billing_at, accepted from ".$this->config->item('db_prefix')."consultation where status='approved' AND remaining_amount='0'";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
				
		$investigate_sql = "Select on_date, receipt_number, fees, discount_amount, remaining_amount, billing_from, billing_at, accepted from ".$this->config->item('db_prefix')."patient_investigations where  status='approved' AND remaining_amount='0'";
        $investigate_q = $this->db->query($investigate_sql);
        $investigate_result = $investigate_q->result_array();
		
		$procedure_sql = "Select procedure_parent, on_date, receipt_number, fees, discount_amount, remaining_amount, billing_from, billing_at, accepted from ".$this->config->item('db_prefix')."patient_procedure where status='approved' AND remaining_amount='0'";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
				
		$response = array();
		$response = array('consultation_result' => $consultation_result, 'investigate_result' => $investigate_result, 'procedure_result' => $procedure_result);
		return $response;
	}
	
	function center_accept_billing(){
		$consultation_result = $investigate_result = $procedure_result = $consultation =  $investigate =  $procedure = array();
		
		$consultation_sql = "Select on_date, receipt_number, fees, remaining_amount, billing_from, billing_at, accepted from ".$this->config->item('db_prefix')."consultation where status='approved' AND remaining_amount='0' AND billing_at='".$_SESSION['logged_accountant']['center']."'";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
				
		$investigate_sql = "Select on_date, receipt_number, fees, discount_amount, remaining_amount, billing_from, billing_at, accepted from ".$this->config->item('db_prefix')."patient_investigations where  status='approved' AND remaining_amount='0' AND billing_at='".$_SESSION['logged_accountant']['center']."'";
        $investigate_q = $this->db->query($investigate_sql);
        $investigate_result = $investigate_q->result_array();
		
		$procedure_sql = "Select procedure_parent, on_date, receipt_number, fees, discount_amount, remaining_amount, billing_from, billing_at, accepted from ".$this->config->item('db_prefix')."patient_procedure where status='approved' AND remaining_amount='0' AND billing_at='".$_SESSION['logged_accountant']['center']."'";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
				
		$response = array();
		$response = array('consultation_result' => $consultation_result, 'investigate_result' => $investigate_result, 'procedure_result' => $procedure_result);
		return $response;
	}
	
	function patient_ledger(){
		$result = array();
		
		$patient_sql = "Select patient_id, wife_name from ".$this->config->item('db_prefix')."patients order by ID desc";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		if (!empty($patient_result))
        {
		   foreach($patient_result as $key => $val){
		   		$c_fee = $c_remain = $c_done = $c_ivf = $c_center =  $c_share = $i_fee = $i_remain = $i_done = $i_ivf = $i_center =  $i_share = $p_fee = $p_remain = $p_done = $p_ivf = $p_center = $p_center_share = $p_ivf_share = 0;
								
				$procedure_sql = "Select payment_done, remaining_amount, center_share, fees, billing_from from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$val['patient_id']."' and status='approved'";
				$procedure_q = $this->db->query($procedure_sql);
				$procedure_result = $procedure_q->result_array();
				if (!empty($procedure_result))
				{
					foreach($procedure_result as $ky => $vl){
						$p_fee += $vl['fees'];
						$p_done += $vl['payment_done'];
						$p_remain += $vl['remaining_amount'];
						$p_center_share  += ($vl['fees']-$vl['center_share']);
						$p_ivf_share += $vl['center_share'];
						if($vl['billing_from'] == 'IndiaIVF'){
							$p_ivf += $vl['payment_done'];
						}else{
							$p_center += $vl['payment_done'];
						}
					}
				}
				
				$total = $balance = $payment_ivf = $payment_center = 0;
				
				$total = $c_fee + $i_fee + $p_fee;
				$balance = $c_remain + $i_remain + $p_remain;
				$payment_ivf_share = $p_ivf_share;
				$payment_center_share = $p_center_share;
				$payment_ivf = $p_ivf;
				$payment_center = $p_center;
				
				if($total > 0){
					$result[] = array('patient_id' => $val['patient_id'], 'patient_name' => $val['wife_name'], 'total' => $total, 'balance' => $balance, 'payment_ivf_share' => $payment_ivf_share, 'payment_center_share' => $payment_center_share, 'payment_ivf' => $payment_ivf, 'payment_center' => $payment_center);
				}
		   }
        }
		return $result;
	}
	
	function ajax_patient_month_ledger($month){
		$result = array();
		
		$patient_sql = "Select patient_id, wife_name from ".$this->config->item('db_prefix')."patients order by ID desc";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		$html = '';
		if (!empty($patient_result))
        {
		   foreach($patient_result as $key => $val){
		   		$c_fee = $c_remain = $c_done = $c_ivf = $c_center =  $c_share = $i_fee = $i_remain = $i_done = $i_ivf = $i_center =  $i_share = $p_fee = $p_remain = $p_done = $p_ivf = $p_center = $p_center_share = $p_ivf_share = 0;
				
				$procedure_sql = "Select payment_done, remaining_amount, center_share, fees, billing_from from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$val['patient_id']."' AND MONTH(on_date) = '".$month."'";
				$procedure_q = $this->db->query($procedure_sql);
				$procedure_result = $procedure_q->result_array();
				if (!empty($procedure_result))
				{
					foreach($procedure_result as $ky => $vl){
						$p_fee += $vl['fees'];
						$p_done += $vl['payment_done'];
						$p_remain += $vl['remaining_amount'];
						$p_center_share  += ($vl['fees']-$vl['center_share']);
						$p_ivf_share += $vl['center_share'];
						if($vl['billing_from'] == 'IndiaIVF'){
							$p_ivf += $vl['payment_done'];
						}else{
							$p_center += $vl['payment_done'];
						}
					}
				}
				
				$total = $balance = $payment_ivf = $payment_center = 0;
				
				$total = $c_fee + $i_fee + $p_fee;
				$balance = $c_remain + $i_remain + $p_remain;
				$payment_ivf_share = $p_ivf_share;
				$payment_center_share = $p_center_share;
				$payment_ivf = $p_ivf;
				$payment_center = $p_center;
									
				if($total > 0){
					$current_balance = $this->get_current_balance($val['patient_id']);
					$payment_at = $this->get_payment_at($val['patient_id']);
					$html .= '<tr class="odd gradeX">
							  <td>'.$val['patient_id'].'</td>
							  <td>'.$val['wife_name'].'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$total.'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.($total-$current_balance).'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$current_balance.'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$payment_ivf_share.'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$payment_center_share.'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$payment_at['payment_ivf'].'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$payment_at['payment_center'].'</td>
							</tr>';
				}
		   }
        }
		return $html;
	}
	
	function ajax_patient_custom_ledger($start, $end){
		$result = array();
		
		$patient_sql = "Select patient_id, wife_name from ".$this->config->item('db_prefix')."patients order by ID desc";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		$html = '';
		if (!empty($patient_result))
        {
		   foreach($patient_result as $key => $val){
		   		$c_fee = $c_remain = $c_done = $c_ivf = $c_center =  $c_share = $i_fee = $i_remain = $i_done = $i_ivf = $i_center =  $i_share = $p_fee = $p_remain = $p_done = $p_ivf = $p_center = $p_center_share = $p_ivf_share = 0;
				
				$procedure_sql = "Select payment_done, remaining_amount, center_share, fees, billing_from from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$val['patient_id']."' AND on_date between '".$start."' AND '".$end."'";
				$procedure_q = $this->db->query($procedure_sql);
				$procedure_result = $procedure_q->result_array();
				if (!empty($procedure_result))
				{
					foreach($procedure_result as $ky => $vl){
						$p_fee += $vl['fees'];
						$p_done += $vl['payment_done'];
						$p_remain += $vl['remaining_amount'];
						$p_center_share  += ($vl['fees']-$vl['center_share']);
						$p_ivf_share += $vl['center_share'];
						if($vl['billing_from'] == 'IndiaIVF'){
							$p_ivf += $vl['payment_done'];
						}else{
							$p_center += $vl['payment_done'];
						}
					}
				}
				
				$total = $balance = $payment_ivf = $payment_center = 0;
				
				$total = $c_fee + $i_fee + $p_fee;
				$balance = $c_remain + $i_remain + $p_remain;
				$payment_ivf_share = $p_ivf_share;
				$payment_center_share = $p_center_share;
				$payment_ivf = $p_ivf;
				$payment_center = $p_center;
				if($total > 0){
					$current_balance = $this->get_current_balance($val['patient_id']);
					$payment_at = $this->get_payment_at($val['patient_id']);
					$html .= '<tr class="odd gradeX">
							  <td>'.$val['patient_id'].'</td>
							  <td>'.$val['wife_name'].'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$total.'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.($total-$current_balance).'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$current_balance.'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$payment_ivf_share.'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$payment_center_share.'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$payment_at['payment_ivf'].'</td>
							  <td><i class="fa fa-inr" aria-hidden="true"></i> '.$payment_at['payment_center'].'</td>
							</tr>';
				}
		   }
        }
		return $html;
	}
	
	function center_patient_ledger(){
		$result = array();
		
		$patient_sql = "Select patient_id, wife_name from ".$this->config->item('db_prefix')."patients order by ID desc";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		if (!empty($patient_result))
        {
			$conditions = '';
			//var_dump($_SESSION['logged_accountant']['center']); die;
			if(isset($_SESSION['logged_accountant']['center']) && $_SESSION['logged_accountant']['center'] != 0)
			{ $conditions = ' AND billing_at="'.$_SESSION['logged_accountant']['center'].'"'; }
			
		    foreach($patient_result as $key => $val){
		   		$c_fee = $c_remain = $c_done = $c_ivf = $c_center =  $c_share = $i_fee = $i_remain = $i_done = $i_ivf = $i_center =  $i_share = $p_fee = $p_remain = $p_done = $p_ivf = $p_center = $p_center_share = $p_ivf_share = 0;
				
				 $procedure_sql = "Select payment_done, remaining_amount, center_share, fees, billing_from from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$val['patient_id']."' $conditions AND status='approved'";
				$procedure_q = $this->db->query($procedure_sql);
				$procedure_result = $procedure_q->result_array();
				if (!empty($procedure_result))
				{
					foreach($procedure_result as $ky => $vl){
						$p_fee += $vl['fees'];
						$p_done += $vl['payment_done'];
						
						
						$p_remain += $vl['remaining_amount'];
						$p_center_share  += ($vl['fees']-$vl['center_share']);
						$p_ivf_share += $vl['center_share'];
						if($vl['billing_from'] == 'IndiaIVF'){
							$p_ivf += $vl['payment_done'];
						}else{
							$p_center += $vl['payment_done'];
						}
					}
				}
				
				$total = $balance = $payment_ivf = $payment_center = 0;
				
				$total = $c_fee + $i_fee + $p_fee;
				$balance = $c_remain + $i_remain + $p_remain;
				$payment_ivf_share = $p_ivf_share;
				$payment_center_share = $p_center_share;
				$payment_ivf = $p_ivf;
				$payment_center = $p_center;
				
				if($total > 0){
					$result[] = array('patient_id' => $val['patient_id'], 'patient_name' => $val['wife_name'], 'total' => $total, 'balance' => $balance, 'payment_ivf_share' => $payment_ivf_share, 'payment_center_share' => $payment_center_share, 'payment_ivf' => $payment_ivf, 'payment_center' => $payment_center);
				}
		   }
        }
		return $result;
	}

	function ajax_center_patient_ledger_data($start, $end, $center){
		$result = array();
		$response = "";

		$patient_sql = "Select patient_id, wife_name from ".$this->config->item('db_prefix')."patients order by ID desc";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		if (!empty($patient_result))
        {
			$conditions = '';
			//var_dump($_SESSION['logged_accountant']['center']); die;
			if($center != 0){ $conditions = ' AND billing_at="'.$center.'"'; }
		    foreach($patient_result as $key => $val){
		   		$c_fee = $c_remain = $c_done = $c_ivf = $c_center =  $c_share = $i_fee = $i_remain = $i_done = $i_ivf = $i_center =  $i_share = $p_fee = $p_remain = $p_done = $p_ivf = $p_center = $p_center_share = $p_ivf_share = 0;
				
				$procedure_sql = "Select payment_done, remaining_amount, center_share, fees, billing_from from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$val['patient_id']."' AND on_date between '".$start."' AND '".$end."' $conditions AND status='approved'";
				$procedure_q = $this->db->query($procedure_sql);
				$procedure_result = $procedure_q->result_array();
				if (!empty($procedure_result))
				{
					foreach($procedure_result as $ky => $vl){
						$p_fee += $vl['fees'];
						$p_done += $vl['payment_done'];
						$p_remain += $vl['remaining_amount'];
						$p_center_share  += ($vl['fees']-$vl['center_share']);
						$p_ivf_share += $vl['center_share'];
						if($vl['billing_from'] == 'IndiaIVF'){
							$p_ivf += $vl['payment_done'];
						}else{
							$p_center += $vl['payment_done'];
						}
					}
				}
				
				$total = $balance = $payment_ivf = $payment_center = 0;
				
				$total = $c_fee + $i_fee + $p_fee;
				$balance = $c_remain + $i_remain + $p_remain;
				$payment_ivf_share = $p_ivf_share;
				$payment_center_share = $p_center_share;
				$payment_ivf = $p_ivf;
				$payment_center = $p_center;
				
				if($total > 0){
					$result[] = array('patient_id' => $val['patient_id'], 'patient_name' => $val['wife_name'], 'total' => $total, 'balance' => $balance, 'payment_ivf_share' => $payment_ivf_share, 'payment_center_share' => $payment_center_share, 'payment_ivf' => $payment_ivf, 'payment_center' => $payment_center);
				}
		   }
		}

		if(!empty($result)){
			foreach($result as $key => $vl){
				$current_balance = $this->get_current_balance($vl['patient_id']);
                $payment_at = $this->get_payment_at($vl['patient_id']);
				$response .='<tr class="odd gradeX">
				<td><a href="'.base_url().'accounts/patient_details/'.$vl['patient_id'].'">'.$vl['patient_id'].'</a></td>
				<td>'. $vl['patient_name'].'</td>
				<td>'. $vl['total'].'</td>
				<td>'. ($vl['total'] - $current_balance).'</td>
				<td>'. $current_balance.'</td>                  
				<td>'. $vl['payment_ivf_share'].'</td>
				<td>'. $vl['payment_center_share'].'</td>
				<td>'. $payment_at['payment_ivf'].'</td>
				<td>'. $payment_at['payment_center'].'</td>
			  </tr>';
			}
		}
		return $response;
	}

	function download_center_patient_ledger_data($start, $end, $center){
		$result = array();
		
		$patient_sql = "Select patient_id, wife_name from ".$this->config->item('db_prefix')."patients order by ID desc";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		if (!empty($patient_result))
        {
			$conditions = '';
			//var_dump($_SESSION['logged_accountant']['center']); die;
			if($center != 0){ $conditions = ' AND billing_at="'.$center.'"'; }
			if($start !== "" && $end !== ""){
				$conditions .= " AND on_date between '".$start."' AND '".$end."'";
			}
			
		    foreach($patient_result as $key => $val){
		   		$c_fee = $c_remain = $c_done = $c_ivf = $c_center =  $c_share = $i_fee = $i_remain = $i_done = $i_ivf = $i_center =  $i_share = $p_fee = $p_remain = $p_done = $p_ivf = $p_center = $p_center_share = $p_ivf_share = 0;
				
				$procedure_sql = "Select payment_done, remaining_amount, center_share, fees, billing_from from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$val['patient_id']."'  $conditions AND status='approved'";
			//	echo $procedure_sql;die;
				$procedure_q = $this->db->query($procedure_sql);
				$procedure_result = $procedure_q->result_array();
				if (!empty($procedure_result)){
					foreach($procedure_result as $ky => $vl){
						$p_fee += $vl['fees'];
						$p_done += $vl['payment_done'];
						
						
						
						$p_remain += $vl['remaining_amount'];
						$p_center_share  += ($vl['fees']-$vl['center_share']);
						$p_ivf_share += $vl['center_share'];
						if($vl['billing_from'] == 'IndiaIVF'){
							$p_ivf += $vl['payment_done'];
						}else{
							$p_center += $vl['payment_done'];
						}
					}
				}
				$total_paid_amount = 0;
				 $patient_sql3 ="SELECT sum(payment_done) as payment from hms_patient_payments where type='procedure' AND status='1' AND patient_id='".$val['patient_id']."'";
						$patient_sql2 = $this->db->query($patient_sql3);
				        $patient_result2 = $patient_sql2->result_array();
						  foreach($patient_result2 as $ky1 => $val1){
							$total_paid_amount += $val1['payment'];
						  }
				//($total_paid_amount);//die;
				$total = $balance = $payment_ivf = $payment_center = 0;
				
				$total = $c_fee + $i_fee + $p_fee;
				$balance = $c_remain + $i_remain + $p_remain - $total_paid_amount;
				$payment_ivf_share = $p_ivf_share;
				$payment_center_share = $p_center_share;
				$payment_ivf = $p_ivf;
				$payment_center = $p_center;
				
				if($total > 0){
					$result[] = array('patient_id' => $val['patient_id'], 'patient_name' => $val['wife_name'], 'total' => $total, 'payment_center' => $payment_center, 'balance' => $balance, 'payment_ivf_share' => $payment_ivf_share, 'payment_center_share' => $payment_center_share, 'payment_ivf' => $payment_ivf, 'payment_center' => $payment_center);
				}
		   }
        }
		return $result;
	}
	
	function billings_request_list(){
		$consultation_result = $investigate_result = $procedure_result = array();
		$conditions = '';
		//var_dump($_SESSION['logged_accountant']['center']); die;
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ $conditions = ' where billing_at="'.$_SESSION['logged_accountant']['center'].'"'; }
		
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation ".$conditions." order by on_date desc";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
		
		$investigate_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations ".$conditions." order by on_date desc";
        $investigate_q = $this->db->query($investigate_sql);
        $investigate_result = $investigate_q->result_array();
		
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure ".$conditions." order by on_date desc";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
        
        $patient_payments_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments ".$conditions." order by on_date desc";
        $patient_payments_q = $this->db->query($patient_payments_sql);
        $patient_payments_result = $patient_payments_q->result_array();
		
		$response = array();
		$response = array('consultation_result' => $consultation_result, 'investigate_result' => $investigate_result, 'procedure_result' => $procedure_result, 'patient_payments_result' => $patient_payments_result);
		return $response;
	}
	
	function approve_billing($request, $type, $status, $reason, $reason_of_cancle, $cn_invoice){ 
		$result = array();
		if($type == 'consultation'){			
			$sql = "UPDATE `".$this->config->item('db_prefix')."consultation` SET `status`='$status',`reason_of_disapprove`='$reason',`reason_of_cancle`='$reason_of_cancle',`cn_invoice`='$cn_invoice',`modified_on`='".date('Y-m-d H:i:s')."' WHERE ID='".$request."'";
		}else if($type == 'registation'){
			$sql = "UPDATE `".$this->config->item('db_prefix')."registation` SET `status`='$status',`reason_of_disapprove`='$reason',`reason_of_cancle`='$reason_of_cancle',`cn_invoice`='$cn_invoice',`modified_on`='".date('Y-m-d H:i:s')."' WHERE ID='".$request."'";
		}else if($type == 'investigation'){
			$sql = "UPDATE `".$this->config->item('db_prefix')."patient_investigations` SET `status`='$status',`reason_of_disapprove`='$reason',`modified_on`='".date('Y-m-d H:i:s')."' WHERE ID='".$request."'";
		}else if($type == 'medicine'){
			$sql = "UPDATE `".$this->config->item('db_prefix')."patient_medicine` SET `status`='$status',`reason_of_disapprove`='$reason',`reason_of_cancle`='$reason_of_cancle',`cn_invoice`='$cn_invoice',`modified_on`='".date('Y-m-d H:i:s')."' WHERE ID='".$request."'";
		}else if($type == 'partialpayments'){
		  $sql = "UPDATE `".$this->config->item('db_prefix')."patient_payments` SET `status`='$status',`disapproval_reason`='$reason',`reason_of_cancle`='$reason_of_cancle',`cn_invoice`='$cn_invoice',`modified_on`='".date('Y-m-d H:i:s')."' WHERE ID='".$request."'";
		}
		
        $q = $this->db->query($sql);
        return 1;
	}
	
	function approve_registation($ID){
		 $sql = "UPDATE `".$this->config->item('db_prefix')."registation` SET `status`='approved',`modified_on`='".date('Y-m-d H:i:s')."' WHERE ID='".$ID."'";
	    $this->db->query($sql);
        return 1;
	}
	
	function approve_consultation($ID){
		$sql = "UPDATE `".$this->config->item('db_prefix')."consultation` SET `status`='approved',`modified_on`='".date('Y-m-d H:i:s')."' WHERE ID='".$ID."'";
		$this->db->query($sql);
        return 1;
	}

	public function get_all_sales_for_tally()
{
    $this->db->select('*'); // adjust your fields
    $this->db->from('hms_patient_procedure'); // replace with your actual table
    $this->db->order_by('id', 'DESC');
    $this->db->limit(10); // prevent loading thousands at once

    $query = $this->db->get();
    return $query->result_array();
}

	
	function send_procedure_tally($ID) {
    $sales = [];
    $sql = "SELECT * FROM hms_patient_procedure WHERE ID = " . (int)$ID;
    $result = run_select_query($sql);

    if (!empty($result)) {
        $row = $result;
        $unserialized_data = unserialize($row['data']);
        $procedure = $unserialized_data['patient_procedures'][0];
        
        $sql = "SELECT * FROM hms_procedures WHERE ID ='".$procedure['sub_procedure']."'";
        $procedure_result = run_select_query($sql);
        
        $sql_patients = "SELECT * FROM hms_patients WHERE patient_id ='".$row['patient_id']."'";
        $patients_result = run_select_query($sql_patients);
        
        $sql_centers = "SELECT * FROM hms_centers WHERE center_number ='".$row['billing_at']."'";
        $centers_result = run_select_query($sql_centers);
        
        $sql_employees = "SELECT * FROM hms_employees WHERE employee_number ='".$row['biller_id']."'";
        $employees_result = run_select_query($sql_employees);
        
        $sql_embryo_transfer = "SELECT * FROM embryo_transfer_discharge_summary WHERE iic_id='" . $row['patient_id'] . "' order by ID ASC";
        $select_embryo_transfer = run_select_query($sql_embryo_transfer);

        // FIXED: Properly handle the embryo transfer data
        $date_of_admission = null;
        $formatted_admission_date = null;
        $type = 'New';  // Default
        
        if (!empty($select_embryo_transfer)) {
            // Check if it's a single row or multiple rows
            if (isset($select_embryo_transfer['date_of_addmission'])) {
                // Single row result
                $date_of_admission = $select_embryo_transfer['date_of_addmission'];
            } elseif (is_array($select_embryo_transfer) && count($select_embryo_transfer) > 0) {
                // Multiple rows result - get the first one
                $first_embryo = $select_embryo_transfer[0];
                $date_of_admission = isset($first_embryo['date_of_addmission']) ? $first_embryo['date_of_addmission'] : null;
            }
            
            if (!empty($date_of_admission)) {
                $formatted_admission_date = date('Y-m-d', strtotime($date_of_admission));
                
                // Determine type based on admission date
                if (strtotime($date_of_admission) < strtotime($row['on_date'])) {
                    $type = 'recycle';
                }
            }
        }

        // Build the return array - FIXED: Safely access embryo transfer data
        $return_data = [
            "patient_id" => $row['patient_id'],
            "patient_name" => $patients_result['wife_name'] . ' W/O ' . $patients_result['husband_name'],
            "billing_center" => $centers_result['center_name'],
            "booking_center" => $centers_result['center_name'],
            "origin_center" => $centers_result['center_name'],
            "on_date" => date('d-m-Y', strtotime($row['on_date'])),
            "receipt_number" => $row['receipt_number'],
            // This will use the name if it exists, or 'N/A' if it doesn't.
"biller_name" => $employees_result['name'] ?? 'N/A',
            "procedure_type" => $type . ($date_of_admission ? " (Admission: " . $date_of_admission . ")" : ""),
            "patient_procedures" => [
                [   
                    "procedure_name" => $procedure_result['procedure_name'],
                    "category" => $procedure_result['category'],
                    "sub_procedure" => $procedure['sub_procedure'],
                    "sub_procedures_code" => $procedure['sub_procedures_code'],
                    "sub_procedures_price" => $procedure['sub_procedures_price'],
                    "sub_procedures_discount" => $procedure['sub_procedures_discount'],
                    "sub_procedures_after_discount" => $procedure['sub_procedures_price'] - $procedure['sub_procedures_discount'],
                    "sub_procedures_paid_price" => $procedure['sub_procedures_paid_price']
                ]
            ],
            "payment_method" => $row['payment_method']
        ];
        
        // Add admission date if available
        if ($formatted_admission_date) {
            $return_data["admission_date"] = $formatted_admission_date;
        }
        
        return $return_data;
    }
    return null; // Return null if no data found
}
	
	function approve_billing_by_receipt($request, $type, $status, $reason){
		$result = array();
		if($type == 'consultation'){			
		   $sql = "UPDATE `".$this->config->item('db_prefix')."consultation` SET `status`='$status',`reason_of_disapprove`='$reason' WHERE receipt_number='".$request."'";
		}else if($type == 'investigation'){
		   $sql = "UPDATE `".$this->config->item('db_prefix')."patient_investigations` SET `status`='$status',`reason_of_disapprove`='$reason' WHERE receipt_number='".$request."'";
		}else if($type == 'procedure'){
		   $sql = "UPDATE `".$this->config->item('db_prefix')."patient_procedure` SET `status`='$status',`reason_of_disapprove`='$reason' WHERE receipt_number='".$request."'";
		}
        $q = $this->db->query($sql);
        return 1;
	}
	
	function update_accept_billing($receipt, $type){
		$result = array();
		if($type == 'consultation'){			
			$sql = "UPDATE `".$this->config->item('db_prefix')."consultation` SET `accepted`='1' WHERE receipt_number='".$receipt."'";
		}else if($type == 'investigation'){
			$sql = "UPDATE `".$this->config->item('db_prefix')."patient_investigations` SET `accepted`='1' WHERE receipt_number='".$receipt."'";
		}else if($type == 'procedure'){
			$sql = "UPDATE `".$this->config->item('db_prefix')."patient_procedure` SET `accepted`='1' WHERE receipt_number='".$receipt."'";
		}
        $q = $this->db->query($sql);
        return 1;
	}
	
	function reconciliation(){
		
		$result = array();
		
		$patient_sql = "Select patient_id, wife_name from ".$this->config->item('db_prefix')."patients order by ID desc";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		if (!empty($patient_result))
        {
		   foreach($patient_result as $key => $val){
		   		$c_fee = $c_remain = $c_done = $c_ivf = $c_center =  $c_share = $i_fee = $i_remain = $i_done = $i_ivf = $i_center =  $i_share = $p_fee = $p_remain = $p_done = $p_ivf = $p_center = $p_center_share = $p_ivf_share = $p_totalpackage = 0;
				
				$procedure_sql = "Select patient_id, totalpackage, payment_done, remaining_amount, center_share, fees, billing_from from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$val['patient_id']."' AND share='1' AND status='approved'";
				$procedure_q = $this->db->query($procedure_sql);
				$procedure_result = $procedure_q->result_array();
				if (!empty($procedure_result))
				{
					foreach($procedure_result as $ky => $vl){
						$p_totalpackage += $vl['totalpackage'];
						$p_fee += $vl['fees'];
						$p_done += $vl['payment_done'];
						$p_remain += $vl['remaining_amount'];
						$p_center_share  += ($vl['fees']-$vl['center_share']);
						$p_ivf_share += $vl['center_share'];
						if($vl['billing_from'] == 'IndiaIVF'){
							$p_ivf += $vl['payment_done'];
						}else{
							$p_center += $vl['payment_done'];
						}
					}
				}
				
				$total = $balance = $payment_ivf = $payment_center = 0;
				
				$total = $c_fee + $i_fee + $p_fee;
				$balance = $c_remain + $i_remain + $p_remain;
				$payment_ivf_share = $p_ivf_share;
				$payment_center_share = $p_center_share;
				$payment_ivf = $p_ivf;
				$payment_center = $p_center;
				
				if($total > 0){
					$result[] = array('patient_id' => $val['patient_id'], 'patient_name' => $val['wife_name'], 'total' => $total, 'balance' => $balance, 'payment_ivf_share' => $payment_ivf_share, 'payment_center_share' => $payment_center_share, 'payment_ivf' => $payment_ivf, 'payment_center' => $payment_center, 'totalpackage' => $p_totalpackage);
				}
		   }
        }
		return $result;
	}
	
	function center_reconciliation(){
	
		$result = array();
		$patient_sql = "Select patient_id, wife_name from ".$this->config->item('db_prefix')."patients order by ID desc";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		if (!empty($patient_result))
        {
			$conditions = '';
			//var_dump($_SESSION['logged_accountant']['center']); die;
			if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ $conditions = ' AND billing_at="'.$_SESSION['logged_accountant']['center'].'"'; }
			
		   foreach($patient_result as $key => $val){
		   		$c_fee = $c_remain = $c_done = $c_ivf = $c_center =  $c_share = $i_fee = $i_remain = $i_done = $i_ivf = $i_center =  $i_share = $p_fee = $p_remain = $p_done = $p_ivf = $p_center = $p_center_share = $p_ivf_share = $p_totalpackage = 0;
				
				$procedure_sql = "Select patient_id, totalpackage, payment_done, remaining_amount, center_share, fees, billing_from from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$val['patient_id']."' AND share='1' AND status='approved' $conditions";
				$procedure_q = $this->db->query($procedure_sql);
				$procedure_result = $procedure_q->result_array();
				if (!empty($procedure_result))
				{
					foreach($procedure_result as $ky => $vl){ //var_dump($vl);die;
						$p_totalpackage += $vl['totalpackage'];
						$p_fee += $vl['fees'];
						$p_done += $vl['payment_done'];
						$p_remain += $vl['remaining_amount'];
						$p_center_share  += ($vl['fees']-$vl['center_share']);
						$p_ivf_share += $vl['center_share'];
						if($vl['billing_from'] == 'IndiaIVF'){
							$p_ivf += $vl['payment_done'];
						}else{
							$p_center += $vl['payment_done'];
						}
					}
				}
				
				$total = $balance = $payment_ivf = $payment_center = 0;
				
				$total = $c_fee + $i_fee + $p_fee;
				$balance = $c_remain + $i_remain + $p_remain;
				$payment_ivf_share = $p_ivf_share;
				$payment_center_share = $p_center_share;
				$payment_ivf = $p_ivf;
				$payment_center = $p_center;
				
				if($total > 0){
					$result[] = array('patient_id' => $val['patient_id'], 'patient_name' => $val['wife_name'], 'total' => $total, 'balance' => $balance, 'payment_ivf_share' => $payment_ivf_share, 'payment_center_share' => $payment_center_share, 'payment_ivf' => $payment_ivf, 'payment_center' => $payment_center, 'totalpackage' => $p_totalpackage);
				}
		   }
        }
		return $result;
	}
	
	
	function get_details($receipt, $type){
		$result = array();
		if($type == 'consultation'){			
			$sql = "Select * from `".$this->config->item('db_prefix')."consultation` WHERE receipt_number='".$receipt."'";
		}else if($type == 'registation'){
			$sql = "Select * from `".$this->config->item('db_prefix')."registation` WHERE receipt_number='".$receipt."'";
		}else if($type == 'investigation'){
			$sql = "Select * from `".$this->config->item('db_prefix')."patient_investigations` WHERE receipt_number='".$receipt."'";
		}else if($type == 'procedure'){
			$sql = "Select * from `".$this->config->item('db_prefix')."patient_procedure` WHERE receipt_number='".$receipt."'";
		}else if($type == 'package'){
			$sql = "Select * from `".$this->config->item('db_prefix')."patient_procedure` WHERE receipt_number='".$receipt."'";
		}else if($type == 'medicine'){
			$sql = "Select * from `".$this->config->item('db_prefix')."patient_medicine` WHERE receipt_number='".$receipt."'";
		}
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
	
	function patient_payments($patient_id){
		
		$sql = "Select sum(payment_done) as payment_done, sum(fees) as fees from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_id."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		
		$done_sql = "Select sum(payment_done) as payment_done from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_id."'";
        $done_q = $this->db->query($done_sql);
        $done_result = $done_q->result_array();
		
		$fees = $result[0]['fees'];
		$billing_payment_done = $result[0]['payment_done'];
		$patient_payments_done = $done_result[0]['payment_done'];
		$total_done = round($billing_payment_done+$patient_payments_done, 2);
		$current_balance = round($fees - $total_done, 2);
		
		
		$patient_sql = "Select wife_name, patient_phone, wife_email from ".$this->config->item('db_prefix')."patients where patient_id='".$patient_id."'";
        $patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		
		$response = array();
		$response = array('current_balance' => $current_balance, 'patient_id' =>$patient_id, 'wife_name' =>$patient_result[0]['wife_name'], 'patient_phone' =>$patient_result[0]['patient_phone'], 'wife_email' =>$patient_result[0]['wife_email']);
		return $response;
	}
	
	function get_current_balance($patient_id){
		$sql = "Select sum(payment_done) as payment_done, sum(fees) as fees from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_id."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		
		$done_sql = "Select sum(payment_done) as payment_done from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_id."' AND type='procedure' and status='1'";
        $done_q = $this->db->query($done_sql);
        $done_result = $done_q->result_array();
		
		$fees = $result[0]['fees'];
		$billing_payment_done = $result[0]['payment_done'];
		$patient_payments_done = $done_result[0]['payment_done'];
		$total_done = round($billing_payment_done+$patient_payments_done, 2);
		$current_balance = round($fees - $total_done, 2);
		
		return $current_balance;
	}
	
	function get_payment_at($patient_id){
		$ivf_sql = "Select sum(payment_done) as payment_done from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_id."' AND billing_from='IndiaIVF' and status='approved'";
        $ivf_q = $this->db->query($ivf_sql);
        $ivf_result = $ivf_q->result_array();
		
		$cnter_sql = "Select sum(payment_done) as payment_done from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_id."' AND billing_from!='IndiaIVF' and status='approved'";
        $cnter_q = $this->db->query($cnter_sql);
        $cnter_result = $cnter_q->result_array();
		
		$ivf_p_sql = "Select sum(payment_done) as payment_done from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_id."' AND type='procedure' AND billing_from='IndiaIVF' AND status='1'";
        $ivf_p_q = $this->db->query($ivf_p_sql);
        $ivf_p_result = $ivf_p_q->result_array();
		
		$cnter_p_sql = "Select sum(payment_done) as payment_done from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_id."' AND type='procedure' AND billing_from!='IndiaIVF' AND status='1'";
        $cnter_p_q = $this->db->query($cnter_p_sql);
        $cnter_p_result = $cnter_p_q->result_array();
		
		$billing_done = $ivf_result[0]['payment_done'];
		$cnter_done = $cnter_result[0]['payment_done'];
		$billing_p_done = $ivf_p_result[0]['payment_done'];
		$cnter_p_done = $cnter_p_result[0]['payment_done'];
		
		$payment_ivf = ($billing_done+$billing_p_done);
		$payment_ivf = round($payment_ivf, 2);
		$payment_center = ($cnter_done+$cnter_p_done);
		$payment_center = round($payment_center, 2);
		
		$response = array('payment_ivf' => $payment_ivf, 'payment_center' => $payment_center);
		return $response;		
	}
		
	
	function partial_payments(){
		$sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where status='0' order by on_date desc";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		return $result;
	}
	
	/*function approve_payment($id){
		$sql = "";
		$sql = "UPDATE `".$this->config->item('db_prefix')."patient_payments` SET `status`='1' WHERE ID='".$id."'";
        $this->db->query($sql);
		return $this->db->affected_rows();
	} */
	
	function approve_medicine($ID){
		$sql = "";
		$sql = "UPDATE `".$this->config->item('db_prefix')."patient_medicine` SET `status`='approved', `modified_on`='" . date_default_timezone_set('Asia/Kolkata') . date('Y-m-d H:i:s') . "' WHERE ID='".$ID."'";
        $this->db->query($sql);
        return $this->db->affected_rows();
	}
	
	function approve_procedure($ID){
	 $sql = "UPDATE `".$this->config->item('db_prefix')."patient_procedure` SET `status`='approved',`modified_on`='".date('Y-m-d H:i:s')."' WHERE ID='".$ID."'";
		$this->db->query($sql);
		
		$inserted_ids = []; // To store IDs of inserted records

	 $sql = "SELECT * FROM hms_patient_procedure WHERE ID = " . (int)$ID;
		$result = run_select_query($sql);

		if (!empty($result)) {
			$row = $result;
			$unserialized_data = unserialize($row['data']);
			
			foreach ($unserialized_data['patient_procedures'] as $procedure) {
				// Prepare data for hms_package_collections
				$collection_data = [
					"patient_id" => $row['patient_id'],
					"procedure_id" => $ID, // Original procedure ID
					"receipt_number" => $row['receipt_number'],
					"biller_id" => $row['biller_id'],
					"procedure_name" => $procedure['sub_procedure'],
					"procedure_code" => $procedure['sub_procedures_code'],
					"original_price" => $procedure['sub_procedures_price'],
					"discount" => $procedure['sub_procedures_discount'],
					"final_price" => $procedure['sub_procedures_paid_price'],
					"payment_status" => ($row['payment_done'] == $row['totalpackage']) ? 'paid' : 'partial',
					"payment_method" => $row['payment_method'],
					"pkg_booking_date" => date('Y-m-d', strtotime($row['on_date'])),
					"created_at" => date('Y-m-d H:i:s'),
					"updated_at" => date('Y-m-d H:i:s')
				];

				$customMonths = [
					1 => 'Apr',
					2 => 'May',
					3 => 'June',
					4 => 'July',
					5 => 'August',
					6 => 'Sep',
					7 => 'Oct',
					8 => 'nov',
					9 => 'dec',
					10 => 'jan',
					11 => 'feb',
					12 => 'mar'
				];

				// Get current standard month number (1-12)
				$standardMonth = date('n'); // e.g., July = 7

				// Convert to your custom number (April=1 to March=12)
				if ($standardMonth >= 4) {
					$customMonthNumber = $standardMonth - 3; // April(4)=1, May(5)=2,...December(12)=9
				} else {
					$customMonthNumber = $standardMonth + 9; // January(1)=10, February(2)=11, March(3)=12
				}

				// Build and execute insert query
				$formatted_date = date('Y-m-d', strtotime($row['on_date']));
				$pkg_month = date("$customMonthNumber.F.y", strtotime($row['on_date']));
				$pkg_booking_year = date("Y", strtotime($row['on_date']));
				$date = strtotime($row['on_date']); // your input date
				$year = date("Y", $date);
				$month = date("n", $date); // numeric month (1-12)

				if ($month >= 4) {
					// From April to December: same year to next year
					$financial_year = $year . '-' . ($year + 1);
				} else {
					// From January to March: previous year to current year
					$financial_year = ($year - 1) . '-' . $year;
				}
				 $receipt_number = $row['receipt_number'];
				 $patient_id = $row['patient_id'];
				 $procedure_code = $procedure['sub_procedures_code'];
				 
			 $sql = "SELECT * FROM hms_procedures WHERE code ='$procedure_code'";
				$procedure_result = run_select_query($sql);
				
			 $app_sql = "SELECT * FROM hms_appointments WHERE paitent_id ='$patient_id'";
				$appointme_result = run_select_query($app_sql);
				
			 $center_sql = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$row['origins']."'";
				$center_result = run_select_query($center_sql);
				
			 $billing_center_sql = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$result['billing_at']."'";
				$billing_center_result = run_select_query($billing_center_sql);
				
				$procedure_name = $procedure_result['procedure_name'];
				$category = $procedure_result['category'];
				$type = $procedure_result['type'];
				$wife_name = $appointme_result['wife_name'];
				$nationality = $appointme_result['nationality'];
				$crm_id = $appointme_result['crm_id'];
				$origin = $center_result['center_name'];
				$billing_center = $billing_center_result['center_name'];
				$totalpackage = $result['totalpackage'];
				$discount = $result['discount_amount'];
				$fees = $result['fees'];
				$payment_done = $result['payment_done'];
				
				$formattedDate = date('m_d', strtotime($result['on_date']));
				$formattedmonth = date('M', strtotime($result['on_date']));
				
				 $lead_sql = "SELECT * FROM hms_leads WHERE lead_id ='$crm_id'";
				$lead_result = run_select_query($lead_sql);
				
				$lead_source = $lead_result['lead_source'];
				$agent = $lead_result['agent'];
				
			   $insert_sql = "INSERT INTO hms_package_collections (`pkg_booking_date`,`pkg_month`,`pkg_booking_year`,`financial_year`,`pkg_no`,`iic_id`,`pkg_code`,`pkg_description`,`category`,`sub_category2`,`patient_name`,`patient_category_2`,`origin_booking_centre`,`sales_reporting_centre`,`billing_centre`,`lead_source_id`,`source_category`,`source_3_agent_name`,`gross_revenue_pkg`,`discount`,`booked_pkg_amt_inc_gst`,`gst_in_booked_pkg_amt`,`booked_pkg_amt_ex_gst`,`collection_year_$formattedDate`,`collection_$formattedmonth`,`total_collection`) 
			    VALUES ('$formatted_date','$pkg_month','$pkg_booking_year','$financial_year',' $receipt_number','$patient_id','$procedure_code','$procedure_name','$category','$type','$wife_name','$nationality','$origin','$billing_center','$billing_center','$crm_id','$lead_source','$agent','$totalpackage','$discount','$fees','0','$fees','$payment_done','$payment_done','$payment_done')";
				$this->db->query($insert_sql);
				
				if ($this->db->affected_rows() > 0) {
					$inserted_ids[] = $this->db->insert_id();
				}
			   
			}
		}
		
			return 1;
		}
	
	function approve_procedure_billing($request, $type, $status, $reason, $reason_of_cancle, $cn_invoice, $used_amount){ 
		if ($type == 'procedure') {
			// Step 1: Update patient procedure status
			 $sql = "UPDATE `".$this->config->item('db_prefix')."patient_procedure` SET `status`='$status',`reason_of_disapprove`='$reason',`reason_of_cancle`='$reason_of_cancle',`cn_invoice`='$cn_invoice',`used_amount`='$used_amount',`modified_on`='".date('Y-m-d H:i:s')."' WHERE ID='".$request."'";
			$this->db->query($sql);

			// Step 2: Get procedure details
			$proce_sql = "SELECT * FROM hms_patient_procedure WHERE ID = '".$request."' and status='cancel'";
			$procedure_result = run_select_query($proce_sql);

			if (!empty($procedure_result)) {
				$receipt_number = $procedure_result['receipt_number'];

				// Step 3: Get package collection data
				 $packag_sql = "SELECT * FROM hms_package_collections WHERE pkg_no = '".$receipt_number."'";
				$packag_result = run_select_query($packag_sql);
				
				$cancelled_date = date('Y-m-d');
				$pkg_month = date("j.F.y");
				$pkg_booking_year = date("Y");
				
				$dateString = date('Y-m-d');
				$timestamp = time(); // Current timestamp

				$year = date("Y", $timestamp);
				$month = date("n", $timestamp);

				$financial_year = ($month >= 4) ? $year . '-' . ($year + 1) : ($year - 1) . '-' . $year;
				
				$booked_pkg_amt_ex_gst = $packag_result['booked_pkg_amt_ex_gst'];
				$total_collection = $packag_result['total_collection'];
				$patient_id = $packag_result['iic_id'];
				$procedure_code = $packag_result['pkg_code'];
				$procedure_name = $packag_result['pkg_description'];
				$category = $packag_result['category'];
				$wife_name = $packag_result['patient_name'];
				$origin = $packag_result['origin_booking_centre'];
				$billing_center = $packag_result['sales_reporting_centre'];
				$totalpackage = $packag_result['gross_revenue_pkg'];
				$discount = $packag_result['discount'];
				$fees = $packag_result['booked_pkg_amt_inc_gst'];
				$cn_invoice = $procedure_result['cn_invoice'];
				
				// Step 4: Update package collection cancellation details
				 $update_sql = "UPDATE hms_package_collections SET 
				`return_credit_note_date` = '$cancelled_date',
				`return_credit_note_amount` = '$booked_pkg_amt_ex_gst',
				`return_credit_note_comment` = 'Cancelled', 
				`return_credit_note_no` = '$cn_invoice',
				`balance_2024_25` = '-$total_collection',
				`balance_2025_26` = '-$total_collection'				
				WHERE `pkg_no` = '$receipt_number'";
				$this->db->query($update_sql);
				
				
				$insert_sql = "INSERT INTO hms_package_collections (`pkg_booking_date`,`pkg_month`,`pkg_booking_year`,`financial_year`,`pkg_no`,`iic_id`,`pkg_code`,`pkg_description`,`category`,`patient_name`,`origin_booking_centre`,`sales_reporting_centre`,`billing_centre`,`gross_revenue_pkg`,`discount`,`booked_pkg_amt_inc_gst`,`gst_in_booked_pkg_amt`,`booked_pkg_amt_ex_gst`,`total_collection`,`total_collection_fy_2025_26`) 
			   VALUES ('$cancelled_date','$pkg_month','$pkg_booking_year','$financial_year',' $receipt_number','$patient_id','$procedure_code','$procedure_name','$category','$wife_name','$origin','$billing_center','$billing_center','-$totalpackage','-$discount','-$fees','0','-$fees','0','-$totalpackage')";
				//$insert_result = run_insert_query($insert_sql);
				$this->db->query($insert_sql);
				
				if ($this->db->query($sql)) {
					return $this->db->insert_id();
				} else {
		return false;
				}
			}

		}
			return 1;
		}

	
	function approve_payment($id) {
		// 1. First update payment status
		 $sql = "UPDATE `".$this->config->item('db_prefix')."patient_payments` SET `status`='1' WHERE ID='".$id."'";
		$this->db->query($sql);
		
		// Get payment details - FIXED: changed to use $receipt_number consistently
		 $patient_sql = "SELECT * FROM hms_patient_payments WHERE ID = '".$id."'";
		$partial_result = run_select_query($patient_sql);
		
		// 2. Get procedure data - FIXED: Changed $ID to $id (parameter name)
		 $sql = "SELECT * FROM hms_patient_procedure WHERE receipt_number = '".$partial_result['billing_id']."'";
		$result = run_select_query($sql);
 
		$inserted_ids = [];
		if (!empty($result)) {
			$row = $result;
			$unserialized_data = unserialize($row['data']);
			
			foreach ($unserialized_data['patient_procedures'] as $procedure) {
				
				$customMonths = [
					1 => 'Apr',
					2 => 'May',
					3 => 'June',
					4 => 'July',
					5 => 'August',
					6 => 'Sep',
					7 => 'Oct',
					8 => 'nov',
					9 => 'dec',
					10 => 'jan',
					11 => 'feb',
					12 => 'mar'
				];

				// Get current standard month number (1-12)
				$standardMonth = date('n'); // e.g., July = 7

				// Convert to your custom number (April=1 to March=12)
				if ($standardMonth >= 4) {
					$customMonthNumber = $standardMonth - 3; // April(4)=1, May(5)=2,...December(12)=9
				} else {
					$customMonthNumber = $standardMonth + 9; // January(1)=10, February(2)=11, March(3)=12
				}
				
				// Prepare common variables
				$formatted_date = date('Y-m-d', strtotime($row['on_date']));
				$pkg_month = date("$customMonthNumber.F.y", strtotime($row['on_date']));
				$pkg_booking_year = date("Y", strtotime($row['on_date']));
				$date = strtotime($row['on_date']);
				$year = date("Y", $date);
				$month = date("n", $date);

				$financial_year = ($month >= 4) ? $year . '-' . ($year + 1) : ($year - 1) . '-' . $year;
				
				$receipt_number = $row['receipt_number'];
				$patient_id = $row['patient_id'];
				$procedure_code = $procedure['sub_procedures_code'];
				
				// Get procedure details
			    $procedure_result = run_select_query("SELECT * FROM hms_procedures WHERE code ='$procedure_code'");
				
				// Get patient appointment details - FIXED: typo in 'patient_id'
			    $appointme_result = run_select_query("SELECT * FROM hms_appointments WHERE paitent_id ='$patient_id'");
				
				// Get center details - FIXED: typo in 'appointment_for'
			   $center_result = run_select_query("SELECT * FROM ".$this->config->item('db_prefix')."centers 
												WHERE center_number='".$row['origins']."'");
				
				// Get billing center - FIXED: using $row instead of undefined $result
			    $billing_center_result = run_select_query("SELECT * FROM ".$this->config->item('db_prefix')."centers 
														 WHERE center_number='".$row['billing_at']."'");
				
				$partial_payment_done = $partial_result['payment_done']; 
				$formattedDate = date('m_d', strtotime($partial_result['on_date']));
				$formattedmonth = date('M', strtotime($partial_result['on_date']));
				
				$proce_formatted = date('m_d', strtotime($result['on_date']));
				$proce_formattedmonth = date('M', strtotime($result['on_date']));
				$procedure_payment_done = $result['payment_done']; 
						
				$procedure_name = $procedure_result['procedure_name'];
				$category = $procedure_result['category'];
				
				$wife_name = $appointme_result['wife_name'];
				$nationality = $appointme_result['nationality'];
				$origin = $center_result['center_name'];
				$billing_center = $billing_center_result['center_name'];
				$totalpackage = $row['totalpackage'];
				$discount = $row['discount_amount'];
				$fees = $row['fees'];
				
				// 3. Build UPDATE query - FIXED: changed $insert_sql to $update_sql
				 $check_sql = "SELECT COUNT(*) as count FROM hms_package_collections WHERE pkg_no = '$receipt_number'";
				$check_result = $this->db->query($check_sql)->row_array();

		if ($check_result['count'] > 0) {
		 $update_sql = "UPDATE hms_package_collections SET `collection_year_$formattedDate` = `collection_year_$formattedDate` + '$partial_payment_done',`collection_$formattedmonth` = `collection_$formattedmonth` + '$partial_payment_done',`total_collection` = `total_collection` + '$partial_payment_done'  WHERE `pkg_no` = '$receipt_number'";
			$this->db->query($update_sql);
		} else {
			 $insert_sql = "INSERT INTO hms_package_collections (`pkg_no`,`pkg_booking_date`,`pkg_month`,`pkg_booking_year`,`financial_year`,`iic_id`,`pkg_code`,`pkg_description`,`category`,
			`patient_name`,`patient_category_2`,`origin_booking_centre`,`sales_reporting_centre`,`billing_centre`,`gross_revenue_pkg`,`discount`,`booked_pkg_amt_inc_gst`,`gst_in_booked_pkg_amt`,`booked_pkg_amt_ex_gst`,`collection_year_$proce_formatted`,`collection_$proce_formattedmonth`,`total_collection`)
			VALUES  ('$receipt_number','$formatted_date','$pkg_month','$pkg_booking_year','$financial_year','$patient_id','$procedure_code','$procedure_name','$category',
			'$wife_name','$nationality','$origin','$billing_center','$billing_center','$totalpackage','$discount','$fees','0','$fees','$procedure_payment_done','$procedure_payment_done','$procedure_payment_done')";
			$this->db->query($insert_sql);
			
			 $update_sql = "UPDATE hms_package_collections SET `collection_year_$formattedDate` = `collection_year_$formattedDate` + '$partial_payment_done',`collection_$formattedmonth` = `collection_$formattedmonth` + '$partial_payment_done',`total_collection` = `total_collection` + '$partial_payment_done'  WHERE `pkg_no` = '$receipt_number'";
		$this->db->query($update_sql);
		
		}
				if ($this->db->affected_rows() > 0) {
					$inserted_ids[] = $this->db->affected_rows();
				}
			}
		}
		
		return $this->db->affected_rows();
}
	
	function disapprove_payment($id, $reason){
		$sql = "";
		$sql = "UPDATE `".$this->config->item('db_prefix')."patient_payments` SET `status`='2', `disapproval_reason`='$reason' WHERE ID='".$id."'";
        $this->db->query($sql);
        return $this->db->affected_rows();
	}

	function cancle_payment($id, $reason){
		$sql = "";
		$sql = "UPDATE `".$this->config->item('db_prefix')."patient_payments` SET `status`='3', `disapproval_reason`='$reason' WHERE ID='".$id."'";
        $this->db->query($sql);
        return $this->db->affected_rows();
	}
		
	function patient_details($patient_id){
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
            return $result;
        }
	}
	
	function get_center_list($patient_id){
		$result = array();
		$sql = "Select billing_at from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_id."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return array_unique($result);
        }
        else
        {
            return $result;
        }
	}
	
	
	function get_doctor_name($doctor){
		
		$result = array();
		$sql = "Select name from ".$this->config->item('db_prefix')."doctors where ID='".$doctor."'";
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
	
	function get_master_investigation_name($investig){
		
		$result = array();
		//echo $sql = "Select investigation_name from ".$this->config->item('db_prefix')."master_investigations where ID='".$investig."'";
		$sql = "SELECT inv.investigation,inv.code AS code,master.code AS master_code,inv.price,inv.center_id,inv.master_id, master.investigation_name FROM hms_investigation AS inv JOIN hms_master_investigations AS master ON inv.master_id = master.id WHERE master.id;";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['investigation_name'];
        }
        else
        {
            return $result;
        }
	}
	
	function get_master_investigation_code($investig){
		
		$result = array();
		//echo $sql = "Select investigation_name from ".$this->config->item('db_prefix')."master_investigations where ID='".$investig."'";
		$sql = "SELECT inv.ID,inv.investigation,inv.code AS code,master.code AS master_code,inv.price,inv.center_id,inv.master_id, master.investigation_name FROM hms_investigation AS inv JOIN hms_master_investigations AS master ON inv.master_id = master.id WHERE inv.ID='".$investig."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['master_code'];
        }
        else
        {
            return $result;
        }
	}
	
	function get_admin_email(){
		$result = array();
		$sql = "Select email from ".$this->config->item('db_prefix')."employees where role='administrator'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['email'];
        }
        else
        {
            return $result;
        }
	}
	
	// SAGAR
	
	function check_patient($patient_id)
	{
		$result = array();
		$sql = "Select ID from ". $this->config->item('db_prefix')."patients WHERE patient_id = '".$patient_id."'";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		if(!empty($result))
		{
			return $result[0];
		}
		else
		{
			return $result;
		}
	}
	
	function insert_settle($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "settlements` SET ";
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
	
	function insert_add_mou($data){
	 $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "mou` SET ";
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
	
	function mou_count($status, $party_name, $start_date, $end_date){
		$procedure_result = array();
		$conditions = '';
		if (!empty($status)){
			$conditions .= " and status='$status'";
		}
		if(!empty($party_name)){
			$conditions .= " and party_name like '%$party_name%'";
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
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."mou where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}
	
	function mou_patination($limit, $page, $status, $party_name, $start_date, $end_date){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
		}
		if(!empty($party_name)){
			$conditions .= " and party_name like '%$party_name%'";
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
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."mou where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
	public function update_mou($data, $ID, $transaction_img)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "mou SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$ID."'";
        $this->db->query($sql);
        return 1;
    }
	
	function check_settle($patient_id)
	{
		$result = array();
		$ivf_sql = "Select sum(payment_done) as payment_done from ". $this->config->item('db_prefix')."settlements WHERE patient_id = '".$patient_id."' AND payment_to='IndiaIVF'";
		$ivf_q = $this->db->query($ivf_sql);
		$ivf_result = $ivf_q->result_array();
		
		$center_sql = "Select sum(payment_done) as payment_done from ". $this->config->item('db_prefix')."settlements WHERE patient_id = '".$patient_id."' AND payment_to!='IndiaIVF'";
		$center_q = $this->db->query($center_sql);
		$center_result = $center_q->result_array();
		
		$ivf_payment = $ivf_result[0]['payment_done'];
		$center_payment = $center_result[0]['payment_done'];
		
		$response = array();
		$response = array('ivf_settle' => round($ivf_payment), 'center_settle' => round($center_payment));
		return $response;
	}
	
	function update_patient_reconcile($receipt_number, $type, $payment_done){
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
		$sql = "UPDATE ".config_item('db_prefix').$condition." SET remaining_amount='0', payment_done=(payment_done - ".$payment_done.")  WHERE receipt_number='".$receipt_number."'";
        $this->db->query($sql);
        return 1;
	}	
	
	function billing_discount($data, $patient_id, $receipt_number, $billing_type){
		$result = array();
		$sql = "Select * from ". $this->config->item('db_prefix')."discount_approval WHERE patient_id = '".$patient_id."' and receipt_number='".$receipt_number."' and type='".$billing_type."'";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		if(!empty($result))
		{
			return 2;
		}else{
			$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "discount_approval` SET ";
			$sqlArr = array();
			foreach( $data as $key=> $value )
			{
				$sqlArr[] = " $key = '".addslashes($value)."'"	;
			}		
			$sql .= implode(',' , $sqlArr);
			$res =  $this->db->query($sql);
			if ($res)
			{
				return 1;
			}
			else
				return 0;
		}
	}	
	
	function discount_request($billing_type, $patient, $status, $receipt, $reason, $disapprove_amount){
		$table = "";
		if($billing_type == "consultation"){
			$table = "consultation";
		}else if($billing_type == "investigation"){
			$table = "patient_investigations";
		}else if($billing_type == "procedure"){
			$table = "patient_procedure";
		}else{
			return 3;	
		}

		$table_sql = "Select * from ". $this->config->item('db_prefix').$table." WHERE patient_id = '".$patient."' AND receipt_number='".$receipt."'";
		$table_q = $this->db->query($table_sql);
		$table_result = $table_q->result_array();
		if(!empty($table_result)){
			$sql = "Select * from ". $this->config->item('db_prefix')."discount_approval WHERE patient_id = '".$patient."' AND receipt_number='".$receipt."' AND status='0'";
			$q = $this->db->query($sql);
			$result = $q->result_array();
			if (!empty($result))
			{
			    $disapprove_amount_condition = "";
				if(!empty($disapprove_amount)){
					$disapprove_amount_condition = ", amount='$disapprove_amount'";
					$status = 2;
				}
				$sql = "UPDATE ". $this->config->item('db_prefix')."discount_approval SET status='".$status."', disapproval_reason='".$reason."' ".$disapprove_amount_condition." WHERE receipt_number='".$receipt."' AND patient_id='".$patient."'";
				$this->db->query($sql);
				return 1;
			}
			else
			{
				return 0;
			}
		}else
		{
			return 2;
		}
	}
	
	function get_billing_discount(){
		$result = array();
		$sql = "Select * from ". $this->config->item('db_prefix')."discount_approval order by ID desc";
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
	
	function get_discount_biller($patient, $receipt){
		$sql = "Select * from ". $this->config->item('db_prefix')."discount_approval WHERE patient_id = '".$patient."' AND receipt_number='".$receipt."'";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		if (!empty($result))
        {
			return $result[0];
        }
        else
        {
            return "";
        }
	}
	
	function check_billing_discount($patient, $receipt_number){
		$result = array();
		$sql = "Select * from ". $this->config->item('db_prefix')."discount_approval WHERE patient_id = '".$patient."' AND receipt_number='".$receipt_number."' AND status='0'";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		if (!empty($result))
        {
			return 1;
        }
        else
        {
            return 0;
        }
	}
	
	function check_discound_applied($receipt){
		$sql = "Select count(*) as discount from ".$this->config->item('db_prefix')."discount_approval where receipt_number='".$receipt."' AND used='0'";
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
	
	function discount_applied_status($receipt){
		$sql = "Select status from ".$this->config->item('db_prefix')."discount_approval where receipt_number='".$receipt."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['status'];
        }
        else
        {
            return $result;
        }
	}
	
	

	function investigation_reports($patient_id){
		$result = array();
		
		$sql = "Select * from ".$this->config->item('db_prefix')."patient_investigation_reports where patient_id='$patient_id' and doctor_accepted='approved'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if(!empty($result)){
			return $result;
		}else{
			return $result;
		}
	}

	function get_partial_payment_receipt($payment_id){
		$result = array();		
		$sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where refrence_number='$payment_id'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if(!empty($result)){
			return $result[0];
		}else{
			return $result;
		}
	}

	function procedure_reports($patient_id){
		$result = array();
		
		$sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where patient_id='$patient_id' order by ID desc limit 1";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if(!empty($result)){
			return $result[0];
		}else{
			return $result;
		}
	}
	
	function get_discharge_form_data() {
        $this->db->from('hms_discharge_forms');
        // Remove the status filter to show both active and inactive forms
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
        return array(); // Return empty array if no forms found
	}
	
	function get_discharge_form_data_embrology() {
        $this->db->from('hms_discharge_forms');
        // Remove the status filter to show both active and inactive forms
		$this->db->where('role', 'embryologist');
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
        return array(); // Return empty array if no forms found
	}
	
	function get_discharge_data() {
        $this->db->from('hms_discharge_forms');
        // Remove the status filter to show both active and inactive forms
		$this->db->where('role', 'nurse');
        $sel = $this->db->get();
        $q = $sel->result_array();
        if ($q) {
            return $q;
        }
        return array(); // Return empty array if no forms found
	}
	
	function patient_discharge($patient_id){
	    $this->db->from('hms_discharge_forms');
        // Remove the status filter to show both active and inactive forms
        $sel = $this->db->get();
        $res = $sel->result_array();
        
        $res_arr = array();
	    foreach($res as $rs){
	        $sql = "SELECT id, iic_id, updated_by, updated_at, appoitmented_date, updated_type FROM `".$rs['db_name']."` WHERE iic_id='".$patient_id."'";
            $q = $this->db->query($sql);
            $result = $q->result_array();
            if(!empty($result)){
                foreach($result as $vl){
                    $vl['form_name'] = $rs['form_name'];
                    $vl['form_id'] = $rs['id'];
                    $res_arr[] = $vl;
                }
            }
	    }
	    
	    return $res_arr;
	}

	function patient_procedure_count($center,$status, $start_date, $end_date, $patient_id, $payment_method, $biller_id){
		$procedure_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
		}
		if (!empty($biller_id)){
			$conditions .= " and biller_id='$biller_id'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if(!empty($sub_procedures_code)){
			$conditions .= ' and sub_procedures_code="'.$sub_procedures_code.'"';
        }
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}

	function procedure_reports_count($center, $start_date, $end_date, $patient_id, $payment_method, $biller_id){
		$procedure_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($biller_id)){
			$conditions .= " and biller_id='$biller_id'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if(!empty($sub_procedures_code)){
			$conditions .= ' and sub_procedures_code="'.$sub_procedures_code.'"';
        }
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}

	function procedure_billings_count($center, $start_date, $end_date, $patient_id, $payment_method){
		$procedure_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}

	

	function freezing_reports_count($origins, $start_date, $end_date, $patient_id, $payment_method, $biller_id){
		$procedure_result = array();
		$conditions = '';
		if (!empty($origins)){
			$conditions .= " and origins='$origins'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();	
	}
	function freezing_reports_list_patination($limit, $page, $origins, $start_date, $end_date, $patient_id, $payment_method, $biller_id){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($origins)){
			$conditions .= " and origins='$origins'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1 and status='approved'".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}

	function procedure_reports_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $payment_method, $biller_id){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($biller_id)){
			$conditions .= " and biller_id='$biller_id'";
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}

	function patient_procedure_list_patination($limit, $page, $center,$status, $start_date, $end_date, $patient_id, $payment_method, $biller_id){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($biller_id)){
			$conditions .= " and biller_id='$biller_id'";
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}

	function export_procedure_data($start, $end, $center, $type, $payment_method, $biller_id){

		$procedure_result = $response = array();
        $conditions = '';
		if(!empty($biller_id)){
			$conditions .= ' and biller_id="'.$biller_id.'"';
        }
		if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
		
	     $procedure_sql = "Select DISTINCT patient_id, receipt_number, totalpackage, fees as discounted_package,payment_done,remaining_amount,
		payment_method,billing_from,billing_at,biller_id,data,on_date,status,hospital_id from ".$this->config->item('db_prefix')."patient_procedure where 1
		$conditions order by on_date desc";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
        if(!empty($procedure_result)){
            foreach($procedure_result as $key => $val){
				$hms_procedures_result = unserialize( $val['data']);
				$procedure_nameArr = [];
				foreach ($hms_procedures_result as $v2_data123){
						foreach ($v2_data123 as $v2_data5){

						    $sql12 = "select procedure_name from hms_procedures where code='".$v2_data5['sub_procedures_code']."'"; 
                            $query12 = $this->db->query($sql12);
                            $select_result1 = $query12->result(); 
							
							foreach ($select_result1 as $res_val){
								// echo $res_val->procedure_name;
								 array_push($procedure_nameArr,$res_val->procedure_name);
							}
						}
						
				}
					 
				$procedure_name1 = implode(',',$procedure_nameArr); 
				
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
                $response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name1,
						'receipt_number' => $val['receipt_number'],
				        'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
						'procedure_name' => $procedure_name1,
						'billing_type' => 'Procedure',
						'on_date' => $val['on_date'],
                        'status' => $val['status'],
						'hospital_id' => $val['hospital_id'],
                );
            }
        }    
		return $response;
    }
	
	
	function export_procedure_center_data($start_date, $end_date, $center, $patient_id){

		$procedure_result = $response = array();
        $conditions = '';
		if(!empty($biller_id)){
			$conditions .= ' and biller_id="'.$biller_id.'"';
        }
		if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
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
		
	    $procedure_sql = "Select * FROM ".$this->config->item('db_prefix')."patient_procedure where 1
		$conditions order by on_date desc";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
        if(!empty($procedure_result)){
            foreach($procedure_result as $key => $val){
				$hms_procedures_result = unserialize( $val['data']);
				$procedure_nameArr = [];
				foreach ($hms_procedures_result as $v2_data123){
						foreach ($v2_data123 as $v2_data5){

						    $sql12 = "select procedure_name from hms_procedures where code='".$v2_data5['sub_procedures_code']."'"; 
                            $query12 = $this->db->query($sql12);
                            $select_result1 = $query12->result(); 
							
							foreach ($select_result1 as $res_val){
								// echo $res_val->procedure_name;
								 array_push($procedure_nameArr,$res_val->procedure_name);
							}
						}
						
				}
					 
				$procedure_name1 = implode(',',$procedure_nameArr); 
				
				$patient_name = $this->get_patient_name($val['patient_id']);
				
				//print_r($v2_data5['sub_procedures_code']);die();
				
				if (is_array($patient_name)) {
    // Log or handle the unexpected input
    $patient_name1 = strtoupper(implode(' ', $patient_name));
} else {
    $patient_name1 = strtoupper($patient_name);
}

                $response[] = array(
                        'patient_id' => $val['patient_id'],
						'appointment_id' => $val['appointment_id'],
                        'wife_name' => $patient_name1,
						'receipt_number' => $val['receipt_number'],
				        'totalpackage' => $val['totalpackage'],
                        'discount_amount' => $val['discount_amount'],
                        'payment_done' => $val['fees'],
						'total_receive' => $val['total_receive'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
						'on_date' => $val['on_date'],
                        'status' => $val['status'],
						'hospital_id' => $val['hospital_id'],
						'procedure_name' => $procedure_nameArr,
			    );
            }
        }    
		return $response;
    }
	
	/*******Psychological*******/
	
		function patient_psychological_count($center, $start_date, $end_date, $patient_id, $payment_method){
		$procedure_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($payment_method)){
			$conditions .= " and payment_method='$payment_method'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}

	function patient_psychological_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $payment_method){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($payment_method)){
			$conditions .= " and payment_method='$payment_method'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}

	function export_psychological_data($start, $end, $center, $type, $payment_method){

		$procedure_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($payment_method)){
			$conditions .= " and payment_method='$payment_method'";
		}
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
		
	     $procedure_sql = "Select DISTINCT patient_id, receipt_number, totalpackage, fees as discounted_package,payment_done,remaining_amount,
		payment_method,billing_from,billing_at,data,on_date as date,status from ".$this->config->item('db_prefix')."patient_procedure where 1
		$conditions order by on_date desc";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
        if(!empty($procedure_result)){
            foreach($procedure_result as $key => $val){
				$hms_procedures_result = unserialize( $val['data']);
				$procedure_nameArr = [];
				foreach ($hms_procedures_result as $v2_data123){
						foreach ($v2_data123 as $v2_data5){

						    $sql12 = "select procedure_name from hms_procedures where code='".$v2_data5['sub_procedures_code']."'"; 
                            $query12 = $this->db->query($sql12);
                            $select_result1 = $query12->result(); 
							
							foreach ($select_result1 as $res_val){
								// echo $res_val->procedure_name;
								 array_push($procedure_nameArr,$res_val->procedure_name);
							}
						}
						
				}
					 
				$procedure_name1 = implode(',',$procedure_nameArr); 
				
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
                $response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name1,
						'receipt_number' => $val['receipt_number'],
				        'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
						'billing_at' => $val['billing_at'],
                        'procedure_name' => $procedure_name1,
                        'status' => $val['status'],
                        'billing_type' => 'Procedure',
                );
            }
        }    
		return $response;
    }

	
	/************Investigation***********/
	
	function patient_investigation_count($center, $status, $start_date, $end_date, $patient_id, $payment_method){
		$investigation_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}

	function patient_investigation_list_patination($limit, $page, $center, $status, $start_date, $end_date, $patient_id, $payment_method){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
function export_investigation_data($start, $status, $end, $center, $type, $payment_method){

		$investigation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($status)){
			$conditions .= ' and status="'.$status.'"';
        }
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
		
	    $investigation_sql = "Select DISTINCT patient_id, receipt_number, totalpackage, fees as discounted_package,payment_done,remaining_amount,investigations,payment_method,billing_from,billing_at,on_date as date,origins,status from ".$this->config->item('db_prefix')."patient_investigations where 1 $conditions order by on_date desc";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				
				$hms_procedures_result = unserialize( $val['investigations']);
				$investigation_nameArr = [];
				foreach ($hms_procedures_result as $v2_data123){
						foreach ($v2_data123 as $v2_data5){

						    $sql12 = "select investigation from hms_investigation where code='".$v2_data5['female_investigation_code']."'"; 
                            $query12 = $this->db->query($sql12);
                            $select_result1 = $query12->result(); 
							
							//print_r($select_result1);
							//echo select_result1['investigation'];
							
							foreach ($select_result1 as $res_val){
								// echo $res_val->investigation;
								 array_push($investigation_nameArr,$res_val->investigation);
							}
						}
						
				}
				//die;
					 
				$investigation_name1 = implode(',',$investigation_nameArr); 
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
                $response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name1,
						'receipt_number' => $val['receipt_number'],
						 'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
						'investigation' => $investigation_name1,
                        'date' => $val['date'],
                        'status' => $val['status'],
						'origins' => $val['origins'],
                        'billing_type' => 'Investigation',
						
                );
            }
        }    
		return $response;
    }
	
	function export_investigation_medicine($start_date, $end_date, $center, $patient_id, $origins){
		$female_investigation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($origins)){
			$conditions .= ' and origins="'.$origins.'"';
        }
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$investigation_sql = "Select DISTINCT patient_id, on_date, investigations, receipt_number from ".$this->config->item('db_prefix')."patient_investigations where status='approved' and 1 $conditions";
        $investigation_q = $this->db->query($investigation_sql);
        $female_investigation_result = $investigation_q->result_array();
        if(!empty($female_investigation_result)){
            foreach($female_investigation_result as $key => $val){
				$data = unserialize($val['investigations']);
				$investigation_name_arr = [];
				$investigation_code_arr = [];
				$investigation_price_arr = [];
				$investigation_discount_arr = [];
				foreach($data as $value2){
				    foreach($value2 as $value4){			
						if($value4['female_investigation_name']){
								array_push($investigation_name_arr,$value4['female_investigation_name']);
								array_push($investigation_code_arr,$value4['female_investigation_code']);
							    array_push($investigation_price_arr,$value4['female_investigation_price']);
							    array_push($investigation_discount_arr,$value4['female_investigation_discount']);
						}
					}
				}
				
				$female_investigation_name = implode(',', $investigation_name_arr );
				$female_investigation_code = implode(',', $investigation_code_arr );
				$female_investigation_price = implode(',', $investigation_price_arr );
				$female_investigation_discount = implode(',', $investigation_discount_arr );
				foreach($investigation_name_arr as $_key => $_value){
					$response[] = array(
				        'on_date' => $val['on_date'],
                        'patient_id' => $val['patient_id'],
				        'female_investigation_name'=> $investigation_name_arr[$_key],
						'female_investigation_code' => $investigation_code_arr[$_key],
						'female_investigation_price' => $investigation_price_arr[$_key],
						'female_investigation_discount' => $investigation_discount_arr[$_key],
						'total' => $final_investigation_price_arr[$_key]
			    );
				}
			}
		}      
		return $response;
    } 
	
	function export_male_investigation($start_date, $end_date, $center, $patient_id, $origins){
		$male_investigation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($origins)){
			$conditions .= ' and origins="'.$origins.'"';
        }
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$male_investigation_sql = "Select DISTINCT patient_id, on_date, investigations, receipt_number from ".$this->config->item('db_prefix')."patient_investigations where status='approved' and 1 $conditions";
        $male_investigation_q = $this->db->query($male_investigation_sql);
        $male_investigation_result = $male_investigation_q->result_array();
        if(!empty($male_investigation_result)){
            foreach($male_investigation_result as $key => $val){
				$_data = unserialize($val['investigations']);
				$investigation_name_arr = [];
				$investigation_code_arr = [];
				$investigation_price_arr = [];
				$investigation_discount_arr = [];
				foreach($_data as $value2){
				    foreach($value2 as $value4){			
						if($value4['male_investigation_name']){
								array_push($investigation_name_arr,$value4['male_investigation_name']);
								array_push($investigation_code_arr,$value4['male_investigation_code']);
							    array_push($investigation_price_arr,$value4['male_investigation_price']);
							    array_push($investigation_discount_arr,$value4['male_investigation_discount']);
						}
					}
				}
				
				$male_investigation_name = implode(',', $investigation_name_arr );
				$male_investigation_code = implode(',', $investigation_code_arr );
				$male_investigation_price = implode(',', $investigation_price_arr );
				$male_investigation_discount = implode(',', $investigation_discount_arr );
				foreach($investigation_name_arr as $_key => $_value){
					$response[] = array(
				        'on_date' => $val['on_date'],
                        'patient_id' => $val['patient_id'],
				        'male_investigation_name'=> $investigation_name_arr[$_key],
						'male_investigation_code' => $investigation_code_arr[$_key],
						'male_investigation_price' => $investigation_price_arr[$_key],
						'male_investigation_discount' => $investigation_discount_arr[$_key],
						'total' => $final_investigation_price_arr[$_key]
			    );
				}
			}
		}      
		return $response;
    } 
	
	/** Start Consultation **/

function export_consultation_patients_data($start_date,$status, $end_date, $center, $patient_id){
        $consultation_result = $response = array();
        $conditions = '';
        if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
            $center = $_SESSION['logged_accountant']['center'];
        }
        if(!empty($center)){
            $conditions .= ' and billing_at="'.$center.'"';
        }
         if(!empty($patient_id)){
            $conditions .= ' and patient_id="'.$patient_id.'"';
        }
         if(!empty($reason_of_visit)){
            $conditions .= ' and reason_of_visit="'.$reason_of_visit.'"';
        }
        if (!empty($start_date) && !empty($end_date)){
        $conditions .= " AND on_date BETWEEN '".$start_date."' AND '".$end_date."'";
        }
        else if (!empty($start_date) && empty($end_date)){
            $conditions .= " AND on_date='$start_date'";
        }
        else if (empty($start_date) && !empty($end_date)){
            $conditions .= " AND on_date='$end_date'";
        }
        
       $consultation_sql = "Select DISTINCT patient_id, receipt_number, totalpackage,doctor_id, fees as discounted_package,payment_done,remaining_amount,payment_method,billing_from,billing_at,reason_of_visit,on_date as date,status from ".$this->config->item('db_prefix')."consultation where 1 $conditions order by on_date desc";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
        if(!empty($consultation_result)){
            foreach($consultation_result as $key => $val){
                $patient_name = $this->get_patient_name($val['patient_id']);
                //$patient_name1 = strtoupper($patient_name);
                $response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name,
                        'receipt_number' => $val['receipt_number'],
                        'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
                        'reason_of_visit' => $val['reason_of_visit'],
                        'doctor_id' => $val['doctor_id'],
                        'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Consultation',
                );
            }
        }    
        return $response;
    }

	function export_consultation_origin_data($start_date, $end_date, $center, $patient_id, $reason_of_visit){
        $consultation_result = $response = array();
        $conditions = '';
        if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
            $center = $_SESSION['logged_accountant']['center'];
        }
        if(!empty($center)){
            $conditions .= ' and billing_at="'.$center.'"';
        }
         if(!empty($patient_id)){
            $conditions .= ' and patient_id="'.$patient_id.'"';
        }
         if(!empty($reason_of_visit)){
            $conditions .= ' and reason_of_visit="'.$reason_of_visit.'"';
        }
        if (!empty($start_date) && !empty($end_date)){
        $conditions .= " AND on_date BETWEEN '".$start_date."' AND '".$end_date."'";
        }
        else if (!empty($start_date) && empty($end_date)){
            $conditions .= " AND on_date='$start_date'";
        }
        else if (empty($start_date) && !empty($end_date)){
            $conditions .= " AND on_date='$end_date'";
        }
        
       $consultation_sql = "Select DISTINCT patient_id, receipt_number, totalpackage,doctor_id, fees as discounted_package,payment_done,remaining_amount,payment_method,billing_from,billing_at,reason_of_visit,on_date as date,status from ".$this->config->item('db_prefix')."consultation where 1 $conditions order by on_date desc";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
        if(!empty($consultation_result)){
            foreach($consultation_result as $key => $val){
                $patient_name = $this->get_patient_name($val['patient_id']);
                //$patient_name1 = strtoupper($patient_name);
                $response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name,
                        'receipt_number' => $val['receipt_number'],
                        'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
                        'reason_of_visit' => $val['reason_of_visit'],
                        'doctor_id' => $val['doctor_id'],
                        'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Consultation',
                );
            }
        }    
        return $response;
    }
	
	// Count function for pagination
function patient_consultation_report_count($center, $start_date, $end_date, $patient_id, $reason_of_visit, $doctor_id,$category,$agent, $lead_source = ''){
    
    $this->db->distinct();
    $this->db->select('T1.patient_id, T1.totalpackage, T1.payment_done, T1.discount_amount,	T1.appointment_id');
    $this->db->from('hms_consultation T1');
    $this->db->join('hms_appointments T2', 'T1.patient_id = T2.paitent_id', 'inner');
    
    // Add conditions
    $this->db->where('T2.billed', '1');
	
    if (!empty($center)){
        $this->db->where('T1.billing_at', $center);
    }
    if (!empty($patient_id)){
        $this->db->where('T1.patient_id', $patient_id);
    }
    if (!empty($lead_source)){
        if (strpos($lead_source, "','") !== false) {
            $lead_sources = explode("','", $lead_source);
            $this->db->where_in('T2.lead_source', $lead_sources);
        } else {
            $this->db->where('T2.lead_source', $lead_source);
        }
    }
    if (!empty($doctor_id)){
        $this->db->where('T1.doctor_id', $doctor_id);
    }
    if (!empty($reason_of_visit)){
        $this->db->where('T1.reason_of_visit', $reason_of_visit);
    }
	if (!empty($agent)){
        $this->db->where('T2.agent', $agent);
    }
    if (!empty($start_date) && !empty($end_date)){
        $this->db->where('T1.on_date >=', $start_date);
        $this->db->where('T1.on_date <=', $end_date);
        $this->db->where('T2.appoitmented_date >=', $start_date);
        $this->db->where('T2.appoitmented_date <=', $end_date);
    }
    else if (!empty($start_date) && empty($end_date)){
        $this->db->where('T1.on_date', $start_date);
        $this->db->where('T2.appoitmented_date', $start_date);
    }
    else if (empty($start_date) && !empty($end_date)){
        $this->db->where('T1.on_date', $end_date);
        $this->db->where('T2.appoitmented_date', $end_date);
    }
    
    return $this->db->count_all_results();
}	

// Count function for consultation patients pagination
function patient_consultation_count($center, $status, $start_date, $end_date, $patient_id, $doctor_id = null){
    
    $this->db->distinct();
    $this->db->select('T1.patient_id');
    $this->db->from('hms_consultation T1');
    $this->db->join('hms_appointments T2', 'T1.patient_id = T2.paitent_id', 'inner');
    
    // Add conditions
    $this->db->where('T2.billed', '1');
	
    if (!empty($center)){
        $this->db->where('T1.billing_at', $center);
    }
    if (!empty($patient_id)){
        $this->db->where('T1.patient_id', $patient_id);
    }
    if (!empty($doctor_id)){
        $this->db->where('T1.doctor_id', $doctor_id);
    }
    if (!empty($start_date) && !empty($end_date)){
        $this->db->where('T1.on_date >=', $start_date);
        $this->db->where('T1.on_date <=', $end_date);
        $this->db->where('T2.appoitmented_date >=', $start_date);
        $this->db->where('T2.appoitmented_date <=', $end_date);
    }
    else if (!empty($start_date) && empty($end_date)){
        $this->db->where('T1.on_date', $start_date);
        $this->db->where('T2.appoitmented_date', $start_date);
    }
    else if (empty($start_date) && !empty($end_date)){
        $this->db->where('T1.on_date', $end_date);
        $this->db->where('T2.appoitmented_date', $end_date);
    }
    
    return $this->db->count_all_results();
}

function patient_consultation_report_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $reason_of_visit, $doctor_id, $agent, $councellor, $category, $procedures, $broad_procedure, $booked_status, $lead_source = '') {
    // This array will hold the values for secure query binding
    $bindings = [];

    // Calculate offset for pagination
    $offset = empty($page) || $page <= 1 ? 0 : ($page - 1) * $limit;

    // --- Build Conditions Securely using '?' Placeholders ---
    $conditions = '';

    if (!empty($center)) {
        $conditions .= " AND T1.billing_at = ?";
        $bindings[] = $center;
    }
    if (!empty($patient_id)) {
        $conditions .= " AND T1.patient_id = ?";
        $bindings[] = $patient_id;
    }
    if (!empty($doctor_id)) {
        $conditions .= " AND T1.doctor_id = ?";
        $bindings[] = $doctor_id;
    }
    if (!empty($reason_of_visit)) {
        $conditions .= " AND T1.reason_of_visit = ?";
        $bindings[] = $reason_of_visit;
    }
    if (!empty($agent)) {
        $conditions .= " AND T2.agent = ?";
        $bindings[] = $agent;
    }
    
    if (!empty($councellor)) {
        $conditions .= " AND T2.councellor = ?"; 
        $bindings[] = $councellor;
    }

    // Handle optional lead source filtering
    if (!empty($lead_source)) {
        if (strpos($lead_source, "','") !== false) {
            $conditions .= " AND T2.lead_source IN (?)";
        } else {
            $conditions .= " AND T2.lead_source = ?";
        }
        $bindings[] = $lead_source;
    }

    // Secure Date Filtering (with typo correction)
    if (!empty($start_date) && !empty($end_date)) {
        $conditions .= " AND T1.on_date BETWEEN ? AND ?";
        // ** TYPO FIX **
        $conditions .= " AND T2.appoitmented_date BETWEEN ? AND ?"; 
        array_push($bindings, $start_date, $end_date, $start_date, $end_date);
    } else if (!empty($start_date)) {
        $conditions .= " AND T1.on_date = ?";
        $conditions .= " AND T2.appoitmented_date = ?"; // Typo fixed
        array_push($bindings, $start_date, $start_date);
    } else if (!empty($end_date)) {
        $conditions .= " AND T1.on_date = ?";
        $conditions .= " AND T2.appoitmented_date = ?"; // Typo fixed
        array_push($bindings, $end_date, $end_date);
    }
	if (!empty($category)) {
        $conditions .= " AND T3.category = ?";
        $bindings[] = $category;
    }
    if (!empty($procedures)) {
        $conditions .= " AND T3.procedures = ?";
        $bindings[] = $procedures;
    }
	if (!empty($broad_procedure)) {
        $conditions .= " AND T3.broad_procedure = ?";
        $bindings[] = $broad_procedure;
    }
	if (!empty($booked_status)) {
        $conditions .= " AND T3.booked_status = ?";
        $bindings[] = $booked_status;
    }

   $consultation_sql = "
    SELECT
        T1.patient_id,
        MAX(T2.agent) AS agent,            -- Use MAX() to pick one value if multiple exist
        MAX(T2.councellor) AS councellor,  -- Use MAX() here as well
        MAX(T2.crm_id) AS crm_id,          -- And here
        MAX(T2.lead_source) AS lead_source,
        T1.on_date,
        T1.reason_of_visit,
        MAX(T1.totalpackage) AS totalpackage, -- Aggregating potentially varying fields is safer
        MAX(T1.payment_done) AS payment_done,
        MAX(T1.discount_amount) AS discount_amount,
        MAX(T1.appointment_id) AS appointment_id,
        MAX(T1.doctor_id) AS doctor_id,
        T1.billing_at,
        MAX(T3.category) as category,  -- FIX: Use MAX() to merge duplicates from T3
		MAX(T3.procedures) as procedures,
		MAX(T3.broad_procedure) as broad_procedure,
		MAX(T3.booked_status) as booked_status
    FROM
        hms_consultation AS T1
    INNER JOIN
        hms_appointments AS T2 ON T1.patient_id = T2.paitent_id 
    INNER JOIN
        hms_patient_procedure AS T3 ON T1.patient_id = T3.patient_id 
    WHERE
        T2.billed = '1'
        AND T2.lead_source != 'D/S' 
        {$conditions}
    GROUP BY
        T1.patient_id, 
        T1.on_date, 
        T1.reason_of_visit, 
        T1.billing_at
    ORDER BY T1.on_date DESC, T1.id DESC
    LIMIT ?, ?"; 

    // Add offset and limit to the bindings array
    $bindings[] = (int) $offset;
    $bindings[] = (int) $limit;

    // --- Execute the Query Securely ---
    $consultation_q = $this->db->query($consultation_sql, $bindings);
    return $consultation_q->result_array();
}


/*
public function patient_consultation_report_patination($center, $start_date, $end_date, $patient_id, $reason_of_visit, $category, $procedures, $broad_procedure, $agent, $councellor, $booked_status, $lead_source = '') 
{
    // This array will hold the values for secure query binding
    $bindings = [];
    $conditions = '';

    // --- 1. Build Conditions for T1 (Consultation) ---
    
    if (!empty($center)) {
        $conditions .= " AND T1.billing_at = ?";
        $bindings[] = $center;
    }
    if (!empty($patient_id)) {
        $conditions .= " AND T1.patient_id = ?";
        $bindings[] = $patient_id;
    }
    if (!empty($reason_of_visit)) {
        $conditions .= " AND T1.reason_of_visit = ?";
        $bindings[] = $reason_of_visit;
    }
    // Note: doctor_id is usually in T1 (consultation) or T2 (appointments). 
    // Assuming T1 here based on your report function.
    if (!empty($doctor_id)) { 
        $conditions .= " AND T1.doctor_id = ?";
        $bindings[] = $doctor_id;
    }

    // --- 2. Build Conditions for T2 (Appointments) ---
    
    if (!empty($agent)) {
        $conditions .= " AND T2.agent = ?";
        $bindings[] = $agent;
    }
    
    if (!empty($councellor)) {
        $conditions .= " AND T2.councellor = ?";
        $bindings[] = $councellor;
    }

    if (!empty($lead_source)) {
        if (strpos($lead_source, "','") !== false) {
            $conditions .= " AND T2.lead_source IN (?)"; // Consider expanding if array
        } else {
            $conditions .= " AND T2.lead_source = ?";
        }
        $bindings[] = $lead_source;
    }

    // --- 3. Secure Date Filtering ---
    if (!empty($start_date) && !empty($end_date)) {
        $conditions .= " AND T1.on_date BETWEEN ? AND ?";
        // IMPORTANT: Your report used 'T2.appoitmented_date' (with typo)
        // I am fixing the typo to 'appointment_date'. Check your DB column name!
        $conditions .= " AND T2.appointment_date BETWEEN ? AND ?"; 
        
        array_push($bindings, $start_date, $end_date, $start_date, $end_date);
    } else if (!empty($start_date)) {
        $conditions .= " AND T1.on_date = ?";
        $conditions .= " AND T2.appointment_date = ?"; 
        array_push($bindings, $start_date, $start_date);
    } else if (!empty($end_date)) {
        $conditions .= " AND T1.on_date = ?";
        $conditions .= " AND T2.appointment_date = ?";
        array_push($bindings, $end_date, $end_date);
    }

    // --- 4. Build Conditions for T3 (Procedures) ---
    // Note: We define T3 in the join logic below
    
    if (!empty($category)) {
        $conditions .= " AND T3.category = ?";
        $bindings[] = $category;
    }
    if (!empty($procedures)) {
        // Assuming 'procedures' is the column name in T3
        $conditions .= " AND T3.procedures = ?"; 
        $bindings[] = $procedures;
    }
    if (!empty($broad_procedure)) {
        $conditions .= " AND T3.broad_procedure = ?";
        $bindings[] = $broad_procedure;
    }
    
    // --- 5. Determine JOIN Logic based on 'booked_status' ---
    // Default is LEFT JOIN (include everyone)
    $join_type_t3 = "LEFT JOIN";
    $booked_logic = "";

    if ($booked_status == 'Booked') {
        // If we want ONLY booked patients, use INNER JOIN
        $join_type_t3 = "INNER JOIN";
    } elseif ($booked_status == 'Not Booked') {
        // If we want ONLY non-booked, use LEFT JOIN ... WHERE id IS NULL
        $join_type_t3 = "LEFT JOIN";
        $booked_logic = " AND T3.patient_id IS NULL";
    } elseif (!empty($category) || !empty($procedures) || !empty($broad_procedure)) {
        // Implicitly, if you filter by a specific procedure, you only want booked patients
        $join_type_t3 = "INNER JOIN";
    }


    // --- 6. The Final COUNT Query ---
    // We count DISTINCT patient_ids to get the number of unique patients
    
    // *** 'echo' REMOVED ***
    $sql = "
        SELECT COUNT(DISTINCT T1.patient_id) AS unique_patient_count
        FROM hms_consultation AS T1
        INNER JOIN hms_appointments AS T2 
            ON T1.patient_id = T2.paitent_id 
            -- (If your DB column is 'paitent_id', change T2.paitent_id to T2.paitent_id above)
        
        $join_type_t3 hms_patient_procedure AS T3 
            ON T1.patient_id = T3.patient_id
            
        WHERE
            T2.billed = '1'
            AND T2.lead_source != 'D/S'
            {$conditions}
            {$booked_logic}
    ";

    // Execute the query securely
    $q = $this->db->query($sql, $bindings);
    
    // Return the result array (e.g., ['unique_patient_count' => 10])
    return $q->row_array();
}*/

function patient_consultation_count_by_reason($center, $start_date, $end_date, $patient_id, $reason_of_visit, $doctor_id, $lead_source){
    $conditions = '';
    if (!empty($center)){
        $conditions .= " AND billing_at='$center'";
    }
    if (!empty($patient_id)){
        $conditions .= " AND patient_id='$patient_id'";
    }
	if (!empty($reason_of_visit)){
			$conditions .= " and reason_of_visit='$reason_of_visit'";
	}
    if (!empty($start_date) && !empty($end_date)){
        $conditions .= " AND on_date BETWEEN '".$start_date."' AND '".$end_date."'";
    }
    else if (!empty($start_date) && empty($end_date)){
        $conditions .= " AND on_date='$start_date'";
    }
    else if (empty($start_date) && !empty($end_date)){
        $conditions .= " AND on_date='$end_date'";
    }

   $consultation_sql = "SELECT COUNT(DISTINCT T1.paitent_id) AS unique_first_patient_count
    FROM hms_appointments AS T1
    INNER JOIN (
        SELECT patient_id
        FROM hms_consultation
        WHERE 1 ".$conditions." ORDER BY on_date DESC
    ) AS T2 ON T1.paitent_id = T2.patient_id";

    $q = $this->db->query($consultation_sql);
    return $q->row_array(); // returns array with both counts
}

/**
 * Gets the count of unique patients who had a specific procedure
 * based on consultation filters.
 *
 * This function is secure (uses query bindings) and fast (uses a JOIN).
 */
public function patient_procedure_consultation_count($center, $start_date, $end_date, $patient_id, $reason_of_visit, $category, $procedures, $broad_procedure, $agent,$councellor,$booked_status)
{
    // This array will hold the values for secure query binding
    $bindings = [];
    $conditions = '';

    // --- Build Conditions Securely (with '?' placeholders) ---
    // Note: We use T1. to specify the hms_consultation table
    
    if (!empty($center)) {
        $conditions .= " AND T1.billing_at = ?";
        $bindings[] = $center;
    }
    if (!empty($patient_id)) {
        $conditions .= " AND T1.patient_id = ?";
        $bindings[] = $patient_id;
    }
    if (!empty($reason_of_visit)) {
        $conditions .= " AND T1.reason_of_visit = ?";
        $bindings[] = $reason_of_visit;
    }
    if (!empty($category)) {
        $conditions .= " AND T2.category = ?";
        $bindings[] = $category;
    }
    if (!empty($procedures)) {
        $conditions .= " AND T2.procedures = ?";
        $bindings[] = $procedures;
    }
	if (!empty($broad_procedure)) {
        $conditions .= " AND T2.broad_procedure = ?";
        $bindings[] = $broad_procedure;
    }
	if (!empty($agent)) {
        $conditions .= " AND T2.agent = ?";
        $bindings[] = $agent;
    }
	if (!empty($councellor)) {
        $conditions .= " AND T2.councellor = ?";
        $bindings[] = $councellor;
    }
	if (!empty($booked_status)) {
        $conditions .= " AND T2.booked_status = ?";
        $bindings[] = $booked_status;
    }
    // Secure Date Filtering
    if (!empty($start_date) && !empty($end_date)) {
        $conditions .= " AND T1.on_date BETWEEN ? AND ?";
        $bindings[] = $start_date;
        $bindings[] = $end_date;
    } else if (!empty($start_date)) {
        $conditions .= " AND T1.on_date = ?";
        $bindings[] = $start_date;
    } else if (!empty($end_date)) {
        $conditions .= " AND T1.on_date = ?";
        $bindings[] = $end_date;
    }

    // --- The Correct, Working Query ---
    // Your commented-out query had a syntax error (WHERE T2.category).
    // Your new query (WHERE 1) is the correct pattern.
    
    // *** 'echo' has been REMOVED from the line below ***
     $sql = "SELECT COUNT(DISTINCT T1.patient_id) AS unique_patient_count
FROM hms_consultation AS T1
INNER JOIN hms_patient_procedure AS T2
    ON T1.patient_id = T2.patient_id
INNER JOIN hms_appointments AS T3
    ON T1.patient_id = T3.paitent_id  -- Assuming 'patient_id' is the column name in appointments table too
WHERE
    1
    {$conditions}
    ";

    // Execute the query securely
    $q = $this->db->query($sql, $bindings);
    
    // Return the result array (e.g., ['unique_patient_count' => 10])
    return $q->row_array();
}

public function get_lead_source_dropdown_data() {
    $this->db->select("mapped_bucket, GROUP_CONCAT(original_lead_source) as sources");
    $this->db->from('hms_lead_source_mapping');
    $this->db->where('original_lead_source IS NOT NULL');
    $this->db->where("original_lead_source != ''");
    $this->db->group_by('mapped_bucket');
    $this->db->order_by('mapped_bucket');
    
    $query = $this->db->get(); // Get the query object
    
    // Check if query was successful
    if (!$query) {
        return array();
    }
    
    $result = $query->result_array(); // Convert to array
    
    $dropdown_data = array();
    foreach($result as $row) {
        $sources_array = explode(',', $row['sources']);
        $cleaned_sources = array();
        foreach($sources_array as $source) {
            $trimmed_source = trim($source);
            $escaped_source = str_replace("'", "\\'", $trimmed_source);
            if (!empty($trimmed_source)) {
                $cleaned_sources[] = $escaped_source;
            }
        }
        $dropdown_data[$row['mapped_bucket']] = implode("','", $cleaned_sources);
    }
    
    return $dropdown_data;
}


function get_available_lead_sources_for_consultations(){
    $sql = "SELECT DISTINCT a.lead_source
            FROM ".$this->config->item('db_prefix')."appointments a
            INNER JOIN ".$this->config->item('db_prefix')."consultation c 
            ON a.paitent_id = c.patient_id
            WHERE a.lead_source IS NOT NULL 
            AND a.lead_source != ''
            ORDER BY a.lead_source";
    
    $query = $this->db->query($sql);
    return $query->result_array();
}
/******End Consultation******/
	function patient_consultation_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $status){
		$consultation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$consultation_q = $this->db->query($consultation_sql);
		$consultation_result = $consultation_q->result_array();
		return $consultation_result;
	}
	
	function export_registration_data($start, $end, $center, $status){
		$registration_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
		
	    $registration_sql = "Select DISTINCT patient_id, receipt_number, totalpackage, fees as discounted_package,payment_done,remaining_amount,payment_method,billing_from,billing_at,on_date as date,status from ".$this->config->item('db_prefix')."registation where 1 $conditions order by on_date desc";
        $registration_q = $this->db->query($registration_sql);
        $registration_result = $registration_q->result_array();
        if(!empty($registration_result)){
            foreach($registration_result as $key => $val){
				$patient_name = $this->get_patient_name($val['patient_id']);
				$response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name,
						'receipt_number' => $val['receipt_number'],
				        'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
                        'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'registration',
                );
            }
        }    
		return $response;
    }
	
	function patient_registration_count($center, $start_date, $end_date, $patient_id){
		$registration_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
        if (!empty($center)){
			$conditions .= " and billing_at='$center'";
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
		$registration_sql = "Select * from ".$this->config->item('db_prefix')."registation where 1 ".$conditions."";
		$q = $this->db->query($registration_sql);
		return $q->num_rows();
	}
	
	function patient_registration_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $status){
		$registration_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$registration_sql = "Select * from ".$this->config->item('db_prefix')."registation where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$registration_q = $this->db->query($registration_sql);
		$registration_result = $registration_q->result_array();
		return $registration_result;
	}
	
	function patient_partialpayments_count($center, $start_date, $end_date, $patient_id, $status, $payment_method){
		$partialpayments_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if (!empty($status)){
			$conditions .= " and status='$status'";
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
		$partialpayments_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where 1 ".$conditions."";
		$q = $this->db->query($partialpayments_sql);
		return $q->num_rows();		
	}
	
	function patient_partialpayments_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $status, $payment_method){
		$partialpayments_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
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
		$partialpayments_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$partialpayments_q = $this->db->query($partialpayments_sql);
		$partialpayments_result = $partialpayments_q->result_array();
		return $partialpayments_result;
	}
	
	function export_partialpayments_data($start, $end, $center, $type, $status){
		$partialpayments_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
	    $partialpayments_sql = "Select DISTINCT billing_id,patient_id,payment_done,payment_method,billing_from,billing_at,on_date as date,status from ".$this->config->item('db_prefix')."patient_payments $conditions order by on_date desc";
        $partialpayments_q = $this->db->query($partialpayments_sql);
        $partialpayments_result = $partialpayments_q->result_array();
        if(!empty($partialpayments_result)){
            foreach($partialpayments_result as $key => $val){
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
                $response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name1,
						'billing_id' => $val['billing_id'],
				         'totalpackage' => '',
                        'discounted_package' => '',
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => '',
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
                        'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Partial Payment',
                );
            }
        }    
		return $response;
    }
	
	function export_partialpayments_report_data($start, $end, $center, $type, $status){
		$partialpayments_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' where billing_at="'.$center.'"';
        }
		if(!empty($start) && !empty($end)){
				
			if(empty($conditions)){
				$conditions .= " where on_date between '".$start."' AND '".$end."' ";
			}else{
				$conditions .= " and on_date between '".$start."' AND '".$end."' ";
			}
		}
		
	    $partialpayments_sql = "Select DISTINCT billing_id,refrence_number,patient_id,payment_done,payment_method,billing_from,billing_at,type,on_date as date,status,biller from ".$this->config->item('db_prefix')."patient_payments $conditions order by on_date desc";
        $partialpayments_r = $this->db->query($partialpayments_sql);
        $partialpayments_result = $partialpayments_r->result_array();
        if(!empty($partialpayments_result)){
            foreach($partialpayments_result as $key => $val){
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
                $response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name1,
						'billing_id' => $val['billing_id'],
						'refrence_number' => $val['refrence_number'],
				        'payment_done' => $val['payment_done'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
						'type' => $val['type'],
                        'date' => $val['date'],
                        'billing_type' => 'Partial Payment',
						'biller' => $val['biller'],
						'status' => $val['status'],
                );
            }
        }    
		return $response;
    }
	
	function procedure_billing_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
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
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
		function investigation_billing_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
			/***************Medicine Stock Account panel***************/
	
	function export_allmedicie_data($employee_number, $start_date, $end_date, $patient_id){

		$investigation_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
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
		
	    $investigation_sql = "Select DISTINCT patient_id, receipt_number,fees, discount_amount, payment_done, data, payment_method, cash_payment,card_payment,upi_payment,neft_payment, billing_at, employee_number, on_date as date, status from ".$this->config->item('db_prefix')."patient_medicine where 1 $conditions order by on_date desc";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				$patient_name = $this->get_patient_name($val['patient_id']);
					if (is_array($patient_name)) {
						$patient_name1 = strtoupper(implode(' ', $patient_name));
					} else {
						$patient_name1 = strtoupper($patient_name);
					}
                $response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name1,
						'receipt_number' => $val['receipt_number'],
						'fees' => $val['fees'],
						'discount_amount' => $val['discount_amount'],
                        'payment_done' => $val['payment_done'],
                        'payment_method' => $val['payment_method'],
                        'cash_payment' => $val['cash_payment'],
						'card_payment' => $val['card_payment'],
						'upi_payment' => $val['upi_payment'],
						'neft_payment' => $val['neft_payment'],
                        'billing_at' => $val['billing_at'],
						'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Medicine',
                );
            }
        }    
		return $response;
    }

	function patient_investigation_count2($employee_number, $start_date, $end_date, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
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
	   $investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where stutus_type='0' AND 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}
	
		function patient_investigation_list_patination2($limit, $page, $employee_number, $start_date, $end_date, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
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
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where stutus_type='0' AND 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
		/*******Reports*******/
	
	
	function dashboard_procedure_list_patination($center, $start_date, $end_date){
		$procedure_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
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
	   $procedure_sql = "SELECT sum(`totalpackage`) as total_package, sum(`fees`) as fees, sum(`discount_amount`) as discount_amount, sum(`payment_done`) as payment_done from ".$this->config->item('db_prefix')."patient_procedure where status='approved' AND 1".$conditions ;

		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
	
	function dashboard_investigation_list_patination($center, $start_date, $end_date){
		$investigation_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
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
		$investigation_sql = "SELECT sum(`totalpackage`) as total_package, sum(`fees`) as fees, sum(`discount_amount`) as discount_amount, sum(`payment_done`) as payment_done from ".$this->config->item('db_prefix')."patient_investigations where status='approved' AND 1".$conditions;
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;		
	}
	

	function dashboard_consultation_report_patination($center, $start_date, $end_date){
		$consultation_result = array();
		$conditions = '';
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
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
		$consultation_sql = "SELECT sum(`totalpackage`) as total_package, sum(`discount_amount`) as discount_amount, sum(`payment_done`) as payment_done from ".$this->config->item('db_prefix')."consultation where status='approved' AND 1".$conditions;
		$consultation_q = $this->db->query($consultation_sql);
		$consultation_result = $consultation_q->result_array();
		return $consultation_result;
	}

	function dashboard_partial_payment($center, $start_date, $end_date){
		$partial_payment = array();
		$conditions = '';
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
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
		$partial_sql = "SELECT sum(`payment_done`) as paymentreceive from ".$this->config->item('db_prefix')."patient_payments where type='procedure' AND status='1' AND 1".$conditions;
		$partial_q = $this->db->query($partial_sql);
		$partial_payment = $partial_q->result_array();
		return $partial_payment;
	}
	
	function dashboard_partial_payment_investigation($center, $start_date, $end_date){
		$partial_payment_investigation = array();
		$conditions = '';
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
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
		$partial_investi_sql = "SELECT sum(`payment_done`) as paymentinvestigation from ".$this->config->item('db_prefix')."patient_payments where type='investigation' AND status='1' AND 1".$conditions;
		$partial_investi_q = $this->db->query($partial_investi_sql);
		$partial_payment_investigation = $partial_investi_q->result_array();
		return $partial_payment_investigation;
	}
	
	function dashboard_registration_payment($center, $start_date, $end_date){
		$registration_payment = array();
		$conditions = '';
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
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
		$registration_sql = "SELECT sum(`totalpackage`) as total_package, sum(`discount_amount`) as discount_amount, sum(`payment_done`) as paymentregistration from ".$this->config->item('db_prefix')."registation where status='approved' AND 1".$conditions;
		$registration_q = $this->db->query($registration_sql);
		$registration_payment = $registration_q->result_array();
		return $registration_payment;
	}
	
	function dashboard_medicine_payment($center, $start_date, $end_date){
		$medicine_payment = array();
		$conditions = '';
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
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
		$medicine_sql = "SELECT sum(`payment_done`) as payment_done, sum(`discount_amount`) as discount_amount from ".$this->config->item('db_prefix')."patient_medicine where status='approved' AND stutus_type='0' AND 1".$conditions;
		$medicine_q = $this->db->query($medicine_sql);
		$medicine_payment = $medicine_q->result_array();
		return $medicine_payment;
	}
	
	function dashboard_medicine_return($center, $start_date, $end_date){
		$medicine_payment = array();
		$conditions = '';
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
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
		$medicine_sql = "SELECT sum(`payment_done`) as payment_done, sum(`discount_amount`) as discount_amount from ".$this->config->item('db_prefix')."patient_medicine where status='approved' AND stutus_type='1' AND 1".$conditions;
		$medicine_q = $this->db->query($medicine_sql);
		$medicine_payment = $medicine_q->result_array();
		return $medicine_payment;
	}

	function dashboard_product_advisory($start_date, $end_date){
		$product_advisory = array();
		$conditions = '';
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$advisory_sql = "SELECT sum(`discount_amount`) as discount_amount, sum(`payment_done`) as payment_done from ".$this->config->item('db_prefix')."training where status='1' AND 1".$conditions;
		$advisory_q = $this->db->query($advisory_sql);
		$product_advisory = $advisory_q->result_array();
		return $product_advisory;
	}

	function dashboard_fellowship_training($start_date, $end_date){
		$fellowship_training = array();
		$conditions = '';
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$fellowship_sql = "SELECT sum(`payment_done`) as payment_done, sum(`discount_amount`) as discount_amount from ".$this->config->item('db_prefix')."fellowship_training where status='1' AND 1".$conditions;
		$fellowship_q = $this->db->query($fellowship_sql);
		$fellowship_training = $fellowship_q->result_array();
		return $fellowship_training;
	}
	
	
 /*********End Reports*********/
	
	function get_employee_list(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."employees where other_role='stock_manager' and status='1'";
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
	
	function get_employee_name($employee_number){
		$result = array();
		$sql_condition = '';
		$sql = "Select name from ".$this->config->item('db_prefix')."employees where employee_number='".$employee_number."'";
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
	
	public function update_investigation($data, $ID)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "patient_investigations SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$ID."'";
        $this->db->query($sql);
        return 1;
    }

    function dashboard_appointment_list($center, $start_date, $end_date){
		$appointments_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and appoitmented_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and appoitmented_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and appoitmented_date='$end_date'";
		}
	   $appointments_sql = "SELECT COUNT(ID) as appointmentID  FROM ".$this->config->item('db_prefix')."appointments WHERE paitent_type='new_patient' AND 1".$conditions;

		$appointments_q = $this->db->query($appointments_sql);
		$appointments_result = $appointments_q->result_array();
		return $appointments_result;
	}
	
	/*********Liason**********/
	
	function insert_add_liason($data){
	 $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "liason` SET ";
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
	
	function liason_count($center, $start_date, $end_date){
		$procedure_result = array();
		$conditions = '';
		if (!empty($center)){
			$conditions .= " and center='$center'";
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
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."liason where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}
	
		function liason_patination($limit, $page, $center, $start_date, $end_date){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($center)){
			$conditions .= " and center='$center'";
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
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."liason where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
	public function update_liason($data, $ID)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "liason SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$ID."'";
        $this->db->query($sql);
        return 1;
    }
	
	/***********End Liason***********/
	
	/*********Document**********/
	
	function insert_add_indiaivf_document($data){
	 $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "document` SET ";
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
	
	function indiaivf_document_count($document_name){
		$procedure_result = array();
		$conditions = '';
		if (!empty($document_name)){
			$conditions .= " and document_name='$document_name'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."document where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}
	
		function indiaivf_document_patination($limit, $page, $document_name){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($document_name)){
			$conditions .= " and document_name='$document_name'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."document where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
	/***********End Document***********/
	
	/**************Start Freezing**************/
		function freezing_renewal_count($start_date, $end_date, $iic_id){
		$partialpayments_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
        if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_date='$end_date'";
		}
		$partialpayments_sql = "Select * from freezing where 1 ".$conditions."";
		$q = $this->db->query($partialpayments_sql);
		return $q->num_rows();
		
	}

	function freezing_renewal_list_patination($limit, $page, $start_date, $end_date, $iic_id){
		$partialpayments_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_date='$end_date'";
		}
		$partialpayments_sql = "Select * from freezing where 1".$conditions." order by expiry_date desc limit ". $limit." OFFSET ".$offset."";
		$partialpayments_q = $this->db->query($partialpayments_sql);
		$partialpayments_result = $partialpayments_q->result_array();
		return $partialpayments_result;
	}
	/********End Freezing********/
	
	/******** Clinical Reports *******/
    function clinical_reports_count($center, $year, $end_date, $patient_id, $payment_method){
		$procedure_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_doctor']['center']) && !empty($_SESSION['logged_doctor']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_doctor']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and center='$center'";
		}
		if(!empty($year)){
			$conditions .= ' and year="'.$year.'"';
        }
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		$sql2 ="SELECT * FROM `hms_doctors` WHERE name='".$_SESSION['logged_doctor']['name']."'";
        $query = $this->db->query($sql2);
        $select_result2 = $query->result();
		foreach($select_result2 as $ky => $vl){ 
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."clinical_reports where center='$vl->center_id' AND 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		}
		$sql4 ="SELECT * FROM `hms_employees` WHERE name='".$_SESSION['logged_embryologist']['name']."'";
        $query = $this->db->query($sql4);
        $select_result4 = $query->result();
		foreach($select_result4 as $ky => $vl2){ 
		$procedure_sql4 = "Select * from ".$this->config->item('db_prefix')."clinical_reports where center='$vl->center_id' AND 1 ".$conditions."";
		$q = $this->db->query($procedure_sql4);
		return $q->num_rows();
		 }
		if($_SESSION['logged_administrator']){
		 $procedure_sql = "Select * from ".$this->config->item('db_prefix')."clinical_reports where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		 }
	}
	
	function clinical_reports_patination($limit, $page, $center, $year, $end_date, $patient_id, $payment_method){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_doctor']['center']) && !empty($_SESSION['logged_doctor']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_doctor']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and center='$center'";
		}
		if(!empty($year)){
			$conditions .= ' and year="'.$year.'"';
        }
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		$sql2 ="SELECT * FROM `hms_doctors` WHERE name='".$_SESSION['logged_doctor']['name']."'";
        $query = $this->db->query($sql2);
        $select_result2 = $query->result();
		foreach($select_result2 as $ky => $vl){ 
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."clinical_reports where center='$vl->center_id' AND 1".$conditions." order by year desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
		}
		
		$sql3 ="SELECT * FROM `hms_employees` WHERE name='".$_SESSION['logged_embryologist']['name']."'";
        $query = $this->db->query($sql3);
        $select_result3 = $query->result();
		foreach($select_result3 as $ky => $vl2){ 
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."clinical_reports where center='$vl2->center_id' AND 1".$conditions." order by year desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
		}
		if($_SESSION['logged_administrator']){
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."clinical_reports where 1".$conditions." order by year desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
		}
	}
	
	function patient_procedure_origin_count($center, $start_date, $end_date, $patient_id, $patient_procedures, $json_data){
		$procedure_result = array();
		$conditions = '';
		/*if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}*/
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if(!empty($patient_procedures)){
			$conditions .= " and data like '%$patient_procedures%'";
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
		else if(!empty($json_data)){
			$conditions .= " and data like '%$json_data%'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
	}
	
	function patient_procedure_origin_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $patient_procedures, $json_data){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		/*if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}*/
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($patient_procedures)){
			$conditions .= " and data like '%$patient_procedures%'";
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
		else if(!empty($json_data)){
			$conditions .= " and data like '%$json_data%'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
	function export_consumption_medicine($center, $start_date, $end_date, $patient_id, $payment_method, $json_data){
		$consuption_result = $response = array();
        $conditions = '';
		/*if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}*/
        if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($patient_id)){
			$conditions .= ' and patient_id="'.$patient_id.'"';
        }
		if(!empty($patient_procedures)){
			$conditions .= " and data like '%$patient_procedures%'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		else if(!empty($json_data)){
			$conditions .= " and data like '%$json_data%'";
		}
		$investigation_sql = "Select DISTINCT patient_id, appointment_id, totalpackage, fees, status, payment_done, on_date, modified_on, data, receipt_number, billing_from,billing_at, origins, cn_invoice from ".$this->config->item('db_prefix')."patient_procedure where 1 $conditions";
        $investigation_q = $this->db->query($investigation_sql);
        $consuption_result = $investigation_q->result_array();
        if(!empty($consuption_result)){
            foreach($consuption_result as $key => $val){
				$_data = unserialize($val['data']);
				$medicine_name_arr = [];
				$medicine_quantity_arr = [];
				$medicine_stock_arr = [];
				$sub_procedures_discount_arr = [];
				$sub_procedures_paid_price_arr = [];
				//echo'<pre>';
				//print_r($_data);
                //die;
			    //echo'<br>';
				foreach($_data as $value1){
					//print_r($value1);
					//echo'<pre>';
				//print_r($value1);
                //die;
			    //echo'<br>';
					foreach($value1 as $value3){
						
						if($value3['sub_procedure']){
							
								array_push($medicine_name_arr,$value3['sub_procedure']);
								array_push($medicine_quantity_arr,$value3['sub_procedures_code']);
							    array_push($medicine_stock_arr,$value3['sub_procedures_price']);
							    array_push($sub_procedures_discount_arr,$value3['sub_procedures_discount']);
								array_push($sub_procedures_paid_price_arr,$value3['sub_procedures_paid_price']);
						    }
					}
				}
				//print_r($sub_procedure);
				//die();
				 $sub_procedure = implode(',', $medicine_name_arr );
				 $sub_procedures_code = implode(',', $medicine_quantity_arr );
				 $sub_procedures_price = implode(',', $medicine_stock_arr );
				 $sub_procedures_discount = implode(',', $sub_procedures_discount_arr );
				 $sub_procedures_paid_price = implode(',', $sub_procedures_paid_price_arr );
				if($sub_procedure  != ''){
				 $medicine_name_sql = "SELECT procedure_name FROM hms_procedures WHERE ID IN (".$sub_procedure.") " ;
				}
				
				       /* echo'<pre>';
					    print_r($final_medicine123);
						print_r($medicine_quantity_arr[$_key]);
						echo'<br>';*/
				
				$_medicine_name_arr = [];
				$medicine_name_sql_q = $this->db->query($medicine_name_sql);
				$medicine_name_sql_q_result = $medicine_name_sql_q->result_array();
				if(!empty($medicine_name_sql_q_result)){
					foreach($medicine_name_sql_q_result as $key => $medicine_name_val){
						array_push($_medicine_name_arr,$medicine_name_val['procedure_name']);
					}
				}
				
				foreach($_medicine_name_arr as $_key => $_value){
					$final_medicine123 = $_value;
					
				//die();
					$response[] = array(
				        'on_date' => $val['on_date'],
                        'patient_id' => $val['patient_id'],
                        'receipt_number' => $val['receipt_number'],
				        'sub_procedure'=> $medicine_name_arr[$_key],
						'sub_procedures_code' => $medicine_quantity_arr[$_key],
						'sub_procedures_price' => $medicine_stock_arr[$_key],
						'sub_procedures_discount' => $sub_procedures_discount_arr[$_key],
						'sub_procedures_paid_price' => $sub_procedures_paid_price_arr[$_key],
						'billing_at' => $val['billing_at'],
						'billing_from' => $val['billing_from'],
						'origins' => $val['origins'],
						'cn_invoice' => $val['cn_invoice'],
						'status' => $val['status'],
						'appointment_id' => $val['appointment_id'],
						'modified_on' => $val['modified_on']
			    );
				}
			}
		}      
		return $response;
    }
	
	/************Investigation*********/
	
	function patient_investigation_origin_count($center, $start_date, $end_date, $patient_id, $patient_procedures, $json_data){
		$investigation_result = array();
		$conditions = '';
/*if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}*/
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if(!empty($patient_procedures)){
			$conditions .= " and investigations like '%$patient_procedures%'";
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
		else if(!empty($json_data)){
			$conditions .= " and investigations like '%$json_data%'";
		}
		$investigation_r_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where 1 ".$conditions."";
		$q = $this->db->query($investigation_r_sql);
		return $q->num_rows();
	}
	
	function patient_investigation_origin_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $patient_procedures, $json_data){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		/*if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}*/
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($patient_procedures)){
			$conditions .= " and investigations like '%$patient_procedures%'";
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
		else if(!empty($json_data)){
			$conditions .= " and investigations like '%$json_data%'";
		}
		$investigation_r_sql = "Select * from ".$this->config->item('db_prefix')."patient_investigations where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_r_q = $this->db->query($investigation_r_sql);
		$investigation_result = $investigation_r_q->result_array();
		return $investigation_result;
	}
	
	/********End Clinical Reports********/
	
	/************Admission Form*********/
	
	function admission_form_count($iic_id){
		$admission_result = array();
		$conditions = '';
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$investigation_r_sql = "Select * from admission_form where 1 ".$conditions."";
		$q = $this->db->query($investigation_r_sql);
		return $q->num_rows();
	}
	
	function admission_form_patination($limit, $page, $iic_id){
		$admission_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($iic_id)){
			$conditions .= " and iic_id='$iic_id'";
		}
		$investigation_r_sql = "Select * from admission_form where 1".$conditions." order by id desc limit ". $limit." OFFSET ".$offset."";
		$investigation_r_q = $this->db->query($investigation_r_sql);
		$admission_result = $investigation_r_q->result_array();
		return $admission_result;
	}
	
	function export_return_medicine($start_date, $end_date, $employee_number, $patient_id){
		$investigation_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
		$cash_medicine_sql = "Select DISTINCT patient_id, patient_detail_name, on_date, return_medicine, receipt_number, hospital_id, payment_method, employee_number, status from ".$this->config->item('db_prefix')."patient_medicine where 1 $conditions";
        $cash_medicine_q = $this->db->query($cash_medicine_sql);
        $cash_medicine_result = $cash_medicine_q->result_array();
        if(!empty($cash_medicine_result)){
            foreach($cash_medicine_result as $key => $val){
				$_data = unserialize($val['return_medicine']);
				$consumables_name_arr_r = [];
				$consumables_item_name_arr_r = [];
				$consumables_stock_arr_r = [];
				$consumables_quantity_arr_r = [];
				$consumables_price_arr_r = [];
				$consumables_vendor_price_arr_r = [];
				$consumables_discount_arr_r = [];
				$consumables_total_arr_r = [];
				$consumables_batch_number_arr_r = [];
				$consumables_hsn_arr_r = [];
				$consumables_gstrate_arr_r = [];
				$consumables_mrp_arr_r = [];
				foreach($_data as $value1){
					foreach($value1 as $value2){
						foreach($value2 as $value3){
							if($value3['consumables_name']){}
							array_push($consumables_name_arr_r,$value3['consumables_name']);
							array_push($consumables_item_name_arr_r,$value3['consumables_item_name']);
							array_push($consumables_stock_arr_r,$value3['consumables_stock']);
							array_push($consumables_quantity_arr_r,$value3['consumables_quantity']);
							array_push($consumables_price_arr_r,$value3['consumables_price']);
							array_push($consumables_vendor_price_arr_r,$value3['consumables_vendor_price']);
							array_push($consumables_discount_arr_r,$value3['consumables_discount_']);
							array_push($consumables_total_arr_r,$value3['consumables_total_']);
							array_push($consumables_batch_number_arr_r,$value3['consumables_batch_number']);
							array_push($consumables_hsn_arr_r,$value3['consumables_hsn']);
							array_push($consumables_gstrate_arr_r,$value3['consumables_gstrate']);
							array_push($consumables_mrp_arr_r,$value3['consumables_mrp']);
						}
					}
				}
				
				$consumables_name = implode(',', $consumables_name_arr_r );
				$consumables_item_name = implode(',', $consumables_item_name_arr_r );
				$consumables_stock = implode(',', $consumables_stock_arr_r );
				$consumables_quantity = implode(',', $consumables_quantity_arr_r );
				$consumables_price = implode(',', $consumables_price_arr_r );
				$consumables_vendor_price = implode(',', $consumables_vendor_price_arr_r );
				$consumables_discount_ = implode(',', $consumables_discount_arr_r );
				$consumables_total_ = implode(',', $consumables_total_arr_r );
				$consumables_batch_number = implode(',', $consumables_batch_number_arr_r );
				$consumables_hsn = implode(',', $consumables_hsn_arr_r );
				$consumables_gstrate = implode(',', $consumables_gstrate_arr_r );
				$consumables_mrp = implode(',', $consumables_mrp_arr_r );
				if($consumables_name  != ''){
				 $consumables_name_sql2 = "SELECT item_name FROM hms_stocks WHERE item_number IN (".$consumables_name.") " ;
				}
				$_consumables_name_arr_r = [];
				
				$consumables_name_sql_qr = $this->db->query($consumables_name_sql2);
				$consumables_name_sql_qr_result = $consumables_name_sql_qr->result_array();
				if(!empty($consumables_name_sql_qr_result)){
					foreach($consumables_name_sql_qr_result as $key => $consumables_name_val2){
						array_push($_consumables_name_arr_r,$consumables_name_val2['item_name']);
					}
				}
				
				$final_consumables_arr_r =[];
				
				foreach($_consumables_name_arr_r as $_key => $_value){
					$final_consumables123 = $_value;
					array_push($final_consumables123);
					$response[] = array(
				        'on_date' => $val['on_date'],
                        'patient_id' => $val['patient_id'],
				        'patient_detail_name' => $val['patient_detail_name'],
						'hospital_id' => $val['hospital_id'],
						'receipt_number' => $val['receipt_number'],
                        'payment_method' => $val['payment_method'],
                        'consumables_name'=> $consumables_name_arr_r[$_key],
						'consumables_item_name'=> $consumables_item_name_arr_r[$_key],
						'consumables_stock'=> $consumables_stock_arr_r[$_key],
						'consumables_quantity' => $consumables_quantity_arr_r[$_key],
						'consumables_price' => $consumables_price_arr_r[$_key],
						'consumables_vendor_price' => $consumables_vendor_price_arr_r[$_key],
						'consumables_discount_' => $consumables_discount_arr_r[$_key],
						'consumables_total_' => $consumables_total_arr_r[$_key],
						'employee_number' => $val['employee_number'],
						'status' => $val['status'],
						'consumables_batch_number' => $consumables_batch_number_arr_r[$_key],
						'consumables_hsn' => $consumables_hsn_arr_r[$_key],
						'consumables_gstrate' => $consumables_gstrate_arr_r[$_key], 
						'consumables_mrp' => $consumables_mrp_arr_r[$_key]
                );
				}
			}
        }      
		return $response;
    }
	
	/********End Admission Form********/
	
	function partial_procedure_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "patient_payments` SET ";
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
	
	function donor_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "donor` SET ";
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
	
	function get_paitent_id(){
		$result = array();
		$sql_condition = '';
	    $sql = "Select DISTINCT paitent_id, wife_name from ".$this->config->item('db_prefix')."appointments where paitent_type='exist_patient'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result;
        }else{
            return $result;
        }
	}
	
	function get_uhid(){
		$result = array();
		$sql_condition = '';
	    $sql = "Select DISTINCT uhid from ".$this->config->item('db_prefix')."appointments";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result;
        }else{
            return $result;
        }
	}
	
	function donor_count($patient_id, $donor_patient_id){
		$procedure_result = array();
		$conditions = '';
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if(!empty($donor_patient_id)){
			$conditions .= " and donor_patient_id='$donor_patient_id'";
        }
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."donor where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}
	
	function donor_patination($limit, $page, $patient_id, $donor_patient_id){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if(!empty($donor_patient_id)){
			$conditions .= " and donor_patient_id='$donor_patient_id'";
        }
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."donor where 1".$conditions." order by date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
	
	function export_cancel_procedure_data($start, $end, $center, $patient_id){

		$procedure_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
		if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($patient_id)){
			$conditions .= ' and patient_id="'.$patient_id.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and modified_on between '".$start."' AND '".$end."' ";
        }
		
	     $procedure_sql = "Select DISTINCT patient_id, receipt_number, totalpackage, fees as discounted_package,payment_done,remaining_amount,
		payment_method,billing_from,billing_at,biller_id,data,on_date,modified_on,status,hospital_id from ".$this->config->item('db_prefix')."patient_procedure where status='cancel' and 1
		$conditions order by on_date desc";
        $procedure_q = $this->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
        if(!empty($procedure_result)){
            foreach($procedure_result as $key => $val){
				$hms_procedures_result = unserialize( $val['data']);
				$procedure_nameArr = [];
				foreach ($hms_procedures_result as $v2_data123){
						foreach ($v2_data123 as $v2_data5){

						    $sql12 = "select procedure_name from hms_procedures where code='".$v2_data5['sub_procedures_code']."'"; 
                            $query12 = $this->db->query($sql12);
                            $select_result1 = $query12->result(); 
							
							foreach ($select_result1 as $res_val){
								// echo $res_val->procedure_name;
								 array_push($procedure_nameArr,$res_val->procedure_name);
							}
						}
						
				}
					 
				$procedure_name1 = implode(',',$procedure_nameArr); 
				
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
                $response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name1,
						'receipt_number' => $val['receipt_number'],
				        'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
						'procedure_name' => $procedure_name1,
						'billing_type' => 'Procedure',
						'on_date' => $val['on_date'],
						'modified_on' => $val['modified_on'],
                        'status' => $val['status'],
						'hospital_id' => $val['hospital_id'],
                );
            }
        }    
		return $response;
    }
	
	function procedure_cancel_count($center, $start_date, $end_date, $patient_id, $payment_method){
		$procedure_result = array();
		$conditions = '';
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if(!empty($sub_procedures_code)){
			$conditions .= ' and sub_procedures_code="'.$sub_procedures_code.'"';
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
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1 ".$conditions." and status='cancel' ";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
	}

	function procedure_cancel_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $payment_method){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
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
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1".$conditions." and status='cancel' order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
	function medicine_cancel_count($center, $start_date, $end_date, $patient_id, $payment_method){
		$medicine_cancel_result = array();
		$conditions = '';
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if(!empty($sub_procedures_code)){
			$conditions .= ' and sub_procedures_code="'.$sub_procedures_code.'"';
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
		$medicine_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where 1 ".$conditions." and status='cancel' ";
		$q = $this->db->query($medicine_sql);
		return $q->num_rows();
	}

	function medicine_cancel_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $payment_method){
		$medicine_cancel_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
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
		$medicine_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where 1".$conditions." and status='cancel' order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$medicine_q = $this->db->query($medicine_sql);
		$medicine_cancel_result = $medicine_q->result_array();
		return $medicine_cancel_result;
	}	
	
	function consultation_cancel_count($center, $start_date, $end_date, $patient_id, $payment_method){
		$consultation_cancel_result = array();
		$conditions = '';
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if(!empty($sub_procedures_code)){
			$conditions .= ' and sub_procedures_code="'.$sub_procedures_code.'"';
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
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where 1 ".$conditions." and status='adjust' ";
		$q = $this->db->query($consultation_sql);
		return $q->num_rows();
	}

	function consultation_cancel_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $payment_method){
		$consultation_cancel_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
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
		 $consultation_sql = "Select * from ".$this->config->item('db_prefix')."consultation where 1".$conditions." and status='adjust' order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$consultation_q = $this->db->query($consultation_sql);
		$consultation_cancel_result = $consultation_q->result_array();
		return $consultation_cancel_result;
	}		
	
	function get_wallet_details($receipt_number, $type){
		$result = array();
		if($type == 'consultation'){			
			$sql = "Select * from `".$this->config->item('db_prefix')."consultation` WHERE receipt_number='".$receipt_number."'";
		}else if($type == 'investigation'){
			$sql = "Select * from `".$this->config->item('db_prefix')."patient_investigations` WHERE receipt_number='".$receipt_number."'";
		}else if($type == 'procedure'){
			$sql = "Select * from `".$this->config->item('db_prefix')."patient_procedure` WHERE receipt_number='".$receipt_number."'";
		}
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
	
	function get_consultation_wallet($receipt_number){
		$result = array();
		$sql = "Select * from `".$this->config->item('db_prefix')."consultation` WHERE receipt_number='".$receipt_number."'";
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

	function training_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "training` SET ";
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

	function training_count($ctraining_name){
		$consultation_cancel_result = array();
		$conditions = '';
		if(!empty($training_name)){
			$conditions .= " and training_name like '%$training_name%'";
        }
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."training where 1 ".$conditions."";
		$q = $this->db->query($consultation_sql);
		return $q->num_rows();
	}

	function training_pagination($limit, $page, $training_name){
		$consultation_cancel_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(!empty($training_name)){
			$conditions .= " and training_name like '%$training_name%'";
        }
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."training where 1".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$consultation_q = $this->db->query($consultation_sql);
		$consultation_cancel_result = $consultation_q->result_array();
		return $consultation_cancel_result;
	}

	function approve_training($ID){
		$sql = "UPDATE `".$this->config->item('db_prefix')."training` SET `status`='1' WHERE `ID`='".$ID."'";
        $this->db->query($sql);
        return 1;
	}
	
	function disapprove_training($ID){
		$sql = "UPDATE `".$this->config->item('db_prefix')."training` SET `status`='2' WHERE `ID`='".$ID."'";
        $this->db->query($sql);
        return 1;
	}

	function fellowship_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "fellowship_training` SET ";
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

	function fellowship_insert_payment($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "fellowship_payments` SET ";
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
	
	function export_fellowship_and_training($name){
		$consultation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($name)){
			$conditions .= ' and name="'.$name.'"';
        }
		
	    $consultation_sql = "Select studentid, name,fname,course,code,hsn,price,discount_amount,payment_done,gst_amount,remaining_amount,gst,payment_method,address,place_of_supply,gst_number,on_date,receipt,invoice_no, status from ".$this->config->item('db_prefix')."fellowship_training where 1 $conditions order by on_date desc";
        $consultation_q = $this->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
        if(!empty($consultation_result)){
            foreach($consultation_result as $key => $val){
				$patient_name = $this->get_patient_name($val['patient_id']);
				//$patient_name1 = strtoupper($patient_name);
                $response[] = array(
                        'studentid' => $val['studentid'],
                        'name' => $val['name'],
						'fname' => $val['fname'],
				        'course' => $val['course'],
                        'code' => $val['code'],
                        'hsn' => $val['hsn'],
                        'price' => $val['price'],
                        'discount_amount' => $val['discount_amount'],
                        'payment_done' => $val['payment_done'],
                        'gst_amount' => $val['gst_amount'],
                        'remaining_amount' => $val['remaining_amount'],
                        'gst' => $val['gst'],
						'payment_method' => $val['payment_method'],
						'address' => $val['address'],
						'place_of_supply' => $val['place_of_supply'],
						'gst_number' => $val['gst_number'],
						'on_date' => $val['on_date'],
						'receipt' => $val['receipt'],
						'invoice_no' => $val['invoice_no'],
                        'status' => $val['status'],
                );
            }
        }    
		return $response;
    }

	function fellowship_count($name){
		$consultation_cancel_result = array();
		$conditions = '';
		if(!empty($name)){
			$conditions .= " and name like '%$name%'";
        }
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."fellowship_training where 1 ".$conditions."";
		$q = $this->db->query($consultation_sql);
		return $q->num_rows();
	}

	function fellowship_pagination($limit, $page, $name){
		$consultation_cancel_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(!empty($name)){
			$conditions .= " and name like '%$name%'";
        }
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."fellowship_training where 1".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$consultation_q = $this->db->query($consultation_sql);
		$consultation_cancel_result = $consultation_q->result_array();
		return $consultation_cancel_result;
	}

	function fellowship_payment_count($name){
		$consultation_cancel_result = array();
		$conditions = '';
		if(!empty($name)){
			$conditions .= " and name like '%$name%'";
        }
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."fellowship_payments where 1 ".$conditions."";
		$q = $this->db->query($consultation_sql);
		return $q->num_rows();
	}

	function fellowship_payment_pagination($limit, $page, $name){
		$consultation_cancel_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(!empty($name)){
			$conditions .= " and name like '%$name%'";
        }
		$consultation_sql = "Select * from ".$this->config->item('db_prefix')."fellowship_payments where 1".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$consultation_q = $this->db->query($consultation_sql);
		$consultation_cancel_result = $consultation_q->result_array();
		return $consultation_cancel_result;
	}
	
	public function approve_fellowship($ID, $voucherCode) {
	   $sql = "UPDATE `".$this->config->item('db_prefix')."fellowship_training` 
				SET `status` = 1, `voucherCode` = ? 
				WHERE `ID` = ?";

		// Execute the query and return the result
		return $this->db->query($sql, array($voucherCode, $ID));
	}
		
	function disapprove_fellowship($ID){
		$sql = "UPDATE `".$this->config->item('db_prefix')."fellowship_training` SET `status`='2' WHERE `ID`='".$ID."'";
        $this->db->query($sql);
        return 1;
	}
	
	function cancel_fellowship($ID){
		$cancel_date = date('Y-m-d');
		$sql = "UPDATE `".$this->config->item('db_prefix')."fellowship_training` SET `status`='3',`cancel_date`='$cancel_date' WHERE `ID`='".$ID."'";
        $this->db->query($sql);
        return 1;
	}

	function get_courses_name(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."courses where status='1'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result;
        }else{
            return $result;
        }
	}

	function get_fellowship_details($receipt){
		$result = array();
		$sql = "Select * from `".$this->config->item('db_prefix')."fellowship_training` WHERE receipt='".$receipt."'";
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

	function get_fellowship_payments($ID){
		$result = array();
		$sql = "Select * from `".$this->config->item('db_prefix')."fellowship_training` WHERE ID='".$ID."'";
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

	function get_productadvisory_details($invoice_no){
		$result = array();
		$sql = "Select * from `".$this->config->item('db_prefix')."training` WHERE invoice_no='".$invoice_no."'";
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

	function balancereturn_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "refund_amount` SET ";
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

	function wallet_count($patient_id){
		$wallet_result = array();
		$conditions = '';
		if(!empty($patient_id)){
			$conditions .= " and patient_id like '%$patient_id%'";
        }
		$wallet_sql = "Select * from ".$this->config->item('db_prefix')."refund_amount where 1 ".$conditions."";
		$walletq = $this->db->query($wallet_sql);
		return $walletq->num_rows();
	}

	function wallet_pagination($limit, $page, $patient_id){
		$wallet_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(!empty($patient_id)){
			$conditions .= " and patient_id like '%$patient_id%'";
        }
		$wallet_sql = "Select * from ".$this->config->item('db_prefix')."refund_amount where 1".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$wallet_q = $this->db->query($wallet_sql);
		$wallet_result = $wallet_q->result_array();
		return $wallet_result;
	}

	function get_wallet_refund($receipt_number){
		$result = array();
		$sql = "Select * from `".$this->config->item('db_prefix')."refund_amount` WHERE receipt_number='".$receipt_number."'";
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


	function get_procedure_advice($paitent_id){
		$result = array();
		$sql = "Select * from `".$this->config->item('db_prefix')."patient_procedure` WHERE patient_id='".$paitent_id."'";
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

	function doctor_patient_lists_pagination($limit, $page, $center, $start_date, $end_date, $patient_id, $payment_method, $biller_id){
		$app_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and consultation_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and consultation_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and consultation_date='$end_date'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($center_number)){
			$conditions .= " and center_number='$center_number'";
		}
		$investigation_r_sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where 1".$conditions." and procedure_suggestion='1' order by ID desc limit ". $limit." OFFSET ".$offset."";
		$investigation_r_q = $this->db->query($investigation_r_sql);
		$app_result = $investigation_r_q->result_array();
		return $app_result;
	}

/***** Partial Payment *****/

	function export_partial_procedure_data($start, $end, $center, $type, $payment_method, $biller_id){

		$procedure_result = $response = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
		if(!empty($biller_id)){
			$conditions .= ' and biller_id="'.$biller_id.'"';
		}
		if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
		}
		if(!empty($start) && !empty($end)){
			$conditions .= " and on_date between '".$start."' AND '".$end."' ";
		}
		
		 $procedure_sql = "Select DISTINCT patient_id,payment_done,
		payment_method,billing_from,billing_at,data,on_date,status from ".$this->config->item('db_prefix')."patient_payments where 1
		$conditions order by on_date desc";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		if(!empty($procedure_result)){
			foreach($procedure_result as $key => $val){
				$hms_procedures_result = unserialize( $val['data']);
				$procedure_nameArr = [];
				foreach ($hms_procedures_result as $v2_data123){
						foreach ($v2_data123 as $v2_data5){

							$sql12 = "select procedure_name from hms_procedures where code='".$v2_data5['sub_procedures_code']."'"; 
							$query12 = $this->db->query($sql12);
							$select_result1 = $query12->result(); 
							
							foreach ($select_result1 as $res_val){
								// echo $res_val->procedure_name;
								 array_push($procedure_nameArr,$res_val->procedure_name);
							}
						}
						
				}
					 
				$procedure_name1 = implode(',',$procedure_nameArr); 
				
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
				$response[] = array(
						'patient_id' => $val['patient_id'],
						'wife_name' => $patient_name1,
						'receipt_number' => $val['receipt_number'],
						'totalpackage' => $val['totalpackage'],
						'discounted_package' => $val['discounted_package'],
						'payment_done' => $val['payment_done'],
						'remaining_amount' => $val['remaining_amount'],
						'payment_method' => $val['payment_method'],
						'billing_from' => $val['billing_from'],
						'billing_at' => $val['billing_at'],
						'procedure_name' => $procedure_name1,
						'billing_type' => 'Procedure',
						'on_date' => $val['on_date'],
						'status' => $val['status'],
						'hospital_id' => $val['hospital_id'],
				);
			}
		}    
		return $response;
	}

	function partial_procedure_payment_count($center, $start_date, $end_date, $patient_id, $patient_procedures, $json_data){
		$procedure_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
		}
		if(!empty($patient_procedures)){
			$conditions .= " and data like '%$patient_procedures%'";
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
		else if(!empty($json_data)){
			$conditions .= " and data like '%$json_data%'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
	}

	function partial_procedure_payment_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $patient_procedures, $json_data){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($patient_procedures)){
			$conditions .= " and data like '%$patient_procedures%'";
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
		else if(!empty($json_data)){
			$conditions .= " and data like '%$json_data%'";
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_payments where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}

	function get_doctor_patient_count($start_date, $end_date, $patient_id, $center_number){
		$app_result = array();
		$conditions = '';
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and consultation_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and consultation_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and consultation_date='$end_date'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($center_number)){
			$conditions .= " and center_number='$center_number'";
		}
		$investigation_r_sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where 1 ".$conditions."";
		$q = $this->db->query($investigation_r_sql);
		return $q->num_rows();
	}
	
	function patient_lists_pagination($limit, $page, $start_date, $end_date, $patient_id, $center_number){
		$app_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and consultation_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and consultation_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and consultation_date='$end_date'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($center_number)){
			$conditions .= " and center_number='$center_number'";
		}
		$investigation_r_sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where 1".$conditions." order by consultation_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_r_q = $this->db->query($investigation_r_sql);
		$app_result = $investigation_r_q->result_array();
		return $app_result;
	}
	
	function revenue_potential_details($patient_id){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where patient_id='".$patient_id."'";
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
	
	function patient_report_count($center,$status, $start_date, $end_date, $patient_id, $payment_method, $biller_id){
		$procedure_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
		}
		if (!empty($biller_id)){
			$conditions .= " and biller_id='$biller_id'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if(!empty($sub_procedures_code)){
			$conditions .= ' and sub_procedures_code="'.$sub_procedures_code.'"';
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
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where status='approved' AND 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}
	
	function patient_report_list_patination($limit, $page, $center,$status, $start_date, $end_date, $patient_id, $payment_method, $biller_id){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($biller_id)){
			$conditions .= " and biller_id='$biller_id'";
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
		}
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
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
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where status='approved' AND 1".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
	function package_collections_count($center, $start_date, $end_date, $patient_id, $status, $payment_method){
		$partialpayments_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if (!empty($status)){
			$conditions .= " and status='$status'";
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
		$partialpayments_sql = "Select * from ".$this->config->item('db_prefix')."package_collections where 1 ".$conditions."";
		$q = $this->db->query($partialpayments_sql);
		return $q->num_rows();		
	}
	
	function package_collections_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $status, $payment_method){
		$partialpayments_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if(!empty($payment_method)){
			$conditions .= ' and payment_method="'.$payment_method.'"';
        }
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($status)){
			$conditions .= " and status='$status'";
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
		$partialpayments_sql = "Select * from ".$this->config->item('db_prefix')."package_collections where 1".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$partialpayments_q = $this->db->query($partialpayments_sql);
		$partialpayments_result = $partialpayments_q->result_array();
		return $partialpayments_result;
	}
	
	function doctor_referral_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "doctor_referral` SET ";
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
	
	function doctor_referral_count(){
		$procedure_result = array();
		$conditions = '';
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."doctor_referral where 1 ".$conditions."";
		$q = $this->db->query($procedure_sql);
		return $q->num_rows();
		
	}
	
	function doctor_referral_patination($limit, $page){
		$procedure_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		$procedure_sql = "Select * from ".$this->config->item('db_prefix')."doctor_referral where 1".$conditions." order by date desc limit ". $limit." OFFSET ".$offset."";
		$procedure_q = $this->db->query($procedure_sql);
		$procedure_result = $procedure_q->result_array();
		return $procedure_result;
	}
	
	function get_doctor_referral($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."doctor_referral where ID='".$item."'";
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
	
	public function update_doctor_referral_data($data, $item)
    {	
        $sql = "UPDATE " . config_item('db_prefix') . "doctor_referral SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$item."'";
        $this->db->query($sql);
        return 1;
    }

	function get_counselor() {
    // FIX: The WHERE clause was written incorrectly.
    $sql = "SELECT * FROM `hms_employees` WHERE `role` = 'counselor' AND `status` = '1'";
    
    $q = $this->db->query($sql);
    $result = $q->result_array();
    return $result;
}
	
/*******End Partial Payment********/

	/************* Discharge Forms Management *************/
	
	function add_discharge_form($data){
		$sql = "INSERT INTO `hms_discharge_forms` SET ";
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
	
	function update_discharge_form($form_id, $data){
		$this->db->where('id', $form_id);
		$res = $this->db->update('hms_discharge_forms', $data);
		if ($res)
		{
			return 1;
		}
		else
			return 0;
	}
	
	function delete_discharge_form($form_id){
		// First, get the form data to know which table to drop
		$form_data = $this->get_discharge_form_by_id($form_id);
		
		if (!empty($form_data) && !empty($form_data['db_name'])) {
			// Additional safety check - ensure table name follows expected pattern
			if (strpos($form_data['db_name'], 'hms_') === 0 || strpos($form_data['db_name'], 'discharge') !== false) {
				// Drop the associated database table
				$table_dropped = $this->drop_discharge_form_table($form_data['db_name']);
				
				// Log the table drop attempt
				if (!$table_dropped) {
					log_message('error', 'Failed to drop table ' . $form_data['db_name'] . ' when deleting discharge form ID: ' . $form_id);
				}
			} else {
				log_message('warning', 'Skipping table drop for potentially unsafe table name: ' . $form_data['db_name']);
			}
		}
		
		// Delete the form record
		$this->db->where('id', $form_id);
		$res = $this->db->delete('hms_discharge_forms');
		if ($res)
		{
			return 1;
		}
		else
			return 0;
	}
	
	/**
	 * Drop database table for discharge form
	 */
	function drop_discharge_form_table($table_name) {
		// Check if table exists before trying to drop it
		$check_sql = "SHOW TABLES LIKE '{$table_name}'";
		$table_exists = $this->db->query($check_sql);
		
		if ($table_exists && $table_exists->num_rows() > 0) {
			// Drop the table
			$drop_sql = "DROP TABLE IF EXISTS `{$table_name}`";
			$result = $this->db->query($drop_sql);
			
			if ($result) {
				log_message('info', 'Successfully dropped table: ' . $table_name);
				return true;
			} else {
				log_message('error', 'Failed to drop table: ' . $table_name);
				return false;
			}
		} else {
			log_message('info', 'Table does not exist, nothing to drop: ' . $table_name);
			return true; // Return true since there's nothing to drop
		}
	}
	
	function get_discharge_form_by_id($form_id){
		$this->db->from('hms_discharge_forms');
		$this->db->where('id', $form_id);
		$sel = $this->db->get();
		$q = $sel->result_array();
		if ($q) {
			return $q[0];
		}
		return array();
	}

	/**
	 * Create database table for discharge form
	 */
	function create_discharge_form_table($table_name, $columns) {
		// Check if table already exists
		$check_sql = "SHOW TABLES LIKE '{$table_name}'";
		$table_exists = $this->db->query($check_sql);
		
		if ($table_exists && $table_exists->num_rows() > 0) {
			return array('success' => false, 'message' => "Table '{$table_name}' already exists!");
		}
		
		// Standard columns that are always included
		$standard_columns = "
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`iic_id` varchar(50) DEFAULT NULL,
			`appoitmented_date` int(11) DEFAULT NULL,
			`form_status` varchar(50) DEFAULT NULL,
			`completed_at` date DEFAULT NULL,
			`created_at` datetime DEFAULT CURRENT_TIMESTAMP,
			`updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			`created_by` varchar(50) DEFAULT NULL,
			`updated_by` varchar(50) DEFAULT NULL,
			`updated_type` varchar(50) DEFAULT NULL
		";
		
		// Build custom columns
		$custom_columns = "";
		if (!empty($columns) && is_array($columns)) {
			foreach ($columns as $column) {
				if (!empty($column['name']) && !empty($column['type'])) {
					$column_name = $this->db->escape_str($column['name']);
					$column_type = $column['type'];
					$required = (!empty($column['required']) && $column['required'] == '1') ? 'NOT NULL' : 'NULL';
					
					$custom_columns .= ", `{$column_name}` {$column_type} {$required}";
				}
			}
		}
		
		// Create table SQL with PRIMARY KEY defined in the same statement
		$sql = "CREATE TABLE `{$table_name}` (
			{$standard_columns}
			{$custom_columns},
			PRIMARY KEY (`id`),
			KEY `idx_iic_id` (`iic_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
		
		// Execute the query
		$result = $this->db->query($sql);
		
		if ($result) {
			return array('success' => true, 'message' => "Table '{$table_name}' created successfully!");
		} else {
			return array('success' => false, 'message' => "Failed to create table '{$table_name}'!");
		}
	}

        
	function get_lead_source($paitent_id){
        $result = array();
        $select_query = "SELECT * FROM hms_appointments WHERE paitent_id='$paitent_id'";
        $select_result = run_select_query($select_query); 
        $sql = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result['wife_phone']."' AND paitent_type='new_patient'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['lead_source'];
        }
        else
        {
            return $result;
        }
    }

	function get_lead($paitent_id){
        $result = array();
         $sql = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$paitent_id."' AND paitent_type='new_patient'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['lead_source'];
        }
        else
        {
            return $result;
        }
    }

function get_counselor_name($appointment_id) {
    $sql = "SELECT * 
            FROM ".$this->config->item('db_prefix')."doctor_consultation 
            WHERE appointment_id='".$appointment_id."' 
              AND status='1'";
    $q = $this->db->query($sql);
    $result = $q->result_array();
    if (!empty($result)) {
        return $result[0]['counsellor_signature']; // return string
    } else {
        return ''; // return empty string instead of array
    }
}

private function _get_common_conditions($center, $start_date, $end_date) {
    $conditions = " AND status IN ('Pending', 'approved')";
    $bindings = [];

    // Center Logic: Use argument first, then session fallback
    if (empty($center)) {
        if (isset($_SESSION['logged_billing_manager']['center'])) {
            $center = $_SESSION['logged_billing_manager']['center'];
        } elseif (isset($_SESSION['logged_counselor']['center'])) {
            $center = $_SESSION['logged_counselor']['center'];
        }
    }
    
    if (!empty($center)) {
        $conditions .= " AND billing_at = ?";
        $bindings[] = $center;
    }

    // Date Logic: Prioritize custom dates, fallback to today
    if (!empty($start_date) && !empty($end_date)) {
        $conditions .= " AND on_date BETWEEN ? AND ?";
        $bindings[] = $start_date;
        $bindings[] = $end_date;
    } elseif (!empty($start_date)) {
        $conditions .= " AND on_date = ?";
        $bindings[] = $start_date;
    } elseif (!empty($end_date)) {
        $conditions .= " AND on_date = ?";
        $bindings[] = $end_date;
    } else {
        // Default to today if no dates provided
        $conditions .= " AND on_date >= CURDATE() AND on_date < CURDATE() + INTERVAL 1 DAY";
    }

    return ['sql' => $conditions, 'bindings' => $bindings];
}

// 2. Dashboard Functions using the helper

function dashboard_medicine_daily_sales($center, $start_date, $end_date) {
    $data = $this->_get_common_conditions($center, $start_date, $end_date);
    $sql = "SELECT COUNT(patient_id) AS total_patients, SUM(payment_done) AS total_payment 
            FROM hms_patient_medicine WHERE 1 " . $data['sql'];
    $q = $this->db->query($sql, $data['bindings']);
    return $q->result_array();
}

function dashboard_investigation_daily_sales($center, $start_date, $end_date) {
    $data = $this->_get_common_conditions($center, $start_date, $end_date);
    $sql = "SELECT COUNT(patient_id) AS total_patients, SUM(payment_done) AS total_payment 
            FROM hms_patient_investigations WHERE 1 " . $data['sql'];
    $q = $this->db->query($sql, $data['bindings']);
    return $q->result_array();
}

function dashboard_consultation_daily_sales($center, $start_date, $end_date) {
    $data = $this->_get_common_conditions($center, $start_date, $end_date);
    $sql = "SELECT COUNT(patient_id) AS total_patients, SUM(payment_done) AS total_payment 
            FROM hms_consultation WHERE 1 " . $data['sql'];
    $q = $this->db->query($sql, $data['bindings']);
    return $q->result_array();
}

function dashboard_registration_daily_sales($center, $start_date, $end_date) {
    $data = $this->_get_common_conditions($center, $start_date, $end_date);
    $sql = "SELECT COUNT(patient_id) AS total_patients, SUM(payment_done) AS total_payment 
            FROM hms_registation WHERE 1 " . $data['sql'];
    $q = $this->db->query($sql, $data['bindings']);
    return $q->result_array();
}

function dashboard_procedure_daily_sales($center, $start_date, $end_date) {
    $data = $this->_get_common_conditions($center, $start_date, $end_date);
    // Added 'status' check to base conditions to match your original
    $sql = "SELECT COUNT(patient_id) AS total_patients, SUM(fees) AS total_fees, SUM(payment_done) AS total_payment 
            FROM hms_patient_procedure WHERE 1 " . $data['sql'];
    $q = $this->db->query($sql, $data['bindings']);
    return $q->result_array();
}

// 3. List/Pagination Functions

function dashboard_procedure_reports_list_patination($center, $start_date, $end_date) {
    $data = $this->_get_common_conditions($center, $start_date, $end_date);
    $sql = "SELECT * FROM " . $this->config->item('db_prefix') . "patient_procedure WHERE 1 " . $data['sql'];
    $q = $this->db->query($sql, $data['bindings']);
    return $q->result_array();
}

function dashboard_partial_daily_sales_report($center, $start_date, $end_date) {
    $data = $this->_get_common_conditions($center, $start_date, $end_date);
    $sql = "SELECT * FROM " . $this->config->item('db_prefix') . "patient_payments WHERE 1 " . $data['sql'];
    $q = $this->db->query($sql, $data['bindings']);
    return $q->result_array();
}

function dashboard_medicine_reports_list_patination($center, $start_date, $end_date) {
    $data = $this->_get_common_conditions($center, $start_date, $end_date);
    $sql = "SELECT * FROM " . $this->config->item('db_prefix') . "patient_medicine WHERE 1 " . $data['sql'];
    $q = $this->db->query($sql, $data['bindings']);
    return $q->result_array();
}

function dashboard_diagnostic_reports_list_patination($center, $start_date, $end_date) {
    $data = $this->_get_common_conditions($center, $start_date, $end_date);
    $sql = "SELECT * FROM " . $this->config->item('db_prefix') . "patient_investigations WHERE 1 " . $data['sql'];
    $q = $this->db->query($sql, $data['bindings']);
    return $q->result_array();
}

function dashboard_consultation_reports_list_patination($center, $start_date, $end_date) {
    $data = $this->_get_common_conditions($center, $start_date, $end_date);
    $sql = "SELECT * FROM " . $this->config->item('db_prefix') . "consultation WHERE 1 " . $data['sql'];
    $q = $this->db->query($sql, $data['bindings']);
    return $q->result_array();
}

public function save_daily_sales_report($data) {
    // Check if a report for this date and center already exists
    $this->db->where('report_date', $data['report_date']);
    $this->db->where('center', $data['center']);
    $query = $this->db->get('hms_daily_sales_reports');

    if ($query->num_rows() > 0) {
        // Update existing record
        $this->db->where('report_date', $data['report_date']);
        $this->db->where('center', $data['center']);
        return $this->db->update('hms_daily_sales_reports', $data);
    } else {
        // Insert new record
        return $this->db->insert('hms_daily_sales_reports', $data);
    }
}

  public function get_doctors_by_center($center_number) {
    // First, let's debug what we're receiving
    error_log("Looking for doctors for center: " . $center_number);
    
    // Try direct match first
    $this->db->select('ID, name, email, is_primary');
    $this->db->from('hms_doctors');
    $this->db->where('center_id', $center_number);
    //$this->db->where('status', 'active');
    $this->db->order_by('is_primary', 'DESC');
    $this->db->order_by('name', 'ASC');
    
    $query = $this->db->get();
    
    if ($query->num_rows() > 0) {
        error_log("Found " . $query->num_rows() . " doctors for center_id: " . $center_number);
        return $query->result_array();
    }
    
    // If no direct match, try to find mapping
    error_log("No direct match found for center_id: " . $center_number);
    
    return [];
}
  
    /**
     * Get comprehensive patient billing data for final bill
     * @param int $patient_id
     * @return array
     */
    public function get_patient_final_billing_data($patient_id) {
        // Get patient basic info
        $this->db->select('*');
        $this->db->from('hms_patients');
        $this->db->where('patient_id', $patient_id);
        $patient_query = $this->db->get();
        $patient_data = $patient_query->row_array();
        
        if (!$patient_data) {
            return false;
        }
        
        // Check payment status
        $payment_status = $this->check_procedures_payment_status($patient_id);
        
        // Get payment history
        $payment_history = $this->get_patient_payment_history($patient_id);
        
	        // Get center information from procedure data
        $center_info = $this->get_center_info_from_procedures($patient_id);
        
        return array(
            'patient' => $patient_data,
            'payment_status' => $payment_status,
            'payment_history' => $payment_history,
            'center_info' => $center_info
        );
    }
	  public function check_procedures_payment_status($patient_id) {
        $this->db->select('ID, billing_id, totalpackage, discount_amount, payment_done, remaining_amount, status, data, on_date');
        $this->db->from('hms_patient_procedure');
        $this->db->where('patient_id', $patient_id);
        $this->db->where_in('status', array('approved', 'pending')); // Include both approved and pending
        $this->db->order_by('on_date', 'ASC');
        $query = $this->db->get();
        
        $procedures = $query->result_array();
        $total_amount = 0;
        $total_paid = 0;
        $total_remaining = 0;
        $all_paid = true;
        $procedure_details = array();
        
        foreach ($procedures as $procedure) {
            $net_amount = $procedure['totalpackage'] - $procedure['discount_amount'];
            $total_amount += $net_amount;
            $total_paid += $procedure['payment_done'];
            $total_remaining += $procedure['remaining_amount'];
            
            if ($procedure['remaining_amount'] > 0) {
                $all_paid = false;
            }
            
            // Unserialize procedure data to get detailed breakdown
            $procedure_data = unserialize($procedure['data']);
            if (isset($procedure_data['patient_procedures'])) {
                foreach ($procedure_data['patient_procedures'] as $proc) {
                    // Get procedure name from hms_procedures table
                    $procedure_name = isset($proc['sub_procedure']) ? $proc['sub_procedure'] : 'Unknown Procedure';
                    if (!empty($proc['sub_procedures_code'])) {
                        $this->db->select('procedure_name');
                        $this->db->from('hms_procedures');
                        $this->db->where('code', $proc['sub_procedures_code']);
                        $proc_query = $this->db->get();
                        if ($proc_query->num_rows() > 0) {
                            $proc_result = $proc_query->row_array();
                            $procedure_name = $proc_result['procedure_name'];
                        }
                    }
                    
                    $procedure_details[] = array(
                        'procedure_name' => $procedure_name,
                        'procedure_code' => isset($proc['sub_procedures_code']) ? $proc['sub_procedures_code'] : 'N/A',
                        'price' => isset($proc['sub_procedures_price']) ? $proc['sub_procedures_price'] : 0,
                        'discount' => isset($proc['sub_procedures_discount']) ? $proc['sub_procedures_discount'] : 0,
                        'paid_price' => isset($proc['sub_procedures_paid_price']) ? $proc['sub_procedures_paid_price'] : 0,
                        'billing_id' => $procedure['billing_id'],
                        'on_date' => $procedure['on_date'],
                        'status' => $procedure['status']
                    );
                }
            }
        }
        
        // Get total payments from hms_patient_payments table
        $this->db->select('SUM(payment_done) as total_payments');
        $this->db->from('hms_patient_payments');
        $this->db->where('patient_id', $patient_id);
        $this->db->where('status', 0); // Only approved payments
        $payments_query = $this->db->get();
        $payments_result = $payments_query->row_array();
        $actual_total_paid = $payments_result['total_payments'] ? $payments_result['total_payments'] : 0;
        
        // Calculate actual remaining amount
        $actual_remaining = $total_amount - $actual_total_paid;
        $all_paid = ($actual_remaining <= 0);
        
        return array(
            'all_paid' => $all_paid,
            'total_amount' => $total_amount,
            'total_paid' => $actual_total_paid,
            'total_remaining' => $actual_remaining,
            'procedures' => $procedures,
            'procedure_details' => $procedure_details
        );
    }
    
	public function get_patient_payment_history($patient_id) {
        $this->db->select('pp.*, pp2.billing_id as procedure_billing_id');
        $this->db->from('hms_patient_payments pp');
        $this->db->join('hms_patient_procedure pp2', 'pp.billing_id = pp2.billing_id', 'left');
        $this->db->where('pp.patient_id', $patient_id);
        $this->db->where('pp.status', 0); // Only approved payments
        $this->db->order_by('pp.on_date', 'ASC');
        $query = $this->db->get();
        
        return $query->result_array();
    }


	private function get_center_info_from_procedures($patient_id) {
        // Get center info from the first procedure
        $this->db->select('billing_at');
        $this->db->from('hms_patient_procedure');
        $this->db->where('patient_id', $patient_id);
        $this->db->where('status', 'approved');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row_array();
        
        if ($result) {
            // Get center details from hms_centers table
            $this->db->select('*');
            $this->db->from('hms_centers');
            $this->db->where('id', $result['billing_at']);
            $center_query = $this->db->get();
            return $center_query->row_array();
        }
        
        // Return default center info if not found
        return array(
            'center_name' => 'INDIA IVF CLINIC',
            'address' => 'Third Floor, N-26, Captain Vijayant Thapar Marg, Dr. Lal Path Labs, Sec.-18, Noida Gautambuddha Nagar Uttar Pradesh, 201301',
            'phone' => '73538 73538',
            'email' => 'INDIAIVFCLINIC@GMAIL.COM',
            'website' => 'WWW.INDIAIVF.IN'
        );
    }

    

}