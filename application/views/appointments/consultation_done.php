<?php 
if (isset($select_result2) && is_array($select_result2) && isset($select_result2['center_id'])) {
    $center_number = $select_result2['center_id'];
} else {
    $center_number = '';
}

$all_method =&get_instance();
$consultation_data = $all_method->get_consultation($appointments['ID']);
$patient_data = get_patient_detail($consultation_data['patient_id']);
//var_dump($patient_data);die;
$consultation_data['patient_id'] = get_patient_by_number($patient_data['wife_phone']);
$patient_medical_info = patient_medical_info_data($appointments['ID'], $consultation_data['patient_id']);
$patient_doctor_consultation = patient_doctor_consultation_data($appointments['ID'], $consultation_data['patient_id']);
//var_dump($consultation_data); echo '<br/><br/><br/>'; 
//var_dump($patient_medical_info);die;
?>
<?php
// Set the countdown duration in seconds (e.g., 5 minutes)
$countdownDuration = 7200; // 5 minutes
?>


<style>
	/* Bootstrap 3 Enhanced Styles */
	.panel-primary {
		border-color: #337ab7;
	}
	.panel-primary > .panel-heading {
		background-color: #337ab7;
		border-color: #337ab7;
		color: #fff;
	}
	.form-group {
		margin-bottom: 15px;
	}
	.form-control {
		border-radius: 4px;
		border: 1px solid #ccc;
		padding: 6px 12px;
		font-size: 14px;
		line-height: 1.42857143;
	}
	.form-control:focus {
		border-color: #66afe9;
		outline: 0;
		box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
	}
	.table > thead > tr > th {
		vertical-align: top;
		background-color: #f5f5f5;
		border-bottom: 2px solid #ddd;
		font-weight: bold;
	}
	.table > tbody > tr > td {
		border-top: 1px solid #ddd;
		padding: 8px;
		vertical-align: middle;
	}
	.table-bordered {
		border: 1px solid #ddd;
	}
	.table-bordered > thead > tr > th,
	.table-bordered > tbody > tr > td {
		border: 1px solid #ddd;
	}
	.btn-primary {
		background-color: #337ab7;
		border-color: #2e6da4;
		color: #fff;
	}
	.btn-primary:hover {
		background-color: #286090;
		border-color: #204d74;
	}
	.btn-success {
		background-color: #5cb85c;
		border-color: #4cae4c;
		color: #fff;
	}
	.btn-success:hover {
		background-color: #449d44;
		border-color: #398439;
	}
	.medicine-section {
		background-color: #f9f9f9;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		padding: 15px;
		margin-bottom: 20px;
	}
	.medicine-section h4 {
		color: #337ab7;
		margin-top: 0;
		margin-bottom: 15px;
		border-bottom: 2px solid #337ab7;
		padding-bottom: 5px;
	}
	.medicine-table {
		background-color: #fff;
		border-radius: 4px;
		overflow: hidden;
	}
	.medicine-table th {
		background-color: #337ab7;
		color: #fff;
		border: none;
		padding: 12px 8px;
		font-size: 12px;
		text-align: center;
	}
	.medicine-table td {
		border: 1px solid #ddd;
		padding: 8px;
		vertical-align: middle;
	}
	.medicine-table input,
	.medicine-table select {
		border: 1px solid #ccc;
		border-radius: 3px;
		padding: 4px 6px;
		font-size: 12px;
		width: 100%;
	}
	.multiselect-container>li>a>label {
		padding: 4px 20px 3px 20px;
	}
	.open > .dropdown-menu {
		width: 350px;
		max-height: 300px;
		overflow: auto;
	}
	label.checkbox {
		color: #000;
	}
	.btn-group{
		max-width: 100%;
	}
	button.multiselect.dropdown-toggle.btn.btn-default {
		width: 100%;
		overflow:hidden;
	}
	select#center {
    display: block!important;
    }
	td {
		width: auto;
	}
	.border-field input.form-control {
		border: 1px solid;
		padding: 0;
	}
	[type="radio"]:checked+label:before, [type="radio"]:checked+label:after, [type="radio"]:not(:checked)+label:before, [type="radio"]:not(:checked)+label:after{display:none!important;}
	[type="radio"]:not(:checked), [type="radio"]:checked { position: relative!important;  left: 0!important; opacity: 1!important; }
	[type="radio"]:not(:checked)+label, [type="radio"]:checked+label{padding-left:0px!important; padding-right:5px!important;}
	table#SOCIAl_DRUG_INTAKE_HISTORY td, #table_dentures td {
		width: 55%;
	}
	input[type="checkbox"] {
		position: relative!important;
		left: 2px!important;
		opacity: 1!important;
    }
	#example1{background:#fff;}
</style>



<form class="form-horizontal" method="post" id="consultation_done_form" onsubmit="return submitForm(this);" action="" enctype="multipart/form-data" >

<input type="hidden" name="action" value="add_consultation_done" />

<input type="hidden" id="submit_type" name="submit_type" value="" />

<input type="hidden" name="appointment_id" value="<?php echo $appointments['ID']; ?>" />

<input type="hidden" name="disapproval_reason" value="<?php echo isset($patient_doctor_consultation['disapproval_reason'])?$patient_doctor_consultation['disapproval_reason']:""; ?>" />

<input type="hidden" name="patient_id" value="<?php echo $patient_data['patient_id']; ?>" />

<input type="hidden" name="wife_phone" value="<?php echo $patient_data['wife_phone']; ?>" />

<input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $_SESSION['logged_doctor']['doctor_id']?>" />

<input type="hidden" name="doctor" id="doctor" value="<?php echo $_SESSION['logged_doctor']['doctor_id']?>" />

