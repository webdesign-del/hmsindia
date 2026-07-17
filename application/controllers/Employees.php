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
			if(isset($_POST['action']) && $_POST['action'] == 'add_item'){
				unset($_POST['action']);
				$email = $_POST['email'];
				$check = $this->check_employee($email);
				if($check > 0){
					header("location:" .base_url(). "employees/add?m=".base64_encode('Employee with '.$email.' email already exits !').'&t='.base64_encode('error'));
					die();
				}else{
					
					// --- यहाँ से हमारा नया डायनामिक परमिशन लॉजिक शुरू होता है ---
					if(isset($_POST['allowed_centers']) && is_array($_POST['allowed_centers'])){
						// एरे [1, 3, 5] को स्ट्रिंग "1,3,5" में बदल देगा
						$_POST['allowed_centers'] = implode(',', $_POST['allowed_centers']);
					} else {
						// अगर एडमिन ने कोई चेकबॉक्स नहीं चुना, तो खाली सेव होगा (यानी कोई स्विचिंग परमिशन नहीं)
						$_POST['allowed_centers'] = '';
					}
					// --- नया लॉजिक समाप्त ---

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

        if(isset($_POST['action']) && $_POST['action'] == 'update_item'){
            unset($_POST['action']);
            
            // --- यहाँ से हमारा नया डायनामिक एडिट परमिशन लॉजिक शुरू होता है ---
            if(isset($_POST['allowed_centers']) && is_array($_POST['allowed_centers'])){
                // एरे को स्ट्रिंग "1,3,4" में कन्वर्ट करेगा
                $_POST['allowed_centers'] = implode(',', $_POST['allowed_centers']);
            } else {
                // अगर एडमिन ने सारे टिक हटा दिए, तो खाली स्ट्रिंग सेव होगी (यानी कोई स्विचिंग परमिशन नहीं)
                $_POST['allowed_centers'] = '';
            }
            // --- नया लॉजिक समाप्त ---

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

			$user_role = $logg['role']; // लॉग-इन यूजर का रोल

			// कर्मचारी का पूरा डेटा लाएं (ताकि हमें allowed_centers का पता चल सके)
			$employee_info = $this->employee_model->get_employee_center_data($item_id);
			$allowed_centers_string = isset($employee_info['allowed_centers']) ? $employee_info['allowed_centers'] : '';

			if(isset($_POST['action']) && $_POST['action'] == 'update_center'){
				unset($_POST['action']);
				
				// सुरक्षा चेक (Backend Security): अगर एडमिन नहीं है, तो चेक करें कि सबमिट किया गया center_id अलाउड लिस्ट में है या नहीं
				if($user_role != 'admin'){
					$allowed_array = explode(',', $allowed_centers_string);
					if(!in_array($_POST['center_id'], $allowed_array)){
						header("location:" . base_url() . "employees/edit_center?m=" . base64_encode('Invalid center selection!').'&t='.base64_encode('error').'&employee_number='.$item_id);
						die();
					}
				}

				$res = $this->employee_model->update_employee_center($_POST, $item_id);
				
				if($res > 0){
					// Session destroy और logout तभी करें जब कोई कर्मचारी खुद अपना सेंटर बदले। 
					// अगर एडमिन किसी और का बदल रहा है, तो एडमिन को लॉगआउट नहीं करना चाहिए।
					// मान लेते हैं कि $logg में लॉग-इन यूजर की ID 'id' या 'employee_number' में है
					$logged_in_id = isset($logg['employee_number']) ? $logg['employee_number'] : (isset($logg['id']) ? $logg['id'] : 0);
					
					if($user_role != 'admin' || $logged_in_id == $item_id) {
						$this->session->sess_destroy();
						header("location:" . base_url() . "?m=" . base64_encode('Center updated successfully! You have been logged out.').'&t='.base64_encode('success'));
						die();
					} else {
						// अगर एडमिन ने बदला है तो वापस एम्प्लॉई लिस्ट या इसी पेज पर भेजें (बिना लॉगआउट किए)
						header("location:" . base_url() . "employees?m=" . base64_encode('Employee center updated successfully!').'&t='.base64_encode('success'));
						die();
					}
				} else {
					header("location:" .base_url(). "employees/edit_center?m=".base64_encode('Update failed or no changes were made.').'&t='.base64_encode('error').'&employee_number='.$item_id);
					die();
				}  
			}

			// --- डायनामिक सेंटर्स फ़िल्टरिंग लॉजिक ---
			$all_centers = $this->employee_model->get_employee_center();
			
			if($user_role == 'admin') {
				// एडमिन को सारे सेंटर्स का ऑप्शन दिखेगा
				$data['centers'] = $all_centers;
			} else {
				// सामान्य कर्मचारी को सिर्फ वही सेंटर्स दिखेंगे जो कॉमा से सेपरेटेड लिस्ट में अलाउड हैं
				$allowed_array = array_filter(explode(',', $allowed_centers_string));
				$filtered_centers = array();
				
				foreach($all_centers as $center) {
					if(in_array($center['center_number'], $allowed_array)) {
						$filtered_centers[] = $center;
					}
				}
				$data['centers'] = $filtered_centers;
			}

			$data['data'] = $employee_info;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('employees/edit_center', $data);
			$this->load->view($template['footer']);
		} else {
			header("location:" .base_url(). "");
			die();
		}
	}

		public function edit_doctor_center()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			// 1. लॉग-इन यूजर का रोल या ID निकालें (जैसे $logg['user_id'] या $logg['role'])
			$logged_in_user_id = $logg['user_id']; 
			$user_role = $logg['role'];

			// 2. परमिशन चेक: क्या इस यूजर को सेंटर बदलने की परमिशन है?
			// (आप अपने मॉडल में 'check_user_permission' नाम का फंक्शन बना सकते हैं)
			$has_permission = $this->employee_model->check_user_permission($logged_in_user_id, 'can_switch_center');
			
			if(!$has_permission && $user_role != 'admin'){ // अगर एडमिन नहीं है और परमिशन भी नहीं है
				header("location:" . base_url() . "dashboard?m=" . base64_encode('You do not have permission to change centers.').'&t='.base64_encode('error'));
				die();
			}

			$data = array();
			if(isset($_GET['ID'])){ $item_id = $_GET['ID']; }
			if(isset($_POST['ID'])) { $item_id = $_POST['ID']; }

			if(isset($_POST['action']) && $_POST['action'] == 'update_doctor_center'){
				unset($_POST['action']);
				
				// 3. बैकएंड सुरक्षा: सबमिट किए गए center_id को चेक करें कि क्या यूजर को इसका हक है?
				$allowed_centers = $this->employee_model->get_allowed_centers_by_user($logged_in_user_id);
				$allowed_center_ids = array_column($allowed_centers, 'center_number');
				
				if(!in_array($_POST['center_id'], $allowed_center_ids) && $user_role != 'admin'){
					header("location:" . base_url() . "employees/edit_doctor_center?m=" . base64_encode('Invalid center selection!').'&t='.base64_encode('error').'&ID='.$item_id);
					die();
				}

				$res = $this->employee_model->update_doctor_center($_POST, $item_id);
				if($res > 0){
					$this->session->sess_destroy();
					header("location:" . base_url() . "doctor-login?m=" . base64_encode('Doctor Center updated successfully! You have been logged out.').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "employees/edit_doctor_center?m=".base64_encode('Update failed or no changes were made.').'&t='.base64_encode('error').'&ID='.$item_id);
					die();
				}  
			}

			// 4. डायनामिक सेंटर्स: सिर्फ वही सेंटर्स लाएं जो इस यूजर को अलाउड हैं
			if($user_role == 'admin') {
				$data['centers'] = $this->employee_model->get_employee_center(); // एडमिन को सब दिखेगा
			} else {
				$data['centers'] = $this->employee_model->get_allowed_centers_by_user($logged_in_user_id); // नॉर्मल यूजर को सिर्फ अलाउड सेंटर्स
			}

			$data['data'] = $this->employee_model->get_doctor_center_data($item_id);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('employees/edit_doctor_center', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

} 