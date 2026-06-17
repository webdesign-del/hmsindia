<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Procedures extends CI_Controller {

	public function __construct()
	{
		// Load parent's constructor.
       	parent::__construct();
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
	    $this->load->library('session');
		$this->load->model('procedures_model');
		$this->load->helper('myhelper');
		$this->load->library("pagination");
	}	
	
	public function procedures()
	{
		
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['upload_procedures']) && isset($_POST['upload_procedures']) && $_POST['upload_procedures'] == 'upload_procedures'){
				$fileTmpPath = $_FILES['procedures_list']['tmp_name'];
				$fileName = $_FILES['procedures_list']['name'];
				$fileSize = $_FILES['procedures_list']['size'];
				$fileType = $_FILES['procedures_list']['type'];
				$fileNameCmps = explode(".", $fileName);
				$fileExtension = strtolower(end($fileNameCmps));
				$allowedfileExtensions = array('csv');
				if (in_array($fileExtension, $allowedfileExtensions)) {
					$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
					$destination = $this->config->item('upload_path');
						if ($_FILES["procedures_list"]["size"] > 0) {
							$file = fopen($fileTmpPath, "r");
							$count = 0;
							while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
								if($count > 0){
									$sqlinsert = "INSERT INTO `".$this->config->item('db_prefix')."procedures`(`procedure_name`, `code`, `parent_id`, price, `usd_price`,`status`) VALUES('".$column[0]."','".$column[1]. "','".$column[2]. "','".$column[3]."','".$column[4]."','1')";									
									$res =  $this->db->query($sqlinsert);
								}
							 $count++;
							}
							if ($res)
							{
								header("location:" .base_url(). "procedures?m=".base64_encode('Procedure uploaded successfully !').'&t='.base64_encode('success'));
								die();
							}
							else{
								header("location:" .base_url(). "procedures?m=".base64_encode('something went wrong !').'&t='.base64_encode('success'));
								die();
							}
						}
				}else{
					header("location:" .base_url(). "procedures?m=".base64_encode('File format not supported !').'&t='.base64_encode('success'));
					die();
				}
			}
			$data = array();
			$data['data'] = $this->procedures_model->get_procedures();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/procedures', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function parent_procedures()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->procedures_model->get_procedures_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/parent-procedures', $data);
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
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_procedure'){
				unset($_POST['action']);
				$procedure_form = $post_arr = array();
				$post_arr['procedure_name'] = $_POST['procedure_name'];unset($_POST['procedure_name']);
				$post_arr['category'] = $_POST['category'];unset($_POST['category']);
				$post_arr['center_id'] = $_POST['center_id'];unset($_POST['center_id']);
				$post_arr['procedure_form'] = $_POST['procedure_form'];unset($_POST['procedure_form']);
				$post_arr['discharge_form'] = $_POST['discharge_form'];unset($_POST['discharge_form']);
				$post_arr['price'] = $_POST['price'];
				$post_arr['usd_price'] = round($_POST['price']/get_converstion_rate(), 2);unset($_POST['price']); 
				$post_arr['code'] = $_POST['code'];unset($_POST['code']);
				$post_arr['procedures'] = $_POST['procedures'];unset($_POST['procedures']);
				$post_arr['broad_procedure'] = $_POST['broad_procedure'];unset($_POST['broad_procedure']);
				$post_arr['broad_procedure_count'] = $_POST['broad_procedure_count'];unset($_POST['broad_procedure_count']);
				$post_arr['parent_id'] = $_POST['parent_id'];unset($_POST['parent_id']);
				$post_arr['code_type'] = $_POST['code_type'];unset($_POST['code_type']);
				$post_arr['package_id'] = $_POST['package_id'];unset($_POST['package_id']);
				$post_arr['status'] = $_POST['status'];unset($_POST['status']);
				
				// Store procedure_form before it gets unset
				$procedure_form_data = $post_arr['procedure_form'];
				$discharge_form_data = $post_arr['discharge_form'];
				
				$data = $this->procedures_model->add_procedure($post_arr);
				if($data > 0){
				    if(!empty($procedure_form_data)){
					    $insert_procedure_forms = $this->procedures_model->insert_form_relation(array('procedure_form' => $procedure_form_data), $data);
				    }
					if(!empty($discharge_form_data)){
					    $insert_discharge_forms = $this->procedures_model->insert_dischargeform_relation(array('discharge_form' => $discharge_form_data), $data);
				    }
					header("location:" .base_url(). "procedures/add?m=".base64_encode('Procedure added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "procedures/add?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$data['procedures'] = $this->procedures_model->get_procedures();
			$data['procedure_forms'] = $this->procedures_model->get_procedures_forms();
			$data['discharge_forms'] = $this->procedures_model->get_discharge_forms();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			
			$this->load->view('procedures/add_procedure', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
/*	public function edit()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			
			if(isset($_GET['id'])){
				if(isset($_GET['id'])){ $item_id = $_GET['id']; }
				
				if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_procedure'){
					unset($_POST['action']);unset($_POST['id']);
					
					$procedure_form = $post_arr = array();
					$discharge_form = $post_arr = array();
					$post_arr['procedure_name'] = $_POST['procedure_name'];unset($_POST['procedure_name']);
					$post_arr['category'] = $_POST['category'];unset($_POST['category']);
					$post_arr['center_id'] = $_POST['center_id'];unset($_POST['center_id']);
					$post_arr['procedure_form'] = $_POST['procedure_form'];
					$post_arr['discharge_form'] = $_POST['discharge_form'];
					$post_arr['price'] = $_POST['price'];
				    $post_arr['usd_price'] = round($_POST['price']/get_converstion_rate(), 2);unset($_POST['price']); 
					$post_arr['code'] = $_POST['code'];unset($_POST['code']);
					$post_arr['procedures'] = $_POST['procedures'];unset($_POST['procedures']);
					$post_arr['broad_procedure'] = $_POST['broad_procedure'];unset($_POST['broad_procedure']);
					$post_arr['broad_procedure_count'] = $_POST['broad_procedure_count'];unset($_POST['broad_procedure_count']);
					$post_arr['status'] = $_POST['status'];unset($_POST['status']);
					
					$data = $this->procedures_model->update_procedure_data($post_arr, $item_id);
					if($data > 0){
    					$update_procedure_form = $this->procedures_model->update_form_relations($_POST, $item_id);
						$update_discharge_form = $this->procedures_model->update_dischargeform_relations($_POST, $item_id);
						header("location:" .base_url(). "procedures?m=".base64_encode('Procedure updated successfully !').'&t='.base64_encode('success').'&id='.$item_id);
						die();
					}else{
						header("location:" .base_url(). "procedures/edit?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&id='.$item_id);
						die();
					}				
				}
				$data['data'] = $this->procedures_model->get_procedure_data($item_id);
				$data['data']['procedure_form'] = $this->procedures_model->get_procedure_form_relationships($item_id);
				$data['data']['discharge_form'] = $this->procedures_model->get_discharge_form_relationships($item_id);
				$data['procedures'] = $this->procedures_model->get_procedures();
				$data['procedure_forms'] = $this->procedures_model->get_procedures_forms();
				$data['discharge_forms'] = $this->procedures_model->get_discharge_forms();
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('procedures/edit_procedure', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "procedures");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
*/

public function edit()
    {
        $logg = checklogin();
        if($logg['status'] == true){
            $data = array();
            
            if(isset($_GET['id'])){
                $item_id = $_GET['id'];
                
                if(isset($_POST['action']) && $_POST['action'] == 'update_procedure'){
                    unset($_POST['action']); unset($_POST['id']);
                    
                    // [FIX 1] 'procedure_form' को सुरक्षित चेक करें और ऐरे को कॉमा-सेपरेटेड स्ट्रिंग में बदलें
                    $p_form_string = '';
                    if(!empty($_POST['procedure_form']) && is_array($_POST['procedure_form'])){
                        $p_form_string = implode(',', $_POST['procedure_form']);
                    }
                    
                    // [FIX 2] 'discharge_form' को सुरक्षित चेक करें और ऐरे को कॉमा-सेपरेटेड स्ट्रिंग में बदलें
                    $d_form_string = '';
                    if(!empty($_POST['discharge_form']) && is_array($_POST['discharge_form'])){
                        $d_form_string = implode(',', $_POST['discharge_form']);
                    }

                    // रिलेशनशिप फंक्शंस को भेजने के लिए $_POST का एक बैकअप बना लें
                    // क्योंकि नीचे unset() करने से $_POST खाली हो रहा था
                    $original_post = $_POST;
                    
                    $post_arr = array();
                    $post_arr['procedure_name'] = $_POST['procedure_name']; unset($_POST['procedure_name']);
                    $post_arr['category'] = $_POST['category']; unset($_POST['category']);
                    $post_arr['center_id'] = $_POST['center_id']; unset($_POST['center_id']);
                    
                    // यहाँ अब ऐरे के बजाय सुरक्षित स्ट्रिंग जाएगी (addslashes एरर खत्म)
                    $post_arr['procedure_form'] = $p_form_string;
                    $post_arr['discharge_form'] = $d_form_string;
                    
                    $post_arr['price'] = $_POST['price'];
                    $post_arr['usd_price'] = round($_POST['price']/get_converstion_rate(), 2); unset($_POST['price']); 
                    $post_arr['code'] = $_POST['code']; unset($_POST['code']);
                    $post_arr['procedures'] = $_POST['procedures']; unset($_POST['procedures']);
                    $post_arr['broad_procedure'] = $_POST['broad_procedure']; unset($_POST['broad_procedure']);
                    $post_arr['broad_procedure_count'] = $_POST['broad_procedure_count']; unset($_POST['broad_procedure_count']);
                    $post_arr['status'] = $_POST['status']; unset($_POST['status']);
                    
                    $data = $this->procedures_model->update_procedure_data($post_arr, $item_id);
					if($data > 0){
                        // [FIX 3] यहाँ $_POST की जगह $original_post भेजा गया है ताकि रिलेशनशिप टेबल्स में पूरा डेटा मिले
                        $update_procedure_form = $this->procedures_model->update_form_relations($original_post, $item_id);
                        $update_discharge_form = $this->procedures_model->update_dischargeform_relations($original_post, $item_id);
                        
                        header("location:" .base_url(). "procedures?m=".base64_encode('Procedure updated successfully !').'&t='.base64_encode('success').'&id='.$item_id);
                        die();
                    }else{
                        header("location:" .base_url(). "procedures/edit?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&id='.$item_id);
                        die();
                    }               
                }
                $data['data'] = $this->procedures_model->get_procedure_data($item_id);
                $data['data']['procedure_form'] = $this->procedures_model->get_procedure_form_relationships($item_id);
                $data['data']['discharge_form'] = $this->procedures_model->get_discharge_form_relationships($item_id);
                $data['procedures'] = $this->procedures_model->get_procedures();
                $data['procedure_forms'] = $this->procedures_model->get_procedures_forms();
                $data['discharge_forms'] = $this->procedures_model->get_discharge_forms();
                $template = get_header_template($logg['role']);
                $this->load->view($template['header']);
                $this->load->view('procedures/edit_procedure', $data);
                $this->load->view($template['footer']);
            }else{
                header("location:" .base_url(). "procedures");
                die();
            }
        }else{
            header("location:" .base_url(). "");
            die();
        }
    }

	public function delete()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_GET['id'])){	
				$item = $_GET['id'];
				if( $item > 0 )
				{
					if( $this->procedures_model->delete_procedure_data($item) !== 0)
					{
						header("location:" .base_url(). "procedures?m=".base64_encode('Item deleted successfully !').'&t='.base64_encode('success'));
						die();
					}
					else
					{
						header("location:" .base_url(). "procedures?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
						die();
					}
				}
				header("location:" .base_url(). "procedures?m=".base64_encode('Item not found !').'&t='.base64_encode('error'));
				die();
			}else{
				header("location:" .base_url(). "procedures");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/*********** IDs **************/
	public function ids()
	{
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$data['data'] = $this->procedures_model->get_ids();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/ids', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function add_id()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_id'){
				unset($_POST['action']);
				$data = $this->procedures_model->add_id($_POST);
				if($data > 0){
					header("location:" .base_url(). "procedures/add_id?m=".base64_encode('ID added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "procedures/add_id?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/add_id', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function edit_id()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['id'])){
				if(isset($_GET['id'])){ $item_id = $_GET['id']; }
				if(isset($_POST['id'])) { $item_id = $_POST['id']; }
				
				if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_id'){
					unset($_POST['action']);
					
					$data = $this->procedures_model->update_id_data($_POST, $item_id);
					if($data > 0){
						header("location:" .base_url(). "procedures/edit_id?m=".base64_encode('ID updated successfully !').'&t='.base64_encode('success').'&id='.$item_id);
						die();
					}else{
						header("location:" .base_url(). "procedures/edit_id?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&id='.$item_id);
						die();
					}				
				}
				$data['data'] = $this->procedures_model->get_id_data($item_id);
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('procedures/edit_id', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "ids");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function delete_id()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_GET['id'])){	
				$item = $_GET['id'];
				if( $item > 0 )
				{
					if( $this->procedures_model->delete_id_data($item) !== 0)
					{
						header("location:" .base_url(). "procedures/ids?m=".base64_encode('ID deleted successfully !').'&t='.base64_encode('success'));
						die();
					}
					else
					{
						header("location:" .base_url(). "procedures/ids?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
						die();
					}
				}
				header("location:" .base_url(). "procedures/ids?m=".base64_encode('ID not found !').'&t='.base64_encode('error'));
				die();
			}else{
				header("location:" .base_url(). "procedures/ids");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/*********** IDs **************/
	
	/*public function embryologist_records(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->procedures_model->embryologist_records();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/embryologist_records', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}*/
	
	public function embryologist_records(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$wife_name = $this->input->get('wife_name', true);
			$patient_id = $this->input->get('patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "procedures/embryologist_records";
        	$config["total_rows"] = $this->procedures_model->patient_embryologist_count($wife_name, $patient_id);
        	$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['embryologist_result'] = $this->procedures_model->patient_embryologist_list_patination($config["per_page"], $per_page, $wife_name, $patient_id);
			$data["wife_name"] = $wife_name;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/embryologist_records', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function get_parent_procedure($procedure){
		$data = $this->procedures_model->get_parent_procedure($procedure);
		return $data;
	}

	/** Procedure Forms */

		
	public function forms()
	{
		$logg = checklogin();
		if($logg['status'] == true){	
			$data = array();
			$data['data'] = $this->procedures_model->get_procedures_form_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/procedures-forms', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function add_form()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_procedure_form'){
				unset($_POST['action']);				
				$_POST['form_area'] = $_POST['form_name'];				
				$insert_procedure_forms = $this->procedures_model->insert_procedure_forms($_POST);
				if($insert_procedure_forms > 0){
					header("location:" .base_url(). "procedures/add_form?m=".base64_encode('Form added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "procedures/add_form?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/add-procedure-form');
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function edit_form()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			
			if(isset($_GET['id'])){
				if(isset($_GET['id'])){ $item_id = $_GET['id']; }				
				if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_procedure_form'){
					unset($_POST['action']);
									
					$data = $this->procedures_model->update_procedure_form($_POST, $item_id);
					if($data > 0){
						header("location:" .base_url(). "procedures/edit_form?m=".base64_encode('Form updated successfully !').'&t='.base64_encode('success').'&id='.$item_id);
						die();
					}else{
						header("location:" .base_url(). "procedures/edit_form?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&id='.$item_id);
						die();
					}				
				}
				$data['data'] = $this->procedures_model->get_form_data($item_id);
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('procedures/edit-procedure-form', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "procedures/forms");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function form_relationship(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$export_form = $this->input->get('export-form', true);
			
			if (isset($export_form)){
				$data = $this->procedures_model->export_form_relations();
				//var_dump($data);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Form-Relation.csv');
				$fp = fopen('php://output','w');
				$headers = 'Procedure Name,Status, Form Name, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
				
					$sql = "SELECT * FROM hms_procedures WHERE ID=".$val['procedure_id']."";
					$select_result = run_select_query($sql);
					$name = $select_result['procedure_name'];
					$pstatus = $select_result['status'];
					
					$sql2 = "SELECT * FROM hms_procedure_forms WHERE ID=".$val['form_id']."";
					$select_result2 = run_select_query($sql2);
					$form_name = $select_result2['form_name'];
					$fstatus = $select_result2['status'];
					
					$lead_arr = array( $name, $pstatus, $form_name, $fstatus);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "procedures/form_relationship";
        	$config["total_rows"] = $this->procedures_model->form_relationship_count();
        	$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['form_relationship_result'] = $this->procedures_model->form_relationship_patination($config["per_page"], $per_page);
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/form_relationship', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
	/****** Package ******/
	
	public function package()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['data'] = $this->procedures_model->get_package_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/package', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function add_package()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_package'){
				unset($_POST['action']);
				$procedures = $post_arr = array();
				$post_arr['package_name'] = $_POST['package_name'];unset($_POST['package_name']);
				$post_arr['procedure_id'] = $_POST['procedure_id'];unset($_POST['procedure_id']);
				$post_arr['status'] = $_POST['status'];unset($_POST['status']);
				$data = $this->procedures_model->insert_package($post_arr);
				if($data > 0){
				    header("location:" .base_url(). "procedures/add_package?m=".base64_encode('Package added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "procedures/add_package?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$data['procedures'] = $this->procedures_model->get_procedures();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/add_package', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function edit_package()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			
			if(isset($_GET['package_id'])){
				if(isset($_GET['package_id'])){ $item_id = $_GET['package_id']; }
				
				if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_package'){
					unset($_POST['action']);unset($_POST['package_id']);
					
					$procedures = $post_arr = array();
					$post_arr['package_id'] = $_POST['package_id'];unset($_POST['package_id']);
					$post_arr['package_name'] = $_POST['package_name'];unset($_POST['package_name']);
					$post_arr['procedure_id'] = $_POST['procedure_id'];unset($_POST['procedure_id']);
					$post_arr['status'] = $_POST['status'];unset($_POST['status']);
					$data = $this->procedures_model->update_procedure_package($post_arr);
					if($data > 0){
    					header("location:" .base_url(). "procedures/package?m=".base64_encode('Package updated successfully !').'&t='.base64_encode('success').'&id='.$item_id);
						die();
					}else{
						header("location:" .base_url(). "procedures/edit_package?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&id='.$item_id);
						die();
					}				
				}
				$data['data'] = $this->procedures_model->get_package_data($item_id);
				$data['procedures'] = $this->procedures_model->get_procedures();
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('procedures/edit_package', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "procedures");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function opu_donor(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/opu_donor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function outcome_donor(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/outcome_donor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function serum_bete_hcg_on_donor(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/serum_bete_hcg_on_donor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function trigger_module_donor(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/trigger_module_donor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function ovulation_induction_protocol_donor(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/ovulation_induction_protocol_donor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function embryo_transfer_donor(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/embryo_transfer_donor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function oocyte_embryo_record_sheet_till_d3_donor(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/oocyte_embryo_record_sheet_till_d3_donor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function initial_details_donor(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('procedures/initial_details_donor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	

	// LIST VIEW
   
	public function procedure_list(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$template = get_header_template($logg['role']);
			$data['list'] = $this->procedures_model->get_all_min_prices();
			$this->load->view($template['header']);
			$this->load->view('procedures/procedure_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

    // ADD / EDIT FORM
    public function procedure_form($id = null) {
    // 1. Login Check
    $logg = checklogin();
    if ($logg['status'] == false) {
        redirect(base_url()); // CodeIgniter style redirect
        die();
    }

    $data = array();
    $data['procedure'] = null;

    // 2. Fetch Data if Edit Mode
    if ($id) {
        $data['procedure'] = $this->procedures_model->get_min_price_by_id($id);
        if (!$data['procedure']) {
            $this->session->set_flashdata('error', 'Record not found');
            redirect('procedures/procedure_list'); // Apni list wali method ka naam likhein
        }
    }

    // 3. Handle Form Submission
    if ($this->input->post()) {
        $save_data = array(
            'procedure_id'   => $this->input->post('procedure_id'),
            'procedure_name' => $this->input->post('procedure_name'),
            'code'           => $this->input->post('code'),
            'min_price'      => $this->input->post('min_price'),
            'actual_price'   => $this->input->post('actual_price'),
            'status'         => $this->input->post('status')
        );

        if ($id) {
            $this->procedures_model->update_min_price($id, $save_data);
            $this->session->set_flashdata('msg', 'Updated Successfully');
        } else {
            $this->procedures_model->insert_min_price($save_data);
            $this->session->set_flashdata('msg', 'Added Successfully');
        }
        
        // Save hone ke baad list par wapas bhejien (Duplicate entry rokne ke liye)
        redirect('procedures/procedure_list'); 
    }

    // 4. Load Views
    $template = get_header_template($logg['role']);
    $this->load->view($template['header']);
    $this->load->view('procedures/procedure_form', $data);
    $this->load->view($template['footer']);
}

public function manage_procedure_prices() {
    $logg = checklogin();
    if($logg['status'] == true) {
        // Fetch the master procedures - Note: 'ID' is uppercase based on your SQL
        $data['master_procedures'] = $this->db->get('hms_procedures')->result();
        
        // Fetch existing entries
        $data['existing_prices'] = $this->db->get('hms_procedure_min_prices')->result();

        $template = get_header_template($logg['role']);
        $this->load->view($template['header']);
        $this->load->view('procedures/procedure_prices_view', $data);
        $this->load->view($template['footer']);
    } else {
        redirect(base_url());
    }
}

public function save_procedure_price() {
    $logg = checklogin();
    if($logg['status'] == true) {
        $data = array(
            'procedure_id'   => $this->input->post('procedure_id'),
            'procedure_name' => $this->input->post('procedure_name'),
            'code'           => $this->input->post('code'),
            'min_price'      => $this->input->post('min_price'),
            'actual_price'   => $this->input->post('actual_price'),
            'status'         => 1, // Default to Active
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s')
        );

        $this->db->insert('hms_procedure_min_prices', $data);
        $this->session->set_flashdata('success', 'New price entry added successfully!');
        redirect('procedures/manage_procedure_prices');
    }
}


} 