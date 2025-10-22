<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('form');
		$this->load->helper('url_helper');
		$this->load->library('session');
		$this->load->model('order_model');
		$this->load->model('stock_model');
		$this->load->model('vendors_model');
		$this->load->helper('myhelper');
		$this->load->library("pagination");
	}

	public function orders()
	{
		$logg = checklogin();
		if($logg['status'] == true)
		{
			$data = array();
			$data['data'] = $this->order_model->get_order_data();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/orders', $data);
			$this->load->view($template['footer']);
		} else {
			header("location:" .base_url(). "");
			die;
		}
	}
	
	/*public function my_orders()
	{
		$logg = checklogin();
		if($logg['status'] == true)
		{
			$data = array();
			$data['data'] = $this->order_model->get_my_orders_data();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/my_orders', $data);
			$this->load->view($template['footer']);
		} else {
			header("location:" .base_url(). "");
			die;
		}
	}*/
	
	public function my_orders(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$purchase_order = $this->input->get('purchase_order', true);
			$po_number = $this->input->get('po_number', true);
			$vendor_number = $this->input->get('vendor_number', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$item_name = $this->input->get('item_name', true);
			$ship_to = $this->input->get('ship_to', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "orders/my_orders";
        	$config["total_rows"] = $this->order_model->get_my_orders_data($purchase_order, $po_number, $vendor_number, $start_date, $end_date, $item_name, $ship_to);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['data'] = $this->order_model->get_my_orders_data_count($config["per_page"], $per_page, $purchase_order, $po_number, $vendor_number, $start_date, $end_date, $item_name,$ship_to);
			
			$data["purchase_order"] = $purchase_order;
			$data["po_number"] = $po_number;
			$data["vendor_number"] = $vendor_number;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["item_name"] = $item_name;
			$data["ship_to"] = $ship_to;
			$template = get_header_template($logg['role']);
			$data['vendors'] = $this->vendors_model->get_vendors();
			$this->load->view($template['header']);
			$this->load->view('orders/my_orders', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function my_internal_orders(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$po_number = $this->input->get('po_number', true);
			$item_name = $this->input->get('item_name', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "orders/my_internal_orders";
        	$config["total_rows"] = $this->order_model->get_my_internal_orders_data($po_number, $item_name);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['data'] = $this->order_model->get_my_orders_internal_data_count($config["per_page"], $per_page, $po_number, $item_name);
			
			$data["po_number"] = $po_number;
			$data["item_name"] = $item_name;
			$template = get_header_template($logg['role']);
			$data['vendors'] = $this->vendors_model->get_vendors();
			$this->load->view($template['header']);
			$this->load->view('orders/my_internal_orders', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function update_order($order_id){
		$logg = checklogin();
		if($logg['status'] == true)
		{
			$data = array();
			$item = $_GET['i'];
			$qty = $_GET['q'];
			$delivery_date = $_GET['d'];
			
			$data = $this->order_model->update_order_data($order_id, $item, $qty, $delivery_date);
			if($data > 0){
				header("location:" .base_url(). "orders/orders?m=".base64_encode('Order status updated!').'&t='.base64_encode('success'));
				die();
			}else{
				header("location:" .base_url(). "orders/orders?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
				die();
			}
		} else {
			header("location:" .base_url(). "");
			die;
		}
	}
	
	public function update_admin_order_item($item_id)
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$order_id = $_GET['i'];
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_order_item'){
				unset($_POST['action']);
				$action_type = $_POST['action_type']; unset($_POST['action_type']);
				$order_number = $_POST['order_number']; unset($_POST['order_number']);
				$batch_number = $_POST['batch_number'];
				$product_id = $_POST['product_id'];
				$brand_name = $_POST['brand_name'];
				$vendor_number = $_POST['vendor_number'];
				$vendor_price = $_POST['vendor_price'];
				$order_qty = $_POST['quantity'];
				$invoice_no = $_POST['invoice_no'];
				$po_date = $_POST['po_date']; unset($_POST['po_date']);
				$vendor_name = $_POST['vendor_name']; unset($_POST['vendor_name']);
				$freight_forwarding_charges = $_POST['freight_forwarding_charges']; unset($_POST['freight_forwarding_charges']);
				$total_purchase_value_excl_gst = $_POST['total_purchase_value_excl_gst']; unset($_POST['total_purchase_value_excl_gst']);
				$centre_location = $_POST['centre_location']; unset($_POST['centre_location']);
				$date_of_receiving = $_POST['date_of_receiving']; unset($_POST['date_of_receiving']);
				$received_by = $_POST['received_by']; unset($_POST['received_by']);
				$discount_amt = $_POST['discount_amt']; unset($_POST['discount_amt']);
				$comment = $_POST['comment']; unset($_POST['comment']);
				$purchase_po_no = $_POST['purchase_po_no']; unset($_POST['purchase_po_no']);
				$date_of_purchase = $_POST['date_of_purchase'];
				if($action_type == "insert"){// var_dump($_POST);die;
					$_POST['add_date'] = date("Y-m-d H:i:s");
					$_POST['status'] = 1;
					$_POST['quantity'] = ($_POST['lots']*$_POST['units']);
					$delivery_date = $_POST['delivery_date'];unset($_POST['delivery_date']);
					$center_order = $this->order_model->get_center_order_number($item_id);
					$new_item_number = $_POST['item_number'];
					//$data = $this->order_model->insert_admin_order_item_data($_POST);
					if($data > 0){
						// center replaced				
						$order = array();
						$order['item_number'] = $new_item_number;
						$order['order_quantity'] = $center_order['order_quantity'];
						$order['batch_number'] = $center_order['batch_number'];
						$order['invoice_no'] = $center_order['invoice_no'];
						$order['comment'] = $center_order['comment'];
						$order['create_date'] = date("Y-m-d H:i:s");
						$order['update_date'] = date("Y-m-d H:i:s");
						$order['delivery_date'] = $delivery_date;
						$order['center_number'] = $center_order['center_number'];
						$order['employee_number'] = $center_order['employee_number'];
						$order['status'] = 0;
						$order['d_status'] = 1;
						$order['cancelled'] = 0;
						$order['replaced'] = 0;
						// centeral replaced
						$centeral_order = array();
						$centeral_order['order_number'] = $order_number;
						$centeral_order['item_number'] = $new_item_number;
						$centeral_order['item_name'] = $_POST['item_name'];
						$centeral_order['company'] = $_POST['company'];
						$centeral_order['brand_name'] = $_POST['brand_name'];
						$centeral_order['vendor_number'] = $_POST['vendor_number'];
						$centeral_order['batch_number'] = $_POST['batch_number'];
						$centeral_order['invoice_no'] = $_POST['invoice_no'];
						$centeral_order['comment'] = $_POST['comment'];
						$centeral_order['price'] = $_POST['price'];
						$centeral_order['quantity'] = $_POST['quantity'];
						$centeral_order['create_date'] = date("Y-m-d H:i:s");
						$centeral_order['update_date'] = date("Y-m-d H:i:s");
						$centeral_order['status'] = 1;
						$centeral_order['received'] = 1;
						$centeral_order['purchase_order'] = 1;
						$centeral_order['cancelled'] = 0;
						$centeral_order['replaced'] = 0;
						$data = $this->order_model->insert_replaced_order_item($centeral_order, $order, $item_id, $center_order);
					}else{
						header("location:" .base_url(). "orders/my_orders?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
						die();
					}
				}else{
					$check_center_new_item = $this->order_model->check_centeral_item($product_id, $brand_name, $vendor_number, $batch_number, $vendor_price);
				    if($check_center_new_item > 0){
					    $data = $this->order_model->update_admin_order_item_data($_POST);
					}else{
					    unset($_POST['brand']);
					    unset($_POST['order_qty_pack']);
					    unset($_POST['free_quantity']);
					    unset($_POST['item_number']);
					    $data = $this->order_model->insert_admin_order_item_data($_POST);
				    }
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
					$vndr_bill['purchase_po_no'] = $_POST['purchase_po_no'];
					$vndr_bill['po_date'] = $po_date;
					$vndr_bill['vendor_name'] = $vendor_name;
					
					$vndr_bill['order_number'] = $order_number;
					$vndr_bill['upload_date'] = date("Y-m-d H:i:s");
					$vndr_bill['invoice_no'] = $_POST['invoice_no'];
					$vndr_bill['brand_name'] = $_POST['brand_name'];
					$vndr_bill['mrp'] = $_POST['mrp'];
					$vndr_bill['hsn'] = $_POST['hsn'];
					$vndr_bill['category'] = $_POST['category'];
					$vndr_bill['date_of_purchase'] = $_POST['date_of_purchase'];
					$vndr_bill['batch_number'] = $batch_number;
					$vndr_bill['centre_location'] = $centre_location;
					$vndr_bill['received_by'] = $received_by;
					$vndr_bill['date_of_receiving'] = $date_of_receiving;
					$vndr_bill['item_name'] = $_POST['item_name'];
					$vndr_bill['company'] = $_POST['company'];
					$vndr_bill['quantity'] = $_POST['quantity'];
					$vndr_bill['expiry'] = $_POST['expiry'];
					$vndr_bill['vendor_price'] = $_POST['vendor_price'];
					$vndr_bill['gstrate'] = $_POST['gstrate'];
					$vndr_bill['discount_amt'] = $_POST['discount_amt'];
					$vndr_bill['free_quantity'] = $_POST['free_quantity'];
					$vndr_bill['total_purchase_value_excl_gst'] = $total_purchase_value_excl_gst;
					$vndr_bill['freight_forwarding_charges'] = $freight_forwarding_charges;
					$vndr_bill['comment'] = $comment;
					 
					// print_r($this->order_model->insert_vendor_billing($vndr_bill));die();
					$data = $this->order_model->insert_vendor_billing($vndr_bill);
					$result = $this->order_model->update_admin_order_status($order_id, $action_type);
					header("location:" .base_url(). "orders/my_orders?m=".base64_encode('Item stock updated successfully!').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "orders/my_orders?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data['item_number'] = $item_id;
			$data['data'] = $this->order_model->get_admin_item_data($item_id);
			$data['purchase_data'] = $this->order_model->get_purchase_item_data($order_id);
			$data['categories'] = $this->stock_model->get_categories();
			$data['brands'] = $this->stock_model->get_brands();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/update_admin_order_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function update_internal_order_item($item_id)
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$order_id = $_GET['i'];
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_internal_item'){
				unset($_POST['action']);
				$action_type = $_POST['action_type']; unset($_POST['action_type']);
				$order_number = $_POST['order_number']; unset($_POST['order_number']);
				$batch_number = $_POST['batch_number'];
				$product_id = $_POST['product_id'];
				$brand_name = $_POST['brand_name'];
				$vendor_number = $_POST['vendor_number'];
				$vendor_price = $_POST['vendor_price'];
				$order_qty = $_POST['quantity'];
				$invoice_no = $_POST['invoice_no'];
				$po_date = $_POST['po_date']; unset($_POST['po_date']);
				$vendor_name = $_POST['vendor_name']; unset($_POST['vendor_name']);
				$freight_forwarding_charges = $_POST['freight_forwarding_charges']; unset($_POST['freight_forwarding_charges']);
				$total_purchase_value_excl_gst = $_POST['total_purchase_value_excl_gst']; unset($_POST['total_purchase_value_excl_gst']);
				$centre_location = $_POST['centre_location']; unset($_POST['centre_location']);
				$date_of_receiving = $_POST['date_of_receiving']; unset($_POST['date_of_receiving']);
				$received_by = $_POST['received_by']; unset($_POST['received_by']);
				$discount_amt = $_POST['discount_amt']; unset($_POST['discount_amt']);
				$comment = $_POST['comment']; unset($_POST['comment']);
				$purchase_po_no = $_POST['purchase_po_no']; unset($_POST['purchase_po_no']);
				$date_of_purchase = $_POST['date_of_purchase'];
				if($action_type == "insert"){// var_dump($_POST);die;
					$_POST['add_date'] = date("Y-m-d H:i:s");
					$_POST['status'] = 1;
					$_POST['quantity'] = ($_POST['lots']*$_POST['units']);
					$delivery_date = $_POST['delivery_date'];unset($_POST['delivery_date']);
					$center_order = $this->order_model->get_center_order_number($item_id);
					//var_dump($center_order);die;
					$new_item_number = $_POST['item_number'];
					//$data = $this->order_model->insert_admin_order_item_data($_POST);
					if($data > 0){
						// center replaced				
						$order = array();
						$order['item_number'] = $new_item_number;
						$order['order_quantity'] = $center_order['order_quantity'];
						$order['batch_number'] = $center_order['batch_number'];
						$order['invoice_no'] = $center_order['invoice_no'];
						$order['comment'] = $center_order['comment'];
						$order['create_date'] = date("Y-m-d H:i:s");
						$order['update_date'] = date("Y-m-d H:i:s");
						$order['delivery_date'] = $delivery_date;
						
						$order['center_number'] = $center_order['center_number'];
						$order['employee_number'] = $center_order['employee_number'];
						
						$order['status'] = 0;
						$order['d_status'] = 1;
						$order['cancelled'] = 0;
						$order['replaced'] = 0;
						// centeral replaced
						$centeral_order = array();
						$centeral_order['order_number'] = $order_number;
						$centeral_order['item_number'] = $new_item_number;
						$centeral_order['item_name'] = $_POST['item_name'];
						$centeral_order['company'] = $_POST['company'];
						$centeral_order['brand_name'] = $_POST['brand_name'];
						$centeral_order['vendor_number'] = $_POST['vendor_number'];
						$centeral_order['batch_number'] = $_POST['batch_number'];
						$centeral_order['invoice_no'] = $_POST['invoice_no'];
						$centeral_order['comment'] = $_POST['comment'];
						$centeral_order['price'] = $_POST['price'];
						$centeral_order['quantity'] = $_POST['quantity'];
						$centeral_order['create_date'] = date("Y-m-d H:i:s");
						$centeral_order['update_date'] = date("Y-m-d H:i:s");
						$centeral_order['status'] = 1;
						$centeral_order['received'] = 1;
						$centeral_order['purchase_order'] = 1;
						$centeral_order['cancelled'] = 0;
						$centeral_order['replaced'] = 0;

						$data = $this->order_model->insert_internal_replaced_order_item($centeral_order, $order, $item_id, $center_order);
					}else{
						header("location:" .base_url(). "orders/my_internal_orders?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
						die();
					}
				}else{
					//var_dump($_POST);die;
					$check_center_new_item = $this->order_model->check_centeral_item($product_id, $brand_name, $vendor_number, $batch_number, $vendor_price);
				    //var_dump($check_center_new_item);die;
				    if($check_center_new_item > 0){
					    $data = $this->order_model->update_admin_order_item_data($_POST);
					}else{
					    unset($_POST['brand']);
					    unset($_POST['order_qty_pack']);
					    unset($_POST['free_quantity']);
					    unset($_POST['item_number']);
					    $data = $this->order_model->insert_admin_order_item_data($_POST);
				    }
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
					$vndr_bill['purchase_po_no'] = $_POST['purchase_po_no'];
					$vndr_bill['po_date'] = $po_date;
					$vndr_bill['vendor_name'] = $vendor_name;
					
					$vndr_bill['order_number'] = $order_number;
					$vndr_bill['upload_date'] = date("Y-m-d H:i:s");
					$vndr_bill['invoice_no'] = $_POST['invoice_no'];
					$vndr_bill['brand_name'] = $_POST['brand_name'];
					$vndr_bill['mrp'] = $_POST['mrp'];
					$vndr_bill['hsn'] = $_POST['hsn'];
					$vndr_bill['category'] = $_POST['category'];
					$vndr_bill['date_of_purchase'] = $_POST['date_of_purchase'];
					$vndr_bill['batch_number'] = $batch_number;
					$vndr_bill['centre_location'] = $centre_location;
					$vndr_bill['received_by'] = $received_by;
					$vndr_bill['date_of_receiving'] = $date_of_receiving;
					$vndr_bill['item_name'] = $_POST['item_name'];
					$vndr_bill['company'] = $_POST['company'];
					$vndr_bill['quantity'] = $_POST['quantity'];
					$vndr_bill['expiry'] = $_POST['expiry'];
					$vndr_bill['vendor_price'] = $_POST['vendor_price'];
					$vndr_bill['gstrate'] = $_POST['gstrate'];
					$vndr_bill['discount_amt'] = $_POST['discount_amt'];
					$vndr_bill['free_quantity'] = $_POST['free_quantity'];
					$vndr_bill['total_purchase_value_excl_gst'] = $total_purchase_value_excl_gst;
					$vndr_bill['freight_forwarding_charges'] = $freight_forwarding_charges;
					$vndr_bill['comment'] = $comment;
					 
					// print_r($this->order_model->insert_vendor_billing($vndr_bill));die();
					$data = $this->order_model->insert_vendor_billing($vndr_bill);
					$result = $this->order_model->update_internal_admin_order_status($order_id, $action_type);
					header("location:" .base_url(). "orders/my_internal_orders?m=".base64_encode('Item stock updated successfully!').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "orders/my_internal_orders?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
					die();
				}				
			}
			$data['item_number'] = $item_id;
			$data['data'] = $this->order_model->get_admin_item_data($item_id);
			$data['purchase_data'] = $this->order_model->get_purchase_internal_item_data($order_id);
			$data['categories'] = $this->stock_model->get_categories();
			$data['brands'] = $this->stock_model->get_brands();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/update_internal_order_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	/*** Center Orders *****/
	
	public function center_order(){
		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			//echo '<pre>';
			if($_SESSION['logged_stock_manager']['center']){
			$data['data'] = $this->order_model->get_center_order($_SESSION['logged_stock_manager']['center']);
		}else{
			$data['data'] = $this->order_model->get_center_order($_SESSION['logged_billing_manager']['center']);
			}
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/center_order', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function update_order_item($item_id)
	{
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_order_item'){
			if($_SESSION['logged_stock_manager']['center']){	
				$center = $_SESSION['logged_stock_manager']['center'];
				$employee_number = $_SESSION['logged_stock_manager']['employee_number'];
			}else{
				$center = $_SESSION['logged_billing_manager']['center'];
				$employee_number = $_SESSION['logged_billing_manager']['employee_number'];
			}
				$check_center_item = $this->order_model->check_center_item($center, $item_id);
				if(empty($check_center_item)){
					$admin_data = $this->order_model->get_admin_item_data($item_id);
					$center_data = $this->order_model->get_center_item_order($center, $item_id);

					unset($admin_data['ID']);
					unset($admin_data['vendor_price']);
					unset($admin_data['expiry']);
					unset($admin_data['expiry_day']);
					$admin_data['center_number'] = $center;
					$admin_data['expiry'] = $_POST['expiry'];
					$admin_data['expiry_day'] = $_POST['expiry_day'];
					$admin_data['batch_number'] = $_POST['batch_number'];
					/*var_dump($admin_data);  echo "<br/><br/>--------------------<br/><br/>";
					var_dump($center_data);  echo "<br/><br/>--------------------<br/><br/>";
					var_dump($_POST);  echo "<br/><br/>--------------------<br/><br/>";
					die;*/
					$update_center_stock = $this->order_model->insert_center_order_item($admin_data);
				}else{
					$stock_arr = array();
					$stock_arr['company'] = $_POST['company'];
					$stock_arr['item_name'] = $_POST['item_name'];
					$stock_arr['brand_name'] = $_POST['brand_name'];
					$stock_arr['vendor_number'] = $_POST['vendor_number'];
					$stock_arr['expiry'] = $_POST['expiry'];
					$stock_arr['expiry_day'] = $_POST['expiry_day'];
					$stock_arr['price'] = $_POST['price'];
					$stock_arr['order_qty'] = $_POST['quantity'];
					$admin_data['batch_number'] = $_POST['batch_number'];
					$update_center_stock = $this->order_model->update_order_item_data($stock_arr, $ID, $employee_number);
				}
				
				if($update_center_stock > 0){
					$order_id = $_GET['i'];
					$result = $this->order_model->update_order_status($order_id);
					header("location:" .base_url(). "orders/center_order?m=".base64_encode('Item stock updated successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "orders/center_order?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}else{
				$data['data'] = $this->order_model->add_item_data($item_id);
				$template = get_header_template($logg['role']);
				$this->load->view($template['header']);
				$this->load->view('orders/update_center_order_item', $data);
				$this->load->view($template['footer']);
			}
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	/*** Center Orders *****/
		
	public function inventory_dispense()
	{
		$logg = checklogin();
		if($logg['status'] == true)
		{
			$data = array();
			$data['alldata'] = $this->stock_model->inventory_dispense_data();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('inventory/inventory_dispense', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die;
		}
	}
	
	public function ajax_inventory_dispense()
	{
		if($_POST['type'] == 'month_wise'){
			$month = $_POST['month'];
			$data = $this->order_model->ajax_inventory_month_dispense($month);
		}
		if($_POST['type'] == 'custom_wise'){
			$start = $_POST['start'];
			$end = $_POST['end'];
			$data = $this->order_model->ajax_inventory_custom_dispense($start, $end);
		}
		echo json_encode($data);
		die;
	}
	
	public function get_all_procedure_detail($receipt_number)
	{
		$logg = checklogin();
		if($logg['status'] == true)
		{
			$data = array();
			$data['data'] = $this->order_model->get_procedure_by_receipt($receipt_number);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('inventory/procedure_dispence_detail', $data);
			$this->load->view($template['footer']);
		}
		else
		{
			header("loacation:" . base_url(). "");
		}
	}
	
	public function get_all_investigation_detail($receipt_number)
	{
		$logg = checklogin();
		if($logg['status'] == true)
		{
			$data = array();
			$data['data'] = $this->order_model->get_investigation_by_reciept($receipt_number);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('inventory/investigation_dispence_detail', $data);
			$this->load->view($template['footer']); 
		}
		else
		{
			header("location:", base_url(). "");
		}
	}
	
	public function purchase_order($item_number){
		$logg = checklogin();
		if($logg['status'] == true)
		{
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'add_purchase_orders'){
				unset($_POST['action']);
				$_POST['order_number'] = getGUID();
				$_POST['create_date'] = date("Y-m-d H:i:s");
				$_POST['update_date'] = date("Y-m-d H:i:s");
				$_POST['status'] = 0;
				$item_number = $_POST['item_number'];
				$data = $this->order_model->add_purchase_order($_POST);
				if($data > 0){
					header("location:" .base_url(). "orders/my_orders?m=".base64_encode('Purchase order added successfully !').'&t='.base64_encode('success'));
					die();
				}else{
					header("location:" .base_url(). "orders/my_orders/".$item_number."?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
					die();
				}
			}
			$data = array();
			$data['data'] = $this->stock_model->item_details($item_number, 'admin');
			$data['brands'] = $this->stock_model->get_brands();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/purchase_order', $data);
			$this->load->view($template['footer']); 
		}
		else
		{
			header("location:", base_url(). "");
		}
	}
	
	public function purchase_orders_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$purchase_order = $this->input->get('purchase_order', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$item_name = $this->input->get('item_name', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "orders/purchase_orders_list";
        	$config["total_rows"] = $this->order_model->get_my_purchase_orders_data($purchase_order, $start_date, $end_date, $item_name);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['orders_result'] = $this->order_model->get_my_orders_count($config["per_page"], $per_page, $purchase_order, $start_date, $end_date, $item_name);
			
			$data["purchase_order"] = $purchase_order;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["item_name"] = $item_name;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/purchase_orders_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	public function purchase_internal_orders_list(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$purchase_order = $this->input->get('purchase_order', true);
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$item_name = $this->input->get('item_name', true);
			
			$config = array();
        	$config["base_url"] = base_url() . "orders/purchase_internal_orders_list";
        	$config["total_rows"] = $this->order_model->get_my_purchase_internal_orders_data($purchase_order, $start_date, $end_date, $item_name);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['orders_result'] = $this->order_model->get_my_internal_orders_count($config["per_page"], $per_page, $purchase_order, $start_date, $end_date, $item_name);
			
			$data["purchase_order"] = $purchase_order;
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["item_name"] = $item_name;
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/purchase_internal_orders_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}

	public function purchase_item(){
		$logg = checklogin();
		error_reporting(0);
		if($logg['status'] == true){

			$per_page = $this->input->get('per_page', true);
			if(empty($per_page)){
				$per_page = 0;
			}
			$start_date = $this->input->get('start_date', true);
			$end_date = $this->input->get('end_date', true);
			$item_name = $this->input->get('item_name', true);
			$batch_number = $this->input->get('batch_number', true);
			$invoice_no = $this->input->get('invoice_no', true);
			$purchase_po_no = $this->input->get('purchase_po_no', true);
			$vendor_name = $this->input->get('vendor_name', true);
			$brand_name = $this->input->get('brand_name', true);

			$export_billing = $this->input->get('export-billing', true);
			if (isset($export_billing)){
				$data = $this->order_model->export_stocks_reports($employee_number, $center_number, $patient_id, $start_date, $end_date, $item_name, $batch_number,$type);
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=Purchase-Item-'.$enddate.'.csv');
				$fp = fopen('php://output','w');
				$headers = 'Purchase Po No, Po Date, Order Number, Vendor Billing, Upload Date, Invoice No, Date Of Purchase, Vendor Name, Item Name, Pack Size, Brand Name, Order Qty, Expiry,Vendor Price, Gst Rate, HSN Code,Batch Number,MRP, Rate Per Unit,Total Purchase Value Excl GST, Freight Forwarding Charges, Discount Rate,Discount Amt,Free Quantity,Total Purchase After Discount Exculding Gst, Total Purchase Value Incl Gst,Centre Location,Date Of Receiving,Received By';
				//Add the headers
				fwrite($fp, $headers. "\r\n");
				foreach ($data as $key => $val) {//var_dump($val);die;
				   // $gst_amount = $gstdivision / $val['vendor_price'];
					$sql2 = "SELECT * FROM hms_orders WHERE order_number='" . $val['order_number'] . "'";
					$select_result2 = run_select_query($sql2);
					
					$sql = "SELECT * FROM hms_orders WHERE item_number='" . $select_result2['item_number'] . "'";
					$select_result = run_select_query($sql);
					
					$sql3 = "SELECT * FROM hms_stocks WHERE item_number='" . $select_result['item_number'] . "'";
					$select_result3 = run_select_query($sql3);
					$pack_size = $select_result3['pack_size'];
					
					$lead_arr = array($val['purchase_po_no'], $val['po_date'], $val['order_number'], $val['vendor_billing'], $val['upload_date'], $val['invoice_no'], $val['date_of_purchase'], $val['vendor_name'], $val['item_name'], $pack_size, $val['brand_name'], $val['quantity'], $val['expiry'],$val['vendor_price'], $val['gstrate'], $val['hsn'], $val['batch_number'], $val['mrp'],$val['rate_per_unit'],$val['total_purchase_value_excl_gst'], $val['freight_forwarding_charges'],$val['discount_rate'],$val['discount_amt'],$val['free_quantity'],$val['total_purchase_after_discount_exculding_gst'], $val['total_purchase_value_incl_gst'], $val['centre_location'],$val['date_of_receiving'], $val['received_by']);
					fputcsv($fp, $lead_arr);
				}
				fclose($fp);
				exit();
			}

			$config = array();
        	$config["base_url"] = base_url() . "orders/purchase_item";
        	$config["total_rows"] = $this->order_model->get_purchase_orders_data($start_date, $end_date, $item_name, $batch_number, $invoice_no, $purchase_po_no,$vendor_name,$brand_name);
        	$config["per_page"] = 20;
        	$config["uri_segment"] = 2;
			$config['use_page_numbers'] = true;
			$config['num_links'] = 5;
			$config['page_query_string'] = true;
			$config['reuse_query_string'] = true;
        	$this->pagination->initialize($config);
        	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
			
        	$data["links"] = $this->pagination->create_links();
			$data['orders_result'] = $this->order_model->get_purchase_orders_count($config["per_page"], $per_page, $start_date, $end_date, $item_name, $batch_number, $invoice_no, $purchase_po_no,$vendor_name,$brand_name);
			
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			$data["item_name"] = $item_name;
			$data["batch_number"] = $batch_number;
			$data["invoice_no"] = $invoice_no;
			$data["purchase_po_no"] = $purchase_po_no;
			$data["vendor_name"] = $vendor_name;
			$data["brand_name"] = $brand_name;
			$data['brands'] = $this->stock_model->get_brands();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/purchase_item', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
}
	
	/*public function purchase_orders_list(){
		$logg = checklogin();
		if($logg['status'] == true){
			$data = array();
			$data['list'] = $this->order_model->get_my_orders_data();
			$data['vendors'] = $this->vendors_model->get_vendors();
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/purchase_orders_list', $data);
			$this->load->view($template['footer']); 
		}else{
			header("location:", base_url(). "");
		}
	}*/
	
	function approve_purchase_order($order_number){
		$approved = $this->order_model->approve_purchase_order($order_number);
		if($approved > 0){
			header("location:" .base_url(). "orders/purchase_orders_list?m=".base64_encode('Purchase order approved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "orders/purchase_orders_list?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function disapprove_purchase_order($order_number){
		$approved = $this->order_model->disapprove_purchase_order($order_number);
		if($approved > 0){
			header("location:" .base_url(). "orders/purchase_orders_list?m=".base64_encode('Purchase order approved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "orders/purchase_orders_list?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function approve_internal_purchase_order($order_number){
		$approved = $this->order_model->approve_internal_purchase_order($order_number);
		if($approved > 0){
			header("location:" .base_url(). "orders/purchase_internal_orders_list?m=".base64_encode('Purchase order approved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "orders/purchase_internal_orders_list?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function disapprove_internal_purchase_order($order_number){
		$approved = $this->order_model->disapprove_internal_purchase_order($order_number);
		if($approved > 0){
			header("location:" .base_url(). "orders/purchase_internal_orders_list?m=".base64_encode('Purchase order approved!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "orders/purchase_internal_orders_list?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function order_internal_place_date($order_number){
		$approved = $this->order_model->order_internal_place_date($order_number);
		if($approved > 0){
			header("location:" .base_url(). "orders/my_internal_orders?m=".base64_encode('Order Placed!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "orders/my_internal_orders?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	function order_place_date($order_number){
		$approved = $this->order_model->order_place_date($order_number);
		if($approved > 0){
			header("location:" .base_url(). "orders/my_orders?m=".base64_encode('Order Placed!').'&t='.base64_encode('success'));
			die();
		}else{
			header("location:" .base_url(). "orders/my_orders?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error'));
			die();
		}
	}
	
	public function my_order_details($order_number){
		$logg = checklogin();
		if($logg['status'] == true)
		{
			$data = array();
			$data['data'] = $this->order_model->my_order_details($order_number);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/my_order_details', $data);
			$this->load->view($template['footer']); 
		}
		else
		{
			header("location:", base_url(). "");
		}
	}
	
	public function get_item_delivery_date($item){
		$data = $this->order_model->get_item_delivery_date($item);
		return $data;
	}
	
	public function get_billing_data($patient){
		$data = $this->order_model->get_billing_data($patient);
		return $data;
	}
	
		function get_investigation_name($investig){
		$name = $this->order_model->get_investigation_name($investig);
		return $name;
	}
	
	function get_procedure_name($procedure){
		$name = $this->order_model->get_procedure_name($procedure);
		return $name;
	}
	
	function get_item_name($item){
		$name = $this->order_model->get_item_name($item);
		return $name;
	}
	
	function get_a_item_name($item){
		$name = $this->order_model->get_a_item_name($item);
		return $name;
	}
		
	function get_center_name($center){
		$name = $this->order_model->get_center_name($center);
		return $name;
	}
	
	function csm_stock_status($item,$order_units){
		$stock = $this->order_model->csm_stock_status($item,$order_units);
		return $stock;
	}
	function csm_purchase_status($item){
		$stock = $this->order_model->csm_purchase_status($item);
		return $stock;
	}
	
	function get_vendor_name($vendor){
		$vendor_name = $this->order_model->get_vendor_name($vendor);
		return $vendor_name;
	}
	function get_brand_name($brand){
		$brand_name = $this->order_model->get_brand_name($brand);
		return $brand_name;
	}
	
	function get_product_id($product){
		$product_name = $this->order_model->get_product_id($product);
		return $product_name;
	}
	function get_generic_name($product){
		$product_generic_name = $this->order_model->get_generic_name($product);
		return $product_generic_name;
	}
	function get_gstdivision($product){
		$product_gstdivision = $this->order_model->get_gstdivision($product);
		return $product_gstdivision;
	}
	function vendor_orders(){
		$vendor_number = $_POST['vendor_number'];
		$orders = $this->order_model->vendor_orders($vendor_number);
		$html = '';
		if(!empty($orders)){
			foreach($orders as $key => $vl){
				$html .= '<tr class="odd gradeX"><td>';
						 	if($vl['replaced'] == '0'){
							$html .= '<a href="'.base_url().'orders/my_order_details/'.$vl['order_number'].'">'.$vl['order_number'].'</a>';
							}else {$html .= $vl['order_number']; }
							$html .= '</td><td><a href="'.base_url().'stocks/details/'.$vl['item_number'].'">'.$vl['item_number'].'</a>'; 
							if($vl['replaced'] == '1'){$html .= '(Replaced)'; }
							$html .= '</td><td>';
							if(!empty($this->get_a_item_name($vl['item_number']))){$html .= $this->get_a_item_name($vl['item_number']);}
							else{$html .= '';}
							$html .= '</td><td>'.$vl['order_quantity'].'</td><td>'.$vl['create_date'].'</td><td>';
							if($vl['purchase_order'] == 0){
								$html .= '<a href="'.base_url('orders/approve_purchase_order/'.$vl['order_number']).'" class="btn btn-large">Approve</a>';
							}else{$html .= 'Approved';}
							$html .= '</td></tr>';
			}
			
			echo json_encode($html);
			die;
		}else{
			echo json_encode($html);
			die;
		}
	}
	
	function get_vendor_billing($order_number){
		$vendor_billing = $this->order_model->get_vendor_billing($order_number);
		return $vendor_billing;
	}
	
	function generate_itemnumber(){
		echo json_encode(getGUID());
		die;
	}
	
	function get_employee_name($employee){
		$name = $this->order_model->get_employee_name($employee);
		return $name;	
	}
	
	
	public function edit_purchase_order($po_number_encoded){
		$segments = $this->uri->segment_array();
		$po_number = implode('/', array_slice($segments, 2));

		$logg = checklogin();
		if($logg['status'] == true){		
			$data = array();
			if(isset($_POST['order_quantity'])) { $order_quantity = $_POST['order_quantity']; }
			if(isset($_POST['action']) && isset($_POST['action']) && $_POST['action'] == 'update_item_order'){
				unset($_POST['action']);
				$order_quantity = $_POST['order_quantity'];
				$order_number = $_POST['order_number'];
				$company = $_POST['company'];
				$vendor_number = $_POST['vendor_number'];
				$brand_name = $_POST['brand_name'];
				$mrp = $_POST['mrp'];
				$hsn = $_POST['hsn'];
				$pack_size = $_POST['pack_size'];
				$gstrate = $_POST['gstrate'];
				$vendor_price = $_POST['vendor_price'];
				$ship_to = $_POST['ship_to'];
				$bill_to = $_POST['bill_to'];
				$purchase_order = $_POST['purchase_order'];
				$order_qty_pack = $_POST['order_qty_pack'];
				$total_vendor_price = $_POST['total_vendor_price'];
				$po_number = $_POST['po_number'];
				//var_dump($order_number);die;
				$data = $this->order_model->update_purchase_item($po_number,	$order_number, $order_quantity, $company, $vendor_number, $brand_name, $mrp, $hsn, $pack_size, $gstrate ,$vendor_price, $ship_to, $bill_to ,$purchase_order, $order_qty_pack, $total_vendor_price);
				if($data > 0){
					header("location:" .base_url(). "orders/edit_purchase_order/$order_number?m=".base64_encode('Item updated successfully !').'&t='.base64_encode('success').'&ID='.$ID);
					die();
				}else{
					header("location:" .base_url(). "accounts/update_mou?m=".base64_encode('Something went wrong !').'&t='.base64_encode('error').'&ID='.$ID);
					die();
				}				
			}
			$data['order_number'] = $order_number;
			$data['data'] = $this->order_model->get_orders_by_po($po_number);
			$data['brands'] = $this->stock_model->get_brands();
			$data['vendors'] = $this->vendors_model->get_vendor_data_by_vendor_number($data['data']['vendor_number']);
			$template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('orders/edit_purchase_order', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
	}
	
	function get_all_centers(){
		$all_centers = $this->stock_model->get_centers();
		return $all_centers;
	}
	
	// public function edit_purchase_order($po_number){

	// 	$logg = checklogin();
	// 	if($logg['status'] == true){
	// 		$data = array();
			
	// 		if(isset($_POST['action']) && $_POST['action'] == 'update_item_order'){
	// 			unset($_POST['action']);
				
	// 			$icounte = $mcounte = $ccounte = $spcounte = 1;
	// 			$i_counte = $m_counte = $c_counte = $s_pcounte = array();
	// 			$i_counter = $m_counter = $c_counter = $s_pcounter = array();
				
	// 			foreach($_POST as $key => $val){
	// 				$pos_c = strpos($key, 'consumables_name_');
	// 				if ($pos_c === false) {} else {
	// 					$cid = $int = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
	// 					$c_counter[] = $cid;
	// 				}	
	// 			}

	// 			if(!empty($c_counter)){
	// 				// First delete existing orders for this PO
	// 				$delete_query = "DELETE FROM hms_orders WHERE po_number = '$po_number'";
	// 				run_form_query($delete_query);
					
	// 				// Delete from ponumber table
	// 				$delete_po_query = "DELETE FROM hms_ponumber WHERE po_number = '$po_number'";
	// 				run_form_query($delete_po_query);
					
	// 				foreach($c_counter as $key => $ccounte){
	// 					if($_POST['consumables_name_'.$ccounte] != ''){
	// 						// insert query
	// 						$order_number = getGUID();
	// 						$po_number = $_POST['po_number'];
	// 						$item_number = $_POST['consumables_serial_'.$ccounte];
	// 						$company = $_POST['consumables_company_'.$ccounte];
	// 						$item_name = $_POST['consumables_item_name_'.$ccounte];
	// 						$batch_number = $_POST['consumables_batch_number_'.$ccounte];
	// 						$vendor_price = $_POST['consumables_vendor_price_'.$ccounte];
	// 						$mrp = $_POST['consumables_mrp_'.$ccounte];
	// 						$hsn = $_POST['consumables_hsn_'.$ccounte];
	// 						$pack_size = $_POST['consumables_pack_size_'.$ccounte];
	// 						$gstrate = $_POST['consumables_gstrate_'.$ccounte];
	// 						$gstdivision = $_POST['consumables_gstdivision_'.$ccounte];
	// 						$total_vendor_price = $_POST['consumables_price_'.$ccounte];
	// 						$order_qty_pack = $_POST['consumables_quantity_'.$ccounte];
	// 						$brand_name = $_POST['consumables_brand_name_'.$ccounte];
	// 						$vendor_number = $_POST['vendor_number'];
	// 						$ship_to = $_POST['ship_to'];
	// 						$bill_to = $_POST['bill_to'];
	// 						$center = $_POST['center'];
	// 						$order_quantity = $pack_size * $order_qty_pack;
							
	// 					    $query = "INSERT INTO `hms_orders` (order_number, po_number, item_number, company, item_name, batch_number, vendor_price, mrp, hsn, gstrate, gstdivision, pack_size, total_vendor_price, order_quantity, order_qty_pack, vendor_number, ship_to, bill_to, brand_name, center, create_date) values ('$order_number','$po_number','$item_number','$company','$item_name','$batch_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','$pack_size','$total_vendor_price','$order_quantity','$order_qty_pack','$vendor_number','$ship_to','$bill_to','$brand_name','$center','".date("Y-m-d H-i-s")."')";
    //                         $result = run_form_query($query); 
    //                         $query2 = "INSERT INTO hms_ponumber (order_number, po_number, created) VALUES ('$order_number','$po_number','".date("Y-m-d")."')";
    //                         $result = run_form_query($query2);
	// 					}
	// 				}
	// 			}
								
	// 			if($result > 0){
	// 				header("location:" .base_url(). "stocks/order_items/?po_number=".$po_number."&t=medicine&m=".base64_encode('Order updated successfully!').'&t='.base64_encode('success'));
	// 				die();
	// 			}else{
	// 				var_dump($result,'3'); die;
	// 				header("location:" .base_url(). "orders/edit_purchase_order/".$po_number."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
	// 				die();
	// 			}
	// 		}
			
	// 		// Get existing orders for this PO
	// 		$data['existing_orders'] = $this->order_model->get_orders_by_po($po_number);
	// 		$data['po_number'] = $po_number;
	// 		$data['consumables'] = $this->stock_model->get_central_all_stocks();
	// 		$data['vendors'] = $this->vendors_model->get_vendors();
	// 		$data['center'] = isset($data['existing_orders'][0]['center']) ? $data['existing_orders'][0]['center'] : '';
			
	// 		$template = get_header_template($logg['role']);
	// 		$this->load->view($template['header']);
	// 		var_dump($data,'3'); die;
	// 		$this->load->view('orders/edit_purchase_order', $data);
	// 		$this->load->view($template['footer']);
	// 	}else{
	// 		header("location:" .base_url(). "");
	// 		die();
	// 	}
	// }


}