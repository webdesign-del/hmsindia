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
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: initial !important;
    padding: 5px !important;
    opacity: 1;
}
select {
    display: block !important;
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
                  <select class="form-control multidselect_dropdown_1" multiple="multiple" id="female_minvestigation_suggestion_list" disabled name="female_minvestigation_suggestion_list[]">
                     <?php 
                     if(!empty($master_investigations)) { 
                        foreach($master_investigations as $key => $val) { 
                            // SAFETY LOGIC: Agar ID blank hai, toh naam ko value bana do taaki checkbox hamesha dikhe
                            $val_id = !empty($val['master_id']) ? $val['master_id'] : $val['investigation_name'];
                     ?>
                     <option value="<?php echo htmlspecialchars($val_id); ?>"><?php echo htmlspecialchars($val['investigation_name']); ?></option>
                     <?php 
                        } 
                     } 
                     ?>
                  </select>
               </td>
               <td>
                  <select class="form-control multidselect_dropdown_1" multiple="multiple" id="male_minvestigation_suggestion_list" disabled name="male_minvestigation_suggestion_list[]">
                     <?php 
                     if(!empty($master_investigations)) { 
                        foreach($master_investigations as $key => $val) { 
                            $val_id = !empty($val['master_id']) ? $val['master_id'] : $val['investigation_name'];
                     ?>
                     <option value="<?php echo htmlspecialchars($val_id); ?>"><?php echo htmlspecialchars($val['investigation_name']); ?></option>
                     <?php 
                        } 
                     } 
                     ?>
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
         // मेडिसिन्स का बेस एरे
         $base_medicines = array('IPD_20', 'OPD_21', 'OPD_23', 'OPD_35', 'OPD_44', 'OPD_46');
         
         // डेटाबेस डेटा का लुकअप
         $medicine_lookup = array();
         foreach($consultation_medicine_ipd as $med) {
             $medicine_lookup[$med['item_number']] = $med['item_name'];
         }
         
         // लूप को 2 बार चलाएंगे लेकिन वैल्यू सिर्फ बेस कोड ही रहेगी (जैसे: IPD_20)
         for ($i = 1; $i <= 2; $i++) {
             foreach($base_medicines as $item_number) { 
                if(isset($medicine_lookup[$item_number])) {
                    
                    // [CHANGED] अब $unique_value की जगह सीधे $item_number का इस्तेमाल किया है
                    $clean_value = $item_number; 
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

    <!-- Management Section -->
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
                <!--<select class="form-control multidselect_dropdown_2" multiple="multiple" id="sub_procedure_suggestion_list" name="sub_procedure_suggestion_list[]" disabled>
                    <?php foreach($procedures as $val) { 
                        if(isset($val['code_type']) && $val['code_type'] == "india") { ?>
                            <option value="<?= $val['ID']; ?>"><?= $val['procedure_name']." (".$val['code'].")"; ?></option>
                    <?php } } ?>
                </select>-->

<?php
$patient_id = $patient_data['patient_id'];

// CodeIgniter Query
$billed_data = $this->db->select('code')
                        ->where('patient_id', $patient_id)
                        ->where('status', 'approved')
                        ->get('hms_patient_procedure')
                        ->result_array();

$billed_codes = [];

// List of codes you want to ALLOW (not disable even if billed)
$exclude_from_disabling = ['IP218', 'IP219','IP64'];

if (!empty($billed_data)) {
    foreach ($billed_data as $row) {
        $trimmed_code = trim($row['code']);
        
        /* Check: Agar code 'IP218' ya 'IP219' array mein NAHI hai, 
           tabhi use $billed_codes mein daalo (taaki wo disable ho sake).
        */
        if (!in_array($trimmed_code, $exclude_from_disabling)) {
            $billed_codes[] = $trimmed_code;
        }
    }
}
?>
<select class="form-control multidselect_dropdown_2" multiple="multiple" id="sub_procedure_suggestion_list" name="sub_procedure_suggestion_list[]" disabled>
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
                $style_attr = ''; // Red color ke liye variable

                if (in_array($current_code, $billed_codes)) {
                    $disabled_attr = 'disabled';
                    $already_billed_text = ' [ALREADY ADVICES]';
                    // Red color aur background light grey taaki alag dikhe
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

<style>
   /* 1. Default browser select ke liye */
#sub_procedure_suggestion_list option:disabled {
    color: red !important;
    background-color: #fff0f0; /* Light red background taaki highlight ho */
    -webkit-text-fill-color: red; /* Safari/Chrome fix */
}

/* 2. Agar Select2 Plugin use kar rahe hain (Most likely) */
.select2-container--default .select2-results__option[aria-disabled=true] {
    color: red !important;
    font-weight: bold;
}

/* 3. Bootstrap Multiselect ke liye */
.multiselect-container > li.disabled > a > label {
    color: red !important;
}
</style>


            </div>
            <div class="col-md-6">
               <label style="color:#ff0000;">International Patient</label>
                <!--<select class="form-control multidselect_dropdown_2" multiple="multiple" id="sub_procedure_suggestion_list" name="sub_procedure_suggestion_list[]" disabled>
                    <?php foreach($procedures as $val) { 
                        if(isset($val['code_type']) && $val['code_type'] == "non-india") { ?>
                            <option value="<?= $val['ID']; ?>"><?= $val['procedure_name']." (".$val['code'].")"; ?></option>
                    <?php } } ?>
                </select>-->
                <select class="form-control multidselect_dropdown_2" multiple="multiple" id="sub_procedure_suggestion_list" name="sub_procedure_suggestion_list[]" disabled>
    <?php 
    if(!empty($procedures)) {
        // 1. Group the procedures by Package ID in a temporary array
        $grouped_procedures = [];
        foreach($procedures as $val) {
            if(isset($val['code_type']) && $val['code_type'] == "non-india") {
                $p_id = !empty($val['package_id']) ? $val['package_id'] : 'General';
                $grouped_procedures[$p_id][] = $val;
            }
        }

        // 2. Loop through the grouped array to create the dropdown
        foreach($grouped_procedures as $package_id => $items) {
            // Label the group (e.g., Package 1, Package 2)
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
</div>s
<script>
$(document).ready(function() {
    
    // ==========================================
    // 1. INITIALIZE ALL MULTISELECT DROPDOWNS
    // ==========================================
    // 🚨 FIX: Sirf INVESTIGATIONS ke options se disabled hatayenge.
    // Procedures (Management Advised) ko touch nahi karenge taaki "Already Adviced" disabled rahein.
    $('#female_minvestigation_suggestion_list option, #male_minvestigation_suggestion_list option').each(function() {
        $(this).removeAttr('disabled').prop('disabled', false); 
        if ($(this).val() == "" || $(this).val() == null) {
            $(this).val($(this).text().trim());
        }
    });

    var multiselectConfig = {
        includeSelectAllOption: true,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        filterPlaceholder: 'Search...',
        maxHeight: 250,
        buttonWidth: '100%',
        dropdownParent: $('body'),
        position: {
            my: 'left top',
            at: 'left bottom',
            collision: 'flip'
        }
    };
   
    try {
        $('.multidselect_dropdown, .multidselect_dropdown_1, .multidselect_dropdown_2').multiselect(multiselectConfig);
    } catch(e) {
        console.error('Multiselect initialization failed:', e);
    }
   
    $(window).on('scroll resize', function() {
        $('.multiselect-container').each(function() {
            $(this).css('position', 'absolute');
        });
    });


    // ==========================================
    // 2. CHECKBOX CHANGE EVENTS (FIXED WITH REBUILD)
    // ==========================================
    
    // --- INVESTIGATIONS CHECKBOX ---
    $("#investigation_suggestion").change(function() {
        var $maleSelect = $("#male_minvestigation_suggestion_list");
        var $femaleSelect = $("#female_minvestigation_suggestion_list");

        if(this.checked) {
            $maleSelect.prop('disabled', false).prop('required', false);
            $femaleSelect.prop('disabled', false).prop('required', true);

            // Investigations ke liye wapas confirm karte hain ki sab enable ho
            $maleSelect.find('option').removeAttr('disabled').prop('disabled', false);
            $femaleSelect.find('option').removeAttr('disabled').prop('disabled', false);

            $maleSelect.multiselect('rebuild');
            $femaleSelect.multiselect('rebuild');
        } else {
            $maleSelect.prop('disabled', true).prop('required', false);
            $('option', $maleSelect).prop('selected', false);
            $maleSelect.multiselect('rebuild');

            $femaleSelect.prop('disabled', true).prop('required', false);
            $('option', $femaleSelect).prop('selected', false);
            $femaleSelect.multiselect('rebuild');
        }
    });

    // --- MEDICINE CHECKBOX ---
    $("#medicine_suggestion_ipd").change(function() {
        var $maleSelect = $("#male_medicine_suggestion_list_ipd");
        var $femaleSelect = $("#female_medicine_suggestion_list_ipd");

        if(this.checked) {
            $maleSelect.prop('disabled', false).prop('required', false);
            $maleSelect.multiselect('rebuild');

            $femaleSelect.prop('disabled', false).prop('required', true);
            $femaleSelect.multiselect('rebuild');
        } else {
            $maleSelect.prop('disabled', true).prop('required', false);
            $('option', $maleSelect).prop('selected', false);
            $maleSelect.multiselect('rebuild');

            $femaleSelect.prop('disabled', true).prop('required', false);
            $('option', $femaleSelect).each(function() {
                if ($(this).val() !== 'OPD_44' && $(this).val() !== 'OPD_46') {
                    $(this).prop('selected', false);
                }
            });
            $femaleSelect.multiselect('rebuild');

            $("#male_medicine_table_ipd, #female_medicine_table_ipd").hide();
            $('div[id^="medicine_male_ipd_"], div[id^="medicine_female_ipd_"]').remove();
            $('input[name^="male_medicine_name_ipd_"], input[name^="female_medicine_name_ipd_"]').remove();
            $('#female_medicine_suggestion_table_ipd, #male_medicine_suggestion_table_ipd').empty();
        }
    });

    // --- PROCEDURE CHECKBOX ---
    $("#procedure_suggestion").change(function() {
        var $subSelect = $("select#sub_procedure_suggestion_list");

        if(this.checked) {
            // Dropdown enable hoga, lekin jo pehle se [ALREADY ADVICES] options disabled the, wo disabled hi rahenge
            $subSelect.prop('disabled', false).prop('required', false);
            $subSelect.multiselect('rebuild');
        } else {
            $subSelect.prop('disabled', true).prop('required', false);
            $('option', $subSelect).prop('selected', false);
            $subSelect.multiselect('rebuild');
        }
    });


    // ==========================================
    // 3. APPOINTMENT / FOLLOW UP LOGIC
    // ==========================================
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
                    $doctorDiv.show();
                    $loaderDiv.hide();          
                } 
            });
        } else {
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
        } else {
            $dateDiv.hide();
        }
        $loaderDiv.hide();
    });
   
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

    $( ".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
   
    $("#follow_up").change(function() {
        $doctorDiv.hide(); $dateDiv.hide(); $slotDiv.hide();
        $appointmentFor.prop({'selectedIndex': 0, 'required': false, 'disabled': true});
        $appointmentDoctor.prop({'selectedIndex': 0, 'required': false, 'disabled': true});
        $appointmentDate.val('').prop({'required': false, 'disabled': true});
        $appointmentSlot.prop({'selectedIndex': 0, 'required': false, 'disabled': true});
        
        if(this.checked) {
            $appointmentFor.prop({'required': true, 'disabled': false});
        }
    });


    // ==========================================
    // 4. MEDICINE TABLE GENERATION LOGIC
    // ==========================================
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
                '<td style="border:1px solid #000;"><input type="number" step="0.1" name="' + gender + '_medicine_dosage' + suffix + '_' + countr + '" required></td>' +
                '<td style="border:1px solid #000;"><input type="text" name="' + gender + '_medicine_remarks' + suffix + '_' + countr + '" required></td>' +
                '<td style="border:1px solid #000;"><input type="date" name="' + gender + '_medicine_when_start' + suffix + '_' + countr + '" required></td>' +
                '<td style="border:1px solid #000;"><input type="number" name="' + gender + '_medicine_days' + suffix + '_' + countr + '" required></td>' +
                '<td style="border:1px solid #000;"><select name="' + gender + '_medicine_route' + suffix + '_' + countr + '" required><option value="PO">PO</option><option value="IM">IM</option><option value="SC" selected>SC</option><option value="VAGINA-LY">VAGINA-LY</option><option value="IV">IV</option><option value="LOCAL">LOCAL</option><option value="NASALY">NASALY</option></select></td>' +
                '<td style="border:1px solid #000;"><select name="' + gender + '_medicine_frequency' + suffix + '_' + countr + '" required><option value="OD">OD</option><option value="BD">BD</option><option value="TDS">TDS</option><option value="QID">QID</option><option value="SOS">SOS</option><option value="HS">HS</option></select></td>' +
                '<td style="border:1px solid #000;"><select name="' + gender + '_medicine_timing' + suffix + '_' + countr + '" required><option value="EMPTY STOMACH">EMPTY STOMACH</option><option value="BEFORE MEAL">BEFORE MEAL</option><option value="AFTER MEAL" selected>AFTER MEAL</option></select></td>' +
                '<td style="border:1px solid #000;"><select name="' + gender + '_medicine_take' + suffix + '_' + countr + '" required><option value="Daily">Daily</option><option value="Biweekly">Biweekly</option><option value="Weekly">Weekly</option><option value="Blank">Blank</option><option value="Alternate Day">Alternate Day</option></select></td>' +
                '</tr>';
            
            $(tbodyId).append(row);
            countr++;
        });
        $(tableId).show();
    }
   
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

    // Auto-trigger Medicine Rows on Load
    setTimeout(function() {
        var ipdSelect = $('#female_medicine_suggestion_list_ipd');
        if (ipdSelect.length > 0 && ipdSelect.find('option:selected').length > 0) {
            ipdSelect.trigger('change');
            if (typeof ipdSelect.multiselect === 'function') {
                ipdSelect.multiselect('rebuild');
            }
        }
    }, 400);


    // ==========================================
    // 5. FORM SUBMISSION LOGIC
    // ==========================================
    $('form').on('submit', function(e) {
        e.preventDefault();
        $('#submit-followup-btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
        var formData = collectFormData();
        submitFollowUpData(formData);
    });
   
    function getMultiselectValues(selectId) {
        var values = $('#' + selectId).val() || [];
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
        data.sections.advisory_templates = getMultiselectValues('advisory_templates');
        return data;
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
                showMessage('Data submitted', 'error');
                setTimeout(function() {
                    window.location.href = '<?php echo base_url("doctor_appointments"); ?>';
                }, 2000);
                $('#submit-followup-btn').prop('disabled', false).html('<i class="fa fa-save"></i> Submit Follow-up Consultation');
            }
        });
    }

});
</script>