<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
		if(!empty($_FILES['male_photographs']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['male_photographs']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['male_photographs']['tmp_name'], $destination.$NewImageName);
			$_POST['male_photographs'] = $transaction_img;
		}
        
        $select_query = "SELECT * FROM `donor_sperm_personal_details` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `donor_sperm_personal_details` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE donor_sperm_personal_details SET ";
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".$value."'"	;
            }
            $query .= implode(',' , $sqlArr);
            $query .= " WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        }
        $result = run_form_query($query);        

        if($result){
          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Procedure form inserted!').'&t='.base64_encode('success'));
					die();
        }else{
          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
					die();
        }
    }
    $select_query = "SELECT * FROM `donor_sperm_personal_details` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);  
	
?>

<!-- <?php
	// php code to Insert data into mysql database from input text
	// if(isset($_POST['submit'])){
	// 	$patient_id = $_POST['patient_id'];
 //        $receipt_number = $_POST['receipt_number'];
	// 	$status = $_POST['status'];
	// 	// get values form input text and number
	// 	$male_art_bank = $_POST['male_art_bank'];
	// 	$male_id_number = $_POST['male_id_number'];
	// 	$male_photographs = $_POST['male_photographs'];
	// 	$male_name = $_POST['male_name'];
	// 	$male_age = $_POST['male_age'];
	// 	$male_nationality = $_POST['male_nationality'];
	// 	$male_mobile_no = $_POST['male_mobile_no'];
	// 	$male_email_address = $_POST['male_email_address'];
	// 	$male_present_address = $_POST['male_present_address'];
	// 	$male_permanent_address = $_POST['male_permanent_address'];
	// 	$male_aadhar_number = $_POST['male_aadhar_number'];
	// 	$male_occupation = $_POST['male_occupation'];
	// 	$male_total_pregnancies = $_POST['male_total_pregnancies'];
	// 	$male_live_births = $_POST['male_live_births'];
	// 	$male_spontaneous_abortions = $_POST['male_spontaneous_abortions'];
	// 	$male_termination_of_pregnancy = $_POST['male_termination_of_pregnancy'];
	// 	$male_still_births = $_POST['male_still_births'];
	// 	$male_ectopic_pregnancy = $_POST['male_ectopic_pregnancy'];
	// 	$male_history_of_abnormality = $_POST['male_history_of_abnormality'];
	// 	$male_others = $_POST['male_others'];
	// 	$heart_attack = $_POST['heart_attack'];
	// 	$pacemaker = $_POST['pacemaker'];
	// 	$other_heart_disease = $_POST['other_heart_disease'];
	// 	$high_blood_pressure = $_POST['high_blood_pressure'];
	// 	$blood_clots = $_POST['blood_clots'];
	// 	$chest_pain = $_POST['chest_pain'];
	// 	$stroke = $_POST['stroke'];
	// 	$asthma = $_POST['asthma'];
	// 	$lung_disease = $_POST['lung_disease'];
	// 	$difficulty_breathing = $_POST['difficulty_breathing'];
	// 	$snoring = $_POST['snoring'];
	// 	$epilepsy = $_POST['epilepsy'];
	// 	$fainting_spells = $_POST['fainting_spells'];
	// 	$diabetes = $_POST['diabetes'];
	// 	$muscle_disorders = $_POST['muscle_disorders'];
	// 	$kidney_disease = $_POST['kidney_disease'];
	// 	$hepatitis = $_POST['hepatitis'];
	// 	$tuberculosis = $_POST['tuberculosis'];
	// 	$hiv = $_POST['hiv'];
	// 	$heart_burn = $_POST['heart_burn'];
	// 	$cancer = $_POST['cancer'];
	// 	$blood_disorders = $_POST['blood_disorders'];
	// 	$rheumatic_disease = $_POST['rheumatic_disease'];
	// 	$psychiatric_disorder = $_POST['psychiatric_disorder'];
	// 	$thyroid_disorder = $_POST['thyroid_disorder'];
	// 	$urinary_infection = $_POST['urinary_infection'];
	// 	$sexually_transmitted = $_POST['sexually_transmitted'];
	// 	$abdominal_operations = $_POST['abdominal_operations'];
	// 	$other_operations = $_POST['other_operations'];
	// 	$medications = $_POST['medications'];
	// 	$environmental_factors = $_POST['environmental_factors'];
	// 	$dentures = $_POST['dentures'];
	// 	$loose_teeth = $_POST['loose_teeth'];
	// 	$hearing_aid = $_POST['hearing_aid'];
	// 	$caps_on_front_teeth = $_POST['caps_on_front_teeth'];
	// 	$contact_lenses = $_POST['contact_lenses'];
	// 	$body_piercing = $_POST['body_piercing'];
	// 	$blood_transfusion = $_POST['blood_transfusion'];
	// 	$traffic_accident = $_POST['traffic_accident'];
	// 	$smoke_past = $_POST['smoke_past'];
	// 	$smoke_present = $_POST['smoke_present'];
	// 	$drink_past = $_POST['drink_past'];
	// 	$drink_present = $_POST['drink_present'];
	// 	$abusive_drugs = $_POST['abusive_drugs'];
	// 	$steroid = $_POST['steroid'];
	// 	$medication = $_POST['medication'];
	// 	$herbal_products = $_POST['herbal_products'];
	// 	$eye_drops = $_POST['eye_drops'];
	// 	$non_prescription_drugs = $_POST['non_prescription_drugs'];
	// 	$maternal_diabetes = $_POST['maternal_diabetes'];
	// 	$paternal_diabetes = $_POST['paternal_diabetes'];
	// 	$maternal_thrombo_embolism = $_POST['maternal_thrombo_embolism'];
	// 	$paternal_thrombo_embolism = $_POST['paternal_thrombo_embolism'];
	// 	$maternal_metabolic = $_POST['maternal_metabolic'];
	// 	$paternal_metabolic = $_POST['paternal_metabolic'];
	// 	$maternal_urinary_tract = $_POST['maternal_urinary_tract'];
	// 	$paternal_urinary_tract = $_POST['paternal_urinary_tract'];
	// 	$maternal_neurological = $_POST['maternal_neurological'];
	// 	$paternal_neurological = $_POST['paternal_neurological'];
	// 	$maternal_malignancy = $_POST['maternal_malignancy'];
	// 	$paternal_malignancy = $_POST['paternal_malignancy'];
	// 	$nutritional_assessment = $_POST['nutritional_assessment'];
	// 	$psychological_assessment = $_POST['psychological_assessment'];
	// 	$anxious = $_POST['anxious'];
	// 	$pulse = $_POST['pulse'];
	// 	$blood_pressure = $_POST['blood_pressure'];
	// 	$cvs = $_POST['cvs'];
	// 	$chest = $_POST['chest'];
	// 	$abdomen = $_POST['abdomen'];
	// 	$others = $_POST['others'];
	// 	$adviced_blood_group = $_POST['adviced_blood_group'];
	// 	$adviced_tsh = $_POST['adviced_tsh'];
	// 	$adviced_cbc = $_POST['adviced_cbc'];
	// 	$adviced_microscopy = $_POST['adviced_microscopy'];
	// 	$adviced_blood_sugar = $_POST['adviced_blood_sugar'];
	// 	$adviced_hb_electrophoresis = $_POST['adviced_hb_electrophoresis'];
	// 	$adviced_hiv = $_POST['adviced_hiv'];
	// 	$adviced_hbsag = $_POST['adviced_hbsag'];
	// 	$adviced_hepc = $_POST['adviced_hepc'];
	// 	$adviced_vdrl = $_POST['adviced_vdrl'];
	// 	$adviced_others = $_POST['adviced_others'];
	// 	$date = $_POST['date'];
	// 	$time = $_POST['time'];
	// 	$doctor_sign = $_POST['doctor_sign'];
	// 	$impairments = $_POST['impairments'];
	// 	$heart_attack_text = $_POST['heart_attack_text'];
	// 	$pacemaker_text = $_POST['pacemaker_text'];
	// 	$other_heart_disease_text = $_POST['other_heart_disease_text'];
	// 	$high_blood_pressure_text = $_POST['high_blood_pressure_text'];
	// 	$blood_clots_text = $_POST['blood_clots_text'];
	// 	$chest_pain_text = $_POST['chest_pain_text'];
	// 	$stroke_text = $_POST['stroke_text'];
	// 	$asthma_text = $_POST['asthma_text'];
	// 	$lung_disease_text = $_POST['lung_disease_text'];
	// 	$difficulty_breathing_text = $_POST['difficulty_breathing_text'];
	// 	$snoring_text = $_POST['snoring_text'];
	// 	$epilepsy_text = $_POST['epilepsy_text'];
	// 	$fainting_spells_text = $_POST['fainting_spells_text'];
	// 	$diabetes_text = $_POST['diabetes_text'];
	// 	$muscle_disorders_text = $_POST['muscle_disorders_text'];
	// 	$kidney_disease_text = $_POST['kidney_disease_text'];
	// 	$hepatitis_text = $_POST['hepatitis_text'];
	// 	$tuberculosis_text = $_POST['tuberculosis_text'];
	// 	$hiv_text = $_POST['hiv_text'];
	// 	$heart_burn_text = $_POST['heart_burn_text'];
	// 	$cancer_text = $_POST['cancer_text'];
	// 	$blood_disorders_text = $_POST['blood_disorders_text'];
	// 	$rheumatic_disease_text = $_POST['rheumatic_disease_text'];
	// 	$psychiatric_disorder_text = $_POST['psychiatric_disorder_text'];
	// 	$thyroid_disorder_text = $_POST['thyroid_disorder_text'];
	// 	$urinary_infection_text = $_POST['urinary_infection_text'];
	// 	$sexually_transmitted_text = $_POST['sexually_transmitted_text'];
	// 	$abdominal_operations_text = $_POST['abdominal_operations_text'];
	// 	$other_operations_text = $_POST['other_operations_text'];
	// 	$medications_text = $_POST['medications_text'];
	// 	$environmental_factors_text = $_POST['environmental_factors_text'];
	// 	$dentures_text = $_POST['dentures_text'];
	// 	$loose_teeth_text = $_POST['loose_teeth_text'];
	// 	$hearing_aid_text = $_POST['hearing_aid_text'];
	// 	$caps_on_front_teeth_text = $_POST['caps_on_front_teeth_text'];
	// 	$contact_lenses_text = $_POST['contact_lenses_text'];
	// 	$body_piercing_text = $_POST['body_piercing_text'];
	// 	$blood_transfusion_text = $_POST['blood_transfusion_text'];
	// 	$traffic_accident_text = $_POST['traffic_accident_text'];
	// 	$smoke_past_text = $_POST['smoke_past_text'];
	// 	$smoke_present_text = $_POST['smoke_present_text'];
	// 	$drink_past_text = $_POST['drink_past_text'];
	// 	$drink_present_text = $_POST['drink_present_text'];
	// 	$abusive_drugs_text = $_POST['abusive_drugs_text'];
	// 	$steroid_text = $_POST['steroid_text'];
	// 	$medication_text = $_POST['medication_text'];
	// 	$herbal_products_text = $_POST['herbal_products_text'];
	// 	$eye_drops_text = $_POST['eye_drops_text'];
	// 	$non_prescription_drugs_text = $_POST['non_prescription_drugs_text'];
	// 	$maternal_diabetes_text = $_POST['maternal_diabetes_text'];
	// 	$paternal_diabetes_text = $_POST['paternal_diabetes_text'];
	// 	$maternal_thrombo_embolism_text = $_POST['maternal_thrombo_embolism_text'];
	// 	$paternal_thrombo_embolism_text = $_POST['paternal_thrombo_embolism_text'];
	// 	$maternal_metabolic_text = $_POST['maternal_metabolic_text'];
	// 	$paternal_metabolic_text = $_POST['paternal_metabolic_text'];
	// 	$maternal_urinary_tract_text = $_POST['maternal_urinary_tract_text'];
	// 	$paternal_urinary_tract_text = $_POST['paternal_urinary_tract_text'];
	// 	$maternal_neurological_text = $_POST['maternal_neurological_text'];
	// 	$paternal_neurological_text = $_POST['paternal_neurological_text'];
	// 	$maternal_malignancy_text = $_POST['maternal_malignancy_text'];
	// 	$paternal_malignancy_text = $_POST['paternal_malignancy_text'];
	// 	$male_ethnicity = $_POST['male_ethnicity'];
	// 	$ft4 = $_POST['ft4'];
	// 	$hba1c = $_POST['hba1c'];
	// 	$temperature = $_POST['temperature'];

		// connect to mysql database using mysqli
		
		
		// mysql query to insert data
		//$query = "INSERT INTO `donor_sperm_personal_details`(`patient_id`, `receipt_number`, `status`,`male_nationality`,`male_age`,`male_name`,`male_photographs`,`male_id_number`,`male_art_bank`,`male_mobile_no`,`male_email_address`,`male_present_address`,`male_permanent_address`,`male_aadhar_number`,`male_occupation`,`male_total_pregnancies`,`male_live_births`,`male_spontaneous_abortions`,`male_termination_of_pregnancy`,`male_still_births`,`male_ectopic_pregnancy`,`male_history_of_abnormality`,`male_others`,`past_medical_history`,`pacemaker`,`other_heart_disease`,`high_blood_pressure`,`blood_clots`,`chest_pain`,`stroke`,`asthma`,`lung_disease`,`difficulty_breathing`,`snoring`,`epilepsy`,`fainting_spells`,`diabetes`,`muscle_disorders`,`kidney_disease`,`hepatitis`,`tuberculosis`,`hiv`,`heart_burn`,`cancer`,`blood_disorders`,`rheumatic_disease`,`psychiatric_disorder`,`thyroid_disorder`,`urinary_infection`,`sexually_transmitted`,`abdominal_operations`,`other_operations`,`medications`,`environmental_factors`,`dentures`,`loose_teeth`,`hearing_aid`,`caps_on_front_teeth`,`contact_lenses`,`body_piercing`,`blood_transfusion`,`traffic_accident`,`smoke_past`,`smoke_present`,`drink_past`,`drink_present`,`abusive_drugs`,`steroid`,`medication`,`herbal_products`,`eye_drops`,`non_prescription_drugs`,`maternal_diabetes`,`paternal_diabetes`,`maternal_thrombo_embolism`,`paternal_thrombo_embolism`,`maternal_metabolic`,`paternal_metabolic`,`maternal_urinary_tract`,`paternal_urinary_tract`,`maternal_neurological`,`paternal_neurological`,`maternal_malignancy`,`paternal_malignancy`,`nutritional_assessment`,`psychological_assessment`,`anxious`,`pulse`,`blood_pressure`,`cvs`,`chest`,`abdomen`,`others`,`adviced_blood_group`,`adviced_tsh`,`adviced_cbc`,`adviced_microscopy`,`adviced_blood_sugar`,`adviced_hb_electrophoresis`,`adviced_hiv`,`adviced_hbsag`,`adviced_hepc`,`adviced_vdrl`,`adviced_others`,`date`,`time`,`doctor_sign`,`impairments`,`heart_attack_text`,`pacemaker_text`,`other_heart_disease_text`,`high_blood_pressure_text`,`blood_clots_text`,`chest_pain_text`,`stroke_text`,`asthma_text`,`lung_disease_text`,`difficulty_breathing_text`,`snoring_text`,`epilepsy_text`,`fainting_spells_text`,`diabetes_text`,`muscle_disorders_text`,`kidney_disease_text`,`hepatitis_text`,`tuberculosis_text`,`hiv_text`,`heart_burn_text`,`cancer_text`,`blood_disorders_text`,`rheumatic_disease_text`,`psychiatric_disorder_text`,`thyroid_disorder_text`,`urinary_infection_text`,`sexually_transmitted_text`,`abdominal_operations_text`,`other_operations_text`,`medications_text`,`environmental_factors_text`,`dentures_text`,`loose_teeth_text`,`hearing_aid_text`,`caps_on_front_teeth_text`,`contact_lenses_text`,`body_piercing_text`,`blood_transfusion_text`,`traffic_accident_text`,`smoke_past_text`,`smoke_present_text`,`drink_past_text`,`drink_present_text`,`abusive_drugs_text`,`steroid_text`,`medication_text`,`herbal_products_text`,`eye_drops_text`,`non_prescription_drugs_text`,`maternal_diabetes_text`,`paternal_diabetes_text`,`maternal_thrombo_embolism_text`,`paternal_thrombo_embolism_text`,`maternal_metabolic_text`,`paternal_metabolic_text`,`maternal_urinary_tract_text`,`paternal_urinary_tract_text`,`maternal_neurological_text`,`paternal_neurological_text`,`maternal_malignancy_text`,`paternal_malignancy_text`,`male_ethnicity`,`ft4`,`hba1c`,`temperature`) VALUES ('$patient_id','$receipt_number','$status','$male_nationality','$male_age','$male_name','$male_photographs','$male_id_number','$male_art_bank','$male_mobile_no','$male_email_address','$male_present_address','$male_permanent_address','$male_aadhar_number','$male_occupation','$male_total_pregnancies','$male_live_births','$male_spontaneous_abortions','$male_termination_of_pregnancy','$male_still_births','$male_ectopic_pregnancy','$male_history_of_abnormality','$male_others','$heart_attack','$pacemaker','$other_heart_disease','$high_blood_pressure','$blood_clots','$chest_pain','$stroke','$asthma','$lung_disease','$difficulty_breathing','$snoring','$epilepsy','$fainting_spells','$diabetes','$muscle_disorders','$kidney_disease','$hepatitis','$tuberculosis','$hiv','$heart_burn','$cancer','$blood_disorders','$rheumatic_disease','$psychiatric_disorder','$thyroid_disorder','$urinary_infection','$sexually_transmitted','$abdominal_operations','$other_operations','$medications','$environmental_factors','$dentures','$loose_teeth','$hearing_aid','$caps_on_front_teeth','$contact_lenses','$body_piercing','$blood_transfusion','$traffic_accident','$smoke_past','$smoke_present','$drink_past','$drink_present','$abusive_drugs','$steroid','$medication','$herbal_products','$eye_drops','$non_prescription_drugs','$maternal_diabetes','$paternal_diabetes','$maternal_thrombo_embolism','$paternal_thrombo_embolism','$maternal_metabolic','$paternal_metabolic','$maternal_urinary_tract','$paternal_urinary_tract','$maternal_neurological','$paternal_neurological','$maternal_malignancy','$paternal_malignancy','$nutritional_assessment','$psychological_assessment','$anxious','$pulse','$blood_pressure','$cvs','$chest','$abdomen','$others','$adviced_blood_group','$adviced_tsh','$adviced_cbc','$adviced_microscopy','$adviced_blood_sugar','$adviced_hb_electrophoresis','$adviced_hiv','$adviced_hbsag','$adviced_hepc','$adviced_vdrl','$adviced_others','$date','$time','$doctor_sign','$impairments','$heart_attack_text','$pacemaker_text','$other_heart_disease_text','$high_blood_pressure_text','$blood_clots_text','$chest_pain_text','$stroke_text','$asthma_text','$lung_disease_text','$difficulty_breathing_text','$snoring_text','$epilepsy_text','$fainting_spells_text','$diabetes_text','$muscle_disorders_text','$kidney_disease_text','$hepatitis_text','$tuberculosis_text','$hiv_text','$heart_burn_text','$cancer_text','$blood_disorders_text','$rheumatic_disease_text','$psychiatric_disorder_text','$thyroid_disorder_text','$urinary_infection_text','$sexually_transmitted_text','$abdominal_operations_text','$other_operations_text','$medications_text','$environmental_factors_text','$dentures_text','$loose_teeth_text','$hearing_aid_text','$caps_on_front_teeth_text','$contact_lenses_text','$body_piercing_text','$blood_transfusion_text','$traffic_accident_text','$smoke_past_text','$smoke_present_text','$drink_past_text','$drink_present_text','$abusive_drugs_text','$steroid_text','$medication_text','$herbal_products_text','$eye_drops_text','$non_prescription_drugs_text','$maternal_diabetes_text','$paternal_diabetes_text','$maternal_thrombo_embolism_text','$paternal_thrombo_embolism_text','$maternal_metabolic_text','$paternal_metabolic_text','$maternal_urinary_tract_text','$paternal_urinary_tract_text','$maternal_neurological_text','$paternal_neurological_text','$maternal_malignancy_text','$paternal_malignancy_text','$male_ethnicity','$ft4','$hba1c','$temperature')";

