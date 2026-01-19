 <?php $all_method =&get_instance(); ?>
    <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3>Sign Consent Book List  </h3></div>
       <div class="clearfix"></div>
	    <form action=""<?php echo base_url().'doctors/consent_book_list'; ?>" method="get">
		     <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Consent Book Name</label>
               <select id="consent_book_name" name="consent_book_name" class="select2" >
			    <option value="">- - - Select - - -</option>
				<option value="consent_surrogacy">Consent Surrogacy</option>
				<option value="divorced_widow">Divorced/Widow</option>
				<option value="agreement_surrogacy">Agreement for Surrogacy</option>
				<option value="couple_availing_surrogacy">Couple for Availing Surrogacy</option>
				<option value="fitness_surrogate_mother">Fitness of Surrogate Mother</option>
				<option value="consent_withdrawal">Consent Form for Withdrawal</option>
				<option value="screening_surrogate">Screening of the Surrogate</option>
				<option value="acknowledgment">Acknowledgment</option>
				<option value="admission_form">Admission Form</option>
				<option value="ipd_admission_form">IPD Admission Form</option>
				<option value="anesthesia_consent">Anesthesia Consent Form</option>
				<option value="ot_consent">OT Consent Form</option>
				<option value="ovarian_stem_cell">Ovarian Stem Cell</option>
				<option value="consent_blastocyst">Consent for Blastocyst</option>
				<option value="consent_embryo_glue">Consent for Embryo Glue</option>
				<option value="consent_icsi">Consent for ICSI</option>
				<option value="consent_lah">Consent for LAH</option>
				<option value="consent_microfluidics_sperm">Consent for Microfluidics Sperm Selection</option>
				<option value="consent_pgt">Consent for PGT</option>
				<option value="consent_sperm_mobil">Consent for Sperm Mobilization</option>
				<option value="consent_thawing_gametes">Consent for Thawing of Gametes</option>
				<option value="couple_woman_consent">Couple/Woman Consent</option>
				<option value="iui_husbands_semen">IUI - Husband's Semen</option>
				<option value="iui_donors_semen">IUI - Donor's Semen</option>
				<option value="iui_donors_semen_single">IUI - Donor's Semen (Single Woman)</option>
				<option value="embryo_freezing">Embryo Freezing</option>
				<option value="sperm_oocyte_freezing">Sperm/Oocyte Freezing</option>
				<option value="parental_freezing_consent">Parental Freezing Consent (Minor)</option>
				<option value="oocyte_retrieval">Oocyte Retrieval</option>
				<option value="embryo_transfer">Embryo Transfer</option>
				<option value="consent_donor_eggs">Consent for Egg Donation</option>
				<option value="consent_donor_sperm">Consent for Sperm Donation</option>
				<option value="posthumous_retrieval_sperm">Posthumous Retrieval of Sperm</option>
				<option value="consent_withdrawal_form">Consent Form for Withdrawal</option>
				<option value="process_risk_consent_art">Process, Risk, and Consent for ART</option>
				<option value="recipient_couple_donor_egg">Recipient Couple - Donor Egg</option>
				<option value="instructions_consent_semen">Instructions & Consent for Semen Collection</option>
				<option value="pesa_tesa_tese_micro_tese">PESA/TESA/TESE/Micro TESE</option>
				<option value="ovarian_prp">Ovarian PRP</option>
				<option value="uterine_prp">Uterine PRP</option>
				<option value="testicular_prp">Testicular PRP</option>
				<option value="testimonial">Testimonial</option>
				<option value="stimulation_low_ovarian_reserve">Stimulation of Low Ovarian Reserve Females</option>
			</select>
            </div>
			<div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Start Date</label>
              <input type="text" class="particular_date_filter form-control" id="start_date" name="start_date" value="<?php echo $start_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>End Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<a href="<?php echo base_url().'doctors/consent_book_list'; ?>" style="text-decoration: none;">
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
                  <th>Patient Id</th>
                  <th>Patient Name</th>
                  <th>Consent Book Name</th>
                  <th>File</th>
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
				  <td><?php echo $vl['patient_id']?></td>
				  <td><?php echo $all_method->get_patient_name($vl['patient_id']); ?></td>
				  <td><?php echo $vl['consent_book_name']?></td>
                  <td><a href="<?php echo $vl['transaction_img']; ?>" target="_blank"><?php echo $vl['transaction_img']; ?></a></td>
                  <td><?php echo $vl['on_date']?></td>
				</tr>
              <?php $count++;} ?>
			   <tr>
                <td colspan="7">
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
		 // turn the element to select2 select style
	  $('.select2').select2({
		placeholder: "Select Consent Book Name."
	  }).on('change', function(e) {
		var data = $(".select2 option:selected").val();
			
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