<?php $all_method =&get_instance();
$consultation_data = $all_method->get_consultation($appointments['ID']);
$patient_data = get_patient_detail($consultation_data['patient_id']);
//var_dump($consultation_data); echo '<br/><br/><br/>'; 
//var_dump($patient_data);die;
?>

<style>
	.table > thead > tr > th {
		vertical-align: top;
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
	.multiselect-container>li>a>label {
		padding: 4px 20px 3px 20px;
	}
	table#SOCIAl_DRUG_INTAKE_HISTORY td, #table_dentures td {
		width: 55%;
	}
	input[type="checkbox"] {
		position: relative!important;
		left: 2px!important;
		opacity: 1!important;
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
</style>

<form class="col-sm-12 col-xs-12" method="post" action="" enctype="multipart/form-data" >
<input type="hidden" name="action" value="add_consultation_done" />
<input type="hidden" name="appointment_id" value="<?php echo $appointments['ID']; ?>" />
<input type="hidden" name="patient_id" value="<?php echo $patient_data['patient_id']; ?>" />
<input type="hidden" name="wife_phone" value="<?php echo $patient_data['wife_phone']; ?>" />
<input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $_SESSION['logged_doctor']['doctor_id']?>" />

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<!-- left column -->
			<div class="col-md-12">
					<!-- general form elements -->
					<div class="card card-primary">
						<!--   <div class="card-header">
							<h3 class="card-title">Quick Example</h3>
						</div> -->
						<!-- /.card-header -->
						<!-- form start -->
						<table id="example1" class="table table-bordered table-striped">
							<thead>
							<th>FOLLOW UP  OF PATIENT</th>
								<th style="color: red;">Female</th>
								<th style="color: red;">Male</th>
								</tr>
							</thead>
							<thead>
							
					<tr>
						<td style="color: red;">PRESENTING COMPLAINTS</td>
						<td><input type="text" name="female_findings" class="form-control"></td>
						<td><input type="text" name="male_findings" class="form-control"></td>
					</tr>
					<tr>
						<td>INVESTIGATIONS REPORT</td>
						<td colspan="2"><a href="<?php echo base_url('my_reports'); ?>"  target="_blank">My Reports</a> | <a href="<?php echo base_url('my_ipd'); ?>" target="_blank">My IPD</a> | <a href="<?php echo base_url('patient_details/'.$patient_data['patient_id']); ?>" target="_blank">Patient Data</a></td>
					</tr>
					<tr style="color: red;">
						<td>INVESTIGATIONS ADVISED   <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="investigation_suggestion" value="1" name="investigation_suggestion" /></td>
						<td>
							<select class="multidselect_dropdown_1" multiple id="female_investigation_suggestion_list" disabled name="female_investigation_suggestion_list[]">
								<?php if(!empty($investigations)) { foreach($investigations as $key => $val) { ?>
										<option value="<?php echo $val['ID']; ?>"><?php echo $val['investigation']; ?></option>
								<?php  } } ?>
							</select>
						</td>
						<td>
								<select class="multidselect_dropdown_1" multiple id="male_investigation_suggestion_list" disabled name="male_investigation_suggestion_list[]">
									<?php if(!empty($investigations)) { foreach($investigations as $key => $val) { ?>
											<option value="<?php echo $val['ID']; ?>"><?php echo $val['investigation']; ?></option>
									<?php  } } ?>
								</select>
						</td>
					</tr>
					<tr>
						<td style="color: red;">MEDICATION ADVISED   <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="medicine_suggestion" value="1" name="medicine_suggestion" /></td>
						<td>
							<div class="col-sm-12 col-xs-12">
								<select class="multidselect_dropdown" multiple id="female_medicine_suggestion_list" disabled>
									<?php if(!empty($consultation_medicine)) { foreach($consultation_medicine as $key => $val) { ?>
											<option value="<?php echo $val['item_number']; ?>" medicine="<?php echo $val['item_name']; ?>"><?php echo $val['item_name']; ?></option>
									<?php  } } ?>
									        <option value="NA" medicine="NA">NA</option>
								</select>
								<hr/>
								<table id="female_medicine_table" style="width:100%; border:1px solid #000; display:none;" border='1'>
										<thead>
											<tr>
												<th style="border:1px solid #000; padding:10px;">Medicine</th>
												<th style="border:1px solid #000; padding:10px;">Dosage</th>
												<th style="border:1px solid #000; padding:10px;">Start on</th>
												<th style="border:1px solid #000; padding:10px;">Days</th>
												<th style="border:1px solid #000; padding:10px;">Route</th>
												<th style="border:1px solid #000; padding:10px;">Frequency</th>
												<th style="border:1px solid #000; padding:10px;">Timing</th>
											</tr>
											<tbody id="female_medicine_suggestion_table"></tbody>
										</thead>
								</table>
							</div>
						</td>
						<td>
							<div class="col-sm-12 col-xs-12">
								<select class="multidselect_dropdown" multiple id="male_medicine_suggestion_list" disabled>
									<?php if(!empty($consultation_medicine)) { foreach($consultation_medicine as $key => $val) { ?>
											<option value="<?php echo $val['item_number']; ?>" medicine="<?php echo $val['item_name']; ?>"><?php echo $val['item_name']; ?></option>
									<?php  } } ?>
									 <option value="NA" medicine="NA">NA</option>
								</select>
								<hr/>
								<table style="width:100%; border:1px solid #000; display:none;" id="male_medicine_table" border='1'>
										<thead>
											<tr>
												<th style="border:1px solid #000; padding:10px;">Medicine</th>
												<th style="border:1px solid #000; padding:10px;">Dosage</th>
												<th style="border:1px solid #000; padding:10px;">Start on</th>
												<th style="border:1px solid #000; padding:10px;">Days</th>
												<th style="border:1px solid #000; padding:10px;">Route</th>
												<th style="border:1px solid #000; padding:10px;">Frequency</th>
												<th style="border:1px solid #000; padding:10px;">Timing</th>
											</tr>
											<tbody id="male_medicine_suggestion_table"></tbody>
										</thead>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td style="color: red;">MANAGEMENT ADVISED  <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="procedure_suggestion" value="1" name="procedure_suggestion" /></td>
						<td colspan="2"><select class="form-control multidselect_dropdown_2"  multiple="multiple" id="sub_procedure_suggestion_list" name="sub_procedure_suggestion_list[]" disabled>
								<?php if(!empty($procedures)) { foreach($procedures as $key => $val) { ?>
										<option value="<?php echo $val['ID']; ?>"><?php echo $val['procedure_name']." (".$val['code'].")"; ?></option>
								<?php  } } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>NEXT FOLLOW UP <input style="left: 5px;position: relative;opacity: 1; top:3px;" type="checkbox" id="follow_up" value="1" name="follow_up" /></th>
						
						<td colspan="2">
							<!-- <div class="col-sm-12 col-xs-12">
								<input type="text" placeholder="yy-mm-dd" autocomplete="off" disabled="disabled" id="follow_up_date" name="follow_up_date" />
							</div>
							<div class="row appoitmented_slot" style="display:none;">            
								<div class="form-group col-sm-6 col-xs-12 role">
									<label for="statuss">Follow up slot (Required)</label>
									<select name="appoitmented_slot" class="empty-field" id="appoitmented_slot">
										<option value="">Select</option>
									</select>
								</div>
							</div> -->
							<div class="row">            
								<div class="form-group col-sm-6 col-xs-12 role">
									<label for="statuss">Centre (Required)</label>
									<select name="appoitment_for" disabled class="empty-field" id="appoitment_for">
										<option value="">Select</option>
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
							<!-- <input type="radio" name="follow_up_purpose" value="FIRST CONSULTATION">
							<label>FIRST CONSULTATION</label> -->
							<input type="radio" name="follow_up_purpose" checked value="FOLLOW UP VISIT">
							<label>FOLLOW UP VISIT</label>
							<input type="radio" name="follow_up_purpose" value="PROCEDURE">
							<label>PROCEDURE</label><br>
						</td>
					</tr>
							</thead>
						</table>
			<!-- /.card-body -->
			<div class="card-footer">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>
		<!-- /.card -->
		</div>
	</div>
	</div>
</form>

<script>
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

			$("#appoitmented_doctor").prop('required',true);
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
		$("#appoitmented_date").prop('required',true);
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
						$("#appoitmented_slot").prop('required',true);
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
	$("#appoitment_for").prop('required',false);
	$("#appoitment_for").prop('disabled',true);
	$('#appoitmented_doctor').prop('selectedIndex',0);
	$("#appoitmented_doctor").prop('required',false);
	$("#appoitmented_doctor").prop('disabled',true);

	$("#appoitmented_date").val('');
	$("#appoitmented_date").prop('required',false);
	$("#appoitmented_date").prop('disabled',true);
	
	$('#appoitmented_slot').prop('selectedIndex',0);
	$("#appoitmented_slot").prop('required',false);
	$("#appoitmented_slot").prop('disabled',true);
	

	if(this.checked) {
		$("#appoitment_for").prop('required',true);
		$("#appoitment_for").prop('disabled',false);
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
		$("tbody#male_medicine_suggestion_table").append("<tr style='border:1px solid #000;'><td style='border:1px solid #000;'>"+$(this).attr('medicine')+"<input type='hidden' required readonly value='"+$(this).val()+"' style='margin:0;padding:0;' name='male_medicine_name_"+countr+"' id='male_medicine_name_"+countr+"'></td><td style='border:1px solid #000;'><input type='number' style='margin:0;padding:0;' name='male_medicine_dosage_"+countr+"' required id='male_medicine_dosage_"+countr+"'></td><td style='border:1px solid #000;'><input type='number' style='margin:0;padding:0;' name='male_medicine_when_start_"+countr+"' id='male_medicine_when_start_"+countr+"' required></td><td style='border:1px solid #000;'><input type='number' style='margin:0;padding:0;' name='male_medicine_days_"+countr+"' required id='male_medicine_days_"+countr+"'></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_route_"+countr+"' id='male_medicine_route_"+countr+"' required> <option value='PO'>PO</option> <option value='IM'>IM</option> <option value='SC'>SC</option> <option value='VAGINA-LY'>VAGINA-LY</option> <option value='IV'>IV</option> <option value='LOCAL'>LOCAL</option> <option value='NASALY'>NASALY</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_frequency_"+countr+"' id='male_medicine_frequency_"+countr+"' required> <option value='OD'>OD</option> <option value='BD'>BD</option> <option value='TDS'>TDS</option> <option value='QID'>QID</option> <option value='SOS'>SOS</option> <option value='HS'>HS</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='male_medicine_timing_"+countr+"' id='male_medicine_timing_"+countr+"' required> <option value='EMPTY STOMACH'>EMPTY STOMACH</option> <option value='BEFORE MEAL'>BEFORE MEAL</option> <option value='AFTER MEAL'>AFTER MEAL</option></select></td></tr>");
		countr++;
		//selected.push([$(this).val()+"--------"+$(this).attr('medicine')]);
	});
	$("table#male_medicine_table").show();
	//console.log(selected);
}); 

