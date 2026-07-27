<?php 
// 💡 CodeIgniter instance fetch karke $db_prefix get karein
$CI =& get_instance();
$db_prefix = $CI->config->item('db_prefix');

// Fallback empty strings for PCP_NDT variables to avoid undefined index crash
$further_referredfor_dellvery = isset($further_referredfor_dellvery) ? $further_referredfor_dellvery : '';
$outcome_of_pregnancy        = isset($outcome_of_pregnancy) ? $outcome_of_pregnancy : '';
$malformation_in_newborn      = isset($malformation_in_newborn) ? $malformation_in_newborn : '';

// Ensure patient_id is not empty before executing anything
if (!empty($patient_id)) {

    // php code to Insert / Update data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
    
        $wife_name                  = $_POST['wife_name'] ?? '';
        $husband_name               = $_POST['husband_name'] ?? '';
        $wife_phone                 = $_POST['wife_phone'] ?? '';
        $wife_age                   = $_POST['wife_age'] ?? '';
        $female_issues              = $_POST['female_issues'] ?? '';
        $wife_address               = $_POST['wife_address'] ?? '';
        $female_pregnancy_other_p   = $_POST['female_pregnancy_other_p'] ?? '';
        $female_pregnancy_other_l   = $_POST['female_pregnancy_other_l'] ?? '';
        $female_pregnancy_other_a   = $_POST['female_pregnancy_other_a'] ?? '';
        $details_management_advised = $_POST['details_management_advised'] ?? '';
        $IVF_Consultant             = $_POST['IVF_Consultant'] ?? '';
        $center                     = $_POST['center'] ?? '';
        
        // 1. Extra non-table fields ko $_POST se clean karein
        unset(
            $_POST['wife_name'], 
            $_POST['wife_phone'], 
            $_POST['husband_name'], 
            $_POST['wife_age'], 
            $_POST['wife_address'],
            $_POST['female_pregnancy_other_p'], 
            $_POST['female_pregnancy_other_l'], 
            $_POST['female_pregnancy_other_a'], 
            $_POST['details_management_advised'], 
            $_POST['IVF_Consultant']
        );

        // Ensure patient_id exists in $_POST
        $_POST['patient_id'] = $patient_id;
        
        // 2. Safely convert arrays (Checkboxes/Multi-selects) to comma-separated strings
        foreach ($_POST as $key => $val) {
            if (is_array($val)) {
                $_POST[$key] = implode(',', $val);
            }
        }
        
        // Dynamic SQL Array Builder for Discharge Summary Table
        $sqlArr = array();
        foreach($_POST as $key => $value) {
            $sqlArr[] = " `$key` = '".addslashes($value)."'";
        }
        
        // Check if record already exists in main discharge table
        $sql = "SELECT * FROM `pesa_tesatesemicro_tese_discharge_summary` WHERE patient_id='$patient_id' AND receipt_number='$receipt_number'";
        $select_result = run_select_query($sql);
    
        if(empty($select_result)){
            // 🎯 FIXED: Extra patient_id parameter removed to prevent duplicate column error
            $query = "INSERT INTO `pesa_tesatesemicro_tese_discharge_summary` SET " . implode(',' , $sqlArr);
        } else {
            // UPDATE main discharge table
            $query = "UPDATE `pesa_tesatesemicro_tese_discharge_summary` SET " . implode(',' , $sqlArr) . " WHERE patient_id='$patient_id' AND receipt_number='$receipt_number'";
        }
        $result = run_form_query($query); 

        // 3. CHECK & UPDATE PCP_NDT TABLE (Prevents Duplicates)
        $check_pcp = "SELECT * FROM `pcp_ndt` WHERE patient_id='$patient_id' AND test_type='TESA'";
        $pcp_result = run_select_query($check_pcp);

        if(empty($pcp_result)){
            // Insert new PCP_NDT record
            $query2 = "INSERT INTO `pcp_ndt` (patient_id, wife_name, husband_name, wife_phone, wife_age, female_issues, wife_address, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised, IVF_Consultant, further_referredfor_dellvery, outcome_of_pregnancy, malformation_in_newborn, center, test_type, type, date) VALUES 
            ('$patient_id','".addslashes($wife_name)."', '".addslashes($husband_name)."', '".addslashes($wife_phone)."', '".addslashes($wife_age)."','".addslashes($female_issues)."', '".addslashes($wife_address)."', 'P:".addslashes($female_pregnancy_other_p)."', 'L:".addslashes($female_pregnancy_other_l)."', 'A:".addslashes($female_pregnancy_other_a)."', '".addslashes($details_management_advised)."','".addslashes($IVF_Consultant)."', '".addslashes($further_referredfor_dellvery)."', '".addslashes($outcome_of_pregnancy)."', '".addslashes($malformation_in_newborn)."', '".addslashes($center)."', 'TESA','TESA','" . date('Y-m-d H:i:s') . "')";
        } else {
            // Update existing PCP_NDT record
            $query2 = "UPDATE `pcp_ndt` SET 
                wife_name = '".addslashes($wife_name)."',
                husband_name = '".addslashes($husband_name)."',
                wife_phone = '".addslashes($wife_phone)."',
                wife_age = '".addslashes($wife_age)."',
                female_issues = '".addslashes($female_issues)."',
                wife_address = '".addslashes($wife_address)."',
                female_pregnancy_other_p = 'P:".addslashes($female_pregnancy_other_p)."',
                female_pregnancy_other_l = 'L:".addslashes($female_pregnancy_other_l)."',
                female_pregnancy_other_a = 'A:".addslashes($female_pregnancy_other_a)."',
                details_management_advised = '".addslashes($details_management_advised)."',
                IVF_Consultant = '".addslashes($IVF_Consultant)."',
                center = '".addslashes($center)."'
                WHERE patient_id='$patient_id' AND test_type='TESA'";
        }
        run_form_query($query2);

        if($result){
            header("location:" .$_SERVER['HTTP_REFERER']."?patient_id=".$patient_id."&m=".base64_encode('Discharge form saved successfully!').'&t='.base64_encode('success'));
            die();
        } else {
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
            die();
        }
    }
  
    // ==============================================================================
    // 🎯 DATA FETCHING BLOCK (Display / Print Mode)
    // ==============================================================================
    $sql = "SELECT * FROM `pesa_tesatesemicro_tese_discharge_summary` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($sql);
    
    $sql2 = "Select * from ".$db_prefix."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);

    $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $patient_data = run_select_query($sql3); 

    $sql4 = "SELECT patient_id, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised FROM `hms_patient_medical_info` WHERE patient_id='$patient_id'";
    $select_result4 = run_select_query($sql4);
    
    if(empty($select_result2)) { $select_result2 = array('appoitment_for' => ''); }
    
    $sql3 = "Select * from ".$db_prefix."centers where center_number='".($select_result2['appoitment_for'] ?? '')."'";
    $select_result3 = run_select_query($sql3);

    $select_result5 = array('doctor_name' => '');
    if (isset($_SESSION['logged_doctor']['doctor_id'])) {
        $sql5 = "Select * from ".$db_prefix."doctors where ID='".$_SESSION['logged_doctor']['doctor_id']."'";
        $select_result5 = run_select_query($sql5);  
    }
  
    $select_result6 = array('female_pregnancy_other_p'=>'', 'female_pregnancy_other_l'=>'', 'female_pregnancy_other_a'=>'', 'details_management_advised'=>'');
    $sql6 = "SELECT patient_id, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised FROM `hms_patient_medical_info` WHERE patient_id='$patient_id'";
    $res6 = run_select_query($sql6);
    if(!empty($res6)) { $select_result6 = $res6; }

} else {
    // Fallback block if patient_id is not passed properly
    $select_result = $select_result2 = $select_result3 = $select_result5 = $select_result6 = array();
}

