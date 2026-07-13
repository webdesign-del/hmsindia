<?php
if(isset($_POST['submit'])){
	unset($_POST['submit']);
	
	$select_query = "SELECT * FROM `hms_fnac` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$select_result = run_select_query($select_query); 
	if(empty($select_result)){
		// mysql query to insert data
		$query = "INSERT INTO `hms_fnac` SET ";
		$sqlArr = array();
		foreach( $_POST as $key=> $value )
		{
		  $sqlArr[] = " $key = '".addslashes($value)."'";
		}		
		$query .= implode(',' , $sqlArr);
	}else{
		// mysql query to update data
		$query = "UPDATE hms_fnac SET ";
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
	$select_query = "SELECT * FROM `hms_fnac` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$select_result = run_select_query($select_query);
	
	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);

	$id = $procedure_id;
	$query = $this->db->select('procedure_name')
                  ->where('ID', $id)
                  ->get('hms_procedures');

	if ($query->num_rows() > 0) {
		$row = $query->row();
		$procedure_name = $row->procedure_name; // Yahan aapka name mil jayega
	//	echo $procedure_name;
	} else {
		echo "Procedure not found";
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
  <input type="hidden" value="First Cycle" name="type"> 
    			<div class="container1 red-field form mt-5 mb-5">
  <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">FNAC TESTES AND TESA</h3></td>
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
					<ul class="d-flex mb-1 mt-2 list-unstyled">
        				<div class = "table-responsive">
            				<table class="table table-bordered table-hover table-sm">
            					<thead>
									<tr style="color: red;">
						                <th colspan="2"><strong>Indication</strong> : <input  type="text" value="<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>" maxlength="200" placeholder="Indication" name="indication"></td>
						                <td colspan="2">Name Of Procedure : <?php echo $procedure_name; ?></td>
						                <td colspan="1">Ecg  : <br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['ecg']) && $select_result['ecg'] == "Yes"){echo 'checked="checked"'; }?>    name="ecg"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['ecg']) && $select_result['ecg'] == "No"){echo 'checked="checked"'; }
  											else if(isset($select_result['ecg']) && $select_result['ecg'] != "Yes"){echo 'checked="checked"';}?>   name="ecg"> No</label></td>
									    <td colspan="1"> Echo : <br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['echo']) && $select_result['echo'] == "Yes"){echo 'checked="checked"'; }?>    name="echo"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['echo']) && $select_result['echo'] == "No"){echo 'checked="checked"'; }
  											else if(isset($select_result['echo']) && $select_result['echo'] != "Yes"){echo 'checked="checked"';}?>   name="echo"> No</label></td>
									
									</tr>
					                <tr style="color: red;">
						                <th><strong>FNAC TESTES AND TESA</strong></th>
						                <td>Date <input  type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"     name="date"></td>
						                <td>Time <input  type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"     name="time"></td>
						                <td>ALLERGIES <input  type="text" value="<?php echo isset($select_result['allergy_yes'])?$select_result['allergy_yes']:""; ?>"     maxlength="50" placeholder="ALLERGIES" name="allergy_yes"></td>
										<td>
											Consent<br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'checked="checked"'; }?>    name="consent"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['consent']) && $select_result['consent'] != "Yes"){echo 'checked="checked"';}?>   name="consent"> No</label>
										</td>
										<td>
											ID <br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?>    name="id_checked"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['id_checked']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?>   name="id_"> No</label>
										</td>
					                </tr>
						            <tr style="color: red;">
						              	<td>BP <input  type="text" value="<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>"     maxlength="20" name="bp"></td>
						              	<td>
						              		PULSE<br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'checked="checked"'; }?>    name="pulse"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pulse']) && $select_result['pulse'] != "Yes"){echo 'checked="checked"';}?>   name="pulse"> No</label>
										</td>
						              	<td>
						              		RESP<br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'checked="checked"'; }?>    name="resp"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['resp']) && $select_result['resp'] != "Yes"){echo 'checked="checked"';}?>   name="resp"> No</label>
										</td>
						              	<td>
						              		VOIDED<br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'checked="checked"'; }?>    name="voided"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['voided']) && $select_result['voided'] != "Yes"){echo 'checked="checked"';}?>   name="voided"> No</label>
										</td>
						              	<td>HT (Cms)<input  type="number" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"     min="0" name="ht"></td>
						              	<td>WT (Kg)<input  type="number" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"     min="0" name="wt"></td>
						            </tr>
						            <tr>
						              	<td>
						              		Glasses<br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'checked="checked"'; }?>    name="glasses"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['glasses']) && $select_result['glasses'] != "Yes"){echo 'checked="checked"';}?>   name="glasses"> No</label>
										</td>
						              	<td>
						              		Contacts<br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'checked="checked"'; }?>    name="contacts"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['contacts']) && $select_result['contacts'] != "Yes"){echo 'checked="checked"';}?>   name="contacts"> No</label>
										</td>
						              	<td>
						              		Denture<br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'checked="checked"'; }?>    name="denture"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['denture']) && $select_result['denture'] != "Yes"){echo 'checked="checked"';}?>   name="denture"> No</label>
										</td>
						              	<td>
						              		Dental bridge<br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'checked="checked"'; }?>    name="dental_bridge"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] != "Yes"){echo 'checked="checked"';}?>   name="dental_bridge"> No</label>
										</td>
						              	<td>
						              		Valuables with escort<br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "Yes"){echo 'checked="checked"'; }?>    name="valuables_with_escort"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] != "Yes"){echo 'checked="checked"';}?>   name="valuables_with_escort"> No</label>
										</td>
										<td>Last meal <input  type="time" value="<?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?>"     name="last_meal"></td>
						            </tr>
					          	</thead>
					    	</table>
					    </div>
					</ul>
					<table>
					   	<thead>
					        <tr>
					            <th>
									<span class= "mr-2">HPE</span>
									<label><input type="radio"  name="hpe"  value="yes"  <?php if(isset($select_result['hpe']) && $select_result['hpe'] == "yes"){echo 'checked="checked"'; }?>   > Yes</label>
									<label><input type="radio"  name="hpe"   value="No"  <?php if(isset($select_result['hpe']) && $select_result['hpe'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hpe']) && $select_result['hpe'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
								</th>
					        </tr>
					   	</thead>  
					</table>
					<div class="table-responsive">
					<table class="table table-bordered table-hover table-sm red-field tableMg">
					 	<thead>
					        <tr><th><b>Prescriptions Given</b></th></tr>
							<tr><td>Injection Monocef 1 gm iv AST</td></tr>
							<tr><td>Injection Pantoprazole 40 mg i.m. stat</td></tr>
							<tr><td>Other: <textarea name="other_prescription" maxlength="100"><?php echo isset($select_result['other_prescription'])?$select_result['other_prescription']:""; ?></textarea></td></tr>
					   	</thead>
					</table>
					<table class="table table-bordered table-hover  table-sm red-field tableMg">
					    <thead>
					        <tr>
					            <th>NURSE <input  type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>"     name="nurse"></th>
					            <th>DOCTOR <input  type="text" value="<?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?>"     name="doctor"></th>
					             <th>EMBRYOLOGIST <input  type="text" value="<?php echo isset($select_result['embryologist'])?$select_result['embryologist']:""; ?>"     name="embryologist"></th>
					         </tr>
					    </thead>
					</table>
					<table class="table table-bordered table-hover  table-sm red-field tableMg">
					    <thead>
					        <tr>
					            <th>
					            	<ul class="list-unstyled">
					                    <li class="d-flex mb-1 mt-2"><span>PRE ASSESSMENT</span></li>
					                    <li class="d-flex mb-1 mt-2"><span class="mr-2">No sexual intercourse for 72 hours</span></li>
					                    <li class="d-flex mb-1 mt-2"><span class="mr-2">No active infection</span></li>
					                    <li class="d-flex mb-1 mt-2"><span>No aspirin or NSAID a week before</span></li>
					                    <li class="d-flex mb-1 mt-2"><span>Physical Examination</span></li> 
					                    <li class="d-flex mb-1 ml-4">
					                    	<span class="mr-5">Resp</span>
					                    	<input  type="text" value="<?php echo isset($select_result['Resp2'])?$select_result['Resp2']:""; ?>"     maxlength="20" name="Resp2">
					                    </li> 
					                    <li class="d-flex mb-1 ml-4">
					                    	<span class="mr-5 ml-1">CVS</span>
					                    	<input  type="text" value="<?php echo isset($select_result['CVS'])?$select_result['CVS']:""; ?>"     maxlength="20" name="CVS">
					                    </li> 
					                    <li class="d-flex mb-1 ml-4">
					                    	<span class="mr-5 ml-1">CNS</span>
					                    	<input  type="text" value="<?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?>"     maxlength="20" name="CNS">
					                    </li>
					                    <li class="d-flex mb-1">
					                    	<span class="mr-4">Abdominal</span>
					                    	<input  type="text" value="<?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?>"     maxlength="20" name="abdominal">
					                   	</li>
					                    <li class="d-flex mb-1">
					                    	<span class="mr-5">Others</span>
					                    	<input class="ml-2"  type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>"     maxlength="100" name="others">
					                   	</li>
					                </ul>
					            </th>
					            <th>
									Written informed consent taken. All vitals  under normal range. Patient put in supine position ,under all sterile conditions, the scrotum was cleansed by betadine and draped. The area around the spermatic cord was locally anesthetized by injecting 5 ml of 2% lidocaine. The aspiration was then performed in the center as well as in the upper and lower poles of each testis using a 23 G needle with a 20 ml syringe attached to it. A constant negative pressure was applied to the syringe when the needle reached the center of the testis.<br>
									The aspirated tissue from each location bilaterally was placed on a separate slide, air-dried, and stained with May-Gr端nwald Giemsa and sent to pathologist. (FNAC)<br>
									The aspiration was done with gentle back and forth movements of the needle at different angles in each puncture location multiple times.The aspirated tissue from each location was placed in separate dishes and given to embryologist. (TESA/PESA)<br>
									Scrotal bandage was done.  Patient stood the procedure well. No complications.<br>
									Others <input  type="text" value="<?php echo isset($select_result['others2'])?$select_result['others2']:""; ?>"     maxlength="100" name="others2">
					            </th>
					        </tr>
					    </thead>
					</table>
					<table class="table table-bordered table-hover table-sm red-field tableMg">
					 	<thead>
					        <tr>
					            <th colspan="2"><p>Doctors signature <input  type="text" value="<?php echo isset($select_result['doctors_signature'])?$select_result['doctors_signature']:""; ?>"     name="doctors_signature"></p></th>
					        </tr>
					   </thead>  
					</table>
				</div>
					<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
					<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
			</form>
			
			
			
			
			
			
			
			
			
			
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 				
			
			
			
			
	
    			    <table class="table-bordered"  style="width:100%;border:1px solid #cdcdcd;">
					<tr>
                <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
			 <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><center><h2 class="text-alogn-center">FNAC TESTES AND TESA</h2></center></td>
					
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
<table>
        				<tr>
        					<td  colspan="4"  style="border:1px solid #cdcdcd;" >
                			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
                			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
                			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
                			            ){?>
                			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
                			    <?php } ?>
                			</td>
        				</tr>
        			</table>
				
        			<ul class="d-flex mb-1 mt-2 list-unstyled">
        			
   <table class="table table-bordered table-hover table-sm" style="width:100%; border:1px solid #cdcdcd;">
    <thead>
	<tr style="color: red;">
		<th style="border:1px solid #cdcdcd;"  colspan="3"><strong>Indication</strong> : <?php echo isset($select_result['indication'])?$select_result['indication']:""; ?></td>
		<td style="border:1px solid #cdcdcd;"  colspan="2">Name Of Procedure : <?php echo $procedure_name; ?></td>
		<td  style="border:1px solid #cdcdcd;" colspan="1">Ecg  : <?php if(isset($select_result['ecg']) && $select_result['ecg'] == "Yes"){echo 'yes'; }?>
		                       <?php if(isset($select_result['ecg']) && $select_result['ecg'] == "No"){echo 'no'; } ?></td>
		<td  style="border:1px solid #cdcdcd;" colspan="1"> Echo : <br><?php if(isset($select_result['echo']) && $select_result['echo'] == "Yes"){echo 'yes'; }?>
		   						<?php if(isset($select_result['echo']) && $select_result['echo'] == "No"){echo 'no'; } ?></td>
									
									</tr>
	<tr style="color: red;">
	<th  style="border:1px solid #cdcdcd;" colspan="2"> <strong>FNAC TESTES/TESA/TESE/MICRO TESE</strong></th>
	<td  style="border:1px solid #cdcdcd;" >Date <?php echo isset($select_result['date'])?$select_result['date']:""; ?></td>
	<td  style="border:1px solid #cdcdcd;" >Time <?php echo isset($select_result['time'])?$select_result['time']:""; ?>     </td>
	<td  style="border:1px solid #cdcdcd;" >ALLERGIES <?php echo isset($select_result['allergy_yes'])?$select_result['allergy_yes']:""; ?></td>
	<td  style="border:1px solid #cdcdcd;" >Consent<br>

										
	<?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'yes'; }?>
	<?php if(isset($select_result['consent']) && $select_result['consent'] == "No"){echo 'no'; }?>								
										
										</td>
										<td  style="border:1px solid #cdcdcd;" >
											ID <br>
								
