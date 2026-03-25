<?php $all_method =&get_instance(); ?>
<?php 
	    $ci = &get_instance();
		$ci->load->database();
		$db_prefix = $ci->config->config['db_prefix'];
		$patient_sql = "Select * from ".$db_prefix."patients where  patient_id='".$patient_data['patient_id']."'";
        $patient_q = $ci->db->query($patient_sql);
        $patient_result = $patient_q->result_array();
		$patient_id = $patient_result[0]['patient_id'];

		$consultation_result = $procedure_result = $investigation_result = $medicine_result =  $registation_result = $remaining_billing = $bill_arr = $bill_total = array();
		$procedure_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_procedure where status='cancel' and patient_id='".$patient_data['patient_id']."'";
        $procedure_q = $ci->db->query($procedure_sql);
        $procedure_result = $procedure_q->result_array();

		$consultation_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."consultation where status='adjust' and patient_id='".$patient_data['patient_id']."'";
        $consultation_q = $ci->db->query($consultation_sql);
        $consultation_result = $consultation_q->result_array();
		
		$registation_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."registation where status='adjust' and patient_id='".$patient_data['patient_id']."'";
        $registation_q = $ci->db->query($registation_sql);
        $registation_result = $registation_q->result_array();

		$investigation_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_investigations where status='cancel' and patient_id='".$patient_data['patient_id']."'";
        $investigation_q = $ci->db->query($investigation_sql);
        $investigation_result = $investigation_q->result_array();
		
		$medicine_sql = "Select receipt_number, payment_done, fees, remaining_amount, billing_at from ".$db_prefix."patient_medicine where status='cancel' and patient_id='".$patient_data['patient_id']."'";
        $medicine_q = $ci->db->query($medicine_sql);
        $medicine_result = $medicine_q->result_array();

		$total = 0;
        $done_sql = "Select sum(payment_done) as payment_done from ".$db_prefix."patient_payments where patient_id='".$patient_data['patient_id']."' AND status='3'";
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
		
		foreach($done_result as $key => $val){
			$bill_arr[] = $val['payment_done'];
		}

        //wallete
		$consultation_wallet_result = $procedure_wallet_result = $investigation_wallet_result = $partialpayments_wallet_result = $medicine_wallet_result = $wallet_remaining_billing = $wallet_arr = $wallet_bill_total = array();
		$procedure_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_procedure where wallet_payment > 0 and patient_id='".$patient_data['patient_id']."'";
        $procedure_wallet_q = $ci->db->query($procedure_wallet_sql);
        $procedure_wallet_result = $procedure_wallet_q->result_array();

		$consultation_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."consultation where wallet_payment > 0 and patient_id='".$patient_data['patient_id']."'";
        $consultation_wallet_q = $ci->db->query($consultation_wallet_sql);
        $consultation_wallet_result = $consultation_wallet_q->result_array();

		$investigation_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_from, billing_at from ".$db_prefix."patient_investigations where wallet_payment > 0 and patient_id='".$patient_data['patient_id']."'";
        $investigation_wallet_q = $ci->db->query($investigation_wallet_sql);
        $investigation_wallet_result = $investigation_wallet_q->result_array();
		
		$medicine_wallet_sql = "Select receipt_number, payment_done, wallet_payment, fees, remaining_amount, billing_at from ".$db_prefix."patient_medicine where wallet_payment > 0 and patient_id='".$patient_data['patient_id']."'";
        $medicine_wallet_q = $ci->db->query($medicine_wallet_sql);
        $medicine_wallet_result = $medicine_wallet_q->result_array();

        $partialpayments_wallet_sql = "Select refrence_number, payment_done, wallet_payment, billing_from, billing_at from ".$db_prefix."patient_payments where wallet_payment > 0 and patient_id='".$patient_data['patient_id']."'";
        $partialpayments_wallet_q = $ci->db->query($partialpayments_wallet_sql);
        $partialpayments_wallet_result = $partialpayments_wallet_q->result_array();

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
		
		//foreach($done_wallet_result as $key => $value){
		//	$wallet_arr[] = $value['wallet_payment'];
		//}

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
<div class="panel-heading">
    <h3 class="heading">Edit patient</h3>
    <a href="<?php echo base_url(); ?>accounts/export_wallet_data/<?php echo $patient_data['patient_id']; ?>" class="btn btn-success pull-right">
        <i class="fa fa-download"></i> Export to Excel
    </a>
    <p style="margin-top:20px;color:red;">Wallets Amount : ...</p>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <a href="<?php echo base_url('accounts/export_wallet_used_history/' . $patient_data['patient_id']); ?>" 
           class="btn btn-success">
           <i class="fa fa-file-excel-o"></i> Download CSV
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="11" style="text-align:center; font-size:18px;">Wallet History</th>
            </tr>
            </thead>
        </table>
