<?php
// php code to Insert data into mysql database from input text
// if(isset($_POST['submit'])){

// 	$patient_id = $_POST['patient_id'];
// 	$receipt_number = $_POST['receipt_number'];
// 	$status = $_POST['status'];

// 	// get values form input text and number
// 	$female_art_bank = $_POST['female_art_bank'];
// 	$female_id_number = $_POST['female_id_number'];
// 	$female_photographs = $_POST['female_photographs'];
// 	$female_name = $_POST['female_name'];
// 	$female_dob = $_POST['female_dob'];
// 	$female_age = $_POST['female_age'];
// 	$female_nationality = $_POST['female_nationality'];
// 	$female_mobile_no = $_POST['female_mobile_no'];
// 	$female_email_address = $_POST['female_email_address'];
// 	$female_present_address = $_POST['female_present_address'];
// 	$female_permanent_address = $_POST['female_permanent_address'];
// 	$female_aadhar_number = $_POST['female_aadhar_number'];
// 	$female_pan_card_number = $_POST['female_pan_card_number'];
// 	$female_religion = $_POST['female_religion'];
// 	$female_monthly_income = $_POST['female_monthly_income'];
// 	$female_education = $_POST['female_education'];
// 	$female_occupation = $_POST['female_occupation'];
// 	$male_art_bank = $_POST['male_art_bank'];
// 	$male_id_number = $_POST['male_id_number'];
// 	$male_photographs = $_POST['male_photographs'];
// 	$male_name = $_POST['male_name'];
// 	$male_dob = $_POST['male_dob'];
// 	$male_age = $_POST['male_age'];
// 	$male_nationality = $_POST['male_nationality'];
// 	$male_mobile_no = $_POST['male_mobile_no'];
// 	$male_email_address = $_POST['male_email_address'];
// 	$male_present_address = $_POST['male_present_address'];
// 	$male_permanent_address = $_POST['male_permanent_address'];
// 	$male_aadhar_number = $_POST['male_aadhar_number'];
// 	$male_pan_card_number = $_POST['male_pan_card_number'];
// 	$male_religion = $_POST['male_religion'];
// 	$male_monthly_income = $_POST['male_monthly_income'];
// 	$male_education = $_POST['male_education'];
// 	$male_occupation = $_POST['male_occupation'];
// 	$female_total_pregnancies = $_POST['female_total_pregnancies'];
// 	$female_live_births = $_POST['female_live_births'];
// 	$female_spontaneous_abortions = $_POST['female_spontaneous_abortions'];
// 	$female_termination_of_pregnancy = $_POST['female_termination_of_pregnancy'];
// 	$female_still_births = $_POST['female_still_births'];
// 	$female_ectopic_pregnancy = $_POST['female_ectopic_pregnancy'];
// 	$female_history_of_abnormality = $_POST['female_history_of_abnormality'];
// 	$female_others = $_POST['female_others'];
// 	$male_total_pregnancies = $_POST['male_total_pregnancies'];
// 	$male_live_births = $_POST['male_live_births'];
// 	$male_spontaneous_abortions = $_POST['male_spontaneous_abortions'];
// 	$male_termination_of_pregnancy = $_POST['male_termination_of_pregnancy'];
// 	$male_still_births = $_POST['male_still_births'];
// 	$male_ectopic_pregnancy = $_POST['male_ectopic_pregnancy'];
// 	$male_history_of_abnormality = $_POST['male_history_of_abnormality'];
// 	$male_others = $_POST['male_others'];
// 	$female_years_of_marriage = $_POST['female_years_of_marriage'];
// 	$female_contraception = $_POST['female_contraception'];
// 	$male_years_of_marriage = $_POST['male_years_of_marriage'];
// 	$male_contraception = $_POST['male_contraception'];
// 	$dysmenorrhea = $_POST['dysmenorrhea'];
// 	$menorrhagia = $_POST['menorrhagia'];
// 	$dandc = $_POST['dandc'];
// 	$dyspareunia = $_POST['dyspareunia'];
// 	$others1 = $_POST['others1'];
// 	$age_of_menarche = $_POST['age_of_menarche'];
// 	$flow = $_POST['flow'];
// 	$frequency = $_POST['frequency'];
// 	$days = $_POST['days'];
// 	$hirsutism = $_POST['hirsutism'];
// 	$galactorrhea = $_POST['galactorrhea'];
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
// 	$eggs_donated = $_POST['eggs_donated'];
// 	$times_did_surrogacy = $_POST['times_did_surrogacy'];
// 	$nutritional_assessment = $_POST['nutritional_assessment'];
// 	$psychological_assessment = $_POST['psychological_assessment'];
// 	$anxious = $_POST['anxious'];
// 	$colour_of_hair = $_POST['colour_of_hair'];
// 	$colour_of_eye = $_POST['colour_of_eye'];
// 	$colour_of_skin = $_POST['colour_of_skin'];
// 	$pulse = $_POST['pulse'];
// 	$blood_pressure = $_POST['blood_pressure'];
// 	$cvs = $_POST['cvs'];
// 	$chest = $_POST['chest'];
// 	$abdomen = $_POST['abdomen'];
// 	$others2 = $_POST['others2'];
// 	$ps = $_POST['ps'];
// 	$pv = $_POST['pv'];
// 	$blood_group = $_POST['blood_group'];
// 	$fsh = $_POST['fsh'];
// 	$e2 = $_POST['e2'];
// 	$lh = $_POST['lh'];
// 	$amh = $_POST['amh'];
// 	$prolactin = $_POST['prolactin'];
// 	$tsh = $_POST['tsh'];
// 	$cbc = $_POST['cbc'];
// 	$pt_aptt_inr = $_POST['pt_aptt_inr'];
// 	$urine_routine = $_POST['urine_routine'];
// 	$blood_sugar = $_POST['blood_sugar'];
// 	$hb_electrophoresis = $_POST['hb_electrophoresis'];
// 	$hiv1 = $_POST['hiv1'];
// 	$hbsag = $_POST['hbsag'];
// 	$hepc = $_POST['hepc'];
// 	$vdrl = $_POST['vdrl'];
// 	$rft = $_POST['rft'];
// 	$lft = $_POST['lft'];
// 	$usg_pelvis = $_POST['usg_pelvis'];
// 	$afc = $_POST['afc'];
// 	$others3 = $_POST['others3'];
// 	$female_medicine_adviced = $_POST['female_medicine_adviced'];
// 	$male_medicine_adviced = $_POST['male_medicine_adviced'];
// 	$female_followup = $_POST['female_followup'];
// 	$male_followup = $_POST['male_followup'];
// 	$female_purpose = $_POST['female_purpose'];
// 	$male_purpose = $_POST['male_purpose'];
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

// 	// connect to mysql database using mysqli


