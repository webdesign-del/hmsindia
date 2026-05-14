<?php
	$all_method =&get_instance();
 //var_dump($data);die;
	$details = unserialize($data['data']);
	// var_dump($details);die;
?>
<form class="col-sm-12 col-xs-12" id="billing_form" method="post" action="<?php echo base_url();?>accounts/bill_consultation" >
  <input type="hidden" name="action" value="bill_consultation" />
  <input type="hidden" name="patient_id" value="<?php echo $data['patient_id']?>" />
  <input type="hidden" name="reason_of_visit" value="<?php echo $data['reason_of_visit']?>" />
  <input type="hidden" name="request" value="<?php echo $request; ?>" />
   <?php if(isset($_SESSION['logged_accountant'])){ ?>
	   <input type="hidden" name="billing_at" value="<?php echo $_SESSION['logged_accountant']['center']?>" />
   <?php }else{ ?>
  	   <input type="hidden" name="billing_at" value="IndiaIVF" />
   <?php } ?>
   
  <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku" id="consultation_details">
      <div class="panel-heading">
        <h3 class="heading">Consultation Billing </h3>
      </div>
      <div class="panel-body profile-edit">
      <p id="msg_area" class="delete"></p>
        <p>
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Doctor </label>
               	<input value="<?php echo $details['doctor_id']; ?>" readonly="readonly" id="doctor_id" name="doctor_id" type="hidden" class="form-control validate" required>
                <p><?php $doctor = $all_method->get_doctor_name($details['doctor_id']); echo 'Dr. '.$doctor; ?></p>
            </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Date </label>
                <input value="<?php echo date("Y-m-d H:i:s"); ?>" placeholder="Date" readonly="readonly" id="on_date" name="on_date" type="hidden" class="form-control validate" required>
                 <p><?php echo date("Y-m-d H:i:s"); ?></p>
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Receipt number </label>
                <input value="<?php echo getGUID(); ?>" placeholder="Receipt number" readonly="readonly" id="receipt_number" name="receipt_number" type="hidden" class="form-control validate" required>
                <p><?php echo getGUID(); ?></p>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Consultation fees </label>
                <input value="<?php echo $details['fees']; ?>" readonly="readonly" id="fees" name="fees" type="hidden" class="form-control validate" required>
                <p><?php echo $details['fees']; ?></p>
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Payment received (Required)</label>
                <input value="" placeholder="Payment received" id="payment_done" name="payment_done" type="number" class="form-control validate" required>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Remaining amount (Required)</label>
               <input value="" placeholder="Remaining amount" readonly="readonly" id="remaining_amount" name="remaining_amount" type="text" class="form-control validate" required>
           </div>
         </div>        
        
        <div class="row">            
           <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Payment mode (Required)</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="">Select</option>
               		<option value="neft" mode="NEFT">NEFT</option>
               		<option value="rtgs" mode="RTGS">RTGS</option>
               		<option value="cash" mode="Cash">Cash</option>
               		<option value="card" mode="Card">Card</option>
               		<option value="insurance" mode="Insurance">Insurance</option>
                </select>
            </div>
            
            <div class="form-group col-sm-6 col-xs-12" id="transaction" style="display:none;">
               <label for="item_name">Transaction ID (Required)</label>
               <input value="" placeholder="Transaction ID" id="transaction_id" name="transaction_id" type="text" class="form-control validate" required>
            </div>
         </div>
         
          <div class="row">
            <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Billing ID</label>
               <input value="" placeholder="Billing ID" id="billing_id" name="billing_id" type="text" class="form-control validate">
            </div>
            <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Consultation ID</label>
               <input value="" placeholder="Consultation ID" id="consultation_id" name="consultation_id" type="text" class="form-control validate">
            </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Billing From (Required)</label>
                <select name="billing_from" required>
                    <option value="">Select</option>
                    <?php if(isset($_SESSION['logged_accountant'])){ $center = $all_method->get_center_name($_SESSION['logged_accountant']['center']); ?>
                    	<option value="<?php echo $_SESSION['logged_accountant']['center']; ?>"><?php echo $center; ?></option>
                    <?php } ?>
                    <option value="IndiaIVF">IndiaIVF</option>       
                </select>
            </div>
         </div>
        
         <div class="clearfix"></div>
	     <div class="form-group col-sm-12 col-xs-12">
            <a class="btn btn-large" id="create_billing" href="javascript:void(0);">Create Billing</a>
         </div>
      </div>
      </p>
    </div>
  </div>
</form>

<script type="text/javascript">
    $(document).on('change',"#payment_method",function(e) {
        $('#transaction_id').empty();
		var method = $(this).val();
		if(method == 'cash' || method == ''){
			 $('#transaction_id').prop('required',false);
			 $('#transaction').hide();		
		}else{
			 $('#transaction_id').prop('required',true);
			 $('#transaction').show();
		}
		
    });
	
    $(document).on('keyup',"#payment_done",function(e) {
		$('#remaining_amount').empty();
		var fees = $('#fees').val();
		var payment_done = $(this).val();
		var remaining_amount = fees-payment_done;
		if(remaining_amount < 0){
			$('#payment_done').val('');
			$('#remaining_amount').val('');
		}
		else{
			$('#remaining_amount').val(remaining_amount);
		}
    });
	
	$(document).on('click',"#create_billing",function(e) {
		$('#msg_area').empty();
		$('#doctor_id_text').empty();
		$('#fees_text').empty();
		$('#payment_done_text').empty();
		$('#remaining_amount_text').empty();
		$('#payment_method_text').empty();	
		$('#transaction_id_text').empty();	
		$('#billing_id_text').empty();	
		$('#consultation_id_text').empty();
		
		var doctor = $('#doctor_id').val();
		var payment_done = $('#payment_done').val();
		var payment_method = $('#payment_method').val();
		
		if(payment_method != 'cash'){
				var transaction_id = $('#transaction_id').val();
				if(transaction_id == ''){
					$('#msg_area').append('One or more fields are empty !')
				}else{
					if(doctor == '' || payment_done == '' || payment_method == ''){$('#msg_area').append('One or more fields are empty !')}else{
						$('form#billing_form').submit()
					}
				}
		}else{
			if(doctor == '' || payment_done == '' || payment_method == ''){$('#msg_area').append('One or more fields are empty !')}else{
					$('form#billing_form').submit()
			}
		}
    });	
</script>