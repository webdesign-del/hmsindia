 <?php $all_method =&get_instance(); ?>
 
        <div class="card">
      <div class="row card-content" style="margin-bottom:20px;">
      <div class="col-md-12"><h3>Consultation Revenue Reports </h3></div>
      <div class="clearfix"></div>
	    <form action="<?php echo base_url().'accounts/consultation_origin'; ?>" method="get">
		   <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
    <label>Filter by billing at</label>
    <select class="form-control" id="billing_at" name="billing_at">
        <option value=''>--Select Center--</option>
        <?php $all_centers = $all_method->get_all_centers();
            foreach($all_centers as $key => $val){ 
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
                <label>Filter by doctor</label>
                <select class="form-control" id="doctor_id" name="doctor_id">
                    <option value="">--Select Doctor--</option>
                    <!-- Doctors will be loaded dynamically -->
                </select>
            </div>
			<div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Reason For Visit </label>
               <select class="form-control" id="reason_of_visit" name="reason_of_visit">
    <option value="">--Select From--</option>
    <option value="First Visit" <?= ($selectedReason === 'First Visit') ? 'selected' : '' ?>>First Visit</option>
    <option value="Consulted Not Booked" <?= ($selectedReason === 'Consulted Not Booked') ? 'selected' : '' ?>>Consulted Not Booked</option>
    <option value="FOLLOW UP VISIT" <?= ($selectedReason === 'FOLLOW UP VISIT') ? 'selected' : '' ?>>Follow up Visit</option>
    <option value="PROCEDURE" <?= ($selectedReason === 'PROCEDURE') ? 'selected' : '' ?>>Procedure</option>
    <option value="TVS" <?= ($selectedReason === 'TVS') ? 'selected' : '' ?>>TVS</option>
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
         <!--   <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Start Booking Date</label>
              <input type="text" class="particular_date_filter form-control" id="start_date" name="start_date" value="<?php echo $start_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>End Booking Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>-->
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>IIC ID </label>
                <input type="text" class="form-control" id="iic_id" name="iic_id" value="<?php echo $patient_id;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Lead Source </label>
            <select name="lead_source" id="lead_source" class="form-control">
    <option value="">All Lead Sources</option>
    <?php if (!empty($lead_sources) && is_array($lead_sources)): ?>
        <?php foreach($lead_sources as $bucket => $sources_value): ?>
            <?php if (!empty($bucket) && !empty($sources_value)): ?>
                <?php
                $current_value = isset($filters['lead_source']) ? $filters['lead_source'] : '';
                // Decode both values to handle URL encoding
                $decoded_current = urldecode($current_value);
                $decoded_sources = urldecode($sources_value);
                $is_selected = ($decoded_current === $decoded_sources);
                ?>
                <option value="<?php echo htmlspecialchars($sources_value); ?>" 
                    <?php echo $is_selected ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($bucket); ?>
                </option>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="">No lead sources available</option>
    <?php endif; ?>
</select>
            </div>
            <div class="col-sm-3" style="margin-top: 30px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            	<a href="<?php echo base_url().'accounts/consultation_origin'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
            	<a href="<?php echo base_url('accounts/consultation-reports'); ?>" style="text-decoration: none;">
                <button name="export-billing" type="submit"  class="btn btn-secondary" id="export-billing">Export Reports</button>
               </a>
            </div>	
		  </form>  
        </div>
        <div class="clearfix"></div>
       <div class="clearfix"></div>
        <div class="card-content">
          <div class="table-responsive">
            <table class="table table-sriped table-bordered table-hover" id="consultation_billing_list">
               <thead>  <tr>
<?php if (!empty($reason_counts['unique_first_patient_count'])): ?>
    <td>First Visit: <?php echo $reason_counts['unique_first_patient_count']; ?></td>
<?php else: ?>
    
<?php endif; ?>

<?php if (!empty($patient_counts['unique_patient_count'])): ?>
    <td>Booking: <?php echo $patient_counts['unique_patient_count']; ?></td>
<?php else: ?>
    
<?php endif; ?>

</tr>  </thead>
              <thead>
                <tr>
                  <th>S.No.</th>
                  <th>CRM ID</th>
                  <th>IIC ID</th>
                  <th>Patient name</th>
                  <th>Consultation Date and Time</th>
                  <th>Total</th>
                  <th>Discount amount</th>
                  <th>Received amount</th>
                  <th>Center</th>
                   <th>Reason For Visit</th>
				          <th>Doctor Name</th>
				          <th>Lead Source</th>
				           <th>Agent</th>
                            <th>Counselor Name</th>
                  <td>Status</td>
				</tr>
              </thead>
              <tbody id="consultation_result">


              <?php 



			  $count=1; foreach($consultation_result as $ky => $vl){
			  			$patient_data = get_patient_detail($vl['patient_id']);
            $currency = '';
           $sql = "SELECT * FROM hms_appointments WHERE paitent_id='" . $vl['patient_id'] . "'";
					        $appoint_result = run_select_query($sql);

					        $sql3 = "SELECT * FROM hms_appointments WHERE wife_phone='" . $appoint_result['wife_phone'] . "' and paitent_type='new_patient'";
					        $select_result3 = run_select_query($sql3);
           

			   ?>

                <tr class="odd gradeX">
                  <td><?php echo $count; ?></td>
                  <td><?php echo $select_result3['crm_id'];  ?></td>
                  <td><?php echo $vl['patient_id']; ?></td>
                  <td><?php $patient_name = $all_method->get_patient_name($vl['patient_id']); echo strtoupper($patient_name); ?></td>
                  <td><?php echo $vl['on_date']?></td>
                  <td><?php echo $vl['totalpackage']?></td>
                  <td><?php echo $vl['discount_amount']?></td>
                  <td><?php echo $vl['payment_done']?></td>
                  <td><?php echo $all_method->get_center_name($vl['billing_at']); ?></td>
                  <td><?php echo $vl['reason_of_visit']?></td>
				  <td><?php echo $all_method->get_doctor_name($vl['doctor_id']); ?></td>
				  <td><?php echo $select_result3['lead_source'];  ?></td>
                  <td><?php echo $select_result3['agent'];  ?></td>
				  <td><?php $counselor_name = $all_method->get_counselor_name($vl['appointment_id']);

if (!empty($counselor_name)) {
    echo $counselor_name;
} else {
    // If no counselor is found, print the agent name from $select_result3
    echo $select_result3['councellor'];
} ?></td>

<td><?php

$sql4 = "SELECT * FROM hms_patient_procedure WHERE patient_id='" . $vl['patient_id'] . "'";
$select_result4 = run_select_query($sql4);

// Check if 'data' exists and is not empty before proceeding
if (!empty($select_result4['data'])) {
    
    $unserialized_data = unserialize($select_result4['data']);

    // 1. CRITICAL CORRECTION: Access the array structure using index [0]
    if (isset($unserialized_data['patient_procedures'][0]['sub_procedure'])) {
        
        $sub_procedure = $unserialized_data['patient_procedures'][0]['sub_procedure'];
        
        // 2. CRITICAL CORRECTION: Use the correct SQL variable ($sql5)
        $sql5 = "SELECT * FROM hms_procedures WHERE ID='" . $sub_procedure . "'"; 
        $select_result5 = run_select_query($sql5);
        
        $category = 'not found';
        if (!empty($select_result5) && isset($select_result5['category'])) {
             // 4. Assuming the category is in the first result row and named 'category_column_name'
             $category = $select_result5['category']; 
        }
        
        // 5. CRITICAL CORRECTION: Use DOUBLE EQUALS (==) for comparison, not single equals (=) for assignment.
        if($category == 'IVF with Bed'){
            echo ' booked';
        } else {
            echo ' not booked';
        }
                       
    } 

} 
?>
</td>

				</tr>
                 <?php $count++;} ?>
			   <tr>
                <td colspan="5">
                <p class="custom-pagination"><?php echo $links; ?></p>
                </td>
              </tr>

              </tbody>

            </table>

          </div>

        </div>

      </div>
   </div>
</div>
<!--End Consultation  Tables -->
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
</script>
<script>
   $(document).ready(function() {
       // When center selection changes
       $('#billing_at').change(function() {
           var centerId = $(this).val();
           var doctorSelect = $('#doctor_id');
           
           // Clear previous options and show loading
           doctorSelect.html('<option value="">Loading doctors...</option>');
           doctorSelect.prop('disabled', true);
           
           if(centerId) {
               // AJAX call to get doctors by center
               $.ajax({
                   url: '<?php echo base_url(); ?>accounts/get_doctors_by_center',
                   type: 'POST',
                   data: { 
                       center_id: centerId,
                       '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                   },
                   dataType: 'json',
                   success: function(response) {
                       doctorSelect.prop('disabled', false);
                       
                       if(response.status === 'success' && response.doctors && response.doctors.length > 0) {
                           doctorSelect.html('<option value="">--Select Doctor--</option>');
                           $.each(response.doctors, function(index, doctor) {
                               var doctorText = 'Dr. ' + doctor.name;
                               if(doctor.is_primary == 1) {
                                   doctorText += ' (Primary)';
                               }
                               
                               doctorSelect.append(
                                   $('<option>', {
                                       value: doctor.ID,
                                       text: doctorText
                                   })
                               );
                           });
                       } else {
                           doctorSelect.html('<option value="">No doctors available</option>');
                           if(response.message) {
                               console.log(response.message);
                           }
                       }
                   },
                   error: function(xhr, status, error) {
                       doctorSelect.prop('disabled', false);
                       doctorSelect.html('<option value="">Error loading doctors</option>');
                       console.error('AJAX Error:', error);
                       console.log('Response:', xhr.responseText);
                   }
               });
           } else {
               // If no center selected, reset doctor dropdown
               doctorSelect.html('<option value="">--Select Doctor--</option>');
               doctorSelect.prop('disabled', false);
           }
       });
       
       // Trigger change on page load if a center is pre-selected
       var selectedCenter = $('#billing_at').val();
       if(selectedCenter) {
           $('#billing_at').trigger('change');
       }
   });
</script>
<script>
$(document).ready(function() {
    // Initialize Select2 on the element with ID 'reason_of_visit'
    $('#reason_of_visit').select2({
        placeholder: "--Select From--", // Use the placeholder from your first option
        allowClear: true // Allows the user to clear the selection (optional)
    });

    // NOTE: Select2 automatically respects the 'selected' attribute set by PHP.
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