// Dynamic Logo handling
$page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
if(empty($page_logo)) {
    $page_logo = base_url('assets/center/default-logo.png');
}

// Checkbox Arrays Breakdown
$applicablemedicine = $procedures = $phyical = array();

if(!empty($select_result['applicablemedicine'])){
    $applicablemedicine = explode(',',$select_result['applicablemedicine']);
} 
if(!empty($select_result['procedures'])){
    $procedures = explode(',',$select_result['procedures']);
}
if(!empty($select_result['physical_examination'])){
    $phyical = explode(',',$select_result['physical_examination']);
} 
?>
<div class="ga-pro">
  <form action="" enctype='multipart/form-data' method="post">
  <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
  <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $patient_id;?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $select_result5['center_id']; ?>" class="form" name="center">
  <input type="hidden" value="<?php echo $patient_data['wife_name']; ?>" class="form" name="wife_name">
  <input type="hidden" value="<?php echo $patient_data['wife_phone']; ?>" class="form" name="wife_phone">
  <input type="hidden" value="<?php echo $patient_data['husband_name']; ?>" class="form" name="husband_name">
  <input type="hidden" value="<?php echo $receipt_number;?>" class="form" name="receipt_number">
  <input type="hidden" value="<?php echo $patient_data['wife_address']; ?>" class="form" name="wife_address">
  <input type="hidden" value="<?php echo $patient_data['wife_age']; ?>" class="form" name="wife_age">
  <input type="hidden" value="Second Cycle" name="type">  
  <input type="hidden" value="<?php echo $select_result4['female_pregnancy_other_p']; ?>" class="form" name="female_pregnancy_other_p">
  <input type="hidden" value="<?php echo $select_result4['female_pregnancy_other_l']; ?>" class="form" name="female_pregnancy_other_l">
  <input type="hidden" value="<?php echo $select_result4['female_pregnancy_other_a']; ?>" class="form" name="female_pregnancy_other_a">
  <input type="hidden" value="<?php echo $select_result4['details_management_advised']; ?>" class="form" name="details_management_advised">
  <input type="hidden" value="<?php echo $_SESSION['logged_doctor']['doctor_id'] ?>" class="form" name="doctor_id">
   <div class="container2 red-field form mt-5 mb-5">
        <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
           <tr>
               <td style="width:50%;padding:5px;" colspan="10">
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo">
                </td>
                <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">Pesa Tesa Tese Micro Tese</h3></td>
           </tr>
        </table>		