<?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'yes'; }?>
<?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "No"){echo 'no'; }?>

									</td>
					                </tr>
						            <tr style="color: red;">
						              	<th  style="border:1px solid #cdcdcd;" ><strong>PRE ASSESSMENT</strong></th>
<td  style="border:1px solid #cdcdcd;" > BP <?php echo isset($select_result['bp'])?$select_result['bp']:""; ?></td>
						              	<td  style="border:1px solid #cdcdcd;" >
						              		PULSE<br>
															
<?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'yes'; }?>

								</td>
						              	<td  style="border:1px solid #cdcdcd;" >
						              		RESP<br>
	<?php if(isset($select_result['resp']) && $select_result['resp'] == "No"){echo 'no'; }?>									
<?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'yes'; }?>
	</td>
						              	<td  style="border:1px solid #cdcdcd;" >
						              		VOIDED<br>
		<?php if(isset($select_result['voided']) && $select_result['voided'] == "No"){echo 'no'; }?>								
		<?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'yes'; }?>
					</td>
	<td  style="border:1px solid #cdcdcd;" >HT (Cms)<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?></td>
		<td  style="border:1px solid #cdcdcd;" >WT (Kg)<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?></td>
						            </tr>
						            <tr>
						              	<td  style="border:1px solid #cdcdcd;" >
						              		Glasses<br>
												
