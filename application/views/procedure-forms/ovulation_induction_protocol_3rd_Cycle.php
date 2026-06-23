<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
        $select_query = "SELECT * FROM `ovulation_induction_protocol` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `ovulation_induction_protocol` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE ovulation_induction_protocol SET ";
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
    $select_query = "SELECT * FROM `ovulation_induction_protocol` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
    $select_result = run_select_query($select_query); 

	$sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);		

	$procedure_sql = "SELECT ID, procedure_name, category FROM hms_procedures WHERE ID = '$procedure_id'";
	$proc_result = run_select_query($procedure_sql);

	$patient_procedure_sql = "SELECT * FROM hms_patient_procedure WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$patient_result = run_select_query($patient_procedure_sql);
	
	$data = [
		"lead_id" => trim($select_result4['crm_id']),
		"patient_id" => $patient_id,
		"procedure_type_name" => $proc_result['procedure_name'] . ', ' . (new DateTime($patient_result['on_date']))->format('Y-m-d'),
		"actual_stimulation_start_date" => isset($select_result['date1'])?$select_result['date1']:"",
	];

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
			'Content-Type: application/json'
		],
	]);

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
	<input type="hidden" value="pending" name="status"> 
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OVULATION INDUCTION PROTOCOL THIRD CYCLE</h3></td>
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
	<table class="table-bordered" width="100%">
		<tr style="text-align: center; color: red;">
			<td colspan="2">
			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
			            ){?>
			        <p id="last_updated">last updated on <?php echo $select_result['updated_at']; ?> by <?php echo $select_result['updated_by']; ?></p>
			    <?php } ?>
			</td>
		</tr>
		<tr style="text-align: center;">
			<td style="color: red;" colspan="2">SELF CYCLE (S)</td>
		</tr>
		<tr>
			<td style="color: red;">Partners name</td>
			<td><input type="text" value="<?php echo isset($select_result['partners_name'])?$select_result['partners_name']:""; ?>" maxlength="20" name="partners_name" class="form-control"></td>
		</tr>
		<tr>
			<td style="color: red;">ID</td>
			<td><input type="text" value="<?php echo isset($select_result['form_id'])?$select_result['form_id']:""; ?>" maxlength="20" name="form_id" class="form-control"></td>
		</tr>
		<tr style="color: red;">
			<td>LAST MENSTRUAL PERIOD -</td>
			<td><input type="date" value="<?php echo isset($select_result['last_menstrual'])?$select_result['last_menstrual']:""; ?>" name="last_menstrual" class="form-control"></td>
		</tr>
		<tr style="color: red;">
			<td>Indication </td>
			<td><input  type="text" value="<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>" maxlength="200" name="indication" class="form-control" ></td>
		</tr>	
	</table>
	<div class="table-responsive">
		<table style="color: red;" class="table-bordered" width="100%">
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
			</tr>
			<tr>
				<td>DATE</td>
				<td><input type="date" value="<?php echo isset($select_result['date1'])?$select_result['date1']:""; ?>" name="date1" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date2'])?$select_result['date2']:""; ?>" name="date2" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date3'])?$select_result['date3']:""; ?>" name="date3" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date4'])?$select_result['date4']:""; ?>" name="date4" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date5'])?$select_result['date5']:""; ?>" name="date5" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date6'])?$select_result['date6']:""; ?>" name="date6" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date7'])?$select_result['date7']:""; ?>" name="date7" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date8'])?$select_result['date8']:""; ?>" name="date8" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date9'])?$select_result['date9']:""; ?>" name="date9" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date10'])?$select_result['date10']:""; ?>" name="date10" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date11'])?$select_result['date11']:""; ?>" name="date11" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date12'])?$select_result['date12']:""; ?>" name="date12" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date13'])?$select_result['date13']:""; ?>" name="date13" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date14'])?$select_result['date14']:""; ?>" name="date14" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date15'])?$select_result['date15']:""; ?>" name="date15" class="form-control"></td>
				<td><input type="date" value="<?php echo isset($select_result['date16'])?$select_result['date16']:""; ?>" name="date16" class="form-control"></td>
			</tr>
			<tr>
				<td>FOLLICLE SIZE RIGHT (cm)</td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right1'])?$select_result['follicle_right1']:""; ?>" min="0" name="follicle_right1" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right2'])?$select_result['follicle_right2']:""; ?>" min="0" name="follicle_right2" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right3'])?$select_result['follicle_right3']:""; ?>" min="0" name="follicle_right3" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right4'])?$select_result['follicle_right4']:""; ?>" min="0" name="follicle_right4" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right5'])?$select_result['follicle_right5']:""; ?>" min="0" name="follicle_right5" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right6'])?$select_result['follicle_right6']:""; ?>" min="0" name="follicle_right6" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right7'])?$select_result['follicle_right7']:""; ?>" min="0" name="follicle_right7" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right8'])?$select_result['follicle_right8']:""; ?>" min="0" name="follicle_right8" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right9'])?$select_result['follicle_right9']:""; ?>" min="0" name="follicle_right9" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right10'])?$select_result['follicle_right10']:""; ?>" min="0" name="follicle_right10" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right11'])?$select_result['follicle_right11']:""; ?>" min="0" name="follicle_right11" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right12'])?$select_result['follicle_right12']:""; ?>" min="0" name="follicle_right12" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right13'])?$select_result['follicle_right13']:""; ?>" min="0" name="follicle_right13" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right14'])?$select_result['follicle_right14']:""; ?>" min="0" name="follicle_right14" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right15'])?$select_result['follicle_right15']:""; ?>" min="0" name="follicle_right15" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_right16'])?$select_result['follicle_right16']:""; ?>" min="0" name="follicle_right16" class="form-control"></td>
			</tr>
			<tr>
				<td>FOLLICLE SIZE LEFT (cm)</td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left1'])?$select_result['follicle_left1']:""; ?>" min="0" name="follicle_left1" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left2'])?$select_result['follicle_left2']:""; ?>" min="0" name="follicle_left2" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left3'])?$select_result['follicle_left3']:""; ?>" min="0" name="follicle_left3" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left4'])?$select_result['follicle_left4']:""; ?>" min="0" name="follicle_left4" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left5'])?$select_result['follicle_left5']:""; ?>" min="0" name="follicle_left5" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left6'])?$select_result['follicle_left6']:""; ?>" min="0" name="follicle_left6" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left7'])?$select_result['follicle_left7']:""; ?>" min="0" name="follicle_left7" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left8'])?$select_result['follicle_left8']:""; ?>" min="0" name="follicle_left8" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left9'])?$select_result['follicle_left9']:""; ?>" min="0" name="follicle_left9" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left10'])?$select_result['follicle_left10']:""; ?>" min="0" name="follicle_left10" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left11'])?$select_result['follicle_left11']:""; ?>" min="0" name="follicle_left11" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left12'])?$select_result['follicle_left12']:""; ?>" min="0" name="follicle_left12" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left13'])?$select_result['follicle_left13']:""; ?>" min="0" name="follicle_left13" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left14'])?$select_result['follicle_left14']:""; ?>" min="0" name="follicle_left14" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left15'])?$select_result['follicle_left15']:""; ?>" min="0" name="follicle_left15" class="form-control"></td>
				<td><input type="text"   value="<?php echo isset($select_result['follicle_left16'])?$select_result['follicle_left16']:""; ?>" min="0" name="follicle_left16" class="form-control"></td>
			</tr>
			<tr>
				<td style="color: green;">NO.FOLLICLES ON DAY OF TRIGGER</td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number1'])?$select_result['follicle_number1']:""; ?>" min="0" name="follicle_number1" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number2'])?$select_result['follicle_number2']:""; ?>" min="0" name="follicle_number2" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number3'])?$select_result['follicle_number3']:""; ?>" min="0" name="follicle_number3" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number4'])?$select_result['follicle_number4']:""; ?>" min="0" name="follicle_number4" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number5'])?$select_result['follicle_number5']:""; ?>" min="0" name="follicle_number5" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number6'])?$select_result['follicle_number6']:""; ?>" min="0" name="follicle_number6" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number7'])?$select_result['follicle_number7']:""; ?>" min="0" name="follicle_number7" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number8'])?$select_result['follicle_number8']:""; ?>" min="0" name="follicle_number8" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number9'])?$select_result['follicle_number9']:""; ?>" min="0" name="follicle_number9" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number10'])?$select_result['follicle_number10']:""; ?>" min="0" name="follicle_number10" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number11'])?$select_result['follicle_number11']:""; ?>" min="0" name="follicle_number11" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number12'])?$select_result['follicle_number12']:""; ?>" min="0" name="follicle_number12" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number13'])?$select_result['follicle_number13']:""; ?>" min="0" name="follicle_number13" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number14'])?$select_result['follicle_number14']:""; ?>" min="0" name="follicle_number14" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number15'])?$select_result['follicle_number15']:""; ?>" min="0" name="follicle_number15" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['follicle_number16'])?$select_result['follicle_number16']:""; ?>" min="0" name="follicle_number16" class="form-control"></td>
			</tr>
			<tr>
				<td>ENDOMETRIAL THICKNESS (cm)</td>
				<td><input type="number" value="<?php echo isset($select_result['endometrial_thickness1'])?$select_result['endometrial_thickness1']:""; ?>" min="0" name="endometrial_thickness1" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['endometrial_thickness2'])?$select_result['endometrial_thickness2']:""; ?>" min="0" name="endometrial_thickness2" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['endometrial_thickness3'])?$select_result['endometrial_thickness3']:""; ?>" min="0" name="endometrial_thickness3" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['endometrial_thickness4'])?$select_result['endometrial_thickness4']:""; ?>" min="0" name="endometrial_thickness4" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['endometrial_thickness5'])?$select_result['endometrial_thickness5']:""; ?>" min="0" name="endometrial_thickness5" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['endometrial_thickness6'])?$select_result['endometrial_thickness6']:""; ?>" min="0" name="endometrial_thickness6" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['endometrial_thickness7'])?$select_result['endometrial_thickness7']:""; ?>" min="0" name="endometrial_thickness7" class="form-control"></td>
				<td><input type="number" value="<?php echo isset($select_result['endometrial_thickness8'])?$select_result['endometrial_thickness8']:""; ?>" min="0" name="endometrial_thickness8" class="form-control"></td>
				<td><input  type="number" value="<?php echo isset($select_result['endometrial_thickness9'])?$select_result['endometrial_thickness9']:""; ?>" min="0" name="endometrial_thickness9" class="form-control"></td>
				<td><input  type="number" value="<?php echo isset($select_result['endometrial_thickness10'])?$select_result['endometrial_thickness10']:""; ?>" min="0" name="endometrial_thickness10" class="form-control"></td>
				<td><input  type="number" value="<?php echo isset($select_result['endometrial_thickness11'])?$select_result['endometrial_thickness11']:""; ?>" min="0" name="endometrial_thickness11" class="form-control"></td>
				<td><input  type="number" value="<?php echo isset($select_result['endometrial_thickness12'])?$select_result['endometrial_thickness12']:""; ?>" min="0" name="endometrial_thickness12" class="form-control"></td>
				<td><input  type="number" value="<?php echo isset($select_result['endometrial_thickness13'])?$select_result['endometrial_thickness13']:""; ?>" min="0" name="endometrial_thickness13" class="form-control"></td>
				<td><input  type="number" value="<?php echo isset($select_result['endometrial_thickness14'])?$select_result['endometrial_thickness14']:""; ?>" min="0" name="endometrial_thickness14" class="form-control"></td>
				<td><input  type="number" value="<?php echo isset($select_result['endometrial_thickness15'])?$select_result['endometrial_thickness15']:""; ?>" min="0" name="endometrial_thickness15" class="form-control"></td>
				<td><input  type="number" value="<?php echo isset($select_result['endometrial_thickness16'])?$select_result['endometrial_thickness16']:""; ?>" min="0" name="endometrial_thickness16" class="form-control"></td>
			</tr>
			<tr>
				<td>INJECTION DOSE: FSH</td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose1'])?$select_result['injection_dose1']:""; ?>" maxlength="20" name="injection_dose1" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose2'])?$select_result['injection_dose2']:""; ?>" maxlength="20" name="injection_dose2" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose3'])?$select_result['injection_dose3']:""; ?>" maxlength="20" name="injection_dose3" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose4'])?$select_result['injection_dose4']:""; ?>" maxlength="20" name="injection_dose4" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose5'])?$select_result['injection_dose5']:""; ?>" maxlength="20" name="injection_dose5" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose6'])?$select_result['injection_dose6']:""; ?>" maxlength="20" name="injection_dose6" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose7'])?$select_result['injection_dose7']:""; ?>" maxlength="20" name="injection_dose7" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose8'])?$select_result['injection_dose8']:""; ?>" maxlength="20" name="injection_dose8" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose9'])?$select_result['injection_dose9']:""; ?>" maxlength="20" name="injection_dose9" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose10'])?$select_result['injection_dose10']:""; ?>" maxlength="20" name="injection_dose10" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose11'])?$select_result['injection_dose11']:""; ?>" maxlength="20" name="injection_dose11" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose12'])?$select_result['injection_dose12']:""; ?>" maxlength="20" name="injection_dose12" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose13'])?$select_result['injection_dose13']:""; ?>" maxlength="20" name="injection_dose13" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose14'])?$select_result['injection_dose14']:""; ?>" maxlength="20" name="injection_dose14" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose15'])?$select_result['injection_dose15']:""; ?>" maxlength="20" name="injection_dose15" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['injection_dose16'])?$select_result['injection_dose16']:""; ?>" maxlength="20" name="injection_dose16" class="form-control"></td>
			</tr>
			<tr>
				<td>HMG</td>
				<td><input type="text" value="<?php echo isset($select_result['hmg1'])?$select_result['hmg1']:""; ?>" maxlength="20" name="hmg1" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg2'])?$select_result['hmg2']:""; ?>" maxlength="20" name="hmg2" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg3'])?$select_result['hmg3']:""; ?>" maxlength="20" name="hmg3" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg4'])?$select_result['hmg4']:""; ?>" maxlength="20" name="hmg4" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg5'])?$select_result['hmg5']:""; ?>" maxlength="20" name="hmg5" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg6'])?$select_result['hmg6']:""; ?>" maxlength="20" name="hmg6" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg7'])?$select_result['hmg7']:""; ?>" maxlength="20" name="hmg7" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg8'])?$select_result['hmg8']:""; ?>" maxlength="20" name="hmg8" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg9'])?$select_result['hmg9']:""; ?>" maxlength="20" name="hmg9" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg10'])?$select_result['hmg10']:""; ?>" maxlength="20" name="hmg10" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg11'])?$select_result['hmg11']:""; ?>" maxlength="20" name="hmg11" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg12'])?$select_result['hmg12']:""; ?>" maxlength="20" name="hmg12" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg13'])?$select_result['hmg13']:""; ?>" maxlength="20" name="hmg13" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg14'])?$select_result['hmg14']:""; ?>" maxlength="20" name="hmg14" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg15'])?$select_result['hmg15']:""; ?>" maxlength="20" name="hmg15" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['hmg16'])?$select_result['hmg16']:""; ?>" maxlength="20" name="hmg16" class="form-control"></td>
			</tr>
			<tr>
				<td>ANTAGONIST</td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist1'])?$select_result['antagonist1']:""; ?>" maxlength="20" name="antagonist1" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist2'])?$select_result['antagonist2']:""; ?>" maxlength="20" name="antagonist2" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist3'])?$select_result['antagonist3']:""; ?>" maxlength="20" name="antagonist3" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist4'])?$select_result['antagonist4']:""; ?>" maxlength="20" name="antagonist4" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist5'])?$select_result['antagonist5']:""; ?>" maxlength="20" name="antagonist5" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist6'])?$select_result['antagonist6']:""; ?>" maxlength="20" name="antagonist6" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist7'])?$select_result['antagonist7']:""; ?>" maxlength="20" name="antagonist7" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist8'])?$select_result['antagonist8']:""; ?>" maxlength="20" name="antagonist8" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist9'])?$select_result['antagonist9']:""; ?>" maxlength="20" name="antagonist9" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist10'])?$select_result['antagonist10']:""; ?>" maxlength="20" name="antagonist10" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist11'])?$select_result['antagonist11']:""; ?>" maxlength="20" name="antagonist11" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist12'])?$select_result['antagonist12']:""; ?>" maxlength="20" name="antagonist12" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist13'])?$select_result['antagonist13']:""; ?>" maxlength="20" name="antagonist13" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist14'])?$select_result['antagonist14']:""; ?>" maxlength="20" name="antagonist14" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist15'])?$select_result['antagonist15']:""; ?>" maxlength="20" name="antagonist15" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['antagonist16'])?$select_result['antagonist16']:""; ?>" maxlength="20" name="antagonist16" class="form-control"></td>
			</tr>
			<tr>
				<td>AGONIST/HCG(TRIGGER)</td>
				<td><input type="text" value="<?php echo isset($select_result['agonist1'])?$select_result['agonist1']:""; ?>" maxlength="20" name="agonist1" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist2'])?$select_result['agonist2']:""; ?>" maxlength="20" name="agonist2" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist3'])?$select_result['agonist3']:""; ?>" maxlength="20" name="agonist3" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist4'])?$select_result['agonist4']:""; ?>" maxlength="20" name="agonist4" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist5'])?$select_result['agonist5']:""; ?>" maxlength="20" name="agonist5" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist6'])?$select_result['agonist6']:""; ?>" maxlength="20" name="agonist6" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist7'])?$select_result['agonist7']:""; ?>" maxlength="20" name="agonist7" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist8'])?$select_result['agonist8']:""; ?>" maxlength="20" name="agonist8" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist9'])?$select_result['agonist9']:""; ?>" maxlength="20" name="agonist9" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist10'])?$select_result['agonist10']:""; ?>" maxlength="20" name="agonist10" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist11'])?$select_result['agonist11']:""; ?>" maxlength="20" name="agonist11" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist12'])?$select_result['agonist12']:""; ?>" maxlength="20" name="agonist12" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist13'])?$select_result['agonist13']:""; ?>" maxlength="20" name="agonist13" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist14'])?$select_result['agonist14']:""; ?>" maxlength="20" name="agonist14" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist15'])?$select_result['agonist15']:""; ?>" maxlength="20" name="agonist15" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['agonist16'])?$select_result['agonist16']:""; ?>" maxlength="20" name="agonist16" class="form-control"></td>
			</tr>
			<tr>
				<td>MEDICINE ADDED</td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose1'])?$select_result['medicine_dose1']:""; ?>" maxlength="20" name="medicine_dose1" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose2'])?$select_result['medicine_dose2']:""; ?>" maxlength="20" name="medicine_dose2" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose3'])?$select_result['medicine_dose3']:""; ?>" maxlength="20" name="medicine_dose3" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose4'])?$select_result['medicine_dose4']:""; ?>" maxlength="20" name="medicine_dose4" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose5'])?$select_result['medicine_dose5']:""; ?>" maxlength="20" name="medicine_dose5" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose6'])?$select_result['medicine_dose6']:""; ?>" maxlength="20" name="medicine_dose6" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose7'])?$select_result['medicine_dose7']:""; ?>" maxlength="20" name="medicine_dose7" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose8'])?$select_result['medicine_dose8']:""; ?>" maxlength="20" name="medicine_dose8" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose9'])?$select_result['medicine_dose9']:""; ?>" maxlength="20" name="medicine_dose9" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose10'])?$select_result['medicine_dose10']:""; ?>" maxlength="20" name="medicine_dose10" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose11'])?$select_result['medicine_dose11']:""; ?>" maxlength="20" name="medicine_dose11" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose12'])?$select_result['medicine_dose12']:""; ?>" maxlength="20" name="medicine_dose12" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose13'])?$select_result['medicine_dose13']:""; ?>" maxlength="20" name="medicine_dose13" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose14'])?$select_result['medicine_dose14']:""; ?>" maxlength="20" name="medicine_dose14" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose15'])?$select_result['medicine_dose15']:""; ?>" maxlength="20" name="medicine_dose15" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['medicine_dose16'])?$select_result['medicine_dose16']:""; ?>" maxlength="20" name="medicine_dose16" class="form-control"></td>
			</tr>
			<tr>
				<td>REMARKS</td>
				<td><input type="text" value="<?php echo isset($select_result['remarks1'])?$select_result['remarks1']:""; ?>" maxlength="20" name="remarks1" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks2'])?$select_result['remarks2']:""; ?>" maxlength="20" name="remarks2" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks3'])?$select_result['remarks3']:""; ?>" maxlength="20" name="remarks3" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks4'])?$select_result['remarks4']:""; ?>" maxlength="20" name="remarks4" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks5'])?$select_result['remarks5']:""; ?>" maxlength="20" name="remarks5" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks6'])?$select_result['remarks6']:""; ?>" maxlength="20" name="remarks6" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks7'])?$select_result['remarks7']:""; ?>" maxlength="20" name="remarks7" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks8'])?$select_result['remarks8']:""; ?>" maxlength="20" name="remarks8" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks9'])?$select_result['remarks9']:""; ?>" maxlength="20" name="remarks9" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks10'])?$select_result['remarks10']:""; ?>" maxlength="20" name="remarks10" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks11'])?$select_result['remarks11']:""; ?>" maxlength="20" name="remarks11" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks12'])?$select_result['remarks12']:""; ?>" maxlength="20" name="remarks12" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks13'])?$select_result['remarks13']:""; ?>" maxlength="20" name="remarks13" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks14'])?$select_result['remarks14']:""; ?>" maxlength="20" name="remarks14" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks15'])?$select_result['remarks15']:""; ?>" maxlength="20" name="remarks15" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['remarks16'])?$select_result['remarks16']:""; ?>" maxlength="20" name="remarks16" class="form-control"></td>
			</tr>
			<tr>
				<td>FOLLOWUP ON</td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on1'])?$select_result['followup_on1']:""; ?>" maxlength="20" name="followup_on1" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on2'])?$select_result['followup_on2']:""; ?>" maxlength="20" name="followup_on2" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on3'])?$select_result['followup_on3']:""; ?>" maxlength="20" name="followup_on3" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on4'])?$select_result['followup_on4']:""; ?>" maxlength="20" name="followup_on4" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on5'])?$select_result['followup_on5']:""; ?>" maxlength="20" name="followup_on5" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on6'])?$select_result['followup_on6']:""; ?>" maxlength="20" name="followup_on6" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on7'])?$select_result['followup_on7']:""; ?>" maxlength="20" name="followup_on7" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on8'])?$select_result['followup_on8']:""; ?>" maxlength="20" name="followup_on8" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on9'])?$select_result['followup_on9']:""; ?>" maxlength="20" name="followup_on9" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on10'])?$select_result['followup_on10']:""; ?>" maxlength="20" name="followup_on10" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on11'])?$select_result['followup_on11']:""; ?>" maxlength="20" name="followup_on11" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on12'])?$select_result['followup_on12']:""; ?>" maxlength="20" name="followup_on12" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on13'])?$select_result['followup_on13']:""; ?>" maxlength="20" name="followup_on13" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on14'])?$select_result['followup_on14']:""; ?>" maxlength="20" name="followup_on14" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on15'])?$select_result['followup_on15']:""; ?>" maxlength="20" name="followup_on15" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['followup_on16'])?$select_result['followup_on16']:""; ?>" maxlength="20" name="followup_on16" class="form-control"></td>
			</tr>
			<tr>
				<td>SERUM ESTRADIOL  (E2) LEVEL</td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level1'])?$select_result['serum_e2_level1']:""; ?>" maxlength="20" name="serum_e2_level1" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level2'])?$select_result['serum_e2_level2']:""; ?>" maxlength="20" name="serum_e2_level2" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level3'])?$select_result['serum_e2_level3']:""; ?>" maxlength="20" name="serum_e2_level3" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level4'])?$select_result['serum_e2_level4']:""; ?>" maxlength="20" name="serum_e2_level4" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level5'])?$select_result['serum_e2_level5']:""; ?>" maxlength="20" name="serum_e2_level5" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level6'])?$select_result['serum_e2_level6']:""; ?>" maxlength="20" name="serum_e2_level6" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level7'])?$select_result['serum_e2_level7']:""; ?>" maxlength="20" name="serum_e2_level7" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level8'])?$select_result['serum_e2_level8']:""; ?>" maxlength="20" name="serum_e2_level8" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level9'])?$select_result['serum_e2_level9']:""; ?>" maxlength="20" name="serum_e2_level9" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level10'])?$select_result['serum_e2_level10']:""; ?>" maxlength="20" name="serum_e2_level10" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level11'])?$select_result['serum_e2_level11']:""; ?>" maxlength="20" name="serum_e2_level11" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level12'])?$select_result['serum_e2_level12']:""; ?>" maxlength="20" name="serum_e2_level12" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level13'])?$select_result['serum_e2_level13']:""; ?>" maxlength="20" name="serum_e2_level13" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level14'])?$select_result['serum_e2_level14']:""; ?>" maxlength="20" name="serum_e2_level14" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level15'])?$select_result['serum_e2_level15']:""; ?>" maxlength="20" name="serum_e2_level15" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_e2_level16'])?$select_result['serum_e2_level16']:""; ?>" maxlength="20" name="serum_e2_level16" class="form-control"></td>
			</tr>
			<tr>
				<td>SERUM LH LEVEL</td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level1'])?$select_result['serum_lh_level1']:""; ?>" maxlength="20" name="serum_lh_level1" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level2'])?$select_result['serum_lh_level2']:""; ?>" maxlength="20" name="serum_lh_level2" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level3'])?$select_result['serum_lh_level3']:""; ?>" maxlength="20" name="serum_lh_level3" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level4'])?$select_result['serum_lh_level4']:""; ?>" maxlength="20" name="serum_lh_level4" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level5'])?$select_result['serum_lh_level5']:""; ?>" maxlength="20" name="serum_lh_level5" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level6'])?$select_result['serum_lh_level6']:""; ?>" maxlength="20" name="serum_lh_level6" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level7'])?$select_result['serum_lh_level7']:""; ?>" maxlength="20" name="serum_lh_level7" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level8'])?$select_result['serum_lh_level8']:""; ?>" maxlength="20" name="serum_lh_level8" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level9'])?$select_result['serum_lh_level9']:""; ?>" maxlength="20" name="serum_lh_level9" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level10'])?$select_result['serum_lh_level10']:""; ?>" maxlength="20" name="serum_lh_level10" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level11'])?$select_result['serum_lh_level11']:""; ?>" maxlength="20" name="serum_lh_level11" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level12'])?$select_result['serum_lh_level12']:""; ?>" maxlength="20" name="serum_lh_level12" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level13'])?$select_result['serum_lh_level13']:""; ?>" maxlength="20" name="serum_lh_level13" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level14'])?$select_result['serum_lh_level14']:""; ?>" maxlength="20" name="serum_lh_level14" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level15'])?$select_result['serum_lh_level15']:""; ?>" maxlength="20" name="serum_lh_level15" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_lh_level16'])?$select_result['serum_lh_level16']:""; ?>" maxlength="20" name="serum_lh_level16" class="form-control"></td>
			</tr>
			<tr>
				<td>SERUM PROGESTERONE LEVEL</td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level1'])?$select_result['serum_progesterone_level1']:""; ?>" maxlength="20" name="serum_progesterone_level1" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level2'])?$select_result['serum_progesterone_level2']:""; ?>" maxlength="20" name="serum_progesterone_level2" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level3'])?$select_result['serum_progesterone_level3']:""; ?>" maxlength="20" name="serum_progesterone_level3" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level4'])?$select_result['serum_progesterone_level4']:""; ?>" maxlength="20" name="serum_progesterone_level4" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level5'])?$select_result['serum_progesterone_level5']:""; ?>" maxlength="20" name="serum_progesterone_level5" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level6'])?$select_result['serum_progesterone_level6']:""; ?>" maxlength="20" name="serum_progesterone_level6" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level7'])?$select_result['serum_progesterone_level7']:""; ?>" maxlength="20" name="serum_progesterone_level7" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level8'])?$select_result['serum_progesterone_level8']:""; ?>" maxlength="20" name="serum_progesterone_level8" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level9'])?$select_result['serum_progesterone_level9']:""; ?>" maxlength="20" name="serum_progesterone_level9" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level10'])?$select_result['serum_progesterone_level10']:""; ?>" maxlength="20" name="serum_progesterone_level10" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level11'])?$select_result['serum_progesterone_level11']:""; ?>" maxlength="20" name="serum_progesterone_level11" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level12'])?$select_result['serum_progesterone_level12']:""; ?>" maxlength="20" name="serum_progesterone_level12" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level13'])?$select_result['serum_progesterone_level13']:""; ?>" maxlength="20" name="serum_progesterone_level13" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level14'])?$select_result['serum_progesterone_level14']:""; ?>" maxlength="20" name="serum_progesterone_level14" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level15'])?$select_result['serum_progesterone_level15']:""; ?>" maxlength="20" name="serum_progesterone_level15" class="form-control"></td>
				<td><input type="text" value="<?php echo isset($select_result['serum_progesterone_level16'])?$select_result['serum_progesterone_level16']:""; ?>" maxlength="20" name="serum_progesterone_level16" class="form-control"></td>
			</tr>
		</table>
	</div>
	<table style="color: red;" class="table-bordered" width="100%">
		<tr>
			<td>DOCTOR</td>
			<td><input type="text" readonly="" value="<?php if (!empty($select_result['doctor'])) {  echo $select_result['doctor']; } else {  echo isset($_SESSION['logged_doctor']['name']) ? $_SESSION['logged_doctor']['name'] : ''; }  ?>" name="doctor" class="form-control"></td>
			<td>COUNSELLOR</td>
			<td><input type="text" value="<?php echo isset($select_result['counsellor'])?$select_result['counsellor']:""; ?>" name="counsellor" class="form-control"></td>
			<td>NURSE</td>
			<td><input type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>" name="nurse" class="form-control"></td>
		</tr>
	</table>
	<!-- /.card-body -->
	<div class="card-footer">
		<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
		<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
	</div>
