<?php

    if(isset($_POST['submit'])){

      unset($_POST['submit']);

      $select_query = "SELECT * FROM `hms_prior_embryo_transfer` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";

      $select_result = run_select_query($select_query); 

      if(empty($select_result)){

          // mysql query to insert data

          $query = "INSERT INTO `hms_prior_embryo_transfer` SET ";

          $sqlArr = array();

          foreach( $_POST as $key=> $value )

          {

            $sqlArr[] = " $key = '".addslashes($value)."'";

          }		

          $query .= implode(',' , $sqlArr);

      }else{

          // mysql query to update data

          $query = "UPDATE hms_prior_embryo_transfer SET ";

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

	$select_query = "SELECT * FROM `hms_prior_embryo_transfer` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
	<input type="hidden" value="<?php echo $_SESSION['logged_doctor']['doctor_id'] ?>" class="form" name="doctor_id">
	<input type="hidden" value="pending" name="status"> 
	<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PRIOR TO EMBRYO TRANSFER</h3></td>
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

					<td style="border:1px solid #cdcdcd;"><center><h4>INSTRUCTIONS PRIOR TO EMBRYO TRANSFER</h4></center></td>

				</tr>

				<tr>

					<td style="border:1px solid #cdcdcd;">LAST INJ.OF PROGESTERONE @ <input type="text" value="<?php echo isset($select_result['inj_progesterone_at'])?$select_result['inj_progesterone_at"']:""; ?>" name="inj_progesterone_at" id="inj_progesterone_at"> on <input type="text" value="<?php echo isset($select_result['inj_progesterone_on'])?$select_result['inj_progesterone_on']:""; ?>" id="inj_progesterone_on" name="inj_progesterone_on"></td>

				</tr>

				<tr>

					<td style="border:1px solid #cdcdcd;">TAKE INJ. CLEXANE @ <input type="text" value="<?php echo isset($select_result['inj_clexane_at'])?$select_result['inj_clexane_at']:""; ?>" id="" name=""> on <input type="text" value="<?php echo isset($select_result['inj_clexane_on'])?$select_result['inj_clexane_on']:""; ?>" id="" name=""> </td>

				</tr>

				<tr>

					<td style="border:1px solid #cdcdcd;">Take Inj. HCG @ <input type="text" value="<?php echo isset($select_result['inj_hcg_at'])?$select_result['inj_hcg_at']:""; ?>" id="" name=""> on <input type="text" value="<?php echo isset($select_result['inj_hcg_on'])?$select_result['inj_hcg_on']:""; ?>" id="" name=""> </td>

				</tr>

				<tr>

					<td style="border:1px solid #cdcdcd;">Embryo transfer on <input type="text" value="<?php echo isset($select_result['embryo_transfer_on'])?$select_result['embryo_transfer_on']:""; ?>" id="" name=""> @ <input type="text" value="<?php echo isset($select_result['embryo_transfer_at'])?$select_result['embryo_transfer_at']:""; ?>" id="" name=""></td>

				</tr>

				<tr>

					<td style="border:1px solid #cdcdcd;">Admit @ <input type="text" value="<?php echo isset($select_result['admit_at'])?$select_result['admit_at"']:""; ?>" id="" name=""> in day care on <input type="text" value="<?php echo isset($select_result['admit_on'])?$select_result['admit_on']:""; ?>" id="" name=""> at <input type="text" value="<?php echo isset($select_result['admit_at2'])?$select_result['admit_at2']:""; ?>" id="" name=""></td>

				</tr>

			</table>

	<div class="card-footer">
		<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
	</div>
</form>

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;">  
    <table class="table-bordered" width="100%">

				<tr>

					<td style="border:1px solid #cdcdcd;"><center><h4>INSTRUCTIONS PRIOR TO EMBRYO TRANSFER</h4></center></td>

				</tr>

				<tr>

					<td style="border:1px solid #cdcdcd;">LAST INJ.OF PROGESTERONE @ <?php echo isset($select_result['inj_progesterone_at'])?$select_result['inj_progesterone_at']:""; ?> on <?php echo isset($select_result['inj_progesterone_on'])?$select_result['inj_progesterone_on']:""; ?></td>

				</tr>

				<tr>

					<td style="border:1px solid #cdcdcd;">TAKE INJ. CLEXANE @ <?php echo isset($select_result['inj_clexane_at'])?$select_result['inj_clexane_at']:""; ?> on <?php echo isset($select_result['inj_clexane_on'])?$select_result['inj_clexane_on']:""; ?> </td>

				</tr>

				<tr>

					<td style="border:1px solid #cdcdcd;">Take Inj. HCG @ <?php echo isset($select_result['inj_hcg_at'])?$select_result['inj_hcg_at']:""; ?> on <?php echo isset($select_result['inj_hcg_on'])?$select_result['inj_hcg_on']:""; ?></td>

				</tr>

				<tr>

					<td style="border:1px solid #cdcdcd;">Embryo transfer on <?php echo isset($select_result['embryo_transfer_on'])?$select_result['embryo_transfer_on']:""; ?> @ <?php echo isset($select_result['embryo_transfer_at'])?$select_result['embryo_transfer_at']:""; ?></td>

				</tr>

				<tr>

					<td style="border:1px solid #cdcdcd;">Admit @ <?php echo isset($select_result['admit_at'])?$select_result['admit_at']:""; ?> in day care on <?php echo isset($select_result['admit_on'])?$select_result['admit_on']:""; ?> at <?php echo isset($select_result['admit_at2'])?$select_result['admit_at2']:""; ?></td>

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
