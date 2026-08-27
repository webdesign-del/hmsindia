<?php

    if(isset($_POST['submit'])){

        unset($_POST['submit']);

        // FIX FOR CHECKBOX ARRAYS: Convert array to string before saving to database
        if(isset($_POST['applicablemedicine']) && is_array($_POST['applicablemedicine'])) {
            $_POST['applicablemedicine'] = implode(',', $_POST['applicablemedicine']);
        }

        // ========================================================================
        // 1. FORMAT AND INSERT DATA FOR `hms_doctor_consultation` TABLE
        // ========================================================================
        
        // DIRECT SERIALIZE array to get format: a:8:{i:0;s:2:"22";i:1;s:2:"24"...}
        $female_serialized = "";
        if(isset($_POST['female_minvestigation_suggestion_list']) && is_array($_POST['female_minvestigation_suggestion_list'])){
            $female_serialized = serialize($_POST['female_minvestigation_suggestion_list']);
        }

        $male_serialized = "";
        if(isset($_POST['male_minvestigation_suggestion_list']) && is_array($_POST['male_minvestigation_suggestion_list'])){
            $male_serialized = serialize($_POST['male_minvestigation_suggestion_list']);
        }

        // Check for both spellings depending on what you named it in the HTML form
        $investigation_status = '0';
        if(isset($_POST['investigation_suggestion'])) {
            $investigation_status = $_POST['investigation_suggestion'];
        } elseif(isset($_POST['investation_suggestion'])) {
            $investigation_status = $_POST['investation_suggestion'];
        }

        // Get Current Date (Format: YYYY-MM-DD)
        // Note: Agar aapko time bhi save karna hai, toh date('Y-m-d H:i:s') use karein
        $current_date = date('Y-m-d');

        // INSERT into the consultation table
        $consultation_insert_query = "INSERT INTO `hms_doctor_consultation` SET 
            `patient_id` = '$patient_id',
            `consultation_date` = '$current_date',
            `female_minvestigation_suggestion_list` = '".addslashes($female_serialized)."',
            `male_minvestigation_suggestion_list` = '".addslashes($male_serialized)."',
            `investation_suggestion` = '".addslashes($investigation_status)."'"; 
            
        run_form_query($consultation_insert_query);
        // ========================================================================


        // ========================================================================
        // 🔥 CRITICAL FIX: Unset ALL consultation fields so they don't break the embryo loop
        // ========================================================================
        if(isset($_POST['investigation_suggestion'])){
            unset($_POST['investigation_suggestion']);
        }
        if(isset($_POST['investation_suggestion'])){
            unset($_POST['investation_suggestion']);
        }
        if(isset($_POST['female_minvestigation_suggestion_list'])){
            unset($_POST['female_minvestigation_suggestion_list']);
        }
        if(isset($_POST['male_minvestigation_suggestion_list'])){
            unset($_POST['male_minvestigation_suggestion_list']);
        }
        // ========================================================================


        // ========================================================================
        // 2. EXISTING LOGIC FOR `pre_embryo_transfer` TABLE
        // ========================================================================
        $select_query = "SELECT * FROM `pre_embryo_transfer` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 

        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `pre_embryo_transfer` SET ";
            $sqlArr = array();

            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }       
            $query .= implode(',' , $sqlArr);

        } else {
            // mysql query to update data
            $query = "UPDATE pre_embryo_transfer SET ";
            $sqlArr = array();
            
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'"; // Added addslashes for safety
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

    $select_query = "SELECT * FROM `pre_embryo_transfer` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);  

    // Prepare applicablemedicine array for checkboxes
    $applicablemedicine = array();
    if(!empty($select_result['applicablemedicine'])){
        $applicablemedicine = explode(',', $select_result['applicablemedicine']);
    }

    $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3);  
    
    $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."' and paitent_type='new_patient'";
    $select_result1 = run_select_query($sql1);
        
    $sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result1['appoitment_for']."'";
    $select_result5 = run_select_query($sql5);  

    $procedure_sql = "SELECT ID, procedure_name, category FROM hms_procedures WHERE ID = '$procedure_id'";
    $proc_result = run_select_query($procedure_sql);

    // ========================================================================
    // UPDATE FOR HTML VIEW: Handle direct unserialize instead of explode
    // ========================================================================
    $saved_female_inv = !empty($select_result['female_minvestigation_suggestion_list']) ? unserialize($select_result['female_minvestigation_suggestion_list']) : array();
    if(!is_array($saved_female_inv)) $saved_female_inv = array();

    $saved_male_inv = !empty($select_result['male_minvestigation_suggestion_list']) ? unserialize($select_result['male_minvestigation_suggestion_list']) : array();
    if(!is_array($saved_male_inv)) $saved_male_inv = array();
    // ========================================================================

    $procedure_billing_sql = "SELECT * FROM hms_patient_procedure WHERE receipt_number = '$receipt_number'";
    $proc_bill_result = run_select_query($procedure_billing_sql);
    
    // find progesterone dates automatically
    $progesterone_dates = [];

    for ($i = 11; $i <= 27; $i++) {
        $p_key = "progesterone".$i;
        $d_key = "date".$i;

        if (!empty($select_result[$p_key])) {
            $progesterone_dates[] = $select_result[$d_key];
        }
    }

    $final_progesterone_date = "";
    if (!empty($progesterone_dates)) {
        $final_progesterone_date = $progesterone_dates[0];
    }

    // API data
    $data = [
        "lead_id" => trim($select_result1['crm_id']),
        "patient_id" => $patient_id,
        "procedure_type_name" => $proc_result['procedure_name'] . ', ' . (new DateTime($proc_bill_result['on_date']))->format('Y-m-d'),
        "progesterone_date" => $final_progesterone_date
    ];

    // send API
   $curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://flertility.in/lead/lead-journey/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'X-Hms-Api-Token: _dkGEDrhpSCpaZVx8-tRbTkq66MHvl_4R5O4fCZ6NPGB7eO7JOThQw'
    ],
]);

$response = curl_exec($curl);
curl_close($curl);
      
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" type="text/css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>

