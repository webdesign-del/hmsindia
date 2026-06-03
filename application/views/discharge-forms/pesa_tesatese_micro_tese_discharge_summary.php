<?php 
$all_method =& get_instance();
$appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        // चेक करें कि क्या इस iic_id का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `pesa_tesatese_micro_tese_discharge_summary` WHERE patient_id='$patient_id'";
        $select_result = run_select_query($sql);
        
        if(!empty($_POST['procedures']) && isset($_POST['procedures'])){
            $_POST['procedures'] = implode(',', $_POST['procedures']);
        }
        
        // अगर डेटा मौजूद नहीं है, सिर्फ तभी INSERT करें
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `pesa_tesatese_micro_tese_discharge_summary` SET ";
            $sqlArr = array();
            
            foreach( $_POST as $key => $value ) {
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
    $sql = "SELECT * FROM `pesa_tesatese_micro_tese_discharge_summary` WHERE patient_id='$patient_id'";
    $select_result = run_select_query($sql);
    
    $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
    
    $sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result2['appoitment_for']."'";
    $select_result3 = run_select_query($sql3);
?>

<?php 
    $procedures = array();
    if(!empty($select_result['procedures'])){
        $procedures = explode(',', $select_result['procedures']);
    }
?>

<form action="" enctype='multipart/form-data' method="post">
    <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
    <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">
	  
    
<div class="ga-pro">
<h3>Discharge Summary</h3>
<h4>Department of Embryology</h4>

<div class="col-sm-12 col-md-12">	
<div class="col-sm-12 col-md-4" style="margin-bottom: 10px;">
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
<div class="col-sm-12 col-md-2" style="margin-bottom: 10px;">
  <label for="Admission">Date of Admission:</label>
  <input type="date" class="Admission" name="date_of_addmission" value="<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>">
 </div>
<div class="col-sm-12 col-md-2" style="margin-bottom: 10px;">
  <label for="Admission">Admission Time:</label>
  <input type="time" class="Admission" name="time_of_addmission" value="<?php echo isset($select_result['time_of_addmission'])?$select_result['time_of_addmission']:""; ?>">
 </div>   
<div class="col-sm-12 col-md-2" style="margin-bottom: 10px;">
  <label for="Discharge">Date of Discharge:</label>
  <input type="date" class="Discharge" name="date_of_discharge" value="<?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?>">
 </div> 
<div class="col-sm-12 col-md-2" style="margin-bottom: 10px;">
  <label for="Discharge">Discharge Time:</label>
  <input type="time" class="Discharge" name="time_of_discharge" value="<?php echo isset($select_result['time_of_discharge'])?$select_result['time_of_discharge']:""; ?>">
 </div>  
</div>
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
    <td colspan="4" width="100%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
  </tr>
<tr style="background: #b3b9b7;">
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></strong>
</td>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" width="57%">
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td width="42%">
<strong>Husband&rsquo;s name : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" width="57%">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td width="42%">
<strong>Age: <?php echo $patient_data['husband_age']; ?> </strong>
</td>
</tr>

<tr>
<td colspan="2" width="57%">
<strong>Provisional Diagnosis:

 <textarea name="female_issues" style="width:100%; height:150px;" > <?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?> </textarea>
</strong>
</td>
<td width="42%">
<strong>Final Diagnosis:

 <textarea name="male_issues" style="width:100%; height:150px;" > <?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?> </textarea>
</strong>

</td>
</tr>
<tr>
<td colspan="2" width="57%">
<strong>Medical complication:
<textarea name="female_complication" style="width:100%; height:150px;"  > <?php echo isset($select_result['female_complication'])?$select_result['female_complication']:""; ?> </textarea>
</strong>
</td>
<td width="42%">
<strong>Medical complication: 

<textarea name="male_complication" style="width:100%; height:150px;"  > <?php echo isset($select_result['male_complication'])?$select_result['male_complication']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td width="50%">
<strong>Name of Procedure : <input type="checkbox" class="PESA" value="PESA"  name="procedures[]" <?php if(!empty($select_result['procedures']) && in_array('PESA',$procedures)){echo "checked";}?>> PESA <input type="checkbox" class="TESA" name="procedures[]" value="TESA" <?php if(!empty($select_result['procedures']) && in_array('TESA',$procedures)){echo "checked";}?>> TESA <input type="checkbox" class="TESE" name="procedures[]" value="TESE" <?php if(!empty($select_result['procedures']) && in_array('TESE',$procedures)){echo "checked";}?>> TESE <input type="checkbox" class="MICROTESE" name="procedures[]" value="MICRO TESE" <?php if(!empty($select_result['procedures']) && in_array('MICRO TESE',$procedures)){echo "checked";}?>> MICRO TESE</strong>
</td>
<td colspan="2" width="50%">
<strong>Date of procedure: <input type="date" class="procedure" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>"></strong>
</td>
</tr>
</tbody>
</table> 
<div class="sec2">
<textarea name="Rt" style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Rt'])?$select_result['Rt']:""; ?></textarea>
<label for="Rt">sperms seen /not seen Right Testes</label><br>
 <textarea name="Lt" style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Lt'])?$select_result['Lt']:""; ?></textarea>
 <label for="Lt">sperms seen /not seen Left Testes </label><br>
 
</div>  
</div>  

<div class="sec21">
<label for="Senior Embryologist">Senior Embryologist</label>
  <input type="hidden" class="SeniorEmbryologist" name="Senior_Embryologist" readonly="" value="<?php echo $_SESSION['logged_embryologist']['name']?>">
  <input type="text" class="SeniorEmbryologist" name="" readonly="" value="<?php echo isset($select_result['Senior_Embryologist'])?$select_result['Senior_Embryologist']:""; ?>">
</div>
<div class="sec2">
  
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
    
</div> 
<input type="submit"  name="submit" value="submit">
</form>

<div class="row" id="print_this_section" style="display:none;">
<form action="" enctype='multipart/form-data' method="post">       
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="https://indiaivf.website/assets/images/india-ivf-logo.webp"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">Discharge Summary</h3><strong>PESA / TESA / TESE / MICRO TESE</strong></td>
   </tr>
</table>

<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="4" style="width:100%;border:1px solid;padding:5px;">
 <strong>Date of Admission: <?php echo isset($select_result['center'])?$select_result['center']:""; ?></strong>
</td>
</tr>

<tr style="background: #b3b9b7;">
<td colspan="2" style="width:50%;border:1px solid;padding:5px;">
 <strong>Date of Admission: <?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?></strong>
</td>
<td style="width:50%;border:1px solid;padding:5px;">
<strong>Date of Discharge: <?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?></strong>
</td>
</tr>

<tr style="background: #b3b9b7;">
    <td colspan="4" width="100%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
  </tr>
<tr style="background: #b3b9b7;">
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></strong>
</td>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%;border:1px solid;padding:5px;" >
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td style="width:50%;border:1px solid;padding:5px;">
<strong>Husband&rsquo;s name : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%;border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td style="width:50%;border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?> </strong>
</td>
</tr>

<tr>
<td colspan="2" style="width:50%;border:1px solid;padding:5px;">
<strong>Provisional Diagnosis:

 <textarea name="female_issues" style="width:100%; height:150px;" > <?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?> </textarea>
</strong>
</td>
<td style="width:50%;border:1px solid;padding:5px;">
<strong>Final Diagnosis:

 <textarea name="male_issues" style="width:100%; height:150px;" > <?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?> </textarea>
</strong>

</td>
</tr>
<tr>
<td colspan="2" style="width:50%;border:1px solid;padding:5px;">
<strong>Medical complication:
<textarea name="female_complication" style="width:100%; height:100px;"  > <?php echo isset($select_result['female_complication'])?$select_result['female_complication']:""; ?> </textarea>
</strong>
</td>
<td style="width:50%;border:1px solid;padding:5px;">
<strong>Medical complication: 

<textarea name="male_complication" style="width:100%; height:100px;"  > <?php echo isset($select_result['male_complication'])?$select_result['male_complication']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%;border:1px solid;padding:5px;">
<strong>Name of Procedure : <input type="checkbox" class="PESA" value="PESA"  name="procedures[]" <?php if(!empty($select_result['procedures']) && in_array('PESA',$procedures)){echo "checked";}?>> PESA <input type="checkbox" class="TESA" name="procedures[]" value="TESA" <?php if(!empty($select_result['procedures']) && in_array('TESA',$procedures)){echo "checked";}?>> TESA <input type="checkbox" class="TESE" name="procedures[]" value="TESE" <?php if(!empty($select_result['procedures']) && in_array('TESE',$procedures)){echo "checked";}?>> TESE <input type="checkbox" class="MICROTESE" name="procedures[]" value="MICRO TESE" <?php if(!empty($select_result['procedures']) && in_array('MICRO TESE',$procedures)){echo "checked";}?>> MICRO TESE</strong>
</td>
<td colspan="2" style="width:50%;border:1px solid;padding:5px;">
<strong>Date of procedure: <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?></strong>
</td>
</tr>

<tr>
<td colspan="2" style="width:100%;border:1px solid;padding:5px;">
<strong>sperms seen /not seen Right Testes : <textarea name="Rt" style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Rt'])?$select_result['Rt']:""; ?></textarea></strong>
</td>
<td colspan="2" style="width:100%;border:1px solid;padding:5px;">
<strong>sperms seen /not seen Left Testes : <textarea name="Lt" style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Lt'])?$select_result['Lt']:""; ?></textarea></strong>
</td>
</tr>

<tr>
<td colspan="4" style="width:100%;border:1px solid;padding:5px;">
<label for="Senior Embryologist">Senior Embryologist</label>
<?php echo isset($select_result['Senior_Embryologist'])?$select_result['Senior_Embryologist']:""; ?>
</td>
</tr>

<tr>
<td colspan="4" style="width:100%;border:1px solid;padding:5px;">
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>

</td>
</tr>
</tbody>
</table> 
</div>  
</form>
</div>
<style>
select#center {
    display: block!important;
}
input[type=checkbox], input[type=radio] {
    opacity: 1 !important;
    left: 0 !important;
    position: unset !important;
    margin: 9px !important;
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
    padding-top: 5px;
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