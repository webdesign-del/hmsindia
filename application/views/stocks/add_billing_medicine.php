<?php 
$appointment_id = null;

if (isset($_GET['appointment_id'])) {
    $appointment_id = intval($_GET['appointment_id']); // Safe cast
} elseif (isset($_GET['ID'])) {
    $ID = intval($_GET['ID']); // Safe cast

    $sql_doctor_consultation_id = "SELECT * FROM `hms_doctor_consultation` WHERE ID = $ID";
    $select_result_consultation_id = run_select_query($sql_doctor_consultation_id);

    if (!empty($select_result_consultation_id['appointment_id'])) {
        $appointment_id = $select_result_consultation_id['appointment_id'];
    } else {
        echo "No appointment found for ID = $ID";
        exit;
    }
} else {
    echo "Missing required parameter: appointment_id or ID.";
    exit;
}

// Continue with consultation lookup
$sql_doctor_consultation = "SELECT * FROM `hms_doctor_consultation` WHERE appointment_id=".$appointment_id."";
$select_result_consultation = run_select_query($sql_doctor_consultation);

$centers_sql = "SELECT * FROM `hms_centers` WHERE center_number=".$_SESSION['logged_billing_manager']['center']."";
$centers_result = run_select_query($centers_sql);

	    $ci = &get_instance();
		$ci->load->database();
		$db_prefix = $ci->config->config['db_prefix'];
		$patient_sql = "Select * from ".$db_prefix."patients where  patient_id='".$select_result_consultation['patient_id']."'";
        $patient_q = $ci->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		$patient_id = $patient_result[0]['patient_id'];

		$consultation_result = $procedure_result = $investigation_result = $medicine_result = $registation_result = $remaining_billing = $bill_arr = $bill_total = array();
		$procedure_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_procedure where status='cancel' and patient_id='".$select_result_consultation['patient_id']."'";
        $procedure_q = $ci->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();

		$consultation_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."consultation where status='adjust' and patient_id='".$select_result_consultation['patient_id']."'";
        $consultation_q = $ci->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
		
		$registation_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."registation where status='adjust' and patient_id='".$select_result_consultation['patient_id']."'";
        $registation_q = $ci->db->query($registation_sql);
        $registation_result = $registation_q->result_array();

		$investigation_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_investigations where status='cancel' and patient_id='".$select_result_consultation['patient_id']."'";
        $investigation_q = $ci->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
		
		$medicine_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_at from ".$db_prefix."patient_medicine where status='cancel' and patient_id='".$select_result_consultation['patient_id']."'";
        $medicine_q = $ci->db->query($medicine_sql);
        $medicine_result = $medicine_q->result_array();
		
		$total = 0;
        $done_sql = "Select sum(payment_done) as payment_done from ".$db_prefix."patient_payments where patient_id='".$select_result_consultation['patient_id']."' AND status='3'";
		$done_q = $ci->db->query($done_sql);
		$done_result = $done_q->result_array();

    	foreach($consultation_result as $key => $val){
		    $bill_arr[] = $val['payment_done'];
		}

		$total = 0;
		foreach($investigation_result as $key => $val){
		    $bill_arr[] = $val['payment_done'];
		}
		
		$total = 0;
		foreach($registation_result as $key => $val){
		    $bill_arr[] = $val['payment_done'];
		}

		$total = 0;
		foreach($procedure_result as $key => $val){
			$bill_arr[] = $val['payment_done'];
		}
		
		$total = 0;
		foreach($medicine_result as $key => $val){
			$bill_arr[] = $val['payment_done'];
		}
		
		foreach($done_result as $key => $val){
			$bill_arr[] = $val['payment_done'];
		}

        //wallete
		$consultation_wallet_result = $procedure_wallet_result = $registation_wallet_result = $investigation_wallet_result = $partialpayments_wallet_result = $medicine_wallet_result = $done_wallet_result = $wallet_remaining_billing = $wallet_arr = $wallet_bill_total = array();
		$procedure_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_procedure where wallet_payment > 0 and patient_id='".$select_result_consultation['patient_id']."'";
        $procedure_wallet_q = $ci->db->query($procedure_wallet_sql);
        $procedure_wallet_result = $procedure_wallet_q->result_array();

		$consultation_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."consultation where wallet_payment > 0 and patient_id='".$select_result_consultation['patient_id']."'";
        $consultation_wallet_q = $ci->db->query($consultation_wallet_sql);
        $consultation_wallet_result = $consultation_wallet_q->result_array();
		
		$investigation_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_investigations where wallet_payment > 0 and patient_id='".$select_result_consultation['patient_id']."'";
        $investigation_wallet_q = $ci->db->query($investigation_wallet_sql);
        $investigation_wallet_result = $investigation_wallet_q->result_array();
		
		$medicine_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_at from ".$db_prefix."patient_medicine where wallet_payment > 0 and patient_id='".$select_result_consultation['patient_id']."'";
        $medicine_wallet_q = $ci->db->query($medicine_wallet_sql);
        $medicine_wallet_result = $medicine_wallet_q->result_array();

        $partialpayments_wallet_sql = "Select refrence_number, payment_done, wallet_payment, billing_from, billing_at from ".$db_prefix."patient_payments where wallet_payment > 0 and patient_id='".$select_result_consultation['patient_id']."'";
        $partialpayments_wallet_q = $ci->db->query($partialpayments_wallet_sql);
        $partialpayments_wallet_result = $partialpayments_wallet_q->result_array();

        $refund_amount_sql = "Select patient_id, package_name, package_code, consultation_fee, usg_scan_charge, consumable_charges, file_registation_charge, refund_amount, on_date, status from ".$db_prefix."refund_amount where patient_id='".$select_result_consultation['patient_id']."'";
        $refund_amount_q = $ci->db->query($refund_amount_sql);
        $refund_amount_result = $refund_amount_q->result_array();

		foreach($consultation_wallet_result as $key => $value){
			$wallet_arr[] = $value['wallet_payment'];
		}

		$total = 0;
		foreach($investigation_wallet_result as $key => $value){
			$wallet_arr[] = $value['wallet_payment'];
		}

		$total = 0;
		foreach($procedure_wallet_result as $key => $value){
			$wallet_arr[] = $value['wallet_payment'];
		}
		
		$total = 0;
		foreach($medicine_wallet_result as $key => $value){
			$wallet_arr[] = $value['wallet_payment'];
		}

        $total = 0;
		foreach($partialpayments_wallet_result as $key => $value){
			$wallet_arr[] = $value['wallet_payment'];
		}
		
		if(is_array($done_wallet_result) && !empty($done_wallet_result)){
			foreach($done_wallet_result as $key => $value){
				$wallet_arr[] = $value['wallet_payment'];
			}
		}

        foreach($refund_amount_result as $key => $value){
			$refund_amount_arr[] = $value['consultation_fee'];
            $refund_amount_arr[] = $value['usg_scan_charge'];
            $refund_amount_arr[] = $value['consumable_charges'];
            $refund_amount_arr[] = $value['file_registation_charge'];
            $refund_amount_arr[] = $value['refund_amount'];
		}
		
		$paid_total = 0;
		$paid_total = array_sum($bill_arr);

        $wallet_bill_total = 0;
		$wallet_bill_total = array_sum($wallet_arr);

        $refund_amount_total = 0;
		//$refund_amount_total = array_sum($refund_amount_arr);
		
		$refund_amount_arr = $refund_amount_arr ?? []; // PHP 7+
		$refund_amount_total = array_sum($refund_amount_arr);

		
		$balance = $paid_total - $wallet_bill_total - $refund_amount_total;
