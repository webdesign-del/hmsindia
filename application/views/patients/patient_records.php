<?php
	$all_method =&get_instance();
	$patient_data = get_patient_detail($patient_id);		
?>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
  text-align: left;
}
.img_sec{max-width:100px;}
</style>
<div class="col-sm-12 col-xs-12  panel panel-piluku ml-5">
  <div class="row">
      <a href="<?php echo base_url('my_ipd');?>" class="btn btn-large pull-right">Go Back</a>
      <?php echo patient_medical_data($patient_id);?>
  </div>
  <hr/>
  <div class="row">
    <h4>Procedure Reports</h4>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover dataList" id="">
          <thead>
            <tr>
				<th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Procedure</th>
				<th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Code</th>
				<th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Record</th>
				<th style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">Check record</th>
			</tr>
          </thead>
          <tbody>
			<?php $procedure_billing = check_patient_procedure_billing($patient_id); //var_dump($procedure_billing);die;
				if(count($procedure_billing) > 0){
				$patient_prodedures_data = $all_method->get_patient_prodedures_data($appointment_id, $patient_id);
				foreach($patient_prodedures_data as $key => $patient_prodedures){
					$receipt_number = $patient_prodedures['receipt_number'];
					$procedure_datas = unserialize($patient_prodedures['data']);
				
				  foreach($procedure_datas['patient_procedures'] as $ky => $val){
					$procedure_assign = have_procedure_assign($val['sub_procedure'], 'daycare_procedure');
					if($procedure_assign){  ?>
				<tr>
					<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;" class="role"><?php echo get_procedure_data($val['sub_procedure']); ?></td>
					<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;"><?php echo $val['sub_procedures_code']?></td>
					<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: left;">
    <?php if (have_form($val['sub_procedure'], 'doctor')) { ?>    
        <!-- 🟢 Upload Record Button with Dynamic Parameters -->
        <a procedure_id="<?php echo $val['sub_procedure']; ?>" 
           patient_procedure_id="<?php echo $patient_prodedures['ID']; ?>" 
           href="javascript:void(0);" 
           appointment_id="<?php echo $appointment_id; ?>" 
           class="upload_record btn btn-large a">
           Upload record
        </a>

        <!-- 📄 AJAX Response Container for Form Links -->
        <div class="procedure_form_div" id="procedure_form_<?php echo $val['sub_procedure']; ?>"></div>
    <?php } ?>
</td>
					<td style="border: 1px solid black; border-collapse: collapse;padding:5px; text-align:left;">
						<?php if(have_form($val['sub_procedure'], 'doctor')){ ?>
						<a href="javascript:void(0);" procedure_id="<?php echo $val['sub_procedure']; ?>" class="check_record btn btn-large">Check record</a>
						<div style="display:none;" class="check_form_div" id="check_form_<?php echo $val['sub_procedure']; ?>">
						<?php $form_list = get_prodecure_forms($val['sub_procedure']); //var_dump($form_list);die; 
							  $html = "";
							  foreach($form_list as $ks => $vls){
							    $form_details = get_prodecure_form($vls['form_id']);
								if(isset($_SESSION['logged_doctor'])){
									if($form_details['form_for'] != "lab_procedure"){
										$check_form_data = check_form_data($patient_id, $receipt_number, $form_details['form_area']); //var_dump($form_details['form_area']);die;
										if(count($check_form_data) > 0){
											$html .= "<a target='_blank' href='".base_url('check_procedure_form/'.$vls['form_id'].'/'.$patient_prodedures['ID'].'/'.$appointment_id.'/'.$val['sub_procedure'])."'>".$form_details['form_name']."</a> | ";
										}
									}
								}
							  }
								$html = substr($html, 0, -3);
								if(empty($html)){
									$html = "<p class='error'>No data found!</p>   ";
								}
							  echo $html;
						?>
						</div>
						<?php } ?>
					</td>
				</tr>
			<?php } } } } ?>
          </tbody>
        </table>
	  </div>
  </div>
</div>
<script>
$(document).on('click',".check_record",function(e) {
    $('.check_form_div').hide();
    $('.procedure_form_div').hide();
    $('.procedure_form_div').empty();
    var procedure_id = $(this).attr('procedure_id');
    $('#check_form_'+procedure_id).show();
});

$(document).on('click',".upload_record",function(e) {
	$('.check_form_div').hide();
	var procedure_id = $(this).attr('procedure_id');
	var appointment_id = $(this).attr('appointment_id');
	var patient_procedure_id = $(this).attr('patient_procedure_id');
	$('.procedure_form_div').hide();
	$('.procedure_form_div').empty();
	
	$.ajax({
		url: '<?php echo base_url('doctors/procedure_upload')?>',
		data: {'procedure_id':procedure_id, 'appointment_id':appointment_id, 'patient_procedure_id':patient_procedure_id, 'patient_id':'<?php echo $patient_id; ?>'},
		dataType: 'json',
		method:'post',
		success: function(data)
		{
			$('#procedure_form_'+procedure_id).empty().append(data);
			$('#procedure_form_'+procedure_id).show();
		}
	});
});

</script>