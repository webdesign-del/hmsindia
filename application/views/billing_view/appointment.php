<?php   $all_method =&get_instance(); $patient_id = getiic() ?>
<script type="text/javascript">
    var _formConfirm_submitted = false;
</script>

<style>
    /* Enhanced Design Styles */
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 16px;
        line-height: 1.6;
    }

    .panel-piluku {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        margin-bottom: 30px;
        background: white;
    }

    .panel-heading {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        color: white !important;
        border-radius: 15px 15px 0 0 !important;
        padding: 25px !important;
        border: none !important;
    }

    .panel-heading h3 {
        margin: 0 !important;
        font-size: 2.2rem !important;
        font-weight: 600 !important;
        text-align: center;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .panel-body {
        padding: 30px !important;
        background: white;
        border-radius: 0 0 15px 15px;
    }

    /* Form Controls */
    .form-control {
        font-size: 16px !important;
        padding: 15px 18px !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 10px !important;
        transition: all 0.3s ease !important;
        background: #f8fafc !important;
        font-weight: 500 !important;
    }

    .form-control:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
        background: white !important;
        outline: none !important;
    }

    .form-control::placeholder {
        color: #94a3b8 !important;
        font-size: 15px !important;
    }

    /* Labels */
    label {
        font-size: 16px !important;
        font-weight: 600 !important;
        color: #374151 !important;
        margin-bottom: 10px !important;
        display: block;
    }

    /* Error Message Styling */
    #msg_area {
        padding: 15px 20px !important;
        border-radius: 10px !important;
        margin: 20px 0 !important;
        font-size: 16px !important;
        font-weight: 500 !important;
        border-left: 4px solid !important;
        display: none;
    }

    #msg_area.success {
        background: #f0fdf4 !important;
        color: #166534 !important;
        border-left-color: #22c55e !important;
    }

    #msg_area.error {
        background: #fef2f2 !important;
        color: #991b1b !important;
        border-left-color: #ef4444 !important;
    }

    #msg_area.info {
        background: #eff6ff !important;
        color: #1e40af !important;
        border-left-color: #3b82f6 !important;
    }

    /* Search Button */
    #search_patient {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        color: white !important;
        border: none !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        transition: all 0.3s ease !important;
        padding-bottom: 30px !important;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3) !important;
    }

    #search_patient:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4) !important;
    }

    /* OR Divider */
    .form-group p {
        text-align: center !important;
        font-size: 18px !important;
        font-weight: 600 !important;
        color: #64748b !important;
        margin: 15px 0 !important;
        background: white;
        padding: 8px 16px;
        border-radius: 20px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    /* Patient ID Section */
    .paitent_id_div {
        background: #f8fafc !important;
        border: 2px dashed #cbd5e1 !important;
        border-radius: 12px !important;
        padding: 25px !important;
        margin: 20px 0 !important;
        text-align: center;
    }

    .paitent_id_div label {
        font-size: 18px !important;
        color: #475569 !important;
        font-weight: 700 !important;
    }

    .paitent_id_div input {
        background: white !important;
        font-weight: 700 !important;
        color: #4f46e5 !important;
        text-align: center !important;
        font-size: 18px !important;
    }

    /* Submit Button */
    #submitbutton {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
        color: white !important;
        border: none !important;
        padding: 38px 40px !important;
        border-radius: 12px !important;
        font-size: 18px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 1px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3) !important;
        margin-top: 20px !important;
    }

    #submitbutton:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4) !important;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 20px !important;
    }

    /* Select Dropdowns */
    select.form-control {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        background-position: right 12px center !important;
        background-repeat: no-repeat !important;
        background-size: 16px !important;
        padding-right: 40px !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .panel-heading h3 {
            font-size: 1.8rem !important;
        }
        
        .panel-body {
            padding: 20px !important;
        }
        
        .form-control {
            font-size: 16px !important;
            padding: 14px 16px !important;
        }
        
        label {
            font-size: 15px !important;
        }
    }

    /* Loading Animation */
    .loader {
        text-align: center;
        padding: 20px;
        color: #4f46e5;
        font-size: 18px;
        font-weight: 600;
    }

    .loader i {
        font-size: 2rem;
        animation: spin 1s linear infinite;
        margin-bottom: 10px;
        display: block;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Row Spacing */
    .row {
        margin-bottom: 15px !important;
    }

    /* HR Styling */
    hr {
        border: none !important;
        height: 2px !important;
        background: linear-gradient(90deg, transparent, #e2e8f0, transparent) !important;
        margin: 30px 0 !important;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 15px !important;
        border: none !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
    }

    .modal-header {
        border-radius: 15px 15px 0 0 !important;
        border: none !important;
    }

    .modal-header .close {
        color: white !important;
        opacity: 0.8 !important;
        text-shadow: none !important;
    }

    .modal-header .close:hover {
        opacity: 1 !important;
    }

    .modal-body {
        padding: 30px !important;
    }

    .modal-footer {
        border: none !important;
        padding: 20px 30px 30px !important;
    }

    .modal-footer .btn {
        padding: 12px 30px !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        font-size: 16px !important;
    }

    .modal-footer .btn-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        border: none !important;
    }

    .modal-footer .btn-secondary {
        background: #6b7280 !important;
        border: none !important;
    }
</style>

<form class="col-sm-12 col-xs-12" method="post" action="<?php echo base_url('booking'); ?>" enctype="multipart/form-data" >
  <input type="hidden" name="action" value="add_appointment" />
  <input type="hidden" id="paitent_type" name="paitent_type" value="new_patient" />
  <div class="row">
    <div class="col-sm-12 col-xs-12 panel panel-piluku">
      <div class="panel-heading">
        <h3 class="heading">Appointment Booking</h3>
      </div>
      <div class="panel-body profile-edit">
      <p id="msg_area" class="delete"></p>
        <p>
        <div class="row">
		<div class="form-group col-sm-1 col-xs-12">
            <input value="" placeholder="ISD Code" id="isd_code" by="isd_code" name="isd_code" type="text" class="form-control" >
          </div>
          <div class="form-group col-sm-4 col-xs-12">
            <input value="" placeholder="Phone number of wife" id="phone_number" by="phone" name="phone_number" type="text" class="form-control validate" >
          </div>
          <div class="form-group col-sm-1 col-xs-12">
          	<p>OR </p>
          </div>
          <div class="form-group col-sm-4 col-xs-12">
            <input value="" placeholder="IIC ID" id="iic_id" by="patient" type="text" class="form-control validate" >
          </div>
          
          	<div class="form-group col-sm-2 col-xs-12">
                <input value="Search" id="search_patient"  type="button" class="form-control validate" >
            </div>
        </div>        
        <hr/>        
        <div id="add_section" style="display:none;"> 
        
          <div class="row paitent_id_div" style="display:none;">
               <div class="form-group col-sm-3 col-xs-12" align="center"></div>                               	
               <div class="form-group col-sm-6 col-xs-12" align="center">
                    <label for="item_name">IIC ID (Required)</label>
                    <input value="" placeholder="IIC ID" readonly="readonly" id="paitent_id" name="paitent_id" type="text" class="form-control validate empty-field" required>
               </div>
               <div class="form-group col-sm-3 col-xs-12" align="center"></div>
	      </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Patient Name (Required)</label>
                <input value="" id="wife_name" name="wife_name" type="text" class="form-control validate in_field empty-field" required>
           </div>
         </div>
		 
		 <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Spouse Name (Required)</label>
                <input value="" id="husband_name" name="husband_name" type="text" class="form-control validate in_field empty-field" required>
           </div>
         </div>
         
         <div class="row">            
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Patient Phone Number (Required)</label>
                <input value="" id="wife_phone" readonly="readonly" name="wife_phone" type="text" class="form-control validate in_field empty-field" required>
                <input type="hidden" id="paitent_id" name="paitent_id" value="<?php echo isset($patient_id) ? $patient_id : ''; ?>" required>
           </div>
         </div>
         
         <div class="row">   
           <div class="form-group col-sm-6 col-xs-12">
                <label for="item_name">Email (Required)</label>
                <input value="" id="wife_email" name="wife_email" required type="text" class="form-control validate in_field empty-field">
           </div>
         </div>
         
         <div class="row">     
         	<div class="form-group col-sm-6 col-xs-12 role" style="display:none;" id="patient_nationality">
                <label for="statuss">Nationality</label>
                <select name="nationality" id="nationality" class="patient_source empty-field" required>
                    <option value="">Select</option>
                    <option value="indian">Indian</option>
                    <option value="non-indian">Non-indian</option>
                </select>
            </div>
         </div>
         
         <div class="row">            
             <div class="form-group col-sm-6 col-xs-12 role">
                <label for="item_name">Reason of visit (Required)</label>
               <!--<textarea id="reason_of_visit" name="reason_of_visit" class="form-control validate empty-field" required></textarea>-->
			   
				<select name="reason_of_visit" id="reason_of_visit" class="empty-field" required>
                    <option value="">Select</option>
                    <option value="First Visit">First Visit</option>
                    <option value="Consulted Not Booked">Consulted Not Booked</option>
                </select>
           </div>
         </div>
         <!-- <div class="row" >            
            <div class="form-group col-sm-6 col-xs-12 role">
            	<label for="item_name">Counsellor Name</label>
                <select id="councellor" name="councellor" class="empty-field" required>
                    <option value="">Select</option>
                    <option value="HARSH">HARSH</option>
                    <option value="Harshita">Harshita</option>
                    <option value="Anamika">Anamika</option>
            </select>
            </div>
		</div>-->
         <div class="row">            
            <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Centre (Required)</label>
                <select name="appoitment_for" class="empty-field" id="appoitment_for" required>
                    <option value="">Select</option>
                    <?php $center = $all_method->get_center_list(); 
					foreach($center as $key => $center){  ?>
                  	<option value="<?php echo $center['center_number']; ?>"><?php echo $center['center_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
         </div>
         
         <div class="row" id="camp_selection_div" style="display: none;">            
            <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Camp (Optional)</label>
                <select name="camp_selection" class="empty-field" id="camp_selection">
                    <option value="">Select Camp</option>
                </select>
            </div>
         </div>
         
        <div class="row appoitmented_doctor" style="display:none;">            
            <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Doctor (Required)</label>
                <select name="appoitmented_doctor" class="empty-field" id="appoitmented_doctor" required>
                    <option value="">Select</option>
                </select>
            </div>
         </div>
         
         <div class="row appoitmented_date" style="display:none;">            
            <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Appointment date (Required)</label>
                <input value="" id="appoitmented_date" autocomplete="off" name="appoitmented_date" type="text" class="form-control empty-field validate" >
            </div>
         </div>
         
         <div class="row appoitmented_slot" style="display:none;">            
            <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Appoitmented_slot (Required)</label>
                <select name="appoitmented_slot" class="empty-field" id="appoitmented_slot" required>
                    <option value="">Select</option>
                </select>
            </div>
         </div>
		  <div class="row">            
            <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Lead Source</label>
                <select name="lead_source" class="empty-field" id="lead_source" required>
                    <option value="">Select Source</option>
                    <option value="Telecalling">Telecalling</option>
                    <option value="Walk In">Walk-in</option>
					<option value="Doctor-Referral">Doctor Referral</option>
					<option value="International">International</option>
					<option value="Corporate">Corporate</option>
					<option value="Camp">Camp</option>
					<option value="D/S">D/S</option>Patient Referral
					<option value="Ayushpay">Ayushpay</option>
					<option value="Patient Referral">Patient Referral</option>
                </select>
            </div>
         </div>
		 
		 <div class="row" id="sub_lead_source_div" style="display: none; margin-top: 10px;">            
            <div class="form-group col-sm-6 col-xs-12 role">
            	<label>Doctor Name</label>
                <select class="empty-field" id="sub_lead_source" name="sub_lead_source">
                	<option value="">--Select--</option>
                    <?php $doctor_referral = $all_method->doctor_referral_list();
					foreach($doctor_referral as $key => $doctor_referral){  ?>
                  	<option value="<?php echo $doctor_referral['ID']; ?>"><?php echo $doctor_referral['doctor_name']; ?></option>
                    <?php } ?>                  
                </select>
            </div>
		</div>

         <div class="row">            
            <div class="form-group col-sm-6 col-xs-12 role">
            	<label>Counsellor Name</label>
                <select class="empty-field" id="councellor" name="councellor" required>
                	<option value="">--Select--</option>
                    <?php $counsellor_name = $all_method->get_counsellor_list();
					foreach($counsellor_name as $key => $counsellor_name){  ?>
                  	<option value="<?php echo $counsellor_name['name']; ?>"><?php echo $counsellor_name['name']; ?></option>
                    <?php } ?>                  
                </select>
            </div>
		</div>

        <!-- <div class="row" id="camp_center_div" style="display: none; margin-top: 10px;">            
            <div class="form-group col-sm-6 col-xs-12 role">
                <label for="statuss">Camp Centre (Required)</label>
                <select name="camp_center" class="empty-field" id="camp_center">
                    <option value="">Select</option>
                    <?php $center = $all_method->get_center_list(); 
					
					foreach($center as $key => $center){  ?>
                  	<option value="<?php echo $center['center_number']; ?>"><?php echo $center['center_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
         </div> -->
         <div class="clearfix"></div>
	     <div class="form-group col-sm-12 col-xs-12">
	        <input type="submit" id="submitbutton" class="btn btn-large" value="Book Appointment" />
         </div>
         </div>
      </div>
      </p>
    </div>
  </div>
</form>

<!-- Loader -->
<div id="loader_div" class="loader" style="display: none;">
    <i class="fa fa-spinner"></i>
    <p>Loading...</p>
</div>

<!-- Camp Creation Modal -->
<div id="campModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white;">
                <h4 class="modal-title">Create New Camp</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="campForm">
                    <div class="row">
                        <div class="form-group col-sm-6 col-xs-12">
                            <label for="camp_name">Camp Name (Required)</label>
                            <input type="text" id="camp_name" name="camp_name" class="form-control" required>
                        </div>
                        <div class="form-group col-sm-6 col-xs-12">
                            <label for="camp_description">Camp Description</label>
                            <textarea id="camp_description" name="camp_description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 col-xs-12">
                            <label for="start_date">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-control">
                        </div>
                        <div class="form-group col-sm-6 col-xs-12">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-control">
                        </div>
                    </div>
                    <input type="hidden" id="modal_center_id" name="center_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="saveCampBtn" class="btn btn-primary">Create Camp</button>
            </div>
        </div>
    </div>
</div>
  

<script type="text/javascript">
function appointsubmit(){
    $('#submitbutton').hide();
}

//Centre Doctor
$('#appoitment_for').on("change", function() {
    // Hide and reset dependent fields
    $('div.appoitmented_doctor').hide();
    $('div.appoitmented_date').hide();
    $('div.appoitmented_slot').hide();
    $('div#camp_selection_div').hide();
    $('#camp_selection').html('<option value="">Select Camp</option>');
    $('#appoitmented_doctor').html('<option value="">Select Doctor</option>');
    // Show loader
    $('#loader_div').show();
    var centre_id = $(this).val();
    if (centre_id !== '') {
        $.ajax({
            url: '<?php echo base_url('billingcontroller/get_camps_by_center')?>',
            type: 'POST',
            data: { center_id: centre_id },
            dataType: 'html',
            success: function(response) {
                $('#camp_selection').html(response);
                $('div#camp_selection_div').show();
            },
            error: function(xhr, status, error) {
                console.error('Camp loading error:', status, error);
                $('#camp_selection').html('<option value="">Error loading camps</option>');
                $('div#camp_selection_div').show();
            },
            complete: function() {
                $('#loader_div').hide();
            }
        });
        $.ajax({
            url: '<?php echo base_url('billingcontroller/search_doctor_in_Camp')?>',
            type: 'POST',
            data: { centre_id: centre_id },
            dataType: 'html',
            success: function(response) {
                console.log(response);
                $('#appoitmented_doctor').html(response);
                $('div.appoitmented_doctor').show();
            },
            error: function(xhr, status, error) {
                console.error('Doctor loading error:', status, error);
                $('#appoitmented_doctor').html('<option value="">Error loading doctors</option>');
                $('div.appoitmented_doctor').show();
            },
            complete: function() {
                $('#loader_div').hide();
            }
        });
    } else {
        $('div.appoitmented_doctor').hide();
        $('div#camp_selection_div').hide();
        $('#loader_div').hide();
    }
});

$('#appoitmented_doctor').on("change", function() {
	$('#loader_div').show();
	var doctor_id = $(this).val();
	$('input#appoitmented_date').val('');
	if(doctor_id != ''){
		$('div.appoitmented_date').show();
	}else{
		$('div.appoitmented_date').hide();
	}
	$('#loader_div').hide();
});

$( function() {
    $( "#appoitmented_date" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
		 	minDate: 0,
			onSelect: function(dateStr) {
				$('#loader_div').show();				
				var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
				var appoitmented_doctor = $('#appoitmented_doctor').val();
				$.ajax({
					url: '<?php echo base_url('billingcontroller/doctor_slots')?>',
					type: 'POST',
					data: {selected:startDate, appoitmented_doctor:appoitmented_doctor},
					success: function(data) {
						$('#appoitmented_slot').empty().append(data);
						$('div.appoitmented_slot').show();
						$('#loader_div').hide();
					}
				});
			}
		});
} );


$(document).on('click',"#search_patient",function(e) {
	$('#loader_div').show();
	$('#msg_area').empty().hide();
	$('.empty-field').val('');
	$('#paitent_type').val('');
    $('#nationality').attr("required", false);
	$('div.appoitmented_doctor').hide();
	$('div.appoitmented_date').hide();
	$('div.appoitmented_slot').hide();
	
	var phone_number = $('#phone_number').val();
	var phone_by = $('#phone_number').attr('by');
	var patient_id = $('#iic_id').val();
	var patient_by = $('#iic_id').attr('by');
	
	if(phone_number != ''){
		var data = {search_this:phone_number, search_by:phone_by};
		 search_patient(data);
	}else if(patient_id != ''){
		var data = {search_this:patient_id, search_by:patient_by};
		 search_patient(data);
	}else{
		 $('#msg_area').removeClass().addClass('error').html('Please enter patient phone number or IIC ID').show();
		 $('#loader_div').hide();
	}
});

function search_patient(data){
	$('#patient_nationality').hide();
	$.ajax({
		url: '<?php echo base_url('billingcontroller/search_appointment')?>',
		data: data,
		dataType: 'json',
		type: 'POST',
		success: function(data)
		{
			if(data.status == 0){  
				$('#msg_area').removeClass().addClass('error').html(data.message).show(); 
			}
			if(data.status == 'appointment_booked'){
				$('#msg_area').removeClass().addClass('info').html(data.message).show();
			}
			if(data.status == 'new_patient'){
				$('div.paitent_id_div').hide();
				$('#paitent_type').val(data.status);
				$('#msg_area').removeClass().addClass('success').html(data.message).show();
				$('#wife_phone').attr("readonly", true);
				$('#wife_phone').val($('#phone_number').val());
				$('#wife_email').empty().val("");				
				$('#patient_nationality').show();
				$('#nationality').attr("required", true);
				$('#add_section').show();
			}			
			if(data.status == 'exist_patient'){
				 $('#paitent_type').val(data.status);
				 $('#msg_area').removeClass().addClass('success').html(data.message).show();
				 $('#paitent_id').val(data.uhid);
				 $('div.paitent_id_div').show();
				 $('#wife_name').val(data.patient.wife_name);
				 $('#wife_phone').val(data.patient.wife_phone);
				 $('#wife_email').val(data.patient.wife_email);
				 $('#nationality [value='+data.patient.nationality+']').attr('selected', 'true');
				 $('.img_show').show();
				 $('#submitbutton').show();
				 $('#add_section').show();
			 }
			$('#loader_div').hide();
		} 
  });
}
$('#submitbutton').hide();
$('#search_patient').hide();
// Phone number validation
$('#phone_number').on("change, blur, keyup", function() { 
	$('#add_section').hide();
	$('#search_patient').hide();
	$('#iic_id').val('');
	var txtpan = $(this).val(); 
	phone_validate(txtpan);
});
$('#iic_id').on("change, blur, keyup", function() {
	$('#add_section').hide();
	$('#search_patient').hide();
	$('#phone_number').val('');
	$('#search_patient').show();
});

function phone_validate(mobile) {
   $('#loader_div').show();
	$('#msg_area').empty().hide();
	var pattern = /^\d{10}$/;
	if (pattern.test(mobile)) {
		$('#submitbutton').show(); 
		$('#search_patient').show(); 
		$('#loader_div').hide(); 
		return true; 
	}
	$('#msg_area').removeClass().addClass('error').html('Please enter a valid 10-digit mobile number').show();
	$('#submitbutton').hide();
	$('#search_patient').hide();
	$("html, body").animate({ scrollTop: 0 }, "slow");
    $('#loader_div').hide();
	return false;
}

// Email validation
$('#wife_email').on("change, blur", function() {   
	var txtpan = $(this).val(); 
	email_validate(txtpan);
});
function email_validate(email) {
   $('#loader_div').show();
	$('#msg_area').empty().hide();
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!regex.test(email)) {
	   $('#msg_area').removeClass().addClass('error').html('Please enter a valid email address').show();
	   $('#submitbutton').hide();
	   $("html, body").animate({ scrollTop: 0 }, "slow");
       $('#loader_div').hide();
	}else{
       $('#submitbutton').show();
       $('#loader_div').hide();
	   return true;
	}
}

    $(document).ready(function(){
            // When lead source changes
            $("#lead_source").change(function(){
                if ($(this).val() == "Doctor-Referral") {
                    $("#sub_lead_source_div").show();  // Show sub_lead_source
                } else {
                    $("#sub_lead_source_div").hide();  // Hide if another option is selected
                    $("#sub_lead_source").val("");     // Reset selection
                }
				
				
				if ($(this).val() == "Camp") {
                    $("#camp_center_div").show();  // Show sub_lead_source
                } else {
                    $("#camp_center_div").hide();  // Hide if another option is selected
                    $("#camp_center").val("");     // Reset selection
                }
            });
            
            // When camp selection changes, check templates
            $("#camp_selection").change(function(){
                var camp_number = $(this).val();
                var center_id = $('#appoitment_for').val();
                
                if(camp_number != '' && center_id != ''){
                    $('#loader_div').show();
                    $.ajax({
                        url: '<?php echo base_url('billingcontroller/check_camp_templates')?>',
                        data: {camp_number: camp_number, center_id: center_id},
                        dataType: 'json',
                        type: 'POST',
                        success: function(response) {
                            if(response.status == 'success'){
                                if(response.templates_exist){
                                    $('#msg_area').removeClass().addClass('success').html('Camp selected successfully! Templates are available.').show();
                                } else {
                                    $('#msg_area').removeClass().addClass('info').html('Camp selected. No templates found for this camp.').show();
                                }
                            }
                            $('#loader_div').hide();
                        },
                        error: function() {
                            $('#msg_area').removeClass().addClass('error').html('Error checking camp templates.').show();
                            $('#loader_div').hide();
                        }
                    });
                }
            });
            
        
            
            // Save camp button
            $("#saveCampBtn").click(function(){
                // Validate form
                var camp_name = $('#camp_name').val().trim();
                if(camp_name == ''){
                    $('#msg_area').removeClass().addClass('error').html('Please enter camp name.').show();
                    return;
                }
                
                var formData = $('#campForm').serialize();
                $('#loader_div').show();
                
                $.ajax({
                    url: '<?php echo base_url('billingcontroller/create_camp_and_check_templates')?>',
                    data: formData,
                    dataType: 'json',
                    type: 'POST',
                    success: function(response) {
                        if(response.status == 'success'){
                            $('#msg_area').removeClass().addClass('success').html(response.message).show();
                            
                            // Refresh camp list
                            var center_id = $('#appoitment_for').val();
                            $.ajax({
                                url: '<?php echo base_url('billingcontroller/get_camps_by_center')?>',
                                data: {center_id: center_id},
                                dataType: 'html',
                                type: 'POST',
                                success: function(data) {
                                    $('#camp_selection').empty().append(data);
                                }
                            });
                            
                            // Close modal and reset form
                            $('#campModal').modal('hide');
                            $('#campForm')[0].reset();
                            
                            // Show template status
                            if(response.templates_exist){
                                $('#msg_area').removeClass().addClass('success').html('Camp created successfully! Templates are available.').show();
                            } else {
                                $('#msg_area').removeClass().addClass('info').html('Camp created successfully! No templates found for this camp.').show();
                            }
                        } else {
                            $('#msg_area').removeClass().addClass('error').html(response.message).show();
                        }
                        $('#loader_div').hide();
                    },
                    error: function() {
                        $('#msg_area').removeClass().addClass('error').html('Error creating camp.').show();
                        $('#loader_div').hide();
                    }
                });
            });
    });
</script>