<?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'yes'; }?>
<?php if(isset($select_result['glasses']) && $select_result['glasses'] == "No"){echo 'no'; }?>

									</td>
						              	<td  style="border:1px solid #cdcdcd;" >
						              		Contacts<br>
	<?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'yes'; }?>
	<?php if(isset($select_result['contacts']) && $select_result['contacts'] == "No"){echo 'no'; }?>
									</td>
						              	<td  style="border:1px solid #cdcdcd;" >
						              		Denture<br>
										
<?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'yes'; }?>
<?php if(isset($select_result['denture']) && $select_result['denture'] == "No"){echo 'no'; }?>
									</td>
						              	<td style="border:1px solid #cdcdcd;" >
						              		Dental bridge<br>
									
<?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'yes'; }?>
<?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "No"){echo 'no'; }?>

									</td>
						              	<td colspan="2" style="border:1px solid #cdcdcd;" >
						              		Valuables with escort<br>
									
<?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "Yes"){echo 'yes'; }?>
<?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "No"){echo 'no'; }?>
									</td>
	<td  style="border:1px solid #cdcdcd;" >Last meal <?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?> </td>
						            </tr>
					          	</thead>
					    	</table>
					   
					</ul>
					<table style="width:100%;border:1px solid #cdcdcd;">
					   	<thead>
					        <tr>
					            <th  style="border:1px solid #cdcdcd;" >
									<span class= "mr-2">HPE</span>
									
				<?php if(isset($select_result['hpe']) && $select_result['hpe'] == "Yes"){echo 'yes'; }?>
					
								</th>
					        </tr>
					   	</thead>  
					</table>
				
					<table class="table table-bordered table-hover table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
					 	<thead>
					        <tr><th  style="border:1px solid #cdcdcd;" ><b>Prescriptions Given</b></th></tr>
							<tr><td  style="border:1px solid #cdcdcd;" >Injection Monocef 1 gm iv AST</td></tr>
							<tr><td  style="border:1px solid #cdcdcd;" >Injection Pantoprazole 40 mg i.m. stat</td></tr>
