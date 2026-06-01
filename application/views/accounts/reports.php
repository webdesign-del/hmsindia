
 <?php $all_method =&get_instance(); ?>
    <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3>Dashboard  </h3></div>
       <div class="clearfix"></div>
	    <form action=""<?php echo base_url().'accounts/reports'; ?>" method="get">
		     <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Filter by billing at</label>
                <select class="form-control" id="billing_at" name="billing_at">
                	<option value=''>--Select From--</option>
                    <?php $all_centers = $all_method->get_all_centers();
						            foreach($all_centers as $key => $val){ //var_dump($val);die;
                          if($billing_at == $val['center_number']){
                            echo '<option value="'.$val['center_number'].'" selected>'.$val['center_name'].'</option>';
                          }else{
		                        echo '<option value="'.$val['center_number'].'">'.$val['center_name'].'</option>';
                          }
                    	  } 
					    ?>
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
            	<a href="<?php echo base_url().'accounts/reports'; ?>" style="text-decoration: none;">
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
            <th>Name</th>
            <th>Total</th>
            <th>Discount amount</th>
            <th>Net Amount</th>
            <th>Receive amount</th>
        </tr>
    </thead>
    <tbody id="procedure_result">
        <tr>
            <td>Procedure</td>
            <?php 
            $procedure_net = 0;
            $procedure_receive = 0;
            $procedure_total = 0;
            $procedure_discount = 0;
            foreach($procedure_result as $ky => $vl){
                $procedure_net += round($vl['fees'],2);
                $procedure_receive += round($vl['payment_done'],2);
                $procedure_total += round($vl['total_package'],2);
                $procedure_discount += round($vl['discount_amount'],2);
            ?>
            <td><?php echo round($vl['total_package'],2); ?></td>
            <td><?php echo round($vl['discount_amount'],2); ?></td>
            <td><?php echo round($vl['fees'],2); ?></td>
            <td><?php echo round($vl['payment_done'],2); ?></td>
            <?php } ?>
        </tr>
    </tbody>
    <tbody id="procedure_result">
        <tr>
            <td>Partial Payment</td>
            <?php 
            $partial_procesure = 0;    
            foreach($partial_payment as $ky => $vl){
                $partial_procesure = round($vl['paymentreceive'],2);
            ?>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo round($partial_procesure); ?></td>
            <?php } ?>
        </tr>
    </tbody>
    
    <tbody id="investigation_result">
        <tr>
            <td>Investigation</td>
            <?php 
            $investigation_net = 0;
            $investigation_receive = 0;
            $partial_investigation = 0;
            $investigation_total = 0;
            $investigation_discount = 0;
            foreach($partial_payment_investigation as $ky => $vl){
                $partial_investigation = round($vl['paymentinvestigation'],2);
            } 
            foreach($investigation_result as $ky => $vl){
                $investigation_net += round($vl['fees'],2);
                $investigation_receive += round($vl['payment_done'],2);
                $investigation_total += round($vl['total_package'],2);
                $investigation_discount += round($vl['discount_amount'],2);
            ?>
            <td><?php echo round($vl['total_package'],2); ?></td>
            <td><?php echo round($vl['discount_amount'],2); ?></td>
            <td><?php echo round($vl['fees'],2); ?></td>
            <td><?php echo round($vl['payment_done'],2) + $partial_investigation; ?></td>
            <?php } 
            $investigation_receive += $partial_investigation;
            ?>
        </tr>
    </tbody>
    
    <tbody id="consultation_result">
        <tr>
            <td>Consultation</td>
            <?php 
            $consultation_net = 0;
            $consultation_receive = 0;
            $consultation_total = 0;
            $consultation_discount = 0;
            foreach($consultation_result as $ky => $vl){
                $consultation_net += round($vl['fees'],2);
                $consultation_receive += round($vl['payment_done'],2);
                $consultation_total += round($vl['total_package'],2);
                $consultation_discount += round($vl['discount_amount'],2);
            ?>
            <td><?php echo round($vl['total_package'],2); ?></td>
            <td><?php echo round($vl['discount_amount'],2); ?></td>
            <td><?php echo round($vl['fees'],2); ?></td>
            <td><?php echo round($vl['payment_done'],2); ?></td>
            <?php } ?>
        </tr>
    </tbody>
    <tbody id="partial_payment">
        <tr>
            <td>Medicine</td>
            <?php 
            $medicine_net = 0;
            $medicine_receive = 0;
            $medicine_total = 0;
            $medicine_discount = 0;
            foreach($medicine_payment as $ky => $vl){
                $medicine_net += round($vl['fees'] - $vl['discount_amount'],2);
                $medicine_receive += round($vl['payment_done'],2);
                $medicine_total += round($vl['fees'],2);
                $medicine_discount += round($vl['discount_amount'],2);
            ?>
            <td><?php echo round($vl['fees'],2); ?></td>
            <td><?php echo round($vl['discount_amount'],2); ?></td>
            <td><?php echo round($vl['fees'] - $vl['discount_amount'],2); ?></td>
            <td><?php echo round($vl['payment_done'],2); ?></td>
            <?php } ?>
        </tr>
    </tbody>
     <tbody id="partial_payment">
        <tr>
            <td>New Medicine</td>
            <?php 
            $medicine_net = 0;
            $medicine_receive = 0;
            $medicine_total = 0;
            $medicine_discount = 0;
            foreach($medicine_sale_payment as $ky => $vl){
                $medicine_net += round($vl['subtotal'] - $vl['discount_amount'],2);
                $medicine_receive += round($vl['payment_done'],2);
                $medicine_total += round($vl['fees'],2);
                $medicine_discount += round($vl['discount_amount'],2);
            ?>
            <td><?php echo round($vl['subtotal'],2); ?></td>
            <td><?php echo round($vl['discount_amount'],2); ?></td>
            <td><?php echo round($vl['subtotal'] - $vl['discount_amount'],2); ?></td>
            <td><?php echo round($vl['payment_done'],2); ?></td>
            <?php } ?>
        </tr>
    </tbody>
    <tbody id="registration">
        <tr>
            <td>Registration</td>
            <?php 
            $registration_net = 0;
            $registration_receive = 0;
            $registration_total = 0;
            $registration_discount = 0;
            foreach($registration_payment as $ky => $vl){
                $registration_net += round($vl['paymentregistration'],2);
                $registration_receive += round($vl['paymentregistration'],2);
                $registration_total += round($vl['total_package'],2);
                $registration_discount += round($vl['discount_amount'],2);
            ?>
            <td><?php echo round($vl['total_package'],2); ?></td>
            <td><?php echo round($vl['discount_amount'],2); ?></td>
            <td><?php echo round($vl['paymentregistration'],2); ?></td>
            <td><?php echo round($vl['paymentregistration'],2); ?></td>
            <?php } ?>
        </tr>
    </tbody>
    <tbody id="registration">
        <tr>
            <td>Total</td>
            <td><?php echo $procedure_total + $investigation_total + $consultation_total + $medicine_total + $registration_total; ?></td>
            <td><?php echo $procedure_discount + $investigation_discount + $consultation_discount + $medicine_discount + $registration_discount; ?></td>
            <td><?php echo $procedure_net + $investigation_net + $consultation_net + $medicine_net + $registration_net; ?></td>
            <td><?php echo $procedure_receive + $partial_procesure + $investigation_receive + $consultation_receive + $medicine_receive + $registration_receive; ?></td>
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
apps-fileview.texmex_20250919.05_p2
reports.php
Displaying reports.php.