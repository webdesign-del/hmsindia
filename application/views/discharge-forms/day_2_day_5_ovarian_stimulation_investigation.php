<?php
    $appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        // चेक करें कि क्या इस iic_id का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `day_2_day_5_ovarian_stimulation_investigation` WHERE patient_id='$patient_id'";
        $select_result = run_select_query($sql);
        
        if(!empty($_POST['ovarian_stimulation']) && isset($_POST['ovarian_stimulation'])){
            $_POST['ovarian_stimulation'] = implode(',', $_POST['ovarian_stimulation']);
        }
        
        // अगर डेटा मौजूद नहीं है, सिर्फ तभी INSERT करें
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `day_2_day_5_ovarian_stimulation_investigation` SET ";
            $sqlArr = array();
            foreach($_POST as $key => $value) {
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
            // अगर डेटा पहले से मौजूद है, तो UPDATE ना करें, बल्कि सीधा एरर मैसेज के साथ रिडायरेक्ट करें
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Data is already saved and cannot be updated!').'&t='.base64_encode('error'));
            die();
        }
    }
    
    // फॉर्म में पुराना डेटा दिखाने के लिए सिर्फ patient_id से फेच करें
    $sql = "SELECT * FROM `day_2_day_5_ovarian_stimulation_investigation` WHERE patient_id='$patient_id'";
    $select_result = run_select_query($sql);
    
    $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
    
    $sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result2['appoitment_for']."'";
    $select_result3 = run_select_query($sql3);   
?>
<?php 
    $ovarian = array();
    if(!empty($select_result['ovarian_stimulation'])){
        $ovarian = explode(',', $select_result['ovarian_stimulation']);
    }
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
<strong>Age: <?php echo $patient_data['husband_age']; ?></strong>
</td>
</tr>

</tbody>
</table>  
 <h3>DAY 2 - DAY 5 OVARIAN STIMULATION INVESTIGATION</h3> 
<div class="sec2">
  <input type="checkbox" id="E2" name="ovarian_stimulation[]" value="Serum Estradiol [5pE2]" <?php if(!empty($select_result['ovarian_stimulation']) && isset($select_result['ovarian_stimulation']) && in_array('Serum Estradiol [5pE2]', $ovarian)){echo "checked";}?>>
  <label for="E2">Serum Estradiol (E2)</label><br>
  <input type="checkbox" id="FSH" name="ovarian_stimulation[]" value="Serum Follicle Stimulating Hormone FSH" <?php if(!empty($select_result['ovarian_stimulation']) && isset($select_result['ovarian_stimulation']) && in_array('Serum Follicle Stimulating Hormone FSH', $ovarian)){echo "checked";}?>>
  <label for="FSH">Serum Follicle Stimulating Hormone (FSH)</label><br>
<input type="checkbox" id="LH" name="ovarian_stimulation[]" value="Serum luteinizing Hormone LH"  <?php if(!empty($select_result['ovarian_stimulation']) && isset($select_result['ovarian_stimulation']) && in_array('Serum luteinizing Hormone LH', $ovarian)){echo "checked";}?>>
<label for="LH">Serum luteinizing Hormone (LH)</label><br>



  <label for="Estrabet">Others</label> 
  <textarea name="others" style="width:100%; height:80px!important"  > <?php echo isset($select_result['others'])?$select_result['others']:""; ?> </textarea>

</div>  
  <div class="sec2">
  
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
    
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
<strong>Date: <?php echo isset($select_result['date'])?$select_result['date']:""; ?></strong>
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
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td width="50%" style="border:1px solid;">
<strong>Husband&rsquo;s name : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?></strong>
</td>
</tr>

<tr>
<td colspan="4" width="50%" style="border:1px solid;padding:5px;">
<h3>DAY 2 - DAY 5 OVARIAN STIMULATION INVESTIGATION</h3> 
</td>
</tr>

<tr>
<td colspan="4" width="50%" style="border:1px solid;padding:5px;">
<?php if(!empty($select_result['ovarian_stimulation']) && isset($select_result['ovarian_stimulation']) && in_array('Serum Estradiol [5pE2]', $ovarian)){ ?>
 <input type="checkbox" id="E2" name="ovarian_stimulation[]" value="Serum Estradiol [5pE2]" <?php if(!empty($select_result['ovarian_stimulation']) && isset($select_result['ovarian_stimulation']) && in_array('Serum Estradiol [5pE2]', $ovarian)){echo "checked";}?>>
 <label for="E2">Serum Estradiol (E2)</label><br>
<?php } ?>
<?php if(!empty($select_result['ovarian_stimulation']) && isset($select_result['ovarian_stimulation']) && in_array('Serum Follicle Stimulating Hormone FSH', $ovarian)){ ?>
 <input type="checkbox" id="FSH" name="ovarian_stimulation[]" value="Serum Follicle Stimulating Hormone FSH" <?php if(!empty($select_result['ovarian_stimulation']) && isset($select_result['ovarian_stimulation']) && in_array('Serum Follicle Stimulating Hormone FSH', $ovarian)){echo "checked";}?>>
 <label for="FSH">Serum Follicle Stimulating Hormone (FSH)</label><br>
<?php } ?>
<?php if(!empty($select_result['ovarian_stimulation']) && isset($select_result['ovarian_stimulation']) && in_array('Serum luteinizing Hormone LH', $ovarian)){ ?>
<input type="checkbox" id="LH" name="ovarian_stimulation[]" value="Serum luteinizing Hormone LH"  <?php if(!empty($select_result['ovarian_stimulation']) && isset($select_result['ovarian_stimulation']) && in_array('Serum luteinizing Hormone LH', $ovarian)){echo "checked";}?>>
<label for="LH">Serum luteinizing Hormone (LH)</label><br>
<?php } ?>
</td>
</tr>

<tr>
<td colspan="4" width="50%" style="border:1px solid;padding:5px;">
 <label for="Estrabet">Others</label> 
  <textarea name="others" style="width:100%; height:100px!important"  > <?php echo isset($select_result['others'])?$select_result['others']:""; ?> </textarea>

</td>
</tr>

<tr>
<td colspan="4" width="50%" style="border:1px solid;padding:5px;">
 <label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>

</td>
</tr>

</tbody>
</table>  
 
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
</style>    