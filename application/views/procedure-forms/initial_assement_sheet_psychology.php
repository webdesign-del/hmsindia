<?php
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
                
        $select_query = "SELECT * FROM `hms_initial_assement` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_initial_assement` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_initial_assement SET ";
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
    $select_query = "SELECT * FROM `hms_initial_assement` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
			<td>Patient</td>
			<td><input  type="text" value="<?php echo isset($select_result['patient'])?$select_result['patient']:""; ?>"   name="patient" class="form-control"></td>
			<td colspan="2">Questionaire</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>Case sheet</td>
			<td><input  type="text" value="<?php echo isset($select_result['case_sheet'])?$select_result['case_sheet']:""; ?>"   name="case_sheet" class="form-control"></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>Anxiety scoring</td>
			<td><input  type="text" value="<?php echo isset($select_result['anxiety_snoring'])?$select_result['anxiety_snoring']:""; ?>"   name="anxiety_snoring" class="form-control"></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>Depression scoring</td>
			<td><input  type="text" value="<?php echo isset($select_result['depression_snoring'])?$select_result['depression_snoring']:""; ?>"   name="depression_snoring" class="form-control"></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>Social support score</td>
			<td><input  type="text" value="<?php echo isset($select_result['social_support_score'])?$select_result['social_support_score']:""; ?>"   name="social_support_score" class="form-control"></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>Cognitive ability scoring</td>
			<td><input  type="text" value="<?php echo isset($select_result['cognitive_ability_snoring'])?$select_result['cognitive_ability_snoring']:""; ?>"   name="cognitive_ability_snoring" class="form-control"></td>
		</tr>
	</table>
	<table class="table-bordered" width="100%">
		<tr>
			<td>Egg Donor</td>
			<td><input  type="text" value="<?php echo isset($select_result['egg_donor'])?$select_result['egg_donor']:""; ?>"   name="egg_donor" class="form-control"></td>
		</tr>
		<tr>
			<td>Surrogate</td>
			<td><input  type="text" value="<?php echo isset($select_result['surrogate'])?$select_result['surrogate']:""; ?>"   name="surrogate" class="form-control"></td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
	</table>
	<p>I. General Information</p>
	<table>
		<tr>
			<td>Name</td>
			<td><input  type="text" value="<?php echo isset($select_result['name'])?$select_result['name']:""; ?>"   name="name" class="form-control"></td>
		</tr>
		<tr>
			<td>Address</td>
			<td><input  type="text" value="<?php echo isset($select_result['address'])?$select_result['address']:""; ?>"   maxlength="50" name="address" class="form-control"></td>
		</tr>
		<tr>
			<td>Phone: (Home)</td>
			<td><input  type="text" value="<?php echo isset($select_result['phone'])?$select_result['phone']:""; ?>"   maxlength="50" name="phone" class="form-control"></td>
			<td>(Work):</td>
			<td><input  type="text" value="<?php echo isset($select_result['work'])?$select_result['work']:""; ?>"   maxlength="50" name="work" class="form-control"></td>
		</tr>
		<tr>
			<td>Cell:</td>
			<td><input  type="text" value="<?php echo isset($select_result['cell'])?$select_result['cell']:""; ?>"   maxlength="50" name="cell" class="form-control"></td>
		</tr>
		<tr>
			<td>Age:</td>
			<td><input  type="number" value="<?php echo isset($select_result['age'])?$select_result['age']:""; ?>"   max="100" min="1" name="age" class="form-control"></td>
			<td>Occupations:</td>
			<td><input  type="text" value="<?php echo isset($select_result['occupation'])?$select_result['occupation']:""; ?>"   maxlength="50" name="occupation" class="form-control"></td>
			<td>Gender:</td>
			<td><input  type="text" value="<?php echo isset($select_result['gender'])?$select_result['gender']:""; ?>"   name="gender" class="form-control"></td>
		</tr>
		<tr>
			<td>Referred by:</td>
			<td><input  type="text" value="<?php echo isset($select_result['referred_by'])?$select_result['referred_by']:""; ?>"   name="referred_by" class="form-control"></td>
		</tr>
		<tr>
			<td>May I acknowledge the referral?</td>
			<td>
				<input type="radio"  name="acknowledge" value="Yes"  <?php if(isset($select_result['acknowledge']) && $select_result['acknowledge'] == "Yes"){echo 'checked="checked"'; }?>  > <label for="type2"> Yes</label>
				<input type="radio"  name="acknowledge"  value="No"  <?php if(isset($select_result['acknowledge']) && $select_result['acknowledge'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['acknowledge']) && $select_result['acknowledge'] != "Yes"){echo 'checked="checked"';}?>  > <label for="type2"> No</label>
			</td>
		</tr>
		<tr>
			<td>Marital Status:</td>
			<td colspan="3">
				<input type="radio"  name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Single"){echo 'checked="checked"'; }?> value="Single"> <label for="type2">Single</label>&nbsp;
				<input type="radio"  name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Engaged"){echo 'checked="checked"'; }?> value="Engaged"> <label for="type2">Engaged</label>&nbsp;
				<input type="radio"  name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Married"){echo 'checked="checked"'; }?> value="Married"> <label for="type2">Married</label><br>
				<input type="radio"  name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Separated"){echo 'checked="checked"'; }?> value="Separated"> <label for="type2">Separated</label>&nbsp;
				<input type="radio"  name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Divorced"){echo 'checked="checked"'; }?> value="Divorced"> <label for="type2">Divorced</label>&nbsp;
				<input type="radio"  name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Widowed"){echo 'checked="checked"'; }?> value="Widowed"> <label for="type2">Widowed</label>
			</td>
		</tr>
		<tr>
			<td>2. Description of Presenting Problem</td>
			<td>
				<p>State in your own words the nature of your main problems:</p>
				<textarea name="presenting_problem" class="form-control"><?php echo isset($select_result['presenting_problem'])?$select_result['presenting_problem']:""; ?></textarea>
			</td>
		</tr>
		<tr>
			<td>3. On the scale below, please estimate the severity of your problem(s) now.</td>
			<td colspan="3">
				<input type="radio"  name="problem_severity" <?php if(isset($select_result['problem_severity']) && $select_result['problem_severity'] == "Mildly upsetting"){echo 'checked="checked"'; }?>  value="Mildly upsetting"> <label for="type2">Mildly upsetting</label>&nbsp;
				<input type="radio"  name="problem_severity" <?php if(isset($select_result['problem_severity']) && $select_result['problem_severity'] == "Moderately upsetting"){echo 'checked="checked"'; }?>  value="Moderately upsetting"> <label for="type2">Moderately upsetting</label><br>
				<input type="radio"  name="problem_severity" <?php if(isset($select_result['problem_severity']) && $select_result['problem_severity'] == "Severely upsetting"){echo 'checked="checked"'; }?>  value="Severely upsetting"> <label for="type2">Severely upsetting</label>&nbsp;
				<input type="radio"  name="problem_severity" <?php if(isset($select_result['problem_severity']) && $select_result['problem_severity'] == "extremely severe"){echo 'checked="checked"'; }?>  value="extremely severe"> <label for="type2">Extremely Severe</label>&nbsp;
			</td>
		</tr>
		<tr>
			<td>4. When did your problems begin?</td>
			<td>
				<input  type="text" value="<?php echo isset($select_result['problem_begin'])?$select_result['problem_begin']:""; ?>"   name="problem_begin" class="form-control" >
			</td>
		</tr>
		<tr>
			<td>5. Please describe significant events occurring at that time, or since then, which may relate to the development or maintenance of your problems.</td>
			<td>
				<input  type="text" value="<?php echo isset($select_result['significant_events'])?$select_result['significant_events']:""; ?>"   name="significant_events" class="form-control" >
			</td>
		</tr>
		<tr>
			<td>6. Have you been in therapy before or received any prior professional assistance for your problems?  If so, please give names, professional titles, dates of treatment, and results.</td>
			<td>
				<input type="radio"  name="therapy_before"  value="Yes" <?php if(isset($select_result['therapy_before']) && $select_result['therapy_before'] == "Yes"){echo 'checked="checked"'; }?>> Yes
				<input type="radio"  name="therapy_before"  value="No" <?php if(isset($select_result['therapy_before']) && $select_result['therapy_before'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['therapy_before']) && $select_result['therapy_before'] != "Yes"){echo 'checked="checked"';}?>  > No
				<input  type="text" value="<?php echo isset($select_result['therapy_before_text'])?$select_result['therapy_before_text']:""; ?>"   name="therapy_before_text">
			</td>
		</tr>
		<tr>
			<td>7. Have you ever been hospitalized for a psychological problem?   Y   N  If yes, when and where?</td>
			<td>
				<input type="radio"  name="hospitalized"  value="Yes"  <?php if(isset($select_result['hospitalized']) && $select_result['hospitalized'] == "Yes"){echo 'checked="checked"'; }?>> Yes
				<input type="radio"  name="hospitalized"  value="No"  <?php if(isset($select_result['hospitalized']) && $select_result['hospitalized'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['hospitalized']) && $select_result['hospitalized'] != "Yes"){echo 'checked="checked"';}?>  > No
				<input  type="text" value="<?php echo isset($select_result['hospitalized_text'])?$select_result['hospitalized_text']:""; ?>"   name="hospitalized_text">
			</td>
		</tr>
		<tr>
			<td>8. Have you ever attempted suicide?</td>
			<td>
				<input type="radio"  name="attempted_suicide"   value="Yes"    <?php if(isset($select_result['attempted_suicide']) && $select_result['attempted_suicide'] == "Yes"){echo 'checked="checked"'; }?>  > <label for="type2"> Yes</label>
				<input type="radio"  name="attempted_suicide"  value="No"  <?php if(isset($select_result['attempted_suicide']) && $select_result['attempted_suicide'] == "No"){echo 'checked="checked"'; }
