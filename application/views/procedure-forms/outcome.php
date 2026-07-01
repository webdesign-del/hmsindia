<?php
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
		
	  $wife_name  = $_POST['wife_name'];
	  $husband_name  = $_POST['husband_name'];
      $wife_phone  = $_POST['wife_phone'];
	  $wife_age  = $_POST['wife_age'];
	  $wife_address  = $_POST['wife_address'];
	  $female_pregnancy_other_p  = $_POST['female_pregnancy_other_p'];
	  $female_pregnancy_other_l  = $_POST['female_pregnancy_other_l'];
	  $female_pregnancy_other_a  = $_POST['female_pregnancy_other_a'];
	  $details_management_advised  = $_POST['details_management_advised'];
	  $IVF_Consultant  = $_POST['IVF_Consultant'];
	  $sex_of_child = $_POST['sex_of_child'];
	  $live_birth_at = $_POST['live_birth_at'];
	  $live_birth_by = $_POST['live_birth_by'];
      $doctor_name = $_POST['doctor_name'];
	  $center  = $_POST['center'];
	  
	  unset($_POST['wife_name']);
      unset($_POST['wife_phone']);
	  unset($_POST['husband_name']);
      unset($_POST['wife_age']);
	  unset($_POST['wife_address']);
	  unset($_POST['female_pregnancy_other_p']);
	  unset($_POST['female_pregnancy_other_l']);
	  unset($_POST['female_pregnancy_other_a']);
	  unset($_POST['details_management_advised']);
	  unset($_POST['IVF_Consultant']);
	  unset($_POST['center']);

        $select_query = "SELECT * FROM `hms_outcome` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 

        if(empty($select_result)){

            // mysql query to insert data
            $query = "INSERT INTO `hms_outcome` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		

            $query .= implode(',' , $sqlArr);
			//Insert into pcp_ndt table
		    $query2 = "INSERT INTO `pcp_ndt` (patient_id, wife_name, husband_name, wife_phone, wife_age, wife_address, female_pregnancy_other_p, female_pregnancy_other_l, female_pregnancy_other_a, details_management_advised, IVF_Consultant, further_referredfor_dellvery, outcome_of_pregnancy, malformation_in_newborn, center, test_type, type, date) values 
		   ('$patient_id','$wife_name', '$husband_name', '$wife_phone', '$wife_age', '$wife_address', 'P:$female_pregnancy_other_p', 'L:$female_pregnancy_other_l', 'A:$female_pregnancy_other_a', '$details_management_advised','$doctor_name', '$live_birth_at $live_birth_by', '$sex_of_child', 'nil', '$center', 'DELIVERY','DELIVERY','" . date('Y-m-d h:i:s') . "')";
            $result = run_form_query($query2);
        }else{

            // mysql query to update data
            $query = "UPDATE hms_outcome SET ";
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

    $select_query = "SELECT * FROM `hms_outcome` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$select_result = run_select_query($select_query); 
	
    $sql2 = "SELECT * FROM `hms_patient_medical_info` WHERE patient_id='$patient_id'";
    $select_result2 = run_select_query($sql2);

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
	<input type="hidden" value="<?php echo $select_result2['female_name']; ?>" class="form" name="wife_name">
	<input type="hidden" value="<?php echo $select_result2['male_name']; ?>" class="form" name="husband_name">
	<input type="hidden" value="<?php echo $select_result2['wife_phone']; ?>" class="form" name="wife_phone">
	<input type="hidden" value="<?php echo $select_result2['female_age']; ?>" class="form" name="wife_age">
	<input type="hidden" value="<?php echo $select_result2['female_pregnancy_other_p']; ?>" class="form" name="female_pregnancy_other_p">
    <input type="hidden" value="<?php echo $select_result2['female_pregnancy_other_l']; ?>" class="form" name="female_pregnancy_other_l">
    <input type="hidden" value="<?php echo $select_result2['female_pregnancy_other_a']; ?>" class="form" name="female_pregnancy_other_a">
    <input type="hidden" value="<?php echo $select_result2['details_management_advised']; ?>" class="form" name="details_management_advised">
	<input type="hidden" value="<?php echo $select_result2['center']; ?>" class="form" name="center">
	<input type="hidden" value="<?php echo $select_result3['wife_address']; ?>" class="form" name="wife_address">
	<input type="hidden" value="pending" name="status">
	<input type="hidden" value="First Cycle" name="type">	
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OUTCOME</h3></td>
   </tr>
</table>
     				  <table width="100%" class="vb45rt">
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
					<td>
						Live birth on <input type="date" value="<?php echo isset($select_result['live_birth_on'])?$select_result['live_birth_on']:""; ?>" name="live_birth_on"> @ <input type="text" maxlength="20"  value="<?php echo isset($select_result['live_birth_at'])?$select_result['live_birth_at']:""; ?>" name="live_birth_at"> by <input type="text"  value="<?php echo isset($select_result['live_birth_by'])?$select_result['live_birth_by']:""; ?>" maxlength="20" name="live_birth_by"> Sex of child/children:
						<input type="radio"  name="sex_of_child"   value="Male"  <?php if(isset($select_result['sex_of_child']) && $select_result['sex_of_child'] == "Male"){echo 'checked="checked"'; }?>   >
						<label for="Male"> Male</label>
						<input type="radio"  name="sex_of_child"   value="Female"  <?php if(isset($select_result['sex_of_child']) && $select_result['sex_of_child'] == "Female"){echo 'checked="checked"'; }
  else if(isset($select_result['sex_of_child']) && $select_result['sex_of_child'] != "Yes"){echo 'checked="checked"';}?>   >
						<label for="Female"> Female</label>
					</td>
				</tr>
			</table>
			<table class="table-bordered" width="100%">
				<tr>
					<td>DATE</td>
					<td><input type="date" name="date" class="form-control" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"   ></td>
					<td>TIME</td>
					<td><input type="time" name="time" class="form-control" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"   ></td>
					<td>Doctor Name</td>
					<td><input type="text" name="doctor_name" readonly="" maxlength="30" class="form-control" value="<?php if (!empty($select_result['doctor_name'])) {  echo $select_result['doctor_name']; } else {  echo isset($_SESSION['logged_doctor']['name']) ? $_SESSION['logged_doctor']['name'] : ''; }  ?>"   ></td>
					<td>Doctor Signature</td>
					<td><input type="text" name="doctor_signature" maxlength="20" class="form-control" value="<?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?>"   ></td>
				</tr>
			</table>
			<!-- /.card-body -->
			<div class="card-footer">
				<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
				<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
			</div>
</form>
		<!--                             Print       TABLE                         -->	
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;">
<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OUTCOME</h3></td>
   </tr>
</table>
     				  <table width="100%" class="vb45rt">
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

		<table class="table-bordered" width="100%">
				<tr>
				<td style="border:1px solid #cdcdcd;">Live birth on <?php echo isset($select_result['live_birth_on'])?$select_result['live_birth_on']:""; ?></td>
				<td style="border:1px solid #cdcdcd;">@ <?php echo isset($select_result['live_birth_at'])?$select_result['live_birth_at']:""; ?></td>
				<td style="border:1px solid #cdcdcd;">by <?php echo isset($select_result['live_birth_by'])?$select_result['live_birth_by']:""; ?></td>
				<td style="border:1px solid #cdcdcd;">.Sex of child/children:
				    <?php if(isset($select_result['sex_of_child']) && $select_result['sex_of_child'] == "Male"){echo 'Male'; }?>
					<?php if(isset($select_result['sex_of_child']) && $select_result['sex_of_child'] == "Female"){echo 'Female'; }?>
				</td>
				</tr>
			</table>
			<table class="table-bordered" width="100%">

				<tr>

					<td style="border:1px solid #cdcdcd;">DATE</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date'])?$select_result['date']:""; ?></td>
					<td style="border:1px solid #cdcdcd;">TIME</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['time'])?$select_result['time']:""; ?></td>
				</tr>
				<tr>
					<td style="border:1px solid #cdcdcd;">Doctor Name</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['doctor_name'])?$select_result['doctor_name']:""; ?></td>
					<td style="border:1px solid #cdcdcd;">Doctor Signature</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?></td>

				</tr>

			</table>



<!-- Main Table END -->
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