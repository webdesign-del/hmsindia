<?php 
$all_method =& get_instance();

if(isset($_POST['submit'])){
        unset($_POST['submit']);
  
        $select_query = "SELECT * FROM `hysteroscopy_laparoscopy_discharge_summary` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 

        $sqlArr = array(); // एरे को ऊपर ही डिफाइन कर दिया ताकि दोनों ब्लॉक्स में सेफ रहे

        if(empty($select_result)){
            // 1. MYSQL QUERY TO INSERT DATA
            $query = "INSERT INTO `hysteroscopy_laparoscopy_discharge_summary` SET ";

            foreach($_POST as $key => $value) {
                // 💡 FIX 1: अगर वैल्यू एरे (Checkboxes) है, तो उसे पहले स्ट्रिंग में बदलें ताकि addslashes() क्रैश न हो
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }   

            $query .= implode(',' , $sqlArr);
            $msg = 'Procedure form inserted successfully!';

        } else {
            // 2. MYSQL QUERY TO UPDATE DATA
            $query = "UPDATE `hysteroscopy_laparoscopy_discharge_summary` SET ";

            foreach($_POST as $key => $value) {
                // 💡 FIX 2: अपडेट में भी एरे को स्ट्रिंग में बदलना ज़रूरी है
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                // 💡 FIX 3: अपडेट में भी addslashes() लगाया ताकि डॉक्टर अगर सिंगल कोट (') यूज़ करे तो क्वेरी न टूटे
                $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }

            $query .= implode(',' , $sqlArr);
            $query .= " WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
            $msg = 'Procedure form updated successfully!';
        }

        $result = run_form_query($query);        
        if($result){
          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode($msg).'&t='.base64_encode('success'));
          die();
        }else{
          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
          die();
        }
    }

    // डेटा फेच करने की क्वेरीज़
    $sql = "SELECT * FROM `hysteroscopy_laparoscopy_discharge_summary` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($sql);

    $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $patient_data = run_select_query($sql3); 
  
    // 💡 FIX 2: $this->config को $all_method->config से बदला ताकि प्रिंट स्क्रीन पर 500 एरर न आए
    $sql1 = "Select * from ".$all_method->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient' ";
    $select_result1 = run_select_query($sql1);
  
    $sql3 = "Select * from ".$all_method->config->item('db_prefix')."centers where center_number='".($select_result1['appoitment_for'] ?? '')."'";
    $select_result3 = run_select_query($sql3);  
  
    $sql5 = "Select * from ".$all_method->config->item('db_prefix')."doctors where ID='".($_SESSION['logged_doctor']['doctor_id'] ?? '')."'";
    $select_result5 = run_select_query($sql5); 
  
    $select_query_laparoscopy = "SELECT * FROM `laparoscopy_hysteroscopy` WHERE patient_id='$patient_id'";
    $select_result_laparoscopy = run_select_query($select_query_laparoscopy); 

    $is_complete = !empty($select_result_laparoscopy);
    $final_receipt = ($is_complete) ? $select_result_laparoscopy['receipt_number'] : "";
       
?>

<?php 
    // 💡 FIX 3: एरे और स्पेलिंग चेक वेरिएबल्स को सुधारा गया
    $physical = $applicablemedicine = $procedures = array();
    if(!empty($select_result['physical_examination'])){
        $physical = explode(',',$select_result['physical_examination']);
    }
    if(!empty($select_result['applicablemedicine'])){
        $applicablemedicine = explode(',',$select_result['applicablemedicine']);
    }
    if(!empty($select_result['procedures'])){
        $procedures = explode(',',$select_result['procedures']);
    }
?>
<div class="ga-pro">
<h3>Discharge Summary</h3>

<form action="" enctype='multipart/form-data' method="post">

