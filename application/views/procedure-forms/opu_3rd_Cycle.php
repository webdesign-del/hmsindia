<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        //var_dump($_POST);die;
		$select_query = "SELECT * FROM `hms_opu` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_opu` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_opu SET ";
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
	$select_query = "SELECT * FROM `hms_opu` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OPU 3RD CYCLE</h3></td>
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
			<td><b>OVUM PICK UP/Pre Fresh cycle self ET</b></td>
			<td>
				Date<br>
				<input  type="date" value="<?php echo isset($select_result['dates'])?$select_result['dates']:""; ?>"     name="dates" class="form-control" >
			</td>
			<td>
				Time<br>
				<input  type="time" value="<?php echo isset($select_result['times'])?$select_result['times']:""; ?>"     name="times" class="form-control" >
			</td>
			<td>
				Indication<br>
				<input  type="text" value="<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>"     maxlength="200" name="indication" class="form-control" >
			</td>
			<td>
				Allergies<br>
				<input  type="text" value="<?php echo isset($select_result['allergies'])?$select_result['allergies']:""; ?>"     maxlength="50" name="allergies" class="form-control" >
			</td>
			<td>
				Consent<br>
				<label><input type="radio"  name="consent"   value="Yes"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="consent"   value="No"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['consent']) && $select_result['consent'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>
				ID <br>
				<label><input type="radio"  name="id_checked"   value="Yes"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="id_checked"   value="No"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['id_checked']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
		</tr>
		<tr>
			<td>PRE ASSESSMENT</td>
			<td>
				BP<br>
				<input  type="text" value="<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>"     maxlength="20" name="bp" class="form-control" >
			</td>
			<td>
				Pulse<br>
				<label><input type="radio"  name="pulse"   value="Yes"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="pulse"   value="No"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pulse']) && $select_result['pulse'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>
				Resp<br>
				<label><input type="radio"  name="resp"   value="Yes"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="resp"   value="No"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['resp']) && $select_result['resp'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>
				Voided<br>
				<label><input type="radio"  name="voided"   value="Yes"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
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
			<td style="color: black;">
				Glasses<br>
				<label><input type="radio"  name="glasses"   value="Yes"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="glasses"   value="No"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['glasses']) && $select_result['glasses'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td style="color: black;">
				Contacts<br>
				<label><input type="radio"  name="contacts"   value="Yes"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="contacts"   value="No"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['contacts']) && $select_result['contacts'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td style="color: black;">
				Denture<br>
				<label><input type="radio"  name="denture"   value="Yes"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="denture"   value="No"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['denture']) && $select_result['denture'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td style="color: black;" colspan="2">
				Dental bridge<br>
				<label><input type="radio"  name="dental_bridge"   value="Yes"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="dental_bridge"   value="No"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td style="color: black;">
				Valuables with escort<br>
				<label><input type="radio"  name="escort"   value="Yes"  <?php if(isset($select_result['escort']) && $select_result['escort'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="escort"   value="No"  <?php if(isset($select_result['escort']) && $select_result['escort'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['escort']) && $select_result['escort'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td style="color: black;">
				Last meal<br>
				<input  type="time" value="<?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?>"     name="last_meal" class="form-control" >
			</td>
		</tr>
	</table>
	<table class="table-bordered" width="100%">
		<tr>
			<td colspan="8"><b>Prescriptions given</b></td>
		</tr>
		<tr>
			<td colspan="8">
				Injection Monocef 1 gm iv AST<br>
				Injection Pantoprazole 40 mg i.m. stat<br>
				Injection emset 8 mg iv stat<br>
				Other: <input  type="text" value="<?php echo isset($select_result['prescriptions_other'])?$select_result['prescriptions_other']:""; ?>"     maxlength="100" name="prescriptions_other" class="form-control" >
			</td>
		</tr>
		<tr>
			<td>Nurse</td>
			<td><input  type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>"     maxlength="20" name="nurse" class="form-control" ></td>
			<td>Doctor</td>
			<td><input  type="text" value="<?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?>"     maxlength="20" name="doctor" class="form-control" ></td>
			<td>Embryologist</td>
			<td><input  type="text" value="<?php echo isset($select_result['embryologist'])?$select_result['embryologist']:""; ?>"     maxlength="20" name="embryologist" class="form-control" ></td>
			<td>Anesthetist</td>
			<td><input  type="text" value="<?php echo isset($select_result['anesthetist'])?$select_result['anesthetist']:""; ?>"     maxlength="20" name="anesthetist" class="form-control" ></td>
		</tr>
	</table>
	<table class="table-bordered" width="100%">
		<tr>
			<td style="padding: 0;" width="20%">
				<table width="100%">
					<tr><td colspan="2"><b>PRE ASSESSMENT</b></td></tr>
					<tr>
						<td colspan="2">
							No sexual intercourse for 72 hrs<br>
							No active infection<br>
							No aspirin or NSAID a week before
						</td>
					</tr>
					<tr><td colspan="2"><b>Physical Examination</b></td></tr>
					<tr>
						<td>Resp</td>
						<td><input  type="text" value="<?php echo isset($select_result['resp2'])?$select_result['resp2']:""; ?>"     maxlength="20" name="resp2"></td>
					</tr>
					<tr>
						<td>CVS</td>
						<td><input  type="text" value="<?php echo isset($select_result['cvs'])?$select_result['cvs']:""; ?>"     maxlength="20" name="cvs"></td>
					</tr>
					<tr>
						<td>CNS</td>
						<td><input  type="text" value="<?php echo isset($select_result['cns'])?$select_result['cns']:""; ?>"     maxlength="20" name="cns"></td>
					</tr>
					<tr>
						<td>Abdominal</td>
						<td><input  type="text" value="<?php echo isset($select_result['emset2'])?$select_result['emset2']:""; ?>"     maxlength="20" name="emset2"></td>
					</tr>
					<tr>
						<td>Others</td>
						<td><input  type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>"     maxlength="100" name="others"></td>
					</tr>
				</table>
			</td>
			<td>
				<p>Written informed consent taken . All vitals  under normal range. The anesthetist examined the patient and given general anesthesia .Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by normal saline and draped. A transducer probe cover with jelly inside is put on the vaginal ultrasound probe,ultrasound guide attached to the probe ,it is introduced transvaginally  ,a baseline transvaginal ultrasound performed to see endometrium and ovaries. Following baseline scan , follicles were aspirated from both right and left ovaries . Follicular fluid aspirated and given to embryologist . <input  type="text" value="<?php echo isset($select_result['oocytes_retrieved'])?$select_result['oocytes_retrieved']:""; ?>"     maxlength="20" name="oocytes_retrieved" > oocytes retrieved.</p>
				<p>No complications seen. Bleeding was mild/moderate .Hemostasis achieved.Patient tolerated the procedure well and was transferred to the recovery room in satisfactory condition.</p>
				<p>Comments <input  type="text" value="<?php echo isset($select_result['comments'])?$select_result['comments']:""; ?>"     maxlength="20" name="comments"></p>
			</td>
		</tr>
	</table>
	<table class="table-bordered" width="100%">
		<tr><td colspan="2"><b>Intra Operative orders</b></td></tr>
		<tr>
			<td>
				<label><input type="radio"  name="npo_x_2hrs"   value="Yes"  <?php if(isset($select_result['npo_x_2hrs']) && $select_result['npo_x_2hrs'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="npo_x_2hrs"   value="No"  <?php if(isset($select_result['npo_x_2hrs']) && $select_result['npo_x_2hrs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['npo_x_2hrs']) && $select_result['npo_x_2hrs'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>NPO X 2HRS</td>
		</tr>
		<tr>
			<td>
				<label><input type="radio"  name="sips_of_fluid"   value="Yes"  <?php if(isset($select_result['sips_of_fluid']) && $select_result['sips_of_fluid'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="sips_of_fluid"   value="No"  <?php if(isset($select_result['sips_of_fluid']) && $select_result['sips_of_fluid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['sips_of_fluid']) && $select_result['sips_of_fluid'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>Sips of fluid after 2 hrs fld by soft diet</td>
		</tr>
		<tr>
			<td>
				<label><input type="radio"  name="fluid"   value="Yes"  <?php if(isset($select_result['fluid']) && $select_result['fluid'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="fluid"   value="No"  <?php if(isset($select_result['fluid']) && $select_result['fluid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['fluid']) && $select_result['fluid'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>i.v. fluid R.L or NS 500 ml@ 125 ml/hr</td>
		</tr>
		<tr>
			<td>
				<label><input type="radio"  name="paracetamol"   value="Yes"  <?php if(isset($select_result['paracetamol']) && $select_result['paracetamol'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="paracetamol"   value="No"  <?php if(isset($select_result['paracetamol']) && $select_result['paracetamol'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paracetamol']) && $select_result['paracetamol'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>i.v. paracetamol 100 ml (SOS)@8-10 drops/min</td>
		</tr>
		<tr>
			<td>
				<label><input type="radio"  name="justin_suppository"   value="Yes"  <?php if(isset($select_result['justin_suppository']) && $select_result['justin_suppository'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="justin_suppository"   value="No"  <?php if(isset($select_result['justin_suppository']) && $select_result['justin_suppository'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['justin_suppository']) && $select_result['justin_suppository'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>Justin suppository per rectally</td>
		</tr>
		<tr>
			<td>
				<label><input type="radio"  name="monitor_pulse"   value="Yes"  <?php if(isset($select_result['monitor_pulse']) && $select_result['monitor_pulse'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="monitor_pulse"   value="No"  <?php if(isset($select_result['monitor_pulse']) && $select_result['monitor_pulse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['monitor_pulse']) && $select_result['monitor_pulse'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>Monitor pulse/BP/Spo2 continuously</td>
		</tr>
		<tr>
			<td>
				<label><input type="radio"  name="monitor_bleeding"   value="Yes"  <?php if(isset($select_result['monitor_bleeding']) && $select_result['monitor_bleeding'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="monitor_bleeding"   value="No"  <?php if(isset($select_result['monitor_bleeding']) && $select_result['monitor_bleeding'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['monitor_bleeding']) && $select_result['monitor_bleeding'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>Monitor bleeding p/v every half hour</td>
		</tr>
		<tr>
			<td>
				<label><input type="radio"  name="remove_vaginal_pack"   value="Yes"  <?php if(isset($select_result['remove_vaginal_pack']) && $select_result['remove_vaginal_pack'] == "Yes"){echo 'checked="checked"'; }?>  > Yes</label>
				<label><input type="radio"  name="remove_vaginal_pack"   value="No"  <?php if(isset($select_result['remove_vaginal_pack']) && $select_result['remove_vaginal_pack'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['remove_vaginal_pack']) && $select_result['remove_vaginal_pack'] != "Yes"){echo 'checked="checked"';}?>  > No</label>
			</td>
			<td>Remove vaginal pack if any</td>
		</tr>
	</table>
	<table class="table-bordered" width="100%">
		<tr>
			<td>DATE</td>
			<td><input  type="date" value="<?php echo isset($select_result['date2'])?$select_result['date2']:""; ?>"     name="date2" class="form-control" ></td>
			<td>TIME</td>
			<td><input  type="time" value="<?php echo isset($select_result['time2'])?$select_result['time2']:""; ?>"     name="time2" class="form-control" ></td>
			<td>Doctor Name</td>
			<td><input  type="text" value="<?php echo isset($select_result['doctor_name'])?$select_result['doctor_name']:""; ?>"     name="doctor_name" class="form-control" ></td>
			<td>Doctor Signature</td>
			<td><input  type="text" value="<?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?>"     name="doctor_signature" class="form-control" ></td>
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

<table style="border:1px solid;width:100%;" class="fg45yu">
   <tr>
   <td style="width:50%;border:1px solid #ccc;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;border:1px solid #ccc;" colspan="10"><h3 style="margin-top:20px;">OPU 3RD CYCLE</h3></td>
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
	
	<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<td><b>OVUM PICK UP/Pre Fresh cycle self ET</b></td>
			<td style="border:1px solid #cdcdcd;">
				Date<br>
			<?php echo isset($select_result['dates'])?$select_result['dates']:""; ?>
			</td>
			<td style="border:1px solid #cdcdcd;">
				Time<br>
				<?php echo isset($select_result['times'])?$select_result['times']:""; ?>
			</td>
			<td style="border:1px solid #cdcdcd;">
				Indication<br>
				<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>
			</td>
			<td style="border:1px solid #cdcdcd;">
				Allergies<br>
				<?php echo isset($select_result['allergies'])?$select_result['allergies']:""; ?>"
			</td>
			<td style="border:1px solid #cdcdcd;">
				Consent<br>
							
		<?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'yes'; }?>	
			
			</td>
			<td style="border:1px solid #cdcdcd;">
				ID <br>
			
		
<?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'yes'; }?>

		</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">PRE ASSESSMENT</td>
			<td style="border:1px solid #cdcdcd;">
				BP<br>
				<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>
			</td>
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
			<td style="border:1px solid #cdcdcd;">
				Wt (Kg)<br>
				<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?>
			</td>
		</tr>
		<tr>
			<td style="color: black;border:1px solid #cdcdcd;">
				Glasses<br>
							
		<?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'yes'; }?>	
			
			
			</td>
			<td style="color: black; border:1px solid #cdcdcd;">
				Contacts<br>
						

<?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'yes'; }?>

		</td>
			<td style="color: black; border:1px solid #cdcdcd;">
				Denture<br>
				
			
			
			<?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'yes'; }?>
			
			
			
			</td>
			<td style="color: black; border:1px solid #cdcdcd;" colspan="2">
				Dental bridge<br>
							
	<?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'yes'; }?>
					
			
			</td>
		


		<td style="color: black;border:1px solid #cdcdcd;">
				Valuables with escort<br>
				
				
			
			<?php if(isset($select_result['escort']) && $select_result['escort'] == "Yes"){echo 'yes'; }?>
			
			</td>
			
			<td style="color: black; border:1px solid #cdcdcd;">
				Last meal<br>
				<?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?>
			</td>
		</tr>
	</table>
	
	
	
	
	<table class="table-bordered"  style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<td colspan="8" style="border:1px solid #cdcdcd;"><b>Prescriptions given</b></td>
		</tr>
		<tr>
			<td colspan="8" style="border:1px solid #cdcdcd;">
				Injection Monocef 1 gm iv AST<br>
				Injection Pantoprazole 40 mg i.m. stat<br>
				Injection emset 8 mg iv stat<br>
				Other:  <?php echo isset($select_result['prescriptions_other'])?$select_result['prescriptions_other']:""; ?>
			</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">Nurse</td>
			<td style="border:1px solid #cdcdcd;">  <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?></td>
			<td style="border:1px solid #cdcdcd;">Doctor</td>
			<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?> </td>
			<td style="border:1px solid #cdcdcd;" >Embryologist</td>
			<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['embryologist'])?$select_result['embryologist']:""; ?> </td>
			<td style="border:1px solid #cdcdcd;"> Anesthetist </td>
			<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['anesthetist'])?$select_result['anesthetist']:""; ?> </td>
		</tr>
	</table>
	
	
	<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<td  style="border:1px solid #cdcdcd; width:20%; padding: 0;">
				<table width="100%">
					<tr><td colspan="2"><b>PRE ASSESSMENT</b></td></tr>
					<tr>
						<td colspan="2" style="border:1px solid #cdcdcd;">
							No sexual intercourse for 72 hrs<br>
							No active infection<br>
							No aspirin or NSAID a week before
						</td>
					</tr>
					<tr><td colspan="2" style="border:1px solid #cdcdcd;"><b>Physical Examination</b></td></tr>
					<tr>
						<td style="border:1px solid #cdcdcd;">Resp</td>
						<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['resp2'])?$select_result['resp2']:""; ?> </td>
					</tr>
					<tr>
						<td style="border:1px solid #cdcdcd;">CVS</td>
						<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['cvs'])?$select_result['cvs']:""; ?> </td>
					</tr>
					<tr>
						<td style="border:1px solid #cdcdcd;">CNS</td>
						<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['cns'])?$select_result['cns']:""; ?> </td>
					</tr>
					<tr>
						<td style="border:1px solid #cdcdcd;">Abdominal</td>
						<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['emset2'])?$select_result['emset2']:""; ?>  </td>
					</tr>
					<tr>
						<td style="border:1px solid #cdcdcd;">Others</td>
						<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['others'])?$select_result['others']:""; ?></td>
					</tr>
				</table>
			</td>
			<td>
				<p>Written informed consent taken . All vitals  under normal range. The anesthetist examined the patient and given general anesthesia .Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by normal saline and draped. A transducer probe cover with jelly inside is put on the vaginal ultrasound probe,ultrasound guide attached to the probe ,it is introduced transvaginally  ,a baseline transvaginal ultrasound performed to see endometrium and ovaries. Following baseline scan , follicles were aspirated from both right and left ovaries . Follicular fluid aspirated and given to embryologist .
				 <?php echo isset($select_result['oocytes_retrieved'])?$select_result['oocytes_retrieved']:""; ?> oocytes retrieved.</p>
				<p>No complications seen. Bleeding was mild/moderate .Hemostasis achieved.Patient tolerated the procedure well and was transferred to the recovery room in satisfactory condition.</p>
				<p>Comments  <?php echo isset($select_result['comments'])?$select_result['comments']:""; ?> </p>
			</td>
		</tr>
	</table>
	<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
		<tr><td colspan="2" style="border:1px solid #cdcdcd;"><b>Intra Operative orders</b></td></tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">
				  
  <?php if(isset($select_result['npo_x_2hrs']) && $select_result['npo_x_2hrs'] == "Yes"){echo 'yes'; }?>
  
  			</td>
			<td style="border:1px solid #cdcdcd;">NPO X 2HRS</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">
							
		<?php if(isset($select_result['sips_of_fluid']) && $select_result['sips_of_fluid'] == "Yes"){echo 'yes'; }?>	
			
			</td>
			<td style="border:1px solid #cdcdcd;">Sips of fluid after 2 hrs fld by soft diet</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">
				
			
		<?php if(isset($select_result['fluid']) && $select_result['fluid'] == "Yes"){echo 'yes'; }?>	
			
			</td>
			<td style="border:1px solid #cdcdcd;">i.v. fluid R.L or NS 500 ml@ 125 ml/hr</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">
				  
  <?php if(isset($select_result['paracetamol']) && $select_result['paracetamol'] == "Yes"){echo 'yes'; }?>
  
			</td>
			<td style="border:1px solid #cdcdcd;">i.v. paracetamol 100 ml (SOS)@8-10 drops/min</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">
								
			
		<?php if(isset($select_result['justin_suppository']) && $select_result['justin_suppository'] == "Yes"){echo 'yes'; }?>	
			
			
			</td>
			<td style="border:1px solid #cdcdcd;">Justin suppository per rectally</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">
				  
  <?php if(isset($select_result['monitor_pulse']) && $select_result['monitor_pulse'] == "Yes"){echo 'yes'; }?>
			</td>
			<td style="border:1px solid #cdcdcd;">Monitor pulse/BP/Spo2 continuously</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">
				
  
  <?php if(isset($select_result['monitor_bleeding']) && $select_result['monitor_bleeding'] == "Yes"){echo 'yes'; }?>
  
			</td>
			<td style="border:1px solid #cdcdcd;">Monitor bleeding p/v every half hour</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">
							
<?php if(isset($select_result['remove_vaginal_pack']) && $select_result['remove_vaginal_pack'] == "Yes"){echo 'yes'; }?>	
						
			</td>
			
			<td style="border:1px solid #cdcdcd;">Remove vaginal pack if any</td>
		</tr>
	</table>
	
	
	
	
	
	<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<td style="border:1px solid #cdcdcd;" >DATE</td>
			<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></td>
			<td style="border:1px solid #cdcdcd;">TIME</td>
			<td style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['time2'])?$select_result['time2']:""; ?> </td>
			<td style="border:1px solid #cdcdcd;" >Doctor Name</td>
			<td style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['doctor_name'])?$select_result['doctor_name']:""; ?>  </td>
			<td style="border:1px solid #cdcdcd;">Doctor Signature</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?> </td>
		</tr>
	</table>
	<!-- /.card-body -->


</div>

<script>
function printtable() 
{
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