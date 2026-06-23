<?php

if(isset($_POST['submit'])){
  unset($_POST['submit']);

  $select_query = "SELECT * FROM `testicular_prp` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
  $select_result = run_select_query($select_query); 
  if(empty($select_result)){
      // mysql query to insert data
      $query = "INSERT INTO `testicular_prp` SET ";
      $sqlArr = array();
      foreach( $_POST as $key=> $value )
      {
        $sqlArr[] = " $key = '".addslashes($value)."'";
      }		
      $query .= implode(',' , $sqlArr);
  }else{
      // mysql query to update data
      $query = "UPDATE testicular_prp SET ";
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
$select_query = "SELECT * FROM `testicular_prp` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">TESTICULAR PRP THIRD CYCLE</h3></td>
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
          <div class="container1 red-field form mt-5 mb-5">
              <ul class="d-flex mb-1 mt-2 list-unstyled">
                <div class = "table-responsive">
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
                  <table class="table table-bordered table-hover table-sm">
                    <thead>
                      <tr style="color: red;">
                        <th><strong>TESTICULAR PRP</strong></th>
                        <td>Date <input class="form-control"  type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"    placeholder="enter a date" name="date"></td>
                        <td>Time <input class="form-control"  type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"    id="appt" name="time"></td>
                        <td>Indication <input class="form-control" maxlength="50"  type="text" value="<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>"    name="indication"></td>
                        <td>Allergies <input class="form-control" maxlength="50"  type="text" value="<?php echo isset($select_result['allergy'])?$select_result['allergy']:""; ?>"    name="allergy"></td>
                        <td>
                          Consent<br>
                          <label><input type="radio"   value="Yes"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'checked="checked"'; }?>   name="consent"> Yes</label>
                          <label><input type="radio"    value="No"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['consent']) && $select_result['consent'] != "Yes"){echo 'checked="checked"';}?>  name="consent"> No</label>
                        </td>
                        <td>
                          ID <br>
                          <label><input type="radio"   value="Yes"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?>   name="id_checked"> Yes</label>
                          <label><input type="radio"    value="No"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['id_checked']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?>  name="id_checked"> No</label>
                        </td>
                      </tr>
                      <tr style="color: red;">
                        <th><strong>PRE ASSESSMENT</strong></th>
                        <td>BP <input class="form-control" maxlength="20"  type="text" value="<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>"    name="bp"></td>
                        <td>
                          Pulse<br>
                          <label><input type="radio"   value="Yes"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'checked="checked"'; }?>   name="pulse"> Yes</label>
                          <label><input type="radio"    value="No"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pulse']) && $select_result['pulse'] != "Yes"){echo 'checked="checked"';}?>  name="pulse"> No</label>
                        </td>
                        <td>
                          RESP<br>
                          <label><input type="radio"   value="Yes"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'checked="checked"'; }?>   name="resp"> Yes</label>
                          <label><input type="radio"    value="No"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['resp']) && $select_result['resp'] != "Yes"){echo 'checked="checked"';}?>  name="resp"> No</label>
                        </td>
                        <td>
                          Voided<br>
                          <label><input type="radio"   value="Yes"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'checked="checked"'; }?>   name="voided"> Yes</label>
                          <label><input type="radio"    value="No"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['voided']) && $select_result['voided'] != "Yes"){echo 'checked="checked"';}?>  name="voided"> No</label>
                        </td>
                        <td>HT (Cms)<input class="form-control"  type="number" value="<?php echo isset($select_result['ht'])?$select_result['ht']:""; ?>"    min="0" name="ht"></td>
                        <td>WT (Kg)<input class="form-control"  type="number" value="<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?>"    min="0" name="wt"></td>
                      </tr>
                      <tr>
                        <td>
                          Glasses<br>
                          <label><input type="radio"   value="Yes"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'checked="checked"'; }?>   name="glasses"> Yes</label>
                          <label><input type="radio"    value="No"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['glasses']) && $select_result['glasses'] != "Yes"){echo 'checked="checked"';}?>  name="glasses"> No</label>
                        </td>
                        <td>
                          Contacts<br>
                          <label><input type="radio"   value="Yes"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'checked="checked"'; }?>   name="contacts"> Yes</label>
                          <label><input type="radio"    value="No"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['contacts']) && $select_result['contacts'] != "Yes"){echo 'checked="checked"';}?>  name="contacts"> No</label>
                        </td>
                        <td>
                          Denture<br>
                          <label><input type="radio"   value="Yes"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'checked="checked"'; }?>   name="denture"> Yes</label>
                          <label><input type="radio"    value="No"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['denture']) && $select_result['denture'] != "Yes"){echo 'checked="checked"';}?>  name="denture"> No</label>
                        </td>
                        <td colspan="2">
                          Dental Bridge<br>
                          <label><input type="radio"   value="Yes"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'checked="checked"'; }?>   name="dental_bridge"> Yes</label>
                          <label><input type="radio"    value="No"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] != "Yes"){echo 'checked="checked"';}?>  name="dental_bridge"> No</label>
                        </td>
                        <td>
                          Valuables with escort<br>
                          <label><input type="radio"   value="Yes"  <?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "Yes"){echo 'checked="checked"'; }?>   name="valuables_with_escort"> Yes</label>
                          <label><input type="radio"    value="No"  <?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] != "Yes"){echo 'checked="checked"';}?>  name="valuables_with_escort"> No</label>
                        </td>
                        <td>Last meal <input class="form-control"  type="time" value="<?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?>"    name="last_meal"></td>
                      </tr>
                    </thead>
                  </table>
                </div>
              </ul>
          <div class = "table-responsive">
          <table class="table table-bordered table-hover  table-sm red-field tableMg">
              <thead>
                  <tr>
                      <th>
                        Prescriptions given
                      </th>
                  </tr>
                  <tr>
                    <td>
                      Lignocaine jelly applied locally<br>
                      Other <input class="form-control"  type="text" value="<?php echo isset($select_result['prescriptions_other'])?$select_result['prescriptions_other']:""; ?>"    maxlength="100" name="prescriptions_other">
                    </td>
                  </tr>
              </thead>
          </table>
          <table class="table table-bordered table-hover table-sm red-field tableMg">
            <thead>
              <tr>
                <th>NURSE <input  type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>"    maxlength="20" name="nurse"></th>
                <th>DOCTOR <input  type="text" value="<?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?>"    maxlength="20" name="doctor"></th>
              </tr>
            </thead>
            <thead>
              <tr>
                <td style="padding: 0; width:30%;">
                  <table width="100%">
                    <tr><td colspan="2">Physical Examination</td></tr>
                    <tr>
                      <td>Resp</td>
                      <td><input  type="text" value="<?php echo isset($select_result['Resp2'])?$select_result['Resp2']:""; ?>"    maxlength="20" name="Resp2"></td>
                    </tr>
                    <tr>
                      <td>CVS</td>
                      <td><input  type="text" value="<?php echo isset($select_result['CVS'])?$select_result['CVS']:""; ?>"    maxlength="20" name="CVS"></td>
                    </tr>
                    <tr>
                      <td>CNS</td>
                      <td><input  type="text" value="<?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?>"    maxlength="20" name="CNS"></td>
                    </tr>
                    <tr>
                      <td>Abdominal</td>
                      <td><input  type="text" value="<?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?>"    maxlength="20" name="abdominal"></td>
                    </tr>
                    <tr>
                      <td>Others</td>
                      <td><input  type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>"    maxlength="100" name="others"></td>
                    </tr>
                  </table>
                      </td>
                      <td>
                        <p>
                          Written informed consent taken. All vitals under normal range. 15 ml of venous blood was drawn from  syringe system , centrifuged by ACP /normal centrifuge system for 5 min. Patient put in supine position ,under all sterile conditions, the scrotum was cleansed by betadine and draped.The PRP was injected beneath the tunica albuginea in the testicular tissue ,3 ml in each testes,under ultrasound guidance, using a 23 G needle with a 20 ml syringe attached to it. No complications seen. Patient stood the procedure well.Till20 mins patient made to lie down.No complications seen . Hemostasis achieved. Patient stood the procedure well.
                        </p>
                           <p><input  type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>"    maxlength="100" name="others"></p>
                   
                      </td>
                  </tr>
              </thead>
             <!-- <thead>
                <tr>
                  <td colspan="2"><b>Post Procedure Orders</b></td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['exercise_daily']) && $select_result['exercise_daily'] == "Yes"){echo 'checked="checked"'; }?>   name="exercise_daily"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['exercise_daily']) && $select_result['exercise_daily'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['exercise_daily']) && $select_result['exercise_daily'] != "Yes"){echo 'checked="checked"';}?>  name="exercise_daily"> No</label>
                  </td>
                  <td>Regular exercise daily for one hour</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['avoid_hot_sauna']) && $select_result['avoid_hot_sauna'] == "Yes"){echo 'checked="checked"'; }?>   name="avoid_hot_sauna"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['avoid_hot_sauna']) && $select_result['avoid_hot_sauna'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['avoid_hot_sauna']) && $select_result['avoid_hot_sauna'] != "Yes"){echo 'checked="checked"';}?>  name="avoid_hot_sauna"> No</label>
                  </td>
                  <td>Avoid hot sauna bath</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['diet']) && $select_result['diet'] == "Yes"){echo 'checked="checked"'; }?>   name="diet"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['diet']) && $select_result['diet'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['diet']) && $select_result['diet'] != "Yes"){echo 'checked="checked"';}?>  name="diet"> No</label>
                  </td>
                  <td>Diet low in carbohydrate ,  green leafy vegetables , beans,pulses ,high protein diet</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['avoid_tea_coffee']) && $select_result['avoid_tea_coffee'] == "Yes"){echo 'checked="checked"'; }?>   name="avoid_tea_coffee"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['avoid_tea_coffee']) && $select_result['avoid_tea_coffee'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['avoid_tea_coffee']) && $select_result['avoid_tea_coffee'] != "Yes"){echo 'checked="checked"';}?>  name="avoid_tea_coffee"> No</label>
                  </td>
                  <td>Avoid/limit intake of tea and coffee</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['drink_plenty']) && $select_result['drink_plenty'] == "Yes"){echo 'checked="checked"'; }?>   name="drink_plenty"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['drink_plenty']) && $select_result['drink_plenty'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['drink_plenty']) && $select_result['drink_plenty'] != "Yes"){echo 'checked="checked"';}?>  name="drink_plenty"> No</label>
                  </td>
                  <td>Drink plenty of fluids</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['walnut_intake']) && $select_result['walnut_intake'] == "Yes"){echo 'checked="checked"'; }?>   name="walnut_intake"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['walnut_intake']) && $select_result['walnut_intake'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['walnut_intake']) && $select_result['walnut_intake'] != "Yes"){echo 'checked="checked"';}?>  name="walnut_intake"> No</label>
                  </td>
                  <td>4 Walnut intake daily</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['tab_levoflox']) && $select_result['tab_levoflox'] == "Yes"){echo 'checked="checked"'; }?>   name="tab_levoflox"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['tab_levoflox']) && $select_result['tab_levoflox'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['tab_levoflox']) && $select_result['tab_levoflox'] != "Yes"){echo 'checked="checked"';}?>  name="tab_levoflox"> No</label>
                  </td>
                  <td>Tab.Levoflox 500 mg once daily  after meals for  5 days</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['cap_pantoprazole']) && $select_result['cap_pantoprazole'] == "Yes"){echo 'checked="checked"'; }?>   name="cap_pantoprazole"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['cap_pantoprazole']) && $select_result['cap_pantoprazole'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cap_pantoprazole']) && $select_result['cap_pantoprazole'] != "Yes"){echo 'checked="checked"';}?>  name="cap_pantoprazole"> No</label>
                  </td>
                  <td>Cap Pantoprazole 40 mg once daily in empty stomach for 5 days</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "Yes"){echo 'checked="checked"'; }?>   name="tab_crocin"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] != "Yes"){echo 'checked="checked"';}?>  name="tab_crocin"> No</label>
                  </td>
                  <td>Tab Crocin 500 mg thrice daily eight hourly after meals for 2 days</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['cap_lycopene']) && $select_result['cap_lycopene'] == "Yes"){echo 'checked="checked"'; }?>   name="cap_lycopene"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['cap_lycopene']) && $select_result['cap_lycopene'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cap_lycopene']) && $select_result['cap_lycopene'] != "Yes"){echo 'checked="checked"';}?>  name="cap_lycopene"> No</label>
                  </td>
                  <td>Cap Lycopene once daily for 72 days after meals</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['arginine_sachet']) && $select_result['arginine_sachet'] == "Yes"){echo 'checked="checked"'; }?>   name="arginine_sachet"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['arginine_sachet']) && $select_result['arginine_sachet'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['arginine_sachet']) && $select_result['arginine_sachet'] != "Yes"){echo 'checked="checked"';}?>  name="arginine_sachet"> No</label>
                  </td>
                  <td>L-Arginine sachet twice daily for 72 days after meals</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['cap_evion']) && $select_result['cap_evion'] == "Yes"){echo 'checked="checked"'; }?>   name="cap_evion"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['cap_evion']) && $select_result['cap_evion'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cap_evion']) && $select_result['cap_evion'] != "Yes"){echo 'checked="checked"';}?>  name="cap_evion"> No</label>
                  </td>
                  <td>Cap Evion 400 mg once daily for 72 days after meals</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['cap_vit']) && $select_result['cap_vit'] == "Yes"){echo 'checked="checked"'; }?>   name="cap_vit"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['cap_vit']) && $select_result['cap_vit'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cap_vit']) && $select_result['cap_vit'] != "Yes"){echo 'checked="checked"';}?>  name="cap_vit"> No</label>
                  </td>
                  <td>Cap Vit D3 60000 IU once weekly for 72 days after meals</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['cap_ubqhr']) && $select_result['cap_ubqhr'] == "Yes"){echo 'checked="checked"'; }?>   name="cap_ubqhr"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['cap_ubqhr']) && $select_result['cap_ubqhr'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['cap_ubqhr']) && $select_result['cap_ubqhr'] != "Yes"){echo 'checked="checked"';}?>  name="cap_ubqhr"> No</label>
                  </td>
                  <td>Cap UBQHR once daily for 72 days</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['medications_as_advised']) && $select_result['medications_as_advised'] == "Yes"){echo 'checked="checked"'; }?>   name="medications_as_advised"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['medications_as_advised']) && $select_result['medications_as_advised'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['medications_as_advised']) && $select_result['medications_as_advised'] != "Yes"){echo 'checked="checked"';}?>  name="medications_as_advised"> No</label>
                  </td>
                  <td>Continue thyroid/antihypertensive/diabetes /other medical disorder medications as advised</td>
                </tr>
                <tr>
                  <td>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['report']) && $select_result['report'] == "Yes"){echo 'checked="checked"'; }?>   name="report"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['report']) && $select_result['report'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['report']) && $select_result['report'] != "Yes"){echo 'checked="checked"';}?>  name="report"> No</label>
                  </td>
                  <td>To report if giddiness /nausea/vomiting/bleeding/pain/fever /purulent discharge/swelling immediately</td>
                </tr>
              </thead>-->
          </table>
          <table class="table table-bordered table-hover table-sm red-field tableMg">
            <thead>
                  <tr>
                      <th><p>Doctors signature <input name="doctor_signature"  type="text" value="<?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?>"   ></p></th>
                  </tr>
            </thead>  
          </table>
          </div>
          <!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
          <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
      </form>
	  
	  
	  
	  
	  
	  <!---         Print Button      -->
	  
	  
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none">  

                <table class="table table-bordered table-hover table-sm" style="width:100%; border:1px solid #cdcdcd;">
					 <tr>
                        <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
             		    <td width="50%" colspan="2" style="border:1px solid #cdcdcd;text-align:center;"><h2><strong>TESTICULAR PRP THIRD CYCLE</strong></h2></td>
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
            			
					
                  <table class="table table-bordered table-hover table-sm" style="width:100%; border:1px solid #cdcdcd;">
                    <thead>
                      <tr style="color: red;">
                        <th  style="border:1px solid #cdcdcd;"><strong>TESTICULAR PRP</strong></th>
                        <td  style="border:1px solid #cdcdcd;">Date <?php echo isset($select_result['date'])?$select_result['date']:""; ?></td>
                        <td  style="border:1px solid #cdcdcd;">Time <?php echo isset($select_result['time'])?$select_result['time']:""; ?></td>
                        <td  style="border:1px solid #cdcdcd;">Indication <?php echo isset($select_result['indication'])?$select_result['indication']:""; ?></td>
                        <td  style="border:1px solid #cdcdcd;">Allergies <?php echo isset($select_result['allergy'])?$select_result['allergy']:""; ?></td>
                        <td  style="border:1px solid #cdcdcd;">
                          Consent<br>
                                