<div class="col-sm-12 col-md-12">	
<div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
  <label for="Admission">Date of Admission:</label>
  <input type="date" class="Admission" name="date_of_addmission" value="<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>">
 </div>
<div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
  <label for="Admission">Admission Time:</label>
  <input type="time" class="Admission" name="time_of_addmission" value="<?php echo isset($select_result['time_of_addmission'])?$select_result['time_of_addmission']:""; ?>">
 </div>   
<div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
  <label for="Discharge">Date of Discharge:</label>
  <input type="date" class="Discharge" name="date_of_discharge" value="<?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?>">
 </div> 
<div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
  <label for="Discharge">Discharge Time:</label>
  <input type="time" class="Discharge" name="time_of_discharge" value="<?php echo isset($select_result['time_of_discharge'])?$select_result['time_of_discharge']:""; ?>">
 </div>  
</div>    

<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
    <td colspan="3" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	 <td colspan="3" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Male Partner</strong>
	</td>
</tr>
<tr style="background: #b3b9b7;">
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%">
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td colspan="3" width="50%" >
<strong>Husband&rsquo;s name : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td colspan="3" width="50%">
<strong>Age: <?php echo $patient_data['husband_age']; ?></strong>
</td>
</tr>

<tr>
<td colspan="3" width="50%">
<strong>Provisional Diagnosis:

 <textarea name="female_issues" style="width:100%; height:150px;" > <?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?> </textarea>
</strong>
</td>
<td colspan="3" width="50%">
<strong>Final Diagnosis:

 <textarea name="male_issues" style="width:100%; height:150px;" > <?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?> </textarea>
</strong>

</td>
</tr>

<tr>
<td colspan="3" width="50%">
<strong>Name of Procedure : <input type="checkbox" class="PESA" name="procedures[]" value="PESA" <?php if(!empty($select_result['procedures']) && in_array('PESA',$procedures)){echo "checked";}?>> PESA <input type="checkbox" class="TESA" name="procedures[]" value="TESA" <?php if(!empty($select_result['procedures']) && in_array('TESA',$procedures)){echo "checked";}?>> TESA <input type="checkbox" class="TESE" name="procedures[]" value="TESE" <?php if(!empty($select_result['procedures']) && in_array('TESE',$procedures)){echo "checked";}?>> TESE <input type="checkbox" class="MICROTESE" name="procedures[]" value="MICRO TESE" <?php if(!empty($select_result['procedures']) && in_array('MICRO TESE',$procedures)){echo "checked";}?>> MICRO TESE</strong>
</td>
<td colspan="3" width="50%">
 <strong>Date of procedure:  <input type="date" class="date_of_procedure" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>">  </strong> 
</td>
</tr>
</tbody>
</table> 

<div class="sec2">
<p><strong>Physical Examination: </strong></p>
<p><input type="radio" id="Conscious" name="Conscious" value="Conscious" <?php if(isset($select_result['Conscious'])  && $select_result['Conscious']== "Conscious"){ echo "checked";} ?>>
  <label for="Conscious">Conscious</label><br>
  <input type="radio" id="oriented" name="Conscious" value="oriented" <?php if(isset($select_result['Conscious']) && $select_result['Conscious'] == "oriented"){ echo "checked";} ?>>
  <label for="oriented">oriented</label><br>  
</p>
 <input type="checkbox" class="pallor" name="physical_examination[]" value="No pallor" <?php if(!empty($select_result['physical_examination']) && in_array('No pallor',$phyical)){echo "checked";}?>>
 <label for="Condition">No pallor</label>
  <input type="checkbox" class="icterus" name="physical_examination[]" value="icterus" <?php if(!empty($select_result['physical_examination']) && in_array('icterus',$phyical)){echo "checked";}?>>
 <label for="Condition">icterus</label>
  <input type="checkbox" class="cyanosis" name="physical_examination[]" value="cyanosis" <?php if(!empty($select_result['physical_examination']) && in_array('cyanosis',$phyical)){echo "checked";}?>>
 <label for="Condition">cyanosis</label>
<input type="checkbox" class="clubbing" name="physical_examination[]" value="digital clubbing" <?php if(!empty($select_result['physical_examination']) && in_array('digital clubbing',$phyical)){echo "checked";}?>>
 <label for="Condition">digital clubbing</label>
<input type="checkbox" class="lymphadenopathy" name="physical_examination[]" value="lymphadenopathy" <?php if(!empty($select_result['physical_examination']) && in_array('lymphadenopathy', $phyical)){echo "checked";}?>>
 <label for="Condition">lymphadenopathy</label>
 <input type="checkbox" class="oedema" name="physical_examination[]" value="pedal oedema" <?php if(!empty($select_result['physical_examination']) && in_array('pedal oedema', $phyical)){echo "checked";}?>>
 <label for="Condition">pedal oedema</label>
 </p>
 <table width="100%" class="vb45rt">
