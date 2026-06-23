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
			
			$select_query = "SELECT * FROM `surrogate_mother_personal_details` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
			$select_result = run_select_query($select_query); 
			if(empty($select_result)){
				// mysql query to insert data
				$query = "INSERT INTO `surrogate_mother_personal_details` SET ";
				$sqlArr = array();
				foreach( $_POST as $key=> $value )
				{
				  $sqlArr[] = " $key = '".addslashes($value)."'";
				}		
				$query .= implode(',' , $sqlArr);
			}else{
				// mysql query to update data
				$query = "UPDATE surrogate_mother_personal_details SET ";
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
		$select_query = "SELECT * FROM `surrogate_mother_personal_details` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
			<td><input  type="text" value="<?php echo isset($select_result['female_art_bank'])?$select_result['female_art_bank']:""; ?>"    name="female_art_bank" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_art_bank'])?$select_result['male_art_bank']:""; ?>"    name="male_art_bank" class="form-control"></td>
		</tr>
		<tr>
			<th>ID NUMBER</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_id_number'])?$select_result['female_id_number']:""; ?>"    name="female_id_number" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_id_number'])?$select_result['male_id_number']:""; ?>"    name="male_id_number" class="form-control"></td>
		</tr>
		<tr>
			<th>PHOTOGRAPHS</th>
			<td><input type="file" name="female_photographs" class="form-control">
			<a target="_blank" href="<?php echo !empty($select_result['female_photographs'])?$select_result['female_photographs']:"javascript:void(0)"; ?>">Download</a>
			<br>
			
			 <?php 
		@		$female_photographs=$select_result['female_photographs'];
		@		$male_photographs=$select_result['male_photographs'];
				
					?>
			
			<?php if(!empty($female_photographs)) {?>
				 <img src="<?php echo $female_photographs;?>" style="width:100px; height:100px;">
					<?php } else {echo " ";}?>
			
			
			</td>
			<td><input type="file" name="male_photographs" class="form-control">
			<a target="_blank" href="<?php echo !empty($select_result['male_photographs'])?$select_result['male_photographs']:"javascript:void(0)"; ?>">Download</a>
			<br>
			<?php if(!empty($male_photographs)) {?>
				 <img src="<?php echo $male_photographs;?>" style="width:100px; height:100px;">
					<?php } else {echo " ";}?>
			
			</td>
		</tr>
		<tr>
			<th>NAME</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_name'])?$select_result['female_name']:""; ?>"    name="female_name" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_name'])?$select_result['male_name']:""; ?>"    name="male_name" class="form-control"></td>
		</tr>
		<tr>
			<th>DOB</th>
			<td><input  type="date" value="<?php echo isset($select_result['female_dob'])?$select_result['female_dob']:""; ?>"    name="female_dob" class="form-control"></td>
			<td><input  type="date" value="<?php echo isset($select_result['male_dob'])?$select_result['male_dob']:""; ?>"    name="male_dob" class="form-control"></td>
		</tr>
		<tr>
			<th>AGE</th>
			<td><input  type="number" value="<?php echo isset($select_result['female_age'])?$select_result['female_age']:""; ?>"    min="1" max="100" name="female_age" class="form-control"></td>
			<td><input  type="number" value="<?php echo isset($select_result['male_age'])?$select_result['male_age']:""; ?>"    min="1" max="100" name="male_age" class="form-control"></td>
		</tr>
		<tr>
			<th>NATIONALITY</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_nationality'])?$select_result['female_nationality']:""; ?>"    name="female_nationality" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_nationality'])?$select_result['male_nationality']:""; ?>"    name="male_nationality" class="form-control"></td>
		</tr>
		<tr>
			<th>MOBILE NO</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_mobile'])?$select_result['female_mobile']:""; ?>"    name="female_mobile" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_mobile'])?$select_result['male_mobile']:""; ?>"    name="male_mobile" class="form-control"></td>
		</tr>
		<tr>
			<th>EMAIL ADDRESS</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_email'])?$select_result['female_email']:""; ?>"    name="female_email" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_email'])?$select_result['male_email']:""; ?>"    name="male_email" class="form-control"></td>
		</tr>
		<tr>
			<th>PRESENT ADDRESS</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_present_address'])?$select_result['female_present_address']:""; ?>"    name="female_present_address" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_present_address'])?$select_result['male_present_address']:""; ?>"    name="male_present_address" class="form-control"></td>
		</tr>
		<tr>
			<th>PERMANENT ADDRESS</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_permanent_address'])?$select_result['female_permanent_address']:""; ?>"    name="female_permanent_address" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_permanent_address'])?$select_result['male_permanent_address']:""; ?>"    name="male_permanent_address" class="form-control"></td>
		</tr>
		<tr>
			<th>AADHAR NUMBER</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_aadhar_number'])?$select_result['female_aadhar_number']:""; ?>"    name="female_aadhar_number" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_aadhar_number'])?$select_result['male_aadhar_number']:""; ?>"    name="male_aadhar_number" class="form-control"></td>
		</tr>
		<tr>
			<th>PAN CARD NUMBER</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_pancard_number'])?$select_result['female_pancard_number']:""; ?>"    name="female_pancard_number" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_pancard_number'])?$select_result['male_pancard_number']:""; ?>"    name="male_pancard_number" class="form-control"></td>
		</tr>
		<tr>
			<th>RELIGION</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_religion'])?$select_result['female_religion']:""; ?>"    name="female_religion" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_religion'])?$select_result['male_religion']:""; ?>"    name="male_religion" class="form-control"></td>
		</tr>
		<tr>
			<th>MONTHLY INCOME</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_monthly_income'])?$select_result['female_monthly_income']:""; ?>"    name="female_monthly_income" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_monthly_income'])?$select_result['male_monthly_income']:""; ?>"    name="male_monthly_income" class="form-control"></td>
		</tr>
		<tr>
			<th><br></th>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<th>EDUCATION</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_education'])?$select_result['female_education']:""; ?>"    name="female_education" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_education'])?$select_result['male_education']:""; ?>"    name="male_education" class="form-control"></td>
		</tr>
		<tr>
			<th>OCCUPATION</th>
			<td><input  type="text" value="<?php echo isset($select_result['female_occupation'])?$select_result['female_occupation']:""; ?>"    name="female_occupation" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['male_occupation'])?$select_result['male_occupation']:""; ?>"    name="male_occupation" class="form-control"></td>
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
						<td><input  type="number" value="<?php echo isset($select_result['female_pregnancy'])?$select_result['female_pregnancy']:""; ?>"    min="0" max="20" name="female_pregnancy" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of live births</td>
						<td><input  type="number" value="<?php echo isset($select_result['female_live_births'])?$select_result['female_live_births']:""; ?>"    min="0" max="20" name="female_live_births" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of spontaneous abortions in first trimester</td>
						<td><input  type="number" value="<?php echo isset($select_result['female_spontaneous_abortion'])?$select_result['female_spontaneous_abortion']:""; ?>"    min="0" max="20" name="female_spontaneous_abortion" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of termination of pregnancy</td>
						<td><input  type="number" value="<?php echo isset($select_result['female_terminated_pregnancy'])?$select_result['female_terminated_pregnancy']:""; ?>"    min="0" max="20" name="female_terminated_pregnancy" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of still births</td>
						<td><input  type="number" value="<?php echo isset($select_result['female_still_births'])?$select_result['female_still_births']:""; ?>"    min="0" max="20" name="female_still_births" class="form-control"></td>
					</tr>
					<tr>
						<td>No. of ectopic pregnancy</td>
						<td><input  type="number" value="<?php echo isset($select_result['female_ectopic_pregnancy'])?$select_result['female_ectopic_pregnancy']:""; ?>"    min="0" max="20" name="female_ectopic_pregnancy" class="form-control"></td>
					</tr>
					<tr>
						<td>History of any abnormality in child</td>
						<td><input  type="text" value="<?php echo isset($select_result['female_abnormility_of_child'])?$select_result['female_abnormility_of_child']:""; ?>"    name="female_abnormility_of_child" class="form-control"></td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input  type="text" value="<?php echo isset($select_result['female_others'])?$select_result['female_others']:""; ?>"    name="female_others" class="form-control"></td>
					</tr>
				</table>
			</td>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Total pregnancies</td>
						<td><input  type="number" value="<?php echo isset($select_result['male_pregnancy'])?$select_result['male_pregnancy']:""; ?>"    min="0" max="20" name="male_pregnancy" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of live births</td>
						<td><input  type="number" value="<?php echo isset($select_result['male_live_births'])?$select_result['male_live_births']:""; ?>"    min="0" max="20" name="male_live_births" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of spontaneous abortions in first trimester</td>
						<td><input  type="number" value="<?php echo isset($select_result['male_spontaneous_abortion'])?$select_result['male_spontaneous_abortion']:""; ?>"    min="0" max="20" name="male_spontaneous_abortion" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of termination of pregnancy</td>
						<td><input  type="number" value="<?php echo isset($select_result['male_terminated_pregnancy'])?$select_result['male_terminated_pregnancy']:""; ?>"    min="0" max="20" name="male_terminated_pregnancy" class="form-control"></td>
					</tr>
					<tr>
						<td>No.of still births</td>
						<td><input  type="number" value="<?php echo isset($select_result['male_still_births'])?$select_result['male_still_births']:""; ?>"    min="0" max="20" name="male_still_births" class="form-control"></td>
					</tr>
					<tr>
						<td>No. of ectopic pregnancy</td>
						<td><input  type="number" value="<?php echo isset($select_result['male_ectopic_pregnancy'])?$select_result['male_ectopic_pregnancy']:""; ?>"    min="0" max="20" name="male_ectopic_pregnancy" class="form-control"></td>
					</tr>
					<tr>
						<td>History of any abnormality in child</td>
						<td><input  type="text" value="<?php echo isset($select_result['male_abnormility_of_child'])?$select_result['male_abnormility_of_child']:""; ?>"    name="male_abnormility_of_child" class="form-control"></td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input  type="text" value="<?php echo isset($select_result['male_others'])?$select_result['male_others']:""; ?>"    name="male_others" class="form-control"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>MARITAL LIFE IN (PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Years of marriage</td>
						<td><input  type="text" value="<?php echo isset($select_result['female_years_of_marriage'])?$select_result['female_years_of_marriage']:""; ?>"    name="female_years_of_marriage" class="form-control"></td>
					</tr>
					<tr>
						<td>Use of contraception</td>
						<td><input  type="text" value="<?php echo isset($select_result['female_contraception'])?$select_result['female_contraception']:""; ?>"    name="female_contraception" class="form-control"></td>
					</tr>
				</table>
			</td>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Years of marriage</td>
						<td><input  type="text" value="<?php echo isset($select_result['male_years_of_marriage'])?$select_result['male_years_of_marriage']:""; ?>"    name="male_years_of_marriage" class="form-control"></td>
					</tr>
					<tr>
						<td>Use of contraception</td>
						<td><input  type="text" value="<?php echo isset($select_result['male_contraception'])?$select_result['male_contraception']:""; ?>"    name="male_contraception" class="form-control"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>PAST GYNECOLOGICAL HISTORY</th>
			<td>
				<table width="100%">
					<tr>
						<td>Dysmenorrhea</td>
						<td><input  type="text" value="<?php echo isset($select_result['dysmenorrhea'])?$select_result['dysmenorrhea']:""; ?>"    name="dysmenorrhea" class="form-control"></td>
					</tr>
					<tr>
						<td>Menorrhagia</td>
						<td><input  type="text" value="<?php echo isset($select_result['menorrhagia'])?$select_result['menorrhagia']:""; ?>"    name="menorrhagia" class="form-control"></td>
					</tr>
					<tr>
						<td>H/o D and c</td>
						<td><input  type="text" value="<?php echo isset($select_result['d_and_c'])?$select_result['d_and_c']:""; ?>"    name="d_and_c" class="form-control"></td>
					</tr>
					<tr>
						<td>Dyspareunia</td>
						<td><input  type="text" value="<?php echo isset($select_result['dyspareunia'])?$select_result['dyspareunia']:""; ?>"    name="dyspareunia" class="form-control"></td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input  type="text" value="<?php echo isset($select_result['past_others'])?$select_result['past_others']:""; ?>"    name="past_others" class="form-control"></td>
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
						<td><input  type="number" value="<?php echo isset($select_result['age_at_menache'])?$select_result['age_at_menache']:""; ?>"    min="0" max="100" name="age_at_menache" class="form-control"></td>
					</tr>
					<tr>
						<td>Flow- heavy/average/less</td>
						<td><input  type="text" value="<?php echo isset($select_result['flow'])?$select_result['flow']:""; ?>"    name="flow" class="form-control"></td>
					</tr>
					<tr>
						<td>Frequency- regular /irregular</td>
						<td><input  type="text" value="<?php echo isset($select_result['frequency'])?$select_result['frequency']:""; ?>"    name="frequency" class="form-control"></td>
					</tr>
					<tr>
						<td>Days</td>
						<td><input  type="text" value="<?php echo isset($select_result['days'])?$select_result['days']:""; ?>"    name="days" class="form-control"></td>
					</tr>
					<tr>
						<td>Hirsutism</td>
						<td><input  type="text" value="<?php echo isset($select_result['hirsutism'])?$select_result['hirsutism']:""; ?>"    name="hirsutism" class="form-control"></td>
					</tr>
					<tr>
						<td>Galactorrhea</td>
						<td><input  type="text" value="<?php echo isset($select_result['galactorrea'])?$select_result['galactorrea']:""; ?>"    name="galactorrea" class="form-control"></td>
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
							<input  type="text" value="<?php echo isset($select_result['heart_attack_text'])?$select_result['heart_attack_text']:""; ?>"    maxlength="20" name="heart_attack_text">
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
							<input  type="text" value="<?php echo isset($select_result['pacemaker_text'])?$select_result['pacemaker_text']:""; ?>"    maxlength="20" name="pacemaker_text">
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
							<input  type="text" value="<?php echo isset($select_result['other_heart_disease_text'])?$select_result['other_heart_disease_text']:""; ?>"    maxlength="20" name="other_heart_disease_text">
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
							<input  type="text" value="<?php echo isset($select_result['high_blood_pressure_text'])?$select_result['high_blood_pressure_text']:""; ?>"    maxlength="20" name="high_blood_pressure_text">
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
							<input  type="text" value="<?php echo isset($select_result['blood_clots_text'])?$select_result['blood_clots_text']:""; ?>"    maxlength="20" name="blood_clots_text">
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
							<input  type="text" value="<?php echo isset($select_result['chest_pain_text'])?$select_result['chest_pain_text']:""; ?>"    maxlength="20" name="chest_pain_text">
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
							<input  type="text" value="<?php echo isset($select_result['stroke_text'])?$select_result['stroke_text']:""; ?>"    maxlength="20" name="stroke_text">
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
							<input  type="text" value="<?php echo isset($select_result['asthma_text'])?$select_result['asthma_text']:""; ?>"    maxlength="20" name="asthma_text">
						</td>
						<td>Asthma</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="other_lung_disease"   value="Yes"  <?php if(isset($select_result['other_lung_disease']) && $select_result['other_lung_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="other_lung_disease"   value="No"  <?php if(isset($select_result['other_lung_disease']) && $select_result['other_lung_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['other_lung_disease']) && $select_result['other_lung_disease'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['lung_disease_text'])?$select_result['lung_disease_text']:""; ?>"    maxlength="20" name="lung_disease_text">
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
							<input  type="text" value="<?php echo isset($select_result['difficulty_breathing_text'])?$select_result['difficulty_breathing_text']:""; ?>"    maxlength="20" name="difficulty_breathing_text">
						</td>
						<td>Difficulty breathing</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="sleep_apnea"   value="Yes"  <?php if(isset($select_result['sleep_apnea']) && $select_result['sleep_apnea'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="sleep_apnea"   value="No"  <?php if(isset($select_result['sleep_apnea']) && $select_result['sleep_apnea'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['sleep_apnea']) && $select_result['sleep_apnea'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['snoring_text'])?$select_result['snoring_text']:""; ?>"    maxlength="20" name="snoring_text">
						</td>
						<td>Sleep apnea or snoring</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="seizures"   value="Yes"  <?php if(isset($select_result['seizures']) && $select_result['seizures'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="seizures"   value="No"  <?php if(isset($select_result['seizures']) && $select_result['seizures'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['seizures']) && $select_result['seizures'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['epilepsy_text'])?$select_result['epilepsy_text']:""; ?>"    maxlength="20" name="epilepsy_text">
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
							<input  type="text" value="<?php echo isset($select_result['fainting_spells_text'])?$select_result['fainting_spells_text']:""; ?>"    maxlength="20" name="fainting_spells_text">
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
							<input  type="text" value="<?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>"    maxlength="20" name="diabetes_text">
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
							<input  type="text" value="<?php echo isset($select_result['muscle_disorders_text'])?$select_result['muscle_disorders_text']:""; ?>"    name="muscle_disorders_text">
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
							<input  type="text" value="<?php echo isset($select_result['kidney_disease_text'])?$select_result['kidney_disease_text']:""; ?>"    maxlength="20" name="kidney_disease_text">
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
							<input  type="text" value="<?php echo isset($select_result['hepatitis_text'])?$select_result['hepatitis_text']:""; ?>"    maxlength="20" name="hepatitis_text">
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
							<input  type="text" value="<?php echo isset($select_result['tuberculosis_text'])?$select_result['tuberculosis_text']:""; ?>"    maxlength="20" name="tuberculosis_text">
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
							<input  type="text" value="<?php echo isset($select_result['hiv_text'])?$select_result['hiv_text']:""; ?>"    maxlength="20" name="hiv_text">
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
							<input  type="text" value="<?php echo isset($select_result['heart_burn_text'])?$select_result['heart_burn_text']:""; ?>"    maxlength="20" name="heart_burn_text">
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
							<input  type="text" value="<?php echo isset($select_result['cancer_text'])?$select_result['cancer_text']:""; ?>"    maxlength="20" name="cancer_text">
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
							<input  type="text" value="<?php echo isset($select_result['blood_disorders_text'])?$select_result['blood_disorders_text']:""; ?>"    maxlength="20" name="blood_disorders_text">
						</td>
						<td>Blood disorders</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="psychiatric_disease"   value="Yes"  <?php if(isset($select_result['psychiatric_disease']) && $select_result['psychiatric_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="psychiatric_disease"   value="No"  <?php if(isset($select_result['psychiatric_disease']) && $select_result['psychiatric_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['psychiatric_disease']) && $select_result['psychiatric_disease'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['rheumatic_disease_text'])?$select_result['rheumatic_disease_text']:""; ?>"    maxlength="20" name="rheumatic_disease_text">
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
							<input  type="text" value="<?php echo isset($select_result['psychiatric_disorder_text'])?$select_result['psychiatric_disorder_text']:""; ?>"    maxlength="20" name="psychiatric_disorder_text">
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
							<input  type="text" value="<?php echo isset($select_result['thyroid_disorder_text'])?$select_result['thyroid_disorder_text']:""; ?>"    maxlength="20" name="thyroid_disorder_text">
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
							<input  type="text" value="<?php echo isset($select_result['urinary_infection_text'])?$select_result['urinary_infection_text']:""; ?>"    maxlength="20" name="urinary_infection_text">
						</td>
						<td>Urinary infection</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="sexually_transmitted_disease"   value="Yes"  <?php if(isset($select_result['sexually_transmitted_disease']) && $select_result['sexually_transmitted_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="sexually_transmitted_disease"   value="No"  <?php if(isset($select_result['sexually_transmitted_disease']) && $select_result['sexually_transmitted_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['sexually_transmitted_disease']) && $select_result['sexually_transmitted_disease'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['sexually_transmitted_text'])?$select_result['sexually_transmitted_text']:""; ?>"    maxlength="20" name="sexually_transmitted_text">
						</td>
						<td>Sexually transmitted disease</td>
					</tr>
					<tr>
						<td><input  type="text" value="<?php echo isset($select_result['impairments'])?$select_result['impairments']:""; ?>"    class="form-control" name="impairments"></td>
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
							<input type="radio"  id="text1" name="operations"   value="Yes"  <?php if(isset($select_result['operations']) && $select_result['operations'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="operations"   value="No"  <?php if(isset($select_result['operations']) && $select_result['operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['operations']) && $select_result['operations'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['operations_text'])?$select_result['operations_text']:""; ?>"    maxlength="20" name="operations_text">
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
							<input  type="text" value="<?php echo isset($select_result['other_operations_text'])?$select_result['other_operations_text']:""; ?>"    maxlength="20" name="other_operations_text">
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
							<input  type="text" value="<?php echo isset($select_result['medications_text'])?$select_result['medications_text']:""; ?>"    maxlength="20" name="medications_text">
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
							<input  type="text" value="<?php echo isset($select_result['environmental_factors_text'])?$select_result['environmental_factors_text']:""; ?>"    maxlength="20" name="environmental_factors_text">
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
							<input  type="text" value="<?php echo isset($select_result['dentures_text'])?$select_result['dentures_text']:""; ?>"    maxlength="20" name="dentures_text">
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
							<input  type="text" value="<?php echo isset($select_result['loose_teeth_text'])?$select_result['loose_teeth_text']:""; ?>"    maxlength="20" name="loose_teeth_text">
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
							<input  type="text" value="<?php echo isset($select_result['hearing_aid_text'])?$select_result['hearing_aid_text']:""; ?>"    maxlength="20" name="hearing_aid_text">
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
							<input  type="text" value="<?php echo isset($select_result['caps_on_front_teeth_text'])?$select_result['caps_on_front_teeth_text']:""; ?>"    maxlength="20" name="caps_on_front_teeth_text">
						</td>
						<td>Caps on front teeth</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="conatact_lenses"   value="Yes"  <?php if(isset($select_result['conatact_lenses']) && $select_result['conatact_lenses'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="conatact_lenses"   value="No"  <?php if(isset($select_result['conatact_lenses']) && $select_result['conatact_lenses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['conatact_lenses']) && $select_result['conatact_lenses'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['contact_lenses_text'])?$select_result['contact_lenses_text']:""; ?>"    maxlength="20" name="contact_lenses_text">
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
							<input  type="text" value="<?php echo isset($select_result['body_piercing_text'])?$select_result['body_piercing_text']:""; ?>"    maxlength="20" name="body_piercing_text">
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
							<input  type="text" value="<?php echo isset($select_result['blood_transfusion_text'])?$select_result['blood_transfusion_text']:""; ?>"    maxlength="20" name="blood_transfusion_text">
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
							<input  type="text" value="<?php echo isset($select_result['traffic_accident_text'])?$select_result['traffic_accident_text']:""; ?>"    maxlength="20" name="traffic_accident_text">
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
							<input  type="text" value="<?php echo isset($select_result['smoke_past_text'])?$select_result['smoke_past_text']:""; ?>"    maxlength="20" name="smoke_past_text">
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
							<input  type="text" value="<?php echo isset($select_result['smoke_present_text'])?$select_result['smoke_present_text']:""; ?>"    maxlength="20" name="smoke_present_text">
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
							<input  type="text" value="<?php echo isset($select_result['drink_past_text'])?$select_result['drink_past_text']:""; ?>"    maxlength="20" name="drink_past_text">
						</td>
						<td>Drink(past)units per week</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="drnk_present"   value="Yes"  <?php if(isset($select_result['drnk_present']) && $select_result['drnk_present'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="drnk_present"   value="No"  <?php if(isset($select_result['drnk_present']) && $select_result['drnk_present'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['drnk_present']) && $select_result['drnk_present'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['drink_present_text'])?$select_result['drink_present_text']:""; ?>"    maxlength="20" name="drink_present_text">
						</td>
						<td>Drink(present)units per week</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="drugs"   value="Yes"  <?php if(isset($select_result['drugs']) && $select_result['drugs'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="drugs"   value="No"  <?php if(isset($select_result['drugs']) && $select_result['drugs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['drugs']) && $select_result['drugs'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['drugs_text'])?$select_result['drugs_text']:""; ?>"    maxlength="20" name="drugs_text">
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
							<input  type="text" value="<?php echo isset($select_result['steroid_text'])?$select_result['steroid_text']:""; ?>"    maxlength="20" name="steroid_text">
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
							<input  type="text" value="<?php echo isset($select_result['medication_text'])?$select_result['medication_text']:""; ?>"    maxlength="20" name="medication_text">
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
							<input  type="text" value="<?php echo isset($select_result['herbal_products_text'])?$select_result['herbal_products_text']:""; ?>"    maxlength="20" name="herbal_products_text">
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
							<input  type="text" value="<?php echo isset($select_result['eye_drops_text'])?$select_result['eye_drops_text']:""; ?>"    maxlength="20" name="eye_drops_text">
						</td>
						<td>Eye drops</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  id="text1" name="nonprescription_drugs"   value="Yes"  <?php if(isset($select_result['nonprescription_drugs']) && $select_result['nonprescription_drugs'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  id="text1" name="nonprescription_drugs"   value="No"  <?php if(isset($select_result['nonprescription_drugs']) && $select_result['nonprescription_drugs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['nonprescription_drugs']) && $select_result['nonprescription_drugs'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['nonprescription_drugs_text'])?$select_result['nonprescription_drugs_text']:""; ?>"    maxlength="20" name="nonprescription_drugs_text">
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
							<input  type="text" value="<?php echo isset($select_result['maternal_diabetes_text'])?$select_result['maternal_diabetes_text']:""; ?>"    maxlength="20" name="maternal_diabetes_text">
						</td>
						<td>
							<input type="radio"  name="paternal_diabetes"   value="Yes"  <?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_diabetes"   value="No"  <?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['paternal_diabetes_text'])?$select_result['paternal_diabetes_text']:""; ?>"    maxlength="20" name="paternal_diabetes_text">
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
							<input  type="text" value="<?php echo isset($select_result['maternal_thrombo_embolism_text'])?$select_result['maternal_thrombo_embolism_text']:""; ?>"    maxlength="20" name="maternal_thrombo_embolism_text">
						</td>
						<td>
							<input type="radio"  name="paternal_thrombo_embolism"   value="Yes"  <?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_thrombo_embolism"   value="No"  <?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['paternal_thrombo_embolism_text'])?$select_result['paternal_thrombo_embolism_text']:""; ?>"    maxlength="20" name="paternal_thrombo_embolism_text">
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
							<input  type="text" value="<?php echo isset($select_result['maternal_metabolic_text'])?$select_result['maternal_metabolic_text']:""; ?>"    maxlength="20" name="maternal_metabolic_text">
						</td>
						<td>
							<input type="radio"  name="paternal_metabolic"   value="Yes"  <?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_metabolic"   value="No"  <?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['paternal_metabolic_text'])?$select_result['paternal_metabolic_text']:""; ?>"    maxlength="20" name="paternal_metabolic_text">
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
							<input  type="text" value="<?php echo isset($select_result['maternal_urinary_tract_text'])?$select_result['maternal_urinary_tract_text']:""; ?>"    maxlength="20" name="maternal_urinary_tract_text">
						</td>
						<td>
							<input type="radio"  name="paternal_urinary_tract"   value="Yes"  <?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_urinary_tract"   value="No"  <?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['paternal_urinary_tract_text'])?$select_result['paternal_urinary_tract_text']:""; ?>"    maxlength="20" name="paternal_urinary_tract_text">
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
							<input  type="text" value="<?php echo isset($select_result['maternal_neurological_text'])?$select_result['maternal_neurological_text']:""; ?>"    maxlength="20" name="maternal_neurological_text">
						</td>
						<td>
							<input type="radio"  name="paternal_neurological"   value="Yes"  <?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_neurological"   value="No"  <?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['paternal_neurological_text'])?$select_result['paternal_neurological_text']:""; ?>"    maxlength="20" name="paternal_neurological_text">
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
							<input  type="text" value="<?php echo isset($select_result['maternal_malignancy_text'])?$select_result['maternal_malignancy_text']:""; ?>"    maxlength="20" name="maternal_malignancy_text">
						</td>
						<td>
							<input type="radio"  name="paternal_malignancy"   value="Yes"  <?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="paternal_malignancy"   value="No"  <?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
							<input  type="text" value="<?php echo isset($select_result['paternal_malignancy_text'])?$select_result['paternal_malignancy_text']:""; ?>"    maxlength="20" name="paternal_malignancy_text">
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
						<td>NO. OF TIMES EGG DONATED COMPLICATIONS</td>
						<td><input  type="number" value="<?php echo isset($select_result['egg_donated'])?$select_result['egg_donated']:""; ?>"    min="0" name="egg_donated" class="form-control"></td>
					</tr>
					<tr>
						<td>COMPLICATIONS</td>
						<td><input  type="text" value="<?php echo isset($select_result['did_surrogacy_complications'])?$select_result['did_surrogacy_complications']:""; ?>"    maxlength="50" name="did_surrogacy_complications" class="form-control"></td>
					</tr>
					<tr>
						<td>NO. OF TIMES EGG DONATED COMPLICATIONS</td>
						<td><input  type="number" value="<?php echo isset($select_result['egg_donated_1'])?$select_result['egg_donated_1']:""; ?>"    min="0" name="egg_donated_1" class="form-control"></td>
					</tr>
					<tr>
						<td>COMPLICATIONS</td>
						<td><input  type="text" value="<?php echo isset($select_result['egg_donated_complications_1'])?$select_result['egg_donated_complications_1']:""; ?>"    maxlength="50" name="egg_donated_complications_1" class="form-control"></td>
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
							<input type="radio" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "Obese"){echo 'checked="checked"'; }?>  name="nutritional_assessment" value="Obese">
							<label>Obese</label>
							<input type="radio" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "average built"){echo 'checked="checked"'; }?> name="nutritional_assessment" value="average built">
							<label>average built</label>
							<input type="radio" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "thin"){echo 'checked="checked"'; }?> name="nutritional_assessment" value="thin">
							<label>thin</label>
							<input type="radio" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "cachexic"){echo 'checked="checked"'; }?> name="nutritional_assessment" value="cachexic">
							<label>cachexic</label>
						</td>
					</tr>
					<tr>
						<td>Psychological assessment</td>
						<td>
							<input type="radio" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "Anxious"){echo 'checked="checked"'; }?> name="psychological_assessment" value="Anxious">
							<label>Anxious</label>
							<input type="radio" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "combative"){echo 'checked="checked"'; }?> name="psychological_assessment" value="combative">
							<label>combative</label>
							<input type="radio" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "depressed"){echo 'checked="checked"'; }?> name="psychological_assessment" value="depressed">
							<label>depressed</label>
							<input type="radio" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "normal"){echo 'checked="checked"'; }?> name="psychological_assessment" value="normal">
							<label>normal</label>
						</td>
					</tr>
					<tr>
						<td>Blood pressure (MM HG)</td>
						<td style="padding: 0;">
							<table width="100%">
								<tr>
									<td>SYSTOLIC</td>
									<td><input  type="number" value="<?php echo isset($select_result['systolic'])?$select_result['systolic']:""; ?>"    min="0" name="systolic" class="form-control"></td>
								</tr>
								<tr>
									<td>DIASTOLIC</td>
									<td><input  type="number" value="<?php echo isset($select_result['diastolic'])?$select_result['diastolic']:""; ?>"    min="0" name="diastolic" class="form-control"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>CVS</td>
						<td>
							<input type="radio"  name="cvs"   value="Yes"  <?php if(isset($select_result['cvs']) && $select_result['cvs'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="cvs"   value="No"  <?php if(isset($select_result['cvs']) && $select_result['cvs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cvs']) && $select_result['cvs'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
						</td>
					</tr>
					<tr>
						<td>Temperature(DEG F)</td>
						<td><input  type="number" value="<?php echo isset($select_result['temperature_deg_f'])?$select_result['temperature_deg_f']:""; ?>"    min="0" name="temperature_deg_f" class="form-control"></td>
					</tr>
					<tr>
						<td>Chest</td>
						<td>
							<input type="radio"  name="cns"   value="Yes"  <?php if(isset($select_result['cns']) && $select_result['cns'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="cns"   value="No"  <?php if(isset($select_result['cns']) && $select_result['cns'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cns']) && $select_result['cns'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
						</td>
					</tr>
					<tr>
						<td>Abdomen</td>
						<td>
							<input type="radio"  name="abdomen"   value="Yes"  <?php if(isset($select_result['abdomen']) && $select_result['abdomen'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="abdomen"   value="No"  <?php if(isset($select_result['abdomen']) && $select_result['abdomen'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['abdomen']) && $select_result['abdomen'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
						</td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input  type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>"    maxlength="20" name="others" class="form-control"></td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<tr>
			<th>LOCAL EXAMINATION</th>
			<td style="padding: 0;"><table width="100%">
					<tr>
						<td>P/S</td>
						<td>
							<input type="radio"  name="ps"   value="Yes"  <?php if(isset($select_result['ps']) && $select_result['ps'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="ps"   value="No"  <?php if(isset($select_result['ps']) && $select_result['ps'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['ps']) && $select_result['ps'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
						</td>
					</tr>
					<tr>
						<td>P/V</td>
						<td>
							<input type="radio"  name="pv"   value="Yes"  <?php if(isset($select_result['pv']) && $select_result['pv'] == "Yes"){echo 'checked="checked"'; }?>  >
							<label>Yes</label>
							<input type="radio"  name="pv"   value="No"  <?php if(isset($select_result['pv']) && $select_result['pv'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pv']) && $select_result['pv'] != "Yes"){echo 'checked="checked"';}?>  >
							<label>No</label>
						</td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
		<!-- <tr>
			<th>INVESTIGATIONS ADVICED(TO BE PICKED FROM BILLING INVESTIGATION LIST)</th>
			<td style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Blood group and Rh typing</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="blood_group" class="form-control"></td>
					</tr>
					<tr>
						<td>FSH</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="fsh" class="form-control"></td>
					</tr>
					<tr>
						<td>E2</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="e2" class="form-control"></td>
					</tr>
					<tr>
						<td>LH</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="lh" class="form-control"></td>
					</tr>
					<tr>
						<td>AMH</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="amh" class="form-control"></td>
					</tr>
					<tr>
						<td>Prolactin</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="prolactin" class="form-control"></td>
					</tr>
					<tr>
						<td>TSH</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="tsh" class="form-control"></td>
					</tr>
					<tr>
						<td>CBC</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="cbc" class="form-control"></td>
					</tr>
					<tr>
						<td>PT,APTT,INR</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="pt_aptt_inr" class="form-control"></td>
					</tr>
					<tr>
						<td>Urine routine microscopy</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="urine_routine_microscopy" class="form-control"></td>
					</tr>
					<tr>
						<td>Blood sugar</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="blood_sugar" class="form-control"></td>
					</tr>
					<tr>
						<td>Hb Electrophoresis</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="hb_electrophoresis" class="form-control"></td>
					</tr>
					<tr>
						<td>HIV</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="hiv" class="form-control"></td>
					</tr>
					<tr>
						<td>HIV PCR</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="hiv_pcr" class="form-control"></td>
					</tr>
					<tr>
						<td>Hbsag</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="hbsag" class="form-control"></td>
					</tr>
					<tr>
						<td>Hep C</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="hepc" class="form-control"></td>
					</tr>
					<tr>
						<td>VDRL</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="cdrl" class="form-control"></td>
					</tr>
					<tr>
						<td>RFT</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="rft" class="form-control"></td>
					</tr>
					<tr>
						<td>LFT</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="lft" class="form-control"></td>
					</tr>
					<tr>
						<td>USG pelvis</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="usg_pelvis" class="form-control"></td>
					</tr>
					<tr>
						<td>AFC</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="afc" class="form-control"></td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="others" class="form-control"></td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr> -->
		<!-- <tr>
			<th>MEDICINES ADVISED(TO BE MAPPED FROM INVENTORY)</th>
			<td style="padding: 0;">
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
						<td><input  type="text" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    maxlength="20" name="" class="form-control"></td>
						<td><input  type="text" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    maxlength="20" name="" class="form-control"></td>
						<td>
							<select class="form-control">
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
							<select class="form-control">
								<option value="OD">OD</option>
								<option value="BD">BD</option>
								<option value="TDS">TDS</option>
								<option value="QID">QID</option>
								<option value="SOS">SOS</option>
								<option value="HS">HS</option>
							</select>
						</td>
						<td>
							<select class="form-control">
								<option value="EMPTY STOMACH">EMPTY STOMACH</option>
								<option value="BEFORE MEAL">BEFORE MEAL</option>
								<option value="AFTER MEAL">AFTER MEAL</option>
							</select>
						</td>
						<td><input  type="text" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    maxlength="20" name="" class="form-control"></td>
						<td><input  type="number" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    min="0" name="" class="form-control"></td>
					</tr>
				</table>
			</td>
			<td style="padding: 0;">
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
						<td><input  type="text" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    maxlength="20" name="" class="form-control"></td>
						<td>
							<select class="form-control">
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
							<select class="form-control">
								<option value="OD">OD</option>
								<option value="BD">BD</option>
								<option value="TDS">TDS</option>
								<option value="QID">QID</option>
								<option value="SOS">SOS</option>
								<option value="HS">HS</option>
							</select>
						</td>
						<td>
							<select class="form-control">
								<option value="EMPTY STOMACH">EMPTY STOMACH</option>
								<option value="BEFORE MEAL">BEFORE MEAL</option>
								<option value="AFTER MEAL">AFTER MEAL</option>
							</select>
						</td>
						<td><input  type="text" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    maxlength="20" name="" class="form-control"></td>
						<td><input  type="number" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    min="0" name="" class="form-control"></td>
					</tr>
				</table>
			</td>
		</tr> -->
		<!-- <tr>
			<th>NEXT FOLLOW UP</th>
			<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="female_followup" class="form-control"></td>
			<td><input  type="date" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    name="male_followup" class="form-control"></td>
		</tr><br> -->
		<!-- <tr>
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
			<td>Date & Time: <input  type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"    name="date"> <input  type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"    name="time"></td>
			<td>Doctor Sign: <input  type="text" value="<?php echo isset($select_result['doctor_sign'])?$select_result['doctor_sign']:""; ?>"    name="doctor_sign"></td>
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


<!-- surrogate_mother_personal_details -->

	
	<table class="table-bordered"  style="width:100%; border:1px solid #cdcdcd;">
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
	
	<table class="table-bordered"  style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<th  style="border:1px solid #cdcdcd;"> </th>
			<td  style="border:1px solid #cdcdcd;"><b>FEMALE</b></td>
			<td  style="border:1px solid #cdcdcd;"><b>MALE</b></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">ART BANK</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_art_bank'])?$select_result['female_art_bank']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_art_bank'])?$select_result['male_art_bank']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">ID NUMBER</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_id_number'])?$select_result['female_id_number']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_id_number'])?$select_result['male_id_number']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PHOTOGRAPHS</th>
			<td  style="border:1px solid #cdcdcd;">
						
			<?php if(!empty($female_photographs)) {?>
				 <img src="<?php echo $female_photographs;?>" style="width:100px; height:100px;">
					<?php } else {echo " ";}?>
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
			
			<?php if(!empty($male_photographs)) {?>
				 <img src="<?php echo $male_photographs;?>" style="width:100px; height:100px;">
					<?php } else {echo " ";}?>
			
			
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">NAME</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_name'])?$select_result['female_name']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_name'])?$select_result['male_name']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">DOB</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_dob'])?$select_result['female_dob']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_dob'])?$select_result['male_dob']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">AGE</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_age'])?$select_result['female_age']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_age'])?$select_result['male_age']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">NATIONALITY</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_nationality'])?$select_result['female_nationality']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_nationality'])?$select_result['male_nationality']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">MOBILE NO</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_mobile'])?$select_result['female_mobile']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_mobile'])?$select_result['male_mobile']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">EMAIL ADDRESS</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_email'])?$select_result['female_email']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_email'])?$select_result['male_email']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PRESENT ADDRESS</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_present_address'])?$select_result['female_present_address']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_present_address'])?$select_result['male_present_address']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PERMANENT ADDRESS</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_permanent_address'])?$select_result['female_permanent_address']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_permanent_address'])?$select_result['male_permanent_address']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">AADHAR NUMBER</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_aadhar_number'])?$select_result['female_aadhar_number']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_aadhar_number'])?$select_result['male_aadhar_number']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PAN CARD NUMBER</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_pancard_number'])?$select_result['female_pancard_number']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_pancard_number'])?$select_result['male_pancard_number']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">RELIGION</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_religion'])?$select_result['female_religion']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_religion'])?$select_result['male_religion']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">MONTHLY INCOME</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_monthly_income'])?$select_result['female_monthly_income']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_monthly_income'])?$select_result['male_monthly_income']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;"><br></th>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">EDUCATION</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_education'])?$select_result['female_education']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_education'])?$select_result['male_education']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">OCCUPATION</th>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_occupation'])?$select_result['female_occupation']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_occupation'])?$select_result['male_occupation']:""; ?></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">WT/HT/BMI</th>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;">NOT REQUIRED</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">H/O previous pregnancies(IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Total pregnancies</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_pregnancy'])?$select_result['female_pregnancy']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No.of live births</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_live_births'])?$select_result['female_live_births']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No.of spontaneous abortions in first trimester</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_spontaneous_abortion'])?$select_result['female_spontaneous_abortion']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No.of termination of pregnancy</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_terminated_pregnancy'])?$select_result['female_terminated_pregnancy']:""; ?></td>
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
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_abnormility_of_child'])?$select_result['female_abnormility_of_child']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Others</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_others'])?$select_result['female_others']:""; ?></td>
					</tr>
				</table>
			</td>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Total pregnancies</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_pregnancy'])?$select_result['male_pregnancy']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No.of live births</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_live_births'])?$select_result['male_live_births']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No.of spontaneous abortions in first trimester</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_spontaneous_abortion'])?$select_result['male_spontaneous_abortion']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">No.of termination of pregnancy</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_terminated_pregnancy'])?$select_result['male_terminated_pregnancy']:""; ?></td>
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
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_abnormility_of_child'])?$select_result['male_abnormility_of_child']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Others</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_others'])?$select_result['male_others']:""; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">MARITAL LIFE IN (PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Years of marriage</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_years_of_marriage'])?$select_result['female_years_of_marriage']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Use of contraception</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_contraception'])?$select_result['female_contraception']:""; ?></td>
					</tr>
				</table>
			</td>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Years of marriage</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_years_of_marriage'])?$select_result['male_years_of_marriage']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Use of contraception</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_contraception'])?$select_result['male_contraception']:""; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PAST GYNECOLOGICAL HISTORY</th>
			<td  style="border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Dysmenorrhea</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dysmenorrhea'])?$select_result['dysmenorrhea']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Menorrhagia</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['menorrhagia'])?$select_result['menorrhagia']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">H/o D and c</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['d_and_c'])?$select_result['d_and_c']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Dyspareunia</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dyspareunia'])?$select_result['dyspareunia']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Others</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['past_others'])?$select_result['past_others']:""; ?></td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;">Not needed</td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">MENSTRUATION HISTORY</th>
			<td  style="border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Age at Menarche</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['age_at_menache'])?$select_result['age_at_menache']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Flow- heavy/average/less</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['flow'])?$select_result['flow']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Frequency- regular /irregular</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frequency'])?$select_result['frequency']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Days</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['days'])?$select_result['days']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Hirsutism</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hirsutism'])?$select_result['hirsutism']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Galactorrhea</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['galactorrea'])?$select_result['galactorrea']:""; ?></td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PAST MEDICAL HISTORY</th>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;">
														
<?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "Yes"){echo 'yes'; }?>		
							<br>
							<?php echo isset($select_result['heart_attack_text'])?$select_result['heart_attack_text']:""; ?>
					
					</td>
						<td  style="border:1px solid #cdcdcd;">Heart attack</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
<?php if(isset($select_result['pacemaker']) && $select_result['pacemaker'] == "Yes"){echo 'yes'; }?>	
	<br> <?php echo isset($select_result['pacemaker_text'])?$select_result['pacemaker_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Pacemaker</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
													
<?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "Yes"){echo 'yes'; }?> <br>
<?php echo isset($select_result['other_heart_disease_text'])?$select_result['other_heart_disease_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Other heart disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
												
<?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "Yes"){echo 'yes'; }?> <br>	<?php echo isset($select_result['high_blood_pressure_text'])?$select_result['high_blood_pressure_text']:""; ?>
						</td>
						
						
						
						<td  style="border:1px solid #cdcdcd;">High blood pressure</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
												
<?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "Yes"){echo 'yes'; }?> <br>
<?php echo isset($select_result['blood_clots_text'])?$select_result['blood_clots_text']:""; ?>
						
						
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;">Blood clots</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
<?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "Yes"){echo 'yes'; }?> <br>

						<?php echo isset($select_result['chest_pain_text'])?$select_result['chest_pain_text']:""; ?>
						
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;">Chest pain</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
		<?php if(isset($select_result['stroke']) && $select_result['stroke'] == "Yes"){echo 'yes'; }?> <br>					
		<?php echo isset($select_result['stroke_text'])?$select_result['stroke_text']:""; ?>
					

					</td>
						<td  style="border:1px solid #cdcdcd;">Stroke</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
			<?php if(isset($select_result['asthma']) && $select_result['asthma'] == "Yes"){echo 'yes'; }?> <br>				
<?php echo isset($select_result['asthma_text'])?$select_result['asthma_text']:""; ?>
					
					</td>
						<td  style="border:1px solid #cdcdcd;">Asthma</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
										
<?php if(isset($select_result['other_lung_disease']) && $select_result['other_lung_disease'] == "Yes"){echo 'yes'; }?> <br>	
<?php echo isset($select_result['lung_disease_text'])?$select_result['lung_disease_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Other lung disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
	<?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "Yes"){echo 'yes'; }?> <br>							
	<?php echo isset($select_result['difficulty_breathing_text'])?$select_result['difficulty_breathing_text']:""; ?>
	</td>
						<td  style="border:1px solid #cdcdcd;">Difficulty breathing</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">											
			<?php if(isset($select_result['sleep_apnea']) && $select_result['sleep_apnea'] == "Yes"){echo 'yes'; }?> <br>	
			<?php echo isset($select_result['snoring_text'])?$select_result['snoring_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Sleep apnea or snoring</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
					
		<?php if(isset($select_result['seizures']) && $select_result['seizures'] == "Yes"){echo 'yes'; }?> <br>		
		<?php echo isset($select_result['epilepsy_text'])?$select_result['epilepsy_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Epilepsy or seizures</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
		<?php if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] == "Yes"){echo 'yes'; }?> <br>			
<?php echo isset($select_result['fainting_spells_text'])?$select_result['fainting_spells_text']:""; ?>
	</td>
						<td  style="border:1px solid #cdcdcd;">Fainting spells</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
				
<?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "Yes"){echo 'yes'; }?> <br>
<?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>
						</td>
						
						<td  style="border:1px solid #cdcdcd;">Diabetes</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
													
	<?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "Yes"){echo 'yes'; }?> <br>						
<?php echo isset($select_result['muscle_disorders_text'])?$select_result['muscle_disorders_text']:""; ?>
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;">Muscle disorders</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
			
<?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "Yes"){echo 'yes'; }?> <br>
			
		<?php echo isset($select_result['kidney_disease_text'])?$select_result['kidney_disease_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Kidney disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
					
	<?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "Yes"){echo 'yes'; }?> <br>	
	<?php echo isset($select_result['hepatitis_text'])?$select_result['hepatitis_text']:""; ?>
						
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;">Hepatitis</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
			
<?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "Yes"){echo 'yes'; }?> <br>	
	<?php echo isset($select_result['tuberculosis_text'])?$select_result['tuberculosis_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Tuberculosis</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
														
	<?php if(isset($select_result['hiv']) && $select_result['hiv'] == "Yes"){echo 'yes'; }?> <br>							
	<?php echo isset($select_result['hiv_text'])?$select_result['hiv_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">HIV</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
										
	<?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "Yes"){echo 'yes'; }?> <br>	
						<?php echo isset($select_result['heart_burn_text'])?$select_result['heart_burn_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Heart burn/reflux</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
														
		<?php if(isset($select_result['cancer']) && $select_result['cancer'] == "Yes"){echo 'yes'; }?> <br>				
		<?php echo isset($select_result['cancer_text'])?$select_result['cancer_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Cancer</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
	<?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "Yes"){echo 'yes'; }?> <br>	
	<?php echo isset($select_result['blood_disorders_text'])?$select_result['blood_disorders_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Blood disorders</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
														
	<?php if(isset($select_result['psychiatric_disease']) && $select_result['psychiatric_disease'] == "Yes"){echo 'yes'; }?> <br>							
	<?php echo isset($select_result['rheumatic_disease_text'])?$select_result['rheumatic_disease_text']:""; ?>
						
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;">Rheumatic disease</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
		<?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "Yes"){echo 'yes'; }?> <br>					
						
		<?php echo isset($select_result['psychiatric_disorder_text'])?$select_result['psychiatric_disorder_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Psychiatric disorder</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
					
<?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "Yes"){echo 'yes'; }?> <br>		
<?php echo isset($select_result['thyroid_disorder_text'])?$select_result['thyroid_disorder_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Thyroid disorder</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
			<?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "Yes"){echo 'yes'; }?> <br>	
							<?php echo isset($select_result['urinary_infection_text'])?$select_result['urinary_infection_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Urinary infection</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
<?php if(isset($select_result['sexually_transmitted_disease']) && $select_result['sexually_transmitted_disease'] == "Yes"){echo 'yes'; }?> <br>				
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
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PAST SURGICAL HISTORY</th>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<table style="width:100%; border:1px solid #cdcdcd;">
					<tr>
						<td  style="border:1px solid #cdcdcd;">
														
					<?php if(isset($select_result['operations']) && $select_result['operations'] == "Yes"){echo 'yes'; }?> <br>		
							
							<?php echo isset($select_result['operations_text'])?$select_result['operations_text']:""; ?>    
						</td>
						<td  style="border:1px solid #cdcdcd;">Laparoscopy/pelvic/abdominal operations</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
		<?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "Yes"){echo 'yes'; }?> <br>						
	<?php echo isset($select_result['other_operations_text'])?$select_result['other_operations_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Other operations</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">Allergy history</th>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;">
		<?php if(isset($select_result['medications']) && $select_result['medications'] == "Yes"){echo 'yes'; }?> <br>
		
		<?php echo isset($select_result['medications_text'])?$select_result['medications_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Medications</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
													
<?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "Yes"){echo 'yes'; }?> <br>					
<?php echo isset($select_result['environmental_factors_text'])?$select_result['environmental_factors_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">environmental factors</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">SOCIAL & DRUG INTAKE HISTORY</th>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<table style="padding: 0;border:1px solid #cdcdcd; width:100%;">
					<tr>
						<td  style="padding: 0;border:1px solid #cdcdcd; width:20%;">
													
<?php if(isset($select_result['dentures']) && $select_result['dentures'] == "Yes"){echo 'yes'; }?> <br>
<?php echo isset($select_result['dentures_text'])?$select_result['dentures_text']:""; ?>"    
						</td>
						<td  style="border:1px solid #cdcdcd;">Dentures</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
			
	<?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "Yes"){echo 'yes'; }?> <br>	
<?php echo isset($select_result['loose_teeth_text'])?$select_result['loose_teeth_text']:""; ?>	
						</td>
						<td  style="border:1px solid #cdcdcd;">Loose teeth</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
											

	<?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "Yes"){echo 'yes'; }?> <br>

	<?php echo isset($select_result['hearing_aid_text'])?$select_result['hearing_aid_text']:""; ?>
					


					</td>
						<td  style="border:1px solid #cdcdcd;">Hearing aid</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
	<?php if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] == "Yes"){echo 'yes'; }?> <br>			
						
	<?php echo isset($select_result['caps_on_front_teeth_text'])?$select_result['caps_on_front_teeth_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Caps on front teeth</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
				
		<?php if(isset($select_result['conatact_lenses']) && $select_result['conatact_lenses'] == "Yes"){echo 'yes'; }?> <br>					
							
<?php echo isset($select_result['contact_lenses_text'])?$select_result['contact_lenses_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Contact lenses</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
														
		<?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "Yes"){echo 'yes'; }?> <br>
		<?php echo isset($select_result['body_piercing_text'])?$select_result['body_piercing_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Body piercing</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
<?php if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] == "Yes"){echo 'yes'; }?> <br>						
<?php echo isset($select_result['blood_transfusion_text'])?$select_result['blood_transfusion_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">H/o blood transfusion</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
	<?php if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] == "Yes"){echo 'yes'; }?> <br>		
	<?php echo isset($select_result['traffic_accident_text'])?$select_result['traffic_accident_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">H/o road traffic accident/any injury</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
		<?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "Yes"){echo 'yes'; }?> <br>					
		<?php echo isset($select_result['smoke_past_text'])?$select_result['smoke_past_text']:""; ?>"    
						</td>
						<td  style="border:1px solid #cdcdcd;">Smoke(past)daily</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
			<?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "Yes"){echo 'yes'; }?> <br>					
							
							<?php echo isset($select_result['smoke_present_text'])?$select_result['smoke_present_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Smoke(present)daily</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
						<?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "Yes"){echo 'yes'; }?> <br>		
							
							<?php echo isset($select_result['drink_past_text'])?$select_result['drink_past_text']:""; ?>"    
						</td>
						<td  style="border:1px solid #cdcdcd;">Drink(past)units per week</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
				<?php if(isset($select_result['drnk_present']) && $select_result['drnk_present'] == "Yes"){echo 'yes'; }?> <br>	
						
							
							<?php echo isset($select_result['drink_present_text'])?$select_result['drink_present_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Drink(present)units per week</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
							
				<?php if(isset($select_result['drugs']) && $select_result['drugs'] == "Yes"){echo 'yes'; }?> <br>	
			
							
							<?php echo isset($select_result['drugs_text'])?$select_result['drugs_text']:""; ?>"    
						</td>
						<td  style="border:1px solid #cdcdcd;">Hashish/cocaine /abusive drugs</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
						

<?php if(isset($select_result['steroid']) && $select_result['steroid'] == "Yes"){echo 'yes'; }?> <br>	


						<?php echo isset($select_result['steroid_text'])?$select_result['steroid_text']:""; ?>   
						</td>
						<td  style="border:1px solid #cdcdcd;">Have you ever used cortisone/steroid</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
													
<?php if(isset($select_result['medication']) && $select_result['medication'] == "Yes"){echo 'yes'; }?> <br>	

						<?php echo isset($select_result['medication_text'])?$select_result['medication_text']:""; ?>"    
						</td>
						<td  style="border:1px solid #cdcdcd;">Medication</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
														
			<?php if(isset($select_result['herbal_products']) && $select_result['herbal_products'] == "Yes"){echo 'yes'; }?> <br>				
							
							<?php echo isset($select_result['herbal_products_text'])?$select_result['herbal_products_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Herbal products</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
														
			<?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "Yes"){echo 'yes'; }?> <br>			
							<?php echo isset($select_result['eye_drops_text'])?$select_result['eye_drops_text']:""; ?>"    
						
						</td>
						<td  style="border:1px solid #cdcdcd;">Eye drops</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
		<?php if(isset($select_result['nonprescription_drugs']) && $select_result['nonprescription_drugs'] == "Yes"){echo 'yes'; }?> <br>							
						
<?php echo isset($select_result['nonprescription_drugs_text'])?$select_result['nonprescription_drugs_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">Non prescription drugs used currently other than medications used for this IVF cycle</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">FAMILY HISTORY</th>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<center>Any family member any problem with anesthesia</center>
				<table style="width:100%; border:1px solid #cdcdcd;">
					<tr>
						<td  style="border:1px solid #cdcdcd;"></td>
						<td  style="border:1px solid #cdcdcd;">Maternal</td>
						<td  style="border:1px solid #cdcdcd;">Paternal</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Diabetes</td>
						<td  style="border:1px solid #cdcdcd;">
													
		<?php if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] == "Yes"){echo 'yes'; }?> <br>					
		<?php echo isset($select_result['maternal_diabetes_text'])?$select_result['maternal_diabetes_text']:""; ?>
		</td>
						
						
						
						
<td  style="border:1px solid #cdcdcd;">
	<?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "Yes"){echo 'yes'; }?> <br>							
						
<?php echo isset($select_result['paternal_diabetes_text'])?$select_result['paternal_diabetes_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Heart/thrombo embolism</td>
						<td  style="border:1px solid #cdcdcd;">
							
	<?php if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] == "Yes"){echo 'yes'; }?> <br>						
							
	<?php echo isset($select_result['maternal_thrombo_embolism_text'])?$select_result['maternal_thrombo_embolism_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">
							
	<?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "Yes"){echo 'yes'; }?> <br>							
