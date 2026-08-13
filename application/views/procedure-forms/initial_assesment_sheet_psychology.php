<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
		if(!empty($_FILES['female_photographs']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['female_photographs']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['female_photographs']['tmp_name'], $destination.$NewImageName);
			$_POST['female_photographs'] = $transaction_img;
		}
        
		if(!empty($_FILES['male_photographs']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['male_photographs']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['male_photographs']['tmp_name'], $destination.$NewImageName);
			$_POST['male_photographs'] = $transaction_img;
		}

        
        $select_query = "SELECT * FROM `initial_assesment_sheet_psychology` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `initial_assesment_sheet_psychology` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE initial_assesment_sheet_psychology SET ";
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
    $select_query = "SELECT * FROM `initial_assesment_sheet_psychology` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);  
	
	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);

	// Dynamic Logo handling
    $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
    if(empty($page_logo)) {
        $page_logo = base_url('assets/center/default-logo.png');
    } 
?>
<form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">
    
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
    
<input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
<input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
<input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
<input type="hidden" value="pending" name="status"> 
  <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">INITIAL ASSEMENT SHEET PSYCHOLOGY</h3></td>
   </tr>
</table>
      <table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">

<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
	   </table>	
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
												<th>PHOTOGRAPHS</th>
												<td style="padding: 0;">
													<input type="file" name="female_photographs" class="form-control">
                      								<a target="_blank" href="<?php echo !empty($select_result['female_photographs'])?$select_result['female_photographs']:"javascript:void(0)"; ?>">Download</a>
												</td>
												<td style="padding: 0;">
													<input type="file" name="male_photographs" class="form-control">
                      								<a target="_blank" href="<?php echo !empty($select_result['male_photographs'])?$select_result['male_photographs']:"javascript:void(0)"; ?>">Download</a>
												</td>
											</tr>
											<tr>
												<th>NAME</th>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['female_name'])?$select_result['female_name']:""; ?>"  name="female_name" class="form-control"></td>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['male_name'])?$select_result['male_name']:""; ?>"  name="male_name" class="form-control"></td>
											</tr>
											<tr>
												<th>AGE (Years)</th>
												<td style="padding: 0;"><input type="number" value="<?php echo isset($select_result['female_age'])?$select_result['female_age']:""; ?>"  name="female_age" class="form-control"></td>
												<td style="padding: 0;"><input type="number" value="<?php echo isset($select_result['male_age'])?$select_result['male_age']:""; ?>"  name="male_age" class="form-control"></td>
											</tr>
											<tr>
												<th>OCCUPATION</th>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['female_occupation'])?$select_result['female_occupation']:""; ?>"  maxlength="15" name="female_occupation" class="form-control"></td>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['male_occupation'])?$select_result['male_occupation']:""; ?>"  maxlength="15" name="male_occupation" class="form-control"></td>
											</tr>
											<tr>
												<th>NATIONALITY</th>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['female_nationality'])?$select_result['female_nationality']:""; ?>"  maxlength="15" name="female_nationality" class="form-control"></td>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['male_nationality'])?$select_result['male_nationality']:""; ?>"  maxlength="15" name="male_nationality" class="form-control"></td>
											</tr>
											<tr>
												<th>ETHNICITY</th>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['female_religion'])?$select_result['female_religion']:""; ?>"  name="female_religion" class="form-control"></td>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['male_religion'])?$select_result['male_religion']:""; ?>"  name="male_religion" class="form-control"></td>
											</tr>
											<tr>
												<th>AADHAR NUMBER</th>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['female_aadhar_number'])?$select_result['female_aadhar_number']:""; ?>"  maxlength="50" name="female_aadhar_number" class="form-control"></td>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['male_aadhar_number'])?$select_result['male_aadhar_number']:""; ?>"  maxlength="50" name="male_aadhar_number" class="form-control"></td>
											</tr>
											<tr>
												<th>PRESENT ADDRESS</th>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['female_present_address'])?$select_result['female_present_address']:""; ?>"  maxlength="50" name="female_present_address" class="form-control"></td>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['male_present_address'])?$select_result['male_present_address']:""; ?>"  maxlength="50" name="male_present_address" class="form-control"></td>
											</tr>
											<tr>
												<th>PERMANENT ADDRESS</th>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['female_permanent_address'])?$select_result['female_permanent_address']:""; ?>"  maxlength="50" name="female_permanent_address" class="form-control"></td>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['male_permanent_address'])?$select_result['male_permanent_address']:""; ?>"  maxlength="50" name="male_permanent_address" class="form-control"></td>
											</tr>
											<tr>
												<th>CONTACT NO</th>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['female_mobile_no'])?$select_result['female_mobile_no']:""; ?>"  maxlength="50" name="female_mobile_no" class="form-control"></td>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['male_mobile_no'])?$select_result['male_mobile_no']:""; ?>"  maxlength="50" name="male_mobile_no" class="form-control"></td>
											</tr>
											<tr>
												<th>EMAIL ADDRESS</th>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['female_email_address'])?$select_result['female_email_address']:""; ?>"  maxlength="50" name="female_email_address" class="form-control"></td>
												<td style="padding: 0;"><input type="text" value="<?php echo isset($select_result['male_email_address'])?$select_result['male_email_address']:""; ?>"  maxlength="50" name="male_email_address" class="form-control"></td>
											</tr>
											<tr>
												<th>WT (kg)/HT(m) /BMI(kg/m2)</th>
												<td></td>
												<td>NOT REQUIRED</td>
											</tr>
											<tr>
												<th>MARITAL STATUS</th>
												<td>
													<input type="radio" checked name="female_marital_status" value="Single" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Single"){echo 'checked="checked"'; }?> > Single
													<input type="radio" checked name="female_marital_status" value="Engaged" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Engaged"){echo 'checked="checked"'; }?> > Engaged
													<input type="radio" checked name="female_marital_status" value="Married" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Married"){echo 'checked="checked"'; }?> > Married
													<input type="radio" checked name="female_marital_status" value="Separated" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Separated"){echo 'checked="checked"'; }?> > Separated
													<input type="radio" checked name="female_marital_status" value="Divorced" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Divorced"){echo 'checked="checked"'; }?> > Divorced
													<input type="radio" checked name="female_marital_status" value="Widowed" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Widowed"){echo 'checked="checked"'; }?> > Widowed
												</td>
												<td>
													<input type="radio" checked name="male_marital_status" value="Single" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Single"){echo 'checked="checked"'; }?> > Single
													<input type="radio" checked name="male_marital_status" value="Engaged" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Engaged"){echo 'checked="checked"'; }?> > Engaged
													<input type="radio" checked name="male_marital_status" value="Married" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Married"){echo 'checked="checked"'; }?> > Married
													<input type="radio" checked name="male_marital_status" value="Separated" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Separated"){echo 'checked="checked"'; }?> > Separated
													<input type="radio" checked name="male_marital_status" value="Divorced" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Divorced"){echo 'checked="checked"'; }?> > Divorced
													<input type="radio" checked name="male_marital_status" value="Widowed" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Widowed"){echo 'checked="checked"'; }?> > Widowed
												</td>
											</tr>
											<tr>
												<th>H/O previous pregnancies(IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>Total pregnancies</td>
															<td><input type="number" value="<?php echo isset($select_result['female_total_pregnancies'])?$select_result['female_total_pregnancies']:""; ?>"  max="20" min="0" name="female_total_pregnancies" class="form-control"></td>
														</tr>
														<tr>
															<td>No.of live births</td>
															<td><input type="number" value="<?php echo isset($select_result['female_live_births'])?$select_result['female_live_births']:""; ?>"  max="20" min="0" name="female_live_births" class="form-control"></td>
														</tr>
														<tr>
															<td>No.of spontaneous abortions</td>
															<td><input type="number" value="<?php echo isset($select_result['female_spontaneous_abortions'])?$select_result['female_spontaneous_abortions']:""; ?>"  max="20" min="0" name="female_spontaneous_abortions" class="form-control"></td>
														</tr>
														<tr>
															<td>No.of termination of pregnancy</td>
															<td><input type="number" value="<?php echo isset($select_result['female_termination_of_pregnancy'])?$select_result['female_termination_of_pregnancy']:""; ?>"  max="20" min="0" name="female_termination_of_pregnancy" class="form-control"></td>
														</tr>
														<tr>
															<td>No.of still births</td>
															<td><input type="number" value="<?php echo isset($select_result['female_still_births'])?$select_result['female_still_births']:""; ?>"  max="20" min="0" name="female_still_births" class="form-control"></td>
														</tr>
														<tr>
															<td>No. of ectopic pregnancy</td>
															<td><input type="number" value="<?php echo isset($select_result['female_ectopic_pregnancy'])?$select_result['female_ectopic_pregnancy']:""; ?>"  max="20" min="0" name="female_ectopic_pregnancy" class="form-control"></td>
														</tr>
														<tr>
															<td>History of any abnormality in child</td>
															<td>
																<input type="radio" checked name="female_history_of_abnormality" value="Yes" <?php if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] == "Yes"){echo 'checked="checked"'; }?> > Yes
																<input type="radio" checked name="female_history_of_abnormality" value="No" <?php if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] != "Yes"){echo 'checked="checked"';}?> > No
																<input type="text" value="<?php echo isset($select_result['female_history_of_abnormality_text'])?$select_result['female_history_of_abnormality_text']:""; ?>"  maxlength="50" name="female_history_of_abnormality_text" class="form-control">
															</td>
														</tr>
														<tr>
															<td>Others</td>
															<td><input type="text" value="<?php echo isset($select_result['female_others'])?$select_result['female_others']:""; ?>"  maxlength="50" name="female_others" class="form-control"></td>
														</tr>
													</table>
												</td>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>Total pregnancies INCLUDING PREVIOUS pregnancies</td>
															<td><input type="number" value="<?php echo isset($select_result['male_total_pregnancies'])?$select_result['male_total_pregnancies']:""; ?>"  max="20" min="0" name="male_total_pregnancies" class="form-control"></td>
														</tr>
														<tr>
															<td colspan="2"><br><br><br><br><br><br><br><br><br><br><br><br><br></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th>PRESENTING COMPLAINT</th>
												<td><input type="text" value="<?php echo isset($select_result['presenting_complaint'])?$select_result['presenting_complaint']:""; ?>"  maxlength="50" name="presenting_complaint" class="form-control"></td>
												<td>NOT NEEDED</td>
											</tr>

											<tr>
												<th>DESCRIPTION OF PRESENTING PROBLEM</th>
												<td colspan="2" style="padding: 0;">
													<table width="100%">
														<tr>
															<td>State in patients own words the nature of main problems:</td>
															<td><input type="text" value="<?php echo isset($select_result['nature_problems'])?$select_result['nature_problems']:""; ?>"  maxlength="20" name="nature_problems"></td>
														</tr>
														<tr>
															<td>On the scale below, please estimate the severity of your problem(s) now.</td>
															<td>
																<input type="radio" checked name="severity" value="Mildly upsetting" <?php if(isset($select_result['severity']) && $select_result['severity'] == "Yes"){echo 'checked="checked"'; }?> > Mildly upsetting<br>
																<input type="radio" checked name="severity" value="Moderately upsetting" <?php if(isset($select_result['severity']) && $select_result['severity'] == "Yes"){echo 'checked="checked"'; }?> > Moderately upsetting<br>
																<input type="radio" checked name="severity" value="Severely upsetting" <?php if(isset($select_result['severity']) && $select_result['severity'] == "Yes"){echo 'checked="checked"'; }?> > Severely upsetting<br>
																<input type="radio" checked name="severity" value="Extremely Severe" <?php if(isset($select_result['severity']) && $select_result['severity'] == "Yes"){echo 'checked="checked"'; }?> > Extremely Severe
															</td>
														</tr>
														<tr>
															<td>When did your problems begin?</td>
															<td><input type="date" value="<?php echo isset($select_result['problem_begin'])?$select_result['problem_begin']:""; ?>"  name="problem_begin"></td>
														</tr>
														<tr>
															<td>Please describe significant events occurring at that time, or since then, which may relate to the development or maintenance of your problems.</td>
															<td><input type="text" value="<?php echo isset($select_result['maintenance'])?$select_result['maintenance']:""; ?>"  maxlength="20" name="maintenance"></td>
														</tr>
														<tr>
															<td>Have you been in therapy before or received any prior professional assistance for your problems?  If so, please give names, professional titles, dates of treatment, and results.</td>
															<td><input type="text" value="<?php echo isset($select_result['therapy_before'])?$select_result['therapy_before']:""; ?>"  maxlength="20" name="therapy_before"></td>
														</tr>
														<tr>
															<td>Have you ever been hospitalized for a psychological problem?</td>
															<td>
																<input type="radio" checked name="psychological_problem" value="Yes" <?php if(isset($select_result['psychological_problem']) && $select_result['psychological_problem'] == "Yes"){echo 'checked="checked"'; }?> > Yes &nbsp;
																<input type="radio" checked name="psychological_problem" value="No" <?php if(isset($select_result['psychological_problem']) && $select_result['psychological_problem'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['psychological_problem']) && $select_result['psychological_problem'] != "Yes"){echo 'checked="checked"';}?> > No &nbsp;
																<input type="text" value="<?php echo isset($select_result['psychological_problem_text'])?$select_result['psychological_problem_text']:""; ?>"  maxlength="20" name="psychological_problem_text">
															</td>
														</tr>
														<tr>
															<td>Have you ever attempted suicide</td>
															<td>
																<input type="radio" checked name="suicide_attempt" value="Yes" <?php if(isset($select_result['suicide_attempt']) && $select_result['suicide_attempt'] == "Yes"){echo 'checked="checked"'; }?> > Yes &nbsp;
																<input type="radio" checked name="suicide_attempt" value="No" <?php if(isset($select_result['suicide_attempt']) && $select_result['suicide_attempt'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['suicide_attempt']) && $select_result['suicide_attempt'] != "Yes"){echo 'checked="checked"';}?> > No &nbsp;
																<input type="text" value="<?php echo isset($select_result['suicide_attempt_text'])?$select_result['suicide_attempt_text']:""; ?>"  maxlength="20" name="suicide_attempt_text">
															</td>
														</tr>
														<tr>
															<td>Do you practice relaxation or meditation regularly?</td>
															<td><input type="text" value="<?php echo isset($select_result['relaxation'])?$select_result['relaxation']:""; ?>"  maxlength="20" name="relaxation"></td>
														</tr>
														<tr>
															<td>Present Feelings</td>
<td>
	<input type="radio" checked name="feelings" value="Angry" <?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Yes"){echo 'checked="checked"'; }?> > Angry &nbsp;
	<input type="radio" checked name="feelings" value="Guilty" <?php if(isset($select_result['Guilty']) && $select_result['Guilty'] == "Yes"){echo 'checked="checked"'; }?> > Guilty &nbsp;
	<input type="radio" checked name="feelings" value="Unhappy" <?php if(isset($select_result['Unhappy']) && $select_result['Unhappy'] == "Yes"){echo 'checked="checked"'; }?> > Unhappy <br>
	<input type="radio" checked name="feelings" value="Annoyed" <?php if(isset($select_result['Annoyed']) && $select_result['Annoyed'] == "Yes"){echo 'checked="checked"'; }?> > Annoyed &nbsp;
	<input type="radio" checked name="feelings" value="Happy" <?php if(isset($select_result['Happy']) && $select_result['Happy'] == "Yes"){echo 'checked="checked"'; }?> > Happy &nbsp;
	<input type="radio" checked name="feelings" value="Bored" <?php if(isset($select_result['Bored']) && $select_result['Bored'] == "Yes"){echo 'checked="checked"'; }?> > Bored <br>
	<input type="radio" checked name="feelings" value="Depressed" <?php if(isset($select_result['Depressed']) && $select_result['Depressed'] == "Yes"){echo 'checked="checked"'; }?> > Depressed &nbsp;
	<input type="radio" checked name="feelings" value="Regretful" <?php if(isset($select_result['Regretful']) && $select_result['Regretful'] == "Yes"){echo 'checked="checked"'; }?> > Regretful &nbsp;
	<input type="radio" checked name="feelings" value="Lonely" <?php if(isset($select_result['Lonely']) && $select_result['Lonely'] == "Yes"){echo 'checked="checked"'; }?> > Lonely <br>
	<input type="radio" checked name="feelings" value="Anxious" <?php if(isset($select_result['Anxious']) && $select_result['Anxious'] == "Yes"){echo 'checked="checked"'; }?> > Anxious &nbsp;
	<input type="radio" checked name="feelings" value="Hopeless" <?php if(isset($select_result['Hopeless']) && $select_result['Hopeless'] == "Yes"){echo 'checked="checked"'; }?> > Hopeless &nbsp;
	<input type="radio" checked name="feelings" value="Contented" <?php if(isset($select_result['Contented']) && $select_result['Contented'] == "Yes"){echo 'checked="checked"'; }?> > Contented<br>
	<input type="radio" checked name="feelings" value="Fearful" <?php if(isset($select_result['Fearful']) && $select_result['Fearful'] == "Yes"){echo 'checked="checked"'; }?> > Fearful &nbsp;
	<input type="radio" checked name="feelings" value="Hopeful" <?php if(isset($select_result['Hopeful']) && $select_result['Hopeful'] == "Yes"){echo 'checked="checked"'; }?> > Hopeful &nbsp;
	<input type="radio" checked name="feelings" value="Excited" <?php if(isset($select_result['Excited']) && $select_result['Excited'] == "Yes"){echo 'checked="checked"'; }?> > Excited <br>
	<input type="radio" checked name="feelings" value="Panicky" <?php if(isset($select_result['Panicky']) && $select_result['Panicky'] == "Yes"){echo 'checked="checked"'; }?> > Panicky &nbsp;
	<input type="radio" checked name="feelings" value="Helpless" <?php if(isset($select_result['Helpless']) && $select_result['Helpless'] == "Yes"){echo 'checked="checked"'; }?> > Helpless &nbsp;
	<input type="radio" checked name="feelings" value="Optimistic" <?php if(isset($select_result['Optimistic']) && $select_result['Optimistic'] == "Yes"){echo 'checked="checked"'; }?> > Optimistic <br>
	<input type="radio" checked name="feelings" value="Energetic" <?php if(isset($select_result['Energetic']) && $select_result['Energetic'] == "Yes"){echo 'checked="checked"'; }?> > Energetic &nbsp;
	<input type="radio" checked name="feelings" value="Relaxed" <?php if(isset($select_result['Relaxed']) && $select_result['Relaxed'] == "Yes"){echo 'checked="checked"'; }?> > Relaxed &nbsp;
	<input type="radio" checked name="feelings" value="Tense" <?php if(isset($select_result['Tense']) && $select_result['Tense'] == "Yes"){echo 'checked="checked"'; }?> > Tense <br>
	<input type="radio" checked name="feelings" value="Envy" <?php if(isset($select_result['Envy']) && $select_result['Envy'] == "Yes"){echo 'checked="checked"'; }?> > Envy &nbsp;
	<input type="radio" checked name="feelings" value="Jealous" <?php if(isset($select_result['Jealous']) && $select_result['Jealous'] == "Yes"){echo 'checked="checked"'; }?> > Jealous
