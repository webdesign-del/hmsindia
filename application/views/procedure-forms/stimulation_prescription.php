<?php 
    if(isset($_POST['submit'])){
        unset($_POST['submit']);

        $select_query = "SELECT * FROM `hms_stimulation_prescription` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        
        $sqlArr = array();
        foreach( $_POST as $key => $value )
        {
            // Array (jaise checkboxes) ko string mein convert karein
            if(is_array($value)) {
                $value = implode(',', $value);
            }
            $sqlArr[] = " `$key` = '".addslashes($value)."'";
        }   

        if(empty($select_result)){
            // Insert data
            $query = "INSERT INTO `hms_stimulation_prescription` SET " . implode(',' , $sqlArr);
        } else {
            // Update data
            $query = "UPDATE `hms_stimulation_prescription` SET " . implode(',' , $sqlArr) . " WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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

    // ==============================================================
    // DATA FETCHING (Yeh code page load par hamesha chalega)
    // ==============================================================
    $select_query = "SELECT * FROM `hms_stimulation_prescription` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);   

    // FIX: Variable ko by default khali array set karein taaki Undefined error na aaye
    $applicablemedicine = array(); 
    
    // Agar database mein purana check kiya hua data hai, toh use array mein tod lein
    if(!empty($select_result) && isset($select_result['applicablemedicine']) && !empty($select_result['applicablemedicine'])) {
        $applicablemedicine = explode(',', $select_result['applicablemedicine']);
    }

    // FIX: Variable ko by default khali array set karein taaki Undefined error na aaye
    // Dosage ko database string (1 Days,2 Days) se Array mein convert karein
    $dosage_arr = array();
    if(!empty($select_result) && !empty($select_result['dosage'])) {
        $dosage_arr = explode(',', $select_result['dosage']);
    }

     $days_arr = array();
    if(!empty($select_result) && !empty($select_result['days'])) {
        $days_arr = explode(',', $select_result['days']);
    }

    $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $patient_data = run_select_query($sql3);    
    
    $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result1 = run_select_query($sql1);
    
    $sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".(isset($select_result1['appoitment_for']) ? $select_result1['appoitment_for'] : '')."'";
    $select_result5 = run_select_query($sql5);
?>
<div class="col-md-12">
<div class="card">
 <div class="card-content">
	<p id="whatsappmessg"></p>
    <input type='button' id='btn' value='Print' class="btn btn-primary pull-right" onclick='printDiv();'>	
    <input type='button' id='btn' value='Send to Patient' class="btn btn-primary pull-right" onclick='sendonwhatsapp("<?php echo $whtname; ?>");'>

<div class="ga-pro">
<h3>Withdrawl Prescription</h3>
    	
  <form action="" enctype='multipart/form-data' method="post">
    <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
    <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
    <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
    <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
    <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
    <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
    <input type="hidden" value="pending" name="status"> 

<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result1['uhid']; ?></strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr style="background: #b3b9b7;">
    <td colspan="3" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Male Partner</strong>
	</td>
  </tr>
<tr>
<td colspan="3" width="50%">
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td width="50%" colspan="3">
<strong>Name : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td width="50%" colspan="3">
<strong>Age: <?php echo $patient_data['husband_age']; ?> </strong>
</td>
</tr>
<tr>
<td width="50%" colspan="3">
<strong>Presenting Complaints : <input type="text" class="Admission" name="presenting_complaints_male" value="<?php echo isset($select_result['presenting_complaints_male'])?$select_result['presenting_complaints_male']:""; ?>"></strong>
</td>
<td colspan="3" width="50%">
<strong>Presenting Complaints:  <input type="text" class="Admission" name="presenting_complaints_female" value="<?php echo isset($select_result['presenting_complaints_female'])?$select_result['presenting_complaints_female']:""; ?>">  </strong>
</td>
</tr>


<tr>
<td width="50%" colspan="3">
<strong>Name of Procedure : IVF cycle initiation prescription</strong>
</td>
<td colspan="3" width="50%">
<strong>Date of procedure:  <input type="date" class="Admission" name="date_of_procedure" value="<?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?>">  </strong>
</td>
</tr>
</tbody>
</table> 

<div class="sec3">
<h4>Advice Female partner </h4>   
<table width="585">
<tbody>
<tr>
<td width="38">
<p>Check</p>
</td>
<td width="117">
<p>Medication</p>
</td>
<td width="76">
<p>Dosage</p>
</td>
<td width="76">
<p>Route</p>
</td>
<td width="83">
<p>Times</p>
</td>
<td width="68">
<p>Timings</p>
</td>
<td width="71">
<p>When to start</p>
</td>
<td width="57">
<p>How many days</p>
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="MENOTASHP150IU1SINJ" <?php if(!empty($select_result['applicablemedicine']) && in_array('MENOTASHP150IU1SINJ',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>MENOTAS HP 150 IU 1S INJ</p>
</td>
<td width="76">
<input type="text" class="form-control" name="dosage[]" value="<?php echo isset($dosage_arr[0]) ? $dosage_arr[0] : ''; ?>">
</td>
<td width="76">
<p>SC</p>
</td>
<td width="83"><p>Once daily</p></td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<input type="text" class="form-control" name="days[]" value="<?php echo isset($days_arr[0]) ? $days_arr[0] : ''; ?>"> days
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Cetrolix0.25mgINJ" <?php if(!empty($select_result['applicablemedicine']) && in_array('Cetrolix0.25mgINJ',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Cetrolix 0.25mg Injection</p>
</td>
<td width="76">
<input type="text" class="form-control" name="dosage[]" value="<?php echo isset($dosage_arr[1]) ? $dosage_arr[1] : ''; ?>">
</td>
<td width="76">
<p>SC</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<input type="text" class="form-control" name="days[]" value="<?php echo isset($days_arr[1]) ? $days_arr[1] : ''; ?>"> days
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CoriosurgeXP10000IUINJ" <?php if(!empty($select_result['applicablemedicine']) && in_array('CoriosurgeXP10000IUINJ',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>Coriosurge XP 10000 IU Inj</p>
</td>
<td width="76">
<input type="text" class="form-control" name="dosage[]" value="<?php echo isset($dosage_arr[2]) ? $dosage_arr[2] : ''; ?>">
</td>
<td width="76">
<p>SC</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<input type="text" class="form-control" name="days[]" value="<?php echo isset($days_arr[2]) ? $days_arr[2] : ''; ?>"> days
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="FOLISURGE300IUINJ" <?php if(!empty($select_result['applicablemedicine']) && in_array('FOLISURGE300IUINJ',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>FOLISURGE 300 IU INJ</p>
</td>
<td width="76">
<input type="text" class="form-control" name="dosage[]" value="<?php echo isset($dosage_arr[3]) ? $dosage_arr[3] : ''; ?>">
</td>
<td width="76">
<p>SC</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<input type="text" class="form-control" name="days[]" value="<?php echo isset($days_arr[3]) ? $days_arr[3] : ''; ?>"> days
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="LUPRORIN4INJ4ML" <?php if(!empty($select_result['applicablemedicine']) && in_array('LUPRORIN4INJ4ML',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117">
<p>LUPRORIN 4 INj 4ML 1s</p>
</td>
<td width="76">
<input type="text" class="form-control" name="dosage[]" value="<?php echo isset($dosage_arr[4]) ? $dosage_arr[4] : ''; ?>">
</td>
<td width="76">
<p>SC</p>
</td>
<td width="83">
<p>Once daily</p>
</td>
<td width="68">
<p>After meals</p>
</td>
<td width="71">
<p>immediately</p>
</td>
<td width="57">
<input type="text" class="form-control" name="days[]" value="<?php echo isset($days_arr[4]) ? $days_arr[4] : ''; ?>"> days
</td>
</tr>

<tr>
<td colspan="8" width="100%">
<strong>Other Medication:

 <textarea name="other_medication" style="width:100%; height:150px;" > <?php echo isset($select_result['other_medication'])?$select_result['other_medication']:""; ?> </textarea>
</strong>
</td>
</tr>

</tbody>
</table>
</div>
<div class="section-card">
               <div class="section-header">
                  <i class="fa fa-file-text-o"></i> Advisory Templates
               </div>
               <div class="section-content">
                  <div class="form-group-enhanced">
                     <label>Select Advisory Templates</label>
                     <select class="form-control multidselect_dropdown_2" multiple="multiple" id="advisory_templates" name="advisory_templates[]">
                        <option value="pre_embryo_transfer_html">PRE EMBRYO TRANSFER</option>
                        <option value="post_operative_instructions_after_ovum_pick_up_html">POST OPERATIVE INSTRUCTIONS AFTER OVUM PICK UP</option>
                        <option value="post_operative_instructions_after_ovarian_prp_html">POST OPERATIVE INSTRUCTIONS AFTER OVARIAN PRP</option>
                        <option value="post_fnac_testes_tprp_tesa_pesa_micro_tese_html">POST FNAC TESTES/ TPRP/TESA/PESA/MICRO TESE</option>
                        <option value="post_embryo_transfer_html">POST EMBRYO TRANSFER</option>
                        <option value="patient_information_section_html">PATIENT INFORMATION</option>
                        <option value="ivf_vitro_fertilization_ivf_information_package_html">IN VITRO FERTILIZATION (IVF) INFORMATION PACKAGE</option>
                        <option value="instructions_for_semen_collection_html">INSTRUCTIONS FOR SEMEN COLLECTION</option>
                        <option value="day_2_day_5_fet_prescription_html">DAY 2 - DAY 5 FET PRESCRIPTION</option>
                     </select>
                  </div>
               </div>
            </div>
<input type="submit" name="submit" value="submit">
</form>
</div>


<div class="row" id="print_this_section" style="display:none;">
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="https://indiaivf.website/assets/images/india-ivf-logo.webp"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">Withdrawl Prescription</h3></td>
   </tr>
</table>
<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result3['center_code']."/".$select_result2['uhid']; ?></strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $data['patient_id']; ?></strong>
</td>
</tr>
<tr style="background: #b3b9b7;">
    <td colspan="3" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Female Partner</strong>
	</td>
	<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
	<strong>Details of Male Partner</strong>
	</td>
  </tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Name : <?php echo $patient_data['wife_name']; ?> </strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Name : <?php echo $patient_data['husband_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['wife_age']; ?></strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Age: <?php echo $patient_data['husband_age']; ?> </strong>
</td>
</tr>

<tr>
<td colspan="3" colspan="2" width="50%" style="border:1px solid;padding:5px;">
<strong>Provisional Diagnosis:</strong>

<p><?php echo isset($select_result['female_issues'])?$select_result['female_issues']:""; ?></p>

</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Final Diagnosis: </strong>

 <p><?php echo isset($select_result['male_issues'])?$select_result['male_issues']:""; ?></p>


</td>
</tr>

<tr>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Name of Procedure :  IVF cycle initiation prescription</strong>
</td>
<td colspan="3" width="50%" style="border:1px solid;padding:5px;">
<strong>Date of procedure:  <?php echo isset($select_result['date_of_procedure'])?$select_result['date_of_procedure']:""; ?></strong>
</td>
</tr>
</tbody>
</table> 
  
<table width="100%">
<tbody>
<tr>
<td colspan="8" style="border:1px solid;padding:5px;" ><h4>ADVICE ON DISCHARGE</h4> </td>
</tr>
<tr>
<td width="100" colspan="1" style="border:1px solid;padding:5px;">
<p>Check</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Medication</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Dosage</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Route</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Times</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Timings</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>When to start</p>
</td>
<td width="100" colspan="1" style="border:1px solid;padding:5px;">
<p>How many days</p>
</td>
</tr>
<?php //if(!empty($select_result['applicablemedicine']) && in_array('EndofertTab2MG',$applicablemedicine)){ ?>
<tr>
<td  width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="EndofertTab2MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('EndofertTab2MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Endofert Tab 2MG</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>One tablet</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Twice daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>7 days</p>
</td>
</tr>
<?php //} ?>
<?php //if(!empty($select_result['applicablemedicine']) && in_array('Tabmeprate10mg',$applicablemedicine)){ ?>
<tr>
<td  width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Tabmeprate10mg" <?php if(!empty($select_result['applicablemedicine']) && in_array('Tabmeprate10mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab meprate 10 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>One tablet</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Twice daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>7 days</p>
</td>
</tr>
<?php //} ?>

<tr>
<td width="100%" colspan="8" style="border:1px solid;padding:5px;">
<div class="sec2">
<ul>
<li>You must stop this medicine after 7 days of intake </li>
<li>Within 10 days of stopping these medications if you don’t get periods inform us</li>
<li>Please visit the clinic between day 2 to day 4 of periods </li>
</ul>
</div>

<div class="sec2">
<ul>
<li>आपको यह दवाई 7 दिनों तक लेनी है, इसके बाद इसे बंद कर देना है।</li>
<li>अगर दवाई बंद करने के 10 दिनों के अंदर पीरियड नहीं आता है, तो हमें तुरंत बताएं।</li>
<li>पीरियड के दूसरे दिन से चौथे दिन के बीच क्लिनिक जरूर आएं।</li>
</ul>
</div>
</td>
</tr>




</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
<style type="text/css">
    form{
        margin: 20px 0;
    }
    form input, button{
        padding: 5px;
    }
    table{
        width: 100%;
        margin-bottom: 20px;
		border-collapse: collapse;
    }
    table, th, td{
        border: 1px solid #cdcdcd;
    }
    table th, table td{
        padding: 10px;
        text-align: left;
    }
	.heading{margin-bottom:10px;margin-top: 0; padding-top:0px;}
	select {
    display: block !important;
}
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: static!important;
    left: -9999pximportant;
    opacity: 1!important;
}
</style>
<script>
function printDiv() 
{
  $('.hide_print').hide();
  $('input[type="submit"]').css('visibility', 'hidden');
  $('p#last_updated').css('visibility', 'hidden');
  var divToPrint=document.getElementById('print_this_section');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
//   setTimeout(function(){newWin.close();},10);
//   window.location.reload();
}
</script>