$('#female_medicine_suggestion_list').change(function(e) {
	$("table#female_medicine_table").hide();
	var brands = $('#female_medicine_suggestion_list option:selected');
	var selected = "";
	var countr=1;
	$("tbody#female_medicine_suggestion_table").empty();
	$(brands).each(function(index, brand){
		$("tbody#female_medicine_suggestion_table").append("<tr style='border:1px solid #000;'><td style='border:1px solid #000;'>"+$(this).attr('medicine')+"<input type='hidden' required readonly value='"+$(this).val()+"' style='margin:0;padding:0;' name='female_medicine_name_"+countr+"' id='female_medicine_name_"+countr+"'></td><td style='border:1px solid #000;'><input type='number' style='margin:0;padding:0;' name='female_medicine_dosage_"+countr+"' required id='female_medicine_dosage_"+countr+"'></td><td style='border:1px solid #000;'><input type='number' style='margin:0;padding:0;' name='female_medicine_when_start_"+countr+"' id='female_medicine_when_start_"+countr+"' required></td><td style='border:1px solid #000;'><input type='number' style='margin:0;padding:0;' name='female_medicine_days_"+countr+"' required id='female_medicine_days_"+countr+"'></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_route_"+countr+"' id='female_medicine_route_"+countr+"' required> <option value='PO'>PO</option> <option value='IM'>IM</option> <option value='SC'>SC</option> <option value='VAGINA-LY'>VAGINA-LY</option> <option value='IV'>IV</option> <option value='LOCAL'>LOCAL</option> <option value='NASALY'>NASALY</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_frequency_"+countr+"' id='female_medicine_frequency_"+countr+"' required> <option value='OD'>OD</option> <option value='BD'>BD</option> <option value='TDS'>TDS</option> <option value='QID'>QID</option> <option value='SOS'>SOS</option> <option value='HS'>HS</option></select></td><td style='border:1px solid #000;' class='role'><select style='margin:0;padding:0;' name='female_medicine_timing_"+countr+"' id='female_medicine_timing_"+countr+"' required> <option value='EMPTY STOMACH'>EMPTY STOMACH</option> <option value='BEFORE MEAL'>BEFORE MEAL</option> <option value='AFTER MEAL'>AFTER MEAL</option></select></td></tr>");
		countr++;
		//selected.push([$(this).val()+"--------"+$(this).attr('medicine')]);
	});
	//console.log(selected);
	$("table#female_medicine_table").show();
}); 
});