// 	// mysql query to insert data
// 	$query = "INSERT INTO `donor_egg_personal_details`(`patient_id`, `receipt_number`, `status`,`female_art_bank`,`female_id_number`,`female_photographs`,`female_name`,`female_dob`,`female_age`,`female_nationality`,`female_mobile_no`,`female_email_address`,`female_present_address`,`female_permanent_address`,`female_aadhar_number`,`female_pan_card_number`,`female_religion`,`female_monthly_income`,`female_education`,`female_occupation`,`male_art_bank`,`male_id_number`,`male_photographs`,`male_name`,`male_dob`,`male_age`,`male_nationality`,`male_mobile_no`,`male_email_address`,`male_present_address`,`male_permanent_address`,`male_aadhar_number`,`male_pan_card_number`,`male_religion`,`male_monthly_income`,`male_education`,`male_occupation`,`female_total_pregnancies`,`female_live_births`,`female_spontaneous_abortions`,`female_termination_of_pregnancy`,`female_still_births`,`female_ectopic_pregnancy`,`female_history_of_abnormality`,`female_others`,`male_total_pregnancies`,`male_live_births`,`male_spontaneous_abortions`,`male_termination_of_pregnancy`,`male_still_births`,`male_ectopic_pregnancy`,`male_history_of_abnormality`,`male_others`,`female_years_of_marriage`,`female_contraception`,`male_years_of_marriage`,`male_contraception`,`dysmenorrhea`,`menorrhagia`,`dandc`,`dyspareunia`,`others1`,`age_of_menarche`,`flow`,`frequency`,`days`,`hirsutism`,`galactorrhea`,`heart_attack`,`pacemaker`,`other_heart_disease`,`high_blood_pressure`,`blood_clots`,`chest_pain`,`stroke`,`asthma`,`lung_disease`,`difficulty_breathing`,`snoring`,`epilepsy`,`fainting_spells`,`diabetes`,`muscle_disorders`,`kidney_disease`,`hepatitis`,`tuberculosis`,`hiv`,`heart_burn`,`cancer`,`blood_disorders`,`rheumatic_disease`,`psychiatric_disorder`,`thyroid_disorder`,`urinary_infection`,`sexually_transmitted`,`abdominal_operations`,`other_operations`,`medications`,`environmental_factors`,`dentures`,`loose_teeth`,`hearing_aid`,`caps_on_front_teeth`,`contact_lenses`,`body_piercing`,`blood_transfusion`,`traffic_accident`,`smoke_past`,`smoke_present`,`drink_past`,`drink_present`,`abusive_drugs`,`steroid`,`medication`,`herbal_products`,`eye_drops`,`non_prescription_drugs`,`maternal_diabetes`,`paternal_diabetes`,`maternal_thrombo_embolism`,`paternal_thrombo_embolism`,`maternal_metabolic`,`paternal_metabolic`,`maternal_urinary_tract`,`paternal_urinary_tract`,`maternal_neurological`,`paternal_neurological`,`maternal_malignancy`,`paternal_malignancy`,`eggs_donated`,`times_did_surrogacy`,`nutritional_assessment`,`psychological_assessment`,`anxious`,`colour_of_hair`,`colour_of_eye`,`colour_of_skin`,`pulse`,`blood_pressure`,`cvs`,`chest`,`abdomen`,`others2`,`ps`,`pv`,`blood_group`,`fsh`,`e2`,`lh`,`amh`,`prolactin`,`tsh`,`cbc`,`pt_aptt_inr`,`urine_routine`,`blood_sugar`,`hb_electrophoresis`,`hiv1`,`hbsag`,`hepc`,`vdrl`,`rft`,`lft`,`usg_pelvis`,`afc`,`others3`,`female_medicine_adviced`,`male_medicine_adviced`,`female_followup`,`male_followup`,`female_purpose`,`male_purpose`,`date`,`time`,`doctor_sign`,`impairments`,`heart_attack_text`,`pacemaker_text`,`other_heart_disease_text`,`high_blood_pressure_text`,`blood_clots_text`,`chest_pain_text`,`stroke_text`,`asthma_text`,`lung_disease_text`,`difficulty_breathing_text`,`snoring_text`,`epilepsy_text`,`fainting_spells_text`,`diabetes_text`,`muscle_disorders_text`,`kidney_disease_text`,`hepatitis_text`,`tuberculosis_text`,`hiv_text`,`heart_burn_text`,`cancer_text`,`blood_disorders_text`,`rheumatic_disease_text`,`psychiatric_disorder_text`,`thyroid_disorder_text`,`urinary_infection_text`,`sexually_transmitted_text`,`abdominal_operations_text`,`other_operations_text`,`medications_text`,`environmental_factors_text`,`dentures_text`,`loose_teeth_text`,`hearing_aid_text`,`caps_on_front_teeth_text`,`contact_lenses_text`,`body_piercing_text`,`blood_transfusion_text`,`traffic_accident_text`,`smoke_past_text`,`smoke_present_text`,`drink_past_text`,`drink_present_text`,`abusive_drugs_text`,`steroid_text`,`medication_text`,`herbal_products_text`,`eye_drops_text`,`non_prescription_drugs_text`,`maternal_diabetes_text`,`paternal_diabetes_text`,`maternal_thrombo_embolism_text`,`paternal_thrombo_embolism_text`,`maternal_metabolic_text`,`paternal_metabolic_text`,`maternal_urinary_tract_text`,`paternal_urinary_tract_text`,`maternal_neurological_text`,`paternal_neurological_text`,`maternal_malignancy_text`,`paternal_malignancy_text`) VALUES ('$patient_id','$receipt_number','$status','$female_art_bank','$female_id_number','$female_photographs','$female_name','$female_dob','$female_age','$female_nationality','$female_mobile_no','$female_email_address','$female_present_address','$female_permanent_address','$female_aadhar_number','$female_pan_card_number','$female_religion','$female_monthly_income','$female_education','$female_occupation','$male_art_bank','$male_id_number','$male_photographs','$male_name','$male_dob','$male_age','$male_nationality','$male_mobile_no','$male_email_address','$male_present_address','$male_permanent_address','$male_aadhar_number','$male_pan_card_number','$male_religion','$male_monthly_income','$male_education','$male_occupation','$female_total_pregnancies','$female_live_births','$female_spontaneous_abortions','$female_termination_of_pregnancy','$female_still_births','$female_ectopic_pregnancy','$female_history_of_abnormality','$female_others','$male_total_pregnancies','$male_live_births','$male_spontaneous_abortions','$male_termination_of_pregnancy','$male_still_births','$male_ectopic_pregnancy','$male_history_of_abnormality','$male_others','$female_years_of_marriage','$female_contraception','$male_years_of_marriage','$male_contraception','$dysmenorrhea','$menorrhagia','$dandc','$dyspareunia','$others1','$age_of_menarche','$flow','$frequency','$days','$hirsutism','$galactorrhea','$heart_attack','$pacemaker','$other_heart_disease','$high_blood_pressure','$blood_clots','$chest_pain','$stroke','$asthma','$lung_disease','$difficulty_breathing','$snoring','$epilepsy','$fainting_spells','$diabetes','$muscle_disorders','$kidney_disease','$hepatitis','$tuberculosis','$hiv','$heart_burn','$cancer','$blood_disorders','$rheumatic_disease','$psychiatric_disorder','$thyroid_disorder','$urinary_infection','$sexually_transmitted','$abdominal_operations','$other_operations','$medications','$environmental_factors','$dentures','$loose_teeth','$hearing_aid','$caps_on_front_teeth','$contact_lenses','$body_piercing','$blood_transfusion','$traffic_accident','$smoke_past','$smoke_present','$drink_past','$drink_present','$abusive_drugs','$steroid','$medication','$herbal_products','$eye_drops','$non_prescription_drugs','$maternal_diabetes','$paternal_diabetes','$maternal_thrombo_embolism','$paternal_thrombo_embolism','$maternal_metabolic','$paternal_metabolic','$maternal_urinary_tract','$paternal_urinary_tract','$maternal_neurological','$paternal_neurological','$maternal_malignancy','$paternal_malignancy','$eggs_donated','$times_did_surrogacy','$nutritional_assessment','$psychological_assessment','$anxious','$colour_of_hair','$colour_of_eye','$colour_of_skin','$pulse','$blood_pressure','$cvs','$chest','$abdomen','$others2','$ps','$pv','$blood_group','$fsh','$e2','$lh','$amh','$prolactin','$tsh','$cbc','$pt_aptt_inr','$urine_routine','$blood_sugar','$hb_electrophoresis','$hiv1','$hbsag','$hepc','$vdrl','$rft','$lft','$usg_pelvis','$afc','$others3','$female_medicine_adviced','$male_medicine_adviced','$female_followup','$male_followup','$female_purpose','$male_purpose','$date','$time','$doctor_sign','$impairments','$heart_attack_text','$pacemaker_text','$other_heart_disease_text','$high_blood_pressure_text','$blood_clots_text','$chest_pain_text','$stroke_text','$asthma_text','$lung_disease_text','$difficulty_breathing_text','$snoring_text','$epilepsy_text','$fainting_spells_text','$diabetes_text','$muscle_disorders_text','$kidney_disease_text','$hepatitis_text','$tuberculosis_text','$hiv_text','$heart_burn_text','$cancer_text','$blood_disorders_text','$rheumatic_disease_text','$psychiatric_disorder_text','$thyroid_disorder_text','$urinary_infection_text','$sexually_transmitted_text','$abdominal_operations_text','$other_operations_text','$medications_text','$environmental_factors_text','$dentures_text','$loose_teeth_text','$hearing_aid_text','$caps_on_front_teeth_text','$contact_lenses_text','$body_piercing_text','$blood_transfusion_text','$traffic_accident_text','$smoke_past_text','$smoke_present_text','$drink_past_text','$drink_present_text','$abusive_drugs_text','$steroid_text','$medication_text','$herbal_products_text','$eye_drops_text','$non_prescription_drugs_text','$maternal_diabetes_text','$paternal_diabetes_text','$maternal_thrombo_embolism_text','$paternal_thrombo_embolism_text','$maternal_metabolic_text','$paternal_metabolic_text','$maternal_urinary_tract_text','$paternal_urinary_tract_text','$maternal_neurological_text','$paternal_neurological_text','$maternal_malignancy_text','$paternal_malignancy_text')";
// 	$result = run_form_query($query);

// 	if($result){
// 	header("location:" .base_url(). "procedure_reports/".$appointment_id."?m=".base64_encode('Procedure form inserted!').'&t='.base64_encode('success'));
// 	die();
// 	}else{
// 	header("location:" .base_url(). "procedure_reports/".$appointment_id."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
// 	die();
// 	}
// }

