<?php 
$all_method =& get_instance();
$appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        // चेक करें कि क्या इस iic_id का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `intra_uterine_insemination_discharge_summary` WHERE iic_id='$iic_id'";
        $select_result = run_select_query($sql); 
        
        // अगर डेटा नहीं है, तो सिर्फ तभी INSERT करें
        if(empty($select_result)){
            
            // mysql query to insert data
            $query = "INSERT INTO `intra_uterine_insemination_discharge_summary` SET ";
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
            // अगर डेटा पहले से मौजूद है, तो UPDATE ना करें, बल्कि सीधा वापस भेज दें
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Data is already saved and cannot be updated!').'&t='.base64_encode('error'));
            die();
        }
    }
    
    // फॉर्म में पुराना डेटा दिखाने के लिए सिर्फ iic_id से फेच करें
    $sql = "SELECT * FROM `intra_uterine_insemination_discharge_summary` WHERE iic_id='$iic_id'";
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
   <input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">
<input type="hidden" value="<?php echo $iic_id; ?>" class="form" name="iic_id">
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
<strong>IIC ID: <?php echo $iic_id; ?></strong>
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
<strong>Age: <?php echo $patient_data['husband_age']; ?></strong>
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
<td width="50%">
<strong>Name of Procedure : IUI (INTRA UTERINE INSEMINATION)</strong>
</td>
<td colspan="2" width="50%">
<strong>Date of procedure:  <input type="date" class="date_of_procedure" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>">   </strong>


</td>
</tr>
<tr>
<td width="50%">
<strong>Appearance of Sample</strong>
</td>
<td colspan="2" width="50%">
<textarea name="Appearance" style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Appearance'])?$select_result['Appearance']:""; ?> </textarea>
</td>
</tr>
<tr>
<td width="50%">
<strong>Sperm Preparation Method</strong>
</td>
<td colspan="2" width="50%">
<textarea name="Preparation" style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Preparation'])?$select_result['Preparation']:""; ?> </textarea>
</td>
</tr>

</tbody>
</table> 
<div class="sec2">
<h3>SEMEN PARAMETERS</h3>

<table>
<tbody>
<tr>
<td width="158">

</td>
<td width="61">
<p>count (millions/ml)</p>
</td>
<td width="83">
<p>Motility ( % )</p>
</td>
<td width="83">
<p>Morphology</p>
</td>
</tr>
<tr>
<td width="158">
<p>Prewash</p>
</td>
<td width="158">
<textarea name="Prewash_count" style="width:100%; height:100px!important;"  > <?php echo isset($select_result['Prewash_count'])?$select_result['Prewash_count']:""; ?> </textarea>
</td>
<td width="158">
<textarea name="Prewash_Motility" style="width:100%; height:100px!important;"  > <?php echo isset($select_result['Prewash_Motility'])?$select_result['Prewash_Motility']:""; ?> </textarea>
</td>
<td width="158">
<textarea name="Prewash_Morphology" style="width:100%; height:100px!important;"  > <?php echo isset($select_result['Prewash_Morphology'])?$select_result['Prewash_Morphology']:""; ?> </textarea>
</td>
</tr>
<tr>
<td width="158">
<p>Post wash</p>
</td>
<td width="158">
<textarea name="Post_count" style="width:100%; height:100px!important;"  > <?php echo isset($select_result['Post_count'])?$select_result['Post_count']:""; ?></textarea>
</td>
<td width="158">
<textarea name="Post_Motility" style="width:100%; height:100px!important;"  > <?php echo isset($select_result['Post_Motility'])?$select_result['Post_Motility']:""; ?> </textarea>
</td>
<td width="158">
<textarea name="Post_Morphology" style="width:100%; height:100px!important;"  > <?php echo isset($select_result['Post_Morphology'])?$select_result['Post_Morphology']:""; ?></textarea>
</td>
</tr>
</tbody>
</table>
</div>  
</div>  
  


