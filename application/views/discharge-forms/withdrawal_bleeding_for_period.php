<?php
    $appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        if(!empty($_POST['physical_examination']) && isset($_POST['physical_examination'])){
            $_POST['physical_examination'] = implode(',', $_POST['physical_examination']);
        }
    
        // चेक करें कि क्या इस iic_id का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `withdrawal_bleeding_for_period` WHERE patient_id='$patient_id'";
        $select_result = run_select_query($sql);
        
        // अगर डेटा नहीं है, तो सिर्फ तभी INSERT करें
        if(empty($select_result)){
            
            // mysql query to insert data
            $query = "INSERT INTO `withdrawal_bleeding_for_period` SET ";
            $sqlArr = array();
            foreach( $_POST as $key => $value ) {
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }   
            $query .= implode(',' , $sqlArr);
            
            $result = run_form_query($query);     
        
            if($result){
                header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Form saved successfully!').'&t='.base64_encode('success'));
                die();
            } else {
                header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
                die();
            }
            
        } else {
            // अगर डेटा पहले से मौजूद है, तो UPDATE ना करें, बल्कि सीधा वापस भेज दें
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Data is already saved and cannot be updated!').'&t='.base64_encode('error'));
            die();
        }
    }
  
    // फॉर्म में पुराना डेटा दिखाने के लिए भी सिर्फ iic_id से फेच करें
    $sql = "SELECT * FROM `withdrawal_bleeding_for_period` WHERE patient_id='$patient_id'";
    $select_result = run_select_query($sql);
  
    $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
  
    $sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result2['appoitment_for']."'";
    $select_result3 = run_select_query($sql3);
?>

<div class="ga-pro">
<h3>INDIA IVF CLINIC</h3>    
<form action="" enctype='multipart/form-data' method="post">
  <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">
  <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
  <?php $physical = array();
    if(!empty($select_result['physical_examination'])){
        $physical = explode(',',$select_result['physical_examination']);
    }
  ?>
<div style="float: left; margin-bottom: 10px;">
  <label for="Admission">Center</label>
 <select name="center" required="" class="empty-field" id="center">
<option value="<?php echo isset($select_result['center'])?$select_result['center']:""; ?>"><?php echo isset($select_result['center'])?$select_result['center']:""; ?></option>
<option value="India IVF Fertility Fortis">India IVF Fertility Fortis</option>
<option value="India IVF Fertility Gurgaon">India IVF Fertility Gurgaon</option>
<option value="India IVF Fertility Noida">India IVF Fertility Noida</option>
</select> 
</div>      
<div style="float: right; margin-bottom: 10px;">
  <label for="Discharge">Date of Discharge:</label>
  <input type="date" class="Discharge" name="date_of_discharge" value="<?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?>"> 
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
<strong>Blood group: <input type="text" name="female_blood_group" value="<?php echo isset($select_result['female_blood_group'])?$select_result['female_blood_group']:""; ?>"></strong>
</td>
<td width="42%">
<strong>Blood group: <input type="text" name="husband_blood_group" value="<?php echo isset($select_result['husband_blood_group'])?$select_result['husband_blood_group']:""; ?>"></strong>
</td>
</tr>
</tbody>
</table> 
<h3>WITHDRAWAL BLEEDING FOR PERIOD NOT COME IN NON PREGNANT FEMALE</h3> 
  <br>
<div class="sec2">
  <input type="checkbox" class="checkbox" id="Estrabet" name="physical_examination[]" value="Estrade" <?php if(!empty($select_result['physical_examination']) && in_array('Estrade', $physical)){echo "checked";}?>>
  <label for="Estrabet">Tab Estrade 2 mg - BD for - 10 days - after meals</label>
  <input type="checkbox" class="checkbox" id="Meprate" name="physical_examination[]" value="Meprate" <?php if(!empty($select_result['physical_examination']) && in_array('Meprate', $physical)){echo "checked";}?>>
  <label for="Estrabet">Tab Meprate 10 mg - BD for - 10 days - after meals</label>
<input type="checkbox" class="checkbox" id="Other" name="physical_examination[]" value="Other" <?php if(!empty($select_result['physical_examination']) && in_array('Other', $physical)){echo "checked";}?>>
  <label for="Estrabet">Other</label>
 <textarea name="Other" style="width:100%; height:150px;" > <?php echo isset($select_result['Other'])?$select_result['Other']:""; ?> </textarea>
<br>
<div class="sec2">

<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
    
</div> 
<div class="sec2">
  
<label for="other">AFTER STOPPING THESE MEDICINES WILL GET PERIODS WITHING 7 TO 10 DAYS</label>
    
</div> 
<input type="submit" name="submit" value="submit">
</form>
</div>  
</div>  

<div class="row" id="print_this_section" style="display:none;">
<div class="ga-pro"> 
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="https://indiaivf.website/assets/images/india-ivf-logo.webp"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">INDIA IVF CLINIC</h3></td>
</tr>
</table>
      
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Center : <?php echo isset($select_result['center'])?$select_result['center']:""; ?></strong>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
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
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Husband&rsquo;s name : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Blood group: <?php echo isset($select_result['female_blood_group'])?$select_result['female_blood_group']:""; ?></strong>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Blood group: <?php echo isset($select_result['husband_blood_group'])?$select_result['husband_blood_group']:""; ?></strong>
</td>
</tr>

<tr>
<td colspan="4" style="width:50%; border:1px solid; padding:5px;">
<h3>WITHDRAWAL BLEEDING FOR PERIOD NOT COME IN NON PREGNANT FEMALE</h3>
</td>
</tr>

<tr>
<td colspan="4" style="width:50%; border:1px solid; padding:5px;">
<input type="checkbox" class="checkbox" id="Estrabet" name="physical_examination[]" value="Estrade" <?php if(!empty($select_result['physical_examination']) && in_array('Estrade', $physical)){echo "checked";}?>>
  <label for="Estrabet">Tab Estrade 2 mg - BD for - 10 days - after meals</label>
  <input type="checkbox" class="checkbox" id="Meprate" name="physical_examination[]" value="Meprate" <?php if(!empty($select_result['physical_examination']) && in_array('Meprate', $physical)){echo "checked";}?>>
  <label for="Estrabet">Tab Meprate 10 mg - BD for - 10 days - after meals</label>
<input type="checkbox" class="checkbox" id="Other" name="physical_examination[]" value="Other" <?php if(!empty($select_result['physical_examination']) && in_array('Other', $physical)){echo "checked";}?>>
   <label for="Estrabet">Other</label>
</td>
</tr>

<tr>
<td colspan="4" style="width:100%; border:1px solid; padding:5px;">
<textarea style="width:100%; height:100px;" > <?php echo isset($select_result['Other'])?$select_result['Other']:""; ?> </textarea>
</td>
</tr>

<tr>
<td colspan="4" style="width:100%; border:1px solid; padding:5px;">
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
</td>
</tr>

<tr>
<td colspan="4" style="width:100%; border:1px solid; padding:5px;">
<label for="other">AFTER STOPPING THESE MEDICINES WILL GET PERIODS WITHING 7 TO 10 DAYS</label>
</td>
</tr>
</tbody>
</table>  
</div>  
</div>  
<style>
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
.sec2 ul li {    margin-bottom: 5px;}
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
.sec2 {
  border: 1px solid #000;
  padding: 34px 12px;
}
select#center {
    display: block!important;
}
</style>    