<?php echo isset($select_result['paternal_thrombo_embolism_text'])?$select_result['paternal_thrombo_embolism_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Endocrine/metabolic</td>
						<td  style="border:1px solid #cdcdcd;">
							
	<?php if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] == "Yes"){echo 'yes'; }?> <br>							
							
<?php echo isset($select_result['maternal_metabolic_text'])?$select_result['maternal_metabolic_text']:""; ?>
</td>
						
						
						
						
						<td  style="border:1px solid #cdcdcd;">
						
		<?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "Yes"){echo 'yes'; }?> <br>					
					
<?php echo isset($select_result['paternal_metabolic_text'])?$select_result['paternal_metabolic_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Urinary tract/renal</td>
						<td  style="border:1px solid #cdcdcd;">
	<?php if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] == "Yes"){echo 'yes'; }?> <br>							
<?php echo isset($select_result['maternal_urinary_tract_text'])?$select_result['maternal_urinary_tract_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">
													
<?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "Yes"){echo 'yes'; }?> <br>						
<?php echo isset($select_result['paternal_urinary_tract_text'])?$select_result['paternal_urinary_tract_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Psychiatric/neurological</td>
						<td  style="border:1px solid #cdcdcd;">
<?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "Yes"){echo 'yes'; }?> <br>						
					
