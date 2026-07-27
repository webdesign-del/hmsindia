<?php 
$all_method =& get_instance();

    // php code to Insert/Update data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
       
        // 1. Extra form inputs extraction
        $wife_name                    = $_POST['wife_name'] ?? '';
        $husband_name                 = $_POST['husband_name'] ?? '';
        $wife_phone                   = $_POST['wife_phone'] ?? '';
        $wife_age                     = $_POST['wife_age'] ?? '';
        $wife_address                 = $_POST['wife_address'] ?? '';
        $female_pregnancy_other_p     = $_POST['female_pregnancy_other_p'] ?? '';
        $female_pregnancy_other_l     = $_POST['female_pregnancy_other_l'] ?? '';
        $female_pregnancy_other_a     = $_POST['female_pregnancy_other_a'] ?? '';
        $details_management_advised   = $_POST['details_management_advised'] ?? '';
        $IVF_Consultant               = $_POST['IVF_Consultant'] ?? '';
        $center                       = $_POST['center'] ?? '';
        
        $female_issues                = $_POST['female_issues'] ?? '';
        $further_referredfor_dellvery = $_POST['further_referredfor_dellvery'] ?? '';
        $outcome_of_pregnancy        = $_POST['outcome_of_pregnancy'] ?? '';
        $malformation_in_newborn      = $_POST['malformation_in_newborn'] ?? '';
      
        // 2. Unset all non-table fields so they don't break dynamic queries
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
            $_POST['further_referredfor_dellvery'],
            $_POST['outcome_of_pregnancy'],
            $_POST['malformation_in_newborn'],
            $_POST['Medicine_Advice1'],
            $_POST['Medicine_Advice2'],
            $_POST['Medicine_Advice3'],
            $_POST['Medicine_Advice4'],
            $_POST['Sr_Consultant']
        );
             
        if(!empty($_POST['Conscious']) && isset($_POST['Conscious'])){
            if(is_array($_POST['Conscious'])) $_POST['Conscious'] = implode(',', $_POST['Conscious']);
        }
        if(!empty($_POST['applicablemedicine']) && isset($_POST['applicablemedicine'])){
            if(is_array($_POST['applicablemedicine'])) $_POST['applicablemedicine'] = implode(',', $_POST['applicablemedicine']);
        }
        if(!empty($_POST['physical_examination']) && isset($_POST['physical_examination'])){
            if(is_array($_POST['physical_examination'])) $_POST['physical_examination'] = implode(',', $_POST['physical_examination']);
        }
        if(!empty($_POST['procedures']) && isset($_POST['procedures'])){
            if(is_array($_POST['procedures'])) $_POST['procedures'] = implode(',', $_POST['procedures']);
        }
        
        // 3. Dynamic array query generator for iui_discharge_summary
        $sqlArr = array();
        foreach( $_POST as $key => $value ) {
            if (is_array($value)) { $value = implode(',', $value); }
            $sqlArr[] = " `$key` = '".addslashes($value)."'";
        }
        
        // Check if record exists in iui_discharge_summary
        $sql = "SELECT * FROM `testicular_prp_discharge_summary` WHERE patient_id='$patient_id'";
        $select_result = run_select_query($sql);
        
        if(empty($select_result)){
            $query = "INSERT INTO `testicular_prp_discharge_summary` SET " . implode(',' , $sqlArr);
        } else {
            $query = "UPDATE `testicular_prp_discharge_summary` SET " . implode(',' , $sqlArr) . " WHERE patient_id='$patient_id'";
        }
        $result = run_form_query($query); 

        // 4. CHECK & UPDATE PCP_NDT TABLE
        $check_pcp = "SELECT * FROM `pcp_ndt` WHERE patient_id='$patient_id' AND test_type='TESTICULAR_PRP'";
        $pcp_result = run_select_query($check_pcp);

        if(empty($pcp_result)){
            $query2 = "INSERT INTO `pcp_ndt` (patient_id, wife_name, husband_name, wife_phone, wife_age, female_issues, wife_address, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised, IVF_Consultant, further_referredfor_dellvery, outcome_of_pregnancy, malformation_in_newborn, center, test_type, type, date) VALUES 
            ('$patient_id','$wife_name', '$husband_name', '$wife_phone', '$wife_age', '$female_issues', '$wife_address', 'P:$female_pregnancy_other_p', 'L:$female_pregnancy_other_l', 'A:$female_pregnancy_other_a', '$details_management_advised', '$IVF_Consultant', '$further_referredfor_dellvery', '$outcome_of_pregnancy', '$malformation_in_newborn', '$center', 'TESA','TESA','" . date('Y-m-d H:i:s') . "')";
        } else {
            $query2 = "UPDATE `pcp_ndt` SET 
                wife_name = '$wife_name',
                husband_name = '$husband_name',
                wife_phone = '$wife_phone',
                wife_age = '$wife_age',
                female_issues = '$female_issues',
                wife_address = '$wife_address',
                female_pregnancy_other_p = 'P:$female_pregnancy_other_p',
                female_pregnancy_other_l = 'L:$female_pregnancy_other_l',
                female_pregnancy_other_a = 'A:$female_pregnancy_other_a',
                details_management_advised = '$details_management_advised',
                IVF_Consultant = '$IVF_Consultant',
                further_referredfor_dellvery = '$further_referredfor_dellvery',
                outcome_of_pregnancy = '$outcome_of_pregnancy',
                malformation_in_newborn = '$malformation_in_newborn',
                center = '$center'
                WHERE patient_id='$patient_id' AND test_type='TESA'";
        }
        $result2 = run_form_query($query2);

        if($result){
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Discharge form saved successfully!').'&t='.base64_encode('success'));
            die();
        } else {
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
            die();
        }
    }
    
    // Fetch Data for Display
    $sql = "SELECT * FROM `testicular_prp_discharge_summary` WHERE patient_id='$patient_id'";
    $select_result = run_select_query($sql);
    
    $sql4 = "SELECT patient_id, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised FROM `hms_patient_medical_info` WHERE patient_id='$patient_id'";
    $select_result4 = run_select_query($sql4);
    
    $sql2 = "Select * from ".$all_method->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
    
    $sql3 = "Select * from ".$all_method->config->item('db_prefix')."centers where center_number='".($select_result2['appoitment_for'] ?? '')."'";
    $select_result3 = run_select_query($sql3);
    
    $sql5 = "Select * from ".$all_method->config->item('db_prefix')."doctors where ID='".($_SESSION['logged_doctor']['doctor_id'] ?? 0)."'";
    $select_result5 = run_select_query($sql5); 

    $sql_data = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $patient_data = run_select_query($sql_data); 

    $select_iui = "SELECT * FROM `intrauterine` WHERE patient_id='$patient_id'";
    $select_result_iui = run_select_query($select_iui); 

    // Dynamic Logo handling
    $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
    if(empty($page_logo)) {
        $page_logo = base_url('assets/center/default-logo.png');
    }

    $is_complete = !empty($select_result_iui);
    
    // Fallback variables
    $center = isset($select_result['center']) ? $select_result['center'] : '';
    $updated_by = isset($updated_by) ? $updated_by : '';
    $updated_type = isset($updated_type) ? $updated_type : '';
    $updated_at = isset($updated_at) ? $updated_at : '';

    // FIX FOR UNDEFINED VARIABLES & ARRAY TYPE ERRORS
    $procedures = array();
    if(!empty($select_result['procedures'])){
        $procedures = explode(',', $select_result['procedures']);
    }

    $physical = array();
    if(!empty($select_result['physical_examination'])){
        $physical = explode(',', $select_result['physical_examination']);
    }

    $physical2 = array();
    if(!empty($select_result['Conscious'])){
        $physical2 = explode(',', $select_result['Conscious']);
    }

    $applicablemedicine = array();
    if(!empty($select_result['applicablemedicine'])){
        $applicablemedicine = explode(',', $select_result['applicablemedicine']);
    }
