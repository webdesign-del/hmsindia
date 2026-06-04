<?php 
$all_method =& get_instance();

// URL से मरीज की ID सुरक्षित तरीके से निकालें (URL सेगमेंट 3 सपोर्ट के साथ)
if ($all_method->uri->segment(3)) {
    $patient_id = $all_method->uri->segment(3);
} elseif (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
} elseif (isset($_GET['paitent_id'])) {
    $patient_id = $_GET['paitent_id'];
} else {
    $patient_id = isset($patient_id) ? $patient_id : ''; 
}

$appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

    // php code to Insert or Update data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        if (!empty($appoitmented_date)) {
            $sql = "SELECT * FROM `admission_form` WHERE patient_id='$patient_id' AND appoitmented_date='$appoitmented_date'";
        } else {
            $sql = "SELECT * FROM `admission_form` WHERE patient_id='$patient_id'";
        }
        $select_result = run_select_query($sql);
        
        $sqlArr = array(); // एरे सुरक्षित डिफाइन किया

        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `admission_form` SET ";
            foreach($_POST as $key => $value) {
                if (is_array($value)) { $value = implode(',', $value); }
                $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
            $msg = 'Admission form inserted successfully!';
        } else {
            // mysql query to update data
            $query = "UPDATE `admission_form` SET ";
            foreach($_POST as $key => $value) {
                if (is_array($value)) { $value = implode(',', $value); }
                $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }
            $query .= implode(',' , $sqlArr);
            $query .= " WHERE patient_id='$patient_id' and appoitmented_date='$appoitmented_date'";
            $msg = 'Admission form updated successfully!';
        }
        
        $result = run_form_query($query); 
        if($result){
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode($msg).'&t='.base64_encode('success'));
            die();
        } else {
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
            die();
        }
    }
    
    // डेटा लोड करने का कोर बैकएंड ब्लॉक
    if (!empty($appoitmented_date)) {
        $sql = "SELECT * FROM `admission_form` WHERE patient_id='$patient_id' AND appoitmented_date='$appoitmented_date'";
    } else {
        $sql = "SELECT * FROM `admission_form` WHERE patient_id='$patient_id'";
    }
    $select_result = run_select_query($sql);
    
    $sql2 = "SELECT RIGHT(CAST(ipid AS CHAR), 3) as last_three FROM `admission_form` ORDER BY ID DESC LIMIT 1";
    $select_result2 = run_select_query($sql2);
    
    // 💡 FIX 1: $this->config को $all_method->config से रिप्लेस किया ताकि Fatal 500 एरर खत्म हो
    $sql4 = "Select * from ".$all_method->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result4 = run_select_query($sql4);
    
    $sql5 = "Select * from ".$all_method->config->item('db_prefix')."centers where center_number='".($select_result4['appoitment_for'] ?? '')."'";
    $select_result5 = run_select_query($sql5);

    $sql_data = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $patient_data = run_select_query($sql_data); 

    $CI =& get_instance();
    $sql_forms = "SELECT id, form_name, name, db_name FROM `hms_discharge_forms` WHERE status = 'active' and role=''";
    $form_list = $CI->db->query($sql_forms)->result_array(); 

    // 1. Check Table 1: Ovulation Induction
    $sql_ov = "SELECT indication FROM ovulation_induction_protocol WHERE patient_id='".trim($patient_id)."' LIMIT 1";
    $res_ov = run_select_query($sql_ov);

    // 2. Check Table 2: Pre Embryo Transfer
    $sql_et = "SELECT indication FROM pre_embryo_transfer WHERE patient_id='".trim($patient_id)."' LIMIT 1";
    $res_et = run_select_query($sql_et);

    // 3. Check Table 3: Ovarian PRP
    $sql_op_query = "SELECT indication FROM ovarian_prp WHERE patient_id='".trim($patient_id)."' LIMIT 1";
    // 💡 FIX 2: यहाँ पुराने कोड में गलत वेरिएबल $sql_et पास था, उसे सुधारा
    $res_op = run_select_query($sql_op_query);

    // OR Logic
    $has_any_data = (!empty($res_ov) || !empty($res_et) || !empty($res_op));

    // Get the Indication value
    $final_indication = "";
    if (!empty($res_ov['indication'])) {
        $final_indication = $res_ov['indication'];
    } elseif (!empty($res_et['indication'])) {
        $final_indication = $res_et['indication'];
    } elseif (!empty($res_op['indication'])) {
        $final_indication = $res_op['indication'];
    }

    // फ़ॉलबैक वेरिएबल्स ताकि स्क्रीन पर खाली वैल्यूज न आएं
    $center = isset($select_result['center']) ? $select_result['center'] : '';
    $updated_by = isset($updated_by) ? $updated_by : '';
    $updated_type = isset($updated_type) ? $updated_type : '';
    $updated_at = isset($updated_at) ? $updated_at : '';
    $last_ipid_num = isset($select_result2['last_three']) ? $select_result2['last_three'] : 0;
