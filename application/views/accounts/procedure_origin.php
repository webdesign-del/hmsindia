 <?php $all_method =&get_instance(); ?>
    <div class="col-md-12">
      <div class="card">
	   <div class="card-action"><h3>Procedure Revenue Reports  </h3></div>
       <div class="clearfix"></div>
	    <form action=""<?php echo base_url().'accounts/procedure_origin'; ?>" method="get">
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
            	<label>Center Of Origin</label>
                <select class="form-control" id="origins" name="origins">
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
            	<label>Procedures Code</label>
                <input type="text" class="form-control" id="json_data" name="json_data" value="<?php echo $data;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>Start Date</label>
              <input type="text" class="particular_date_filter form-control" id="start_date" name="start_date" value="<?php echo $start_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>End Date</label>
                <input type="text" class="particular_date_filter form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
            </div>
            <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            	<label>IIC ID </label>
                <input type="text" class="form-control" id="iic_id" name="iic_id" value="<?php echo $patient_id;?>" />
            </div>
			<div class="col-sm-3" style="margin-top: 30px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            	<a href="<?php echo base_url().'accounts/procedure_origin'; ?>" style="text-decoration: none;">
                <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
               </a>
            	<a href="<?php echo base_url('accounts/Consumption-Report'); ?>" style="text-decoration: none;">
                <button name="export-consumption" type="submit"  class="btn btn-secondary" id="export-consumption">Export</button>
               </a>
            </div>			
            </form>  
        <div class="clearfix"></div>
        <div class="card-content">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="procedure_billing_list">
              <thead>
                <tr>
				  <th>CRM ID</th>
				  <th>IIC ID</th>
                  <th>Patient name</th>
                  <th>On Date</th>
                  <th>Revenue Before Discount</th>
                  <th>Revenue After Discount</th>
				  <th>Amount Received Till Date</th>
				  <th>Center Of Billing</th>
                  <th>Center Of Origin</th>
				  <th>Procedure Name & Code</th>
				  <th>Category</th>
				  <th>Procedures</th>
				  <th>Broad Procedure</th>
				  <th>Doctor Name</th>
				  <th>Lead Source</th>
				  <th>Counselor Name</th>
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
                 <?php 
				  if(!empty($vl['data'])){
					  $procedure_data = unserialize($vl['data']);
					  foreach ($procedure_data['patient_procedures'] as $v2_data){
							$sql1 = "select * from hms_procedures where code='".$v2_data['sub_procedures_code']."'";
							$query = $this->db->query($sql1);
							$select_result1 = $query->result(); 
						  
						  if(!empty($json_data)){
							  
							  if ($json_data == $v2_data['sub_procedures_code']){
									  echo '<tr class="odd gradeX"><td>';

									   $sql = "SELECT * FROM hms_appointments WHERE paitent_id='" . $vl['patient_id'] . "'";
					        $appoint_result = run_select_query($sql);

					        $sql3 = "SELECT * FROM hms_appointments WHERE wife_phone='" . $appoint_result['wife_phone'] . "' and paitent_type='new_patient'";
					        $select_result3 = run_select_query($sql3);
                  
                  echo $select_result3['crm_id'];
				    echo '</td><td>';
									  echo $vl['patient_id'];
									  echo '</td><td>';
										  $patient_name = $all_method->get_patient_name($vl['patient_id']);
									  echo strtoupper($patient_name);
									  echo '</td><td>';
									  echo $vl['on_date'];
									  echo '</td><td>';
									  echo $vl['totalpackage'];
									  echo '</td><td>';
									  echo $vl['fees'];
									  echo '</td><td>';
									  echo $vl['payment_done'];
									  echo '</td><td>';
									  echo $all_method->get_center_name($vl['billing_at']);
									  echo '</td><td>';
									  $sql2 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$vl['origins']."'"; 
										$query = $this->db->query($sql2);
											$select_result2 = $query->result(); 
											foreach ($select_result2 as $res_val2){
												echo '<br/>';
												echo $res_val2->center_name;
											}
									  echo '</td><td>';
											foreach ($select_result1 as $res_val){
												 echo '<br/>';
												 echo $res_val->procedure_name;
												 echo " = ". $v2_data['sub_procedures_code'];
											}
									echo '</td><td>';
									  echo $vl['category'];
									echo '</td><td>';
									 echo $vl['procedures'];
									 echo '</td><td>';
									 echo $vl['broad_procedure'];
									echo '</td>';			
							  }
						  }
						  else{
							  echo '<tr class="odd gradeX"><td>';
					  $sql = "SELECT * FROM hms_appointments WHERE paitent_id='" . $vl['patient_id'] . "'";
					        $appoint_result = run_select_query($sql);

					        $sql3 = "SELECT * FROM hms_appointments WHERE wife_phone='" . $appoint_result['wife_phone'] . "' and paitent_type='new_patient'";
					        $select_result3 = run_select_query($sql3);
                  
                  echo $select_result3['crm_id'];
					 
					   echo '</td><td>';
                  echo $vl['patient_id'];
				   echo '</td><td>';
					      $patient_name = $all_method->get_patient_name($vl['patient_id']);
                      echo strtoupper($patient_name);
					  echo '</td><td>';
			          echo $vl['on_date'];
			          echo '</td><td>';
					  echo $vl['totalpackage'];
					  echo '</td><td>';
					  echo $vl['fees'];
					  echo '</td><td>';
					  echo $vl['payment_done'];
					  echo '</td><td>';
					  echo $all_method->get_center_name($vl['billing_at']);
					  echo '</td><td>';
					  $sql2 = "Select * from ".$this->config->item('db_prefix')."centers where center_number='".$vl['origins']."'"; 
			            $query = $this->db->query($sql2);
                            $select_result2 = $query->result(); 
							foreach ($select_result2 as $res_val2){
								echo '<br/>';
								echo $res_val2->center_name;
							}
						echo '</td><td>';
						    $sql1 = "select * from hms_procedures where code='".$v2_data['sub_procedures_code']."'";
                            $query = $this->db->query($sql1);
                            $select_result1 = $query->result(); 
							foreach ($select_result1 as $res_val){
								 echo '<br/>';
								 echo $res_val->procedure_name;
								 echo " = ". $v2_data['sub_procedures_code'];
							}
						echo '</td><td>';
						  echo $vl['category'];
									echo '</td><td>';
									 echo $vl['procedures'];
									 echo '</td><td>';
									 echo $vl['broad_procedure'];
						echo '</td>';
						  }					  					  
				}
				 }
				  ?>
                  <td><?php 
                  $sql1 = "Select * from ".$this->config->item('db_prefix')."doctor_consultation where appointment_id='".$vl['appointment_id']."'";
	                $select_result1 = run_select_query($sql1);
                  
                  echo $all_method->get_doctor_name($select_result1['doctor_id']); ?></td>
				  <td><?php echo $all_method->get_lead_source($vl['patient_id']); ?></td>
				  <td><?php echo $all_method->get_counselor_name($vl['appointment_id']); ?></td>
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