<input type="hidden" value="<?php echo isset($updated_by) ? $updated_by : ''; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo isset($updated_type) ? $updated_type : ''; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo isset($updated_at) ? $updated_at : ''; ?>" class="form" name="updated_at">
<input type="hidden" value="<?php echo isset($appoitmented_date) ? $appoitmented_date : ''; ?>" class="form" name="appoitmented_date">
<input type="hidden" value="<?php echo isset($patient_id) ? $patient_id : ''; ?>" class="form" name="patient_id">
<input type="hidden" value="<?php echo isset($_SESSION['logged_doctor']['doctor_id']) ? $_SESSION['logged_doctor']['doctor_id'] : ''; ?>" class="form" name="doctor_id">        

<?php if ($is_complete): ?>
    <input type="hidden" value="<?php echo isset($final_receipt) ? $final_receipt : ''; ?>" name="receipt_number">
    
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
            <li>Laparoscopy Form (Missing for Receipt: <?php echo isset($receipt_number) ? $receipt_number : ''; ?>)</li>
        </ul>
        <p style="margin-top:10px;"><em>Please fill the Laparoscopy form before proceeding with this entry.</em></p>
    </div>
    
    <input type="hidden" name="receipt_number" value="">
    
    <style>
        /* 💡 FIX: आपकी फ़ाइल के नीचे बटन input[type="submit"] है, उसे भी यहाँ छुपाने का रूल जोड़ दिया */
        #submitbutton, .btn-submit, button[type="submit"], input[type="submit"] { 
            display: none !important; 
        } 
    </style>
<?php endif; ?>
  
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
<strong>IPID: <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong>
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
<strong>Name of Procedure : <input type="checkbox" class="Hysteroscopy" name="procedures[]" value="Hysteroscopy" <?php if(!empty($select_result['procedures']) && in_array('Hysteroscopy',$procedures)){echo "checked";}?>> Diagnostic operative Hysteroscopy <input type="checkbox" class="Laparoscopy" name="procedures[]" value="Laparoscopy" <?php if(!empty($select_result['procedures']) && in_array('Laparoscopy',$procedures)){echo "checked";}?>> Diagnostic operative  Laparoscopy</strong>
</td>
<td colspan="3" width="50%">
 <strong>Date of procedure:  <input type="date" class="date_of_procedure" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>">   </strong>
</td>
</tr>
</tbody>
</table> 

<div class="sec2">
<p><strong>Physical Examination: </strong></p>
<p>
  <input type="radio" id="Conscious" name="conscious" value="Conscious" <?php if(isset($select_result['Conscious'])  && $select_result['Conscious'] == "Conscious"){ echo "checked";} ?> >
  <label for="Conscious">Conscious</label><br>
  <input type="radio" id="oriented" name="conscious" value="Oriented" <?php if(isset($select_result['Conscious'])  && $select_result['Conscious'] == "Oriented"){ echo "checked";} ?> >
  <label for="oriented">Oriented</label><br>  
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
<input type="checkbox" class="lymphadenopathy" name="physical_examination[]" value="lymphadenopathy" <?php if(!empty($select_result['physical_examination']) && in_array('digital clubbing',$physical)){echo "checked";}?>>
 <label for="Condition">lymphadenopathy</label>
 <input type="checkbox" class="oedema" name="physical_examination[]" value="pedal oedema" <?php if(!empty($select_result['physical_examination']) && in_array('digital clubbing',$physical)){echo "checked";}?>>
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


<div class="sec2">
 <label for="Course">Course in the hospital:</label>
  <input type="text" class="Course" name="Patient_Course" style="width: 100%;" value="<?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?>">
</div>

<div class="sec2">
<h4 style="margin: 0; padding-left: 10px; text-decoration: underline; font-size: 20px;">Hysteroscopy findings:</h4> 

  <label for="date">Date:</label>
  <input type="date" class="Hysteroscopy-date" name="Hysteroscopy_date" value="<?php echo isset($select_result['Hysteroscopy_date'])?$select_result['Hysteroscopy_date']:""; ?>">
