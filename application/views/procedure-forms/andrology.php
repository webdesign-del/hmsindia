<?php
$all_method =& get_instance();
$appoitmented_date = isset($_GET['appoitmented_date']) ? $_GET['appoitmented_date'] : '';

// 🎯 AUTOMATIC RECEIPT NUMBER & PATIENT ID CATCHER
if (!isset($receipt_number) || empty($receipt_number)) {
    $receipt_number = isset($_GET['receipt_number']) ? $_GET['receipt_number'] : (isset($_GET['receipt']) ? $_GET['receipt'] : $this->uri->segment(4));
}
if (!isset($patient_id) || empty($patient_id)) {
    $patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : $this->uri->segment(3);
}

    // php code to Insert/Update data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);

        if(!empty($_FILES['upload']['tmp_name'])){
          $dest_path = $this->config->item('upload_path');
          $destination = $dest_path.'procedure-forms-uploads/';
          $NewImageName = rand(4,10000)."-".$_FILES['upload']['name'];
          $transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
          
          move_uploaded_file($_FILES['upload']['tmp_name'], $destination.$NewImageName);
          $_POST['upload'] = $transaction_img;
        }

        $select_query = "SELECT * FROM `andrology` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result_check = run_select_query($select_query); 

        $sqlArr = array(); 

        if(empty($select_result_check)){
            $query = "INSERT INTO `andrology` SET ";
            foreach( $_POST as $key=> $value ) {
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }   
            $query .= implode(',' , $sqlArr);
        } else {
            $query = "UPDATE `andrology` SET ";
            foreach( $_POST as $key=> $value ) {
              $sqlArr[] = " `$key` = '".addslashes($value)."'"; 
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

    // FORM LOAD LOGIC: Single array row fetch verification
    $select_query = "SELECT * FROM `andrology` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $db_res = run_select_query($select_query); 
    $select_result = isset($db_res[0]) ? $db_res[0] : (isset($db_res['patient_id']) ? $db_res : array());

    $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $db_res3 = run_select_query($sql3);  
    $select_result3 = isset($db_res3[0]) ? $db_res3[0] : (isset($db_res3['wife_name']) ? $db_res3 : array()); 
  
    $sql1 = "SELECT * FROM ".$this->config->item('db_prefix')."appointments WHERE paitent_id='".$patient_id."' AND paitent_type='new_patient'";
    $db_res1 = run_select_query($sql1);
    $select_result1 = isset($db_res1[0]) ? $db_res1[0] : (isset($db_res1['uhid']) ? $db_res1 : array());
  
    $appointment_for = isset($select_result1['appoitment_for']) ? $select_result1['appoitment_for'] : '';
    
    $sql5 = "SELECT * FROM ".$this->config->item('db_prefix')."centers WHERE center_number='".$appointment_for."'";
    $db_res5 = run_select_query($sql5);  
    $select_result5 = isset($db_res5[0]) ? $db_res5[0] : (isset($db_res5['center_code']) ? $db_res5 : array());  
?>

<div class="no-print-section">
<form enctype='multipart/form-data' class="searchform" name="form" action="" method="POST">
  <input type="hidden" value="<?php echo isset($updated_by)?$updated_by:''; ?>" class="form" name="updated_by">
  <input type="hidden" value="<?php echo isset($updated_type)?$updated_type:''; ?>" class="form" name="updated_type">
  <input type="hidden" value="<?php echo isset($updated_at)?$updated_at:''; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo isset($procedure_id)?$procedure_id:''; ?>" class="form" name="procedure_id">  
  <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
  <input type="hidden" value="pending" name="status"> 

    <div class='table-responsive'>
        <table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
           <tr>
               <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
               <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">ANDROLOGY</h3></td>
           </tr>
        </table>
        
        <table width="100%" class="vb45rt">
            <tbody>
                <tr style="background: #b3b9b7;">
                    <td colspan="2" width="33%" style="border:1px solid;padding:5px;">
                        <strong>UHID : <?php echo (isset($select_result5['center_code'])?$select_result5['center_code']:'')."/".(isset($select_result1['uhid'])?$select_result1['uhid']:''); ?></strong>
                    </td>
                    <td colspan="2" width="33%" style="border:1px solid;padding:5px;">
                        <strong>Patient Name : <?php echo isset($select_result3['wife_name'])?$select_result3['wife_name']:''; ?> </strong>
                    </td>
                    <td colspan="2" width="33%" style="border:1px solid;padding:5px;">
                        <strong>IIC ID: <?php echo $patient_id; ?></strong>
                    </td>
                </tr>
            </tbody>
         </table>

      <table class="table table-bordered table-hover mt-2 table-sm red-field">
          <thead>
                <tr>
                  <td colspan="4">
                      <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by'])){ ?>
                          <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
                      <?php } ?>
                  </td>
                </tr>
          </thead>
      </table>

      <table class="table table-bordered table-hover mt-2 table-sm red-field">
         <thead>
            <tr><th class="text-aloign-center">SEMEN EXAMINATION</th></tr>
         </thead>
      </table>

      <table class="table table-bordered table-hover mt-2 table-sm red-field">
          <tbody>
                <tr>
                    <td>Physical Examination</td>
                    <td>VALUES</td>
                    <td>UNIT</td>
                    <td>REF RANGE</td>
                </tr>
                <tr>
                    <td>Time of specimen</td>
                    <td><input type="time" class="form" value="<?php echo isset($select_result['time_of_specimen'])?$select_result['time_of_specimen']:""; ?>" name="time_of_specimen"></td>
                    <td>AM/PM</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Time of Examination</td>
                    <td><input type="time" class="form" value="<?php echo isset($select_result['time_of_examination'])?$select_result['time_of_examination']:""; ?>" name="time_of_examination"></td>
                    <td>AM/PM</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Duration of abstinence</td>
                    <td><input type="number" min="0" class="form" value="<?php echo isset($select_result['doa'])?$select_result['doa']:""; ?>" name="doa"></td>
                    <td>Days</td>
                    <td>2-7 </td>
                </tr>
                <tr>
                    <td>Liquefaction at 37 c </td>
                    <td><input type="number" min="0" class="form" value="<?php echo isset($select_result['liquefaction'])?$select_result['liquefaction']:""; ?>" name="liquefaction"></td>
                    <td>Minutes</td>
                    <td>30-60</td>
                </tr>
                <tr>
                    <td>Volume</td>
                    <td><input type="text" class="form" pattern="[-+]?[0-9]*[.,]?[0-9]+" value="<?php echo isset($select_result['volume'])?$select_result['volume']:""; ?>" name="volume"></td>
                    <td>ML</td>
                    <td>>1.5</td>
                </tr>
                <tr>
                    <td>Appearance</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['appearance1'])?$select_result['appearance1']:""; ?>" name="appearance1"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Colour</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['color_1'])?$select_result['color_1']:""; ?>" name="color_1"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Viscosity</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['viscosity_1'])?$select_result['viscosity_1']:""; ?>" name="viscosity_1"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Ph</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['uph_1'])?$select_result['uph_1']:""; ?>" name="uph_1"></td>
                    <td></td>
                    <td>7.2-7.8</td>
                </tr>
                <tr>
                    <td><strong>Microscopic Examination</strong></td>
                    <td></td><td></td><td></td>
                </tr>
                <tr>
                    <td>Total sperm concentration</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['tsc'])?$select_result['tsc']:""; ?>" name="tsc"></td>
                    <td>Million/mL</td>
                    <td>>15</td>
                </tr>
                <tr>
                    <td>Percentage Motility</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['percentage_motility'])?$select_result['percentage_motility']:""; ?>" name="percentage_motility"></td>
                    <td>%</td>
                    <td>>40(Grade A+B)</td>
                </tr>
                <tr>
                    <td>Grade A(progressive motile)</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['grade_a'])?$select_result['grade_a']:""; ?>" name="grade_a"></td>
                    <td>%</td>
                    <td>>32(lower reference limit)</td>
                </tr>
                <tr>
                    <td>Grade B (Non progressive motile)</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['grade_b'])?$select_result['grade_b']:""; ?>" name="grade_b"></td>
                    <td>%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Grade C (Immotile)</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['grade_c'])?$select_result['grade_c']:""; ?>" name="grade_c"></td>
                    <td>%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Grade D (Immotile)</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['grade_d'])?$select_result['grade_d']:""; ?>" name="grade_d"></td>
                    <td>%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Agglutination</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['agglutination'])?$select_result['agglutination']:""; ?>" name="agglutination"></td>
                    <td></td>
                    <td>Negative</td>
                </tr>
                <tr>
                    <td>Pus cells</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['plus_cells1'])?$select_result['plus_cells1']:""; ?>" name="plus_cells1"></td>
                    <td>/hpf</td>
                    <td>Nil</td>
                </tr>
                <tr>
                    <td>Red blood cells</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['red_blood'])?$select_result['red_blood']:""; ?>" name="red_blood"></td>
                    <td>/hpf</td>
                    <td>Nil</td>
                </tr>
                <tr>
                    <td>Epithelial cells</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['epithelial_cell'])?$select_result['epithelial_cell']:""; ?>" name="epithelial_cell"></td>
                    <td>/hpf</td>
                    <td>Nil</td>
                </tr>
                <tr>
                    <td><strong>Morphology</strong></td>
                    <td></td><td></td><td></td>
                </tr>
                <tr>
                    <td>Normal Morphology</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['normal_morphology'])?$select_result['normal_morphology']:""; ?>" name="normal_morphology"></td>
                    <td>%</td>
                    <td>>4</td>
                </tr>
                <tr>
                    <td>Abnormal Morphology</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['abnormal_morphology1'])?$select_result['abnormal_morphology1']:""; ?>" name="abnormal_morphology1"></td>
                    <td>%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>(a)Head Defects</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['head_defects'])?$select_result['head_defects']:""; ?>" name="head_defects"></td>
                    <td>%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Neck&Midpiece</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['neck_midpiece'])?$select_result['neck_midpiece']:""; ?>" name="neck_midpiece"></td>
                    <td>%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>(a)Tail Defects</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['head_defects2'])?$select_result['head_defects2']:""; ?>" name="head_defects2"></td>
                    <td>%</td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>Chemical Examination</strong></td>
                    <td></td><td></td><td></td>
                </tr>
                <tr>
                    <td>Semen Fructose Qualitative</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['semen_fructose_qualitative_1'])?$select_result['semen_fructose_qualitative_1']:""; ?>" name="semen_fructose_qualitative_1"></td>
                    <td></td>
                    <td>Positive</td>
                </tr>
                <tr>
                    <td><strong>Special Test</strong></td>
                    <td></td><td></td><td></td>
                </tr>
                <tr>
                    <td>Sperm Vitality</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['sperm_vitality'])?$select_result['sperm_vitality']:""; ?>" name="sperm_vitality"></td>
                    <td>%</td>
                    <td>>58</td>
                </tr>
                <tr>
                    <td>Hypo osmotic swelling test</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['host'])?$select_result['host']:""; ?>" name="host"></td>
                    <td>%</td>
                    <td>>58</td>
                </tr>
                <tr>
                    <td>DFI(DNA Fragmentation Index)</td>
                    <td><input type="text" maxlength="20" class="form" value="<?php echo isset($select_result['dfi'])?$select_result['dfi']:""; ?>" name="dfi"></td>
                    <td>%</td>
                    <td><15</td>
                </tr>
                <tr>
                    <td>Upload</td>
                    <td>
                      <input type="file" id="file" name="upload" multiple />
                      <a target="_blank" href="<?php echo !empty($select_result['upload'])?$select_result['upload']:"javascript:void(0)"; ?>">Download</a>
                     </td>
                    <td></td><td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <table class="table table-bordered table-hover mt-2 table-sm red-field">
        <thead>
            <tr>
                <th>Not for Medico legal purpose. Results to be correlated with clinical findings for a final diagnosis Parameters are according to latest WHO Laboratory Manual for Examination & Processing of Human Semen FIFTH EDITION</th>
            </tr>
            <tr><th>EMBRYOLOGIST</th></tr>
        </thead>
    </table>
    <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
