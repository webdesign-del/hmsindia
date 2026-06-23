<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        $select_query = "SELECT * FROM `hms_iui_stimulation` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_iui_stimulation` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_iui_stimulation SET ";
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".$value."'"    ;
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
    $select_query = "SELECT * FROM `hms_iui_stimulation` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
  <input type="hidden" value="First Cycle" name="type"> 
    <div class="container1 red-field form mt-5 mb-5">
        <ul class="d-flex mb-1 mt-2 list-unstyled">
        <div class="table-responsive">
             <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">IUI STIMULATION</h3></td>
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
            <table class="table table-bordered table-hover mt-2 table-sm tableMg">
                <thead>
                    <tr>
                        <th style="color: red;"><h2>SELF CYCLE  (S)</h2></th>
                    </tr>
                </thead>
            </table>
                <ul class="d-flex mb-1 mt-2 list-unstyled">
                <div class = "table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                        <th style="color: red;"><strong>Partners name</strong></th>
                        <th><input type="text" value="<?php echo isset($select_result['partners_name2'])?$select_result['partners_name2']:""; ?>" maxlength="20" class="form" name="partners_name2"></th>
                        </tr>
                    </thead>
                  <thead>
                    <tr>
                      <th style="color: red;"><strong>ID</strong></th>
                      <th><input type="text" value="<?php echo isset($select_result['form_id2'])?$select_result['form_id2']:""; ?>" maxlength="20" class="form" name="form_id2"></th>
                    </tr>
                  </thead>
            </table>
            </div>
        </ul>
        <table class="table table-bordered table-hover  table-sm red-field tableMg">
            <thead>
                <tr>
                    <td>LAST MENSTRUAL PERIOD</td>
                    <td><input type="date" class="form" value="<?php echo isset($select_result['last_menstrual_period2'])?$select_result['last_menstrual_period2']:""; ?>" name="last_menstrual_period2"></td>
                </tr>
            </thead>
        </table>
        <div class = "table-responsive">
        <table class="table table-bordered table-hover  table-sm red-field tableMg">
            <thead>
                        <tr>
                        <th><strong>Day of Stimulation</strong></th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        </tr>
                    </thead>
                <tbody>
                    <tr>
                    <td>DATE</td>
                    <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['date2_'.$i])) {$value = $select_result['date2_'.$i]; }
                        echo '<td><input type="date" class="form" value="'.$value.'" name="date2_'.$i.'"></td>';
                        }
                        ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td>FOLLICLE SIZE RT (cm)</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['follicle_size_rt2_'.$i])) {$value = $select_result['follicle_size_rt2_'.$i]; }
                        echo '<td><input type="text" min="0" class="form" value="'.$value.'" name="follicle_size_rt2_'.$i.'"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                    <td>FOLLICLE SIZE LT (cm)</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['follicle_size_lt2_'.$i])) {$value = $select_result['follicle_size_lt2_'.$i]; }
                        echo '<td><input type="text" min="0" class="form" value="'.$value.'" name="follicle_size_lt2_'.$i.'"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                        <td>ENDOMETRIAL THICKNESS (cm)</td>
                        <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['endometrial_thickness2_'.$i])) {$value = $select_result['endometrial_thickness2_'.$i]; }
                            echo '<td><input type="text" min="0" class="form" value="'.$value.'" name="endometrial_thickness2_'.$i.'"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                        <td>INJECTION DOSE: FSH</td>
                        <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['inf_fsh_'.$i])) {$value = $select_result['inf_fsh_'.$i]; }
                            echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="inf_fsh_'.$i.'"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td>HMG</td>
                    <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['hmg_'.$i])) {$value = $select_result['hmg_'.$i]; }
                        echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="hmg_'.$i.'"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td>ANTAGONIST</td>
                    <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['antagonist_'.$i])) {$value = $select_result['antagonist_'.$i]; }
                        echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="antagonist_'.$i.'"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                    <td>HCG(TRIGGER)</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['hcg2_'.$i])) {$value = $select_result['hcg2_'.$i]; }
                        echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="hcg2_'.$i.'"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td>MEDICINE ADDED</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['medicine_added2_'.$i])) {$value = $select_result['medicine_added2_'.$i]; }
                        echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="medicine_added2_'.$i.'"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td>REMARKS</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['remarks2_'.$i])) {$value = $select_result['remarks2_'.$i]; }
                        echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="remarks2_'.$i.'"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                    <td>FOLLOWUP ON</td>
                    <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['follow_up2_'.$i])) {$value = $select_result['follow_up2_'.$i]; }
                        echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="follow_up2_'.$i.'"></td>';
                        }
                      ?>
                      
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                    <td>SERUM ESTRADIOL (E2) LEVEL</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['estradoil2_'.$i])) {$value = $select_result['estradoil2_'.$i]; }
                        echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="estradoil2_'.$i.'"></td>';
                        }
                      ?>
                      
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td>SERUM PROGESTERONE LEVEL</td>
                      <?php for($i=1;$i<=4;$i++){
                            $value=""; if(isset($select_result['progesterone2_'.$i])) {$value = $select_result['progesterone2_'.$i]; }
                        echo '<td><input type="text" maxlength="20" class="form" value="'.$value.'" name="progesterone2_'.$i.'"></td>';
                        }
                      ?>
                    </tr>
                  </tbody>
        </table>
        </div>
        <table class="table table-bordered table-hover  table-sm red-field tableMg">
            <thead>
                <tr>
                    <th>DOCTOR <input type="text" value="<?php echo isset($select_result['doctor2'])?$select_result['doctor2']:""; ?>" class="form" name="doctor2"></th>
                    <th>COUNSELLOR <input type="text" value="<?php echo isset($select_result['counsellor2'])?$select_result['counsellor2']:""; ?>" class="form" name="counsellor2"></th>
                    <th>NURSE <input type="text" value="<?php echo isset($select_result['nurse2'])?$select_result['nurse2']:""; ?>" class="form" name="nurse2"></th>
                </tr>
            </thead>
        </table>
                 
            </div>
        </ul>
     
                    <!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
                    <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