<br>    
 <label for="Indication">Indication:</label>
  <input type="text" class="Indication" name="Indication" value="<?php echo isset($select_result['Indication'])?$select_result['Indication']:""; ?>"><br>
 <label for="Indication">Uterine cavity:</label>
  <input type="text" class="Uterine-cavity" name="Uterine_cavity" value="<?php echo isset($select_result['Uterine_cavity'])?$select_result['Uterine_cavity']:""; ?>"><br>
 <label for="Indication">Ostia:</label>
  <input type="text" class="Ostia" name="Ostia" value="<?php echo isset($select_result['Ostia'])?$select_result['Ostia']:""; ?>"><br>
 <label for="Indication">Endometrial Biopsy: TB HPE:</label>
  <input type="text" class="Endometrial-Biopsy" name="Endometrial_Biopsy" value="<?php echo isset($select_result['Endometrial_Biopsy'])?$select_result['Endometrial_Biopsy']:""; ?>"><br>
 <label for="Any other finding">Any other finding:</label>
  <input type="text" class="Any-other-finding" name="Any_other_finding" value="<?php echo isset($select_result['Any_other_finding'])?$select_result['Any_other_finding']:""; ?>"><br>

<h4 style="margin: 0; padding-left: 10px; text-decoration: underline; font-size: 20px;">Laparoscopy findings:</h4> 


  <label for="date">Date:</label>
  <input type="date" class="Laparoscopy-date" name="Laparoscopy_date" value="<?php echo isset($select_result['Laparoscopy_date'])?$select_result['Laparoscopy_date']:""; ?>">
<br> 
 <label for="Indication">Indication:</label>
  <input type="text" class="Laparoscopy-Indication" name="Laparoscopy_Indication" value="<?php echo isset($select_result['Laparoscopy_Indication'])?$select_result['Laparoscopy_Indication']:""; ?>"><br>
 <label for="Uterus">Uterus:</label>
  <input type="text" class="Uterus" name="Uterus" value="<?php echo isset($select_result['Uterus'])?$select_result['Uterus']:""; ?>"><br>
 <label for="Tubes">Tubes:</label>
  <input type="text" class="Tubes" name="Tubes" value="<?php echo isset($select_result['Tubes'])?$select_result['Tubes']:""; ?>"><br>
 <label for="Ovaries">Ovaries:</label>
  <input type="text" class="Ovaries" name="Ovaries" value="<?php echo isset($select_result['Ovaries'])?$select_result['Ovaries']:""; ?>"><br>
 <label for="POD">POD:</label>
  <input type="text" class="POD" name="POD" value="<?php echo isset($select_result['POD'])?$select_result['POD']:""; ?>"><br>
 <label for="Liver">Liver:</label>
  <input type="text" class="Liver" name="Liver" value="<?php echo isset($select_result['Liver'])?$select_result['Liver']:""; ?>"><br>
 <label for="Chromotubation">Chromotubation:</label>
  <input type="text" class="Chromotubation" name="Chromotubation" value="<?php echo isset($select_result['Chromotubation'])?$select_result['Chromotubation']:""; ?>"><br>
 <label for="Laparoscopy Any other finding">Any other finding:</label>
  <input type="text" class="Laparoscopy-Any-other-finding" name="Laparoscopy_Any_other_finding" value="<?php echo isset($select_result['Laparoscopy_Any_other_finding'])?$select_result['Laparoscopy_Any_other_finding']:""; ?>"><br>
</div>  

<div class="sec2">
 <label for="Condition">Condition at Discharge:</label>
  <input type="text" class="Condition" name="Patient_Condition" style="width: 100%;" value="<?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?>">
</div> 


<div class="sec3">
  <h5 style="border: 1px solid #000; margin: 0;padding: 10px 10px;">ADVICE ON DISCHARGE</h5>
  <table width="100%">
<tbody>
<tr>
<td width="100%">
<p>Regular LIGHT exercise daily for one hour after one week</p>
</td>
</tr>
<tr>
<td width="100%">
<p>Do fertility yoga daily after one week</p>
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
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Tab Ceftum(500 mg)" <?php if(!empty($select_result['applicablemedicine']) && in_array('Tab Ceftum(500 mg)',$applicablemedicine)){echo "checked";}?>>

