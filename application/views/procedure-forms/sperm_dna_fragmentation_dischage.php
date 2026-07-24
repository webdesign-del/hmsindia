<?php
    // php code to Insert data into mysql database from input text
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
      
        $select_query = "SELECT * FROM `sperm_dna_fragmentation2` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `sperm_dna_fragmentation2` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE sperm_dna_fragmentation2 SET ";
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
    $select_query = "SELECT * FROM `sperm_dna_fragmentation2` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
                <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">Intra Uterine Insemination</h3></td>
           </tr>
        </table>

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
    <td><?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></td>
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
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">
  
<div class="printtable prtable" id="printtable" style="display:none;"> 
<div class="ga-pro">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
<tr>
   <td style="width:50%;padding:5px;" colspan="2"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
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
    <td colspan="1" width="25%" style="border:1px solid;padding:5px;"><?php echo ($select_result5['center_code'] ?? '')."/".($select_result4['uhid'] ?? ''); ?></td>
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