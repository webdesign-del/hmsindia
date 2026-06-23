<?php

if(isset($_POST['submit'])){
	unset($_POST['submit']);
	
	$select_query = "SELECT * FROM `evaluation_for_surrogate` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$select_result = run_select_query($select_query); 
	if(empty($select_result)){
		// mysql query to insert data
		$query = "INSERT INTO `evaluation_for_surrogate` SET ";
		$sqlArr = array();
		foreach( $_POST as $key=> $value )
		{
		  $sqlArr[] = " $key = '".addslashes($value)."'";
		}		
		$query .= implode(',' , $sqlArr);
	}else{
		// mysql query to update data
		$query = "UPDATE evaluation_for_surrogate SET ";
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
$select_query = "SELECT * FROM `evaluation_for_surrogate` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
			<td colspan="2"><center><h3>1. Identifying Information</h3></center></td>
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
		<td>Name</td>
		<td><input  type="text" value="<?php echo isset($select_result['name'])?$select_result['name']:""; ?>"   name="name" class="form-control" ></td>
	</tr>
	<tr>
		<td>Address and telephone number</td>
		<td><input  type="text" value="<?php echo isset($select_result['address'])?$select_result['address']:""; ?>"   maxlength="50" name="address" class="form-control" ></td>
	</tr>
	<tr>
		<td>Age & date of birth</td>
		<td><input  type="date" value="<?php echo isset($select_result['age'])?$select_result['age']:""; ?>"   name="age" class="form-control" ></td>
	</tr>
	<tr>
		<td>Occupation</td>
		<td><input  type="text" value="<?php echo isset($select_result['occupation'])?$select_result['occupation']:""; ?>"   maxlength="15" name="occupation" class="form-control" ></td>
	</tr>
	<tr>
		<td>Education</td>
		<td><input  type="text" value="<?php echo isset($select_result['education'])?$select_result['education']:""; ?>"   name="education" class="form-control" ></td>
	</tr>
	<tr>
		<td>Marital status</td>
		<td>
			<input type="radio" name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Single"){echo 'checked="checked"'; }?> value="Single"> Single
			<input type="radio" name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Engaged"){echo 'checked="checked"'; }?> value="Engaged"> Engaged
			<input type="radio" name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Married"){echo 'checked="checked"'; }?> value="Married"> Married
			<input type="radio" name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Separated"){echo 'checked="checked"'; }?> value="Separated"> Separated
			<input type="radio" name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Divorced"){echo 'checked="checked"'; }?> value="Divorced"> Divorced
			<input type="radio" name="marital_status" <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Widowed"){echo 'checked="checked"'; }?> value="Widowed"> Widowed
		</td>
	</tr>
	<tr>
		<td>Sexual History</td>
		<td><input  type="text" value="<?php echo isset($select_result['sexual_history'])?$select_result['sexual_history']:""; ?>"   name="sexual_history" class="form-control" ></td>
	</tr>
	<tr>
		<td>Children (yes/no), if yes age of each child</td>
		<td>
			<input type="radio" name="children"  value="Yes"  <?php if(isset($select_result['children']) && $select_result['children'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
			<input type="radio" name="children"   value="No"  <?php if(isset($select_result['children']) && $select_result['children'] == "No"){echo 'checked="checked"'; }
  																	else if(!isset($select_result['children'])){echo 'checked="checked"';}?>  > No
			<input  type="text" value="<?php echo isset($select_result['children_age'])?$select_result['children_age']:""; ?>"   name="children_age" class="form-control">
		</td>
	</tr>
</table>
<h3 style="margin-top: 20px;">2.Medical Health & Life Style</h3>
<table class="table-bordered" width="100%">
	<tr>
		<td>self-care (diet, exercise, sleep)</td>
		<td><input  type="text" value="<?php echo isset($select_result['self_care'])?$select_result['self_care']:""; ?>"   name="self_care" class="form-control" ></td>
	</tr>
	<tr>
		<td>level of stress or responsibility/demands</td>
		<td><input  type="text" value="<?php echo isset($select_result['stress'])?$select_result['stress']:""; ?>"   name="stress" class="form-control" ></td>
	</tr>
	<tr>
		<td>legal problems?</td>
		<td><input  type="text" value="<?php echo isset($select_result['legal_problem'])?$select_result['legal_problem']:""; ?>"   name="legal_problem" class="form-control" ></td>
	</tr>
	<tr>
		<td>past or present problematic use of drug or alcohol?</td>
		<td><input  type="text" value="<?php echo isset($select_result['use_of_drug_alcohol'])?$select_result['use_of_drug_alcohol']:""; ?>"   name="use_of_drug_alcohol" class="form-control" ></td>
	</tr>
	<tr>
		<td>past or present psychiatric problems?</td>
		<td><input  type="text" value="<?php echo isset($select_result['psychiatric_problems'])?$select_result['psychiatric_problems']:""; ?>"   name="psychiatric_problems" class="form-control" ></td>
	</tr>
	<tr>
		<td>previous abortion(s)?</td>
		<td><input  type="text" value="<?php echo isset($select_result['previous_abortion'])?$select_result['previous_abortion']:""; ?>"   name="previous_abortion" class="form-control" ></td>
	</tr>
	<tr>
		<td>physical or sexual abuse?</td>
		<td><input  type="text" value="<?php echo isset($select_result['abuse'])?$select_result['abuse']:""; ?>"   name="abuse" class="form-control" ></td>
	</tr>
</table>
<h3 style="margin-top: 20px;">3.Awareness about the Procedure</h3>
<table class="table-bordered" width="100%">
	<tr>
		<td>Why do you want to be A Surrogate?</td>
		<td><input  type="text" value="<?php echo isset($select_result['why_surrogate'])?$select_result['why_surrogate']:""; ?>"   name="why_surrogate" class="form-control" ></td>
	</tr>
	<tr>
		<td>Have you volunteered or forced?</td>
		<td><input  type="text" value="<?php echo isset($select_result['volunteered_or_forced'])?$select_result['volunteered_or_forced']:""; ?>"   name="volunteered_or_forced" class="form-control" ></td>
	</tr>
	<tr>
		<td>With whom have you chosen to disclose, and are they supportive?</td>
		<td><input  type="text" value="<?php echo isset($select_result['disclosed_with'])?$select_result['disclosed_with']:""; ?>"   name="disclosed_with" class="form-control" ></td>
	</tr>
	<tr>
		<td>Any medical issues?</td>
		<td><input  type="text" value="<?php echo isset($select_result['medical_issues'])?$select_result['medical_issues']:""; ?>"   name="medical_issues" class="form-control" ></td>
	</tr>
	<tr>
		<td>Do you understand the medical treatment that are requrird during the gestational period? yes/no</td>
		<td><input  type="text" value="<?php echo isset($select_result['treatment_during_gestational_period'])?$select_result['treatment_during_gestational_period']:""; ?>"   name="treatment_during_gestational_period" class="form-control" ></td>
	</tr>
</table>
<h3 style="margin-top: 20px;">4.Mental Status Examination</h3>
<table class="table-bordered" width="100%">
	<tr>
		<td>1. Appearance</td>
		<td><input  type="text" value="<?php echo isset($select_result['appearance'])?$select_result['appearance']:""; ?>"   name="appearance" class="form-control" ></td>
	</tr>
	<tr>
		<td>2. General Behaviour</td>
		<td><input  type="text" value="<?php echo isset($select_result['general_behaviour'])?$select_result['general_behaviour']:""; ?>"   name="general_behaviour" class="form-control" ></td>
	</tr>
	<tr>
		<td>3. Level of Consciousness</td>
		<td><input  type="text" value="<?php echo isset($select_result['consciousness'])?$select_result['consciousness']:""; ?>"   name="consciousness" class="form-control" ></td>
	</tr>
	<tr>
		<td>4. Orientation</td>
		<td><input  type="text" value="<?php echo isset($select_result['orientation'])?$select_result['orientation']:""; ?>"   name="orientation" class="form-control" ></td>
	</tr>
	<tr>
		<td>5. Thought content</td>
		<td><input  type="text" value="<?php echo isset($select_result['thought_content'])?$select_result['thought_content']:""; ?>"   name="thought_content" class="form-control" ></td>
	</tr>
</table>
<h3 style="margin-top: 20px;">5. Family History</h3>
<table class="table-bordered" width="100%">
	<tr>
		<td>Do you have a history of (if so please explain):</td>
		<td></td>
	</tr>
	<tr>
		<td>past or present significant losses or crises</td>
		<td><input  type="text" value="<?php echo isset($select_result['losses'])?$select_result['losses']:""; ?>"   name="losses" class="form-control" ></td>
	</tr>
	<tr>
		<td>conflict in relationships</td>
		<td><input  type="text" value="<?php echo isset($select_result['conflict'])?$select_result['conflict']:""; ?>"   name="conflict" class="form-control" ></td>
	</tr>
</table>
<h3 style="margin-top: 20px;">6.Spouse History</h3>
<table class="table-bordered" width="100%">
	<tr>
		<td>Name</td>
		<td><input  type="text" value="<?php echo isset($select_result['spouse_name'])?$select_result['spouse_name']:""; ?>"   name="spouse_name" class="form-control" ></td>
	</tr>
	<tr>
		<td>Address and telephone number</td>
		<td><input  type="text" value="<?php echo isset($select_result['spouse_address'])?$select_result['spouse_address']:""; ?>"   maxlength="50" name="spouse_address" class="form-control" ></td>
	</tr>
	<tr>
		<td>Age & date of birth</td>
		<td><input  type="date" value="<?php echo isset($select_result['spouse_age'])?$select_result['spouse_age']:""; ?>"   name="spouse_age" class="form-control" ></td>
	</tr>
	<tr>
		<td>Occupation</td>
		<td><input  type="text" value="<?php echo isset($select_result['spouse_occupation'])?$select_result['spouse_occupation']:""; ?>"   maxlength="15" name="spouse_occupation" class="form-control" ></td>
	</tr>
	<tr>
		<td>Education</td>
		<td><input  type="text" value="<?php echo isset($select_result['spouse_education'])?$select_result['spouse_education']:""; ?>"   name="spouse_education" class="form-control" ></td>
	</tr>
	<tr>
		<td>Sexual History</td>
		<td><input  type="text" value="<?php echo isset($select_result['spouse_sexual_history'])?$select_result['spouse_sexual_history']:""; ?>"   name="spouse_sexual_history" class="form-control" ></td>
	</tr>
</table>
<!-- /.card-body -->
<div class="card-footer">
	<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
	<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
</div>
</form>


    <!-- print -->

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" style="margin-right: 120px" onclick="printtable();">


<div  class="printtable pttable"  id="printtable"  style="display: none;">  




<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<td colspan="2" style="border:1px solid #cdcdcd;"><center><h3>1. Identifying Information</h3></center></td>
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
	<tr>
		<td  style="border:1px solid #cdcdcd;">Name</td>
		<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['name'])?$select_result['name']:""; ?> </td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Address and telephone number</td>
		<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['address'])?$select_result['address']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Age & date of birth</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['age'])?$select_result['age']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Occupation</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['occupation'])?$select_result['occupation']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Education</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['education'])?$select_result['education']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Marital status</td>
		<td  style="border:1px solid #cdcdcd;">
			<?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Single"){echo 'Single'; }?> 
			<?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Engaged"){echo 'Engaged'; }?> 
	<?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Married"){echo 'Married'; }?> 
			 <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Separated"){echo 'Separated'; }?> 
		 <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Divorced"){echo 'Divorced'; }?> 
		 <?php if(isset($select_result['marital_status']) && $select_result['marital_status'] == "Widowed"){echo 'Widowed'; }?> 
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Sexual History</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['sexual_history'])?$select_result['sexual_history']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Children (yes/no), if yes age of each child</td>
		<td  style="border:1px solid #cdcdcd;">
		 <?php if(isset($select_result['children']) && $select_result['children'] == "Yes"){echo 'yes'; }?>
             		<br>											
			<?php echo isset($select_result['children_age'])?$select_result['children_age']:""; ?>
		</td>
	</tr>
