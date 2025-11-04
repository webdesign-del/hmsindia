<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class Center_model extends CI_Model
{
	function get_centers(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."centers where status='1'  ORDER by ID DESC";
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
		try {
			$table_name = $this->config->item('db_prefix') . 'centers';
			$table_exists = $this->db->table_exists($table_name);
			if (!$table_exists) {
				log_message('error', 'Table ' . $table_name . ' does not exist');
				return 0;
			}
			$fields = $this->db->list_fields($table_name);
			$filtered_data = array();
			foreach ($data as $key => $value) {
				$field_name = $key;
				$field_mappings = array(
					'center_location' => 'center_address',
					'upload_photo_1' => 'upload_photo_1'
				);
				if (isset($field_mappings[$key])) {
					$field_name = $field_mappings[$key];
				}
				if ($key === 'ID' || $key === 'id' || $field_name === 'ID' || $field_name === 'id') {
					log_message('debug', 'Skipping ID field: ' . $key);
					continue;
				}
				if (in_array($field_name, $fields)) {
					$filtered_data[$field_name] = $value;
				} else {
					log_message('warning', 'Field ' . $key . ' (mapped to ' . $field_name . ') not found in table ' . $table_name);
				}
			}
			$filtered_data['add_date'] = date("Y-m-d H:i:s");
			$filtered_data['center_number'] = getGUID();
			if (!isset($filtered_data['status'])) {
				$filtered_data['status'] = '1';
			}
			$required_fields = ['center_name', 'type', 'center_address'];
			foreach ($required_fields as $field) {
				if (!isset($filtered_data[$field]) || empty($filtered_data[$field])) {
					log_message('error', 'Required field missing or empty: ' . $field);
					return 0;
				}
			}
			$result = $this->db->insert($table_name, $filtered_data);
			if ($result) {
				$insert_id = $this->db->insert_id();
				return $insert_id;
			} else {
				$error = $this->db->error();
				if (isset($error['code'])) {
					switch ($error['code']) {
						case 1062: // Duplicate entry
							log_message('error', 'Duplicate entry error - likely duplicate center_number');
							break;
						case 1366: // Incorrect integer value
							log_message('error', 'Incorrect integer value - check field types');
							break;
						case 1452: // Cannot add or update a child row
							log_message('error', 'Foreign key constraint failure');
							break;
						default:
							log_message('error', 'Unknown database error code: ' . $error['code']);
					}
				}
				return 0;
			}
		} catch (Exception $e) {
			log_message('error', 'Exception in add_item: ' . $e->getMessage());
			log_message('error', 'Exception trace: ' . $e->getTraceAsString());
			return 0;
		}
	}
	
	function get_item_data($item){
		try {
			$result = array();
			$table_name = $this->config->item('db_prefix') . 'centers';
			if (!$this->db->table_exists($table_name)) {
				return array();
			}
			$sql = "SELECT * FROM " . $table_name . " WHERE center_number = ?";
			$q = $this->db->query($sql, array($item));
			$result = $q->result_array();
			if (!empty($result)) {
				$data = $result[0];
				if (!isset($data['center_address']) && isset($data['center_location'])) {
					$data['center_address'] = $data['center_location'];
				}
				if (!isset($data['center_classification']) || empty($data['center_classification'])) {
					$data['center_classification'] = 'hub'; // Default to hub
				}
				return $data;
			} else {
				return array();
			}
		} catch (Exception $e) {
			return array();
		}
	}
	
	public function update_item_data($data, $item)
    {
        // try {
            $table_name = $this->config->item('db_prefix') . 'centers';
            $table_exists = $this->db->table_exists($table_name);
            if (!$table_exists) {
                return 0;
            }
            $fields = $this->db->list_fields($table_name);
            $filtered_data = array();
            foreach ($data as $key => $value) {
                $field_name = $key;
                $field_mappings = array(
                    'center_location' => 'center_address',
                    'upload_photo_1' => 'upload_photo_1'
                );
                if (isset($field_mappings[$key])) {
                    $field_name = $field_mappings[$key];
                }
                // Skip ID field completely to avoid primary key conflicts
                if ($key === 'ID' || $key === 'id' || $field_name === 'ID' || $field_name === 'id') {
                    continue;
                }
                if (in_array($field_name, $fields)) {
                    $filtered_data[$field_name] = $value;
                } else {
                    log_message('warning', 'Field ' . $key . ' (mapped to ' . $field_name . ') not found in table ' . $table_name);
                }
            }
            $this->db->where('center_number', $item);
            $result = $this->db->update($table_name, $filtered_data);
            if ($result) {
                $affected_rows = $this->db->affected_rows();
                return $affected_rows;
            } else {
                $error = $this->db->error();
                return 0;
            }
        // } catch (Exception $e) {
        //     return 0;
        // }
    }
	
	public function delete_item_data($item){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "centers WHERE center_number = '".$item."'";
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return 1;
		}
		else
			return 0;	
	}
	
	function get_center_by_id($center_id){
		$result = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."centers where ID='".$center_id."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0];
        }
        else
        {
            return array();
        }
	}

	public function get_center_number(){
		$result = array();
		$sql = "Select center_number from ".$this->config->item('db_prefix')."centers ";
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

	public function get_center_code(){
		$result = array();
		$sql = "Select center_number from ".$this->config->item('db_prefix')."centers ";
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

	function get_center_name_by_code($center_id){
		$sql = "Select * from ".$this->config->item('db_prefix')."centers where center_number='$center_id'";
		$q = $this->db->query($sql);
		$result = $q->result_array();
		if (!empty($result)) {
			return $result[0];
		} else {
			return array();
		}
	}
}
// END Stock_model class

/* End of file Stock_model.php */
/* Location: ./application/models/Stock_model.php */