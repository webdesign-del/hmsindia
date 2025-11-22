<?php $all_method =&get_instance();  ?>
 	<div class="card">
      <!-- Advanced Tables -->
 <div class="row card-content" style="margin-bottom:20px;">
      <div class="col-md-12"><h3>Advance Payment List</h3></div>
      <div class="clearfix"></div>
        <form action="<?php echo base_url().'accounts/advance_payment_list'; ?>" method="get">
		        <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Patient Id</label>
                <input type="text" class="form-control" id="patient_id" name="patient_id" value="<?php echo $training_name;?>" />
            </div>
            <div class="col-sm-2" style="margin-top: 30px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
              <a href="<?php echo base_url().'accounts/advance_payment_list'; ?>" style="text-decoration: none;">
				<button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
				</a>
            </div>
           
            </form>  
        </div>

       <!--Procedure Tables -->

   		  <div class="card">

         <div class="clearfix"></div>

        <div class="card-content">

          <div class="table-responsive">

            <table class="table table-striped table-bordered table-hover" id="">

              <thead>

                <tr>

                  <th>S.No.</th>

                  <th>Patient Id</th>

                  <th>Amount</th>

                  <th>Payment Mode</th>

                  <th>Transaction Id</th>

                  <th>Payment Date</th>

                  <th>Center</th>
				  
				</tr>

              </thead>

              <tbody id="procedure_result">
			  
			  <?php $count=1; foreach($consultation_cancel_result as $ky => $vl){ ?>
                <tr class="odd gradeX">

                  <td><?php echo $count; ?></td>

                  <td><?php echo $vl['patient_id']; ?></td>

                  <td><?php echo $vl['payment_done']; ?></td>

                  <td><?php echo $vl['payment_mode']; ?></td>

                  <td><?php echo $vl['transaction_id']; ?></td>

                  <td><?php echo $vl['payment_date']; ?></td>

                  <td><?php echo $vl['center']; ?></td>
				  
				</tr>

              <?php $count++;} ?>
			   <tr>
                <td colspan="12">
                <p class="custom-pagination"><?php echo $links; ?></p>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!--End Procedure Tables -->
    </div>
<style>
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