</form>

















<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none">  

    <div class="container red-field form mt-5 mb-5">
        <div class="table-responsive">
         <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">IUI STIMULATION</h3></td>
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
		<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="border:1px solid #cdcdcd;">
            <thead>
		      <tr>
                   <td colspan="4" style="border:1px solid #cdcdcd;">
        			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
        			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
        			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
        			            ){?>
        			        <p id="last_updated"> Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
        			    <?php } ?>
        			</td>
                </tr>
            </thead>
            <table class="table table-bordered table-hover mt-2 table-sm tableMg" style="width:100%;border:1px solid #cdcdcd;">
                <thead>
                    <tr>
                        <th colspan="2" width="50%" style="border:1px solid #cdcdcd;"><h2>SELF CYCLE  (S)</h2></th>
                        <th colspan="2" style="border:1px solid #cdcdcd;" ><h2>DONOR CYCLE (D)</h2></th>
                    </tr>
                </thead>
            </table>
             
               
                    <table class="table table-bordered table-hover table-sm" style="width:100%; border:1px solid #cdcdcd;" >
                    <thead>
                        <tr>
                        <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"><strong>Partners name</strong></th>
                        <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['partners_name2'])?$select_result['partners_name2']:""; ?> </th>
                        <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"><strong>ART bank reg no</strong></th>
                        <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['art_bank_reg_no2'])?$select_result['art_bank_reg_no2']:""; ?> </th>
                        </tr>
                    </thead>
                  <thead>
				    <tr>
                      <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"><strong>ID</strong></th>
                      <th colspan="1" width="25%" style="border:1px solid #cdcdcd;">  <?php echo isset($select_result['form_id2'])?$select_result['form_id2']:""; ?>  </th>
                      <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"><strong>Donor ID</strong></th>
                      <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['donor_d2'])?$select_result['donor_d2']:""; ?> </th>
                    </tr>
                  </thead>
            </table>
          
     
		
		
        <table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
            <thead>
                <tr>
                    <td colspan="2" width="50%" style="border:1px solid #cdcdcd;" >LAST MENSTRUAL PERIOD</td>
                    <td colspan="2" width="50%" style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['last_menstrual_period2'])?$select_result['last_menstrual_period2']:""; ?> </td>
                </tr>
            </thead>
        </table>
        <div class = "table-responsive">
        <table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
            <thead>
                        <tr>
                        <th style="border:1px solid #cdcdcd;"><strong>Day of Stimulation</strong></th>
                        <th style="border:1px solid #cdcdcd;">1</th>
                        <th style="border:1px solid #cdcdcd;">2</th>
                        <th style="border:1px solid #cdcdcd;">3</th>
                        <th style="border:1px solid #cdcdcd;">4</th>
                        <th style="border:1px solid #cdcdcd;">5</th>
                        <th style="border:1px solid #cdcdcd;">6</th>
                        <th style="border:1px solid #cdcdcd;">7</th>
                        <th style="border:1px solid #cdcdcd;">8</th>
                        <th style="border:1px solid #cdcdcd;">9</th>
                        <th style="border:1px solid #cdcdcd;">10</th>
                        <th style="border:1px solid #cdcdcd;">11</th>
                        <th style="border:1px solid #cdcdcd;">12</th>
                        <th style="border:1px solid #cdcdcd;">13</th>
                        <th style="border:1px solid #cdcdcd;">14</th>
                        <th style="border:1px solid #cdcdcd;">15</th>
                        </tr>
                    </thead>
                <tbody>
                    <tr>
                    <td style="border:1px solid #cdcdcd;">DATE</td>
                    <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['date2_'.$i])) {$value = $select_result['date2_'.$i]; } ?>  
                       
						<td style="border:1px solid #cdcdcd;">  
						
						<?php echo $value; ?>   
						</td>
                      <?php  }  ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td style="border:1px solid #cdcdcd;" >FOLLICLE SIZE RT (cm)</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['follicle_size_rt2_'.$i])) {$value = $select_result['follicle_size_rt2_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;">  
						
						<?php echo $value; ?>  
						</td>
                      <?php  }  ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                    <td style="border:1px solid #cdcdcd;" >FOLLICLE SIZE LT (cm)</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['follicle_size_lt2_'.$i])) {$value = $select_result['follicle_size_lt2_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;">  
						
						<?php echo $value; ?>  
						</td>
                      <?php  }  ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                        <td style="border:1px solid #cdcdcd;" >ENDOMETRIAL THICKNESS (cm)</td>
                        <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['endometrial_thickness2_'.$i])) {$value = $select_result['endometrial_thickness2_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;"> 
						
						<?php echo $value; ?>  
						 </td>
                      <?php  }  ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                        <td style="border:1px solid #cdcdcd;" >INJECTION DOSE: FSH</td>
                        <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['inf_fsh_'.$i])) {$value = $select_result['inf_fsh_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;">  
						
						<?php echo $value; ?>   
						</td>
                      <?php  }  ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td style="border:1px solid #cdcdcd;">HMG</td>
                    <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['hmg_'.$i])) {$value = $select_result['hmg_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;">  
						
						<?php echo $value; ?>   
						</td>
                      <?php  }  ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td style="border:1px solid #cdcdcd;">ANTAGONIST</td>
                    <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['antagonist_'.$i])) {$value = $select_result['antagonist_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;"> 
						
						<?php echo $value; ?>   
						 </td>
                      <?php  }  ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                    <td style="border:1px solid #cdcdcd;">HCG(TRIGGER)</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['hcg2_'.$i])) {$value = $select_result['hcg2_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;">  
						
						<?php echo $value; ?>   
						</td>
                      <?php  }  ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td style="border:1px solid #cdcdcd;">MEDICINE ADDED</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['medicine_added2_'.$i])) {$value = $select_result['medicine_added2_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;">  
						
						<?php echo $value; ?>  
						</td>
                      <?php  }  ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td style="border:1px solid #cdcdcd;">REMARKS</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['remarks2_'.$i])) {$value = $select_result['remarks2_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;">  
						
						<?php echo $value; ?>  
						</td>
                      <?php  }  ?>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                    <td style="border:1px solid #cdcdcd;">FOLLOWUP ON</td>
                    <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['follow_up2_'.$i])) {$value = $select_result['follow_up2_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;"> 
						
						<?php echo $value; ?>   
						 </td>
                      <?php  }  ?>
                      
                    </tr>
                  </tbody>
                  <tbody style="border:1px solid #cdcdcd;">
                    <tr>
                    <td style="border:1px solid #cdcdcd;">SERUM ESTRADIOL (E2) LEVEL</td>
                      <?php for($i=1;$i<=15;$i++){
                            $value=""; if(isset($select_result['estradoil2_'.$i])) {$value = $select_result['estradoil2_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;">  
						
						<?php echo $value; ?>   
						</td>
                      <?php  }  ?>
                      
                    </tr>
                  </tbody>
                  <tbody style="border:1px solid #cdcdcd;">
                    <tr>
                      <td style="border:1px solid #cdcdcd;">SERUM PROGESTERONE LEVEL</td>
                      <?php for($i=1;$i<=7;$i++){
                            $value=""; if(isset($select_result['progesterone2_'.$i])) {$value = $select_result['progesterone2_'.$i]; }?>  
                       
						<td style="border:1px solid #cdcdcd;">  
						
						<?php echo $value; ?>   
						</td>
                      <?php  }  ?>
                     
                    </tr>
                  </tbody>
        </table>
        </div>
		
		
		
        <table class="table table-bordered table-hover  table-sm red-field tableMg" style="border:1px solid #cdcdcd;">
            <thead>
                <tr>
                    <th style="border:1px solid #cdcdcd;">DOCTOR <?php echo isset($select_result['doctor2'])?$select_result['doctor2']:""; ?> </th>
                    <th style="border:1px solid #cdcdcd;">COUNSELLOR <?php echo isset($select_result['counsellor2'])?$select_result['counsellor2']:""; ?> </th>
                    <th style="border:1px solid #cdcdcd;">NURSE <?php echo isset($select_result['nurse2'])?$select_result['nurse2']:""; ?> </th>
                </tr>
            </thead>
        </table>
                  <!--<table class="table table-bordered table-hover table-sm" style="border:1px solid #cdcdcd;">
                    <thead>
                      <tr style="border:1px solid #cdcdcd;">
                        <td style="border:1px solid #cdcdcd;" ><strong>INTRAUTERINE INSEMINATION</strong></td>
                        <td style="border:1px solid #cdcdcd;" >Date <?php echo isset($select_result['date'])?$select_result['date']:""; ?> </td>
                        <td style="border:1px solid #cdcdcd;">Time <?php echo isset($select_result['time'])?$select_result['time']:""; ?> </td>
                        <td style="border:1px solid #cdcdcd;">Indication  <?php echo isset($select_result['indication'])?$select_result['indication']:""; ?> </td>
						<td style="border:1px solid #cdcdcd;"> ALLERGIES <?php echo isset($select_result['allergy'])?$select_result['allergy']:""; ?> </td>
						<td style="border:1px solid #cdcdcd;">Consent<br>
						<?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'yes'; }?>
						</td>
                        <td style="border:1px solid #cdcdcd;">ID checked<br>                           
						<?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'yes'; }?>
                        </td>
                      </tr>
                      <tr>
                        <td style="border:1px solid #cdcdcd;"><strong>PRE ASSESSMENT</strong></td>
                        <td style="border:1px solid #cdcdcd;"> BP  <?php echo isset($select_result['bp'])?$select_result['bp']:""; ?> </td>
                        <td style="border:1px solid #cdcdcd;">PULSE <br>                           
						<?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'yes'; }?>	
						</td>
                        <td style="border:1px solid #cdcdcd;">RESP<br>
                        <?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'yes'; }?>
						</td>
                        <td style="border:1px solid #cdcdcd;">voided<br>                                                  
						<?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'yes'; }?>
						</td>
						<td style="border:1px solid #cdcdcd;">HT (Cms)<?php echo isset($select_result['ht'])?$select_result['ht']:""; ?> </td>
						<td  style="border:1px solid #cdcdcd;"> WT (Kg) <?php echo isset($select_result['wt'])?$select_result['wt']:""; ?> </td>
						</tr>
						<tr>
                        <td style="border:1px solid #cdcdcd;">Glasses<br>
                        <?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'yes'; }?>
						</td>
                        <td style="border:1px solid #cdcdcd;">Contacts<br>
						<?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'yes'; }?>
						</td>
                        <td style="border:1px solid #cdcdcd;">Denture<br>                                                  
						<?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'yes'; }?>
						</td>
                        <td colspan="2" style="border:1px solid #cdcdcd;">Dental bridge<br>                                  
						<?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'yes'; }?>
						</td>
						<td style="border:1px solid #cdcdcd;">Valuables with escort<br>
                        <?php if(isset($select_result['valuables_with_escort']) && $select_result['valuables_with_escort'] == "Yes"){echo 'yes'; }?>
					  	</td>
						<td style="border:1px solid #cdcdcd;">Last meal <?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?> </td>
						</tr>
						<tr style="color: red; border:1px solid #cdcdcd;">
                        <th colspan="7" style="border:1px solid #cdcdcd;">
                          <strong> PRE ASSESSMENT </strong>
                        </th>
						</tr>
						<tr style="color: red; border:1px solid #cdcdcd;" >
                        <td colspan="7">
                            No active vaginal infection<br>
                            Other <?php echo isset($select_result['pre_assessment_other'])?$select_result['pre_assessment_other']:""; ?>
                        </td>
						</tr>
                    </thead>
                  </table>-->
            </div>
        <div class = "table-responsive">
        <table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;">
            <thead>
                <tr>
  <th style="border:1px solid #cdcdcd;"> NURSE <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?> </th>
  <th style="border:1px solid #cdcdcd;"> DOCTOR  <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?> </th>
 <th style="border:1px solid #cdcdcd;">Embryologist <?php echo isset($select_result['embryologist'])?$select_result['embryologist']:""; ?> </th>
                </tr>
            </thead>
        </table>
        <table class="table table-bordered table-hover  table-sm red-field tableMg" style="width:100%;border:1px solid #cdcdcd;">
            <thead>
                <tr>
                    <th style="border:1px solid #cdcdcd;"><ol >
  <li class="d-flex mb-1 mt-2 "> <span> Physical Examination </span></li> 
 <li class="d-flex mb-1 ml-4"><span class= "mr-5 ">Resp </span> <?php echo isset($select_result['Resp2'])?$select_result['Resp2']:""; ?> </li> 
