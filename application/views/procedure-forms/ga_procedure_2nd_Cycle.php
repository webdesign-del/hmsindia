<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
      
        $select_query = "SELECT * FROM `ga_procedure` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `ga_procedure` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE ga_procedure SET ";
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
    $select_query = "SELECT * FROM `ga_procedure` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
    <input type="hidden" value="Second Cycle" name="type">  
    <div class="container2 red-field form mt-5 mb-5">
        <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
           <tr>
               <td style="width:50%;padding:5px;" colspan="10">
                    <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo">
                </td>
                <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">Embryo Discard Discharge Summary</h3></td>
           </tr>
        </table>


<!--<h3 style="color: #4141ab;">GENERAL INSTRUCTIONS PRIOR TO OVUM PICK UP/OVARIAN CYST/ ASPIRATION /PRP/ STEM CELLS / LAP/ HYSTERO/FNAC TESTES/TESA/TESE/TESA/M TESE</h3>-->
<div class="ga-pro">
<h3>GENERAL INSTRUCTIONS PRIOR TO PROCEDURE NEEDING GA</h3>
<p>1.Stop Tablet Ecosprin/Aspirin/Baby aspirin 48 hrs prior to procedure</p>
<p>2.Continue thyroid/antihypertensive/other medical disorder medication with sips of water in the morning of procedure.</p>
<p>3.If you are taking oral medications for diabetes, please see anesthetist three days before procedure for instructions /medications /insulin.</p>
<p>4.Pre anesthetic checkup will be done</p>
<p>5.You should arrange for a responsible adult to accompany you home from the day stay unit following procedure and to remain with you overnight. You should not be left alone in home for 24 hrs. following the procedures We cannot perform procedure if you fail to arrange an escort. You will need to rest at home all day following the procedures, but you should feel well enough to resume normal activities the day after.</p>
<p>6.Please leave valuables and jewelry at home. Please remove nail paint and make up prior to the procedure. It is important that you do not wear any strong perfumes before the procedure.</p>
<p>7.Please remove vaginal hair (by normal waxing/shaving/trimming) at home before you come for procedure.</p>
<p>8.You will be kept in for approximately 2 hours after your operation to allow a full recovery. Your escort should be available to take you home.</p>
<p>9.Please remember to inform us of any medication (other than ivf drugs) that you take regularly- i.e., inhalers, blood pressure tablets. You should bring all medication with you to the fertility unit on the day of procedure.</p>
<p>10. Do not take alcohol for 24 hours prior to your procedure. Please refrain from smoking for as long as possible before the procedure.
<p>11.We look forward to seeing you and will do everything to ensure a safe and comfortable stay.</p>
<div class="ga23">
    
    
    <label for="nofood">12.No food /liquids (nil per mouth) 08 hrs before ovum pick up from</label>
  <input type="text" class="pickup" name="pickup" value="<?php echo isset($select_result['pickup'])?$select_result['pickup']:""; ?>">   <br>  
  <label for="followup">hrs on</label>
  <input type="date" class="follow-up" name="advice" value="<?php echo isset($select_result['advice'])?$select_result['advice']:""; ?>">
.
</div>
<p>13. Please carry your id proofs on the day of procedure</p>
<p>14. Please carry one copy of your passport size photograph on the day of procedure</p>

<div class="ga23">
 <label for="admit">15. Admit @</label>
  <input type="text" class="admit" name="admit" value="<?php echo isset($select_result['admit'])?$select_result['admit']:""; ?>"> <br> 
  
  <label for="daycare">in day care on</label>
  <input type="date" class="daycare" name="daycare" value="<?php echo isset($select_result['daycare'])?$select_result['daycare']:""; ?>">
 <br>
   <label for="admit">at</label>
  <input type="text" class="admit" name="at" value="<?php echo isset($select_result['at'])?$select_result['at']:""; ?>"> <br>
</div>     

<div class="ga23">
 
  <label for="daycare">16. Procedure on</label>
  <input type="date" class="Procedure" name="Procedures" value="<?php echo isset($select_result['Procedures'])?$select_result['Procedures']:""; ?>">
  <br>
   <label for="admit">@</label>
  <input type="text" class="admit" name="byw" value="<?php echo isset($select_result['byw'])?$select_result['byw']:""; ?>">
</div>
   
<div class="ga23">
   <label for="admit">17. Male partner to produce fresh sperm sample on the day of egg collection AT IVF CENTRE (Time to be confirmed on the day)</label>
  <input type="text" class="admit" name="male_partner" value="<?php echo isset($select_result['male_partner'])?$select_result['male_partner']:""; ?>"> 
</div>
<div class="sec2">
  
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
    
</div> 
</div>  
<td width="42%">
<strong>IIC ID: <input type="text" name="patient_id" value="<?php echo $patient_id;?>"  readonly></strong>
</td>
<input type="submit" name="submit" value="submit">

</form>

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">
  
