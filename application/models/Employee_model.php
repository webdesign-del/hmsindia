<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class Employee_model extends CI_Model
{
	function get_employees(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."employees where employee_number!='1' ORDER by ID DESC";
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
	
	function add_item($data){
		$password = $data['password'];
		unset($data['password']);
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "employees` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}
		$date = date("Y-m-d H:i:s");
		$sqlArr[] = " add_date = '".addslashes($date)."'";
		$sqlArr[] = " employee_number = '".addslashes(getGUID())."'";
		$sqlArr[] = " username = '".addslashes($data['email'])."'";
		$sqlArr[] = " password = '".addslashes(md5($password))."'";
		
		$sql .= implode(',' , $sqlArr);
		
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}
	
	function get_employee_details($email){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."employees where email='".$email."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (count($result) > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
	}
	
	function get_item_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."employees where employee_number='".$item."'";
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
	
        public function update_item_data($data, $item)
    {   
        if(empty($data['password'])){ unset($data['password']);}
        else{$password = md5($data['password']); unset($data['password']); $data['password'] = $password;}

        // नोट: $data['allowed_centers'] अब "1,3,5" के रूप में यहाँ आएगा और SQL का हिस्सा बन जाएगा
        $sql = "UPDATE " . config_item('db_prefix') . "employees SET ";
        $sqlArr = array(); // एरे इनिशियलाइज़ करना एक अच्छी प्रैक्टिस है
        foreach( $data as $key=> $value )
        {
            $sqlArr[] = " $key = '".$value."'"  ;
        }
        $sql .= implode(',' , $sqlArr);
        $sql .= " WHERE employee_number = '".$item."'";
        $this->db->query($sql);
        return 1;
    }
	
	public function delete_item_data($item){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "employees WHERE employee_number = '".$item."'";
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return 1;
		}
		else
			return 0;	
	}
	
	public function get_centers(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."centers where status='1' ORDER by ID ASC ";
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
	
	public function get_center_data($center){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$center."'";
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
	
	function allowed_discount($biller_id, $nationality){
		$select = "";
		if($nationality == "indian"){
			$select = "allow_discount_rs as allow_discount";
		}else{
			$select = "allow_discount_us as allow_discount";
		}
		
		$result = array();
		$sql = "Select $select from ".$this->config->item('db_prefix')."employees where employee_number='".$biller_id."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['allow_discount'];
        }
        else
        {
            return 0;
        }
	}
	
	// Get approvers for purchase order based on roles and status
	function get_approvers_for_purchase_order(){
		$result = array();
		$approval_roles = array('administrator', 'accountant', 'billing_manager', 'stock_manager', 'central_stock_manager');
		// Build SQL condition for roles
		$role_condition = "role IN ('" . implode("','", $approval_roles) . "')";
		$sql = "SELECT name, email, role FROM " . $this->config->item('db_prefix') . "employees 
				WHERE status = '1' AND email IS NOT NULL AND email != '' AND $role_condition
				ORDER BY role, name ASC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        $used_emails = array();
        $special_approvers = array(
            array(
                'name' => 'DIRECTOR Richika Sahay Shukla',
                'email' => 'director@indiaivf.in',
                'role' => 'director'
            ),
            array(
                'name' => 'CEO',
                'email' => 'ceo@indiaivf.in',
                'role' => 'ceo'
            ),
            array(
                'name' => 'Alan',
                'email' => 'alan@indiaivf.in',
                'role' => 'Director'
            )
        );
        // First, collect all emails from existing results
        foreach($result as $approver) {
            $used_emails[] = strtolower($approver['email']);
        }
        // Add special approvers only if their emails are not already present
        foreach($special_approvers as $special_approver) {
            if(!in_array(strtolower($special_approver['email']), $used_emails)) {
                array_unshift($result, $special_approver);
                $used_emails[] = strtolower($special_approver['email']);
            }
        }
        return $result;
	}

    function get_employee_center_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."employees where employee_number='".$item."'";
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

    	public function update_employee_center($data, $item)
    {	
		if(empty($data['password'])){ unset($data['password']);}
		else{$password = md5($data['password']); unset($data['password']); $data['password'] = $password;}

        $sql = "UPDATE " . config_item('db_prefix') . "employees SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE employee_number = '".$item."'";
        $this->db->query($sql);
        return 1;
    }

    public function get_employee_center(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."centers where status='1' ORDER by ID ASC";
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

    function get_doctor_center_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."doctors where ID='".$item."'";
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

    	public function update_doctor_center($data, $item)
    {	
		$sql = "UPDATE " . config_item('db_prefix') . "doctors SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$item."'";
        $this->db->query($sql);
        return 1;
    }

}
// END Stock_model class

/* End of file Stock_model.php */
/* Location: ./application/models/Stock_model.php */