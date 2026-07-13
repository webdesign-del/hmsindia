<?php
    // 1. URL या GET पैरामीटर्स से डेटा सुरक्षित रूप से निकालना
    $appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    // अगर सीधे GET में patient_id नहीं है, तो चेक करें
    $patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : $id;

    // फिक्स: अगर ऊपर से भी patient_id नहीं मिला, तो URL पाथ से निकालेंगे (/patient-discharge/106/17727797457248/25)
    if (empty($patient_id)) {
        $current_url = $_SERVER['REQUEST_URI'];
        $url_parts = explode('/', trim($current_url, '/'));
        
        // CodeIgniter / MVC फ्रेमवर्क के यूआरएल से 14-digit या लॉन्ग संख्या (Patient ID) खोजना
        foreach ($url_parts as $part) {
            // क्लीन करने के लिए अगर पार्ट में ? है तो उसे हटाएं
            $clean_part = strtok($part, '?');
            if (is_numeric($clean_part) && strlen($clean_part) >= 10) {
                $patient_id = $clean_part;
                break;
            }
        }
    }

    // वेरिएबल के अनडिफाइंड एरर (Notice) को रोकने के लिए डिफॉल्ट्स
    $updated_by = isset($updated_by) ? $updated_by : '';
    $updated_type = isset($updated_type) ? $updated_type : '';
    $updated_at = isset($updated_at) ? $updated_at : date('Y-m-d H:i:s');
    $receipt_number = isset($receipt_number) ? $receipt_number : '';
    $patient_data = isset($patient_data) ? $patient_data : array('husband_name'=>'', 'husband_age'=>'', 'wife_name'=>'');

    // PHP code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        // Image Upload Logic
        if(!empty($_FILES['upload_photo_1']['tmp_name'])){
            $dest_path = $this->config->item('upload_path');
            $destination = $dest_path.'procedure-forms-uploads/';
            $NewImageName = rand(4,10000)."-".$_FILES['upload_photo_1']['name'];
            $transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
            move_uploaded_file($_FILES['upload_photo_1']['tmp_name'], $destination.$NewImageName);
            $_POST['upload_photo_1'] = $transaction_img;
        }
       
        // चेक करें कि क्या इस patient_id का डेटा पहले से मौजूद है
        $sql = "SELECT * FROM `sperm_dna_fragmentation2` WHERE patient_id='$patient_id'";
        $select_result = run_select_query($sql);
        
        // अगर डेटा नहीं है, तो सिर्फ तभी INSERT करें
        if(empty($select_result)){
            
            // mysql query to insert data
            $query = "INSERT INTO `sperm_dna_fragmentation2` SET ";
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
            // अगर डेटा पहले से मौजूद है, तो UPDATE ना करें, बल्कि सीधा वापस भेज दें
            header("location:" .$_SERVER['HTTP_REFERER']."?m=".base64_encode('Data is already saved and cannot be updated!').'&t='.base64_encode('error'));
            die();
        }
    }
   
    // फॉर्म में पुराना डेटा दिखाने के लिए सिर्फ patient_id से फेच करें
    $select_result = array();
    if(!empty($patient_id)) {
        $sql = "SELECT * FROM `sperm_dna_fragmentation2` WHERE patient_id='$patient_id'";
        $select_result = run_select_query($sql);
    }
    
    $select_result2 = array('uhid'=>'', 'appoitment_for'=>'');
    if(!empty($patient_id)) {
        $sql2 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
        $res2 = run_select_query($sql2);
        if(!empty($res2)) $select_result2 = $res2;
    }
    
    $select_result3 = array('center_code'=>'');
    if(!empty($select_result2['appoitment_for'])) {
        $sql3 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result2['appoitment_for']."'";
        $res3 = run_select_query($sql3);
        if(!empty($res3)) $select_result3 = $res3;
    }

    $dna_result = array();
    if(!empty($patient_id)) {
        $select_query = "SELECT * FROM `dna_fragmentation` WHERE patient_id='$patient_id' ";
        $dna_result = run_select_query($select_query); 
    }

    // 2. Define the 'is_complete' flag based on the result
    $is_complete = !empty($dna_result);

    // 3. Set the receipt number for the hidden input
    $final_receipt = ($is_complete && isset($dna_result['receipt_number'])) ? $dna_result['receipt_number'] : "";