?>

<div class="ga-pro">
<form enctype='multipart/form-data' class="searchform" name="form" action="" method="POST">
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
  <input type="hidden" value="First Cycle" name="type"> 
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
                <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">Testicular Prp Discharge Summary</h3></td>
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

<table width="100%">
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
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td width="50%" colspan="3">
<strong>Male Partner : <?php echo $patient_data['husband_name']; ?></strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%">
<strong>Age: <?php echo $patient_data['wife_age']; ?>  </strong>
</td>
<td width="50%" colspan="3">
<strong>Age: <?php echo $patient_data['husband_age']; ?> </strong>
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
<td width="50%" colspan="3">
<strong>Name of Procedure : <input type="checkbox" class="Testicular-PRP-TPRP" name="procedures[]" value="Testicular PRP (TPRP)" <?php if(!empty($select_result['procedures']) && in_array('Testicular PRP (TPRP)',$procedures)){echo "checked";}?>>Testicular PRP (TPRP) <input type="checkbox" class="IUI" name="procedures[]" value="Testicular Stem Cell" <?php if(!empty($select_result['procedures']) && in_array('Testicular-Stem-Cell',$procedures)){echo "checked";}?>>Testicular Stem Cell</strong>
</td>
<td colspan="3" width="50%">
 <strong>Date of procedure:  <input type="date" class="Admission" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>">  </strong>

