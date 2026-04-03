<?php

	    if(isset($_POST['submit'])){

			unset($_POST['submit']);

					

			$select_query = "SELECT * FROM `pre_embryo_transfer` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";

			$select_result = run_select_query($select_query); 

			if(empty($select_result)){

				// mysql query to insert data

				$query = "INSERT INTO `pre_embryo_transfer` SET ";

				$sqlArr = array();

				foreach( $_POST as $key=> $value )

				{

				  $sqlArr[] = " $key = '".addslashes($value)."'";

				}		

				$query .= implode(',' , $sqlArr);

			}else{

				// mysql query to update data

				$query = "UPDATE pre_embryo_transfer SET ";

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

	$select_query = "SELECT * FROM `pre_embryo_transfer` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
 <table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">
			<tr>
                <th width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></th>
				<th width="50%" colspan="2" style="border:1px solid #cdcdcd;"><center><h3>PRE EMBRYO TRANSFER SECOND CYCLE</h3></center></th>
				
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

			<td colspan="2">SELF CYCLE (S)</td>

			<td style="color: black;" colspan="2">SURROGATE MOTHER CYCLE (SUR)</td>

		</tr>

		<tr>

			<td>Partners name</td>

			<td><input  type="text" value="<?php echo isset($select_result['partners_name'])?$select_result['partners_name']:""; ?>"   maxlength="20" name="partners_name" class="form-control"></td>

			<td style="color: black;">ART bank reg no</td>

			<td><input  type="text" value="<?php echo isset($select_result['art_bank_reg_no'])?$select_result['art_bank_reg_no']:""; ?>"   maxlength="20" name="art_bank_reg_no" class="form-control"></td>

		</tr>

		<tr>

			<td>ID</td>

			<td><input  type="text" value="<?php echo isset($select_result['form_id'])?$select_result['form_id']:""; ?>"   maxlength="20" name="form_id" class="form-control"></td>

			<td style="color: black;">Surrogate ID</td>

			<td><input  type="text" value="<?php echo isset($select_result['surrogate_id'])?$select_result['surrogate_id']:""; ?>"   maxlength="20" name="surrogate_id" class="form-control"></td>

		</tr>

		<tr>

			<td colspan="2">LAST MENSTRUAL PERIOD</td>

			<td colspan="2"><input  type="date" value="<?php echo isset($select_result['last_menstrual_period'])?$select_result['last_menstrual_period']:""; ?>"   name="last_menstrual_period" class="form-control"></td>

		</tr>
		<tr>
			<td colspan="">Indication </td>
			<td colspan="3"><input  type="text" value="<?php echo isset($select_result['indication'])?$select_result['indication']:""; ?>" maxlength="200" name="indication" class="form-control" ></td>
		</tr>	

	</table>

	<div class="table-responsive">

		<table class="table-bordered" width="100%">

			<tr>

				<td style="width: 20%;"><br></td>

				<td colspan="10" style="background-color: yellow;">ESTROGEN</td>

				<td colspan="17" style="background-color: orange;">PROGESTERONE</td>

			</tr>

			<tr>

				<td>Day of Stimulation</td>

				<td style="background-color: yellow;">1</td>

				<td style="background-color: yellow;">2</td>

				<td style="background-color: yellow;">3</td>

				<td style="background-color: yellow;">4</td>

				<td style="background-color: yellow;">5</td>

				<td style="background-color: yellow;">6</td>

				<td style="background-color: yellow;">7</td>

				<td style="background-color: yellow;">8</td>

				<td style="background-color: yellow;">9</td>

				<td style="background-color: yellow;">10</td>

				<td style="background-color: orange;">11</td>

				<td style="background-color: orange;">12</td>

				<td style="background-color: orange;">13</td>

				<td style="background-color: orange;">14</td>

				<td style="background-color: orange;">15</td>

				<td style="background-color: orange;">16</td>

				<td style="background-color: orange;">17</td>

				<td style="background-color: orange;">18</td>

				<td style="background-color: orange;">19</td>

				<td style="background-color: orange;">20</td>

				<td style="background-color: orange;">21</td>

				<td style="background-color: orange;">22</td>

				<td style="background-color: orange;">23</td>

				<td style="background-color: orange;">24</td>

				<td style="background-color: orange;">25</td>

				<td style="background-color: orange;">26</td>

				<td style="background-color: orange;">27</td>

			</tr>

			<tr>

				<td>DATE</td>

				<td style="background-color: yellow;"><input  type="date" value="<?php echo isset($select_result['date1'])?$select_result['date1']:""; ?>"   name="date1" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="date" value="<?php echo isset($select_result['date2'])?$select_result['date2']:""; ?>"   name="date2" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="date" value="<?php echo isset($select_result['date3'])?$select_result['date3']:""; ?>"   name="date3" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="date" value="<?php echo isset($select_result['date4'])?$select_result['date4']:""; ?>"   name="date4" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="date" value="<?php echo isset($select_result['date5'])?$select_result['date5']:""; ?>"   name="date5" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="date" value="<?php echo isset($select_result['date6'])?$select_result['date6']:""; ?>"   name="date6" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="date" value="<?php echo isset($select_result['date7'])?$select_result['date7']:""; ?>"   name="date7" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="date" value="<?php echo isset($select_result['date8'])?$select_result['date8']:""; ?>"   name="date8" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="date" value="<?php echo isset($select_result['date9'])?$select_result['date9']:""; ?>"   name="date9" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="date" value="<?php echo isset($select_result['date10'])?$select_result['date10']:""; ?>"   name="date10" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date11'])?$select_result['date11']:""; ?>"   name="date11" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date12'])?$select_result['date12']:""; ?>"   name="date12" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date13'])?$select_result['date13']:""; ?>"   name="date13" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date14'])?$select_result['date14']:""; ?>"   name="date14" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date15'])?$select_result['date15']:""; ?>"   name="date15" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date16'])?$select_result['date16']:""; ?>"   name="date16" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date17'])?$select_result['date17']:""; ?>"   name="date17" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date18'])?$select_result['date18']:""; ?>"   name="date18" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date19'])?$select_result['date19']:""; ?>"   name="date19" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date20'])?$select_result['date20']:""; ?>"   name="date20" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date21'])?$select_result['date21']:""; ?>"   name="date21" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date22'])?$select_result['date22']:""; ?>"   name="date22" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date23'])?$select_result['date23']:""; ?>"   name="date23" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date24'])?$select_result['date24']:""; ?>"   name="date24" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date25'])?$select_result['date25']:""; ?>"   name="date25" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date26'])?$select_result['date26']:""; ?>"   name="date26" class="form-control"></td>

				<td style="background-color: orange;"><input  type="date" value="<?php echo isset($select_result['date27'])?$select_result['date27']:""; ?>"   name="date27" class="form-control"></td>

			</tr>

			<tr>

				<td>ENDOMETRIAL THICKNESS (cm)</td>

				<td style="background-color: yellow;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness1'])?$select_result['endometrial_thickness1']:""; ?>"   min="0" name="endometrial_thickness1" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness2'])?$select_result['endometrial_thickness2']:""; ?>"   min="0" name="endometrial_thickness2" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness3'])?$select_result['endometrial_thickness3']:""; ?>"   min="0" name="endometrial_thickness3" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness4'])?$select_result['endometrial_thickness4']:""; ?>"   min="0" name="endometrial_thickness4" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness5'])?$select_result['endometrial_thickness5']:""; ?>"   min="0" name="endometrial_thickness5" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness6'])?$select_result['endometrial_thickness6']:""; ?>"   min="0" name="endometrial_thickness6" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness7'])?$select_result['endometrial_thickness7']:""; ?>"   min="0" name="endometrial_thickness7" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness8'])?$select_result['endometrial_thickness8']:""; ?>"   min="0" name="endometrial_thickness8" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness9'])?$select_result['endometrial_thickness9']:""; ?>"   min="0" name="endometrial_thickness9" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness10'])?$select_result['endometrial_thickness10']:""; ?>"   min="0" name="endometrial_thickness10" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness11'])?$select_result['endometrial_thickness11']:""; ?>"   min="0" name="endometrial_thickness11" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness12'])?$select_result['endometrial_thickness12']:""; ?>"   min="0" name="endometrial_thickness12" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness13'])?$select_result['endometrial_thickness13']:""; ?>"   min="0" name="endometrial_thickness13" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness14'])?$select_result['endometrial_thickness14']:""; ?>"   min="0" name="endometrial_thickness14" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness15'])?$select_result['endometrial_thickness15']:""; ?>"   min="0" name="endometrial_thickness15" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness16'])?$select_result['endometrial_thickness16']:""; ?>"   min="0" name="endometrial_thickness16" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness17'])?$select_result['endometrial_thickness17']:""; ?>"   min="0" name="endometrial_thickness17" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness18'])?$select_result['endometrial_thickness18']:""; ?>"   min="0" name="endometrial_thickness18" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness19'])?$select_result['endometrial_thickness19']:""; ?>"   min="0" name="endometrial_thickness19" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness20'])?$select_result['endometrial_thickness20']:""; ?>"   min="0" name="endometrial_thickness20" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness21'])?$select_result['endometrial_thickness21']:""; ?>"   min="0" name="endometrial_thickness21" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness22'])?$select_result['endometrial_thickness22']:""; ?>"   min="0" name="endometrial_thickness22" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness23'])?$select_result['endometrial_thickness23']:""; ?>"   min="0" name="endometrial_thickness23" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness24'])?$select_result['endometrial_thickness24']:""; ?>"   min="0" name="endometrial_thickness24" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness25'])?$select_result['endometrial_thickness25']:""; ?>"   min="0" name="endometrial_thickness25" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness26'])?$select_result['endometrial_thickness26']:""; ?>"   min="0" name="endometrial_thickness26" class="form-control"></td>

				<td style="background-color: orange;"><input  type="number" value="<?php echo isset($select_result['endometrial_thickness27'])?$select_result['endometrial_thickness27']:""; ?>"   min="0" name="endometrial_thickness27" class="form-control"></td>

			</tr>

			<tr>

				<td>ESTROGEN</td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['estrogen1'])?$select_result['estrogen1']:""; ?>"   maxlength="20" name="estrogen1" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['estrogen2'])?$select_result['estrogen2']:""; ?>"   maxlength="20" name="estrogen2" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['estrogen3'])?$select_result['estrogen3']:""; ?>"   maxlength="20" name="estrogen3" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['estrogen4'])?$select_result['estrogen4']:""; ?>"   maxlength="20" name="estrogen4" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['estrogen5'])?$select_result['estrogen5']:""; ?>"   maxlength="20" name="estrogen5" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['estrogen6'])?$select_result['estrogen6']:""; ?>"   maxlength="20" name="estrogen6" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['estrogen7'])?$select_result['estrogen7']:""; ?>"   maxlength="20" name="estrogen7" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['estrogen8'])?$select_result['estrogen8']:""; ?>"   maxlength="20" name="estrogen8" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['estrogen9'])?$select_result['estrogen9']:""; ?>"   maxlength="20" name="estrogen9" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['estrogen10'])?$select_result['estrogen10']:""; ?>"   maxlength="20" name="estrogen10" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen11'])?$select_result['estrogen11']:""; ?>"   maxlength="20" name="estrogen11" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen12'])?$select_result['estrogen12']:""; ?>"   maxlength="20" name="estrogen12" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen13'])?$select_result['estrogen13']:""; ?>"   maxlength="20" name="estrogen13" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen14'])?$select_result['estrogen14']:""; ?>"   maxlength="20" name="estrogen14" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen15'])?$select_result['estrogen15']:""; ?>"   maxlength="20" name="estrogen15" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen16'])?$select_result['estrogen16']:""; ?>"   maxlength="20" name="estrogen16" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen17'])?$select_result['estrogen17']:""; ?>"   maxlength="20" name="estrogen17" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen18'])?$select_result['estrogen18']:""; ?>"   maxlength="20" name="estrogen18" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen19'])?$select_result['estrogen19']:""; ?>"   maxlength="20" name="estrogen19" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen20'])?$select_result['estrogen20']:""; ?>"   maxlength="20" name="estrogen20" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen21'])?$select_result['estrogen21']:""; ?>"   maxlength="20" name="estrogen21" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen22'])?$select_result['estrogen22']:""; ?>"   maxlength="20" name="estrogen22" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen23'])?$select_result['estrogen23']:""; ?>"   maxlength="20" name="estrogen23" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen24'])?$select_result['estrogen24']:""; ?>"   maxlength="20" name="estrogen24" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen25'])?$select_result['estrogen25']:""; ?>"   maxlength="20" name="estrogen25" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen26'])?$select_result['estrogen26']:""; ?>"   maxlength="20" name="estrogen26" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['estrogen27'])?$select_result['estrogen27']:""; ?>"   maxlength="20" name="estrogen27" class="form-control"></td>

			</tr>

			<tr>

				<td>PROGESTERONE</td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['progesterone1'])?$select_result['progesterone1']:""; ?>"   maxlength="20" name="progesterone1" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['progesterone2'])?$select_result['progesterone2']:""; ?>"   maxlength="20" name="progesterone2" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['progesterone3'])?$select_result['progesterone3']:""; ?>"   maxlength="20" name="progesterone3" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['progesterone4'])?$select_result['progesterone4']:""; ?>"   maxlength="20" name="progesterone4" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['progesterone5'])?$select_result['progesterone5']:""; ?>"   maxlength="20" name="progesterone5" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['progesterone6'])?$select_result['progesterone6']:""; ?>"   maxlength="20" name="progesterone6" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['progesterone7'])?$select_result['progesterone7']:""; ?>"   maxlength="20" name="progesterone7" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['progesterone8'])?$select_result['progesterone8']:""; ?>"   maxlength="20" name="progesterone8" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['progesterone9'])?$select_result['progesterone9']:""; ?>"   maxlength="20" name="progesterone9" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['progesterone10'])?$select_result['progesterone10']:""; ?>"   maxlength="20" name="progesterone10" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone11'])?$select_result['progesterone11']:""; ?>"   maxlength="20" name="progesterone11" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone12'])?$select_result['progesterone12']:""; ?>"   maxlength="20" name="progesterone12" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone13'])?$select_result['progesterone13']:""; ?>"   maxlength="20" name="progesterone13" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone14'])?$select_result['progesterone14']:""; ?>"   maxlength="20" name="progesterone14" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone15'])?$select_result['progesterone15']:""; ?>"   maxlength="20" name="progesterone15" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone16'])?$select_result['progesterone16']:""; ?>"   maxlength="20" name="progesterone16" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone17'])?$select_result['progesterone17']:""; ?>"   maxlength="20" name="progesterone17" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone18'])?$select_result['progesterone18']:""; ?>"   maxlength="20" name="progesterone18" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone19'])?$select_result['progesterone19']:""; ?>"   maxlength="20" name="progesterone19" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone20'])?$select_result['progesterone20']:""; ?>"   maxlength="20" name="progesterone20" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone21'])?$select_result['progesterone21']:""; ?>"   maxlength="20" name="progesterone21" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone22'])?$select_result['progesterone22']:""; ?>"   maxlength="20" name="progesterone22" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone23'])?$select_result['progesterone23']:""; ?>"   maxlength="20" name="progesterone23" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone24'])?$select_result['progesterone24']:""; ?>"   maxlength="20" name="progesterone24" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone25'])?$select_result['progesterone25']:""; ?>"   maxlength="20" name="progesterone25" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone26'])?$select_result['progesterone26']:""; ?>"   maxlength="20" name="progesterone26" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['progesterone27'])?$select_result['progesterone27']:""; ?>"   maxlength="20" name="progesterone27" class="form-control"></td>

			</tr>

			<tr>

				<td>MEDICINE ADDED</td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['medicine_added1'])?$select_result['medicine_added1']:""; ?>"   maxlength="20" name="medicine_added1" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['medicine_added2'])?$select_result['medicine_added2']:""; ?>"   maxlength="20" name="medicine_added2" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['medicine_added3'])?$select_result['medicine_added3']:""; ?>"   maxlength="20" name="medicine_added3" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['medicine_added4'])?$select_result['medicine_added4']:""; ?>"   maxlength="20" name="medicine_added4" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['medicine_added5'])?$select_result['medicine_added5']:""; ?>"   maxlength="20" name="medicine_added5" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['medicine_added6'])?$select_result['medicine_added6']:""; ?>"   maxlength="20" name="medicine_added6" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['medicine_added7'])?$select_result['medicine_added7']:""; ?>"   maxlength="20" name="medicine_added7" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['medicine_added8'])?$select_result['medicine_added8']:""; ?>"   maxlength="20" name="medicine_added8" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['medicine_added9'])?$select_result['medicine_added9']:""; ?>"   maxlength="20" name="medicine_added9" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['medicine_added10'])?$select_result['medicine_added10']:""; ?>"   maxlength="20" name="medicine_added10" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added11'])?$select_result['medicine_added11']:""; ?>"   maxlength="20" name="medicine_added11" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added12'])?$select_result['medicine_added12']:""; ?>"   maxlength="20" name="medicine_added12" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added13'])?$select_result['medicine_added13']:""; ?>"   maxlength="20" name="medicine_added13" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added14'])?$select_result['medicine_added14']:""; ?>"   maxlength="20" name="medicine_added14" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added15'])?$select_result['medicine_added15']:""; ?>"   maxlength="20" name="medicine_added15" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added16'])?$select_result['medicine_added16']:""; ?>"   maxlength="20" name="medicine_added16" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added17'])?$select_result['medicine_added17']:""; ?>"   maxlength="20" name="medicine_added17" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added18'])?$select_result['medicine_added18']:""; ?>"   maxlength="20" name="medicine_added18" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added19'])?$select_result['medicine_added19']:""; ?>"   maxlength="20" name="medicine_added19" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added20'])?$select_result['medicine_added20']:""; ?>"   maxlength="20" name="medicine_added20" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added21'])?$select_result['medicine_added21']:""; ?>"   maxlength="20" name="medicine_added21" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added22'])?$select_result['medicine_added22']:""; ?>"   maxlength="20" name="medicine_added22" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added23'])?$select_result['medicine_added23']:""; ?>"   maxlength="20" name="medicine_added23" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added24'])?$select_result['medicine_added24']:""; ?>"   maxlength="20" name="medicine_added24" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added25'])?$select_result['medicine_added25']:""; ?>"   maxlength="20" name="medicine_added25" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added26'])?$select_result['medicine_added26']:""; ?>"   maxlength="20" name="medicine_added26" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['medicine_added27'])?$select_result['medicine_added27']:""; ?>"   maxlength="20" name="medicine_added27" class="form-control"></td>

			</tr>

			<tr>

				<td>REMARKS</td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks1'])?$select_result['remarks1']:""; ?>"   maxlength="20" name="remarks1" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks2'])?$select_result['remarks2']:""; ?>"   maxlength="20" name="remarks2" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks3'])?$select_result['remarks3']:""; ?>"   maxlength="20" name="remarks3" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks4'])?$select_result['remarks4']:""; ?>"   maxlength="20" name="remarks4" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks5'])?$select_result['remarks5']:""; ?>"   maxlength="20" name="remarks5" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks6'])?$select_result['remarks6']:""; ?>"   maxlength="20" name="remarks6" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks7'])?$select_result['remarks7']:""; ?>"   maxlength="20" name="remarks7" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks8'])?$select_result['remarks8']:""; ?>"   maxlength="20" name="remarks8" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks9'])?$select_result['remarks9']:""; ?>"   maxlength="20" name="remarks9" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks10'])?$select_result['remarks10']:""; ?>"   maxlength="20" name="remarks10" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks11'])?$select_result['remarks11']:""; ?>"   maxlength="20" name="remarks11" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks12'])?$select_result['remarks12']:""; ?>"   maxlength="20" name="remarks12" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks13'])?$select_result['remarks13']:""; ?>"   maxlength="20" name="remarks13" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks14'])?$select_result['remarks14']:""; ?>"   maxlength="20" name="remarks14" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks15'])?$select_result['remarks15']:""; ?>"   maxlength="20" name="remarks15" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks16'])?$select_result['remarks16']:""; ?>"   maxlength="20" name="remarks16" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks17'])?$select_result['remarks17']:""; ?>"   maxlength="20" name="remarks17" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks18'])?$select_result['remarks18']:""; ?>"   maxlength="20" name="remarks18" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks19'])?$select_result['remarks19']:""; ?>"   maxlength="20" name="remarks19" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks20'])?$select_result['remarks20']:""; ?>"   maxlength="20" name="remarks20" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks21'])?$select_result['remarks21']:""; ?>"   maxlength="20" name="remarks21" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks22'])?$select_result['remarks22']:""; ?>"   maxlength="20" name="remarks22" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks23'])?$select_result['remarks23']:""; ?>"   maxlength="20" name="remarks23" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks24'])?$select_result['remarks24']:""; ?>"   maxlength="20" name="remarks24" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks25'])?$select_result['remarks25']:""; ?>"   maxlength="20" name="remarks25" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks26'])?$select_result['remarks26']:""; ?>"   maxlength="20" name="remarks26" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['remarks27'])?$select_result['remarks27']:""; ?>"   maxlength="20" name="remarks27" class="form-control"></td>

			</tr>

			<tr>

				<td>FOLLOWUP ON</td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['followup_on1'])?$select_result['followup_on1']:""; ?>"   maxlength="20" name="followup_on1" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['followup_on2'])?$select_result['followup_on2']:""; ?>"   maxlength="20" name="followup_on2" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['followup_on3'])?$select_result['followup_on3']:""; ?>"   maxlength="20" name="followup_on3" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['followup_on4'])?$select_result['followup_on4']:""; ?>"   maxlength="20" name="followup_on4" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['followup_on5'])?$select_result['followup_on5']:""; ?>"   maxlength="20" name="followup_on5" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['followup_on6'])?$select_result['followup_on6']:""; ?>"   maxlength="20" name="followup_on6" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['followup_on7'])?$select_result['followup_on7']:""; ?>"   maxlength="20" name="followup_on7" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['followup_on8'])?$select_result['followup_on8']:""; ?>"   maxlength="20" name="followup_on8" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['followup_on9'])?$select_result['followup_on9']:""; ?>"   maxlength="20" name="followup_on9" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['followup_on10'])?$select_result['followup_on10']:""; ?>"   maxlength="20" name="followup_on10" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on11'])?$select_result['followup_on11']:""; ?>"   maxlength="20" name="followup_on11" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on12'])?$select_result['followup_on12']:""; ?>"   maxlength="20" name="followup_on12" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on13'])?$select_result['followup_on13']:""; ?>"   maxlength="20" name="followup_on13" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on14'])?$select_result['followup_on14']:""; ?>"   maxlength="20" name="followup_on14" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on15'])?$select_result['followup_on15']:""; ?>"   maxlength="20" name="followup_on15" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on16'])?$select_result['followup_on16']:""; ?>"   maxlength="20" name="followup_on16" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on17'])?$select_result['followup_on17']:""; ?>"   maxlength="20" name="followup_on17" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on18'])?$select_result['followup_on18']:""; ?>"   maxlength="20" name="followup_on18" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on19'])?$select_result['followup_on19']:""; ?>"   maxlength="20" name="followup_on19" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on20'])?$select_result['followup_on20']:""; ?>"   maxlength="20" name="followup_on20" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on21'])?$select_result['followup_on21']:""; ?>"   maxlength="20" name="followup_on21" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on22'])?$select_result['followup_on22']:""; ?>"   maxlength="20" name="followup_on22" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on23'])?$select_result['followup_on23']:""; ?>"   maxlength="20" name="followup_on23" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on24'])?$select_result['followup_on24']:""; ?>"   maxlength="20" name="followup_on24" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on25'])?$select_result['followup_on25']:""; ?>"   maxlength="20" name="followup_on25" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on26'])?$select_result['followup_on26']:""; ?>"   maxlength="20" name="followup_on26" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['followup_on27'])?$select_result['followup_on27']:""; ?>"   maxlength="20" name="followup_on27" class="form-control"></td>

			</tr>

			<tr>

				<td>SERUM ESTRADIOL (E2) LEVEL</td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level1'])?$select_result['serum_e2_level1']:""; ?>"   maxlength="20" name="serum_e2_level1" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level2'])?$select_result['serum_e2_level2']:""; ?>"   maxlength="20" name="serum_e2_level2" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level3'])?$select_result['serum_e2_level3']:""; ?>"   maxlength="20" name="serum_e2_level3" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level4'])?$select_result['serum_e2_level4']:""; ?>"   maxlength="20" name="serum_e2_level4" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level5'])?$select_result['serum_e2_level5']:""; ?>"   maxlength="20" name="serum_e2_level5" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level6'])?$select_result['serum_e2_level6']:""; ?>"   maxlength="20" name="serum_e2_level6" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level7'])?$select_result['serum_e2_level7']:""; ?>"   maxlength="20" name="serum_e2_level7" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level8'])?$select_result['serum_e2_level8']:""; ?>"   maxlength="20" name="serum_e2_level8" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level9'])?$select_result['serum_e2_level9']:""; ?>"   maxlength="20" name="serum_e2_level9" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level10'])?$select_result['serum_e2_level10']:""; ?>"   maxlength="20" name="serum_e2_level10" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level11'])?$select_result['serum_e2_level11']:""; ?>"   maxlength="20" name="serum_e2_level11" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level12'])?$select_result['serum_e2_level12']:""; ?>"   maxlength="20" name="serum_e2_level12" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level13'])?$select_result['serum_e2_level13']:""; ?>"   maxlength="20" name="serum_e2_level13" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level14'])?$select_result['serum_e2_level14']:""; ?>"   maxlength="20" name="serum_e2_level14" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level15'])?$select_result['serum_e2_level15']:""; ?>"   maxlength="20" name="serum_e2_level15" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level16'])?$select_result['serum_e2_level16']:""; ?>"   maxlength="20" name="serum_e2_level16" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level17'])?$select_result['serum_e2_level17']:""; ?>"   maxlength="20" name="serum_e2_level17" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level18'])?$select_result['serum_e2_level18']:""; ?>"   maxlength="20" name="serum_e2_level18" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level19'])?$select_result['serum_e2_level19']:""; ?>"   maxlength="20" name="serum_e2_level19" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level20'])?$select_result['serum_e2_level20']:""; ?>"   maxlength="20" name="serum_e2_level20" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level21'])?$select_result['serum_e2_level21']:""; ?>"   maxlength="20" name="serum_e2_level21" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level22'])?$select_result['serum_e2_level22']:""; ?>"   maxlength="20" name="serum_e2_level22" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level23'])?$select_result['serum_e2_level23']:""; ?>"   maxlength="20" name="serum_e2_level23" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level24'])?$select_result['serum_e2_level24']:""; ?>"   maxlength="20" name="serum_e2_level24" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level25'])?$select_result['serum_e2_level25']:""; ?>"   maxlength="20" name="serum_e2_level25" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level26'])?$select_result['serum_e2_level26']:""; ?>"   maxlength="20" name="serum_e2_level26" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_e2_level27'])?$select_result['serum_e2_level27']:""; ?>"   maxlength="20" name="serum_e2_level27" class="form-control"></td>

			</tr>

			<tr>

				<td>SERUM PROGESTERONE LEVEL</td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level1'])?$select_result['serum_progesterone_level1']:""; ?>"   maxlength="20" name="serum_progesterone_level1" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level2'])?$select_result['serum_progesterone_level2']:""; ?>"   maxlength="20" name="serum_progesterone_level2" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level3'])?$select_result['serum_progesterone_level3']:""; ?>"   maxlength="20" name="serum_progesterone_level3" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level4'])?$select_result['serum_progesterone_level4']:""; ?>"   maxlength="20" name="serum_progesterone_level4" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level5'])?$select_result['serum_progesterone_level5']:""; ?>"   maxlength="20" name="serum_progesterone_level5" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level6'])?$select_result['serum_progesterone_level6']:""; ?>"   maxlength="20" name="serum_progesterone_level6" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level7'])?$select_result['serum_progesterone_level7']:""; ?>"   maxlength="20" name="serum_progesterone_level7" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level8'])?$select_result['serum_progesterone_level8']:""; ?>"   maxlength="20" name="serum_progesterone_level8" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level9'])?$select_result['serum_progesterone_level9']:""; ?>"   maxlength="20" name="serum_progesterone_level9" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level10'])?$select_result['serum_progesterone_level10']:""; ?>"   maxlength="20" name="serum_progesterone_level10" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level11'])?$select_result['serum_progesterone_level11']:""; ?>"   maxlength="20" name="serum_progesterone_level11" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level12'])?$select_result['serum_progesterone_level12']:""; ?>"   maxlength="20" name="serum_progesterone_level12" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level13'])?$select_result['serum_progesterone_level13']:""; ?>"   maxlength="20" name="serum_progesterone_level13" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level14'])?$select_result['serum_progesterone_level14']:""; ?>"   maxlength="20" name="serum_progesterone_level14" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level15'])?$select_result['serum_progesterone_level15']:""; ?>"   maxlength="20" name="serum_progesterone_level15" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level16'])?$select_result['serum_progesterone_level16']:""; ?>"   maxlength="20" name="serum_progesterone_level16" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level17'])?$select_result['serum_progesterone_level17']:""; ?>"   maxlength="20" name="serum_progesterone_level17" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level18'])?$select_result['serum_progesterone_level18']:""; ?>"   maxlength="20" name="serum_progesterone_level18" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level19'])?$select_result['serum_progesterone_level19']:""; ?>"   maxlength="20" name="serum_progesterone_level19" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level20'])?$select_result['serum_progesterone_level20']:""; ?>"   maxlength="20" name="serum_progesterone_level20" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level21'])?$select_result['serum_progesterone_level21']:""; ?>"   maxlength="20" name="serum_progesterone_level21" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level22'])?$select_result['serum_progesterone_level22']:""; ?>"   maxlength="20" name="serum_progesterone_level22" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level23'])?$select_result['serum_progesterone_level23']:""; ?>"   maxlength="20" name="serum_progesterone_level23" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level24'])?$select_result['serum_progesterone_level24']:""; ?>"   maxlength="20" name="serum_progesterone_level24" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level25'])?$select_result['serum_progesterone_level25']:""; ?>"   maxlength="20" name="serum_progesterone_level25" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level26'])?$select_result['serum_progesterone_level26']:""; ?>"   maxlength="20" name="serum_progesterone_level26" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['serum_progesterone_level27'])?$select_result['serum_progesterone_level27']:""; ?>"   maxlength="20" name="serum_progesterone_level27" class="form-control"></td>

			</tr>

			<tr>

				<td>OTHER INVESTIGATION</td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['other_investigation1'])?$select_result['other_investigation1']:""; ?>"   maxlength="20" name="other_investigation1" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['other_investigation2'])?$select_result['other_investigation2']:""; ?>"   maxlength="20" name="other_investigation2" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['other_investigation3'])?$select_result['other_investigation3']:""; ?>"   maxlength="20" name="other_investigation3" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['other_investigation4'])?$select_result['other_investigation4']:""; ?>"   maxlength="20" name="other_investigation4" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['other_investigation5'])?$select_result['other_investigation5']:""; ?>"   maxlength="20" name="other_investigation5" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['other_investigation6'])?$select_result['other_investigation6']:""; ?>"   maxlength="20" name="other_investigation6" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['other_investigation7'])?$select_result['other_investigation7']:""; ?>"   maxlength="20" name="other_investigation7" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['other_investigation8'])?$select_result['other_investigation8']:""; ?>"   maxlength="20" name="other_investigation8" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['other_investigation9'])?$select_result['other_investigation9']:""; ?>"   maxlength="20" name="other_investigation9" class="form-control"></td>

				<td style="background-color: yellow;"><input  type="text" value="<?php echo isset($select_result['other_investigation10'])?$select_result['other_investigation10']:""; ?>"   maxlength="20" name="other_investigation10" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation11'])?$select_result['other_investigation11']:""; ?>"   maxlength="20" name="other_investigation11" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation12'])?$select_result['other_investigation12']:""; ?>"   maxlength="20" name="other_investigation12" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation13'])?$select_result['other_investigation13']:""; ?>"   maxlength="20" name="other_investigation13" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation14'])?$select_result['other_investigation14']:""; ?>"   maxlength="20" name="other_investigation14" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation15'])?$select_result['other_investigation15']:""; ?>"   maxlength="20" name="other_investigation15" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation16'])?$select_result['other_investigation16']:""; ?>"   maxlength="20" name="other_investigation16" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation17'])?$select_result['other_investigation17']:""; ?>"   maxlength="20" name="other_investigation17" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation18'])?$select_result['other_investigation18']:""; ?>"   maxlength="20" name="other_investigation18" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation19'])?$select_result['other_investigation19']:""; ?>"   maxlength="20" name="other_investigation19" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation20'])?$select_result['other_investigation20']:""; ?>"   maxlength="20" name="other_investigation20" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation21'])?$select_result['other_investigation21']:""; ?>"   maxlength="20" name="other_investigation21" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation22'])?$select_result['other_investigation22']:""; ?>"   maxlength="20" name="other_investigation22" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation23'])?$select_result['other_investigation23']:""; ?>"   maxlength="20" name="other_investigation23" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation24'])?$select_result['other_investigation24']:""; ?>"   maxlength="20" name="other_investigation24" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation25'])?$select_result['other_investigation25']:""; ?>"   maxlength="20" name="other_investigation25" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation26'])?$select_result['other_investigation26']:""; ?>"   maxlength="20" name="other_investigation26" class="form-control"></td>

				<td style="background-color: orange;"><input  type="text" value="<?php echo isset($select_result['other_investigation27'])?$select_result['other_investigation27']:""; ?>"   maxlength="20" name="other_investigation27" class="form-control"></td>

			</tr>

		</table>

		<table width="100%">

			<tr>

				<td>DOCTOR <input  type="text" readonly="" value="<?php if (!empty($select_result['doctor'])) {  echo $select_result['doctor']; } else {  echo isset($_SESSION['logged_doctor']['name']) ? $_SESSION['logged_doctor']['name'] : ''; }  ?>"   maxlength="20" name="doctor" class="form-control"></td>

				<td>COUNSELLOR <input  type="text" value="<?php echo isset($select_result['counsellor'])?$select_result['counsellor']:""; ?>"   maxlength="20" name="counsellor" class="form-control"></td>

				<td>NURSE <input  type="text" value="<?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?>"   maxlength="20" name="nurse" class="form-control"></td>

			</tr>

		</table>

	</div>

	<!-- /.card-body -->

	<div class="card-footer">

		<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->

		<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">

	</div>