else if(isset($select_result['attempted_suicide']) && $select_result['attempted_suicide'] != "Yes"){echo 'checked="checked"';}?>  > <label for="type2"> No</label>
			</td>
		</tr>
		<tr>
			<td>9.Do you practice relaxation or meditation regularly?</td>
			<td>
				<input  type="text" value="<?php echo isset($select_result['meditation'])?$select_result['meditation']:""; ?>"   name="meditation" class="form-control" >
			</td>
		</tr>
	</table>
	<br>
	<p>10. Present Feelings<br>
		<em>Circle any of the following feelings that often apply to you.</em>
	</p>
	<p>	<input type="checkbox" name="feelings[]" value="Angry" <?php if(!empty($select_result['feelings']) && in_array('Angry',$feelings)){echo "checked";}?>>
	Angry 
	<input type="checkbox" name="feelings[]" value="Guilty" <?php if(!empty($select_result['feelings']) && in_array('Guilty',$feelings)){echo "checked";}?>>
	Guilty
	<input type="checkbox" name="feelings[]" value="Unhappy" <?php if(!empty($select_result['feelings']) && in_array('Unhappy',$feelings)){echo "checked";}?>>
	Unhappy
	<input type="checkbox" name="feelings[]" value="Annoyed" <?php if(!empty($select_result['feelings']) && in_array('Annoyed',$feelings)){echo "checked";}?>>
	Annoyed
	<input type="checkbox" name="feelings[]" value="Happy" <?php if(!empty($select_result['feelings']) && in_array('Happy',$feelings)){echo "checked";}?>>
	Happy
	<input type="checkbox" name="feelings[]" value="Bored" <?php if(!empty($select_result['feelings']) && in_array('Bored',$feelings)){echo "checked";}?>>
	Bored
	<input type="checkbox" name="feelings[]" value="Depressed" <?php if(!empty($select_result['feelings']) && in_array('Depressed',$feelings)){echo "checked";}?>>
	Depressed
	<input type="checkbox" name="feelings[]" value="Regretful" <?php if(!empty($select_result['feelings']) && in_array('Regretful',$feelings)){echo "checked";}?>>
	Regretful
	<input type="checkbox" name="feelings[]" value="Lonely" <?php if(!empty($select_result['feelings']) && in_array('Lonely',$feelings)){echo "checked";}?>>
	Lonely
	<input type="checkbox" name="feelings[]" value="Anxious" <?php if(!empty($select_result['feelings']) && in_array('Anxious',$feelings)){echo "checked";}?>>
	Anxious
	<input type="checkbox" name="feelings[]" value="Hopeless" <?php if(!empty($select_result['feelings']) && in_array('Hopeless',$feelings)){echo "checked";}?>>
	Hopeless
	<input type="checkbox" name="feelings[]" value="Contented Fearful" <?php if(!empty($select_result['feelings']) && in_array('Contented Fearful',$feelings)){echo "checked";}?>>
	Contented Fearful
	<input type="checkbox" name="feelings[]" value="Hopeful" <?php if(!empty($select_result['feelings']) && in_array('Hopeful',$feelings)){echo "checked";}?>>
	Hopeful
	<input type="checkbox" name="feelings[]" value="Excited" <?php if(!empty($select_result['feelings']) && in_array('Excited',$feelings)){echo "checked";}?>>
	Excited
	<input type="checkbox" name="feelings[]" value="Panicky" <?php if(!empty($select_result['feelings']) && in_array('Panicky',$feelings)){echo "checked";}?>>
	Panicky
	<input type="checkbox" name="feelings[]" value="Helpless Optimistic" <?php if(!empty($select_result['feelings']) && in_array('Helpless Optimistic',$feelings)){echo "checked";}?>>
	Helpless Optimistic
	<input type="checkbox" name="feelings[]" value="Energetic" <?php if(!empty($select_result['feelings']) && in_array('Energetic Optimistic',$feelings)){echo "checked";}?>>
	Energetic
	<input type="checkbox" name="feelings[]" value="Relaxed" <?php if(!empty($select_result['feelings']) && in_array('Relaxed',$feelings)){echo "checked";}?>>
	Relaxed
	<input type="checkbox" name="feelings[]" value="Tense" <?php if(!empty($select_result['feelings']) && in_array('Tense',$feelings)){echo "checked";}?>>
	Tense
	<input type="checkbox" name="feelings[]" value="Envy" <?php if(!empty($select_result['feelings']) && in_array('Envy',$feelings)){echo "checked";}?>>
	Envy
	<input type="checkbox" name="feelings[]" value="Jealous" <?php if(!empty($select_result['feelings']) && in_array('Jealous',$feelings)){echo "checked";}?>>
	Jealous
	</p>
	<br>
	<table>
		<tr>
			<p>List your 5 main fears:</p>
		</tr>
		<tr>
			<td><input  type="text" value="<?php echo isset($select_result['main_fear1'])?$select_result['main_fear1']:""; ?>"   name="main_fear1" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['main_fear2'])?$select_result['main_fear2']:""; ?>"   name="main_fear2" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['main_fear3'])?$select_result['main_fear3']:""; ?>"   name="main_fear3" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['main_fear4'])?$select_result['main_fear4']:""; ?>"   name="main_fear4" class="form-control"></td>
			<td><input  type="text" value="<?php echo isset($select_result['main_fear5'])?$select_result['main_fear5']:""; ?>"   name="main_fear5" class="form-control"></td>
		</tr>
	</table>
	<div class="card-footer">
		<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
		<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
	</div>
