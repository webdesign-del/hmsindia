<?php

$segments = explode("/", $_SERVER['REQUEST_URI']);
$patient_id = end($segments); // Get last part of 

$select_query8 = "SELECT patient_id FROM `hms_doctor_consultation` WHERE patient_id='$patient_id' LIMIT 1";
$select_result8 = run_select_query($select_query8);

    $patient_id = $select_result8['patient_id'];

	    if(isset($_POST['submit'])){
			unset($_POST['submit']);
					
			$select_query = "SELECT * FROM `hms_prp` WHERE patient_id='$patient_id'";
			$select_result = run_select_query($select_query); 
			if(empty($select_result)){
				// mysql query to insert data
				$query = "INSERT INTO `hms_prp` SET ";
				$sqlArr = array();
				foreach( $_POST as $key=> $value )
				{
				  $sqlArr[] = " $key = '".addslashes($value)."'";
				}		
				$query .= implode(',' , $sqlArr);
			}else{
				// mysql query to update data
				$query = "UPDATE hms_prp SET ";
				foreach( $_POST as $key=> $value )
				{
				  $sqlArr[] = " $key = '".$value."'"	;
				}
				$query .= implode(',' , $sqlArr);
				$query .= " WHERE patient_id='$patient_id'";
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
		$select_query = "SELECT * FROM `hms_prp` WHERE patient_id='$patient_id'";
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
<input type="hidden" value="First Cycle" class="form" name="type">
    <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
							<input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
							<input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
							<input type="hidden" value="pending" name="status"> 
 <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PRE ASSESSMENT PRP</h3></td>
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
								<tr style="color: red;">
									<td>Date<br><input   type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"     name="date" class="form-control" ></td>
									<td>Time<br><input   type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"     name="time" class="form-control" ></td>
									<td>Indication<br><input type="text" value="<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>" maxlength="200" name="indication" class="form-control" ></td>
									<td>Allergies<br><input   type="text" value="<?php echo isset($select_result['allergies'])?$select_result['allergies']:""; ?>"     maxlength="50" name="allergies" class="form-control" ></td>
									<td>
										Consent<br>
										<label><input type="radio"  name="consent"  value="Yes"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'checked="checked"'; }?>   > Yes</label>
										<label><input type="radio"  name="consent"   value="No"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['consent']) && $select_result['consent'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
									</td>
									<td>
										ID <br>
										<label><input type="radio"  name="id_checked"  value="Yes"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?>   > Yes</label>
										<label><input type="radio"  name="id_checked"   value="No"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['id_checked']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
									</td>
								</tr>
								<tr style="color: red;">
									<td>BP<br><input   type="text" value="<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>"     name="bp" class="form-control" ></td>
									<td>
										Pulse<br>
										<label><input type="radio"  name="pulse"  value="Yes"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'checked="checked"'; }?>   > Yes</label>
										<label><input type="radio"  name="pulse"   value="No"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pulse']) && $select_result['pulse'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
									</td>
									<td>
										Resp<br>
										<label><input type="radio"  name="resp"  value="Yes"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'checked="checked"'; }?>   > Yes</label>
										<label><input type="radio"  name="resp"   value="No"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['resp']) && $select_result['resp'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
									</td>
									<td>
										Voided<br>
										<label><input type="radio"  name="voided"  value="Yes"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'checked="checked"'; }?>   > Yes</label>
										<label><input type="radio"  name="voided"   value="No"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['voided']) && $select_result['voided'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
									</td>
									<td>
										Ht (Cms)<br>
										<input  type="number" value="<?php echo isset($select_result['ht'])?$select_result['ht']:""; ?>"     min="0" name="ht" class="form-control" >
									</td>
									<td>
										Wt (Kg)<br>
										<input  type="number" value="<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?>"     min="0" name="wt" class="form-control" >
									</td>
								</tr>
								<tr>
									<td>
										Glasses<br>
										<label><input type="radio"  name="glasses"  value="Yes"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'checked="checked"'; }?>   > Yes</label>
										<label><input type="radio"  name="glasses"   value="No"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['glasses']) && $select_result['glasses'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
									</td>
									<td>
										Contacts<br>
										<label><input type="radio"  name="contacts"  value="Yes"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'checked="checked"'; }?>   > Yes</label>
										<label><input type="radio"  name="contacts"   value="No"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['contacts']) && $select_result['contacts'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
									</td>
									<td>
										Denture<br>
										<label><input type="radio"  name="denture"  value="Yes"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'checked="checked"'; }?>   > Yes</label>
										<label><input type="radio"  name="denture"   value="No"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['denture']) && $select_result['denture'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
									</td>
									<td>
										Dental bridge<br>
										<label><input type="radio"  name="dental_bridge"  value="Yes"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'checked="checked"'; }?>   > Yes</label>
										<label><input type="radio"  name="dental_bridge"   value="No"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
									</td>
									<td>
										Valuables with escort<br>
										<label><input type="radio"  name="escort"  value="Yes"  <?php if(isset($select_result['escort']) && $select_result['escort'] == "Yes"){echo 'checked="checked"'; }?>   > Yes</label>
										<label><input type="radio"  name="escort"   value="No"  <?php if(isset($select_result['escort']) && $select_result['escort'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['escort']) && $select_result['escort'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
									</td>
									<td>Last meal<br><input   type="time" value="<?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?>"     name="last_meal" class="form-control"></td>
								</tr>
							</table>
									</td>
								</tr>
							</table>
							<!-- /.card-body -->
							<div class="card-footer">
								<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
								<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
							</div>
						</form
						
						
		<!---  Print Button Start form --> 



<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none">  
		
						
	
 <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PRE ASSESSMENT PRP</h3></td>
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
								<tr style="color: red;">
									
<td style="border:1px solid #cdcdcd;">Date<br> <?php echo isset($select_result['date'])?$select_result['date']:""; ?> </td>
									
									
<td style="border:1px solid #cdcdcd;">Time<br> <?php echo isset($select_result['time'])?$select_result['time']:""; ?></td>

 <td style="border:1px solid #cdcdcd;">Indication<br> <?php echo isset($select_result['indication'])?$select_result['indication']:""; ?> </td>
<td style="border:1px solid #cdcdcd;">Allergies<br> <?php echo isset($select_result['allergies'])?$select_result['allergies']:""; ?> </td>
									<td style="border:1px solid #cdcdcd;">
										Consent<br>
										
				
<?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'yes'; }?>




				</td>
									<td style="border:1px solid #cdcdcd;">
										ID <br>
										
											
<?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'yes'; }?>
						</td>
								</tr>
								
								
								
