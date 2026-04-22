 <?php $all_method =&get_instance(); ?>
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
		background:#fff;
    }
    table, th, td{
        border: 1px solid #cdcdcd;
    }
    table th, table td{
        padding: 10px;
        text-align: left;
    }
</style>

<div class="col-sm-12 col-xs-12" >
  <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku">
      <div class="panel-heading">
        <h3 class="heading">Account Ledger</h3>
      </div>
      <div class="panel-body profile-edit">
      <p id="msg_area" class="delete"></p>
        <p>

        <a href="#" class="btn btn-primary" id="procedure_link" target="_blank">Wallet</a>
        <a href="#" class="btn btn-primary" id="checkbalance_link" target="_blank">Wallet History</a>

        <div class="row">
          <div class="form-group col-sm-4 col-xs-12">
            <input value="" placeholder="Phone number of wife" id="phone_number" by="phone" name="phone_number" type="text" class="form-control validate" >
          </div>
          <div class="form-group col-sm-1 col-xs-12">
          	<p>OR </p>
          </div>
          <div class="form-group col-sm-4 col-xs-12">
            <input value="" placeholder="IIC ID" id="iic_id" by="patient" type="text" class="form-control validate" >
          </div>

          <div class="form-group col-sm-3 col-xs-12">
                <a id="search_patient" href="javascript:void(0)" class="btn btn-large" required>Search</a>
          </div>
        </div>        
        <hr/>         
         <div class="row">            
           <div class="form-group col-sm-4 col-xs-12">
                <label for="item_name">Paitent Name </label>
                <input value="" placeholder="Paitent name" readonly="readonly" id="paitent_name" name="paitent_name" type="text" class="form-control validate" required>
           </div>
           
           <div class="form-group col-sm-4 col-xs-12">
                <label for="item_name">Phone Number </label>
                 <input value="" placeholder="Phone Number" readonly="readonly" id="wife_phone_number" name="wife_phone_number" type="text" class="form-control validate" required>
           </div>
           
           <div class="form-group col-sm-4 col-xs-12">
                <label for="item_name">Husband Name</label>
                 <input value="" placeholder="Husband Name" readonly="readonly" id="husband_name" name="husband_name" type="text" class="form-control validate" required>
           </div>
         </div>
        <hr/>    
        
        <div class="row patient_data_table">
        	 <h3 style="margin-bottom:20px; float:left;">Patient Billings</h3> 
             <span style="float:right; font-weight:600; font-size:20px;color:#FF0000;" class="current_balance"></span>
             <table>
                <thead>
                    <tr>
                        <th>Receipt Number</th>
                        <th>Date</th>
                        <th>Billing at</th>
                        <th>Billing For</th>
						            <th>Biller Name</th>
                        <th>Total package</th>
                        <th>Discounted package</th>
                        <th>Amount Paid</th>
                        <th>Balance amount</th>
                        <th>Type</th>
						            <th>Status</th>
						            <th>Name</th>
                    </tr>
                </thead>
                <tbody id="patient_data_table_body">
                    <tr><td colspan="10" align="center">No record!</td></tr>
                </tbody>
            </table>
             <hr/>
             <h3 style="margin-bottom:20px;">Partial payments</h3>
             <table>
                <thead>
                    <tr>
	                   	  <th>Receipt Number</th>
                        <th>Payment ID</th>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Billing at</th>
                        <th>Billing from</th>
						            <th>Biller Name</th>                        
                        <th>Amount Paid</th>
                        <th>Payment method</th>
                        <th>Transaction ID</th>
                        <th>Date</th>
						            <th>Status</th>
						            <th>Name</th>
                    </tr>
                </thead>
                <tbody id="patient_payment_table_body">
                    <tr><td colspan="11" align="center">No record!</td></tr>
                </tbody>
            </table>
         </div> 
      </div>
      </p>
    </div>
  </div>
</div>

<script type="text/javascript">
$('#search_patient').hide();

// Phone number validation
$('#phone_number').on("change, blur, keyup", function() { 
	$('#add_section').hide();
	$('#iic_id').val('');
  $('#search_patient').show();
});
$('#iic_id').on("change, blur, keyup", function() {
	$('#add_section').hide();
	$('#phone_number').val('');
	$('#search_patient').show();
});

$(document).on('click',"#search_patient",function(e) {
	$('#loader_div').show();
	$('#msg_area').empty();
	
	var phone_number = $('#phone_number').val();
	var phone_by = $('#phone_number').attr('by');
	var patient_id = $('#iic_id').val();
	var patient_by = $('#iic_id').attr('by');
	
	if(phone_number != ''){
    $('#iic_id').val('');
		var data = {search_this:phone_number, search_by:phone_by};
		 search_patient(data);
	}else if(patient_id != ''){
    $('#phone_number').val('');
		var data = {search_this:patient_id, search_by:patient_by};
		 search_patient(data);
	}else{
		 $('#msg_area').append('Enter patient phone number or IIC ID');
		 $('#loader_div').hide();
	}
});

function search_patient(data){
    $.ajax({
			url: '<?php echo base_url('accounts/get_patient_data')?>',
			data: {'data' : data},
			dataType: 'json',
			method:'post',
			success: function(data)
			{
				$('#patient_data_table_body').empty();
				$('#paitent_name').val(data.patient_name);
				$('#wife_phone_number').val(data.patient_phone);
				$('#husband_name').val(data.husband_name);
				if(data.current_balance >= 1){
					$('.current_balance').empty().append("Current balance is :- "+data.current_balance);
				}else{
					$('.current_balance').empty().append("Current balance is :- 0");
				}
				$('#patient_data_table_body').empty().append(data.data);
				$('#patient_payment_table_body').empty().append(data.payment_html);
				$('#loader_div').hide();
			} 
	   });
}
// $(document).on('click',"#search_patient",function(e) {
// 	$('#loader_div').show();
// 	var patient_id = $("#paitent_id").val();
// 	if(patient_id != ''){	
//    }
//     $('#loader_div').hide();
// });

$(document).on('click',"#accept_billing",function(e) {
	var billing = $(this).attr('billing');
	var type = $(this).attr('type');
	$.ajax({
		url: '<?php echo base_url('accounts/update_billing'); ?>',
		data: {billing : billing, type:type},
		dataType: 'json',
		method:'post',
		success: function(data)
		{
			if(data == 1){ $('#search_patient').click(); }
		} 
   });
});


// Function to update the link's href attribute
function updateLink() {
    var dynamicId = document.getElementById("iic_id").value;
    var procedureLink = document.getElementById("procedure_link");

    // Update the href attribute with the new ID
    procedureLink.href = "<?php echo base_url('accounts/wallet_balance/'); ?>" + encodeURIComponent(dynamicId);

     var checkbalance = document.getElementById("checkbalance_link");

    // Update the href attribute with the new ID
    checkbalance.href = "<?php echo base_url('accounts/check_balance/'); ?>" + encodeURIComponent(dynamicId);
}

// Add an event listener to the input field
document.getElementById("iic_id").addEventListener("input", updateLink);

</script>