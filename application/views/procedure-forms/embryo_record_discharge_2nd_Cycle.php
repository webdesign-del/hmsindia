<?php  
   $all_method =& get_instance();
   
   // Variable Safety Initializations
   $patient_id = $patient_id ?? ($_POST['patient_id'] ?? '');
   $receipt_number = $receipt_number ?? ($_POST['receipt_number'] ?? '');
   $appoitmented_date = $appoitmented_date ?? ($_POST['appoitmented_date'] ?? '');
   $updated_by = $updated_by ?? '';
   $updated_type = $updated_type ?? '';
   $updated_at = $updated_at ?? date('Y-m-d H:i:s');
   $procedure_id = $procedure_id ?? '';

   // Handle Draft Form Submission
   if(isset($_POST['submit'])){
       unset($_POST['submit']);
       
       $validation_errors = array();
       $required_fields = array('patient_id', 'date_of_addmission', 'date_of_discharge');
       foreach ($required_fields as $field) {
           if (empty($_POST[$field])) {
               $validation_errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
           }
       }
       
       if (!empty($_POST['date_of_addmission']) && !empty($_POST['date_of_discharge'])) {
           $admission_date = strtotime($_POST['date_of_addmission']);
           $discharge_date = strtotime($_POST['date_of_discharge']);
           if ($discharge_date < $admission_date) {
               $validation_errors[] = 'Date of discharge cannot be earlier than date of admission';
           }
       }
       
       if (!empty($validation_errors)) {
           $message = implode(', ', $validation_errors);
           $message_type = 'error';
       } else {
           $check_sql = "SELECT * FROM embryo_record_discharge_summery WHERE patient_id='".$_POST['patient_id']."' AND receipt_number='$receipt_number'";
           $existing_record = run_select_query($check_sql);
           
           if(empty($existing_record)) {
               $_POST['created_at'] = date('Y-m-d H:i:s');
               $_POST['updated_at'] = date('Y-m-d H:i:s');
               $_POST['form_status'] = 'draft'; 
               
               $insert_sql = "INSERT INTO embryo_record_discharge_summery SET ";
               $insert_fields = array();
               foreach($_POST as $key => $value) {
                   $insert_fields[] = "`$key`='".addslashes($value)."'";
               }
               $insert_sql .= implode(', ', $insert_fields);
               
               $result = run_form_query($insert_sql);
               if($result) {
                   $message = 'Embryo record discharge form saved as draft successfully!';
                   $message_type = 'success';
               } else {
                   $message = 'Something went wrong while inserting!';
                   $message_type = 'error';
               }
           } else {
               $_POST['updated_at'] = date('Y-m-d H:i:s');
               
               $update_sql = "UPDATE embryo_record_discharge_summery SET ";
               $update_fields = array();
               foreach($_POST as $key => $value) {
                   if($key != 'patient_id' && $key != 'appoitmented_date') {
                       $update_fields[] = "`$key`='".addslashes($value)."'";
                   }
               }
               $update_sql .= implode(', ', $update_fields);
               $update_sql .= " WHERE patient_id='".$_POST['patient_id']."' AND receipt_number='$receipt_number'";
               
               $result = run_form_query($update_sql);
               if($result) {
                   $message = 'Embryo record discharge form updated successfully!';
                   $message_type = 'success';
               } else {
                   $message = 'Something went wrong while updating!';
                   $message_type = 'error';
               }
           }
       }
       
       if (isset($message)) {
           header("location:" . $_SERVER['HTTP_REFERER'] . "?m=" . base64_encode($message) . '&t=' . base64_encode($message_type));
           die();
       }
   }
   
   // Handle Final Submission (Complete Form)
   if(isset($_POST['submit_final'])){
       unset($_POST['submit_final']);
       
       $validation_errors = array();
       $required_fields = array('patient_id', 'date_of_addmission', 'date_of_discharge');
       foreach ($required_fields as $field) {
           if (empty($_POST[$field])) {
               $validation_errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
           }
       }
       
       if (!empty($_POST['date_of_addmission']) && !empty($_POST['date_of_discharge'])) {
           $admission_date = strtotime($_POST['date_of_addmission']);
           $discharge_date = strtotime($_POST['date_of_discharge']);
           if ($discharge_date < $admission_date) {
               $validation_errors[] = 'Date of discharge cannot be earlier than date of admission';
           }
       }
       
       if (!empty($validation_errors)) {
           $message = implode(', ', $validation_errors);
           $message_type = 'error';
       } else {
           $check_sql = "SELECT * FROM embryo_record_discharge_summery WHERE patient_id='".$_POST['patient_id']."' AND receipt_number='$receipt_number'";
           $existing_record = run_select_query($check_sql);
           
           if(empty($existing_record)) {
               $_POST['created_at'] = date('Y-m-d H:i:s');
               $_POST['updated_at'] = date('Y-m-d H:i:s');
               $_POST['form_status'] = 'completed';
               $_POST['completed_at'] = date('Y-m-d H:i:s');
               
               $insert_sql = "INSERT INTO embryo_record_discharge_summery SET ";
               $insert_fields = array();
               foreach($_POST as $key => $value) {
                   $insert_fields[] = "`$key`='".addslashes($value)."'";
               }
               $insert_sql .= implode(', ', $insert_fields);
               
               $result = run_form_query($insert_sql);
               if($result) {
                   $message = 'Embryo record discharge form completed and submitted successfully!';
                   $message_type = 'success';
               } else {
                   $message = 'Something went wrong while submitting!';
                   $message_type = 'error';
               }
           } else {
               $_POST['updated_at'] = date('Y-m-d H:i:s');
               $_POST['form_status'] = 'completed';
               $_POST['completed_at'] = date('Y-m-d H:i:s');
               
               $update_sql = "UPDATE embryo_record_discharge_summery SET ";
               $update_fields = array();
               foreach($_POST as $key => $value) {
                   if($key != 'patient_id' && $key != 'appoitmented_date') {
                       $update_fields[] = "`$key`='".addslashes($value)."'";
                   }
               }
               $update_sql .= implode(', ', $update_fields);
               $update_sql .= " WHERE patient_id='".$_POST['patient_id']."' AND receipt_number='$receipt_number'";
               
               $result = run_form_query($update_sql);
               if($result) {
                   $message = 'Embryo record discharge form completed and submitted successfully!';
                   $message_type = 'success';
               } else {
                   $message = 'Something went wrong while submitting!';
                   $message_type = 'error';
               }
           }
       }
       
       if (isset($message)) {
           header("location:" . $_SERVER['HTTP_REFERER'] . "?m=" . base64_encode($message) . '&t=' . base64_encode($message_type));
           die();
       }
   }
   
   // Fetch Existing Data
   $select_sql = "SELECT * FROM embryo_record_discharge_summery WHERE patient_id='".$patient_id."'";
   if(!empty($receipt_number)) {
       $select_sql .= " AND receipt_number='".$receipt_number."'";
   }
   $select_result = run_select_query($select_sql);
   
   $form_locked = false;
   if(!empty($select_result) && isset($select_result['form_status']) && $select_result['form_status'] == 'completed') {
       $form_locked = true;
   }
   
   $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
   $patient_data = run_select_query($sql3);    
    
   $sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
   $select_result4 = run_select_query($sql4);
   
   $sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".($select_result4['appoitment_for'] ?? '')."'";
   $select_result5 = run_select_query($sql5); 

   $embryo_record_query = "SELECT * FROM `embryo_record` WHERE patient_id='$patient_id'";
   $embryo_record_result = run_select_query($embryo_record_query); 
   
   if(isset($_GET['m']) && isset($_GET['t'])){
       $message = base64_decode($_GET['m']);
       $message_type = base64_decode($_GET['t']);
       
       if($message_type == 'success'){
           echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                   <i class="fa fa-check-circle"></i> ' . htmlspecialchars($message) . '
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 </div>';
       } else {
           echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                   <i class="fa fa-exclamation-circle"></i> ' . htmlspecialchars($message) . '
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 </div>';
       }
   }
   
   if($form_locked) {
       echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
               <i class="fa fa-lock"></i> This form has been completed and submitted. It is now locked for editing.
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
   }

   $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
   if(empty($page_logo)) {
       $page_logo = base_url('assets/center/default-logo.png');
   }
