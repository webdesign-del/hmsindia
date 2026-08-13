	<?php
if(isset($_POST['submit'])){
	unset($_POST['submit']);
	
	$select_query = "SELECT * FROM `evaluation_for_oocyte_donor` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$select_result = run_select_query($select_query); 
	if(empty($select_result)){
		// mysql query to insert data
		$query = "INSERT INTO `evaluation_for_oocyte_donor` SET ";
		$sqlArr = array();
		foreach( $_POST as $key=> $value )
		{
		  $sqlArr[] = " $key = '".addslashes($value)."'";
		}		
		$query .= implode(',' , $sqlArr);
	}else{
		// mysql query to update data
		$query = "UPDATE evaluation_for_oocyte_donor SET ";
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
$select_query = "SELECT * FROM `evaluation_for_oocyte_donor` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
$select_result = run_select_query($select_query);

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
			<td><input type="text" value="<?php echo isset($select_result['name'])?$select_result['name']:""; ?>"   name="name" class="form-control" ></td>
		</tr>
		<tr>
			<td>Address and telephone number</td>
			<td><input type="text" value="<?php echo isset($select_result['address'])?$select_result['address']:""; ?>"   maxlength="50" name="address" class="form-control" ></td>
		</tr>
		<tr>
			<td>Age & date of birth</td>
			<td><input type="date" value="<?php echo isset($select_result['age'])?$select_result['age']:""; ?>"   name="age" class="form-control" ></td>
		</tr>
		<tr>
			<td>Occupation</td>
			<td><input type="text" value="<?php echo isset($select_result['occupation'])?$select_result['occupation']:""; ?>"   maxlength="15" name="occupation" class="form-control" ></td>
		</tr>
		<tr>
			<td>Education</td>
			<td><input type="text" value="<?php echo isset($select_result['education'])?$select_result['education']:""; ?>"   name="education" class="form-control" ></td>
		</tr>
		<tr>
			<td>Marital status</td>
			<td><input type="text" value="<?php echo isset($select_result['marital_status'])?$select_result['marital_status']:""; ?>"   name="marital_status" class="form-control" ></td>
		</tr>
		<tr>
			<td>Sexual History</td>
			<td><input type="text" value="<?php echo isset($select_result['sexual_history'])?$select_result['sexual_history']:""; ?>"   name="sexual_history" class="form-control" ></td>
		</tr>
		<tr>
			<td>Children (yes/no), if yes age of each child</td>
			<td>
				<input type="radio" name="children" value="Yes"  <?php if(isset($select_result['children']) && $select_result['children'] == "Yes"){echo 'checked="checked"'; }?>  > Yes
				<input type="radio" name="children"   value="No"  <?php if(isset($select_result['children']) && $select_result['children'] == "No"){echo 'checked="checked"'; }else if(isset($select_result['children']) && $select_result['children'] != "Yes"){echo 'checked="checked"';}?>  > No
				<input type="date" value="<?php echo isset($select_result['children_age'])?$select_result['children_age']:""; ?>"   name="children_age" class="form-control">
			</td>
		</tr>
	</table>
	<h3 style="margin-top: 20px;">2.Medical Health & Life Style</h3>
	<table class="table-bordered" width="100%">
		<tr>
			<td>self-care (diet, exercise, sleep)</td>
			<td><input type="text" value="<?php echo isset($select_result['self_care'])?$select_result['self_care']:""; ?>"   name="self_care" class="form-control" ></td>
		</tr>
		<tr>
			<td>level of stress or responsibility/demands</td>
			<td><input type="text" value="<?php echo isset($select_result['stress'])?$select_result['stress']:""; ?>"   name="stress" class="form-control" ></td>
		</tr>
		<tr>
			<td>legal problems?</td>
			<td><input type="text" value="<?php echo isset($select_result['legal_problem'])?$select_result['legal_problem']:""; ?>"   name="legal_problem" class="form-control" ></td>
		</tr>
		<tr>
			<td>past or present problematic use of drug or alcohol?</td>
			<td><input type="text" value="<?php echo isset($select_result['use_of_drug_alcohol'])?$select_result['use_of_drug_alcohol']:""; ?>"   name="use_of_drug_alcohol" class="form-control" ></td>
		</tr>
		<tr>
			<td>past or present psychiatric problems?</td>
			<td><input type="text" value="<?php echo isset($select_result['psychiatric_problems'])?$select_result['psychiatric_problems']:""; ?>"   name="psychiatric_problems" class="form-control" ></td>
		</tr>
		<tr>
			<td>previous abortion(s)?</td>
			<td><input type="text" value="<?php echo isset($select_result['previous_abortion'])?$select_result['previous_abortion']:""; ?>"   name="previous_abortion" class="form-control" ></td>
		</tr>
		<tr>
			<td>physical or sexual abuse?</td>
			<td><input type="text" value="<?php echo isset($select_result['abuse'])?$select_result['abuse']:""; ?>"   name="abuse" class="form-control" ></td>
		</tr>
	</table>
	<h3 style="margin-top: 20px;">3.Awareness about the Procedure</h3>
	<table class="table-bordered" width="100%">
		<tr>
			<td>Why do you want to be an egg donor?</td>
			<td><input type="text" value="<?php echo isset($select_result['why_egg_donor'])?$select_result['why_egg_donor']:""; ?>"   name="why_egg_donor" class="form-control" ></td>
		</tr>
		<tr>
			<td>Have you volunteered or forced?</td>
			<td><input type="text" value="<?php echo isset($select_result['volunteered_or_forced'])?$select_result['volunteered_or_forced']:""; ?>"   name="volunteered_or_forced" class="form-control" ></td>
		</tr>
		<tr>
			<td>With whom have you chosen to disclose, and are they supportive?</td>
			<td><input type="text" value="<?php echo isset($select_result['disclosed_with'])?$select_result['disclosed_with']:""; ?>"   name="disclosed_with" class="form-control" ></td>
		</tr>
		<tr>
			<td>Medical Issues</td>
			<td><input type="text" value="<?php echo isset($select_result['medical_issues'])?$select_result['medical_issues']:""; ?>"   name="medical_issues" class="form-control" ></td>
		</tr>
		<tr>
			<td>Do you understand the medical treatment (daily injections, scans, retrieval)? yes/no</td>
			<td><input type="text" value="<?php echo isset($select_result['understand_the_treatment'])?$select_result['understand_the_treatment']:""; ?>"   name="understand_the_treatment" class="form-control" ></td>
		</tr>
		<tr>
			<td>If you have trouble with injections, do you have someone who can help? yes/n</td>
			<td><input type="text" value="<?php echo isset($select_result['trouble_with_injection'])?$select_result['trouble_with_injection']:""; ?>"   name="trouble_with_injection" class="form-control" ></td>
		</tr>
	</table>
	<h3 style="margin-top: 20px;">4.Mental Status Examination</h3>
	<table class="table-bordered" width="100%">
		<tr>
			<td>1. Apperance</td>
			<td><input type="text" value="<?php echo isset($select_result['apperance'])?$select_result['apperance']:""; ?>"   name="apperance" class="form-control" ></td>
		</tr>
		<tr>
			<td>2. General Behaviour</td>
			<td><input type="text" value="<?php echo isset($select_result['general_behaviour'])?$select_result['general_behaviour']:""; ?>"   name="general_behaviour" class="form-control" ></td>
		</tr>
		<tr>
			<td>3. Level of Consciousness</td>
			<td><input type="text" value="<?php echo isset($select_result['consciousness'])?$select_result['consciousness']:""; ?>"   name="consciousness" class="form-control" ></td>
		</tr>
		<tr>
			<td>4. Orientation</td>
			<td><input type="text" value="<?php echo isset($select_result['orientation'])?$select_result['orientation']:""; ?>"   name="orientation" class="form-control" ></td>
		</tr>
	</table>
	<h3 style="margin-top: 20px;">5.Spouse History</h3>
	<table class="table-bordered" width="100%">
		<tr>
			<td>Name</td>
			<td><input type="text" value="<?php echo isset($select_result['spouse_name'])?$select_result['spouse_name']:""; ?>"   name="spouse_name" class="form-control" ></td>
		</tr>
		<tr>
			<td>Address and telephone number</td>
			<td><input type="text" value="<?php echo isset($select_result['spouse_address'])?$select_result['spouse_address']:""; ?>"   name="spouse_address" class="form-control" ></td>
		</tr>
		<tr>
			<td>Age & date of birth</td>
			<td><input type="text" value="<?php echo isset($select_result['spouse_age'])?$select_result['spouse_age']:""; ?>"   name="spouse_age" class="form-control" ></td>
		</tr>
		<tr>
			<td>Occupation</td>
			<td><input type="text" value="<?php echo isset($select_result['spouse_occupation'])?$select_result['spouse_occupation']:""; ?>"   name="spouse_occupation" class="form-control" ></td>
		</tr>
		<tr>
			<td>Education</td>
			<td><input type="text" value="<?php echo isset($select_result['spouse_education'])?$select_result['spouse_education']:""; ?>"   name="spouse_education" class="form-control" ></td>
		</tr>
		<tr>
			<td>Sexual History</td>
			<td><input type="text" value="<?php echo isset($select_result['spouse_sexual_history'])?$select_result['spouse_sexual_history']:""; ?>"   name="spouse_sexual_history" class="form-control" ></td>
		</tr>
	</table>
	<!-- /.card-body -->
	<div class="card-footer">
		<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
		<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
	</div>
</form>




<!------   Print Button -->

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none"> 



<!-- evaluation_for_oocyte_donor  --->
	<table class="table-bordered"  style="border:1px solid #cdcdcd; width:100%;">
	<tr>
                <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
				<td colspan="2"><center><h3>1. Identifying Information</h3></center></td>	
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
			<td  style="border:1px solid #cdcdcd;">Name</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['name'])?$select_result['name']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Address and telephone number</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['address'])?$select_result['address']:""; ?></td>
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
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['marital_status'])?$select_result['marital_status']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Sexual History</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['sexual_history'])?$select_result['sexual_history']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Children (yes/no), if yes age of each child</td>
			<td  style="border:1px solid #cdcdcd;">
			 <?php if(isset($select_result['children']) && $select_result['children'] == "Yes"){echo 'Yes'; }?>
				
			</td>
		</tr>
	</table>
	<h3 style="margin-top: 20px;">2.Medical Health & Life Style</h3>
	<table class="table-bordered"  style="border:1px solid #cdcdcd; width:100%;">
		<tr>
			<td  style="border:1px solid #cdcdcd;">self-care (diet, exercise, sleep)</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['self_care'])?$select_result['self_care']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">level of stress or responsibility/demands</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['stress'])?$select_result['stress']:""; ?></td>
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
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['abuse'])?$select_result['abuse']:""; ?></td>
		</tr>
	</table>
	<h3 style="margin-top: 20px;">3.Awareness about the Procedure</h3>
	<table class="table-bordered"  style="border:1px solid #cdcdcd; width:100%;">
		<tr>
			<td  style="border:1px solid #cdcdcd;">Why do you want to be an egg donor?</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['why_egg_donor'])?$select_result['why_egg_donor']:""; ?></td>
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
			<td  style="border:1px solid #cdcdcd;">Medical Issues</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medical_issues'])?$select_result['medical_issues']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Do you understand the medical treatment (daily injections, scans, retrieval)? yes/no</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['understand_the_treatment'])?$select_result['understand_the_treatment']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">If you have trouble with injections, do you have someone who can help? yes/n</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['trouble_with_injection'])?$select_result['trouble_with_injection']:""; ?></td>
		</tr>
	</table>
	<h3 style="margin-top: 20px;">4.Mental Status Examination</h3>
	<table class="table-bordered"  style="border:1px solid #cdcdcd; width:100%;">
		<tr>
			<td  style="border:1px solid #cdcdcd;">1. Apperance</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['apperance'])?$select_result['apperance']:""; ?></td>
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
	</table>
	<h3 style="margin-top: 20px;">5.Spouse History</h3>
	<table class="table-bordered"  style="border:1px solid #cdcdcd; width:100%;">
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
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['spouse_age'])?$select_result['spouse_age']:""; ?></td>
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
 