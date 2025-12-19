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
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>End Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>IIC ID </label>
                <input type="text" class="form-control" id="iic_id" name="iic_id" value="<?php echo $patient_id;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Clearance by Counsellor</label>
                <select class="form-control" id="clearance" name="clearance">
                	<option value=''>--Select From--</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
             <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
                <label>Doctor Consultant </label>
                 <select class="form-control" id="consultant" name="consultant">
                	<option value=''>--Select From--</option>
                    <option value="Done">Done</option>
                </select>
                </div>
             <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
                <label>Clearance by Accounts</label>
                 <select class="form-control" id="accclearance" name="accclearance">
                	<option value=''>--Select From--</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
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
                    <th>Category</th>
                    <th>Procedure Date</th>
                    <?php if (
    !empty($_SESSION['logged_doctor']['name']) ||
    !empty($_SESSION['logged_embryologist']['name']) ||
    !empty($_SESSION['logged_counselor']['name'])
) { ?>
                     <th>Email</th>
                     <?php } ?>
				    <th><strong>FC / CH Clearance</strong></th>
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
    $balance = $vl['fees'] - ($sum_result->total_paid + $vl['payment_done']);
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
                    <td><?php echo number_format($balance, 2); ?>
</td>
                    <?php }else{ ?>
                    <td><?php echo $vl['receipt_number']?></td>
                    <?php } ?>
                    <td><?php echo $all_method->get_center_name($vl['billing_at']); ?></td>
				    <td><?php echo $all_method->get_center_name($vl['origins']); ?></td>
				    <td><?php echo $vl['procedure_name']; ?></td>
                    <td><?php echo $vl['code']; ?></td>
                    <td><?php echo $vl['category']; ?></td>
                    <?php if (!empty($_SESSION['logged_doctor']['name'])) { ?>
                    <td>
    <label><input type="checkbox" class="mail_check" value="director@indiaivf.in" id="mail_1_<?php echo $vl['ID']; ?>">director@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="dranu.singh@indiaivf.in" id="mail_2_<?php echo $vl['ID']; ?>">dranu.singh@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="rdrdivya.pandey@indiaivf.in" id="mail_3_<?php echo $vl['ID']; ?>">rahulghaziabad@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="drshavya.aggarwal@indiaivf.in" id="mail_4_<?php echo $vl['ID']; ?>">drshavya.aggarwal@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="dreshna.gupta@indiaivf.in" id="mail_5_<?php echo $vl['ID']; ?>">dreshna.gupta@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="drmanjote.kour@indiaivf.in" id="mail_6_<?php echo $vl['ID']; ?>">drmanjote.kour@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="drsheeba.farooq@indiaivf.in" id="mail_7_<?php echo $vl['ID']; ?>">drsheeba.farooq@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="gaurav.kumar@indiaivf.in" id="mail_8_<?php echo $vl['ID']; ?>">gaurav.kumar@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="ethan.rinngheta@indiaivf.in" id="mail_9_<?php echo $vl['ID']; ?>">ethan.rinngheta@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="sangeeth.samuel@indiaivf.in" id="mail_10_<?php echo $vl['ID']; ?>">sangeeth.samuel@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="harsh.sharma@indiaivf.in" id="mail_11_<?php echo $vl['ID']; ?>">harsh.sharma@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="tajinder.kaur@indiaivf.in" id="mail_12_<?php echo $vl['ID']; ?>">tajinder.kaur@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="ishver.singh@indiaivf.in" id="mail_13_<?php echo $vl['ID']; ?>">ishver.singh@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="drbabita.singh@indiaivf.in" id="mail_14_<?php echo $vl['ID']; ?>">drbabita.singh@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="anjali.sodhi@indiaivf.in" id="mail_15_<?php echo $vl['ID']; ?>">anjali.sodhi@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="mohd.ovais@indiaivf.in" id="mail_16_<?php echo $vl['ID']; ?>">mohd.ovais@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check" value="webdesign@indiaivf.in" id="mail_5_<?php echo $vl['ID']; ?>">webdesign@indiaivf.in</label><br>

    
</td><?php } ?>

                   <td><?php if (!empty($_SESSION['logged_doctor']['name'])) { ?>
                    <input type="date" id="procedure_date_<?php echo $vl['ID']; ?>" value="<?php echo $vl['procedure_date']; ?>" onchange="updateProcedureDate('<?php echo $vl['ID']; ?>', this.value)">
<?php }else{ ?>
                    <?php echo $vl['procedure_date']; } ?>
