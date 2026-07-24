<?php
    // PHP code to Insert / Update data into MySQL database
    if(isset($_POST['submit'])){
        unset($_POST['submit']);

        // Handle Procedures Checkbox Array Conversion
        if (isset($_POST['procedures']) && is_array($_POST['procedures'])) {
            $_POST['procedures'] = implode(',', $_POST['procedures']);
        } else {
            $_POST['procedures'] = '';
        }

        $select_query = "SELECT * FROM `pesa_tesatese_micro_tese_discharge_summary` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 

        if(empty($select_result)){
            // MySQL query to insert data
            $query = "INSERT INTO `pesa_tesatese_micro_tese_discharge_summary` SET ";
            $sqlArr = array();
            foreach( $_POST as $key => $value ) {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
        } else {
            // MySQL query to update data
            $query = "UPDATE `pesa_tesatese_micro_tese_discharge_summary` SET ";
            $sqlArr = array();
            foreach( $_POST as $key => $value ) {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }
            $query .= implode(',' , $sqlArr);
            $query .= " WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        }

        $result = run_form_query($query);        
        if($result){
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Procedure form inserted!').'&t='.base64_encode('success'));
            die();
        } else {
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
            die();
        }
    }

    // Fetch existing records for form population
    $select_query = "SELECT * FROM `pesa_tesatese_micro_tese_discharge_summary` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);   
    
    $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3);  
    
    $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
    $select_result1 = run_select_query($sql1);
    
    $sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".(isset($select_result1['wife_phone']) ? $select_result1['wife_phone'] : '')."' and paitent_type='new_patient'";
    $select_result4 = run_select_query($sql4);
    
    $sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".(isset($select_result4['appoitment_for']) ? $select_result4['appoitment_for'] : '')."'";
    $select_result5 = run_select_query($sql5);  

    // Procedures Checkbox array conversion logic
    $procedures = array();
    if(!empty($select_result['procedures'])){
        $procedures = explode(',', $select_result['procedures']);
    }

    // Dynamic Logo handling
    $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
    if(empty($page_logo)) {
        $page_logo = base_url('assets/center/default-logo.png');
    }
?>

<!-- Clean Outer Wrapper Div without print_this_section ID -->
<div class="row">
    <form enctype='multipart/form-data' class="searchform" name="form" action="" method="POST">
        <input type="hidden" value="<?php echo isset($updated_by)?$updated_by:''; ?>" class="form" name="updated_by">
        <input type="hidden" value="<?php echo isset($updated_type)?$updated_type:''; ?>" class="form" name="updated_type">
        <input type="hidden" value="<?php echo isset($updated_at)?$updated_at:''; ?>" class="form" name="updated_at">
        <input type="hidden" value="<?php echo isset($procedure_id)?$procedure_id:''; ?>" class="form" name="procedure_id">
        <input type="hidden" value="<?php echo isset($patient_id)?$patient_id:''; ?>" class="form" name="patient_id">
        <input type="hidden" value="<?php echo isset($receipt_number)?$receipt_number:''; ?>" class="form" name="receipt_number">
        <input type="hidden" value="pending" name="status"> 
        <input type="hidden" value="Second Cycle" name="type"> 

        <div class="container2 red-field form mt-5 mb-5">
            <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
               <tr>
                   <td style="width:50%;padding:5px;" colspan="10">
                       <img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo">
                   </td>
                   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">Department of Embryology</h3><h4>Pesa Tesa Tese Micro Tese</h4></td>
               </tr>
            </table>
         
            <div class="ga-pro">
                <div class="col-sm-12 col-md-12">   
                    <div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
                      <label for="Admission">Date of Admission:</label>
                      <input type="date" class="Admission" name="date_of_addmission" value="<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>">
                    </div>
                    <div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
                      <label for="Admission">Admission Time:</label>
                      <input type="time" class="Admission" name="time_of_addmission" value="<?php echo isset($select_result['time_of_addmission'])?$select_result['time_of_addmission']:""; ?>">
                    </div>   
                    <div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
                      <label for="Discharge">Date of Discharge:</label>
                      <input type="date" class="Discharge" name="date_of_discharge" value="<?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?>">
                    </div> 
                    <div class="col-sm-12 col-md-3" style="margin-bottom: 10px;">
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
                        <strong>UHID : <?php echo (isset($select_result5['center_code'])?$select_result5['center_code']:'')."/".(isset($select_result4['uhid'])?$select_result4['uhid']:''); ?></strong>
                        </td>
                        <td colspan="2" width="50%" style="border:1px solid;padding:5px;">
                        <strong>IIC ID: <?php echo isset($patient_id)?$patient_id:''; ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" width="57%">
                        <strong>Name : <?php echo isset($select_result3['wife_name'])?$select_result3['wife_name']:''; ?> </strong>
                        </td>
                        <td width="42%">
                        <strong>Husband&rsquo;s name : <?php echo isset($select_result3['husband_name'])?$select_result3['husband_name']:''; ?> </strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" width="57%">
                        <strong>Age: <?php echo isset($select_result3['wife_age'])?$select_result3['wife_age']:''; ?></strong>
                        </td>
                        <td width="42%">
                        <strong>Age: <?php echo isset($select_result3['husband_age'])?$select_result3['husband_age']:''; ?> </strong>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" width="57%">
                        <strong>Provisional Diagnosis:
                         <textarea name="female_issues" style="width:100%; height:150px;"><?php echo isset($select_result['female_issues'])?trim($select_result['female_issues']):""; ?></textarea>
                        </strong>
                        </td>
                        <td width="42%">
                        <strong>Final Diagnosis:
                         <textarea name="male_issues" style="width:100%; height:150px;"><?php echo isset($select_result['male_issues'])?trim($select_result['male_issues']):""; ?></textarea>
                        </strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" width="57%">
                        <strong>Medical complication:
                        <textarea name="female_complication" style="width:100%; height:150px;"><?php echo isset($select_result['female_complication'])?trim($select_result['female_complication']):""; ?></textarea>
                        </strong>
                        </td>
                        <td width="42%">
                        <strong>Medical complication: 
                        <textarea name="male_complication" style="width:100%; height:150px;"><?php echo isset($select_result['male_complication'])?trim($select_result['male_complication']):""; ?></textarea>
                        </strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                        <strong>Name of Procedure : 
                        <input type="checkbox" class="PESA" value="PESA" name="procedures[]" <?php if(in_array('PESA',$procedures)){echo "checked";}?>> PESA 
                        <input type="checkbox" class="TESA" name="procedures[]" value="TESA" <?php if(in_array('TESA',$procedures)){echo "checked";}?>> TESA 
                        <input type="checkbox" class="TESE" name="procedures[]" value="TESE" <?php if(in_array('TESE',$procedures)){echo "checked";}?>> TESE 
                        <input type="checkbox" class="MICROTESE" name="procedures[]" value="MICRO TESE" <?php if(in_array('MICRO TESE',$procedures)){echo "checked";}?>> MICRO TESE</strong>
                        </td>
                        <td colspan="2" width="50%">
                        <strong>Date of procedure: <input type="date" class="procedure" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>"></strong>
                        </td>
                    </tr>
                </tbody>
                </table> 

                <div class="sec2">
                    <textarea name="Rt" style="width:100%; height:80px!important;"><?php echo isset($select_result['Rt'])?trim($select_result['Rt']):""; ?></textarea>
                    <label for="Rt">sperms seen /not seen Right Testes</label><br>
                    <textarea name="Lt" style="width:100%; height:80px!important;"><?php echo isset($select_result['Lt'])?trim($select_result['Lt']):""; ?></textarea>
                    <label for="Lt">sperms seen /not seen Left Testes </label><br>
                </div>  
            </div>  

            <div class="sec21">
                <label for="Senior Embryologist">Senior Embryologist</label>
                <input type="hidden" class="SeniorEmbryologist" name="Senior_Embryologist" readonly="" value="<?php echo isset($_SESSION['logged_embryologist']['name'])?$_SESSION['logged_embryologist']['name']:''?>">
                <input type="text" class="SeniorEmbryologist" name="" readonly="" value="<?php echo isset($select_result['Senior_Embryologist'])?$select_result['Senior_Embryologist']:(isset($_SESSION['logged_embryologist']['name'])?$_SESSION['logged_embryologist']['name']:''); ?>">
            </div>

            <div class="sec2">
                <label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
            </div> 

            <input type="submit" name="submit" value="submit" class="btn btn-primary" style="margin-top:10px;">
        </div>
    </form>

   <input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">
  
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
     <div class="ga-pro">
            <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
               <tr>
                   <td style="width:50%;padding:5px;text-align:left;" colspan="2">
                       <img src="<?php echo $page_logo; ?>" style="max-width:200px;" alt="Center Logo">
                   </td>
                   <td style="width:50%;padding:5px;text-align:left;" colspan="2">
                       <h3 style="margin-top:20px;text-align:left;">Discharge Summary</h3>
                       <strong>PESA / TESA / TESE / MICRO TESE</strong>
                   </td>
               </tr>
            </table>

            <table width="100%" class="vb45rt">
            <tbody>
                <tr style="background: #b3b9b7;">
                    <td colspan="2" style="width:50%;border:1px solid;padding:5px;">
                     <strong>Date of Admission: <?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>  <?php echo isset($select_result['time_of_addmission'])?$select_result['time_of_addmission']:""; ?></strong>
                    </td>
                    <td style="width:50%;border:1px solid;padding:5px;">
                    <strong>Date of Discharge: <?php echo isset($select_result['date_of_discharge'])?$select_result['date_of_discharge']:""; ?>  <?php echo isset($select_result['time_of_discharge'])?$select_result['time_of_discharge']:""; ?></strong>
                    </td>
                </tr>

                <tr style="background: #b3b9b7;">
                    <td colspan="4" width="100%" style="border:1px solid;padding:5px;">
                    <strong>Details of Patient</strong>
                    </td>
                </tr>
                <tr style="background: #b3b9b7;">
                    <td colspan="2" width="50%" style="border:1px solid;padding:5px;">
                    <strong>UHID : <?php echo (isset($select_result5['center_code'])?$select_result5['center_code']:'')."/".(isset($select_result4['uhid'])?$select_result4['uhid']:''); ?></strong>
                    </td>
                    <td colspan="2" width="50%" style="border:1px solid;padding:5px;">
                    <strong>IIC ID: <?php echo isset($patient_id)?$patient_id:''; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="width:50%;border:1px solid;padding:5px;" >
                    <strong>Name : <?php echo isset($select_result3['wife_name']) ? $select_result3['wife_name'] : ''; ?> </strong>
                    </td>
                    <td style="width:50%;border:1px solid;padding:5px;">
                    <strong>Husband&rsquo;s name : <?php echo isset($select_result3['husband_name']) ? $select_result3['husband_name'] : ''; ?> </strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="width:50%;border:1px solid;padding:5px;">
                    <strong>Age: <?php echo isset($select_result3['wife_age']) ? $select_result3['wife_age'] : ''; ?></strong>
                    </td>
                    <td style="width:50%;border:1px solid;padding:5px;">
                    <strong>Age: <?php echo isset($select_result3['husband_age']) ? $select_result3['husband_age'] : ''; ?> </strong>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="width:50%;border:1px solid;padding:5px;text-align:left;vertical-align:top;">
                    <strong>Provisional Diagnosis:</strong><br>
                    <?php echo isset($select_result['female_issues'])?nl2br(trim($select_result['female_issues'])):""; ?>
                    </td>
                    <td style="width:50%;border:1px solid;padding:5px;text-align:left;vertical-align:top;">
                    <strong>Final Diagnosis:</strong><br>
                    <?php echo isset($select_result['male_issues'])?nl2br(trim($select_result['male_issues'])):""; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="width:50%;border:1px solid;padding:5px;text-align:left;vertical-align:top;">
                    <strong>Medical complication:</strong><br>
                    <?php echo isset($select_result['female_complication'])?nl2br(trim($select_result['female_complication'])):""; ?>
                    </td>
                    <td style="width:50%;border:1px solid;padding:5px;text-align:left;vertical-align:top;">
                    <strong>Medical complication:</strong><br>
                    <?php echo isset($select_result['male_complication'])?nl2br(trim($select_result['male_complication'])):""; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="width:50%;border:1px solid;padding:5px;text-align:left;">
                    <strong>Name of Procedure : </strong>
                    <input type="checkbox" disabled <?php if(in_array('PESA',$procedures)){echo "checked";}?>> PESA 
                    <input type="checkbox" disabled <?php if(in_array('TESA',$procedures)){echo "checked";}?>> TESA 
                    <input type="checkbox" disabled <?php if(in_array('TESE',$procedures)){echo "checked";}?>> TESE 
                    <input type="checkbox" disabled <?php if(in_array('MICRO TESE',$procedures)){echo "checked";}?>> MICRO TESE
                    </td>
                    <td colspan="2" style="width:50%;border:1px solid;padding:5px;text-align:left;">
                    <strong>Date of procedure: <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?></strong>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="width:50%;border:1px solid;padding:5px;text-align:left;vertical-align:top;">
                    <strong>sperms seen /not seen Right Testes :</strong><br>
                    <?php echo isset($select_result['Rt'])?nl2br(trim($select_result['Rt'])):""; ?>
                    </td>
                    <td colspan="2" style="width:50%;border:1px solid;padding:5px;text-align:left;vertical-align:top;">
                    <strong>sperms seen /not seen Left Testes :</strong><br>
                    <?php echo isset($select_result['Lt'])?nl2br(trim($select_result['Lt'])):""; ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="4" style="width:100%;border:1px solid;padding:5px;text-align:left;">
                    <strong>Senior Embryologist: </strong> <?php echo isset($select_result['Senior_Embryologist'])?$select_result['Senior_Embryologist']:""; ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="4" style="width:100%;border:1px solid;padding:5px;text-align:left;color:red;font-weight:bold;">
                    Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.
                    </td>
                </tr>
            </tbody>
            </table> 
        </div>  
    </div>
</div>

<style>
select#center {
    display: block!important;
}
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
    padding-top: 5px;
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