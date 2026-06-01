<?php 
$all_method =& get_instance();

    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
       
        $wife_name  = $_POST['wife_name'];
        $husband_name  = $_POST['husband_name'];
        $wife_phone  = $_POST['wife_phone'];
        $wife_age  = $_POST['wife_age'];
        // $female_issues  = $_POST['female_issues'];
        $wife_address  = $_POST['wife_address'];
        $female_pregnancy_other_p  = $_POST['female_pregnancy_other_p'];
        $female_pregnancy_other_l  = $_POST['female_pregnancy_other_l'];
        $female_pregnancy_other_a  = $_POST['female_pregnancy_other_a'];
        $details_management_advised  = $_POST['details_management_advised'];
        $IVF_Consultant  = $_POST['IVF_Consultant'];
        $center  = $_POST['center'];
      
        unset($_POST['wife_name']);
        unset($_POST['wife_phone']);
        unset($_POST['husband_name']);
        unset($_POST['wife_age']);
        //unset($_POST['female_issues']);
        unset($_POST['wife_address']);
        unset($_POST['female_pregnancy_other_p']);
        unset($_POST['female_pregnancy_other_l']);
        unset($_POST['female_pregnancy_other_a']);
        unset($_POST['details_management_advised']);
        //unset($_POST['IVF_Consultant']);
             
        if(!empty($_POST['Conscious']) && isset($_POST['Conscious'])){
            $_POST['Conscious'] = implode(',', $_POST['Conscious']);
        }
        if(!empty($_POST['applicablemedicine']) && isset($_POST['applicablemedicine'])){
             $_POST['applicablemedicine'] = implode(',', $_POST['applicablemedicine']);
        }
        if(!empty($_POST['physical_examination']) && isset($_POST['physical_examination'])){
            $_POST['physical_examination'] = implode(',', $_POST['physical_examination']);
        }
        
        // चेक करें कि क्या इस iic_id का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `iui_discharge_summary` WHERE iic_id='$iic_id'";
        $select_result = run_select_query($sql);
        
        // अगर डेटा मौजूद नहीं है, सिर्फ तभी INSERT करें
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `iui_discharge_summary` SET ";
            $sqlArr = array();
            foreach( $_POST as $key => $value ) {
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
            
            // Insert into pcp_ndt table
            // Note: $female_issues, $further_referredfor_dellvery, $outcome_of_pregnancy, $malformation_in_newborn variables should be defined before using here.
            $query2 = "INSERT INTO `pcp_ndt` (iic_id, wife_name, husband_name, wife_phone, wife_age, female_issues, wife_address, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised, IVF_Consultant, further_referredfor_dellvery, outcome_of_pregnancy, malformation_in_newborn, center, test_type, type, date) values 
           ('$iic_id','$wife_name', '$husband_name', '$wife_phone', '$wife_age', '$female_issues', '$wife_address', 'P:$female_pregnancy_other_p', 'L:$female_pregnancy_other_l', 'A:$female_pregnancy_other_a', '$details_management_advised', '$IVF_Consultant', '$further_referredfor_dellvery', '$outcome_of_pregnancy', '$malformation_in_newborn', '$center', 'IUI','IUI','" . date('Y-m-d H:i:s') . "')";
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
            // अगर डेटा पहले से मौजूद है, तो UPDATE ना करें, बल्कि सीधा एरर मैसेज के साथ रिडायरेक्ट करें
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Data is already saved and cannot be updated!').'&t='.base64_encode('error'));
            die();
        }
    }
    
    // php code to Insert data into mysql database from input text (submit2)
    if(isset($_POST['submit2'])){
        unset($_POST['submit2']);
    
      $wife_name  = $_POST['wife_name'];
      $husband_name  = $_POST['husband_name'];
      $wife_phone  = $_POST['wife_phone'];
      $wife_age  = $_POST['wife_age'];
      $wife_address  = $_POST['wife_address'];
      $female_pregnancy_other_p  = $_POST['female_pregnancy_other_p'];
      $female_pregnancy_other_l  = $_POST['female_pregnancy_other_l'];
      $female_pregnancy_other_a  = $_POST['female_pregnancy_other_a'];
      $details_management_advised  = $_POST['details_management_advised'];
      $IVF_Consultant  = $_POST['IVF_Consultant'];
      $center  = $_POST['center'];
      
      unset($_POST['wife_name']);
      unset($_POST['wife_phone']);
      unset($_POST['husband_name']);
      unset($_POST['wife_age']);
      unset($_POST['wife_address']);
      unset($_POST['female_pregnancy_other_p']);
      unset($_POST['female_pregnancy_other_l']);
      unset($_POST['female_pregnancy_other_a']);
      unset($_POST['details_management_advised']);
      unset($_POST['center']);
       
            $query2 = "INSERT INTO `pcp_ndt` (iic_id, wife_name, husband_name, wife_phone, wife_age, wife_address, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised, IVF_Consultant, further_referredfor_dellvery, outcome_of_pregnancy, malformation_in_newborn, center, test_type, type, date) values 
           ('$iic_id','$wife_name', '$husband_name', '$wife_phone', '$wife_age', '$wife_address', 'P:$female_pregnancy_other_p', 'L:$female_pregnancy_other_l', 'A:$female_pregnancy_other_a', '$details_management_advised','$IVF_Consultant', '$further_referredfor_dellvery', '$outcome_of_pregnancy', '$malformation_in_newborn', '$center', 'IUI','IUI','" . date('Y-m-d H:i:s') . "')";
            $result = run_form_query($query2); 
           if($result){
         header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Pcp Ndt inserted!').'&t='.base64_encode('success'));
            die();
        }else{
          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
          die();
        }
    }
    
    // फॉर्म में डेटा दिखाने के लिए सिर्फ iic_id से फेच करें
    $sql = "SELECT * FROM `iui_discharge_summary` WHERE patient_id='$patient_id'";
    $select_result = run_select_query($sql);
    
    $sql4 = "SELECT patient_id, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised FROM `hms_patient_medical_info` WHERE patient_id=$patient_id";
    $select_result4 = run_select_query($sql4);
    
    $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
    
    $sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result2['appoitment_for']."'";
    $select_result3 = run_select_query($sql3);
    
    $sql5 = "Select * from ".$this->config->item('db_prefix')."doctors where ID='".$_SESSION['logged_doctor']['doctor_id']."'";
    $select_result5 = run_select_query($sql5); 

    $sql_data = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $patient_data = run_select_query($sql_data); 

    $select_iui = "SELECT * FROM `intrauterine` WHERE patient_id='$patient_id'";
    $select_result_iui = run_select_query($select_iui); 

    // 2. Define the 'is_complete' flag based on the result
    $is_complete = !empty($select_result_iui);

    // 3. Set the receipt number for the hidden input
    $final_receipt = ($is_complete) ? $select_result_iui['receipt_number'] : "";