?>
<style type="text/css">
    form{
        margin: 20px 0;
    }
    form input, button{
        padding: 5px;
    }
    table{
        width: 100%;
        margin-bottom: 20px;
		border-collapse: collapse;
    }
    table, th, td{
        border: 1px solid #cdcdcd;
    }
    table th, table td{
        padding: 10px;
        text-align: left;
    }
	.heading{margin-bottom:10px;margin-top: 0; padding-top:0px;}
</style>
<form class="col-sm-12 col-xs-12" id="add_billing_form" method="post" action="">
  <input type="hidden" name="action" value="add_billing_medicine" />
  <input type="hidden" name="appointment_id" id="appointment_id" value="<?php echo $appointment_id ?>" />
  
  <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku" id="procedure_details">
      <div class="panel-heading">
        <h3 class="heading">Medicine Billing</h3><p style="margin-top:20px;color:red;">Wallets Amount : <a href="<?php echo base_url(); ?>patients/edit/<?php echo $select_result_consultation['patient_id']; ?>"><?php echo $balance; ?></a></p>
      </div>
      <div class="panel-body profile-edit">
     	<p id="msg_area" class="delete"></p>
        <p>
            <div id="main_div">
            	<div class="row">            
                   <div class="form-group col-sm-4 col-xs-12">
                        <label for="item_name">IIC ID (Required)</label>
                        <input value="<?php echo $patient_id; ?>" placeholder="IIC ID" id="patient_id" name="patient_id" type="text" class="form-control required_value" required>
                   </div>
                   <div class="form-group col-sm-4 col-xs-12">
                        <label for="item_name">Patient Name (Required)</label>
                        <input value="" placeholder="Patient Name" id="patient_detail_name" name="patient_detail_name" type="text" class="form-control required_value" required>
				   </div>
                   <div class="form-group col-sm-4 col-xs-12">
                        <label for="item_name">Receipt number (Required)</label>
                        <input value="<?php date_default_timezone_set("America/New_York");$receipt_number = date("YmdHis") . substr(microtime(), 2, 6);echo $receipt_number; ?>" placeholder="Receipt number" id="receipt_number" name="receipt_number" readonly="readonly" type="text" class="form-control required_value" required>                        
						<input type="hidden" value="<?php echo $select_result_consultation['doctor_id']; ?>" id="doctor_id" name="doctor_id">
				   </div>
                </div>
              
                <div class="clearfix"></div>
                <hr />
               
                <section class="col-sm-12 col-xs-12 consumables_section">
                  <div class="clearfix"></div>
                  <input type="button" class="add-consumables-row btn btn-large" value="Add Medicine">
				  <input type="button" class="delete-consumables-row btn btn-large pull-right" value="Delete Selected Consumables">
                  <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Serial Number</th>
                            <th>Consumable</th>
                            <th>Unit</th>
							<th>Open Stock</th>
							<th>Batch Number</th>
                            <th>Price (<i class="fa fa-inr" aria-hidden="true"></i>)</th>
                            <th>Discount (%)</th>
                            <th>Net Price</th>
							<th>Tax (%)</th>
                            <th>Delete</th>
						</tr>
                    </thead>
					
                    <tbody id="consumables_table_body">
                        <tr class="consumables_row_1" trcount="1">
                            <td><input type="checkbox" class="active-statuss" rel="consumables" index="1"></td>
                            <td><input value="" placeholder="Serial Number" readonly="readonly" id="consumables_serial_1" class="cons-cls-1 consumables_serial_1 form-control" name="consumables_serial_1" type="text" required></td>
                            <td class="role cons_cls_1">
                                <select disabled name="consumables_name_1" class="item_select consumables_select cons-cls-1" id="consumables_name_1" count="1" required>
                                    <option value="">Select</option>
                                <?php foreach($consumables as $key => $val){ ?>
                                    <option value="<?php echo $val['item_number']; ?>" ID="<?php echo $val['ID']; ?>" batch_number="<?php echo $val['batch_number']; ?>" quantity="<?php echo $val['quantity']; ?>" fees="<?php echo $val['price']; ?>" mrp="<?php echo $val['mrp']; ?>" item_name="<?php echo $val['item_name']; ?>" vendor_price="<?php echo $val['vendor_price']; ?>" expiry="<?php echo $val['expiry']; ?>" gstrate="<?php echo $val['gstrate']; ?>" hsn="<?php echo $val['hsn']; ?>" pack_size="<?php echo $val['pack_size']; ?>" gstdivision="<?php echo $val['gstdivision']; ?>" company="<?php echo $val['company']; ?>" date_of_purchase="<?php echo $val['date_of_purchase']; ?>" invoice_no="<?php echo $val['invoice_no']; ?>"> <?php echo $val['item_name']; ?> (<?php echo $val['batch_number']; ?>) (<?php echo $val['expiry']; ?>)</option>
                                <?php } ?>
                                </select>
                            </td>
							<td><input value="" item_number="" placeholder="ID" readonly="readonly" id="consumables_ID_1" class="cons-cls-1 consumables_ID_1 form-control" name="consumables_ID_1" type="hidden" required><input disabled value="" item_number="" placeholder="Consumption/patient (unit)" id="consumables_quantity_1" qcount="1"  onkeyup="consumables_quantity_update(this)" class="cons-cls-1 consumables_quantity consumables_quantity_1 form-control" name="consumables_quantity_1" type="text" min="0" required></td>
							<td><input value="" placeholder="Open Stock" readonly="readonly" id="consumables_stock_1" class="cons-cls-1 consumables_stock_1 form-control" name="consumables_stock_1" type="text" required></td> 
							<td><input value="" item_number="" placeholder="Batch Number" readonly="readonly" id="consumables_batch_number_1" class="cons-cls-1 consumables_batch_number_1 form-control" name="consumables_batch_number_1" type="text" required></td> 
							<td><input value="" placeholder="Price" readonly="readonly" id="consumables_price_1" class="cons-cls-1 consumables_price form-control" name="consumables_price_1" type="text" required></td>                   
							<td><input value="" placeholder="Discout" qcount="1" onblur="consumables_total_discount_price_update(this)" id="consumables_discount_1" class="cons-cls-1 consumables_discount form-control" name="consumables_discount_1" type="text" required></td>                   
							<td><input value="" placeholder="Total" readonly="readonly" id="consumables_total_1" class="cons-cls-1 consumables_total form-control" name="consumables_total_1" type="text" required></td>
                            <td><input value="" placeholder="Tax" readonly="readonly" id="consumables_gstrate_1" class="cons-cls-1 consumables_gstrate_1 form-control" name="consumables_gstrate_1" type="text" required>
							    <input value="" item_number="" id="consumables_company_1" class="cons-cls-1 consumables_company_1 form-control" name="consumables_company_1" type="hidden">
							    <input value="" item_number="" id="consumables_item_name_1" class="cons-cls-1 consumables_item_name_1 form-control" name="consumables_item_name_1" type="hidden">
							    <input value="" item_number="" id="consumables_vendor_price_1" class="cons-cls-1 consumables_vendor_price_1 form-control" name="consumables_vendor_price_1" type="hidden">
							    <input value="" item_number="" id="consumables_expiry_1" class="cons-cls-1 consumables_expiry_1 form-control" name="consumables_expiry_1" type="hidden">
							    <input value="" item_number="" id="consumables_hsn_1" class="cons-cls-1 consumables_hsn_1 form-control" name="consumables_hsn_1" type="hidden">
								<input value="" item_number="" id="consumables_gstdivision_1" class="cons-cls-1 consumables_gstdivision_1 form-control" name="consumables_gstdivision_1" type="hidden">
								<input value="" item_number="" id="consumables_mrp_1" class="cons-cls-1 consumables_mr_1 form-control" name="consumables_mrp_1" type="hidden">
								<input value="" item_number="" id="consumables_pack_size_1" class="cons-cls-1 consumables_pack_size_1 form-control" name="consumables_pack_size_1" type="hidden">
                                <input value="" placeholder="" id="consumables_date_of_purchase_1" name="consumables_date_of_purchase_1" type="hidden" class="cons-cls-1 consumables_date_of_purchase_1 form-control">
                                <input value="" placeholder="" id="consumables_invoice_no_1" name="consumables_invoice_no_1" type="hidden" class="cons-cls-1 consumables_invoice_no_1 form-control">
                            </td>								
							<td><input type="checkbox" class="statuss" name="record"></td>
                        </tr>
                    </tbody>
                </table> 
                <table>
                    <thead>
                        <tr>
                           <td width="60%">Total</td>
						   <td width="20%"><div> Total Amount: <input type="text" name="fees" id="total_amount" readonly></div></td>
						   <!--<td width="12%" id="consumables_price"></td>
						  <td colspan="1"><input type="hidden" value="" id="consumables_price" name="consumables_price" ></td>-->
                           
                           <td id="total_final_discount" width="11%"></td>
                           <td colspan="1">
						   <input type="hidden" id="payment_done" name ="payment_done" value=""/>
						   </td>
                        </tr>
                    </thead>
                                </table>          
                </section>
				
				
		
          

        <div class="row" id="grand_total_section">
          <div class="row">
              <div class="form-group col-sm-6 col-xs-12 role">
                    <label for="statuss">Payment mode</label>
                    <select name="payment_method" id="payment_method">
                        <option value="">Select</option>
                        <option value="card" mode="Card">Card</option>
                        <option value="upi" mode="UPI">UPI</option>
                        <option value="cash" mode="Cash">Cash</option>
						<option value="neft" mode="Neft">Neft</option>
						<option value="wallet" mode="wallet">Wallet</option>
                    </select>
                </div>
				<div class="form-group col-sm-6 col-xs-12" id="transaction">
                  <label for="item_name">Reference no. (Optional)</label>
                  <input value="" placeholder="Reference no." id="transaction_id" name="transaction_id" type="text" class="form-control  required_value" required>
                </div>
          </div>     
		   <div class="row">
           <div class="form-group col-sm-6 col-xs-12 role hospital_id_section">
                  <label for="item_name">Hospital ID</label>
				  <select name="hospital_id" id="hospital_id">
                        <option value="">Select</option>
                        <option value="Noida">Noida</option>
                        <option value="Ghaziabad">Ghaziabad</option>
                        <option value="Gurgaon">Gurgaon</option>
						<option value="Green Park">Green Park</option>
						<option value="Srinagar">Srinagar</option>
                    </select>
                </div>
                <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Billing ID (Optional)</label>
                  <input value="" placeholder="Billing ID" id="billing_id" name="billing_id" type="text" class="form-control ">
                </div>
				 <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Cash Payment</label>
                  <input value="" placeholder="Cash Payment" id="cash_payment" name="cash_payment" type="text" class="form-control ">
                </div>
				 <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">Card Payment</label>
                  <input value="" placeholder="Card Payment" id="card_payment" name="card_payment" type="text" class="form-control ">
                </div>
				 <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">UPI Payment</label>
                  <input value="" placeholder="UPI Payment" id="upi_payment" name="upi_payment" type="text" class="form-control ">
                </div>
				 <div class="form-group col-sm-6 col-xs-12">
                  <label for="item_name">NEFT Payment</label>
                  <input value="" placeholder="NEFT Payment" id="neft_payment" name="neft_payment" type="text" class="form-control ">
                </div>
				<div class="form-group col-sm-6 col-xs-12">
					<label for="item_name">Wallet Payment</label>
					<input type="number" wallet_payment="<?php echo $balance; ?>" name="wallet_payment" id="wallet_payment" onchange="wallet_quantity_update(this)">
					<input type="hidden" value="Pending" id="status" name="status" class="form-control ">
					<input type="hidden" value="<?php echo $_SESSION['logged_billing_manager']['employee_number']?>" id="employee_number" name="employee_number">
					<input type="hidden" value="<?php echo $centers_result['center_code']; ?>/O/<?php $year = date("y"); echo $year, $year+1; ?>/" id="series_number" name="series_number" class="form-control validate">
					<input type="hidden" value="<?php echo $_SESSION['logged_billing_manager']['center']?>" id="billing_at" name="billing_at">
                </div>

				
			</div>
            
            <div class="clearfix"></div>
            <div class="form-group col-sm-12 col-xs-12">
                <button type="button" id="create_billing"> Create Billing </button>
            </div>
          </div>
				
                <div class="clearfix"></div>
                
            </div>
      </div>
      </p>
    </div>
  </div>