// php code to Insert data into mysql database from input text
if(isset($_POST['submit'])){
	unset($_POST['submit']);
	
	$_POST['female_photographs'] = '';
	if(!empty($_FILES['female_photographs']['tmp_name'])){
		$dest_path = $this->config->item('upload_path');
		$destination = $dest_path.'procedure-forms-uploads/';
		$NewImageName = rand(4,10000)."-".$_FILES['female_photographs']['name'];
		$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
		move_uploaded_file($_FILES['female_photographs']['tmp_name'], $destination.$NewImageName);
		$_POST['female_photographs'] = $transaction_img;
	}
	$_POST['male_photographs'] = '';
	if(!empty($_FILES['male_photographs']['tmp_name'])){
		$dest_path = $this->config->item('upload_path');
		$destination = $dest_path.'procedure-forms-uploads/';
		$NewImageName = rand(4,10000)."-".$_FILES['male_photographs']['name'];
		$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
		move_uploaded_file($_FILES['male_photographs']['tmp_name'], $destination.$NewImageName);
		$_POST['male_photographs'] = $transaction_img;
	}
	
	$select_query = "SELECT * FROM `donor_egg_personal_details` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$select_result = run_select_query($select_query); 
	if(empty($select_result)){
		// mysql query to insert data
		$query = "INSERT INTO `donor_egg_personal_details` SET ";
		$sqlArr = array();
		foreach( $_POST as $key=> $value )
		{
		  $sqlArr[] = " $key = '".addslashes($value)."'";
		}		
		$query .= implode(',' , $sqlArr);
	}else{
		// mysql query to update data
		$query = "UPDATE donor_egg_personal_details SET ";
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
$select_query = "SELECT * FROM `donor_egg_personal_details` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
$select_result = run_select_query($select_query); 

?>
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
			<td style="padding: 0;"><input type="text" name="female_art_bank" class="form-control" value="<?php echo isset($select_result['female_art_bank'])?$select_result['female_art_bank']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" name="male_art_bank" class="form-control" value="<?php echo isset($select_result['male_art_bank'])?$select_result['male_art_bank']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>ID NUMBER</th>
			<td style="padding: 0;"><input type="text" name="female_id_number" class="form-control" value="<?php echo isset($select_result['female_id_number'])?$select_result['female_id_number']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" name="male_id_number" class="form-control" value="<?php echo isset($select_result['male_id_number'])?$select_result['male_id_number']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>PHOTOGRAPHS</th>
			<td style="padding: 0;"><input type="file" name="female_photographs" class="form-control" >
			<a target="_blank" href="<?php echo !empty($select_result['female_photographs'])?$select_result['female_photographs']:"javascript:void(0)"; ?>">Download</a></td>
			<td style="padding: 0;"><input type="file" name="male_photographs" class="form-control" >
			<a target="_blank" href="<?php echo !empty($select_result['male_photographs'])?$select_result['male_photographs']:"javascript:void(0)"; ?>">Download</a></td>
		</tr>
		<tr>
			<th>NAME</th>
			<td style="padding: 0;"><input type="text" name="female_name" class="form-control" value="<?php echo isset($select_result['female_name'])?$select_result['female_name']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" name="male_name" class="form-control" value="<?php echo isset($select_result['male_name'])?$select_result['male_name']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>AGE (Years)</th>
			<td style="padding: 0;"><input type="number" name="female_age" class="form-control" value="<?php echo isset($select_result['female_age'])?$select_result['female_age']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="number" name="male_age" class="form-control" value="<?php echo isset($select_result['male_age'])?$select_result['male_age']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>OCCUPATION</th>
			<td style="padding: 0;"><input type="text" maxlength="15" name="female_occupation" class="form-control" value="<?php echo isset($select_result['female_occupation'])?$select_result['female_occupation']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" maxlength="15" name="male_occupation" class="form-control" value="<?php echo isset($select_result['male_occupation'])?$select_result['male_occupation']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>NATIONALITY</th>
			<td style="padding: 0;"><input type="text" maxlength="15" name="female_nationality" class="form-control" value="<?php echo isset($select_result['female_nationality'])?$select_result['female_nationality']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" maxlength="15" name="male_nationality" class="form-control" value="<?php echo isset($select_result['male_nationality'])?$select_result['male_nationality']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>ETHNICITY</th>
			<td style="padding: 0;"><input type="text" name="female_religion" class="form-control" value="<?php echo isset($select_result['female_religion'])?$select_result['female_religion']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" name="male_religion" class="form-control" value="<?php echo isset($select_result['male_religion'])?$select_result['male_religion']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>AADHAR NUMBER</th>
			<td style="padding: 0;"><input type="text" maxlength="50" name="female_aadhar_number" class="form-control" value="<?php echo isset($select_result['female_aadhar_number'])?$select_result['female_aadhar_number']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" maxlength="50" name="male_aadhar_number" class="form-control" value="<?php echo isset($select_result['male_aadhar_number'])?$select_result['male_aadhar_number']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>PRESENT ADDRESS</th>
			<td style="padding: 0;"><input type="text" maxlength="50" name="female_present_address" class="form-control" value="<?php echo isset($select_result['female_present_address'])?$select_result['female_present_address']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" maxlength="50" name="male_present_address" class="form-control" value="<?php echo isset($select_result['male_present_address'])?$select_result['male_present_address']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>PERMANENT ADDRESS</th>
			<td style="padding: 0;"><input type="text" maxlength="50" name="female_permanent_address" class="form-control" value="<?php echo isset($select_result['female_permanent_address'])?$select_result['female_permanent_address']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" maxlength="50" name="male_permanent_address" class="form-control" value="<?php echo isset($select_result['male_permanent_address'])?$select_result['male_permanent_address']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>CONTACT NO</th>
			<td style="padding: 0;"><input type="text" maxlength="50" name="female_mobile_no" class="form-control" value="<?php echo isset($select_result['female_mobile_no'])?$select_result['female_mobile_no']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" maxlength="50" name="male_mobile_no" class="form-control" value="<?php echo isset($select_result['male_mobile_no'])?$select_result['male_mobile_no']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>EMAIL ADDRESS</th>
			<td style="padding: 0;"><input type="text" maxlength="50" name="female_email_address" class="form-control" value="<?php echo isset($select_result['female_email_address'])?$select_result['female_email_address']:""; ?>"  ></td>
			<td style="padding: 0;"><input type="text" maxlength="50" name="male_email_address" class="form-control" value="<?php echo isset($select_result['male_email_address'])?$select_result['male_email_address']:""; ?>"  ></td>
		</tr>
		<tr>
			<th>WT/HT/BMI</th>
			<td></td>
			<td>NOT REQUIRED</td>
		</tr>
		<tr>
			<th>H/O previous pregnancies(IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Total pregnancies</td>
						<td><input type="number" max="20" min="0" name="female_total_pregnancies" class="form-control" value="<?php echo isset($select_result['female_total_pregnancies'])?$select_result['female_total_pregnancies']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>No.of live births</td>
						<td><input type="number" max="20" min="0" name="female_live_births" class="form-control" value="<?php echo isset($select_result['female_live_births'])?$select_result['female_live_births']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>No.of spontaneous abortions in first trimester</td>
						<td><input type="number" max="20" min="0" name="female_spontaneous_abortions" class="form-control" value="<?php echo isset($select_result['female_spontaneous_abortions'])?$select_result['female_spontaneous_abortions']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>No.of termination of pregnancy</td>
						<td><input type="number" max="20" min="0" name="female_termination_of_pregnancy" class="form-control" value="<?php echo isset($select_result['female_termination_of_pregnancy'])?$select_result['female_termination_of_pregnancy']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>No.of still births</td>
						<td><input type="number" max="20" min="0" name="female_still_births" class="form-control" value="<?php echo isset($select_result['female_still_births'])?$select_result['female_still_births']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>No. of ectopic pregnancy</td>
						<td><input type="number" max="20" min="0" name="female_ectopic_pregnancy" class="form-control" value="<?php echo isset($select_result['female_ectopic_pregnancy'])?$select_result['female_ectopic_pregnancy']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>History of any abnormality in child</td>
						<td>
							<input type="radio"  name="female_history_of_abnormality"   value="Yes"  <?php if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
							<input type="radio"  name="female_history_of_abnormality"   value="No"  <?php if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] != "Yes"){echo 'checked="checked"';}?>  > No
							<input type="text" maxlength="50" name="female_history_of_abnormality_text" class="form-control" value="<?php echo isset($select_result['female_history_of_abnormality_text'])?$select_result['female_history_of_abnormality_text']:""; ?>"  >
						</td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input type="text" maxlength="50" name="female_others" class="form-control" value="<?php echo isset($select_result['female_others'])?$select_result['female_others']:""; ?>"  ></td>
					</tr>
				</table>
			</td>
			<td style="padding: 0;"></td>
		</tr>
		<tr>
			<th>PAST GYNECOLOGICAL HISTORY</th>
			<td>
				<table width="100%">
					<tr>
						<td colspan="2">
							<input type="radio" <?php if(isset($select_result['dysmenorrhea']) && $select_result['dysmenorrhea'] == "Dysmenorrhea"){echo 'checked="checked"'; }?>   name="dysmenorrhea" value="Dysmenorrhea"> Dysmenorrhea
							<input type="radio" <?php if(isset($select_result['dysmenorrhea']) && $select_result['dysmenorrhea'] == "Menorrhagia"){echo 'checked="checked"'; }?>    name="dysmenorrhea" value="Menorrhagia"> Menorrhagia
							<input type="radio" <?php if(isset($select_result['dysmenorrhea']) && $select_result['dysmenorrhea'] == "H/o D and c"){echo 'checked="checked"'; }?>    name="dysmenorrhea" value="H/o D and c"> H/o D and c
							<input type="radio" <?php if(isset($select_result['dysmenorrhea']) && $select_result['dysmenorrhea'] == "Dyspareunia"){echo 'checked="checked"'; }?>   name="dysmenorrhea" value="Dyspareunia"> Dyspareunia
						</td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input type="text" name="others1" class="form-control" value="<?php echo isset($select_result['others1'])?$select_result['others1']:""; ?>"  ></td>
					</tr>
				</table>
			</td>
			<td>Not needed</td>
		</tr>
		<tr>
			<th>MENSTRUATION HISTORY</th>
			<td>
				<table width="100%">
					<tr>
						<td>Age at Menarche</td>
						<td><input type="number" min="0" name="age_of_menarche" class="form-control" value="<?php echo isset($select_result['age_of_menarche'])?$select_result['age_of_menarche']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>Flow</td>
						<td>
							<input type="radio" <?php if(isset($select_result['flow']) && $select_result['flow'] == "heavy"){echo 'checked="checked"'; }?>   name="flow" value="heavy"> heavy
							<input type="radio" <?php if(isset($select_result['flow']) && $select_result['flow'] == "average"){echo 'checked="checked"'; }?>   name="flow" value="average"> average
							<input type="radio" <?php if(isset($select_result['flow']) && $select_result['flow'] == "less"){echo 'checked="checked"'; }?>   name="flow" value="less"> less
						</td>
					</tr>
					<tr>
						<td>Frequency</td>
						<td>
							<input type="radio" <?php if(isset($select_result['frequency']) && $select_result['frequency'] == "regular"){echo 'checked="checked"'; }?>   name="frequency" value="regular"> regular
							<input type="radio" <?php if(isset($select_result['frequency']) && $select_result['frequency'] == "irregular"){echo 'checked="checked"'; }?>   name="frequency" value="irregular"> irregular
						</td>
					</tr>
					<tr>
						<td>Days</td>
						<td><input type="text" maxlength="10" name="days" class="form-control" value="<?php echo isset($select_result['days'])?$select_result['days']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>Hirsutism</td>
						<td>
							<input type="radio"  name="hirsutism"   value="Yes"  <?php if(isset($select_result['hirsutism']) && $select_result['hirsutism'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
							<input type="radio"  name="hirsutism"   value="No"  <?php if(isset($select_result['hirsutism']) && $select_result['hirsutism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hirsutism']) && $select_result['hirsutism'] != "Yes"){echo 'checked="checked"';}?>  > No
						</td>
					</tr>
					<tr>
						<td>Galactorrhea</td>
						<td>
							<input type="radio"  name="galactorrhea"   value="Yes"  <?php if(isset($select_result['galactorrhea']) && $select_result['galactorrhea'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
							<input type="radio"  name="galactorrhea"   value="No"  <?php if(isset($select_result['galactorrhea']) && $select_result['galactorrhea'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['galactorrhea']) && $select_result['galactorrhea'] != "Yes"){echo 'checked="checked"';}?>  > No
						</td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<tr>
			<th>PAST MEDICAL HISTORY</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>
							<input type="radio"  id="text1" name="heart_attack"   value="Yes"  <?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="heart_attack"   value="No"  <?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['heart_attack']) && $select_result['heart_attack'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['heart_attack_text'])?$select_result['heart_attack_text']:""; ?>"      maxlength="25" name="heart_attack_text">
						</td>
						<td>Heart attack</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="pacemaker"   value="Yes"  <?php if(isset($select_result['pacemaker']) && $select_result['pacemaker'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="pacemaker"   value="No"  <?php if(isset($select_result['pacemaker']) && $select_result['pacemaker'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pacemaker']) && $select_result['pacemaker'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['pacemaker_text'])?$select_result['pacemaker_text']:""; ?>"     maxlength="25" name="pacemaker_text">
						</td>
						<td>Pacemaker</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="other_heart_disease"   value="Yes"  <?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="other_heart_disease"   value="No"  <?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['other_heart_disease_text'])?$select_result['other_heart_disease_text']:""; ?>"     maxlength="25" name="other_heart_disease_text">
						</td>
						<td>Other heart disease</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="high_blood_pressure"   value="Yes"  <?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="high_blood_pressure"   value="No"  <?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['high_blood_pressure_text'])?$select_result['high_blood_pressure_text']:""; ?>"     maxlength="25" name="high_blood_pressure_text">
						</td>
						<td>High blood pressure</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="blood_clots"   value="Yes"  <?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="blood_clots"   value="No"  <?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['blood_clots']) && $select_result['blood_clots'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['blood_clots_text'])?$select_result['blood_clots_text']:""; ?>"     maxlength="25" name="blood_clots_text">
						</td>
						<td>Blood clots</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="chest_pain"   value="Yes"  <?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="chest_pain"   value="No"  <?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['chest_pain']) && $select_result['chest_pain'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['chest_pain_text'])?$select_result['chest_pain_text']:""; ?>"     maxlength="25" name="chest_pain_text">
						</td>
						<td>Chest pain</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="stroke"   value="Yes"  <?php if(isset($select_result['stroke']) && $select_result['stroke'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="stroke"   value="No"  <?php if(isset($select_result['stroke']) && $select_result['stroke'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['stroke']) && $select_result['stroke'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['stroke_text'])?$select_result['stroke_text']:""; ?>"     maxlength="25" name="stroke_text">
						</td>
						<td>Stroke</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="asthma"   value="Yes"  <?php if(isset($select_result['asthma']) && $select_result['asthma'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="asthma"   value="No"  <?php if(isset($select_result['asthma']) && $select_result['asthma'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['asthma']) && $select_result['asthma'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['asthma_text'])?$select_result['asthma_text']:""; ?>"     maxlength="25" name="asthma_text">
						</td>
						<td>Asthma</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="lung_disease"   value="Yes"  <?php if(isset($select_result['lung_disease']) && $select_result['lung_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="lung_disease"   value="No"  <?php if(isset($select_result['lung_disease']) && $select_result['lung_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['lung_disease']) && $select_result['lung_disease'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['lung_disease_text'])?$select_result['lung_disease_text']:""; ?>"     maxlength="25" name="lung_disease_text">
						</td>
						<td>Other lung disease</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="difficulty_breathing"   value="Yes"  <?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="difficulty_breathing"   value="No"  <?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['difficulty_breathing_text'])?$select_result['difficulty_breathing_text']:""; ?>"     maxlength="25" name="difficulty_breathing_text">
						</td>
						<td>Difficulty breathing</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="snoring"   value="Yes"  <?php if(isset($select_result['snoring']) && $select_result['snoring'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="snoring"   value="No"  <?php if(isset($select_result['snoring']) && $select_result['snoring'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['snoring']) && $select_result['snoring'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['snoring_text'])?$select_result['snoring_text']:""; ?>"     maxlength="25" name="snoring_text">
						</td>
						<td>Sleep apnea or snoring</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="epilepsy"   value="Yes"  <?php if(isset($select_result['epilepsy']) && $select_result['epilepsy'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="epilepsy"   value="No"  <?php if(isset($select_result['epilepsy']) && $select_result['epilepsy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['epilepsy']) && $select_result['epilepsy'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['epilepsy_text'])?$select_result['epilepsy_text']:""; ?>"     maxlength="25" name="epilepsy_text">
						</td>
						<td>Epilepsy or seizures</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="fainting_spells"   value="Yes"  <?php if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="fainting_spells"   value="No"  <?php if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['fainting_spells_text'])?$select_result['fainting_spells_text']:""; ?>"     maxlength="25" name="fainting_spells_text">
						</td>
						<td>Fainting spells</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="diabetes"   value="Yes"  <?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="diabetes"   value="No"  <?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['diabetes']) && $select_result['diabetes'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>"     maxlength="25" name="diabetes_text">
						</td>
						<td>Diabetes</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="muscle_disorders"   value="Yes"  <?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="muscle_disorders"   value="No"  <?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['muscle_disorders_text'])?$select_result['muscle_disorders_text']:""; ?>"     maxlength="25" name="muscle_disorders_text">
						</td>
						<td>Muscle disorders</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="kidney_disease"   value="Yes"  <?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="kidney_disease"   value="No"  <?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['kidney_disease_text'])?$select_result['kidney_disease_text']:""; ?>"     maxlength="25" name="kidney_disease_text">
						</td>
						<td>Kidney disease</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="hepatitis"   value="Yes"  <?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="hepatitis"   value="No"  <?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hepatitis']) && $select_result['hepatitis'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['hepatitis_text'])?$select_result['hepatitis_text']:""; ?>"     maxlength="25" name="hepatitis_text">
						</td>
						<td>Hepatitis</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="tuberculosis"   value="Yes"  <?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="tuberculosis"   value="No"  <?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['tuberculosis_text'])?$select_result['tuberculosis_text']:""; ?>"     maxlength="25" name="tuberculosis_text">
						</td>
						<td>Tuberculosis</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="hiv"   value="Yes"  <?php if(isset($select_result['hiv']) && $select_result['hiv'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="hiv"   value="No"  <?php if(isset($select_result['hiv']) && $select_result['hiv'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hiv']) && $select_result['hiv'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['hiv_text'])?$select_result['hiv_text']:""; ?>"     maxlength="25" name="hiv_text">
						</td>
						<td>HIV</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="heart_burn"   value="Yes"  <?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="heart_burn"   value="No"  <?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['heart_burn']) && $select_result['heart_burn'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['heart_burn_text'])?$select_result['heart_burn_text']:""; ?>"     maxlength="25" name="heart_burn_text">
						</td>
						<td>Heart burn/reflux</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="cancer"   value="Yes"  <?php if(isset($select_result['cancer']) && $select_result['cancer'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="cancer"   value="No"  <?php if(isset($select_result['cancer']) && $select_result['cancer'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cancer']) && $select_result['cancer'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['cancer_text'])?$select_result['cancer_text']:""; ?>"     maxlength="25" name="cancer_text">
						</td>
						<td>Cancer</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="blood_disorders"   value="Yes"  <?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="blood_disorders"   value="No"  <?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['blood_disorders_text'])?$select_result['blood_disorders_text']:""; ?>"     maxlength="25" name="blood_disorders_text">
						</td>
						<td>Blood disorders</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="rheumatic_disease"   value="Yes"  <?php if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="rheumatic_disease"   value="No"  <?php if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['rheumatic_disease_text'])?$select_result['rheumatic_disease_text']:""; ?>"     maxlength="25" name="rheumatic_disease_text">
						</td>
						<td>Rheumatic disease</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="psychiatric_disorder"   value="Yes"  <?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="psychiatric_disorder"   value="No"  <?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['psychiatric_disorder_text'])?$select_result['psychiatric_disorder_text']:""; ?>"     maxlength="25" name="psychiatric_disorder_text">
						</td>
						<td>Psychiatric disorder</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="thyroid_disorder"   value="Yes"  <?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="thyroid_disorder"   value="No"  <?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['thyroid_disorder_text'])?$select_result['thyroid_disorder_text']:""; ?>"     maxlength="25" name="thyroid_disorder_text">
						</td>
						<td>Thyroid disorder</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="urinary_infection"   value="Yes"  <?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="urinary_infection"   value="No"  <?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['urinary_infection_text'])?$select_result['urinary_infection_text']:""; ?>"     maxlength="25" name="urinary_infection_text">
						</td>
						<td>Urinary infection</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="sexually_transmitted"   value="Yes"  <?php if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="sexually_transmitted"   value="No"  <?php if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['sexually_transmitted_text'])?$select_result['sexually_transmitted_text']:""; ?>"     maxlength="25" name="sexually_transmitted_text">
						</td>
						<td>Sexually transmitted disease</td>
					</tr>
					<tr>
						<td><input type="text" maxlength="25" class="form-control" value="<?php echo isset($select_result['impairments'])?$select_result['impairments']:""; ?>"   name="impairments"></td>
						<td>Other medical condition or impairments</td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<tr>
			<th>PAST SURGICAL HISTORY</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>
							<input type="radio"  id="text1" name="abdominal_operations"   value="Yes"  <?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="abdominal_operations"   value="No"  <?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['abdominal_operations_text'])?$select_result['abdominal_operations_text']:""; ?>"     maxlength="25" name="abdominal_operations_text">
						</td>
						<td>Laparoscopy/pelvic/abdominal operations</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="other_operations"   value="Yes"  <?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="other_operations"   value="No"  <?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['other_operations']) && $select_result['other_operations'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['other_operations_text'])?$select_result['other_operations_text']:""; ?>"     maxlength="50" name="other_operations_text">
						</td>
						<td>Other operations</td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<tr>
			<th>Allergy history</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>
							<input type="radio"  id="text1" name="medications"   value="Yes"  <?php if(isset($select_result['medications']) && $select_result['medications'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="medications"   value="No"  <?php if(isset($select_result['medications']) && $select_result['medications'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['medications']) && $select_result['medications'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['medications_text'])?$select_result['medications_text']:""; ?>"     maxlength="25" name="medications_text">
						</td>
						<td>Medications</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="environmental_factors"   value="Yes"  <?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="environmental_factors"   value="No"  <?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['environmental_factors_text'])?$select_result['environmental_factors_text']:""; ?>"     maxlength="25" name="environmental_factors_text">
						</td>
						<td>environmental factors</td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<tr>
			<th>SOCIAL & DRUG INTAKE HISTORY</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td width="20%">
							<input type="radio"  id="text1" name="dentures"   value="Yes"  <?php if(isset($select_result['dentures']) && $select_result['dentures'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="dentures"   value="No"  <?php if(isset($select_result['dentures']) && $select_result['dentures'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['dentures']) && $select_result['dentures'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['dentures_text'])?$select_result['dentures_text']:""; ?>"     maxlength="25" name="dentures_text">
						</td>
						<td>Dentures</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="loose_teeth"   value="Yes"  <?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="loose_teeth"   value="No"  <?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['loose_teeth_text'])?$select_result['loose_teeth_text']:""; ?>"     maxlength="25" name="loose_teeth_text">
						</td>
						<td>Loose teeth</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="hearing_aid"   value="Yes"  <?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="hearing_aid"   value="No"  <?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['hearing_aid_text'])?$select_result['hearing_aid_text']:""; ?>"     maxlength="25" name="hearing_aid_text">
						</td>
						<td>Hearing aid</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="caps_on_front_teeth"   value="Yes"  <?php if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="caps_on_front_teeth"   value="No"  <?php if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['caps_on_front_teeth_text'])?$select_result['caps_on_front_teeth_text']:""; ?>"     maxlength="25" name="caps_on_front_teeth_text">
						</td>
						<td>Caps on front teeth</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="contact_lenses"   value="Yes"  <?php if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="contact_lenses"   value="No"  <?php if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['contact_lenses_text'])?$select_result['contact_lenses_text']:""; ?>"     maxlength="25" name="contact_lenses_text">
						</td>
						<td>Contact lenses</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="body_piercing"   value="Yes"  <?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="body_piercing"   value="No"  <?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['body_piercing']) && $select_result['body_piercing'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['body_piercing_text'])?$select_result['body_piercing_text']:""; ?>"     maxlength="25" name="body_piercing_text">
						</td>
						<td>Body piercing</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="blood_transfusion"   value="Yes"  <?php if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="blood_transfusion"   value="No"  <?php if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['blood_transfusion_text'])?$select_result['blood_transfusion_text']:""; ?>"     maxlength="25" name="blood_transfusion_text">
						</td>
						<td>H/o blood transfusion</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="traffic_accident"   value="Yes"  <?php if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="traffic_accident"   value="No"  <?php if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['traffic_accident_text'])?$select_result['traffic_accident_text']:""; ?>"     maxlength="25" name="traffic_accident_text">
						</td>
						<td>H/o road traffic accident/any injury</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="smoke_past"   value="Yes"  <?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="smoke_past"   value="No"  <?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['smoke_past']) && $select_result['smoke_past'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['smoke_past_text'])?$select_result['smoke_past_text']:""; ?>"     maxlength="25" name="smoke_past_text">
						</td>
						<td>Smoke(past)daily</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="smoke_present"   value="Yes"  <?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="smoke_present"   value="No"  <?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['smoke_present']) && $select_result['smoke_present'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['smoke_present_text'])?$select_result['smoke_present_text']:""; ?>"     maxlength="25" name="smoke_present_text">
						</td>
						<td>Smoke(present)daily</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="drink_past"   value="Yes"  <?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="drink_past"   value="No"  <?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['drink_past']) && $select_result['drink_past'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['drink_past_text'])?$select_result['drink_past_text']:""; ?>"     maxlength="25" name="drink_past_text">
						</td>
						<td>Drink(past)units per week</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="drink_present"   value="Yes"  <?php if(isset($select_result['drink_present']) && $select_result['drink_present'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="drink_present"   value="No"  <?php if(isset($select_result['drink_present']) && $select_result['drink_present'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['drink_present']) && $select_result['drink_present'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['drink_present_text'])?$select_result['drink_present_text']:""; ?>"     maxlength="25" name="drink_present_text">
						</td>
						<td>Drink(present)units per week</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="abusive_drugs"   value="Yes"  <?php if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="abusive_drugs"   value="No"  <?php if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['abusive_drugs_text'])?$select_result['abusive_drugs_text']:""; ?>"     maxlength="25" name="abusive_drugs_text">
						</td>
						<td>Hashish/cocaine /abusive drugs</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="steroid"   value="Yes"  <?php if(isset($select_result['steroid']) && $select_result['steroid'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="steroid"   value="No"  <?php if(isset($select_result['steroid']) && $select_result['steroid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['steroid']) && $select_result['steroid'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['steroid_text'])?$select_result['steroid_text']:""; ?>"     maxlength="25" name="steroid_text">
						</td>
						<td>Have you ever used cortisone/steroid</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="medication"   value="Yes"  <?php if(isset($select_result['medication']) && $select_result['medication'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="medication"   value="No"  <?php if(isset($select_result['medication']) && $select_result['medication'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['medication']) && $select_result['medication'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['medication_text'])?$select_result['medication_text']:""; ?>"     maxlength="25" name="medication_text">
						</td>
						<td>Medication</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="herbal_products"   value="Yes"  <?php if(isset($select_result['herbal_products']) && $select_result['herbal_products'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="herbal_products"   value="No"  <?php if(isset($select_result['herbal_products']) && $select_result['herbal_products'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['herbal_products']) && $select_result['herbal_products'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['herbal_products_text'])?$select_result['herbal_products_text']:""; ?>"     maxlength="25" name="herbal_products_text">
						</td>
						<td>Herbal products</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="eye_drops"   value="Yes"  <?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="eye_drops"   value="No"  <?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['eye_drops']) && $select_result['eye_drops'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['eye_drops_text'])?$select_result['eye_drops_text']:""; ?>"     maxlength="25" name="eye_drops_text">
						</td>
						<td>Eye drops</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="non_prescription_drugs"   value="Yes"  <?php if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="non_prescription_drugs"   value="No"  <?php if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['non_prescription_drugs_text'])?$select_result['non_prescription_drugs_text']:""; ?>"     maxlength="25" name="non_prescription_drugs_text">
						</td>
						<td>Non prescription drugs used currently other than medications used for this IVF cycle</td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<tr>
			<th>FAMILY HISTORY</th>
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
							<input type="radio"  name="maternal_diabetes"   value="Yes"  <?php if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="maternal_diabetes"   value="No"  <?php if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['maternal_diabetes_text'])?$select_result['maternal_diabetes_text']:""; ?>"     maxlength="25" name="maternal_diabetes_text">
						</td>
						<td>
							<input type="radio"  name="paternal_diabetes"   value="Yes"  <?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_diabetes"   value="No"  <?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['paternal_diabetes_text'])?$select_result['paternal_diabetes_text']:""; ?>"     maxlength="25" name="paternal_diabetes_text">
						</td>
					</tr>
					<tr>
						<td>Heart/thrombo embolism</td>
						<td>
							<input type="radio"  name="maternal_thrombo_embolism"   value="Yes"  <?php if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="maternal_thrombo_embolism"   value="No"  <?php if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['maternal_thrombo_embolism_text'])?$select_result['maternal_thrombo_embolism_text']:""; ?>"     maxlength="25" name="maternal_thrombo_embolism_text">
						</td>
						<td>
							<input type="radio"  name="paternal_thrombo_embolism"   value="Yes"  <?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_thrombo_embolism"   value="No"  <?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['paternal_thrombo_embolism_text'])?$select_result['paternal_thrombo_embolism_text']:""; ?>"     maxlength="25" name="paternal_thrombo_embolism_text">
						</td>
					</tr>
					<tr>
						<td>Endocrine/metabolic</td>
						<td>
							<input type="radio"  name="maternal_metabolic"   value="Yes"  <?php if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="maternal_metabolic"   value="No"  <?php if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['maternal_metabolic_text'])?$select_result['maternal_metabolic_text']:""; ?>"     maxlength="25" name="maternal_metabolic_text">
						</td>
						<td>
							<input type="radio"  name="paternal_metabolic"   value="Yes"  <?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_metabolic"   value="No"  <?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['paternal_metabolic_text'])?$select_result['paternal_metabolic_text']:""; ?>"     maxlength="25" name="paternal_metabolic_text">
						</td>
					</tr>
					<tr>
						<td>Urinary tract/renal</td>
						<td>
							<input type="radio"  name="maternal_urinary_tract"   value="Yes"  <?php if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="maternal_urinary_tract"   value="No"  <?php if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['maternal_urinary_tract_text'])?$select_result['maternal_urinary_tract_text']:""; ?>"     maxlength="25" name="maternal_urinary_tract_text">
						</td>
						<td>
							<input type="radio"  name="paternal_urinary_tract"   value="Yes"  <?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_urinary_tract"   value="No"  <?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['paternal_urinary_tract_text'])?$select_result['paternal_urinary_tract_text']:""; ?>"     maxlength="25" name="paternal_urinary_tract_text">
						</td>
					</tr>
					<tr>
						<td>Psychiatric/neurological</td>
						<td>
							<input type="radio"  name="maternal_neurological"   value="Yes"  <?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="maternal_neurological"   value="No"  <?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['maternal_neurological_text'])?$select_result['maternal_neurological_text']:""; ?>"     maxlength="25" name="maternal_neurological_text">
						</td>
						<td>
							<input type="radio"  name="paternal_neurological"   value="Yes"  <?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_neurological"   value="No"  <?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['paternal_neurological_text'])?$select_result['paternal_neurological_text']:""; ?>"     maxlength="25" name="paternal_neurological_text">
						</td>
					</tr>
					<tr>
						<td>Other/malignancy</td>
						<td>
							<input type="radio"  name="maternal_malignancy"   value="Yes"  <?php if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="maternal_malignancy"   value="No"  <?php if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text" value="<?php echo isset($select_result['maternal_malignancy_text'])?$select_result['maternal_malignancy_text']:""; ?>"     maxlength="25" name="maternal_malignancy_text">
						</td>
						<td>
							<input type="radio"  name="paternal_malignancy"   value="Yes"  <?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_malignancy"   value="No"  <?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input type="text"  value="<?php echo isset($select_result['paternal_malignancy_text'])?$select_result['paternal_malignancy_text']:""; ?>"     maxlength="25" name="paternal_malignancy_text">
						</td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<tr>
			<th>PREVIOUS HISTORY OF THIRD PARTY REPRODUCTION</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>NO. OF TIMES EGG DONATED</td>
						<td><input type="number" min="0" name="eggs_donated" class="form-control" value="<?php echo isset($select_result['eggs_donated'])?$select_result['eggs_donated']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>COMPLICATIONS</td>
						<td><input type="text" maxlength="50" name="times_did_surrogacy_complicated" class="form-control" value="<?php echo isset($select_result['times_did_surrogacy_complicated'])?$select_result['times_did_surrogacy_complicated']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>NO. OF TIMES SURROGACY DONE</td>
						<td><input type="number" min="0" name="eggs_donated" class="form-control" value="<?php echo isset($select_result['eggs_donated'])?$select_result['eggs_donated']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>COMPLICATIONS</td>
						<td><input type="text" maxlength="50" name="times_did_surrogacy_complicated" class="form-control" value="<?php echo isset($select_result['times_did_surrogacy_complicated'])?$select_result['times_did_surrogacy_complicated']:""; ?>"  ></td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<tr>
			<th>GENERAL EXAMINATION</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Nutritional assessment</td>
						<td>
							<input type="radio" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "Obese"){echo 'checked="checked"'; }?>   name="nutritional_assessment" value="Obese">
							<label>Obese</label>
							<input type="radio" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "average built"){echo 'checked="checked"'; }?>   name="nutritional_assessment" value="average built">
							<label>average built</label>
							<input type="radio" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "thin"){echo 'checked="checked"'; }?>   name="nutritional_assessment" value="thin">
							<label>thin</label>
							<input type="radio" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "cachexic"){echo 'checked="checked"'; }?>   name="nutritional_assessment" value="cachexic">
							<label>cachexic</label>
						</td>
					</tr>
					<tr>
						<td>Psychological assessment</td>
						<td>
							<input type="radio" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "Anxious"){echo 'checked="checked"'; }?>   name="psychological_assessment" value="Anxious">
							<label>Anxious</label>
							<input type="radio" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "combative"){echo 'checked="checked"'; }?>   name="psychological_assessment" value="combative">
							<label>combative</label>
							<input type="radio" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "depressed"){echo 'checked="checked"'; }?>   name="psychological_assessment" value="depressed">
							<label>depressed</label>
							<input type="radio" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "normal"){echo 'checked="checked"'; }?>   name="psychological_assessment" value="normal">
							<label>normal</label>
						</td>
					</tr>
					<tr>
						<td>Pulse (PER MIN)</td>
						<td><input type="number" min="0" name="pulse" class="form-control" value="<?php echo isset($select_result['pulse'])?$select_result['pulse']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>Blood pressure (MM HG)</td>
						<td>
							<table width="100%">
								<tr>
									<td>SYSTOLIC</td>
									<td>
										<input type="number" min="0" name="systolic_pulse" class="form-control" value="<?php echo isset($select_result['systolic_pulse'])?$select_result['systolic_pulse']:""; ?>"  >
									</td>
								</tr>
								<tr>
									<td>DIASTOLIC</td>
									<td>
										<input type="number" min="0" name="diastolic_pulse" class="form-control" value="<?php echo isset($select_result['diastolic_pulse'])?$select_result['diastolic_pulse']:""; ?>"  >
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>Temperature(DEG F):</td>
						<td><input type="text" name="temperature" class="form-control" value="<?php echo isset($select_result['temperature'])?$select_result['temperature']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>CVS</td>
						
						
						<td>
							<input type="radio"  name="cvs"   value="Yes"  <?php if(isset($select_result['cvs']) && $select_result['cvs'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
							<input type="radio"  name="cvs"   value="No"  <?php if(isset($select_result['cvs']) && $select_result['cvs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cvs']) && $select_result['cvs'] != "Yes"){echo 'checked="checked"';}?>  > No
							<input type="text" maxlength="50" name="cvs_text" class="form-control" value="<?php echo isset($select_result['cvs_text'])?$select_result['cvs_text']:""; ?>"  >
						</td>	
						
						
						
						
						
					</tr>
					<tr>
						<td>Chest</td>
					
							<td>
							<input type="radio"  name="chest"   value="Yes"  <?php if(isset($select_result['chest']) && $select_result['chest'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
							<input type="radio"  name="chest"   value="No"  <?php if(isset($select_result['chest']) && $select_result['chest'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['chest']) && $select_result['chest'] != "Yes"){echo 'checked="checked"';}?>  > No
							<input type="text" maxlength="50" name="chest_text" class="form-control" value="<?php echo isset($select_result['chest_text'])?$select_result['chest_text']:""; ?>"  >
						</td>
						
						
						
						
						
					</tr>
					<tr>
						<td>Abdomen</td>
						<td><input type="text" name="abdomen_text" class="form-control" value="<?php echo isset($select_result['abdomen_text'])?$select_result['abdomen_text']:""; ?>"  ></td>
						<td>
							<input type="radio"  name="abdomen"   value="Yes"  <?php if(isset($select_result['children']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="abdomen"   value="No"  <?php if(isset($select_result['children']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['children']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
						</td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input type="text" name="others2" class="form-control" value="<?php echo isset($select_result['others2'])?$select_result['others2']:""; ?>"  ></td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<tr>
			<th>LOCAL EXAMINATION</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>P/S</td>
					
						
						<td>
							<input type="radio"  name="ps"   value="Yes"  <?php if(isset($select_result['ps']) && $select_result['ps'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
							<input type="radio"  name="ps"   value="No"  <?php if(isset($select_result['ps']) && $select_result['ps'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['ps']) && $select_result['ps'] != "Yes"){echo 'checked="checked"';}?>  > No
							<input type="text" maxlength="50" name="ps_text" class="form-control" value="<?php echo isset($select_result['ps_text'])?$select_result['ps_text']:""; ?>"  >
						</td>
						
						
						
						
						
						
						
						
						
					</tr>
					<tr>
						<td>P/V</td>
						<td>
							<input type="radio"  name="pv"   value="Yes"<?php if(isset($select_result['pv']) && $select_result['pv'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
							<input type="radio"  name="pv"   value="No" <?php if(isset($select_result['pv']) && $select_result['pv'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pv']) && $select_result['pv'] != "Yes"){echo 'checked="checked"';}?>  > No
							<input type="text" maxlength="50" name="pv_text" class="form-control" value="<?php echo isset($select_result['pv_text'])?$select_result['pv_text']:""; ?>"  >
						</td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<tr>
			<th>INVESTIGATIONS ADVICED(TO BE PICKED FROM BILLING INVESTIGATION LIST)</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Blood group and Rh typing</td>
						<td><input type="text" name="blood_group" class="form-control" value="<?php echo isset($select_result['blood_group'])?$select_result['blood_group']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>FSH</td>
						<td><input type="text" name="fsh" class="form-control" value="<?php echo isset($select_result['fsh'])?$select_result['fsh']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>E2</td>
						<td><input type="text" name="e2" class="form-control" value="<?php echo isset($select_result['e2'])?$select_result['e2']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>LH</td>
						<td><input type="text" name="lh" class="form-control" value="<?php echo isset($select_result['lh'])?$select_result['lh']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>AMH</td>
						<td><input type="text" name="amh" class="form-control" value="<?php echo isset($select_result['amh'])?$select_result['amh']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>Prolactin</td>
						<td><input type="text" name="prolactin" class="form-control" value="<?php echo isset($select_result['prolactin'])?$select_result['prolactin']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>TSH</td>
						<td><input type="text" name="tsh" class="form-control" value="<?php echo isset($select_result['tsh'])?$select_result['tsh']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>CBC</td>
						<td><input type="text" name="cbc" class="form-control" value="<?php echo isset($select_result['cbc'])?$select_result['cbc']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>PT,APTT,INR</td>
						<td><input type="text" name="pt_aptt_inr" class="form-control" value="<?php echo isset($select_result['pt_aptt_inr'])?$select_result['pt_aptt_inr']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>Urine routine microscopy</td>
						<td><input type="text" name="urine_routine" class="form-control" value="<?php echo isset($select_result['urine_routine'])?$select_result['urine_routine']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>Blood sugar</td>
						<td><input type="text" name="blood_sugar" class="form-control" value="<?php echo isset($select_result['blood_sugar'])?$select_result['blood_sugar']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>Hb Electrophoresis</td>
						<td><input type="text" name="hb_electrophoresis" class="form-control" value="<?php echo isset($select_result['hb_electrophoresis'])?$select_result['hb_electrophoresis']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>HIV</td>
						<td><input type="text" name="hiv1" class="form-control" value="<?php echo isset($select_result['hiv1'])?$select_result['hiv1']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>Hbsag</td>
						<td><input type="text" name="hbsag" class="form-control" value="<?php echo isset($select_result['hbsag'])?$select_result['hbsag']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>Hep C</td>
						<td><input type="text" name="hepc" class="form-control" value="<?php echo isset($select_result['hepc'])?$select_result['hepc']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>VDRL</td>
						<td><input type="text" name="vdrl" class="form-control" value="<?php echo isset($select_result['vdrl'])?$select_result['vdrl']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>RFT</td>
						<td><input type="text" name="rft" class="form-control" value="<?php echo isset($select_result['rft'])?$select_result['rft']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>LFT</td>
						<td><input type="text" name="lft" class="form-control" value="<?php echo isset($select_result['lft'])?$select_result['lft']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>USG pelvis</td>
						<td><input type="text" name="usg_pelvis" class="form-control" value="<?php echo isset($select_result['usg_pelvis'])?$select_result['usg_pelvis']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>AFC</td>
						<td><input type="text" name="afc" class="form-control" value="<?php echo isset($select_result['afc'])?$select_result['afc']:""; ?>"  ></td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input type="text" name="others3" class="form-control" value="<?php echo isset($select_result['others3'])?$select_result['others3']:""; ?>"  ></td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<!-- <tr>
			<th>MEDICINES ADVISED(TO BE MAPPED FROM INVENTORY)</th>
			<td style="padding: 0;">
				<input type="text" name="female_medicine_adviced" class="form-control" value="<?php echo isset($select_result['female_medicine_adviced'])?$select_result['female_medicine_adviced']:""; ?>"  >
				<table>
					<tr>
						<td></td>
						<td>MEDICINE NAME</td>
						<td>DOSAGE</td>
						<td>ROUTE</td>
						<td>FREQUENCY</td>
						<td>TIMINGS</td>
						<td>WHEN TO START</td>
						<td>DAYS</td>
					</tr>
					<tr>
						<td></td>
						<td><input type="text" maxlength="20" name="" class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  ></td>
						<td><input type="text" maxlength="20" name="" class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  ></td>
						<td>
							<select class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  >
								<option value="PO">PO</option>
								<option value="IM">IM</option>
								<option value="SC">SC</option>
								<option value="VAGINA-LY">VAGINA-LY</option>
								<option value="IV">IV</option>
								<option value="LOCAL">LOCAL</option>
								<option value="NASALY">NASALY</option>
							</select>
						</td>
						<td>
							<select class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  >
								<option value="OD">OD</option>
								<option value="BD">BD</option>
								<option value="TDS">TDS</option>
								<option value="QID">QID</option>
								<option value="SOS">SOS</option>
								<option value="HS">HS</option>
							</select>
						</td>
						<td>
							<select class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  >
								<option value="EMPTY STOMACH">EMPTY STOMACH</option>
								<option value="BEFORE MEAL">BEFORE MEAL</option>
								<option value="AFTER MEAL">AFTER MEAL</option>
							</select>
						</td>
						<td><input type="text" maxlength="20" name="" class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  ></td>
						<td><input type="number" min="0" name="" class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  ></td>
					</tr>
				</table>
			</td>
			<td style="padding: 0;">
				<input type="text" name="female_medicine_adviced" class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  >
				<table>
					<tr>
						<td></td>
						<td>MEDICINE NAME</td>
						<td>DOSAGE</td>
						<td>ROUTE</td>
						<td>FREQUENCY</td>
						<td>TIMINGS</td>
						<td>WHEN TO START</td>
						<td>DAYS</td>
					</tr>
					<tr>
						<td></td>
						<td>Mapped to Purpose of consultation</td>
						<td><input type="text" maxlength="20" name="" class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  ></td>
						<td>
							<select class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  >
								<option value="PO">PO</option>
								<option value="IM">IM</option>
								<option value="SC">SC</option>
								<option value="VAGINA-LY">VAGINA-LY</option>
								<option value="IV">IV</option>
								<option value="LOCAL">LOCAL</option>
								<option value="NASALY">NASALY</option>
							</select>
						</td>
						<td>
							<select class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  >
								<option value="OD">OD</option>
								<option value="BD">BD</option>
								<option value="TDS">TDS</option>
								<option value="QID">QID</option>
								<option value="SOS">SOS</option>
								<option value="HS">HS</option>
							</select>
						</td>
						<td>
							<select class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  >
								<option value="EMPTY STOMACH">EMPTY STOMACH</option>
								<option value="BEFORE MEAL">BEFORE MEAL</option>
								<option value="AFTER MEAL">AFTER MEAL</option>
							</select>
						</td>
						<td><input type="text" maxlength="20" name="" class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  ></td>
						<td><input type="number" min="0" name="" class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  ></td>
					</tr>
				</table>
			</td>
		</tr> -->
		<!-- <tr>
			<th>NEXT FOLLOW UP</th>
			<td><input type="date" name="female_followup" class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  ></td>
			<td><input type="date" name="male_followup" class="form-control" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"  ></td>
		</tr><br>
		<tr>
			<th>PURPOSE OF NEXT FOLLOWUP</th>
			<td>
				<input type="radio"  name="pv" value="FIRST CONSULTATION">
				<label>FIRST CONSULTATION</label>
				<input type="radio"  name="pv" value="FOLLOW UP VISIT">
				<label>FOLLOW UP VISIT</label>
				<input type="radio"  name="pv" value="PROCEDURE">
				<label>PROCEDURE</label><br>
				Mapped to Purpose of consultation
			</td>
			<td>Mapped to Purpose of consultation</td>
		</tr> -->
		<tr>
			<th></th>
			<td>Date & Time: <input type="date" name="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"> <input type="time" name="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"></td>
			<td>Doctor Sign: <input type="text" value="<?php echo isset($select_result['doctor_sign'])?$select_result['doctor_sign']:""; ?>" name="doctor_sign"></td>
		</tr>
	</table>
	<!-- /.card-body -->
	<div class="card-footer">
		<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
		<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
	</div>
</form>






<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none">  	



	 <table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
	 <tr>
                <td width="100%" colspan="4" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
					
</tr>
<tr>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;">UHID</td>
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
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;">IIC ID</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo $patient_id; ?></td>
</tr>
				<tr>
					<td colspan="4" style="border:1px solid #cdcdcd;" >
        			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
        			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
        			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
        			            ){?>
        			        <p id="last_updated"> Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?> </p>
        			    <?php } ?>
        			</td>
				</tr>
			</table>
	<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
	     <?php 
		@		$female_photographs=$select_result['female_photographs'];
		@		$male_photographs=$select_result['male_photographs'];
				
					?>
	    
		<tr>
			<th  style="border:1px solid #cdcdcd;" ></th>
			<td  style="border:1px solid #cdcdcd;" ><b>FEMALE</b></td>
			<td  style="border:1px solid #cdcdcd;" ><b>MALE</b></td>
		</tr>
		<tr>
			<th style="border:1px solid #cdcdcd;">ART BANK</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_art_bank'])?$select_result['female_art_bank']:""; ?> </td>
			<td style="padding: 0; border:1px solid #cdcdcd;"> <?php echo isset($select_result['male_art_bank'])?$select_result['male_art_bank']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >ID NUMBER</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_id_number'])?$select_result['female_id_number']:""; ?> </td>
			<td style="padding: 0; border:1px solid #cdcdcd;"> <?php echo isset($select_result['male_id_number'])?$select_result['male_id_number']:""; ?> </td>
		</tr>
		<tr>
		
<?php //@ $female_photographs=$select_result['female_photographs'];	$male_photographs=$select_result['male_photographs'];
		?>
			<th  style="border:1px solid #cdcdcd;" >PHOTOGRAPHS</th>
			
			
			<td style="padding: 0;border:1px solid #cdcdcd;"> 
				<?php if(!empty($female_photographs)) {?>
				 <img src="<?php echo $female_photographs;?>" style="width:100px; height:100px;">
					<?php } else {echo " ";}?>
			  
			
			</td>
			
			
			<td style="padding: 0; border:1px solid #cdcdcd;">
			
			<?php if(!empty($male_photographs)) {?>
					
					
					 <img src="<?php echo $male_photographs;?>" style="width:100px; height:100px;">
					
					<?php } else {echo " ";}?>	

			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >NAME</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['female_name'])?$select_result['female_name']:""; ?> </td>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['male_name'])?$select_result['male_name']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >AGE (Years)</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['female_age'])?$select_result['female_age']:""; ?></td>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['male_age'])?$select_result['male_age']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >OCCUPATION</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['female_occupation'])?$select_result['female_occupation']:""; ?></td>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['male_occupation'])?$select_result['male_occupation']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >NATIONALITY</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_nationality'])?$select_result['female_nationality']:""; ?>  </td>
			
			<td style="padding: 0;border:1px solid #cdcdcd;"> <?php echo isset($select_result['male_nationality'])?$select_result['male_nationality']:""; ?> </td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >ETHNICITY</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_religion'])?$select_result['female_religion']:""; ?> </td>
			<td style="padding: 0; border:1px solid #cdcdcd;"> <?php echo isset($select_result['male_religion'])?$select_result['male_religion']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >AADHAR NUMBER</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_aadhar_number'])?$select_result['female_aadhar_number']:""; ?> </td>
			<td style="padding: 0; border:1px solid #cdcdcd;"> <?php echo isset($select_result['male_aadhar_number'])?$select_result['male_aadhar_number']:""; ?> </td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >PRESENT ADDRESS</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['female_present_address'])?$select_result['female_present_address']:""; ?> </td>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['male_present_address'])?$select_result['male_present_address']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >PERMANENT ADDRESS</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['female_permanent_address'])?$select_result['female_permanent_address']:""; ?></td>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['male_permanent_address'])?$select_result['male_permanent_address']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >CONTACT NO</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['female_mobile_no'])?$select_result['female_mobile_no']:""; ?></td>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['male_mobile_no'])?$select_result['male_mobile_no']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >EMAIL ADDRESS</th>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['female_email_address'])?$select_result['female_email_address']:""; ?></td>
			<td style="padding: 0; border:1px solid #cdcdcd;"><?php echo isset($select_result['male_email_address'])?$select_result['male_email_address']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >WT/HT/BMI</th>
			<td  style="border:1px solid #cdcdcd;" ></td>
			<td  style="border:1px solid #cdcdcd;" >NOT REQUIRED</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >H/O previous pregnancies(IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
			<td style="padding: 0; border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Total pregnancies</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['female_total_pregnancies'])?$select_result['female_total_pregnancies']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >No.of live births</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['female_live_births'])?$select_result['female_live_births']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >No.of spontaneous abortions in first trimester</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['female_spontaneous_abortions'])?$select_result['female_spontaneous_abortions']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >No.of termination of pregnancy</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['female_termination_of_pregnancy'])?$select_result['female_termination_of_pregnancy']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >No.of still births</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['female_still_births'])?$select_result['female_still_births']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >No. of ectopic pregnancy</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['female_ectopic_pregnancy'])?$select_result['female_ectopic_pregnancy']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >History of any abnormality in child</td>
						<td  style="border:1px solid #cdcdcd;" >
							  
  <?php if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] == "Yes"){echo 'yes'; }?>
  
  <br> <?php echo isset($select_result['female_history_of_abnormality_text'])?$select_result['female_history_of_abnormality_text']:""; ?>
						
						
						
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" > Others </td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['female_others'])?$select_result['female_others']:""; ?>  </td>
					</tr>
				</table>
			</td>
			<td style="padding: 0; border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >PAST GYNECOLOGICAL HISTORY</th>
			<td  style="border:1px solid #cdcdcd;" >
				<table width="100%">
					<tr>
						<td colspan="2">
							 <?php if(isset($select_result['dysmenorrhea']) && $select_result['dysmenorrhea'] == "Dysmenorrhea"){echo 'Dysmenorrhea'; }?>  
							 <?php if(isset($select_result['dysmenorrhea']) && $select_result['dysmenorrhea'] == "Menorrhagia"){echo 'Menorrhagia'; }?> 
						<?php if(isset($select_result['dysmenorrhea']) && $select_result['dysmenorrhea'] == "H/o D and c"){echo 'H/o D and c'; }?>  
							<?php if(isset($select_result['dysmenorrhea']) && $select_result['dysmenorrhea'] == "Dyspareunia"){echo 'Dyspareunia'; }?>   
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Others</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['others1'])?$select_result['others1']:""; ?></td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" >Not needed</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >MENSTRUATION HISTORY</th>
			<td  style="border:1px solid #cdcdcd;" >
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Age at Menarche</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['age_of_menarche'])?$select_result['age_of_menarche']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Flow</td>
						<td  style="border:1px solid #cdcdcd;" >
							 <?php if(isset($select_result['flow']) && $select_result['flow'] == "heavy"){echo 'heavy'; }?>   
							 <?php if(isset($select_result['flow']) && $select_result['flow'] == "average"){echo 'average'; }?>
							<?php if(isset($select_result['flow']) && $select_result['flow'] == "less"){echo 'less'; }?>   
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Frequency</td>
						<td  style="border:1px solid #cdcdcd;" >
							 <?php if(isset($select_result['frequency']) && $select_result['frequency'] == "regular"){echo 'regular'; }?> 
						<?php if(isset($select_result['frequency']) && $select_result['frequency'] == "irregular"){echo 'irregular'; }?> 
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Days</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['days'])?$select_result['days']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Hirsutism</td>
						<td  style="border:1px solid #cdcdcd;" >
												
				<?php if(isset($select_result['hirsutism']) && $select_result['hirsutism'] == "Yes"){echo 'yes'; }?>		
						
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Galactorrhea</td>
						<td  style="border:1px solid #cdcdcd;" >
							
		<?php if(isset($select_result['galactorrhea']) && $select_result['galactorrhea'] == "Yes"){echo 'yes'; }?>
						</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" ></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >PAST MEDICAL HISTORY</th>
			<td style="padding: 0; border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
							
				<?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "Yes"){echo 'yes'; }?>	
							<br>
							
							<?php echo isset($select_result['heart_attack_text'])?$select_result['heart_attack_text']:""; ?>
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;" >Heart attack</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
							
			<?php if(isset($select_result['pacemaker']) && $select_result['pacemaker'] == "Yes"){echo 'yes'; }?>	
						<br>
	<?php echo isset($select_result['pacemaker_text'])?$select_result['pacemaker_text']:""; ?>
						
						</td>
						<td  style="border:1px solid #cdcdcd;" >Pacemaker</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
					
			<?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "Yes"){echo 'yes'; }?>	
						<br>						
							
				<?php echo isset($select_result['other_heart_disease_text'])?$select_result['other_heart_disease_text']:""; ?>
						
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;" >Other heart disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
<?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "Yes"){echo 'yes'; }?>	
						<br>		
