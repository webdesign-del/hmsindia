<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        
       if(!empty($_FILES['upload_photo_0']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['upload_photo_0']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['upload_photo_0']['tmp_name'], $destination.$NewImageName);
			$_POST['upload_photo_0'] = $transaction_img;
		}
        
       
		if(!empty($_FILES['upload_photo_1']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['upload_photo_1']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['upload_photo_1']['tmp_name'], $destination.$NewImageName);
			$_POST['upload_photo_1'] = $transaction_img;
		}
        
        
		if(!empty($_FILES['upload_photo_2']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['upload_photo_2']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['upload_photo_2']['tmp_name'], $destination.$NewImageName);
			$_POST['upload_photo_2'] = $transaction_img;
		}
        
		if(!empty($_FILES['upload_photo_3']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['upload_photo_3']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['upload_photo_3']['tmp_name'], $destination.$NewImageName);
			$_POST['upload_photo_3'] = $transaction_img;
		}
        
		if(!empty($_FILES['upload_photo_4']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['upload_photo_4']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['upload_photo_4']['tmp_name'], $destination.$NewImageName);
			$_POST['upload_photo_4'] = $transaction_img;
		}
        
		if(!empty($_FILES['upload_photo_5']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['upload_photo_5']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['upload_photo_5']['tmp_name'], $destination.$NewImageName);
			$_POST['upload_photo_5'] = $transaction_img;
		}

        $select_query = "SELECT * FROM `embryo_record` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `embryo_record` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE embryo_record SET ";
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
    $select_query = "SELECT * FROM `embryo_record` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
    			<div class="container2 red-field form mt-5 mb-5">
				<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OOCYTE EMBRYO RECORD SHEET TILL D3</h3></td>
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
					        		<th colspan="3" style="background: #FFC000">Day O (OPU)</th>
					        		<th colspan="6" style="background: #F4B083">Day 1</th>
					        		<th colspan="4" style="background: #C5E0B3">Day 2</th>
					        		<th colspan="3" style="background: #8EAADB">Day 3</th>
					        		<!--<th style="background: #548135">Day 4</th>
					        		<th colspan="2" style="background: #AEABAB">Day 5</th>
					        		<th colspan="7" style="background: #F2F2F2">Freezing</th>
					        		<th colspan="6" style="background: #C5E0B3">Thawing</th>
					        		<th colspan="2" style="background: #FEF2CB">LAH</th>-->
					        		<th colspan="2" style="background: #FEF2CB">FATE</th>
									<th colspan="2" style="background: #F7CAAC">ADD ONS USED AT THE TIME OF TRANSFER</th>
					        		<th colspan="2" style="background: #8EAADB">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Date: <input type="date" value="<?php echo isset($select_result['date0'])?$select_result['date0']:""; ?>"  name="date0"></th>
					        		<th colspan="6" style="background: #F4B083">Date: <input type="date" value="<?php echo isset($select_result['date1'])?$select_result['date1']:""; ?>"  name="date1"></th>
					        		<th colspan="4" style="background: #C5E0B3">Date: <input type="date" value="<?php echo isset($select_result['date2'])?$select_result['date2']:""; ?>"  name="date2"></th>
					        		<th colspan="3" style="background: #8EAADB">Date: <input type="date" value="<?php echo isset($select_result['date3'])?$select_result['date3']:""; ?>"  name="date3"></th>
					        		<!--<th colspan="1" style="background: #548135">Date: <input type="date" value="<?php echo isset($select_result['date4'])?$select_result['date4']:""; ?>"  name="date4"></th>
					        		<th colspan="2" style="background: #AEABAB">Date: <input type="date" value="<?php echo isset($select_result['date5'])?$select_result['date5']:""; ?>"  name="date5"></th>
					        		<th colspan="7" style="background: #F2F2F2">Date: <input type="date" value="<?php echo isset($select_result['date6'])?$select_result['date6']:""; ?>"  name="date6"></th>
					        		<th colspan="6" style="background: #C5E0B3">Date: <input type="date" value="<?php echo isset($select_result['date7'])?$select_result['date7']:""; ?>"  name="date7"></th>
					        		--><th colspan="2" style="background: #FEF2CB">Date: <input type="date" value="<?php echo isset($select_result['date8'])?$select_result['date8']:""; ?>"  name="date8"></th>
					        		<th colspan="2" style="background: #F7CAAC">Date: <input type="date" value="<?php echo isset($select_result['date9'])?$select_result['date9']:""; ?>"  name="date9"></th>
					        		<th colspan="2" style="background: #8EAADB">Date: <input type="date" value="<?php echo isset($select_result['date10'])?$select_result['date10']:""; ?>"  name="date10"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Time: <input type="time" value="<?php echo isset($select_result['time0'])?$select_result['time0']:""; ?>"  name="time0"></th>
					        		<th colspan="6" style="background: #F4B083">Diss.Time: <input type="time" value="<?php echo isset($select_result['time1'])?$select_result['time1']:""; ?>"  name="time1"></th>
					        		<th colspan="4" style="background: #C5E0B3">Time: <input type="time" value="<?php echo isset($select_result['time2'])?$select_result['time2']:""; ?>"  name="time2"></th>
					        		<th colspan="3" style="background: #8EAADB">Time: <input type="time" value="<?php echo isset($select_result['time3'])?$select_result['time3']:""; ?>"  name="time3"></th>
					        		<!--<th style="background: #548135">Time: <input type="time" value="<?php echo isset($select_result['time4'])?$select_result['time4']:""; ?>"  name="time4"></th>
					        		<th colspan="2" style="background: #AEABAB">Time: <input type="time" value="<?php echo isset($select_result['time5'])?$select_result['time5']:""; ?>"  name="time5"></th>
					        		<th colspan="7" style="background: #F2F2F2">Time: <input type="time" value="<?php echo isset($select_result['time6'])?$select_result['time6']:""; ?>"  name="time6"></th>
					        		<th colspan="6" style="background: #C5E0B3">Time: <input type="time" value="<?php echo isset($select_result['time7'])?$select_result['time7']:""; ?>"  name="time7"></th>
					        		--><th colspan="2" style="background: #FEF2CB">Time: <input type="time" value="<?php echo isset($select_result['time8'])?$select_result['time8']:""; ?>"  name="time8"></th>
					        		<th colspan="2" style="background: #F7CAAC">Time: <input type="time" value="<?php echo isset($select_result['time9'])?$select_result['time9']:""; ?>"  name="time9"></th>
					        		<th colspan="2" style="background: #8EAADB">Time: <input type="time" value="<?php echo isset($select_result['time10'])?$select_result['time10']:""; ?>"  name="time10"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Emb: <input type="text" value="<?php echo isset($select_result['emb'])?$select_result['emb']:""; ?>"  maxlength="20" name="emb"  ></th>
					        		<th colspan="6" style="background: #F4B083">Score Time: <input type="time" value="<?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?>"  name="score_time" ></th>
					        		<th colspan="4" style="background: #C5E0B3">Hrs: <input type="time" value="<?php echo isset($select_result['hrs0'])?$select_result['hrs0']:""; ?>"  name="hrs0"></th>
					        		<th colspan="3" style="background: #8EAADB">Hrs: <input type="time" value="<?php echo isset($select_result['hrs1'])?$select_result['hrs1']:""; ?>"  name="hrs1"></th>
					        		<!--<th style="background: #548135">Hrs: <input type="time" value="<?php echo isset($select_result['hrs2'])?$select_result['hrs2']:""; ?>"  name="hrs2"></th>
					        		<th colspan="2" style="background: #AEABAB">Hrs: <input type="time" value="<?php echo isset($select_result['hrs3'])?$select_result['hrs3']:""; ?>"  name="hrs3"></th>
					        		<th colspan="7" style="background: #F2F2F2">Hrs: <input type="time" value="<?php echo isset($select_result['hrs4'])?$select_result['hrs4']:""; ?>"  name="hrs4"></th>
					        		<th colspan="6" style="background: #C5E0B3">Hrs: <input type="time" value="<?php echo isset($select_result['hrs5'])?$select_result['hrs5']:""; ?>"  name="hrs5"></th>
					        		--><th colspan="2" style="background: #FEF2CB">Hrs: <input type="time" value="<?php echo isset($select_result['hrs6'])?$select_result['hrs6']:""; ?>"  name="hrs6"></th>
					        		<th colspan="2" style="background: #F7CAAC">Hrs: <input type="time" value="<?php echo isset($select_result['hrs7'])?$select_result['hrs7']:""; ?>"  name="hrs7"></th>
					        		<th colspan="2" style="background: #8EAADB">Hrs: <input type="time" value="<?php echo isset($select_result['hrs8'])?$select_result['hrs8']:""; ?>"  name="hrs8"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Dr: <input type="text" value="<?php echo isset($select_result['dr'])?$select_result['dr']:""; ?>"  maxlength="20" name="dr"  ></th>
					        		<th colspan="6" style="background: #F4B083">Emb: <input type="text" value="<?php echo isset($select_result['emb0'])?$select_result['emb0']:""; ?>"  maxlength="20" name="emb0"  ></th>
					        		<th colspan="4" style="background: #C5E0B3">Emb: <input type="text" value="<?php echo isset($select_result['emb1'])?$select_result['emb1']:""; ?>"  maxlength="20" name="emb1"  ></th>
					        		<th colspan="3" style="background: #8EAADB">Emb: <input type="text" value="<?php echo isset($select_result['emb2'])?$select_result['emb2']:""; ?>"  maxlength="20" name="emb2"  ></th>
					        		<!--<th style="background: #548135">Emb: <input type="text" value="<?php echo isset($select_result['emb3'])?$select_result['emb3']:""; ?>"  maxlength="20" name="emb3"  ></th>
					        		<th colspan="2" style="background: #AEABAB">Emb: <input type="text" value="<?php echo isset($select_result['emb4'])?$select_result['emb4']:""; ?>"  maxlength="20" name="emb4"  ></th>
					        		<th colspan="7" style="background: #F2F2F2">Emb: <input type="text" value="<?php echo isset($select_result['emb5'])?$select_result['emb5']:""; ?>"  maxlength="20" name="emb5"  ></th>
					        		<th colspan="6" style="background: #C5E0B3">Emb: <input type="text" value="<?php echo isset($select_result['emb6'])?$select_result['emb6']:""; ?>"  maxlength="20" name="emb6"  ></th>
					        		--><th colspan="2" style="background: #FEF2CB">Emb: <input type="text" value="<?php echo isset($select_result['emb7'])?$select_result['emb7']:""; ?>"  maxlength="20" name="emb7"  ></th>
					        		<th colspan="2" style="background: #F7CAAC">Emb: <input type="text" value="<?php echo isset($select_result['emb8'])?$select_result['emb8']:""; ?>"  maxlength="20" name="emb8"  ></th>
					        		<th colspan="2" style="background: #8EAADB">Emb: <input type="text" value="<?php echo isset($select_result['emb9'])?$select_result['emb9']:""; ?>"  maxlength="20" name="emb9"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="background: #FFC000">Witness: <input type="text" value="<?php echo isset($select_result['witness0'])?$select_result['witness0']:""; ?>"  maxlength="20" name="witness0"  ></th>
					        		<th colspan="6" style="background: #F4B083">Witness: <input type="text" value="<?php echo isset($select_result['witness1'])?$select_result['witness1']:""; ?>"  maxlength="20" name="witness1"  ></th>
					        		<th colspan="4" style="background: #C5E0B3">Witness: <input type="text" value="<?php echo isset($select_result['witness2'])?$select_result['witness2']:""; ?>"  maxlength="20" name="witness2"  ></th>
					        		<th colspan="3" style="background: #8EAADB">Wit: <input type="text" value="<?php echo isset($select_result['wit0'])?$select_result['wit0']:""; ?>"  maxlength="20" name="wit0"  ></th>
					        		<!--<th style="background: #548135">Wit: <input type="text" value="<?php echo isset($select_result['wit1'])?$select_result['wit1']:""; ?>"  maxlength="20" name="wit1"  ></th>
					        		<th colspan="2" style="background: #AEABAB">Wit: <input type="text" value="<?php echo isset($select_result['wit2'])?$select_result['wit2']:""; ?>"  maxlength="20" name="wit2"  ></th>
					        		<th colspan="7" style="background: #F2F2F2">Wit: <input type="text" value="<?php echo isset($select_result['wit3'])?$select_result['wit3']:""; ?>"  maxlength="20" name="wit3"  ></th>
					        		<th colspan="6" style="background: #C5E0B3">Wit: <input type="text" value="<?php echo isset($select_result['wit4'])?$select_result['wit4']:""; ?>"  maxlength="20" name="wit4"  ></th>
					        		--><th colspan="2" style="background: #FEF2CB">Witness: <input type="text" value="<?php echo isset($select_result['wit5'])?$select_result['wit5']:""; ?>"  maxlength="20" name="wit5"  ></th>
					        		<th colspan="2" style="background: #F7CAAC">Wit: <input type="text" value="<?php echo isset($select_result['wit6'])?$select_result['wit6']:""; ?>"  maxlength="20" name="wit6"  ></th>
					        		<th colspan="2" style="background: #8EAADB">Wit: <input type="text" value="<?php echo isset($select_result['wit7'])?$select_result['wit7']:""; ?>"  maxlength="20" name="wit7"  ></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="padding: 0; background: #FFC000">
					        			<table style="width: 100%;">
					        				<tr>
					        					<td></td>
					        					<td>IVF</td>
					        					<td>ICSI</td>
					        				</tr>
					        				<tr>
					        					<td>No. COC inseminated</td>
					        					<td><input type="number" value="<?php echo isset($select_result['ivf_inseminated'])?$select_result['ivf_inseminated']:""; ?>"  min="0" name="ivf_inseminated"></td>
					        					<td><input type="number" value="<?php echo isset($select_result['icsi_inseminated'])?$select_result['icsi_inseminated']:""; ?>"  min="0" name="icsi_inseminated"></td>
					        				</tr>
					        				<tr>
					        					<td>All oocyte injected</td>
					        					<td><input type="number" value="<?php echo isset($select_result['ivf_injected'])?$select_result['ivf_injected']:""; ?>"  min="0" name="ivf_injected"></td>
					        					<td><input type="number" value="<?php echo isset($select_result['icsi_injected'])?$select_result['icsi_injected']:""; ?>"  min="0" name="icsi_injected"></td>
					        				</tr>
					        				<tr>
					        					<td>No. damaged or degenerated oocyte during ICSI</td>
					        					<td><input type="number" value="<?php echo isset($select_result['ivf_degenerated'])?$select_result['ivf_degenerated']:""; ?>"  min="0" name="ivf_degenerated"></td>
					        					<td><input type="number" value="<?php echo isset($select_result['icsi_degenerated'])?$select_result['icsi_degenerated']:""; ?>"  min="0" name="icsi_degenerated"></td>
					        				</tr>
					        				<tr>
					        					<td>No.M2 oocytes at ICSI</td>
					        					<td><input type="number" value="<?php echo isset($select_result['ivf_oocytes'])?$select_result['ivf_oocytes']:""; ?>"  min="0" name="ivf_oocytes"></td>
					        					<td><input type="number" value="<?php echo isset($select_result['icsi_oocytes'])?$select_result['icsi_oocytes']:""; ?>"  min="0" name="icsi_oocytes"></td>
					        				</tr>
					        				<tr>
					        					<td>No.M2 oocytes injected</td>
					        					<td><input type="number" value="<?php echo isset($select_result['ivf_oocytes_injected'])?$select_result['ivf_oocytes_injected']:""; ?>"  min="0" name="ivf_oocytes_injected"></td>
					        					<td><input type="number" value="<?php echo isset($select_result['icsi_oocytes_injected'])?$select_result['icsi_oocytes_injected']:""; ?>"  min="0" name="icsi_oocytes_injected"></td>
					        				</tr>
					        			</table>
					        		</th>
					        		<th colspan="6" style="padding: 0; background: #F4B083">
					        			<table style="width: 100%;">
					        				<tr>
												<td>Total No.of oocytes with no fertilization</td>
												<td><input type="number" value="<?php echo isset($select_result['no_fertilization'])?$select_result['no_fertilization']:""; ?>"  min="0" name="no_fertilization"></td>
											</tr>
											<tr>
												<td>No.1 PN oocyte</td>
												<td><input type="number" value="<?php echo isset($select_result['no_1_pn_oocyte'])?$select_result['no_1_pn_oocyte']:""; ?>"  min="0" name="no_1_pn_oocyte"></td>
											</tr>
											<tr>
												<td>No.oocytes with 2 PN and PB</td>
												<td><input type="number" value="<?php echo isset($select_result['oocyte_2pn_pb'])?$select_result['oocyte_2pn_pb']:""; ?>"  min="0" name="oocyte_2pn_pb"></td>
											</tr>
											<tr>
												<td>No.fertilized oocytes with >2PN</td>
												<td><input type="number" value="<?php echo isset($select_result['oocyte_2pn'])?$select_result['oocyte_2pn']:""; ?>"  min="0" name="oocyte_2pn"></td>
											</tr>
					        			</table>
					        		</th>
					        		<th colspan="4" style="padding: 0; background: #C5E0B3">
					        			<table style="width: 100%;">
					        				<tr>
					        					<td>Total no of cleaved embryos Day2</td>
					        					<td><input type="number" value="<?php echo isset($select_result['cleaved_embryos'])?$select_result['cleaved_embryos']:""; ?>"  min="0" name="cleaved_embryos"></td>
					        				</tr>
					        				<tr>
					        					<td>Total no.of 4 cell embryos Day2</td>
					        					<td><input type="number" value="<?php echo isset($select_result['cell_embryos_day2'])?$select_result['cell_embryos_day2']:""; ?>"  min="0" name="cell_embryos_day2"></td>
					        				</tr>
					        			</table>
					        		</th>
					        		<th colspan="3" style="padding: 0; background: #8EAADB">
					        			<table style="width: 100%;">
						        			<tr>
						        				<td>Total no. of 8 cell embryos day 3</td>
						        				<td><input type="number" value="<?php echo isset($select_result['cell_embryos_day3'])?$select_result['cell_embryos_day3']:""; ?>"  min="0" name="cell_embryos_day3"></td>
						        			</tr>
					        			</table>
					        		</th>
					        		<th colspan="2" style="background: #FEF2CB">
									<input type="checkbox" name="type" value="BLASTOCYST" <?php if(isset($select_result['type']) && $select_result['type'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> BLASTOCYST<br>
							        <input type="checkbox" name="type" value="TRANSFER" <?php if(isset($select_result['type']) && $select_result['type'] == "TRANSFER"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="type" value="FREEZING" <?php if(isset($select_result['type']) && $select_result['type'] == "FREEZING"){echo 'checked="checked"'; }?>> FREEZING<br>
									<input type="checkbox" name="type" value="GDEGENERATE" <?php if(isset($select_result['type']) && $select_result['type'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DEGENERATE<br>
							        <input type="checkbox" name="type" value="DISCARD" <?php if(isset($select_result['type']) && $select_result['type'] == "DISCARD"){echo 'checked="checked"'; }?>> DISCARD							       
									</th>
									<th colspan="2" style="background: #F7CAAC">
									<table style="width: 100%;">
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">TOTAL NO OF EMBRYOS ON WITH LAH DONE</td>
					        					<td  style="border:1px solid #cdcdcd;"><input type="text" name="cleaved_embryos" id="cleaved_embryos" value="<?php echo isset($select_result['cleaved_embryos'])?$select_result['cleaved_embryos']:""; ?>"></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">EMBRYO GLUE</td>
					        					<td  style="border:1px solid #cdcdcd;"><input type="text" name="cell_embryos_day2" id="cell_embryos_day2" value="<?php echo isset($select_result['cell_embryos_day2'])?$select_result['cell_embryos_day2']:""; ?>"></td>
					        				</tr>
					        			</table>
									</th>
					        		<th colspan="2" style="background: #8EAADB"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="padding: 0; background: #FFC000">
					        			<table style="width: 100%;">
					        				<tr><td>Hyal Time: <input type="time" value="<?php echo isset($select_result['hyal_time'])?$select_result['hyal_time']:""; ?>"  name="hyal_time"></td></tr>
					        				<tr><td>Emb. : <input type="text" value="<?php echo isset($select_result['hyal_time_emb'])?$select_result['hyal_time_emb']:""; ?>"  maxlength="20" name="hyal_time_emb"  ></td></tr>
					        				<tr><td>Inj Time :<input type="time" value="<?php echo isset($select_result['inj_time'])?$select_result['inj_time']:""; ?>"  name="inj_time"></td></tr>
					        				<tr><td>Emb. :<input type="text" value="<?php echo isset($select_result['inj_time_emb'])?$select_result['inj_time_emb']:""; ?>"  maxlength="20" name="inj_time_emb"  ></td></tr>
					        			</table>
					        		</th>
					        		<th colspan="6" style="background: #F4B083">
					        			<input type="file" value="<?php echo isset($select_result['upload_photo_0'])?$select_result['upload_photo_0']:""; ?>"  name="upload_photo_0">
                      					<a target="_blank" href="<?php echo !empty($select_result['upload_photo_0'])?$select_result['upload_photo_0']:"javascript:void(0)"; ?>">Download</a>
					        		</th>
					        		<th colspan="4" style="background: #C5E0B3">
					        			<input type="file" value="<?php echo isset($select_result['upload_photo_1'])?$select_result['upload_photo_1']:""; ?>"  name="upload_photo_1">
                      					<a target="_blank" href="<?php echo !empty($select_result['upload_photo_1'])?$select_result['upload_photo_1']:"javascript:void(0)"; ?>">Download</a>
					        		</th>
					        		<th colspan="3" style="background: #8EAADB">
					        			<input type="file" value="<?php echo isset($select_result['upload_photo_2'])?$select_result['upload_photo_2']:""; ?>"  name="upload_photo_2">
                      					<a target="_blank" href="<?php echo !empty($select_result['upload_photo_2'])?$select_result['upload_photo_2']:"javascript:void(0)"; ?>">Download</a>
					        		</th>
					        		<th colspan="2" style="background: #FEF2CB"></th><th colspan="2" style="background: #F7CAAC"></th>
					        		<th colspan="2" style="background: #8EAADB"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td style="background: #FFC000">No</td>
									<td style="background: #FFC000">Egg Quality</td>
									<td style="background: #FFC000">comment</td>
									<td style="background: #F4B083">1PN</td>
									<td style="background: #F4B083">2PN</td>
									<td style="background: #F4B083">3PN</td>
									<td style="background: #F4B083">Degenerate</td>
									<td style="background: #F4B083">2PB</td>
									<td style="background: #F4B083">comments</td>
									<td style="background: #C5E0B3">Cell</td>
									<td style="background: #C5E0B3">Grade</td>
									<td style="background: #C5E0B3">Clevage Time</td>
									<td style="background: #C5E0B3">Frag%</td>
									<td style="background: #8EAADB">Cell</td>
									<td style="background: #8EAADB">Grade</td>
									<td style="background: #8EAADB">Frag%</td>
								    <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC">Date</td>
									<td style="background: #F7CAAC">Reason</td>
									<td colspan="2" style="background: #8EAADB">Remarks</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_0'])?$select_result['no_0']:""; ?>"  min="0" name="no_0">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
									<input type="checkbox" name="egg_quality_0" value="M2" <?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0" value="M1" <?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0" value="GV" <?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
							        </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0'])?$select_result['comment_0']:""; ?>"  name="comment_0"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0'])?$select_result['pn1_0']:""; ?>"  min="0" name="pn1_0"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0'])?$select_result['pn2_0']:""; ?>"  min="0" name="pn2_0"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0'])?$select_result['pn3_0']:""; ?>"  min="0" name="pn3_0"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate'])?$select_result['degenerate']:""; ?>"  min="0" name="degenerate"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2'])?$select_result['pb2']:""; ?>"  min="0" name="pb2"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments'])?$select_result['comments']:""; ?>"  maxlength="20" name="comments"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0'])?$select_result['cell_0']:""; ?>"  maxlength="20" name="cell_0"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0'])?$select_result['grade_0']:""; ?>"  maxlength="20" name="grade_0"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time'])?$select_result['clevage_time']:""; ?>"  maxlength="20" name="clevage_time"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0'])?$select_result['frag_0']:""; ?>"  maxlength="20" name="frag_0"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1'])?$select_result['cell_1']:""; ?>"  maxlength="20" name="cell_1"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1'])?$select_result['grade_1']:""; ?>"  maxlength="20" name="grade_1"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1'])?$select_result['frag_1']:""; ?>"  maxlength="20" name="frag_1"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2'])?$select_result['date_2']:""; ?>"  name="date_2"></td>
									<td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason'])?$select_result['reason']:""; ?>"  maxlength="20" name="reason"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1'])?$select_result['empty_1']:""; ?>"  maxlength="20" name="empty_1"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_1'])?$select_result['no_1']:""; ?>"  min="0" name="no_1">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
							        	<input type="checkbox" name="egg_quality_0_1" value="M2" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
							        	<input type="checkbox" name="egg_quality_0_1" value="M1" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
							        	<input type="checkbox" name="egg_quality_0_1" value="GV" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
							        	<input type="checkbox" name="egg_quality_0_1" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
							       
							        </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_1'])?$select_result['comment_0_1']:""; ?>"  name="comment_0_1"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_1'])?$select_result['pn1_0_1']:""; ?>"  min="0" name="pn1_0_1"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_1'])?$select_result['pn2_0_1']:""; ?>"  min="0" name="pn2_0_1"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_1'])?$select_result['pn3_0_1']:""; ?>"  min="0" name="pn3_0_1"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_1'])?$select_result['degenerate_1']:""; ?>"  min="0" name="degenerate_1"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_1'])?$select_result['pb2_1']:""; ?>"  min="0" name="pb2_1"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_1'])?$select_result['comments_1']:""; ?>"  maxlength="20" name="comments_1"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_1'])?$select_result['cell_0_1']:""; ?>"  maxlength="20" name="cell_0_1"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_1'])?$select_result['grade_0_1']:""; ?>"  maxlength="20" name="grade_0_1"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_1'])?$select_result['clevage_time_1']:""; ?>"  maxlength="20" name="clevage_time_1"></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_1'])?$select_result['frag_0_1']:""; ?>"  maxlength="20" name="frag_0_1"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_1'])?$select_result['cell_1_1']:""; ?>"  maxlength="20" name="cell_1_1"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_1'])?$select_result['grade_1_1']:""; ?>"  maxlength="20" name="grade_1_1"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_1'])?$select_result['frag_1_1']:""; ?>"  maxlength="20" name="frag_1_1"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_1'])?$select_result['date_2_1']:""; ?>"  name="date_2_1"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_1'])?$select_result['reason_1']:""; ?>"  maxlength="20" name="reason_1"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_1'])?$select_result['empty_1_1']:""; ?>"  maxlength="20" name="empty_1_1"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_2'])?$select_result['no_2']:""; ?>"  min="0" name="no_2">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
							        	<input type="checkbox" name="egg_quality_0_2" value="M2" <?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
							        	<input type="checkbox" name="egg_quality_0_2" value="M1" <?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
							        	<input type="checkbox" name="egg_quality_0_2" value="GV" <?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
							        	<input type="checkbox" name="egg_quality_0_2" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
							       </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_2'])?$select_result['comment_0_2']:""; ?>"  name="comment_0_2"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_2'])?$select_result['pn1_0_2']:""; ?>"  min="0" name="pn1_0_2"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_2'])?$select_result['pn2_0_2']:""; ?>"  min="0" name="pn2_0_2"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_2'])?$select_result['pn3_0_2']:""; ?>"  min="0" name="pn3_0_2"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_2'])?$select_result['degenerate_2']:""; ?>"  min="0" name="degenerate_2"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_2'])?$select_result['pb2_2']:""; ?>"  min="0" name="pb2_2"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_2'])?$select_result['comments_2']:""; ?>"  maxlength="20" name="comments_2"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_2'])?$select_result['cell_0_2']:""; ?>"  maxlength="20" name="cell_0_2"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_2'])?$select_result['grade_0_2']:""; ?>"  maxlength="20" name="grade_0_2"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_2'])?$select_result['clevage_time_2']:""; ?>"  maxlength="20" name="clevage_time_2"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_2'])?$select_result['frag_0_2']:""; ?>"  maxlength="20" name="frag_0_2"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_2'])?$select_result['cell_1_2']:""; ?>"  maxlength="20" name="cell_1_2"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_2'])?$select_result['grade_1_2']:""; ?>"  maxlength="20" name="grade_1_2"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_2'])?$select_result['frag_1_2']:""; ?>"  maxlength="20" name="frag_1_2"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_2'])?$select_result['date_2_2']:""; ?>"  name="date_2_2"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_2'])?$select_result['reason_2']:""; ?>"  maxlength="20" name="reason_2"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_2'])?$select_result['empty_1_2']:""; ?>"  maxlength="20" name="empty_1_2"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_3'])?$select_result['no_3']:""; ?>"  min="0" name="no_3" >NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
									<input type="checkbox" name="egg_quality_0_3" value="M2" <?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_3" value="M1" <?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_3" value="GV" <?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_3" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
	 								</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_3'])?$select_result['comment_0_3']:""; ?>"  name="comment_0_3"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_3'])?$select_result['pn1_0_3']:""; ?>"  min="0" name="pn1_0_3"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_3'])?$select_result['pn2_0_3']:""; ?>"  min="0" name="pn2_0_3"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_3'])?$select_result['pn3_0_3']:""; ?>"  min="0" name="pn3_0_3"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_3'])?$select_result['degenerate_3']:""; ?>"  min="0" name="degenerate_3"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_3'])?$select_result['pb2_3']:""; ?>"  min="0" name="pb2_3"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_3'])?$select_result['comments_3']:""; ?>"  maxlength="20" name="comments_3"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_3'])?$select_result['cell_0_3']:""; ?>"  maxlength="20" name="cell_0_3"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_3'])?$select_result['grade_0_3']:""; ?>"  maxlength="20" name="grade_0_3"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_3'])?$select_result['clevage_time_3']:""; ?>"  maxlength="20" name="clevage_time_3"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_3'])?$select_result['frag_0_3']:""; ?>"  maxlength="20" name="frag_0_3"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_3'])?$select_result['cell_1_3']:""; ?>"  maxlength="20" name="cell_1_3"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_3'])?$select_result['grade_1_3']:""; ?>"  maxlength="20" name="grade_1_3"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_3'])?$select_result['frag_1_3']:""; ?>"  maxlength="20" name="frag_1_3"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_3'])?$select_result['date_2_3']:""; ?>"  name="date_2_3"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_3'])?$select_result['reason_3']:""; ?>"  maxlength="20" name="reason_3"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_3'])?$select_result['empty_1_3']:""; ?>"  maxlength="20" name="empty_1_3"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_4'])?$select_result['no_4']:""; ?>"  min="0" name="no_4">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
							        	<input type="checkbox"  name="egg_quality_0_4" value="M2" <?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
							        	<input type="checkbox"  name="egg_quality_0_4" value="M1" <?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
							        	<input type="checkbox"  name="egg_quality_0_4" value="GV" <?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
							        	<input type="checkbox"  name="egg_quality_0_4" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
							        </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_4'])?$select_result['comment_0_4']:""; ?>"  name="comment_0_4"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_4'])?$select_result['pn1_0_4']:""; ?>"  min="0" name="pn1_0_4" ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_4'])?$select_result['pn2_0_4']:""; ?>"  min="0" name="pn2_0_4"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_4'])?$select_result['pn3_0_4']:""; ?>"  min="0" name="pn3_0_4"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_4'])?$select_result['degenerate_4']:""; ?>"  min="0" name="degenerate_4"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_4'])?$select_result['pb2_4']:""; ?>"  min="0" name="pb2_4"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_4'])?$select_result['comments_4']:""; ?>"  maxlength="20" name="comments_4"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_4'])?$select_result['cell_0_4']:""; ?>"  maxlength="20" name="cell_0_4"   ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_4'])?$select_result['grade_0_4']:""; ?>"  maxlength="20" name="grade_0_4"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_4'])?$select_result['clevage_time_4']:""; ?>"  maxlength="20" name="clevage_time_4"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_4'])?$select_result['frag_0_4']:""; ?>"  maxlength="20" name="frag_0_4"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_4'])?$select_result['cell_1_4']:""; ?>"  maxlength="20" name="cell_1_4"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_4'])?$select_result['grade_1_4']:""; ?>"  maxlength="20" name="grade_1_4"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_4'])?$select_result['frag_1_4']:""; ?>"  maxlength="20" name="frag_1_4"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
								    <td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_4'])?$select_result['date_2_4']:""; ?>"  name="date_2_4"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_4'])?$select_result['reason_4']:""; ?>"  maxlength="20" name="reason_4"  ></td>
									 <td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_4'])?$select_result['empty_1_4']:""; ?>"  maxlength="20" name="empty_1_4"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_5'])?$select_result['no_5']:""; ?>"  min="0" name="no_5">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
							        <input type="checkbox" name="egg_quality_0_5" value="M2" <?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_5" value="M1" <?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_5" value="GV" <?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_5" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
								 </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_5'])?$select_result['comment_0_5']:""; ?>"  name="comment_0_5"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_5'])?$select_result['pn1_0_5']:""; ?>"  min="0" name="pn1_0_5"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_5'])?$select_result['pn2_0_5']:""; ?>"  min="0" name="pn2_0_5"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_5'])?$select_result['pn3_0_5']:""; ?>"  min="0" name="pn3_0_5"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_5'])?$select_result['degenerate_5']:""; ?>"  min="0" name="degenerate_5"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_5'])?$select_result['pb2_5']:""; ?>"  min="0" name="pb2_5"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_5'])?$select_result['comments_5']:""; ?>"  maxlength="20" name="comments_5"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_5'])?$select_result['cell_0_5']:""; ?>"  maxlength="20" name="cell_0_5"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_5'])?$select_result['grade_0_5']:""; ?>"  maxlength="20" name="grade_0_5"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_5'])?$select_result['clevage_time_5']:""; ?>"  maxlength="20" name="clevage_time_5"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_5'])?$select_result['frag_0_5']:""; ?>"  maxlength="20" name="frag_0_5"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_5'])?$select_result['cell_1_5']:""; ?>"  maxlength="20" name="cell_1_5"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_5'])?$select_result['grade_1_5']:""; ?>"  maxlength="20" name="grade_1_5"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_5'])?$select_result['frag_1_5']:""; ?>"  maxlength="20" name="frag_1_5"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_5'])?$select_result['date_2_5']:""; ?>"  name="date_2_5"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_5'])?$select_result['reason_5']:""; ?>"  maxlength="20" name="reason_5"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_5'])?$select_result['empty_1_5']:""; ?>"  maxlength="20" name="empty_1_5"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_6'])?$select_result['no_6']:""; ?>"  min="0" name="no_6">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
							        	<input type="checkbox"  name="egg_quality_0_6" value="M2" <?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
							        	<input type="checkbox"  name="egg_quality_0_6" value="M1" <?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
							        	<input type="checkbox"  name="egg_quality_0_6" value="GV" <?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
							        	<input type="checkbox"  name="egg_quality_0_6" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
							        </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_6'])?$select_result['comment_0_6']:""; ?>"  name="comment_0_6"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_6'])?$select_result['pn1_0_6']:""; ?>"  min="0" name="pn1_0_6"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_6'])?$select_result['pn2_0_6']:""; ?>"  min="0" name="pn2_0_6"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_6'])?$select_result['pn3_0_6']:""; ?>"  min="0" name="pn3_0_6"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_6'])?$select_result['degenerate_6']:""; ?>"  min="0" name="degenerate_6"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_6'])?$select_result['pb2_6']:""; ?>"  min="0" name="pb2_6"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_6'])?$select_result['comments_6']:""; ?>"  maxlength="20" name="comments_6"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_6'])?$select_result['cell_0_6']:""; ?>"  maxlength="20" name="cell_0_6"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_6'])?$select_result['grade_0_6']:""; ?>"  maxlength="20" name="grade_0_6"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_6'])?$select_result['clevage_time_6']:""; ?>"  maxlength="20" name="clevage_time_6"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_6'])?$select_result['frag_0_6']:""; ?>"  maxlength="20" name="frag_0_6"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_6'])?$select_result['cell_1_6']:""; ?>"  maxlength="20" name="cell_1_6"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_6'])?$select_result['grade_1_6']:""; ?>"  maxlength="20" name="grade_1_6"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_6'])?$select_result['frag_1_6']:""; ?>"  maxlength="20" name="frag_1_6"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_6'])?$select_result['date_2_6']:""; ?>"  name="date_2_6"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_6'])?$select_result['reason_6']:""; ?>"  maxlength="20" name="reason_6"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_6'])?$select_result['empty_1_6']:""; ?>"  maxlength="20" name="empty_1_6"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_7'])?$select_result['no_7']:""; ?>"  min="0" name="no_7">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
							        	<input type="checkbox"  name="egg_quality_0_7" value="M2" <?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
							        	<input type="checkbox"  name="egg_quality_0_7" value="M1" <?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
							        	<input type="checkbox"  name="egg_quality_0_7" value="GV" <?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
							        	<input type="checkbox"  name="egg_quality_0_7" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
							        </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_7'])?$select_result['comment_0_7']:""; ?>"  name="comment_0_7"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_7'])?$select_result['pn1_0_7']:""; ?>"  min="0" name="pn1_0_7"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_7'])?$select_result['pn2_0_7']:""; ?>"  min="0" name="pn2_0_7"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_7'])?$select_result['pn3_0_7']:""; ?>"  min="0" name="pn3_0_7"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_7'])?$select_result['degenerate_7']:""; ?>"  min="0" name="degenerate_7"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_7'])?$select_result['pb2_7']:""; ?>"  min="0" name="pb2_7"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_7'])?$select_result['comments_7']:""; ?>"  maxlength="20" name="comments_7"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_7'])?$select_result['cell_0_7']:""; ?>"  maxlength="20" name="cell_0_7"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_7'])?$select_result['grade_0_7']:""; ?>"  maxlength="20" name="grade_0_7"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_7'])?$select_result['clevage_time_7']:""; ?>"  maxlength="20" name="clevage_time_7"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_7'])?$select_result['frag_0_7']:""; ?>"  maxlength="20" name="frag_0_7"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_7'])?$select_result['cell_1_7']:""; ?>"  maxlength="20" name="cell_1_7"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_7'])?$select_result['grade_1_7']:""; ?>"  maxlength="20" name="grade_1_7"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_7'])?$select_result['frag_1_7']:""; ?>"  maxlength="20" name="frag_1_7"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_7'])?$select_result['date_2_7']:""; ?>"  name="date_2_7"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_7'])?$select_result['reason_7']:""; ?>"  maxlength="20" name="reason_7"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_7'])?$select_result['empty_1_7']:""; ?>"  maxlength="20" name="empty_1_7"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_8'])?$select_result['no_8']:""; ?>"  min="0" name="no_8">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">							        	
									<input type="checkbox" name="egg_quality_0_8" value="M2" <?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_8" value="M1" <?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_8" value="GV" <?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_8" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
									</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_8'])?$select_result['comment_0_8']:""; ?>"  name="comment_0_8"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_8'])?$select_result['pn1_0_8']:""; ?>"  min="0" name="pn1_0_8"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_8'])?$select_result['pn2_0_8']:""; ?>"  min="0" name="pn2_0_8"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_8'])?$select_result['pn3_0_8']:""; ?>"  min="0" name="pn3_0_8"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_8'])?$select_result['degenerate_8']:""; ?>"  min="0" name="degenerate_8"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_8'])?$select_result['pb2_8']:""; ?>"  min="0" name="pb2_8"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_8'])?$select_result['comments_8']:""; ?>"  maxlength="20" name="comments_8"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_8'])?$select_result['cell_0_8']:""; ?>"  maxlength="20" name="cell_0_8"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_8'])?$select_result['grade_0_8']:""; ?>"  maxlength="20" name="grade_0_8"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_8'])?$select_result['clevage_time_8']:""; ?>"  maxlength="20" name="clevage_time_8"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_8'])?$select_result['frag_0_8']:""; ?>"  maxlength="20" name="frag_0_8"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_8'])?$select_result['cell_1_8']:""; ?>"  maxlength="20" name="cell_1_8"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_8'])?$select_result['grade_1_8']:""; ?>"  maxlength="20" name="grade_1_8"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_8'])?$select_result['frag_1_8']:""; ?>"  maxlength="20" name="frag_1_8"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_8'])?$select_result['date_2_8']:""; ?>"  name="date_2_8"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_8'])?$select_result['reason_8']:""; ?>"  maxlength="20" name="reason_8"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_8'])?$select_result['empty_1_8']:""; ?>"  maxlength="20" name="empty_1_8"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_9'])?$select_result['no_9']:""; ?>"  min="0" name="no_9">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">	
									<input type="checkbox" name="egg_quality_0_9" value="M2" <?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_9" value="M1" <?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_9" value="GV" <?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_9" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
							        </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_9'])?$select_result['comment_0_9']:""; ?>"  name="comment_0_9"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_9'])?$select_result['pn1_0_9']:""; ?>"  min="0" name="pn1_0_9"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_9'])?$select_result['pn2_0_9']:""; ?>"  min="0" name="pn2_0_9"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_9'])?$select_result['pn3_0_9']:""; ?>"  min="0" name="pn3_0_9"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_9'])?$select_result['degenerate_9']:""; ?>"  min="0" name="degenerate_9"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_9'])?$select_result['pb2_9']:""; ?>"  min="0" name="pb2_9"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_9'])?$select_result['comments_9']:""; ?>"  maxlength="20" name="comments_9"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_9'])?$select_result['cell_0_9']:""; ?>"  maxlength="20" name="cell_0_9"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_9'])?$select_result['grade_0_9']:""; ?>"  maxlength="20" name="grade_0_9"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_9'])?$select_result['clevage_time_9']:""; ?>"  maxlength="20" name="clevage_time_9"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_9'])?$select_result['frag_0_9']:""; ?>"  maxlength="20" name="frag_0_9"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_9'])?$select_result['cell_1_9']:""; ?>"  maxlength="20" name="cell_1_9"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_9'])?$select_result['grade_1_9']:""; ?>"  maxlength="20" name="grade_1_9"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_9'])?$select_result['frag_1_9']:""; ?>"  maxlength="20" name="frag_1_9"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_9'])?$select_result['date_2_9']:""; ?>"  name="date_2_9" ></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_9'])?$select_result['reason_9']:""; ?>"  maxlength="20" name="reason_9"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_9'])?$select_result['empty_1_9']:""; ?>"  maxlength="20" name="empty_1_9"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_10'])?$select_result['no_10']:""; ?>"  min="0" name="no_10">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">							    
									<input type="checkbox" name="egg_quality_0_10" value="M2" <?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_10" value="M1" <?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_10" value="GV" <?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_10" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
								   </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_10'])?$select_result['comment_0_10']:""; ?>"  name="comment_0_10"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_10'])?$select_result['pn1_0_10']:""; ?>"  min="0" name="pn1_0_10"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_10'])?$select_result['pn2_0_10']:""; ?>"  min="0" name="pn2_0_10"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_10'])?$select_result['pn3_0_10']:""; ?>"  min="0" name="pn3_0_10"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_10'])?$select_result['degenerate_10']:""; ?>"  min="0" name="degenerate_10"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_10'])?$select_result['pb2_10']:""; ?>"  min="0" name="pb2_10"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_10'])?$select_result['comments_10']:""; ?>"  maxlength="20" name="comments_10"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_10'])?$select_result['cell_0_10']:""; ?>"  maxlength="20" name="cell_0_10"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_10'])?$select_result['grade_0_10']:""; ?>"  maxlength="20" name="grade_0_10"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_10'])?$select_result['clevage_time_10']:""; ?>"  maxlength="20" name="clevage_time_10"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_10'])?$select_result['frag_0_10']:""; ?>"  maxlength="20" name="frag_0_10"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_10'])?$select_result['cell_1_10']:""; ?>"  maxlength="20" name="cell_1_10"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_10'])?$select_result['grade_1_10']:""; ?>"  maxlength="20" name="grade_1_10"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_10'])?$select_result['frag_1_10']:""; ?>"  maxlength="20" name="frag_1_10"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_10'])?$select_result['date_2_10']:""; ?>"  name="date_2_10"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_10'])?$select_result['reason_10']:""; ?>"  maxlength="20" name="reason_10"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_10'])?$select_result['empty_1_10']:""; ?>"  maxlength="20" name="empty_1_10"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_11'])?$select_result['no_11']:""; ?>"  min="0" name="no_11">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">							        
                                    <input type="checkbox" name="egg_quality_0_11" value="M2" <?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
                                    <input type="checkbox" name="egg_quality_0_11" value="M1" <?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
                                    <input type="checkbox" name="egg_quality_0_11" value="GV" <?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
                                    <input type="checkbox" name="egg_quality_0_11" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
								   </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_11'])?$select_result['comment_0_11']:""; ?>"  name="comment_0_11"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_11'])?$select_result['pn1_0_11']:""; ?>"  min="0" name="pn1_0_11"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_11'])?$select_result['pn2_0_11']:""; ?>"  min="0" name="pn2_0_11"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_11'])?$select_result['pn3_0_11']:""; ?>"  min="0" name="pn3_0_11"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_11'])?$select_result['degenerate_11']:""; ?>"  min="0" name="degenerate_11"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_11'])?$select_result['pb2_11']:""; ?>"  min="0" name="pb2_11"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_11'])?$select_result['comments_11']:""; ?>"  maxlength="20" name="comments_11"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_11'])?$select_result['cell_0_11']:""; ?>"  maxlength="20" name="cell_0_11"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_11'])?$select_result['grade_0_11']:""; ?>"  maxlength="20" name="grade_0_11"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_11'])?$select_result['clevage_time_11']:""; ?>"  maxlength="20" name="clevage_time_11"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_11'])?$select_result['frag_0_11']:""; ?>"  maxlength="20" name="frag_0_11"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_11'])?$select_result['cell_1_11']:""; ?>"  maxlength="20" name="cell_1_11"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_11'])?$select_result['grade_1_11']:""; ?>"  maxlength="20" name="grade_1_11"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_11'])?$select_result['frag_1_11']:""; ?>"  maxlength="20" name="frag_1_11"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_11'])?$select_result['date_2_11']:""; ?>"  name="date_2_11"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_11'])?$select_result['reason_11']:""; ?>"  maxlength="20" name="reason_11"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_11'])?$select_result['empty_1_11']:""; ?>"  maxlength="20" name="empty_1_11"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_12'])?$select_result['no_12']:""; ?>"  min="0" name="no_12">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
									<input type="checkbox" name="egg_quality_0_12" value="M2" <?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_12" value="M1" <?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_12" value="GV" <?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_12" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
									</td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_12'])?$select_result['comment_0_12']:""; ?>"  name="comment_0_12"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_12'])?$select_result['pn1_0_12']:""; ?>"  min="0" name="pn1_0_12"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_12'])?$select_result['pn2_0_12']:""; ?>"  min="0" name="pn2_0_12"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_12'])?$select_result['pn3_0_12']:""; ?>"  min="0" name="pn3_0_12"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_12'])?$select_result['degenerate_12']:""; ?>"  min="0" name="degenerate_12"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_12'])?$select_result['pb2_12']:""; ?>"  min="0" name="pb2_12"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_12'])?$select_result['comments_12']:""; ?>"  maxlength="20" name="comments_12"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_12'])?$select_result['cell_0_12']:""; ?>"  maxlength="20" name="cell_0_12"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_12'])?$select_result['grade_0_12']:""; ?>"  maxlength="20" name="grade_0_12"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_12'])?$select_result['clevage_time_12']:""; ?>"  maxlength="20" name="clevage_time_12"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_12'])?$select_result['frag_0_12']:""; ?>"  maxlength="20" name="frag_0_12"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_12'])?$select_result['cell_1_12']:""; ?>"  maxlength="20" name="cell_1_12"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_12'])?$select_result['grade_1_12']:""; ?>"  maxlength="20" name="grade_1_12"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_12'])?$select_result['frag_1_12']:""; ?>"  maxlength="20" name="frag_1_12"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_12'])?$select_result['date_2_12']:""; ?>"  name="date_2_12"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_12'])?$select_result['reason_12']:""; ?>"  maxlength="20" name="reason_12"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_12'])?$select_result['empty_1_12']:""; ?>"  maxlength="20" name="empty_1_12"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_13'])?$select_result['no_13']:""; ?>"  min="0" name="no_13">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">							        				
									<input type="checkbox" name="egg_quality_0_13" value="M2" <?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_13" value="M1" <?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_13" value="GV" <?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_13" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
    								 </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_13'])?$select_result['comment_0_13']:""; ?>"  name="comment_0_13"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_13'])?$select_result['pn1_0_13']:""; ?>"  min="0" name="pn1_0_13"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_13'])?$select_result['pn2_0_13']:""; ?>"  min="0" name="pn2_0_13"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_13'])?$select_result['pn3_0_13']:""; ?>"  min="0" name="pn3_0_13"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_13'])?$select_result['degenerate_13']:""; ?>"  min="0" name="degenerate_13"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_13'])?$select_result['pb2_13']:""; ?>"  min="0" name="pb2_13"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_13'])?$select_result['comments_13']:""; ?>"  maxlength="20" name="comments_13"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_13'])?$select_result['cell_0_13']:""; ?>"  maxlength="20" name="cell_0_13"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_13'])?$select_result['grade_0_13']:""; ?>"  maxlength="20" name="grade_0_13"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_13'])?$select_result['clevage_time_13']:""; ?>"  maxlength="20" name="clevage_time_13"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_13'])?$select_result['frag_0_13']:""; ?>"  maxlength="20" name="frag_0_13"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_13'])?$select_result['cell_1_13']:""; ?>"  maxlength="20" name="cell_1_13"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_13'])?$select_result['grade_1_13']:""; ?>"  maxlength="20" name="grade_1_13"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_13'])?$select_result['frag_1_13']:""; ?>"  maxlength="20" name="frag_1_13"  ></td>
	                                <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_13'])?$select_result['date_2_13']:""; ?>"  name="date_2_13"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_13'])?$select_result['reason_13']:""; ?>"  maxlength="20" name="reason_13"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_13'])?$select_result['empty_1_13']:""; ?>"  maxlength="20" name="empty_1_13"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_14'])?$select_result['no_14']:""; ?>"  min="0" name="no_14">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
							        	<input type="checkbox"  name="egg_quality_0_14" value="M2" <?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
							        	<input type="checkbox"  name="egg_quality_0_14" value="M1" <?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
							        	<input type="checkbox"  name="egg_quality_0_14" value="GV" <?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
							        	<input type="checkbox"  name="egg_quality_0_14" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
							        </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_14'])?$select_result['comment_0_14']:""; ?>"  name="comment_0_14"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_14'])?$select_result['pn1_0_14']:""; ?>"  min="0" name="pn1_0_14"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_14'])?$select_result['pn2_0_14']:""; ?>"  min="0" name="pn2_0_14"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_14'])?$select_result['pn3_0_14']:""; ?>"  min="0" name="pn3_0_14"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_14'])?$select_result['degenerate_14']:""; ?>"  min="0" name="degenerate_14"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_14'])?$select_result['pb2_14']:""; ?>"  min="0" name="pb2_14"  ></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_14'])?$select_result['comments_14']:""; ?>"  maxlength="20" name="comments_14"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_14'])?$select_result['cell_0_14']:""; ?>"  maxlength="20" name="cell_0_14"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_14'])?$select_result['grade_0_14']:""; ?>"  maxlength="20" name="grade_0_14"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_14'])?$select_result['clevage_time_14']:""; ?>"  maxlength="20" name="clevage_time_14"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_14'])?$select_result['frag_0_14']:""; ?>"  maxlength="20" name="frag_0_14"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_14'])?$select_result['cell_1_14']:""; ?>"  maxlength="20" name="cell_1_14"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_14'])?$select_result['grade_1_14']:""; ?>"  maxlength="20" name="grade_1_14"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_14'])?$select_result['frag_1_14']:""; ?>"  maxlength="20" name="frag_1_14"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_14'])?$select_result['date_2_14']:""; ?>"  name="date_2_14"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_14'])?$select_result['reason_14']:""; ?>"  maxlength="20" name="reason_14"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_14'])?$select_result['empty_1_14']:""; ?>"  maxlength="20" name="empty_1_14"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_15'])?$select_result['no_15']:""; ?>"  min="0" name="no_15">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">							        
									<input type="checkbox" name="egg_quality_0_15" value="M2" <?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_15" value="M1" <?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_15" value="GV" <?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_15" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
							        </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_15'])?$select_result['comment_0_15']:""; ?>"  name="comment_0_15"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_15'])?$select_result['pn1_0_15']:""; ?>"  min="0" name="pn1_0_15"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_15'])?$select_result['pn2_0_15']:""; ?>"  min="0" name="pn2_0_15"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_15'])?$select_result['pn3_0_15']:""; ?>"  min="0" name="pn3_0_15"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_15'])?$select_result['degenerate_15']:""; ?>"  min="0" name="degenerate_15"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_15'])?$select_result['pb2_15']:""; ?>"  min="0" name="pb2_15"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_15'])?$select_result['comments_15']:""; ?>"  maxlength="20" name="comments_15"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_15'])?$select_result['cell_0_15']:""; ?>"  maxlength="20" name="cell_0_15"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_15'])?$select_result['grade_0_15']:""; ?>"  maxlength="20" name="grade_0_15"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_15'])?$select_result['clevage_time_15']:""; ?>"  maxlength="20" name="clevage_time_15"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_15'])?$select_result['frag_0_15']:""; ?>"  maxlength="20" name="frag_0_15"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_15'])?$select_result['cell_1_15']:""; ?>"  maxlength="20" name="cell_1_15"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_15'])?$select_result['grade_1_15']:""; ?>"  maxlength="20" name="grade_1_15"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_15'])?$select_result['frag_1_15']:""; ?>"  maxlength="20" name="frag_1_15"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_15'])?$select_result['date_2_15']:""; ?>"  name="date_2_15"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_15'])?$select_result['reason_15']:""; ?>"  maxlength="20" name="reason_15"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_15'])?$select_result['empty_1_15']:""; ?>"  maxlength="20" name="empty_1_15"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_16'])?$select_result['no_16']:""; ?>"  min="0" name="no_16">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">							    
									<input type="checkbox" name="egg_quality_0_16" value="M2" <?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_16" value="M1" <?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_16" value="GV" <?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_16" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
							        </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_16'])?$select_result['comment_0_16']:""; ?>"  name="comment_0_16"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_16'])?$select_result['pn1_0_16']:""; ?>"  min="0" name="pn1_0_16"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_16'])?$select_result['pn2_0_16']:""; ?>"  min="0" name="pn2_0_16"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_16'])?$select_result['pn3_0_16']:""; ?>"  min="0" name="pn3_0_16"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_16'])?$select_result['degenerate_16']:""; ?>"  min="0" name="degenerate_16"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_16'])?$select_result['pb2_16']:""; ?>"  min="0" name="pb2_16"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_16'])?$select_result['comments_16']:""; ?>"  maxlength="20" name="comments_16"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_16'])?$select_result['cell_0_16']:""; ?>"  maxlength="20" name="cell_0_16"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_16'])?$select_result['grade_0_16']:""; ?>"  maxlength="20" name="grade_0_16"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_16'])?$select_result['clevage_time_16']:""; ?>"  maxlength="20" name="clevage_time_16"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_16'])?$select_result['frag_0_16']:""; ?>"  maxlength="20" name="frag_0_16"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_16'])?$select_result['cell_1_16']:""; ?>"  maxlength="20" name="cell_1_16"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_16'])?$select_result['grade_1_16']:""; ?>"  maxlength="20" name="grade_1_16"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_16'])?$select_result['frag_1_16']:""; ?>"  maxlength="20" name="frag_1_16"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_16'])?$select_result['date_2_16']:""; ?>"  name="date_2_16"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_16'])?$select_result['reason_16']:""; ?>"  maxlength="20" name="reason_16"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_16'])?$select_result['empty_1_16']:""; ?>"  maxlength="20" name="empty_1_16"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_17'])?$select_result['no_17']:""; ?>"  min="0" name="no_17">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">						
									<input type="checkbox" name="egg_quality_0_17" value="M2" <?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_17" value="M1" <?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_17" value="GV" <?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_17" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
	   							 </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_17'])?$select_result['comment_0_17']:""; ?>"  name="comment_0_17"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_17'])?$select_result['pn1_0_17']:""; ?>"  min="0" name="pn1_0_17"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_17'])?$select_result['pn2_0_17']:""; ?>"  min="0" name="pn2_0_17"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_17'])?$select_result['pn3_0_17']:""; ?>"  min="0" name="pn3_0_17"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_17'])?$select_result['degenerate_17']:""; ?>"  min="0" name="degenerate_17"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_17'])?$select_result['pb2_17']:""; ?>"  min="0" name="pb2_17"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_17'])?$select_result['comments_17']:""; ?>"  maxlength="20" name="comments_17"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_17'])?$select_result['cell_0_17']:""; ?>"  maxlength="20" name="cell_0_17"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_17'])?$select_result['grade_0_17']:""; ?>"  maxlength="20" name="grade_0_17"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_17'])?$select_result['clevage_time_17']:""; ?>"  maxlength="20" name="clevage_time_17"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_17'])?$select_result['frag_0_17']:""; ?>"  maxlength="20" name="frag_0_17"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_17'])?$select_result['cell_1_17']:""; ?>"  maxlength="20" name="cell_1_17"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_17'])?$select_result['grade_1_17']:""; ?>"  maxlength="20" name="grade_1_17"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_17'])?$select_result['frag_1_17']:""; ?>"  maxlength="20" name="frag_1_17"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_17'])?$select_result['date_2_17']:""; ?>"  name="date_2_17"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_17'])?$select_result['reason_17']:""; ?>"  maxlength="20" name="reason_17"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_17'])?$select_result['empty_1_17']:""; ?>"  maxlength="20" name="empty_1_17"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_18'])?$select_result['no_18']:""; ?>"  min="0" name="no_18">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
									<input type="checkbox" name="egg_quality_0_18" value="M2" <?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_18" value="M1" <?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_18" value="GV" <?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_18" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
								    </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_18'])?$select_result['comment_0_18']:""; ?>"  name="comment_0_18"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_18'])?$select_result['pn1_0_18']:""; ?>"  min="0" name="pn1_0_18"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_18'])?$select_result['pn2_0_18']:""; ?>"  min="0" name="pn2_0_18"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_18'])?$select_result['pn3_0_18']:""; ?>"  min="0" name="pn3_0_18"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_18'])?$select_result['degenerate_18']:""; ?>"  min="0" name="degenerate_18"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_18'])?$select_result['pb2_18']:""; ?>"  min="0" name="pb2_18"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_18'])?$select_result['comments_18']:""; ?>"  maxlength="20" name="comments_18"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_18'])?$select_result['cell_0_18']:""; ?>"  maxlength="20" name="cell_0_18"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_18'])?$select_result['grade_0_18']:""; ?>"  maxlength="20" name="grade_0_18"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_18'])?$select_result['clevage_time_18']:""; ?>"  maxlength="20" name="clevage_time_18"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_18'])?$select_result['frag_0_18']:""; ?>"  maxlength="20" name="frag_0_18"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_18'])?$select_result['cell_1_18']:""; ?>"  maxlength="20" name="cell_1_18"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_18'])?$select_result['grade_1_18']:""; ?>"  maxlength="20" name="grade_1_18"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_18'])?$select_result['frag_1_18']:""; ?>"  maxlength="20" name="frag_1_18"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_18'])?$select_result['date_2_18']:""; ?>"  name="date_2_18"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_18'])?$select_result['reason_18']:""; ?>"  maxlength="20" name="reason_18"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_18'])?$select_result['empty_1_18']:""; ?>"  maxlength="20" name="empty_1_18"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_19'])?$select_result['no_19']:""; ?>"  min="0" name="no_19">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">
									<input type="checkbox" name="egg_quality_0_19" value="M2" <?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_19" value="M1" <?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_19" value="GV" <?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_19" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
	                                </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_19'])?$select_result['comment_0_19']:""; ?>"  name="comment_0_19"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_19'])?$select_result['pn1_0_19']:""; ?>"  min="0" name="pn1_0_19"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_19'])?$select_result['pn2_0_19']:""; ?>"  min="0" name="pn2_0_19"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_19'])?$select_result['pn3_0_19']:""; ?>"  min="0" name="pn3_0_19"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_19'])?$select_result['degenerate_19']:""; ?>"  min="0" name="degenerate_19"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_19'])?$select_result['pb2_19']:""; ?>"  min="0" name="pb2_19"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_19'])?$select_result['comments_19']:""; ?>"  maxlength="20" name="comments_19"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_19'])?$select_result['cell_0_19']:""; ?>"  maxlength="20" name="cell_0_19"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_19'])?$select_result['grade_0_19']:""; ?>"  maxlength="20" name="grade_0_19"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_19'])?$select_result['clevage_time_19']:""; ?>"  maxlength="20" name="clevage_time_19"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_19'])?$select_result['frag_0_19']:""; ?>"  maxlength="20" name="frag_0_19"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_19'])?$select_result['cell_1_19']:""; ?>"  maxlength="20" name="cell_1_19"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_19'])?$select_result['grade_1_19']:""; ?>"  maxlength="20" name="grade_1_19"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_19'])?$select_result['frag_1_19']:""; ?>"  maxlength="20" name="frag_1_19"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_19'])?$select_result['date_2_19']:""; ?>"  name="date_2_19"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_19'])?$select_result['reason_19']:""; ?>"  maxlength="20" name="reason_19"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_19'])?$select_result['empty_1_19']:""; ?>"  maxlength="20" name="empty_1_19"  ></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td style="background: #FFC000"><input type="number" value="<?php echo isset($select_result['no_20'])?$select_result['no_20']:""; ?>"  min="0" name="no_20">NUMERAL DROP DOWN</td>
							        <td style="background: #FFC000">				        
									<input type="checkbox" name="egg_quality_0_20" value="M2" <?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "M2"){echo 'checked="checked"'; }?>> M2<br>
									<input type="checkbox" name="egg_quality_0_20" value="M1" <?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "M1"){echo 'checked="checked"'; }?>> M1<br>
									<input type="checkbox" name="egg_quality_0_20" value="GV" <?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "GV"){echo 'checked="checked"'; }?>> GV<br>
									<input type="checkbox" name="egg_quality_0_20" value="DEGENERATE" <?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "DEGENERATE"){echo 'checked="checked"'; }?>>DEGENERATE
								    </td>
							        <td style="background: #FFC000"><input type="text" value="<?php echo isset($select_result['comment_0_20'])?$select_result['comment_0_20']:""; ?>"  name="comment_0_20"  ></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn1_0_20'])?$select_result['pn1_0_20']:""; ?>"  min="0" name="pn1_0_20"> </td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn2_0_20'])?$select_result['pn2_0_20']:""; ?>"  min="0" name="pn2_0_20"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pn3_0_20'])?$select_result['pn3_0_20']:""; ?>"  min="0" name="pn3_0_20"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['degenerate_20'])?$select_result['degenerate_20']:""; ?>"  min="0" name="degenerate_20"></td>
							        <td style="background: #F4B083"><input type="number" value="<?php echo isset($select_result['pb2_20'])?$select_result['pb2_20']:""; ?>"  min="0" name="pb2_20"></td>
							        <td style="background: #F4B083"><input type="text" value="<?php echo isset($select_result['comments_20'])?$select_result['comments_20']:""; ?>"  maxlength="20" name="comments_20"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['cell_0_20'])?$select_result['cell_0_20']:""; ?>"  maxlength="20" name="cell_0_20"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['grade_0_20'])?$select_result['grade_0_20']:""; ?>"  maxlength="20" name="grade_0_20"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['clevage_time_20'])?$select_result['clevage_time_20']:""; ?>"  maxlength="20" name="clevage_time_20"  ></td>
							        <td style="background: #C5E0B3"><input type="text" value="<?php echo isset($select_result['frag_0_20'])?$select_result['frag_0_20']:""; ?>"  maxlength="20" name="frag_0_20"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['cell_1_20'])?$select_result['cell_1_20']:""; ?>"  maxlength="20" name="cell_1_20"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['grade_1_20'])?$select_result['grade_1_20']:""; ?>"  maxlength="20" name="grade_1_20"  ></td>
							        <td style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['frag_1_20'])?$select_result['frag_1_20']:""; ?>"  maxlength="20" name="frag_1_20"  ></td>
							        <th colspan="2" style="background: #FEF2CB"></th>
									<td style="background: #F7CAAC"><input type="date" value="<?php echo isset($select_result['date_2_20'])?$select_result['date_2_20']:""; ?>"  name="date_2_20"></td>
							        <td style="background: #F7CAAC"><input type="text" value="<?php echo isset($select_result['reason_20'])?$select_result['reason_20']:""; ?>"  maxlength="20" name="reason_20"  ></td>
									<td colspan="2" style="background: #8EAADB"><input type="text" value="<?php echo isset($select_result['empty_1_20'])?$select_result['empty_1_20']:""; ?>"  maxlength="20" name="empty_1_20"  ></td>
					        	</tr>
					        </thead>
					    </table>
					</div>
					<div class='table-responsive'>
						<table class="table table-bordered table-hover mt-2 table-sm red-field">
							<ul><li>Emb/Gr = Embryo/Grade</li></ul>
					    	<thead>
						        <th> Embryo Grading (Fragmentation): 1- Best </th>
					    	</thead>
					    	<thead>
					        	<th> Day 3 </th>
					         	<th> 1</th>
					          	<th>2 </th>
					           	<th>3 </th>
					           	<th>4</th>
					    	</thead>
					    	<thead>
					        	<th> Fragmentation</th>
					         	<th> No Fragmentation</th>
					          	<th>0-20%</th>
					           	<th>20-30% </th>
					           	<th>50-100%</th>
					    	</thead>
					    	<thead>
						        <th>Blastocyst Grade (Expansion): 4&A- Best,1 &D -Poor </th>
						    </thead>
					    	<thead>
					        	<th> Grade </th>
					         	<th> 1</th>
					          	<th>2 </th>
					           	<th>3 </th>
					           	<th>4</th>
					    	</thead>
					    	<thead>
					         	<th>ICM</th>
					          	<th>A</th>
					           	<th>B</th>
					           	<th>C</th>
					           	<th>D</th>
					    	</thead>
					    	<thead>
					         	<th>Trophoblast</th>
					          	<th>A</th>
					           	<th>B</th>
					           	<th>C</th>
					           	<th>D</th>
					    	</thead>
					    </table>
					</div>
				 
					<!-- <p class="mt-2">
						<span>UPLOAD PHOTO</span>
						<input type="file" value="<?php echo isset($select_result['upload'])?$select_result['upload']:""; ?>"  id="file" name="upload" multiple change = "onFileChange"/>
					</p> -->
					<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
					<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
				</div>
			</form>
			
			
	<!-- pRINT bUTTON -->		
			
			
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable prtable"  id="printtable"  style="display:none"> 
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">OOCYTE EMBRYO RECORD SHEET TILL D3</h3></td>
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
	<table class="table table-bordered table-hover mt-2 table-sm red-field" style="width:100%; border:1px solid #cdcdcd;"  >
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Day O (OPU)</th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Day 1</th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Day 2</th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Day 3</th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">FATE</th>
									<th colspan="2" style="border:1px solid #cdcdcd;">ADD ONS USED AT THE TIME OF TRANSFER</th>
									<th colspan="1" style="border:1px solid #cdcdcd;">REMARKS</th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date0'])?$select_result['date0']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date1'])?$select_result['date1']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date3'])?$select_result['date3']:""; ?></th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date8'])?$select_result['date8']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date9'])?$select_result['date9']:""; ?></th>
								    <th colspan="1" style="border:1px solid #cdcdcd;">Date: <?php echo isset($select_result['date10'])?$select_result['date10']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time0'])?$select_result['time0']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Diss.Time: <?php echo isset($select_result['time1'])?$select_result['time1']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time2'])?$select_result['time2']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time3'])?$select_result['time3']:""; ?></th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time8'])?$select_result['time8']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time9'])?$select_result['time9']:""; ?></th>
									<th colspan="1" style="border:1px solid #cdcdcd;">Time: <?php echo isset($select_result['time10'])?$select_result['time10']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb'])?$select_result['emb']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Score Time: <?php echo isset($select_result['score_time'])?$select_result['score_time']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs0'])?$select_result['hrs0']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs1'])?$select_result['hrs1']:""; ?></th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs6'])?$select_result['hrs6']:""; ?></th>
									<th colspan="2" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs7'])?$select_result['hrs7']:""; ?></th>
									<th colspan="1" style="border:1px solid #cdcdcd;">Hrs: <?php echo isset($select_result['hrs8'])?$select_result['hrs8']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Dr: <?php echo isset($select_result['dr'])?$select_result['dr']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb0'])?$select_result['emb0']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb1'])?$select_result['emb1']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb2'])?$select_result['emb2']:""; ?></th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb7'])?$select_result['emb7']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb8'])?$select_result['emb8']:""; ?></th>
									<th colspan="1" style="border:1px solid #cdcdcd;">Emb: <?php echo isset($select_result['emb9'])?$select_result['emb9']:""; ?></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness0'])?$select_result['witness0']:""; ?></th>
					        		<th colspan="6" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness1'])?$select_result['witness1']:""; ?></th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['witness2'])?$select_result['witness2']:""; ?></th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['wit0'])?$select_result['wit0']:""; ?></th>
					        		<th colspan="1" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['wit5'])?$select_result['wit5']:""; ?></th>
					        		<th colspan="2" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['wit6'])?$select_result['wit6']:""; ?></th>
									<th colspan="1" style="border:1px solid #cdcdcd;">Witness: <?php echo isset($select_result['wit7'])?$select_result['wit7']:""; ?></th>
								
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<th colspan="3" style="padding: 0; background: #FFC000">
					        			<table style="width: 100%;">
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;"></td>
					        					<td  style="border:1px solid #cdcdcd;">IVF</td>
					        					<td  style="border:1px solid #cdcdcd;">ICSI</td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">No. COC inseminated</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ivf_inseminated'])?$select_result['ivf_inseminated']:""; ?></td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['icsi_inseminated'])?$select_result['icsi_inseminated']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">All oocyte injected</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ivf_injected'])?$select_result['ivf_injected']:""; ?></td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['icsi_injected'])?$select_result['icsi_injected']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">No. damaged or degenerated oocyte during ICSI</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ivf_degenerated'])?$select_result['ivf_degenerated']:""; ?></td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['icsi_degenerated'])?$select_result['icsi_degenerated']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">No.M2 oocytes at ICSI</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ivf_oocytes'])?$select_result['ivf_oocytes']:""; ?></td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['icsi_oocytes'])?$select_result['icsi_oocytes']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">No.M2 oocytes injected</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['ivf_oocytes_injected'])?$select_result['ivf_oocytes_injected']:""; ?></td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['icsi_oocytes_injected'])?$select_result['icsi_oocytes_injected']:""; ?></td>
					        				</tr>
					        			</table>
					        		</th>
					        		<th colspan="6" style="padding: 0; border:1px solid #cdcdcd;">
					        			<table style="width: 100%; border:1px solid #cdcdcd;">
					        				<tr>
												<td  style="border:1px solid #cdcdcd;">Total No.of oocytes with no fertilization</td>
												<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_fertilization'])?$select_result['no_fertilization']:""; ?></td>
											</tr>
											<tr>
												<td  style="border:1px solid #cdcdcd;">No.1 PN oocyte</td>
												<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_1_pn_oocyte'])?$select_result['no_1_pn_oocyte']:""; ?></td>
											</tr>
											<tr>
												<td  style="border:1px solid #cdcdcd;">No.oocytes with 2 PN and PB</td>
												<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_2pn_pb'])?$select_result['oocyte_2pn_pb']:""; ?></td>
											</tr>
											<tr>
												<td  style="border:1px solid #cdcdcd;">No.fertilized oocytes with >2PN</td>
												<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['oocyte_2pn'])?$select_result['oocyte_2pn']:""; ?></td>
											</tr>
					        			</table>
					        		</th>
					        		<th colspan="4" style="padding: 0; background: #C5E0B3">
					        			<table style="width: 100%;">
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">Total no of cleaved embryos Day2</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cleaved_embryos'])?$select_result['cleaved_embryos']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">Total no.of 4 cell embryos Day2</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_embryos_day2'])?$select_result['cell_embryos_day2']:""; ?></td>
					        				</tr>
					        			</table>
					        		</th>
					        		<th colspan="3" style="padding: 0;  border:1px solid #cdcdcd;">
					        			<table style="width: 100%;">
						        			<tr>
						        				<td  style="border:1px solid #cdcdcd;">Total no. of 8 cell embryos day 3</td>
						        				<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_embryos_day3'])?$select_result['cell_embryos_day3']:""; ?></td>
						        			</tr>
					        			</table>
					        		</th>
									<th colspan="2" style="padding: 0;  border:1px solid #cdcdcd;">
									<input type="checkbox" name="egg_quality_0_1" value="BLASTOCYST" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "BLASTOCYST"){echo 'checked="checked"'; }?>> BLASTOCYST<br>
							        <input type="checkbox" name="egg_quality_0_1" value="TRANSFER" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "TRANSFER"){echo 'checked="checked"'; }?>> TRANSFER<br>
							        <input type="checkbox" name="egg_quality_0_1" value="FREEZING" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "FREEZING"){echo 'checked="checked"'; }?>> FREEZING<br>
									<input type="checkbox" name="egg_quality_0_1" value="GDEGENERATE" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "DEGENERATE"){echo 'checked="checked"'; }?>> DEGENERATE<br>
							        <input type="checkbox" name="egg_quality_0_1" value="DISCARD" <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "DISCARD"){echo 'checked="checked"'; }?>> DISCARD							       
									</th>
									<th colspan="4" style="padding: 0;  border:1px solid #cdcdcd;">
									<table style="width: 100%;">
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">TOTAL NO OF EMBRYOS ON WITH LAH DONE</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cleaved_embryos'])?$select_result['cleaved_embryos']:""; ?></td>
					        				</tr>
					        				<tr>
					        					<td  style="border:1px solid #cdcdcd;">EMBRYO GLUE</td>
					        					<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_embryos_day2'])?$select_result['cell_embryos_day2']:""; ?></td>
					        				</tr>
					        			</table>
									</th>
					        		
					        	</tr>
					        </thead>
							<thead>
					        	<tr>
					        		<th colspan="3" style="padding: 0;border:1px solid #cdcdcd;">
					        			<table style="width:100%; border:1px solid #cdcdcd;" >
					        				<tr><td  style="border:1px solid #cdcdcd;">Hyal Time: <?php echo isset($select_result['hyal_time'])?$select_result['hyal_time']:""; ?></td></tr>
					        				<tr><td  style="border:1px solid #cdcdcd;">Emb. : <?php echo isset($select_result['hyal_time_emb'])?$select_result['hyal_time_emb']:""; ?></td></tr>
					        				<tr><td  style="border:1px solid #cdcdcd;">Inj Time :<?php echo isset($select_result['inj_time'])?$select_result['inj_time']:""; ?></td></tr>
					        				<tr><td  style="border:1px solid #cdcdcd;">Emb. :<?php echo isset($select_result['inj_time_emb'])?$select_result['inj_time_emb']:""; ?></td></tr>
					        			</table>
					        		</th>
					        			<th colspan="6" style="border:1px solid #cdcdcd;">
									    <?php if(!empty($upload_photo_0)) {?>
				 						<img src="<?php echo $upload_photo_0;?>" style="width:100px; height:100px;">
										<?php } else {echo " ";}?>	
									</th>
					        		<th colspan="4" style="border:1px solid #cdcdcd;">
					        			<?php if(!empty($upload_photo_1)) {?>
										 <img src="<?php echo $upload_photo_1;?>" style="width:100px; height:100px;">
										<?php } else {echo " ";}?>	
										</th>
					        		<th colspan="3" style="border:1px solid #cdcdcd;">
					        				<?php if(!empty($upload_photo_2)) {?>
				 							<img src="<?php echo $upload_photo_2;?>" style="width:100px; height:100px;">
											<?php } else {echo " ";}?>
					        		</th>
					        		<th colspan="5" style="border:1px solid #cdcdcd;"></th>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
					        		<td  style="border:1px solid #cdcdcd;">No</td>
									<td  style="border:1px solid #cdcdcd;">Egg Quality</td>
									<td  style="border:1px solid #cdcdcd;">comment</td>
									<td  style="border:1px solid #cdcdcd;">1PN</td>
									<td  style="border:1px solid #cdcdcd;">2PN</td>
									<td  style="border:1px solid #cdcdcd;">3PN</td>
									<td  style="border:1px solid #cdcdcd;">Degenerate</td>
									<td  style="border:1px solid #cdcdcd;">2PB</td>
									<td  style="border:1px solid #cdcdcd;">comments</td>
									<td  style="border:1px solid #cdcdcd;">Cell</td>
									<td  style="border:1px solid #cdcdcd;">Grade</td>
									<td  style="border:1px solid #cdcdcd;">Clevage Time</td>
									<td  style="border:1px solid #cdcdcd;">Frag%</td>
									<td  style="border:1px solid #cdcdcd;">Cell</td>
									<td  style="border:1px solid #cdcdcd;">Grade</td>
									<td  style="border:1px solid #cdcdcd;">Frag%</td>
									<td  style="border:1px solid #cdcdcd;">Date</td>
									<td  style="border:1px solid #cdcdcd;">Reason</td>
									<td  colspan="2" style="border:1px solid #cdcdcd;">Remarks</td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_0'])?$select_result['no_0']:""; ?> NUMERAL DROP DOWN </td>
							        <td  style="border:1px solid #cdcdcd;">
									 <?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "M1"){echo 'M1'; }?> <br>
									<?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "GV"){echo 'GV'; }?><br>
									 <?php if(isset($select_result['egg_quality_0']) && $select_result['egg_quality_0'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
								    </td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0'])?$select_result['comment_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0'])?$select_result['pn1_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0'])?$select_result['pn2_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0'])?$select_result['pn3_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate'])?$select_result['degenerate']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2'])?$select_result['pb2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments'])?$select_result['comments']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0'])?$select_result['cell_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0'])?$select_result['grade_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time'])?$select_result['clevage_time']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0'])?$select_result['frag_0']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1'])?$select_result['cell_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1'])?$select_result['grade_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1'])?$select_result['frag_1']:""; ?></td>
								    <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2'])?$select_result['date_2']:""; ?></td>
                                    <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason'])?$select_result['reason']:""; ?></td>
									<td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1'])?$select_result['empty_1']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_1'])?$select_result['no_1']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">
							         <?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_1']) && $select_result['egg_quality_0_1'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
   									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_1'])?$select_result['comment_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_1'])?$select_result['pn1_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_1'])?$select_result['pn2_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_1'])?$select_result['pn3_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_1'])?$select_result['degenerate_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_1'])?$select_result['pb2_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_1'])?$select_result['comments_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_1'])?$select_result['cell_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_1'])?$select_result['grade_0_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_1'])?$select_result['clevage_time_1']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_1'])?$select_result['frag_0_1']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_1'])?$select_result['cell_1_1']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_1'])?$select_result['grade_1_1']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_1'])?$select_result['frag_1_1']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_1'])?$select_result['date_2_1']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_1'])?$select_result['reason_1']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_1'])?$select_result['empty_1_1']:""; ?></td>
                            	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_2'])?$select_result['no_2']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">							        	
									<?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_2']) && $select_result['egg_quality_0_2'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_2'])?$select_result['comment_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_2'])?$select_result['pn1_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_2'])?$select_result['pn2_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_2'])?$select_result['pn3_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_2'])?$select_result['degenerate_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_2'])?$select_result['pb2_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_2'])?$select_result['comments_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_2'])?$select_result['cell_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_2'])?$select_result['grade_0_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_2'])?$select_result['clevage_time_2']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_2'])?$select_result['frag_0_2']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_2'])?$select_result['cell_1_2']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_2'])?$select_result['grade_1_2']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_2'])?$select_result['frag_1_2']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_2'])?$select_result['date_2_2']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_2'])?$select_result['reason_2']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_2'])?$select_result['empty_1_2']:""; ?></td>
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_3'])?$select_result['no_3']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">
							        <?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_3']) && $select_result['egg_quality_0_3'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_3'])?$select_result['comment_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_3'])?$select_result['pn1_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_3'])?$select_result['pn2_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_3'])?$select_result['pn3_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_3'])?$select_result['degenerate_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_3'])?$select_result['pb2_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_3'])?$select_result['comments_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_3'])?$select_result['cell_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_3'])?$select_result['grade_0_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_3'])?$select_result['clevage_time_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_3'])?$select_result['frag_0_3']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_3'])?$select_result['cell_1_3']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_3'])?$select_result['grade_1_3']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_3'])?$select_result['frag_1_3']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_3'])?$select_result['date_2_3']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_3'])?$select_result['reason_3']:""; ?></td>
							        <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_3'])?$select_result['empty_1_3']:""; ?></td>
									</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_4'])?$select_result['no_4']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">							        	
									<?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_4']) && $select_result['egg_quality_0_4'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_4'])?$select_result['comment_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_4'])?$select_result['pn1_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_4'])?$select_result['pn2_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_4'])?$select_result['pn3_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_4'])?$select_result['degenerate_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_4'])?$select_result['pb2_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_4'])?$select_result['comments_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_4'])?$select_result['cell_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_4'])?$select_result['grade_0_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_4'])?$select_result['clevage_time_4']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_4'])?$select_result['frag_0_4']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_4'])?$select_result['cell_1_4']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_4'])?$select_result['grade_1_4']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_4'])?$select_result['frag_1_4']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_4'])?$select_result['date_2_4']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_4'])?$select_result['reason_4']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_4'])?$select_result['empty_1_4']:""; ?></td>								 
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_5'])?$select_result['no_5']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">							
									<?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_5']) && $select_result['egg_quality_0_5'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
								 	</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_5'])?$select_result['comment_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_5'])?$select_result['pn1_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_5'])?$select_result['pn2_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_5'])?$select_result['pn3_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_5'])?$select_result['degenerate_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_5'])?$select_result['pb2_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_5'])?$select_result['comments_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_5'])?$select_result['cell_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_5'])?$select_result['grade_0_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_5'])?$select_result['clevage_time_5']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_5'])?$select_result['frag_0_5']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_5'])?$select_result['cell_1_5']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_5'])?$select_result['grade_1_5']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_5'])?$select_result['frag_1_5']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_5'])?$select_result['date_2_5']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_5'])?$select_result['reason_5']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_5'])?$select_result['empty_1_5']:""; ?></td>								  
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_6'])?$select_result['no_6']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">
									<?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_6']) && $select_result['egg_quality_0_6'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
							        </td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_6'])?$select_result['comment_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_6'])?$select_result['pn1_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_6'])?$select_result['pn2_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_6'])?$select_result['pn3_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_6'])?$select_result['degenerate_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_6'])?$select_result['pb2_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_6'])?$select_result['comments_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_6'])?$select_result['cell_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_6'])?$select_result['grade_0_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_6'])?$select_result['clevage_time_6']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_6'])?$select_result['frag_0_6']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_6'])?$select_result['cell_1_6']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_6'])?$select_result['grade_1_6']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_6'])?$select_result['frag_1_6']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_6'])?$select_result['date_2_6']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_6'])?$select_result['reason_6']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_6'])?$select_result['empty_1_6']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_7'])?$select_result['no_7']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">						        
									<?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_7']) && $select_result['egg_quality_0_7'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
							        </td>
							        <td  style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['comment_0_7'])?$select_result['comment_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_7'])?$select_result['pn1_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_7'])?$select_result['pn2_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_7'])?$select_result['pn3_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_7'])?$select_result['degenerate_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_7'])?$select_result['pb2_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_7'])?$select_result['comments_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_7'])?$select_result['cell_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_7'])?$select_result['grade_0_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_7'])?$select_result['clevage_time_7']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_7'])?$select_result['frag_0_7']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_7'])?$select_result['cell_1_7']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_7'])?$select_result['grade_1_7']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_7'])?$select_result['frag_1_7']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_7'])?$select_result['date_2_7']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_7'])?$select_result['reason_7']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_7'])?$select_result['empty_1_7']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_8'])?$select_result['no_8']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">
							    	<?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_8']) && $select_result['egg_quality_0_8'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
								   </td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_8'])?$select_result['comment_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_8'])?$select_result['pn1_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_8'])?$select_result['pn2_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_8'])?$select_result['pn3_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_8'])?$select_result['degenerate_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_8'])?$select_result['pb2_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_8'])?$select_result['comments_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_8'])?$select_result['cell_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_8'])?$select_result['grade_0_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_8'])?$select_result['clevage_time_8']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_8'])?$select_result['frag_0_8']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_8'])?$select_result['cell_1_8']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_8'])?$select_result['grade_1_8']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_8'])?$select_result['frag_1_8']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_8'])?$select_result['date_2_8']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_8'])?$select_result['reason_8']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_8'])?$select_result['empty_1_8']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_9'])?$select_result['no_9']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">
									 <?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_9']) && $select_result['egg_quality_0_9'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_9'])?$select_result['comment_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_9'])?$select_result['pn1_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_9'])?$select_result['pn2_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_9'])?$select_result['pn3_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_9'])?$select_result['degenerate_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_9'])?$select_result['pb2_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_9'])?$select_result['comments_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_9'])?$select_result['cell_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_9'])?$select_result['grade_0_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_9'])?$select_result['clevage_time_9']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_9'])?$select_result['frag_0_9']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_9'])?$select_result['cell_1_9']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_9'])?$select_result['grade_1_9']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_9'])?$select_result['frag_1_9']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_9'])?$select_result['date_2_9']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_9'])?$select_result['reason_9']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_9'])?$select_result['empty_1_9']:""; ?></td>
					        	  </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_10'])?$select_result['no_10']:""; ?>NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">						    
 									<?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_10']) && $select_result['egg_quality_0_10'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
							   		</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_10'])?$select_result['comment_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_10'])?$select_result['pn1_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_10'])?$select_result['pn2_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_10'])?$select_result['pn3_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_10'])?$select_result['degenerate_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_10'])?$select_result['pb2_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_10'])?$select_result['comments_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_10'])?$select_result['cell_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_10'])?$select_result['grade_0_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_10'])?$select_result['clevage_time_10']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_10'])?$select_result['frag_0_10']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_10'])?$select_result['cell_1_10']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_10'])?$select_result['grade_1_10']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_10'])?$select_result['frag_1_10']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_10'])?$select_result['date_2_10']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_10'])?$select_result['reason_10']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_10'])?$select_result['empty_1_10']:""; ?></td>
					        	</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_11'])?$select_result['no_11']:""; ?> NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">							        
 									<?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "GV"){echo 'GV'; }?><br>
									 <?php if(isset($select_result['egg_quality_0_11']) && $select_result['egg_quality_0_11'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_11'])?$select_result['comment_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_11'])?$select_result['pn1_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_11'])?$select_result['pn2_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_11'])?$select_result['pn3_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_11'])?$select_result['degenerate_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_11'])?$select_result['pb2_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_11'])?$select_result['comments_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_11'])?$select_result['cell_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_11'])?$select_result['grade_0_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_11'])?$select_result['clevage_time_11']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_11'])?$select_result['frag_0_11']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_11'])?$select_result['cell_1_11']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_11'])?$select_result['grade_1_11']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_11'])?$select_result['frag_1_11']:""; ?></td>
							      <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_11'])?$select_result['date_2_11']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_11'])?$select_result['reason_11']:""; ?></td>
							       <td  colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_11'])?$select_result['empty_1_11']:""; ?></td>
					        	  </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_12'])?$select_result['no_12']:""; ?>NUMERAL DROP DOWN</td>
									<td  style="border:1px solid #cdcdcd;">
									<?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_12']) && $select_result['egg_quality_0_12'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							 	    <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_12'])?$select_result['comment_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_12'])?$select_result['pn1_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_12'])?$select_result['pn2_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;height:30px;"><?php echo isset($select_result['pn3_0_12'])?$select_result['pn3_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_12'])?$select_result['degenerate_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_12'])?$select_result['pb2_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_12'])?$select_result['comments_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_12'])?$select_result['cell_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_12'])?$select_result['grade_0_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_12'])?$select_result['clevage_time_12']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_12'])?$select_result['frag_0_12']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_12'])?$select_result['cell_1_12']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_12'])?$select_result['grade_1_12']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_12'])?$select_result['frag_1_12']:""; ?></td>
							      <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_12'])?$select_result['date_2_12']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_12'])?$select_result['reason_12']:""; ?></td>
							       <td colspan="2"  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_12'])?$select_result['empty_1_12']:""; ?></td>
					        	 </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_13'])?$select_result['no_13']:""; ?> NUMERAL DROP DOWN</td>
							        <td  style="border:1px solid #cdcdcd;">							        	
 									<?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_13']) && $select_result['egg_quality_0_13'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd; height:30px; height:30px;"><?php echo isset($select_result['comment_0_13'])?$select_result['comment_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_13'])?$select_result['pn1_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_13'])?$select_result['pn2_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_13'])?$select_result['pn3_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_13'])?$select_result['degenerate_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_13'])?$select_result['pb2_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_13'])?$select_result['comments_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_13'])?$select_result['cell_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_13'])?$select_result['grade_0_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_13'])?$select_result['clevage_time_13']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_13'])?$select_result['frag_0_13']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_13'])?$select_result['cell_1_13']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_13'])?$select_result['grade_1_13']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_13'])?$select_result['frag_1_13']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_13'])?$select_result['date_2_13']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_13'])?$select_result['reason_13']:""; ?></td>
							       <td colspan="2"  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_13'])?$select_result['empty_1_13']:""; ?></td>
					        	  </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_14'])?$select_result['no_14']:""; ?> NUMERAL DROP DOWN</td>
							       <td  style="border:1px solid #cdcdcd;">
							         <?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "GV"){echo 'GV'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_14']) && $select_result['egg_quality_0_14'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
							        </td>
									<td  style="border:1px solid #cdcdcd; height:30px;"><?php echo isset($select_result['comment_0_14'])?$select_result['comment_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_14'])?$select_result['pn1_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_14'])?$select_result['pn2_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_14'])?$select_result['pn3_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_14'])?$select_result['degenerate_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_14'])?$select_result['pb2_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_14'])?$select_result['comments_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_14'])?$select_result['cell_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_14'])?$select_result['grade_0_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_14'])?$select_result['clevage_time_14']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_14'])?$select_result['frag_0_14']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_14'])?$select_result['cell_1_14']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_14'])?$select_result['grade_1_14']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_14'])?$select_result['frag_1_14']:""; ?></td>
							      	<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_14'])?$select_result['date_2_14']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_14'])?$select_result['reason_14']:""; ?></td>
							       <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_14'])?$select_result['empty_1_14']:""; ?></td>					        	
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_15'])?$select_result['no_15']:""; ?> NUMERAL DROP DOWN </td>
							        <td  style="border:1px solid #cdcdcd;">
									 <?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_15']) && $select_result['egg_quality_0_15'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
							        <td  style="border:1px solid #cdcdcd; height:30px;"><?php echo isset($select_result['comment_0_15'])?$select_result['comment_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_15'])?$select_result['pn1_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_15'])?$select_result['pn2_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_15'])?$select_result['pn3_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_15'])?$select_result['degenerate_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_15'])?$select_result['pb2_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_15'])?$select_result['comments_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_15'])?$select_result['cell_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_15'])?$select_result['grade_0_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_15'])?$select_result['clevage_time_15']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_15'])?$select_result['frag_0_15']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_15'])?$select_result['cell_1_15']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_15'])?$select_result['grade_1_15']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_15'])?$select_result['frag_1_15']:""; ?></td>
							      	<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_15'])?$select_result['date_2_15']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_15'])?$select_result['reason_15']:""; ?></td>
							       <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_15'])?$select_result['empty_1_15']:""; ?></td>
					        	  </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_16'])?$select_result['no_16']:""; ?> NUMERAL DROP DOWN </td>
							        <td  style="border:1px solid #cdcdcd;">
									 <?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "M2"){echo 'M2'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_16']) && $select_result['egg_quality_0_16'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
							        </td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_16'])?$select_result['comment_0_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_16'])?$select_result['pn1_0_16']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_16'])?$select_result['pn2_0_16']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_16'])?$select_result['pn3_0_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_16'])?$select_result['degenerate_16']:""; ?> </td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_16'])?$select_result['pb2_16']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_16'])?$select_result['comments_16']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_16'])?$select_result['cell_0_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_16'])?$select_result['grade_0_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_16'])?$select_result['clevage_time_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_16'])?$select_result['frag_0_16']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_16'])?$select_result['cell_1_16']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_16'])?$select_result['grade_1_16']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_16'])?$select_result['frag_1_16']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_16'])?$select_result['date_2_16']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_8'])?$select_result['reason_16']:""; ?></td>
							        <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_16'])?$select_result['empty_1_16']:""; ?></td>				        	
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_17'])?$select_result['no_17']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;">					
 									<?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "M2"){echo 'M2'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "M1"){echo 'M1'; }?> <br>
 									<?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_17']) && $select_result['egg_quality_0_17'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
 									</td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_17'])?$select_result['comment_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_17'])?$select_result['pn1_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_17'])?$select_result['pn2_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_17'])?$select_result['pn3_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_17'])?$select_result['degenerate_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_17'])?$select_result['pb2_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_17'])?$select_result['comments_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_17'])?$select_result['cell_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_17'])?$select_result['grade_0_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_17'])?$select_result['clevage_time_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_17'])?$select_result['frag_0_17']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_17'])?$select_result['cell_1_17']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_17'])?$select_result['grade_1_17']:""; ?></td>
							       <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_17'])?$select_result['frag_1_17']:""; ?></td>
								   <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_17'])?$select_result['date_2_17']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_17'])?$select_result['reason_17']:""; ?></td>
							        <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_17'])?$select_result['empty_1_17']:""; ?></td>				        	
								   </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_18'])?$select_result['no_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;">	
									<?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "M2"){echo 'M2'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "M1"){echo 'M1'; }?><br>
									<?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_18']) && $select_result['egg_quality_0_18'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
								   </td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_18'])?$select_result['comment_0_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_18'])?$select_result['pn1_0_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_18'])?$select_result['pn2_0_18']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_18'])?$select_result['pn3_0_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_18'])?$select_result['degenerate_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_18'])?$select_result['pb2_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_18'])?$select_result['comments_18']:""; ?></td>
	  								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_18'])?$select_result['cell_0_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_18'])?$select_result['grade_0_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_18'])?$select_result['clevage_time_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_18'])?$select_result['frag_0_18']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_18'])?$select_result['cell_1_18']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_18'])?$select_result['grade_1_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_18'])?$select_result['frag_1_18']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_18'])?$select_result['date_2_18']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_18'])?$select_result['reason_18']:""; ?></td>
							        <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_18'])?$select_result['empty_1_18']:""; ?></td>				        	
								 </tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_19'])?$select_result['no_19']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;">
 									<?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "M2"){echo 'M2'; }?><br>
									<?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "M1"){echo 'M1'; }?><br>
									<?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "GV"){echo 'GV'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_19']) && $select_result['egg_quality_0_19'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
 									</td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_19'])?$select_result['comment_0_19']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_19'])?$select_result['pn1_0_19']:""; ?></td>
  									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_19'])?$select_result['pn2_0_19']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_19'])?$select_result['pn3_0_19']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_19'])?$select_result['degenerate_19']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_19'])?$select_result['pb2_19']:""; ?></td>
  									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_19'])?$select_result['comments_19']:""; ?></td>
  									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_19'])?$select_result['cell_0_19']:""; ?></td>
  									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_19'])?$select_result['grade_0_19']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_19'])?$select_result['clevage_time_19']:""; ?>"</td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_19'])?$select_result['frag_0_19']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_19'])?$select_result['cell_1_19']:""; ?>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_19'])?$select_result['grade_1_19']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_19'])?$select_result['frag_1_19']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_19'])?$select_result['date_2_19']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_19'])?$select_result['reason_19']:""; ?></td>
							        <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_19'])?$select_result['empty_1_19']:""; ?></td>				        	
								</tr>
					        </thead>
					        <thead>
					        	<tr>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_20'])?$select_result['no_20']:""; ?>NUMERAL DROP DOWN</td>
									 <td  style="border:1px solid #cdcdcd;">
									 <?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "M2"){echo 'M2'; }?><br>
 									<?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "M1"){echo 'M1'; }?><br>
									<?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "GV"){echo 'GV'; }?><br>
									<?php if(isset($select_result['egg_quality_0_20']) && $select_result['egg_quality_0_20'] == "DEGENERATE"){echo 'DEGENERATE'; }?>
									</td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comment_0_20'])?$select_result['comment_0_20']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn1_0_20'])?$select_result['pn1_0_20']:""; ?>  </td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn2_0_20'])?$select_result['pn2_0_20']:""; ?></td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pn3_0_20'])?$select_result['pn3_0_20']:""; ?>  </td>
	 								<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['degenerate_20'])?$select_result['degenerate_20']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['pb2_20'])?$select_result['pb2_20']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['comments_20'])?$select_result['comments_20']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_0_20'])?$select_result['cell_0_20']:""; ?>  </td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_0_20'])?$select_result['grade_0_20']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['clevage_time_20'])?$select_result['clevage_time_20']:""; ?></td>
 									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_0_20'])?$select_result['frag_0_20']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cell_1_20'])?$select_result['cell_1_20']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['grade_1_20'])?$select_result['grade_1_20']:""; ?></td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['frag_1_20'])?$select_result['frag_1_20']:""; ?>  </td>
									<td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2_20'])?$select_result['date_2_20']:""; ?></td>
							        <td  style="border:1px solid #cdcdcd;"><?php echo isset($select_result['reason_20'])?$select_result['reason_20']:""; ?></td>
							        <td colspan="2" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['empty_1_20'])?$select_result['empty_1_20']:""; ?></td>				        	
	</tr>
					        </thead>
					    </table>
	<table class="table table-bordered table-hover mt-2 table-sm red-field"  style="width:100%; border:1px solid #cdcdcd;">
							<ul><li>Emb/Gr = Embryo/Grade</li></ul>
					    	<thead>
						        <th  style="border:1px solid #cdcdcd;"> Embryo Grading (Fragmentation): 1- Best </th>
					    	</thead>
					    	<thead>
					        	<th  style="border:1px solid #cdcdcd;"> Day 3 </th>
					         	<th  style="border:1px solid #cdcdcd;"> 1</th>
					          	<th  style="border:1px solid #cdcdcd;">2 </th>
					           	<th  style="border:1px solid #cdcdcd;">3 </th>
					           	<th  style="border:1px solid #cdcdcd;">4</th>
					    	</thead>
					    	<thead>
					        	<th  style="border:1px solid #cdcdcd;"> Fragmentation</th>
					         	<th  style="border:1px solid #cdcdcd;"> No Fragmentation</th>
					          	<th  style="border:1px solid #cdcdcd;">0-20%</th>
					           	<th  style="border:1px solid #cdcdcd;">20-30% </th>
					           	<th  style="border:1px solid #cdcdcd;">50-100%</th>
					    	</thead>
					    	<thead>
						        <th  style="border:1px solid #cdcdcd;">Blastocyst Grade (Expansion): 4&A- Best,1 &D -Poor </th>
						    </thead>
					    	<thead>
					        	<th  style="border:1px solid #cdcdcd;"> Grade </th>
					         	<th  style="border:1px solid #cdcdcd;"> 1</th>
					          	<th  style="border:1px solid #cdcdcd;">2 </th>
					           	<th  style="border:1px solid #cdcdcd;">3 </th>
					           	<th  style="border:1px solid #cdcdcd;">4</th>
					    	</thead>
					    	<thead>
					         	<th  style="border:1px solid #cdcdcd;">ICM</th>
					          	<th  style="border:1px solid #cdcdcd;">A</th>
					           	<th  style="border:1px solid #cdcdcd;">B</th>
					           	<th  style="border:1px solid #cdcdcd;">C</th>
					           	<th  style="border:1px solid #cdcdcd;">D</th>
					    	</thead>
					    	<thead>
					         	<th  style="border:1px solid #cdcdcd;"> Trophoblast</th>
					          	<th  style="border:1px solid #cdcdcd;">A</th>
					           	<th  style="border:1px solid #cdcdcd;">B</th>
					           	<th  style="border:1px solid #cdcdcd;">C</th>
					           	<th  style="border:1px solid #cdcdcd;">D</th>
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