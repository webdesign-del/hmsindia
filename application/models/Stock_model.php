<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');

class Stock_model extends CI_Model
{
	/**Products */

	function get_product_name($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select name from ".$this->config->item('db_prefix')."stock_products where ID='".$item."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['name'];
        }
        else
        {
            return "--";
        }
	}
	function stock_product_add($data){
	    $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "stock_products` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
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
	
	function stock_medicine_add($data){
	    $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "medicines` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
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
	
	
	public function update_stock_medicine_data($data, $ID, $name, $company, $generic_name, $pack_size, $hsn, $gstrate, $gstdivision, $vendor_price, $mrp, $vendor_number, $brand_number)
    {
		$sql3 = "UPDATE hms_medicines SET `name` = '$name', `company` = '$company', `generic_name` = '$generic_name', `pack_size` = '$pack_size', `hsn` = '".$hsn."', `gstrate` = '".$gstrate."', `gstdivision` = '".$gstdivision."', `vendor_price` = ".$vendor_price.", `mrp` = '".$mrp."', `brand_number` = '".$brand_number."', `vendor_number` = '".$vendor_number."' WHERE `ID`='".$ID."'";
		$this->db->query($sql3);
        return $this->db->affected_rows();
    }
	
	function get_stock_medicine_data($ID){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."medicines where ID='".$ID."'";
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
	
	function export_medicine($name, $vendor_number, $brand_number){
		$product_discard = $response = array();
        $conditions = '';
		if (!empty($name)){
			$conditions .= " and name like '%$name%'";
		}
		if (!empty($vendor_number)){
			$conditions .= " and vendor_number='$vendor_number'";
		}
		if (!empty($brand_number)){
			$conditions .= " and brand_number='$brand_number'";
		}
		$product_discard_sql = "Select * from ".$this->config->item('db_prefix')."medicines where 1 $conditions order by rand() desc ";
        $product_discard_q = $this->db->query($product_discard_sql);
        $product_discard = $product_discard_q->result_array();
		if(!empty($product_discard)){
            foreach($product_discard as $key => $val){
				$response[] = array(
                        'name' => $val['name'],
                        'company' => $val['company'],
                        'generic_name' => $val['generic_name'],
						'vendor_number' => $val['vendor_number'],
						'brand_number' => $val['brand_number'],
						'hsn' => $val['hsn'],
						'gstrate' => $val['gstrate'],
						'pack_size' => $val['pack_size'],
                        'vendor_price' => $val['vendor_price'],
						'mrp' => $val['mrp'],
                        'status' => $val['status'],
						'add_date' => $val['add_date']
				);
            }
        } 
    	return $response;
    }
    
    function get_medicine_count($name, $vendor_number, $brand_number){
		$conditions = '';
		if (!empty($name)){
			$conditions .= " and name like '%$name%'";
		}
		if (!empty($vendor_number)){
			$conditions .= " and vendor_number='$vendor_number'";
		}
		if (!empty($brand_number)){
			$conditions .= " and brand_number='$brand_number'";
		}
	    $vendor_return_sql = "Select * from ".$this->config->item('db_prefix')."medicines where 1 ".$conditions."";
		$q = $this->db->query($vendor_return_sql);
		return $q->num_rows();
	}
	
	function get_medicine_patination($limit, $page, $name, $vendor_number, $brand_number){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($name)){
			$conditions .= " and name like '%$name%'";
		}
		if (!empty($vendor_number)){
			$conditions .= " and vendor_number='$vendor_number'";
		}
		if (!empty($brand_number)){
			$conditions .= " and brand_number='$brand_number'";
		}
		$product_discard_sql = "Select * from ".$this->config->item('db_prefix')."medicines where 1".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$product_discard_q = $this->db->query($product_discard_sql);
		$product_discard = $product_discard_q->result_array();
		return $product_discard;
	}
	
	function get_all_stock_product(){
		$result = array();
		$sql_condition = '';
		//$sql = "Select * from ".$this->config->item('db_prefix')."medicines ORDER by ID DESC";
	    $sql = "Select * from ".$this->config->item('db_prefix')."stock_products ORDER by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result;
        }else{
            return $result;
        }
	}

	function get_stock_products(){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."stock_products ORDER by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result;
        }else{
            return $result;
        }
	}
	
	function get_vendor_invoice(){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."vendor_invoice where no_of_item > 0 ORDER by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result;
        }else{
            return $result;
        }
	}
	
	function get_medicines(){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."medicines";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result;
        }else{
            return $result;
        }
	}
	
	function get_medicine_name(){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."medicines where status='1'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result;
        }else{
            return $result;
        }
	}
	
	function update_vendor_invoice($ID, $invoice_no, $qty){
	    $sql = "UPDATE ".$this->config->item('db_prefix')."vendor_invoice SET `no_of_item`= `no_of_item`-$qty where invoice_no='".$invoice_no."'";
        $this->db->query($sql);
	}

	function get_product_brand_vendor($product_id, $brand_number){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."product_vendors where product_id='$product_id' and brand_number='$brand_number' ORDER by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result;
        }else{
            return $result;
        }
	}

	function get_product_vendor_info($product_id, $brand_number, $vendor_number){
		$result = array();
		$sql_condition = '';
		$sql = "Select price, units from ".$this->config->item('db_prefix')."product_vendors where product_id='$product_id' and brand_number='$brand_number' and vendor_number='$vendor_number' ORDER by ID DESC limit 1";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result)){
            return $result[0];
        }else{
            return $result;
        }
	}
		
	function center_stock_export($center){ 
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$center."' ORDER by ID DESC";
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
		//var_dump($data);die;
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "stocks` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'";
		}
		$date = date("Y-m-d H:i:s");
		$sqlArr[] = " add_date = '".addslashes($date)."'";
		$sqlArr[] = " item_number = '".addslashes(getGUID())."'";	
		
		$sql .= implode(',' , $sqlArr);		
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}
	
	function get_item_lists(){
		$result = array();
		$options = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."stocks where status='1'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
			foreach($result as $key => $val){
				$options .= '<option value="'.$val['item_number'].'">'.$val['item_name'].' ('.$val['expiry'].')-('.$val['batch_number'].')</option>';
			}
            return $options;
        }
        else
        {
            return $options;
        }
	}
	
	function get_item_details($item){
		$result = $response = array();
		$sql = "Select * from ".$this->config->item('db_prefix')."stocks where item_number='".$item."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if (!empty($result))
        {
			$stock_data = $result[0];
			$cat_name = self::get_category_name($result[0]['category']);
			$brand_name = self::get_brand_name($result[0]['brand_name']);
			$brand_number = $result[0]['brand_name'];
			$product_id = $result[0]['product_id'];
			$vendor_number = $result[0]['vendor_number'];
			$product_vendor_info = $this->get_product_vendor_info($product_id, $brand_number, $vendor_number);
			
			$response = array('stock_data' => $stock_data, 'cat_name' => $cat_name, 'brand_name' => $brand_name, "product_vendor_info" =>$product_vendor_info);
            return $response;
        }
        else
        {
			$response = array('stock_data' => "", 'cat_name' => "", 'brand_name' => "", "product_vendor_info" => "");
            return $response;
        }
	}
	
	function item_details($item, $item_of){
		$result = $response = array();
		$condition = "";
		if($item_of == 'center'){
			$condition = "center_stocks";
		}else if($item_of == 'admin'){
			$condition = "stocks";
		}else{
			$condition = "center_stocks";
		}
		$sql = "Select * from ".$this->config->item('db_prefix').$condition." where item_number='".$item."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if (!empty($result))
        {
            return $result[0];
        }
        else
        {
            return $response;
        }
	}

	function stock_transfer_details($ID){
		$result = $response = array();
		$condition = "";
		//var_dump($sql);die();
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where ID='".$ID."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
		if (!empty($result))
        {
            return $result[0];
        }
        else
        {
            return $response;
        }
	}
	
	/*function add_center_item($data){
	 $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "center_stocks` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
		}		
		$sql .= implode(',' , $sqlArr);		
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}*/
	
	public function add_center_item($data, $invoice_no, $item_number, $product_id, $company, $item_name, $batch_number, $hsn, $gstrate, $pack_size, $gstdivision, $brand_name, $vendor_number, $generic_name, $item_qty, $safety_stock, $order_qty, $category, $price, $expiry, $expiry_day, $add_date, $center_number, $status, $employee_number, $department, $vendor_price, $mrp, $date_of_purchase)
    {
		//var_dump($sql);die;
		 $sql = "INSERT INTO `".$this->config->item('db_prefix')."center_stocks` (`invoice_no`,`item_number`,`product_id`,`company`,`item_name`,`batch_number`,`hsn`,`gstrate`,`pack_size`,`gstdivision`,`brand_name`,`vendor_number`,`generic_name`,`quantity`, `safety_stock`, `order_qty`, `category`, `price`, `expiry`, `expiry_day`, `add_date`, `center_number`, `status`, `employee_number`,`department`, `vendor_price`, `mrp`, `date_of_purchase`) VALUES ('$invoice_no','$item_number','$product_id','$company','$item_name','$batch_number','$hsn','$gstrate','$pack_size','$gstdivision','$brand_name','$vendor_number','$generic_name','$item_qty','$safety_stock','$order_qty','$category','$price','$expiry','$expiry_day','".date("Y-m-d H:i:s")."','$center_number','1','$employee_number','$department','$vendor_price','$mrp','$date_of_purchase')";

		$res =  $this->db->query($sql);						
        
		return 1;
	}

	public function add_center_new_item($data, $invoice_no, $item_number, $product_id, $company, $item_name, $batch_number, $hsn, $gstrate, $pack_size, $gstdivision, $brand_name, $vendor_number, $generic_name, $item_qty, $safety_stock, $order_qty, $category, $price, $expiry, $expiry_day, $add_date, $center_number, $status, $employee_number,$department, $vendor_price, $mrp, $date_of_purchase)
    {
		//var_dump($sql);die;
		 $sql = "INSERT INTO `".$this->config->item('db_prefix')."center_stocks` (`invoice_no`,`item_number`,`product_id`,`company`,`item_name`,`batch_number`,`hsn`,`gstrate`,`pack_size`,`gstdivision`,`brand_name`,`vendor_number`,`generic_name`,`quantity`, `safety_stock`, `order_qty`, `category`, `price`, `expiry`, `expiry_day`, `add_date`, `center_number`, `status`, `employee_number`,`department`, `vendor_price`, `mrp`, `date_of_purchase`) VALUES ('$invoice_no','$item_number','$product_id','$company','$item_name','$batch_number','$hsn','$gstrate','$pack_size','$gstdivision','$brand_name','$vendor_number','$generic_name','$item_qty','$safety_stock','$order_qty','$category','$price','$expiry','$expiry_day','".date("Y-m-d H:i:s")."','$center_number','1','$employee_number','$department','$vendor_price','$mrp','$date_of_purchase')";

		$res =  $this->db->query($sql);						
        
		return 1;
	}
	
	public function add_central_stock_report($data, $invoice_no, $item_number, $item_name, $batch_number, $curruntyquantity, $expiry, $expiry_day, $add_date, $employee_number, $vendor_price, $mrp, $item_qty, $gstrate, $gstdivision,  $closingstock, $center_number, $date_of_purchase)
    {   
		$sql = "INSERT INTO `".$this->config->item('db_prefix')."central_stock_report` (`invoice_no`,`item_number`,`item_name`,`batch_number`,`openstock`,`expiry`,`expiry_day`,`add_date`,`employee_number`,`vendor_price`, `quantity_in`, `mrp`, `gstrate`, `gstdivision`, `enddate`, `quantity_out`, `closingstock`, `type`,`center_number`,`date_of_purchase`) VALUES ('$invoice_no','$item_number','$item_name','$batch_number','$curruntyquantity','$expiry','$expiry_day','".date("Y-m-d H:i:s")."','$employee_number','$vendor_price','$item_qty','$mrp','$gstrate','$gstdivision','".date("Y-m-d H:i:s")."','$item_qty','$closingstock','Center In','$center_number','$date_of_purchase')";
        
		$res =  $this->db->query($sql);						
        
		$sql2 = "UPDATE ".$this->config->item('db_prefix')."stocks SET `quantity` = `quantity` - ".$item_qty." WHERE item_number='".$item_number."'";
        $this->db->query($sql2);
		//die;
		return 1;
	}
	
	public function transfer_stock_report($data, $item_number, $product_id, $item_name, $company, $batch_number, $openstock, $expiry, $expiry_day, $add_date, $employee_number, $vendor_price, $mrp, $hsn, $gstrate, $gstdivision, $pack_size, $brand_name, $vendor_number, $generic_name, $category, $quantity_out, $center_number, $department,$invoice_no, $date_of_purchase, $r_center_number, $r_department, $r_employee_number, $remarks)
    
	{ 
		$closingstock = $openstock - $quantity_out;
		$sql = "INSERT INTO `".$this->config->item('db_prefix')."central_stock_report` (`item_number`,`item_name`,`company`,`batch_number`,`openstock`,`expiry`,`expiry_day`,`add_date`,`employee_number`,`vendor_price`,`mrp`,`hsn`,`gstrate`,`gstdivision`,`quantity_out`,`enddate`,`closingstock`,`type`,`center_number`) VALUES ('$item_number','$item_name','$company','$batch_number','$openstock','$expiry','$expiry_day','".date("Y-m-d H:i:s")."','$employee_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','$quantity_out','".date("Y-m-d H:i:s")."','$closingstock','Transfer Stocks','$center_number')";
		$res =  $this->db->query($sql);						
        
		$sql2 = "INSERT INTO `".$this->config->item('db_prefix')."transfer_stocks` (`item_number`,`product_id`,`item_name`,`company`,`batch_number`,`expiry`,`expiry_day`,`quantity`,`add_date`,`employee_number`,`vendor_price`,`mrp`,`hsn`,`gstrate`,`gstdivision`,`pack_size`,`brand_name`,`vendor_number`,`generic_name`,`category`,`r_quantity`,`department`,`center_number`,`invoice_no`,`date_of_purchase`,`r_center_number`,`r_department`,`r_employee_number`,`remarks`) VALUES ('$item_number','$product_id','$item_name','$company','$batch_number','$expiry','$expiry_day','$quantity_out','".date("Y-m-d H:i:s")."','$employee_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','$pack_size','$brand_name','$vendor_number','$generic_name','$category','$quantity_out','$department','$center_number','$invoice_no','$date_of_purchase','$r_center_number','$r_employee_number','$r_department','$remarks')";
		$res =  $this->db->query($sql2);						
        
		return 1;
	}

	public function transfer_stock_report_center($data, $item_number, $product_id, $item_name, $company, $batch_number, $quantity, $openstock, $expiry, $expiry_day, $add_date, $employee_number, $vendor_price, $mrp, $hsn, $gstrate, $gstdivision, $pack_size, $brand_name, $vendor_number, $generic_name, $category, $quantity_out, $center_number, $department, $invoice_no, $date_of_purchase)
    
	{ 
		$closingstock = $openstock - $quantity_out;
		$sql = "INSERT INTO `".$this->config->item('db_prefix')."central_stock_report` (`item_number`,`invoice_no`,`item_name`,`company`,`batch_number`,`openstock`,`expiry`,`expiry_day`,`add_date`,`employee_number`,`vendor_price`,`mrp`,`hsn`,`gstrate`,`gstdivision`,`quantity_out`,`enddate`,`closingstock`,`type`,`center_number`,`date_of_purchase`) VALUES ('$item_number','$invoice_no','$item_name','$company','$batch_number','$openstock','$expiry','$expiry_day','".date("Y-m-d H:i:s")."','$employee_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','$quantity_out','".date("Y-m-d H:i:s")."','$closingstock','Center In','$center_number','$date_of_purchase')";
		$res =  $this->db->query($sql);						
        
		$sql2 = "INSERT INTO `".$this->config->item('db_prefix')."center_stocks` (`item_number`,`invoice_no`,`product_id`,`item_name`,`company`,`batch_number`,`quantity`,`expiry`,`expiry_day`,`add_date`,`employee_number`,`vendor_price`,`mrp`,`hsn`,`gstrate`,`gstdivision`,`pack_size`,`brand_name`,`vendor_number`,`generic_name`,`category`,`center_number`,`department`,`date_of_purchase`) VALUES ('$item_number','$invoice_no','$product_id','$item_name','$company','$batch_number','$quantity','$expiry','$expiry_day','".date("Y-m-d H:i:s")."','$employee_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','$pack_size','$brand_name','$vendor_number','$generic_name','$category','$center_number','$department','$date_of_purchase')";
		$res =  $this->db->query($sql2);						
        return 1;
	}	
	
	
	public function update_central_stock_report($data, $item_id, $company, $item_name, $batch_number, $expiry, $expiry_day, $add_date, $vendor_price, $quantity_in, $mrp, $gstrate, $gstdivision, $quantity, $closingstock)
    {
	 $sql = "INSERT INTO `".$this->config->item('db_prefix')."central_stock_report` (`item_number`,`company`,`item_name`,`batch_number`,`expiry`,`expiry_day`,`add_date`,`vendor_price`,`quantity_in`, `mrp`,`openstock`,`gstrate`, `gstdivision`,`quantity_out`,`closingstock`,`enddate`,`type`) VALUES ('$item_id','$company','$item_name','$batch_number','$expiry','$expiry_day','".date("Y-m-d H:i:s")."','$vendor_price','$quantity_in','$mrp','$quantity','$gstrate','$gstdivision','$quantity_in','$closingstock','".date("Y-m-d H:i:s")."','Update Centeral Product')";

		$res =  $this->db->query($sql);						
	}
		
	function get_item_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."stocks where item_number='".$item."'";
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
	
	function get_audit_stocks_data($ID){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where ID='$ID'";
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
	
	function audit_item_data($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "audit_stocks` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
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
	
	function export_audit_report($employee_number, $start, $end, $add_date, $item_name){

		$investigation_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
        if(!empty($start) && !empty($end)){
            $conditions .= " and expiry_day between '".$start."' AND '".$end."' ";
        }
		 if(!empty($add_date)){
			$conditions .= ' and add_date="'.$add_date.'"';
        }
		 if(!empty($item_name)){
			$conditions .= ' and item_name="'.$item_name.'"';
        }
		
	    $investigation_sql = "Select DISTINCT item_name,batch_number,quantity,physical_quantity,register_quantity,short, excess, damage, discard, reason, item_below_min, near_expiry, requisition, employee_number,add_date from ".$this->config->item('db_prefix')."audit_stocks where 1 $conditions order by rand() desc";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				
				$response[] = array(
                        'item_name' => $val['item_name'],
				        'batch_number' => $val['batch_number'],
                        'quantity' => $val['quantity'],
                        'physical_quantity' => $val['physical_quantity'],
                        'register_quantity' => $val['register_quantity'],
                        'short' => $val['short'],
                        'excess' => $val['excess'],
                        'damage' => $val['damage'],
						'discard' => $val['discard'],
						'reason' => $val['reason'],
						'item_below_min' => $val['item_below_min'],
						'near_expiry' => $val['near_expiry'],
						'requisition' => $val['requisition'],
						'employee_number' => $val['employee_number'],
						'add_date' => $val['add_date'],
                );
            }
        } 
                 
 		
		return $response;
    }
	
	function get_all_audit_report($employee_number, $start_date, $end_date, $add_date, $item_name,$batch_number){
		//$investigation_result = array();
		$conditions = '';
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($add_date)){
			$conditions .= " and add_date like '%$add_date%'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_day between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry_day='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_day='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."audit_stocks where 1 ".$conditions." order by add_date desc";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}

	function get_all_audit_report_patination($limit, $page, $employee_number, $start_date, $end_date, $add_date, $item_name, $batch_number){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['employee_number']) && !empty($_SESSION['logged_accountant']['employee_number'])){ 
			$conditions = ' and employee_number="'.$_SESSION['logged_accountant']['employee_number'].'"'; 
		}
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($add_date)){
			$conditions .= " and add_date like '%$add_date%'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_day between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry_day='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_day='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."audit_stocks where 1".$conditions." order by add_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}

	function get_audit_report($employee_number, $start_date, $end_date, $add_date, $item_name,$batch_number){
		//$investigation_result = array();
		$conditions = '';
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($add_date)){
			$conditions .= " and add_date like '%$add_date%'";
		}
		$investigation_sql = "Select DISTINCT employee_number, add_date from ".$this->config->item('db_prefix')."audit_stocks where  1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}

	function get_audit_report_patination($limit, $page, $employee_number, $start_date, $end_date, $add_date, $item_name, $batch_number){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($add_date)){
			$conditions .= " and add_date like '%$add_date%'";
		}
	    $investigation_sql = "Select DISTINCT employee_number, add_date from ".$this->config->item('db_prefix')."audit_stocks where 1".$conditions." order by add_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	public function update_audit_report($data, $id)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "audit_stocks SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE id = '".$id."'";
        $this->db->query($sql);
        return 1;
    }
	
	function get_audit_data($id){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."audit_stocks where id='".$id."'";
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

	function get_stock_product_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."stock_products where ID='".$item."'";
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

	function get_center_item_data($item){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where item_number='".$item."'";
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
	
	function get_center_return_data($ID){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where ID='".$ID."'";
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
	
	function get_center_return_item($item, $employee_number){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where item_number='".$item."' and employee_number='".$_SESSION['logged_stock_manager']['employee_number']."'";
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
	
	function get_center_item_data2($item){
		$result = array();
		$sql_condition = '';
	    
		if($_SESSION['logged_stock_manager']['employee_number']){
	    $sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where item_number='".$item."' and department='".$_SESSION['logged_stock_manager']['department']."'";
	    }else{
	     $sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where item_number='".$item."' and department='".$_SESSION['logged_billing_manager']['department']."'";
	     }
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
	
	function get_centeral_item($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."stocks where item_number='".$item."'";
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
	
	function get_center_item_medicine($ID){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where ID='".$ID."'";
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
	
	
	
	function get_medicine_update($ID){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where ID='$ID'";
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

	public function update_stock_product_data($data, $item)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "stock_products SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$item."'";
        $this->db->query($sql);
        return 1;
	}

	public function product_vendor_update($data, $item)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "product_vendors SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$item."'";
        $this->db->query($sql);
        return 1;
	}
	
	public function update_item_data($data, $item, $company, $item_name, $generic_name, $batch_number, $mrp, $product_id, $vendor_price, $quantity_in, $hsn, $pack_size, $gstrate, $gstdivision, $expiry, $vendor_number, $brand_name, $status)
    {
		 $sql3 = "UPDATE hms_stocks SET `item_number` = '$item', `company` = '$company', `item_name` = '$item_name', `generic_name` = '$generic_name', `batch_number` = '".$batch_number."', `mrp` = '".$data['mrp']."', `product_id` = '".$data['product_id']."', `vendor_price` = '".$data['vendor_price']."', `quantity` = quantity+".$data['quantity_in'].", `hsn` = '".$data['hsn']."', `pack_size` = '".$data['pack_size']."', `gstrate` = '".$data['gstrate']."',`gstdivision` = '".$data['gstdivision']."', `expiry` = '".$data['expiry']."', `safety_stock` = '".$data['safety_stock']."', `order_qty` = '".$data['order_qty']."', `category` = '".$data['category']."',`vendor_number` = '".$data['vendor_number']."',`brand_name` = '".$data['brand_name']."', `status` = '".$data['status']."',`medicine_type` = '".$data['medicine_type']."'  WHERE `item_number`='".$item."'"; //echo $sql3; die;` WHERE `item_number`='".$item."'";
		$this->db->query($sql3);
        return $this->db->affected_rows();
    }

	public function bulk_update_items_status($item_numbers, $status)
	{
		if (empty($item_numbers)) {
			return 0;
		}
		$clean_items = array();
		foreach ($item_numbers as $num) {
			$clean_items[] = $this->db->escape_str($num);
		}
		$status_val = $this->db->escape_str($status);
		$sql = "UPDATE hms_stocks SET `status` = '".$status_val."' WHERE `item_number` IN ('".implode("','", $clean_items)."')";
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function bulk_update_center_items_status($item_ids, $status)
	{
		if (empty($item_ids)) {
			return 0;
		}
		$clean_items = array();
		foreach ($item_ids as $id) {
			$clean_items[] = $this->db->escape_str($id);
		}
		$status_val = $this->db->escape_str($status);
		$sql = "UPDATE " . $this->config->item('db_prefix') . "center_stocks SET `status` = '".$status_val."' WHERE `ID` IN ('".implode("','", $clean_items)."')";
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function update_item($data, $product_id, $invoice_no, $no_of_item, $brand_name, $vendor_number, $batch_number, $vendor_price, $hsn, $pack_size, $pack, $gstrate, $gstdivision, $expiry, $date_of_purchase)
    {
		//var_dump($data);die;
		$sql = "UPDATE hms_stocks SET `product_id` = '$product_id',  `no_of_item` = '$no_of_item', `brand_name` = '$brand_name', `vendor_number` = '$vendor_number', `quantity` = quantity+".$data['quantity'].", `safety_stock` = '".$data['safety_stock']."', `price` = '".$data['price']."', `batch_number` = '".$batch_number."', `expiry` = '$expiry', `expiry_day` = '".$data['expiry_day']."', `order_qty` = '".$data['order_qty']."', `category` = '".$data['category']."', `vendor_price` = '".$data['vendor_price']."',`pack_size` = '".$data['pack_size']."',`pack` = '".$data['pack']."',`hsn` = '".$data['hsn']."',`gstrate` = '".$data['gstrate']."',`gstdivision` = '".$data['gstdivision']."',`invoice_no` = '".$data['invoice_no']."',`date_of_purchase` = '".$data['date_of_purchase']."', `status` = '1'";
		$sql .= " WHERE `product_id`='$product_id' and `brand_name`='$brand_name' and `vendor_number`='$vendor_number' and `batch_number`='$batch_number' and `vendor_price`='$vendor_price' and `hsn`='$hsn' and `pack_size`='$pack_size' and `gstrate`='$gstrate'";
		$this->db->query($sql);
        return $this->db->affected_rows();
    }
	
	
	public function update_center_item_data($data, $ID)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "center_stocks SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$ID."'";
        $this->db->query($sql);
        return 1;
    }
	
	public function update_center_item_medicine($data, $ID, $post_arr)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "patient_medicine SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$ID."'";
        $this->db->query($sql);
        return 1;
    }
	
	public function delete_item_data($item){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "stocks WHERE item_number = '".$item."'";
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return 1;
		}
		else
			return 0;	
	}
	
	public function delete_center_item_data($item){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "center_stocks WHERE item_number = '".$item."'";
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return 1;
		}
		else
			return 0;	
	}
	
	function get_categories(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."categories ORDER by ID ASC";
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
	
	function add_category($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "categories` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
		}
		$sqlArr[] = " category_id = '".addslashes(getGUID())."'"	;
		$sql .= implode(',' , $sqlArr);
		
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}
	
	function get_category_data($cat){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."categories where ID='".$cat."'";
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
	
	public function update_category_data($data, $item)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "categories SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$item."'";
        $this->db->query($sql);
        return 1;
    }
	
	public function delete_category_data($item){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "categories WHERE ID = '".$item."'";
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return 1;
		}
		else
			return 0;	
	}
	
	
	/**** Products ***/
		
	function get_products(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."products ORDER by ID ASC";
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
	
	function add_product($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "products` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
		}
		$sqlArr[] = " code = '".addslashes(getGUID())."'"	;
		$sql .= implode(',' , $sqlArr);
		
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return $this->db->insert_id();
		}
		else
			return 0;
	}
	
	function get_product_data($item_id){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."products where ID='".$item_id."'";
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
	
	public function update_product_data($data, $item)
    {			
        $sql = "UPDATE " . config_item('db_prefix') . "products SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$item."'";
        $this->db->query($sql);
        return 1;
    }
	
	public function delete_product_data($item){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "products WHERE ID = '".$item."'";
       	$res =  $this->db->query($sql);
		if ($res)
		{
			return 1;
		}
		else
			return 0;	
	}
		
	/**** Products ****/
	
	function get_category_name($category){
		$result = array();
		$sql_condition = '';
		$sql = "Select name from ".$this->config->item('db_prefix')."categories where category_id='".$category."' ORDER by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['name'];
        }
        else
        {
            return $result;
        }
	}
	
	function get_brand_name($brand){
		$result = array();
		$sql_condition = '';
		$sql = "Select name from ".$this->config->item('db_prefix')."brands where brand_number='".$brand."' ORDER by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['name'];
        }
        else
        {
            return "--";
        }
	}
	
	function get_consumbles_list(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."stocks where category='1565461619'";
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
	
	function get_employee_list(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."employees where other_role='stock_manager' and status='1'";
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

	function get_stock_user(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."employees where department='billing' and status='1'";
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
	
	function get_center_consumbles_list(){
		$result = array();
		$sql_condition = '';
		//$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where category='1565461624' AND center_number='".$_SESSION['logged_stock_manager']['center']."' AND employee_number='".$_SESSION['logged_stock_manager']['employee_number']."' AND quantity > 0 order by expiry asc";
	   
		if($_SESSION['logged_stock_manager']['center']){
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where category='1565461624' AND center_number='".$_SESSION['logged_stock_manager']['center']."' AND department='".$_SESSION['logged_stock_manager']['department']."' AND quantity > 0 order by expiry asc";
	    }else{
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where category='1565461624' AND center_number='".$_SESSION['logged_billing_manager']['center']."' AND department='".$_SESSION['logged_billing_manager']['department']."' AND quantity > 0 order by expiry asc";
        }
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
	
	function get_center_consumbles_medicine_list(){
		$result = array();
		$sql_condition = '';
		//$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_stock_manager']['center']."' AND employee_number='".$_SESSION['logged_stock_manager']['employee_number']."' AND quantity > 0 order by expiry asc";
	    
		if(isset($_SESSION['logged_stock_manager']['center']) && $_SESSION['logged_stock_manager']['center']){
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_stock_manager']['center']."' AND department='".$_SESSION['logged_stock_manager']['department']."' AND quantity > 0 order by expiry asc";
	    }else{
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_billing_manager']['center']."' AND department='".$_SESSION['logged_billing_manager']['department']."' AND quantity > 0 order by expiry asc";
	    }
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

	
	function get_injection_list(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."stocks where category='1565461619'";
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
	
	function get_center_injection_list(){
		$result = array();
		$sql_condition = '';
		if($_SESSION['logged_stock_manager']['center']){
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where category='1565461619' AND center_number='".$_SESSION['logged_stock_manager']['center']."' AND department='".$_SESSION['logged_stock_manager']['department']."' AND status='1' AND quantity > 0 order by expiry asc";
        }else{
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where category='1565461619' AND center_number='".$_SESSION['logged_billing_manager']['center']."' AND department='".$_SESSION['logged_billing_manager']['department']."' AND status='1' AND quantity > 0 order by expiry asc";
        }
		//  $sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_stock_manager']['center']."' AND quantity > 0 order by expiry asc";
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

	function get_stock_item_price($item_number, $units){
		$get_item_details = $this->get_item_details($item_number);
		if(!empty($get_item_details)){
			$product_vendor_info = $get_item_details['product_vendor_info'];
			//var_dump($product_vendor_info);die;
			if(!empty($product_vendor_info)){
				$vendor_price = $product_vendor_info['price'];
				$vendor_units = $product_vendor_info['units'];

				$percent = $vendor_price / $vendor_units;
				$item_price = $percent * $units ;
				return $item_price;
			}else{
				return " ";
			}
		}else{
			return " ";
		}
	}
	function get_stock_item_discount_price($item_number, $units, $discount){
		$get_item_details = $this->get_item_details($item_number);
		if(!empty($get_item_details)){
			$product_vendor_info = $get_item_details['product_vendor_info'];
			//var_dump($product_vendor_info);die;
			if(!empty($product_vendor_info)){
				$vendor_price = $product_vendor_info['price'];
				$vendor_units = $product_vendor_info['units'];

				$percent = $vendor_price / $vendor_units;
				$item_price = ($percent * $units)- $discount;
				$totalPrice = $percent * $units;
                $totalDiscountPrice = ($totalPrice * $discount)/100;
                $item_price = $totalPrice - $totalDiscountPrice;
				return $item_price;
			}else{
				return " ";
			}
		}else{
			return " ";
		}
	}
	
	function get_medicine_list(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."stocks where category='1579086005'";
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
	
	function get_center_medicine_list(){
		$result = array();
		$sql_condition = '';
		if($_SESSION['logged_stock_manager']['center']){
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where category='1579086005' AND center_number='".$_SESSION['logged_stock_manager']['center']."' AND department='".$_SESSION['logged_stock_manager']['department']."' AND quantity > 0 order by expiry asc";
        }else{
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where category='1579086005' AND center_number='".$_SESSION['logged_billing_manager']['center']."' AND department='".$_SESSION['logged_billing_manager']['department']."' AND quantity > 0 order by expiry asc";
        }	
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
	
	function get_center_embrology_list(){
		$result = array();
		$sql_condition = '';
		if($_SESSION['logged_stock_manager']['center']){
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where category='1565461628' AND center_number='".$_SESSION['logged_stock_manager']['center']."' AND department='".$_SESSION['logged_stock_manager']['department']."' AND quantity > 0 order by expiry asc";
        }else{
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where category='1565461628' AND center_number='".$_SESSION['logged_billing_manager']['center']."' AND department='".$_SESSION['logged_billing_manager']['department']."' AND quantity > 0 order by expiry asc";
        }
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
	
	/*function get_medicine_data($receipt){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where receipt_number='$receipt' order by on_date asc";
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
	
	
	function update_stock($id, $order_quantity)
	{
 		$sql = "UPDATE " . config_item('db_prefix') . "stocks SET quantity = quantity + '".$order_quantity."'";
		$sql .= " WHERE item_number = '".$id."'";
        $this->db->query($sql);
        return 1;	
    }
	
	function billing_item_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "patient_items` SET ";
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
	
	function billing_medicine_item_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "patient_medicine` SET ";
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
	
	function billing_purchase_item_insert($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "purchase_order` SET ";
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
	
	function deduct_stock($ID, $serial, $qty){
		if($_SESSION['logged_stock_manager']['employee_number']){
		$sql = "UPDATE ".$this->config->item('db_prefix')."center_stocks SET `quantity` = `quantity` - ".$qty." WHERE item_number='".$serial."' AND ID='".$ID."' AND department='".$_SESSION['logged_stock_manager']['department']."'";
        }else{
		$sql = "UPDATE ".$this->config->item('db_prefix')."center_stocks SET `quantity` = `quantity` - ".$qty." WHERE item_number='".$serial."' AND ID='".$ID."' AND department='".$_SESSION['logged_billing_manager']['department']."'";
        }	
		$this->db->query($sql);
        return 1;
	}

	function deduct_transfer_stock($item_number, $invoice_no, $batch_number, $status, $employee_number, $quantity_out, $center_number, $department){
		$sql = "UPDATE ".$this->config->item('db_prefix')."center_stocks SET `quantity` = `quantity` - ".$quantity_out." WHERE item_number='".$item_number."' AND invoice_no='".$invoice_no."' AND batch_number='".$batch_number."' AND status='1' AND employee_number='".$employee_number."' AND center_number='".$center_number."' AND department='".$department."'";
        $this->db->query($sql);
        return 1;
	}
	
	function update_return_stock($item_qty, $item_id){
		$sql = "UPDATE ".$this->config->item('db_prefix')."stocks SET `quantity` = `quantity` + ".$item_qty.", return_date='".date("Y-m-d H:i:s")."' WHERE item_number='".$item_id."'";
        $this->db->query($sql);
        return 1;
	}
	
	function get_patient_name($receipt, $patient_id){
		$consumable_result = $injection_result = $medicine_result = array();
		
		$patient_items_sql = "Select * from ".$this->config->item('db_prefix')."patient_items where patient_id='".$patient_id."' AND receipt_number='".$receipt."'";
        $patient_items_q = $this->db->query($patient_items_sql);
        $patient_items_result = $patient_items_q->result_array();
		if (!empty($patient_items_result))
        {
			$patient_items_result = $patient_items_result[0];
			$data = unserialize($patient_items_result['data']);
			$data = $data['data'];
			if (!empty($data['consumables']))
       		{
				foreach($data['consumables'] as $ky => $vl){
					$consumable_result[] = $vl;
				}
			}
			if (!empty($data['injections']))
       		{
				foreach($data['injections'] as $ky => $vl){
					$injection_result[] = $vl;
				}
			}
			if (!empty($data['medicine']))
       		{
				foreach($data['medicine'] as $ky => $vl){
					$medicine_result[] = $vl;
				}
			}
			
			$response = array();
			$response = array('consumable_result' => $consumable_result, 'injection_result' => $injection_result, 'medicine_result' => $medicine_result);
			return $response;
        }
	}
	
	function get_patient_items_data($patient_id){
		$consumable_result = $injection_result = $medicine_result = $patient_result = array();
		
		$patient_sql = "Select wife_name, wife_email, wife_phone from ".$this->config->item('db_prefix')."patients where patient_id='".$patient_id."'";
		//echo $patient_sql;die;
		$patient_q = $this->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		//var_dump($patient_result);die;
		if (!empty($patient_result))
        {
           $patient_result = $patient_result[0];
        }

		$patient_items_sql = "Select * from ".$this->config->item('db_prefix')."patient_items where patient_id='".$patient_id."' order by add_on desc";
        $patient_items_q = $this->db->query($patient_items_sql);
        $patient_items_result = $patient_items_q->result_array();
		
		$response = array();
		
		if (!empty($patient_items_result))
        {
			
			//echo $vls['add_on'];
				//echo '<pre>';
				//print_r($patient_items_result);
				//echo '</pre>';
			
			foreach($patient_items_result as $key => $vals){
				//var_dump($vals);die;
				$patientitemsresult = $vals;
				$data = unserialize($patientitemsresult['data']);
				
				
				$data = $data['data'];
				
				if (!empty($data['consumables']))
				{
					foreach($data['consumables'] as $ky => $vl){
						$consumable_result[] = $vl;
					}
				}
				if (!empty($data['injections']))
				{
					foreach($data['injections'] as $ky => $vl){
						$injection_result[] = $vl;
					}
				}
				if (!empty($data['medicine']))
				{
					foreach($data['medicine'] as $ky => $vl){
						$medicine_result[] = $vl;
					}
				}
			}
			$response[] = array(
			'consumable_result' => $consumable_result,
			'injection_result' => $injection_result,
			'medicine_result' => $medicine_result,
			'receipt_number' => $patientitemsresult['receipt_number'],
			'add_on' => $patientitemsresult['add_on'],
			);
			//var_dump($response);die;
		}
		
		
		$response['patient_result'] = $patient_result;//var_dump($response);die;
		return $response;		
	}
	
	
	
	// Brands
	function get_brands(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."brands ORDER by ID ASC";
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
		$sql = "Select * from ".$this->config->item('db_prefix')."brands where status='1' ORDER by ID ASC";
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

	function stock_product_brands($data, $product){
		$sql = "DELETE FROM " . $this->config->item('db_prefix') . "product_brands WHERE product_id = '".$product."'";
		$res =  $this->db->query($sql);
		
		foreach($data['brands'] as $key => $vals){
			$sql = "INSERT INTO `".$this->config->item('db_prefix')."product_brands` (`brand_number`, `product_id`) VALUES ('$vals', '$product')";
			$res =  $this->db->query($sql);						
		}
		return 1;
	}

	// Product Vendor


	function product_vendor_add($data){
		$sql = "INSERT INTO `" . $this->config->item('db_prefix') . "product_vendors` SET ";
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

	function get_product_vendor_data($id){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."product_vendors where ID='$id'";
        $q = $this->db->query($sql);
		$result = $q->result_array();
        if (!empty($result))
        {
            return $result[0];
        }else{
            return $result;
        }
	}

	function get_stock_product_vendors(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."product_vendors ORDER by ID ASC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }else{
            return $result;
        }
	}

	
	function get_product_brands($product){
		$result = array();
		$sql_condition = '';
		$sql = "Select brand_number from ".$this->config->item('db_prefix')."product_brands where product_id='".$product."'";
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

	function check_center_item($item_number, $employee_number){
	    
	    //$sql_condition = "";
	    //if(!empty($center)){
	    //    $sql_condition = " AND center_number='".$center."' ";    
	    //}
	    
		$result = array();
		//var_dump($sql);die;
		$sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where item_number='".$item_number."' and employee_number='".$employee_number."' limit 1";
		
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

	function check_centeral_item($product_id, $brand_name, $vendor_number, $batch_number, $vendor_price){
		$result = array();
		$sql_condition = '';
		$sql = "Select count(*) as count from ".$this->config->item('db_prefix')."stocks where product_id='".$product_id."' and brand_name='$brand_name' and vendor_number='$vendor_number' and batch_number='$batch_number' and vendor_price='$vendor_price'  limit 1";
		//echo $sql;die;
		$q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['count'];
        }else
        {
            return $result;
        }
	}

	public function update_center_item_qty($employee_number, $item_number, $item_qty, $status)
    {
		$sql = "UPDATE `hms_center_stocks` SET `quantity`= `quantity`+$item_qty, `update_date` = '".date("Y-m-d H:i:s")."' WHERE employee_number='$employee_number' and item_number='$item_number' and status='1'";
		//echo $sql;die;
		$this->db->query($sql);
        return $this->db->affected_rows();
	}
	
	// Patient Inventory 
	function inventory_dispense_data(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."patient_items ORDER by add_on DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result;
        }else
        {
            return $result;
        }
	}

	// Vendor number 
	function get_vendor_number($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select vendor_number from ".$this->config->item('db_prefix')."stocks where item_number='".$item."' ORDER by ID DESC";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['vendor_number'];
        }
        else
        {
            return "--";
        }
	}	

	function get_vendor_name($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select name from ".$this->config->item('db_prefix')."vendors where vendor_number='".$item."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['name'];
        }
        else
        {
            return "--";
        }
	}

	function patient_center_medicine_count($center, $start_date, $end_date, $patient_id){
		//$investigation_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}

	function center_medicine_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id){
		//$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		if($_SESSION['logged_stock_manager']['center']){
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where billing_at='".$_SESSION['logged_stock_manager']['center']."' AND employee_number='".$_SESSION['logged_stock_manager']['employee_number']."' AND 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		}else{
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where billing_at='".$_SESSION['logged_billing_manager']['center']."' AND employee_number='".$_SESSION['logged_billing_manager']['employee_number']."' AND 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		}	
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	function disaprove_medicine_count($center, $start_date, $end_date, $patient_id){
		//$investigation_result = array();
		$conditions = '';
		
		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}
	
	function disaprove_medicine_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id){
		//$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where status='disapproved' AND 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	function export_medicine_center_data($start, $end, $patient_id, $type){

		$investigation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['stock_manager']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
		if($_SESSION['logged_stock_manager']['employee_number']){
	    $investigation_sql = "Select DISTINCT patient_id, patient_detail_name, on_date,data, receipt_number, hospital_id, payment_method,status from ".$this->config->item('db_prefix')."patient_medicine where 1 $conditions AND employee_number='".$_SESSION['logged_stock_manager']['employee_number']."' order by on_date desc";
        }else{
		$investigation_sql = "Select DISTINCT patient_id, patient_detail_name, on_date,data, receipt_number, hospital_id, payment_method,status from ".$this->config->item('db_prefix')."patient_medicine where 1 $conditions AND employee_number='".$_SESSION['logged_billing_manager']['employee_number']."' order by on_date desc";
       	}
		$investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				$_data = unserialize($val['data']);
				$consumables_serial_arr = [];
				$consumables_name_arr = [];
				$consumables_stock_arr = [];
				$consumables_quantity_arr = [];
				//echo '<pre>';
				//print_r($_data);
                 //die;
				//echo '<br>';
				foreach($_data as $value1){
				//	print_r($value1);
					foreach($value1 as $value2){
						//print_r($value2);
						foreach($value2 as $value3){
							//print_r($value3);
							if($value3['consumables_name']){
								array_push($consumables_name_arr,$value3['consumables_name']);
							}
							array_push($consumables_serial_arr,$value3['consumables_serial']);
							array_push($consumables_stock_arr,$value3['consumables_total_']);
							array_push($consumables_quantity_arr,$value3['consumables_quantity']);
						}
					}
				}
				//print_r($consumables_name_arr);
				 $consumables_serial = implode(',', $consumables_serial_arr );
				 $consumables_name = implode(',', $consumables_name_arr );
				 $consumables_total_ = implode(',', $consumables_stock_arr );
				 $consumables_quantity = implode(',', $consumables_quantity_arr );
				if($consumables_name  != ''){
				 $consumables_name_sql = "SELECT item_name FROM hms_stocks WHERE item_number IN (".$consumables_name.")" ;
				}
				//die();
				$_consumables_name_arr = [];
				$consumables_name_sql_q = $this->db->query($consumables_name_sql);
				$consumables_name_sql_q_result = $consumables_name_sql_q->result_array();
				if(!empty($consumables_name_sql_q_result)){
					foreach($consumables_name_sql_q_result as $key => $consumables_name_val){
						array_push($_consumables_name_arr,$consumables_name_val['item_name']);
					}
				}
				
				$final_consumables_arr =[];
				
				foreach($_consumables_name_arr as $_key => $_value){
					
					$final_consumables123 = $_value;
					array_push($final_consumables_arr,$final_consumables123 );
					$response[] = array(
				        'on_date' => $val['on_date'],
                        'patient_id' => $val['patient_id'],
				        'patient_detail_name' => $val['patient_detail_name'],
						'hospital_id' => $val['hospital_id'],
                        'receipt_number' => $val['receipt_number'],
                        'payment_method' => $val['payment_method'],
						'consumables_serial'=> $consumables_serial_arr[$_key],
                        'final__consumables'=> $final_consumables123,
                        'consumables_quantity' => $consumables_quantity_arr[$_key],
						'consumables_total_' => $consumables_stock_arr[$_key],
						'status' => $val['status']
                );
					
				}
				
				$_consumables_name = implode(',', $_consumables_name_arr );
				$final__consumables = implode(",\n", $final_consumables_arr );
			}
        }      
		return $response;
    }

/*****All Center Stock*****/
	function get_all_center_stocks($employee_number, $start_date, $end_date, $generic_name, $item_name,$batch_number, $item_number){
		//$investigation_result = array();
		$conditions = '';
		
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		
		if (!empty($generic_name)){
			$conditions .= " and generic_name like '%$generic_name%'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number='$item_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry='$end_date'";
		}
		
	 $investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}

	function get_all_center_stocks_patination($limit, $page, $employee_number, $start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		/*if(isset($_SESSION['logged_accountant']['employee_number']) && !empty($_SESSION['logged_accountant']['employee_number'])){ 
			$conditions = ' and employee_number="'.$_SESSION['logged_accountant']['employee_number'].'"'; 
		}*/
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($generic_name)){
			$conditions .= " and generic_name like '%$generic_name%'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number='$item_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry='$end_date'";
		}
		
		
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where 1".$conditions." order by expiry_day desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	} 

	function get_expiry_item_stocks($employee_number, $start_date, $end_date, $generic_name, $item_name,$batch_number, $item_number){
		//$investigation_result = array();
		$conditions = '';
		
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		
		if (!empty($generic_name)){
			$conditions .= " and generic_name like '%$generic_name%'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number='$item_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry='$end_date'";
		}
		
	 $investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}

	function get_expiry_item_patination($limit, $page, $employee_number, $start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['employee_number']) && !empty($_SESSION['logged_accountant']['employee_number'])){ 
			$conditions = ' and employee_number="'.$_SESSION['logged_accountant']['employee_number'].'"'; 
		}
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($generic_name)){
			$conditions .= " and generic_name like '%$generic_name%'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number='$item_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry='$end_date'";
		}
		
		
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where 1".$conditions." order by expiry ASC limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	} 
	
	function export_all_center_stocks($employee_number, $start, $end, $generic_name, $item_name){

		$investigation_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
        if(!empty($start) && !empty($end)){
            $conditions .= " and expiry_day between '".$start."' AND '".$end."' ";
        }
		 if(!empty($generic_name)){
			$conditions .= ' and generic_name="'.$generic_name.'"';
        }
		 if(!empty($item_name)){
			$conditions .= ' and item_name="'.$item_name.'"';
        }
		
	    $investigation_sql = "Select DISTINCT item_number, company,employee_number, item_name,batch_number,hsn,gstrate,pack_size,brand_name,vendor_number,generic_name,quantity,vendor_price,mrp,center_number,expiry,status,department,date_of_purchase from ".$this->config->item('db_prefix')."center_stocks where 1 $conditions and status='1' order by rand() desc ";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
		if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				
				$response[] = array(
                        'item_number' => $val['item_number'],
				        'company' => $val['company'],
                        'item_name' => $val['item_name'],
                        'batch_number' => $val['batch_number'],
                        'brand_name' => $val['brand_name'],
                        'vendor_number' => $val['vendor_number'],
                        'generic_name' => $val['generic_name'],
						'hsn' => $val['hsn'],
						'gstrate' => $val['gstrate'],
						'pack_size' => $val['pack_size'],
                        'quantity' => $val['quantity'],
						'vendor_price' => $val['vendor_price'],
						'mrp' => $val['mrp'],
                        'category' => $val['category'],
						'expiry' => $val['expiry'],
                        'status' => $val['status'],
						'employee_number' => $val['employee_number'],
						'center_number' => $val['center_number'],
						'department' => $val['department'],
						'date_of_purchase' => $val['date_of_purchase'],
                );
            }
        } 
                 
 		
		return $response;
    }
	
	/*****Center Stock*****/
	function get_center_stocks($start_date, $end_date, $generic_name, $item_name){
		//$investigation_result = array();
		$conditions = '';
		if (!empty($generic_name)){
			$conditions .= " and generic_name like '%$generic_name%'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_day between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry_day='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_day='$end_date'";
		}
		if($_SESSION['logged_stock_manager']['center']){
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_stock_manager']['center']."' AND department='".$_SESSION['logged_stock_manager']['department']."' AND status='1' AND 1 ".$conditions."";
		}else{
		 $investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_billing_manager']['center']."' AND department='".$_SESSION['logged_billing_manager']['department']."' AND status='1' AND 1 ".$conditions."";
		}
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}

	function get_center_stocks_patination($limit, $page, $start_date, $end_date, $generic_name, $item_name){
		//$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		
		if (!empty($generic_name)){
			$conditions .= " and generic_name like '%$generic_name%'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_day between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry_day='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_day='$end_date'";
		}
		
		if($_SESSION['logged_stock_manager']['center']){
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_stock_manager']['center']."' AND department='".$_SESSION['logged_stock_manager']['department']."' AND status='1' AND 1".$conditions." order by expiry_day desc limit ". $limit." OFFSET ".$offset."";
		}else{
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_billing_manager']['center']."' AND department='".$_SESSION['logged_billing_manager']['department']."' AND status='1' AND 1".$conditions." order by expiry_day desc limit ". $limit." OFFSET ".$offset."";
		}	
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	} 
	
	function export_center_stocks($start, $end, $generic_name, $item_name){

		$investigation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' AND billing_at="'.$center.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " AND expiry_day between '".$start."' AND '".$end."' ";
        }
		if($_SESSION['logged_stock_manager']['center']){
	    $investigation_sql = "Select DISTINCT item_number, company, item_name,batch_number,brand_name,vendor_number,generic_name,quantity,safety_stock,order_qty,expiry,status from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_stock_manager']['center']."' AND department='".$_SESSION['logged_stock_manager']['department']."' AND 1 $conditions and status='1' order by rand() desc";
        }else{
		 $investigation_sql = "Select DISTINCT item_number, company, item_name,batch_number,brand_name,vendor_number,generic_name,quantity,safety_stock,order_qty,expiry,status from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_billing_manager']['center']."' AND department='".$_SESSION['logged_billing_manager']['department']."' AND 1 $conditions and status='1' order by rand() desc";
       	}
		$investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				
				$response[] = array(
                        'item_number' => $val['item_number'],
				        'company' => $val['company'],
                        'item_name' => $val['item_name'],
                        'batch_number' => $val['batch_number'],
                        'brand_name' => $val['brand_name'],
                        'vendor_number' => $val['vendor_number'],
                        'generic_name' => $val['generic_name'],
                        'quantity' => $val['quantity'],
						'safety_stock' => $val['safety_stock'],
                        'order_qty' => $val['order_qty'],
						'expiry' => $val['expiry'],
						'status' => $val['status'],
                        'billing_type' => 'Medicine',
                );
            }
        }    
		return $response;
    }	
	
 /*************Central Stock******************/	
 function get_central_stocks_patination($limit, $page, $start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($generic_name)){
			$conditions .= " and generic_name like '%$generic_name%'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number like '%$batch_number%'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number like '%$item_number%'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and expiry_day between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry_day='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_day='$end_date'";
		}
		
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."stocks where 1".$conditions." order by expiry_day desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	} 
	
	function get_central_stocks($start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number){
		//$investigation_result = array();
		$conditions = '';
		
		if (!empty($generic_name)){
			$conditions .= " and generic_name like '%$generic_name%'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number like '%$batch_number%'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number like '%$item_number%'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and expiry_day between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry_day='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry_day='$end_date'";
		}
		
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."stocks where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}
 
 	function export_central_stocks($start, $end, $generic_name, $item_name){

		$investigation_result = $response = array();
        $conditions = '';
		 if(!empty($start) && !empty($end)){
            $conditions .= " and expiry_day between '".$start."' AND '".$end."' ";
        }
		 if(!empty($generic_name)){
			$conditions .= ' and generic_name="'.$generic_name.'"';
        }
		 if(!empty($item_name)){
			$conditions .= ' and item_name="'.$item_name.'"';
        }
		
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."stocks where 1 $conditions order by rand() desc";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				
				$response[] = array(
                        'item_number' => $val['item_number'],
				        'company' => $val['company'],
                        'item_name' => $val['item_name'],
                        'batch_number' => $val['batch_number'],
                        'brand_name' => $val['brand_name'],
                        'vendor_number' => $val['vendor_number'],
                        'vendor_price' => $val['vendor_price'],
						'hsn' => $val['hsn'],
						'pack_size' => $val['pack_size'],
						'gstrate' => $val['gstrate'],
						'mrp' => $val['mrp'],
						'generic_name' => $val['generic_name'],
						'quantity' => $val['quantity'],
						'safety_stock' => $val['safety_stock'],
                        'order_qty' => $val['order_qty'],
                        'category' => $val['category'],
                        'status' => $val['status'],
                );
            }
        }    
		return $response;
    }
	
	function get_active_central_stocks_patination($limit, $page, $item_name, $batch_number, $item_number){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number like '%$batch_number%'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number like '%$item_number%'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."stocks where quantity > 0 and 1".$conditions." order by expiry_day desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	} 
	
	function get_active_central_stocks($item_name, $batch_number, $item_number){
		//$investigation_result = array();
		$conditions = '';
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number like '%$batch_number%'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number like '%$item_number%'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."stocks where quantity > 0 and 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}
	
	/*********End Central Stock***********/
	
	 /*************Stock Report******************/	
/*	function total_stocks_reports($employee_number,$center_number, $start_date, $end_date, $item_name, $batch_number){
		$total_stock_result = array();
		$conditions = '';
		if(!empty($employee_number)){
			$emp_number = implode(', ', $employee_number);
			$conditions .= ' and employee_number in ('.$emp_number.')';
        }
		if(!empty($center_number)){
			$conditions .= ' and center_number="'.$center_number.'"';
        }
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and enddate between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and enddate='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and enddate='$end_date'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number like '%$batch_number%'";
		}
		
	    $total_stock_sql = "SELECT sum(`total_vendor_price_gst_excluded`) as total_vendor_price_gst_excluded, sum(`total_vendor_price_gst_included`) as total_vendor_price_gst_included, sum(`total_mrp_price`) as total_mrp_price from ".$this->config->item('db_prefix')."central_stock_report where employee_number and item_number and (SELECT MAX(enddate)) and 1 " .$conditions;

		$total_stock_q = $this->db->query($total_stock_sql);
		$total_stock_result = $total_stock_q->result_array();
		return $total_stock_result;
	}*/  
	 
    function get_stocks_reports_patination($limit, $page, $employee_number, $center_number, $patient_id, $start_date, $end_date, $item_name, $batch_number, $invoice_no, $type, $date_of_purchase){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(!empty($employee_number)){
			$emp_number = implode(', ', $employee_number);
			$conditions .= ' and employee_number  in ('.$emp_number.')';
        }
		if(!empty($center_number)){
			$conditions .= ' and center_number="'.$center_number.'"';
        }
		if (!empty($patient_id)){
			$conditions .= ' and patient_id="'.$patient_id.'"';
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and enddate between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and enddate='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and enddate='$end_date'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number like '%$batch_number%'";
		}
		if (!empty($invoice_no)){  
			$conditions .= " and invoice_no like '%$invoice_no%'";
		}
		if (!empty($type)){
			$conditions .= " and type like '%$type%'";
		}
		if (!empty($date_of_purchase)){
			$conditions .= " and date_of_purchase like '%$date_of_purchase%'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."central_stock_report where 1".$conditions." order by enddate desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	} 
	
	function get_stocks_reports($employee_number, $center_number, $patient_id, $start_date, $end_date, $item_name, $batch_number, $invoice_no, $type, $date_of_purchase){
		$conditions = '';
		if(!empty($employee_number)){
			$emp_number = implode(', ', $employee_number);
			$conditions .= ' and employee_number  in ('.$emp_number.')';
        }
		if(!empty($center_number)){
			$conditions .= ' and center_number="'.$center_number.'"';
        }
		if (!empty($patient_id)){
			$conditions .= " and patient_id='".$patient_id."'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and enddate between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and enddate='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and enddate='$end_date'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number like '%$batch_number%'";
		}
		if (!empty($invoice_no)){  
			$conditions .= " and invoice_no like '%$invoice_no%'";
		}
		if (!empty($type)){  
			$conditions .= " and type like '%$type%'";
		}
		if (!empty($date_of_purchase)){
			$conditions .= " and date_of_purchase like '%$date_of_purchase%'";
		}
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."central_stock_report where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}
 
 	function export_stocks_reports($employee_number, $center_number, $patient_id, $start_date, $end_date, $item_name, $batch_number, $type){
		$investigation_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
		if(!empty($center_number)){
			$conditions .= ' and center_number="'.$center_number.'"';
        }
		if(!empty($patient_id)){
			$conditions .= ' and patient_id="'.$patient_id.'"';
        }
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and enddate between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and enddate='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and enddate='$end_date'";
		}
		if(!empty($item_name)){
			$conditions .= ' and item_name="'.$item_name.'"';
        }
		if (!empty($type)){  
			$conditions .= " and type like '%$type%'";
		}
		if(!empty($batch_number)){
			$conditions .= ' and batch_number="'.$batch_number.'"';
        }
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."central_stock_report where 1 $conditions order by enddate desc";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){		
				$response[] = array(
				        'item_number' => $val['item_number'],
                        'item_name' => $val['item_name'],
                        'batch_number' => $val['batch_number'],
						'openstock' => $val['openstock'],
						'quantity_in' => $val['quantity_in'],
						'quantity_out' => $val['quantity_out'],
						'closingstock' => $val['closingstock'],
                        'vendor_price' => $val['vendor_price'],
						'mrp' => $val['mrp'],
                        'gstrate' => $val['gstrate'],
						'type' => $val['type'],
						'add_date' => $val['add_date'],
						'employee_number' => $val['employee_number'],
				);
            }
        }
        return $response;
    }

	
	/********End Stock Report******/
	function export_medicine_origin($start, $end, $employee_number, $type){
		$cash_medicine_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
		$cash_medicine_sql = "Select DISTINCT patient_id, patient_detail_name, on_date,data, receipt_number, hospital_id, payment_method,status from ".$this->config->item('db_prefix')."patient_medicine where status='approved' and 1 $conditions";
        $cash_medicine_q = $this->db->query($cash_medicine_sql);
        $cash_medicine_result = $cash_medicine_q->result_array();
        if(!empty($cash_medicine_result)){
            foreach($cash_medicine_result as $key => $val){
				$_data = unserialize($val['data']);
				$consumables_name_arr = [];
				$consumables_item_name_arr = [];
				$consumables_stock_arr = [];
				$consumables_quantity_arr = [];
				foreach($_data as $value1){
				//	print_r($value1);
					foreach($value1 as $value2){
						//print_r($value2);
						foreach($value2 as $value3){
							//print_r($value3);
							if($value3['consumables_name']){
								array_push($consumables_name_arr,$value3['consumables_name']);
							}
							array_push($consumables_item_name_arr,$value3['consumables_item_name']);
							array_push($consumables_stock_arr,$value3['consumables_total_']);
							array_push($consumables_quantity_arr,$value3['consumables_quantity']);
						}
					}
				}
				//print_r($consumables_name_arr);
				 $consumables_name = implode(',', $consumables_name_arr );
				 $consumables_item_name = implode(',', $consumables_item_name_arr );
				 $consumables_total_ = implode(',', $consumables_stock_arr );
				 $consumables_quantity = implode(',', $consumables_quantity_arr );
				if($consumables_name  != ''){
				 $consumables_name_sql = "SELECT item_name FROM hms_stocks WHERE item_number IN (".$consumables_name.") " ;
				}
				//die();
				$_consumables_name_arr = [];
				$consumables_name_sql_q = $this->db->query($consumables_name_sql);
				$consumables_name_sql_q_result = $consumables_name_sql_q->result_array();
				if(!empty($consumables_name_sql_q_result)){
					foreach($consumables_name_sql_q_result as $key => $consumables_name_val){
						array_push($_consumables_name_arr,$consumables_name_val['item_name']);
					}
				}
				
				//$final_consumables_arr =[];
				$final_consumables123 = [];
				
				foreach($_consumables_name_arr as $_key => $_value){
					
					array_push($final_consumables123, $_value);
					$response[] = array(
				        'on_date' => $val['on_date'],
                        'patient_id' => $val['patient_id'],
				        'patient_detail_name' => $val['patient_detail_name'],
						'hospital_id' => $val['hospital_id'],
                        'final__consumables'=> $final_consumables123,
						'consumables_item_name' => $consumables_item_name_arr[$_key],
						'consumables_quantity' => $consumables_quantity_arr[$_key],
						'consumables_total_' => $consumables_stock_arr[$_key],
						'status' => $val['status']
                );
					
				}
				
			}
        }      
		return $response;
    }

	
	function export_medicine_data2($start, $end, $employee_number, $type){
		$investigation_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
		$investigation_sql = "Select DISTINCT patient_id, patient_detail_name, on_date,data, receipt_number, hospital_id, payment_method, status, employee_number from ".$this->config->item('db_prefix')."patient_medicine where 1 $conditions";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
			foreach($investigation_result as $key => $val){
				
				$_data = unserialize($val['data']);
				$consumables_name_arr = [];
				//$consumables_item_name_arr = [];
				$consumables_stock_arr = [];
				$consumables_quantity_arr = [];
				foreach($_data as $value1){
					//print_r($employee_number);
					//echo '<br/>';
					//die();
					foreach($value1 as $value2){
						//print_r($value2);
						foreach($value2 as $value3){
							//print_r($value3);
							if($value3['consumables_name']){
								array_push($consumables_name_arr,$value3['consumables_name']);
							}
							array_push($consumables_stock_arr,$value3['consumables_total_']);
							array_push($consumables_quantity_arr,$value3['consumables_quantity']);
						}
					}
				}
				//print_r($consumables_name_arr);
				 $consumables_name = implode(',', $consumables_name_arr );
				 //$consumables_item_name = implode(',', $consumables_item_name_arr );
				 $consumables_total_ = implode(',', $consumables_stock_arr );
				 $consumables_quantity = implode(',', $consumables_quantity_arr );
				 if($consumables_name  != ''){
				 $consumables_name_sql = "SELECT item_name FROM hms_stocks WHERE item_number IN (".$consumables_name.") " ;
				 }
				//die();
				$_consumables_name_arr = [];
				$consumables_name_sql_q = $this->db->query($consumables_name_sql);
				$consumables_name_sql_q_result = $consumables_name_sql_q->result_array();
				if(!empty($consumables_name_sql_q_result)){
					foreach($consumables_name_sql_q_result as $key => $consumables_name_val){
						array_push($_consumables_name_arr,$consumables_name_val['item_name']);
					}
				}
				
				$final_consumables_arr =[];
				
				foreach($_consumables_name_arr as $_key => $_value){
					
					$final_consumables123 = $_value;
					array_push($final_consumables123);
				
					$response[] = array(
				        'on_date' => $val['on_date'],
                        'patient_id' => $val['patient_id'],
				        'patient_detail_name' => $val['patient_detail_name'],
						'hospital_id' => $val['hospital_id'],
						'receipt_number' => $val['receipt_number'],
                        'payment_method' => $val['payment_method'],
                        'final__consumables'=> $final_consumables123,
						'consumables_name'=> $consumables_name_arr[$_key],
						//'consumables_item_name'=> $consumables_item_name_arr[$_key],
						'consumables_quantity' => $consumables_quantity_arr[$_key],
						'consumables_total_' => $consumables_stock_arr[$_key],
						'status' => $val['status']
				);
				}
			}
        }      
		return $response;
    }
	
	function export_cash_medicine($start_date, $end_date, $employee_number, $patient_id){
		$investigation_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$cash_medicine_sql = "Select DISTINCT patient_id, patient_detail_name, on_date, data, receipt_number, hospital_id, payment_method, employee_number, status from ".$this->config->item('db_prefix')."patient_medicine where 1 $conditions";
        $cash_medicine_q = $this->db->query($cash_medicine_sql);
        $cash_medicine_result = $cash_medicine_q->result_array();
        if(!empty($cash_medicine_result)){
            foreach($cash_medicine_result as $key => $val){
				$_data = unserialize($val['data']);
				$consumables_name_arr = [];
				$consumables_item_name_arr = [];
				$consumables_stock_arr = [];
				$consumables_quantity_arr = [];
				$consumables_price_arr = [];
				$consumables_vendor_price_arr = [];
				$consumables_discount_arr = [];
				$consumables_total_arr = [];
				$consumables_batch_number_arr = [];
				$consumables_hsn_arr = [];
				$consumables_gstrate_arr = [];
				$consumables_gstdivision_arr = [];
				$consumables_mrp_arr = [];
				foreach($_data as $value1){
					//print_r($value1);
					//echo '<br/>';
					//die();
					foreach($value1 as $value2){
						//print_r($value2);
						foreach($value2 as $value3){
							//print_r($value3);
							if($value3['consumables_name']){
								//array_push($consumables_name_arr,$value3['consumables_name']);
							}
							//array_push($consumables_item_name_arr,$value3['consumables_item_name']);
							array_push($consumables_name_arr,$value3['consumables_name']);
							array_push($consumables_item_name_arr,$value3['consumables_item_name']);
							array_push($consumables_stock_arr,$value3['consumables_stock']);
							array_push($consumables_quantity_arr,$value3['consumables_quantity']);
							array_push($consumables_price_arr,$value3['consumables_price']);
							array_push($consumables_vendor_price_arr,$value3['consumables_vendor_price']);
							array_push($consumables_discount_arr,$value3['consumables_discount_']);
							array_push($consumables_total_arr,$value3['consumables_total_']);
							array_push($consumables_batch_number_arr,$value3['consumables_batch_number']);
							array_push($consumables_hsn_arr,$value3['consumables_hsn']);
							array_push($consumables_gstrate_arr,$value3['consumables_gstrate']);
							array_push($consumables_gstdivision_arr,$value3['consumables_gstdivision']);
							array_push($consumables_mrp_arr,$value3['consumables_mrp']);
						}
						//die();
					}
				}
				//print_r($consumables_name_arr);
				
				$consumables_name = implode(',', $consumables_name_arr );
				$consumables_item_name = implode(',', $consumables_item_name_arr );
				$consumables_stock = implode(',', $consumables_stock_arr );
				$consumables_quantity = implode(',', $consumables_quantity_arr );
				$consumables_price = implode(',', $consumables_price_arr );
				$consumables_vendor_price = implode(',', $consumables_vendor_price_arr );
				$consumables_discount_ = implode(',', $consumables_discount_arr );
				$consumables_total_ = implode(',', $consumables_total_arr );
				$consumables_batch_number = implode(',', $consumables_batch_number_arr );
				$consumables_hsn = implode(',', $consumables_hsn_arr );
				$consumables_gstrate = implode(',', $consumables_gstrate_arr );
				$consumables_gstdivision = implode(',', $consumables_gstdivision_arr );
				$consumables_mrp = implode(',', $consumables_mrp_arr );
				if($consumables_name  != ''){
				 $consumables_name_sql = "SELECT item_name FROM hms_stocks WHERE item_number IN (".$consumables_name.") " ;
				}
				//die();
				$_consumables_name_arr = [];
				$consumables_name_sql_q = $this->db->query($consumables_name_sql);
				$consumables_name_sql_q_result = $consumables_name_sql_q->result_array();
				if(!empty($consumables_name_sql_q_result)){
					foreach($consumables_name_sql_q_result as $key => $consumables_name_val){
						array_push($_consumables_name_arr,$consumables_name_val['item_name']);
					}
				}
				
				
				$final_consumables_arr =[];
				
				foreach($_consumables_name_arr as $_key => $_value){
					$final_consumables123 = $_value;
					//array_push($final_consumables123);
					$response[] = array(
				        'on_date' => $val['on_date'],
                        'patient_id' => $val['patient_id'],
				        'patient_detail_name' => $val['patient_detail_name'],
						'hospital_id' => $val['hospital_id'],
						'receipt_number' => $val['receipt_number'],
                        'payment_method' => $val['payment_method'],
                        'consumables_name'=> $consumables_name_arr[$_key],
						'consumables_item_name'=> $consumables_item_name_arr[$_key],
						'consumables_stock'=> $consumables_stock_arr[$_key],
						'consumables_quantity' => $consumables_quantity_arr[$_key],
						'consumables_price' => $consumables_price_arr[$_key],
						'consumables_vendor_price' => $consumables_vendor_price_arr[$_key],
						'consumables_discount_' => $consumables_discount_arr[$_key],
						'consumables_total_' => $consumables_total_arr[$_key],
						'employee_number' => $val['employee_number'],
						'status' => $val['status'],
						'consumables_batch_number' => $consumables_batch_number_arr[$_key],
						'consumables_hsn' => $consumables_hsn_arr[$_key],
						'consumables_gstrate' => $consumables_gstrate_arr[$_key], 
						'consumables_gstdivision' => $consumables_gstdivision_arr[$_key],
						'consumables_mrp' => $consumables_mrp_arr[$_key]
                );
				}
			}
        }      
		return $response;
    }
	
	function export_return_medicine($start_date, $end_date, $employee_number, $patient_id){
		$investigation_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		 $cash_medicine_re_sql = "Select DISTINCT patient_id, patient_detail_name, on_date, return_medicine, receipt_number, hospital_id, payment_method, employee_number, status from ".$this->config->item('db_prefix')."patient_medicine where stutus_type='1' and 1 $conditions";
        $cash_medicine_re_q = $this->db->query($cash_medicine_re_sql);
        $cash_medicine_re_result = $cash_medicine_re_q->result_array();
        if(!empty($cash_medicine_re_result)){
            foreach($cash_medicine_re_result as $key => $val){
				$_data = unserialize($val['return_medicine']);
				$consumables_name_arr_r = [];
				$consumables_item_name_arr_r = [];
				$consumables_stock_arr_r = [];
				$consumables_quantity_arr_r = [];
				$consumables_price_arr_r = [];
				$consumables_vendor_price_arr_r = [];
				$consumables_discount_arr_r = [];
				$consumables_total_arr_r = [];
				$consumables_batch_number_arr_r = [];
				$consumables_hsn_arr_r = [];
				$consumables_gstrate_arr_r = [];
				$consumables_mrp_arr_r = [];
				foreach($_data as $value1){
					foreach($value1 as $value2){
						foreach($value2 as $value3){
							if($value3['consumables_name']){}
							array_push($consumables_name_arr_r,$value3['consumables_name']);
							array_push($consumables_item_name_arr_r,$value3['consumables_item_name']);
							array_push($consumables_stock_arr_r,$value3['consumables_stock']);
							array_push($consumables_quantity_arr_r,$value3['consumables_quantity']);
							array_push($consumables_price_arr_r,$value3['consumables_price']);
							array_push($consumables_vendor_price_arr_r,$value3['consumables_vendor_price']);
							array_push($consumables_discount_arr_r,$value3['consumables_discount_']);
							array_push($consumables_total_arr_r,$value3['consumables_total_']);
							array_push($consumables_batch_number_arr_r,$value3['consumables_batch_number']);
							array_push($consumables_hsn_arr_r,$value3['consumables_hsn']);
							array_push($consumables_gstrate_arr_r,$value3['consumables_gstrate']);
							array_push($consumables_mrp_arr_r,$value3['consumables_mrp']);
						}
					}
				}
				
				$consumables_name = implode(',', $consumables_name_arr_r );
				$consumables_item_name = implode(',', $consumables_item_name_arr_r );
				$consumables_stock = implode(',', $consumables_stock_arr_r );
				$consumables_quantity = implode(',', $consumables_quantity_arr_r );
				$consumables_price = implode(',', $consumables_price_arr_r );
				$consumables_vendor_price = implode(',', $consumables_vendor_price_arr_r );
				$consumables_discount_ = implode(',', $consumables_discount_arr_r );
				$consumables_total_ = implode(',', $consumables_total_arr_r );
				$consumables_batch_number = implode(',', $consumables_batch_number_arr_r );
				$consumables_hsn = implode(',', $consumables_hsn_arr_r );
				$consumables_gstrate = implode(',', $consumables_gstrate_arr_r );
				$consumables_mrp = implode(',', $consumables_mrp_arr_r );
				/*if ($consumables_name != '') {
				$consumables_name_sql2 = "SELECT item_name FROM hms_stocks WHERE item_number IN (" . $consumables_name . ")";
				$consumables_name_arr_r = [];
				$consumables_name_sql_qr = $this->db->query($consumables_name_sql2);
				$consumables_name_sql_qr_result = $consumables_name_sql_qr->result_array();
				if (!empty($consumables_name_sql_qr_result)) {
					foreach ($consumables_name_sql_qr_result as $key => $consumables_name_val2) {
						array_push($consumables_name_arr_r, $consumables_name_val2['item_name']);
					}
				}
				} */
				
				$final_consumables_arr_r =[];
				
				foreach($consumables_name_arr_r as $_key => $_value){
					$final_consumables123 = $_value;
					//array_push($final_consumables123);
					$final_consumables_arr[] = $final_consumables123;
					$response[] = array(
				        'on_date' => $val['on_date'],
                        'patient_id' => $val['patient_id'],
				        'patient_detail_name' => $val['patient_detail_name'],
						'hospital_id' => $val['hospital_id'],
						'receipt_number' => $val['receipt_number'],
                        'payment_method' => $val['payment_method'],
                        'consumables_name'=> $consumables_name_arr_r[$_key],
						'consumables_item_name'=> $consumables_item_name_arr_r[$_key],
						'consumables_stock'=> $consumables_stock_arr_r[$_key],
						'consumables_quantity' => $consumables_quantity_arr_r[$_key],
						'consumables_price' => $consumables_price_arr_r[$_key],
						'consumables_vendor_price' => $consumables_vendor_price_arr_r[$_key],
						'consumables_discount_' => $consumables_discount_arr_r[$_key],
						'consumables_total_' => $consumables_total_arr_r[$_key],
						'employee_number' => $val['employee_number'],
						'status' => $val['status'],
						'consumables_batch_number' => $consumables_batch_number_arr_r[$_key],
						'consumables_hsn' => $consumables_hsn_arr_r[$_key],
						'consumables_gstrate' => $consumables_gstrate_arr_r[$_key], 
						'consumables_mrp' => $consumables_mrp_arr_r[$_key]
                );
				}
			}
        }      
		return $response;
    }
	
	
		function patient_medicine_count($employee_number, $start_date, $end_date, $patient_id, $patient_detail_name){
		//$investigation_result = array();
		$conditions = '';
		
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($patient_detail_name)){
			$conditions .= " and patient_detail_name='$patient_detail_name'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

	    $medicine_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where 1 ".$conditions."";
		$q = $this->db->query($medicine_sql);
		return $q->num_rows();
		
	}
	
	function patient_medicine_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id, $consumables_name){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
        if (!empty($center)){
			$conditions .= " and employee_number='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($consumables_name)){
			$conditions .= " and consumables_name='$consumables_name'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	function get_generic(){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."stocks";
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
	
	function get_generic_data($item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."stocks where ID='".$item."'";
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
	
	public function update_generic_data($data, $item)
    {	
        $sql = "UPDATE " . config_item('db_prefix') . "stocks SET ";
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".$value."'"	;
		}
		$sql .= implode(',' , $sqlArr);
		$sql .= " WHERE ID = '".$item."'";
        $this->db->query($sql);
        return 1;
    }
	
	/**************	All Consuption **************/
	function export_consumption_medicine($start, $end, $employee_number, $type){
		$consuption_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and add_on between '".$start."' AND '".$end."' ";
        }
		$investigation_sql = "Select DISTINCT patient_id, employee_number, add_on, data, receipt_number from ".$this->config->item('db_prefix')."patient_items where 1 $conditions";
        $investigation_q = $this->db->query($investigation_sql);
        $consuption_result = $investigation_q->result_array();
        if(!empty($consuption_result)){
            foreach($consuption_result as $key => $val){
				$_data = unserialize($val['data']);
				$medicine_name_arr = [];
				$medicine_serial_arr = [];
				$medicine_stock_arr = [];
				$medicine_quantity_arr = [];
				foreach($_data as $value1){
					foreach($value1 as $value2){
						foreach($value2 as $value3){
							if($value3['medicine_name']){
								array_push($medicine_name_arr,$value3['medicine_name']);
							}
							array_push($medicine_serial_arr,$value3['medicine_serial']);
							array_push($medicine_quantity_arr,$value3['medicine_quantity']);
							array_push($medicine_stock_arr,$value3['medicine_price']);
						}
					}
				}
				//print_r($value3['medicine_name']);
				//echo '<br/>';
				//die();
				 $medicine_name = implode(',', $medicine_name_arr );
				 $medicine_serial = implode(',', $medicine_serial_arr );
				 $medicine_quantity = implode(',', $medicine_quantity_arr );
				 $medicine_price = implode(',', $medicine_stock_arr );
				if($medicine_name  != ''){
				 $medicine_name_sql = "SELECT item_name FROM hms_stocks WHERE item_number IN (".$medicine_name.") " ;
				}
				$_medicine_name_arr = [];
				$medicine_name_sql_q = $this->db->query($medicine_name_sql);
				$medicine_name_sql_q_result = $medicine_name_sql_q->result_array();
				if(!empty($medicine_name_sql_q_result)){
					foreach($medicine_name_sql_q_result as $key => $medicine_name_val){
						array_push($_medicine_name_arr,$medicine_name_val['item_name']);
					}
				}
				
				foreach($_medicine_name_arr as $_key => $_value){
					$final_medicine123 = $_value;
					$response[] = array(
				        'add_on' => $val['add_on'],
                        'patient_id' => $val['patient_id'],
						'employee_number' => $val['employee_number'],
				        'receipt_number' => $val['receipt_number'],
                        'final__medicine'=> $final_medicine123,
						'medicine_serial'=> $medicine_serial_arr[$_key],
						'medicine_quantity' => $medicine_quantity_arr[$_key],
						'medicine_price' => $medicine_stock_arr[$_key]
			    );
				}
			}
		}      
		return $response;
    }
	
	function export_consumption_injections($start_date, $end_date, $employee_number, $patient_id){
		$injections_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and add_on between '".$start."' AND '".$end."' ";
        }
		$injections_sql = "Select DISTINCT patient_id, add_on, data, receipt_number from ".$this->config->item('db_prefix')."patient_items where 1 $conditions";
        $injections_q = $this->db->query($injections_sql);
        $injections_result = $injections_q->result_array();
		
		/*if(!empty($vl['data'])){
		$data_arr = unserialize($vl['data']);
		if(!empty($data_arr['data']['injections'])){
			$injections_arr = $data_arr['data']['injections'];
		}
	    }*/
		
        if(!empty($injections_result)){
            foreach($injections_result as $key => $val){
				$_data = unserialize($val['data']);
				
				if(!empty($_data['data']['injections'])){
			    $injections_arr = $_data['data']['injections'];
		        }
				
				$injections_serial_arr = [];
				$injections_name_arr = [];
				$injections_item_name_arr = [];
				$injections_stock_arr = [];
				$injections_batch_number_arr = [];
				$injections_quantity_arr = [];
				$injections_price_arr = [];
				//if(!empty($_data['data']['injections'])){
				//foreach($_data as $value1){	
                if(!empty($injections_arr)){
		        foreach ($injections_arr as $value1 ){				
					foreach($value1 as $value2){
						foreach($value2 as $value3){
							if($value3['injections_name']){
								array_push($injections_name_arr,$value3['injections_name']);
							}
							array_push($injections_serial_arr,$value3['injections_serial']);
							array_push($injections_name_arr,$value3['injections_name']);
							array_push($injections_item_name_arr,$value3['injections_item_name']);
							array_push($injections_stock_arr,$value3['injections_stock']);
							array_push($injections_batch_number_arr,$value3['injections_batch_number']);
							array_push($injections_quantity_arr,$value3['injections_quantity']);
							array_push($injections_price_arr,$value3['injections_price']);
						}
					}
				}
				}
				print_r(!empty($_data['data']['injections']));
				//print_r($injections_name  != '');
				//echo '<br/>';
				//die();
				$injections_name = implode(',', $injections_name_arr );
				$injections_quantity = implode(',', $injections_quantity_arr );
				$injections_price = implode(',', $injections_stock_arr );
				/*if($injections_name  != ''){
				 $injections_name_sql = "SELECT item_name FROM hms_stocks WHERE item_number IN (".$injections_name.") " ;
				}*/
				
				$_injections_name_arr = [];
				$injections_name_sql_q = $this->db->query($injections_name_sql);
				$injections_name_sql_q_result = $injections_name_sql_q->result_array();
				if(!empty($injections_name_sql_q_result)){
					foreach($injections_name_sql_q_result as $key => $injections_name_val){
						array_push($_injections_name_arr,$injections_name_val['item_name']);
					}
				}
				
				foreach($_injections_name_arr as $_key => $_value){
					$final_injections123 = $_value;
					$response[] = array(
				        'add_on' => $val['add_on'],
                        'patient_id' => $val['patient_id'],
				        'receipt_number' => $val['receipt_number'],
                        'injections_name'=> $injections_name_arr[$_key],
						'injections_quantity' => $injections_quantity_arr[$_key],
						'injections_price' => $injections_stock_arr[$_key]
			    );
				}
			}
		}      
		return $response;
    }
	
	function export_consumption_consumables($start, $end, $employee_number, $type){
		$consuption_result = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and add_on between '".$start."' AND '".$end."' ";
        }
		 $consumables_sql = "Select DISTINCT patient_id, add_on, data, receipt_number from ".$this->config->item('db_prefix')."patient_items where 1 $conditions";
        $consumables_q = $this->db->query($consumables_sql);
        $consuption_result = $consumables_q->result_array();
        if(!empty($consuption_result)){
            foreach($consuption_result as $key => $val){
				$_data = unserialize($val['data']);
				$consumables_name_arr = [];
				$consumables_stock_arr = [];
				$consumables_quantity_arr = [];
				foreach($_data as $value1){
					foreach($value1 as $value2){
						foreach($value2 as $value3){
							if($value3['consumables_name']){
								array_push($consumables_name_arr,$value3['consumables_name']);
							}
							array_push($consumables_quantity_arr,$value3['consumables_quantity']);
							array_push($consumables_stock_arr,$value3['consumables_price']);
						}
					}
				}
				 $consumables_name = implode(',', $consumables_name_arr );
				 $consumables_quantity = implode(',', $consumables_quantity_arr );
				 $consumables_price = implode(',', $consumables_stock_arr );
				if($consumables_name  != ''){
				 $consumables_name_sql = "SELECT item_name FROM hms_stocks WHERE item_number IN (".$consumables_name.") " ;
				}
				$_consumables_name_arr = [];
				$consumables_name_sql_q = $this->db->query($consumables_name_sql);
				$consumables_name_sql_q_result = $consumables_name_sql_q->result_array();
				if(!empty($consumables_name_sql_q_result)){
					foreach($consumables_name_sql_q_result as $key => $consumables_name_val){
						array_push($_consumables_name_arr,$consumables_name_val['item_name']);
					}
				}
				
				foreach($_consumables_name_arr as $_key => $_value){
					$final_consumables123 = $_value;
					$response[] = array(
				        'add_on' => $val['add_on'],
                        'patient_id' => $val['patient_id'],
				        'receipt_number' => $val['receipt_number'],
                        'final__consumables'=> $final_consumables123,
						'consumables_quantity' => $consumables_quantity_arr[$_key],
						'consumables_price' => $consumables_stock_arr[$_key]
			    );
				}
			}
		}      
		return $response;
    }
	
	function patient_consuption_list_patination($limit, $page, $employee_number, $start_date, $end_date, $patient_id,$medicine_serial=null){
		$investigate_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($type)){
			$conditions .= " and type like '%$type%'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and date ='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and date ='$end_date'";
		}
	   // $consuption_sql = "Select * from ".$this->config->item('db_prefix')."consumptions where 1".$conditions." order by date desc limit ". $limit." OFFSET ".$offset."";
		$consuption_sql = "SELECT patient_id, patient_name,date, SUM(total_vendor_price) AS total_vendor_price FROM ".$this->config->item('db_prefix')."consumptions where 1".$conditions." GROUP BY patient_id ORDER BY date DESC limit ". $limit." OFFSET ".$offset."";
		$consuption_q = $this->db->query($consuption_sql);
		$investigate_result = $consuption_q->result_array();
		return $investigate_result;
	}
	
	function patient_consuption_medicine_count($employee_number, $start_date, $end_date, $patient_id, $type){
		$conditions = '';
		$investigate_result = array();
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($type)){
			$conditions .= " and type like '%$type%'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and date ='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and date ='$end_date'";
		}
		
		$investigation_sql = "SELECT patient_id, patient_name, date, SUM(total_vendor_price) AS total_vendor_price FROM ".$this->config->item('db_prefix')."consumptions where 1 ".$conditions." GROUP BY patient_id ORDER BY date DESC";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}
	
	function consumption_list_patination($limit, $page, $start_date, $end_date, $patient_id, $type, $procedure_name, $patient_name){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($type)){
			$conditions .= " and type like '%$type%'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and add_on ='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on ='$end_date'";
		}
		if (!empty($procedure_name)){
			$conditions .= " and procedure_name like '%$procedure_name%'";
		}
		if (!empty($patient_name)){
			$conditions .= " and patient_name like '%$patient_name%'";
		}
	    $consuption_sql = "SELECT * FROM ".$this->config->item('db_prefix')."consumptions where 1".$conditions." ORDER BY date DESC limit ". $limit." OFFSET ".$offset."";
		$consuption_q = $this->db->query($consuption_sql);
		$consuption_result = $consuption_q->result_array();
		return $consuption_result;
	}
	
	function consumption_list_count($start_date, $end_date, $patient_id,$type, $procedure_name, $patient_name){
		$conditions = '';
		if (!empty($type)){
			$conditions .= " and type like '%$type%'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and add_on ='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on ='$end_date'";
		}
		if (!empty($procedure_name)){
			$conditions .= " and procedure_name like '%$procedure_name%'";
		}
		if (!empty($patient_name)){
			$conditions .= " and patient_name like '%$patient_name%'";
		}
		$investigation_sql = "SELECT * FROM ".$this->config->item('db_prefix')."consumptions where 1 ".$conditions." ORDER BY date DESC";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
	}
	
	/**************	All Consuption Patient Wise **************/
	function patient_consuption_patination($limit, $page, $employee_number, $start_date, $end_date, $patient_id, $medicine_serial=null){
		$consumables_arr = $medicine_arr = $injections_arr = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($medicine_serial)){
			$conditions .= " and data like '%$medicine_serial%'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and add_on ='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on ='$end_date'";
		}
	    $consuption_sql = "Select * from ".$this->config->item('db_prefix')."patient_items where 1".$conditions." order by add_on desc limit ". $limit." OFFSET ".$offset."";
		$consuption_q = $this->db->query($consuption_sql);
		$consuption_result = $consuption_q->result_array();
		if (!empty($consuption_result))
        {
			$consuption_result = $consuption_result[0];
			$data = unserialize($consuption_result['data']);
			$data = $data['data'];
			if (!empty($data['consumables']))
       		{
				foreach($data['consumables'] as $ky => $vl){
					$consumable_result[] = $vl;
				}
			}
			if (!empty($data['injections']))
       		{
				foreach($data['injections'] as $ky => $vl){
					$injection_result[] = $vl;
				}
			}
			if (!empty($data['medicine']))
       		{
				foreach($data['medicine'] as $ky => $vl){
					$medicine_result[] = $vl;
				}
			}
			
			$response = array();
			$response = array('consumable_result' => $consumable_result, 'injection_result' => $injection_result, 'medicine_result' => $medicine_result);
			return $response;
        }
		
		//return $consuption_result;
	}
	
	function patient_consuption_count($employee_number, $start_date, $end_date, $patient_id, $medicine_serial=null){
		$consumables_arr = $medicine_arr = $injections_arr = array();
		$conditions = '';
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($medicine_serial)){
			$conditions .= " and data like '%$medicine_serial%'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and add_on ='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and add_on ='$end_date'";
		}
		$patient_consuption_sql = "Select * from ".$this->config->item('db_prefix')."patient_items where 1 ".$conditions."";
		$q = $this->db->query($patient_consuption_sql);
		return $q->num_rows();
	}
	
	function get_centers(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."centers where status='1' ORDER by ID DESC";
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
	
	
	function get_center_name($center){
		$result = array();
		$sql = "Select center_name from ".$this->config->item('db_prefix')."centers where center_number='".$center."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['center_name'];
        }
        else
        {
            return $result;
        }
	}
	
	function get_employee_name($employee_number){
		$result = array();
		$sql_condition = '';
		$sql = "Select name from ".$this->config->item('db_prefix')."employees where employee_number='".$employee_number."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['name'];
        }
        else
        {
            return $result;
        }
	}
	
	
	/***************Medicine Stock Account panel***************/
	
	function export_investigation_data($start, $end, $center, $type){

		$investigation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
		
	    $investigation_sql = "Select DISTINCT patient_id, receipt_number, totalpackage, fees as discounted_package,payment_done,remaining_amount,investigations,payment_method,billing_from,billing_at,on_date as date,status from ".$this->config->item('db_prefix')."patient_medicine where 1 $conditions order by on_date desc";
        $investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				
				$hms_procedures_result = unserialize( $val['investigations']);
				$investigation_nameArr = [];
				foreach ($hms_procedures_result as $v2_data123){
						foreach ($v2_data123 as $v2_data5){

						    $sql12 = "select investigation from hms_investigation where code='".$v2_data5['female_investigation_code']."'"; 
                            $query12 = $this->db->query($sql12);
                            $select_result1 = $query12->result(); 
							
							//print_r($select_result1);
							//echo select_result1['investigation'];
							
							foreach ($select_result1 as $res_val){
								// echo $res_val->investigation;
								 array_push($investigation_nameArr,$res_val->investigation);
							}
						}
						
				}
				//die;
					 
				$investigation_name1 = implode(',',$investigation_nameArr); 
				
				$patient_name = $this->get_patient_name($val['patient_id']);
				$patient_name1 = strtoupper($patient_name);
                $response[] = array(
                        'patient_id' => $val['patient_id'],
                        'wife_name' => $patient_name1,
						'receipt_number' => $val['receipt_number'],
						 'totalpackage' => $val['totalpackage'],
                        'discounted_package' => $val['discounted_package'],
                        'payment_done' => $val['payment_done'],
                        'remaining_amount' => $val['remaining_amount'],
                        'payment_method' => $val['payment_method'],
                        'billing_from' => $val['billing_from'],
                        'billing_at' => $val['billing_at'],
						'investigation' => $investigation_name1,
                        'date' => $val['date'],
                        'status' => $val['status'],
                        'billing_type' => 'Investigation',
                );
            }
        }    
		return $response;
    }

	function patient_investigation_count($center, $start_date, $end_date, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}
	
	
		function patient_investigation_list_patination($limit, $page, $center, $start_date, $end_date, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	/**********Medicine Center Order List********/
	
	function medicine_center_order_item($center, $start_date, $end_date, $item_name){
		$investigation_result = array();
		$conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name='$item_name'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_order_medicine where 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}
	
	function medicine_center_order_patination($limit, $page, $center, $start_date, $end_date, $item_name){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}

		if (!empty($center)){
			$conditions .= " and billing_at='$center'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name='$item_name'";
		}
		if (!empty($start_date) && !empty($end_date)){
			//$conditions .= " and on_date >='$start_date' and  on_date <= '$end_date'";
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
			
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_order_medicine where 1".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}

    function get_patient_name_2($patient_id){
		
		$result = array();
		$sql = "Select wife_name from ".$this->config->item('db_prefix')."patients where patient_id='".$patient_id."'";
        $q = $this->db->query($sql);
        $result = $q->result_array();
        if (!empty($result))
        {
            return $result[0]['wife_name'];
        }
        else
        {
            return $result;
        }
	}
	
	function return_center_item_data($data){
	 $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "return_stocks` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
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
	
	function center_order_medicine($data){
	 $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "center_order_medicine` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
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
	
	/*function get_return_order(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."return_stocks";
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
	
	function add_item_data($employee_number, $item){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."return_stocks where item_number='".$item."'";
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
	
	function update_discard_data($item_number, $employee_number, $item_qty, $item_id){
		$sql = "UPDATE ".$this->config->item('db_prefix')."center_stocks SET `quantity`= `quantity`-$item_qty, `update_date`='".date("Y-m-d H:i:s")."' where item_number='".$item_id."' AND employee_number='".$employee_number."' AND status=1";
        $this->db->query($sql);
		
	    $sql2 = "UPDATE ".$this->config->item('db_prefix')."return_stocks SET `quantity`= `quantity`-$item_qty where item_number='".$item_id."' AND employee_number='".$employee_number."'";
       $this->db->query($sql2);
		
    }

    function update_discard_central_data($item_number, $ID, $item_qty, $item_id){
	 $sql = "UPDATE ".$this->config->item('db_prefix')."stocks SET `quantity`= `quantity`-$item_qty where item_number='".$item_id."' AND ID='".$ID."' AND status=1";
        $this->db->query($sql);
		
    }
	
	function add_discard_item($data){
	 $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "product_discard` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
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

    function vendor_return_data($item_number, $employee_number, $item_qty, $item_id){
		$sql_query = "UPDATE ".$this->config->item('db_prefix')."center_stocks SET `quantity`= `quantity`-$item_qty, `update_date`='".date("Y-m-d H:i:s")."' where item_number='".$item_id."' AND employee_number='".$employee_number."' AND status=1";
        $this->db->query($sql_query);
		
	    $sql_query2 = "UPDATE ".$this->config->item('db_prefix')."return_stocks SET `quantity`= `quantity`-$item_qty where item_number='".$item_id."' AND employee_number='".$employee_number."'";
        $this->db->query($sql_query2);
	}	

    function vendor_return_central_data($item_number, $ID, $item_qty, $item_id){
	    $sql_query = "UPDATE ".$this->config->item('db_prefix')."stocks SET `quantity`= `quantity`-$item_qty where item_number='".$item_id."' AND ID='".$ID."' AND status=1";
        $this->db->query($sql_query);
		
	}	
	
	function add_vendor_item($data){
	    $sql = "INSERT INTO `" . $this->config->item('db_prefix') . "vendor_return` SET ";
		$sqlArr = array();
		foreach( $data as $key=> $value )
		{
			$sqlArr[] = " $key = '".addslashes($value)."'"	;
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
	
	/*function get_discard_product(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."product_discard";
        $que = $this->db->query($sql);
        $result = $que->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}*/
	
	/*************Return Medicine*************/
	
	/*function get_vendor_return(){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."vendor_return";
        $sql_q = $this->db->query($sql);
        $result = $sql_q->result_array();
        if (!empty($result))
        {
            return $result;
        }
        else
        {
            return $result;
        }
	}*/
	
	public function update_return_item_data($ID, $item_number, $qty)
    {	
		if($_SESSION['logged_stock_manager']['employee_number']){
	    $sql = "UPDATE `".$this->config->item('db_prefix')."center_stocks` SET `quantity` = `quantity`+".$qty." WHERE `item_number`='".$item_number."' AND `ID`='".$ID."'";
        }else{
		$sql = "UPDATE `".$this->config->item('db_prefix')."center_stocks` SET `quantity` = `quantity`+".$qty." WHERE `item_number`='".$item_number."' AND `ID`='".$ID."'";
        }	
		$this->db->query($sql);
        return 1;
    }
	
	function get_return_medicine_data($receipt){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where receipt_number='$receipt' and stutus_type='1' order by on_date asc";
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
	
	function export_return_medicine_center_data($start, $end, $patient_id, $type){

		$investigation_result = $response = array();
        $conditions = '';
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['stock_manager']['center'])){ 
			$center = $_SESSION['logged_accountant']['center'];
		}
        if(!empty($center)){
			$conditions .= ' and billing_at="'.$center.'"';
        }
		if(!empty($start) && !empty($end)){
            $conditions .= " and on_date between '".$start."' AND '".$end."' ";
        }
		if($_SESSION['logged_stock_manager']['employee_number']){
	    $investigation_sql = "Select DISTINCT patient_id, patient_detail_name, on_date,return_medicine, receipt_number, hospital_id, payment_method,status from ".$this->config->item('db_prefix')."patient_medicine where 1 $conditions AND employee_number='".$_SESSION['logged_stock_manager']['employee_number']."' and status='Return' order by on_date desc";
        }else{
		$investigation_sql = "Select DISTINCT patient_id, patient_detail_name, on_date,return_medicine, receipt_number, hospital_id, payment_method,status from ".$this->config->item('db_prefix')."patient_medicine where 1 $conditions AND employee_number='".$_SESSION['logged_billing_manager']['employee_number']."' and status='Return' order by on_date desc";
       	}
		$investigation_q = $this->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
        if(!empty($investigation_result)){
            foreach($investigation_result as $key => $val){
				$_data = unserialize($val['return_medicine']);
				$consumables_serial_arr = [];
				$consumables_name_arr = [];
				$consumables_stock_arr = [];
				$consumables_quantity_arr = [];
				//echo '<pre>';
				//print_r($_data);
                 //die;
				//echo '<br>';
				foreach($_data as $value1){
				//	print_r($value1);
					foreach($value1 as $value2){
						//print_r($value2);
						foreach($value2 as $value3){
							//print_r($value3);
							if($value3['consumables_name']){
								array_push($consumables_name_arr,$value3['consumables_name']);
							}
							array_push($consumables_serial_arr,$value3['consumables_serial']);
							array_push($consumables_stock_arr,$value3['consumables_total_']);
							array_push($consumables_quantity_arr,$value3['consumables_quantity']);
						}
					}
				}
				//print_r($consumables_name_arr);
				 $consumables_serial = implode(',', $consumables_serial_arr );
				 $consumables_name = implode(',', $consumables_name_arr );
				 $consumables_total_ = implode(',', $consumables_stock_arr );
				 $consumables_quantity = implode(',', $consumables_quantity_arr );
				if($consumables_name  != ''){
				 $consumables_name_sql = "SELECT item_name FROM hms_stocks WHERE item_number IN (".$consumables_name.")" ;
				}
				//die();
				$_consumables_name_arr = [];
				$consumables_name_sql_q = $this->db->query($consumables_name_sql);
				$consumables_name_sql_q_result = $consumables_name_sql_q->result_array();
				if(!empty($consumables_name_sql_q_result)){
					foreach($consumables_name_sql_q_result as $key => $consumables_name_val){
						array_push($_consumables_name_arr,$consumables_name_val['item_name']);
					}
				}
				
				$final_consumables_arr =[];
				
				foreach($_consumables_name_arr as $_key => $_value){
					
					$final_consumables123 = $_value;
					array_push($final_consumables_arr,$final_consumables123 );
					$response[] = array(
				        'on_date' => $val['on_date'],
                        'patient_id' => $val['patient_id'],
				        'patient_detail_name' => $val['patient_detail_name'],
						'hospital_id' => $val['hospital_id'],
                        'receipt_number' => $val['receipt_number'],
                        'payment_method' => $val['payment_method'],
						'consumables_serial'=> $consumables_serial_arr[$_key],
                        'final__consumables'=> $final_consumables123,
                        'consumables_quantity' => $consumables_quantity_arr[$_key],
						'consumables_total_' => $consumables_stock_arr[$_key],
						'status' => $val['status']
                );
					
				}
				
				$_consumables_name = implode(',', $_consumables_name_arr );
				$final__consumables = implode(",\n", $final_consumables_arr );
			}
        }      
		return $response;
    }
	
	function patient_return_medcine($employee_number, $start_date, $end_date, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}

	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where stutus_type='1' and 1 ".$conditions."";
		$q = $this->db->query($investigation_sql);
		return $q->num_rows();
		
	}
	
	function patient_investigation_list_patination2($limit, $page, $center, $start_date, $end_date, $patient_id){
		$investigation_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if(isset($_SESSION['logged_accountant']['center']) && !empty($_SESSION['logged_accountant']['center'])){ 
			$conditions = ' and billing_at="'.$_SESSION['logged_accountant']['center'].'"'; 
		}
		if (!empty($center)){
			$conditions .= " and employee_number='$center'";
		}
		if (!empty($patient_id)){
			$conditions .= " and patient_id='$patient_id'";
		}
		if (!empty($consumables_name)){
			$conditions .= " and consumables_name='$consumables_name'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."patient_medicine where stutus_type='1' and 1 ".$conditions." order by on_date desc limit ". $limit." OFFSET ".$offset."";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	public function invoice_item($data, $invoice_no, $no_of_item, $Total_amount, $invoice_date, $add_date)
    
	{ 
	 $sql = "INSERT INTO hms_vendor_invoice (`invoice_no`,`no_of_item`,`Total_amount`,`invoice_date`,`add_date`) VALUES ('$invoice_no','$no_of_item','$Total_amount','".date("Y-m-d")."','".date("Y-m-d H:i:s")."')";
	$res =  $this->db->query($sql);	
	return 1;
	}
	
	function vendor_medicine_invoice($invoice_no){
		$invoice_result = array();
		$conditions = '';
		if (!empty($invoice_no)){
			$conditions .= " and invoice_no='$invoice_no'";
		}
		$invoice_sql = "Select * from ".$this->config->item('db_prefix')."vendor_invoice where 1 ".$conditions."";
		$q = $this->db->query($invoice_sql);
		return $q->num_rows();
		
	}
	
	function vendor_medicine_invoice_patination($limit, $page, $invoice_no){
		$invoice_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($invoice_no)){
			$conditions .= " and invoice_no='$invoice_no'";
		}
		$invoice_sql = "Select * from ".$this->config->item('db_prefix')."vendor_invoice where 1 ".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$invoice_q = $this->db->query($invoice_sql);
		$invoice_result = $invoice_q->result_array();
		return $invoice_result;
	}
	
	/*function approve_new_medicine($ID){
		$sql = "UPDATE `".$this->config->item('db_prefix')."medicines` SET `status`='1',`modified_on`='".date("Y-m-d H:i:s")."' WHERE `ID`='".$ID."'";
        $this->db->query($sql);
        return 1;
	}*/

	public function approve_new_medicine($id) {
		$this->db->where('ID', $id);
		$this->db->update('hms_medicines', ['status' => 1, 'modified_on' => date("Y-m-d H:i:s")]);
		echo json_encode(['status' => 'success', 'message' => 'Approved successfully']);
	}
	
	
	function disapprove_new_medicine($ID){
		$sql = "UPDATE `".$this->config->item('db_prefix')."medicines` SET `status`='2',`modified_on`='".date("Y-m-d H:i:s")."' WHERE `ID`='".$ID."'";
        $this->db->query($sql);
        return 1;
	}
	
	function inactive_medicine($ID){
		$sql = "UPDATE `".$this->config->item('db_prefix')."medicines` SET `status`='3',`modified_on`='".date("Y-m-d H:i:s")."' WHERE `ID`='".$ID."'";
        $this->db->query($sql);
        return 1;
	}

	function transfer_stock_count($item_name, $batch_number, $start_date, $end_date, $center_number){
		$invoice_result = array();
		$conditions = '';
		if (!empty($item_name)){
			$conditions .= " and item_name='$item_name'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		if (!empty($center_number)){
			$conditions .= " and center_number='$center_number'";
		}
		$invoice_sql = "Select * from ".$this->config->item('db_prefix')."transfer_stocks where 1 ".$conditions."";
		$q = $this->db->query($invoice_sql);
		return $q->num_rows();
		
	}
	
	function transfer_stock_patination($limit, $page, $item_name, $batch_number, $start_date, $end_date, $center_number){
		$invoice_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($item_name)){
			$conditions .= " and item_name='$item_name'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		if (!empty($center_number)){
			$conditions .= " and center_number='$center_number'";
		}
		$invoice_sql = "Select * from ".$this->config->item('db_prefix')."transfer_stocks where 1 ".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$invoice_q = $this->db->query($invoice_sql);
		$invoice_result = $invoice_q->result_array();
		return $invoice_result;
	}

	function get_transfer_data($ID){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."transfer_stocks where ID='".$ID."'";
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

	function check_center_item_new2($item_number, $invoice_no, $batch_number, $center_number,  $employee_number, $department, $status){
	   $result = array();
		//var_dump($sql);die;
	   echo $sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where item_number='".$item_number."' and invoice_no='".$invoice_no."' and batch_number='".$batch_number."' and center_number='".$center_number."' and employee_number='".$employee_number."'and department='".$department."' and status='1' limit 1";
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

	public function update_transfer_item($data, $quantity, $invoice_no, $center_number, $item_number, $employee_number, $department, $item_name, $company, $batch_number, $openstock, $expiry, $expiry_day, $add_date, $vendor_price, $mrp, $hsn, $gstrate, $gstdivision, $quantity_out, $date_of_purchase)
    {
		//var_dump($data);die;
		$sql = "UPDATE hms_center_stocks SET  `quantity` = quantity+".$quantity." where `item_number` = '$item_number' and `employee_number` = '$employee_number' and `center_number` = '$center_number' and `department` = '$department' and `status` = '1' ";
		$this->db->query($sql);
        //return $this->db->affected_rows();

		//$closingstock = $openstock - $quantity_out;
		$sql = "INSERT INTO `".$this->config->item('db_prefix')."central_stock_report` (`item_number`,`invoice_no`,`item_name`,`company`,`batch_number`,`openstock`,`expiry`,`expiry_day`,`add_date`,`employee_number`,`vendor_price`,`mrp`,`hsn`,`gstrate`,`gstdivision`,`quantity_in`,`enddate`,`closingstock`,`type`,`center_number`,`date_of_purchase`) VALUES ('$item_number','$invoice_no','$item_name','$company','$batch_number','$openstock','$expiry','$expiry_day','".date("Y-m-d H:i:s")."','$employee_number','$vendor_price','$mrp','$hsn','$gstrate','$gstdivision','$quantity','".date("Y-m-d H:i:s")."','$quantity','Center In','$center_number','$date_of_purchase')";
		$res =  $this->db->query($sql);						
        return 1;
    }

	function center_audit_patination(){
		if($_SESSION['logged_stock_manager']['center']){
	    $investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_stock_manager']['center']."' AND department='".$_SESSION['logged_stock_manager']['department']."' AND status='1' and quantity > 0 order by expiry_day desc";
		}else{
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where center_number='".$_SESSION['logged_billing_manager']['center']."' AND department='".$_SESSION['logged_billing_manager']['department']."' AND status='1' and quantity > 0 order by expiry_day desc";
		}	
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	function get_all_center_stocks_audit($employee_number, $item_name, $batch_number, $item_number){
		$investigate_result = array();
		$conditions = '';
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number='$item_number'";
		}
		$investigation_sql = "Select * from ".$this->config->item('db_prefix')."center_stocks where 1 ".$conditions." and status='1' and quantity > 0 order by expiry_day desc";
		$investigation_q = $this->db->query($investigation_sql);
		$investigation_result = $investigation_q->result_array();
		return $investigation_result;
	}
	
	function return_stock_count($item_name, $batch_number, $start_date, $end_date, $employee_number){
		$invoice_result = array();
		$conditions = '';
		if (!empty($item_name)){
			$conditions .= " and item_name='$item_name'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		$invoice_sql = "Select * from ".$this->config->item('db_prefix')."return_stocks where 1 ".$conditions."";
		$q = $this->db->query($invoice_sql);
		return $q->num_rows();
		
	}
	
	function return_stock_patination($limit, $page, $item_name, $batch_number, $start_date, $end_date, $employee_number){
		$invoice_result = array();
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($item_name)){
			$conditions .= " and item_name='$item_name'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and on_date='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and on_date='$end_date'";
		}
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		$invoice_sql = "Select * from ".$this->config->item('db_prefix')."return_stocks where 1 ".$conditions." order by ID desc limit ". $limit." OFFSET ".$offset."";
		$invoice_q = $this->db->query($invoice_sql);
		$invoice_result = $invoice_q->result_array();
		return $invoice_result;
	}
	
	function export_vendor_return($employee_number, $start, $end, $generic_name, $item_name){
		$vendor_return = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
        if(!empty($start) && !empty($end)){
            $conditions .= " and expiry_day between '".$start."' AND '".$end."' ";
        }
		if(!empty($generic_name)){
			$conditions .= ' and generic_name="'.$generic_name.'"';
        }
		 if(!empty($item_name)){
			$conditions .= ' and item_name="'.$item_name.'"';
        }
		$vendor_return_sql = "Select DISTINCT item_number, company,employee_number, item_name,batch_number,quantity,center_number,expiry,status from ".$this->config->item('db_prefix')."vendor_return where 1 $conditions and status='1' order by rand() desc ";
        $vendor_return_q = $this->db->query($vendor_return_sql);
        $vendor_return = $vendor_return_q->result_array();
		if(!empty($vendor_return)){
            foreach($vendor_return as $key => $val){
				$response[] = array(
                        'item_number' => $val['item_number'],
				        'company' => $val['company'],
                        'item_name' => $val['item_name'],
                        'batch_number' => $val['batch_number'],
                        'brand_name' => $val['brand_name'],
                        'vendor_number' => $val['vendor_number'],
                        'generic_name' => $val['generic_name'],
						'hsn' => $val['hsn'],
						'gstrate' => $val['gstrate'],
						'pack_size' => $val['pack_size'],
                        'quantity' => $val['quantity'],
						'vendor_price' => $val['vendor_price'],
						'mrp' => $val['mrp'],
                        'category' => $val['category'],
						'expiry' => $val['expiry'],
                        'status' => $val['status'],
						'employee_number' => $val['employee_number'],
						'center_number' => $val['center_number'],
						'department' => $val['department'],
						'date_of_purchase' => $val['date_of_purchase'],
                );
            }
        } 
    	return $response;
    }
	
	function get_vendor_return($employee_number, $start_date, $end_date, $generic_name, $item_name,$batch_number, $item_number){
		$conditions = '';
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number='$item_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry='$end_date'";
		}
	    $vendor_return_sql = "Select * from ".$this->config->item('db_prefix')."vendor_return where 1 ".$conditions."";
		$q = $this->db->query($vendor_return_sql);
		return $q->num_rows();
	}
	
	function get_vendor_return_patination($limit, $page, $employee_number, $start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number='$item_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry='$end_date'";
		}
		$vendor_return_sql = "Select * from ".$this->config->item('db_prefix')."vendor_return where 1".$conditions." order by expiry_day desc limit ". $limit." OFFSET ".$offset."";
		$vendor_return_q = $this->db->query($vendor_return_sql);
		$vendor_return = $vendor_return_q->result_array();
		return $vendor_return;
	}
	
	/*****Product Discard List*****/
	
	function export_product_discard($employee_number, $start, $end, $generic_name, $item_name){
		$product_discard = $response = array();
        $conditions = '';
		if(!empty($employee_number)){
			$conditions .= ' and employee_number="'.$employee_number.'"';
        }
        if(!empty($start) && !empty($end)){
            $conditions .= " and expiry_day between '".$start."' AND '".$end."' ";
        }
		if(!empty($generic_name)){
			$conditions .= ' and generic_name="'.$generic_name.'"';
        }
		 if(!empty($item_name)){
			$conditions .= ' and item_name="'.$item_name.'"';
        }
		$product_discard_sql = "Select DISTINCT item_number, company,employee_number, item_name,batch_number,quantity,center_number,expiry,status from ".$this->config->item('db_prefix')."product_discard where 1 $conditions and status='1' order by rand() desc ";
        $product_discard_q = $this->db->query($product_discard_sql);
        $product_discard = $product_discard_q->result_array();
		if(!empty($product_discard)){
            foreach($product_discard as $key => $val){
				$response[] = array(
                        'item_number' => $val['item_number'],
				        'company' => $val['company'],
                        'item_name' => $val['item_name'],
                        'batch_number' => $val['batch_number'],
                        'brand_name' => $val['brand_name'],
                        'vendor_number' => $val['vendor_number'],
                        'generic_name' => $val['generic_name'],
						'hsn' => $val['hsn'],
						'gstrate' => $val['gstrate'],
						'pack_size' => $val['pack_size'],
                        'quantity' => $val['quantity'],
						'vendor_price' => $val['vendor_price'],
						'mrp' => $val['mrp'],
                        'category' => $val['category'],
						'expiry' => $val['expiry'],
                        'status' => $val['status'],
						'employee_number' => $val['employee_number'],
						'center_number' => $val['center_number'],
						'department' => $val['department'],
						'date_of_purchase' => $val['date_of_purchase'],
                );
            }
        } 
    	return $response;
    }
	
	function get_product_discard($employee_number, $start_date, $end_date, $generic_name, $item_name,$batch_number, $item_number){
		$conditions = '';
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number='$item_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry='$end_date'";
		}
	    $vendor_return_sql = "Select * from ".$this->config->item('db_prefix')."product_discard where 1 ".$conditions."";
		$q = $this->db->query($vendor_return_sql);
		return $q->num_rows();
	}
	
	function get_product_discard_patination($limit, $page, $employee_number, $start_date, $end_date, $generic_name, $item_name, $batch_number, $item_number){
		$conditions = '';
		if(empty($page)){
			$offset = 0;
		}else{
			$offset = ($page - 1) * $limit;
		}
		if (!empty($employee_number)){
			$conditions .= " and employee_number='$employee_number'";
		}
		if (!empty($item_name)){
			$conditions .= " and item_name like '%$item_name%'";
		}
		if (!empty($batch_number)){
			$conditions .= " and batch_number='$batch_number'";
		}
		if (!empty($item_number)){
			$conditions .= " and item_number='$item_number'";
		}
		if (!empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry between '".$start_date."' AND '".$end_date."' ";
		}
		else if (!empty($start_date) && empty($end_date)){
			$conditions .= " and expiry='$start_date'";
		}
		else if (empty($start_date) && !empty($end_date)){
			$conditions .= " and expiry='$end_date'";
		}
		$product_discard_sql = "Select * from ".$this->config->item('db_prefix')."product_discard where 1".$conditions." order by expiry_day desc limit ". $limit." OFFSET ".$offset."";
		$product_discard_q = $this->db->query($product_discard_sql);
		$product_discard = $product_discard_q->result_array();
		return $product_discard;
	}
	
	function get_central_all_stocks(){
		$result = array();
		$sql_condition = '';
		$sql = "Select * from ".$this->config->item('db_prefix')."stocks where status='1'";
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
	function get_order_data($po_number){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."orders where po_number='$po_number'";
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
	
	function get_internal_order_data($po_number){
		$result = array();
		$sql_condition = '';
	    $sql = "Select * from ".$this->config->item('db_prefix')."internal_orders where po_number='$po_number'";
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
	
	function approve_transfer_stocks($ID){
		$sql = "UPDATE `".$this->config->item('db_prefix')."transfer_stocks` SET `status`='1' WHERE `ID`='".$ID."'";
        $this->db->query($sql);
        return 1;
	}
	
	function disapprove_transfer_stocks($ID){
		$sql = "UPDATE `".$this->config->item('db_prefix')."transfer_stocks` SET `status`='2' WHERE `ID`='".$ID."'";
        $this->db->query($sql);
        return 1;
	}
	
	// Get transfer history for a specific item
	function get_transfer_history_by_item($item_number) {
		$sql = "SELECT * FROM `".$this->config->item('db_prefix')."transfer_stocks` 
				WHERE `item_number` = '".$item_number."' 
				ORDER BY `add_date` DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}