?>

<div class="ga-pro">
<h3>IUI Discharge Summary</h3>

  <form action="" enctype='multipart/form-data' method="post">
  <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $patient_id;?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $select_result5['center_id']; ?>" class="form" name="center">
  <input type="hidden" value="<?php echo $patient_data['wife_name']; ?>" class="form" name="wife_name">
  <input type="hidden" value="<?php echo $patient_data['wife_phone']; ?>" class="form" name="wife_phone">
  <input type="hidden" value="<?php echo $patient_data['husband_name']; ?>" class="form" name="husband_name">
  <input type="hidden" value="<?php echo $patient_data['wife_address']; ?>" class="form" name="wife_address">
  <input type="hidden" value="<?php echo $patient_data['wife_age']; ?>" class="form" name="wife_age">
  <input type="hidden" value="<?php echo $select_result4['female_pregnancy_other_p']; ?>" class="form" name="female_pregnancy_other_p">
  <input type="hidden" value="<?php echo $select_result4['female_pregnancy_other_l']; ?>" class="form" name="female_pregnancy_other_l">
  <input type="hidden" value="<?php echo $select_result4['female_pregnancy_other_a']; ?>" class="form" name="female_pregnancy_other_a">
  <input type="hidden" value="<?php echo $select_result4['details_management_advised']; ?>" class="form" name="details_management_advised">
  <?php if ($is_complete): ?>
    <input type="hidden" value="<?php echo $final_receipt; ?>" name="receipt_number">
    
    <div class="alert alert-success" style="padding: 10px; border-left: 5px solid #00a65a; margin-top: 5px;">
        <i class="fa fa-check-circle"></i> 
        <strong>Verified:</strong> IUI IPD clinical form is present. You may proceed.
    </div>

