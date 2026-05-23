<?php $all_method =&get_instance();
   $consultation_data = $all_method->get_consultation($appointments['ID']);   
   $patient_data = get_patient_detail($consultation_data['patient_id']);
   $patient_doctor_consultation = patient_doctor_consultation_data($appointments['ID'], $consultation_data['patient_id']);
   $sql2 = "Select * from ".$this->config->item('db_prefix')."doctors where ID='".$_SESSION['logged_doctor']['doctor_id']."'"; 
   $select_result2 = run_select_query($sql2);
   ?>
<style>
   /* Enhanced Follow-up Page Styles */
   .follow-up-container {
   background: #f8f9fa;
   min-height: 100vh;
   padding: 20px 0;
   }
   .main-card {
   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
   border: none;
   border-radius: 8px;
   margin-bottom: 20px;
   }
   .card-header-custom {
   background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
   color: white;
   border-radius: 8px 8px 0 0;
   padding: 20px;
   margin: 0;
   }
   .card-header-custom h3 {
   margin: 0;
   font-weight: 600;
   font-size: 1.5rem;
   }
   .section-card {
   background: white;
   border-radius: 8px;
   box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
   margin-bottom: 20px;
   overflow: hidden;
   }
   .section-header {
   background: #f8f9fa;
   padding: 15px 20px;
   border-bottom: 2px solid #e9ecef;
   font-weight: 600;
   color: #495057;
   font-size: 1.1rem;
   }
   .section-content {
   padding: 20px;
   }
   .form-group-enhanced {
   margin-bottom: 20px;
   }
   .form-group-enhanced label {
   font-weight: 600;
   color: #495057;
   margin-bottom: 8px;
   display: block;
   }
   .form-control-enhanced {
   border: 2px solid #e9ecef;
   border-radius: 6px;
   padding: 12px 15px;
   font-size: 14px;
   transition: all 0.3s ease;
   }
   .form-control-enhanced:focus {
   border-color: #667eea;
   box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
   }
   .table-enhanced {
   background: white;
   border-radius: 8px;
   overflow: hidden;
   box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
   }
   .table-enhanced thead th {
   background: #667eea;
   color: white;
   font-weight: 600;
   padding: 15px 12px;
   border: none;
   text-align: center;
   }
   .table-enhanced tbody td {
   padding: 12px;
   border: 1px solid #e9ecef;
   vertical-align: middle;
   }
   .table-enhanced tbody tr:nth-child(even) {
   background: #f8f9fa;
   }
   .table-enhanced tbody tr:hover {
   background: #e3f2fd;
   }
   .checkbox-enhanced {
   position: relative;
   margin-right: 10px;
   }
   .checkbox-enhanced input[type="checkbox"] {
   position: relative;
   left: 0;
   opacity: 1;
   margin-right: 8px;
   transform: scale(1.2);
   }
   .radio-enhanced {
   margin-right: 20px;
   }
   .radio-enhanced input[type="radio"] {
   position: relative;
   left: 0;
   opacity: 1;
   margin-right: 8px;
   transform: scale(1.2);
   }
   .btn-enhanced {
   padding: 12px 30px;
   font-weight: 600;
   border-radius: 6px;
   transition: all 0.3s ease;
   }
   .btn-primary-enhanced {
   background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
   border: none;
   }
   .btn-primary-enhanced:hover {
   transform: translateY(-2px);
   box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
   }
   .patient-info-card {
   background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
   color: white;
   border-radius: 8px;
   padding: 20px;
   margin-bottom: 20px;
   }
   .patient-info-card h4 {
   margin: 0 0 10px 0;
   font-weight: 600;
   }
   .patient-info-card p {
   margin: 5px 0;
   font-size: 14px;
   }
   .medicine-table {
   font-size: 12px;
   }
   .medicine-table th {
   background: #495057;
   color: white;
   padding: 8px 6px;
   font-size: 11px;
   text-align: center;
   }
   .medicine-table td {
   padding: 6px;
   font-size: 11px;
   }
   .medicine-table input, .medicine-table select {
   font-size: 11px;
   padding: 4px 6px;
   border: 1px solid #ddd;
   border-radius: 3px;
   }
   .multiselect-container>li>a>label {
   padding: 8px 20px;
   font-size: 14px;
   }
   .open > .dropdown-menu {
   width: 350px;
   max-height: 300px;
   overflow: auto;
   border-radius: 6px;
   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
   }
   .btn-group {
   max-width: 100%;
   }
   button.multiselect.dropdown-toggle.btn.btn-default {
   width: 100%;
   overflow: hidden;
   border: 2px solid #e9ecef;
   border-radius: 6px;
   padding: 12px 15px;
   }
   .alert-info-custom {
   background: #e3f2fd;
   border: 1px solid #bbdefb;
   color: #1565c0;
   border-radius: 6px;
   padding: 15px;
   margin-bottom: 20px;
   }
   .follow-up-section {
   background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
   border-radius: 8px;
   padding: 20px;
   margin-top: 20px;
   }
   .follow-up-section h4 {
   color: #495057;
   margin-bottom: 20px;
   font-weight: 600;
   }
   .purpose-radio-group {
   display: flex;
   flex-wrap: wrap;
   gap: 20px;
   margin-top: 15px;
   }
   .purpose-radio-group .radio-enhanced {
   background: white;
   padding: 10px 15px;
   border-radius: 6px;
   border: 2px solid #e9ecef;
   cursor: pointer;
   transition: all 0.3s ease;
   }
   .purpose-radio-group .radio-enhanced:hover {
   border-color: #667eea;
   background: #f8f9fa;
   }
   .purpose-radio-group .radio-enhanced input[type="radio"]:checked + label {
   color: #667eea;
   font-weight: 600;
   }
   .purpose-radio-group .radio-enhanced input[type="radio"]:checked {
   border-color: #667eea;
   }
   /* Performance optimizations */
   .table-enhanced, .medicine-table {
   will-change: transform;
   }
   .form-control-enhanced {
   will-change: border-color, box-shadow;
   }
   .btn-enhanced {
   will-change: transform, box-shadow;
   }
   /* Lazy loading for images */
   img {
   loading: lazy;
   }
   /* Optimize animations */
   * {
   transition-duration: 0.2s;
   }
   @media (max-width: 768px) {
   .follow-up-container {
   padding: 10px 0;
   }
   .section-content {
   padding: 15px;
   }
   .purpose-radio-group {
   flex-direction: column;
   gap: 10px;
   }
   .medicine-table {
   font-size: 10px;
   }
   .medicine-table th, .medicine-table td {
   padding: 4px 2px;
   }
   }
   button.multiselect.dropdown-toggle {
   overflow: visible !important;   /* allow dropdown to expand */
   }
   .multiselect-container {
   z-index: 10000 !important;       /* make sure it's on top */
   position: absolute !important;  /* float above other elements */
   }
   .multiselect-container .multiselect-dropdown {
   z-index: 10001 !important;
   position: absolute !important;
   max-height: 200px !important;
   overflow-y: auto !important;
   }
   .multiselect-container .multiselect-dropdown ul {
   z-index: 10001 !important;
   position: relative !important;
   }
   .select2-container {
   z-index: 10000 !important;
   }
   .bootstrap-select .dropdown-menu {
   z-index: 2000 !important;
   }
   .dropdown-menu {
   position: absolute !important;
   z-index: 9999 !important;
   }
   .parent-container {
   overflow: visible !important;
   }
   /* Ensure dropdowns are not clipped by parent containers */
   .section-card, .table-responsive, .table-enhanced {
   overflow: visible !important;
   }
   /* Fix for table cells containing multiselect */
   .table td {
   overflow: visible !important;
   position: relative;
   }
   /* Ensure multiselect button and dropdown are properly positioned */
   .multiselect {
   position: relative !important;
   }
   .multiselect .multiselect-container {
   position: absolute !important;
   top: 100% !important;
   left: 0 !important;
   right: 0 !important;
   z-index: 10000 !important;
   }
   /* Additional fix for Bootstrap multiselect */
   .bootstrap-multiselect .dropdown-menu {
   z-index: 10000 !important;
   position: absolute !important;
   }
   .bootstrap-multiselect .dropdown-menu ul {
   z-index: 10001 !important;
   }
   .open > .dropdown-menu {
    width: 450px;
}
</style>
<div class="follow-up-container">
   <div class="container-fluid">
      <form method="post" action="<?php echo base_url('doctors/follow_up_clean/'.$appointments['ID']); ?>" enctype="multipart/form-data">
         <input type="hidden" name="action" value="add_consultation_done" />
         <input type="hidden" name="appointment_id" value="<?php echo $appointments['ID']; ?>" />
         <input type="hidden" name="patient_id" value="<?php echo $patient_data['patient_id']; ?>" />
         <input type="hidden" name="wife_phone" value="<?php echo $patient_data['wife_phone']; ?>" />
         <input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $_SESSION['logged_doctor']['doctor_id']; ?>" />
         <input type="hidden" name="center_number" id="center_number" value="<?php echo $select_result2['center_id']; ?>" />
         <?php if($appointments['partial_billing'] == 1){ ?>
         <input type="hidden" name="doc_consult_id" value="<?php echo $patient_doctor_consultation['ID']; ?>" />
         <?php } ?>
         <div class="patient-info-card">
            <h4><i class="fa fa-user"></i> Patient Follow-up Consultation</h4>
            <p><strong>Patient ID:</strong> <?php echo isset($patient_data['patient_id']) ? $patient_data['patient_id'] : 'N/A'; ?></p>
            <p><strong>Patient Name:</strong> <?php echo isset($patient_data['patient_name']) ? $patient_data['patient_name'] : 'N/A'; ?></p>
            <p><strong>Phone:</strong> <?php echo isset($patient_data['phone']) ? $patient_data['phone'] : 'N/A'; ?></p>
         </div>
         <div class="main-card">
            <div class="card-header-custom">
               <h3><i class="fa fa-stethoscope"></i> Follow-up Consultation Details</h3>
            </div>
            <div class="section-card">
               <div class="section-header">
                  <i class="fa fa-clipboard"></i> Presenting Complaints
               </div>
               <div class="section-content">
                  <table class="table table-enhanced">
                     <thead>
                        <tr>
                           <th style="width: 30%;">Complaint Type</th>
                           <th style="width: 35%;">Patient</th>
                           <th style="width: 35%;">Spouse</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td><strong>Presenting Complaints</strong></td>
                           <td>
                              <input type="text" name="female_findings" class="form-control form-control-enhanced" placeholder="Enter patient complaints">
                           </td>
                           <td>
                              <input type="text" name="male_findings" class="form-control form-control-enhanced" placeholder="Enter spouse complaints">
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            
            <div class="section-card">
               <div class="section-header">
                  <i class="fa fa-medkit"></i> Medication Advised Ipd
                  <label class="checkbox-enhanced pull-right">
                     <input type="checkbox" id="medicine_suggestion_ipd" value="1" name="medicine_suggestion_ipd" checked />
                     Enable Medication
                  </label>
               </div>
               <div class="section-content">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group-enhanced">
                           <label><i class="fa fa-female"></i> Patient Medication</label>
                           <select class="form-control multidselect_dropdown" multiple id="female_medicine_suggestion_list_ipd" name="female_medicine_suggestion_list_ipd[]">
                              <?php if(!empty($consultation_medicine_ipd)) { 
                                 foreach($consultation_medicine_ipd as $key => $val) { 
                                    $is_selected = in_array($val['item_number'], array('OPD_21','OPD_23','OPD_35','OPD_44', 'OPD_46')) ? 'selected' : '';
                              ?>
                                 <option value="<?php echo $val['item_number']; ?>" medicine="<?php echo $val['item_name']; ?>" <?php echo $is_selected; ?>>
                                    <?php echo $val['item_name']; ?>
                                 </option>
                              <?php  } 
                              } ?>
                              <option value="0" medicine="NA">NA</option>
                           </select>
                        </div>
                        
                        <div class="table-responsive">
                           <table id="female_medicine_table_ipd" class="table table-bordered medicine-table" style="display:none;">
                              <thead>
                                 <tr>
                                    <th>Medicine</th>
                                    <th>Dosage</th>
                                    <th>Remarks</th>
                                    <th>Start on</th>
                                    <th>Days</th>
                                    <th>Route</th>
                                    <th>Frequency</th>
                                    <th>Timing</th>
                                    <th>Take</th>
                                 </tr>
                              </thead>
                              <tbody id="female_medicine_suggestion_table_ipd"></tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
                         

            <div class="section-card">
               <div class="section-header">
                  <i class="fa fa-file-text-o"></i> Advisory Templates
               </div>
               <div class="section-content">
                  <div class="form-group-enhanced">
                     <label>Select Advisory Templates</label>
                     <select class="form-control multidselect_dropdown_2" multiple="multiple" id="advisory_templates" name="advisory_templates[]">
                        <option value="pre_embryo_transfer_html">PRE EMBRYO TRANSFER</option>
                        <option value="post_operative_instructions_after_ovum_pick_up_html">POST OPERATIVE INSTRUCTIONS AFTER OVUM PICK UP</option>
                        <option value="post_operative_instructions_after_ovarian_prp_html">POST OPERATIVE INSTRUCTIONS AFTER OVARIAN PRP</option>
                        <option value="post_fnac_testes_tprp_tesa_pesa_micro_tese_html">POST FNAC TESTES/ TPRP/TESA/PESA/MICRO TESE</option>
                        <option value="post_embryo_transfer_html">POST EMBRYO TRANSFER</option>
                        <option value="patient_information_section_html">PATIENT INFORMATION</option>
                        <option value="ivf_vitro_fertilization_ivf_information_package_html">IN VITRO FERTILIZATION (IVF) INFORMATION PACKAGE</option>
                        <option value="instructions_for_semen_collection_html">INSTRUCTIONS FOR SEMEN COLLECTION</option>
                        <option value="day_2_day_5_fet_prescription_html">DAY 2 - DAY 5 FET PRESCRIPTION</option>
                     </select>
                  </div>
               </div>
            </div>-
            <div class="follow-up-section">
               <h4><i class="fa fa-calendar"></i> Next Follow-up Appointment</h4>
               <input type="hidden" id="follow_up" checked value="1" name="follow_up" />
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group-enhanced">
                        <label for="appoitment_for">Centre <span class="text-danger">*</span></label>
                        <select name="appoitment_for" style="height: 50px !important;" required class="form-control form-control-enhanced empty-field" id="appoitment_for">
                           <option value="">Select Centre</option>
                           <?php $center = $all_method->get_center_list(); foreach($center as $key => $center){?>
                           <option value="<?php echo $center['center_number']; ?>"><?php echo $center['center_name']; ?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6 appoitmented_doctor" style="display:none;">
                     <div class="form-group-enhanced">
                        <label for="appoitmented_doctor">Doctor <span class="text-danger">*</span></label>
                        <select name="appoitmented_doctor" style="height: 50px !important;" disabled class="form-control form-control-enhanced empty-field" id="appoitmented_doctor">
                           <option value="">Select Doctor</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6 appoitmented_date" style="display:none;">
                     <div class="form-group-enhanced">
                        <label for="appoitmented_date">Appointment Date <span class="text-danger">*</span></label>
                        <input value="" id="appoitmented_date" disabled autocomplete="off" name="follow_up_date" type="text" class="form-control form-control-enhanced empty-field validate" placeholder="Select Date">
                     </div>
                  </div>
                  <div class="col-md-6 appoitmented_slot" style="display:none;">
                     <div class="form-group-enhanced">
                        <label for="appoitmented_slot">Time Slot <span class="text-danger">*</span></label>
                        <select name="appoitmented_slot" disabled style="height: 50px !important;" class="form-control form-control-enhanced empty-field" id="appoitmented_slot">
                           <option value="">Select Time Slot</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="form-group-enhanced">
                  <label>Purpose of Next Follow-up</label>
                  <div class="purpose-radio-group">
                     <div class="radio-enhanced">
                        <input type="radio" name="follow_up_purpose" value="TVS" id="purpose_tvs">
                        <label for="purpose_tvs">TVS</label>
                     </div>
                     <div class="radio-enhanced">
                        <input type="radio" name="follow_up_purpose" checked value="FOLLOW UP VISIT" id="purpose_followup">
                        <label for="purpose_followup">Follow Up Visit</label>
                     </div>
                     <div class="radio-enhanced">
                        <input type="radio" name="follow_up_purpose" value="PROCEDURE" id="purpose_procedure">
                        <label for="purpose_procedure">Procedure</label>
                     </div>
                  </div>
               </div>
            </div>
            <div id="loader_div" style="display: none; text-align: center; margin: 20px 0;">
               <i class="fa fa-spinner fa-spin fa-2x"></i>
               <p>Loading...</p>
            </div>
            
            <div class="text-center" style="margin-top: 30px;">
               <button type="submit" id="submit-followup-btn" class="btn btn-primary btn-enhanced btn-primary-enhanced">
               <i class="fa fa-save"></i> Submit Follow-up Consultation
               </button>
            </div>
      </form>
      </div>
   </div>
</div>
<script>
   // Performance optimizations - cache DOM elements
   var $appointmentFor = $('#appoitment_for');
   var $appointmentDoctor = $('#appoitmented_doctor');
   var $appointmentDate = $('#appoitmented_date');
   var $appointmentSlot = $('#appoitmented_slot');
   var $doctorDiv = $('div.appoitmented_doctor');
   var $dateDiv = $('div.appoitmented_date');
   var $slotDiv = $('div.appoitmented_slot');
   var $loaderDiv = $('#loader_div');
   
   //Centre Doctor - Optimized
   $appointmentFor.on("change", function() {
   	$doctorDiv.hide();
   	$dateDiv.hide();
   	$slotDiv.hide();
   	
   	$loaderDiv.show();
   	var centre_id = $(this).val();
   	if(centre_id != ''){
   		$.ajax({
   		url: '<?php echo base_url('billingcontroller/search_doctor')?>',
   		data: {centre_id:centre_id},
   		dataType: 'json',
   		method:'post',
   		success: function(data)
   		{
   			$appointmentDoctor.empty().append(data);
   			$appointmentDoctor.prop({'required': true, 'disabled': false});
   			$doctorDiv.show();
   			$loaderDiv.hide();			
   		} 
     });
       }
   	else{
   		$doctorDiv.hide();
   		$loaderDiv.hide();
   	}
   });
   
   $appointmentDoctor.on("change", function() {
   	$loaderDiv.show();
   	var doctor_id = $(this).val();
   	$appointmentDate.val('');
   	if(doctor_id != ''){
   		$appointmentDate.prop({'required': true, 'disabled': false});
   		$dateDiv.show();
   	}else{
   		$dateDiv.hide();
   	}
   	$loaderDiv.hide();
   });
   
   $( function() {
       $appointmentDate.datepicker({
   			dateFormat: 'yy-mm-dd',
   			changeMonth: true,
   			changeYear: true,
   		 	minDate: 0,
   			onSelect: function(dateStr) {
   				$loaderDiv.show();				
   				var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
   				var appoitmented_doctor = $appointmentDoctor.val();
   				$.ajax({
   					url: '<?php echo base_url('billingcontroller/doctor_slots')?>',
   					type: 'POST',
   					data: {selected:startDate, appoitmented_doctor:appoitmented_doctor},
   					success: function(data) {
   						$appointmentSlot.empty().append(data);
   						$appointmentSlot.prop({'required': true, 'disabled': false});
   						$slotDiv.show();
   						$loaderDiv.hide();
   					}
   				});
   			}
   		});
   } );
   
   $("#follow_up").change(function() {
   	$doctorDiv.hide();
   	$dateDiv.hide();
   	$slotDiv.hide();
   	
   	$appointmentFor.prop({'selectedIndex': 0, 'required': false, 'disabled': true});
   	$appointmentDoctor.prop({'selectedIndex': 0, 'required': false, 'disabled': true});
   	$appointmentDate.val('').prop({'required': false, 'disabled': true});
   	$appointmentSlot.prop({'selectedIndex': 0, 'required': false, 'disabled': true});
   	
   	if(this.checked) {
   		$appointmentFor.prop({'required': true, 'disabled': false});
   	}
   });
   
   $( function() {
   $( ".datepicker" ).datepicker({
   		dateFormat: 'yy-mm-dd',
   		changeMonth: true,
   		changeYear: true,
   		onSelect: function(dateStr) {}
   	});
   });
   
   $("#medicine_suggestion").change(function() {
   //Male Medicine
   $("select#male_medicine_suggestion_list").prop('disabled',true);
   $("select#male_medicine_suggestion_list").parent().find('button').prop('disabled',true);
   $("select#male_medicine_suggestion_list").parent().find('button').addClass('disabled');
   $('option', $('#male_medicine_suggestion_list')).each(function(element) {
   	$(this).removeAttr('selected').prop('selected', false);
   });
   $("#male_medicine_suggestion_list").multiselect('refresh');	
   $("select#male_medicine_suggestion_list").prop('required',false);
   //Female Medicine
   $("select#female_medicine_suggestion_list").prop('disabled',true);
   $("select#female_medicine_suggestion_list").parent().find('button').prop('disabled',true);
   $("select#female_medicine_suggestion_list").parent().find('button').addClass('disabled');
   $('option', $('#female_medicine_suggestion_list')).each(function(element) {
   	$(this).removeAttr('selected').prop('selected', false);
   });
   $("#female_medicine_suggestion_list").multiselect('refresh');	
   $("select#female_medicine_suggestion_list").prop('required',false);
   
   $("#male_medicine_table").hide();
   $("#female_medicine_table").hide();
   $('div[id^="medicine_male_"]').remove();
   $('div[id^="medicine_female_"]').remove();
   $('input[name^="male_medicine_name_"]').remove();
   $('input[name^="female_medicine_name_"]').remove();
   
   if(this.checked) {
   	$("select#male_medicine_suggestion_list").prop('required',false);
   	$("select#male_medicine_suggestion_list").prop('disabled',false);
   	$("select#male_medicine_suggestion_list").parent().find('button').prop('disabled',false);
   	$("select#male_medicine_suggestion_list").parent().find('button').removeClass('disabled');
   	
   	$("select#female_medicine_suggestion_list").prop('required',true);
   	$("select#female_medicine_suggestion_list").prop('disabled',false);
   	$("select#female_medicine_suggestion_list").parent().find('button').prop('disabled',false);
   	$("select#female_medicine_suggestion_list").parent().find('button').removeClass('disabled');
   }
   });
   
   $("#medicine_suggestion_ipd").change(function() {
   $("select#male_medicine_suggestion_list_ipd").prop('disabled',true);
   $("select#male_medicine_suggestion_list_ipd").parent().find('button').prop('disabled',true);
   $("select#male_medicine_suggestion_list_ipd").parent().find('button').addClass('disabled');
   $('option', $('#male_medicine_suggestion_list_ipd')).each(function(element) {
   	$(this).removeAttr('selected').prop('selected', false);
   });
   $("#male_medicine_suggestion_list_ipd").multiselect('refresh');	
   $("select#male_medicine_suggestion_list_ipd").prop('required',false);
   
   $("select#female_medicine_suggestion_list_ipd").prop('disabled',true);
   $("select#female_medicine_suggestion_list_ipd").parent().find('button').prop('disabled',true);
   $("select#female_medicine_suggestion_list_ipd").parent().find('button').addClass('disabled');
   $('option', $('#female_medicine_suggestion_list_ipd')).each(function(element) {
      if ($(this).val() !== 'OPD_44' && $(this).val() !== 'OPD_46') {
   	     $(this).removeAttr('selected').prop('selected', false);
      }
   });
   $("#female_medicine_suggestion_list_ipd").multiselect('refresh');	
   $("select#female_medicine_suggestion_list_ipd").prop('required',false);
   
   $("#male_medicine_table_ipd").hide();
   $("#female_medicine_table_ipd").hide();
   $('div[id^="medicine_male_ipd_"]').remove();
   $('div[id^="medicine_female_ipd_"]').remove();
   $('input[name^="male_medicine_name_ipd_"]').remove();
   $('input[name^="female_medicine_name_ipd_"]').remove();
   
   if(this.checked) {
   	$("select#male_medicine_suggestion_list_ipd").prop('required',false);
   	$("select#male_medicine_suggestion_list_ipd").prop('disabled',false);
   	$("select#male_medicine_suggestion_list_ipd").parent().find('button').prop('disabled',false);
   	$("select#male_medicine_suggestion_list_ipd").parent().find('button').removeClass('disabled');
   	
   	$("select#female_medicine_suggestion_list_ipd").prop('required',true);
   	$("select#female_medicine_suggestion_list_ipd").prop('disabled',false);
   	$("select#female_medicine_suggestion_list_ipd").parent().find('button').prop('disabled',false);
   	$("select#female_medicine_suggestion_list_ipd").parent().find('button').removeClass('disabled');
   }
   });
   
   $("#investigation_suggestion").change(function() {
   $("select#male_investigation_suggestion_list").prop('disabled',true);
   $("select#male_investigation_suggestion_list").parent().find('button').prop('disabled',true);
   $("select#male_investigation_suggestion_list").parent().find('button').addClass('disabled');
   $('option', $('#male_investigation_suggestion_list')).each(function(element) {
   	$(this).removeAttr('selected').prop('selected', false);
   });
   $("#male_investigation_suggestion_list").multiselect('refresh');
   $("select#male_investigation_suggestion_list").prop('required',false);
   
   $("select#female_investigation_suggestion_list").prop('disabled',true);
   $("select#female_investigation_suggestion_list").parent().find('button').prop('disabled',true);
   $("select#female_investigation_suggestion_list").parent().find('button').addClass('disabled');
   $('option', $('#female_investigation_suggestion_list')).each(function(element) {
   	$(this).removeAttr('selected').prop('selected', false);
   });
   $("#female_investigation_suggestion_list").multiselect('refresh');
   $("select#female_investigation_suggestion_list").prop('required',false);
   
   if(this.checked) {
   	$("select#male_investigation_suggestion_list").prop('required',false);
   	$("select#male_investigation_suggestion_list").prop('disabled',false);
   	$("select#male_investigation_suggestion_list").parent().find('button').prop('disabled',false);
   	$("select#male_investigation_suggestion_list").parent().find('button').removeClass('disabled');
   	
   	$("select#female_investigation_suggestion_list").prop('required',true);
   	$("select#female_investigation_suggestion_list").prop('disabled',false);
   	$("select#female_investigation_suggestion_list").parent().find('button').prop('disabled',false);
   	$("select#female_investigation_suggestion_list").parent().find('button').removeClass('disabled');
   }
   
   $("select#male_minvestigation_suggestion_list").prop('disabled',true);
   $("select#male_minvestigation_suggestion_list").parent().find('button').prop('disabled',true);
   $("select#male_minvestigation_suggestion_list").parent().find('button').addClass('disabled');
   $('option', $('#male_minvestigation_suggestion_list')).each(function(element) {
   	$(this).removeAttr('selected').prop('selected', false);
   });
   $("#male_minvestigation_suggestion_list").multiselect('refresh');
   $("select#male_minvestigation_suggestion_list").prop('required',false);
   
   $("select#female_minvestigation_suggestion_list").prop('disabled',true);
   $("select#female_minvestigation_suggestion_list").parent().find('button').prop('disabled',true);
   $("select#female_minvestigation_suggestion_list").parent().find('button').addClass('disabled');
   $('option', $('#female_minvestigation_suggestion_list')).each(function(element) {
   	$(this).removeAttr('selected').prop('selected', false);
   });
   $("#female_minvestigation_suggestion_list").multiselect('refresh');
   $("select#female_minvestigation_suggestion_list").prop('required',false);
   
   if(this.checked) {
   	$("select#male_minvestigation_suggestion_list").prop('required',false);
   	$("select#male_minvestigation_suggestion_list").prop('disabled',false);
   	$("select#male_minvestigation_suggestion_list").parent().find('button').prop('disabled',false);
   	$("select#male_minvestigation_suggestion_list").parent().find('button').removeClass('disabled');
   	
   	$("select#female_minvestigation_suggestion_list").prop('required',true);
   	$("select#female_minvestigation_suggestion_list").prop('disabled',false);
   	$("select#female_minvestigation_suggestion_list").parent().find('button').prop('disabled',false);
   	$("select#female_minvestigation_suggestion_list").parent().find('button').removeClass('disabled');
   }
   });
   
   $("#procedure_suggestion").change(function() {
   $("select#sub_procedure_suggestion_list").prop('disabled',true);
   $("select#sub_procedure_suggestion_list").parent().find('button').prop('disabled',true);
   $("select#sub_procedure_suggestion_list").parent().find('button').addClass('disabled');
   $('option', $('#sub_procedure_suggestion_list')).each(function(element) {
   	$(this).removeAttr('selected').prop('selected', false);
   });
   $("#sub_procedure_suggestion_list").multiselect('refresh');
   
   $("select#procedure_suggestion_list").prop('required',false);	
   $("select#sub_procedure_suggestion_list").prop('required',false);
   $("select#procedure_suggestion_list").prop('disabled',true);
   if(this.checked) {
   	$("select#sub_procedure_suggestion_list").prop('disabled',false);
   	$("select#sub_procedure_suggestion_list").parent().find('button').prop('disabled',false);
   	$("select#sub_procedure_suggestion_list").parent().find('button').removeClass('disabled');
   }
   });
   
   $("#package_suggestion").change(function() {
   $("select#package_suggestion_list").prop('disabled',true);
   $("select#package_suggestion_list").parent().find('button').prop('disabled',true);
   $("select#package_suggestion_list").parent().find('button').addClass('disabled');
   $('option', $('#package_suggestion_list')).each(function(element) {
   	$(this).removeAttr('selected').prop('selected', false);
   });
   $("#package_suggestion_list").multiselect('refresh');
   $("select#package_suggestion_list").prop('required',false);
   
   if(this.checked) {
   	$("select#package_suggestion_list").prop('required',true);
   	$("select#package_suggestion_list").prop('disabled',false);
   	$("select#package_suggestion_list").parent().find('button').prop('disabled',false);
   	$("select#package_suggestion_list").parent().find('button').removeClass('disabled');
   }
   });
   
   var multiselectSelections = {};
   
   function forceCaptureMultiselectValues() {
       multiselectSelections = {};
       console.log('Processing multiselect elements...');
       
       var selectors = [
           'select[id="female_medicine_suggestion_list"]',
           'select[id="male_medicine_suggestion_list"]',
           'select[id="female_medicine_suggestion_list_ipd"]',
           'select[id="male_medicine_suggestion_list_ipd"]',
           'select[id="female_minvestigation_suggestion_list"]',
           'select[id="male_minvestigation_suggestion_list"]',
           'select[id="sub_procedure_suggestion_list"]',
           'select[id="package_suggestion_list"]',
           'select[name="advisory_templates[]"]'
       ];
       
       selectors.forEach(function(selector) {
           var $select = $(selector);
           if ($select.length) {
               var name = $select.attr('name');
               console.log('Processing element:', selector, 'with name:', name);
               
               if (name) {
                   $select.prop('disabled', false);
                   var selectedValues = [];
                   $select.find('option:selected').each(function() {
                       var value = $(this).val();
                       if (value && value !== '0') {
                           selectedValues.push(value);
                       }
                   });
                   
                   if (selectedValues.length === 0) {
                       var valResult = $select.val();
                       if (valResult && Array.isArray(valResult)) {
                           selectedValues = valResult.filter(function(v) { return v && v !== '0'; });
                       }
                   }
                   
                   if (selectedValues.length > 0) {
                       multiselectSelections[name] = selectedValues;
                       console.log('Captured values for', name, ':', selectedValues);
                   }
               }
           }
       });
       
       return multiselectSelections;
   }
   
   $(function() {
       var multiselectConfig = {
           独立SelectAllOption: true,
           includeSelectAllOption: true,
           enableFiltering: true,
           enableCaseInsensitiveFiltering: true,
           filterPlaceholder: 'Search...',
           maxHeight: 200,
           buttonWidth: '100%',
           dropdownParent: $('body'),
           position: {
               my: 'left top',
               at: 'left bottom',
               collision: 'flip'
           }
       };
       
       console.log('Found multiselect elements:', $('.multidselect_dropdown, .multidselect_dropdown_1, .multidselect_dropdown_2').length);
       
       try {
           $('.multidselect_dropdown, .multidselect_dropdown_1, .multidselect_dropdown_2').multiselect(multiselectConfig);
           console.log('Multiselect initialized successfully');
       } catch(e) {
           console.error('Multiselect initialization failed:', e);
       }
       
       $(window).on('scroll resize', function() {
           $('.multiselect-container').each(function() {
               $(this).css('position', 'absolute');
           });
       });
   });
   
   function captureMultiselectValues() {
       var multiselectValues = {};
       
       $('.multidselect_dropdown, .multidselect_dropdown_1, .multidselect_dropdown_2').each(function() {
           var $select = $(this);
           var name = $select.attr('name');
           var values = [];
           
           if ($select.val()) {
               values = $select.val();
           }
           
           if (values.length === 0) {
               var $button = $select.parent().find('.multiselect');
               var buttonText = $button.text();
               if (buttonText && buttonText !== 'Select options' && buttonText !== 'None selected') {
                   console.log('Button text for ' + name + ':', buttonText);
               }
           }
           
           if (values.length === 0) {
               $select.find('option:selected').each(function() {
                   values.push($(this).val());
               });
           }
           
           if (name && values.length > 0) {
               multiselectValues[name] = values;
               $select.siblings('input[name="' + name + '"]').remove();
               values.forEach(function(value) {
                   $select.after('<input type="hidden" name="' + name + '" value="' + value + '">');
               });
           }
       });
       
       return multiselectValues;
   }
   
   function generateMedicineTable(gender, suffix = '') {
       var selectId = '#' + gender + '_medicine_suggestion_list' + suffix;
       var tableId = '#' + gender + '_medicine_table' + suffix;
       var tbodyId = '#' + gender + '_medicine_suggestion_table' + suffix;
       
       $(tableId).hide();
       var brands = $(selectId + ' option:selected');
       var countr = 1;
       $(tbodyId).empty();
       
       $(brands).each(function(index, brand) {
           var medicineName = $(this).attr('medicine');
           var medicineValue = $(this).val();
           
           console.log('Generating row for medicine:', medicineName, 'with value:', medicineValue);
           
           var row = '<tr style="border:1px solid #000;">' +
               '<td style="border:1px solid #000;">' + medicineName + 
               '<input type="hidden" required readonly value="' + medicineValue + '" style="margin:0;padding:0;" name="' + gender + '_medicine_name' + suffix + '_' + countr + '" id="' + gender + '_medicine_name' + suffix + '_' + countr + '">' +
               '</td>' +
               '<td style="border:1px solid #000;"><input type="number" step="0.1" style="margin:0;padding:0;" name="' + gender + '_medicine_dosage' + suffix + '_' + countr + '" required id="' + gender + '_medicine_dosage' + suffix + '_' + countr + '"></td>' +
               '<td style="border:1px solid #000;"><input type="text" style="margin:0;padding:0;" name="' + gender + '_medicine_remarks' + suffix + '_' + countr + '" required id="' + gender + '_medicine_remarks' + suffix + '_' + countr + '"></td>' +
               '<td style="border:1px solid #000;"><input type="date" placeholder="DD-MM-YYYY" style="margin:0;padding:0;" name="' + gender + '_medicine_when_start' + suffix + '_' + countr + '" id="' + gender + '_medicine_when_start' + suffix + '_' + countr + '" required></td>' +
               '<td style="border:1px solid #000;"><input type="number" style="margin:0;padding:0;" name="' + gender + '_medicine_days' + suffix + '_' + countr + '" required id="' + gender + '_medicine_days' + suffix + '_' + countr + '"></td>' +
               '<td style="border:1px solid #000;" class="role"><select style="margin:0;padding:0;" name="' + gender + '_medicine_route' + suffix + '_' + countr + '" id="' + gender + '_medicine_route' + suffix + '_' + countr + '" required>' +
               '<option value="PO">PO</option>' +
               '<option value="IM">IM</option>' +
               '<option value="SC" selected>SC</option>' + // Mapped SC to default as per clinical context
               '<option value="VAGINA-LY">VAGINA-LY</option>' +
               '<option value="IV">IV</option>' +
               '<option value="LOCAL">LOCAL</option>' +
               '<option value="NASALY">NASALY</option>' +
               '</select></td>' +
               '<td style="border:1px solid #000;" class="role"><select style="margin:0;padding:0;" name="' + gender + '_medicine_frequency' + suffix + '_' + countr + '" id="' + gender + '_medicine_frequency' + suffix + '_' + countr + '" required>' +
               '<option value="OD">OD</option>' +
               '<option value="BD">BD</option>' +
               '<option value="TDS">TDS</option>' +
               '<option value="QID">QID</option>' +
               '<option value="SOS">SOS</option>' +
               '<option value="HS">HS</option>' +
               '</select></td>' +
               '<td style="border:1px solid #000;" class="role"><select style="margin:0;padding:0;" name="' + gender + '_medicine_timing' + suffix + '_' + countr + '" id="' + gender + '_medicine_timing' + suffix + '_' + countr + '" required>' +
               '<option value="EMPTY STOMACH">EMPTY STOMACH</option>' +
               '<option value="BEFORE MEAL">BEFORE MEAL</option>' +
               '<option value="AFTER MEAL" selected>AFTER MEAL</option>' +
               '</select></td>' +
               '<td style="border:1px solid #000;" class="role"><select style="margin:0;padding:0;" name="' + gender + '_medicine_take' + suffix + '_' + countr + '" id="' + gender + '_medicine_take' + suffix + '_' + countr + '" required>' +
               '<option value="Daily">Daily</option>' +
               '<option value="Biweekly">Biweekly</option>' +
               '<option value="Weekly">Weekly</option>' +
               '<option value="Blank">Blank</option>' +
               '<option value="Alternate Day">Alternate Day</option>' +
               '</select></td>' +
               '</tr>';
           
           $(tbodyId).append(row);
           countr++;
       });
       
       $(tableId).show();
   }
   
   function generateMedicineFieldsFromArray(gender, medicineIds, suffix) {
       var tableId = '#' + gender + '_medicine_table' + suffix;
       var tbodyId = '#' + gender + '_medicine_suggestion_table' + suffix;
       
       $(tbodyId).empty();
       var countr = 1;
       
       medicineIds.forEach(function(medicineId) {
           var medicineName = '';
           var selectId = '#' + gender + '_medicine_suggestion_list' + suffix;
           $(selectId + ' option[value="' + medicineId + '"]').each(function() {
               medicineName = $(this).attr('medicine') || $(this).text();
           });
           
           var row = '<tr style="border:1px solid #000;">' +
               '<td style="border:1px solid #000;">' + medicineName + 
               '<input type="hidden" required readonly value="' + medicineId + '" style="margin:0;padding:0;" name="' + gender + '_medicine_name' + suffix + '_' + countr + '" id="' + gender + '_medicine_name' + suffix + '_' + countr + '">' +
               '</td>' +
               '<td style="border:1px solid #000;"><input type="number" step="0.1" style="margin:0;padding:0;" name="' + gender + '_medicine_dosage' + suffix + '_' + countr + '" required id="' + gender + '_medicine_dosage' + suffix + '_' + countr + '"></td>' +
               '<td style="border:1px solid #000;"><input type="text" style="margin:0;padding:0;" name="' + gender + '_medicine_remarks' + suffix + '_' + countr + '" required id="' + gender + '_medicine_remarks' + suffix + '_' + countr + '"></td>' +
               '<td style="border:1px solid #000;"><input type="date" placeholder="DD-MM-YYYY" style="margin:0;padding:0;" name="' + gender + '_medicine_when_start' + suffix + '_' + countr + '" id="' + gender + '_medicine_when_start' + suffix + '_' + countr + '" required></td>' +
               '<td style="border:1px solid #000;"><input type="number" style="margin:0;padding:0;" name="' + gender + '_medicine_days' + suffix + '_' + countr + '" required id="' + gender + '_medicine_days' + suffix + '_' + countr + '"></td>' +
               '<td style="border:1px solid #000;" class="role"><select style="margin:0;padding:0;" name="' + gender + '_medicine_route' + suffix + '_' + countr + '" id="' + gender + '_medicine_route' + suffix + '_' + countr + '" required>' +
               '<option value="PO">PO</option>' +
               '<option value="IM">IM</option>' +
               '<option value="SC" selected>SC</option>' +
               '<option value="VAGINA-LY">VAGINA-LY</option>' +
               '<option value="IV">IV</option>' +
               '<option value="LOCAL">LOCAL</option>' +
               '<option value="NASALY">NASALY</option>' +
               '</select></td>' +
               '<td style="border:1px solid #000;" class="role"><select style="margin:0;padding:0;" name="' + gender + '_medicine_frequency' + suffix + '_' + countr + '" id="' + gender + '_medicine_frequency' + suffix + '_' + countr + '" required>' +
               '<option value="OD">OD</option>' +
               '<option value="BD">BD</option>' +
               '<option value="TDS">TDS</option>' +
               '<option value="QID">QID</option>' +
               '<option value="SOS">SOS</option>' +
               '<option value="HS">HS</option>' +
               '</select></td>' +
               '<td style="border:1px solid #000;" class="role"><select style="margin:0;padding:0;" name="' + gender + '_medicine_timing' + suffix + '_' + countr + '" id="' + gender + '_medicine_timing' + suffix + '_' + countr + '" required>' +
               '<option value="EMPTY STOMACH">EMPTY STOMACH</option>' +
               '<option value="BEFORE MEAL">BEFORE MEAL</option>' +
               '<option value="AFTER MEAL" selected>AFTER MEAL</option>' +
               '</select></td>' +
               '<td style="border:1px solid #000;" class="role"><select style="margin:0;padding:0;" name="' + gender + '_medicine_take' + suffix + '_' + countr + '" id="' + gender + '_medicine_take' + suffix + '_' + countr + '" required>' +
               '<option value="Daily">Daily</option>' +
               '<option value="Biweekly">Biweekly</option>' +
               '<option value="Weekly">Weekly</option>' +
               '<option value="Blank">Blank</option>' +
               '<option value="Alternate Day">Alternate Day</option>' +
               '</select></td>' +
               '</tr>';
           
           $(tbodyId).append(row);
           countr++;
       });
       
       $(tableId).show();
   }
   
   $('#female_medicine_suggestion_list').change(function() {
       var selectedMedicines = $(this).val();
       if (selectedMedicines && selectedMedicines.length > 0 && selectedMedicines[0] !== '0') {
           generateMedicineFieldsFromArray('female', selectedMedicines, '');
       } else {
           $('#female_medicine_table').hide();
           $('#female_medicine_suggestion_table').empty();
       }
   });
   
   $('#male_medicine_suggestion_list').change(function() {
       var selectedMedicines = $(this).val();
       if (selectedMedicines && selectedMedicines.length > 0 && selectedMedicines[0] !== '0') {
           generateMedicineFieldsFromArray('male', selectedMedicines, '');
       } else {
           $('#male_medicine_table').hide();
           $('#male_medicine_suggestion_table').empty();
       }
   });
   
   $('#female_medicine_suggestion_list_ipd').change(function() {
       var selectedMedicines = $(this).val();
       if (selectedMedicines && selectedMedicines.length > 0 && selectedMedicines[0] !== '0') {
           generateMedicineFieldsFromArray('female', selectedMedicines, '_ipd');
       } else {
           $('#female_medicine_table_ipd').hide();
           $('#female_medicine_suggestion_table_ipd').empty();
       }
   });
   
   $('#male_medicine_suggestion_list_ipd').change(function() {
       var selectedMedicines = $(this).val();
       if (selectedMedicines && selectedMedicines.length > 0 && selectedMedicines[0] !== '0') {
           generateMedicineFieldsFromArray('male', selectedMedicines, '_ipd');
       } else {
           $('#male_medicine_table_ipd').hide();
           $('#male_medicine_suggestion_table_ipd').empty();
       }
   });
   
   $('form').on('submit', function(e) {
       e.preventDefault();
       
       $('#submit-followup-btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
       var formData = collectFormData();
       submitFollowUpData(formData);
   });
   
   function collectFormData() {
       var data = {
           action: 'add_consultation_done',
           appointment_id: $('input[name="appointment_id"]').val(),
           patient_id: $('input[name="patient_id"]').val(),
           wife_phone: $('input[name="wife_phone"]').val(),
           doctor_id: $('input[name="doctor_id"]').val(),
           center_number: $('input[name="center_number"]').val(),
           doc_consult_id: $('input[name="doc_consult_id"]').val() || '',
           female_findings: $('input[name="female_findings"]').val(),
           male_findings: $('input[name="male_findings"]').val(),
           withdrawal_date: $('input[name="withdrawal_date"]').val(),
           follow_up: $('input[name="follow_up"]').val(),
           follow_up_date: $('input[name="follow_up_date"]').val(),
           follow_slot: $('select[name="appoitmented_slot"]').val(),
           follow_up_purpose: $('input[name="follow_up_purpose"]:checked').val(),
           appoitment_for: $('select[name="appoitment_for"]').val(),
           appoitmented_doctor: $('select[name="appoitmented_doctor"]').val(),
           sections: {}
       };
       
       if ($('#investigation_suggestion').is(':checked')) {
           data.sections.investigations = {
               enabled: true,
               female_minvestigation_suggestion_list: getMultiselectValues('female_minvestigation_suggestion_list'),
               male_minvestigation_suggestion_list: getMultiselectValues('male_minvestigation_suggestion_list')
           };
       }
       
       if ($('#medicine_suggestion').is(':checked')) {
           data.sections.medicines_opd = {
               enabled: true,
               female_medicines: collectMedicineData('female', ''),
               male_medicines: collectMedicineData('male', '')
           };
       }
       
       if ($('#medicine_suggestion_ipd').is(':checked')) {
           data.sections.medicines_ipd = {
               enabled: true,
               female_medicines: collectMedicineData('female', '_ipd'),
               male_medicines: collectMedicineData('male', '_ipd')
           };
       }
       
       if ($('#procedure_suggestion').is(':checked')) {
           data.sections.procedures = {
               enabled: true,
               sub_procedure_suggestion_list: getMultiselectValues('sub_procedure_suggestion_list')
           };
       }
       
       if ($('#package_suggestion').is(':checked')) {
           data.sections.packages = {
               enabled: true,
               package_suggestion_list: getMultiselectValues('package_suggestion_list')
           };
       }
       
       data.sections.advisory_templates = getMultiselectValues('advisory_templates');
       return data;
   }
   
   function getMultiselectValues(selectId) {
       var $select = $('#' + selectId);
       var values = $select.val() || [];
       return values.filter(function(v) { return v && v !== '0'; });
   }
   
   function collectMedicineData(gender, suffix) {
       var medicines = [];
       var medicineIds = getMultiselectValues(gender + '_medicine_suggestion_list' + suffix);
       
       medicineIds.forEach(function(medicineId, index) {
           var medicineData = {
               medicine_id: medicineId,
               medicine_name: $('#' + gender + '_medicine_suggestion_list' + suffix + ' option[value="' + medicineId + '"]').attr('medicine'),
               dosage: $('input[name="' + gender + '_medicine_dosage' + suffix + '_' + (index + 1) + '"]').val(),
               remarks: $('input[name="' + gender + '_medicine_remarks' + suffix + '_' + (index + 1) + '"]').val(),
               when_start: $('input[name="' + gender + '_medicine_when_start' + suffix + '_' + (index + 1) + '"]').val(),
               days: $('input[name="' + gender + '_medicine_days' + suffix + '_' + (index + 1) + '"]').val(),
               route: $('select[name="' + gender + '_medicine_route' + suffix + '_' + (index + 1) + '"]').val(),
               frequency: $('select[name="' + gender + '_medicine_frequency' + suffix + '_' + (index + 1) + '"]').val(),
               timing: $('select[name="' + gender + '_medicine_timing' + suffix + '_' + (index + 1) + '"]').val(),
               take: $('select[name="' + gender + '_medicine_take' + suffix + '_' + (index + 1) + '"]').val()
           };
           medicines.push(medicineData);
       });
       
       return medicines;
   }
   
   function submitFollowUpData(formData) {
       $('#loader_div').show();
       
       $.ajax({
           url: '<?php echo base_url('doctors/follow_up_clean/'.$appointments['ID']); ?>',
           type: 'POST',
           data: formData,
           dataType: 'json',
           success: function(response) {
               $('#loader_div').hide();
               if (response.status === 'success') {
                   showMessage(response.message, 'success');
                   setTimeout(function() {
                       window.location.href = response.redirect_url || '<?php echo base_url("doctor_appointments"); ?>';
                   }, 2000);
               } else {
                   showMessage(response.message, 'error');
                   $('#submit-followup-btn').prop('disabled', false).html('<i class="fa fa-save"></i> Submit Follow-up Consultation');
               }
           },
           error: function(xhr, status, error) {
               $('#loader_div').hide();
               console.error('AJAX Error:', error);
               var errorMessage = 'Data submitted';
               showMessage(errorMessage, 'error');
               
               setTimeout(function() {
                   window.location.href = '<?php echo base_url("doctor_appointments"); ?>';
               }, 2000);
               
               $('#submit-followup-btn').prop('disabled', false).html('<i class="fa fa-save"></i> Submit Follow-up Consultation');
           }
       });
   }
   
   function printSubmittedData() {
       var appointmentId = '<?php echo $appointments["ID"]; ?>';
       var printUrl = '<?php echo base_url("print-submitted-consultation/"); ?>' + appointmentId;
       var printWindow = window.open(printUrl, '_blank', 'width=1000,height=700');
       if (printWindow) {
           printWindow.focus();
       } else {
           alert('Please allow popups for this site to print the consultation report.');
       }
   }
   
   function printConsultationForm() {
       var formData = collectFormData();
       var printContent = generatePrintContent(formData);
       var printWindow = window.open('', '_blank', 'width=800,height=600');
       printWindow.document.write(printContent);
       printWindow.document.close();
       printWindow.onload = function() {
           printWindow.print();
           printWindow.close();
       };
   }
   
   function generatePrintContent(formData) {
       var patientName = '<?php echo isset($patient_data["wife_name"]) ? $patient_data["wife_name"] : "N/A"; ?>';
       var patientPhone = '<?php echo isset($patient_data["wife_phone"]) ? $patient_data["wife_phone"] : "N/A"; ?>';
       var doctorName = '<?php echo isset($appointments["doctor_name"]) ? $appointments["doctor_name"] : "N/A"; ?>';
       var appointmentDate = '<?php echo isset($appointments["appoitmented_date"]) ? date("d-m-Y", strtotime($appointments["appoitmented_date"])) : "N/A"; ?>';
       var centerName = '<?php echo isset($appointments["center_name"]) ? $appointments["center_name"] : "N/A"; ?>';
       
       var html = `
       <!DOCTYPE html>
       <html>
       <head>
           <title>Follow-up Consultation Report</title>
           <style>
               body { font-family: Arial, sans-serif; margin: 20px; }
               .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
               .section { margin-bottom: 25px; }
               .section-title { background-color: #f5f5f5; padding: 10px; font-weight: bold; border-left: 4px solid #007bff; }
               .field { margin: 8px 0; }
               .field-label { font-weight: bold; display: inline-block; width: 200px; }
               .field-value { display: inline-block; }
               .medicine-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
               .medicine-table th, .medicine-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
               .medicine-table th { background-color: #f2f2f2; }
               .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #666; }
               @media print { body { margin: 0; } }
           </style>
       </head>
       <body>
           <div class="header">
               <h1>Follow-up Consultation Report</h1>
               <p><strong>Hospital Management System</strong></p>
           </div>
           
           <div class="section">
               <div class="section-title">Patient Information</div>
               <div class="field"><span class="field-label">Patient Name:</span> <span class="field-value">${patientName}</span></div>
               <div class="field"><span class="field-label">Phone Number:</span> <span class="field-value">${patientPhone}</span></div>
               <div class="field"><span class="field-label">Doctor:</span> <span class="field-value">${doctorName}</span></div>
               <div class="field"><span class="field-label">Appointment Date:</span> <span class="field-value">${appointmentDate}</span></div>
               <div class="field"><span class="field-label">Center:</span> <span class="field-value">${centerName}</span></div>
           </div>
           
           <div class="section">
               <div class="section-title">Clinical Findings</div>
               <div class="field"><span class="field-label">Female Findings:</span> <span class="field-value">${formData.female_findings || 'N/A'}</span></div>
               <div class="field"><span class="field-label">Male Findings:</span> <span class="field-value">${formData.male_findings || 'N/A'}</span></div>
           </div>
           
           ${generateInvestigationSection(formData)}
           ${generateMedicineSection(formData)}
           ${generateProcedureSection(formData)}
           ${generatePackageSection(formData)}
           ${generateFollowUpSection(formData)}
           
           <div class="footer">
               <p>Generated on: ${new Date().toLocaleString()}</p>
               <p>This is a computer-generated report.</p>
           </div>
       </body>
       </html>`;
       
       return html;
   }
   
   function generateInvestigationSection(formData) {
       if (!formData.sections || !formData.sections.investigations || !formData.sections.investigations.enabled) {
           return '';
       }
       var html = '<div class="section"><div class="section-title">Investigations Recommended</div>';
       if (formData.sections.investigations.female_minvestigation_suggestion_list && formData.sections.investigations.female_minvestigation_suggestion_list.length > 0) {
           html += '<div class="field"><span class="field-label">Female Investigations:</span></div><ul>';
           formData.sections.investigations.female_minvestigation_suggestion_list.forEach(function(inv) { html += '<li>' + inv + '</li>'; });
           html += '</ul>';
       }
       if (formData.sections.investigations.male_minvestigation_suggestion_list && formData.sections.investigations.male_minvestigation_suggestion_list.length > 0) {
           html += '<div class="field"><span class="field-label">Male Investigations:</span></div><ul>';
           formData.sections.investigations.male_minvestigation_suggestion_list.forEach(function(inv) { html += '<li>' + inv + '</li>'; });
           html += '</ul>';
       }
       html += '</div>';
       return html;
   }
   
   function generateMedicineSection(formData) {
       var html = '';
       if (formData.sections && formData.sections.medicines_opd && formData.sections.medicines_opd.enabled) {
           html += '<div class="section"><div class="section-title">OPD Medicines</div>';
           if (formData.sections.medicines_opd.female_medicines && formData.sections.medicines_opd.female_medicines.length > 0) {
               html += '<div class="field"><span class="field-label">Female Medicines:</span></div>' + generateMedicineTable(formData.sections.medicines_opd.female_medicines);
           }
           if (formData.sections.medicines_opd.male_medicines && formData.sections.medicines_opd.male_medicines.length > 0) {
               html += '<div class="field"><span class="field-label">Male Medicines:</span></div>' + generateMedicineTable(formData.sections.medicines_opd.male_medicines);
           }
           html += '</div>';
       }
       if (formData.sections && formData.sections.medicines_ipd && formData.sections.medicines_ipd.enabled) {
           html += '<div class="section"><div class="section-title">IPD Medicines</div>';
           if (formData.sections.medicines_ipd.female_medicines && formData.sections.medicines_ipd.female_medicines.length > 0) {
               html += '<div class="field"><span class="field-label">Female Medicines:</span></div>' + generateMedicineTable(formData.sections.medicines_ipd.female_medicines);
           }
           if (formData.sections.medicines_ipd.male_medicines && formData.sections.medicines_ipd.male_medicines.length > 0) {
               html += '<div class="field"><span class="field-label">Male Medicines:</span></div>' + generateMedicineTable(formData.sections.medicines_ipd.male_medicines);
           }
           html += '</div>';
       }
       return html;
   }
   
   function generateMedicineTable(medicines) {
       if (!medicines || medicines.length === 0) return '';
       var html = '<table class="medicine-table"><thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Route</th><th>Remarks</th></tr></thead><tbody>';
       medicines.forEach(function(medicine) {
           html += '<tr>' +
               '<td>' + (medicine.medicine_name || 'N/A') + '</td>' +
               '<td>' + (medicine.dosage || 'N/A') + '</td>' +
               '<td>' + (medicine.frequency || 'N/A') + '</td>' +
               '<td>' + (medicine.days || 'N/A') + '</td>' +
               '<td>' + (medicine.route || 'N/A') + '</td>' +
               '<td>' + (medicine.remarks || 'N/A') + '</td>' +
               '</tr>';
       });
       html += '</tbody></table>';
       return html;
   }
   
   function generateProcedureSection(formData) {
       if (!formData.sections || !formData.sections.procedures || !formData.sections.procedures.enabled) return '';
       var html = '<div class="section"><div class="section-title">Procedures Recommended</div>';
       if (formData.sections.procedures.sub_procedure_suggestion_list && formData.sections.procedures.sub_procedure_suggestion_list.length > 0) {
           html += '<ul>';
           formData.sections.procedures.sub_procedure_suggestion_list.forEach(function(proc) { html += '<li>' + proc + '</li>'; });
           html += '</ul>';
       }
       html += '</div>';
       return html;
   }
   
   function generatePackageSection(formData) {
       if (!formData.sections || !formData.sections.packages || !formData.sections.packages.enabled) return '';
       var html = '<div class="section"><div class="section-title">Packages Recommended</div>';
       if (formData.sections.packages.package_suggestion_list && formData.sections.packages.package_suggestion_list.length > 0) {
           html += '<ul>';
           formData.sections.packages.package_suggestion_list.forEach(function(pkg) { html += '<li>' + pkg + '</li>'; });
           html += '</ul>';
       }
       html += '</div>';
       return html;
   }
   
   function generateFollowUpSection(formData) {
       if (!formData.follow_up || formData.follow_up != 1) return '';
       return '<div class="section"><div class="section-title">Follow-up Appointment</div>' +
           '<div class="field"><span class="field-label">Follow-up Date:</span> <span class="field-value">' + (formData.follow_up_date || 'N/A') + '</span></div>' +
           '<div class="field"><span class="field-label">Follow-up Time:</span> <span class="field-value">' + (formData.follow_slot || 'N/A') + '</span></div>' +
           '<div class="field"><span class="field-label">Purpose:</span> <span class="field-value">' + (formData.follow_up_purpose || 'N/A') + '</span></div></div>';
   }
   
   function showMessage(message, type) {
       var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
       var messageHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade in" role="alert">' +
           '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
           '<span aria-hidden="true">&times;</span></button>' + message + '</div>';
       $('.alert').remove();
       $('.follow-up-container').prepend(messageHtml);
       setTimeout(function() { $('.alert').fadeOut(); }, 5000);
   }
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#procedure_suggestion').on('change', function() {
        var isChecked = $(this).is(':checked');
        var $dropdowns = $('#list_india, #list_non_india');

        if (isChecked) {
            $dropdowns.prop('disabled', false);
            if (typeof $dropdowns.multiselect === 'function') {
                $dropdowns.multiselect('enable');
                $dropdowns.multiselect('refresh');
            }
        } else {
            $dropdowns.val(null).prop('disabled', true);
            if (typeof $dropdowns.multiselect === 'function') {
                $dropdowns.multiselect('deselectAll', false);
                $dropdowns.multiselect('updateButtonText');
                $dropdowns.multiselect('disable');
            }
        }
    });

    $('form').on('submit', function() {
        if ($('#procedure_suggestion').is(':checked')) {
            $('#list_india, #list_non_india').prop('disabled', false);
        }
    });

    // ==============================================================================
    // CRITICAL FIXED AUTO-TRIGGER: Page load hone par dynamic rows generate karne ke liye
    // ==============================================================================
    setTimeout(function() {
        var ipdSelect = $('#female_medicine_suggestion_list_ipd');
        // Agar select block null nahi hai aur isme pre-selected values ('OPD_44', 'OPD_46') hain
        if (ipdSelect.val() !== null && ipdSelect.val().length > 0) {
            // Forcefully change trigger execute karein taaki data rows populate ho jayein
            ipdSelect.trigger('change');
            
            // Multiselect UI update karne ke liye refresh hit karein
            if (typeof ipdSelect.multiselect === 'function') {
                ipdSelect.multiselect('refresh');
            }
        }
    }, 400); // 400ms delay taaki dropdown plugin core successfully init ho sake
});
</script>