<?php echo isset($select_result['high_blood_pressure_text'])?$select_result['high_blood_pressure_text']:""; ?>
						
</td>
						<td  style="border:1px solid #cdcdcd;" >High blood pressure</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
									
<?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "Yes"){echo 'yes'; }?>	
				<br>
				<?php echo isset($select_result['blood_clots_text'])?$select_result['blood_clots_text']:""; ?>    
						</td>
						
						<td  style="border:1px solid #cdcdcd;" >Blood clots</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
														
							<?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "Yes"){echo 'yes'; }?>	
				<br>
							<?php echo isset($select_result['chest_pain_text'])?$select_result['chest_pain_text']:""; ?>     
						
						</td>
						<td  style="border:1px solid #cdcdcd;" >Chest pain</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
							
							
			<?php if(isset($select_result['stroke']) && $select_result['stroke'] == "Yes"){echo 'yes'; }?>	
				<br>
						<?php echo isset($select_result['stroke_text'])?$select_result['stroke_text']:""; ?>    
						</td>
						<td  style="border:1px solid #cdcdcd;" >Stroke</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
							
<?php if(isset($select_result['asthma']) && $select_result['asthma'] == "Yes"){echo 'yes'; }?>		
						<br>	
						<?php echo isset($select_result['asthma_text'])?$select_result['asthma_text']:""; ?>     
							
						</td>
						<td  style="border:1px solid #cdcdcd;" >Asthma</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >				
		<?php if(isset($select_result['lung_disease']) && $select_result['lung_disease'] == "Yes"){echo 'yes'; }?>	<br>	
		<?php echo isset($select_result['lung_disease_text'])?$select_result['lung_disease_text']:""; ?>
			</td>
						
						<td  style="border:1px solid #cdcdcd;" >Other lung disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
														