<?php else: ?>
    <div class="alert alert-danger" style="padding: 15px; margin-top: 5px; border-left: 5px solid #a94442;">
        <i class="fa fa-exclamation-triangle fa-2x pull-left" style="margin-right: 15px;"></i> 
        <strong>Clinical Data Incomplete!</strong><br>
        The following mandatory record is missing:
        <ul style="margin-top:10px;">
            <li>IUI Form (Missing for Receipt: <?php echo $receipt_number; ?>)</li>
        </ul>
        <p style="margin-top:10px;"><em>Please fill the IUI form before proceeding with this entry.</em></p>
    </div>
    
    <input type="hidden" name="receipt_number" value="">
    
    <style>
        /* Automatically hides the save button if clinical data is missing */
        #submitbutton, .btn-submit, button[type="submit"] { 
            display: none !important; 
        } 
    </style>
<?php endif; ?>	
  <?php $physical = array();
    if(!empty($select_result['physical_examination'])){
        $physical = explode(',',$select_result['physical_examination']);
    }
	$physical2 = array();
    if(!empty($select_result['Conscious'])){
        $physical2 = explode(',',$select_result['Conscious']);
    }
    if(!empty($select_result['applicablemedicine'])){
        $applicablemedicine = explode(',',$select_result['applicablemedicine']);
    }
  ?>
  
<input type="hidden" value="<?php echo $_SESSION['logged_doctor']['doctor_id'] ?>" class="form" name="doctor_id">
<div class="col-sm-12 col-md-12">	
<div class="col-sm-12 col-md-4" style="margin-bottom: 10px;">
<label for="Center">Center</label>
<select class="form-control" id="center" name="center">
    <option value=''>--Select From--</option>
    <?php $all_centers = $all_method->get_all_centers();
	foreach($all_centers as $key => $val){ //var_dump($val);die;
    if($center == $val['center_number']){
    echo '<option value="'.$val['center_number'].'" selected>'.$val['center_name'].'</option>';
    }else{
	echo '<option value="'.$val['center_number'].'">'.$val['center_name'].'</option>';
    }
    } 
	?>
    </select> 
 </div> 
<div class="col-sm-12 col-md-2" style="margin-bottom: 10px;">
  <label for="Admission">Date of Admission:</label>
  <input type="date" class="Admission" name="date_of_addmission" value="<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>">
 </div>
<div class="col-sm-12 col-md-2" style="margin-bottom: 10px;">
  <label for="Admission">Admission Time:</label>
  <input type="time" class="Admission" name="time_of_addmission" value="<?php echo isset($select_result['time_of_addmission'])?$select_result['time_of_addmission']:""; ?>">
 </div>   
<div class="col-sm-12 col-md-2" style="margin-bottom: 10px;">
  <label for="Discharge">Date of Discharge:</label>
  <input type="date" class="Discharge" name="date_of_discharge" value="<?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?>">
 </div> 
<div class="col-sm-12 col-md-2" style="margin-bottom: 10px;">
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
<td width="50%" colspan="3">
<strong>Female Partner : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
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
<td width="50%" colspan="3">
<strong>Final Diagnosis: 

 <textarea name="male_issues" style="width:100%; height:150px;" > <?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?> </textarea>
</strong>

</td>
</tr>

<tr>
<td width="50%" colspan="3">
<strong>Name of Procedure : IUI (INTRA UTERINE INSEMINATION)</strong>
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
<p>
 <input type="checkbox" class="pallor" name="physical_examination[]" value="Nopallor" <?php if(!empty($select_result['physical_examination']) && in_array('Nopallor', $physical)){echo "checked";}?>>
 <label for="Condition">No pallor</label>
  <input type="checkbox" class="icterus" name="physical_examination[]" value="icterus" <?php if(!empty($select_result['physical_examination']) && in_array('icterus',$physical)){echo "checked";}?>>
 <label for="Condition">icterus</label>
  <input type="checkbox" class="cyanosis" name="physical_examination[]" value="cyanosis" <?php if(!empty($select_result['physical_examination']) && in_array('cyanosis',$physical)){echo "checked";}?>>
 <label for="Condition">cyanosis</label>
<input type="checkbox" class="clubbing" name="physical_examination[]" value="digitalclubbing" <?php if(!empty($select_result['physical_examination']) && in_array('digitalclubbing',$physical)){echo "checked";}?>>
 <label for="Condition">digital clubbing</label>
