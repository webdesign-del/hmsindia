 <?php $all_method =&get_instance(); ?>
    <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3> Procedure Financial Clearance</h3></div>
       <div class="clearfix"></div>
	    <form action="<?php echo base_url().'accounts/patient_financial_clearance'; ?>" method="get">
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
            <div class="col-sm-2 col-xs-12" style="margin-top:10px;">
            	<label>End Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>
            <div class="col-sm-2 col-xs-12" style="margin-top:10px;">
            	<label>IIC ID </label>
                <input type="text" class="form-control" id="iic_id" name="iic_id" value="<?php echo $patient_id;?>" />
            </div>
			<div class="col-sm-1" style="margin-top: 10px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 10px;">
            	<a href="<?php echo base_url().'accounts/patient_financial_clearance'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
            </div>
            <div class="col-sm-2" style="margin-top: 10px;">
            	<a href="<?php echo base_url('accounts/procedure-reports'); ?>" style="text-decoration: none;">
                <button name="export-billing" type="submit"  class="btn btn-secondary" id="export-billing">Export Report</button>
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
                  <th>Patient name</th>
                  <th>Receipt number</th>
                  <th>On Date</th>
                  <th>Total</th>
                  <th>Discount Amount</th>
				          <th>Discount Package</th>
				          <th>Receive Amount</th>
                  <th>Center</th>
				          <th>Origins</th>
				          <th>Employee Name</th>
				          <th>Procedure</th>
				          <th>FC / CH</th>
                  <th>User</th>
                  <th>User / Doctor /  Embryologist</th>
                  <th>Account</th>
                </tr>
              </thead>
              <tbody id="procedure_result">
              <?php 
			  $total_totalpackage = 0;
              $total_discount_amount = 0;
			  $total_payment_done = 0;
			  $count=1; foreach($procedure_result as $ky => $vl){
                $patient_data = get_patient_detail($vl['patient_id']);
						    $currency = '';
                $current_balance = $all_method->get_current_balance($vl['patient_id']); ?>
                <tr class="odd gradeX">
                  <td><?php echo $count; ?></td>
                  <td><?php 
                  $sql1 = "Select * from ".$this->config->item('db_prefix')."appointments where ID='".$vl['appointment_id']."'";
	                $select_appoint = run_select_query($sql1);
                  
                  echo $select_appoint['crm_id']; ?></td>
                  <td><a href="<?php echo base_url().'accounts/procedure_advice'; ?>/<?php echo $vl['patient_id']; ?>"><?php echo $vl['patient_id']; ?></a></td>
                  <td><?php 
                    $patient_name = $all_method->get_patient_name($vl['patient_id']);
                    echo strtoupper($patient_name); ?>
                  </td>
                  <td><a href="<?php echo base_url().'accounts/details';?>/<?php echo $vl['receipt_number']?>?t=procedure"><?php echo $vl['receipt_number']; ?></a></td>
                  <td><?php echo $vl['on_date']?></td>
                  <td><?php echo $vl['totalpackage']?></td>
                  <td><?php echo $vl['discount_amount']?></td>
				          <td><?php echo $vl['fees']?></td>
				          <td><?php echo $vl['payment_done']?></td>
                  <td><?php echo $all_method->get_center_name($vl['billing_at']); ?></td>
				          <td><?php 
				      $sql2 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$vl['origins']."'"; 
			            $query = $this->db->query($sql2);
                            $select_result2 = $query->result(); 
							foreach ($select_result2 as $res_val2){
								echo '<br/>';
								echo $res_val2->center_name;
							}
						?></td>
				  <td><?php 
				      $sql2 = "Select * from ".$this->config->item('db_prefix')."employees where employee_number='".$vl['biller_id']."'"; 
			            $query = $this->db->query($sql2);
                            $select_result3 = $query->result(); 
							foreach ($select_result3 as $res_val3){
								echo '<br/>';
								echo $res_val3->name;
							}
						?></td>
				    <td><?php echo $vl['procedure_name']; ?></td>
            <td><?php echo $vl['councellor']; ?></td>
            <td></td>
            <td></td>
                </tr>
              <?php $count++;} ?>
               <tr>
                <td colspan="5">
                <p class="custom-pagination"><?php echo $links; ?></p>
               
              </tr>
              </tbody>			  
            </table>
          </div>
        </div>
      </div>
     </div>
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


      function searchFilter(){
          var employee_name = $("#employee_number").val();
          //top.location.href = '/stocks/stocks_reports?employee_number='+employee_name;
      }

</script>