<div class="printtable prtable" id="printtable" style="display:none;"> 
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:30%;padding:5px;" colspan="1"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
   <!--<td style="width:70%;padding:5px;" colspan="3"><h3 style="margin-top:20px;color: #4141ab;">GENERAL INSTRUCTIONS PRIOR TO OVUM PICK UP/OVARIAN CYST/ ASPIRATION /PRP/ STEM CELLS/ LAP/ HYSTERO/FNAC TESTES/TESA/TESE/TESA/M TESE</h3></td>-->
   </tr>
</table>
<form action="" enctype='multipart/form-data' method="post">
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="border-bottom:1px solid;text-align:center;color:red" colspan="4"><h3>GENERAL INSTRUCTIONS PRIOR TO PROCEDURE NEEDING GA</h3></td>
</tr>
   <tr>
<td style="color:red" colspan="4">
<p style="border-bottom:1px solid;">1.Stop Tablet Ecosprin/Aspirin/Baby aspirin 48 hrs prior to procedure</p>
<p style="border-bottom:1px solid;">2.Continue thyroid/antihypertensive/other medical disorder medication with sips of water in the morning of procedure.</p>
<p style="border-bottom:1px solid;">3.If you are taking oral medications for diabetes, please see anesthetist three days before procedure for instructions /medications /insulin.</p>
<p style="border-bottom:1px solid;">4.Pre anesthetic checkup will be done</p>
<p style="border-bottom:1px solid;">5.You should arrange for a responsible adult to accompany you home from the day stay unit following procedure and to remain with you overnight. You should not be left alone in home for 24 hrs. following the procedures We cannot perform procedure if you fail to arrange an escort. You will need to rest at home all day following the procedures, but you should feel well enough to resume normal activities the day after.</p>
<p style="border-bottom:1px solid;">6.Please leave valuables and jewelry at home. Please remove nail paint and make up prior to the procedure. It is important that you do not wear any strong perfumes before the procedure.</p>
<p style="border-bottom:1px solid;">7.Please remove vaginal hair (by normal waxing/shaving/trimming) at home before you come for procedure.</p>
<p style="border-bottom:1px solid;">8.You will be kept in for approximately 2 hours after your operation to allow a full recovery. Your escort should be available to take you home.</p>
<p style="border-bottom:1px solid;">9.Please remember to inform us of any medication (other than ivf drugs) that you take regularly- i.e., inhalers, blood pressure tablets. You should bring all medication with you to the fertility unit on the day of procedure.</p>
<p style="border-bottom:1px solid;">10. Do not take alcohol for 24 hours prior to your procedure. Please refrain from smoking for as long as possible before the procedure.
<p style="border-bottom:1px solid;">11.We look forward to seeing you and will do everything to ensure a safe and comfortable stay.</p>
<p style="border-bottom:1px solid;">12.No food /liquids (nil per mouth) 08 hrs before ovum pick up from<label style="width:200px;">: <?php echo isset($select_result['pickup'])?$select_result['pickup']:""; ?> </label>hrs on <?php echo isset($select_result['advice'])?$select_result['advice']:""; ?></p>
<p style="border-bottom:1px solid;">13. Please carry your id proofs on the day of procedure</p>
<p style="border-bottom:1px solid;">14. Please carry one copy of your passport size photograph on the day of procedure</p> 
<p style="border-bottom:1px solid;">15. Admit @ <label style="width:200px;"><?php echo isset($select_result['admit'])?$select_result['admit']:""; ?></label> in day care on <label style="width:200px;"><?php echo isset($select_result['daycare'])?$select_result['daycare']:""; ?></label> at :<label style="width:200px;"><?php echo isset($select_result['at'])?$select_result['at']:""; ?></label></p> 
<p style="border-bottom:1px solid;">16. Procedure on <label style="width:200px;"><?php echo isset($select_result['Procedures'])?$select_result['Procedures']:""; ?></label> @ <label style="width:200px;"><?php echo isset($select_result['byw'])?$select_result['byw']:""; ?></label></p> 
<p>17. Male partner to produce fresh sperm sample on the day of egg collection AT IVF CENTRE (Time to be confirmed on the day) <?php echo isset($select_result['male_partner'])?$select_result['male_partner']:""; ?></p> 

</td>
</tr>

<tr>
   <td style="border:1px solid;color:red;padding:5px;" colspan="4">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</td>
</tr>
<tr style="background: #b3b9b7;">
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></strong>
</td>
<td colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
</table>    
   
</div>  
</form>
</div>
<style>
.ga-pro {
    border-left: 1px solid #000;
    border-right: 1px solid #000;
}
.ga-pro h3 {
    border-bottom: 1px solid #000;
    border-top: 1px solid #000;
    color: red;
    text-align: center;
    font-size: 25px;
    margin: 0;
}
.ga-pro p {
    border-bottom: 1px solid #000;
    color: red;
    font-size: 17px;
    padding: 0px 10px;
    margin: 8px 0px;
}
.ga-pro p span {
    color: green;
}

.ga23 { color: red; display: flex; border-bottom: 1px solid #000; padding-left: 10px;padding-top: 5px; }
form {
    margin-bottom: 3px;
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