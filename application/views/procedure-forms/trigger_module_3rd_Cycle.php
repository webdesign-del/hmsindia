<?php
    // php code to Insert data into mysql database from input text
    if(isset($_POST['submit'])){
        unset($_POST['submit']);
        //var_dump($_POST);die;
		if(!empty($_FILES['upload']['tmp_name'])){
			$dest_path = $this->config->item('upload_path');
			$destination = $dest_path.'procedure-forms-uploads/';
			$NewImageName = rand(4,10000)."-".$_FILES['upload']['name'];
			$transaction_img = base_url().'assets/procedure-forms-uploads/'.$NewImageName;
			move_uploaded_file($_FILES['upload']['tmp_name'], $destination.$NewImageName);
			$_POST['upload'] = $transaction_img;
		}
        
       $select_query = "SELECT * FROM `trigger_module` WHERE patient_id='$patient_id' and receipt_number='$receipt_number' and ipid='$ipid'";
        $select_result = run_select_query($select_query); 
        if(empty($select_result)){
            // mysql query to insert data
            $query = "INSERT INTO `trigger_module` SET ";
            $sqlArr = array();
            foreach( $_POST as $key=> $value )
            {
              $sqlArr[] = " $key = '".addslashes($value)."'";
            }		
            $query .= implode(',' , $sqlArr);
        }else{
            // mysql query to update data
            $query = "UPDATE trigger_module SET ";
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
	$select_query = "SELECT * FROM `trigger_module` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
		"actual_trigger_date" => isset($select_result['last_inj_fsh'])?$select_result['last_inj_fsh']:"",
		"actual_opu_date" => 	isset($select_result['ovum_pick_up_on'])?$select_result['ovum_pick_up_on']:"",	
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
	<input type="hidden" value="<?php echo $_SESSION['logged_doctor']['doctor_id'] ?>" class="form" name="doctor_id">
	<input type="hidden" value="pending" name="status"> 
	<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">TRIGGER 3RD CYCLE</h3></td>
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
	<table class="table-bordered" width="100%">
		<tr>
			<td>INSTRUCTIONS PRIOR TO EGG COLLECTION</td>
		</tr>
		
		<tr>
			<td>• LAST INJ.OF FSH/GMH @<input  type="text" value="<?php echo isset($select_result['last_inj_fsh_at'])?$select_result['last_inj_fsh_at']:""; ?>"     maxlength="20" name="last_inj_fsh_at" >	on <input  type="date" value="<?php echo isset($select_result['last_inj_fsh'])?$select_result['last_inj_fsh']:""; ?>"     name="last_inj_fsh" ></td>
		</tr>
		<tr>
			<td>• TAKE LAST CETROTIDE @<input  type="text" value="<?php echo isset($select_result['last_cetrotide_at'])?$select_result['last_cetrotide_at']:""; ?>"     maxlength="20" name="last_cetrotide_at" >	on <input  type="date" value="<?php echo isset($select_result['last_cetrotide'])?$select_result['last_cetrotide']:""; ?>"     name="last_cetrotide" ></td>
		</tr>
		<tr>
			<td>• TAKE INJ.LUPRIDE @ <input  type="text" value="<?php echo isset($select_result['inj_lupride_at'])?$select_result['inj_lupride_at']:""; ?>"     maxlength="20" name="inj_lupride_at" > on <input  type="date" value="<?php echo isset($select_result['inj_lupride'])?$select_result['inj_lupride']:""; ?>"     name="inj_lupride" ></td>
		</tr>
		<tr>
			<td>• Take Inj. HCG @ <input  type="text" value="<?php echo isset($select_result['inj_hcg_at'])?$select_result['inj_hcg_at']:""; ?>"     maxlength="20" name="inj_hcg_at" > on <input  type="date" value="<?php echo isset($select_result['inj_hcg'])?$select_result['inj_hcg']:""; ?>"     name="inj_hcg" ></td>
		</tr>
		<tr>
			<td>• Ovum pick up on <input  type="date" value="<?php echo isset($select_result['ovum_pick_up_on'])?$select_result['ovum_pick_up_on']:""; ?>"     name="ovum_pick_up_on" > @ <input  type="text" value="<?php echo isset($select_result['ovum_pick_up_at'])?$select_result['ovum_pick_up_at']:""; ?>"     maxlength="20" name="ovum_pick_up_at" ></td>
		</tr>
		<tr>
			<td>• Admit @ <input  type="text" value="<?php echo isset($select_result['admit'])?$select_result['admit']:""; ?>"     maxlength="20" name="admit" > in day care on <input  type="date" value="<?php echo isset($select_result['admit_on'])?$select_result['admit_on']:""; ?>"     name="admit_on" > At <input  type="text" value="<?php echo isset($select_result['admit_at'])?$select_result['admit_at']:""; ?>"     name="admit_at" maxlength="20" ></td>
		</tr>
		<tr>
			<td>• No food /liquids (nil per mouth) 08 hrs before ovum pick up from <input  type="text" value="<?php echo isset($select_result['no_food_liquids'])?$select_result['no_food_liquids']:""; ?>"     maxlength="20" name="no_food_liquids" > hrs on <input  type="date" value="<?php echo isset($select_result['no_food_liquids1'])?$select_result['no_food_liquids1']:""; ?>"     name="no_food_liquids1" >.</td>
		</tr>
		<tr>
			<td>• Male partner to produce fresh sperm sample on the day of egg collection AT IVF CENTRE (Time to be confirmed on the day).<input  type="text" value="<?php echo isset($select_result['produce_fresh_sperm'])?$select_result['produce_fresh_sperm']:""; ?>"     maxlength="20" name="produce_fresh_sperm" ></td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
	</table>
	<table class="table-bordered" width="100%">
		<tr>
			<td>DATE</td>
			<td><input  type="date" value="<?php echo isset($select_result['date2'])?$select_result['date2']:""; ?>"     name="date2" class="form-control" ></td>
			<td>TIME</td>
			<td><input  type="time" value="<?php echo isset($select_result['time2'])?$select_result['time2']:""; ?>"     name="time2" class="form-control" ></td>
			<td>Doctor Name</td>
			<td><input  type="text" readonly="" value="<?php if (!empty($select_result['doctor_name'])) {  echo $select_result['doctor_name']; } else {  echo isset($_SESSION['logged_doctor']['name']) ? $_SESSION['logged_doctor']['name'] : ''; }  ?>"     name="doctor_name" class="form-control" ></td>
			<td>Doctor Signature</td>
			<td><input  type="text" value="<?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?>"     name="doctor_signature" class="form-control" ></td>
		</tr>
	</table>
	<!-- /.card-body -->
	<div class="card-footer">
		<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
		<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
	</div>
</form>




<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 


	<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
	 <tr>
                <td width="100%" colspan="4" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
				
			</tr>
			<tr>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;">UHID :</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?> </td>
</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;">IIC ID:</td>
<td colspan="1" width="25%" style="border:1px solid #cdcdcd;"> <?php echo $patient_id; ?></td>
</tr>
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
			</table>
	<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
	   <tr>
			<td style="border:1px solid #cdcdcd;">INSTRUCTIONS PRIOR TO EGG COLLECTION</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">• LAST INJ.OF FSH/GMH @ <?php echo isset($select_result['last_inj_fsh_at'])?$select_result['last_inj_fsh_at']:""; ?> 	on <?php echo isset($select_result['last_inj_fsh'])?$select_result['last_inj_fsh']:""; ?> </td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">• TAKE LAST CETROTIDE @ <?php echo isset($select_result['last_cetrotide_at'])?$select_result['last_cetrotide_at']:""; ?> 	on <?php echo isset($select_result['last_cetrotide'])?$select_result['last_cetrotide']:""; ?> </td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">• TAKE INJ.LUPRIDE @  <?php echo isset($select_result['inj_lupride_at'])?$select_result['inj_lupride_at']:""; ?>  on <?php echo isset($select_result['inj_lupride'])?$select_result['inj_lupride']:""; ?>  </td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">• Take Inj. HCG @ <?php echo isset($select_result['inj_hcg_at'])?$select_result['inj_hcg_at']:""; ?>  on <?php echo isset($select_result['inj_hcg'])?$select_result['inj_hcg']:""; ?> </td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">• Ovum pick up on <?php echo isset($select_result['ovum_pick_up_on'])?$select_result['ovum_pick_up_on']:""; ?> @ <?php echo isset($select_result['ovum_pick_up_at'])?$select_result['ovum_pick_up_at']:""; ?> </td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">• Admit <?php echo isset($select_result['admit'])?$select_result['admit']:""; ?> in day care on <?php echo isset($select_result['admit_on'])?$select_result['admit_on']:""; ?> At <?php echo isset($select_result['admit_at'])?$select_result['admit_at']:""; ?> </td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">• No food /liquids (nil per mouth) 08 hrs before ovum pick up from <?php echo isset($select_result['no_food_liquids'])?$select_result['no_food_liquids']:""; ?> hrs on <?php echo isset($select_result['no_food_liquids1'])?$select_result['no_food_liquids1']:""; ?> .</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">• Male partner to produce fresh sperm sample on the day of egg collection AT IVF CENTRE (Time to be confirmed on the day).<?php echo isset($select_result['produce_fresh_sperm'])?$select_result['produce_fresh_sperm']:""; ?> </td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;"><br></td>
		</tr>
	</table>
	
	
	
	
	
	<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<td><b>OVUM PICK UP/Pre Fresh cycle self ET</b></td>
			<td style="border:1px solid #cdcdcd;">
				Date<br>
			<?php echo isset($select_result['dates'])?$select_result['dates']:""; ?>
			</td>
			<td style="border:1px solid #cdcdcd;">
				Time<br>
				<?php echo isset($select_result['times'])?$select_result['times']:""; ?>
			</td>
			<td style="border:1px solid #cdcdcd;">
				Indication<br>
				<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>
			</td>
			<td style="border:1px solid #cdcdcd;">
				Allergies<br>
				<?php echo isset($select_result['allergies'])?$select_result['allergies']:""; ?>"
			</td>
			<td style="border:1px solid #cdcdcd;">
				Consent<br>
							
		<?php if(isset($select_result['consent']) && $select_result['consent'] == "Yes"){echo 'yes'; }?>	
			
			</td>
			<td style="border:1px solid #cdcdcd;">
				ID <br>
			
		
<?php if(isset($select_result['id_checked']) && $select_result['id_checked'] == "Yes"){echo 'yes'; }?>

		</td>
		</tr>
		<tr>
			<td style="border:1px solid #cdcdcd;">PRE ASSESSMENT</td>
			<td style="border:1px solid #cdcdcd;">
				BP<br>
				<?php echo isset($select_result['bp'])?$select_result['bp']:""; ?>
			</td>
			<td style="border:1px solid #cdcdcd;">
				Pulse<br>
						
			<?php if(isset($select_result['pulse']) && $select_result['pulse'] == "Yes"){echo 'yes'; }?>
			
			</td>
			<td style="border:1px solid #cdcdcd;">
				Resp<br>
							
			<?php if(isset($select_result['resp']) && $select_result['resp'] == "Yes"){echo 'yes'; }?>
			
			</td>
			
			<td style="border:1px solid #cdcdcd;">
				Voided<br>
				
			
			<?php if(isset($select_result['voided']) && $select_result['voided'] == "Yes"){echo 'yes'; }?>
			
			</td>
			<td style="border:1px solid #cdcdcd;">
				Ht (Cms)<br>
				<?php echo isset($select_result['ht'])?$select_result['ht']:""; ?>
			</td>
			<td style="border:1px solid #cdcdcd;">
				Wt (Kg)<br>
				<?php echo isset($select_result['wt'])?$select_result['wt']:""; ?>
			</td>
		</tr>
		<tr>
			<td style="color: black;border:1px solid #cdcdcd;">
				Glasses<br>
							
		<?php if(isset($select_result['glasses']) && $select_result['glasses'] == "Yes"){echo 'yes'; }?>	
			
			
			</td>
			<td style="color: black; border:1px solid #cdcdcd;">
				Contacts<br>
						

<?php if(isset($select_result['contacts']) && $select_result['contacts'] == "Yes"){echo 'yes'; }?>

		</td>
			<td style="color: black; border:1px solid #cdcdcd;">
				Denture<br>
				
			
			
			<?php if(isset($select_result['denture']) && $select_result['denture'] == "Yes"){echo 'yes'; }?>
			
			
			
			</td>
			<td style="color: black; border:1px solid #cdcdcd;" colspan="2">
				Dental bridge<br>
							
	<?php if(isset($select_result['dental_bridge']) && $select_result['dental_bridge'] == "Yes"){echo 'yes'; }?>
					
			
			</td>
		


		<td style="color: black;border:1px solid #cdcdcd;">
				Valuables with escort<br>
				
				
			
			<?php if(isset($select_result['escort']) && $select_result['escort'] == "Yes"){echo 'yes'; }?>
			
			</td>
			
			<td style="color: black; border:1px solid #cdcdcd;">
				Last meal<br>
				<?php echo isset($select_result['last_meal'])?$select_result['last_meal']:""; ?>
			</td>
		</tr>
	</table>
	
	
	
	

	
	
	<table class="table-bordered" style="width:100%; border:1px solid #cdcdcd;">
		<tr>
			<td style="border:1px solid #cdcdcd;" >DATE</td>
			<td  style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></td>
			<td style="border:1px solid #cdcdcd;">TIME</td>
			<td style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['time2'])?$select_result['time2']:""; ?> </td>
			<td style="border:1px solid #cdcdcd;" >Doctor Name</td>
			<td style="border:1px solid #cdcdcd;" > <?php echo isset($select_result['doctor_name'])?$select_result['doctor_name']:""; ?>  </td>
			<td style="border:1px solid #cdcdcd;">Doctor Signature</td>
			<td  style="border:1px solid #cdcdcd;"> <?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?> </td>
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