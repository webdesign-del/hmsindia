<?php $all_method =&get_instance(); ?>
<div class="col-md-12 card">
   <div class="row" style="margin-bottom:20px;">
      <div class="col-md-12">
         <h3> Patient Lists </h3>
      </div>
      <div class="clearfix"></div>
      <form action="<?php echo base_url().'patients/patients'; ?>" method="get">
         <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            <label>Filter by billing at</label>
            <select class="form-control" id="center" name="center">
               <option value=''>--Select From--</option>
               <?php $all_centers = $all_method->get_all_centers();
                  foreach($all_centers as $key => $val){ //var_dump($val);die;
                          if($center == $val['center_number']){
                            echo '<option value="'.$val['center_number'].'" selected>'.$val['center_name'].'</option>';
                          }else{
                          echo '<option value="'.$val['center_number'].'">'.$val['center_name'].'</option>';
                          }
                    	  } 
                  ?>
            </select>
         </div>
         <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            <label>Wife Name</label>
            <input type="text" class="form-control" id="wife_name" name="wife_name" value="<?php echo $wife_name;?>" />
         </div>
         <div class="col-sm-3 col-xs-12" style="margin-top:10px;">
            <label>IIC ID </label>
            <input type="text" class="form-control" id="iic_id" name="iic_id" value="<?php echo $patient_id;?>" />
         </div>
         <div class="col-sm-3" style="margin-top: 30px;">
            <button name="btnsearch" id="btnsearch" type="submit"  class="btn btn-primary">Search</button>
            <a href="<?php echo base_url().'patients/patients'; ?>" style="text-decoration: none;">
            <button name="btnreset" id="btnreset" type="button"  class="btn btn-secondary">RESET</button>
            </a>
         <?php if($_SESSION['logged_administrator']['username']){ ?>
            <a href="<?php echo base_url('patients/Patients'); ?>" style="text-decoration: none;">
            <button name="export-patient" type="submit"  class="btn btn-secondary" id="export-patient">Export Patient</button>
            </a>
         <?php } ?>
         </div>
      </form>
   </div>
   <div class="clearfix"></div>
   <div class="card-content">
      <div class="table-responsive">
         <table class="table table-striped table-bordered table-hover" id="investigation_billing_list">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>IIC ID</th>
                  <th>Patient phone</th>
                  <th>Wife Name</th>
                  <th>Husband Name</th>
                  <th>Nationality</th>
                  <th>Center Name</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody id="investigate_result">
               <?php 
                  $count=1; 
                  foreach($investigate_result as $ky => $vl){
                           $patient_data = get_patient_detail($vl['patient_id']);
                  $currency = '';
                  
                  $sql = "SELECT * FROM hms_appointments WHERE paitent_id='" . $vl['patient_id'] . "'";
                  $appoint_result = run_select_query($sql);
                  
                  $sql3 = "SELECT * FROM hms_appointments WHERE wife_phone='" . $appoint_result['wife_phone'] . "' and paitent_type='new_patient'";
                  $select_result3 = run_select_query($sql3);
                  
                  $sql4 = "SELECT * FROM hms_centers WHERE center_number='" . $select_result3['appoitment_for'] . "'";
                  $select_centers = run_select_query($sql4);
                        ?>
               <tr class="odd gradeX">
                  <td><?php echo $count; ?></td>
                  <td><a href="<?php echo base_url()?>accounts/patient_details/<?php echo $vl['patient_id'];?>"><?php echo $vl['patient_id']; ?></a></td>
                  <td><?php echo sting_masking($vl['patient_phone']); ?></td>
                  <td><?php echo strtoupper($vl['wife_name']); ?></td>
                  <td><?php echo strtoupper($vl['husband_name']); ?></td>
                  <td><?php echo $vl['nationality']?></td>
                  <td><?php echo $select_centers['center_name']; ?>
                     <?php $incomplete = patient_profile($vl['patient_id']);
                        if($incomplete == true){ ?>
                  <td><a href="<?php echo base_url();?>patients/edit/<?php echo $vl['patient_id']?>" class="btn btn-primary">Incomplete profile</a></td>
                  <?php }else{ ?> 
                  <td><a href="<?php echo base_url();?>patients/edit/<?php echo $vl['patient_id']?>" class="btn btn-primary">Edit profile</a></td>
                  <?php }?>
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
<!--End Investigation Tables -->
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