</div>
<form class="col-sm-12 col-xs-12" method="post" action="" enctype="multipart/form-data" >
    <input type="hidden" name="action" value="wallet_balance" />
    <input type="hidden" name="patient_id" value="<?php echo $patient_data['patient_id']; ?>" id="patient_id" />
    
    <div class="row">
      <div class="col-sm-12 col-xs-12 panel panel-piluku">
        <div class="panel-heading">
          <h3 class="heading">Edit patient</h3><p style="margin-top:20px;color:red;">Wallets Amount : <a href="<?php echo base_url(); ?>patients/edit/<?php echo $patient_data['patient_id']; ?>"><?php echo $balance; ?></a></p>
          <p id="msg_area" style="margin-top:30px; display:none; padding:5px 15px;" class="error"></p>
        </div>
        <div class="panel-body profile-edit">
          <p>
       <div id="add_section"> 
        <div class="row">
		   <div class="form-group col-sm-3 col-xs-12" align="center"></div>                               	
           <div class="form-group col-sm-6 col-xs-12" align="center">
                <label for="item_name">IIC ID </label>
                <h3><?php echo $patient_data['patient_id']; ?></h3>
           </div>
		   <div class="form-group col-sm-3 col-xs-12" align="center"></div>
         </div>
		 	<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="">
              <thead>
                <tr>
                  <th>Receipt number</th>
                  <th>Name</th>
				  <th>On Date</th>
                  <th>Total package</th>
                  <th>Discounted package</th>
				  <th>Paid amount</th>
				  <th>Used amount</th>
                  <th>Credit amount</th>
                  <th>Cancle Date</th>
                  <th>Status</th>
				  <th>CN Invoice</th>
				</tr>
              </thead>
			   <?php 
	        $sql = "Select * from ".$this->config->item('db_prefix')."consultation where patient_id='".$patient_data['patient_id']."' and status='adjust'"; 
			   $query = $this->db->query($sql);
                  $select_result = $query->result(); 
					foreach ($select_result as $res_val){ ?>
              <tbody id="procedure_result">
			    <tr class="odd gradeX">
					<td><a href="<?php echo base_url(); ?>accounts/consultation_wallet/<?php echo $res_val->receipt_number; ?>?t=consultation"><?php echo $res_val->receipt_number; ?></a></td>
                	<td>Consultation</td>
					<td><?php echo $res_val->on_date; ?></td>
					<td><?php echo $res_val->totalpackage; ?></td>
					<td><?php echo $res_val->fees; ?></td>
					<td><?php echo $res_val->payment_done; ?></td>
					<td></td>
					<td></td>
                   	<td><?php echo $res_val->modified_on; ?></td>
					<td><?php echo $res_val->status; ?></td>
					<td><?php echo $res_val->cn_invoice; ?></td>
				</tr>
              </tbody>
			  <?php } ?>
			  
			     <?php 
	        $sql = "Select * from ".$this->config->item('db_prefix')."registation where patient_id='".$patient_data['patient_id']."' and status='adjust'"; 
			   $registation_query = $this->db->query($sql);
                  $registation_select_result = $registation_query->result(); 
					foreach ($registation_select_result as $registation_val){ ?>
              <tbody id="procedure_result">
			    <tr class="odd gradeX">
					<td><a href="<?php echo base_url(); ?>accounts/consultation_wallet/<?php echo $registation_val->receipt_number; ?>?t=consultation"><?php echo $registation_val->receipt_number; ?></a></td>
                	<td>Registation</td>
					<td><?php echo $registation_val->on_date; ?></td>
					<td><?php echo $registation_val->totalpackage; ?></td>
					<td><?php echo $registation_val->fees; ?></td>
					<td><?php echo $registation_val->payment_done; ?></td>
					<td></td>
					<td></td>
                   	<td><?php echo $registation_val->modified_on; ?></td>
					<td><?php echo $registation_val->status; ?></td>
					<td><?php echo $registation_val->cn_invoice; ?></td>
				</tr>
              </tbody>
			  <?php } ?>
			  
			  <?php 
	        $sql2 = "Select * from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_data['patient_id']."' and status='cancel'"; 
			   $query = $this->db->query($sql2);
                  $select_result2 = $query->result(); 
					foreach ($select_result2 as $res_val2){ ?>
              <tbody id="procedure_result">
			    <tr class="odd gradeX">
                  <td><a href="<?php echo base_url(); ?>accounts/wallet/<?php echo $res_val2->receipt_number; ?>?t=procedure"><?php echo $res_val2->receipt_number; ?></a></td>
				  <td>Procedure</td>
				  <td><?php echo $res_val2->on_date; ?></td>
                  <td><?php echo $res_val2->totalpackage; ?></td>
                  <td><?php echo $res_val2->fees; ?></td>
                  <td><?php echo $res_val2->payment_done; ?></td>
				  <td><?php echo $res_val2->used_amount; ?></td>
				  <td><?php echo $res_val2->used_amount; ?></td>
                  <td><?php echo $res_val2->modified_on; ?></td>
                  <td><?php echo $res_val2->status; ?></td>
				  <td><?php echo $res_val2->cn_invoice; ?></td>
				</tr>
              </tbody>
			  <?php } ?>
        <?php 
	        $sql_par = "Select * from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_data['patient_id']."' and status='3'"; 
			   $query = $this->db->query($sql_par);
                  $select_result_par = $query->result(); 
					foreach ($select_result_par as $res_val_par){ ?>
              <tbody id="procedure_result">
			    <tr class="odd gradeX">
                  <td><a href="<?php echo base_url(); ?>accounts/wallet/<?php echo $res_val_par->billing_id; ?>?t=procedure"><?php echo $res_val_par->billing_id; ?></a></td>
				  <td>Partial Payments</td>
				  <td><?php echo $res_val_par->on_date; ?></td>
                  <td><?php echo $res_val_par->totalpackage; ?></td>
                  <td><?php echo $res_val_par->fees; ?></td>
                  <td><?php echo $res_val_par->payment_done; ?></td>
				  <td><?php echo $res_val_par->used_amount; ?></td>
				  <td><?php echo $res_val_par->used_amount; ?></td>
                  <td><?php echo $res_val_par->modified_on; ?></td>
                  <td>cancel</td>
				  <td><?php echo $res_val_par->cn_invoice; ?></td>
				</tr>
              </tbody>
			  <?php } ?>
