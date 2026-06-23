<?php
if(isset($_POST['submit'])){

  unset($_POST['submit']);

  $select_query = "SELECT * FROM `endometrial_scratching` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";

  $select_result = run_select_query($select_query); 

  if(empty($select_result)){

      // mysql query to insert data

      $query = "INSERT INTO `endometrial_scratching` SET ";

      $sqlArr = array();

      foreach( $_POST as $key=> $value )

      {

        $sqlArr[] = " $key = '".addslashes($value)."'";

      }		

      $query .= implode(',' , $sqlArr);

  }else{

      // mysql query to update data

      $query = "UPDATE endometrial_scratching SET ";

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

	$select_query = "SELECT * FROM `endometrial_scratching` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">ENDOMETRIAL SCRATCHING SECOND CYCLE</h3></td>
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
       <div class = "table-responsive">
<ul class="d-flex mb-1 list-unstyled">

        <li class = "form_header mt-5 mr-2">

            <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&

		            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 

		            isset($select_result['updated_type']) && !empty($select_result['updated_type'])

		            ){?>

		        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>

		    <?php } ?>

        </li>
</ul>

            <table class="table table-bordered table-hover mt-2 table-sm red-field">

            <thead>

                <tr>

                <th>Date</th>

                <th>Time</th>

                <th>Indication</th>

                <th>ALLERGIES</th>

                <th>Consent</th>

                <th>ID </th>

                </tr>

            </thead>

          <tbody>

            <tr>

              <td><input  type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"   name="date"></td>

              <td><input  type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"   name="time"></td>

              <td><input  type="text" value="<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>"   maxlength="50" placeholder="Indication" name="indication"></td>

              <td><input  type="text" value="<?php echo isset($select_result['allergy'])?$select_result['allergy']:""; ?>"   maxlength="50" placeholder="Allergies" name="allergy"></td>

              <td>

                <label><input type="radio"  value="yes"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "yes"){echo 'checked="checked"'; }?>  name="consent"> Yes</label>

                <label><input type="radio" value="No"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "No"){echo 'checked="checked"'; }else if(isset($select_result['consent']) && $select_result['consent'] != "Yes"){echo 'checked="checked"';}?>  name="consent"> No</label>

              </td>

              <td>

                <label><input type="radio" value="Yes"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?>  name="id_checked"> Yes</label>

                <label><input type="radio" value="No"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }else if(isset($select_result['id_checked']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?>  name="id_checked"> No</label>

              </td>

            </tr>

          </tbody>

          <thead>

            <tr>

              <th>BP</th>

              <th>PULSE</th>

              <th>RESP</th>

              <th>VOIDED</th>

              <th>HT (Cms)</th>

              <th>WT (Kg)</th>

            </tr>

          </thead>

          <tbody>

            <tr>

              <td><input  type="text" value="<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>"   maxlength='20' name="bp"></td>

              <td>

                <label><input type="radio"  value="Yes"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'checked="checked"'; }?>  name="pulse"> Yes</label>

                <label><input type="radio" value="No"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "No"){echo 'checked="checked"'; }else if(isset($select_result['pulse']) && $select_result['pulse'] != "Yes"){echo 'checked="checked"';}?>  name="pulse"> No</label>

            </td>

              <td>

                <label><input type="radio"  value="Yes"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'checked="checked"'; }?>  name="resp"> Yes</label>

                <label><input type="radio" value="No"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "No"){echo 'checked="checked"'; }else if(isset($select_result['resp']) && $select_result['resp'] != "Yes"){echo 'checked="checked"';}?>  name="resp"> No</label>

              </td>

              <td>

                <label><input type="radio"  value="Yes"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'checked="checked"'; }?>  name="voided"> Yes</label>

                <label><input type="radio" value="No"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "No"){echo 'checked="checked"'; }else if(isset($select_result['voided']) && $select_result['voided'] != "Yes"){echo 'checked="checked"';}?>  name="voided"> No</label>

              </td>

              <td><input  type="number" value="<?php echo isset($select_result['ht'])?$select_result['ht']:""; ?>"   min="0" name="ht"></td>

              <td><input  type="number" value="<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?>"   min="0" name="wt"></td>

            </tr>

          </tbody>

    </table>



        <table class="table table-hover table-bordered mt-2 red-field">

          <thead>

            <tr>

              <th>NURSE<br><input name="nurse" maxlength='20'  type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>"  ></th>

              <th>DOCTOR<br><input name="doctor" maxlength='30' readonly=""  type="text" value="<?php if (!empty($select_result['doctor'])) {  echo $select_result['doctor']; } else {  echo isset($_SESSION['logged_doctor']['name']) ? $_SESSION['logged_doctor']['name'] : ''; }  ?>"  ></th>

            </tr>

          </thead>

          <thead>

            <tr>

              <th>

                <h4>PRE ASSESSMENT</h4>

                <p>No active vaginal infection</p>

              </th>

              <th style="padding: 0;">

                <table width="100%">

                  <tr>

                    <td colspan="3">Written informed consent taken. All vitals  under normal range. Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. A sterile Cuscos speculum /Sims speculum with tenaculum introduced.</td>

                  </tr>

                  <tr>

                    <td>Per speculum</td>

                    <td>Cervix</td>

                    <td><input  type="text" value="<?php echo isset($select_result['cervix'])?$select_result['cervix']:""; ?>"   maxlength="50" name='cervix'></td>

                  </tr>

                  <tr>

                    <td></td>

                    <td>Vagina</td>

                    <td><input  type="text" value="<?php echo isset($select_result['vagina'])?$select_result['vagina']:""; ?>"   maxlength="50" name='vagina'></td>

                  </tr>

                  <tr>

                    <td></td>

                    <td>Discharge</td>

                    <td><input  type="text" value="<?php echo isset($select_result['discharge'])?$select_result['discharge']:""; ?>"   maxlength="50" name='discharge'></td>

                  </tr>

                  <tr>

                    <td colspan="3">An IUI catheter introduced and endometrial scratching done.</td>

                  </tr>

                  <tr>

                    <td colspan="3">An outer embryo transfer catheter introduced through external os to internal os , position of uterus and os demarcated.</td>

                  </tr>

                  <tr>

                    <td colspan="3">Patient stood the procedure well .No complications.</td>

                  </tr>

                  <tr>

                    <td colspan="1">Others</td>

                    <td colspan="2"><input  type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>"   maxlength="100" name="others"></td>

                  </tr>

                </table>

              </th>

            </tr>

          </thead>

          <thead>

            <tr>

              <th colspan="2">Post procedure orders</th>

            </tr>

            <tr>

              <td colspan="2" style="padding: 0;">

                <table width="100%">

                  <tr>

                    <td>

                      <label><input type="radio"  value="Yes"  <?php if(isset($select_result['normal_diet']) && $select_result['normal_diet'] == "Yes"){echo 'checked="checked"'; }?>  name="normal_diet"> Yes</label>

                      <label><input type="radio" value="No"  <?php if(isset($select_result['normal_diet']) && $select_result['normal_diet'] == "No"){echo 'checked="checked"'; }else if(isset($select_result['normal_diet']) && $select_result['normal_diet'] != "Yes"){echo 'checked="checked"';}?>  name="normal_diet"> No</label>

                    </td>

                    <td>Normal diet</td>

                  </tr>

                  <tr>

                    <td>

                      <label><input type="radio"  value="Yes"  <?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "Yes"){echo 'checked="checked"'; }?>  name="tab_crocin"> Yes</label>

                      <label><input type="radio" value="No"  <?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "No"){echo 'checked="checked"'; }else if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] != "Yes"){echo 'checked="checked"';}?>  name="tab_crocin"> No</label>

                    </td>

                    <td>Tab Crocin 500 mg thrice daily eight hourly after meals for 2 days</td>

                  </tr>

                  <tr>

                    <td>

                      <label><input type="radio"  value="Yes"  <?php if(isset($select_result['report']) && $select_result['report'] == "Yes"){echo 'checked="checked"'; }?>  name="report"> Yes</label>

                      <label><input type="radio" value="No"  <?php if(isset($select_result['report']) && $select_result['report'] == "No"){echo 'checked="checked"'; }else if(isset($select_result['report']) && $select_result['report'] != "Yes"){echo 'checked="checked"';}?>  name="report"> No</label>

                    </td>

                    <td>To report if giddiness /nausea/vomiting/bleeding/pain/fever /purulent discharge immediately</td>

                  </tr>

                </table>

              </td>

            </tr>

          </thead>

          <thead>

            <tr>

              <th>Doctors Signature</th>

              <th><input name="doctor_signature"  type="text" value="<?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?>"  ></th>

            </tr>

          </thead>

        </table>

      </div>

          <!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->

          <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">

    </form>


    <!-- print -->

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" style="margin-right: 120px" onclick="printtable();">


