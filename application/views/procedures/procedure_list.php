
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
       <div class="card-action">
         <h3> Procedure Pricing Management </h3>
      </div>
      <div class="clearfix"></div>
      <div class="card-content">
         <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataList" id="">
               <thead>
                   <tr>
                                <th>Procedure Name</th>
                                <th>Code</th>
                                <th>Min Price</th>
                                <th>Actual Price</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
               </thead>
               <tbody>
                  <?php if(!empty($list)): ?>
                                <?php foreach($list as $row): ?>
                                <tr>
                                    <td>
                                        <div class="font-weight-bold"><?= $row['procedure_name']; ?></div>
                                    </td>
                                     <td>
                                        <div class="font-weight-bold"><?= $row['code']; ?></div>
                                    </td>
                                    <td>
                                        <span class="min-price-cell">
                                            <i class="fas fa-arrow-down mr-1"></i> ₹<?= number_format($row['min_price'], 2); ?>
                                        </span>
                                    </td>
                                    <td class="font-weight-bold text-dark">₹<?= number_format($row['actual_price'], 2); ?></td>
                                    <td>
                                        <?php if($row['status'] == 1): ?>
                                            <span class="badge badge-success badge-status">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary badge-status">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('procedures/procedure_form/'.$row['id']); ?>" class="btn btn-outline-primary btn-sm btn-action" title="Edit Price">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
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