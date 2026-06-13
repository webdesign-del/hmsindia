<?php

    // php code to Insert data into mysql database from input text

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
        $select_result = run_select_query($select_query); 

        if(empty($select_result)){

            // mysql query to insert data

            $query = "INSERT INTO `andrology` SET ";

            $sqlArr = array();

            foreach( $_POST as $key=> $value )

            {

              $sqlArr[] = " $key = '".addslashes($value)."'";

            }		

            $query .= implode(',' , $sqlArr);

        }else{

            // mysql query to update data

            $query = "UPDATE andrology SET ";

            foreach( $_POST as $key=> $value )

            {

              $sqlArr[] = " $key = '".$value."'"	;

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

    $select_query = "SELECT * FROM `andrology` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query); 

	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);	
?>

<form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">

  <input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">

  <input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">

  <input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">

  <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">  

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
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
	   </table>

      <table class="table table-bordered table-hover mt-2 table-sm red-field">

      <thead>

            <tr>

              <td colspan="4">

        			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&

        			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 

        			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])

        			            ){?>

        			        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>

        			    <?php } ?>

        			</td>

            </tr>

      <thead>

      </table>

    <table class="table table-bordered table-hover mt-2 table-sm red-field">

     <thead>

            <tr>

            <th class="text-aloign-center">SEMEN EXAMINATION</th>



            </tr>

     <thead>

    </table>

    <div class='table-responsive'>

    <table class="table table-bordered table-hover mt-2 table-sm red-field">

      <tbody>

            <tr>

                <td>Physical Examination</td>

                <td>VALUES</td>

                <td>UNIT</td>

                <td>REF RANGE</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td>Time of specimen</td>

                <td><input type="time"  class="form" value="<?php echo isset($select_result['time_of_specimen'])?$select_result['time_of_specimen']:""; ?>"  name="time_of_specimen"></td>

                <td>AM/PM</td>

                <td></td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td>Time of Examination</td>

                <td><input type="time"  class="form" value="<?php echo isset($select_result['time_of_examination'])?$select_result['time_of_examination']:""; ?>"  name="time_of_examination"></td>

                <td>AM/PM</td>

                <td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td>Duration of abstinence</td>

                <td><input type="number" min="0"  class="form" value="<?php echo isset($select_result['doa'])?$select_result['doa']:""; ?>"  name="doa"></td>

                <td>Days</td>

                <td>2-7 </td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <?php //var_dump($select_result);die; ?>

                <td>Liquefaction at 37 c </td>

                <td><input type="number" min="0"  class="form" value="<?php echo isset($select_result['liquefaction'])?$select_result['liquefaction']:""; ?>"  name="liquefaction"></td>

                <td>Minutes</td>

                <td>30-60</td>

            </tr>

          </tbody>

            <tbody>

                <tr>

                    <td>Volume</td>

                    <td><input type="text" min="0"  class="form"  pattern="[-+]?[0-9]*[.,]?[0-9]+" value="<?php echo isset($select_result['volume'])?$select_result['volume']:""; ?>"  name="volume"></td>

                    <td>ML</td>

                    <td>>1.5</td>

                </tr>

            </tbody>

            <tbody>

                <tr>

                    <td>Appearance</td>

                    <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['appearance1'])?$select_result['appearance1']:""; ?>"  name="appearance1"></td>

                    <td></td>

                    <td></td>

                </tr>

            </tbody>



          <tbody>

                 <tr>

                <td>Colour</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['color_1'])?$select_result['color_1']:""; ?>"  name="color_1"></td>

                <td></td>

                <td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td>Viscosity</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['viscosity_1'])?$select_result['viscosity_1']:""; ?>"  name="viscosity_1"></td>

                <td></td>

                <td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td>Ph</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['uph_1'])?$select_result['uph_1']:""; ?>"  name="uph_1"></td>

                <td></td>

             <td>7.2-7.8</td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td><strong>Microscopic Examination</strong></td>

                <td></td>

                <td></td>

                <td></td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td>Total sperm concentration</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['tsc'])?$select_result['tsc']:""; ?>"  name="tsc"></td>

                <td>Million/mL</td>

                <td>>15</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td>Percentage Motility</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['percentage_motility'])?$select_result['percentage_motility']:""; ?>"  name="percentage_motility"></td>

                <td>%</td>

                <td>>40(Grade A+B)</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td>Grade A(progressive motile)</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['grade_a'])?$select_result['grade_a']:""; ?>"  name="grade_a"></td>

                <td>%</td>

                <td>>32(lower reference limit)</td>

            </tr>

          </tbody>

        <tbody>

            <tr>

                <td>Grade B (Non progressive motile)</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['grade_b'])?$select_result['grade_b']:""; ?>"  name="grade_b"></td>

                <td>%</td>

                <td></td>

            </tr>

          </tbody>

            <tbody>

            <tr>

                <td>Grade C (Immotile)</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['grade_c'])?$select_result['grade_c']:""; ?>"  name="grade_c"></td>

                <td>%</td>

                <td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td>Grade D (Immotile)</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['grade_d'])?$select_result['grade_d']:""; ?>"  name="grade_d"></td>

                <td>%</td>

                <td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td>Agglutination</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['agglutination'])?$select_result['agglutination']:""; ?>"  name="agglutination"></td>

                <td></td>

                <td>Negative</td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td>Pus cells</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['plus_cells1'])?$select_result['plus_cells1']:""; ?>"  name="plus_cells1"></td>

                <td>/hpf</td>

                <td>Nil</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td>Red blood cells</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['red_blood'])?$select_result['red_blood']:""; ?>"  name="red_blood"></td>

                <td>/hpf</td>

                <td>Nil</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td>Epithelial cells</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['epithelial_cell'])?$select_result['epithelial_cell']:""; ?>"  name="epithelial_cell"></td>

                <td>/hpf</td>

                <td>Nil</td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td><strong>Morphology</strong></td>

                <td></td><td></td><td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td>Normal Morphology</td>

            <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['normal_morphology'])?$select_result['normal_morphology']:""; ?>"  name="normal_morphology"></td>

             <td>%</td>

             <td>>4</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

             <td>Abnormal Morphology</td>

            <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['abnormal_morphology1'])?$select_result['abnormal_morphology1']:""; ?>"  name="abnormal_morphology1"></td>

             <td>%</td>

             <td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td>(a)Head Defects</td>

            <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['head_defects'])?$select_result['head_defects']:""; ?>"  name="head_defects"></td>

             <td>%</td>

             <td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td>Neck&Midpiece</td>

            <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['neck_midpiece'])?$select_result['neck_midpiece']:""; ?>"  name="neck_midpiece"></td>

             <td>%</td>

             <td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td>(a)Tail Defects</td>

            <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['head_defects2'])?$select_result['head_defects2']:""; ?>"  name="head_defects2"></td>

             <td>%</td>

             <td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td><strong>Chemical Examination</strong></td>

                <td></td><td></td><td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td>Semen Fructose Qualitative</td>

                <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['semen_fructose_qualitative_1'])?$select_result['semen_fructose_qualitative_1']:""; ?>"  name="semen_fructose_qualitative_1"></td>

                <td></td>

            <td>Positive</td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td><strong>Special Test</strong></td>

                <td></td><td></td><td></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td>Sperm Vitality</td>

            <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['sperm_vitality'])?$select_result['sperm_vitality']:""; ?>"  name="sperm_vitality"></td>

             <td>%</td>

             <td>>58</td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td>Hypo osmotic swelling test</td>

            <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['host'])?$select_result['host']:""; ?>"  name="host"></td>

             <td>%</td>

             <td>>58</td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td>DFI(DNA Fragmentation Index)</td>

            <td><input type="text" maxlength="20"  class="form" value="<?php echo isset($select_result['dfi'])?$select_result['dfi']:""; ?>"  name="dfi"></td>

             <td>%</td>

             <td><15</td>

            </tr>

          </tbody>

            <tbody>

                <tr>

                    <td>Upload</td>

                    <td>

                      <input type = "file" id="file" name="upload" multiple change="onFileChange"/>

                      <a target="_blank" href="<?php echo !empty($select_result['upload'])?$select_result['upload']:"javascript:void(0)"; ?>">Download</a>

                     </td>

                    <td></td>

                    <td></td>

                </tr>

            </tbody>

        </table>

    </div>

            <table class="table table-bordered table-hover mt-2 table-sm red-field">

                <thead>

                    <tr>

                        <th>Not for Medico legal purpose. Results to be correlated with clinical findings for a final diagnosis Parameters are according to latest WHO Laboratory Manual for Examination & Processing of Human Semen FIFTH EDITION</th>

                    </tr>

                </thead>

                <thead>

                    <tr><th>EMBRYOLOGIST</th></tr>

                </thead>

            </table>

            <!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->

            <input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">

        </form>

    </div>

