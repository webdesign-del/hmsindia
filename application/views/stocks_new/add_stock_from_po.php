<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-plus-circle"></i> Add Stock from Purchase Order
            <small>Convert approved purchase order items to stock batches</small>
        </h1>
    </div>
</div>

<!-- Breadcrumb -->
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>"><i class="fa fa-dashboard"></i> Stock Dashboard</a></li>
            <li><a href="<?php echo base_url('stocks_new/purchase_orders_for_stock'); ?>"><i class="fa fa-shopping-cart"></i> Purchase Orders</a></li>
            <li class="active"><i class="fa fa-plus-circle"></i> Add Stock</li>
        </ol>
    </div>
</div>

<!-- Purchase Order Details -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Purchase Order Details
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>PO Number:</strong><br>
                        <?php echo isset($purchase_order->po_number) ? htmlspecialchars($purchase_order->po_number) : 'N/A'; ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Vendor:</strong><br>
                        <?php echo isset($purchase_order->vendor_name) ? htmlspecialchars($purchase_order->vendor_name) : 'N/A'; ?>
                    </div>
                    <div class="col-md-3">
                        <strong>PO Date:</strong><br>
                        <?php echo isset($purchase_order->created_at) && !empty($purchase_order->created_at) ? date('M d, Y', strtotime($purchase_order->created_at)) : 'N/A'; ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Total Amount:</strong><br>
                        <span class="badge badge-info">₹<?php echo isset($purchase_order->total_amount) && is_numeric($purchase_order->total_amount) ? number_format($purchase_order->total_amount, 2) : '0.00'; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Addition Form -->
<form method="post" action="<?php echo base_url('stocks_new/process_stock_from_po'); ?>" id="stockAdditionForm">
    <input type="hidden" name="action" value="add_stock_from_po" />
    <input type="hidden" name="po_id" value="<?php echo isset($purchase_order->id) ? $purchase_order->id : ''; ?>" />
    
    <!-- General Information -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-info"></i> General Information
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="invoice_number">Invoice Number</label>
                                <input type="text" class="form-control" id="invoice_number" name="invoice_number" 
                                       placeholder="Enter invoice number" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="receipt_date">Receipt Date</label>
                                <input type="date" class="form-control" id="receipt_date" name="receipt_date" 
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="received_by">Received By</label>
                                <input type="text" class="form-control" id="received_by" name="received_by" 
                                       placeholder="Enter receiver name" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Items Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-list"></i> Purchase Order Items
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Brand</th>
                                    <th>Qty Ordered</th>
                                    <th>Qty Received</th>
                                    <th>Qty Rejected</th>
                                    <th>Free Qty</th>
                                    <th>Batch Number</th>
                                    <th>Expiry Date</th>
                                    <th>Manufacturing Date</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>MRP</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($po_items) && !empty($po_items)): ?>
                                    <?php $i = 1; ?>
                                    <?php foreach($po_items as $item): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo isset($item->medicine_name) ? htmlspecialchars($item->medicine_name) : 'N/A'; ?></strong><br>
                                                <small class="text-muted"><?php echo isset($item->medicine_code) ? htmlspecialchars($item->medicine_code) : ''; ?></small>
                                                <input type="hidden" name="item_number_<?php echo $i; ?>" value="<?php echo isset($item->item_number) ? htmlspecialchars($item->item_number) : ''; ?>" />
                                                <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo isset($item->medicine_name) ? htmlspecialchars($item->medicine_name) : ''; ?>" />
                                                <input type="hidden" name="brand_name_<?php echo $i; ?>" value="<?php echo isset($item->brand_name) ? htmlspecialchars($item->brand_name) : ''; ?>" />
                                                <input type="hidden" name="generic_name_<?php echo $i; ?>" value="<?php echo isset($item->generic_name) ? htmlspecialchars($item->generic_name) : ''; ?>" />
                                                <input type="hidden" name="company_<?php echo $i; ?>" value="<?php echo isset($item->company) ? htmlspecialchars($item->company) : ''; ?>" />
                                                <input type="hidden" name="pack_size_<?php echo $i; ?>" value="<?php echo isset($item->pack_size) ? htmlspecialchars($item->pack_size) : '1'; ?>" />
                                                <input type="hidden" name="hsn_<?php echo $i; ?>" value="<?php echo isset($item->hsn) ? htmlspecialchars($item->hsn) : ''; ?>" />
                                                <input type="hidden" name="tax_percentage_<?php echo $i; ?>" value="<?php echo isset($item->tax_percentage) ? $item->tax_percentage : 0; ?>" />
                                            </td>
                                            <td><?php echo isset($item->brand_name) ? htmlspecialchars($item->brand_name) : 'N/A'; ?></td>
                                            <td>
                                                <span class="badge badge-info"><?php echo isset($item->quantity) && is_numeric($item->quantity) ? number_format($item->quantity) : '0'; ?></span>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="quantity_received_<?php echo $i; ?>" 
                                                       value="<?php echo isset($item->quantity) ? $item->quantity : '0'; ?>" 
                                                       min="0" step="0.001" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="quantity_rejected_<?php echo $i; ?>" 
                                                       value="0" min="0" step="0.001">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="free_quantity_<?php echo $i; ?>" 
                                                       value="0" min="0" step="0.001">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="batch_number_<?php echo $i; ?>" 
                                                       value="<?php echo isset($item->batch_number) ? htmlspecialchars($item->batch_number) : ''; ?>" 
                                                       placeholder="Enter batch number" required>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" name="expiry_date_<?php echo $i; ?>" 
                                                       value="<?php echo isset($item->expiry_date) ? $item->expiry_date : ''; ?>" required>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" name="manufacturing_date_<?php echo $i; ?>" 
                                                       value="<?php echo isset($item->manufacturing_date) ? $item->manufacturing_date : ''; ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="purchase_price_<?php echo $i; ?>" 
                                                       value="<?php echo isset($item->vendor_price) ? $item->vendor_price : '0'; ?>" 
                                                       min="0" step="0.01" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="selling_price_<?php echo $i; ?>" 
                                                       value="<?php echo isset($item->selling_price) ? $item->selling_price : '0'; ?>" 
                                                       min="0" step="0.01" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="mrp_<?php echo $i; ?>" 
                                                       value="<?php echo isset($item->mrp) ? $item->mrp : '0'; ?>" 
                                                       min="0" step="0.01" required>
                                            </td>
                                            <td>
                                                <textarea class="form-control" name="comments_<?php echo $i; ?>" rows="2" 
                                                          placeholder="Enter comments"><?php echo isset($item->comments) ? htmlspecialchars($item->comments) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="13" class="text-center text-muted">
                                            <i class="fa fa-info-circle fa-2x"></i><br>
                                            No items found for this purchase order.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Submit Buttons -->
    <div class="row">
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fa fa-plus"></i> Add Stock to Inventory
            </button>
            <a href="<?php echo base_url('stocks_new/purchase_orders_for_stock'); ?>" class="btn btn-default btn-lg">
                <i class="fa fa-times"></i> Cancel
            </a>
        </div>
    </div>