<?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'yes'; }?>

					 </td>
                        <td  style="border:1px solid #cdcdcd;">
                          ID <br>
                         
                      
<?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'yes'; }?>


					  </td>
                      </tr>
                      <tr style="color: red;">
                        <th  style="border:1px solid #cdcdcd;"><strong>PRE ASSESSMENT</strong></th>
                        <td  style="border:1px solid #cdcdcd;">BP <?php echo isset($select_result['bp'])?$select_result['bp']:""; ?></td>
                        <td  style="border:1px solid #cdcdcd;">
                          Pulse<br>
                        
<?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'yes'; }?>

					  </td>
                        <td  style="border:1px solid #cdcdcd;">
                          RESP<br>
                        
<?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'yes'; }?>
					  </td>
                        <td  style="border:1px solid #cdcdcd;">
                          Voided<br>
                    
<?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'yes'; }?>


					  </td>
                        <td  style="border:1px solid #cdcdcd;">HT (Cms)<?php echo isset($select_result['ht'])?$select_result['ht']:""; ?></td>
                        <td  style="border:1px solid #cdcdcd;">WT (Kg)<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?> </td>
                      </tr>
                      <tr>
                        <td  style="border:1px solid #cdcdcd;">
                          Glasses<br>
                          
<?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'yes'; }?>


					   </td>
                        <td  style="border:1px solid #cdcdcd;">
                          Contacts<br>
                        
