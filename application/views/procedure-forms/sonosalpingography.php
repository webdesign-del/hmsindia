<?php
	if(isset($_POST['submit'])){
		unset($_POST['submit']);
		
        if(!empty($_FILES['pic1']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['pic1']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['pic1']['tmp_name'], $destination.$NewImageName);
			$_POST['pic1'] = $transaction_img;
		}
		if(!empty($_FILES['pic2']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['pic2']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['pic2']['tmp_name'], $destination.$NewImageName);
			$_POST['pic2'] = $transaction_img;
		}
        
        $select_query = "SELECT * FROM `sonosalpingography` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `sonosalpingography` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE sonosalpingography SET ";
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
	$select_prp = "SELECT * FROM `hms_prp` WHERE patient_id='$patient_id'";
	$select_result_prp = run_select_query($select_prp);
	
    $select_query = "SELECT * FROM `sonosalpingography` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">SONOSALPINGOGRAPHY</h3></td>
   </tr>
</table>
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
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

	<table class="table-bordered">
		<tr>
			<td colspan="2">
			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
			            ){?>
			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
			    <?php } ?>
			</td>
			<td style="color: red;">
				Date<br>
				<input  type="date" value="<?php echo isset($select_result_prp['date'])?$select_result_prp['date']:""; ?>"     name="date" class="form-control" >
			</td>
			<td style="color: red;">
				Time<br>
				<input  type="time" value="<?php echo isset($select_result_prp['time'])?$select_result_prp['time']:""; ?>"     name="time" class="form-control" >
			</td>
			<td style="color: red;">
				Indication<br>
				<input  type="text" value="<?php echo isset($select_result_prp['indication'])?$select_result_prp['indication']:""; ?>" maxlength="50" name="indication" class="form-control" >
			</td>
			<td style="color: red;">
				Allergies<br>
				<input  type="text" value="<?php echo isset($select_result_prp['allergies'])?$select_result_prp['allergies']:""; ?>"     maxlength="50" name="allergies" class="form-control" >
			</td>
			<td style="color: red;">
				Consent<br>
				<input type="radio"  name="consent"   value="Yes"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="consent"   value="No"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['consent']) && $select_result['consent'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td style="color: red;">
				ID <br>
				<input type="radio"  name="id_checked"   value="Yes"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="id_checked"   value="No"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['id_checked']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td style="color: red;">
				Date<br>
				<input  type="date" value="<?php echo isset($select_result_prp['date2'])?$select_result_prp['date2']:""; ?>"     name="date2" class="form-control" >
			</td>
		</tr>
		<tr>
			<td style="color: red;">PRE ASSESSMENT</td>
			<td style="color: red;">
				BP<br>
				<input  type="text" value="<?php echo isset($select_result_prp['bp'])?$select_result_prp['bp']:""; ?>"     maxlength="20" name="bp" class="form-control" >
			</td>
			<td style="color: red;">
				PULSE<br>
				<input type="radio"  name="pulse"   value="Yes"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="pulse"   value="No"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pulse']) && $select_result['pulse'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td style="color: red;">
				RESP<br>
				<input type="radio"  name="resp"   value="Yes"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="resp"   value="No"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['resp']) && $select_result['resp'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td style="color: red;">
				Voided<br>
				<input type="radio"  name="voided"   value="Yes"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="voided"   value="No"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['voided']) && $select_result['voided'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td style="color: red;">
				Ht (Cms)<br>
				<input  type="number" value="<?php echo isset($select_result_prp['ht'])?$select_result_prp['ht']:""; ?>"     min="0" name="ht" class="form-control" >
			</td>
			<td style="color: red;">
				Wt (Kg)<br>
				<input  type="number" value="<?php echo isset($select_result_prp['wt'])?$select_result_prp['wt']:""; ?>"     min="0" name="wt" class="form-control" >
			</td>
			<td style="color: red;">
				BP<br>
				<input  type="text" value="<?php echo isset($select_result_prp['bp2'])?$select_result_prp['bp2']:""; ?>"     maxlength="20" name="bp2" class="form-control" >
			</td>
		</tr>
		<tr>
			<td>
				Glasses<br>
				<input type="radio"  name="glasses"   value="Yes"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="glasses"   value="No"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['glasses']) && $select_result['glasses'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td>
				Contacts<br>
				<input type="radio"  name="contacts"   value="Yes"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="contacts"   value="No"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['contacts']) && $select_result['contacts'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td>
				Denture<br>
				<input type="radio"  name="denture"   value="Yes"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="denture"   value="No"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['denture']) && $select_result['denture'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td colspan="2">
				Dental bridge<br>
				<input type="radio"  name="dental_bridge"   value="Yes"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="dental_bridge"   value="No"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td>
				Valuables with escort<br>
				<input type="radio"  name="escort"   value="Yes"  <?php if(isset($select_result['escort']) && $select_result['escort'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="escort"   value="No"  <?php if(isset($select_result['escort']) && $select_result['escort'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['escort']) && $select_result['escort'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td>
				Last meal<br>
				<input  type="time" value="<?php echo isset($select_result_prp['last_meal'])?$select_result_prp['last_meal']:""; ?>"     name="last_meal" class="form-control" >
			</td>
			<td>
				Indication<br>
				<input  type="text" value="<?php echo isset($select_result_prp['indication'])?$select_result_prp['indication']:""; ?>" name="indication2" class="form-control" readonly="">
			</td>
		</tr>
	</table>
	<table class="table-bordered" width="100%">
		<tr>
			<td colspan="2">Prescriptions given</td>
		</tr>
		<tr>
			<td>
				<input type="radio"  name="inj_drotin"   value="Yes"  <?php if(isset($select_result['inj_drotin']) && $select_result['inj_drotin'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="inj_drotin"   value="No"  <?php if(isset($select_result['inj_drotin']) && $select_result['inj_drotin'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['inj_drotin']) && $select_result['inj_drotin'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td>Injection Drotin 1 amp i.m. stat</td>
		</tr>
		<tr>
			<td>
				<input type="radio"  name="inj_pantoprazole"   value="Yes"  <?php if(isset($select_result['inj_pantoprazole']) && $select_result['inj_pantoprazole'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="inj_pantoprazole"   value="No"  <?php if(isset($select_result['inj_pantoprazole']) && $select_result['inj_pantoprazole'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['inj_pantoprazole']) && $select_result['inj_pantoprazole'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td>Injection Pantoprazole 40 mg i.m. stat</td>
		</tr>
		<tr>
			<td>
				<input type="radio"  name="lignocaine_jelly"   value="Yes"  <?php if(isset($select_result['lignocaine_jelly']) && $select_result['lignocaine_jelly'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="lignocaine_jelly"   value="No"  <?php if(isset($select_result['lignocaine_jelly']) && $select_result['lignocaine_jelly'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['lignocaine_jelly']) && $select_result['lignocaine_jelly'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td>Lignocaine jelly applied vaginally</td>
		</tr>
		<tr>
			<td>
				<input type="radio"  name="other_prescription"   value="Yes"  <?php if(isset($select_result['other_prescription']) && $select_result['other_prescription'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="other_prescription"   value="No"  <?php if(isset($select_result['other_prescription']) && $select_result['other_prescription'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['other_prescription']) && $select_result['other_prescription'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td>Other</td>
		</tr>
	</table>
	<table class="table-bordered">
		<tr style="color: red;">
			<td>NURSE</td>
			<td><input  type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>"     maxlegth="20" name="nurse" class="form-control"></td>
			<td>DOCTOR</td>
			<td><input  type="text" value="<?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?>"     maxlegth="20" name="doctor" class="form-control"></td>
		</tr>
		<tr>
			<td width="35%" style="padding: 0;" colspan="2">
				<table width="100%">
					<tr>
						<td colspan="2">Physical Examination</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  name="resp2"   value="Yes"  <?php if(isset($select_result['resp2']) && $select_result['resp2'] == "Yes"){echo 'checked="checked"'; }?> > Yes
							<input type="radio"  name="resp2"   value="No"  <?php if(isset($select_result['resp2']) && $select_result['resp2'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['resp2']) && $select_result['resp2'] != "Yes"){echo 'checked="checked"';}?>  > No
						</td>
						<td>Resp</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  name="cvs"   value="Yes"  <?php if(isset($select_result['cvs']) && $select_result['cvs'] == "Yes"){echo 'checked="checked"'; }?> > Yes
							<input type="radio"  name="cvs"   value="No"  <?php if(isset($select_result['cvs']) && $select_result['cvs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cvs']) && $select_result['cvs'] != "Yes"){echo 'checked="checked"';}?>  > No
						</td>
						<td>CVS</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  name="cns"   value="Yes"  <?php if(isset($select_result['cns']) && $select_result['cns'] == "Yes"){echo 'checked="checked"'; }?> > Yes
							<input type="radio"  name="cns"   value="No"  <?php if(isset($select_result['cns']) && $select_result['cns'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cns']) && $select_result['cns'] != "Yes"){echo 'checked="checked"';}?>  > No
						</td>
						<td>CNS</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  name="abdominal"  value="Yes"  <?php if(isset($select_result['abdominal']) && $select_result['abdominal'] == "Yes"){echo 'checked="checked"'; }?> > Yes
							<input type="radio"  name="abdominal"   value="No"  <?php if(isset($select_result['abdominal']) && $select_result['abdominal'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['abdominal']) && $select_result['abdominal'] != "Yes"){echo 'checked="checked"';}?>  > No
						</td>
						<td>Abdominal</td>
					</tr>
					<tr>
						<td>
							<input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> > Yes
							<input type="radio"  name="others2"   value="No"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['others2']) && $select_result['others2'] != "Yes"){echo 'checked="checked"';}?>  > No
						</td>
						<td>Others</td>
					</tr>
				</table>
			</td>
			<td colspan="2">
				<p>Written informed consent taken . All vitals  under normal range. Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. A condom with lubricated jelly is put on the vaginal ultrasound probe,it is introduced transvaginally  ,a baseline transvaginal ultrasound performed to see endometrium and ovaries. Following baseline scan , a sterile <input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> >Cuscos speculum <input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> >Sims speculum with tenaculum introduced .The cervix cleansed with betadine.A 6 Fr Foleys catheter introduced in the uterine cavity ,its bulb inflated and catheter clamped . The speculum is taken out and again the transvaginal ultrasound probe introduced .Sterile saline 20 ml is then injected into the uterine cavity through the catheter and ultrasound is performed to see spillage from both the tubes and shape of uterine cavity </p>
				<p>Rt tube spillage <input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> >seen <input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> > not seen. Lt.Tube spillage <input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> >seen<input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> >not seen.Uterine cavity <input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> >regular <input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> >irregular .Fluid is <input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> >seen <input type="radio"  name="others2"   value="Yes"  <?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'checked="checked"'; }?> >not seen in POD.</p>
				<p>No complications seen. Catheter deflated and removed from uterine cavity and speculum also removed.</p>
				<p>Patient stood the procedure well.</p>
			</td>
		</tr>
	</table>
	<table class="table-bordered" style="width: 100%; color: red;">
		<tr>
			<td style="text-align: center;">SONOSALPINGOGRAPHY REPORT</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
	</table>
	<table class="table-bordered" style="width: 100%; color: red;">
		<tr>
			<td><b>History</b></td>
			<td>
				Primary Infertility
				<input type="radio"  name="primary_infertility"   value="Yes"  <?php if(isset($select_result['primary_infertility']) && $select_result['primary_infertility'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="primary_infertility"   value="No"  <?php if(isset($select_result['primary_infertility']) && $select_result['primary_infertility'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['primary_infertility']) && $select_result['primary_infertility'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td>
				Secondary Infertility
				<input type="radio"  name="secondary_infertility"   value="Yes"  <?php if(isset($select_result['secondary_infertility']) && $select_result['secondary_infertility'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="secondary_infertility"   value="No"  <?php if(isset($select_result['secondary_infertility']) && $select_result['secondary_infertility'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['secondary_infertility']) && $select_result['secondary_infertility'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
			<td>
				Recurrent Pregnancy Loss
				<input type="radio"  name="recurrent_pregnancy_loss"   value="Yes"  <?php if(isset($select_result['recurrent_pregnancy_loss']) && $select_result['recurrent_pregnancy_loss'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="recurrent_pregnancy_loss"   value="No"  <?php if(isset($select_result['recurrent_pregnancy_loss']) && $select_result['recurrent_pregnancy_loss'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['recurrent_pregnancy_loss']) && $select_result['recurrent_pregnancy_loss'] != "Yes"){echo 'checked="checked"';}?>  > No
			</td>
		</tr>
	</table>
	<table class="table-bordered" style="width: 100%; color: red;">
		<tr>
			<td>Other</td>
			<td><input  type="text" value="<?php echo isset($select_result['other'])?$select_result['other']:""; ?>"     maxlength="100" name="other" class="form-control"></td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td>Right Fallopian Tube:</td>
			<td>
				<input type="radio" <?php if(isset($select_result['right_fallopian']) && $select_result['right_fallopian'] == "seen"){echo 'checked="checked"'; }?>    name="right_fallopian" value="seen"> free spill of saline seen
				<input type="radio" <?php if(isset($select_result['right_fallopian']) && $select_result['right_fallopian'] == "not_seen"){echo 'checked="checked"'; }?>   name="right_fallopian" value="not_seen"> not seen
			</td>
		</tr>
		<tr>
			<td>Other/ Description</td>
			<td><input  type="text" value="<?php echo isset($select_result['right_description'])?$select_result['right_description']:""; ?>"     maxlength="100" name="right_description" class="form-control"></td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td>Left Fallopian Tube:</td>
			<td>
				<input type="radio" <?php if(isset($select_result['left_fallopian']) && $select_result['left_fallopian'] == "seen"){echo 'checked="checked"'; }?>   name="left_fallopian" value="seen"> free spill of saline seen
				<input type="radio" <?php if(isset($select_result['left_fallopian']) && $select_result['left_fallopian'] == "not_seen"){echo 'checked="checked"'; }?>   name="left_fallopian" value="not_seen"> not seen
			</td>
		</tr>
		<tr>
			<td>Other/ Description</td>
			<td><input  type="text" value="<?php echo isset($select_result['left_description'])?$select_result['left_description']:""; ?>"     maxlength="100" name="left_description" class="form-control"></td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
	</table>
	<table class="table-bordered" style="width: 100%; color: red;">
		<tr>
			<td>Uterine cavity :</td>
			<td>
				<input type="radio" <?php if(isset($select_result['uterine_cavity']) && $select_result['uterine_cavity'] == "regular"){echo 'checked="checked"'; }?>   name="uterine_cavity" value="regular"> Regular
				<input type="radio" <?php if(isset($select_result['uterine_cavity']) && $select_result['uterine_cavity'] == "Filling defect"){echo 'checked="checked"'; }?>  name="uterine_cavity" value="Filling defect"> Filling defect
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2" style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Describe</td>
						<td><input  type="text" value="<?php echo isset($select_result['described'])?$select_result['described']:""; ?>"     name="described" class="form-control"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>POD:</td>
			<td>
				<input type="radio"  <?php if(isset($select_result['pod']) && $select_result['pod'] == "seen"){echo 'checked="checked"'; }?>   name="pod" value="seen"> fluid seen
				<input type="radio"  <?php if(isset($select_result['pod']) && $select_result['pod'] == "seen"){echo 'checked="checked"'; }?>   name="pod" value="not seen"> Not Seen
			</td>
		</tr>
		<tr>
			<td></td>
			<td>Pressure: Normal</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2" style="padding: 0;">
				<table width="100%">
					<tr>
						<td>Complications:</td>
						<td><input type="radio"  <?php if(isset($select_result['complications']) && $select_result['complications'] == "Pain"){echo 'checked="checked"'; }?>   name="complications" value="Pain"> Pain</td>
						<td><input type="radio"  <?php if(isset($select_result['complications']) && $select_result['complications'] == "None"){echo 'checked="checked"'; }?>  name="complications" value="None"> None</td>
						<td><input type="radio"  <?php if(isset($select_result['complications']) && $select_result['complications'] == "Mild"){echo 'checked="checked"'; }?>  name="complications" value="Mild"> Mild</td>
						<td><input type="radio"  <?php if(isset($select_result['complications']) && $select_result['complications'] == "Moderate"){echo 'checked="checked"'; }?>  name="complications" value="Moderate"> Moderate</td>
						<td><input type="radio"  <?php if(isset($select_result['complications']) && $select_result['complications'] == "Severe"){echo 'checked="checked"'; }?>  name="complications" value="Severe"> Severe</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>Other:</td>
			<td><input  type="text" value="<?php echo isset($select_result['other2'])?$select_result['other2']:""; ?>"     maxlength="100" name="other2" class="form-control"></td>
		</tr>
		<tr>
			<td></td>
			<td>Comments/Drawing</td>
			<td><input  type="text" value="<?php echo isset($select_result['comments'])?$select_result['comments']:""; ?>"     maxlength="100" name="comments" class="form-control"></td>
		</tr>
	</table>
	<table class="table-bordered" style="width: 100%; color: red;">
		<tr>
			<td>Pic1:</td>
			<td><input type="file" name="pic1" class="form-control">
			<a target="_blank" href="<?php echo !empty($select_result['pic1'])?$select_result['pic1']:"javascript:void(0)"; ?>">Download</a>
			</td>
			<td>Pic2:</td>
			<td><input type="file" name="pic2" class="form-control">
			<a target="_blank" href="<?php echo !empty($select_result['pic2'])?$select_result['pic2']:"javascript:void(0)"; ?>">Download</a>
			</td>
		</tr>
	</table>
	<table class="table-bordered" style="width: 100%; color: red;">
		<tr>
			<td colspan="2"><b>Post Procedure orders</b></td>
		</tr>
		<tr>
			<td>
				<input type="radio"  name="normal_diet"   value="Yes"  <?php if(isset($select_result['normal_diet']) && $select_result['normal_diet'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="normal_diet"   value="No"  <?php if(isset($select_result['normal_diet']) && $select_result['normal_diet'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['normal_diet']) && $select_result['normal_diet'] != "Yes"){echo 'checked="checked"';}?>  > No
			
			<br>
			<input type="text" value="<?php echo isset($select_result['normal_diet_text'])?$select_result['normal_diet_text']:""; ?>"  maxlength="25" name="normal_diet_text">
			
			
			</td>
			<td>
				Normal diet
			</td>
		</tr>
		<tr>
			<td>
				<input type="radio"  name="tab_doxycycline"   value="Yes"  <?php if(isset($select_result['tab_doxycycline']) && $select_result['tab_doxycycline'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="tab_doxycycline"   value="No"  <?php if(isset($select_result['tab_doxycycline']) && $select_result['tab_doxycycline'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['tab_doxycycline']) && $select_result['tab_doxycycline'] != "Yes"){echo 'checked="checked"';}?>  > No
  
  <br>
			<input type="text" value="<?php echo isset($select_result['tab_doxycycline_text'])?$select_result['tab_doxycycline_text']:""; ?>"  maxlength="25" name="tab_doxycycline_text">
  
  
			</td>
			<td>
				Tab.Doxycycline 100 mg twice daily one morning one evening after meals for 5 days
			</td>
		</tr>
		<tr>
			<td>
				<input type="radio"  name="cap_pantoprazole"   value="Yes"  <?php if(isset($select_result['cap_pantoprazole']) && $select_result['cap_pantoprazole'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="cap_pantoprazole"   value="No"  <?php if(isset($select_result['cap_pantoprazole']) && $select_result['cap_pantoprazole'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cap_pantoprazole']) && $select_result['cap_pantoprazole'] != "Yes"){echo 'checked="checked"';}?>  > No
  
  
   <br>
			<input type="text" value="<?php echo isset($select_result['cap_pantoprazole_text'])?$select_result['cap_pantoprazole_text']:""; ?>"  maxlength="25" name="cap_pantoprazole_text">
  
  
  
			</td>
			<td>
				Cap Pantoprazole 40 mg once daily in empty stomach for 5 days
			</td>
		</tr>
		<tr>
			<td>
				<input type="radio"  name="tab_crocin"   value="Yes"  <?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="tab_crocin"   value="No"  <?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] != "Yes"){echo 'checked="checked"';}?>  > No
			
			
			 <br>
			<input type="text" value="<?php echo isset($select_result['tab_crocin_text'])?$select_result['tab_crocin_text']:""; ?>"  maxlength="25" name="tab_crocin_text">
			
			
			</td>
			<td>
				Tab Crocin 500 mg thrice daily eight hourly after meals for 2 days
			</td>
		</tr>
		<tr>
			<td>
				<input type="radio"  name="report"   value="Yes"  <?php if(isset($select_result['report']) && $select_result['report'] == "Yes"){echo 'checked="checked"'; }?> > Yes
				<input type="radio"  name="report"   value="No"  <?php if(isset($select_result['report']) && $select_result['report'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['report']) && $select_result['report'] != "Yes"){echo 'checked="checked"';}?>  > No
			
			
		 <br>
			<input type="text" value="<?php echo isset($select_result['report_text'])?$select_result['report_text']:""; ?>"  maxlength="25" name="report_text" >	
			
			
			
			</td>
			<td>
				To report if giddiness /nausea/vomiting/bleeding/pain/fever/purulent discharge immediately
			</td>
		</tr>
		<tr>
			<td>Doctors signature</td>
			<td><input  type="text" value="<?php echo isset($select_result['doctors_signature'])?$select_result['doctors_signature']:""; ?>"     name="doctors_signature" class="form-control"></td>
		</tr>
	</table>
	<!-- /.card-body -->
	<div class="card-footer">
		<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
		<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
	</div>
</form>



<!-----        Print Button -----------> 


<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none"> 




<!--    1. sonosalpingography -->

	<table class="table-bordered" style="border:1px solid #cdcdcd; width:100%;">
	<tr>
             <td width="50%" colspan="4" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
			 <td width="50%" colspan="4" style="border:1px solid #cdcdcd;"><center><h2>SONOSALPINGOGRAPHY</h2></center></td>		
            </tr>
			</table>
			<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
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
		<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">	
		<tr>
			<td colspan="1" style="border:1px solid #cdcdcd;">
			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
			            ){?>
			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
			    <?php } ?>
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Date<br>
				<?php echo isset($select_result['date'])?$select_result['date']:""; ?>
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Time<br>
			<?php echo isset($select_result['time'])?$select_result['time']:""; ?>
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Indication<br>
				<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Allergies<br>
				<?php echo isset($select_result['allergies'])?$select_result['allergies']:""; ?>
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Consent<br>
			
		<?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'Yes'; }?>
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				ID <br>
			
			<?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'Yes'; }?>
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Date<br>
			<?php echo isset($select_result['date2'])?$select_result['date2']:""; ?>
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">PRE ASSESSMENT</td>
			<td  style="border:1px solid #cdcdcd;">
				BP<br>
				<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>
			</td>
			<td  style="border:1px solid #cdcdcd;">
				PULSE<br>
							
		<?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'Yes'; }?>	
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				RESP<br>
				
				<?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'Yes'; }?>	
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Voided<br>
				 			
				<?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'Yes'; }?>	
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Ht (Cms)<br>
				<?php echo isset($select_result['ht'])?$select_result['ht']:""; ?>
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Wt (Kg)<br>
			<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?>
			</td>
			<td  style="border:1px solid #cdcdcd;">
				BP<br>
				<?php echo isset($select_result['bp2'])?$select_result['bp2']:""; ?>
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">
				Glasses<br>
							
	<?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'Yes'; }?>	
			
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Contacts<br>
				
			
				<?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'Yes'; }?>
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Denture<br>
				
			
	<?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'Yes'; }?>
					
			
			</td>
			<td colspan="2" style="border:1px solid #cdcdcd;">
				Dental bridge<br>
				
		<?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'Yes'; }?>		
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Valuables with escort<br>
				 
			
				<?php if(isset($select_result['escort']) && $select_result['escort'] == "Yes"){echo 'Yes'; }?>
			
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Last meal<br>
				<?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?>
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Indication<br>
				<?php echo isset($select_result['indication2'])?$select_result['indication2']:""; ?> </td>
		</tr>
	</table>
	
	<table class="table-bordered"  style="border:1px solid #cdcdcd; width:100%;">
		<tr>
			<td colspan="2" style="border:1px solid #cdcdcd;">Prescriptions given</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">
				
			
		<?php if(isset($select_result['inj_drotin']) && $select_result['inj_drotin'] == "Yes"){echo 'Yes'; }?>	
			
			
			</td>
			<td  style="border:1px solid #cdcdcd;">Injection Drotin 1 amp i.m. stat</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">
				
			
		<?php if(isset($select_result['inj_pantoprazole']) && $select_result['inj_pantoprazole'] == "Yes"){echo 'Yes'; }?>		
				</td>
			<td  style="border:1px solid #cdcdcd;">Injection Pantoprazole 40 mg i.m. stat</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">
				
		<?php if(isset($select_result['lignocaine_jelly']) && $select_result['lignocaine_jelly'] == "Yes"){echo 'Yes'; }?>	
		
		
		</td>
			<td  style="border:1px solid #cdcdcd;">Lignocaine jelly applied vaginally</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">
				
			
	<?php if(isset($select_result['other_prescription']) && $select_result['other_prescription'] == "Yes"){echo 'Yes'; }?>	
		
			
			</td>
			<td  style="border:1px solid #cdcdcd;">Other</td>
		</tr>
	</table>
	
	<table class="table-bordered"  style="border:1px solid #cdcdcd; width:100%;">
		<tr style="color: red;">
			<td  style="border:1px solid #cdcdcd;">NURSE</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?></td>
			<td  style="border:1px solid #cdcdcd;">DOCTOR</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?></td>
		</tr>
		<tr>
			<td  colspan="2" style="padding: 0; border:1px solid #cdcdcd; width:35%;">
				<table style="border:1px solid #cdcdcd;width:100%;">
					<tr>
						<td colspan="2" style="border:1px solid #cdcdcd;">Physical Examination</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
					<?php if(isset($select_result['resp2']) && $select_result['resp2'] == "Yes"){echo 'Yes'; }?>	
		</td>
						<td  style="border:1px solid #cdcdcd;">Resp</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
					
	<?php if(isset($select_result['cvs']) && $select_result['cvs'] == "Yes"){echo 'Yes'; }?>	

					</td>
						<td  style="border:1px solid #cdcdcd;">CVS</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
						
	<?php if(isset($select_result['cns']) && $select_result['cns'] == "Yes"){echo 'Yes'; }?>					
						
						
						</td>
						<td  style="border:1px solid #cdcdcd;">CNS</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
						
						
		<?php if(isset($select_result['abdominal']) && $select_result['abdominal'] == "Yes"){echo 'Yes'; }?>					
						</td>
						<td  style="border:1px solid #cdcdcd;">Abdominal</td>
					</tr>
					<tr>
						<td  style="border:1px solid #cdcdcd;">
							
						
			<?php if(isset($select_result['others2']) && $select_result['others2'] == "Yes"){echo 'Yes'; }?>			
						
						</td>
						<td  style="border:1px solid #cdcdcd;">Others</td>
					</tr>
				</table>
			</td>
			<td colspan="2" style="border:1px solid #cdcdcd;">
				<p>Written informed consent taken . All vitals  under normal range. Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. A condom with lubricated jelly is put on the vaginal ultrasound probe,it is introduced transvaginally  ,a baseline transvaginal ultrasound performed to see endometrium and ovaries. Following baseline scan , a sterile Cuscos speculum /Sims speculum with tenaculum introduced .The cervix cleansed with betadine.A 6 Fr Foleys catheter introduced in the uterine cavity ,its bulb inflated and catheter clamped . The speculum is taken out and again the transvaginal ultrasound probe introduced .Sterile saline 20 ml is then injected into the uterine cavity through the catheter and ultrasound is performed to see spillage from both the tubes and shape of uterine cavity </p>
				<p>Rt tube spillage seen/not seen. Lt.Tube spillage seen/not seen.Uterine cavity regular/irregular .Fluid is seen/not seen in POD.</p>
				<p>No complications seen. Catheter deflated and removed from uterine cavity and speculum also removed.</p>
				<p>Patient stood the procedure well.</p>
			</td>
		</tr>
	</table>
	
	<table class="table-bordered" style="border:1px solid #cdcdcd; width:100%;">
		<tr>
			<td style="text-align: center;">SONOSALPINGOGRAPHY REPORT</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"><br></td>
		</tr>
	</table>
	
	
	<table class="table-bordered" style="border:1px solid #cdcdcd; width:100%;">
		<tr>
			<td  style="border:1px solid #cdcdcd;"><b>History</b></td>
			<td  style="border:1px solid #cdcdcd;">
				Primary Infertility <br>
		
			<?php if(isset($select_result['primary_infertility']) && $select_result['primary_infertility'] == "Yes"){echo 'Yes'; }?>	

		</td>
			<td  style="border:1px solid #cdcdcd;">
				Secondary Infertility <br>
			
			
		<?php if(isset($select_result['secondary_infertility']) && $select_result['secondary_infertility'] == "Yes"){echo 'Yes'; }?>		
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Recurrent Pregnancy Loss <br>
				 
  <?php if(isset($select_result['recurrent_pregnancy_loss']) && $select_result['recurrent_pregnancy_loss'] == "Yes"){echo 'Yes'; }?>
  
			</td>
		</tr>
	</table>
	
	<table class="table-bordered" style="border:1px solid #cdcdcd; width:100%;">
		<tr>
			<td  style="border:1px solid #cdcdcd;">Other</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['other'])?$select_result['other']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"><br></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Right Fallopian Tube:</td>
			<td  style="border:1px solid #cdcdcd;">
				<?php if(isset($select_result['right_fallopian']) && $select_result['right_fallopian'] == "seen"){echo 'seen'; }?> 
				<?php if(isset($select_result['right_fallopian']) && $select_result['right_fallopian'] == "not_seen"){echo 'not seen'; }?>   
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Other/ Description</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['right_description'])?$select_result['right_description']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"><br></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Left Fallopian Tube:</td>
			<td  style="border:1px solid #cdcdcd;">
				<?php if(isset($select_result['left_fallopian']) && $select_result['left_fallopian'] == "seen"){echo ' free spill of saline seen'; }?>  
				<?php if(isset($select_result['left_fallopian']) && $select_result['left_fallopian'] == "not_seen"){echo 'not seen'; }?>   
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Other/ Description</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['left_description'])?$select_result['left_description']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"><br></td>
		</tr>
	</table>
	
	
	
	<table class="table-bordered" style="border:1px solid #cdcdcd; width:100%;">
		<tr>
			<td  style="border:1px solid #cdcdcd;">Uterine cavity :</td>
			<td  style="border:1px solid #cdcdcd;">
				<?php if(isset($select_result['uterine_cavity']) && $select_result['uterine_cavity'] == "regular"){echo 'Regular'; }?> 
				<?php if(isset($select_result['uterine_cavity']) && $select_result['uterine_cavity'] == "Filling defect"){echo 'Filling defect'; }?>  
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td colspan="2"  style="padding: 0; border:1px solid #cdcdcd;">
				<table  style="width:100%;border:1px solid #cdcdcd;">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Describe</td>
						<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['described'])?$select_result['described']:""; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;">POD:</td>
			<td  style="border:1px solid #cdcdcd;">
				 <?php if(isset($select_result['pod']) && $select_result['pod'] == "seen"){echo 'fluid seen'; }?>    
				 <?php if(isset($select_result['pod']) && $select_result['pod'] == "Not Seen"){echo 'Not Seen'; }?>    
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;">Pressure: Normal</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td colspan="2" style="border:1px solid #cdcdcd;padding: 0;">
				<table  style="border:1px solid #cdcdcd; width:100%">
					<tr>
						<td  style="border:1px solid #cdcdcd;">Complications:</td>
						<td  style="border:1px solid #cdcdcd;"> <?php if(isset($select_result['complications']) && $select_result['complications'] == "Pain"){echo 'Pain'; }?> </td>
						<td  style="border:1px solid #cdcdcd;"> <?php if(isset($select_result['complications']) && $select_result['complications'] == "None"){echo 'None'; }?>  </td>
						<td  style="border:1px solid #cdcdcd;"> <?php if(isset($select_result['complications']) && $select_result['complications'] == "Mild"){echo 'Mild'; }?>  </td>
						<td  style="border:1px solid #cdcdcd;"> <?php if(isset($select_result['complications']) && $select_result['complications'] == "Moderate"){echo 'Moderate'; }?>  </td>
						<td  style="border:1px solid #cdcdcd;"> <?php if(isset($select_result['complications']) && $select_result['complications'] == "Severe"){echo 'Severe'; }?>   Severe</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;">Other:</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['other2'])?$select_result['other2']:""; ?></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;"></td>
			<td  style="border:1px solid #cdcdcd;">Comments/Drawing</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments'])?$select_result['comments']:""; ?></td>
		</tr>
	</table>
	
	
	
	<table class="table-bordered" style="border:1px solid #cdcdcd; width:100%;">
		<tr>
		<?php @ $pic1= $select_result['pic1'];
		     @ $pic2= $select_result['pic2'];
		?>
		
		
			<td  style="border:1px solid #cdcdcd;">Pic1:</td>
			<td  style="border:1px solid #cdcdcd;">
			<?php if(!empty($pic1)) {?>
                 <img src="<?php echo $pic1;?>" style="width:100px; height:100px;">
                    <?php } else {echo " ";}?>
		
			
			
			</td>
			<td  style="border:1px solid #cdcdcd;">Pic2:</td>
			<td  style="border:1px solid #cdcdcd;">
			
			<?php if(!empty($pic2)) {?>
                 <img src="<?php echo $pic2;?>" style="width:100px; height:100px;">
                    <?php } else {echo " ";}?>
			
			</td>
		</tr>
	</table>
	
	
	
	<table class="table-bordered" style="border:1px solid #cdcdcd; width:100%;">
		<tr>
			<td colspan="2" style="border:1px solid #cdcdcd;"><b>Post Procedure orders</b></td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">

			<?php if(isset($select_result['normal_diet']) && $select_result['normal_diet'] == "Yes"){echo 'yes'; }?>
			
			<br> 
			<?php echo isset($select_result['normal_diet_text'])?$select_result['normal_diet_text']:""; ?>
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Normal diet
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">
		<?php if(isset($select_result['tab_doxycycline']) && $select_result['tab_doxycycline'] == "Yes"){echo 'yes'; }?>
  	
	<br> 
			<?php echo isset($select_result['tab_doxycycline_text'])?$select_result['tab_doxycycline_text']:""; ?>
			
	
	</td>
			<td  style="border:1px solid #cdcdcd;">
				Tab.Doxycycline 100 mg twice daily one morning one evening after meals for 5 days
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">
			  
  			<?php if(isset($select_result['cap_pantoprazole']) && $select_result['cap_pantoprazole'] == "Yes"){echo 'yes'; }?>
  
  <br> 
			<?php echo isset($select_result['cap_pantoprazole_text'])?$select_result['cap_pantoprazole_text']:""; ?>
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Cap Pantoprazole 40 mg once daily in empty stomach for 5 days
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">
							
			<?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "Yes"){echo 'yes'; }?>
			
			<br> 
			<?php echo isset($select_result['tab_crocin_text'])?$select_result['tab_crocin_text']:""; ?>
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				Tab Crocin 500 mg thrice daily eight hourly after meals for 2 days
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">
				
			
			<?php if(isset($select_result['report']) && $select_result['report'] == "Yes"){echo 'yes'; }?>
			<br> 
			<?php echo isset($select_result['report_text'])?$select_result['report_text']:""; ?>
			
			
			</td>
			<td  style="border:1px solid #cdcdcd;">
				To report if giddiness /nausea/vomiting/bleeding/pain/fever/purulent discharge immediately
			</td>
		</tr>
		<tr>
			<td  style="border:1px solid #cdcdcd;">Doctors signature</td>
			<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['doctors_signature'])?$select_result['doctors_signature']:""; ?></td>
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


  $('input[name=normal_diet_text]').hide();
  if ($('input[name=normal_diet]:checked').val() == "Yes") {
    $('input[name=normal_diet_text]').show();
  }
  
  
  $("input:radio").click(function () {
    if ($('input[name=normal_diet]:checked').val() == "Yes") {
      $('input[name=normal_diet_text]').show();
    } else if ($('input[name=normal_diet]:checked').val() == "No") {
      $('input[name=normal_diet_text]').hide();
    }
  }); 


 $('input[name=tab_doxycycline_text]').hide();
  if ($('input[name=tab_doxycycline]:checked').val() == "Yes") {
    $('input[name=tab_doxycycline_text]').show();
  }
  $("input:radio").click(function () {
    if ($('input[name=tab_doxycycline]:checked').val() == "Yes") {
      $('input[name=tab_doxycycline_text]').show();
    } else if ($('input[name=tab_doxycycline]:checked').val() == "No") {
      $('input[name=tab_doxycycline_text]').hide();
    }
  }); 
  
  
   $('input[name=cap_pantoprazole_text]').hide();
  if ($('input[name=cap_pantoprazole]:checked').val() == "Yes") {
    $('input[name=cap_pantoprazole_text]').show();
  }
     $("input:radio").click(function () {
    if ($('input[name=cap_pantoprazole]:checked').val() == "Yes") {
      $('input[name=cap_pantoprazole_text]').show();
    } else if ($('input[name=cap_pantoprazole]:checked').val() == "No") {
      $('input[name=cap_pantoprazole_text]').hide();
    }
  }); 
  
  
    $('input[name=tab_crocin_text]').hide();
  if ($('input[name=tab_crocin]:checked').val() == "Yes") {
    $('input[name=tab_crocin_text]').show();
  }
  
     $("input:radio").click(function () {
    if ($('input[name=tab_crocin]:checked').val() == "Yes") {
      $('input[name=tab_crocin_text]').show();
    } else if ($('input[name=tab_crocin]:checked').val() == "No") {
      $('input[name=tab_crocin_text]').hide();
    }
  }); 
  
  
    $('input[name=report_text]').hide();
  if ($('input[name=report]:checked').val() == "Yes") {
    $('input[name=report_text]').show();
  }
  
      $("input:radio").click(function () {
    if ($('input[name=report]:checked').val() == "Yes") {
      $('input[name=report_text]').show();
    } else if ($('input[name=report]:checked').val() == "No") {
      $('input[name=report_text]').hide();
    }
  });






</script>	

<style>
   .none1{display:none;}
</style>
 