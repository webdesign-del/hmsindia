<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class User_model extends CI_Model
{
	function userlogin($data)
    {
		$result = array();
		$sql_condition = '';
		$sql = "Select * from " . $this->config->item('db_prefix') . "employees WHERE username='".$data['email']."' AND status='1'";
        $q = $this->db->query($sql);
        $user_result = $q->result_array();
        if (count($user_result) > 0)
        {
		   // Simplified login query - avoid complex JOIN that might be causing issues
		   $new_sql = "Select * from ".$this->config->item('db_prefix')."employees WHERE username='".$data['email']."' AND password ='".md5($data['password'])."' AND status='1'";
	 	   $new_q = $this->db->query($new_sql); 
		   $affected_rows = $new_q->result_array();
		   if (count($affected_rows) > 0)
	       {
	            if(isset($data['rememberme']))
				{
					setcookie( "femail", $data['email'], time() + 36000 );
					setcookie( "lpsswrd", $data['password'], time() + 36000 );
					setcookie( "rememberme", 'yes', time() + 36000 );
				}else{
					setcookie( "femail", '', time() + 36000 );
					setcookie( "lpsswrd", '', time() + 36000 );
					setcookie( "rememberme", '', time() + 36000 );
				}
				unset($_SESSION['logged_administrator']);unset($_SESSION['logged_accountant']);unset($_SESSION['logged_stock_manager']);
				unset($_SESSION['logged_billing_manager']);unset($_SESSION['logged_central_stock_manager']);unset($_SESSION['logged_counselor']);
				
				$role = $affected_rows[0]['role'];
				if($role == 'administrator'){
					$_SESSION['logged_administrator'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number']);
				}
				if($role == 'accountant'){
					$center = $this->get_center($affected_rows[0]['username']);
					$_SESSION['logged_accountant'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => isset($center['center_number']) ? $center['center_number'] : 0, 'center_type' => isset($center['type']) ? $center['type'] : '');
				}
				if($role == 'stock_manager'){
					$center = $this->get_center($affected_rows[0]['username']);
					$_SESSION['logged_stock_manager'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => isset($center['center_number']) ? $center['center_number'] : 0, 'center_type' => isset($center['type']) ? $center['type'] : '', 'department' => $affected_rows[0]['department']);
				}
				if($role == 'billing_manager'){
					$center = $this->get_center($affected_rows[0]['username']); 
					$_SESSION['logged_billing_manager'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => isset($center['center_number']) ? $center['center_number'] : 0, 'center_type' => isset($center['type']) ? $center['type'] : '', 'allow_discount_rs' => $affected_rows[0]['allow_discount_rs'], 'allow_discount_us' => $affected_rows[0]['allow_discount_us'], 'department' => $affected_rows[0]['department']);
				}
				if($role == 'telecaller'){
					$center = $this->get_center($affected_rows[0]['username']); 
					$_SESSION['logged_telecaller'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => isset($center['center_number']) ? $center['center_number'] : 0, 'center_type' => isset($center['type']) ? $center['type'] : '');
				}
				if($role == 'central_stock_manager'){
					$center = $this->get_center($affected_rows[0]['username']);
					$_SESSION['logged_central_stock_manager'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => 0);
				}
				
				if($role == 'investigator_manager'){
					$center = $this->get_center($affected_rows[0]['username']);
					$_SESSION['logged_investigation_manager'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => 0);
				}
				if($role == 'counselor'){
					$center = $this->get_center($affected_rows[0]['username']);
					$_SESSION['logged_counselor'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => isset($center['center_number']) ? $center['center_number'] : 0);
				}
				if($role == 'embryologist'){
					$center = $this->get_center($affected_rows[0]['username']);
					$_SESSION['logged_embryologist'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => isset($center['center_number']) ? $center['center_number'] : 0);
				}
				if($role == 'liason'){
					$center = $this->get_center($affected_rows[0]['username']);
					$_SESSION['logged_liason'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => 0);
				}
				if($role == 'mrd'){
					$center = $this->get_center($affected_rows[0]['username']);
					$_SESSION['logged_mrd'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => 0);
				}
				if($role == 'viewer'){
					$center = $this->get_center($affected_rows[0]['username']);
					$_SESSION['logged_viewer'] = array('name'=>$affected_rows[0]['name'], 'username'=>$affected_rows[0]['username'], 'email'=>$affected_rows[0]['email'], 'role'=>$affected_rows[0]['role'], 'employee_number'=>$affected_rows[0]['employee_number'], 'center' => 0);
				}
				$result = array('status' => 1);
            	return $result;	
			}else{
				$result = array('status' => 0);
            	return $result;		
			}
        }
        else
        {
			$result = array('status' => 0);
            return $result;		
        }
	}
	
	function get_center($username){
		$result = array();
		$sql = "SELECT a.center_name, a.center_number, a.type FROM ".config_item('db_prefix')."centers as a, ".config_item('db_prefix')."employees as b WHERE a.center_number = b.center_id and b.username='".$username."'";
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
	
	function get_week_month_data($filter_value, $filter_type){
	
		if($filter_value == 'weekly'){
			$next_day = date('Y-m-d', strtotime('+1 day')); 
			$week_end = date("Y-m-d H:i:s", strtotime($next_day));
			$week_start = date("Y-m-d", strtotime("-8 day", strtotime($week_end)));
			$week_start = date("Y-m-d H:i:s", strtotime($week_start));
		}
		if($filter_value == 'monthly'){
			$next_day = date('Y-m-d', strtotime('+1 day')); 
			$week_end = date("Y-m-d H:i:s", strtotime($next_day));
			$week_start = date("Y-m-d", strtotime("-31 day", strtotime($week_end)));
			$week_start = date("Y-m-d H:i:s", strtotime($week_start));
		}
		
		$sql = "Select * from " . $this->config->item('db_prefix') .$filter_type;
		
		//echo $sql; die;
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

	function user_details($user_id){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from " . $this->config->item('db_prefix') . "subscribers WHERE userid='".$user_id."'";
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
	
	public function update_profile($user, $userid)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "subscribers SET ";
		foreach( $user as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE userid = '".$userid."'";
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
	
	public function update_password($new_password, $userid)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "subscribers SET `password`='".md5($new_password)."' WHERE userid='".$userid."'";
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

	
	function checkuser($username)
    {
		$result = array();
		$sql_condition = '';
		$sql = "Select * from " . $this->config->item('db_prefix') . "subscribers WHERE username='".$username."'";
        $q = $this->db->query($sql);
        $user_result = $q->result_array();
        if (count($user_result) > 0)
        {
			$new_password = $this->generate_random_password();
			$new_sql = "UPDATE " . config_item('db_prefix') . "subscribers SET `password`='".md5($new_password)."' WHERE username='".$username."'";
			$this->db->query($new_sql);
			$affected_rows = $this->db->affected_rows();
			if ($affected_rows > 0)
	        {
				$email = $user_result[0]['email'];
				$firstname = $user_result[0]['firstname'];
				$result = array('new_password' => $new_password, 'status' => 1, 'email' => $email, 'firstname' => $firstname);
            	return $result;	
			}else{
				$result = array('new_password' => '', 'status' => 0);
            	return $result;		
			}
        }
        else
        {
				$result = array('new_password' => '', 'status' => 0);
            	return $result;		
        }
	}
	
	function generate_random_password($length = 10) 
	{	
		$smallalpha = range('a','z');
    	$alphabets = range('A','Z');
	    $numbers = range('0','9');
	    $additional_characters = array('_','#');
    	$final_array = array_merge($smallalpha,$alphabets,$numbers,$additional_characters);
         
	    $password = '';
  
    	while($length--)
		{
      		$key = array_rand($final_array);
      		$password .= $final_array[$key];
    	}
	    return $password;
	}	
	

	//OPTIONS

	function setting_data(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from " . $this->config->item('db_prefix') . "options WHERE ID='1'";
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

	function update_setting_data($conversion_rate){
		$new_sql = "UPDATE ".config_item('db_prefix')."options SET `conversion_rate`='".$conversion_rate."' WHERE ID='1'";
		$this->db->query($new_sql);
		return $this->db->affected_rows();
	}
	
	
	function change_password($username, $password){
    	$new_sql = "UPDATE ".config_item('db_prefix')."employees SET `password`='".$password."' WHERE username='$username'";
		$this->db->query($new_sql);
		return $this->db->affected_rows();
	}
}

/* End of file Menu_model.php */
/* Location: ./application/modules/contact/models/Menu_model.php */