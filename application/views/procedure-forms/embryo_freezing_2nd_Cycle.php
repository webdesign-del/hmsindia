<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        $select_query = "SELECT * FROM `hms_embryo_freezing` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `hms_embryo_freezing` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE hms_embryo_freezing SET ";
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
    $select_query = "SELECT * FROM `hms_embryo_freezing` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query);
	
	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$select_center_sql = "SELECT * FROM `hms_centers` WHERE center_number='" .$select_result4['appoitment_for']."'";
	$result_center = run_select_query($select_center_sql);
	
	$select_query_pro = "SELECT * FROM `hms_patient_procedure` WHERE receipt_number='" .$receipt_number."'";
	$select_result_pro = run_select_query($select_query_pro);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result_pro['billing_at']."'";
	$select_result5 = run_select_query($sql5);

	$sql_procedure = "Select * from ".$this->config->item('db_prefix')."procedures where ID='".$procedure_id."'";
	$result_pro = run_select_query($sql_procedure);	
	
	$payload = json_encode([
				"patient_id" => $patient_id,
				"receipt_number" => $receipt_number,
				"wife_name" => $select_result3['wife_name'],
				"payment_freezing" => $select_result_pro['fees'],
				"spoke" => $select_result5['center_name'],
				"hub" => $result_center['center_name'],
				"date" => $select_result['date'],
				"no_of_straw_0" => $select_result['no_of_embryos_frozen'],
				"procedure_id" => $result_pro['code'],
				"lead_id" => $select_result4['crm_id']
		]);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://staging.flertility.in/lead/embryo-freezing/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		
		
	
?>

<form enctype='multipart/form-data'  class ="searchform" name="form" action="" method="POST">
<input type="hidden" value="<?php echo $updated_by; ?>" class="form" name="updated_by">
<input type="hidden" value="<?php echo $updated_type; ?>" class="form" name="updated_type">
<input type="hidden" value="<?php echo $updated_at; ?>" class="form" name="updated_at">
  <input type="hidden" value="<?php echo $procedure_id; ?>" class="form" name="procedure_id">
  <input type="hidden" value="<?php echo $patient_id; ?>" class="form" name="patient_id">
  <input type="hidden" value="<?php echo $receipt_number; ?>" class="form" name="receipt_number">
  <input type="hidden" value="Second Cycle" class="form" name="type">
  <input type="hidden" value="pending" name="status"> 
    			<div class="container2 red-field form mt-5 mb-5">
    			<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">EMBRYO FREEZING SECOND CYCLE</h3></td>
   </tr>