</form>


<!-- print -->

<input type="button" id="btn" value="Print" class="btn btn-primary pull-right printbtn" onclick="printtable();">
            
<div  class="printtable pttable"  id="printtable"  style="display: none;">  
<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">
 <tr>
                <th width="50%" colspan="2" style="border:1px solid #cdcdcd;"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></th>
				<th width="50%" colspan="2" style="border:1px solid #cdcdcd;"><center><h3>PRE EMBRYO TRANSFER SECOND CYCLE</h3></center></th>
				
			</tr>

	<tr>

			<td colspan="2">SELF CYCLE (S)</td>

			<td style="color: black;" colspan="2">SURROGATE MOTHER CYCLE (SUR)</td>

		</tr>

<tr>

			<td>Partners name</td>

			<td style="width:20%"><?php echo isset($select_result['partners_name'])?$select_result['partners_name']:""; ?></td>

			<td style="color: black;">ART bank reg no</td>

			<td style="width:20%"><?php echo isset($select_result['art_bank_reg_no'])?$select_result['art_bank_reg_no']:""; ?></td>

		</tr>
		
		<tr>

			<td colspan="2">UHID</td>

			<td style="width:20%" colspan="2"> <?php echo $select_result5['center_code']."/".$select_result4['uhid']; ?> </td>


		</tr>

