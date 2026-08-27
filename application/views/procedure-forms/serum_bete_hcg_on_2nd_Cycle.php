<?php

    // php code to Insert data into mysql database from input text

    if(isset($_POST['submit'])){

        unset($_POST['submit']);
		
	    $select_query = "SELECT * FROM `hms_serum_bete_hcg_on` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";

        $select_result = run_select_query($select_query); 

        if(empty($select_result)){

            // mysql query to insert data

            $query = "INSERT INTO `hms_serum_bete_hcg_on` SET ";

            $sqlArr = array();

            foreach( $_POST as $key=> $value )

            {

              $sqlArr[] = " $key = '".addslashes($value)."'";

            }		

            $query .= implode(',' , $sqlArr);
        }else{

            // mysql query to update data

            $query = "UPDATE hms_serum_bete_hcg_on SET ";

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

    $select_query = "SELECT * FROM `hms_serum_bete_hcg_on` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$select_result = run_select_query($select_query); 
	
    $sql3 = "SELECT * FROM `hms_patients` WHERE patient_id='$patient_id'";
    $select_result3 = run_select_query($sql3); 	
	
	$sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where paitent_id='".$patient_id."'";
	$select_result1 = run_select_query($sql1);
	
	$sql4 = "Select * from ".$this->config->item('db_prefix')."appointments where wife_phone='".$select_result1['wife_phone']."' and paitent_type='new_patient'";
	$select_result4 = run_select_query($sql4);
	
	$sql5 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$select_result4['appoitment_for']."'";
	$select_result5 = run_select_query($sql5);		

				$data = array(
					"lead_id" => trim($select_result4['crm_id']),
					"procedure_type_name" => $proc_result['procedure_name'] . ', ' . (new DateTime($patient_result['on_date']))->format('Y-m-d'),
					"serum_beta_hcg_no" => isset($select_result['cardiac_activity_no'])?$select_result['cardiac_activity_no']:"",
					"serum_beta_hcg_date" => isset($select_result['date'])?$select_result['date']:"",
					"no_of_gestational_sac_cardiac_ultrasound" => isset($select_result['no_of_gestational'])?$select_result['no_of_gestational']:"",
					"date_of_gestational_sac_cardiac_ultrasound" => isset($select_result['date_5'])?$select_result['date_5']:"",
				);

				// Convert PHP array to JSON
				$jsonData = json_encode($data);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://flertility.in/lead/lead-journey/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $jsonData,  // Send JSON Data
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',  // Specify JSON Content Type
    'Accept: application/json',         // Expect JSON Response
    'X-Hms-Api-Token: _dkGEDrhpSCpaZVx8-tRbTkq66MHvl_4R5O4fCZ6NPGB7eO7JOThQw'
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
	<input type="hidden" value="pending" name="status">
	<input type="hidden" value="Second Cycle" name="type">
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">SERUM BETA HCG ON SECOND CYCLE</h3></td>
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
					<td colspan="4">SERUM BETA HCG ON</td>
				</tr>
				<tr>
					<td>First Value</td>
					<td><input type="number"  value="<?php echo isset($select_result['cardiac_activity_no'])?$select_result['cardiac_activity_no']:""; ?>" min="0" name="cardiac_activity_no"></td>
					<td>DATE</td>
					<td><input type="date" name="date" class="form-control" value="<?php echo isset($select_result['date'])?$select_result['date']:""; ?>"></td>
				</tr>
				<tr>
					<td>Second Value</td>
					<td><input type="number"  value="<?php echo isset($select_result['cardiac_activity_no_2'])?$select_result['cardiac_activity_no_2']:""; ?>" min="0" name="cardiac_activity_no_2"></td>
					<td>DATE</td>
					<td><input type="date" name="date_2" class="form-control" value="<?php echo isset($select_result['date_2'])?$select_result['date_2']:""; ?>"></td>
				</tr>
				<tr>
					<td>Third Value</td>
					<td><input type="number"  value="<?php echo isset($select_result['cardiac_activity_no_3'])?$select_result['cardiac_activity_no_3']:""; ?>" min="0" name="cardiac_activity_no_3"></td>
					<td>DATE</td>
					<td><input type="date" name="date_3" class="form-control" value="<?php echo isset($select_result['date_3'])?$select_result['date_3']:""; ?>"></td>
				</tr>
				<tr>
					<td colspan="4">
					    <p>No. of gestational sac with cardiac activity on ultrasound on</p>
					    <input type="text"  value="<?php echo isset($select_result['no_of_gestational'])?$select_result['no_of_gestational']:""; ?>" min="0" name="no_of_gestational">
					    </td>
				</tr>
			</table>

			<table class="table-bordered" width="100%">
				<tr>
					<td>DATE</td>
					<td><input type="date" name="date_4" class="form-control" value="<?php echo isset($select_result['date_4'])?$select_result['date_4']:""; ?>"   ></td>
					<td>TIME</td>
					<td><input type="time" name="time" class="form-control" value="<?php echo isset($select_result['time'])?$select_result['time']:""; ?>"   ></td>
					<td>Doctor Name</td>
					<td><input type="text" name="doctor_name" maxlength="20" class="form-control" value="<?php echo isset($select_result['doctor_name'])?$select_result['doctor_name']:""; ?>"   ></td>
					<td>Doctor Signature</td>
					<td><input type="text" name="doctor_signature" maxlength="20" class="form-control" value="<?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?>"   ></td>
				</tr>
			</table>



			<!-- /.card-body -->

			<div class="card-footer">

				<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->

				<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">

			</div>

</form>






		<!--                             Print       TABLE                         -->	
		
		
		
		
		
		
		
			
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;">
<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">
			<table style="border:1px solid;width:100%;" class="fg45yu">
   <tr>
   <td style="width:50%;border:1px solid #ccc" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;border:1px solid #ccc" colspan="10"><h3 style="margin-top:20px;">SERUM BETA HCG ON SECOND CYCLE</h3></td>
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
					<td colspan="4" style="border:1px solid #cdcdcd;">SERUM BETA HCG ON</td>
				</tr>
				<tr>
					<td style="border:1px solid #cdcdcd;">First Value</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cardiac_activity_no'])?$select_result['cardiac_activity_no']:""; ?></td>
					<td style="border:1px solid #cdcdcd;">DATE</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date'])?$select_result['date']:""; ?></td>
				</tr>
				<tr>  
				    <td style="border:1px solid #cdcdcd;">Second Value</td>
				    <td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cardiac_activity_no_2'])?$select_result['cardiac_activity_no_2']:""; ?></td>
					<td style="border:1px solid #cdcdcd;">DATE</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_2'])?$select_result['date_2']:""; ?></td>
				</tr>
				<tr>
					<td style="border:1px solid #cdcdcd;">Third Value</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['cardiac_activity_no_3'])?$select_result['cardiac_activity_no_3']:""; ?></td>
					<td style="border:1px solid #cdcdcd;">DATE</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_3'])?$select_result['date_3']:""; ?></td>
				</tr>
				<tr>
					<td colspan="4" style="border:1px solid #cdcdcd;">No. of gestational sac with cardiac activity on ultrasound on</td>
				</tr>
				<tr>
					<td colspan="4" style="border:1px solid #cdcdcd;"><?php echo isset($select_result['no_of_gestational'])?$select_result['no_of_gestational']:""; ?></td>
				</tr>
			</table>
			
			<table class="table-bordered" width="100%">

				<tr>

					<td style="border:1px solid #cdcdcd;">DATE</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['date_4'])?$select_result['date_4']:""; ?></td>
					<td style="border:1px solid #cdcdcd;">TIME</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['time'])?$select_result['time']:""; ?></td>
				</tr>
				<tr>
					<td style="border:1px solid #cdcdcd;">Doctor Name</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['doctor_name'])?$select_result['doctor_name']:""; ?></td>
					<td style="border:1px solid #cdcdcd;">Doctor Signature</td>
					<td style="border:1px solid #cdcdcd;"><?php echo isset($select_result['doctor_signature'])?$select_result['doctor_signature']:""; ?></td>

				</tr>

			</table>

<!-- Main Table END -->
</table>
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