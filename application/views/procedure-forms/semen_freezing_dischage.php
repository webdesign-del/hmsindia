<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
      
        $select_query = "SELECT * FROM `hms_semen_freezing_discharge` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_semen_freezing_discharge` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_semen_freezing_discharge SET ";
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".$value."'";
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
    $select_query = "SELECT * FROM `hms_semen_freezing_discharge` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);  
    
    $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $patient_data = run_select_query($sql3);    
    
    $sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result4 = run_select_query($sql4);
    
    $sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".($select_result4['appoitment_for'] ?? '')."'";
    $select_result5 = run_select_query($sql5);  

    // Dynamic Logo handling
    $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
    if(empty($page_logo)) {
        $page_logo = base_url('assets/center/default-logo.png');
    }
?>

<form enctype='multipart/form-data' class="searchform" name="form" action="" method="POST">
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
               <td style="width:50%;padding:5px;" colspan="10">
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo">
                </td>
                <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">SEMEN FREEZING</h3></td>
           </tr>
        </table>
         
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
            <td colspan="2" width="50%" style="border:1px solid;padding:5px;">
            <strong>Details of Female Partner</strong>
            </td>
            <td colspan="2" width="100%" style="border:1px solid;padding:5px;">
            <strong>Details of Male Partner</strong>
            </td>
        </tr>
        <tr style="background: #b3b9b7;">
        <td colspan="2" width="50%" style="border:1px solid;padding:5px;">
        <strong>UHID : <?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></strong>
        </td>
        <td colspan="2" width="50%" style="border:1px solid;padding:5px;">
        <strong>IIC ID: <?php echo $patient_id; ?></strong>
        </td>
        </tr>
        <tr>
        <td colspan="2" width="50%">
        <strong>Female Partner : <?php echo $patient_data['wife_name'] ?? ''; ?> </strong>
        </td>
        <td width="50%">
        <strong>Male Partner :  <?php echo $patient_data['husband_name'] ?? ''; ?> </strong>
        </td>
        </tr>
        <tr>
        <td colspan="2" width="50%">
        <strong>Age:  <?php echo $patient_data['wife_age'] ?? ''; ?> Year</strong>
        </td>
        <td width="50%">
        <strong>Age: <?php echo $patient_data['husband_age'] ?? ''; ?> Year</strong>
        </td>
        </tr>
         <tr>
            <td colspan="2" width="50%">Date of Semen Collection:: <input type="date" id="date" name="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>">  </td>
            <td>Date of Freezing: <input type="date" id="date_of_freezing" name="date_of_freezing" value="<?php echo isset($select_result['date_of_freezing'])?$select_result['date_of_freezing']:""; ?>">  </td>
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
        <textarea name="Freezing" id="Freezing"><?php echo isset($select_result['Freezing'])?$select_result['Freezing']:""; ?></textarea>
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
        <td colspan="4" style="width:100%; border:1px solid; padding:5px;">
        <label for="other">Note: Sperm Freezing may not survive cryopreservation process, which means on thawing nothing, or lesser quantity will be retrieved.</label>
        </td>
        </tr>
        </table>

        <div class="col-sm-4" style="margin-top: 10px;">
        <input type="submit" name="submit" value="submit" class="btn btn-secondary">    
        </div>
    </div>
</form>

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">
  
<div class="printtable prtable" id="printtable" style="display:none;"> 
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="width:50%;padding:5px;" colspan="2">    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">SEMEN FREEZING REPORT</h3><strong>Discharge Summary</strong></td>
</tr>
</table>
<table width="100%" class="fg45yu3">
<tr>    
    <td colspan="3" width="50%" style="border:1px solid;padding:5px;">UHID : <?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></td>
    <td colspan="3" style="width:50%; border:1px solid; padding:5px;">IIC ID : <?php echo $patient_id;?></td>
</tr>   
<tr>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Name : <?php echo $patient_data['wife_name'] ?? ''; ?> </strong>
</td>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Husband&rsquo;s name :  <?php echo $patient_data['husband_name'] ?? ''; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Age:  <?php echo $patient_data['wife_age'] ?? ''; ?> Year</strong>
</td>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age'] ?? ''; ?> Year</strong>
</td>
</tr>
<tr>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Date of Semen Collection:  <?php echo $select_result['date'] ?? ''; ?> </strong>
</td>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Date of Freezing: <?php echo $select_result['date_of_freezing'] ?? ''; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Date of Renewal:  <?php echo $select_result['date_of_renewal'] ?? ''; ?> </strong>
</td>
<td colspan="3" style="width:50%; border:1px solid; padding:5px;">
<strong>Number of Vials Frozen: <?php echo $select_result['Freezing'] ?? ''; ?> </strong>
</td>
</tr>
</table>

<table width="100%" class="fg45yu3">
<tr>
    <td colspan="6" style="width:100%; border:1px solid; padding:5px;"> <p style="font-size: 20px;">Semen Parameter Before Freezing:</p></td>
</tr>
 
<tr>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><strong>Sperm Count :</strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><?php echo $select_result['Sperm_minutes'] ?? ''; ?></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"> million/ml </td>
</tr>
<tr>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><strong>Motility : </strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><?php echo $select_result['Sperm_Motility'] ?? ''; ?></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"> % </td>
</tr>
<tr>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><strong>Morphology : </strong></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"><?php echo $select_result['sperm_morphology_val'] ?? ''; ?></td>
    <td colspan="2" style="width:33%; border:1px solid; padding:5px;"> % normal forms </td>
</tr>
</table>

<table class="jhyu67uy" width="100%">
<tr>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
Prepared By:- <?php echo $select_result['prepared_by'] ?? ''; ?>
</td>
<td colspan="2" style="width:50%; border:1px solid; padding:5px;">
<p>Adviced :   <?php echo $select_result['Adviced'] ?? ''; ?></p>
</td>
</tr>
<tr>
<td colspan="4" style="width:100%; border:1px solid; padding:5px;">
Checked By:-  <?php echo $select_result['checked_by'] ?? ''; ?>
</td>
</tr>
<tr>
<td colspan="4" style="width:100%; border:1px solid; padding:5px;">
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
td {border: 1px solid #000; text-align: left; font-weight: 600; padding-left: 20px;}
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
    $('#btn').prop('disabled', false);

    $(".ptable").click(function(){
        $('.searchform').hide();
        $('.printbtn').hide(); 
        $('.prtable').css('display', 'block');
      
        var divToPrint = document.getElementById('printtable');
        var newWin = window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();
      
        setTimeout(function(){
            newWin.close();
        }, 500); 
    });
});
</script>