?>
<div class="ga-pro">
   <form action="" enctype='multipart/form-data' method="post" id="embryoRecordForm" onsubmit="return validateForm()">
    <input type="hidden" id="center" name="center" value="<?php echo $select_result5['center_name'] ?? 'Main Center'; ?>">
    <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
    <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
    <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
    <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
    <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
    <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
    <input type="hidden" value="pending" name="status">
    <input type="hidden" value="Second Cycle" name="type">   
    
    <div class="container2 red-field form mt-5 mb-5">
        <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
           <tr>
               <td style="width:50%;padding:5px;" colspan="10">
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo">
                </td>
                <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">Embryo Record</h3></td>
           </tr>
        </table>
      <div style="float: left; margin-bottom: 10px;">
         <label for="Admission">Date of Admission <span style="color: red;">*</span></label>
         <input type="date" class="Admission" name="date_of_addmission" value="<?php echo $select_result['date_of_addmission'] ?? ''; ?>" required <?php echo $form_locked ? 'disabled' : ''; ?>>
      </div>
      <div style="float: right; margin-bottom: 10px;">
         <label for="Discharge">Date of Discharge <span style="color: red;">*</span></label>
         <input type="hidden" name="date_of_discharge" id="date_of_discharge_hidden" value="<?php echo $select_result['date_of_discharge'] ?? ''; ?>">
         <input type="date" class="Discharge" name="date_of_discharge_display" value="<?php echo $select_result['date_of_discharge'] ?? ''; ?>" required <?php echo $form_locked ? 'disabled' : ''; ?>>
      </div>
      <table width="100%" class="vb45rt">
         <tbody>
            <tr style="background: #b3b9b7;">
               <td colspan="6" width="50%" style="border:1px solid;padding:5px;">
                  <strong>Details of Female Partner</strong>
               </td>
               <td colspan="6" width="50%" style="border:1px solid;padding:5px;">
                  <strong>Details of Male Partner</strong>
               </td>
            </tr>
            <tr style="background: #b3b9b7;">
               <td colspan="6" width="50%" style="border:1px solid;padding:5px;">
                  <strong>UHID : <?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></strong>
               </td>
               <td colspan="6" width="50%" style="border:1px solid;padding:5px;">
                  <strong>IIC ID: <?php echo $patient_id; ?></strong>
               </td>
            </tr>
            <tr>
               <td colspan="6" width="50%">
                  <strong>Female Partner : <?php echo $patient_data['wife_name'] ?? ''; ?> </strong>
               </td>
               <td width="50%" colspan="6">
                  <strong>Male Partner : <?php echo $patient_data['husband_name'] ?? ''; ?> </strong>
               </td>
            </tr>
            <tr>
               <td colspan="6" width="50%">
                  <strong>Age: <?php echo $patient_data['wife_age'] ?? ''; ?> Year</strong>
               </td>
               <td width="50%" colspan="6">
                  <strong>Age: <?php echo $patient_data['husband_age'] ?? ''; ?> Year</strong>
               </td>
            </tr>
         </tbody>
      </table>
      <div class="sec2">
         <h3 style="text-align: left; margin-left: 10px;">Embryo Record Details:</h3>
         <table width="100%" class="vb45rt">
            <tbody>
               <tr>
                  <td colspan="4" width="45%">
                     <label for="fertilization">Fertilization status:</label>
                     <input type="text" name="fertilization_status" value="<?php echo $select_result['fertilization_status'] ?? ''; ?>" style="width:100%;" <?php echo $form_locked ? 'disabled' : ''; ?>>
                  </td> 
                  <td colspan="4" width="45%">
                     <label for="d2">D2:</label>
                     <input type="text" name="d2_status" value="<?php echo $embryo_record_result['cell_embryos_day2'] ?? ''; ?>" style="width:100%;" <?php echo $form_locked ? 'disabled' : ''; ?>>
                  </td>
               </tr>
               <tr>
                  <td colspan="2" width="25%">
                     <label for="d3">D3:</label>
                     <input type="text" name="d3_status" value="<?php echo $embryo_record_result['cell_embryos_day3'] ?? ''; ?>" style="width:100%;" <?php echo $form_locked ? 'disabled' : ''; ?>>
                  </td>
                  <td colspan="2" width="25%">
                     <label for="d4">D4:</label>
                     <input type="text" name="d4_status" value="<?php echo $select_result['d4_status'] ?? ''; ?>" style="width:100%;" <?php echo $form_locked ? 'disabled' : ''; ?>>
                  </td>
                  <td colspan="2" width="25%">
                     <label for="d5">D5:</label>
                     <input type="text" name="d5_status" value="<?php echo $select_result['d5_status'] ?? ''; ?>" style="width:100%;" <?php echo $form_locked ? 'disabled' : ''; ?>>
                  </td>
                  <td colspan="2" width="25%">
                     <label for="d6">D6:</label>
                     <input type="text" name="d6_status" value="<?php echo $select_result['d6_status'] ?? ''; ?>" style="width:100%;" <?php echo $form_locked ? 'disabled' : ''; ?>>
                  </td>
               </tr>
               <tr>
                  <td colspan="8" width="100%">
                     <label for="pgt_embryo">Embryo number and grading sent for PGT:</label>
                     <textarea name="pgt_embryo" style="width:100%; height:60px!important" <?php echo $form_locked ? 'disabled' : ''; ?>><?php echo $select_result['pgt_embryo'] ?? ''; ?></textarea>
                  </td>
               </tr>
               <tr>
                  <td colspan="8" width="100%">
                     <label for="senior_embryologist">Senior Embryologist:</label>
                     <input type="text" name="senior_embryologist" value="<?php echo $select_result['senior_embryologist'] ?? ''; ?>" style="width:300px;" <?php echo $form_locked ? 'disabled' : ''; ?>>
                  </td>
               </tr>
            </tbody>
         </table>
         <div style="margin:20px 0px; padding:15px; background:#fff3cd; border-left:4px solid #ffc107;">
            <p style="margin:10px 0px; font-weight:bold;">Please take prescribed medicines / injections only. Don't skip/ stop any medicine on your own unless advised by the doctor.</p>
         </div>
      </div>
      <div style="clear:both; text-align:center; margin-top:30px;">
         <?php if(!$form_locked): ?>
            <input type="submit" name="submit" value="Save & Continue (Draft)" class="btn btn-warning" style="padding:10px 20px; font-size:16px;">
            <input type="submit" name="submit_final" value="Submit & Complete Form" class="btn btn-danger" style="padding:10px 20px; font-size:16px; margin-left:10px;" onclick="return confirmFinalSubmission()">
         <?php else: ?>
            <div style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
               <strong>Form Status: COMPLETED</strong><br>
               <small>This form has been submitted and completed. It is now locked for editing.</small>
            </div>
         <?php endif; ?>
         
         <input type="button" value="Print" class="btn btn-success" style="padding:10px 20px; font-size:16px; margin-left:10px;" onclick="printtable();">
      </div>
   </form>