<form enctype='multipart/form-data' class="searchform" name="form" action="" method="POST">

    <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
    <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
    <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
    <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
    <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
    <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
    <input type="hidden" value="pending" name="status">
    
    <table style="width:100%; border:1px solid #cdcdcd;" id="" border="1">
        <tr>
            <th width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></th>
            <th width="50%" colspan="2" style="border:1px solid #cdcdcd;"><center><h3>PRE EMBRYO TRANSFER</h3></center></th>
        </tr>
    </table>    
    
    <table width="100%" class="vb45rt">
        <tr style="background: #b3b9b7;">
            <td colspan="2" width="33%" style="border:1px solid;padding:5px;">
                <strong>UHID : <?php echo $select_result5['center_code']."/".$select_result1['uhid']; ?></strong>
            </td>
            <td colspan="2" width="33%" style="border:1px solid;padding:5px;">
                <strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
            </td>
            <td colspan="2" width="33%" style="border:1px solid;padding:5px;">
                <strong>IIC ID: <?php echo $patient_id; ?></strong>
            </td>
        </tr>
    </table>
       
    <table class="table-bordered" width="100%">
        <tr>        
            <td colspan="2">
                <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
                        isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
                        isset($select_result['updated_type']) && !empty($select_result['updated_type'])
                        ){?>
                    <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
                <?php } ?>
            </td>
        </tr>
    </table>
            
    <div class="table-responsive">
        <table class="table-bordered" width="100%">
			<tr>
				<td colspan="1">LAST MENSTRUAL PERIOD</td>
				<td colspan="12"><input  type="date" value="<?php echo isset($select_result['last_menstrual_period'])?$select_result['last_menstrual_period']:""; ?>"   name="last_menstrual_period" class="form-control"></td>
			</tr>
            <tr>
                <td style="width: 20%;"><br></td>
                <td colspan="10" style="background-color: yellow;">ESTROGEN</td>
                <td colspan="17" style="background-color: orange;">PROGESTERONE</td>
            </tr>

            <tr>
                <td>DATE</td>
                <?php for($i=1; $i<=10; $i++){ ?>
                    <td style="background-color: yellow;"><input type="date" value="<?php echo isset($select_result["date$i"])?$select_result["date$i"]:""; ?>" name="date<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
                <?php for($i=11; $i<=27; $i++){ ?>
                    <td style="background-color: orange;"><input type="date" value="<?php echo isset($select_result["date$i"])?$select_result["date$i"]:""; ?>" name="date<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
            </tr>

            <tr>
                <td>ENDOMETRIAL THICKNESS (cm)</td>
                <?php for($i=1; $i<=10; $i++){ ?>
                    <td style="background-color: yellow;"><input type="number" value="<?php echo isset($select_result["endometrial_thickness$i"])?$select_result["endometrial_thickness$i"]:""; ?>" min="0" name="endometrial_thickness<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
                <?php for($i=11; $i<=27; $i++){ ?>
                    <td style="background-color: orange;"><input type="number" value="<?php echo isset($select_result["endometrial_thickness$i"])?$select_result["endometrial_thickness$i"]:""; ?>" min="0" name="endometrial_thickness<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
            </tr>

            <tr>
                <td>REMARKS</td>
                <?php for($i=1; $i<=10; $i++){ ?>
                    <td style="background-color: yellow;"><input type="text" value="<?php echo isset($select_result["remarks$i"])?$select_result["remarks$i"]:""; ?>" maxlength="20" name="remarks<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
                <?php for($i=11; $i<=27; $i++){ ?>
                    <td style="background-color: orange;"><input type="text" value="<?php echo isset($select_result["remarks$i"])?$select_result["remarks$i"]:""; ?>" maxlength="20" name="remarks<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
            </tr>

            <tr>
                <td>FOLLOWUP ON</td>
                <?php for($i=1; $i<=10; $i++){ ?>
                    <td style="background-color: yellow;"><input type="text" value="<?php echo isset($select_result["followup_on$i"])?$select_result["followup_on$i"]:""; ?>" maxlength="20" name="followup_on<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
                <?php for($i=11; $i<=27; $i++){ ?>
                    <td style="background-color: orange;"><input type="text" value="<?php echo isset($select_result["followup_on$i"])?$select_result["followup_on$i"]:""; ?>" maxlength="20" name="followup_on<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
            </tr>

            <tr>
                <td>SERUM ESTRADIOL (E2) LEVEL</td>
                <?php for($i=1; $i<=10; $i++){ ?>
                    <td style="background-color: yellow;"><input type="text" value="<?php echo isset($select_result["serum_e2_level$i"])?$select_result["serum_e2_level$i"]:""; ?>" maxlength="20" name="serum_e2_level<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
                <?php for($i=11; $i<=27; $i++){ ?>
                    <td style="background-color: orange;"><input type="text" value="<?php echo isset($select_result["serum_e2_level$i"])?$select_result["serum_e2_level$i"]:""; ?>" maxlength="20" name="serum_e2_level<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
            </tr>

            <tr>
                <td>SERUM PROGESTERONE LEVEL</td>
                <?php for($i=1; $i<=10; $i++){ ?>
                    <td style="background-color: yellow;"><input type="text" value="<?php echo isset($select_result["serum_progesterone_level$i"])?$select_result["serum_progesterone_level$i"]:""; ?>" maxlength="20" name="serum_progesterone_level<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
                <?php for($i=11; $i<=27; $i++){ ?>
                    <td style="background-color: orange;"><input type="text" value="<?php echo isset($select_result["serum_progesterone_level$i"])?$select_result["serum_progesterone_level$i"]:""; ?>" maxlength="20" name="serum_progesterone_level<?php echo $i; ?>" class="form-control"></td>
                <?php } ?>
            </tr>
        </table>
        
        <br>
        <table width="100%" border="1" style="border-collapse: collapse; text-align: left; margin-bottom:15px;" cellpadding="5">
            <tbody>
                <tr style="background-color: #f2f2f2;">
                    <td width="5%"><strong>Check</strong></td>
                    <td width="20%"><strong>Medication</strong></td>
                    <td width="10%"><strong>Dosage</strong></td>
                    <td width="10%"><strong>Route</strong></td>
                    <td width="25%"><strong>Times</strong></td>
                    <td width="10%"><strong>Timings</strong></td>
                    <td width="10%"><strong>When to start</strong></td>
                    <td width="10%"><strong>How many days</strong></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabCrocin" <?php if(in_array('TabCrocin',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Tab Crocin</td>
                    <td>500 mg</td>
                    <td>Oral</td>
                    <td>SOS<br><strong>Maximum three times at interval of 6 hrs (if Require )</strong></td>
                    <td>After meals</td>
                    <td>SOS (if pain)</td>
                    <td></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Sypcremaffin" <?php if(in_array('Sypcremaffin',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Sypcremaffin</td>
                    <td>ONE TSF</td>
                    <td>Oral</td>
                    <td>SOS</td>
                    <td>After dinner</td>
                    <td>SOS (if constipation)</td>
                    <td></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="EndofertTab2MG" <?php if(in_array('EndofertTab2MG',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Endofert Tab 2MG</td>
                    <td>1TAB</td>
                    <td>Oral</td>
                    <td>
                        <input type="checkbox" name="applicablemedicine[]" value="gufitwice" <?php if(in_array('gufitwice',$applicablemedicine)){echo "checked";}?>> Twice <br>
                        <input type="checkbox" name="applicablemedicine[]" value="gufithrice" <?php if(in_array('gufithrice',$applicablemedicine)){echo "checked";}?>> thrice <br>
                        <input type="checkbox" name="applicablemedicine[]" value="gufifour" <?php if(in_array('gufifour',$applicablemedicine)){echo "checked";}?>> four times daily
                    </td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>20 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabEcosprin75mg" <?php if(in_array('TabEcosprin75mg',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Tab Ecosprin 75 mg</td>
                    <td>1TAB</td>
                    <td>Oral</td>
                    <td>
                        <input type="checkbox" name="applicablemedicine[]" value="eco75once" <?php if(in_array('eco75once',$applicablemedicine)){echo "checked";}?>> once<br>
                        <input type="checkbox" name="applicablemedicine[]" value="eco75twice" <?php if(in_array('eco75twice',$applicablemedicine)){echo "checked";}?>> twice
                    </td>
                    <td>After meals</td>
                    <td>Tomorrow</td>
                    <td>20 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Tabvigor50mg" <?php if(in_array('Tabvigor50mg',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Tab Vigor 50 mg</td>
                    <td>50 MG</td>
                    <td>Oral</td>
                    <td>once</td>
                    <td>After meals</td>
                    <td>HS</td>
                    <td>16 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone5mg" <?php if(in_array('TabWysolone5mg',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Tab Wysolone 5mg</td>
                    <td>5mg for --- days followed by</td>
                    <td>oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>Tomorrow</td>
                    <td>----------</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone" <?php if(in_array('TabWysolone',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Tab Wysolone 10mg</td>
                    <td>10mg for---days followed by</td>
                    <td>oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>Tomorrow</td>
                    <td>----------</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone15mg" <?php if(in_array('TabWysolone15mg',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Tab Wysolone 15mg</td>
                    <td>15mg for---days</td>
                    <td>oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>Tomorrow</td>
                    <td>----------</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilL" <?php if(in_array('BiophilL',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Biophil L</td>
                    <td>1 CAP</td>
                    <td>Oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>30 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilO" <?php if(in_array('BiophilO',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Biophil O</td>
                    <td>1 CAP</td>
                    <td>Oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>30 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilQ3" <?php if(in_array('BiophilQ3',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Biophil Q3</td>
                    <td>1 CAP</td>
                    <td>Oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>30 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BIOLARG" <?php if(in_array('BIOLARG',$applicablemedicine)){echo "checked";}?>></td>
                    <td>BIOLARG</td>
                    <td>1 SACHET</td>
                    <td>Oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>30 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BIOPHILVITA" <?php if(in_array('BIOPHILVITA',$applicablemedicine)){echo "checked";}?>></td>
                    <td>BIOPHIL VITA</td>
                    <td>1 cap</td>
                    <td>oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>30 days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="IPARIN40MG" <?php if(in_array('IPARIN40MG',$applicablemedicine)){echo "checked";}?>></td>
                    <td>IPARIN 40 MG</td>
                    <td>40 mg</td>
                    <td>subcutaneous</td>
                    <td>
                        <input type="checkbox" name="applicablemedicine[]" value="oxyOncedaily" <?php if(in_array('oxyOncedaily',$applicablemedicine)){echo "checked";}?>> Once daily<br>
                        <input type="checkbox" name="applicablemedicine[]" value="oxyalternate" <?php if(in_array('oxyalternate',$applicablemedicine)){echo "checked";}?>> alternate<br>
                        <input type="checkbox" name="applicablemedicine[]" value="oxybiweekly" <?php if(in_array('oxybiweekly',$applicablemedicine)){echo "checked";}?>> biweekly<br>
                        <input type="checkbox" name="applicablemedicine[]" value="oxyweekly" <?php if(in_array('oxyweekly',$applicablemedicine)){echo "checked";}?>> weekly
                    </td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>20 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabAllegra" <?php if(in_array('TabAllegra',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Tab Allegra</td>
                    <td>1 TAB</td>
                    <td>Oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>16 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabMontairLC" <?php if(in_array('TabMontairLC',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Tab Montair LC</td>
                    <td>1TAB</td>
                    <td>Oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>16 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabShelcal500mg" <?php if(in_array('TabShelcal500mg',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Tab Shelcal 500 mg</td>
                    <td>1TAB</td>
                    <td>Oral</td>
                    <td>Once daily</td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>16 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Estogel" <?php if(in_array('Estogel',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Estogel</td>
                    <td>2.5 gm</td>
                    <td>Locally</td>
                    <td>
                        <input type="checkbox" name="applicablemedicine[]" value="estoonce" <?php if(in_array('estoonce',$applicablemedicine)){echo "checked";}?>> Once <br>
                        <input type="checkbox" name="applicablemedicine[]" value="estotwice" <?php if(in_array('estotwice',$applicablemedicine)){echo "checked";}?>> twice <br>
                        <input type="checkbox" name="applicablemedicine[]" value="estothrice" <?php if(in_array('estothrice',$applicablemedicine)){echo "checked";}?>> thrice <br>
                        <input type="checkbox" name="applicablemedicine[]" value="estofour" <?php if(in_array('estofour',$applicablemedicine)){echo "checked";}?>> four times to be applied locally daily
                    </td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>16 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Lenzettospray" <?php if(in_array('Lenzettospray',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Lenzetto Spray</td>
                    <td>1 spray</td>
                    <td>Locally</td>
                    <td>
                        <input type="checkbox" name="applicablemedicine[]" value="lenonce" <?php if(in_array('lenonce',$applicablemedicine)){echo "checked";}?>> Once <br>
                        <input type="checkbox" name="applicablemedicine[]" value="lentwice" <?php if(in_array('lentwice',$applicablemedicine)){echo "checked";}?>> twice times to be applied
                    </td>
                    <td></td>
                    <td>immediately</td>
                    <td>16 Days</td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapCalcitasD3" <?php if(in_array('CapCalcitasD3',$applicablemedicine)){echo "checked";}?>></td>
                    <td>Cap Calcitas D3</td>
                    <td>60000IU</td>
                    <td>oral</td>
                    <td>weekly</td>
                    <td>After meals</td>
                    <td>immediately</td>
                    <td>16 Days</td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align:center;"><strong>There are No Substitutes</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col-md-6 nb56ty">
                <label for="other">Other Medication1:</label>
                <input type="text" class="form-control other1" name="Other_Medication1" value="<?php echo isset($select_result['Other_Medication1'])?$select_result['Other_Medication1']:""; ?>">
            </div>  
            <div class="col-md-6 nb56ty">
                <label for="other">Other Medication2:</label>
                <input type="text" class="form-control other2" name="Other_Medication2" value="<?php echo isset($select_result['Other_Medication2'])?$select_result['Other_Medication2']:""; ?>">
            </div> 
        </div>

        <div class="sec2 mb-3 mt-2" style="color:red; font-weight:bold;">
            <label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
        </div>
        <table width="100%">
            <tr>
                <td>DOCTOR <input type="text" readonly="" value="<?php if (!empty($select_result['doctor'])) {  echo $select_result['doctor']; } else {  echo isset($_SESSION['logged_doctor']['name']) ? $_SESSION['logged_doctor']['name'] : ''; }  ?>" maxlength="20" name="doctor" class="form-control"></td>
                <td>COUNSELLOR <input type="text" value="<?php echo isset($select_result['counsellor'])?$select_result['counsellor']:""; ?>" maxlength="20" name="counsellor" class="form-control"></td>
                <td>NURSE <input type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>" maxlength="20" name="nurse" class="form-control"></td>
            </tr>
        </table>

		 <div class="section-card">
               <div class="section-header">
                  <i class="fa fa-flask"></i> IIC Investigations Advised
                  <label class="checkbox-enhanced pull-right">
                  <input type="checkbox" id="investation_suggestion" value="1" name="investation_suggestion" <?php echo !empty($select_result['investation_suggestion']) ? 'checked' : ''; ?> />
                  Enable Investigations
                  </label>
               </div>
               <div class="section-content" style="margin-bottom: 80px;">
                  <table class="table table-enhanced">
                     <thead>
                        <tr>
                           <th style="width: 50%;">Patient</th>
                           <th style="width: 50%;">Spouse</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>
                              <select class="form-control multidselect_dropdown_1" multiple id="female_minvestigation_suggestion_list" name="female_minvestigation_suggestion_list[]">
   <?php if(!empty($master_investigations)) { 
      foreach($master_investigations as $key => $val) { 
          $selected = in_array($val['master_id'], $saved_female_inv) ? 'selected' : '';
      ?>
   <option value="<?php echo $val['master_id']; ?>" <?php echo $selected; ?>><?php echo $val['investigation_name']; ?></option>
   <?php  } } ?>
   <option value="0">NA</option>
</select>
                           </td>
                           <td>
                              <select class="form-control multidselect_dropdown_1" multiple id="male_minvestigation_suggestion_list" name="male_minvestigation_suggestion_list[]">
   <?php if(!empty($master_investigations)) { 
      foreach($master_investigations as $key => $val) { 
          $selected = in_array($val['master_id'], $saved_male_inv) ? 'selected' : '';
      ?>
   <option value="<?php echo $val['master_id']; ?>" <?php echo $selected; ?>><?php echo $val['investigation_name']; ?></option>
   <?php  } } ?>
   <option value="0">NA</option>
</select>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>

    </div>
    <div class="card-footer">
        <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
    </div>

</form>


<!-- print -->

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
<table style="width:100%; border:1px solid #cdcdcd;" id="" border="1">
<tr>
        <th width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></th>
		<th width="50%" colspan="2" style="border:1px solid #cdcdcd;"><center><h3>PRE EMBRYO TRANSFER</h3></center></th>
				
</tr>

	<!--<tr>

			<td colspan="2">SELF CYCLE (S)</td>

			<td style="color: black;" colspan="2">SURROGATE MOTHER CYCLE (SUR)</td>

		</tr>-->

<tr>

			<td colspan="2">Patient name</td>

			<td style="width:20%"><?php echo isset($select_result['partners_name'])?$select_result['partners_name']:""; ?></td>

			<!--<td style="color: black;">ART bank reg no</td>

			<td style="width:20%"><?php echo isset($select_result['art_bank_reg_no'])?$select_result['art_bank_reg_no']:""; ?></td>-->

		</tr>
		
		<tr>

			<td colspan="2">UHID</td>

			<td style="width:20%" colspan="2"> <?php echo $select_result5['center_code']."/".$select_result1['uhid']; ?> </td>


		</tr>

<tr>

			<td colspan="2">Patients ID</td>

			<td style="width:20%"><?php echo isset($select_result['form_id'])?$select_result['form_id']:""; ?></td>

			<!--<td style="color: black;">Surrogate ID</td>

			<td style="width:20%"><?php echo isset($select_result['surrogate_id'])?$select_result['surrogate_id']:""; ?></td>-->

		</tr>

		<tr>

			<td colspan="2">LAST MENSTRUAL PERIOD</td>

			<td colspan="2" style="width:20%"><?php echo isset($select_result['last_menstrual_period'])?$select_result['last_menstrual_period']:""; ?></td>

		</tr>


</table>

<table style="width:100%; border:1px solid #cdcdcd;" id="" border="1">


<tr>

				<td style="width: 10%;"><br></td>

				<td colspan="10">ESTROGEN</td>

				<td colspan="17" >PROGESTERONE</td>

			</tr>

			<tr>

				<td>Day of Stimulation</td>

				<td>1</td>

				<td>2</td>

				<td>3</td>

				<td>4</td>

				<td>5</td>

				<td>6</td>

				<td>7</td>

				<td>8</td>

				<td>9</td>

				<td>10</td>

				<td>11</td>

				<td>12</td>

				<td>13</td>

				<td>14</td>

				<td>15</td>

				<td>16</td>

				<td>17</td>

				<td>18</td>

				<td>19</td>

				<td>20</td>

				<td>21</td>

				<td>22</td>

				<td>23</td>

				<td>24</td>

				<td>25</td>

				<td>26</td>

				<td>27</td>

			</tr>

			<tr>

				<td>DATE</td>

				<td><?php echo isset($select_result['date1'])?$select_result['date1']:""; ?></td>

				<td><?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></td>

				<td><?php echo isset($select_result['date3'])?$select_result['date3']:""; ?></td>

				<td><?php echo isset($select_result['date4'])?$select_result['date4']:""; ?></td>

				<td><?php echo isset($select_result['date5'])?$select_result['date5']:""; ?></td>

				<td><?php echo isset($select_result['date6'])?$select_result['date6']:""; ?></td>

				<td><?php echo isset($select_result['date7'])?$select_result['date7']:""; ?></td>

				<td><?php echo isset($select_result['date8'])?$select_result['date8']:""; ?></td>

				<td><?php echo isset($select_result['date9'])?$select_result['date9']:""; ?></td>

				<td><?php echo isset($select_result['date10'])?$select_result['date10']:""; ?></td>

				<td><?php echo isset($select_result['date11'])?$select_result['date11']:""; ?></td>

				<td><?php echo isset($select_result['date12'])?$select_result['date12']:""; ?></td>

				<td><?php echo isset($select_result['date13'])?$select_result['date13']:""; ?></td>

				<td><?php echo isset($select_result['date14'])?$select_result['date14']:""; ?></td>

				<td><?php echo isset($select_result['date15'])?$select_result['date15']:""; ?></td>

				<td><?php echo isset($select_result['date16'])?$select_result['date16']:""; ?></td>

				<td><?php echo isset($select_result['date17'])?$select_result['date17']:""; ?></td>

				<td><?php echo isset($select_result['date18'])?$select_result['date18']:""; ?></td>

				<td><?php echo isset($select_result['date19'])?$select_result['date19']:""; ?></td>

				<td><?php echo isset($select_result['date20'])?$select_result['date20']:""; ?></td>

				<td><?php echo isset($select_result['date21'])?$select_result['date21']:""; ?></td>

				<td><?php echo isset($select_result['date22'])?$select_result['date22']:""; ?></td>

				<td><?php echo isset($select_result['date23'])?$select_result['date23']:""; ?></td>

				<td><?php echo isset($select_result['date24'])?$select_result['date24']:""; ?></td>

				<td><?php echo isset($select_result['date25'])?$select_result['date25']:""; ?></td>

				<td><?php echo isset($select_result['date26'])?$select_result['date26']:""; ?></td>

				<td><?php echo isset($select_result['date27'])?$select_result['date27']:""; ?></td>

			</tr>

			<tr>

				<td>ENDOMETRIAL THICKNESS (cm)</td>

				<td><?php echo isset($select_result['endometrial_thickness1'])?$select_result['endometrial_thickness1']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness2'])?$select_result['endometrial_thickness2']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness3'])?$select_result['endometrial_thickness3']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness4'])?$select_result['endometrial_thickness4']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness5'])?$select_result['endometrial_thickness5']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness6'])?$select_result['endometrial_thickness6']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness7'])?$select_result['endometrial_thickness7']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness8'])?$select_result['endometrial_thickness8']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness9'])?$select_result['endometrial_thickness9']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness10'])?$select_result['endometrial_thickness10']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness11'])?$select_result['endometrial_thickness11']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness12'])?$select_result['endometrial_thickness12']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness13'])?$select_result['endometrial_thickness13']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness14'])?$select_result['endometrial_thickness14']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness15'])?$select_result['endometrial_thickness15']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness16'])?$select_result['endometrial_thickness16']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness17'])?$select_result['endometrial_thickness17']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness18'])?$select_result['endometrial_thickness18']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness19'])?$select_result['endometrial_thickness19']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness20'])?$select_result['endometrial_thickness20']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness21'])?$select_result['endometrial_thickness21']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness22'])?$select_result['endometrial_thickness22']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness23'])?$select_result['endometrial_thickness23']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness24'])?$select_result['endometrial_thickness24']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness25'])?$select_result['endometrial_thickness25']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness26'])?$select_result['endometrial_thickness26']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness27'])?$select_result['endometrial_thickness27']:""; ?></td>

			</tr>

			<?php 
    // Pehle check karein ki kya 1 se 27 tak koi bhi estrogen value aayi hai
    $show_estrogen_row = false;
    for($i = 1; $i <= 27; $i++) {
        if(!empty($select_result["estrogen$i"])) {
            $show_estrogen_row = true;
            break; // Agar ek bhi value mil gayi, toh loop rok dein aur row show karein
        }
    }

    // Agar value mili tabhi ye <tr> print hoga
    if($show_estrogen_row) { 
?>
    <tr>
        <td>ESTROGEN</td>
        <?php 
            // 27 columns ko loop ke through print karna (Code chota karne ke liye)
            for($i = 1; $i <= 27; $i++) { 
        ?>
            <td><?php echo isset($select_result["estrogen$i"]) ? $select_result["estrogen$i"] : ""; ?></td>
        <?php } ?>
    </tr>
<?php } ?>

			<?php 
    // Pehle check karein ki kya 1 se 27 tak koi bhi progesterone value aayi hai
    $show_progesterone_row = false;
    for($i = 1; $i <= 27; $i++) {
        if(!empty($select_result["progesterone$i"])) {
            $show_progesterone_row = true;
            break; // Agar ek bhi value mil gayi, toh loop rok dein aur row show karein
        }
    }

    // Agar value mili tabhi ye <tr> print hoga
    if($show_progesterone_row) { 
?>
    <tr>
        <td>PROGESTERONE</td>
        <?php 
            // 27 columns ko loop ke through print karna (Code chota karne ke liye)
            for($i = 1; $i <= 27; $i++) { 
        ?>
            <td><?php echo isset($select_result["progesterone$i"]) ? $select_result["progesterone$i"] : ""; ?></td>
        <?php } ?>
    </tr>
<?php } ?>

			<?php 
    // Pehle check karein ki kya 1 se 27 tak koi bhi medicine_added value aayi hai
    $show_medicine_added_row = false;
    for($i = 1; $i <= 27; $i++) {
        if(!empty($select_result["medicine_added$i"])) {
            $show_medicine_added_row = true;
            break; // Agar ek bhi value mil gayi, toh loop rok dein aur row show karein
        }
    }

    // Agar value mili tabhi ye <tr> print hoga
    if($show_medicine_added_row) { 
?>
    <tr>
        <td>MEDICINE ADDED</td>
        <?php 
            // 27 columns ko loop ke through print karna
            for($i = 1; $i <= 27; $i++) { 
        ?>
            <td><?php echo isset($select_result["medicine_added$i"]) ? $select_result["medicine_added$i"] : ""; ?></td>
        <?php } ?>
    </tr>
<?php } ?>

			<tr>

				<td>REMARKS</td>

				<td><?php echo isset($select_result['remarks1'])?$select_result['remarks1']:""; ?></td>

				<td><?php echo isset($select_result['remarks2'])?$select_result['remarks2']:""; ?></td>

				<td><?php echo isset($select_result['remarks3'])?$select_result['remarks3']:""; ?></td>

				<td><?php echo isset($select_result['remarks4'])?$select_result['remarks4']:""; ?></td>

				<td><?php echo isset($select_result['remarks5'])?$select_result['remarks5']:""; ?></td>

				<td><?php echo isset($select_result['remarks6'])?$select_result['remarks6']:""; ?></td>

				<td><?php echo isset($select_result['remarks7'])?$select_result['remarks7']:""; ?></td>

				<td><?php echo isset($select_result['remarks8'])?$select_result['remarks8']:""; ?></td>

				<td><?php echo isset($select_result['remarks9'])?$select_result['remarks9']:""; ?></td>

				<td><?php echo isset($select_result['remarks10'])?$select_result['remarks10']:""; ?></td>

				<td><?php echo isset($select_result['remarks11'])?$select_result['remarks11']:""; ?></td>

				<td><?php echo isset($select_result['remarks12'])?$select_result['remarks12']:""; ?></td>

				<td><?php echo isset($select_result['remarks13'])?$select_result['remarks13']:""; ?></td>

				<td><?php echo isset($select_result['remarks14'])?$select_result['remarks14']:""; ?></td>

				<td><?php echo isset($select_result['remarks15'])?$select_result['remarks15']:""; ?></td>

				<td><?php echo isset($select_result['remarks16'])?$select_result['remarks16']:""; ?></td>

				<td><?php echo isset($select_result['remarks17'])?$select_result['remarks17']:""; ?></td>

				<td><?php echo isset($select_result['remarks18'])?$select_result['remarks18']:""; ?></td>

				<td><?php echo isset($select_result['remarks19'])?$select_result['remarks19']:""; ?></td>

				<td><?php echo isset($select_result['remarks20'])?$select_result['remarks20']:""; ?></td>

				<td><?php echo isset($select_result['remarks21'])?$select_result['remarks21']:""; ?></td>

				<td><?php echo isset($select_result['remarks22'])?$select_result['remarks22']:""; ?></td>

				<td><?php echo isset($select_result['remarks23'])?$select_result['remarks23']:""; ?></td>

				<td><?php echo isset($select_result['remarks24'])?$select_result['remarks24']:""; ?></td>

				<td><?php echo isset($select_result['remarks25'])?$select_result['remarks25']:""; ?></td>

				<td><?php echo isset($select_result['remarks26'])?$select_result['remarks26']:""; ?></td>

				<td><?php echo isset($select_result['remarks27'])?$select_result['remarks27']:""; ?></td>

			</tr>

			<tr>

				<td>FOLLOWUP ON</td>

				<td><?php echo isset($select_result['followup_on1'])?$select_result['followup_on1']:""; ?></td>

				<td><?php echo isset($select_result['followup_on2'])?$select_result['followup_on2']:""; ?></td>

				<td><?php echo isset($select_result['followup_on3'])?$select_result['followup_on3']:""; ?></td>

				<td><?php echo isset($select_result['followup_on4'])?$select_result['followup_on4']:""; ?></td>

				<td><?php echo isset($select_result['followup_on5'])?$select_result['followup_on5']:""; ?></td>

				<td><?php echo isset($select_result['followup_on6'])?$select_result['followup_on6']:""; ?></td>

				<td><?php echo isset($select_result['followup_on7'])?$select_result['followup_on7']:""; ?></td>

				<td><?php echo isset($select_result['followup_on8'])?$select_result['followup_on8']:""; ?></td>

				<td><?php echo isset($select_result['followup_on9'])?$select_result['followup_on9']:""; ?></td>

				<td><?php echo isset($select_result['followup_on10'])?$select_result['followup_on10']:""; ?></td>

				<td><?php echo isset($select_result['followup_on11'])?$select_result['followup_on11']:""; ?></td>

				<td><?php echo isset($select_result['followup_on12'])?$select_result['followup_on12']:""; ?></td>

				<td><?php echo isset($select_result['followup_on13'])?$select_result['followup_on13']:""; ?></td>

				<td><?php echo isset($select_result['followup_on14'])?$select_result['followup_on14']:""; ?></td>

				<td><?php echo isset($select_result['followup_on15'])?$select_result['followup_on15']:""; ?></td>

				<td><?php echo isset($select_result['followup_on16'])?$select_result['followup_on16']:""; ?></td>

				<td><?php echo isset($select_result['followup_on17'])?$select_result['followup_on17']:""; ?></td>

				<td><?php echo isset($select_result['followup_on18'])?$select_result['followup_on18']:""; ?></td>

				<td><?php echo isset($select_result['followup_on19'])?$select_result['followup_on19']:""; ?></td>

				<td><?php echo isset($select_result['followup_on20'])?$select_result['followup_on20']:""; ?></td>

				<td><?php echo isset($select_result['followup_on21'])?$select_result['followup_on21']:""; ?></td>

				<td><?php echo isset($select_result['followup_on22'])?$select_result['followup_on22']:""; ?></td>

				<td><?php echo isset($select_result['followup_on23'])?$select_result['followup_on23']:""; ?></td>

				<td><?php echo isset($select_result['followup_on24'])?$select_result['followup_on24']:""; ?></td>

				<td><?php echo isset($select_result['followup_on25'])?$select_result['followup_on25']:""; ?></td>

				<td><?php echo isset($select_result['followup_on26'])?$select_result['followup_on26']:""; ?></td>

				<td><?php echo isset($select_result['followup_on27'])?$select_result['followup_on27']:""; ?></td>

			</tr>

			<tr>

				<td>SERUM ESTRADIOL (E2) LEVEL</td>

				<td><?php echo isset($select_result['serum_e2_level1'])?$select_result['serum_e2_level1']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level2'])?$select_result['serum_e2_level2']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level3'])?$select_result['serum_e2_level3']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level4'])?$select_result['serum_e2_level4']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level5'])?$select_result['serum_e2_level5']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level6'])?$select_result['serum_e2_level6']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level7'])?$select_result['serum_e2_level7']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level8'])?$select_result['serum_e2_level8']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level9'])?$select_result['serum_e2_level9']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level10'])?$select_result['serum_e2_level10']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level11'])?$select_result['serum_e2_level11']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level12'])?$select_result['serum_e2_level12']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level13'])?$select_result['serum_e2_level13']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level14'])?$select_result['serum_e2_level14']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level15'])?$select_result['serum_e2_level15']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level16'])?$select_result['serum_e2_level16']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level17'])?$select_result['serum_e2_level17']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level18'])?$select_result['serum_e2_level18']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level19'])?$select_result['serum_e2_level19']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level20'])?$select_result['serum_e2_level20']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level21'])?$select_result['serum_e2_level21']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level22'])?$select_result['serum_e2_level22']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level23'])?$select_result['serum_e2_level23']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level24'])?$select_result['serum_e2_level24']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level25'])?$select_result['serum_e2_level25']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level26'])?$select_result['serum_e2_level26']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level27'])?$select_result['serum_e2_level27']:""; ?></td>

			</tr>

			<tr>

				<td>SERUM PROGESTERONE LEVEL</td>

				<td><?php echo isset($select_result['serum_progesterone_level1'])?$select_result['serum_progesterone_level1']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level2'])?$select_result['serum_progesterone_level2']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level3'])?$select_result['serum_progesterone_level3']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level4'])?$select_result['serum_progesterone_level4']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level5'])?$select_result['serum_progesterone_level5']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level6'])?$select_result['serum_progesterone_level6']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level7'])?$select_result['serum_progesterone_level7']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level8'])?$select_result['serum_progesterone_level8']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level9'])?$select_result['serum_progesterone_level9']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level10'])?$select_result['serum_progesterone_level10']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level11'])?$select_result['serum_progesterone_level11']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level12'])?$select_result['serum_progesterone_level12']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level13'])?$select_result['serum_progesterone_level13']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level14'])?$select_result['serum_progesterone_level14']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level15'])?$select_result['serum_progesterone_level15']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level16'])?$select_result['serum_progesterone_level16']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level17'])?$select_result['serum_progesterone_level17']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level18'])?$select_result['serum_progesterone_level18']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level19'])?$select_result['serum_progesterone_level19']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level20'])?$select_result['serum_progesterone_level20']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level21'])?$select_result['serum_progesterone_level21']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level22'])?$select_result['serum_progesterone_level22']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level23'])?$select_result['serum_progesterone_level23']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level24'])?$select_result['serum_progesterone_level24']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level25'])?$select_result['serum_progesterone_level25']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level26'])?$select_result['serum_progesterone_level26']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level27'])?$select_result['serum_progesterone_level27']:""; ?></td>

			</tr>

			<?php 
    // Pehle check karein ki kya 1 se 27 tak koi bhi other_investigation value aayi hai
    $show_other_investigation_row = false;
    for($i = 1; $i <= 27; $i++) {
        if(!empty($select_result["other_investigation$i"])) {
            $show_other_investigation_row = true;
            break; // Agar ek bhi value mil gayi, toh loop rok dein aur row show karein
        }
    }

    // Agar value mili tabhi ye <tr> print hoga
    if($show_other_investigation_row) { 
?>
    <tr>
        <td>OTHER INVESTIGATION</td>
        <?php 
            // 27 columns ko loop ke through print karna
            for($i = 1; $i <= 27; $i++) { 
        ?>
            <td><?php echo isset($select_result["other_investigation$i"]) ? $select_result["other_investigation$i"] : ""; ?></td>
        <?php } ?>
    </tr>
<?php } ?>