<?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'yes'; }?>

					   </td>
                        <td  style="border:1px solid #cdcdcd;">
                          Denture<br>
                         
<?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'yes'; }?>


					  </td>
                       <td  colspan="2"  style="border:1px solid #cdcdcd;" >
                          Dental Bridge<br>
                                                
<?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'yes'; }?>
					   </td>
                        <td  style="border:1px solid #cdcdcd;">
                          Valuables with escort<br>
                          
                       
<?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "Yes"){echo 'yes'; }?>

					   </td>
                        <td  style="border:1px solid #cdcdcd;">Last meal <?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?></td>
                      </tr>
                    </thead>
                  </table>
             
         
       
          <table style="width:100%; border:1px solid #cdcdcd;">
              <thead>
                  <tr>
                      <th  style="border:1px solid #cdcdcd;">
                        Prescriptions given
                      </th>
                  </tr>
                  <tr>
                    <td  style="border:1px solid #cdcdcd;">
                      Lignocaine jelly applied locally<br>
                      Other <?php echo isset($select_result['prescriptions_other'])?$select_result['prescriptions_other']:""; ?>
                    </td>
                  </tr>
              </thead>
          </table>
          <table style="width:100%; border:1px solid #cdcdcd;">
            <thead>
              <tr>
                <th  style="border:1px solid #cdcdcd;">NURSE <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?></th>
                <th  style="border:1px solid #cdcdcd;">DOCTOR <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?></th>
              </tr>
            </thead>
            <thead>
              <tr>
                <td style="padding: 0; width:30%; border:1px solid #cdcdcd;">
                  <table width="100%">
                    <tr><td colspan="2" style="border:1px solid #cdcdcd;">Physical Examination</td></tr>
                    <tr>
                      <td  style="border:1px solid #cdcdcd;">Resp</td>
                      <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['Resp2'])?$select_result['Resp2']:""; ?></td>
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
                      <td  style="border:1px solid #cdcdcd;">
                        <p>
                          Written informed consent taken. All vitals under normal range. 15 ml of venous blood was drawn from  syringe system , centrifuged by ACP /normal centrifuge system for 5 min. Patient put in supine position ,under all sterile conditions, the scrotum was cleansed by betadine and draped.The PRP was injected beneath the tunica albuginea in the testicular tissue ,3 ml in each testes,under ultrasound guidance, using a 23 G needle with a 20 ml syringe attached to it. No complications seen. Patient stood the procedure well.Till20 mins patient made to lie down.No complications seen . Hemostasis achieved. Patient stood the procedure well.
                        </p>
                      </td>
                  </tr>
              </thead>
              <!--<thead>
                <tr>
                 <td  colspan="2"  style="border:1px solid #cdcdcd;" ><b>Post Procedure Orders</b></td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                                    
