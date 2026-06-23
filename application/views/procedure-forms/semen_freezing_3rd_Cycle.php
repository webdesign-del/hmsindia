<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
      
        $select_query = "SELECT * FROM `hms_semen_freezing` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_semen_freezing` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_semen_freezing SET ";
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
    $select_query = "SELECT * FROM `hms_semen_freezing` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
	<input type="hidden" value="Third Cycle" name="type">  
    			<div class="container2 red-field form mt-5 mb-5">
				<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">SEMEN FREEZING THIRD CYCLE</h3></td>
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
					        		<th colspan="3" style="background: #FFC000">SEMEN FREEZING</th>
					        		<th colspan="6" style="background: #F4B083">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Date: <input type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"  name="date"></th>
					        		<th colspan="6" style="background: #F4B083">Date: <input type="date" value="<?php echo isset($select_result['date1'])?$select_result['date1']:""; ?>"  name="date1"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Time: <input type="time" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"  name="time"></th>
					        		<th colspan="6" style="background: #F4B083">Diss.Time: <input type="time" value="<?php echo isset($select_result['time1'])?$select_result['time1']:""; ?>"  name="time1"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Emb: <input type="text" value="<?php echo isset($select_result['emb'])?$select_result['emb']:""; ?>" name="emb"  ></th>
					        		<th colspan="6" style="background: #F4B083">Score Time: <input type="time" value="<?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?>"  name="score_time" ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Dr: <input type="text" value="<?php echo isset($select_result['dr'])?$select_result['dr']:""; ?>"  name="dr"  ></th>
					        		<th colspan="6" style="background: #F4B083">Emb: <input type="text" value="<?php echo isset($select_result['emb1'])?$select_result['emb1']:""; ?>"   name="emb1"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Witness: <input type="text" value="<?php echo isset($select_result['witness1'])?$select_result['witness1']:""; ?>" name="witness1"  ></th>
					        		<th colspan="6" style="background: #F4B083">Witness: <input type="text" value="<?php echo isset($select_result['witness2'])?$select_result['witness2']:""; ?>" name="witness2"  ></th>
					        	</tr>
					        </thead>
							<thead>
					        	<tr>
					        		<th colspan="9" style="background: #FFC000">TOTAL NO OF VIALS FROZEN: <input type="text" value="<?php echo isset($select_result['total_vials_frozen'])?$select_result['total_vials_frozen']:""; ?>" name="total_vials_frozen"  ></th>
								</tr>
							</thead>
					        <thead>
					        	<tr>
					        		<td style="background: #FFC000">NO OF VIALS FROZEN</td>
									<td style="background: #FFC000">MEDIA</td>
									<td style="background: #FFC000">HOLDER NO</td>
									<td style="background: #F4B083">POSITION</td>
									<td style="background: #F4B083">DEWAR</td>
									<td style="background: #F4B083">STORAGE RENEWAL DATE</td>
									<td style="background: #F4B083">REMARKS</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_0'])?$select_result['no_0']:""; ?>" name="no_0"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_0'])?$select_result['media_0']:""; ?>" name="media_0"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_0'])?$select_result['hn_0']:""; ?>" name="hn_0"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_0'])?$select_result['pn_0']:""; ?>" name="pn_0"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_0'])?$select_result['dewar_0']:""; ?>" name="dewar_0"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_0'])?$select_result['srd_0']:""; ?>" name="srd_0"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_0'])?$select_result['remarks_0']:""; ?>" name="remarks_0" ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_1'])?$select_result['no_1']:""; ?>" name="no_1"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_1'])?$select_result['media_1']:""; ?>"  name="media_1"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_1'])?$select_result['hn_1']:""; ?>" name="hn_1"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_1'])?$select_result['pn_1']:""; ?>" name="pn_1"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_1'])?$select_result['dewar_1']:""; ?>" name="dewar_1"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_1'])?$select_result['srd_1']:""; ?>" name="srd_1"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_1'])?$select_result['remarks_1']:""; ?>" name="remarks_1" ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_2'])?$select_result['no_1']:""; ?>" name="no_1"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_2'])?$select_result['media_2']:""; ?>"  name="media_2"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_2'])?$select_result['hn_2']:""; ?>" name="hn_2"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_2'])?$select_result['pn_2']:""; ?>" name="pn_2"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_2'])?$select_result['dewar_2']:""; ?>" name="dewar_2"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_2'])?$select_result['srd_2']:""; ?>" name="srd_2"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_2'])?$select_result['remarks_2']:""; ?>" name="remarks_2" ></td>
							    </tr>
							</thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_3'])?$select_result['no_3']:""; ?>" name="no_1"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_3'])?$select_result['media_3']:""; ?>"  name="media_2"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_3'])?$select_result['hn_3']:""; ?>" name="hn_2"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_3'])?$select_result['pn_3']:""; ?>" name="pn_2"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_3'])?$select_result['dewar_3']:""; ?>" name="dewar_2"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_3'])?$select_result['srd_3']:""; ?>" name="srd_2"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_3'])?$select_result['remarks_3']:""; ?>" name="remarks_2" ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_4'])?$select_result['no_4']:""; ?>" name="no_4"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_4'])?$select_result['media_4']:""; ?>"  name="media_4"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_4'])?$select_result['hn_4']:""; ?>" name="hn_4"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_4'])?$select_result['pn_4']:""; ?>" name="pn_4"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_4'])?$select_result['dewar_4']:""; ?>" name="dewar_4"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_4'])?$select_result['srd_4']:""; ?>" name="srd_4"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_4'])?$select_result['remarks_4']:""; ?>" name="remarks_4" ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_5'])?$select_result['no_5']:""; ?>" name="no_5"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_5'])?$select_result['media_5']:""; ?>"  name="media_5"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_5'])?$select_result['hn_5']:""; ?>" name="hn_5"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_5'])?$select_result['pn_5']:""; ?>" name="pn_5"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_5'])?$select_result['dewar_5']:""; ?>" name="dewar_5"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_5'])?$select_result['srd_5']:""; ?>" name="srd_5"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_5'])?$select_result['remarks_5']:""; ?>" name="remarks_5" ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_6'])?$select_result['no_6']:""; ?>" name="no_6"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_6'])?$select_result['media_6']:""; ?>"  name="media_6"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_6'])?$select_result['hn_6']:""; ?>" name="hn_6"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_6'])?$select_result['pn_6']:""; ?>" name="pn_6"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_6'])?$select_result['dewar_6']:""; ?>" name="dewar_6"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_6'])?$select_result['srd_6']:""; ?>" name="srd_6"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_6'])?$select_result['remarks_6']:""; ?>" name="remarks_6" ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_7'])?$select_result['no_7']:""; ?>" name="no_7"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_7'])?$select_result['media_7']:""; ?>"  name="media_7"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_7'])?$select_result['hn_7']:""; ?>" name="hn_7"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_7'])?$select_result['pn_7']:""; ?>" name="pn_7"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_7'])?$select_result['dewar_7']:""; ?>" name="dewar_7"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_7'])?$select_result['srd_7']:""; ?>" name="srd_7"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_7'])?$select_result['remarks_7']:""; ?>" name="remarks_7" ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_8'])?$select_result['no_8']:""; ?>" name="no_8"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_8'])?$select_result['media_8']:""; ?>"  name="media_8"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_8'])?$select_result['hn_8']:""; ?>" name="hn_8"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_8'])?$select_result['pn_8']:""; ?>" name="pn_8"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_8'])?$select_result['dewar_8']:""; ?>" name="dewar_8"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_8'])?$select_result['srd_8']:""; ?>" name="srd_8"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_8'])?$select_result['remarks_8']:""; ?>" name="remarks_8" ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_9'])?$select_result['no_9']:""; ?>" name="no_9"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_9'])?$select_result['media_9']:""; ?>"  name="media_9"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_9'])?$select_result['hn_9']:""; ?>" name="hn_9"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_9'])?$select_result['pn_9']:""; ?>" name="pn_9"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_9'])?$select_result['dewar_9']:""; ?>" name="dewar_9"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_9'])?$select_result['srd_9']:""; ?>" name="srd_9"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_9'])?$select_result['remarks_9']:""; ?>" name="remarks_9" ></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_10'])?$select_result['no_10']:""; ?>" name="no_10"></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['media_10'])?$select_result['media_10']:""; ?>"  name="media_10"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['hn_10'])?$select_result['hn_10']:""; ?>" name="hn_10"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['pn_10'])?$select_result['pn_10']:""; ?>" name="pn_10"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_10'])?$select_result['dewar_10']:""; ?>" name="dewar_10"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['srd_10'])?$select_result['srd_10']:""; ?>" name="srd_10"></td>
									<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_10'])?$select_result['remarks_10']:""; ?>" name="remarks_10" ></td>
							    </tr>
					        </thead>
					    </table>
					</div>
					<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
				</div>
			</form>
			
			
	<!-- pRINT bUTTON -->		
			
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">SEMEN FREEZING THIRD CYCLE</h3></td>
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
					        		<th colspan="3" style="border:1px solid #cdcdcd;">SEMEN FREEZING</th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date'])?$select_result['date']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date1'])?$select_result['date1']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time'])?$select_result['time']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Diss.Time: <?php echo isset($select_result['time1'])?$select_result['time1']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb'])?$select_result['emb']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Score Time: <?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Dr: <?php echo isset($select_result['dr'])?$select_result['dr']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb1'])?$select_result['emb1']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness1'])?$select_result['witness1']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness2'])?$select_result['witness2']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td style="border:1px solid #cdcdcd;height:25px;">NO OF VIALS FROZEN</td>
									<td style="border:1px solid #cdcdcd;">MEDIA</td>
									<td style="border:1px solid #cdcdcd;">HOLDER NO</td>
									<td style="border:1px solid #cdcdcd;">POSITION</td>
									<td style="border:1px solid #cdcdcd;">DEWAR</td>
									<td style="border:1px solid #cdcdcd;">STORAGE RENEWAL DATE</td>
									<td style="border:1px solid #cdcdcd;">REMARKS</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_0'])?$select_result['no_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_0'])?$select_result['media_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_0'])?$select_result['hn_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_0'])?$select_result['pn_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_0'])?$select_result['dewar_0']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_0'])?$select_result['srd_0']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_0'])?$select_result['remarks_0']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_1'])?$select_result['no_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_1'])?$select_result['media_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_1'])?$select_result['hn_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_1'])?$select_result['pn_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_1'])?$select_result['dewar_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_1'])?$select_result['srd_1']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_1'])?$select_result['remarks_1']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_2'])?$select_result['no_1']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_2'])?$select_result['media_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_2'])?$select_result['hn_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_2'])?$select_result['pn_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_2'])?$select_result['dewar_2']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_2'])?$select_result['srd_2']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_2'])?$select_result['remarks_2']:""; ?></td>
							    </tr>
							</thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_3'])?$select_result['no_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_3'])?$select_result['media_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_3'])?$select_result['hn_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_3'])?$select_result['pn_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_3'])?$select_result['dewar_3']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_3'])?$select_result['srd_3']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_3'])?$select_result['remarks_3']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_4'])?$select_result['no_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_4'])?$select_result['media_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_4'])?$select_result['hn_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_4'])?$select_result['pn_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_4'])?$select_result['dewar_4']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_4'])?$select_result['srd_4']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_4'])?$select_result['remarks_4']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_5'])?$select_result['no_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_5'])?$select_result['media_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_5'])?$select_result['hn_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_5'])?$select_result['pn_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_5'])?$select_result['dewar_5']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_5'])?$select_result['srd_5']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_5'])?$select_result['remarks_5']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_6'])?$select_result['no_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_6'])?$select_result['media_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_6'])?$select_result['hn_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_6'])?$select_result['pn_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_6'])?$select_result['dewar_6']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_6'])?$select_result['srd_6']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_6'])?$select_result['remarks_6']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_7'])?$select_result['no_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_7'])?$select_result['media_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_7'])?$select_result['hn_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_7'])?$select_result['pn_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_7'])?$select_result['dewar_7']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_7'])?$select_result['srd_7']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_7'])?$select_result['remarks_7']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_8'])?$select_result['no_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_8'])?$select_result['media_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_8'])?$select_result['hn_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_8'])?$select_result['pn_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_8'])?$select_result['dewar_8']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_8'])?$select_result['srd_8']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_8'])?$select_result['remarks_8']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_9'])?$select_result['no_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_9'])?$select_result['media_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_9'])?$select_result['hn_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_9'])?$select_result['pn_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_9'])?$select_result['dewar_9']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_9'])?$select_result['srd_9']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_9'])?$select_result['remarks_9']:""; ?></td>
							    </tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid #cdcdcd;height:25px;"><?php echo isset($select_result['no_10'])?$select_result['no_10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['media_10'])?$select_result['media_10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hn_10'])?$select_result['hn_10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn_10'])?$select_result['pn_10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['dewar_10'])?$select_result['dewar_10']:""; ?></td>
							        <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['srd_10'])?$select_result['srd_10']:""; ?></td>
									<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks_10'])?$select_result['remarks_10']:""; ?></td>
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