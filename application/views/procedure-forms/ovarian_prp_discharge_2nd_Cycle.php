<?php 
$all_method =& get_instance();
  if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        $wife_name  = $_POST['wife_name'] ?? '';
        $husband_name  = $_POST['husband_name'] ?? '';
        $wife_phone  = $_POST['wife_phone'] ?? '';
        $wife_age  = $_POST['wife_age'] ?? '';
        $wife_address  = $_POST['wife_address'] ?? '';
        $female_pregnancy_other_p  = $_POST['female_pregnancy_other_p'] ?? '';
        $female_pregnancy_other_l  = $_POST['female_pregnancy_other_l'] ?? '';
        $female_pregnancy_other_a  = $_POST['female_pregnancy_other_a'] ?? '';
        $details_management_advised  = $_POST['details_management_advised'] ?? '';
        $IVF_Consultant  = $_POST['IVF_Consultant'] ?? '';
        $center  = $_POST['center'] ?? '';
        
        // अनडिफाइंड वेरिएबल्स सेफ्टी नेट
        $further_referredfor_dellvery = $further_referredfor_dellvery ?? '';
        $outcome_of_pregnancy = $outcome_of_pregnancy ?? '';
        $malformation_in_newborn = $malformation_in_newborn ?? '';
      
        unset($_POST['wife_name'], $_POST['wife_phone'], $_POST['husband_name'], $_POST['wife_age'], $_POST['wife_address'], $_POST['female_pregnancy_other_p'], $_POST['female_pregnancy_other_l'], $_POST['female_pregnancy_other_a'], $_POST['details_management_advised']);
        
        // 💡 चेकबॉक्स एरे को सुरक्षित कोमा-सेपरेटेड स्ट्रिंग्स में बदलना ताकि addslashes क्रैश न हो
        foreach($_POST as $key => $value) {
            if (is_array($value)) {
                $_POST[$key] = implode(',', $value);
            }
        }
        
        // चेक करें कि क्या इस मरीज का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `ovarian_prp_discharge_summary` WHERE patient_id='$patient_id'";
        $select_result = run_select_query($sql);
         
        if(empty($select_result)){
            $query = "INSERT INTO `ovarian_prp_discharge_summary` SET ";
            $sqlArr = array();
           
            foreach($_POST as $key => $value) {
                $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
            
            // PCP NDT टेबल में एंट्री
            $query2 = "INSERT INTO `pcp_ndt` (patient_id, wife_name, husband_name, wife_phone, wife_age, wife_address, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised, IVF_Consultant, further_referredfor_dellvery, outcome_of_pregnancy, malformation_in_newborn, center, test_type, type, date) values 
            ('$patient_id','$wife_name', '$husband_name', '$wife_phone', '$wife_age', '$wife_address', 'P:$female_pregnancy_other_p', 'L:$female_pregnancy_other_l', 'A:$female_pregnancy_other_a', '$details_management_advised','$IVF_Consultant', '$further_referredfor_dellvery', '$outcome_of_pregnancy', '$malformation_in_newborn', '$center', 'REJUVENATION', 'REJUVENATION', '" . date('Y-m-d H:i:s') . "')";
            $result2 = run_form_query($query2);
            
            $result = run_form_query($query); 
        
            if($result){
                header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Discharge form saved successfully!').'&t='.base64_encode('success'));
                die();
            } else {
                header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
                die();
            }
        } else {
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Data is already saved and cannot be updated!').'&t='.base64_encode('error'));
            die();
        }
    }
    
    // ==============================================================================
    // 🔍 व्यू / प्रिंट मोड डेटा रेंडरिंग लॉजिक
    // ==============================================================================
    $sql = "SELECT * FROM `ovarian_prp_discharge_summary` WHERE patient_id='$patient_id'";
    $select_result = run_select_query($sql);
    
    $sql4 = "SELECT patient_id, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised FROM `hms_patient_medical_info` WHERE patient_id='$patient_id'";
    $select_result4 = run_select_query($sql4);
    
    // 💡 FIX: $this->config को $all_method->config से बदला ताकि 500 internal server error न आए
    $sql2 = "Select * from ".$all_method->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
    
    $sql3 = "Select * from ".$all_method->config->item('db_prefix')."centers where center_number='".($select_result2['appoitment_for'] ?? '')."'";
    $select_result3 = run_select_query($sql3);
    
    $sql5 = "Select * from ".$all_method->config->item('db_prefix')."doctors where ID='".($_SESSION['logged_doctor']['doctor_id'] ?? 0)."'";
    $select_result5 = run_select_query($sql5); 

    $sql_data = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $patient_data = run_select_query($sql_data);

    $center = $select_result['center'] ?? '';

    // Dynamic Logo handling
    $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
    if(empty($page_logo)) {
        $page_logo = base_url('assets/center/default-logo.png');
    }
?>
<?php 
    $physical = $applicablemedicine = $physical_name = array();
    
    if(!empty($select_result['physical_examination'])){
        $physical = explode(',', $select_result['physical_examination']);
    }
    if(!empty($select_result['applicablemedicine'])){
        $applicablemedicine = explode(',', $select_result['applicablemedicine']);
    }
    // Fixed typo: removed the $ sign from the key
    if(!empty($select_result['procedure_name'])){
        $physical_name = explode(',', $select_result['procedure_name']);
    }
?>