</td>
<td width="117">
<p>Tab Ceftum(500 mg)</p>
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
<p>	<input type="checkbox" name="applicablemedicine[]" value="gufi5days" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufi5days',$applicablemedicine)){echo "checked";}?>>
	5 Days 
	<input type="checkbox" name="applicablemedicine[]" value="gufi10days" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufi10days',$applicablemedicine)){echo "checked";}?>>
	10 Days 
	<input type="checkbox" name="applicablemedicine[]" value="gufi15days" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufi15days',$applicablemedicine)){echo "checked";}?>>
	15 Days
	<input type="checkbox" name="applicablemedicine[]" value="gufi21days" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufi21days',$applicablemedicine)){echo "checked";}?>>
	21 Days
	<input type="checkbox" name="applicablemedicine[]" value="gufi30days" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufi30days',$applicablemedicine)){echo "checked";}?>>
	30 Days
	</p>
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
<p>	<input type="checkbox" name="applicablemedicine[]" value="esto5days" <?php if(!empty($select_result['applicablemedicine']) && in_array('esto5days',$applicablemedicine)){echo "checked";}?>>
	5 Days 
	<input type="checkbox" name="applicablemedicine[]" value="esto10days" <?php if(!empty($select_result['applicablemedicine']) && in_array('esto10days',$applicablemedicine)){echo "checked";}?>>
	10 Days 
	<input type="checkbox" name="applicablemedicine[]" value="esto15days" <?php if(!empty($select_result['applicablemedicine']) && in_array('esto15days',$applicablemedicine)){echo "checked";}?>>
	15 Days
	<input type="checkbox" name="applicablemedicine[]" value="esto21days" <?php if(!empty($select_result['applicablemedicine']) && in_array('esto21days',$applicablemedicine)){echo "checked";}?>>
	21 Days
	<input type="checkbox" name="applicablemedicine[]" value="esto30days" <?php if(!empty($select_result['applicablemedicine']) && in_array('esto30days',$applicablemedicine)){echo "checked";}?>>
	30 Days
	</p>
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
<p>	<input type="checkbox" name="applicablemedicine[]" value="len5days" <?php if(!empty($select_result['applicablemedicine']) && in_array('len5days',$applicablemedicine)){echo "checked";}?>>
	5 Days 
	<input type="checkbox" name="applicablemedicine[]" value="len10days" <?php if(!empty($select_result['applicablemedicine']) && in_array('len10days',$applicablemedicine)){echo "checked";}?>>
	10 Days 
	<input type="checkbox" name="applicablemedicine[]" value="len15days" <?php if(!empty($select_result['applicablemedicine']) && in_array('len15days',$applicablemedicine)){echo "checked";}?>>
	15 Days
	<input type="checkbox" name="applicablemedicine[]" value="len21days" <?php if(!empty($select_result['applicablemedicine']) && in_array('len21days',$applicablemedicine)){echo "checked";}?>>
	21 Days
	<input type="checkbox" name="applicablemedicine[]" value="len30days" <?php if(!empty($select_result['applicablemedicine']) && in_array('len30days',$applicablemedicine)){echo "checked";}?>>
	30 Days
	</p>
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
<p>	<input type="checkbox" name="applicablemedicine[]" value="lupro1ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('lupro1ml',$applicablemedicine)){echo "checked";}?>>
	1 ML 
	<input type="checkbox" name="applicablemedicine[]" value="lupro2ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('lupro2ml',$applicablemedicine)){echo "checked";}?>>
	2 ML 
	<input type="checkbox" name="applicablemedicine[]" value="lupro3ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('lupro3ml',$applicablemedicine)){echo "checked";}?>>
	3 ML
	<input type="checkbox" name="applicablemedicine[]" value="lupro4ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('lupro4ml',$applicablemedicine)){echo "checked";}?>>
	4 ML
	</p>
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
<p>once Daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<p>5 Days</p>
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
 <label for="other">Medicine Advice1:</label>
  <input type="text" class="other1" name="Medicine_Advice1" value="<?php echo isset($select_result['Medicine_Advice1'])?$select_result['Medicine_Advice1']:""; ?>"><br>
