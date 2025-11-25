<?php $all_method =&get_instance(); ?>
<div class="col-md-12">
   <div class="card" style="margin-bottom:20px;">
      <div class="col-md-12">
         <h3><i class="fa fa-shopping-cart "></i> New Purchase Orders</h3>
      </div>
      <div class="clearfix"></div>
      
      <div class="clearfix"></div>
      
      <!-- Filters Section -->
      <form class="row" style="margin: 0px !important; margin-top: 20px !important;" action="<?php echo base_url('new_purchase_orders'); ?>" method="get">
         <div class="col-sm-2 col-xs-12">
            <div class="form-group">
               <label for="status">Status</label>
               <select class="form-control" id="status" name="status">
                  <option value="">All Status</option>
                  <option value="pending" <?php echo ($filters['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                  <option value="approved" <?php echo ($filters['status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                  <option value="rejected" <?php echo ($filters['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                  <option value="completed" <?php echo ($filters['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
               </select>
            </div>
         </div>
         <div class="col-sm-2 col-xs-12">
            <div class="form-group">
               <label for="po_number">PO Number</label>
               <input type="text" class="form-control" id="po_number" name="po_number" 
                  value="<?php echo $filters['po_number']; ?>" placeholder="Search PO">
            </div>
         </div>
         <div class="col-sm-2 col-xs-12">
            <div class="form-group">
               <label for="start_date">Start Date</label>
               <input type="text" class="form-control particular_date_filter" 
                  id="start_date" name="start_date" 
                  value="<?php echo $filters['start_date']; ?>" />
            </div>
         </div>
         <div class="col-sm-2 col-xs-12">
            <div class="form-group">
               <label for="end_date">End Date</label>
               <input type="text" class="form-control particular_date_filter" 
                  id="end_date" name="end_date" 
                  value="<?php echo $filters['end_date']; ?>" />
            </div>
         </div>
         <div class="col-sm-2 col-xs-12">
            <div class="form-group">
               <label for="vendor_number">Vendor</label>
               <select class="form-control" id="vendor_number" name="vendor_number">
                  <option value="">All Vendors</option>
                  <?php if (!empty($vendors)): ?>
                     <?php foreach ($vendors as $vendor): ?>
                        <option value="<?php echo $vendor['ID']; ?>" <?php echo ($filters['vendor_number'] == $vendor['ID']) ? 'selected' : ''; ?>>
                           <?php echo htmlspecialchars($vendor['name']); ?>
                        </option>
                     <?php endforeach; ?>
                  <?php endif; ?>
               </select>
            </div>
         </div>
         <!-- Buttons -->
         <div class="col-sm-12 text-right">
            <div class="form-group">
               <button type="submit" name="btnsearch" id="btnsearch" class="btn btn-primary">
                  <i class="fa fa-search"></i> Search
               </button>
               <a href="<?php echo base_url('new_purchase_orders'); ?>" class="btn btn-default">
                  <i class="fa fa-refresh"></i> Reset
               </a>
               <a href="<?php echo base_url('new_purchase_orders/add'); ?>" class="btn btn-success">
                  <i class="fa fa-plus"></i> Add New PO
               </a>
               <a href="<?php echo base_url('new_purchase_orders/export_csv?' . http_build_query($filters)); ?>" class="btn btn-info">
                    <i class="fa fa-file-excel-o"></i> Export CSV
                </a>
                  <!-- <a href="<?php echo base_url('new_purchase_orders/purchase_order_receipt'); ?>" class="btn btn-info">
                     <i class="fa fa-file-text -o"></i> Purchase Order Receipt
                  </a> -->
               <!-- <a href="<?php echo base_url('new_purchase_orders/stock_transfer'); ?>" class="btn btn-warning">
                  <i class="fa fa-exchange"></i> Stock Transfer
               </a> -->
            </div>
         </div>
      </form>
      <div class="clearfix"></div>
      
      <!-- Flash Messages -->
      <?php if ($this->session->flashdata('success')): ?>
         <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert">
               <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
         </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('error')): ?>
         <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert">
               <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
         </div>
      <?php endif; ?>
      <div class="card-content">
         <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="new_purchase_orders_list">
               <thead>
                  <tr>
                     <th>PO Number</th>
                     <th>Vendor</th>
                     <th>Bill To</th>
                     <th>Ship To</th>
                     <th>Department</th>
                     <th>Total Amount</th>
                     <th>Status</th>
                     <th>Add stock</th>
                     <th>Created Date</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if (!empty($purchase_orders)): ?>
                     <?php foreach ($purchase_orders as $po): ?>
                        <?php if (!is_array($po)) continue; // Skip if not an array ?>
                        <tr class="odd gradeX">
                           <td>
                              <strong><?php echo !empty($po['po_number']) ? $po['po_number'] : 'N/A'; ?></strong>
                           </td>
                           <?php
                           $all_method->load->model('Vendors_model');
                           $vendor_data = $all_method->Vendors_model->get_vendor_name_by_vendor_id($po['vendor_number']);
                           // $vendor_name = (!empty($vendor_data) && isset($vendor_data[0]['name'])) ? $vendor_data[0]['name'] : 'N/A';
                           ?>
                           <td><?php echo $vendor_data->name; ?></td>
                           <?php
                            $ship_to = $all_method->get_center_name($po['ship_to']);
                            $bill_to = $all_method->get_center_name($po['bill_to']);
                            $ship_to = !empty($ship_to) ? $ship_to : 'N/A';
                            $bill_to = !empty($bill_to) ? $bill_to : 'N/A';
                           ?>
                           <td><?php echo $bill_to; ?></td>
                           <td><?php echo $ship_to; ?></td> 
                           <td><?php echo !empty($po['department']) ? $po['department'] : 'N/A'; ?></td>
                           <td>
                              <span class="label label-info">
                                 ₹<?php echo number_format(!empty($po['total_amount']) ? $po['total_amount'] : 0, 2); ?>
                              </span>
                           </td>
                           <td>
                              <?php 
                              $status_class = '';
                              $status_text = '';
                              $status = !empty($po['status']) ? $po['status'] : 'pending';
                              switch($status) {
                                 case 'pending':
                                    $status_class = 'label-warning';
                                    $status_text = 'Pending';
                                    break;
                                 case 'approved':
                                    $status_class = 'label-success';
                                    $status_text = 'Approved';
                                    break;
                                 case 'rejected':
                                    $status_class = 'label-danger';
                                    $status_text = 'Rejected';
                                    break;
                                 case 'completed':
                                    $status_class = 'label-info';
                                    $status_text = 'Completed';
                                    break;
                                 default:
                                    $status_class = 'label-default';
                                    $status_text = 'Unknown';
                                    break;
                              }
                              ?>
                              <span class="label <?php echo $status_class; ?>">
                                 <?php echo $status_text; ?>
                              </span>
                           </td>
                           <td>
                                 <?php
                                    $is_fully_received = false;
                                    if ($status == 'approved' || $status == 'completed') {
                                          $is_fully_received = $all_method->New_purchase_order_model->is_po_fully_received($po['id']);
                                    }
                                    if (($status == 'approved' || $status == 'completed') && $user_role == 'central_stock_manager' && !$is_fully_received):
                                    ?>
                                    <a href="<?php echo base_url('new_purchase_orders/new_add_stock/' . (!empty($po['id']) ? $po['id'] : '0')); ?>" class="btn btn-info">
                                       <i class="fa fa-file-text-o"></i>Add stocks
                                    </a>
                                 <?php elseif ($is_fully_received): ?>
                                       <button class="btn btn-success" disabled title="All items for this PO have been received">
                                          <i class="fa fa-check-circle"></i> Fully Received
                                       </button>
                                 <?php else: ?>
                                       <button class="btn btn-info" disabled title="Add Stock - Only available for approved/completed orders">
                                          <i class="fa fa-file-text-o"></i>Add stocks
                                       </button>
                                 <?php endif; ?>
                           </td>   
                           <!-- <td>
                              <?php if ($status == 'completed' && $user_role == 'central_stock_manager'): ?>
                                 <a href="<?php echo base_url('new_purchase_orders/new_add_stock/' . (!empty($po['id']) ? $po['id'] : '0')); ?>" class="btn btn-info">
                                    <i class="fa fa-file-text-o"></i>Add stocks
                                 </a>
                              <?php else: ?>
                                 <button class="btn btn-info" disabled title="Add Stock - Only available for completed orders">
                                    <i class="fa fa-file-text-o"></i>Add stocks
                                 </button>
                              <?php endif; ?>
                           </td> -->
                           <td><?php echo !empty($po['created_at']) ? date('d/m/Y H:i', strtotime($po['created_at'])) : 'N/A'; ?></td>
                           <td>
                              <div class="btn-group" role="group">
                                 <a href="<?php echo base_url('new_purchase_orders/view/' . (!empty($po['id']) ? $po['id'] : '0')); ?>" 
                                    class="btn btn-xs btn-info" title="View">
                                    <i class="fa fa-eye"></i>
                                 </a>
                                 
                                 <!-- Edit button - disabled if approved or completed -->
                                 <?php if ($status == 'pending' || $status != 'rejected'): ?>
                                    <a href="<?php echo base_url('new_purchase_orders/edit/' . (!empty($po['id']) ? $po['id'] : '0')); ?>" 
                                       class="btn btn-xs btn-warning" title="Edit">
                                       <i class="fa fa-edit"></i>
                                    </a>
                                 <?php else: ?>
                                    <button class="btn btn-xs btn-warning" title="Edit Disabled - Order is <?php echo ucfirst($status); ?>" disabled>
                                       <i class="fa fa-edit"></i>
                                    </button>
                                 <?php endif; ?>
                                 
                                 <!-- Approve/Reject buttons - Only for administrators -->
                                 <?php if (isset($user_role) && $user_role == 'administrator' && $status == 'pending'): ?>
                                    <a href="<?php echo base_url('new_purchase_orders/approve/' . (!empty($po['id']) ? $po['id'] : '0')); ?>" 
                                       class="btn btn-xs btn-success" title="Approve"
                                       onclick="return confirm('Are you sure you want to approve this purchase order?')">
                                       <i class="fa fa-check"></i>
                                    </a>
                                    <a href="<?php echo base_url('new_purchase_orders/reject/' . (!empty($po['id']) ? $po['id'] : '0')); ?>" 
                                       class="btn btn-xs btn-danger" title="Reject"
                                       onclick="return confirm('Are you sure you want to reject this purchase order?')">
                                       <i class="fa fa-times"></i>
                                    </a>
                                 <?php elseif ($status == 'pending'): ?>
                                    <button class="btn btn-xs btn-success" title="Approve - Administrator Only" disabled>
                                       <i class="fa fa-check"></i>
                                    </button>
                                    <button class="btn btn-xs btn-danger" title="Reject - Administrator Only" disabled>
                                       <i class="fa fa-times"></i>
                                    </button>
                                 <?php endif; ?>
                                 
                                 <!-- Print button - Only for approved and completed orders -->
                                 <?php if ($status == 'approved' || $status == 'completed'): ?>
                                    <a href="<?php echo base_url('new_purchase_orders/print_po/' . (!empty($po['id']) ? $po['id'] : '0')); ?>" 
                                       class="btn btn-xs btn-primary" title="Print Purchase Order" target="_blank">
                                       <i class="fa fa-print"></i>
                                    </a>
                                 <?php endif; ?>
                                 
                                 <!-- Complete button -->
                                 <?php if ($status == 'approved'): ?>
                                    <a href="<?php echo base_url('new_purchase_orders/complete/' . (!empty($po['id']) ? $po['id'] : '0')); ?>" 
                                       class="btn btn-xs btn-info" title="Mark Complete"
                                       onclick="return confirm('Are you sure you want to mark this purchase order as completed?')">
                                       <i class="fa fa-flag-checkered"></i>
                                    </a>
                                 <?php endif; ?>
                                 
                                 <!-- <a href="<?php echo base_url('new_purchase_orders/delete/' . $po['id']); ?>" 
                                    class="btn btn-xs btn-danger" title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this purchase order?')">
                                    <i class="fa fa-trash"></i>
                                 </a> -->
                              </div>
                           </td>
                        </tr>
                     <?php endforeach; ?>
                  <?php else: ?>
                     <tr>
                        <td colspan="9" class="text-center text-muted">
                           <i class="fa fa-inbox fa-3x" style="margin-bottom: 10px;"></i>
                           <br>No purchase orders found
                        </td>
                     </tr>
                  <?php endif; ?>
               </tbody>
            </table>
         </div>

         <!-- Pagination Info -->
         <div class="row" style="margin-top: 20px;">
            <div class="col-md-6">
               <p class="text-muted">
                  Showing <?php echo (($current_page - 1) * 20) + 1; ?> to <?php echo min($current_page * 20, $total_count); ?> of <?php echo $total_count; ?> records
               </p>
            </div>
            <div class="col-md-6 text-right">
               <p class="text-muted">
                  Page <?php echo $current_page; ?> of <?php echo $total_pages; ?>
               </p>
            </div>
         </div>

         <!-- Pagination -->
         <?php if ($total_pages > 1): ?>
            <div class="text-center">
               <ul class="pagination">
                  <?php if ($current_page > 1): ?>
                     <li>
                        <a href="<?php echo base_url('new_purchase_orders?page=' . ($current_page - 1) . '&' . http_build_query($filters)); ?>">
                           <i class="fa fa-chevron-left"></i>
                        </a>
                     </li>
                  <?php endif; ?>
                  
                  <?php 
                  // Show limited pagination for better UX
                  $start_page = max(1, $current_page - 2);
                  $end_page = min($total_pages, $current_page + 2);
                  
                  // Show first page if not in range
                  if ($start_page > 1): ?>
                     <li class="<?php echo (1 == $current_page) ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('new_purchase_orders?page=1&' . http_build_query($filters)); ?>">1</a>
                     </li>
                     <?php if ($start_page > 2): ?>
                        <li class="disabled"><span>...</span></li>
                     <?php endif; ?>
                  <?php endif; ?>
                  
                  <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                     <li class="<?php echo ($i == $current_page) ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('new_purchase_orders?page=' . $i . '&' . http_build_query($filters)); ?>">
                           <?php echo $i; ?>
                        </a>
                     </li>
                  <?php endfor; ?>
                  
                  <?php 
                  // Show last page if not in range
                  if ($end_page < $total_pages): ?>
                     <?php if ($end_page < $total_pages - 1): ?>
                        <li class="disabled"><span>...</span></li>
                     <?php endif; ?>
                     <li class="<?php echo ($total_pages == $current_page) ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('new_purchase_orders?page=' . $total_pages . '&' . http_build_query($filters)); ?>"><?php echo $total_pages; ?></a>
                     </li>
                  <?php endif; ?>
                  
                  <?php if ($current_page < $total_pages): ?>
                     <li>
                        <a href="<?php echo base_url('new_purchase_orders?page=' . ($current_page + 1) . '&' . http_build_query($filters)); ?>">
                           <i class="fa fa-chevron-right"></i>
                        </a>
                     </li>
                  <?php endif; ?>
               </ul>
            </div>
         <?php endif; ?>
      </div>
   </div>
</div>

<script>
$(document).ready(function() {
    // Initialize date pickers
    $(".particular_date_filter").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    
    // Initialize DataTable without pagination (we use custom pagination)
    $('#new_purchase_orders_list').DataTable({
        "paging": false, // Disable DataTable pagination
        "searching": false, // Disable DataTable search
        "info": false, // Disable DataTable info
        "order": [[ 7, "desc" ]], // Sort by Created Date (column 7)
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 8 }, // Disable sorting on Actions column
            { "defaultContent": "", "targets": "_all" } // Provide default content for empty cells
        ],
        "language": {
            "emptyTable": "No purchase orders found"
        },
        "processing": true, // Show processing indicator
        "deferRender": true // Defer rendering for better performance
    });
});
</script>