</div>

<!-- Print Section -->
<div class="row" id="print_this_section" style="display:none;">
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
      <td style="width:50%;padding:5px;" colspan="2"><img src="<?php echo $page_logo; ?>" style="width:250px;"></td>
      <td style="width:50%;padding:5px;" colspan="2">
         <h3 style="margin-top:20px;">Department of Embryology</h3>
         <h4>Embryo Record Discharge Summary</h4>
      </td>
   </tr>
</table>

<div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">
   <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
      <div style="flex: 1; margin-right: 20px;">
         <strong>Center:</strong> <span class="center-value"><?php echo $select_result5['center_name'] ?? ''; ?></span>
      </div>
      <div style="flex: 1;">
         <strong>Date of Admission:</strong> <span class="date_of_addmission-value"><?php echo $select_result['date_of_addmission'] ?? ''; ?></span>
      </div>
   </div>
   <div style="display: flex; justify-content: space-between;">
      <div style="flex: 1; margin-right: 20px;">
         <strong>Date of Discharge:</strong> <span class="date_of_discharge-value"><?php echo $select_result['date_of_discharge'] ?? ''; ?></span>
      </div>
   </div>
</div>

<table width="100%" class="vb45rt">
   <tbody>
      <tr style="background: #b3b9b7;">
         <td colspan="6" width="50%" style="border:1px solid;padding:5px;">
            <strong>Details of Female Partner</strong>
         </td>
         <td colspan="6" width="50%" style="border:1px solid;padding:5px;">
            <strong>Details of Male Partner</strong>
         </td>
      </tr>
      <tr style="background: #b3b9b7;">
         <td colspan="6" width="50%" style="border:1px solid;padding:5px;">
            <strong>UHID : <span class="center-value"><?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></span></strong>
         </td>
         <td colspan="6" width="50%" style="border:1px solid;padding:5px;">
            <strong>IIC ID: <span class="iic_id-value"><?php echo $patient_id; ?></span></strong>
         </td>
      </tr>
      <tr>
         <td colspan="6" width="50%">
            <strong>Female Partner : <span class="wife_name-value"><?php echo $patient_data['wife_name'] ?? ''; ?></span></strong>
         </td>
         <td width="50%" colspan="6">
            <strong>Male Partner : <span class="husband_name-value"><?php echo $patient_data['husband_name'] ?? ''; ?></span></strong>
         </td>
      </tr>
      <tr>
         <td colspan="6" width="50%">
            <strong>Age: <span class="wife_age-value"><?php echo $patient_data['wife_age'] ?? ''; ?> Year</span></strong>
         </td>
         <td width="50%" colspan="6">
            <strong>Age: <span class="husband_age-value"><?php echo $patient_data['husband_age'] ?? ''; ?> Year</span></strong>
         </td>
      </tr>
   </tbody>