<div  class="printtable pttable"  id="printtable"  style="display: none;"> 
  <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">ENDOMETRIAL SCRATCHING SECOND CYCLE</h3></td>
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

<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">

 <thead>
<tr>

    <ul style="list-style:none;">
    <li class = "form_header mt-5 mr-2">

            <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&

                isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 

                isset($select_result['updated_type']) && !empty($select_result['updated_type'])

                ){?>

            <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>

        <?php } ?>

        </li>

      </ul>

   </tr>

    </thead>

    <thead>

                <tr>

                <th>Date</th>

                <th>Time</th>

                <th>Indication</th>

                <th>ALLERGIES</th>

                <th>Consent</th>

                <th>ID </th>

                </tr>

            </thead>

            <tbody>

            <tr style="height: 40px;">

              <td><?php echo isset($select_result['date'])?$select_result['date']:""; ?></td>

              <td><?php echo isset($select_result['time'])?$select_result['time']:""; ?></td>

              <td><?php echo isset($select_result['indication'])?$select_result['indication']:""; ?></td>

              <td><?php echo isset($select_result['allergy'])?$select_result['allergy']:""; ?></td>

              <td>

               <?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'yes'; }?>

                

              </td>

              <td>

                   <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'yes'; }?>
                

               

              </td>

            </tr>

          </tbody>

          <thead>

            <tr>

              <th>BP</th>

              <th>PULSE</th>

              <th>RESP</th>

              <th>VOIDED</th>

              <th>HT (Cms)</th>

              <th>WT (Kg)</th>

            </tr>

          </thead>

           <tbody>

            <tr style="height:40px">

              <td><?php echo isset($select_result['bp'])?$select_result['bp']:""; ?></td>

              <td>
                   <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'yes'; }?>
                

            </td>

              <td>

                  <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'yes'; }?>

                

              </td>

              <td>

  <?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'yes'; }?>
               
              </td>

              <td><?php echo isset($select_result['ht'])?$select_result['ht']:""; ?></td>

              <td><?php echo isset($select_result['wt'])?$select_result['wt']:""; ?></td>

            </tr>

          </tbody>


