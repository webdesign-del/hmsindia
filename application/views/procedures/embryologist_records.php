 <?php $all_method =&get_instance(); ?>
    <div class="col-md-12">
      <div class="card">
	  <?php if (isset($_SESSION['logged_embryologist']) && !empty($_SESSION['logged_embryologist'])) { ?>
	  
	   <div class="card-action"><h3>Embryologist records</h3></div>
	  <?php }else{ ?>
	  <div class="card-action"><h3>Patient Details</h3></div>
	  <?php } ?>
	   
       <div class="clearfix"></div>
	    <form action=""<?php echo base_url().'procedures/embryologist_records'; ?>" method="get">
		    <div class="col-sm-2 col-xs-12">
            	<label>IIC ID </label>
                <input type="text" class="form-control" id="patient_id" name="patient_id" value="<?php echo $patient_id;?>" />
            </div>
			<div class="col-sm-1" style="margin-top: 20px;">
            	<button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            </div>
            <div class="col-sm-1" style="margin-top: 20px;">
            	<a href="<?php echo base_url().'procedures/embryologist_records'; ?>" style="text-decoration: none;">
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
				  <th>IIC ID</th>
                  <th>Patient name</th>
                  <th>Appointment Date</th>
					<?php if (isset($_SESSION['logged_embryologist']) && !empty($_SESSION['logged_embryologist'])) { ?>
                  <th>Reports</th>
					<?php } ?>
                </tr>
              </thead>
              <tbody id="procedure_result">
              <?php 
			 $count=1;
			   foreach($embryologist_result as $ky => $vl){
                $patient_data = get_patient_detail($vl['patient_id']);

				$sql1 = "SELECT * FROM hms_appointments WHERE ID=".$vl['appointment_id']."";
                        $query = $this->db->query($sql1);
						 $select_result3 = $query->result(); 
						 foreach ($select_result3 as $res_val) { 
					 ?>
					 	
                  <?php  
					     echo '<tr class="odd gradeX">';
                         echo '<td>','<a href="'.base_url().'accounts/patient_details/'.$vl['patient_id'].'?appoitmented_date='.$res_val->appoitmented_date.'" target="_blank">'.$vl['patient_id'].'</a>','</td>';
						 echo '<td>'.$patient_data['wife_name'].'</td>';
                         echo '<td>'.$vl['on_date'].'</td>';
						 if(!empty($vl['data'])){
							$procedure_data = unserialize($vl['data']);
							   foreach ($procedure_data['patient_procedures'] as $v2_data){
								 $sql1 = "select * from hms_procedures where code='".$v2_data['sub_procedures_code']."'";
								 $query = $this->db->query($sql1);
								  $select_result1 = $query->result(); 
								   if (isset($_SESSION['logged_embryologist']) && !empty($_SESSION['logged_embryologist'])) {
						 echo '<td>','<a href="'.base_url().'procedure_reports/'.$vl['appointment_id'].'" class="btn btn-primary">Procedure Report</a>','</td>';
						 }
					}
				}
						 echo '</tr>';
				  } 
				  ?>
                 
               
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
      <!--End Advanced Tables -->
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