</table>
<div class="sec2">
   <h3 style="text-align: left; margin-left: 10px;">Embryo Record Details:</h3>
   <table width="100%" class="vb45rt">
      <tbody>
         <tr>
            <td colspan="4" width="45%">
               <strong>Fertilization status:</strong><br>
               <span class="fertilization_status-value"><?php echo $select_result['fertilization_status'] ?? ''; ?></span>
            </td>
            <td colspan="4" width="45%">
               <strong>D2:</strong><br>
               <span class="d2_status-value"><?php echo $embryo_record_result['cell_embryos_day2'] ?? ''; ?></span>
            </td>
         </tr>
         <tr>
            <td colspan="2" width="25%">
               <strong>D3:</strong><br>
               <span class="d3_status-value"><?php echo $embryo_record_result['cell_embryos_day3'] ?? ''; ?></span>
            </td>
            <td colspan="2" width="25%">
               <strong>D4:</strong><br>
               <span class="d4_status-value"><?php echo $select_result['d4_status'] ?? ''; ?></span>
            </td>
            <td colspan="2" width="25%">
               <strong>D5:</strong><br>
               <span class="d5_status-value"><?php echo $select_result['d5_status'] ?? ''; ?></span>
            </td>
            <td colspan="2" width="25%">
               <strong>D6:</strong><br>
               <span class="d6_status-value"><?php echo $select_result['d6_status'] ?? ''; ?></span>
            </td>
         </tr>
         <tr>
            <td colspan="8" width="100%">
               <strong>Embryo number and grading sent for PGT:</strong><br>
               <span class="pgt_embryo-value"><?php echo $select_result['pgt_embryo'] ?? ''; ?></span>
            </td>
         </tr>
         <tr>
            <td colspan="8" width="100%">
               <strong>Senior Embryologist:</strong><br>
               <span class="senior_embryologist-value"><?php echo $select_result['senior_embryologist'] ?? ''; ?></span>
            </td>
         </tr>
      </tbody>
   </table>
   <div style="margin:20px 0px; padding:15px; background:#fff3cd; border-left:4px solid #ffc107;">
      <p style="margin:10px 0px; font-weight:bold;">Please take prescribed medicines / injections only. Don't skip/ stop any medicine on your own unless advised by the doctor.</p>
   </div>