</td>
</tr>
</tbody>
</table> 

<div class="sec2">
<p><strong>Physical Examination: </strong></p>
<p><input type="checkbox" class="Conscious" name="Conscious[]" value="Conscious" <?php if(!empty($select_result['Conscious']) && in_array('Conscious', $physical2)){echo "checked";}?>>
   <label for="Condition">Conscious</label>
   <input type="checkbox" class="oriented" name="Conscious[]" value="oriented" <?php if(!empty($select_result['Conscious']) && in_array('oriented', $physical2)){echo "checked";}?>>
   <label for="Condition">oriented</label>  
</p>

  
 <input type="checkbox" class="pallor" name="physical_examination[]" value="No pallor" <?php if(!empty($select_result['physical_examination']) && isset($select_result['physical_examination']) && in_array('No pallor', $physical)){echo "checked";}?>>
 <label for="Condition">No pallor</label>
  <input type="checkbox" class="icterus" name="physical_examination[]" value="icterus" <?php if(!empty($select_result['physical_examination']) && isset($select_result['physical_examination']) && in_array('icterus', $physical)){echo "checked";}?>>
 <label for="Condition">icterus</label>
  <input type="checkbox" class="cyanosis" name="physical_examination[]" value="cyanosis" <?php if(!empty($select_result['physical_examination']) && isset($select_result['physical_examination']) && in_array('cyanosis',$physical)){echo "checked";}?>>
 <label for="Condition">cyanosis</label>
<input type="checkbox" class="clubbing" name="physical_examination[]" value="digital clubbing" <?php if(!empty($select_result['physical_examination']) && in_array('digital clubbing',$physical)){echo "checked";}?>>
 <label for="Condition">digital clubbing</label>
<input type="checkbox" class="lymphadenopathy" name="physical_examination[]" value="lymphadenopathy" <?php if(!empty($select_result['physical_examination']) && in_array('lymphadenopathy',$physical)){echo "checked";}?>>
 <label for="Condition">lymphadenopathy</label>
 <input type="checkbox" class="oedema" name="physical_examination[]" value="pedal oedema" <?php if(!empty($select_result['physical_examination']) && in_array('pedal oedema',$physical)){echo "checked";}?>>
 <label for="Condition">pedal oedema</label>
 </p>
<label for="BP">BP</label>
  <input type="text" class="bp" name="Patient_BP" value="<?php echo isset($select_result['Patient_BP'])?$select_result['Patient_BP']:""; ?>"> mm Hg <br>
<label for="PR">PR</label>
  <input type="text" class="PR" name="Patient_PR" value="<?php echo isset($select_result['Patient_PR'])?$select_result['Patient_PR']:""; ?>"> / min <br>
<label for="RR">RR</label>
  <input type="text" class="RR" name="Patient_RR" value="<?php echo isset($select_result['Patient_RR'])?$select_result['Patient_RR']:""; ?>"> / min <br>
<label for="Temp">Temp</label>
  <input type="text" class="Temp" name="Patient_Temp" value="<?php echo isset($select_result['Patient_Temp'])?$select_result['Patient_Temp']:""; ?>"> F<br>
<label for="SPO2">SPO2</label>
  <input type="text" class="SPO2" name="Patient_SPO2" value="<?php echo isset($select_result['Patient_SPO2'])?$select_result['Patient_SPO2']:""; ?>">on room air<br>
 <label for="CVS">CVS</label>
  <input type="text" class="CVS" name="Patient_CVS" value="<?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?>"><br>