</form>















		<!---  Print Button Start form --> 



<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none"> 




<!--          psychological_questionaire  forms              -->

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
			<td  style="border:1px solid #cdcdcd;">Patient</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['patient'])?$select_result['patient']:""; ?></td>
			<td colspan="2" style="border:1px solid #cdcdcd;"></td>
		</tr>
		<tr>
			<td colspan="2" style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;">Case sheet</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['case_sheet'])?$select_result['case_sheet']:""; ?></td>
		</tr>
		<tr>
			<td colspan="2" style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;">Anxiety scoring</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['anxiety_snoring'])?$select_result['anxiety_snoring']:""; ?></td>
		</tr>
		<tr>
			<td colspan="2" style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;">Depression scoring</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['depression_snoring'])?$select_result['depression_snoring']:""; ?></td>
		</tr>
		<tr>
			<td colspan="2" style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;">Social support score</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['social_support_score'])?$select_result['social_support_score']:""; ?></td>
		</tr>
		<tr>
			<td colspan="2" style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;">Cognitive ability scoring</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['cognitive_ability_snoring'])?$select_result['cognitive_ability_snoring']:""; ?></td>
		</tr>
	</table>
	<table class="table-bordered"  style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<td  style="border:1px solid #cdcdcd;">Egg Donor</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['egg_donor'])?$select_result['egg_donor']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Surrogate</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['surrogate'])?$select_result['surrogate']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"><br></td>
		</tr>
	</table>
	<p>I. General Information</p>
	<table  style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<td  style="border:1px solid #cdcdcd;">Name</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['name'])?$select_result['name']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Address</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['address'])?$select_result['address']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Phone: (Home)</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['phone'])?$select_result['phone']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;">(Work):</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['work'])?$select_result['work']:""; ?>   </td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Cell:</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['cell'])?$select_result['cell']:""; ?>   </td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Age:</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['age'])?$select_result['age']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;">Occupations:</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['occupation'])?$select_result['occupation']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;">Gender:</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['gender'])?$select_result['gender']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Referred by:</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['referred_by'])?$select_result['referred_by']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">May I acknowledge the referral?</td>
			<td  style="border:1px solid #cdcdcd;">
				

