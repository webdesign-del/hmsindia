<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
       $select_query = "SELECT * FROM `laparoscopy_hysteroscopy` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `laparoscopy_hysteroscopy` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }   
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE laparoscopy_hysteroscopy SET ";
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".$value."'"  ;
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
  $select_query = "SELECT * FROM `laparoscopy_hysteroscopy` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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

  $select_prp = "SELECT * FROM `hms_prp` WHERE patient_id='$patient_id'";
	$select_result_prp = run_select_query($select_prp);
?>

<form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">
    
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">

    <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
  <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
  <input type="hidden" value="pending" name="status"> 
    <div class="container1 red-field form mt-5 mb-5">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">LAPAROSCOPY AND HYSTEROSCOPY</h3></td>
   </tr>
</table>
     				  <table width="100%" class="vb45rt">
<tr style="background: #b3b9b7;">

<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
	   </table>
    <table class="table table-bordered table-hover mt-2 table-sm red-field tableMg">
      <thead>
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
      </thead>
    </table>
            <ul class="d-flex mb-1 mt-2 list-unstyled">
            <div class = "table-responsive">
              <table class="table table-bordered table-hover table-sm">
                <thead>
                  <tr style="color: red;">
						                <th colspan="2"><strong>Indication</strong> : <input  type="text" value="<?php echo isset($select_result_prp['indication'])?$select_result_prp['indication']:""; ?>" maxlength="200" placeholder="Indication" name="indication" readonly></td>
						                <td colspan="3">Name Of Procedure : <?php echo $procedure_name; ?></td>
						                <td colspan="">Ecg  : <br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['ecg']) && $select_result['ecg'] == "Yes"){echo 'checked="checked"'; }?>    name="ecg"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['ecg']) && $select_result['ecg'] == "No"){echo 'checked="checked"'; }
  											else if(isset($select_result['ecg']) && $select_result['ecg'] != "Yes"){echo 'checked="checked"';}?>   name="ecg"> No</label></td>
									    <td colspan="1"> Echo : <br>
											<label><input type="radio"   value="Yes"  <?php if(isset($select_result['echo']) && $select_result['echo'] == "Yes"){echo 'checked="checked"'; }?>    name="echo"> Yes</label>
											<label><input type="radio"    value="No"  <?php if(isset($select_result['echo']) && $select_result['echo'] == "No"){echo 'checked="checked"'; }
  											else if(isset($select_result['echo']) && $select_result['echo'] != "Yes"){echo 'checked="checked"';}?>   name="echo"> No</label></td>
									
									</tr>
                  <tr style="color: red;">
                    <td><strong>LAPAROSCOPY & HYSTEROSCOPY</strong></td>
                    <td>Date <input type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>" class="form-control" placeholder="enter a date" name="date"></td>
                    <td>Time <input type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>" class="form-control" id="appt" name="time"></td>
                    <td  colspan="2">ALLERGIES <input type="text" value="<?php echo isset($select_result['allergy'])?$select_result['allergy']:""; ?>" maxlength="50" placeholder="allergy" class="form-control" name="allergy"></td>
                    <td>Consent<br>
                      <label><input type="radio" checked name="consent" value="Yes" <?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                      <label><input type="radio" checked name="consent" value="No" <?php if(isset($select_result['consent']) && $select_result['consent'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['consent']) && $select_result['consent'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                    </td>
                    <td>ID checked<br>
                      <label><input type="radio" checked name="id_checked" value="Yes" <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                      <label><input type="radio" checked name="id_checked" value="No" <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['id_checked']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                    </td>
                  </tr>
                  <tr style="color: red;">
                    <td><strong>PRE ASSESSMENT</strong></td>
                    <td>BP <input type="text" value="<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>" maxlength="20" placeholder="BP" class="form-control" name="bp"></td>
                    <td>PULSE<br>
                      <label><input type="radio" checked name="pulse" value="Yes" <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                      <label><input type="radio" checked name="pulse" value="No" <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pulse']) && $select_result['pulse'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                    </td>
                    <td>RESP<br>
                      <label><input type="radio" checked name="resp" value="Yes" <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                      <label><input type="radio" checked name="resp" value="No" <?php if(isset($select_result['resp']) && $select_result['resp'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['resp']) && $select_result['resp'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                    </td>
                    <td>Voided<br>
                      <label><input type="radio" checked name="voided" value="Yes" <?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                      <label><input type="radio" checked name="voided" value="No" <?php if(isset($select_result['voided']) && $select_result['voided'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['voided']) && $select_result['voided'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                    </td>
                    <td>HT (Cms)<input type="number" value="<?php echo isset($select_result['ht'])?$select_result['ht']:""; ?>" min="0" class="form-control" placeholder="HT" name="ht"></td>
                    <td>WT (Kg)<input type="number" value="<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?>" min="0" class="form-control" placeholder="WT" name="wt"></td>
                  </tr>
                  <tr>
                    <td>Glasses<br>
                      <label><input type="radio" checked name="glasses" value="Yes" <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                      <label><input type="radio" checked name="glasses" value="No" <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['glasses']) && $select_result['glasses'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                    </td>
                    <td>Contacts<br>
                      <label><input type="radio" checked name="contacts" value="Yes" <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                      <label><input type="radio" checked name="contacts" value="No" <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['contacts']) && $select_result['contacts'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                    </td>
                    <td>Denture<br>
                      <label><input type="radio" checked name="denture" value="Yes" <?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                      <label><input type="radio" checked name="denture" value="No" <?php if(isset($select_result['denture']) && $select_result['denture'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['denture']) && $select_result['denture'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                    </td>
                    <td colspan="2">Dental bridge<br>
                      <label><input type="radio" checked name="dental_bridge" value="Yes" <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                      <label><input type="radio" checked name="dental_bridge" value="No" <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                    </td>
                    <td>Valuables with escort<br>
                      <label><input type="radio" checked name="valuables_with_escort" value="Yes" <?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                      <label><input type="radio" checked name="valuables_with_escort" value="No" <?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                    </td>
                    <td>Last meal <input type="time" value="<?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?>" class="form-control" name="last_meal"></td>
                  </tr>
                </thead>
              </table>
        </div>
    </ul>
    <div class = "">
        <table class="table table-bordered table-hover table-sm red-field tableMg">
            <thead>
              <tr>
                <td colspan="1">HPE</td>
                <td colspan="3">
                  <label><input type="radio" checked name="hpe1" value="Yes" <?php if(isset($select_result['hpe1']) && $select_result['hpe1'] == "Yes"){echo 'checked="checked"'; }?> > Yes</label>
                  <label><input type="radio" checked name="hpe1" value="No" <?php if(isset($select_result['hpe1']) && $select_result['hpe1'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hpe1']) && $select_result['hpe1'] != "Yes"){echo 'checked="checked"';}?> > No</label>
                </td>
              </tr>
              <tr>
                <td colspan="4"><b>Prescriptions given</b></td>
              </tr>
              <tr>
                <td colspan="4">
                  Injection Monocef 1 gm iv AST<br>
                  Injection Pantoprazole 40 mg i.m. stat<br>
                  Injection emset 1 gm iv stat<br>
                  Other <input type="text" value="<?php echo isset($select_result['prescription_given'])?$select_result['prescription_given']:""; ?>" class="form-control" name="prescription_given">
                </td>
              </tr>
            </thead>
        </table>
        <table class="table table-bordered table-hover  table-sm red-field tableMg">
            <thead>
                <tr>
                    <th>NURSE <input type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>" maxlength="20" class="form-control" name="nurse"></th>
                    <th>DOCTOR <input type="text" readonly="" value="<?php if (!empty($select_result['doctor'])) {  echo $select_result['doctor']; } else {  echo isset($_SESSION['logged_doctor']['name']) ? $_SESSION['logged_doctor']['name'] : ''; }  ?>" maxlength="30" class="form-control" name="doctor"></th>
                    <th>Anesthetist <input type="text" value="<?php echo isset($select_result['anesthetist'])?$select_result['anesthetist']:""; ?>" maxlength="20" class="form-control" name="anesthetist"></th>
                </tr>
            </thead>
        </table>
        <div class='table-responsive'>
          <table class="table table-bordered table-sm red-field tableMg">
            <tr>
              <td style="padding: 0;">
                <table width="100%">
                  <tr>
                    <td colspan="2"><b>PRE ASSESSMENT</b></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      No sexual intercourse for 72 hours<br>
                      No active  infection<br>
                      No aspirin or NSAID a week before 
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2"><b>Physical Examination</b></td>
                  </tr>
                  <tr>
                    <td>Resp</td>
                    <td><input type="text" value="<?php echo isset($select_result['resp2'])?$select_result['resp2']:""; ?>" maxlength="20" class="form" name="resp2"></td>
                  </tr>
                  <tr>
                    <td>CVS</td>
                    <td><input type="text" value="<?php echo isset($select_result['CVS'])?$select_result['CVS']:""; ?>" maxlength="20" class="form" name="CVS"></td>
                  </tr>
                  <tr>
                    <td>CNS</td>
                    <td><input type="text" value="<?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?>" maxlength="20" class="form" name="CNS"></td>
                  </tr>
                  <tr>
                    <td>Abdominal</td>
                    <td><input type="text" value="<?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?>" maxlength="20" class="form" name="abdominal"></td>
                  </tr>
                  <tr>
                    <td>Others</td>
                    <td><input  type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>" maxlength="100" class="form" name="others"></td>
                  </tr>
                </table>
              </td>
              <td style="padding: 0;">
                <table width="100%">
                  <tr style="color: black;">
                    <td colspan="2"><b>Diagnostic laparoscopy</b></td>
                  </tr>
                  <tr style="color: black;">
                    <td colspan="2">
                      <p>Written informed consent taken. All vitals checked under normal range. The anesthetist examined the patient and given general anesthesia.</p>
                      <p>Patient put in supine position and painted and draped in the abdominal area. Trocar introduced through the umbilicus and two more side ports created</p>
                    </td>
                  </tr>
                  <tr style="color: black;">
                    <td>Uterus visualized</td>
                    <td><input type="text" value="<?php echo isset($select_result['uterus_visualized'])?$select_result['uterus_visualized']:""; ?>" maxlength="20" name="uterus_visualized"></td>
                  </tr>
                  <tr style="color: black;">
                    <td>Bilateral tubes visualized</td>
                    <td><input type="text" value="<?php echo isset($select_result['bilateral_tubes'])?$select_result['bilateral_tubes']:""; ?>" maxlength="20" name="bilateral_tubes"></td>
                  </tr>
                  <tr style="color: black;">
                    <td>Bilateral ovaries visualized</td>
                    <td><input type="text" value="<?php echo isset($select_result['bilateral_ovaries'])?$select_result['bilateral_ovaries']:""; ?>" maxlength="20" name="bilateral_ovaries"></td>
                  
                  </tr>
                  
                  <tr style="color: black;">
                    <td>Bilateral CPT</td>
                    <td><input type="text" value="<?php echo isset($select_result['bilateral_cpt'])?$select_result['bilateral_cpt']:""; ?>" maxlength="20" name="bilateral_cpt"></td>
                  
                  </tr>
                  
                  
                  
                  
                  
                  <tr>
                    <td colspan="2"><h3><u>Findings:</u></h3></td>
                  </tr>
                  <tr>
                    <td>Uterus</td>
                    <td><input type="text" value="<?php echo isset($select_result['uterus'])?$select_result['uterus']:""; ?>" maxlength="20" class="form" name="uterus"></td>
                  </tr>
                  <tr>
                    <td>Tubes</td>
                    <td><input type="text" value="<?php echo isset($select_result['tubes'])?$select_result['tubes']:""; ?>" maxlength="20" class="form" name="tubes"></td>
                  </tr>
                  <tr>
                    <td>Ovaries</td>
                    <td><input type="text" value="<?php echo isset($select_result['ovaries'])?$select_result['ovaries']:""; ?>" maxlength="20" class="form" name="ovaries"></td>
                  </tr>
                  <tr>
                    <td>POD</td>
                    <td><input type="text" value="<?php echo isset($select_result['pod'])?$select_result['pod']:""; ?>" maxlength="20" class="form" name="pod"></td>
                  </tr>
                  <tr>
                    <td>Liver</td>
                    <td><input type="text" value="<?php echo isset($select_result['liver'])?$select_result['liver']:""; ?>" maxlength="20" class="form" name="liver"></td>
                  </tr>
                  <tr>
                    <td>Chromopertubation</td>
                    <td><input type="text" value="<?php echo isset($select_result['Chromopertubation'])?$select_result['Chromopertubation']:""; ?>" maxlength="20" class="form" name="Chromopertubation"></td>
                  </tr>
                  <tr>
                    <td>Any other findings</td>
                    <td><input type="text" value="<?php echo isset($select_result['any_other_findings'])?$select_result['any_other_findings']:""; ?>" maxlength="20" class= "form" name="any_other_findings"></td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="2">No complications seen. Bleeding was mild/moderate. Hemostasis achieved. Patient tolerated the procedure well and was transferred to the recovery room in satisfactory condition</td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="2"><b>Operative Laparoscopy</b></td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="2">Written informed consent taken. All vitals checked under normal range. The anesthetist examined the patient and given general anesthesia.</td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="2">Patient put in supine position and painted and draped in the abdominal area. Trocar introduced through the umbilicus and two more side ports created Comments <input type="text" value="<?php echo isset($select_result['comments1'])?$select_result['comments1']:""; ?>" maxlength="20" name="comments1"></td>
                  </tr>
                  <tr>
                    <td colspan="2"><h3><u>Findings:</u></h3></td>
                  </tr>
                  <tr>
                    <td>Uterus</td>
                    <td><input type="text" value="<?php echo isset($select_result['operative_uterus'])?$select_result['operative_uterus']:""; ?>" maxlength="20" class="form" name="operative_uterus"></td>
                  </tr>
                  <tr>
                    <td>Tubes</td>
                    <td><input type="text" value="<?php echo isset($select_result['operative_tubes'])?$select_result['operative_tubes']:""; ?>" maxlength="20" class="form" name="operative_tubes"></td>
                  </tr>
                  <tr>
                    <td>Ovaries</td>
                    <td><input type="text" value="<?php echo isset($select_result['operative_ovaries'])?$select_result['operative_ovaries']:""; ?>" maxlength="20" class="form" name="operative_ovaries"></td>
                  </tr>
                  <tr>
                    <td>POD</td>
                    <td><input type="text" value="<?php echo isset($select_result['operative_pod'])?$select_result['operative_pod']:""; ?>" maxlength="20" class="form" name="operative_pod"></td>
                  </tr>
                  <tr>
                    <td>Liver</td>
                    <td><input type="text" value="<?php echo isset($select_result['operative_liver'])?$select_result['operative_liver']:""; ?>" maxlength="20" class="form" name="operative_liver"></td>
                  </tr>
                  <tr>
                    <td>Chromopertubation</td>
                    <td><input type="text" value="<?php echo isset($select_result['operative_any_other_findings'])?$select_result['operative_any_other_findings']:""; ?>" maxlength="20" class="form" name="operative_Chromopertubation"></td>
                  </tr>
                  <tr>
                    <td>Any other findings</td>
                    <td><input type="text" value="<?php echo isset($select_result['hiv_1'])?$select_result['hiv_1']:""; ?>" maxlength="20" class="form" name="operative_any_other_findings"></td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="2">No complications seen. Bleeding was mild/moderate .Hemostasis achieved. Patient tolerated the procedure well and was transferred to the recovery room in satisfactory condition</td>
                  </tr>
                </table>
                <table>
                  <tr>
                    <td style="color: black;" colspan="3"><h3><u>Diagnostic hysteroscopy</u></h3></td>
                  </tr>
                  <tr style="color: black;">
                    <td colspan="3">Patient put in lithotomy position, under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. Sims speculum with tenaculum introduced.</td>
                  </tr>
                  <tr style="color: black;">
                    <td>P/S</td>
                    <td>Cervix</td>
                    <td><input type="text" value="<?php echo isset($select_result['cervix'])?$select_result['cervix']:""; ?>" maxlength="20" class="form" name="cervix"></td>
                  </tr>
                  <tr style="color: black;">
                    <td></td>
                    <td>vagina</td>
                    <td><input type="text" value="<?php echo isset($select_result['vagina'])?$select_result['vagina']:""; ?>" maxlength="20" class="form" name="vagina"></td>
                  </tr>
                  <tr style="color: black;">
                    <td>P/V</td>
                    <td></td>
                    <td><input type="text" value="<?php echo isset($select_result['pv'])?$select_result['pv']:""; ?>" maxlength="20" class="form" name="pv"></td>
                  </tr>
                  <tr style="color: black;">
                    <td>Uterocervical length</td>
                    <td colspan="2"><input type="text" value="<?php echo isset($select_result['uterocervical_length'])?$select_result['uterocervical_length']:""; ?>" maxlength="20" class="form" name="uterocervical_length"></td>
                  </tr>
                  <tr style="color: black;">
                    <td colspan="3">Hysteroscope introduced</td>
                  </tr>
                  <tr style="color: black;">
                    <td>Ectocervical canal</td>
                    <td colspan="2"><input type="text" value="<?php echo isset($select_result['ectocervical_canal'])?$select_result['ectocervical_canal']:""; ?>" maxlength="20" class="form" name="ectocervical_canal"></td>
                  </tr>
                  <tr style="color: black;">
                    <td>Endometrial cavity</td>
                    <td colspan="2"><input name="endometrial_cavity" maxlength="20" type="text" value="<?php echo isset($select_result['endometrial_cavity'])?$select_result['endometrial_cavity']:""; ?>"></td>
                  </tr>
                  <tr style="color: black;">
                    <td>Bilateral ostia</td>
                    <td colspan="2"><input name="bilateral_ostia" maxlength="20" type="text" value="<?php echo isset($select_result['bilateral_ostia'])?$select_result['bilateral_ostia']:""; ?>"></td>
                  </tr>
                </table>
                <table>
                  <tr>
                    <td colspan="3"><h3><u>Hysteroscopy findings:</u></h3></td>
                  </tr>
                  <tr>
                    <td>Uterine cavity</td>
                    <td colspan="2"><input type="text" value="<?php echo isset($select_result['uterine_cavity'])?$select_result['uterine_cavity']:""; ?>" maxlength="20" class="form" name="uterine_cavity"></td>
                  </tr>
                  <tr>
                    <td>Ostia</td>
                    <td colspan="2"><input type="text" value="<?php echo isset($select_result['ostia'])?$select_result['ostia']:""; ?>" maxlength="20" class="form" name="ostia"></td>
                  </tr>
                  <tr>
                    <td>Endometrial Biopsy:</td>
                    <td>
                      <span class= "mr-2">TB</span>
                      <label><input type="radio" checked value="Yes" <?php if(isset($select_result['tb']) && $select_result['tb'] == "Yes"){echo 'checked="checked"'; }?>  name="tb"> Yes</label>
                      <label><input type="radio" checked value="No" <?php if(isset($select_result['tb']) && $select_result['tb'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['tb']) && $select_result['tb'] != "Yes"){echo 'checked="checked"';}?>  name="tb"> No</label>
                    </td>
                    <td>
                      <span class="mr-2">HPE</span>
                      <label><input type="radio" checked value="Yes" <?php if(isset($select_result['hpe']) && $select_result['hpe'] == "Yes"){echo 'checked="checked"'; }?>  name="hpe"> Yes</label>
                      <label><input type="radio" checked value="No" <?php if(isset($select_result['hpe']) && $select_result['hpe'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hpe']) && $select_result['hpe'] != "Yes"){echo 'checked="checked"';}?>  name="hpe"> No</label>
                    </td>
                  </tr>
                  <tr>
                    <td>Any other findings</td>
                    <td colspan="2"><input type="text" value="<?php echo isset($select_result['operative_any_other_finding'])?$select_result['operative_any_other_finding']:""; ?>" maxlength="20" class="form" name="operative_any_other_finding"></td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="3">No complications seen. Bleeding was mild/moderate .Hemostasis achieved. Patient tolerated the procedure well and was transferred to the recovery room in satisfactory condition</td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="3"><b>Operative hysteroscopy</b></td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="3">Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. Sims speculum with tenaculum introduced.</td>
                  </tr>
                  <tr>
                  <tr style="color: black;">
                    <td colspan="1">Comments</td>
                    <td colspan="2"><input name="comments" maxlength="20" type="text" value="<?php echo isset($select_result['comments'])?$select_result['comments']:""; ?>"></td>
                  </tr>
                  <tr>
                    <td colspan="3"><h3><u>Hysteroscopy findings:</u></h3></td>
                  </tr>
                  <tr>
                    <td>Uterine cavity</td>
                    <td colspan="2"><input type="text" value="<?php echo isset($select_result['uterine_cavity2'])?$select_result['uterine_cavity2']:""; ?>" maxlength="20" class="form" name="uterine_cavity2"></td>
                  </tr>
                  <tr>
                    <td>Ostia</td>
                    <td colspan="2"><input type="text" value="<?php echo isset($select_result['ostia2'])?$select_result['ostia2']:""; ?>" maxlength="20" class="form" name="ostia2"></td>
                  </tr>
                  <tr>
                    <td>Endometrial Biopsy:</td>
                    <td>
                      <span class= "mr-2">TB</span>
                      <label><input type="radio" checked value="Yes" <?php if(isset($select_result['tb2']) && $select_result['tb2'] == "Yes"){echo 'checked="checked"'; }?>  name="tb2"> Yes</label>
                      <label><input type="radio" checked value="No" <?php if(isset($select_result['tb2']) && $select_result['tb2'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['tb2']) && $select_result['tb2'] != "Yes"){echo 'checked="checked"';}?>  name="tb2"> No</label>
                    </td>
                    <td>
                      <span class="mr-2">HPE</span>
                      <label><input type="radio" checked value="Yes" <?php if(isset($select_result['hpe2']) && $select_result['hpe2'] == "Yes"){echo 'checked="checked"'; }?>  name="hpe2"> Yes</label>
                      <label><input type="radio" checked value="No" <?php if(isset($select_result['hpe2']) && $select_result['hpe2'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hpe2']) && $select_result['hpe2'] != "Yes"){echo 'checked="checked"';}?>  name="hpe2"> No</label>
                    </td>
                  </tr>
                  <tr>
                    <td>Any other findings</td>
                    <td colspan="2"><input type="text" value="<?php echo isset($select_result['operative_any_other_finding2'])?$select_result['operative_any_other_finding2']:""; ?>" maxlength="20" class="form" name="operative_any_other_finding2"></td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="3">No complications seen. Bleeding was mild/moderate. Hemostasis achieved. Patient tolerated the procedure well and was transferred to the recovery room in satisfactory condition.</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
          <table class="table table-bordered table-hover mb-2 table-sm tableMg">
            <thead>
              <tr style="color: red;">
                <td colspan="2"><b>Intra-operative orders</b></td>
              </tr>
              <tr>
                <td>
                 <label>
            <input type="radio" name="intra_operative" value="Yes" <?php if(!isset($select_result['intra_operative']) || $select_result['intra_operative'] == "Yes" || $select_result['intra_operative'] == "") { echo 'checked="checked"'; } ?>> Yes
        </label>
        <label><input type="radio" checked value="No" <?php if(isset($select_result['intra_operative']) && $select_result['intra_operative'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['intra_operative']) && $select_result['intra_operative'] != "Yes"){echo 'checked="checked"';}?>  name="intra_operative"> No</label>
                </td>
                <td>Intra-operative orders</td>
              </tr>
              <tr>
                <td>
                  <label>
            <input type="radio" name="npo_x_2hrs" value="Yes" <?php if(!isset($select_result['npo_x_2hrs']) || $select_result['npo_x_2hrs'] == "Yes" || $select_result['npo_x_2hrs'] == "") { echo 'checked="checked"'; } ?>> Yes
        </label>
        <label><input type="radio" checked value="No" <?php if(isset($select_result['npo_x_2hrs']) && $select_result['npo_x_2hrs'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['npo_x_2hrs']) && $select_result['npo_x_2hrs'] != "Yes"){echo 'checked="checked"';}?>  name="npo_x_2hrs"> No</label>
                </td>
                <td>NPO X 2HRS</td>
              </tr>
              <tr>
                <td>
                  <label>
            <input type="radio" name="sips_of_fluid" value="Yes" <?php if(!isset($select_result['sips_of_fluid']) || $select_result['sips_of_fluid'] == "Yes" || $select_result['sips_of_fluid'] == "") { echo 'checked="checked"'; } ?>> Yes
        </label>
        <label><input type="radio" checked value="No" <?php if(isset($select_result['sips_of_fluid']) && $select_result['sips_of_fluid'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['sips_of_fluid']) && $select_result['sips_of_fluid'] != "Yes"){echo 'checked="checked"';}?>  name="sips_of_fluid"> No</label>
                </td>
                <td>Sips of fluid after 2 hrs fld by soft diet</td>
              </tr>
              <tr>
                <td>
                 <label>
            <input type="radio" name="fluid_rl" value="Yes" <?php if(!isset($select_result['fluid_rl']) || $select_result['fluid_rl'] == "Yes" || $select_result['fluid_rl'] == "") { echo 'checked="checked"'; } ?>> Yes
        </label>
        <label><input type="radio" checked value="No" <?php if(isset($select_result['fluid_rl']) && $select_result['fluid_rl'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['fluid_rl']) && $select_result['fluid_rl'] != "Yes"){echo 'checked="checked"';}?>  name="fluid_rl"> No</label>
                </td>
                <td>i.v. fluid R.L or NS 500 ml@ 125 ml/hr</td>
              </tr>
              <tr>
                <td>
                  <label>
            <input type="radio" name="paracetamol" value="Yes" <?php if(!isset($select_result['paracetamol']) || $select_result['paracetamol'] == "Yes" || $select_result['paracetamol'] == "") { echo 'checked="checked"'; } ?>> Yes
        </label>
        <label><input type="radio" checked value="No" <?php if(isset($select_result['paracetamol']) && $select_result['paracetamol'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['paracetamol']) && $select_result['paracetamol'] != "Yes"){echo 'checked="checked"';}?>  name="paracetamol"> No</label>
                </td>
                <td>i.v. paracetamol 100 ml @8-10 drops/min</td>
              </tr>
              <tr>
                <td>
                  <label>
            <input type="radio" name="justin_suppository" value="Yes" <?php if(!isset($select_result['justin_suppository']) || $select_result['justin_suppository'] == "Yes" || $select_result['justin_suppository'] == "") { echo 'checked="checked"'; } ?>> Yes
        </label>
        <label><input type="radio" checked value="No" <?php if(isset($select_result['justin_suppository']) && $select_result['justin_suppository'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['justin_suppository']) && $select_result['justin_suppository'] != "Yes"){echo 'checked="checked"';}?>  name="justin_suppository"> No</label>
                </td>
                <td>Justin suppository per rectally</td>
              </tr>
              <tr>
                <td>
                  <label>
            <input type="radio" name="monitor_pulse" value="Yes" <?php if(!isset($select_result['monitor_pulse']) || $select_result['monitor_pulse'] == "Yes" || $select_result['monitor_pulse'] == "") { echo 'checked="checked"'; } ?>> Yes
        </label>
        <label><input type="radio" checked value="No" <?php if(isset($select_result['monitor_pulse']) && $select_result['monitor_pulse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['monitor_pulse']) && $select_result['monitor_pulse'] != "Yes"){echo 'checked="checked"';}?>  name="monitor_pulse"> No</label>
                </td>
                <td>Monitor pulse/BP/Spo2 continuously</td>
              </tr>
              <tr>
                <td>
                  <label>
            <input type="radio" name="monitor_bleeding" value="Yes" <?php if(!isset($select_result['monitor_bleeding']) || $select_result['monitor_bleeding'] == "Yes" || $select_result['monitor_bleeding'] == "") { echo 'checked="checked"'; } ?>> Yes
        </label>
        <label><input type="radio" checked value="No" <?php if(isset($select_result['monitor_bleeding']) && $select_result['monitor_bleeding'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['monitor_bleeding']) && $select_result['monitor_bleeding'] != "Yes"){echo 'checked="checked"';}?>  name="monitor_bleeding"> No</label>
                </td>
                <td>Monitor bleeding p/v every half hour</td>
              </tr>
              <tr>
                <td>
                  <label>
            <input type="radio" name="remove_vaginal" value="Yes" <?php if(!isset($select_result['remove_vaginal']) || $select_result['remove_vaginal'] == "Yes" || $select_result['remove_vaginal'] == "") { echo 'checked="checked"'; } ?>> Yes
        </label>
        <label><input type="radio" checked value="No" <?php if(isset($select_result['remove_vaginal']) && $select_result['remove_vaginal'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['remove_vaginal']) && $select_result['remove_vaginal'] != "Yes"){echo 'checked="checked"';}?>  name="remove_vaginal"> No</label>
                </td>
                <td>Remove vaginal pack if any</td>
              </tr>
            </thead> 
          </table>
        </div>
          <!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
          <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
  </form>
  
  
  
  
  
  
  <!---         Print button             -->
  
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">LAPAROSCOPY AND HYSTEROSCOPY</h3></td>
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
    <table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;" >
      <thead>
        <tr>
            <td colspan="2" style="border:1px solid #cdcdcd;">
			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
			            ){?>
			        <p id="last_updated"> Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
			    <?php } ?>
			</td>
        </tr>
    </thead>
    </table>
            
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
                     <td  style="border:1px solid #cdcdcd;"><strong>LAPAROSCOPY & HYSTEROSCOPY</strong></td>
                     <td  style="border:1px solid #cdcdcd;">Date <?php echo isset($select_result['date'])?$select_result['date']:""; ?> </td>
                     <td  style="border:1px solid #cdcdcd;">Time <?php echo isset($select_result['time'])?$select_result['time']:""; ?> </td>
                     <td  style="border:1px solid #cdcdcd;" colspan="2">ALLERGIES <?php echo isset($select_result['allergy'])?$select_result['allergy']:""; ?> </td>
                     <td  style="border:1px solid #cdcdcd;">Consent<br>
					 
  <?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'yes'; }?>
  <?php if(isset($select_result['consent']) && $select_result['consent'] == "No"){echo 'no'; }?>                  
				   </td>
					
					
                     <td  style="border:1px solid #cdcdcd;">ID checked<br>
                   <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "No"){echo 'no'; }?>
                 <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'yes'; }?>
                    
				  </td>
					
					
                  </tr>
                  <tr style="color: red;">
                     <td  style="border:1px solid #cdcdcd;"><strong>PRE ASSESSMENT</strong></td>
                     <td  style="border:1px solid #cdcdcd;">BP <?php echo isset($select_result['bp'])?$select_result['bp']:""; ?> </td>
                     <td  style="border:1px solid #cdcdcd;">PULSE<br>
                                
			<?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'yes'; }?>
      <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "No"){echo 'no'; }?>               
					
					</td>
					
					
                     <td  style="border:1px solid #cdcdcd;">RESP<br>
                      
  <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'yes'; }?>
  <?php if(isset($select_result['resp']) && $select_result['resp'] == "No"){echo 'no'; }?>                 
				   </td>
                     <td  style="border:1px solid #cdcdcd;">Voided<br>
                                       
					  <?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'yes'; }?>
            <?php if(isset($select_result['voided']) && $select_result['voided'] == "No"){echo 'no'; }?>       					
					</td>
                     <td  style="border:1px solid #cdcdcd;">HT (Cms) <?php echo isset($select_result['ht'])?$select_result['ht']:""; ?> </td>
                     <td  style="border:1px solid #cdcdcd;">WT (Kg)<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?> </td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Glasses<br>
                                          
					  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'yes'; }?>
            <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "No"){echo 'no'; }?>       
					</td>
                     <td  style="border:1px solid #cdcdcd;">Contacts<br>
                     
  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'yes'; }?>
  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "No"){echo 'no'; }?>               
				  </td>
                     <td  style="border:1px solid #cdcdcd;">Denture<br>
                                       
  <?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'yes'; }?>
  <?php if(isset($select_result['denture']) && $select_result['denture'] == "No"){echo 'no'; }?>                  
				   </td>
                    <td colspan="2" style="border:1px solid #cdcdcd;">Dental bridge<br>
                       
    <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'yes'; }?>
    <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "No"){echo 'no'; }?>                  
                    </td>
                     <td  style="border:1px solid #cdcdcd;">Valuables with escort<br>
                                          
<?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "Yes"){echo 'yes'; }?>
<?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "No"){echo 'no'; }?>                    
				   </td>
				   
                     <td  style="border:1px solid #cdcdcd;">Last meal <?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?> </td>
                  </tr>
                </thead>
              </table>
       
	
	
	
    <div class = "">
        <table class="table table-bordered table-hover table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;">
            <thead>
              <tr>
                <td colspan="1" style="border:1px solid #cdcdcd;">HPE</td>
                <td colspan="3" style="border:1px solid #cdcdcd;">
                 
                
				<?php if(isset($select_result['hpe1']) && $select_result['hpe1'] == "Yes"){echo 'yes'; }?>
     	<?php if(isset($select_result['hpe1']) && $select_result['hpe1'] == "No"){echo 'no'; }?>
				
				</td>
              </tr>
              <tr>
                <td colspan="4" style="border:1px solid #cdcdcd;"><b>Prescriptions given</b></td>
              </tr>
              <tr>
                <td colspan="4" style="border:1px solid #cdcdcd;">
                  Injection Monocef 1 gm iv AST<br>
                  Injection Pantoprazole 40 mg i.m. stat<br>
                  Injection emset 1 gm iv stat<br>
                  Other <?php echo isset($select_result['prescription_given'])?$select_result['prescription_given']:""; ?>
                </td>
              </tr>
            </thead>
        </table>
        <table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;">
            <thead>
                <tr>
                    <th style="border:1px solid #cdcdcd;">NURSE <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?> </th>
                    <th style="border:1px solid #cdcdcd;">DOCTOR <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?> </th>
                    <th style="border:1px solid #cdcdcd;">Anesthetist <?php echo isset($select_result['anesthetist'])?$select_result['anesthetist']:""; ?>" </th>
                </tr>
            </thead>
        </table>
        <div class='table-responsive'>
          <table class="table table-bordered table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;">
            <tr>
              <td style="padding: 0;">
                <table width="100%">
                  <tr>
                    <td colspan="2" style="border:1px solid #cdcdcd;"><b>PRE ASSESSMENT</b></td>
                  </tr>
                  <tr>
                    <td colspan="2" style="border:1px solid #cdcdcd;">
                      No sexual intercourse for 72 hours<br>
                      No active  infection<br>
                      No aspirin or NSAID a week before 
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" style="border:1px solid #cdcdcd;"><b>Physical Examination</b></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Resp</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['resp2'])?$select_result['resp2']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">CVS</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['CVS'])?$select_result['CVS']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">CNS</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Abdominal</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Others</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['others'])?$select_result['others']:""; ?></td>
                  </tr>
                </table>
              </td>
              <td style="padding: 0;">
                <table width="100%">
                  <tr style="color: black;">
                    <td colspan="2" style="border:1px solid #cdcdcd;"><b>Diagnostic laparoscopy</b></td>
                  </tr>
                  <tr style="color: black;">
                    <td colspan="2" style="border:1px solid #cdcdcd;">
                      <p>Written informed consent taken. All vitals checked under normal range. The anesthetist examined the patient and given general anesthesia.</p>
                      <p>Patient put in supine position and painted and draped in the abdominal area. Trocar introduced through the umbilicus and two more side ports created</p>
                    </td>
                  </tr>
                  <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;">Uterus visualized</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['uterus_visualized'])?$select_result['uterus_visualized']:""; ?></td>
                  </tr>
                  <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;">Bilateral tubes visualized</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['bilateral_tubes'])?$select_result['bilateral_tubes']:""; ?></td>
                  </tr>
                  <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;">Bilateral ovaries visualized</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['bilateral_ovaries'])?$select_result['bilateral_ovaries']:""; ?></td>
                  </tr>
                  
                   <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;">Bilateral CPT</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['bilateral_cpt'])?$select_result['bilateral_cpt']:""; ?></td>
                  </tr>
                  
                  <tr>
                    <td colspan="2" style="border:1px solid #cdcdcd;"><h3><u>Findings:</u></h3></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Uterus</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['uterus'])?$select_result['uterus']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Tubes</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['tubes'])?$select_result['tubes']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Ovaries</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ovaries'])?$select_result['ovaries']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">POD</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pod'])?$select_result['pod']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Liver</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['liver'])?$select_result['liver']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Chromopertubation</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['Chromopertubation'])?$select_result['Chromopertubation']:""; ?> </td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Any other findings</td>
                     <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['any_other_findings'])?$select_result['any_other_findings']:""; ?> </td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="2">No complications seen. Bleeding was mild/moderate. Hemostasis achieved. Patient tolerated the procedure well and was transferred to the recovery room in satisfactory condition</td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="2"><b>Operative Laparoscopy</b></td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="2">Written informed consent taken. All vitals checked under normal range. The anesthetist examined the patient and given general anesthesia.</td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="2">Patient put in supine position and painted and draped in the abdominal area. Trocar introduced through the umbilicus and two more side ports created Comments <?php echo isset($select_result['comments1'])?$select_result['comments1']:""; ?></td>
                  </tr>
                  <tr>
                    <td colspan="2" style="border:1px solid #cdcdcd;"><h3><u>Findings:</u></h3></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Uterus</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['operative_uterus'])?$select_result['operative_uterus']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Tubes</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['operative_tubes'])?$select_result['operative_tubes']:""; ?> </td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Ovaries</td>
                     <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['operative_ovaries'])?$select_result['operative_ovaries']:""; ?> </td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">POD</td>
                     <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['operative_pod'])?$select_result['operative_pod']:""; ?> </td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Liver</td>
                     <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['operative_liver'])?$select_result['operative_liver']:""; ?> </td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Chromopertubation</td>
                     <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['operative_any_other_findings'])?$select_result['operative_any_other_findings']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Any other findings</td>
                     <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hiv_1'])?$select_result['hiv_1']:""; ?> </td>
                  </tr>
                  <tr>
                    <td colspan="2" style="border:1px solid #cdcdcd;">No complications seen. Bleeding was mild/moderate .Hemostasis achieved. Patient tolerated the procedure well and was transferred to the recovery room in satisfactory condition</td>
                  </tr>
                </table>
                <table>
                  <tr>
                    <td style="border:1px solid #cdcdcd;" colspan="3"><h3><u>Diagnostic hysteroscopy</u></h3></td>
                  </tr>
                  <tr style="color: black;">
                    <td colspan="3" style="border:1px solid #cdcdcd;">Patient put in lithotomy position, under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. Sims speculum with tenaculum introduced.</td>
                  </tr>
                  <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;">P/S</td>
                     <td  style="border:1px solid #cdcdcd;">Cervix</td>
                     <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['cervix'])?$select_result['cervix']:""; ?> </td>
                  </tr>
                  <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;"></td>
                     <td  style="border:1px solid #cdcdcd;">vagina</td>
                     <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['vagina'])?$select_result['vagina']:""; ?> </td>
                  </tr>
                  <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;">P/V</td>
                     <td  style="border:1px solid #cdcdcd;"></td>
                     <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['pv'])?$select_result['pv']:""; ?> </td>
                  </tr>
                  <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;">Uterocervical length</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['uterocervical_length'])?$select_result['uterocervical_length']:""; ?> </td>
                  </tr>
                  <tr style="color: black;">
                    <td colspan="3">Hysteroscope introduced</td>
                  </tr>
                  <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;">Ectocervical canal</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['ectocervical_canal'])?$select_result['ectocervical_canal']:""; ?> </td>
                  </tr>
                  <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;">Endometrial cavity</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_cavity'])?$select_result['endometrial_cavity']:""; ?> </td>
                  </tr>
                  <tr style="color: black;">
                     <td  style="border:1px solid #cdcdcd;">Bilateral ostia</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['bilateral_ostia'])?$select_result['bilateral_ostia']:""; ?> </td>
                  </tr>
                </table>
                <table>
                  <tr>
                    <td colspan="3"><h3><u>Hysteroscopy findings:</u></h3></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Uterine cavity</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['uterine_cavity'])?$select_result['uterine_cavity']:""; ?> </td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Ostia</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['ostia'])?$select_result['ostia']:""; ?> </td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Endometrial Biopsy:</td>
                     <td  style="border:1px solid #cdcdcd;">
                      <span class= "mr-2">TB</span>
                    
     <?php if(isset($select_result['tb']) && $select_result['tb'] == "Yes"){echo 'yes'; }?>
     

				  </td>
                     <td  style="border:1px solid #cdcdcd;">
                      <span class="mr-2">HPE</span>
                                       