<label for="RS">RS</label>
  <input type="text" class="RS" name="Patient_RS" value="<?php echo isset($select_result['Patient_RS'])?$select_result['Patient_RS']:""; ?>"><br>
 <label for="P/A">P/A</label>
  <input type="text" class="PA" name="Patient_PA" value="<?php echo isset($select_result['Patient_PA'])?$select_result['Patient_PA']:""; ?>"><br>
<label for="CNS">CNS</label>
  <input type="text" class="CNS" name="Patient_CNS" value="<?php echo isset($select_result['Patient_CNS'])?$select_result['Patient_CNS']:""; ?>"><br>
</div>  
 

<div class="sec2">
 <label for="Course">Course in the hospital:</label>
  <input type="text" class="Course" name="Patient_Course" style="width: 100%;" value="<?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?>"><br>
</div>  
<div class="sec2">
<label for="Condition">Condition at Discharge:</label>
  <input type="text" class="Condition" name="Patient_Condition" style="width: 100%;" value="<?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?>"><br>
</div> 

<div class="sec3">
  <h5 style="border: 1px solid #000; margin: 0;padding: 10px 10px;">POST OF INSTRUCTION</h5>
<table width="100%">
<tbody>
<tr>
<td width="100%">
<p>Do fertility yoga daily</p>
</td>
</tr>
<tr>
<td width="100%">
<p>Avoid hot sauna bath</p>
</td>
</tr>
<tr>
<td width="100%">
<p>Consume Diet low in carbohydrate</p>
</td>
</tr>
<tr>
<td width="100%">
<p>Consume Diet rich in green leafy vegetables, beans,pulses ,high protein diet</p>
</td>
</tr>
<tr>
<td width="100%">
<p>Avoid/limit intake of tea and coffee/perfumes/cosmetics with fragrance</p>
</td>
</tr>
<tr>
<td width="100%">
<p>Drink plenty of fluids</p>
</td>
</tr>
<tr>
  <td width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabCeftum"  <?php if(!empty($select_result['applicablemedicine']) && in_array('TabCeftum', $applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Tab.CEROXITUM 500 1 TAB twice daily one morning one evening after meals for 5 days</label>
 <br>
</td>
</tr>
<tr>
  <td width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapPantoprazole" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapPantoprazole',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Pantoprazole (40 mg) 1 CAP once daily in empty stomach for 5 days</label>
 <br>
</td>
</tr>
<tr>
<td width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabCrocin" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabCrocin',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Tab Crocin (500 mg) 1 TAB thrice daily eight hourly after meals for 2 days</label>
 <br>
</td>
</tr>
<tr>
 <td width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapBiophilM" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapBiophilM',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Biophil M 1 CAP once daily for 90 days after meals</label>
 <br>
</td> 
</tr>
<tr>
 <td width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapBIOUBQR" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapBIOUBQR',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap BIOUBQR 1 CAP once daily for 90 days after meals</label>
 <br>
</td>   
</tr>
<tr>
<td width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapVitD3" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapVitD3',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Calcitas D3 1 CAP once weekly for 90 days after meals</label>
 <br>
</td>  
</tr>
<tr>
  <td width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapCARNIPHIL" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapCARNIPHIL',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap CARNIPHIL 1 CAP once daily for 90 days after meals</label>
 <br>
</td>

</tr>
<tr>
<td width="100%">
<p>Review after 72 days</p>
</td>
</tr>
<tr>
<td width="100%">
<p>There are No Substitutes</p>
</td>
</tr>
</tbody>
</table>
<div class="nb56ty">
  <label for="other">Medicine Advice1:</label>
  <input type="text" class="other1" name="Medicine_Advice1" value="<?php echo isset($select_result['Medicine_Advice1'])?$select_result['Medicine_Advice1']:""; ?>"><br> 
    <label for="other">Medicine Advice2:</label>
  <input type="text" class="other2" name="Medicine_Advice2" value="<?php echo isset($select_result['Medicine_Advice2'])?$select_result['Medicine_Advice2']:""; ?>"><br>
    <label for="other">Medicine Advice3:</label>
  <input type="text" class="other3" name="Medicine_Advice3" value="<?php echo isset($select_result['Medicine_Advice3'])?$select_result['Medicine_Advice3']:""; ?>"><br>
    <label for="other">Medicine Advice4:</label>
  <input type="text" class="other4" name="Medicine_Advice4" value="<?php echo isset($select_result['Medicine_Advice4'])?$select_result['Medicine_Advice4']:""; ?>"><br>