<div class="ga-pro">
<form action="" enctype='multipart/form-data' class="searchform" method="post">
  <input type="hidden" value="<?php echo $procedure_id ?? ''; ?>" class="form" name="procedure_id">
  <input type="hidden" value="<?php echo $updated_by ?? ''; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type ?? ''; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at ?? ''; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $patient_id;?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $select_result5['center_id'] ?? ''; ?>" class="form" name="center">
  <input type="hidden" value="<?php echo $patient_data['wife_name'] ?? ''; ?>" class="form" name="wife_name">
  <input type="hidden" value="<?php echo $patient_data['wife_phone'] ?? ''; ?>" class="form" name="wife_phone">
  <input type="hidden" value="<?php echo $patient_data['husband_name'] ?? ''; ?>" class="form" name="husband_name">
  <input type="hidden" value="<?php echo $receipt_number;?>" class="form" name="receipt_number">
  <input type="hidden" value="<?php echo $patient_data['wife_address'] ?? ''; ?>" class="form" name="wife_address">
  <input type="hidden" value="<?php echo $patient_data['wife_age'] ?? ''; ?>" class="form" name="wife_age">
  <input type="hidden" value="Second Cycle" name="type"> 
  <input type="hidden" value="<?php echo $select_result4['female_pregnancy_other_p'] ?? ''; ?>" class="form" name="female_pregnancy_other_p">
  <input type="hidden" value="<?php echo $select_result4['female_pregnancy_other_l'] ?? ''; ?>" class="form" name="female_pregnancy_other_l">
  <input type="hidden" value="<?php echo $select_result4['female_pregnancy_other_a'] ?? ''; ?>" class="form" name="female_pregnancy_other_a">
  <input type="hidden" value="<?php echo $select_result4['details_management_advised'] ?? ''; ?>" class="form" name="details_management_advised">
  <input type="hidden" value="<?php echo $_SESSION['logged_doctor']['doctor_id'] ?? '' ?>" class="form" name="doctor_id">
   <div class="container2 red-field form mt-5 mb-5">
        <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
           <tr>
               <td style="width:50%;padding:5px;" colspan="10">
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo">
                </td>
                <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">Ovum Pickup Discharge Summary</h3></td>
           </tr>
        </table>			 
<div class="col-sm-12 col-md-12">
<div class="col-sm-12 col-md-3" style="float: left; margin-bottom: 10px;">
  <label for="Admission">Date of Admission:</label>
  <input type="date" class="Admission" name="date_of_addmission" value="<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>">
</div>
<div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
  <label for="Admission">Admission Time:</label>
  <input type="time" class="Admission" name="time_of_addmission" value="<?php echo isset($select_result['time_of_addmission'])?$select_result['time_of_addmission']:""; ?>">
</div>    
<div class="col-sm-12 col-md-3" style="float: right; margin-bottom: 10px;">
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
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td colspan="3" width="50%">
<strong>Male Partner : <?php echo $patient_data['husband_name']; ?> </strong>
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
<strong>Name of Procedure : </strong>
<input type="checkbox" class="Ovarian" name="procedure_name[]" value="Ovarian PRP" <?php if(!empty($select_result['procedure_name']) && in_array('Ovarian PRP',$physical_name)){echo "checked";}?>>
<label for="Condition">Ovarian PRP</label>
<input type="checkbox" class="STEM" name="procedure_name[]" value="STEM CELL" <?php if(!empty($select_result['procedure_name']) && in_array('STEM CELL',$physical_name)){echo "checked";}?>>
<label for="Condition">STEM CELL</label>
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
<p>
 <input type="checkbox" class="pallor" name="physical_examination[]" value="No pallor" <?php if(!empty($select_result['physical_examination']) && in_array('No pallor',$physical)){echo "checked";}?>>
 <label for="Condition">No pallor</label>
  <input type="checkbox" class="icterus" name="physical_examination[]" value="icterus" <?php if(!empty($select_result['physical_examination']) && in_array('icterus',$physical)){echo "checked";}?>>
 <label for="Condition">icterus</label>
  <input type="checkbox" class="cyanosis" name="physical_examination[]" value="cyanosis" <?php if(!empty($select_result['physical_examination']) && in_array('cyanosis',$physical)){echo "checked";}?>>
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
 <label for="PR">RR</label>
  <input type="text" class="RR" name="Patient_RR" value="<?php echo isset($select_result['Patient_RR'])?$select_result['Patient_RR']:""; ?>"> / min <br>
 <label for="PR">Temp</label>
  <input type="text" class="Temp" name="Patient_Temp" value="<?php echo isset($select_result['Patient_Temp'])?$select_result['Patient_Temp']:""; ?>"> F<br>
 <label for="PR">SPO2</label>
  <input type="text" class="SPO2" name="Patient_SPO2" value="<?php echo isset($select_result['Patient_SPO2'])?$select_result['Patient_SPO2']:""; ?>"> on room air<br>
 <label for="CVS">CVS</label>
  <input type="text" class="CVS" name="Patient_CVS" value="<?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?>"><br>
 <label for="RS">RS</label>
  <input type="text" class="RS" name="Patient_RS" value="<?php echo isset($select_result['Patient_RS'])?$select_result['Patient_RS']:""; ?>"><br>
 <label for="P/A">P/A</label>
  <input type="text" class="PA" name="Patient_PA" value="<?php echo isset($select_result['Patient_PA'])?$select_result['Patient_PA']:""; ?>"><br>
 <label for="CNS">CNS</label>
  <input type="text" class="CNS" name="Patient_CNS" value="<?php echo isset($select_result['Patient_CNS'])?$select_result['Patient_CNS']:""; ?>"><br>
