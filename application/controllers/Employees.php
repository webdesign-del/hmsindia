<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller {

	public function __construct()
	{
		// Load parent's constructor.
       	parent::__construct();
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
	    $this->load->library('session');
		$this->load->model('employee_model');
		$this->load->helper('myhelper');
	}	
	
	public function employees()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->employee_model->get_employees();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('employees/employees', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function add()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_item'){
				unset($_POST['action']);
				$email = $_POST['email'];
				$check = $this->check_employee($email);
				if($check > 0){
					header("location:" .base_url(). "employees/add?m=".base64_encode('Employee with '.$email.' email already exits !').'&t='.base64_encode('error'));
					die();
				}else{
					$data = $this->employee_model->add_item($_POST);
					if($data > 0){
						header("location:" .base_url(). "employees/add?m=".base64_encode('Employee added successfully !').'&t='.base64_encode('success'));
						die();
					}else{
						header("location:" .base_url(). "employees/add?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
						die();
					}
				}		
			}
			$data['centers'] = $this->employee_model->get_centers();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('employees/add_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	function check_employee($email){
		$check = $this->employee_model->get_employee_details($email);
		return $check;
	}
	
	public function edit()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['employee_number'])){ $item_id = $_GET['employee_number']; }
			if(isset($_POST['employee_number'])) { $item_id = $_POST['employee_number']; }

			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_item'){
				unset($_POST['action']);
				$data = $this->employee_model->update_item_data($_POST, $item_id);
				if($data > 0){
					header("location:" .base_url(). "employees/edit?m=".base64_encode('Employee updated successfully !').'&t='.base64_encode('success').'&employee_number='.$item_id);
					die();
				}else{
					header("location:" .base_url(). "employees/edit?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&employee_number='.$item_id);
					die();
				}				
			}
			$data['centers'] = $this->employee_model->get_centers();
			$data['data'] = $this->employee_model->get_item_data($item_id);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('employees/edit_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function delete()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$item = $_GET['employee_number'];
			if( $item > 0 )
			{
				if( $this->employee_model->delete_item_data($item) !== 0)
				{
					header("location:" .base_url(). "employees?m=".base64_encode('Employee deleted successfully !').'&t='.base64_encode('success'));
					die();
				}
				else
				{
					header("location:" .base_url(). "employees?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			header("location:" .base_url(). "employees?m=".base64_encode('Item not found !').'&t='.base64_encode('error'));
			die();
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function get_center($center){
		$data = $this->employee_model->get_center_data($center);
		return $data;
	}


	public function edit_center()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['employee_number'])){ $item_id = $_GET['employee_number']; }
			if(isset($_POST['employee_number'])) { $item_id = $_POST['employee_number']; }

			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_center'){
				unset($_POST['action']);
				$data = $this->employee_model->update_employee_center($_POST, $item_id);
				/*if($data > 0){
					header("location:" .base_url(). "employees/edit_center?m=".base64_encode('Employee updated successfully !').'&t='.base64_encode('success').'&employee_number='.$item_id);
					die();
				}else{
					header("location:" .base_url(). "employees/edit_center?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&employee_number='.$item_id);
					die();
				}*/
				
				if($data > 0){
                // 2. DATA UPDATE SUCCESSFUL
                
                // --- LOGOUT IMPLEMENTATION ---
                // Destroy the user session
                $this->session->sess_destroy();

                // Redirect the user to the login page (or any desired page)
                // Use the base URL or your specific login controller route
                header("location:" . base_url() . "?m=" . base64_encode('Employee data updated successfully! You have been logged out.').'&t='.base64_encode('success'));
                die();
                // --- LOGOUT IMPLEMENTATION END ---
                
            }else{
                // Update failed or no rows affected
                header("location:" .base_url(). "employees/edit_center?m=".base64_encode('Update failed or no changes were made.').'&t='.base64_encode('error').'&employee_number='.$item_id);
                die();
            }  


			}
			$data['centers'] = $this->employee_model->get_employee_center();
			$data['data'] = $this->employee_model->get_employee_center_data($item_id);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('employees/edit_center', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

} 