<?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "Yes"){echo 'yes'; }?>	<br>					
<?php echo isset($select_result['difficulty_breathing_text'])?$select_result['difficulty_breathing_text']:""; ?>
	</td>
						
						
						
						
						<td  style="border:1px solid #cdcdcd;" >Difficulty breathing</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
							
<?php if(isset($select_result['snoring']) && $select_result['snoring'] == "Yes"){echo 'yes'; }?>	<br>

<?php echo isset($select_result['snoring_text'])?$select_result['snoring_text']:""; ?>
	
						</td>
						<td  style="border:1px solid #cdcdcd;" >Sleep apnea or snoring</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
<?php if(isset($select_result['epilepsy']) && $select_result['epilepsy'] == "Yes"){echo 'yes'; }?>	<br>
<?php echo isset($select_result['epilepsy_text'])?$select_result['epilepsy_text']:""; ?>
						
						</td>
						<td  style="border:1px solid #cdcdcd;" >Epilepsy or seizures</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
														
													
<?php if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] == "Yes"){echo 'yes'; }?>	<br>
	<?php echo isset($select_result['fainting_spells_text'])?$select_result['fainting_spells_text']:""; ?>
						
						
						
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;" >Fainting spells</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
											
<?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "Yes"){echo 'yes'; }?>	<br>		
<?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Diabetes</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
							
