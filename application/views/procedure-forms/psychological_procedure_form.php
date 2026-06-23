<?php
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        $select_query = "SELECT * FROM `hms_psychological_procedure` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_psychological_procedure` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_psychological_procedure SET ";
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
    $select_query = "SELECT * FROM `hms_psychological_procedure` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PSYCHOLOGICAL PROCEDURE FORM</h3></td>
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
					<th style="width:20%">Details</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['details'])?$select_result['details']:""; ?>" name="details" class="form-control"></td>
				</tr>
				<tr>
					<th>Details</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['details_2'])?$select_result['details_2']:""; ?>" name="details_2" class="form-control"></td>
				</tr>
				<tr>
					<th>Details</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['details_3'])?$select_result['details_3']:""; ?>" name="details_3" class="form-control"></td>
				</tr>
				<tr>
					<th>Details</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['details_4'])?$select_result['details_4']:""; ?>" name="details_4" class="form-control"></td>
				</tr>
				<tr>
					<th>Details</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['details_5'])?$select_result['details_5']:""; ?>" name="details_5" class="form-control"></td>
				</tr>
				<tr>
					<th>Details</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['details_6'])?$select_result['details_6']:""; ?>" name="details_6" class="form-control"></td>
				</tr>
				<tr>
					<th>Details</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['details_7'])?$select_result['details_7']:""; ?>" name="details_7" class="form-control"></td>
				</tr>
				<tr>
					<th>Details</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['details_8'])?$select_result['details_8']:""; ?>" name="details_8" class="form-control"></td>
				</tr>
				<tr>
					<th>Details</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['details_9'])?$select_result['details_9']:""; ?>" name="details_9" class="form-control"></td>
				</tr>
				<tr>
					<th>Details</th>
					<td style="padding: 0;"><input  type="text" value="<?php echo isset($select_result['details_10'])?$select_result['details_10']:""; ?>" name="details_10" class="form-control"></td>
				</tr>
			</table>
			<table class="table-bordered" width="100%">
				<tr>
					<td>DATE</td>
					<td><input type="date" name="date_4" class="form-control" value="<?php echo isset($select_result['date_4'])?$select_result['date_4']:""; ?>"   ></td>
					<td>TIME</td>
					<td><input type="time" name="time" class="form-control" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"   ></td>
					<td>Psychologists Name</td>
					<td><input type="text" name="psychologists_name" maxlength="20" class="form-control" value="<?php echo isset($select_result['psychologists_name'])?$select_result['psychologists_name']:""; ?>"   ></td>
					<td>Psychologists Signature</td>
					<td><input type="text" name="psychologists_signature" maxlength="20" class="form-control" value="<?php echo isset($select_result['psychologists_signature'])?$select_result['psychologists_signature']:""; ?>"   ></td>
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
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PSYCHOLOGICAL PROCEDURE FORM</h3></td>
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
					<th style="border:1px solid #cdcdcd;">Details</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;">  <?php echo isset($select_result['details'])?$select_result['details']:""; ?> </td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">Details</th>
					<td style="border:1px solid #cdcdcd; padding: 0;"> <?php echo isset($select_result['details_2'])?$select_result['details_2']:""; ?> </td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">Details</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['details_3'])?$select_result['details_3']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">Details</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['details_4'])?$select_result['detail_4']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">Details</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['details_5'])?$select_result['details_5']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">Details</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['details_6'])?$select_result['details_6']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">Details</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['details_7'])?$select_result['details_7']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">Details</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['details_8'])?$select_result['details_8']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">Details</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"><?php echo isset($select_result['details_9'])?$select_result['details_9']:""; ?></td>
				</tr>
				<tr>
					<th style="border:1px solid #cdcdcd;">Details</th>
					<td    style="border:1px solid #cdcdcd; padding: 0;"> <?php echo isset($select_result['details_10'])?$select_result['details_10']:""; ?> </td>
				</tr>
					</table>
					<table class="table-bordered" width="100%">

				<tr>

					<td style="border:1px solid #cdcdcd;">DATE</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_4'])?$select_result['date_4']:""; ?></td>
					<td style="border:1px solid #cdcdcd;">TIME</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['time'])?$select_result['time']:""; ?></td>
				</tr>
				<tr>
					<td style="border:1px solid #cdcdcd;">Psychologists Name</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['psychologists_name'])?$select_result['psychologists_name']:""; ?></td>
					<td style="border:1px solid #cdcdcd;">Psychologists Signature</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['psychologists_signature'])?$select_result['psychologists_signature']:""; ?></td>

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