<tbody>
<tr>
<td colspan="2" width="50%">
 <label for="BP">BP (mm Hg)</label>
 <textarea name="Patient_BP" style="width:100%; height:80px!important;"> <?php echo isset($select_result['Patient_BP'])?$select_result['Patient_BP']:""; ?></textarea>
  </td>
 <td width="50%">
  <label for="PR">PR (min)</label><br>
  <textarea name="Patient_PR" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_PR'])?$select_result['Patient_PR']:""; ?></textarea>
 </td>
 </tr>
 <tr>
 <td colspan="2" width="50%">
 <label for="PR">RR (min)</label>
 <textarea name="Patient_RR" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_RR'])?$select_result['Patient_RR']:""; ?></textarea>
 </td>
 <td width="50%">
 <label for="PR">Temp (F)</label>
 <textarea name="Patient_Temp" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_Temp'])?$select_result['Patient_Temp']:""; ?></textarea> 
</td>
</tr>
 <tr>
 <td colspan="2" width="50%">
<label for="PR">SPO2 (on room air)</label>
<textarea name="Patient_SPO2" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_SPO2'])?$select_result['Patient_SPO2']:""; ?></textarea> 
 </td>
 <td width="50%">
<label for="CVS">CVS</label>
<textarea name="Patient_CVS" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?></textarea> 
</tr>
 <tr>
 <td colspan="2" width="50%">
 <label for="RS">RS</label>
 <textarea name="Patient_RS" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_RS'])?$select_result['Patient_RS']:""; ?></textarea> 
</td>
 <td width="50%">
 <label for="P/A">P/A</label>
<textarea name="Patient_PA" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_PA'])?$select_result['Patient_PA']:""; ?></textarea> 
</td>
 </tr>
 <tr>
 <td colspan="2" width="50%">
 <label for="CNS">CNS</label>
 <textarea name="Patient_CNS" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_CNS'])?$select_result['Patient_CNS']:""; ?></textarea> 
</td>
 </tr>
 <tr>
 <td colspan="4" width="100%">
 <label for="CNS">Course in the hospital:</label>
 <textarea name="Patient_Course" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?></textarea> 
</td>
 </tr>
 <tr>
 <td colspan="4" width="50%">
 <label for="CNS">Condition at Discharge:</label>
 <textarea name="Patient_Condition" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?></textarea> 
</td>
 </tr>
</tbody>
</table>
</div>  
</div>  

<div class="sec3">
  <h5 style="border: 1px solid #000; margin: 0;padding: 10px 10px;">POST OP INSTRUCTION</h5>
<table width="100%">
<tbody>
<tr>
<td colspan="4" width="100%">
<p>Do fertility yoga daily</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%">
<p>Avoid hot sauna bath</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%">
<p>Consume Diet low in carbohydrate</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%">
<p>Consume Diet rich in green leafy vegetables, beans,pulses ,high protein diet</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%">
<p>Avoid/limit intake of tea and coffee/perfumes/cosmetics with fragrance</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%">
<p>Drink plenty of fluids</p>
</td>
</tr>
<tr>
  <td colspan="4" width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabCeftum"  <?php if(!empty($select_result['applicablemedicine']) && in_array('TabCeftum', $applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Tab.CEROXITUM 500 1 TAB twice daily one morning one evening after meals for 5 days</label>
 <br>
</td>
</tr>
<tr>
  <td colspan="4" width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapPantoprazole" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapPantoprazole',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Pantoprazole (40 mg) 1 CAP once daily in empty stomach for 5 days</label>
 <br>
</td>
</tr>
<tr>
<td colspan="4" width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabCrocin" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabCrocin',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Tab Crocin (500 mg) 1 TAB thrice daily eight hourly after meals for 2 days</label>
 <br>
</td>
</tr>
<tr>
 <td colspan="4" width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapBiophilM" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapBiophilM',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Biophil M 1 CAP once daily for 90 days after meals</label>
 <br>
</td> 
</tr>
<tr>
 <td colspan="4" width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapBIOUBQR" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapBIOUBQR',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap BIOUBQR 1 CAP once daily for 90 days after meals</label>
 <br>
</td>   
</tr>
<tr>
<td colspan="4" width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapVitD3" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapVitD3',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Calcitas D3 1 CAP once weekly for 90 days after meals</label>
 <br>
</td>  
</tr>
<tr>
  <td colspan="4" width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapCARNIPHIL" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapCARNIPHIL',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap CARNIPHIL 1 CAP once daily for 90 days after meals</label>
 <br>
</td>

</tr>

<tr>
<td colspan="4" width="100%">
 <label for="Condition">There are No Substitutes</label>