<?php if(isset($select_result['hpe']) && $select_result['hpe'] == "Yes"){echo 'yes'; }?>
     

				 </td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Any other findings</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['operative_any_other_finding'])?$select_result['operative_any_other_finding']:""; ?> </td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="3">No complications seen. Bleeding was mild/moderate .Hemostasis achieved. Patient tolerated the procedure well and was transferred to the recovery room in satisfactory condition</td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="3"><b>Operative hysteroscopy</b></td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="3">Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. Sims speculum with tenaculum introduced.</td>
                  </tr>
                  <tr>
                  <tr style="color: black;">
                    <td colspan="1">Comments</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['comments'])?$select_result['comments']:""; ?> </td>
                  </tr>
                  <tr>
                    <td colspan="3"><h3><u>Hysteroscopy findings:</u></h3></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Uterine cavity</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['uterine_cavity2'])?$select_result['uterine_cavity2']:""; ?> </td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Ostia</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['ostia2'])?$select_result['ostia2']:""; ?></td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Endometrial Biopsy:</td>
                     <td  style="border:1px solid #cdcdcd;">
                      <span class= "mr-2">TB</span>
                                       
<?php if(isset($select_result['tb2']) && $select_result['tb2'] == "Yes"){echo 'yes'; }?>
     
				   </td>
                     <td  style="border:1px solid #cdcdcd;">
                      <span class="mr-2">HPE</span>
                                  
