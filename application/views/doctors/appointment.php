 <?php $all_method = &get_instance(); ?>
    <div class="col-md-12">
      <!-- Advanced Tables -->
      <div class="card">
       <div class="card-action"><h3>My Appointments</h3></div>
        <div class="col-sm-12 col-xs-12">
            <form action="<?php echo base_url().'doctor_appointments'; ?>" method="get">
              <div class="col-sm-3 col-xs-12 ">
                  <label>Filter by status</label>
                    <select class="form-control" id="status" name="status" style="height:40px!important;">
                      <option value=''>--Select--</option>
                        <option value="booked">Scheduled</option>
                        <option value="rescheduled">Rescheduled</option>
                        <option value="visited">Billing done</option>
                        <option value="consultation">Patient in</option>
                        <option value="consultation_done">Consultation done</option>
                    </select>
                </div>
                <div class="col-sm-3 col-xs-12 ">
                  <label>Start Date</label>
                  <input type="text" class="particular_date_filter form-control" id="start_date" name="start_date" value="<?php echo $start_date;?>" />
                </div>
                <div class="col-sm-3 col-xs-12 ">
                  <label>End Date</label>
                    <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
                </div>
                <div class="col-sm-3 col-xs-12 ">
                    <label>Patient ID</label>
                    <input type="text" id="patient_id" name="patient_id"value="<?php echo $patient_id;?>" class="form-control" />
                </div>
                <div class="col-sm-3 col-xs-12 ">
                    <label>Patient Name/Mobile</label>
                    <input type="text" id="patient_name" name="patient_name" value="<?php echo $patient_name;?>" class="form-control" placeholder="Enter patient name/mobile" />
                </div>
                <div class="col-sm-1" style="margin-top: 22px;">
                  <button name="search" type="submit"  class="btn btn-primary">Search</button>
                </div>
            </form>  
            <div class="col-sm-1" style="margin-top: 22px;">
            	<a href="<?php echo base_url().'doctor_appointments'; ?>" style="text-decoration: none;">
                <button name="search" type="submit"  class="btn btn-secondary">RESET</button>
               </a>
            </div>          
        </div>
		
         <div class="clearfix"></div>
        <div class="card-content">
	      <div id="msg_area" class="error"></div>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="doctor_appointments1">
              <thead>
                <tr>
                  <th>S. No.</th>
                  <th>Patient Name</th>
                  <th>Doctor</th>
                  <th>Date</th>
                  <th>Slot</th>
                  <th>Reason of visit</th>
                  <th>Status</th>
                  <!-- <th>Investigation Reports</th>
                  <th>Procedure Reports</th> -->
                </tr>
              </thead>
              <tbody id="appointment_body">
              <?php $count = 1; foreach($appointments as $ky => $vl){ ?>
                <?php 
                $patient_id = $vl['paitent_id'];
                
                ?>
                <tr class="odd gradeX">
                  <td><?php echo $count; ?></td>
                  <td>
				  <?php if (isset($_SESSION['logged_viewer'])) { ?>
				   <a target="_blank" href="<?php echo base_url()?>patient_details/<?php echo $patient_id; ?>?appoitmented_date=<?php echo $vl['appoitmented_date']?>">
	                  	<br/> (<?php echo $patient_id; ?>)
	                  	</a>
				   <?php } ?>
                  	<?php if($vl['paitent_type'] == 'exist_patient'){ ?>
                      
	                  	<a target="_blank" href="<?php echo base_url()?>patient_details/<?php echo $patient_id; ?>?appoitmented_date=<?php echo $vl['appoitmented_date']?>"><?php echo $vl['wife_name']; ?>
	                  	<br/> (<?php echo $patient_id; ?>)
	                  	</a>
                    <?php } else {?>
                    	<?php echo $vl['wife_name']; ?>
                    <?php } ?>
                  </td>
                  <td>Dr. <?php echo $all_method->doctor_name($vl['appoitmented_doctor']); ?></td>
                  <td><?php echo $vl['appoitmented_date']?></td>
                  <td><?php echo $vl['appoitmented_slot']?></td>
                  <td><?php echo $vl['reason_of_visit']?></td>
                  <td class="role appint_td_<?php echo $vl['ID']?>">
                    <?php if(($vl['status'] == 'consultation' || $vl['status'] == 'visited') && $vl['billed'] == '1'){ ?>
                            <?php if($vl['follow_up_appointment'] == 1){?>
                              <a class="btn btn-primary" href="<?php echo base_url('follow-up-form/'.$vl['ID']);?>">Follow up</a>
                               <a class="btn btn-primary" href="<?php echo base_url('follow-up-ipd/'.$vl['ID']);?>">IPD Follow up</a>
                            <?php }else { ?>
                              <a class="btn btn-primary" href="<?php echo base_url('consultation_done/'.$vl['ID']);?>">Initiate Consultation</a>
                            <?php } ?>
                        <?php }else if($vl['status'] == 'consultation_done'){ ?>
                          	<?php 
                              $edit_consult = check_edit_appointment($vl['ID']); 
                              //var_dump($edit_consult);die; ?>
                            <?php if($edit_consult['final_mode'] == 0){ ?>
                              <?php if(!empty($edit_consult['disapproval_reason'])){ ?>
                                <a class="btn btn-danger" href="<?php echo base_url('consultation_done/'.$vl['ID'].'?mode=edit');?>">Complete Consultation</a>
                                (<?php echo $edit_consult['disapproval_reason']; ?>)
                              <?php }else{?>
                                <a class="btn btn-primary" href="<?php echo base_url('consultation_done/'.$vl['ID'].'?mode=edit');?>">Complete Consultation</a>
                              <?php } ?>
                            <?php }else{ ?>
                              Consultation Done
                            <?php } ?>
                        <?php } else if($vl['status'] == 'in_clinic'){ ?> 
                          In clinic
                        <?php } else{?>
                        	In process
                        <?php } ?>
                  </td>
                  <!-- <td>                    
	                  <a target="_blank" class="btn btn-primary" href="<?php echo base_url()?>patient_reports/<?php echo $patient_id;?>">Check Reports</a>
                  </td>
                  <td>
                    <?php $procedure_billing = check_procedure_billing($vl['ID']); //var_dump($procedure_billing);die;
                          if(count($procedure_billing) > 0){
                    ?>
                        <a target="_blank" class="btn btn-primary" href="<?php echo base_url()?>procedure_reports/<?php echo $vl['ID'];?>">Check Reports</a>
                    <?php } ?>
                  </td> -->
                </tr>
              <?php $count++; } ?>
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
      <!--End Advanced Tables -->
    </div>

    <script>
      // $(document).ready(function() {
      //     $('#doctor_appointments').DataTable( {
      //         "order": [[ 2, "desc" ]]
      //     } );
      // } );
    </script>

