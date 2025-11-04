 <?php $all_method =&get_instance(); ?>
    <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3>Patient Timeline</h3></div>
       <div class="clearfix"></div>
	    <form action=""<?php echo base_url().'patients/timeline_view'; ?>" method="get">
		     <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            <label>IIC ID </label>
                <input type="text" class="form-control" id="paitent_id" name="paitent_id">
            </div>
              <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>CRM ID </label>
                <input type="text" class="form-control" id="crm_id" name="crm_id">
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Start Date</label>
              <input type="text" class="particular_date_filter form-control" id="start_date" name="start_date" value="<?php echo $start_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>End Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>
		       	<div class="col-sm-3" style="margin-top: 10px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            	<a href="<?php echo base_url().'patients/timeline_view'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
               <a href="<?php echo base_url('patients/agent-reports'); ?>" style="text-decoration: none;">
                <button name="export-billing" type="submit"  class="btn btn-secondary" id="export-billing">Export Reports</button>
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
                  <th>CRM ID</th>
                  <th>IIC ID</th>
                  <th>Appointment Create</th>
                  <th>Agent</th>
                  <th>Date of Appointment </th>
                  <th>Consultation</th>
                  <th>Agent</th>
                  <th>Date Of consultation</th>
                   <th>Booking</th>
                  <th>Agent</th>
                  <th>Date Of Booking</th>
		      		</tr>
              </thead>
              <tbody id="semen_analysis_result">
<<<<<<< Updated upstream
                <?php 
            $count=1; 
            foreach($timeline_data as $ky => $vl){
                  ?>
                   <tr class="odd gradeX">
=======
              <?php 
			  $count=1; 
			  foreach($timeline_data as $ky => $vl){

               ?>
                <tr class="odd gradeX">
>>>>>>> Stashed changes
                  <td><?php echo $count; ?></td>
                  <td><?php echo $vl['crm_id']?></td>
                   <td><?php echo $vl['paitent_id']?></td>
                  <td>Appointment</td>
                  <td><?php echo $vl['agent']; ?></td>
<<<<<<< Updated upstream
                  <td><?php echo $vl['appoitmented_date']?></td>
                  <td><?php if(!empty($vl['consultation_date'])){ ?>Consultation <?php } ?></td>
                  <td><?php if(!empty($vl['consultation_date'])){ ?><?php echo $vl['agent']; ?><?php } ?></td>
                  <td><?php if(!empty($vl['consultation_date'])){ ?><?php echo $vl['consultation_date']?> <?php } ?></td>
=======
				          <td><?php echo $vl['appoitmented_date']?></td>
                  <td><?php if(!empty($vl['consultation_date'])){ ?>Consultation <?php } ?></td>
                  <td><?php if(!empty($vl['consultation_date'])){ ?><?php echo $vl['agent']; ?><?php } ?></td>
				          <td><?php if(!empty($vl['consultation_date'])){ ?><?php echo $vl['consultation_date']?> <?php } ?></td>
>>>>>>> Stashed changes
                  <td><?php if(!empty($vl['procedure_date'])){ ?>Procedure<?php } ?></td>
                  <td><?php if(!empty($vl['procedure_date'])){ ?><?php echo $vl['agent']; ?><?php } ?></td>
                  <td><?php if(!empty($vl['procedure_date'])){ ?><?php echo $vl['procedure_date']; ?><?php } ?></td>
                  
                </tr>
                  <?php $count++;} ?>
                <tr>
                    <td colspan="5">
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