<?php
    if(isset($_POST['submit'])){
      unset($_POST['submit']);
      
      $select_query = "SELECT * FROM `uterine_prp` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
      $select_result = run_select_query($select_query); 
      if(empty($select_result)){
          // mysql query to insert data
          $query = "INSERT INTO `uterine_prp` SET ";
          $sqlArr = array();
          foreach( $_POST as $key=> $value )
          {
            $sqlArr[] = " $key = '".addslashes($value)."'";
          }		
          $query .= implode(',' , $sqlArr);
      }else{
          // mysql query to update data
          $query = "UPDATE uterine_prp SET ";
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
  $select_query = "SELECT * FROM `uterine_prp` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
  $select_result = run_select_query($select_query);   
	
	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);

  $select_prp = "SELECT * FROM `hms_prp` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
      <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">UTERINE PRP</h3></td>
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
      <div class="container1 red-field form mt-5 mb-5">
        <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
        			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
        			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
        			            ){?>
        			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
        			    <?php } ?>
        <ul class="d-flex mb-1 mt-2 list-unstyled">
          <div class = "table-responsive">
            <table class="table table-bordered table-hover table-sm">
              <thead>
                <tr style="color: red;">
                  
                  <td>Date <input  type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>" placeholder="enter a date" name="date"></td>
                  <td>Time <input  type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"   id="appt" name="time"></td>
                  <td>Indication <input  type="text" value="<?php echo isset($select_result_prp['indication'])?$select_result_prp['indication']:""; ?>" name="indication" readonly></td>
                  <td>
                    ALLERGIES <br>
                    <input  type="text" value="<?php echo isset($select_result_prp['allergy'])?$select_result_prp['allergy']:""; ?>" maxlength="50" name="allergy" readonly>
                  </td>
                  <td>
                    Consent<br>
                    <label><input type="radio" value="Yes"  <?php if(isset($select_result_prp['consent']) && $select_result_prp['consent'] == "Yes"){echo 'checked="checked"'; }?>  name="consent"> Yes</label>
                    <label><input type="radio" value="No"  <?php if(isset($select_result_prp['consent']) && $select_result_prp['consent'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result_prp['consent']) && $select_result_prp['consent'] != "Yes"){echo 'checked="checked"';}?>   name="consent"> No</label>
                  </td>
                  <td>
                    ID checked<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result_prp['id_checked']) && $select_result_prp['id_checked'] == "Yes"){echo 'checked="checked"'; }?>  name="id_checked"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result_prp['id_checked']) && $select_result_prp['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result_prp['id_checked']) && $select_result_prp['id_checked'] != "Yes"){echo 'checked="checked"';}?>   name="id_checked"> No</label>
                  </td>
                </tr>
                <tr style="color: red;">
                  <td>BP <input  type="text" value="<?php echo isset($select_result_prp['bp'])?$select_result_prp['bp']:""; ?>"   maxlength="20" name="bp" readonly></td>
                  <td>
                    PULSE <br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result_prp['pulse']) && $select_result_prp['pulse'] == "Yes"){echo 'checked="checked"'; }?>  name="pulse"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result_prp['pulse']) && $select_result_prp['pulse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result_prp['pulse']) && $select_result_prp['pulse'] != "Yes"){echo 'checked="checked"';}?>   name="pulse"> No</label>
                  </td>
                  <td>
                    RESP <br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result_prp['resp']) && $select_result_prp['resp'] == "Yes"){echo 'checked="checked"'; }?>  name="resp"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result_prp['resp']) && $select_result_prp['resp'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result_prp['resp']) && $select_result_prp['resp'] != "Yes"){echo 'checked="checked"';}?>   name="resp"> No</label>
                  </td>
                  <td>
                    VOIDED <br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result_prp['voided']) && $select_result_prp['voided'] == "Yes"){echo 'checked="checked"'; }?>  name="voided"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result_prp['voided']) && $select_result_prp['voided'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result_prp['voided']) && $select_result_prp['voided'] != "Yes"){echo 'checked="checked"';}?>   name="voided"> No</label>
                  </td>
                  <td>HT (Cms)<br>
                    <input  type="number" value="<?php echo isset($select_result_prp['ht'])?$select_result_prp['ht']:""; ?>"   min="0" name="ht" readonly>
                  </td>
                  <td>
                    WT (Kg)<br>
                     <input  type="number" value="<?php echo isset($select_result_prp['wt'])?$select_result_prp['wt']:""; ?>"   min="0" name="wt" readonly>
                  </td>
                </tr>
                <tr>
                  <td>
                    Glasses<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result_prp['glasses']) && $select_result_prp['glasses'] == "Yes"){echo 'checked="checked"'; }?>     name="glasses"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result_prp['glasses']) && $select_result_prp['glasses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result_prp['glasses']) && $select_result_prp['glasses'] != "Yes"){echo 'checked="checked"';}?>   name="glasses"> No</label>
                  </td>
                  <td>
                    Contacts<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result_prp['contacts']) && $select_result_prp['contacts'] == "Yes"){echo 'checked="checked"'; }?>     name="contacts"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result_prp['contacts']) && $select_result_prp['contacts'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result_prp['contacts']) && $select_result_prp['contacts'] != "Yes"){echo 'checked="checked"';}?>   name="contacts"> No</label>
                  </td>
                  <td>
                    Denture<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result_prp['denture']) && $select_result_prp['denture'] == "Yes"){echo 'checked="checked"'; }?>     name="denture"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result_prp['denture']) && $select_result_prp['denture'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result_prp['denture']) && $select_result_prp['denture'] != "Yes"){echo 'checked="checked"';}?>   name="denture"> No</label>
                  </td>
                  <td>
                    Dental bridge<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result_prp['dental_bridge']) && $select_result_prp['dental_bridge'] == "Yes"){echo 'checked="checked"'; }?>     name="dental_bridge"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result_prp['dental_bridge']) && $select_result_prp['dental_bridge'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result_prp['dental_bridge']) && $select_result_prp['dental_bridge'] != "Yes"){echo 'checked="checked"';}?>   name="dental_bridge"> No</label>
                  </td>
                  <td>
                    Valuables with escort<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result_prp['valuables_with_escort']) && $select_result_prp['valuables_with_escort'] == "Yes"){echo 'checked="checked"'; }?>     name="valuables_with_escort"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result_prp['valuables_with_escort']) && $select_result_prp['valuables_with_escort'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result_prp['valuables_with_escort']) && $select_result_prp['valuables_with_escort'] != "Yes"){echo 'checked="checked"';}?>   name="valuables_with_escort"> No</label>
                  </td>
                  <td>Last meal <input  type="time" value="<?php echo isset($select_result_prp['last_meal'])?$select_result_prp['last_meal']:""; ?>"   name="last_meal"></td>
                </tr>
              </thead>
            </table>
          </div>
        </ul>
        <div class = "table-responsive">
        <table class="table table-bordered table-hover  table-sm red-field tableMg">
          <tr>
            <td colspan="2"><b>Prescriptions given</b></td>
          </tr>
          <tr>
            <td colspan="2">Lignocaine jelly applied vaginally</td>
          </tr>
          <tr>
            <td>Other</td>
            <td><input  type="text" value="<?php echo isset($select_result['prescriptions_others'])?$select_result['prescriptions_others']:""; ?>"   maxlength="100" name="prescriptions_others"></td>
          </tr>
        </table>
        <table class="table table-bordered table-hover table-sm red-field tableMg">
          <tr>
            <td>NURSE</td>
            <td><input  type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>"   maxlength="20" class="form-control" name="nurse"></td>
            <td>DOCTOR</td>
            <td><input  type="text" value="<?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?>"   maxlength="20" class="form-control" name="doctor"></td>
          </tr>
          <tr>
            <td colspan="2" style="padding: 0; width:30%;">
              <table>
                <tr>
                  <td colspan="2">Physical Examination</td>
                </tr>
                <tr>
                  <td>Resp</td>
                  <td><input class="form-control" maxlength="20"  type="text" value="<?php echo isset($select_result['resp2'])?$select_result['resp2']:""; ?>"   name="resp2"></td>
                </tr>
                <tr>
                  <td>CVS</td>
                  <td><input class="form-control" maxlength="20"  type="text" value="<?php echo isset($select_result['CVS'])?$select_result['CVS']:""; ?>"  name="CVS"></td>
                </tr>
                <tr>
                  <td>CNS</td>
                  <td><input class="form-control" maxlength="20"  type="text" value="<?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?>"   name="CNS"></td>
                </tr>
                <tr>
                  <td>Abdominal</td>
                  <td><input class="form-control" maxlength="20"  type="text" value="<?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?>"   name="abdominal"></td>
                </tr>
                <tr>
                  <td>Others</td>
                  <td><input class="form-control" maxlength="100"  type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>"   name="others"></td>
                </tr>
              </table>
            </td>
            <td style="padding: 0;" colspan="2">
              <table width="100%">
                <tr>
                  <td>Written informed consent taken. All vitals under normal range. 15 ml of venous blood was drawn from syringe system, centrifuged by ACP /normal centrifuge system for 5 min.</td>
                </tr>
                <tr>
                  <td>Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. A condom with lubricated jelly is put on the vaginal ultrasound probe,it is introduced transvaginally ,a baseline transvaginal ultrasound performed to see endometrium and ovaries. Following baseline scan , a sterile Cuscos speculum /Sims speculum with tenaculum introduced .The cervix cleansed with betadine.An IUI catheter with 0.5 ml of prepared PRP(supernatant pellet ) put in the uterine cavity .The speculum is taken out.</td>
                </tr>
                <tr>
                  <td><p>No complications seen. Patient stood the procedure well.Till20 mins patient made to lie down<p>
                <p><input class="form-control" maxlength="100"  type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>"   name="others"></p>
                </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <table class="table table-bordered table-hover table-sm red-field">
          <tr>
            <td colspan="2"><b>Post Procedure orders</b></td>
          </tr>
          <tr>
            <td>
              <label><input type="radio"   value="Yes"  <?php if(isset($select_result['normal_diet']) && $select_result['normal_diet'] == "Yes"){echo 'checked="checked"'; }?>     name="normal_diet"> Yes</label>
              <label><input type="radio"    value="No"  <?php if(isset($select_result['normal_diet']) && $select_result['normal_diet'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['normal_diet']) && $select_result['normal_diet'] != "Yes"){echo 'checked="checked"';}?>   name="normal_diet"> No</label>
            </td>
            <td>Normal diet</td>
          </tr>
          <tr>
            <td>
              <label><input type="radio"   value="Yes"  <?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "Yes"){echo 'checked="checked"'; }?>     name="tab_crocin"> Yes</label>
              <label><input type="radio"    value="No"  <?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] != "Yes"){echo 'checked="checked"';}?>   name="tab_crocin"> No</label>
            </td>
            <td>Tab Crocin 500 mg (SOS) thrice daily eight hourly after meals for 1 days</td>
          </tr>
          <tr>
            <td>
              <label><input type="radio"   value="Yes"  <?php if(isset($select_result['other_medicines']) && $select_result['other_medicines'] == "Yes"){echo 'checked="checked"'; }?>     name="other_medicines"> Yes</label>
              <label><input type="radio"    value="No"  <?php if(isset($select_result['other_medicines']) && $select_result['other_medicines'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['other_medicines']) && $select_result['other_medicines'] != "Yes"){echo 'checked="checked"';}?>   name="other_medicines"> No</label>
            </td>
            <td>Keep taking other medicines as the pt is taking</td>
          </tr>
          <tr>
            <td>
              <label><input type="radio"   value="Yes"  <?php if(isset($select_result['report']) && $select_result['report'] == "Yes"){echo 'checked="checked"'; }?>     name="report"> Yes</label>
              <label><input type="radio"    value="No"  <?php if(isset($select_result['report']) && $select_result['report'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['report']) && $select_result['report'] != "Yes"){echo 'checked="checked"';}?>   name="report"> No</label>
            </td>
            <td>To report if giddiness /nausea/vomiting/bleeding/pain/fever /purulent discharge immediately</td>
          </tr>
          <tr>
            <td>Doctors signature</td>
            <td><input  type="text" value="<?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?>"   class="form" name="doctor_signature"></td>
          </tr>
        </table>
        </div>
          <!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
          <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
