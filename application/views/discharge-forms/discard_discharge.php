<?php 
$all_method =& get_instance();
$appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

    // PHP code to Insert/Update data
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
           
        $sql = "SELECT * FROM `hms_discard_discharge` WHERE patient_id='$patient_id'";
        $select_result = run_select_query($sql);
        
        if(empty($select_result)){
            $query = "INSERT INTO `hms_discard_discharge` SET ";
            $sqlArr = array();
            foreach($_POST as $key => $value) {
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
        } else {
            $query = "UPDATE `hms_discard_discharge` SET ";
            $sqlArr = array();
            foreach($_POST as $key => $value) {
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }
            $query .= implode(',' , $sqlArr);
            $query .= " WHERE patient_id='$patient_id'";
        }
        
        $result = run_form_query($query);          
        
        if($result){
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Discharge form saved successfully!').'&t='.base64_encode('success'));
            die();
        } else {
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Something went wrong!').'&t='.base64_encode('error'));
            die();
        }
    }
    
    // Fetch Data safely
    $sql = "SELECT * FROM `hms_discard_discharge` WHERE patient_id='$patient_id'";
    $select_result = run_select_query($sql);
    
    $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result2 = run_select_query($sql2);
    
    // Agar appointments table me na mile, to dusri table backup logic
    $center_for_query = isset($select_result['center']) ? $select_result['center'] : (isset($select_result2['appoitment_for']) ? $select_result2['appoitment_for'] : '');
    
    $sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$center_for_query."'";
    $select_result3 = run_select_query($sql3);

    // 🎯 Center Wise Global Logo Handler
    $center_logo = isset($select_result3['upload_photo_1']) ? $select_result3['upload_photo_1'] : '';
    $center_name_global = isset($select_result3['center_name']) ? $select_result3['center_name'] : '';
    $center_code_val = isset($select_result3['center_code']) ? $select_result3['center_code'] : '';
    $uhid_val = isset($select_result2['uhid']) ? $select_result2['uhid'] : '';
?>

<form action="" enctype='multipart/form-data' method="post">
  <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $patient_id;?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">
 
<div class="ga-pro">
<h3>Discharge Summary</h3>
<h4>Embryo Discard Discharge Summary</h4>

 <div style="float: left; margin-bottom: 10px;margin-right:20px;">
<label for="Center">Center</label>
<select class="form-control" id="center" name="center" onchange="update_center_details(this)">
    <option value=''>--Select From--</option>
    <?php $all_centers = $all_method->get_all_centers();
    foreach($all_centers as $key => $val){ 
        // JavaScript lookup mapping dataset inject
        $logo_url = !empty($val['upload_photo_1']) ? $val['upload_photo_1'] : base_url('assets/center/default-logo.png');
        if((isset($select_result['center']) && $select_result['center'] == $val['center_number']) || ($center_for_query == $val['center_number'])){
            echo '<option value="'.$val['center_number'].'" data-logo="'.$logo_url.'" data-name="'.$val['center_name'].'" selected>'.$val['center_name'].'</option>';
        } else {
            echo '<option value="'.$val['center_number'].'" data-logo="'.$logo_url.'" data-name="'.$val['center_name'].'">'.$val['center_name'].'</option>';
        }
    } 
    ?>
</select> 
 </div>
 
 <div style="float: left; margin-bottom: 10px;">
  <label for="Admission">Date of Admission:</label>
  <input type="date" class="form-control" id="form_date_of_discard" name="date_of_discard" value="<?php echo isset($select_result['date_of_discard'])?$select_result['date_of_discard']:""; ?>">
 </div>

<table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">
    <td colspan="2" width="50%" style="border:1px solid;padding:5px;"><strong>Details of Female Partner</strong></td>
    <td colspan="2" width="50%" style="border:1px solid;padding:5px;"><strong>Details of Male Partner</strong></td>
</tr>
<tr style="background: #b3b9b7;">
    <td colspan="2" style="border:1px solid;padding:5px;"><strong>UHID : <?php echo (!empty($center_code_val) ? $center_code_val : "") . "/" . $uhid_val; ?></strong></td>
    <td colspan="2" style="border:1px solid;padding:5px;"><strong>IIC ID: <?php echo $patient_id; ?></strong></td>
</tr>
<tr>
    <td colspan="2"><strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong></td>
    <td><strong>Male Partner :  <?php echo $patient_data['husband_name']; ?> </strong></td>
</tr>
<tr>
    <td colspan="2"><strong>Age:  <?php echo $patient_data['wife_age']; ?> Year</strong></td>
    <td><strong>Age: <?php echo $patient_data['husband_age']; ?> Year</strong></td>