//		$result = run_form_query($query);

 //        if($result){
 //          header("location:" .base_url(). "procedure_reports/".$appointment_id."?m=".base64_encode('Procedure form inserted!').'&t='.base64_encode('success'));
	// 				die();
 //        }else{
 //          header("location:" .base_url(). "procedure_reports/".$appointment_id."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
	// 				die();
 //        }
	// }
?> -->

<form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
    <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
  <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
  <input type="hidden" value="pending" name="status"> 
  
    <table class="table-bordered" width="100%">
		<tr>
			<td colspan="2">
			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
			            ){?>
			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
			    <?php } ?>
			</td>
		</tr>
	</table>
			
	<table class="table-bordered" width="100%">
		<tr>
			<th></th>
			<td><b>FEMALE</b></td>
			<td><b>MALE</b></td>
		</tr>
		<tr>
			<th>ART BANK</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_art_bank'])?$select_result['male_art_bank']:""; ?>"  name="male_art_bank" class="form-control"></td>
		</tr>
		<tr>
			<th>ID NUMBER</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_id_number'])?$select_result['male_id_number']:""; ?>"  name="male_id_number" class="form-control"></td>
		</tr>
		<tr>
			<th>PHOTOGRAPHS</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="file" value="<?php echo isset($select_result['male_photographs'])?$select_result['male_photographs']:""; ?>" name="male_photographs" class="form-control">
			<a target="_blank" href="<?php echo !empty($select_result['male_photographs'])?$select_result['male_photographs']:"javascript:void(0)"; ?>">Download</a>
			</td>
		</tr>
		<tr>
			<th>NAME</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_name'])?$select_result['male_name']:""; ?>"  name="male_name" class="form-control"></td>
		</tr>
		<tr>
			<th>AGE</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="number" value="<?php echo isset($select_result['male_age'])?$select_result['male_age']:""; ?>"  name="male_age" class="form-control"></td>
		</tr>
		<tr>
			<th>OCCUPATION</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_occupation'])?$select_result['male_occupation']:""; ?>"  maxlength="15" name="male_occupation" class="form-control"></td>
		</tr>
		<tr>
			<th>NATIONALITY</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_nationality'])?$select_result['male_nationality']:""; ?>"  name="male_nationality" class="form-control"></td>
		</tr>
		<tr>
			<th>ETHNICITY</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_ethnicity'])?$select_result['male_ethnicity']:""; ?>"  name="male_ethnicity" class="form-control"></td>
		</tr>
		<tr>
			<th>AADHAR NUMBER</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_aadhar_number'])?$select_result['male_aadhar_number']:""; ?>"  maxlength="50" name="male_aadhar_number" class="form-control"></td>
		</tr>
		<tr>
			<th>PRESENT ADDRESS</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_present_address'])?$select_result['male_present_address']:""; ?>"  maxlength="50" name="male_present_address" class="form-control"></td>
		</tr>
		<tr>
			<th>PERMANENT ADDRESS</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_permanent_address'])?$select_result['male_permanent_address']:""; ?>"  maxlength="50" name="male_permanent_address" class="form-control"></td>
		</tr>
		<tr>
			<th>MOBILE NO</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_mobile_no'])?$select_result['male_mobile_no']:""; ?>"  maxlength="50" name="male_mobile_no" class="form-control"></td>
		</tr>
		<tr>
			<th>EMAIL ADDRESS</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;"><input type="text"  value="<?php echo isset($select_result['male_email_address'])?$select_result['male_email_address']:""; ?>"  maxlength="50" name="male_email_address" class="form-control"></td>
		</tr>
		<tr>
			<th>WT/HT/BMI</th>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<th>H/O previous pregnancies(IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
			<td style="padding: 0;"></td>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Total pregnancies</td>
						<td><input type="number" value="<?php echo isset($select_result['male_total_pregnancies'])?$select_result['male_total_pregnancies']:""; ?>"  max="20" min="0" name="male_total_pregnancies" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of live births</td>
						<td><input type="number" value="<?php echo isset($select_result['male_live_births'])?$select_result['male_live_births']:""; ?>"  max="20" min="0" name="male_live_births" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of spontaneous abortions</td>
						<td><input type="number" value="<?php echo isset($select_result['male_spontaneous_abortions'])?$select_result['male_spontaneous_abortions']:""; ?>"  max="20" min="0" name="male_spontaneous_abortions" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of termination of pregnancy</td>
						<td><input type="number" value="<?php echo isset($select_result['male_termination_of_pregnancy'])?$select_result['male_termination_of_pregnancy']:""; ?>"  max="20" min="0" name="male_termination_of_pregnancy" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of still births</td>
						<td><input type="number" value="<?php echo isset($select_result['male_still_births'])?$select_result['male_still_births']:""; ?>"  max="20" min="0" name="male_still_births" class="form-control"></td>
					</tr>
					<tr>
						<td>No. of ectopic pregnancy</td>
						<td><input type="number" value="<?php echo isset($select_result['male_ectopic_pregnancy'])?$select_result['male_ectopic_pregnancy']:""; ?>"  max="20" min="0" name="male_ectopic_pregnancy" class="form-control"></td>
					</tr>
					<tr>
						<td>History of any abnormality in child</td>
						<td>
							<input type="radio" checked name="male_history_of_abnormality" value="Yes" <?php if(isset($select_result['male_history_of_abnormality']) && $select_result['male_history_of_abnormality'] == "Yes"){echo 'checked="checked"'; }?> > Yes
							<input type="radio" checked name="male_history_of_abnormality" value="No" <?php if(isset($select_result['male_history_of_abnormality']) && $select_result['male_history_of_abnormality'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['male_history_of_abnormality']) && $select_result['male_history_of_abnormality'] != "Yes"){echo 'checked="checked"';}?> > No
							<input type="text"  value="<?php echo isset($select_result['male_history_of_abnormality_text'])?$select_result['male_history_of_abnormality_text']:""; ?>"  maxlength="25" name="male_history_of_abnormality_text">
						</td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input type="text"  value="<?php echo isset($select_result['male_others'])?$select_result['male_others']:""; ?>"  name="male_others" class="form-control"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>PAST MEDICAL HISTORY</th>
			<td></td>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>
							<input type="radio" checked id="text1" name="heart_attack" value="Yes" <?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="heart_attack" value="No" <?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['heart_attack']) && $select_result['heart_attack'] = "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['heart_attack_text'])?$select_result['heart_attack_text']:""; ?>"  maxlength="25" name="heart_attack_text">
						</td>
						<td>Heart attack</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="pacemaker" value="Yes" <?php if(isset($select_result['pacemaker']) && $select_result['pacemaker'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="pacemaker" value="No" <?php if(isset($select_result['pacemaker']) && $select_result['pacemaker'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['pacemaker']) && $select_result['pacemaker'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['pacemaker_text'])?$select_result['pacemaker_text']:""; ?>"  maxlength="25" name="pacemaker_text">
						</td>
						<td>Pacemaker</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="other_heart_disease" value="Yes" <?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="other_heart_disease" value="No" <?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['other_heart_disease_text'])?$select_result['other_heart_disease_text']:""; ?>"  maxlength="25" name="other_heart_disease_text">
						</td>
						<td>Other heart disease</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="high_blood_pressure" value="Yes" <?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="high_blood_pressure" value="No" <?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['high_blood_pressure_text'])?$select_result['high_blood_pressure_text']:""; ?>"  maxlength="25" name="high_blood_pressure_text">
						</td>
						<td>High blood pressure</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="blood_clots" value="Yes" <?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="blood_clots" value="No" <?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['blood_clots']) && $select_result['blood_clots'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['blood_clots_text'])?$select_result['blood_clots_text']:""; ?>"  maxlength="25" name="blood_clots_text">
						</td>
						<td>Blood clots</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="chest_pain" value="Yes" <?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="chest_pain" value="No" <?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['chest_pain']) && $select_result['chest_pain'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['chest_pain_text'])?$select_result['chest_pain_text']:""; ?>"  maxlength="25" name="chest_pain_text">
						</td>
						<td>Chest pain</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="stroke" value="Yes" <?php if(isset($select_result['stroke']) && $select_result['stroke'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="stroke" value="No" <?php if(isset($select_result['stroke']) && $select_result['stroke'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['stroke']) && $select_result['stroke'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['stroke_text'])?$select_result['stroke_text']:""; ?>"  maxlength="25" name="stroke_text">
						</td>
						<td>Stroke</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="asthma" value="Yes" <?php if(isset($select_result['asthma']) && $select_result['asthma'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="asthma" value="No" <?php if(isset($select_result['asthma']) && $select_result['asthma'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['asthma']) && $select_result['asthma'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['asthma_text'])?$select_result['asthma_text']:""; ?>"  maxlength="25" name="asthma_text">
						</td>
						<td>Asthma</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="lung_disease" value="Yes" <?php if(isset($select_result['lung_disease']) && $select_result['lung_disease'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="lung_disease" value="No" <?php if(isset($select_result['lung_disease']) && $select_result['lung_disease'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['lung_disease']) && $select_result['lung_disease'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['lung_disease_text'])?$select_result['lung_disease_text']:""; ?>"  maxlength="25" name="lung_disease_text">
						</td>
						<td>Other lung disease</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="difficulty_breathing" value="Yes" <?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="difficulty_breathing" value="No" <?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['difficulty_breathing_text'])?$select_result['difficulty_breathing_text']:""; ?>"  maxlength="25" name="difficulty_breathing_text">
						</td>
						<td>Difficulty breathing</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="snoring" value="Yes" <?php if(isset($select_result['snoring']) && $select_result['snoring'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="snoring" value="No" <?php if(isset($select_result['snoring']) && $select_result['snoring'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['snoring']) && $select_result['snoring'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['snoring_text'])?$select_result['snoring_text']:""; ?>"  maxlength="25" name="snoring_text">
						</td>
						<td>Sleep apnea or snoring</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="epilepsy" value="Yes" <?php if(isset($select_result['epilepsy']) && $select_result['epilepsy'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="epilepsy" value="No" <?php if(isset($select_result['epilepsy']) && $select_result['epilepsy'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['epilepsy']) && $select_result['epilepsy'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['epilepsy_text'])?$select_result['epilepsy_text']:""; ?>"  maxlength="25" name="epilepsy_text">
						</td>
						<td>Epilepsy or seizures</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="fainting_spells" value="Yes" <?php if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="fainting_spells" value="No" <?php if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['fainting_spells_text'])?$select_result['fainting_spells_text']:""; ?>"  maxlength="25" name="fainting_spells_text">
						</td>
						<td>Fainting spells</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="diabetes" value="Yes" <?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="diabetes" value="No" <?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['diabetes']) && $select_result['diabetes'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>"  maxlength="25" name="diabetes_text">
						</td>
						<td>Diabetes</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="muscle_disorders" value="Yes" <?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="muscle_disorders" value="No" <?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['muscle_disorders_text'])?$select_result['muscle_disorders_text']:""; ?>"  maxlength="25" name="muscle_disorders_text">
						</td>
						<td>Muscle disorders</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="kidney_disease" value="Yes" <?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="kidney_disease" value="No" <?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['kidney_disease_text'])?$select_result['kidney_disease_text']:""; ?>"  maxlength="25" name="kidney_disease_text">
						</td>
						<td>Kidney disease</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="hepatitis" value="Yes" <?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="hepatitis" value="No" <?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['hepatitis']) && $select_result['hepatitis'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['hepatitis_text'])?$select_result['hepatitis_text']:""; ?>"  maxlength="25" name="hepatitis_text">
						</td>
						<td>Hepatitis</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="tuberculosis" value="Yes" <?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="tuberculosis" value="No" <?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['tuberculosis_text'])?$select_result['tuberculosis_text']:""; ?>"  maxlength="25" name="tuberculosis_text">
						</td>
						<td>Tuberculosis</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="hiv" value="Yes" <?php if(isset($select_result['hiv']) && $select_result['hiv'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="hiv" value="No" <?php if(isset($select_result['hiv']) && $select_result['hiv'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['hiv']) && $select_result['hiv'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['hiv_text'])?$select_result['hiv_text']:""; ?>"  maxlength="25" name="hiv_text">
						</td>
						<td>HIV</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="heart_burn" value="Yes" <?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="heart_burn" value="No" <?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['heart_burn']) && $select_result['heart_burn'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['heart_burn_text'])?$select_result['heart_burn_text']:""; ?>"  maxlength="25" name="heart_burn_text">
						</td>
						<td>Heart burn/reflux</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="cancer" value="Yes" <?php if(isset($select_result['cancer']) && $select_result['cancer'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="cancer" value="No" <?php if(isset($select_result['cancer']) && $select_result['cancer'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['cancer']) && $select_result['cancer'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['cancer_text'])?$select_result['cancer_text']:""; ?>"  maxlength="25" name="cancer_text">
						</td>
						<td>Cancer</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="blood_disorders" value="Yes" <?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="blood_disorders" value="No" <?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['blood_disorders_text'])?$select_result['blood_disorders_text']:""; ?>"  maxlength="25" name="blood_disorders_text">
						</td>
						<td>Blood disorders</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="rheumatic_disease" value="Yes" <?php if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="rheumatic_disease" value="No" <?php if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['rheumatic_disease_text'])?$select_result['rheumatic_disease_text']:""; ?>"  maxlength="25" name="rheumatic_disease_text">
						</td>
						<td>Rheumatic disease</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="psychiatric_disorder" value="Yes" <?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="psychiatric_disorder" value="No" <?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['psychiatric_disorder_text'])?$select_result['psychiatric_disorder_text']:""; ?>"  maxlength="25" name="psychiatric_disorder_text">
						</td>
						<td>Psychiatric disorder</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="thyroid_disorder" value="Yes" <?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="thyroid_disorder" value="No" <?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['thyroid_disorder_text'])?$select_result['thyroid_disorder_text']:""; ?>"  maxlength="25" name="thyroid_disorder_text">
						</td>
						<td>Thyroid disorder</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="urinary_infection" value="Yes" <?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="urinary_infection" value="No" <?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['urinary_infection_text'])?$select_result['urinary_infection_text']:""; ?>"  maxlength="25" name="urinary_infection_text">
						</td>
						<td>Urinary infection</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="sexually_transmitted" value="Yes" <?php if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="sexually_transmitted" value="No" <?php if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['sexually_transmitted_text'])?$select_result['sexually_transmitted_text']:""; ?>"  maxlength="25" name="sexually_transmitted_text">
						</td>
						<td>Sexually transmitted disease</td>
					</tr>
					<tr>
						<td><input type="text"  value="<?php echo isset($select_result['impairments'])?$select_result['impairments']:""; ?>"  maxlength="25" class="form-control" name="impairments"></td>
						<td>Other medical condition or impairments</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>PAST SURGICAL HISTORY</th>
			<td></td>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>
							<input type="radio" checked id="text1" name="abdominal_operations" value="Yes" <?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="abdominal_operations" value="No" <?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['abdominal_operations_text'])?$select_result['abdominal_operations_text']:""; ?>"  maxlength="25" name="abdominal_operations_text">
						</td>
						<td>Laparoscopy/pelvic/abdominal operations</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="other_operations" value="Yes" <?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="other_operations" value="No" <?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['other_operations']) && $select_result['other_operations'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['other_operations_text'])?$select_result['other_operations_text']:""; ?>"  maxlength="25" name="other_operations_text">
						</td>
						<td>Other operations</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Allergy history</th>
			<td></td>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>
							<input type="radio" checked id="text1" name="medications" value="Yes" <?php if(isset($select_result['medications']) && $select_result['medications'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="medications" value="No" <?php if(isset($select_result['medications']) && $select_result['medications'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['medications']) && $select_result['medications'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['medications_text'])?$select_result['medications_text']:""; ?>"  maxlength="25" name="medications_text">
						</td>
						<td>Medications</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="environmental_factors" value="Yes" <?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="environmental_factors" value="No" <?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['environmental_factors_text'])?$select_result['environmental_factors_text']:""; ?>"  maxlength="25" name="environmental_factors_text">
						</td>
						<td>environmental factors</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>SOCIAL & DRUG INTAKE HISTORY</th>
			<td></td>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td width="20%">
							<input type="radio" checked id="text1" name="dentures" value="Yes" <?php if(isset($select_result['dentures']) && $select_result['dentures'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="dentures" value="No" <?php if(isset($select_result['dentures']) && $select_result['dentures'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['dentures']) && $select_result['dentures'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['dentures_text'])?$select_result['dentures_text']:""; ?>"  maxlength="25" name="dentures_text">
						</td>
						<td>Dentures</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="loose_teeth" value="Yes" <?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="loose_teeth" value="No" <?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['loose_teeth_text'])?$select_result['loose_teeth_text']:""; ?>"  maxlength="25" name="loose_teeth_text">
						</td>
						<td>Loose teeth</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="hearing_aid" value="Yes" <?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="hearing_aid" value="No" <?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['hearing_aid_text'])?$select_result['hearing_aid_text']:""; ?>"  maxlength="25" name="hearing_aid_text">
						</td>
						<td>Hearing aid</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="caps_on_front_teeth" value="Yes" <?php if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="caps_on_front_teeth" value="No" <?php if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['caps_on_front_teeth_text'])?$select_result['caps_on_front_teeth_text']:""; ?>"  maxlength="25" name="caps_on_front_teeth_text">
						</td>
						<td>Caps on front teeth</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="contact_lenses" value="Yes" <?php if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="contact_lenses" value="No" <?php if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['contact_lenses_text'])?$select_result['contact_lenses_text']:""; ?>"  maxlength="25" name="contact_lenses_text">
						</td>
						<td>Contact lenses</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="body_piercing" value="Yes" <?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="body_piercing" value="No" <?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['body_piercing']) && $select_result['body_piercing'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['body_piercing_text'])?$select_result['body_piercing_text']:""; ?>"  maxlength="25" name="body_piercing_text">
						</td>
						<td>Body piercing</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="blood_transfusion" value="Yes" <?php if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="blood_transfusion" value="No" <?php if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['blood_transfusion_text'])?$select_result['blood_transfusion_text']:""; ?>"  maxlength="25" name="blood_transfusion_text">
						</td>
						<td>H/o blood transfusion</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="traffic_accident" value="Yes" <?php if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="traffic_accident" value="No" <?php if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['traffic_accident_text'])?$select_result['traffic_accident_text']:""; ?>"  maxlength="25" name="traffic_accident_text">
						</td>
						<td>H/o road traffic accident/any injury</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="smoke_past" value="Yes" <?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="smoke_past" value="No" <?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['smoke_past']) && $select_result['smoke_past'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['smoke_past_text'])?$select_result['smoke_past_text']:""; ?>"  maxlength="25" name="smoke_past_text">
						</td>
						<td>Smoke(past)daily</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="smoke_present" value="Yes" <?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="smoke_present" value="No" <?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['smoke_present']) && $select_result['smoke_present'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['smoke_present_text'])?$select_result['smoke_present_text']:""; ?>"  maxlength="25" name="smoke_present_text">
						</td>
						<td>Smoke(present)daily</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="drink_past" value="Yes" <?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="drink_past" value="No" <?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['drink_past']) && $select_result['drink_past'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['drink_past_text'])?$select_result['drink_past_text']:""; ?>"  maxlength="25" name="drink_past_text">
						</td>
						<td>Drink(past)units per week</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="drink_present" value="Yes" <?php if(isset($select_result['drink_present']) && $select_result['drink_present'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="drink_present" value="No" <?php if(isset($select_result['drink_present']) && $select_result['drink_present'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['drink_present']) && $select_result['drink_present'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['drink_present_text'])?$select_result['drink_present_text']:""; ?>"  maxlength="25" name="drink_present_text">
						</td>
						<td>Drink(present)units per week</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="abusive_drugs" value="Yes" <?php if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="abusive_drugs" value="No" <?php if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['abusive_drugs_text'])?$select_result['abusive_drugs_text']:""; ?>"  maxlength="25" name="abusive_drugs_text">
						</td>
						<td>Hashish/cocaine /abusive drugs</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="steroid" value="Yes" <?php if(isset($select_result['steroid']) && $select_result['steroid'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="steroid" value="No" <?php if(isset($select_result['steroid']) && $select_result['steroid'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['steroid']) && $select_result['steroid'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['steroid_text'])?$select_result['steroid_text']:""; ?>"  maxlength="25" name="steroid_text">
						</td>
						<td>Have you ever used cortisone/steroid</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="medication" value="Yes" <?php if(isset($select_result['medication']) && $select_result['medication'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="medication" value="No" <?php if(isset($select_result['medication']) && $select_result['medication'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['medication']) && $select_result['medication'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['medication_text'])?$select_result['medication_text']:""; ?>"  maxlength="25" name="medication_text">
						</td>
						<td>Medication</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="herbal_products" value="Yes" <?php if(isset($select_result['herbal_products']) && $select_result['herbal_products'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="herbal_products" value="No" <?php if(isset($select_result['herbal_products']) && $select_result['herbal_products'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['herbal_products']) && $select_result['herbal_products'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['herbal_products_text'])?$select_result['herbal_products_text']:""; ?>"  maxlength="25" name="herbal_products_text">
						</td>
						<td>Herbal products</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="eye_drops" value="Yes" <?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="eye_drops" value="No" <?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['eye_drops']) && $select_result['eye_drops'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['eye_drops_text'])?$select_result['eye_drops_text']:""; ?>"  maxlength="25" name="eye_drops_text">
						</td>
						<td>Eye drops</td>
					</tr>
					<tr>
						<td>
							<input type="radio" checked id="text1" name="non_prescription_drugs" value="Yes" <?php if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked id="text1" name="non_prescription_drugs" value="No" <?php if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['non_prescription_drugs_text'])?$select_result['non_prescription_drugs_text']:""; ?>"  maxlength="25" name="non_prescription_drugs_text">
						</td>
						<td>Non prescription drugs used currently other than medications used for this IVF cycle</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>FAMILY HISTORY</th>
			<td></td>
			<td style="padding: 0;">
				<center>Any family member any problem with anesthesia</center>
				<table width="100%">
					<tr>
						<td></td>
						<td>Maternal</td>
						<td>Paternal</td>
					</tr>
					<tr>
						<td>Diabetes</td>
						<td>
							<input type="radio" checked name="maternal_diabetes" value="Yes" <?php if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="maternal_diabetes" value="No" <?php if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['maternal_diabetes_text'])?$select_result['maternal_diabetes_text']:""; ?>"  maxlength="25" name="maternal_diabetes_text">
						</td>
						<td>
							<input type="radio" checked name="paternal_diabetes" value="Yes" <?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="paternal_diabetes" value="No" <?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['paternal_diabetes_text'])?$select_result['paternal_diabetes_text']:""; ?>"  maxlength="25" name="paternal_diabetes_text">
						</td>
					</tr>
					<tr>
						<td>Heart/thrombo embolism</td>
						<td>
							<input type="radio" checked name="maternal_thrombo_embolism" value="Yes" <?php if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="maternal_thrombo_embolism" value="No" <?php if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['maternal_thrombo_embolism_text'])?$select_result['maternal_thrombo_embolism_text']:""; ?>"  maxlength="25" name="maternal_thrombo_embolism_text">
						</td>
						<td>
							<input type="radio" checked name="paternal_thrombo_embolism" value="Yes" <?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="paternal_thrombo_embolism" value="No" <?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['paternal_thrombo_embolism_text'])?$select_result['paternal_thrombo_embolism_text']:""; ?>"  maxlength="25" name="paternal_thrombo_embolism_text">
						</td>
					</tr>
					<tr>
						<td>Endocrine/metabolic</td>
						<td>
							<input type="radio" checked name="maternal_metabolic" value="Yes" <?php if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="maternal_metabolic" value="No" <?php if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['maternal_metabolic_text'])?$select_result['maternal_metabolic_text']:""; ?>"  maxlength="25" name="maternal_metabolic_text">
						</td>
						<td>
							<input type="radio" checked name="paternal_metabolic" value="Yes" <?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="paternal_metabolic" value="No" <?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['paternal_metabolic_text'])?$select_result['paternal_metabolic_text']:""; ?>"  maxlength="25" name="paternal_metabolic_text">
						</td>
					</tr>
					<tr>
						<td>Urinary tract/renal</td>
						<td>
							<input type="radio" checked name="maternal_urinary_tract" value="Yes" <?php if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="maternal_urinary_tract" value="No" <?php if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['maternal_urinary_tract_text'])?$select_result['maternal_urinary_tract_text']:""; ?>"  maxlength="25" name="maternal_urinary_tract_text">
						</td>
						<td>
							<input type="radio" checked name="paternal_urinary_tract" value="Yes" <?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="paternal_urinary_tract" value="No" <?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['paternal_urinary_tract_text'])?$select_result['paternal_urinary_tract_text']:""; ?>"  maxlength="25" name="paternal_urinary_tract_text">
						</td>
					</tr>
					<tr>
						<td>Psychiatric/neurological</td>
						<td>
							<input type="radio" checked name="maternal_neurological" value="Yes" <?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="maternal_neurological" value="No" <?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['maternal_neurological_text'])?$select_result['maternal_neurological_text']:""; ?>"  maxlength="25" name="maternal_neurological_text">
						</td>
						<td>
							<input type="radio" checked name="paternal_neurological" value="Yes" <?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="paternal_neurological" value="No" <?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['paternal_neurological_text'])?$select_result['paternal_neurological_text']:""; ?>"  maxlength="25" name="paternal_neurological_text">
						</td>
					</tr>
					<tr>
						<td>Other/malignancy</td>
						<td>
							<input type="radio" checked name="maternal_malignancy" value="Yes" <?php if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="maternal_malignancy" value="No" <?php if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['maternal_malignancy_text'])?$select_result['maternal_malignancy_text']:""; ?>"  maxlength="25" name="maternal_malignancy_text">
						</td>
						<td>
							<input type="radio" checked name="paternal_malignancy" value="Yes" <?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?> >
							<label>Yes</label>
							<input type="radio" checked name="paternal_malignancy" value="No" <?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] != "Yes"){echo 'checked="checked"';}?> >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['paternal_malignancy_text'])?$select_result['paternal_malignancy_text']:""; ?>"  maxlength="25" name="paternal_malignancy_text">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>GENERAL EXAMINATION</th>
			<td></td>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Nutritional assessment :-Obese/average built/thin/cachexic</td>
						<td><input type="text"  value="<?php echo isset($select_result['nutritional_assessment'])?$select_result['nutritional_assessment']:""; ?>"  name="nutritional_assessment" class="form-control"></td>
					</tr>
					<tr>
						<td>Psychological assessment :-</td>
						<td><input type="text"  value="<?php echo isset($select_result['psychological_assessment'])?$select_result['psychological_assessment']:""; ?>"  name="psychological_assessment" class="form-control"></td>
					</tr>
					<tr>
						<td>Anxious/combative/depressed/normal</td>
						<td><input type="text"  value="<?php echo isset($select_result['anxious'])?$select_result['anxious']:""; ?>"  name="anxious" class="form-control"></td>
					</tr>
					<tr>
						<td>Pulse</td>
						<td><input type="number" value="<?php echo isset($select_result['pulse'])?$select_result['pulse']:""; ?>"  min="0" name="pulse" class="form-control"></td>
					</tr>
					<tr>
						<td>Blood pressure</td>
						<td><input type="text"  value="<?php echo isset($select_result['blood_pressure'])?$select_result['blood_pressure']:""; ?>"  name="blood_pressure" class="form-control"></td>
					</tr>
					<tr>
						<td>Temperature(DEG F)</td>
						<td><input type="number" value="<?php echo isset($select_result['temperature'])?$select_result['temperature']:""; ?>"  min="0" name="temperature" class="form-control"></td>
					</tr>
					<tr>
						<td>CVS</td>
						<td><input type="text"  value="<?php echo isset($select_result['cvs'])?$select_result['cvs']:""; ?>"  name="cvs" class="form-control"></td>
					</tr>
					<tr>
						<td>Chest</td>
						<td><input type="text"  value="<?php echo isset($select_result['chest'])?$select_result['chest']:""; ?>"  name="chest" class="form-control"></td>
					</tr>
					<tr>
						<td>Abdomen</td>
						<td><input type="text"  value="<?php echo isset($select_result['abdomen'])?$select_result['abdomen']:""; ?>"  name="abdomen" class="form-control"></td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input type="text"  value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>"  maxlength="20" name="others" class="form-control"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>INVESTIGATIONS ADVICED(TO BE PICKED FROM BILLING INVESTIGATION LIST)</th>
			<td></td>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Blood group and Rh typing</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_blood_group'])?$select_result['adviced_blood_group']:""; ?>" name="adviced_blood_group" class="form-control"></td>
					</tr>
					<tr>
						<td>TSH</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_tsh'])?$select_result['adviced_tsh']:""; ?>" name="adviced_tsh" class="form-control"></td>
					</tr>
					<tr>
						<td>FT4</td>
						<td><input type="date" value="<?php echo isset($select_result['ft4'])?$select_result['ft4']:""; ?>" name="ft4" class="form-control"></td>
					</tr>
					<tr>
						<td>CBC</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_cbc'])?$select_result['adviced_cbc']:""; ?>" name="adviced_cbc" class="form-control"></td>
					</tr>
					<tr>
						<td>Urine routine microscopy</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_microscopy'])?$select_result['adviced_microscopy']:""; ?>" name="adviced_microscopy" class="form-control"></td>
					</tr>
					<tr>
						<td>Blood sugar</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_blood_sugar'])?$select_result['adviced_blood_sugar']:""; ?>" name="adviced_blood_sugar" class="form-control"></td>
					</tr>
					<tr>
						<td>HbA1c</td>
						<td><input type="date" value="<?php echo isset($select_result['hba1c'])?$select_result['hba1c']:""; ?>" name="hba1c" class="form-control"></td>
					</tr>
					<tr>
						<td>Hb Electrophoresis</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_hb_electrophoresis'])?$select_result['adviced_hb_electrophoresis']:""; ?>" name="adviced_hb_electrophoresis" class="form-control"></td>
					</tr>
					<tr>
						<td>HIV</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_hiv'])?$select_result['adviced_hiv']:""; ?>" name="adviced_hiv" class="form-control"></td>
					</tr>
					<tr>
						<td>Hbsag</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_hbsag'])?$select_result['adviced_hbsag']:""; ?>" name="adviced_hbsag" class="form-control"></td>
					</tr>
					<tr>
						<td>Hep C</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_hepc'])?$select_result['adviced_hepc']:""; ?>" name="adviced_hepc" class="form-control"></td>
					</tr>
					<tr>
						<td>VDRL</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_vdrl'])?$select_result['adviced_vdrl']:""; ?>" name="adviced_vdrl" class="form-control"></td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input type="date" value="<?php echo isset($select_result['adviced_others'])?$select_result['adviced_others']:""; ?>" name="adviced_others" class="form-control"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				Date & Time:
				<input type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>" name="date">
				<input type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>" name="time">
			</td>
			<td>Doctor Sign: <input type="text"  value="<?php echo isset($select_result['doctor_sign'])?$select_result['doctor_sign']:""; ?>"  name="doctor_sign"></td>
		</tr>
	</table>
	<!-- /.card-body -->
	<div class="card-footer">
		<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
	</div>