<?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "Yes"){echo 'yes'; }?>	<br>

<?php echo isset($select_result['muscle_disorders_text'])?$select_result['muscle_disorders_text']:""; ?>
						
						</td>
						<td  style="border:1px solid #cdcdcd;" >Muscle disorders</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
													
<?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "Yes"){echo 'yes'; }?>	<br>	
							
	<?php echo isset($select_result['kidney_disease_text'])?$select_result['kidney_disease_text']:""; ?>
						
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;" >Kidney disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
<?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "Yes"){echo 'yes'; }?>	<br>
<?php echo isset($select_result['hepatitis_text'])?$select_result['hepatitis_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Hepatitis</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
															
<?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['tuberculosis_text'])?$select_result['tuberculosis_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Tuberculosis</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
<?php if(isset($select_result['hiv']) && $select_result['hiv'] == "Yes"){echo 'yes'; }?>	<br>	

<?php echo isset($select_result['hiv_text'])?$select_result['hiv_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >HIV</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
<?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "Yes"){echo 'yes'; }?>	<br>	

	<?php echo isset($select_result['heart_burn_text'])?$select_result['heart_burn_text']:""; ?>
					

					</td>
						<td  style="border:1px solid #cdcdcd;" >Heart burn/reflux</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
																
<?php if(isset($select_result['cancer']) && $select_result['cancer'] == "Yes"){echo 'yes'; }?>	<br>				
							
	<?php echo isset($select_result['cancer_text'])?$select_result['cancer_text']:""; ?>
						
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;" >Cancer</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
																	
<?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "Yes"){echo 'yes'; }?>	<br>	<?php echo isset($select_result['blood_disorders_text'])?$select_result['blood_disorders_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Blood disorders</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
<?php if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['rheumatic_disease_text'])?$select_result['rheumatic_disease_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Rheumatic disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
																			
<?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['psychiatric_disorder_text'])?$select_result['psychiatric_disorder_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Psychiatric disorder</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
											
														
<?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "Yes"){echo 'yes'; }?>	<br>						
<?php echo isset($select_result['thyroid_disorder_text'])?$select_result['thyroid_disorder_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Thyroid disorder</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
									
												
<?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['urinary_infection_text'])?$select_result['urinary_infection_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Urinary infection</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
<?php if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['sexually_transmitted_text'])?$select_result['sexually_transmitted_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Sexually transmitted disease</td>
					</tr>
					<tr>
<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['impairments'])?$select_result['impairments']:""; ?> </td>
						<td  style="border:1px solid #cdcdcd;" >Other medical condition or impairments</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" ></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >PAST SURGICAL HISTORY</th>
			<td style="padding: 0; border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
																	