</td>
</tr>
<tr>
<td colspan="2" width="50%">
 <label for="BP">Medicine Advice1:</label>
 <textarea name="Medicine_Advice1" style="width:100%; height:80px!important;"> <?php echo isset($select_result['Medicine_Advice1'])?$select_result['Medicine_Advice1']:""; ?></textarea>
  </td>
 <td width="50%">
  <label for="PR">Medicine Advice2:</label><br>
  <textarea name="Medicine_Advice2" style="width:100%; height:80px!important;"><?php echo isset($select_result['Medicine_Advice2'])?$select_result['Medicine_Advice2']:""; ?></textarea>
 </td>
 </tr>
 <tr>
 <td colspan="2" width="50%">
 <label for="PR">Medicine Advice3:</label>
 <textarea name="Medicine_Advice3" style="width:100%; height:80px!important;"><?php echo isset($select_result['Medicine_Advice3'])?$select_result['Medicine_Advice3']:""; ?></textarea>
 </td>
 <td width="50%">
 <label for="PR">Medicine Advice4:</label>
 <textarea name="Medicine_Advice4" style="width:100%; height:80px!important;"><?php echo isset($select_result['Medicine_Advice4'])?$select_result['Medicine_Advice4']:""; ?></textarea> 
</td>
</tr>
</tbody>
</table>
</div>

<div class="sec2">
<ul>
<li>Continue thyroid /antihypertensive/ diabetes medications as have been taking previously.</li>
<li>To report in emergency of the hospital near by immediately if patient has abdominal pain/ vaginal bleeding/ fever /excessive cough /giddiness /vomiting/nausea/purulent discharge.</li>
<li>To take soft diet on the day of procedure. </li>
<li>To resume normal diet after one day of procedure.</li>
</ul>
</div> 

<div class="sec2" style="display: flex; padding-top: 5px;">
<label for="BP"><b>Follow Up Advice:</b> Review with DR.</label>
  <input type="text" class="followup" name="Doctor_name" value="<?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>"> <br>
 
  <label for="followup">on </label>
  <input type="date" class="follow-up" name="advice" value="<?php echo isset($select_result['advice'])?$select_result['advice']:""; ?>">

  

</div>

<div class="sec2">
  <p><strong>Please seek expert Medical Advice If:</strong></p>
<ul>
<li>High grade Fever.</li>
<li>Loose stools/ coffee coloured vomiting or passing black stools like coal tar.</li>
<li>Bleeding from any site.</li>
<li>Chest pain, breathing difficulty, loss of consciousness, profuse sweating, giddiness, palpitation, pain in abdomen.</li>
<li>Reduced urine output.</li>
<li>Severe weakness/ severe mouth ulcers.</li>
<li>Rash over skin/ swelling over body or lower limbs or face.</li>
</ul>

</div>

<div class="sec2">
 <label for="Sr Consultant">Sr Consultant  Urosurgeon</label>
 <input type="text" class="IVFConsultant" name="" value="<?php echo $select_result['Sr_Consultant']; ?>" readonly>
  <input type="hidden" class="IVFConsultant" name="Sr_Consultant" value="<?php echo $select_result5['name'];?>" readonly>
 </div>
<div class="sec2">
  
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
    
</div> 
<input type="submit" name="submit" value="submit">
</form>

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">
  
<div class="printtable prtable" id="printtable" style="display:none;"> 
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">Discharge Summary</h3></td>
</tr>
</table>
 
<form action="" enctype='multipart/form-data' method="post">			     
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="6" style="border:1px solid;width:150%;padding:5px;">
<strong>Cener: <?php echo isset($select_result['center'])?$select_result['center']:""; ?></strong>
</td>
</tr>

<tr style="background: #b3b9b7;">
<td colspan="3" style="border:1px solid;width:50%;padding:5px;">
<strong>Date of Admission:<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?></strong>
</td>
<td colspan="3" style="border:1px solid;width:50%;padding:5px;">
<strong>Date of Discharge: <?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?></strong>
</td>
</tr>

<tr style="background: #b3b9b7;">
    <td colspan="6" width="100%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
  </tr>
<tr style="background: #b3b9b7;">
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="3" style="border:1px solid;width:50%;padding:5px;">
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td colspan="3" style="border:1px solid;width:150%;padding:5px;">
<strong>Husband&rsquo;s name : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" style="border:1px solid;width:50%;padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td colspan="3" style="border:1px solid;width:150%;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?></strong>
</td>
</tr>

<tr>
<td colspan="3" style="border:1px solid;width:50%;padding:5px;">
<strong>Provisional Diagnosis:
 <textarea name="female_issues" style="width:100%; height:100px;" > <?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?> </textarea>
</strong>
</td>
<td colspan="3" style="border:1px solid;width:50%;padding:5px;">
<strong>Final Diagnosis: 
 <textarea name="male_issues" style="width:100%; height:100px;" > <?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="3" style="border:1px solid;width:50%;padding:5px;">
<strong>Name of Procedure : <input type="checkbox" class="PESA" name="procedures[]" value="PESA" <?php if(!empty($select_result['procedures']) && in_array('PESA',$procedures)){echo "checked";}?>> PESA <input type="checkbox" class="TESA" name="procedures[]" value="TESA" <?php if(!empty($select_result['procedures']) && in_array('TESA',$procedures)){echo "checked";}?>> TESA <input type="checkbox" class="TESE" name="procedures[]" value="TESE" <?php if(!empty($select_result['procedures']) && in_array('TESE',$procedures)){echo "checked";}?>> TESE <input type="checkbox" class="MICROTESE" name="procedures[]" value="MICRO TESE" <?php if(!empty($select_result['procedures']) && in_array('MICRO TESE',$procedures)){echo "checked";}?>> MICRO TESE</strong>
</td>
<td colspan="3" style="border:1px solid;width:50%;padding:5px;">
 <strong>Date of procedure:  <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?></strong> 