</td>
														</tr>
														<tr>
															<td>List your 5 main fears:</td>
															<td>
																1. <input type="text" value="<?php echo isset($select_result['main_fear_1'])?$select_result['main_fear_1']:""; ?>"  maxlength="20" name="main_fear_1"><br>
																2. <input type="text" value="<?php echo isset($select_result['main_fear_2'])?$select_result['main_fear_2']:""; ?>"  maxlength="20" name="main_fear_2"><br>
																3. <input type="text" value="<?php echo isset($select_result['main_fear_3'])?$select_result['main_fear_3']:""; ?>"  maxlength="20" name="main_fear_3"><br>
																4. <input type="text" value="<?php echo isset($select_result['main_fear_4'])?$select_result['main_fear_4']:""; ?>"  maxlength="20" name="main_fear_4"><br>
																5. <input type="text" value="<?php echo isset($select_result['main_fear_5'])?$select_result['main_fear_5']:""; ?>"  maxlength="20" name="main_fear_5">
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th>PAST MEDICAL HISTORY</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>
																<input type="radio" checked id="text1" name="heart_attack" value="Yes" <?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="heart_attack" value="No" <?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['heart_attack']) && $select_result['heart_attack'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['heart_attack_text'])?$select_result['heart_attack_text']:""; ?>"  maxlength="25" name="heart_attack_text">
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
																<input type="text" value="<?php echo isset($select_result['pacemaker_text'])?$select_result['pacemaker_text']:""; ?>"  maxlength="25" name="pacemaker_text">
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
																<input type="text" value="<?php echo isset($select_result['other_heart_disease_text'])?$select_result['other_heart_disease_text']:""; ?>"  maxlength="25" name="other_heart_disease_text">
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
																<input type="text" value="<?php echo isset($select_result['high_blood_pressure_text'])?$select_result['high_blood_pressure_text']:""; ?>"  maxlength="25" name="high_blood_pressure_text">
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
																<input type="text" value="<?php echo isset($select_result['blood_clots_text'])?$select_result['blood_clots_text']:""; ?>"  maxlength="25" name="blood_clots_text">
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
																<input type="text" value="<?php echo isset($select_result['chest_pain_text'])?$select_result['chest_pain_text']:""; ?>"  maxlength="25" name="chest_pain_text">
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
																<input type="text" value="<?php echo isset($select_result['stroke_text'])?$select_result['stroke_text']:""; ?>"  maxlength="25" name="stroke_text">
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
																<input type="text" value="<?php echo isset($select_result['asthma_text'])?$select_result['asthma_text']:""; ?>"  maxlength="25" name="asthma_text">
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
																<input type="text" value="<?php echo isset($select_result['lung_disease_text'])?$select_result['lung_disease_text']:""; ?>"  maxlength="25" name="lung_disease_text">
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
																<input type="text" value="<?php echo isset($select_result['difficulty_breathing_text'])?$select_result['difficulty_breathing_text']:""; ?>"  maxlength="25" name="difficulty_breathing_text">
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
																<input type="text" value="<?php echo isset($select_result['snoring_text'])?$select_result['snoring_text']:""; ?>"  maxlength="25" name="snoring_text">
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
																<input type="text" value="<?php echo isset($select_result['epilepsy_text'])?$select_result['epilepsy_text']:""; ?>"  maxlength="25" name="epilepsy_text">
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
																<input type="text" value="<?php echo isset($select_result['fainting_spells_text'])?$select_result['fainting_spells_text']:""; ?>"  maxlength="25" name="fainting_spells_text">
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
																<input type="text" value="<?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>"  maxlength="25" name="diabetes_text">
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
																<input type="text" value="<?php echo isset($select_result['muscle_disorders_text'])?$select_result['muscle_disorders_text']:""; ?>"  maxlength="25" name="muscle_disorders_text">
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
																<input type="text" value="<?php echo isset($select_result['kidney_disease_text'])?$select_result['kidney_disease_text']:""; ?>"  maxlength="25" name="kidney_disease_text">
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
																<input type="text" value="<?php echo isset($select_result['hepatitis_text'])?$select_result['hepatitis_text']:""; ?>"  maxlength="25" name="hepatitis_text">
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
																<input type="text" value="<?php echo isset($select_result['tuberculosis_text'])?$select_result['tuberculosis_text']:""; ?>"  maxlength="25" name="tuberculosis_text">
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
																<input type="text" value="<?php echo isset($select_result['hiv_text'])?$select_result['hiv_text']:""; ?>"  maxlength="25" name="hiv_text">
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
																<input type="text" value="<?php echo isset($select_result['heart_burn_text'])?$select_result['heart_burn_text']:""; ?>"  maxlength="25" name="heart_burn_text">
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
																<input type="text" value="<?php echo isset($select_result['cancer_text'])?$select_result['cancer_text']:""; ?>"  maxlength="25" name="cancer_text">
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
																<input type="text" value="<?php echo isset($select_result['blood_disorders_text'])?$select_result['blood_disorders_text']:""; ?>"  maxlength="25" name="blood_disorders_text">
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
																<input type="text" value="<?php echo isset($select_result['rheumatic_disease_text'])?$select_result['rheumatic_disease_text']:""; ?>"  maxlength="25" name="rheumatic_disease_text">
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
																<input type="text" value="<?php echo isset($select_result['psychiatric_disorder_text'])?$select_result['psychiatric_disorder_text']:""; ?>"  maxlength="25" name="psychiatric_disorder_text">
															</td>
															<td>Psychiatric disorder</td>
														</tr>
														<tr>
															<td>
																<input type="radio" checked id="text1" name="thyroid_disorder" value="Yes" <?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="thyroid_disorder" value="No" <?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['chithyroid_disorderldren']) && $select_result['thyroid_disorder'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['thyroid_disorder_text'])?$select_result['thyroid_disorder_text']:""; ?>"  maxlength="25" name="thyroid_disorder_text">
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
																<input type="text" value="<?php echo isset($select_result['urinary_infection_text'])?$select_result['urinary_infection_text']:""; ?>"  maxlength="25" name="urinary_infection_text">
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
																<input type="text" value="<?php echo isset($select_result['sexually_transmitted_text'])?$select_result['sexually_transmitted_text']:""; ?>"  maxlength="25" name="sexually_transmitted_text">
															</td>
															<td>Sexually transmitted disease</td>
														</tr>
														<tr>
															<td><input type="text" value="<?php echo isset($select_result['impairments'])?$select_result['impairments']:""; ?>"  maxlength="25" class="form-control" name="impairments"></td>
															<td>Other medical condition or impairments</td>
														</tr>
													</table>
												</td>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>Heart attack</td>
															<td>
																<input type="radio" checked id="text1" name="male_heart_attack" value="Yes" <?php if(isset($select_result['male_heart_attack']) && $select_result['male_heart_attack'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_heart_attack" value="No" <?php if(isset($select_result['male_heart_attack']) && $select_result['male_heart_attack'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_heart_attack']) && $select_result['male_heart_attack'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_heart_attack_text'])?$select_result['male_heart_attack_text']:""; ?>"  maxlength="25" name="male_heart_attack_text">
															</td>
														</tr>
														<tr>
															<td>Pacemaker</td>
															<td>
																<input type="radio" checked id="text1" name="male_pacemaker" value="Yes" <?php if(isset($select_result['male_pacemaker']) && $select_result['male_pacemaker'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_pacemaker" value="No" <?php if(isset($select_result['male_pacemaker']) && $select_result['male_pacemaker'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_pacemaker']) && $select_result['male_pacemaker'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_pacemaker_text'])?$select_result['male_pacemaker_text']:""; ?>"  maxlength="25" name="male_pacemaker_text">
															</td>
														</tr>
														<tr>
															<td>Other heart disease</td>
															<td>
																<input type="radio" checked id="text1" name="male_other_heart_disease" value="Yes" <?php if(isset($select_result['male_other_heart_disease']) && $select_result['male_other_heart_disease'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_other_heart_disease" value="No" <?php if(isset($select_result['male_other_heart_disease']) && $select_result['male_other_heart_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_other_heart_disease']) && $select_result['male_other_heart_disease'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_other_heart_disease_text'])?$select_result['male_other_heart_disease_text']:""; ?>"  maxlength="25" name="male_other_heart_disease_text">
															</td>
														</tr>
														<tr>
															<td>High blood pressure</td>
															<td>
																<input type="radio" checked id="text1" name="male_high_blood_pressure" value="Yes" <?php if(isset($select_result['male_high_blood_pressure']) && $select_result['male_high_blood_pressure'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_high_blood_pressure" value="No" <?php if(isset($select_result['male_high_blood_pressure']) && $select_result['male_high_blood_pressure'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_high_blood_pressure']) && $select_result['male_high_blood_pressure'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_high_blood_pressure_text'])?$select_result['male_high_blood_pressure_text']:""; ?>"  maxlength="25" name="male_high_blood_pressure_text">
															</td>
														</tr>
														<tr>
															<td>Blood clots</td>
															<td>
																<input type="radio" checked id="text1" name="male_blood_clots" value="Yes" <?php if(isset($select_result['male_blood_clots']) && $select_result['male_blood_clots'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_blood_clots" value="No" <?php if(isset($select_result['male_blood_clots']) && $select_result['male_blood_clots'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_blood_clots']) && $select_result['male_blood_clots'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_blood_clots_text'])?$select_result['male_blood_clots_text']:""; ?>"  maxlength="25" name="male_blood_clots_text">
															</td>
														</tr>
														<tr>
															<td>Chest pain</td>
															<td>
																<input type="radio" checked id="text1" name="male_chest_pain" value="Yes" <?php if(isset($select_result['male_chest_pain']) && $select_result['male_chest_pain'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_chest_pain" value="No" <?php if(isset($select_result['male_chest_pain']) && $select_result['male_chest_pain'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_chest_pain']) && $select_result['male_chest_pain'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_chest_pain_text'])?$select_result['male_chest_pain_text']:""; ?>"  maxlength="25" name="male_chest_pain_text">
															</td>
														</tr>
														<tr>
															<td>Stroke</td>
															<td>
																<input type="radio" checked id="text1" name="male_stroke" value="Yes" <?php if(isset($select_result['male_stroke']) && $select_result['male_stroke'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_stroke" value="No" <?php if(isset($select_result['male_stroke']) && $select_result['male_stroke'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_stroke']) && $select_result['male_stroke'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_stroke_text'])?$select_result['male_stroke_text']:""; ?>"  maxlength="25" name="male_stroke_text">
															</td>
														</tr>
														<tr>
															<td>Asthma</td>
															<td>
																<input type="radio" checked id="text1" name="male_asthma" value="Yes" <?php if(isset($select_result['male_asthma']) && $select_result['male_asthma'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_asthma" value="No" <?php if(isset($select_result['male_asthma']) && $select_result['male_asthma'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_asthma']) && $select_result['male_asthma'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_asthma_text'])?$select_result['male_asthma_text']:""; ?>"  maxlength="25" name="male_asthma_text">
															</td>
														</tr>
														<tr>
															<td>Other lung disease</td>
															<td>
																<input type="radio" checked id="text1" name="male_lung_disease" value="Yes" <?php if(isset($select_result['male_lung_disease']) && $select_result['male_lung_disease'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_lung_disease" value="No" <?php if(isset($select_result['male_lung_disease']) && $select_result['male_lung_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_lung_disease']) && $select_result['male_lung_disease'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_lung_disease_text'])?$select_result['male_lung_disease_text']:""; ?>"  maxlength="25" name="male_lung_disease_text">
															</td>
														</tr>
														<tr>
															<td>Difficulty breathing</td>
															<td>
																<input type="radio" checked id="text1" name="male_difficulty_breathing" value="Yes" <?php if(isset($select_result['male_difficulty_breathing']) && $select_result['male_difficulty_breathing'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_difficulty_breathing" value="No" <?php if(isset($select_result['male_difficulty_breathing']) && $select_result['male_difficulty_breathing'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_difficulty_breathing']) && $select_result['male_difficulty_breathing'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_difficulty_breathing_text'])?$select_result['male_difficulty_breathing_text']:""; ?>"  maxlength="25" name="male_difficulty_breathing_text">
															</td>
														</tr>
														<tr>
															<td>Sleep apnea or snoring</td>
															<td>
																<input type="radio" checked id="text1" name="male_snoring" value="Yes" <?php if(isset($select_result['male_snoring']) && $select_result['male_snoring'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_snoring" value="No" <?php if(isset($select_result['male_snoring']) && $select_result['male_snoring'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_snoring']) && $select_result['male_snoring'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_snoring_text'])?$select_result['male_snoring_text']:""; ?>"  maxlength="25" name="male_snoring_text">
															</td>
														</tr>
														<tr>
															<td>Epilepsy or seizures</td>
															<td>
																<input type="radio" checked id="text1" name="male_epilepsy" value="Yes" <?php if(isset($select_result['male_epilepsy']) && $select_result['male_epilepsy'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_epilepsy" value="No" <?php if(isset($select_result['male_epilepsy']) && $select_result['male_epilepsy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_epilepsy']) && $select_result['male_epilepsy'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_epilepsy_text'])?$select_result['male_epilepsy_text']:""; ?>"  maxlength="25" name="male_epilepsy_text">
															</td>
														</tr>
														<tr>
															<td>Fainting spells</td>
															<td>
																<input type="radio" checked id="text1" name="male_fainting_spells" value="Yes" <?php if(isset($select_result['male_fainting_spells']) && $select_result['male_fainting_spells'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_fainting_spells" value="No" <?php if(isset($select_result['male_fainting_spells']) && $select_result['male_fainting_spells'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_fainting_spells']) && $select_result['male_fainting_spells'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_fainting_spells_text'])?$select_result['male_fainting_spells_text']:""; ?>"  maxlength="25" name="male_fainting_spells_text">
															</td>
														</tr>
														<tr>
															<td>Diabetes</td>
															<td>
																<input type="radio" checked id="text1" name="male_diabetes" value="Yes" <?php if(isset($select_result['male_diabetes']) && $select_result['male_diabetes'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_diabetes" value="No" <?php if(isset($select_result['male_diabetes']) && $select_result['male_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_diabetes']) && $select_result['male_diabetes'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_diabetes_text'])?$select_result['male_diabetes_text']:""; ?>"  maxlength="25" name="male_diabetes_text">
															</td>
														</tr>
														<tr>
															<td>Muscle disorders</td>
															<td>
																<input type="radio" checked id="text1" name="male_muscle_disorders" value="Yes" <?php if(isset($select_result['male_muscle_disorders']) && $select_result['male_muscle_disorders'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_muscle_disorders" value="No" <?php if(isset($select_result['male_muscle_disorders']) && $select_result['male_muscle_disorders'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_muscle_disorders']) && $select_result['male_muscle_disorders'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_muscle_disorders_text'])?$select_result['male_muscle_disorders_text']:""; ?>"  maxlength="25" name="male_muscle_disorders_text">
															</td>
														</tr>
														<tr>
															<td>Kidney disease</td>
															<td>
																<input type="radio" checked id="text1" name="male_kidney_disease" value="Yes" <?php if(isset($select_result['male_kidney_disease']) && $select_result['male_kidney_disease'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_kidney_disease" value="No" <?php if(isset($select_result['male_kidney_disease']) && $select_result['male_kidney_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_kidney_disease']) && $select_result['male_kidney_disease'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_kidney_disease_text'])?$select_result['male_kidney_disease_text']:""; ?>"  maxlength="25" name="male_kidney_disease_text">
															</td>
														</tr>
														<tr>
															<td>Hepatitis</td>
															<td>
																<input type="radio" checked id="text1" name="male_hepatitis" value="Yes" <?php if(isset($select_result['male_hepatitis']) && $select_result['male_hepatitis'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_hepatitis" value="No" <?php if(isset($select_result['male_hepatitis']) && $select_result['male_hepatitis'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_hepatitis']) && $select_result['male_hepatitis'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_hepatitis_text'])?$select_result['male_hepatitis_text']:""; ?>"  maxlength="25" name="male_hepatitis_text">
															</td>
														</tr>
														<tr>
															<td>Tuberculosis</td>
															<td>
																<input type="radio" checked id="text1" name="male_tuberculosis" value="Yes" <?php if(isset($select_result['male_tuberculosis']) && $select_result['male_tuberculosis'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_tuberculosis" value="No" <?php if(isset($select_result['male_tuberculosis']) && $select_result['male_tuberculosis'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_tuberculosis']) && $select_result['male_tuberculosis'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_tuberculosis_text'])?$select_result['male_tuberculosis_text']:""; ?>"  maxlength="25" name="male_tuberculosis_text">
															</td>
														</tr>
														<tr>
															<td>HIV</td>
															<td>
																<input type="radio" checked id="text1" name="male_hiv" value="Yes" <?php if(isset($select_result['male_hiv']) && $select_result['male_hiv'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_hiv" value="No" <?php if(isset($select_result['male_hiv']) && $select_result['male_hiv'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_hiv']) && $select_result['male_hiv'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_hiv_text'])?$select_result['male_hiv_text']:""; ?>"  maxlength="25" name="male_hiv_text">
															</td>
														</tr>
														<tr>
															<td>Heart burn/reflux</td>
															<td>
																<input type="radio" checked name="male_heart_burn" value="Yes" <?php if(isset($select_result['male_heart_burn']) && $select_result['male_heart_burn'] == "Yes"){echo 'checked="checked"'; }?> >
																<label for="type2">Yes</label>
																<input type="radio" checked name="male_heart_burn" value="No" <?php if(isset($select_result['male_heart_burn']) && $select_result['male_heart_burn'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_heart_burn']) && $select_result['male_heart_burn'] != "Yes"){echo 'checked="checked"';}?> >
																<label for="type2">No</label>
																<input type="text" value="<?php echo isset($select_result['male_heart_burn_text'])?$select_result['male_heart_burn_text']:""; ?>"  maxlength="25" name="male_heart_burn_text">
															</td>
														</tr>
														<tr>
															<td>Cancer </td>
															<td>
																<input type="radio" checked name="male_cancer" value="Yes" <?php if(isset($select_result['male_cancer']) && $select_result['male_cancer'] == "Yes"){echo 'checked="checked"'; }?> >
																<label for="type2">Yes</label>
																<input type="radio" checked name="male_cancer" value="No" <?php if(isset($select_result['male_cancer']) && $select_result['male_cancer'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_cancer']) && $select_result['male_cancer'] != "Yes"){echo 'checked="checked"';}?> >
																<label for="type2">No</label>
																<input type="text" value="<?php echo isset($select_result['male_cancer_text'])?$select_result['male_cancer_text']:""; ?>"  maxlength="25" name="male_cancer_text">
															</td>
														</tr>
														<tr>
															<td>Blood disorders</td>
															<td>
																<input type="radio" checked name="male_blood_disorders" value="Yes" <?php if(isset($select_result['male_blood_disorders']) && $select_result['male_blood_disorders'] == "Yes"){echo 'checked="checked"'; }?> >
																<label for="type2">Yes</label>
																<input type="radio" checked name="male_blood_disorders" value="No" <?php if(isset($select_result['male_blood_disorders']) && $select_result['male_blood_disorders'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_blood_disorders']) && $select_result['male_blood_disorders'] != "Yes"){echo 'checked="checked"';}?> >
																<label for="type2">No</label>
																<input type="text" value="<?php echo isset($select_result['male_blood_disorders_text'])?$select_result['male_blood_disorders_text']:""; ?>"  maxlength="25" name="male_blood_disorders_text">
															</td>
														</tr>
														<tr>
															<td>Rheumatic disease</td>
															<td>
																<input type="radio" checked name="male_rheumatic_disease" value="Yes" <?php if(isset($select_result['male_rheumatic_disease']) && $select_result['male_rheumatic_disease'] == "Yes"){echo 'checked="checked"'; }?> >
																<label for="type2">Yes</label>
																<input type="radio" checked name="male_rheumatic_disease" value="No" <?php if(isset($select_result['male_rheumatic_disease']) && $select_result['male_rheumatic_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_rheumatic_disease']) && $select_result['male_rheumatic_disease'] != "Yes"){echo 'checked="checked"';}?> >
																<label for="type2">No</label>
																<input type="text" value="<?php echo isset($select_result['male_rheumatic_disease_text'])?$select_result['male_rheumatic_disease_text']:""; ?>"  maxlength="25" name="male_rheumatic_disease_text">
															</td>
														</tr>
														<tr>
															<td>Psychiatric disorder</td>
															<td>
																<input type="radio" checked name="male_psychiatric_disorder" value="Yes" <?php if(isset($select_result['male_psychiatric_disorder']) && $select_result['male_psychiatric_disorder'] == "Yes"){echo 'checked="checked"'; }?> >
																<label for="type2">Yes</label>
																<input type="radio" checked name="male_psychiatric_disorder" value="No" <?php if(isset($select_result['male_psychiatric_disorder']) && $select_result['male_psychiatric_disorder'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_psychiatric_disorder']) && $select_result['male_psychiatric_disorder'] != "Yes"){echo 'checked="checked"';}?> >
																<label for="type2">No</label>
																<input type="text" value="<?php echo isset($select_result['male_psychiatric_disorder_text'])?$select_result['male_psychiatric_disorder_text']:""; ?>"  maxlength="25" name="male_psychiatric_disorder_text">
															</td>
														</tr>
														<tr>
															<td>Thyroid disorder</td>
															<td>
																<input type="radio" checked name="male_thyroid_disorder" value="Yes" <?php if(isset($select_result['male_thyroid_disorder']) && $select_result['male_thyroid_disorder'] == "Yes"){echo 'checked="checked"'; }?> >
																<label for="type2">Yes</label>
																<input type="radio" checked name="male_thyroid_disorder" value="No" <?php if(isset($select_result['male_thyroid_disorder']) && $select_result['male_thyroid_disorder'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_thyroid_disorder']) && $select_result['male_thyroid_disorder'] != "Yes"){echo 'checked="checked"';}?> >
																<label for="type2">No</label>
																<input type="text" value="<?php echo isset($select_result['male_thyroid_disorder_text'])?$select_result['male_thyroid_disorder_text']:""; ?>"  maxlength="25" name="male_thyroid_disorder_text">
															</td>
														</tr>
														<tr>
															<td>Urinary infection</td>
															<td>
																<input type="radio" checked name="male_urinary_infection" value="Yes" <?php if(isset($select_result['male_urinary_infection']) && $select_result['male_urinary_infection'] == "Yes"){echo 'checked="checked"'; }?> >
																<label for="type2">Yes</label>
																<input type="radio" checked name="male_urinary_infection" value="No" <?php if(isset($select_result['male_urinary_infection']) && $select_result['male_urinary_infection'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_urinary_infection']) && $select_result['male_urinary_infection'] != "Yes"){echo 'checked="checked"';}?> >
																<label for="type2">No</label>
																<input type="text" value="<?php echo isset($select_result['male_urinary_infection_text'])?$select_result['male_urinary_infection_text']:""; ?>"  maxlength="25" name="male_urinary_infection_text">
															</td>
														</tr>
														<tr>
															<td>Sexually transmitted disease</td>
															<td>
																<input type="radio" checked name="male_sexually_transmitted" value="Yes" <?php if(isset($select_result['male_sexually_transmitted']) && $select_result['male_sexually_transmitted'] == "Yes"){echo 'checked="checked"'; }?> >
																<label for="type2">Yes</label>
																<input type="radio" checked name="male_sexually_transmitted" value="No" <?php if(isset($select_result['male_sexually_transmitted']) && $select_result['male_sexually_transmitted'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_sexually_transmitted']) && $select_result['male_sexually_transmitted'] != "Yes"){echo 'checked="checked"';}?> >
																<label for="type2">No</label>
																<input type="text" value="<?php echo isset($select_result['male_sexually_transmitted_text'])?$select_result['male_sexually_transmitted_text']:""; ?>"  maxlength="25" name="male_sexually_transmitted_text">
															</td>
														</tr>
														<tr>
															<td>Other medical condition or impairments</td>
															<td>
																<input type="text" value="<?php echo isset($select_result['male_impairments'])?$select_result['male_impairments']:""; ?>"  maxlength="50" name="male_impairments" class="form-control">
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th>PAST ANESTHESIA AND SURGICAL PROCEDURES</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>Laparoscopy/pelvic/abdominal operations</td>
															<td>
																<input type="radio" checked id="text1" name="abdominal_operations" value="Yes" <?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="abdominal_operations" value="No" <?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['abdominal_operations_text'])?$select_result['abdominal_operations_text']:""; ?>"  maxlength="25" name="abdominal_operations_text">
															</td>
														</tr>
														<tr>
															<td>Other operations</td>
															<td>
																<input type="radio" checked id="text1" name="other_operations" value="Yes" <?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="other_operations" value="No" <?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['other_operations']) && $select_result['other_operations'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['other_operations_text'])?$select_result['other_operations_text']:""; ?>"  maxlength="25" name="other_operations_text">
															</td>
														</tr>
													</table>
												</td>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>Laparoscopy/pelvic/abdominal operations</td>
															<td>
																<input type="radio" checked id="text1" name="male_abdominal_operations" value="Yes" <?php if(isset($select_result['male_abdominal_operations']) && $select_result['male_abdominal_operations'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_abdominal_operations" value="No" <?php if(isset($select_result['male_abdominal_operations']) && $select_result['male_abdominal_operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_abdominal_operations']) && $select_result['male_abdominal_operations'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_abdominal_operations_text'])?$select_result['male_abdominal_operations_text']:""; ?>"  maxlength="25" name="male_abdominal_operations_text">
															</td>
														</tr>
														<tr>
															<td>Other operations</td>
															<td>
																<input type="radio" checked id="text1" name="male_other_operations" value="Yes" <?php if(isset($select_result['male_other_operations']) && $select_result['male_other_operations'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_other_operations" value="No" <?php if(isset($select_result['male_other_operations']) && $select_result['male_other_operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_other_operations']) && $select_result['male_other_operations'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_other_operations_text'])?$select_result['male_other_operations_text']:""; ?>"  maxlength="25" name="male_other_operations_text">
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th>ALLERGY HISTORY</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>Medications</td>
															<td>
																<input type="radio" checked id="text1" name="medications" value="Yes" <?php if(isset($select_result['medications']) && $select_result['medications'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="medications" value="No" <?php if(isset($select_result['medications']) && $select_result['medications'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['medications']) && $select_result['medications'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['medications_text'])?$select_result['medications_text']:""; ?>"  maxlength="25" name="medications_text">
															</td>
														</tr>
														<tr>
															<td>environmental factors</td>
															<td>
																<input type="radio" checked id="text1" name="environmental_factors" value="Yes" <?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="environmental_factors" value="No" <?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['environmental_factors_text'])?$select_result['environmental_factors_text']:""; ?>"  maxlength="25" name="environmental_factors_text">
															</td>
														</tr>
													</table>
												</td>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>Medications</td>
															<td>
																<input type="radio" checked id="text1" name="male_medications" value="Yes" <?php if(isset($select_result['male_medications']) && $select_result['male_medications'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_medications" value="No" <?php if(isset($select_result['male_medications']) && $select_result['male_medications'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_medications']) && $select_result['male_medications'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_medications_text'])?$select_result['male_medications_text']:""; ?>"  maxlength="25" name="male_medications_text">
															</td>
														</tr>
														<tr>
															<td>environmental factors</td>
															<td>
																<input type="radio" checked id="text1" name="male_environmental_factors" value="Yes" <?php if(isset($select_result['male_environmental_factors']) && $select_result['male_environmental_factors'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_environmental_factors" value="No" <?php if(isset($select_result['male_environmental_factors']) && $select_result['male_environmental_factors'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_environmental_factors']) && $select_result['male_environmental_factors'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_environmental_factors_text'])?$select_result['male_environmental_factors_text']:""; ?>"  maxlength="25" name="male_environmental_factors_text">
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th>SOCIAL & DRUG INTAKE HISTORY</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td width="20%">
																<input type="radio" checked id="text1" name="dentures" value="Yes" <?php if(isset($select_result['dentures']) && $select_result['dentures'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="dentures" value="No" <?php if(isset($select_result['dentures']) && $select_result['dentures'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['dentures']) && $select_result['dentures'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['dentures_text'])?$select_result['dentures_text']:""; ?>"  maxlength="25" name="dentures_text">
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
																<input type="text" value="<?php echo isset($select_result['loose_teeth_text'])?$select_result['loose_teeth_text']:""; ?>"  maxlength="25" name="loose_teeth_text">
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
																<input type="text" value="<?php echo isset($select_result['hearing_aid_text'])?$select_result['hearing_aid_text']:""; ?>"  maxlength="25" name="hearing_aid_text">
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
																<input type="text" value="<?php echo isset($select_result['caps_on_front_teeth_text'])?$select_result['caps_on_front_teeth_text']:""; ?>"  maxlength="25" name="caps_on_front_teeth_text">
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
																<input type="text" value="<?php echo isset($select_result['contact_lenses_text'])?$select_result['contact_lenses_text']:""; ?>"  maxlength="25" name="contact_lenses_text">
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
																<input type="text" value="<?php echo isset($select_result['body_piercing_text'])?$select_result['body_piercing_text']:""; ?>"  maxlength="25" name="body_piercing_text">
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
																<input type="text" value="<?php echo isset($select_result['blood_transfusion_text'])?$select_result['blood_transfusion_text']:""; ?>"  maxlength="25" name="blood_transfusion_text">
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
																<input type="text" value="<?php echo isset($select_result['traffic_accident_text'])?$select_result['traffic_accident_text']:""; ?>"  maxlength="25" name="traffic_accident_text">
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
																<input type="text" value="<?php echo isset($select_result['smoke_past_text'])?$select_result['smoke_past_text']:""; ?>"  maxlength="25" name="smoke_past_text">
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
																<input type="text" value="<?php echo isset($select_result['smoke_present_text'])?$select_result['smoke_present_text']:""; ?>"  maxlength="25" name="smoke_present_text">
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
																<input type="text" value="<?php echo isset($select_result['drink_past_text'])?$select_result['drink_past_text']:""; ?>"  maxlength="25" name="drink_past_text">
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
																<input type="text" value="<?php echo isset($select_result['drink_present_text'])?$select_result['drink_present_text']:""; ?>"  maxlength="25" name="drink_present_text">
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
																<input type="text" value="<?php echo isset($select_result['abusive_drugs_text'])?$select_result['abusive_drugs_text']:""; ?>"  maxlength="25" name="abusive_drugs_text">
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
																<input type="text" value="<?php echo isset($select_result['steroid_text'])?$select_result['steroid_text']:""; ?>"  maxlength="25" name="steroid_text">
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
																<input type="text" value="<?php echo isset($select_result['medication_text'])?$select_result['medication_text']:""; ?>"  maxlength="25" name="medication_text">
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
																<input type="text" value="<?php echo isset($select_result['herbal_products_text'])?$select_result['herbal_products_text']:""; ?>"  maxlength="25" name="herbal_products_text">
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
																<input type="text" value="<?php echo isset($select_result['eye_drops_text'])?$select_result['eye_drops_text']:""; ?>"  maxlength="25" name="eye_drops_text">
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
																<input type="text" value="<?php echo isset($select_result['non_prescription_drugs_text'])?$select_result['non_prescription_drugs_text']:""; ?>"  maxlength="25" name="non_prescription_drugs_text">
															</td>
															<td>Non prescription drugs used currently other than medications used for this IVF cycle</td>
														</tr>
													</table>
												</td>
												<td style="padding: 0;">
													<table>
														<tr>
															<td>Dentures</td>
															<td width="30%">
																<input type="radio" checked id="text1" name="male_dentures" value="Yes" <?php if(isset($select_result['male_dentures']) && $select_result['male_dentures'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_dentures" value="No" <?php if(isset($select_result['male_dentures']) && $select_result['male_dentures'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_dentures']) && $select_result['male_dentures'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_dentures_text'])?$select_result['male_dentures_text']:""; ?>"  maxlength="25" name="male_dentures_text">
															</td>
														</tr>
														<tr>
															<td>Loose teeth</td>
															<td>
																<input type="radio" checked id="text1" name="male_loose_teeth" value="Yes" <?php if(isset($select_result['male_loose_teeth']) && $select_result['male_loose_teeth'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_loose_teeth" value="No" <?php if(isset($select_result['male_loose_teeth']) && $select_result['male_loose_teeth'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_loose_teeth']) && $select_result['male_loose_teeth'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_loose_teeth_text'])?$select_result['male_loose_teeth_text']:""; ?>"  maxlength="25" name="male_loose_teeth_text">
															</td>
														</tr>
														<tr>
															<td>Hearing aid</td>
															<td>
																<input type="radio" checked id="text1" name="male_hearing_aid" value="Yes" <?php if(isset($select_result['male_hearing_aid']) && $select_result['male_hearing_aid'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_hearing_aid" value="No" <?php if(isset($select_result['male_hearing_aid']) && $select_result['male_hearing_aid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_hearing_aid']) && $select_result['male_hearing_aid'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_hearing_aid_text'])?$select_result['male_hearing_aid_text']:""; ?>"  maxlength="25" name="male_hearing_aid_text">
															</td>
														</tr>
														<tr>
															<td>Caps on front teeth</td>
															<td>
																<input type="radio" checked id="text1" name="male_caps_on_front_teeth" value="Yes" <?php if(isset($select_result['male_caps_on_front_teeth']) && $select_result['male_caps_on_front_teeth'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_caps_on_front_teeth" value="No" <?php if(isset($select_result['male_caps_on_front_teeth']) && $select_result['male_caps_on_front_teeth'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_caps_on_front_teeth']) && $select_result['male_caps_on_front_teeth'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_caps_on_front_teeth_text'])?$select_result['male_caps_on_front_teeth_text']:""; ?>"  maxlength="25" name="male_caps_on_front_teeth_text">
															</td>
														</tr>
														<tr>
															<td>Contact lenses</td>
															<td>
																<input type="radio" checked id="text1" name="male_contact_lenses" value="Yes" <?php if(isset($select_result['male_contact_lenses']) && $select_result['male_contact_lenses'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_contact_lenses" value="No" <?php if(isset($select_result['male_contact_lenses']) && $select_result['male_contact_lenses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_contact_lenses']) && $select_result['male_contact_lenses'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_contact_lenses_text'])?$select_result['male_contact_lenses_text']:""; ?>"  maxlength="25" name="male_contact_lenses_text">
															</td>
														</tr>
														<tr>
															<td>Body piercing</td>
															<td>
																<input type="radio" checked id="text1" name="male_body_piercing" value="Yes" <?php if(isset($select_result['male_body_piercing']) && $select_result['male_body_piercing'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_body_piercing" value="No" <?php if(isset($select_result['male_body_piercing']) && $select_result['male_body_piercing'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_body_piercing']) && $select_result['male_body_piercing'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_body_piercing_text'])?$select_result['male_body_piercing_text']:""; ?>"  maxlength="25" name="male_body_piercing_text">
															</td>
														</tr>
														<tr>
															<td>H/o blood transfusion</td>
															<td>
																<input type="radio" checked id="text1" name="male_blood_transfusion" value="Yes" <?php if(isset($select_result['male_blood_transfusion']) && $select_result['male_blood_transfusion'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_blood_transfusion" value="No" <?php if(isset($select_result['male_blood_transfusion']) && $select_result['male_blood_transfusion'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_blood_transfusion']) && $select_result['male_blood_transfusion'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_blood_transfusion_text'])?$select_result['male_blood_transfusion_text']:""; ?>"  maxlength="25" name="male_blood_transfusion_text">
															</td>
														</tr>
														<tr>
															<td>H/o road traffic accident/any injury</td>
															<td>
																<input type="radio" checked id="text1" name="male_traffic_accident" value="Yes" <?php if(isset($select_result['male_traffic_accident']) && $select_result['male_traffic_accident'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_traffic_accident" value="No" <?php if(isset($select_result['male_traffic_accident']) && $select_result['male_traffic_accident'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_traffic_accident']) && $select_result['male_traffic_accident'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_traffic_accident_text'])?$select_result['male_traffic_accident_text']:""; ?>"  maxlength="25" name="male_traffic_accident_text">
															</td>
														</tr>
														<tr>
															<td>Smoke(past)daily</td>
															<td>
																<input type="radio" checked id="text1" name="male_smoke_past" value="Yes" <?php if(isset($select_result['male_smoke_past']) && $select_result['male_smoke_past'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_smoke_past" value="No" <?php if(isset($select_result['male_smoke_past']) && $select_result['male_smoke_past'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_smoke_past']) && $select_result['male_smoke_past'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_smoke_past_text'])?$select_result['male_smoke_past_text']:""; ?>"  maxlength="25" name="male_smoke_past_text">
															</td>
														</tr>
														<tr>
															<td>Smoke(present)daily</td>
															<td>
																<input type="radio" checked id="text1" name="male_smoke_present" value="Yes" <?php if(isset($select_result['male_smoke_present']) && $select_result['male_smoke_present'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_smoke_present" value="No" <?php if(isset($select_result['male_smoke_present']) && $select_result['male_smoke_present'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_smoke_present']) && $select_result['male_smoke_present'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_smoke_present_text'])?$select_result['male_smoke_present_text']:""; ?>"  maxlength="25" name="male_smoke_present_text">
															</td>
														</tr>
														<tr>
															<td>Drink(past)units per week</td>
															<td>
																<input type="radio" checked id="text1" name="male_drink_past" value="Yes" <?php if(isset($select_result['male_drink_past']) && $select_result['male_drink_past'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_drink_past" value="No" <?php if(isset($select_result['male_drink_past']) && $select_result['male_drink_past'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_drink_past']) && $select_result['male_drink_past'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_drink_past_text'])?$select_result['male_drink_past_text']:""; ?>"  maxlength="25" name="male_drink_past_text">
															</td>
														</tr>
														<tr>
															<td>Drink(present)units per week</td>
															<td>
																<input type="radio" checked id="text1" name="male_drink_present" value="Yes" <?php if(isset($select_result['male_drink_present']) && $select_result['male_drink_present'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_drink_present" value="No" <?php if(isset($select_result['male_drink_present']) && $select_result['male_drink_present'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_drink_present']) && $select_result['male_drink_present'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_drink_present_text'])?$select_result['male_drink_present_text']:""; ?>"  maxlength="25" name="male_drink_present_text">
															</td>
														</tr>
														<tr>
															<td>Hashish/cocaine /abusive drugs</td>
															<td>
																<input type="radio" checked id="text1" name="male_abusive_drugs" value="Yes" <?php if(isset($select_result['male_abusive_drugs']) && $select_result['male_abusive_drugs'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_abusive_drugs" value="No" <?php if(isset($select_result['male_abusive_drugs']) && $select_result['male_abusive_drugs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_abusive_drugs']) && $select_result['male_abusive_drugs'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_abusive_drugs_text'])?$select_result['male_abusive_drugs_text']:""; ?>"  maxlength="25" name="male_abusive_drugs_text">
															</td>
														</tr>
														<tr>
															<td>Have you ever used cortisone/steroid</td>
															<td>
																<input type="radio" checked id="text1" name="male_steroid" value="Yes" <?php if(isset($select_result['male_steroid']) && $select_result['male_steroid'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_steroid" value="No" <?php if(isset($select_result['male_steroid']) && $select_result['male_steroid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_steroid']) && $select_result['male_steroid'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_steroid_text'])?$select_result['male_steroid_text']:""; ?>"  maxlength="25" name="male_steroid_text">
															</td>
														</tr>
														<tr>
															<td>Medication</td>
															<td>
																<input type="radio" checked id="text1" name="male_medication" value="Yes" <?php if(isset($select_result['male_medication']) && $select_result['male_medication'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_medication" value="No" <?php if(isset($select_result['male_medication']) && $select_result['male_medication'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_medication']) && $select_result['male_medication'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_medication_text'])?$select_result['male_medication_text']:""; ?>"  maxlength="25" name="male_medication_text">
															</td>
														</tr>
														<tr>
															<td>Herbal products</td>
															<td>
																<input type="radio" checked id="text1" name="male_herbal_products" value="Yes" <?php if(isset($select_result['male_herbal_products']) && $select_result['male_herbal_products'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_herbal_products" value="No" <?php if(isset($select_result['male_herbal_products']) && $select_result['male_herbal_products'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_herbal_products']) && $select_result['male_herbal_products'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_herbal_products_text'])?$select_result['male_herbal_products_text']:""; ?>"  maxlength="25" name="male_herbal_products_text">
															</td>
														</tr>
														<tr>
															<td>Eye drops</td>
															<td>
																<input type="radio" checked id="text1" name="male_eye_drops" value="Yes" <?php if(isset($select_result['male_eye_drops']) && $select_result['male_eye_drops'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_eye_drops" value="No" <?php if(isset($select_result['male_eye_drops']) && $select_result['male_eye_drops'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_eye_drops']) && $select_result['male_eye_drops'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_eye_drops_text'])?$select_result['male_eye_drops_text']:""; ?>"  maxlength="25" name="male_eye_drops_text">
															</td>
														</tr>
														<tr>
															<td>Non prescription drugs used currently other than medications used for this IVF cycle</td>
															<td>
																<input type="radio" checked id="text1" name="male_non_prescription_drugs" value="Yes" <?php if(isset($select_result['male_non_prescription_drugs']) && $select_result['male_non_prescription_drugs'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked id="text1" name="male_non_prescription_drugs" value="No" <?php if(isset($select_result['male_non_prescription_drugs']) && $select_result['male_non_prescription_drugs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_non_prescription_drugs']) && $select_result['male_non_prescription_drugs'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_non_prescription_drugs_text'])?$select_result['male_non_prescription_drugs_text']:""; ?>"  maxlength="25" name="male_non_prescription_drugs_text">
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th>FAMILY HISTORY</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>Any family member any problem <br> with anesthesia</td>
															<td colspan="2">
																<input type="radio" checked name="member_with_anesthesia" value="Yes" <?php if(isset($select_result['member_with_anesthesia']) && $select_result['member_with_anesthesia'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="member_with_anesthesia" value="No" <?php if(isset($select_result['member_with_anesthesia']) && $select_result['member_with_anesthesia'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['member_with_anesthesia']) && $select_result['member_with_anesthesia'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['member_with_anesthesia_text'])?$select_result['member_with_anesthesia_text']:""; ?>"  maxlength="25" name="member_with_anesthesia_text">
															</td>
														</tr>
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
																<input type="text" value="<?php echo isset($select_result['maternal_diabetes_text'])?$select_result['maternal_diabetes_text']:""; ?>"  maxlength="25" name="maternal_diabetes_text">
															</td>
															<td>
																<input type="radio" checked name="paternal_diabetes" value="Yes" <?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="paternal_diabetes" value="No" <?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['paternal_diabetes_text'])?$select_result['paternal_diabetes_text']:""; ?>"  maxlength="25" name="paternal_diabetes_text">
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
																<input type="text" value="<?php echo isset($select_result['maternal_thrombo_embolism_text'])?$select_result['maternal_thrombo_embolism_text']:""; ?>"  maxlength="25" name="maternal_thrombo_embolism_text">
															</td>
															<td>
																<input type="radio" checked name="paternal_thrombo_embolism" value="Yes" <?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="paternal_thrombo_embolism" value="No" <?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['paternal_thrombo_embolism_text'])?$select_result['paternal_thrombo_embolism_text']:""; ?>"  maxlength="25" name="paternal_thrombo_embolism_text">
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
																<input type="text" value="<?php echo isset($select_result['maternal_metabolic_text'])?$select_result['maternal_metabolic_text']:""; ?>"  maxlength="25" name="maternal_metabolic_text">
															</td>
															<td>
																<input type="radio" checked name="paternal_metabolic" value="Yes" <?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="paternal_metabolic" value="No" <?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['paternal_metabolic_text'])?$select_result['paternal_metabolic_text']:""; ?>"  maxlength="25" name="paternal_metabolic_text">
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
																<input type="text" value="<?php echo isset($select_result['maternal_urinary_tract_text'])?$select_result['maternal_urinary_tract_text']:""; ?>"  maxlength="25" name="maternal_urinary_tract_text">
															</td>
															<td>
																<input type="radio" checked name="paternal_urinary_tract" value="Yes" <?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="paternal_urinary_tract" value="No" <?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['paternal_urinary_tract_text'])?$select_result['paternal_urinary_tract_text']:""; ?>"  maxlength="25" name="paternal_urinary_tract_text">
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
																<input type="text" value="<?php echo isset($select_result['maternal_neurological_text'])?$select_result['maternal_neurological_text']:""; ?>"  maxlength="25" name="maternal_neurological_text">
															</td>
															<td>
																<input type="radio" checked name="paternal_neurological" value="Yes" <?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="paternal_neurological" value="No" <?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['paternal_neurological_text'])?$select_result['paternal_neurological_text']:""; ?>"  maxlength="25" name="paternal_neurological_text">
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
																<input type="text" value="<?php echo isset($select_result['maternal_malignancy_text'])?$select_result['maternal_malignancy_text']:""; ?>"  maxlength="25" name="maternal_malignancy_text">
															</td>
															<td>
																<input type="radio" checked name="paternal_malignancy" value="Yes" <?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="paternal_malignancy" value="No" <?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['paternal_malignancy_text'])?$select_result['paternal_malignancy_text']:""; ?>"  maxlength="25" name="paternal_malignancy_text">
															</td>
														</tr>
													</table>
												</td>
												<td>
													<table width="100%">
														<tr>
															<td>Any family member any problem <br> with anesthesia</td>
															<td colspan="2">
																<input type="radio" checked name="male_member_with_anesthesia" value="Yes" <?php if(isset($select_result['male_member_with_anesthesia']) && $select_result['male_member_with_anesthesia'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_member_with_anesthesia" value="No" <?php if(isset($select_result['male_member_with_anesthesia']) && $select_result['male_member_with_anesthesia'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_member_with_anesthesia']) && $select_result['male_member_with_anesthesia'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_member_with_anesthesia_text'])?$select_result['male_member_with_anesthesia_text']:""; ?>"  maxlength="25" name="male_member_with_anesthesia_text">
															</td>
														</tr>
														<tr>
															<td></td>
															<td>Maternal</td>
															<td>Paternal</td>
														</tr>
														<tr>
															<td>Diabetes</td>
															<td>
																<input type="radio" checked name="male_maternal_diabetes" value="Yes" <?php if(isset($select_result['male_maternal_diabetes']) && $select_result['male_maternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_maternal_diabetes" value="No" <?php if(isset($select_result['male_maternal_diabetes']) && $select_result['male_maternal_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_diabetes']) && $select_result['male_maternal_diabetes'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_maternal_diabetes_text'])?$select_result['male_maternal_diabetes_text']:""; ?>"  maxlength="25" name="male_maternal_diabetes_text">
															</td>
															<td>
																<input type="radio" checked name="male_paternal_diabetes" value="Yes" <?php if(isset($select_result['male_paternal_diabetes']) && $select_result['male_paternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_paternal_diabetes" value="No" <?php if(isset($select_result['male_paternal_diabetes']) && $select_result['male_paternal_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_diabetes']) && $select_result['male_paternal_diabetes'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_paternal_diabetes_text'])?$select_result['male_paternal_diabetes_text']:""; ?>"  maxlength="25" name="male_paternal_diabetes_text">
															</td>
														</tr>
														<tr>
															<td>Heart/thrombo embolism</td>
															<td>
																<input type="radio" checked name="male_maternal_thrombo_embolism" value="Yes" <?php if(isset($select_result['male_maternal_thrombo_embolism']) && $select_result['male_maternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_maternal_thrombo_embolism" value="No" <?php if(isset($select_result['male_maternal_thrombo_embolism']) && $select_result['male_maternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_thrombo_embolism']) && $select_result['male_maternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_maternal_thrombo_embolism_text'])?$select_result['male_maternal_thrombo_embolism_text']:""; ?>"  maxlength="25" name="male_maternal_thrombo_embolism_text">
															</td>
															<td>
																<input type="radio" checked name="male_paternal_thrombo_embolism" value="Yes" <?php if(isset($select_result['male_paternal_thrombo_embolism']) && $select_result['male_paternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_paternal_thrombo_embolism" value="No" <?php if(isset($select_result['male_paternal_thrombo_embolism']) && $select_result['male_paternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_thrombo_embolism']) && $select_result['male_paternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_paternal_thrombo_embolism_text'])?$select_result['male_paternal_thrombo_embolism_text']:""; ?>"  maxlength="25" name="male_paternal_thrombo_embolism_text">
															</td>
														</tr>
														<tr>
															<td>Endocrine/metabolic</td>
															<td>
																<input type="radio" checked name="male_maternal_metabolic" value="Yes" <?php if(isset($select_result['male_maternal_metabolic']) && $select_result['male_maternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_maternal_metabolic" value="No" <?php if(isset($select_result['male_maternal_metabolic']) && $select_result['male_maternal_metabolic'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_metabolic']) && $select_result['male_maternal_metabolic'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_maternal_metabolic_text'])?$select_result['male_maternal_metabolic_text']:""; ?>"  maxlength="25" name="male_maternal_metabolic_text">
															</td>
															<td>
																<input type="radio" checked name="male_paternal_metabolic" value="Yes" <?php if(isset($select_result['male_paternal_metabolic']) && $select_result['male_paternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_paternal_metabolic" value="No" <?php if(isset($select_result['male_paternal_metabolic']) && $select_result['male_paternal_metabolic'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_metabolic']) && $select_result['male_paternal_metabolic'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_paternal_metabolic_text'])?$select_result['male_paternal_metabolic_text']:""; ?>"  maxlength="25" name="male_paternal_metabolic_text">
															</td>
														</tr>
														<tr>
															<td>Urinary tract/renal</td>
															<td>
																<input type="radio" checked name="male_maternal_urinary_tract" value="Yes" <?php if(isset($select_result['male_maternal_urinary_tract']) && $select_result['male_maternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_maternal_urinary_tract" value="No" <?php if(isset($select_result['male_maternal_urinary_tract']) && $select_result['male_maternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_urinary_tract']) && $select_result['male_maternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_maternal_urinary_tract_text'])?$select_result['male_maternal_urinary_tract_text']:""; ?>"  maxlength="25" name="male_maternal_urinary_tract_text">
															</td>
															<td>
																<input type="radio" checked name="male_paternal_urinary_tract" value="Yes" <?php if(isset($select_result['male_paternal_urinary_tract']) && $select_result['male_paternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_paternal_urinary_tract" value="No" <?php if(isset($select_result['male_paternal_urinary_tract']) && $select_result['male_paternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_urinary_tract']) && $select_result['male_paternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_paternal_urinary_tract_text'])?$select_result['male_paternal_urinary_tract_text']:""; ?>"  maxlength="25" name="male_paternal_urinary_tract_text">
															</td>
														</tr>
														<tr>
															<td>Psychiatric/neurological</td>
															<td>
																<input type="radio" checked name="male_maternal_neurological" value="Yes" <?php if(isset($select_result['male_maternal_neurological']) && $select_result['male_maternal_neurological'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_maternal_neurological" value="No" <?php if(isset($select_result['male_maternal_neurological']) && $select_result['male_maternal_neurological'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_neurological']) && $select_result['male_maternal_neurological'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_maternal_neurological_text'])?$select_result['male_maternal_neurological_text']:""; ?>"  maxlength="25" name="male_maternal_neurological_text">
															</td>
															<td>
																<input type="radio" checked name="male_paternal_neurological" value="Yes" <?php if(isset($select_result['male_paternal_neurological']) && $select_result['male_paternal_neurological'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_paternal_neurological" value="No" <?php if(isset($select_result['male_paternal_neurological']) && $select_result['male_paternal_neurological'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_neurological']) && $select_result['male_paternal_neurological'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_paternal_neurological_text'])?$select_result['male_paternal_neurological_text']:""; ?>"  maxlength="25" name="male_paternal_neurological_text">
															</td>
														</tr>
														<tr>
															<td>Other/malignancy</td>
															<td>
																<input type="radio" checked name="male_maternal_malignancy" value="Yes" <?php if(isset($select_result['male_maternal_malignancy']) && $select_result['male_maternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_maternal_malignancy" value="No" <?php if(isset($select_result['male_maternal_malignancy']) && $select_result['male_maternal_malignancy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_malignancy']) && $select_result['male_maternal_malignancy'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_maternal_malignancy_text'])?$select_result['male_maternal_malignancy_text']:""; ?>"  maxlength="25" name="male_maternal_malignancy_text">
															</td>
															<td>
																<input type="radio" checked name="male_paternal_malignancy" value="Yes" <?php if(isset($select_result['male_paternal_malignancy']) && $select_result['male_paternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="male_paternal_malignancy" value="No" <?php if(isset($select_result['male_paternal_malignancy']) && $select_result['male_paternal_malignancy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_malignancy']) && $select_result['male_paternal_malignancy'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['male_paternal_malignancy_text'])?$select_result['male_paternal_malignancy_text']:""; ?>"  maxlength="25" name="male_paternal_malignancy_text">
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th>PREVIOUS HISTORY OF INFERTILITY TREATMENT</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>NO. OF TIMES IUI CYCLE</td>
															<td>
																<input type="radio" checked name="iui_cycle_times" value="Yes" <?php if(isset($select_result['iui_cycle_times']) && $select_result['iui_cycle_times'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="iui_cycle_times" value="No" <?php if(isset($select_result['iui_cycle_times']) && $select_result['iui_cycle_times'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['iui_cycle_times']) && $select_result['iui_cycle_times'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['iui_cycle_times_text'])?$select_result['iui_cycle_times_text']:""; ?>"  maxlength="50" name="iui_cycle_times_text">
															</td>
														</tr>
														<tr>
															<td>NO. OF TIMES IVF CYCLE</td>
															<td>
																<input type="radio" checked name="ivf_cycle_times" value="Yes" <?php if(isset($select_result['ivf_cycle_times']) && $select_result['ivf_cycle_times'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="ivf_cycle_times" value="No" <?php if(isset($select_result['ivf_cycle_times']) && $select_result['ivf_cycle_times'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['ivf_cycle_times']) && $select_result['ivf_cycle_times'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['ivf_cycle_times_text'])?$select_result['ivf_cycle_times_text']:""; ?>"  maxlength="50" name="ivf_cycle_times_text">
															</td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th>RELATIONSHIP HISTORY</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>SUPPORT FROM HUSBAND:</td>
															<td><input type="text" value="<?php echo isset($select_result['support_from_husband'])?$select_result['support_from_husband']:""; ?>"  maxlength="50" name="support_from_husband"></td>
														</tr>
														<tr>
															<td>SUPPORT FROM IMMEDIATE FAMILY MEMBERS:</td>
															<td><input type="text" value="<?php echo isset($select_result['support_from_family'])?$select_result['support_from_family']:""; ?>"  maxlength="50" name="support_from_family"></td>
														</tr>
														<tr>
															<td>INTERPERSONAL CONFLICTS BETWEEN COUPLE:</td>
															<td><input type="text" value="<?php echo isset($select_result['conflicts'])?$select_result['conflicts']:""; ?>"  maxlength="50" name="conflicts"></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th>PHYSICAL OR SEXUAL ABUSE HISTORY</th>
												<td>
													<input type="radio" checked name="sexual_abuse" value="Yes" <?php if(isset($select_result['sexual_abuse']) && $select_result['sexual_abuse'] == "Yes"){echo 'checked="checked"'; }?> >
													<label>Yes</label>
													<input type="radio" checked name="sexual_abuse" value="No" <?php if(isset($select_result['sexual_abuse']) && $select_result['sexual_abuse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['sexual_abuse']) && $select_result['sexual_abuse'] != "Yes"){echo 'checked="checked"';}?> >
													<label>No</label>
													<input type="text" value="<?php echo isset($select_result['sexual_abuse_text'])?$select_result['sexual_abuse_text']:""; ?>"  maxlength="50" name="sexual_abuse_text">
												</td>
												<td></td>
											</tr>
											<tr>
												<th>LEGAL PROBLEM HISTORY</th>
												<td>
													<input type="radio" checked name="legal_problem" value="Yes" <?php if(isset($select_result['legal_problem']) && $select_result['legal_problem'] == "Yes"){echo 'checked="checked"'; }?> >
													<label>Yes</label>
													<input type="radio" checked name="legal_problem" value="No" <?php if(isset($select_result['legal_problem']) && $select_result['legal_problem'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['legal_problem']) && $select_result['legal_problem'] != "Yes"){echo 'checked="checked"';}?> >
													<label>No</label>
													<input type="text" value="<?php echo isset($select_result['legal_problem_text'])?$select_result['legal_problem_text']:""; ?>"  maxlength="50" name="legal_problem_text">
												</td>
												<td></td>
											</tr>
											<tr>
												<th>MENTAL HISTORY</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>EXPECTATION LEVEL FOR CONCEIVING ON A SCALE FROM (1-10) 1 BEING LEAST 10 BEING MOST</td>
															<td><input type="number" value="<?php echo isset($select_result['expectation'])?$select_result['expectation']:""; ?>"  max="10" min="1" name="expectation"></td>
														</tr>
														<tr>
															<td>BOUTS OF DEPRESSION</td>
															<td><input type="text" value="<?php echo isset($select_result['depression'])?$select_result['depression']:""; ?>"  maxlength="50" name="depression"></td>
														</tr>
														<tr>
															<td>BOUTS OF ANXIETY</td>
															<td><input type="text" value="<?php echo isset($select_result['anxiety'])?$select_result['anxiety']:""; ?>"  maxlength="50" name="anxiety"></td>
														</tr>
														<tr>
															<td>MOOD AND AFFECT</td>
															<td><input type="text" value="<?php echo isset($select_result['affect'])?$select_result['affect']:""; ?>"  maxlength="50" name="affect"></td>
														</tr>
														<tr>
															<td>SUCIDAL TENDENCIES</td>
															<td><input type="text" value="<?php echo isset($select_result['sucidal'])?$select_result['sucidal']:""; ?>"  maxlength="50" name="sucidal"></td>
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
																<input type="radio" checked name="nutritional_assessment" value="Obese">
																<label>Obese</label>
																<input type="radio" checked name="nutritional_assessment" value="average built">
																<label>average built</label>
																<input type="radio" checked name="nutritional_assessment" value="thin">
																<label>thin</label>
																<input type="radio" checked name="nutritional_assessment" value="cachexic">
																<label>cachexic</label>
															</td>
														</tr>
														<tr>
															<td>Psychological assessment</td>
															<td>
																<input type="radio" checked name="psychological_assessment" value="Anxious">
																<label>Anxious</label>
																<input type="radio" checked name="psychological_assessment" value="combative">
																<label>combative</label>
																<input type="radio" checked name="psychological_assessment" value="depressed">
																<label>depressed</label>
																<input type="radio" checked name="psychological_assessment" value="normal">
																<label>normal</label>
															</td>
														</tr>
														<tr>
															<td>Pulse (PER MIN)</td>
															<td><input type="number" value="<?php echo isset($select_result['pulse'])?$select_result['pulse']:""; ?>"  min="0" name="pulse" class="form-control"></td>
														</tr>
														<tr>
															<td>Blood pressure (MM HG)</td>
															<td>
																<table width="100%">
																	<tr>
																		<td>SYSTOLIC</td>
																		<td>
																			<input type="number" value="<?php echo isset($select_result['systolic'])?$select_result['systolic']:""; ?>"  min="0" name="systolic" class="form-control">
																		</td>
																	</tr>
																	<tr>
																		<td>DIASTOLIC</td>
																		<td>
																			<input type="number" value="<?php echo isset($select_result['diastolic'])?$select_result['diastolic']:""; ?>"  min="0" name="diastolic" class="form-control">
																		</td>
																	</tr>
																</table>
															</td>
														</tr>														
														<tr>
															<td>Temperature(DEG F):</td>
															<td><input type="text" value="<?php echo isset($select_result['temperature'])?$select_result['temperature']:""; ?>"  name="temperature" class="form-control"></td>
														</tr>
														<tr>
															<td>CVS</td>
															<td>
																<input type="radio" checked name="cvs" value="Yes" <?php if(isset($select_result['cvs']) && $select_result['cvs'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="cvs" value="No" <?php if(isset($select_result['cvs']) && $select_result['cvs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cvs']) && $select_result['cvs'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['cvs_text'])?$select_result['cvs_text']:""; ?>"  maxlength="20" name="cvs_text">
															</td>
														</tr>
														<tr>
															<td>Chest</td>
															<td>
																<input type="radio" checked name="chest" value="Yes" <?php if(isset($select_result['chest']) && $select_result['chest'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="chest" value="No" <?php if(isset($select_result['chest']) && $select_result['chest'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['chest']) && $select_result['chest'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['chest_text'])?$select_result['chest_text']:""; ?>"  maxlength="20" name="chest_text">
															</td>
														</tr>
														<tr>
															<td>Abdomen</td>
															<td>
																<input type="radio" checked name="abdomen" value="Yes" <?php if(isset($select_result['abdomen']) && $select_result['abdomen'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="abdomen" value="No" <?php if(isset($select_result['abdomen']) && $select_result['abdomen'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['abdomen']) && $select_result['abdomen'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
																<input type="text" value="<?php echo isset($select_result['abdomen_text'])?$select_result['abdomen_text']:""; ?>"  maxlength="20" name="abdomen_text">
															</td>
														</tr>
														<tr>
															<td>Others</td>
															<td><input type="text" value="<?php echo isset($select_result['others2'])?$select_result['others2']:""; ?>"  maxlength="20" name="others2" class="form-control"></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th>MENTAL STATUS EXAMINATION</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>Appearance</td>
															<td><input type="text" value="<?php echo isset($select_result['appearance'])?$select_result['appearance']:""; ?>"  maxlength="20" name="appearance"></td>
														</tr>
														<tr>
															<td>self-care (diet, exercise, sleep)</td>
															<td><input type="text" value="<?php echo isset($select_result['self_care'])?$select_result['self_care']:""; ?>"  maxlength="50" name="self_care"></td>
														</tr>
														<tr>
															<td>General Behaviour</td>
															<td><input type="text" value="<?php echo isset($select_result['general_behaviour'])?$select_result['general_behaviour']:""; ?>"  maxlength="50" name="general_behaviour"></td>
														</tr>
														<tr>
															<td>Level of Consciousness</td>
															<td><input type="text" value="<?php echo isset($select_result['consciousness'])?$select_result['consciousness']:""; ?>"  maxlength="20" name="consciousness"></td>
														</tr>
														<tr>
															<td>Orientation</td>
															<td><input type="text" value="<?php echo isset($select_result['orientation'])?$select_result['orientation']:""; ?>"  maxlength="20" name="orientation"></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th>MANAGEMENT ADVISED</th>
												<td style="padding: 0;">
													<table width="100%">
														<tr>
															<td>PSYCHOLOGICAL COUNSELLING</td>
															<td>
																<input type="radio" checked name="psychological_counselling" value="Yes" <?php if(isset($select_result['psychological_counselling']) && $select_result['psychological_counselling'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="psychological_counselling" value="No" <?php if(isset($select_result['psychological_counselling']) && $select_result['psychological_counselling'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['psychological_counselling']) && $select_result['psychological_counselling'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
															</td>
														</tr>
														<tr>
															<td>COGNITIVE THERAPY</td>
															<td>
																<input type="radio" checked name="cognitive_therapy" value="Yes" <?php if(isset($select_result['cognitive_therapy']) && $select_result['cognitive_therapy'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="cognitive_therapy" value="No" <?php if(isset($select_result['cognitive_therapy']) && $select_result['cognitive_therapy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cognitive_therapy']) && $select_result['cognitive_therapy'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
															</td>
														</tr>
														<tr>
															<td>PSYCHIATRIST REFERRAL FOR MEDICATIONS</td>
															<td>
																<input type="radio" checked name="psychiatrist_referral" value="Yes" <?php if(isset($select_result['psychiatrist_referral']) && $select_result['psychiatrist_referral'] == "Yes"){echo 'checked="checked"'; }?> >
																<label>Yes</label>
																<input type="radio" checked name="psychiatrist_referral" value="No" <?php if(isset($select_result['psychiatrist_referral']) && $select_result['psychiatrist_referral'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['psychiatrist_referral']) && $select_result['psychiatrist_referral'] != "Yes"){echo 'checked="checked"';}?> >
																<label>No</label>
															</td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<!-- <tr>
												<th>NEXT FOLLOW UP</th>
												<td><input type="date" value="<?php echo isset($select_result['female_followup'])?$select_result['female_followup']:""; ?>"  name="female_followup" class="form-control"></td>
												<td><input type="date" value="<?php echo isset($select_result['male_followup'])?$select_result['male_followup']:""; ?>"  name="male_followup" class="form-control"></td>
											</tr><br>
											<tr>
												<th>PURPOSE OF NEXT FOLLOWUP</th>
												<td>
													<input type="radio" checked name="next_followup" value="FIRST CONSULTATION">
													<label>FIRST CONSULTATION</label>
													<input type="radio" checked name="next_followup" value="FOLLOW UP VISIT">
													<label>FOLLOW UP VISIT</label>
													<input type="radio" checked name="next_followup" value="PROCEDURE">
													<label>PROCEDURE</label><br>
													Mapped to Purpose of consultation
												</td>
												<td>Mapped to Purpose of consultation</td>
											</tr> -->
										</table>
										<!-- /.card-body -->
										<div class="card-footer">
											<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
										</div>
									</form>
									
									
									
									
									
									
									
									
									
									
	
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none"> 


<!--   initial_assesment_sheet_psychology        -->
 <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">INITIAL ASSEMENT SHEET PSYCHOLOGY</h3></td>
   </tr>
</table>
     				 <table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">

<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
	   </table>

<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
	<tr>
		<td colspan="2" style="border:1px solid #cdcdcd;">
		    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
		            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
		            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
		            ){?>
		        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
		    <?php } ?>
		</td>
	</tr>
</table>
										
										<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
										
											<?php 
		@		$upload1=$select_result['female_photographs'];
		@		$upload2=$select_result['male_photographs'];
				
					?>	
										
											<tr>
												<th  style="border:1px solid #cdcdcd;"></th>
												<td  style="border:1px solid #cdcdcd;"><b>FEMALE</b></td>
												<td  style="border:1px solid #cdcdcd;"><b>MALE</b></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">PHOTOGRAPHS</th>
												
												<td  style="border:1px solid #cdcdcd; padding: 0;">
													
													<?php if(!empty($upload1)) {?>
				 <img src="<?php echo $upload1;?>" style="width:100px; height:100px;">
					<?php } else {echo " ";}?>
													
										
												</td>
												<td  style="border:1px solid #cdcdcd; padding: 0;">
													<?php if(!empty($upload2)) {?>
				 <img src="<?php echo $upload2;?>" style="width:100px; height:100px;">
					<?php } else {echo " ";}?>
												</td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">NAME</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_name'])?$select_result['female_name']:""; ?></td>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_name'])?$select_result['male_name']:""; ?></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">AGE (Years)</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_age'])?$select_result['female_age']:""; ?></td>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_age'])?$select_result['male_age']:""; ?></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">OCCUPATION</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_occupation'])?$select_result['female_occupation']:""; ?></td>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_occupation'])?$select_result['male_occupation']:""; ?></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">NATIONALITY</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_nationality'])?$select_result['female_nationality']:""; ?></td>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_nationality'])?$select_result['male_nationality']:""; ?></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">ETHNICITY</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_religion'])?$select_result['female_religion']:""; ?></td>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_religion'])?$select_result['male_religion']:""; ?></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">AADHAR NUMBER</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_aadhar_number'])?$select_result['female_aadhar_number']:""; ?></td>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_aadhar_number'])?$select_result['male_aadhar_number']:""; ?></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">PRESENT ADDRESS</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_present_address'])?$select_result['female_present_address']:""; ?>  </td>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_present_address'])?$select_result['male_present_address']:""; ?></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">PERMANENT ADDRESS</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_permanent_address'])?$select_result['female_permanent_address']:""; ?></td>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_permanent_address'])?$select_result['male_permanent_address']:""; ?>  </td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">CONTACT NO</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_mobile_no'])?$select_result['female_mobile_no']:""; ?></td>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_mobile_no'])?$select_result['male_mobile_no']:""; ?></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">EMAIL ADDRESS</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_email_address'])?$select_result['female_email_address']:""; ?></td>
												<th  style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_email_address'])?$select_result['male_email_address']:""; ?></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">WT (kg)/HT(m) /BMI(kg/m2)</th>
												<td  style="border:1px solid #cdcdcd;"></td>
												<td  style="border:1px solid #cdcdcd;">NOT REQUIRED</td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">MARITAL STATUS</th>
												<td  style="border:1px solid #cdcdcd;">
<?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Single"){echo 'Single'; }?> 
<?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Engaged"){echo 'Engaged'; }?> 
<?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Married"){echo 'Married'; }?> 
<?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Separated"){echo 'Separated'; }?> 
<?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Divorced"){echo 'Divorced'; }?>
 <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Widowed"){echo 'Widowed'; }?>
												</td>
			
			
			
			<td  style="border:1px solid #cdcdcd;">
													
				 <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Single"){echo 'Single'; }?> 
			 <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Engaged"){echo 'Engaged'; }?> 
		<?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Married"){echo 'Married'; }?> 
<?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Separated"){echo 'Separated'; }?> 
<?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Divorced"){echo 'Divorced'; }?>
 <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Widowed"){echo 'Widowed'; }?>										
			
	</td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">H/O previous pregnancies(IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Total pregnancies</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_total_pregnancies'])?$select_result['female_total_pregnancies']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">No.of live births</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_live_births'])?$select_result['female_live_births']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">No.of spontaneous abortions</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_spontaneous_abortions'])?$select_result['female_spontaneous_abortions']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">No.of termination of pregnancy</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_termination_of_pregnancy'])?$select_result['female_termination_of_pregnancy']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">No.of still births</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_still_births'])?$select_result['female_still_births']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">No. of ectopic pregnancy</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_ectopic_pregnancy'])?$select_result['female_ectopic_pregnancy']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">History of any abnormality in child</td>
															<td  style="border:1px solid #cdcdcd;">
																
  
  
  <?php if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] == "Yes"){echo 'Yes'; }?> <br> 
  
				<?php echo isset($select_result['female_history_of_abnormality_text'])?$select_result['female_history_of_abnormality_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Others</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_others'])?$select_result['female_others']:""; ?></td>
														</tr>
													</table>
												</td>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Total pregnancies INCLUDING PREVIOUS pregnancies</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_total_pregnancies'])?$select_result['male_total_pregnancies']:""; ?></td>
														</tr>
														<tr>
						<td colspan="2" style="border:1px solid #cdcdcd;"><br><br><br><br><br><br><br><br><br><br><br><br><br></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">PRESENTING COMPLAINT</th>
												<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['presenting_complaint'])?$select_result['presenting_complaint']:""; ?></td>
												<td  style="border:1px solid #cdcdcd;">NOT NEEDED</td>
											</tr>

											<tr>
												<th  style="border:1px solid #cdcdcd;">DESCRIPTION OF PRESENTING PROBLEM</th>
												<td colspan="2" style="padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">State in patients own words the nature of main problems:</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nature_problems'])?$select_result['nature_problems']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">On the scale below, please estimate the severity of your problem(s) now.</td>
															<td  style="border:1px solid #cdcdcd;">
	<?php if(isset($select_result['severity']) && $select_result['severity'] == "Yes"){echo 'Mildly upsetting'; }?><br>
 <?php if(isset($select_result['severity']) && $select_result['severity'] == "Yes"){echo 'Severely upsetting"'; }?> 
<?php if(isset($select_result['severity']) && $select_result['severity'] == "Yes"){echo 'Extremely Severe'; }?>  
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">When did your problems begin?</td>
	<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['problem_begin'])?$select_result['problem_begin']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Please describe significant events occurring at that time, or since then, which may relate to the development or maintenance of your problems.</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['maintenance'])?$select_result['maintenance']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Have you been in therapy before or received any prior professional assistance for your problems?  If so, please give names, professional titles, dates of treatment, and results.</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['therapy_before'])?$select_result['therapy_before']:""; ?>"</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Have you ever been hospitalized for a psychological problem?</td>
															<td  style="border:1px solid #cdcdcd;">
																
						

<?php if(isset($select_result['psychological_problem']) && $select_result['psychological_problem'] == "Yes"){echo 'Yes'; }?>
						
																
					<?php echo isset($select_result['psychological_problem_text'])?$select_result['psychological_problem_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Have you ever attempted suicide</td>
															
															
															<td  style="border:1px solid #cdcdcd;">
																
  
				<?php if(isset($select_result['suicide_attempt']) && $select_result['suicide_attempt'] == "Yes"){echo 'Yes'; }?> <br>													
																<?php echo isset($select_result['suicide_attempt_text'])?$select_result['suicide_attempt_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Do you practice relaxation or meditation regularly?</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['relaxation'])?$select_result['relaxation']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Present Feelings</td>
<td  style="border:1px solid #cdcdcd;">
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Angry"){echo 'Angry'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Guilty"){echo 'Guilty'; }?>
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Unhappy"){echo 'Unhappy'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Annoyed"){echo 'Annoyed'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Happy"){echo 'Happy'; }?>
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Bored"){echo 'Bored'; }?>
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Depressed"){echo 'Depressed'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Regretful"){echo 'Regretful'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Lonely"){echo 'Lonely'; }?> <br>
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Anxious"){echo 'Anxious'; }?> 
 <?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Hopeless"){echo 'Hopeless'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Contented"){echo 'Contented'; }?>
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Fearful"){echo 'Fearful'; }?>
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Hopeful"){echo 'Hopeful'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Excited"){echo 'Excited'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Panicky"){echo 'Panicky'; }?>
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Helpless"){echo 'Helpless'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Optimistic"){echo 'Optimistic'; }?> <br>
 <?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Energetic"){echo 'Energetic'; }?> 
 <?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Relaxed"){echo 'Relaxed'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Tense"){echo 'Tense'; }?> 
 <?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Envy"){echo 'Envy'; }?> 
<?php if(isset($select_result['feelings']) && $select_result['feelings'] == "Jealous"){echo 'Jealous'; }?> 
</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">List your 5 main fears:</td>
															<td  style="border:1px solid #cdcdcd;">
																1. <?php echo isset($select_result['main_fear_1'])?$select_result['main_fear_1']:""; ?><br>
																2. <?php echo isset($select_result['main_fear_2'])?$select_result['main_fear_2']:""; ?><br>
																3. <?php echo isset($select_result['main_fear_3'])?$select_result['main_fear_3']:""; ?><br>
																4. <?php echo isset($select_result['main_fear_4'])?$select_result['main_fear_4']:""; ?><br>
																5. <?php echo isset($select_result['main_fear_5'])?$select_result['main_fear_5']:""; ?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">PAST MEDICAL HISTORY</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
																
				<?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "Yes"){echo 'Yes'; }?> <br>													
																<?php echo isset($select_result['heart_attack_text'])?$select_result['heart_attack_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Heart attack</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
																
				<?php if(isset($select_result['pacemaker']) && $select_result['pacemaker'] == "Yes"){echo 'Yes'; }?> <br>													
																
																<?php echo isset($select_result['pacemaker_text'])?$select_result['pacemaker_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Pacemaker</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
														
																
			<?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "Yes"){echo 'Yes'; }?> <br>													
																<?php echo isset($select_result['other_heart_disease_text'])?$select_result['other_heart_disease_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Other heart disease</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
																
			<?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "Yes"){echo 'Yes'; }?> <br>														
				<?php echo isset($select_result['high_blood_pressure_text'])?$select_result['high_blood_pressure_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">High blood pressure</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
						<?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "Yes"){echo 'Yes'; }?> <br>
					<?php echo isset($select_result['blood_clots_text'])?$select_result['blood_clots_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Blood clots</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
														
							<?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "Yes"){echo 'Yes'; }?> <br>
						<?php echo isset($select_result['chest_pain_text'])?$select_result['chest_pain_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Chest pain</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
			<?php if(isset($select_result['stroke']) && $select_result['stroke'] == "Yes"){echo 'Yes'; }?> <br>
			<?php echo isset($select_result['stroke_text'])?$select_result['stroke_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Stroke</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
														
					<?php if(isset($select_result['asthma']) && $select_result['asthma'] == "Yes"){echo 'Yes'; }?> <br>
					<?php echo isset($select_result['asthma_text'])?$select_result['asthma_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Asthma</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
				<?php if(isset($select_result['lung_disease']) && $select_result['lung_disease'] == "Yes"){echo 'Yes'; }?> <br>													
																
				<?php echo isset($select_result['lung_disease_text'])?$select_result['lung_disease_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Other lung disease</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
												
					<?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "Yes"){echo 'Yes'; }?> <br>												
			<?php echo isset($select_result['difficulty_breathing_text'])?$select_result['difficulty_breathing_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Difficulty breathing</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
													
			<?php if(isset($select_result['snoring']) && $select_result['snoring'] == "Yes"){echo 'Yes'; }?> <br>														
																<?php echo isset($select_result['snoring_text'])?$select_result['snoring_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Sleep apnea or snoring</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
				<?php if(isset($select_result['epilepsy']) && $select_result['epilepsy'] == "Yes"){echo 'Yes'; }?> <br>
														
		<?php echo isset($select_result['epilepsy_text'])?$select_result['epilepsy_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Epilepsy or seizures</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
												
		
		<?php if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] == "Yes"){echo 'Yes'; }?> <br>
		
	<?php echo isset($select_result['fainting_spells_text'])?$select_result['fainting_spells_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Fainting spells</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
													
																
		<?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "Yes"){echo 'Yes'; }?> <br>									
		<?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Diabetes</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
															
																
		<?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "Yes"){echo 'Yes'; }?> <br>																	
		<?php echo isset($select_result['muscle_disorders_text'])?$select_result['muscle_disorders_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Muscle disorders</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
												
			<?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "Yes"){echo 'Yes'; }?> <br>													
		<?php echo isset($select_result['kidney_disease_text'])?$select_result['kidney_disease_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Kidney disease</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
														
			<?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "Yes"){echo 'Yes'; }?> <br>														
			<?php echo isset($select_result['hepatitis_text'])?$select_result['hepatitis_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Hepatitis</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
											
			<?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "Yes"){echo 'Yes'; }?> <br>														
																
																
																<?php echo isset($select_result['tuberculosis_text'])?$select_result['tuberculosis_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Tuberculosis</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
													
						<?php if(isset($select_result['hiv']) && $select_result['hiv'] == "Yes"){echo 'Yes'; }?> <br>										
					<?php echo isset($select_result['hiv_text'])?$select_result['hiv_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">HIV</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
												
																
				<?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "Yes"){echo 'Yes'; }?> <br>	<?php echo isset($select_result['heart_burn_text'])?$select_result['heart_burn_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Heart burn/reflux</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
													
		<?php if(isset($select_result['cancer']) && $select_result['cancer'] == "Yes"){echo 'Yes'; }?> <br>	
		<?php echo isset($select_result['cancer_text'])?$select_result['cancer_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Cancer</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
												
												
					<?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "Yes"){echo 'Yes'; }?> <br>	

					<?php echo isset($select_result['blood_disorders_text'])?$select_result['blood_disorders_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Blood disorders</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
											
																
					<?php if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] == "Yes"){echo 'Yes'; }?> <br>												
																
					<?php echo isset($select_result['rheumatic_disease_text'])?$select_result['rheumatic_disease_text']:""; ?>
															</td>
															
															<td  style="border:1px solid #cdcdcd;">Rheumatic disease</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
														
							<?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "Yes"){echo 'Yes'; }?> <br>											
																
																
		<?php echo isset($select_result['psychiatric_disorder_text'])?$select_result['psychiatric_disorder_text']:""; ?>
																
																
																
																
																
																
															</td>
															<td  style="border:1px solid #cdcdcd;">Psychiatric disorder</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
												
			<?php if(isset($select_result['chithyroid_disorderldren']) && $select_result['chithyroid_disorderldren'] == "Yes"){echo 'Yes'; }?> <br>														
																
																
																<?php echo isset($select_result['thyroid_disorder_text'])?$select_result['thyroid_disorder_text']:""; ?>  
															</td>
															<td  style="border:1px solid #cdcdcd;">Thyroid disorder</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
													
			<?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "Yes"){echo 'Yes'; }?> <br>															
																
																
																<?php echo isset($select_result['urinary_infection_text'])?$select_result['urinary_infection_text']:""; ?>  
															</td>
															<td  style="border:1px solid #cdcdcd;">Urinary infection</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
											
		<?php if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] == "Yes"){echo 'Yes'; }?> <br>															
																
																
																<?php echo isset($select_result['sexually_transmitted_text'])?$select_result['sexually_transmitted_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Sexually transmitted disease</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['impairments'])?$select_result['impairments']:""; ?></td>
															<td  style="border:1px solid #cdcdcd;">Other medical condition or impairments</td>
														</tr>
													</table>
												</td>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Heart attack</td>
															<td  style="border:1px solid #cdcdcd;">
												
																
			<?php if(isset($select_result['male_heart_attack']) && $select_result['male_heart_attack'] == "Yes"){echo 'Yes'; }?> <br>															
			
																<?php echo isset($select_result['male_heart_attack_text'])?$select_result['male_heart_attack_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Pacemaker</td>
															<td  style="border:1px solid #cdcdcd;">
												
																
			<?php if(isset($select_result['male_pacemaker']) && $select_result['male_pacemaker'] == "Yes"){echo 'Yes'; }?> <br>															
																
																
																<?php echo isset($select_result['male_pacemaker_text'])?$select_result['male_pacemaker_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Other heart disease</td>
															<td  style="border:1px solid #cdcdcd;">
																	
																
				<?php if(isset($select_result['male_other_heart_disease']) && $select_result['male_other_heart_disease'] == "Yes"){echo 'Yes'; }?> <br>													
																
																
																<?php echo isset($select_result['male_other_heart_disease_text'])?$select_result['male_other_heart_disease_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">High blood pressure</td>
															<td  style="border:1px solid #cdcdcd;">
												
					<?php if(isset($select_result['male_high_blood_pressure']) && $select_result['male_high_blood_pressure'] == "Yes"){echo 'Yes'; }?> <br>										
																			
																<?php echo isset($select_result['male_high_blood_pressure_text'])?$select_result['male_high_blood_pressure_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Blood clots</td>
															<td  style="border:1px solid #cdcdcd;">
												
				<?php if(isset($select_result['male_blood_clots']) && $select_result['male_blood_clots'] == "Yes"){echo 'Yes'; }?> <br>										
																
																<?php echo isset($select_result['male_blood_clots_text'])?$select_result['male_blood_clots_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Chest pain</td>
															<td  style="border:1px solid #cdcdcd;">
													
				<?php if(isset($select_result['male_chest_pain']) && $select_result['male_chest_pain'] == "Yes"){echo 'Yes'; }?> <br>															
					<?php echo isset($select_result['male_chest_pain_text'])?$select_result['male_chest_pain_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Stroke</td>
															<td  style="border:1px solid #cdcdcd;">
												
																
					<?php if(isset($select_result['male_stroke']) && $select_result['male_stroke'] == "Yes"){echo 'Yes'; }?> <br>												
																
						<?php echo isset($select_result['male_stroke_text'])?$select_result['male_stroke_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Asthma</td>
															<td  style="border:1px solid #cdcdcd;">
													
																
																
											<?php if(isset($select_result['male_asthma']) && $select_result['male_asthma'] == "Yes"){echo 'Yes'; }?> <br>							
																
																
																<?php echo isset($select_result['male_asthma_text'])?$select_result['male_asthma_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Other lung disease</td>
															<td  style="border:1px solid #cdcdcd;">
														
											
																
																
							<?php if(isset($select_result['male_lung_disease']) && $select_result['male_lung_disease'] == "Yes"){echo 'Yes'; }?> <br>										
												
																
																<?php echo isset($select_result['male_lung_disease_text'])?$select_result['male_lung_disease_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Difficulty breathing</td>
															<td  style="border:1px solid #cdcdcd;">
												
																
				<?php if(isset($select_result['male_difficulty_breathing']) && $select_result['male_difficulty_breathing'] == "Yes"){echo 'Yes'; }?> <br>											
																						
																
																
																<?php echo isset($select_result['male_difficulty_breathing_text'])?$select_result['male_difficulty_breathing_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Sleep apnea or snoring</td>
															<td  style="border:1px solid #cdcdcd;">
													
						<?php if(isset($select_result['male_snoring']) && $select_result['male_snoring'] == "Yes"){echo 'Yes'; }?> <br>											
																
																<?php echo isset($select_result['male_snoring_text'])?$select_result['male_snoring_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Epilepsy or seizures</td>
															<td  style="border:1px solid #cdcdcd;">
													
																
				<?php if(isset($select_result['male_epilepsy']) && $select_result['male_epilepsy'] == "Yes"){echo 'Yes'; }?> <br>														
			
																<?php echo isset($select_result['male_epilepsy_text'])?$select_result['male_epilepsy_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Fainting spells</td>
															<td  style="border:1px solid #cdcdcd;">
												
			<?php if(isset($select_result['male_fainting_spells']) && $select_result['male_fainting_spells'] == "Yes"){echo 'Yes'; }?> <br>														
																		
			<?php echo isset($select_result['male_fainting_spells_text'])?$select_result['male_fainting_spells_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Diabetes</td>
															<td  style="border:1px solid #cdcdcd;">
												
																
<?php if(isset($select_result['male_diabetes']) && $select_result['male_diabetes'] == "Yes"){echo 'Yes'; }?> <br>														
<?php echo isset($select_result['male_diabetes_text'])?$select_result['male_diabetes_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Muscle disorders</td>
															<td  style="border:1px solid #cdcdcd;">
													
							<?php if(isset($select_result['male_muscle_disorders']) && $select_result['male_muscle_disorders'] == "Yes"){echo 'Yes'; }?> <br>														
																
									<?php echo isset($select_result['male_muscle_disorders_text'])?$select_result['male_muscle_disorders_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Kidney disease</td>
															<td  style="border:1px solid #cdcdcd;">
												
																
							<?php if(isset($select_result['male_kidney_disease']) && $select_result['male_kidney_disease'] == "Yes"){echo 'Yes'; }?> <br>												
																

																<?php echo isset($select_result['male_kidney_disease_text'])?$select_result['male_kidney_disease_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Hepatitis</td>
															<td  style="border:1px solid #cdcdcd;">
												
																
																
										<?php if(isset($select_result['male_hepatitis']) && $select_result['male_hepatitis'] == "Yes"){echo 'Yes'; }?> <br>								
																
																
																<?php echo isset($select_result['male_hepatitis_text'])?$select_result['male_hepatitis_text']:""; ?>
																
											
																
																
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Tuberculosis</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
						<?php if(isset($select_result['male_tuberculosis']) && $select_result['male_tuberculosis'] == "Yes"){echo 'Yes'; }?> <br>												
																
																
																
																<?php echo isset($select_result['male_tuberculosis_text'])?$select_result['male_tuberculosis_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">HIV</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
				<?php if(isset($select_result['male_hiv']) && $select_result['male_hiv'] == "Yes"){echo 'Yes'; }?> <br>														
																
																
																<?php echo isset($select_result['male_hiv_text'])?$select_result['male_hiv_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Heart burn/reflux</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
					<?php if(isset($select_result['male_heart_burn']) && $select_result['male_heart_burn'] == "Yes"){echo 'Yes'; }?> <br>												
																			
										
																<?php echo isset($select_result['male_heart_burn_text'])?$select_result['male_heart_burn_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Cancer </td>
															<td  style="border:1px solid #cdcdcd;">
												
																
					<?php if(isset($select_result['male_cancer']) && $select_result['male_cancer'] == "Yes"){echo 'Yes'; }?> <br>												
								
																
																<?php echo isset($select_result['male_cancer_text'])?$select_result['male_cancer_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Blood disorders</td>
															<td  style="border:1px solid #cdcdcd;">
															
																
									<?php if(isset($select_result['male_blood_disorders']) && $select_result['male_blood_disorders'] == "Yes"){echo 'Yes'; }?> <br>									
																
																
																<?php echo isset($select_result['male_blood_disorders_text'])?$select_result['male_blood_disorders_text']:""; ?>
												
																
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Rheumatic disease</td>
															<td  style="border:1px solid #cdcdcd;">
											
																
				<?php if(isset($select_result['male_rheumatic_disease']) && $select_result['male_rheumatic_disease'] == "Yes"){echo 'Yes'; }?> <br>														
																
																<?php echo isset($select_result['male_rheumatic_disease_text'])?$select_result['male_rheumatic_disease_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Psychiatric disorder</td>
															<td  style="border:1px solid #cdcdcd;">
													
																
					<?php if(isset($select_result['male_psychiatric_disorder']) && $select_result['male_psychiatric_disorder'] == "Yes"){echo 'Yes'; }?> <br>											
																
																<?php echo isset($select_result['male_psychiatric_disorder_text'])?$select_result['male_psychiatric_disorder_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Thyroid disorder</td>
															<td  style="border:1px solid #cdcdcd;">
												
																
					<?php if(isset($select_result['male_thyroid_disorder']) && $select_result['male_thyroid_disorder'] == "Yes"){echo 'Yes'; }?> <br>														
																			
																
																<?php echo isset($select_result['male_thyroid_disorder_text'])?$select_result['male_thyroid_disorder_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Urinary infection</td>
															<td  style="border:1px solid #cdcdcd;">
															
		<?php if(isset($select_result['male_urinary_infection']) && $select_result['male_urinary_infection'] == "Yes"){echo 'Yes'; }?> <br>														
																
																<?php echo isset($select_result['male_urinary_infection_text'])?$select_result['male_urinary_infection_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Sexually transmitted disease</td>
															<td  style="border:1px solid #cdcdcd;">
															
															<?php if(isset($select_result['male_sexually_transmitted']) && $select_result['male_sexually_transmitted'] == "Yes"){echo 'Yes'; }?> <br>			
																
																<?php echo isset($select_result['male_sexually_transmitted_text'])?$select_result['male_sexually_transmitted_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Other medical condition or impairments</td>
															<td  style="border:1px solid #cdcdcd;">
																<?php echo isset($select_result['male_impairments'])?$select_result['male_impairments']:""; ?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">PAST ANESTHESIA AND SURGICAL PROCEDURES</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Laparoscopy/pelvic/abdominal operations</td>
															<td  style="border:1px solid #cdcdcd;">
														
																
											<?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "Yes"){echo 'Yes'; }?> <br>							
																
																<?php echo isset($select_result['abdominal_operations_text'])?$select_result['abdominal_operations_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Other operations</td>
															<td  style="border:1px solid #cdcdcd;">
													
				<?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "Yes"){echo 'Yes'; }?> <br>												
																
																<?php echo isset($select_result['other_operations_text'])?$select_result['other_operations_text']:""; ?>
															</td>
														</tr>
													</table>
												</td>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Laparoscopy/pelvic/abdominal operations</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
													
						<?php if(isset($select_result['male_abdominal_operations']) && $select_result['male_abdominal_operations'] == "Yes"){echo 'Yes'; }?> <br>												
																
																
																
																<?php echo isset($select_result['male_abdominal_operations_text'])?$select_result['male_abdominal_operations_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Other operations</td>
															<td  style="border:1px solid #cdcdcd;">
														
						<?php if(isset($select_result['male_other_operations']) && $select_result['male_other_operations'] == "Yes"){echo 'Yes'; }?> <br>											
																
																<?php echo isset($select_result['male_other_operations_text'])?$select_result['male_other_operations_text']:""; ?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">ALLERGY HISTORY</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Medications</td>
															<td  style="border:1px solid #cdcdcd;">
													
									<?php if(isset($select_result['medications']) && $select_result['medications'] == "Yes"){echo 'Yes'; }?> <br>									
																<?php echo isset($select_result['medications_text'])?$select_result['medications_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">environmental factors</td>
															<td  style="border:1px solid #cdcdcd;">
													
							<?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "Yes"){echo 'Yes'; }?> <br>												
																
																
																<?php echo isset($select_result['environmental_factors_text'])?$select_result['environmental_factors_text']:""; ?>
															</td>
														</tr>
													</table>
												</td>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Medications</td>
															<td  style="border:1px solid #cdcdcd;">
													
																
				<?php if(isset($select_result['male_medications']) && $select_result['male_medications'] == "Yes"){echo 'Yes'; }?> <br>													
																
				<?php echo isset($select_result['male_medications_text'])?$select_result['male_medications_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">environmental factors</td>
															<td  style="border:1px solid #cdcdcd;">
														
						<?php if(isset($select_result['male_environmental_factors']) && $select_result['male_environmental_factors'] == "Yes"){echo 'Yes'; }?> <br>															
																	
																
																<?php echo isset($select_result['male_environmental_factors_text'])?$select_result['male_environmental_factors_text']:""; ?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">SOCIAL & DRUG INTAKE HISTORY</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td width="20%">
															
												
																
			<?php if(isset($select_result['dentures']) && $select_result['dentures'] == "Yes"){echo 'Yes'; }?> <br>															
													
																<?php echo isset($select_result['dentures_text'])?$select_result['dentures_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Dentures</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
																
																
				<?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "Yes"){echo 'Yes'; }?> <br>															
																
			<?php echo isset($select_result['loose_teeth_text'])?$select_result['loose_teeth_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Loose teeth</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
														
					<?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "Yes"){echo 'Yes'; }?> <br>												
					<?php echo isset($select_result['hearing_aid_text'])?$select_result['hearing_aid_text']:""; ?>
														
															</td>
															<td  style="border:1px solid #cdcdcd;">Hearing aid</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
																
																
			<?php if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] == "Yes"){echo 'Yes'; }?> <br>														
																
																<?php echo isset($select_result['caps_on_front_teeth_text'])?$select_result['caps_on_front_teeth_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Caps on front teeth</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
																
																
						<?php if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] == "Yes"){echo 'Yes'; }?> <br>											
																
																
																
																<?php echo isset($select_result['contact_lenses_text'])?$select_result['contact_lenses_text']:""; ?>   
															</td>
															<td  style="border:1px solid #cdcdcd;">Contact lenses</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
																
				<?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "Yes"){echo 'Yes'; }?> <br>												
																					
																<?php echo isset($select_result['body_piercing_text'])?$select_result['body_piercing_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Body piercing</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
															
																
																
				<?php if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] == "Yes"){echo 'Yes'; }?> <br>												
																
																<?php echo isset($select_result['blood_transfusion_text'])?$select_result['blood_transfusion_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">H/o blood transfusion</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
													

	<?php if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] == "Yes"){echo 'Yes'; }?> <br>
													
				<?php echo isset($select_result['traffic_accident_text'])?$select_result['traffic_accident_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">H/o road traffic accident/any injury</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
																
									<?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "Yes"){echo 'Yes'; }?> <br>					

														
																<?php echo isset($select_result['smoke_past_text'])?$select_result['smoke_past_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Smoke(past)daily</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
													
						<?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "Yes"){echo 'Yes'; }?> <br>													
							<?php echo isset($select_result['smoke_present_text'])?$select_result['smoke_present_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Smoke(present)daily</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
											
							<?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "Yes"){echo 'Yes'; }?> <br>										
																
																
																<?php echo isset($select_result['drink_past_text'])?$select_result['drink_past_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Drink(past)units per week</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
																
																
							<?php if(isset($select_result['drink_present']) && $select_result['drink_present'] == "Yes"){echo 'Yes'; }?> <br>										
																
																<?php echo isset($select_result['drink_present_text'])?$select_result['drink_present_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Drink(present)units per week</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
															
																
																
							<?php if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] == "Yes"){echo 'Yes'; }?> <br>											
																<?php echo isset($select_result['abusive_drugs_text'])?$select_result['abusive_drugs_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Hashish/cocaine /abusive drugs</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
													
							<?php if(isset($select_result['steroid']) && $select_result['steroid'] == "Yes"){echo 'Yes'; }?> <br>										
																
																
																<?php echo isset($select_result['steroid_text'])?$select_result['steroid_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Have you ever used cortisone/steroid</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
											
																
								<?php if(isset($select_result['medication']) && $select_result['medication'] == "Yes"){echo 'Yes'; }?> <br>								
																
																
																<?php echo isset($select_result['medication_text'])?$select_result['medication_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Medication</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
													
																
					<?php if(isset($select_result['herbal_products']) && $select_result['herbal_products'] == "Yes"){echo 'Yes'; }?> <br>											
						<?php echo isset($select_result['herbal_products_text'])?$select_result['herbal_products_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Herbal products</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
																
												
																
				<?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "Yes"){echo 'Yes'; }?> <br>												
							
																<?php echo isset($select_result['eye_drops_text'])?$select_result['eye_drops_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Eye drops</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">
													
										<?php if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] == "Yes"){echo 'Yes'; }?> <br>											
																
																
																<?php echo isset($select_result['non_prescription_drugs_text'])?$select_result['non_prescription_drugs_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">Non prescription drugs used currently other than medications used for this IVF cycle</td>
														</tr>
													</table>
												</td>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Dentures</td>
															<td width="30%">
																
										<?php if(isset($select_result['male_dentures']) && $select_result['male_dentures'] == "Yes"){echo 'Yes'; }?> <br>								
																
																
																<?php echo isset($select_result['male_dentures_text'])?$select_result['male_dentures_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Loose teeth</td>
															<td  style="border:1px solid #cdcdcd;">
													
																
				<?php if(isset($select_result['male_loose_teeth']) && $select_result['male_loose_teeth'] == "Yes"){echo 'Yes'; }?> <br>														
						
																<?php echo isset($select_result['male_loose_teeth_text'])?$select_result['male_loose_teeth_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Hearing aid</td>
															<td  style="border:1px solid #cdcdcd;">
												
																			
						<?php if(isset($select_result['male_hearing_aid']) && $select_result['male_hearing_aid'] == "Yes"){echo 'Yes'; }?> <br>									
																
																
																<?php echo isset($select_result['male_hearing_aid_text'])?$select_result['male_hearing_aid_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Caps on front teeth</td>
															<td  style="border:1px solid #cdcdcd;">
													
																
						<?php if(isset($select_result['male_caps_on_front_teeth']) && $select_result['male_caps_on_front_teeth'] == "Yes"){echo 'Yes'; }?> <br>												
																
																
																<?php echo isset($select_result['male_caps_on_front_teeth_text'])?$select_result['male_caps_on_front_teeth_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Contact lenses</td>
															<td  style="border:1px solid #cdcdcd;">
														
																
									<?php if(isset($select_result['male_contact_lenses']) && $select_result['male_contact_lenses'] == "Yes"){echo 'Yes'; }?> <br>								
																
																<?php echo isset($select_result['male_contact_lenses_text'])?$select_result['male_contact_lenses_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Body piercing</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
									<?php if(isset($select_result['male_body_piercing']) && $select_result['male_body_piercing'] == "Yes"){echo 'Yes'; }?> <br>								
																<?php echo isset($select_result['male_body_piercing_text'])?$select_result['male_body_piercing_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">H/o blood transfusion</td>
															<td  style="border:1px solid #cdcdcd;">
																
						<?php if(isset($select_result['male_blood_transfusion']) && $select_result['male_blood_transfusion'] == "Yes"){echo 'Yes'; }?> <br>	

							
																<?php echo isset($select_result['male_blood_transfusion_text'])?$select_result['male_blood_transfusion_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">H/o road traffic accident/any injury</td>
															<td  style="border:1px solid #cdcdcd;">
													
																
									<?php if(isset($select_result['male_traffic_accident']) && $select_result['male_traffic_accident'] == "Yes"){echo 'Yes'; }?> <br>							
																<?php echo isset($select_result['male_traffic_accident_text'])?$select_result['male_traffic_accident_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Smoke(past)daily</td>
															<td  style="border:1px solid #cdcdcd;">
													
																
						<?php if(isset($select_result['male_smoke_past']) && $select_result['male_smoke_past'] == "Yes"){echo 'Yes'; }?> <br>											
									<?php echo isset($select_result['male_smoke_past_text'])?$select_result['male_smoke_past_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Smoke(present)daily</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
																
			<?php if(isset($select_result['male_smoke_present']) && $select_result['male_smoke_present'] == "Yes"){echo 'Yes'; }?> <br>													
																<?php echo isset($select_result['male_smoke_present_text'])?$select_result['male_smoke_present_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Drink(past)units per week</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
				<?php if(isset($select_result['male_drink_past']) && $select_result['male_drink_past'] == "Yes"){echo 'Yes'; }?> <br>												
								
																<?php echo isset($select_result['male_drink_past_text'])?$select_result['male_drink_past_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Drink(present)units per week</td>
															<td  style="border:1px solid #cdcdcd;">
																
				<?php if(isset($select_result['male_drink_present']) && $select_result['male_drink_present'] == "Yes"){echo 'Yes'; }?> <br>													
																
																<?php echo isset($select_result['male_drink_present_text'])?$select_result['male_drink_present_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Hashish/cocaine /abusive drugs</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
									<?php if(isset($select_result['male_abusive_drugs']) && $select_result['male_abusive_drugs'] == "Yes"){echo 'Yes'; }?> <br>								
																<?php echo isset($select_result['male_abusive_drugs_text'])?$select_result['male_abusive_drugs_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Have you ever used cortisone/steroid</td>
															<td  style="border:1px solid #cdcdcd;">
														
				<?php if(isset($select_result['male_steroid']) && $select_result['male_steroid'] == "Yes"){echo 'Yes'; }?> <br>													
					<?php echo isset($select_result['male_steroid_text'])?$select_result['male_steroid_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Medication</td>
															<td  style="border:1px solid #cdcdcd;">
													
					<?php if(isset($select_result['male_medication']) && $select_result['male_medication'] == "Yes"){echo 'Yes'; }?> <br>											
								<?php echo isset($select_result['male_medication_text'])?$select_result['male_medication_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Herbal products</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
								<?php if(isset($select_result['male_herbal_products']) && $select_result['male_herbal_products'] == "Yes"){echo 'Yes'; }?> <br>												
																		
																
																<?php echo isset($select_result['male_herbal_products_text'])?$select_result['male_herbal_products_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Eye drops</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
					<?php if(isset($select_result['male_eye_drops']) && $select_result['male_eye_drops'] == "Yes"){echo 'Yes'; }?> <br>												
																
																<?php echo isset($select_result['male_eye_drops_text'])?$select_result['male_eye_drops_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Non prescription drugs used currently other than medications used for this IVF cycle</td>
															<td  style="border:1px solid #cdcdcd;">
																
														
					<?php if(isset($select_result['male_non_prescription_drugs']) && $select_result['male_non_prescription_drugs'] == "Yes"){echo 'Yes'; }?> <br>														
																
																<?php echo isset($select_result['male_non_prescription_drugs_text'])?$select_result['male_non_prescription_drugs_text']:""; ?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">FAMILY HISTORY</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Any family member any problem <br> with anesthesia</td>
															<td colspan="2" style="border:1px solid #cdcdcd;">
																
																
					<?php if(isset($select_result['member_with_anesthesia']) && $select_result['member_with_anesthesia'] == "Yes"){echo 'Yes'; }?> <br>													
																<?php echo isset($select_result['member_with_anesthesia_text'])?$select_result['member_with_anesthesia_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;"></td>
															<td  style="border:1px solid #cdcdcd;">Maternal</td>
															<td  style="border:1px solid #cdcdcd;">Paternal</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Diabetes</td>
															<td  style="border:1px solid #cdcdcd;">
														
				<?php if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] == "Yes"){echo 'Yes'; }?> <br>												
					<?php echo isset($select_result['maternal_diabetes_text'])?$select_result['maternal_diabetes_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">
																
					<?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "Yes"){echo 'Yes'; }?> <br>											
																
																<?php echo isset($select_result['paternal_diabetes_text'])?$select_result['paternal_diabetes_text']:""; ?>  
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Heart/thrombo embolism</td>
															<td  style="border:1px solid #cdcdcd;">
													

	<?php if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] == "Yes"){echo 'Yes'; }?> <br> 				
																
				<?php echo isset($select_result['maternal_thrombo_embolism_text'])?$select_result['maternal_thrombo_embolism_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
						<?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "Yes"){echo 'Yes'; }?> <br> 											
																
				<?php echo isset($select_result['paternal_thrombo_embolism_text'])?$select_result['paternal_thrombo_embolism_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Endocrine/metabolic</td>
															<td  style="border:1px solid #cdcdcd;">
															
			<?php if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] == "Yes"){echo 'Yes'; }?> <br> 		

			<?php echo isset($select_result['maternal_metabolic_text'])?$select_result['maternal_metabolic_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">
																	
			<?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "Yes"){echo 'Yes'; }?> <br> 													
									
																<?php echo isset($select_result['paternal_metabolic_text'])?$select_result['paternal_metabolic_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Urinary tract/renal</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
			<?php if(isset($select_result['maternal_urinary_tract_text']) && $select_result['maternal_urinary_tract_text'] == "Yes"){echo 'Yes'; }?> <br> 													
																
																
																<?php echo isset($select_result['maternal_urinary_tract_text'])?$select_result['maternal_urinary_tract_text']:""; ?>  
															</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
						<?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "Yes"){echo 'Yes'; }?> <br> 										
																
																
																<?php echo isset($select_result['paternal_urinary_tract_text'])?$select_result['paternal_urinary_tract_text']:""; ?>
															
															
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Psychiatric/neurological</td>
															<td  style="border:1px solid #cdcdcd;">
																
			<?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "Yes"){echo 'Yes'; }?> <br> 														
																
	<?php echo isset($select_result['maternal_neurological_text'])?$select_result['maternal_neurological_text']:""; ?>  
	</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
							<?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "Yes"){echo 'Yes'; }?> <br> 										
																
																<?php echo isset($select_result['paternal_neurological_text'])?$select_result['paternal_neurological_text']:""; ?>  
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Other/malignancy</td>
															<td  style="border:1px solid #cdcdcd;">
																
		<?php if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] == "Yes"){echo 'Yes'; }?> <br> 														
																
			<?php echo isset($select_result['maternal_malignancy_text'])?$select_result['maternal_malignancy_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
			<?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "Yes"){echo 'Yes'; }?> <br> 														
																
																<?php echo isset($select_result['paternal_malignancy_text'])?$select_result['paternal_malignancy_text']:""; ?>
															</td>
														</tr>
													</table>
												</td>
												<td  style="border:1px solid #cdcdcd;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Any family member any problem <br> with anesthesia</td>
															<td colspan="2" style="border:1px solid #cdcdcd;">
														
	<?php if(isset($select_result['male_member_with_anesthesia']) && $select_result['male_member_with_anesthesia'] == "Yes"){echo 'Yes'; }?> <br> 				
								
				<?php echo isset($select_result['male_member_with_anesthesia_text'])?$select_result['male_member_with_anesthesia_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;"></td>
															<td  style="border:1px solid #cdcdcd;">Maternal</td>
															<td  style="border:1px solid #cdcdcd;">Paternal</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Diabetes</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
		<?php if(isset($select_result['male_maternal_diabetes']) && $select_result['male_maternal_diabetes'] == "Yes"){echo 'Yes'; }?> <br> 														
			<?php echo isset($select_result['male_maternal_diabetes_text'])?$select_result['male_maternal_diabetes_text']:""; ?>  
															</td>
															<td  style="border:1px solid #cdcdcd;">
												
<?php if(isset($select_result['male_paternal_diabetes']) && $select_result['male_paternal_diabetes'] == "Yes"){echo 'Yes'; }?> <br> 
<?php echo isset($select_result['male_paternal_diabetes_text'])?$select_result['male_paternal_diabetes_text']:""; ?>  
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Heart/thrombo embolism</td>
															<td  style="border:1px solid #cdcdcd;">
															
<?php if(isset($select_result['male_maternal_thrombo_embolism']) && $select_result['male_maternal_thrombo_embolism'] == "Yes"){echo 'Yes'; }?> <br> 
								
		<?php echo isset($select_result['male_maternal_thrombo_embolism_text'])?$select_result['male_maternal_thrombo_embolism_text']:""; ?>  
															</td>
															
															
															
															<td  style="border:1px solid #cdcdcd;">
																
		<?php if(isset($select_result['male_paternal_thrombo_embolism']) && $select_result['male_paternal_thrombo_embolism'] == "Yes"){echo 'Yes'; }?> <br> 														
																
		<?php echo isset($select_result['male_paternal_thrombo_embolism_text'])?$select_result['male_paternal_thrombo_embolism_text']:""; ?>  
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Endocrine/metabolic</td>
															<td  style="border:1px solid #cdcdcd;">
																
	
<?php if(isset($select_result['male_maternal_metabolic']) && $select_result['male_maternal_metabolic'] == "Yes"){echo 'Yes'; }?> <br> 		
																
<?php echo isset($select_result['male_maternal_metabolic_text'])?$select_result['male_maternal_metabolic_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
			
 <?php if(isset($select_result['male_paternal_metabolic']) && $select_result['male_paternal_metabolic'] == "Yes"){echo 'Yes'; }?> <br> 			
																			
																
			<?php echo isset($select_result['male_paternal_metabolic_text'])?$select_result['male_paternal_metabolic_text']:""; ?>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Urinary tract/renal</td>
															<td  style="border:1px solid #cdcdcd;">
																
			

 <?php if(isset($select_result['male_maternal_urinary_tract']) && $select_result['male_maternal_urinary_tract'] == "Yes"){echo 'Yes'; }?> <br> 			
																
			<?php echo isset($select_result['male_maternal_urinary_tract_text'])?$select_result['male_maternal_urinary_tract_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">
															
																
																
				 <?php if(isset($select_result['male_paternal_urinary_tract']) && $select_result['male_paternal_urinary_tract'] == "Yes"){echo 'Yes'; }?> <br> 												
																
																
				<?php echo isset($select_result['male_paternal_urinary_tract_text'])?$select_result['male_paternal_urinary_tract_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Psychiatric/neurological</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
		 <?php if(isset($select_result['male_maternal_neurological']) && $select_result['male_maternal_neurological'] == "Yes"){echo 'Yes'; }?> <br> 															
																
																
		<?php echo isset($select_result['male_maternal_neurological_text'])?$select_result['male_maternal_neurological_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">
																
			 <?php if(isset($select_result['male_paternal_neurological']) && $select_result['male_paternal_neurological'] == "Yes"){echo 'Yes'; }?> <br> 													
																
			<?php echo isset($select_result['male_paternal_neurological_text'])?$select_result['male_paternal_neurological_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Other/malignancy</td>
															<td  style="border:1px solid #cdcdcd;">
																
			 <?php if(isset($select_result['male_maternal_malignancy']) && $select_result['male_maternal_malignancy'] == "Yes"){echo 'Yes'; }?> <br> 														
																
	<?php echo isset($select_result['male_maternal_malignancy_text'])?$select_result['male_maternal_malignancy_text']:""; ?>
															</td>
															<td  style="border:1px solid #cdcdcd;">
																
		 <?php if(isset($select_result['male_paternal_malignancy']) && $select_result['male_paternal_malignancy'] == "Yes"){echo 'Yes'; }?> <br> 																
																
<?php echo isset($select_result['male_paternal_malignancy_text'])?$select_result['male_paternal_malignancy_text']:""; ?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">PREVIOUS HISTORY OF INFERTILITY TREATMENT</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">NO. OF TIMES IUI CYCLE</td>
															<td  style="border:1px solid #cdcdcd;">
																
						 <?php if(isset($select_result['iui_cycle_times']) && $select_result['iui_cycle_times'] == "Yes"){echo 'Yes'; }?> <br> 										
																
					<?php echo isset($select_result['iui_cycle_times_text'])?$select_result['iui_cycle_times_text']:""; ?>  
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">NO. OF TIMES IVF CYCLE</td>
		<td  style="border:1px solid #cdcdcd;">
	 <?php if(isset($select_result['ivf_cycle_times']) && $select_result['ivf_cycle_times'] == "Yes"){echo 'Yes'; }?> <br> 
  	<?php echo isset($select_result['ivf_cycle_times_text'])?$select_result['ivf_cycle_times_text']:""; ?>  
	</td>
														</tr>
													</table>
												</td>
												<td  style="border:1px solid #cdcdcd;"></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">RELATIONSHIP HISTORY</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">SUPPORT FROM HUSBAND:</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['support_from_husband'])?$select_result['support_from_husband']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">SUPPORT FROM IMMEDIATE FAMILY MEMBERS:</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['support_from_family'])?$select_result['support_from_family']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">INTERPERSONAL CONFLICTS BETWEEN COUPLE:</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['conflicts'])?$select_result['conflicts']:""; ?></td>
														</tr>
													</table>
												</td>
												<td  style="border:1px solid #cdcdcd;"></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">PHYSICAL OR SEXUAL ABUSE HISTORY</th>
												<td  style="border:1px solid #cdcdcd;">
													
		
<?php if(isset($select_result['sexual_abuse']) && $select_result['sexual_abuse'] == "Yes"){echo 'Yes'; }?> <br> 
		
													
	<?php echo isset($select_result['sexual_abuse_text'])?$select_result['sexual_abuse_text']:""; ?>
												</td>
												<td  style="border:1px solid #cdcdcd;"></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">LEGAL PROBLEM HISTORY</th>
												<td  style="border:1px solid #cdcdcd;">
												
				<?php if(isset($select_result['legal_problem']) && $select_result['legal_problem'] == "Yes"){echo 'Yes'; }?> <br> 									
	<?php echo isset($select_result['legal_problem_text'])?$select_result['legal_problem_text']:""; ?>
		</td>
												
												
												
												<td  style="border:1px solid #cdcdcd;"></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">MENTAL HISTORY</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">EXPECTATION LEVEL FOR CONCEIVING ON A SCALE FROM (1-10) 1 BEING LEAST 10 BEING MOST</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['expectation'])?$select_result['expectation']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">BOUTS OF DEPRESSION</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['depression'])?$select_result['depression']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">BOUTS OF ANXIETY</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['anxiety'])?$select_result['anxiety']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">MOOD AND AFFECT</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['affect'])?$select_result['affect']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">SUCIDAL TENDENCIES</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['sucidal'])?$select_result['sucidal']:""; ?></td>
														</tr>
													</table>
												</td>
												<td  style="border:1px solid #cdcdcd;"></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">GENERAL EXAMINATION</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Nutritional assessment</td>
															<td  style="border:1px solid #cdcdcd;">
															
														
															
		<?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "Obese"){echo 'Obese'; }?>													
															
		<?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "average built"){echo 'average built'; }?>	
<?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "thin"){echo 'thin'; }?>	
<?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "cachexic"){echo 'cachexic'; }?>			
															
															
															
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Psychological assessment</td>
															<td  style="border:1px solid #cdcdcd;">
			
			<?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "Anxious"){echo 'Anxious'; }?>
			<?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "combative"){echo 'combative'; }?>
			<?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "depressed"){echo 'depressed'; }?>
			<?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "normal"){echo 'normal'; }?>
			
				</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Pulse (PER MIN)</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pulse'])?$select_result['pulse']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Blood pressure (MM HG)</td>
															<td  style="border:1px solid #cdcdcd;">
																<table width="100%">
																	<tr>
																		<td  style="border:1px solid #cdcdcd;">SYSTOLIC</td>
																		<td  style="border:1px solid #cdcdcd;">
																			<?php echo isset($select_result['systolic'])?$select_result['systolic']:""; ?>
																		</td>
																	</tr>
																	<tr>
																		<td  style="border:1px solid #cdcdcd;">DIASTOLIC</td>
																		<td  style="border:1px solid #cdcdcd;">
																		<?php echo isset($select_result['diastolic'])?$select_result['diastolic']:""; ?>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>														
														<tr>
															<td  style="border:1px solid #cdcdcd;">Temperature(DEG F):</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['temperature'])?$select_result['temperature']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">CVS</td>
															<td  style="border:1px solid #cdcdcd;">
												
			<?php if(isset($select_result['cvs']) && $select_result['cvs'] == "Yes"){echo 'Yes'; }?><br>		
			<?php echo isset($select_result['cvs_text'])?$select_result['cvs_text']:""; ?>
			</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Chest</td>
															<td  style="border:1px solid #cdcdcd;">
																
																
																
	<?php if(isset($select_result['chest']) && $select_result['chest'] == "Yes"){echo 'Yes'; }?><br>														
	<?php echo isset($select_result['chest_text'])?$select_result['chest_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Abdomen</td>
															<td  style="border:1px solid #cdcdcd;">
																
									

<?php if(isset($select_result['abdomen']) && $select_result['abdomen'] == "Yes"){echo 'Yes'; }?><br>  									
																
	<?php echo isset($select_result['abdomen_text'])?$select_result['abdomen_text']:""; ?>
															</td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Others</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['others2'])?$select_result['others2']:""; ?></td>
														</tr>
													</table>
												</td>
												<td  style="border:1px solid #cdcdcd;"></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">MENTAL STATUS EXAMINATION</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">Appearance</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['appearance'])?$select_result['appearance']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">self-care (diet, exercise, sleep)</td>
															<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['self_care'])?$select_result['self_care']:""; ?></td>
														</tr>
														<tr>
						<td  style="border:1px solid #cdcdcd;">General Behaviour</td>
					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['general_behaviour'])?$select_result['general_behaviour']:""; ?> </td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Level of Consciousness</td>
	<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['consciousness'])?$select_result['consciousness']:""; ?></td>
														</tr>
														<tr>
															<td  style="border:1px solid #cdcdcd;">Orientation</td>
		<td  style="border:1px solid #cdcdcd;">   <?php echo isset($select_result['orientation'])?$select_result['orientation']:""; ?></td>
														</tr>
													</table>
												</td>
												<td  style="border:1px solid #cdcdcd;"></td>
											</tr>
											<tr>
												<th  style="border:1px solid #cdcdcd;">MANAGEMENT ADVISED</th>
												<th  style="border:1px solid #cdcdcd; padding: 0;">
													<table width="100%">
														<tr>
															<td  style="border:1px solid #cdcdcd;">PSYCHOLOGICAL COUNSELLING</td>
															<td  style="border:1px solid #cdcdcd;">
															
	<?php if(isset($select_result['psychological_counselling']) && $select_result['psychological_counselling'] == "Yes"){echo 'Yes'; }?>													

														</td>
														</tr>
														<tr>
		<td  style="border:1px solid #cdcdcd;">COGNITIVE THERAPY</td>
		<td  style="border:1px solid #cdcdcd;">
																
		<?php if(isset($select_result['cognitive_therapy']) && $select_result['cognitive_therapy'] == "Yes"){echo 'Yes'; }?> 	</td>
														</tr>
<tr>
<td  style="border:1px solid #cdcdcd;">PSYCHIATRIST REFERRAL FOR MEDICATIONS</td>
<td  style="border:1px solid #cdcdcd;">
<?php if(isset($select_result['psychiatrist_referral']) && $select_result['psychiatrist_referral'] == "Yes"){echo 'Yes'; }?> 
</td>
</tr>
	

	</table>
</td>
<td  style="border:1px solid #cdcdcd;"></td>
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