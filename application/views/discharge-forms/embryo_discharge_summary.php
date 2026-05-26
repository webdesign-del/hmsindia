<?php 
$all_method =& get_instance();
$appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
  
        // चेक करें कि क्या इस iic_id का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `embryology_discharge_summary` WHERE iic_id='$iic_id'";
        $select_result = run_select_query($sql);
       
        // अगर डेटा मौजूद नहीं है, सिर्फ तभी INSERT करें
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `embryology_discharge_summary` SET ";
            $sqlArr = array();
            foreach($_POST as $key => $value) {
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }   
            $query .= implode(',' , $sqlArr);
            
            $result = run_form_query($query); 
        
            if($result){
                header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Discharge form saved successfully!').'&t='.base64_encode('success'));
                die();
            } else {
                header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
                die();
            }
            
        } else {
            // अगर डेटा पहले से मौजूद है, तो UPDATE ना करें, बल्कि सीधा एरर मैसेज के साथ रिडायरेक्ट करें
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Data is already saved and cannot be updated!').'&t='.base64_encode('error'));
            die();
        }
    }
  
    // फॉर्म में पुराना डेटा दिखाने के लिए सिर्फ iic_id से फेच करें
    $sql = "SELECT * FROM `embryology_discharge_summary` WHERE iic_id='$iic_id'";
    $select_result = run_select_query($sql);
  
    $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$iic_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
  
    $sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result2['appoitment_for']."'";
    $select_result3 = run_select_query($sql3);
     
?>
<div class="ga-pro">
<table class="fg45yu">
   <tr><td style="text-align: center;border:0px;"><h3 style="margin-top:20px;">Department of Embryology</h3></td></tr>
   <tr><td style="text-align: center;border:0px;"><h4>Embryo Transfer Dischrge Summary</h4></td></tr>
</table>
    
<form action="" enctype='multipart/form-data' method="post">
  <input type="hidden" value="<?php echo $iic_id;?>" class="form" name="iic_id">
  <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">

<div style="float: left; margin-bottom: 10px;margin-right:20px;">
<label for="Center">Center</label>
<select class="form-control" id="center" name="center">
    <option value=''>--Select From--</option>
    <?php $all_centers = $all_method->get_all_centers();
	foreach($all_centers as $key => $val){ //var_dump($val);die;
    if($center == $val['center_number']){
    echo '<option value="'.$val['center_number'].'" selected>'.$val['center_name'].'</option>';
    }else{
	echo '<option value="'.$val['center_number'].'">'.$val['center_name'].'</option>';
    }
    } 
	?>
    </select> 
</div> 

<div style="float: left; margin-bottom: 10px;">
  <label for="Admission">Date of Admission:</label>
  <input type="date" class="Admission" name="date_of_addmission" value="<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>">
 </div>
     
<div style="float: right; margin-bottom: 10px;">
  <label for="Discharge">Date of Discharge:</label>
  <input type="date" class="Discharge" name="date_of_discharge" value="<?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?>">
 </div>

<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
    <td colspan="6" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Male Partner</strong>
	</td>
  </tr>