<label for="other">Medicine Advice2:</label>
  <input type="text" class="other2" name="Medicine_Advice2" value="<?php echo isset($select_result['Medicine_Advice2'])?$select_result['Patient_BP']:""; ?>"><br>
 <label for="other">Medicine Advice3:</label>
  <input type="text" class="other3" name="Medicine_Advice3" value="<?php echo isset($select_result['Medicine_Advice3'])?$select_result['Medicine_Advice3']:""; ?>"><br>
 <label for="other">Medicine Advice4:</label>
  <input type="text" class="other4" name="Medicine_Advice4" value="<?php echo isset($select_result['Medicine_Advice4'])?$select_result['Medicine_Advice4']:""; ?>"><br>
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



<div class="sec2" style="display: flex; padding-top: 5px;">
 <label for="BP"><b>Follow Up Advice:</b> Review with DR.</label>
  <input type="text" class="followup" name="Doctor_name" value="<?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>"> <br>

  <label for="followup">on</label>
  <input type="date" class="follow-up" name="advice" value="<?php echo isset($select_result['advice'])?$select_result['advice']:""; ?>">

</div> 

<div class="sec2" style="color: red;">
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
<!-- 🖨️ नया प्रिंट बटन (यह सिर्फ तभी दिखेगा जब डेटाबेस में रिकॉर्ड पहले से मौजूद हो) -->
  
</form>
  <?php if(!empty($select_result)): ?>
        <button type="button" onclick="printDischargeSummary();" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff; padding: 10px 30px; font-size: 16px; margin-left: 10px; color: white; border: none; cursor: pointer; id="printButton">
            <i class="fa fa-print"></i> Print Summary
        </button>
    <?php endif; ?>
<div class="row" id="print_this_section" style="display:none;">
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="https://indiaivf.website/assets/images/india-ivf-logo.webp"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">Discharge Summary</h3></td>
</tr>
</table>
<form action="" enctype='multipart/form-data' method="post">   
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="Admission">Date of Admission:</label>
  <?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="Discharge">Date of Discharge:</label>
  <?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?>
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
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result1['uhid']; ?></strong>
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
<strong>Male Partner : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?></strong>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Provisional Diagnosis:
 <textarea name="female_issues" style="width:100%; height:80px!important;" > <?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?> </textarea>
</strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Final Diagnosis:
 <textarea name="male_issues" style="width:100%; height:80px!important;" > <?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?> </textarea>
</strong>

</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Name of Procedure : <input type="checkbox" class="Hysteroscopy" name="procedures[]" value="Hysteroscopy" <?php if(!empty($select_result['procedures']) && in_array('Hysteroscopy',$procedures)){echo "checked";}?>> Diagnostic operative Hysteroscopy <input type="checkbox" class="Laparoscopy" name="procedures[]" value="Laparoscopy" <?php if(!empty($select_result['procedures']) && in_array('Laparoscopy',$procedures)){echo "checked";}?>> Diagnostic operative  Laparoscopy</strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <strong>Date of procedure:  <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?></strong>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
<p><strong>Physical Examination: </strong></p>
<p>
  <input type="radio" id="Conscious" name="conscious" value="Conscious" <?php if(isset($select_result['Conscious'])  && $select_result['Conscious'] == "Conscious"){ echo "checked";} ?> >
  <label for="Conscious">Conscious</label><br>
  <input type="radio" id="oriented" name="conscious" value="Oriented" <?php if(isset($select_result['Conscious'])  && $select_result['Conscious'] == "Oriented"){ echo "checked";} ?> >
  <label for="oriented">Oriented</label><br>  
</p></td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
<p>
 <input type="checkbox" class="pallor" name="physical_examination[]" value="No pallor" <?php if(!empty($select_result['physical_examination']) && in_array('No pallor',$physical)){echo "checked";}?>>
 <label for="Condition">No pallor</label>
  <input type="checkbox" class="icterus" name="physical_examination[]" value="icterus" <?php if(!empty($select_result['physical_examination']) && in_array('icterus',$physical)){echo "checked";}?>>
 <label for="Condition">icterus</label>
  <input type="checkbox" class="cyanosis" name="physical_examination[]" value="cyanosis" <?php if(!empty($select_result['physical_examination']) && in_array('cyanosis',$physical)){echo "checked";}?>>
 <label for="Condition">cyanosis</label>
