<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        $select_query = "SELECT * FROM `natural_cycle_protocol` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `natural_cycle_protocol` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE natural_cycle_protocol SET ";
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
    $select_query = "SELECT * FROM `natural_cycle_protocol` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);  
    
	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);

   // Dynamic Logo handling
    $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
    if(empty($page_logo)) {
        $page_logo = base_url('assets/center/default-logo.png');
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
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">NATURAL CYCLE PROTOCOL OI</h3></td>
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
               <!-- <th><h2>DONOR CYCLE (D)</h2></th>-->
                </tr>
                
     </thead>
    </table>
        <div class = "table-responsive">
            <table class="table table-bordered table-hover table-sm">
            <thead>
                <tr>
                <th style="color: red;"><strong>Partners name</strong></th>
                <th><input type="text" value="<?php echo isset($select_result['partners_name'])?$select_result['partners_name']:""; ?>" maxlength="20" class="form" name="partners_name"></th>
               <!-- <th><strong>ART bank reg no</strong></th>
                <th><input type="text" value="<?php echo isset($select_result['art_bank_reg_no'])?$select_result['art_bank_reg_no']:""; ?>" maxlength="20" class="form" name="art_bank_reg_no"></th>
               --> </tr>
            </thead>
          <thead>
            <tr>
              <th style="color: red;"><strong>ID</strong></th>
              <th><input type="text" value="<?php echo isset($select_result['form_id'])?$select_result['form_id']:""; ?>" maxlength="20" class="form" name="form_id"></th>
             <!-- <th><strong>Donor ID</strong></th>
              <th><input type="text" value="<?php echo isset($select_result['donor_d'])?$select_result['donor_d']:""; ?>" maxlength="20" class="form" name="donor_d"></th>
            --></tr>
          </thead>
    </table>
    <table class="table table-bordered table-hover  table-sm red-field tableMg">
        <thead>
            <tr>
                <td>LAST MENSTRUAL PERIOD</td>
                <td><input type="date" value="<?php echo isset($select_result['last_menstrual_period'])?$select_result['last_menstrual_period']:""; ?>" class="form" name="last_menstrual_period"></td>
            </tr>
        </thead>
    </table>
    <table class="table table-bordered table-hover table-sm red-field tableMg">
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
                        $value=""; if(isset($select_result['date_'.$i])) {$value = $select_result['date_'.$i]; }
                    echo '<td><input type="date" class="form" name="date_'.$i.'" value='.$value.'></td>';
                    }
                    ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td>FOLLICLE SIZE RT (cm)</td>
                  <?php for($i=1;$i<=15;$i++){
                        $value=""; if(isset($select_result['follicle_size_rt_'.$i])) {$value = $select_result['follicle_size_rt_'.$i]; }
					
					
						
                    echo '<td><input type="text" min="0"  class="form" name="follicle_size_rt_'.$i.'" value='.$value.'></td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                <td>FOLLICLE SIZE LT (cm)</td>
                  <?php for($i=1;$i<=15;$i++){
                        $value=""; if(isset($select_result['follicle_size_lt_'.$i])) {$value = $select_result['follicle_size_lt_'.$i]; }
                    echo '<td><input type="text" min="0"  class="form" name="follicle_size_lt_'.$i.'"  value='.$value.'></td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td>ENDOMETRIAL THICKNESS (cm)</td>
                <?php for($i=1;$i<=15;$i++){
                        $value=""; if(isset($select_result['endometrial_thickness_'.$i])) {$value = $select_result['endometrial_thickness_'.$i]; }
                    echo '<td><input type="text" min="0"    class="form" name="endometrial_thickness_'.$i.'" value='.$value.'> </td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                <td>HCG(TRIGGER)</td>
                  <?php for($i=1;$i<=15;$i++){
                        $value=""; if(isset($select_result['hcg_'.$i])) {$value = $select_result['hcg_'.$i]; }
                    echo '<td><input type="text" maxlength="20"  class="form" name="hcg_'.$i.'" value='.$value.'></td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td>MEDICINE ADDED</td>
                  <?php for($i=1;$i<=15;$i++){
                        $value=""; if(isset($select_result['medicine_added_'.$i])) {$value = $select_result['medicine_added_'.$i]; }
                    echo '<td><input type="text" maxlength="20"  class="form" name="medicine_added_'.$i.'" value='.$value.'></td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td>REMARKS</td>
                  <?php for($i=1;$i<=15;$i++){
                        $value=""; if(isset($select_result['remarks_'.$i])) {$value = $select_result['remarks_'.$i]; }
                    echo '<td><input type="text" maxlength="20"   class="form" name="remarks_'.$i.'" value='.$value.'></td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                <td>FOLLOWUP ON</td>
                <?php for($i=1;$i<=15;$i++){
                        $value=""; if(isset($select_result['follow_up_'.$i])) {$value = $select_result['follow_up_'.$i]; }
                    echo '<td><input type="text" maxlength="20"  class="form" name="follow_up_'.$i.'"  value='.$value.'></td>';
                    }
                  ?>
                  
                </tr>
              </tbody>
              <tbody>
                <tr>
                <td>SERUM ESTRADIOL (E2) LEVEL</td>
                  <?php for($i=1;$i<=15;$i++){
                        $value=""; if(isset($select_result['estradoil_'.$i])) {$value = $select_result['estradoil_'.$i]; }
                    echo '<td><input type="text" maxlength="20"   class="form" name="estradoil_'.$i.'"  value='.$value.'></td>';
                    }
                  ?>
                  
                </tr>
              </tbody>
              <tbody>
                <tr>
                    <td>SERUM PROGESTERONE LEVEL</td>
                    <?php for($i=1;$i<=15;$i++){
                        $value=""; if(isset($select_result['progesterone_'.$i])) {$value = $select_result['progesterone_'.$i]; } 
						
						
						
						
                        echo '<td><input type="text" maxlength="20"  class="form" name="progesterone_'.$i.'" value='.$value.'></td>';
                    }
                  ?>
                </tr>
              </tbody>
    </table>
    </div>
    <table class="table table-bordered table-hover table-sm red-field tableMg">
        <thead>
            <tr>
                <th>DOCTOR <input type="text" value="<?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?>" class="form" name="doctor"></th>
                <th>COUNSELLOR <input type="text" value="<?php echo isset($select_result['counsellor'])?$select_result['counsellor']:""; ?>" class="form" name="counsellor"></th>
                <th>NURSE <input type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>" class="form" name="nurse"></th>
            </tr>
        </thead>
    </table>
            <!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
            <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
    </div>
</form>







<!--           Print Button              -->

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none"> 





<!--          natural_cycle_protocol              -->




    <table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;" >
     <thead>
	 <tr>
                <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
			 <td width="50%" colspan="2" style="border:1px solid #cdcdcd;"><center><h2>NATURAL CYCLE  PROTOCOL OI</h2></center></td>
					
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
	   <table width="100%" class="vb45rt">
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
     </thead>
     <table class="table table-bordered table-hover mt-2 table-sm tableMg" style="width:100%; border:1px solid #cdcdcd;">
     <thead>
                <tr>
                <th width="50%" colspan="2" style="color: red;"><h2>SELF CYCLE  (S)</h2></th>
                <!--<th width="50%" colspan="2" style="border:1px solid #cdcdcd;"><h2>DONOR CYCLE (D)</h2></th>-->
                </tr>
                
     </thead>
    </table>
        
            <table class="table table-bordered table-hover table-sm" style="width:100%; border:1px solid #cdcdcd;">
            <thead>
                <tr>
                <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"><strong>Partners name</strong></th>
                <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['partners_name'])?$select_result['partners_name']:""; ?></th>
                <!--<th colspan="1" width="25%" style="border:1px solid #cdcdcd;"><strong>ART bank reg no</strong></th>
                <th colspan="1" width="25%" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['art_bank_reg_no'])?$select_result['art_bank_reg_no']:""; ?></th>
                --></tr>
            </thead>
          <thead>
            <tr>
              <th style="color: red;"><strong>ID</strong></th>
              <th  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['form_id'])?$select_result['form_id']:""; ?></th>
            <!--  <th  style="border:1px solid #cdcdcd;"><strong>Donor ID</strong></th>
              <th  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['donor_d'])?$select_result['donor_d']:""; ?></th>
            --></tr>
          </thead>
    </table>
   
    <table class="table table-bordered table-hover  table-sm red-field tableMg">
        <thead>
            <tr>
                <td  style="border:1px solid #cdcdcd;">LAST MENSTRUAL PERIOD</td>
                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['last_menstrual_period'])?$select_result['last_menstrual_period']:""; ?></td>
            </tr>
        </thead>
    </table>
 
  
	
	   <!-- # 1est  table -->
  
  <table class="table table-bordered table-hover table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;">
            <thead>
                    <tr>
                            <th  style="border:1px solid #cdcdcd;"><strong>Day of Stimulation</strong></th>
                            <th  style="border:1px solid #cdcdcd;">1</th>
                            <th  style="border:1px solid #cdcdcd;">2</th>
                            <th  style="border:1px solid #cdcdcd;">3</th>
                            <th  style="border:1px solid #cdcdcd;">4</th>
                            <th  style="border:1px solid #cdcdcd;">5</th>
                         
                    </tr>
                </thead>
              <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">DATE</td>
                <?php for($i=1;$i<=5;$i++){
                        $value=""; if(isset($select_result['date_'.$i])) {$value = $select_result['date_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                    ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">FOLLICLE SIZE RT (cm)</td>
                  <?php for($i=1;$i<=5;$i++){
                        $value=""; if(isset($select_result['follicle_size_rt_'.$i])) {$value = $select_result['follicle_size_rt_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">FOLLICLE SIZE LT (cm)</td>
                  <?php for($i=1;$i<=5;$i++){
                        $value=""; if(isset($select_result['follicle_size_lt_'.$i])) {$value = $select_result['follicle_size_lt_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;"> '.$value.' </td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">ENDOMETRIAL THICKNESS (cm)</td>
                <?php for($i=1;$i<=5;$i++){
                        $value=""; if(isset($select_result['endometrial_thickness_'.$i])) {$value = $select_result['endometrial_thickness_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">HCG(TRIGGER)</td>
                  <?php for($i=1;$i<=5;$i++){
                        $value=""; if(isset($select_result['hcg_'.$i])) {$value = $select_result['hcg_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;"> '.$value.' </td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">MEDICINE ADDED</td>
                  <?php for($i=1;$i<=5;$i++){
                        $value=""; if(isset($select_result['medicine_added_'.$i])) {$value = $select_result['medicine_added_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">REMARKS</td>
                  <?php for($i=1;$i<=5;$i++){
                        $value=""; if(isset($select_result['remarks_'.$i])) {$value = $select_result['remarks_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
              <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">FOLLOWUP ON</td>
                <?php for($i=1;$i<=5;$i++){
                        $value=""; if(isset($select_result['follow_up_'.$i])) {$value = $select_result['follow_up_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                  
                </tr>
              </tbody>
              <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">SERUM ESTRADIOL (E2) LEVEL</td>
                  <?php for($i=1;$i<=5;$i++){
                        $value=""; if(isset($select_result['estradoil_'.$i])) {$value = $select_result['estradoil_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;"> '.$value.' </td>';
                    }
                  ?>
                  
                </tr>
              </tbody>
              <tbody>
                <tr>
                    <td  style="border:1px solid #cdcdcd;">SERUM PROGESTERONE LEVEL</td>
                    <?php for($i=1;$i<=5;$i++){
                        $value=""; if(isset($select_result['progesterone_'.$i])) {$value = $select_result['progesterone_'.$i]; }
                        echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
    </table>
	
	
	
	  <!-- # 2rd table -->
  
  <table class="table table-bordered table-hover table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;">
            <thead>
                    <tr>
                            <th  style="border:1px solid #cdcdcd;"><strong>Day of Stimulation</strong></th>
                            
						
                            <th  style="border:1px solid #cdcdcd;">6</th>
                            <th  style="border:1px solid #cdcdcd;">7</th>
                            <th  style="border:1px solid #cdcdcd;">8</th>
                            <th  style="border:1px solid #cdcdcd;">9</th>
                            <th  style="border:1px solid #cdcdcd;">10</th>
						
                    </tr>
                </thead>
				
				<tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">DATE</td>
                <?php for($i=6;$i<=10;$i++){
                        $value=""; if(isset($select_result['date_'.$i])) {$value = $select_result['date_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                    ?>
                </tr>
              </tbody>
			  
			  
			   <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">FOLLICLE SIZE LT (cm)</td>
                  <?php for($i=6;$i<=10;$i++){
                        $value=""; if(isset($select_result['follicle_size_lt_'.$i])) {$value = $select_result['follicle_size_lt_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">ENDOMETRIAL THICKNESS (cm)</td>
                <?php for($i=6;$i<=10;$i++){
                        $value=""; if(isset($select_result['endometrial_thickness_'.$i])) {$value = $select_result['endometrial_thickness_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">HCG(TRIGGER)</td>
                  <?php for($i=6;$i<=10;$i++){
                        $value=""; if(isset($select_result['hcg_'.$i])) {$value = $select_result['hcg_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">MEDICINE ADDED</td>
                  <?php for($i=6;$i<=10;$i++){
                        $value=""; if(isset($select_result['medicine_added_'.$i])) {$value = $select_result['medicine_added_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
			  
			  <tbody>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">REMARKS</td>
                  <?php for($i=6;$i<=10;$i++){
                        $value=""; if(isset($select_result['remarks_'.$i])) {$value = $select_result['remarks_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">FOLLOWUP ON</td>
                <?php for($i=6;$i<=10;$i++){
                        $value=""; if(isset($select_result['follow_up_'.$i])) {$value = $select_result['follow_up_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                  
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">SERUM ESTRADIOL (E2) LEVEL</td>
                  <?php for($i=6;$i<=10;$i++){
                        $value=""; if(isset($select_result['estradoil_'.$i])) {$value = $select_result['estradoil_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                  
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                    <td  style="border:1px solid #cdcdcd;">SERUM PROGESTERONE LEVEL</td>
                    <?php for($i=6;$i<=10;$i++){
                        $value=""; if(isset($select_result['progesterone_'.$i])) {$value = $select_result['progesterone_'.$i]; }
                        echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
    </table>
	
	
	
	
<!-- # 3rd table -->

<table class="table table-bordered table-hover table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;">
            <thead>
                    <tr>
                            <th  style="border:1px solid #cdcdcd;"><strong>Day of Stimulation</strong></th>
                           							
                            <th  style="border:1px solid #cdcdcd;">11</th>                           						
							<th  style="border:1px solid #cdcdcd;">12</th>
                            <th  style="border:1px solid #cdcdcd;">13</th>
                            <th  style="border:1px solid #cdcdcd;">14</th>
                            <th  style="border:1px solid #cdcdcd;">15</th>
                    </tr>
                </thead>
				
				<tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">DATE</td>
                <?php for($i=11;$i<=15;$i++){
                        $value=""; if(isset($select_result['date_'.$i])) {$value = $select_result['date_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                    ?>
                </tr>
              </tbody>
			  
			  
			   <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">FOLLICLE SIZE LT (cm)</td>
                  <?php for($i=11;$i<=15;$i++){
                        $value=""; if(isset($select_result['follicle_size_lt_'.$i])) {$value = $select_result['follicle_size_lt_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">ENDOMETRIAL THICKNESS (cm)</td>
                <?php for($i=11;$i<=15;$i++){
                        $value=""; if(isset($select_result['endometrial_thickness_'.$i])) {$value = $select_result['endometrial_thickness_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">HCG(TRIGGER)</td>
                  <?php for($i=11;$i<=15;$i++){
                        $value=""; if(isset($select_result['hcg_'.$i])) {$value = $select_result['hcg_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">MEDICINE ADDED</td>
                  <?php for($i=11;$i<=15;$i++){
                        $value=""; if(isset($select_result['medicine_added_'.$i])) {$value = $select_result['medicine_added_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
			  
			  <tbody>
                <tr>
                  <td  style="border:1px solid #cdcdcd;">REMARKS</td>
                  <?php for($i=11;$i<=15;$i++){
                        $value=""; if(isset($select_result['remarks_'.$i])) {$value = $select_result['remarks_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">FOLLOWUP ON</td>
                <?php for($i=11;$i<=15;$i++){
                        $value=""; if(isset($select_result['follow_up_'.$i])) {$value = $select_result['follow_up_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;"><input type="text" maxlength="20" class="form" name="follow_up_'.$i.'"></td>';
                    }
                  ?>
                  
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                <td  style="border:1px solid #cdcdcd;">SERUM ESTRADIOL (E2) LEVEL</td>
                  <?php for($i=11;$i<=15;$i++){
                        $value=""; if(isset($select_result['estradoil_'.$i])) {$value = $select_result['estradoil_'.$i]; }
                    echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                  
                </tr>
              </tbody>
			  
			   <tbody>
                <tr>
                    <td  style="border:1px solid #cdcdcd;">SERUM PROGESTERONE LEVEL</td>
                    <?php for($i=11;$i<=15;$i++){
                        $value=""; if(isset($select_result['progesterone_'.$i])) {$value = $select_result['progesterone_'.$i]; }
                        echo '<td  style="border:1px solid #cdcdcd;">'.$value.'</td>';
                    }
                  ?>
                </tr>
              </tbody>
    </table>
	
	
	
	
	
   
    <table class="table table-bordered table-hover table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;">
        <thead>
            <tr>
                <th  style="border:1px solid #cdcdcd;">DOCTOR <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?></th>
                <th  style="border:1px solid #cdcdcd;">COUNSELLOR <?php echo isset($select_result['counsellor'])?$select_result['counsellor']:""; ?></th>
                <th  style="border:1px solid #cdcdcd;">NURSE <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?></th>
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