?>

<div class="ga-pro">
<h3>Admission Form</h3>

  <form action="" enctype='multipart/form-data' method="post">
    <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
    <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
    <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
    <input type="hidden" value="<?php echo $patient_id;?>" class="form" name="patient_id">
    <input type="hidden" value="<?php echo date('Y-m-d'); ?>" class="form" name="date_of_addmission">
    <input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">  
    <input type="hidden" value="<?php echo ($select_result5['center_code'] ?? 'CENTER').'/'.(date("y")-1).date("y").'/'.($last_ipid_num + 1);?>" class="form" id="ipid" name="ipid">   
    
<div class="col-sm-12 col-md-12" style="margin-bottom:15px; padding:0;">
    <div class="col-sm-12 col-md-4">
        <label for="Center">Center</label>
        <select class="form-control" id="center" name="center" required="">
            <option value=''>--Select From--</option>
            <?php $all_centers = $all_method->get_all_centers();
            foreach($all_centers as $key => $val){ 
                if($center == $val['center_number']){
                    echo '<option value="'.$val['center_number'].'" selected>'.$val['center_name'].'</option>';
                }else{
                    echo '<option value="'.$val['center_number'].'">'.$val['center_name'].'</option>';
                }
            } 
            ?>
        </select>
     </div> 
    <div class="col-sm-12 col-md-4">
      <label for="Admission">Date of Admission:</label>
      <input type="date" class="form-control" required="" name="date_of_addmission" value="<?php echo isset($select_result['date_of_addmission'])?$select_result['date_of_addmission']:""; ?>">
    </div>  
    <div class="form-group col-md-4">
        <label>Select Discharge Form:</label>
        <select class="form-control" name="form_type" required>
            <option value="">-- Choose Form --</option>
            <?php if(!empty($form_list)): ?>
                <?php foreach($form_list as $form): ?>
                    <option value="<?php echo $form['name']; ?>" <?php echo (isset($select_result['form_type']) && $select_result['form_type'] == $form['name']) ? 'selected' : ''; ?>>
                        <?php echo $form['name']; ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">No forms found in database</option>
            <?php endif; ?>
        </select>
    </div>
 </div>  

<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
    <td colspan="3" width="50%" style="border:1px solid;padding:5px;">
    <strong>Details of Female Partner</strong>
    </td>
    <td colspan="3" width="50%" style="border:1px solid;padding:5px;">
    <strong>Details of Male Partner</strong>
    </td>
</tr>
<tr style="background: #b3b9b7;">
<td colspan="2" width="34%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IPID: <?php echo isset($select_result['ipid']) ? $select_result['ipid'] : 'Auto Generated'; ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%">
<strong>Female Partner : <?php echo isset($patient_data['wife_name']) ? $patient_data['wife_name'] : ''; ?> </strong>
</td>
<td width="50%" colspan="3">
<strong>Male Partner : <?php echo isset($patient_data['husband_name']) ? $patient_data['husband_name'] : ''; ?> </strong>
</td>
</tr>
<tr>
<td colspan="3" width="50%">
<strong>Age: <?php echo isset($patient_data['wife_age']) ? $patient_data['wife_age'] : ''; ?></strong>
</td>
<td width="50%" colspan="3">
<strong>Age: <?php echo isset($patient_data['husband_age']) ? $patient_data['husband_age'] : ''; ?> </strong>
</td>
</tr>

<tr>
<td colspan="3" width="50%" style="vertical-align: top;">
    <strong>Indication:</strong><br>
    <?php if ($has_any_data): ?>
        <textarea name="indication" class="form-control" style="width:100%; height:80px!important; border: 1px solid #ccc; padding: 5px;"><?php echo isset($select_result['indication']) ? $select_result['indication'] : $final_indication; ?></textarea>
    <?php else: ?>
        <div class="alert alert-danger" style="margin-top: 5px; padding: 15px; border-left: 5px solid #a94442;">
            <i class="fa fa-exclamation-triangle"></i> 
            <strong>Please Fill Ovulation Induction / Pre Embryo transfer IPD Form!</strong><br>
            <small>No Indication found in Ovulation or Pre-ET forms for ID: <?php echo $patient_id; ?></small>
        </div>
        <style> #submitbutton, button[type="submit"], input[type="submit"] { display: none !important; } </style>
    <?php endif; ?>