<?php 
	        $sql3 = "Select * from ".$this->config->item('db_prefix')."patient_medicine where patient_id='".$patient_data['patient_id']."' and status='cancel'"; 
			   $query = $this->db->query($sql3);
                  $select_result3 = $query->result(); 
					foreach ($select_result3 as $res_val3){ ?>
              <tbody id="procedure_result">
			  
			    <tr class="odd gradeX">
                  <td><td><a href="<?php echo base_url(); ?>accounts/details/<?php echo $res_val3->receipt_number; ?>?t=procedure"><?php echo $res_val3->receipt_number; ?></a></td>
				  <td>Medicine</td>
				  <td><?php echo $res_val3->on_date; ?></td>
                  <td><?php echo $res_val3->totalpackage; ?></td>
                  <td><?php echo $res_val3->fees; ?></td>
                  <td><?php echo $res_val3->payment_done; ?></td>
				  <td></td>
				  <td></td>
                  <td><?php echo $res_val3->modified_on; ?></td>
                  <td><?php echo $res_val3->status; ?></td>
				  <td><?php echo $res_val3->cn_invoice; ?></td>
				</tr>
              </tbody>
			  <?php } ?>
              <thead>
              <tr><th><h3><br></h3></th></tr> 
              <tr><th colspan="11"><h3 style="text-align:center;">Wallet History</h3></th></tr>
<tr>
  <th>Receipt number</th>
  <th>Name</th>  
  <th>On Date</th>
  <th>Total package</th>
  <th>Discounted package</th>  
  <th>Paid amount</th>
  <th>Used amount</th>
  <th>Credit amount</th>
  <th>Cancle Date</th>
  <th>Status</th>
  <th>CN Invoice</th>
