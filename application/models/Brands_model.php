<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class Brands_model extends CI_Model
{
	function get_brands(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from medicine_brands ORDER by id DESC";
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
	
	function get_brands_list(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from medicine_brands where status='1' ORDER by id DESC";
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
	
	function add_brand($data){
		$sql = "INSERT INTO `medicine_brands` SET ";
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
	
	function get_brand_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from medicine_brands where id='".$item."'";
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
	
	public function update_brand_data($data, $item)
    {	
        $sql = "UPDATE medicine_brands SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE id = '".$item."'";
        $this->db->query($sql);
        return 1;
    }
	
	public function delete_brand_data($item){
		$sql = "DELETE FROM medicine_brands WHERE id = '".$item."'";
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return 1;
		}
		else
			return 0;	
	}
}