</td>
<?php if (
    !empty($_SESSION['logged_doctor']['name']) ||
    !empty($_SESSION['logged_embryologist']['name']) ||
    !empty($_SESSION['logged_counselor']['name'])
) { ?>
<td>
    <label><input type="checkbox" value="director@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">director@indiaivf.in</label><br>
    <label><input type="checkbox" value="dranu.singh@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">dranu.singh@indiaivf.in</label><br>
    <label><input type="checkbox" value="rdrdivya.pandey@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">rahulghaziabad@indiaivf.in</label><br>
    <label><input type="checkbox" value="drshavya.aggarwal@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">drshavya.aggarwal@indiaivf.in</label><br>
    <label><input type="checkbox" value="dreshna.gupta@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">dreshna.gupta@indiaivf.in</label><br>
    <label><input type="checkbox" value="drmanjote.kour@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">drmanjote.kour@indiaivf.in</label><br>
    <label><input type="checkbox" value="drsheeba.farooq@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">drsheeba.farooq@indiaivf.in</label><br>
    <label><input type="checkbox" value="gaurav.kumar@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">gaurav.kumar@indiaivf.in</label><br>
    <label><input type="checkbox" value="ethan.rinngheta@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">ethan.rinngheta@indiaivf.in</label><br>
    <label><input type="checkbox" value="sangeeth.samuel@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">sangeeth.samuel@indiaivf.in</label><br>
    <label><input type="checkbox" value="harsh.sharma@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">harsh.sharma@indiaivf.in</label><br>
    <label><input type="checkbox" value="tajinder.kaur@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">tajinder.kaur@indiaivf.in</label><br>
    <label><input type="checkbox" value="ishver.singh@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">ishver.singh@indiaivf.in</label><br>
    <label><input type="checkbox" value="drbabita.singh@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">drbabita.singh@indiaivf.in</label><br>
    <label><input type="checkbox" value="anjali.sodhi@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">anjali.sodhi@indiaivf.in</label><br>
    <label><input type="checkbox" value="mohd.ovais@indiaivf.in" class="mail_check_<?php echo $vl['ID']; ?>">mohd.ovais@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check_<?php echo $vl['ID']; ?>" value="accounts@indiaivf.in">accounts@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check_<?php echo $vl['ID']; ?>" value="pan.singh@indiaivf.in">pan.singh@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check_<?php echo $vl['ID']; ?>" value="deepa.mishra@indiaivf.in">rahulghaziabad@indiaivf.in</label><br>
    <label><input type="checkbox" class="mail_check_<?php echo $vl['ID']; ?>" value="webdesign@indiaivf.in">webdesign@indiaivf.in</label></td>
<?php } ?>
 <td><?php 
// 1. Check if Counselor is logged in AND Status is pending (empty)
if(!empty($_SESSION['logged_counselor']['name']) && $vl['clearance'] == '') { 
    
    // --- LOGIC START: Check Balance ---
    // We use <= 0 to account for 0.00 or negative numbers (overpaid)
    if($balance <= 0) { 
        ?> 
        <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="ClearanceProcedure('<?php echo $vl['ID']; ?>')">
            Clearance
        </a>
        <?php 
    } else {
        ?>
        <button class="btn btn-secondary btn-sm" disabled title="Balance must be 0">
            Due: <?php echo number_format($balance, 2); ?>
        </button>
        <?php
    }
    // --- LOGIC END ---
    ?>

    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="NonClearanceProcedure('<?php echo $vl['ID']; ?>')">
        Non Clearance
    </a>

<?php 
} else { 
    // 2. SHOW TEXT (If not counselor OR status is already decided)
    if($vl['clearance'] == '') {
        echo '<span class="text-warning">Pending</span>'; 
    } else {
        echo ucwords($vl['clearance']);
    }
} 
?>
                    </td>
                  <td><?php
