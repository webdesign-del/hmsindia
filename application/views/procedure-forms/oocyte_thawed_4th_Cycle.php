<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
		$select_query = "SELECT * FROM `hms_oocyte_thawing` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_oocyte_thawing` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_oocyte_thawing SET ";
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
    $select_query = "SELECT * FROM `hms_oocyte_thawing` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
  <input type="hidden" value="Fourth Cycle" name="type"> 
    			<div class="container2 red-field form mt-5 mb-5">
				<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OOCYTE THAWING FOURTH CYCLE</h3></td>
   </tr>
</table>

<table class="table table-bordered table-hover mt-2 table-sm red-field tableMg" style="width:100%; border:1px solid #cdcdcd;" >
     				  <table width="100%" class="vb45rt">
<tbody>
<tr style="background: #b3b9b7;">

<td colspan="3" width="34%" style="border:1px solid;padding:5px;">
<strong>UHID : <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?> </strong>
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
					        		<th colspan="3" style="background: #FFC000">OOCYTE THAWED</th>
					        		<th colspan="6" style="background: #F4B083">FATE OF OOCYTES</th>
					        		<th colspan="4" style="background: #C5E0B3">TOTAL NO OF STRAW REMAINING</th>
					        		<th colspan="2" style="background: #8EAADB">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Date: <input type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"  name="date"></th>
					        		<th colspan="6" style="background: #F4B083">Date: <input type="date" value="<?php echo isset($select_result['date2'])?$select_result['date2']:""; ?>"  name="date2"></th>
					        		<th colspan="4" style="background: #C5E0B3">Date: <input type="date" value="<?php echo isset($select_result['date4'])?$select_result['date4']:""; ?>"  name="date4"></th>
					        		<th colspan="4" style="background: #8EAADB">Date: <input type="date" value="<?php echo isset($select_result['date3'])?$select_result['date3']:""; ?>"  name="date3"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Time: <input type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"  name="time"></th>
					        		<th colspan="6" style="background: #F4B083">Diss.Time: <input type="time" value="<?php echo isset($select_result['time2'])?$select_result['time2']:""; ?>"  name="time2"></th>
					        		<th colspan="4" style="background: #C5E0B3">Time: <input type="time" value="<?php echo isset($select_result['time3'])?$select_result['time3']:""; ?>"  name="time3"></th>
					        		<th colspan="4" style="background: #8EAADB">Time: <input type="time" value="<?php echo isset($select_result['time3'])?$select_result['time3']:""; ?>"  name="time3"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Emb: <input type="text" value="<?php echo isset($select_result['emb'])?$select_result['emb']:""; ?>"  maxlength="20" name="emb"  ></th>
					        		<th colspan="6" style="background: #F4B083">Score Time: <input type="time" value="<?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?>"  name="score_time" ></th>
					        		<th colspan="4" style="background: #C5E0B3">Hrs: <input type="time" value="<?php echo isset($select_result['hrs'])?$select_result['hrs']:""; ?>"  name="hrs"></th>
					        		<th colspan="4" style="background: #8EAADB">Hrs: <input type="time" value="<?php echo isset($select_result['hrs'])?$select_result['hrs']:""; ?>"  name="hrs"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Dr: <input type="text" value="<?php echo isset($select_result['dr'])?$select_result['dr']:""; ?>"  maxlength="20" name="dr"  ></th>
					        		<th colspan="6" style="background: #F4B083">Emb: <input type="text" value="<?php echo isset($select_result['emb2'])?$select_result['emb2']:""; ?>"  maxlength="20" name="emb2"  ></th>
					        		<th colspan="4" style="background: #C5E0B3">Emb: <input type="text" value="<?php echo isset($select_result['emb2'])?$select_result['emb2']:""; ?>"  maxlength="20" name="emb2"  ></th>
					        		<th colspan="4" style="background: #8EAADB">Emb: <input type="text" value="<?php echo isset($select_result['emb3'])?$select_result['emb3']:""; ?>"  maxlength="20" name="emb3"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Witness: <input type="text" value="<?php echo isset($select_result['witness'])?$select_result['witness']:""; ?>"  maxlength="20" name="witness"  ></th>
					        		<th colspan="6" style="background: #F4B083">Witness: <input type="text" value="<?php echo isset($select_result['witness2'])?$select_result['witness2']:""; ?>"  maxlength="20" name="witness2"  ></th>
					        		<th colspan="4" style="background: #C5E0B3">Witness: <input type="text" value="<?php echo isset($select_result['witness3'])?$select_result['witness3']:""; ?>"  maxlength="20" name="witness3"></th>
					        		<th colspan="4" style="background: #8EAADB">Witness: <input type="text" value="<?php echo isset($select_result['witness3'])?$select_result['witness3']:""; ?>"  maxlength="20" name="witness3"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td style="background: #FFC000">NO OF STRAW THAWED</td>
									<td style="background: #FFC000">no of oocytes warmed</td>
									<td style="background: #FFC000">no of oocytes intact</td>
									<td style="background: #F4B083" colspan="6">ICSI/DISCARD</td>
									<td style="background: #C5E0B3" colspan="4"></td>
									<td style="background: #8EAADB" colspan="2"></td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_0'])?$select_result['nost_0']:""; ?>"  maxlength="20" name="nost_0"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_0'])?$select_result['noow_0']:""; ?>"  maxlength="20" name="noow_0"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_0'])?$select_result['nooi_0']:""; ?>"  maxlength="20" name="nooi_0"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"  maxlength="20" name="icsi_0"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness4'])?$select_result['witness4']:""; ?>"  maxlength="20" name="witness4"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_1'])?$select_result['nost_1']:""; ?>"  maxlength="20" name="nost_1"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_1'])?$select_result['noow_1']:""; ?>"  maxlength="20" name="noow_1"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_1'])?$select_result['nooi_1']:""; ?>"  maxlength="20" name="nooi_1"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_1'])?$select_result['icsi_1']:""; ?>"  maxlength="20" name="icsi_1"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness5'])?$select_result['witness5']:""; ?>"  maxlength="20" name="witness5"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_2'])?$select_result['nost_2']:""; ?>"  maxlength="20" name="nost_2"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_2'])?$select_result['noow_2']:""; ?>"  maxlength="20" name="noow_2"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_2'])?$select_result['nooi_2']:""; ?>"  maxlength="20" name="nooi_2"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_2'])?$select_result['icsi_2']:""; ?>"  maxlength="20" name="icsi_2"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?>"  maxlength="20" name="witness6"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_3'])?$select_result['nost_3']:""; ?>"  maxlength="20" name="nost_3"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_3'])?$select_result['noow_3']:""; ?>"  maxlength="20" name="noow_3"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_3'])?$select_result['nooi_3']:""; ?>"  maxlength="20" name="nooi_3"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_3'])?$select_result['icsi_3']:""; ?>"  maxlength="20" name="icsi_3"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness7'])?$select_result['witness7']:""; ?>"  maxlength="20" name="witness7"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_4'])?$select_result['nost_4']:""; ?>"  maxlength="20" name="nost_4"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_4'])?$select_result['noow_4']:""; ?>"  maxlength="20" name="noow_4"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_4'])?$select_result['nooi_4']:""; ?>"  maxlength="20" name="nooi_4"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_4'])?$select_result['icsi_4']:""; ?>"  maxlength="20" name="icsi_4"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness8'])?$select_result['witness8']:""; ?>"  maxlength="20" name="witness8"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_5'])?$select_result['nost_5']:""; ?>"  maxlength="20" name="nost_5"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_5'])?$select_result['noow_5']:""; ?>"  maxlength="20" name="noow_5"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_5'])?$select_result['nooi_5']:""; ?>"  maxlength="20" name="nooi_5"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_5'])?$select_result['icsi_5']:""; ?>"  maxlength="20" name="icsi_5"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness9'])?$select_result['witness9']:""; ?>"  maxlength="20" name="witness9"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_6'])?$select_result['nost_6']:""; ?>"  maxlength="20" name="nost_6"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_6'])?$select_result['noow_6']:""; ?>"  maxlength="20" name="noow_6"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_6'])?$select_result['nooi_6']:""; ?>"  maxlength="20" name="nooi_6"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_6'])?$select_result['icsi_6']:""; ?>"  maxlength="20" name="icsi_6"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness10'])?$select_result['witness10']:""; ?>"  maxlength="20" name="witness10"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_7'])?$select_result['nost_7']:""; ?>"  maxlength="20" name="nost_7"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_7'])?$select_result['noow_7']:""; ?>"  maxlength="20" name="noow_7"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_7'])?$select_result['nooi_7']:""; ?>"  maxlength="20" name="nooi_7"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_7'])?$select_result['icsi_7']:""; ?>"  maxlength="20" name="icsi_7"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness11'])?$select_result['witness11']:""; ?>"  maxlength="20" name="witness11"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_8'])?$select_result['nost_8']:""; ?>"  maxlength="20" name="nost_8"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_8'])?$select_result['noow_8']:""; ?>"  maxlength="20" name="noow_8"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_8'])?$select_result['nooi_8']:""; ?>"  maxlength="20" name="nooi_8"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_8'])?$select_result['icsi_8']:""; ?>"  maxlength="20" name="icsi_8"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness12'])?$select_result['witness12']:""; ?>"  maxlength="20" name="witness12"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_9'])?$select_result['nost_9']:""; ?>"  maxlength="20" name="nost_9"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_9'])?$select_result['noow_9']:""; ?>"  maxlength="20" name="noow_9"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_9'])?$select_result['nooi_9']:""; ?>"  maxlength="20" name="nooi_9"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_9'])?$select_result['icsi_9']:""; ?>"  maxlength="20" name="icsi_9"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness13'])?$select_result['witness13']:""; ?>"  maxlength="20" name="witness13"  ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nost_10'])?$select_result['nost_10']:""; ?>"  maxlength="20" name="nost_10"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['noow_10'])?$select_result['noow_10']:""; ?>"  maxlength="20" name="noow_10"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['nooi_10'])?$select_result['nooi_10']:""; ?>"  maxlength="20" name="nooi_10"  ></td>
							        <td style="background: #F4B083" colspan="6"><input type="text" value="<?php echo isset($select_result['icsi_10'])?$select_result['icsi_10']:""; ?>"  maxlength="20" name="icsi_10"  ></td>
							        <td style="background: #C5E0B3" colspan="4"><input type="text" name="transfer" value="<?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?>"></td>
									<td style="background: #8EAADB" colspan="2"><input type="text" value="<?php echo isset($select_result['witness14'])?$select_result['witness14']:""; ?>"  maxlength="20" name="witness14"  ></td>
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
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OOCYTE THAWING FOURTH CYCLE</h3></td>
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
					        		<th colspan="3" style="border:1px solid #cdcdcd;">OOCYTE THAWED</th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">FATE OF OOCYTES</th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">TOTAL NO OF STRAW REMAINING</th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date'])?$select_result['date']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date3'])?$select_result['date3']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time'])?$select_result['time']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Diss.Time: <?php echo isset($select_result['time2'])?$select_result['time2']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time3'])?$select_result['time3']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time3'])?$select_result['time3']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb'])?$select_result['emb']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Score Time: <?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs'])?$select_result['hrs']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs'])?$select_result['hrs']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Dr: <?php echo isset($select_result['dr'])?$select_result['dr']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb2'])?$select_result['emb2']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb3'])?$select_result['emb3']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb3'])?$select_result['emb3']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness'])?$select_result['witness']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness2'])?$select_result['witness2']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness3'])?$select_result['witness3']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness3'])?$select_result['witness3']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td style="border:1px solid #cdcdcd;">NO OF STRAW THAWED</td>
									<td style="border:1px solid #cdcdcd;">No of oocytes warmed</td>
									<td style="border:1px solid #cdcdcd;">No of oocytes intact</td>
									<td style="border:1px solid #cdcdcd;" colspan="6">ICSI/DISCARD</td>
									<td style="border:1px solid #cdcdcd;" colspan="4"></td>
									<td style="border:1px solid #cdcdcd;" colspan="2">REMARKS</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_0'])?$select_result['nost_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_0'])?$select_result['noow_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_0'])?$select_result['nooi_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6"><?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['icsi_0'])?$select_result['icsi_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness4'])?$select_result['witness4']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_1'])?$select_result['nost_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_1'])?$select_result['noow_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_1'])?$select_result['nooi_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"colspan="6"><?php echo isset($select_result['icsi_1'])?$select_result['icsi_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['witness5'])?$select_result['witness5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness5'])?$select_result['witness5']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_2'])?$select_result['nost_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_2'])?$select_result['noow_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_2'])?$select_result['nooi_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6"><?php echo isset($select_result['icsi_2'])?$select_result['icsi_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_3'])?$select_result['nost_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_3'])?$select_result['noow_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_3'])?$select_result['nooi_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6"><?php echo isset($select_result['icsi_3'])?$select_result['icsi_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['icsi_4'])?$select_result['icsi_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness7'])?$select_result['witness7']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_4'])?$select_result['nost_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_4'])?$select_result['noow_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_4'])?$select_result['nooi_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6"><?php echo isset($select_result['icsi_4'])?$select_result['icsi_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness8'])?$select_result['witness8']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_5'])?$select_result['nost_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_5'])?$select_result['noow_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_5'])?$select_result['nooi_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6"><?php echo isset($select_result['icsi_5'])?$select_result['icsi_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness9'])?$select_result['witness9']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_6'])?$select_result['nost_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_6'])?$select_result['noow_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_6'])?$select_result['nooi_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6"><?php echo isset($select_result['icsi_6'])?$select_result['icsi_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness10'])?$select_result['witness10']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_7'])?$select_result['nost_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_7'])?$select_result['noow_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_7'])?$select_result['nooi_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6"><?php echo isset($select_result['icsi_7'])?$select_result['icsi_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness11'])?$select_result['witness11']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_8'])?$select_result['nost_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_8'])?$select_result['noow_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_8'])?$select_result['nooi_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6"><?php echo isset($select_result['icsi_8'])?$select_result['icsi_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness12'])?$select_result['witness12']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_9'])?$select_result['nost_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_9'])?$select_result['noow_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_9'])?$select_result['nooi_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6"><?php echo isset($select_result['icsi_9'])?$select_result['icsi_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness13'])?$select_result['witness13']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['nost_10'])?$select_result['nost_10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['noow_10'])?$select_result['noow_10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nooi_10'])?$select_result['nooi_10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="6"><?php echo isset($select_result['icsi_10'])?$select_result['icsi_10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="4"><?php echo isset($select_result['witness6'])?$select_result['witness6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;" colspan="2"><?php echo isset($select_result['witness14'])?$select_result['witness14']:""; ?></td>
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
			