<input type="checkbox" class="lymphadenopathy" name="physical_examination[]" value="lymphadenopathy" <?php if(!empty($select_result['physical_examination']) && in_array('lymphadenopathy',$physical)){echo "checked";}?>>
 <label for="Condition">lymphadenopathy</label>
 <input type="checkbox" class="oedema" name="physical_examination[]" value="pedaloedema" <?php if(!empty($select_result['physical_examination']) && in_array('pedaloedema',$physical)){echo "checked";}?>>
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
  <input type="text" class="CVS" name="Patient_CVS"  value="<?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?>"><br>
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
  <input type="text" class="Course" name="Patient_Course" style="width: 100%;" value="<?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?>"><br>
</div> 
<div class="sec2">
 <label for="Condition">Condition at Discharge:</label>
  <input type="text" class="Condition" name="Patient_Condition" style="width: 100%;" value="<?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?>"><br>
</div> 

<div class="sec3">
<h4>ADVICE ON DISCHARGE</h4>   
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
<p>1TAB</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Aquagest25MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('Aquagest25MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Aquagest 25 MG</p>
</td>
<td width="76">
<p>25 mg</p>
</td>
<td width="76">
<p>intramuscular</p>
</td>
<td width="83">
<p><input type="checkbox" name="applicablemedicine[]" value="natOncedaily" <?php if(!empty($select_result['applicablemedicine']) && in_array('natOncedaily',$applicablemedicine)){echo "checked";}?>>
	Once daily
	<input type="checkbox" name="applicablemedicine[]" value="natalternate" <?php if(!empty($select_result['applicablemedicine']) && in_array('natalternate',$applicablemedicine)){echo "checked";}?>>
	alternate 
	<input type="checkbox" name="applicablemedicine[]" value="natbiweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('natbiweekly',$applicablemedicine)){echo "checked";}?>>
	biweekly 
	</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>16 Days</p>
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
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Genprogel" <?php if(!empty($select_result['applicablemedicine']) && in_array('Genprogel',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Genpro gel</p>
</td>
<td width="76">
<p>8%</p>
</td>
<td width="76">
<p>Vaginal</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>Before going to sleep</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>16 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="INFAGEST10MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('INFAGEST10MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>INFAGEST 10MG</p>
</td>
<td width="76">
<p>1TAB</p>
</td>
<td width="76">
<p>Oral</p>
</td>
<td width="83">
<p>Thrice daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="IPARIN40MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('IPARIN40MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>IPARIN 40 MG</p>
</td>
<td width="76">
<p>40 mg</p>
</td>
<td width="76">
<p>subcutaneous</p>
</td>
<td width="83">
<p><input type="checkbox" name="applicablemedicine[]" value="oxyOncedaily" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxyOncedaily',$applicablemedicine)){echo "checked";}?>>
	Once daily
	<input type="checkbox" name="applicablemedicine[]" value="oxyalternate" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxyalternate',$applicablemedicine)){echo "checked";}?>>
	alternate
	<input type="checkbox" name="applicablemedicine[]" value="oxybiweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxybiweekly',$applicablemedicine)){echo "checked";}?>>
	biweekly 
	<input type="checkbox" name="applicablemedicine[]" value="oxyweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxyweekly',$applicablemedicine)){echo "checked";}?>>
	weekly
	</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>16 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Injcoriosurge10000" <?php if(!empty($select_result['applicablemedicine']) && in_array('Injcoriosurge10000',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Inj coriosurge 10000</p>
</td>
<td width="76">
<p></p>
</td>
<td width="76">
<p>subcutaneous</p>
</td>
<td width="83">
<p><input type="checkbox" name="applicablemedicine[]" value="corOncedaily" <?php if(!empty($select_result['applicablemedicine']) && in_array('corOncedaily',$applicablemedicine)){echo "checked";}?>>
	Once daily
	<input type="checkbox" name="applicablemedicine[]" value="coralternate" <?php if(!empty($select_result['applicablemedicine']) && in_array('coralternate',$applicablemedicine)){echo "checked";}?>>
	alternate
	<input type="checkbox" name="applicablemedicine[]" value="corbiweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('corbiweekly',$applicablemedicine)){echo "checked";}?>>
	biweekly
	<input type="checkbox" name="applicablemedicine[]" value="corweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('corweekly',$applicablemedicine)){echo "checked";}?>>
	weekly
	</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>16 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabAllegra" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabAllegra',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Tab Allegra</p>
</td>
<td width="76">
<p>1 TAB</p>
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
<p>16 Days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabMontairLC" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabMontairLC',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Tab Montair LC</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>	<input type="checkbox" name="applicablemedicine[]" value="INFAGESoral" <?php if(!empty($select_result['applicablemedicine']) && in_array('INFAGESoral',$applicablemedicine)){echo "checked";}?>>
	Oral
	<input type="checkbox" name="applicablemedicine[]" value="INFAGESvaginally" <?php if(!empty($select_result['applicablemedicine']) && in_array('INFAGESvaginally',$applicablemedicine)){echo "checked";}?>>
	vaginally</p>
</td>
<td width="83">
<p>	<input type="checkbox" name="applicablemedicine[]" value="genonce" <?php if(!empty($select_result['applicablemedicine']) && in_array('genonce',$applicablemedicine)){echo "checked";}?>>
	Once
	<input type="checkbox" name="applicablemedicine[]" value="gentwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('gentwice',$applicablemedicine)){echo "checked";}?>>
	twice
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<?php
$medicine = 'TabEstrabet2mg';
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
<p>2 mg</p>
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
$medicine = 'TabEstrade2mg';
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
<p>2 mg</p>
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
$medicine = 'TabGest10mg';
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
<p>10 MG</p>
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
$medicine = 'GenproD';
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
$medicine = 'TabDuphaston10mg';
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
<p>40 mg</p>
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
$medicine = 'Injsugest';
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
$medicine = 'ETgel';
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
$medicine = 'Injclexane';
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
$medicine = 'InjPuberjenJO7500IU';
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
$medicine = 'Crinonegel';
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
$medicine = 'GenproSR';
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
<p>400 mg</p>
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
$medicine = 'Luprorin4MGInj';
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
<p>400 mg</p>
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
$medicine = 'CapVitD3';
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
<p>400 mg</p>
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
$medicine = 'CapFericipXT';
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
<tr>
<td width="12.5%">
<p>There are No Substitutes</p>
</td>
</tr>
</tbody>
</table>
</div>
<div class="nb56ty">
 <label for="other">Other Medication1:</label>
  <input type="text" class="other1" name="Other_Medication1" value="<?php echo isset($select_result['Other_Medication1'])?$select_result['Other_Medication1']:""; ?>"><br>
 <label for="other">Other Medication2:</label>
  <input type="text" class="other2" name="Other_Medication2" value="<?php echo isset($select_result['Other_Medicatio2'])?$select_result['Other_Medication2']:""; ?>"><br> 
 <label for="other">Other Medication3:</label>
  <input type="text" class="other3" name="Other_Medication3" value="<?php echo isset($select_result['Other_Medication3'])?$select_result['Other_Medication3']:""; ?>"><br>
 <label for="other">Other Medication4:</label>
  <input type="text" class="other4" name="Other_Medication4" value="<?php echo isset($select_result['Other_Medication4'])?$select_result['Other_Medication4']:""; ?>"><br>
</div>

<div class="sec2">
<ul>
<li>Normal diet (avoid pineapple, papaya, spicy food, food you are allergic to, excessive tea coffee)</li>
<li>Continue thyroid /antihypertensive/ diabetes medications as have been taking previously</li>
<li>Serum beta HCG blood test 15days after IUI</li>
<li>Don&rsquo;t stop any medication without consulting doctor</li>
<li>Avoid heavy work. you can do normal activity.</li>
<li>To report in emergency of the hospital near by immediately if patient has abdominal pain/ vaginal bleeding/ fever /excessive cough /giddiness /vomiting/nausea/purulent discharge</li>
</ul>
</div>

<div class="sec2" style="display: flex; padding-top: 5px;">
 <label for="BP"><b>Follow Up Advice:</b>Serum beta HCG blood test 15days after IUI Review with DR. </label>
  <input type="text" class="followup" name="Doctor_name" value="<?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>"> <br>
  <label for="followup">on</label>
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
<input type="submit" name="submit2" value="PCP NDT">
</form>

<div class="row" id="print_this_section" style="display:none;">
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="https://indiaivf.website/assets/images/india-ivf-logo.webp"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">IUI Discharge Summary</h3></td>
   </tr>
</table>
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="Admission">Date of Admission:</label>
 <p><?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?></p>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<label for="Admission">Date of Discharge:</label>
<p><?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?></p>
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
<strong>IIC ID: <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Female Partner : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?> </strong>
</td>
</tr>

<tr>
<td colspan="3" colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Provisional Diagnosis:</strong>

<p><?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?></p>

</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Final Diagnosis: </strong>

 <p><?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?></p>


</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Name of Procedure : IUI (INTRA UTERINE INSEMINATION)</strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of procedure:  <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?></strong>
</td>
</tr>

<tr>
<td width="100%" colspan="6" style="border:1px solid;padding:5px;">
<p><strong>Physical Examination: </strong></p>
<p><input type="checkbox" class="Conscious" name="Conscious[]" value="Conscious" <?php if(!empty($select_result['Conscious']) && in_array('Conscious', $physical2)){echo "checked";}?>>
   <label for="Condition">Conscious</label>
   <input type="checkbox" class="oriented" name="Conscious[]" value="oriented" <?php if(!empty($select_result['Conscious']) && in_array('oriented', $physical2)){echo "checked";}?>>
   <label for="Condition">oriented</label>  
</p>
</td>
</tr>
<tr>
<td width="100%" colspan="6" style="border:1px solid;padding:5px;">
<p>
 <input type="checkbox" class="pallor" name="physical_examination[]" value="Nopallor" <?php if(!empty($select_result['physical_examination']) && in_array('Nopallor', $physical)){echo "checked";}?>>
 <label for="Condition">No pallor</label>
  <input type="checkbox" class="icterus" name="physical_examination[]" value="icterus" <?php if(!empty($select_result['physical_examination']) && in_array('icterus',$physical)){echo "checked";}?>>
 <label for="Condition">icterus</label>
  <input type="checkbox" class="cyanosis" name="physical_examination[]" value="cyanosis" <?php if(!empty($select_result['physical_examination']) && in_array('cyanosis',$physical)){echo "checked";}?>>
 <label for="Condition">cyanosis</label>
<input type="checkbox" class="clubbing" name="physical_examination[]" value="digitalclubbing" <?php if(!empty($select_result['physical_examination']) && in_array('digitalclubbing',$physical)){echo "checked";}?>>
 <label for="Condition">digital clubbing</label>
<input type="checkbox" class="lymphadenopathy" name="physical_examination[]" value="lymphadenopathy" <?php if(!empty($select_result['physical_examination']) && in_array('lymphadenopathy',$physical)){echo "checked";}?>>
 <label for="Condition">lymphadenopathy</label>
 <input type="checkbox" class="oedema" name="physical_examination[]" value="pedaloedema" <?php if(!empty($select_result['physical_examination']) && in_array('pedaloedema',$physical)){echo "checked";}?>>
 <label for="Condition">pedal oedema</label>
 </p>
</td>
</tr>

<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<label for="BP">BP</label>
 <p> <?php echo isset($select_result['Patient_BP'])?$select_result['Patient_BP']:""; ?></p>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<label for="BP">(mm Hg) PR</label>
 <p> <?php echo isset($select_result['Patient_PR'])?$select_result['Patient_PR']:""; ?></p>
</td>
</tr>

<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<label for="BP">RR</label>
 <p> <?php echo isset($select_result['Patient_RR'])?$select_result['Patient_RR']:""; ?></p>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<label for="BP">Temp</label>
 <p> <?php echo isset($select_result['Patient_Temp'])?$select_result['Patient_Temp']:""; ?></p>
</td>
</tr>

<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<label for="BP">SPO2 (on room air)</label>
 <p><?php echo isset($select_result['Patient_SPO2'])?$select_result['Patient_SPO2']:""; ?></p>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<label for="BP">CVS</label>
 <p> <?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?></p>
</td>
</tr>

<tr>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<label for="BP">RS</label>
 <p><?php echo isset($select_result['Patient_RS'])?$select_result['Patient_RS']:""; ?></p>
</td>
<td width="50%" colspan="3" style="border:1px solid;padding:5px;">
<label for="BP">P/A</label>
 <p> <?php echo isset($select_result['Patient_PA'])?$select_result['Patient_PA']:""; ?></p>
</td>
</tr>
<tr>
<td width="50%" colspan="6" style="border:1px solid;padding:5px;">
<label for="BP">CNS</label>
 <p><?php echo isset($select_result['Patient_CNS'])?$select_result['Patient_CNS']:""; ?></p>
</td>
</tr>

<tr>
<td width="50%" colspan="6" style="border:1px solid;padding:5px;">
<label for="BP">Course in the hospital:</label>
 <p><?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?></p>
</td>
</tr>

<tr>
<td width="50%" colspan="6" style="border:1px solid;padding:5px;">
<label for="BP">Condition at Discharge:</label>
 <p><?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?></p>
</td>
</tr>
</tbody>
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
<p>16 Days</p>
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
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Aquagest25MG',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Aquagest25MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('Aquagest25MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Aquagest 25 MG</p>
</td>
<td width="76">
<p>100 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>intramuscular</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p><input type="checkbox" name="applicablemedicine[]" value="natOncedaily" <?php if(!empty($select_result['applicablemedicine']) && in_array('natOncedaily',$applicablemedicine)){echo "checked";}?>>
	Once daily
	<input type="checkbox" name="applicablemedicine[]" value="natalternate" <?php if(!empty($select_result['applicablemedicine']) && in_array('natalternate',$applicablemedicine)){echo "checked";}?>>
	alternate 
	<input type="checkbox" name="applicablemedicine[]" value="natbiweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('natbiweekly',$applicablemedicine)){echo "checked";}?>>
	biweekly 
	</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
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
<?php if(!empty($select_result['applicablemedicine']) && in_array('Genprogel',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Genprogel" <?php if(!empty($select_result['applicablemedicine']) && in_array('Genprogel',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Genpro gel</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>8%</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Vaginal</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Before going to sleep</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('INFAGEST10MG',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="INFAGEST10MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('INFAGEST10MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>INFAGEST 10MG</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1TAB</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Thrice daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('IPARIN40MG',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="IPARIN40MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('IPARIN40MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>IPARIN 40 MG</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>40 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>subcutaneous</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p><input type="checkbox" name="applicablemedicine[]" value="oxyOncedaily" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxyOncedaily',$applicablemedicine)){echo "checked";}?>>
	Once daily
	<input type="checkbox" name="applicablemedicine[]" value="oxyalternate" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxyalternate',$applicablemedicine)){echo "checked";}?>>
	alternate
	<input type="checkbox" name="applicablemedicine[]" value="oxybiweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxybiweekly',$applicablemedicine)){echo "checked";}?>>
	biweekly 
	<input type="checkbox" name="applicablemedicine[]" value="oxyweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxyweekly',$applicablemedicine)){echo "checked";}?>>
	weekly 
	</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Injcoriosurge10000',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Injcoriosurge10000" <?php if(!empty($select_result['applicablemedicine']) && in_array('Injcoriosurge10000',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Inj coriosurge 10000</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>subcutaneous</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p><input type="checkbox" name="applicablemedicine[]" value="corOncedaily" <?php if(!empty($select_result['applicablemedicine']) && in_array('corOncedaily',$applicablemedicine)){echo "checked";}?>>
	Once daily
	<input type="checkbox" name="applicablemedicine[]" value="coralternate" <?php if(!empty($select_result['applicablemedicine']) && in_array('coralternate',$applicablemedicine)){echo "checked";}?>>
	alternate
	<input type="checkbox" name="applicablemedicine[]" value="corbiweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('corbiweekly',$applicablemedicine)){echo "checked";}?>>
	biweekly
	<input type="checkbox" name="applicablemedicine[]" value="corweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('corweekly',$applicablemedicine)){echo "checked";}?>>
	weekly
	</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabAllegra',$applicablemedicine)){  ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabAllegra" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabAllegra',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Allegra</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 TAB</p>
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
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabMontairLC',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabMontairLC" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabMontairLC',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Montair LC</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>	<input type="checkbox" name="applicablemedicine[]" value="INFAGESoral" <?php if(!empty($select_result['applicablemedicine']) && in_array('INFAGESoral',$applicablemedicine)){echo "checked";}?>>
	Oral
	<input type="checkbox" name="applicablemedicine[]" value="INFAGESvaginally" <?php if(!empty($select_result['applicablemedicine']) && in_array('INFAGESvaginally',$applicablemedicine)){echo "checked";}?>>
	vaginally</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>	<input type="checkbox" name="applicablemedicine[]" value="genonce" <?php if(!empty($select_result['applicablemedicine']) && in_array('genonce',$applicablemedicine)){echo "checked";}?>>
	Once
	<input type="checkbox" name="applicablemedicine[]" value="gentwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('gentwice',$applicablemedicine)){echo "checked";}?>>
	twice
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<p>16 Days</p>
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
<?php
$medicine = 'TabEstrabet2mg';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
</td>
<td width="117"style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>2 mg</p>
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
$medicine = 'TabEstrade2mg';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>2 mg</p>
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
$medicine = 'TabGest10mg';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>10 MG</p>
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
$medicine = 'GenproD';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
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
<td width="71" style="border:1px solid;padding:5px;">
<p>SOS )</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php
$medicine = 'TabDuphaston10mg';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>40 mg</p>
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
$medicine = 'Injsugest';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
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
$medicine = 'ETgel';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
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
$medicine = 'Injclexane';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
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
$medicine = 'InjPuberjenJO7500IU';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
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
$medicine = 'Crinonegel';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
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
$medicine = 'GenproSR';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>400 mg</p>
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
$medicine = 'Luprorin4MGInj';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>400 mg</p>
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
$medicine = 'CapVitD3';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p><?= $medicine ?></p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>400 mg</p>
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
$medicine = 'CapFericipXT';
if (!empty($select_result['applicablemedicine']) && in_array($medicine, $applicablemedicine)) {
?>
<tr>
<td style="border:1px solid;padding:5px;">
    <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="<?= $medicine ?>" checked> 
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
<tr><td width="100%"></td></tr>
<tr>
<td width="100%" colspan="8" style="border:1px solid;padding:5px;">
<p>Other Medication1: <?php echo isset($select_result['Other_Medication1'])?$select_result['Other_Medication1']:""; ?></p>
</td>
</tr>
<tr>
<td width="100%" colspan="8" style="border:1px solid;padding:5px;">
<p>Other Medication2: <?php echo isset($select_result['Other_Medicatio2'])?$select_result['Other_Medication2']:""; ?></p>
</td>
</tr>
<tr>
<td width="100%" colspan="8" style="border:1px solid;padding:5px;">
<p>Other Medication3: <?php echo isset($select_result['Other_Medication3'])?$select_result['Other_Medication3']:""; ?></p>
</td>
</tr>
<tr>
<td width="100%" colspan="8" style="border:1px solid;padding:5px;">
<p>Other Medication4: <?php echo isset($select_result['Other_Medication4'])?$select_result['Other_Medication4']:""; ?></p>
</td>
</tr>
<tr>
<td width="100%" colspan="8" style="border:1px solid;padding:5px;">
<ul>
<li>Normal diet (avoid pineapple, papaya, spicy food, food you are allergic to, excessive tea coffee)</li>
<li>Continue thyroid /antihypertensive/ diabetes medications as have been taking previously</li>
<li>Serum beta HCG blood test 15days after IUI</li>
<li>Don&rsquo;t stop any medication without consulting doctor</li>
<li>Avoid heavy work. you can do normal activity.</li>
<li>To report in emergency of the hospital near by immediately if patient has abdominal pain/ vaginal bleeding/ fever /excessive cough /giddiness /vomiting/nausea/purulent discharge</li>
</ul>
</td>
</tr>

<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<label for="BP"><b>Follow Up Advice:</b>Serum beta HCG blood test 15days after IUI Review with DR. </label>
<?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>
 
</td>

<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<label for="followup">on</label>
  <?php echo isset($select_result['advice'])?$select_result['advice']:""; ?>
 
</td>
</tr>

<tr>
<td width="100%" colspan="8" style="border:1px solid;padding:5px;">
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
<td width="100%" colspan="8" style="border:1px solid;padding:5px;">
<label for="Sr IVF Consultant">Sr IVF Consultant</label>
  <?php echo isset($select_result['IVF_Consultant'])?$select_result['IVF_Consultant'] : ""; ?>
 
</td>
</tr>

<tr>
<td width="100%" colspan="8" style="border:1px solid;padding:5px;">
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<style type="text/css">
    form{
        margin: 20px 0;
    }
    form input, button{
        padding: 5px;
    }
    table{
        width: 100%;
        margin-bottom: 20px;
		border-collapse: collapse;
    }
    table, th, td{
        border: 1px solid #cdcdcd;
    }
    table th, table td{
        padding: 10px;
        text-align: left;
    }
	.heading{margin-bottom:10px;margin-top: 0; padding-top:0px;}
	select {
    display: block !important;
}
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: static!important;
    left: -9999pximportant;
    opacity: 1!important;
}
</style>