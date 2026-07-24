<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        $select_query = "SELECT * FROM `discharge_summary` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `discharge_summary` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE discharge_summary SET ";
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
    $select_query = "SELECT * FROM `discharge_summary` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
<input type="hidden" value="Third Cycle" name="type"> 
<div class="container2 red-field form mt-5 mb-5">
				<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><?php 
                // Header se set kiya hua global session uthayenge
                $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : ''; 
                
                if(!empty($page_logo)): ?>
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php else: ?>
                    <img src="<?php echo base_url('assets/center/default-logo.png'); ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php endif; ?></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">Department of Embryology</h3></td>
   </tr>
</table>

<div class="ga-pro">
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
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" width="57%">
<strong>Female Partner : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td width="42%">
<strong>Male Partner :  <?php echo $select_result3['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" width="57%">
<strong>Age:  <?php echo $select_result3['wife_age']; ?> Year</strong>
</td>
<td width="42%">
<strong>Age: <?php echo $select_result3['husband_age']; ?> Year</strong>
</td>
</tr>

<tr>
<td colspan="4">
<strong>Name of Procedure :
<textarea name="name_of_procedure" id="name_of_procedure"  > <?php echo isset($select_result['name_of_procedure'])?$select_result['name_of_procedure']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>No. of oocytes retrieved
<textarea id="oocytes" name="no_of_oocytes_retrieved" id="no_of_oocytes_retrieved"> <?php echo isset($select_result['no_of_oocytes_retrieved'])?$select_result['no_of_oocytes_retrieved']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>Fertilization status
<textarea name="fertilization_status" id="status"  > <?php echo isset($select_result['fertilization_status'])?$select_result['fertilization_status']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>D2
<textarea name="d2" id="d2"  > <?php echo isset($select_result['d2'])?$select_result['d2']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>D3
<textarea name="d3" id="d3"  > <?php echo isset($select_result['d3'])?$select_result['d3']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>D4
<textarea name="d4" id="d4"  > <?php echo isset($select_result['d4'])?$select_result['d4']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>D5
<textarea name="d5" id="d5"  > <?php echo isset($select_result['d5'])?$select_result['d5']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>D6
<textarea name="d6" id="d6"  > <?php echo isset($select_result['d6'])?$select_result['d6']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>EMBRYO STATUS
<textarea name="embryo_status" id="EMBRYO"> <?php echo isset($select_result['embryo_status'])?$select_result['embryo_status']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>Embryo number and grading on day of freezing
<textarea name="day_of_freezing" id="freezing"> <?php echo isset($select_result['day_of_freezing'])?$select_result['day_of_freezing']:""; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="4">
<strong>Embryo number and grading sent for PGT
<textarea name="sent_for_PGT" id="sent_for_PGT"> <?php echo isset($select_result['sent_for_PGT'])?$select_result['sent_for_PGT']:""; ?> </textarea>
</strong>
</td>
</tr>
</tbody>
</table> 
<div class="sec2">
<ul>
<li>Renewal date of embryo freezing   --  <br/><strong><?php echo isset($select_result['renewal_date'])?$select_result['renewal_date']:""; ?></strong><input type="date" id="Renewal" style="width: 100%;" name="renewal_date" value="<?php echo isset($select_result['renewal_date'])?$select_result['renewal_date']:""; ?>"></li>
</ul>

<p style="margin:10px 0px;">Note: embryo/egg freezing may not survive cryopreservation process, which means on thawing nothing, or lesser quantity will be retrieved.</p>
</div>  
</div>  


<div class="sec21">
 <label for="Senior Embryologist">Senior Embryologist</label>
  <input type="text" class="SeniorEmbryologist" name="senior_embryologist" readonly="" value="<?php echo $_SESSION['logged_embryologist']['name']?><?php echo isset($select_result['senior_embryologist'])?$select_result['senior_embryologist']:""; ?>">
</div>
<div class="sec2">
  
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
    
</div> 
<div class="col-sm-2" style="margin-top: 10px;">
<input type="submit" name="submit" value="submit" class="btn btn-secondary">    
</div>

</form>

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">
  
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
<form action="" enctype='multipart/form-data' method="post">
<div class="ga-pro">
<table style="border:1px solid;width:100%;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="2"><?php 
                // Header se set kiya hua global session uthayenge
                $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : ''; 
                
                if(!empty($page_logo)): ?>
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php else: ?>
                    <img src="<?php echo base_url('assets/center/default-logo.png'); ?>" class="center" style="width:250px; display: block; margin: 0 auto;">
                <?php endif; ?></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:10px;">Department of Embryology</h3><strong>Discharge Summary</strong></td>
   </tr>
</table>

<table width="100%" class="vb45rt">
<tbody>
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
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Female Partner : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td width="50%" style="border:1px solid;padding:5px;">
<strong>Male Partner :  <?php echo $select_result3['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Age:  <?php echo $select_result3['wife_age']; ?> Year</strong>
</td>
<td width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $select_result3['husband_age']; ?> Year</strong>
</td>
</tr>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>Name of Procedure :
<textarea name="name_of_procedure" id="name_of_procedure" style="width:100%;height:40px;" > <?php echo isset($select_result['name_of_procedure'])?$select_result['name_of_procedure']:""; ?> </textarea>
</strong>
</td>
</tr>
<?php if (!empty($select_result['no_of_oocytes_retrieved'])): ?>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>No. of oocytes retrieved
<textarea id="oocytes" name="no_of_oocytes_retrieved" id="no_of_oocytes_retrieved" style="width:100%;height:40px;"> <?php echo isset($select_result['no_of_oocytes_retrieved'])?$select_result['no_of_oocytes_retrieved']:""; ?> </textarea>
</strong>
</td>
</tr>
<?php endif; ?>
<?php if (!empty($select_result['fertilization_status'])): ?>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>Fertilization status
<textarea name="fertilization_status" id="status" style="width:100%;height:40px;" > <?php echo isset($select_result['fertilization_status'])?$select_result['fertilization_status']:""; ?> </textarea>
</strong>
</td>
</tr>
<?php endif; ?>
<?php if (!empty($select_result['d2'])): ?>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>D2
<textarea name="d2" id="d2"  style="width:100%;height:40px;"> <?php echo isset($select_result['d2'])?$select_result['d2']:""; ?> </textarea>
</strong>
</td>
</tr>
<?php endif; ?>
<?php if (!empty($select_result['d3'])): ?>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>D3
<textarea name="d3" id="d3" style="width:100%;height:40px;" > <?php echo isset($select_result['d3'])?$select_result['d3']:""; ?> </textarea>
</strong>
</td>
</tr>
<?php endif; ?>
<?php if (!empty($select_result['d4'])): ?>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>D4
<textarea name="d4" id="d4" style="width:100%;height:40px;" > <?php echo isset($select_result['d4'])?$select_result['d4']:""; ?> </textarea>
</strong>
</td>
</tr>
<?php endif; ?>
<?php if (!empty($select_result['d5'])): ?>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>D5
<textarea name="d5" id="d5" style="width:100%;height:40px;" > <?php echo isset($select_result['d5'])?$select_result['d5']:""; ?> </textarea>
</strong>
</td>
</tr>
<?php endif; ?>
<?php if (!empty($select_result['d6'])): ?>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>D6
<textarea name="d6" id="d6" style="width:100%;height:40px;" > <?php echo isset($select_result['d6'])?$select_result['d6']:""; ?> </textarea>
</strong>
</td>
</tr>
<?php endif; ?>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>EMBRYO STATUS
<textarea name="embryo_status" id="EMBRYO" style="width:100%;height:40px;"> <?php echo isset($select_result['embryo_status'])?$select_result['embryo_status']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>Embryo number and grading on day of freezing
<textarea style="width:100%;height:40px;"> <?php echo isset($select_result['day_of_freezing'])?$select_result['day_of_freezing']:""; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>Embryo number and grading sent for PGT
<textarea style="width:100%;height:40px;"> <?php echo isset($select_result['sent_for_PGT'])?$select_result['sent_for_PGT']:""; ?> </textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<strong>Renewal date of embryo freezing -- <?php echo isset($select_result['renewal_date'])?$select_result['renewal_date']:""; ?></strong>
</td>
</tr>

<tr>
<td colspan="4" style="border:1px solid;padding:5px;">
<p style="margin:10px 0px;">Note: embryo/egg freezing may not survive cryopreservation process, which means on thawing nothing, or lesser quantity will be retrieved.</p>
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