<tr style="color: red; ">
<td style="border:1px solid #cdcdcd;">BP<br>  <?php echo isset($select_result['bp'])?$select_result['bp']:""; ?></td>
									 
									 <td style="border:1px solid #cdcdcd;">
										Pulse<br>
										
								
<?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'yes'; }?>






								</td>
									<td style="border:1px solid #cdcdcd;">
										Resp<br>
										
								
<?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'yes'; }?>




								</td>
									<td style="border:1px solid #cdcdcd;">
										Voided<br>
										
								
<?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'yes'; }?>








								</td>
									<td style="border:1px solid #cdcdcd;">
										Ht (Cms)<br>
										<?php echo isset($select_result['ht'])?$select_result['ht']:""; ?>
									</td>
									<td>
										Wt (Kg)<br>
									<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?>
									</td>
								</tr>
								<tr>
									<td style="border:1px solid #cdcdcd;">
										Glasses<br>
										 
								
<?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'yes'; }?>






								</td>
									<td style="border:1px solid #cdcdcd;">
										Contacts<br>
										
									
									
			<?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'yes'; }?>						
									
									
									
									
									</td>
									<td style="border:1px solid #cdcdcd;">
										Denture<br>
										
								

<?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'yes'; }?>



								</td>
									<td style="border:1px solid #cdcdcd;">
										Dental bridge<br>
										
  
  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'yes'; }?>
  
  
  
									</td>
									<td style="border:1px solid #cdcdcd;">
										Valuables with escort<br>
			<?php if(isset($select_result['escort']) && $select_result['escort'] == "Yes"){echo 'yes'; }?>				
									</td>
									
		<td style="border:1px solid #cdcdcd;">Last meal<br>   <?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?> </td>
								</tr>
							</table>
							
							
							
			
</div>				
<script> 
 function printtable() 
{
    //alert();
    
    
  $('.searchform').hide();
   $('.printbtn').hide();
  $('.printbtn').css('display', 'hide');
  $('.prtable').css('display', 'block');
  var divToPrint=document.getElementById('printtable');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}


</script>					
						
						