</table>








<!-- print -->

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none">  
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
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
<td colspan="2" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
	   </table>
<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">



 <thead>
<tr>

                <td colspan="4" style="border:1px solid #cdcdcd;">

                        <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&

                                isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 

                                isset($select_result['updated_type']) && !empty($select_result['updated_type'])

                                ){?>

                            <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>

                        <?php } ?>

                    </td>
</tr>           
 </thead> 

<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">

     <thead>

            <tr>

            <th class="text-aloign-center">SEMEN EXAMINATION</th>



            </tr>

     </thead>

    

      <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Physical Examination</td>

                <td  style="border:1px solid #cdcdcd;">VALUES</td>

                <td  style="border:1px solid #cdcdcd;">UNIT</td>

                <td  style="border:1px solid #cdcdcd;">REF RANGE</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Time of specimen</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['time_of_specimen'])?$select_result['time_of_specimen']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">AM/PM</td>

                <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Time of Examination</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['time_of_examination'])?$select_result['time_of_examination']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">AM/PM</td>

                <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Duration of abstinence</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['doa'])?$select_result['doa']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">Days</td>

                <td  style="border:1px solid #cdcdcd;">2-7 </td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <?php //var_dump($select_result);die; ?>

                <td  style="border:1px solid #cdcdcd;">Liquefaction at 37 c </td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['liquefaction'])?$select_result['liquefaction']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">Minutes</td>

                <td  style="border:1px solid #cdcdcd;">30-60</td>

            </tr>

          </tbody>

            <tbody>

                <tr>

                    <td  style="border:1px solid #cdcdcd;">Volume</td>

                    <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['volume'])?$select_result['volume']:""; ?></td>

                    <td  style="border:1px solid #cdcdcd;">ML</td>

                    <td  style="border:1px solid #cdcdcd;">>1.5</td>

                </tr>

            </tbody>

            <tbody>

                <tr>

                    <td  style="border:1px solid #cdcdcd;">Appearance</td>

                    <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['appearance1'])?$select_result['appearance1']:""; ?></td>

                    <td  style="border:1px solid #cdcdcd;"></td>

                    <td  style="border:1px solid #cdcdcd;"></td>

                </tr>

            </tbody>



          <tbody>

                 <tr>

                <td  style="border:1px solid #cdcdcd;">Colour</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['color_1'])?$select_result['color_1']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;"></td>

                <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Viscosity</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['viscosity_1'])?$select_result['viscosity_1']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;"></td>

                <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Ph</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['uph_1'])?$select_result['uph_1']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;"></td>

             <td  style="border:1px solid #cdcdcd;">7.2-7.8</td>

            </tr>

          </tbody>
           </table>
           <table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">

           <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;"><strong>Microscopic Examination</strong></td>

               

            </tr>

          </tbody>
          <tbody>

            <tr >

                <td  style="border:1px solid #cdcdcd;">Total sperm concentration</td>

                <td style="width: 40%;"><?php echo isset($select_result['tsc'])?$select_result['tsc']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">Million/mL</td>

                <td  style="border:1px solid #cdcdcd;">>15</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Percentage Motility</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['percentage_motility'])?$select_result['percentage_motility']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">%</td>

                <td  style="border:1px solid #cdcdcd;">>40(Grade A+B)</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Grade A(progressive motile)</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_a'])?$select_result['grade_a']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">%</td>

                <td  style="border:1px solid #cdcdcd;">>32(lower reference limit)</td>

            </tr>

          </tbody>

        <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Grade B (Non progressive motile)</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_b'])?$select_result['grade_b']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">%</td>

                <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

            <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Grade C (Immotile)</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_c'])?$select_result['grade_c']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">%</td>

                <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Grade D (Immotile)</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_d'])?$select_result['grade_d']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">%</td>

                <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Agglutination</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agglutination'])?$select_result['agglutination']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;"></td>

                <td  style="border:1px solid #cdcdcd;">Negative</td>

            </tr>

          </tbody>

          <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Pus cells</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['plus_cells1'])?$select_result['plus_cells1']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">/hpf</td>

                <td  style="border:1px solid #cdcdcd;">Nil</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Red blood cells</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['red_blood'])?$select_result['red_blood']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">/hpf</td>

                <td  style="border:1px solid #cdcdcd;">Nil</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Epithelial cells</td>

                <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['epithelial_cell'])?$select_result['epithelial_cell']:""; ?></td>

                <td  style="border:1px solid #cdcdcd;">/hpf</td>

                <td  style="border:1px solid #cdcdcd;">Nil</td>

            </tr>

          </tbody>

      </table>

      <table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">

           <tbody>

            <tr>
                 <td  style="border:1px solid #cdcdcd;"><strong>Morphology</strong></td>
            </tr>

          </tbody>

          <tbody>

            <tr>

             <td  style="border:1px solid #cdcdcd;">Normal Morphology</td>

            <td style="width:40%"><?php echo isset($select_result['normal_morphology'])?$select_result['normal_morphology']:""; ?></td>

             <td  style="border:1px solid #cdcdcd;">%</td>

             <td  style="border:1px solid #cdcdcd;">>4</td>

            </tr>

          </tbody>

           <tbody>

            <tr>

             <td  style="border:1px solid #cdcdcd;">Abnormal Morphology</td>

            <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['abnormal_morphology1'])?$select_result['abnormal_morphology1']:""; ?></td>

             <td  style="border:1px solid #cdcdcd;">%</td>

             <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td  style="border:1px solid #cdcdcd;">(a)Head Defects</td>

            <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['head_defects'])?$select_result['head_defects']:""; ?></td>

             <td  style="border:1px solid #cdcdcd;">%</td>

             <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td  style="border:1px solid #cdcdcd;">Neck&Midpiece</td>

            <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['neck_midpiece'])?$select_result['neck_midpiece']:""; ?></td>

             <td  style="border:1px solid #cdcdcd;">%</td>

             <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td  style="border:1px solid #cdcdcd;">(a)Tail Defects</td>

            <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['head_defects2'])?$select_result['head_defects2']:""; ?></td>

             <td  style="border:1px solid #cdcdcd;">%</td>

             <td  style="border:1px solid #cdcdcd;"></td>

            </tr>

          </tbody>

           </table>

            <table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">

           <tbody>

            <tr>
                 <td  style="border:1px solid #cdcdcd;"><strong>Chemical Examination</strong></td>
            </tr>

          </tbody>
            <tbody>

            <tr>

                <td  style="border:1px solid #cdcdcd;">Semen Fructose Qualitative</td>

                <td style="width:30%"><?php echo isset($select_result['semen_fructose_qualitative_1'])?$select_result['semen_fructose_qualitative_1']:""; ?></td>

                <td style="width:30%"></td>

            <td  style="border:1px solid #cdcdcd;">Positive</td>

            </tr>

          </tbody>
          </table>

          <table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">

           <tbody>

            <tr>
                <td  style="border:1px solid #cdcdcd;"><strong>Special Test</strong></td>
            </tr>

          </tbody>
            <tbody>

            <tr>

             <td  style="border:1px solid #cdcdcd;">Sperm Vitality</td>

            <td style="width:40%"><?php echo isset($select_result['sperm_vitality'])?$select_result['sperm_vitality']:""; ?></td>

             <td  style="border:1px solid #cdcdcd;">%</td>

             <td  style="border:1px solid #cdcdcd;">>58</td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td  style="border:1px solid #cdcdcd;">Hypo osmotic swelling test</td>

            <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['host'])?$select_result['host']:""; ?></td>

             <td  style="border:1px solid #cdcdcd;">%</td>

             <td  style="border:1px solid #cdcdcd;">>58</td>

            </tr>

          </tbody>

          <tbody>

            <tr>

             <td  style="border:1px solid #cdcdcd;">DFI(DNA Fragmentation Index)</td>

            <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dfi'])?$select_result['dfi']:""; ?></td>

             <td  style="border:1px solid #cdcdcd;">%</td>

             <td  style="border:1px solid #cdcdcd;"><15</td>

            </tr>

          </tbody>

            <tbody>

                <tr>

                    <td  style="border:1px solid #cdcdcd;">Upload</td>

                    <td  style="border:1px solid #cdcdcd;">

                     
                     </td>

                    <td  style="border:1px solid #cdcdcd;"></td>

                    <td  style="border:1px solid #cdcdcd;"></td>

                </tr>

            </tbody>
          </table>
            <table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">

                <thead>

                    <tr>

                        <th  style="border:1px solid #cdcdcd;">Not for Medico legal purpose. Results to be correlated with clinical findings for a final diagnosis Parameters are according to latest WHO Laboratory Manual for Examination & Processing of Human Semen FIFTH EDITION</th>

                    </tr>

                </thead>

                <thead>

                    <tr><th  style="border:1px solid #cdcdcd;">EMBRYOLOGIST</th></tr>

                </thead>

            </table>
</div>

<script>
function printtable() 
{
    //alert();       
  $('.searchform').hide();
   $('.printbtn').hide();
  $('.printbtn').css('display', 'hide');
  $('.prtable').css('display', 'block');
  var divToPrint=document.getElementById('printtable');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}
</script>