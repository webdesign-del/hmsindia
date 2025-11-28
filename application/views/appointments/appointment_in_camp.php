<?php  $all_method = &get_instance(); ?>
<div class="col-md-12">
   <div class="card">
      <div class="card-action">
         <h3>My Appointments</h3>
      </div>
      <div class="col-sm-12 col-xs-12">
         <form action=""<?php echo base_url().'my_appointments'; ?>" method="get">
            <div class="col-sm-3 col-xs-12 ">
               <label>Filter by status</label>
               <select class="form-control" id="status" name="status">
                  <option value=''>--Select--</option>
                  <option value='booked'>Scheduled</option>
                  <option value="cancelled">Cancelled</option>
                  <option value="rescheduled">Rescheduled</option>
                  <option value="in_clinic">In clinic</option>
                  <option value="no_show">No show</option>
                  <option value="visited">Billing done</option>
                  <option value="consultation">Patient in</option>
                  <option value="consultation_done">Consultation done</option>
               </select>
            </div>
            <div class="col-sm-3 col-xs-12 ">
               <label>Filter by doctor</label>
               <select class="form-control" id="doctor" name="doctor">
                  <option value="">--Select--</option>
                  <?php $doctor_list = $all_method->center_doctors(); 
                     foreach($doctor_list as $key => $vals){
                     ?>
                  <option value="<?php echo $vals['ID']; ?>">Dr. <?php echo $vals['name']; ?></option>
                  <?php } ?>                    
               </select>
            </div>
            <div class="col-sm-3 col-xs-12 ">
               <label>Patient Type</label>
               <select class="form-control" id="paitent_type" name="paitent_type">
                  <option value="">--Select--</option>
                  <option value="new_patient">New</option>
                  <option value="exist_patient">Exist</option>
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
            <div class="col-sm-3 col-xs-12 ">
               <label>CRM ID</label>
               <input type="text" id="crm_id" name="crm_id" value="<?php echo $crm_id;?>" class="form-control" placeholder="CRM ID" />
            </div>
            <div class="col-sm-3 col-xs-12 ">
               <label>Lead Source</label>
               <select class="form-control" id="lead_source" name="lead_source">
                  <option value="">--Select--</option>
                  <?php 
                  if(!empty($lead_sources)) {
                     foreach($lead_sources as $source) {
                        $selected = ($lead_source == $source['lead_source']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($source['lead_source']) . '" ' . $selected . '>' . htmlspecialchars($source['lead_source']) . '</option>';
                     }
                  }
                  ?>
               </select>
            </div>
            <div class="col-sm-3 col-xs-12 ">
               <label>Camp Name</label>
               <select class="form-control" id="camp_id" name="camp_id">
                  <option value="">--Select Camp--</option>
                  <?php 
                  if(!empty($camps)) {
                     foreach($camps as $camp) {
                        $selected = ($camp_id == $camp['camp_number']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($camp['camp_number']) . '" ' . $selected . '>' . htmlspecialchars($camp['camp_name']) . '</option>';
                     }
                  }
                  ?>
               </select>
            </div>
            <div class="col-sm-3 float-end" style="margin-top: 22px;display: flex;gap: 10px;">
               <button name="search" type="submit"  class="btn btn-primary">Search</button>
               <a href="<?php echo base_url().'my_appointments_camp'; ?>" style="text-decoration: none;">
               <button name="search" type="button"  class="btn btn-secondary">RESET</button>
               </a>
               <a href="<?php echo base_url('Appointments-Patients'); ?>" style="text-decoration: none;">
               <button name="export-billing" type="submit"  class="btn btn-secondary" id="export-billing">Export Billings</button>
               </a>
            </div>
         </form>
      </div>
      <div class="clearfix"></div>
      <div class="card-content">
         <div id="msg_area" class="error"></div>
         <?php if(empty($camps)): ?>
         <div class="alert alert-info">
            <strong>No Camps Available:</strong> This center does not have any camps configured. Please contact the administrator to set up camps for this center.
         </div>
         <?php endif; ?>
         <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" data-sort-name="date" data-sort-order="desc" id="appointment_body_xx">
               <thead>
                  <tr>
                     <th>S. No.</th>
                     <th>CRM ID</th>
                     <th>Patient Name</th>
                     <th>Doctor</th>
                     <th>Date</th>
                     <th>Slot</th>
                     <th>Camp Name</th>
                     <th>Reason of visit</th>
                     <th>Lead Source</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody id="appointment_body">
                  <?php $count = 1; foreach($appointments as $ky => $vl){ ?>
                  <tr class="odd gradeX">
                     <td><?php echo $count; ?></td>
                     <td><?php echo $vl['crm_id']?></td>
                     <td>
                        <?php if($vl['paitent_type'] == 'exist_patient'){ $patient_data = get_patient_detail($vl['paitent_id']);?>
                        <a target="_blank" href="<?php echo base_url()?>patient_details/<?php echo $vl['paitent_id'];?>"><?php echo  strtoupper($patient_data['wife_name']); ?></a>
                        <?php } else {?>
                        <?php echo strtoupper($vl['wife_name']); ?>
                        <?php } ?>
                     </td>
                     <td>Dr. <?php echo $all_method->doctor_name($vl['appoitmented_doctor']); ?></td>
                     <td><?php echo $vl['appoitmented_date']?></td>
                     <td><?php echo $vl['appoitmented_slot']?></td>
                     <td><?php echo $vl['camp_name']; ?></td>
                     <td><?php echo $vl['reason_of_visit']?></td>
                     <td><?php echo $vl['lead_source']?></td>
                       <?php if (!empty($_SESSION['logged_billing_manager']['center'])) {
				$center = $_SESSION['logged_billing_manager']['center'];
			} elseif (!empty($_SESSION['logged_counselor']['center'])) {
				$center = $_SESSION['logged_counselor']['center'];
			} elseif (!empty($_SESSION['logged_telecaller']['center'])) {
				$center = $_SESSION['logged_telecaller']['center'];
			  ?><?php if($vl['paitent_type'] == 'new_patient'){ ?>
                     <td class="role appint_td_<?php echo $vl['ID']?>">
                        <?php if($vl['status'] == 'consultation_done'){echo 'Consultation Done';}
                           else{ 
                           		if($vl['status'] == 'booked' || $vl['status'] == 'rescheduled' || $vl['status'] == 'in_clinic'){ ?>
                        <div class="appoint_<?php echo $vl['ID']?>">
                           <select appointment_id="<?php echo $vl['ID']?>" doctor_id="<?php echo $vl['appoitmented_doctor']; ?>" class="appointment_status">
                              <option value="">--Select status--</option>
                              <option value="in_clinic" <?php if($vl['status'] == 'in_clinic')echo 'selected="selected"';?>>In clinic</option>
                              <option value="cancelled" <?php if($vl['status'] == 'cancelled')echo 'selected="selected"';?>>Cancelled</option>
                              <option value="rescheduled" <?php if($vl['status'] == 'rescheduled')echo 'selected="selected"';?>>Rescheduled</option>
                              <option value="no_show" <?php if($vl['status'] == 'no_show')echo 'selected="selected"';?>>No show</option>
                           </select>
                        </div>
                        <?php }else if($vl['billed'] == '1'){ ?>
                        <select appointment_id="<?php echo $vl['ID']?>" doctor_id="<?php echo $vl['appoitmented_doctor']; ?>" class="appointment_status">
                           <option value="visited" <?php if($vl['status'] == 'visited')echo 'selected="selected"';?>>Biling done</option>
                           <option value="consultation" <?php if($vl['status'] == 'consultation')echo 'selected="selected"';?>>Patient in</option>
                        </select>
                        <?php }else{ echo strtoupper($vl['status']);}
                           }
                           ?>
                     </td>
                     <td class="appint_td_<?php echo $vl['ID']?>">
                        <?php 
                           if($vl['status'] == 'consultation_done'){echo 'Consultation Done';}else{
                           if($vl['status'] == 'cancelled' || $vl['status'] == 'no_show'){
                           echo strtoupper($vl['status']);
                           }
                           else{ ?>
                        <?php if($vl['billed'] == '0'){ if($vl['status'] == "in_clinic"){?>
                        <div class="appoint_<?php echo $vl['ID']?>">
                           <a href="<?php echo base_url('consultation/'.$vl['ID']); ?>" class="btn btn-primary" id="billing_link_<?php echo $vl['ID']?>">Consultation billing</a>
                        </div>
                        <?php } }else{ ?>
                        BILLED
                        <?php if($vl['partial_billing'] == 0){ ?>
                        <a href="<?php echo base_url('partial-billing/'.$vl['ID']); ?>" class="btn btn-primary">Partial Consultation</a>
                        <?php } ?>
                        <?php } } 
                           }
                           ?>
                     </td>
                   
                     <td>   
                        <a target="_blank" href="<?php echo base_url(); ?>accounts/patient_update?ID=<?php echo $vl['ID']?>">Edit</a>	
                        <a target="_blank" href="<?php echo base_url('registation/'.$vl['ID']); ?>" class="btn btn-primary">Registration</a>
                     </td>
                       <?php }} ?>	

                     <?php if($vl['status'] == "in_clinic"){ if (!empty($_SESSION['logged_doctor']['center'])) { ?>
                        <td> 
                           <a target="_blank" href="<?php echo base_url('accounts/assessment_form/'.$vl['paitent_id']); ?>" class="btn btn-primary">Assessment Sheet</a>                     
                        </td> <?php }} ?>	

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
</div>
<style>
   div#load_pop {
   position: fixed;
   top: 0;
   bottom: 0;
   margin: auto;
   z-index: 99999;
   left: 0;
   right: 0;
   width: 50%;
   background: #fff;
   height: 70%;
   padding: 30px 15px;
   box-shadow: 0px 0px 10px -4px #000;
   border-radius: 10px;
   display:none;
   }
   div#load_pop .ui-datepicker .ui-datepicker-header {
   height: 30px;
   }
   #load_pop_close {
   position: absolute;
   right: 20px;
   top: 8px;
   font-weight: 700;
   z-index: 9999;
   }
   img.pop_img {
   max-width: 100%;
   }
