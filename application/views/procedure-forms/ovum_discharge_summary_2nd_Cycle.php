<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        $select_query = "SELECT * FROM `ovum_discharge_summary` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `ovum_discharge_summary` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE ovum_discharge_summary SET ";
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".$value."'"	;
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

    // Dynamic Logo handling
    $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
    if(empty($page_logo)) {
        $page_logo = base_url('assets/center/default-logo.png');
    }

    $select_query = "SELECT * FROM `ovum_discharge_summary` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);  	
	
	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);	
?>

<form enctype='multipart/form-data' class ="searchform" name="form" action="" method="POST">
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
<input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
<input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
<input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
<input type="hidden" value="pending" name="status"> 
<input type="hidden" value="Second Cycle" name="type"> 
<div class="container2 red-field form mt-5 mb-5">
				<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
    <td style="width:50%;padding:5px;" colspan="10">
        <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo">
    </td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">Department of Embryology</h3><h4>Ovum Discharge Summary</h4></td>
   </tr>
</table>
<div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
  <label for="Admission">Date of Admission:</label>
  <input type="date" class="Admission" name="date_of_addmission" value="<?php echo isset($select_opu_result['dates'])?$select_opu_result['dates']:""; ?>">
 </div>
<div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
  <label for="Admission">Admission Time:</label>
  <input type="time" class="Admission" name="time_of_addmission" value="<?php echo isset($select_result['time_of_addmission'])?$select_result['time_of_addmission']:""; ?>">
 </div>   
<div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
  <label for="Discharge">Date of Discharge:</label>
  <input type="date" class="Discharge" name="date_of_discharge" value="<?php echo isset($select_opu_result['dates'])?$select_opu_result['dates']:""; ?>">
 </div> 
<div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
  <label for="Discharge">Discharge Time:</label>
  <input type="time" class="Discharge" name="time_of_discharge" value="<?php echo isset($select_result['time_of_discharge'])?$select_result['time_of_discharge']:""; ?>">
 </div>  

<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
    <td colspan="5" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	<td colspan="5" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Male Partner</strong>
	</td>
  </tr>
