<?php
if(isset($_POST['submit'])){
	unset($_POST['submit']);
	
	$select_query = "SELECT * FROM `hms_psychological_questionnaire` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
	$select_result = run_select_query($select_query); 
	if(empty($select_result)){
		// mysql query to insert data
		$query = "INSERT INTO `hms_psychological_questionnaire` SET ";
		$sqlArr = array();
		foreach( $_POST as $key=> $value )
		{
		  $sqlArr[] = " $key = '".addslashes($value)."'";
		}		
		$query .= implode(',' , $sqlArr);
	}else{
		// mysql query to update data
		$query = "UPDATE hms_psychological_questionnaire SET ";
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
$select_query = "SELECT * FROM `hms_psychological_questionnaire` WHERE patient_id='$patient_id' and receipt_number='$receipt_number'";
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
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PSYCHOLOGICAL QUESTIONNAIRE</h3></td>
   </tr>
</table>
     				  <table width="100%" class="vb45rt">
<tbody>
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
		<td>I feel fine</td>
		<td>
			<select class="form-control" name="feel_fine">
							
				
				<option <?php if(isset($select_result['feel_fine']) && $select_result['feel_fine'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['feel_fine']) && $select_result['feel_fine'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['feel_fine']) && $select_result['feel_fine'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['feel_fine']) && $select_result['feel_fine'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I feel satisfied</td>
		<td>
			<select class="form-control" name="feel_satisfied">
			
				
					<option <?php if(isset($select_result['feel_satisfied']) && $select_result['feel_satisfied'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['feel_satisfied']) && $select_result['feel_satisfied'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['feel_satisfied']) && $select_result['feel_satisfied'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['feel_satisfied']) && $select_result['feel_satisfied'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I worry too much about not really important things</td>
		<td>
			<select class="form-control" name="not_important">
			
				<option <?php if(isset($select_result['not_important']) && $select_result['not_important'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['not_important']) && $select_result['not_important'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['not_important']) && $select_result['not_important'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['not_important']) && $select_result['not_important'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I am happy</td>
		<td>
			<select class="form-control" name="happy">
				
				<option <?php if(isset($select_result['happy']) && $select_result['happy'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['happy']) && $select_result['happy'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['happy']) && $select_result['happy'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['happy']) && $select_result['happy'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I am troubled by disturbing thoughts</td>
		<td>
			<select class="form-control" name="troubled_dusturbing">
				
				
			<option <?php if(isset($select_result['troubled_dusturbing']) && $select_result['troubled_dusturbing'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['troubled_dusturbing']) && $select_result['troubled_dusturbing'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['troubled_dusturbing']) && $select_result['troubled_dusturbing'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['troubled_dusturbing']) && $select_result['troubled_dusturbing'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>	
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I feel safe</td>
		<td>
			<select class="form-control" name="feel_safe">
								
				<option <?php if(isset($select_result['feel_safe']) && $select_result['feel_safe'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['feel_safe']) && $select_result['feel_safe'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['feel_safe']) && $select_result['feel_safe'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['feel_safe']) && $select_result['feel_safe'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>	
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I am pleased</td>
		<td>
			<select class="form-control" name="pleased">
			
				
				<option <?php if(isset($select_result['pleased']) && $select_result['pleased'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['pleased']) && $select_result['pleased'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['pleased']) && $select_result['pleased'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['pleased']) && $select_result['pleased'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>There are thoughts that keep haunting me</td>
		<td>
			<select class="form-control" name="haunting">
							
				<option <?php if(isset($select_result['haunting']) && $select_result['haunting'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['haunting']) && $select_result['haunting'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['haunting']) && $select_result['haunting'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['haunting']) && $select_result['haunting'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I take disappointments so seriously that I cannot get them out of my mind</td>
		<td>
			<select class="form-control" name="disappointments">
				
				<option <?php if(isset($select_result['disappointments']) && $select_result['disappointments'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['disappointments']) && $select_result['disappointments'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['disappointments']) && $select_result['disappointments'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['disappointments']) && $select_result['disappointments'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I get very nervous and worried when thinking about my current troubles</td>
		<td>
			<select class="form-control" name="nervous">
								
				<option <?php if(isset($select_result['nervous']) && $select_result['nervous'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['nervous']) && $select_result['nervous'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['nervous']) && $select_result['nervous'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['nervous']) && $select_result['nervous'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p>* reverse scoring, see instruction. Do not indicate asterisks on patient form</p>
			<p>Cut off 24 and above</p>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<h3>Depression</h3>
			<p>So it is about how you felt during the last week.</p>
		</td>
	</tr>
	<tr>
		<td>Are you feeling sad?</td>
		<td>
			<select class="form-control" name="feeling_sad">
				
			<option <?php if(isset($select_result['feeling_sad']) && $select_result['feeling_sad'] == "1"){echo 'selected="selected"'; }?>   value="1">I do not feel sad.</option>
			<option <?php if(isset($select_result['feeling_sad']) && $select_result['feeling_sad'] == "2"){echo 'selected="selected"'; }?>   value="2">I feel sad.</option>
				
			<option <?php if(isset($select_result['feeling_sad']) && $select_result['feeling_sad'] == "3"){echo 'selected="selected"'; }?>   value="3">I am sad all the time and I can’t snap out of it.</option>
				
			<option <?php if(isset($select_result['feeling_sad']) && $select_result['feeling_sad'] == "4"){echo 'selected="selected"'; }?>   value="4">I am so sad and unhappy that I can’t stand it.</option>
				
			
			</select>
		</td>
	</tr>
	<tr>
		<td>Are you feeling discouraged?</td>
		<td>
			<select class="form-control" name="discouraged">
					
			<option <?php if(isset($select_result['discouraged']) && $select_result['discouraged'] == "1"){echo 'selected="selected"'; }?>   value="1">I am not particularly discouraged about the future.</option>
			<option <?php if(isset($select_result['discouraged']) && $select_result['discouraged'] == "2"){echo 'selected="selected"'; }?>   value="2">I feel discouraged about the future.</option>
				
			<option <?php if(isset($select_result['discouraged']) && $select_result['discouraged'] == "3"){echo 'selected="selected"'; }?>   value="3">I feel I have nothing to look forward to.</option>
				
			<option <?php if(isset($select_result['discouraged']) && $select_result['discouraged'] == "4"){echo 'selected="selected"'; }?>   value="4">I feel the future is hopeless and that things cannot improve.</option>
			
				
			</select>
		</td>
	</tr>
	<tr>
		<td>Are do you feel about failure?</td>
		<td>
			<select class="form-control" name="failure">
			
				
			<option <?php if(isset($select_result['failure']) && $select_result['failure'] == "1"){echo 'selected="selected"'; }?>   value="1">I do not feel like a failure.</option>
			<option <?php if(isset($select_result['failure']) && $select_result['failure'] == "2"){echo 'selected="selected"'; }?>   value="2">I feel I have failed more than the average person.</option>
				
			<option <?php if(isset($select_result['failure']) && $select_result['failure'] == "3"){echo 'selected="selected"'; }?>   value="3">As I look back on my life, all I can see is a lot of failures.</option>
				
			<option <?php if(isset($select_result['failure']) && $select_result['failure'] == "4"){echo 'selected="selected"'; }?>   value="4">I feel I am a complete failure as a person.</option>
				
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>How much satisfied are you?</td>
		<td>
			<select class="form-control" name="satisfied">
			
				
			<option <?php if(isset($select_result['satisfied']) && $select_result['satisfied'] == "1"){echo 'selected="selected"'; }?>   value="1">I get as much satisfaction out of things as I used to.</option>
			<option <?php if(isset($select_result['satisfied']) && $select_result['satisfied'] == "2"){echo 'selected="selected"'; }?>   value="2">I don’t enjoy things the way I used to.</option>
				
			<option <?php if(isset($select_result['satisfied']) && $select_result['satisfied'] == "3"){echo 'selected="selected"'; }?>   value="3">I don’t get real satisfaction out of anything anymore.</option>
				
			<option <?php if(isset($select_result['satisfied']) && $select_result['satisfied'] == "4"){echo 'selected="selected"'; }?>   value="4">I am dissatisfied or bored with everything.</option>	
				
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>Do you feel disappointed?</td>
		<td>
			<select class="form-control" name="disappointed">
			
						
			<option <?php if(isset($select_result['disappointed']) && $select_result['disappointed'] == "1"){echo 'selected="selected"'; }?>   value="1">I don’t feel disappointed in myself.</option>
			<option <?php if(isset($select_result['disappointed']) && $select_result['disappointed'] == "2"){echo 'selected="selected"'; }?>   value="2">I am disappointed in myself.</option>
				
			<option <?php if(isset($select_result['disappointed']) && $select_result['disappointed'] == "3"){echo 'selected="selected"'; }?>   value="3">I am disgusted with myself.</option>
				
			<option <?php if(isset($select_result['disappointed']) && $select_result['disappointed'] == "4"){echo 'selected="selected"'; }?>   value="4">I hate myself.</option>	
				
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>How do you feel about yourself in comparison with others?</td>
		<td>
			<select class="form-control" name="comparison">
				
			
					
			<option <?php if(isset($select_result['comparison']) && $select_result['comparison'] == "1"){echo 'selected="selected"'; }?>   value="1">I don’t feel I am any worse than anybody else.</option>
			<option <?php if(isset($select_result['comparison']) && $select_result['comparison'] == "2"){echo 'selected="selected"'; }?>   value="2">I am critical of myself for my weaknesses or mistakes.</option>
				
			<option <?php if(isset($select_result['comparison']) && $select_result['comparison'] == "3"){echo 'selected="selected"'; }?>   value="3">I blame myself all the time for my faults.</option>
				
			<option <?php if(isset($select_result['comparison']) && $select_result['comparison'] == "4"){echo 'selected="selected"'; }?>   value="4">I blame myself for everything bad that happens.</option>	
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>Do think of committing suicide?</td>
		<td>
			<select class="form-control" name="suicide">
				
			
			<option <?php if(isset($select_result['suicide']) && $select_result['suicide'] == "1"){echo 'selected="selected"'; }?>   value="1">I don’t have any thoughts about killing myself.</option>
			<option <?php if(isset($select_result['suicide']) && $select_result['suicide'] == "2"){echo 'selected="selected"'; }?>   value="2">I have thoughts about killing myself, but I would not carry them out.</option>
				
			<option <?php if(isset($select_result['suicide']) && $select_result['suicide'] == "3"){echo 'selected="selected"'; }?>   value="3">I would like to kill myself.</option>
				
			<option <?php if(isset($select_result['suicide']) && $select_result['suicide'] == "4"){echo 'selected="selected"'; }?>   value="4">I would like to kill myself if I had the chance.</option>	
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2"><p>Cut off 4</p></td>
	</tr>
	<tr>
		<td colspan="2">
			<h3>Social support</h3>
			<p>The questions refer to how you felt about your social relationships the last six months.</p>
		</td>
	</tr>
	<tr>
	<tr>
		<td>When I feel tense or nervous, there is someone to help me</td>
		<td>
			<select class="form-control" name="someone_to_help">
								
			<option <?php if(isset($select_result['someone_to_help']) && $select_result['someone_to_help'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
			<option <?php if(isset($select_result['someone_to_help']) && $select_result['someone_to_help'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
			<option <?php if(isset($select_result['someone_to_help']) && $select_result['someone_to_help'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
			<option <?php if(isset($select_result['someone_to_help']) && $select_result['someone_to_help'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
				
				
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>When I experience some nice things, there is someone with whom to talk about it</td>
		<td>
			<select class="form-control" name="someone_to_talk">
				
				<option <?php if(isset($select_result['someone_to_talk']) && $select_result['someone_to_talk'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['someone_to_talk']) && $select_result['someone_to_talk'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['someone_to_talk']) && $select_result['someone_to_talk'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['someone_to_talk']) && $select_result['someone_to_talk'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>When I am in pain there is someone to comfort me</td>
		<td>
			<select class="form-control" name="comfort_me">
				
				
				
				<option <?php if(isset($select_result['comfort_me']) && $select_result['comfort_me'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['comfort_me']) && $select_result['comfort_me'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['comfort_me']) && $select_result['comfort_me'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['comfort_me']) && $select_result['comfort_me'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>When I am sad there is someone with whom to talk about it</td>
		<td>
			<select class="form-control" name="someone_to_talk_when_sad">
			
			 <option <?php if(isset($select_result['someone_to_talk_when_sad']) && $select_result['someone_to_talk_when_sad'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['someone_to_talk_when_sad']) && $select_result['someone_to_talk_when_sad'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['someone_to_talk_when_sad']) && $select_result['someone_to_talk_when_sad'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['someone_to_talk_when_sad']) && $select_result['someone_to_talk_when_sad'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
			
			</select>
		</td>
	</tr>
	<tr>
		<td>When I need help with a job I cannot carry out alone there is someone to help me</td>
		<td>
			<select class="form-control" name="help_with_job">
				
				
				 <option <?php if(isset($select_result['help_with_job']) && $select_result['help_with_job'] == "1"){echo 'selected="selected"'; }?>   value="1">Nearly never</option>
				<option <?php if(isset($select_result['help_with_job']) && $select_result['help_with_job'] == "2"){echo 'selected="selected"'; }?>   value="2">Sometimes</option>
				
				<option <?php if(isset($select_result['help_with_job']) && $select_result['help_with_job'] == "3"){echo 'selected="selected"'; }?>   value="3">Regularly</option>
				
				<option <?php if(isset($select_result['help_with_job']) && $select_result['help_with_job'] == "4"){echo 'selected="selected"'; }?>   value="4">Often</option>
			
				
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<h3>Cognitions regarding fertility problems</h3>
			<p>The next items are statements from people with fertility problems. We ask you to indicate to what extent you agree with the statements. You can do that by encircling the number next to the statement that most closely matches with what you think about the statement. Do not think too deeply, your first impression is usually best.</p>
		</td>
	</tr>
	<tr>
		<td>Because of my fertility problems I miss things that are most important for me</td>
		<td>
			<select class="form-control" name="fertility_problems">
				
				
				
				 <option <?php if(isset($select_result['fertility_problems']) && $select_result['fertility_problems'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['fertility_problems']) && $select_result['fertility_problems'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['fertility_problems']) && $select_result['fertility_problems'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['fertility_problems']) && $select_result['fertility_problems'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I can deal with the consequences of my fertility problems</td>
		<td>
			<select class="form-control" name="fertility_problems_consequences">
			
				
				
                    <option <?php if(isset($select_result['fertility_problems_consequences']) && $select_result['fertility_problems_consequences'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['fertility_problems_consequences']) && $select_result['fertility_problems_consequences'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['fertility_problems_consequences']) && $select_result['fertility_problems_consequences'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['fertility_problems_consequences']) && $select_result['fertility_problems_consequences'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I have learned to live with my fertility problems</td>
		<td>
			<select class="form-control" name="learned_to_live">
				
				
				
				
				<option <?php if(isset($select_result['learned_to_live']) && $select_result['learned_to_live'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['learned_to_live']) && $select_result['learned_to_live'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['learned_to_live']) && $select_result['learned_to_live'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['learned_to_live']) && $select_result['learned_to_live'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>
				
				
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>My fertility problems control my life</td>
		<td>
			<select class="form-control" name="control_life">
			
			<option <?php if(isset($select_result['control_life']) && $select_result['control_life'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['control_life']) && $select_result['control_life'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['control_life']) && $select_result['control_life'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['control_life']) && $select_result['control_life'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>	
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>My fertility problems sometimes give me the feeling of being useless</td>
		<td>
			<select class="form-control" name="feeling_useless">
							
				
			<option <?php if(isset($select_result['feeling_useless']) && $select_result['feeling_useless'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['feeling_useless']) && $select_result['feeling_useless'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['feeling_useless']) && $select_result['feeling_useless'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['feeling_useless']) && $select_result['feeling_useless'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>	
				
			</select>
		</td>
	</tr>
	<tr>
		<td>My fertility problems make my life incomplete</td>
		<td>
			<select class="form-control" name="incomplete">
			
				
				
				
				<option <?php if(isset($select_result['incomplete']) && $select_result['incomplete'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['incomplete']) && $select_result['incomplete'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['incomplete']) && $select_result['incomplete'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['incomplete']) && $select_result['incomplete'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>
				
				
				
				
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I have learned to accept my fertility problems</td>
		<td>
			<select class="form-control" name="learned_to_accept">
				
				<option <?php if(isset($select_result['learned_to_accept']) && $select_result['learned_to_accept'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['learned_to_accept']) && $select_result['learned_to_accept'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['learned_to_accept']) && $select_result['learned_to_accept'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['learned_to_accept']) && $select_result['learned_to_accept'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>
				
				
			</select>
			
		</td>
	</tr>
	<tr>
		<td>My fertility problems affect everything that is important for me</td>
		<td>
			<select class="form-control" name="affect_important_things">
				
				
					<option <?php if(isset($select_result['affect_important_things']) && $select_result['affect_important_things'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['affect_important_things']) && $select_result['affect_important_things'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['affect_important_things']) && $select_result['affect_important_things'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['affect_important_things']) && $select_result['affect_important_things'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I can accept my fertility problems</td>
		<td>
			<select class="form-control" name="accept_fertility_problems">
			
				
				
				
				
				<option <?php if(isset($select_result['accept_fertility_problems']) && $select_result['accept_fertility_problems'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['accept_fertility_problems']) && $select_result['accept_fertility_problems'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['accept_fertility_problems']) && $select_result['accept_fertility_problems'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['accept_fertility_problems']) && $select_result['accept_fertility_problems'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>			
				
				
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I think I can cope with my fertility problems, even if they are not solved</td>
		<td>
			<select class="form-control" name="cope_with_unsolved_fertility_problems">
				
				
				
				
		<option <?php if(isset($select_result['cope_with_unsolved_fertility_problems']) && $select_result['cope_with_unsolved_fertility_problems'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['cope_with_unsolved_fertility_problems']) && $select_result['cope_with_unsolved_fertility_problems'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['cope_with_unsolved_fertility_problems']) && $select_result['cope_with_unsolved_fertility_problems'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['cope_with_unsolved_fertility_problems']) && $select_result['cope_with_unsolved_fertility_problems'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>			
				
				
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I often feel helpless because of my fertility Problems</td>
		<td>
			<select class="form-control" name="helpless">
			
			<option <?php if(isset($select_result['helpless']) && $select_result['helpless'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['helpless']) && $select_result['helpless'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['helpless']) && $select_result['helpless'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['helpless']) && $select_result['helpless'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>	
			
				
			</select>
		</td>
	</tr>
	<tr>
		<td>I can cope well with my fertility problems</td>
		<td>
			<select class="form-control" name="cope_with_fertility_problems">
			
				<option <?php if(isset($select_result['cope_with_fertility_problems']) && $select_result['cope_with_fertility_problems'] == "1"){echo 'selected="selected"'; }?>   value="1">Do not agree</option>
				<option <?php if(isset($select_result['cope_with_fertility_problems']) && $select_result['cope_with_fertility_problems'] == "2"){echo 'selected="selected"'; }?>   value="2">Agree a little bit</option>
				
				<option <?php if(isset($select_result['cope_with_fertility_problems']) && $select_result['cope_with_fertility_problems'] == "3"){echo 'selected="selected"'; }?>   value="3">Agree</option>
				
				<option <?php if(isset($select_result['cope_with_fertility_problems']) && $select_result['cope_with_fertility_problems'] == "4"){echo 'selected="selected"'; }?>   value="4">Strongly agree</option>
				
			</select>
		</td>
	</tr>
</table>


<!-- /.card-body -->
<div class="card-footer">
	<!-- <input type="" name="" class="btn btn-primary mt-2 mb-2" value="submit"> -->
	<input type="submit" name="submit" class="btn btn-primary mt-2 mb-2" value="submit">
</div>
</form>



			
				
<!-- print -->
<input type="button" id="btn" value="Print" class="btn btn-primary pull-right ptable">

	
<!--<div  class="printtable prtable"  id="printtable"  style="display:none;">-->
<div  class="printtable prtable"  id="printtable" style="display:none;"> 
<table style="width:100%; border:1px solid #cdcdcd;" id="printtable" border="1">			
				
<table style="border:1px solid;width:100%;padding:5px;" class="fg45yu">
   <tr>
   <td style="width:50%;padding:5px;" colspan="10"><img src="<?php echo base_url(); ?>/assets/images/India-IVF-Logo-Option-5.png" style="width:220px"></td>
   <td style="width:50%;padding:5px;" colspan="10"><h3 style="margin-top:20px;">PSYCHOLOGICAL QUESTIONNAIRE</h3></td>
   </tr>
</table>
     				  <table width="100%" class="vb45rt">
<tbody>
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
				
				
				
		<!--  anxiety    -->

<table class="table-bordered" style="border:1px solid #cdcdcd; width:100%;">
	<tr>
		<td colspan="2" style="border:1px solid #cdcdcd;">
		    <?php if(isset($select_result['updated_by']) && !empty($select_result['updated_by']) &&
		            isset($select_result['updated_at']) && !empty($select_result['updated_at']) && 
		            isset($select_result['updated_type']) && !empty($select_result['updated_type'])
		            ){?>
		        <p id="last_updated">Last updated on <?php echo $select_result['updated_at']; ?> by <?php echo last_updated_user($select_result['updated_type'],$select_result['updated_by']); ?></p>
		    <?php } ?>
		</td>
	</tr>
</table>
<table class="table-bordered" width="100%" style="border:1px solid #cdcdcd; width:100%;">
	<tr>
		<td  style="border:1px solid #cdcdcd;">I feel fine</td>
		<td  style="border:1px solid #cdcdcd;">
		
							
				
			<?php if(isset($select_result['feel_fine']) && $select_result['feel_fine'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['feel_fine']) && $select_result['feel_fine'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['feel_fine']) && $select_result['feel_fine'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['feel_fine']) && $select_result['feel_fine'] == "4"){echo 'Often'; }?>   
			
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I feel satisfied</td>
		<td  style="border:1px solid #cdcdcd;">
	
				<?php if(isset($select_result['feel_satisfied']) && $select_result['feel_satisfied'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['feel_satisfied']) && $select_result['feel_satisfied'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['feel_satisfied']) && $select_result['feel_satisfied'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['feel_satisfied']) && $select_result['feel_satisfied'] == "4"){echo 'Often'; }?>   
			
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I worry too much about not really important things</td>
		<td  style="border:1px solid #cdcdcd;">
		<?php if(isset($select_result['not_important']) && $select_result['not_important'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['not_important']) && $select_result['not_important'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['not_important']) && $select_result['not_important'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['not_important']) && $select_result['not_important'] == "4"){echo 'Often'; }?>  
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I am happy</td>
		<td  style="border:1px solid #cdcdcd;">
		<?php if(isset($select_result['happy']) && $select_result['happy'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['happy']) && $select_result['happy'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['happy']) && $select_result['happy'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['happy']) && $select_result['happy'] == "4"){echo 'Often'; }?>  
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I am troubled by disturbing thoughts</td>
		<td  style="border:1px solid #cdcdcd;">
	<?php if(isset($select_result['troubled_dusturbing']) && $select_result['troubled_dusturbing'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['troubled_dusturbing']) && $select_result['troubled_dusturbing'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['troubled_dusturbing']) && $select_result['troubled_dusturbing'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['troubled_dusturbing']) && $select_result['troubled_dusturbing'] == "4"){echo 'Often'; }?> 
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I feel safe</td>
		<td  style="border:1px solid #cdcdcd;">
		
	<?php if(isset($select_result['feel_safe']) && $select_result['feel_safe'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['feel_safe']) && $select_result['feel_safe'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['feel_safe']) && $select_result['feel_safe'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['feel_safe']) && $select_result['feel_safe'] == "4"){echo 'Often'; }?> 
	
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I am pleased</td>
		<td  style="border:1px solid #cdcdcd;">
		<?php if(isset($select_result['pleased']) && $select_result['pleased'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['pleased']) && $select_result['pleased'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['pleased']) && $select_result['pleased'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['pleased']) && $select_result['pleased'] == "4"){echo 'Often'; }?> 
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">There are thoughts that keep haunting me</td>
		<td  style="border:1px solid #cdcdcd;">
		<?php if(isset($select_result['haunting']) && $select_result['haunting'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['haunting']) && $select_result['haunting'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['haunting']) && $select_result['haunting'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['haunting']) && $select_result['haunting'] == "4"){echo 'Often'; }?> 
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I take disappointments so seriously that I cannot get them out of my mind</td>
		<td  style="border:1px solid #cdcdcd;">
		<?php if(isset($select_result['disappointments']) && $select_result['disappointments'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['disappointments']) && $select_result['disappointments'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['disappointments']) && $select_result['disappointments'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['disappointments']) && $select_result['disappointments'] == "4"){echo 'Often'; }?> 
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I get very nervous and worried when thinking about my current troubles</td>
		<td  style="border:1px solid #cdcdcd;">
		 <?php if(isset($select_result['nervous']) && $select_result['nervous'] == "1"){echo 'Nearly never'; }?> 
		 <?php if(isset($select_result['nervous']) && $select_result['nervous'] == "2"){echo 'Sometimes'; }?>   
		 <?php if(isset($select_result['nervous']) && $select_result['nervous'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['nervous']) && $select_result['nervous'] == "4"){echo 'Often'; }?> 
	
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p>* reverse scoring, see instruction. Do not indicate asterisks on patient form</p>
			<p>Cut off 24 and above</p>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<h3>Depression</h3>
			<p>So it is about how you felt during the last week.</p>
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Are you feeling sad?</td>
		<td  style="border:1px solid #cdcdcd;">
	
	<?php if(isset($select_result['feeling_sad']) && $select_result['feeling_sad'] == "1"){echo 'I do not feel sad.'; }?>
	<?php if(isset($select_result['feeling_sad']) && $select_result['feeling_sad'] == "2"){echo 'I feel sad.'; }?>   
	<?php if(isset($select_result['feeling_sad']) && $select_result['feeling_sad'] == "3"){echo 'I am sad all the time and I can’t snap out of it.'; }?> 
	<?php if(isset($select_result['feeling_sad']) && $select_result['feeling_sad'] == "4"){echo 'I am so sad and unhappy that I can’t stand it.'; }?>
	
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Are you feeling discouraged?</td>
		<td  style="border:1px solid #cdcdcd;">
			
<?php if(isset($select_result['discouraged']) && $select_result['discouraged'] == "1"){echo 'I am not particularly discouraged about the future.'; }?> 
<?php if(isset($select_result['discouraged']) && $select_result['discouraged'] == "2"){echo 'I feel discouraged about the future.'; }?> 
<?php if(isset($select_result['discouraged']) && $select_result['discouraged'] == "3"){echo 'I feel I have nothing to look forward to.'; }?>  
<?php if(isset($select_result['discouraged']) && $select_result['discouraged'] == "4"){echo 'I feel the future is hopeless and that things cannot improve.'; }?> 

		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Are do you feel about failure?</td>
		<td  style="border:1px solid #cdcdcd;">
		
			
				
<?php if(isset($select_result['failure']) && $select_result['failure'] == "1"){echo 'I do not feel like a failure.'; }?>
<?php if(isset($select_result['failure']) && $select_result['failure'] == "2"){echo 'I feel I have failed more than the average person.'; }?>  
<?php if(isset($select_result['failure']) && $select_result['failure'] == "3"){echo 'As I look back on my life, all I can see is a lot of failures.'; }?> 
<?php if(isset($select_result['failure']) && $select_result['failure'] == "4"){echo 'I feel I am a complete failure as a person.'; }?> 
				
	
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">How much satisfied are you?</td>
		<td  style="border:1px solid #cdcdcd;">
			
 <?php if(isset($select_result['satisfied']) && $select_result['satisfied'] == "1"){echo 'I get as much satisfaction out of things as I used to.'; }?> 
<?php if(isset($select_result['satisfied']) && $select_result['satisfied'] == "2"){echo 'I don’t enjoy things the way I used to.'; }?>  
<?php if(isset($select_result['satisfied']) && $select_result['satisfied'] == "3"){echo 'I don’t get real satisfaction out of anything anymore.'; }?> 
<?php if(isset($select_result['satisfied']) && $select_result['satisfied'] == "4"){echo 'I am dissatisfied or bored with everything.'; }?> 
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Do you feel disappointed?</td>
		<td  style="border:1px solid #cdcdcd;">
				
<?php if(isset($select_result['disappointed']) && $select_result['disappointed'] == "1"){echo 'I don’t feel disappointed in myself.'; }?> 
<?php if(isset($select_result['disappointed']) && $select_result['disappointed'] == "2"){echo 'I am disappointed in myself.'; }?> 
<?php if(isset($select_result['disappointed']) && $select_result['disappointed'] == "3"){echo 'I am disgusted with myself.'; }?> 
<?php if(isset($select_result['disappointed']) && $select_result['disappointed'] == "4"){echo 'I hate myself.'; }?>   
				

		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">How do you feel about yourself in comparison with others?</td>
		<td  style="border:1px solid #cdcdcd;">
	
				
<?php if(isset($select_result['comparison']) && $select_result['comparison'] == "1"){echo 'I don’t feel I am any worse than anybody else.'; }?>  
<?php if(isset($select_result['comparison']) && $select_result['comparison'] == "2"){echo 'I am critical of myself for my weaknesses or mistakes.'; }?> </option>
<?php if(isset($select_result['comparison']) && $select_result['comparison'] == "3"){echo 'I blame myself all the time for my faults.'; }?>   
<?php if(isset($select_result['comparison']) && $select_result['comparison'] == "4"){echo 'I blame myself for everything bad that happens.'; }?> 
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Do think of committing suicide?</td>
		<td  style="border:1px solid #cdcdcd;">
		
				
<?php if(isset($select_result['suicide']) && $select_result['suicide'] == "1"){echo 'I don’t have any thoughts about killing myself.'; }?> 
 <?php if(isset($select_result['suicide']) && $select_result['suicide'] == "2"){echo 'I have thoughts about killing myself, but I would not carry them out.'; }?>
 <?php if(isset($select_result['suicide']) && $select_result['suicide'] == "3"){echo 'I would like to kill myself.'; }?>
<?php if(isset($select_result['suicide']) && $select_result['suicide'] == "4"){echo 'I would like to kill myself if I had the chance.'; }?>  	
		
		</td>
	</tr>
	<tr>
		<td colspan="2"><p>Cut off 4</p></td>
	</tr>
	<tr>
		<td colspan="2">
			<h3>Social support</h3>
			<p>The questions refer to how you felt about your social relationships the last six months.</p>
		</td>
	</tr>
	<tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">When I feel tense or nervous, there is someone to help me</td>
		<td  style="border:1px solid #cdcdcd;">
		
								
			<?php if(isset($select_result['someone_to_help']) && $select_result['someone_to_help'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['someone_to_help']) && $select_result['someone_to_help'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['someone_to_help']) && $select_result['someone_to_help'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['someone_to_help']) && $select_result['someone_to_help'] == "4"){echo 'Often'; }?>
				
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">When I experience some nice things, there is someone with whom to talk about it</td>
		<td  style="border:1px solid #cdcdcd;">
		
				
				<?php if(isset($select_result['someone_to_talk']) && $select_result['someone_to_talk'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['someone_to_talk']) && $select_result['someone_to_talk'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['someone_to_talk']) && $select_result['someone_to_talk'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['someone_to_talk']) && $select_result['someone_to_talk'] == "4"){echo 'Often'; }?>
	
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">When I am in pain there is someone to comfort me</td>
		<td  style="border:1px solid #cdcdcd;">
		
		
				<?php if(isset($select_result['comfort_me']) && $select_result['comfort_me'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['comfort_me']) && $select_result['comfort_me'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['comfort_me']) && $select_result['comfort_me'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['comfort_me']) && $select_result['comfort_me'] == "4"){echo 'Often'; }?> 
				
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">When I am sad there is someone with whom to talk about it</td>
		<td  style="border:1px solid #cdcdcd;">
			
			
			 <?php if(isset($select_result['someone_to_talk_when_sad']) && $select_result['someone_to_talk_when_sad'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['someone_to_talk_when_sad']) && $select_result['someone_to_talk_when_sad'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['someone_to_talk_when_sad']) && $select_result['someone_to_talk_when_sad'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['someone_to_talk_when_sad']) && $select_result['someone_to_talk_when_sad'] == "4"){echo 'Often'; }?> 
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">When I need help with a job I cannot carry out alone there is someone to help me</td>
		<td  style="border:1px solid #cdcdcd;">
		
				
			
			
			 <?php if(isset($select_result['help_with_job']) && $select_result['help_with_job'] == "1"){echo 'Nearly never'; }?> 
			<?php if(isset($select_result['help_with_job']) && $select_result['help_with_job'] == "2"){echo 'Sometimes'; }?>   
		<?php if(isset($select_result['help_with_job']) && $select_result['help_with_job'] == "3"){echo 'Regularly'; }?>   
		 <?php if(isset($select_result['help_with_job']) && $select_result['help_with_job'] == "4"){echo 'Often'; }?> 
			
		
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<h3>Cognitions regarding fertility problems</h3>
			<p>The next items are statements from people with fertility problems. We ask you to indicate to what extent you agree with the statements. You can do that by encircling the number next to the statement that most closely matches with what you think about the statement. Do not think too deeply, your first impression is usually best.</p>
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">Because of my fertility problems I miss things that are most important for me</td>
		<td  style="border:1px solid #cdcdcd;">
		
				
				
			<?php if(isset($select_result['fertility_problems']) && $select_result['fertility_problems'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['fertility_problems']) && $select_result['fertility_problems'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['fertility_problems']) && $select_result['fertility_problems'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['fertility_problems']) && $select_result['fertility_problems'] == "4"){echo 'Strongly agree'; }?>
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I can deal with the consequences of my fertility problems</td>
		<td  style="border:1px solid #cdcdcd;">
		
	 <?php if(isset($select_result['fertility_problems_consequences']) && $select_result['fertility_problems_consequences'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['fertility_problems_consequences']) && $select_result['fertility_problems_consequences'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['fertility_problems_consequences']) && $select_result['fertility_problems_consequences'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['fertility_problems_consequences']) && $select_result['fertility_problems_consequences'] == "4"){echo 'Strongly agree'; }?> 
				
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I have learned to live with my fertility problems</td>
		<td  style="border:1px solid #cdcdcd;">
		
		<?php if(isset($select_result['learned_to_live']) && $select_result['learned_to_live'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['learned_to_live']) && $select_result['learned_to_live'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['learned_to_live']) && $select_result['learned_to_live'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['learned_to_live']) && $select_result['learned_to_live'] == "4"){echo 'Strongly agree'; }?> 
				
		
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">My fertility problems control my life</td>
		<td  style="border:1px solid #cdcdcd;">
			
			
		<?php if(isset($select_result['control_life']) && $select_result['control_life'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['control_life']) && $select_result['control_life'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['control_life']) && $select_result['control_life'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['control_life']) && $select_result['control_life'] == "4"){echo 'Strongly agree'; }?> 	
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">My fertility problems sometimes give me the feeling of being useless</td>
		<td  style="border:1px solid #cdcdcd;">
			
			 <?php if(isset($select_result['feeling_useless']) && $select_result['feeling_useless'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['feeling_useless']) && $select_result['feeling_useless'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['feeling_useless']) && $select_result['feeling_useless'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['feeling_useless']) && $select_result['feeling_useless'] == "4"){echo 'Strongly agree'; }?> 
				
			
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">My fertility problems make my life incomplete</td>
		<td  style="border:1px solid #cdcdcd;">
			
			
				
				
				<?php if(isset($select_result['incomplete']) && $select_result['incomplete'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['incomplete']) && $select_result['incomplete'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['incomplete']) && $select_result['incomplete'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['incomplete']) && $select_result['incomplete'] == "4"){echo 'Strongly agree'; }?> 
				
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I have learned to accept my fertility problems</td>
		<td  style="border:1px solid #cdcdcd;">
			
				
				 <?php if(isset($select_result['learned_to_accept']) && $select_result['learned_to_accept'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['learned_to_accept']) && $select_result['learned_to_accept'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['learned_to_accept']) && $select_result['learned_to_accept'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['learned_to_accept']) && $select_result['learned_to_accept'] == "4"){echo 'Strongly agree'; }?> 
				
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">My fertility problems affect everything that is important for me</td>
		<td  style="border:1px solid #cdcdcd;">
	
				
				
				 <?php if(isset($select_result['affect_important_things']) && $select_result['affect_important_things'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['affect_important_things']) && $select_result['affect_important_things'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['affect_important_things']) && $select_result['affect_important_things'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['affect_important_things']) && $select_result['affect_important_things'] == "4"){echo 'Strongly agree'; }?> 
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I can accept my fertility problems</td>
		<td  style="border:1px solid #cdcdcd;">
			
			
				
			<?php if(isset($select_result['accept_fertility_problems']) && $select_result['accept_fertility_problems'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['accept_fertility_problems']) && $select_result['accept_fertility_problems'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['accept_fertility_problems']) && $select_result['accept_fertility_problems'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['accept_fertility_problems']) && $select_result['accept_fertility_problems'] == "4"){echo 'Strongly agree'; }?> 			
				
				
				
				
				
				
			</select>
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I think I can cope with my fertility problems, even if they are not solved</td>
		<td  style="border:1px solid #cdcdcd;">
		
				
				
			 <?php if(isset($select_result['cope_with_unsolved_fertility_problems']) && $select_result['cope_with_unsolved_fertility_problems'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['cope_with_unsolved_fertility_problems']) && $select_result['cope_with_unsolved_fertility_problems'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['cope_with_unsolved_fertility_problems']) && $select_result['cope_with_unsolved_fertility_problems'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['cope_with_unsolved_fertility_problems']) && $select_result['cope_with_unsolved_fertility_problems'] == "4"){echo 'Strongly agree'; }?> 			
				
				
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I often feel helpless because of my fertility Problems</td>
		<td  style="border:1px solid #cdcdcd;">
		
			
		 <?php if(isset($select_result['helpless']) && $select_result['helpless'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['helpless']) && $select_result['helpless'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['helpless']) && $select_result['helpless'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['helpless']) && $select_result['helpless'] == "4"){echo 'Strongly agree'; }?> 	
			
		
		</td>
	</tr>
	<tr>
		<td  style="border:1px solid #cdcdcd;">I can cope well with my fertility problems</td>
		<td  style="border:1px solid #cdcdcd;">
			
			
			 <?php if(isset($select_result['cope_with_fertility_problems']) && $select_result['cope_with_fertility_problems'] == "1"){echo 'Do not agree'; }?>
		<?php if(isset($select_result['cope_with_fertility_problems']) && $select_result['cope_with_fertility_problems'] == "2"){echo 'Agree a little bit'; }?>   
				
		 <?php if(isset($select_result['cope_with_fertility_problems']) && $select_result['cope_with_fertility_problems'] == "3"){echo 'Agree'; }?>  
		<?php if(isset($select_result['cope_with_fertility_problems']) && $select_result['cope_with_fertility_problems'] == "4"){echo 'Strongly agree'; }?>   
				
		
		</td>
	</tr>
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