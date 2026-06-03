<?php
$appoitmented_date = $_GET['appoitmented_date'];
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
      
        $sql = "SELECT * FROM `hcg_report_decipher_negative` WHERE patient_id='$patient_id' and appoitmented_date='$appoitmented_date'";
        $select_result = run_select_query($sql); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hcg_report_decipher_negative` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hcg_report_decipher_negative SET ";
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".$value."'"	;
            }
            $query .= implode(',' , $sqlArr);
            $query .= " WHERE patient_id='$patient_id' and appoitmented_date='$appoitmented_date'";
        }
          $result = run_form_query($query);  
        
        if($result){
         header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Discharge form inserted!').'&t='.base64_encode('success'));
        	die();
        }else{
          header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
		  die();
        }
    }
	$sql = "SELECT * FROM `hcg_report_decipher_negative` WHERE patient_id='$patient_id' and appoitmented_date='$appoitmented_date'";
    $select_result = run_select_query($sql);
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
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

<div style="float: left; margin-bottom: 10px;">
<label for="Center">Center</label>
<select name="center" required="" class="empty-field" id="center">
<option value="<?php echo isset($select_result['center'])?$select_result['center']:""; ?>"><?php echo isset($select_result['center'])?$select_result['center']:""; ?></option>
<option value="India IVF Fertility Fortis">India IVF Fertility Fortis</option>
<option value="India IVF Fertility Gurgaon">India IVF Fertility Gurgaon</option>
<option value="India IVF Fertility Noida">India IVF Fertility Noida</option>
</select>
</div>

     
<div style="float: right; margin-bottom: 10px;">
  <label for="Discharge">Date:</label>
  <input type="date" class="Discharge" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"  name="date">
 </div>

<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
    <td colspan="4" width="100%">
	<strong>Details of Female Partner</strong>
	</td>
  </tr>
<tr style="background: #b3b9b7;">
<td colspan="2" width="50%">
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></strong>
</td>
<td colspan="2" width="50%">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%">
<strong>Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
<td colspan="2" width="50%">
<strong>Husband&rsquo;s name : <?php echo $patient_data['husband_name']; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%">
<strong>Age: <?php echo $patient_data['wife_age']; ?> Year</strong>
</td>
<td colspan="2" width="50%">
<strong>Age: <?php echo $patient_data['husband_age']; ?> Year</strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%">
<strong>Blood group: <input type="text" name="female_blood_group" value="<?php echo isset($select_result['female_blood_group'])?$select_result['female_blood_group']:""; ?>"></strong>
</td>
<td colspan="2" width="50%">
<strong>Blood group: <input type="text" name="husband_blood_group" value="<?php echo isset($select_result['husband_blood_group'])?$select_result['husband_blood_group']:""; ?>"></strong>
</td>
</tr>

</tbody>
</table> 
  <h3>BETA HCG REPORT DECIPHER</h3> 
<div class="sec2">
  
<h4>IF BETA HCG POSITIVE WHAT TO DO</h4>
    <textarea name="what_to_do" style="width:100%;"> <?php echo isset($select_result['what_to_do'])?$select_result['what_to_do']:""; ?> </textarea>

</div>

<div class="sec2">
  
<h5>Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</h5>
    
</div>
   
    <input type="submit" name="submit" value="submit">
    </form>
</div>  

<div class="row" id="print_this_section" style="display:none;">
<div class="ga-pro">

<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="https://indiaivf.website/assets/images/india-ivf-logo.webp"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">INDIA IVF CLINIC</h3></td>
   </tr>
</table>

<form action="" enctype='multipart/form-data' method="post">
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Center : <?php echo isset($select_result['center'])?$select_result['center']:""; ?></strong>
</td>
<td width="50%" style="border:1px solid;padding:5px;">
<strong>Date:<?php echo isset($select_result['date'])?$select_result['date']:""; ?></strong>
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
<td width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Name : <?php echo $patient_data['wife_name']; ?></strong>
</td>
<td width="50%" style="border:1px solid;padding:5px;">
<strong>Husband&rsquo;s name : <?php echo $patient_data['husband_name']; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?> Year</strong>
</td>
<td width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?> Year</strong>
</td>
</tr>

<tr>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Blood group: <?php echo isset($select_result['female_blood_group'])?$select_result['female_blood_group']:""; ?></strong>
</td>
<td width="50%" style="border:1px solid;padding:5px;">
<strong>Blood group: <?php echo isset($select_result['husband_blood_group'])?$select_result['husband_blood_group']:""; ?></strong>
</td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
 <h3>BETA HCG REPORT DECIPHER</h3> 
 </td>
</tr>
<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<strong>BETA HCG REPORT DECIPHER: <textarea name="what_to_do" style="width:100%;height: 100px!important;"> <?php echo isset($select_result['what_to_do'])?$select_result['what_to_do']:""; ?> </textarea></strong>
</td>
</tr>

<tr>
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
 <h5>Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</h5>
 </td>
</tr>
</tbody>
</table> 
    </form>
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
    margin-top:20px;
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
select#center {
    display: block!important;
    }
</style>    