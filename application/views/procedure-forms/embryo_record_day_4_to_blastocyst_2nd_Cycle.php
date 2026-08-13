<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        $select_query = "SELECT * FROM `hms_blastocyst` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_blastocyst` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " `$key` = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_blastocyst SET ";
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " `$key` = '".$value."'"	;
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
    $select_query = "SELECT * FROM `hms_blastocyst` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);  
	
	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);

	// Dynamic Logo handling
    $page_logo = isset($_SESSION['global_center_logo']) ? $_SESSION['global_center_logo'] : '';
    if(empty($page_logo)) {
        $page_logo = base_url('assets/center/default-logo.png');
    }
?>

<form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
  <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
  <input type="hidden" value="pending" name="status"> 
  <input type="hidden" value="Second Cycle" name="cycle_type"> 
    			<div class="container red-field form mt-5 mb-5">
	<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
	   <tr>
	   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
	   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">EMBRYO RECODR DAY 4 TO BLASTOCYST SECOND CYCLE</h3></td>
	   </tr>
	</table>
	<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
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
 					<div class='table-responsive'>
    					<table class="table table-bordered table-hover mt-2 table-sm red-field">
					        <thead>
					        	<tr>
					        		<th style="background: #548135">Day 4</th>
					        		<th colspan="2" style="background: #AEABAB">Day 5</th>
					        		<th colspan="5" style="background: #C5E0B3">FATE</th>
					        		<th colspan="2" style="background: #FEF2CB">ADD ONS USED AT THE TIME OF TRANSFER</th>
					        		<th colspan="2" style="background: #8EAADB">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<th colspan="1" style="background: #548135">Date: <input type="date" value="<?php echo isset($select_result['date1'])?$select_result['date1']:""; ?>"  name="date1"></th>
					        		<th colspan="2" style="background: #AEABAB">Date: <input type="date" value="<?php echo isset($select_result['date2'])?$select_result['date2']:""; ?>"  name="date2"></th>
					        		<th colspan="5" style="background: #C5E0B3">Date: <input type="date" value="<?php echo isset($select_result['date3'])?$select_result['date3']:""; ?>"  name="date3"></th>
					        		<th colspan="2" style="background: #FEF2CB"></th>
					        		<th colspan="2" style="background: #8EAADB">Date: <input type="date" value="<?php echo isset($select_result['date4'])?$select_result['date4']:""; ?>"  name="date4"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th style="background: #548135">Time: <input type="time" value="<?php echo isset($select_result['time1'])?$select_result['time1']:""; ?>"  name="time1"></th>
					        		<th colspan="2" style="background: #AEABAB">Time: <input type="time" value="<?php echo isset($select_result['time2'])?$select_result['time2']:""; ?>"  name="time2"></th>
					        		<th colspan="5" style="background: #C5E0B3">Time: <input type="time" value="<?php echo isset($select_result['time3'])?$select_result['time3']:""; ?>"  name="time3"></th>
					        		<th colspan="2" style="background: #FEF2CB"></th>
					        		<th colspan="2" style="background: #8EAADB">Time: <input type="time" value="<?php echo isset($select_result['time4'])?$select_result['time4']:""; ?>"  name="time4"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th style="background: #548135">Hrs: <input type="time" value="<?php echo isset($select_result['hrs1'])?$select_result['hrs1']:""; ?>"  name="hrs1"></th>
					        		<th colspan="2" style="background: #AEABAB">Hrs: <input type="time" value="<?php echo isset($select_result['hrs2'])?$select_result['hrs2']:""; ?>"  name="hrs2"></th>
					        		<th colspan="5" style="background: #C5E0B3">Hrs: <input type="time" value="<?php echo isset($select_result['hrs3'])?$select_result['hrs3']:""; ?>"  name="hrs3"></th>
					        		<th colspan="2" style="background: #FEF2CB"></th>
					        		<th colspan="2" style="background: #8EAADB">Hrs: <input type="time" value="<?php echo isset($select_result['hrs4'])?$select_result['hrs4']:""; ?>"  name="hrs4"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th style="background: #548135">Emb: <input type="text" value="<?php echo isset($select_result['emb1'])?$select_result['emb1']:""; ?>"  maxlength="20" name="emb1"  ></th>
					        		<th colspan="2" style="background: #AEABAB">Emb: <input type="text" value="<?php echo isset($select_result['emb2'])?$select_result['emb2']:""; ?>"  maxlength="20" name="emb2"  ></th>
					        		<th colspan="5" style="background: #C5E0B3">Emb: <input type="text" value="<?php echo isset($select_result['emb3'])?$select_result['emb3']:""; ?>"  maxlength="20" name="emb3"  ></th>
					        		<th colspan="2" style="background: #FEF2CB"></th>
					        		<th colspan="2" style="background: #8EAADB">Emb: <input type="text" value="<?php echo isset($select_result['emb4'])?$select_result['emb4']:""; ?>"  maxlength="20" name="emb4"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th style="background: #548135">Wit: <input type="text" value="<?php echo isset($select_result['wit1'])?$select_result['wit1']:""; ?>"  maxlength="20" name="wit1"  ></th>
					        		<th colspan="2" style="background: #AEABAB">Wit: <input type="text" value="<?php echo isset($select_result['wit2'])?$select_result['wit2']:""; ?>"  maxlength="20" name="wit2"  ></th>
					        		<th colspan="5" style="background: #C5E0B3"> Wit: <input type="text" value="<?php echo isset($select_result['wit3'])?$select_result['wit3']:""; ?>"  maxlength="20" name="wit3"  ></th>
					        		<th colspan="2" style="background: #FEF2CB">TOTAL NO OF EMBRYOS ON WITH LAH DONE<input type="text" value="<?php echo isset($select_result['embryos_on_with_lah_done'])?$select_result['embryos_on_with_lah_done']:""; ?>"  maxlength="20" name="embryos_on_with_lah_done"  ></th>
					        		<th colspan="2" style="background: #8EAADB">Wit: <input type="text" value="<?php echo isset($select_result['wit4'])?$select_result['wit4']:""; ?>"  maxlength="20" name="wit4"  ></th>
					        	</tr>
					        </thead>
							<thead>
					        	<tr>
					        		<th style="background: #548135">TOTAL NO OF MORULA <input type="text" value="<?php echo isset($select_result['total_no_of_morula'])?$select_result['total_no_of_morula']:""; ?>"  maxlength="20" name="total_no_of_morula"  ></th>
					        		<th colspan="2" style="background: #AEABAB">TOTAL NO OF BLASTOCYST <input type="text" value="<?php echo isset($select_result['total_no_of_blastocyst'])?$select_result['total_no_of_blastocyst']:""; ?>"  maxlength="20" name="total_no_of_blastocyst"  ></th>
					        		<th colspan="5" style="background: #C5E0B3">Dr. <input type="text" value="<?php echo isset($select_result['dr'])?$select_result['dr']:""; ?>"  maxlength="20" name="dr"  ></th>
									</th>
					        		<th colspan="2" style="background: #FEF2CB">EMBRYO GLUE<input type="text" value="<?php echo isset($select_result['embryo_glue'])?$select_result['embryo_glue']:""; ?>"  maxlength="20" name="embryo_glue"  ></th>
					        		<th colspan="2" style="background: #8EAADB"></th>
					        	</tr>
					        </thead>
					        
					        
					        <thead>
					        	<tr>
					        		<td style="background: #548135">Stage</td>
									<td style="background: #AEABAB">CELL STAGE & GRADE </td>
									<td style="background: #AEABAB">Frag%</td>
									<td style="background: #C5E0B3" colspan="5">TRANSFER/FREEZING/DEGENERATE/DISCARD</td>
									<td style="background: #FEF2CB" colspan="2"></td>
									<td style="background: #8EAADB" colspan="2">Remarks</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #548135"><input type="text" value="<?php echo isset($select_result['stage'])?$select_result['stage']:""; ?>"  maxlength="20" name="stage"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['cell'])?$select_result['cell']:""; ?>"  maxlength="20" name="cell"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['frag'])?$select_result['frag']:""; ?>"  maxlength="20" name="frag"  ></td>
							        <td style="background: #C5E0B3" colspan="5">
									<input type="checkbox" name="type" value="TRANSFER" <?php if(isset($select_result['type']) && $select_result['type'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type" value="FREEZING" <?php if(isset($select_result['type']) && $select_result['type'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type" value="DEGENERATE" <?php if(isset($select_result['type']) && $select_result['type'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type" value="DISCARD" <?php if(isset($select_result['type']) && $select_result['type'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="background: #FEF2CB" colspan="2"></td>
							    	<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['empty'])?$select_result['empty']:""; ?>"  maxlength="20" name="empty"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #548135"><input type="text" value="<?php echo isset($select_result['stage_1'])?$select_result['stage_1']:""; ?>"  maxlength="20" name="stage_1"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['cell_1'])?$select_result['cell_1']:""; ?>"  maxlength="20" name="cell_1"  ></td>
									<td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['frag_1'])?$select_result['frag_1']:""; ?>"  maxlength="20" name="frag_1"  ></td>
							        <td style="background: #C5E0B3" colspan="5">
									<input type="checkbox" name="type_1" value="TRANSFER" <?php if(isset($select_result['type_1']) && $select_result['type_1'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_1" value="FREEZING" <?php if(isset($select_result['type_1']) && $select_result['type_1'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_1" value="DEGENERATE" <?php if(isset($select_result['type_1']) && $select_result['type_1'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_1" value="DISCARD" <?php if(isset($select_result['type_1']) && $select_result['type_1'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="background: #FEF2CB" colspan="2"></td>
							    	<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['empty_1'])?$select_result['empty_1']:""; ?>"  maxlength="20" name="empty_1"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #548135"><input type="text" value="<?php echo isset($select_result['stage_2'])?$select_result['stage_2']:""; ?>"  maxlength="20" name="stage_2"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['cell_2'])?$select_result['cell_2']:""; ?>"  maxlength="20" name="cell_2"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['frag_2'])?$select_result['frag_2']:""; ?>"  maxlength="20" name="frag_2"  ></td>
							        <td style="background: #C5E0B3" colspan="5">
									<input type="checkbox" name="type_2" value="TRANSFER" <?php if(isset($select_result['type_2']) && $select_result['type_2'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_2" value="FREEZING" <?php if(isset($select_result['type_2']) && $select_result['type_2'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_2" value="DEGENERATE" <?php if(isset($select_result['type_2']) && $select_result['type_2'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_2" value="DISCARD" <?php if(isset($select_result['type_2']) && $select_result['type_2'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="background: #FEF2CB" colspan="2"></td>
							    	<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['empty_2'])?$select_result['empty_2']:""; ?>"  maxlength="20" name="empty_2"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #548135"><input type="text" value="<?php echo isset($select_result['stage_3'])?$select_result['stage_3']:""; ?>"  maxlength="20" name="stage_3"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['cell_3'])?$select_result['cell_3']:""; ?>"  maxlength="20" name="cell_3"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['frag_3'])?$select_result['frag_3']:""; ?>"  maxlength="20" name="frag_3"  ></td>
							        <td style="background: #C5E0B3" colspan="5">
									<input type="checkbox" name="type_3" value="TRANSFER" <?php if(isset($select_result['type_3']) && $select_result['type_3'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_3" value="FREEZING" <?php if(isset($select_result['type_3']) && $select_result['type_3'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_3" value="DEGENERATE" <?php if(isset($select_result['type_3']) && $select_result['type_3'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_3" value="DISCARD" <?php if(isset($select_result['type_3']) && $select_result['type_3'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="background: #FEF2CB" colspan="2"></td>
							    	<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['empty_3'])?$select_result['empty_3']:""; ?>"  maxlength="20" name="empty_3"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #548135"><input type="text" value="<?php echo isset($select_result['stage_4'])?$select_result['stage_4']:""; ?>"  maxlength="20" name="stage_4"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['cell_4'])?$select_result['cell_4']:""; ?>"  maxlength="20" name="cell_4"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['frag_4'])?$select_result['frag_4']:""; ?>"  maxlength="20" name="frag_4"  ></td>
									<td style="background: #C5E0B3" colspan="5">
									<input type="checkbox" name="type_4" value="TRANSFER" <?php if(isset($select_result['type_4']) && $select_result['type_4'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_4" value="FREEZING" <?php if(isset($select_result['type_4']) && $select_result['type_4'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_4" value="DEGENERATE" <?php if(isset($select_result['type_4']) && $select_result['type_4'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_4" value="DISCARD" <?php if(isset($select_result['type_4']) && $select_result['type_4'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="background: #FEF2CB" colspan="2"></td>
							    	<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['empty_4'])?$select_result['empty_4']:""; ?>"  maxlength="20" name="empty_4"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #548135"><input type="text" value="<?php echo isset($select_result['stage_5'])?$select_result['stage_5']:""; ?>"  maxlength="20" name="stage_5"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['cell_5'])?$select_result['cell_5']:""; ?>"  maxlength="20" name="cell_5"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['frag_5'])?$select_result['frag_5']:""; ?>"  maxlength="20" name="frag_5"  ></td>
							        <td style="background: #C5E0B3" colspan="5">
									<input type="checkbox" name="type_5" value="TRANSFER" <?php if(isset($select_result['type_5']) && $select_result['type_5'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_5" value="FREEZING" <?php if(isset($select_result['type_5']) && $select_result['type_5'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_5" value="DEGENERATE" <?php if(isset($select_result['type_5']) && $select_result['type_5'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_5" value="DISCARD" <?php if(isset($select_result['type_5']) && $select_result['type_5'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="background: #FEF2CB" colspan="2"></td>
							    	<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['empty_5'])?$select_result['empty_5']:""; ?>"  maxlength="20" name="empty_5"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #548135"><input type="text" value="<?php echo isset($select_result['stage_6'])?$select_result['stage_6']:""; ?>"  maxlength="20" name="stage_6"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['cell_6'])?$select_result['cell_6']:""; ?>"  maxlength="20" name="cell_6"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['frag_6'])?$select_result['frag_6']:""; ?>"  maxlength="20" name="frag_6"  ></td>
							        <td style="background: #C5E0B3" colspan="5">
									<input type="checkbox" name="type_6" value="TRANSFER" <?php if(isset($select_result['type_6']) && $select_result['type_6'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_6" value="FREEZING" <?php if(isset($select_result['type_6']) && $select_result['type_6'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_6" value="DEGENERATE" <?php if(isset($select_result['type_6']) && $select_result['type_6'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_6" value="DISCARD" <?php if(isset($select_result['type_6']) && $select_result['type_6'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="background: #FEF2CB" colspan="2"></td>
							    	<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['empty_6'])?$select_result['empty_6']:""; ?>"  maxlength="20" name="empty_6"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #548135"><input type="text" value="<?php echo isset($select_result['stage_7'])?$select_result['stage_7']:""; ?>"  maxlength="20" name="stage_7"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['cell_7'])?$select_result['cell_7']:""; ?>"  maxlength="20" name="cell_7"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['frag_7'])?$select_result['frag_7']:""; ?>"  maxlength="20" name="frag_7"  ></td>
							        <td style="background: #C5E0B3" colspan="5">
									<input type="checkbox" name="type_7" value="TRANSFER" <?php if(isset($select_result['type_7']) && $select_result['type_7'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_7" value="FREEZING" <?php if(isset($select_result['type_7']) && $select_result['type_7'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_7" value="DEGENERATE" <?php if(isset($select_result['type_7']) && $select_result['type_7'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_7" value="DISCARD" <?php if(isset($select_result['type_7']) && $select_result['type_7'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="background: #FEF2CB" colspan="2"></td>
							    	<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['empty_7'])?$select_result['empty_7']:""; ?>"  maxlength="20" name="empty_7"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #548135"><input type="text" value="<?php echo isset($select_result['stage_8'])?$select_result['stage_8']:""; ?>"  maxlength="20" name="stage_8"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['cell_8'])?$select_result['cell_8']:""; ?>"  maxlength="20" name="cell_8"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['frag_8'])?$select_result['frag_8']:""; ?>"  maxlength="20" name="frag_8"  ></td>
									<td style="background: #C5E0B3" colspan="5">
									<input type="checkbox" name="type_8" value="TRANSFER" <?php if(isset($select_result['type_8']) && $select_result['type_8'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_8" value="FREEZING" <?php if(isset($select_result['type_8']) && $select_result['type_8'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_8" value="DEGENERATE" <?php if(isset($select_result['type_8']) && $select_result['type_8'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_8" value="DISCARD" <?php if(isset($select_result['type_8']) && $select_result['type_8'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="background: #FEF2CB" colspan="2"></td>
							    	<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['empty_8'])?$select_result['empty_8']:""; ?>"  maxlength="20" name="empty_8"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #548135"><input type="text" value="<?php echo isset($select_result['stage_9'])?$select_result['stage_9']:""; ?>"  maxlength="20" name="stage_9"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['cell_9'])?$select_result['cell_9']:""; ?>"  maxlength="20" name="cell_9"  ></td>
							        <td style="background: #AEABAB"><input type="text" value="<?php echo isset($select_result['frag_9'])?$select_result['frag_9']:""; ?>"  maxlength="20" name="frag_9"  ></td>
							        <td style="background: #C5E0B3" colspan="5">
									<input type="checkbox" name="type_9" value="TRANSFER" <?php if(isset($select_result['type_9']) && $select_result['type_9'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_9" value="FREEZING" <?php if(isset($select_result['type_9']) && $select_result['type_9'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_9" value="DEGENERATE" <?php if(isset($select_result['type_9']) && $select_result['type_9'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_9" value="DISCARD" <?php if(isset($select_result['type_9']) && $select_result['type_9'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="background: #FEF2CB" colspan="2"></td>
							    	<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['empty_9'])?$select_result['empty_9']:""; ?>"  maxlength="20" name="empty_9"  ></td>
					        	</tr>
					        </thead>
					    </table>
					</div>
				<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
				</div>
			</form>
			
			
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
	   <tr>
	   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo $page_logo; ?>" class="center" style="width:250px; display: block; margin: 0 auto;" alt="Center Logo"></td>
	   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">EMBRYO RECODR DAY 4 TO BLASTOCYST SECOND CYCLE</h3></td>
	   </tr>
	</table>
	<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
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
					        		<th colspan="1" style="border:1px solid;padding:5px;">Day 4</th>
					        		<th colspan="2" style="border:1px solid;padding:5px;">Day 5</th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">FATE</th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">ADD ONS USED AT THE TIME OF TRANSFER</th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<th colspan="1" style="border:1px solid;padding:5px;">Date: <?php echo isset($select_result['date1'])?$select_result['date1']:""; ?></th>
					        		<th colspan="2" style="border:1px solid;padding:5px;">Date: <?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Date: <?php echo isset($select_result['date3'])?$select_result['date3']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;"></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Date: <?php echo isset($select_result['date4'])?$select_result['date4']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Time: <?php echo isset($select_result['time1'])?$select_result['time1']:""; ?></th>
					        		<th colspan="2" style="border:1px solid;padding:5px;">Time: <?php echo isset($select_result['time2'])?$select_result['time2']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Time: <?php echo isset($select_result['time3'])?$select_result['time3']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;"></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Time: <?php echo isset($select_result['time4'])?$select_result['time4']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Hrs: <?php echo isset($select_result['hrs1'])?$select_result['hrs1']:""; ?></th>
					        		<th colspan="2" style="border:1px solid;padding:5px;">Hrs: <?php echo isset($select_result['hrs2'])?$select_result['hrs2']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Hrs: <?php echo isset($select_result['hrs3'])?$select_result['hrs3']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;"></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Hrs: <?php echo isset($select_result['hrs4'])?$select_result['hrs4']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Emb: <?php echo isset($select_result['emb1'])?$select_result['emb1']:""; ?></th>
					        		<th colspan="2" style="border:1px solid;padding:5px;">Emb: <?php echo isset($select_result['emb2'])?$select_result['emb2']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Emb: <?php echo isset($select_result['emb3'])?$select_result['emb3']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;"></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Emb: <?php echo isset($select_result['emb4'])?$select_result['emb4']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Wit: <?php echo isset($select_result['wit1'])?$select_result['wit1']:""; ?></th>
					        		<th colspan="2" style="border:1px solid;padding:5px;">Wit: <?php echo isset($select_result['wit2'])?$select_result['wit2']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;"> Wit: <?php echo isset($select_result['wit3'])?$select_result['wit3']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">TOTAL NO OF EMBRYOS ON WITH LAH DONE <?php echo isset($select_result['embryos_on_with_lah_done'])?$select_result['embryos_on_with_lah_done']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Wit: <?php echo isset($select_result['wit4'])?$select_result['wit4']:""; ?></th>
					        	</tr>
					        </thead>
							<thead>
					        	<tr>
					        		<th colspan="1" style="border:1px solid;padding:5px;">TOTAL NO OF MORULA <?php echo isset($select_result['total_no_of_morula'])?$select_result['total_no_of_morula']:""; ?></th>
					        		<th colspan="2" style="border:1px solid;padding:5px;">TOTAL NO OF BLASTOCYST <?php echo isset($select_result['total_no_of_blastocyst'])?$select_result['total_no_of_blastocyst']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">Dr. <?php echo isset($select_result['dr'])?$select_result['dr']:""; ?></th>
									</th>
					        		<th colspan="1" style="border:1px solid;padding:5px;">EMBRYO GLUE <?php echo isset($select_result['embryo_glue'])?$select_result['embryo_glue']:""; ?></th>
					        		<th colspan="1" style="border:1px solid;padding:5px;"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td style="border:1px solid;padding:5px;">Stage</td>
									<td style="border:1px solid;padding:5px;">CELL STAGE & GRADE </td>
									<td style="border:1px solid;padding:5px;">Frag%</td>
									<td style="border:1px solid;padding:5px;">TRANSFER/FREEZING/DEGENERATE/DISCARD</td>
									<td style="border:1px solid;padding:5px;"></td>
									<td style="border:1px solid;padding:5px;">Remarks</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['stage'])?$select_result['stage']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['cell'])?$select_result['cell']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['frag'])?$select_result['frag']:""; ?></td>
							        <td style="border:1px solid;padding:5px;">
									<input type="checkbox" name="type" value="TRANSFER" <?php if(isset($select_result['type']) && $select_result['type'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type" value="FREEZING" <?php if(isset($select_result['type']) && $select_result['type'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type" value="DEGENERATE" <?php if(isset($select_result['type']) && $select_result['type'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type" value="DISCARD" <?php if(isset($select_result['type']) && $select_result['type'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
									<td style="border:1px solid;padding:5px;"></td>
                                    <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['empty'])?$select_result['empty']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['stage_1'])?$select_result['stage_1']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['cell_1'])?$select_result['cell_1']:""; ?></td>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['frag_1'])?$select_result['frag_1']:""; ?></td>
							        <td style="border:1px solid;padding:5px;">
									<input type="checkbox" name="type_1" value="TRANSFER" <?php if(isset($select_result['type_1']) && $select_result['type_1'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_1" value="FREEZING" <?php if(isset($select_result['type_1']) && $select_result['type_1'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_1" value="DEGENERATE" <?php if(isset($select_result['type_1']) && $select_result['type_1'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_1" value="DISCARD" <?php if(isset($select_result['type_1']) && $select_result['type_1'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="border:1px solid;padding:5px;"></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['empty_1'])?$select_result['empty_1']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['stage_2'])?$select_result['stage_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['cell_2'])?$select_result['cell_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['frag_2'])?$select_result['frag_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;">
									<input type="checkbox" name="type_2" value="TRANSFER" <?php if(isset($select_result['type_2']) && $select_result['type_2'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_2" value="FREEZING" <?php if(isset($select_result['type_2']) && $select_result['type_2'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_2" value="DEGENERATE" <?php if(isset($select_result['type_2']) && $select_result['type_2'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_2" value="DISCARD" <?php if(isset($select_result['type_2']) && $select_result['type_2'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="border:1px solid;padding:5px;"></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['empty_2'])?$select_result['empty_2']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['stage_3'])?$select_result['stage_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['cell_3'])?$select_result['cell_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['frag_3'])?$select_result['frag_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;">
									<input type="checkbox" name="type_3" value="TRANSFER" <?php if(isset($select_result['type_3']) && $select_result['type_3'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_3" value="FREEZING" <?php if(isset($select_result['type_3']) && $select_result['type_3'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_3" value="DEGENERATE" <?php if(isset($select_result['type_3']) && $select_result['type_3'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_3" value="DISCARD" <?php if(isset($select_result['type_3']) && $select_result['type_3'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="border:1px solid;padding:5px;"></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['empty_3'])?$select_result['empty_3']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['stage_4'])?$select_result['stage_4']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['cell_4'])?$select_result['cell_4']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['frag_4'])?$select_result['frag_4']:""; ?></td>
									<td style="border:1px solid;padding:5px;">
									<input type="checkbox" name="type_4" value="TRANSFER" <?php if(isset($select_result['type_4']) && $select_result['type_4'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_4" value="FREEZING" <?php if(isset($select_result['type_4']) && $select_result['type_4'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_4" value="DEGENERATE" <?php if(isset($select_result['type_4']) && $select_result['type_4'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_4" value="DISCARD" <?php if(isset($select_result['type_4']) && $select_result['type_4'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="border:1px solid;padding:5px;"></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['empty_4'])?$select_result['empty_4']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['stage_5'])?$select_result['stage_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['cell_5'])?$select_result['cell_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['frag_5'])?$select_result['frag_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;">
									<input type="checkbox" name="type_5" value="TRANSFER" <?php if(isset($select_result['type_5']) && $select_result['type_5'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_5" value="FREEZING" <?php if(isset($select_result['type_5']) && $select_result['type_5'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_5" value="DEGENERATE" <?php if(isset($select_result['type_5']) && $select_result['type_5'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_5" value="DISCARD" <?php if(isset($select_result['type_5']) && $select_result['type_5'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="border:1px solid;padding:5px;"></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['empty_5'])?$select_result['empty_5']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['stage_6'])?$select_result['stage_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['cell_6'])?$select_result['cell_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['frag_6'])?$select_result['frag_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;">
									<input type="checkbox" name="type_6" value="TRANSFER" <?php if(isset($select_result['type_6']) && $select_result['type_6'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_6" value="FREEZING" <?php if(isset($select_result['type_6']) && $select_result['type_6'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_6" value="DEGENERATE" <?php if(isset($select_result['type_6']) && $select_result['type_6'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_6" value="DISCARD" <?php if(isset($select_result['type_6']) && $select_result['type_6'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="border:1px solid;padding:5px;"></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['empty_6'])?$select_result['empty_6']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['stage_7'])?$select_result['stage_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['cell_7'])?$select_result['cell_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['frag_7'])?$select_result['frag_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;">
									<input type="checkbox" name="type_7" value="TRANSFER" <?php if(isset($select_result['type_7']) && $select_result['type_7'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_7" value="FREEZING" <?php if(isset($select_result['type_7']) && $select_result['type_7'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_7" value="DEGENERATE" <?php if(isset($select_result['type_7']) && $select_result['type_7'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_7" value="DISCARD" <?php if(isset($select_result['type_7']) && $select_result['type_7'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="border:1px solid;padding:5px;"></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['empty_7'])?$select_result['empty_7']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['stage_8'])?$select_result['stage_8']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['cell_8'])?$select_result['cell_8']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['frag_8'])?$select_result['frag_8']:""; ?></td>
									<td style="border:1px solid;padding:5px;">
									<input type="checkbox" name="type_8" value="TRANSFER" <?php if(isset($select_result['type_8']) && $select_result['type_8'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_8" value="FREEZING" <?php if(isset($select_result['type_8']) && $select_result['type_8'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_8" value="DEGENERATE" <?php if(isset($select_result['type_8']) && $select_result['type_8'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_8" value="DISCARD" <?php if(isset($select_result['type_8']) && $select_result['type_8'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="border:1px solid;padding:5px;"></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['empty_8'])?$select_result['empty_8']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['stage_9'])?$select_result['stage_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['cell_9'])?$select_result['cell_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['frag_9'])?$select_result['frag_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;">
									<input type="checkbox" name="type_9" value="TRANSFER" <?php if(isset($select_result['type_9']) && $select_result['type_9'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type_9" value="FREEZING" <?php if(isset($select_result['type_9']) && $select_result['type_9'] == "TRANSFER"){echo 'checked="checked"'; }?>> FREEZING<br>
							        <input type="checkbox" name="type_9" value="DEGENERATE" <?php if(isset($select_result['type_9']) && $select_result['type_9'] == "FREEZING"){echo 'checked="checked"'; }?>> DEGENERATE<br>
									<input type="checkbox" name="type_9" value="DISCARD" <?php if(isset($select_result['type_9']) && $select_result['type_9'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DISCARD<br>
                                    </td>
                                    <td style="border:1px solid;padding:5px;"></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['empty_9'])?$select_result['empty_9']:""; ?></td>
					        	</tr>
					        </thead>
					    </table>
</div>
<style type="text/css">
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: static!important;
    left: -9999px;
    opacity: 1!important;
}
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
			