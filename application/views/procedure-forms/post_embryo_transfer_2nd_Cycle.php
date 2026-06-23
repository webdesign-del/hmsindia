<?php if (isset($_POST["submit"])) {
   unset($_POST["submit"]);
   $select_query = "SELECT * FROM `post_embryo_transfer` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
   $select_result = run_select_query($select_query);
   if (empty($select_result)) {
       $query = "INSERT INTO `post_embryo_transfer` SET ";
       $sqlArr = [];
       foreach ($_POST as $key => $value) {
           $sqlArr[] = " $key = '" . addslashes($value) . "'";
       }
       $query .= implode(",", $sqlArr);
   } else {
       $query = "UPDATE post_embryo_transfer SET ";
       foreach ($_POST as $key => $value) {
           $sqlArr[] = " $key = '" . $value . "'";
       }
       $query .= implode(",", $sqlArr);
       $query .= " WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
   }
   $result = run_form_query($query);
   if ($result) {
       header(
           "location:" .
               $_SERVER["HTTP_REFERER"] .
               "?m=" .
               base64_encode("Procedure form inserted!") .
               "&t=" .
               base64_encode("success")
       );
       die();
   } else {
       header(
           "location:" .
               $_SERVER["HTTP_REFERER"] .
               "?m=" .
               base64_encode("Something went wrong!") .
               "&t=" .
               base64_encode("error")
       );
       die();
   }
   }
   $select_query = "SELECT * FROM `post_embryo_transfer` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
   $select_result = run_select_query($select_query);
   ?>
