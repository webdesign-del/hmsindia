<?php $all_method =&get_instance(); 

?>
<form class="col-sm-12 col-xs-12" method="post" action="" enctype="multipart/form-data" >
  <input type="hidden" name="action" value="add_registation" />
  <input type="hidden" name="appointment_id" value="<?php echo $appointments['ID']; ?>" />
  <input type="hidden" name="billing_at" value="<?php echo $_SESSION['logged_billing_manager']['center']?>" />
  <input type="hidden" id="billing_type" value="consultation" />
  <input type="hidden" id="crm_id" name="crm_id" value="<?php echo $appointments['crm_id'];?>" />
  <input type="hidden" id="uhid" name="uhid" value="<?php echo $appointments['uhid'];?>" />
  <input type="hidden" name="biller_id" value="<?php echo $_SESSION['logged_billing_manager']['employee_number']?>" />
  <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $appointments['paitent_id']; ?>" />
            
  <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku" id="consultation_details">
      <div class="panel-heading">
        <h3 class="heading">Registration Details</h3>
      </div>
      <div class="panel-body profile-edit">
      <p id="msg_area" class="delete"></p>
        <p>

        <div class="row">            
           <div class="form-group col-sm-6 col-xs-12 role">
           <label for="item_name">IIC ID(Required)</label>
                <input value="<?php echo $appointments['paitent_id']; ?>" placeholder="IIC ID" readonly="readonly" name="" id="" type="text" disabled class="form-control validate" required>
            </div>
	    	</div>


         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Doctor (Required)</label>
                <p>Dr. <?php echo $all_method->doctor_name($appointments['appoitmented_doctor']); ?></p>
                <input id="doctor_id" name="doctor_id" type="hidden" value="<?php echo $appointments['appoitmented_doctor']; ?>" >
            </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Date(Required)</label>
                <input value="<?php echo date("Y-m-d H:i:s"); ?>" placeholder="Date" readonly="readonly" id="on_date" name="on_date" type="text" class="form-control validate" required>
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Receipt number (Required)</label>
                <input value="<?php echo getReceiptGUID(); ?>" placeholder="Receipt number" readonly="readonly" id="receipt_number" name="receipt_number" type="text" class="form-control validate" required>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Consultation fees (Required)</label>
                <!-- <input value="<?php echo $all_method->doctor_fees($appointments['appoitmented_doctor'], $appointments['nationality']); ?>" name="totalpackage" placeholder="Consultation fees" readonly="readonly" class="dhee" id="fees" type="hidden" class="form-control validate" required>
                <input value="<?php echo $all_method->doctor_fees($appointments['appoitmented_doctor'], $appointments['nationality']); ?>" placeholder="Consultation fees" readonly="readonly" id="after_discount" name="fees" type="text" class="form-control validate" required> -->
                <input value="1000" name="totalpackage" placeholder="Consultation fees" readonly="readonly" class="dhee" id="fees" type="hidden" class="form-control validate" required>
                <input value="1000" placeholder="Consultation fees" readonly="readonly" id="after_discount" name="fees" type="text" class="form-control validate" required>
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

            <div class="form-group col-sm-6 col-xs-12 role" id="free_reason_box" style="display:none;">
                <label for="item_name">Free Reason (Required)</label>
                <select id="free_reason">
               		<option value="">Select</option>
                    <option value="First Visit">First Visit</option>
               		  <option value="For TVS (Under Package)">For TVS (Under Package)</option>
                    <option value="Under Package">Under Package</option>
                    <option value="BHCG Counselling">BHCG Counselling</option>
                    <option value="Medicine Purchase">Medicine Purchase</option>
                    <option value="Diagnostic Test">Diagnostic Test</option>
                    <option value="Camp">Camp</option>
                </select>
              <!--  <input value="" placeholder="Free Reason" id="free_reason" name="free_reason" type="text" class="form-control validate">
                    --> </div>
          </div>        
        
        <div class="row" id="discount_avail" style="display:none;">
            <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Discount amount (Required)</label>
               <input value="0" placeholder="Discount amount" id="discount_amount" name="discount_amount" type="text" class="form-control validate">
               <input value="<?php echo $_SESSION['logged_billing_manager']['allow_discount_rs']; ?>" id="allow_discount" type="hidden" class="form-control validate" required>
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
           <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Payment mode (Optional for free)</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="">Select</option>
                   	<?php if($appointments['nationality'] == 'indian'){?>
                      <option value="neft" mode="NEFT">NEFT</option>
                      <option value="rtgs" mode="RTGS">RTGS</option>
                      <option value="card" mode="Card">Card</option>
                      <option value="upi" mode="UPI">UPI</option>
                      <option value="insurance" mode="Insurance">Insurance</option>
                    <?php }else{ ?>
	                    <option value="international_card" mode="International Card">International Card</option>
                    <?php } ?>
                      <option value="cash" mode="Cash">Cash</option>
                      <option value="cheque" mode="Cheque">Cheque</option>                    
                </select>
            </div>
            
         </div>

         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Payment received (Required)</label>
                <input value="" placeholder="Payment received" id="payment_done" step="any" name="payment_done" type="number" class="form-control validate" required>
           </div>
           
           <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Remaining amount (Required)</label>
               <input value="" placeholder="Remaining amount" readonly="readonly" id="remaining_amount" name="remaining_amount" type="text" class="form-control validate" required>
           </div>
         </div>

         
        <div class="row">
            <div class="form-group col-sm-6 col-xs-12">
               <label for="item_name">Billing ID (Optional)</label>
               <input value="" placeholder="Billing ID" id="billing_id" name="billing_id" type="text" class="form-control validate">
			    <?php 
				      $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$appointments['wife_phone']."' and paitent_type='new_patient'"; 
			            $query = $this->db->query($sql1);
                            $select_result1 = $query->result(); 
							foreach ($select_result1 as $res_val){
							//	echo '<br/>';
							//	echo $res_val->appoitment_for;
							//}
						?>
						<input value="<?php echo $res_val->appoitment_for;?>" placeholder="origins" readonly="readonly" name="origins" id="origins" type="hidden" class="form-control">
           		<?php } ?>
            </div>
            
            <div class="form-group col-sm-6 col-xs-12 role">
               <label for="item_name">Consultation ID</label>
               <select id="consultation_id" name="consultation_id">
               		<option value="">Consultation ID</option>
                    <?php echo $all_method->get_code('consultation');?>
               </select>
            </div>
        </div>
         
         <div class="row">            
            <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Billing source (Required)</label>
                <select name="billing_from" id="billing_from" required>
                    <option value="">Select</option>
                    <?php if(isset($_SESSION['logged_billing_manager'])){ 
                            $center = $all_method->get_center(); 
                            if($_SESSION['logged_billing_manager']['center_type'] == "associated"){ ?>
                    	      <option value="<?php echo $center['center_number']; ?>"><?php echo $center['center_name']; ?></option>
                    <?php } } ?>
                </select>
            </div>
              <div class="form-group col-sm-6 col-xs-12 hospital_id_section" style="display:none;">
               <label for="item_name">Hospital ID</label>
               <input value="" id="hospital_id" name="hospital_id" type="text" class="form-control validate">
			         <?php if($_SESSION['logged_billing_manager']['employee_number'] == "16249617235059" ){ ?>
						<input value="001/C/<?php $year = date("y"); echo $year, $year+1; ?>/" id="series_number" name="series_number" type="hidden" class="form-control validate">
					<?php  } ?>	
                	<?php if($_SESSION['logged_billing_manager']['employee_number'] == "16266784114794" ){ ?>
						<input value="002/C/<?php $year = date("y"); echo $year, $year+1; ?>/" id="series_number" name="series_number" type="hidden" class="form-control validate">
					<?php  } ?>	
					<?php if($_SESSION['logged_billing_manager']['employee_number'] == "16289367598583" ){ ?>
						<input value="003/C/<?php $year = date("y"); echo $year, $year+1; ?>/" id="series_number" name="series_number" type="hidden" class="form-control validate">
					<?php  } ?>	
					<?php if($_SESSION['logged_billing_manager']['employee_number'] == "16299510247261" ){ ?>
						<input value="005/C/<?php $year = date("y"); echo $year, $year+1; ?>/" id="series_number" name="series_number" type="hidden" class="form-control validate">
					<?php  } ?>	
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
        <input type='button' id='btn' value='Print' class="btn btn-primary pull-right" onclick='printDiv();'>
      </div>
      <div class="panel-body profile-edit">
      <p id="msg_area" class="delete"></p>
        <p>
        <table border="1" id="print_this_section" style="width:100%; border: 1px solid black; border-collapse: collapse;">
        <tr style="width:100%; border: 1px solid black; border-collapse: collapse;">
          <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" colspan="2" >Billing summary:</th>
        </tr>
        
        <tr style="width:100%; border: 1px solid black; border-collapse: collapse;">
          <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Doctor:</th>
          <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" id="doctor_id_text"></td>
        </tr>

        <tr style="width:100%; border: 1px solid black; border-collapse: collapse;">
          <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Date:</th>
          <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" id="on_date_text"></td>
        </tr>
        
       <!-- <tr style="width:100%; border: 1px solid black; border-collapse: collapse;">
          <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">IIC ID:</th>
          <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" id="patient_id_text"></td>
        </tr>-->

        <tr style="width:100%; border: 1px solid black; border-collapse: collapse;">
          <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Receipt number:</th>
          <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" id="receipt_number_text"></td>
        </tr>

        <tr style="width:100%; border: 1px solid black; border-collapse: collapse;">
          <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Fees:</th>
          <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" id="fees_text"></td>
        </tr>

        <tr style="width:100%; border: 1px solid black; border-collapse: collapse;">
          <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Payment received:</th>
          <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" id="payment_done_text"></td>
        </tr>  

        <tr style="width:100%; border: 1px solid black; border-collapse: collapse;">
          <th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Remaining amount:</th>
          <td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" id="remaining_amount_text"></td>
        </tr>
      </table>

          <div class="row">
             <!-- <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Payment mode (Optional for free)</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="">Select</option>
                   	<?php if($appointments['nationality'] == 'indian'){?>
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
            </div> -->
            
            <div class="form-group col-sm-6 col-xs-12" id="transaction" style="display:none;">
               <label for="item_name">Reference no. (Optional)</label>
               <input value="" placeholder="Reference no." id="transaction_id" name="transaction_id" type="text" class="form-control validate" required>
               <label>Upload screenshot/document here</label>
               <input type="file" name="transaction_img" id="transaction_img"  />
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
  $(document).on('keyup',"#subvention_charges",function(e) {
    var subvention_charges = $(this).val();
    var fees = parseFloat($('#fees').val());
    //console.log(subvention_charges+"----------"+fees);
    var discount = parseFloat($('#discount_amount').val());
    discount_amount = (discount_amount)?discount_amount:0;
		if(isNaN(discount)){ discount = 0;}
    fees = (fees)?fees:0;
    if(isNaN(fees)){ fees = 0;}
    fees = (parseFloat(fees) - parseFloat(discount));
    if(subvention_charges != ""){
      var subvention = (parseFloat(subvention_charges) + parseFloat(fees));
      $('#after_discount').val(parseFloat(subvention));
    }else{
      $('#after_discount').val(parseFloat(fees));
    }
  });

	$(document).on('change',"#payment_discount",function(e) {
    $('#payment_method').prop('selectedIndex',0);
    $('#subvention_charges').val("");
    $("#subvention_charges").prop('required', false);
    $("#subvention_box").hide();

    $('#free_reason').val("");
    $("#free_reason").prop('required', false);
    $("#free_reason_box").hide();
    $("#payment_method").prop('required',true);
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
      $("#free_reason").prop('required', true);
      $("#free_reason_box").show();
      $("#payment_method").prop('required',false);
      $("#transaction_id").prop('required',false);
			$("#after_discount").val(0);
			$('#payment_done').val(0);
			$('#remaining_amount').val(0);
		}
	});

	$(document).on('keyup',"#discount_amount",function(e) {
    var subvention_charges = 0;
    if($("#payment_method").val() == "insurance"){
      subvention_charges = parseFloat($("#subvention_charges").val());
      if(subvention_charges == ""){  subvention_charges = 0; }
    }

		$('#payment_done').val('');
		$('#remaining_amount').val('');
		var fees = parseFloat($('#fees').val());
    var new_fees = (fees + subvention_charges);
		var allowd = parseFloat($('#allow_discount').val());
		var discount_amount = parseFloat($(this).val());
    var after_cal_price = ( new_fees * allowd / 100 ).toFixed(2);
		discount_amount = (discount_amount)?discount_amount:0;
		if(discount_amount == ''){ $(this).val(""); discount_amount = 0;}
   
    //console.log(discount_amount+' ----- '+after_cal_price);
 		//console.log(fees+' ----- '+allowd+' ----- '+discount_amount+' -------- '+after_cal_price+' -------- '+subvention_charges);
		if(discount_amount > after_cal_price){
				$('#fees').val('');
				$('#fees').val(parseFloat(fees));
				$('#after_discount').val(parseFloat(new_fees));
				$('#create_billing').hide();
				$('#show_disc_app').show();				
		}else{
			if(parseFloat(discount_amount) <= parseFloat(after_cal_price)){
          var listPrice = parseFloat(new_fees);
          var discount  = parseFloat(discount_amount);
          
          console.log(listPrice+' ----- '+discount);
          //var remaining_amount =  (listPrice - ( listPrice * discount / 100 ));
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
	
    $(document).on('change',"#payment_method",function(e) {
        
        if($('#payment_discount').val() != "free"){
          var fees = parseFloat($('#fees').val());
          var discount_amount = parseFloat($('#discount_amount').val());
          discount_amount = (discount_amount)?discount_amount:0;
          fees = (fees - discount_amount);
          $('#after_discount').val(parseFloat(fees));
        }

        //$('#discount_amount').val(" ");
        $('#payment_done').val(" ");
        $('#remaining_amount').val(" ");
        $('#show_disc_app').hide();

        $('#transaction_id').prop('required',false);
        $('#transaction_img').prop('required',false);
        $('#transaction_id').empty();
        $('#subvention_charges').val("");
        $('#subvention_charges').prop('required',false);
        $('#subvention_box').hide();
        var method = $(this).val();
        if(method == ''){
          $('#transaction_id').prop('required',false);
          $('#transaction_img').prop('required',false);
          $('#transaction').hide();		
        }else{
          $('#transaction_id').prop('required',false);
          $('#transaction_img').prop('required',false);
          $('#transaction').show();
        }
        if(method == "insurance"){
          $('#subvention_charges').prop('required',true);
          $('#subvention_box').show();
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
		if(doctor == '' || payment_done == '' || payment_discount == ''){
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
		$('#doctor_id_text').empty().append($('#doctor_name').val());
		$('#fees_text').empty().append($('#after_discount').val());
		$('#payment_done_text').empty().append($('#payment_done').val());
		$('#remaining_amount_text').empty().append($('#remaining_amount').val());
		$('#transaction_id_text').empty().append($('#transaction_id').val());
		$('#payment_method_text').empty().append($('#payment_method').find(':selected').attr('mode'));			
		$('#billing_id_text').empty().append($('#billing_id').val());
    $('#receipt_number_text').empty().append($('#receipt_number').val());
	// $('#patient_id_text').empty().append($('#patient_id').val());
    $('#on_date_text').empty().append($('#on_date').val());
   

		// $('#consultation_id_text').append($('#on_date_text').val());
		// $('#hospital_id_text').append($('#hospital_id').val());
		// $('#payment_discount_text').append($('#payment_discount').find(':selected').val());
		// $('#discount_amount_text').append($('#discount_amount').val());
		// $('#reason_of_discount_text').append($('#reason_of_discount').val());
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
	
function printDiv() 
{
  $('#print_this_section').css('visibility', 'visible');
  // var divToPrint=document.getElementById('print_this_section');
  // var newWin=window.open('','Print-Window');
  // newWin.document.open();
  // newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  // newWin.document.close();
  // setTimeout(function(){newWin.close();},10);

  var divToPrint=document.getElementById("print_this_section");
  newWin= window.open("");
  newWin.document.write(divToPrint.outerHTML);
  newWin.print();
  newWin.close();

}
</script>