</table>
 <table width="100%" class="vb45rt">
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
 					<div class='table-responsive2'>
    					<table class="table table-bordered table-hover mt-2 table-sm red-field">
					        <thead>
					        	<tr>
					        		<th colspan="4" style="background: #FFC000">EMBRYO FREEZING</th>
					        		<th colspan="6" style="background: #F4B083">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="background: #FFC000">Date: <input type="date" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"  name="date"></th>
					        		<th colspan="6" style="background: #F4B083">Date: <input type="date" value="<?php echo isset($select_result['date2'])?$select_result['date2']:""; ?>"  name="date2"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="background: #FFC000">Time: <input type="time" value="<?php echo isset($select_result['time0'])?$select_result['time0']:""; ?>"  name="time0"></th>
					        		<th colspan="6" style="background: #F4B083">Diss.Time: <input type="time" value="<?php echo isset($select_result['time1'])?$select_result['time1']:""; ?>"  name="time1"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="background: #FFC000">Emb: <input type="text" value="<?php echo isset($select_result['emb'])?$select_result['emb']:""; ?>"  maxlength="20" name="emb"  ></th>
					        		<th colspan="6" style="background: #F4B083">Score Time: <input type="time" value="<?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?>"  name="score_time" ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="background: #FFC000">Dr: <input type="text" value="<?php echo isset($select_result['dr'])?$select_result['dr']:""; ?>"  maxlength="20" name="dr"  ></th>
					        		<th colspan="6" style="background: #F4B083">Emb: <input type="text" value="<?php echo isset($select_result['emb0'])?$select_result['emb0']:""; ?>"  maxlength="20" name="emb0"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="background: #FFC000">Witness: <input type="text" value="<?php echo isset($select_result['witness0'])?$select_result['witness0']:""; ?>"  maxlength="20" name="witness0"  ></th>
					        		<th colspan="6" style="background: #F4B083">Witness: <input type="text" value="<?php echo isset($select_result['witness1'])?$select_result['witness1']:""; ?>"  maxlength="20" name="witness1"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="padding: 0; background: #FFC000">
					        			<table style="width: 100%;">
					        				<tr>
					        					<td>TOTAL NO OF EMBRYOS FROZEN</td>
					        					<td><input type="number" value="<?php echo isset($select_result['no_of_embryos_frozen'])?$select_result['no_of_embryos_frozen']:""; ?>"  min="0" name="no_of_embryos_frozen"></td>
					        					<td><input type="number" value="<?php echo isset($select_result['no_of_embryos_frozen1'])?$select_result['no_of_embryos_frozen1']:""; ?>"  min="0" name="no_of_embryos_frozen1"></td>
					        				</tr>
					        			</table>
					        		</th>
					        		<th colspan="6" style="padding: 0; background: #F4B083"></th>
					        		
								</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td style="background: #FFC000">No</td>
									<td style="background: #FFC000">NO OF STRAW</td>
									<td style="background: #FFC000">EMBRYOS FROZEN IN THAT STRAW WITH CELL STAGE AND GRADING</td>
									<td style="background: #FFC000">STRAW COLOUR</td>
									<td style="background: #FFC000">VISOTUBE</td>
									<td style="background: #FFC000">GOBLET</td>
									<td style="background: #FFC000">DEWAR</td>
									<td style="background: #FFC000">MEDIA USED</td>
									<td style="background: #FFC000">STORAGE RENEWAL DATE</td>
									<td style="background: #FFC000">Remarks</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_0'])?$select_result['no_0']:""; ?>"  min="0" name="no_0">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw_0'])?$select_result['no_of_straw_0']:""; ?>"  name="no_of_straw_0"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['embryos_frozen_0'])?$select_result['embryos_frozen_0']:""; ?>"  name="embryos_frozen_0"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_color_0'])?$select_result['straw_color_0']:""; ?>"  min="0" name="straw_color_0"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['visotube_0'])?$select_result['visotube_0']:""; ?>"  min="0" name="visotube_0"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['goblet_0'])?$select_result['goblet_0']:""; ?>"  min="0" name="goblet_0"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_0'])?$select_result['dewar_0']:""; ?>"  min="0" name="dewar_0"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['media_used_0'])?$select_result['media_used_0']:""; ?>"  min="0" name="media_used_0"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['date3'])?$select_result['date3']:""; ?>"  maxlength="20" name="date3"  ></td>
							    	<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_0'])?$select_result['remarks_0']:""; ?>"  maxlength="20" name="remarks_0"  ></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_1'])?$select_result['no_1']:""; ?>"  min="0" name="no_1">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw_1'])?$select_result['no_of_straw_1']:""; ?>"  name="no_of_straw_1"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['embryos_frozen_1'])?$select_result['embryos_frozen_1']:""; ?>"  name="embryos_frozen_1"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_color_1'])?$select_result['straw_color_1']:""; ?>"  min="0" name="straw_color_1"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['visotube_1'])?$select_result['visotube_1']:""; ?>"  min="0" name="visotube_1"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['goblet_1'])?$select_result['goblet_1']:""; ?>"  min="0" name="goblet_1"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_1'])?$select_result['dewar_1']:""; ?>"  min="0" name="dewar_1"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['media_used_1'])?$select_result['media_used_1']:""; ?>"  min="0" name="media_used_1"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['date4'])?$select_result['date4']:""; ?>"  maxlength="20" name="date4"  ></td>
							    	<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_1'])?$select_result['remarks_1']:""; ?>"  maxlength="20" name="remarks_1"  ></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_2'])?$select_result['no_2']:""; ?>"  min="0" name="no_2">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw_2'])?$select_result['no_of_straw_2']:""; ?>"  name="no_of_straw_2"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['embryos_frozen_2'])?$select_result['embryos_frozen_2']:""; ?>"  name="embryos_frozen_2"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_color_2'])?$select_result['straw_color_2']:""; ?>"  min="0" name="straw_color_2"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['visotube_2'])?$select_result['visotube_2']:""; ?>"  min="0" name="visotube_2"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['goblet_2'])?$select_result['goblet_2']:""; ?>"  min="0" name="goblet_2"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_2'])?$select_result['dewar_2']:""; ?>"  min="0" name="dewar_2"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['media_used_2'])?$select_result['media_used_2']:""; ?>"  min="0" name="media_used_2"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['date5'])?$select_result['date5']:""; ?>"  maxlength="20" name="date5"  ></td>
							    	<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_2'])?$select_result['remarks_2']:""; ?>"  maxlength="20" name="remarks_2"  ></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_3'])?$select_result['no_3']:""; ?>"  min="0" name="no_3">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw_3'])?$select_result['no_of_straw_3']:""; ?>"  name="no_of_straw_3"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['embryos_frozen_3'])?$select_result['embryos_frozen_3']:""; ?>"  name="embryos_frozen_3"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_color_3'])?$select_result['straw_color_3']:""; ?>"  min="0" name="straw_color_3"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['visotube_3'])?$select_result['visotube_3']:""; ?>"  min="0" name="visotube_3"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['goblet_3'])?$select_result['goblet_3']:""; ?>"  min="0" name="goblet_3"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_3'])?$select_result['dewar_3']:""; ?>"  min="0" name="dewar_3"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['media_used_3'])?$select_result['media_used_3']:""; ?>"  min="0" name="media_used_3"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['date6'])?$select_result['date6']:""; ?>"  maxlength="20" name="date6"  ></td>
							    	<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_3'])?$select_result['remarks_3']:""; ?>"  maxlength="20" name="remarks_3"  ></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_4'])?$select_result['no_4']:""; ?>"  min="0" name="no_4">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw_4'])?$select_result['no_of_straw_4']:""; ?>"  name="no_of_straw_4"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['embryos_frozen_4'])?$select_result['embryos_frozen_4']:""; ?>"  name="embryos_frozen_4"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_color_4'])?$select_result['straw_color_4']:""; ?>"  min="0" name="straw_color_4"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['visotube_4'])?$select_result['visotube_4']:""; ?>"  min="0" name="visotube_4"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['goblet_4'])?$select_result['goblet_4']:""; ?>"  min="0" name="goblet_4"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_4'])?$select_result['dewar_4']:""; ?>"  min="0" name="dewar_4"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['media_used_4'])?$select_result['media_used_4']:""; ?>"  min="0" name="media_used_4"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['date7'])?$select_result['date7']:""; ?>"  maxlength="20" name="date7"  ></td>
							    	<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_4'])?$select_result['remarks_4']:""; ?>"  maxlength="20" name="remarks_4"  ></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_5'])?$select_result['no_5']:""; ?>"  min="0" name="no_5">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw_5'])?$select_result['no_of_straw_5']:""; ?>"  name="no_of_straw_5"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['embryos_frozen_5'])?$select_result['embryos_frozen_5']:""; ?>"  name="embryos_frozen_5"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_color_5'])?$select_result['straw_color_5']:""; ?>"  min="0" name="straw_color_5"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['visotube_5'])?$select_result['visotube_5']:""; ?>"  min="0" name="visotube_5"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['goblet_5'])?$select_result['goblet_5']:""; ?>"  min="0" name="goblet_5"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_5'])?$select_result['dewar_5']:""; ?>"  min="0" name="dewar_5"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['media_used_5'])?$select_result['media_used_5']:""; ?>"  min="0" name="media_used_5"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['date8'])?$select_result['date8']:""; ?>"  maxlength="20" name="date8"  ></td>
							    	<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_5'])?$select_result['remarks_5']:""; ?>"  maxlength="20" name="remarks_5"  ></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_6'])?$select_result['no_6']:""; ?>"  min="0" name="no_6">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw_6'])?$select_result['no_of_straw_6']:""; ?>"  name="no_of_straw_6"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['embryos_frozen_6'])?$select_result['embryos_frozen_6']:""; ?>"  name="embryos_frozen_6"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_color_6'])?$select_result['straw_color_6']:""; ?>"  min="0" name="straw_color_6"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['visotube_6'])?$select_result['visotube_6']:""; ?>"  min="0" name="visotube_6"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['goblet_6'])?$select_result['goblet_6']:""; ?>"  min="0" name="goblet_6"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_6'])?$select_result['dewar_6']:""; ?>"  min="0" name="dewar_6"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['media_used_6'])?$select_result['media_used_6']:""; ?>"  min="0" name="media_used_6"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['date9'])?$select_result['date9']:""; ?>"  maxlength="20" name="date9"  ></td>
							    	<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_6'])?$select_result['remarks_6']:""; ?>"  maxlength="20" name="remarks_6"  ></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_7'])?$select_result['no_7']:""; ?>"  min="0" name="no_7">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw_7'])?$select_result['no_of_straw_7']:""; ?>"  name="no_of_straw_7"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['embryos_frozen_7'])?$select_result['embryos_frozen_7']:""; ?>"  name="embryos_frozen_7"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_color_7'])?$select_result['straw_color_7']:""; ?>"  min="0" name="straw_color_7"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['visotube_7'])?$select_result['visotube_7']:""; ?>"  min="0" name="visotube_7"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['goblet_7'])?$select_result['goblet_7']:""; ?>"  min="0" name="goblet_7"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_7'])?$select_result['dewar_7']:""; ?>"  min="0" name="dewar_7"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['media_used_7'])?$select_result['media_used_7']:""; ?>"  min="0" name="media_used_7"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['date10'])?$select_result['date10']:""; ?>"  maxlength="20" name="date10"  ></td>
							    	<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_7'])?$select_result['remarks_7']:""; ?>"  maxlength="20" name="remarks_7"  ></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_8'])?$select_result['no_8']:""; ?>"  min="0" name="no_8">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw_8'])?$select_result['no_of_straw_8']:""; ?>"  name="no_of_straw_8"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['embryos_frozen_8'])?$select_result['embryos_frozen_8']:""; ?>"  name="embryos_frozen_8"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_color_8'])?$select_result['straw_color_8']:""; ?>"  min="0" name="straw_color_8"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['visotube_8'])?$select_result['visotube_8']:""; ?>"  min="0" name="visotube_8"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['goblet_8'])?$select_result['goblet_8']:""; ?>"  min="0" name="goblet_8"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_8'])?$select_result['dewar_8']:""; ?>"  min="0" name="dewar_8"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['media_used_8'])?$select_result['media_used_8']:""; ?>"  min="0" name="media_used_8"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['date11'])?$select_result['date11']:""; ?>"  maxlength="20" name="date11"  ></td>
							    	<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_8'])?$select_result['remarks_8']:""; ?>"  maxlength="20" name="remarks_8"  ></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_9'])?$select_result['no_9']:""; ?>"  min="0" name="no_9">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['no_of_straw_9'])?$select_result['no_of_straw_9']:""; ?>"  name="no_of_straw_9"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['embryos_frozen_9'])?$select_result['embryos_frozen_9']:""; ?>"  name="embryos_frozen_9"  ></td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['straw_color_9'])?$select_result['straw_color_9']:""; ?>"  min="0" name="straw_color_9"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['visotube_9'])?$select_result['visotube_9']:""; ?>"  min="0" name="visotube_9"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['goblet_9'])?$select_result['goblet_9']:""; ?>"  min="0" name="goblet_9"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['dewar_9'])?$select_result['dewar_9']:""; ?>"  min="0" name="dewar_9"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['media_used_9'])?$select_result['media_used_9']:""; ?>"  min="0" name="media_used_9"></td>
							        <td style="background: #F4B083"><input type="date" value="<?php echo isset($select_result['date12'])?$select_result['date12']:""; ?>"  maxlength="20" name="date12"  ></td>
							    	<td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['remarks_9'])?$select_result['remarks_9']:""; ?>"  maxlength="20" name="remarks_9"  ></td>
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
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">EMBRYO FREEZING SECOND CYCLE</h3></td>
   </tr>
