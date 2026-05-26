<?php 
$all_method =& get_instance();
$appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);   
        
        // चेक करें कि क्या इस iic_id का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `hms_semen_freezing_discharge` WHERE iic_id='$iic_id'";
        $select_result = run_select_query($sql);
        
        // अगर डेटा मौजूद नहीं है, सिर्फ तभी INSERT करें
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_semen_freezing_discharge` SET ";
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
    $sql = "SELECT * FROM `hms_semen_freezing_discharge` WHERE iic_id='$iic_id'";
    $select_result = run_select_query($sql);
  
    $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$iic_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
  
    $sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result2['appoitment_for']."'";
    $select_result3 = run_select_query($sql3);  

    $sql_semen_freezing = "Select * from ".$this->config->item('db_prefix')."semen_freezing where patient_id='".$iic_id."'";
    $select_semen_freezing = run_select_query($sql_semen_freezing);   
?>
 <form action="" enctype='multipart/form-data' method="post">
  
  <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $iic_id;?>" class="form" name="iic_id">
  <input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">
<div class="ga-pro">
<h3>Discharge Summary</h3>
<h4>SEMEN FREEZING REPORT</h4>

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
<td colspan="2" width="50%">
<strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td width="50%">
<strong>Male Partner :  <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%">
<strong>Age:  <?php echo $patient_data['wife_age']; ?> Year</strong>
</td>
<td width="50%">
<strong>Age: <?php echo $patient_data['husband_age']; ?> Year</strong>
</td>
</tr>
 <tr>
    <td colspan="2" width="50%">Date of Semen Collection:: <input type="date" id="date" name="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>">  </td>
    <td>Date of Freezing: <input type="date" id="date_of_freezing" name="date_of_freezing" required="" readonly="" style="background: #f1f1f1;" value="<?php echo isset($select_semen_freezing['date'])?$select_semen_freezing['date']:""; ?>">  </td>
 </tr>
 <tr>
<td colspan="4">
<strong>Date of Renewal:
<input type="date" name="date_of_renewal" id="date_of_renewal" value="<?php echo isset($select_result['date_of_renewal'])?$select_result['date_of_renewal']:""; ?>">
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>Number of Vials Frozen:
<textarea name="Freezing" id="Freezing" required="" readonly="" style="background: #f1f1f1;"><?php echo isset($select_semen_freezing['no_0'])?$select_semen_freezing['no_0']:""; ?></textarea>
</strong>
</td>
</tr>
</tbody> 
 </table>

 <table class="jh67yu">
<tbody>
<tr>
<td colspan="6" width="100%">
<p style="font-size: 20px; font-weight: 600; text-decoration: underline;">Semen Parameter Before Freezing:</p>
</td>
</tr>
<tr>
<td colspan="2" width="33%">
<p><strong>Sperm count</strong></p>
</td>
<td width="33%">
<p><input type="text" class="minutes" name="Sperm_minutes" value="<?php echo isset($select_result['Sperm_minutes'])?$select_result['Sperm_minutes']:""; ?>" > Millions/mL</p>
</td>
<td width="33%">
<p> million/ml</p>
</td>
</tr>
<tr>
<td colspan="2" width="33%">
<p><strong>Sperm Motility</strong></p>
</td>
<td width="33%">
<p><input type="text" class="minutes" name="Sperm_Motility" value="<?php echo isset($select_result['Sperm_Motility'])?$select_result['Sperm_Motility']:""; ?>" > %</p>
</td>
<td width="33%">
<p>%</p>
</td>
</tr>
<tr>
<td colspan="2" width="33%">
<p><strong>Sperm Morphology</strong></p>
</td>
<td width="33%">
<input type="text" class="minutes" name="sperm_morphology_val" value="<?php echo isset($select_result['sperm_morphology_val'])?$select_result['sperm_morphology_val']:""; ?>">
</td>
<td width="33%">
<p><strong>% normal forms</strong></p>
</td>
</tr>
</tbody>
</table>

<table class="jhyu67uy">
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
<tr>
<td colspan="4"style="width:100%; border:1px solid; padding:5px;">
<label for="other">Note: Sperm Freezing may not survive cryopreservation process, which means on thawing nothing, or lesser quantity will be retrieved.</label>
</td>
</tr>
</table>

<div class="col-sm-4" style="margin-top: 10px;">
<input type="submit" name="submit" value="submit" class="btn btn-secondary">    
</div>

</form>
</div>


<div class="row" id="print_this_section" style="display:none;">
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="https://indiaivf.website/assets/images/india-ivf-logo.webp"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">SEMEN FREEZING REPORT</h3></td>
</tr>
</table>
<table width="100%"  class="fg45yu3">
<tr>    
	<td colspan="3" width="50%" style="border:1px solid;padding:5px;">UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></td>
    <td colspan="3" style="width:50%; border:1px solid; padding:5px;">IIC ID : <?php echo $iic_id;?></td>
</tr>	
<tr>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Husband&rsquo;s name :  <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Age:  <?php echo $patient_data['wife_age']; ?> Year</strong>
</td>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?> Year</strong>
</td>
</tr>
<tr>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Date of Semen Collection:  <?php echo $select_result['date']; ?> </strong>
</td>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Date of Freezing: <?php echo $select_result['date_of_freezing']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Date of Renewal:  <?php echo $select_result['date_of_renewal']; ?> </strong>
</td>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Number of Vials Frozen: <?php echo $select_result['Freezing']; ?> </strong>
</td>
</tr>
 </table>

<table width="100%" class="fg45yu3">
<tr>
    <td colspan="6" style="width:100%; border:1px solid; padding:5px;"> <p style="font-size: 20px;">Semen Parameter Before Freezing:</p></td>
</tr>
 
<tr>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><strong>Sperm Count :</strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><?php echo isset($select_result['Sperm_minutes'])?$select_result['Sperm_minutes']:""; ?></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"> million/ml </td>
</tr>
<tr>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><strong>Motility : </strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><?php echo isset($select_result['Sperm_Motility'])?$select_result['Sperm_Motility']:""; ?></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"> % </td>
</tr>
<tr>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><strong>Morphology : </strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><?php echo isset($select_result['sperm_morphology_val'])?$select_result['sperm_morphology_val']:""; ?></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"> % normal forms </td>
</tr>
 </table>

<table class="jhyu67uy" width="100%">
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
Prepared By:- <?php echo isset($select_result['prepared_by'])?$select_result['prepared_by']:""; ?>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p>Adviced :   <?php echo isset($select_result['Adviced'])?$select_result['Adviced']:""; ?></p>
</td>
</tr>
<tr>
<td colspan="4" style="width:100%; border:1px solid; padding:5px;">
Checked By:-  <?php echo isset($select_result['checked_by'])?$select_result['checked_by']:""; ?>
</td>
</tr>
<tr>
<td colspan="4"style="width:100%; border:1px solid; padding:5px;">
<label for="other">Note: Sperm Freezing may not survive cryopreservation process, which means on thawing nothing, or lesser quantity will be retrieved.</label>
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