</form>

<!--****** consumables SCRIPT *******-->
<script>
$(document).on('blur',"#patient_id",function(e) {
        $('#patient_detail_name').empty();
		
        var patient_id = $(this).val();
        if(patient_id != ""){
            $.ajax({
                url: '<?php echo base_url('patients/patient_detail_name2')?>',
                data: {patient_id : patient_id},
                dataType: 'json',
                method:'post',
                success: function(data)
                {
					$('#patient_detail_name').val(data);
                   // $('#patient_detail_name').append(data); 
                }
            });
        }
    });

    

	 $(document).on('change',".	",function(e) {
        $('#msg_area').empty();
		var serial = $(this).val();
		var count = $(this).attr('count');
		
		$('#consumables_serial_'+count).val('');
		$('#consumables_ID_'+count).val('');
		$('#consumables_company_'+count).val('');
		$('#consumables_item_name_'+count).val('');
		$('#consumables_quantity_'+count).val('');
		$('#consumables_stock_'+count).val('');
		$('#consumables_price_'+count).val('');
		$('#consumables_quantity_'+count).attr("item_number", "");
		$('#consumables_batch_number_'+count).val('');
		$('#consumables_gstrate_'+count).val('');
		$('#consumables_hsn_'+count).val('');
		$('#consumables_mrp_'+count).val('');
		$('#consumables_pack_size_'+count).val('');
		$('#consumables_gstdivision_'+count).val('');
		$('#consumables_sub_total').val('');
		$('#consumables_discount_'+count).val('');  
        $('#consumables_discount_'+count).attr("item_number", "");
		$('#consumables_total_'+count).val('');
		$('#consumables_vendor_price_'+count).val('');
		$('#consumables_expiry_'+count).val('');
		$('#consumables_date_of_purchase_'+count).val('');
		$('#consumables_invoice_no_'+count).val('');
		  
		
		if(serial != ''){
			var serial = $(this).val();
			var ID = $(this).find(':selected').attr('ID');
			var company = $(this).find(':selected').attr('company');
			var item_name = $(this).find(':selected').attr('item_name');
			var batch_number = $(this).find(':selected').attr('batch_number');
			var gstrate = $(this).find(':selected').attr('gstrate');
			var hsn = $(this).find(':selected').attr('hsn');
			var mrp = $(this).find(':selected').attr('mrp');
			var pack_size = $(this).find(':selected').attr('pack_size');
			var gstdivision = $(this).find(':selected').attr('gstdivision');
			var quantity = $(this).find(':selected').attr('quantity');
			var fees = $(this).find(':selected').attr('fees');
			var vendor_price = $(this).find(':selected').attr('vendor_price');
			var expiry = $(this).find(':selected').attr('expiry');
            var date_of_purchase = $(this).find(':selected').attr('date_of_purchase');
			var invoice_no = $(this).find(':selected').attr('invoice_no');
			
			
			$('#consumables_serial_'+count).val(serial);
			$('#consumables_ID_'+count).val(ID);
			$('#consumables_company_'+count).val(company);
			$('#consumables_item_name_'+count).val(item_name);
			$('#consumables_batch_number_'+count).val(batch_number);
			$('#consumables_gstrate_'+count).val(gstrate);
			$('#consumables_hsn_'+count).val(hsn);
			$('#consumables_mrp_'+count).val(mrp);
			$('#consumables_pack_size_'+count).val(pack_size);
			$('#consumables_gstdivision_'+count).val(gstdivision);
            $('#consumables_stock_'+count).val(quantity);
			$('#consumables_quantity_'+count).attr({'max': parseInt(quantity), 'min': 0});
            $('#consumables_quantity_'+count).attr("item_number", serial);
			$('#consumables_quantity_'+count).attr("item_quantity", quantity);
			//$('#consumables_batch_number_'+count).val(batch_number);
			$('#consumables_price_'+count).val(fees);
            $('#consumables_discount_'+count).attr("item_number", serial);
			$('#consumables_vendor_price_'+count).val(vendor_price);
			$('#consumables_expiry_'+count).val(expiry);
            $('#consumables_date_of_purchase_'+count).val(date_of_purchase);
            $('#consumables_invoice_no_'+count).val(invoice_no);
		}
			var fee_total = 0;
			$('.consumables_price').each(function(){
				var price_total = 0;
				var price_total = $(this).val();
				fee_total += +price_total;
			});
			$('#consumables_sub_total').val(fee_total);
			$('#consumables_total').val(fee_total);
		//	calculate_fees();
    });
	
	 $(document).ready(function(){
		 
		$(".add-consumables-row").click(function(){
			var rows= $('#consumables_table_body tr:last').attr('trcount');
			var count = parseFloat(rows) + 1;
           var markup = '<tr class="consumables_row_'+count+'" trcount="'+count+'"><td><input type="checkbox" class="active-statuss"  rel="consumables"  index="'+count+'"></td><td><input value="" placeholder="Serial Number" readonly="readonly" id="consumables_serial_'+count+'" class="cons-cls-'+count+' consumables_serial_'+count+' form-control " name="consumables_serial_'+count+'" type="text" required></td><td class="role cons_cls_'+count+'"><select disabled name="consumables_name_'+count+'" class="cons-cls-'+count+' item_select consumables_select form-control " id="consumables_name_'+count+'" count="'+count+'" required><option value="">Select</option><?php foreach($consumables as $key => $val){ ?><option value="<?php echo $val['item_number']; ?>" ID="<?php echo $val['ID']; ?>" quantity="<?php echo $val['quantity']; ?>" fees="<?php echo $val['price']; ?>" item_name="<?php echo $val['item_name']; ?>" batch_number="<?php echo $val['batch_number']; ?>" vendor_price="<?php echo $val['vendor_price']; ?>" expiry="<?php echo $val['expiry']; ?>" gstrate="<?php echo $val['gstrate']; ?>" mrp="<?php echo $val['mrp']; ?>" hsn="<?php echo $val['hsn']; ?>" gstdivision="<?php echo $val['gstdivision']; ?>" company="<?php echo $val['company']; ?>" date_of_purchase="<?php echo $val['date_of_purchase']; ?>" invoice_no="<?php echo $val['invoice_no']; ?>"> <?php echo $val['item_name']; ?> (<?php echo $val['batch_number']; ?>) (<?php echo $val['expiry']; ?>)</option><?php } ?></select></td><td><input value="" placeholder="ID" readonly="readonly" id="consumables_ID_'+count+'" class="cons-cls-'+count+' consumables_ID form-control"  name="consumables_ID_'+count+'" type="hidden" required><input value="" qcount="'+count+'" onkeyup="consumables_quantity_update(this)" placeholder="Consumption/patient (unit)"  item_number="" item_quantity="" id="consumables_quantity_'+count+'" disabled class="consumables_quantity consumables_quantity_'+count+' form-control cons-cls-'+count+'" name="consumables_quantity_'+count+'" type="text" min="0" required></td><td><input value="" placeholder="Currunt Stock" readonly="readonly" id="consumables_stock_'+count+'" class="cons-cls-'+count+' consumables_stock_'+count+' form-control " name="consumables_stock_'+count+'" type="text" required></td><td><input value="" placeholder="Batch Number" readonly="readonly" id="consumables_batch_number_'+count+'" class="cons-cls-'+count+' consumables_batch_number form-control "  name="consumables_batch_number_'+count+'" type="text" required></td><td><input value="" placeholder="Price" readonly="readonly" id="consumables_price_'+count+'" class="cons-cls-'+count+' consumables_price form-control "  name="consumables_price_'+count+'" type="text" required></td><td><input value="" placeholder="Discount" id="consumables_discount_'+count+'" qcount="'+count+'" onblur="consumables_total_discount_price_update(this)" item_number="" class="cons-cls-'+count+' consumables_discount_'+count+' form-control " name="consumables_discount_'+count+'" type="text" required></td><td><input value="" placeholder="Total" readonly="readonly" id="consumables_total_'+count+'" class="cons-cls-'+count+' consumables_total_'+count+' form-control " name="consumables_total_'+count+'" type="text" required></td><td><input value="" id="consumables_gstrate_'+count+'" class="cons-cls-'+count+' consumables_gstrate_'+count+' form-control " name="consumables_gstrate_'+count+'" readonly="readonly" type="text"><input value="" placeholder="Tax" readonly="readonly" id="consumables_hsn_'+count+'" class="cons-cls-'+count+' consumables_hsn_'+count+' form-control " name="consumables_hsn_'+count+'" type="hidden"><input value="" placeholder="Tax" readonly="readonly" id="consumables_mrp_'+count+'" class="cons-cls-'+count+' consumables_mrp_'+count+' form-control " name="consumables_mrp_'+count+'" type="hidden"><input value="" placeholder="Tax" readonly="readonly" id="consumables_gstdivision_'+count+'" class="cons-cls-'+count+' consumables_gstdivision_'+count+' form-control " name="consumables_gstdivision_'+count+'" type="hidden"><input value="" placeholder="Tax" readonly="readonly" id="consumables_pack_size_'+count+'" class="cons-cls-'+count+' consumables_pack_size_'+count+' form-control " name="consumables_pack_size_'+count+'" type="hidden"></td><td style="display:none"><input value="" id="consumables_vendor_price_'+count+'" class="cons-cls-'+count+' consumables_vendor_price_'+count+' form-control " name="consumables_vendor_price_'+count+'" type="hidden"><input value="" id="consumables_expiry_'+count+'" class="cons-cls-'+count+' consumables_expiry_'+count+' form-control " name="consumables_expiry_'+count+'" type="hidden"><input value="" id="consumables_item_name_'+count+'" class="cons-cls-'+count+' consumables_item_name_'+count+' form-control " name="consumables_item_name_'+count+'" type="hidden"><input value="" id="consumables_company_'+count+'" class="cons-cls-'+count+' consumables_company_'+count+' form-control " name="consumables_company_'+count+'" type="hidden"><input value="" id="consumables_date_of_purchase_'+count+'" class="cons-cls-'+count+' consumables_date_of_purchase_'+count+' form-control " name="consumables_date_of_purchase_'+count+'" type="hidden"><input value="" id="consumables_invoice_no_'+count+'" class="cons-cls-'+count+' consumables_invoice_no_'+count+' form-control " name="consumables_invoice_no_'+count+'" type="hidden"></td><td><input type="checkbox" class="statuss" name="record"></td></tr>';
             $("table tbody#consumables_table_body").append(markup);
		//	calculate_fees();
        });
        
        // Find and remove selected table rows
        $(".delete-consumables-row").click(function(){
            $("table tbody").find('input[name="record"]').each(function(){
            	if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
				var fee_total = 0;
				$('.consumables_price').each(function(){
					var price_total = 0;
					var price_total = $(this).val();
					fee_total += +price_total;
				});
				$('#consumables_sub_total').val(fee_total);
				$('#consumables_discount').val(0);
				$('#consumables_total').val(fee_total);
            });
			//calculate_fees();
        });
    });