<?php if(isset($select_result['exercise_daily']) && $select_result['exercise_daily'] == "Yes"){echo 'yes'; }?>

				 </td>
                  <td  style="border:1px solid #cdcdcd;">Regular exercise daily for one hour</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                                  
<?php if(isset($select_result['avoid_hot_sauna']) && $select_result['avoid_hot_sauna'] == "Yes"){echo 'yes'; }?>

				 </td>
                  <td  style="border:1px solid #cdcdcd;">Avoid hot sauna bath</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                                      
		<?php if(isset($select_result['diet']) && $select_result['diet'] == "Yes"){echo 'yes'; }?>		  
				  </td>
                  <td  style="border:1px solid #cdcdcd;">Diet low in carbohydrate ,  green leafy vegetables , beans,pulses ,high protein diet</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
         <?php if(isset($select_result['avoid_tea_coffee']) && $select_result['avoid_tea_coffee'] == "Yes"){echo 'yes'; }?>          
				  </td>
                  <td  style="border:1px solid #cdcdcd;">Avoid/limit intake of tea and coffee</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                                 

<?php if(isset($select_result['drink_plenty']) && $select_result['drink_plenty'] == "Yes"){echo 'yes'; }?>

			   </td>
                  <td  style="border:1px solid #cdcdcd;">Drink plenty of fluids</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                   <?php if(isset($select_result['walnut_intake']) && $select_result['walnut_intake'] == "Yes"){echo 'yes'; }?>


				</td>
                  <td  style="border:1px solid #cdcdcd;">4 Walnut intake daily</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                   
