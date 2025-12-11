 <?php $all_method =&get_instance(); ?>
    <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3> Procedure Financial Clearance</h3></div>
       <div class="clearfix"></div>
	    <form action="<?php echo base_url().'accounts/patient_financial_clearance'; ?>" method="get">
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
            <div class="col-sm-2 col-xs-12" style="margin-top:10px;">
            	<label>End Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>
            <div class="col-sm-2 col-xs-12" style="margin-top:10px;">
            	<label>IIC ID </label>
                <input type="text" class="form-control" id="iic_id" name="iic_id" value="<?php echo $patient_id;?>" />
            </div>
			<div class="col-sm-1" style="margin-top: 10px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<a href="<?php echo base_url().'accounts/patient_financial_clearance'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
            </div>
            </form>  
        <div class="clearfix"></div>
        <div class="card-content">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="procedure_billing_list">
              <thead>
                <tr>
				    <th>S.No.</th>
                    <th>IIC ID</th>
                    <th>Patient name</th>
                    <th>Receipt number</th>
                    <?php   if (!empty($_SESSION['logged_counselor']['name']) 
    || !empty($_SESSION['logged_accountant']['name'])) { ?>
                    <th>Billing Date & Time</th>
                    <th>Package Amount</th>
                    <th>Discount Amount</th>
				    <th>Discounted Package</th>
				    <th>Received Amount</th>
                    <th>Pending Amount</th>
                    <?php } ?>
                    <th>Billing Center</th>
				    <th>Origins Center</th>
				    <th>Procedure</th>
                    <th>Code</th>
				    <th>FC / CH Clearance</th>
                    <th>User / Doctor /  Embryologist</th>
                    <th>Account</th>
                </tr>
              </thead>
              <tbody id="procedure_result">
              <?php 
			  $total_totalpackage = 0;
              $total_discount_amount = 0;
			  $total_payment_done = 0;
			  $count=1; foreach($procedure_result as $ky => $vl){
                $patient_data = get_patient_detail($vl['patient_id']);
						    $currency = '';
                $current_balance = $all_method->get_current_balance($vl['patient_id']);
               $current_receipt = $vl['receipt_number']; 

    // 2. Run the Sum Query
    $sum_sql = "SELECT SUM(payment_done) as total_paid FROM hms_patient_payments WHERE billing_id = '$current_receipt'";
    $sum_query = $this->db->query($sum_sql);
    $sum_result = $sum_query->row();

    // 3. Display the result (formatted with 2 decimal places)
    
                
                ?>
                <tr class="odd gradeX">
                    <td><?php echo $count; ?></td>
                    <td><a href="<?php echo base_url().'patient_details'; ?>/<?php echo $vl['patient_id']; ?>"><?php echo $vl['patient_id']; ?></a></td>
                    <td><?php 
                    $patient_name = $all_method->get_patient_name($vl['patient_id']);
                    echo strtoupper($patient_name); ?>
                    </td>
                    <?php   if (!empty($_SESSION['logged_counselor']['name']) 
    || !empty($_SESSION['logged_accountant']['name'])) { ?>
                    <td><a href="<?php echo base_url().'accounts/financial_clearance_details';?>/<?php echo $vl['receipt_number']?>"><?php echo $vl['receipt_number']; ?></a></td>
                    
                    <td><?php echo $vl['on_date']?></td>
                    <td><?php echo $vl['totalpackage']?></td>
                    <td><?php echo $vl['discount_amount']?></td>
				    <td><?php echo $vl['fees']?></td>
				    <td><?php echo number_format($sum_result->total_paid + $vl['payment_done'], 2);   ?></td>
                    <td><?php 
$balance = $vl['fees'] - ($sum_result->total_paid + $vl['payment_done']);
echo number_format($balance, 2);
?>
</td>
                    <?php }else{ ?>
                    <td><?php echo $vl['receipt_number']?></td>
                    <?php } ?>
                    <td><?php echo $all_method->get_center_name($vl['billing_at']); ?></td>
				    <td><?php echo $all_method->get_center_name($vl['origins']); ?></td>
				    <td><?php echo $vl['procedure_name']; ?></td>
                    <td><?php echo $vl['code']; ?></td>
                    <td><?php 
                        // 1. Check if Counselor is logged in AND Status is pending (empty)
                        if(!empty($_SESSION['logged_counselor']['name']) && $vl['clearance'] == '') { 
                        ?> 
                            <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="ClearanceProcedure('<?php echo $vl['ID']; ?>')">
                                Clearance
                            </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="NonClearanceProcedure('<?php echo $vl['ID']; ?>')">
                                Non Clearance
                            </a>
                        <?php 
                        } else { 
                            // 2. SHOW TEXT (If not counselor OR status is already decided)
                            
                            if($vl['clearance'] == '') {
                                // Optional: Show 'Pending' text if it's empty and user isn't a counselor
                                echo '<span style="color:orange;">Pending</span>'; 
                            } else {
                                // Show the actual status
                                echo ucwords($vl['clearance']);
                            }
                        } 
                        ?>
                    </td>
                  <td><?php 
// 1. Check if (Embryologist OR Doctor) is logged in AND clearance is 'yed'
if ($vl['clearance'] == 'Yes') {

    $is_authorized = !empty($_SESSION['logged_embryologist']['name']) 
                    || !empty($_SESSION['logged_doctor']['name']);

    // Authorized AND consultant field is empty → show "Done" button
    if ($is_authorized && $vl['consultant'] == '') { 
        ?> 
        <a href="javascript:void(0);" class="btn btn-success btn-sm" 
           onclick="consultantProcedure('<?php echo $vl['ID']; ?>')">
            <i class="fas fa-check"></i> Done
        </a>
        <?php
    } else {
        // User unauthorized OR consultant already updated → show status
        if ($vl['consultant'] == '') {
            echo '<span class="text-warning">Pending</span>'; 
        } else {
            $color_class = ($vl['consultant'] == 'Done') ? 'text-success' : 'text-danger';
            echo '<span class="'.$color_class.'">'.ucwords($vl['consultant']).'</span>';
        }
    }

} else {

    // 2. clearance != 'yed' → show status only
    if ($vl['consultant'] == '') {
        echo '<span class="text-warning">Pending</span>'; 
    } else {
        $color_class = ($vl['consultant'] == 'Done') ? 'text-success' : 'text-danger';
        echo '<span class="'.$color_class.'">'.ucwords($vl['consultant']).'</span>';
    }
}
?>
</td>
                   <td><?php 
                    // 1. If Accountant is logged in AND Status is empty -> Show Buttons
                    if(!empty($_SESSION['logged_accountant']['name']) && $vl['accclearance'] == '') { 
                    ?> 
                        <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="accClearanceProcedure('<?php echo $vl['ID']; ?>')">
                            <i class="fas fa-check"></i> Clearance
                        </a>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="accNonClearanceProcedure('<?php echo $vl['ID']; ?>')">
                            <i class="fas fa-times"></i> Non Clearance
                        </a>

                    <?php 
                    } 
                    // 2. Otherwise (User is not accountant OR Status is already updated) -> Show Text
                    else { 
                        if($vl['accclearance'] == '') {
                            echo '<span class="text-warning">Pending</span>'; // Optional: Show 'Pending' if empty
                        } else {
                            // Color code the result for better UI
                            $color_class = ($vl['accclearance'] == 'Clearance') ? 'text-success' : 'text-danger';
                            echo '<span class="'.$color_class.'">'.ucwords($vl['accclearance']).'</span>';
                        }
                    } 
                    ?>
                </td>
                </tr>
              <?php $count++;} ?>
               <tr>
                <td colspan="5">
                <p class="custom-pagination"><?php echo $links; ?></p>
               
              </tr>
              </tbody>			  
            </table>
          </div>
        </div>
      </div>
     </div>
  <script>
    // 1. Function for CLEARANCE
    function ClearanceProcedure(ID) {
        if (confirm('Are you sure you want to approve this Clearance?')) {
            $.ajax({
                url: '<?php echo base_url("accounts/clearance_procedure/"); ?>' + ID,
                type: 'POST',
                success: function(response) {
                    // You can keep the alert if you want, or remove it for a faster feel
                    alert('Procedure Clearance successful!'); 
                    
                    // --- THIS COMMAND RELOADS THE PAGE ---
                    location.reload(); 
                },
                error: function(xhr, status, error) {
                    alert('Something went wrong. Please try again.');
                    console.log(xhr.responseText);
                }
            });
        }
    }

    // 2. Function for NON-CLEARANCE
    function NonClearanceProcedure(ID) {
        if (confirm('Are you sure you want to mark this as Non-Clearance?')) {
            $.ajax({
                // Assuming you have a similar controller function for Non-Clearance
                url: '<?php echo base_url("accounts/nonclearance_procedure/"); ?>' + ID, 
                type: 'POST',
                success: function(response) {
                    alert('Marked as Non-Clearance successfully!');
                    
                    // --- THIS COMMAND RELOADS THE PAGE ---
                    location.reload(); 
                },
                error: function(xhr, status, error) {
                    alert('Something went wrong. Please try again.');
                    console.log(xhr.responseText);
                }
            });
        }
    }