</script>
<!--****** consumables SCRIPT *******-->

<!--****** Billing SCRIPT *******-->
<script>
	$(document).on('click',"#create_billing",function(e) {
		// Prevent double-click submissionp
		if ($(this).prop('disabled')) {
			e.preventDefault();
			return false;
		}
		
		// Disable button immediately to prevent double-click
		$(this).prop('disabled', true);
		
		// Add loading state
		var originalText = $(this).text();
		$(this).text('Processing...');
		
       $('#error').empty();
	   var has_empty = false;
	   var patient_id = $('#patient_id').val();
	    var receipt_number = $('#receipt_number').val();
	   if ( patient_id == '' || receipt_number == '') 
	   {
		   $('#error').append('One or more fields are empty!');
		   // Re-enable button if validation fails
		   $(this).prop('disabled', false);
		   $(this).text(originalText);
	   }else{
	   		var com_count = 1;
			var com_rows= $('#consumables_table_body tr').length;
			$('.consumables_select').each(function(){
				if(com_count <= com_rows){
					var name, price, serial, quantity, batch_number,vendor_price,expiry,gstrate,hsn stock = '';
					if($(this).val() != ''){
						quantity = $('#consumables_quantity_'+com_count).val();
						if(quantity == ''){
							has_empty = false;
						}else{
							has_empty = true;
						}
						
					}
					com_count++;
				 }
			});
			
			if(has_empty == true){
				$('#add_billing_form').submit();
			}else{
			   $('#error').append('One or more fields are empty!');
			   // Re-enable button if validation fails
			   $(this).prop('disabled', false);
			   $(this).text(originalText);
		   }	   		
	   }
	   
	   // Re-enable button after 10 seconds as a safety measure
	   setTimeout(function() {
		   $('#create_billing').prop('disabled', false);
		   $('#create_billing').text(originalText);
	   }, 10000);
    });