<script>
$(document).on('change',"#filter_by_status",function(e) {
  $('#loader_div').show();
   var status = $(this).val();
   if(status != ''){
		var data = {status:status, type:'appointment_status'};
		appointment_filter(data);
	}else{
		$('#loader_div').hide();
	}
});

$(function() {
	  $('input[name="date_range"]').daterangepicker({
		opens: 'left'
	  }, function(start, end, label) {
			//console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
			var data = {start:start.format('YYYY-MM-DD'),end:end.format('YYYY-MM-DD'), type:'date_wise'};
			appointment_filter(data);
	  });
});

// $('input[name="date_range"]').on('cancel.daterangepicker', function(ev, picker) {
// 	$(this).val('');
// 	$(this).data('daterangepicker').setStartDate(moment());
// 	$(this).data('daterangepicker').setEndDate(moment());
// });

$( function() {
    $( ".particular_date_filter" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			onSelect: function(dateStr) {
				$('#loader_div').show();				
				var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
				var data = {appointment_date:startDate, type:'particular_date_filter'};
			}
		});
});

// function appointment_filter(data){
// 	$('#appointment_body').empty();
// 	$.ajax({
// 		url: '<?php// echo base_url('doctors/ajax_appointment_filter')?>',
// 		data: data,
// 		dataType: 'json',
// 		method:'post',
// 		success: function(data)
// 		{
// 			$('#appointment_body').append(data.appointment_html);
// 			$('#loader_div').hide();
// 		} 
// 	});
// }
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
  </style>