</form>
<input type="button" id="btn" value="Print Summary" class="btn btn-primary pull-right printbtn" onclick="window.print();">
</div>


<div id="master_print_wrapper">  
  <table style="width:100%; border:1px solid #000; padding:5px;">
       <tr>
           <td style="width:50%; padding:5px;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
           <td style="width:50%; padding:5px; text-align:right;"><h3>ANDROLOGY REPORT</h3></td>
       </tr>
  </table>
  
  <table width="100%" style="margin-top:10px; border-collapse: collapse;" border="1">
    <tbody>
        <tr style="background: #b3b9b7 !important; -webkit-print-color-adjust: exact;">
            <td style="padding:6px;"><strong>UHID : <?php echo (isset($select_result5['center_code'])?$select_result5['center_code']:'')."/".(isset($select_result1['uhid'])?$select_result1['uhid']:''); ?></strong></td>
            <td style="padding:6px;"><strong>Patient Name : <?php echo isset($select_result3['wife_name'])?$select_result3['wife_name']:''; ?> </strong></td>
            <td style="padding:6px;"><strong>IIC ID: <?php echo $patient_id; ?></strong></td>
        </tr>
     </tbody>
  </table>

  <table style="width:100%; border:1px solid #000; margin-top:15px; border-collapse: collapse;" border="1">
     <thead>
        <tr style="background:#f2f2f2;"><th colspan="4" style="text-align:center; padding:6px;">SEMEN EXAMINATION REPORT</th></tr>
        <tr style="background:#f9f9f9; font-weight:bold;">
            <td style="padding:6px;">Test Description</td>
            <td style="padding:6px;">Observed Value</td>
            <td style="padding:6px;">Unit</td>
            <td style="padding:6px;">Reference Range</td>
        </tr>
     </thead> 
     <tbody>
            <tr><td colspan="4" style="font-weight:bold; background:#eee;">Physical Examination</td></tr>
            <tr><td>Time of specimen</td><td><?php echo isset($select_result['time_of_specimen'])?$select_result['time_of_specimen']:"--"; ?></td><td>AM/PM</td><td></td></tr>
            <tr><td>Time of Examination</td><td><?php echo isset($select_result['time_of_examination'])?$select_result['time_of_examination']:"--"; ?></td><td>AM/PM</td><td></td></tr>
            <tr><td>Duration of abstinence</td><td><?php echo isset($select_result['doa'])?$select_result['doa']:"--"; ?></td><td>Days</td><td>2-7 </td></tr>
            <tr><td>Liquefaction at 37 c</td><td><?php echo isset($select_result['liquefaction'])?$select_result['liquefaction']:"--"; ?></td><td>Minutes</td><td>30-60</td></tr>
            <tr><td>Volume</td><td><?php echo isset($select_result['volume'])?$select_result['volume']:"--"; ?></td><td>ML</td><td>>1.5</td></tr>
            <tr><td>Appearance</td><td><?php echo isset($select_result['appearance1'])?$select_result['appearance1']:"--"; ?></td><td></td><td></td></tr>
            <tr><td>Colour</td><td><?php echo isset($select_result['color_1'])?$select_result['color_1']:"--"; ?></td><td></td><td></td></tr>
            <tr><td>Viscosity</td><td><?php echo isset($select_result['viscosity_1'])?$select_result['viscosity_1']:"--"; ?></td><td></td><td></td></tr>
            <tr><td>Ph</td><td><?php echo isset($select_result['uph_1'])?$select_result['uph_1']:"--"; ?></td><td></td><td>7.2-7.8</td></tr>
            
            <tr><td colspan="4" style="font-weight:bold; background:#eee;">Microscopic Examination</td></tr>
            <tr><td>Total sperm concentration</td><td><?php echo isset($select_result['tsc'])?$select_result['tsc']:"--"; ?></td><td>Million/mL</td><td>>15</td></tr>
            <tr><td>Percentage Motility</td><td><?php echo isset($select_result['percentage_motility'])?$select_result['percentage_motility']:"--"; ?></td><td>%</td><td>>40(Grade A+B)</td></tr>
            <tr><td>Grade A (progressive motile)</td><td><?php echo isset($select_result['grade_a'])?$select_result['grade_a']:"--"; ?></td><td>%</td><td>>32</td></tr>
            <tr><td>Grade B (Non progressive motile)</td><td><?php echo isset($select_result['grade_b'])?$select_result['grade_b']:"--"; ?></td><td>%</td><td></td></tr>
            <tr><td>Grade C (Immotile)</td><td><?php echo isset($select_result['grade_c'])?$select_result['grade_c']:"--"; ?></td><td>%</td><td></td></tr>
            <tr><td>Grade D (Immotile)</td><td><?php echo isset($select_result['grade_d'])?$select_result['grade_d']:"--"; ?></td><td>%</td><td></td></tr>
            <tr><td>Agglutination</td><td><?php echo isset($select_result['agglutination'])?$select_result['agglutination']:"--"; ?></td><td></td><td>Negative</td></tr>
            <tr><td>Pus cells</td><td><?php echo isset($select_result['plus_cells1'])?$select_result['plus_cells1']:"--"; ?></td><td>/hpf</td><td>Nil</td></tr>
            <tr><td>Red blood cells</td><td><?php echo isset($select_result['red_blood'])?$select_result['red_blood']:"--"; ?></td><td>/hpf</td><td>Nil</td></tr>
            <tr><td>Epithelial cells</td><td><?php echo isset($select_result['epithelial_cell'])?$select_result['epithelial_cell']:"--"; ?></td><td>/hpf</td><td>Nil</td></tr>
            
            <tr><td colspan="4" style="font-weight:bold; background:#eee;">Morphology</td></tr>
            <tr><td>Normal Morphology</td><td><?php echo isset($select_result['normal_morphology'])?$select_result['normal_morphology']:"--"; ?></td><td>%</td><td>>4</td></tr>
            <tr><td>Abnormal Morphology</td><td><?php echo isset($select_result['abnormal_morphology1'])?$select_result['abnormal_morphology1']:"--"; ?></td><td>%</td><td></td></tr>
            <tr><td>(a) Head Defects</td><td><?php echo isset($select_result['head_defects'])?$select_result['head_defects']:"--"; ?></td><td>%</td><td></td></tr>
            <tr><td>Neck & Midpiece</td><td><?php echo isset($select_result['neck_midpiece'])?$select_result['neck_midpiece']:"--"; ?></td><td>%</td><td></td></tr>
            <tr><td>(b) Tail Defects</td><td><?php echo isset($select_result['head_defects2'])?$select_result['head_defects2']:"--"; ?></td><td>%</td><td></td></tr>
            
            <tr><td colspan="4" style="font-weight:bold; background:#eee;">Chemical Examination & Special Tests</td></tr>
            <tr><td>Semen Fructose Qualitative</td><td><?php echo isset($select_result['semen_fructose_qualitative_1'])?$select_result['semen_fructose_qualitative_1']:"--"; ?></td><td></td><td>Positive</td></tr>
            <tr><td>Sperm Vitality</td><td><?php echo isset($select_result['sperm_vitality'])?$select_result['sperm_vitality']:"--"; ?></td><td>%</td><td>>58</td></tr>
            <tr><td>Hypo osmotic swelling test</td><td><?php echo isset($select_result['host'])?$select_result['host']:"--"; ?></td><td>%</td><td>>58</td></tr>
            <tr><td>DFI (DNA Fragmentation Index)</td><td><?php echo isset($select_result['dfi'])?$select_result['dfi']:"--"; ?></td><td>%</td><td><15</td></tr>
     </tbody>
  </table>
  
  <table style="width:100%; border:1px solid #000; margin-top:15px; border-collapse: collapse;" border="1">
        <tbody>
            <tr>
                <td style="padding:8px; font-size:11px; line-height:1.4;">Not for Medico legal purpose. Results to be correlated with clinical findings for a final diagnosis. Parameters are according to latest WHO Laboratory Manual for Examination & Processing of Human Semen FIFTH EDITION.</td>
            </tr>
            <tr><td style="padding:20px 8px; font-weight:bold; text-align:right;">EMBRYOLOGIST SIGNATURE</td></tr>
        </tbody>
  </table>
</div>

<style>
/* CSS RULES FOR SCREEN MODE */
#master_print_wrapper {
    display: none;
}
.no-print-section {
    display: block;
}

/* 🚀 HYBRID CSS RULES FOR BROWSER PRINT ENGINE */
@media print {
    body * {
        visibility: hidden !important;
    }
    #master_print_wrapper, #master_print_wrapper * {
        visibility: visible !important;
    }
    #master_print_wrapper {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print-section {
        display: none !important;
    }
}

input[type=checkbox], input[type=radio] {
    opacity: 1 !important;
    left: 0 !important;
    position: unset !important;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
td, th {
  text-align: left;
  padding: 6px; 
}
</style>