if ($vl['clearance'] == 'Yes') {

    $show_button = false;

    // --- LOGIC FOR DOCTOR ---
    // Doctor can only access: 'IVF with Bed' OR 'Non IVF with Bed'
    if (!empty($_SESSION['logged_doctor']['name'])) {
        $doctor_allowed_categories = ['IVF with Bed', 'Non IVF with Bed'];
        
        if (in_array($vl['category'], $doctor_allowed_categories)) {
            $show_button = true;
        }
    }

    // --- LOGIC FOR EMBRYOLOGIST ---
    // Embryologist can only access: 'Non IVF without Bed'
    if (!empty($_SESSION['logged_embryologist']['name'])) {
        if ($vl['category'] == 'Non IVF without Bed') {
            $show_button = true;
        }
    }

    // 2. DISPLAY LOGIC
    // If Authorized ($show_button is true) AND Status is empty -> Show Button
    if ($show_button && $vl['consultant'] == '') { 
        ?> 
        <a href="javascript:void(0);" class="btn btn-success btn-sm" 
           onclick="consultantProcedure('<?php echo $vl['ID']; ?>')">
            <i class="fas fa-check"></i> Done
        </a>
        <?php
    } else {
        // Otherwise -> Show Status Text (Pending / Done)
        if ($vl['consultant'] == '') {
            echo '<span class="text-warning">Pending</span>'; 
        } else {
            $color_class = ($vl['consultant'] == 'Done') ? 'text-success' : 'text-danger';
            echo '<span class="'.$color_class.'">'.ucwords($vl['consultant']).'</span>';
        }
    }

} else {

    // 3. If Clearance is NOT 'Yes' -> Always show status text only
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
// Note: This is a reconstructed complete block, assuming it's part of a loop
// processing individual items (e.g., in a table row).

// Outer condition: Only proceed if the current item is marked as a consultant entry.
if ($vl['consultant'] == 'Done') {

    // 1. If Accountant is logged in AND Status is empty -> Show Buttons
    // This block is for the Accountant to take action on a pending item.
    if (!empty($_SESSION['logged_accountant']['name']) && $vl['accclearance'] == '') {
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
    // This block is for displaying the current status (for all users).
    else {
        if ($vl['accclearance'] == '') {
            // Case A: Status is pending (Accountant not logged in, or viewing status cell)
            echo '<span class="text-warning">Pending</span>'; 
        } else {
            // Case B: Status is already set
            
            // Determine the appropriate CSS class for color coding
            $color_class = ($vl['accclearance'] == 'Clearance') ? 'text-success' : 'text-danger';
            
            // Display the color-coded status text
            echo '<span class="'.$color_class.'">'.ucwords($vl['accclearance']).'</span>';
        }
    }
} 
// If the outer condition is NOT met (e.g., $vl['consultant'] != 'Yes'), 
// you might add an 'else' here to handle non-consultant entries, e.g.:
/*
else {
    echo '<span class="text-muted">N/A</span>';
}
*/
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
function ClearanceProcedure(ID) {

    let emails = [];

    $('.mail_check_' + ID + ':checked').each(function () {
        emails.push($(this).val());
    });

    if (emails.length === 0) {
        alert('Please select at least one email');
        return;
    }

    if (!confirm('Are you sure you want to approve this Clearance?')) {
        return;
    }

    $.ajax({
        url: '<?php echo base_url("accounts/clearance_procedure"); ?>',
        type: 'POST',
        data: {
            id: ID,
            emails: emails
        },
        success: function (response) {

            if (response === 'success') {
                alert('Clearance approved and mail sent successfully.');
                location.reload();
            } else if (response === 'already_done') {
                alert('Clearance already completed.');
            } else {
                alert(response);
            }

        },
        error: function (xhr) {
            alert('Something went wrong.');
            console.log(xhr.responseText);
        }
    });
}
</script>

  <script>
    // 1. Function for CLEARANCE
   

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
function consultantProcedure(ID) {

    let emails = [];

    $('.mail_check_' + ID + ':checked').each(function () {
        emails.push($(this).val());
    });

    if (emails.length === 0) {
        alert('Please select at least one email');
        return;
    }

    if (!confirm('Are you sure you want to approve this Consultant Clearance?')) {
        return;
    }

    $.ajax({
        url: '<?php echo base_url("accounts/consultant_procedure"); ?>',
        type: 'POST',
        data: {
            id: ID,
            emails: emails
        },
        success: function (response) {

            if (response === 'success') {
                alert('Consultant clearance done and mail sent.');
                location.reload();
            } else if (response === 'already_done') {
                alert('Consultant clearance already completed.');
            } else {
                alert(response);
            }

        },
        error: function (xhr) {
            alert('Something went wrong.');
            console.log(xhr.responseText);
        }
    });
}
</script>

 <script>
  
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
<script>
function updateProcedureDate(id) {

    let date = $('#procedure_date_' + id).val();

    if (!date) {
        alert('Please select procedure date');
        return;
    }

    let emails = [];
    $('#mail_1_' + id + ':checked, #mail_2_' + id + ':checked, #mail_3_' + id + ':checked, #mail_4_' + id + ':checked, #mail_5_' + id + ':checked, #mail_6_' + id + ':checked, #mail_7_' + id + ':checked, #mail_8_' + id + ':checked, #mail_9_' + id + ':checked, #mail_10_' + id + ':checked, #mail_11_' + id + ':checked, #mail_12_' + id + ':checked, #mail_13_' + id + ':checked, #mail_14_' + id + ':checked, #mail_15_' + id + ':checked, #mail_16_' + id + ':checked')
        .each(function () {
            emails.push($(this).val());
        });

    if (emails.length === 0) {
        alert('Please select at least one email');
        return;
    }

    $.ajax({
        url: '<?php echo base_url("accounts/update_procedure_date"); ?>',
        type: 'POST',
        data: {
            id: id,
            procedure_date: date,
            emails: emails
        },
        success: function(response) {
            if (response === 'success') {
                alert('Date saved and email sent successfully.');
            } else {
                alert(response);
            }
        }
    });
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
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: unset;
    left: -9999px;
    opacity: 1;
    /* display: block; */
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