<tr>

			<td>ID</td>

			<td style="width:20%"><?php echo isset($select_result['form_id'])?$select_result['form_id']:""; ?></td>

			<td style="color: black;">Surrogate ID</td>

			<td style="width:20%"><?php echo isset($select_result['surrogate_id'])?$select_result['surrogate_id']:""; ?></td>

		</tr>

		<tr>

			<td colspan="2">LAST MENSTRUAL PERIOD</td>

			<td colspan="2" style="width:20%"><?php echo isset($select_result['last_menstrual_period'])?$select_result['last_menstrual_period']:""; ?></td>

		</tr>


</table>

<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">


<tr>

				<td style="width: 10%;"><br></td>

				<td colspan="10">ESTROGEN</td>

				<td colspan="17" >PROGESTERONE</td>

			</tr>

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

				<td>17</td>

				<td>18</td>

				<td>19</td>

				<td>20</td>

				<td>21</td>

				<td>22</td>

				<td>23</td>

				<td>24</td>

				<td>25</td>

				<td>26</td>

				<td>27</td>

			</tr>

			<tr>

				<td>DATE</td>

				<td><?php echo isset($select_result['date1'])?$select_result['date1']:""; ?></td>

				<td><?php echo isset($select_result['date2'])?$select_result['date2']:""; ?></td>

				<td><?php echo isset($select_result['date3'])?$select_result['date3']:""; ?></td>

				<td><?php echo isset($select_result['date4'])?$select_result['date4']:""; ?></td>

				<td><?php echo isset($select_result['date5'])?$select_result['date5']:""; ?></td>

				<td><?php echo isset($select_result['date6'])?$select_result['date6']:""; ?></td>

				<td><?php echo isset($select_result['date7'])?$select_result['date7']:""; ?></td>

				<td><?php echo isset($select_result['date8'])?$select_result['date8']:""; ?></td>

				<td><?php echo isset($select_result['date9'])?$select_result['date9']:""; ?></td>

				<td><?php echo isset($select_result['date10'])?$select_result['date10']:""; ?></td>

				<td><?php echo isset($select_result['date11'])?$select_result['date11']:""; ?></td>

				<td><?php echo isset($select_result['date12'])?$select_result['date12']:""; ?></td>

				<td><?php echo isset($select_result['date13'])?$select_result['date13']:""; ?></td>

				<td><?php echo isset($select_result['date14'])?$select_result['date14']:""; ?></td>

				<td><?php echo isset($select_result['date15'])?$select_result['date15']:""; ?></td>

				<td><?php echo isset($select_result['date16'])?$select_result['date16']:""; ?></td>

				<td><?php echo isset($select_result['date17'])?$select_result['date17']:""; ?></td>

				<td><?php echo isset($select_result['date18'])?$select_result['date18']:""; ?></td>

				<td><?php echo isset($select_result['date19'])?$select_result['date19']:""; ?></td>

				<td><?php echo isset($select_result['date20'])?$select_result['date20']:""; ?></td>

				<td><?php echo isset($select_result['date21'])?$select_result['date21']:""; ?></td>

				<td><?php echo isset($select_result['date22'])?$select_result['date22']:""; ?></td>

				<td><?php echo isset($select_result['date23'])?$select_result['date23']:""; ?></td>

				<td><?php echo isset($select_result['date24'])?$select_result['date24']:""; ?></td>

				<td><?php echo isset($select_result['date25'])?$select_result['date25']:""; ?></td>

				<td><?php echo isset($select_result['date26'])?$select_result['date26']:""; ?></td>

				<td><?php echo isset($select_result['date27'])?$select_result['date27']:""; ?></td>

			</tr>

			<tr>

				<td>ENDOMETRIAL THICKNESS (cm)</td>

				<td><?php echo isset($select_result['endometrial_thickness1'])?$select_result['endometrial_thickness1']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness2'])?$select_result['endometrial_thickness2']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness3'])?$select_result['endometrial_thickness3']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness4'])?$select_result['endometrial_thickness4']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness5'])?$select_result['endometrial_thickness5']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness6'])?$select_result['endometrial_thickness6']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness7'])?$select_result['endometrial_thickness7']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness8'])?$select_result['endometrial_thickness8']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness9'])?$select_result['endometrial_thickness9']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness10'])?$select_result['endometrial_thickness10']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness11'])?$select_result['endometrial_thickness11']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness12'])?$select_result['endometrial_thickness12']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness13'])?$select_result['endometrial_thickness13']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness14'])?$select_result['endometrial_thickness14']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness15'])?$select_result['endometrial_thickness15']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness16'])?$select_result['endometrial_thickness16']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness17'])?$select_result['endometrial_thickness17']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness18'])?$select_result['endometrial_thickness18']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness19'])?$select_result['endometrial_thickness19']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness20'])?$select_result['endometrial_thickness20']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness21'])?$select_result['endometrial_thickness21']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness22'])?$select_result['endometrial_thickness22']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness23'])?$select_result['endometrial_thickness23']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness24'])?$select_result['endometrial_thickness24']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness25'])?$select_result['endometrial_thickness25']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness26'])?$select_result['endometrial_thickness26']:""; ?></td>

				<td><?php echo isset($select_result['endometrial_thickness27'])?$select_result['endometrial_thickness27']:""; ?></td>

			</tr>

			<tr>

				<td>ESTROGEN</td>

				<td><?php echo isset($select_result['estrogen1'])?$select_result['estrogen1']:""; ?></td>

				<td><?php echo isset($select_result['estrogen2'])?$select_result['estrogen2']:""; ?></td>

				<td><?php echo isset($select_result['estrogen3'])?$select_result['estrogen3']:""; ?></td>

				<td><?php echo isset($select_result['estrogen4'])?$select_result['estrogen4']:""; ?></td>

				<td><?php echo isset($select_result['estrogen5'])?$select_result['estrogen5']:""; ?></td>

				<td><?php echo isset($select_result['estrogen6'])?$select_result['estrogen6']:""; ?></td>

				<td><?php echo isset($select_result['estrogen7'])?$select_result['estrogen7']:""; ?></td>

				<td><?php echo isset($select_result['estrogen8'])?$select_result['estrogen8']:""; ?></td>

				<td><?php echo isset($select_result['estrogen9'])?$select_result['estrogen9']:""; ?></td>

				<td><?php echo isset($select_result['estrogen10'])?$select_result['estrogen10']:""; ?></td>

				<td><?php echo isset($select_result['estrogen11'])?$select_result['estrogen11']:""; ?></td>

				<td><?php echo isset($select_result['estrogen12'])?$select_result['estrogen12']:""; ?></td>

				<td><?php echo isset($select_result['estrogen13'])?$select_result['estrogen13']:""; ?></td>

				<td><?php echo isset($select_result['estrogen14'])?$select_result['estrogen14']:""; ?></td>

				<td><?php echo isset($select_result['estrogen15'])?$select_result['estrogen15']:""; ?></td>

				<td><?php echo isset($select_result['estrogen16'])?$select_result['estrogen16']:""; ?></td>

				<td><?php echo isset($select_result['estrogen17'])?$select_result['estrogen17']:""; ?></td>

				<td><?php echo isset($select_result['estrogen18'])?$select_result['estrogen18']:""; ?></td>

				<td><?php echo isset($select_result['estrogen19'])?$select_result['estrogen19']:""; ?></td>

				<td><?php echo isset($select_result['estrogen20'])?$select_result['estrogen20']:""; ?></td>

				<td><?php echo isset($select_result['estrogen21'])?$select_result['estrogen21']:""; ?></td>

				<td><?php echo isset($select_result['estrogen22'])?$select_result['estrogen22']:""; ?></td>

				<td><?php echo isset($select_result['estrogen23'])?$select_result['estrogen23']:""; ?></td>

				<td><?php echo isset($select_result['estrogen24'])?$select_result['estrogen24']:""; ?></td>

				<td><?php echo isset($select_result['estrogen25'])?$select_result['estrogen25']:""; ?></td>

				<td><?php echo isset($select_result['estrogen26'])?$select_result['estrogen26']:""; ?></td>

				<td><?php echo isset($select_result['estrogen27'])?$select_result['estrogen27']:""; ?></td>

			</tr>

			<tr>

				<td>PROGESTERONE</td>

				<td><?php echo isset($select_result['progesterone1'])?$select_result['progesterone1']:""; ?></td>

				<td><?php echo isset($select_result['progesterone2'])?$select_result['progesterone2']:""; ?></td>

				<td><?php echo isset($select_result['progesterone3'])?$select_result['progesterone3']:""; ?></td>

				<td><?php echo isset($select_result['progesterone4'])?$select_result['progesterone4']:""; ?></td>

				<td><?php echo isset($select_result['progesterone5'])?$select_result['progesterone5']:""; ?></td>

				<td><?php echo isset($select_result['progesterone6'])?$select_result['progesterone6']:""; ?></td>

				<td><?php echo isset($select_result['progesterone7'])?$select_result['progesterone7']:""; ?></td>

				<td><?php echo isset($select_result['progesterone8'])?$select_result['progesterone8']:""; ?></td>

				<td><?php echo isset($select_result['progesterone9'])?$select_result['progesterone9']:""; ?></td>

				<td><?php echo isset($select_result['progesterone10'])?$select_result['progesterone10']:""; ?></td>

				<td><?php echo isset($select_result['progesterone11'])?$select_result['progesterone11']:""; ?></td>

				<td><?php echo isset($select_result['progesterone12'])?$select_result['progesterone12']:""; ?></td>

				<td><?php echo isset($select_result['progesterone13'])?$select_result['progesterone13']:""; ?></td>

				<td><?php echo isset($select_result['progesterone14'])?$select_result['progesterone14']:""; ?></td>

				<td><?php echo isset($select_result['progesterone15'])?$select_result['progesterone15']:""; ?></td>

				<td><?php echo isset($select_result['progesterone16'])?$select_result['progesterone16']:""; ?></td>

				<td><?php echo isset($select_result['progesterone17'])?$select_result['progesterone17']:""; ?></td>

				<td><?php echo isset($select_result['progesterone18'])?$select_result['progesterone18']:""; ?></td>

				<td><?php echo isset($select_result['progesterone19'])?$select_result['progesterone19']:""; ?></td>

				<td><?php echo isset($select_result['progesterone20'])?$select_result['progesterone20']:""; ?></td>

				<td><?php echo isset($select_result['progesterone21'])?$select_result['progesterone21']:""; ?></td>

				<td><?php echo isset($select_result['progesterone22'])?$select_result['progesterone22']:""; ?></td>

				<td><?php echo isset($select_result['progesterone23'])?$select_result['progesterone23']:""; ?></td>

				<td><?php echo isset($select_result['progesterone24'])?$select_result['progesterone24']:""; ?></td>

				<td><?php echo isset($select_result['progesterone25'])?$select_result['progesterone25']:""; ?></td>

				<td><?php echo isset($select_result['progesterone26'])?$select_result['progesterone26']:""; ?></td>

				<td><?php echo isset($select_result['progesterone27'])?$select_result['progesterone27']:""; ?></td>

			</tr>

			<tr>

				<td>MEDICINE ADDED</td>

				<td><?php echo isset($select_result['medicine_added1'])?$select_result['medicine_added1']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added2'])?$select_result['medicine_added2']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added3'])?$select_result['medicine_added3']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added4'])?$select_result['medicine_added4']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added5'])?$select_result['medicine_added5']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added6'])?$select_result['medicine_added6']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added7'])?$select_result['medicine_added7']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added8'])?$select_result['medicine_added8']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added9'])?$select_result['medicine_added9']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added10'])?$select_result['medicine_added10']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added11'])?$select_result['medicine_added11']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added12'])?$select_result['medicine_added12']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added13'])?$select_result['medicine_added13']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added14'])?$select_result['medicine_added14']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added15'])?$select_result['medicine_added15']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added16'])?$select_result['medicine_added16']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added17'])?$select_result['medicine_added17']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added18'])?$select_result['medicine_added18']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added19'])?$select_result['medicine_added19']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added20'])?$select_result['medicine_added20']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added21'])?$select_result['medicine_added21']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added22'])?$select_result['medicine_added22']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added23'])?$select_result['medicine_added23']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added24'])?$select_result['medicine_added24']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added25'])?$select_result['medicine_added25']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added26'])?$select_result['medicine_added26']:""; ?></td>

				<td><?php echo isset($select_result['medicine_added27'])?$select_result['medicine_added27']:""; ?></td>

			</tr>

			<tr>

				<td>REMARKS</td>

				<td><?php echo isset($select_result['remarks1'])?$select_result['remarks1']:""; ?></td>

				<td><?php echo isset($select_result['remarks2'])?$select_result['remarks2']:""; ?></td>

				<td><?php echo isset($select_result['remarks3'])?$select_result['remarks3']:""; ?></td>

				<td><?php echo isset($select_result['remarks4'])?$select_result['remarks4']:""; ?></td>

				<td><?php echo isset($select_result['remarks5'])?$select_result['remarks5']:""; ?></td>

				<td><?php echo isset($select_result['remarks6'])?$select_result['remarks6']:""; ?></td>

				<td><?php echo isset($select_result['remarks7'])?$select_result['remarks7']:""; ?></td>

				<td><?php echo isset($select_result['remarks8'])?$select_result['remarks8']:""; ?></td>

				<td><?php echo isset($select_result['remarks9'])?$select_result['remarks9']:""; ?></td>

				<td><?php echo isset($select_result['remarks10'])?$select_result['remarks10']:""; ?></td>

				<td><?php echo isset($select_result['remarks11'])?$select_result['remarks11']:""; ?></td>

				<td><?php echo isset($select_result['remarks12'])?$select_result['remarks12']:""; ?></td>

				<td><?php echo isset($select_result['remarks13'])?$select_result['remarks13']:""; ?></td>

				<td><?php echo isset($select_result['remarks14'])?$select_result['remarks14']:""; ?></td>

				<td><?php echo isset($select_result['remarks15'])?$select_result['remarks15']:""; ?></td>

				<td><?php echo isset($select_result['remarks16'])?$select_result['remarks16']:""; ?></td>

				<td><?php echo isset($select_result['remarks17'])?$select_result['remarks17']:""; ?></td>

				<td><?php echo isset($select_result['remarks18'])?$select_result['remarks18']:""; ?></td>

				<td><?php echo isset($select_result['remarks19'])?$select_result['remarks19']:""; ?></td>

				<td><?php echo isset($select_result['remarks20'])?$select_result['remarks20']:""; ?></td>

				<td><?php echo isset($select_result['remarks21'])?$select_result['remarks21']:""; ?></td>

				<td><?php echo isset($select_result['remarks22'])?$select_result['remarks22']:""; ?></td>

				<td><?php echo isset($select_result['remarks23'])?$select_result['remarks23']:""; ?></td>

				<td><?php echo isset($select_result['remarks24'])?$select_result['remarks24']:""; ?></td>

				<td><?php echo isset($select_result['remarks25'])?$select_result['remarks25']:""; ?></td>

				<td><?php echo isset($select_result['remarks26'])?$select_result['remarks26']:""; ?></td>

				<td><?php echo isset($select_result['remarks27'])?$select_result['remarks27']:""; ?></td>

			</tr>

			<tr>

				<td>FOLLOWUP ON</td>

				<td><?php echo isset($select_result['followup_on1'])?$select_result['followup_on1']:""; ?></td>

				<td><?php echo isset($select_result['followup_on2'])?$select_result['followup_on2']:""; ?></td>

				<td><?php echo isset($select_result['followup_on3'])?$select_result['followup_on3']:""; ?></td>

				<td><?php echo isset($select_result['followup_on4'])?$select_result['followup_on4']:""; ?></td>

				<td><?php echo isset($select_result['followup_on5'])?$select_result['followup_on5']:""; ?></td>

				<td><?php echo isset($select_result['followup_on6'])?$select_result['followup_on6']:""; ?></td>

				<td><?php echo isset($select_result['followup_on7'])?$select_result['followup_on7']:""; ?></td>

				<td><?php echo isset($select_result['followup_on8'])?$select_result['followup_on8']:""; ?></td>

				<td><?php echo isset($select_result['followup_on9'])?$select_result['followup_on9']:""; ?></td>

				<td><?php echo isset($select_result['followup_on10'])?$select_result['followup_on10']:""; ?></td>

				<td><?php echo isset($select_result['followup_on11'])?$select_result['followup_on11']:""; ?></td>

				<td><?php echo isset($select_result['followup_on12'])?$select_result['followup_on12']:""; ?></td>

				<td><?php echo isset($select_result['followup_on13'])?$select_result['followup_on13']:""; ?></td>

				<td><?php echo isset($select_result['followup_on14'])?$select_result['followup_on14']:""; ?></td>

				<td><?php echo isset($select_result['followup_on15'])?$select_result['followup_on15']:""; ?></td>

				<td><?php echo isset($select_result['followup_on16'])?$select_result['followup_on16']:""; ?></td>

				<td><?php echo isset($select_result['followup_on17'])?$select_result['followup_on17']:""; ?></td>

				<td><?php echo isset($select_result['followup_on18'])?$select_result['followup_on18']:""; ?></td>

				<td><?php echo isset($select_result['followup_on19'])?$select_result['followup_on19']:""; ?></td>

				<td><?php echo isset($select_result['followup_on20'])?$select_result['followup_on20']:""; ?></td>

				<td><?php echo isset($select_result['followup_on21'])?$select_result['followup_on21']:""; ?></td>

				<td><?php echo isset($select_result['followup_on22'])?$select_result['followup_on22']:""; ?></td>

				<td><?php echo isset($select_result['followup_on23'])?$select_result['followup_on23']:""; ?></td>

				<td><?php echo isset($select_result['followup_on24'])?$select_result['followup_on24']:""; ?></td>

				<td><?php echo isset($select_result['followup_on25'])?$select_result['followup_on25']:""; ?></td>

				<td><?php echo isset($select_result['followup_on26'])?$select_result['followup_on26']:""; ?></td>

				<td><?php echo isset($select_result['followup_on27'])?$select_result['followup_on27']:""; ?></td>

			</tr>

			<tr>

				<td>SERUM ESTRADIOL (E2) LEVEL</td>

				<td><?php echo isset($select_result['serum_e2_level1'])?$select_result['serum_e2_level1']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level2'])?$select_result['serum_e2_level2']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level3'])?$select_result['serum_e2_level3']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level4'])?$select_result['serum_e2_level4']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level5'])?$select_result['serum_e2_level5']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level6'])?$select_result['serum_e2_level6']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level7'])?$select_result['serum_e2_level7']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level8'])?$select_result['serum_e2_level8']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level9'])?$select_result['serum_e2_level9']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level10'])?$select_result['serum_e2_level10']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level11'])?$select_result['serum_e2_level11']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level12'])?$select_result['serum_e2_level12']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level13'])?$select_result['serum_e2_level13']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level14'])?$select_result['serum_e2_level14']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level15'])?$select_result['serum_e2_level15']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level16'])?$select_result['serum_e2_level16']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level17'])?$select_result['serum_e2_level17']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level18'])?$select_result['serum_e2_level18']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level19'])?$select_result['serum_e2_level19']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level20'])?$select_result['serum_e2_level20']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level21'])?$select_result['serum_e2_level21']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level22'])?$select_result['serum_e2_level22']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level23'])?$select_result['serum_e2_level23']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level24'])?$select_result['serum_e2_level24']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level25'])?$select_result['serum_e2_level25']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level26'])?$select_result['serum_e2_level26']:""; ?></td>

				<td><?php echo isset($select_result['serum_e2_level27'])?$select_result['serum_e2_level27']:""; ?></td>

			</tr>

			<tr>

				<td>SERUM PROGESTERONE LEVEL</td>

				<td><?php echo isset($select_result['serum_progesterone_level1'])?$select_result['serum_progesterone_level1']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level2'])?$select_result['serum_progesterone_level2']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level3'])?$select_result['serum_progesterone_level3']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level4'])?$select_result['serum_progesterone_level4']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level5'])?$select_result['serum_progesterone_level5']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level6'])?$select_result['serum_progesterone_level6']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level7'])?$select_result['serum_progesterone_level7']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level8'])?$select_result['serum_progesterone_level8']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level9'])?$select_result['serum_progesterone_level9']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level10'])?$select_result['serum_progesterone_level10']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level11'])?$select_result['serum_progesterone_level11']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level12'])?$select_result['serum_progesterone_level12']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level13'])?$select_result['serum_progesterone_level13']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level14'])?$select_result['serum_progesterone_level14']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level15'])?$select_result['serum_progesterone_level15']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level16'])?$select_result['serum_progesterone_level16']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level17'])?$select_result['serum_progesterone_level17']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level18'])?$select_result['serum_progesterone_level18']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level19'])?$select_result['serum_progesterone_level19']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level20'])?$select_result['serum_progesterone_level20']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level21'])?$select_result['serum_progesterone_level21']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level22'])?$select_result['serum_progesterone_level22']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level23'])?$select_result['serum_progesterone_level23']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level24'])?$select_result['serum_progesterone_level24']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level25'])?$select_result['serum_progesterone_level25']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level26'])?$select_result['serum_progesterone_level26']:""; ?></td>

				<td><?php echo isset($select_result['serum_progesterone_level27'])?$select_result['serum_progesterone_level27']:""; ?></td>

			</tr>

			<tr>

				<td>OTHER INVESTIGATION</td>

				<td><?php echo isset($select_result['other_investigation1'])?$select_result['other_investigation1']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation2'])?$select_result['other_investigation2']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation3'])?$select_result['other_investigation3']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation4'])?$select_result['other_investigation4']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation5'])?$select_result['other_investigation5']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation6'])?$select_result['other_investigation6']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation7'])?$select_result['other_investigation7']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation8'])?$select_result['other_investigation8']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation9'])?$select_result['other_investigation9']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation10'])?$select_result['other_investigation10']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation11'])?$select_result['other_investigation11']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation12'])?$select_result['other_investigation12']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation13'])?$select_result['other_investigation13']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation14'])?$select_result['other_investigation14']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation15'])?$select_result['other_investigation15']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation16'])?$select_result['other_investigation16']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation17'])?$select_result['other_investigation17']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation18'])?$select_result['other_investigation18']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation19'])?$select_result['other_investigation19']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation20'])?$select_result['other_investigation20']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation21'])?$select_result['other_investigation21']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation22'])?$select_result['other_investigation22']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation23'])?$select_result['other_investigation23']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation24'])?$select_result['other_investigation24']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation25'])?$select_result['other_investigation25']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation26'])?$select_result['other_investigation26']:""; ?></td>

				<td><?php echo isset($select_result['other_investigation27'])?$select_result['other_investigation27']:""; ?></td>

			</tr>

</table>

<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">

			<tr>

				<td>DOCTOR <?php echo isset($select_result['doctor'])?$select_result['doctor']:""; ?></td>

				<td>COUNSELLOR <?php echo isset($select_result['counsellor'])?$select_result['counsellor']:""; ?></td>

				<td>NURSE <?php echo isset($select_result['nurse'])?$select_result['nurse']:""; ?></td>

			</tr>

		</table>

</div>	

<script>
function printtable() 
{
    //alert();
        
  $('.searchform').hide();
   $('.printbtn').hide();
  $('.printbtn').css('display', 'hide');
  $('.pttable').css('display', 'block');
  var divToPrint=document.getElementById('printtable');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
  setTimeout(function(){newWin.close();},10);
  window.location.reload();
}
</script>  