<?php 
$all_method =& get_instance();
$appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
               
        // चेक करें कि क्या इस iic_id का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `ovarian_cyst_aspiration_discharge_summary` WHERE iic_id='$iic_id'";
        $select_result = run_select_query($sql); 
        
        if(!empty($_POST['physical_examination']) && isset($_POST['physical_examination'])){
            $_POST['physical_examination'] = implode(',', $_POST['physical_examination']);
        }
        if(!empty($_POST['applicablemedicine']) && isset($_POST['applicablemedicine'])){
            $_POST['applicablemedicine'] = implode(',', $_POST['applicablemedicine']);
        }
    
        // अगर डेटा मौजूद नहीं है, सिर्फ तभी INSERT करें
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `ovarian_cyst_aspiration_discharge_summary` SET ";
            $sqlArr = array();
            
            foreach($_POST as $key => $value) {
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }   
            $query .= implode(',' , $sqlArr);
            
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
    
    // फॉर्म में पुराना डेटा दिखाने के लिए सिर्फ iic_id से फेच करें
    $sql = "SELECT * FROM `ovarian_cyst_aspiration_discharge_summary` WHERE iic_id='$iic_id'";
    $select_result = run_select_query($sql);
  
    $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$iic_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
  
    $sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result2['appoitment_for']."'";
    $select_result3 = run_select_query($sql3);  
  
    $sql5 = "Select * from ".$this->config->item('db_prefix')."doctors where ID='".$_SESSION['logged_doctor']['doctor_id']."'";
    $select_result5 = run_select_query($sql5);  
?>

<?php 
    $physical = $applicablemedicine = array();
    if(!empty($select_result['physical_examination'])){
        $physical = explode(',', $select_result['physical_examination']);
    }
    if(!empty($select_result['applicablemedicine'])){
        $applicablemedicine = explode(',', $select_result['applicablemedicine']);
    }
?>

<div class="ga-pro">
<h3>Discharge Summary</h3>
<form action="" enctype='multipart/form-data' method="post">

 <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
   <input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">
<input type="hidden" value="<?php echo $iic_id; ?>" class="form" name="iic_id">
<input type="hidden" name="appointment_id" value="<?php echo $select_result1['ID']; ?>" />
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
<strong>IIC ID: <?php echo $iic_id; ?></strong>
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
<strong>Name of Procedure : Ovarian Cyst Aspiration</strong>
</td>
<td colspan="3" width="50%">
<strong>Date of procedure:  <input type="date" class="date_of_procedure" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>">   </strong>



</td>
</tr>
</tbody>
</table> 

<div class="sec2">
<p><strong>Physical Examination: </strong></p>
<p><input type="radio" id="Conscious" name="Conscious" value="Conscious" <?php if(isset($select_result['Conscious']) && $select_result['Conscious'] == "Conscious"){ echo "checked";} ?>>
  <label for="Conscious">Conscious</label><br>
  <input type="radio" id="oriented" name="Conscious" value="oriented"  value="Conscious" <?php if(isset($select_result['Conscious']) && $select_result['Conscious'] == "oriented"){ echo "checked";} ?>>
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
  <input type="text" class="CVS" name="Patient_CVS"  value="<?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?>"><br>
 <label for="RS">RS</label>
  <input type="text" class="RS" name="Patient_RS" value="<?php echo isset($select_result['Patient_RS'])?$select_result['Patient_RS']:""; ?>"><br>
 <label for="P/A">P/A</label>
  <input type="text" class="PA" name="Patient_PA" value="<?php echo isset($select_result['Patient_PA'])?$select_result['Patient_PA']:""; ?>"><br>
<label for="CNS">CNS</label>
  <input type="text" class="CNS" name="Patient_CNS" value="<?php echo isset($select_result['Patient_CNS'])?$select_result['Patient_CNS']:""; ?>">
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
  <h5 style="border: 1px solid #000; margin: 0;padding: 10px 10px;">POST OF INSTRUCTION</h5>
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
<p>Consume Diet rich in green leafy vegetables, beans, pulses, high protein diet</p>
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

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CEROXITUM (500 mg)" <?php if(!empty($select_result['applicablemedicine']) && in_array('CEROXITUM (500 mg)',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">CEROXITUM (500 mg) 1 TAB twice daily one morning one evening after meals for 5 days</label>

