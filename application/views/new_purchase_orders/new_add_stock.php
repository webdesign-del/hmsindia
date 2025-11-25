<?php  $all_method =&get_instance(); ?>
<!-- Select2 CSS and JS -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/select2.min.css">
<script src="<?php echo base_url();?>assets/js/select2.min.js"></script>

<style>
    .purchase-receipt-header {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        color: white;
        padding: 20px;
        margin: -20px -20px 20px -20px;
        border-radius: 8px 8px 0 0;
    }

    .purchase-receipt-header h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .currency-info {
        background: rgba(255,255,255,0.1);
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
        margin-top: 10px;
    }

    .attached-files-btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 8px 15px;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    .attached-files-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        text-decoration: none;
    }

    .file-item {
        background: #f8f9fa;
        padding: 10px;
        margin: 5px 0;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .file-item:hover {
        background: #e9ecef;
        border-color: #adb5bd;
    }

    .file-item .btn-danger {
        padding: 2px 8px;
        font-size: 12px;
    }

    #file-upload-section {
        border: 2px dashed #dee2e6;
        background: #f8f9fa;
    }

    #file-upload-section:hover {
        border-color: #2196F3;
        background: #e3f2fd;
    }

    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .form-section h4 {
        color: #495057;
        margin-bottom: 20px;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 10px;
    }

    .receipt-table {
        background: white;
        border-radius: 8px;
        overflow-x: auto;
        overflow-y: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        min-width: 100%;
    }
    
    .receipt-table table {
        min-width: 1400px;
        width: 100%;
    }

    .receipt-table thead {
        background: linear-gradient(135deg, #E3F2FD, #BBDEFB);
        color: #1976D2;
    }

    .receipt-table th {
        font-weight: 600;
        text-align: center;
        padding: 15px 12px;
        border: none;
        font-size: 13px;
        white-space: nowrap;
        min-width: 100px;
    }

    .receipt-table td {
        padding: 12px 12px;
        border: 1px solid #e9ecef;
        vertical-align: middle;
        min-width: 100px;
    }

    .receipt-table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .receipt-table tbody tr:hover {
        background-color: #e3f2fd;
    }

    .checkbox-cell {
        text-align: center;
        width: 50px;
    }

    .product-details-btn {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 11px;
        margin-top: 5px;
        cursor: pointer;
    }

    .tax-details-btn {
        background: #9E9E9E;
        color: white;
        border: none;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 11px;
        margin-top: 5px;
        cursor: pointer;
    }

    .purchase-tax-label {
        color: #4CAF50;
        font-weight: 600;
        font-size: 16px;
    }

    .instruction-text {
        font-weight: bold;
        color: #495057;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .date-input-group {
        position: relative;
    }

    .date-input-group .fa-calendar {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
    }

    .form-control:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
    }

    .form-control.error {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .validation-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 2px;
        display: block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        border: none;
        padding: 10px 25px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1976D2, #1565C0);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .purchase-receipt-header {
            margin: -15px -15px 15px -15px;
            padding: 15px;
        }
        
        .purchase-receipt-header h3 {
            font-size: 18px;
        }
        
        .form-section {
            padding: 15px;
        }
        
        .receipt-table {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .receipt-table th,
        .receipt-table td {
            padding: 8px 6px;
            font-size: 12px;
            min-width: 80px;
        }
        
        .receipt-table table {
            min-width: 1200px;
        }
        
        .btn-primary {
            padding: 8px 20px;
            font-size: 14px;
        }
        
        .col-md-4,
        .col-md-6 {
            margin-bottom: 15px;
        }
    }

    @media (max-width: 576px) {
        .purchase-receipt-header .row {
            flex-direction: column;
        }
        
        .purchase-receipt-header .col-md-4 {
            text-align: left !important;
            margin-top: 10px;
        }
        
        .receipt-table th,
        .receipt-table td {
            padding: 6px 4px;
            font-size: 11px;
            min-width: 70px;
        }
        
        .receipt-table table {
            min-width: 1000px;
        }
        
        .product-details-btn,
        .tax-details-btn {
            font-size: 10px;
            padding: 2px 6px;
        }
    }

    /* Enhanced File Upload Styles */
    .file-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .file-upload-area:hover {
        border-color: #2196F3;
        background: #e3f2fd;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(33, 150, 243, 0.2);
    }

    .file-upload-content i {
        color: #6c757d;
        margin-bottom: 15px;
    }

    .file-upload-content p {
        margin: 5px 0;
        font-size: 16px;
        color: #495057;
    }

    .file-item.existing-file {
        background: #e8f5e8;
        border-left: 4px solid #28a745;
    }

    .file-item.new-file {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
    }

    .progress {
        height: 20px;
        border-radius: 10px;
    }

    .progress-bar {
        background: linear-gradient(135deg, #2196F3, #1976D2);
    }
</style>
Batch Number	<div class="col-md-12">
   <div class="card" style="margin-bottom:20px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
     
     <!-- Header Section -->
     <div class="purchase-receipt-header">
       <div class="row">
          <div class="col-md-8">
             <h3><i class="fa fa-file-text-o"></i>Add Stocks</h3>
          </div>
          <div class="col-md-4 text-right">
             <div class="currency-info">
                <i class="fa fa-rupee"></i> Base Currency : INR
             </div>
             <a href="#" class="attached-files-btn" onclick="toggleFileUpload()">
                <i class="fa fa-paperclip"></i> Receipt Files (<span id="file-count">0</span>) <span class="text-danger">*Required</span>
             </a>
          </div>
       </div>
     </div>
     
     <!-- File Upload Section (Hidden by default) -->
 
     
     <form method="post" action="<?php echo base_url('new_purchase_orders/save_add_stock?id=' . $purchase_order['id']); ?>" id="purchase_receipt_form" enctype="multipart/form-data">
       <!-- Hidden fields for processing -->
        <div id="file-upload-section" class="form-section" style="display: block; margin-top: 0;">
        <h4><i class="fa fa-upload"></i> Upload Receipt Files (Required)</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="receipt_files">Receipt Files <span class="text-danger">*</span> <span class="text-muted">(PDF, JPG, PNG - Max 5MB each, Multiple files required)</span></label>
                        <div class="file-upload-area" onclick="document.getElementById('receipt_files').click()">
                        <div class="file-upload-content">
                            <i class="fa fa-cloud-upload fa-3x"></i>
                            <p>Click here to select receipt files (PDF/Images)</p>
                            <p class="text-muted">or drag and drop files here</p>
                        </div>
                        </div>
                        <input type="file" class="form-control" id="receipt_files" name="receipt_files[]" multiple 
                        accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileSelection(this)" style="display: none;">
                        
                        <!-- Existing Files Display -->
                        <div id="existing-files" class="mt-3">
                        <?php if (isset($uploaded_files) && !empty($uploaded_files)): ?>
                            <h5><i class="fa fa-files-o"></i> Previously Uploaded Files:</h5>
                            <?php foreach ($uploaded_files as $index => $file): ?>
                                <div class="file-item existing-file">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span>
                                        <i class="fa fa-file-<?php echo strtolower(pathinfo($file['original_name'], PATHINFO_EXTENSION)) == 'pdf' ? 'pdf' : 'image'; ?>-o"></i> 
                                        <?php echo $file['original_name']; ?> 
                                        <span class="text-muted">(<?php echo number_format($file['file_size'] / 1024, 2); ?> KB)</span>
                                        <small class="text-muted">- Uploaded: <?php echo date('M d, Y H:i', strtotime($file['upload_date'])); ?></small>
                                    </span>
                                    <div>
                                        <a href="<?php echo base_url('uploads/receipts/' . $file['stored_name']); ?>" 
                                            target="_blank" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                        <a href="<?php echo base_url('uploads/receipts/' . $file['stored_name']); ?>" 
                                            download class="btn btn-sm btn-success">
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeExistingFile(<?php echo $index; ?>)">
                                            <i class="fa fa-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </div>
                        
                        <!-- New Files Display -->
                        <div id="file-list" class="mt-3"></div>
                        
                        <!-- Upload Progress -->
                        <div id="upload-progress" class="mt-3" style="display: none;">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                        <p class="text-center mt-2">Uploading files...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       <input type="hidden" name="po_id" value="<?php echo $purchase_order['id']; ?>">
       <input type="hidden" name="po_number" value="<?php echo $purchase_order['po_number']; ?>">
       <input type="hidden" name="vendor_number" value="<?php echo $purchase_order['vendor_number']; ?>">
       <input type="hidden" name="department" value="<?php echo $purchase_order['department']; ?>">
       <input type="hidden" name="center_id" value="<?php echo $ship_to_center_id; ?>">
       
       <div class="card-content" style="padding: 20px;">
          
          <!-- General Purchase Order Details Section -->
          <div class="form-section">
             <h4><i class="fa fa-info-circle"></i> General Purchase Order Details</h4>
             
             <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                   <div class="row">
                       <div class="col-md-6">
                 <div class="form-group">
                    <label for="po_number">PO Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="po_number" name="po_number" 
                       value="<?php echo isset($purchase_order['po_number']) ? $purchase_order['po_number'] : ''; ?>" readonly>
                 </div>
                       </div>
                       <div class="col-md-6">
                          <div class="form-group">
                             <label for="receipt_number">Invoice No  <span class="text-danger">*</span></label>
                             <input type="text" class="form-control" id="receipt_number" name="receipt_number" required>
                          </div>
                       </div>
                   </div>
                   
                   <div class="form-group">
                       <label for="reference">Reference</label>
                       <input type="text" class="form-control" id="reference" name="reference" 
                          value="<?php echo isset($purchase_order['reference']) ? $purchase_order['reference'] : ''; ?>" required>
                   </div>
                   
                  <div class="form-group">
                       <label for="ship_to">Ship To <span class="text-danger">*</span></label>
                       <p>
                          <textarea id="ship_to" name="ship_to" class="form-control" rows="3" style="height:100px !important" readonly><?php 
                             echo isset($ship_to_address) ? $ship_to_address : ''; 
                          ?></textarea>
                       </p>
                   </div>

                </div>
                <!-- Right Column -->
                <div class="col-md-6">
                   <div class="form-group">
                       <label for="supplier_name">Supplier Name <span class="text-danger">*</span></label>
                       <select class="form-control" id="supplier_name" name="supplier_name_display" disabled>
                          <option value="">-- Select Supplier --</option>
                          <!-- <option value="Cash Purchase" <?php echo (isset($vendor_id) && $vendor_id == 'Cash Purchase') ? 'selected' : ''; ?>>Cash Purchase</option> -->
                          <?php if(isset($vendors) && !empty($vendors)): ?>
                             <?php foreach($vendors as $vendor): ?>
                                 <option value="<?php echo $vendor['ID']; ?>" 
                                    <?php echo (isset($vendor_id) && $vendor_id == $vendor['ID']) ? 'selected' : ''; ?>>
                                    <?php echo $vendor['name']; ?>
                                 </option>
                             <?php endforeach; ?>
                          <?php endif; ?>
                       </select>
                       <!-- Hidden input that actually sends the value -->
                       <input type="hidden" name="supplier_name" 
                          value="<?php echo isset($purchase_order['vendor_number']) ? $purchase_order['vendor_number'] : ''; ?>">
                   </div>
                   <div class="row">
                       <div class="col-md-6">
                          <div class="form-group">
                             <label for="total_amount">Total Amount</label>
                             <input type="number" class="form-control" id="total_amount" name="total_amount" 
                                value="<?php echo isset($purchase_order['total_amount']) ? $purchase_order['total_amount'] : '0'; ?>" step="0.01">
                          </div>
                       </div>
                       <div class="col-md-6">
                          <div class="form-group">
                             <label for="po_date">PO Date <span class="text-danger">*</span></label>
                             <div class="date-input-group">
                                <input type="text" class="form-control" id="po_date" name="po_date" 
                                   value="<?php echo isset($purchase_order['created_at']) ? date('Y-m-d', strtotime($purchase_order['created_at'])) : date('Y-m-d'); ?>">
                                <i class="fa fa-calendar"></i>
                             </div>
                          </div>
                       </div>
                   </div>
                   
                   <div class="row">
                       <div class="col-md-4">
                          <div class="form-group">
                             <label for="reference_date">Receipt Date</label>
                             <div class="date-input-group">
                                <input type="date" class="form-control" id="receipt_date" name="receipt_date" 
                                   value="<?php echo date('Y-m-d'); ?>" placeholder="YYYY-MM-DD">
                             </div>
                          </div>
                       </div>
                       <div class="col-md-4">
                          <div class="form-group">
                             <label for="receive_by">Receive By</label>
                             <input type="text" class="form-control" name="receive_by" id="receive_by" 
                                value="" placeholder="Enter receiver name" required>
                          </div>
                       </div>
                       <div class="col-md-4">
                          <div class="form-group">
                             <label for="date_receiving">Date of Receiving</label>
                             <div class="date-input-group">
                                <input type="text" class="form-control" name="date_receiving" id="date_receiving" 
                                   value="<?php echo date('Y-m-d'); ?>" placeholder="YYYY-MM-DD" required>
                                <i class="fa fa-calendar"></i>
                             </div>
                          </div>
                       </div>
                   </div>
                   
                   <div class="form-group">
                       <label>Purchase Tax</label>
                       <div>
                          <span class="purchase-tax-label">Yes</span>
                       </div>
                   </div>
                </div>
             </div>
          </div>

          <!-- Items/Products/Services Section -->
          <div class="form-section">
             <div class="receipt-table">
                <table class="table table-bordered" id="receipt_items_table">
                   <thead>
                      <tr>
                         <th style="min-width: 200px;">Product</th>
                         <th style="min-width: 80px;">UOM</th>
                         <th style="min-width: 100px;">Qty Remain</th>
                         <th style="min-width: 80px;">Rec.All?</th>
                         <th style="min-width: 120px;">Qty Receiving</th>
                         <th style="min-width: 100px;">Free Qty</th>
                         <th style="min-width: 100px;">Qty Rej</th>
                         <th style="min-width: 120px;">Batch Number</th>
                         <th style="min-width: 120px;">Expiry Date</th>
                         <!-- <th style="min-width: 140px;">Notify Expiry On</th> -->
                         <th style="min-width: 150px;">Comments</th>
                         <th style="min-width: 100px;">Vendor Price</th>
                         <th style="min-width: 100px;">Tax %</th>
                         <th style ="min-width: 100px;">Mrp</th>
                         <th style="min-width: 100px;">Discount (%)</th>
                         <th style="min-width: 80px;">Incl.Tax?</th>
                         <th style="min-width: 100px;">Tax Amt</th>
                         <th style="min-width: 100px;">Amount</th>
                      </tr>
                   </thead>
                   <tbody id="receipt_items_tbody">
                      <?php if (!empty($purchase_order_items)): ?>
                         <?php $row_counter = 1; ?>
                         <?php foreach ($purchase_order_items as $item): ?>
                             <!-- *** NEW LOGIC: Check if row should be disabled *** -->
                            <?php 
                                $qty_ordered = (isset($item['quantity']) ? $item['quantity'] : 0);
                                $qty_received = (isset($item['quantity_received']) ? $item['quantity_received'] : 0);
                                $qty_remaining = $qty_ordered - $qty_received;
                                $is_row_disabled = ($qty_remaining <= 0);
                                $disabled_attr = $is_row_disabled ? 'disabled' : '';
                                $row_class = $is_row_disabled ? 'disabled-row' : '';
                            ?>
                            <!-- *** END NEW LOGIC *** -->
                         
                             <tr class="receipt-item-row <?php echo $row_class; ?>" id="receipt_row_<?php echo $row_counter; ?>">
                               <td>
                                  <select class="form-control" name="product_<?php echo $row_counter; ?>" id="product_<?php echo $row_counter; ?>" readonly onchange="populateProductDetails(<?php echo $row_counter; ?>)" <?php echo $disabled_attr; ?>>
                                     <option value="<?php echo $item['item_number']; ?>" selected>
                                       <?php echo isset($item['item_name']) ? $item['item_name'] : 'Product Name'; ?>
                                     </option>
                                  </select>
                                  <input type="hidden" name="po_item_id_<?php echo $row_counter; ?>" 
                                         value="<?php echo $item['id']; ?>">
                               </td>
                               <td>
                                  <input type="text" class="form-control" name="uom_<?php echo $row_counter; ?>" id="uom_<?php echo $row_counter; ?>" 
                                     value="<?php echo isset($item['pack_size']) ? $item['pack_size'] : 'PCS'; ?>" readonly>
                               </td>
                               <td>
                                  <input type="number" class="form-control" name="qty_remain_<?php echo $row_counter; ?>" id="qty_remain_<?php echo $row_counter; ?>" 
                                     value="<?php echo $qty_remaining; ?>" step="0.001" readonly>
                               </td>
                               <td class="checkbox-cell">
                                  <input type="checkbox" name="receive_all_<?php echo $row_counter; ?>" id="receive_all_<?php echo $row_counter; ?>" style="left: 0px !important;opacity: 1 !important;position: unset !important;" <?php echo $disabled_attr; ?>>
                               </td>
                               <td>
                                  <input type="number" class="form-control" name="qty_receiving_<?php echo $row_counter; ?>" id="qty_receiving_<?php echo $row_counter; ?>" 
                                     value="<?php echo $qty_remaining; ?>" step="0.001" onchange="updateAmount(<?php echo $row_counter; ?>)" <?php echo $disabled_attr; ?>>
                                  
                                  <!-- *** NEW: Show "Fully Received" Badge *** -->
                                  <?php if ($is_row_disabled): ?>
                                      <span class="label label-success" style="margin-top: 5px; display: block; font-size: 11px;">
                                          <i class="fa fa-check"></i> Fully Received
                                      </span>
                                  <?php else: ?>
                                      <button type="button" class="product-details-btn" onclick="showProductDetails(<?php echo $row_counter; ?>)">
                                        Product Details
                                      </button>
                                  <?php endif; ?>

                               </td>
                               <td>
                                  <input type="number" class="form-control" name="free_qty_<?php echo $row_counter; ?>" id="free_qty_<?php echo $row_counter; ?>" 
                                     value="0" step="0.001" placeholder="Free Quantity" <?php echo $disabled_attr; ?>>
                               </td>
                               <td>
                                  <input type="number" class="form-control" name="qty_rejected_<?php echo $row_counter; ?>" id="qty_rejected_<?php echo $row_counter; ?>" 
                                     value="0" step="0.001" onchange="updateAmount(<?php echo $row_counter; ?>)" <?php echo $disabled_attr; ?>>
                               </td>
                               <td>
                                  <input type="text" class="form-control" name="batch_number_<?php echo $row_counter; ?>" id="batch_number_<?php echo $row_counter; ?>" 
                                     value="<?php echo isset($item['batch_number']) ? $item['batch_number'] : ''; ?>" placeholder="Enter batch number" <?php echo $disabled_attr; ?>>
                               </td>
                               <td>
                                  <div class="date-input-group">
                                     <input type="text" class="form-control" name="expiry_date_<?php echo $row_counter; ?>" id="expiry_date_<?php echo $row_counter; ?>" 
                                        value="<?php echo isset($item['expiry_date']) ? $item['expiry_date'] : ''; ?>" placeholder="YYYY-MM-DD" required <?php echo $disabled_attr; ?>>
                                     <i class="fa fa-calendar"></i>
                                  </div>
                               </td>
                               <td>
                                  <textarea class="form-control" name="comments_<?php echo $row_counter; ?>" id="comments_<?php echo $row_counter; ?>" 
                                     rows="2" placeholder="Enter comments" <?php echo $disabled_attr; ?>><?php echo isset($item['comments']) ? $item['comments'] : ''; ?></textarea>
                               </td>
                               <td>
                                  <input type="number" class="form-control" name="unit_price_<?php echo $row_counter; ?>" id="unit_price_<?php echo $row_counter; ?>" 
                                     value="<?php echo isset($item['vendor_price']) ? $item['vendor_price'] : '0'; ?>" step="0.01" onchange="updateAmount(<?php echo $row_counter; ?>)" <?php echo $disabled_attr; ?>>
                               </td>
                               <td>
                                  <input type="number" class="form-control" name="tax_percentage_<?php echo $row_counter; ?>" id="tax_percentage_<?php echo $row_counter; ?>" 
                                     value="<?php echo isset($item['tax_percentage']) ? $item['tax_percentage'] : '0'; ?>" step="0.01" onchange="updateAmount(<?php echo $row_counter; ?>)" <?php echo $disabled_attr; ?>>
                               </td>
                               <td>
                                  <input type="number" class="form-control" name="mrp_<?php echo $row_counter; ?>" id="mrp_<?php echo $row_counter; ?>" 
                                     value="<?php echo isset($item['mrp']) ? $item['mrp'] : '0'; ?>" step="0.01" onchange="updateAmount(<?php echo $row_counter; ?>)" <?php echo $disabled_attr; ?>>
                               </td>
                             
                               <td>
                                  <input type="number" class="form-control" name="discount_<?php echo $row_counter; ?>" id="discount_<?php echo $row_counter; ?>" 
                                     value="0" step="0.01" onchange="updateAmount(<?php echo $row_counter; ?>)" <?php echo $disabled_attr; ?>>
                               </td>
                               <td class="checkbox-cell">
                                  <input type="checkbox" name="include_tax_<?php echo $row_counter; ?>"  style="left: 0px !important;opacity: 1 !important;position: unset !important;" id="include_tax_<?php echo $row_counter; ?>" 
                                     <?php echo (isset($item['tax_percentage']) && $item['tax_percentage'] > 0) ? 'checked' : ''; ?> onchange="updateAmount(<?php echo $row_counter; ?>)" <?php echo $disabled_attr; ?>>
                               </td>
                               <td>
                                  <input type="number" class="form-control" name="tax_amount_<?php echo $row_counter; ?>" id="tax_amount_<?php echo $row_counter; ?>" 
                                     value="0.00" 
                                     data-tax-percentage="<?php echo isset($item['tax_percentage']) ? $item['tax_percentage'] : '0'; ?>"
                                     step="0.01" readonly>
                                  <button type="button" class="tax-details-btn" onclick="showTaxDetails(<?php echo $row_counter; ?>)">
                                    Tax Details
                                  </button>
                               </td>
                               <td>
                                  <input type="number" class="form-control" name="amount_<?php echo $row_counter; ?>" id="amount_<?php echo $row_counter; ?>" 
                                     value="0.00" 
                                     step="0.01" readonly>
                               </td>
                            </tr>
                            <?php $row_counter++; ?>
                         <?php endforeach; ?>
                      <?php else: ?>
                         <!-- Fallback row if no items, though this page should always have items -->
                      <?php endif; ?>
                   </tbody>
                </table>
             </div>
             
          </div>

          <!-- Submit Buttons -->
          <div class="row">
             <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                   <i class="fa fa-save"></i> Add Stock
                </button>
                <a href="<?php echo base_url('new_purchase_orders'); ?>" class="btn btn-default btn-lg">
                   <i class="fa fa-times"></i> Cancel
                </a>
                <a href="<?php echo base_url('stocks_new/track_po_batches/' . $purchase_order['id']); ?>" class="btn btn-info btn-lg">
                   <i class="fa fa-truck"></i> Track Batches
                </a>
             </div>
          </div>
       </div>
     </form>
   </div>
</div>

<script>
// File upload functions (handleFileSelection, etc.)
function toggleFileUpload() {
    var fileSection = document.getElementById('file-upload-section');
    if (fileSection.style.display === 'none') {
        fileSection.style.display = 'block';
    } else {
        fileSection.style.display = 'none';
    }
}
function handleFileSelection(input) { /* ... Your file handling code ... */ }
function removeFile(index) { /* ... Your file handling code ... */ }
function formatFileSize(bytes) { /* ... Your file handling code ... */ }
// End file upload functions

function populateProductDetails(rowId) {
    // This function seems to be for a different form,
    // as the product select is read-only here.
}

function showProductDetails(rowId) {
    alert('Showing product details for row ' + rowId);
}

function showTaxDetails(rowId) {
    alert('Showing tax details for row ' + rowId);
}

function validateReceivingQuantity(rowId) {
    var orderQty = parseFloat($('#qty_remain_' + rowId).val()) || 0;
    var receivingQty = parseFloat($('#qty_receiving_' + rowId).val()) || 0;
    
    if (receivingQty > orderQty) {
        $('#qty_receiving_' + rowId).addClass('error');
        showValidationError(rowId, 'Receiving quantity cannot exceed order quantity (' + orderQty + ')');
        return false;
    } else {
        $('#qty_receiving_' + rowId).removeClass('error');
        hideValidationError(rowId);
        return true;
    }
}
function showValidationError(rowId, message) { /* ... Your validation code ... */ }
function hideValidationError(rowId) { /* ... Your validation code ... */ }


// --- THIS IS THE CRITICAL CALCULATION SECTION ---

function updateAmount(rowId) {
    var qtyReceiving = parseFloat($('#qty_receiving_' + rowId).val()) || 0;
    var unitPrice = parseFloat($('#unit_price_' + rowId).val()) || 0;
    
    // *** THIS IS THE FIX ***
    // Read from the 'tax_percentage_...' input field, NOT the 'tax_amount_...' data attribute.
    var taxPercentage = parseFloat($('#tax_percentage_' + rowId).val()) || 0;
    
    var discount = parseFloat($('#discount_' + rowId).val()) || 0;
    var includeTax = $('#include_tax_' + rowId).is(':checked');
    
    // Calculate base amount
    var baseAmount = qtyReceiving * unitPrice;
    
    // Calculate discount
    // Ensure discount cannot be more than the base amount
    var discountAmount = 0;
    if (discount > 0) {
        discountAmount = baseAmount * (discount / 100);
    }
    
    var amountAfterDiscount = baseAmount - discountAmount;
    
    // Calculate tax
    var taxAmount = 0;
    if (includeTax && taxPercentage > 0) {
        // Tax is calculated on the amount *after* discount
        taxAmount = amountAfterDiscount * (taxPercentage / 100);
    }
    
    var finalAmount = amountAfterDiscount + taxAmount;
    
    // Debug logging (can be removed in production)
    console.log('Row ' + rowId + ' Calculation:', {
        qtyReceiving: qtyReceiving,
        unitPrice: unitPrice,
        taxPercentage: taxPercentage, // This will now show the correct, changed value
        discount: discount,
        baseAmount: baseAmount,
        discountAmount: discountAmount,
        amountAfterDiscount: amountAfterDiscount,
        taxAmount: taxAmount,
        finalAmount: finalAmount,
        includeTax: includeTax
    });
    
    // Update the readonly fields
    $('#tax_amount_' + rowId).val(taxAmount.toFixed(2));
    $('#amount_' + rowId).val(finalAmount.toFixed(2));
}
// ===================================================
// 💡 START: ADD/REPLACE THIS FILE HANDLING CODE
// ===================================================
let newFileStore = new DataTransfer();
function handleFileSelection(input) {
    const fileListDiv = document.getElementById('file-list');
    const newFiles = input.files;
    
    // Validate that only PDF and image files are selected
    for (let i = 0; i < newFiles.length; i++) {
        const file = newFiles[i];
        const fileType = file.type.toLowerCase();
        const fileName = file.name.toLowerCase();
        
        // Check if file is PDF or image
        const isPDF = fileType === 'application/pdf' || fileName.endsWith('.pdf');
        const isImage = fileType.startsWith('image/') || fileName.match(/\.(jpg|jpeg|png)$/);
        
        if (!isPDF && !isImage) {
            alert('Only PDF and image files (PDF, JPG, PNG) are allowed. Please select valid files only.');
            input.value = ''; // Clear the input
            return;
        }
        
        // Check file size (5MB limit)
        if (file.size > 5 * 1024 * 1024) {
            alert('File "' + file.name + '" is too large. Maximum file size is 5MB.');
            input.value = ''; // Clear the input
            return;
        }
        
        newFileStore.items.add(file);
    }
    
    input.files = newFileStore.files;
    renderNewFileList();
    
    // Clear any error styling when files are selected
    $('.file-upload-area').css('border-color', '').css('background-color', '');
}

function renderNewFileList() {
    const fileListDiv = document.getElementById('file-list');
    fileListDiv.innerHTML = ''; // Clear the current list
    if (newFileStore.files.length > 0) {
        let listHtml = '<h5><i class="fa fa-file-plus-o"></i> New Files to Upload:</h5>';
        
        for (let i = 0; i < newFileStore.files.length; i++) {
            const file = newFileStore.files[i];
            const fileIcon = getFileIconClass(file.name);
            const fileSize = formatFileSize(file.size);

            listHtml += `
                <div class="file-item new-file" id="new-file-${i}">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span>
                            <i class="fa ${fileIcon}"></i> 
                            ${file.name}
                            <span class="text-muted">(${fileSize})</span>
                        </span>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeNewFile(${i})">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;
        }
        fileListDiv.innerHTML = listHtml;
    }
    updateTotalFileCount();
}
function removeNewFile(index) {
    newFileStore.items.remove(index);
    document.getElementById('receipt_files').files = newFileStore.files;
    renderNewFileList();
}
function getFileIconClass(fileName) {
    const extension = fileName.split('.').pop().toLowerCase();
    if (extension === 'pdf') {
        return 'fa-file-pdf-o';
    }
    if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
        return 'fa-file-image-o';
    }
    return 'fa-file-o'; // Default file icon
}
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Updates the total file count in the header button.
 */
function updateTotalFileCount() {
    const existingFileCount = document.querySelectorAll('#existing-files .file-item').length;
    const newFileCount = newFileStore.files.length;
    document.getElementById('file-count').innerText = existingFileCount + newFileCount;
}

$(document).ready(function() {
    // Initialize date pickers (assuming jQuery UI is loaded)
    try {
        $('#po_date, #reference_date, #date_receiving').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
        
        $('input[name^="expiry_date_"]').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
        
        // $('input[name^="notify_expiry_"]').datepicker({
        //     dateFormat: 'yy-mm-dd',
        //     changeMonth: true,
        //     changeYear: true
        // });
    } catch(e) {
        console.warn("Datepicker not loaded. Please load jQuery UI.");
    }
    
    // Auto-fill receive all checkbox functionality
    $('input[name^="receive_all_"]').on('change', function() {
        var rowId = $(this).attr('name').split('_')[2];
        if ($(this).is(':checked')) {
            var qtyRemain = parseFloat($('#qty_remain_' + rowId).val()) || 0;
            $('#qty_receiving_' + rowId).val(qtyRemain.toFixed(3));
            updateAmount(rowId);
            validateReceivingQuantity(rowId);
        }
    });
    
    // Real-time validation for receiving quantity
    $(document).on('input change', 'input[name^="qty_receiving_"]', function() {
        var rowId = $(this).attr('name').split('_')[2];
        validateReceivingQuantity(rowId);
        // updateAmount(rowId); // Already called by onchange
    });
    
    // Form submission validation
    $('#purchase_receipt_form').on('submit', function(e) {
        var isValid = true;
        
        // Check if receipt files are uploaded
        var existingFileCount = document.querySelectorAll('#existing-files .file-item').length;
        var newFileCount = newFileStore.files.length;
        var totalFileCount = existingFileCount + newFileCount;
        
        if (totalFileCount === 0) {
            alert('Please upload at least one receipt file (PDF or Image). Receipt files are required.');
            // Show the file upload section if it's hidden
            $('#file-upload-section').show();
            // Add visual indicator for required field
            $('.file-upload-area').css('border-color', '#dc3545').css('background-color', '#f8d7da');
            // Scroll to the file upload section
            $('html, body').animate({
                scrollTop: $('#file-upload-section').offset().top - 100
            }, 500);
            isValid = false;
        } else {
            // Remove error styling if files are present
            $('.file-upload-area').css('border-color', '').css('background-color', '');
        }
        
        var hasReceivingItems = false;
        $('input[name^="qty_receiving_"]').each(function() {
            if (parseFloat($(this).val()) > 0) {
                hasReceivingItems = true;
                return false; 
            }
        });
        
        if (!hasReceivingItems) {
            alert('Please enter quantity for at least one item to receive.');
            isValid = false;
        }
        
        if (!$('#receive_by').val().trim()) {
             alert('Please enter the name of the receiver.');
             isValid = false;
        }
        
        // Validate expiry dates for items being received
        $('input[name^="qty_receiving_"]').each(function() {
            if (parseFloat($(this).val()) > 0) {
                var rowId = $(this).attr('name').split('_')[2];
                var expiryDate = $('#expiry_date_' + rowId).val();
                
                if (!expiryDate) {
                    alert('Please enter expiry date for item in row ' + rowId + '.');
                    isValid = false;
                    return false; 
                }
                
                if (!validateReceivingQuantity(rowId)) {
                    isValid = false;
                    return false;
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Initialize amounts for all existing rows on page load
    <?php if (!empty($purchase_order_items)): ?>
        <?php for ($i = 1; $i <= count($purchase_order_items); $i++): ?>
            updateAmount(<?php echo $i; ?>);
        <?php endfor; ?>
    <?php endif; ?>
    updateTotalFileCount();
});
</script>