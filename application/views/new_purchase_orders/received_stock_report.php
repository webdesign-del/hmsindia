<?php $all_method =&get_instance(); ?>

<div class="col-md-12">
   <div class="card" style="margin-bottom:20px;">
     <div class="col-md-12">
        <h3><i class="fa fa-list"></i> Received Stock Report</h3>
        <small>This report shows all individual stock receiving transactions from purchase orders.</small>
     </div>
     <div class="clearfix"></div>
     
     <!-- Filters Section -->
     <form class="row" style="margin: 0px !important; margin-top: 20px !important;" action="<?php echo base_url('new_purchase_orders/received_stock_report'); ?>" method="get">
       <div class="col-sm-3 col-xs-12">
          <div class="form-group">
             <label for="po_number">PO Number</label>
             <input type="text" class="form-control" id="po_number" name="po_number" 
                value="<?php echo htmlspecialchars($filters['po_number'] ?? ''); ?>" placeholder="Search PO">
          </div>
       </div>
       <div class="col-sm-3 col-xs-12">
          <div class="form-group">
             <label for="vendor_id">Vendor</label>
             <select class="form-control" id="vendor_id" name="vendor_id">
                <option value="">All Vendors</option>
                <?php if (!empty($vendors)): ?>
                   <?php foreach ($vendors as $vendor): ?>
                       <option value="<?= $vendor['ID']; ?>" 
                           <?= (($filters['vendor_id'] ?? '') == $vendor['ID']) ? 'selected' : ''; ?>>
                           <?= $vendor['name']; ?> 
                       </option>
                   <?php endforeach; ?>
                <?php endif; ?>
             </select>
          </div>
       </div>
       <div class="col-sm-2 col-xs-12">
          <div class="form-group">
             <label for="start_date">Start Date</label>
             <input type="date" class="form-control" 
                id="start_date" name="start_date" 
                value="<?php echo htmlspecialchars($filters['start_date'] ?? ''); ?>" />
          </div>
       </div>
       <div class="col-sm-2 col-xs-12">
          <div class="form-group">
             <label for="end_date">End Date</label>
             <input type="date" class="form-control" 
                id="end_date" name="end_date" 
                value="<?php echo htmlspecialchars($filters['end_date'] ?? ''); ?>" />
          </div>
       </div>
       <!-- Buttons -->
       <div class="col-sm-2 col-xs-12">
          <label>&nbsp;</label>
          <div class="form-group">
             <button type="submit" name="btnsearch" id="btnsearch" class="btn btn-primary">
                <i class="fa fa-search"></i> Search
             </button>
             <a href="<?php echo base_url('new_purchase_orders/received_stock_report'); ?>" class="btn btn-default">
                <i class="fa fa-refresh"></i> Reset
             </a>
          </div>
       </div>
     </form>
     <div class="clearfix"></div>
     
     <div class="card-content">
        <div class="table-responsive">
           <table class="table table-striped table-bordered table-hover" id="received_items_table">
              <thead>
                <tr>
                    <th>Received Date</th>
                    <th>PO Number</th>
                    <th>Vendor</th>
                    <th>Center</th>
                    <th>Item Name</th>
                    <th>Item Code</th>
                    <th>Batch #</th>
                    <th>Qty Received</th>
                    <th>Vendor Price</th>
                    <th>Vendor Price With GST</th>
                    <th>Receive By</th>
                    <th>file</th>
                    <th>Total Value</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($received_items)): ?>
                    <?php foreach ($received_items as $item): ?>
                        <tr class="odd gradeX">
                            <td><?php echo date('d-m-Y H:i', strtotime($item->received_date)); ?></td>
                            <td><strong><?php echo htmlspecialchars($item->po_number); ?></strong></td>
                            <td><?php echo htmlspecialchars($item->vendor_name); ?></td>
                            <td><?php echo htmlspecialchars($item->center_name); ?></td>
                            <td><?php echo htmlspecialchars($item->medicine_name); ?></td>
                            <td><?php echo htmlspecialchars($item->item_number); ?></td>
                            <td><?php echo htmlspecialchars($item->batch_number); ?></td>
                            <td><strong class="text-success"><?php echo $item->quantity_change; ?></strong></td>
                            <td><?php echo number_format($item->unit_price, 2); ?></td>
                            <td><?php echo number_format($item->vendor_price_with_tax, 2); ?></td>
                            <td><?php echo htmlspecialchars($item->receive_by); ?></td>
                            <td>
                                 <?php 
                                 if (!empty($item->uploaded_files)) {
                                    $files = json_decode($item->uploaded_files); // decode JSON into array
                                    
                                    if (!empty($files)) {
                                          foreach ($files as $file) {
                                             $file_url = base_url($file);
                                             $file_name = basename($file);

                                             echo '<div style="margin-bottom:5px;">
                                                      <a href="'.$file_url.'" target="_blank" style="color:#007bff; text-decoration:none;">View</a>
                                                      |
                                                      <a href="'.$file_url.'" download="'.$file_name.'" style="color:#28a745; text-decoration:none;">Download</a>
                                                   </div>';
                                          }
                                    } else {
                                          echo 'No files';
                                    }
                                 } else {
                                    echo 'No files';
                                 }
                                 ?>
                              </td>
                             <td><?php echo number_format($item->total_value, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                         <td colspan="10" class="text-center text-muted">
                            <i class="fa fa-inbox fa-3x" style="margin-bottom: 10px;"></i>
                            <br>No received items found matching your criteria.
                         </td>
                    </tr>
                <?php endif; ?>
              </tbody>
           </table>
        </div>
     </div>
   </div>
</div>

<script>
$(document).ready(function() {
    // --- THIS IS THE FIX ---
    // Only initialize DataTables IF there are items to show
    <?php if (!empty($received_items)): ?>
    $('#received_items_table').DataTable({
        "paging": true,
        "pageLength": 25,
        "searching": false, 
        "info": true, 
        "order": [[ 0, "desc" ]], 
        "responsive": true,
        "language": {
            "emptyTable": "No received items found"
        }
    });
    <?php endif; ?>
    // --- END OF FIX ---
});
</script>