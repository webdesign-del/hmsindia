<?php $all_method =&get_instance(); ?>
<div class="col-md-12">
   <div class="card" style="margin-bottom:20px;">
      <div class="col-md-12">
         <h3><i class="fa fa-eye"></i> View Purchase Order</h3>
      </div>
      <div class="clearfix"></div>
      
      <div class="card-content">
         <!-- PO Details Section -->
         <div class="row">
            <div class="col-md-12">
               <div class="panel panel-info">
                  <div class="panel-heading">
                     <h4><i class="fa fa-info-circle"></i> Purchase Order Details</h4>
                  </div>
                  <div class="panel-body">
                     <div class="row">
                        <div class="col-md-6">
                           <table class="table table-borderless">
                              <tr>
                                 <td><strong>PO Number:</strong></td>
                                 <td><?php echo $purchase_order['po_number']; ?></td>
                              </tr>
                              <tr>
                                 <td><strong>Status:</strong></td>
                                 <td>
                                    <?php 
                                    $status_class = '';
                                    $status_text = '';
                                    switch($purchase_order['status']) {
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
                                    }
                                    ?>
                                    <span class="label <?php echo $status_class; ?>">
                                       <?php echo $status_text; ?>
                                    </span>
                                 </td>
                              </tr>
                              <?php
                              $all_method =&get_instance();
                              $all_method->load->model('Vendors_model');
                              $vendor_name = $all_method->Vendors_model->get_vendor_name_by_vendor_id($purchase_order['vendor_number']);
                              // $vendor_name = $vendor_name[0]['name'];
                              ?>
                              <tr>
                                 <td><strong>Vendor:</strong></td>
                                 <td><?php echo $vendor_name->name; ?></td>
                              </tr>
                              <tr>
                                 <td><strong>Department:</strong></td>
                                 <td><?php echo $purchase_order['department']; ?></td>
                              </tr>
                           </table>
                        </div>
                        <div class="col-md-6">
                           <table class="table table-borderless">
                              <tr>
                                 <td><strong>Ship To:</strong></td>
                                 <?php
                                 $ship_to = $all_method->get_center_name($purchase_order['ship_to']);
                                 ?>
                                 <td><?php echo $ship_to; ?></td>
                              </tr>
                              <tr>
                                 <td><strong>Bill To:</strong></td>
                                 <?php
                                 $bill_to = $all_method->get_center_name($purchase_order['bill_to']);
                                 ?>
                                 <td><?php echo $bill_to; ?></td>
                              </tr>
                              <tr>
                                 <td><strong>Total Amount:</strong></td>
                                 <td>
                                    <span class="label label-info">
                                       ₹<?php echo number_format($purchase_order['total_amount'], 2); ?>
                                    </span>
                                 </td>
                              </tr>
                              <tr>
                                 <td><strong>Created Date:</strong></td>
                                 <td><?php echo date('d/m/Y H:i', strtotime($purchase_order['created_at'])); ?></td>
                              </tr>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <!-- Action Buttons -->
         <div class="row">
            <div class="col-md-12 text-center">
               <?php if ($purchase_order['status'] == 'pending'): ?>
                  <a href="<?php echo base_url('new_purchase_orders/approve/' . $purchase_order['id']); ?>" 
                     class="btn btn-success btn-lg" 
                     onclick="return confirm('Are you sure you want to approve this purchase order?')">
                     <i class="fa fa-check"></i> Approve
                  </a>
                  <a href="<?php echo base_url('new_purchase_orders/reject/' . $purchase_order['id']); ?>" 
                     class="btn btn-danger btn-lg" 
                     onclick="return confirm('Are you sure you want to reject this purchase order?')">
                     <i class="fa fa-times"></i> Reject
                  </a>
               <?php endif; ?>
               
               <?php if ($purchase_order['status'] == 'approved'): ?>
                  <a href="<?php echo base_url('new_purchase_orders/complete/' . $purchase_order['id']); ?>" 
                     class="btn btn-info btn-lg" 
                     onclick="return confirm('Are you sure you want to mark this purchase order as completed?')">
                     <i class="fa fa-flag-checkered"></i> Mark Complete
                  </a>
               <?php endif; ?>
               
               
               <!-- <?php if ($purchase_order['status'] == 'completed'): ?>
                  <a href="<?php echo base_url('new_purchase_orders/new_add_stock/' . $purchase_order['id']); ?>" 
                     class="btn btn-success btn-lg">
                     <i class="fa fa-plus"></i> Add Stock
                  </a>
               <?php endif; ?> -->
               
               <?php if ($purchase_order['status'] == 'pending' || $purchase_order['status'] == 'rejected'): ?>
                  <a href="<?php echo base_url('new_purchase_orders/edit/' . $purchase_order['id']); ?>" 
                     class="btn btn-warning btn-lg">
                     <i class="fa fa-edit"></i> Edit
                  </a>
               <?php endif; ?>
               <a href="<?php echo base_url('new_purchase_orders'); ?>" class="btn btn-default btn-lg">
                  <i class="fa fa-arrow-left"></i> Back to List
               </a>
            </div>
         </div>

         <hr>

         <!-- Items Section -->
         <div class="row">
            <div class="col-md-12">
               <div class="panel panel-primary">
                  <div class="panel-heading">
                     <h4><i class="fa fa-list"></i> Purchase Order Items</h4>
                  </div>
                  <div class="panel-body">
                     <?php if (!empty($purchase_order_items)): ?>
                        <div class="table-responsive">
                           <table class="table table-striped table-bordered table-hover">
                              <thead>
                                 <tr>
                                    <th>Sr.</th>
                                    <th>Item Name</th>
                                    <th>Item Number</th>
                                    <th>Quantity Order</th>
                                    <th>Quantity Received</th>
                                    <th>Batch</th>
                                    <th>Price</th>
                                    <th>Vendor Price</th>
                                    <th>Pack Size</th>
                                    <th>MRP</th>
                                    <th>Tax %</th>
                                    <th>Company</th>
                                    <th>HSN</th>
                                    <th>GST Division</th>
                                    <th>Brand</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php foreach ($purchase_order_items as $index => $item): ?>
                                    <tr>
                                       <td><?php echo $index + 1; ?></td>
                                       <td><?php echo $item['item_name']; ?></td>
                                       <td><?php echo $item['item_number']; ?></td>
                                       <td><?php echo $item['quantity']; ?></td>
                                       <td><?php echo $item['quantity_received']; ?></td>
                                       <td><?php echo $item['batch_number']; ?></td>
                                       <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                       <td>₹<?php echo number_format($item['vendor_price'], 2); ?></td>
                                       <td><?php echo $item['pack_size']; ?></td>
                                       <td>₹<?php echo number_format($item['mrp'], 2); ?></td>
                                       <td><?php echo $item['tax_percentage']; ?>%</td>
                                       <td><?php echo $item['company']; ?></td>
                                       <td><?php echo $item['hsn']; ?></td>
                                       <td><?php echo $item['gst_division']; ?></td>
                                       <td><?php echo $item['brand_name']; ?></td>
                                    </tr>
                                 <?php endforeach; ?>
                              </tbody>
                           </table>
                        </div>
                     <?php else: ?>
                        <div class="text-center text-muted">
                           <i class="fa fa-inbox fa-3x" style="margin-bottom: 10px;"></i>
                           <br>No items found for this purchase order
                        </div>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         </div>

         <!-- Approval History -->
         <?php if ($purchase_order['status'] != 'pending'): ?>
            <div class="row">
               <div class="col-md-12">
                  <div class="panel panel-default">
                     <div class="panel-heading">
                        <h4><i class="fa fa-history"></i> Approval History</h4>
                     </div>
                     <div class="panel-body">
                        <div class="row">
                           <div class="col-md-6">
                              <table class="table table-borderless">
                                 <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><?php echo ucfirst($purchase_order['status']); ?></td>
                                 </tr>
                                 <?php if ($purchase_order['approved_by']): ?>
                                    <tr>
                                       <td><strong>Approved By:</strong></td>
                                       <td><?php echo $purchase_order['approved_by']; ?></td>
                                    </tr>
                                 <?php endif; ?>
                                 <?php if ($purchase_order['approved_at']): ?>
                                    <tr>
                                       <td><strong>Approved At:</strong></td>
                                       <td><?php echo date('d/m/Y H:i', strtotime($purchase_order['approved_at'])); ?></td>
                                    </tr>
                                 <?php endif; ?>
                              </table>
                           </div>
                           <div class="col-md-6">
                              <table class="table table-borderless">
                                 <tr>
                                    <td><strong>Created By:</strong></td>
                                    <td><?php echo isset($employee_detail_number['name']) ? $employee_detail_number['name'] : '-'; ?></td>
                                 </tr>
                                 <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($purchase_order['created_at'])); ?></td>
                                 </tr>
                                 <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($purchase_order['updated_at'])); ?></td>
                                 </tr>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         <?php endif; ?>
      </div>
   </div>
</div>