</table>

<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">

 <thead>

            <tr>

              <th>NURSE<br><?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?></th>

              <th>DOCTOR<br><?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?></th>

            </tr>

          </thead>

          <thead>

            <tr>

              <th>

                <h4>PRE ASSESSMENT</h4>

                <p>No active vaginal infection</p>

              </th>

              <th style="padding: 0;">

                <table width="100%">

                  <tr >

                    <td colspan="3" style="border:1px solid #cdcdcd";>Written informed consent taken. All vitals  under normal range. Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. A sterile Cuscos speculum /Sims speculum with tenaculum introduced.</td>

                  </tr>

                  <tr>

                    <td style="border:1px solid #cdcdcd";>Per speculum</td>

                    <td style="border:1px solid #cdcdcd";>Cervix</td>

                    <td style="border:1px solid #cdcdcd" width="50%" ;><?php echo isset($select_result['cervix'])?$select_result['cervix']:""; ?></td>

                  </tr>

                  <tr>

                    <td></td>

                    <td style="border:1px solid #cdcdcd";>Vagina</td>

                    <td style="border:1px solid #cdcdcd";><?php echo isset($select_result['vagina'])?$select_result['vagina']:""; ?></td>

                  </tr>

                  <tr>

                    <td></td>

                    <td style="border:1px solid #cdcdcd";>Discharge</td>

                    <td style="border:1px solid #cdcdcd";><?php echo isset($select_result['discharge'])?$select_result['discharge']:""; ?></td>

                  </tr>

                  <tr>

                    <td colspan="3" style="border:1px solid #cdcdcd";>An IUI catheter introduced and endometrial scratching done.</td>

                  </tr>

                 
                  <tr>

                    <td colspan="3">An outer embryo transfer catheter introduced through external os to internal os , position of uterus and os demarcated.</td>

                  </tr>

                  <tr>

                    <td colspan="3" style="border:1px solid #cdcdcd";>Patient stood the procedure well .No complications.</td>

                  </tr>

                  <tr>

                    <td colspan="1" style="border:1px solid #cdcdcd";>Others</td>

                    <td colspan="2" style="border:1px solid #cdcdcd";><?php echo isset($select_result['others'])?$select_result['others']:""; ?></td>

                  </tr>

                </table>

              </th>

            </tr>

          </thead>

          <thead>

            <tr>

              <th colspan="2">Post procedure orders</th>

            </tr>

            <tr>

              <td colspan="2" style="padding: 0;">

                <table width="100%">

                  <tr>

                    <td style="width:20%; border:1px solid #cdcdcd;">
                      <?php if(isset($select_result['normal_diet']) && $select_result['normal_diet'] == "Yes"){echo 'yes'; }?>
                    </td>

                    <td style="border:1px solid #cdcdcd";>Normal diet</td>

                  </tr>

                  <tr>

                     <td style="width:20%; border:1px solid #cdcdcd;">
                         <?php if(isset($select_result['tab_crocin']) && $select_result['tab_crocin'] == "Yes"){echo 'yes'; }?>
                    

                    </td>

                    <td style="border:1px solid #cdcdcd";>Tab Crocin 500 mg thrice daily eight hourly after meals for 2 days</td>

                  </tr>

                  <tr>

                    <td style="width:20%; border:1px solid #cdcdcd;">
                       <?php if(isset($select_result['report']) && $select_result['report'] == "Yes"){echo 'yes'; }?>
                     

                    </td>

                    <td style="border:1px solid #cdcdcd";>To report if giddiness /nausea/vomiting/bleeding/pain/fever /purulent discharge immediately</td>

                  </tr>

                </table>

              </td>

            </tr>

          </thead>

            <thead>

            <tr>

              <th>Doctors Signature</th>

              <th><?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?></th>

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