<tr style="background: #b3b9b7;">
<td colspan="5" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="5" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="5" width="57%">
<strong>Female Partner : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td width="42%" colspan="5">
<strong>Male Partner : <?php echo $select_result3['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="5" width="57%">
<strong>Age: <?php echo $select_result3['wife_age']; ?></strong>
</td>
<td width="42%" colspan="5">
<strong>Age: <?php echo $select_result3['husband_age']; ?></strong>
</td>
</tr>

<tr>
<td width="50%" colspan="5">
<strong>Name of Procedure : Ovum Pickup</strong>
</td>
<td colspan="5" width="50%">
<strong>Date of procedure:  
<input type="date" class="date_of_procedure" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>">   </strong>
</td>
</tr>
<tr>
<td width="100%" colspan="10">
<label>No. of oocytes retrieved</label><br/><br/> 
<textarea name="oocytes_retrieved" style="width:100%; height:80px!important;"> <?php echo isset($select_result['oocytes_retrieved'])?$select_result['oocytes_retrieved']:""; ?></textarea>
</td>
</tr>

<tr>
<td colspan="2" width="20%">
<p>IVF</p>
<input type="radio" id="IVF" name="IVF" value="Yes" <?php if(isset($select_result['IVF']) && $select_result['IVF']== "Yes"){ echo "checked";} ?>>
<label for="age1">Yes</label><br>
<input type="radio" id="IVF" name="IVF" value="No" <?php if(isset($select_result['IVF']) && $select_result['IVF'] == "No"){ echo "checked";} ?>>
<label for="age2">No</label><br> 
</td>

<td colspan="2" width="20%">
<p>ICSI</p>
<input type="radio" id="ICSI" name="ICSI" value="Yes" <?php if(isset($select_result['ICSI']) && $select_result['ICSI']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="ICSI" name="ICSI" value="No" <?php if(isset($select_result['ICSI']) && $select_result['ICSI'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" width="20%">
<p>Micro Fluidics</p>
<input type="radio" id="Micro_Fluidics" name="Micro_Fluidics" value="Yes" <?php if(isset($select_result['Micro_Fluidics']) && $select_result['Micro_Fluidics']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Micro_Fluidics" name="Micro_Fluidics" value="No" <?php if(isset($select_result['Micro_Fluidics']) && $select_result['Micro_Fluidics'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" width="20%">
<p>Sperm Mobil</p>
<input type="radio" id="Sperm_Mobil" name="Sperm_Mobil" value="Yes" <?php if(isset($select_result['Sperm_Mobil']) && $select_result['Sperm_Mobil']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Sperm_Mobil" name="Sperm_Mobil" value="No" <?php if(isset($select_result['Sperm_Mobil']) && $select_result['Sperm_Mobil'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>
<td colspan="2" width="20%">
<p>Ooactive</p>
<input type="radio" id="Ooactive" name="Ooactive" value="Yes" <?php if(isset($select_result['Ooactive']) && $select_result['Ooactive']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Ooactive" name="Ooactive" value="No" <?php if(isset($select_result['Ooactive']) && $select_result['Ooactive'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>
</tr>
</tbody>
</table> 
</div>  
 


<div class="sec21">
 <label for="Senior Embryologist">Senior Embryologist</label>
   <input type="hidden" class="SeniorEmbryologist" name="Senior_Embryologist" readonly="" value="<?php echo $_SESSION['logged_embryologist']['name']?>">
  <input type="text" class="SeniorEmbryologist" name="" readonly="" value="<?php echo isset($select_result['Senior_Embryologist'])?$select_result['Senior_Embryologist']:""; ?>">
</div>
<div class="sec2">
  
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
    
</div> 
<div class="col-sm-6" style="margin-top: 10px;">
<input type="submit" name="submit" value="submit" class="btn btn-secondary">   
</div>
</form>

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">
  
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="width:50%;padding:5px;" colspan="2">    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">Discharge Summary</h3><strong>Department of Embryology OPU</strong></td>
</tr>
</table>

<form action="" enctype='multipart/form-data' method="post">    
<div class="ga-pro">
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="5" style="width:50%; border:1px solid; padding:5px;">
<strong>Date of Admission: <?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?></strong>
</td>
<td colspan="5" style="width:50%; border:1px solid; padding:5px;">
<strong>Date of Discharge: <?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?></strong>
</td>
</tr>

<tr style="background: #b3b9b7;">
    <td colspan="5" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	<td colspan="5" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Male Partner</strong>
	</td>
  </tr>
<tr style="background: #b3b9b7;">
<td colspan="5" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="5" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="5" style="width:50%; border:1px solid; padding:5px;">
<strong>Female Partner : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td colspan="5" style="width:50%; border:1px solid; padding:5px;">
<strong>Male Partner : <?php echo $select_result3['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="5" style="width:50%; border:1px solid; padding:5px;">
<strong>Age: <?php echo $select_result3['wife_age']; ?></strong>
</td>
<td colspan="5" style="width:50%; border:1px solid; padding:5px;">
<strong>Age: <?php echo $select_result3['husband_age']; ?></strong>
</td>
</tr>


<tr>
<td colspan="5" style="width:50%; border:1px solid; padding:5px;">
<strong>Name of Procedure : Ovum Pickup</strong>
</td>
<td colspan="5" style="width:50%; border:1px solid; padding:5px;">
 <strong>Date of procedure:  <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?> </strong>
</td>
</tr>
<tr>
<td colspan="10" style="width:100%; border:1px solid; padding:5px;">
<label>No. of oocytes retrieved with grading</label> 
<textarea style="width:100%; height:80px!important;"> <?php echo isset($select_result['oocytes_retrieved'])?$select_result['oocytes_retrieved']:""; ?></textarea>
 </td>
</tr>

<tr>
<td colspan="2" style="width:20%; border:1px solid; padding:5px;">
<p>IVF</p>
<input type="radio" id="IVF" name="IVF" value="Yes" <?php if(isset($select_result['IVF']) && $select_result['IVF']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="IVF" name="IVF" value="No" <?php if(isset($select_result['IVF']) && $select_result['IVF'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" style="width:20%; border:1px solid; padding:5px;">
<p>ICSI</p>
<input type="radio" id="ICSI" name="ICSI" value="Yes" <?php if(isset($select_result['ICSI']) && $select_result['ICSI']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="ICSI" name="ICSI" value="No" <?php if(isset($select_result['ICSI']) && $select_result['ICSI'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" style="width:20%; border:1px solid; padding:5px;">
<p>Micro Fluidics</p>
<input type="radio" id="Micro_Fluidics" name="Micro_Fluidics" value="Yes" <?php if(isset($select_result['Micro_Fluidics']) && $select_result['Micro_Fluidics']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Micro_Fluidics" name="Micro_Fluidics" value="No" <?php if(isset($select_result['Micro_Fluidics']) && $select_result['Micro_Fluidics'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>

<td colspan="2" style="width:20%; border:1px solid; padding:5px;">
<p>Sperm Mobil</p>
<input type="radio" id="Sperm_Mobil" name="Sperm_Mobil" value="Yes" <?php if(isset($select_result['Sperm_Mobil']) && $select_result['Sperm_Mobil']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Sperm_Mobil" name="Sperm_Mobil" value="No" <?php if(isset($select_result['Sperm_Mobil']) && $select_result['Sperm_Mobil'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>
<td colspan="2" style="width:20%; border:1px solid; padding:5px;">
<p>Ooactive</p>
<input type="radio" id="Ooactive" name="Ooactive" value="Yes" <?php if(isset($select_result['Ooactive']) && $select_result['Ooactive']== "Yes"){ echo "checked";} ?>>
  <label for="age1">Yes</label><br>
  <input type="radio" id="Ooactive" name="Ooactive" value="No" <?php if(isset($select_result['Ooactive']) && $select_result['Ooactive'] == "No"){ echo "checked";} ?>>
  <label for="age2">No</label><br> 
</td>
</tr>

<tr>
<td colspan="10" style="width:100%; border:1px solid; padding:5px;">
<label for="Senior Embryologist">Senior Embryologist</label>
<?php echo isset($select_result['Senior_Embryologist'])?$select_result['Senior_Embryologist']:""; ?>
</td>
</tr>

<tr>
<td colspan="10" style="width:100%; border:1px solid; padding:5px;">
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
</td>
</tr>
</tbody>
</table>  
</div>  
</form>	  
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