<tr style="background: #b3b9b7;">
<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></strong>
</td>
<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="6" width="50%">
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td width="50%" colspan="6">
<strong>Male Partner : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="6" width="50%">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td width="50%" colspan="6">
<strong>Age: <?php echo $patient_data['husband_age']; ?></strong>
</td>
</tr>
<tr>
<td colspan="12" width="100%">
<strong><p>Name of Procedure</p></strong>
</td>
</tr>
<tr>
<td colspan="2" width="17%">
<p>Fresh Embryo Transfer </p>
<input type="radio" id="Embryo_Transfer" name="Embryo_Transfer" value="Yes" <?php if(isset($select_result['Embryo_Transfer']) && $select_result['Embryo_Transfer']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Embryo_Transfer" name="Embryo_Transfer" value="No" <?php if(isset($select_result['Embryo_Transfer']) && $select_result['Embryo_Transfer'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" width="17%">
<p>FET</p>
<input type="radio" id="FET" name="FET" value="Yes" <?php if(isset($select_result['FET']) && $select_result['FET']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="FET" name="FET" value="No" <?php if(isset($select_result['FET']) && $select_result['FET'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" width="17%">
<p>Blastocyst</p>
<input type="radio" id="Blastocyst" name="Blastocyst" value="Yes" <?php if(isset($select_result['Blastocyst']) && $select_result['Blastocyst']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Blastocyst" name="Blastocyst" value="No" <?php if(isset($select_result['Blastocyst']) && $select_result['Blastocyst'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" width="17%">
<p>Laser Assisted Hatching</p>
<input type="radio" id="Laser_Assisted" name="Laser_Assisted" value="Yes" <?php if(isset($select_result['Laser_Assisted']) && $select_result['Laser_Assisted']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Laser_Assisted" name="Laser_Assisted" value="No" <?php if(isset($select_result['Laser_Assisted']) && $select_result['Laser_Assisted'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>
<td colspan="2" width="16%">
<p>Embryo Glue</p>
<input type="radio" id="Embryo_Glue" name="Embryo_Glue" value="Yes" <?php if(isset($select_result['Embryo_Glue']) && $select_result['Embryo_Glue']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Embryo_Glue" name="Embryo_Glue" value="No" <?php if(isset($select_result['Embryo_Glue']) && $select_result['Embryo_Glue'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>
<td colspan="2" width="16%">
<strong>Date of procedure:   <input type="date" class="date_of_procedure" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>">   </strong>
</td>
</tr>

</tbody>
</table> 

<div class="sec2">
<h3 style="text-align: left; margin-left: 10px;">Embryo transfer details:</h3> 
<table width="100%" class="vb45rt">
<tbody>
<tr>
<!--<td colspan="2" width="50%">
<label for="fertilization">Day 1 fertilization status </label>
<textarea name="fertilization_status" style="width:100%; height:80px!important"  > <?php echo isset($select_result['fertilization_status'])?$select_result['fertilization_status']:""; ?> </textarea>
</td>-->
<td width="50%">
<label for="embryo-transfer">Detail of fresh /frozen embryo transfer with number and grading</label>
<textarea name="fresh_embryo_transfer" style="width:100%; height:80px!important"  > <?php echo isset($select_result['fresh_embryo_transfer'])?$select_result['fresh_embryo_transfer']:""; ?> </textarea>

</td>
</tr>
<!--
<tr>
<td colspan="2" width="50%">
<label for="fertilization">Date of cryopreservation of embryos with number and grading</label>
<textarea name="date_of_cryopreservation" style="width:100%; height:80px!important"  > <?php echo isset($select_result['date_of_cryopreservation'])?$select_result['date_of_cryopreservation']:""; ?> </textarea>
</td>
<td width="50%">
<label for="Storage renewal">Storage renewal date:</label>
<input type="date" class="Storage-renewal" name="storage_renewal_date" value="<?php echo isset($select_result['storage_renewal_date'])?$select_result['storage_renewal_date']:""; ?>">
</td>
</tr>

<tr>
<td colspan="4" width="100%">
<label for="embryo-transfer">Remaining embryos after transfer</label>
<textarea name="embryos_after_transfer" style="width:100%; height:80px!important"  > <?php echo isset($select_result['embryos_after_transfer'])?$select_result['embryos_after_transfer']:""; ?> </textarea>
</td>
</tr>-->
</tbody>
</table> 


  
<br>
<input type="radio" id="easy" name="embryo_transfer_process" value="Easy embryo transfer" <?php if(isset($select_result['embryo_transfer_process']) && $select_result['embryo_transfer_process'] == "Easy embryo transfer"){ echo "checked";} ?>>
<label for="easy">Easy  embryo transfer</label><br>
<input type="radio" id="Difficult" name="embryo_transfer_process" value="Difficult embryo transfer" <?php if(isset($select_result['embryo_transfer_process'])  && $select_result['embryo_transfer_process']  == "Difficult embryo transfer"){ echo "checked";} ?>>
<label for="Difficult">Difficult embryo transfer</label><br>

<p>ETG-</p>

<input type="radio" id="ETG" name="etg" value="A"  <?php if(isset($select_result['etg']) && $select_result['etg'] == "A"){echo 'checked="checked"'; }?> >
<label for="ETG">A</label><br>
<input type="radio" id="ETG" name="etg" value="B"  <?php if(isset($select_result['etg']) && $select_result['etg'] == "B"){echo 'checked="checked"'; }?>>
<label for="ETG">B</label><br>
<input type="radio" id="ETG" name="etg" value="C"  <?php if(isset($select_result['etg']) && $select_result['etg'] == "C"){echo 'checked="checked"'; }?>>
<label for="ETG">C</label><br>

<!--
<p style="margin:10px 0px;">Note: embryos/eggs may not survive cryopreservation process, which means on thawing nothing or lesser quantity will be retrieved.</p>
-->



</div>  
</div>  



<div class="sec21">
 <label for="Senior Embryologist">Senior Embryologist</label>
  <input type="text" class="SeniorEmbryologist" name="senior_embryologist" readonly="" value="<?php echo isset($select_result['senior_embryologist'])?$select_result['senior_embryologist']:""; ?>">
</div>
<div class="sec2">
  
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
    
</div> 
<input type="submit" name="submit" value="submit">
</form>

<div class="row" id="print_this_section" style="display:none;">
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="https://indiaivf.website/assets/images/india-ivf-logo.webp"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">Department of Embryology</h3><strong>Embryo Transfer Dischrge Summary</strong></td>
   </tr>
</table>
    
<form action="" enctype='multipart/form-data' method="post">     
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="12" width="100%" style="border:1px solid;padding:5px;">
<strong>Center: <?php echo isset($select_result['center'])?$select_result['center']:""; ?></strong>
</td>
</tr>

<tr style="background: #b3b9b7;">
<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of Admission: <?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?></strong>
</td>
<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of Discharge: <?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?></strong>
</td>
</tr>

<tr style="background: #b3b9b7;">
    <td colspan="6" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Male Partner</strong>
	</td>
  </tr>
<tr style="background: #b3b9b7;">
<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></strong>
</td>
<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
<strong>Male Partner : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td colspan="6" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?></strong>
</td>
</tr>

<tr>
<td colspan="12" width="100%" style="border:1px solid;padding:5px;">
<strong><p>Name of Procedure</p></strong>
</td>
</tr>
<tr>
<td colspan="2" width="17%" style="border:1px solid;padding:5px;">
<p>Fresh Embryo Transfer </p>
<input type="radio" id="Embryo_Transfer" name="Embryo_Transfer" value="Yes" <?php if(isset($select_result['Embryo_Transfer']) && $select_result['Embryo_Transfer']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Embryo_Transfer" name="Embryo_Transfer" value="No" <?php if(isset($select_result['Embryo_Transfer']) && $select_result['Embryo_Transfer'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" width="17%" style="border:1px solid;padding:5px;">
<p>FET</p>
<input type="radio" id="FET" name="FET" value="Yes" <?php if(isset($select_result['FET']) && $select_result['FET']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="FET" name="FET" value="No" <?php if(isset($select_result['FET']) && $select_result['FET'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" width="17%" style="border:1px solid;padding:5px;">
<p>Blastocyst</p>
<input type="radio" id="Blastocyst" name="Blastocyst" value="Yes" <?php if(isset($select_result['Blastocyst']) && $select_result['Blastocyst']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Blastocyst" name="Blastocyst" value="No" <?php if(isset($select_result['Blastocyst']) && $select_result['Blastocyst'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" width="17%" style="border:1px solid;padding:5px;">
<p>Laser Assisted Hatching</p>
<input type="radio" id="Laser_Assisted" name="Laser_Assisted" value="Yes" <?php if(isset($select_result['Laser_Assisted']) && $select_result['Laser_Assisted']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Laser_Assisted" name="Laser_Assisted" value="No" <?php if(isset($select_result['Laser_Assisted']) && $select_result['Laser_Assisted'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>
<td colspan="2" width="16%" style="border:1px solid;padding:5px;">
<p>Embryo Glue</p>
<input type="radio" id="Embryo_Glue" name="Embryo_Glue" value="Yes" <?php if(isset($select_result['Embryo_Glue']) && $select_result['Embryo_Glue']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Embryo_Glue" name="Embryo_Glue" value="No" <?php if(isset($select_result['Embryo_Glue']) && $select_result['Embryo_Glue'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>
<td colspan="2" width="16%" style="border:1px solid;padding:5px;">
<strong>Date of procedure:   <input type="date" class="date_of_procedure" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>">   </strong>
</td>
</tr>

</tbody>
</table> 



<div class="sec2">
<table width="100%" class="vb45rt">
<tbody>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<h3 style="text-align: left; margin-left: 10px;">Embryo transfer details:</h3> </td>
</tr>

<tr>
<!--<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<label for="fertilization">Day 1 fertilization status </label>
<textarea name="fertilization_status" style="width:100%; height:80px!important"  > <?php echo isset($select_result['fertilization_status'])?$select_result['fertilization_status']:""; ?> </textarea>
</td>-->
<td width="50%" style="border:1px solid;padding:5px;">
<label for="embryo-transfer">Detail of fresh /frozen embryo transfer with number and grading</label>
<textarea name="fresh_embryo_transfer" style="width:100%; height:80px!important"  > <?php echo isset($select_result['fresh_embryo_transfer'])?$select_result['fresh_embryo_transfer']:""; ?> </textarea>

</td>
</tr>
<!--
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<label for="fertilization">Date of cryopreservation of embryos with number and grading</label>
<textarea name="date_of_cryopreservation" style="width:100%; height:80px!important"  > <?php echo isset($select_result['date_of_cryopreservation'])?$select_result['date_of_cryopreservation']:""; ?> </textarea>
</td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<label for="Storage renewal">Storage renewal date:</label>
<?php echo isset($select_result['storage_renewal_date'])?$select_result['storage_renewal_date']:""; ?>
</td>
</tr>

<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<label for="embryo-transfer">Remaining embryos after transfer</label>
<textarea name="embryos_after_transfer" style="width:100%; height:50px!important"  > <?php echo isset($select_result['embryos_after_transfer'])?$select_result['embryos_after_transfer']:""; ?> </textarea>
</td>
</tr>
-->
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<input type="radio" id="easy" name="embryo_transfer_process" value="Easy embryo transfer" <?php if(isset($select_result['embryo_transfer_process']) && $select_result['embryo_transfer_process'] == "Easy embryo transfer"){ echo "checked";} ?>>
<label for="easy">Easy  embryo transfer</label><br>
<input type="radio" id="Difficult" name="embryo_transfer_process" value="Difficult embryo transfer" <?php if(isset($select_result['embryo_transfer_process'])  && $select_result['embryo_transfer_process']  == "Difficult embryo transfer"){ echo "checked";} ?>>
<label for="Difficult">Difficult embryo transfer</label><br>
</td>
</tr>

<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p>ETG-</p>
<input type="radio" id="ETG" name="etg" value="A"  <?php if(isset($select_result['etg']) && $select_result['etg'] == "A"){echo 'checked="checked"'; }?> >
<label for="ETG">A</label>
<input type="radio" id="ETG" name="etg" value="B"  <?php if(isset($select_result['etg']) && $select_result['etg'] == "B"){echo 'checked="checked"'; }?>>
<label for="ETG">B</label>
<input type="radio" id="ETG" name="etg" value="C"  <?php if(isset($select_result['etg']) && $select_result['etg'] == "C"){echo 'checked="checked"'; }?>>
<label for="ETG">C</label>
</td>
</tr>
<!--
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<p style="margin:10px 0px;">Note: embryos/eggs may not survive cryopreservation process, which means on thawing nothing or lesser quantity will be retrieved.</p>
</td>
</tr>-->
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
 <label for="Senior Embryologist">Senior Embryologist</label>
  <?php echo isset($select_result['senior_embryologist'])?$select_result['senior_embryologist']:""; ?>
</td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
</td>
</tr>
</tbody>
</table> 
</div>   
</form>
</div> 
</div>
<style>
input[type=checkbox], input[type=radio] {
    opacity: 1 !important;
    left: 0 !important;
    position: unset !important;
    margin: 9px !important;
}
select#center {
    display: block!important;
}
.sec3 {
    border: 1px solid #000;
    padding: 5px;
}
.sec21 {
    border: 1px solid #000;
}
.sec21 p {
    margin: 20px;
    padding: 2px 10px;
}
.sec2 {
    border: 1px solid #000;
}
.sec2 p {
    margin: 0px;
    padding: 2px 10px;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
td {
  border: 1px solid #000;
  text-align: center;
  padding: 5px; 
}
.ga-pro h3 {
      text-align: center;
    font-size: 25px;
}
.ga-pro h4 {
      text-align: center;
    font-size: 20px;
}
form {
    padding-left: 10px;
    margin-bottom: 4px;
}
.nb56ty {
    border: 1px solid #000;
}
.nb56ty input {
    width: 100%;
}
.vb45rt td {text-align: left; padding-left: 10px;}
</style>    