</td>
</tr>

<tr>
<td colspan="6" style="border:1px solid;width:100%;padding:5px;">
<p><strong>Physical Examination: </strong></p>
<p><input type="radio" id="Conscious" name="Conscious" value="Conscious" <?php if(isset($select_result['Conscious'])  && $select_result['Conscious']== "Conscious"){ echo "checked";} ?>>
  <label for="Conscious">Conscious</label><br>
  <input type="radio" id="oriented" name="Conscious" value="oriented" <?php if(isset($select_result['Conscious']) && $select_result['Conscious'] == "oriented"){ echo "checked";} ?>>
  <label for="oriented">oriented</label><br>  
</p>
 <input type="checkbox" class="pallor" name="physical_examination[]" value="No pallor" <?php if(!empty($select_result['physical_examination']) && in_array('No pallor',$phyical)){echo "checked";}?>>
 <label for="Condition">No pallor</label>
  <input type="checkbox" class="icterus" name="physical_examination[]" value="icterus" <?php if(!empty($select_result['physical_examination']) && in_array('icterus',$phyical)){echo "checked";}?>>
 <label for="Condition">icterus</label>
  <input type="checkbox" class="cyanosis" name="physical_examination[]" value="cyanosis" <?php if(!empty($select_result['physical_examination']) && in_array('cyanosis',$phyical)){echo "checked";}?>>
 <label for="Condition">cyanosis</label>
<input type="checkbox" class="clubbing" name="physical_examination[]" value="digital clubbing" <?php if(!empty($select_result['physical_examination']) && in_array('digital clubbing',$phyical)){echo "checked";}?>>
 <label for="Condition">digital clubbing</label>
<input type="checkbox" class="lymphadenopathy" name="physical_examination[]" value="lymphadenopathy" <?php if(!empty($select_result['physical_examination']) && in_array('lymphadenopathy', $phyical)){echo "checked";}?>>
 <label for="Condition">lymphadenopathy</label>
 <input type="checkbox" class="oedema" name="physical_examination[]" value="pedal oedema" <?php if(!empty($select_result['physical_examination']) && in_array('pedal oedema', $phyical)){echo "checked";}?>>
 <label for="Condition">pedal oedema</label>
</td>
</tr>

<tr>
<td colspan="6" style="border:1px solid;width:100%;padding:5px;">
<p><strong>Physical Examination: </strong></p>
<p><input type="radio" id="Conscious" name="Conscious" value="Conscious" <?php if(isset($select_result['Conscious'])  && $select_result['Conscious']== "Conscious"){ echo "checked";} ?>>
  <label for="Conscious">Conscious</label><br>
  <input type="radio" id="oriented" name="Conscious" value="oriented" <?php if(isset($select_result['Conscious']) && $select_result['Conscious'] == "oriented"){ echo "checked";} ?>>
  <label for="oriented">oriented</label><br>  
</p>
 <input type="checkbox" class="pallor" name="physical_examination[]" value="No pallor" <?php if(!empty($select_result['physical_examination']) && in_array('No pallor',$phyical)){echo "checked";}?>>
 <label for="Condition">No pallor</label>
  <input type="checkbox" class="icterus" name="physical_examination[]" value="icterus" <?php if(!empty($select_result['physical_examination']) && in_array('icterus',$phyical)){echo "checked";}?>>
 <label for="Condition">icterus</label>
  <input type="checkbox" class="cyanosis" name="physical_examination[]" value="cyanosis" <?php if(!empty($select_result['physical_examination']) && in_array('cyanosis',$phyical)){echo "checked";}?>>
 <label for="Condition">cyanosis</label>
<input type="checkbox" class="clubbing" name="physical_examination[]" value="digital clubbing" <?php if(!empty($select_result['physical_examination']) && in_array('digital clubbing',$phyical)){echo "checked";}?>>
 <label for="Condition">digital clubbing</label>
<input type="checkbox" class="lymphadenopathy" name="physical_examination[]" value="lymphadenopathy" <?php if(!empty($select_result['physical_examination']) && in_array('lymphadenopathy', $phyical)){echo "checked";}?>>
 <label for="Condition">lymphadenopathy</label>
 <input type="checkbox" class="oedema" name="physical_examination[]" value="pedal oedema" <?php if(!empty($select_result['physical_examination']) && in_array('pedal oedema', $phyical)){echo "checked";}?>>
 <label for="Condition">pedal oedema</label>
</td>
</tr>
</tbody>
</table> 

<div class="sec2">

 </p>
 <table width="100%" class="vb45rt">