<?php if(isset($select_result['acknowledge']) && $select_result['acknowledge'] == "Yes"){echo 'yes'; }?>
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Marital Status:</td>
			<td colspan="3">
				  <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Single"){echo 'Single'; }?> 
				  <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Engaged"){echo 'Engaged'; }?> 
				  <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Married"){echo 'Married'; }?> 
				  <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Separated"){echo 'Separated'; }?> 
				  <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Divorced"){echo 'Divorced'; }?> 
				  <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Widowed"){echo 'Widowed'; }?> 
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">2. Description of Presenting Problem</td>
			<td  style="border:1px solid #cdcdcd;">
				<p>State in your own words the nature of your main problems:</p>
			<?php echo isset($select_result['presenting_problem'])?$select_result['presenting_problem']:""; ?>
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">3. On the scale below, please estimate the severity of your problem(s) now.</td>
			<td colspan="3">
				  <?php if(isset($select_result['problem_severity']) && $select_result['problem_severity'] == "Mildly upsetting"){echo 'Mildly upsetting'; }?> 
				  
				  <?php if(isset($select_result['problem_severity']) && $select_result['problem_severity'] == "Moderately upsetting"){echo 'Moderately upsetting'; }?> 
				  <?php if(isset($select_result['problem_severity']) && $select_result['problem_severity'] == "Severely upsetting"){echo 'Severely upsetting'; }?> 
				  <?php if(isset($select_result['problem_severity']) && $select_result['problem_severity'] == "extremely severe"){echo 'extremely severe'; }?>  
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">4. When did your problems begin?</td>
			<td  style="border:1px solid #cdcdcd;">
				 <?php echo isset($select_result['problem_begin'])?$select_result['problem_begin']:""; ?>   
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"> 5. Please describe significant events occurring at that time, or since then, which may relate to the development or maintenance of your problems.</td>
			<td  style="border:1px solid #cdcdcd;">
				 <?php echo isset($select_result['significant_events'])?$select_result['significant_events']:""; ?>
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"> 6. Have you been in therapy before or received any prior professional assistance for your problems?  If so, please give names, professional titles, dates of treatment, and results.</td>
			<td  style="border:1px solid #cdcdcd;">
				
				