</table>
<h3 style="margin-top: 20px;">2.Medical Health & Life Style</h3>
<table class="table-bordered"  style="width:100%; border:1px solid #cdcdcd;">
	<tr>
		<td  style="border:1px solid #cdcdcd;">self-care (diet, exercise, sleep)</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['self_care'])?$select_result['self_care']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">level of stress or responsibility/demands</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['stress'])?$select_result['stress']:""; ?>   </td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">legal problems?</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['legal_problem'])?$select_result['legal_problem']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">past or present problematic use of drug or alcohol?</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['use_of_drug_alcohol'])?$select_result['use_of_drug_alcohol']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">past or present psychiatric problems?</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['psychiatric_problems'])?$select_result['psychiatric_problems']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">previous abortion(s)?</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['previous_abortion'])?$select_result['previous_abortion']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">physical or sexual abuse?</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['abuse'])?$select_result['abuse']:""; ?>   </td>
	</tr>
</table>
<h3 style="margin-top: 20px;">3.Awareness about the Procedure</h3>
<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
	<tr>
		<td  style="border:1px solid #cdcdcd;">Why do you want to be A Surrogate?</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['why_surrogate'])?$select_result['why_surrogate']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Have you volunteered or forced?</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['volunteered_or_forced'])?$select_result['volunteered_or_forced']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">With whom have you chosen to disclose, and are they supportive?</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['disclosed_with'])?$select_result['disclosed_with']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Any medical issues?</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medical_issues'])?$select_result['medical_issues']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Do you understand the medical treatment that are requrird during the gestational period? yes/no</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['treatment_during_gestational_period'])?$select_result['treatment_during_gestational_period']:""; ?></td>
	</tr>
