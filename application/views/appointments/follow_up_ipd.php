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
   /* Ensure dropdowns are not clipped by parent containers */
   .section-card, .table-responsive, .table-enhanced {
   overflow: visible !important;
   }
   .table td {
   overflow: visible !important;
   position: relative;
   }
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
   .open > .dropdown-menu {
    width: 450px;
   }
   
   /* 🎯 Dropdown Disabled Colors Hardening overrides */
   #list_india option:disabled, 
   #list_non_india option:disabled {
       color: red !important;
       background-color: #fff0f0;
       -webkit-text-fill-color: red;
   }
   .multiselect-container > li.disabled > a > label {
       color: red !important;
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
                  <i class="fa fa-flask"></i> IIC Investigations Advised
                  <label class="checkbox-enhanced pull-right">
                  <input type="checkbox" id="investigation_suggestion" value="1" name="investigation_suggestion" />
                  Enable Investigations
                  </label>
               </div>
               <div class="section-content" style="margin-bottom: 80px;">
                  <table class="table table-enhanced">
                     <thead>
                        <tr>
                           <th style="width: 35%;">Patient</th>
                           <th style="width: 35%;">Spouse</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>
                              <select class="form-control multidselect_dropdown_1" multiple id="female_minvestigation_suggestion_list" disabled name="female_minvestigation_suggestion_list[]">
                                 <?php if(!empty($master_investigations)) { 
                                    foreach($master_investigations as $key => $val) { ?>
                                 <option value="<?php echo $val['master_id']; ?>" ><?php echo $val['investigation_name']; ?></option>
                                 <?php  } } ?>
                                 <option value="0">NA</option>
                              </select>
                           </td>
                           <td>
                              <select class="form-control multidselect_dropdown_1" multiple id="male_minvestigation_suggestion_list" disabled name="male_minvestigation_suggestion_list[]">
                                 <?php if(!empty($master_investigations)) { foreach($master_investigations as $key => $val) { ?>
                                 <option value="<?php echo $val['master_id']; ?>"><?php echo $val['investigation_name']; ?></option>
                                 <?php  } } ?>
                                 <option value="0">NA</option>
                              </select>
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
                              <?php 
                              if(!empty($consultation_medicine_ipd)) { 
                                 $base_medicines = array('IPD_20', 'OPD_21', 'OPD_23', 'OPD_35', 'OPD_44', 'OPD_46');
                                 $medicine_lookup = array();
                                 foreach($consultation_medicine_ipd as $med) {
                                     $medicine_lookup[$med['item_number']] = $med['item_name'];
                                 }
                                 
                                 for ($i = 1; $i <= 2; $i++) {
                                     foreach($base_medicines as $item_number) { 
                                        if(isset($medicine_lookup[$item_number])) {
                                           $clean_value = $item_number . "_set_" . $i; 
                                 ?>
                                    <option value="<?php echo $clean_value; ?>" medicine="<?php echo $medicine_lookup[$item_number]; ?>">
                                       <?php echo $medicine_lookup[$item_number]; ?> (Set <?php echo $i; ?>)
                                    </option>
                                 <?php 
                                        } 
                                     }
                                 } 
                              } 
                              ?>
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
                  <i class="fa fa-cogs"></i> Management Advised
                  <label class="checkbox-enhanced pull-right">
                  <input type="checkbox" id="procedure_suggestion" value="1" name="procedure_suggestion" />
                  Enable Management
                  </label>
               </div>

               <div class="section-content">
                  <div class="row">
                     <div class="col-md-6">
                        <label>Only Indian Patient</label>
                        <?php
                        $patient_id = $patient_data['patient_id'];
                        $billed_data = $this->db->select('code')
                                                ->where('patient_id', $patient_id)
                                                ->get('hms_patient_procedure')
                                                ->result_array();

                        $billed_codes = [];
                        $exclude_from_disabling = ['IP218', 'IP219','IP64'];

                        if (!empty($billed_data)) {
                            foreach ($billed_data as $row) {
                                $trimmed_code = trim($row['code']);
                                if (!in_array($trimmed_code, $exclude_from_disabling)) {
                                    $billed_codes[] = $trimmed_code;
                                }
                            }
                        }
                        ?>
                        <select class="form-control multidselect_dropdown_2" multiple="multiple" id="list_india" name="sub_procedure_suggestion_list[]" disabled>
                            <?php 
                            if(!empty($procedures)) {
                                $grouped_procedures = [];
                                foreach($procedures as $val) {
                                    if(isset($val['code_type']) && $val['code_type'] == "india") {
                                        $p_id = !empty($val['package_id']) ? $val['package_id'] : 'General';
                                        $grouped_procedures[$p_id][] = $val;
                                    }
                                }

                                foreach($grouped_procedures as $package_id => $items) {
                                    $label = ($package_id == 'General' || $package_id == 'General Procedure') ? "General Procedures" : "Package Name: " . $package_id;
                                    echo '<optgroup label="' . $label . '">';
                                    
                                    foreach($items as $item) {
                                        $current_code = trim($item['code']);
                                        $disabled_attr = '';
                                        $already_billed_text = '';
                                        $style_attr = '';

                                        if (in_array($current_code, $billed_codes)) {
                                            $disabled_attr = 'disabled';
                                            $already_billed_text = ' [ALREADY ADVICES]';
                                            $style_attr = 'style="color: red !important; background-color: #ffe6e6;"';
                                        }
                                        ?>
                                        <option value="<?= $item['ID']; ?>" <?= $disabled_attr; ?> <?= $style_attr; ?>>
                                            <?= $item['procedure_name'] . " (" . $current_code . ")" . $already_billed_text; ?>
                                        </option>
                                        <?php
                                    }
                                    echo '</optgroup>';
                                }
                            } else {
                                echo '<option disabled>No procedures found</option>';
                            }
                            ?>
                        </select>
                     </div>
                     
                     <div class="col-md-6">
                        <label style="color:#ff0000;">International Patient</label>
                        <select class="form-control multidselect_dropdown_2" multiple="multiple" id="list_non_india" name="sub_procedure_suggestion_list[]" disabled>
                            <?php 
                            if(!empty($procedures)) {
                                $grouped_procedures = [];
                                foreach($procedures as $val) {
                                    if(isset($val['code_type']) && $val['code_type'] == "non-india") {
                                        $p_id = !empty($val['package_id']) ? $val['package_id'] : 'General';
                                        $grouped_procedures[$p_id][] = $val;
                                    }
                                }

                                foreach($grouped_procedures as $package_id => $items) {
                                    $label = ($package_id == 'General Procedure') ? "General Procedures" : "Package Name: " . $package_id;
                                    echo '<optgroup label="' . $label . '">';
                                    foreach($items as $item) {
                                        ?>
                                        <option value="<?= $item['ID']; ?>">
                                            <?= $item['procedure_name']." (".$item['code'].")"; ?>
                                        </option>
                                        <?php
                                    }
                                    echo '</optgroup>';
                                }
                            } 
                            ?>
                        </select>
                     </div>
                  </div>
               </div>
            </div>

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

<script data-cfasync="false" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script data-cfasync="false">
   var $appointmentFor = $('#appoitment_for');
   var $appointmentDoctor = $('#appoitmented_doctor');
   var $appointmentDate = $('#appoitmented_date');
   var $appointmentSlot = $('#appoitmented_slot');
   var $doctorDiv = $('div.appoitmented_doctor');
   var $dateDiv = $('div.appoitmented_date');
   var $slotDiv = $('div.appoitmented_slot');
   var $loaderDiv = $('#loader_div');
   
   $appointmentFor.on("change", function() {
      $doctorDiv.hide(); $dateDiv.hide(); $slotDiv.hide();
      $loaderDiv.show();
      var centre_id = $(this).val();
      if(centre_id != ''){
         $.ajax({
            url: '<?php echo base_url('billingcontroller/search_doctor')?>',
            data: {centre_id:centre_id},
            dataType: 'json',
            method:'post',
            success: function(data) {
               $appointmentDoctor.empty().append(data);
               $appointmentDoctor.prop({'required': true, 'disabled': false});
               $doctorDiv.show(); $loaderDiv.hide();         
            } 
         });
      } else {
         $doctorDiv.hide(); $loaderDiv.hide();
      }
   });
   
   $appointmentDoctor.on("change", function() {
      $loaderDiv.show();
      var doctor_id = $(this).val();
      $appointmentDate.val('');
      if(doctor_id != ''){
         $appointmentDate.prop({'required': true, 'disabled': false});
         $dateDiv.show();
      } else {
         $dateDiv.hide();
      }
      $loaderDiv.hide();
   });
   
   $(function() {
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
                     $slotDiv.show(); $loaderDiv.hide();
                  }
               });
            }
         });
   });
   
   $("#follow_up").change(function() {
      $doctorDiv.hide(); $dateDiv.hide(); $slotDiv.hide();
      $appointmentFor.prop({'selectedIndex': 0, 'required': false, 'disabled': true});
      $appointmentDoctor.prop({'selectedIndex': 0, 'required': false, 'disabled': true});
      $appointmentDate.val('').prop({'required': false, 'disabled': true});
      $appointmentSlot.prop({'selectedIndex': 0, 'required': false, 'disabled': true});
      if(this.checked) { $appointmentFor.prop({'required': true, 'disabled': false}); }
   });
   
   // 🎯 FIXED EXPLICIT MAPPING FOR INVESTIGATIONS DROPDOWNS
   $("#investigation_suggestion").change(function() {
      var lists = "select#female_minvestigation_suggestion_list, select#male_minvestigation_suggestion_list";
      $(lists).prop('disabled', true);
      
      if (typeof $(lists).multiselect === 'function') {
          $(lists).multiselect('deselectAll', false);
          $(lists).multiselect('updateButtonText');
          $(lists).multiselect('disable');
      }
      
      if(this.checked) {
         $(lists).prop('disabled', false);
         if (typeof $(lists).multiselect === 'function') {
             $(lists).multiselect('enable');
             $(lists).multiselect('refresh');
         }
      }
   });
   
   // Management Advised toggle mappings
   $("#procedure_suggestion").change(function() {
      var $dropdowns = $('#list_india, #list_non_india');
      $dropdowns.prop('disabled', true);
      if (typeof $dropdowns.multiselect === 'function') {
          $dropdowns.multiselect('deselectAll', false);
          $dropdowns.multiselect('updateButtonText');
          $dropdowns.multiselect('disable');
      }
      
      if(this.checked) {
         $dropdowns.prop('disabled', false);
         if (typeof $dropdowns.multiselect === 'function') {
             $dropdowns.multiselect('enable');
             $dropdowns.multiselect('refresh');
         }
      }
   });
   
   // Master global structure initialization sequence
   $(document).ready(function() {
       var multiselectConfig = {
           includeSelectAllOption: true,
           enableFiltering: true,
           enableCaseInsensitiveFiltering: true,
           filterPlaceholder: 'Search...',
           maxHeight: 200,
           buttonWidth: '100%',
           dropdownParent: $('body'),
           position: { my: 'left top', at: 'left bottom', collision: 'flip' }
       };
       try {
           $('.multidselect_dropdown, .multidselect_dropdown_1, .multidselect_dropdown_2').multiselect(multiselectConfig);
       } catch(e) { console.log('Plugin fallback initialized safely.'); }
   });
   
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
               '<input type="hidden" required readonly value="' + medicineId + '" name="' + gender + '_medicine_name' + suffix + '_' + countr + '">' +
               '</td>' +
               '<td style="border:1px solid #000;"><input type="number" step="0.1" style="width:100%;" name="' + gender + '_medicine_dosage' + suffix + '_' + countr + '" required></td>' +
               '<td style="border:1px solid #000;"><input type="text" style="width:100%;" name="' + gender + '_medicine_remarks' + suffix + '_' + countr + '" required></td>' +
               '<td style="border:1px solid #000;"><input type="date" style="width:100%;" name="' + gender + '_medicine_when_start' + suffix + '_' + countr + '" required></td>' +
               '<td style="border:1px solid #000;"><input type="number" style="width:100%;" name="' + gender + '_medicine_days' + suffix + '_' + countr + '" required></td>' +
               '<td style="border:1px solid #000;"><select style="width:100%;" name="' + gender + '_medicine_route' + suffix + '_' + countr + '" required>' +
               '<option value="PO">PO</option><option value="IM">IM</option><option value="SC" selected>SC</option><option value="IV">IV</option>' +
               '</select></td>' +
               '<td style="border:1px solid #000;"><select style="width:100%;" name="' + gender + '_medicine_frequency' + suffix + '_' + countr + '" required>' +
               '<option value="OD">OD</option><option value="BD">BD</option><option value="TDS">TDS</option><option value="QID">QID</option>' +
               '</select></td>' +
               '<td style="border:1px solid #000;"><select style="width:100%;" name="' + gender + '_medicine_timing' + suffix + '_' + countr + '" required>' +
               '<option value="EMPTY STOMACH">EMPTY STOMACH</option><option value="BEFORE MEAL">BEFORE MEAL</option><option value="AFTER MEAL" selected>AFTER MEAL</option>' +
               '</select></td>' +
               '<td style="border:1px solid #000;"><select style="width:100%;" name="' + gender + '_medicine_take' + suffix + '_' + countr + '" required>' +
               '<option value="Daily">Daily</option><option value="Weekly">Weekly</option>' +
               '</select></td>' +
               '</tr>';
           $(tbodyId).append(row);
           countr++;
       });
       $(tableId).show();
   }
   
   $('#female_medicine_suggestion_list_ipd').change(function() {
       var selected = $(this).val();
       if (selected && selected.length > 0 && selected[0] !== '0') { generateMedicineFieldsFromArray('female', selected, '_ipd'); } 
       else { $('#female_medicine_table_ipd').hide(); }
   });

   // 🎯 HARDENING AUTOMATION & SUBMIT LOCK BYPASS ENGINE
   $(document).ready(function() {
       setTimeout(function() {
           // Forcing Auto-populate for IPD values
           var ipdSelect = $('#female_medicine_suggestion_list_ipd');
           if (ipdSelect.length > 0) {
               var selectedOptions = ipdSelect.find('option:selected');
               if (selectedOptions.length > 0) {
                   ipdSelect.trigger('change');
                   if (typeof ipdSelect.multiselect === 'function') {
                       ipdSelect.multiselect('refresh');
                   }
               }
           }
       }, 400);

       // 🚀 CRITICAL FIX: Form submit se just pehle sabhi drop-downs ko safe force-enable karein taaki data POST ho sake
       $('form').on('submit', function() {
           $('#female_minvestigation_suggestion_list, #male_minvestigation_suggestion_list').prop('disabled', false);
           $('#list_india, #list_non_india').prop('disabled', false);
       });
   });
</script>