<?php if(isset($select_result['therapy_before']) && $select_result['therapy_before'] == "Yes"){echo 'yes'; }?> <br>
				 <?php echo isset($select_result['therapy_before_text'])?$select_result['therapy_before_text']:""; ?>
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"> 7. Have you ever been hospitalized for a psychological problem?   Y   N  If yes, when and where?</td>
			<td  style="border:1px solid #cdcdcd;">
			

<?php if(isset($select_result['hospitalized']) && $select_result['hospitalized'] == "Yes"){echo 'yes'; }?> <br>

				 <?php echo isset($select_result['hospitalized_text'])?$select_result['hospitalized_text']:""; ?>
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"> 8. Have you ever attempted suicide?</td>
			<td  style="border:1px solid #cdcdcd;">
				
<?php if(isset($select_result['attempted_suicide']) && $select_result['attempted_suicide'] == "Yes"){echo 'yes'; }?>
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"> 9.Do you practice relaxation or meditation regularly?</td>
			<td  style="border:1px solid #cdcdcd;">
				 <?php echo isset($select_result['meditation'])?$select_result['meditation']:""; ?>
			</td>
		</tr>
	</table>
	<br>
	<p>10. Present Feelings<br>
		<em>Circle any of the following feelings that often apply to you.</em>
	</p>
	<p>	<input type="checkbox" name="feelings[]" value="Angry" <?php if(!empty($select_result['feelings']) && in_array('Angry',$feelings)){echo "checked";}?>>
	Angry 
	<input type="checkbox" name="feelings[]" value="Guilty" <?php if(!empty($select_result['feelings']) && in_array('Guilty',$feelings)){echo "checked";}?>>
	Guilty
	<input type="checkbox" name="feelings[]" value="Unhappy" <?php if(!empty($select_result['feelings']) && in_array('Unhappy',$feelings)){echo "checked";}?>>
	Unhappy
	<input type="checkbox" name="feelings[]" value="Annoyed" <?php if(!empty($select_result['feelings']) && in_array('Annoyed',$feelings)){echo "checked";}?>>
	Annoyed
	<input type="checkbox" name="feelings[]" value="Happy" <?php if(!empty($select_result['feelings']) && in_array('Happy',$feelings)){echo "checked";}?>>
	Happy
	<input type="checkbox" name="feelings[]" value="Bored" <?php if(!empty($select_result['feelings']) && in_array('Bored',$feelings)){echo "checked";}?>>
	Bored
	<input type="checkbox" name="feelings[]" value="Depressed" <?php if(!empty($select_result['feelings']) && in_array('Depressed',$feelings)){echo "checked";}?>>
	Depressed
	<input type="checkbox" name="feelings[]" value="Regretful" <?php if(!empty($select_result['feelings']) && in_array('Regretful',$feelings)){echo "checked";}?>>
	Regretful
	<input type="checkbox" name="feelings[]" value="Lonely" <?php if(!empty($select_result['feelings']) && in_array('Lonely',$feelings)){echo "checked";}?>>
	Lonely
	<input type="checkbox" name="feelings[]" value="Anxious" <?php if(!empty($select_result['feelings']) && in_array('Anxious',$feelings)){echo "checked";}?>>
	Anxious
	<input type="checkbox" name="feelings[]" value="Hopeless" <?php if(!empty($select_result['feelings']) && in_array('Hopeless',$feelings)){echo "checked";}?>>
	Hopeless
	<input type="checkbox" name="feelings[]" value="Contented Fearful" <?php if(!empty($select_result['feelings']) && in_array('Contented Fearful',$feelings)){echo "checked";}?>>
	Contented Fearful
	<input type="checkbox" name="feelings[]" value="Hopeful" <?php if(!empty($select_result['feelings']) && in_array('Hopeful',$feelings)){echo "checked";}?>>
	Hopeful
	<input type="checkbox" name="feelings[]" value="Excited" <?php if(!empty($select_result['feelings']) && in_array('Excited',$feelings)){echo "checked";}?>>
	Excited
	<input type="checkbox" name="feelings[]" value="Panicky" <?php if(!empty($select_result['feelings']) && in_array('Panicky',$feelings)){echo "checked";}?>>
	Panicky
	<input type="checkbox" name="feelings[]" value="Helpless Optimistic" <?php if(!empty($select_result['feelings']) && in_array('Helpless Optimistic',$feelings)){echo "checked";}?>>
	Helpless Optimistic
	<input type="checkbox" name="feelings[]" value="Energetic" <?php if(!empty($select_result['feelings']) && in_array('Energetic Optimistic',$feelings)){echo "checked";}?>>
	Energetic
	<input type="checkbox" name="feelings[]" value="Relaxed" <?php if(!empty($select_result['feelings']) && in_array('Relaxed',$feelings)){echo "checked";}?>>
	Relaxed
	<input type="checkbox" name="feelings[]" value="Tense" <?php if(!empty($select_result['feelings']) && in_array('Tense',$feelings)){echo "checked";}?>>
	Tense
	<input type="checkbox" name="feelings[]" value="Envy" <?php if(!empty($select_result['feelings']) && in_array('Envy',$feelings)){echo "checked";}?>>
	Envy
	<input type="checkbox" name="feelings[]" value="Jealous" <?php if(!empty($select_result['feelings']) && in_array('Jealous',$feelings)){echo "checked";}?>>
	Jealous
	</p>
	<br>
	<table  style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<p>List your 5 main fears:</p>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['main_fear1'])?$select_result['main_fear1']:""; ?> </td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['main_fear2'])?$select_result['main_fear2']:""; ?> </td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['main_fear3'])?$select_result['main_fear3']:""; ?> </td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['main_fear4'])?$select_result['main_fear4']:""; ?> </td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['main_fear5'])?$select_result['main_fear5']:""; ?></td>
		</tr>











</div>
<style type="text/css">
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: unset!important;
    left: -9999px;
    opacity: 1!important;
}
</style>			
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
 