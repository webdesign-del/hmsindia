<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class Vendors_model extends CI_Model
{
	function get_vendors(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."vendors ORDER by ID DESC";
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
	
	function get_vendors_list(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."vendors where status='1' ORDER by ID DESC";
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
	
	function add_vendor($data){
		//var_dump($sql);die;
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "vendors` SET ";
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
	
	function get_vendor_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."vendors where ID='".$item."'";
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
	
	public function update_vendor_data($data, $item)
    {	
        $sql = "UPDATE " . config_item('db_prefix') . "vendors SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$item."'";
        $this->db->query($sql);
        return 1;
    }
	
	public function delete_vendor_data($item){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "vendors WHERE ID = '".$item."'";
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return 1;
		}
		else
			return 0;	
	}
	
	function vendor_fees($id){
		$sql = "Select fees from ".$this->config->item('db_prefix')."vendors where ID='".$id."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['fees'];
        }
        else
        {
            return $result;
        }
	}

	function get_vendor_data_by_vendor_number($vendor_number){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."vendors where vendor_number='".$vendor_number."'";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		return $result;
	}

	function get_vendor_name_by_vendor_id($vendor_id)
	{
		$this->db->select('name,vendor_number,company_name,gst_no');
		$this->db->from($this->config->item('db_prefix') . 'vendors');
		$this->db->where('ID', $vendor_id);
		$query = $this->db->get();
     
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

}
// END Stock_model class

/* End of file Stock_model.php */
/* Location: ./application/models/Stock_model.php */