</style>
<div class="col-sm-12 col-xs-12" id="load_pop">
   <span id="load_pop_close">X</span>
   <div class="col-sm-12 col-xs-12">
      <form name="" action="<?php echo base_url('appointmentcontroller/reschedule_appointment'); ?>" method="POST">
         <input type="hidden" value="" class="reschedule_doctor_id" />
         <input type="hidden" value="" name="reschedule_appointment_id" class="reschedule_appointment_id" />
         <input type="hidden" value="" name="appoitmented_date" class="reschedule_appointment_date" />
         <input type="hidden" value="reschedule_appointment" name="reschedule_appointment" />
         <div class="row">
            <div class="form-group col-sm-12 col-xs-12">
               <label for="statuss">Reschedule Appointment date (Required)</label>
               <!-- <input type="text" class="rescheduled_datepicker" autocomplete="off" id="rescheduled_datepicker" name="appoitmented_date" placeholder="" required /> -->
               <div id="rescheduled_datepicker"></div>
            </div>
            <div class="form-group col-sm-12 col-xs-12 role" id="pop_appoitmented_slot" style="display:none;">
               <label for="statuss">Appoitmented_slot (Required)</label>
               <select name="appoitmented_slot" class="empty-field" id="appoitmented_slot" required>
                  <option value="">Select</option>
               </select>
            </div>
         </div>
         <input value="Submit" id="reschedule_appointment_btn" style="display:none;" type="submit" class="btn btn-large">
      </form>
   </div>
