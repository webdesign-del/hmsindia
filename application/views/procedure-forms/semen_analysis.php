<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);

        // 1. Convert checkbox arrays to comma-separated strings BEFORE database insert/update
        $array_fields = array('physical_examination', 'applicablemedicine', 'Agglutination', 'Debris');
        foreach ($array_fields as $field) {
            if (isset($_POST[$field]) && is_array($_POST[$field])) {
                $_POST[$field] = implode(',', $_POST[$field]);
            } else if (!isset($_POST[$field])) {
                $_POST[$field] = '';
            }
        }

        $select_query = "SELECT * FROM `semen_analysis` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 

        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `semen_analysis` SET ";
            $sqlArr = array();
            foreach( $_POST as $key => $value )
            {
              // Check if value is array to prevent addslashes fatal error
              if (is_array($value)) {
                  $value = implode(',', $value);
              }
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
        } else {
            // mysql query to update data
            $query = "UPDATE `semen_analysis` SET ";
            $sqlArr = array();
            foreach( $_POST as $key => $value )
            {
              // Check if value is array to prevent addslashes fatal error
              if (is_array($value)) {
                  $value = implode(',', $value);
              }
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }
            $query .= implode(',' , $sqlArr);
            $query .= " WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        }

        $result = run_form_query($query);        
        if($result){
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Procedure form inserted!').'&t='.base64_encode('success'));
            die();
        } else {
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
            die();
        }
    }

    // Fetch saved data for form pre-filling
    $select_query = "SELECT * FROM `semen_analysis` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);   
    
    $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3);  
    
    $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
    $select_result1 = run_select_query($sql1);
    
    $sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".(isset($select_result1['wife_phone'])?$select_result1['wife_phone']:'')."' and paitent_type='new_patient'";
    $select_result4 = run_select_query($sql4);
    
    $sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".(isset($select_result4['appoitment_for'])?$select_result4['appoitment_for']:'')."'";
    $select_result5 = run_select_query($sql5);  

    // Extract saved checkbox arrays safely for checking inputs
    $physical = !empty($select_result['physical_examination']) ? explode(',', $select_result['physical_examination']) : array();
    $applicablemedicine = !empty($select_result['applicablemedicine']) ? explode(',', $select_result['applicablemedicine']) : array();
    $Agglutination = !empty($select_result['Agglutination']) ? explode(',', $select_result['Agglutination']) : array();
    $Debris = !empty($select_result['Debris']) ? explode(',', $select_result['Debris']) : array();
?><?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);

        // 1. Convert checkbox arrays to comma-separated strings BEFORE database insert/update
        $array_fields = array('physical_examination', 'applicablemedicine', 'Agglutination', 'Debris');
        foreach ($array_fields as $field) {
            if (isset($_POST[$field]) && is_array($_POST[$field])) {
                $_POST[$field] = implode(',', $_POST[$field]);
            } else if (!isset($_POST[$field])) {
                $_POST[$field] = '';
            }
        }

        $select_query = "SELECT * FROM `semen_analysis` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 

        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `semen_analysis` SET ";
            $sqlArr = array();
            foreach( $_POST as $key => $value )
            {
              // Check if value is array to prevent addslashes fatal error
              if (is_array($value)) {
                  $value = implode(',', $value);
              }
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
        } else {
            // mysql query to update data
            $query = "UPDATE `semen_analysis` SET ";
            $sqlArr = array();
            foreach( $_POST as $key => $value )
            {
              // Check if value is array to prevent addslashes fatal error
              if (is_array($value)) {
                  $value = implode(',', $value);
              }
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }
            $query .= implode(',' , $sqlArr);
            $query .= " WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        }

        $result = run_form_query($query);        
        if($result){
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Procedure form inserted!').'&t='.base64_encode('success'));
            die();
        } else {
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
            die();
        }
    }

    // Fetch saved data for form pre-filling
    $select_query = "SELECT * FROM `semen_analysis` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);   
    
    $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3);  
    
    $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
    $select_result1 = run_select_query($sql1);
    
    $sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".(isset($select_result1['wife_phone'])?$select_result1['wife_phone']:'')."' and paitent_type='new_patient'";
    $select_result4 = run_select_query($sql4);
    
    $sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".(isset($select_result4['appoitment_for'])?$select_result4['appoitment_for']:'')."'";
    $select_result5 = run_select_query($sql5);  

    // Extract saved checkbox arrays safely for checking inputs
    $physical = !empty($select_result['physical_examination']) ? explode(',', $select_result['physical_examination']) : array();
    $applicablemedicine = !empty($select_result['applicablemedicine']) ? explode(',', $select_result['applicablemedicine']) : array();
    $Agglutination = !empty($select_result['Agglutination']) ? explode(',', $select_result['Agglutination']) : array();
    $Debris = !empty($select_result['Debris']) ? explode(',', $select_result['Debris']) : array();