<form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">
   <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
   <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
   <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">  
   <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">	<input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">	<input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">	<input type="hidden" value="pending" name="status"> 	
   <table class="table-bordered" width="100%">
      <tr>
         <td colspan="2">
            <h3>POST EMBRYO TRANSFER</h3>
            • Embryo transfer done under all aseptic precautions<br>				• To take normal diet			
         </td>
         <td colspan="2">
            <?php if (
               isset($select_result["updated_by"]) &&
               !empty($select_result["updated_by"]) &&
               isset($select_result["updated_at"]) &&
               !empty($select_result["updated_at"]) &&
               isset($select_result["updated_type"]) &&
               !empty($select_result["updated_type"])
               ) { ?>			        
            <p id="last_updated">Last updated on <?php echo $select_result[
               "updated_at"
               ]; ?> by <?php echo last_updated_user(
               $select_result["updated_type"],
               $select_result["updated_by"]
               ); ?></p>
            <?php } ?>			
         </td>
      </tr>
      <tr>
         <td>Treatment Advised</td>
      </tr>
   </table>
   <table class="table-bordered" width="100%">
      <tr>
         <td style="width: 5%;"></td>
         <td>Medication</td>
         <td>Dosage</td>
         <td>Route</td>
         <td>Times</td>
         <td>Timings</td>
         <td>When to start</td>
         <td>How many days</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="tab_taxim_o"  value="Yes"  <?php if (
            isset($select_result["tab_taxim_o"]) &&
            $select_result["tab_taxim_o"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="tab_taxim_o"   value="No"  <?php if (
            isset($select_result["tab_taxim_o"]) &&
            $select_result["tab_taxim_o"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["tab_taxim_o"]) &&
            $select_result["tab_taxim_o"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Tab Taxim O</td>
         <td>200mg</td>
         <td>Oral</td>
         <td>Twice daily</td>
         <td>After meals</td>
         <td>Immediately</td>
         <td>one day</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="cap_pantoprazole"  value="Yes"  <?php if (
            isset($select_result["cap_pantoprazole"]) &&
            $select_result["cap_pantoprazole"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="cap_pantoprazole"   value="No"  <?php if (
            isset($select_result["cap_pantoprazole"]) &&
            $select_result["cap_pantoprazole"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["cap_pantoprazole"]) &&
            $select_result["cap_pantoprazole"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Cap Pantoprazole</td>
         <td>40mg</td>
         <td>Oral</td>
         <td>Once daily</td>
         <td>Empty stomach</td>
         <td>SOS</td>
         <td>If gastritis</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="tab_crocin"  value="Yes"  <?php if (
            isset($select_result["tab_crocin"]) &&
            $select_result["tab_crocin"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="tab_crocin"   value="No"  <?php if (
            isset($select_result["tab_crocin"]) &&
            $select_result["tab_crocin"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["tab_crocin"]) &&
            $select_result["tab_crocin"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Tab Crocin</td>
         <td>500mg</td>
         <td>Oral</td>
         <td>SOS Maximum three times(if )</td>
         <td>After meals</td>
         <td>If pain</td>
         <td>If </td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="syrup_cremaffin"  value="Yes"  <?php if (
            isset($select_result["syrup_cremaffin"]) &&
            $select_result["syrup_cremaffin"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="syrup_cremaffin"   value="No"  <?php if (
            isset($select_result["syrup_cremaffin"]) &&
            $select_result["syrup_cremaffin"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["syrup_cremaffin"]) &&
            $select_result["syrup_cremaffin"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Syrup cremaffin</td>
         <td></td>
         <td>Oral</td>
         <td>One tsf</td>
         <td>After dinner</td>
         <td>SOS(if constipation</td>
         <td></td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="tab_estrabet"  value="Yes"  <?php if (
            isset($select_result["tab_estrabet"]) &&
            $select_result["tab_estrabet"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="tab_estrabet"   value="No"  <?php if (
            isset($select_result["tab_estrabet"]) &&
            $select_result["tab_estrabet"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["tab_estrabet"]) &&
            $select_result["tab_estrabet"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Tab Estrabet</td>
         <td>2mg</td>
         <td>Oral</td>
         <td>Twice/thrice/four times daily</td>
         <td>After meals</td>
         <td>Immediately</td>
         <td>sixteen days</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="tab_ecosprin"  value="Yes"  <?php if (
            isset($select_result["tab_ecosprin"]) &&
            $select_result["tab_ecosprin"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="tab_ecosprin"   value="No"  <?php if (
            isset($select_result["tab_ecosprin"]) &&
            $select_result["tab_ecosprin"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["tab_ecosprin"]) &&
            $select_result["tab_ecosprin"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Tab Ecosprin</td>
         <td>75 mg</td>
         <td>Oral</td>
         <td>Once daily</td>
         <td>After meals</td>
         <td>Immediately</td>
         <td>sixteen days</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="injection_gestone"  value="Yes"  <?php if (
            isset($select_result["injection_gestone"]) &&
            $select_result["injection_gestone"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="injection_gestone"   value="No"  <?php if (
            isset($select_result["injection_gestone"]) &&
            $select_result["injection_gestone"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["children"]) &&
            $select_result["id_checked"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Injection Gestone</td>
         <td>100 mg</td>
         <td>intramuscular</td>
         <td>Once daily</td>
         <td>After meals</td>
         <td>Immediately</td>
         <td>sixteen days</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="tab_wysolone"  value="Yes"  <?php if (
            isset($select_result["tab_wysolone"]) &&
            $select_result["tab_wysolone"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="tab_wysolone"   value="No"  <?php if (
            isset($select_result["tab_wysolone"]) &&
            $select_result["tab_wysolone"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["tab_wysolone"]) &&
            $select_result["tab_wysolone"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Tab wysolone/omnacortil</td>
         <td>5mg</td>
         <td>Oral</td>
         <td>Once daily</td>
         <td>After meals</td>
         <td>Immediately</td>
         <td>sixteen days</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="gestone_gel"  value="Yes"  <?php if (
            isset($select_result["gestone_gel"]) &&
            $select_result["gestone_gel"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="gestone_gel"   value="No"  <?php if (
            isset($select_result["gestone_gel"]) &&
            $select_result["gestone_gel"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["gestone_gel"]) &&
            $select_result["gestone_gel"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Crinone/gestone gel</td>
         <td>8%</td>
         <td>vaginal</td>
         <td>Once daily</td>
         <td>Before going to sleep</td>
         <td>Immediately</td>
         <td>sixteen days</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="tab_duphaston"  value="Yes"  <?php if (
            isset($select_result["tab_duphaston"]) &&
            $select_result["tab_duphaston"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="tab_duphaston"   value="No"  <?php if (
            isset($select_result["tab_duphaston"]) &&
            $select_result["tab_duphaston"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["tab_duphaston"]) &&
            $select_result["tab_duphaston"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Tablet Duphaston</td>
         <td>10 mg</td>
         <td>Oral</td>
         <td>Once daily</td>
         <td>After meals</td>
         <td>Immediately</td>
         <td>sixteen days</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="cap_lycopene"  value="Yes"  <?php if (
            isset($select_result["cap_lycopene"]) &&
            $select_result["cap_lycopene"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="cap_lycopene"   value="No"  <?php if (
            isset($select_result["cap_lycopene"]) &&
            $select_result["cap_lycopene"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["cap_lycopene"]) &&
            $select_result["cap_lycopene"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Cap Lycopene</td>
         <td></td>
         <td>Oral</td>
         <td>Once daily</td>
         <td>After meals</td>
         <td>Immediately</td>
         <td>sixteen days</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="injection_clexane"  value="Yes"  <?php if (
            isset($select_result["injection_clexane"]) &&
            $select_result["injection_clexane"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="injection_clexane"   value="No"  <?php if (
            isset($select_result["injection_clexane"]) &&
            $select_result["injection_clexane"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["injection_clexane"]) &&
            $select_result["injection_clexane"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Injection clexane</td>
         <td>40mg</td>
         <td>subcutaneous</td>
         <td>Once daily/alternate day</td>
         <td>After meals</td>
         <td>Immediately</td>
         <td>sixteen days</td>
      </tr>
      <tr>
         <td>				<input type="radio"  name="injection_puborjen_jo"  value="Yes"  <?php if (
            isset($select_result["injection_puborjen_jo"]) &&
            $select_result["injection_puborjen_jo"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Yes"> Yes</label>				<input type="radio"  name="injection_puborjen_jo"   value="No"  <?php if (
            isset($select_result["injection_puborjen_jo"]) &&
            $select_result["injection_puborjen_jo"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["injection_puborjen_jo"]) &&
            $select_result["injection_puborjen_jo"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="no"> No</label>			</td>
         <td>Injection Puborjen Jo</td>
         <td>7500</td>
         <td>subcutaneous</td>
         <td>Biweekly</td>
         <td>After meals</td>
         <td>Immediately</td>
         <td>One week after embryo transfer then stop</td>
      </tr>
   </table>
   <table class="table-bordered" width="100%">
      <tr>
         <td>To take normal diet</td>
      </tr>
      <tr>
         <td>				● Continue thyroid/antihypertensive/diabetes medications as have been taking previously<br>				● To take normal diet<br>				● To report in emergency of the hospital immediately if patient has abdominal pain/vaginal bleeding/fever/excessive cough/giddiness/vomiting<br>				● SERUM BETA HCG ON <input  type="date" value="<?php echo isset(
            $select_result["serum_beta"]
            )
            ? $select_result["serum_beta"]
            : ""; ?>"  name="serum_beta" >			</td>
      </tr>
      <tr>
         <td><input  type="number" value="<?php echo isset(
            $select_result["gestational_sac_no"]
            )
            ? $select_result["gestational_sac_no"]
            : ""; ?>"  min="0" name="gestational_sac_no"> No. of gestational sac on ultrasound on <input  type="date" value="<?php echo isset(
            $select_result["gestational_sac"]
            )
            ? $select_result["gestational_sac"]
            : ""; ?>"  name="gestational_sac">.</td>
      </tr>
      <tr>
         <td><input  type="number" value="<?php echo isset(
            $select_result["cardiac_activity_no"]
            )
            ? $select_result["cardiac_activity_no"]
            : ""; ?>"  min="0" name="cardiac_activity_no"> No.of sac with cardiac activity on <input  type="date" value="<?php echo isset(
            $select_result["cardiac_activity"]
            )
            ? $select_result["cardiac_activity"]
            : ""; ?>"  name="cardiac_activity">.</td>
      </tr>
      <tr>
         <td>				Live birth on <input  type="date" value="<?php echo isset(
            $select_result["live_birth_on"]
            )
            ? $select_result["live_birth_on"]
            : ""; ?>"  name="live_birth_on"> @ <input  type="text" value="<?php echo isset(
            $select_result["live_birth_at"]
            )
            ? $select_result["live_birth_at"]
            : ""; ?>"  maxlength="20" name="live_birth_at"> by <input  type="text" value="<?php echo isset(
            $select_result["live_birth_by"]
            )
            ? $select_result["live_birth_by"]
            : ""; ?>"  maxlength="20" name="live_birth_by">.Sex of child/children:				<input type="radio"  name="sex_of_child"  value="Yes"  <?php if (
            isset($select_result["sex_of_child"]) &&
            $select_result["sex_of_child"] == "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Male"> Male</label>				<input type="radio"  name="sex_of_child"   value="No"  <?php if (
            isset($select_result["sex_of_child"]) &&
            $select_result["sex_of_child"] == "No"
            ) {
            echo 'checked="checked"';
            } elseif (
            isset($select_result["sex_of_child"]) &&
            $select_result["sex_of_child"] != "Yes"
            ) {
            echo 'checked="checked"';
            } ?>   >				<label for="Female"> Female</label>			</td>
      </tr>
   </table>
   <table class="table-bordered" width="100%">
      <tr>
         <td>DATE</td>
         <td><input  type="date" value="<?php echo isset(
            $select_result["date"]
            )
            ? $select_result["date"]
            : ""; ?>"  name="date" class="form-control" ></td>
         <td>TIME</td>
         <td><input  type="time" value="<?php echo isset(
            $select_result["time"]
            )
            ? $select_result["time"]
            : ""; ?>"  name="time" class="form-control" ></td>
         <td>Doctor Name</td>
         <td><input  type="text" value="<?php echo isset(
            $select_result["doctor_name"]
            )
            ? $select_result["doctor_name"]
            : ""; ?>"  name="doctor_name" maxlength="20" class="form-control" ></td>
         <td>Doctor Signature</td>
         <td><input  type="text" value="<?php echo isset(
            $select_result["doctor_signature"]
            )
            ? $select_result["doctor_signature"]
            : ""; ?>"  name="doctor_signature" maxlength="20" class="form-control" ></td>
      </tr>
   </table>
   <!-- /.card-body -->	
   <div class="card-footer">
      <!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->		<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">	
   </div>
</form>
<!--                             Print       TABLE                         -->																																								<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable" onclick="printtable();">	
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
   <table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">
      <table class="table-bordered" width="100%">
	  <tr>
                <th width="50%" colspan="3" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></th>
				<th width="50%" colspan="3" style="border:1px solid #cdcdcd;"><center><h3>POST EMBRYO TRANSFER</h3></center></th>
				
			</tr>
         <tr>
            <td colspan="2" style="border:1px solid #cdcdcd;">
               
               <br>				
               <li>  Embryo transfer done under all aseptic precautions</li>
               <li> To take normal diet </li>
            </td>
            <td colspan="2" style="border:1px solid #cdcdcd;">
               <?php if (
                  isset($select_result["updated_by"]) &&
                  !empty($select_result["updated_by"]) &&
                  isset($select_result["updated_at"]) &&
                  !empty($select_result["updated_at"]) &&
                  isset($select_result["updated_type"]) &&
                  !empty($select_result["updated_type"])
                  ) { ?>			        
               <p id="last_updated">Last updated on <?php echo $select_result[
                  "updated_at"
                  ]; ?> by <?php echo last_updated_user(
                  $select_result["updated_type"],
                  $select_result["updated_by"]
                  ); ?></p>
               <?php } ?>			
            </td>
         </tr>
         <tr>
            <td  width="100%" colspan="6" style="border:1px solid #cdcdcd;">Treatment Advised</td>
         </tr>
		
           <tr style="height: 40px;">
            <td width="50%" colspan="3" style="border:1px solid #cdcdcd;">UHID : <?php 
	        $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' limit 1 "; 
			   $query = $this->db->query($sql1);
                  $select_result1 = $query->result(); 
					foreach ($select_result1 as $res_val2){
						$sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$res_val2->wife_phone."' and paitent_type='new_patient'"; 
			            $query = $this->db->query($sql2);
                        $select_result2 = $query->result();
						  foreach ($select_result2 as $res_val){
						?>
	  <?php if($res_val->appoitment_for == '16249589462327'){?>
	  <?php if($res_val->uhid){ ?>001/<?php echo $res_val->uhid; } ?>
	  <?php } if($res_val->appoitment_for == '16266778858144'){?>
	  <?php if($res_val->uhid){ ?>002/<?php echo $res_val->uhid; } ?>
	  <?php } if($res_val->appoitment_for == '16267558222750'){?>
	  <?php if($res_val->uhid){ ?>003/<?php echo $res_val->uhid; } ?>
	  <?php }  if($res_val->appoitment_for == '16098223739590') {?>
	  <?php if($res_val->uhid){ ?>004/<?php echo $res_val->uhid; } ?>
	  <?php } if($res_val->appoitment_for == '16133769691598'){?>
	  <?php if($res_val->uhid){ ?>005/<?php echo $res_val->uhid;} ?>
						<?php }}} ?></td>

              <td width="50%" colspan="3" style="border:1px solid #cdcdcd;">IIC ID : <?php echo $patient_id; ?></td>

            </tr>

      </table>
      <table class="table-bordered" width="100%">
         <tr>
            <td style="width: 5%; border:1px solid #cdcdcd;" ></td>
            <td style="border:1px solid #cdcdcd;">Medication</td>
            <td style="border:1px solid #cdcdcd;">Dosage</td>
            <td style="border:1px solid #cdcdcd;">Route</td>
            <td style="border:1px solid #cdcdcd;">Times</td>
            <td style="border:1px solid #cdcdcd;">Timings</td>
            <td style="border:1px solid #cdcdcd;">When to start</td>
            <td style="border:1px solid #cdcdcd;">How many days</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">															  <?php if (
               isset($select_result["tab_taxim_o"]) &&
               $select_result["tab_taxim_o"] == "Yes"
               ) {
               echo "yes";
               } ?>																											</td>
            <td style="border:1px solid #cdcdcd;">Tab Taxim O</td>
            <td style="border:1px solid #cdcdcd;">200mg</td>
            <td style="border:1px solid #cdcdcd;">Oral</td>
            <td style="border:1px solid #cdcdcd;">Twice daily</td>
            <td style="border:1px solid #cdcdcd;">After meals</td>
            <td style="border:1px solid #cdcdcd;">Immediately</td>
            <td style="border:1px solid #cdcdcd;">one day</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">																  <?php if (
               isset($select_result["cap_pantoprazole"]) &&
               $select_result["cap_pantoprazole"] == "Yes"
               ) {
               echo "yes";
               } ?>							</td>
            <td style="border:1px solid #cdcdcd;">Cap Pantoprazole</td>
            <td style="border:1px solid #cdcdcd;">40mg</td>
            <td style="border:1px solid #cdcdcd;">Oral</td>
            <td style="border:1px solid #cdcdcd;">Once daily</td>
            <td style="border:1px solid #cdcdcd;">Empty stomach</td>
            <td style="border:1px solid #cdcdcd;">SOS</td>
            <td style="border:1px solid #cdcdcd;">If gastritis</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">			         <?php if (
               isset($select_result["tab_crocin"]) &&
               $select_result["tab_crocin"] == "Yes"
               ) {
               echo "yes";
               } ?>			</td>
            <td style="border:1px solid #cdcdcd;">Tab Crocin</td>
            <td style="border:1px solid #cdcdcd;">500mg</td>
            <td style="border:1px solid #cdcdcd;">Oral</td>
            <td style="border:1px solid #cdcdcd;">SOS Maximum three times(if )</td>
            <td style="border:1px solid #cdcdcd;">After meals</td>
            <td style="border:1px solid #cdcdcd;">If pain</td>
            <td style="border:1px solid #cdcdcd;">If </td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">																<?php if (
               isset($select_result["syrup_cremaffin"]) &&
               $select_result["syrup_cremaffin"] == "Yes"
               ) {
               echo "yes";
               } ?>											</td>
            <td style="border:1px solid #cdcdcd;">Syrup cremaffin</td>
            <td style="border:1px solid #cdcdcd;"></td>
            <td style="border:1px solid #cdcdcd;">Oral</td>
            <td style="border:1px solid #cdcdcd;">One tsf</td>
            <td style="border:1px solid #cdcdcd;">After dinner</td>
            <td style="border:1px solid #cdcdcd;">SOS(if constipation</td>
            <td style="border:1px solid #cdcdcd;"></td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">																	 <?php if (
               isset($select_result["tab_estrabet"]) &&
               $select_result["tab_estrabet"] == "Yes"
               ) {
               echo "yes";
               } ?>																							</td>
            <td style="border:1px solid #cdcdcd;">Tab Estrabet</td>
            <td style="border:1px solid #cdcdcd;">2mg</td>
            <td style="border:1px solid #cdcdcd;">Oral</td>
            <td style="border:1px solid #cdcdcd;">Twice/thrice/four times daily</td>
            <td style="border:1px solid #cdcdcd;">After meals</td>
            <td style="border:1px solid #cdcdcd;">Immediately</td>
            <td style="border:1px solid #cdcdcd;">sixteen days</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">				          	 <?php if (
               isset($select_result["tab_ecosprin"]) &&
               $select_result["tab_ecosprin"] == "Yes"
               ) {
               echo "yes";
               } ?>			</td>
            <td style="border:1px solid #cdcdcd;">Tab Ecosprin</td>
            <td style="border:1px solid #cdcdcd;">75 mg</td>
            <td style="border:1px solid #cdcdcd;">Oral</td>
            <td style="border:1px solid #cdcdcd;">Once daily</td>
            <td style="border:1px solid #cdcdcd;">After meals</td>
            <td style="border:1px solid #cdcdcd;">Immediately</td>
            <td style="border:1px solid #cdcdcd;" >sixteen days</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">												 <?php if (
               isset($select_result["injection_gestone"]) &&
               $select_result["injection_gestone"] == "Yes"
               ) {
               echo "yes";
               } ?>											</td>
            <td style="border:1px solid #cdcdcd;">Injection Gestone</td>
            <td style="border:1px solid #cdcdcd;">100 mg</td>
            <td style="border:1px solid #cdcdcd;">intramuscular</td>
            <td style="border:1px solid #cdcdcd;">Once daily</td>
            <td style="border:1px solid #cdcdcd;">After meals</td>
            <td style="border:1px solid #cdcdcd;">Immediately</td>
            <td style="border:1px solid #cdcdcd;">sixteen days</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">													 <?php if (
               isset($select_result["tab_wysolone"]) &&
               $select_result["tab_wysolone"] == "Yes"
               ) {
               echo "yes";
               } ?>							</td>
            <td style="border:1px solid #cdcdcd;">Tab wysolone/omnacortil</td>
            <td style="border:1px solid #cdcdcd;">5mg</td>
            <td style="border:1px solid #cdcdcd;">Oral</td>
            <td style="border:1px solid #cdcdcd;">Once daily</td>
            <td style="border:1px solid #cdcdcd;">After meals</td>
            <td style="border:1px solid #cdcdcd;">Immediately</td>
            <td style="border:1px solid #cdcdcd;">sixteen days</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">								 <?php if (
               isset($select_result["gestone_gel"]) &&
               $select_result["gestone_gel"] == "Yes"
               ) {
               echo "yes";
               } ?>											</td>
            <td style="border:1px solid #cdcdcd;">Crinone/gestone gel</td>
            <td style="border:1px solid #cdcdcd;">8%</td>
            <td style="border:1px solid #cdcdcd;">vaginal</td>
            <td style="border:1px solid #cdcdcd;">Once daily</td>
            <td style="border:1px solid #cdcdcd;">Before going to sleep</td>
            <td style="border:1px solid #cdcdcd;">Immediately</td>
            <td style="border:1px solid #cdcdcd;">sixteen days</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">												 <?php if (
               isset($select_result["tab_duphaston"]) &&
               $select_result["tab_duphaston"] == "Yes"
               ) {
               echo "yes";
               } ?>											</td>
            <td style="border:1px solid #cdcdcd;">Tablet Duphaston</td>
            <td style="border:1px solid #cdcdcd;">10 mg</td>
            <td style="border:1px solid #cdcdcd;">Oral</td>
            <td style="border:1px solid #cdcdcd;">Once daily</td>
            <td style="border:1px solid #cdcdcd;">After meals</td>
            <td style="border:1px solid #cdcdcd;">Immediately</td>
            <td style="border:1px solid #cdcdcd;">sixteen days</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">												 <?php if (
               isset($select_result["cap_lycopene"]) &&
               $select_result["cap_lycopene"] == "Yes"
               ) {
               echo "yes";
               } ?>															</td>
            <td style="border:1px solid #cdcdcd;">Cap Lycopene</td>
            <td style="border:1px solid #cdcdcd;"></td>
            <td style="border:1px solid #cdcdcd;">Oral</td>
            <td style="border:1px solid #cdcdcd;">Once daily</td>
            <td style="border:1px solid #cdcdcd;">After meals</td>
            <td style="border:1px solid #cdcdcd;">Immediately</td>
            <td style="border:1px solid #cdcdcd;">sixteen days</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">												 <?php if (
               isset($select_result["injection_clexane"]) &&
               $select_result["injection_clexane"] == "Yes"
               ) {
               echo "yes";
               } ?>							</td>
            <td style="border:1px solid #cdcdcd;">Injection clexane</td>
            <td style="border:1px solid #cdcdcd;">40mg</td>
            <td style="border:1px solid #cdcdcd;">subcutaneous</td>
            <td style="border:1px solid #cdcdcd;">Once daily/alternate day</td>
            <td style="border:1px solid #cdcdcd;">After meals</td>
            <td style="border:1px solid #cdcdcd;">Immediately</td>
            <td style="border:1px solid #cdcdcd;">sixteen days</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">							 <?php if (
               isset($select_result["injection_puborjen_jo"]) &&
               $select_result["injection_puborjen_jo"] == "Yes"
               ) {
               echo "yes";
               } ?>									</td>
            <td style="border:1px solid #cdcdcd;">Injection Puborjen Jo</td>
            <td style="border:1px solid #cdcdcd;">7500</td>
            <td style="border:1px solid #cdcdcd;">subcutaneous</td>
            <td style="border:1px solid #cdcdcd;">Biweekly</td>
            <td style="border:1px solid #cdcdcd;">After meals</td>
            <td style="border:1px solid #cdcdcd;">Immediately</td>
            <td style="border:1px solid #cdcdcd;">One week after embryo transfer then stop</td>
         </tr>
      </table>
      <table class="table-bordered" width="100%">
         <tr>
            <td style="border:1px solid #cdcdcd;">To take normal diet</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">
               <li>  Continue thyroid/antihypertensive/diabetes medications as have been taking previously</li>
               <li> To take normal diet</li>
               <li> To report in emergency of the hospital immediately if patient has abdominal pain/vaginal bleeding/fever/excessive cough/giddiness/vomiting</li>
               <li> SERUM BETA HCG ON <?php echo isset(
                  $select_result["serum_beta"]
                  )
                  ? $select_result["serum_beta"]
                  : ""; ?> </li>
            </td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;"><?php echo isset(
               $select_result["gestational_sac_no"]
               )
               ? $select_result["gestational_sac_no"]
               : ""; ?> No. of gestational sac on ultrasound on <?php echo isset(
               $select_result["gestational_sac"]
               )
               ? $select_result["gestational_sac"]
               : ""; ?>.</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;"><?php echo isset(
               $select_result["cardiac_activity_no"]
               )
               ? $select_result["cardiac_activity_no"]
               : ""; ?> No.of sac with cardiac activity on <?php echo isset(
               $select_result["cardiac_activity"]
               )
               ? $select_result["cardiac_activity"]
               : ""; ?>.</td>
         </tr>
         <tr>
            <td style="border:1px solid #cdcdcd;">				Live birth on <?php echo isset(
               $select_result["live_birth_on"]
               )
               ? $select_result["live_birth_on"]
               : ""; ?> @ <?php echo isset($select_result["live_birth_at"])
               ? $select_result["live_birth_at"]
               : ""; ?> by <?php echo isset($select_result["live_birth_by"])
               ? $select_result["live_birth_by"]
               : ""; ?>.Sex of child/children:				<?php
               if (
                   isset($select_result["sex_of_child"]) &&
                   $select_result["sex_of_child"] == "Yes"
               ) {
                   echo "Male";
               }
               if (
                   isset($select_result["sex_of_child"]) &&
                   $select_result["sex_of_child"] == "No"
               ) {
                   echo "Female";
               }
               ?>			</td>
         </tr>
      </table>
      <br>	
      <table class="table-bordered" width="100%">
         <tr>
            <td style="border:1px solid #cdcdcd;">DATE</td>
            <td style="border:1px solid #cdcdcd; width:10%;"><?php echo isset(
               $select_result["date"]
               )
               ? $select_result["date"]
               : ""; ?></td>
            <td style="border:1px solid #cdcdcd;">TIME</td>
            <td style="border:1px solid #cdcdcd; width:10%;"><?php echo isset(
               $select_result["time"]
               )
               ? $select_result["time"]
               : ""; ?></td>
            <td style="border:1px solid #cdcdcd; ">Doctor Name</td>
            <td style="border:1px solid #cdcdcd; width:10%;"><?php echo isset(
               $select_result["doctor_name"]
               )
               ? $select_result["doctor_name"]
               : ""; ?></td>
            <td style="border:1px solid #cdcdcd;">Doctor Signature</td>
            <td style="border:1px solid #cdcdcd; width:10%;"><?php echo isset(
               $select_result["doctor_signature"]
               )
               ? $select_result["doctor_signature"]
               : ""; ?></td>
         </tr>
      </table>
      <!-- Main Table END -->
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