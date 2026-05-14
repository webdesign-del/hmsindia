<?php $all_method =&get_instance(); ?>
<form class="col-sm-12 col-xs-12" method="post" action="" enctype="multipart/form-data" >
  <input type="hidden" name="action" value="add_consultation" />
  <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $session_data['paitent_id']?>" />
  <input type="hidden" name="hospital_id" id="hospital_id" value="<?php echo $session_data['hospital_id']?>" />
  <input type="hidden" name="reason_of_visit" value="<?php echo $session_data['reason_of_visit']?>" />
  <input type="hidden" name="billing_from" value="<?php echo $session_data['billing_from']?>" />
  <input type="hidden" name="billing_at" value="<?php echo $_SESSION['logged_billing_manager']['center']?>" />
  <input type="hidden" id="billing_type" value="consultation" />
  <input type="hidden" name="biller_id" value="<?php echo $_SESSION['logged_billing_manager']['employee_number']?>" />
  
  <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku" id="consultation_details">
      <div class="panel-heading">
        <h3 class="heading">Consultation Details </h3>
      </div>
      <div class="panel-body profile-edit">
      <p id="msg_area" class="delete"></p>
        <p>
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Doctor (Required)</label>
                <select name="doctor_id" id="doctor_id" required>
                    <option value="">Select</option>
                    <?php foreach($doctors as $key => $val){?>
                  		<option value="<?php echo $val['ID']; ?>" doc="Dr. <?php echo $val['name']; ?>">Dr. <?php echo $val['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Date(Required)</label>
                <input value="<?php echo date("Y-m-d H:i:s"); ?>" placeholder="Date" readonly="readonly" id="on_date" name="on_date" type="text" class="form-control validate" required>
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Receipt number (Required)</label>
                <input value="<?php echo getGUID(); ?>" placeholder="Receipt number" readonly="readonly" id="receipt_number" name="receipt_number" type="text" class="form-control validate" required>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Consultation fees (Required)</label>
                 <input value="" name="totalpackage" placeholder="Consultation fees" readonly="readonly" class="dhee" id="fees" type="hidden" class="form-control validate" required>
                 <input value="" placeholder="Consultation fees" readonly="readonly" id="after_discount" name="fees" type="text" class="form-control validate" required>
           </div>
         </div>
     
         
         <div class="row">
         	 <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Payment discount (Required)</label>
                <select id="payment_discount" required>
               		<option value="">Select</option>
                    <option value="free">Free</option>
               		<option value="discount">Discount</option>
                    <option value="no discount">No discount</option>
                </select>
            </div>
         </div>
        
        
        <div class="row" id="discount_avail" style="display:none;">
            <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Discount amount (Required)</label>
               <input value="0" placeholder="Discount amount" id="discount_amount" name="discount_amount" type="text" class="form-control validate">
               <input value="<?php echo $_SESSION['logged_billing_manager']['allow_discount'];?>" id="allow_discount" type="hidden" class="form-control validate" required>
               <p id="show_disc_app" style="display:none;">Given discount is more than allowed, <a href="javascript:void(0);" accountant="<?php echo $_SESSION['logged_billing_manager']['username'];?>" id="get_discount_approval">click here</a> for admin approval.</p> 
            </div>
            
            <div class="form-group col-sm-6 col-xs-12">
           		<div id="center_share_div">
                <label for="item_name">Reason of discount(Required)</label>
                <input value="" placeholder="Reason of discount" id="reason_of_discount" name="reason_of_discount" type="text" class="form-control validate">
                </div>
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
                   	<?php if($session_data['nationality'] == 'indian'){?>
               			<option value="neft" mode="NEFT">NEFT</option>
               			<option value="rtgs" mode="RTGS">RTGS</option>
        	       		<option value="card" mode="Card">Card</option>
    	           		<option value="insurance" mode="Insurance">Insurance</option>
                    <?php }else{ ?>
	                    <option value="international_card" mode="International Card">International Card</option>
                    <?php } ?>
                    <option value="cash" mode="Cash">Cash</option>
                    <option value="cheque" mode="Cheque">Cheque</option>                    
                </select>
            </div>
            
            <div class="form-group col-sm-6 col-xs-12" id="transaction" style="display:none;">
               <label for="item_name">Reference no. (Required)</label>
               <input value="" placeholder="Reference no." id="transaction_id" name="transaction_id" type="text" class="form-control validate" required>
               <label>Upload screenshot/document here</label>
               <input type="file" name="transaction_img" id="transaction_img"  />
            </div>
         </div>
         
          <div class="row">
	        <?php if($session_data['billing_from'] != 'IndiaIVF'){?>
            <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Billing ID (Optional)</label>
               <input value="" placeholder="Billing ID" id="billing_id" name="billing_id" type="text" class="form-control validate">
            </div>
            <?php } ?>
            <div class="form-group col-sm-6 col-xs-12 role">
               <label for="item_name">Consultation ID</label>
               <select id="consultation_id" name="consultation_id">
               		<option value="">Consultation ID</option>
                    <?php echo $all_method->get_code('consultation');?>
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
    
    <div class="col-sm-12 col-xs-12 panel panel-piluku" style="display:none;" id="consultation_preview">
      <div class="panel-heading">
        <h3 class="heading">Billing Summary</h3>
      </div>
      <div class="panel-body profile-edit">
      <p id="msg_area" class="delete"></p>
        <p>
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Doctor (Required)</label>
                <p id="doctor_id_text"></p>
            </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Date (Required)</label>
                <p id="on_date_text"><?php echo date("Y-m-d H:i:s"); ?></p>
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Receipt number (Required)</label>
                <p id="receipt_number_text"><?php echo getGUID(); ?></p>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Consultation fees (Required)</label>
                <p id="fees_text"></p>
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Payment received (Required)</label>
                <p id="payment_done_text"></p>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Remaining amount (Required)</label>
                <p id="remaining_amount_text"></p>
           </div>
         </div>        
        
        <div class="row">            
           <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Payment mode (Required)</label>
                <p id="payment_method_text"></p>
            </div>
             <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Transaction ID(Required)</label>
                <p id="transaction_id_text"></p>
            </div>
         </div>
         
          <div class="row">
            <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Billing ID</label>
                <p id="billing_id_text"></p>
            </div>
             <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Consultation ID</label>
                <p id="consultation_id_text"></p>
            </div>
          </div>
          
          <div class="row">
            <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Hospital ID</label>
                <p id="hospital_id_text"></p>
            </div>
             <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Payment discount</label>
                <p id="payment_discount_text"></p>
            </div>
          </div>
          
          <div class="row discount_div">
            <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Discount amount</label>
                <p id="discount_amount_text"></p>
            </div>
             <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Reason of discount</label>
                <p id="reason_of_discount_text"></p>
            </div>
          </div>
          
         <div class="clearfix"></div>
	     <div class="form-group col-sm-12 col-xs-12">
            <a class="btn btn-large" id="edit_billing" href="javascript:void(0);">Edit Billing</a>
            <input type="submit" id="submitbutton" class="btn btn-large" value="Create Billing" />
         </div>
      </div>
      </p>
    </div>
  </div>
</form>

<script type="text/javascript">

	$(document).on('change',"#payment_discount",function(e) {
		$("input#after_discount").val($("input#fees").val());
		$("input#payment_done").val('');
		$("input#remaining_amount").val('');
		$("input#discount_amount").val('');
		$("input#reason_of_discount").val('');
		$("input#discount").prop('required',false);
		$("input#reason_of_discount").prop('required',false);
		$('#discount_avail').hide();
		if($(this).val() == 'discount'){
			$("input#discount").prop('required',true);
			$("input#reason_of_discount").prop('required',true);
			$('#discount_avail').show();
		}else if($(this).val() == 'free'){
			$("#after_discount").val(0);
			$('#payment_done').val(0);
			$('#remaining_amount').val(0);
		}
	});

	$(document).on('keyup',"#discount_amount",function(e) {
		$('#payment_done').val('');
		$('#remaining_amount').val('');
		var fees = parseFloat($('#fees').val());
		var allowd = parseFloat($('#allow_discount').val());
		var discount_amount = parseFloat($(this).val());
		discount_amount = (discount_amount)?discount_amount:0;
		if(discount_amount == ''){ $(this).val(0); }
 		//console.log(fees+' ----- '+allowd+' ----- '+discount_amount);
		if(parseFloat(discount_amount) > allowd){
				$('#fees').val('');
				$('#fees').val(parseFloat(fees));
				$('#after_discount').val(parseFloat(fees));
				$('#create_billing').hide();
				$('#show_disc_app').show();				
		}else{
			if(parseFloat(discount_amount) <= parseFloat(allowd)){
				var listPrice = parseFloat(fees);
				var discount  = parseFloat(discount_amount);
				console.log(listPrice+' ----- '+discount);
				<!--var remaining_amount =  (listPrice - ( listPrice * discount / 100 ));-->
				var remaining_amount = listPrice - discount;
				if(remaining_amount < 1){
					$('#payment_done').val('');
					$('#fees').val('');
					$(this).val('');
					$('#fees').val(parseFloat(fees));
					$('#after_discount').val(parseFloat(fees));
				}else{//console.log(remaining_amount);
					$('#after_discount').val(parseFloat(remaining_amount));
				}
				$('#show_disc_app').hide();
				$('#create_billing').show();
			}
			else{
				$('#fees').val(parseFloat(fees));
				$('#after_discount').val(parseFloat(fees));
				$('#create_billing').hide();
				$('#show_disc_app').show();				
			}
		}
    });
	
   $(document).on('change',"#doctor_id",function(e) {
		$('#loader_div').show();
        $('#msg_area').empty();
	    $('#fees').empty();
		$('#payment_done').val('');
		$('#discount_amount').val('');
		$('#remaining_amount').val('');
		$('#after_discount').empty();	
        var doctor_id = $(this).val();
		if(doctor_id != ''){
			$.ajax({
				url: '<?php echo base_url('billings/doctor_fees')?>',
				data: {doctor_id : doctor_id, biller_id:<?php echo $_SESSION['logged_billing_manager']['employee_number']?>,patient_id:<?php echo $session_data['paitent_id']?>},
				dataType: 'json',
				method:'post',
				success: function(data)
				{
					$('#fees').empty().val(data.fees);
					$('#after_discount').empty().val(data.fees);
					$('#allow_discount').empty().val(data.allowed_discount);
					$('#loader_div').hide();
				} 
		   });
	  }
    });
	
    $(document).on('change',"#payment_method",function(e) {
        $('#transaction_id').empty();
		var method = $(this).val();
		if(method == ''){
			 $('#transaction_id').prop('required',false);
			 $('#transaction_img').prop('required',false);
			 $('#transaction').hide();		
		}else{
			 $('#transaction_id').prop('required',true);
			 $('#transaction_img').prop('required',true);
			 $('#transaction').show();
		}		
    });
	
    $(document).on('keyup',"#payment_done",function(e) {
		$('#remaining_amount').empty();
		var fees = $('#after_discount').val();
		var payment_done = $(this).val();
		var remaining_amount = fees-payment_done;
		$('#remaining_amount').val(remaining_amount);
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
		$('#hospital_id_text').empty();
		$('#payment_discount_text').empty();
		$('#discount_amount_text').empty();
		$('#reason_of_discount_text').empty();
						
		var doctor = $('#doctor_id').val();
		var payment_done = $('#payment_done').val();
		var payment_method = $('#payment_method').val();
		var payment_discount = $('#payment_discount').val();
		
		var transaction_id = $('#transaction_id').val();
		var transaction_img = $('#transaction_img').val();
		if(doctor == '' || payment_done == '' || payment_method == '' || payment_discount == '' || transaction_id == '' || transaction_img == ''){
			$('#msg_area').append('One or more fields are empty !');
		}else{
					if(payment_discount == 'discount'){
						var reason_of_discount =  $("input#reason_of_discount").val();
						var discount_amount =  $("input#discount_amount").val();
							
						if(discount_amount == '' || reason_of_discount == ''){
							$('#msg_area').append('One or more fields are empty !');
						}else{
							value_into_text();	
						}
					}else{
						value_into_text();
					}
			}
    });
	
	function value_into_text(){
		$('#doctor_id_text').append($('#doctor_id').find(':selected').attr('doc'));
		$('#fees_text').append($('#after_discount').val());
		$('#payment_done_text').append($('#payment_done').val());
		$('#remaining_amount_text').append($('#remaining_amount').val());
		$('#transaction_id_text').append($('#transaction_id').val());
		$('#payment_method_text').append($('#payment_method').find(':selected').attr('mode'));			
		$('#billing_id_text').append($('#billing_id').val());
		$('#consultation_id_text').append($('#consultation_id').val());
		$('#hospital_id_text').append($('#hospital_id').val());
		$('#payment_discount_text').append($('#payment_discount').find(':selected').val());
		$('#discount_amount_text').append($('#discount_amount').val());
		$('#reason_of_discount_text').append($('#reason_of_discount').val());
		hideshow_discount();
		$('#consultation_details').hide();
		$('#consultation_preview').show();
	}
	
	function hideshow_discount(){
		var discount_amount = $('#discount_amount').val()
		if(discount_amount < 1){
			$('.discount_div').hide();
		}else{
			$('.discount_div').show();	
		}
	}
	
	$(document).on('click',"#edit_billing",function(e) {
			$('#consultation_preview').hide();
			$('#consultation_details').show();
	});
	
</script>