<?php $all_method =&get_instance(); ?>
<div class="card">
      <div class="row card-content" style="margin-bottom:20px;">
    <div class="col-md-12">
           <div class="row" style="margin-bottom:20px;">
      <div class="col-md-12"><h3>Partial Payments </h3></div>
      <div class="clearfix"></div>
	    <form action="<?php echo base_url().'accounts/partialpayments_request'; ?>" method="get">
		   <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Filter by billing at</label>
                <select class="form-control" id="billing_at" name="billing_at">
                	<option value=''>--Select From--</option>
                    <?php $all_centers = $all_method->get_all_centers();
						            foreach($all_centers as $key => $val){ //var_dump($val);die;
                          if($billing_at == $val['center_number']){
                            echo '<option value="'.$val['center_number'].'" selected>'.$val['center_name'].'</option>';
                          }else{
		                        echo '<option value="'.$val['center_number'].'">'.$val['center_name'].'</option>';
                          }
                    	  } 
					    ?>
                </select>
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Start Date</label>
              <input type="text" class="particular_date_filter form-control" id="start_date" name="start_date" value="<?php echo $start_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>End Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>IIC ID </label>
                <input type="text" class="form-control" id="iic_id" name="iic_id" value="<?php echo $patient_id;?>" />
            </div>
			 <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Status </label>
                <input type="text" class="form-control" id="status" name="status" value="<?php echo $status;?>" />
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<a href="<?php echo base_url().'accounts/partialpayments_request'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
            </div>
            <div class="col-sm-2" style="margin-top: 10px;">
            	<a href="<?php echo base_url('accounts/Partialpayments'); ?>" style="text-decoration: none;">
                <button name="export-billing" type="submit"  class="btn btn-secondary" id="export-billing">Export Billings</button>
               </a>
            </div>	
		  </form>  
        </div>
             <div class="clearfix"></div>
            <div class="card-content">
              <div class="table-responsive">
				  <div class="action-buttons">
            <button id="selectAllBtn" class="btn btn-default">Select All</button>
            <button id="deselectAllBtn" class="btn btn-default">Deselect All</button>
            <button id="sendToTallyBtn" class="btn btn-primary">Send Selected to Tally</button>
        </div>
                <table class="table table-striped table-bordered table-hover" id="procedure_billing_list">
                  <thead>
                    <tr>
    				  <th>S.No.</th>
					  <th></th>
                      <th>Receipt Number</th>
                      <th>Payment ID</th>
                      <th>Patient ID</th>
                      <th>On Date</th>
                      <th>Amount Paid</th>
                      <th>Payment Method</th>
                      <th>Billing At</th>
                      <th>Billing Source</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody id="partial_payment_result">
                  <?php $count=1; foreach($partialpayments_result as $ky => $vl){
                            $patient_data = get_patient_detail($vl['patient_id']);
    						$currency = '';
                  $current_balance = $all_method->get_current_balance($vl['patient_id']); ?>
                    <tr class="odd gradeX">
                      <td><?php echo $count; ?></td>
                      <td>
    <?php 
    // Show checkbox if (Status is 1 OR 3) AND it hasn't been sent to Tally yet
    if (($vl['status'] == '1' || $vl['status'] == '3') && $vl['tally_status'] != '1') { 
    ?>
        <input type="checkbox" class="rowCheckbox" value="<?php echo $vl['ID']; ?>">
    <?php } ?>
    
    <?php 
    // Show text if it is already sent
    if ($vl['tally_status'] == '1') { 
        echo '<span class="label label-success">Already Sent</span>'; 
    } 
    ?>
</td>
					            <td><a href="<?php echo base_url(); ?>accounts/details/<?php echo $vl['billing_id']?>?t=<?php echo $vl['type']?>"><?php echo $vl['billing_id']?></a></td>
                      <td><a href="<?php echo base_url()?>partial-payment-receipt/<?php echo $vl['refrence_number'];?>"><?php echo $vl['refrence_number']; ?></a></td>
                      <td><a href="<?php echo base_url()?>accounts/patient_details/<?php echo $vl['patient_id'];?>"><?php echo $vl['patient_id']; ?></a></td>
                      <td><?php echo $vl['on_date']?></td>
                      <td><?php echo $currency.$vl['payment_done']?></td>
                      <td><?php echo $vl['payment_method']?></td>
                      <td><?php echo $all_method->get_center_name($vl['billing_at']); ?></td>
                      <?php
                        if($vl['billing_from'] == 'IndiaIVF'){ echo '<td>'.$vl['billing_from'].'</td>'; }
					    else{echo '<td>'.$all_method->get_center_name($vl['billing_from']).'</td>';}
                      ?>
                      <?php if($vl['status'] == 1){ echo '<td>Approved</td>'; }
        					else if($vl['status'] == 2){ echo '<td>Disapproved</td>'; }
							else if($vl['status'] == 3){ echo '<td>Cancel</td>'; }
        					else{echo '<td>Pending</td>';}
    				  ?>
              
					<td>
<?php if($vl['status'] == 0 || $vl['status'] == 1){ ?>
    <a href="javascript:void(0);" 
       type="partialpayments" 
       bill="<?php echo $vl['ID']; ?>" 
       class="cancle_first btn btn-large">
       Cancel
    </a>
<?php } ?>
</td>
</tr>
                  <?php $count++;} ?>
				   <tr>
                <td colspan="7">
                <p class="custom-pagination"><?php echo $links; ?></p>
                </td>
              </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
      <!--End Partial Tables -->   
      <!--End Advanced Tables -->

	    <div class="row" id="cancle_pop">
            <div class="col-sm-12 cancle_pop_inner role">
            	<div class="col-sm-8 no-pad pt-7">
            		<label class="pop_lable">Reason of Cancel?</label>
                </div>
                <div class="col-sm-4">
            		<a href="javascript:void(0);" class="close_cancle btn btn-large">close</a>
                </div>
				
				 <label class="pop_lable">CN NO:</label>
                <input type="text" class="hidden_field" readonly="readonly" value="" id="bill_type" />
                <input type="text" class="hidden_field" readonly="readonly" value="3" id="bill_actions" />
                <input type="text" class="hidden_field" readonly="readonly" value="" id="bill_id" />
						<input type="text" class="form-control"  value="" id="cn_invoice" />
				        <p class="error hidden_field"></p>
                <label class="pop_lable">Cancel because:</label>
                <select class="cancle_suggestion mt-20">
                	<option value="">-- Select reason --</option>
                    <option value="Wrong entry">Wrong entry</option>
                	<option value="Wrong billing">Wrong billing</option>
                	<option value="Received amount not correct">Received amount not correct</option>
                	<option value="Amount not received">Amount not received</option>
					<option value="Wrong Package">Wrong Package</option>
					<option value="Used Amount">Used Amount</option>
                </select>
				
                <label class="pop_lable">Submit your own reason:</label>
                <textarea class="form-control" id="cancle_reason"></textarea>
				
				<label class="pop_lable">Used Amount:</label>
				<input type="text" class="form-control" name="used_amount" id="used_amount">
                <a href="javascript:void(0);" class="now_cancle btn btn-large">Cancel</a>
            </div>
        </div>
    </div>
    <style>
		.hidden_field{display:none;}
		div#disapprove_pop {
			position: fixed;
			top: 0;
			right: 0;
			left: 0;
			background: rgba(255,255,255,0.6);
			z-index: 999999999;
			height: 100%;
			box-shadow: 0px 0px 3px 0px #000;
			display:none;
		}
		.pop_lable {
			width: 100%;
			color: #000!important;
			font-weight: 800;
			font-size: 15px;
			margin-bottom: 10px!important;
		}
		.disapprove_pop_inner {
			width: 50%;
			margin: 80px 25%;
			float:left;
			box-shadow: 0px 0px 10px 0px #000;
			background: #fff;
		}
		a.close_disapprove {
			float: right;
			margin-top: 10px;
		}
		a.now_disapprove.btn.btn-large {
			margin: 10px 0px;
		}
		div#cancle_pop {
			position: fixed;
			top: 0;
			right: 0;
			left: 0;
			background: rgba(255,255,255,0.6);
			z-index: 999999999;
			height: 100%;
			box-shadow: 0px 0px 3px 0px #000;
			display:none;
		}
		.cancle_pop_inner {
			width: 50%;
			margin: 80px 25%;
			float:left;
			box-shadow: 0px 0px 10px 0px #000;
			background: #fff;
		}
		a.close_cancle {
			float: right;
			margin-top: 10px;
		}
		a.now_cancle.btn.btn-large {
			margin: 10px 0px;
		}
		[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: static;
    left: -9999px;
    opacity: 1;
}
	</style>
 <script>
	$(document).on('click','a.xyx',function(){
			$('#disapprove_pop p.error.hidden_field').empty().show();
			var xyx = confirm("Are you sure to approve this billing?");
			if(xyx){
				window.location.href = $(this).attr('link');
			}
		});
		
    $(document).on('click','a.disaprove_first',function(){
			$('#disapprove_pop p.error.hidden_field').empty().hide();
			$('#bill_type').val($(this).attr('type'));
			$('#bill_id').val($(this).attr('bill'));
			$('div#disapprove_pop').show();
		});
    $(document).on('click','a.close_disapprove',function(){
			$('#disapprove_pop p.error.hidden_field').empty().hide();
			$('#bill_type').val('');
			$('#bill_id').val('');
			$('div#disapprove_pop').hide();
		});
    $(document).on('click','a.now_disapprove',function(){
			$('p.error.hidden_field').empty().hide();
			var  bill_type = $('#bill_type').val();
			var  bill_action = $('#bill_action').val();
			var  bill_id = $('#bill_id').val();
			var  disapprove_suggestion = $('.disapprove_suggestion').val();
			var  disapprove_reason = $('#disapprove_reason').val();
			if(disapprove_suggestion != '' || disapprove_reason != ''){
				if(disapprove_suggestion !== ''){ disapprove_reason = disapprove_suggestion; }
				window.location.href = '<?php echo base_url();?>accounts/approve/'+bill_id+'?t='+bill_type+'&u='+bill_action+'&r='+disapprove_reason+'';			
			}else{
				$('#disapprove_pop p.error.hidden_field').empty().append('Select any reason!').show();
			}
		});
		
		$(document).on('click','a.cancle_first',function(){
			$('#cancle_pop p.error.hidden_field').empty().hide();
			$('#bill_type').val($(this).attr('type'));
			$('#bill_id').val($(this).attr('bill'));
			//$('#cn_invoice').val($(this).attr('cn_invoice'));
			$('div#cancle_pop').show();
		});
		$(document).on('click','a.close_cancle',function(){
			$('#cancle_pop p.error.hidden_field').empty().hide();
			$('#bill_type').val('');
			$('#bill_id').val('');
			$('#cn_invoice').val('');
			$('#used_amount').val('');
			$('div#cancle_pop').hide();
		});	
		$(document).on('click','a.now_cancle',function(){
			$('p.error.hidden_field').empty().hide();
			var  bill_type = $('#bill_type').val();
			var  bill_actions = $('#bill_actions').val();
			var  bill_id = $('#bill_id').val();
			var  cn_invoice = $('#cn_invoice').val();
			var  used_amount = $('#used_amount').val();
			var  cancle_suggestion = $('.cancle_suggestion').val();
			var  cancle_reason = $('#cancle_reason').val();
			if(cancle_suggestion != '' || cancle_reason != ''){
				if(cancle_suggestion !== ''){ cancle_reason = cancle_suggestion; }
				window.location.href = '<?php echo base_url();?>accounts/partial_approve/'+bill_id+'?t='+bill_type+'&u='+bill_actions+'&c='+cancle_reason+'&cn='+cn_invoice+'&ua='+used_amount+'';			
			}else{
				$('#cancle_pop p.error.hidden_field').empty().append('Select any reason!').show();
			}
		});	
    </script>
  </div>
 </div>