<div class="sec21">
 <label for="Senior Embryologist">Senior Embryologist</label>
  <input type="text" class="SeniorEmbryologist" name="Senior_Embryologist" readonly="" value="<?php echo $_SESSION['logged_embryologist']['name']?><?php echo isset($select_result['Senior_Embryologist'])?$select_result['Senior_Embryologist']:""; ?>"><br>
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
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">Discharge Summary</h3><strong>Department of Embryology IUI</strong></td>
</tr>
</table>
<form action="" enctype='multipart/form-data' method="post">
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Date of Admission: <?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?></strong>
</td>
<td style="width:50%; border:1px solid; padding:5px;">
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
<strong>IIC ID: <?php echo $iic_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td style="width:50%; border:1px solid; padding:5px;">
<strong>Husband&rsquo;s name : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td style="width:50%; border:1px solid; padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?></strong>
</td>
</tr>

<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Provisional Diagnosis:
 <textarea  style="width:100%; height:100px;" > <?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?> </textarea>
</strong>
</td>
<td style="width:50%; border:1px solid; padding:5px;">
<strong>Final Diagnosis:
 <textarea style="width:100%; height:100px;" > <?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Name of Procedure : IUI (INTRA UTERINE INSEMINATION)</strong>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<strong>Date of procedure:  <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?></strong>
</td>
</tr>

<tr>
<td colspan="2"style="width:50%; border:1px solid; padding:5px;">
<strong>Appearance of Sample</strong>
<textarea style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Appearance'])?$select_result['Appearance']:""; ?> </textarea>
</td>
<td colspan="2" width="50%" style="width:50%; border:1px solid; padding:5px;">
<strong>Sperm Preparation Method</strong>
<textarea style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Preparation'])?$select_result['Preparation']:""; ?> </textarea>
</td>
</tr>

</tbody>
</table> 

<div class="sec2">
<table width="100%">
<tbody>
<tr>
<td colspan="4"style="width:50%; border:1px solid; padding:5px;">
<h3>SEMEN PARAMETERS</h3>
</td>
</tr>

<tr>
<td width="158" colspan="1"style="width:25%; border:1px solid; padding:5px;"></td>
<td width="61" colspan="1"style="width:25%; border:1px solid; padding:5px;">
<p>count (millions/ml)</p>
</td>
<td width="83" colspan="1"style="width:25%; border:1px solid; padding:5px;">
<p>Motility ( % )</p>
</td>
<td width="83" colspan="1"style="width:25%; border:1px solid; padding:5px;">
<p>Morphology</p>
</td>
</tr>
<tr>
<td width="158" colspan="1"style="width:25%; border:1px solid; padding:5px;">
<p>Prewash</p>
</td>
<td width="158" colspan="1"style="width:25%; border:1px solid; padding:5px;">
<textarea name="Prewash_count" style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Prewash_count'])?$select_result['Prewash_count']:""; ?> </textarea>
</td>
<td width="158" colspan="1"style="width:25%; border:1px solid; padding:5px;">
<textarea style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Prewash_Motility'])?$select_result['Prewash_Motility']:""; ?> </textarea>
</td>
<td colspan="1"style="width:25%; border:1px solid; padding:5px;">
<textarea style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Prewash_Morphology'])?$select_result['Prewash_Morphology']:""; ?> </textarea>
</td>
</tr>
<tr>
<td colspan="1"style="width:25%; border:1px solid; padding:5px;">
<p>Post wash</p>
</td>
<td colspan="1"style="width:25%; border:1px solid; padding:5px;">
<textarea style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Post_count'])?$select_result['Post_count']:""; ?></textarea>
</td>
<td colspan="1"style="width:25%; border:1px solid; padding:5px;">
<textarea style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Post_Motility'])?$select_result['Post_Motility']:""; ?> </textarea>
</td>
<td colspan="1"style="width:25%; border:1px solid; padding:5px;">
<textarea name="Post_Morphology" style="width:100%; height:80px!important;"  > <?php echo isset($select_result['Post_Morphology'])?$select_result['Post_Morphology']:""; ?></textarea>
</td>
</tr>

<tr>
<td colspan="4"style="width:100%; border:1px solid; padding:5px;">
<label for="Senior Embryologist">Senior Embryologist</label> : <?php echo isset($select_result['Senior_Embryologist'])?$select_result['Senior_Embryologist']:""; ?>
</td>
</tr>

<tr>
<td colspan="4"style="width:100%; border:1px solid; padding:5px;">
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