</td>
<td width="50%" colspan="3" style="vertical-align: top;">
<strong>Name Of Procedure: 
 <textarea name="name_of_procedure" style="width:100%; height:80px!important;" ><?php echo isset($select_result['name_of_procedure'])?$select_result['name_of_procedure']:""; ?></textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="6" width="100%">
<strong>Anesthesia Needed:
<textarea name="anesthesia_needed" style="width:100%; height:80px!important;"  ><?php echo isset($select_result['anesthesia_needed'])?$select_result['anesthesia_needed']:""; ?></textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="6" width="100%">
<strong>Doctor Name: <input type="text" class="form-control" style="display:inline-block; width:auto !important;" name="doctor_name" value="<?php echo isset($select_result['doctor_name'])?$select_result['doctor_name']:""; ?>">  </strong>
</td>
</tr>
</tbody>
</table> 

<div style="margin-top: 15px;">
    <input type="submit" name="submit" value="Submit Form" class="btn btn-success">
    <?php if(!empty($select_result)): ?>
        <button type="button" onclick="window.print();" class="btn btn-primary" style="margin-left: 10px;"><i class="fa fa-print"></i> Print Labels</button>
    <?php endif; ?>
</div>
</form>

<div class="row" id="print_this_section" style="display:none;">
<div class="ga-pro">
<table width="100%" class="vb45rt">
<tbody>
<tr>
<?php for($i=1; $i<=3; $i++){ ?>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr><td><strong>Pt Name : <?php echo isset($patient_data['wife_name']) ? $patient_data['wife_name'] : ''; ?></strong></td></tr>
<tr><td><strong>Age / Sex : <?php echo isset($patient_data['wife_age']) ? $patient_data['wife_age'] : ''; ?> / F</strong></td></tr>
<tr><td><strong>UHID : <?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></strong></td></tr>
<tr><td><strong>IIC ID : <?php echo $patient_id; ?></strong></td></tr>
<tr><td><strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong></td></tr>
</tbody>
</table> 
</td>
<?php } ?>
</tr>

<tr><td style="padding:10px!important;" colspan="3"></td></tr>

<tr>
<?php for($i=1; $i<=3; $i++){ ?>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr><td><strong>Pt Name : <?php echo isset($patient_data['wife_name']) ? $patient_data['wife_name'] : ''; ?></strong></td></tr>
<tr><td><strong>Age / Sex : <?php echo isset($patient_data['wife_age']) ? $patient_data['wife_age'] : ''; ?> / F</strong></td></tr>
<tr><td><strong>UHID : <?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></strong></td></tr>
<tr><td><strong>IIC ID : <?php echo $patient_id; ?></strong></td></tr>
<tr><td><strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong></td></tr>
</tbody>
</table> 
</td>
<?php } ?>
</tr>

<tr><td style="padding:10px!important;" colspan="3"></td></tr>

<tr>
<?php for($i=1; $i<=3; $i++){ ?>
<td style="padding:10px;">
<table width="280px" class="vb45rt" style="border:1px solid;padding:5px;" >
<tbody>
<tr><td><strong>Pt Name : <?php echo isset($patient_data['wife_name']) ? $patient_data['wife_name'] : ''; ?></strong></td></tr>
<tr><td><strong>Age / Sex : <?php echo isset($patient_data['wife_age']) ? $patient_data['wife_age'] : ''; ?> / F</strong></td></tr>
<tr><td><strong>UHID : <?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></strong></td></tr>
<tr><td><strong>IIC ID : <?php echo $patient_id; ?></strong></td></tr>
<tr><td><strong>IPID : <?php echo isset($select_result['ipid'])?$select_result['ipid']:""; ?></strong></td></tr>
</tbody>
</table> 
</td>
<?php } ?>
</tr>
</tbody>
</table>
</div>
</div>
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
form {
    padding-left: 10px;
    margin-bottom: 4px;
}
.vb45rt td {text-align: left; padding-left: 10px;}

@media print {
    body * { visibility: hidden; }
    #print_this_section, #print_this_section * { visibility: visible; }
    #print_this_section { position: absolute; left: 0; top: 0; width: 100%; display: block !important; }
}
</style>