<?php if(isset($select_result['tab_levoflox']) && $select_result['tab_levoflox'] == "Yes"){echo 'yes'; }?>
				</td>
                  <td  style="border:1px solid #cdcdcd;">Tab.Levoflox 500 mg once daily  after meals for  5 days</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                  
                 
<?php if(isset($select_result['cap_pantoprazole']) && $select_result['cap_pantoprazole'] == "Yes"){echo 'yes'; }?>



				 </td>
                  <td  style="border:1px solid #cdcdcd;">Cap Pantoprazole 40 mg once daily in empty stomach for 5 days</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                   
                 
<?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "Yes"){echo 'yes'; }?>



				 </td>
                  <td  style="border:1px solid #cdcdcd;">Tab Crocin 500 mg thrice daily eight hourly after meals for 2 days</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                                    
<?php if(isset($select_result['cap_lycopene']) && $select_result['cap_lycopene'] == "Yes"){echo 'yes'; }?>

				 </td>
                 


				 <td  style="border:1px solid #cdcdcd;">Cap Lycopene once daily for 72 days after meals</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                  
            <?php if(isset($select_result['arginine_sachet']) && $select_result['arginine_sachet'] == "Yes"){echo 'yes'; }?>      
				  
				  </td>
                  <td  style="border:1px solid #cdcdcd;">L-Arginine sachet twice daily for 72 days after meals</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                       