<?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "Yes"){echo 'yes'; }?>	<br>		
<?php echo isset($select_result['abdominal_operations_text'])?$select_result['abdominal_operations_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Laparoscopy/pelvic/abdominal operations</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
																		
<?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "Yes"){echo 'yes'; }?>	<br>		
<?php echo isset($select_result['other_operations_text'])?$select_result['other_operations_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Other operations</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" ></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >Allergy history</th>
			<td style="padding: 0; border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
												
<?php if(isset($select_result['medications']) && $select_result['medications'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['medications_text'])?$select_result['medications_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Medications</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
							
<?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "Yes"){echo 'yes'; }?>	<br>			
<?php echo isset($select_result['environmental_factors_text'])?$select_result['environmental_factors_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >environmental factors</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" ></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >SOCIAL & DRUG INTAKE HISTORY</th>
			<td style="padding: 0; border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td width="20%">
												
<?php if(isset($select_result['dentures']) && $select_result['dentures'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['dentures_text'])?$select_result['dentures_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Dentures</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
<?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['loose_teeth_text'])?$select_result['loose_teeth_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Loose teeth</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
								
<?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['hearing_aid_text'])?$select_result['hearing_aid_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Hearing aid</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
<?php if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['caps_on_front_teeth_text'])?$select_result['caps_on_front_teeth_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Caps on front teeth</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
															
<?php if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] == "Yes"){echo 'yes'; }?>	<br>
<?php echo isset($select_result['contact_lenses_text'])?$select_result['contact_lenses_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Contact lenses</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
										
<?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "Yes"){echo 'yes'; }?>	<br>	

					
	<?php echo isset($select_result['body_piercing_text'])?$select_result['body_piercing_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Body piercing</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
								
															
<?php if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] == "Yes"){echo 'yes'; }?>	<br>					
<?php echo isset($select_result['blood_transfusion_text'])?$select_result['blood_transfusion_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >H/o blood transfusion</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
											
<?php if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['traffic_accident_text'])?$select_result['traffic_accident_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >H/o road traffic accident/any injury</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
										
<?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "Yes"){echo 'yes'; }?>	<br>	
<?php echo isset($select_result['smoke_past_text'])?$select_result['smoke_past_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Smoke(past)daily</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
														
<?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "Yes"){echo 'yes'; }?>	<br>
<?php echo isset($select_result['smoke_present_text'])?$select_result['smoke_present_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Smoke(present)daily</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
											
<?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "Yes"){echo 'yes'; }?>	<br>	

<?php echo isset($select_result['drink_past_text'])?$select_result['drink_past_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Drink(past)units per week</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
														
<?php if(isset($select_result['drink_present']) && $select_result['drink_present'] == "Yes"){echo 'yes'; }?>	<br><?php echo isset($select_result['drink_present_text'])?$select_result['drink_present_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Drink(present)units per week</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
<?php if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] == "Yes"){echo 'yes'; }?>	<br>	

	<?php echo isset($select_result['abusive_drugs_text'])?$select_result['abusive_drugs_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Hashish/cocaine /abusive drugs</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
																						
<?php if(isset($select_result['steroid']) && $select_result['steroid'] == "Yes"){echo 'yes'; }?>	<br>				
	<?php echo isset($select_result['steroid_text'])?$select_result[
							'steroid_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Have you ever used cortisone/steroid</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
							
							
																		
<?php if(isset($select_result['medication']) && $select_result['medication'] == "Yes"){echo 'yes'; }?>	<br>		
							
						<?php echo isset($select_result['medication_text'])?$select_result['medication_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Medication</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
												
												
<?php if(isset($select_result['herbal_products']) && $select_result['herbal_products'] == "Yes"){echo 'yes'; }?>	<br>	

<?php echo isset($select_result['herbal_products_text'])?$select_result['herbal_products_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Herbal products</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
																
<?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "Yes"){echo 'yes'; }?>	<br>			
						
<?php echo isset($select_result['eye_drops_text'])?$select_result['eye_drops_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Eye drops</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >
							
			<?php if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] == "Yes"){echo 'yes'; }?>	<br>					
							
		<?php echo isset($select_result['non_prescription_drugs_text'])?$select_result['non_prescription_drugs_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >Non prescription drugs used currently other than medications used for this IVF cycle</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" ></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >FAMILY HISTORY</th>
			<td style="padding: 0; border:1px solid #cdcdcd;">
				<center>Any family member any problem with anesthesia</center>
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;" ></td>
						<td  style="border:1px solid #cdcdcd;" >Maternal</td>
						<td  style="border:1px solid #cdcdcd;" >Paternal</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Diabetes</td>
						<td  style="border:1px solid #cdcdcd;" >
								
<?php if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] == "Yes"){echo 'yes'; }?>
		<br>						
<?php echo isset($select_result['maternal_diabetes_text'])?$select_result['maternal_diabetes_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >
						
<?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "Yes"){echo 'yes'; }?>
		<br>
<?php echo isset($select_result['paternal_diabetes_text'])?$select_result['paternal_diabetes_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Heart/thrombo embolism</td>
						<td  style="border:1px solid #cdcdcd;" >
							
	<?php if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] == "Yes"){echo 'yes'; }?>
		<br>						
<?php echo isset($select_result['maternal_thrombo_embolism_text'])?$select_result['maternal_thrombo_embolism_text']:""; ?>
					

					</td>
						<td  style="border:1px solid #cdcdcd;" >
							
<?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "Yes"){echo 'yes'; }?>
		<br>	
<?php echo isset($select_result['paternal_thrombo_embolism_text'])?$select_result['paternal_thrombo_embolism_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Endocrine/metabolic</td>
						<td  style="border:1px solid #cdcdcd;" >
							
		<?php if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] == "Yes"){echo 'yes'; }?>
		<br>					
						
		<?php echo isset($select_result['maternal_metabolic_text'])?$select_result['maternal_metabolic_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;" >
							
<?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "Yes"){echo 'yes'; }?>
		<br>			
			<?php echo isset($select_result['paternal_metabolic_text'])?$select_result['paternal_metabolic_text']:""; ?>     
						
						
						
						
						
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Urinary tract/renal</td>
						<td  style="border:1px solid #cdcdcd;" >
														
					<?php if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] == "Yes"){echo 'yes'; }?>
		<br>		
			<?php echo isset($select_result['maternal_urinary_tract_text'])?$select_result['maternal_urinary_tract_text']:""; ?>
						
						
						
						
						</td>
						
						
						
						
						<td  style="border:1px solid #cdcdcd;" >
													

		<?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "Yes"){echo 'yes'; }?>
		<br>
	<?php echo isset($select_result['paternal_urinary_tract_text'])?$select_result['paternal_urinary_tract_text']:""; ?>
						
						
						
						
						
						
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Psychiatric/neurological</td>
						<td  style="border:1px solid #cdcdcd;" >
							
					<?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "Yes"){echo 'yes'; }?>
		<br>		