</div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hide flash messages automatically
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert && alert.parentNode) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    if (alert && alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }
        }, 5000);
    });
    
    // Auto-sync display discharge date with hidden parameter
    const dischargeDisplayField = document.querySelector('input[name="date_of_discharge_display"]');
    const dischargeHiddenField = document.getElementById('date_of_discharge_hidden');
    
    if (dischargeDisplayField && dischargeHiddenField) {
        dischargeDisplayField.addEventListener('change', function() {
            dischargeHiddenField.value = this.value;
        });
        // Initial sync
        dischargeHiddenField.value = dischargeDisplayField.value;
    }
});

function validateForm() {
    const admissionElem = document.querySelector('input[name="date_of_addmission"]');
    const dischargeElem = document.querySelector('input[name="date_of_discharge_display"]');
    
    const admissionDate = admissionElem ? admissionElem.value : '';
    const dischargeDate = dischargeElem ? dischargeElem.value : '';
    
    if (!admissionDate) {
        alert('Please select admission date');
        if(admissionElem) admissionElem.focus();
        return false;
    }
    
    if (!dischargeDate) {
        alert('Please select discharge date');
        if(dischargeElem) dischargeElem.focus();
        return false;
    }
    
    if (admissionDate && dischargeDate) {
        const admission = new Date(admissionDate);
        const discharge = new Date(dischargeDate);
        
        if (discharge < admission) {
            alert('Date of discharge cannot be earlier than date of admission');
            if(dischargeElem) dischargeElem.focus();
            return false;
        }
    }
    
    return true;
}

