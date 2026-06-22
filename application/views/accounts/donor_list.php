 <?php $all_method =&get_instance(); ?>
    <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3>Donor List  </h3></div>
       <div class="clearfix"></div>
	    <form action="<?php echo base_url().'accounts/donor_list'; ?>" method="get" >
		    <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Patients Id</label>
              <input type="text" class="form-control" id="patient_id" name="patient_id" value="<?php echo $patient_id;?>" />
            </div>
			<div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Donor Patients Id</label>
              <input type="text" class="form-control" id="donor_patient_id" name="donor_patient_id" value="<?php echo $donor_patient_id;?>" />
            </div>
			<div class="col-sm-1" style="margin-top: 10px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<a href="<?php echo base_url().'accounts/donor_list'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
            </div>
            </form>  
        <div class="clearfix"></div>
        <div class="card-content">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="procedure_billing_list">
              <thead>
                <tr>
				          <th>S.No.</th>
				          <th>UHID</th>
                  <th>Patients Id</th>
                  <th>Patients Name</th>
                  <th>Donor UHID</th>
                  <th>Donor Patients Id</th>
                  <th>Donor Patients Name</th>
				          <th>Type</th>
                  <th>Date</th>
				</tr>
              </thead>
              <tbody id="procedure_result">
              <?php 
			  $count=1; 
			  foreach($procedure_result as $ky => $vl){
               ?>
                <tr class="odd gradeX">
                  <td><?php echo $count; ?></td>
				  <td><?php echo $vl['uhid']?></td>
                  <td><a href="<?php echo base_url(); ?>patient_details/<?php echo $vl['patient_id']?>"><?php echo $vl['patient_id']?></a></td>
                  <td><?php echo $vl['PatientName']?></td>
                  <td><?php echo $vl['donor_uhid']?></td>
                  <td><a href="<?php echo base_url(); ?>patient_details/<?php echo $vl['donor_patient_id']?>"><?php echo $vl['donor_patient_id']?></a></td>
                  <td><?php echo $vl['donor_PatientName']?></td>
				  <td><?php echo $vl['type']?></td>
				  <td><?php echo $vl['date']?></td>
          <td><?php
switch($vl['type']) {
    case "Donor":
        echo '<a href="' . base_url() . 'procedures/opu_donor/'.$vl['donor_patient_id'].'" target="_blank">Opu Donor,</a>&nbsp';
        echo '<a href="' . base_url() . 'procedures/outcome_donor/'.$vl['donor_patient_id'].'" target="_blank">Outcome Donor, </a>&nbsp';
        echo '<a href="' . base_url() . 'procedures/serum_bete_hcg_on_donor/'.$vl['donor_patient_id'].'" target="_blank">Serum Bete Hcg On Donor, </a>&nbsp';
        echo '<a href="' . base_url() . 'procedures/trigger_module_donor/'.$vl['donor_patient_id'].'" target="_blank">Trigger Module Donor, </a>&nbsp';
        echo '<a href="' . base_url() . 'procedures/ovulation_induction_protocol_donor/'.$vl['donor_patient_id'].'" target="_blank">Ovulation Induction Protocol Donor, </a>&nbsp';
        echo '<a href="' . base_url() . 'procedures/embryo_transfer_donor/'.$vl['donor_patient_id'].'" target="_blank">Embryo Transfer Donor, </a>&nbsp';
        echo '<a href="' . base_url() . 'procedures/oocyte_embryo_record_sheet_till_d3_donor/'.$vl['donor_patient_id'].'" target="_blank">Oocyte Embryo Record Sheet Till D3 Donor, </a>&nbsp';
        echo '<a href="' . base_url() . 'procedures/initial_details_donor/'.$vl['donor_patient_id'].'" target="_blank">Initial Details Donor, </a>&nbsp';
        echo '<a href="' . base_url() . 'procedures/admission_form_donor/'.$vl['donor_patient_id'].'" target="_blank">Admission Form Donor, </a>&nbsp';
        
        break;
    case "Surrogate":
        echo '<a href="surrogate_mother_personal_details">Surrogate</a>';
        break;
}
?>
        </td>
				</tr>
              <?php $count++;} ?>
			   <tr>
                <td colspan="11">
                <p class="custom-pagination"><?php echo $links; ?></p>
                </td>
              </tr>
              </tbody>
			  
			  

			  
            </table>
          </div>
        </div>
      </div>
     </div>
   
<script>
      $( function() {
        $( ".particular_date_filter" ).datepicker({
          dateFormat: 'yy-mm-dd',
          changeMonth: true,
          changeYear: true,
          onSelect: function(dateStr) {
            $('#loader_div').hide();				
            var startDate = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker('getDate'));
            var data = {appointment_date:startDate, type:'particular_date_filter'};
          }
        });
    });
</script>
<style >
.custom-pagination{
  padding:8px;
}
.custom-pagination a{
  padding:10px;
  text-decoration: none;
}
.form-control{
  height: 30px!important;
  border: 1px solid #9e9e9e!important;
}
.form-control#billing_at{
  height: 40px!important;
  border: 1px solid #9e9e9e!important;
}
</style>