</form>


<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 






<!--  donor_sperm_personal_details    -->


    <table class="table-bordered" style="border:1px solid #cdcdcd; width:100%;">
	 <tr>
                <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
			 <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><center><h2>NATURAL CYCLE  PROTOCOL</h2></center></td>
					
</tr>
<tr>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;">UHID :</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;"><?php 
	        $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' limit 1 "; 
			   $query = $this->db->query($sql1);
                  $select_result1 = $query->result(); 
					foreach ($select_result1 as $res_val2){
						$sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$res_val2->wife_phone."' and paitent_type='new_patient'"; 
			            $query = $this->db->query($sql2);
                        $select_result2 = $query->result();
						  foreach ($select_result2 as $res_val){
						?>
	  <?php if($res_val->appoitment_for == '16249589462327'){?>
	  <?php if($res_val->uhid){ ?>001/<?php echo $res_val->uhid; } ?>
	  <?php } if($res_val->appoitment_for == '16266778858144'){?>
	  <?php if($res_val->uhid){ ?>002/<?php echo $res_val->uhid; } ?>
	  <?php } if($res_val->appoitment_for == '16267558222750'){?>
	  <?php if($res_val->uhid){ ?>003/<?php echo $res_val->uhid; } ?>
	  <?php }  if($res_val->appoitment_for == '16098223739590') {?>
	  <?php if($res_val->uhid){ ?>004/<?php echo $res_val->uhid; } ?>
	  <?php } if($res_val->appoitment_for == '16133769691598'){?>
	  <?php if($res_val->uhid){ ?>005/<?php echo $res_val->uhid;} ?>
						<?php }}} ?></td>
