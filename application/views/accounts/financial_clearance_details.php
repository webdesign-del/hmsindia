 <?php $all_method =&get_instance(); 
 
 
 
 ?>
    <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3> Procedure Financial Clearance</h3></div>
       <div class="clearfix"></div>
	      <div class="clearfix"></div>
        <div class="card-content">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="procedure_billing_list">
              <thead>
                <tr>
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
                  <th>Accounts</th>
                </tr>
              </thead>
              <tbody id="procedure_result">
                <tr class="odd gradeX">
                  <td><?php echo $data_result['patient_id']; ?></td>
                  <td><?php 
                    $patient_name = $all_method->get_patient_name($data_result['patient_id']);
                    echo strtoupper($patient_name); ?></td>
                  <td><?php echo $data_result['receipt_number']; ?></td>
                  <td><?php echo $data_result['on_date']?></td>
                  <td><?php echo $data_result['totalpackage']?></td>
                  <td><?php echo $data_result['discount_amount']?></td>
				          <td><?php echo $data_result['fees']?></td>
				          <td><?php echo $data_result['payment_done']?></td>
                  <td><?php echo $all_method->get_center_name($data_result['billing_at']); ?></td>
				          <td><?php echo $all_method->get_center_name($data_result['origins']); ?></td>
				          <td><?php 
				              $sql2 = "Select * from ".$this->config->item('db_prefix')."employees where employee_number='".$data_result['biller_id']."'"; 
			                $query = $this->db->query($sql2);
                            $select_result3 = $query->result(); 
                            foreach ($select_result3 as $res_val3){
                              echo '<br/>';
                              echo $res_val3->name;
                            }
					      	?></td>
				          <td><?php echo $data_result['procedure_name']; ?></td>
                  <td><?php echo $data_result['councellor']; ?></td>
                  <td><?php echo $data_result['status']; ?></td>
                </tr>
             <?php foreach($partial_data_result as $ky => $vl){ ?>
                <tr class="odd gradeX">
                  <td><?php echo $vl['patient_id']; ?></td>
                  <td><?php $patient_name = $all_method->get_patient_name($vl['patient_id']);
                    echo strtoupper($patient_name); ?>
                  </td>
                  <td><?php echo $vl['billing_id']?></td>
                  <td><?php echo $data_result['on_date']?></td>
                  <td></td>
                  <td></td>
				          <td></td>
				          <td><?php echo $vl['payment_done']?></td>
                  <td><?php echo $all_method->get_center_name($vl['billing_at']); ?></td>
                  <td><?php echo $all_method->get_center_name($data_result['origins']); ?></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><?php echo $vl['status']?></td>
                </tr>
              <?php } ?>
              
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