</div>  
</div>  

<div class="sec2">
 <label for="Course">Course in the hospital:</label>
  <input type="text" class="Course" name="Patient_Course" style="width: 100%;" value="<?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?>">
</div>  
<div class="sec2">
 <label for="Condition">Condition at Discharge:</label>
  <input type="text" class="Condition" name="Patient_Condition" style="width: 100%;" value="<?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?>">
    
</div>

<div class="sec3">
  <h5 style="border: 1px solid #000; margin: 0;padding: 10px 10px;">POST OP INSTRUCTION</h5>
  <table width="100%">
<tbody>
<tr>
<td width="100%">
<p>Regular exercise daily for one hour</p>
</td>
</tr>
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
</table>
<table width="585">
<tbody>
<tr>
<td width="38">
<p>Check</p>
</td>
<td width="117">
<p>Medication</p>
</td>
<td width="76">
<p>Dosage</p>
</td>
<td width="76">
<p>Route</p>
</td>
<td width="83">
<p>Times</p>
</td>
<td width="68">
<p>Timings</p>
</td>
<td width="71">
<p>When to start</p>
</td>
<td width="57">
<p>How many days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabCrocin" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabCrocin',$applicablemedicine)){echo "checked";}?>>

</td>
<td width="117">
<p>Tab Crocin</p>
</td>
<td width="76">
<p>500 mg</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong>Maximum three times at interval of 6 hrs (if Require )</strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS (if pain)</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Sypcremaffin"  <?php if(!empty($select_result['applicablemedicine']) && in_array('Sypcremaffin',$applicablemedicine)){echo "checked";}?>>

</td>
<td width="117">
<p>Sypcremaffin</p>
</td>
<td width="76">
<p>ONE TSF</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS</p>
</td>
<td width="68">
<p>After dinner</p>
</td>
<td width="71">
<p>SOS (if constipation)</p>
</td>
<td width="57"></td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="EndofertTab2MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('EndofertTab2MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Endofert Tab 2MG</p>
</td>
<td width="76">
<p>1 TAB</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>	<input type="checkbox" name="applicablemedicine[]" value="gufitwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufitwice',$applicablemedicine)){echo "checked";}?>>
	Twice 
	<input type="checkbox" name="applicablemedicine[]" value="gufithrice" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufithrice',$applicablemedicine)){echo "checked";}?>>
	thrice 
	<input type="checkbox" name="applicablemedicine[]" value="gufifour" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufifour',$applicablemedicine)){echo "checked";}?>>
	four times daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>14 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabEcosprin75mg"  <?php if(!empty($select_result['applicablemedicine']) && in_array('TabEcosprin75mg',$applicablemedicine)){echo "checked";}?>>

</td>
<td width="117">
<p>Tab Ecosprin 75 mg</p>
</td>
<td width="76">
<p>1TAB</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p><input type="checkbox" name="applicablemedicine[]" value="eco75once" <?php if(!empty($select_result['applicablemedicine']) && in_array('eco75once',$applicablemedicine)){echo "checked";}?>>
	once
	<input type="checkbox" name="applicablemedicine[]" value="eco75twice" <?php if(!empty($select_result['applicablemedicine']) && in_array('eco75twice',$applicablemedicine)){echo "checked";}?>>
	twice</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>Tomorrow</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone5mg" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone5mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Tab Wysolone 5mg</p>