<input type="checkbox" class="clubbing" name="physical_examination[]" value="digital clubbing" <?php if(!empty($select_result['physical_examination']) && in_array('digital clubbing',$physical)){echo "checked";}?>>
 <label for="Condition">digital clubbing</label>
<input type="checkbox" class="lymphadenopathy" name="physical_examination[]" value="lymphadenopathy" <?php if(!empty($select_result['physical_examination']) && in_array('digital clubbing',$physical)){echo "checked";}?>>
 <label for="Condition">lymphadenopathy</label>
 <input type="checkbox" class="oedema" name="physical_examination[]" value="pedal oedema" <?php if(!empty($select_result['physical_examination']) && in_array('digital clubbing',$physical)){echo "checked";}?>>
 <label for="Condition">pedal oedema</label>
 </p>
 </td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="BP">BP (mm Hg)</label>
  <?php echo isset($select_result['Patient_BP'])?$select_result['Patient_BP']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="PR">PR (min)</label>
 <?php echo isset($select_result['Patient_PR'])?$select_result['Patient_PR']:""; ?>
 </td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <label for="PR">RR (min)</label>
  <?php echo isset($select_result['Patient_RR'])?$select_result['Patient_RR']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="PR">Temp (F)</label>
 <?php echo isset($select_result['Patient_Temp'])?$select_result['Patient_Temp']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
  <label for="PR">SPO2 (on room air)</label>
  <?php echo isset($select_result['Patient_SPO2'])?$select_result['Patient_SPO2']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="CVS">CVS</label>
  <?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?>
 </td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
  <label for="RS">RS</label>
  <?php echo isset($select_result['Patient_RS'])?$select_result['Patient_RS']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="P/A">P/A</label>
<?php echo isset($select_result['Patient_PA'])?$select_result['Patient_PA']:""; ?> 
 </td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
  <label for="CNS">CNS</label>
  <?php echo isset($select_result['Patient_CNS'])?$select_result['Patient_CNS']:""; ?>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
 <label for="Course">Course in the hospital:</label>
<?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
<h4 style="margin: 0; padding-left: 10px; text-decoration: underline; font-size: 20px;">Hysteroscopy findings:</h4> 
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <label for="date">Date:</label>
  <?php echo isset($select_result['Hysteroscopy_date'])?$select_result['Hysteroscopy_date']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="Indication">Indication:</label>
 <?php echo isset($select_result['Indication'])?$select_result['Indication']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="Indication">Uterine cavity:</label>
 <?php echo isset($select_result['Uterine_cavity'])?$select_result['Uterine_cavity']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <label for="Indication">Ostia:</label>
 <?php echo isset($select_result['Ostia'])?$select_result['Ostia']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="Indication">Endometrial Biopsy: TB HPE:</label>
 <?php echo isset($select_result['Endometrial_Biopsy'])?$select_result['Endometrial_Biopsy']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
  <label for="Any other finding">Any other finding:</label>
  <?php echo isset($select_result['Any_other_finding'])?$select_result['Any_other_finding']:""; ?>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
<h4 style="margin: 0; padding-left: 10px; text-decoration: underline; font-size: 20px;">Laparoscopy findings:</h4> 
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="date">Date:</label>
<?php echo isset($select_result['Laparoscopy_date'])?$select_result['Laparoscopy_date']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <label for="Indication">Indication:</label>
 <?php echo isset($select_result['Laparoscopy_Indication'])?$select_result['Laparoscopy_Indication']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="Uterus">Uterus:</label>
 <?php echo isset($select_result['Uterus'])?$select_result['Uterus']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <label for="Tubes">Tubes:</label>
 <?php echo isset($select_result['Tubes'])?$select_result['Tubes']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <label for="Ovaries">Ovaries:</label>
 <?php echo isset($select_result['Ovaries'])?$select_result['Ovaries']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <label for="POD">POD:</label>
 <?php echo isset($select_result['POD'])?$select_result['POD']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <label for="Liver">Liver:</label>
  <?php echo isset($select_result['Liver'])?$select_result['Liver']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
 <label for="Chromotubation">Chromotubation:</label>
  <?php echo isset($select_result['Chromotubation'])?$select_result['Chromotubation']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="Laparoscopy Any other finding">Any other finding:</label>