</td>
</tr>
<tr>
 <td width="100%">

 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Cap Pantoprazole (40 mg)" <?php if(!empty($select_result['applicablemedicine']) && in_array('Cap Pantoprazole (40 mg)',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Pantoprazole (40 mg) 1 CAP once daily in empty stomach for 5 days</label>

</td>
</tr>
<tr>
<td width="100%">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Tab Crocin (500 mg)" <?php if(!empty($select_result['applicablemedicine']) && in_array('Tab Crocin (500 mg)',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Tab Crocin (500 mg) 1 TAB thrice daily eight hourly after meals for 2 days</label>
</td>
</tr>
<tr><td width="100%">There are No Substitutes</td></tr>
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
</tbody>
</table>

</div>

<div class="sec2">
<ul>
<li>Continue thyroid /antihypertensive/ diabetes medications as have been taking previously.</li>
<li>To report in emergency of the hospital near by immediately if patient has abdominal pain/ vaginal bleeding/ fever /excessive cough /giddiness /vomiting/nausea/purulent discharge.</li>
<li>To take soft diet on the day of ovarian cyst aspiration.</li>
<li>To resume normal diet after one day of ovarian cyst aspiration.</li>
</ul>
</div> 

<div class="sec2" style="display: flex; padding-top: 5px;">
 <label for="BP"><b>Follow Up Advice:</b> Review with DR.</label>
  <input type="text" class="followup" name="Doctor_name" value="<?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>"> 
<br>
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
</form>


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
<tr style="background: #b3b9b7;" >
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of Admission:</strong>
<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?></strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of Discharge: <?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?></strong>
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
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Husband&rsquo;s name : <?php echo $patient_data['husband_name']; ?> </strong>
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
 <textarea style="width:100%; height:80px!important;" > <?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?> </textarea>
</strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Final Diagnosis:
 <textarea style="width:100%; height:80px!important;" > <?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Name of Procedure : Ovarian Cyst Aspiration</strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of procedure:  <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?></strong>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
<p><strong>Physical Examination: </strong></p>
<p><input type="radio" id="Conscious" name="Conscious" value="Conscious" <?php if(isset($select_result['Conscious']) && $select_result['Conscious'] == "Conscious"){ echo "checked";} ?>>
  <label for="Conscious">Conscious</label><br>
  <input type="radio" id="oriented" name="Conscious" value="oriented"  value="Conscious" <?php if(isset($select_result['Conscious']) && $select_result['Conscious'] == "oriented"){ echo "checked";} ?>>
  <label for="oriented">oriented</label><br>  
</p>
</td>
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
<input type="checkbox" class="lymphadenopathy" name="physical_examination[]" value="lymphadenopathy" <?php if(!empty($select_result['physical_examination']) && in_array('lymphadenopathy',$physical)){echo "checked";}?>>
 <label for="Condition">lymphadenopathy</label>
 <input type="checkbox" class="oedema" name="physical_examination[]" value="pedal oedema" <?php if(!empty($select_result['physical_examination']) && in_array('pedal oedema',$physical)){echo "checked";}?>>
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
<label for="PR">PR (min) <?php echo isset($select_result['Patient_PR'])?$select_result['Patient_PR']:""; ?></label>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="BP">RR (min)</label>
<?php echo isset($select_result['Patient_RR'])?$select_result['Patient_RR']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="PR">Temp (F) </label><?php echo isset($select_result['Patient_Temp'])?$select_result['Patient_Temp']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="BP">SPO2 (on room air)</label>
<?php echo isset($select_result['Patient_SPO2'])?$select_result['Patient_SPO2']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="PR">CVS </label><?php echo isset($select_result['Patient_CVS'])?$select_result['Patient_CVS']:""; ?>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="BP">RS</label>
<?php echo isset($select_result['Patient_RS'])?$select_result['Patient_RS']:""; ?>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<label for="PR">P/A</label><?php echo isset($select_result['Patient_PA'])?$select_result['Patient_PA']:""; ?>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
<label for="PR">CNS</label><?php echo isset($select_result['Patient_CNS'])?$select_result['Patient_CNS']:""; ?>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
<label for="PR">Course in the hospital:</label><?php echo isset($select_result['Patient_Course'])?$select_result['Patient_Course']:""; ?>
</td>
</tr>

<tr>
<td colspan="6" width="100%" style="border:1px solid;padding:5px;">
<label for="PR">Condition at Discharge:</label><?php echo isset($select_result['Patient_Condition'])?$select_result['Patient_Condition']:""; ?>
</td>
</tr>
</tbody>
</table> 

