<?php 
    if(isset($_POST['submit'])){
        unset($_POST['submit']);

        // Predefined list jo aapke HTML rows ke sequence se bilkul match karti hai
        $predefined_medicines = [
            0 => 'MENOTASHP150IU1SINJ',
            1 => 'Cetrolix0.25mgINJ',
            2 => 'CoriosurgeXP10000IUINJ',
            3 => 'FOLISURGE300IUINJ',
            4 => 'LUPRORIN4INJ4ML'
        ];

        // 1. ZAFAR / GHANSHYAM FIX: Data ko Serialized Format me convert karein
        $serialized_list = array();
        if(!empty($_POST['female_medicine_suggestion_list_ipd']) && is_array($_POST['female_medicine_suggestion_list_ipd'])) {
            foreach($predefined_medicines as $index => $med_name) {
                // Agar doctor ne is medicine ka checkbox tick kiya hai
                if(in_array($med_name, $_POST['female_medicine_suggestion_list_ipd'])) {
                    $serialized_list[] = array(
                        "female_medicine_name"       => $med_name,
                        "female_medicine_dosage"     => isset($_POST['dosage'][$index]) ? $_POST['dosage'][$index] : '',
                        "female_medicine_when_start" => "",
                        "female_medicine_days"       => isset($_POST['days'][$index]) ? $_POST['days'][$index] : '',
                        "female_medicine_route"      => "SC",
                        "female_medicine_frequency"  => "OD",
                        "female_medicine_timing"     => "AFTER MEAL",
                        "female_medicine_take"       => "Daily"
                    );
                }
            }
        }

        // Serialized string ko main column ke andar daal dein
        $_POST['female_medicine_suggestion_list_ipd'] = serialize($serialized_list);

        // 2. CRITICAL FIX: In dono ko POST se hata rahe hain kyunki ye database columns nahi hain
        unset($_POST['dosage']);
        unset($_POST['days']);

        // Baaki saara loop code pehle jaisa hi chalega bina error ke
        $select_query = "SELECT * FROM `hms_doctor_consultation` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        
        $sqlArr = array();
        foreach( $_POST as $key => $value )
        {
            if(is_array($value)) {
                $value = implode(',', $value);
            }
            $sqlArr[] = " `$key` = '".addslashes($value)."'";
        }   

        if(empty($select_result)){
            $query = "INSERT INTO `hms_doctor_consultation` SET " . implode(',' , $sqlArr);
        } else {
            $query = "UPDATE `hms_doctor_consultation` SET " . implode(',' , $sqlArr) . " WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
    // DATA FETCHING (Page Load hone par chalega)
    // ==============================================================
    $select_query = "SELECT * FROM `hms_doctor_consultation` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);   

    // Default arrays ko khali set karein taaki HTML me error na aaye
    $female_medicine_suggestion_list_ipd = array(); 
    $dosage_arr = array_fill(0, 5, ''); 
    $days_arr = array_fill(0, 5, '');   

    // 3. FETCH FIX: Serialized data ko wapas todkar HTML me dikhana
    if(!empty($select_result) && !empty($select_result['female_medicine_suggestion_list_ipd'])) {
        $data = @unserialize($select_result['female_medicine_suggestion_list_ipd']);
        
        if ($data !== false) {
            $predefined_medicines = [
                0 => 'MENOTASHP150IU1SINJ',
                1 => 'Cetrolix0.25mgINJ',
                2 => 'CoriosurgeXP10000IUINJ',
                3 => 'FOLISURGE300IUINJ',
                4 => 'LUPRORIN4INJ4ML'
            ];
            
            foreach($data as $med) {
                $med_name = $med['female_medicine_name'];
                $female_medicine_suggestion_list_ipd[] = $med_name;
                
                // Pata lagayein ye kaunse input index par show hoga
                $idx = array_search($med_name, $predefined_medicines);
                if($idx !== false) {
                    $dosage_arr[$idx] = $med['female_medicine_dosage'];
                    $days_arr[$idx] = $med['female_medicine_days'];
                }
            }
        }
    }

    // 4. Advisory Templates array (Dropdown ke liye)
    $advisory_arr = array();
    if(!empty($select_result) && !empty($select_result['advisory_templates'])) {
        $advisory_arr = explode(',', $select_result['advisory_templates']);
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
<strong>Presenting Complaints : <input type="text" class="Admission" name="female_findings" value="<?php echo isset($select_result['female_findings'])?$select_result['female_findings']:""; ?>"></strong>
</td>
<td colspan="3" width="50%">
<strong>Presenting Complaints:  <input type="text" class="Admission" name="male_findings" value="<?php echo isset($select_result['male_findings'])?$select_result['male_findings']:""; ?>">  </strong>
</td>
</tr>


</tbody>
</table> 

<div class="sec3">
<h4>Advice Female partner </h4>   
<table width="585">
<tbody>
<tr>
<td width="38"><p>Check</p></td>
<td width="117"><p>Medication</p></td>
<td width="76"><p>Dosage</p></td>
<td width="76"><p>Route</p></td>
<td width="83"><p>Times</p></td>
<td width="68"><p>Timings</p></td>
<td width="71"><p>When to start</p></td>
<td width="57"><p>How many days</p></td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="female_medicine_suggestion_list_ipd[]" value="MENOTASHP150IU1SINJ" <?php if(in_array('MENOTASHP150IU1SINJ',$female_medicine_suggestion_list_ipd)){echo "checked";}?>>
</td>
<td width="117"><p>MENOTAS HP 150 IU 1S INJ</p></td>
<td width="76">
<input type="text" class="form-control" name="dosage[]" value="<?php echo isset($dosage_arr[0]) ? $dosage_arr[0] : ''; ?>">
</td>
<td width="76"><p>SC</p></td>
<td width="83"><p>Once daily</p></td>
<td width="68"><p>After meals</p></td>
<td width="71"><p>immediately</p></td>
<td width="57">
<input type="text" class="form-control" name="days[]" value="<?php echo isset($days_arr[0]) ? $days_arr[0] : ''; ?>"> days
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="female_medicine_suggestion_list_ipd[]" value="Cetrolix0.25mgINJ" <?php if(in_array('Cetrolix0.25mgINJ',$female_medicine_suggestion_list_ipd)){echo "checked";}?>>
</td>
<td width="117"><p>Cetrolix 0.25mg Injection</p></td>
<td width="76">
<input type="text" class="form-control" name="dosage[]" value="<?php echo isset($dosage_arr[1]) ? $dosage_arr[1] : ''; ?>">
</td>
<td width="76"><p>SC</p></td>
<td width="83"><p>Once daily</p></td>
<td width="68"><p>After meals</p></td>
<td width="71"><p>immediately</p></td>
<td width="57">
<input type="text" class="form-control" name="days[]" value="<?php echo isset($days_arr[1]) ? $days_arr[1] : ''; ?>"> days
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="female_medicine_suggestion_list_ipd[]" value="CoriosurgeXP10000IUINJ" <?php if(in_array('CoriosurgeXP10000IUINJ',$female_medicine_suggestion_list_ipd)){echo "checked";}?>>
</td>
<td width="117"><p>Coriosurge XP 10000 IU Inj</p></td>
<td width="76">
<input type="text" class="form-control" name="dosage[]" value="<?php echo isset($dosage_arr[2]) ? $dosage_arr[2] : ''; ?>">
</td>
<td width="76"><p>SC</p></td>
<td width="83"><p>Once daily</p></td>
<td width="68"><p>After meals</p></td>
<td width="71"><p>immediately</p></td>
<td width="57">
<input type="text" class="form-control" name="days[]" value="<?php echo isset($days_arr[2]) ? $days_arr[2] : ''; ?>"> days
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="female_medicine_suggestion_list_ipd[]" value="FOLISURGE300IUINJ" <?php if(in_array('FOLISURGE300IUINJ',$female_medicine_suggestion_list_ipd)){echo "checked";}?>>
</td>
<td width="117"><p>FOLISURGE 300 IU INJ</p></td>
<td width="76">
<input type="text" class="form-control" name="dosage[]" value="<?php echo isset($dosage_arr[3]) ? $dosage_arr[3] : ''; ?>">
</td>
<td width="76"><p>SC</p></td>
<td width="83"><p>Once daily</p></td>
<td width="68"><p>After meals</p></td>
<td width="71"><p>immediately</p></td>
<td width="57">
<input type="text" class="form-control" name="days[]" value="<?php echo isset($days_arr[3]) ? $days_arr[3] : ''; ?>"> days
</td>
</tr>
<tr>
<td>
 <input type="checkbox" class="checkmedicine" name="female_medicine_suggestion_list_ipd[]" value="LUPRORIN4INJ4ML" <?php if(in_array('LUPRORIN4INJ4ML',$female_medicine_suggestion_list_ipd)){echo "checked";}?>>
</td>
<td width="117"><p>LUPRORIN 4 INj 4ML 1s</p></td>
<td width="76">
<input type="text" class="form-control" name="dosage[]" value="<?php echo isset($dosage_arr[4]) ? $dosage_arr[4] : ''; ?>">
</td>
<td width="76"><p>SC</p></td>
<td width="83"><p>Once daily</p></td>
<td width="68"><p>After meals</p></td>
<td width="71"><p>immediately</p></td>
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
<!--
<div class="section-card">
   <div class="section-header">
      <i class="fa fa-file-text-o"></i> Advisory Templates
   </div>
   <div class="section-content">
      <div class="form-group-enhanced">
         <select class="form-control multidselect_dropdown_2" multiple="multiple" id="advisory_templates" name="advisory_templates[]">
            <option value="pre_embryo_transfer_html" <?php if(in_array('pre_embryo_transfer_html', $advisory_arr)){ echo 'selected'; } ?>>PRE EMBRYO TRANSFER</option>
            
            <option value="post_operative_instructions_after_ovum_pick_up_html" <?php if(in_array('post_operative_instructions_after_ovum_pick_up_html', $advisory_arr)){ echo 'selected'; } ?>>POST OPERATIVE INSTRUCTIONS AFTER OVUM PICK UP</option>
            
            <option value="post_operative_instructions_after_ovarian_prp_html" <?php if(in_array('post_operative_instructions_after_ovarian_prp_html', $advisory_arr)){ echo 'selected'; } ?>>POST OPERATIVE INSTRUCTIONS AFTER OVARIAN PRP</option>
            
            <option value="post_fnac_testes_tprp_tesa_pesa_micro_tese_html" <?php if(in_array('post_fnac_testes_tprp_tesa_pesa_micro_tese_html', $advisory_arr)){ echo 'selected'; } ?>>POST FNAC TESTES/ TPRP/TESA/PESA/MICRO TESE</option>
            
            <option value="post_embryo_transfer_html" <?php if(in_array('post_embryo_transfer_html', $advisory_arr)){ echo 'selected'; } ?>>POST EMBRYO TRANSFER</option>
            
            <option value="patient_information_section_html" <?php if(in_array('patient_information_section_html', $advisory_arr)){ echo 'selected'; } ?>>PATIENT INFORMATION</option>
            
            <option value="ivf_vitro_fertilization_ivf_information_package_html" <?php if(in_array('ivf_vitro_fertilization_ivf_information_package_html', $advisory_arr)){ echo 'selected'; } ?>>IN VITRO FERTILIZATION (IVF) INFORMATION PACKAGE</option>
            
            <option value="instructions_for_semen_collection_html" <?php if(in_array('instructions_for_semen_collection_html', $advisory_arr)){ echo 'selected'; } ?>>INSTRUCTIONS FOR SEMEN COLLECTION</option>
            
            <option value="day_2_day_5_fet_prescription_html" <?php if(in_array('day_2_day_5_fet_prescription_html', $advisory_arr)){ echo 'selected'; } ?>>DAY 2 - DAY 5 FET PRESCRIPTION</option>
         </select>
      </div>
   </div>
</div>-->

<input type="submit" name="submit" value="submit" class="btn btn-success">
</form>
</div>
</div>
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