</div> 
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

  <label for="followup">on</label>
  <input type="date" class="follow-up" name="advice" value="<?php echo isset($select_result['advice'])?$select_result['advice']:""; ?>">
  
<br>
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
<p><b>Sr Consultant  Urosurgeon</b></p>
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
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">Testicular Prp Discharge Summary</h3></td>
   </tr>
</table>
<div class="ga-pro">
<form action="" enctype='multipart/form-data' method="post">
<table width="100%">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
<strong>Center: <?php echo isset($select_result['center'])?$select_result['center']:""; ?></strong>
</td>
</tr>
<tr style="background: #b3b9b7;">
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of Admission: <?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?></strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of Discharge:<?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?></strong>
</td>
</tr>
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
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Male Partner : <?php echo $patient_data['husband_name']; ?></strong>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?>  </strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?> </strong>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Provisional Diagnosis:
 <textarea name="female_issues" style="width:100%; height:60px;" > <?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?> </textarea>
</strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Final Diagnosis:
 <textarea name="male_issues" style="width:100%; height:60px;" > <?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;" >
<strong>Name of Procedure : <input type="checkbox" class="Testicular-PRP-TPRP" name="procedures[]" value="Testicular PRP (TPRP)" <?php if(!empty($select_result['procedures']) && in_array('Testicular PRP (TPRP)',$procedures)){echo "checked";}?>>Testicular PRP (TPRP) <input type="checkbox" class="IUI" name="procedures[]" value="Testicular Stem Cell" <?php if(!empty($select_result['procedures']) && in_array('Testicular-Stem-Cell',$procedures)){echo "checked";}?>>Testicular Stem Cell</strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <strong>Date of procedure:  <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?> </strong>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;" >
<p><strong>Physical Examination: </strong></p>
<p><input type="checkbox" class="Conscious" name="Conscious[]" value="Conscious" <?php if(!empty($select_result['Conscious']) && in_array('Conscious', $physical2)){echo "checked";}?>>
   <label for="Condition">Conscious</label>
   <input type="checkbox" class="oriented" name="Conscious[]" value="oriented" <?php if(!empty($select_result['Conscious']) && in_array('oriented', $physical2)){echo "checked";}?>>
   <label for="Condition">oriented</label>  
</p>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;" >
<p><input type="checkbox" class="pallor" name="physical_examination[]" value="No pallor" <?php if(!empty($select_result['physical_examination']) && isset($select_result['physical_examination']) && in_array('No pallor', $physical)){echo "checked";}?>>
 <label for="Condition">No pallor</label>
  <input type="checkbox" class="icterus" name="physical_examination[]" value="icterus" <?php if(!empty($select_result['physical_examination']) && isset($select_result['physical_examination']) && in_array('icterus', $physical)){echo "checked";}?>>
 <label for="Condition">icterus</label>
  <input type="checkbox" class="cyanosis" name="physical_examination[]" value="cyanosis" <?php if(!empty($select_result['physical_examination']) && isset($select_result['physical_examination']) && in_array('cyanosis',$physical)){echo "checked";}?>>
 <label for="Condition">cyanosis</label>
<input type="checkbox" class="clubbing" name="physical_examination[]" value="digital clubbing" <?php if(!empty($select_result['physical_examination']) && in_array('digital clubbing',$physical)){echo "checked";}?>>
 <label for="Condition">digital clubbing</label>
<input type="checkbox" class="lymphadenopathy" name="physical_examination[]" value="lymphadenopathy" <?php if(!empty($select_result['physical_examination']) && in_array('lymphadenopathy',$physical)){echo "checked";}?>>
 <label for="Condition">lymphadenopathy</label>
 <input type="checkbox" class="oedema" name="physical_examination[]" value="pedal oedema" <?php if(!empty($select_result['physical_examination']) && in_array('pedal oedema',$physical)){echo "checked";}?>>
 <label for="Condition">pedal oedema</label> 