<?php echo isset($select_result['Laparoscopy_Any_other_finding'])?$select_result['Laparoscopy_Any_other_finding']:""; ?>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
<label for="Condition">Condition at Discharge:</label>
 <?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?>
</td>
</tr>
</tbody>
</table> 
 
<div class="sec3">
  <h5 style="border: 1px solid #000; margin: 0;padding: 10px 10px;">ADVICE ON DISCHARGE</h5>
  <table width="100%">
<tbody>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p>Regular LIGHT exercise daily for one hour after one week</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p>Do fertility yoga daily after one week</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p>Avoid hot sauna bath</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p>Consume Diet low in carbohydrate</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p>Consume Diet rich in green leafy vegetables, beans,pulses ,high protein diet</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p>Avoid/limit intake of tea and coffee/perfumes/cosmetics with fragrance</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
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
<p>	<input type="checkbox" name="applicablemedicine[]" value="gufi5days" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufi5days',$applicablemedicine)){echo "checked";}?>>
	5 Days 
	<input type="checkbox" name="applicablemedicine[]" value="gufi10days" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufi10days',$applicablemedicine)){echo "checked";}?>>
	10 Days 
	<input type="checkbox" name="applicablemedicine[]" value="gufi15days" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufi15days',$applicablemedicine)){echo "checked";}?>>
	15 Days
	<input type="checkbox" name="applicablemedicine[]" value="gufi21days" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufi21days',$applicablemedicine)){echo "checked";}?>>
	21 Days
	<input type="checkbox" name="applicablemedicine[]" value="gufi30days" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufi30days',$applicablemedicine)){echo "checked";}?>>
	30 Days
	</p>
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
<p>	<input type="checkbox" name="applicablemedicine[]" value="esto5days" <?php if(!empty($select_result['applicablemedicine']) && in_array('esto5days',$applicablemedicine)){echo "checked";}?>>
	5 Days 
	<input type="checkbox" name="applicablemedicine[]" value="esto10days" <?php if(!empty($select_result['applicablemedicine']) && in_array('esto10days',$applicablemedicine)){echo "checked";}?>>
	10 Days 
	<input type="checkbox" name="applicablemedicine[]" value="esto15days" <?php if(!empty($select_result['applicablemedicine']) && in_array('esto15days',$applicablemedicine)){echo "checked";}?>>
	15 Days
	<input type="checkbox" name="applicablemedicine[]" value="esto21days" <?php if(!empty($select_result['applicablemedicine']) && in_array('esto21days',$applicablemedicine)){echo "checked";}?>>
	21 Days
	<input type="checkbox" name="applicablemedicine[]" value="esto30days" <?php if(!empty($select_result['applicablemedicine']) && in_array('esto30days',$applicablemedicine)){echo "checked";}?>>
	30 Days
	</p>
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
<p>	<input type="checkbox" name="applicablemedicine[]" value="len5days" <?php if(!empty($select_result['applicablemedicine']) && in_array('len5days',$applicablemedicine)){echo "checked";}?>>
	5 Days 
	<input type="checkbox" name="applicablemedicine[]" value="len10days" <?php if(!empty($select_result['applicablemedicine']) && in_array('len10days',$applicablemedicine)){echo "checked";}?>>
	10 Days 
	<input type="checkbox" name="applicablemedicine[]" value="len15days" <?php if(!empty($select_result['applicablemedicine']) && in_array('len15days',$applicablemedicine)){echo "checked";}?>>
	15 Days
	<input type="checkbox" name="applicablemedicine[]" value="len21days" <?php if(!empty($select_result['applicablemedicine']) && in_array('len21days',$applicablemedicine)){echo "checked";}?>>
	21 Days
	<input type="checkbox" name="applicablemedicine[]" value="len30days" <?php if(!empty($select_result['applicablemedicine']) && in_array('len30days',$applicablemedicine)){echo "checked";}?>>
	30 Days
	</p>
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
<p>	<input type="checkbox" name="applicablemedicine[]" value="lupro1ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('lupro1ml',$applicablemedicine)){echo "checked";}?>>
	1 ML 
	<input type="checkbox" name="applicablemedicine[]" value="lupro2ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('lupro2ml',$applicablemedicine)){echo "checked";}?>>
	2 ML 
	<input type="checkbox" name="applicablemedicine[]" value="lupro3ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('lupro3ml',$applicablemedicine)){echo "checked";}?>>
	3 ML
	<input type="checkbox" name="applicablemedicine[]" value="lupro4ml" <?php if(!empty($select_result['applicablemedicine']) && in_array('lupro4ml',$applicablemedicine)){echo "checked";}?>>
	4 ML
	</p>    
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
<p>once Daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>5 Days</p>
</td>
</tr>
<?php } ?>
<tr><td width="100%"></td></tr>
</table>
<table width="100%">
<tr>
  <td>
