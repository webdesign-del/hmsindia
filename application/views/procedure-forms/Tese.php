<?php

if(isset($_POST['submit'])){
  unset($_POST['submit']);
  
  $select_query = "SELECT * FROM `tese` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
  $select_result = run_select_query($select_query); 
  if(empty($select_result)){
      // mysql query to insert data
      $query = "INSERT INTO `tese` SET ";
      $sqlArr = array();
      foreach( $_POST as $key=> $value )
      {
        $sqlArr[] = " $key = '".addslashes($value)."'";
      }		
      $query .= implode(',' , $sqlArr);
  }else{
      // mysql query to update data
      $query = "UPDATE tese SET ";
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
$select_query = "SELECT * FROM `tese` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
$select_result = run_select_query($select_query);   

	// php code to Insert data into mysql database from input text
	// if(isset($_POST['submit'])){
  //   $patient_id = $_POST['patient_id'];
  //       $receipt_number = $_POST['receipt_number'];
	// 	$status = $_POST['status'];
	// 	// get values form input text and number
	// 	$date = $_POST['date'];
	// 	$time = $_POST['time'];
	// 	$indication = $_POST['indication'];
	// 	$allergy = $_POST['allergy'];
	// 	$consent = $_POST['consent'];
	// 	$id_ = $_POST['id_'];
	// 	$bp = $_POST['bp'];
	// 	$pulse = $_POST['pulse'];
	// 	$resp = $_POST['resp'];
	// 	$voided = $_POST['voided'];
	// 	$ht = $_POST['ht'];
	// 	$wt = $_POST['wt'];
	// 	$contacts = $_POST['contacts'];
	// 	$denture = $_POST['denture'];
	// 	$dental_bridge = $_POST['dental_bridge'];
	// 	$valuables_with_escort = $_POST['valuables_with_escort'];
	// 	$last_meal = $_POST['last_meal'];
	// 	$hpe = $_POST['hpe'];
	// 	$Resp2 = $_POST['Resp2'];
	// 	$CVS = $_POST['CVS'];
	// 	$CNS = $_POST['CNS'];
	// 	$abdominal = $_POST['abdominal'];
	// 	$others = $_POST['others'];
	// 	$sperm_detail = $_POST['sperm_detail'];
  //   $anesthetist = $_POST['anesthetist'];
  //   $embryologist = $_POST['embryologist'];
  //   $doctor = $_POST['doctor'];
  //   $nurse = $_POST['nurse'];
  //   $others2 = $_POST['others2'];
  //   $doctor_signature = $_POST['doctor_signature'];
  //   $glasses = $_POST['glasses'];
  //   $prescriptions_other = $_POST['prescriptions_other'];

	// 	// connect to mysql database using mysqli
		

	// 	// mysql query to insert data
	// 	$query = "INSERT INTO `tese`(`patient_id`, `receipt_number`, `status`,`date`,`time`,`indication`,`allergy`,`consent`,`id_`,`bp`,`pulse`,`resp`,`voided`,`ht`,`wt`,`contacts`,`denture`,`dental_bridge`,`valuables_with_escort`,`last_meal`,`hpe`,`Resp2`,`CVS`,`CNS`,`abdominal`,`others`,`sperm_detail`,`anesthetist`,`embryologist`,`doctor`,`nurse`,`others2`,`doctor_signature`,`glasses`,`prescriptions_other`) VALUES ('$patient_id','$receipt_number','$status','$date','$time','$indication','$allergy','$consent','$id_','$bp','$pulse','$resp','$voided','$ht','$wt','$contacts','$denture','$dental_bridge','$valuables_with_escort','$last_meal','$hpe','$Resp2','$CVS','$CNS','$abdominal','$others','$sperm_detail','$anesthetist','$embryologist','$doctor','$nurse','$others2','$doctor_signature','$glasses','$prescriptions_other')";
	
	// 	$result = run_form_query($query);

  //       if($result){
  //         header("location:" .base_url(). "procedure_reports/".$appointment_id."?m=".base64_encode('Procedure form inserted!').'&t='.base64_encode('success'));
	// 				die();
  //       }else{
  //         header("location:" .base_url(). "procedure_reports/".$appointment_id."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
	// 				die();
  //       }
	// }
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
        <ul class="d-flex mb-1 mt-2 list-unstyled">
          <div class="table-responsive">
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
                  <th><strong>TESE/M-TESE</strong></th>
                  <td>Date <input class="form-control"  type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"    name="date"></td>
                  <td>Time <input class="form-control"  type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"    id="appt" name="time"></td>
                  <td>Indication <input class="form-control"  type="text" value="<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>"    maxlength="50" placeholder="Indication" name="indication"></td>
                  <td>ALLERGIES <input class="form-control"  type="text" value="<?php echo isset($select_result['allergy'])?$select_result['allergy']:""; ?>"    maxlength="50" placeholder="allergy" name="allergy"></td>
                  <td>
                    Consent<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'checked="checked"'; }?>   name="consent"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['consent']) && $select_result['consent'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['consent']) && $select_result['consent'] != "Yes"){echo 'checked="checked"';}?>   name="consent"> No</label>
                  </td>
                  <td>
                    ID <br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'checked="checked"'; }?>   name="id_checked"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['id_checked']) && $select_result['id_checked'] != "Yes"){echo 'checked="checked"';}?>   name="id_checked"> No</label>
                  </td>
                </tr>
                <tr style="color: red;">
                  <th><strong>PRE ASSESSMENT</strong></td>
                  <td>BP <input class="form-control"  type="text" value="<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>"    maxlength="20" name="bp"></td>
                  <td>
                    PULSE<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'checked="checked"'; }?>   name="pulse"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['pulse']) && $select_result['pulse'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['pulse']) && $select_result['pulse'] != "Yes"){echo 'checked="checked"';}?>   name="pulse"> No</label>
                  </td>
                  <td>
                    RESP<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'checked="checked"'; }?>   name="resp"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['resp']) && $select_result['resp'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['resp']) && $select_result['resp'] != "Yes"){echo 'checked="checked"';}?>   name="resp"> No</label>
                  </td>
                  <td>
                    VOIDED<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'checked="checked"'; }?>   name="voided"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['voided']) && $select_result['voided'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['voided']) && $select_result['voided'] != "Yes"){echo 'checked="checked"';}?>   name="voided"> No</label>
                  </td>
                  <td>HT (Cms)<input class="form-control"  type="number" value="<?php echo isset($select_result['ht'])?$select_result['ht']:""; ?>"    min="0" name="ht"></td>
                  <td>WT (Kg)<input class="form-control"  type="number" value="<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?>"    min="0" name="wt"></td>
                </tr>
                <tr>
                  <td>
                    Glasses<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'checked="checked"'; }?>   name="glasses"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['glasses']) && $select_result['glasses'] != "Yes"){echo 'checked="checked"';}?>   name="glasses"> No</label>
                  </td>
                  <td>
                    Contacts<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'checked="checked"'; }?>   name="contacts"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['contacts']) && $select_result['contacts'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['contacts']) && $select_result['contacts'] != "Yes"){echo 'checked="checked"';}?>   name="contacts"> No</label>
                  </td>
                  <td>
                    Denture<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'checked="checked"'; }?>   name="denture"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['denture']) && $select_result['denture'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['denture']) && $select_result['denture'] != "Yes"){echo 'checked="checked"';}?>   name="denture"> No</label>
                  </td>
                  <td colspan="2">
                    Dental bridge<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'checked="checked"'; }?>   name="dental_bridge"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] != "Yes"){echo 'checked="checked"';}?>   name="dental_bridge"> No</label>
                  </td>
                  <td>
                    Valuables with escort<br>
                    <label><input type="radio"   value="Yes"  <?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "Yes"){echo 'checked="checked"'; }?>   name="valuables_with_escort"> Yes</label>
                    <label><input type="radio"    value="No"  <?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] != "Yes"){echo 'checked="checked"';}?>   name="valuables_with_escort"> No</label>
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
              <span class= "mr-2">HPE</span>
              <label><input type="radio"  name="hpe" value="Yes"  <?php if(isset($select_result['hpe']) && $select_result['hpe'] == "Yes"){echo 'checked="checked"'; }?>   value="Yes"> Yes</label>
              <label><input type="radio"  name="hpe"   value="No"  <?php if(isset($select_result['hpe']) && $select_result['hpe'] == "No"){echo 'checked="checked"'; }
  else if(isset($select_result['hpe']) && $select_result['hpe'] != "Yes"){echo 'checked="checked"';}?>  >No</label>
            </th>
              </tr>
        </thead>  
      </table>
          <table class="table table-bordered table-hover  table-sm red-field tableMg">
            <thead>
              <tr><td><b>Prescriptions Given</b></td></tr>
              <tr><td>Injection Monocef 1 gm iv AST</td></tr>
              <tr><td>Injection Pantoprazole 40 mg i.m. stat</td></tr>
              <tr><td>Other: <input  type="text" value="<?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?>"    class="form-control" maxlength="100" name="prescriptions_other"></td></tr>
            </thead>
          </table>
      <table class="table table-bordered table-hover  table-sm red-field tableMg">
          <thead>
              <tr>
                <td>NURSE <input  type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>"    maxlength="20" class="form-control" name="nurse"></td>
                <td>DOCTOR <input  type="text" value="<?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?>"    maxlength="20" class="form-control" name="doctor"></td>
                <td>Embryologist <input  type="text" value="<?php echo isset($select_result['embryologist'])?$select_result['embryologist']:""; ?>"    maxlength="20" class="form-control" name="embryologist"></td>
                <td>Anesthetist <input  type="text" value="<?php echo isset($select_result['anesthetist'])?$select_result['anesthetist']:""; ?>"    maxlength="20" class="form-control" name="anesthetist"></td>
              </tr>
          </thead>
      </table>
      <table class="table table-bordered table-hover table-sm red-field tableMg">
          <thead>
              <tr>
                  <td style="padding: 0; width:30%;">
                    <table width="100%">
                      <tr>
                        <td colspan="2">PRE ASSESSMENT</td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          No sexual intercourse for 72 hours<br>
                          No active infection<br>
                          No aspirin or NSAID a week before
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">Physical Examination</td>
                      </tr>
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
                        <td><input maxlength="100"  type="text" value="<?php echo isset($select_result['others'])?$select_result['others']:""; ?>"    name="others"></td>
                      </tr>
                    </table>
                  </td>
                  <td>
                    <p>Written informed consent taken. All vitals  under normal range. Patient put in supine position ,under all sterile conditions, the scrotum was cleansed by betadine and draped. TESE was subsequently performed on both testes.</p>
                    <p>Tunica albugina and epididymis were exposed through a 5- to 15-mm incision in scrotal skin and tunica vaginalis. Three different incisions were made in tunica albugina near the sites of needle insertion, and a sample of roughly 5 × 3 × 2 mm was excised from each site. The specimens were placed in separate dishes and immediately given to embryologist. (TESE)</p>
                    <p>Micro TESE was subsequently performed on both testes. Tunica albugina and epididymis were exposed through a 15-mm incision in scrotal skin and tunica vaginalis. By operating microscope these multiple  incisions were explored for areas of tubules with sperm. These were collected in dishes and given to embryologist for processing. If none  can be identified, biopsies are instead taken at random from a wide range of locations. (M-TESE)</p>
                    <p>Tunica albugina were closed with continuous suture using Vicryl 4/0. Scrotal bandage was done. Patient stood the procedure well and shifted to recovery.</p>
                    <p>Patients were closely observed for 2 hours. Patient stood the procedure well. No complications.</p>
                    <p>Others <input  type="text" value="<?php echo isset($select_result['others2'])?$select_result['others2']:""; ?>"    maxlength="100" name="others2"></p>
                  </td>
              </tr>
          </thead>
          <thead>
              <tr>
                  <td colspan="2"><p class="d-flex p-2"><span>SPERM DETAIL</span><input  type="text" value="<?php echo isset($select_result['sperm_detail'])?$select_result['sperm_detail']:""; ?>"    name="sperm_detail"></p></td>
                  <!-- <td><a href="sperm_preparation.php">SPERM PREPERATION</a></td> -->
              </tr>
          </thead>
          <thead>
            <tr>
              <td colspan="2"><p>Doctors signature <input  type="text" value="<?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?>"    name="doctor_signature"></p></td>
            </tr>
          </thead>  
        </table>
      </div>
          <!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
          <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