<?php echo isset($select_result['maternal_neurological_text'])?$select_result['maternal_neurological_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">
<?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "Yes"){echo 'yes'; }?> <br>
<?php echo isset($select_result['paternal_neurological_text'])?$select_result['paternal_neurological_text']:""; ?>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Other/malignancy</td>
						<td  style="border:1px solid #cdcdcd;">
														
<?php if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] == "Yes"){echo 'yes'; }?> <br>				
<?php echo isset($select_result['maternal_malignancy_text'])?$select_result['maternal_malignancy_text']:""; ?>
						</td>
						<td  style="border:1px solid #cdcdcd;">
							
	<?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "Yes"){echo 'yes'; }?> <br>
	<?php echo isset($select_result['paternal_malignancy_text'])?$select_result['paternal_malignancy_text']:""; ?>
						</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">PREVIOUS HISTORY OF THIRD PARTY REPRODUCTION</th>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<table style="width:100%; border:1px solid #cdcdcd;">
					<tr>
						<td  style="border:1px solid #cdcdcd;">NO. OF TIMES EGG DONATED COMPLICATIONS</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['egg_donated'])?$select_result['egg_donated']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">COMPLICATIONS</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['did_surrogacy_complications'])?$select_result['did_surrogacy_complications']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">NO. OF TIMES EGG DONATED COMPLICATIONS</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['egg_donated_1'])?$select_result['egg_donated_1']:""; ?></td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">COMPLICATIONS</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['egg_donated_complications_1'])?$select_result['egg_donated_complications_1']:""; ?></td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">GENERAL EXAMINATION</th>
			<td style="padding: 0;border:1px solid #cdcdcd;">
				<table style="width:100%; border:1px solid #cdcdcd;">
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
						<td  style="border:1px solid #cdcdcd;">Blood pressure (MM HG)</td>
						<td style="padding: 0; border:1px solid #cdcdcd;">
							<table style="width:100%; border:1px solid #cdcdcd;">
								<tr>
									<td  style="border:1px solid #cdcdcd;">SYSTOLIC</td>