<tbody>
<tr>
<td colspan="2" style="border:1px solid;width:50%;padding:5px;">
 <label for="BP">BP (mm Hg)</label>
 <textarea name="Patient_BP" style="width:100%; height:80px!important;"> <?php echo isset($select_result['Patient_BP'])?$select_result['Patient_BP']:""; ?></textarea>
  </td>
 <td style="border:1px solid;width:50%;padding:5px;">
  <label for="PR">PR (min)</label><br>
  <textarea name="Patient_PR" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_PR'])?$select_result['Patient_PR']:""; ?></textarea>
 </td>
 </tr>
 <tr>
 <td colspan="2" style="border:1px solid;width:50%;padding:5px;">
 <label for="PR">RR (min)</label>
 <textarea style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_RR'])?$select_result['Patient_RR']:""; ?></textarea>
 </td>
 <td style="border:1px solid;width:50%;padding:5px;">
 <label for="PR">Temp (F)</label>
 <textarea style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_Temp'])?$select_result['Patient_Temp']:""; ?></textarea> 
</td>
</tr>
 <tr>
 <td colspan="2" style="border:1px solid;width:50%;padding:5px;">
<label for="PR">SPO2 (on room air)</label>
<textarea style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_SPO2'])?$select_result['Patient_SPO2']:""; ?></textarea> 
 </td>
 <td style="border:1px solid;width:50%;padding:5px;">
<label for="CVS">CVS</label>
<textarea style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?></textarea> 
</tr>
 <tr>
 <td colspan="2" style="border:1px solid;width:50%;padding:5px;">
 <label for="RS">RS</label>
 <textarea style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_RS'])?$select_result['Patient_RS']:""; ?></textarea> 
</td>
 <td style="border:1px solid;width:100%;padding:5px;">
 <label for="P/A">P/A</label>
<textarea style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_PA'])?$select_result['Patient_PA']:""; ?></textarea> 
</td>
 </tr>
 <tr>
 <td colspan="4" style="border:1px solid;width:100%;padding:5px;">
 <label for="CNS">CNS</label>
 <textarea name="Patient_CNS" style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_CNS'])?$select_result['Patient_CNS']:""; ?></textarea> 
</td>
 </tr>
 <tr>
 <td colspan="2" style="border:1px solid;width:50%;padding:5px;">
 <label for="CNS">Course in the hospital:</label>
 <textarea style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?></textarea> 
</td>
 <td colspan="2" style="border:1px solid;width:50%;padding:5px;">
 <label for="CNS">Condition at Discharge:</label>
 <textarea style="width:100%; height:80px!important;"><?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?></textarea> 
</td>
 </tr>
</tbody>
</table>
</div>  
 

<div class="sec3">
  <h5 style="border: 1px solid #000; margin: 0;padding: 10px 10px;">POST OP INSTRUCTION</h5>
<table width="100%">
<tbody>
<tr>
<td colspan="4" style="border:1px solid;width:100%;padding:5px;">
<p>Do fertility yoga daily</p>
</td>
</tr>
<tr>
<td colspan="4" style="border:1px solid;width:100%;padding:5px;">
<p>Avoid hot sauna bath</p>
</td>
</tr>
<tr>
<td colspan="4" style="border:1px solid;width:100%;padding:5px;">
<p>Consume Diet low in carbohydrate</p>
</td>
</tr>
<tr>
<td colspan="4" style="border:1px solid;width:100%;padding:5px;">
<p>Consume Diet rich in green leafy vegetables, beans,pulses ,high protein diet</p>
</td>
</tr>
<tr>
<td colspan="4" style="border:1px solid;width:100%;padding:5px;">
<p>Avoid/limit intake of tea and coffee/perfumes/cosmetics with fragrance</p>
</td>
</tr>
<tr>
<td colspan="4" style="border:1px solid;width:100%;padding:5px;">
<p>Drink plenty of fluids</p>
</td>
</tr>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabCeftum', $applicablemedicine)){ ?>
<tr>
  <td width="100%" colspan="4"  style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabCeftum"  <?php if(!empty($select_result['applicablemedicine']) && in_array('TabCeftum', $applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Tab.CEROXITUM 500 1 TAB twice daily one morning one evening after meals for 5 days</label>
 <br>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('CapPantoprazole',$applicablemedicine)){ ?>
<tr>
  <td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapPantoprazole" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapPantoprazole',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Pantoprazole (40 mg) 1 CAP once daily in empty stomach for 5 days</label>
 <br>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabCrocin',$applicablemedicine)){ ?>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabCrocin" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabCrocin',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Tab Crocin (500 mg) 1 TAB thrice daily eight hourly after meals for 2 days</label>
 <br>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('CapBiophilM',$applicablemedicine)){ ?>
<tr>
 <td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapBiophilM" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapBiophilM',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Biophil M 1 CAP once daily for 90 days after meals</label>
 <br>
</td> 
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('CapBIOUBQR',$applicablemedicine)){ ?>
<tr>
 <td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapBIOUBQR" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapBIOUBQR',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap BIOUBQR 1 CAP once daily for 90 days after meals</label>
 <br>
</td>   
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('CapVitD3',$applicablemedicine)){ ?>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapVitD3" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapVitD3',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Calcitas D3 1 CAP once weekly for 90 days after meals</label>
 <br>
</td>  
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('CapCARNIPHIL',$applicablemedicine)){ ?>
<tr>
  <td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapCARNIPHIL" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapCARNIPHIL',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap CARNIPHIL 1 CAP once daily for 90 days after meals</label>
</td>
</tr>
<?php } ?>
<tr>
<td colspan="4" style="border:1px solid;width:100%;padding:5px;">
 <label for="Condition">There are No Substitutes</label>