?>

<div class="ga-pro">
<br>
<h3 style="text-align: center;  padding-bottom: 20px;">SPERM DNA FRAGMENTATION TEST REPORT</h3>
<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>

<form action="" enctype='multipart/form-data' method="post">
  
  <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <?php if ($is_complete): ?>
    <input type="hidden" value="<?php echo $final_receipt; ?>" name="receipt_number">
    
    <div class="alert alert-success" style="padding: 10px; border-left: 5px solid #00a65a; margin-top: 5px;">
        <i class="fa fa-check-circle"></i> 
        <strong>Verified:</strong> Dna Fragmentation clinical form is present. You may proceed.
    </div>

<?php else: ?>
    <div class="alert alert-danger" style="padding: 15px; margin-top: 5px; border-left: 5px solid #a94442;">
        <i class="fa fa-exclamation-triangle fa-2x pull-left" style="margin-right: 15px;"></i> 
        <strong>Clinical Data Incomplete!</strong><br>
        The following mandatory record is missing:
        <ul style="margin-top:10px;">
            <li>Dna Fragmentation Form (Missing for Receipt: <?php echo $receipt_number; ?>)</li>
        </ul>
        <p style="margin-top:10px;"><em>Please fill the Dna Fragmentation form before proceeding with this entry.</em></p>
    </div>
    
    <input type="hidden" name="receipt_number" value="">
    
    <style>
        /* Automatically hides the save button if clinical data is missing */
        #submitbutton, .btn-submit, button[type="submit"] { 
            display: none !important; 
        } 
    </style>
<?php endif; ?> 
<input type="hidden" value="<?php echo $appoitmented_date; ?>" class="form" name="appoitmented_date">
<div class="fg45rt">
<h5>PATIENT INFORMATION</h5>
</div>

<table width="100%">
  <tr>
    <td>HUSBAND NAME</td>
    <td> <?php echo isset($patient_data['husband_name']) ? $patient_data['husband_name'] : ''; ?>  </td>
    <td>AGE</td>
    <td> <?php echo isset($patient_data['husband_age']) ? $patient_data['husband_age'] : ''; ?> Years</td>
  </tr>
  <tr>
    <td>UHID</td>
    <td><?php echo (isset($select_result3['center_code']) ? $select_result3['center_code'] : '') . "/" . (isset($select_result2['uhid']) ? $select_result2['uhid'] : ''); ?></td>
  </tr>
  <tr>
    <td>WIFE NAME</td>
    <td><input type="text" class="WIFENAME" name="WIFE_NAME" value="<?php echo isset($patient_data['wife_name']) ? $patient_data['wife_name'] : ''; ?>"></td>
    <td>ID NO</td>
     <td> <input type="text" name="patient_id" value="<?php echo $patient_id;?>" readonly  > </td>
  </tr>
  <tr>
    <td> REF. BY DR: </td>
   <td> <input type="text" class="REF" name="REF"  value="<?php echo isset($select_result['REF'])?$select_result['REF']:""; ?>"> </td>
    <td>DATE</td>
    <td><input type="date" class="DATE" name="DATE"  value="<?php echo isset($select_result['DATE'])?$select_result['DATE']:""; ?>"> </td>
  </tr>
</table>