<div class="nb56ty">
 <label for="other">Medicine Advice1:</label>
  <?php echo isset($select_result['Medicine_Advice1'])?$select_result['Medicine_Advice1']:""; ?>
</div>
</td>
</tr>
<tr>
  <td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<div class="nb56ty">
<label for="other">Medicine Advice2:</label>
 <?php echo isset($select_result['Medicine_Advice2'])?$select_result['Patient_BP']:""; ?>
 </div>
</td>
</tr>
<tr>
  <td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<div class="nb56ty">
 <label for="other">Medicine Advice3:</label>
  <?php echo isset($select_result['Medicine_Advice3'])?$select_result['Medicine_Advice3']:""; ?>
 </div>
</td>
</tr>
<tr>
  <td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<div class="nb56ty">
 <label for="other">Medicine Advice4:</label>
 <?php echo isset($select_result['Medicine_Advice4'])?$select_result['Medicine_Advice4']:""; ?>
</div>
</td>
</tr>

<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p>Inform on Day one of next cycle</p>
</td>

</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p>Continue thyroid/antihypertensive/diabetes /other medical disorder medications as advised</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p>To report if giddiness /nausea/vomiting/vaginal bleeding/pain/fever /purulent discharge immediately</p>
</td>
</tr>

<tr>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<label for="BP"><b>Follow Up Advice:</b> Review with DR.</label>
 <?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>

</td>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<label for="followup">on</label>
  <?php echo isset($select_result['advice'])?$select_result['advice']:""; ?>
</td>
</tr>

<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
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
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<label for="Sr IVF Consultant">Sr IVF Consultant</label>
<?php echo isset($select_result['IVF_Consultant'])?$select_result['IVF_Consultant']:""; ?>
</td>
</tr>

<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
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
<script type="text/javascript">
function printDischargeSummary() {
    // 1. प्रिंट एरिया (#print_this_section) का सारा कंटेंट वेरिएबल में लें
    var printContents = document.getElementById('print_this_section').innerHTML;
    
    // 2. वर्तमान पूरे पेज के कंटेंट का बैकअप लें
    var originalContents = document.body.innerHTML;

    // 3. बॉडी के कंटेंट को सिर्फ प्रिंट वाले लेआउट से बदलें
    document.body.innerHTML = printContents;
    
    // 4. प्रिंट एरिया के छुपे हुए (display:none) होने की वजह से उसे स्क्रीन पर 'block' करें
    var elements = document.getElementsByClassName('row');
    for(var i=0; i<elements.length; i++) {
        elements[i].style.display = 'block';
    }

    // 5. ब्राउज़र का प्रिंटर कमांड चालू करें
    window.print();

    // 6. प्रिंटर विजेट बंद होते ही पुराना पेज वापस लोड कर दें (ताकि बटन्स दोबारा काम करें)
    document.body.innerHTML = originalContents;
    window.location.reload(); 
}
</script> 