</script>
<script>
    $(document).on('click',".active-statuss",function(e) {
        var count = $(this).attr('index');
        var type = $(this).attr('rel');
        if($(this).is(':checked'))
        {
           // console.log(count+"---------"+type);
            if(type =="consumables"){
                $('td.role.cons_cls_'+count+' select.item_select').select2({tags: true});
                $('.cons-cls-'+count).prop("disabled", false);
                $('.cons-cls-'+count).addClass("required_value");
            }
        }else
        {
           if(type =="consumables"){
                $('.cons-cls-'+count).prop("disabled", true);
                $('.cons-cls-'+count).removeClass("required_value");
            }
        }       
    });
	$(document).on('click',"#create_billing",function(e) {
		// Prevent double-click submission
		if ($(this).prop('disabled')) {
			e.preventDefault();
			return false;
		}
		
		// Disable button immediately to prevent double-click
		$(this).prop('disabled', true);
		
		// Add loading state
		var originalText = $(this).text();
		$(this).text('Processing...');
		
	 	  var value = $('.required_value').filter(function () {
			return this.value === '';
		  });
		  if (value.length == 0) {
		  	$('#add_billing_form').submit();
		  } else if (value.length > 0) { 
		  	alert('Please fill out all fields.');
		  	// Re-enable button if validation fails
		  	$(this).prop('disabled', false);
		  	$(this).text(originalText);
		  }
		  
		  // Re-enable button after 10 seconds as a safety measure
		  setTimeout(function() {
			  $('#create_billing').prop('disabled', false);
			  $('#create_billing').text(originalText);
		  }, 10000);
    });


   // $('.consumables_quantity').on("keyup", function() {
	function consumables_quantity_update(el) {
      var count =  $(el).attr('qcount');
       var item_number =  $(el).attr('item_number');
	   var item_quantity =  $(el).attr('item_quantity');
       $('#consumables_price_'+count).val(" ");
       var units = $(el).val();
	   
       if(units > 0){
		   if(parseInt(item_quantity) < parseInt(units)){
			   alert('Unit must be less than or equal to quantity');
			   $(el).val("0");
			   return false;
		   }else{
				$.ajax({
					url: '<?php echo base_url('stocks/get_stock_item_price')?>',
					data: {item_number : item_number, units:units},
					dataType: 'json',
					method:'post',
					success: function(data)
					{
						$('#consumables_price_'+count).val(data.toFixed(2)); 
					}
				});
		   }
        } 
	}
   // });

   