<div class="fg45rt">
<h5>CHARACTERSTICS</h5>
</div>
<table width="100%">
  <tr>
    <td>COLLECTION TIME</td>
    <td><input type="time" id="COLLECTIONTIME" name="COLLECTION_TIME"  value="<?php echo isset($select_result['COLLECTION_TIME'])?$select_result['COLLECTION_TIME']:""; ?>"></td>
    <td>EXAMINATION TIME</td>
    <td><input type="time" id="EXAMINATIONTIME" name="EXAMINATION_TIME"  value="<?php echo isset($select_result['EXAMINATION_TIME'])?$select_result['EXAMINATION_TIME']:""; ?>"></td>
  </tr>
  <tr>
    <td>COLLECTION TYPE</td>
    <td><input type="text" class="COLLECTIONTYPE" name="COLLECTION_TYPE"  value="<?php echo isset($select_result['COLLECTION_TYPE'])?$select_result['COLLECTION_TYPE']:""; ?>"> </td>
    <td>PLACE OF COLLECTION</td>
     <td><input type="text" class="PLACEOFCOLLECTION" name="PLACE_OF_COLLECTION" value="<?php echo isset($select_result['PLACE_OF_COLLECTION'])?$select_result['PLACE_OF_COLLECTION']:""; ?>"> </td>
  </tr>
  <tr>
    <td>SAMPLE</td>
    <td><input type="text" class="SAMPLE" name="SAMPLE" value="<?php echo isset($select_result['SAMPLE'])?$select_result['SAMPLE']:""; ?>">  </td>
    <td>ABSTINENCE</td>
    <td><input type="text" class="ABSTINENCE" name="ABSTINENCE"  value="<?php echo isset($select_result['ABSTINENCE'])?$select_result['ABSTINENCE']:""; ?>"> </td>
  </tr>
   <tr>
    <td>SPERM COUNT</td>
    <td><input type="text" class="SPERMCOUNT" name="SPERM_COUNT" value="<?php echo isset($select_result['SPERM_COUNT'])?$select_result['SPERM_COUNT']:""; ?>"> </td>
    <td>SEMEN VOLUME</td>
    <td><input type="text" class="SEMENVOLUME" name="SEMEN_VOLUME" value="<?php echo isset($select_result['SEMEN_VOLUME'])?$select_result['SEMEN_VOLUME']:""; ?>"> </td>
  </tr>
  <tr>
    <td>SPERM MOTILITY</td>
    <td><input type="text" class="SPERMMOTILITY" name="SPERM_MOTILITY" value="<?php echo isset($select_result['SPERM_MOTILITY'])?$select_result['SPERM_MOTILITY']:""; ?>"></td>
    <td>APPEARANCE</td>
    <td><input type="text" class="APPEARANCE" name="APPEARANCE" value="<?php echo isset($select_result['APPEARANCE'])?$select_result['APPEARANCE']:""; ?>"></td>
  </tr>
   <tr>
    <td>ROUND CELLS</td>
    <td><input type="text" class="ROUNDCELLS" name="ROUND_CELLS" value="<?php echo isset($select_result['ROUND_CELLS'])?$select_result['ROUND_CELLS']:""; ?>"> </td>
    <td>LIQUEFACTION</td>
    <td><input type="text" class="LIQUEFACTION" name="LIQUEFACTION" value="<?php echo isset($select_result['LIQUEFACTION'])?$select_result['LIQUEFACTION']:""; ?>"> </td>
  </tr>
  <tr>
    <td>NORMAL FORMS</td>
    <td><input type="text" class="NORMALFORMS" name="NORMAL_FORMS" value="<?php echo isset($select_result['NORMAL_FORMS'])?$select_result['NORMAL_FORMS']:""; ?>"></td>
    <td>VISCOCITY</td>
    <td><input type="text" class="VISCOCITY" name="VISCOCITY" value="<?php echo isset($select_result['VISCOCITY'])?$select_result['VISCOCITY']:""; ?>"></td>
  </tr>
</table>

<div class="fg45rt">
<h5 style="text-align: left;">DNA FRAGMENTATION INDEX (DFI): <input type="text" class="dna_frag_val" name="dna_frag_val" value="<?php echo isset($select_result['dna_frag_val'])?$select_result['dna_frag_val']:""; ?>"></h5>
</div>

<?php 
$Fragmented = 0;
$Not_Fragmented = 0;
$db_patient_id = isset($select_result['patient_id']) ? $select_result['patient_id'] : ''; 

