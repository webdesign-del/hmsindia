<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        $select_query = "SELECT * FROM `hms_embryo_thawing` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_embryo_thawing` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_embryo_thawing SET ";
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
    $select_query = "SELECT * FROM `hms_embryo_thawing` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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

<form enctype='multipart/form-data' class ="searchform" name="form" action="" method="POST">
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
<input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
<input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
<input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
<input type="hidden" value="pending" name="status"> 
<input type="hidden" value="Second Cycle" name="type"> 
    			<div class="container2 red-field form mt-5 mb-5">
				<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">EMBRYO THAWED SECOND CYCLE</h3></td>
   </tr>
</table>

<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;" >
     				  <table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">

<td colspan="3" width="34%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="3" width="100%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="6" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>
</tr>
	   </table>
    				<div class='table-responsive2'>
    					<table class="table table-bordered table-hover mt-2 table-sm red-field">
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">EMBRYO THAWED</th>
					        		<th colspan="6" style="background: #F4B083">FATE OF EMBRYOS</th>
					        		<th colspan="4" style="background: #C5E0B3">TOTAL NO OF STRAW REMAINING</th>
					        		<th colspan="3" style="background: #8EAADB">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Date: <input type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"  name="date"></th>
					        		<th colspan="6" style="background: #F4B083">Date: <input type="date" value="<?php echo isset($select_result['date2'])?$select_result['date2']:""; ?>"  name="date2"></th>
					        		<th colspan="4" style="background: #C5E0B3">Date: <input type="date" value="<?php echo isset($select_result['date3'])?$select_result['date3']:""; ?>"  name="date3"></th>
					        		<th colspan="3" style="background: #8EAADB">Date: <input type="date" value="<?php echo isset($select_result['date4'])?$select_result['date4']:""; ?>"  name="date4"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Time: <input type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"  name="time"></th>
					        		<th colspan="6" style="background: #F4B083">Diss.Time: <input type="time" value="<?php echo isset($select_result['time2'])?$select_result['time2']:""; ?>"  name="time2"></th>
					        		<th colspan="4" style="background: #C5E0B3">Time: <input type="time" value="<?php echo isset($select_result['time3'])?$select_result['time3']:""; ?>"  name="time3"></th>
					        		<th colspan="3" style="background: #8EAADB">Time: <input type="time" value="<?php echo isset($select_result['time4'])?$select_result['time4']:""; ?>"  name="time4"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Emb: <input type="text" value="<?php echo isset($select_result['emb'])?$select_result['emb']:""; ?>"  maxlength="20" name="emb"  ></th>
					        		<th colspan="6" style="background: #F4B083">Score Time: <input type="time" value="<?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?>"  name="score_time" ></th>
					        		<th colspan="4" style="background: #C5E0B3">Hrs: <input type="time" value="<?php echo isset($select_result['hrs'])?$select_result['hrs']:""; ?>"  name="hrs"></th>
					        		<th colspan="3" style="background: #8EAADB">Hrs: <input type="time" value="<?php echo isset($select_result['hrs2'])?$select_result['hrs2']:""; ?>"  name="hrs2"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Dr: <input type="text" value="<?php echo isset($select_result['dr'])?$select_result['dr']:""; ?>"  maxlength="20" name="dr"  ></th>
					        		<th colspan="6" style="background: #F4B083">Emb: <input type="text" value="<?php echo isset($select_result['emb'])?$select_result['emb']:""; ?>"  maxlength="20" name="emb"  ></th>
					        		<th colspan="4" style="background: #C5E0B3">Emb: <input type="text" value="<?php echo isset($select_result['emb2'])?$select_result['emb2']:""; ?>"  maxlength="20" name="emb2"  ></th>
					        		<th colspan="3" style="background: #8EAADB">Emb: <input type="text" value="<?php echo isset($select_result['emb3'])?$select_result['emb3']:""; ?>"  maxlength="20" name="emb3"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Witness: <input type="text" value="<?php echo isset($select_result['witness'])?$select_result['witness']:""; ?>"  maxlength="20" name="witness"></th>
					        		<th colspan="6" style="background: #F4B083">Witness: <input type="text" value="<?php echo isset($select_result['witness2'])?$select_result['witness2']:""; ?>"  maxlength="20" name="witness2"></th>
					        		<th colspan="4" style="background: #C5E0B3">Witness: <input type="text" value="<?php echo isset($select_result['witness3'])?$select_result['witness3']:""; ?>"  maxlength="20" name="witness3"></th>
					        		<th colspan="3" style="background: #8EAADB">Wit: <input type="text" value="<?php echo isset($select_result['witness4'])?$select_result['witness4']:""; ?>"  maxlength="20" name="witness4"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td style="background: #FFC000">NO OF STRAW THAWED</td>
									<td style="background: #FFC000">NO OF EMBRYOS WARMED</td>
									<td style="background: #FFC000">NO OF EMBRYOS INTACT</td>
									<th colspan="6" style="background: #F4B083">TRANSFERRED/REFROZEN/PGT/DISCARD</th>
									<th colspan="4" style="background: #C5E0B3"></th>
									<td colspan="3" style="background: #8EAADB"></td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_0'])?$select_result['nost_0']:""; ?>" name="nost_0"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noew_0'])?$select_result['noew_0']:""; ?>"  name="noew_0"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noei_0'])?$select_result['noei_0']:""; ?>"  name="noei_0"  ></td>
							        <td colspan="6" style="background: #F4B083">
									<input type="checkbox" name="transfer_0" value="TRANSFERRED" <?php if(isset($select_result['transfer_0']) && $select_result['transfer_0'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_0" value="REFROZEN" <?php if(isset($select_result['transfer_0']) && $select_result['transfer_0'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_0" value="PGT" <?php if(isset($select_result['transfer_0']) && $select_result['transfer_0'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_0" value="DISCARD" <?php if(isset($select_result['transfer_0']) && $select_result['transfer_0'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
									<th colspan="4" style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['witness15'])?$select_result['witness15']:""; ?>" name="witness15"  ></th>
									<td colspan="3" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['witness5'])?$select_result['witness5']:""; ?>" name="witness5"  ></td>
								</tr>
					        </thead>
							<thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_1'])?$select_result['nost_1']:""; ?>" name="nost_1"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noew_1'])?$select_result['noew_1']:""; ?>"  name="noew_1"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noei_1'])?$select_result['noei_1']:""; ?>"  name="noei_1"  ></td>
							        <td colspan="6" style="background: #F4B083">
									<input type="checkbox" name="transfer_1" value="TRANSFERRED" <?php if(isset($select_result['transfer_1']) && $select_result['transfer_9'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_1" value="REFROZEN" <?php if(isset($select_result['transfer_1']) && $select_result['transfer_9'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_1" value="PGT" <?php if(isset($select_result['transfer_1']) && $select_result['transfer_9'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_1" value="DISCARD" <?php if(isset($select_result['transfer_1']) && $select_result['transfer_9'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
									<th colspan="4" style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['witness16'])?$select_result['witness16']:""; ?>" name="witness16"  ></th>
									<td colspan="3" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?>" name="witness6"  ></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_2'])?$select_result['nost_2']:""; ?>" name="nost_2"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noew_2'])?$select_result['noew_2']:""; ?>"  name="noew_2"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noei_2'])?$select_result['noei_2']:""; ?>"  name="noei_2"  ></td>
							        <td colspan="6" style="background: #F4B083">
									<input type="checkbox" name="transfer_2" value="TRANSFERRED" <?php if(isset($select_result['transfer_2']) && $select_result['transfer_2'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_2" value="REFROZEN" <?php if(isset($select_result['transfer_2']) && $select_result['transfer_2'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_2" value="PGT" <?php if(isset($select_result['transfer_2']) && $select_result['transfer_2'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_2" value="DISCARD" <?php if(isset($select_result['transfer_2']) && $select_result['transfer_2'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
									<th colspan="4" style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['witness17'])?$select_result['witness17']:""; ?>" name="witness17"  ></th>
									<td colspan="3" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['witness7'])?$select_result['witness7']:""; ?>" name="witness7"  ></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_3'])?$select_result['nost_3']:""; ?>" name="nost_3"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noew_3'])?$select_result['noew_3']:""; ?>"  name="noew_3"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noei_3'])?$select_result['noei_3']:""; ?>"  name="noei_3"  ></td>
							        <td colspan="6" style="background: #F4B083">
									<input type="checkbox" name="transfer_3" value="TRANSFERRED" <?php if(isset($select_result['transfer_3']) && $select_result['transfer_3'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_3" value="REFROZEN" <?php if(isset($select_result['transfer_3']) && $select_result['transfer_3'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_3" value="PGT" <?php if(isset($select_result['transfer_3']) && $select_result['transfer_3'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_3" value="DISCARD" <?php if(isset($select_result['transfer_3']) && $select_result['transfer_3'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
									<th colspan="4" style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['witness18'])?$select_result['witness18']:""; ?>" name="witness18"  ></th>
									<td colspan="3" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['witness8'])?$select_result['witness8']:""; ?>" name="witness8"  ></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_4'])?$select_result['nost_4']:""; ?>" name="nost_4"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noew_4'])?$select_result['noew_4']:""; ?>"  name="noew_4"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noei_4'])?$select_result['noei_4']:""; ?>"  name="noei_4"  ></td>
							        <td colspan="6" style="background: #F4B083">
									<input type="checkbox" name="transfer_4" value="TRANSFERRED" <?php if(isset($select_result['transfer_4']) && $select_result['transfer_4'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_4" value="REFROZEN" <?php if(isset($select_result['transfer_4']) && $select_result['transfer_4'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_4" value="PGT" <?php if(isset($select_result['transfer_4']) && $select_result['transfer_4'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_4" value="DISCARD" <?php if(isset($select_result['transfer_4']) && $select_result['transfer_4'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
									<th colspan="4" style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['witness19'])?$select_result['witness19']:""; ?>" name="witness19"  ></th>
									<td colspan="3" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['witness9'])?$select_result['witness9']:""; ?>" name="witness9"  ></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_5'])?$select_result['nost_5']:""; ?>" name="nost_5"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noew_5'])?$select_result['noew_5']:""; ?>"  name="noew_5"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noei_5'])?$select_result['noei_5']:""; ?>"  name="noei_5"  ></td>
							        <td colspan="6" style="background: #F4B083">
									<input type="checkbox" name="transfer_5" value="TRANSFERRED" <?php if(isset($select_result['transfer_5']) && $select_result['transfer_5'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_5" value="REFROZEN" <?php if(isset($select_result['transfer_5']) && $select_result['transfer_5'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_5" value="PGT" <?php if(isset($select_result['transfer_5']) && $select_result['transfer_5'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_5" value="DISCARD" <?php if(isset($select_result['transfer_5']) && $select_result['transfer_5'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
									<th colspan="4" style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['witness20'])?$select_result['witness20']:""; ?>" name="witness20"  ></th>
									<td colspan="3" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['witness10'])?$select_result['witness10']:""; ?>" name="witness10"  ></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_6'])?$select_result['nost_6']:""; ?>" name="nost_6"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noew_6'])?$select_result['noew_6']:""; ?>"  name="noew_6"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noei_6'])?$select_result['noei_6']:""; ?>"  name="noei_6"  ></td>
							        <td colspan="6" style="background: #F4B083">
									<input type="checkbox" name="transfer_6" value="TRANSFERRED" <?php if(isset($select_result['transfer_6']) && $select_result['transfer_6'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_6" value="REFROZEN" <?php if(isset($select_result['transfer_6']) && $select_result['transfer_6'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_6" value="PGT" <?php if(isset($select_result['transfer_6']) && $select_result['transfer_6'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_6" value="DISCARD" <?php if(isset($select_result['transfer_6']) && $select_result['transfer_6'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
									<th colspan="4" style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['witness21'])?$select_result['witness21']:""; ?>" name="witness21"  ></th>
									<td colspan="3" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['witness11'])?$select_result['witness11']:""; ?>" name="witness11"  ></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_7'])?$select_result['nost_7']:""; ?>" name="nost_7"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noew_7'])?$select_result['noew_7']:""; ?>"  name="noew_7"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noei_7'])?$select_result['noei_7']:""; ?>"  name="noei_7"  ></td>
							        <td colspan="6" style="background: #F4B083">
									<input type="checkbox" name="transfer_7" value="TRANSFERRED" <?php if(isset($select_result['transfer_7']) && $select_result['transfer_7'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_7" value="REFROZEN" <?php if(isset($select_result['transfer_7']) && $select_result['transfer_7'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_7" value="PGT" <?php if(isset($select_result['transfer_7']) && $select_result['transfer_7'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_7" value="DISCARD" <?php if(isset($select_result['transfer_7']) && $select_result['transfer_7'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
									<th colspan="4" style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['witness22'])?$select_result['witness22']:""; ?>" name="witness22"  ></th>
									<td colspan="3" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['witness12'])?$select_result['witness12']:""; ?>" name="witness12"  ></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_8'])?$select_result['nost_8']:""; ?>" name="nost_8"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noew_8'])?$select_result['noew_8']:""; ?>"  name="noew_8"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noei_8'])?$select_result['noei_8']:""; ?>"  name="noei_8"  ></td>
							        <td colspan="6" style="background: #F4B083">
									<input type="checkbox" name="transfer_8" value="TRANSFERRED" <?php if(isset($select_result['transfer_8']) && $select_result['transfer_8'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_8" value="REFROZEN" <?php if(isset($select_result['transfer_8']) && $select_result['transfer_8'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_8" value="PGT" <?php if(isset($select_result['transfer_8']) && $select_result['transfer_8'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_8" value="DISCARD" <?php if(isset($select_result['transfer_8']) && $select_result['transfer_8'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
									<th colspan="4" style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['witness23'])?$select_result['witness23']:""; ?>" name="witness23"  ></th>
									<td colspan="3" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['witness13'])?$select_result['witness13']:""; ?>" name="witness13"  ></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_9'])?$select_result['nost_9']:""; ?>" name="nost_9"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noew_9'])?$select_result['noew_9']:""; ?>"  name="noew_9"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noei_9'])?$select_result['noei_9']:""; ?>"  name="noei_9"  ></td>
							        <td colspan="6" style="background: #F4B083">
									<input type="checkbox" name="transfer_9" value="TRANSFERRED" <?php if(isset($select_result['transfer_9']) && $select_result['transfer_9'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_9" value="REFROZEN" <?php if(isset($select_result['transfer_9']) && $select_result['transfer_9'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_9" value="PGT" <?php if(isset($select_result['transfer_9']) && $select_result['transfer_9'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_9" value="DISCARD" <?php if(isset($select_result['transfer_9']) && $select_result['transfer_9'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
									<th colspan="4" style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['witness24'])?$select_result['witness24']:""; ?>" name="witness24"  ></th>
									<td colspan="3" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['witness14'])?$select_result['witness14']:""; ?>" name="witness14"  ></td>
								</tr>
					        </thead>
					    </table>
					</div>
					<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
				</div>
			</form>
			
			
	<!-- pRINT bUTTON -->		
			
			
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none"> 


<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">EMBRYO THAWED SECOND CYCLE</h3></td>
   </tr>
</table>

<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;" >
     				  <table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">

<td colspan="3" width="34%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?></strong>
</td>
<td colspan="3" width="100%" style="border:1px solid;padding:5px;">
<strong>Patient Name : <?php echo $select_result3['wife_name']; ?> </strong>
</td>
</tr>
<tr>
<td colspan="6" width="33%" style="border:1px solid;padding:5px;">
<strong>IIC ID: <?php echo $patient_id; ?></strong>
</td>


</tr>
	   </table>	

<table class="table table-bordered table-hover mt-2 table-sm red-field">
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">EMBRYO THAWED</th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">FATE OF EMBRYOS</th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">TOTAL NO OF STRAW REMAINING</th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date'])?$select_result['date']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date3'])?$select_result['date3']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date4'])?$select_result['date4']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time'])?$select_result['time']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Diss.Time: <?php echo isset($select_result['time2'])?$select_result['time2']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time3'])?$select_result['time3']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time4'])?$select_result['time4']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb'])?$select_result['emb']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Score Time: <?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs'])?$select_result['hrs']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs2'])?$select_result['hrs2']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Dr: <?php echo isset($select_result['dr'])?$select_result['dr']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb'])?$select_result['emb']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb2'])?$select_result['emb2']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb3'])?$select_result['emb3']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness'])?$select_result['witness']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness2'])?$select_result['witness2']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness3'])?$select_result['witness3']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness4'])?$select_result['witness4']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td style="border:1px solid #cdcdcd;" colspan="1">NO OF STRAW THAWED</td>
									<td style="border:1px solid #cdcdcd;" colspan="1">NO OF EMBRYOS WARMED</td>
									<td style="border:1px solid #cdcdcd;" colspan="1">NO OF EMBRYOS INTACT</td>
									<td style="border:1px solid #cdcdcd;" colspan="6">TRANSFERRED/REFROZEN/PGT/DISCARD</td>
									<td style="border:1px solid #cdcdcd;" colspan="4"></td>
									<td style="border:1px solid #cdcdcd;" colspan="3">REMARKS</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['nost_0'])?$select_result['nost_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noew_0'])?$select_result['noew_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noei_0'])?$select_result['noei_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6">
									<input type="checkbox" name="transfer_0" value="TRANSFERRED" <?php if(isset($select_result['transfer_0']) && $select_result['transfer_0'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_0" value="REFROZEN" <?php if(isset($select_result['transfer_0']) && $select_result['transfer_0'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_0" value="PGT" <?php if(isset($select_result['transfer_0']) && $select_result['transfer_0'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_0" value="DISCARD" <?php if(isset($select_result['transfer_0']) && $select_result['transfer_0'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
							        <td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness15'])?$select_result['witness15']:""; ?></td>
									<td colspan="3" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness5'])?$select_result['witness5']:""; ?></td>
								</tr>
					        </thead>
							<thead>
							<tr>
									<td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['nost_1'])?$select_result['nost_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noew_1'])?$select_result['noew_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noei_1'])?$select_result['noei_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6">
									<input type="checkbox" name="transfer_1" value="TRANSFERRED" <?php if(isset($select_result['transfer_1']) && $select_result['transfer_9'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_1" value="REFROZEN" <?php if(isset($select_result['transfer_1']) && $select_result['transfer_9'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_1" value="PGT" <?php if(isset($select_result['transfer_1']) && $select_result['transfer_9'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_1" value="DISCARD" <?php if(isset($select_result['transfer_1']) && $select_result['transfer_9'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
							        <td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness16'])?$select_result['witness16']:""; ?></td>
									<td colspan="3" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['nost_2'])?$select_result['nost_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noew_2'])?$select_result['noew_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noei_2'])?$select_result['noei_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6">
									<input type="checkbox" name="transfer_2" value="TRANSFERRED" <?php if(isset($select_result['transfer_2']) && $select_result['transfer_2'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_2" value="REFROZEN" <?php if(isset($select_result['transfer_2']) && $select_result['transfer_2'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_2" value="PGT" <?php if(isset($select_result['transfer_2']) && $select_result['transfer_2'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_2" value="DISCARD" <?php if(isset($select_result['transfer_2']) && $select_result['transfer_2'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
							        <td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness17'])?$select_result['witness17']:""; ?></td>
									<td colspan="3" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness7'])?$select_result['witness7']:""; ?></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['nost_3'])?$select_result['nost_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noew_3'])?$select_result['noew_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noei_3'])?$select_result['noei_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6">
									<input type="checkbox" name="transfer_3" value="TRANSFERRED" <?php if(isset($select_result['transfer_3']) && $select_result['transfer_3'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_3" value="REFROZEN" <?php if(isset($select_result['transfer_3']) && $select_result['transfer_3'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_3" value="PGT" <?php if(isset($select_result['transfer_3']) && $select_result['transfer_3'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_3" value="DISCARD" <?php if(isset($select_result['transfer_3']) && $select_result['transfer_3'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
							        <td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness18'])?$select_result['witness18']:""; ?></td>
									<td colspan="3" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness8'])?$select_result['witness8']:""; ?></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['nost_4'])?$select_result['nost_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noew_4'])?$select_result['noew_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noei_4'])?$select_result['noei_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6">
									<input type="checkbox" name="transfer_4" value="TRANSFERRED" <?php if(isset($select_result['transfer_4']) && $select_result['transfer_4'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_4" value="REFROZEN" <?php if(isset($select_result['transfer_4']) && $select_result['transfer_4'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_4" value="PGT" <?php if(isset($select_result['transfer_4']) && $select_result['transfer_4'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_4" value="DISCARD" <?php if(isset($select_result['transfer_4']) && $select_result['transfer_4'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
							        <td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness19'])?$select_result['witness19']:""; ?></td>
									<td colspan="3" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness9'])?$select_result['witness9']:""; ?></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['nost_5'])?$select_result['nost_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noew_5'])?$select_result['noew_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noei_5'])?$select_result['noei_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6">
									<input type="checkbox" name="transfer_5" value="TRANSFERRED" <?php if(isset($select_result['transfer_5']) && $select_result['transfer_5'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_5" value="REFROZEN" <?php if(isset($select_result['transfer_5']) && $select_result['transfer_5'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_5" value="PGT" <?php if(isset($select_result['transfer_5']) && $select_result['transfer_5'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_5" value="DISCARD" <?php if(isset($select_result['transfer_5']) && $select_result['transfer_5'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
							        <td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness20'])?$select_result['witness20']:""; ?></td>
									<td colspan="3" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness10'])?$select_result['witness10']:""; ?></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['nost_6'])?$select_result['nost_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noew_6'])?$select_result['noew_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noei_6'])?$select_result['noei_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6">
									<input type="checkbox" name="transfer_6" value="TRANSFERRED" <?php if(isset($select_result['transfer_6']) && $select_result['transfer_6'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_6" value="REFROZEN" <?php if(isset($select_result['transfer_6']) && $select_result['transfer_6'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_6" value="PGT" <?php if(isset($select_result['transfer_6']) && $select_result['transfer_6'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_6" value="DISCARD" <?php if(isset($select_result['transfer_6']) && $select_result['transfer_6'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
							        <td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness21'])?$select_result['witness21']:""; ?></td>
									<td colspan="3" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness11'])?$select_result['witness11']:""; ?></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['nost_7'])?$select_result['nost_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noew_7'])?$select_result['noew_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noei_7'])?$select_result['noei_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6">
									<input type="checkbox" name="transfer_7" value="TRANSFERRED" <?php if(isset($select_result['transfer_7']) && $select_result['transfer_7'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_7" value="REFROZEN" <?php if(isset($select_result['transfer_7']) && $select_result['transfer_7'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_7" value="PGT" <?php if(isset($select_result['transfer_7']) && $select_result['transfer_7'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_7" value="DISCARD" <?php if(isset($select_result['transfer_7']) && $select_result['transfer_7'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
							        <td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness22'])?$select_result['witness22']:""; ?></td>
									<td colspan="3" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness12'])?$select_result['witness12']:""; ?></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['nost_8'])?$select_result['nost_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noew_8'])?$select_result['noew_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noei_8'])?$select_result['noei_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6">
									<input type="checkbox" name="transfer_8" value="TRANSFERRED" <?php if(isset($select_result['transfer_8']) && $select_result['transfer_8'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_8" value="REFROZEN" <?php if(isset($select_result['transfer_8']) && $select_result['transfer_8'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_8" value="PGT" <?php if(isset($select_result['transfer_8']) && $select_result['transfer_8'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_8" value="DISCARD" <?php if(isset($select_result['transfer_8']) && $select_result['transfer_8'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
							        <td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness23'])?$select_result['witness23']:""; ?></td>
									<td colspan="3" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness13'])?$select_result['witness13']:""; ?></td>
								</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['nost_9'])?$select_result['nost_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noew_9'])?$select_result['noew_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="1"><?php echo isset($select_result['noei_9'])?$select_result['noei_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6">
									<input type="checkbox" name="transfer_9" value="TRANSFERRED" <?php if(isset($select_result['transfer_9']) && $select_result['transfer_9'] == "TRANSFERRED"){echo 'checked="checked"'; }?>> TRANSFERRED<br>
									<input type="checkbox" name="transfer_9" value="REFROZEN" <?php if(isset($select_result['transfer_9']) && $select_result['transfer_9'] == "REFROZEN"){echo 'checked="checked"'; }?>> REFROZEN<br>
									<input type="checkbox" name="transfer_9" value="PGT" <?php if(isset($select_result['transfer_9']) && $select_result['transfer_9'] == "PGT"){echo 'checked="checked"'; }?>> PGT<br>
									<input type="checkbox" name="transfer_9" value="DISCARD" <?php if(isset($select_result['transfer_9']) && $select_result['transfer_9'] == "DISCARD"){echo 'checked="checked"'; }?>>DISCARD
							        </td>
							        <td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness24'])?$select_result['witness24']:""; ?></td>
									<td colspan="3" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['witness14'])?$select_result['witness14']:""; ?></td>
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
 function printtable() 
{    //alert();
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
			