</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;">IIC ID:</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo $patient_id; ?></td>
</tr>
		<tr>
			<td colspan="4" style="border:1px solid #cdcdcd;">
			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
			            ){?>
			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
			    <?php } ?>
			</td>
		</tr>
	</table>
			
	<table class="table-bordered"  style="border:1px solid #cdcdcd; width:100%;">
		<tr>
			<th  style="border:1px solid #cdcdcd;"></th>
			<td  style="border:1px solid #cdcdcd;"><b>FEMALE</b></td>
			<td  style="border:1px solid #cdcdcd;"><b>MALE</b></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">ART BANK</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_art_bank'])?$select_result['male_art_bank']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">ID NUMBER</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_id_number'])?$select_result['male_id_number']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PHOTOGRAPHS</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			
			
			<td  style="border:1px solid #cdcdcd; padding: 0;">
			
			<?php @ $male_photographs= $select_result['male_photographs']; ?> 
			
			
			
			<?php if(!empty($male_photographs)) {?>
                 <img src="<?php echo $male_photographs;?>" style="width:100px; height:100px;">
                    <?php } else {echo " ";}?>
			
			
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">NAME</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_name'])?$select_result['male_name']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">AGE</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_age'])?$select_result['male_age']:""; ?>  </td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">OCCUPATION</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_occupation'])?$select_result['male_occupation']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">NATIONALITY</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_nationality'])?$select_result['male_nationality']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">ETHNICITY</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_ethnicity'])?$select_result['male_ethnicity']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">AADHAR NUMBER</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_aadhar_number'])?$select_result['male_aadhar_number']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PRESENT ADDRESS</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_present_address'])?$select_result['male_present_address']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PERMANENT ADDRESS</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_permanent_address'])?$select_result['male_permanent_address']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">MOBILE NO</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_mobile_no'])?$select_result['male_mobile_no']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">EMAIL ADDRESS</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_email_address'])?$select_result['male_email_address']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">WT/HT/BMI</th>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">H/O previous pregnancies(IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
			<td  style="border:1px solid #cdcdcd; padding: 0;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;">
				<table style="border:1px solid #cdcdcd; width:100%;">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Total pregnancies</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_total_pregnancies'])?$select_result['male_total_pregnancies']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No.of live births</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_live_births'])?$select_result['male_live_births']:""; ?>  </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No.of spontaneous abortions</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_spontaneous_abortions'])?$select_result['male_spontaneous_abortions']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No.of termination of pregnancy</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_termination_of_pregnancy'])?$select_result['male_termination_of_pregnancy']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No.of still births</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_still_births'])?$select_result['male_still_births']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No. of ectopic pregnancy</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_ectopic_pregnancy'])?$select_result['male_ectopic_pregnancy']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">History of any abnormality in child</td>
						<td  style="border:1px solid #cdcdcd;">
							