function confirmFinalSubmission() {
    if (!validateForm()) {
        return false;
    }
    return confirm('WARNING: This will complete and submit the form. Once submitted, the form will be locked and cannot be edited. Are you sure you want to proceed?');
}

function printtable() {
    var divToPrint = document.getElementById('print_this_section');
    var newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write('<html><head><title>Print View</title><style>body{font-family:Arial,sans-serif;} table{width:100%;border-collapse:collapse;} td,th{border:1px solid #ccc;padding:8px;}</style></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
    newWin.document.close();
    setTimeout(function(){ newWin.close(); }, 500);
}
</script>

<style>
   .ga-pro {
       background: #fff;
       padding: 20px;
       border-radius: 8px;
       box-shadow: 0 2px 10px rgba(0,0,0,0.1);
       margin: 20px 0;
   }
   .alert {
       margin-bottom: 20px;
       padding: 15px 20px;
       border: 1px solid transparent;
       border-radius: 6px;
       font-size: 14px;
       position: relative;
   }
   .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
   .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
   .alert-warning { color: #856404; background-color: #fff3cd; border-color: #ffeaa7; }
   
   .fg45yu, .vb45rt {
       width: 100%;
       border-collapse: collapse;
       margin-bottom: 20px;
   }
   .fg45yu td { padding: 10px; text-align: center; }
   .vb45rt td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
   .vb45rt tr:nth-child(even) { background-color: #f9f9f9; }
   
   input[type="text"], input[type="number"], input[type="date"], select, textarea {
       border: 1px solid #ddd;
       border-radius: 4px;
       padding: 8px;
       font-size: 14px;
   }
   label { font-weight: bold; color: #333; display: block; margin-bottom: 5px; }
   
   .btn-success { background-color: #28a745; border-color: #28a745; color: white; }
   .btn-warning { background-color: #ffc107; border-color: #ffc107; color: #212529; }
   .btn-danger { background-color: #dc3545; border-color: #dc3545; color: white; }
</style>