</form>





		<!---  Print Button Start form --> 



<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none">  
		






        <ul class="d-flex mb-1 mt-2 list-unstyled">
         
            <table class="table-bordered"  style="width:100%;border:1px solid #cdcdcd;">
				<tr>
					<td colspan="2" style="border:1px solid #cdcdcd;">
        			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
        			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
        			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
        			            ){?>
        			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
        			    <?php } ?>
        			</td>
				</tr>
			</table>
            <table class="table table-bordered table-hover table-sm" style="width:100%;border:1px solid #cdcdcd;">
              <thead>
                <tr style="color: red;">
                  <th  style="border:1px solid #cdcdcd;"><strong>TESE/M-TESE</strong></th>
                  <td  style="border:1px solid #cdcdcd;">Date <?php echo isset($select_result['date'])?$select_result['date']:""; ?></td>
                  <td  style="border:1px solid #cdcdcd;">Time <?php echo isset($select_result['time'])?$select_result['time']:""; ?></td>
                  <td  style="border:1px solid #cdcdcd;">Indication <?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>"    </td>
                  <td  style="border:1px solid #cdcdcd;">ALLERGIES <?php echo isset($select_result['allergy'])?$select_result['allergy']:""; ?>"    </td>
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
                  <th  style="border:1px solid #cdcdcd;"><strong>PRE ASSESSMENT</strong></td>
                  <td  style="border:1px solid #cdcdcd;">BP <?php echo isset($select_result['bp'])?$select_result['bp']:""; ?></td>
                  <td  style="border:1px solid #cdcdcd;">
                    PULSE<br>
                       