</table>
 <table width="100%" class="vb45rt">
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
	
 					<div class='table-responsive2'>
    					<table class="table table-bordered table-hover mt-2 table-sm red-field">
						<thead>
					        	<tr>
					        		<th colspan="4" style="border:1px solid;padding:5px;">EMBRYO FREEZING</th>
					        		<th colspan="6" style="border:1px solid;padding:5px;">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="border:1px solid;padding:5px;">Date: <?php echo isset($select_result['date'])?$select_result['date']:""; ?></th>
					        		<th colspan="6" style="border:1px solid;padding:5px;">Date: <?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="border:1px solid;padding:5px;">Time: <?php echo isset($select_result['time0'])?$select_result['time0']:""; ?></th>
					        		<th colspan="6" style="border:1px solid;padding:5px;">Diss.Time: <?php echo isset($select_result['time1'])?$select_result['time1']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="border:1px solid;padding:5px;">Emb: <?php echo isset($select_result['emb'])?$select_result['emb']:""; ?></th>
					        		<th colspan="6" style="border:1px solid;padding:5px;">Score Time: <?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="border:1px solid;padding:5px;">Dr: <?php echo isset($select_result['dr'])?$select_result['dr']:""; ?></th>
					        		<th colspan="6" style="border:1px solid;padding:5px;">Emb: <?php echo isset($select_result['emb0'])?$select_result['emb0']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="border:1px solid;padding:5px;">Witness: <?php echo isset($select_result['witness0'])?$select_result['witness0']:""; ?></th>
					        		<th colspan="6" style="border:1px solid;padding:5px;">Witness: <?php echo isset($select_result['witness1'])?$select_result['witness1']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="4" style="border:1px solid;padding:5px;">
					        			<table style="width: 100%;">
					        				<tr>
					        					<td>TOTAL NO OF EMBRYOS FROZEN</td>
					        					<td><?php echo isset($select_result['no_of_embryos_frozen'])?$select_result['no_of_embryos_frozen']:""; ?></td>
					        					<td><?php echo isset($select_result['no_of_embryos_frozen1'])?$select_result['no_of_embryos_frozen1']:""; ?></td>
					        				</tr>
					        			</table>
					        		</th>
					        		<th colspan="6" style="border:1px solid;padding:5px;"></th>
					        		
								</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td style="border:1px solid;padding:5px;">No</td>
									<td style="border:1px solid;padding:5px;">NO OF STRAW</td>
									<td style="border:1px solid;padding:5px;">EMBRYOS FROZEN IN THAT STRAW WITH CELL STAGE AND GRADING</td>
									<td style="border:1px solid;padding:5px;">STRAW COLOUR</td>
									<td style="border:1px solid;padding:5px;">VISOTUBE</td>
									<td style="border:1px solid;padding:5px;">GOBLET</td>
									<td style="border:1px solid;padding:5px;">DEWAR</td>
									<td style="border:1px solid;padding:5px;">MEDIA USED</td>
									<td style="border:1px solid;padding:5px;">STORAGE RENEWAL DATE</td>
									<td style="border:1px solid;padding:5px;">Remarks</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_0'])?$select_result['no_0']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_of_straw_0'])?$select_result['no_of_straw_0']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['embryos_frozen_0'])?$select_result['embryos_frozen_0']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['straw_color_0'])?$select_result['straw_color_0']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['visotube_0'])?$select_result['visotube_0']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['goblet_0'])?$select_result['goblet_0']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['dewar_0'])?$select_result['dewar_0']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['media_used_0'])?$select_result['media_used_0']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['date3'])?$select_result['date3']:""; ?></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['remarks_0'])?$select_result['remarks_0']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_1'])?$select_result['no_1']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_of_straw_1'])?$select_result['no_of_straw_1']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['embryos_frozen_1'])?$select_result['embryos_frozen_1']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['straw_color_1'])?$select_result['straw_color_1']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['visotube_1'])?$select_result['visotube_1']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['goblet_1'])?$select_result['goblet_1']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['dewar_1'])?$select_result['dewar_1']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['media_used_1'])?$select_result['media_used_1']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['date4'])?$select_result['date4']:""; ?></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['remarks_1'])?$select_result['remarks_1']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_2'])?$select_result['no_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_of_straw_2'])?$select_result['no_of_straw_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['embryos_frozen_2'])?$select_result['embryos_frozen_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['straw_color_2'])?$select_result['straw_color_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['visotube_2'])?$select_result['visotube_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['goblet_2'])?$select_result['goblet_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['dewar_2'])?$select_result['dewar_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['media_used_2'])?$select_result['media_used_2']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['date5'])?$select_result['date5']:""; ?></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['remarks_2'])?$select_result['remarks_2']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_3'])?$select_result['no_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_of_straw_3'])?$select_result['no_of_straw_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['embryos_frozen_3'])?$select_result['embryos_frozen_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['straw_color_3'])?$select_result['straw_color_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['visotube_3'])?$select_result['visotube_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['goblet_3'])?$select_result['goblet_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['dewar_3'])?$select_result['dewar_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['media_used_3'])?$select_result['media_used_3']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['date6'])?$select_result['date6']:""; ?></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['remarks_3'])?$select_result['remarks_3']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_4'])?$select_result['no_4']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_of_straw_4'])?$select_result['no_of_straw_4']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['embryos_frozen_4'])?$select_result['embryos_frozen_4']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['straw_color_4'])?$select_result['straw_color_4']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['visotube_4'])?$select_result['visotube_4']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['goblet_4'])?$select_result['goblet_4']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['dewar_4'])?$select_result['dewar_4']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['media_used_4'])?$select_result['media_used_4']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['date7'])?$select_result['date7']:""; ?></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['remarks_4'])?$select_result['remarks_4']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_5'])?$select_result['no_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_of_straw_5'])?$select_result['no_of_straw_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['embryos_frozen_5'])?$select_result['embryos_frozen_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['straw_color_5'])?$select_result['straw_color_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['visotube_5'])?$select_result['visotube_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['goblet_5'])?$select_result['goblet_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['dewar_5'])?$select_result['dewar_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['media_used_5'])?$select_result['media_used_5']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['date8'])?$select_result['date8']:""; ?></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['remarks_5'])?$select_result['remarks_5']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_6'])?$select_result['no_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_of_straw_6'])?$select_result['no_of_straw_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['embryos_frozen_6'])?$select_result['embryos_frozen_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['straw_color_6'])?$select_result['straw_color_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['visotube_6'])?$select_result['visotube_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['goblet_6'])?$select_result['goblet_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['dewar_6'])?$select_result['dewar_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['media_used_6'])?$select_result['media_used_6']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['date9'])?$select_result['date9']:""; ?></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['remarks_6'])?$select_result['remarks_6']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_7'])?$select_result['no_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_of_straw_7'])?$select_result['no_of_straw_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['embryos_frozen_7'])?$select_result['embryos_frozen_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['straw_color_7'])?$select_result['straw_color_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['visotube_7'])?$select_result['visotube_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['goblet_7'])?$select_result['goblet_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['dewar_7'])?$select_result['dewar_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['media_used_7'])?$select_result['media_used_7']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['date10'])?$select_result['date10']:""; ?></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['remarks_7'])?$select_result['remarks_7']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_8'])?$select_result['no_8']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_of_straw_8'])?$select_result['no_of_straw_8']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['embryos_frozen_8'])?$select_result['embryos_frozen_8']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['straw_color_8'])?$select_result['straw_color_8']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['visotube_8'])?$select_result['visotube_8']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['goblet_8'])?$select_result['goblet_8']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['dewar_8'])?$select_result['dewar_8']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['media_used_8'])?$select_result['media_used_8']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['date11'])?$select_result['date11']:""; ?></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['remarks_8'])?$select_result['remarks_8']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
							<tr>
									<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_9'])?$select_result['no_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['no_of_straw_9'])?$select_result['no_of_straw_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['embryos_frozen_9'])?$select_result['embryos_frozen_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['straw_color_9'])?$select_result['straw_color_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['visotube_9'])?$select_result['visotube_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['goblet_9'])?$select_result['goblet_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['dewar_9'])?$select_result['dewar_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['media_used_9'])?$select_result['media_used_9']:""; ?></td>
							        <td style="border:1px solid;padding:5px;"><?php echo isset($select_result['date12'])?$select_result['date12']:""; ?></td>
							    	<td style="border:1px solid;padding:5px;"><?php echo isset($select_result['remarks_9'])?$select_result['remarks_9']:""; ?></td>
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
			