</tr>

<tr>
<td colspan="4">
<strong>Name of Procedure :
<textarea name="name_of_procedure" id="form_name_of_procedure"><?php echo isset($select_result['name_of_procedure'])?$select_result['name_of_procedure']:""; ?></textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>No. of Embryos Discard
<textarea name="no_of_embryos_discard" id="form_no_of_embryos_discard"><?php echo isset($select_result['no_of_embryos_discard'])?$select_result['no_of_embryos_discard']:""; ?> </textarea>
</strong>
</td>
</tr>
<tr>
<td colspan="4">
<strong>Embryos number and grading on day of Discarding
<textarea name="day_of_discard" id="form_day_of_discard"><?php echo isset($select_result['day_of_discard'])?$select_result['day_of_discard']:""; ?></textarea>
</strong>
</td>
</tr>

<tr>
<td colspan="4">
<p style="margin:10px 0px;">Note: Once discarded, embryos cannot be retrieved again. Discard will be done only after due consent of both partners and as per standard embryology protocols.</p>
</td>
</tr>

<tr>
<td colspan="4">
<strong>Senior Embryologist
<input type="text" class="SeniorEmbryologist" id="form_senior_embryologist" name="senior_embryologist" readonly value="<?php echo isset($select_result['senior_embryologist']) ? $select_result['senior_embryologist'] : (isset($_SESSION['logged_embryologist']['name']) ? $_SESSION['logged_embryologist']['name'] : ''); ?>">
</strong>
</td>
</tr>

<tr>
<td colspan="4">
<label>Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
</td>
</tr>
</tbody>
</table> 
</div>  

<div style="margin-top: 20px; display: flex; gap: 10px;">
    <input type="submit" name="submit" value="Save Report" class="btn btn-secondary">    
    <input type="button" value="Print Summary" class="btn btn-primary" onclick="print_discharge()">
</div>
</form>

<div id="print_this_section" style="display:none;">
    <table style="border:1px solid #000; width:100%; margin-bottom: 20px;">
       <tr>
           <td style="width:40%; padding:10px; text-align: center;">
                <img id="print_logo_img" src="<?php echo !empty($center_logo) ? $center_logo : base_url('assets/center/default-logo.png'); ?>" style="max-width:200px; height:auto; display:block; margin:0 auto;">
           </td>
           <td style="width:60%; padding:10px; text-align: left;">
                <h3 style="margin:0 0 5px 0; font-size:22px;">Department of Embryology</h3>
                <strong>Embryo Discard Discharge Summary</strong><br>
                <small style="color:#555;">Center: <span id="print_center_name"><?php echo !empty($center_name_global) ? $center_name_global : 'Not Selected'; ?></span></small>
           </td>
       </tr>
    </table>

    <table width="100%" class="vb45rt" style="border-collapse: collapse;">
    <tbody>
    <tr>
        <td colspan="4" style="border:1px solid #000; padding:8px; background: #eef0f1;">
            <strong>Date of Admission: <span id="print_date"></span></strong>
        </td>
    </tr>
    <tr style="background: #eef0f1;">
        <td colspan="2" width="50%" style="border:1px solid #000; padding:8px;"><strong>Details of Female Partner</strong></td>
        <td colspan="2" width="50%" style="border:1px solid #000; padding:8px;"><strong>Details of Male Partner</strong></td>
    </tr>
    <tr>
        <td colspan="2" style="border:1px solid #000; padding:8px;"><strong>UHID : <?php echo (!empty($center_code_val) ? $center_code_val : "") . "/" . $uhid_val; ?></strong></td>
        <td colspan="2" style="border:1px solid #000; padding:8px;"><strong>IIC ID: <?php echo $patient_id; ?></strong></td>
    </tr>
    <tr>
        <td colspan="2" style="border:1px solid #000; padding:8px;"><strong>Female Partner : <?php echo $patient_data['wife_name']; ?> </strong></td>
        <td style="border:1px solid #000; padding:8px;"><strong>Male Partner :  <?php echo $patient_data['husband_name']; ?> </strong></td>
    </tr>
    <tr>
        <td colspan="2" style="border:1px solid #000; padding:8px;"><strong>Age:  <?php echo $patient_data['wife_age']; ?> Year</strong></td>
        <td style="border:1px solid #000; padding:8px;"><strong>Age: <?php echo $patient_data['husband_age']; ?> Year</strong></td>
    </tr>
    <tr>
        <td colspan="4" style="border:1px solid #000; padding:10px; text-align:left;">
            <strong>Name of Procedure :</strong><br>
            <p id="print_procedure" style="margin: 5px 0 0 0; white-space: pre-wrap; font-weight: normal;"></p>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="border:1px solid #000; padding:10px; text-align:left;">
            <strong>No. of Embryos Discard :</strong><br>
            <p id="print_oocytes" style="margin: 5px 0 0 0; white-space: pre-wrap; font-weight: normal;"></p>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="border:1px solid #000; padding:10px; text-align:left;">
            <strong>Embryos number and grading on day of Discarding :</strong><br>
            <p id="print_grading" style="margin: 5px 0 0 0; white-space: pre-wrap; font-weight: normal;"></p>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="border:1px solid #000; padding:8px;">
            <p style="margin:5px 0px; font-style: italic; font-size:13px;">Note: Once discarded, embryos cannot be retrieved again. Discard will be done only after due consent of both partners and as per standard embryology protocols.</p>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="border:1px solid #000; padding:8px; text-align:left;">
            <strong>Senior Embryologist :</strong> <span id="print_embryologist" style="font-weight: normal;"></span>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="border:1px solid #000; padding:8px; background: #fff8f8;">
            <small><strong>Instructions:</strong> Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</small>
        </td>
    </tr>
    </tbody>
    </table> 