</form>


<!---  Print Button Start form --> 

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
<table  id="printtable" border="1">

             <table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
	            <tr>
                    <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
					<td width="50%" colspan="2" style="border:1px solid #cdcdcd;text-align:center"><h2><strong>UTERINE PRP</strong></h2></td>
				</tr>
				</table>
 <table class="table table-bordered table-hover table-sm" style="width:100%; border:1px solid #cdcdcd;">
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
					<td colspan="4" style="border:1px solid #cdcdcd;" >
        			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
        			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
        			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
        			            ){?>
        			        <p id="last_updated"> Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?> </p>
        			    <?php } ?>
        			</td>
				</tr>
	   </table>	
			
            <table class="table table-bordered table-hover table-sm" style="width:100%; border:1px solid #cdcdcd;">
              <thead>
                <tr style="color: red; border:1px solid #cdcdcd;">
                  <td colspan="2" style="border:1px solid #cdcdcd;">Date <?php echo isset($select_result['date'])?$select_result['date']:""; ?> </td>
                  <td style="border:1px solid #cdcdcd;">Time <?php echo isset($select_result['time'])?$select_result['time']:""; ?> </td>
                  <td style="border:1px solid #cdcdcd;">Indication <?php echo isset($select_result['indication'])?$select_result['indication']:""; ?> </td>
                  <td style="border:1px solid #cdcdcd;">
                    ALLERGIES <br>
                    <?php echo isset($select_result['allergy'])?$select_result['allergy']:""; ?>
                  </td>
                  <td style="border:1px solid #cdcdcd;">
                    Consent<br>
                      
  <?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'yes'; }?>
                   
                  </td>
                  <td style="border:1px solid #cdcdcd;">
                    ID checked<br>
                   
  
  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'yes'; }?>
       
  
                  </td>
                </tr>
                <tr style="color: red; border:1px solid #cdcdcd;">
                  <td style="border:1px solid #cdcdcd;">PRE ASSESSMENT</td>
                  <td style="border:1px solid #cdcdcd;">BP <input  type="text" value="<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>"   maxlength="20" name="bp"></td>
                  <td style="border:1px solid #cdcdcd;">
                    PULSE <br>
                   
               