</form>

<!-- Information Panel -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Stock Addition Process & Duplicate Prevention
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Duplicate Prevention Logic:</h4>
                        <ul>
                            <li><strong>Unique Check:</strong> System checks for existing stock by item name + batch number + vendor</li>
                            <li><strong>Update Existing:</strong> If duplicate found, quantity is added to existing stock</li>
                            <li><strong>Create New:</strong> If no duplicate, new stock record is created</li>
                            <li><strong>Vendor Billing:</strong> Separate billing record created for accounting</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4>Important Notes:</h4>
                        <ul>
                            <li>Ensure batch numbers are unique per vendor</li>
                            <li>Verify expiry dates are correct</li>
                            <li>Check quantities match received items</li>
                            <li>Review pricing before submission</li>
                            <li>System follows exact same logic as original PO system</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Form validation
    $('#stockAdditionForm').on('submit', function(e) {
        var isValid = true;
        var hasItems = false;
        
        // Check if at least one item has quantity received > 0
        $('input[name^="quantity_received_"]').each(function() {
            if (parseFloat($(this).val()) > 0) {
                hasItems = true;
                return false;
            }
        });
        
        if (!hasItems) {
            alert('Please enter quantity received for at least one item.');
            isValid = false;
        }
        
        // Validate required fields for items with quantity > 0
        $('input[name^="quantity_received_"]').each(function() {
            if (parseFloat($(this).val()) > 0) {
                var rowIndex = $(this).attr('name').split('_')[2];
                
                // Check batch number
                if (!$('input[name="batch_number_' + rowIndex + '"]').val().trim()) {
                    alert('Please enter batch number for item in row ' + rowIndex + '.');
                    isValid = false;
                    return false;
                }
                
                // Check expiry date
                if (!$('input[name="expiry_date_' + rowIndex + '"]').val()) {
                    alert('Please enter expiry date for item in row ' + rowIndex + '.');
                    isValid = false;
                    return false;
                }
                
                // Check purchase price
                if (parseFloat($('input[name="purchase_price_' + rowIndex + '"]').val()) <= 0) {
                    alert('Please enter valid purchase price for item in row ' + rowIndex + '.');
                    isValid = false;
                    return false;
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Auto-calculate selling price based on purchase price (if not set)
    $('input[name^="purchase_price_"]').on('change', function() {
        var rowIndex = $(this).attr('name').split('_')[2];
        var purchasePrice = parseFloat($(this).val()) || 0;
        var sellingPriceInput = $('input[name="selling_price_' + rowIndex + '"]');
        
        if (sellingPriceInput.val() == '0' || sellingPriceInput.val() == '') {
            // Set selling price as 20% markup on purchase price
            sellingPriceInput.val((purchasePrice * 1.2).toFixed(2));
        }
        
        // Set MRP as 30% markup on purchase price if not set
        var mrpInput = $('input[name="mrp_' + rowIndex + '"]');
        if (mrpInput.val() == '0' || mrpInput.val() == '') {
            mrpInput.val((purchasePrice * 1.3).toFixed(2));
        }
    });
    
    // Auto-generate batch number if not provided
    $('input[name^="batch_number_"]').on('blur', function() {
        if (!$(this).val().trim()) {
            var today = new Date();
            var batchNumber = 'BATCH' + today.getFullYear() + 
                            String(today.getMonth() + 1).padStart(2, '0') + 
                            String(today.getDate()).padStart(2, '0') + 
                            Math.floor(Math.random() * 1000);
            $(this).val(batchNumber);
        }
    });
});
</script>