<?php if(isset($select_result['hpe2']) && $select_result['hpe2'] == "Yes"){echo 'yes'; }?>
     

				</td>
                  </tr>
                  <tr>
                     <td  style="border:1px solid #cdcdcd;">Any other findings</td>
                    <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['operative_any_other_finding2'])?$select_result['operative_any_other_finding2']:""; ?></td>
                  </tr>
                  <tr>
                    <td style="color: black;" colspan="3">No complications seen. Bleeding was mild/moderate. Hemostasis achieved. Patient tolerated the procedure well and was transferred to the recovery room in satisfactory condition.</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
          <table class="table table-bordered table-hover mb-2 table-sm tableMg" style="width:100%; border:1px solid #cdcdcd;" >
            <thead>
              <tr style="color: red;">
                <td colspan="2" style="border:1px solid #cdcdcd;"><b>Intra-operative orders</b></td>
              </tr>
              <tr>
                 <td  style="border:1px solid #cdcdcd;">
                 
              <?php if(isset($select_result['intra_operative']) && $select_result['intra_operative'] == "Yes"){echo 'yes'; }?>
     
			  </td>
                 <td  style="border:1px solid #cdcdcd;">Intra-operative orders</td>
              </tr>
              <tr>
                 <td  style="border:1px solid #cdcdcd;">
                               