</table>

<table width="100%">
<tbody>
<tr>
<td colspan="8" style="border:1px solid;padding:5px;" ><h4>ADVICE ON MEDICINE</h4> </td>
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
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabCrocin',$applicablemedicine)){ ?>
<tr>
<td width="100" colspan="1" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabCrocin" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabCrocin',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Crocin</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>500 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>SOS
<strong>Maximum three times at interval of 6 hrs (if Require )</strong></p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>SOS (if pain)</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p></p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Sypcremaffin',$applicablemedicine)){ ?>
<tr>
<td width="100" colspan="1" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Sypcremaffin"  <?php if(!empty($select_result['applicablemedicine']) && in_array('Sypcremaffin',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Sypcremaffin</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>ONE TSF</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>SOS</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After dinner</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>SOS (if constipation)</p>
</td>
<td width="100" style="border:1px solid;padding:5px;"></td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('EndofertTab2MG',$applicablemedicine)){ ?>
<tr>
<td  width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="EndofertTab2MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('EndofertTab2MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Endofert Tab 2MG</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1TAB</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>	<input type="checkbox" name="applicablemedicine[]" value="gufitwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufitwice',$applicablemedicine)){echo "checked";}?>>
	Twice 
	<input type="checkbox" name="applicablemedicine[]" value="gufithrice" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufithrice',$applicablemedicine)){echo "checked";}?>>
	thrice 
	<input type="checkbox" name="applicablemedicine[]" value="gufifour" <?php if(!empty($select_result['applicablemedicine']) && in_array('gufifour',$applicablemedicine)){echo "checked";}?>>
	four times daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>20 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabEcosprin75mg',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabEcosprin75mg"  <?php if(!empty($select_result['applicablemedicine']) && in_array('TabEcosprin75mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Ecosprin 75 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1TAB</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p><input type="checkbox" name="applicablemedicine[]" value="eco75once" <?php if(!empty($select_result['applicablemedicine']) && in_array('eco75once',$applicablemedicine)){echo "checked";}?>>
	once
	<input type="checkbox" name="applicablemedicine[]" value="eco75twice" <?php if(!empty($select_result['applicablemedicine']) && in_array('eco75twice',$applicablemedicine)){echo "checked";}?>>
	twice</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tomorrow</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>20 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Tabvigor50mg',$applicablemedicine)){ ?>
<tr>
<td style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Tabvigor50mg"  <?php if(!empty($select_result['applicablemedicine']) && in_array('Tabvigor50mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="117" style="border:1px solid;padding:5px;">
<p>Tab Vigor 50 mg</p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>50 MG</p>
</td>
<td width="76" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="83" style="border:1px solid;padding:5px;">
<p>once</p>
</td>
<td width="68" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="71" style="border:1px solid;padding:5px;">
<p>HS</p>
</td>
<td width="57" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone5mg',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone5mg" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone5mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Wysolone 5mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>5mg for --- days followed by</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tomorrow</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>----------</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Wysolone 10mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>10mg for---days followed by</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tomorrow</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>----------</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone15mg',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabWysolone15mg" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabWysolone15mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Wysolone 15mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>15mg for---days</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tomorrow</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>----------</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilL',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilL" <?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilL',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Biophil L</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 CAP</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilO',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilO" <?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilO',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Biophil O</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 CAP</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilQ3',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BiophilQ3" <?php if(!empty($select_result['applicablemedicine']) && in_array('BiophilQ3',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Biophil Q3</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 CAP</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('BIOLARG',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BIOLARG" <?php if(!empty($select_result['applicablemedicine']) && in_array('BIOLARG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>BIOLARG</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 SACHET</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('BIOPHILVITA',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="BIOPHILVITA" <?php if(!empty($select_result['applicablemedicine']) && in_array('BIOPHILVITA',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>BIOPHIL VITA</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 cap</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>30 days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('IPARIN40MG',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="IPARIN40MG" <?php if(!empty($select_result['applicablemedicine']) && in_array('IPARIN40MG',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>IPARIN 40 MG</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>40 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>subcutaneous</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p><input type="checkbox" name="applicablemedicine[]" value="oxyOncedaily" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxyOncedaily',$applicablemedicine)){echo "checked";}?>>
	Once daily
	<input type="checkbox" name="applicablemedicine[]" value="oxyalternate" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxyalternate',$applicablemedicine)){echo "checked";}?>>
	alternate
	<input type="checkbox" name="applicablemedicine[]" value="oxybiweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxybiweekly',$applicablemedicine)){echo "checked";}?>>
	biweekly
	<input type="checkbox" name="applicablemedicine[]" value="oxyweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('oxyweekly',$applicablemedicine)){echo "checked";}?>>
	weekly
	</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>20 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Injcoriosurge10000',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Injcoriosurge10000" <?php if(!empty($select_result['applicablemedicine']) && in_array('Injcoriosurge10000',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Inj coriosurge 10000</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>100 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>subcutaneous</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p><input type="checkbox" name="applicablemedicine[]" value="corOncedaily" <?php if(!empty($select_result['applicablemedicine']) && in_array('corOncedaily',$applicablemedicine)){echo "checked";}?>>
	Once daily
	<input type="checkbox" name="applicablemedicine[]" value="coralternate" <?php if(!empty($select_result['applicablemedicine']) && in_array('coralternate',$applicablemedicine)){echo "checked";}?>>
	alternate
	<input type="checkbox" name="applicablemedicine[]" value="corbiweekly" <?php if(!empty($select_result['applicablemedicine']) && in_array('corbiweekly',$applicablemedicine)){echo "checked";}?>>
	biweekly </p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabAllegra',$applicablemedicine)){  ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabAllegra" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabAllegra',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Allegra</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 TAB</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabMontairLC',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabMontairLC" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabMontairLC',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Montair LC</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1TAB</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('TabShelcal500mg',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="TabShelcal500mg" <?php if(!empty($select_result['applicablemedicine']) && in_array('TabShelcal500mg',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Tab Shelcal 500 mg</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1TAB</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Once daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Estogel',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Estogel" <?php if(!empty($select_result['applicablemedicine']) && in_array('Estogel',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Estogel</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>2.5 gm</p>  
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Locally</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>
	<input type="checkbox" name="applicablemedicine[]" value="estoonce" <?php if(!empty($select_result['applicablemedicine']) && in_array('estoonce',$applicablemedicine)){echo "checked";}?>>
	Once
	<input type="checkbox" name="applicablemedicine[]" value="estotwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('estotwice',$applicablemedicine)){echo "checked";}?>>
	twice 
	<input type="checkbox" name="applicablemedicine[]" value="estothrice" <?php if(!empty($select_result['applicablemedicine']) && in_array('estothrice',$applicablemedicine)){echo "checked";}?>>
	thrice 
	<input type="checkbox" name="applicablemedicine[]" value="estofour" <?php if(!empty($select_result['applicablemedicine']) && in_array('estofour',$applicablemedicine)){echo "checked";}?>>
	four  times to be applied locally daily</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('Lenzettospray',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="Lenzettospray" <?php if(!empty($select_result['applicablemedicine']) && in_array('Lenzettospray',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Lenzetto Spray</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>1 spray</p>    
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Locally</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>
	<input type="checkbox" name="applicablemedicine[]" value="lenonce" <?php if(!empty($select_result['applicablemedicine']) && in_array('lenonce',$applicablemedicine)){echo "checked";}?>>
	Once
	<input type="checkbox" name="applicablemedicine[]" value="lentwice" <?php if(!empty($select_result['applicablemedicine']) && in_array('lentwice',$applicablemedicine)){echo "checked";}?>>
	twice 
	times to be applied</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p></p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<?php if(!empty($select_result['applicablemedicine']) && in_array('CapCalcitasD3',$applicablemedicine)){ ?>
<tr>
<td width="100" style="border:1px solid;padding:5px;">
 <input type="checkbox" class="checkmedicine" name="applicablemedicine[]" value="CapCalcitasD3" <?php if(!empty($select_result['applicablemedicine']) && in_array('CapCalcitasD3',$applicablemedicine)){echo "checked";}?>>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>Cap Calcitas D3</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>60000IU</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>oral</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>weekly</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>After meals</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>immediately</p>
</td>
<td width="100" style="border:1px solid;padding:5px;">
<p>16 Days</p>
</td>
</tr>
<?php } ?>
<tr><td colspan="8" style="border:1px solid;padding:5px;">There are No Substitutes</td></tr>
<?php if(!empty($select_result['Other_Medication1'])) { ?>
<tr>
    <td colspan="8" style="border:1px solid;padding:5px;">
        <label for="other">Other Medication1:</label>
        <?php echo $select_result['Other_Medication1']; ?>
    </td>
</tr>
<?php } ?>

<?php if(!empty($select_result['Other_Medication2'])) { ?>
<tr>
    <td colspan="8" style="border:1px solid;padding:5px;">
        <label for="other">Other Medication2:</label>
        <?php echo $select_result['Other_Medication2']; ?>
    </td>
</tr>
<?php } ?>

<tr>
<td colspan="8" style="border:1px solid;padding:5px;">
<label for="other">Please take prescribed medicines / injections only. Dont skip/ stop any medicine on your own unless advised by the doctor.</label>
</td>
</tr>
</tbody>
</table>


<table style="width:100%; border:1px solid #cdcdcd;" id="" border="1">

			<tr>

				<td>DOCTOR <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?></td>

				<td>COUNSELLOR <?php echo isset($select_result['counsellor'])?$select_result['counsellor']:""; ?></td>

				<td>NURSE <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?></td>

			</tr>

		</table>

</div>	

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
<style>
	[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
		position: unset;
		left: -9999px;
		opacity: 1;
	}
</style>

<style type="text/css">
    form { margin: 20px 0; }
    form input, button { padding: 5px; }
    table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
    table, th, td { border: 1px solid #cdcdcd; }
    table th, table td { padding: 10px; text-align: left; }
    .heading { margin-bottom:10px; margin-top: 0; padding-top:0px; }
    
    /* 1. Core Wrapper Setup */
    .btn-group, .multiselect {
        width: 100% !important;
        text-align: left !important;
        position: relative !important;
    }
    
    /* 2. Dropdown List Toggle Force (Click Engine Fix) */
    .btn-group.open .multiselect-container,
    .btn-group.show .multiselect-container {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* 3. Container Window Fix (Taaki list layers ke peeche na chhupe) */
    .multiselect-container {
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        z-index: 999999 !important; /* Highest Layer Priority */
        float: left !important;
        min-width: 100% !important;
        max-width: 100% !important;
        padding: 5px 0 !important;
        margin: 2px 0 0 !important;
        background-color: #ffffff !important;
        border: 1px solid #ccc !important;
        border: 1px solid rgba(0,0,0,.15) !important;
        border-radius: 4px !important;
        box-shadow: 0 6px 12px rgba(0,0,0,.175) !important;
        max-height: 300px !important;
        overflow-y: auto !important;
    }
    
    /* 4. List Items & Interaction Padding */
    .multiselect-container > li {
        width: 100% !important;
        clear: both !important;
        display: block !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .multiselect-container > li > a {
        padding: 6px 15px !important;
        display: block !important;
        color: #333333 !important;
        text-decoration: none !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .multiselect-container > li > a:hover,
    .multiselect-container > li.active > a {
        background-color: #f5f5f5 !important;
        color: #262626 !important;
    }
    
    .multiselect-container > li > a > label {
        margin: 0 !important;
        padding: 0 !important;
        cursor: pointer !important;
        display: block !important;
        width: 100% !important;
        font-weight: normal !important;
    }

    /* 5. Checkbox Rendering Fix */
    .multiselect-container input[type="checkbox"] {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        margin: 2px 8px 0 0 !important;
        float: left !important;
        width: 16px !important;
        height: 16px !important;
        -webkit-appearance: checkbox !important;
        -moz-appearance: checkbox !important;
        appearance: checkbox !important;
    }
    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: initial;
    left: 0px;
    opacity: 1;
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
}

// Controller function to toggle disabled state smoothly
function controlInvestigationDropdowns(shouldEnable) {
   var $femaleSelect = $("select#female_minvestigation_suggestion_list");
   var $maleSelect = $("select#male_minvestigation_suggestion_list");

   if (shouldEnable) {
       $femaleSelect.prop('disabled', false);
       $maleSelect.prop('disabled', false);
       if (jQuery.isFunction(jQuery.fn.multiselect)) {
           $femaleSelect.multiselect('enable');
           $maleSelect.multiselect('enable');
       }
   } else {
       $('option', $femaleSelect).prop('selected', false);
       $('option', $maleSelect).prop('selected', false);
       $femaleSelect.prop('disabled', true);
       $maleSelect.prop('disabled', true);
       if (jQuery.isFunction(jQuery.fn.multiselect)) {
           $femaleSelect.multiselect('deselectAll', false);
           $femaleSelect.multiselect('disable');
           $maleSelect.multiselect('deselectAll', false);
           $maleSelect.multiselect('disable');
       }
   }
}

$(document).ready(function() {
   // Initialize Bootstrap Multiselect with Custom Layout Templates
   if (jQuery.isFunction(jQuery.fn.multiselect)) {
       $('.multidselect_dropdown_1').multiselect({
           includeSelectAllOption: true,
           enableFiltering: true,
           buttonWidth: '100%',
           nonSelectedText: 'NONE SELECTED',
           templates: {
               li: '<li><a href="javascript:void(0);"><label><input type="checkbox" /> </label></a></li>'
           }
       });
   }

   // Handle click toggle issues caused by custom Admin/Dashboard UI templates
   $(document).on('click', '.btn-group .multiselect', function(e) {
       e.preventDefault();
       var $parent = $(this).parent('.btn-group');
       
       if (!$parent.hasClass('disabled') && !$(this).hasClass('disabled')) {
           // Close all other open multiselects first
           $('.btn-group').not($parent).removeClass('open show');
           // Toggle current target selection block
           $parent.toggleClass('open show');
       }
       e.stopPropagation();
   });

   // Close dropdown when clicking anywhere outside on screen layout
   $(document).on('click', function(e) {
       if (!$(e.target).closest('.btn-group').length) {
           $('.btn-group').removeClass('open show');
       }
   });

   // Read initial state on page load refresh window frame
   var checkboxState = $("#investation_suggestion").is(':checked');
   controlInvestigationDropdowns(checkboxState);
});

// Sync real-time selection visibility changes
$("#investation_suggestion").change(function() {
   controlInvestigationDropdowns(this.checked);
});
</script>