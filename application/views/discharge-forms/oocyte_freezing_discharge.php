<?php 
$all_method =&get_instance();
$appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
           
        // चेक करें कि क्या इस iic_id का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `hms_oocyte_freezing_discharge` WHERE iic_id='$iic_id'";
        $select_result = run_select_query($sql);
        
        // अगर डेटा मौजूद नहीं है, सिर्फ तभी INSERT करें
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_oocyte_freezing_discharge` SET ";
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
    $sql = "SELECT * FROM `hms_oocyte_freezing_discharge` WHERE iic_id='$iic_id'";
    $select_result = run_select_query($sql);
    
    $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$iic_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
    
    $sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result2['appoitment_for']."'";
    $select_result3 = run_select_query($sql3);
?>

<form action="" enctype='multipart/form-data' method="post">

 <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $iic_id;?>" class="form" name="iic_id">
 <input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">
 
<div class="ga-pro">
<h3>Discharge Summary</h3>
<h4>Oocyte Freezing Dischrge Summary</h4>

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
  <input type="date" class="Admission" name="date_of_addmission" value="<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>"  >
 </div>
     
<div style="float: right; margin-bottom: 10px;">
  <label for="Discharge">Date of Discharge:</label>
  <input type="date" class="Discharge" name="date_of_discharge" value="<?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?>">
 </div>

<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
    <td colspan="2" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	<td colspan="2" width="100%" style="border:1px solid;padding:5px;">
	<strong>Details of Male Partner</strong>
	</td>
  </tr>
<tr style="background: #b3b9b7;">
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></strong>
</td>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" width="57%">
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td width="42%">
<strong>Male Partner :  <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" width="57%">
<strong>Age:  <?php echo $patient_data['wife_age']; ?> Year</strong>
</td>
<td width="42%">
<strong>Age: <?php echo $patient_data['husband_age']; ?> Year</strong>
</td>
</tr>

<tr>
<td colspan="4">
<strong>Name of Procedure :
<textarea name="name_of_procedure" id="name_of_procedure"><?php echo isset($select_result['name_of_procedure'])?$select_result['name_of_procedure']:""; ?></textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>No. of oocytes retrieved
<textarea id="oocytes" name="no_of_oocytes_retrieved" id="no_of_oocytes_retrieved"><?php echo isset($select_result['no_of_oocytes_retrieved'])?$select_result['no_of_oocytes_retrieved']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>Oocyte number and grading on day of freezing
<textarea name="day_of_freezing" id="freezing"><?php echo isset($select_result['day_of_freezing'])?$select_result['day_of_freezing']:""; ?></textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="4">
<strong>Renewal date of embryo freezing
<input type="date" id="Renewal" style="width: 100%;" name="renewal_date" value="<?php echo isset($select_result['renewal_date'])?$select_result['renewal_date']:""; ?>">
</strong>
</td>
</tr>

<tr>
<td colspan="4">
<p style="margin:10px 0px;">Note: Oocyte freezing may not survive cryopreservation process, which means on thawing nothing, or lesser quantity will be retrieved.</p>
</td>
</tr>

<tr>
<td colspan="4">
<strong>Senior Embryologist
<input type="text" class="SeniorEmbryologist" name="senior_embryologist" readonly="" value="<?php echo $_SESSION['logged_embryologist']['name']?>">
</strong>
</td>
</tr>

<tr>
<td colspan="4">
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
</td>
</tr>
</tbody>
</table> 
  
</div>  

<div class="col-sm-2" style="margin-top: 10px;">
<input type="submit" name="submit" value="submit" class="btn btn-secondary">    
</div>

</form>
  
<div class="row" id="print_this_section" style="display:none;">
<form action="" enctype='multipart/form-data' method="post">
<div class="ga-pro">
<table style="border:1px solid;width:100%;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="https://indiaivf.website/assets/images/india-ivf-logo.webp"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">Department of Embryology</h3><strong>Oocyte Freezing Dischrge Summary</strong></td>
   </tr>
</table>

<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="4" width="100%" style="border:1px solid;padding:5px;">
<strong>Center <?php echo isset($select_result['center'])?$select_result['center']:""; ?></strong>
</td>
</tr>
<tr style="background: #b3b9b7;">
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of Admission: <?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?></strong>
</td>
<td width="50%" style="border:1px solid;padding:5px;">
<strong>Date of Discharge: <?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?></strong>
</td>
</tr>

<tr style="background: #b3b9b7;">
    <td colspan="2" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Male Partner</strong>
	</td>
  </tr>
<tr style="background: #b3b9b7;">
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></strong>
</td>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td width="50%" style="border:1px solid;padding:5px;">
<strong>Male Partner :  <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Age:  <?php echo $patient_data['wife_age']; ?> Year</strong>
</td>
<td width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?> Year</strong>
</td>
</tr>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>Name of Procedure :
<textarea name="name_of_procedure" id="name_of_procedure" style="width:100%;height:80px;" > <?php echo isset($select_result['name_of_procedure'])?$select_result['name_of_procedure']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>No. of oocytes retrieved
<textarea id="oocytes" name="no_of_oocytes_retrieved" id="no_of_oocytes_retrieved" style="width:100%;height:80px;"> <?php echo isset($select_result['no_of_oocytes_retrieved'])?$select_result['no_of_oocytes_retrieved']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>Oocytes number and grading on day of freezing
<textarea style="width:100%;height:80px;"> <?php echo isset($select_result['day_of_freezing'])?$select_result['day_of_freezing']:""; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>Renewal date of Oocytes freezing -- <?php echo isset($select_result['renewal_date'])?$select_result['renewal_date']:""; ?></strong>
</td>
</tr>

<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<p style="margin:10px 0px;">Note: Oocytes freezing may not survive cryopreservation process, which means on thawing nothing, or lesser quantity will be retrieved.</p>
</td>
</tr>

<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>Senior Embryologist : <?php echo isset($select_result['senior_embryologist'])?$select_result['senior_embryologist']:""; ?></strong>
</td>
</tr>

<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
</td>
</tr>
</tbody>
</table> 
       
</div>  
</form>
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
select#center {
    display: block!important;
    }
textarea {
    height: 60px!important;
	width:100%;
}	
</style>    