if(!empty($db_patient_id))  { 
    $BIG_HALO = isset($select_result['BIG_HALO']) ? $select_result['BIG_HALO'] : 0;
    $MEDIUM_HALO = isset($select_result['MEDIUM_HALO']) ? $select_result['MEDIUM_HALO'] : 0;
    $SMALL_HALO = isset($select_result['SMALL_HALO']) ? $select_result['SMALL_HALO'] : 0;
    $WITHOUT_HALO = isset($select_result['WITHOUT_HALO']) ? $select_result['WITHOUT_HALO'] : 0;

    $Not_Fragmented = ((!empty($BIG_HALO)?$BIG_HALO:0)+(!empty($MEDIUM_HALO)?$MEDIUM_HALO:0));
    $Fragmented = ((!empty($SMALL_HALO)?$SMALL_HALO:0)+(!empty($WITHOUT_HALO)?$WITHOUT_HALO:0));
?>
<table class="bv45rt" width="100%">
  <tr>
    <td style="width:70%;">
      <!-- यहाँ पहले पाईचार्ट का कोड था जो आपने बंद कर रखा है -->
    </td>
    <td>  
      <input type="file" name="upload_photo_1">
      <?php if(!empty($select_result['upload_photo_1'])): ?>
         <img src="<?php echo $select_result['upload_photo_1'];?>" style="max-width:400px; height:auto;">
      <?php endif; ?>
    </td>
  </tr>
</table>
<?php } ?>

<p style="font-weight: 600;">NO. of Sperm Evaluated: <input type="text" class="Evaluated" name="Evaluated" value="<?php echo isset($select_result['Evaluated'])?$select_result['Evaluated']:""; ?>"> </p>

<p style="font-weight:600; font-size: 20px; text-decoration: underline;">Interpretation of Result:</p>
<div class="bvfg45tr">
<p>0-15% Fragmentation : Higher fertility potential</p>
<p>word_greater_than 15-25% Fragmentation :Good to fair fertility potential</p>
<p>word_greater_than 25% Fragmentation : Poor fertility potential</p>
 </div>

<div class="fg45rt" style="margin-top: 20px;">
<h5 style="text-align: left;">Comment:</h5>
</div>

<table class="bv45rt">
  <tr>
    <td>
      Prepared By: <input type="text" class="Prepared" name="prepared_by" value="<?php echo isset($select_result['prepared_by'])?$select_result['prepared_by']:""; ?>">
    </td>
    <td>
     Checked By:  <input type="text" class="checked_by" name="checked_by" value="<?php echo isset($select_result['checked_by'])?$select_result['checked_by']:""; ?>">
    </td>
  </tr>
</table>
<div class="sec2">
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
</div> 
<input type="submit" name="submit" value="submit">
</form>
</div>

<!-- ==================== PRINT PREVIEW SECTION ==================== -->
<div class="row" id="print_this_section" style="display:block; margin-top: 40px; border-top: 2px dashed #000; padding-top: 20px;">
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="https://indiaivf.website/assets/images/india-ivf-logo.webp"></td>
   <td style="width:50%;padding:5px;" colspan="2"><h3 style="margin-top:20px;">SPERM DNA FRAGMENTATION TEST REPORT</h3></td>
</tr>
</table>

<table width="100%">
 <tr>
    <td colspan="4" width="100%"><h5>PATIENT INFORMATION</h5></td>    
  </tr>
  <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">HUSBAND NAME</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($patient_data['husband_name']) ? $patient_data['husband_name'] : ''; ?></td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">AGE</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($patient_data['husband_age']) ? $patient_data['husband_age'] : ''; ?> Years</td>
  </tr>
  <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">WIFE NAME</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($patient_data['wife_name']) ? $patient_data['wife_name'] : ''; ?></td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">UHID</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo (isset($select_result3['center_code']) ? $select_result3['center_code'] : '') . "/" . (isset($select_result2['uhid']) ? $select_result2['uhid'] : ''); ?></td>
  </tr>
  <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">ID NO</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"> <?php echo $patient_id;?></td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"> REF. BY DR: </td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['REF'])?$select_result['REF']:""; ?></td>
  </tr>
  <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">DATE</td>
    <td colspan="3" width="75%" style="border:1px solid;padding:5px; text-align: left;"><?php echo isset($select_result['DATE'])?$select_result['DATE']:""; ?></td>
  </tr>
</table>

