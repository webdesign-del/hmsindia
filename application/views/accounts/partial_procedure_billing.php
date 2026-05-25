<?php $all_method =&get_instance();
	$center= get_center_name($data['billing_at']);
	$patient_data = get_patient_detail($data['patient_id']);
	$center_data = center_detail($center);
	$currency = '';
	$status = check_billing_status($data['patient_id'], $data['receipt_number'], 'procedure');
 ?> 
<?php 
	    
		$ci = &get_instance();
		$ci->load->database();
		$db_prefix = $ci->config->config['db_prefix'];
		$patient_sql = "Select * from ".$db_prefix."patients where  patient_id='".$data['patient_id']."'";
        $patient_q = $ci->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		$patient_id = $patient_result[0]['patient_id'];

		$consultation_result = $procedure_result = $investigation_result = $medicine_result = $registation_result = $remaining_billing = $bill_arr = $bill_total = array();
		$procedure_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_procedure where status='cancel' and patient_id='".$data['patient_id']."'";
        $procedure_q = $ci->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();
		
		$registation_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."registation where status='adjust' and patient_id='".$data['patient_id']."'";
        $registation_q = $ci->db->query($registation_sql);
        $registation_result = $registation_q->result_array();

		$consultation_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."consultation where status='adjust ' and patient_id='".$data['patient_id']."'";
        $consultation_q = $ci->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();

		$investigation_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_investigations where status='cancel' and patient_id='".$data['patient_id']."'";
        $investigation_q = $ci->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
		
		$medicine_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_at from ".$db_prefix."patient_medicine where status='cancel' and patient_id='".$data['patient_id']."'";
        $medicine_q = $ci->db->query($medicine_sql);
        $medicine_result = $medicine_q->result_array();

		$done_sql = "Select sum(payment_done) as payment_done from ".$db_prefix."patient_payments where patient_id='".$data['patient_id']."' AND status='3'";
		$done_q = $ci->db->query($done_sql);
		$done_result = $done_q->result_array();

		$total = 0;
    	foreach($consultation_result as $key => $val){
		    $bill_arr[] = $val['payment_done'];
		}

		$total = 0;
		foreach($investigation_result as $key => $val){
		    $bill_arr[] = $val['payment_done'];
		}

		$total = 0;
		foreach($procedure_result as $key => $val){
			$bill_arr[] = $val['payment_done'];
		}
		
		$total = 0;
		foreach($registation_result as $key => $val){
		    $bill_arr[] = $val['payment_done'];
		}
		
		$total = 0;
		foreach($medicine_result as $key => $val){
			$bill_arr[] = $val['payment_done'];
		}
		
		$total = 0;
		foreach($done_result as $key => $val){
			$bill_arr[] = $val['payment_done'];
		}

         //wallete
		$consultation_wallet_result = $procedure_wallet_result = $investigation_wallet_result = $done_wallet_result = $medicine_wallet_result = $wallet_remaining_billing = $wallet_arr = $wallet_bill_total = array();
		$procedure_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_procedure where wallet_payment > 0 and patient_id='".$data['patient_id']."'";
        $procedure_wallet_q = $ci->db->query($procedure_wallet_sql);
        $procedure_wallet_result = $procedure_wallet_q->result_array();

		$consultation_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."consultation where wallet_payment > 0 and patient_id='".$data['patient_id']."'";
        $consultation_wallet_q = $ci->db->query($consultation_wallet_sql);
        $consultation_wallet_result = $consultation_wallet_q->result_array();

		$investigation_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_investigations where wallet_payment > 0 and patient_id='".$data['patient_id']."'";
        $investigation_wallet_q = $ci->db->query($investigation_wallet_sql);
        $investigation_wallet_result = $investigation_wallet_q->result_array();
		
		$medicine_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_at from ".$db_prefix."patient_medicine where wallet_payment > 0 and patient_id='".$data['patient_id']."'";
        $medicine_wallet_q = $ci->db->query($medicine_wallet_sql);
        $medicine_wallet_result = $medicine_wallet_q->result_array();
		
		$done_wallet_sql = "Select billing_id, payment_done, wallet_payment, refrence_number, billing_from, billing_at from ".$db_prefix."patient_payments where wallet_payment > 0 and patient_id='".$data['patient_id']."'";
		$done_wallet_q = $ci->db->query($done_wallet_sql);
		$done_wallet_result = $done_wallet_q->result_array();

		$refund_amount_sql = "Select patient_id, package_name, package_code, consultation_fee, usg_scan_charge, consumable_charges, file_registation_charge, refund_amount, on_date, status from ".$db_prefix."refund_amount where patient_id='".$patient_data['patient_id']."'";
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
		foreach($medicine_wallet_result as $key => $value){
			$wallet_arr[] = $value['wallet_payment'];
		}

		$total = 0;
		foreach($procedure_wallet_result as $key => $value){
			$wallet_arr[] = $value['wallet_payment'];
		}
		
		$total = 0;
		foreach($done_wallet_result as $key => $value){
			$wallet_arr[] = $value['wallet_payment'];
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
		$refund_amount_total = array_sum(is_array($refund_amount_arr) ? $refund_amount_arr : []);

		
		$balance = $paid_total - $wallet_bill_total - $refund_amount_total;

		
		//Pending Amount
		$procedure_pending_result = $done_pending_result =  $pending_arr = $pending_bill_total = array();
		$procedure_pending_sql = "Select receipt_number, totalpackage, discount_amount, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_procedure where status IN ('approved', 'pending') and patient_id='".$data['patient_id']."' and receipt_number='".$data['receipt_number']."'";
		$procedure_pending_q = $ci->db->query($procedure_pending_sql);
		$procedure_pending_result = $procedure_pending_q->result_array();
	 
		$done_pending_sql = "Select billing_id, payment_done, wallet_payment, refrence_number, billing_from, billing_at from ".$db_prefix."patient_payments where status IN ('0', '1') and patient_id='".$data['patient_id']."' and billing_id='".$data['receipt_number']."'";
		$done_pending_q = $ci->db->query($done_pending_sql);
		$done_pending_result = $done_pending_q->result_array();
			 
		$total = 0;
		foreach($procedure_pending_result as $key => $value){
			$pending_arr[] = $value['payment_done'];
			$pending_arr2[] = $value['fees'];
		}
			 
		$total = 0;
		foreach($done_pending_result as $key => $value){
			$pending_arr[] = $value['payment_done'];
		}

		$paid_total = 0;
		//$paid_total = array_sum($pending_arr2);
		
		$paid_total = array_sum(is_array($pending_arr2) ? $pending_arr2 : []);
			 
		$pending_bill_total = 0;
		$pending_bill_total = array_sum($pending_arr);
			 
		$pendingbalance = $paid_total - $pending_bill_total;
		?>
<?php 
    // Load our smart helper
    $this->load->helper('billing');
    
    // Check if parent page passed $patient_data, otherwise let helper find it
    $p_id = $patient_data['patient_id'] ?? $patient_data['uhid'] ?? null;
    $wallet = get_universal_wallet($p_id); 
?>
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm" style="border-left: 5px solid #28a745; background: #f8fff9; min-height: 100px;">
            <div class="card-body">
                <h6 class="text-success font-weight-bold">Money Wallet</h6>
                <h2 class="display-5" style="margin: 10px 0;">₹ <?php echo number_format($wallet['wallet_1'], 2); ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm" style="border-left: 5px solid #ff9800; background: #fffaf2; min-height: 100px;">
            <div class="card-body">
                <h6 class="text-warning font-weight-bold">Package Wallet</h6>
                <h2 class="display-5" style="margin: 10px 0;">₹ <?php echo number_format($wallet['wallet_2'], 2); ?></h2>
            </div>
        </div>
    </div>
</div>		
	<?php  $procedures = unserialize($data['data']); if(isset($procedures) && !empty($procedures)) {  ?>
             
			  <form class="col-sm-12 col-xs-12" method="post" action="" enctype="multipart/form-data" >
			  <input type="hidden" name="action" value="add_partial_procedure" />
			  <input type="hidden" id="patient_id" name="patient_id" value="<?php echo $data['patient_id'];?>" />
			  <input type="hidden" id="billing_id" name="billing_id" value="<?php echo $data['receipt_number'];?>" />
			   
			   <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku" id="procedure_details">
      <div class="panel-heading">
        <h3 class="heading">Partial Procedures Billing </h3>  <p style="margin-top:20px;color:red;">Wallet Amount : <?php echo $balance; ?></p> <p style="margin-top:20px;color:red;">Total Paid Amount : <?php echo $pending_bill_total; ?></p>
		<p style="margin-top:20px;color:red;">Remaining Amount : <?php echo $pendingbalance; ?></p>
      </div>
      <div class="panel-body profile-edit">
     	<p id="msg_area" class="delete"></p>
        <p>
            <div id="main_div">
			
				<section class="col-sm-12 col-xs-12 consumables_section">
                  <div class="clearfix"></div>
                  <input type="button" class="delete-consumables-row btn btn-large pull-right" value="Delete Selected Procedures">
					<table>
						<thead>
							<tr>
								<!--<th></th>-->
								<th>Procedure Name</th>
								<th>Procedures Code</th>
								<th>Procedures Price</th>
								<th>Discount</th>
								<th>Paid Price</th>
								<th>Paid Price</th>
								<th>Delete</th>
							</tr>
						</thead>
					
						<tbody id="consumables_table_body">
						<?php $sub_procedure_counter = 1; foreach($procedures['patient_procedures'] as $key => $val){ ?>
							<tr class="consumables_row_1" trcount="1">
								<!--<td><input type="checkbox" class="active-statuss" rel="consumables" index="1"></td>-->
								<td><input value="<?php echo $all_method->get_procedure_name($val['sub_procedure']); ?>" readonly="readonly" id="sub_procedure_<?php echo $sub_procedure_counter;?>" class="cons-cls-1 consumables_serial_1 form-control" name="sub_procedure_<?php echo $sub_procedure_counter;?>" type="text" required></td>
								<td><input value="<?php echo $val['sub_procedures_code']?>" readonly="readonly" id="sub_procedures_code_<?php echo $sub_procedure_counter;?>" class="cons-cls-1 consumables_serial_1 form-control" name="sub_procedures_code_<?php echo $sub_procedure_counter;?>" type="text" required></td>
								<td><input value="<?php echo $val['sub_procedures_price']?>" readonly="readonly" id="sub_procedures_price_<?php echo $sub_procedure_counter;?>" class="cons-cls-1 consumables_serial_1 form-control" name="sub_procedures_price_<?php echo $sub_procedure_counter;?>" type="text" required></td>
								<td><input value="<?php echo $val['sub_procedures_discount']?>" readonly="readonly" id="" class="cons-cls-1 consumables_serial_1 form-control" name="" type="text" required></td>
								<td><input value="<?php echo $val['sub_procedures_paid_price']?>" readonly="readonly" id="" class="cons-cls-1 consumables_serial_1 form-control" name="" type="text" required></td>
								<td><input value="" id="sub_procedures_paid_price_<?php echo $sub_procedure_counter;?>" totalpendingbalance="<?php echo $pendingbalance; ?>" class="cons-cls-1 consumables_serial_1 form-control" name="sub_procedures_paid_price_<?php echo $sub_procedure_counter;?>" type="text" onchange="pending_amount_update(this)" required></td>
								<td><input type="checkbox" class="statuss" name="record"></td>
							</tr>
							 <?php $sub_procedure_counter++; } ?>
						</tbody>
					</table> 
			    </section>
			  
			  <div class="row">
          
            <div class="form-group col-sm-6 col-xs-12 role">
                        <label for="statuss">Payment mode (Required)</label>
                        <select name="payment_method" id="payment_method" required>
                            <option value="">Select</option>
                            <option value="neft" class="indian" mode="NEFT">NEFT</option>
                            <option value="rtgs" class="indian" mode="RTGS">RTGS</option>
                            <option value="cash" mode="Cash">Cash</option>
                            <option value="cheque" mode="Cheque">Cheque</option>
                            <option value="card" class="indian" mode="Card">Card</option>
                            <option value="upi" class="indian" mode="UPI">UPI</option>
                            <option value="insurance" class="indian" mode="Insurance">Insurance</option>
                            <option value="international_card" class="foreign" mode="International Card">International Card</option>
							<option value="wallet" mode="Wallet">Wallet</option>		
                        </select>
              </div>
              
            <div class="form-group col-sm-6 col-xs-12" id="transaction">
                       <label for="item_name">Transaction ID (Optional)</label>
                       <input value="" placeholder="Transaction ID" id="transaction_id" name="transaction_id" type="text" class="form-control validate">
                       <input type="file" name="transaction_img" id="transaction_img"  />
                    </div>
				  <div class="form-group col-sm-6 col-xs-12 hospital_id_section role">
                  <label for="item_name">Center Source</label>
                  <select name="biller" class="required_value" id="biller" required>
                        <option value="">Select</option>
                        <option value="Noida">Noida</option> 
                        <option value="Gurgaon">Gurgaon</option> 
                        <option value="Green Park">Green Park</option> 
                        <option value="Srinagar">SRINAGAR</option> 
                        <option value="Ghaziabad">Ghaziabad</option> 
                    </select>
				  
                </div>
          </div>
          
          <div class="row">           
            <div class="form-group col-sm-6 col-xs-12 role">
                <input type="hidden" id="billing_from" name="billing_from" value="<?php echo $_SESSION['logged_billing_manager']['center']?>" />
				<input type="hidden" id="billing_at" name="billing_at" value="<?php echo $_SESSION['logged_billing_manager']['center']?>" />
				<input type="hidden" id="employee_number" name="employee_number" value="<?php echo $_SESSION['logged_billing_manager']['employee_number']?>" />
            </div>
          </div>
          
       	  <div class="clearfix"></div>
          <div class="form-group col-sm-12 col-xs-12">
          <input type="submit" id="submitbutton" class="btn btn-large" value="Submit" />
        </div>
			   </div>
			   </div>
			   </div>
			   </div>
			  </form>
    <?php } ?>

<script>
function printDiv() 
{
  $('.hide_print').hide();
  $('#print_this_section').css('display', 'block');
  $('tr#medinfologo_tr').css('display', 'table-row');
  var divToPrint=document.getElementById('print_this_section');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}

function sendonwhatsapp() 
{
    var data = {'iic_id':<?php echo $data['patient_id']; ?>, 'html': $("#print_this_section").html()};
    $('#whatsappmessg').hide();
	$.ajax({
		url: '<?php echo base_url('accounts/billhtmltopdf')?>',
		data: data,
		dataType: 'json',
		method:'post',
		success: function(data)
		{
		    if(data.status == 1){
                $('#whatsappmessg').empty().append('Bill has been sent to patient!');
		    }else{
		        $('#whatsappmessg').empty().append('Oops, something went wrong!');
		    }
		    $('#whatsappmessg').show();
		} 
	});
}

 $(document).ready(function(){
		 
		// Find and remove selected table rows
        $(".delete-consumables-row").click(function(){
            $("table tbody").find('input[name="record"]').each(function(){
            	if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
			});
	    });
    });
	
	 // $('.consumables_quantity').on("keyup", function() {
	function consumables_quantity_update(el) {
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

function pending_amount_update(el) {
    var totalpendingbalance = $(el).attr('totalpendingbalance');
    var pendingbalance = $(el).val();

    if (pendingbalance > 0) {
        if (parseInt(totalpendingbalance) < parseInt(pendingbalance)) {
            alert('Wallet Amount must be less than or equal to wallet payment');
            $(el).val("0");
            return false;
        }
    }
}

</script>

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