<?php if(isset($select_result['npo_x_2hrs']) && $select_result['npo_x_2hrs'] == "Yes"){echo 'yes'; }?>
     
			   </td>
                 <td  style="border:1px solid #cdcdcd;">NPO X 2HRS</td>
              </tr>
              <tr>
                 <td  style="border:1px solid #cdcdcd;">
                               
<?php if(isset($select_result['sips_of_fluid']) && $select_result['sips_of_fluid'] == "Yes"){echo 'yes'; }?>
     
			  </td>
                 <td  style="border:1px solid #cdcdcd;">Sips of fluid after 2 hrs fld by soft diet</td>
              </tr>
              <tr>
                 <td  style="border:1px solid #cdcdcd;">
                              
<?php if(isset($select_result['fluid_rl']) && $select_result['fluid_rl'] == "Yes"){echo 'yes'; }?>
     
			 </td>
                 <td  style="border:1px solid #cdcdcd;">i.v. fluid R.L or NS 500 ml@ 125 ml/hr</td>
              </tr>
              <tr>
                 <td  style="border:1px solid #cdcdcd;">
                              
<?php if(isset($select_result['paracetamol']) && $select_result['paracetamol'] == "Yes"){echo 'yes'; }?>
     
			  </td>
                 <td  style="border:1px solid #cdcdcd;">i.v. paracetamol 100 ml @8-10 drops/min</td>
              </tr>
              <tr>
                 <td  style="border:1px solid #cdcdcd;">
                               