</td>
<td width="76">
<p>5mg for --- days followed by</p>
</td>
<td width="76">
<p>oral</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>Tomorrow</p>
</td>
<td width="57">
<p>----------</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Tab Wysolone 10mg</p>
</td>
<td width="76">
<p>10mg for---days followed by</p>
</td>
<td width="76">
<p>oral</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>Tomorrow</p>
</td>
<td width="57">
<p>----------</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone15mg" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone15mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Tab Wysolone 15mg</p>
</td>
<td width="76">
<p>15mg for---days</p>
</td>
<td width="76">
<p>oral</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>Tomorrow</p>
</td>
<td width="57">
<p>----------</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilL" <?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilL',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Biophil L</p>
</td>
<td width="76">
<p>1 CAP</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilO" <?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilO',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Biophil O</p>
</td>
<td width="76">
<p>1 CAP</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilQ3" <?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilQ3',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Biophil Q3</p>
</td>
<td width="76">
<p>1 CAP</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BIOLARG" <?php if(!empty($select_result['applicablemedicine']) && in_array('BIOLARG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>BIOLARG</p>
</td>
<td width="76">
<p>1 SACHET</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BIOPHILVITA" <?php if(!empty($select_result['applicablemedicine']) && in_array('BIOPHILVITA',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>BIOPHIL VITA</p>
</td>
<td width="76">
<p>1 cap</p>
</td>
<td width="76">
<p>oral</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabShelcal500mg" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabShelcal500mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Tab Shelcal 500 mg</p>
</td>
<td width="76">
<p>1TAB</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="INFAGESTRONSR200" <?php if(!empty($select_result['applicablemedicine']) && in_array('INFAGESTRONSR200',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>INFAGESTRON SR 200</p>
</td>
<td width="76">
<p>400mg</p>  
</td>
<td width="76">
<p>Oral/vaginally</p>
</td>
<td width="83">
<p>	<input type="checkbox" name="applicablemedicine[]" value="genonce" <?php if(!empty($select_result['applicablemedicine']) && in_array('genonce',$applicablemedicine)){echo "checked";}?>>
	Once
	<input type="checkbox" name="applicablemedicine[]" value="gentwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('gentwice',$applicablemedicine)){echo "checked";}?>>
	twice
	<input type="checkbox" name="applicablemedicine[]" value="genthree" <?php if(!empty($select_result['applicablemedicine']) && in_array('genthree',$applicablemedicine)){echo "checked";}?>>
	three
	<input type="checkbox" name="applicablemedicine[]" value="genthrice" <?php if(!empty($select_result['applicablemedicine']) && in_array('genthrice',$applicablemedicine)){echo "checked";}?>>
	four times daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Estogel" <?php if(!empty($select_result['applicablemedicine']) && in_array('Estogel',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Estogel</p>
</td>
<td width="76">
<p>2.5 gm</p>  
</td>
<td width="76">
<p>Locally</p>
</td>
<td width="83">
<p>
	<input type="checkbox" name="applicablemedicine[]" value="estoonce" <?php if(!empty($select_result['applicablemedicine']) && in_array('estoonce',$applicablemedicine)){echo "checked";}?>>
	Once
	<input type="checkbox" name="applicablemedicine[]" value="estotwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('estotwice',$applicablemedicine)){echo "checked";}?>>
	twice 
	<input type="checkbox" name="applicablemedicine[]" value="estothrice" <?php if(!empty($select_result['applicablemedicine']) && in_array('estothrice',$applicablemedicine)){echo "checked";}?>>
	thrice 
	<input type="checkbox" name="applicablemedicine[]" value="estofour" <?php if(!empty($select_result['applicablemedicine']) && in_array('estofour',$applicablemedicine)){echo "checked";}?>>
	four  times to be applied locally daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Lenzettospray" <?php if(!empty($select_result['applicablemedicine']) && in_array('Lenzettospray',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Lenzetto Spray</p>
</td>
<td width="76">
<p>1 spray</p>    
</td>
<td width="76">
<p>Locally</p>
</td>
<td width="83">
<p>
	<input type="checkbox" name="applicablemedicine[]" value="lenonce" <?php if(!empty($select_result['applicablemedicine']) && in_array('lenonce',$applicablemedicine)){echo "checked";}?>>
	Once
	<input type="checkbox" name="applicablemedicine[]" value="lentwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('lentwice',$applicablemedicine)){echo "checked";}?>>
	twice 
	times to be applied</p>
</td>
<td width="68">
<p></p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Luprorin4MGInj" <?php if(!empty($select_result['applicablemedicine']) && in_array('Luprorin4MGInj',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Luprorin 4MG Inj</p>
</td>
<td width="76">
<p>
	<input type="checkbox" name="applicablemedicine[]" value="euro1ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('euro1ml',$applicablemedicine)){echo "checked";}?>>
	1 ML
	<input type="checkbox" name="applicablemedicine[]" value="euro2ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('euro2ml',$applicablemedicine)){echo "checked";}?>>
	2 ML
	<input type="checkbox" name="applicablemedicine[]" value="euro3ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('euro3ml',$applicablemedicine)){echo "checked";}?>>
	3 ML 
	<input type="checkbox" name="applicablemedicine[]" value="euro4ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('euro4ml',$applicablemedicine)){echo "checked";}?>>
	4 ML</p>   
</td>
<td width="76">
<p>Subcutaneous</p>
</td>
<td width="83">
<p>
	<input type="checkbox" name="applicablemedicine[]" value="eurodaily" <?php if(!empty($select_result['applicablemedicine']) && in_array('eurodaily',$applicablemedicine)){echo "checked";}?>>
	Daily
	<input type="checkbox" name="applicablemedicine[]" value="euroalternate day" <?php if(!empty($select_result['applicablemedicine']) && in_array('euroalternate',$applicablemedicine)){echo "checked";}?>>
	alternate day
	<input type="checkbox" name="applicablemedicine[]" value="eurobiweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('eurobiweekly',$applicablemedicine)){echo "checked";}?>>
	biweekly 
	<input type="checkbox" name="applicablemedicine[]" value="euroweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('euroweekly',$applicablemedicine)){echo "checked";}?>>
	weekly</p>
</td>
<td width="68">
<p></p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapCalcitasD3" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapCalcitasD3',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Cap Calcitas D3</p>
</td>
<td width="76">
<p>60000IU</p>
</td>
<td width="76">
<p>oral</p>
</td>
<td width="83">
<p>weekly</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CEROXITUM500" <?php if(!empty($select_result['applicablemedicine']) && in_array('CEROXITUM500',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>CEROXITUM 500</p>
</td>
<td width="76">
<p>500MG</p>
</td>
<td width="76">
<p>1 Tab</p>
</td>
<td width="83">
<p>Twice Daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>3 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Meprate10mgTab" <?php if(!empty($select_result['applicablemedicine']) && in_array('Meprate10mgTab',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Meprate 10mg Tab</p>
</td>
<td width="76">
<p>10 MG</p>
</td>
<td width="76">
<p>Tab</p>
</td>
<td width="83">
<p>Twice Daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>14 Days</p>
</td>
</tr>
<?php
$medicine = 'Tab.Ceftum (500 mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p>500 mg</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS </p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Pantoprazole (40 mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab Crocin (500 mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS </p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Biophil DHEA 75';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS )</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Biophil L';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'BIOLARG sachet';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Evion (400 mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p>7500 mg</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Vit D3 (60000 IU)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p>10 mg</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap BIOPHIL VITA';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Biophil Q';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Biophil O3';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab Shelcal (500 mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab.estrabet (2mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab.meprate (10mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab.estrade (2mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'TabMontairLC';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'TabAllegra';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Injcoriosurge10000';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'IPARIN40MG';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Aquagest25MG';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Genprogel';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'INFAGEST10MG';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>SOS
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>SOS</p>
</td>
<td width="57">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Biophil DHEA';
?>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" <?php if(!empty($select_result['applicablemedicine']) && in_array($medicine,$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p>1 Cap</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>Once daily
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<?php
$medicine = 'Women Fertility Tea';
?>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" <?php if(!empty($select_result['applicablemedicine']) && in_array($medicine,$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>Once daily
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<tr>
<td width="12.5%">
<p>There are No Substitutes</p>
</td>
</tr>
</tbody>
</table>
<table>
<tr>
<td>
<div class="nb56ty">
  <label for="other">Other Medication1:</label>
  <input type="text" class="other1" name="Other_Medication1" value="<?php echo isset($select_result['Other_Medication1'])?$select_result['Other_Medication1']:""; ?>"><br> 
    <label for="other">Other Medication2:</label>
  <input type="text" class="other2" name="Other_Medication2" value="<?php echo isset($select_result['Other_Medication2'])?$select_result['Other_Medication2']:""; ?>"><br> 
   <label for="other">Other Medication3:</label>
  <input type="text" class="other3" name="Other_Medication3" value="<?php echo isset($select_result['Other_Medication3'])?$select_result['Other_Medication3']:""; ?>"><br>  
    <label for="other">Other Medication4:</label>
  <input type="text" class="other4" name="Other_Medication4" value="<?php echo isset($select_result['Other_Medication4'])?$select_result['Other_Medication4']:""; ?>"><br>  
</div> 
 </td> 
</tr>  
<tr>
<td width="100%">
<p>Inform on Day one of next cycle</p>
</td>

</tr>
<tr>
<td width="100%">
<p>Continue thyroid/antihypertensive/diabetes /other medical disorder medications as advised</p>
</td>
</tr>
<tr>
<td width="100%">
<p>To report if giddiness /nausea/vomiting/vaginal bleeding/pain/fever /purulent discharge immediately</p>
</td>
</tr>
</tbody>
</table>

</div>

<div class="sec2">
<ul>
<li>Continue thyroid /antihypertensive/ diabetes medications as have been taking previously.</li>
<li>To report in emergency of the hospital near by immediately if patient has abdominal pain/ vaginal bleeding/ fever /excessive cough /giddiness /vomiting/nausea/purulent discharge.</li>
<li>To take soft diet on the day of procedure.</li>
<li>To resume normal diet after one day of procedure.</li>
</ul>
</div> 



<div class="sec2" style="display: flex; padding-top: 5px;">
 <label for="BP"><b>Follow Up Advice:</b> Review with DR.</label>
  <input type="text" class="followup" name="Doctor_name" value="<?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>"> 
 
  <label for="followup">on Day 1 of next period</label>
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
 <label for="Sr IVF Consultant">Sr IVF Consultant</label>
 <input type="text" class="IVFConsultant" name="" value="<?php echo $select_result['IVF_Consultant']; ?>" readonly>
  <input type="hidden" class="IVFConsultant" name="IVF_Consultant" value="<?php echo $select_result5['name'];?>" readonly>
</div>
<div class="sec2">
  
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
    
</div> 
<input type="submit" name="submit" value="submit">
</form>

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">
  
<div class="printtable prtable" id="printtable" style="display:none;"> 
    
<div class="ga-pro">
<form action="" enctype='multipart/form-data' method="post">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">Ovarian Prp</h3></td>
</tr>
</table>
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="2" width="25%" style="border:1px solid;padding:5px;">
<strong>Date of Admission: <?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?></strong>
</td>
<td colspan="1" width="25%" style="border:1px solid;padding:5px;">
<strong>Admission Time: <?php echo isset($select_result['time_of_addmission'])?$select_result['time_of_addmission']:""; ?></strong>
</td>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of Discharge:: <?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?></strong>
</td>
<td colspan="1" width="25%" style="border:1px solid;padding:5px;">
<strong>Discharge Time: <?php echo isset($select_result['time_of_discharge'])?$select_result['time_of_discharge']:""; ?></strong>
</td>
</tr>

<tr style="background: #b3b9b7;">
    <td colspan="3" width="100%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	<td colspan="3" width="100%" style="border:1px solid;padding:5px;">
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
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>Male Partner : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?></strong>
</td>
</tr>

<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>Provisional Diagnosis:
 <textarea name="female_issues" style="width:100%; height:60px;" > <?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?> </textarea>
</strong>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>Final Diagnosis: 
 <textarea name="male_issues" style="width:100%; height:60px;" > <?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>Name of Procedure : </strong>
<input type="checkbox" class="Ovarian" name="procedure_name[]" value="Ovarian PRP" <?php if(!empty($select_result['procedure_name']) && in_array('Ovarian PRP',$physical_name)){echo "checked";}?>>
<label for="Condition">Ovarian PRP</label>
<input type="checkbox" class="STEM" name="procedure_name[]" value="STEM CELL" <?php if(!empty($select_result['procedure_name']) && in_array('STEM CELL',$physical_name)){echo "checked";}?>>
<label for="Condition">STEM CELL</label>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>Date of procedure:  <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>  </strong>
</td>
</tr>

<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<p><strong>Physical Examination: </strong></p>
<p><input type="radio" id="Conscious" name="Conscious" value="Conscious" <?php if(isset($select_result['Conscious'])  && $select_result['Conscious']== "Conscious"){ echo "checked";} ?>>
  <label for="Conscious">Conscious</label><br>
  <input type="radio" id="oriented" name="Conscious" value="oriented" <?php if(isset($select_result['Conscious']) && $select_result['Conscious'] == "oriented"){ echo "checked";} ?>>
  <label for="oriented">oriented</label><br>  
</p>
</td>
</tr>

<tr>
<td width="100%" colspan="6" style="border:1px solid;padding:5px;">
<p>
 <input type="checkbox" class="pallor" name="physical_examination[]" value="No pallor" <?php if(!empty($select_result['physical_examination']) && in_array('No pallor',$physical)){echo "checked";}?>>
 <label for="Condition">No pallor</label>
  <input type="checkbox" class="icterus" name="physical_examination[]" value="icterus" <?php if(!empty($select_result['physical_examination']) && in_array('icterus',$physical)){echo "checked";}?>>
 <label for="Condition">icterus</label>
  <input type="checkbox" class="cyanosis" name="physical_examination[]" value="cyanosis" <?php if(!empty($select_result['physical_examination']) && in_array('cyanosis',$physical)){echo "checked";}?>>
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
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>BP (mm Hg): <?php echo isset($select_result['Patient_BP'])?$select_result['Patient_BP']:""; ?></strong>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>PR (min):  <?php echo isset($select_result['Patient_PR'])?$select_result['Patient_PR']:""; ?> </strong>
</td>
</tr>

<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>RR (min): <?php echo isset($select_result['Patient_RR'])?$select_result['Patient_RR']:""; ?></strong>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>Temp (F):  <?php echo isset($select_result['Patient_Temp'])?$select_result['Patient_Temp']:""; ?></strong>
</td>
</tr>

<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>SPO2: <?php echo isset($select_result['Patient_SPO2'])?$select_result['Patient_SPO2']:""; ?></strong>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>CVS :  <?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?></strong>
</td>
</tr>

<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>RS: <?php echo isset($select_result['Patient_RS'])?$select_result['Patient_RS']:""; ?></strong>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<strong>P/A :  <?php echo isset($select_result['Patient_PA'])?$select_result['Patient_PA']:""; ?></strong>
</td>
</tr>

<tr>
<td width="100%" colspan="6" style="border:1px solid;padding:5px;">
<strong>CNS :  <?php echo isset($select_result['Patient_CNS'])?$select_result['Patient_CNS']:""; ?></strong>
</td>
</tr>

<tr>
<td width="100%" colspan="6" style="border:1px solid;padding:5px;">
<strong>Course in the hospital:<?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?></strong>
</td>
</tr>

<tr>
<td width="100%" colspan="6" style="border:1px solid;padding:5px;">
<strong>Condition at Discharge: <?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 

<div class="sec3">
  <h5 style="border: 1px solid #000; margin: 0;padding: 10px 10px;">POST OP INSTRUCTION</h5>
  <table width="100%">
<tbody>
<tr>
<td width="100%;">
<p>Regular exercise daily for one hour</p>
</td>
</tr>
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
</table>
<table width="100%">
<tbody>
<tr>
<td colspan="8" style="border:1px solid;padding:5px;" ><h4>ADVICE ON DISCHARGE</h4> </td>
</tr>
<tr>
<td width="100" colspan="1" style="border:1px solid;padding:5px;">
<p>Check</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Medication</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Dosage</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Route</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Times</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Timings</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>When to start</p>
</td>
<td width="100" colspan="1" style="border:1px solid;padding:5px;">
<p>How many days</p>
</td>
</tr>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabCrocin',$applicablemedicine)){ ?>
<tr>
<td width="100" colspan="1" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabCrocin" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabCrocin',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Crocin</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>500 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>SOS
<strong>Maximum three times at interval of 6 hrs (if Require )</strong></p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>SOS (if pain)</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Sypcremaffin',$applicablemedicine)){ ?>
<tr>
<td width="100" colspan="1" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Sypcremaffin"  <?php if(!empty($select_result['applicablemedicine']) && in_array('Sypcremaffin',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Sypcremaffin</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>ONE TSF</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After dinner</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>SOS (if constipation)</p>
</td>
<td width="100" style="border:1px solid;padding:5px;"></td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('EndofertTab2MG',$applicablemedicine)){ ?>
<tr>
<td  width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="EndofertTab2MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('EndofertTab2MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Endofert Tab 2MG</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1TAB</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>	<input type="checkbox" name="applicablemedicine[]" value="gufitwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufitwice',$applicablemedicine)){echo "checked";}?>>
	Twice 
	<input type="checkbox" name="applicablemedicine[]" value="gufithrice" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufithrice',$applicablemedicine)){echo "checked";}?>>
	thrice 
	<input type="checkbox" name="applicablemedicine[]" value="gufifour" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufifour',$applicablemedicine)){echo "checked";}?>>
	four times daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>14 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabEcosprin75mg',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabEcosprin75mg"  <?php if(!empty($select_result['applicablemedicine']) && in_array('TabEcosprin75mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Ecosprin 75 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1TAB</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p><input type="checkbox" name="applicablemedicine[]" value="eco75once" <?php if(!empty($select_result['applicablemedicine']) && in_array('eco75once',$applicablemedicine)){echo "checked";}?>>
	once
	<input type="checkbox" name="applicablemedicine[]" value="eco75twice" <?php if(!empty($select_result['applicablemedicine']) && in_array('eco75twice',$applicablemedicine)){echo "checked";}?>>
	twice</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tomorrow</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone5mg',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone5mg" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone5mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Wysolone 5mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>5mg for --- days followed by</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tomorrow</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>----------</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Wysolone 10mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>10mg for---days followed by</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tomorrow</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>----------</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone15mg',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone15mg" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone15mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Wysolone 15mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>15mg for---days</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tomorrow</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>----------</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilL',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilL" <?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilL',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Biophil L</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 CAP</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilO',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilO" <?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilO',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Biophil O</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 CAP</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilQ3',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilQ3" <?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilQ3',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Biophil Q3</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 CAP</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('BIOLARG',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BIOLARG" <?php if(!empty($select_result['applicablemedicine']) && in_array('BIOLARG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>BIOLARG</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 SACHET</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('BIOPHILVITA',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BIOPHILVITA" <?php if(!empty($select_result['applicablemedicine']) && in_array('BIOPHILVITA',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>BIOPHIL VITA</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 cap</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabShelcal500mg',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabShelcal500mg" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabShelcal500mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Shelcal 500 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1TAB</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('INFAGESTRONSR200',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="INFAGESTRONSR200" <?php if(!empty($select_result['applicablemedicine']) && in_array('INFAGESTRONSR200',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>INFAGESTRON SR 200</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>200mg</p>  
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral/vaginally</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>	<input type="checkbox" name="applicablemedicine[]" value="genonce" <?php if(!empty($select_result['applicablemedicine']) && in_array('genonce',$applicablemedicine)){echo "checked";}?>>
	Once
	<input type="checkbox" name="applicablemedicine[]" value="gentwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('gentwice',$applicablemedicine)){echo "checked";}?>>
	twice
	<input type="checkbox" name="applicablemedicine[]" value="genthree" <?php if(!empty($select_result['applicablemedicine']) && in_array('genthree',$applicablemedicine)){echo "checked";}?>>
	three
	<input type="checkbox" name="applicablemedicine[]" value="genthrice" <?php if(!empty($select_result['applicablemedicine']) && in_array('genthrice',$applicablemedicine)){echo "checked";}?>>
	four times daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr><?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Estogel',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Estogel" <?php if(!empty($select_result['applicablemedicine']) && in_array('Estogel',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Estogel</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>2.5 gm</p>  
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Locally</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>
	<input type="checkbox" name="applicablemedicine[]" value="estoonce" <?php if(!empty($select_result['applicablemedicine']) && in_array('estoonce',$applicablemedicine)){echo "checked";}?>>
	Once
	<input type="checkbox" name="applicablemedicine[]" value="estotwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('estotwice',$applicablemedicine)){echo "checked";}?>>
	twice 
	<input type="checkbox" name="applicablemedicine[]" value="estothrice" <?php if(!empty($select_result['applicablemedicine']) && in_array('estothrice',$applicablemedicine)){echo "checked";}?>>
	thrice 
	<input type="checkbox" name="applicablemedicine[]" value="estofour" <?php if(!empty($select_result['applicablemedicine']) && in_array('estofour',$applicablemedicine)){echo "checked";}?>>
	four  times to be applied locally daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Lenzettospray',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Lenzettospray" <?php if(!empty($select_result['applicablemedicine']) && in_array('Lenzettospray',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Lenzetto Spray</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 spray</p>    
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Locally</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>
	<input type="checkbox" name="applicablemedicine[]" value="lenonce" <?php if(!empty($select_result['applicablemedicine']) && in_array('lenonce',$applicablemedicine)){echo "checked";}?>>
	Once
	<input type="checkbox" name="applicablemedicine[]" value="lentwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('lentwice',$applicablemedicine)){echo "checked";}?>>
	twice 
	times to be applied</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Luprorin4MGInj',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Luprorin4MGInj" <?php if(!empty($select_result['applicablemedicine']) && in_array('Luprorin4MGInj',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Luprorin 4MG Inj</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>
	<input type="checkbox" name="applicablemedicine[]" value="euro1ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('euro1ml',$applicablemedicine)){echo "checked";}?>>
	1 ML
	<input type="checkbox" name="applicablemedicine[]" value="euro2ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('euro2ml',$applicablemedicine)){echo "checked";}?>>
	2 ML
	<input type="checkbox" name="applicablemedicine[]" value="euro3ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('euro3ml',$applicablemedicine)){echo "checked";}?>>
	3 ML 
	<input type="checkbox" name="applicablemedicine[]" value="euro4ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('euro4ml',$applicablemedicine)){echo "checked";}?>>
	4 ML</p>    
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Subcutaneous</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>
	<input type="checkbox" name="applicablemedicine[]" value="eurodaily" <?php if(!empty($select_result['applicablemedicine']) && in_array('eurodaily',$applicablemedicine)){echo "checked";}?>>
	Daily
	<input type="checkbox" name="applicablemedicine[]" value="euroalternate day" <?php if(!empty($select_result['applicablemedicine']) && in_array('euroalternate',$applicablemedicine)){echo "checked";}?>>
	alternate day
	<input type="checkbox" name="applicablemedicine[]" value="eurobiweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('eurobiweekly',$applicablemedicine)){echo "checked";}?>>
	biweekly 
	<input type="checkbox" name="applicablemedicine[]" value="euroweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('euroweekly',$applicablemedicine)){echo "checked";}?>>
	weekly</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('CapCalcitasD3',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapCalcitasD3" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapCalcitasD3',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Cap Calcitas D3</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>60000IU</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>weekly</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('CEROXITUM500',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CEROXITUM500" <?php if(!empty($select_result['applicablemedicine']) && in_array('CEROXITUM500',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>CEROXITUM 500</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>500MG</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 Tab</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Twice Daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>3 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Meprate10mgTab',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Meprate10mgTab" <?php if(!empty($select_result['applicablemedicine']) && in_array('Meprate10mgTab',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Meprate 10mg Tab</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>10 MG</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Twice  Daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>14 Days</p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab.Ceftum (500 mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>500 mg</p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS </p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Pantoprazole (40 mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab Crocin (500 mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76"style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76"style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS </p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Biophil DHEA 75';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83"style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71"style="border:1px solid;padding:5px;">
<p>SOS )</p>
</td>
<td width="57"style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Biophil L';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117"style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71"style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'BIOLARG sachet';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83"style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Evion (400 mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>7500 mg</p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Vit D3 (60000 IU)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>10 mg</p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68"style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap BIOPHIL VITA';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Biophil Q';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;" >
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Cap Biophil O3';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab Shelcal (500 mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab.estrabet (2mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab.meprate (10mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Tab.estrade (2mg)';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'TabMontairLC';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57"style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'TabAllegra';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117"style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76"style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83"style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71"style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Injcoriosurge10000';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68"style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'IPARIN40MG';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Aquagest25MG';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Genprogel';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'INFAGEST10MG';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>SOS
<strong></strong></p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Biophil DHEA';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p>1 Cap</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>Once daily
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'Women Fertility Tea';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td>
    <input type="checkbox" class="checkmedicine" name="" value="<?= $medicine ?>" checked> 
</td>
<td width="117">
<p><?= $medicine ?></p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>Once daily
<strong></strong></p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<table>
<tr>
<td>
<div class="nb56ty">
  <label for="other">Other Medication1:</label>
  <?php echo isset($select_result['Other_Medication1'])?$select_result['Other_Medication1']:""; ?><br> 
   </div> 
 </td> 
</tr> 
<tr>
<td>
<div class="nb56ty">
    <label for="other">Other Medication2:</label>
  <?php echo isset($select_result['Other_Medication2'])?$select_result['Other_Medication2']:""; ?><br> 
  </div> 
 </td> 
</tr> 
<tr>
<td>
<div class="nb56ty">
  <label for="other">Other Medication3:</label>
  <?php echo isset($select_result['Other_Medication3'])?$select_result['Other_Medication3']:""; ?><br>  
  </div> 
 </td> 
</tr>  
<tr>
<td>
<div class="nb56ty">
 <label for="other">Other Medication4:</label>
  <?php echo isset($select_result['Other_Medication4'])?$select_result['Other_Medication4']:""; ?><br>  
</div> 
 </td> 
</tr> 
<tr>
<td width="100%">
<p>Inform on Day one of next cycle</p>
</td>

</tr>
<tr>
<td width="100%">
<p>Continue thyroid/antihypertensive/diabetes /other medical disorder medications as advised</p>
</td>
</tr>
<tr>
<td width="100%">
<p>To report if giddiness /nausea/vomiting/vaginal bleeding/pain/fever /purulent discharge immediately</p>
</td>
</tr>
</tbody>
</table>

</div>

<div class="sec2">
<ul>
<li>Continue thyroid /antihypertensive/ diabetes medications as have been taking previously.</li>
<li>To report in emergency of the hospital near by immediately if patient has abdominal pain/ vaginal bleeding/ fever /excessive cough /giddiness /vomiting/nausea/purulent discharge.</li>
<li>To take soft diet on the day of procedure.</li>
<li>To resume normal diet after one day of procedure.</li>
</ul>
</div> 



<div class="sec2" style="display: flex; padding-top: 5px;">
 <label for="BP"><b>Follow Up Advice:</b> Review with DR.</label>
  <input type="text" class="followup" name="Doctor_name" value="<?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>"> 
 
  <label for="followup">on Day 1 of next period</label>
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
 <label for="Sr IVF Consultant">Sr IVF Consultant</label>
  <?php echo isset($select_result['IVF_Consultant'])?$select_result['IVF_Consultant']:""; ?>
</div>
<div class="sec2">
  
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
    
</div> 
</form>
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
        newWin.document.write('<html><head><title>Print Discharge Summary</title><style>table{width:100%;border-collapse:collapse;} td,th{border:1px solid #000;padding:5px;text-align:left;}</style></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();
      
        setTimeout(function(){
            newWin.close();
            $('.searchform').show();
            $('.prtable').css('display', 'none');
        }, 500); 
    });
});
</script>  