</script>  

 <script>
    // 1. Function for CLEARANCE
    function consultantProcedure(ID) {
        if (confirm('Are you sure you want to approve this Clearance?')) {
            $.ajax({
                url: '<?php echo base_url("accounts/consultant_procedure/"); ?>' + ID,
                type: 'POST',
                success: function(response) {
                    // You can keep the alert if you want, or remove it for a faster feel
                    alert('Procedure consultant successful!'); 
                    
                    // --- THIS COMMAND RELOADS THE PAGE ---
                    location.reload(); 
                },
                error: function(xhr, status, error) {
                    alert('Something went wrong. Please try again.');
                    console.log(xhr.responseText);
                }
            });
        }
    }

    // 2. Function for NON-CLEARANCE
    function accClearanceProcedure(ID) {
        if (confirm('Are you sure you want to mark this as Non-Clearance?')) {
            $.ajax({
                // Assuming you have a similar controller function for Non-Clearance
                url: '<?php echo base_url("accounts/accclearance_procedure/"); ?>' + ID, 
                type: 'POST',
                success: function(response) {
                    alert('Marked as Clearance successfully!');
                    
                    // --- THIS COMMAND RELOADS THE PAGE ---
                    location.reload(); 
                },
                error: function(xhr, status, error) {
                    alert('Something went wrong. Please try again.');
                    console.log(xhr.responseText);
                }
            });
        }
    }

     // 2. Function for NON-CLEARANCE
    function accNonClearanceProcedure(ID) {
        if (confirm('Are you sure you want to mark this as Non-Clearance?')) {
            $.ajax({
                // Assuming you have a similar controller function for Non-Clearance
                url: '<?php echo base_url("accounts/accnonclearance_procedure/"); ?>' + ID, 
                type: 'POST',
                success: function(response) {
                    alert('Procedure Clearance Reject!');
                    
                    // --- THIS COMMAND RELOADS THE PAGE ---
                    location.reload(); 
                },
                error: function(xhr, status, error) {
                    alert('Something went wrong. Please try again.');
                    console.log(xhr.responseText);
                }
            });
        }
    }
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
<script>
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


      function searchFilter(){
          var employee_name = $("#employee_number").val();
          //top.location.href = '/stocks/stocks_reports?employee_number='+employee_name;
      }

</script>