<div class="sec3">
  <h5 style="border: 1px solid #000; margin: 0;padding: 10px 10px;">POST OF INSTRUCTION</h5>
  <table width="100%">
<tbody>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
<p>Regular exercise daily for one hour</p>
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
<p>Consume Diet rich in green leafy vegetables, beans, pulses, high protein diet</p>
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
<?php if(!empty($select_result['applicablemedicine']) && in_array('CEROXITUM (500 mg)',$applicablemedicine)){ ?>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CEROXITUM (500 mg)" <?php if(!empty($select_result['applicablemedicine']) && in_array('CEROXITUM (500 mg)',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">CEROXITUM (500 mg) 1 TAB twice daily one morning one evening after meals for 5 days</label>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Cap Pantoprazole (40 mg)',$applicablemedicine)){ ?>
<tr>
 <td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Cap Pantoprazole (40 mg)" <?php if(!empty($select_result['applicablemedicine']) && in_array('Cap Pantoprazole (40 mg)',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Cap Pantoprazole (40 mg) 1 CAP once daily in empty stomach for 5 days</label>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Tab Crocin (500 mg)',$applicablemedicine)){ ?>
<tr>
<td width="100%" colspan="4" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Tab Crocin (500 mg)" <?php if(!empty($select_result['applicablemedicine']) && in_array('Tab Crocin (500 mg)',$applicablemedicine)){echo "checked";}?>>
 <label for="Condition">Tab Crocin (500 mg) 1 TAB thrice daily eight hourly after meals for 2 days</label>
</td>
</tr>
<?php } ?>
<tr><td width="100%" colspan="4" style="border:1px solid;padding:5px;">There are No Substitutes</td></tr>
<tr>
<td style="border:1px solid;padding:5px;" colspan="4">
<div class="nb56ty">
 <label for="other">Other Medication1:</label>
  <?php echo isset($select_result['Other_Medication1'])?$select_result['Other_Medication1']:""; ?>
</div> 
 </td> 
</tr> 
<tr>
<td style="border:1px solid;padding:5px;" colspan="4">
<div class="nb56ty">
<label for="other">Other Medication2:</label>
  <?php echo isset($select_result['Other_Medication2'])?$select_result['Other_Medication2']:""; ?> 
  </div> 
 </td> 
</tr> 
<tr>
<td style="border:1px solid;padding:5px;" colspan="4">
<div class="nb56ty">
  <label for="other">Other Medication3:</label>
  <?php echo isset($select_result['Other_Medication3'])?$select_result['Other_Medication3']:""; ?>
</div> 
 </td> 
</tr> 
<tr>
<td style="border:1px solid;padding:5px;" colspan="4">
<div class="nb56ty">
  <label for="other">Other Medication4:</label>
  <?php echo isset($select_result['Other_Medication4'])?$select_result['Other_Medication4']:""; ?>
</div> 
 </td> 
</tr> 

<tr>
<td style="border:1px solid;padding:5px;" colspan="4">
<div class="nb56ty">
 <ul>
<li>Continue thyroid /antihypertensive/ diabetes medications as have been taking previously.</li>
<li>To report in emergency of the hospital near by immediately if patient has abdominal pain/ vaginal bleeding/ fever /excessive cough /giddiness /vomiting/nausea/purulent discharge.</li>
<li>To take soft diet on the day of ovarian cyst aspiration.</li>
<li>To resume normal diet after one day of ovarian cyst aspiration.</li>
</ul>
</div> 
 </td> 
</tr> 

<tr>
<td style="border:1px solid;padding:5px;" colspan="2">
<label for="BP"><b>Follow Up Advice:</b> Review with DR.</label>
  <?php echo isset($select_result['Doctor_name'])?$select_result['Doctor_name']:""; ?>

 </td> 
 <td style="border:1px solid;padding:5px;" colspan="2" width="30%">
<label for="followup">on</label>
  <?php echo isset($select_result['advice'])?$select_result['advice']:""; ?>
 </td> 
</tr> 

<tr>
<td style="border:1px solid;padding:5px;" colspan="4">
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
<td style="border:1px solid;padding:5px;" colspan="4">
 <label for="Sr IVF Consultant">Sr IVF Consultant</label>
  <?php echo isset($select_result['IVF_Consultant'])?$select_result['IVF_Consultant']:""; ?>
 </td> 
</tr> 

<tr>
<td style="border:1px solid;padding:5px;" colspan="4">
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