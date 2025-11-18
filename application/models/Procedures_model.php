<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class Procedures_model extends CI_Model
{
	function get_procedures() {
		$sql = "SELECT p.*, 
					   GROUP_CONCAT(c.center_name SEPARATOR ', ') as center_names
				FROM " . $this->config->item('db_prefix') . "procedures p
				LEFT JOIN " . $this->config->item('db_prefix') . "centers c 
				  ON FIND_IN_SET(c.center_number, p.center_id)
				GROUP BY p.id
				ORDER BY p.id DESC";
	
		$q = $this->db->query($sql);
		return $q->result_array();
	}
	
	// function get_procedures(){
	// 	$result = array();
	// 	$sql_condition = '';
	// 	$sql = "Select * from ".$this->config->item('db_prefix')."procedures ORDER by ID DESC";
    //     $q = $this->db->query($sql);
    //     $result = $q->result_array();
    //     if (!empty($result))
    //     {
    //         return $result;
    //     }
    //     else
    //     {
    //         return $result;
    //     }
	// }
	
	function get_procedures_list(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."procedures where parent_id='0' and status='1' ORDER by ID DESC";
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
	
	function add_procedure($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "procedures` SET ";
		$sqlArr = array();
		foreach($data as $key => $value){
			if(is_array($value)){
				$value = implode(",", $value);
			}
			$sqlArr[] = " $key = '".$this->db->escape_str($value)."'";
		}
		$sql .= implode(',', $sqlArr);
		$res = $this->db->query($sql);
		if ($res){
			return $this->db->insert_id();
		} else {
			return 0;
		}
	}
	
	
	function get_parent_procedure($procedure){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."procedures where ID='".$procedure."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		
        if (count($result) > 0)
        {
            return $result[0];
        }
        else
        {
            return 0;
        }
	}
	
	function get_procedure_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."procedures where ID='".$item."'";
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
	
	function get_procedure_form_relationships($procedure_id){
		$result = array();
		$sql = "Select form_id from ".$this->config->item('db_prefix')."form_relationship where procedure_id='".$procedure_id."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            $form_ids = array();
            foreach($result as $row){
                $form_ids[] = $row['form_id'];
            }
            return implode(',', $form_ids);
        }
        else
        {
            return '';
        }
	}
	
	public function update_procedure_data($data, $item)
	{	
		$sql = "UPDATE " . config_item('db_prefix') . "procedures SET ";	
		$sqlArr = [];
		foreach ($data as $key => $value) {
			if ($key == 'center_id' && is_array($value)) {
				$value = implode(',', $value);
			}
			if ($key == 'procedure_form' && is_array($value)) {
				$value = implode(',', $value);
			}
			$sqlArr[] = " $key = '" . addslashes($value) . "'";
		}
		$sql .= implode(', ', $sqlArr);
		$sql .= " WHERE id = '" . intval($item) . "'";
		$this->db->query($sql);
		return 1;
	}
	
	
	public function delete_procedure_data($item){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "procedures WHERE ID = '".$item."'";
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return 1;
		}
		else
			return 0;	
	}
	
	/*********** IDs **************/
	
	function get_ids(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."ids ORDER by ID DESC";
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
	
	function add_id($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "ids` SET ";
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
	
	function get_id_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."ids where ID='".$item."'";
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
		
	public function update_id_data($data, $item)
    {	
        $sql = "UPDATE " . config_item('db_prefix') . "ids SET ";
		$sqlArr = [];
		foreach( $data as $key=> $value )
		{
			if (is_array($value)) {
				$value = implode(',', $value);
			}
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$item."'";
        $this->db->query($sql);
        return 1;
    }
	
	public function delete_id_data($item){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "ids WHERE ID = '".$item."'";
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return 1;
		}
		else
			return 0;	
	}
	
	/*********** IDs **************/

	/*function embryologist_records(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where procedure_billed='1'";
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
	}*/
	
	function patient_embryologist_count($wife_name, $patient_id){
		$embryologist_result = array();
		$conditions = '';
		if (!empty($wife_name)){
			$conditions .= " and wife_name='$wife_name'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		/*$embryologist_sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where procedure_billed='1' and procedure_suggestion='1' and 1".$conditions."";
		$q = $this->db->query($embryologist_sql);
		return $q->num_rows();*/
		
		$embryologist_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1 ".$conditions." and status='approved'";
		$q = $this->db->query($embryologist_sql);
		return $q->num_rows();
	}

	function patient_embryologist_list_patination($limit, $page, $wife_name, $patient_id){
		$embryologist_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($wife_name)){
			$conditions .= " and wife_name='$wife_name'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		/*$embryologist_sql = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where procedure_billed='1' and procedure_suggestion='1' and 1".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$embryologist_q = $this->db->query($embryologist_sql);
		$embryologist_result = $embryologist_q->result_array();
		return $embryologist_result;*/
		
		$embryologist_sql = "Select * from ".$this->config->item('db_prefix')."patient_procedure where 1".$conditions." and status='approved' order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$embryologist_q = $this->db->query($embryologist_sql);
		$embryologist_result = $embryologist_q->result_array();
		return $embryologist_result;
	}

	/**Procedure Forms */
	
	function get_procedures_forms(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."procedure_forms where status='active'";
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

	function get_procedures_form_list(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."procedure_forms";
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

	function insert_procedure_forms($data){		
		$sql = "";
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "procedure_forms` SET ";
		$sqlArr = array();
		foreach( $data as $ky=> $val )
		{
			$sqlArr[] = " $ky = '".addslashes($val)."'";
		}
		$sql .= implode(',' , $sqlArr);
		$res =  $this->db->query($sql);
		return 1;
	}

	function get_form_data($item_id){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."procedure_forms where ID='$item_id'";
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

	function update_procedure_form($data, $item_id){
		$sql = "UPDATE " . config_item('db_prefix') . "procedure_forms SET ";
		$sqlArr = [];
		foreach( $data as $key=> $value )
		{
			if (is_array($value)) {
				$value = implode(',', $value);
			}
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$item_id."'";
        $this->db->query($sql);
        return 1;
	}

	function insert_form_relation($data, $procedure){
		foreach( $data['procedure_form'] as $ky=> $val )
		{
			$sql = "";
			$sql = "INSERT INTO `".$this->config->item('db_prefix')."form_relationship` (`procedure_id`, `form_id`) VALUES ('$procedure','$val')";
			$res =  $this->db->query($sql);
		}
		return 1;

	}

	function update_form_relations($data, $procedure){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "form_relationship WHERE procedure_id = '".$procedure."'";
		$res =  $this->db->query($sql);
		if ($res)
		{
		    if(!empty($data['procedure_form'])){
    			foreach( $data['procedure_form'] as $ky=> $val )
    			{
    				$sql = "";
    				$sql = "INSERT INTO `".$this->config->item('db_prefix')."form_relationship` (`procedure_id`, `form_id`) VALUES ('$procedure','$val')";
    				$res =  $this->db->query($sql);
    			}
		    }
			return 1;
		}
		else
			return 0;
	}
	
	function export_form_relations(){

	$form_relationship_result = $response = array();
	$conditions = '';
	$procedure_sql = "Select * from ".$this->config->item('db_prefix')."form_relationship where 1 $conditions";
	$procedure_q = $this->db->query($procedure_sql);
	$form_relationship_result = $procedure_q->result_array();
		foreach($form_relationship_result as $key => $val){
			
			$response[] = array(
					'procedure_id' => $val['procedure_id'],
					'form_id' => $val['form_id'],
			);
		}
		return $response;
	}
	
	function form_relationship_count(){
		$embryologist_result = array();
		$conditions = '';
		$embryologist_sql = "Select * from ".$this->config->item('db_prefix')."form_relationship where 1 ".$conditions."";
		$q = $this->db->query($embryologist_sql);
		return $q->num_rows();
	}

	function form_relationship_patination($limit, $page){
		$embryologist_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		$embryologist_sql = "Select * from ".$this->config->item('db_prefix')."form_relationship where 1".$conditions." limit ". $limit." OFFSET ".$offset."";
		$embryologist_q = $this->db->query($embryologist_sql);
		$embryologist_result = $embryologist_q->result_array();
		return $embryologist_result;
	}
	
	function insert_package($data) {
    foreach ($data['procedure_id'] as $procedure_id) {  // Loop through each procedure_id
        $package_name = addslashes($data['package_name']); // Prevent SQL injection
        $status = (int) $data['status']; // Convert status to integer
        
        // Build the SQL query
        $sql = "INSERT INTO `".$this->config->item('db_prefix')."procedure_package` 
                (`package_name`, `procedure_id`, `status`) 
                VALUES ('$package_name', '$procedure_id', '$status')";
        // Execute query
        $res = $this->db->query($sql);
    }
		return 1; // Return success
	}
	
	function get_package_list(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."procedure_package ORDER by ID DESC";
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
	
	function update_procedure_package($data, $procedure){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "procedure_package WHERE procedure_id = '".$procedure."'";
		$res =  $this->db->query($sql);
		if ($res)
		{
		    if(!empty($data['procedure_form'])){
    			foreach ($data['procedure_id'] as $procedure_id) {  // Loop through each procedure_id
					$package_name = addslashes($data['package_name']); // Prevent SQL injection
					$status = (int) $data['status']; // Convert status to integer
        
					$sql = "INSERT INTO `".$this->config->item('db_prefix')."procedure_package` (`package_name`, `procedure_id`, `status`) VALUES ('$package_name', '$procedure_id', '$status')";
					$res = $this->db->query($sql);
				}
		    }
			
			return 1;
		}
		else
			return 0;
	}
	
	function get_package_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."procedure_package where package_id='".$item."'";
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
	
	function get_procedure_package_list(){
		$result = array();
		$sql_condition = '';
		$sql = "SELECT package_name, GROUP_CONCAT(procedure_id ORDER BY procedure_id ASC) AS procedure_ids FROM hms_procedure_package GROUP BY package_name";
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

	function get_procedures_category() {
		$sql = "SELECT DISTINCT category FROM hms_procedures";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		return $result;
	}

	function get_origin_procedures() {
		$sql = "SELECT DISTINCT procedures FROM hms_procedures";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		return $result;
	}

	function get_booked_status() {
		$sql = "SELECT DISTINCT booked_status FROM hms_patient_procedure";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		return $result;
	}

	function get_broad_procedure() {
		$sql = "SELECT DISTINCT broad_procedure FROM hms_procedures";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		return $result;
	}

}
// END Stock_model class

/* End of file Stock_model.php */
/* Location: ./application/models/Stock_model.php */