<script type="text/javascript">
      $( function() {
        $( ".particular_date_filter" ).datepicker({
          dateFormat: 'yy-mm-dd',
          changeMonth: true,
          changeYear: true,
          onSelect: function(dateStr) {
            $('#loader_div').hide();				
            var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
            var data = {appointment_date:startDate, type:'particular_date_filter'};
          }
        });
    });
</script>
<script type="text/javascript">
$(document).ready(function() {

    // 1. Logic for "Select All"
    $('#selectAllBtn').click(function() {
        $('.rowCheckbox').prop('checked', true);
    });

    // 2. Logic for "Deselect All"
    $('#deselectAllBtn').click(function() {
        $('.rowCheckbox').prop('checked', false);
    });

    // 3. Logic for "Send to Tally"
    $('#sendToTallyBtn').click(function() {
        var btn = $(this);
        var selectedIds = [];

        // Gather all checked checkboxes
        $('.rowCheckbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        // Validation: Check if anything is selected
        if(selectedIds.length === 0) {
            alert('Please select at least one record to send to Tally.');
            return;
        }

        if(!confirm('Are you sure you want to send ' + selectedIds.length + ' records to Tally?')) {
            return;
        }

        // Change button state to indicate loading
        var originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');

        // 4. AJAX Request to your specific URL
        $.ajax({
            url: '<?php echo base_url("accounts/partial_tally"); ?>', // Maps to your URL
            type: 'POST',
            data: {
                payment_ids: selectedIds // Sending the array of IDs
            },
            dataType: 'json', // Expecting JSON response from controller
            success: function(response) {
                if(response.success) {
                    alert('Success: ' + response.message);
                    // Optional: Reload page to update status
                    // location.reload(); 
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Server Error: Failed to connect to Tally endpoint.');
                console.error(xhr.responseText);
            },
            complete: function() {
                // Reset button
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
<style >
.custom-pagination{
  padding:8px;
}
.custom-pagination a{
  padding:10px;
  text-decoration: none;
}
.form-control{
  height: 30px!important;
  border: 1px solid #9e9e9e!important;
}
.form-control#billing_at{
  height: 40px!important;
  border: 1px solid #9e9e9e!important;
}
</style>