<?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'yes'; }?>


				 </td>
                  <td  style="border:1px solid #cdcdcd;">
                    RESP<br>
                  
               
<?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'yes'; }?>


			   </td>
                  <td  style="border:1px solid #cdcdcd;">
                    VOIDED<br>
                
                
<?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'yes'; }?>

				</td>
                  <td  style="border:1px solid #cdcdcd;">HT (Cms) <?php echo isset($select_result['ht'])?$select_result['ht']:""; ?></td>
                  <td  style="border:1px solid #cdcdcd;">WT (Kg) <?php echo isset($select_result['wt'])?$select_result['wt']:""; ?></td>
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
                  <td colspan="2" style="border:1px solid #cdcdcd;">
                    Dental bridge<br>
					
					
					
             
                  
			<?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'yes'; }?>	  
				  
				  </td>
                  <td  style="border:1px solid #cdcdcd;">
                    Valuables with escort <br>
                   

				  
                
<?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "Yes"){echo 'yes'; }?>

				</td>
                  <td  style="border:1px solid #cdcdcd;">Last meal <?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?></td>
                </tr>
              </thead>
            </table>
         
        </ul>
   
      <table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
        <thead>
              <tr>
                  <th  style="border:1px solid #cdcdcd;">
              <span class= "mr-2">HPE</span>
              
            
			<?php if(isset($select_result['hpe']) && $select_result['hpe'] == "Yes"){echo 'yes'; }?>
			
			
			</th>
              </tr>
        </thead>  
      </table>
          <table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
            <thead>
              <tr><td  style="border:1px solid #cdcdcd;"><b>Prescriptions Given</b></td></tr>
              <tr><td  style="border:1px solid #cdcdcd;">Injection Monocef 1 gm iv AST</td></tr>
              <tr><td  style="border:1px solid #cdcdcd;">Injection Pantoprazole 40 mg i.m. stat</td></tr>
              <tr><td  style="border:1px solid #cdcdcd;">Other: <?php echo isset($select_result['afc_left'])?$select_result['afc_left']:""; ?></td></tr>
            </thead>
          </table>
      <table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
          <thead>
              <tr>
                <td  style="border:1px solid #cdcdcd;">NURSE <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?></td>
                <td  style="border:1px solid #cdcdcd;">DOCTOR <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?></td>
                <td  style="border:1px solid #cdcdcd;">Embryologist <?php echo isset($select_result['embryologist'])?$select_result['embryologist']:""; ?>    </td>
                <td  style="border:1px solid #cdcdcd;">Anesthetist <?php echo isset($select_result['anesthetist'])?$select_result['anesthetist']:""; ?>    </td>
              </tr>
          </thead>
      </table>
      <table class="table table-bordered table-hover table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
          <thead>
              <tr>
                  <td style="padding: 0; width:30%;">
                    <table width="100%">
                      <tr>
                        <td colspan="2" style="border:1px solid #cdcdcd;">PRE ASSESSMENT</td>
                      </tr>
                      <tr>
                        <td colspan="2" style="border:1px solid #cdcdcd;">
                          No sexual intercourse for 72 hours<br>
                          No active infection<br>
                          No aspirin or NSAID a week before
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2" style="border:1px solid #cdcdcd;">Physical Examination</td>
                      </tr>
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
                        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?></td>
                      </tr>
                      <tr>
                        <td  style="border:1px solid #cdcdcd;">Abdominal</td>
                        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?>"    </td>
                      </tr>
                      <tr>
                        <td  style="border:1px solid #cdcdcd;">Others</td>
                        <td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['others'])?$select_result['others']:""; ?></td>
                      </tr>
                    </table>
                  </td>
                  <td  style="border:1px solid #cdcdcd;">
                    <p>Written informed consent taken. All vitals  under normal range. Patient put in supine position ,under all sterile conditions, the scrotum was cleansed by betadine and draped. TESE was subsequently performed on both testes.</p>
                    <p>Tunica albugina and epididymis were exposed through a 5- to 15-mm incision in scrotal skin and tunica vaginalis. Three different incisions were made in tunica albugina near the sites of needle insertion, and a sample of roughly 5 × 3 × 2 mm was excised from each site. The specimens were placed in separate dishes and immediately given to embryologist. (TESE)</p>
                    <p>Micro TESE was subsequently performed on both testes. Tunica albugina and epididymis were exposed through a 15-mm incision in scrotal skin and tunica vaginalis. By operating microscope these multiple  incisions were explored for areas of tubules with sperm. These were collected in dishes and given to embryologist for processing. If none  can be identified, biopsies are instead taken at random from a wide range of locations. (M-TESE)</p>
                    <p>Tunica albugina were closed with continuous suture using Vicryl 4/0. Scrotal bandage was done. Patient stood the procedure well and shifted to recovery.</p>
                    <p>Patients were closely observed for 2 hours. Patient stood the procedure well. No complications.</p>
                    
					<p>Others <?php echo isset($select_result['others2'])?$select_result['others2']:""; ?>  </p>
                  </td>
              </tr>
          </thead>
          <thead>
              <tr>
                  <td colspan="2" style="border:1px solid #cdcdcd;"><p class="d-flex p-2"><span>SPERM DETAIL</span> <?php echo isset($select_result['sperm_detail'])?$select_result['sperm_detail']:""; ?> </p></td>
               
              </tr>
          </thead>
          <thead>
            <tr>
              <td colspan="2" style="border:1px solid #cdcdcd;"><p>Doctors signature <?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?> </p></td>
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
</script><script>
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
			