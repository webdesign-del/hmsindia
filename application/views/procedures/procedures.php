<?php $all_method =&get_instance(); ?>
<div class="col-md-12">
   <style>
      a#upload_procedures {
      text-align: right;
      width: 100%;
      display: block;
      }
      #upload_procedures {
      color: #000;
      text-decoration: none;
      }
   </style>
   <!-- Advanced Tables -->
   <div class="card">
      <div class="card-action stock_upload_box">
         <a href="javascript:void(0)" id="upload_procedures" title="Upload stock"><i class="fa fa-upload  fa-lg" aria-hidden="true"></i> Upload Procedures</a>
         <div class="show_upload" style="display:none;">
            <form method="post" action="" enctype="multipart/form-data">
               <input type="hidden" name="upload_procedures" value="upload_procedures" />
               <input type="file" name="procedures_list" class="up_file" required />
               <input type="submit" value="Upload CSV file only" class="btn btn-primary up_btn" />
            </form>
            <a href="<?php echo base_url();?>assets/procedure_files/sample_procedure.csv" download>Download sample</a>
         </div>
      </div>
      <div class="card-action">
         <h3> Procedures Lists </h3>
      </div>
      <div class="clearfix"></div>
      <div class="card-content">
         <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataList" id="">
               <thead>
                  <tr>
                     <th>Procedure</th>
                     <th>Type</th>
                     <th>Code</th>
                     <th>Procedures</th>
                     <th>Broad Procedure</th>
                     <th>Broad Procedure Count</th>
                     <th>Price (Rupee)</th>
                     <th>Price (USD)</th>
                     <th>Center</th>
                     <th>Status</th>
                     <?php if (isset($_SESSION['logged_administrator']) && 
                        $_SESSION['logged_administrator']) { ?>
                     <th>Action</th>
                     <?php } ?>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($data as $ky => $vl){ ?>
                  <tr class="odd gradeX">
                     <td><?php echo $vl['procedure_name']?></td>
                     <td><?php echo $vl['category']?></td>
                     <td><?php echo $vl['code']?></td>
                     <td><?php echo $vl['procedures']?></td>
                     <td><?php echo $vl['broad_procedure']?></td>
                     <td><?php echo $vl['broad_procedure_count']?></td>
                     <!-- <td><?php if($vl['parent_id'] == 0){echo '';}else{ $procedure_parent = $all_method->get_parent_procedure($vl['parent_id']); echo $procedure_parent['procedure_name'];} ?></td> -->
                     <td><?php echo '<i class="fa fa-inr" aria-hidden="true"></i> '.$vl['price']; ?></td>
                     <td><?php echo '<i class="fa fa-usd" aria-hidden="true"></i> '.$vl['usd_price']?></td>
                     <td>
                        <?php 
                           if (!empty($vl['center_names'])) {
                              $centers = explode(',', $vl['center_names']);
                              foreach($centers as $c){
                                 echo '<span class="badge badge-primary" style="margin:2px;">'.$c.'</span>';
                              }
                           }
                        ?>
                     </td>
                     <td><?php if($vl['status'] == '1'){echo "Active";}else{echo "Inactive"; } ?></td>
                     <td><a href="<?php echo base_url();?>procedures/edit?id=<?php echo $vl['ID']?>" class="edit"><i class="material-icons">edit</i></a> <a href="<?php echo base_url();?>procedures/delete?id=<?php echo $vl['ID']?>" class="delete"><i class="material-icons">delete</i></a></td>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!--End Advanced Tables -->
</div>
<script>
   $( "#upload_procedures" ).click(function() {
     $( ".show_upload" ).toggle( "slow", function() {
   	// Animation complete.
     });
   });
     
</script>