<div class="fg45rt">
<h5>CHARACTERSTICS</h5>
</div>
<table width="100%">
  <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">COLLECTION TIME</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['COLLECTION_TIME'])?$select_result['COLLECTION_TIME']:""; ?></td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">EXAMINATION TIME</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['EXAMINATION_TIME'])?$select_result['EXAMINATION_TIME']:""; ?></td>
  </tr>
  <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">COLLECTION TYPE</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['COLLECTION_TYPE'])?$select_result['COLLECTION_TYPE']:""; ?> </td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">PLACE OF COLLECTION</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['PLACE_OF_COLLECTION'])?$select_result['PLACE_OF_COLLECTION']:""; ?></td>
  </tr>
  <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">SAMPLE</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['SAMPLE'])?$select_result['SAMPLE']:""; ?></td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">ABSTINENCE</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['ABSTINENCE'])?$select_result['ABSTINENCE']:""; ?></td>
  </tr>
   <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">SPERM COUNT</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['SPERM_COUNT'])?$select_result['SPERM_COUNT']:""; ?></td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">SEMEN VOLUME</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['SEMEN_VOLUME'])?$select_result['SEMEN_VOLUME']:""; ?></td>
  </tr>
  <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">SPERM MOTILITY</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['SPERM_MOTILITY'])?$select_result['SPERM_MOTILITY']:""; ?></td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">APPEARANCE</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['APPEARANCE'])?$select_result['APPEARANCE']:""; ?></td>
  </tr>
   <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">ROUND CELLS</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['ROUND_CELLS'])?$select_result['ROUND_CELLS']:""; ?></td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">LIQUEFACTION</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['LIQUEFACTION'])?$select_result['LIQUEFACTION']:""; ?></td>
  </tr>
  <tr>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">NORMAL FORMS</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['NORMAL_FORMS'])?$select_result['NORMAL_FORMS']:""; ?></td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;">VISCOCITY</td>
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo isset($select_result['VISCOCITY'])?$select_result['VISCOCITY']:""; ?></td>
  </tr>
</table>

<div class="fg45rt">
<h5 width="100%" style="border:1px solid;padding:5px;text-align:left;">DNA FRAGMENTATION INDEX (DFI): <?php echo isset($select_result['dna_frag_val'])?$select_result['dna_frag_val']:""; ?></h5>
</div>

<?php if(!empty($db_patient_id)) { ?>
<table class="bv45rt" width="100%" style="border:1px solid;padding:5px;">
  <tr>
    <td style="width:70%;"></td>
    <td>  
      <?php if(!empty($select_result['upload_photo_1'])): ?>
         <img src="<?php echo $select_result['upload_photo_1'];?>" style="max-width:400px; height:auto;">
      <?php endif; ?>
    </td>
  </tr>
</table>
<?php } ?>

<p style="font-weight: 600;">NO. of Sperm Evaluated: <?php echo isset($select_result['Evaluated'])?$select_result['Evaluated']:""; ?></p>

<table class="bv45rt" width="100%">
<tr>
    <td colspan="4" width="100%" style="text-align:left;border:1px solid;padding:5px;">
   <p style="font-weight:600; font-size: 20px; text-decoration: underline;">Interpretation of Result:</p>
<p>0-15% Fragmentation : Higher fertility potential</p>
<p>word_greater_than 15-25% Fragmentation :Good to fair fertility potential</p>
<p>word_greater_than 25% Fragmentation : Poor fertility potential</p>
<h5 style="text-align: left;">Comment:</h5>
</td>
  </tr>
  <tr>
    <td colspan="2" width="50%" style="border:1px solid;padding:5px;">
      Prepared By: <?php echo isset($select_result['prepared_by'])?$select_result['prepared_by']:""; ?>
    </td>
    <td colspan="2" width="50%" style="border:1px solid;padding:5px;">
     Checked By:  <?php echo isset($select_result['checked_by'])?$select_result['checked_by']:""; ?>
    </td>
  </tr>
<tr>
 <td colspan="4" width="100%" style="text-align:left;border:1px solid;padding:5px;">
 <label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
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
  td {border: 1px solid #000; text-align: center; font-weight: 600;}
  .fg45rt { border: 1px solid #000; background: #e1d6d6; }
  .fg45rt h5 { padding: 0px; margin: 0px; text-align: center; font-weight: 600; font-size: 18px; }
  .fg45rt4r { display: flex; } 
  .fg45rt4r table { margin-right: 50px; }
  .bvfg45tr p {margin: 0px; padding: 0px;}
  .bv45rt {margin-top: 20px;}
  .bv45rt td {border: none;} 
</style>