<?php if(isset($select_result['justin_suppository']) && $select_result['justin_suppository'] == "Yes"){echo 'yes'; }?>
     

			  </td>
                 <td  style="border:1px solid #cdcdcd;">Justin suppository per rectally</td>
              </tr>
              <tr>
                 <td  style="border:1px solid #cdcdcd;">
                  
<?php if(isset($select_result['monitor_pulse']) && $select_result['monitor_pulse'] == "Yes"){echo 'yes'; }?>
     
			  </td>
                 <td  style="border:1px solid #cdcdcd;">Monitor pulse/BP/Spo2 continuously</td>
              </tr>
              <tr>
                 <td  style="border:1px solid #cdcdcd;">
<?php if(isset($select_result['monitor_bleeding']) && $select_result['monitor_bleeding'] == "Yes"){echo 'yes'; }?>
			  </td>
                 <td  style="border:1px solid #cdcdcd;">Monitor bleeding p/v every half hour</td>
              </tr>
              <tr>
                 <td  style="border:1px solid #cdcdcd;">                 
<?php if(isset($select_result['remove_vaginal']) && $select_result['remove_vaginal'] == "Yes"){echo 'yes'; }?>
			   </td>
                 <td  style="border:1px solid #cdcdcd;">Remove vaginal pack if any</td>
              </tr>
              <tr style="color: red;">
                <th colspan="2" style="border:1px solid #cdcdcd;"><p> Doctors signature <?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?> </p></th>
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