?>

<form enctype='multipart/form-data' class ="searchform" name="form" action="" method="POST">
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
<input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
<input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
<input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
<input type="hidden" value="pending" name="status"> 
<input type="hidden" value="First Cycle" name="type"> 
<div class="container2 red-field form mt-5 mb-5">
				<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><?php 
                // Header se set kiya hua global session uthayenge
                $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : ''; 
                
                if(!empty($page_logo)): ?>
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php else: ?>
                    <img src="<?php echo base_url('assets/center/default-logo.png'); ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php endif; ?></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">Department of Embryology</h3><h4>SEMEN ANALYSIS REPORT</h4></td>
   </tr>
</table>


<table class="fg45yu">
  <tr>
    
	  <td colspan="1" width="25%" style="border:1px solid;padding:5px;">UHID</td>
<td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></td>
  
  </tr>
   <tr>
    <td>PT.ID - <?php echo $patient_id;?></td>
    <td>DATE: <input type="date" id="date" name="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>">  </td>
    </tr>
 </table>
 <p style="font-size: 20px; font-weight: 600; text-decoration: underline;">Basic Data-</p>

<table class="fg45yu3">
<tr>
<td colspan="" width="57%">
<strong>Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td width="42%">
<strong>Husband&rsquo;s name :  <?php echo $select_result3['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="" width="57%">
<strong>Age:  <?php echo $select_result3['wife_age']; ?> Year</strong>
</td>
<td width="42%">
<strong>Age: <?php echo $select_result3['husband_age']; ?> Year</strong>
</td>
</tr>
   <tr>
    <td>Ejaculatory abstinence period</td>
    <td><input type="text" class="Ejaculatory" name="Ejaculatory" value="<?php echo isset($select_result['Ejaculatory'])?$select_result['Ejaculatory']:""; ?>"> days</td>
    </tr>
  <tr>
    <td>Method of Collection</td>
    <td>
  <input class="checkbox" type="checkbox" id="Masturbation" name="physical_examination[]" value="Masturbation" <?php if(!empty($select_result['physical_examination']) && in_array('Masturbation', $physical)){echo "checked";}?>>
  <label for="Masturbation">Masturbation</label>
  <input class="checkbox" type="checkbox" id="Coitus" name="physical_examination[]" value="Coitus" <?php if(!empty($select_result['physical_examination']) && in_array('Coitus', $physical)){echo "checked";}?>>
  <label for="Coitus">Coitus</label>
  <input class="checkbox" type="checkbox" id="Condom" name="physical_examination[]" value="Condom" <?php if(!empty($select_result['physical_examination']) && in_array('Condom', $physical)){echo "checked";}?>>
  <label for="Condom">Condom</label>
   <input class="checkbox" type="checkbox" id="Others" name="physical_examination[]" value="Others" <?php if(!empty($select_result['physical_examination']) && in_array('Others', $physical)){echo "checked";}?>>
  <label for="Others">Others</label>


    </td>
  </tr>
  <tr>
    <td>Sample Collected:
Place:<input type="text" class="Place" name="Place" value="<?php echo isset($select_result['Place'])?$select_result['Place']:""; ?>">
Time:<input type="text" class="Time" name="Time" value="<?php echo isset($select_result['Time'])?$select_result['Time']:""; ?>">
    </td>
    <td>
  
  <input class="checkbox" type="checkbox" id="HOSPITAL" name="applicablemedicine[]" value="HOSPITAL" <?php if(!empty($select_result['applicablemedicine']) && in_array('HOSPITAL',$applicablemedicine)){echo "checked";}?>>
  <label for="HOSPITAL">HOSPITAL</label>
  <input class="checkbox" type="checkbox" id="OUTSIDE" name="applicablemedicine[]" value="OUTSIDE" <?php if(!empty($select_result['applicablemedicine']) && in_array('OUTSIDE',$applicablemedicine)){echo "checked";}?>>
  <label for="OUTSIDE">OUTSIDE</label>
  <input class="checkbox" type="checkbox" id="HOME" name="applicablemedicine[]" value="HOME" <?php if(!empty($select_result['applicablemedicine']) && in_array('HOME',$applicablemedicine)){echo "checked";}?>>
  <label for="HOME">HOME</label>
   <input class="checkbox" type="checkbox" id="Others" name="applicablemedicine[]" value="Others" <?php if(!empty($select_result['applicablemedicine']) && in_array('Others',$applicablemedicine)){echo "checked";}?>>
  <label for="Others">Others</label>

</td>
  </tr>
 </table>
<table class="fg45yu3q">
   <tr>
    <td>PARAMETERS</td>
    <td>RESULT</td>
   <td> Normal Range(WHO 6th edition)</td>
    </tr>
 </table>
 <p style="font-size: 20px; font-weight: 600; text-decoration: underline;">MACROSCOPIC EXAMINATION</p>

 <table class="fg45yu3">
   <tr>
    <td><strong>Volume</strong></td>
    <td><input type="text" class="Time" name="Volume" value="<?php echo isset($select_result['Volume'])?$select_result['Volume']:""; ?>" >mL</td>
   <td>1.4 ml or more</td>
    </tr>
    <tr>
    <td><strong>Liquefaction Time</strong></td>
    <td><input type="text" class="minutes" name="minutes" value="<?php echo isset($select_result['minutes'])?$select_result['minutes']:""; ?>">minutes</td>
   <td><30 minutes</td>
    </tr>
      <tr>
    <td><strong>Color</strong></td>
    <td>Grey-Opalescent</td>
   <td></td>
    </tr>
     <tr>
    <td><strong>pH</strong></td>
    <td><input type="text" class="ph_value" name="ph_value" value="<?php echo isset($select_result['ph_value'])?$select_result['ph_value']:""; ?>"></td>
   <td>ALKALINE</td>
    </tr>
     <tr>
    <td><strong>Viscosity</strong></td>
    <td>
    <form action="">
  <input type="radio" id="NORMAL" name="Viscosity" value="NORMAL" <?php if(isset($select_result['Viscosity']) && $select_result['Viscosity'] == "NORMAL"){ echo "checked";} ?>>
  <label for="NORMAL">NORMAL</label><br>
  <input type="radio" id="HIGH" name="Viscosity" value="HIGH" <?php if(isset($select_result['Viscosity']) && $select_result['Viscosity'] == "HIGH"){ echo "checked";} ?>>
  <label for="HIGH">HIGH</label><br> 
   <input type="radio" id="tooHIGH" name="Viscosity" value="tooHIGH" <?php if(isset($select_result['Viscosity']) && $select_result['Viscosity'] == "tooHIGH"){ echo "checked";} ?>>
  <label for="tooHIGH">TOO HIGH</label><br>   
</form></td>
   <td></td>
    </tr>

 </table>

 <table>
 <tr>
   <td>Freezing Details:  <textarea name="Freezing" style="width:100%; height:150px;" > <?php echo isset($select_result['Freezing'])?$select_result['Freezing']:""; ?> </textarea>
</td>
     </tr>
 </table>


 <p style="font-size: 20px; font-weight: 600; text-decoration: underline;">MICROSCOPIC EXAMINATION</p>

 <table class="jh67yu">
<tbody>
<tr>
<td colspan="2" width="222">
<p><strong>Sperm count</strong></p>
</td>
<td width="185">
<p><input type="text" class="minutes" name="Sperm_minutes" value="<?php echo isset($select_result['Sperm_minutes'])?$select_result['Sperm_minutes']:""; ?>" > Millions/mL</p>
</td>
<td width="196">
<p>16 million/ml</p>
</td>
</tr>
<tr>
<td colspan="2" width="222">
<p>Total Sperm Number (TSN)</p>
</td>
<td width="185">
<p><input type="text" class="minutes" name="t_minutes" value="<?php echo isset($select_result['t_minutes'])?$select_result['t_minutes']:""; ?>"> Millions/Ejaculate</p>
</td>
<td width="196">
<p>&gt;39 million/ejaculate</p>
</td>
</tr>
<tr>
<td rowspan="4" width="87">
<p><strong>Sperm Motility</strong></p>
</td>
<td width="135">
<p>Total</p>
</td>
<td width="185">
<p><input type="text" class="minutes" name="Sperm_Motility" value="<?php echo isset($select_result['Sperm_Motility'])?$select_result['Sperm_Motility']:""; ?>" > %</p>
</td>
<td width="196">
<p>&gt;42%</p>
</td>
</tr>
<tr>
<td width="135">
<p>Progressive</p>
</td>
<td width="185">
<p><input type="text" class="minutes" name="Progressive" value="<?php echo isset($select_result['Progressive'])?$select_result['Progressive']:""; ?>"> %</p>
</td>
<td width="196">
<p>&gt;30%</p>
</td>
</tr>
<tr>
<td width="135">
<p>Non Progressive</p>
</td>
<td width="185">
<p><input type="text" class="minutes" name="n_Progressive" value="<?php echo isset($select_result['n_Progressive'])?$select_result['n_Progressive']:""; ?>"> %</p>
</td>
<td width="196">
<p>&lt;12%</p>
</td>
</tr>
<tr>
<td width="135">
<p>Non Motile</p>
</td>
<td width="185">
<p><input type="text" class="minutes" name="Motile" value="<?php echo isset($select_result['Motile'])?$select_result['Motile']:""; ?>"> %</p>
</td>
</tr>
<tr>
<td colspan="2" width="222">
<p>Sperm Viability</p>
</td>
<td width="185">
<p><input type="text" class="minutes" name="Viability_minute" value="<?php echo isset($select_result['Viability_minute'])?$select_result['Viability_minute']:""; ?>"> %</p>
</td>
<td width="196">
<p>&gt;54%</p>
</td>
</tr>
<tr>
<td rowspan="4" width="87">
<p><strong>Sperm Morphology</strong></p>
</td>
<td width="135">
<p><strong>Normal</strong></p>
</td>
<td width="196">
<input type="text" class="minutes" name="sperm_morphology_val" value="<?php echo isset($select_result['sperm_morphology_val'])?$select_result['sperm_morphology_val']:""; ?>">
</td>
<td width="196">
<p><strong>4%</strong></p>
</td>
</tr>
<tr>
<td width="135">
<p>Head defects</p>
</td>
<td width="135">
<input type="text" class="minutes" name="Head" value="<?php echo isset($select_result['Head'])?$select_result['Head']:""; ?>">
</td>

</tr>

<tr>
<td width="135">
<p>Neck &amp; Mid-piece defects</p>
</td>
<td width="135">
<input type="text" class="minutes" name="Mid_piece" value="<?php echo isset($select_result['Mid_piece'])?$select_result['Mid_piece']:""; ?>">

</td>
<td width="135">

</td>
</tr>
<tr>
<td width="135">
<p>Tail defects</p>
</td>
<td width="135">
<input type="text" class="minutes" name="Tail" value="<?php echo isset($select_result['Tail'])?$select_result['Tail']:""; ?>">

</td>
<td width="135">

</td>
</tr>

</tbody>
</table>
<table class="jh67yu">
<tbody>
<tr>
<td width="302">
<p>Agglutination</p>
</td>
<td width="296">
<p>

  <input class="checkbox" type="checkbox" id="NIL" name="Agglutination[]" value="NIL" <?php if(!empty($select_result['Agglutination']) && in_array('NIL', $Agglutination)){echo "checked";}?> >
  <label for="Masturbation">NIL</label>
  <input class="checkbox" type="checkbox" id="NIL1" name="Agglutination[]" value="NIL1" <?php if(!empty($select_result['Agglutination']) && in_array('NIL1', $Agglutination)){echo "checked";}?>>
  <label for="NIL1">+</label>
  <input class="checkbox" type="checkbox" id="NIL2" name="Agglutination[]" value="NIL2" <?php if(!empty($select_result['Agglutination']) && in_array('NIL2', $Agglutination)){echo "checked";}?>>
  <label for="NIL2">++</label>
  <input class="checkbox" type="checkbox" id="NIL3" name="Agglutination[]" value="NIL3" <?php if(!empty($select_result['Agglutination']) && in_array('NIL3', $Agglutination)){echo "checked";}?>>
  <label for="NIL3">+++</label>


</p>
</td>
</tr>
<tr>
<td width="302">
<p>Pus Cells</p>
</td>
<td width="296">
<p><input type="text" class="Millions" name="Millions" value="<?php echo isset($select_result['Millions'])?$select_result['Millions']:""; ?>"> Millions/mL</p>
</td>
</tr>
<tr>
<td width="302">
<p>Debris</p>
</td>
<td width="296">
<p>
  <input class="checkbox" type="checkbox" id="NIL" name="Debris[]" value="NIL"  <?php if(!empty($select_result['Debris']) && in_array('NIL', $Debris)){echo "checked";}?> >
  <label for="Masturbation">NIL</label>
  <input class="checkbox" type="checkbox" id="NIL1" name="Debris[]" value="NIL1" <?php if(!empty($select_result['Debris']) && in_array('NIL1', $Debris)){echo "checked";}?> >
  <label for="NIL1">+</label>
  <input class="checkbox" type="checkbox" id="NIL2" name="Debris[]" value="NIL2" <?php if(!empty($select_result['Debris']) && in_array('NIL2', $Debris)){echo "checked";}?> >
  <label for="NIL2">++</label>
  <input class="checkbox" type="checkbox" id="NIL3" name="Debris[]" value="NIL3" <?php if(!empty($select_result['Debris']) && in_array('NIL3', $Debris)){echo "checked";}?> >
  <label for="NIL3">+++</label></p>
</td>
</tr>
<tr>
<td width="302">
<p>Others</p>
</td>
<td width="302">
 
 <textarea name="Others" style="width:100%; height:150px;" > <?php echo isset($select_result['Others'])?$select_result['Others']:""; ?> </textarea>



</td>
</tr>
</tbody>
</table>

<table class="jhyu67uy">
<tr>
<td width="302">
<p>Impression :  <input style="width:100%" type="text" class="Prepared" name="impression" value="<?php echo isset($select_result['impression'])?$select_result['impression']:""; ?>"> </p>
</td>
</tr>
<tr>
<td width="302">
Prepared By:- <input type="text" style="width:100%" class="Prepared" name="prepared_by" value="<?php echo isset($select_result['prepared_by'])?$select_result['prepared_by']:""; ?>">
</td>
</tr>
<tr>
<td width="302">
<p>Adviced :   <input type="text" style="width:100%" class="Prepared" name="Adviced" value="<?php echo isset($select_result['Adviced'])?$select_result['Adviced']:""; ?>"></p>
</td>
</tr>
<tr>
<td width="302">
Checked By:-  <input type="text" style="width:100%" class="Prepared" name="checked_by" value="<?php echo isset($select_result['checked_by'])?$select_result['checked_by']:""; ?>">
</td>
</tr>

</table>

<div class="col-sm-4" style="margin-top: 10px;">
<input type="submit" name="submit" value="submit" class="btn btn-secondary">    
</div>

</form>
</div>

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">
  
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="width:50%;padding:5px;" colspan="2"><?php 
                // Header se set kiya hua global session uthayenge
                $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : ''; 
                
                if(!empty($page_logo)): ?>
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php else: ?>
                    <img src="<?php echo base_url('assets/center/default-logo.png'); ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php endif; ?></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">SEMEN ANALYSIS REPORT</h3></td>
</tr>
</table>
  <?php $physical = $applicablemedicine= $Agglutination = $Debris = array();
    if(!empty($select_result['physical_examination'])){
        $physical = explode(',',$select_result['physical_examination']);
    }
    if(!empty($select_result['applicablemedicine'])){
        $applicablemedicine = explode(',',$select_result['applicablemedicine']);
    }	
	if(!empty($select_result['Agglutination'])){
        $Agglutination = explode(',',$select_result['Agglutination']);
    }
	if(!empty($select_result['Debris'])){
        $Debris = explode(',',$select_result['Debris']);
    }	
?>
<table class="fg45yu3">
  <tr>
    
	  <td colspan="2" width="50%" style="border:1px solid;padding:5px;">UHID</td>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;"><?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?>
</td>
  
  </tr>
<tr>
    <td colspan="2" style="width:50%; border:1px solid; padding:5px;">PT.ID - <?php echo $patient_id;?></td>
    <td colspan="2" style="width:50%; border:1px solid; padding:5px;">DATE: <?php echo isset($select_result['date'])?$select_result['date']:""; ?></td>
</tr>
	
<tr>
    <td colspan="4" style="width:100%; border:1px solid; padding:5px;"><p style="font-size: 20px; font-weight: 600; text-decoration: underline;">Basic Data-</p></td>
</tr>

<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Husband&rsquo;s name :  <?php echo $select_result3['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Age:  <?php echo $select_result3['wife_age']; ?> Year</strong>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Age: <?php echo $select_result3['husband_age']; ?> Year</strong>
</td>
</tr>
<tr>
    <td colspan="2" style="width:50%; border:1px solid; padding:5px;">Ejaculatory abstinence period</td>
    <td colspan="2" style="width:50%; border:1px solid; padding:5px;"><?php echo isset($select_result['Ejaculatory'])?$select_result['Ejaculatory']:""; ?></td>
</tr>
<tr>
    <td colspan="2" style="width:50%; border:1px solid; padding:5px;">Method of Collection</td>
    <td colspan="2" style="width:50%; border:1px solid; padding:5px;">
  <input class="checkbox" type="checkbox" id="Masturbation" name="physical_examination[]" value="Masturbation" <?php if(!empty($select_result['physical_examination']) && in_array('Masturbation', $physical)){echo "checked";}?>>
  <label for="Masturbation">Masturbation</label>
  <input class="checkbox" type="checkbox" id="Coitus" name="physical_examination[]" value="Coitus" <?php if(!empty($select_result['physical_examination']) && in_array('Coitus', $physical)){echo "checked";}?>>
  <label for="Coitus">Coitus</label>
  <input class="checkbox" type="checkbox" id="Condom" name="physical_examination[]" value="Condom" <?php if(!empty($select_result['physical_examination']) && in_array('Condom', $physical)){echo "checked";}?>>
  <label for="Condom">Condom</label>
   <input class="checkbox" type="checkbox" id="Others" name="physical_examination[]" value="Others" <?php if(!empty($select_result['physical_examination']) && in_array('Others', $physical)){echo "checked";}?>>
  <label for="Others">Others</label>
  </td>
</tr>
<tr>
    <td colspan="2" style="width:50%; border:1px solid; padding:5px;">Sample Collected:
Place:<input type="text" class="Place" name="Place" value="<?php echo isset($select_result['Place'])?$select_result['Place']:""; ?>">
Time:<input type="text" class="Time" name="Time" value="<?php echo isset($select_result['Time'])?$select_result['Time']:""; ?>">
    </td>
    <td colspan="2" style="width:50%; border:1px solid; padding:5px;">
  <input class="checkbox" type="checkbox" id="HOSPITAL" name="applicablemedicine[]" value="HOSPITAL" <?php if(!empty($select_result['applicablemedicine']) && in_array('HOSPITAL',$applicablemedicine)){echo "checked";}?>>
  <label for="HOSPITAL">HOSPITAL</label>
  <input class="checkbox" type="checkbox" id="OUTSIDE" name="applicablemedicine[]" value="OUTSIDE" <?php if(!empty($select_result['applicablemedicine']) && in_array('OUTSIDE',$applicablemedicine)){echo "checked";}?>>
  <label for="OUTSIDE">OUTSIDE</label>
  <input class="checkbox" type="checkbox" id="HOME" name="applicablemedicine[]" value="HOME" <?php if(!empty($select_result['applicablemedicine']) && in_array('HOME',$applicablemedicine)){echo "checked";}?>>
  <label for="HOME">HOME</label>
   <input class="checkbox" type="checkbox" id="Others" name="applicablemedicine[]" value="Others" <?php if(!empty($select_result['applicablemedicine']) && in_array('Others',$applicablemedicine)){echo "checked";}?>>
  <label for="Others">Others</label>
  </td>
</tr>
 </table>
<table width="100%" class="fg45yu3q">
   <tr>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;">PARAMETERS</td>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;">RESULT</td>
   <td colspan="2" style="width:33%; border:1px solid; padding:5px;"> Normal Range(WHO 6th edition)</td>
    </tr>
 </table>


<table width="100%" class="fg45yu3">
<tr>
    <td colspan="4" style="width:100%; border:1px solid; padding:5px;"> <p style="font-size: 20px;">MACROSCOPIC EXAMINATION</p></td>
</tr>
 
<tr>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;"><strong>Volume</strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><?php echo isset($select_result['Volume'])?$select_result['Volume']:""; ?>mL</td>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;">1.4 ml or more</td>
</tr>
<tr>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;"><strong>Liquefaction Time</strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><?php echo isset($select_result['minutes'])?$select_result['minutes']:""; ?></td>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;"><30 minutes</td>
</tr>
<tr>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;"><strong>Color</strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;">Grey-Opalescent</td>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;"></td>
</tr>
<tr>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;"><strong>pH</strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><?php echo isset($select_result['ph_value'])?$select_result['ph_value']:""; ?></td>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;">ALKALINE</td>
</tr>
<tr>
    <td colspan="1" style="width:33%; border:1px solid; padding:5px;"><strong>Viscosity</strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;">
  <input type="radio" id="NORMAL" name="Viscosity" value="NORMAL" <?php if(isset($select_result['Viscosity']) && $select_result['Viscosity'] == "NORMAL"){ echo "checked";} ?>>
  <label for="NORMAL">NORMAL</label><br>
  <input type="radio" id="HIGH" name="Viscosity" value="HIGH" <?php if(isset($select_result['Viscosity']) && $select_result['Viscosity'] == "HIGH"){ echo "checked";} ?>>
  <label for="HIGH">HIGH</label><br> 
   <input type="radio" id="tooHIGH" name="Viscosity" value="tooHIGH" <?php if(isset($select_result['Viscosity']) && $select_result['Viscosity'] == "tooHIGH"){ echo "checked";} ?>>
  <label for="tooHIGH">TOO HIGH</label><br>   
 </td>
   <td colspan="1" style="width:33%; border:1px solid; padding:5px;"></td>
</tr>

 </table>

 <table style="width:100%;">
 <tr>
   <td colspan="4" style="width:100%; border:1px solid; padding:5px;">Freezing Details:  <textarea style="width:100%; height:100px;" > <?php echo isset($select_result['Freezing'])?$select_result['Freezing']:""; ?> </textarea>
</td>
     </tr>
 </table>

<table class="jh67yu" style="width:100%;">
<tbody>
<tr>
   <td colspan="4" style="width:100%; border:1px solid; padding:5px;">MICROSCOPIC EXAMINATION</td>
</tr>
<tr>
<td colspan="1" style="width:33%; border:1px solid; padding:5px;">
<p><strong>Sperm count</strong></p>
</td>
<td colspan="2" style="width:33%; border:1px solid; padding:5px;">
<p> <?php echo isset($select_result['Sperm_minutes'])?$select_result['Sperm_minutes']:""; ?> :  Millions/mL</p>
</td>
<td colspan="1" style="width:33%; border:1px solid; padding:5px;">
<p>16 million/ml</p>
</td>
</tr>

<tr>
<td colspan="1" style="width:33%; border:1px solid; padding:5px;">
<p>Total Sperm Number (TSN)</p>
</td>
<td colspan="2" style="width:33%; border:1px solid; padding:5px;">
<p><?php echo isset($select_result['t_minutes'])?$select_result['t_minutes']:""; ?> :Millions/Ejaculate</p>
</td>
<td colspan="1" style="width:33%; border:1px solid; padding:5px;">
<p>&gt;39 million/ejaculate</p>
</td>
</tr>
</tbody>
</table>

<table class="jh67yu" style="width:100%;">
<tbody>
<tr>
<td rowspan="4" colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p><strong>Sperm Motility</strong></p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>Total</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p><?php echo isset($select_result['Sperm_Motility'])?$select_result['Sperm_Motility']:""; ?> %</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>&gt;42%</p>
</td>
</tr>

<tr>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>Progressive</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p><input type="text" class="minutes" name="Progressive" value="<?php echo isset($select_result['Progressive'])?$select_result['Progressive']:""; ?>"> %</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>&gt;30%</p>
</td>
</tr>

<tr>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>Non Progressive</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p><input type="text" class="minutes" name="n_Progressive" value="<?php echo isset($select_result['n_Progressive'])?$select_result['n_Progressive']:""; ?>"> %</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>&lt;12%</p>
</td>
</tr>

<tr>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>Non Motile</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p><input type="text" class="minutes" name="Motile" value="<?php echo isset($select_result['Motile'])?$select_result['Motile']:""; ?>"> %</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
</td>
</tr>

<tr>
<td colspan="2" style="width:25%; border:1px solid; padding:5px;">
<p>Sperm Viability</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p><input type="text" class="minutes" name="Viability_minute" value="<?php echo isset($select_result['Viability_minute'])?$select_result['Viability_minute']:""; ?>"> %</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>&gt;54%</p>
</td>
</tr>
<tr>
<td rowspan="4" colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p><strong>Sperm Morphology</strong></p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p><strong>Normal</strong></p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<input type="text" class="minutes" name="sperm_morphology_val" value="<?php echo isset($select_result['sperm_morphology_val'])?$select_result['sperm_morphology_val']:""; ?>">
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p><strong>4%</strong></p>
</td>
</tr>
<tr>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>Head defects</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<input type="text" class="minutes" name="Head" value="<?php echo isset($select_result['Head'])?$select_result['Head']:""; ?>">
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
</td>
</tr>

<tr>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>Neck &amp; Mid-piece defects</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<input type="text" class="minutes" name="Mid_piece" value="<?php echo isset($select_result['Mid_piece'])?$select_result['Mid_piece']:""; ?>">

</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">

</td>
</tr>
<tr>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<p>Tail defects</p>
</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
<input type="text" class="minutes" name="Tail" value="<?php echo isset($select_result['Tail'])?$select_result['Tail']:""; ?>">

</td>
<td colspan="1" style="width:25%; border:1px solid; padding:5px;">
</td>
</tr>
</tbody>
</table>

<table class="jh67yu" width="100%">
<tbody>
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p>Agglutination</p>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p>

  <input class="checkbox" type="checkbox" id="NIL" name="Agglutination[]" value="NIL" <?php if(!empty($select_result['Agglutination']) && in_array('NIL', $Agglutination)){echo "checked";}?> >
  <label for="Masturbation">NIL</label>
  <input class="checkbox" type="checkbox" id="NIL1" name="Agglutination[]" value="NIL1" <?php if(!empty($select_result['Agglutination']) && in_array('NIL1', $Agglutination)){echo "checked";}?>>
  <label for="NIL1">+</label>
  <input class="checkbox" type="checkbox" id="NIL2" name="Agglutination[]" value="NIL2" <?php if(!empty($select_result['Agglutination']) && in_array('NIL2', $Agglutination)){echo "checked";}?>>
  <label for="NIL2">++</label>
  <input class="checkbox" type="checkbox" id="NIL3" name="Agglutination[]" value="NIL3" <?php if(!empty($select_result['Agglutination']) && in_array('NIL3', $Agglutination)){echo "checked";}?>>
  <label for="NIL3">+++</label>


</p>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p>Pus Cells</p>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p><input type="text" class="Millions" name="Millions" value="<?php echo isset($select_result['Millions'])?$select_result['Millions']:""; ?>"> Millions/mL</p>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p>Debris</p>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p>
  <input class="checkbox" type="checkbox" id="NIL" name="Debris[]" value="NIL"  <?php if(!empty($select_result['Debris']) && in_array('NIL', $Debris)){echo "checked";}?> >
  <label for="Masturbation">NIL</label>
  <input class="checkbox" type="checkbox" id="NIL1" name="Debris[]" value="NIL1" <?php if(!empty($select_result['Debris']) && in_array('NIL1', $Debris)){echo "checked";}?> >
  <label for="NIL1">+</label>
  <input class="checkbox" type="checkbox" id="NIL2" name="Debris[]" value="NIL2" <?php if(!empty($select_result['Debris']) && in_array('NIL2', $Debris)){echo "checked";}?> >
  <label for="NIL2">++</label>
  <input class="checkbox" type="checkbox" id="NIL3" name="Debris[]" value="NIL3" <?php if(!empty($select_result['Debris']) && in_array('NIL3', $Debris)){echo "checked";}?> >
  <label for="NIL3">+++</label></p>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p>Others</p>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
 <textarea style="width:100%; height:100px;" > <?php echo isset($select_result['Others'])?$select_result['Others']:""; ?> </textarea>
</td>
</tr>
</tbody>
</table>

<table class="jhyu67uy" width="100%">
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p>Impression :  <?php echo isset($select_result['impression'])?$select_result['impression']:""; ?> </p>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
Prepared By:- <?php echo isset($select_result['prepared_by'])?$select_result['prepared_by']:""; ?>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p>Adviced :   <?php echo isset($select_result['Adviced'])?$select_result['Adviced']:""; ?></p>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
Checked By:-  <?php echo isset($select_result['checked_by'])?$select_result['checked_by']:""; ?>
</td>
</tr>

</table>
</div>
</div>
<style>
  table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    margin-bottom:20px ;
}
td {border: 1px  solid #000; text-align: left; font-weight: 600;  padding-left: 20px;}
.fg45yu td {height: 40px; width: 50%;}
.fg45yu3 td {font-weight: 100;}
.jh67yu td {font-weight: 100;}
.fg45yu3q td {text-align: center; background: #c5c1bc; padding: 10px; width: 30%;}
.jhyu67uy td {border: none;}
input[type=checkbox], input[type=radio] {
    opacity: 1 !important;
    left: 0 !important;
    position: unset !important;
    margin: 9px !important;
}
.checkbox {
  appearance: none;
  transform:translateY(-50%);
  background-color: #F44336;
  width:23px;
  height:23px;
  border-radius:40px;
  margin:0px;
  outline: none; 
  display: inline-block !important;
  transition:background-color .5s;
  float: left;
}
[type="checkbox"] + label {
  float: left !important;
  padding-left: 0 !important;
}
.checkbox:before {
  content:'';
  position: absolute;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%) rotate(45deg);
  background-color:#ffffff;
  width:20px;
  height:5px;
  border-radius:40px;
  transition:all .5s;
}
.checkbox:after {
  content:'';
  position: absolute;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%) rotate(-45deg);
  background-color:#ffffff;
  width:20px;
  height:5px;
  border-radius:40px;
  transition:all .5s;
}
.checkbox:checked {
  background-color:#4CAF50;
}
.checkbox:checked:before {
  content:'';
  position: absolute;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%) translate(-4px,3px) rotate(45deg);
  background-color:#ffffff;
  width:12px;
  height:5px;
  border-radius:40px;
}
.checkbox:checked:after {
  content:'';
  position: absolute;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%) translate(3px,2px) rotate(-45deg);
  background-color:#ffffff;
  width:16px;
  height:5px;
  border-radius:40px;
}
 </style>
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