</td>
</tr>
<tr>
<td colspan="2" style="border:1px solid;width:50%;padding:5px;">
 <label for="BP">Medicine Advice1:</label>
 <textarea name="Medicine_Advice1" style="width:100%; height:60px!important;"> <?php echo isset($select_result['Medicine_Advice1'])?$select_result['Medicine_Advice1']:""; ?></textarea>
  </td>
 <td style="border:1px solid;width:50%;padding:5px;">
  <label for="PR">Medicine Advice2:</label><br>
  <textarea name="Medicine_Advice2" style="width:100%; height:60px!important;"><?php echo isset($select_result['Medicine_Advice2'])?$select_result['Medicine_Advice2']:""; ?></textarea>
 </td>
 </tr>
 <tr>
 <td colspan="2" style="border:1px solid;width:50%;padding:5px;">
 <label for="PR">Medicine Advice3:</label>
 <textarea style="width:100%; height:60px!important;"><?php echo isset($select_result['Medicine_Advice3'])?$select_result['Medicine_Advice3']:""; ?></textarea>
 </td>
 <td style="border:1px solid;width:50%;padding:5px;">
 <label for="PR">Medicine Advice4:</label>
 <textarea style="width:100%; height:60px!important;"><?php echo isset($select_result['Medicine_Advice4'])?$select_result['Medicine_Advice4']:""; ?></textarea> 
</td>
</tr>

 <tr>
 <td colspan="4" style="border:1px solid;width:100%;padding:5px;">
 <ul>
<li>Continue thyroid /antihypertensive/ diabetes medications as have been taking previously.</li>
<li>To report in emergency of the hospital near by immediately if patient has abdominal pain/ vaginal bleeding/ fever /excessive cough /giddiness /vomiting/nausea/purulent discharge.</li>
<li>To take soft diet on the day of procedure. </li>
<li>To resume normal diet after one day of procedure.</li>
</ul></td>
</tr>

 <tr>
 <td colspan="2" style="border:1px solid;width:50%;padding:5px;">
<label for="BP"><b>Follow Up Advice:</b> Review with DR.</label>
  <?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>
</td>
 <td colspan="2" style="border:1px solid;width:50%;padding:5px;">
<label for="followup">on </label>
  <?php echo isset($select_result['advice'])?$select_result['advice']:""; ?>
</td>
</tr>

<tr>
<td colspan="4" style="border:1px solid;width:100%;padding:5px;">
<p><strong>Please seek expert Medical Advice If:</strong></p>
<ul>
<li>High grade Fever.</li>
<li>Loose stools/ coffee coloured vomiting or passing black stools like coal tar.</li>
<li>Bleeding from any site.</li>
<li>Chest pain, breathing difficulty, loss of consciousness, profuse sweating, giddiness, palpitation, pain in abdomen.</li>
<li>Reduced urine output.</li>
<li>Severe weakness/ severe mouth ulcers.</li>
<li>Rash over skin/ swelling over body or lower limbs or face.</li>
</ul>
</td>
</tr>

<tr>
<td colspan="4" style="border:1px solid;width:100%;padding:5px;">
<label for="Sr Consultant">Sr Consultant  Urosurgeon</label>
 <textarea style="width:100%; height:80px!important;"> <?php echo isset($select_result['Sr_Consultant'])?$select_result['Sr_Consultant']:""; ?></textarea>
 
</td>
</tr>

<tr>
<td colspan="4" style="border:1px solid;width:100%;padding:5px;">
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
 
</td>
</tr>
</tbody>
</table>
</div>

</form>
</div> 
</div>

<style>
select#center {
    display: block!important;
}
input[type=checkbox], input[type=radio] {
    opacity: 1 !important;
    left: 0 !important;
    position: unset !important;
    margin: 9px !important;
}
.sec3 p {
    color: red;
}
.sec3 td {
    text-align: left;
}
.sec2 {
    border: 1px solid #000;
}
.sec2 p {
    margin: 0px;
    padding: 2px 10px;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
td {
  border: 1px solid #000;
  text-align: center;
  padding: 5px; 
}
.ga-pro h3 {
      text-align: center;
    font-size: 25px;
}
form {
    padding-left: 10px;
    margin-bottom: 4px;
}
.sec3 label {
    color: red;
}
.nb56ty {
    border: 1px solid #000;
}
.nb56ty input {
    width: 100%;
}
.vb45rt td {text-align: left; padding-left: 10px;}
</style>  
<script>
$(document).ready(function(){
    $('#btn').prop('disabled', false);

    $(".ptable").click(function(){
        $('.searchform').hide();
        $('.printbtn').hide(); 
        $('.prtable').css('display', 'block');
      
        var divToPrint = document.getElementById('printtable');
        var newWin = window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();
      
        setTimeout(function(){
            newWin.close();
        }, 500); 
    });
});
</script>     