<?php if(isset($select_result['cap_evion']) && $select_result['cap_evion'] == "Yes"){echo 'yes'; }?>   
				 </td>
                  <td  style="border:1px solid #cdcdcd;">Cap Evion 400 mg once daily for 72 days after meals</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                      
  <?php if(isset($select_result['cap_vit']) && $select_result['cap_vit'] == "Yes"){echo 'yes'; }?>
                  </td>
                  <td  style="border:1px solid #cdcdcd;">Cap Vit D3 60000 IU once weekly for 72 days after meals</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                  
<?php if(isset($select_result['cap_ubqhr']) && $select_result['cap_ubqhr'] == "Yes"){echo 'yes'; }?>


				 </td>
                  <td  style="border:1px solid #cdcdcd;">Cap UBQHR once daily for 72 days</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                   
                 
<?php if(isset($select_result['medications_as_advised']) && $select_result['medications_as_advised'] == "Yes"){echo 'yes'; }?>



				 </td>
                  <td  style="border:1px solid #cdcdcd;">Continue thyroid/antihypertensive/diabetes /other medical disorder medications as advised</td>
                </tr>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">
                   
  <?php if(isset($select_result['report']) && $select_result['report'] == "Yes"){echo 'yes'; }?>
  
                  </td>
                  <td  style="border:1px solid #cdcdcd;">To report if giddiness /nausea/vomiting/bleeding/pain/fever /purulent discharge/swelling immediately</td>
                </tr>
              </thead>-->
          </table>
          <table class="table table-bordered table-hover table-sm red-field tableMg"  style="width:100%; border:1px solid #cdcdcd;">
            <thead>
                  <tr>
  <th  style="border:1px solid #cdcdcd;"><p> Doctors signature <?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?> </p></th>
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