<?php echo isset($select_result['maternal_neurological_text'])?$select_result['maternal_neurological_text']:""; ?>
						</td>
						
						<td  style="border:1px solid #cdcdcd;" >
														
						<?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "Yes"){echo 'yes'; }?>

		<br>		
<?php echo isset($select_result['paternal_neurological_text'])?$select_result['paternal_neurological_text']:""; ?>
						
						
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Other/malignancy</td>
						<td  style="border:1px solid #cdcdcd;" >
						
							
					<?php if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] == "Yes"){echo 'yes'; }?>

		<br>		

						
							<?php echo isset($select_result['maternal_malignancy_text'])?$select_result['maternal_malignancy_text']:""; ?>
						</td>
						
						
						<td  style="border:1px solid #cdcdcd;" >
							
							
					<?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "Yes"){echo 'yes'; }?>

		<br>	<?php echo isset($select_result['paternal_malignancy_text'])?$select_result['paternal_malignancy_text']:""; ?>
						</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" ></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >PREVIOUS HISTORY OF THIRD PARTY REPRODUCTION</th>
			<td style="padding: 0; border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;" >NO. OF TIMES EGG DONATED</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['eggs_donated'])?$select_result['eggs_donated']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >COMPLICATIONS</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['times_did_surrogacy_complicated'])?$select_result['times_did_surrogacy_complicated']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >NO. OF TIMES SURROGACY DONE</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['eggs_donated'])?$select_result['eggs_donated']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >COMPLICATIONS</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['times_did_surrogacy_complicated'])?$select_result['times_did_surrogacy_complicated']:""; ?> </td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" ></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >GENERAL EXAMINATION</th>
			<td style="padding: 0; border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Nutritional assessment</td>
						<td  style="border:1px solid #cdcdcd;" >
					 <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "Obese"){echo 'Obese'; }?>
						
					<?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "average built"){echo 'average built'; }?> 
						
	<?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "thin"){echo 'thin'; }?> 
							
		<?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "cachexic"){echo 'cachexic'; }?> 
						
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Psychological assessment</td>
						<td  style="border:1px solid #cdcdcd;" >
							<?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "Anxious"){echo 'Anxious'; }?>   
						
						 <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "combative"){echo 'combative'; }?>
						
							<input type="radio" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "depressed"){echo 'depressed'; }?>
							
	<?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "normal"){echo 'normal'; }?> 
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Pulse (PER MIN)</td>
<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['pulse'])?$select_result['pulse']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Blood pressure (MM HG)</td>
						<td  style="border:1px solid #cdcdcd;" >
							<table width="100%">
								<tr>
									<td  style="border:1px solid #cdcdcd;" >SYSTOLIC</td>
									<td  style="border:1px solid #cdcdcd;" >
<?php echo isset($select_result['systolic_pulse'])?$select_result['systolic_pulse']:""; ?>
									</td>
								</tr>
								<tr>
									<td  style="border:1px solid #cdcdcd;" >DIASTOLIC</td>
									<td  style="border:1px solid #cdcdcd;" >
	<?php echo isset($select_result['diastolic_pulse'])?$select_result['diastolic_pulse']:""; ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Temperature(DEG F):</td>
						<td  style="border:1px solid #cdcdcd;" ><input type="text" name="cvs" class="form-control" value="<?php echo isset($select_result['cvs'])?$select_result['cvs']:""; ?>"  ></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >CVS</td>
						<td  style="border:1px solid #cdcdcd;" >
							
	<?php if(isset($select_result['cvs']) && $select_result['cvs'] == "Yes"){echo 'yes'; }?> <br>
	
							
			<?php echo isset($select_result['cvs_text'])?$select_result['cvs_text']:""; ?>	
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Chest</td>
						<td  style="border:1px solid #cdcdcd;" >
							
						
			<?php if(isset($select_result['chest']) && $select_result['chest'] == "Yes"){echo 'yes'; }?>
			
			<br>
	
							
			<?php echo isset($select_result['chest_text'])?$select_result['chest_text']:""; ?>
			
						
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Abdomen</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['abdomen_text'])?$select_result['abdomen_text']:""; ?> </td>
						
						
						
						<td  style="border:1px solid #cdcdcd;" >
							
					<?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'yes'; }?>		
						
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Others</td>
<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['others2'])?$select_result['others2']:""; ?> </td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" ></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >LOCAL EXAMINATION</th>
			<td style="padding: 0; border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;" >P/S</td>
						<td  style="border:1px solid #cdcdcd;" >
							
						<?php if(isset($select_result['ps']) && $select_result['ps'] == "Yes"){echo 'yes'; }?>	
							<br>
						<?php echo isset($select_result['ps_text'])?$select_result['ps_text']:""; ?>	
							
							
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >P/V</td>
						<td  style="border:1px solid #cdcdcd;" >
														
				<?php if(isset($select_result['pv']) && $select_result['pv'] == "Yes"){echo 'yes'; }?><br>
					<?php echo isset($select_result['pv_text'])?$select_result['pv_text']:""; ?>
		
						</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" ></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;" >INVESTIGATIONS ADVICED(TO BE PICKED FROM BILLING INVESTIGATION LIST)</th>
			<td style="padding: 0; border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Blood group and Rh typing</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['blood_group'])?$select_result['blood_group']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >FSH</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['fsh'])?$select_result['fsh']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >E2</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['e2'])?$select_result['e2']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >LH</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['lh'])?$select_result['lh']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >AMH</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['amh'])?$select_result['amh']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Prolactin</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['prolactin'])?$select_result['prolactin']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >TSH</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['tsh'])?$select_result['tsh']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >CBC</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['cbc'])?$select_result['cbc']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >PT,APTT,INR</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pt_aptt_inr'])?$select_result['pt_aptt_inr']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Urine routine microscopy</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['urine_routine'])?$select_result['urine_routine']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Blood sugar</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['blood_sugar'])?$select_result['blood_sugar']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Hb Electrophoresis</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['hb_electrophoresis'])?$select_result['hb_electrophoresis']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >HIV</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['hiv1'])?$select_result['hiv1']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Hbsag</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['hbsag'])?$select_result['hbsag']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Hep C</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['hepc'])?$select_result['hepc']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >VDRL</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['vdrl'])?$select_result['vdrl']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >RFT</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['rft'])?$select_result['rft']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >LFT</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['lft'])?$select_result['lft']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >USG pelvis</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['usg_pelvis'])?$select_result['usg_pelvis']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >AFC</td>
						<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['afc'])?$select_result['afc']:""; ?>  </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;" >Others</td>
						<td  style="border:1px solid #cdcdcd;" ><?php echo isset($select_result['others3'])?$select_result['others3']:""; ?> </td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;" ></td>
		</tr>
		
		<tr>
			<th  style="border:1px solid #cdcdcd;" ></th>
			<td  style="border:1px solid #cdcdcd;" > Date & Time: <?php echo isset($select_result['date'])?$select_result['date']:""; ?>  <?php echo isset($select_result['time'])?$select_result['time']:""; ?> </td>
			<td  style="border:1px solid #cdcdcd;" > Doctor Sign: <?php echo isset($select_result['doctor_sign'])?$select_result['doctor_sign']:""; ?> </td>
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