</form>



<!--- print Button -->



<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;">  





	<table class="table-bordered"  style="width:100%; border:1px solid #cdcdcd;">
	   <tr style="text-align: center; color: red; ">
   <td colspan="2" style="width:50%;padding:5px;border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td colspan="2" style="border:1px solid #cdcdcd;"><h2>OVULATION INDUCTION PROTOCOL THIRD CYCLE</h2></td>
</tr>
		<tr style="text-align: center; color: red; ">
			<td colspan="4" style="border:1px solid #cdcdcd;">
			    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
			            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
			            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
			            ){?>
			        <p id="last_updated"> last updated on <?php echo $select_result['updated_at']; ?> by <?php echo $select_result['updated_by']; ?></p>
			    <?php } ?>
			</td>
		</tr>
		
		<tr style="text-align: center;">
			<td  style=" color:red; border:1px solid #cdcdcd;" colspan="2">SELF CYCLE (S)</td>
		</tr>
		<tr>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;">UHID :</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?> </td>
</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;">IIC ID:</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo $patient_id; ?></td>
</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">Partners name</td>
			<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['partners_name'])?$select_result['partners_name']:""; ?> </td>
		</tr>
		<tr>
		
			<td style="border:1px solid #cdcdcd;">Donor ID</td>
			<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['donor_id'])?$select_result['donor_id']:""; ?></td>
		
		</tr>
		
	</table>

		<table  class="table-bordered"  style="color:red; width:100%; border:1px solid #cdcdcd;">
			<tr>
				<td style="border:1px solid #cdcdcd;" >Day of Stimulation</td>
				<td style="border:1px solid #cdcdcd;">1</td>
				<td style="border:1px solid #cdcdcd;">2</td>
				<td style="border:1px solid #cdcdcd;">3</td>
				<td style="border:1px solid #cdcdcd;">4</td>
				<td style="border:1px solid #cdcdcd;">5</td>
				<td style="border:1px solid #cdcdcd;">6</td>
				<td style="border:1px solid #cdcdcd;">7</td>
				<td style="border:1px solid #cdcdcd;">8</td>
				<td style="border:1px solid #cdcdcd;">9</td>
				<td style="border:1px solid #cdcdcd;">10</td>
				<td style="border:1px solid #cdcdcd;">11</td>
				<td style="border:1px solid #cdcdcd;">12</td>
				<td style="border:1px solid #cdcdcd;">13</td>
				<td style="border:1px solid #cdcdcd;">14</td>
				<td style="border:1px solid #cdcdcd;">15</td>
				<td style="border:1px solid #cdcdcd;">16</td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">DATE</td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['date1'])?$select_result['date1']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['date3'])?$select_result['date3']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['date4'])?$select_result['date4']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['date5'])?$select_result['date5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['date6'])?$select_result['date6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['date7'])?$select_result['date7']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date8'])?$select_result['date8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['date9'])?$select_result['date9']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['date10'])?$select_result['date10']:""; ?> </td>
				<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date11'])?$select_result['date11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date12'])?$select_result['date12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date13'])?$select_result['date13']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date14'])?$select_result['date14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date15'])?$select_result['date15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['date16'])?$select_result['date16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">FOLLICLE SIZE RIGHT (cm)</td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['follicle_right1'])?$select_result['follicle_right1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_right2'])?$select_result['follicle_right2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_right3'])?$select_result['follicle_right3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['follicle_right4'])?$select_result['follicle_right4']:""; ?></td>
				
				
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_right5'])?$select_result['follicle_right5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['follicle_right6'])?$select_result['follicle_right6']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['follicle_right7'])?$select_result['follicle_right7']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['follicle_right8'])?$select_result['follicle_right8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['follicle_right9'])?$select_result['follicle_right9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_right10'])?$select_result['follicle_right10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_right11'])?$select_result['follicle_right11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_right12'])?$select_result['follicle_right12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_right13'])?$select_result['follicle_right13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_right14'])?$select_result['follicle_right14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"<?php echo isset($select_result['follicle_right15'])?$select_result['follicle_right15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_right16'])?$select_result['follicle_right16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;"> FOLLICLE SIZE LEFT (cm)</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left1'])?$select_result['follicle_left1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left2'])?$select_result['follicle_left2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left3'])?$select_result['follicle_left3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left4'])?$select_result['follicle_left4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left5'])?$select_result['follicle_left5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left6'])?$select_result['follicle_left6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left7'])?$select_result['follicle_left7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left8'])?$select_result['follicle_left8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left9'])?$select_result['follicle_left9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left10'])?$select_result['follicle_left10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left11'])?$select_result['follicle_left11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left12'])?$select_result['follicle_left12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left13'])?$select_result['follicle_left13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left14'])?$select_result['follicle_left14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left15'])?$select_result['follicle_left15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_left16'])?$select_result['follicle_left16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">NO.FOLLICLES ON DAY OF TRIGGER</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number1'])?$select_result['follicle_number1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number2'])?$select_result['follicle_number2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number3'])?$select_result['follicle_number3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number4'])?$select_result['follicle_number4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number5'])?$select_result['follicle_number5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number6'])?$select_result['follicle_number6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number7'])?$select_result['follicle_number7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number8'])?$select_result['follicle_number8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number9'])?$select_result['follicle_number9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number10'])?$select_result['follicle_number10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number11'])?$select_result['follicle_number11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number12'])?$select_result['follicle_number12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number13'])?$select_result['follicle_number13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number14'])?$select_result['follicle_number14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number15'])?$select_result['follicle_number15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['follicle_number16'])?$select_result['follicle_number16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">ENDOMETRIAL THICKNESS (cm)</td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['endometrial_thickness1'])?$select_result['endometrial_thickness1']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness2'])?$select_result['endometrial_thickness2']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness3'])?$select_result['endometrial_thickness3']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness4'])?$select_result['endometrial_thickness4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness5'])?$select_result['endometrial_thickness5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness6'])?$select_result['endometrial_thickness6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness7'])?$select_result['endometrial_thickness7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness8'])?$select_result['endometrial_thickness8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness9'])?$select_result['endometrial_thickness9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness10'])?$select_result['endometrial_thickness10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness11'])?$select_result['endometrial_thickness11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness12'])?$select_result['endometrial_thickness12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness13'])?$select_result['endometrial_thickness13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness14'])?$select_result['endometrial_thickness14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness15'])?$select_result['endometrial_thickness15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['endometrial_thickness16'])?$select_result['endometrial_thickness16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">INJECTION DOSE: FSH</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose1'])?$select_result['injection_dose1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose2'])?$select_result['injection_dose2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['injection_dose3'])?$select_result['injection_dose3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose4'])?$select_result['injection_dose4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose5'])?$select_result['injection_dose5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose6'])?$select_result['injection_dose6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose7'])?$select_result['injection_dose7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose8'])?$select_result['injection_dose8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose9'])?$select_result['injection_dose9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose10'])?$select_result['injection_dose10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose11'])?$select_result['injection_dose11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose12'])?$select_result['injection_dose12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose13'])?$select_result['injection_dose13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose14'])?$select_result['injection_dose14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose15'])?$select_result['injection_dose15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['injection_dose16'])?$select_result['injection_dose16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">HMG</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg1'])?$select_result['hmg1']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg2'])?$select_result['hmg2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg3'])?$select_result['hmg3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg4'])?$select_result['hmg4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg5'])?$select_result['hmg5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg6'])?$select_result['hmg6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg7'])?$select_result['hmg7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg8'])?$select_result['hmg8']:""; ?> </td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg9'])?$select_result['hmg9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg10'])?$select_result['hmg10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg11'])?$select_result['hmg11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg12'])?$select_result['hmg12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg13'])?$select_result['hmg13']:""; ?></td>
				</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg15'])?$select_result['hmg15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['hmg16'])?$select_result['hmg16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">ANTAGONIST</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist1'])?$select_result['antagonist1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist2'])?$select_result['antagonist2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist3'])?$select_result['antagonist3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist4'])?$select_result['antagonist4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist5'])?$select_result['antagonist5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist6'])?$select_result['antagonist6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist7'])?$select_result['antagonist7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist8'])?$select_result['antagonist8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist9'])?$select_result['antagonist9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist10'])?$select_result['antagonist10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist11'])?$select_result['antagonist11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist12'])?$select_result['antagonist12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist13'])?$select_result['antagonist13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist14'])?$select_result['antagonist14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist15'])?$select_result['antagonist15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['antagonist16'])?$select_result['antagonist16']:""; ?></td>
			</tr>
			<tr>
<td style="border:1px solid #cdcdcd;">AGONIST/HCG(TRIGGER)</td>
<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist1'])?$select_result['agonist1']:""; ?></td>
<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist2'])?$select_result['agonist2']:""; ?></td>
<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist3'])?$select_result['agonist3']:""; ?></td>
<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist4'])?$select_result['agonist4']:""; ?></td>
<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist5'])?$select_result['agonist5']:""; ?></td>
<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist6'])?$select_result['agonist6']:""; ?></td>
<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist7'])?$select_result['agonist7']:""; ?></td>
<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist8'])?$select_result['agonist8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist9'])?$select_result['agonist9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist10'])?$select_result['agonist10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist11'])?$select_result['agonist11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist12'])?$select_result['agonist12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist13'])?$select_result['agonist13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist14'])?$select_result['agonist14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist15'])?$select_result['agonist15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['agonist16'])?$select_result['agonist16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">MEDICINE ADDED</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose1'])?$select_result['medicine_dose1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose2'])?$select_result['medicine_dose2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose3'])?$select_result['medicine_dose3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose4'])?$select_result['medicine_dose4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose5'])?$select_result['medicine_dose5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose6'])?$select_result['medicine_dose6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose7'])?$select_result['medicine_dose7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose8'])?$select_result['medicine_dose8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose9'])?$select_result['medicine_dose9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose10'])?$select_result['medicine_dose10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose11'])?$select_result['medicine_dose11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose12'])?$select_result['medicine_dose12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose13'])?$select_result['medicine_dose13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose14'])?$select_result['medicine_dose14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose15'])?$select_result['medicine_dose15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['medicine_dose16'])?$select_result['medicine_dose16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">REMARKS</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks1'])?$select_result['remarks1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks2'])?$select_result['remarks2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks3'])?$select_result['remarks3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks4'])?$select_result['remarks4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks5'])?$select_result['remarks5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks6'])?$select_result['remarks6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks7'])?$select_result['remarks7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks8'])?$select_result['remarks8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks9'])?$select_result['remarks9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks10'])?$select_result['remarks10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks11'])?$select_result['remarks11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks12'])?$select_result['remarks12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks13'])?$select_result['remarks13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks14'])?$select_result['remarks14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks15'])?$select_result['remarks15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['remarks16'])?$select_result['remarks16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">FOLLOWUP ON</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on1'])?$select_result['followup_on1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on2'])?$select_result['followup_on2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on3'])?$select_result['followup_on3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on4'])?$select_result['followup_on4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on5'])?$select_result['followup_on5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on6'])?$select_result['followup_on6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on7'])?$select_result['followup_on7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on8'])?$select_result['followup_on8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on9'])?$select_result['followup_on9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on10'])?$select_result['followup_on10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on11'])?$select_result['followup_on11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on12'])?$select_result['followup_on12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on13'])?$select_result['followup_on13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on14'])?$select_result['followup_on14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on15'])?$select_result['followup_on15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['followup_on16'])?$select_result['followup_on16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">SERUM ESTRADIOL  (E2) LEVEL</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level1'])?$select_result['serum_e2_level1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level2'])?$select_result['serum_e2_level2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level3'])?$select_result['serum_e2_level3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level4'])?$select_result['serum_e2_level4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level5'])?$select_result['serum_e2_level5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level6'])?$select_result['serum_e2_level6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level7'])?$select_result['serum_e2_level7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level8'])?$select_result['serum_e2_level8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level9'])?$select_result['serum_e2_level9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level10'])?$select_result['serum_e2_level10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level11'])?$select_result['serum_e2_level11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level12'])?$select_result['serum_e2_level12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level13'])?$select_result['serum_e2_level13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level14'])?$select_result['serum_e2_level14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level15'])?$select_result['serum_e2_level15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_e2_level16'])?$select_result['serum_e2_level16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">SERUM LH LEVEL</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level1'])?$select_result['serum_lh_level1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level2'])?$select_result['serum_lh_level2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level3'])?$select_result['serum_lh_level3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level4'])?$select_result['serum_lh_level4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level5'])?$select_result['serum_lh_level5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level6'])?$select_result['serum_lh_level6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level7'])?$select_result['serum_lh_level7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level8'])?$select_result['serum_lh_level8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level9'])?$select_result['serum_lh_level9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level10'])?$select_result['serum_lh_level10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level11'])?$select_result['serum_lh_level11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level12'])?$select_result['serum_lh_level12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level13'])?$select_result['serum_lh_level13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level14'])?$select_result['serum_lh_level14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level15'])?$select_result['serum_lh_level15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_lh_level16'])?$select_result['serum_lh_level16']:""; ?></td>
			</tr>
			<tr>
				<td style="border:1px solid #cdcdcd;">SERUM PROGESTERONE LEVEL</td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level1'])?$select_result['serum_progesterone_level1']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level2'])?$select_result['serum_progesterone_level2']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level3'])?$select_result['serum_progesterone_level3']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level4'])?$select_result['serum_progesterone_level4']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level5'])?$select_result['serum_progesterone_level5']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level6'])?$select_result['serum_progesterone_level6']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level7'])?$select_result['serum_progesterone_level7']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level8'])?$select_result['serum_progesterone_level8']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level9'])?$select_result['serum_progesterone_level9']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level10'])?$select_result['serum_progesterone_level10']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level11'])?$select_result['serum_progesterone_level11']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level12'])?$select_result['serum_progesterone_level12']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level13'])?$select_result['serum_progesterone_level13']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level14'])?$select_result['serum_progesterone_level14']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level15'])?$select_result['serum_progesterone_level15']:""; ?></td>
				<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['serum_progesterone_level16'])?$select_result['serum_progesterone_level16']:""; ?></td>
			</tr>
		</table>
	
	<table  class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<td style="border:1px solid #cdcdcd;">DOCTOR</td>
			<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?></td>
			<td style="border:1px solid #cdcdcd;">COUNSELLOR</td>
			<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['counsellor'])?$select_result['counsellor']:""; ?></td>
			<td style="border:1px solid #cdcdcd;">NURSE</td>
			<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?> </td>
		</tr>
	</table>
	<!-- /.card-body -->




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