</div>

<script>
var global_selected_logo = "<?php echo !empty($center_logo) ? $center_logo : ''; ?>";
var global_selected_name = "<?php echo !empty($center_name_global) ? $center_name_global : ''; ?>";

function update_center_details(dropdown) {
    var selectedOption = dropdown.options[dropdown.selectedIndex];
    if(selectedOption.value !== "") {
        global_selected_logo = selectedOption.getAttribute('data-logo');
        global_selected_name = selectedOption.getAttribute('data-name');
        
        // Form layout inline tracking update
        document.getElementById("print_logo_img").src = global_selected_logo;
        document.getElementById("print_center_name").innerText = global_selected_name;
    }
}

function print_discharge() {
    // Dropdown value verification fallback injection
    var dropdown = document.getElementById("center");
    if(dropdown.selectedIndex > 0) {
        global_selected_logo = dropdown.options[dropdown.selectedIndex].getAttribute('data-logo');
        global_selected_name = dropdown.options[dropdown.selectedIndex].getAttribute('data-name');
        document.getElementById("print_logo_img").src = global_selected_logo;
        document.getElementById("print_center_name").innerText = global_selected_name;
    }

    // Capture real-time values safely
    document.getElementById("print_date").innerText = document.getElementById("form_date_of_discard").value;
    document.getElementById("print_procedure").innerText = document.getElementById("form_name_of_procedure").value;
    document.getElementById("print_oocytes").innerText = document.getElementById("form_no_of_embryos_discard").value;
    document.getElementById("print_grading").innerText = document.getElementById("form_day_of_discard").value;
    document.getElementById("print_embryologist").innerText = document.getElementById("form_senior_embryologist").value;

    // Isolate document context and deploy print sequence
    var printContent = document.getElementById("print_this_section").innerHTML;
    var win = window.open("", "", "width=900,height=700");
    win.document.write('<html><head><title>Discharge Summary Print</title>');
    win.document.write('<style>body{font-family:Arial,sans-serif; padding:20px;} table{width:100%; border-collapse:collapse;} td{border:1px solid #000; padding:8px; text-align:left;}</style>');
    win.document.write('</head><body>');
    win.document.write(printContent);
    win.document.write('</body></html>');
    win.document.close();
    
    // Tiny delay to ensure images render before system dialog layout interrupts tracking
    setTimeout(function(){
        win.print();
        win.close();
    }, 350);
}
</script>

<style>
/* CSS Styles */
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
td {
  border: 1px solid #ccc;
  text-align: left;
  padding: 8px; 
}
.ga-pro h3 { text-align: center; font-size: 25px; margin-bottom:5px; }
.ga-pro h4 { text-align: center; font-size: 20px; margin-top:0px; color:#666; }
form { padding-left: 10px; margin-bottom: 4px; }
.vb45rt td { text-align: left; padding-left: 10px; }
select#center { display: block!important; padding: 6px; width: 250px;}
textarea { height: 70px!important; width:100%; margin-top:5px; padding:5px; box-sizing: border-box;}   
.btn { padding: 8px 15px; font-size: 14px; cursor: pointer; border-radius: 4px; border:none;}
.btn-primary { background-color: #007bff; color: white; }
.btn-secondary { background-color: #6c757d; color: white; }
</style>