<li class="d-flex mb-1 ml-4"><span class= "mr-5 ml-1 "> CVS </span> <?php echo isset($select_result['CVS'])?$select_result['CVS']:""; ?> </li> 
 <li class="d-flex mb-1 ml-4"><span class= "mr-5 ml-1 ">CNS</span> <?php echo isset($select_result['CNS'])?$select_result['CNS']:""; ?></li>
 <li class="d-flex mb-1"><span class= "mr-4 ">Abdominal</span> <?php echo isset($select_result['abdominal'])?$select_result['abdominal']:""; ?></li>
  <li class="d-flex mb-1 ml-2"><span class= "mr-5 ">Others</span> <?php echo isset($select_result['others'])?$select_result['others']:""; ?></li>
                        </ul>
                    </th>
                    <th style="border:1px solid #cdcdcd;">
                        <p>
                            Written informed consent taken. All vitals checked under normal range. Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by normal saline  and draped.A baseline transabdominal ultrasound performed to see endometrium and ovaries. Following baseline scan , a sterile Cuscos speculum /Sims speculum with tenaculum introduced .The cervix cleansed with normal saline .An IUI catheter with 0.5 ml of prepared sperm sample  put in the uterine cavity .The speculum is taken out . No complications seen. Patient stood the procedure well.Till20 mins patient made to lie down
                        </p>
                        <!-- <p>Written informed consent taken . All vitals checked under normal range.  15 ml of venous blood was drawn from  Arthrex  Double syringe system ,  centrifuged by ACP centrifuge system for 10 min.  
                            Patient put in lithotomy position ,under all sterile conditions, the vulva and vagina were cleansed by betadine and draped. A condom with lubricated jelly is put on the vaginal ultrasound probe,it is introduced transvaginally  ,a baseline transvaginal ultrasound performed to see endometrium and ovaries. Following baseline scan , a sterile Cuscos speculum /Sims speculum with tenaculum introduced .The cervix cleansed with betadine.An IUI catheter with 0.5 ml of prepared PRP(supernatant pellet ) put in the uterine cavity .The speculum is taken out .
                            No complications seen. Patient stood the procedure well.Till20 mins patient made to lie down </p> -->
                    </th>
                </tr>
            </thead>
        </table>
      



	  <table style="width:100%; border:1px solid #cdcdcd;">
            <thead>
                <tr> <th colspan="2" style="border:1px solid #cdcdcd;" >Post Procedure orders</th></tr>
                <tr>
                    <td style="border:1px solid #cdcdcd;">
                        
                  <?php if(isset($select_result['normal_diet']) && $select_result['normal_diet'] == "Yes"){echo 'yes'; }?>
   
  	            
				 </td>
                    <td style="border:1px solid #cdcdcd;">Normal diet</td>
                </tr>
                <tr>
                    <td style="border:1px solid #cdcdcd;">
                        
                  
 <?php if(isset($select_result['tab_estrabet']) && $select_result['tab_estrabet'] == "Yes"){echo 'yes'; }?>
  
  	


				  </td>
                    <td style="border:1px solid #cdcdcd;">Tab estrabet 2 mg twice/ thrice/four times  daily after meals for 16 days</td>
                </tr>
                <tr>
                    <td style="border:1px solid #cdcdcd;">
                                       
 <?php if(isset($select_result['tab_duphaston']) && $select_result['tab_duphaston'] == "Yes"){echo 'yes'; }?>
  
  	

				  </td>
                    <td style="border:1px solid #cdcdcd;">Tab Duphaston 10 mg three times daily after meals for 16 days</td>
                </tr>
                <tr>
                    <td style="border:1px solid #cdcdcd;">
                            
 <?php if(isset($select_result['tab_ecosprin']) && $select_result['tab_ecosprin'] == "Yes"){echo 'yes'; }?>
  
  	

				 </td>
                    <td style="border:1px solid #cdcdcd;"> Tab ecosprin 75 mg once daily for 16 days</td>
                </tr>
                <tr>
                    <td style="border:1px solid #cdcdcd;">
                                     
 <?php if(isset($select_result['serum_beta']) && $select_result['serum_beta'] == "Yes"){echo 'yes'; }?>
   	

			 </td>
                    <td style="border:1px solid #cdcdcd;">Serum beta hcg after 15 days</td>
                </tr>
                <tr>
                    <td style="border:1px solid #cdcdcd;">
                       
  
   <?php if(isset($select_result['medications_as_advised']) && $select_result['medications_as_advised'] == "Yes"){echo 'yes'; }?>
  
  	
  
                    </td>
                    <td style="border:1px solid #cdcdcd;">Continue thyroid/antihypertensive/diabetes /other medical disorder medications as advised</td>
                </tr>
                <tr>
                    <td style="border:1px solid #cdcdcd;">
                       
 <?php if(isset($select_result['report']) && $select_result['report'] == "Yes"){echo 'yes'; }?>
   	       </td>
                    <td style="border:1px solid #cdcdcd;"> To report if giddiness /nausea/vomiting/bleeding/pain/fever /purulent discharge immediately </td>
                </tr>
            </thead> 
            <thead>
                <tr>
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