<input type="hidden" name="center_number" id="center_number" value="<?php echo htmlspecialchars($center_number); ?>" />

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			<!-- Patient Information Panel -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-user"></i> Patient Consultation Form</h3>
				</div>
				<div class="panel-body">
						<table id="example1" class="table table-bordered table-striped">
							<thead>
							<tr>
								<th></th>
								<th style="color: #d9534f; font-weight: bold;">Patient</th>
								<th style="color: #d9534f; font-weight: bold;">Spouse</th>
								</tr>
							</thead>
						<tbody>
							<tr>
								<th style="color: #d9534f; font-weight: bold;">NAME</th>
								<td>
									<div class="form-group">
										<input type="text" class="form-control" name="female_name" readonly value="<?php echo $patient_data['wife_name']?>" id="exampleInputEmail1" placeholder="Enter name">
									</div>
									</td>
									<td>
									<div class="form-group">
									<input type="text" class="form-control" name="male_name" value="<?php echo !empty($patient_medical_info['male_name'])?$patient_medical_info['male_name']:"";?>" id="exampleInputEmail1" placeholder="Enter name" required>
									</div>
									</td>
								</tr>
								<tr>
								<th style="color: #d9534f; font-weight: bold;">AGE (YEARS)</th>
									<td>
									<div class="form-group">
                       <input type="number" class="form-control" value="<?php echo !empty($patient_medical_info['female_age'])?$patient_medical_info['female_age']:""; ?>" name="female_age" id="exampleInputEmail1" placeholder="Enter your age" required>
									</div>
                    </td>
                    <td>
									<div class="form-group">
                      <input type="number" class="form-control" value="<?php echo !empty($patient_medical_info['male_age'])?$patient_medical_info['male_age']:""; ?>" name="male_age" id="exampleInputEmail1" placeholder="Enter your age" required>
									</div>
                    </td>
                    </tr>

                    <tr>

						<th style="color: red;">Blood Group</th>

						<td>

						<input type="text" class="form-control" name="female_blood_grp" value="<?php echo !empty($patient_medical_info['female_blood_grp'])?$patient_medical_info['female_blood_grp']:"";?>"  id="exampleInputEmail1" placeholder="Enter Blood Group" required>

						</td>

						<td>

						<input type="text" class="form-control" name="male_blood_grp" value="<?php echo !empty($patient_medical_info['male_blood_grp'])?$patient_medical_info['male_blood_grp']:"";?>" id="exampleInputEmail1" placeholder="Enter Blood Group" required>

						</td>

					</tr>

                    <tr>

                    <th style="color: red;">OCCUPATION</th>

                    <?php
					$selected_occupation = !empty($patient_medical_info['female_occupation']) ? $patient_medical_info['female_occupation'] : '';
					$options = ['Private', 'Government', 'Business', 'Student', 'Others', 'Housewife'];
					?>

					<td>
						<select class="form-control" name="female_occupation" id="female_occupation" required>
							<option value="">-- Select Occupation --</option>
							<?php foreach ($options as $option): ?>
								<option value="<?php echo $option; ?>" <?php echo ($selected_occupation === $option) ? 'selected' : ''; ?>>
									<?php echo $option; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>

                   <?php
					$selected_occupation = !empty($patient_medical_info['male_occupation']) ? $patient_medical_info['male_occupation'] : '';
					$options = ['Private', 'Government', 'Business', 'Student', 'Others'];
					?>

					<td>
						<select class="form-control" name="male_occupation" id="male_occupation" required>
							<option value="">-- Select Occupation --</option>
							<?php foreach ($options as $option): ?>
								<option value="<?php echo $option; ?>" <?php echo ($selected_occupation === $option) ? 'selected' : ''; ?>>
									<?php echo $option; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>                 

				 </tr>
				 
				  <tr>

                    <th style="color: red;">EDUCATION</th>

                    <?php
					$selected_education = !empty($patient_medical_info['female_education']) ? $patient_medical_info['female_education'] : '';
					$options = ['High School', 'Intermediate', 'Diploma', 'Graduate', 'Post Graduate', 'Others'];
					?>

					<td>
						<select class="form-control" name="female_education" id="female_education" required>
							<option value="">-- Select Education --</option>
							<?php foreach ($options as $option): ?>
								<option value="<?php echo $option; ?>" <?php echo ($selected_education === $option) ? 'selected' : ''; ?>>
									<?php echo $option; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>

                   <?php
					$selected_education = !empty($patient_medical_info['male_education']) ? $patient_medical_info['male_education'] : '';
					$options = ['High School', 'Intermediate', 'Diploma', 'Graduate', 'Post Graduate', 'Others'];
					?>

					<td>
						<select class="form-control" name="male_education" id="male_education" required>
							<option value="">-- Select Education --</option>
							<?php foreach ($options as $option): ?>
								<option value="<?php echo $option; ?>" <?php echo ($selected_education === $option) ? 'selected' : ''; ?>>
									<?php echo $option; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>                 

				 </tr>

                  <tr>

                    <th style="color: red;">NATIONALITY</th>

                    <td><input type="text" class="form-control" value="<?php echo !empty($patient_medical_info['female_nationality'])?$patient_medical_info['female_nationality']:""; ?>" name="female_nationality" id="exampleInputEmail1" placeholder="Enter your Nationality" required></td>

                    <td><input type="text" class="form-control" value="<?php echo !empty($patient_medical_info['male_nationality'])?$patient_medical_info['male_nationality']:""; ?>" name="male_nationality" id="exampleInputEmail1" placeholder="Enter your Nationality" required></td>

                  </tr>

                  <tr>

                    <th style="color: red;">ETHNICITY</th>

                    <td><input type="text" class="form-control" value="<?php echo !empty($patient_medical_info['female_ethnicity'])?$patient_medical_info['female_ethnicity']:""; ?>" name="female_ethnicity" id="exampleInputEmail1" placeholder="Enter your Ethnicity" required></td>

                    <td><input type="text" class="form-control" value="<?php echo !empty($patient_medical_info['male_ethnicity'])?$patient_medical_info['male_ethnicity']:""; ?>" name="male_ethnicity" id="exampleInputEmail1" placeholder="Enter your Ethnicity" required></td>

                  </tr>

                  <tr>

                    <th style="color: red;">WT/HT/BMI</th>

                   <td>
				   <input type="text" value="<?php echo !empty($patient_medical_info['female_wt_ht_bmi'])?$patient_medical_info['female_wt_ht_bmi']:""; ?>" class="form-control" name="female_wt_ht_bmi" id="exampleInputEmail1" required placeholder="Patient Weight">
				   <input type="text" value="<?php echo !empty($patient_medical_info['female_ht'])?$patient_medical_info['female_ht']:""; ?>" class="form-control" name="female_ht" id="exampleInputEmail1" required placeholder="Patient hieght">
				   <input type="text" value="<?php echo !empty($patient_medical_info['female_bmi'])?$patient_medical_info['female_bmi']:""; ?>" class="form-control" name="female_bmi" id="exampleInputEmail1" required placeholder="Patient BMI">
				   </td>

                    <td>
					<input type="text" value="<?php echo !empty($patient_medical_info['male_wt_ht_bmi'])?$patient_medical_info['male_wt_ht_bmi']:""; ?>" class="form-control" name="male_wt_ht_bmi" id="exampleInputEmail1" required placeholder="Spouse Weight">
					<input type="text" value="<?php echo !empty($patient_medical_info['male_ht'])?$patient_medical_info['male_ht']:""; ?>" class="form-control" name="male_ht" id="exampleInputEmail1" required placeholder="Spouse hieght">
					<input type="text" value="<?php echo !empty($patient_medical_info['male_bmi'])?$patient_medical_info['male_bmi']:""; ?>" class="form-control" name="male_bmi" id="exampleInputEmail1" required placeholder="Spouse BMI">
					</td>

                  </tr>
				   <tr>

                    <th style="color: red;">Center</th>

                   <td>
				  
				  <select name="center" required class="empty-field" id="center">

										<option value="">Select</option>

										<?php $center = $all_method->get_center_list(); foreach($center as $key => $center){?>

										<option value="<?php echo $center['center_name']; ?>"><?php echo $center['center_name']; ?></option>

										<?php } ?>

									</select>
				  
				   </td>

                    <td>
					</td>

                  </tr>

                  <tr>

                    <th style="color: red;">

						H/O PREVIOUS PREGNANCIES<br>

						(IN PREVIOUS RELATIONSHIPS ,MARRIAGES ALSO )

					</th>

					<td>

						<!-- <h1 style="margin-top:50px;">Tick the right option</h1> -->

						<table width="100%">

							<tr>

								<td colspan="2"><p style="color: red;">Total pregnancies</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['female_total_pregnancies'])?$patient_medical_info['female_total_pregnancies']:""; ?>" name="female_total_pregnancies" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">No.of live births</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['female_live_birth'])?$patient_medical_info['female_live_birth']:""; ?>" name="female_live_birth" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">No.of spontaneous abortions in first trimester</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['female_spontaneous_abortions'])?$patient_medical_info['female_spontaneous_abortions']:"";  ?>" name="female_spontaneous_abortions" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">No.of termination of pregnancy</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['female_termination_pregnancy'])?$patient_medical_info['female_termination_pregnancy']:""; ?>" name="female_termination_pregnancy" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">No.of still births</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['female_still_births'])?$patient_medical_info['female_still_births']:""; ?>" name="female_still_births" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">No. of ectopic pregnancy</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['female_ectopic_pregnancy'])?$patient_medical_info['female_ectopic_pregnancy']:""; ?>" name="female_ectopic_pregnancy" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">History of any abnormality in child</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['female_abnormality_child'])?$patient_medical_info['female_abnormality_child']:""; ?>" name="female_abnormality_child" class="form-control" required></td>

							</tr>
                            <tr>
								<td colspan="1"><p style="color: red;">P. L. A.</p></td>
								<td colspan="1">
								<input type="number" value="<?php echo isset($patient_medical_info['female_pregnancy_other_p'])?$patient_medical_info['female_pregnancy_other_p']:""; ?>" name="female_pregnancy_other_p" class="form-control" required placeholder="P" style="width:90%;">
								</td>
								<td colspan="1">
								<input type="number" value="<?php echo isset($patient_medical_info['female_pregnancy_other_l'])?$patient_medical_info['female_pregnancy_other_l']:""; ?>" name="female_pregnancy_other_l" class="form-control" required placeholder="L" style="width:90%;">
								</td>
								<td colspan="1">
								<input type="number" value="<?php echo isset($patient_medical_info['female_pregnancy_other_a'])?$patient_medical_info['female_pregnancy_other_a']:""; ?>" name="female_pregnancy_other_a" class="form-control" required placeholder="A" style="width:90%;">
								</td>
							</tr>
							<tr>

								<td colspan="2"><p style="color: red;">Others</p></td>

								<td colspan="2">
								<input type="text" value="<?php echo isset($patient_medical_info['female_pregnancy_other'])?$patient_medical_info['female_pregnancy_other']:""; ?>" name="female_pregnancy_other" class="form-control" required>
								</td>

							</tr>

						</table>

					</td>

					<td>

						<!-- <h1 style="margin-top:50px;">Tick the right option</h1> -->

						<table width="100%">

							<tr>

								<td colspan="2"><p style="color: red;">Total pregnancies</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['male_total_pregnancies'])?$patient_medical_info['male_total_pregnancies']:"";  ?>" name="male_total_pregnancies" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">No.of live births</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['male_live_birth'])?$patient_medical_info['male_live_birth']:"";  ?>" name="male_live_birth" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">No.of spontaneous abortions in first trimester</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['male_spontaneous_abortions'])?$patient_medical_info['male_spontaneous_abortions']:"";  ?>" name="male_spontaneous_abortions" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">No.of termination of pregnancy</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['male_termination_pregnancy'])?$patient_medical_info['male_termination_pregnancy']:"";  ?>" name="male_termination_pregnancy" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">No.of still births</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['male_still_births'])?$patient_medical_info['male_still_births']:"";  ?>" name="male_still_births" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">No. of ectopic pregnancy</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['male_ectopic_pregnancy'])?$patient_medical_info['male_ectopic_pregnancy']:"";  ?>" name="male_ectopic_pregnancy" class="form-control" required></td>

							</tr>

							<tr>

								<td colspan="2"><p style="color: red;">History of any abnormality in child</p></td>

								<td colspan="2"><input type="number" value="<?php echo isset($patient_medical_info['male_abnormality_child'])?$patient_medical_info['male_abnormality_child']:"";  ?>" name="male_abnormality_child" class="form-control" required></td>

							</tr>

                            <tr>
								<td colspan="1"><p style="color: red;"> P. L. A</p></td>
								<td colspan="1">
								<input type="number" value="<?php echo isset($patient_medical_info['male_pregnancy_other_p'])?$patient_medical_info['male_pregnancy_other_p']:"";  ?>" name="male_pregnancy_other_p" class="form-control" required Placeholder="P" style="width:90%;">
								</td>
								<td colspan="1">
								<input type="number" value="<?php echo isset($patient_medical_info['male_pregnancy_other_l'])?$patient_medical_info['male_pregnancy_other_l']:"";  ?>" name="male_pregnancy_other_l" class="form-control" required Placeholder="L" style="width:90%;">
								</td>
								<td colspan="1">
								<input type="number" value="<?php echo isset($patient_medical_info['male_pregnancy_other_a'])?$patient_medical_info['male_pregnancy_other_a']:"";  ?>" name="male_pregnancy_other_a" class="form-control" required Placeholder="A" style="width:90%;">
								</td>
							</tr>

                            <tr>
								<td colspan="2"><p style="color: red;">Others</p></td>
								<td colspan="2">
								<input type="text" value="<?php echo isset($patient_medical_info['male_pregnancy_other'])?$patient_medical_info['male_pregnancy_other']:"";  ?>" name="male_pregnancy_other" class="form-control" required>
								</td>
							</tr>

							

						</table>

					</tr>

					<tr>

                    <th style="color: red;">SEXUAL HISTORY</th>

                    <td>

						<table>
						    
						    <tr>

								<td>Marital life</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['female_marital_life'])?$patient_medical_info['female_marital_life']:""; ?>" name="female_marital_life" class="form-control" required></td>

							</tr>

							<tr>

								<td>Active marital life</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['female_active_marital'])?$patient_medical_info['female_active_marital']:""; ?>" name="female_active_marital" class="form-control" required></td>

							</tr>

							<tr>

								<td>No.of sexual partners</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['female_sexual_partners'])?$patient_medical_info['female_sexual_partners']:""; ?>" name="female_sexual_partners" class="form-control" required></td>

							</tr>

							<tr>

								<td>Duration of sexual partners</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['female_duration_sexual'])?$patient_medical_info['female_duration_sexual']:"";  ?>" name="female_duration_sexual" class="form-control" required></td>

							</tr>

							<tr>

								<td>Frequency of sexual intercourse</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['female_frequency_sexual'])?$patient_medical_info['female_frequency_sexual']:"";  ?>" name="female_frequency_sexual" class="form-control" required></td>

							</tr>

							<tr>

								<td>Contraception used</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['female_contraception'])?$patient_medical_info['female_contraception']:"";  ?>" name="female_contraception" class="form-control" required></td>

							</tr>
							
							<tr>

								<td>Erection disorder</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['female_erection_disorder'])?$patient_medical_info['female_erection_disorder']:""; ?>" name="female_erection_disorder" class="form-control" required></td>

							</tr>

							<tr>

								<td>Ejaculation disorder</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['female_ejaculation_disorder'])?$patient_medical_info['female_ejaculation_disorder']:"";  ?>" name="female_ejaculation_disorder" class="form-control" required></td>

							</tr>

							<tr>

								<td>Others</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['female_sexual_other'])?$patient_medical_info['female_sexual_other']:""; ?>" name="female_sexual_other" class="form-control" required></td>

							</tr>

						</table>

					</td>

					<td>
						<table>
						    
						    <tr>

								<td>Marital life</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['male_marital_life'])?$patient_medical_info['male_marital_life']:""; ?>" name="male_marital_life" class="form-control" required></td>

							</tr>


							<tr>

								<td>Active marital life</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['male_active_marital'])?$patient_medical_info['male_active_marital']:""; ?>" name="male_active_marital" class="form-control" required></td>

							</tr>

							<tr>

								<td>No.of sexual partners</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['male_sexual_partners'])?$patient_medical_info['male_sexual_partners']:""; ?>" name="male_sexual_partners" class="form-control" required></td>

							</tr>

							<tr>

								<td>Duration of sexual partners</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['male_duration_sexual'])?$patient_medical_info['male_duration_sexual']:""; ?>" name="male_duration_sexual" class="form-control" required></td>

							</tr>

							<tr>

								<td>Frequency of sexual intercourse</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['male_frequency_sexual'])?$patient_medical_info['male_frequency_sexual']:""; ?>" name="male_frequency_sexual" class="form-control" required></td>

							</tr>

							<tr>

								<td>Contraception used</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['male_contraception'])?$patient_medical_info['male_contraception']:""; ?>" name="male_contraception" class="form-control" required></td>

							</tr>

							<tr>

								<td>Erection disorder</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['male_erection_disorder'])?$patient_medical_info['male_erection_disorder']:""; ?>" name="male_erection_disorder" class="form-control" required></td>

							</tr>

							<tr>

								<td>Ejaculation disorder</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['male_ejaculation_disorder'])?$patient_medical_info['male_ejaculation_disorder']:"";  ?>" name="male_ejaculation_disorder" class="form-control" required></td>

							</tr>

							<tr>

								<td>Others</td>

								<td><input type="text" value="<?php echo !empty($patient_medical_info['male_sexual_other'])?$patient_medical_info['male_sexual_other']:""; ?>" name="male_sexual_other" class="form-control" required></td>

							</tr>

						</table>

					</td>

					</tr>

					<tr>

						<th style="color: red;">TYPE OF INFERTILITY</th>

						<td>

							<input type="radio" <?php if(isset($patient_medical_info['female_intertiliy_type']) && $patient_medical_info['female_intertiliy_type'] == "Primary"){echo 'checked="checked"';}?> name="female_intertiliy_type" value="Primary">

							<label for="age1">Primary</label>

							<input type="radio" <?php if(isset($patient_medical_info['female_intertiliy_type']) && $patient_medical_info['female_intertiliy_type'] == "Secondary"){echo 'checked="checked"';}?> name="female_intertiliy_type" value="Secondary">

							<label for="age2">Secondary</label>

						</td>

						<td>

							<input type="radio" <?php if(isset($patient_medical_info['male_intertiliy_type']) && $patient_medical_info['male_intertiliy_type'] == "Primary"){echo 'checked="checked"';}?> name="male_intertiliy_type" value="Primary">

							<label for="age1">Primary</label>

							<input type="radio" <?php if(isset($patient_medical_info['male_intertiliy_type']) && $patient_medical_info['male_intertiliy_type'] == "Secondary"){echo 'checked="checked"';}?> name="male_intertiliy_type" value="Secondary">

							<label for="age2">Secondary</label>

						</td>

					</tr>

					<tr>

						<th style="color: red;">PAST GYNECOLOGICAL/UROLOGICAL HISTORY</th>

						<td>

							<table>

								<tr>

									<td>Dysmenorrhea</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_dysmenorrhea'])?$patient_medical_info['female_dysmenorrhea']:""; ?>" name="female_dysmenorrhea" class="form-control" required></td>

								</tr>

								<tr>

									<td>Menorrhagia</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_menorrhagia'])?$patient_medical_info['female_menorrhagia']:""; ?>" name="female_menorrhagia" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">H/o D and c</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_h_o_dandc'])?$patient_medical_info['female_h_o_dandc']:""; ?>" name="female_h_o_dandc" class="form-control" required></td>

								</tr>

								<tr>

									<td>Dyspareunia</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_dyspareunia'])?$patient_medical_info['female_dyspareunia']:""; ?>" name="female_dyspareunia" class="form-control" required></td>

								</tr>
								
								<tr>

									<td>Orchidopexies</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_orchidopexies'])?$patient_medical_info['female_orchidopexies']:""; ?>" name="female_orchidopexies" class="form-control" required></td>

								</tr>

								<tr>

									<td>Mumps/orchitis</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_mumps_orchitis'])?$patient_medical_info['female_mumps_orchitis']:""; ?>" name="female_mumps_orchitis" class="form-control" required></td>

								</tr>

								<tr>

									<td>Hernia</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_hernia'])?$patient_medical_info['female_hernia']:""; ?>" name="female_hernia" class="form-control" required></td>

								</tr>

								<tr>

									<td>Varicocele</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_varicocele'])?$patient_medical_info['female_varicocele']:""; ?>" name="female_varicocele" class="form-control" required></td>

								</tr>

								<tr>

									<td>Others</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_history_other'])?$patient_medical_info['female_history_other']:""; ?>" name="female_history_other" class="form-control" required></td>

								</tr>

							</table>

						</td>

						<td>

							<table> 
							
							<tr>

									<td>Dysmenorrhea</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_dysmenorrhea'])?$patient_medical_info['male_dysmenorrhea']:""; ?>" name="male_dysmenorrhea" class="form-control" required></td>

								</tr>

								<tr>

									<td>Menorrhagia</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_menorrhagia'])?$patient_medical_info['male_menorrhagia']:""; ?>" name="male_menorrhagia" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">H/o D and c</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_h_o_dandc'])?$patient_medical_info['male_h_o_dandc']:""; ?>" name="male_h_o_dandc" class="form-control" required></td>

								</tr>

								<tr>

									<td>Dyspareunia</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_dyspareunia'])?$patient_medical_info['male_dyspareunia']:""; ?>" name="male_dyspareunia" class="form-control" required></td>

								</tr>

								
								<tr>

									<td>Orchidopexies</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_orchidopexies'])?$patient_medical_info['male_orchidopexies']:""; ?>" name="male_orchidopexies" class="form-control" required></td>

								</tr>

								<tr>

									<td>Mumps/orchitis</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_mumps_orchitis'])?$patient_medical_info['male_mumps_orchitis']:""; ?>" name="male_mumps_orchitis" class="form-control" required></td>

								</tr>

								<tr>

									<td>Hernia</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_hernia'])?$patient_medical_info['male_hernia']:""; ?>" name="male_hernia" class="form-control" required></td>

								</tr>

								<tr>

									<td>Varicocele</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_varicocele'])?$patient_medical_info['male_varicocele']:""; ?>" name="male_varicocele" class="form-control" required></td>

								</tr>

								<tr>

									<td>Others</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_history_other'])?$patient_medical_info['male_history_other']:""; ?>" name="male_history_other" class="form-control" required></td>

								</tr>

							</table>

						</td>

					</tr>

					<tr>

						<th style="color: red;">MENSTRUATION HISTORY</th>

						<td>

							<table>

								<tr>

									<td>Age at menarche</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_menarche_age'])?$patient_medical_info['female_menarche_age']:""; ?>" name="female_menarche_age" class="form-control" required></td>

								</tr>

								<tr>

									<td>Flow- heavy/average/less</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_flow'])?$patient_medical_info['female_flow']:""; ?>" name="female_flow" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Frequency- regular /irregular</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_frequency'])?$patient_medical_info['female_frequency']:""; ?>" name="female_frequency" class="form-control" required></td>

								</tr>

								<tr>

									<td>Days</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_days'])?$patient_medical_info['female_days']:""; ?>" name="female_days" class="form-control" required></td>

								</tr>

								<tr>

									<td>Hirsutism</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_hirsutism'])?$patient_medical_info['female_hirsutism']:""; ?>" name="female_hirsutism" class="form-control" required></td>

								</tr>

								<tr>

									<td>Galactorrhea</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_galactorrhea'])?$patient_medical_info['female_galactorrhea']:""; ?>" name="female_galactorrhea" class="form-control" required></td>

								</tr>

							</table>

						</td>
						
						<td>

							<table>

								<tr>

									<td>Age at menarche</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_menarche_age'])?$patient_medical_info['male_menarche_age']:""; ?>" name="male_menarche_age" class="form-control" required></td>

								</tr>

								<tr>

									<td>Flow- heavy/average/less</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_flow'])?$patient_medical_info['male_flow']:""; ?>" name="male_flow" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Frequency- regular /irregular</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_frequency'])?$patient_medical_info['male_frequency']:""; ?>" name="male_frequency" class="form-control" required></td>

								</tr>

								<tr>

									<td>Days</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_days'])?$patient_medical_info['male_days']:""; ?>" name="male_days" class="form-control" required></td>

								</tr>

								<tr>

									<td>Hirsutism</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_hirsutism'])?$patient_medical_info['male_hirsutism']:""; ?>" name="male_hirsutism" class="form-control" required></td>

								</tr>

								<tr>

									<td>Galactorrhea</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_galactorrhea'])?$patient_medical_info['male_galactorrhea']:""; ?>" name="male_galactorrhea" class="form-control" required></td>

								</tr>

							</table>

						</td>

					</tr>

					<tr>

						<th style="color: red;">PAST /PRESENT MEDICAL HISTORY</th>

						<td>

							<table width="100%">

								<tr>

									<td>Heart attack</td>

												<td>

													<input type="radio" id="text1" name="heart_attack"  <?php if(isset($patient_medical_info['heart_attack']) && $patient_medical_info['heart_attack'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="heart_attack"  <?php if(isset($patient_medical_info['heart_attack']) && $patient_medical_info['heart_attack'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['heart_attack_text'])?$patient_medical_info['heart_attack_text']:""; ?>" maxlength="25" name="heart_attack_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Pacemaker</td>

												<td>

													<input type="radio" id="text1" name="pacemaker"  <?php if(isset($patient_medical_info['pacemaker']) && $patient_medical_info['pacemaker'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="pacemaker"  <?php if(isset($patient_medical_info['pacemaker']) && $patient_medical_info['pacemaker'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['pacemaker_text'])?$patient_medical_info['pacemaker_text']:""; ?>" maxlength="25" name="pacemaker_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Other heart disease</td>

												<td>

													<input type="radio" id="text1" name="other_heart_disease"  <?php if(isset($patient_medical_info['other_heart_disease']) && $patient_medical_info['other_heart_disease'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="other_heart_disease"  <?php if(isset($patient_medical_info['other_heart_disease']) && $patient_medical_info['other_heart_disease'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['other_heart_disease_text'])?$patient_medical_info['other_heart_disease_text']:""; ?>" maxlength="25" name="other_heart_disease_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>High blood pressure</td>

												<td>

													<input type="radio" id="text1" name="high_blood_pressure"  <?php if(isset($patient_medical_info['high_blood_pressure']) && $patient_medical_info['high_blood_pressure'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="high_blood_pressure"  <?php if(isset($patient_medical_info['high_blood_pressure']) && $patient_medical_info['high_blood_pressure'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['high_blood_pressure_text'])?$patient_medical_info['high_blood_pressure_text']:""; ?>" maxlength="25" name="high_blood_pressure_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Blood clots</td>

												<td>

													<input type="radio" id="text1" name="blood_clots"  <?php if(isset($patient_medical_info['blood_clots']) && $patient_medical_info['blood_clots'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="blood_clots"  <?php if(isset($patient_medical_info['blood_clots']) && $patient_medical_info['blood_clots'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['blood_clots_text'])?$patient_medical_info['blood_clots_text']:""; ?>" maxlength="25" name="blood_clots_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Chest pain</td>

												<td>

													<input type="radio" id="text1" name="chest_pain"  <?php if(isset($patient_medical_info['chest_pain']) && $patient_medical_info['chest_pain'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="chest_pain"  <?php if(isset($patient_medical_info['chest_pain']) && $patient_medical_info['chest_pain'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['chest_pain_text'])?$patient_medical_info['chest_pain_text']:""; ?>" maxlength="25" name="chest_pain_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Stroke</td>

												<td>

													<input type="radio" id="text1" name="stroke"  <?php if(isset($patient_medical_info['stroke']) && $patient_medical_info['stroke'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="stroke"  <?php if(isset($patient_medical_info['stroke']) && $patient_medical_info['stroke'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['stroke_text'])?$patient_medical_info['stroke_text']:""; ?>" maxlength="25" name="stroke_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Asthma</td>

												<td>

													<input type="radio" id="text1" name="asthma"  <?php if(isset($patient_medical_info['asthma']) && $patient_medical_info['asthma'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="asthma"  <?php if(isset($patient_medical_info['asthma']) && $patient_medical_info['asthma'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['asthma_text'])?$patient_medical_info['asthma_text']:""; ?>" maxlength="25" name="asthma_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Other lung disease</td>

												<td>

													<input type="radio" id="text1" name="lung_disease"  <?php if(isset($patient_medical_info['lung_disease']) && $patient_medical_info['lung_disease'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="lung_disease"  <?php if(isset($patient_medical_info['lung_disease']) && $patient_medical_info['lung_disease'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['lung_disease_text'])?$patient_medical_info['lung_disease_text']:""; ?>" maxlength="25" name="lung_disease_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Difficulty breathing</td>

												<td>

													<input type="radio" id="text1" name="difficulty_breathing"  <?php if(isset($patient_medical_info['difficulty_breathing']) && $patient_medical_info['difficulty_breathing'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="difficulty_breathing"  <?php if(isset($patient_medical_info['difficulty_breathing']) && $patient_medical_info['difficulty_breathing'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['difficulty_breathing_text'])?$patient_medical_info['difficulty_breathing_text']:""; ?>" maxlength="25" name="difficulty_breathing_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Sleep apnea or snoring</td>

												<td>

													<input type="radio" id="text1" name="snoring"  <?php if(isset($patient_medical_info['snoring']) && $patient_medical_info['snoring'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="snoring"  <?php if(isset($patient_medical_info['snoring']) && $patient_medical_info['snoring'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['snoring_text'])?$patient_medical_info['snoring_text']:""; ?>" maxlength="25" name="snoring_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Epilepsy or seizures</td>

												<td>

													<input type="radio" id="text1" name="epilepsy"  <?php if(isset($patient_medical_info['epilepsy']) && $patient_medical_info['epilepsy'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="epilepsy"  <?php if(isset($patient_medical_info['epilepsy']) && $patient_medical_info['epilepsy'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['epilepsy_text'])?$patient_medical_info['epilepsy_text']:""; ?>" maxlength="25" name="epilepsy_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Fainting spells</td>

												<td>

													<input type="radio" id="text1" name="fainting_spells"  <?php if(isset($patient_medical_info['fainting_spells']) && $patient_medical_info['fainting_spells'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="fainting_spells"  <?php if(isset($patient_medical_info['fainting_spells']) && $patient_medical_info['fainting_spells'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['fainting_spells_text'])?$patient_medical_info['fainting_spells_text']:""; ?>" maxlength="25" name="fainting_spells_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Diabetes</td>

												<td>

													<input type="radio" id="text1" name="diabetes"  <?php if(isset($patient_medical_info['diabetes']) && $patient_medical_info['diabetes'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="diabetes"  <?php if(isset($patient_medical_info['diabetes']) && $patient_medical_info['diabetes'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['diabetes_text'])?$patient_medical_info['diabetes_text']:""; ?>" maxlength="25" name="diabetes_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Muscle disorders</td>

												<td>

													<input type="radio" id="text1" name="muscle_disorders"  <?php if(isset($patient_medical_info['muscle_disorders']) && $patient_medical_info['muscle_disorders'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="muscle_disorders"  <?php if(isset($patient_medical_info['muscle_disorders']) && $patient_medical_info['muscle_disorders'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['muscle_disorders_text'])?$patient_medical_info['muscle_disorders_text']:""; ?>" maxlength="25" name="muscle_disorders_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Kidney disease</td>

												<td>

													<input type="radio" id="text1" name="kidney_disease"  <?php if(isset($patient_medical_info['kidney_disease']) && $patient_medical_info['kidney_disease'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="kidney_disease"  <?php if(isset($patient_medical_info['kidney_disease']) && $patient_medical_info['kidney_disease'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['kidney_disease_text'])?$patient_medical_info['kidney_disease_text']:""; ?>" maxlength="25" name="kidney_disease_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Hepatitis</td>

												<td>

													<input type="radio" id="text1" name="hepatitis"  <?php if(isset($patient_medical_info['hepatitis']) && $patient_medical_info['hepatitis'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="hepatitis"  <?php if(isset($patient_medical_info['hepatitis']) && $patient_medical_info['hepatitis'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['hepatitis_text'])?$patient_medical_info['hepatitis_text']:""; ?>" maxlength="25" name="hepatitis_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Tuberculosis</td>

												<td>

													<input type="radio" id="text1" name="tuberculosis"  <?php if(isset($patient_medical_info['tuberculosis']) && $patient_medical_info['tuberculosis'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="tuberculosis"  <?php if(isset($patient_medical_info['tuberculosis']) && $patient_medical_info['tuberculosis'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['tuberculosis_text'])?$patient_medical_info['tuberculosis_text']:""; ?>" maxlength="25" name="tuberculosis_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>HIV</td>

												<td>

													<input type="radio" id="text1" name="hiv"  <?php if(isset($patient_medical_info['hiv']) && $patient_medical_info['hiv'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="hiv"  <?php if(isset($patient_medical_info['hiv']) && $patient_medical_info['hiv'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['hiv_text'])?$patient_medical_info['hiv_text']:""; ?>" maxlength="25" name="hiv_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Heart burn/reflux</td>

												<td>

													<input type="radio" id="text1" name="heart_burn"  <?php if(isset($patient_medical_info['heart_burn']) && $patient_medical_info['heart_burn'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="heart_burn"  <?php if(isset($patient_medical_info['heart_burn']) && $patient_medical_info['heart_burn'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['heart_burn_text'])?$patient_medical_info['heart_burn_text']:""; ?>" maxlength="25" name="heart_burn_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Cancer </td>

												<td>

													<input type="radio" id="text1" name="cancer"  <?php if(isset($patient_medical_info['cancer']) && $patient_medical_info['cancer'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="cancer"  <?php if(isset($patient_medical_info['cancer']) && $patient_medical_info['cancer'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['cancer_text'])?$patient_medical_info['cancer_text']:""; ?>" maxlength="25" name="cancer_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Blood disorders</td>

												<td>

													<input type="radio" id="text1" name="blood_disorders"  <?php if(isset($patient_medical_info['blood_disorders']) && $patient_medical_info['blood_disorders'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="blood_disorders"  <?php if(isset($patient_medical_info['blood_disorders']) && $patient_medical_info['blood_disorders'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['blood_disorders_text'])?$patient_medical_info['blood_disorders_text']:""; ?>" maxlength="25" name="blood_disorders_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Rheumatic disease</td>

												<td>

													<input type="radio" id="text1" name="rheumatic_disease"  <?php if(isset($patient_medical_info['rheumatic_disease']) && $patient_medical_info['rheumatic_disease'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="rheumatic_disease"  <?php if(isset($patient_medical_info['rheumatic_disease']) && $patient_medical_info['rheumatic_disease'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['rheumatic_disease_text'])?$patient_medical_info['rheumatic_disease_text']:""; ?>" maxlength="25" name="rheumatic_disease_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Psychiatric disorder</td>

												<td>

													<input type="radio" id="text1" name="psychiatric_disorder"  <?php if(isset($patient_medical_info['psychiatric_disorder']) && $patient_medical_info['psychiatric_disorder'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="psychiatric_disorder"  <?php if(isset($patient_medical_info['psychiatric_disorder']) && $patient_medical_info['psychiatric_disorder'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['psychiatric_disorder_text'])?$patient_medical_info['psychiatric_disorder_text']:""; ?>" maxlength="25" name="psychiatric_disorder_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Thyroid disorder</td>

												<td>

													<input type="radio" id="text1" name="thyroid_disorder"  <?php if(isset($patient_medical_info['thyroid_disorder']) && $patient_medical_info['thyroid_disorder'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="thyroid_disorder"  <?php if(isset($patient_medical_info['thyroid_disorder']) && $patient_medical_info['thyroid_disorder'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['thyroid_disorder_text'])?$patient_medical_info['thyroid_disorder_text']:""; ?>" maxlength="25" name="thyroid_disorder_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Urinary infection</td>

												<td>

													<input type="radio" id="text1" name="urinary_infection"  <?php if(isset($patient_medical_info['urinary_infection']) && $patient_medical_info['urinary_infection'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="urinary_infection"  <?php if(isset($patient_medical_info['urinary_infection']) && $patient_medical_info['urinary_infection'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['urinary_infection_text'])?$patient_medical_info['urinary_infection_text']:""; ?>" maxlength="25" name="urinary_infection_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Sexually transmitted disease</td>

												<td>

													<input type="radio" id="text1" name="sexually_transmitted"  <?php if(isset($patient_medical_info['sexually_transmitted']) && $patient_medical_info['sexually_transmitted'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="sexually_transmitted"  <?php if(isset($patient_medical_info['sexually_transmitted']) && $patient_medical_info['sexually_transmitted'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['sexually_transmitted_text'])?$patient_medical_info['sexually_transmitted_text']:""; ?>" maxlength="25" name="sexually_transmitted_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Other medical condition or impairments</td>

									<td>

										<input type="text" maxlength="50" class="form-control">

									</td>

								</tr>

							</table>

						</td>

						<td>

							<table width="100%">

								<tr>

									<td>Heart attack</td>

												<td>

													<input type="radio" id="text1" name="male_heart_attack"  <?php if(isset($patient_medical_info['male_heart_attack']) && $patient_medical_info['male_heart_attack'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_heart_attack"  <?php if(isset($patient_medical_info['male_heart_attack']) && $patient_medical_info['male_heart_attack'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_heart_attack_text'])?$patient_medical_info['male_heart_attack_text']:""; ?>" maxlength="25" name="male_heart_attack_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Pacemaker</td>

												<td>

													<input type="radio" id="text1" name="male_pacemaker"  <?php if(isset($patient_medical_info['male_pacemaker']) && $patient_medical_info['male_pacemaker'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_pacemaker"  <?php if(isset($patient_medical_info['male_pacemaker']) && $patient_medical_info['male_pacemaker'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_pacemaker_text'])?$patient_medical_info['male_pacemaker_text']:"";  ?>" maxlength="25" name="male_pacemaker_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Other heart disease</td>

												<td>

													<input type="radio" id="text1" name="male_other_heart_disease"  <?php if(isset($patient_medical_info['male_other_heart_disease']) && $patient_medical_info['male_other_heart_disease'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_other_heart_disease"  <?php if(isset($patient_medical_info['male_other_heart_disease']) && $patient_medical_info['male_other_heart_disease'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_other_heart_disease_text'])?$patient_medical_info['male_other_heart_disease_text']:""; ?>" maxlength="25" name="male_other_heart_disease_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>High blood pressure</td>

												<td>

													<input type="radio" id="text1" name="male_high_blood_pressure"  <?php if(isset($patient_medical_info['male_high_blood_pressure']) && $patient_medical_info['male_high_blood_pressure'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_high_blood_pressure"  <?php if(isset($patient_medical_info['male_high_blood_pressure']) && $patient_medical_info['male_high_blood_pressure'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_high_blood_pressure_text'])?$patient_medical_info['male_high_blood_pressure_text']:""; ?>" maxlength="25" name="male_high_blood_pressure_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Blood clots</td>

												<td>

													<input type="radio" id="text1" name="male_blood_clots"  <?php if(isset($patient_medical_info['male_blood_clots']) && $patient_medical_info['male_blood_clots'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_blood_clots"  <?php if(isset($patient_medical_info['male_blood_clots']) && $patient_medical_info['male_blood_clots'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_blood_clots_text'])?$patient_medical_info['male_blood_clots_text']:""; ?>" maxlength="25" name="male_blood_clots_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Chest pain</td>

												<td>

													<input type="radio" id="text1" name="male_chest_pain"  <?php if(isset($patient_medical_info['male_chest_pain']) && $patient_medical_info['male_chest_pain'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_chest_pain"  <?php if(isset($patient_medical_info['male_chest_pain']) && $patient_medical_info['male_chest_pain'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_chest_pain_text'])?$patient_medical_info['male_chest_pain_text']:""; ?>" maxlength="25" name="male_chest_pain_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Stroke</td>

												<td>

													<input type="radio" id="text1" name="male_stroke"  <?php if(isset($patient_medical_info['male_stroke']) && $patient_medical_info['male_stroke'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_stroke"  <?php if(isset($patient_medical_info['male_stroke']) && $patient_medical_info['male_stroke'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_stroke_text'])?$patient_medical_info['male_stroke_text']:""; ?>" maxlength="25" name="male_stroke_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Asthma</td>

												<td>

													<input type="radio" id="text1" name="male_asthma"  <?php if(isset($patient_medical_info['male_asthma']) && $patient_medical_info['male_asthma'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_asthma"  <?php if(isset($patient_medical_info['male_asthma']) && $patient_medical_info['male_asthma'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_asthma_text'])?$patient_medical_info['male_asthma_text']:""; ?>" maxlength="25" name="male_asthma_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Other lung disease</td>

												<td>

													<input type="radio" id="text1" name="male_lung_disease"  <?php if(isset($patient_medical_info['male_lung_disease']) && $patient_medical_info['male_lung_disease'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_lung_disease"  <?php if(isset($patient_medical_info['male_lung_disease']) && $patient_medical_info['male_lung_disease'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_lung_disease_text'])?$patient_medical_info['male_lung_disease_text']:""; ?>" maxlength="25" name="male_lung_disease_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Difficulty breathing</td>

												<td>

													<input type="radio" id="text1" name="male_difficulty_breathing"  <?php if(isset($patient_medical_info['male_difficulty_breathing']) && $patient_medical_info['male_difficulty_breathing'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_difficulty_breathing"  <?php if(isset($patient_medical_info['male_difficulty_breathing']) && $patient_medical_info['male_difficulty_breathing'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_difficulty_breathing_text'])?$patient_medical_info['male_difficulty_breathing_text']:""; ?>" maxlength="25" name="male_difficulty_breathing_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Sleep apnea or snoring</td>

												<td>

													<input type="radio" id="text1" name="male_snoring"  <?php if(isset($patient_medical_info['male_snoring']) && $patient_medical_info['male_snoring'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_snoring"  <?php if(isset($patient_medical_info['male_snoring']) && $patient_medical_info['male_snoring'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_snoring_text'])?$patient_medical_info['male_snoring_text']:""; ?>" maxlength="25" name="male_snoring_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Epilepsy or seizures</td>

												<td>

													<input type="radio" id="text1" name="male_epilepsy"  <?php if(isset($patient_medical_info['male_epilepsy']) && $patient_medical_info['male_epilepsy'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_epilepsy"  <?php if(isset($patient_medical_info['male_epilepsy']) && $patient_medical_info['male_epilepsy'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_epilepsy_text'])?$patient_medical_info['male_epilepsy_text']:""; ?>" maxlength="25" name="male_epilepsy_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Fainting spells</td>

												<td>

													<input type="radio" id="text1" name="male_fainting_spells"  <?php if(isset($patient_medical_info['male_fainting_spells']) && $patient_medical_info['male_fainting_spells'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_fainting_spells"  <?php if(isset($patient_medical_info['male_fainting_spells']) && $patient_medical_info['male_fainting_spells'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_fainting_spells_text'])?$patient_medical_info['male_fainting_spells_text']:""; ?>" maxlength="25" name="male_fainting_spells_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Diabetes</td>

												<td>

													<input type="radio" id="text1" name="male_diabetes"  <?php if(isset($patient_medical_info['male_diabetes']) && $patient_medical_info['male_diabetes'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_diabetes"  <?php if(isset($patient_medical_info['male_diabetes']) && $patient_medical_info['male_diabetes'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_diabetes_text'])?$patient_medical_info['male_diabetes_text']:""; ?>" maxlength="25" name="male_diabetes_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Muscle disorders</td>

												<td>

													<input type="radio" id="text1" name="male_muscle_disorders"  <?php if(isset($patient_medical_info['male_muscle_disorders']) && $patient_medical_info['male_muscle_disorders'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_muscle_disorders"  <?php if(isset($patient_medical_info['male_muscle_disorders']) && $patient_medical_info['male_muscle_disorders'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_muscle_disorders_text'])?$patient_medical_info['male_muscle_disorders_text']:""; ?>" maxlength="25" name="male_muscle_disorders_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Kidney disease</td>

												<td>

													<input type="radio" id="text1" name="male_kidney_disease"  <?php if(isset($patient_medical_info['male_kidney_disease']) && $patient_medical_info['male_kidney_disease'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_kidney_disease"  <?php if(isset($patient_medical_info['male_kidney_disease']) && $patient_medical_info['male_kidney_disease'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_kidney_disease_text'])?$patient_medical_info['male_kidney_disease_text']:""; ?>" maxlength="25" name="male_kidney_disease_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Hepatitis</td>

												<td>

													<input type="radio" id="text1" name="male_hepatitis"  <?php if(isset($patient_medical_info['male_hepatitis']) && $patient_medical_info['male_hepatitis'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_hepatitis"  <?php if(isset($patient_medical_info['male_hepatitis']) && $patient_medical_info['male_hepatitis'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input type="text" value="<?php echo !empty($patient_medical_info['male_hepatitis_text'])?$patient_medical_info['male_hepatitis_text']:""; ?>" maxlength="25" name="male_hepatitis_text" style="display:none;">

												</td>

								</tr>

								<tr>

									<td>Tuberculosis</td>

									<td>

										<input type="radio" id="text1" name="male_tuberculosis"  <?php if(isset($patient_medical_info['male_tuberculosis']) && $patient_medical_info['male_tuberculosis'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" id="text1" name="male_tuberculosis"  <?php if(isset($patient_medical_info['male_tuberculosis']) && $patient_medical_info['male_tuberculosis'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_tuberculosis_text'])?$patient_medical_info['male_tuberculosis_text']:""; ?>" maxlength="25" name="male_tuberculosis_text" style="display:none;">

									</td>

								</tr>

								<tr>

									<td>HIV</td>

									<td>

										<input type="radio" id="text1" name="male_hiv"  <?php if(isset($patient_medical_info['male_hiv']) && $patient_medical_info['male_hiv'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" id="text1" name="male_hiv"  <?php if(isset($patient_medical_info['male_hiv']) && $patient_medical_info['male_hiv'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_hiv_text'])?$patient_medical_info['male_hiv_text']:""; ?>" maxlength="25" name="male_hiv_text" style="display:none;">

									</td>

								</tr>

								<tr>

									<td>Heart burn/reflux</td>

									<td>

										<input type="radio" name="male_heart_burn"  <?php if(isset($patient_medical_info['male_heart_burn']) && $patient_medical_info['male_heart_burn'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="male_heart_burn"  <?php if(isset($patient_medical_info['male_heart_burn']) && $patient_medical_info['male_heart_burn'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label for="type2">No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_heart_burn_text'])?$patient_medical_info['male_heart_burn_text']:""; ?>" maxlength="25" name="male_heart_burn_text" style="display:none;">

									</td>

								</tr>

								<tr>

									<td>Cancer </td>

									<td>

										<input type="radio" name="male_cancer"  <?php if(isset($patient_medical_info['male_cancer']) && $patient_medical_info['male_cancer'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="male_cancer"  <?php if(isset($patient_medical_info['male_cancer']) && $patient_medical_info['male_cancer'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label for="type2">No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_cancer_text'])?$patient_medical_info['male_cancer_text']:""; ?>" maxlength="25" name="male_cancer_text" style="display:none;">

									</td>

								</tr>

								<tr>

									<td>Blood disorders</td>

									<td>

										<input type="radio" name="male_blood_disorders"  <?php if(isset($patient_medical_info['male_blood_disorders']) && $patient_medical_info['male_blood_disorders'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="male_blood_disorders"  <?php if(isset($patient_medical_info['male_blood_disorders']) && $patient_medical_info['male_blood_disorders'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label for="type2">No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_blood_disorders_text'])?$patient_medical_info['male_blood_disorders_text']:""; ?>" maxlength="25" name="male_blood_disorders_text" style="display:none;">

									</td>

								</tr>

								<tr>

									<td>Rheumatic disease</td>

									<td>

										<input type="radio" name="male_rheumatic_disease"  <?php if(isset($patient_medical_info['male_rheumatic_disease']) && $patient_medical_info['male_rheumatic_disease'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="male_rheumatic_disease"  <?php if(isset($patient_medical_info['male_rheumatic_disease']) && $patient_medical_info['male_rheumatic_disease'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

										<label for="type2">No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_rheumatic_disease_text'])?$patient_medical_info['male_rheumatic_disease_text']:""; ?>" maxlength="25" name="male_rheumatic_disease_text" style="display:none;">

									</td>

								</tr>

								<tr>

									<td>Psychiatric disorder</td>

									<td>

										<input type="radio" name="male_psychiatric_disorder"  <?php if(isset($patient_medical_info['male_psychiatric_disorder']) && $patient_medical_info['male_psychiatric_disorder'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="male_psychiatric_disorder"  <?php if(isset($patient_medical_info['male_psychiatric_disorder']) && $patient_medical_info['male_psychiatric_disorder'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label for="type2">No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_psychiatric_disorder_text'])?$patient_medical_info['male_psychiatric_disorder_text']:""; ?>" maxlength="25" name="male_psychiatric_disorder_text" style="display:none;">

									</td>

								</tr>

								<tr>

									<td>Thyroid disorder</td>

									<td>

										<input type="radio" name="male_thyroid_disorder"  <?php if(isset($patient_medical_info['male_thyroid_disorder']) && $patient_medical_info['male_thyroid_disorder'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="male_thyroid_disorder"  <?php if(isset($patient_medical_info['male_thyroid_disorder']) && $patient_medical_info['male_thyroid_disorder'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label for="type2">No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_thyroid_disorder_text'])?$patient_medical_info['male_thyroid_disorder_text']:""; ?>" maxlength="25" name="male_thyroid_disorder_text" style="display:none;">

									</td>

								</tr>

								<tr>

									<td>Urinary infection</td>

									<td>

										<input type="radio" name="male_urinary_infection"  <?php if(isset($patient_medical_info['male_urinary_infection']) && $patient_medical_info['male_urinary_infection'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="male_urinary_infection"  <?php if(isset($patient_medical_info['male_urinary_infection']) && $patient_medical_info['male_urinary_infection'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label for="type2">No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_urinary_infection_text'])?$patient_medical_info['male_urinary_infection_text']:""; ?>" maxlength="25" name="male_urinary_infection_text" style="display:none;">

									</td>

								</tr>

								<tr>

									<td>Sexually transmitted disease</td>

									<td>

										<input type="radio" name="male_sexually_transmitted"  <?php if(isset($patient_medical_info['male_sexually_transmitted']) && $patient_medical_info['male_sexually_transmitted'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="male_sexually_transmitted"  <?php if(isset($patient_medical_info['male_sexually_transmitted']) && $patient_medical_info['male_sexually_transmitted'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label for="type2">No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_sexually_transmitted_text'])?$patient_medical_info['male_sexually_transmitted_text']:""; ?>" maxlength="25" name="male_sexually_transmitted_text" style="display:none;">

									</td>

								</tr>

								<tr>

									<td>Other medical condition or impairments</td>

									<td>

										<input type="text" maxlength="50" class="form-control">

									</td>

								</tr>

							</table>

						</td>

					</tr>

					<tr>

						<th style="color: red;">PAST ANESTHESIA AND SURGICAL PROCEDURES</th>

						<td>

							<table>

								<tr>

									<td>Laparoscopy/pelvic/abdominal operations</td>

									<td>

										<p>

										<input type="radio" id="text1" name="abdominal_operations"  <?php if(isset($patient_medical_info['abdominal_operations']) && $patient_medical_info['abdominal_operations'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" id="text1" name="abdominal_operations"  <?php if(isset($patient_medical_info['abdominal_operations']) && $patient_medical_info['abdominal_operations'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label>No</label></p>

										<p>

										<input type="text" value="<?php echo !empty($patient_medical_info['abdominal_operations_text'])?$patient_medical_info['abdominal_operations_text']:""; ?>" maxlength="25" name="abdominal_operations_text">

										</p>

									</td>

								</tr>

								<tr>

									<td>Other operations</td>

									<td>

										<input type="radio" id="text1" name="other_operations"  <?php if(isset($patient_medical_info['other_operations']) && $patient_medical_info['other_operations'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" id="text1" name="other_operations"  <?php if(isset($patient_medical_info['other_operations']) && $patient_medical_info['other_operations'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['other_operations_text'])?$patient_medical_info['other_operations_text']:""; ?>" maxlength="25" name="other_operations_text">

									</td>

								</tr>

							</table>

						</td>

						<td>

							<table>

								<tr>

									<td>Laparoscopy/pelvic/abdominal operations</td>

									<td>

										<input type="radio" id="text1" name="male_abdominal_operations"  <?php if(isset($patient_medical_info['male_abdominal_operations']) && $patient_medical_info['male_abdominal_operations'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" id="text1" name="male_abdominal_operations"  <?php if(isset($patient_medical_info['male_abdominal_operations']) && $patient_medical_info['male_abdominal_operations'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_abdominal_operations_text'])?$patient_medical_info['male_abdominal_operations_text']:""; ?>" maxlength="25" name="male_abdominal_operations_text">

									</td>

								</tr>

								<tr>

									<td>Other operations</td>

									<td>

										<input type="radio" id="text1" name="male_other_operations"  <?php if(isset($patient_medical_info['male_other_operations']) && $patient_medical_info['male_other_operations'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" id="text1" name="male_other_operations"  <?php if(isset($patient_medical_info['male_other_operations']) && $patient_medical_info['male_other_operations'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_other_operations_text'])?$patient_medical_info['male_other_operations_text']:""; ?>" maxlength="25" name="male_other_operations_text">

									</td>

								</tr>

							</table>

						</td>

					</tr>

					<tr>

						<th style="color: red;">ALLERGY HISTORY</th>

						<td>

							<table width="100%">

								<tr>

									<td>Medications</td>

									<td>

										<input type="radio" id="text1" name="medications"  <?php if(isset($patient_medical_info['medications']) && $patient_medical_info['medications'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" id="text1" name="medications"  <?php if(isset($patient_medical_info['medications']) && $patient_medical_info['medications'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['medications_text'])?$patient_medical_info['medications_text']:""; ?>" maxlength="25" name="medications_text">

									</td>

								</tr>

								<tr>

									<td>environmental factors</td>

									<td>

										<input type="radio" id="text1" name="environmental_factors"  <?php if(isset($patient_medical_info['environmental_factors']) && $patient_medical_info['environmental_factors'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" id="text1" name="environmental_factors"  <?php if(isset($patient_medical_info['environmental_factors']) && $patient_medical_info['environmental_factors'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['environmental_factors_text'])?$patient_medical_info['environmental_factors_text']:""; ?>" maxlength="25" name="environmental_factors_text">

									</td>

								</tr>

							</table>

						</td>

						<td>

							<table width="100%">

								<tr>

									<td>Medications</td>

									<td>

										<input type="radio" id="text1" name="male_medications"  <?php if(isset($patient_medical_info['male_medications']) && $patient_medical_info['male_medications'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" id="text1" name="male_medications"  <?php if(isset($patient_medical_info['male_medications']) && $patient_medical_info['male_medications'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_medications_text'])?$patient_medical_info['male_medications_text']:""; ?>" maxlength="25" name="male_medications_text">

									</td>

								</tr>

								<tr>

									<td>environmental factors</td>

									<td>

										<input type="radio" id="text1" name="male_environmental_factors"  <?php if(isset($patient_medical_info['male_environmental_factors']) && $patient_medical_info['male_environmental_factors'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" id="text1" name="male_environmental_factors"  <?php if(isset($patient_medical_info['male_environmental_factors']) && $patient_medical_info['male_environmental_factors'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_environmental_factors_text'])?$patient_medical_info['male_environmental_factors_text']:""; ?>" maxlength="25" name="male_environmental_factors_text">

									</td>

								</tr>

							</table>

						</td>

					</tr>

					<tr>

						<th style="color: red;">SOCIAL & DRUG INTAKE HISTORY</th>

						<td>

							<table>

								<tr>

									<td>Dentures</td>

												<td width="30%">

													<input type="radio" id="text1" name="dentures"  <?php if(isset($patient_medical_info['dentures']) && $patient_medical_info['dentures'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="dentures"  <?php if(isset($patient_medical_info['dentures']) && $patient_medical_info['dentures'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['dentures_text'])?$patient_medical_info['dentures_text']:""; ?>" type="text" maxlength="25" name="dentures_text">

												</td>

								</tr>

								<tr>

									<td>Loose teeth</td>

												<td>

													<input type="radio" id="text1" name="loose_teeth"  <?php if(isset($patient_medical_info['loose_teeth']) && $patient_medical_info['loose_teeth'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="loose_teeth"  <?php if(isset($patient_medical_info['loose_teeth']) && $patient_medical_info['loose_teeth'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input  value="<?php echo !empty($patient_medical_info['loose_teeth_text'])?$patient_medical_info['loose_teeth_text']:""; ?>" type="text" maxlength="25" name="loose_teeth_text">

												</td>

								</tr>

								<tr>

									<td>Hearing aid</td>

												<td>

													<input type="radio" id="text1" name="hearing_aid"  <?php if(isset($patient_medical_info['hearing_aid']) && $patient_medical_info['hearing_aid'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="hearing_aid"  <?php if(isset($patient_medical_info['hearing_aid']) && $patient_medical_info['hearing_aid'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['hearing_aid_text'])?$patient_medical_info['hearing_aid_text']:""; ?>" type="text" maxlength="25" name="hearing_aid_text">

												</td>

								</tr>

								<tr>

									<td>Caps on front teeth</td>

												<td>

													<input type="radio" id="text1" name="caps_on_front_teeth"  <?php if(isset($patient_medical_info['caps_on_front_teeth']) && $patient_medical_info['caps_on_front_teeth'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="caps_on_front_teeth"  <?php if(isset($patient_medical_info['caps_on_front_teeth']) && $patient_medical_info['caps_on_front_teeth'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['caps_on_front_teeth_text'])?$patient_medical_info['caps_on_front_teeth_text']:""; ?>" type="text" maxlength="25" name="caps_on_front_teeth_text">

												</td>

								</tr>

								<tr>

									<td>Contact lenses</td>

												<td>

													<input type="radio" id="text1" name="contact_lenses"  <?php if(isset($patient_medical_info['contact_lenses']) && $patient_medical_info['contact_lenses'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="contact_lenses"  <?php if(isset($patient_medical_info['contact_lenses']) && $patient_medical_info['contact_lenses'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['contact_lenses_text'])?$patient_medical_info['contact_lenses_text']:""; ?>" type="text" maxlength="25" name="contact_lenses_text">

												</td>

								</tr>

								<tr>

									<td>Body piercing</td>

												<td>

													<input type="radio" id="text1" name="body_piercing"  <?php if(isset($patient_medical_info['body_piercing']) && $patient_medical_info['body_piercing'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="body_piercing"  <?php if(isset($patient_medical_info['body_piercing']) && $patient_medical_info['body_piercing'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['body_piercing_text'])?$patient_medical_info['body_piercing_text']:""; ?>" type="text" maxlength="25" name="body_piercing_text">

												</td>

								</tr>

								<tr>

									<td>H/o blood transfusion</td>

												<td>

													<input type="radio" id="text1" name="blood_transfusion"  <?php if(isset($patient_medical_info['blood_transfusion']) && $patient_medical_info['blood_transfusion'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="blood_transfusion"  <?php if(isset($patient_medical_info['blood_transfusion']) && $patient_medical_info['blood_transfusion'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['blood_transfusion_text'])?$patient_medical_info['blood_transfusion_text']:""; ?>" type="text" maxlength="25" name="blood_transfusion_text">

												</td>

								</tr>

								<tr>

									<td>H/o road traffic accident/any injury</td>

												<td>

													<input type="radio" id="text1" name="traffic_accident"  <?php if(isset($patient_medical_info['traffic_accident']) && $patient_medical_info['traffic_accident'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="traffic_accident"  <?php if(isset($patient_medical_info['traffic_accident']) && $patient_medical_info['traffic_accident'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['traffic_accident_text'])?$patient_medical_info['traffic_accident_text']:""; ?>" type="text" maxlength="25" name="traffic_accident_text">

												</td>

								</tr>

								<tr>

									<td>Smoke(past)daily</td>

												<td>

													<input type="radio" id="text1" name="smoke_past"  <?php if(isset($patient_medical_info['smoke_past']) && $patient_medical_info['smoke_past'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="smoke_past"  <?php if(isset($patient_medical_info['smoke_past']) && $patient_medical_info['smoke_past'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['smoke_past_text'])?$patient_medical_info['smoke_past_text']:""; ?>" type="text" maxlength="25" name="smoke_past_text">

												</td>

								</tr>

								<tr>

									<td>Smoke(present)daily</td>

												<td>

													<input type="radio" id="text1" name="smoke_present"  <?php if(isset($patient_medical_info['smoke_present']) && $patient_medical_info['smoke_present'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="smoke_present"  <?php if(isset($patient_medical_info['smoke_present']) && $patient_medical_info['smoke_present'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['smoke_present_text'])?$patient_medical_info['smoke_present_text']:"";?>" type="text" maxlength="25" name="smoke_present_text">

												</td>

								</tr>

								<tr>

									<td>Drink(past)units per week</td>

												<td>

													<input type="radio" id="text1" name="drink_past"  <?php if(isset($patient_medical_info['drink_past']) && $patient_medical_info['drink_past'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="drink_past"  <?php if(isset($patient_medical_info['drink_past']) && $patient_medical_info['drink_past'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['drink_past_text'])?$patient_medical_info['drink_past_text']:""; ?>" type="text" maxlength="25" name="drink_past_text">

												</td>

								</tr>

								<tr>

									<td>Drink(present)units per week</td>

												<td>

													<input type="radio" id="text1" name="drink_present"  <?php if(isset($patient_medical_info['drink_present']) && $patient_medical_info['drink_present'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="drink_present"  <?php if(isset($patient_medical_info['drink_present']) && $patient_medical_info['drink_present'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['drink_present_text'])?$patient_medical_info['drink_present_text']:""; ?>" type="text" maxlength="25" name="drink_present_text">

												</td>

								</tr>

								<tr>

									<td>Hashish/cocaine /abusive drugs</td>

												<td>

													<input type="radio" id="text1" name="abusive_drugs"  <?php if(isset($patient_medical_info['abusive_drugs']) && $patient_medical_info['abusive_drugs'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="abusive_drugs"  <?php if(isset($patient_medical_info['abusive_drugs']) && $patient_medical_info['abusive_drugs'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['abusive_drugs_text'])?$patient_medical_info['abusive_drugs_text']:""; ?>" type="text" maxlength="25" name="abusive_drugs_text">

												</td>

								</tr>

								<tr>

									<td>Have you ever used cortisone/steroid</td>

												<td>

													<input type="radio" id="text1" name="steroid"  <?php if(isset($patient_medical_info['steroid']) && $patient_medical_info['steroid'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="steroid"  <?php if(isset($patient_medical_info['steroid']) && $patient_medical_info['steroid'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['steroid_text'])?$patient_medical_info['steroid_text']:""; ?>" type="text" maxlength="25" name="steroid_text">

												</td>

								</tr>

								<tr>

									<td>Medication</td>

												<td>

													<input type="radio" id="text1" name="medication"  <?php if(isset($patient_medical_info['medication']) && $patient_medical_info['medication'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="medication"  <?php if(isset($patient_medical_info['medication']) && $patient_medical_info['medication'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['medication_text'])?$patient_medical_info['medication_text']:""; ?>" type="text" maxlength="25" name="medication_text">

												</td>

								</tr>

								<tr>

									<td>Herbal products</td>

												<td>

													<input type="radio" id="text1" name="herbal_products"  <?php if(isset($patient_medical_info['herbal_products']) && $patient_medical_info['herbal_products'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="herbal_products"  <?php if(isset($patient_medical_info['herbal_products']) && $patient_medical_info['herbal_products'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['herbal_products_text'])?$patient_medical_info['herbal_products_text']:""; ?>" type="text" maxlength="25" name="herbal_products_text">

												</td>

								</tr>

								<tr>

									<td>Eye drops</td>

												<td>

													<input type="radio" id="text1" name="eye_drops"  <?php if(isset($patient_medical_info['eye_drops']) && $patient_medical_info['eye_drops'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="eye_drops"  <?php if(isset($patient_medical_info['eye_drops']) && $patient_medical_info['eye_drops'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['eye_drops_text'])?$patient_medical_info['eye_drops_text']:""; ?>" type="text" maxlength="25" name="eye_drops_text">

												</td>

								</tr>

								<tr>

									<td>Non prescription drugs used currently other than medications used for this IVF cycle</td>

												<td>

													<input type="radio" id="text1" name="non_prescription_drugs"  <?php if(isset($patient_medical_info['non_prescription_drugs']) && $patient_medical_info['non_prescription_drugs'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="non_prescription_drugs"  <?php if(isset($patient_medical_info['non_prescription_drugs']) && $patient_medical_info['non_prescription_drugs'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['non_prescription_drugs_text'])?$patient_medical_info['non_prescription_drugs_text']:""; ?>" type="text" maxlength="25" name="non_prescription_drugs_text">

												</td>

								</tr>

							</table>

						</td>

						<td>

							<table>

								<tr>

									<td>Dentures</td>

												<td width="30%">

													<input type="radio" id="text1" name="male_dentures"  <?php if(isset($patient_medical_info['male_dentures']) && $patient_medical_info['male_dentures'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_dentures"  <?php if(isset($patient_medical_info['male_dentures']) && $patient_medical_info['male_dentures'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_dentures_text'])?$patient_medical_info['male_dentures_text']:""; ?>" type="text" maxlength="25" name="male_dentures_text">

												</td>

								</tr>

								<tr>

									<td>Loose teeth</td>

												<td>

													<input type="radio" id="text1" name="male_loose_teeth"  <?php if(isset($patient_medical_info['male_loose_teeth']) && $patient_medical_info['male_loose_teeth'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_loose_teeth"  <?php if(isset($patient_medical_info['male_loose_teeth']) && $patient_medical_info['male_loose_teeth'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_loose_teeth_text'])?$patient_medical_info['male_loose_teeth_text']:""; ?>" type="text" maxlength="25" name="male_loose_teeth_text">

												</td>

								</tr>

								<tr>

									<td>Hearing aid</td>

												<td>

													<input type="radio" id="text1" name="male_hearing_aid"  <?php if(isset($patient_medical_info['male_hearing_aid']) && $patient_medical_info['male_hearing_aid'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_hearing_aid"  <?php if(isset($patient_medical_info['male_hearing_aid']) && $patient_medical_info['male_hearing_aid'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_hearing_aid_text'])?$patient_medical_info['male_hearing_aid_text']:""; ?>" type="text" maxlength="25" name="male_hearing_aid_text">

												</td>

								</tr>

								<tr>

									<td>Caps on front teeth</td>

												<td>

													<input type="radio" id="text1" name="male_caps_on_front_teeth"  <?php if(isset($patient_medical_info['male_caps_on_front_teeth']) && $patient_medical_info['male_caps_on_front_teeth'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_caps_on_front_teeth"  <?php if(isset($patient_medical_info['male_caps_on_front_teeth']) && $patient_medical_info['male_caps_on_front_teeth'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_caps_on_front_teeth_text'])?$patient_medical_info['male_caps_on_front_teeth_text']:""; ?>" type="text" maxlength="25" name="male_caps_on_front_teeth_text">

												</td>

								</tr>

								<tr>

									<td>Contact lenses</td>

												<td>

													<input type="radio" id="text1" name="male_contact_lenses"  <?php if(isset($patient_medical_info['male_contact_lenses']) && $patient_medical_info['male_contact_lenses'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_contact_lenses"  <?php if(isset($patient_medical_info['male_contact_lenses']) && $patient_medical_info['male_contact_lenses'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_contact_lenses_text'])?$patient_medical_info['male_contact_lenses_text']:""; ?>" type="text" maxlength="25" name="male_contact_lenses_text">

												</td>

								</tr>

								<tr>

									<td>Body piercing</td>

												<td>

													<input type="radio" id="text1" name="male_body_piercing"  <?php if(isset($patient_medical_info['male_body_piercing']) && $patient_medical_info['male_body_piercing'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_body_piercing"  <?php if(isset($patient_medical_info['male_body_piercing']) && $patient_medical_info['male_body_piercing'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_body_piercing_text'])?$patient_medical_info['male_body_piercing_text']:""; ?>" type="text" maxlength="25" name="male_body_piercing_text">

												</td>

								</tr>

								<tr>

									<td>H/o blood transfusion</td>

												<td>

													<input type="radio" id="text1" name="male_blood_transfusion"  <?php if(isset($patient_medical_info['male_blood_transfusion']) && $patient_medical_info['male_blood_transfusion'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_blood_transfusion"  <?php if(isset($patient_medical_info['male_blood_transfusion']) && $patient_medical_info['male_blood_transfusion'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_blood_transfusion_text'])?$patient_medical_info['male_blood_transfusion_text']:""; ?>" type="text" maxlength="25" name="male_blood_transfusion_text">

												</td>

								</tr>

								<tr>

									<td>H/o road traffic accident/any injury</td>

												<td>

													<input type="radio" id="text1" name="male_traffic_accident"  <?php if(isset($patient_medical_info['male_traffic_accident']) && $patient_medical_info['male_traffic_accident'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_traffic_accident"  <?php if(isset($patient_medical_info['male_traffic_accident']) && $patient_medical_info['male_traffic_accident'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_traffic_accident_text'])?$patient_medical_info['male_traffic_accident_text']:""; ?>" type="text" maxlength="25" name="male_traffic_accident_text">

												</td>

								</tr>

								<tr>

									<td>Smoke(past)daily</td>

												<td>

													<input type="radio" id="text1" name="male_smoke_past"  <?php if(isset($patient_medical_info['male_smoke_past']) && $patient_medical_info['male_smoke_past'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_smoke_past"  <?php if(isset($patient_medical_info['male_smoke_past']) && $patient_medical_info['male_smoke_past'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_smoke_past_text'])?$patient_medical_info['male_smoke_past_text']:""; ?>" type="text" maxlength="25" name="male_smoke_past_text">

												</td>

								</tr>

								<tr>

									<td>Smoke(present)daily</td>

												<td>

													<input type="radio" id="text1" name="male_smoke_present"  <?php if(isset($patient_medical_info['male_smoke_present']) && $patient_medical_info['male_smoke_present'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_smoke_present"  <?php if(isset($patient_medical_info['male_smoke_present']) && $patient_medical_info['male_smoke_present'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_smoke_present_text'])?$patient_medical_info['male_smoke_present_text']:""; ?>" type="text" maxlength="25" name="male_smoke_present_text">

												</td>

								</tr>

								<tr>

									<td>Drink(past)units per week</td>

												<td>

													<input type="radio" id="text1" name="male_drink_past"  <?php if(isset($patient_medical_info['male_drink_past']) && $patient_medical_info['male_drink_past'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_drink_past"  <?php if(isset($patient_medical_info['male_drink_past']) && $patient_medical_info['male_drink_past'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_drink_past_text'])?$patient_medical_info['male_drink_past_text']:""; ?>" type="text" maxlength="25" name="male_drink_past_text">

												</td>

								</tr>

								<tr>

									<td>Drink(present)units per week</td>

												<td>

													<input type="radio" id="text1" name="male_drink_present"  <?php if(isset($patient_medical_info['male_drink_present']) && $patient_medical_info['male_drink_present'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_drink_present"  <?php if(isset($patient_medical_info['male_drink_present']) && $patient_medical_info['male_drink_present'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_drink_present_text'])?$patient_medical_info['male_drink_present_text']:""; ?>" type="text" maxlength="25" name="male_drink_present_text">

												</td>

								</tr>

								<tr>

									<td>Hashish/cocaine /abusive drugs</td>

												<td>

													<input type="radio" id="text1" name="male_abusive_drugs"  <?php if(isset($patient_medical_info['male_abusive_drugs']) && $patient_medical_info['male_abusive_drugs'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_abusive_drugs"  <?php if(isset($patient_medical_info['male_abusive_drugs']) && $patient_medical_info['male_abusive_drugs'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_abusive_drugs_text'])?$patient_medical_info['male_abusive_drugs_text']:""; ?>" type="text" maxlength="25" name="male_abusive_drugs_text">

												</td>

								</tr>

								<tr>

									<td>Have you ever used cortisone/steroid</td>

												<td>

													<input type="radio" id="text1" name="male_steroid"  <?php if(isset($patient_medical_info['male_steroid']) && $patient_medical_info['male_steroid'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_steroid"  <?php if(isset($patient_medical_info['male_steroid']) && $patient_medical_info['male_steroid'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_steroid_text'])?$patient_medical_info['male_steroid_text']:""; ?>" type="text" maxlength="25" name="male_steroid_text">

												</td>

								</tr>

								<tr>

									<td>Medication</td>

												<td>

													<input type="radio" id="text1" name="male_medication"  <?php if(isset($patient_medical_info['male_medication']) && $patient_medical_info['male_medication'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_medication"  <?php if(isset($patient_medical_info['male_medication']) && $patient_medical_info['male_medication'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_medication_text'])?$patient_medical_info['male_medication_text']:""; ?>" type="text" maxlength="25" name="male_medication_text">

												</td>

								</tr>

								<tr>

									<td>Herbal products</td>

												<td>

													<input type="radio" id="text1" name="male_herbal_products"  <?php if(isset($patient_medical_info['male_herbal_products']) && $patient_medical_info['male_herbal_products'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_herbal_products"  <?php if(isset($patient_medical_info['male_herbal_products']) && $patient_medical_info['male_herbal_products'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_herbal_products_text'])?$patient_medical_info['male_herbal_products_text']:""; ?>" type="text" maxlength="25" name="male_herbal_products_text">

												</td>

								</tr>

								<tr>

									<td>Eye drops</td>

												<td>

													<input type="radio" id="text1" name="male_eye_drops"  <?php if(isset($patient_medical_info['male_eye_drops']) && $patient_medical_info['male_eye_drops'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_eye_drops"  <?php if(isset($patient_medical_info['male_eye_drops']) && $patient_medical_info['male_eye_drops'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_eye_drops_text'])?$patient_medical_info['male_eye_drops_text']:"";  ?>" type="text" maxlength="25" name="male_eye_drops_text">

												</td>

								</tr>

								<tr>

									<td>Non prescription drugs used currently other than medications used for this IVF cycle</td>

												<td>

													<input type="radio" id="text1" name="male_non_prescription_drugs"  <?php if(isset($patient_medical_info['male_non_prescription_drugs']) && $patient_medical_info['male_non_prescription_drugs'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" id="text1" name="male_non_prescription_drugs"  <?php if(isset($patient_medical_info['male_non_prescription_drugs']) && $patient_medical_info['male_non_prescription_drugs'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_non_prescription_drugs_text'])?$patient_medical_info['male_non_prescription_drugs_text']:""; ?>" type="text" maxlength="25" name="male_non_prescription_drugs_text">

												</td>

								</tr>

							</table>

						</td>

					</tr>

					<tr>

						<th style="color: red;">FAMILY HISTORY</th>

						<td>

							<table width="100%">

								<tr>

									<td>Any family member any problem <br> with anesthesia</td>

									<td>

										<input type="radio" name="member_with_anesthesia"  <?php if(isset($patient_medical_info['member_with_anesthesia']) && $patient_medical_info['member_with_anesthesia'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" name="member_with_anesthesia"  <?php if(isset($patient_medical_info['member_with_anesthesia']) && $patient_medical_info['member_with_anesthesia'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['member_with_anesthesia_text'])?$patient_medical_info['member_with_anesthesia_text']:""; ?>" maxlength="25" name="member_with_anesthesia_text">

									</td>

								</tr>

							</table>

							<table>

											<tr>

												<td></td>

												<td>Maternal</td>

												<td>Paternal</td>

											</tr>

											<tr>

												<td>Diabetes</td>

												<td>

													<input type="radio" name="maternal_diabetes"  <?php if(isset($patient_medical_info['maternal_diabetes']) && $patient_medical_info['maternal_diabetes'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="maternal_diabetes"  <?php if(isset($patient_medical_info['maternal_diabetes']) && $patient_medical_info['maternal_diabetes'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['maternal_diabetes_text'])?$patient_medical_info['maternal_diabetes_text']:""; ?>" type="text" maxlength="25" name="maternal_diabetes_text">

												</td>

												<td>

													<input type="radio" name="paternal_diabetes"  <?php if(isset($patient_medical_info['paternal_diabetes']) && $patient_medical_info['paternal_diabetes'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="paternal_diabetes"  <?php if(isset($patient_medical_info['paternal_diabetes']) && $patient_medical_info['paternal_diabetes'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['paternal_diabetes_text'])?$patient_medical_info['paternal_diabetes_text']:"";?>" type="text" maxlength="25" name="paternal_diabetes_text">

												</td>

											</tr>

											<tr>

												<td>Heart/thrombo embolism</td>

												<td>

													<input type="radio" name="maternal_thrombo_embolism"  <?php if(isset($patient_medical_info['maternal_thrombo_embolism']) && $patient_medical_info['maternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="maternal_thrombo_embolism"  <?php if(isset($patient_medical_info['maternal_thrombo_embolism']) && $patient_medical_info['maternal_thrombo_embolism'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['maternal_thrombo_embolism_text'])?$patient_medical_info['maternal_thrombo_embolism_text']:""; ?>" type="text" maxlength="25" name="maternal_thrombo_embolism_text">

												</td>

												<td>

													<input type="radio" name="paternal_thrombo_embolism"  <?php if(isset($patient_medical_info['paternal_thrombo_embolism']) && $patient_medical_info['paternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="paternal_thrombo_embolism"  <?php if(isset($patient_medical_info['paternal_thrombo_embolism']) && $patient_medical_info['paternal_thrombo_embolism'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['paternal_thrombo_embolism_text'])?$patient_medical_info['paternal_thrombo_embolism_text']:""; ?>" type="text" maxlength="25" name="paternal_thrombo_embolism_text">

												</td>

											</tr>

											<tr>

												<td>Endocrine/metabolic</td>

												<td>

													<input type="radio" name="maternal_metabolic"  <?php if(isset($patient_medical_info['maternal_metabolic']) && $patient_medical_info['maternal_metabolic'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="maternal_metabolic"  <?php if(isset($patient_medical_info['maternal_metabolic']) && $patient_medical_info['maternal_metabolic'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['maternal_metabolic_text'])?$patient_medical_info['maternal_metabolic_text']:""; ?>" type="text" maxlength="25" name="maternal_metabolic_text">

												</td>

												<td>

													<input type="radio" name="paternal_metabolic"  <?php if(isset($patient_medical_info['paternal_metabolic']) && $patient_medical_info['paternal_metabolic'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="paternal_metabolic"  <?php if(isset($patient_medical_info['paternal_metabolic']) && $patient_medical_info['paternal_metabolic'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['paternal_metabolic_text'])?$patient_medical_info['paternal_metabolic_text']:""; ?>" type="text" maxlength="25" name="paternal_metabolic_text">

												</td>

											</tr>

											<tr>

												<td>Urinary tract/renal</td>

												<td>

													<input type="radio" name="maternal_urinary_tract"  <?php if(isset($patient_medical_info['maternal_urinary_tract']) && $patient_medical_info['maternal_urinary_tract'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="maternal_urinary_tract"  <?php if(isset($patient_medical_info['maternal_urinary_tract']) && $patient_medical_info['maternal_urinary_tract'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['maternal_urinary_tract_text'])?$patient_medical_info['maternal_urinary_tract_text']:""; ?>" type="text" maxlength="25" name="maternal_urinary_tract_text">

												</td>

												<td>

													<input type="radio" name="paternal_urinary_tract"  <?php if(isset($patient_medical_info['paternal_urinary_tract']) && $patient_medical_info['paternal_urinary_tract'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="paternal_urinary_tract"  <?php if(isset($patient_medical_info['paternal_urinary_tract']) && $patient_medical_info['paternal_urinary_tract'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['paternal_urinary_tract_text'])?$patient_medical_info['paternal_urinary_tract_text']:""; ?>" type="text" maxlength="25" name="paternal_urinary_tract_text">

												</td>

											</tr>

											<tr>

												<td>Psychiatric/neurological</td>

												<td>

													<input type="radio" name="maternal_neurological"  <?php if(isset($patient_medical_info['maternal_neurological']) && $patient_medical_info['maternal_neurological'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="maternal_neurological"  <?php if(isset($patient_medical_info['maternal_neurological']) && $patient_medical_info['maternal_neurological'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['maternal_neurological_text'])?$patient_medical_info['maternal_neurological_text']:""; ?>" type="text" maxlength="25" name="maternal_neurological_text">

												</td>

												<td>

													<input type="radio" name="paternal_neurological"  <?php if(isset($patient_medical_info['paternal_neurological']) && $patient_medical_info['paternal_neurological'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="paternal_neurological"  <?php if(isset($patient_medical_info['paternal_neurological']) && $patient_medical_info['paternal_neurological'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['paternal_neurological_text'])?$patient_medical_info['paternal_neurological_text']:""; ?>" type="text" maxlength="25" name="paternal_neurological_text">

												</td>

											</tr>

											<tr>

												<td>Other/malignancy</td>

												<td>

													<input type="radio" name="maternal_malignancy"  <?php if(isset($patient_medical_info['maternal_malignancy']) && $patient_medical_info['maternal_malignancy'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="maternal_malignancy"  <?php if(isset($patient_medical_info['maternal_malignancy']) && $patient_medical_info['maternal_malignancy'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['maternal_malignancy_text'])?$patient_medical_info['maternal_malignancy_text']:""; ?>" type="text" maxlength="25" name="maternal_malignancy_text">

												</td>

												<td>

													<input type="radio" name="paternal_malignancy"  <?php if(isset($patient_medical_info['paternal_malignancy']) && $patient_medical_info['paternal_malignancy'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="paternal_malignancy"  <?php if(isset($patient_medical_info['paternal_malignancy']) && $patient_medical_info['paternal_malignancy'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['paternal_malignancy_text'])?$patient_medical_info['paternal_malignancy_text']:""; ?>" type="text" maxlength="25" name="paternal_malignancy_text">

												</td>

											</tr>

										</table>

						</td>

						<td>

							<table width="100%">

								<tr>

									<td>Any family member any problem <br> with anesthesia</td>

									<td>

										<input type="radio" name="male_member_with_anesthesia"  <?php if(isset($patient_medical_info['male_member_with_anesthesia']) && $patient_medical_info['male_member_with_anesthesia'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label>Yes</label>

										<input type="radio" name="male_member_with_anesthesia"  <?php if(isset($patient_medical_info['male_member_with_anesthesia']) && $patient_medical_info['male_member_with_anesthesia'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label>No</label>

										<input type="text" value="<?php echo !empty($patient_medical_info['male_member_with_anesthesia_text'])?$patient_medical_info['male_member_with_anesthesia_text']:""; ?>" maxlength="25" name="male_member_with_anesthesia_text">

									</td>

								</tr>

							</table>

							<table>

											<tr>

												<td></td>

												<td>Maternal</td>

												<td>Paternal</td>

											</tr>

											<tr>

												<td>Diabetes</td>

												<td>

													<input type="radio" name="male_maternal_diabetes"  <?php if(isset($patient_medical_info['male_maternal_diabetes']) && $patient_medical_info['male_maternal_diabetes'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_maternal_diabetes"  <?php if(isset($patient_medical_info['male_maternal_diabetes']) && $patient_medical_info['male_maternal_diabetes'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_maternal_diabetes_text'])?$patient_medical_info['male_maternal_diabetes_text']:""; ?>" type="text" maxlength="25" name="male_maternal_diabetes_text">

												</td>

												<td>

													<input type="radio" name="male_paternal_diabetes"  <?php if(isset($patient_medical_info['male_paternal_diabetes']) && $patient_medical_info['male_paternal_diabetes'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_paternal_diabetes"  <?php if(isset($patient_medical_info['male_paternal_diabetes']) && $patient_medical_info['male_paternal_diabetes'] == "No"){echo 'checked="checked"';}?> value="No" checked  >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_paternal_diabetes_text'])?$patient_medical_info['male_paternal_diabetes_text']:""; ?>" type="text" maxlength="25" name="male_paternal_diabetes_text">

												</td>

											</tr>

											<tr>

												<td>Heart/thrombo embolism</td>

												<td>

													<input type="radio" name="male_maternal_thrombo_embolism"  <?php if(isset($patient_medical_info['male_maternal_thrombo_embolism']) && $patient_medical_info['male_maternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_maternal_thrombo_embolism"  <?php if(isset($patient_medical_info['male_maternal_thrombo_embolism']) && $patient_medical_info['male_maternal_thrombo_embolism'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_maternal_thrombo_embolism_text'])?$patient_medical_info['male_maternal_thrombo_embolism_text']:""; ?>" type="text" maxlength="25" name="male_maternal_thrombo_embolism_text">

												</td>

												<td>

													<input type="radio" name="male_paternal_thrombo_embolism"  <?php if(isset($patient_medical_info['male_paternal_thrombo_embolism']) && $patient_medical_info['male_paternal_thrombo_embolism'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_paternal_thrombo_embolism"  <?php if(isset($patient_medical_info['male_paternal_thrombo_embolism']) && $patient_medical_info['male_paternal_thrombo_embolism'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_paternal_thrombo_embolism_text'])?$patient_medical_info['male_paternal_thrombo_embolism_text']:""; ?>" type="text" maxlength="25" name="male_paternal_thrombo_embolism_text">

												</td>

											</tr>

											<tr>

												<td>Endocrine/metabolic</td>

												<td>

													<input type="radio" name="male_maternal_metabolic"  <?php if(isset($patient_medical_info['male_maternal_metabolic']) && $patient_medical_info['male_maternal_metabolic'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_maternal_metabolic"  <?php if(isset($patient_medical_info['male_maternal_metabolic']) && $patient_medical_info['male_maternal_metabolic'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_maternal_metabolic_text'])?$patient_medical_info['male_maternal_metabolic_text']:""; ?>" type="text" maxlength="25" name="male_maternal_metabolic_text">

												</td>

												<td>

													<input type="radio" name="male_paternal_metabolic"  <?php if(isset($patient_medical_info['male_paternal_metabolic']) && $patient_medical_info['male_paternal_metabolic'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_paternal_metabolic"  <?php if(isset($patient_medical_info['male_paternal_metabolic']) && $patient_medical_info['male_paternal_metabolic'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_paternal_metabolic_text'])?$patient_medical_info['male_paternal_metabolic_text']:""; ?>" type="text" maxlength="25" name="male_paternal_metabolic_text">

												</td>

											</tr>

											<tr>

												<td>Urinary tract/renal</td>

												<td>

													<input type="radio" name="male_maternal_urinary_tract"  <?php if(isset($patient_medical_info['male_maternal_urinary_tract']) && $patient_medical_info['male_maternal_urinary_tract'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_maternal_urinary_tract"  <?php if(isset($patient_medical_info['male_maternal_urinary_tract']) && $patient_medical_info['male_maternal_urinary_tract'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_maternal_urinary_tract_text'])?$patient_medical_info['male_maternal_urinary_tract_text']:""; ?>" type="text" maxlength="25" name="male_maternal_urinary_tract_text">

												</td>

												<td>

													<input type="radio" name="male_paternal_urinary_tract"  <?php if(isset($patient_medical_info['male_paternal_urinary_tract']) && $patient_medical_info['male_paternal_urinary_tract'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_paternal_urinary_tract"  <?php if(isset($patient_medical_info['male_paternal_urinary_tract']) && $patient_medical_info['male_paternal_urinary_tract'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_paternal_urinary_tract_text'])?$patient_medical_info['male_paternal_urinary_tract_text']:""; ?>" type="text" maxlength="25" name="male_paternal_urinary_tract_text">

												</td>

											</tr>

											<tr>

												<td>Psychiatric/neurological</td>

												<td>

													<input type="radio" name="male_maternal_neurological"  <?php if(isset($patient_medical_info['male_maternal_neurological']) && $patient_medical_info['male_maternal_neurological'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_maternal_neurological"  <?php if(isset($patient_medical_info['male_maternal_neurological']) && $patient_medical_info['male_maternal_neurological'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_maternal_neurological_text'])?$patient_medical_info['male_maternal_neurological_text']:""; ?>" type="text" maxlength="25" name="male_maternal_neurological_text">

												</td>

												<td>

													<input type="radio" name="male_paternal_neurological"  <?php if(isset($patient_medical_info['male_paternal_neurological']) && $patient_medical_info['male_paternal_neurological'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_paternal_neurological"  <?php if(isset($patient_medical_info['male_paternal_neurological']) && $patient_medical_info['male_paternal_neurological'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_paternal_neurological_text'])?$patient_medical_info['male_paternal_neurological_text']:""; ?>" type="text" maxlength="25" name="male_paternal_neurological_text">

												</td>

											</tr>

											<tr>

												<td>Other/malignancy</td>

												<td>

													<input type="radio" name="male_maternal_malignancy"  <?php if(isset($patient_medical_info['male_maternal_malignancy']) && $patient_medical_info['male_maternal_malignancy'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_maternal_malignancy"  <?php if(isset($patient_medical_info['male_maternal_malignancy']) && $patient_medical_info['male_maternal_malignancy'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_maternal_malignancy_text'])?$patient_medical_info['male_maternal_malignancy_text']:""; ?>" type="text" maxlength="25" name="male_maternal_malignancy_text">

												</td>

												<td>

													<input type="radio" name="male_paternal_malignancy"  <?php if(isset($patient_medical_info['male_paternal_malignancy']) && $patient_medical_info['male_paternal_malignancy'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

													<label>Yes</label>

													<input type="radio" name="male_paternal_malignancy"  <?php if(isset($patient_medical_info['male_paternal_malignancy']) && $patient_medical_info['male_paternal_malignancy'] == "No"){echo 'checked="checked"';}?> value="No" checked >

													<label>No</label>

													<input value="<?php echo !empty($patient_medical_info['male_paternal_malignancy_text'])?$patient_medical_info['male_paternal_malignancy_text']:""; ?>" type="text" maxlength="25" name="male_paternal_malignancy_text">

												</td>

											</tr>

										</table>

						</td>

					</tr>

					<tr>

						<th style="color: red;">PAST INVESTIGATIONS</th>

						<td>

							<p><b style="color: red;">SERUM AMH / SEMEN ANALYSIS</b></p>

							<table width="100%">

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_amh_dt_1'])?$patient_medical_info['female_amh_dt_1']:""; ?>" placeholder="yy-mm-dd" name="female_amh_dt_1" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_amh_dt_result_1'])?$patient_medical_info['female_amh_dt_result_1']:""; ?>" name="female_amh_dt_result_1" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" placeholder="yy-mm-dd" value="<?php echo !empty($patient_medical_info['female_amh_dt_2'])?$patient_medical_info['female_amh_dt_2']:""; ?>" name="female_amh_dt_2" class="form-control datepicker" required></td>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_amh_dt_result_2'])?$patient_medical_info['female_amh_dt_result_2']:""; ?>" name="female_amh_dt_result_2" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_amh_dt_3'])?$patient_medical_info['female_amh_dt_3']:""; ?>" placeholder="yy-mm-dd" name="female_amh_dt_3" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_amh_dt_result_3'])?$patient_medical_info['female_amh_dt_result_3']:""; ?>" name="female_amh_dt_result_3" min="0" class="form-control" required></td>

								</tr>

							</table>

							<br>

							<p><b style="color: red;">SERUM FSH</b></p>

							<table width="100%">

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_fsh_dt_1'])?$patient_medical_info['female_fsh_dt_1']:""; ?>" placeholder="yy-mm-dd" name="female_fsh_dt_1" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_fsh_dt_result_1'])?$patient_medical_info['female_fsh_dt_result_1']:""; ?>" name="female_fsh_dt_result_1" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_fsh_dt_2'])?$patient_medical_info['female_fsh_dt_2']:""; ?>" placeholder="yy-mm-dd" name="female_fsh_dt_2" class="form-control datepicker" required></td>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_fsh_dt_result_2'])?$patient_medical_info['female_fsh_dt_result_2']:""; ?>" name="female_fsh_dt_result_2" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_fsh_dt_3'])?$patient_medical_info['female_fsh_dt_3']:""; ?>" placeholder="yy-mm-dd" name="female_fsh_dt_3" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_fsh_dt_result_3'])?$patient_medical_info['female_fsh_dt_result_3']:""; ?>" name="female_fsh_dt_result_3" min="0" class="form-control" required></td>

								</tr>

							</table>

							<br>

							<p><b style="color: red;">HSG</b></p>

							<table width="100%">

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_hsg_dt_1'])?$patient_medical_info['female_hsg_dt_1']:""; ?>" placeholder="yy-mm-dd" name="female_hsg_dt_1" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_hsg_dt_result_1'])?$patient_medical_info['female_hsg_dt_result_1']:""; ?>" name="female_hsg_dt_result_1" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_hsg_dt_2'])?$patient_medical_info['female_hsg_dt_2']:""; ?>" placeholder="yy-mm-dd" name="female_hsg_dt_2" class="form-control datepicker" required></td>

								<tr>

									<td style="color: green;">RESULT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_hsg_dt_result_2'])?$patient_medical_info['female_hsg_dt_result_2']:""; ?>" name="female_hsg_dt_result_2" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_hsg_dt_3'])?$patient_medical_info['female_hsg_dt_3']:""; ?>" placeholder="yy-mm-dd" name="female_hsg_dt_3" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_hsg_dt_result_3'])?$patient_medical_info['female_hsg_dt_result_3']:""; ?>" name="female_hsg_dt_result_3" min="0" class="form-control" required></td>

								</tr>

							</table>

							<br>

							<p><b style="color: red;">USG OF PELVIS</b></p>

							<table width="100%">

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_pelvis_dt_1'])?$patient_medical_info['female_pelvis_dt_1']:""; ?>" placeholder="yy-mm-dd" name="female_pelvis_dt_1" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_pelvis_dt_result_1'])?$patient_medical_info['female_pelvis_dt_result_1']:""; ?>" name="female_pelvis_dt_result_1" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_pelvis_dt_2'])?$patient_medical_info['female_pelvis_dt_2']:""; ?>" placeholder="yy-mm-dd" name="female_pelvis_dt_2" class="form-control datepicker" required></td>

								<tr>

									<td style="color: green;">RESULT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_pelvis_dt_result_2'])?$patient_medical_info['female_pelvis_dt_result_2']:""; ?>" name="female_pelvis_dt_result_2" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_pelvis_dt_3'])?$patient_medical_info['female_pelvis_dt_3']:""; ?>" placeholder="yy-mm-dd" name="female_pelvis_dt_3" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_pelvis_dt_result_3'])?$patient_medical_info['female_pelvis_dt_result_3']:""; ?>" name="female_pelvis_dt_result_3" min="0" class="form-control" required></td>

								</tr>

							</table>

							<br>

							<table width="100%">

								<tr>

									<td style="color: red;">Others</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_past_investigation_others'])?$patient_medical_info['female_past_investigation_others']:""; ?>" name="female_past_investigation_others" class="form-control" required></td>

								</tr>

							</table>

						</td>

						<td>

							<p><b style="color: red;">SEMEN ANALYSIS / SERUM AMH</b></p>

							<table width="100%">

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_semen_dt_1'])?$patient_medical_info['male_semen_dt_1']:""; ?>" name="male_semen_dt_1" placeholder="yy-mm-dd" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_semen_dt_result_1'])?$patient_medical_info['male_semen_dt_result_1']:""; ?>" name="male_semen_dt_result_1" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_semen_dt_2'])?$patient_medical_info['male_semen_dt_2']:""; ?>" name="male_semen_dt_2" placeholder="yy-mm-dd" class="form-control datepicker" required></td>

								<tr>

									<td style="color: green;">RESULT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_semen_dt_result_2'])?$patient_medical_info['male_semen_dt_result_2']:""; ?>" name="male_semen_dt_result_2" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_semen_dt_3'])?$patient_medical_info['male_semen_dt_3']:""; ?>" name="male_semen_dt_3" placeholder="yy-mm-dd" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_semen_dt_result_3'])?$patient_medical_info['male_semen_dt_result_3']:""; ?>" name="male_semen_dt_result_3" min="0" class="form-control" required></td>

								</tr>

							</table>

							<br>

							<p><b style="color: red;">SERUM FSH</b></p>

							<table width="100%">

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_fsh_dt_1'])?$patient_medical_info['male_fsh_dt_1']:""; ?>"  name="male_fsh_dt_1" placeholder="yy-mm-dd" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_fsh_dt_1'])?$patient_medical_info['male_fsh_dt_1']:""; ?>"  name="male_fsh_dt_result_1" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_fsh_dt_2'])?$patient_medical_info['male_fsh_dt_2']:""; ?>" name="male_fsh_dt_2" placeholder="yy-mm-dd" class="form-control datepicker" required></td>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_fsh_dt_result_2'])?$patient_medical_info['male_fsh_dt_result_2']:""; ?>" name="male_fsh_dt_result_2" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_fsh_dt_3'])?$patient_medical_info['male_fsh_dt_3']:""; ?>" name="male_fsh_dt_3" placeholder="yy-mm-dd" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_fsh_dt_result_3'])?$patient_medical_info['male_fsh_dt_result_3']:""; ?>" name="male_fsh_dt_result_3" min="0" class="form-control" required></td>

								</tr>

							</table>

							<br>

							<p><b style="color: red;">SERUM TESTOSTERONE</b></p>

							<table width="100%">

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_testost_dt_1'])?$patient_medical_info['male_testost_dt_1']:""; ?>" name="male_testost_dt_1" placeholder="yy-mm-dd" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_testost_dt_result_1'])?$patient_medical_info['male_testost_dt_result_1']:""; ?>" name="male_testost_dt_result_1" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_testost_dt_2'])?$patient_medical_info['male_testost_dt_2']:""; ?>" name="male_testost_dt_2" placeholder="yy-mm-dd" class="form-control datepicker" required></td>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_testost_dt_result_2'])?$patient_medical_info['male_testost_dt_result_2']:""; ?>" name="male_testost_dt_result_2" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">DT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_testost_dt_3'])?$patient_medical_info['male_testost_dt_3']:""; ?>" name="male_testost_dt_3" placeholder="yy-mm-dd" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: green;">RESULT(Ng/ml)</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_testost_dt_result_3'])?$patient_medical_info['male_testost_dt_result_3']:""; ?>" name="male_testost_dt_result_3" min="0" class="form-control" required></td>

								</tr>

							</table>

							<br>

							<table width="100%">

								<tr>

									<td style="color: red;">Others</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['male_past_investigation_others'])?$patient_medical_info['male_past_investigation_others']:""; ?>" name="male_past_investigation_others" class="form-control" required></td>

								</tr>

							</table>

						</td>

					</tr>

					<tr>

						<th style="color: red;">PREVIOUS INFERTILITY TREATMENT DETAILS </th>

						<td>

							<table>

								<tr>

									<td style="color: red;">YEARS OF TAKING INFERTILITY TREATMENT</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_infertility_treatment_years'])?$patient_medical_info['female_infertility_treatment_years']:""; ?>" name="female_infertility_treatment_years" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">OVULATION INDUCTION DRUGS HOW MANY CYCLES</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_induction_drugs_cycles'])?$patient_medical_info['female_induction_drugs_cycles']:""; ?>" name="female_induction_drugs_cycles" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td>OVULATION INDUCTION INJECTION TAKEN HOW MANY CYCLES</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_induction_injection_cycles'])?$patient_medical_info['female_induction_injection_cycles']:""; ?>" name="female_induction_injection_cycles" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">TOTAL NO. OF IUI CYCLES</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_iui_cycles'])?$patient_medical_info['female_iui_cycles']:""; ?>" name="female_iui_cycles" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">TOTAL NO. OF IVF/ICSI CYCLES</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_ivf_icsi_cycles'])?$patient_medical_info['female_ivf_icsi_cycles']:""; ?>" name="female_ivf_icsi_cycles" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: green;">Total No. OF STIMULATED IVF CYCLES</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_stimulated_ivf_cycles'])?$patient_medical_info['female_stimulated_ivf_cycles']:""; ?>" name="female_stimulated_ivf_cycles" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: green;">Total No. cycles with no evidence of fertilization</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_no_evidence_fertilization'])?$patient_medical_info['female_no_evidence_fertilization']:""; ?>" name="female_no_evidence_fertilization" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td>NO. OF EGGS RETREIVED EACH CYCLE</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_egg_retreived'])?$patient_medical_info['female_egg_retreived']:""; ?>" name="female_egg_retreived" maxlength="100" class="form-control" required></td>

								</tr>

								<tr>

									<td>NO. OF FRESH CYCLE</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_fresh_cycle'])?$patient_medical_info['female_fresh_cycle']:""; ?>" name="female_fresh_cycle" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td>NO. OF FROZEN CYCLE</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_frozen_cycle'])?$patient_medical_info['female_frozen_cycle']:""; ?>" name="female_frozen_cycle" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td>NO. OF CYCLE OF DONOR EGG</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_donor_egg'])?$patient_medical_info['female_donor_egg']:""; ?>" name="female_donor_egg" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td>NO.OF CYCLE OF DONOR SPERM</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_donor_sperm'])?$patient_medical_info['female_donor_sperm']:""; ?>" name="female_donor_sperm" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td>NO.OF CYCLE OF SURROGACY</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['female_surrogacy'])?$patient_medical_info['female_surrogacy']:""; ?>" name="female_surrogacy" min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td>OTHERS</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_infertility_treatment_others'])?$patient_medical_info['female_infertility_treatment_others']:""; ?>" name="female_infertility_treatment_others" maxlength="50" class="form-control" required></td>

								</tr>

							</table>

						</td>

						<td>

							<table>

								<tr>

									<td>MEDICATIONS FOR SPERM</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['medication_for_sperm'])?$patient_medical_info['medication_for_sperm']:""; ?>" name="medication_for_sperm" maxlength="50" class="form-control" required></td>

								</tr>

								<tr>

									<td colspan="2" style="color: red;">FNAC TESTES DONE WITH REPORT</td>

								</tr>

								<tr>

									<td>NO. OF TIMES TESA DONE</td>

									<td><input type="number" value="<?php echo !empty($patient_medical_info['no_of_tesa'])?$patient_medical_info['no_of_tesa']:""; ?>" name="no_of_tesa"  min="0" class="form-control" required></td>

								</tr>

								<tr>

									<td>TESA REPORT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['tesa_report'])?$patient_medical_info['tesa_report']:""; ?>" name="tesa_report"  maxlength="100" class="form-control" required></td>

								</tr>

								<tr>

									<td>TESE REPORT</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['tese_report'])?$patient_medical_info['tese_report']:""; ?>" name="tese_report"  maxlength="100" class="form-control" required></td>

								</tr>

								<tr>

									<td>MICRO TESE</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['micro_tese'])?$patient_medical_info['micro_tese']:""; ?>" name="micro_tese"  maxlength="100" class="form-control" required></td>

								</tr>

								<tr>

									<td>OTHERS</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['other_s'])?$patient_medical_info['other_s']:""; ?>" name="other_s" maxlength="100" class="form-control" required></td>

								</tr>

							</table>

						</td>

					</tr>

					<tr>

						<th>OTHERS</th>

						<td><input type="text" value="<?php echo !empty($patient_medical_info['female_others_msg'])?$patient_medical_info['female_others_msg']:""; ?>" name="female_others_msg" maxlength="100" class="form-control" required></td>

						<td><input type="text" value="<?php echo !empty($patient_medical_info['male_others_msg'])?$patient_medical_info['male_others_msg']:""; ?>" name="male_others_msg" maxlength="100" class="form-control" required></td>

					</tr>

					<tr>

						<th style="color: red;">GENERAL EXAMINATION</th>

						<td>

							<table>

								<tr>

									<td style="color: red;">Nutritional assessment:</td>

									<td>

										<input type="text" value="<?php echo !empty($patient_medical_info['female_nutritional_assessment'])?$patient_medical_info['female_nutritional_assessment']:""; ?>" name="female_nutritional_assessment" class="form-control" required>

									</td>

								</tr>

								<tr>

									<td style="color: red;">Psychological assessment :-</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_psychological_assessment'])?$patient_medical_info['female_psychological_assessment']:""; ?>" name="female_psychological_assessment" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Anxious/combative/depressed/normal</td>

									<td>

										<input type="text" value="<?php echo !empty($patient_medical_info['female_mood_assessment'])?$patient_medical_info['female_mood_assessment']:""; ?>" name="female_mood_assessment" class="form-control" required>

									</td>

								</tr>

								<tr>

									<td style="color: red;">Pulse-</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_pulse'])?$patient_medical_info['female_pulse']:""; ?>" name="female_pulse" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Blood pressure-</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_blood_pressure'])?$patient_medical_info['female_blood_pressure']:""; ?>" name="female_blood_pressure" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Temperature</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_temperature'])?$patient_medical_info['female_temperature']:"";?>" name="female_temperature" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">CVS-</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_cvs'])?$patient_medical_info['female_cvs']:"";?>" name="female_cvs" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Chest -</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_chest'])?$patient_medical_info['female_chest']:""; ?>" name="female_chest" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Abdomen –</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_abdomen'])?$patient_medical_info['female_abdomen']:""; ?>" name="female_abdomen" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Others</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_general_exam_others'])?$patient_medical_info['female_general_exam_others']:""; ?>" name="female_general_exam_others" class="form-control" required></td>

								</tr>

							</table>

						</td>

						<td>

							<table>

								<tr>

									<td style="color: red;">Nutritional assessment :-Obese/average built/thin/cachexic</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['nutritional_assessment'])?$patient_medical_info['nutritional_assessment']:"";  ?>" name="nutritional_assessment" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Psychological assessment :-</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['psychological_assessment'])?$patient_medical_info['psychological_assessment']:""; ?>" name="psychological_assessment" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Anxious/combative/depressed/normal</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['anxious_assessment'])?$patient_medical_info['anxious_assessment']:""; ?>" name="anxious_assessment" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Pulse-</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['pulse_assessment'])?$patient_medical_info['pulse_assessment']:""; ?>" name="pulse_assessment" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Blood pressure-</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['bp_assessment'])?$patient_medical_info['bp_assessment']:""; ?>" name="bp_assessment" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Temperature</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['temp_assessment'])?$patient_medical_info['temp_assessment']:""; ?>" name="temp_assessment" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">CVS-</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['cvs_assessment'])?$patient_medical_info['cvs_assessment']:""; ?>" name="cvs_assessment" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Chest -</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['chest_assessment'])?$patient_medical_info['chest_assessment']:""; ?>" name="chest_assessment" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Abdomen –</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['abdomen_assessment'])?$patient_medical_info['abdomen_assessment']:""; ?>" name="abdomen_assessment" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">Others</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['assessment_others'])?$patient_medical_info['assessment_others']:""; ?>" name="assessment_others" class="form-control" required></td>

								</tr>

							</table>

						</td>

					</tr>

					<tr>

						<th style="color: red;">LOCAL EXAMINATION</th>

						<td>

							<table width="100%">

								<tr>

									<td style="color: red;">P/S</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_local_exam_ps'])?$patient_medical_info['female_local_exam_ps']:""; ?>" name="female_local_exam_ps" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">P/V</td>

									<td><input type="text" value="<?php echo !empty($patient_medical_info['female_local_exam_pv'])?$patient_medical_info['female_local_exam_pv']:""; ?>" name="female_local_exam_pv" class="form-control" required></td>

								</tr>

								<tr>

									<td style="color: red;">PAP SMEAR TAKEN</td>

									<td>

										<input type="radio" name="female_local_exam_pap"  <?php if(isset($patient_medical_info['female_local_exam_pap']) && $patient_medical_info['female_local_exam_pap'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="female_local_exam_pap"  <?php if(isset($patient_medical_info['female_local_exam_pap']) && $patient_medical_info['female_local_exam_pap'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label for="type2">No</label>
										
										<input value="<?php echo !empty($patient_medical_info['female_local_exam_pap_text'])?$patient_medical_info['female_local_exam_pap_text']:""; ?>" id="female_local_exam_pap_text" type="text" maxlength="250" name="female_local_exam_pap_text">

									</td>

								</tr>

								<tr>

									<td style="color: red;">HVS C&S TAKEN</td>

									<td>

										<input type="radio" name="female_hvs_taken"  <?php if(isset($patient_medical_info['female_hvs_taken']) && $patient_medical_info['female_hvs_taken'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="female_hvs_taken"  <?php if(isset($patient_medical_info['female_hvs_taken']) && $patient_medical_info['female_hvs_taken'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label for="type2">No</label>
										
										<input value="<?php echo !empty($patient_medical_info['female_hvs_taken_text'])?$patient_medical_info['female_hvs_taken_text']:""; ?>" type="text" maxlength="250" name="female_hvs_taken_text">

									</td>

								</tr>

								<tr>

									<td style="color: red;">ENDOMETRIAL BIOPSY HPE/TB QUANTIFERON</td>

									<td>

										<input type="radio" name="female_endometrial_biopsy"  <?php if(isset($patient_medical_info['female_endometrial_biopsy']) && $patient_medical_info['female_endometrial_biopsy'] == "Yes"){echo 'checked="checked"';}?> value="Yes"  >

										<label for="type2">Yes</label>

										<input type="radio" name="female_endometrial_biopsy"  <?php if(isset($patient_medical_info['female_endometrial_biopsy']) && $patient_medical_info['female_endometrial_biopsy'] == "No"){echo 'checked="checked"';}?> value="No" checked >

										<label for="type2">No</label>

                                        <input value="<?php echo !empty($patient_medical_info['female_endometrial_biopsy_text'])?$patient_medical_info['female_endometrial_biopsy_text']:""; ?>" type="text" maxlength="250" name="female_endometrial_biopsy_text">
									</td>

								</tr>

							</table>

						</td>

						<td>

							<span style="color: red;">UROSURGEON FINDINGS (ATTACH PRESCRIPTION)</span>

							<input type="text" value="<?php echo !empty($patient_medical_info['urosurgeon_findings'])?$patient_medical_info['urosurgeon_findings']:""; ?>" name="urosurgeon_findings" maxlength="100" class="form-control">

							<input type="file" name="prescription" class="form-control">

						</td>

					</tr>

					<tr>

						<th style="color: red;">Intervention</th>

						<td colspan="2">

							<?php //var_dump($patient_medical_info);die; 

								$management_intervention = array();

								if(!empty($patient_medical_info['management_intervention']) && isset($patient_medical_info['management_intervention'])){

									$management_intervention = unserialize($patient_medical_info['management_intervention']);

								}

							?>

							<input type="checkbox" name="management_intervention[]" <?php if(in_array('Natural', $management_intervention)){echo 'checked="checked"';}?> value="Natural"> NATURAL <br>

							<input type="checkbox" name="management_intervention[]" <?php if(in_array('Medical', $management_intervention)){echo 'checked="checked"';}?> value="Medical"> Medical <br>

							<input type="checkbox" name="management_intervention[]" <?php if(in_array('Surgical', $management_intervention)){echo 'checked="checked"';}?> value="Surgical"> Surgical <br>

							<input type="checkbox" name="management_intervention[]" <?php if(in_array('IUI', $management_intervention)){echo 'checked="checked"';}?> value="IUI"> IUI <br>

							<input type="checkbox" name="management_intervention[]" <?php if(in_array('ART', $management_intervention)){echo 'checked="checked"';}?> value="ART"> ART<br>

							<input type="checkbox" name="management_intervention[]" <?php if(in_array('Rejuvenation_techniques', $management_intervention)){echo 'checked="checked"';}?> value="Rejuvenation_techniques"> Rejuvenation techniques

						</td>

						<td></td>

					</tr>

					<?php $sub_procedure_suggestion_list = array(); if($appointments['partial_billing'] == 0){ ?>
						<tr>
							<th style="color: red;">MANAGEMENT ADVISED <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="procedure_suggestion" value="1" <?php if(isset($patient_doctor_consultation['procedure_suggestion']) && $patient_doctor_consultation['procedure_suggestion'] == "1"){echo 'checked="checked"';}?> name="procedure_suggestion" required /></th>
							<td colspan="2">
								<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['procedure_suggestion']) && $patient_doctor_consultation['procedure_suggestion'] == "1"){
										if(!empty($patient_doctor_consultation['sub_procedure_suggestion_list'])){
											$sub_procedure_suggestion_list = unserialize($patient_doctor_consultation['sub_procedure_suggestion_list']); 
										}
										$disabled = "";
										//var_dump($sub_procedure_suggestion_list);die;
								}?>
								<select class="form-control multidselect_dropdown_2"  multiple="multiple" id="sub_procedure_suggestion_list" name="sub_procedure_suggestion_list[]" <?php echo $disabled; ?> required>
									<?php if(!empty($procedures)) { foreach($procedures as $key => $val) { $selected=""; if(in_array($val['ID'], $sub_procedure_suggestion_list)){$selected= 'checked="checked"';} ?>
											<option value="<?php echo $val['ID']; ?>" <?php echo $selected; ?>><?php echo $val['procedure_name']." (".$val['code'].")"; ?></option>
									<?php  } } ?>
								</select>
							</td>
							<td></td>
						</tr>
						<script type='text/javascript'>
    						$('#sub_procedure_suggestion_list').val(<?php echo json_encode($sub_procedure_suggestion_list); ?>);
    					</script>
					<?php }else {?>
						<tr>
							<th style="color: red;">MANAGEMENT ADVISED</th>
							<td colspan="2">
								<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['procedure_suggestion']) && $patient_doctor_consultation['procedure_suggestion'] == "1"){
										if(!empty($patient_doctor_consultation['sub_procedure_suggestion_list'])){
											$sub_procedure_suggestion_list = unserialize($patient_doctor_consultation['sub_procedure_suggestion_list']); 
										}
										$disabled = "";
										//var_dump($sub_procedure_suggestion_list);die;
								}?>
									<?php if(!empty($procedures)) {
										$cs = 1;
										foreach($procedures as $key => $val) {
											if(in_array($val['ID'], $sub_procedure_suggestion_list)){ 
												echo $cs.'. '.$val['procedure_name']." (".$val['code'].")<br/>";
											$cs++; } ?>											
									<?php  } } ?>
							</td>
							<td></td>
						</tr>
					<?php } ?>
					
                    <?php $package_suggestion_list = array(); if($appointments['partial_billing'] == 0){ ?>
						<tr>
							<th style="color: red;">PACKAGE ADVISED <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="package_suggestion" value="1" <?php if(isset($patient_doctor_consultation['package_suggestion']) && $patient_doctor_consultation['package_suggestion'] == "1"){echo 'checked="checked"';}?> name="package_suggestion" required /></th>
							<td colspan="2">
								<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['package_suggestion']) && $patient_doctor_consultation['package_suggestion'] == "1"){
										if(!empty($patient_doctor_consultation['package_suggestion_list'])){
											$package_suggestion_list = unserialize($patient_doctor_consultation['package_suggestion_list']); 
										}
										$disabled = "";
										//var_dump($sub_procedure_suggestion_list);die;
								}?>
								<select class="form-control multidselect_dropdown_2"  multiple="multiple" id="package_suggestion_list" name="package_suggestion_list[]" <?php echo $disabled; ?> required>
									<?php
if (!isset($package_suggestion_list) || !is_array($package_suggestion_list)) {
    $package_suggestion_list = [];
}

if (!empty($package)) {
    foreach ($package as $key => $val) {
        $selected = "";
        if (in_array($val['ID'], $package_suggestion_list)) {
            $selected = 'checked="checked"';
        }
?>
        <option value="<?php echo $val['procedure_ids']; ?>" <?php echo $selected; ?>>
            <?php echo $val['package_name']; ?>
        </option>
<?php
    }
}
?>

								</select>
							</td>
							<td></td>
						</tr>
						<script type='text/javascript'>
    						$('#package_suggestion_list').val(<?php echo json_encode($package_suggestion_list); ?>);
    					</script>
					<?php }else {?>
						<tr>
							<th style="color: red;">PACKAGE ADVISED</th>
							<td colspan="2">
								<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['package_suggestion']) && $patient_doctor_consultation['package_suggestion'] == "1"){
										if(!empty($patient_doctor_consultation['package_suggestion_list'])){
											$package_suggestion_list = unserialize($patient_doctor_consultation['package_suggestion_list']); 
										}
										$disabled = "";
										//var_dump($sub_procedure_suggestion_list);die;
								}?>
									<?php if(!empty($package)) {
										$cs = 1;
										foreach($package as $key => $val) {
											if(in_array($val['procedure_ids'], $package_suggestion_list)){ 
												echo $cs.'. '.$val['package_name']." (".$val['code'].")<br/>";
											$cs++; } ?>											
									<?php  } } ?>
							</td>
							<td></td>
						</tr>
					<?php } ?>


					<tr>

						<th style="color: red;">DETAILS OF MANAGEMENT ADVISED</th>

						<td style="padding: 0;" colspan="2"><textarea maxlength="1000" class="form-control" name="details_management_advised" placeholder="DETAILS OF MANAGEMENT ADVISED" required><?php echo !empty($patient_medical_info['details_management_advised'])?$patient_medical_info['details_management_advised']:""; ?></textarea></td>

					</tr>

					<tr>

						<th style="color: red;">REASON FOR ADVISED MANAGEMENT</th>

						<td colspan="2">

							<table width="100%">

								<tr>

									<td style="color: red;">LOW OVARIAN RESERVE</td>

									<td style="color: red;">Evidence: <input type="text" value="<?php echo !empty($patient_medical_info['low_ovarian_reserve_evidence'])?$patient_medical_info['low_ovarian_reserve_evidence']:""; ?>" name="low_ovarian_reserve_evidence" maxlength="20" class="form-control" required></td>

									<td style="color: red;"><input type="text" value="<?php echo !empty($patient_medical_info['low_ovarian_reserve_evidence_date'])?$patient_medical_info['low_ovarian_reserve_evidence_date']:""; ?>" placeholder="yy-mm-dd" name="low_ovarian_reserve_evidence_date" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: red;">TUBAL FACTOR</td>

									<td style="color: red;">Evidence: <input type="text" value="<?php echo !empty($patient_medical_info['tubal_factor_evidence'])?$patient_medical_info['tubal_factor_evidence']:""; ?>" name="tubal_factor_evidence" maxlength="20" class="form-control" required></td>

									<td style="color: red;"><input type="text" value="<?php echo !empty($patient_medical_info['tubal_factor_evidence_date'])?$patient_medical_info['tubal_factor_evidence_date']:""; ?>" placeholder="yy-mm-dd" name="tubal_factor_evidence_date" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: red;">MALE FACTOR</td>

									<td style="color: red;">Evidence: <input type="text" value="<?php echo !empty($patient_medical_info['male_factor_evidence'])?$patient_medical_info['male_factor_evidence']:""; ?>" name="male_factor_evidence" maxlength="20" class="form-control" required></td>

									<td style="color: red;"><input type="text" value="<?php echo !empty($patient_medical_info['male_factor_evidence_date'])?$patient_medical_info['male_factor_evidence_date']:""; ?>" placeholder="yy-mm-dd" name="male_factor_evidence_date" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: red;">ENDOMETRIOSIS</td>

									<td style="color: red;">Evidence: <input type="text" value="<?php echo !empty($patient_medical_info['endometriosis_evidence'])?$patient_medical_info['endometriosis_evidence']:""; ?>" name="endometriosis_evidence" maxlength="20" class="form-control" required></td>

									<td style="color: red;"><input type="text" value="<?php echo !empty($patient_medical_info['endometriosis_evidence_date'])?$patient_medical_info['endometriosis_evidence_date']:""; ?>" placeholder="yy-mm-dd" name="endometriosis_evidence_date" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: red;">PCOS</td>

									<td style="color: red;">Evidence: <input type="text" value="<?php echo !empty($patient_medical_info['pcos_evidence'])?$patient_medical_info['pcos_evidence']:""; ?>" name="pcos_evidence" maxlength="20" class="form-control" required></td>

									<td style="color: red;"><input type="text" value="<?php echo !empty($patient_medical_info['pcos_evidence_date'])?$patient_medical_info['pcos_evidence_date']:""; ?>" placeholder="yy-mm-dd" name="pcos_evidence_date" class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: red;">UNEXPLAINED INFERTILITY</td>

									<td style="color: red;">Evidence: <input type="text" value="<?php echo !empty($patient_medical_info['unexplained_infertility_evidence'])?$patient_medical_info['unexplained_infertility_evidence']:""; ?>" name="unexplained_infertility_evidence" maxlength="20" class="form-control" required></td>

									<td style="color: red;"><input type="text" value="<?php echo !empty($patient_medical_info['unexplained_infertility_evidence_date'])?$patient_medical_info['unexplained_infertility_evidence_date']:""; ?>" placeholder="yy-mm-dd" name="unexplained_infertility_evidence_date"  class="form-control datepicker" required></td>

								</tr>

								<tr>

									<td style="color: red;">Others</td>

									<td style="color: red;">Evidence: <input type="text" value="<?php echo !empty($patient_medical_info['advised_reason_other_evidence'])?$patient_medical_info['advised_reason_other_evidence']:""; ?>" name="advised_reason_other_evidence" maxlength="20" class="form-control" required></td>

									<td style="color: red;"><input type="text" value="<?php echo !empty($patient_medical_info['advised_reason_other_evidence_date'])?$patient_medical_info['advised_reason_other_evidence_date']:""; ?>" placeholder="yy-mm-dd" name="advised_reason_other_evidence_date" class="form-control datepicker" required></td>

								</tr>

							</table>

						</td>

					</tr>
					
					<tr>
						<th style="color: red;">Provisional Diagnosis (ICD 10 CODES)  <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="provisional_diagnosis_suggestion" <?php if(isset($patient_doctor_consultation['provisional_diagnosis']) && $patient_doctor_consultation['provisional_diagnosis'] == "1"){echo 'checked="checked"';}?> value="1" name="provisional_diagnosis_suggestion" required /></th>
						<td>
						<div class="col-sm-12 col-xs-12 role">
							<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['provisional_diagnosis']) && $patient_doctor_consultation['provisional_diagnosis'] == "1"){
									$female_provisional_diagnosis_list = unserialize($patient_doctor_consultation['female_provisional_diagnosis_list']); 
									$disabled = "";
									//var_dump($sub_procedure_suggestion_list);die;
							}?>
							<select class="multidselect_dropdown_1" multiple id="female_provisional_diagnosis_list" <?php echo $disabled; ?> name="female_provisional_diagnosis_list[]"  >
								<?php if(!empty($consultation_provisional_diagnosis)) { foreach($consultation_provisional_diagnosis as $key => $val) { ?>
										<option value="<?php echo $val['code']; ?> - <?php echo $val['name']; ?>"><?php echo $val['code']; ?> - <?php echo $val['name']; ?></option>
								<?php  } } ?>
							</select>
							<script type='text/javascript'>
								$('#female_provisional_diagnosis_list').val(
									<?php echo isset($female_provisional_diagnosis_list) ? json_encode($female_provisional_diagnosis_list) : '""'; ?> 
								);
							</script>

						</div>
						
						</td>
						<td>
							<div class="col-sm-12 col-xs-12 role">
								<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['provisional_diagnosis']) && $patient_doctor_consultation['provisional_diagnosis'] == "1"){
										$male_provisional_diagnosis_list = unserialize($patient_doctor_consultation['male_provisional_diagnosis_list']); 
										$disabled = "";
										//var_dump($sub_procedure_suggestion_list);die;
								}?>
								<select class="multidselect_dropdown_1" multiple id="male_provisional_diagnosis_list" <?php echo $disabled; ?> name="male_provisional_diagnosis_list[]">
									<?php if(!empty($consultation_provisional_diagnosis)) { foreach($consultation_provisional_diagnosis as $key => $val) { ?>
											<option value="<?php echo $val['ID']; ?>"><?php echo $val['code']; ?> - <?php echo $val['name']; ?></option>
									<?php  } } ?>
								</select>
								<script type='text/javascript'>
									$('#male_provisional_diagnosis_list').val(
										<?php echo isset($male_provisional_diagnosis_list) ? json_encode($male_provisional_diagnosis_list) : '""'; ?>
									);
								</script>
							</div>
						</td>
					</tr>

					<!--<?php if($appointments['partial_billing'] == 0){ ?>
					<tr>
						<th style="color: red;">INVESTIGATIONS ADVISED  <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="investigation_suggestion" <?php if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == "1"){echo 'checked="checked"';}?> value="1" name="investigation_suggestion"   /></th>
						<td>
						<div class="col-sm-12 col-xs-12 role">
							<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == "1"){
									$female_investigation_suggestion_list = unserialize($patient_doctor_consultation['female_investigation_suggestion_list']); 
									$disabled = "";
									//var_dump($sub_procedure_suggestion_list);die;
							}?>
							<select class="multidselect_dropdown_1" multiple id="female_investigation_suggestion_list" <?php echo $disabled; ?> name="female_investigation_suggestion_list[]"  >
								<?php if(!empty($investigations)) { foreach($investigations as $key => $val) { ?>
										<option value="<?php echo $val['ID']; ?>"><?php echo $val['investigation']; ?></option>
								<?php  } } ?>
							</select>
							<script type='text/javascript'>
								$('#female_investigation_suggestion_list').val(<?php echo json_encode($female_investigation_suggestion_list); ?>);
							</script>
						</div>
						<!-- <script type='text/javascript'>
							$('#female_investigation_suggestion_list').val(<?php echo "";//json_encode($sub_procedure_suggestion_list); ?>);
						</script> -->
						<!--</td>
						<td>
							<div class="col-sm-12 col-xs-12 role">
								<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == "1"){
										$male_investigation_suggestion_list = unserialize($patient_doctor_consultation['male_investigation_suggestion_list']); 
										$disabled = "";
										//var_dump($sub_procedure_suggestion_list);die;
								}?>
								<select class="multidselect_dropdown_1" multiple id="male_investigation_suggestion_list" <?php echo $disabled; ?> name="male_investigation_suggestion_list[]">
									<?php if(!empty($investigations)) { foreach($investigations as $key => $val) { ?>
											<option value="<?php echo $val['ID']; ?>"><?php echo $val['investigation']; ?></option>
									<?php  } } ?>
								</select>
								<script type='text/javascript'>
								$('#male_investigation_suggestion_list').val(<?php echo json_encode($male_investigation_suggestion_list); ?>);
								</script>
							</div>
						</td>
					</tr>
					<?php } else { ?>
						<tr>
						<th style="color: red;">INVESTIGATIONS ADVISED</th>
						<td>
						<div class="col-sm-12 col-xs-12 role">
							<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == "1"){
									$female_investigation_suggestion_list = unserialize($patient_doctor_consultation['female_investigation_suggestion_list']); 
									$disabled = "";
									//var_dump($sub_procedure_suggestion_list);die;
							}?>
							
							<?php if(!empty($female_investigation_suggestion_list)) {
								$cis = 1;
									foreach($female_investigation_suggestion_list as $key => $val) { ?>
									<?php echo $cis.". ".get_investigation_name($val); ?><br/>
							<?php  $cis++; } } ?>
							
						</div>
						<!-- <script type='text/javascript'>
							$('#female_investigation_suggestion_list').val(<?php echo "";//json_encode($sub_procedure_suggestion_list); ?>);
						</script> -->
						<!--</td>
						<td>
							<div class="col-sm-12 col-xs-12 role">
								<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == "1"){
										$male_investigation_suggestion_list = unserialize($patient_doctor_consultation['male_investigation_suggestion_list']); 
										$disabled = "";
										//var_dump($sub_procedure_suggestion_list);die;
								}?>
								<?php if(!empty($male_investigation_suggestion_list)) {
									$cis = 1;
										foreach($male_investigation_suggestion_list as $key => $val) { ?>
										<?php echo $cis.". ".get_investigation_name($val); ?><br/>
								<?php  $cis++; } } ?>
							</div>
						</td>
					</tr>
					<?php } ?>-->
					
					<?php if($appointments['partial_billing'] == 0){ ?>
					<tr>
						<th style="color: red;">IIC INVESTIGATIONS ADVISED  <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="investigation_suggestion" <?php if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == "1"){echo 'checked="checked"';}?> value="1" name="investigation_suggestion"   /></th>
						<td>
						<div class="col-sm-12 col-xs-12 role">
							<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == "1"){
									$female_minvestigation_suggestion_list = unserialize($patient_doctor_consultation['female_minvestigation_suggestion_list']); 
									$disabled = "";
							}?>
							<select class="multidselect_dropdown_1" multiple id="female_minvestigation_suggestion_list" <?php echo $disabled; ?> name="female_minvestigation_suggestion_list[]"  >
								<?php if(!empty($master_investigations)) { foreach($master_investigations as $key => $val) { ?>
										<option value="<?php echo $val['master_id']; ?>"><?php echo $val['investigation_name']; ?></option>
								<?php  } } ?>
							</select>
							<script type='text/javascript'>
								$('#female_minvestigation_suggestion_list').val(
									<?php echo isset($female_minvestigation_suggestion_list) ? json_encode($female_minvestigation_suggestion_list) : '""'; ?>
								);
							</script>
						</div>
						</td>
						<td>
							<div class="col-sm-12 col-xs-12 role">
								<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == "1"){
										$male_minvestigation_suggestion_list = unserialize($patient_doctor_consultation['male_minvestigation_suggestion_list']); 
										$disabled = "";
								}?>
								<select class="multidselect_dropdown_1" multiple id="male_minvestigation_suggestion_list" <?php echo $disabled; ?> name="male_minvestigation_suggestion_list[]">
									<?php if(!empty($master_investigations)) { foreach($master_investigations as $key => $val) { ?>
											<option value="<?php echo $val['master_id']; ?>"><?php echo $val['investigation_name']; ?></option>
									<?php  } } ?>
								</select>
								<script type='text/javascript'>
									$('#male_minvestigation_suggestion_list').val(
										<?php echo isset($male_minvestigation_suggestion_list) ? json_encode($male_minvestigation_suggestion_list) : '""'; ?>
									);
								</script>
							</div>
						</td>
					</tr>
					<?php } else { ?>
						<tr>
						<th style="color: red;">INVESTIGATIONS ADVISED</th>
						<td>
						<div class="col-sm-12 col-xs-12 role">
							<?php $disabled = "disabled";  if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == "1"){
									$female_minvestigation_suggestion_list = unserialize($patient_doctor_consultation['female_minvestigation_suggestion_list']); 
									$disabled = "";
									//var_dump($sub_procedure_suggestion_list);die;
							}?>
							
							<?php if(!empty($female_minvestigation_suggestion_list)) {
								$cis = 1;
									foreach($female_minvestigation_suggestion_list as $key => $val) { ?>
									<?php echo $cis.". ".get_master_investigation_name($val); ?><br/>
							<?php  $cis++; } } ?>
							
						</div>
						</td>
						<td>
							<div class="col-sm-12 col-xs-12 role">
									<?php $male_minvestigation_suggestion_list=array();
									 if(isset($patient_doctor_consultation['investation_suggestion']) && $patient_doctor_consultation['investation_suggestion'] == "1"){
											$male_minvestigation_suggestion_list = unserialize($patient_doctor_consultation['male_minvestigation_suggestion_list']); 
									}
								?>
								<?php if(!empty($male_minvestigation_suggestion_list)) {
									$cis=1;
									 foreach($male_minvestigation_suggestion_list as $key => $val) { ?>
											<?php echo $cis.". ".get_master_investigation_name($val); ?><br/>
											
								<?php  $cis++; } } ?>
							</div>
						</td>
					</tr>
					<?php } ?>
					<?php if($appointments['partial_billing'] == 0){ ?>
						<tr>
							<th>MEDICINES ADVISED  <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="medicine_suggestion" value="1" <?php if(isset($patient_doctor_consultation['medicine_suggestion']) && $patient_doctor_consultation['medicine_suggestion'] == "1"){echo 'checked="checked"';}?> name="medicine_suggestion"   /></th>
							<td style="padding: 0;">
								<div class="col-sm-12 col-xs-12">
									<?php $disabled = "disabled"; $display="none";$female_medicine_suggestion_arr = array(); 
										if(isset($patient_doctor_consultation['medicine_suggestion']) && $patient_doctor_consultation['medicine_suggestion'] == "1"){
											$female_medicine_suggestion_list = unserialize($patient_doctor_consultation['female_medicine_suggestion_list']); 
											$disabled = "";
											$display="block";
											if(!empty($female_medicine_suggestion_list['female_medicine_suggestion_list']) && isset($female_medicine_suggestion_list['female_medicine_suggestion_list'])){
												foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $val){
													$female_medicine_suggestion_arr[] = $val['female_medicine_name'];
												}
											}
									}?>
									<select class="multidselect_dropdown" multiple id="female_medicine_suggestion_list" <?php echo $disabled; ?>  >
										<?php if(!empty($consultation_medicine)) { foreach($consultation_medicine as $key => $val) { ?>
												<option value="<?php echo $val['item_number']; ?>" medicine="<?php echo $val['item_name']; ?>"><?php echo $val['item_name']; ?></option>
										<?php  } } ?>
									</select>
									<hr/>
									<table id="female_medicine_table" style="width:100%; border:1px solid #000; display:<?php echo $display;?>;" border='1'>
											<thead>
												<tr>
													<th style="border:1px solid #000; padding:10px;">Medicine</th>
													<th style="border:1px solid #000; padding:10px;">Dosage</th>
													<th style="border:1px solid #000; padding:10px;">Start on</th>
													<th style="border:1px solid #000; padding:10px;">Days</th>
													<th style="border:1px solid #000; padding:10px;">Route</th>
													<th style="border:1px solid #000; padding:10px;">Frequency</th>
													<th style="border:1px solid #000; padding:10px;">Timing</th>
													<th style="border:1px solid #000; padding:10px;">Take</th>
												</tr>
												<tbody id="female_medicine_suggestion_table">
													<?php if(!empty($female_medicine_suggestion_arr )){
														$fmd_count = 1;
														foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $val){
														?>
														<tr style='border:1px solid #000;' count="<?php echo $fmd_count; ?>">
															<td style='border:1px solid #000;'><?php echo get_medicine_name($val['female_medicine_name']);?><input type='hidden'   readonly value='<?php echo $val['female_medicine_name']?>' style='margin:0;padding:0;' name='female_medicine_name_<?php echo $fmd_count; ?>' id='female_medicine_name_<?php echo $fmd_count; ?>'></td>
															<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['female_medicine_dosage']?>" style='margin:0;padding:0;' name='female_medicine_dosage_<?php echo $fmd_count; ?>'   id='female_medicine_dosage_<?php echo $fmd_count; ?>'></td>
															<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['female_medicine_when_start']?>" style='margin:0;padding:0;' name='female_medicine_when_start_<?php echo $fmd_count; ?>' id='female_medicine_when_start_<?php echo $fmd_count; ?>'  ></td>
															<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['female_medicine_days']?>" style='margin:0;padding:0;' name='female_medicine_days_<?php echo $fmd_count; ?>'   id='female_medicine_days_<?php echo $fmd_count; ?>'></td>
															<td style='border:1px solid #000;' class='role'>
																<select style='margin:0;padding:0;' name='female_medicine_route_<?php echo $fmd_count; ?>' id='female_medicine_route_<?php echo $fmd_count; ?>'  >
																	<option value='PO' <?php if($val['female_medicine_route'] == "PO"){echo 'selected="selected"';}?> >PO</option> 
																	<option value='IM' <?php if($val['female_medicine_route'] == "IM"){echo 'selected="selected"';}?>>IM</option>
																	<option value='SC' <?php if($val['female_medicine_route'] == "SC"){echo 'selected="selected"';}?>>SC</option>
																	<option value='VAGINA-LY' <?php if($val['female_medicine_route'] == "VAGINA-LY"){echo 'selected="selected"';}?>>VAGINA-LY</option>
																	<option value='IV' <?php if($val['female_medicine_route'] == "IV"){echo 'selected="selected"';}?>>IV</option>
																	<option value='LOCAL' <?php if($val['female_medicine_route'] == "LOCAL"){echo 'selected="selected"';}?>>LOCAL</option>
																	<option value='NASALY' <?php if($val['female_medicine_route'] == "NASALY"){echo 'selected="selected"';}?>>NASALY</option>
																</select>
															</td>
															<td style='border:1px solid #000;' class='role'>
																<select style='margin:0;padding:0;' name='female_medicine_frequency_<?php echo $fmd_count; ?>' id='female_medicine_frequency_<?php echo $fmd_count; ?>'  >
																	<option value='OD ( दिन में एक बार )' <?php if($val['female_medicine_frequency'] == "OD ( दिन में एक बार )"){echo 'selected="selected"';}?>>OD ( दिन में एक बार )</option> 
																	<option value='BD ( दिन में  दो बार )' <?php if($val['female_medicine_frequency'] == "BD ( दिन में  दो बार )"){echo 'selected="selected"';}?>>BD ( दिन में  दो बार )</option> 
																	<option value='TDS ( दिन में 3 बार)' <?php if($val['female_medicine_frequency'] == "TDS ( दिन में 3बार)"){echo 'selected="selected"';}?>>TDS ( दिन में 3बार)</option> 
																	<option value='QID ( दिन में 4 बार)' <?php if($val['female_medicine_frequency'] == "QID ( दिन में 4 बार)"){echo 'selected="selected"';}?>>QID ( दिन में 4 बार)</option> 
																	<option value='SOS ( लक्षण होने पर )' <?php if($val['female_medicine_frequency'] == "SOS ( लक्षण होने पर )"){echo 'selected="selected"';}?>>SOS ( लक्षण होने पर )</option>
																	<option value='HS ( रात में )' <?php if($val['female_medicine_frequency'] == "HS ( रात में )"){echo 'selected="selected"';}?>>HS ( रात में )</option>
																</select>
															</td>
															<td style='border:1px solid #000;' class='role'>
																<select style='margin:0;padding:0;' name='female_medicine_timing_<?php echo $fmd_count; ?>' id='female_medicine_timing_<?php echo $fmd_count; ?>'  > 
																	<option value='EMPTY STOMACH' <?php if($val['female_medicine_timing'] == "EMPTY STOMACH"){echo 'selected="selected"';}?>>EMPTY STOMACH</option>
																	<option value='BEFORE MEAL' <?php if($val['female_medicine_timing'] == "BEFORE MEAL"){echo 'selected="selected"';}?>>BEFORE MEAL</option> 
																	<option value='AFTER MEAL' <?php if($val['female_medicine_timing'] == "AFTER MEAL"){echo 'selected="selected"';}?>>AFTER MEAL</option>
																</select>
															</td>
															<td style='border:1px solid #000;' class='role'>
																<select style='margin:0;padding:0;' name='female_medicine_take_<?php echo $fmd_count; ?>' id='female_medicine_take_<?php echo $fmd_count; ?>'  >
                                                                    <option value='Daily' <?php if(isset($val['female_medicine_take']) && $val['female_medicine_take'] == "Daily"){echo 'selected="selected"';}?>>Daily</option>
                                                                    <option value='Biweekly' <?php if(isset($val['female_medicine_take']) && $val['female_medicine_take'] == "Biweekly"){echo 'selected="selected"';}?>>Biweekly</option>
                                                                    <option value='Weekly' <?php if(isset($val['female_medicine_take']) && $val['female_medicine_take'] == "Weekly"){echo 'selected="selected"';}?>>Weekly</option> 
                                                                    <option value='Blank' <?php if(isset($val['female_medicine_take']) && $val['female_medicine_take'] == "Blank"){echo 'selected="selected"';}?>>Blank</option>
                                                                    <option value='Alternate Day' <?php if(isset($val['female_medicine_take']) && $val['female_medicine_take'] == "Alternate Day"){echo 'selected="selected"';}?>>Alternate Day</option>
																</select>
															</td>
														</tr>
													<?php $fmd_count++; }} ?>
												</tbody>
											</thead>
									</table>
									<script type='text/javascript'>
										$('#female_medicine_suggestion_list').val(<?php echo json_encode($female_medicine_suggestion_arr); ?>);
									</script>
								</div>
							</td>
							<td style="padding: 0;">
								<div class="col-sm-12 col-xs-12">
									<?php $disabled = "disabled"; $display="none"; $male_medicine_suggestion_arr = array(); 
									if(isset($patient_doctor_consultation['medicine_suggestion']) && $patient_doctor_consultation['medicine_suggestion'] == "1"){
											$male_medicine_suggestion_list = unserialize($patient_doctor_consultation['male_medicine_suggestion_list']); 
											$disabled = "";
											$display="block";
											if(!empty($male_medicine_suggestion_list['male_medicine_suggestion_list']) && isset($male_medicine_suggestion_list['male_medicine_suggestion_list'])){
												foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $val){
													$male_medicine_suggestion_arr[] = $val['male_medicine_name'];
												}
											}
									}?>
									<select class="multidselect_dropdown" multiple id="male_medicine_suggestion_list" <?php echo $disabled; ?>>
										<?php if(!empty($consultation_medicine)) { foreach($consultation_medicine as $key => $val) { ?>
												<option value="<?php echo $val['item_number']; ?>" medicine="<?php echo $val['item_name']; ?>"><?php echo $val['item_name']; ?></option>
										<?php  } } ?>
									</select>
									<hr/>
									<table style="width:100%; border:1px solid #000; display:<?php echo $display;?>;" id="male_medicine_table" border='1'>
											<thead>
												<tr>
													<th style="border:1px solid #000; padding:10px;">Medicine</th>
													<th style="border:1px solid #000; padding:10px;">Dosage</th>
													<th style="border:1px solid #000; padding:10px;">Start on</th>
													<th style="border:1px solid #000; padding:10px;">Days</th>
													<th style="border:1px solid #000; padding:10px;">Route</th>
													<th style="border:1px solid #000; padding:10px;">Frequency</th>
													<th style="border:1px solid #000; padding:10px;">Timing</th>
													<th style="border:1px solid #000; padding:10px;">Take</th>
												</tr>
												<tbody id="male_medicine_suggestion_table">
													<?php if(!empty($male_medicine_suggestion_arr )){
														$mmd_count = 1;
														foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $val){
														?>
														<tr style='border:1px solid #000;' count="<?php echo $mmd_count; ?>">
															<td style='border:1px solid #000;'><?php echo get_medicine_name($val['male_medicine_name']);?><input type='hidden'   readonly value='<?php echo $val['male_medicine_name']?>' style='margin:0;padding:0;' name='male_medicine_name_<?php echo $mmd_count; ?>' id='male_medicine_name_<?php echo $mmd_count; ?>'></td>
															<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['male_medicine_dosage']?>" style='margin:0;padding:0;' name='male_medicine_dosage_<?php echo $mmd_count; ?>'   id='male_medicine_dosage_<?php echo $mmd_count; ?>'></td>
															<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['male_medicine_when_start']?>" style='margin:0;padding:0;' name='male_medicine_when_start_<?php echo $mmd_count; ?>' id='male_medicine_when_start_<?php echo $mmd_count; ?>'  ></td>
															<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['male_medicine_days']?>" style='margin:0;padding:0;' name='male_medicine_days_<?php echo $mmd_count; ?>'   id='male_medicine_days_<?php echo $mmd_count; ?>'></td>
															<td style='border:1px solid #000;' class='role'>
																<select style='margin:0;padding:0;' name='male_medicine_route_<?php echo $mmd_count; ?>' id='male_medicine_route_<?php echo $mmd_count; ?>'  >
																	<option value='PO' <?php if($val['male_medicine_route'] == "PO"){echo 'selected="selected"';}?> >PO</option> 
																	<option value='IM' <?php if($val['male_medicine_route'] == "IM"){echo 'selected="selected"';}?>>IM</option>
																	<option value='SC' <?php if($val['male_medicine_route'] == "SC"){echo 'selected="selected"';}?>>SC</option>
																	<option value='VAGINA-LY' <?php if($val['male_medicine_route'] == "VAGINA-LY"){echo 'selected="selected"';}?>>VAGINA-LY</option>
																	<option value='IV' <?php if($val['male_medicine_route'] == "IV"){echo 'selected="selected"';}?>>IV</option>
																	<option value='LOCAL' <?php if($val['male_medicine_route'] == "LOCAL"){echo 'selected="selected"';}?>>LOCAL</option>
																	<option value='NASALY' <?php if($val['male_medicine_route'] == "NASALY"){echo 'selected="selected"';}?>>NASALY</option>
																</select>
															</td>
															<td style='border:1px solid #000;' class='role'>
																<select style='margin:0;padding:0;' name='male_medicine_frequency_<?php echo $mmd_count; ?>' id='male_medicine_frequency_<?php echo $mmd_count; ?>'  >
																	<option value='OD (दिन में एक बार )' <?php if($val['female_medicine_frequency'] == "OD (दिन में एक बार )"){echo 'selected="selected"';}?>>OD (दिन में एक बार )</option> 
																	<option value='BD (दिन में  दो बार )' <?php if($val['female_medicine_frequency'] == "BD (दिन में  दो बार )"){echo 'selected="selected"';}?>>BD (दिन में  दो बार )</option> 
																	<option value='TDS (दिन में 3 बार)' <?php if($val['female_medicine_frequency'] == "TDS (दिन में 3बार)"){echo 'selected="selected"';}?>>TDS (दिन में 3बार)</option> 
																	<option value='QID (दिन में 4 बार)' <?php if($val['female_medicine_frequency'] == "QID (दिन में 4 बार)"){echo 'selected="selected"';}?>>QID (दिन में 4 बार)</option> 
																	<option value='SOS (लक्षण होने पर )' <?php if($val['female_medicine_frequency'] == "SOS (लक्षण होने पर )"){echo 'selected="selected"';}?>>SOS (लक्षण होने पर )</option>
																	<option value='HS (रात में )' <?php if($val['female_medicine_frequency'] == "HS (रात में )"){echo 'selected="selected"';}?>>HS (रात में )</option>
																</select>
															</td>
															<td style='border:1px solid #000;' class='role'>
																<select style='margin:0;padding:0;' name='male_medicine_timing_<?php echo $mmd_count; ?>' id='male_medicine_timing_<?php echo $mmd_count; ?>'  > 
																	<option value='EMPTY STOMACH' <?php if($val['male_medicine_timing'] == "EMPTY STOMACH"){echo 'selected="selected"';}?>>EMPTY STOMACH</option>
																	<option value='BEFORE MEAL' <?php if($val['male_medicine_timing'] == "BEFORE MEAL"){echo 'selected="selected"';}?>>BEFORE MEAL</option> 
																	<option value='AFTER MEAL' <?php if($val['male_medicine_timing'] == "AFTER MEAL"){echo 'selected="selected"';}?>>AFTER MEAL</option>
																</select>
															</td>
															<td style='border:1px solid #000;' class='role'>
																<select style='margin:0;padding:0;' name='male_medicine_take_<?php echo $fmd_count; ?>' id='male_medicine_take_<?php echo $fmd_count; ?>'  >
                                                                    <option value='Daily' <?php if(isset($val['male_medicine_take']) && $val['male_medicine_take'] == "Daily"){echo 'selected="selected"';}?>>Daily</option>
                                                                    <option value='Biweekly' <?php if(isset($val['male_medicine_take']) && $val['male_medicine_take'] == "Biweekly"){echo 'selected="selected"';}?>>Biweekly</option>
                                                                    <option value='Weekly' <?php if(isset($val['male_medicine_take']) && $val['male_medicine_take'] == "Weekly"){echo 'selected="selected"';}?>>Weekly</option> 
                                                                    <option value='Blank' <?php if(isset($val['male_medicine_take']) && $val['male_medicine_take'] == "Blank"){echo 'selected="selected"';}?>>Blank</option>
                                                                    <option value='Alternate Day' <?php if(isset($val['male_medicine_take']) && $val['male_medicine_take'] == "Alternate Day"){echo 'selected="selected"';}?>>Alternate Day</option>
																</select>
															</td>
														</tr>
													<?php $mmd_count++; }} ?>										
												</tbody>
											</thead>
									</table>
									<script type='text/javascript'>
										$('#male_medicine_suggestion_list').val(<?php echo json_encode($male_medicine_suggestion_arr); ?>);
									</script>
								</div>
							</td>
						</tr>
							<tr>
							<!-- start  medicine advised ipd  -->
								<!-- <th>MEDICINES ADVISED Ipd <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="medicine_suggestion_ipd" value="1" <?php if(isset($patient_doctor_consultation['medicine_suggestion_ipd']) && $patient_doctor_consultation['medicine_suggestion_ipd'] == "1"){echo 'checked="checked"';}?> name="medicine_suggestion_ipd"   /></th>
								<td style="padding: 0;">
									<div class="col-sm-12 col-xs-12">
										<?php $disabled = "disabled"; $display="none";$female_medicine_suggestion_arr = array(); 
											if(isset($patient_doctor_consultation['medicine_suggestion_ipd']) && $patient_doctor_consultation['medicine_suggestion_ipd'] == "1"){
												$female_medicine_suggestion_list = unserialize($patient_doctor_consultation['female_medicine_suggestion_list_ipd']); 
												$disabled = "";
												$display="block";
												if(!empty($female_medicine_suggestion_list['female_medicine_suggestion_list']) && isset($female_medicine_suggestion_list['female_medicine_suggestion_list'])){
													foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $val){
														$female_medicine_suggestion_arr[] = $val['female_medicine_name'];
													}
												}
										}?>
										<select class="multidselect_dropdown" multiple id="female_medicine_suggestion_list_ipd" <?php echo $disabled; ?>  >
											<?php if(!empty($consultation_medicine_ipd)) { foreach($consultation_medicine_ipd as $key => $val) { ?>
													<option value="<?php echo $val['item_number']; ?>" medicine="<?php echo $val['item_name']; ?>"><?php echo $val['item_name']; ?></option>
											<?php  } } ?>
										</select>
										<hr/>
										<table id="female_medicine_table_ipd" style="width:100%; border:1px solid #000; display:<?php echo $display;?>;" border='1'>
												<thead>
													<tr>
														<th style="border:1px solid #000; padding:10px;">Medicine</th>
														<th style="border:1px solid #000; padding:10px;">Dosage</th>
														<th style="border:1px solid #000; padding:10px;">Start on</th>
														<th style="border:1px solid #000; padding:10px;">Days</th>
														<th style="border:1px solid #000; padding:10px;">Route</th>
														<th style="border:1px solid #000; padding:10px;">Frequency</th>
														<th style="border:1px solid #000; padding:10px;">Timing</th>
														<th style="border:1px solid #000; padding:10px;">Take</th>
													</tr>
													<tbody id="female_medicine_suggestion_table_ipd">
														<?php if(!empty($female_medicine_suggestion_arr )){
															$fmd_count = 1;
															foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $val){
															?>
															<tr style='border:1px solid #000;' count="<?php echo $fmd_count; ?>">
																<td style='border:1px solid #000;'><?php echo get_medicine_name($val['female_medicine_name']);?><input type='hidden'   readonly value='<?php echo $val['female_medicine_name']?>' style='margin:0;padding:0;' name='female_medicine_name_ipd_<?php echo $fmd_count; ?>' id='female_medicine_name_ipd_<?php echo $fmd_count; ?>'></td>
																<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['female_medicine_dosage']?>" style='margin:0;padding:0;' name='female_medicine_dosage_ipd_<?php echo $fmd_count; ?>'   id='female_medicine_dosage_ipd_<?php echo $fmd_count; ?>'></td>
																<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['female_medicine_when_start']?>" style='margin:0;padding:0;' name='female_medicine_when_start_ipd_<?php echo $fmd_count; ?>' id='female_medicine_when_start_ipd_<?php echo $fmd_count; ?>'  ></td>
																<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['female_medicine_days']?>" style='margin:0;padding:0;' name='female_medicine_days_ipd_<?php echo $fmd_count; ?>'   id='female_medicine_days_ipd_<?php echo $fmd_count; ?>'></td>
																<td style='border:1px solid #000;' class='role'>
																	<select style='margin:0;padding:0;' name='female_medicine_route_ipd_<?php echo $fmd_count; ?>' id='female_medicine_route_ipd_<?php echo $fmd_count; ?>'  >
																		<option value='PO' <?php if($val['female_medicine_route'] == "PO"){echo 'selected="selected"';}?> >PO</option> 
																		<option value='IM' <?php if($val['female_medicine_route'] == "IM"){echo 'selected="selected"';}?>>IM</option>
																		<option value='SC' <?php if($val['female_medicine_route'] == "SC"){echo 'selected="selected"';}?>>SC</option>
																		<option value='VAGINA-LY' <?php if($val['female_medicine_route'] == "VAGINA-LY"){echo 'selected="selected"';}?>>VAGINA-LY</option>
																		<option value='IV' <?php if($val['female_medicine_route'] == "IV"){echo 'selected="selected"';}?>>IV</option>
																		<option value='LOCAL' <?php if($val['female_medicine_route'] == "LOCAL"){echo 'selected="selected"';}?>>LOCAL</option>
																		<option value='NASALY' <?php if($val['female_medicine_route'] == "NASALY"){echo 'selected="selected"';}?>>NASALY</option>
																	</select>
																</td>
																<td style='border:1px solid #000;' class='role'>
																	<select style='margin:0;padding:0;' name='female_medicine_frequency_ipd_<?php echo $fmd_count; ?>' id='female_medicine_frequency_ipd_<?php echo $fmd_count; ?>'  >
																		<option value='OD ( दिन में एक बार )' <?php if($val['female_medicine_frequency'] == "OD ( दिन में एक बार )"){echo 'selected="selected"';}?>>OD ( दिन में एक बार )</option> 
																		<option value='BD ( दिन में  दो बार )' <?php if($val['female_medicine_frequency'] == "BD ( दिन में  दो बार )"){echo 'selected="selected"';}?>>BD ( दिन में  दो बार )</option> 
																		<option value='TDS ( दिन में 3 बार)' <?php if($val['female_medicine_frequency'] == "TDS ( दिन में 3बार)"){echo 'selected="selected"';}?>>TDS ( दिन में 3बार)</option> 
																		<option value='QID ( दिन में 4 बार)' <?php if($val['female_medicine_frequency'] == "QID ( दिन में 4 बार)"){echo 'selected="selected"';}?>>QID ( दिन में 4 बार)</option> 
																		<option value='SOS ( लक्षण होने पर )' <?php if($val['female_medicine_frequency'] == "SOS ( लक्षण होने पर )"){echo 'selected="selected"';}?>>SOS ( लक्षण होने पर )</option>
																		<option value='HS ( रात में )' <?php if($val['female_medicine_frequency'] == "HS ( रात में )"){echo 'selected="selected"';}?>>HS ( रात में )</option>
																	</select>
																</td>
																<td style='border:1px solid #000;' class='role'>
																	<select style='margin:0;padding:0;' name='female_medicine_timing_ipd_<?php echo $fmd_count; ?>' id='female_medicine_timing_ipd_<?php echo $fmd_count; ?>'  > 
																		<option value='EMPTY STOMACH' <?php if($val['female_medicine_timing'] == "EMPTY STOMACH"){echo 'selected="selected"';}?>>EMPTY STOMACH</option>
																		<option value='BEFORE MEAL' <?php if($val['female_medicine_timing'] == "BEFORE MEAL"){echo 'selected="selected"';}?>>BEFORE MEAL</option> 
																		<option value='AFTER MEAL' <?php if($val['female_medicine_timing'] == "AFTER MEAL"){echo 'selected="selected"';}?>>AFTER MEAL</option>
																	</select>
																</td>
																<td style='border:1px solid #000;' class='role'>
																	<select style='margin:0;padding:0;' name='female_medicine_take_ipd_<?php echo $fmd_count; ?>' id='female_medicine_take_ipd_<?php echo $fmd_count; ?>'  >
																		<option value='Daily' <?php if(isset($val['female_medicine_take']) && $val['female_medicine_take'] == "Daily"){echo 'selected="selected"';}?>>Daily</option>
																		<option value='Biweekly' <?php if(isset($val['female_medicine_take']) && $val['female_medicine_take'] == "Biweekly"){echo 'selected="selected"';}?>>Biweekly</option>
																		<option value='Weekly' <?php if(isset($val['female_medicine_take']) && $val['female_medicine_take'] == "Weekly"){echo 'selected="selected"';}?>>Weekly</option> 
																		<option value='Blank' <?php if(isset($val['female_medicine_take']) && $val['female_medicine_take'] == "Blank"){echo 'selected="selected"';}?>>Blank</option>
																		<option value='Alternate Day' <?php if(isset($val['female_medicine_take']) && $val['female_medicine_take'] == "Alternate Day"){echo 'selected="selected"';}?>>Alternate Day</option>
																	</select>
																</td>
															</tr>
														<?php $fmd_count++; }} ?>
													</tbody>
												</thead>
										</table>
										<script type='text/javascript'>
											$('#female_medicine_suggestion_list_ipd').val(<?php echo json_encode($female_medicine_suggestion_arr); ?>);
										</script>
									</div>
								</td>
								<td style="padding: 0;">
									<div class="col-sm-12 col-xs-12">
										<?php $disabled = "disabled"; $display="none"; $male_medicine_suggestion_arr = array(); 
										if(isset($patient_doctor_consultation['medicine_suggestion_ipd']) && $patient_doctor_consultation['medicine_suggestion_ipd'] == "1"){
												$male_medicine_suggestion_list = unserialize($patient_doctor_consultation['male_medicine_suggestion_list_ipd']); 
												$disabled = "";
												$display="block";
												if(!empty($male_medicine_suggestion_list['male_medicine_suggestion_list']) && isset($male_medicine_suggestion_list['male_medicine_suggestion_list'])){
													foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $val){
														$male_medicine_suggestion_arr[] = $val['male_medicine_name'];
													}
												}
										}?>
										<select class="multidselect_dropdown" multiple id="male_medicine_suggestion_list_ipd" <?php echo $disabled; ?>>
											<?php if(!empty($consultation_medicine_ipd)) { foreach($consultation_medicine_ipd as $key => $val) { ?>
													<option value="<?php echo $val['item_number']; ?>" medicine="<?php echo $val['item_name']; ?>"><?php echo $val['item_name']; ?></option>
											<?php  } } ?>
										</select>
										<hr/>
										<table style="width:100%; border:1px solid #000; display:<?php echo $display;?>;" id="male_medicine_table_ipd" border='1'>
												<thead>
													<tr>
														<th style="border:1px solid #000; padding:10px;">Medicine</th>
														<th style="border:1px solid #000; padding:10px;">Dosage</th>
														<th style="border:1px solid #000; padding:10px;">Start on</th>
														<th style="border:1px solid #000; padding:10px;">Days</th>
														<th style="border:1px solid #000; padding:10px;">Route</th>
														<th style="border:1px solid #000; padding:10px;">Frequency</th>
														<th style="border:1px solid #000; padding:10px;">Timing</th>
														<th style="border:1px solid #000; padding:10px;">Take</th>
													</tr>
													<tbody id="male_medicine_suggestion_table_ipd">
														<?php if(!empty($male_medicine_suggestion_arr )){
															$mmd_count = 1;
															foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $val){
															?>
															<tr style='border:1px solid #000;' count="<?php echo $mmd_count; ?>">
																<td style='border:1px solid #000;'><?php echo get_medicine_name($val['male_medicine_name']);?><input type='hidden'   readonly value='<?php echo $val['male_medicine_name']?>' style='margin:0;padding:0;' name='male_medicine_name_ipd_<?php echo $mmd_count; ?>' id='male_medicine_name_ipd_<?php echo $mmd_count; ?>'></td>
																<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['male_medicine_dosage']?>" style='margin:0;padding:0;' name='male_medicine_dosage_ipd_<?php echo $mmd_count; ?>'   id='male_medicine_dosage_ipd_<?php echo $mmd_count; ?>'></td>
																<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['male_medicine_when_start']?>" style='margin:0;padding:0;' name='male_medicine_when_start_ipd_<?php echo $mmd_count; ?>' id='male_medicine_when_start_ipd_<?php echo $mmd_count; ?>'  ></td>
																<td style='border:1px solid #000;'><input type='number' value="<?php echo $val['male_medicine_days']?>" style='margin:0;padding:0;' name='male_medicine_days_ipd_<?php echo $mmd_count; ?>'   id='male_medicine_days_ipd_<?php echo $mmd_count; ?>'></td>
																<td style='border:1px solid #000;' class='role'>
																	<select style='margin:0;padding:0;' name='male_medicine_route_ipd_<?php echo $mmd_count; ?>' id='male_medicine_route_ipd_<?php echo $mmd_count; ?>'  >
																		<option value='PO' <?php if($val['male_medicine_route'] == "PO"){echo 'selected="selected"';}?> >PO</option> 
																		<option value='IM' <?php if($val['male_medicine_route'] == "IM"){echo 'selected="selected"';}?>>IM</option>
																		<option value='SC' <?php if($val['male_medicine_route'] == "SC"){echo 'selected="selected"';}?>>SC</option>
																		<option value='VAGINA-LY' <?php if($val['male_medicine_route'] == "VAGINA-LY"){echo 'selected="selected"';}?>>VAGINA-LY</option>
																		<option value='IV' <?php if($val['male_medicine_route'] == "IV"){echo 'selected="selected"';}?>>IV</option>
																		<option value='LOCAL' <?php if($val['male_medicine_route'] == "LOCAL"){echo 'selected="selected"';}?>>LOCAL</option>
																		<option value='NASALY' <?php if($val['male_medicine_route'] == "NASALY"){echo 'selected="selected"';}?>>NASALY</option>
																	</select>
																</td>
																<td style='border:1px solid #000;' class='role'>
																	<select style='margin:0;padding:0;' name='male_medicine_frequency_ipd_<?php echo $mmd_count; ?>' id='male_medicine_frequency_ipd_<?php echo $mmd_count; ?>'  >
																		<option value='OD (दिन में एक बार )' <?php if($val['male_medicine_frequency'] == "OD (दिन में एक बार )"){echo 'selected="selected"';}?>>OD (दिन में एक बार )</option> 
																		<option value='BD (दिन में  दो बार )' <?php if($val['male_medicine_frequency'] == "BD (दिन में  दो बार )"){echo 'selected="selected"';}?>>BD (दिन में  दो बार )</option> 
																		<option value='TDS (दिन में 3 बार)' <?php if($val['male_medicine_frequency'] == "TDS (दिन में 3बार)"){echo 'selected="selected"';}?>>TDS (दिन में 3बार)</option> 
																		<option value='QID (दिन में 4 बार)' <?php if($val['male_medicine_frequency'] == "QID (दिन में 4 बार)"){echo 'selected="selected"';}?>>QID (दिन में 4 बार)</option> 
																		<option value='SOS (लक्षण होने पर )' <?php if($val['male_medicine_frequency'] == "SOS (लक्षण होने पर )"){echo 'selected="selected"';}?>>SOS (लक्षण होने पर )</option>
																		<option value='HS (रात में )' <?php if($val['male_medicine_frequency'] == "HS (रात में )"){echo 'selected="selected"';}?>>HS (रात में )</option>
																	</select>
																</td>
																<td style='border:1px solid #000;' class='role'>
																	<select style='margin:0;padding:0;' name='male_medicine_timing_ipd_<?php echo $mmd_count; ?>' id='male_medicine_timing_ipd_<?php echo $mmd_count; ?>'  > 
																		<option value='EMPTY STOMACH' <?php if($val['male_medicine_timing'] == "EMPTY STOMACH"){echo 'selected="selected"';}?>>EMPTY STOMACH</option>
																		<option value='BEFORE MEAL' <?php if($val['male_medicine_timing'] == "BEFORE MEAL"){echo 'selected="selected"';}?>>BEFORE MEAL</option> 
																		<option value='AFTER MEAL' <?php if($val['male_medicine_timing'] == "AFTER MEAL"){echo 'selected="selected"';}?>>AFTER MEAL</option>
																	</select>
																</td>
																<td style='border:1px solid #000;' class='role'>
																	<select style='margin:0;padding:0;' name='male_medicine_take_ipd_<?php echo $mmd_count; ?>' id='male_medicine_take_ipd_<?php echo $mmd_count; ?>'  >
																		<option value='Daily' <?php if(isset($val['male_medicine_take']) && $val['male_medicine_take'] == "Daily"){echo 'selected="selected"';}?>>Daily</option>
																		<option value='Biweekly' <?php if(isset($val['male_medicine_take']) && $val['male_medicine_take'] == "Biweekly"){echo 'selected="selected"';}?>>Biweekly</option>
																		<option value='Weekly' <?php if(isset($val['male_medicine_take']) && $val['male_medicine_take'] == "Weekly"){echo 'selected="selected"';}?>>Weekly</option> 
																		<option value='Blank' <?php if(isset($val['male_medicine_take']) && $val['male_medicine_take'] == "Blank"){echo 'selected="selected"';}?>>Blank</option>
																		<option value='Alternate Day' <?php if(isset($val['male_medicine_take']) && $val['male_medicine_take'] == "Alternate Day"){echo 'selected="selected"';}?>>Alternate Day</option>
																	</select>
																</td>
															</tr>
														<?php $mmd_count++; }} ?>										
													</tbody>
												</thead>
										</table>
										<script type='text/javascript'>
											$('#male_medicine_suggestion_list_ipd').val(<?php echo json_encode($male_medicine_suggestion_arr); ?>);
										</script>
									</div>
								</td> -->
							</tr>
							<!-- end medicine advised ipd -->
					<?php }else{ ?>

						<tr>
                            <th>MEDICINES ADVISED </th>
                            <td style="padding: 0;">
                                <div class="col-sm-12 col-xs-12">
                                    <?php $disabled = "disabled"; $display="none";$female_medicine_suggestion_arr = array(); 
                                            if(isset($patient_doctor_consultation['medicine_suggestion']) && $patient_doctor_consultation['medicine_suggestion'] == "1"){
                                            $female_medicine_suggestion_list = unserialize($patient_doctor_consultation['female_medicine_suggestion_list']); 
                                            $disabled = "";
                                            $display="block";
                                            if(!empty($female_medicine_suggestion_list['female_medicine_suggestion_list']) && isset($female_medicine_suggestion_list['female_medicine_suggestion_list'])){
                                                foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $val){
                                                    $female_medicine_suggestion_arr[] = $val['female_medicine_name'];
                                                }
                                            }
                                    }?>
                                    <table id="female_medicine_table" style="width:100%; border:1px solid #000; display:<?php echo $display;?>;" border='1'>
                                            <thead>
                                                <tr>
                                                    <th style="border:1px solid #000; padding:10px;">Medicine</th>
                                                    <th style="border:1px solid #000; padding:10px;">Dosage</th>
                                                    <th style="border:1px solid #000; padding:10px;">Start on</th>
                                                    <th style="border:1px solid #000; padding:10px;">Days</th>
                                                    <th style="border:1px solid #000; padding:10px;">Route</th>
                                                    <th style="border:1px solid #000; padding:10px;">Frequency</th>
                                                    <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                    <th style="border:1px solid #000; padding:10px;">Take</th>
                                                </tr>
                                                <tbody id="female_medicine_suggestion_table">
                                                    <?php if(!empty($female_medicine_suggestion_arr )){
                                                        $fmd_count = 1;
                                                        foreach($female_medicine_suggestion_list['female_medicine_suggestion_list'] as $key => $val){
                                                            ?>
                                                        <tr style='border:1px solid #000;' count="<?php echo $fmd_count; ?>">
                                                            <td style='border:1px solid #000;'><?php echo get_medicine_name($val['female_medicine_name']);?></td>
                                                            <td style='border:1px solid #000;'><?php echo $val['female_medicine_dosage']?></td>
                                                            <td style='border:1px solid #000;'><?php echo $val['female_medicine_when_start']?></td>
                                                            <td style='border:1px solid #000;'><?php echo $val['female_medicine_days']?></td>
                                                            <td style='border:1px solid #000;' class='role'>
                                                                <?php echo $val['female_medicine_route']; ?>
                                                            </td>
                                                            <td style='border:1px solid #000;' class='role'>
                                                                <?php echo $val['female_medicine_frequency']; ?>
                                                            </td>
                                                            <td style='border:1px solid #000;' class='role'>
                                                                <?php echo $val['female_medicine_timing']; ?>
                                                            </td>
                                                            <td style='border:1px solid #000;' class='role'>
                                                                <?php echo $val['female_medicine_take']; ?>
                                                            </td>
                                                        </tr>
                                                    <?php $fmd_count++; }} ?>
                                                </tbody>
                                            </thead>
                                    </table>
                                </div>
                            </td>
                            <td style="padding: 0;">
                                <div class="col-sm-12 col-xs-12">
                                    <?php $disabled = "disabled"; $display="none"; $male_medicine_suggestion_arr = array(); 
                                        if(isset($patient_doctor_consultation['medicine_suggestion']) && $patient_doctor_consultation['medicine_suggestion'] == "1"){
                                            $male_medicine_suggestion_list = unserialize($patient_doctor_consultation['male_medicine_suggestion_list']); 
                                            $disabled = "";
                                            $display="block";
                                            if(!empty($male_medicine_suggestion_list['male_medicine_suggestion_list']) && isset($male_medicine_suggestion_list['male_medicine_suggestion_list'])){
                                                foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $val){
                                                    $male_medicine_suggestion_arr[] = $val['male_medicine_name'];
                                                }
                                            }
                                    }?>
                                    <table style="width:100%; border:1px solid #000; display:<?php echo $display;?>;" id="male_medicine_table" border='1'>
                                            <thead>
                                                <tr>
                                                    <th style="border:1px solid #000; padding:10px;">Medicine</th>
                                                    <th style="border:1px solid #000; padding:10px;">Dosage</th>
                                                    <th style="border:1px solid #000; padding:10px;">Start on</th>
                                                    <th style="border:1px solid #000; padding:10px;">Days</th>
                                                    <th style="border:1px solid #000; padding:10px;">Route</th>
                                                    <th style="border:1px solid #000; padding:10px;">Frequency</th>
                                                    <th style="border:1px solid #000; padding:10px;">Timing</th>
                                                    <th style="border:1px solid #000; padding:10px;">Take</th>
                                                </tr>
                                                <tbody id="male_medicine_suggestion_table">
                                                    <?php if(!empty($male_medicine_suggestion_arr )){
                                                        $mmd_count = 1;
                                                        foreach($male_medicine_suggestion_list['male_medicine_suggestion_list'] as $key => $val){
                                                            //var_dump($val);die;?>
                                                            <tr style='border:1px solid #000;' count="<?php echo $mmd_count; ?>">
                                                                <td style='border:1px solid #000;'><?php echo get_medicine_name($val['male_medicine_name']);?></td>
                                                                <td style='border:1px solid #000;'><?php echo $val['male_medicine_dosage']?></td>
                                                                <td style='border:1px solid #000;'><?php echo $val['male_medicine_when_start']?></td>
                                                                <td style='border:1px solid #000;'><?php echo $val['male_medicine_days']?></td>
                                                                <td style='border:1px solid #000;' class='role'>
                                                                    <?php echo $val['male_medicine_route']; ?>
                                                                </td>
                                                                <td style='border:1px solid #000;' class='role'>
                                                                    <?php echo $val['male_medicine_frequency']; ?>
                                                                </td>
                                                                <td style='border:1px solid #000;' class='role'>
                                                                    <?php echo $val['male_medicine_timing']; ?>
                                                                </td>
                                                                <td style='border:1px solid #000;' class='role'>
                                                                    <?php echo $val['male_medicine_take']; ?>
                                                                </td>
                                                            </tr>
                                                    <?php $mmd_count++; }} ?>										
                                                </tbody>
                                            </thead>
                                    </table>
                                </div>
                            </td>
                        </tr>
					<?php } ?>
					<tr>
						<th>NEXT FOLLOW UP <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="hidden" id="follow_up" checked value="1" name="follow_up" /></th>
						<td colspan="2">
							<div class="row">            
								<div class="form-group col-sm-6 col-xs-12 role">
									<label for="statuss">Centre (Required)</label>
									<select name="appoitment_for"   class="empty-field" id="appoitment_for">
										<option value="<?php echo $center['center_number']; ?>"><?php echo $center['center_name']; ?></option>
										<?php $center = $all_method->get_center_list(); foreach($center as $key => $center){?>
										<option value="<?php echo $center['center_number']; ?>"><?php echo $center['center_name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
								<div class="row appoitmented_doctor" style="display:none;">            
									<div class="form-group col-sm-6 col-xs-12 role">
										<label for="statuss">Doctor (Required)</label>
										<select name="appoitmented_doctor" disabled class="empty-field" id="appoitmented_doctor">
											<option value="">Select</option>
										</select>
									</div>
								</div>
								<div class="row appoitmented_date" style="display:none;">            
									<div class="form-group col-sm-6 col-xs-12 role">
										<label for="statuss">Appointment date (Required)</label>
										<input value="" id="appoitmented_date" disabled autocomplete="off" name="follow_up_date" type="text" class="form-control empty-field validate" >
									</div>
								</div>
								

								<div class="row appoitmented_slot" style="display:none;">            

									<div class="form-group col-sm-6 col-xs-12 role">

										<label for="statuss">Appoitmented_slot (Required)</label>

										<select name="appoitmented_slot" disabled class="empty-field" id="appoitmented_slot">

											<option value="">Select</option>

										</select>

									</div>

								</div>

								</div>

							</div>
						</td>
					</tr><br>
					<tr>
						<th>PURPOSE OF NEXT FOLLOWUP</th>
						<td colspan="2">
							<input type="radio" name="follow_up_purpose" value="TVS">
							<label>TVS</label>
							<input type="radio" name="follow_up_purpose" checked value="FOLLOW UP VISIT">
							<label>FOLLOW UP VISIT</label>
							<input type="radio" name="follow_up_purpose" value="PROCEDURE">
							<label>PROCEDURE</label><br>
						</td>
                     <input type="hidden" id="time_remaining" name="time_remaining" readonly>
					</tr>
            </thead>
              </table>
			<div class="card-footer">

				<button type="button" id="save_exit-button" class="btn btn-primary">Save & Exit</button>

				<?php if($_SESSION['logged_doctor']['junior_doctor'] == 0){ ?>

					<button type="button" id="exit-button" class="btn btn-primary pull-right">Submit</button>

				<?php } ?>

			</div>

		</div>

		<!-- /.card -->

		</div>

	</div>

	</div>

</form>

<script>
        // Set the countdown duration from PHP
        var countdownDuration = <?php echo $countdownDuration; ?>;
        var countdownForm = document.getElementById('countdownForm');
        var timerInput = document.getElementById('time_remaining');

        function startCountdown() {
            var remainingTime = countdownDuration;

            function updateTimer() {
                var minutes = Math.floor(remainingTime / 60);
                var seconds = remainingTime % 60;

                // Format the time to display
                minutes = (minutes < 10) ? "0" + minutes : minutes;
                seconds = (seconds < 10) ? "0" + seconds : seconds;

                timerInput.value = minutes + ":" + seconds;

                if (remainingTime > 0) {
                    remainingTime--;
                } else {
                    // Timer reached zero, you can auto-submit the form or notify the user
                    clearInterval(timerInterval);
                    alert("Time's up! Submitting the form...");
                    countdownForm.submit();
                }
            }

            // Update the timer every second
            var timerInterval = setInterval(updateTimer, 1000);
            updateTimer(); // Initial call to display timer immediately
        }

        // Start the countdown when the page loads
        window.onload = startCountdown;
    </script>

<script>
function submitForm() {
  return confirm('Do you really want to submit the form?');
}


$("#save_exit-button").click(function(){

    event.preventDefault();



	//validate fields

	var fail = false;

	var fail_log = '';

	var name;

	$( 'form#consultation_done_form' ).find( 'select, textarea, input' ).each(function(){

        if( ! $( this ).prop( ' ' )){



        } else {

            if ( ! $( this ).val() ) {

                fail = true;

                name = $( this ).attr( 'name' );

                fail_log += name + " is   \n";

            }

        }

    });



    //submit if fail never got set to true

    if ( ! fail ) {
        if(confirm("Are you sure you want to save this?")){
			$('#submit_type').val('save_exit');
			$("form#consultation_done_form").submit();

		}else{
			return false;
		}
    } else {
        alert( fail_log );
    }

});



$("#exit-button").click(function(){

    event.preventDefault();
	var fail = false;
	var fail_log = '';
	var name;
	$( 'form#consultation_done_form' ).find( 'select, textarea, input' ).each(function(){
        if( ! $( this ).prop( ' ' )){
        } else {
            if ( ! $( this ).val() ) {

                fail = true;

                name = $( this ).attr( 'name' );

                fail_log += name + " is   \n";

            }

        }

    });



    //submit if fail never got set to true

    if ( ! fail ) {

        if(confirm("Are you sure you want to submit this?")){

			$('#submit_type').val('exit');

			$("form#consultation_done_form").submit();

		}else{

			return false;

		}

    } else {

        alert( fail_log );

    }

});



$(function() {

$('#male_medicine_suggestion_list').change(function(e) {
	$("table#male_medicine_table").hide();
	var brands = $('#male_medicine_suggestion_list option:selected');
	var selected = "";
	var countr=1;
	$("tbody#male_medicine_suggestion_table").empty();
	$(brands).each(function(index, brand){
		$("tbody#male_medicine_suggestion_table").append("<tr style='border:1px solid #000;' count='"+countr+"'><td style='border:1px solid #000;'>"+$(this).attr('medicine')+"<input type='hidden'   readonly value='"+$(this).val()+"' style='margin:0;padding:0;' name='male_medicine_name_"+countr+"' id='male_medicine_name_"+countr+"'></td><td style='border:1px solid #000;'><input type='text' style='margin:0;padding:0;' name='male_medicine_dosage_"+countr+"'   id='male_medicine_dosage_"+countr+"'></td><td style='border:1px solid #000;'><input  type='text' placeholder='DD-MM-YYYY' style='margin:0;padding:0;' name='male_medicine_when_start_"+countr+"' id='male_medicine_when_start_"+countr+"'  ></td><td style='border:1px solid #000;'><input type='number' style='margin:0;padding:0;' name='male_medicine_days_"+countr+"'   id='male_medicine_days_"+countr+"'></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_route_"+countr+"' id='male_medicine_route_"+countr+"'  > <option value='PO'>PO</option> <option value='IM'>IM</option> <option value='SC'>SC</option> <option value='VAGINA-LY'>VAGINA-LY</option> <option value='IV'>IV</option> <option value='LOCAL'>LOCAL</option> <option value='NASALY'>NASALY</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_frequency_"+countr+"' id='male_medicine_frequency_"+countr+"'  > <option value='OD'>OD</option> <option value='BD'>BD</option> <option value='TDS'>TDS</option> <option value='QID'>QID</option> <option value='SOS'>SOS</option> <option value='HS'>HS</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_timing_"+countr+"' id='male_medicine_timing_"+countr+"'  > <option value='EMPTY STOMACH'>EMPTY STOMACH</option> <option value='BEFORE MEAL'>BEFORE MEAL</option> <option value='AFTER MEAL'>AFTER MEAL</option><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_take_"+countr+"' id='male_medicine_take_"+countr+"'  > <option value='Daily'>Daily</option> <option value='Biweekly'>Biweekly</option> <option value='Weekly'>Weekly</option> <option value='Blank'>Blank</option> <option value='Alternate Day'>Alternate Day</option></select></td></select></td></tr>");
		countr++;
	});
	$("table#male_medicine_table").show();
}); 

$('#female_medicine_suggestion_list').change(function(e) {
	$("table#female_medicine_table").hide();
	var brands = $('#female_medicine_suggestion_list option:selected');
	var selected = "";
	var countr=1;
	$("tbody#female_medicine_suggestion_table").empty();
	$(brands).each(function(index, brand){
		$("tbody#female_medicine_suggestion_table").append("<tr style='border:1px solid #000;' count='"+countr+"'><td style='border:1px solid #000;'>"+$(this).attr('medicine')+"<input type='hidden'   readonly value='"+$(this).val()+"' style='margin:0;padding:0;' name='female_medicine_name_"+countr+"' id='female_medicine_name_"+countr+"'></td><td style='border:1px solid #000;'><input type='text' style='margin:0;padding:0;' name='female_medicine_dosage_"+countr+"'   id='female_medicine_dosage_"+countr+"'></td><td style='border:1px solid #000;'><input type='text' placeholder='DD-MM-YYYY' style='margin:0;padding:0;' name='female_medicine_when_start_"+countr+"' id='female_medicine_when_start_"+countr+"'  ></td><td style='border:1px solid #000;'><input type='number' style='margin:0;padding:0;' name='female_medicine_days_"+countr+"'   id='female_medicine_days_"+countr+"'></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_route_"+countr+"' id='female_medicine_route_"+countr+"'  > <option value='PO'>PO</option> <option value='IM'>IM</option> <option value='SC'>SC</option> <option value='VAGINA-LY'>VAGINA-LY</option> <option value='IV'>IV</option> <option value='LOCAL'>LOCAL</option> <option value='NASALY'>NASALY</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_frequency_"+countr+"' id='female_medicine_frequency_"+countr+"'  > <option value='OD'>OD</option> <option value='BD'>BD</option> <option value='TDS'>TDS</option> <option value='QID'>QID</option> <option value='SOS'>SOS</option> <option value='HS'>HS</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_timing_"+countr+"' id='female_medicine_timing_"+countr+"'  > <option value='EMPTY STOMACH'>EMPTY STOMACH</option> <option value='BEFORE MEAL'>BEFORE MEAL</option> <option value='AFTER MEAL'>AFTER MEAL</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_take_"+countr+"' id='female_medicine_take_"+countr+"'  > <option value='Daily'>Daily</option> <option value='Biweekly'>Biweekly</option> <option value='Weekly'>Weekly</option> <option value='Blank'>Blank</option> <option value='Alternate Day'>Alternate Day</option></select></td></tr>");
		countr++;
	});
	$("table#female_medicine_table").show();
}); 

// IPD Medicine Change Handlers
$('#female_medicine_suggestion_list_ipd').change(function(e) {
	$("table#female_medicine_table_ipd").hide();
	var brands = $('#female_medicine_suggestion_list_ipd option:selected');
	var selected = "";
	var countr=1;
	$("tbody#female_medicine_suggestion_table_ipd").empty();
	$(brands).each(function(index, brand){
		$("tbody#female_medicine_suggestion_table_ipd").append("<tr style='border:1px solid #000;' count='"+countr+"'><td style='border:1px solid #000;'>"+$(this).attr('medicine')+"<input type='hidden'   readonly value='"+$(this).val()+"' style='margin:0;padding:0;' name='female_medicine_name_ipd_"+countr+"' id='female_medicine_name_ipd_"+countr+"'></td><td style='border:1px solid #000;'><input type='text' style='margin:0;padding:0;' name='female_medicine_dosage_ipd_"+countr+"'   id='female_medicine_dosage_ipd_"+countr+"'></td><td style='border:1px solid #000;'><input type='text' placeholder='DD-MM-YYYY' style='margin:0;padding:0;' name='female_medicine_when_start_ipd_"+countr+"' id='female_medicine_when_start_ipd_"+countr+"'  ></td><td style='border:1px solid #000;'><input type='number' style='margin:0;padding:0;' name='female_medicine_days_ipd_"+countr+"'   id='female_medicine_days_ipd_"+countr+"'></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_route_ipd_"+countr+"' id='female_medicine_route_ipd_"+countr+"'  > <option value='PO'>PO</option> <option value='IM'>IM</option> <option value='SC'>SC</option> <option value='VAGINA-LY'>VAGINA-LY</option> <option value='IV'>IV</option> <option value='LOCAL'>LOCAL</option> <option value='NASALY'>NASALY</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_frequency_ipd_"+countr+"' id='female_medicine_frequency_ipd_"+countr+"'  > <option value='OD'>OD</option> <option value='BD'>BD</option> <option value='TDS'>TDS</option> <option value='QID'>QID</option> <option value='SOS'>SOS</option> <option value='HS'>HS</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_timing_ipd_"+countr+"' id='female_medicine_timing_ipd_"+countr+"'  > <option value='EMPTY STOMACH'>EMPTY STOMACH</option> <option value='BEFORE MEAL'>BEFORE MEAL</option> <option value='AFTER MEAL'>AFTER MEAL</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_take_ipd_"+countr+"' id='female_medicine_take_ipd_"+countr+"'  > <option value='Daily'>Daily</option> <option value='Biweekly'>Biweekly</option> <option value='Weekly'>Weekly</option> <option value='Blank'>Blank</option> <option value='Alternate Day'>Alternate Day</option></select></td></tr>");
		countr++;
	});
	$("table#female_medicine_table_ipd").show();
}); 

$('#male_medicine_suggestion_list_ipd').change(function(e) {
	$("table#male_medicine_table_ipd").hide();
	var brands = $('#male_medicine_suggestion_list_ipd option:selected');
	var selected = "";
	var countr=1;
	$("tbody#male_medicine_suggestion_table_ipd").empty();
	$(brands).each(function(index, brand){
		$("tbody#male_medicine_suggestion_table_ipd").append("<tr style='border:1px solid #000;' count='"+countr+"'><td style='border:1px solid #000;'>"+$(this).attr('medicine')+"<input type='hidden'   readonly value='"+$(this).val()+"' style='margin:0;padding:0;' name='male_medicine_name_ipd_"+countr+"' id='male_medicine_name_ipd_"+countr+"'></td><td style='border:1px solid #000;'><input type='text' style='margin:0;padding:0;' name='male_medicine_dosage_ipd_"+countr+"'   id='male_medicine_dosage_ipd_"+countr+"'></td><td style='border:1px solid #000;'><input type='text' placeholder='DD-MM-YYYY' style='margin:0;padding:0;' name='male_medicine_when_start_ipd_"+countr+"' id='male_medicine_when_start_ipd_"+countr+"'  ></td><td style='border:1px solid #000;'><input type='number' style='margin:0;padding:0;' name='male_medicine_days_ipd_"+countr+"'   id='male_medicine_days_ipd_"+countr+"'></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_route_ipd_"+countr+"' id='male_medicine_route_ipd_"+countr+"'  > <option value='PO'>PO</option> <option value='IM'>IM</option> <option value='SC'>SC</option> <option value='VAGINA-LY'>VAGINA-LY</option> <option value='IV'>IV</option> <option value='LOCAL'>LOCAL</option> <option value='NASALY'>NASALY</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_frequency_ipd_"+countr+"' id='male_medicine_frequency_ipd_"+countr+"'  > <option value='OD'>OD</option> <option value='BD'>BD</option> <option value='TDS'>TDS</option> <option value='QID'>QID</option> <option value='SOS'>SOS</option> <option value='HS'>HS</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_timing_ipd_"+countr+"' id='male_medicine_timing_ipd_"+countr+"'  > <option value='EMPTY STOMACH'>EMPTY STOMACH</option> <option value='BEFORE MEAL'>BEFORE MEAL</option> <option value='AFTER MEAL'>AFTER MEAL</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_take_ipd_"+countr+"' id='male_medicine_take_ipd_"+countr+"'  > <option value='Daily'>Daily</option> <option value='Biweekly'>Biweekly</option> <option value='Weekly'>Weekly</option> <option value='Blank'>Blank</option> <option value='Alternate Day'>Alternate Day</option></select></td></tr>");
		countr++;
	});
	$("table#male_medicine_table_ipd").show();
}); 
});



// $("#follow_up").change(function() {

// 	$("#follow_up_date").val('');

// 	$('div.appoitmented_slot').hide();

// 	$("#appoitmented_slot").prop(' ',false);

// 	$("#follow_up_date").prop('disabled',true);

// if(this.checked) {

// 	$("#appoitmented_slot").prop(' ',true);

// 	$("#follow_up_date").prop('disabled',false);				

// }

// });



//Centre Doctor

$('#appoitment_for').on("change", function() {

	$('div.appoitmented_doctor').hide();

	$('div.appoitmented_date').hide();

	$('div.appoitmented_slot').hide();

	

	$('#loader_div').show();

	var centre_id = $(this).val();

	if(centre_id != ''){

		$.ajax({

		url: '<?php echo base_url('billingcontroller/search_doctor')?>',

		data: {centre_id:centre_id},

		dataType: 'json',

		method:'post',

		success: function(data)

		{

			$('#appoitmented_doctor').empty().append(data);



			$("#appoitmented_doctor").prop(' ',true);

			$("#appoitmented_doctor").prop('disabled',false);

			

			$('div.appoitmented_doctor').show();

			$('#loader_div').hide();			

		} 

  });

    }

	else{

		$('div.appoitmented_doctor').hide();

		$('#loader_div').hide();

	}

});



$('#appoitmented_doctor').on("change", function() {

	$('#loader_div').show();

	var doctor_id = $(this).val();

	$('input#appoitmented_date').val('');

	if(doctor_id != ''){

		$("#appoitmented_date").prop(' ',true);

		$("#appoitmented_date").prop('disabled',false);

		$('div.appoitmented_date').show();

	}else{

		$('div.appoitmented_date').hide();

	}

	$('#loader_div').hide();

});



$( function() {

    $( "#appoitmented_date" ).datepicker({

			dateFormat: 'yy-mm-dd',

			changeMonth: true,

			changeYear: true,

		 	minDate: 0,

			onSelect: function(dateStr) {

				$('#loader_div').show();				

				var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));

				var appoitmented_doctor = $('#appoitmented_doctor').val();

				$.ajax({

					url: '<?php echo base_url('billingcontroller/doctor_slots')?>',

					type: 'POST',

					data: {selected:startDate, appoitmented_doctor:appoitmented_doctor},

					success: function(data) {

						$('#appoitmented_slot').empty().append(data);

						$("#appoitmented_slot").prop(' ',true);

						$("#appoitmented_slot").prop('disabled',false);

						$('div.appoitmented_slot').show();

						$('#loader_div').hide();

					}

				});

			}

		});

} );



$("#follow_up").change(function() {

	$('div.appoitmented_doctor').hide();

	$('div.appoitmented_date').hide();

	$('div.appoitmented_slot').hide();

	

	$('#appoitment_for').prop('selectedIndex',0);

	$("#appoitment_for").prop(' ',false);

	$("#appoitment_for").prop('disabled',true);

	$('#appoitmented_doctor').prop('selectedIndex',0);

	$("#appoitmented_doctor").prop(' ',false);

	$("#appoitmented_doctor").prop('disabled',true);



	$("#appoitmented_date").val('');

	$("#appoitmented_date").prop(' ',false);

	$("#appoitmented_date").prop('disabled',true);

	

	$('#appoitmented_slot').prop('selectedIndex',0);

	$("#appoitmented_slot").prop(' ',false);

	$("#appoitmented_slot").prop('disabled',true);

	



	if(this.checked) {

		$("#appoitment_for").prop(' ',true);

		$("#appoitment_for").prop('disabled',false);

	}

});



$( function() {

$( ".datepicker" ).datepicker({

		dateFormat: 'yy-mm-dd',

		changeMonth: true,

		changeYear: true,

		onSelect: function(dateStr) {}

	});

});



$( function() {

    $( "#follow_up_date" ).datepicker({

			dateFormat: 'yy-mm-dd',

			changeMonth: true,

			changeYear: true,

		 	minDate: 0,

			onSelect: function(dateStr) {

				$('#loader_div').show();				

				var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));

				var appoitmented_doctor = $('#doctor_id').val();

				$.ajax({

					url: '<?php echo base_url('billingcontroller/doctor_slots')?>',

					type: 'POST',

					data: {'selected':startDate, 'appoitmented_doctor':appoitmented_doctor},

					success: function(data) {

						$('#appoitmented_slot').empty().append(data);

						$('div.appoitmented_slot').show();

						$('#loader_div').hide();

					}

				});

			}

		});

} );



$("#medicine_suggestion").change(function() {

//Male Investigation

$("select#male_medicine_suggestion_list").prop('disabled',true);

$("select#male_medicine_suggestion_list").parent().find('button').prop('disabled',true);

$("select#male_medicine_suggestion_list").parent().find('button').addClass('disabled');

$('option', $('#male_medicine_suggestion_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#male_medicine_suggestion_list").multiselect('refresh');	

$("select#male_medicine_suggestion_list").prop('disabled',false);

//Female Investigation

$("select#female_medicine_suggestion_list").prop('disabled',true);

$("select#female_medicine_suggestion_list").parent().find('button').prop('disabled',true);

$("select#female_medicine_suggestion_list").parent().find('button').addClass('disabled');

$('option', $('#female_medicine_suggestion_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#female_medicine_suggestion_list").multiselect('refresh');	

$("select#female_medicine_suggestion_list").prop('disabled',false);



if(this.checked) {

	//Male Investigation

	$("select#male_medicine_suggestion_list").prop('disabled',false);

	$("select#male_medicine_suggestion_list").prop('disabled',false);

	$("select#male_medicine_suggestion_list").parent().find('button').prop('disabled',false);

	$("select#male_medicine_suggestion_list").parent().find('button').removeClass('disabled');

	//Female Investigation

	$("select#female_medicine_suggestion_list").prop('disabled',true);

	$("select#female_medicine_suggestion_list").prop('disabled',false);

	$("select#female_medicine_suggestion_list").parent().find('button').prop('disabled',false);

	$("select#female_medicine_suggestion_list").parent().find('button').removeClass('disabled');

}

});

// IPD Medicine Suggestion Handler
$("#medicine_suggestion_ipd").change(function() {

//Male Investigation

$("select#male_medicine_suggestion_list_ipd").prop('disabled',true);

$("select#male_medicine_suggestion_list_ipd").parent().find('button').prop('disabled',true);

$("select#male_medicine_suggestion_list_ipd").parent().find('button').addClass('disabled');

$('option', $('#male_medicine_suggestion_list_ipd')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#male_medicine_suggestion_list_ipd").multiselect('refresh');	

$("select#male_medicine_suggestion_list_ipd").prop('disabled',false);

//Female Investigation

$("select#female_medicine_suggestion_list_ipd").prop('disabled',true);

$("select#female_medicine_suggestion_list_ipd").parent().find('button').prop('disabled',true);

$("select#female_medicine_suggestion_list_ipd").parent().find('button').addClass('disabled');

$('option', $('#female_medicine_suggestion_list_ipd')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#female_medicine_suggestion_list_ipd").multiselect('refresh');	

$("select#female_medicine_suggestion_list_ipd").prop('disabled',false);



if(this.checked) {

	//Male Investigation

	$("select#male_medicine_suggestion_list_ipd").prop('disabled',false);

	$("select#male_medicine_suggestion_list_ipd").prop('disabled',false);

	$("select#male_medicine_suggestion_list_ipd").parent().find('button').prop('disabled',false);

	$("select#male_medicine_suggestion_list_ipd").parent().find('button').removeClass('disabled');

	//Female Investigation

	$("select#female_medicine_suggestion_list_ipd").prop('disabled',false);

	$("select#female_medicine_suggestion_list_ipd").prop('disabled',false);

	$("select#female_medicine_suggestion_list_ipd").parent().find('button').prop('disabled',false);

	$("select#female_medicine_suggestion_list_ipd").parent().find('button').removeClass('disabled');

}

});

$("#investigation_suggestion").change(function() {

// Male Investigation

$("select#male_investigation_suggestion_list").prop('disabled',true);

$("select#male_investigation_suggestion_list").parent().find('button').prop('disabled',true);

$("select#male_investigation_suggestion_list").parent().find('button').addClass('disabled');

$('option', $('#male_investigation_suggestion_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#male_investigation_suggestion_list").multiselect('refresh');

$("select#male_investigation_suggestion_list").prop(' ',false); 

//Female Investigation

$("select#female_investigation_suggestion_list").prop('disabled',true);

$("select#female_investigation_suggestion_list").parent().find('button').prop('disabled',true);

$("select#female_investigation_suggestion_list").parent().find('button').addClass('disabled');

$('option', $('#female_investigation_suggestion_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#female_investigation_suggestion_list").multiselect('refresh');

$("select#female_investigation_suggestion_list").prop(' ',false);

$("#female_investigation_suggestion_list").multiselect('refresh');

$("select#female_investigation_suggestion_list").prop(' ',false);

if(this.checked) {

	// Male Investigation

	$("select#male_investigation_suggestion_list").prop(' ',false);

	$("select#male_investigation_suggestion_list").prop('disabled',false);

	$("select#male_investigation_suggestion_list").parent().find('button').prop('disabled',false);

	$("select#male_investigation_suggestion_list").parent().find('button').removeClass('disabled');

	//Female Investigation

	$("select#female_investigation_suggestion_list").prop(' ',true);

	$("select#female_investigation_suggestion_list").prop('disabled',false);

	$("select#female_investigation_suggestion_list").parent().find('button').prop('disabled',false);

	$("select#female_investigation_suggestion_list").parent().find('button').removeClass('disabled');

}



// Male Investigation

$("select#male_minvestigation_suggestion_list").prop('disabled',true);

$("select#male_minvestigation_suggestion_list").parent().find('button').prop('disabled',true);

$("select#male_minvestigation_suggestion_list").parent().find('button').addClass('disabled');

$('option', $('#male_minvestigation_suggestion_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#male_minvestigation_suggestion_list").multiselect('refresh');

$("select#male_minvestigation_suggestion_list").prop(' ',false); 

//Female Investigation

$("select#female_minvestigation_suggestion_list").prop('disabled',true);

$("select#female_minvestigation_suggestion_list").parent().find('button').prop('disabled',true);

$("select#female_minvestigation_suggestion_list").parent().find('button').addClass('disabled');

$('option', $('#female_minvestigation_suggestion_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#female_minvestigation_suggestion_list").multiselect('refresh');

$("select#female_minvestigation_suggestion_list").prop(' ',false);

$("#female_minvestigation_suggestion_list").multiselect('refresh');

$("select#female_minvestigation_suggestion_list").prop(' ',false);

if(this.checked) {

	// Male Investigation

	$("select#male_minvestigation_suggestion_list").prop(' ',false);

	$("select#male_minvestigation_suggestion_list").prop('disabled',false);

	$("select#male_minvestigation_suggestion_list").parent().find('button').prop('disabled',false);

	$("select#male_minvestigation_suggestion_list").parent().find('button').removeClass('disabled');

	//Female Investigation

	$("select#female_minvestigation_suggestion_list").prop(' ',true);

	$("select#female_minvestigation_suggestion_list").prop('disabled',false);

	$("select#female_minvestigation_suggestion_list").parent().find('button').prop('disabled',false);

	$("select#female_minvestigation_suggestion_list").parent().find('button').removeClass('disabled');

}


});

$("#provisional_diagnosis_suggestion").change(function() {

// Male Investigation

$("select#male_provisional_diagnosis_list").prop('disabled',true);

$("select#male_provisional_diagnosis_list").parent().find('button').prop('disabled',true);

$("select#male_provisional_diagnosis_list").parent().find('button').addClass('disabled');

$('option', $('#male_provisional_diagnosis_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#male_provisional_diagnosis_list").multiselect('refresh');

$("select#male_provisional_diagnosis_list").prop(' ',false); 

//Female Investigation

$("select#female_provisional_diagnosis_list").prop('disabled',true);

$("select#female_provisional_diagnosis_list").parent().find('button').prop('disabled',true);

$("select#female_provisional_diagnosis_list").parent().find('button').addClass('disabled');

$('option', $('#female_provisional_diagnosis_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#female_provisional_diagnosis_list").multiselect('refresh');

$("select#female_provisional_diagnosis_list").prop(' ',false);

//Female Provisional Diagnosis

$("select#female_provisional_diagnosis_list").prop('disabled',true);

$("select#female_provisional_diagnosis_list").parent().find('button').prop('disabled',true);

$("select#female_provisional_diagnosis_list").parent().find('button').addClass('disabled');

$('option', $('#female_provisional_diagnosis_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#female_provisional_diagnosis_list").multiselect('refresh');

$("select#female_provisional_diagnosis_list").prop(' ',false);

if(this.checked) {

	// Male Investigation

	$("select#male_provisional_diagnosis_list").prop(' ',false);

	$("select#male_provisional_diagnosis_list").prop('disabled',false);

	$("select#male_provisional_diagnosis_list").parent().find('button').prop('disabled',false);

	$("select#male_provisional_diagnosis_list").parent().find('button').removeClass('disabled');

	//Female Investigation

	$("select#female_provisional_diagnosis_list").prop(' ',true);

	$("select#female_provisional_diagnosis_list").prop('disabled',false);

	$("select#female_provisional_diagnosis_list").parent().find('button').prop('disabled',false);

	$("select#female_provisional_diagnosis_list").parent().find('button').removeClass('disabled');

}

});


$("#procedure_suggestion").change(function() {

$("select#sub_procedure_suggestion_list").prop('disabled',true);

$("select#sub_procedure_suggestion_list").parent().find('button').prop('disabled',true);

$("select#sub_procedure_suggestion_list").parent().find('button').addClass('disabled');

$('option', $('#sub_procedure_suggestion_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#sub_procedure_suggestion_list").multiselect('refresh');



$("select#procedure_suggestion_list").prop(' ',false);	

$("select#sub_procedure_suggestion_list").prop(' ',false);

$("select#procedure_suggestion_list").prop('disabled',true);

if(this.checked) {

	$("select#sub_procedure_suggestion_list").prop('disabled',false);

	$("select#sub_procedure_suggestion_list").parent().find('button').prop('disabled',false);

	$("select#sub_procedure_suggestion_list").parent().find('button').removeClass('disabled');

}

});


$("#package_suggestion").change(function() {

$("select#package_suggestion_list").prop('disabled',true);

$("select#package_suggestion_list").parent().find('button').prop('disabled',true);

$("select#package_suggestion_list").parent().find('button').addClass('disabled');

$('option', $('#package_suggestion_list')).each(function(element) {

	$(this).removeAttr('selected').prop('selected', false);

});

$("#package_suggestion_list").multiselect('refresh');



$("select#package_suggestion_list").prop(' ',false);	

$("select#package_suggestion_list").prop(' ',false);

$("select#package_suggestion_list").prop('disabled',true);

if(this.checked) {

	$("select#package_suggestion_list").prop('disabled',false);

	$("select#package_suggestion_list").parent().find('button').prop('disabled',false);

	$("select#package_suggestion_list").parent().find('button').removeClass('disabled');

}

});



// $("#procedure_suggestion_list").change(function() {

// 	$('option', $('#sub_procedure_suggestion_list')).each(function(element) {

// 		$(this).removeAttr('selected').prop('selected', false);

// 	});

// 	$("#sub_procedure_suggestion_list").multiselect('refresh');

// 	$("select#sub_procedure_suggestion_list").prop('disabled',true);



// 	$('#loader_div').show();

// 	var parent_parents = $(this).val();

// 	$.ajax({

// 		url: '<?php echo base_url('billingcontroller/get_sub_procedures')?>',

// 		data: {parent_parents : parent_parents,patient_id:'<?php echo $patient_data['patient_id']?>'},

// 		dataType: 'json',

// 		method:'post',

// 		success: function(data)

// 		{

// 			$('#sub_procedure_suggestion_list').empty().append(data.html);

// 			$("select#sub_procedure_suggestion_list").multiselect('rebuild');

// 			$("select#sub_procedure_suggestion_list").prop('disabled',false);

// 			$("select#sub_procedure_suggestion_list").parent().find('button').prop('disabled',false);

// 			$("select#sub_procedure_suggestion_list").parent().find('button').removeClass('disabled');

// 			$('#loader_div').hide();

// 		} 

// 	});

// });



$(function() {

    // $('.multidselect_dropdown').multiselect({ includeSelectAllOption: true });

    // $('.multidselect_dropdown_1').multiselect({ includeSelectAllOption: true });

    // $('.multidselect_dropdown_2').multiselect({ includeSelectAllOption: true });

    $('.multidselect_dropdown').multiselect({

		includeSelectAllOption: true,

		enableFiltering: true,

		enableCaseInsensitiveFiltering: true,

		filterPlaceholder: 'Search for something...'

	}); 



	$('.multidselect_dropdown_1').multiselect({

		includeSelectAllOption: true,

		enableFiltering: true,

		enableCaseInsensitiveFiltering: true,

		filterPlaceholder: 'Search for something...'

	}); 



	$('.multidselect_dropdown_2').multiselect({

		includeSelectAllOption: true,

		enableFiltering: true,

		enableCaseInsensitiveFiltering: true,

		filterPlaceholder: 'Search for something...'

	});

});

</script>