<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['systolic'])?$select_result['systolic']:""; ?></td>
								</tr>
								<tr>
<td  style="border:1px solid #cdcdcd;">DIASTOLIC</td>
<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['diastolic'])?$select_result['diastolic']:""; ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">CVS</td>
						<td  style="border:1px solid #cdcdcd;">
					
						<?php if(isset($select_result['cvs']) && $select_result['cvs'] == "Yes"){echo 'yes'; }?>
						
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Temperature(DEG F)</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['temperature_deg_f'])?$select_result['temperature_deg_f']:""; ?> </td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Chest</td>
						<td  style="border:1px solid #cdcdcd;">
												
<?php if(isset($select_result['cns']) && $select_result['cns'] == "Yes"){echo 'yes'; }?>

					</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Abdomen</td>
						<td  style="border:1px solid #cdcdcd;">
												
<?php if(isset($select_result['abdomen']) && $select_result['abdomen'] == "Yes"){echo 'yes'; }?>


					</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">Others</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['others'])?$select_result['others']:""; ?></td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<th  style="border:1px solid #cdcdcd;">LOCAL EXAMINATION</th>
			<td style="padding: 0;border:1px solid #cdcdcd;"><table width="100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;">P/S</td>
						<td  style="border:1px solid #cdcdcd;">
												
			<?php if(isset($select_result['ps']) && $select_result['ps'] == "Yes"){echo 'yes'; }?>			
				
						</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">P/V</td>
						<td  style="border:1px solid #cdcdcd;">
						
						<?php if(isset($select_result['pv']) && $select_result['pv'] == "Yes"){echo 'yes'; }?>	
							
						</td>
					</tr>
				</table>
			</td>
			<td  style="border:1px solid #cdcdcd;"></td>
		</tr>
	
		<tr>
			<th  style="border:1px solid #cdcdcd;"></th>
			<td  style="border:1px solid #cdcdcd;">Date & Time: <?php echo isset($select_result['date'])?$select_result['date']:""; ?> <?php echo isset($select_result['time'])?$select_result['time']:""; ?> </td>
			<td  style="border:1px solid #cdcdcd;">Doctor Sign: <?php echo isset($select_result['doctor_sign'])?$select_result['doctor_sign']:""; ?> </td>
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