<?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'yes'; }?>
  
			   </td>
                  <td style="border:1px solid #cdcdcd;">
                    RESP <br>
                  
            
<?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'yes'; }?>
  
			</td>
                  <td style="border:1px solid #cdcdcd;">
                    VOIDED <br>
               
<?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'yes'; }?>
  			  </td>
			  
                  <td style="border:1px solid #cdcdcd;">HT (Cms)<br>
                    <?php echo isset($select_result['ht'])?$select_result['ht']:""; ?>
                  </td>
                  <td style="border:1px solid #cdcdcd;">
                    WT (Kg)<br>
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
                  <td colspan="2" style="border:1px solid #cdcdcd;">
                    Dental bridge<br>
                   
  
  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'yes'; }?>
  
  
                  </td>
                  <td style="border:1px solid #cdcdcd;">
                    Valuables with escort<br>
                   
  
  <?php if(isset($select_result['valuables_with_escort']) && $select_result['report'] == "Yes"){echo 'yes'; }?>
  
                  </td>
                 


				 <td style="border:1px solid #cdcdcd;">Last meal <?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?></td>
                </tr>
              </thead>
            </table>
         
        </ul>
      
        <table class="table table-bordered table-hover  table-sm red-field tableMg"  style="width:100%; border:1px solid #cdcdcd;">
          <tr>
            <td colspan="2" style="border:1px solid #cdcdcd;"><b>Prescriptions given</b></td>
          </tr>
          <tr>
            <td colspan="2" style="border:1px solid #cdcdcd;">Lignocaine jelly applied vaginally</td>
          </tr>
          <tr>
            <td style="border:1px solid #cdcdcd;">Other</td>
            <td style="border:1px solid #cdcdcd; width:20%; min-height:30px;"> <?php echo isset($select_result['prescriptions_others'])?$select_result['prescriptions_others']:""; ?></td>
          </tr>
        </table>
        <table class="table table-bordered table-hover table-sm red-field tableMg"  style="width:100%; border:1px solid #cdcdcd;">
          <tr>
            <td style="border:1px solid #cdcdcd;">NURSE</td>
            <td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?> </td>
            <td style="border:1px solid #cdcdcd;">DOCTOR</td>
            <td style="border:1px solid #cdcdcd; width:20%;"> <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?></td>
          </tr>
          <tr>
            <td colspan="2" style="padding: 0; width:30%; border:1px solid #cdcdcd;">
              <table>
                <tr>
                  <td colspan="2" style="border:1px solid #cdcdcd;">Physical Examination</td>
                </tr>
                <tr>
                  <td style="border:1px solid #cdcdcd;">Resp</td>
                  <td style="border:1px solid #cdcdcd; width:20%;"> <?php echo isset($select_result['resp2'])?$select_result['resp2']:""; ?> </td>
                </tr>
                <tr>
                  <td style="border:1px solid #cdcdcd;">CVS</td>
                  <td style="border:1px solid #cdcdcd; width:20%;"> <?php echo isset($select_result['CVS'])?$select_result['CVS']:""; ?> </td>
                </tr>
                <tr>
                  <td style="border:1px solid #cdcdcd;">CNS</td>
                  <td style="border:1px solid #cdcdcd; width:20%;"> <?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?> </td>
                </tr>
                <tr>
                  <td style="border:1px solid #cdcdcd;">Abdominal</td>
                  <td style="border:1px solid #cdcdcd; width:20%:"> <?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?> </td>
                </tr>
                <tr>
                  <td style="border:1px solid #cdcdcd;">Others</td>
                  <td style="border:1px solid #cdcdcd; width:20%;"> <?php echo isset($select_result['others'])?$select_result['others']:""; ?> </td>
                </tr>
              </table>
            </td>
            <td style="padding: 0; " colspan="2">
              <table  style="width:100%; border:1px solid #cdcdcd;">
                <tr>
                  <td style="border:1px solid #cdcdcd;">Written informed consent taken. All vitals under normal range. 15 ml of venous blood was drawn from syringe system, centrifuged by ACP /normal centrifuge system for 5 min.</td>
                </tr>
                <tr>
                  <td style="border:1px solid #cdcdcd;">Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. A condom with lubricated jelly is put on the vaginal ultrasound probe,it is introduced transvaginally ,a baseline transvaginal ultrasound performed to see endometrium and ovaries. Following baseline scan , a sterile Cuscos speculum /Sims speculum with tenaculum introduced .The cervix cleansed with betadine.An IUI catheter with 0.5 ml of prepared PRP(supernatant pellet ) put in the uterine cavity .The speculum is taken out.</td>
                </tr>
                <tr>
                  <td style="border:1px solid #cdcdcd;">No complications seen. Patient stood the procedure well.Till20 mins patient made to lie down</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <table class="table table-bordered table-hover table-sm red-field"  style="width:100%; border:1px solid #cdcdcd;">
          <tr>
            <td colspan="2" style="border:1px solid #cdcdcd;"><b>Post Procedure orders</b></td>
          </tr>
          <tr>
            <td style="border:1px solid #cdcdcd;">
              
  
  <?php if(isset($select_result['normal_diet']) && $select_result['normal_diet'] == "Yes"){echo 'yes'; }?>
  
  
            </td>
            <td>Normal diet</td>
          </tr>
          <tr>
            <td style="border:1px solid #cdcdcd;">
             
  <?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "Yes"){echo 'yes'; }?>
  
            </td>
            <td style="border:1px solid #cdcdcd;">Tab Crocin 500 mg (SOS) thrice daily eight hourly after meals for 1 days</td>
          </tr>
          <tr>
            <td style="border:1px solid #cdcdcd;">
             
  
  
   <?php if(isset($select_result['other_medicines']) && $select_result['other_medicines'] == "Yes"){echo 'yes'; }?>
  
  
            </td>
            <td style="border:1px solid #cdcdcd;">Keep taking other medicines as the pt is taking</td>
          </tr>
          <tr>
            <td style="border:1px solid #cdcdcd;">
              
  
  <?php if(isset($select_result['report']) && $select_result['report'] == "Yes"){echo 'yes'; }?>
  
            </td>
            <td style="border:1px solid #cdcdcd;">To report if giddiness /nausea/vomiting/bleeding/pain/fever /purulent discharge immediately</td>
          </tr>
          <tr>
            <td style="border:1px solid #cdcdcd;">Doctors signature</td>
            <td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?> </td>
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