</div>
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
   $( "#load_pop_close" ).click(function() {
   	$('#load_pop').hide();
   });
   $(document).on('change',".appointment_status",function(e) {
           $('#appoitmented_slot').empty();
   		$('div#pop_appoitmented_slot').hide();
   		$('#reschedule_appointment_btn').hide();
   		
   		$('#loader_div').show();
           $('#msg_area').empty();
           var appointment_status = $(this).val();
           var appointment_id = $(this).attr('appointment_id');
   		if(appointment_status != ""){
   			if(appointment_status == "rescheduled"){
   				var doctor_id = $(this).attr('doctor_id');	
   				$('.reschedule_doctor_id').val(doctor_id);
   				$('.reschedule_appointment_id').val(appointment_id);
   				$('div#load_pop').show();
   			}else{
   				update_status(appointment_status, appointment_id);	
   			}
   		}
   		$('#loader_div').hide();
       });	
   	
   function update_status(appointment_status, appointment_id){
   	$.ajax({
   		url: '<?php echo base_url('appointmentcontroller/appointment_status')?>',
   		data: {appointment_status:appointment_status, appointment_id:appointment_id},
   		dataType: 'json',
   		method:'post',
   		success: function(data)
   		{
   			$('#msg_area').empty().append(data.message);
   			if(appointment_status == 'cancelled' || appointment_status == 'no_show'){$('td.appint_td_'+appointment_id).empty().append(appointment_status.toUpperCase())}
   			window.location.reload();
   
   		} 
   	});
   }
   
   $( function() {
       $( "#rescheduled_datepicker" ).datepicker({
   			dateFormat: 'yy-mm-dd',
   			changeMonth: true,
   			changeYear: true,
   		 	minDate: 0,
   			onSelect: function(dateStr) {
   			    $('#appoitmented_slot').empty();
   				$('div#pop_appoitmented_slot').hide();
   				$('#reschedule_appointment_btn').hide();
   				
   				$(".reschedule_appointment_date").val();
   				// $('#loader_div').show();				
   				var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
   				$(".reschedule_appointment_date").val(startDate);
   				var appoitmented_doctor = $('.reschedule_doctor_id').val();
   				$.ajax({
   					url: '<?php echo base_url('billingcontroller/doctor_slots')?>',
   					type: 'POST',
   					data: {selected:startDate, appoitmented_doctor:appoitmented_doctor},
   					success: function(data) {
   						$('#appoitmented_slot').empty().append(data);
   						$('div#pop_appoitmented_slot').show();
   						$('#reschedule_appointment_btn').show();
   						$('#loader_div').hide();
   					}
   				});
   			}
   		});
   } );
   	
   $(document).on('change',"#filter_by_status",function(e) {
     $('#loader_div').show();
      var status = $(this).val();
      if(status != ''){
   		var data = {status:status, type:'appointment_status'};
   		// appointment_filter(data);
   	}else{
   		// $('#loader_div').hide();
   	}
   });
   $(document).on('change',"#filter_by_doctor",function(e) {
     $('#loader_div').show();
      var doctor = $(this).val();
      if(doctor != ''){
   		var data = {doctor:doctor, type:'by_doctor'};
   		// appointment_filter(data);
   	}else{
   		// $('#loader_div').hide();
   	}
   });
   $(function() {
   	  $('input[name="daterange"]').daterangepicker({
   		opens: 'left'
   	  }, function(start, end, label) {
   			//console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
   			var data = {start:start.format('YYYY-MM-DD'),end:end.format('YYYY-MM-DD'), type:'date_wise'};
   			// appointment_filter(data);
   			//$(this).datepicker('setDate', null);
   	  });
   });
   
   $( function() {
       $( "#particular_date_filter" ).datepicker({
   			dateFormat: 'yy-mm-dd',
   			changeMonth: true,
   			changeYear: true,
   			onSelect: function(dateStr) {
   				// $('#loader_div').show();				
   				var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
   				var data = {appointment_date:startDate, type:'particular_date_filter'};
   				// appointment_filter(data);
   			}
   		});
   } );
   	
   
   $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
   	$(this).val('');
   	$(this).data('daterangepicker').setStartDate(moment());
   	$(this).data('daterangepicker').setEndDate(moment());
   });
   
   function appointment_filter(data){
       $('#appoitmented_slot').empty();
   	$('div#pop_appoitmented_slot').hide();
   	$('#reschedule_appointment_btn').hide();
   	
   	$('#appointment_body').empty();
   	$.ajax({
   		url: '<?php echo base_url('appointmentcontroller/ajax_appointment_filter')?>',
   		data: data,
   		dataType: 'json',
   		method:'post',
   		success: function(data)
   		{
   			$('#appointment_body').append(data.appointment_html);
   			$('#loader_div').hide();
   		} 
   	});
   }
</script>
<script>
   $(document).on('change',"#filter_by_status",function(e) {
      $('#loader_div').show();
      var status = $(this).val();
      if(status != ''){
   		var data = {status:status, type:'appointment_status'};
   		// appointment_filter(data);
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
   
   $( function() {
       $( ".particular_date_filter" ).datepicker({
   			dateFormat: 'yy-mm-dd',
   			changeMonth: true,
   			changeYear: true,
   			onSelect: function(dateStr) {
   				// $('#loader_div').show();				
   				var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
   				var data = {appointment_date:startDate, type:'particular_date_filter'};
   			}
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
</style>