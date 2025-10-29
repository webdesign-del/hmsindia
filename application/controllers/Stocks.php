<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocks extends CI_Controller {

	public function __construct()
	{
		// Load parent's constructor.
       	parent::__construct();
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
	    $this->load->library('session');
		$this->load->model('order_model');
		$this->load->model('stock_model');
		$this->load->model('vendors_model');
		$this->load->model('billings_model');
		$this->load->helper('myhelper');
		$this->load->library("pagination");
	}

	/**ADD PRODUCTS**/

	public function stock_products()
	{
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$data['data'] = $this->stock_model->get_all_stock_product();
			//var_dump($this->stock_model->get_stock_products);die;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/products/stock_products', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function stock_product_add()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_single_product'){
				unset($_POST['action']);
				$type = $_POST['type'];
				$solid = array('Capsule', 'Tablet');
				$liquid = array('Injection', 'Cyrup');

				if(in_array($type, $solid)){
					$_POST['product_type'] = "solid";
				}else if(in_array($type, $liquid)){
					$_POST['product_type'] = "liquid";
				}
				$data = $this->stock_model->stock_product_add($_POST);
				//var_dump($this->stock_model->stock_product_add($_POST));die;
				if($data > 0){
					header("location:" .base_url(). "products?m=".base64_encode('Product added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "add-product?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$data['medicines'] = $this->stock_model->get_medicine_name();
			$this->load->view($template['header']);
			$this->load->view('stocks/products/stock_product_add', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function stock_product_edit()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['id'])){ $item_id = $_GET['id']; }
			if(isset($_POST['id'])) { $item_id = $_POST['id']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_products'){
				unset($_POST['action']);
				$type = $_POST['type'];
				$solid = array('Capsule', 'Tablet');
				$liquid = array('Injection', 'Cyrup');

				if(in_array($type, $solid)){
					$_POST['product_type'] = "solid";
				}else if(in_array($type, $liquid)){
					$_POST['product_type'] = "liquid";
				}

				$data = $this->stock_model->update_stock_product_data($_POST, $item_id);
				if($data > 0){
					header("location:" .base_url(). "edit-product?m=".base64_encode('Product updated successfully !').'&t='.base64_encode('success').'&id='.$item_id);
					die();
				}else{
					header("location:" .base_url(). "edit-product?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&id='.$item_id);
					die();
				}				
			}
			$data['data'] = $this->stock_model->get_stock_product_data($item_id);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/products/stock_product_edit', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function stock_medicine_add()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_medicines'){
				unset($_POST['action']);
				$type = $_POST['type'];
				$data = $this->stock_model->stock_medicine_add($_POST);
				//var_dump($this->stock_model->stock_product_add($_POST));die;
				if($data > 0){
					header("location:" .base_url(). "add-product?m=".base64_encode('Product added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "add-medicine?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$data['vendors'] = $this->vendors_model->get_vendors_list();
			$data['brands'] = $this->stock_model->get_brands_list();
			$this->load->view($template['header']);
			$this->load->view('stocks/products/stock_medicine_add', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function stock_medicine_edit()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['ID'])){ $ID = $_GET['ID']; }
			if(isset($_POST['ID'])) { $ID = $_POST['ID']; }

			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_medicine_products'){
				unset($_POST['action']);
				$name = $_POST['name'];
				$company = $_POST['company'];
				$generic_name = $_POST['generic_name'];
				$pack_size = $_POST['pack_size'];
				$hsn = $_POST['hsn'];
				$gstrate = $_POST['gstrate'];
				$gstdivision = $_POST['gstdivision'];
				$vendor_price = $_POST['vendor_price'];
				$mrp = $_POST['mrp'];
				$vendor_number = $_POST['vendor_number'];
				$brand_number = $_POST['brand_number'];
				$data = $this->stock_model->update_stock_medicine_data($_POST, $ID, $name, $company, $generic_name, $pack_size, $hsn, $gstrate, $gstdivision, $vendor_price, $mrp, $vendor_number, $brand_number);
				if($data > 0){
					header("location:" .base_url(). "edit-medicine?ID=".$ID."m=".base64_encode('Product updated successfully !').'&t='.base64_encode('success').'&id='.$item_id);
					die();
				}else{
					header("location:" .base_url(). "edit-medicine?ID=".$ID."m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&id='.$item_id);
					die();
				}				
			}
			$data['data'] = $this->stock_model->get_stock_medicine_data($ID);
			$data['vendors'] = $this->vendors_model->get_vendors_list();
			$data['brands'] = $this->stock_model->get_brands_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/products/stock_medicine_edit', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function stock_medicine(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$name = $this->input->get('name', true);
			$vendor_number = $this->input->get('vendor_number', true);
			$brand_number = $this->input->get('brand_number', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_medicine($name, $vendor_number, $brand_number);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Medicine-List-'.$start_date.'-'.$end_date.'-'.date("Y-m-d H-i-s").'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Name, Company,Generic Name, Vendor Name, Brand Name, HSN, GST, Pack Size, Vendor Price, MRP, Status, Add Date';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {
				    
				    $sql2 = "SELECT * FROM hms_vendors WHERE vendor_number='" . $val['vendor_number'] . "'";
					$select_result2 = run_select_query($sql2);
					$vendor_name = $select_result2['name'];
					
					$sql = "SELECT * FROM hms_brands WHERE brand_number='" . $val['brand_number'] . "'";
					$select_result = run_select_query($sql);
					$brand_name = $select_result['name'];
					
					$lead_arr = array($val['name'],  $val['company'], $val['generic_name'], $vendor_name, $brand_name, $val['hsn'], $val['gstrate'], $val['pack_size'],  $val['vendor_price'], $val['mrp'],  $val['status'], $val['add_date']);
					fputcsv($fp, $lead_arr);
				}
				
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/stock_medicine";
        	$config["total_rows"] = $this->stock_model->get_medicine_count($name, $vendor_number, $brand_number);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['product_discard'] = $this->stock_model->get_medicine_patination($config["per_page"], $per_page, $name, $vendor_number, $brand_number);
			$data["name"] = $name;
			$data["vendor_number"] = $vendor_number;
			$data["brand_number"] = $brand_number;
			$data['vendors'] = $this->vendors_model->get_vendors_list();
			$data['brands'] = $this->stock_model->get_brands_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/products/stock_medicine', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	


	function stock_product_brands($product){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'assign_brands'){
				unset($_POST['action']);

				$data = $this->stock_model->stock_product_brands($_POST, $product);
				if($data > 0){
					header("location:" .base_url(). "product-brands/$product?m=".base64_encode('Product assigned successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "product-brands/$product?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$data['product'] = $this->stock_model->get_stock_product_data($product);
			$data['brands'] = $this->stock_model->get_brands_list();
			$data['prodcut_brands'] = $this->stock_model->get_product_brands($product);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/products/stock_product_brands', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	function stock_product_vendors(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$data['data'] = $this->stock_model->get_stock_product_vendors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/products/stock_product_vendors', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function product_vendor_add(){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'product_vendor_add'){
				unset($_POST['action']);
				//var_dump($_POST);die;
				$data = $this->stock_model->product_vendor_add($_POST);
				if($data > 0){
					header("location:" .base_url(). "product-vendors?m=".base64_encode('Vendor assigned successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "product-vendors?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			//$data['medicine'] = $this->stock_model->get_medicines();
			$data['products'] = $this->stock_model->get_all_stock_product();
			$data['vendors'] = $this->vendors_model->get_vendors_list();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/products/product_vendor_add', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	function product_vendor_edit($id){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'product_vendor_update'){
				unset($_POST['action']);
				//var_dump($_POST);die;
				$data = $this->stock_model->product_vendor_update($_POST, $id);
				if($data > 0){
					header("location:" .base_url(). "product-vendors?m=".base64_encode('Product Vendor updated successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "product-vendors?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$data['data'] = $this->stock_model->get_product_vendor_data($id);
			$data['products'] = $this->stock_model->get_stock_products();
			$data['vendors'] = $this->vendors_model->get_vendors_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/products/product_vendor_edit', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	function ajax_product_brands(){
		$response = array(); $brand_html = '';
		$brand_html = '<option value="">-- Select --</option>';
		$product_id = $_POST['product_id'];
		$brands = $this->stock_model->get_product_brands($product_id);
		if(!empty($brands)){
			$selected_brands = "";
			if(isset($_POST['mode']) && !empty($_POST['mode']) && $_POST['mode'] == "edit"){
				$selected_brands = $_POST['brand_number'];
			}

			$product_info = $this->stock_model->get_stock_product_data($product_id);
			foreach($brands as $key => $val){
				$brand_name = $selected = "";
				$brand_name = $this->stock_model->get_brand_name($val['brand_number']);
				if($val['brand_number'] == $selected_brands){
					$selected = "selected='selected'";
				}
				$brand_html .= '<option value="'.$val['brand_number'].'" '.$selected.'>'.$brand_name.'</option>';
			}

			$response = array('brands' => $brand_html, 'product_info' => 'Type: '.$product_info['type'].', Consumption Unit: '.$product_info['consumption_unit'].'');		
		}else{
			$response = array('brands' => $brand_html, 'product_info' => 'Product Brands not assigned!');		
		}
		echo json_encode($response);
		die;
	}

	function ajax_product_brand_vendor(){
		$product_id = $_POST['product_id'];
		$brand_number = $_POST['brand_number'];
		$response = array(); $vendor_html = '';
		$vendor_html = '<option value="">-- Select --</option>';

		$vendors = $this->stock_model->get_product_brand_vendor($product_id, $brand_number);
		if(!empty($vendors)){
			foreach($vendors as $key => $val){
				$vendor_name = "";
				$vendor_name = $this->stock_model->get_vendor_name($val['vendor_number']);
				$vendor_html .= '<option value="'.$val['vendor_number'].'">'.$vendor_name.'</option>';
			}
			$response = array('vendors' => $vendor_html);
		}else{
			$response = array('vendors' => $vendor_html);
		}
		echo json_encode($response);
		die;
	}

	function ajax_product_vendor_data(){
		$product_id = $_POST['product_id'];
		$brand_number = $_POST['brand_number'];
		$vendor_number = $_POST['vendor_number'];
		$batch_number = $_POST['batch_number'];
		$pack_size = $_POST['pack_size'];
		$hsn = $_POST['hsn'];
		$vendor_price = $_POST['vendor_price'];
		$gstrate = $_POST['gstrate'];
		$gstdivision = $_POST['gstdivision'];
		
		$product_vendor_data = $this->stock_model->get_product_vendor_info($product_id, $brand_number, $vendor_number, $batch_number, $pack_size, $hsn, $vendor_price, $gstrate, $gstdivision);
		if(!empty($product_vendor_data)){
			echo json_encode(array('product_vendor_data' => $product_vendor_data, 'status' => 1));
			die;
		}else{
			echo json_encode(array('product_vendor_data' => array(), 'status' => 0));
			die;
		}
	}


	public function add()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_item'){
				unset($_POST['action']);
				$item_number = $_POST['item_number'];
				$invoice_no = $_POST['invoice_no'];
				$no_of_item = $_POST['no_of_item'];
				$company = $_POST['company'];
				$item_name = $_POST['item_name'];
				$product_id = $_POST['product_id'];
				$brand_name = $_POST['brand_name'];
				$vendor_number = $_POST['vendor_number'];
				$batch_number = $_POST['batch_number'];
				$vendor_price = $_POST['vendor_price'];
				$medicine_type = $_POST['medicine_type'];
				$hsn = $_POST['hsn'];
				$pack_size = $_POST['pack_size'];
				$pack = $_POST['pack'];
				$generic_name = $_POST['generic_name'];
				$gstrate = $_POST['gstrate'];
				$gstdivision = $_POST['gstdivision'];
				$expiry = $_POST['expiry'];
				$expiry_day = $_POST['expiry_day'];
				$mrp = $_POST['mrp'];
				$date_of_purchase = $_POST['date_of_purchase'];
				$quantity = $_POST['quantity'];
				$qty = $_POST['no_of_item'];
				

				$check_center_item = $this->stock_model->check_centeral_item($product_id, $brand_name, $vendor_number, $batch_number, $vendor_price, $hsn, $pack_size, $gstrate, $gstdivision, $expiry);
				if($check_center_item > 0){
					$data = $this->stock_model->update_item($_POST, $product_id, $invoice_no, $no_of_item, $brand_name, $vendor_number, $batch_number, $vendor_price, $hsn, $pack_size, $pack, $gstrate, $gstdivision, $expiry, $mrp, $date_of_purchase,$medicine_type);
				}else{
					$_POST['item_name'] = $this->get_product_name($product_id);
					//$data = $this->stock_model->update_vendor_invoice($ID, $invoice_no, $qty);
					//var_dump($this->stock_model->add_item($_POST));die;
					//unset($_POST['vendor_billing']);
                    
                    unset($_POST['vendor_billing']);
					$data = $this->stock_model->add_item($_POST);

					//$_POST['type'] ="Central Stocks New Entry";
					//var_dump($this->stock_model->add_central_report($_POST, $item_number, $item_name, $batch_number, $expiry, $expiry_day, $add_date, $quantity));die;
					 //12-12-2023 $data = $this->stock_model->add_central_report($_POST, $product_id, $item_name, $batch_number, $expiry, $expiry_day, $add_date, $vendor_price, $mrp, $gstrate, $gstdivision, $quantity);
				
					//$data = $this->stock_model->add_item_central($_POST);
				}
				if($data > 0){
					$vndr_bill = array();
					$vendor_billing = '';
					if(!empty($_FILES['vendor_billing']['tmp_name'])){
						$dest_path = $this->config->item('upload_path');
						$destination = $dest_path.'stock_files/';
						$NewImageName = rand(4,10000)."-".str_replace(" ","_",$_FILES['vendor_billing']['name']);
						$vendor_billing = base_url().'assets/stock_files/'.$NewImageName;
						move_uploaded_file($_FILES['vendor_billing']['tmp_name'], $destination.$NewImageName);
						$vndr_bill['vendor_billing'] = $vendor_billing;
					}
					//$vndr_bill['vendor_billing'] = $_POST['vendor_billing'];
					$vndr_bill['purchase_po_no'] = $_POST['purchase_po_no'];
					$vndr_bill['po_date'] = $_POST['po_date'];
					$vndr_bill['order_number'] = $order_number;
					$vndr_bill['upload_date'] = date("Y-m-d H:i:s");
					$vndr_bill['invoice_no'] = $_POST['invoice_no'];
					$vndr_bill['vendor_name'] = $_POST['vendor_name'];
					$vndr_bill['brand_name'] = $_POST['brand_name'];
					$vndr_bill['mrp'] = $_POST['mrp'];
					$vndr_bill['hsn'] = $_POST['hsn'];
					$vndr_bill['category'] = $_POST['category'];
					$vndr_bill['date_of_purchase'] = $_POST['date_of_purchase'];
					$vndr_bill['batch_number'] = $batch_number;
					$vndr_bill['centre_location'] = $_POST['centre_location'];
					$vndr_bill['received_by'] = $_POST['received_by'];
					$vndr_bill['date_of_receiving'] = $_POST['date_of_receiving'];
					$vndr_bill['item_name'] = $_POST['item_name'];
					$vndr_bill['company'] = $_POST['company'];
					$vndr_bill['quantity'] = $_POST['quantity'];
					$vndr_bill['expiry'] = $_POST['expiry'];
					$vndr_bill['vendor_price'] = $_POST['vendor_price'];
					$vndr_bill['gstrate'] = $_POST['gstrate'];
					$vndr_bill['discount_amt'] = $_POST['discount_amt'];
					$vndr_bill['free_quantity'] = $_POST['free_quantity'];
					$vndr_bill['total_purchase_value_excl_gst'] = $_POST['price'];
					$vndr_bill['freight_forwarding_charges'] = $_POST['freight_forwarding_charges'];
					$vndr_bill['medicine_type'] = $_POST['medicine_type'];
					$vndr_bill['comment'] = $comment;
					$data = $this->order_model->insert_vendor_billing($vndr_bill);
				}	
				if($data > 0){
					header("location:" .base_url(). "stocks/add?m=".base64_encode('Item added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "stocks/add?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$data['categories'] = $this->stock_model->get_categories();
			$data['brands'] = $this->stock_model->get_brands();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$data['vendors'] = $this->vendors_model->get_vendors_list();
			$data['products'] = $this->stock_model->get_stock_products();
			$data['invoices'] = $this->stock_model->get_vendor_invoice();
			$data['medicine'] = $this->stock_model->get_medicines();
			$data['medicines'] = $this->stock_model->get_medicine_name();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/add_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function edit()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['item_number'])){ $item_id = $_GET['item_number']; }
			if(isset($_POST['item_number'])) { $item_id = $_POST['item_number']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_cent_item'){
				unset($_POST['action']);
				$item_number = $_POST['item_number'];
				$company = $_POST['company'];
				$item_name = $_POST['item_name'];
				$generic_name = $_POST['generic_name'];
				$batch_number = $_POST['batch_number'];
				$vendor_price = $_POST['vendor_price'];
				$quantity = $_POST['quantity'];
				$quantity_in = $_POST['quantity_in'];
				$closingstock = (float) $quantity + (float) $quantity_in;
				$expiry = $_POST['expiry'];
				$mrp = $_POST['mrp'];
				$product_id = $_POST['product_id'];
				$vendor_number = $_POST['vendor_number'];
				$brand_name = $_POST['brand_name'];
				$status = $_POST['status'];
				//echo "<pre>";
				//print_r($this->stock_model->update_item_data($_POST, $item_id));
				//echo "</pre>";
				//exit();
				//var_dump($this->stock_model->update_item_data($_POST, $item_id, $company, $generic_name, $batch_number, $mrp, $vendor_price, $quantity_in, $hsn, $pack_size, $gstrate, $gstdivision, $expiry, $status));die;
				$data = $this->stock_model->update_item_data($_POST, $item_id, $company, $item_name, $generic_name, $batch_number, $mrp,$product_id, $vendor_number, $brand_name, $vendor_price, $quantity_in, $hsn, $pack_size, $gstrate, $gstdivision, $expiry, $status);
				if($data > 0){
					//$data = $this->stock_model->update_central_stock_report($_POST, $item_id, $company, $item_name, $batch_number, $expiry, $expiry_day, $add_date, $vendor_price, $quantity_in, $mrp, $gstrate, $gstdivision, $quantity, $closingstock);
					header("location:" .base_url(). "stocks/edit?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&item_number='.$item_id);
					die();
				}else{
					header("location:" .base_url(). "stocks/edit?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&item_number='.$item_id);
					die();
				}				
			}
			$data['data'] = $this->stock_model->get_item_data($item_id);
			$data['categories'] = $this->stock_model->get_categories();
			$data['brands'] = $this->stock_model->get_brands();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$data['vendors'] = $this->vendors_model->get_vendors_list();
			$data['brands'] = $this->stock_model->get_brands_list();
			$data['prodcut_brands'] = $this->stock_model->get_product_brands($product);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/edit_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function audit_stocks()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['ID'])){ $ID = $_GET['ID']; }
			if(isset($_POST['ID'])){ $ID = $_POST['ID']; }
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_audit_item'){
				unset($_POST['action']);
				//$_POST['add_date'] = date("Y-m-d H:i:s");
				$data = $this->stock_model->audit_item_data($_POST, $ID);
				if($data > 0){
					header("location:" .base_url(). "stocks/audit_stocks?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&ID='.$ID);
					die();
				}else{
					header("location:" .base_url(). "stocks/audit_stocks?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&ID='.$ID);
					die();
				}				
			}			
			$data['data'] = $this->stock_model->get_audit_stocks_data($ID);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/audit_stocks', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function audit_report(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$add_date = $this->input->get('add_date', true);
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_audit_report($employee_number, $start_date, $end_date, $add_date, $item_name);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Audit-Report-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Item Name,Batch Number, Quantity, Physical Quantity, Register Quantity, Short Quantity, Excess Quantity, Damage Quantity,Discard, Reason,Item Below Min,Near Expiry,Requisition, Employee Name, Date';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$lead_arr = array($val['item_name'], $val['batch_number'], $val['quantity'], $val['physical_quantity'], $val['register_quantity'], $val['short'], $val['excess'], $val['damage'],$val['discard'],$val['reason'],$val['item_below_min'],$val['near_expiry'],$val['requisition'], $val['employee_number'], $val['add_date']);
					fputcsv($fp, $lead_arr);
				}
				
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/audit_report";
        	$config["total_rows"] = $this->stock_model->get_all_audit_report($employee_number, $start_date, $end_date, $add_date, $item_name, $batch_number);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->get_all_audit_report_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $add_date, $item_name, $batch_number);
			//var_dump($data);die;
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["add_date"] = $add_date;
            $data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/audit_report', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function all_audit_report(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$add_date = $this->input->get('add_date', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_audit_report($employee_number, $start_date, $end_date, $add_date, $item_name);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=All-Center-Medicine-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Item Number,Company, Item Name, Batch Number, Date, Quantity, Safety Stock, Order Qty, Status, Employee Name';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$lead_arr = array($val['item_number'], $val['company'],$val['item_name'], $val['batch_number'], $val['add_date'], $val['quantity'], $val['safety_stock'], $val['order_qty'], $val['status'], $val['employee_number']);
					fputcsv($fp, $lead_arr);
				}
				
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/all_audit_report";
        	$config["total_rows"] = $this->stock_model->get_audit_report($employee_number, $start_date, $end_date, $add_date, $item_name, $batch_number);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->get_audit_report_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $add_date, $item_name, $batch_number);
			//var_dump($data);die;
			$data["employee_number"] = $employee_number;
			$data["add_date"] = $add_date;
            $template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/all_audit_report', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function update_audit_report(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			if(isset($_GET['id'])){ $id = $_GET['id']; }
			if(isset($_POST['id'])) { $id = $_POST['id']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_audit_item'){
				unset($_POST['action']);
				$data = $this->stock_model->update_audit_report($_POST, $id);
				
				if($data > 0){
					header("location:" .base_url(). "stocks/update_audit_report?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&id='.$id);
					die();
				}else{
					header("location:" .base_url(). "stocks/update_audit_report?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&ID='.$ID);
					die();
				}				
			}
			
			$template = get_header_template($logg['role']);
			$data['data'] = $this->stock_model->get_audit_data($id);
			$this->load->view($template['header']);
			$this->load->view('stocks/update_audit_report', $data);
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
			$item = $_GET['item_number'];
			if( $item > 0 )
			{
				if( $this->stock_model->delete_item_data($item) !== 0)
				{
					header("location:" .base_url(). "stocks?m=".base64_encode('Item deleted successfully !').'&t='.base64_encode('success'));
					die();
				}
				else
				{
					header("location:" .base_url(). "stocks?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			header("location:" .base_url(). "stocks?m=".base64_encode('Item not found !').'&t='.base64_encode('error'));
			die();
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function details($item){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = $this->stock_model->item_details($item, 'admin');
//			var_dump($data);die;
			$post_arr = array();
			$post_arr['data'] = $data;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/details', $post_arr);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
		
	/****** ADMIN STOCKS *****/
	
	
	/****** CENTER STOCKS *****/
		public function center_stocks(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$generic_name = $this->input->get('generic_name', true);
			$item_name = $this->input->get('item_name', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_center_stocks($start_date, $end_date, $generic_name, $item_name);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Medicine-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Item Number,Company, Item Name, Batch Number, Brand Name, Vendor Number, Generic Name, Quantity, Safety Stock, Order Qty,Expiry,  Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['item_number'], $val['company'],$val['item_name'], $val['batch_number'], $val['brand_name'], $val['vendor_number'], $val['generic_name'], $val['quantity'], $val['safety_stock'], $val['order_qty'],$val['expiry'], $val['status']);
					fputcsv($fp, $lead_arr);
				}
				
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/center_stocks";
        	$config["total_rows"] = $this->stock_model->get_center_stocks($start_date, $end_date, $generic_name, $item_name);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->get_center_stocks_patination($config["per_page"], $per_page, $start_date, $end_date, $generic_name, $item_name);
			//var_dump($data);die;
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["generic_name"] = $generic_name;
            $data["item_name"] = $item_name;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/center_stocks', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	public function get_item_details(){
		$item_number = $_POST['item_number'];
		$data = $this->stock_model->get_item_details($item_number);
		echo json_encode($data);
		die;
	}

	public function add_center_item()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_center_item'){
				unset($_POST['action']);
				$item_number = $_POST['item_number'];
				$employee_number = $_POST['employee_number'];
				$item_qty = $_POST['quantity'];
				$product_id = $_POST['product_id'];
				$company = $_POST['company'];
				$item_name = $_POST['item_name'];
				$batch_number = $_POST['batch_number'];
				$hsn = $_POST['hsn'];
				$gstrate = $_POST['gstrate'];
				$pack_size = $_POST['pack_size'];
				$gstdivision = $_POST['gstdivision'];
				$brand_name = $_POST['brand_name'];
				$generic_name = $_POST['generic_name'];
				$safety_stock = $_POST['safety_stock'];
				$order_qty = $_POST['order_qty'];
				$category = $_POST['category'];
				$price = $_POST['price'];
				$expiry = $_POST['expiry'];
				$expiry_day = $_POST['expiry_day'];
				$add_date = $_POST['add_date'];
				$center_number = $_POST['center_number'];
				$status = $_POST['status'];
				$vendor_price = $_POST['vendor_price'];
				$mrp = $_POST['mrp'];
				$curruntyquantity = $_POST['curruntyquantity'];
				$_POST['item_number'] = $_POST['item_number'];
				$_POST['add_date'] = date("Y-m-d H:i:s");
				$_POST['update_date'] = date("Y-m-d H:i:s");
				$vendor_number = self::get_vendor_number($_POST['item_number']);
				$_POST['vendor_number'] = $vendor_number;
                $department = $_POST['department'];
				$closingstock = $curruntyquantity - $item_qty;
				//var_dump($this->stock_model->add_central_stock_report($_POST, $item_number, $item_name, $batch_number, $curruntyquantity, $expiry, $expiry_day, $add_date, $item_qty, $employee_number, $closingstock));die;
						
				$data = $this->stock_model->add_central_stock_report($_POST, $invoice_no, $item_number, $item_name, $batch_number, $curruntyquantity, $expiry, $expiry_day, $add_date, $employee_number, $vendor_price, $mrp, $item_qty, $gstrate, $gstdivision, $closingstock, $center_number, $date_of_purchase);
				
				//var_dump($check_center_item = $this->stock_model->check_center_item($item_number));die();
				$check_center_item = $this->stock_model->check_center_item($item_number, $employee_number, $status);
				if(!empty($check_center_item)){
					//if($check_center_item > 0){
				    $data = $this->stock_model->update_center_item_qty($employee_number, $item_number, $item_qty, $status);
					$data = $this->stock_model->add_central_stock_report($_POST, $invoice_no, $item_number, $item_name, $batch_number, $curruntyquantity, $expiry, $expiry_day, $add_date, $employee_number, $vendor_price, $mrp, $item_qty, $gstrate, $gstdivision, $closingstock, $center_number, $date_of_purchase);
				} else{
					    $data = $this->stock_model->add_center_item($_POST, $invoice_no, $item_number, $product_id, $company, $item_name, $batch_number, $hsn, $gstrate, $pack_size, $gstdivision, $brand_name, $vendor_number, $generic_name, $item_qty, $safety_stock, $order_qty, $category, $price, $expiry, $expiry_day, $add_date, $center_number, $status, $employee_number,$department, $vendor_price, $mrp, $date_of_purchase);
				}
				if($data > 0){
					//$data = $this->stock_model->update_central_stock_report($_POST, $item_id, $company, $item_name, $batch_number, $expiry, $expiry_day, $add_date, $vendor_price, $quantity_in, $mrp, $gstrate, $gstdivision, $quantity, $closingstock);
					header("location:" .base_url(). "stocks/add_center_item?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "stocks/add_center_item?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}	
			}
			$data = array();
			$data['categories'] = $this->stock_model->get_categories();
		    $data['item_lists'] = $this->stock_model->get_item_lists();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/add_center_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function add_center_new_item()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_center_new_item'){
				unset($_POST['action']);
				$invoice_no = $_POST['invoice_no'];
				$item_number = $_POST['item_number'];
				$item_qty = $_POST['quantity'];
				$product_id = $_POST['product_id'];
				$company = $_POST['company'];
				$item_name = $_POST['item_name'];
				$batch_number = $_POST['batch_number'];
				$hsn = $_POST['hsn'];
				$gstrate = $_POST['gstrate'];
				$pack_size = $_POST['pack_size'];
				$gstdivision = $_POST['gstdivision'];
				$brand_name = $_POST['brand_name'];
				$generic_name = $_POST['generic_name'];
				$safety_stock = $_POST['safety_stock'];
				$order_qty = $_POST['order_qty'];
				$category = $_POST['category'];
				$price = $_POST['price'];
				$expiry = $_POST['expiry'];
				$expiry_day = $_POST['expiry_day'];
				$add_date = $_POST['add_date'];
				$center_number = $_POST['center_number'];
				$status = $_POST['status'];
				$vendor_price = $_POST['vendor_price'];
				$mrp = $_POST['mrp'];
				$date_of_purchase = $_POST['date_of_purchase'];
				$curruntyquantity = $_POST['curruntyquantity'];
				$_POST['item_number'] = $_POST['item_number'];
				$_POST['add_date'] = date("Y-m-d H:i:s");
				$_POST['update_date'] = date("Y-m-d H:i:s");
				$vendor_number = self::get_vendor_number($_POST['item_number']);
				$_POST['vendor_number'] = $vendor_number;
                $employee_number = $_POST['employee_number'];
				$department = $_POST['department'];
				$closingstock = $curruntyquantity - $item_qty;
				
                $data = $this->stock_model->add_center_new_item($_POST,$invoice_no, $item_number, $product_id, $company, $item_name, $batch_number, $hsn, $gstrate, $pack_size, $gstdivision, $brand_name, $vendor_number, $generic_name, $item_qty, $safety_stock, $order_qty, $category, $price, $expiry, $expiry_day, $add_date, $center_number, $status, $employee_number, $department, $vendor_price, $mrp, $date_of_purchase);
				$data = $this->stock_model->add_central_stock_report($_POST, $invoice_no, $item_number, $item_name, $batch_number, $curruntyquantity, $expiry, $expiry_day, $add_date, $employee_number, $vendor_price, $mrp, $item_qty, $gstrate, $gstdivision, $closingstock, $center_number, $date_of_purchase);
				if($data > 0){
						header("location:" .base_url(). "stocks/add_center_new_item?m=".base64_encode('Item added successfully !').'&t='.base64_encode('success'));
						die();
					}else{
						header("location:" .base_url(). "stocks/add_center_new_item?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
						die();
				}	
			}
			$data = array();
			$data['categories'] = $this->stock_model->get_categories();
		    $data['item_lists'] = $this->stock_model->get_item_lists();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/add_center_new_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
		
	public function edit_center_item()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['ID'])){ $ID = $_GET['ID']; }
			if(isset($_POST['ID'])) { $ID = $_POST['ID']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_center_medicine'){
				unset($_POST['action']);
				$data = $this->stock_model->update_center_item_data($_POST, $ID);
				//var_dump($this->stock_model->update_center_item_data($_POST, $ID));die;	
				if($data > 0){
					header("location:" .base_url(). "stocks/edit_center_item?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&ID='.$ID);
					die();
				}else{
					header("location:" .base_url(). "stocks/edit_center_item?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&ID='.$ID);
					die();
				}				
			}
			$data['ID'] = $ID;
			$data['data'] = $this->stock_model->get_center_item_medicine($ID);
			//$data['categories'] = $this->stock_model->get_categories();
			$data['brands'] = $this->stock_model->get_brands();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/edit_center_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

public function medicine_update()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['ID'])){ $ID = $_GET['ID']; }
			if(isset($_POST['ID'])) { $ID = $_POST['ID']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_medicine'){
				unset($_POST['action']);
				
				$icounte = $mcounte = $ccounte = $spcounte = 1;
				$i_counte = $m_counte = $c_counte = $s_pcounte = array();
				$i_counter = $m_counter = $c_counter = $s_pcounter = array();
				foreach($_POST as $key => $val){
					$pos_c = strpos($key, 'consumables_name_');
					if ($pos_c === false) {} else {
						$cid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$c_counter[] = $cid;
					}	
				}

				if(!empty($c_counter)){
					foreach($c_counter as $key => $ccounte){
						if($_POST['consumables_name_'.$ccounte] == ''){
							unset($_POST['consumables_ID_'.$ccounte]);
							unset($_POST['consumables_serial_'.$ccounte]);
							unset($_POST['consumables_name_'.$ccounte]);
							unset($_POST['consumables_stock_'.$ccounte]);
							unset($_POST['consumables_batch_number_'.$ccounte]);
							unset($_POST['consumables_quantity_'.$ccounte]);
						    unset($_POST['consumables_price_'.$ccounte]);
							unset($_POST['consumables_discount_'.$ccounte]);
							unset($_POST['consumables_total_'.$ccounte]);
							unset($_POST['consumables_vendor_price_'.$ccounte]);
							unset($_POST['consumables_expiry_'.$ccounte]);
						}else{
							$c_counte[] = array('consumables_ID'=> $_POST['consumables_ID_'.$ccounte],'consumables_serial'=> $_POST['consumables_serial_'.$ccounte],'consumables_name'=> $_POST['consumables_name_'.$ccounte],'consumables_stock'=> $_POST['consumables_stock_'.$ccounte],'consumables_batch_number'=> $_POST['consumables_batch_number_'.$ccounte],'consumables_quantity'=> $_POST['consumables_quantity_'.$ccounte],'consumables_price'=> $_POST['consumables_price_'.$ccounte],'consumables_discount_'=> $_POST['consumables_discount_'.$ccounte],'consumables_total_'=> $_POST['consumables_total_'.$ccounte],'consumables_vendor_price'=> $_POST['consumables_vendor_price_'.$ccounte],'consumables_expiry'=> $_POST['consumables_expiry_'.$ccounte]);
						}
					}
				}
								
				$details = array();
				$details['data']['consumables'] = $c_counte;
				$post_arr['data'] = serialize($details);
				//print_r($post_arr);die();
           		$data = $this->stock_model->update_center_item_medicine($_POST, $ID, $post_arr);
				
				//$data = $this->stock_model->update_center_item_medicine($_POST, $ID);
				if($data > 0){
					header("location:" .base_url(). "stocks/medicine_update?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&ID='.$ID);
					die();
				}else{
					header("location:" .base_url(). "stocks/medicine_update?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&ID='.$ID);
					die();
				}				
			}
			$data['ID'] = $ID;
			$data['data'] = $this->stock_model->get_medicine_update($ID);
			$data['consumables'] = $this->stock_model->get_center_consumbles_medicine_list();//die();
			//$data['categories'] = $this->stock_model->get_categories();
			//var_dump($this->stock_model->get_medicine_update($ID));die;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/medicine_update', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	} 	
	
	public function delete_center_item()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$item = $_GET['item_number'];
			if( $item > 0 )
			{
				if( $this->stock_model->delete_center_item_data($item) !== 0)
				{
					header("location:" .base_url(). "stocks/center_stocks?m=".base64_encode('Item deleted successfully !').'&t='.base64_encode('success'));
					die();
				}
				else
				{
					header("location:" .base_url(). "stocks/center_stocks?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			header("location:" .base_url(). "stocks/center_stocks?m=".base64_encode('Item not found !').'&t='.base64_encode('error'));
			die();
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function cdetail($item){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = $this->stock_model->item_details($item, 'center');
//			var_dump($data);die;
			$post_arr = array();
			$post_arr['data'] = $data;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/details', $post_arr);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	/****** CENTER STOCKS *****/
	
	public function add_billing_item(){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_billing_item'){
				unset($_POST['action']);
				
				//$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);
				$post_arr['receipt_number'] = $_POST['receipt_number'];unset($_POST['receipt_number']);
				//$post_arr['center_number'] = $_SESSION['logged_stock_manager']['center'];
				
				//echo '<pre>';
				
				$icounte = $mcounte = $ccounte = $spcounte = 1;
				$i_counte = $m_counte = $c_counte = $s_pcounte = array();
				$i_counter = $m_counter = $c_counter = $s_pcounter = array();
				foreach($_POST as $key => $val){
					$pos = strpos($key, 'injections_name_');
					if ($pos === false) {} else {
						$iid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$i_counter[] = $iid;
					}	
					$pos_m = strpos($key, 'medicine_name_');
					if ($pos_m === false) {} else {
						$mid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$m_counter[] = $mid;
					}	
					
					$pos_c = strpos($key, 'consumables_name_');
					if ($pos_c === false) {} else {
						$cid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$c_counter[] = $cid;
					}	
				}

				// var_dump($_POST); 
				// echo '<br/><br/><br/><br/>';
				// die;

				// var_dump($i_counter); echo '<br/><br/><br/><br/>'; 
				// var_dump($m_counter); echo '<br/><br/><br/><br/>'; 
				// var_dump($c_counter); echo '<br/><br/><br/><br/>'; die;
				if(!empty($i_counter)){
					foreach($i_counter as $key => $icounte){
						if($_POST['injections_name_'.$icounte] == ''){
							unset($_POST['injections_ID_'.$icounte]);
							unset($_POST['injections_serial_'.$icounte]);
							unset($_POST['injections_name_'.$icounte]);
							unset($_POST['injections_item_name_'.$icounte]);
							unset($_POST['injections_stock_'.$icounte]);
							unset($_POST['injections_batch_number_'.$icounte]);
							unset($_POST['injections_quantity_'.$icounte]);
							unset($_POST['injections_price_'.$icounte]);
							unset($_POST['injections_vendor_price_'.$icounte]);
							unset($_POST['injections_mrp_'.$icounte]);
							unset($_POST['injections_hsn_'.$icounte]);
							unset($_POST['injections_expiry_'.$icounte]);
							unset($_POST['injections_gstrate_'.$icounte]);
							unset($_POST['injections_gstdivision_'.$icounte]);
							unset($_POST['injections_date_'.$icounte]);
							unset($_POST['injections_pack_size_'.$icounte]);
						}else{
							$invoice_no = $_POST['injections_invoice_no_'.$icounte];
							$item_number = $_POST['injections_serial_'.$icounte];
							$company = $_POST['injections_company_'.$icounte];
							$item_name = $_POST['injections_item_name_'.$icounte];
							$batch_number = $_POST['injections_batch_number_'.$icounte];
							$open_stock = $_POST['injections_stock_'.$icounte];
							$expiry = $_POST['injections_expiry_'.$icounte];
							$add_date['add_date'] = date("Y-m-d H:i:s");
							$patient_id = $_POST['patient_id'];
							$patient_name = $_POST['patient_name'];
							$procedure_name = $_POST['procedure_name'];
							$employee_number = $_POST['employee_number'];
							$center_number = $_POST['center_number'];
							$date_of_purchase = $_POST['injections_date_of_purchase_'.$icounte];
							$vendor_price = $_POST['injections_vendor_price_'.$icounte];
							$mrp = $_POST['injections_mrp_'.$icounte];
							$hsn = $_POST['injections_hsn_'.$icounte];
							$gstrate = $_POST['injections_gstrate_'.$icounte];
							$gstdivision = $_POST['injections_gstdivision_'.$icounte];
							$pack_size = $_POST['injections_pack_size_'.$icounte];
							$enddate['enddate'] = date("Y-m-d");
							$quantity_out = $_POST['injections_quantity_'.$icounte];
								$closingstock = floatval($_POST['injections_stock_'.$icounte]) - floatval($_POST['injections_quantity_'.$icounte]);
							
							$gst_division = floatval($_POST['injections_gstdivision_'.$icounte]);

							if ($gst_division != 0) {
								$total_vendor_price_gst_excluded = floatval($_POST['injections_vendor_price_'.$icounte]) / $gst_division * $closingstock;
							} else {
								$total_vendor_price_gst_excluded = 0; // or handle it however you prefer
							}

							//$total_vendor_price_gst_excluded = $_POST['injections_vendor_price_'.$icounte] / $_POST['injections_gstdivision_'.$icounte] * $closingstock;
								$total_vendor_price_gst_included = $closingstock * floatval($_POST['injections_vendor_price_'.$icounte]);
								$total_mrp_price = $closingstock * floatval($_POST['injections_mrp_'.$icounte]);
							//$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);
							
						    $query = "INSERT INTO `hms_central_stock_report` (invoice_no, item_number, company, item_name, batch_number, openstock, expiry, add_date, employee_number, vendor_price, mrp,hsn, gstrate, gstdivision, enddate, quantity_out, closingstock,type, total_vendor_price_gst_excluded, total_vendor_price_gst_included, total_mrp_price, patient_id, date_of_purchase, center_number) values ('$invoice_no','$item_number','$company','$item_name','$batch_number','$open_stock','$expiry','".date("Y-m-d H:i:s")."','$employee_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','".date("Y-m-d")."','$quantity_out','$closingstock','Hormonal','$total_vendor_price_gst_excluded','$total_vendor_price_gst_included','$total_mrp_price','$patient_id','$date_of_purchase','$center_number')";
                            $result = run_form_query($query); 
							//$total_vendor_price = $quantity_out * $vendor_price;
							$total_vendor_price = $vendor_price / $pack_size * $quantity_out;
							$unit_vendor_price = $vendor_price / $pack_size;
                            $query2 = "INSERT INTO `hms_consumptions` (item_number, date, patient_id, patient_name, center, 	type, medicine_name, quantity, 	opening_stock, closing_stock, vendor_price,total_vendor_price,procedure_name) values ('$item_number','".date("Y-m-d H:i:s")."','$patient_id','$patient_name','$center_number','Hormonal','$item_name','$quantity_out','$open_stock','$closingstock','$unit_vendor_price','$total_vendor_price','$procedure_name')";
                            $result = run_form_query($query2); 
							$i_counte[] = array('injections_ID'=> $_POST['injections_ID_'.$icounte],'injections_serial'=> $_POST['injections_serial_'.$icounte],'injections_name'=> $_POST['injections_name_'.$icounte],'injections_item_name'=> $_POST['injections_item_name_'.$icounte],'injections_stock'=> $_POST['injections_stock_'.$icounte],'injections_batch_number'=> $_POST['injections_batch_number_'.$icounte],'injections_quantity'=> $_POST['injections_quantity_'.$icounte],'injections_price'=> $_POST['injections_price_'.$icounte],'injections_vendor_price'=> $_POST['injections_vendor_price_'.$icounte],'injections_mrp'=> $_POST['injections_mrp_'.$icounte],'injections_hsn'=> $_POST['injections_hsn_'.$icounte],'injections_expiry'=> $_POST['injections_expiry_'.$icounte],'injections_gstrate'=> $_POST['injections_gstrate_'.$icounte],'injections_gstdivision'=> $_POST['injections_gstdivision_'.$icounte],'injections_date'=> date("Y-m-d H:i:s"));
						}
					}
				}
				if(!empty($m_counter)){
					foreach($m_counter as $key => $mcounte){
						if($_POST['medicine_name_'.$mcounte] == ''){
							unset($_POST['medicine_ID_'.$mcounte]);
							unset($_POST['medicine_serial_'.$mcounte]);
							unset($_POST['medicine_name_'.$mcounte]);
							unset($_POST['medicine_item_name_'.$mcounte]);
							unset($_POST['medicine_stock_'.$mcounte]);
							unset($_POST['medicine_batch_number_'.$mcounte]);
							unset($_POST['medicine_quantity_'.$mcounte]);
							unset($_POST['medicine_price_'.$mcounte]);
							unset($_POST['medicine_vendor_price_'.$mcounte]);
							unset($_POST['medicine_mrp_'.$mcounte]);
							unset($_POST['medicine_hsn_'.$mcounte]);
							unset($_POST['medicine_expiry_'.$mcounte]);
							unset($_POST['medicine_gstrate_'.$mcounte]);
							unset($_POST['medicine_gstdivision_'.$mcounte]);
							unset($_POST['medicine_date_'.$mcounte]);
							unset($_POST['medicine_pack_size_'.$mcounte]);
						}else{
							$invoice_no = $_POST['medicine_invoice_no_'.$mcounte];
							$item_number = $_POST['medicine_serial_'.$mcounte];
							$company = $_POST['medicine_company_'.$mcounte];
							$item_name = $_POST['medicine_item_name_'.$mcounte];
							$batch_number = $_POST['medicine_batch_number_'.$mcounte];
							$open_stock = $_POST['medicine_stock_'.$mcounte];
							$expiry = $_POST['medicine_expiry_'.$mcounte];
							$add_date['add_date'] = date("Y-m-d H:i:s");
							$patient_id = $_POST['patient_id'];
							$patient_name = $_POST['patient_name'];
							$procedure_name = $_POST['procedure_name'];
							$employee_number = $_POST['employee_number'];
							$center_number = $_POST['center_number'];
							$date_of_purchase = $_POST['medicine_date_of_purchase_'.$mcounte];
							$vendor_price = $_POST['medicine_vendor_price_'.$mcounte];
							$mrp = $_POST['medicine_mrp_'.$mcounte];
							$hsn = $_POST['medicine_hsn_'.$mcounte];
							$gstrate = $_POST['medicine_gstrate_'.$mcounte];
							$gstdivision = $_POST['medicine_gstdivision_'.$mcounte];
							$enddate['enddate'] = date("Y-m-d");
							$quantity_out = $_POST['medicine_quantity_'.$mcounte];
							$pack_size = $_POST['medicine_pack_size_'.$mcounte];
								$closingstock = floatval($_POST['medicine_stock_'.$mcounte]) - floatval($_POST['medicine_quantity_'.$mcounte]);
							//$total_vendor_price_gst_excluded = $_POST['medicine_vendor_price_'.$mcounte] / $_POST['medicine_gstdivision_'.$mcounte] * $closingstock;
							$total_vendor_price_gst_excluded = 0; // Default value
							if (isset($_POST['medicine_gstdivision_'.$mcounter]) && $_POST['medicine_gstdivision_'.$mcounter] != 0) {
									$total_vendor_price_gst_excluded = (floatval($_POST['medicine_vendor_price_'.$mcounter]) / floatval($_POST['medicine_gstdivision_'.$mcounter])) * $closingstock;
							} else {
								error_log("Division by zero attempted: medicine_gstdivision_".$mcounter);
									$total_vendor_price_gst_excluded = floatval($_POST['medicine_vendor_price_'.$mcounter]) * $closingstock;
							}
								$total_vendor_price_gst_included = $closingstock * floatval($_POST['medicine_vendor_price_'.$mcounte]);
								$total_mrp_price = $closingstock * floatval($_POST['medicine_mrp_'.$mcounte]);
							//$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);
							
						    $query = "INSERT INTO `hms_central_stock_report` (invoice_no, item_number, company, item_name, batch_number, openstock, expiry, add_date,employee_number, vendor_price, mrp, hsn, gstrate, gstdivision, enddate, quantity_out, closingstock,type, total_vendor_price_gst_excluded, total_vendor_price_gst_included, total_mrp_price, patient_id, date_of_purchase, center_number) values ('$invoice_no','$item_number','$company','$item_name','$batch_number','$open_stock','$expiry','".date("Y-m-d H:i:s")."','$employee_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','".date("Y-m-d")."','$quantity_out','$closingstock','Embryologist','$total_vendor_price_gst_excluded','$total_vendor_price_gst_included','$total_mrp_price','$patient_id','$date_of_purchase','$center_number')";
                            $result = run_form_query($query); 
							//$total_vendor_price = $quantity_out * $vendor_price;
							$total_vendor_price = $vendor_price / $pack_size * $quantity_out;
							$unit_vendor_price = $vendor_price / $pack_size;
                            $query2 = "INSERT INTO `hms_consumptions` (date, patient_id, patient_name, center, 	type, medicine_name, quantity, 	opening_stock, closing_stock, vendor_price,total_vendor_price,procedure_name) values ('".date("Y-m-d H:i:s")."','$patient_id','$patient_name','$center_number','Embryologist','$item_name','$quantity_out','$open_stock','$closingstock','$unit_vendor_price','$total_vendor_price','$procedure_name')";
                            $result = run_form_query($query2); 	
							$m_counte[] = array('medicine_ID'=> $_POST['medicine_ID_'.$mcounte],'medicine_serial'=> $_POST['medicine_serial_'.$mcounte],'medicine_name'=> $_POST['medicine_name_'.$mcounte],'medicine_item_name'=> $_POST['medicine_item_name_'.$mcounte],'medicine_stock'=> $_POST['medicine_stock_'.$mcounte],'medicine_batch_number'=> $_POST['medicine_batch_number_'.$mcounte],'medicine_quantity'=> $_POST['medicine_quantity_'.$mcounte],'medicine_price'=> $_POST['medicine_price_'.$mcounte],'medicine_vendor_price'=> $_POST['medicine_vendor_price_'.$mcounte],'medicine_mrp'=> $_POST['medicine_mrp_'.$mcounte],'medicine_hsn'=> $_POST['medicine_hsn_'.$mcounte],'medicine_expiry'=> $_POST['medicine_expiry_'.$mcounte],'medicine_gstrate'=> $_POST['medicine_gstrate_'.$mcounte],'medicine_gstdivision'=> $_POST['medicine_gstdivision_'.$mcounte],'medicine_date'=> date("Y-m-d H:i:s"));
						}
					}
				}
				if(!empty($c_counter)){
					foreach($c_counter as $key => $ccounte){
						if($_POST['consumables_name_'.$ccounte] == ''){
							unset($_POST['consumables_ID_'.$ccounte]);
							unset($_POST['consumables_serial_'.$ccounte]);
							unset($_POST['consumables_name_'.$ccounte]);
							unset($_POST['consumables_item_name_'.$ccounte]);
							unset($_POST['consumables_stock_'.$ccounte]);
							unset($_POST['consumables_batch_number_'.$ccounte]);
							unset($_POST['consumables_quantity_'.$ccounte]);
							unset($_POST['consumables_price_'.$ccounte]);
							unset($_POST['consumables_vendor_price_'.$ccounte]);
							unset($_POST['consumables_mrp_'.$ccounte]);
							unset($_POST['consumables_hsn_'.$ccounte]);
							unset($_POST['consumables_expiry_'.$ccounte]);
							unset($_POST['consumables_gstrate_'.$ccounte]);
							unset($_POST['consumables_gstdivision_'.$ccounte]);
							unset($_POST['consumables_date_'.$ccounte]);
						}else{
							$invoice_no = $_POST['consumables_invoice_no_'.$ccounte];
							$item_number = $_POST['consumables_serial_'.$ccounte];
							$company = $_POST['consumables_company_'.$ccounte];
							$item_name = $_POST['consumables_item_name_'.$ccounte];
							$batch_number = $_POST['consumables_batch_number_'.$ccounte];
							$open_stock = $_POST['consumables_stock_'.$ccounte];
							$expiry = $_POST['consumables_expiry_'.$ccounte];
							// Calculate expiry_day using the same logic as gimmeYesterday function
							$expiry_day = '';
							if (!empty($expiry)) {
								$d = new DateTime($expiry);
								$day = $d->format('d');
								$month = $d->format('m');
								$year = $d->format('Y');
								
								// Adjust for edge cases like the JavaScript function
								if ($day == 30 || $day == 31 || $day == 29) { 
									$day = 28; 
								}
								
								$expiry_day = $year . '-' . $month . '-' . sprintf('%02d', $day);
							}
							$add_date['add_date'] = date("Y-m-d H:i:s");
							$patient_id = $_POST['patient_id'];
							$patient_name = $_POST['patient_name'];
							$procedure_name = $_POST['procedure_name'];
							$employee_number = $_POST['employee_number'];
							$center_number = $_POST['center_number'];
							$date_of_purchase = $_POST['consumables_date_of_purchase_'.$ccounte];
							$vendor_price = $_POST['consumables_vendor_price_'.$ccounte];
							$mrp = $_POST['consumables_mrp_'.$ccounte];
							$hsn = $_POST['consumables_hsn_'.$ccounte];
							$gstrate = $_POST['consumables_gstrate_'.$ccounte];
							$gstdivision = $_POST['consumables_gstdivision_'.$ccounte];
							$enddate['enddate'] = date("Y-m-d");
							$quantity_out = $_POST['consumables_quantity_'.$ccounte];
								$pack_size = $_POST['consumables_pack_size_'.$ccounte];
								$closingstock = floatval($_POST['consumables_stock_'.$ccounte]) - floatval($_POST['consumables_quantity_'.$ccounte]);
							//$total_vendor_price_gst_excluded = $_POST['consumables_vendor_price_'.$ccounte] / $_POST['consumables_gstdivision_'.$ccounte] * $closingstock;
							$gst_division = $_POST['consumables_gstdivision_'.$ccounte] ?? 0;

							if ($gst_division != 0) {
									$total_vendor_price_gst_excluded = (floatval($_POST['consumables_vendor_price_'.$ccounte]) / floatval($gst_division)) * $closingstock;
							} else {
								$total_vendor_price_gst_excluded = 0; // or handle differently
							}

								$total_vendor_price_gst_included = $closingstock * floatval($_POST['consumables_vendor_price_'.$ccounte]);
								$total_mrp_price = $closingstock * floatval($_POST['consumables_mrp_'.$ccounte]);
								$company = $_POST['consumables_company_'.$ccounte];
							//$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);
							
						    $query = "INSERT INTO `hms_central_stock_report` (invoice_no, item_number, company, item_name, batch_number, openstock, expiry, expiry_day, add_date,employee_number, vendor_price, mrp, hsn, gstrate, gstdivision, enddate, quantity_out, closingstock, type, total_vendor_price_gst_excluded, total_vendor_price_gst_included, total_mrp_price, patient_id, date_of_purchase, center_number) values ('$invoice_no','$item_number','$company','$item_name','$batch_number','$open_stock','$expiry','$expiry_day','".date("Y-m-d H:i:s")."','$employee_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','".date("Y-m-d")."','$quantity_out','$closingstock','Ot','$total_vendor_price_gst_excluded','$total_vendor_price_gst_included','$total_mrp_price','$patient_id','$date_of_purchase','$center_number')";
                            $result = run_form_query($query);
							
							//$total_vendor_price = $vendor_price / $pack_size * $quantity_out;
							
							if ($pack_size == 0) {
								$total_vendor_price = 0; // or set a fallback value
							} else {
								$total_vendor_price = ($vendor_price / $pack_size) * $quantity_out;
							}
						$unit_vendor_price = ($pack_size != 0) ? $vendor_price / $pack_size : 0;
							//$unit_vendor_price = $vendor_price / $pack_size;
							//$total_vendor_price = $quantity_out * $vendor_price;
                            $query2 = "INSERT INTO `hms_consumptions` (date, patient_id, patient_name, center, 	type, medicine_name, quantity, 	opening_stock, closing_stock, vendor_price,total_vendor_price, procedure_name) values ('".date("Y-m-d H:i:s")."','$patient_id','$patient_name','$center_number','Ot','$item_name','$quantity_out','$open_stock','$closingstock','$unit_vendor_price','$total_vendor_price','$procedure_name')";
                            $result = run_form_query($query2); 							
							$c_counte[] = array('consumables_ID'=> $_POST['consumables_ID_'.$ccounte],'consumables_serial'=> $_POST['consumables_serial_'.$ccounte],'consumables_name'=> $_POST['consumables_name_'.$ccounte],'consumables_item_name'=> $_POST['consumables_item_name_'.$ccounte],'consumables_stock'=> $_POST['consumables_stock_'.$ccounte],'consumables_batch_number'=> $_POST['consumables_batch_number_'.$ccounte],'consumables_quantity'=> $_POST['consumables_quantity_'.$ccounte],'consumables_price'=> $_POST['consumables_price_'.$ccounte],'consumables_vendor_price'=> $_POST['consumables_vendor_price_'.$ccounte],'consumables_mrp'=> $_POST['consumables_mrp_'.$ccounte],'consumables_hsn'=> $_POST['consumables_hsn_'.$ccounte],'consumables_expiry'=> $_POST['consumables_expiry_'.$ccounte],'consumables_gstrate'=> $_POST['consumables_gstrate_'.$ccounte],'consumables_gstdivision'=> $_POST['consumables_gstdivision_'.$ccounte],'consumables_date'=> date("Y-m-d H:i:s"));
						}
					}
				}
								
				$details = array();
				$details['data']['consumables'] = $c_counte;
				$details['data']['injections'] = $i_counte;
				$details['data']['medicine'] = $m_counte;
				$post_arr['data'] = serialize($details);
				$post_arr['employee_number'] = $_POST['employee_number'];unset($_POST['employee_number']);
				$post_arr['procedure_name'] = $_POST['procedure_name'];unset($_POST['procedure_name']);
				$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);
				$post_arr['add_on'] = date("Y-m-d H:i:s");
				
				// var_dump($_POST); echo '<br/><br/><br/><br/>';//die;
				// var_dump($post_arr);
				// die;
				
				$result = $this->stock_model->billing_item_insert($post_arr);
				if($result > 0){
					if(!empty($i_counter)){
						foreach($i_counter as $key => $icounte){
							$ID = $_POST['injections_ID_'.$icounte];
							$serial = $_POST['injections_serial_'.$icounte];
							$qty = $_POST['injections_quantity_'.$icounte];
							$update_stock = $this->stock_model->deduct_stock($ID, $serial, $qty);
						}
					}
					if(!empty($m_counter)){
						foreach($m_counter as $key => $mcounte){
							$ID = $_POST['medicine_ID_'.$mcounte];
							$serial = $_POST['medicine_serial_'.$mcounte];
							$qty = $_POST['medicine_quantity_'.$mcounte];
							$update_stock = $this->stock_model->deduct_stock($ID, $serial, $qty);
						}
					}
					if(!empty($c_counter)){
						foreach($c_counter as $key => $ccounte){
							$ID = $_POST['consumables_ID_'.$ccounte];
							$serial = $_POST['consumables_serial_'.$ccounte];
							$qty = $_POST['consumables_quantity_'.$ccounte];
							$update_stock = $this->stock_model->deduct_stock($ID, $serial, $qty);
						}
					}
					header("location:" .base_url(). "stocks/add_billing_item?m=".base64_encode('Patient Items added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "stocks/add_billing_item?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			//var_dump($this->stock_model->get_center_medicine_list());die;
			$template = get_header_template($logg['role']);
			$data['consumables'] = $this->stock_model->get_center_consumbles_list();
			$data['injections'] = $this->stock_model->get_center_injection_list();
			$data['medicine'] = $this->stock_model->get_center_embrology_list();
			$this->load->view($template['header']);
			$this->load->view('stocks/add_billing_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	/****** CENTER STOCKS Meicine Billing *****/
	
	public function add_billing_medicine(){
		//$receipt_number = receipt_number();
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_billing_medicine'){
				unset($_POST['action']);
				
				
				$post_arr['patient_detail_name'] = $_POST['patient_detail_name'];unset($_POST['patient_detail_name']);
				$post_arr['doctor_id'] = $_POST['doctor_id'];unset($_POST['doctor_id']);
				$post_arr['appointment_id'] = $_POST['appointment_id'];unset($_POST['appointment_id']);
				$post_arr['receipt_number'] = $_POST['receipt_number'];unset($_POST['receipt_number']);
				$post_arr['payment_method'] = $_POST['payment_method'];unset($_POST['payment_method']);
				$post_arr['cash_payment'] = isset($_POST['cash_payment']) ? substr($_POST['cash_payment'], 0, 10) : '0';unset($_POST['cash_payment']);
				$post_arr['card_payment'] = isset($_POST['card_payment']) ? substr($_POST['card_payment'], 0, 10) : '0';unset($_POST['card_payment']);
				$post_arr['upi_payment'] = isset($_POST['upi_payment']) ? substr($_POST['upi_payment'], 0, 10) : '0';unset($_POST['upi_payment']);
				$post_arr['neft_payment'] = isset($_POST['neft_payment']) ? substr($_POST['neft_payment'], 0, 10) : '0';unset($_POST['neft_payment']);
				$post_arr['wallet_payment'] = isset($_POST['wallet_payment']) ? substr($_POST['wallet_payment'], 0, 10) : '0';unset($_POST['wallet_payment']);
				$post_arr['transaction_id'] = $_POST['transaction_id'];unset($_POST['transaction_id']);
				$post_arr['hospital_id'] = $_POST['hospital_id'];unset($_POST['hospital_id']);
				$post_arr['payment_done'] = $_POST['payment_done'];unset($_POST['payment_done']);
				$post_arr['fees'] = $_POST['fees'];unset($_POST['fees']);
				$post_arr['status'] = $_POST['status'];unset($_POST['status']);
                $post_arr['discount_amount'] = floatval($post_arr['fees']) - floatval($post_arr['payment_done']);
				$post_arr['employee_number'] = isset($_POST['employee_number']) ? $_POST['employee_number'] : '';unset($_POST['employee_number']);
				$post_arr['department'] = isset($_POST['department']) ? $_POST['department'] : '';unset($_POST['department']);
				$post_arr['billing_at'] = $_POST['billing_at'];unset($_POST['billing_at']);
				
				$icounte = $mcounte = $ccounte = $spcounte = 1;
				$i_counte = $m_counte = $c_counte = $s_pcounte = array();
				$i_counter = $m_counter = $c_counter = $s_pcounter = array();
				foreach($_POST as $key => $val){
					$pos_c = strpos($key, 'consumables_name_');
					if ($pos_c === false) {} else {
						$cid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$c_counter[] = $cid;
					}	
				}

				if(!empty($c_counter)){
					foreach($c_counter as $key => $ccounte){
						if($_POST['consumables_name_'.$ccounte] == ''){
							unset($_POST['consumables_ID_'.$ccounte]);
							unset($_POST['consumables_serial_'.$ccounte]);
							unset($_POST['consumables_name_'.$ccounte]);
							unset($_POST['consumables_company_'.$ccounte]);
							unset($_POST['consumables_item_name_'.$ccounte]);
							unset($_POST['consumables_stock_'.$ccounte]);
							unset($_POST['consumables_batch_number_'.$ccounte]);
							unset($_POST['consumables_quantity_'.$ccounte]);
						    unset($_POST['consumables_price_'.$ccounte]);
							unset($_POST['consumables_discount_'.$ccounte]);
							unset($_POST['consumables_total_'.$ccounte]);
							unset($_POST['consumables_vendor_price_'.$ccounte]);
							unset($_POST['consumables_expiry_'.$ccounte]);
							unset($_POST['consumables_hsn_'.$ccounte]);
							unset($_POST['consumables_gstrate_'.$ccounte]);
							unset($_POST['consumables_gstdivision_'.$ccounte]);
							unset($_POST['consumables_pack_size_'.$ccounte]);
							unset($_POST['consumables_mrp_'.$ccounte]);
							
						}else{
							$invoice_no = $_POST['consumables_invoice_no_'.$ccounte];
							$item_number = $_POST['consumables_serial_'.$ccounte];
							$company = $_POST['consumables_company_'.$ccounte];
							$item_name = $_POST['consumables_item_name_'.$ccounte];
							$batch_number = $_POST['consumables_batch_number_'.$ccounte];
							$open_stock = $_POST['consumables_stock_'.$ccounte];
							$expiry = $_POST['consumables_expiry_'.$ccounte];
							// Calculate expiry_day using the same logic as gimmeYesterday function
							$expiry_day = '';
							if (!empty($expiry)) {
								$d = new DateTime($expiry);
								$day = $d->format('d');
								$month = $d->format('m');
								$year = $d->format('Y');
								// Adjust for edge cases like the JavaScript function
								if ($day == 30 || $day == 31 || $day == 29) { 
									$day = 28; 
								}
								
								$expiry_day = $year . '-' . $month . '-' . sprintf('%02d', $day);
							}
							$add_date['add_date'] = date("Y-m-d H:i:s");
							$patient_id = $_POST['patient_id'];
							$employee_number = isset($_POST['employee_number']) ? $_POST['employee_number'] : '';
							$date_of_purchase = $_POST['consumables_date_of_purchase_'.$ccounte];
							$center_number = isset($_POST['center_number']) ? $_POST['center_number'] : '';
							$vendor_price = $_POST['consumables_vendor_price_'.$ccounte];
							$mrp = $_POST['consumables_mrp_'.$ccounte];
							$hsn = $_POST['consumables_hsn_'.$ccounte];
							$gstrate = $_POST['consumables_gstrate_'.$ccounte];
							$gstdivision = $_POST['consumables_gstdivision_'.$ccounte];
							$enddate['enddate'] = date("Y-m-d");
								$quantity_out = $_POST['consumables_quantity_'.$ccounte];
								$closingstock = floatval($_POST['consumables_stock_'.$ccounte]) - floatval($_POST['consumables_quantity_'.$ccounte]);
							//$total_vendor_price_gst_excluded = $_POST['consumables_vendor_price_'.$ccounte] / $_POST['consumables_gstdivision_'.$ccounte] * $closingstock;
							$gst_division = $_POST['consumables_gstdivision_'.$ccounte] ?? 0;

							if ($gst_division != 0) {
									$total_vendor_price_gst_excluded = floatval($_POST['consumables_vendor_price_'.$ccounte]) / floatval($gst_division) * $closingstock;
							} else {
								$total_vendor_price_gst_excluded = 0; // or handle it another way if needed
							}
							$total_vendor_price_gst_included = $closingstock * floatval($_POST['consumables_vendor_price_'.$ccounte]);
							$total_mrp_price = $closingstock * floatval($_POST['consumables_mrp_'.$ccounte]);
						    $query = "INSERT INTO `hms_central_stock_report` (invoice_no, item_number, company, item_name, batch_number, openstock, expiry, expiry_day, add_date, employee_number, vendor_price, mrp, hsn, gstrate, gstdivision,enddate, quantity_out, closingstock,type, total_vendor_price_gst_excluded, total_vendor_price_gst_included, total_mrp_price, patient_id, date_of_purchase, center_number) values ('$invoice_no','$item_number','$company','$item_name','$batch_number','$open_stock','$expiry','$expiry_day','".date("Y-m-d H:i:s")."','".$post_arr['employee_number']."','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','".date("Y-m-d")."','$quantity_out','$closingstock','Cash','$total_vendor_price_gst_excluded','$total_vendor_price_gst_included','$total_mrp_price','$patient_id','$date_of_purchase','".$post_arr['billing_at']."')";
                            $result = run_form_query($query); 
							$c_counte[] = array('consumables_ID'=> $_POST['consumables_ID_'.$ccounte],'consumables_serial'=> $_POST['consumables_serial_'.$ccounte],'consumables_name'=> $_POST['consumables_name_'.$ccounte],'consumables_company'=> $_POST['consumables_company_'.$ccounte],'consumables_item_name'=> $_POST['consumables_item_name_'.$ccounte],'consumables_stock'=> $_POST['consumables_stock_'.$ccounte],'consumables_batch_number'=> $_POST['consumables_batch_number_'.$ccounte],'consumables_quantity'=> $_POST['consumables_quantity_'.$ccounte],'consumables_price'=> $_POST['consumables_price_'.$ccounte],'consumables_discount_'=> $_POST['consumables_discount_'.$ccounte],'consumables_total_'=> $_POST['consumables_total_'.$ccounte],'consumables_vendor_price'=> $_POST['consumables_vendor_price_'.$ccounte],'consumables_expiry'=> $_POST['consumables_expiry_'.$ccounte],'consumables_hsn'=> $_POST['consumables_hsn_'.$ccounte],'consumables_gstrate'=> $_POST['consumables_gstrate_'.$ccounte],'consumables_gstdivision'=> $_POST['consumables_gstdivision_'.$ccounte],'consumables_pack_size'=> $_POST['consumables_pack_size_'.$ccounte],'consumables_mrp'=> $_POST['consumables_mrp_'.$ccounte]);
						}
					}
				}
				$details = array();
				$details['data']['consumables'] = $c_counte;
				$post_arr['data'] = serialize($details);
				$post_arr['patient_id'] = $_POST['patient_id'];
				$post_arr['on_date'] = date("Y-m-d H:i:s");
				$post_arr['series_number'] = $_POST['series_number'];unset($_POST['series_number']);
				
				$result = $this->stock_model->billing_medicine_item_insert($post_arr);
				if($result > 0){
					if(!empty($c_counter)){
						foreach($c_counter as $key => $ccounte){
							$ID = $_POST['consumables_ID_'.$ccounte];
							$serial = $_POST['consumables_serial_'.$ccounte];
							$qty = $_POST['consumables_quantity_'.$ccounte];
							$update_stock = $this->stock_model->deduct_stock($ID, $serial, $qty);
						}
					}
					header("location:" .base_url(). "accounts/details/".$post_arr['receipt_number']."?t=medicine");
					die();
				}else{
					header("location:" .base_url(). "stocks/add_billing_medicine?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			$data['consumables'] = $this->stock_model->get_center_consumbles_medicine_list();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/add_billing_medicine', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
	
	/*************Return medicin by customer***************/
	function is_receipt_number_unique($receipt_number) {
		$query = $this->db->query("SELECT COUNT(*) AS count FROM  hms_patient_medicine WHERE receipt_number = ?", [$receipt_number]);
		$row = $query->row();
		return ($row->count == 0); // true if not exists
	}
	public function return_billing_medicine(){
		//$receipt_number = receipt_number();
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'return_billing_medicine'){
				unset($_POST['action']);
				$receipt_number = $_POST['receipt_number'];
				if(!$this->is_receipt_number_unique($receipt_number)) {
					header("location:" . base_url() . "stocks/add_billing_medicine?m=" . base64_encode('Receipt number already exists!') . "&t=" . base64_encode('error'));
					die();
				}
				$post_arr['patient_id'] = $_POST['patient_id'];unset($_POST['patient_id']);
				$post_arr['receipt_number'] = $_POST['receipt_number'];unset($_POST['receipt_number']);
				$post_arr['patient_detail_name'] = $_POST['patient_detail_name'];unset($_POST['patient_detail_name']);
				$post_arr['billing_at'] = $_SESSION['logged_billing_manager']['center'];
				$post_arr['payment_method'] = $_POST['payment_method'];unset($_POST['payment_method']);
				$post_arr['transaction_id'] = $_POST['transaction_id'];unset($_POST['transaction_id']);
				$post_arr['hospital_id'] = $_POST['hospital_id'];unset($_POST['hospital_id']);
				//$post_arr['billing_id'] = $_POST['billing_id'];unset($_POST['billing_id']);
				$post_arr['payment_done'] = $_POST['payment_done'];unset($_POST['payment_done']);
				$post_arr['discount_amount'] = $_POST['discount_amount'];unset($_POST['discount_amount']);
				$post_arr['status'] = $_POST['status'];unset($_POST['status']);
				
				$icounte = $mcounte = $ccounte = $spcounte = 1;
				$i_counte = $m_counte = $c_counte = $s_pcounte = array();
				$i_counter = $m_counter = $c_counter = $s_pcounter = array();
				foreach($_POST as $key => $val){
					$pos_c = strpos($key, 'consumables_name_');
					if ($pos_c === false) {} else {
						$cid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$c_counter[] = $cid;
					}	
				}

				if(!empty($c_counter)){
					foreach($c_counter as $key => $ccounte){
						if($_POST['consumables_name_'.$ccounte] == ''){
							unset($_POST['consumables_ID_'.$ccounte]);
							unset($_POST['consumables_serial_'.$ccounte]);
							unset($_POST['consumables_name_'.$ccounte]);
							unset($_POST['consumables_stock_'.$ccounte]);
							unset($_POST['consumables_batch_number_'.$ccounte]);
							unset($_POST['consumables_quantity_'.$ccounte]);
						    unset($_POST['consumables_price_'.$ccounte]);
							unset($_POST['consumables_discount_'.$ccounte]);
							unset($_POST['consumables_total_'.$ccounte]);
							unset($_POST['consumables_vendor_price_'.$ccounte]);
							unset($_POST['consumables_expiry_'.$ccounte]);
							unset($_POST['consumables_mrp_'.$ccounte]);
							unset($_POST['consumables_hsn_'.$ccounte]);
							unset($_POST['consumables_gstrate_'.$ccounte]);
							unset($_POST['consumables_gstdivision_'.$ccounte]);
						}else{
							$c_counte[] = array('consumables_ID'=> $_POST['consumables_ID_'.$ccounte],'consumables_serial'=> $_POST['consumables_serial_'.$ccounte],'consumables_name'=> $_POST['consumables_name_'.$ccounte],'consumables_stock'=> $_POST['consumables_stock_'.$ccounte],'consumables_batch_number'=> $_POST['consumables_batch_number_'.$ccounte],'consumables_quantity'=> $_POST['consumables_quantity_'.$ccounte],'consumables_price'=> $_POST['consumables_price_'.$ccounte],'consumables_discount_'=> $_POST['consumables_discount_'.$ccounte],'consumables_total_'=> $_POST['consumables_total_'.$ccounte],'consumables_vendor_price'=> $_POST['consumables_vendor_price_'.$ccounte],'consumables_expiry'=> $_POST['consumables_expiry_'.$ccounte],'consumables_mrp'=> $_POST['consumables_mrp_'.$ccounte],'consumables_hsn'=> $_POST['consumables_hsn_'.$ccounte],'consumables_gstrate'=> $_POST['consumables_gstrate_'.$ccounte],'consumables_gstdivision'=> $_POST['consumables_gstdivision_'.$ccounte]);
						}
					}
				}
								
				$details = array();
				$details['return_medicine']['consumables'] = $c_counte;
				$post_arr['return_medicine'] = serialize($details);
				$post_arr['on_date'] = date("Y-m-d H:i:s");
				$post_arr['stutus_type'] = $_POST['stutus_type'];unset($_POST['stutus_type']);
				//$post_arr['center_number'] = $_POST['center_number'];unset($_POST['center_number']);
				//$post_arr['department'] = $_POST['department'];unset($_POST['department']);

				$post_arr['employee_number'] = $_POST['employee_number'];unset($_POST['employee_number']);
				$post_arr['department'] = $_POST['department'];unset($_POST['department']);
				$post_arr['billing_at'] = $_SESSION['logged_billing_manager']['center'];

				$result = $this->stock_model->billing_medicine_item_insert($post_arr);
				//var_dump($this->stock_model->billing_medicine_item_insert($post_arr));die;
				if($result > 0){
					if(!empty($c_counter)){
						foreach($c_counter as $key => $ccounte){
							$ID = $_POST['consumables_ID_'.$ccounte];
							$item_number = $_POST['consumables_serial_'.$ccounte];
							$qty = $_POST['consumables_quantity_'.$ccounte];
							//$department = $_POST['consumables_department_'.$ccounte];

							//var_dump($update_stock = $this->stock_model->update_return_item_data($ID, $item_number, $qty));die;

							$update_stock = $this->stock_model->update_return_item_data($ID, $item_number, $qty);
							//var_dump($this->stock_model->update_return_item_data($ID, $item_number, $qty));die;
						}
					}
					header("location:" .base_url(). "stocks/return_billing_medicine/?&t=medicine=Success");
					die();
				}else{
					header("location:" .base_url(). "stocks/return_billing_medicine?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			$template = get_header_template($logg['role']);
			$data['consumables'] = $this->stock_model->get_center_consumbles_medicine_list();
			$this->load->view($template['header']);
			$this->load->view('stocks/return_billing_medicine', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	function get_stock_item_price(){
		$item_number = $_POST['item_number'];
		$units = $_POST['units'];
		$data = $this->stock_model->get_stock_item_price($item_number, $units);
		//var_dump($data);die;
		echo json_encode($data);die;
	}

	function get_stock_item_discount_price(){
		$discount = $_POST['discount'];
		$item_number = $_POST['item_number'];
		$units = $_POST['units'];
		$data = $this->stock_model->get_stock_item_discount_price($item_number, $units, $discount);
		//var_dump($data);die;
		echo json_encode($data);die;
	}
	
	function patient_items(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/patient_items', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	/*function patient_medicine_items(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$receipt_number = $this->input->get('receipt_number', TRUE);
			$t_parameter = $this->input->get('t', TRUE);
			$data['data'] = $this->stock_model->get_medicine_data($receipt_number);
			$data['patient_data'] = get_patient_detail($receipt_number);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/patient_medicine_items', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}*/
	
	public function get_patient_items_data(){
		$patient_id = $_POST['patient_id'];
		$consumable_result = $injection_result = $medicine_result = $patient_result = array();
		$data = $this->stock_model->get_patient_items_data($patient_id);
		//var_dump($data);die;
		
		$patient_result = $data['patient_result'];
		//var_dump($data);die;
		$response = array();
		$total_consum_price = 0;
		$total_injections_price = 0;
		$total_medicine_price = 0;
		$total_consum_vendor_price = 0;
		$total_injections_vendor_price = 0;
		$total_medicine_vendor_price = 0;
		if (!empty($patient_result))
        {       
			//echo '<pre>';
				//print_r($data);
				//echo '</pre>';
			$html = '';
			foreach($data as $ky => $vls){ //var_dump($vls);die;
				
				$total_consum_price = $total_consum_price2 + $total_injections_price + $total_medicine_price;
				$total_consum_vendor_price = $total_consum_vendor_price2 + $total_injections_vendor_price + $total_medicine_vendor_price;
				//$date = $vls['add_on'];
				//echo $date;			
				$consumable_result = isset($vls['consumable_result'])?$vls['consumable_result']:array();
				$injection_result = isset($vls['injection_result'])?$vls['injection_result']:array();
				$medicine_result = isset($vls['medicine_result'])?$vls['medicine_result']:array();
				if(count($consumable_result) > 0){
					$type = 'Consumable';
					foreach($consumable_result as $key => $val){//var_dump($vls['receipt_number']);die;
						$item_data = $this->stock_model->get_center_item_data($val['consumables_serial']);
						//$date = $val['add_on'];
						//var_dump($item_data);die;
						$html .= '<tr>';
						    
						    $total_vendor_price = $val['consumables_vendor_price'] * $val['consumables_quantity'];
							$html .= '<td><a href="'.base_url().'stocks/cdetail/'.$val['consumables_serial'].'">'.$val['consumables_serial'].'</a></td>';
							$html .= '<td>'.$item_data['employee_number'].'</td>';
							$html .= '<td>'.$item_data['item_name'].'</td>';
							$html .= '<td>'.$val['consumables_batch_number'].'</td>';
							$html .= '<td>'.$type.'</td>';
							$html .= '<td>'.$val['consumables_stock'].'</td>';
							$html .= '<td>'.$val['consumables_quantity'].'</td>';
							if($_SESSION['logged_administrator']){
							$html .= '<td><i class="fa fa-inr" aria-hidden="true"></i> '.$val['consumables_mrp'] * $val['consumables_quantity'].'</td>';
							$html .= '<td>'.$total_vendor_price.'</td>';
							}
							$total_consum_price2 += (float) $val['consumables_price'];
							$total_consum_vendor_price2 += $total_vendor_price;
							$html .= '<td>'.$val['consumables_date'].'</td>';
							
						$html .= '</tr>';
					}
					if($_SESSION['logged_administrator']){
				    $html .= '<tr><td colspan="3">Total Vendor Price</td><td colspan="1">'.$total_consum_vendor_price2.'</td></tr>';
				    }
					$html .= '<tr><td colspan="3">Total Consumables Price</td><td colspan="1">'.$total_consum_price2.'</td></tr>';
					
					$html .= '<tr><td colspan="9"></td></tr>';
					
					$html .= '<tr><td colspan="9"><h5 class="center">IPD Injection</h5></td></tr>';
					
					$html .= '<tr>
						<th>Item code</th>
						<th>Receipt number</th>
						<th>Item name</th>
						<th>Batch Number</th>
						<th>Category</th>
						<th>Open Qty</th>
						<th>Qty</th>';

					if ($_SESSION['logged_administrator']) {
						$html .= '
						<th>Consumption Price</th>
						<th>Vendor Price</th>';
					}

					$html .= '
						<th>Added On Date</th>
					</tr>';

				    
				}//var_dump($html);//die;
				if(count($injection_result) > 0){
					$type = 'Injection';
					foreach($injection_result as $key => $val){
						$item_data = $this->stock_model->get_center_item_data($val['injections_serial']);
						  $html .= '<tr>';
						    $total_vendor_price_injection = $val['injections_vendor_price'] * $val['injections_quantity'];
							$html .= '<td><a href="'.base_url().'stocks/cdetail/'.$val['injections_serial'].'">'.$val['injections_serial'].'</a></td>';
							$html .= '<td>'.$item_data['employee_number'].'</td>';
							$html .= '<td>'.$item_data['item_name'].'</td>';
							$html .= '<td>'.$val['injections_batch_number'].'</td>';
							$html .= '<td>'.$type.'</td>';
							$html .= '<td>'.$val['injections_stock'].'</td>';
							$html .= '<td>'.$val['injections_quantity'].'</td>';
							if($_SESSION['logged_administrator']){
							$html .= '<td><i class="fa fa-inr" aria-hidden="true"></i> '.$val['injections_mrp'] * $val['injections_quantity'].'</td>';
							$html .= '<td>'.$total_vendor_price_injection.'</td>';
							}
							$html .= '<td>'.$val['injections_date'].'</td>';
							$total_injections_price += (float) $val['injections_price'];
							$total_injections_vendor_price += $total_vendor_price_injection;
							
						$html .= '</tr>';
					}
					if($_SESSION['logged_administrator']){
					$html .= '<tr><td colspan="3">Total Vendor Price</td><td colspan="1">'. $total_injections_vendor_price.' </td>  </tr>';
					}
						$html .= '<tr><td colspan="3">Total Injections Price</td><td colspan="1">'.$total_injections_price.'</td></tr>';
						
						$html .= '<tr><td colspan="9"></td></tr>';
						
						$html .= '<tr><td colspan="9"><h5 class="center">Embrology</h5></td></tr>';
						
						$html .= '<tr>
							<th>Item code</th>
							<th>Receipt number</th>
							<th>Item name</th>
							<th>Batch Number</th>
							<th>Category</th>
							<th>Open Qty</th>
							<th>Qty</th>';

						if ($_SESSION['logged_administrator']) {
							$html .= '
							<th>Consumption Price</th>
							<th>Vendor Price</th>';
						}

						$html .= '
							<th>Added On Date</th>
						</tr>';

				}
				
				if(count($medicine_result) > 0){
					$type = 'Embrology';
					foreach($medicine_result as $key => $val){
						
						$item_data = $this->stock_model->get_center_item_data($val['medicine_serial']);
						//$item_data = $this->stock_model->get_employee_name($val['employee_number']);
						
						$html .= '<tr>';
						    $total_vendor_price_medicine = $val['medicine_vendor_price'] * $val['medicine_quantity'];
							$html .= '<td><a href="'.base_url().'stocks/cdetail/'.$val['medicine_serial'].'">'.$val['medicine_serial'].'</a></td>';
							$html .= '<td>'.$item_data['employee_number'].'</td>';
							$html .= '<td>'.$item_data['item_name'].'</td>';
							$html .= '<td>'.$val['medicine_batch_number'].'</td>';
							$html .= '<td>'.$type.'</td>';
							$html .= '<td>'.$val['medicine_stock'].'</td>';
							$html .= '<td>'.$val['medicine_quantity'].'</td>';
							if($_SESSION['logged_administrator']){
							$html .= '<td><i class="fa fa-inr" aria-hidden="true"></i> '.$val['medicine_mrp'] * $val['medicine_quantity'].'</td>';
							$html .= '<td>'.$total_vendor_price_medicine.'</td>';
							}
							$total_medicine_price += (float) $val['medicine_price'];
							$total_medicine_vendor_price += $total_vendor_price_medicine;
							$html .= '<td>'.$val['medicine_date'].'</td>';
						$html .= '</tr>';
					}
					if($_SESSION['logged_administrator']){
					$html .= '<tr><td colspan="3">Total Vendor Price</td><td colspan="1">'.$total_medicine_vendor_price.'</td></tr>';
					}
					$html .= '<tr><td colspan="3">Total Consumables Price</td><td colspan="1">'.$total_medicine_price.'</td></tr>';
					
				}
				

			}
			
			$response = array('data' => $html, 'patient_name'=> $patient_result['wife_name'], 'patient_email'=> $patient_result['wife_email'], 'patient_phone'=> $patient_result['wife_phone'], "total_consum_price" =>$total_consum_price, "total_consum_vendor_price" =>$total_consum_vendor_price);
			echo json_encode($response);
			die;
        }else{
			$response = array('data' => 'No record found!', 'patient_name'=> '', 'patient_email'=> '', 'patient_phone'=> '', 'total_consum_price' =>0, 'total_consum_vendor_price' =>0);
		}
	}
	
		/******  *****/
	public function get_patient_medicine_items_data(){
		$patient_id = $_POST['patient_id'];
		$consumable_result = $patient_result = array();
		$data = $this->stock_model->get_patient_medicine_items_data($patient_id);
		//var_dump($data);die;
		
		$patient_result = $data['patient_result'];
		//var_dump($data);die;
		$response = array();
		$total_consum_price = 0;
		if (!empty($patient_result))
        {       
			$html = '';
			foreach($data as $ky => $vls){ //var_dump($vls);die;
				$consumable_result = isset($vls['consumable_result'])?$vls['consumable_result']:array();
				if(count($consumable_result) > 0){
					$type = 'Consumable';
					foreach($consumable_result as $key => $val){//var_dump($vls['receipt_number']);die;
						$item_data = $this->stock_model->get_center_item_data($val['consumables_serial']);
						//var_dump($item_data);die;
						$html .= '<tr>';
							$html .= '<td><a href="'.base_url().'stocks/cdetail/'.$val['consumables_serial'].'">'.$val['consumables_serial'].'</a></td>';
							$html .= '<td><a href="'.base_url().'accounts/details/'.$vls['receipt_number'].'?t=procedure">'.$vls['receipt_number'].'</a></td>';
							$html .= '<td><a href="'.base_url().'accounts/details/'.$vls['receipt_number'].'?t=procedure">'.$vls['receipt_number'].'</a></td>';
							$html .= '<td>'.$item_data['item_name'].'</td>';
							$html .= '<td>'.$item_data['batch_number'].'</td>';
							$html .= '<td>'.$type.'</td>';
							$html .= '<td>'.$val['consumables_quantity'].'</td>';
							$html .= '<td><i class="fa fa-inr" aria-hidden="true"></i> '.$val['consumables_price'].'</td>';
							$total_consum_price += $val['consumables_price'];
							// $html .= '<td>'.date('d-m-Y H:i', strtotime($vls['add_on'])).'</td>';
						$html .= '</tr>';
					}
				}//var_dump($html);die;
				
			}
			
			$response = array('data' => $html, 'patient_name'=> $patient_result['wife_name'], 'patient_email'=> $patient_result['wife_email'], 'patient_phone'=> $patient_result['wife_phone'], "total_consum_price" =>$total_consum_price);
			echo json_encode($response);
			die;
        }else{
			$response = array('data' => 'No record found!', 'patient_name'=> '', 'patient_email'=> '', 'patient_phone'=> '', 'total_consum_price' =>0);
		}
	}	
	
	/****** CENTER STOCKS *****/
	
	public function categories(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$data['data'] = $this->stock_model->get_categories();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/categories', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function add_category(){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_category'){
				unset($_POST['action']);
				$data = $this->stock_model->add_category($_POST);
				if($data > 0){
					header("location:" .base_url(). "stocks/add_category?m=".base64_encode('Category added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "stocks/add_category?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/add_category');
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function edit_category()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['i'])){ $item_id = $_GET['i']; }
			if(isset($_POST['i'])) { $item_id = $_POST['i']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_category'){
				unset($_POST['action']);unset($_POST['i']);
				$data = $this->stock_model->update_category_data($_POST, $item_id);
				if($data > 0){
					header("location:" .base_url(). "stocks/edit_category?m=".base64_encode('Category updated successfully !').'&t='.base64_encode('success').'&i='.$item_id);
					die();
				}else{
					header("location:" .base_url(). "stocks/edit_category?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&i='.$item_id);
					die();
				}				
			}
			$data['data'] = $this->stock_model->get_category_data($item_id);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/edit_category', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function delete_category()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$item = $_GET['i'];
			if( $item > 0 )
			{
				if( $this->stock_model->delete_category_data($item) !== 0)
				{
					header("location:" .base_url(). "stocks/categories?m=".base64_encode('Category deleted successfully !').'&t='.base64_encode('success'));
					die();
				}
				else
				{
					header("location:" .base_url(). "stocks/categories?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			header("location:" .base_url(). "stocks/categories?m=".base64_encode('Category not found !').'&t='.base64_encode('error'));
			die();
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/**** Products ****/
	
	public function products(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$data['data'] = $this->stock_model->get_products();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/products', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function add_product(){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_product'){
				unset($_POST['action']);
				$data = $this->stock_model->add_product($_POST);
				if($data > 0){
					header("location:" .base_url(). "stocks/add_product?m=".base64_encode('Product added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "stocks/add_product?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/add_product');
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function edit_product()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['i'])){ $item_id = $_GET['i']; }
			if(isset($_POST['i'])) { $item_id = $_POST['i']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_product'){
				unset($_POST['action']);unset($_POST['i']);
				$data = $this->stock_model->update_product_data($_POST, $item_id);
				if($data > 0){
					header("location:" .base_url(). "stocks/edit_product?m=".base64_encode('Product updated successfully !').'&t='.base64_encode('success').'&i='.$item_id);
					die();
				}else{
					header("location:" .base_url(). "stocks/edit_product?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&i='.$item_id);
					die();
				}				
			}
			$data['data'] = $this->stock_model->get_product_data($item_id);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/edit_product', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function delete_product()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$item = $_GET['i'];
			if( $item > 0 )
			{
				if( $this->stock_model->delete_category_data($item) !== 0)
				{
					header("location:" .base_url(). "stocks/categories?m=".base64_encode('Category deleted successfully !').'&t='.base64_encode('success'));
					die();
				}
				else
				{
					header("location:" .base_url(). "stocks/categories?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			header("location:" .base_url(). "stocks/categories?m=".base64_encode('Category not found !').'&t='.base64_encode('error'));
			die();
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	public function center_medicine_report(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$export_billing = $this->input->get('export-billing', true);
			$paid_amount = 0;
			$discounted_package = 0;
			$total_package = 0;
			if (isset($export_billing)){
				$data = $this->stock_model->export_medicine_center_data($start_date, $end_date, $center, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Medicine-Report-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Date, IIC ID, Patient Name, Hospital Id,Receipt Number, Payment Method, Medicine, Medicine Name, Quantity, Ammount, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$employee_number = get_center_name($val['employee_number']);
					$lead_arr = array($val['on_date'], $val['patient_id'], $val['patient_detail_name'],$val['hospital_id'],$val['receipt_number'], $val['payment_method'], $val['consumables_serial'], $val['final__consumables'], $val['consumables_quantity'], $val['consumables_total_'], $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/center_medicine_report";
        	$config["total_rows"] = $this->stock_model->patient_center_medicine_count($center, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->center_medicine_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id);
			//var_dump($data);die;
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/center_medicine_report', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	public function disaprove_medicine_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/disaprove_medicine_list";
        	$config["total_rows"] = $this->stock_model->disaprove_medicine_count($center, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 50;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->disaprove_medicine_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id);
			//var_dump($data);die;
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/disaprove_medicine_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/***********Center Stock***********/

	public function all_center_stocks(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$generic_name = $this->input->get('generic_name', true);
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$item_number = $this->input->get('item_number', true);
            $expiry = $this->input->get('expiry', true); 
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_all_center_stocks($employee_number, $start_date, $end_date, $generic_name, $item_name);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=All-Center-Medicine-'.$start_date.'-'.$end_date.'-'.date("Y-m-d H-i-s").'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Product Number,Company, Product Name, Batch Number, Generic Name, HSN, GST, Pack Size, Quantity (Unit), Vendor Price, MRP, Expiry, Status, Employee Number, Stock Value, Center Name,Department, Date Of Purchase,Employee Name,Single Unit Vendor Price, Total Vendor Price';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$sql2 = "SELECT * FROM hms_employees WHERE employee_number='" . $val['employee_number'] . "'";
					$select_result = run_select_query($sql2);
					$name = $select_result['name'];
					
					if (!empty($val['pack_size']) && $val['pack_size'] != 0) {
						$single_unit_vendor_price = $val['vendor_price'] / $val['pack_size'];
					} else {
						$single_unit_vendor_price = 0; // or handle differently
					}

					$total_unit_vendor_price = $single_unit_vendor_price * $val['quantity'];
					$lead_arr = array($val['item_number'], $val['company'],$val['item_name'], $val['batch_number'], $val['generic_name'], $val['hsn'], $val['gstrate'], $val['pack_size'], $val['quantity'], $val['vendor_price'], $val['mrp'], $val['expiry'], $val['status'], $val['employee_number'],$val['quantity'] * $val['vendor_price'],$val['center_number'],$val['department'],$val['date_of_purchase'],$name, $single_unit_vendor_price,$total_unit_vendor_price);
					fputcsv($fp, $lead_arr);
				}
				
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/all_center_stocks";
        	$config["total_rows"] = $this->stock_model->get_all_center_stocks($employee_number, $start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number, $expiry);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->get_all_center_stocks_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number, $expiry);
			//var_dump($data);die;
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["generic_name"] = $generic_name;
            $data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["item_number"] = $item_number;
			$data["expiry"] = $expiry;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/all_center_stocks', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function expiry_item(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/expiry_item";
        	$config["total_rows"] = $this->stock_model->get_expiry_item_stocks($employee_number, $start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number, $expiry);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->get_expiry_item_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number, $expiry);
			//var_dump($data);die;
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["generic_name"] = $generic_name;
            $data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["item_number"] = $item_number;
			$data["expiry"] = $expiry;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/expiry_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
		/***********All Center Stock***********/

	public function stocks(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$generic_name = $this->input->get('generic_name', true);
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$item_number = $this->input->get('item_number', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_central_stocks($start_date, $end_date, $generic_name, $item_name);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Central-Medicine-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Item Number,Company, Item Name, Batch Number, Brand Name, Vendor Name,Vendor Price, HSN, Pack Size, GST Rate, MRP, Generic Name, Quantity, Safety Stock, Order Qty, Category, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$sql2 = "SELECT * FROM hms_vendors WHERE vendor_number='" . $val['vendor_number'] . "'";
					$select_result2 = run_select_query($sql2);
					$vendor_name = $select_result2['name'];
					
					$sql = "SELECT * FROM hms_brands WHERE brand_number='" . $val['brand_name'] . "'";
					$select_result = run_select_query($sql);
					$brand_name = $select_result['name']; 
						
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['item_number'], $val['company'],$val['item_name'], $val['batch_number'],$brand_name, $vendor_name,$val['vendor_price'],$val['hsn'],$val['pack_size'],$val['gstrate'],$val['mrp'], $val['generic_name'], $val['quantity'], $val['safety_stock'], $val['order_qty'], $val['category'], $val['status']);
					fputcsv($fp, $lead_arr);
				}
				
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/stocks";
        	$config["total_rows"] = $this->stock_model->get_central_stocks($start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->get_central_stocks_patination($config["per_page"], $per_page, $start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number);
			//var_dump($data);die;
			//$data = array();
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["generic_name"] = $generic_name;
            $data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["item_number"] = $item_number;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/stocks', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function bulk_status()
	{
		// Clear any output buffering
		if (ob_get_level()) {
			ob_clean();
		}
		
		$logg = checklogin();
		if ($logg['status'] != true) {
			http_response_code(401);
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode(array('status' => 0, 'message' => 'Unauthorized'));
			return;
		}
		
		header('Content-Type: application/json; charset=utf-8');
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		
		$item_numbers = $this->input->post('item_numbers');
		$status = $this->input->post('status');
		
		if (!is_array($item_numbers) || ($status !== '0' && $status !== '1')) {
			echo json_encode(array('status' => 0, 'message' => 'Invalid payload'));
			return;
		}
		
		$affected = $this->stock_model->bulk_update_items_status($item_numbers, $status);
		echo json_encode(array('status' => 1, 'updated' => (int)$affected));
	}
	
	public function bulk_status_center()
	{
		// Clear any output buffering
		if (ob_get_level()) {
			ob_clean();
		}
		
		$logg = checklogin();
		if ($logg['status'] != true) {
			http_response_code(401);
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode(array('status' => 0, 'message' => 'Unauthorized'));
			return;
		}
		
		header('Content-Type: application/json; charset=utf-8');
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		
		$item_ids = $this->input->post('item_ids');
		$status = $this->input->post('status');
		
		if (!is_array($item_ids) || ($status !== '0' && $status !== '1')) {
			echo json_encode(array('status' => 0, 'message' => 'Invalid payload'));
			return;
		}
		
		$affected = $this->stock_model->bulk_update_center_items_status($item_ids, $status);
		echo json_encode(array('status' => 1, 'updated' => (int)$affected));
	}
	
	public function active_stocks(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$item_number = $this->input->get('item_number', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/active_stocks";
        	$config["total_rows"] = $this->stock_model->get_active_central_stocks($item_name, $batch_number, $item_number);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->get_active_central_stocks_patination($config["per_page"], $per_page, $item_name, $batch_number, $item_number);
			$data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["item_number"] = $item_number;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/active_stocks', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/**********All Medicine Sale Report**********/
	
		/***********All Stock Report***********/

	public function stocks_reports(){
		$logg = checklogin();
		//error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$center_number = $this->input->get('center_number', true);
			$patient_id = $this->input->get('patient_id', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$invoice_no = $this->input->get('invoice_no', true);
			$type = $this->input->get('type', true);
			$date_of_purchase = $this->input->get('date_of_purchase', true);
			
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_stocks_reports($employee_number, $center_number, $patient_id, $start_date, $end_date, $item_name, $batch_number,$type);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Medicine-Stock-Reports-'.$enddate.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Item Number, Item Name,Pack Size, Batch Number, Opening Stock, Quantity IN, Quantity Out, Closing Stock, Vendor Price, MRP, GST RATE, Type, Date, Employee Name';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
				   // $gst_amount = $gstdivision / $val['vendor_price'];
					$sql2 = "SELECT * FROM hms_employees WHERE employee_number='" . $val['employee_number'] . "'";
					$select_result = run_select_query($sql2);
					$name = $select_result['name'];
					
					$sql = "SELECT * FROM hms_stocks WHERE item_number='" . $val['item_number'] . "'";
					$select_result2 = run_select_query($sql);
					$packsize = $select_result2['pack_size'];
				    $lead_arr = array($val['item_number'], $val['item_name'],$packsize, $val['batch_number'], $val['openstock'], $val['quantity_in'], $val['quantity_out'], $val['closingstock'], $val['vendor_price'],  $val['mrp'], $val['gstrate'], $val['type'], $val['add_date'],$name);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/stocks_reports";
        	$config["total_rows"] = $this->stock_model->get_stocks_reports($employee_number, $center_number, $patient_id, $start_date, $end_date, $item_name, $batch_number, $invoice_no, $type, $date_of_purchase);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->get_stocks_reports_patination($config["per_page"], $per_page, $employee_number, $center_number, $patient_id, $start_date, $end_date, $item_name, $batch_number, $invoice_no, $type, $date_of_purchase);
			//$data['total_stock_result'] = $this->stock_model->total_stocks_reports($employee_number, $center_number, $patient_id, $enddate, $item_name, $batch_number);
			//var_dump($data);die;
			//$data = array();
			$data["employee_number"] = $employee_number;
			$data["center_number"] = $center_number;
			$data["patient_id"] = $patient_id;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["invoice_no"] = $invoice_no;
			$data["type"] = $type;
			$data["$date_of_purchase"] = $date_of_purchase;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/stocks_reports', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/**********All Stock Report**********/
	
public function medicine_stock(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$export_billing = $this->input->get('export-billing', true);
			
			if (isset($export_billing)){
				$data = $this->stock_model->export_medicine_data2($start_date, $end_date, $employee_number, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Medicine-Stock-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Date, IIC ID, Patient Name, Hospital Id,Receipt Number, Payment Method, Medicine Name, Medicine Name, Quantity, Amount, Status, Employee Name, Center Name';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
				$sql2 = "SELECT * FROM hms_employees WHERE employee_number='" . $val['employee_number'] . "'";
				$select_result = run_select_query($sql2);
				$name = $select_result['name'];
				
				$sql2 = "SELECT * FROM hms_centers WHERE center_number='" . $val['billing_at'] . "'";
				$select_center = run_select_query($sql2);
				$center_name = $select_center['center_name'];
				
					
					$lead_arr = array($val['on_date'], $val['patient_id'], $val['patient_detail_name'],$val['hospital_id'],$val['receipt_number'], $val['payment_method'], $val['final__consumables'], $val['consumables_name'], $val['consumables_quantity'], $val['consumables_total_'], $val['status'],$name, $center_name);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
			$export_cash_medicine = $this->input->get('export-cash-medicine', true);
			if (isset($export_cash_medicine)){
				$data = $this->stock_model->export_cash_medicine($start_date, $end_date, $employee_number, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Cash-Medicine-Stock-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Date, IIC ID, Patient Name, Hospital Id,Receipt Number, Payment Method, Medicine Name,Item Name,Pack Size, Quantity,Out Qty, Amount,Vendor Price,Discount, Total, Employee Number, Status, Batch Number,	HSN, GST,	Division, MRP,Emplyee Name, Single Unit Vendor Price, Total Vendor Price, Center Name';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$sql2 = "SELECT * FROM hms_employees WHERE employee_number='" . $val['employee_number'] . "'";
					$select_result = run_select_query($sql2);
					$name = $select_result['name'];
					
					$sql3 = "SELECT * FROM hms_stocks WHERE item_number='" . $val['consumables_name'] . "'";
					$select_result3 = run_select_query($sql3);
					$vendor_price = $select_result3['vendor_price'];
					$pack_size = $select_result3['pack_size'];
					$item_name = $select_result3['item_name'];
					
					$sql_center = "SELECT * FROM hms_centers WHERE center_number='" . $val['billing_at'] . "'";
					$select_center = run_select_query($sql_center);
					$center_name = $select_center['center_name'];
					
					$single_unit_vendor_price = (!empty($pack_size)) ? round($vendor_price / $pack_size, 2) : 0;

					$total_unit_vendor_price = $single_unit_vendor_price * (float)$val['consumables_quantity'];
					
					$lead_arr = array($val['on_date'], $val['patient_id'], $val['patient_detail_name'],$val['hospital_id'],$val['receipt_number'], $val['payment_method'], $val['consumables_name'],$item_name, $pack_size, $val['consumables_stock'], $val['consumables_quantity'], $val['consumables_price'], $vendor_price, $val['consumables_discount_'], $val['consumables_total_'],$val['employee_number'], $val['status'], $val['consumables_batch_number'], $val['consumables_hsn'], $val['consumables_gstrate'], $val['consumables_gstdivision'], $val['consumables_mrp'],$name, $single_unit_vendor_price, $total_unit_vendor_price,$center_name);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
			
			$export_return_medicine = $this->input->get('export-return-medicine', true);
			if (isset($export_return_medicine)){
				$data = $this->stock_model->export_return_medicine($start_date, $end_date, $employee_number, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Cash-Return-Stock-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Date, IIC ID, Patient Name, Hospital Id,Receipt Number, Payment Method, Medicine Name,Item Name, Quantity,Out Qty, Amount, Vendor Price, Pack Size, Discount, Total, Employee Number, Status, Batch Number, HSN, GST, MRP,Employee Name';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$sql2 = "SELECT * FROM hms_employees WHERE employee_number='" . $val['employee_number'] . "'";
					$select_result = run_select_query($sql2);
					$name = $select_result['name'];
					
					$sql3 = "SELECT * FROM hms_stocks WHERE item_number='" . $val['consumables_name'] . "'";
					$select_result3 = run_select_query($sql3);
					$item_name = $select_result3['item_name'];
					$vendor_price = $select_result3['vendor_price'];
					$pack_size = $select_result3['pack_size'];
					
					$lead_arr = array($val['on_date'], $val['patient_id'], $val['patient_detail_name'],$val['hospital_id'],$val['receipt_number'], $val['payment_method'], $val['consumables_name'], $item_name, $val['consumables_stock'], $val['consumables_quantity'], $val['consumables_price'], $vendor_price, $pack_size, $val['consumables_discount_'], $val['consumables_total_'],$val['employee_number'], $val['status'], $val['consumables_batch_number'], $val['consumables_hsn'], $val['consumables_gstrate'], $val['consumables_mrp'],$name);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/medicine_stock";
        	$config["total_rows"] = $this->stock_model->patient_medicine_count($employee_number, $start_date, $end_date, $patient_id, $consumables_name);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->patient_medicine_list_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id, $consumables_name);
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["consumables_name"] = $consumables_name;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/medicine_stock', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function medicine_origin(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$export_billing = $this->input->get('export-medicine', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_medicine_origin($start_date, $end_date, $employee_number, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Medicine-Stock-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Date, IIC ID, Patient Name, Hospital Id, Medicine Name, Name, Quantity,  Amount,Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$employee_number = get_center_name($val['employee_number']);
					$lead_arr = array($val['on_date'], $val['patient_id'], $val['patient_detail_name'],$val['hospital_id'], $val['final__consumables'], $val['consumables_item_name'], $val['consumables_quantity'], $val['consumables_total_'], $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/medicine_origin";
        	$config["total_rows"] = $this->stock_model->patient_medicine_count($employee_number, $start_date, $end_date, $patient_id, $consumables_name);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->patient_medicine_list_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id, $consumables_name);
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["consumables_name"] = $consumables_name;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/medicine_origin', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/****Generic Name****/
	public function generic(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){
		
        	//var_dump($data);die;
			$data = array();
			$data['data'] = $this->stock_model->get_generic();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/generic', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function eit_generic_name()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			
			if(isset($_GET['ID'])){
				if(isset($_GET['ID'])){ $item_id = $_GET['ID']; }
				if(isset($_POST['ID'])) { $item_id = $_POST['ID']; }
				
				if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_brand'){
					unset($_POST['action']);
					$data = $this->stock_model->update_generic_data($_POST, $item_id);
					if($data > 0){
						header("location:" .base_url(). "stocks/eit_generic_name?m=".base64_encode('Generic Name updated successfully !').'&t='.base64_encode('success').'&ID='.$item_id);
						die();
					}else{
						header("location:" .base_url(). "stocks/eit_generic_name?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&ID='.$item_id);
						die();
					}				
				}
				$data['data'] = $this->stock_model->get_generic_data($item_id);
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('stocks/eit_generic_name', $data);
				$this->load->view($template['footer']);
			}else{
				header("location:" .base_url(). "stocks");
				die();
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
		/*****Consumption*****/
		
			public function consumption_price(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){
			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$type = $this->input->get('type', true);
			$config = array();
        	$config["base_url"] = base_url() . "stocks/consumption_price";
        	$config["total_rows"] = $this->stock_model->patient_consuption_medicine_count($employee_number, $start_date, $end_date, $patient_id, $type);
        	//$config["total_rows"] = $this->stock_model->patient_consuption_medicine_ot($employee_number, $start_date, $end_date, $patient_id,$type);
        	//var_dump($data);
		    //die;
			$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->patient_consuption_list_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id,$type);
			//$data['investigate_result'] = $this->stock_model->patient_consuption_list_ot($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id,$type);
			
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["medicine_serial"] = $medicine_serial;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/consumption_price', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function consumption_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){
			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$type = $this->input->get('type', true);
			$procedure_name = $this->input->get('procedure_name', true);
			$patient_name = $this->input->get('patient_name', true);
			$config = array();
        	$config["base_url"] = base_url() . "stocks/consumption_list";
        	$config["total_rows"] = $this->stock_model->consumption_list_count($start_date, $end_date, $patient_id, $type, $procedure_name, $patient_name);
        	//var_dump($data);
		    //die;
			$config["per_page"] = 500;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->consumption_list_patination($config["per_page"], $per_page, $start_date, $end_date, $patient_id, $type, $procedure_name, $patient_name);
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["type"] = $type;
			$data["procedure_name"] = $procedure_name;
			$data["patient_name"] = $patient_name;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/consumption_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function vendor_price_item(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){
			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			$medicine_serial = $this->input->get('medicine_serial', true);
			$config = array();
        	$config["base_url"] = base_url() . "stocks/vendor_price_item";
        	$config["total_rows"] = $this->stock_model->patient_consuption_medicine_count($employee_number, $start_date, $end_date, $patient_id,$medicine_serial=null);
        	//var_dump($data);
		    //die;
			$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->patient_consuption_list_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id,$medicine_serial=null);
			
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$data["medicine_serial"] = $medicine_serial;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/vendor_price_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	
	/*******************Medicine Report Account Panel****************/
	
	   public function medicine_patients(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('iic_id', true);
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_investigation_data($start_date, $end_date, $center, $patient_id);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Investigation-Patients-'.$start_date.'-'.$end_date.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'IIC ID, Patient Name, Total package, Discounted Package, Paid Amount, Remaining Amount, Payment Method, Billing From, Billing At, Billing Type, Date, Status';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
					$billing_from = $val['billing_from'];
					if($billing_from != "IndiaIVF"){
						$billing_from = get_center_name($billing_from);
					}
					$billing_at = get_center_name($val['billing_at']);
					$lead_arr = array($val['patient_id'], $val['wife_name'], $val['totalpackage'], $val['discounted_package'], $val['payment_done'], $val['remaining_amount'], $val['payment_method'], $billing_from, $billing_at, $val['billing_type'], date('Y-m-d H:i:s', strtotime($val['date'])), $val['status']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "stocks/medicine_patients";
        	$config["total_rows"] = $this->stock_model->patient_investigation_count($center, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->patient_investigation_list_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $patient_id);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/medicine_patients', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	} 
	
	
	
	   public function medicine_center_order(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$center = $this->input->get('billing_at', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$item_name = $this->input->get('item_name', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/medicine_center_order";
        	$config["total_rows"] = $this->stock_model->medicine_center_order_item($center, $start_date, $end_date, $item_name);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->medicine_center_order_patination($config["per_page"], $per_page, $center, $start_date, $end_date, $item_name);
			$data["billing_at"] = $center;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["item_name"] = $item_name;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/medicine_center_order', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	} 
	
		/****Return Medicine Item****/
	public function return_medicine_item(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['ID'])){ $ID = $_GET['ID']; }
			
			if(isset($_POST['ID'])) { $ID = $_POST['ID']; }
			
			if(isset($_GET['employee_number'])) { $employee_number = $_GET['employee_number']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_center_item'){
				unset($_POST['action']);
				$data = $this->stock_model->return_center_item_data($_POST, $item_id);
				if($data > 0){
					header("location:" .base_url(). "stocks/return_medicine_item?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&item_number='.$item_id);
					die();
				}else{
					header("location:" .base_url(). "stocks/return_medicine_item?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('error').'&item_number='.$item_id);
					die();
				}				
			}
			//print_r($_POST['employee_number']);die();
			//$data['item_number'] = $item_id;
			$data['ID'] = $ID;
			$data['employee_number'] = $employee_number;
			$data['data'] = $this->stock_model->get_center_return_data($ID, $employee_number);
			//$data['categories'] = $this->stock_model->get_categories();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/return_medicine_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function center_medicine_order(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['item_number'])){ $item_id = $_GET['item_number']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'center_order_item'){
				unset($_POST['action']);
				$data = $this->stock_model->center_order_medicine($_POST, $item_id);
				if($data > 0){
					header("location:" .base_url(). "stocks/center_medicine_order?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&item_number='.$item_id);
					die();
				}else{
					header("location:" .base_url(). "stocks/center_medicine_order?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('error').'&item_number='.$item_id);
					die();
				}				
			}
			$data['item_number'] = $item_id;
            $data['data'] = $this->stock_model->get_center_item_data2($item_id);
			//$data['categories'] = $this->stock_model->get_categories();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/center_medicine_order', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function return_medicine_central(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['item_number'])){ $item_id = $_GET['item_number']; }
			
			if(isset($_POST['item_number'])) { $item_id = $_POST['item_number']; }
			
			if(isset($_GET['item_id'])){ $item_id = $_GET['item_id']; }
			$item_qty = $_POST['quantity'];
			//if(isset($_POST['quantity'])) { $quantity = $_POST['quantity']; }
			if(isset($_POST['ID'])) { $ID = $_POST['ID']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_discard_item_centaral'){
				unset($_POST['action']);
				$data = $this->stock_model->update_discard_central_data($item_number, $ID, $item_qty, $item_id);
				//var_dump($update_discard_data); 
				
				$item_number = $_POST['item_number'];
				$item_name = $_POST['item_name'];
				$company = $_POST['company'];
				$batch_number = $_POST['batch_number'];
				$reason = $_POST['reason'];
				$item_qty = $_POST['quantity'];
                $employee_number = $_POST['employee_number'];
				$_POST['expiry'] = date("Y-m-d H:i:s");
				$data = $this->stock_model->add_discard_item($_POST);
								
			}
			$data['ID'] = $ID;
            $data['item_number'] = $item_id;
			$data['data'] = $this->stock_model->get_centeral_item($item_id);
			//$data['categories'] = $this->stock_model->get_categories();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/return_medicine_central', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function update_return_item($item_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['item_id'])){ $item_id = $_GET['item_id']; }
			$item_qty = $_POST['quantity'];
			//if(isset($_POST['quantity'])) { $quantity = $_POST['quantity']; }
			if(isset($_POST['employee_number'])) { $employee_number = $_POST['employee_number']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_discard_item'){
				unset($_POST['action']);
				$data = $this->stock_model->update_discard_data($item_number, $employee_number, $item_qty, $item_id);
				//var_dump($update_discard_data); 
				
				$item_number = $_POST['item_number'];
				$item_name = $_POST['item_name'];
				$company = $_POST['company'];
				$batch_number = $_POST['batch_number'];
				$reason = $_POST['reason'];
				$item_qty = $_POST['quantity'];
				$_POST['employee_number'] = $employee_number;
				$_POST['expiry'] = date("Y-m-d H:i:s");
				$data = $this->stock_model->add_discard_item($_POST);
								
			}
			$data['item_number'] = $item_id;
			$data['employee_number'] = $employee_number;
			$data['data'] = $this->stock_model->add_item_data($employee_number, $item_id);
			//$data['categories'] = $this->stock_model->get_categories();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/update_return_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function return_vendor($item_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['item_id'])){ $item_id = $_GET['item_id']; }
			$item_qty = $_POST['quantity'];
			
			if(isset($_POST['employee_number'])) { $employee_number = $_POST['employee_number']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_discard_item'){
				unset($_POST['action']);
				$data = $this->stock_model->vendor_return_data($item_number, $employee_number, $item_qty, $item_id);
				//var_dump($update_discard_data); 
				
				$item_number = $_POST['item_number'];
				$item_name = $_POST['item_name'];
				$company = $_POST['company'];
				$batch_number = $_POST['batch_number'];
				$reason = $_POST['reason'];
				$item_qty = $_POST['quantity'];
				$_POST['employee_number'] = $employee_number;
				$_POST['expiry'] = date("Y-m-d H:i:s");
				$data = $this->stock_model->add_vendor_item($_POST);
								
			}
			$data['item_number'] = $item_id;
			$data['employee_number'] = $employee_number;
			$data['data'] = $this->stock_model->add_item_data($employee_number, $item_id);
			//$data['categories'] = $this->stock_model->get_categories();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/return_vendor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function add_stock($item_id){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['item_id'])){ $item_id = $_GET['item_id']; }
			$item_qty = $_POST['quantity'];
			
			if(isset($_POST['employee_number'])) { $employee_number = $_POST['employee_number']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_return_stocks'){
				unset($_POST['action']);
				$data = $this->stock_model->vendor_return_data($item_number, $employee_number, $item_qty, $item_id);
				//var_dump($update_discard_data); 
				
				$item_number = $_POST['item_number'];
				$item_name = $_POST['item_name'];
				$company = $_POST['company'];
				$batch_number = $_POST['batch_number'];
				$item_qty = $_POST['quantity'];
				$_POST['employee_number'] = $employee_number;
				$_POST['return_date'] = date("Y-m-d H:i:s");
				//var_dump($this->stock_model->update_return_stock($item_qty, $item_id));die();
				$data = $this->stock_model->update_return_stock($item_qty, $item_id);
				$_POST['type'] = 'Center Return';
				$data = $this->stock_model->add_central_stock_report($_POST);
								
			}
			$data['item_number'] = $item_id;
			$data['employee_number'] = $employee_number;
			$data['data'] = $this->stock_model->add_item_data($employee_number, $item_id);
			//$data['categories'] = $this->stock_model->get_categories();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/add_stock', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function centaral_vendor(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_GET['item_number'])){ $item_id = $_GET['item_number']; }
			
			if(isset($_POST['item_number'])) { $item_id = $_POST['item_number']; }
			
			if(isset($_GET['item_id'])){ $item_id = $_GET['item_id']; }
			$item_qty = $_POST['quantity'];
			//if(isset($_POST['quantity'])) { $quantity = $_POST['quantity']; }
			if(isset($_POST['ID'])) { $ID = $_POST['ID']; }
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_vendor_item_centaral'){
				unset($_POST['action']);
				$data = $this->stock_model->vendor_return_central_data($item_number, $ID, $item_qty, $item_id);
				//var_dump($update_discard_data); 
				
				$item_number = $_POST['item_number'];
				$item_name = $_POST['item_name'];
				$company = $_POST['company'];
				$batch_number = $_POST['batch_number'];
				$reason = $_POST['reason'];
				$item_qty = $_POST['quantity'];
                $employee_number = $_POST['employee_number'];
				$_POST['expiry'] = date("Y-m-d H:i:s");
				$data = $this->stock_model->add_vendor_item($_POST);
								
			}
			$data['ID'] = $ID;
            $data['item_number'] = $item_id;
			$data['data'] = $this->stock_model->get_centeral_item($item_id);
			//$data['categories'] = $this->stock_model->get_categories();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/centaral_vendor', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/***********Return Medicine Details***********/
	
	function patient_medicine_return(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$receipt_number = $this->input->get('receipt_number', TRUE);
			$t_parameter = $this->input->get('t', TRUE);
			$data['data'] = $this->stock_model->get_return_medicine_data($receipt_number);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/patient_medicine_return', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	  public function medicine_return_report(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$patient_id = $this->input->get('patient_id', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/medicine_return_report";
        	$config["total_rows"] = $this->stock_model->patient_return_medcine($employee_number, $start_date, $end_date, $patient_id);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['investigate_result'] = $this->stock_model->patient_investigation_list_patination2($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $patient_id);
			
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["patient_id"] = $patient_id;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/medicine_return_report', $data);
			$this->load->view($template['footer']);
			//var_dump($html);
			//die;
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
		public function add_invoice()
	{
		$logg = checklogin();
		if($logg['status'] == true){
			
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'invoice_add'){
				unset($_POST['action']);
				//var_dump($_POST);die;
				$invoice_no = $_POST['invoice_no'];
				$no_of_item = $_POST['no_of_item'];
				$Total_amount = $_POST['Total_amount'];
				$invoice_date = $_POST['invoice_date'];
				$add_date = $_POST['add_date'];
				
				$data = $this->stock_model->invoice_item($_POST, $invoice_no, $no_of_item, $Total_amount, $invoice_date, $add_date);
				if($data > 0){
					header("location:" .base_url(). "stocks/add_invoice?m=".base64_encode('Item added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "stocks/add_invoice?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data = array();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/add_invoice', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	  public function invoice_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$invoice_no = $this->input->get('invoice_no', true);
			$config = array();
        	$config["base_url"] = base_url() . "stocks/invoice_list";
        	$config["total_rows"] = $this->stock_model->vendor_medicine_invoice($invoice_no);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			$data["links"] = $this->pagination->create_links();
			$data['invoice_result'] = $this->stock_model->vendor_medicine_invoice_patination($config["per_page"], $per_page, $invoice_no);
			$data["invoice_no"] = $invoice_no;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/invoice_list', $data);
			$this->load->view($template['footer']);
			}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	
	/****** ADD Purchase Item *****/
	
	public function add_purchase_item(){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_purchase_item'){
				unset($_POST['action']);
				
				$icounte = $mcounte = $ccounte = $spcounte = 1;
				$i_counte = $m_counte = $c_counte = $s_pcounte = array();
				$i_counter = $m_counter = $c_counter = $s_pcounter = array();
				foreach($_POST as $key => $val){
					$pos_c = strpos($key, 'product_name_');
					if ($pos_c === false) {} else {
						$cid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$c_counter[] = $cid;
					}	
				}

				if(!empty($c_counter)){
					foreach($c_counter as $key => $ccounte){
						if($_POST['product_name_'.$ccounte] == ''){
							unset($_POST['product_name_'.$ccounte]);
							unset($_POST['brand_name_'.$ccounte]);
							unset($_POST['batch_no_'.$ccounte]);
							unset($_POST['hsn_code_'.$ccounte]);
							unset($_POST['date_of_expiry_'.$ccounte]);
							unset($_POST['mrp_'.$ccounte]);
							unset($_POST['rate_per_unit_'.$ccounte]);
							unset($_POST['quantity_'.$ccounte]);
							unset($_POST['total_purchase_value_excl_gst_'.$ccounte]);
						    unset($_POST['discount_rate_'.$ccounte]);
							unset($_POST['discount_amt_'.$ccounte]);
							unset($_POST['total_purchase_after_discount_exculding_gst_'.$ccounte]);
							unset($_POST['gst_rate_'.$ccounte]);
							unset($_POST['total_purchase_value_incl_gst_'.$ccounte]);
							unset($_POST['monetary_value_'.$ccounte]);
							unset($_POST['category_'.$ccounte]);
							
						}else{
							// insert query
							$purchase_po_no = $_POST['purchase_po_no'];
							$po_date = $_POST['po_date'];
							$purchase_invoice_no = $_POST['purchase_invoice_no'];
							$date_of_purchase = $_POST['date_of_purchase'];
							$vendor_name = $_POST['vendor_name'];
							$vendor_code = $_POST['vendor_code'];
							$product_name = $_POST['product_name_'.$ccounte];
							$brand_name = $_POST['brand_name_'.$ccounte];
							$batch_no = $_POST['batch_no_'.$ccounte];
							$hsn_code = $_POST['hsn_code_'.$ccounte];
							$date_of_expiry = $_POST['date_of_expiry_'.$ccounte];
							$mrp = $_POST['mrp_'.$ccounte];
							$rate_per_unit = $_POST['rate_per_unit_'.$ccounte];
							$quantity = $_POST['quantity_'.$ccounte];
							$total_purchase_value_excl_gst = $_POST['total_purchase_value_excl_gst_'.$ccounte];
							$discount_rate = $_POST['discount_rate_'.$ccounte];
							$discount_amt = $_POST['discount_amt_'.$ccounte];
							$total_purchase_after_discount_exculding_gst = $_POST['total_purchase_after_discount_exculding_gst_'.$ccounte];
							$gst_rate = $_POST['gst_rate_'.$ccounte];
							$total_purchase_value_incl_gst = $_POST['total_purchase_value_incl_gst_'.$ccounte];
							$monetary_value = $_POST['monetary_value_'.$ccounte];
							$category = $_POST['category_'.$ccounte];
							$freight_forwarding_charges = $_POST['freight_forwarding_charges'];
							$centre_location = $_POST['centre_location'];
							$date_of_receiving['date_of_receiving'] = date("Y-m-d");
							$received_by = $_POST['received_by'];
							$entry_date_in_tally = $_POST['entry_date_in_tally'];
							$msme_applicability = $_POST['msme_applicability'];
							
						    $query = "INSERT INTO `hms_purchase_order` (purchase_po_no, po_date, purchase_invoice_no, date_of_purchase, vendor_name, vendor_code, product_name, brand_name, batch_no, hsn_code, date_of_expiry, mrp, rate_per_unit,quantity, total_purchase_value_excl_gst, discount_rate,discount_amt, total_purchase_after_discount_exculding_gst, gst_rate, total_purchase_value_incl_gst, monetary_value, category,freight_forwarding_charges,centre_location,date_of_receiving,received_by,entry_date_in_tally,msme_applicability) values ('$purchase_po_no','$po_date','$purchase_invoice_no','$date_of_purchase','$vendor_name','$vendor_code','$product_name','$brand_name','$batch_no','$hsn_code','$date_of_expiry','$mrp','$rate_per_unit','$quantity','$total_purchase_value_excl_gst','$discount_rate','$discount_amt','$total_purchase_after_discount_exculding_gst','$gst_rate','$total_purchase_value_incl_gst','$monetary_value','$category','$freight_forwarding_charges','$centre_location','$date_of_receiving','$received_by','$entry_date_in_tally','$msme_applicability')";
                            $result = run_form_query($query); 
							//$c_counte[] = array('product_name'=> $_POST['product_name_'.$ccounte],'brand_name'=> $_POST['brand_name_'.$ccounte],'batch_no'=> $_POST['batch_no_'.$ccounte],'hsn_code'=> $_POST['hsn_code_'.$ccounte],'date_of_expiry'=> $_POST['date_of_expiry_'.$ccounte],'mrp'=> $_POST['mrp_'.$ccounte],'rate_per_unit'=> $_POST['rate_per_unit_'.$ccounte],'quantity'=> $_POST['quantity_'.$ccounte],'total_purchase_value_excl_gst'=> $_POST['total_purchase_value_excl_gst_'.$ccounte],'discount_rate'=> $_POST['discount_rate_'.$ccounte],'discount_amt'=> $_POST['discount_amt_'.$ccounte],'total_purchase_after_discount_exculding_gst'=> $_POST['total_purchase_after_discount_exculding_gst_'.$ccounte],'gst_rate'=> $_POST['gst_rate_'.$ccounte],'total_purchase_value_incl_gst'=> $_POST['total_purchase_value_incl_gst_'.$ccounte],'monetary_value'=> $_POST['monetary_value_'.$ccounte],'category'=> $_POST['category_'.$ccounte]);
						}
					}
				}
				//$details = array();
				//$details['data']['consumables'] = $c_counte;
				//$post_arr['data'] = serialize($details);
				//$result = $this->stock_model->billing_purchase_item_insert($post_arr);
				if($result > 0){
					header("location:" .base_url(). "stocks/add_purchase_item?&t=sucess");
					die();
				}else{
					header("location:" .base_url(). "stocks/add_purchase_item?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/add_purchase_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}	
	
	function get_patient_name_2($patient_id){

		$name = $this->stock_model->get_patient_name($patient_id);

		return $name;

	}
	
	function get_all_centers(){
		$all_centers = $this->stock_model->get_centers();
		return $all_centers;
	}
	
	function get_center_name($center){
		$name = $this->stock_model->get_center_name($center);
		return $name;
	}
	
	function get_category_name($category){
		$name = $this->stock_model->get_category_name($category);
		return $name;		
	}
	
	function get_consumbles_list(){
		$consumbles = $this->stock_model->get_consumbles_list();
		return $consumbles;	
	}
	
	function get_employee_list(){
		$employee = $this->stock_model->get_employee_list();
		return $employee;	
	}

	function get_stock_user(){
		$user = $this->stock_model->get_stock_user();
		return $user;	
	}
	
	function get_injection_list(){
		$injection = $this->stock_model->get_injection_list();
		return $injection;	
	}
	
	function get_medicine_list(){
		$medicine = $this->stock_model->get_medicine_list();
		return $medicine;	
	}
	
	function get_brand_name($brand){
		$brand_name = $this->order_model->get_brand_name($brand);
		return $brand_name;
	}
	
	function get_vendor_number($item){
		$vendor_number = $this->stock_model->get_vendor_number($item);
		return $vendor_number;
	}

	function get_vendor_name($item){
		$vendor_name = $this->stock_model->get_vendor_name($item);
		return $vendor_name;
	}
	
	function get_product_name($item){
		$product_name = $this->stock_model->get_product_name($item);
		return $product_name;
	}
	
	function get_medicine_data($item){
		$receipt_number = $this->stock_model->get_medicine_data($receipt_number);
		return $receipt_number;
	}
	
	function get_employee_name($employee){
		$name = $this->stock_model->get_employee_name($employee);
		return $name;	
	}
	
	function approve_new_medicine($ID){
		$approved = $this->stock_model->approve_new_medicine($ID);
		
		// Get the referring URL
		$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('vendors');
	
		if($approved > 0){
			// Append success message to the URL
			$redirect_url .= "?m=".base64_encode('New Item approved!').'&t='.base64_encode('success');
		} else {
			// Append error message to the URL
			$redirect_url .= "?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error');
		}
	
		// Redirect to the referring page with the message
		header("Location: " . $redirect_url);
		die();
	}

	function disapprove_new_medicine($ID){
		$disapproved = $this->stock_model->disapprove_new_medicine($ID);
		
		// Get the referring URL
		$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('vendors');
	
		if($disapproved > 0){
			// Append success message to the URL
			$redirect_url .= "?m=".base64_encode('New Item Disapproved!').'&t='.base64_encode('success');
		} else {
			// Append error message to the URL
			$redirect_url .= "?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error');
		}
	
		// Redirect to the referring page with the message
		header("Location: " . $redirect_url);
		die();
	}
	
	function inactive_medicine($ID){
		$approved = $this->stock_model->inactive_medicine($ID);
		if($approved > 0){
			header("location:" .base_url(). "vendors/?m=".base64_encode('New Item Inactive!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "vendors/?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	
	public function transfer_stocks($ID = null) {
		$logg = checklogin();
		if ($logg['status'] == true) {
			$data = array();
			$employee_number = 0;
			// Check if $ID is passed in URL or POST
			if (is_null($ID)) {
				if (isset($_GET['ID'])) {
					$ID = $_GET['ID'];
				} elseif (isset($_POST['ID'])) {
					$ID = $_POST['ID'];
				} else {
					// Handle missing ID gracefully
					echo "Error: No ID provided for transfer.";
					return;
				}
			}
	
			// Continue with your existing logic
			if (isset($_POST['action']) && $_POST['action'] == 'transfer_stocks') {
				unset($_POST['action']);
				$item_number = $_POST['item_number'];
				$product_id = $_POST['product_id'];
				$item_name = $_POST['item_name'];
				$company = $_POST['company'];
				$batch_number = $_POST['batch_number'];
				$openstock = $_POST['openstock'];
				$expiry = $_POST['expiry'];
				$expiry_day = $_POST['expiry_day'];
				$add_date = $_POST['add_date'];
				$employee_number = $_POST['employee_number'];
				$vendor_price = $_POST['vendor_price'];
				$mrp = $_POST['mrp'];
				$hsn = $_POST['hsn'];
				$gstrate = $_POST['gstrate'];
				$gstdivision = $_POST['gstdivision'];
				$pack_size = $_POST['pack_size'];
				$brand_name = $_POST['brand_name'];
				$vendor_number = $_POST['vendor_number'];
				$generic_name = $_POST['generic_name'];
				$category = $_POST['category'];
				$quantity_out = $_POST['quantity_out'];
				$center_number = $_POST['center_number'];
				$department = $_POST['department'];
				$invoice_no = $_POST['invoice_no'];
				$date_of_purchase = $_POST['date_of_purchase'];
				$r_center_number = $_POST['r_center_number'];
				$r_employee_number = $_POST['r_employee_number'];
	            $r_department = $_POST['r_department'];  
				$remarks = $_POST['remarks'];  
				$data = $this->stock_model->transfer_stock_report($_POST, $item_number, $product_id, $item_name, $company, $batch_number, $openstock, $expiry, $expiry_day, $add_date, $employee_number, $vendor_price, $mrp, $hsn, $gstrate, $gstdivision, $pack_size, $brand_name, $vendor_number,$generic_name,$category, $quantity_out, $center_number, $department, $invoice_no, $date_of_purchase, $r_center_number, $r_employee_number, $r_department, $remarks);
				if ($data > 0) {
					// Update stock and 
					$update_stock = $this->stock_model->deduct_transfer_stock($item_number, $invoice_no, $batch_number, $status, $employee_number, $quantity_out, $center_number, $department);
					header("location:" . base_url() . "stocks/transfer_stocks/" . $ID . "?m=" . base64_encode('Transfer Stock added successfully!') . '&t=' . base64_encode('success'));
					die();
				} else {
					header("location:" . base_url() . "stocks/transfer_stocks/" . $ID . "?m=" . base64_encode('Something went wrong!') . '&t=' . base64_encode('error'));
					die();
				}
			}
	
			// Prepare data and load views
			$data['employee_number'] = $employee_number;
			$data['ID'] = $ID;
			$data['data'] = $this->stock_model->stock_transfer_details($ID);
			$data['brands'] = $this->stock_model->get_brands();
			$data['vendors'] = $this->vendors_model->get_vendors();
			
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/transfer_stocks', $data);
			$this->load->view($template['footer']);
		} else {
			header("location:" . base_url() . "");
		}
	}
	

	public function transfer_stock_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$center_number = $this->input->get('center_number', true);

			$config = array();
        	$config["base_url"] = base_url() . "stocks/transfer_stock_list";
        	$config["total_rows"] = $this->stock_model->transfer_stock_count($item_name, $batch_number, $start_date, $end_date, $center_number);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			$data["links"] = $this->pagination->create_links();
			$data['invoice_result'] = $this->stock_model->transfer_stock_patination($config["per_page"], $per_page, $item_name, $batch_number, $start_date, $end_date, $center_number);
			$data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["center_number"] = $center_number;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/transfer_stock_list', $data);
			$this->load->view($template['footer']);
			}else{
			header("location:" .base_url(). "");
			die();
		}
	}
			
	public function update_transfer_item($ID){
    $logg = checklogin();
    if ($logg['status'] !== true) {
        header("location:" . base_url());
        die();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_transfer_item') {
        $item_number = $_POST['item_number'];
		$product_id = $_POST['product_id'];
		$item_name = $_POST['item_name'];
		$company = $_POST['company'];
		$batch_number = $_POST['batch_number'];
		$openstock = $_POST['openstock'];
		$expiry = $_POST['expiry'];
		$expiry_day = $_POST['expiry_day'];
        $add_date = $_POST['add_date'];
		$vendor_price = $_POST['vendor_price'];
		$mrp = $_POST['mrp'];
		$hsn = $_POST['hsn'];
		$gstrate = $_POST['gstrate'];
		$gstdivision = $_POST['gstdivision'];
		$pack_size = $_POST['pack_size'];
		$brand_name = $_POST['brand_name'];
		$vendor_number = $_POST['vendor_number'];
		$generic_name = $_POST['generic_name'];
		$category = $_POST['category'];
		$quantity = $_POST['quantity'];
		$center_number = $_POST['center_number'];
        $employee_number = $_POST['employee_number'];
        $department = $_POST['department'];
		$date_of_purchase = $_POST['date_of_purchase'];
		$invoice_no = $_POST['invoice_no'];
		$check_center_item_new = $this->stock_model->check_center_item_new2($item_number, $invoice_no, $batch_number, $center_number,  $employee_number, $department, $status);
		if (!empty($check_center_item_new)) {    
			$data = $this->stock_model->update_transfer_item($_POST, $quantity, $invoice_no, $center_number, $item_number, $employee_number, $department, $item_name, $company, $batch_number, $openstock, $expiry, $expiry_day, $add_date, $vendor_price, $mrp, $hsn, $gstrate, $gstdivision, $quantity_out, $date_of_purchase);
			var_dump('Updated');
		} else {
			$data = $this->stock_model->transfer_stock_report_center($_POST, $item_number, $product_id, $item_name, $company, $batch_number, $quantity, $openstock, $expiry, $expiry_day, $add_date, $employee_number, $vendor_price, $mrp, $hsn, $gstrate, $gstdivision, $pack_size, $brand_name, $vendor_number, $generic_name, $category, $quantity_out, $center_number, $department, $invoice_no, $date_of_purchase);
			var_dump('Inserted'); 
			if ($data > 0) {
				header("location:" . base_url() . "stocks/update_transfer_item/" . $ID . "?m=" . base64_encode('Item stock updated successfully!') . '&t=' . base64_encode('success'));
			} else {
				header("location:" . base_url() . "stocks/update_transfer_item/" . $ID . "?m=" . base64_encode('Something went wrong!') . '&t=' . base64_encode('error'));
			}
		}
        die();
    } else {
        $data['data'] = $this->stock_model->get_transfer_data($ID);
        $template = get_header_template($logg['role']);
        $this->load->view($template['header']);
        $this->load->view('stocks/update_transfer_item', $data);
        $this->load->view($template['footer']);
    }
}

public function center_audit_report() {
	$logg = checklogin();
	error_reporting(0);

	if ($logg['status'] == true) {
		$config = array();
		if (isset($_POST['action']) && $_POST['action'] == 'center_audit_report') {
			unset($_POST['action']);
			
			// Loop through submitted data based on the number of items
			$items = $_POST['item_name'];
			for ($i = 0; $i < count($items); $i++) {
				// Check if `item_name` is empty for this row
				if (empty($_POST['item_name'][$i])) {
					continue;  // Skip if the item_name is empty
				}

				// Fetching values safely
				$item_name = $this->db->escape($_POST['item_name'][$i]);
				$batch_number = $this->db->escape($_POST['batch_number'][$i]);
				$quantity = $this->db->escape($_POST['quantity'][$i]);
				$physical_quantity = $this->db->escape($_POST['physical_quantity'][$i]);
				$register_quantity = $this->db->escape($_POST['register_quantity'][$i]);
				$short = $this->db->escape($_POST['short'][$i]);
				$excess = $this->db->escape($_POST['excess'][$i]);
				$damage = $this->db->escape($_POST['damage'][$i]);
				$expiry = $this->db->escape($_POST['expiry'][$i]);
				$expiry_day = $this->db->escape($_POST['expiry_day'][$i]);
				$employee_number = $this->db->escape($_POST['employee_number'][$i]);
				$requisition = $this->db->escape($_POST['requisition'][$i]);

				// Remove additional single quotes manually added
				// The `$this->db->escape()` already handles escaping, so we don't need to add quotes manually.
				$sql = "INSERT INTO `".$this->config->item('db_prefix')."audit_stocks` 
					(`item_name`, `batch_number`, `quantity`, `physical_quantity`, `register_quantity`, `short`, `excess`, `damage`, `expiry`, `expiry_day`, `employee_number`, `requisition`, `add_date`) 
					VALUES 
					($item_name, $batch_number, $quantity, $physical_quantity, $register_quantity, $short, $excess, $damage, $expiry, $expiry_day, $employee_number, $requisition, '".date("Y-m-d H:i:s")."')";

				// Execute the query
				$result = $this->db->query($sql);
			}

			// Redirect based on query result
			if ($result) {
				header("location:" . base_url() . "stocks/center_audit_report/" . $ID . "?m=" . base64_encode('Transfer Stock added successfully!') . '&t=' . base64_encode('success'));
				die();
			} else {
				header("location:" . base_url() . "stocks/center_audit_report/" . $ID . "?m=" . base64_encode('Something went wrong!') . '&t=' . base64_encode('error'));
				die();
			}
		}

		$data['investigate_result'] = $this->stock_model->center_audit_patination();
		$template = get_header_template($logg['role']);
		$this->load->view($template['header']);
		$this->load->view('stocks/center_audit_report', $data);
		$this->load->view($template['footer']);
	} else {
		header("location:" . base_url() . "");
		die();
	}
}

	public function all_center_audit_report(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){
			$config = array();
			
			$per_page = $this->input->get('per_page', true);
			$employee_number = $this->input->get('employee_number', true);
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$item_number = $this->input->get('item_number', true);
           
			if (isset($_POST['action']) && $_POST['action'] == 'all_center_audit_report') {
			unset($_POST['action']);
			
			// Loop through submitted data based on the number of items
			$items = $_POST['item_name'];
			for ($i = 0; $i < count($items); $i++) {
				// Check if `item_name` is empty for this row
				if (empty($_POST['item_name'][$i])) {
					continue;  // Skip if the item_name is empty
				}

				// Fetching values safely
				$item_name = $this->db->escape($_POST['item_name'][$i]);
				$batch_number = $this->db->escape($_POST['batch_number'][$i]);
				$quantity = $this->db->escape($_POST['quantity'][$i]);
				$physical_quantity = $this->db->escape($_POST['physical_quantity'][$i]);
				$register_quantity = $this->db->escape($_POST['register_quantity'][$i]);
				$short = $this->db->escape($_POST['short'][$i]);
				$excess = $this->db->escape($_POST['excess'][$i]);
				$damage = $this->db->escape($_POST['damage'][$i]);
				$expiry = $this->db->escape($_POST['expiry'][$i]);
				$expiry_day = $this->db->escape($_POST['expiry_day'][$i]);
				$employee_number = $this->db->escape($_POST['employee_number'][$i]);
				$requisition = $this->db->escape($_POST['requisition'][$i]);
				$employee_name = $this->db->escape($_POST['employee_name'][$i]);

				// Remove additional single quotes manually added
				// The `$this->db->escape()` already handles escaping, so we don't need to add quotes manually.
				$sql = "INSERT INTO `".$this->config->item('db_prefix')."audit_stocks` 
					(`item_name`, `batch_number`, `quantity`, `physical_quantity`, `register_quantity`, `short`, `excess`, `damage`, `expiry`, `expiry_day`, `employee_number`, `requisition`,`employee_name`, `add_date`) 
					VALUES 
					($item_name, $batch_number, $quantity, $physical_quantity, $register_quantity, $short, $excess, $damage, $expiry, $expiry_day, $employee_number, $requisition, $employee_name, '".date("Y-m-d H:i:s")."')";

				// Execute the query
				$result = $this->db->query($sql);
			}

			// Redirect based on query result
			if ($result) {
				header("location:" . base_url() . "stocks/all_center_audit_report/" . $ID . "?m=" . base64_encode('Audit Report added successfully!') . '&t=' . base64_encode('success'));
				die();
			} else {
				header("location:" . base_url() . "stocks/all_center_audit_report/" . $ID . "?m=" . base64_encode('Something went wrong!') . '&t=' . base64_encode('error'));
				die();
			}
		}
			
        	$data["investigate_result"] = $this->stock_model->get_all_center_stocks_audit($employee_number, $item_name, $batch_number, $item_number);
        	$data["employee_number"] = $employee_number;
			$data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["item_number"] = $item_number;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/all_center_audit_report', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
/********Return List**********/
	public function return_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$employee_number = $this->input->get('employee_number', true);

			$config = array();
        	$config["base_url"] = base_url() . "stocks/return_list";
        	$config["total_rows"] = $this->stock_model->return_stock_count($item_name, $batch_number, $start_date, $end_date, $employee_number);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			$data["links"] = $this->pagination->create_links();
			$data['invoice_result'] = $this->stock_model->return_stock_patination($config["per_page"], $per_page, $item_name, $batch_number, $start_date, $end_date, $employee_number);
			$data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["employee_number"] = $employee_number;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/return_list', $data);
			$this->load->view($template['footer']);
			}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/***********Vendors Product Return List***********/

	public function vendor_return_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$item_number = $this->input->get('item_number', true);
            $expiry = $this->input->get('expiry', true); 
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_vendor_return($employee_number, $start_date, $end_date, $generic_name, $item_name);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=All-Vendor-Return-Medicine-'.$start_date.'-'.$end_date.'-'.date("Y-m-d H-i-s").'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Product Number,Company, Product Name, Batch Number, Generic Name, HSN, GST, Pack Size, Quantity (Unit), Vendor Price, MRP, Expiry, Status, Employee Name, Stock Value, Center Name,Department, Date Of Purchase';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {
					$lead_arr = array($val['item_number'], $val['company'],$val['item_name'], $val['batch_number'], $val['generic_name'], $val['hsn'], $val['gstrate'], $val['pack_size'], $val['quantity'], $val['vendor_price'], $val['mrp'], $val['expiry'], $val['status'], $val['employee_number'],$val['quantity'] * $val['vendor_price'],$val['center_number'],$val['department'],$val['date_of_purchase']);
					fputcsv($fp, $lead_arr);
				}
				
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/vendor_return_list";
        	$config["total_rows"] = $this->stock_model->get_vendor_return($employee_number, $start_date, $end_date, $item_name, $batch_number, $item_number, $expiry);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['vendor_return'] = $this->stock_model->get_vendor_return_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $item_name, $batch_number, $item_number, $expiry);
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["item_number"] = $item_number;
			$data["expiry"] = $expiry;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/vendor_return_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/***********Product Discard List***********/
	
	public function product_discard_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$employee_number = $this->input->get('employee_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$item_number = $this->input->get('item_number', true);
            $expiry = $this->input->get('expiry', true); 
			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->stock_model->export_product_discard($employee_number, $start_date, $end_date, $generic_name, $item_name);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=All-Product-Discard-'.$start_date.'-'.$end_date.'-'.date("Y-m-d H-i-s").'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Product Number,Company, Product Name, Batch Number, Generic Name, HSN, GST, Pack Size, Quantity (Unit), Vendor Price, MRP, Expiry, Status, Employee Name, Stock Value, Center Name,Department, Date Of Purchase';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {
					$lead_arr = array($val['item_number'], $val['company'],$val['item_name'], $val['batch_number'], $val['generic_name'], $val['hsn'], $val['gstrate'], $val['pack_size'], $val['quantity'], $val['vendor_price'], $val['mrp'], $val['expiry'], $val['status'], $val['employee_number'],$val['quantity'] * $val['vendor_price'],$val['center_number'],$val['department'],$val['date_of_purchase']);
					fputcsv($fp, $lead_arr);
				}
				
				fclose($fp);
				exit();
			}
			
			$config = array();
        	$config["base_url"] = base_url() . "stocks/product_discard_list";
        	$config["total_rows"] = $this->stock_model->get_product_discard($employee_number, $start_date, $end_date, $item_name, $batch_number, $item_number, $expiry);
        	$config["per_page"] = 10;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['product_discard'] = $this->stock_model->get_product_discard_patination($config["per_page"], $per_page, $employee_number, $start_date, $end_date, $item_name, $batch_number, $item_number, $expiry);
			$data["employee_number"] = $employee_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["item_number"] = $item_number;
			$data["expiry"] = $expiry;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/product_discard_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function add_orders(){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_item_order'){
				unset($_POST['action']);
				
				$icounte = $mcounte = $ccounte = $spcounte = 1;
				$i_counte = $m_counte = $c_counte = $s_pcounte = array();
				$i_counter = $m_counter = $c_counter = $s_pcounter = array();
				foreach($_POST as $key => $val){
					$pos_c = strpos($key, 'consumables_name_');
					if ($pos_c === false) {} else {
						$cid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$c_counter[] = $cid;
					}	
				}

				if(!empty($c_counter)){
					foreach($c_counter as $key => $ccounte){
						if($_POST['consumables_name_'.$ccounte] == ''){
							unset($_POST['consumables_serial_'.$ccounte]);
							unset($_POST['consumables_name_'.$ccounte]);
							unset($_POST['consumables_company_'.$ccounte]);
							unset($_POST['consumables_item_name_'.$ccounte]);
							unset($_POST['consumables_batch_number_'.$ccounte]);
							unset($_POST['consumables_vendor_price_'.$ccounte]);
							unset($_POST['consumables_mrp_'.$ccounte]);
							unset($_POST['consumables_hsn_'.$ccounte]);
							unset($_POST['consumables_pack_size_'.$ccounte]);
							unset($_POST['consumables_gstrate_'.$ccounte]);
							unset($_POST['consumables_gstdivision_'.$ccounte]);
							unset($_POST['consumables_price_'.$ccounte]);
							unset($_POST['consumables_quantity_'.$ccounte]);
							unset($_POST['consumables_brand_name_'.$ccounte]);
						}else{
							// insert query
							$order_number = getGUID();
							$po_number = $_POST['po_number'];
							$item_number = $_POST['consumables_serial_'.$ccounte];
							$company = $_POST['consumables_company_'.$ccounte];
							$item_name = $_POST['consumables_item_name_'.$ccounte];
							$batch_number = $_POST['consumables_batch_number_'.$ccounte];
							$vendor_price = $_POST['consumables_vendor_price_'.$ccounte];
							$mrp = $_POST['consumables_mrp_'.$ccounte];
							$hsn = $_POST['consumables_hsn_'.$ccounte];
							$pack_size = $_POST['consumables_pack_size_'.$ccounte];
							$gstrate = $_POST['consumables_gstrate_'.$ccounte];
							$gstdivision = $_POST['consumables_gstdivision_'.$ccounte];
							$total_vendor_price = $_POST['consumables_price_'.$ccounte];
							$order_qty_pack = $_POST['consumables_quantity_'.$ccounte];
							$brand_name = $_POST['consumables_brand_name_'.$ccounte];
							$vendor_number = $_POST['vendor_number'];
							$ship_to = $_POST['ship_to'];
							$bill_to = $_POST['bill_to'];
							$center = $_POST['center'];
							$order_quantity = $pack_size * $order_qty_pack;
							
						    $query = "INSERT INTO `hms_orders` (order_number, po_number, item_number, company, item_name, batch_number, vendor_price, mrp, hsn, gstrate, gstdivision, pack_size, total_vendor_price, order_quantity, order_qty_pack, vendor_number, ship_to, bill_to, brand_name, center, create_date) values ('$order_number','$po_number','$item_number','$company','$item_name','$batch_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','$pack_size','$total_vendor_price','$order_quantity','$order_qty_pack','$vendor_number','$ship_to','$bill_to','$brand_name','$center','".date("Y-m-d H-i-s")."')";
                            $result = run_form_query($query); 
                            $query2 = "INSERT INTO hms_ponumber (order_number, po_number, created) VALUES ('$order_number','$po_number','".date("Y-m-d")."')";
                            $result = run_form_query($query2);
						}
					}
				}
								
				$details = array();
				if($result > 0){
					header("location:" .base_url(). "stocks/order_items/?po_number=".$post_arr['po_number']."&t=medicine");
					die();
				}else{
					header("location:" .base_url(). "stocks/order_items?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			$data['consumables'] = $this->stock_model->get_central_all_stocks();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/add_orders', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function internal_orders(){
		$logg = checklogin();
		if($logg['status'] == true){
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'internal_orders'){
				unset($_POST['action']);
				
				$icounte = $mcounte = $ccounte = $spcounte = 1;
				$i_counte = $m_counte = $c_counte = $s_pcounte = array();
				$i_counter = $m_counter = $c_counter = $s_pcounter = array();
				foreach($_POST as $key => $val){
					$pos_c = strpos($key, 'consumables_name_');
					if ($pos_c === false) {} else {
						$cid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
						$c_counter[] = $cid;
					}	
				}

				if(!empty($c_counter)){
					foreach($c_counter as $key => $ccounte){
						if($_POST['consumables_name_'.$ccounte] == ''){
							unset($_POST['consumables_serial_'.$ccounte]);
							unset($_POST['consumables_name_'.$ccounte]);
							unset($_POST['consumables_company_'.$ccounte]);
							unset($_POST['consumables_item_name_'.$ccounte]);
							unset($_POST['consumables_batch_number_'.$ccounte]);
							unset($_POST['consumables_vendor_price_'.$ccounte]);
							unset($_POST['consumables_mrp_'.$ccounte]);
							unset($_POST['consumables_hsn_'.$ccounte]);
							unset($_POST['consumables_pack_size_'.$ccounte]);
							unset($_POST['consumables_gstrate_'.$ccounte]);
							unset($_POST['consumables_gstdivision_'.$ccounte]);
							unset($_POST['consumables_price_'.$ccounte]);
							unset($_POST['consumables_quantity_'.$ccounte]);
							unset($_POST['consumables_brand_name_'.$ccounte]);
						}else{
							// insert query
							$order_number = getGUID();
							$po_number = $_POST['po_number'];
							$item_number = $_POST['consumables_serial_'.$ccounte];
							$company = $_POST['consumables_company_'.$ccounte];
							$item_name = $_POST['consumables_item_name_'.$ccounte];
							$batch_number = $_POST['consumables_batch_number_'.$ccounte];
							$vendor_price = $_POST['consumables_vendor_price_'.$ccounte];
							$mrp = $_POST['consumables_mrp_'.$ccounte];
							$hsn = $_POST['consumables_hsn_'.$ccounte];
							$pack_size = $_POST['consumables_pack_size_'.$ccounte];
							$gstrate = $_POST['consumables_gstrate_'.$ccounte];
							$gstdivision = $_POST['consumables_gstdivision_'.$ccounte];
							$total_vendor_price = $_POST['consumables_price_'.$ccounte];
							$order_qty_pack = $_POST['consumables_quantity_'.$ccounte];
							$brand_name = $_POST['consumables_brand_name_'.$ccounte];
							$vendor_number = $_POST['vendor_number'];
							$ship_to = $_POST['ship_to'];
							$bill_to = $_POST['bill_to'];
							$center = $_POST['center'];
							$order_quantity = $pack_size * $order_qty_pack;
							
						    $query = "INSERT INTO `hms_internal_orders` (order_number, po_number, item_number, company, item_name, batch_number, vendor_price, mrp, hsn, gstrate, gstdivision, pack_size, total_vendor_price, order_quantity, order_qty_pack, vendor_number, ship_to, bill_to, brand_name, center, create_date) values ('$order_number','$po_number','$item_number','$company','$item_name','$batch_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','$pack_size','$total_vendor_price','$order_quantity','$order_qty_pack','$vendor_number','$ship_to','$bill_to','$brand_name','$center','".date("Y-m-d H-i-s")."')";
                            $result = run_form_query($query); 
                        }
					}
				}
								
				$details = array();
				if($result > 0){
					header("location:" .base_url(). "stocks/internal_order_items/?po_number=".$po_number."&t=medicine");
					die();
				}else{
					header("location:" .base_url(). "stocks/internal_order_items?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			$data['consumables'] = $this->stock_model->get_central_all_stocks();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/internal_orders', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	function internal_order_items(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$po_number = $this->input->get('po_number', TRUE);
			$t_parameter = $this->input->get('t', TRUE);
			$data['data'] = $this->stock_model->get_internal_order_data($po_number);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/internal_order_items', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	function order_items(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			$po_number = $this->input->get('po_number', TRUE);
			$t_parameter = $this->input->get('t', TRUE);
			$data['data'] = $this->stock_model->get_order_data($po_number);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('stocks/order_items', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	function approve_transfer_stocks($ID){
		$approved = $this->stock_model->approve_transfer_stocks($ID);
		if($approved > 0){
			header("location:" .base_url(). "stocks/transfer_stock_list?m=".base64_encode('Transfer Stock approved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "stocks/transfer_stock_list?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function disapprove_transfer_stocks($ID){
		$approved = $this->stock_model->disapprove_transfer_stocks($ID);
		if($approved > 0){
			header("location:" .base_url(). "stocks/transfer_stock_list?m=".base64_encode('Transfer Stock Disapproved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "stocks/transfer_stock_list?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	// Get transfer history for a specific item
	public function get_transfer_history() {
		$logg = checklogin();
		if($logg['status'] == true) {
			$item_number = $this->input->post('item_number');
			
			if(empty($item_number)) {
				echo json_encode(['status' => 'error', 'message' => 'Item number is required']);
				return;
			}
			
			$transfers = $this->stock_model->get_transfer_history_by_item($item_number);
			
			if(!empty($transfers)) {
				echo json_encode(['status' => 'success', 'data' => $transfers]);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'No transfer history found']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
		}
	}
	
	// Export transfer history
	public function export_transfer_history() {
		$logg = checklogin();
		if($logg['status'] == true) {
			$item_number = $this->input->get('item_number');
			
			if(empty($item_number)) {
				echo "Error: Item number is required";
				return;
			}
			
			$transfers = $this->stock_model->get_transfer_history_by_item($item_number);
			
			// Set headers for CSV download
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=Transfer-History-' . $item_number . '-' . date("Y-m-d-H-i-s") . '.csv');
			
			$fp = fopen('php://output', 'w');
			
			// Add CSV headers
			$headers = ['Transfer Date', 'Item Number', 'Item Name', 'From Center', 'To Center', 'Quantity', 'Status', 'Employee', 'Remarks', 'Invoice No', 'Purchase Date'];
			fputcsv($fp, $headers);
			
			// Add data rows
			foreach($transfers as $transfer) {
				$status = $transfer['status'] == '1' ? 'Approved' : ($transfer['status'] == '2' ? 'Rejected' : 'Pending');
				$row = [
					$transfer['add_date'],
					$transfer['item_number'],
					$transfer['item_name'],
					$transfer['center_number'],
					$transfer['r_center_number'],
					$transfer['quantity'],
					$status,
					$transfer['employee_number'],
					$transfer['remarks'],
					$transfer['invoice_no'],
					$transfer['date_of_purchase']
				];
				fputcsv($fp, $row);
			}
			
			fclose($fp);
			exit();
		} else {
			echo "Unauthorized access";
		}
	}
	
	// Update medicine type for individual item
	public function update_medicine_type() {
		$logg = checklogin();
		if($logg['status'] == true) {
			$item_number = $this->input->post('item_number');
			$medicine_type = $this->input->post('medicine_type');
			
			if(empty($item_number) || empty($medicine_type)) {
				echo json_encode(['status' => 0, 'message' => 'Item number and medicine type are required']);
				return;
			}
			
			// Validate medicine type
			if(!in_array($medicine_type, ['ipd', 'opd'])) {
				echo json_encode(['status' => 0, 'message' => 'Invalid medicine type']);
				return;
			}
			
			// Update the medicine type in database
			$sql = "UPDATE " . $this->config->item('db_prefix') . "stocks SET medicine_type = ? WHERE item_number = ?";
			$result = $this->db->query($sql, [$medicine_type, $item_number]);
			
			if($result && $this->db->affected_rows() > 0) {
				echo json_encode(['status' => 1, 'message' => 'Medicine type updated successfully']);
			} else {
				echo json_encode(['status' => 0, 'message' => 'Failed to update medicine type']);
			}
		} else {
			echo json_encode(['status' => 0, 'message' => 'Unauthorized access']);
		}
	}
	
	// Bulk update medicine type for selected items
	public function bulk_update_medicine_type() {
		$logg = checklogin();
		if($logg['status'] == true) {
			$item_numbers = $this->input->post('item_numbers');
			$medicine_type = $this->input->post('medicine_type');
			
			if(empty($item_numbers) || !is_array($item_numbers) || empty($medicine_type)) {
				echo json_encode(['status' => 0, 'message' => 'Item numbers and medicine type are required']);
				return;
			}
			
			// Validate medicine type
			if(!in_array($medicine_type, ['ipd', 'opd'])) {
				echo json_encode(['status' => 0, 'message' => 'Invalid medicine type']);
				return;
			}
			
			$updated_count = 0;
			foreach($item_numbers as $item_number) {
				$sql = "UPDATE " . $this->config->item('db_prefix') . "stocks SET medicine_type = ? WHERE item_number = ?";
				$result = $this->db->query($sql, [$medicine_type, $item_number]);
				if($result && $this->db->affected_rows() > 0) {
					$updated_count++;
				}
			}
			
			if($updated_count > 0) {
				echo json_encode(['status' => 1, 'message' => 'Updated ' . $updated_count . ' items successfully', 'updated' => $updated_count]);
			} else {
				echo json_encode(['status' => 0, 'message' => 'No items were updated']);
			}
		} else {
			echo json_encode(['status' => 0, 'message' => 'Unauthorized access']);
		}
	}
} 