</tr>
</thead>
              <?php 
	        $sql2 = "Select * from ".$this->config->item('db_prefix')."patient_procedure where patient_id='".$patient_data['patient_id']."' and payment_method='wallet'"; 
			   $query = $this->db->query($sql2);
                  $select_result2 = $query->result(); 
					foreach ($select_result2 as $res_val2){ ?>
              <tbody id="procedure_result">
			    <tr class="odd gradeX">
                  <td><a href="<?php echo base_url(); ?>accounts/wallet/<?php echo $res_val2->receipt_number; ?>?t=procedure"><?php echo $res_val2->receipt_number; ?></a></td>
				  <td>Procedure</td>
				  <td><?php echo $res_val2->on_date; ?></td>
                  <td><?php echo $res_val2->totalpackage; ?></td>
                  <td><?php echo $res_val2->fees; ?></td>
                  <td><?php echo $res_val2->payment_done; ?></td>
				  <td><?php echo $res_val2->used_amount; ?></td>
				  <td><?php echo $res_val2->used_amount; ?></td>
                  <td><?php echo $res_val2->modified_on; ?></td>
                  <td><?php echo $res_val2->status; ?></td>
				  <td><?php echo $res_val2->cn_invoice; ?></td>
				</tr>
              </tbody>
			  <?php } ?>
              <?php 
	        $sql_par = "Select * from ".$this->config->item('db_prefix')."patient_payments where patient_id='".$patient_data['patient_id']."' and payment_method='wallet'"; 
			   $query = $this->db->query($sql_par);
                  $select_result_par = $query->result(); 
					foreach ($select_result_par as $res_val_par){ ?>
              <tbody id="procedure_result">
			    <tr class="odd gradeX">
                  <td><a href="<?php echo base_url(); ?>accounts/wallet/<?php echo $res_val_par->billing_id; ?>?t=procedure"><?php echo $res_val_par->billing_id; ?></a></td>
				  <td>Partial Payments</td>
				  <td><?php echo $res_val_par->on_date; ?></td>
                  <td></td>
                  <td></td>
                  <td><?php echo $res_val_par->payment_done; ?></td>
				  <td></td>
				  <td></td>
                  <td><?php echo $res_val_par->modified_on; ?></td>
                  <td><?php echo $res_val_par->status; ?></td>
				  <td><?php echo $res_val_par->cn_invoice; ?></td>
				</tr>
              </tbody>
			  <?php } ?>
            </table>
          </div>
		  <div class="row">            
            <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Package Code (Required)</label>
                <input type="text" id="package_code" name="package_code" required class="form-control validate">
            </div>
           
            <div class="form-group col-sm-6 col-xs-12">
    <label for="item_name">Consultation Charge (Required)</label>
    <input type="text" id="consultation_fee" name="consultation_fee" required class="form-control validate" oninput="checkTotalAmount()">
</div>

<div class="form-group col-sm-6 col-xs-12">
    <label for="item_name">USG Scan Charge (Required)</label>
    <input type="text" id="usg_scan_charge" name="usg_scan_charge" required class="form-control validate" oninput="checkTotalAmount()">
</div>

<div class="form-group col-sm-6 col-xs-12">
    <label for="item_name">Consumable Charges (Required)</label>
    <input type="text" id="consumable_charges" name="consumable_charges" required class="form-control validate" oninput="checkTotalAmount()">
</div>

<div class="form-group col-sm-6 col-xs-12">
    <label for="item_name">File And Registration Charge (Required)</label>
    <input type="text" id="file_registation_charge" name="file_registation_charge" required class="form-control validate" oninput="checkTotalAmount()">
</div>

<div class="form-group col-sm-6 col-xs-12">
    <label for="item_name">Refund Amount (Required)</label>
    <input type="text" id="refund_amount" name="refund_amount" required class="form-control validate" oninput="checkTotalAmount()">
    <input value="<?php echo date("Y-m-d"); ?>" type="hidden" id="on_date" name="on_date" required class="form-control validate">
</div>

<div id="error_message" style="color: red; display: none;">
    The total amount exceeds the wallet balance of <?php echo $balance; ?>.
</div>
         </div> 
  		<div class="clearfix"></div>
	     <div class="form-group col-sm-12 col-xs-12">
	        <input type="submit" id="submitbutton" class="btn btn-large" value="Update" />
         </div>
         </div>
         
        </p>
      </div>
    </div>
</form>

<script>
function checkTotalAmount() {
    var consultationFee = parseFloat(document.getElementById("consultation_fee").value) || 0;
    var usgScanCharge = parseFloat(document.getElementById("usg_scan_charge").value) || 0;
    var consumableCharges = parseFloat(document.getElementById("consumable_charges").value) || 0;
    var fileRegistrationCharge = parseFloat(document.getElementById("file_registation_charge").value) || 0;
    var refundAmount = parseFloat(document.getElementById("refund_amount").value) || 0;

    // Calculate the total amount
    var totalAmount = consultationFee + usgScanCharge + consumableCharges + fileRegistrationCharge + refundAmount;

    // Wallet balance from PHP
    var walletBalance = parseFloat('<?php echo $balance; ?>');

    // Check if total amount exceeds wallet balance
    if (totalAmount > walletBalance) {
        document.getElementById("error_message").style.display = "block";
    } else {
        document.getElementById("error_message").style.display = "none";
    }
}
</script>