function consumables_total_discount_price_update(el) {
    var count = $(el).attr('qcount');
    var item_number = $(el).attr('item_number');
    var discount = $(el).val();
    
    $('#consumables_total_' + count).val('');
    var units = $('#consumables_quantity_' + count).val();

    if (units > 0) {
        $.ajax({
            url: '<?php echo base_url('stocks/get_stock_item_discount_price')?>',
            data: { item_number: item_number, units: units, discount: discount },
            dataType: 'json',
            method: 'post',
            success: function(data) {
                $('#consumables_total_' + count).val(data.toFixed(2));
                setTimeout(function () {
                    var discount_total = 0;
                    var final_total = 0;
                    var total_amount = 0;

                    for (var ctr = 1; ctr <= count; ctr++) {
                        var temp_discount = $('#consumables_discount_' + ctr).val();
                        discount_total += parseFloat(temp_discount) || 0;

                        var temp_final = $('#consumables_total_' + ctr).val();
                        final_total += parseFloat(temp_final) || 0;

                        var temp_amount = $('#consumables_price_' + ctr).val();
                        total_amount += parseFloat(temp_amount) || 0;
                    }

                    $("#discount").html(discount_total.toFixed(2));
                    $("#discount_amount").val(discount_total.toFixed(2));
                    $("#total_final_discount").html(final_total.toFixed(2));
                    $("#payment_done").val(final_total.toFixed(2));
                    $("#total_amount").val(total_amount.toFixed(2)); // Update the total amount in the input field
                }, 500);
            }
        });
    }
}

function wallet_quantity_update(el) {
    var wallet_payment = $(el).attr('wallet_payment');
    var balance = $(el).val();

    if (balance > 0) {
        if (parseInt(wallet_payment) < parseInt(balance)) {
            alert('Wallet Amount must be less than or equal to wallet payment');
            $(el).val("0");
            return false;
        }
    }
}

</script>

<script>
   $('#create_billing').click(function(e){
	// Prevent double-click submission
	if ($(this).prop('disabled')) {
		e.preventDefault();
		return false;
	}
	
	// Disable button immediately to prevent double-click
	$(this).prop('disabled', true);
	
	// Add loading state
	var originalText = $(this).text();
	$(this).text('Processing...');
	
	e.preventDefault();
	if(this.form.reportValidity()){
		this.form.submit();
	} else {
		// Re-enable button if validation fails
		$(this).prop('disabled', false);
		$(this).text(originalText);
	}
	
	// Re-enable button after 10 seconds as a safety measure
	setTimeout(function() {
		$('#create_billing').prop('disabled', false);
		$('#create_billing').text(originalText);
	}, 10000);
});
</script>


<!--****** Billing SCRIPT *******-->