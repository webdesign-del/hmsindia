<?php
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
        
        $select_query = "SELECT * FROM `hms_psychological_evaluation_sheet` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_psychological_evaluation_sheet` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_psychological_evaluation_sheet SET ";
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
    $select_query = "SELECT * FROM `hms_psychological_evaluation_sheet` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$select_result = run_select_query($select_query);  
	
	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);
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
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PSYCHOLOGICAL EVALUATION SHEET</h3></td>
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
					<td style="padding: 0;"><input type="file" name="female_photographs" class="form-control">
					<a target="_blank" href="<?php echo !empty($select_result['female_photographs'])?$select_result['female_photographs']:"javascript:void(0)"; ?>">Download</a></td>
					<td style="padding: 0;"><input type="file" name="male_photographs" class="form-control">
					<a target="_blank" href="<?php echo !empty($select_result['male_photographs'])?$select_result['male_photographs']:"javascript:void(0)"; ?>">Download</a></td>
				</tr>
				<tr>
					<th>NAME</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['female_name'])?$select_result['female_name']:""; ?>"     name="female_name" class="form-control"></td>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['male_name'])?$select_result['male_name']:""; ?>"     name="male_name" class="form-control"></td>
				</tr>
				<tr>
					<th>AGE (Years)</th>
					<td style="padding: 0;"><input  type="number" value="<?php echo isset($select_result['female_age'])?$select_result['female_age']:""; ?>"     name="female_age" class="form-control"></td>
					<td style="padding: 0;"><input  type="number" value="<?php echo isset($select_result['male_age'])?$select_result['male_age']:""; ?>"     name="male_age" class="form-control"></td>
				</tr>
				<tr>
					<th>OCCUPATION</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['female_occupation'])?$select_result['female_occupation']:""; ?>"     maxlength="15" name="female_occupation" class="form-control"></td>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['male_occupation'])?$select_result['male_occupation']:""; ?>"     maxlength="15" name="male_occupation" class="form-control"></td>
				</tr>
				<tr>
					<th>NATIONALITY</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['female_nationality'])?$select_result['female_nationality']:""; ?>"     maxlength="15" name="female_nationality" class="form-control"></td>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['male_nationality'])?$select_result['male_nationality']:""; ?>"     maxlength="15" name="male_nationality" class="form-control"></td>
				</tr>
				<tr>
					<th>ETHNICITY</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['female_religion'])?$select_result['female_religion']:""; ?>"     name="female_religion" class="form-control"></td>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['male_religion'])?$select_result['male_religion']:""; ?>"     name="male_religion" class="form-control"></td>
				</tr>
				<tr>
					<th>AADHAR NUMBER</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['female_aadhar_number'])?$select_result['female_aadhar_number']:""; ?>"     maxlength="50" name="female_aadhar_number" class="form-control"></td>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['male_aadhar_number'])?$select_result['male_aadhar_number']:""; ?>"     maxlength="50" name="male_aadhar_number" class="form-control"></td>
				</tr>
				<tr>
					<th>PRESENT ADDRESS</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['female_present_address'])?$select_result['female_present_address']:""; ?>"     maxlength="50" name="female_present_address" class="form-control"></td>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['male_present_address'])?$select_result['male_present_address']:""; ?>"     maxlength="50" name="male_present_address" class="form-control"></td>
				</tr>
				<tr>
					<th>PERMANENT ADDRESS</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['female_permanent_address'])?$select_result['female_permanent_address']:""; ?>"     maxlength="50" name="female_permanent_address" class="form-control"></td>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['male_permanent_address'])?$select_result['male_permanent_address']:""; ?>"     maxlength="50" name="male_permanent_address" class="form-control"></td>
				</tr>
				<tr>
					<th>CONTACT NO</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['female_mobile_no'])?$select_result['female_mobile_no']:""; ?>"     maxlength="50" name="female_mobile_no" class="form-control"></td>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['male_mobile_no'])?$select_result['male_mobile_no']:""; ?>"     maxlength="50" name="male_mobile_no" class="form-control"></td>
				</tr>
				<tr>
					<th>EMAIL ADDRESS</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['female_email_address'])?$select_result['female_email_address']:""; ?>"     maxlength="50" name="female_email_address" class="form-control"></td>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['male_email_address'])?$select_result['male_email_address']:""; ?>"     maxlength="50" name="male_email_address" class="form-control"></td>
				</tr>
				<tr>
					<th>WT (kg)/HT(m) /BMI(kg/m2)</th>
					<td><input  type="text" value="<?php echo isset($select_result['female_wt_ht_bmi'])?$select_result['female_wt_ht_bmi']:""; ?>"     maxlength="50" name="female_wt_ht_bmi" class="form-control"></td>
					<td><input  type="text" value="<?php echo isset($select_result['male_wt_ht_bmi'])?$select_result['male_wt_ht_bmi']:""; ?>"     maxlength="50" name="male_wt_ht_bmi" class="form-control"></td>
				</tr>
				<tr>
					<th>MARITAL STATUS</th>
					<td>
						<input type="radio"  name="female_marital_status" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Single"){echo 'checked="checked"'; }?> value="Single"> Single
						<input type="radio"  name="female_marital_status" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Engaged"){echo 'checked="checked"'; }?> value="Engaged"> Engaged
						<input type="radio"  name="female_marital_status" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Married"){echo 'checked="checked"'; }?> value="Married"> Married
						<input type="radio"  name="female_marital_status" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Yes"){echo 'checked="checked"'; }?> value="Separated"> Separated
						<input type="radio"  name="female_marital_status" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Divorced"){echo 'checked="checked"'; }?> value="Divorced"> Divorced
						<input type="radio"  name="female_marital_status" <?php if(isset($select_result['female_marital_status']) && $select_result['female_marital_status'] == "Widowed"){echo 'checked="checked"'; }?> value="Widowed"> Widowed
					</td>
					<td>
						<input type="radio"  name="male_marital_status" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Single"){echo 'checked="checked"'; }?> value="Single"> Single
						<input type="radio"  name="male_marital_status" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Engaged"){echo 'checked="checked"'; }?> value="Engaged"> Engaged
						<input type="radio"  name="male_marital_status" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Married"){echo 'checked="checked"'; }?> value="Married"> Married
						<input type="radio"  name="male_marital_status" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Separated"){echo 'checked="checked"'; }?> value="Separated"> Separated
						<input type="radio"  name="male_marital_status" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Divorced"){echo 'checked="checked"'; }?> value="Divorced"> Divorced
						<input type="radio"  name="male_marital_status" <?php if(isset($select_result['male_marital_status']) && $select_result['male_marital_status'] == "Widowed"){echo 'checked="checked"'; }?> value="Widowed"> Widowed
					</td>
				</tr>
				<tr>
					<th>H/O previous pregnancies(IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>Total pregnancies</td>
								<td><input  type="number" value="<?php echo isset($select_result['female_total_pregnancies'])?$select_result['female_total_pregnancies']:""; ?>"     max="20" min="0" name="female_total_pregnancies" class="form-control"></td>
							</tr>
							<tr>
								<td>No.of live births</td>
								<td><input  type="number" value="<?php echo isset($select_result['female_live_births'])?$select_result['female_live_births']:""; ?>"     max="20" min="0" name="female_live_births" class="form-control"></td>
							</tr>
							<tr>
								<td>No.of spontaneous abortions</td>
								<td><input  type="number" value="<?php echo isset($select_result['female_spontaneous_abortions'])?$select_result['female_spontaneous_abortions']:""; ?>"     max="20" min="0" name="female_spontaneous_abortions" class="form-control"></td>
							</tr>
							<tr>
								<td>No.of termination of pregnancy</td>
								<td><input  type="number" value="<?php echo isset($select_result['female_termination_of_pregnancy'])?$select_result['female_termination_of_pregnancy']:""; ?>"     max="20" min="0" name="female_termination_of_pregnancy" class="form-control"></td>
							</tr>
							<tr>
								<td>No.of still births</td>
								<td><input  type="number" value="<?php echo isset($select_result['female_still_births'])?$select_result['female_still_births']:""; ?>"     max="20" min="0" name="female_still_births" class="form-control"></td>
							</tr>
							<tr>
								<td>No. of ectopic pregnancy</td>
								<td><input  type="number" value="<?php echo isset($select_result['female_ectopic_pregnancy'])?$select_result['female_ectopic_pregnancy']:""; ?>"     max="20" min="0" name="female_ectopic_pregnancy" class="form-control"></td>
							</tr>
							<tr>
								<td>History of any abnormality in child</td>
								<td>
									<input type="radio"  name="female_history_of_abnormality"  value="Yes"  <?php if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
									<input type="radio"  name="female_history_of_abnormality"   value="No"  <?php if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] != "Yes"){echo 'checked="checked"';}?>  > No
									<input  type="text" value="<?php echo isset($select_result['female_history_of_abnormality_text'])?$select_result['female_history_of_abnormality_text']:""; ?>"     maxlength="50" name="female_history_of_abnormality_text" class="form-control">
								</td>
							</tr>
							<tr>
								<td>Others</td>
								<td><input  type="text" value="<?php echo isset($select_result['female_others'])?$select_result['female_others']:""; ?>"     maxlength="50" name="female_others" class="form-control"></td>
							</tr>
						</table>
					</td>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>Total pregnancies INCLUDING PREVIOUS pregnancies</td>
								<td><input  type="number" value="<?php echo isset($select_result['male_total_pregnancies'])?$select_result['male_total_pregnancies']:""; ?>"     max="20" min="0" name="male_total_pregnancies" class="form-control"></td>
							</tr>
							<tr>
								<td colspan="2"><br><br><br><br><br><br><br><br><br><br><br><br><br></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>LEGAL PROBLEMS</th>
					<td><input  type="text" value="<?php echo isset($select_result['legal_problems'])?$select_result['legal_problems']:""; ?>"     maxlength="50" name="legal_problems" class="form-control"></td>
					<td><input  type="text" value="<?php echo isset($select_result['male_legal_problems'])?$select_result['male_legal_problems']:""; ?>"     maxlength="50" name="male_legal_problems" class="form-control"></td>

				</tr>
				<tr>
					<th>PHYSICAL OR SEXUAL ABUSE HISTORY</th>
					<td>
						<input type="radio"  name="sexual_abuse"  value="Yes"  <?php if(isset($select_result['sexual_abuse']) && $select_result['sexual_abuse'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
						<input type="radio"  name="sexual_abuse"   value="No"  <?php if(isset($select_result['sexual_abuse']) && $select_result['sexual_abuse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['sexual_abuse']) && $select_result['sexual_abuse'] != "Yes"){echo 'checked="checked"';}?>  > No
						<input  type="text" value="<?php echo isset($select_result['sexual_abuse_text'])?$select_result['sexual_abuse_text']:""; ?>"     maxlength="50" name="sexual_abuse_text">
					</td>
					<td>
						<input type="radio"  name="male_sexual_abuse"  value="Yes"  <?php if(isset($select_result['male_sexual_abuse']) && $select_result['male_sexual_abuse'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
						<input type="radio"  name="male_sexual_abuse"   value="No"  <?php if(isset($select_result['male_sexual_abuse']) && $select_result['male_sexual_abuse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_sexual_abuse']) && $select_result['male_sexual_abuse'] != "Yes"){echo 'checked="checked"';}?>  > No
						<input  type="text" value="<?php echo isset($select_result['male_sexual_abuse_text'])?$select_result['male_sexual_abuse_text']:""; ?>"     maxlength="50" name="male_sexual_abuse_text">
					</td>
				</tr>
				<tr>
					<th>AWARENESS ABOUT THE PROCEDURE</th>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>why do you want to undergo ivf cycle/be an egg /sperm donor/surrogate</td>
								<td><input  type="text" value="<?php echo isset($select_result['egg_donor'])?$select_result['egg_donor']:""; ?>"     maxlength="50" name="egg_donor"></td>
							</tr>
							<tr>
								<td>Have you volunteered or forced?</td>
								<td><input  type="text" value="<?php echo isset($select_result['volunteered'])?$select_result['volunteered']:""; ?>"     maxlength="50" name="volunteered"></td>
							</tr>
							<tr>
								<td>With whom have you chosen to disclose, and are they supportive?</td>
								<td><input  type="text" value="<?php echo isset($select_result['supportive'])?$select_result['supportive']:""; ?>"     maxlength="50" name="supportive"></td>
							</tr>
							<tr>
								<td>Do you understand the medical treatment (daily injections, scans, retrieval)?</td>
								<td>
									<input type="radio"  name="medical_treatment"  value="Yes"  <?php if(isset($select_result['medical_treatment']) && $select_result['medical_treatment'] == "Yes"){echo 'checked="checked"'; }?>  > Yes &nbsp;
									<input type="radio"  name="medical_treatment"   value="No"  <?php if(isset($select_result['medical_treatment']) && $select_result['medical_treatment'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['medical_treatment']) && $select_result['medical_treatment'] != "Yes"){echo 'checked="checked"';}?>  > No &nbsp;
								</td>
							</tr>
							<tr>
								<td>If you have trouble with injections, do you have someone who can help?</td>
								<td>
									<input type="radio"  name="trouble_with_injections"  value="Yes"  <?php if(isset($select_result['trouble_with_injections']) && $select_result['trouble_with_injections'] == "Yes"){echo 'checked="checked"'; }?>  > Yes &nbsp;
									<input type="radio"  name="trouble_with_injections"   value="No"  <?php if(isset($select_result['trouble_with_injections']) && $select_result['trouble_with_injections'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['trouble_with_injections']) && $select_result['trouble_with_injections'] != "Yes"){echo 'checked="checked"';}?>  > No &nbsp;
								</td>
							</tr>
						</table>
					</td>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>why do you want to undergo ivf cycle/be an egg /sperm donor/surrogate</td>
								<td><input  type="text" value="<?php echo isset($select_result['egg_donor_2'])?$select_result['egg_donor_2']:""; ?>"     maxlength="50" name="egg_donor_2"></td>
							</tr>
							<tr>
								<td>Have you volunteered or forced?</td>
								<td><input  type="text" value="<?php echo isset($select_result['volunteered_2'])?$select_result['volunteered_2']:""; ?>"     maxlength="50" name="volunteered_2"></td>
							</tr>
							<tr>
								<td>With whom have you chosen to disclose, and are they supportive?</td>
								<td><input  type="text" value="<?php echo isset($select_result['supportive_2'])?$select_result['supportive_2']:""; ?>"     maxlength="50" name="supportive_2"></td>
							</tr>
							<tr>
								<td>Do you understand the medical treatment (daily injections, scans, retrieval)?</td>
								<td>
									<input type="radio"  name="medical_treatment_2"  value="Yes"  <?php if(isset($select_result['medical_treatment_2']) && $select_result['medical_treatment_2'] == "Yes"){echo 'checked="checked"'; }?>  > Yes &nbsp;
									<input type="radio"  name="medical_treatment_2"   value="No"  <?php if(isset($select_result['medical_treatment_2']) && $select_result['medical_treatment_2'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['medical_treatment_2']) && $select_result['medical_treatment_2'] != "Yes"){echo 'checked="checked"';}?>  > No &nbsp;
								</td>
							</tr>
							<tr>
								<td>If you have trouble with injections, do you have someone who can help?</td>
								<td>
									<input type="radio"  name="trouble_with_injections_2"  value="Yes"  <?php if(isset($select_result['trouble_with_injections_2']) && $select_result['trouble_with_injections_2'] == "Yes"){echo 'checked="checked"'; }?>  > Yes &nbsp;
									<input type="radio"  name="trouble_with_injections_2"   value="No"  <?php if(isset($select_result['trouble_with_injections_2']) && $select_result['trouble_with_injections_2'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['trouble_with_injections_2']) && $select_result['trouble_with_injections_2'] != "Yes"){echo 'checked="checked"';}?>  > No &nbsp;
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
									<input type="radio"  id="text1" name="heart_attack"  value="Yes"  <?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="heart_attack"   value="No"  <?php if(isset($select_result['heart_attack']) && $select_result['heart_attack'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['heart_attack']) && $select_result['heart_attack'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									
									<input  type="text" value="<?php echo isset($select_result['heart_attack_text'])?$select_result['heart_attack_text']:""; ?>"     maxlength="25" name="heart_attack_text">
									
								</td>
								<td>Heart attack</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="pacemaker"  value="Yes"  <?php if(isset($select_result['pacemaker']) && $select_result['pacemaker'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="pacemaker"   value="No"  <?php if(isset($select_result['pacemaker']) && $select_result['pacemaker'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pacemaker']) && $select_result['pacemaker'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['pacemaker_text'])?$select_result['pacemaker_text']:""; ?>"     maxlength="25" name="pacemaker_text">
								</td>
								<td>Pacemaker</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="other_heart_disease"  value="Yes"  <?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="other_heart_disease"   value="No"  <?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['other_heart_disease_text'])?$select_result['other_heart_disease_text']:""; ?>"     maxlength="25" name="other_heart_disease_text">
								</td>
								<td>Other heart disease</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="high_blood_pressure"  value="Yes"  <?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="high_blood_pressure"   value="No"  <?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['high_blood_pressure_text'])?$select_result['high_blood_pressure_text']:""; ?>"     maxlength="25" name="high_blood_pressure_text">
								</td>
								<td>High blood pressure</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="blood_clots"  value="Yes"  <?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="blood_clots"   value="No"  <?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['blood_clots']) && $select_result['blood_clots'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['blood_clots_text'])?$select_result['blood_clots_text']:""; ?>"     maxlength="25" name="blood_clots_text">
								</td>
								<td>Blood clots</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="chest_pain"  value="Yes"  <?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="chest_pain"   value="No"  <?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['chest_pain']) && $select_result['chest_pain'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['chest_pain_text'])?$select_result['chest_pain_text']:""; ?>"     maxlength="25" name="chest_pain_text">
								</td>
								<td>Chest pain</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="stroke"  value="Yes"  <?php if(isset($select_result['stroke']) && $select_result['stroke'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="stroke"   value="No"  <?php if(isset($select_result['stroke']) && $select_result['stroke'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['stroke']) && $select_result['stroke'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"     maxlength="25" name="stroke_text">
								</td>
								<td>Stroke</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="asthma"  value="Yes"  <?php if(isset($select_result['asthma']) && $select_result['asthma'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="asthma"   value="No"  <?php if(isset($select_result['asthma']) && $select_result['asthma'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['asthma']) && $select_result['asthma'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['asthma_text'])?$select_result['asthma_text']:""; ?>"     maxlength="25" name="asthma_text">
								</td>
								<td>Asthma</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="lung_disease"  value="Yes"  <?php if(isset($select_result['lung_disease']) && $select_result['lung_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="lung_disease"   value="No"  <?php if(isset($select_result['lung_disease']) && $select_result['lung_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['lung_disease']) && $select_result['lung_disease'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['lung_disease_text'])?$select_result['lung_disease_text']:""; ?>"     maxlength="25" name="lung_disease_text">
								</td>
								<td>Other lung disease</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="difficulty_breathing"  value="Yes"  <?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="difficulty_breathing"   value="No"  <?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['difficulty_breathing_text'])?$select_result['difficulty_breathing_text']:""; ?>"     maxlength="25" name="difficulty_breathing_text">
								</td>
								<td>Difficulty breathing</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="snoring"  value="Yes"  <?php if(isset($select_result['snoring']) && $select_result['snoring'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="snoring"   value="No"  <?php if(isset($select_result['snoring']) && $select_result['snoring'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['snoring']) && $select_result['snoring'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['snoring_text'])?$select_result['snoring_text']:""; ?>"     maxlength="25" name="snoring_text">
								</td>
								<td>Sleep apnea or snoring</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="epilepsy"  value="Yes"  <?php if(isset($select_result['epilepsy']) && $select_result['epilepsy'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="epilepsy"   value="No"  <?php if(isset($select_result['epilepsy']) && $select_result['epilepsy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['epilepsy']) && $select_result['epilepsy'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['epilepsy_text'])?$select_result['epilepsy_text']:""; ?>"     maxlength="25" name="epilepsy_text">
								</td>
								<td>Epilepsy or seizures</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="fainting_spells"  value="Yes"  <?php if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="fainting_spells"   value="No"  <?php if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['fainting_spells']) && $select_result['fainting_spells'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['fainting_spells_text'])?$select_result['fainting_spells_text']:""; ?>"     maxlength="25" name="fainting_spells_text">
								</td>
								<td>Fainting spells</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="diabetes"  value="Yes"  <?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="diabetes"   value="No"  <?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['diabetes']) && $select_result['diabetes'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>"     maxlength="25" name="diabetes_text">
								</td>
								<td>Diabetes</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="muscle_disorders"  value="Yes"  <?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="muscle_disorders"   value="No"  <?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['muscle_disorders_text'])?$select_result['muscle_disorders_text']:""; ?>"     maxlength="25" name="muscle_disorders_text">
								</td>
								<td>Muscle disorders</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="kidney_disease"  value="Yes"  <?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="kidney_disease"   value="No"  <?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['kidney_disease_text'])?$select_result['kidney_disease_text']:""; ?>"     maxlength="25" name="kidney_disease_text">
								</td>
								<td>Kidney disease</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="hepatitis"  value="Yes"  <?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="hepatitis"   value="No"  <?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hepatitis']) && $select_result['hepatitis'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['hepatitis_text'])?$select_result['hepatitis_text']:""; ?>"     maxlength="25" name="hepatitis_text">
								</td>
								<td>Hepatitis</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="tuberculosis"  value="Yes"  <?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="tuberculosis"   value="No"  <?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['tuberculosis_text'])?$select_result['tuberculosis_text']:""; ?>"     maxlength="25" name="tuberculosis_text">
								</td>
								<td>Tuberculosis</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="hiv"  value="Yes"  <?php if(isset($select_result['hiv']) && $select_result['hiv'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="hiv"   value="No"  <?php if(isset($select_result['hiv']) && $select_result['hiv'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hiv']) && $select_result['hiv'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['hiv_text'])?$select_result['hiv_text']:""; ?>"     maxlength="25" name="hiv_text">
								</td>
								<td>HIV</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="heart_burn"  value="Yes"  <?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="heart_burn"   value="No"  <?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['heart_burn']) && $select_result['heart_burn'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['heart_burn_text'])?$select_result['heart_burn_text']:""; ?>"     maxlength="25" name="heart_burn_text">
								</td>
								<td>Heart burn/reflux</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="cancer"  value="Yes"  <?php if(isset($select_result['cancer']) && $select_result['cancer'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="cancer"   value="No"  <?php if(isset($select_result['cancer']) && $select_result['cancer'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cancer']) && $select_result['cancer'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['cancer_text'])?$select_result['cancer_text']:""; ?>"     maxlength="25" name="cancer_text">
								</td>
								<td>Cancer</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="blood_disorders"  value="Yes"  <?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="blood_disorders"   value="No"  <?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['blood_disorders_text'])?$select_result['blood_disorders_text']:""; ?>"     maxlength="25" name="blood_disorders_text">
								</td>
								<td>Blood disorders</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="rheumatic_disease"  value="Yes"  <?php if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="rheumatic_disease"   value="No"  <?php if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['rheumatic_disease_text'])?$select_result['rheumatic_disease_text']:""; ?>"     maxlength="25" name="rheumatic_disease_text">
								</td>
								<td>Rheumatic disease</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="psychiatric_disorder"  value="Yes"  <?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="psychiatric_disorder"   value="No"  <?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['psychiatric_disorder_text'])?$select_result['psychiatric_disorder_text']:""; ?>"     maxlength="25" name="psychiatric_disorder_text">
								</td>
								<td>Psychiatric disorder</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="thyroid_disorder"  value="Yes"  <?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="thyroid_disorder"   value="No"  <?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['thyroid_disorder_text'])?$select_result['thyroid_disorder_text']:""; ?>"     maxlength="25" name="thyroid_disorder_text">
								</td>
								<td>Thyroid disorder</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="urinary_infection"  value="Yes"  <?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="urinary_infection"   value="No"  <?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['urinary_infection_text'])?$select_result['urinary_infection_text']:""; ?>"     maxlength="25" name="urinary_infection_text">
								</td>
								<td>Urinary infection</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="sexually_transmitted"  value="Yes"  <?php if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="sexually_transmitted"   value="No"  <?php if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['sexually_transmitted_text'])?$select_result['sexually_transmitted_text']:""; ?>"     maxlength="25" name="sexually_transmitted_text">
								</td>
								<td>Sexually transmitted disease</td>
							</tr>
							<tr>
								<td><input  type="text" value="<?php echo isset($select_result['impairments'])?$select_result['impairments']:""; ?>"     maxlength="25" class="form-control" name="impairments"></td>
								<td>Other medical condition or impairments</td>
							</tr>
						</table>
					</td>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>Heart attack</td>
								<td>
									<input type="radio"  id="text1" name="male_heart_attack"  value="Yes"  <?php if(isset($select_result['male_heart_attack']) && $select_result['male_heart_attack'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_heart_attack"   value="No"  <?php if(isset($select_result['male_heart_attack']) && $select_result['male_heart_attack'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_heart_attack']) && $select_result['male_heart_attack'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_heart_attack_text'])?$select_result['male_heart_attack_text']:""; ?>"     maxlength="25" name="male_heart_attack_text">
								</td>
							</tr>
							<tr>
								<td>Pacemaker</td>
								<td>
									<input type="radio"  id="text1" name="male_pacemaker"  value="Yes"  <?php if(isset($select_result['male_pacemaker']) && $select_result['male_pacemaker'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_pacemaker"   value="No"  <?php if(isset($select_result['male_pacemaker']) && $select_result['male_pacemaker'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_pacemaker']) && $select_result['male_pacemaker'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_pacemaker_text'])?$select_result['male_pacemaker_text']:""; ?>"     maxlength="25" name="male_pacemaker_text">
								</td>
							</tr>
							<tr>
								<td>Other heart disease</td>
								<td>
									<input type="radio"  id="text1" name="male_other_heart_disease"  value="Yes"  <?php if(isset($select_result['male_other_heart_disease']) && $select_result['male_other_heart_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_other_heart_disease"   value="No"  <?php if(isset($select_result['male_other_heart_disease']) && $select_result['male_other_heart_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_other_heart_disease']) && $select_result['male_other_heart_disease'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_other_heart_disease_text'])?$select_result['male_other_heart_disease_text']:""; ?>"     maxlength="25" name="male_other_heart_disease_text">
								</td>
							</tr>
							<tr>
								<td>High blood pressure</td>
								<td>
									<input type="radio"  id="text1" name="male_high_blood_pressure"  value="Yes"  <?php if(isset($select_result['male_high_blood_pressure']) && $select_result['male_high_blood_pressure'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_high_blood_pressure"   value="No"  <?php if(isset($select_result['male_high_blood_pressure']) && $select_result['male_high_blood_pressure'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_high_blood_pressure']) && $select_result['male_high_blood_pressure'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_high_blood_pressure_text'])?$select_result['male_high_blood_pressure_text']:""; ?>"     maxlength="25" name="male_high_blood_pressure_text">
								</td>
							</tr>
							<tr>
								<td>Blood clots</td>
								<td>
									<input type="radio"  id="text1" name="male_blood_clots"  value="Yes"  <?php if(isset($select_result['male_blood_clots']) && $select_result['male_blood_clots'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_blood_clots"   value="No"  <?php if(isset($select_result['male_blood_clots']) && $select_result['male_blood_clots'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_blood_clots']) && $select_result['male_blood_clots'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_blood_clots_text'])?$select_result['male_blood_clots_text']:""; ?>"     maxlength="25" name="male_blood_clots_text">
								</td>
							</tr>
							<tr>
								<td>Chest pain</td>
								<td>
									<input type="radio"  id="text1" name="male_chest_pain"  value="Yes"  <?php if(isset($select_result['male_chest_pain']) && $select_result['male_chest_pain'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_chest_pain"   value="No"  <?php if(isset($select_result['male_chest_pain']) && $select_result['male_chest_pain'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_chest_pain']) && $select_result['male_chest_pain'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_chest_pain_text'])?$select_result['male_chest_pain_text']:""; ?>"     maxlength="25" name="male_chest_pain_text">
								</td>
							</tr>
							<tr>
								<td>Stroke</td>
								<td>
									<input type="radio"  id="text1" name="male_stroke"  value="Yes"  <?php if(isset($select_result['male_stroke']) && $select_result['male_stroke'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_stroke"   value="No"  <?php if(isset($select_result['male_stroke']) && $select_result['male_stroke'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_stroke']) && $select_result['male_stroke'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_stroke_text'])?$select_result['male_stroke_text']:""; ?>"     maxlength="25" name="male_stroke_text">
								</td>
							</tr>
							<tr>
								<td>Asthma</td>
								<td>
									<input type="radio"  id="text1" name="male_asthma"  value="Yes"  <?php if(isset($select_result['male_asthma']) && $select_result['male_asthma'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_asthma"   value="No"  <?php if(isset($select_result['male_asthma']) && $select_result['male_asthma'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_asthma']) && $select_result['male_asthma'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_asthma_text'])?$select_result['male_asthma_text']:""; ?>"     maxlength="25" name="male_asthma_text">
								</td>
							</tr>
							<tr>
								<td>Other lung disease</td>
								<td>
									<input type="radio"  id="text1" name="male_lung_disease"  value="Yes"  <?php if(isset($select_result['male_lung_disease']) && $select_result['male_lung_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_lung_disease"   value="No"  <?php if(isset($select_result['male_lung_disease']) && $select_result['male_lung_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_lung_disease']) && $select_result['male_lung_disease'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_lung_disease_text'])?$select_result['male_lung_disease_text']:""; ?>"     maxlength="25" name="male_lung_disease_text">
								</td>
							</tr>
							<tr>
								<td>Difficulty breathing</td>
								<td>
									<input type="radio"  id="text1" name="male_difficulty_breathing"  value="Yes"  <?php if(isset($select_result['male_difficulty_breathing']) && $select_result['male_difficulty_breathing'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_difficulty_breathing"   value="No"  <?php if(isset($select_result['male_difficulty_breathing']) && $select_result['male_difficulty_breathing'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_difficulty_breathing']) && $select_result['male_difficulty_breathing'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"     maxlength="25" name="male_difficulty_breathing_text">
								</td>
							</tr>
							<tr>
								<td>Sleep apnea or snoring</td>
								<td>
									<input type="radio"  id="text1" name="male_snoring"  value="Yes"  <?php if(isset($select_result['male_snoring']) && $select_result['male_snoring'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_snoring"   value="No"  <?php if(isset($select_result['male_snoring']) && $select_result['male_snoring'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_snoring']) && $select_result['male_snoring'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_snoring_text'])?$select_result['male_snoring_text']:""; ?>"     maxlength="25" name="male_snoring_text">
								</td>
							</tr>
							<tr>
								<td>Epilepsy or seizures</td>
								<td>
									<input type="radio"  id="text1" name="male_epilepsy"  value="Yes"  <?php if(isset($select_result['male_epilepsy']) && $select_result['male_epilepsy'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_epilepsy"   value="No"  <?php if(isset($select_result['male_epilepsy']) && $select_result['male_epilepsy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_epilepsy']) && $select_result['male_epilepsy'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_epilepsy_text'])?$select_result['male_epilepsy_text']:""; ?>"     maxlength="25" name="male_epilepsy_text">
								</td>
							</tr>
							<tr>
								<td>Fainting spells</td>
								<td>
									<input type="radio"  id="text1" name="male_fainting_spells"  value="Yes"  <?php if(isset($select_result['male_fainting_spells']) && $select_result['male_fainting_spells'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_fainting_spells"   value="No"  <?php if(isset($select_result['male_fainting_spells']) && $select_result['male_fainting_spells'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_fainting_spells']) && $select_result['male_fainting_spells'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_fainting_spells_text'])?$select_result['male_fainting_spells_text']:""; ?>"     maxlength="25" name="male_fainting_spells_text">
								</td>
							</tr>
							<tr>
								<td>Diabetes</td>
								<td>
									<input type="radio"  id="text1" name="male_diabetes"  value="Yes"  <?php if(isset($select_result['male_diabetes']) && $select_result['male_diabetes'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_diabetes"   value="No"  <?php if(isset($select_result['male_diabetes']) && $select_result['male_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_diabetes']) && $select_result['male_diabetes'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_diabetes_text'])?$select_result['male_diabetes_text']:""; ?>"     maxlength="25" name="male_diabetes_text">
								</td>
							</tr>
							<tr>
								<td>Muscle disorders</td>
								<td>
									<input type="radio"  id="text1" name="male_muscle_disorders"  value="Yes"  <?php if(isset($select_result['male_muscle_disorders']) && $select_result['male_muscle_disorders'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_muscle_disorders"   value="No"  <?php if(isset($select_result['male_muscle_disorders']) && $select_result['male_muscle_disorders'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_muscle_disorders']) && $select_result['male_muscle_disorders'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_muscle_disorders_text'])?$select_result['male_muscle_disorders_text']:""; ?>"     maxlength="25" name="male_muscle_disorders_text">
								</td>
							</tr>
							<tr>
								<td>Kidney disease</td>
								<td>
									<input type="radio"  id="text1" name="male_kidney_disease"  value="Yes"  <?php if(isset($select_result['male_kidney_disease']) && $select_result['male_kidney_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_kidney_disease"   value="No"  <?php if(isset($select_result['male_kidney_disease']) && $select_result['male_kidney_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_kidney_disease']) && $select_result['male_kidney_disease'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_kidney_disease_text'])?$select_result['male_kidney_disease_text']:""; ?>"     maxlength="25" name="male_kidney_disease_text">
								</td>
							</tr>
							<tr>
								<td>Hepatitis</td>
								<td>
									<input type="radio"  id="text1" name="male_hepatitis"  value="Yes"  <?php if(isset($select_result['male_hepatitis']) && $select_result['male_hepatitis'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_hepatitis"   value="No"  <?php if(isset($select_result['male_hepatitis']) && $select_result['male_hepatitis'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_hepatitis']) && $select_result['male_hepatitis'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_hepatitis_text'])?$select_result['male_hepatitis_text']:""; ?>"     maxlength="25" name="male_hepatitis_text">
								</td>
							</tr>
							<tr>
								<td>Tuberculosis</td>
								<td>
									<input type="radio"  id="text1" name="male_tuberculosis"  value="Yes"  <?php if(isset($select_result['male_tuberculosis']) && $select_result['male_tuberculosis'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_tuberculosis"   value="No"  <?php if(isset($select_result['male_tuberculosis']) && $select_result['male_tuberculosis'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_tuberculosis']) && $select_result['male_tuberculosis'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_tuberculosis_text'])?$select_result['male_tuberculosis_text']:""; ?>"     maxlength="25" name="male_tuberculosis_text">
								</td>
							</tr>
							<tr>
								<td>HIV</td>
								<td>
									<input type="radio"  id="text1" name="male_hiv"  value="Yes"  <?php if(isset($select_result['male_hiv']) && $select_result['male_hiv'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_hiv"   value="No"  <?php if(isset($select_result['male_hiv']) && $select_result['male_hiv'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_hiv']) && $select_result['male_hiv'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_hiv_text'])?$select_result['male_hiv_text']:""; ?>"     maxlength="25" name="male_hiv_text">
								</td>
							</tr>
							<tr>
								<td>Heart burn/reflux</td>
								<td>
									<input type="radio"  name="male_heart_burn"  value="Yes"  <?php if(isset($select_result['male_heart_burn']) && $select_result['male_heart_burn'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label for="type2">Yes</label>
									<input type="radio"  name="male_heart_burn"   value="No"  <?php if(isset($select_result['male_heart_burn']) && $select_result['male_heart_burn'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_heart_burn']) && $select_result['male_heart_burn'] != "Yes"){echo 'checked="checked"';}?>  >
									<label for="type2">No</label>
									<input  type="text" value="<?php echo isset($select_result['male_heart_burn_text'])?$select_result['male_heart_burn_text']:""; ?>"     maxlength="25" name="male_heart_burn_text">
								</td>
							</tr>
							<tr>
								<td>Cancer </td>
								<td>
									<input type="radio"  name="male_cancer"  value="Yes"  <?php if(isset($select_result['male_cancer']) && $select_result['male_cancer'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label for="type2">Yes</label>
									<input type="radio"  name="male_cancer"   value="No"  <?php if(isset($select_result['male_cancer']) && $select_result['male_cancer'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_cancer']) && $select_result['male_cancer'] != "Yes"){echo 'checked="checked"';}?>  >
									<label for="type2">No</label>
									<input  type="text" value="<?php echo isset($select_result['male_cancer_text'])?$select_result['male_cancer_text']:""; ?>"     maxlength="25" name="male_cancer_text">
								</td>
							</tr>
							<tr>
								<td>Blood disorders</td>
								<td>
									<input type="radio"  name="male_blood_disorders"  value="Yes"  <?php if(isset($select_result['male_blood_disorders']) && $select_result['male_blood_disorders'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label for="type2">Yes</label>
									<input type="radio"  name="male_blood_disorders"   value="No"  <?php if(isset($select_result['male_blood_disorders']) && $select_result['male_blood_disorders'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_blood_disorders']) && $select_result['male_blood_disorders'] != "Yes"){echo 'checked="checked"';}?>  >
									<label for="type2">No</label>
									<input  type="text" value="<?php echo isset($select_result['male_blood_disorders_text'])?$select_result['male_blood_disorders_text']:""; ?>"     maxlength="25" name="male_blood_disorders_text">
								</td>
							</tr>
							<tr>
								<td>Rheumatic disease</td>
								<td>
									<input type="radio"  name="male_rheumatic_disease"  value="Yes"  <?php if(isset($select_result['male_rheumatic_disease']) && $select_result['male_rheumatic_disease'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label for="type2">Yes</label>
									<input type="radio"  name="male_rheumatic_disease"   value="No"  <?php if(isset($select_result['male_rheumatic_disease']) && $select_result['male_rheumatic_disease'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_rheumatic_disease']) && $select_result['male_rheumatic_disease'] != "Yes"){echo 'checked="checked"';}?>  >
									<label for="type2">No</label>
									<input  type="text" value="<?php echo isset($select_result['male_rheumatic_disease_text'])?$select_result['male_rheumatic_disease_text']:""; ?>"     maxlength="25" name="male_rheumatic_disease_text">
								</td>
							</tr>
							<tr>
								<td>Psychiatric disorder</td>
								<td>
									<input type="radio"  name="male_psychiatric_disorder"  value="Yes"  <?php if(isset($select_result['male_psychiatric_disorder']) && $select_result['male_psychiatric_disorder'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label for="type2">Yes</label>
									<input type="radio"  name="male_psychiatric_disorder"   value="No"  <?php if(isset($select_result['male_psychiatric_disorder']) && $select_result['male_psychiatric_disorder'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_psychiatric_disorder']) && $select_result['male_psychiatric_disorder'] != "Yes"){echo 'checked="checked"';}?>  >
									<label for="type2">No</label>
									<input  type="text" value="<?php echo isset($select_result['male_psychiatric_disorder_text'])?$select_result['male_psychiatric_disorder_text']:""; ?>"     maxlength="25" name="male_psychiatric_disorder_text">
								</td>
							</tr>
							<tr>
								<td>Thyroid disorder</td>
								<td>
									<input type="radio"  name="male_thyroid_disorder"  value="Yes"  <?php if(isset($select_result['male_thyroid_disorder']) && $select_result['male_thyroid_disorder'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label for="type2">Yes</label>
									<input type="radio"  name="male_thyroid_disorder"   value="No"  <?php if(isset($select_result['male_thyroid_disorder']) && $select_result['male_thyroid_disorder'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_thyroid_disorder']) && $select_result['male_thyroid_disorder'] != "Yes"){echo 'checked="checked"';}?>  >
									<label for="type2">No</label>
									<input  type="text" value="<?php echo isset($select_result['male_thyroid_disorder_text'])?$select_result['male_thyroid_disorder_text']:""; ?>"     maxlength="25" name="male_thyroid_disorder_text">
								</td>
							</tr>
							<tr>
								<td>Urinary infection</td>
								<td>
									<input type="radio"  name="male_urinary_infection"  value="Yes"  <?php if(isset($select_result['male_urinary_infection']) && $select_result['male_urinary_infection'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label for="type2">Yes</label>
									<input type="radio"  name="male_urinary_infection"   value="No"  <?php if(isset($select_result['male_urinary_infection']) && $select_result['male_urinary_infection'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_urinary_infection']) && $select_result['male_urinary_infection'] != "Yes"){echo 'checked="checked"';}?>  >
									<label for="type2">No</label>
									<input  type="text" value="<?php echo isset($select_result['male_urinary_infection_text'])?$select_result['male_urinary_infection_text']:""; ?>"     maxlength="25" name="male_urinary_infection_text">
								</td>
							</tr>
							<tr>
								<td>Sexually transmitted disease</td>
								<td>
									<input type="radio"  name="male_sexually_transmitted"  value="Yes"  <?php if(isset($select_result['male_sexually_transmitted']) && $select_result['male_sexually_transmitted'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label for="type2">Yes</label>
									<input type="radio"  name="male_sexually_transmitted"   value="No"  <?php if(isset($select_result['male_sexually_transmitted']) && $select_result['male_sexually_transmitted'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_sexually_transmitted']) && $select_result['male_sexually_transmitted'] != "Yes"){echo 'checked="checked"';}?>  >
									<label for="type2">No</label>
									<input  type="text" value="<?php echo isset($select_result['male_sexually_transmitted_text'])?$select_result['male_sexually_transmitted_text']:""; ?>"     maxlength="25" name="male_sexually_transmitted_text">
								</td>
							</tr>
							<tr>
								<td>Other medical condition or impairments</td>
								<td>
									<input  type="text" value="<?php echo isset($select_result['male_impairments'])?$select_result['male_impairments']:""; ?>"     maxlength="50" name="male_impairments" class="form-control">
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
									<input type="radio"  id="text1" name="abdominal_operations"  value="Yes"  <?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="abdominal_operations"   value="No"  <?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['abdominal_operations_text'])?$select_result['abdominal_operations_text']:""; ?>"     maxlength="20" name="abdominal_operations_text">
								</td>
							</tr>
							<tr>
								<td>Other operations</td>
								<td>
									<input type="radio"  id="text1" name="other_operations"  value="Yes"  <?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="other_operations"   value="No"  <?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['other_operations']) && $select_result['other_operations'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['other_operations_text'])?$select_result['other_operations_text']:""; ?>"     maxlength="50" name="other_operations_text">
								</td>
							</tr>
						</table>
					</td>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>Laparoscopy/pelvic/abdominal operations</td>
								<td>
									<input type="radio"  id="text1" name="male_abdominal_operations"  value="Yes"  <?php if(isset($select_result['male_abdominal_operations']) && $select_result['male_abdominal_operations'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_abdominal_operations"   value="No"  <?php if(isset($select_result['male_abdominal_operations']) && $select_result['male_abdominal_operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_abdominal_operations']) && $select_result['male_abdominal_operations'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_abdominal_operations_text'])?$select_result['male_abdominal_operations_text']:""; ?>"     maxlength="20" name="male_abdominal_operations_text">
								</td>
							</tr>
							<tr>
								<td>Other operations</td>
								<td>
									<input type="radio"  id="text1" name="male_other_operations"  value="Yes"  <?php if(isset($select_result['male_other_operations']) && $select_result['male_other_operations'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_other_operations"   value="No"  <?php if(isset($select_result['male_other_operations']) && $select_result['male_other_operations'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_other_operations']) && $select_result['male_other_operations'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_other_operations_text'])?$select_result['male_other_operations_text']:""; ?>"     maxlength="50" name="male_other_operations_text">
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
									<input type="radio"  id="text1" name="medications"  value="Yes"  <?php if(isset($select_result['medications']) && $select_result['medications'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="medications"   value="No"  <?php if(isset($select_result['medications']) && $select_result['medications'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['medications']) && $select_result['medications'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['medications_text'])?$select_result['medications_text']:""; ?>"     maxlength="20" name="medications_text">
								</td>
							</tr>
							<tr>
								<td>environmental factors</td>
								<td>
									<input type="radio"  id="text1" name="environmental_factors"  value="Yes"  <?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="environmental_factors"   value="No"  <?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['environmental_factors_text'])?$select_result['environmental_factors_text']:""; ?>"     maxlength="20" name="environmental_factors_text">
								</td>
							</tr>
						</table>
					</td>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>Medications</td>
								<td>
									<input type="radio"  id="text1" name="male_medications"  value="Yes"  <?php if(isset($select_result['male_medications']) && $select_result['male_medications'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_medications"   value="No"  <?php if(isset($select_result['male_medications']) && $select_result['male_medications'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_medications']) && $select_result['male_medications'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_medications_text'])?$select_result['male_medications_text']:""; ?>"     maxlength="20" name="male_medications_text">
								</td>
							</tr>
							<tr>
								<td>environmental factors</td>
								<td>
									<input type="radio"  id="text1" name="male_environmental_factors"  value="Yes"  <?php if(isset($select_result['male_environmental_factors']) && $select_result['male_environmental_factors'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_environmental_factors"   value="No"  <?php if(isset($select_result['male_environmental_factors']) && $select_result['male_environmental_factors'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_environmental_factors']) && $select_result['male_environmental_factors'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_environmental_factors_text'])?$select_result['male_environmental_factors_text']:""; ?>"     maxlength="20" name="male_environmental_factors_text">
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
									<input type="radio"  id="text1" name="dentures"  value="Yes"  <?php if(isset($select_result['dentures']) && $select_result['dentures'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="dentures"   value="No"  <?php if(isset($select_result['dentures']) && $select_result['dentures'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['dentures']) && $select_result['dentures'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['dentures_text'])?$select_result['dentures_text']:""; ?>"     maxlength="25" name="dentures_text">
								</td>
								<td>Dentures</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="loose_teeth"  value="Yes"  <?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="loose_teeth"   value="No"  <?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['loose_teeth_text'])?$select_result['loose_teeth_text']:""; ?>"     maxlength="25" name="loose_teeth_text">
								</td>
								<td>Loose teeth</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="hearing_aid"  value="Yes"  <?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="hearing_aid"   value="No"  <?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['hearing_aid_text'])?$select_result['hearing_aid_text']:""; ?>"     maxlength="25" name="hearing_aid_text">
								</td>
								<td>Hearing aid</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="caps_on_front_teeth"  value="Yes"  <?php if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="caps_on_front_teeth"   value="No"  <?php if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['caps_on_front_teeth']) && $select_result['caps_on_front_teeth'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['caps_on_front_teeth_text'])?$select_result['caps_on_front_teeth_text']:""; ?>"     maxlength="25" name="caps_on_front_teeth_text">
								</td>
								<td>Caps on front teeth</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="contact_lenses"  value="Yes"  <?php if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="contact_lenses"   value="No"  <?php if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['contact_lenses_text'])?$select_result['contact_lenses_text']:""; ?>"     maxlength="25" name="contact_lenses_text">
								</td>
								<td>Contact lenses</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="body_piercing"  value="Yes"  <?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="body_piercing"   value="No"  <?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['body_piercing']) && $select_result['body_piercing'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['body_piercing_text'])?$select_result['body_piercing_text']:""; ?>"     maxlength="25" name="body_piercing_text">
								</td>
								<td>Body piercing</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="blood_transfusion"  value="Yes"  <?php if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="blood_transfusion"   value="No"  <?php if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['blood_transfusion']) && $select_result['blood_transfusion'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['blood_transfusion_text'])?$select_result['blood_transfusion_text']:""; ?>"     maxlength="25" name="blood_transfusion_text">
								</td>
								<td>H/o blood transfusion</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="traffic_accident"  value="Yes"  <?php if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="traffic_accident"   value="No"  <?php if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['traffic_accident']) && $select_result['traffic_accident'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['traffic_accident_text'])?$select_result['traffic_accident_text']:""; ?>"     maxlength="25" name="traffic_accident_text">
								</td>
								<td>H/o road traffic accident/any injury</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="smoke_past"  value="Yes"  <?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="smoke_past"   value="No"  <?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['smoke_past']) && $select_result['smoke_past'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['smoke_past_text'])?$select_result['smoke_past_text']:""; ?>"     maxlength="25" name="smoke_past_text">
								</td>
								<td>Smoke(past)daily</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="smoke_present"  value="Yes"  <?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="smoke_present"   value="No"  <?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['smoke_present']) && $select_result['smoke_present'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['smoke_present_text'])?$select_result['smoke_present_text']:""; ?>"     maxlength="25" name="smoke_present_text">
								</td>
								<td>Smoke(present)daily</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="drink_past"  value="Yes"  <?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="drink_past"   value="No"  <?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['drink_past']) && $select_result['drink_past'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['drink_past_text'])?$select_result['drink_past_text']:""; ?>"     maxlength="25" name="drink_past_text">
								</td>
								<td>Drink(past)units per week</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="drink_present"  value="Yes"  <?php if(isset($select_result['drink_present']) && $select_result['drink_present'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="drink_present"   value="No"  <?php if(isset($select_result['drink_present']) && $select_result['drink_present'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['drink_present']) && $select_result['drink_present'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['drink_present_text'])?$select_result['drink_present_text']:""; ?>"     maxlength="25" name="drink_present_text">
								</td>
								<td>Drink(present)units per week</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="abusive_drugs"  value="Yes"  <?php if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="abusive_drugs"   value="No"  <?php if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['abusive_drugs_text'])?$select_result['abusive_drugs_text']:""; ?>"     maxlength="25" name="abusive_drugs_text">
								</td>
								<td>Hashish/cocaine /abusive drugs</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="steroid"  value="Yes"  <?php if(isset($select_result['steroid']) && $select_result['steroid'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="steroid"   value="No"  <?php if(isset($select_result['steroid']) && $select_result['steroid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['steroid']) && $select_result['steroid'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['steroid_text'])?$select_result['steroid_text']:""; ?>"     maxlength="25" name="steroid_text">
								</td>
								<td>Have you ever used cortisone/steroid</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="medication"  value="Yes"  <?php if(isset($select_result['medication']) && $select_result['medication'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="medication"   value="No"  <?php if(isset($select_result['medication']) && $select_result['medication'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['medication']) && $select_result['medication'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['medication_text'])?$select_result['medication_text']:""; ?>"     maxlength="25" name="medication_text">
								</td>
								<td>Medication</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="herbal_products"  value="Yes"  <?php if(isset($select_result['herbal_products']) && $select_result['herbal_products'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="herbal_products"   value="No"  <?php if(isset($select_result['herbal_products']) && $select_result['herbal_products'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['herbal_products']) && $select_result['herbal_products'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['herbal_products_text'])?$select_result['herbal_products_text']:""; ?>"     maxlength="25" name="herbal_products_text">
								</td>
								<td>Herbal products</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="eye_drops"  value="Yes"  <?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="eye_drops"   value="No"  <?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['eye_drops']) && $select_result['eye_drops'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['eye_drops_text'])?$select_result['eye_drops_text']:""; ?>"     maxlength="25" name="eye_drops_text">
								</td>
								<td>Eye drops</td>
							</tr>
							<tr>
								<td>
									<input type="radio"  id="text1" name="non_prescription_drugs"  value="Yes"  <?php if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="non_prescription_drugs"   value="No"  <?php if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['non_prescription_drugs']) && $select_result['non_prescription_drugs'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['non_prescription_drugs_text'])?$select_result['non_prescription_drugs_text']:""; ?>"     maxlength="25" name="non_prescription_drugs_text">
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
									<input type="radio"  id="text1" name="male_dentures"  value="Yes"  <?php if(isset($select_result['male_dentures']) && $select_result['male_dentures'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_dentures"   value="No"  <?php if(isset($select_result['male_dentures']) && $select_result['male_dentures'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_dentures']) && $select_result['male_dentures'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_dentures_text'])?$select_result['male_dentures_text']:""; ?>"     maxlength="25" name="male_dentures_text">
								</td>
							</tr>
							<tr>
								<td>Loose teeth</td>
								<td>
									<input type="radio"  id="text1" name="male_loose_teeth"  value="Yes"  <?php if(isset($select_result['male_loose_teeth']) && $select_result['male_loose_teeth'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_loose_teeth"   value="No"  <?php if(isset($select_result['male_loose_teeth']) && $select_result['male_loose_teeth'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_loose_teeth']) && $select_result['male_loose_teeth'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_loose_teeth_text'])?$select_result['male_loose_teeth_text']:""; ?>"     maxlength="25" name="male_loose_teeth_text">
								</td>
							</tr>
							<tr>
								<td>Hearing aid</td>
								<td>
									<input type="radio"  id="text1" name="male_hearing_aid"  value="Yes"  <?php if(isset($select_result['male_hearing_aid']) && $select_result['male_hearing_aid'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_hearing_aid"   value="No"  <?php if(isset($select_result['male_hearing_aid']) && $select_result['male_hearing_aid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_hearing_aid']) && $select_result['male_hearing_aid'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_hearing_aid_text'])?$select_result['male_hearing_aid_text']:""; ?>"     maxlength="25" name="male_hearing_aid_text">
								</td>
							</tr>
							<tr>
								<td>Caps on front teeth</td>
								<td>
									<input type="radio"  id="text1" name="male_caps_on_front_teeth"  value="Yes"  <?php if(isset($select_result['male_caps_on_front_teeth']) && $select_result['male_caps_on_front_teeth'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_caps_on_front_teeth"   value="No"  <?php if(isset($select_result['male_caps_on_front_teeth']) && $select_result['male_caps_on_front_teeth'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_caps_on_front_teeth']) && $select_result['male_caps_on_front_teeth'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_caps_on_front_teeth_text'])?$select_result['male_caps_on_front_teeth_text']:""; ?>"     maxlength="25" name="male_caps_on_front_teeth_text">
								</td>
							</tr>
							<tr>
								<td>Contact lenses</td>
								<td>
									<input type="radio"  id="text1" name="male_contact_lenses"  value="Yes"  <?php if(isset($select_result['male_contact_lenses']) && $select_result['male_contact_lenses'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_contact_lenses"   value="No"  <?php if(isset($select_result['male_contact_lenses']) && $select_result['male_contact_lenses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_contact_lenses']) && $select_result['male_contact_lenses'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_contact_lenses_text'])?$select_result['male_contact_lenses_text']:""; ?>"     maxlength="25" name="male_contact_lenses_text">
								</td>
							</tr>
							<tr>
								<td>Body piercing</td>
								<td>
									<input type="radio"  id="text1" name="male_body_piercing"  value="Yes"  <?php if(isset($select_result['male_body_piercing']) && $select_result['male_body_piercing'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_body_piercing"   value="No"  <?php if(isset($select_result['male_body_piercing']) && $select_result['male_body_piercing'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_body_piercing']) && $select_result['male_body_piercing'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_body_piercing_text'])?$select_result['male_body_piercing_text']:""; ?>"     maxlength="25" name="male_body_piercing_text">
								</td>
							</tr>
							<tr>
								<td>H/o blood transfusion</td>
								<td>
									<input type="radio"  id="text1" name="male_blood_transfusion"  value="Yes"  <?php if(isset($select_result['male_blood_transfusion']) && $select_result['male_blood_transfusion'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_blood_transfusion"   value="No"  <?php if(isset($select_result['male_blood_transfusion']) && $select_result['male_blood_transfusion'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_blood_transfusion']) && $select_result['male_blood_transfusion'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_blood_transfusion_text'])?$select_result['male_blood_transfusion_text']:""; ?>"     maxlength="25" name="male_blood_transfusion_text">
								</td>
							</tr>
							<tr>
								<td>H/o road traffic accident/any injury</td>
								<td>
									<input type="radio"  id="text1" name="male_traffic_accident"  value="Yes"  <?php if(isset($select_result['male_traffic_accident']) && $select_result['male_traffic_accident'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_traffic_accident"   value="No"  <?php if(isset($select_result['male_traffic_accident']) && $select_result['male_traffic_accident'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_traffic_accident']) && $select_result['male_traffic_accident'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_traffic_accident_text'])?$select_result['male_traffic_accident_text']:""; ?>"     maxlength="25" name="male_traffic_accident_text">
								</td>
							</tr>
							<tr>
								<td>Smoke(past)daily</td>
								<td>
									<input type="radio"  id="text1" name="male_smoke_past"  value="Yes"  <?php if(isset($select_result['male_smoke_past']) && $select_result['male_smoke_past'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_smoke_past"   value="No"  <?php if(isset($select_result['male_smoke_past']) && $select_result['male_smoke_past'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_smoke_past']) && $select_result['male_smoke_past'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_smoke_past_text'])?$select_result['male_smoke_past_text']:""; ?>"     maxlength="25" name="male_smoke_past_text">
								</td>
							</tr>
							<tr>
								<td>Smoke(present)daily</td>
								<td>
									<input type="radio"  id="text1" name="male_smoke_present"  value="Yes"  <?php if(isset($select_result['male_smoke_present']) && $select_result['male_smoke_present'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_smoke_present"   value="No"  <?php if(isset($select_result['male_smoke_present']) && $select_result['male_smoke_present'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_smoke_present']) && $select_result['male_smoke_present'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_smoke_present_text'])?$select_result['male_smoke_present_text']:""; ?>"     maxlength="25" name="male_smoke_present_text">
								</td>
							</tr>
							<tr>
								<td>Drink(past)units per week</td>
								<td>
									<input type="radio"  id="text1" name="male_drink_past"  value="Yes"  <?php if(isset($select_result['male_drink_past']) && $select_result['male_drink_past'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_drink_past"   value="No"  <?php if(isset($select_result['male_drink_past']) && $select_result['male_drink_past'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_drink_past']) && $select_result['male_drink_past'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_drink_past_text'])?$select_result['male_drink_past_text']:""; ?>"     maxlength="25" name="male_drink_past_text">
								</td>
							</tr>
							<tr>
								<td>Drink(present)units per week</td>
								<td>
									<input type="radio"  id="text1" name="male_drink_present"  value="Yes"  <?php if(isset($select_result['children']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_drink_present"   value="No"  <?php if(isset($select_result['children']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['children']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"     maxlength="25" name="male_drink_present_text">
								</td>
							</tr>
							<tr>
								<td>Hashish/cocaine /abusive drugs</td>
								<td>
									<input type="radio"  id="text1" name="male_abusive_drugs"  value="Yes"  <?php if(isset($select_result['male_abusive_drugs']) && $select_result['male_abusive_drugs'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_abusive_drugs"   value="No"  <?php if(isset($select_result['male_abusive_drugs']) && $select_result['male_abusive_drugs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_abusive_drugs']) && $select_result['male_abusive_drugs'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_abusive_drugs_text'])?$select_result['male_abusive_drugs_text']:""; ?>"     maxlength="25" name="male_abusive_drugs_text">
								</td>
							</tr>
							<tr>
								<td>Have you ever used cortisone/steroid</td>
								<td>
									<input type="radio"  id="text1" name="male_steroid"  value="Yes"  <?php if(isset($select_result['male_steroid']) && $select_result['male_steroid'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_steroid"   value="No"  <?php if(isset($select_result['male_steroid']) && $select_result['male_steroid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_steroid']) && $select_result['male_steroid'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_steroid_text'])?$select_result['male_steroid_text']:""; ?>"     maxlength="25" name="male_steroid_text">
								</td>
							</tr>
							<tr>
								<td>Medication</td>
								<td>
									<input type="radio"  id="text1" name="male_medication"  value="Yes"  <?php if(isset($select_result['male_medication']) && $select_result['male_medication'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_medication"   value="No"  <?php if(isset($select_result['male_medication']) && $select_result['male_medication'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_medication']) && $select_result['male_medication'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_medication_text'])?$select_result['male_medication_text']:""; ?>"     maxlength="25" name="male_medication_text">
								</td>
							</tr>
							<tr>
								<td>Herbal products</td>
								<td>
									<input type="radio"  id="text1" name="male_herbal_products"  value="Yes"  <?php if(isset($select_result['male_herbal_products']) && $select_result['male_herbal_products'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_herbal_products"   value="No"  <?php if(isset($select_result['male_herbal_products']) && $select_result['male_herbal_products'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_herbal_products']) && $select_result['male_herbal_products'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_herbal_products_text'])?$select_result['male_herbal_products_text']:""; ?>"     maxlength="25" name="male_herbal_products_text">
								</td>
							</tr>
							<tr>
								<td>Eye drops</td>
								<td>
									<input type="radio"  id="text1" name="male_eye_drops"  value="Yes"  <?php if(isset($select_result['male_eye_drops']) && $select_result['male_eye_drops'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_eye_drops"   value="No"  <?php if(isset($select_result['male_eye_drops']) && $select_result['male_eye_drops'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_eye_drops']) && $select_result['male_eye_drops'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_eye_drops_text'])?$select_result['male_eye_drops_text']:""; ?>"     maxlength="25" name="male_eye_drops_text">
								</td>
							</tr>
							<tr>
								<td>Non prescription drugs used currently other than medications used for this IVF cycle</td>
								<td>
									<input type="radio"  id="text1" name="male_non_prescription_drugs"  value="Yes"  <?php if(isset($select_result['male_non_prescription_drugs']) && $select_result['male_non_prescription_drugs'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  id="text1" name="male_non_prescription_drugs"   value="No"  <?php if(isset($select_result['male_non_prescription_drugs']) && $select_result['male_non_prescription_drugs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_non_prescription_drugs']) && $select_result['male_non_prescription_drugs'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_non_prescription_drugs_text'])?$select_result['male_non_prescription_drugs_text']:""; ?>"     maxlength="25" name="male_non_prescription_drugs_text">
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
									<input type="radio"  name="member_with_anesthesia"  value="Yes"  <?php if(isset($select_result['member_with_anesthesia']) && $select_result['member_with_anesthesia'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="member_with_anesthesia"   value="No"  <?php if(isset($select_result['member_with_anesthesia']) && $select_result['member_with_anesthesia'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['member_with_anesthesia']) && $select_result['member_with_anesthesia'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['member_with_anesthesia_text'])?$select_result['member_with_anesthesia_text']:""; ?>"     maxlength="25" name="member_with_anesthesia_text">
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
									<input type="radio"  name="maternal_diabetes"  value="Yes"  <?php if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="maternal_diabetes"   value="No"  <?php if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_diabetes']) && $select_result['maternal_diabetes'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['maternal_diabetes_text'])?$select_result['maternal_diabetes_text']:""; ?>"     maxlength="25" name="maternal_diabetes_text">
								</td>
								<td>
									<input type="radio"  name="paternal_diabetes"  value="Yes"  <?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="paternal_diabetes"   value="No"  <?php if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_diabetes']) && $select_result['paternal_diabetes'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['paternal_diabetes_text'])?$select_result['paternal_diabetes_text']:""; ?>"     maxlength="25" name="paternal_diabetes_text">
								</td>
							</tr>
							<tr>
								<td>Heart/thrombo embolism</td>
								<td>
									<input type="radio"  name="maternal_thrombo_embolism"  value="Yes"  <?php if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="maternal_thrombo_embolism"   value="No"  <?php if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_thrombo_embolism']) && $select_result['maternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"     maxlength="25" name="maternal_thrombo_embolism_text">
								</td>
								<td>
									<input type="radio"  name="paternal_thrombo_embolism"  value="Yes"  <?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="paternal_thrombo_embolism"   value="No"  <?php if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_thrombo_embolism']) && $select_result['paternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['paternal_thrombo_embolism_text'])?$select_result['paternal_thrombo_embolism_text']:""; ?>"     maxlength="25" name="paternal_thrombo_embolism_text">
								</td>
							</tr>
							<tr>
								<td>Endocrine/metabolic</td>
								<td>
									<input type="radio"  name="maternal_metabolic"  value="Yes"  <?php if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="maternal_metabolic"   value="No"  <?php if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_metabolic']) && $select_result['maternal_metabolic'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['maternal_metabolic_text'])?$select_result['maternal_metabolic_text']:""; ?>"     maxlength="25" name="maternal_metabolic_text">
								</td>
								<td>
									<input type="radio"  name="paternal_metabolic"  value="Yes"  <?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="paternal_metabolic"   value="No"  <?php if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_metabolic']) && $select_result['paternal_metabolic'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['paternal_metabolic_text'])?$select_result['paternal_metabolic_text']:""; ?>"     maxlength="25" name="paternal_metabolic_text">
								</td>
							</tr>
							<tr>
								<td>Urinary tract/renal</td>
								<td>
									<input type="radio"  name="maternal_urinary_tract"  value="Yes"  <?php if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="maternal_urinary_tract"   value="No"  <?php if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_urinary_tract']) && $select_result['maternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['maternal_urinary_tract_text'])?$select_result['maternal_urinary_tract_text']:""; ?>"     maxlength="25" name="maternal_urinary_tract_text">
								</td>
								<td>
									<input type="radio"  name="paternal_urinary_tract"  value="Yes"  <?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="paternal_urinary_tract"   value="No"  <?php if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_urinary_tract']) && $select_result['paternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['paternal_urinary_tract_text'])?$select_result['paternal_urinary_tract_text']:""; ?>"     maxlength="25" name="paternal_urinary_tract_text">
								</td>
							</tr>
							<tr>
								<td>Psychiatric/neurological</td>
								<td>
									<input type="radio"  name="maternal_neurological"  value="Yes"  <?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="maternal_neurological"   value="No"  <?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['maternal_neurological_text'])?$select_result['maternal_neurological_text']:""; ?>"     maxlength="25" name="maternal_neurological_text">
								</td>
								<td>
									<input type="radio"  name="paternal_neurological"  value="Yes"  <?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="paternal_neurological"   value="No"  <?php if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_neurological']) && $select_result['paternal_neurological'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['paternal_neurological_text'])?$select_result['paternal_neurological_text']:""; ?>"     maxlength="25" name="paternal_neurological_text">
								</td>
							</tr>
							<tr>
								<td>Other/malignancy</td>
								<td>
									<input type="radio"  name="maternal_malignancy"  value="Yes"  <?php if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="maternal_malignancy"   value="No"  <?php if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['maternal_malignancy']) && $select_result['maternal_malignancy'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['maternal_malignancy_text'])?$select_result['maternal_malignancy_text']:""; ?>"     maxlength="25" name="maternal_malignancy_text">
								</td>
								<td>
									<input type="radio"  name="paternal_malignancy"  value="Yes"  <?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="paternal_malignancy"   value="No"  <?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['paternal_malignancy_text'])?$select_result['paternal_malignancy_text']:""; ?>"     maxlength="25" name="paternal_malignancy_text">
								</td>
							</tr>
						</table>
					</td>
					<td>
						<table width="100%">
							<tr>
								<td>Any family member any problem <br> with anesthesia</td>
								<td colspan="2">
									<input type="radio"  name="male_member_with_anesthesia"  value="Yes"  <?php if(isset($select_result['male_member_with_anesthesia']) && $select_result['male_member_with_anesthesia'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_member_with_anesthesia"   value="No"  <?php if(isset($select_result['male_member_with_anesthesia']) && $select_result['male_member_with_anesthesia'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_member_with_anesthesia']) && $select_result['male_member_with_anesthesia'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_member_with_anesthesia_text'])?$select_result['male_member_with_anesthesia_text']:""; ?>"     maxlength="25" name="male_member_with_anesthesia_text">
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
									<input type="radio"  name="male_maternal_diabetes"  value="Yes"  <?php if(isset($select_result['male_maternal_diabetes']) && $select_result['male_maternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_maternal_diabetes"   value="No"  <?php if(isset($select_result['male_maternal_diabetes']) && $select_result['male_maternal_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_diabetes']) && $select_result['male_maternal_diabetes'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_maternal_diabetes_text'])?$select_result['male_maternal_diabetes_text']:""; ?>"     maxlength="25" name="male_maternal_diabetes_text">
								</td>
								<td>
									<input type="radio"  name="male_paternal_diabetes"  value="Yes"  <?php if(isset($select_result['male_paternal_diabetes']) && $select_result['male_paternal_diabetes'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_paternal_diabetes"   value="No"  <?php if(isset($select_result['male_paternal_diabetes']) && $select_result['male_paternal_diabetes'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_diabetes']) && $select_result['male_paternal_diabetes'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_paternal_diabetes_text'])?$select_result['male_paternal_diabetes_text']:""; ?>"     maxlength="25" name="male_paternal_diabetes_text">
								</td>
							</tr>
							<tr>
								<td>Heart/thrombo embolism</td>
								<td>
									<input type="radio"  name="male_maternal_thrombo_embolism"  value="Yes"  <?php if(isset($select_result['male_maternal_thrombo_embolism']) && $select_result['male_maternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_maternal_thrombo_embolism"   value="No"  <?php if(isset($select_result['male_maternal_thrombo_embolism']) && $select_result['male_maternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_thrombo_embolism']) && $select_result['male_maternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_maternal_thrombo_embolism_text'])?$select_result['male_maternal_thrombo_embolism_text']:""; ?>"     maxlength="25" name="male_maternal_thrombo_embolism_text">
								</td>
								<td>
									<input type="radio"  name="male_paternal_thrombo_embolism"  value="Yes"  <?php if(isset($select_result['male_paternal_thrombo_embolism']) && $select_result['male_paternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_paternal_thrombo_embolism"   value="No"  <?php if(isset($select_result['male_paternal_thrombo_embolism']) && $select_result['male_paternal_thrombo_embolism'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_thrombo_embolism']) && $select_result['male_paternal_thrombo_embolism'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_paternal_thrombo_embolism_text'])?$select_result['male_paternal_thrombo_embolism_text']:""; ?>"     maxlength="25" name="male_paternal_thrombo_embolism_text">
								</td>
							</tr>
							<tr>
								<td>Endocrine/metabolic</td>
								<td>
									<input type="radio"  name="male_maternal_metabolic"  value="Yes"  <?php if(isset($select_result['male_maternal_metabolic']) && $select_result['male_maternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_maternal_metabolic"   value="No"  <?php if(isset($select_result['male_maternal_metabolic']) && $select_result['male_maternal_metabolic'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_metabolic']) && $select_result['male_maternal_metabolic'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_maternal_metabolic_text'])?$select_result['male_maternal_metabolic_text']:""; ?>"     maxlength="25" name="male_maternal_metabolic_text">
								</td>
								<td>
									<input type="radio"  name="male_paternal_metabolic"  value="Yes"  <?php if(isset($select_result['male_paternal_metabolic']) && $select_result['male_paternal_metabolic'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_paternal_metabolic"   value="No"  <?php if(isset($select_result['male_paternal_metabolic']) && $select_result['male_paternal_metabolic'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_metabolic']) && $select_result['male_paternal_metabolic'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_paternal_metabolic_text'])?$select_result['male_paternal_metabolic_text']:""; ?>"     maxlength="25" name="male_paternal_metabolic_text">
								</td>
							</tr>
							<tr>
								<td>Urinary tract/renal</td>
								<td>
									<input type="radio"  name="male_maternal_urinary_tract"  value="Yes"  <?php if(isset($select_result['male_maternal_urinary_tract']) && $select_result['male_maternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_maternal_urinary_tract"   value="No"  <?php if(isset($select_result['male_maternal_urinary_tract']) && $select_result['male_maternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_urinary_tract']) && $select_result['male_maternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_maternal_urinary_tract_text'])?$select_result['male_maternal_urinary_tract_text']:""; ?>"     maxlength="25" name="male_maternal_urinary_tract_text">
								</td>
								<td>
									<input type="radio"  name="male_paternal_urinary_tract"  value="Yes"  <?php if(isset($select_result['male_paternal_urinary_tract']) && $select_result['male_paternal_urinary_tract'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_paternal_urinary_tract"   value="No"  <?php if(isset($select_result['male_paternal_urinary_tract']) && $select_result['male_paternal_urinary_tract'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_urinary_tract']) && $select_result['male_paternal_urinary_tract'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_paternal_urinary_tract_text'])?$select_result['male_paternal_urinary_tract_text']:""; ?>"     maxlength="25" name="male_paternal_urinary_tract_text">
								</td>
							</tr>
							<tr>
								<td>Psychiatric/neurological</td>
								<td>
									<input type="radio"  name="male_maternal_neurological"  value="Yes"  <?php if(isset($select_result['male_maternal_neurological']) && $select_result['male_maternal_neurological'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_maternal_neurological"   value="No"  <?php if(isset($select_result['male_maternal_neurological']) && $select_result['male_maternal_neurological'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_neurological']) && $select_result['male_maternal_neurological'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_maternal_neurological_text'])?$select_result['male_maternal_neurological_text']:""; ?>"     maxlength="25" name="male_maternal_neurological_text">
								</td>
								<td>
									<input type="radio"  name="male_paternal_neurological"  value="Yes"  <?php if(isset($select_result['male_paternal_neurological']) && $select_result['male_paternal_neurological'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_paternal_neurological"   value="No"  <?php if(isset($select_result['male_paternal_neurological']) && $select_result['male_paternal_neurological'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_paternal_neurological']) && $select_result['male_paternal_neurological'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_paternal_neurological_text'])?$select_result['male_paternal_neurological_text']:""; ?>"     maxlength="25" name="male_paternal_neurological_text">
								</td>
							</tr>
							<tr>
								<td>Other/malignancy</td>
								<td>
									<input type="radio"  name="male_maternal_malignancy"  value="Yes"  <?php if(isset($select_result['male_maternal_malignancy']) && $select_result['male_maternal_malignancy'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_maternal_malignancy"   value="No"  <?php if(isset($select_result['male_maternal_malignancy']) && $select_result['male_maternal_malignancy'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['male_maternal_malignancy']) && $select_result['male_maternal_malignancy'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_maternal_malignancy_text'])?$select_result['male_maternal_malignancy_text']:""; ?>"     maxlength="25" name="male_maternal_malignancy_text">
								</td>
								<td>
									<input type="radio"  name="male_paternal_malignancy"  value="Yes"  <?php if(isset($select_result['children']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="male_paternal_malignancy"   value="No"  <?php if(isset($select_result['children']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['children']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['male_paternal_malignancy_text'])?$select_result['male_paternal_malignancy_text']:""; ?>"     maxlength="25" name="male_paternal_malignancy_text">
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
								<td>UNDERGONE IVF CYCLES</td>
								<td>
									<input type="radio"  name="surrogacy_cycles_before"  value="Yes"  <?php if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="surrogacy_cycles_before"   value="No"  <?php if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['surrogacy_cycles_before_text'])?$select_result['surrogacy_cycles_before_text']:""; ?>"     maxlength="50" name="surrogacy_cycles_before_text">
								</td>
							</tr>
							<tr>
								<td>UNDERGONE DONOR EGG CYCLES BEFORE</td>
								<td>
									<input type="radio"  name="egg_cycles_before"  value="Yes"  <?php if(isset($select_result['egg_cycles_before']) && $select_result['egg_cycles_before'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="egg_cycles_before"   value="No"  <?php if(isset($select_result['egg_cycles_before']) && $select_result['egg_cycles_before'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['egg_cycles_before']) && $select_result['egg_cycles_before'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['egg_cycles_before_text'])?$select_result['egg_cycles_before_text']:""; ?>"     maxlength="50" name="egg_cycles_before_text">
								</td>
							</tr>
							<tr>
								<td>UNDERGONE SURROGACY CYCLES BEFORE</td>
								<td>
									<input type="radio"  name="surrogacy_cycles_before"  value="Yes"  <?php if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="surrogacy_cycles_before"   value="No"  <?php if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['surrogacy_cycles_before_text'])?$select_result['surrogacy_cycles_before_text']:""; ?>"     maxlength="50" name="surrogacy_cycles_before_text">
								</td>
							</tr>
						</table>
					</td>
					<td style="padding: 0;">
						<table width="100%">
						<tr>
								<td>UNDERGONE IVF CYCLES</td>
								<td>
									<input type="radio"  name="surrogacy_cycles_before"  value="Yes"  <?php if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="surrogacy_cycles_before"   value="No"  <?php if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['surrogacy_cycles_before_text'])?$select_result['surrogacy_cycles_before_text']:""; ?>"     maxlength="50" name="surrogacy_cycles_before_text">
								</td>
							</tr>
							<tr>
								<td>UNDERGONE DONOR EGG CYCLES BEFORE</td>
								<td>
									<input type="radio"  name="egg_cycles_before"  value="Yes"  <?php if(isset($select_result['egg_cycles_before']) && $select_result['egg_cycles_before'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="egg_cycles_before"   value="No"  <?php if(isset($select_result['egg_cycles_before']) && $select_result['egg_cycles_before'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['egg_cycles_before']) && $select_result['egg_cycles_before'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['egg_cycles_before_text'])?$select_result['egg_cycles_before_text']:""; ?>"     maxlength="50" name="egg_cycles_before_text">
								</td>
							</tr>
							<tr>
								<td>UNDERGONE SURROGACY CYCLES BEFORE</td>
								<td>
									<input type="radio"  name="surrogacy_cycles_before"  value="Yes"  <?php if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="surrogacy_cycles_before"   value="No"  <?php if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['surrogacy_cycles_before']) && $select_result['surrogacy_cycles_before'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['surrogacy_cycles_before_text'])?$select_result['surrogacy_cycles_before_text']:""; ?>"     maxlength="50" name="surrogacy_cycles_before_text">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>RELATIONSHIP HISTORY</th>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>level of stress or responsibility/demands</td>
								<td><input  type="text" value="<?php echo isset($select_result['stress_level'])?$select_result['stress_level']:""; ?>"     maxlength="50" name="stress_level"></td>
							</tr>
							<tr>
								<td>SUPPORT FROM HUSBAND/WIFE:</td>
								<td><input  type="text" value="<?php echo isset($select_result['support_from_husband'])?$select_result['support_from_husband']:""; ?>"     maxlength="50" name="support_from_husband"></td>
							</tr>
							<tr>
								<td>SUPPORT FROM IMMEDIATE FAMILY MEMBERS:</td>
								<td><input  type="text" value="<?php echo isset($select_result['support_from_family'])?$select_result['support_from_family']:""; ?>"     maxlength="50" name="support_from_family"></td>
							</tr>
							<tr>
								<td>INTERPERSONAL CONFLICTS BETWEEN COUPLE:</td>
								<td><input  type="text" value="<?php echo isset($select_result['conflicts'])?$select_result['conflicts']:""; ?>"     maxlength="50" name="conflicts"></td>
							</tr>
						</table>
					</td>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>level of stress or responsibility/demands</td>
								<td><input  type="text" value="<?php echo isset($select_result['stress_level_2'])?$select_result['stress_level_2']:""; ?>"     maxlength="50" name="stress_level_2"></td>
							</tr>
							<tr>
								<td>SUPPORT FROM HUSBAND/WIFE:</td>
								<td><input  type="text" value="<?php echo isset($select_result['support_from_husband_2'])?$select_result['support_from_husband_2']:""; ?>"     maxlength="50" name="support_from_husband_2"></td>
							</tr>
							<tr>
								<td>SUPPORT FROM IMMEDIATE FAMILY MEMBERS:</td>
								<td><input  type="text" value="<?php echo isset($select_result['support_from_family_2'])?$select_result['support_from_family_2']:""; ?>"     maxlength="50" name="support_from_family_2"></td>
							</tr>
							<tr>
								<td>INTERPERSONAL CONFLICTS BETWEEN COUPLE:</td>
								<td><input  type="text" value="<?php echo isset($select_result['conflicts_2'])?$select_result['conflicts_2']:""; ?>"     maxlength="50" name="conflicts_2"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th>MENTAL HISTORY</th>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>BOUTS OF DEPRESSION</td>
								<td><input  type="text" value="<?php echo isset($select_result['depression'])?$select_result['depression']:""; ?>"     maxlength="50" name="depression"></td>
							</tr>
							<tr>
								<td>BOUTS OF ANXIETY</td>
								<td><input  type="text" value="<?php echo isset($select_result['anxiety'])?$select_result['anxiety']:""; ?>"     maxlength="50" name="anxiety"></td>
							</tr>
							<tr>
								<td>MOOD AND AFFECT</td>
								<td><input  type="text" value="<?php echo isset($select_result['affect'])?$select_result['affect']:""; ?>"     maxlength="50" name="affect"></td>
							</tr>
							<tr>
								<td>SUCIDAL TENDENCIES</td>
								<td><input  type="text" value="<?php echo isset($select_result['sucidal'])?$select_result['sucidal']:""; ?>"     maxlength="50" name="sucidal"></td>
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
									<input type="radio"  name="nutritional_assessment" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "Obese"){echo 'checked="checked"'; }?> value="Obese">
									<label>Obese</label>
									<input type="radio"  name="nutritional_assessment" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "average built"){echo 'checked="checked"'; }?> value="average built">
									<label>average built</label>
									<input type="radio"  name="nutritional_assessment" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "thin"){echo 'checked="checked"'; }?> value="thin">
									<label>thin</label>
									<input type="radio"  name="nutritional_assessment" <?php if(isset($select_result['nutritional_assessment']) && $select_result['nutritional_assessment'] == "cachexic"){echo 'checked="checked"'; }?> value="cachexic">
									<label>cachexic</label>
								</td>
							</tr>
							<tr>
								<td>Psychological assessment</td>
								<td>
									<input type="radio"  name="psychological_assessment" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "Anxious"){echo 'checked="checked"'; }?> value="Anxious">
									<label>Anxious</label>
									<input type="radio"  name="psychological_assessment" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "combative"){echo 'checked="checked"'; }?> value="combative">
									<label>combative</label>
									<input type="radio"  name="psychological_assessment" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "depressed"){echo 'checked="checked"'; }?> value="depressed">
									<label>depressed</label>
									<input type="radio"  name="psychological_assessment" <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "normal"){echo 'checked="checked"'; }?> value="normal">
									<label>normal</label>
								</td>
							</tr>
							<tr>
								<td>Pulse (PER MIN)</td>
								<td><input  type="number" value="<?php echo isset($select_result['pulse'])?$select_result['pulse']:""; ?>"     min="0" name="pulse" class="form-control"></td>
							</tr>
							<tr>
								<td>Blood pressure (MM HG)</td>
								<td>
									<table width="100%">
										<tr>
											<td>SYSTOLIC</td>
											<td>
												<input  type="number" value="<?php echo isset($select_result['systolic'])?$select_result['systolic']:""; ?>"     min="0" name="systolic" class="form-control">
											</td>
										</tr>
										<tr>
											<td>DIASTOLIC</td>
											<td>
												<input  type="number" value="<?php echo isset($select_result['diastolic'])?$select_result['diastolic']:""; ?>"     min="0" name="diastolic" class="form-control">
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>Temperature(DEG F):</td>
								<td><input  type="text" value="<?php echo isset($select_result['temperature'])?$select_result['temperature']:""; ?>"     name="temperature" class="form-control"></td>
							</tr>
							<tr>
								<td>CVS</td>
								<td>
									<input type="radio"  name="cvs"  value="Yes"  <?php if(isset($select_result['cvs']) && $select_result['cvs'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="cvs"   value="No"  <?php if(isset($select_result['cvs']) && $select_result['cvs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cvs']) && $select_result['cvs'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['cvs_text'])?$select_result['cvs_text']:""; ?>"     maxlength="20" name="cvs_text">
								</td>
							</tr>
							<tr>
								<td>Chest</td>
								<td>
									<input type="radio"  name="chest"  value="Yes"  <?php if(isset($select_result['chest']) && $select_result['chest'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="chest"   value="No"  <?php if(isset($select_result['chest']) && $select_result['chest'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['chest']) && $select_result['chest'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['chest_text'])?$select_result['chest_text']:""; ?>"     maxlength="20" name="chest_text">
								</td>
							</tr>
							<tr>
								<td>Abdomen</td>
								<td>
									<input type="radio"  name="abdomen"  value="Yes"  <?php if(isset($select_result['abdomen']) && $select_result['abdomen'] == "Yes"){echo 'checked="checked"'; }?>  >
									<label>Yes</label>
									<input type="radio"  name="abdomen"   value="No"  <?php if(isset($select_result['abdomen']) && $select_result['abdomen'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['abdomen']) && $select_result['abdomen'] != "Yes"){echo 'checked="checked"';}?>  >
									<label>No</label>
									<input  type="text" value="<?php echo isset($select_result['abdomen_text'])?$select_result['abdomen_text']:""; ?>"     maxlength="20" name="abdomen_text">
								</td>
							</tr>
							<tr>
								<td>Others</td>
								<td><input  type="text" value="<?php echo isset($select_result['others2'])?$select_result['others2']:""; ?>"     maxlength="20" name="others2" class="form-control"></td>
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
								<td><input  type="text" value="<?php echo isset($select_result['appearance'])?$select_result['appearance']:""; ?>"     maxlength="20" name="appearance"></td>
							</tr>
							<tr>
								<td>self-care (diet, exercise, sleep)</td>
								<td><input  type="text" value="<?php echo isset($select_result['self_care'])?$select_result['self_care']:""; ?>"     maxlength="50" name="self_care"></td>
							</tr>
							<tr>
								<td>General Behaviour</td>
								<td><input  type="text" value="<?php echo isset($select_result['general_behaviour'])?$select_result['general_behaviour']:""; ?>"     maxlength="50" name="general_behaviour"></td>
							</tr>
							<tr>
								<td>Level of Consciousness</td>
								<td><input  type="text" value="<?php echo isset($select_result['consciousness'])?$select_result['consciousness']:""; ?>"     maxlength="20" name="consciousness"></td>
							</tr>
							<tr>
								<td>Orientation</td>
								<td><input  type="text" value="<?php echo isset($select_result['orientation'])?$select_result['orientation']:""; ?>"     maxlength="20" name="orientation"></td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
				<tr>
					<th>TAKEN UP FOR IVF/THIRD PARTY REPRODUCTION PROGRAMME</th>
					<td>
						<input type="radio"  name="third_party_reproduction"  value="Yes"  <?php if(isset($select_result['third_party_reproduction']) && $select_result['third_party_reproduction'] == "Yes"){echo 'checked="checked"'; }?>  >
						<label>Yes</label>
						<input type="radio"  name="third_party_reproduction"   value="No"  <?php if(isset($select_result['third_party_reproduction']) && $select_result['third_party_reproduction'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['third_party_reproduction']) && $select_result['third_party_reproduction'] != "Yes"){echo 'checked="checked"';}?>  >
						<label>No</label>
			<input type="text" maxlength="50" name="third_party_reproduction_text" class="form-control" value="<?php echo isset($select_result['third_party_reproduction_text'])?$select_result['third_party_reproduction_text']:""; ?>"  >
			<input type="text" maxlength="50" name="type" class="form-control" value="First Cycle">
						
						
						
					</td>
					<td></td>
				</tr>
			</table>
			<!-- /.card-body -->
			<div class="card-footer">
				<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
				<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
			</div>
		</form>
		
		
		
		
		
		




<div class="clearfix"> </div>




<!-- print     ------ -->



<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 	




	<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PSYCHOLOGICAL EVALUATION SHEET</h3></td>
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
			    
			    <?php 
				$female_photographs=$select_result['female_photographs'];
				$male_photographs=$select_result['male_photographs'];
				
					?>
				<tr>
					<th style="border:1px solid #cdcdcd;"> </th>
					<td  style="border:1px solid #cdcdcd;"><b>FEMALE</b></td>
					<td  style="border:1px solid #cdcdcd;"><b>MALE</b></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">PHOTOGRAPHS</th>
					<td style="padding: 0; border:1px solid #cdcdcd;"> 
				
				<?php if(!empty($female_photographs)) {?>
				 <img src="<?php echo $female_photographs;?>" style="width:100px; height:100px;">
					<?php } else {echo " ";}?>
				
					</td>
					
					<td    style="border:1px solid #cdcdcd; padding: 0;"> 
					
					<?php if(!empty($male_photographs)) {?>
					
					
					 <img src="<?php echo $male_photographs;?>" style="width:100px; height:100px;">
					
					<?php } else {echo " ";}?>
					
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">NAME</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">  <?php echo isset($select_result['female_name'])?$select_result['female_name']:""; ?> </td>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_name'])?$select_result['male_name']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">AGE (Years)</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"> <?php echo isset($select_result['female_age'])?$select_result['female_age']:""; ?> </td>
					<td style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_age'])?$select_result['male_age']:""; ?> </td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">OCCUPATION</th>
					<td style="border:1px solid #cdcdcd; padding: 0;"> <?php echo isset($select_result['female_occupation'])?$select_result['female_occupation']:""; ?> </td>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_occupation'])?$select_result['male_occupation']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">NATIONALITY</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_nationality'])?$select_result['female_nationality']:""; ?></td>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_nationality'])?$select_result['male_nationality']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">ETHNICITY</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_religion'])?$select_result['female_religion']:""; ?></td>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_religion'])?$select_result['male_religion']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">AADHAR NUMBER</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_aadhar_number'])?$select_result['female_aadhar_number']:""; ?></td>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_aadhar_number'])?$select_result['male_aadhar_number']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">PRESENT ADDRESS</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_present_address'])?$select_result['female_present_address']:""; ?></td>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_present_address'])?$select_result['male_present_address']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">PERMANENT ADDRESS</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_permanent_address'])?$select_result['female_permanent_address']:""; ?></td>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_permanent_address'])?$select_result['male_permanent_address']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">CONTACT NO</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_mobile_no'])?$select_result['female_mobile_no']:""; ?></td>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_mobile_no'])?$select_result['male_mobile_no']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">EMAIL ADDRESS</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['female_email_address'])?$select_result['female_email_address']:""; ?></td>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['male_email_address'])?$select_result['male_email_address']:""; ?></td>
				</tr>
				<tr>
					<th>WT (kg)/HT(m) /BMI(kg/m2)</th>
					<td><input  type="text" value="<?php echo isset($select_result['female_wt_ht_bmi'])?$select_result['female_wt_ht_bmi']:""; ?>"     maxlength="50" name="female_wt_ht_bmi" class="form-control"></td>
					<td><input  type="text" value="<?php echo isset($select_result['male_wt_ht_bmi'])?$select_result['male_wt_ht_bmi']:""; ?>"     maxlength="50" name="male_wt_ht_bmi" class="form-control"></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">MARITAL STATUS</th>
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
					<th style="border:1px solid #cdcdcd;">H/O previous pregnancies(IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">Total pregnancies</td>
								<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_total_pregnancies'])?$select_result['female_total_pregnancies']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">No.of live births</td>
								<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_live_births'])?$select_result['female_live_births']:""; ?> </td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">No.of spontaneous abortions</td>
								<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_spontaneous_abortions'])?$select_result['female_spontaneous_abortions']:""; ?> </td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">No.of termination of pregnancy</td>
								<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_termination_of_pregnancy'])?$select_result['female_termination_of_pregnancy']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">No.of still births</td>
								<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_still_births'])?$select_result['female_still_births']:""; ?> </td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">No. of ectopic pregnancy</td>
								<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['female_ectopic_pregnancy'])?$select_result['female_ectopic_pregnancy']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">History of any abnormality in child</td>
								<td  style="border:1px solid #cdcdcd;">
								
							<?php if(isset($select_result['female_history_of_abnormality']) && $select_result['female_history_of_abnormality'] == "Yes"){echo 'yes'; }?>	 <br>

<?php echo isset($select_result['female_history_of_abnormality_text'])?$select_result['female_history_of_abnormality_text']:""; ?>
							
																	
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Others</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['female_others'])?$select_result['female_others']:""; ?> </td>
							</tr>
						</table>
					</td>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">Total pregnancies INCLUDING PREVIOUS pregnancies</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_total_pregnancies'])?$select_result['male_total_pregnancies']:""; ?> </td>
							</tr>
							<tr>
								<td  colspan="2"  style="border:1px solid #cdcdcd;"><br><br><br><br><br><br><br><br><br><br><br><br><br></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">LEGAL PROBLEMS</th>
					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['legal_problems'])?$select_result['legal_problems']:""; ?></td>
					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['male_legal_problems'])?$select_result['male_legal_problems']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">PHYSICAL OR SEXUAL ABUSE HISTORY</th>
					<td  style="border:1px solid #cdcdcd;">
						
					<?php if(isset($select_result['sexual_abuse']) && $select_result['sexual_abuse'] == "Yes"){echo 'yes'; }?>
					<br> 
					<?php echo isset($select_result['sexual_abuse_text'])?$select_result['sexual_abuse_text']:""; ?>     
					
					</td>
					<td  style="border:1px solid #cdcdcd;">
						
					<?php if(isset($select_result['male_sexual_abuse']) && $select_result['male_sexual_abuse'] == "Yes"){echo 'yes'; }?>
					<br> 
					<?php echo isset($select_result['male_sexual_abuse_text'])?$select_result['male_sexual_abuse_text']:""; ?>     
					
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">AWARENESS ABOUT THE PROCEDURE</th>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">why do you want to undergo ivf cycle/be an egg /sperm donor/surrogate</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['egg_donor'])?$select_result['egg_donor']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Have you volunteered or forced?</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['volunteered'])?$select_result['volunteered']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">With whom have you chosen to disclose, and are they supportive?</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['supportive'])?$select_result['supportive']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Do you understand the medical treatment (daily injections, scans, retrieval)?</td>
								<td  style="border:1px solid #cdcdcd;">
									  
  <?php if(isset($select_result['medical_treatment']) && $select_result['medical_treatment'] == "Yes"){echo 'yes'; }?>
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">If you have trouble with injections, do you have someone who can help?</td>
								<td  style="border:1px solid #cdcdcd;">
																	
								
				 <?php if(isset($select_result['trouble_with_injections']) && $select_result['trouble_with_injections'] == "Yes"){echo 'yes'; }?>				
								
								</td>
							</tr>
						</table>
					</td>
					<td style="padding: 0;">
						<table width="100%">
							<tr>
								<td>why do you want to undergo ivf cycle/be an egg /sperm donor/surrogate</td>
								<td><input  type="text" value="<?php echo isset($select_result['egg_donor_2'])?$select_result['egg_donor_2']:""; ?>"     maxlength="50" name="egg_donor_2"></td>
							</tr>
							<tr>
								<td>Have you volunteered or forced?</td>
								<td><input  type="text" value="<?php echo isset($select_result['volunteered_2'])?$select_result['volunteered_2']:""; ?>"     maxlength="50" name="volunteered_2"></td>
							</tr>
							<tr>
								<td>With whom have you chosen to disclose, and are they supportive?</td>
								<td><input  type="text" value="<?php echo isset($select_result['supportive_2'])?$select_result['supportive_2']:""; ?>"     maxlength="50" name="supportive_2"></td>
							</tr>
							<tr>
								<td>Do you understand the medical treatment (daily injections, scans, retrieval)?</td>
								<td>
									<input type="radio"  name="medical_treatment_2"  value="Yes"  <?php if(isset($select_result['medical_treatment_2']) && $select_result['medical_treatment_2'] == "Yes"){echo 'checked="checked"'; }?>  > Yes &nbsp;
									<input type="radio"  name="medical_treatment_2"   value="No"  <?php if(isset($select_result['medical_treatment_2']) && $select_result['medical_treatment_2'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['medical_treatment_2']) && $select_result['medical_treatment_2'] != "Yes"){echo 'checked="checked"';}?>  > No &nbsp;
								</td>
							</tr>
							<tr>
								<td>If you have trouble with injections, do you have someone who can help?</td>
								<td>
									<input type="radio"  name="trouble_with_injections_2"  value="Yes"  <?php if(isset($select_result['trouble_with_injections_2']) && $select_result['trouble_with_injections_2'] == "Yes"){echo 'checked="checked"'; }?>  > Yes &nbsp;
									<input type="radio"  name="trouble_with_injections_2"   value="No"  <?php if(isset($select_result['trouble_with_injections_2']) && $select_result['trouble_with_injections_2'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['trouble_with_injections_2']) && $select_result['trouble_with_injections_2'] != "Yes"){echo 'checked="checked"';}?>  > No &nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">PAST MEDICAL HISTORY</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
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
																
								
<?php if(isset($select_result['other_heart_disease']) && $select_result['other_heart_disease'] == "Yes"){echo 'yes'; }?>						<br> 
<?php echo isset($select_result['other_heart_disease_text'])?$select_result['other_heart_disease_text']:""; ?>
									
								</td>
								<td  style="border:1px solid #cdcdcd;">Other heart disease</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																	
									
<?php if(isset($select_result['high_blood_pressure']) && $select_result['high_blood_pressure'] == "Yes"){echo 'yes'; }?>	<br> 
<?php echo isset($select_result['high_blood_pressure_text'])?$select_result['high_blood_pressure_text']:""; ?>						
									
								</td>
								<td  style="border:1px solid #cdcdcd;">High blood pressure</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																
<?php if(isset($select_result['blood_clots']) && $select_result['blood_clots'] == "Yes"){echo 'yes'; }?>
<br>
<?php echo isset($select_result['blood_clots_text'])?$select_result['blood_clots_text']:""; ?>

							</td>
								<td  style="border:1px solid #cdcdcd;">Blood clots</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
				<?php if(isset($select_result['chest_pain']) && $select_result['chest_pain'] == "Yes"){echo 'yes'; }?>
				<br>
				<?php echo isset($select_result['chest_pain_text'])?$select_result['chest_pain_text']:""; ?>
								</td>
								<td  style="border:1px solid #cdcdcd;">Chest pain</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																	
			<?php if(isset($select_result['stroke']) && $select_result['stroke'] == "Yes"){echo 'yes'; }?>					
					<br> 
<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>					
								</td>
								<td  style="border:1px solid #cdcdcd;">Stroke</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																		
	<?php if(isset($select_result['asthma']) && $select_result['asthma'] == "Yes"){echo 'yes'; }?>					<br> 
<?php echo isset($select_result['asthma_text'])?$select_result['asthma_text']:""; ?>	
								</td>
								<td  style="border:1px solid #cdcdcd;">Asthma</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
															
<?php if(isset($select_result['lung_disease']) && $select_result['lung_disease'] == "Yes"){echo 'yes'; }?>
<br>
<?php echo isset($select_result['lung_disease_text'])?$select_result['lung_disease_text']:""; ?>
							</td>
								<td  style="border:1px solid #cdcdcd;">Other lung disease</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																
<?php if(isset($select_result['difficulty_breathing']) && $select_result['difficulty_breathing'] == "Yes"){echo 'yes'; }?>
<br>
<?php echo isset($select_result['difficulty_breathing_text'])?$select_result['difficulty_breathing_text']:""; ?>
							</td>
								<td  style="border:1px solid #cdcdcd;">Difficulty breathing</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
								
<?php if(isset($select_result['snoring']) && $select_result['snoring'] == "Yes"){echo 'yes'; }?>
<br>
 <?php echo isset($select_result['snoring_text'])?$select_result['snoring_text']:""; ?>
							</td>
								<td  style="border:1px solid #cdcdcd;">Sleep apnea or snoring</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																	
	<?php if(isset($select_result['epilepsy']) && $select_result['epilepsy'] == "Yes"){echo 'yes'; }?>
	<br> <?php echo isset($select_result['epilepsy_text'])?$select_result['epilepsy_text']:""; ?>														
								</td>
								<td  style="border:1px solid #cdcdcd;">Epilepsy or seizures</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
			
<?php if(isset($select_result['fainting_spells_text']) && $select_result['fainting_spells_text'] == "Yes"){echo 'yes'; }?>					<br> <?php echo isset($select_result['fainting_spells_text'])?$select_result['fainting_spells_text']:""; ?>										
								</td>
								<td  style="border:1px solid #cdcdcd;">Fainting spells</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
<?php if(isset($select_result['diabetes']) && $select_result['diabetes'] == "Yes"){echo 'yes'; }?>		<br> <?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>						
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Diabetes</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
<?php if(isset($select_result['muscle_disorders']) && $select_result['muscle_disorders'] == "Yes"){echo 'yes'; }?>					<br> <?php echo isset($select_result['diabetes_text'])?$select_result['diabetes_text']:""; ?>

<?php echo isset($select_result['muscle_disorders_text'])?$select_result['muscle_disorders_text']:""; ?>
				
								</td>
								<td  style="border:1px solid #cdcdcd;">Muscle disorders</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
	
							
<?php if(isset($select_result['kidney_disease']) && $select_result['kidney_disease'] == "Yes"){echo 'yes'; }?>
<br> <?php echo isset($select_result['kidney_disease_text'])?$select_result['kidney_disease_text']:""; ?>
							</td>
								<td  style="border:1px solid #cdcdcd;">Kidney disease</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																
<?php if(isset($select_result['hepatitis']) && $select_result['hepatitis'] == "Yes"){echo 'yes'; }?>
<br> <?php echo isset($select_result['hepatitis_text'])?$select_result['hepatitis_text']:""; ?>
							</td>
								<td  style="border:1px solid #cdcdcd;">Hepatitis</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">

	<?php if(isset($select_result['tuberculosis']) && $select_result['tuberculosis'] == "Yes"){echo 'yes'; }?>						
<br> <?php echo isset($select_result['tuberculosis_text'])?$select_result['tuberculosis_text']:""; ?>
							</td>
								<td  style="border:1px solid #cdcdcd;">Tuberculosis</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
			<?php if(isset($select_result['hiv']) && $select_result['hiv'] == "Yes"){echo 'yes'; }?>						
<br> <?php echo isset($select_result['hiv_text'])?$select_result['hiv_text']:""; ?>
							</td>
								<td  style="border:1px solid #cdcdcd;">HIV</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
								
		<?php if(isset($select_result['heart_burn']) && $select_result['heart_burn'] == "Yes"){echo 'yes'; }?>								
			<br> <?php echo isset($select_result['heart_burn_text'])?$select_result['heart_burn_text']:""; ?>					
								</td>
								<td  style="border:1px solid #cdcdcd;">Heart burn/reflux</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
														
<?php if(isset($select_result['cancer']) && $select_result['cancer'] == "Yes"){echo 'yes'; }?>

	<br> <?php echo isset($select_result['cancer_text'])?$select_result['cancer_text']:""; ?>						</td>
								<td  style="border:1px solid #cdcdcd;">Cancer</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
			
<?php if(isset($select_result['blood_disorders']) && $select_result['blood_disorders'] == "Yes"){echo 'yes'; }?>					
<br> <?php echo isset($select_result['blood_disorders_text'])?$select_result['blood_disorders_text']:""; ?>								
								</td>
								<td  style="border:1px solid #cdcdcd;">Blood disorders</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
	
	<?php if(isset($select_result['rheumatic_disease']) && $select_result['rheumatic_disease'] == "Yes"){echo 'yes'; }?>
	<br> 

<?php echo isset($select_result['rheumatic_disease_text'])?$select_result['rheumatic_disease_text']:""; ?>	
								</td>
								<td  style="border:1px solid #cdcdcd;">Rheumatic disease</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
			
			<?php if(isset($select_result['psychiatric_disorder']) && $select_result['psychiatric_disorder'] == "Yes"){echo 'yes'; }?>			
		<br>	
		<?php echo isset($select_result['psychiatric_disorder_text'])?$select_result['psychiatric_disorder_text']:""; ?>	
			
			
			</td>
								
								
								
								<td  style="border:1px solid #cdcdcd;">Psychiatric disorder</td>
							
							
							
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
		<?php if(isset($select_result['thyroid_disorder']) && $select_result['thyroid_disorder'] == "Yes"){echo 'yes'; }?>	<br> <?php echo isset($select_result['thyroid_disorder_text'])?$select_result['thyroid_disorder_text']:""; ?>					
		
		</td>
								<td  style="border:1px solid #cdcdcd;">Thyroid disorder</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
							
	<?php if(isset($select_result['urinary_infection']) && $select_result['urinary_infection'] == "Yes"){echo 'yes'; }?>
<br> <?php echo isset($select_result['urinary_infection_text'])?$select_result['urinary_infection_text']:""; ?>
							</td>
								<td  style="border:1px solid #cdcdcd;">Urinary infection</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																	
		<?php if(isset($select_result['sexually_transmitted']) && $select_result['sexually_transmitted'] == "Yes"){echo 'yes'; }?>
		<br> <?php echo isset($select_result['sexually_transmitted_text'])?$select_result['sexually_transmitted_text']:""; ?>					
								</td>
								<td  style="border:1px solid #cdcdcd;">Sexually transmitted disease</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['impairments'])?$select_result['impairments']:""; ?> 
							<br> <?php echo isset($select_result['impairments'])?$select_result['impairments']:""; ?>	
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Other medical condition or impairments</td>
							</tr>
						</table>
					</td>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">Heart attack</td>
								<td  style="border:1px solid #cdcdcd;">
																
						<?php if(isset($select_result['male_heart_attack']) && $select_result['male_heart_attack'] == "Yes"){echo 'yes'; }?>		
								
						<br> <?php echo isset($select_result['male_heart_attack_text'])?$select_result['male_heart_attack_text']:""; ?>		
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Pacemaker</td>
								<td  style="border:1px solid #cdcdcd;">
																	
						<?php if(isset($select_result['male_pacemaker']) && $select_result['male_pacemaker'] == "Yes"){echo 'yes'; }?>		
					<br> <?php echo isset($select_result['male_pacemaker_text'])?$select_result['male_pacemaker_text']:""; ?>			
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Other heart disease</td>
								<td  style="border:1px solid #cdcdcd;">
									
								<?php if(isset($select_result['male_other_heart_disease']) && $select_result['male_other_heart_disease'] == "Yes"){echo 'yes'; }?>
							<br> <?php echo isset($select_result['male_other_heart_disease_text'])?$select_result['male_other_heart_disease_text']:""; ?>	
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">High blood pressure</td>
								<td  style="border:1px solid #cdcdcd;">
								
								
					<?php if(isset($select_result['male_high_blood_pressure']) && $select_result['male_high_blood_pressure'] == "Yes"){echo 'yes'; }?>	 <br>
<?php echo isset($select_result['male_high_blood_pressure_text'])?$select_result['male_high_blood_pressure_text']:""; ?>					
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Blood clots</td>
								<td  style="border:1px solid #cdcdcd;">
																	
							<?php if(isset($select_result['male_blood_clots']) && $select_result['male_blood_clots'] == "Yes"){echo 'yes'; }?>		
			<br> <?php echo isset($select_result['male_blood_clots_text'])?$select_result['male_blood_clots_text']:""; ?>					
							


							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Chest pain</td>
								<td  style="border:1px solid #cdcdcd;">
									
								<?php if(isset($select_result['male_chest_pain']) && $select_result['male_chest_pain'] == "Yes"){echo 'yes'; }?>		
			<br> <?php echo isset($select_result['male_chest_pain_text'])?$select_result['male_chest_pain_text']:""; ?>					
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Stroke</td>
								<td  style="border:1px solid #cdcdcd;">
								
								<?php if(isset($select_result['male_stroke']) && $select_result['male_stroke'] == "Yes"){echo 'yes'; }?>

		<br> <?php echo isset($select_result['male_stroke_text'])?$select_result['male_stroke_text']:""; ?>						
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Asthma</td>
								<td  style="border:1px solid #cdcdcd;">
									
								<?php if(isset($select_result['male_asthma']) && $select_result['male_asthma'] == "Yes"){echo 'yes'; }?>		
			<br> <?php echo isset($select_result['male_asthma_text'])?$select_result['male_asthma_text']:""; ?>					
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Other lung disease</td>
								<td  style="border:1px solid #cdcdcd;">
														

	<?php if(isset($select_result['male_lung_disease_text']) && $select_result['male_lung_disease_text'] == "Yes"){echo 'yes'; }?> <br>
	<?php echo isset($select_result['male_lung_disease_text'])?$select_result['male_lung_disease_text']:""; ?>


							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Difficulty breathing</td>
								<td  style="border:1px solid #cdcdcd;">
																	
		<?php if(isset($select_result['male_difficulty_breathing']) && $select_result['male_difficulty_breathing'] == "Yes"){echo 'yes'; }?> <br>
<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>		
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Sleep apnea or snoring</td>
								<td  style="border:1px solid #cdcdcd;">
																
		<?php if(isset($select_result['male_snoring']) && $select_result['male_snoring'] == "Yes"){echo 'yes'; }?>								
			<br>	<?php echo isset($select_result['male_snoring_text'])?$select_result['male_snoring_text']:""; ?>				
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Epilepsy or seizures</td>
								<td  style="border:1px solid #cdcdcd;">
																	
<?php if(isset($select_result['male_epilepsy']) && $select_result['male_epilepsy'] == "Yes"){echo 'yes'; }?>			
<br> <?php echo isset($select_result['male_epilepsy_text'])?$select_result['male_epilepsy_text']:""; ?>								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Fainting spells</td>
								<td  style="border:1px solid #cdcdcd;">
																
			<?php if(isset($select_result['male_fainting_spells']) && $select_result['male_fainting_spells'] == "Yes"){echo 'yes'; }?>	
<br> <?php echo isset($select_result['male_fainting_spells_text'])?$select_result['male_fainting_spells_text']:""; ?>
			
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Diabetes</td>
								<td  style="border:1px solid #cdcdcd;">
								
			<?php if(isset($select_result['male_diabetes']) && $select_result['male_diabetes'] == "Yes"){echo 'yes'; }?><br><?php echo isset($select_result['male_diabetes_text'])?$select_result['male_diabetes_text']:""; ?> 						
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Muscle disorders</td>
								<td  style="border:1px solid #cdcdcd;">
														
						<?php if(isset($select_result['male_muscle_disorders']) && $select_result['male_muscle_disorders'] == "Yes"){echo 'yes'; }?>					
			<br> <?php echo isset($select_result['male_muscle_disorders_text'])?$select_result['male_muscle_disorders_text']:""; ?>	

				
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Kidney disease</td>
								<td  style="border:1px solid #cdcdcd;">
														
<?php if(isset($select_result['male_kidney_disease']) && $select_result['male_kidney_disease'] == "Yes"){echo 'yes'; }?>	
<br> <?php echo isset($select_result['male_kidney_disease_text'])?$select_result['male_kidney_disease_text']:""; ?>
							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Hepatitis</td>
								<td  style="border:1px solid #cdcdcd;">
																
	<?php if(isset($select_result['male_hepatitis']) && $select_result['male_hepatitis'] == "Yes"){echo 'yes'; }?>	
															
		<br> <?php echo isset($select_result['male_hepatitis_text'])?$select_result['male_hepatitis_text']:""; ?>						</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Tuberculosis</td>
								<td  style="border:1px solid #cdcdcd;">
																	
			<?php if(isset($select_result['male_tuberculosis_text']) && $select_result['male_tuberculosis_text'] == "Yes"){echo 'yes'; }?> <br>
<?php echo isset($select_result['male_tuberculosis_text'])?$select_result['male_tuberculosis_text']:""; ?>			
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">HIV</td>
								<td  style="border:1px solid #cdcdcd;">
																	
																	
			<?php if(isset($select_result['male_hiv']) && $select_result['male_hiv'] == "Yes"){echo 'yes'; }?>
			
			<br><?php echo isset($select_result['male_hiv_text'])?$select_result['male_hiv_text']:""; ?> 						
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Heart burn/reflux</td>
								<td  style="border:1px solid #cdcdcd;">
								
<?php if(isset($select_result['male_heart_burn']) && $select_result['male_heart_burn'] == "Yes"){echo 'yes'; }?>						
	<br> <?php echo isset($select_result['male_heart_burn_text'])?$select_result['male_heart_burn_text']:""; ?>							
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Cancer </td>
								<td  style="border:1px solid #cdcdcd;">
																
	<?php if(isset($select_result['male_cancer']) && $select_result['male_cancer'] == "Yes"){echo 'yes'; }?>
	<br><?php echo isset($select_result['male_cancer_text'])?$select_result['male_cancer_text']:""; ?> 							
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Blood disorders</td>
								<td  style="border:1px solid #cdcdcd;">
		<?php if(isset($select_result['male_blood_disorders']) && $select_result['male_blood_disorders'] == "Yes"){echo 'yes'; }?>	
<br> <?php echo isset($select_result['male_blood_disorders_text'])?$select_result['male_blood_disorders_text']:""; ?>
		
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Rheumatic disease</td>
								<td  style="border:1px solid #cdcdcd;">
									
			<?php if(isset($select_result['male_rheumatic_disease']) && $select_result['male_rheumatic_disease'] == "Yes"){echo 'yes'; }?>	 <br>
<?php echo isset($select_result['male_rheumatic_disease_text'])?$select_result['male_rheumatic_disease_text']:""; ?>			
					</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Psychiatric disorder</td>
								<td  style="border:1px solid #cdcdcd;">
							<?php if(isset($select_result['male_psychiatric_disorder']) && $select_result['male_psychiatric_disorder'] == "Yes"){echo 'yes'; }?>							
				<br> <?php echo isset($select_result['male_psychiatric_disorder_text'])?$select_result['male_psychiatric_disorder_text']:""; ?>				
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Thyroid disorder</td>
								<td  style="border:1px solid #cdcdcd;">
								
								
								<?php if(isset($select_result['male_thyroid_disorder']) && $select_result['male_thyroid_disorder'] == "Yes"){echo 'yes'; }?>			
				<br> <?php echo isset($select_result['male_thyroid_disorder_text'])?$select_result['male_thyroid_disorder_text']:""; ?>				
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Urinary infection</td>
								<td  style="border:1px solid #cdcdcd;">
									<?php if(isset($select_result['male_urinary_infection']) && $select_result['male_urinary_infection'] == "Yes"){echo 'yes'; }?>	
									
								<br> <?php echo isset($select_result['male_urinary_infection_text'])?$select_result['male_urinary_infection_text']:""; ?>	
									
									
								
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Sexually transmitted disease</td>
								<td  style="border:1px solid #cdcdcd;">
									
<?php if(isset($select_result['male_sexually_transmitted']) && $select_result['male_sexually_transmitted'] == "Yes"){echo 'yes'; }?>	 <br>
<?php echo isset($select_result['male_sexually_transmitted_text'])?$select_result['male_sexually_transmitted_text']:""; ?>				
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Other medical condition or impairments</td>
								<td  style="border:1px solid #cdcdcd;">
									<?php echo isset($select_result['male_impairments'])?$select_result['male_impairments']:""; ?> <br>
									
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">PAST ANESTHESIA AND SURGICAL PROCEDURES</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">Laparoscopy/pelvic/abdominal operations</td>
								<td  style="border:1px solid #cdcdcd;">
									
	<?php if(isset($select_result['abdominal_operations']) && $select_result['abdominal_operations'] == "Yes"){echo 'yes'; }?>	<br> <?php echo isset($select_result['abdominal_operations_text'])?$select_result['abdominal_operations_text']:""; ?>					

							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Other operations</td>
								<td  style="border:1px solid #cdcdcd;">
									
	<?php if(isset($select_result['other_operations']) && $select_result['other_operations'] == "Yes"){echo 'yes'; }?>			<br> <?php echo isset($select_result['other_operations_text'])?$select_result['other_operations_text']:""; ?>				
								
								</td>
							</tr>
						</table>
					</td>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">Laparoscopy/pelvic/abdominal operations</td>
								<td  style="border:1px solid #cdcdcd;">
																
		<?php if(isset($select_result['male_abdominal_operations']) && $select_result['male_abdominal_operations'] == "Yes"){echo 'yes'; }?>	 <br> <?php echo isset($select_result['male_abdominal_operations_text'])?$select_result['male_abdominal_operations_text']:""; ?>						
									
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Other operations</td>
								<td  style="border:1px solid #cdcdcd;">
									
	<?php if(isset($select_result['male_other_operations_text']) && $select_result['male_other_operations_text'] == "Yes"){echo 'yes'; }?> <br> <?php echo isset($select_result['male_other_operations_text'])?$select_result['male_other_operations_text']:""; ?>


							</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">ALLERGY HISTORY</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">Medications</td>
								<td  style="border:1px solid #cdcdcd;">
									
	<?php if(isset($select_result['medications']) && $select_result['medications'] == "Yes"){echo 'yes'; }?>							
<br> <?php echo isset($select_result['medications_text'])?$select_result['medications_text']:""; ?>

							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">environmental factors</td>
								<td  style="border:1px solid #cdcdcd;">
									
	<?php if(isset($select_result['environmental_factors']) && $select_result['environmental_factors'] == "Yes"){echo 'yes'; }?>	 <br> <?php echo isset($select_result['environmental_factors_text'])?$select_result['environmental_factors_text']:""; ?>							
								
								</td>
							</tr>
						</table>
					</td>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">Medications</td>
								<td  style="border:1px solid #cdcdcd;">
									
		<?php if(isset($select_result['male_medications']) && $select_result['male_medications'] == "Yes"){echo 'yes'; }?><br><?php echo isset($select_result['male_medications_text'])?$select_result['male_medications_text']:""; ?> 							


							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">environmental factors</td>
								<td  style="border:1px solid #cdcdcd;">
									
				<?php if(isset($select_result['male_environmental_factors']) && $select_result['male_environmental_factors'] == "Yes"){echo 'yes'; }?>	<br>
<?php echo isset($select_result['male_environmental_factors_text'])?$select_result['male_environmental_factors_text']:""; ?>				
								
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">SOCIAL & DRUG INTAKE HISTORY</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td width="20%">
									
		<?php if(isset($select_result['dentures_text']) && $select_result['dentures_text'] == "Yes"){echo 'yes'; }?>
		<br> <?php echo isset($select_result['dentures_text'])?$select_result['dentures_text']:""; ?>						
								
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Dentures</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
		<?php if(isset($select_result['loose_teeth']) && $select_result['loose_teeth'] == "Yes"){echo 'yes'; }?> <br>	<?php echo isset($select_result['loose_teeth_text'])?$select_result['loose_teeth_text']:""; ?>					

							</td>
								<td  style="border:1px solid #cdcdcd;">Loose teeth</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
								
	<?php if(isset($select_result['hearing_aid']) && $select_result['hearing_aid'] == "Yes"){echo 'yes'; }?>	<br> <?php echo isset($select_result['hearing_aid_text'])?$select_result['hearing_aid_text']:""; ?>							
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Hearing aid</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
<?php if(isset($select_result['caps_on_front_teeth_text']) && $select_result['caps_on_front_teeth_text'] == "Yes"){echo 'yes'; }?>	 <br> <?php echo isset($select_result['caps_on_front_teeth_text'])?$select_result['caps_on_front_teeth_text']:""; ?>						
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Caps on front teeth</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
	<?php if(isset($select_result['contact_lenses']) && $select_result['contact_lenses'] == "Yes"){echo 'yes'; }?>
	<br><?php echo isset($select_result['contact_lenses_text'])?$select_result['contact_lenses_text']:""; ?>						
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Contact lenses</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
	<?php if(isset($select_result['body_piercing']) && $select_result['body_piercing'] == "Yes"){echo 'yes'; }?><br> <?php echo isset($select_result['body_piercing_text'])?$select_result['body_piercing_text']:""; ?>								
								
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Body piercing</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
<?php if(isset($select_result['blood_transfusion_text']) && $select_result['blood_transfusion_text'] == "Yes"){echo 'yes'; }?>	
<br> <?php echo isset($select_result['blood_transfusion_text'])?$select_result['blood_transfusion_text']:""; ?>

							</td>
								<td  style="border:1px solid #cdcdcd;">H/o blood transfusion</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
				<?php if(isset($select_result['traffic_accident_text']) && $select_result['traffic_accident_text'] == "Yes"){echo 'yes'; }?>	<br> 							
<?php echo isset($select_result['traffic_accident_text'])?$select_result['traffic_accident_text']:""; ?>
								
								</td>
								<td  style="border:1px solid #cdcdcd;">H/o road traffic accident/any injury</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
	<?php if(isset($select_result['smoke_past']) && $select_result['smoke_past'] == "Yes"){echo 'yes'; }?>	<br> <?php echo isset($select_result['smoke_past_text'])?$select_result['smoke_past_text']:""; ?>							
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Smoke(past)daily</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
								
	<?php if(isset($select_result['smoke_present']) && $select_result['smoke_present'] == "Yes"){echo 'yes'; }?><br>
<?php echo isset($select_result['smoke_present_text'])?$select_result['smoke_present_text']:""; ?>	</td>
								<td  style="border:1px solid #cdcdcd;">Smoke(present)daily</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																			
<?php if(isset($select_result['drink_past']) && $select_result['drink_past'] == "Yes"){echo 'yes'; }?>	<br> <?php echo isset($select_result['drink_past_text'])?$select_result['drink_past_text']:""; ?>						
							</td>
								<td  style="border:1px solid #cdcdcd;">Drink(past)units per week</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																		
<?php if(isset($select_result['drink_present_text']) && $select_result['drink_present_text'] == "Yes"){echo 'yes'; }?> <br>		<?php echo isset($select_result['drink_present_text'])?$select_result['drink_present_text']:""; ?>				
								</td>
								<td  style="border:1px solid #cdcdcd;">Drink(present)units per week</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
									
		<?php if(isset($select_result['abusive_drugs']) && $select_result['abusive_drugs'] == "Yes"){echo 'yes'; }?><br>						
		<?php echo isset($select_result['abusive_drugs_text'])?$select_result['abusive_drugs_text']:""; ?>					
								</td>
								<td  style="border:1px solid #cdcdcd;">Hashish/cocaine /abusive drugs</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
								
		<?php if(isset($select_result['steroid']) && $select_result['steroid'] == "Yes"){echo 'yes'; }?>				<br>		
			<?php echo isset($select_result['steroid_text'])?$select_result['steroid_text']:""; ?>					
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Have you ever used cortisone/steroid</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
	
	<?php if(isset($select_result['medication']) && $select_result['medication'] == "Yes"){echo 'yes'; }?>
	
	<br> <?php echo isset($select_result['medication_text'])?$select_result['medication_text']:""; ?>	
			</td>
								<td  style="border:1px solid #cdcdcd;">Medication</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
															
				<?php if(isset($select_result['herbal_products_text']) && $select_result['herbal_products_text'] == "Yes"){echo 'yes'; }?>	
<br>
<br> <?php echo isset($select_result['herbal_products_text'])?$select_result['herbal_products_text']:""; ?>				
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Herbal products</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
																	
	<?php if(isset($select_result['eye_drops']) && $select_result['eye_drops'] == "Yes"){echo 'yes'; }?>	<br> <?php echo isset($select_result['eye_drops_text'])?$select_result['eye_drops_text']:""; ?>							
								</td>
								<td  style="border:1px solid #cdcdcd;">Eye drops</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">
								
		<?php if(isset($select_result['non_prescription_drugs_text']) && $select_result['non_prescription_drugs_text'] == "Yes"){echo 'yes'; }?>	 <br>						
			<br> <?php echo isset($select_result['non_prescription_drugs_text'])?$select_result['non_prescription_drugs_text']:""; ?>					
								
								</td>
								<td  style="border:1px solid #cdcdcd;">Non prescription drugs used currently other than medications used for this IVF cycle</td>
							</tr>
						</table>
					</td>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Dentures</td>
								<td width="30%">
								
	<?php if(isset($select_result['male_dentures_text']) && $select_result['male_dentures_text'] == "Yes"){echo 'yes'; }?>	
	<br>
	<?php echo isset($select_result['male_dentures_text'])?$select_result['male_dentures_text']:""; ?>							
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Loose teeth</td>
								<td  style="border:1px solid #cdcdcd;">
									
<?php if(isset($select_result['male_loose_teeth_text']) && $select_result['male_loose_teeth_text'] == "Yes"){echo 'yes'; }?>
<br>
<?php echo isset($select_result['male_loose_teeth_text'])?$select_result['male_loose_teeth_text']:""; ?>	
							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Hearing aid</td>
								<td  style="border:1px solid #cdcdcd;">
											
			
			<?php if(isset($select_result['male_hearing_aid_text']) && $select_result['male_hearing_aid_text'] == "Yes"){echo 'yes'; }?>				

<br>
<?php echo isset($select_result['male_hearing_aid_text'])?$select_result['male_hearing_aid_text']:""; ?>

						</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Caps on front teeth</td>
								<td  style="border:1px solid #cdcdcd;">
						<?php if(isset($select_result['male_caps_on_front_teeth_text']) && $select_result['male_caps_on_front_teeth_text'] == "Yes"){echo 'yes'; }?>			
<br>
<?php echo isset($select_result['male_caps_on_front_teeth_text'])?$select_result['male_caps_on_front_teeth_text']:""; ?>


					</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Contact lenses</td>
								<td  style="border:1px solid #cdcdcd;">
													
	<?php if(isset($select_result['male_contact_lenses_text']) && $select_result['male_contact_lenses_text'] == "Yes"){echo 'yes'; }?>
	
	<br>
	<?php echo isset($select_result['male_contact_lenses_text'])?$select_result['male_contact_lenses_text']:""; ?>


					</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Body piercing</td>
								<td  style="border:1px solid #cdcdcd;">
									
								
		<?php if(isset($select_result['male_body_piercing_text']) && $select_result['male_body_piercing_text'] == "Yes"){echo 'yes'; }?>	
<br>
<?php echo isset($select_result['male_body_piercing_text'])?$select_result['male_body_piercing_text']:""; ?>		
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">H/o blood transfusion</td>
								<td  style="border:1px solid #cdcdcd;">
									
<?php if(isset($select_result['male_blood_transfusion_text']) && $select_result['male_blood_transfusion_text'] == "Yes"){echo 'yes'; }?>	<br>

<?php echo isset($select_result['male_blood_transfusion_text'])?$select_result['male_blood_transfusion_text']:""; ?>
							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">H/o road traffic accident/any injury</td>
								<td  style="border:1px solid #cdcdcd;">
								
<?php if(isset($select_result['male_traffic_accident_text']) && $select_result['male_traffic_accident_text'] == "Yes"){echo 'yes'; }?>									
							<br>	
<?php echo isset($select_result['male_traffic_accident_text'])?$select_result['male_traffic_accident_text']:""; ?>

							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Smoke(past)daily</td>
								<td  style="border:1px solid #cdcdcd;">
																
<?php if(isset($select_result['male_smoke_past_text']) && $select_result['male_smoke_past_text'] == "Yes"){echo 'yes'; }?>								<br>	
	<?php echo isset($select_result['male_smoke_past_text'])?$select_result['male_smoke_past_text']:""; ?>							
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Smoke(present)daily</td>
								<td  style="border:1px solid #cdcdcd;">
																
	<?php if(isset($select_result['male_smoke_present_text']) && $select_result['male_smoke_present_text'] == "Yes"){echo 'yes'; }?>
<br>
<?php echo isset($select_result['male_smoke_present_text'])?$select_result['male_smoke_present_text']:""; ?>	
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Drink(past)units per week</td>
								<td  style="border:1px solid #cdcdcd;">
																
								
<?php if(isset($select_result['male_drink_past_text']) && $select_result['male_drink_past_text'] == "Yes"){echo 'yes'; }?>							
		<br>
<?php echo isset($select_result['male_drink_past_text'])?$select_result['male_drink_past_text']:""; ?>
		
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Drink(present)units per week</td>
								<td  style="border:1px solid #cdcdcd;">
									
							
<?php if(isset($select_result['afc_left']) && $select_result['afc_left'] == "Yes"){echo 'yes'; }?>

<br>
<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>
							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Hashish/cocaine /abusive drugs</td>
								<td  style="border:1px solid #cdcdcd;">
								
			<?php if(isset($select_result['male_abusive_drugs_text']) && $select_result['male_abusive_drugs_text'] == "Yes"){echo 'yes'; }?> <br>

<?php echo isset($select_result['male_abusive_drugs_text'])?$select_result['male_abusive_drugs_text']:""; ?>
			
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Have you ever used cortisone/steroid</td>
								<td  style="border:1px solid #cdcdcd;">
																		
					<?php if(isset($select_result['male_steroid_text']) && $select_result['male_steroid_text'] == "Yes"){echo 'yes'; }?>	 <br>

<?php echo isset($select_result['male_steroid_text'])?$select_result['male_steroid_text']:""; ?>

					
										
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Medication</td>
								<td  style="border:1px solid #cdcdcd;">
																	
			<?php if(isset($select_result['male_medication_text']) && $select_result['male_medication_text'] == "Yes"){echo 'yes'; }?>						
		<br>
<?php echo isset($select_result['male_medication_text'])?$select_result['male_medication_text']:""; ?>		
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Herbal products</td>
								<td  style="border:1px solid #cdcdcd;">
									
			<?php if(isset($select_result['male_herbal_products_text']) && $select_result['male_herbal_products_text'] == "Yes"){echo 'yes'; }?> <br>				
<?php echo isset($select_result['male_herbal_products_text'])?$select_result['male_herbal_products_text']:""; ?>
						</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Eye drops</td>
								<td  style="border:1px solid #cdcdcd;">
							
		<?php if(isset($select_result['male_eye_drops_text']) && $select_result['male_eye_drops_text'] == "Yes"){echo 'yes'; }?>	 <br>
<?php echo isset($select_result['male_eye_drops_text'])?$select_result['male_eye_drops_text']:""; ?>
		


						</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Non prescription drugs used currently other than medications used for this IVF cycle</td>
								<td  style="border:1px solid #cdcdcd;">
															
<?php if(isset($select_result['male_non_prescription_drugs_text']) && $select_result['male_non_prescription_drugs_text'] == "Yes"){echo 'yes'; }?> <br>

<?php echo isset($select_result['male_non_prescription_drugs_text'])?$select_result['male_non_prescription_drugs_text']:""; ?>


							</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">FAMILY HISTORY</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">Any family member any problem <br> with anesthesia</td>
								<td  colspan="2"  style="border:1px solid #cdcdcd;">
					
		<?php if(isset($select_result['member_with_anesthesia_text']) && $select_result['member_with_anesthesia_text'] == "Yes"){echo 'yes'; }?> <br>
		
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
	
<?php if(isset($select_result['maternal_diabetes_text']) && $select_result['maternal_diabetes_text'] == "Yes"){echo 'yes'; }?>
<br>
<?php echo isset($select_result['maternal_diabetes_text'])?$select_result['maternal_diabetes_text']:""; ?>

	</td>
								
								
								<td  style="border:1px solid #cdcdcd;">
															
<?php if(isset($select_result['paternal_diabetes_text']) && $select_result['paternal_diabetes_text'] == "Yes"){echo 'yes'; }?>
<br>
<?php echo isset($select_result['paternal_diabetes_text'])?$select_result['paternal_diabetes_text']:""; ?>
							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Heart/thrombo embolism</td>
								<td  style="border:1px solid #cdcdcd;">
									
			
			<?php if(isset($select_result['afc_left']) && $select_result['afc_left'] == "Yes"){echo 'yes'; }?>
			<br>
			<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>						
								
								
								
								</td>
								<td  style="border:1px solid #cdcdcd;">
									
		<?php if(isset($select_result['paternal_thrombo_embolism_text']) && $select_result['paternal_thrombo_embolism_text'] == "Yes"){echo 'yes'; }?>	

<br>
<?php echo isset($select_result['paternal_thrombo_embolism_text'])?$select_result['paternal_thrombo_embolism_text']:""; ?>		


							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Endocrine/metabolic</td>
								<td  style="border:1px solid #cdcdcd;">
									
				<?php if(isset($select_result['maternal_metabolic_text']) && $select_result['maternal_metabolic_text'] == "Yes"){echo 'yes'; }?> <br>
				
				<br> <?php echo isset($select_result['maternal_metabolic_text'])?$select_result['maternal_metabolic_text']:""; ?>				
								</td>
							

							<td  style="border:1px solid #cdcdcd;">
								
<?php if(isset($select_result['paternal_metabolic_text']) && $select_result['paternal_metabolic_text'] == "Yes"){echo 'yes'; }?>  <br>
<?php echo isset($select_result['paternal_metabolic_text'])?$select_result['paternal_metabolic_text']:""; ?>

							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Urinary tract/renal</td>
								<td  style="border:1px solid #cdcdcd;">
																	
			<?php if(isset($select_result['maternal_urinary_tract_text']) && $select_result['maternal_urinary_tract_text'] == "Yes"){echo 'yes'; }?>
					<br>
			<?php echo isset($select_result['maternal_urinary_tract_text'])?$select_result['maternal_urinary_tract_text']:""; ?>					
								
								
								
								</td>
								<td  style="border:1px solid #cdcdcd;">
																
<?php if(isset($select_result['paternal_urinary_tract_text']) && $select_result['paternal_urinary_tract_text'] == "Yes"){echo 'yes'; }?>
<br>
<?php echo isset($select_result['paternal_urinary_tract_text'])?$select_result['paternal_urinary_tract_text']:""; ?>


							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Psychiatric/neurological</td>
								<td  style="border:1px solid #cdcdcd;">
																	
					<?php if(isset($select_result['maternal_neurological']) && $select_result['maternal_neurological'] == "Yes"){echo 'yes'; }?>	 <br>				
								
				<?php echo isset($select_result['maternal_neurological_text'])?$select_result['maternal_neurological_text']:""; ?>				
								</td>
								
								
								
								<td  style="border:1px solid #cdcdcd;">
																	
				<?php if(isset($select_result['paternal_neurological_text']) && $select_result['paternal_neurological_text'] == "Yes"){echo 'yes'; }?>		 <br>			
								
			<?php echo isset($select_result['paternal_neurological_text'])?$select_result['paternal_neurological_text']:""; ?>					
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Other/malignancy</td>
								<td  style="border:1px solid #cdcdcd;">
																	
					<?php if(isset($select_result['maternal_malignancy_text']) && $select_result['maternal_malignancy_text'] == "Yes"){echo 'yes'; }?>	 <br>		
								
				<?php echo isset($select_result['maternal_malignancy_text'])?$select_result['maternal_malignancy_text']:""; ?>				
								</td>
							



							<td  style="border:1px solid #cdcdcd;">
									
				<?php if(isset($select_result['paternal_malignancy']) && $select_result['paternal_malignancy'] == "Yes"){echo 'yes'; }?>				
<br>
<?php echo isset($select_result['paternal_malignancy_text'])?$select_result['paternal_malignancy_text']:""; ?>

							</td>
							</tr>
						</table>
					</td>
					<td  style="border:1px solid #cdcdcd;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">Any family member any problem <br> with anesthesia</td>
								<td  colspan="2"  style="border:1px solid #cdcdcd;">
									  
		<?php if(isset($select_result['male_member_with_anesthesia_text']) && $select_result['male_member_with_anesthesia_text'] == "Yes"){echo 'yes'; }?>	 <br>					
								
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
																
						
<?php if(isset($select_result['male_maternal_diabetes_text']) && $select_result['male_maternal_diabetes_text'] == "Yes"){echo 'yes'; }?>	
<br>
<?php echo isset($select_result['male_maternal_diabetes_text'])?$select_result['male_maternal_diabetes_text']:""; ?>	
								
								
								</td>
								
								
								
								<td  style="border:1px solid #cdcdcd;">
																

<?php if(isset($select_result['male_paternal_diabetes_text']) && $select_result['male_paternal_diabetes_text'] == "Yes"){echo 'yes'; }?>		
			<br>
<?php echo isset($select_result['male_paternal_diabetes_text'])?$select_result['male_paternal_diabetes_text']:""; ?>
			
							</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Heart/thrombo embolism</td>
								<td  style="border:1px solid #cdcdcd;">
									
								
						 <?php if(isset($select_result['male_maternal_thrombo_embolism_text']) && $select_result['male_maternal_thrombo_embolism_text'] == "Yes"){echo 'yes'; }?>	 <br>	
								
			<?php echo isset($select_result['male_maternal_thrombo_embolism_text'])?$select_result['male_maternal_thrombo_embolism_text']:""; ?>					
								
								</td>
								
								
								<td  style="border:1px solid #cdcdcd;">
																	
								 <?php if(isset($select_result['male_paternal_thrombo_embolism_text']) && $select_result['male_paternal_thrombo_embolism_text'] == "Yes"){echo 'yes'; }?> <br>
						<?php echo isset($select_result['male_paternal_thrombo_embolism_text'])?$select_result['male_paternal_thrombo_embolism_text']:""; ?>									
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Endocrine/metabolic</td>
								<td  style="border:1px solid #cdcdcd;">
								
						 <?php if(isset($select_result['male_maternal_metabolic_text']) && $select_result['male_maternal_metabolic_text'] == "Yes"){echo 'yes'; }?>		
						<br>
<?php echo isset($select_result['male_maternal_metabolic_text'])?$select_result['male_maternal_metabolic_text']:""; ?>
						
								</td>
								<td  style="border:1px solid #cdcdcd;">
																		
								 <?php if(isset($select_result['male_paternal_metabolic']) && $select_result['male_paternal_metabolic'] == "Yes"){echo 'yes'; }?>
                    	<br>
							<?php echo isset($select_result['male_paternal_metabolic_text'])?$select_result['male_paternal_metabolic_text']:""; ?>		
									
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Urinary tract/renal</td>
								<td  style="border:1px solid #cdcdcd;">
									
								
							  <?php if(isset($select_result['male_maternal_urinary_tract_text']) && $select_result['male_maternal_urinary_tract_text'] == "Yes"){echo 'yes'; }?>
                    <br>
		<?php echo isset($select_result['male_maternal_urinary_tract_text'])?$select_result['male_maternal_urinary_tract_text']:""; ?>
								
								
								</td>
								
								
								
								<td  style="border:1px solid #cdcdcd;">
									
								   <?php if(isset($select_result['male_paternal_urinary_tract_text']) && $select_result['male_paternal_urinary_tract_text'] == "Yes"){echo 'yes'; }?> <br>
                    <?php echo isset($select_result['male_paternal_urinary_tract_text'])?$select_result['male_paternal_urinary_tract_text']:""; ?>
	
									
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Psychiatric/neurological</td>
								<td  style="border:1px solid #cdcdcd;">
									 
  
  <?php if(isset($select_result['male_maternal_neurological']) && $select_result['male_maternal_neurological'] == "Yes"){echo 'yes'; }?> <br>
  		<?php echo isset($select_result['male_maternal_neurological_text'])?$select_result['male_maternal_neurological_text']:""; ?>
								</td>
								<td  style="border:1px solid #cdcdcd;">
									
						<?php if(isset($select_result['male_paternal_neurological_text']) && $select_result['male_paternal_neurological_text'] == "Yes"){echo 'yes'; }?>				
								<br>	
					<?php echo isset($select_result['male_paternal_neurological_text'])?$select_result['male_paternal_neurological_text']:""; ?>				
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Other/malignancy</td>
								<td  style="border:1px solid #cdcdcd;">
																	
					<?php if(isset($select_result['male_maternal_malignancy_text']) && $select_result['male_maternal_malignancy_text'] == "Yes"){echo 'yes'; }?>			
								<br>
			<?php echo isset($select_result['male_maternal_malignancy_text'])?$select_result['male_maternal_malignancy_text']:""; ?>				
								</td>
								<td  style="border:1px solid #cdcdcd;">
																	
									<?php if(isset($select_result['male_paternal_malignancy_text']) && $select_result['male_paternal_malignancy_text'] == "Yes"){echo 'yes'; }?>
							<br>
<?php echo isset($select_result['male_paternal_malignancy_text'])?$select_result['male_paternal_malignancy_text']:""; ?>
							
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">PREVIOUS HISTORY OF INFERTILITY TREATMENT</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
						<tr>
								<td  style="border:1px solid #cdcdcd;">UNDERGONE IVF CYCLES</td>
								<td  style="border:1px solid #cdcdcd;">
									
								<?php if(isset($select_result['egg_cycles_before']) && $select_result['egg_cycles_before'] == "Yes"){echo 'yes'; }?>	
								<br>
								<?php echo isset($select_result['egg_cycles_before_text'])?$select_result['egg_cycles_before_text']:""; ?>
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">UNDERGONE DONOR EGG CYCLES BEFORE</td>
								<td  style="border:1px solid #cdcdcd;">
									
								<?php if(isset($select_result['egg_cycles_before']) && $select_result['egg_cycles_before'] == "Yes"){echo 'yes'; }?>	
								<br>
								<?php echo isset($select_result['egg_cycles_before_text'])?$select_result['egg_cycles_before_text']:""; ?>
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">UNDERGONE SURROGACY CYCLES BEFORE</td>
								<td  style="border:1px solid #cdcdcd;">
																		
							<?php if(isset($select_result['surrogacy_cycles_before_text']) && $select_result['surrogacy_cycles_before_text'] == "Yes"){echo 'yes'; }?>
					<br>		
  <?php echo isset($select_result['surrogacy_cycles_before_text'])?$select_result['surrogacy_cycles_before_text']:""; ?>
							
								</td>
							</tr>
						</table>
					</td>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
						<tr>
								<td  style="border:1px solid #cdcdcd;">UNDERGONE IVF CYCLES</td>
								<td  style="border:1px solid #cdcdcd;">
									
								<?php if(isset($select_result['egg_cycles_before']) && $select_result['egg_cycles_before'] == "Yes"){echo 'yes'; }?>	
								<br>
								<?php echo isset($select_result['egg_cycles_before_text'])?$select_result['egg_cycles_before_text']:""; ?>
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">UNDERGONE DONOR EGG CYCLES BEFORE</td>
								<td  style="border:1px solid #cdcdcd;">
									
								<?php if(isset($select_result['egg_cycles_before']) && $select_result['egg_cycles_before'] == "Yes"){echo 'yes'; }?>	
								<br>
								<?php echo isset($select_result['egg_cycles_before_text'])?$select_result['egg_cycles_before_text']:""; ?>
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">UNDERGONE SURROGACY CYCLES BEFORE</td>
								<td  style="border:1px solid #cdcdcd;">
																		
							<?php if(isset($select_result['surrogacy_cycles_before_text']) && $select_result['surrogacy_cycles_before_text'] == "Yes"){echo 'yes'; }?>
					<br>		
  <?php echo isset($select_result['surrogacy_cycles_before_text'])?$select_result['surrogacy_cycles_before_text']:""; ?>
							
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">RELATIONSHIP HISTORY</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">level of stress or responsibility/demands</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['stress_level'])?$select_result['stress_level']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">SUPPORT FROM HUSBAND/WIFE:</td>
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
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
							<tr>
								<td  style="border:1px solid #cdcdcd;">level of stress or responsibility/demands</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['stress_level_2'])?$select_result['stress_level_2']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">SUPPORT FROM HUSBAND/WIFE:</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['support_from_husband_2'])?$select_result['support_from_husband_2']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">SUPPORT FROM IMMEDIATE FAMILY MEMBERS:</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['support_from_family_2'])?$select_result['support_from_family_2']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">INTERPERSONAL CONFLICTS BETWEEN COUPLE:</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['conflicts_2'])?$select_result['conflicts_2']:""; ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">MENTAL HISTORY</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
						<table width="100%">
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
					<th style="border:1px solid #cdcdcd;">GENERAL EXAMINATION</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
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
									
									 <?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "combative"){echo 'combative"'; }?> 
								
								<?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "depressed"){echo 'depressed'; }?>
								
								<?php if(isset($select_result['psychological_assessment']) && $select_result['psychological_assessment'] == "normal"){echo 'normal'; }?> 
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Pulse (PER MIN)</td>
								<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['pulse'])?$select_result['pulse']:""; ?></td>
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
									   
								
								<?php if(isset($select_result['cvs']) && $select_result['cvs'] == "Yes"){echo 'yes'; }?>
								
							<br>	<?php echo isset($select_result['cvs_text'])?$select_result['cvs_text']:""; ?>
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Chest</td>
								<td  style="border:1px solid #cdcdcd;">
									   
								<?php if(isset($select_result['chest']) && $select_result['chest'] == "Yes"){echo 'yes'; }?>
							<br>	<?php echo isset($select_result['chest'])?$select_result['chest']:""; ?>
								
								</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Abdomen</td>
								<td  style="border:1px solid #cdcdcd;">
								
								<?php if(isset($select_result['abdomen']) && $select_result['abdomen'] == "Yes"){echo 'yes'; }?> <br>
								<?php echo isset($select_result['abdomen_text'])?$select_result['abdomen_text']:""; ?>
								
		</td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Others</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['others2'])?$select_result['others2']:""; ?>
								
							<br>	<?php echo isset($select_result['others2'])?$select_result['others2']:""; ?>
								
								</td>
								
							</tr>
						</table>
					</td>
					<td  style="border:1px solid #cdcdcd;"></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">MENTAL STATUS EXAMINATION</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">
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
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['general_behaviour'])?$select_result['general_behaviour']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Level of Consciousness</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['consciousness'])?$select_result['consciousness']:""; ?></td>
							</tr>
							<tr>
								<td  style="border:1px solid #cdcdcd;">Orientation</td>
								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['orientation'])?$select_result['orientation']:""; ?></td>
							</tr>
						</table>
					</td>
					<td  style="border:1px solid #cdcdcd;"></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">TAKEN UP FOR IVF/THIRD PARTY REPRODUCTION PROGRAMME</th>
					
					<td  style="border:1px solid #cdcdcd;">
						
     <?php if(isset($select_result['third_party_reproduction']) && $select_result['third_party_reproduction'] == "Yes"){echo 'yes'; }?> 
     <br>
     
     <?php echo isset($select_result['third_party_reproduction_text'])?$select_result['third_party_reproduction_text']:""; ?>
                  
					</td>
					
					
					<td  style="border:1px solid #cdcdcd;"></td>
				</tr>
			</table>
			<!-- /.card-body -->
		
		
		
		

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


      $('input[name=third_party_reproduction_text]').hide();
  if ($('input[name=third_party_reproduction]:checked').val() == "Yes") {
      
  
    $('input[name=third_party_reproduction_text]').show();
  } 
  $("input:radio").click(function () {
    if ($('input[name=third_party_reproduction]:checked').val() == "Yes") {
       
      $('input[name=third_party_reproduction_text]').show();
    } else if ($('input[name=third_party_reproduction]:checked').val() == "No") {
      $('input[name=third_party_reproduction_text]').hide();
    }
  });
  


</script>		