<tr><td  style="border:1px solid #cdcdcd;" >Other: <?php echo isset($select_result['other_prescription'])?$select_result['other_prescription']:""; ?> </td></tr>
					   	</thead>
					</table>
					<table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
					    <thead>
					        <tr>
 <th  style="border:1px solid #cdcdcd;" >NURSE <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>    </th>
					            <th  style="border:1px solid #cdcdcd;" >DOCTOR <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?></th>
					             <th  style="border:1px solid #cdcdcd;" >EMBRYOLOGIST <?php echo isset($select_result['embryologist'])?$select_result['embryologist']:""; ?></th>
					         </tr>
					    </thead>
					</table>
					<table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
					    <thead>
					        <tr>
					            <th  style="border:1px solid #cdcdcd;" >
					            	<ul class="list-unstyled">
					                    <li class="d-flex mb-1 mt-2"><span>PRE ASSESSMENT</span></li>
					                    <li class="d-flex mb-1 mt-2"><span class="mr-2">No sexual intercourse for 72 hours</span></li>
					                    <li class="d-flex mb-1 mt-2"><span class="mr-2">No active infection</span></li>
					                    <li class="d-flex mb-1 mt-2"><span>No aspirin or NSAID a week before</span></li>
					                    <li class="d-flex mb-1 mt-2"><span>Physical Examination</span></li> 
					                    <li class="d-flex mb-1 ml-4">
					                    	<span class="mr-5">Resp</span>
					                    <?php echo isset($select_result['Resp2'])?$select_result['Resp2']:""; ?>     
					                    </li> 
					                    <li class="d-flex mb-1 ml-4">
					                    	<span class="mr-5 ml-1">CVS</span>
					             <?php echo isset($select_result['CVS'])?$select_result['CVS']:""; ?>
					                    </li> 
					                    <li class="d-flex mb-1 ml-4">
					                    	<span class="mr-5 ml-1">CNS</span>
					               <?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?>
					                    </li>
					                    <li class="d-flex mb-1">
					                    	<span class="mr-4">Abdominal</span>
					            <?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?>
					                   	</li>
					                    <li class="d-flex mb-1">
					                    	<span class="mr-5">Others</span>
					     <?php echo isset($select_result['others'])?$select_result['others']:""; ?>
					                   	</li>
					                </ul>
					            </th>
					            <th  style="border:1px solid #cdcdcd;" >
								<!--	Written informed consent taken. All vitals  under normal range. Patient put in supine position ,under all sterile conditions, the scrotum was cleansed by betadine and draped. The area around the spermatic cord was locally anesthetized by injecting 5 ml of 2% lidocaine. The aspiration was then performed in the center as well as in the upper and lower poles of each testis using a 23 G needle with a 20 ml syringe attached to it. A constant negative pressure was applied to the syringe when the needle reached the center of the testis.<br>
									The aspirated tissue from each location bilaterally was placed on a separate slide, air-dried, and stained with May-Gr端nwald Giemsa and sent to pathologist. (FNAC)<br>
									The aspiration was done with gentle back and forth movements of the needle at different angles in each puncture location multiple times.The aspirated tissue from each location was placed in separate dishes and given to embryologist. (TESA/PESA)<br>
									Scrotal bandage was done.  Patient stood the procedure well. No complications.<br>
									Others-->
                                The aspirated tissue from each location bilaterally was placed on a separate slide, air-dried, and stained with May-Gr端nwald Giemsa and sent to pathologist. (FNAC TESTES)<br/>
								The aspiration was done with gentle back and forth movements of the needle at different angles in each puncture location multiple times.The aspirated tissue from each location was placed in separate dishes and given to embryologist. (TESA/TESE/M-TESE)<br/>
								Scrotal bandage was done. Patient stood the procedure well. No complications.<?php echo isset($select_result['others2'])?$select_result['others2']:""; ?>
					            </th>
					        </tr>
					    </thead>
					</table>
					<table class="table table-bordered table-hover table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
					 	<thead>
					        <tr>
					            <th colspan="2"><p>Doctors signature <?php echo isset($select_result['doctors_signature'])?$select_result['doctors_signature']:""; ?> </p></th>
					        </tr>
					   </thead>  
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
						