<?php if(isset($select_result['male_history_of_abnormality']) && $select_result['male_history_of_abnormality'] == "Yes"){echo 'Yes'; }?> 	<br>

<?php echo isset($select_result['male_history_of_abnormality_text'])?$select_result['male_history_of_abnormality_text']:""; ?>
							
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Others</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_others'])?$select_result['male_others']:""; ?>  </td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PAST MEDICAL HISTORY</th>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;">
				<table style="border:1px solid #cdcdcd; width:100%;">
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
							
						<?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "Yes"){echo 'Yes'; }?> 	<br>	
							
							<?php echo isset($select_result['heart_attack_text'])?$select_result['heart_attack_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Heart attack</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
							
				<?php if(isset($select_result['pacemaker']) && $select_result['pacemaker'] == "Yes"){echo 'Yes'; }?> 	<br>					
							
							<?php echo isset($select_result['pacemaker_text'])?$select_result['pacemaker_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Pacemaker</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
							
			<?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "Yes"){echo 'Yes'; }?> 	<br>			
							
				<?php echo isset($select_result['other_heart_disease_text'])?$select_result['other_heart_disease_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Other heart disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
				<?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "Yes"){echo 'Yes'; }?> 
				<br>				
		     	<?php echo isset($select_result['high_blood_pressure_text'])?$select_result['high_blood_pressure_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">High blood pressure</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
							
				<?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "Yes"){echo 'Yes'; }?> 	<br>						
							<?php echo isset($select_result['blood_clots_text'])?$select_result['blood_clots_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Blood clots</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
			

		<?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "Yes"){echo 'Yes'; }?> 	<br>	
		<?php echo isset($select_result['chest_pain_text'])?$select_result['chest_pain_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Chest pain</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
				<?php if(isset($select_result['stroke']) && $select_result['stroke'] == "Yes"){echo 'Yes'; }?> 	<br>
				<?php echo isset($select_result['stroke_text'])?$select_result['stroke_text']:""; ?> 
						</td>
						<td  style="border:1px solid #cdcdcd;">Stroke</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
			<?php if(isset($select_result['asthma']) && $select_result['asthma'] == "Yes"){echo 'Yes'; }?> 	<br>					
		<?php echo isset($select_result['asthma_text'])?$select_result['asthma_text']:""; ?> 
						</td>
						<td  style="border:1px solid #cdcdcd;">Asthma</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
<?php if(isset($select_result['lung_disease']) && $select_result['lung_disease'] == "Yes"){echo 'Yes'; }?> 	<br>			
							
	<?php echo isset($select_result['lung_disease_text'])?$select_result['lung_disease_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Other lung disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
	<?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "Yes"){echo 'Yes'; }?> 	<br>							
							
	<?php echo isset($select_result['difficulty_breathing_text'])?$select_result['difficulty_breathing_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Difficulty breathing</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
	<?php if(isset($select_result['snoring']) && $select_result['snoring'] == "Yes"){echo 'Yes'; }?> 	<br>						
	<?php echo isset($select_result['snoring_text'])?$select_result['snoring_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Sleep apnea or snoring</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
								
	<?php if(isset($select_result['epilepsy']) && $select_result['epilepsy'] == "Yes"){echo 'Yes'; }?> 	<br>						
							
		<?php echo isset($select_result['epilepsy_text'])?$select_result['epilepsy_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Epilepsy or seizures</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
<?php if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] == "Yes"){echo 'Yes'; }?> 	<br>							
<?php echo isset($select_result['fainting_spells_text'])?$select_result['fainting_spells_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Fainting spells</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
														
	<?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "Yes"){echo 'Yes'; }?> 	<br>							
	<?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Diabetes</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
		<?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "Yes"){echo 'Yes'; }?> 	<br>					
							
		<?php echo isset($select_result['muscle_disorders_text'])?$select_result['muscle_disorders_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Muscle disorders</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
				<?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "Yes"){echo 'Yes'; }?> 	<br>			
							<?php echo isset($select_result['kidney_disease_text'])?$select_result['kidney_disease_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Kidney disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
							
					<?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "Yes"){echo 'Yes'; }?> 	<br>			
							
							<?php echo isset($select_result['hepatitis_text'])?$select_result['hepatitis_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Hepatitis</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
					<?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "Yes"){echo 'Yes'; }?> 	<br>			
							
							<?php echo isset($select_result['tuberculosis_text'])?$select_result['tuberculosis_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Tuberculosis</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
							
							
			<?php if(isset($select_result['hiv']) && $select_result['hiv'] == "Yes"){echo 'Yes'; }?> 	<br>				
							<?php echo isset($select_result['hiv_text'])?$select_result['hiv_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">HIV</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
					<?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "Yes"){echo 'Yes'; }?> 	<br>		
							
							<?php echo isset($select_result['heart_burn_text'])?$select_result['heart_burn_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Heart burn/reflux</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
				<?php if(isset($select_result['cancer']) && $select_result['cancer'] == "Yes"){echo 'Yes'; }?> 	<br>			
							<?php echo isset($select_result['cancer_text'])?$select_result['cancer_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Cancer</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
			<?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "Yes"){echo 'Yes'; }?> 	<br>					
							
							<?php echo isset($select_result['blood_disorders_text'])?$select_result['blood_disorders_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Blood disorders</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
		<?php if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] == "Yes"){echo 'Yes'; }?> 	<br>						
							
							<?php echo isset($select_result['rheumatic_disease_text'])?$select_result['rheumatic_disease_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Rheumatic disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">

							
		<?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "Yes"){echo 'Yes'; }?> 	<br>					
		<?php echo isset($select_result['psychiatric_disorder_text'])?$select_result['psychiatric_disorder_text']:""; ?>
							
							
							
						</td>
						<td  style="border:1px solid #cdcdcd;">Psychiatric disorder</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
				<?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "Yes"){echo 'Yes'; }?> 	<br>					
				<?php echo isset($select_result['thyroid_disorder_text'])?$select_result['thyroid_disorder_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Thyroid disorder</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
			<?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "Yes"){echo 'Yes'; }?> 	<br>					
							
							<?php echo isset($select_result['urinary_infection_text'])?$select_result['urinary_infection_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Urinary infection</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
							
					<?php if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] == "Yes"){echo 'Yes'; }?> 	<br>			
							<?php echo isset($select_result['sexually_transmitted_text'])?$select_result['sexually_transmitted_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Sexually transmitted disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['impairments'])?$select_result['impairments']:""; ?>  </td>
						<td  style="border:1px solid #cdcdcd;">Other medical condition or impairments</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PAST SURGICAL HISTORY</th>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;">
				<table style="border:1px solid #cdcdcd; width:100%;">
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
			<?php if(isset($select_result['abdominal_operations_text']) && $select_result['abdominal_operations_text'] == "Yes"){echo 'Yes'; }?> 	<br>				
							
							
							<?php echo isset($select_result['abdominal_operations_text'])?$select_result['abdominal_operations_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Laparoscopy/pelvic/abdominal operations</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
					<?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "Yes"){echo 'Yes'; }?> 	<br>			
							
							<?php echo isset($select_result['other_operations_text'])?$select_result['other_operations_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Other operations</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">Allergy history</th>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;">
				<table style="border:1px solid #cdcdcd; width:100%;">
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
		<?php if(isset($select_result['medications']) && $select_result['medications'] == "Yes"){echo 'Yes'; }?> 	<br>
		<?php echo isset($select_result['medications_text'])?$select_result['medications_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Medications</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
							<?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "Yes"){echo 'Yes'; }?> 	<br>
							
							<?php echo isset($select_result['environmental_factors_text'])?$select_result['environmental_factors_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">environmental factors</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">SOCIAL & DRUG INTAKE HISTORY</th>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;">
				<table style="border:1px solid #cdcdcd; width:100%;">
					<tr>
						<td style="border:1px solid #cdcdcd;width:20%;">
							
		<?php if(isset($select_result['dentures']) && $select_result['dentures'] == "Yes"){echo 'Yes'; }?> 	<br>	
							
							<?php echo isset($select_result['dentures_text'])?$select_result['dentures_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Dentures</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
						<?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "Yes"){echo 'Yes'; }?> 	<br>	
							
							<?php echo isset($select_result['loose_teeth_text'])?$select_result['loose_teeth_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Loose teeth</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
							
					<?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "Yes"){echo 'Yes'; }?> 	<br>		
							
							<?php echo isset($select_result['hearing_aid_text'])?$select_result['hearing_aid_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Hearing aid</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
							
						<?php if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] == "Yes"){echo 'Yes'; }?> 	<br>			
							
							
							<?php echo isset($select_result['caps_on_front_teeth_text'])?$select_result['caps_on_front_teeth_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Caps on front teeth</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							<?php if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] == "Yes"){echo 'Yes'; }?> 	<br>		
							
					<?php echo isset($select_result['contact_lenses_text'])?$select_result['contact_lenses_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Contact lenses</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
						<?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "Yes"){echo 'Yes'; }?> 	<br>		
							
							
							<?php echo isset($select_result['body_piercing_text'])?$select_result['body_piercing_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Body piercing</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
									
					<?php if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] == "Yes"){echo 'Yes'; }?> 	<br>		
						
							<?php echo isset($select_result['blood_transfusion_text'])?$select_result['blood_transfusion_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">H/o blood transfusion</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
							
					<?php if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] == "Yes"){echo 'Yes'; }?> 	<br>		
							
							<?php echo isset($select_result['traffic_accident_text'])?$select_result['traffic_accident_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">H/o road traffic accident/any injury</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
						<?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "Yes"){echo 'Yes'; }?> 	<br>	
							
							<?php echo isset($select_result['smoke_past_text'])?$select_result['smoke_past_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Smoke(past)daily</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
		<?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "Yes"){echo 'Yes'; }?> 	<br>						
							
							<?php echo isset($select_result['smoke_present_text'])?$select_result['smoke_present_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Smoke(present)daily</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
	<?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "Yes"){echo 'Yes'; }?> 	<br>			
							
							<?php echo isset($select_result['drink_past_text'])?$select_result['drink_past_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Drink(past)units per week</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
							
							<?php if(isset($select_result['drink_present']) && $select_result['drink_present'] == "Yes"){echo 'Yes'; }?> 	<br>
							
							<?php echo isset($select_result['drink_present_text'])?$select_result['drink_present_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Drink(present)units per week</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							<?php if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] == "Yes"){echo 'Yes'; }?> 	<br>
							
							<?php echo isset($select_result['abusive_drugs_text'])?$select_result['abusive_drugs_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Hashish/cocaine /abusive drugs</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							<?php if(isset($select_result['steroid']) && $select_result['steroid'] == "Yes"){echo 'Yes'; }?> 	<br>
							
							<?php echo isset($select_result['steroid_text'])?$select_result['steroid_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Have you ever used cortisone/steroid</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
					<?php if(isset($select_result['medication']) && $select_result['medication'] == "Yes"){echo 'Yes'; }?> 	<br>
							
							<?php echo isset($select_result['medication_text'])?$select_result['medication_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Medication</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
														
							<?php if(isset($select_result['herbal_products']) && $select_result['herbal_products'] == "Yes"){echo 'Yes'; }?> 	<br>
							<?php echo isset($select_result['herbal_products_text'])?$select_result['herbal_products_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Herbal products</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
				<?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "Yes"){echo 'Yes'; }?> 	<br>			
							
							<?php echo isset($select_result['eye_drops_text'])?$select_result['eye_drops_text']:""; ?>  
						</td>
						<td  style="border:1px solid #cdcdcd;">Eye drops</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
							
					<?php if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] == "Yes"){echo 'Yes'; }?> 	<br>				
							<?php echo isset($select_result['non_prescription_drugs_text'])?$select_result['non_prescription_drugs_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Non prescription drugs used currently other than medications used for this IVF cycle</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">FAMILY HISTORY</th>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;">
				<center>Any family member any problem with anesthesia</center>
				<table style="border:1px solid #cdcdcd; width:100%;">
					<tr>
						<td  style="border:1px solid #cdcdcd;"></td>
						<td  style="border:1px solid #cdcdcd;">Maternal</td>
						<td  style="border:1px solid #cdcdcd;">Paternal</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Diabetes</td>
						<td  style="border:1px solid #cdcdcd;">
							
							<?php if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] == "Yes"){echo 'Yes'; }?> 	<br>	
							
							<?php echo isset($select_result['maternal_diabetes_text'])?$select_result['maternal_diabetes_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">
							
							
				<?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "Yes"){echo 'Yes'; }?> 	<br>
							
							
							
							<?php echo isset($select_result['paternal_diabetes_text'])?$select_result['paternal_diabetes_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Heart/thrombo embolism</td>
						<td  style="border:1px solid #cdcdcd;">
						
							
						<?php if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] == "Yes"){echo 'Yes'; }?> 	<br>		
							
							<?php echo isset($select_result['maternal_thrombo_embolism_text'])?$select_result['maternal_thrombo_embolism_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">
							
							
					<?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "Yes"){echo 'Yes'; }?> 	<br>		
							
							<?php echo isset($select_result['paternal_thrombo_embolism_text'])?$select_result['paternal_thrombo_embolism_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Endocrine/metabolic</td>
						<td  style="border:1px solid #cdcdcd;">
						
							
							<?php if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] == "Yes"){echo 'Yes'; }?> 	<br>
							
							<?php echo isset($select_result['maternal_metabolic_text'])?$select_result['maternal_metabolic_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">
						
							<?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "Yes"){echo 'Yes'; }?> 	<br>
							
							<?php echo isset($select_result['paternal_metabolic_text'])?$select_result['paternal_metabolic_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Urinary tract/renal</td>
						<td  style="border:1px solid #cdcdcd;">
							

	<?php if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] == "Yes"){echo 'Yes'; }?> 	<br>	

						
							<?php echo isset($select_result['maternal_urinary_tract_text'])?$select_result['maternal_urinary_tract_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">
						
							
							<?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "Yes"){echo 'Yes'; }?> 	<br>	
							
							<?php echo isset($select_result['paternal_urinary_tract_text'])?$select_result['paternal_urinary_tract_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Psychiatric/neurological</td>
						<td  style="border:1px solid #cdcdcd;">
							
							
				<?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "Yes"){echo 'Yes'; }?> 	<br>			
							
							<?php echo isset($select_result['maternal_neurological_text'])?$select_result['maternal_neurological_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">
							
							<?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "Yes"){echo 'Yes'; }?> 	<br>
							
							<?php echo isset($select_result['paternal_neurological_text'])?$select_result['paternal_neurological_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Other/malignancy</td>
						<td  style="border:1px solid #cdcdcd;">
							
							
							<?php if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] == "Yes"){echo 'Yes'; }?> 	<br>
							
							<?php echo isset($select_result['maternal_malignancy_text'])?$select_result['maternal_malignancy_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">
							
							
				<?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "Yes"){echo 'Yes'; }?> 			
							
				<?php echo isset($select_result['paternal_malignancy_text'])?$select_result['paternal_malignancy_text']:""; ?>
						
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">GENERAL EXAMINATION</th>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;">
				<table style="border:1px solid #cdcdcd; width:100%;">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Nutritional assessment :-Obese/average built/thin/cachexic</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nutritional_assessment'])?$select_result['nutritional_assessment']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Psychological assessment :-</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['psychological_assessment'])?$select_result['psychological_assessment']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Anxious/combative/depressed/normal</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['anxious'])?$select_result['anxious']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Pulse</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pulse'])?$select_result['pulse']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Blood pressure</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['blood_pressure'])?$select_result['blood_pressure']:""; ?>  </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Temperature(DEG F)</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['temperature'])?$select_result['temperature']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">CVS</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cvs'])?$select_result['cvs']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Chest</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['chest'])?$select_result['chest']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Abdomen</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['abdomen'])?$select_result['abdomen']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Others</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['others'])?$select_result['others']:""; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">INVESTIGATIONS ADVICED(TO BE PICKED FROM BILLING INVESTIGATION LIST)</th>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd; padding: 0;">
				<table style="border:1px solid #cdcdcd; width:100%;">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Blood group and Rh typing</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_blood_group'])?$select_result['adviced_blood_group']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">TSH</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_tsh'])?$select_result['adviced_tsh']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">FT4</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ft4'])?$select_result['ft4']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">CBC</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_cbc'])?$select_result['adviced_cbc']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Urine routine microscopy</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_microscopy'])?$select_result['adviced_microscopy']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Blood sugar</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_blood_sugar'])?$select_result['adviced_blood_sugar']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">HbA1c</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hba1c'])?$select_result['hba1c']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Hb Electrophoresis</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_hb_electrophoresis'])?$select_result['adviced_hb_electrophoresis']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">HIV</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_hiv'])?$select_result['adviced_hiv']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Hbsag</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_hbsag'])?$select_result['adviced_hbsag']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Hep C</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_hepc'])?$select_result['adviced_hepc']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">VDRL</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_vdrl'])?$select_result['adviced_vdrl']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Others</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['adviced_others'])?$select_result['adviced_others']:""; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;"></th>
			<td  style="border:1px solid #cdcdcd;">
				Date & Time:
				<?php echo isset($select_result['date'])?$select_result['date']:""; ?>
			<?php echo isset($select_result['time'])?$select_result['time']:""; ?>
			</td>
			<td  style="border:1px solid #cdcdcd;">Doctor Sign: <?php echo isset($select_result['doctor_sign'])?$select_result['doctor_sign']:""; ?></td>
		</tr>
	</table>
	












</div>
						
<script>
$(document).ready(function(){
    
    // Step 1: Agar button kahin aur se disable ho raha hai toh usko enable karein
    $('#btn').prop('disabled', false);

    $(".ptable").click(function(){
        // Elements ko hide/show karna
        $('.searchform').hide();
        $('.printbtn').hide(); 
        $('.prtable').css('display', 'block');
      
        // Print logic
        var divToPrint = document.getElementById('printtable');
        var newWin = window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();
      
        // Timeout thoda bada rakhein taaki print dialog perfectly load ho sake
        setTimeout(function(){
            newWin.close();
        }, 500); 

        // Note: window.location.reload(); ko hta diya gaya hai taaki page achanak refresh na ho.
    });
});
</script>	