</p>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;" >
<strong>BP (mm Hg)</strong> <?php echo isset($select_result['Patient_BP'])?$select_result['Patient_BP']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <strong>PR (min) </strong><?php echo isset($select_result['Patient_PR'])?$select_result['Patient_PR']:""; ?> 
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;" >
<strong>RR (min)</strong> <?php echo isset($select_result['Patient_RR'])?$select_result['Patient_RR']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <strong>Temp (F) </strong><?php echo isset($select_result['Patient_Temp'])?$select_result['Patient_Temp']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;" >
<strong>SPO2 (on room air)</strong> <?php echo isset($select_result['Patient_SPO2'])?$select_result['Patient_SPO2']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <strong>CVS</strong><?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;" >
<strong>RS </strong> <?php echo isset($select_result['Patient_RS'])?$select_result['Patient_RS']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <strong>P/A</strong><?php echo isset($select_result['Patient_PA'])?$select_result['Patient_PA']:""; ?>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
 <strong>CNS</strong><?php echo isset($select_result['Patient_CNS'])?$select_result['Patient_CNS']:""; ?>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
 <strong>Course in the hospital:</strong><?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
 <strong>Condition at Discharge:</strong><?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?>
</td>
</tr>
</tbody>
</table>  
 
<table width="100%">
<tbody>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
 <h5 style="border: 1px solid #000; margin: 0;padding: 10px 10px;">POST OF INSTRUCTION</h5>
</td>
</tr>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Do fertility yoga daily</p>
</td>
</tr>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Avoid hot sauna bath</p>
</td>
</tr>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Consume Diet low in carbohydrate</p>
</td>
</tr>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Consume Diet rich in green leafy vegetables, beans,pulses ,high protein diet</p>
</td>
</tr>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Avoid/limit intake of tea and coffee/perfumes/cosmetics with fragrance</p>
</td>
</tr>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
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
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Review after 72 days</p>
</td>
</tr>

<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>There are No Substitutes</p>
</td>
</tr>

<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Medicine Advice1: <?php echo isset($select_result['Medicine_Advice1'])?$select_result['Medicine_Advice1']:""; ?></p>
</td>
</tr>

<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Medicine Advice2: <?php echo isset($select_result['Medicine_Advice2'])?$select_result['Medicine_Advice2']:""; ?></p>
</td>
</tr>

<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Medicine Advice3: <?php echo isset($select_result['Medicine_Advice3'])?$select_result['Medicine_Advice3']:""; ?></p>
</td>
</tr>

<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Medicine Advice4: <?php echo isset($select_result['Medicine_Advice4'])?$select_result['Medicine_Advice4']:""; ?></p>
</td>
</tr>

<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<ul>
<li>Continue thyroid /antihypertensive/ diabetes medications as have been taking previously.</li>
<li>To report in emergency of the hospital near by immediately if patient has abdominal pain/ vaginal bleeding/ fever /excessive cough /giddiness /vomiting/nausea/purulent discharge.</li>
<li>To take soft diet on the day of procedure. </li>
<li>To resume normal diet after one day of procedure.</li>
</ul>
</td>
</tr>

<tr>
<td width="70%" colspan="3" style="border:1px solid;padding:5px;">
<label for="BP"><b>Follow Up Advice:</b> Review with DR.</label>
<?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>

</td>

<td width="30%" colspan="1" style="border:1px solid;padding:5px;">
<label for="followup">on</label>
  <?php echo isset($select_result['advice'])?$select_result['advice']:""; ?>
</td>
</tr>

<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
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
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <p><strong>Sr Consultant  Urosurgeon</strong></p>
</td>
</tr>

<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <p><strong>Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</strong></p>
</td>
</tr>
</tbody>
</table>
</form>
</div>
</div>
<style>

input[type=checkbox], input[type=radio] {
    opacity: 1 !important;
    left: 0 !important;
    position: unset !important;
    margin: 9px !important;
}
input[type=text], textarea {
    width: 100%!important;
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
.nb56ty input {
    width: 100%;
}
.vb45rt td {text-align: left; padding-left: 10px;}
</style>
<style>
/* 🖨️ CSS प्रिंट मीडिया रूल: यह पक्का करता है कि प्रिंटर सिर्फ इस सेक्शन को देखे */
@media print {
    body * { 
        visibility: hidden; 
    }
    #print_this_section, #print_this_section * { 
        visibility: visible; 
    }
    #print_this_section { 
        position: absolute; 
        left: 0; 
        top: 0; 
        width: 100%; 
        display: block !important; 
    }
}
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