$( function() {
$( ".datepicker" ).datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateStr) {}
	});
});

// $( function() {
//     $( "#follow_up_date" ).datepicker({
// 			dateFormat: 'yy-mm-dd',
// 			changeMonth: true,
// 			changeYear: true,
// 		 	minDate: 0,
// 			onSelect: function(dateStr) {
// 				$('#loader_div').show();				
// 				var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
// 				var appoitmented_doctor = $('#doctor_id').val();
// 				$.ajax({
// 					url: '<?php echo base_url('billingcontroller/doctor_slots')?>',
// 					type: 'POST',
// 					data: {'selected':startDate, 'appoitmented_doctor':appoitmented_doctor},
// 					success: function(data) {
// 						$('#appoitmented_slot').empty().append(data);
// 						$('div.appoitmented_slot').show();
// 						$('#loader_div').hide();
// 					}
// 				});
// 			}
// 		});
// } );

$("#medicine_suggestion").change(function() {
//Male Investigation
$("select#male_medicine_suggestion_list").prop('disabled',true);
$("select#male_medicine_suggestion_list").parent().find('button').prop('disabled',true);
$("select#male_medicine_suggestion_list").parent().find('button').addClass('disabled');
$('option', $('#male_medicine_suggestion_list')).each(function(element) {
	$(this).removeAttr('selected').prop('selected', false);
});
$("#male_medicine_suggestion_list").multiselect('refresh');	
$("select#male_medicine_suggestion_list").prop('required',false);
//Female Investigation
$("select#female_medicine_suggestion_list").prop('disabled',true);
$("select#female_medicine_suggestion_list").parent().find('button').prop('disabled',true);
$("select#female_medicine_suggestion_list").parent().find('button').addClass('disabled');
$('option', $('#female_medicine_suggestion_list')).each(function(element) {
	$(this).removeAttr('selected').prop('selected', false);
});
$("#female_medicine_suggestion_list").multiselect('refresh');	
$("select#female_medicine_suggestion_list").prop('required',false);

if(this.checked) {
	//Male Investigation
	$("select#male_medicine_suggestion_list").prop('required',true);
	$("select#male_medicine_suggestion_list").prop('disabled',false);
	$("select#male_medicine_suggestion_list").parent().find('button').prop('disabled',false);
	$("select#male_medicine_suggestion_list").parent().find('button').removeClass('disabled');
	//Female Investigation
	$("select#female_medicine_suggestion_list").prop('required',true);
	$("select#female_medicine_suggestion_list").prop('disabled',false);
	$("select#female_medicine_suggestion_list").parent().find('button').prop('disabled',false);
	$("select#female_medicine_suggestion_list").parent().find('button').removeClass('disabled');
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
$("select#male_investigation_suggestion_list").prop('required',false);
//Female Investigation
$("select#female_investigation_suggestion_list").prop('disabled',true);
$("select#female_investigation_suggestion_list").parent().find('button').prop('disabled',true);
$("select#female_investigation_suggestion_list").parent().find('button').addClass('disabled');
$('option', $('#female_investigation_suggestion_list')).each(function(element) {
	$(this).removeAttr('selected').prop('selected', false);
});
$("#female_investigation_suggestion_list").multiselect('refresh');
$("select#female_investigation_suggestion_list").prop('required',false);

if(this.checked) {
	// Male Investigation
	$("select#male_investigation_suggestion_list").prop('required',true);
	$("select#male_investigation_suggestion_list").prop('disabled',false);
	$("select#male_investigation_suggestion_list").parent().find('button').prop('disabled',false);
	$("select#male_investigation_suggestion_list").parent().find('button').removeClass('disabled');
	//Female Investigation
	$("select#female_investigation_suggestion_list").prop('required',true);
	$("select#female_investigation_suggestion_list").prop('disabled',false);
	$("select#female_investigation_suggestion_list").parent().find('button').prop('disabled',false);
	$("select#female_investigation_suggestion_list").parent().find('button').removeClass('disabled');
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

$("select#procedure_suggestion_list").prop('required',false);	
$("select#sub_procedure_suggestion_list").prop('required',false);
$("select#procedure_suggestion_list").prop('disabled',true);
if(this.checked) {
	$("select#sub_procedure_suggestion_list").prop('disabled',false);
	$("select#sub_procedure_suggestion_list").parent().find('button').prop('disabled',false);
	$("select#sub_procedure_suggestion_list").parent().find('button').removeClass('disabled');
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
	$('.multidselect_dropdown').multiselect({ includeSelectAllOption: true });
	$('.multidselect_dropdown_1').multiselect({ includeSelectAllOption: true });
	$('.multidselect_dropdown_2').multiselect({ includeSelectAllOption: true });
});


</script>