</table>
<h3 style="margin-top: 20px;">4.Mental Status Examination</h3>
<table class="table-bordered"  style="width:100%; border:1px solid #cdcdcd;">
	<tr>
		<td  style="border:1px solid #cdcdcd;">1. Appearance</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['appearance'])?$select_result['appearance']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">2. General Behaviour</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['general_behaviour'])?$select_result['general_behaviour']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">3. Level of Consciousness</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['consciousness'])?$select_result['consciousness']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">4. Orientation</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['orientation'])?$select_result['orientation']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">5. Thought content</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['thought_content'])?$select_result['thought_content']:""; ?></td>
	</tr>
</table>
<h3 style="margin-top: 20px;">5. Family History</h3>
<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
	<tr>
		<td  style="border:1px solid #cdcdcd;">Do you have a history of (if so please explain):</td>
		<td  style="border:1px solid #cdcdcd;"></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">past or present significant losses or crises</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['losses'])?$select_result['losses']:""; ?>   </td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">conflict in relationships</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['conflict'])?$select_result['conflict']:""; ?></td>
	</tr>
</table>
<h3 style="margin-top: 20px;">6.Spouse History</h3>
<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
	<tr>
		<td  style="border:1px solid #cdcdcd;">Name</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['spouse_name'])?$select_result['spouse_name']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Address and telephone number</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['spouse_address'])?$select_result['spouse_address']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Age & date of birth</td>
		<td  style="border:1px solid #cdcdcd;"><input  type="date" value="<?php echo isset($select_result['spouse_age'])?$select_result['spouse_age']:""; ?>"   name="spouse_age" class="form-control" ></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Occupation</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['spouse_occupation'])?$select_result['spouse_occupation']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Education</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['spouse_education'])?$select_result['spouse_education']:""; ?></td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Sexual History</td>
		<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['spouse_sexual_history'])?$select_result['spouse_sexual_history']:""; ?></td>
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