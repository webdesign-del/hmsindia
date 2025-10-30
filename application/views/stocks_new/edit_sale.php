<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-edit"></i> Edit Sale
                    <small>Add items to sale with FEFO batch selection</small>
                </h1>
            </div>
        </div>
        
        <!-- Sale Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Sale Details
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Sale Number:</strong> <?php echo $sale->sale_number; ?></p>
                                <p><strong>Patient:</strong> <?php echo $sale->patient_name; ?></p>
                                <p><strong>Center:</strong> <?php echo $sale->center_name; ?></p>
                                <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($sale->sale_date)); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> 
                                    <span class="badge <?php 
                                        echo $sale->status == 'CONFIRMED' ? 'badge-success' : 
                                            ($sale->status == 'CANCELLED' ? 'badge-danger' : 'badge-warning'); 
                                    ?>">
                                        <?php echo $sale->status; ?>
                                    </span>
                                </p>
                                <p><strong>Payment:</strong> 
                                    <span class="badge <?php 
                                        echo $sale->payment_status == 'PAID' ? 'badge-success' : 
                                            ($sale->payment_status == 'PARTIAL' ? 'badge-warning' : 'badge-danger'); 
                                    ?>">
                                        <?php echo $sale->payment_status; ?>
                                    </span>
                                </p>
                                <p><strong>Items:</strong> <?php echo $sale->total_items; ?></p>
                                <p><strong>Total Amount:</strong> ₹<?php echo number_format($sale->total_amount, 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Sale Item Form -->
        <?php if($sale->status == 'DRAFT'): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus"></i> Add Sale Item
                    </div>
                    <div class="panel-body">
                        <?php if(validation_errors()): ?>
                            <div class="alert alert-danger">
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="<?php echo base_url('stocks_new/edit_sale/' . $sale->id); ?>" method="post" class="form-horizontal">
                            <input type="hidden" name="action" value="add_sale_item">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Select Batch *</label>
                                        <div class="col-sm-8">
                                            <select name="batch_id" class="form-control" required onchange="loadBatchDetails()">
                                                <option value="">Select Batch (FEFO Order)</option>
                                                <?php foreach($batches as $batch): ?>
                                                    <option value="<?php echo $batch->id; ?>" 
                                                            data-expiry="<?php echo $batch->expiry_date; ?>"
                                                            data-price="<?php echo $batch->selling_price; ?>"
                                                            data-available="<?php echo $batch->available_quantity; ?>"
                                                            data-medicine="<?php echo $batch->medicine_name; ?>"
                                                            data-gst-rate="<?php echo $batch->gst_rate; ?>"
                                                            data-brand="<?php echo $batch->brand_name; ?>">
                                                        <?php echo $batch->medicine_name . ' - ' . $batch->batch_number . ' (Exp: ' . date('M d, Y', strtotime($batch->expiry_date)) . ') - Available: ' . $batch->available_quantity; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Medicine Details</label>
                                        <div class="col-sm-8">
                                            <div id="medicine_details" class="well" style="display: none;">
                                                <p><strong>Medicine:</strong> <span id="medicine_name"></span></p>
                                                <p><strong>Brand:</strong> <span id="brand_name"></span></p>
                                                <p><strong>Expiry:</strong> <span id="expiry_date"></span></p>
                                                <p><strong>Available:</strong> <span id="available_qty"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Quantity *</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="quantity_sold" class="form-control" placeholder="Enter quantity" min="1" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Unit Price *</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="unit_price" class="form-control" placeholder="Unit price" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Subtotal</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="subtotal" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                   <div class="form-group">
                                        <label class="col-sm-4 control-label">Discount (₹)</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="discount_amount" class="form-control" placeholder="0.00" step="0.01" min="0">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tax Amount (₹)</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="number" name="tax_amount" class="form-control" placeholder="0.00" step="0.01" min="0">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info" id="calculate_tax_btn" title="Auto-calculate tax">
                                                        <i class="fa fa-calculator"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Total</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="total" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea name="remarks" class="form-control" rows="2" placeholder="Enter remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-plus"></i> Add Item
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Sale Items List -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Sale Items
                        <span class="badge pull-right"><?php echo count($sale_items); ?> items</span>
                    </div>
                    <div class="panel-body">
                        <?php if(!empty($sale_items)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Brand</th>
                                            <th>Batch</th>
                                            <th>Expiry Date</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                            <th>Discount</th> <th>Tax</th>     <th>Total</th>
                                            <?php if($sale->status == 'DRAFT'): ?>
                                                <th>Actions</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($sale_items as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item->medicine_name ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($item->brand_name ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($item->batch_number ?? 'N/A'); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($item->expiry_date)); ?></td>
                                                <td><?php echo number_format($item->quantity_sold); ?></td>
                                                <td>₹<?php echo number_format($item->unit_price, 2); ?></td>
                                                <td>₹<?php echo number_format($item->subtotal, 2); ?></td>
                                                
                                                <td>₹<?php echo number_format($item->discount_amount, 2); ?></td>
                                                <td>₹<?php echo number_format($item->tax_amount, 2); ?></td>
                                                
                                                <td>₹<?php echo number_format($item->total, 2); ?></td>
                                                
                                                <?php if($sale->status == 'DRAFT'): ?>
                                                    <td>
                                                        <a href="<?php echo base_url('stocks_new/remove_sale_item/' . $item->id); ?>" 
                                                        class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Are you sure you want to remove this item?')">
                                                            <i class="fa fa-trash"></i> Remove
                                                        </a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                   <tfoot>
                                    <tr class="info">
                                        <th colspan="9" style="text-align:right;">Subtotal</th>
                                        <th>₹<?php echo number_format($sale->subtotal, 2); ?></th>
                                        <?php if($sale->status == 'DRAFT'): ?> <th></th> <?php endif; ?>
                                    </tr>
                                    <tr class="info">
                                        <th colspan="9" style="text-align:right;">Total Discount</th>
                                        <th>- ₹<?php echo number_format($sale->discount_amount, 2); ?></th>
                                        <?php if($sale->status == 'DRAFT'): ?> <th></th> <?php endif; ?>
                                    </tr>
                                    <tr class="info">
                                        <th colspan="9" style="text-align:right;">Total Tax</th>
                                        <th>+ ₹<?php echo number_format($sale->tax_amount, 2); ?></th>
                                        <?php if($sale->status == 'DRAFT'): ?> <th></th> <?php endif; ?>
                                    </tr>
                                    <tr class="info" style="font-weight:bold; font-size: 1.1em;">
                                        <th colspan="9" style="text-align:right;">Grand Total</th>
                                        <th>₹<?php echo number_format($sale->total_amount, 2); ?></th>
                                        <?php if($sale->status == 'DRAFT'): ?> <th></th> <?php endif; ?>
                                    </tr>
                                </tfoot>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fa fa-info-circle fa-2x"></i><br>
                                No items added yet. Add items using the form above.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sale Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-cog"></i> Sale Actions
                    </div>
                    <div class="panel-body">
                        <?php if($sale->status == 'DRAFT'): ?>
                            <?php if(!empty($sale_items)): ?>
                                <a href="<?php echo base_url('stocks_new/confirm_sale/' . $sale->id); ?>" 
                                   class="btn btn-success" 
                                   onclick="return confirm('Are you sure you want to confirm this sale? This will reduce stock using FEFO.')">
                                    <i class="fa fa-check"></i> Confirm Sale
                                </a>
                            <?php else: ?>
                                <button class="btn btn-success" disabled>
                                    <i class="fa fa-check"></i> Confirm Sale (Add items first)
                                </button>
                            <?php endif; ?>
                        <?php elseif($sale->status == 'CONFIRMED'): ?>
                            <span class="badge badge-success">Sale confirmed and stock reduced using FEFO</span>
                        <?php endif; ?>
                        
                        <a href="<?php echo base_url('stocks_new/print_sale/' . $sale->id); ?>" target="_blank" class="btn btn-info">
                            <i class="fa fa-print"></i> Print Bill
                        </a>
                        
                        <a href="<?php echo base_url('stocks_new/sales'); ?>" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Sales
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FEFO Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> FEFO Sale Process
                    </div>
                    <div class="panel-body">
                        <p><strong>FEFO (First Expiry First Out)</strong> ensures that batches with the earliest expiry dates are sold first.</p>
                        <ul>
                            <li>Batches are automatically sorted by expiry date (earliest first)</li>
                            <li>Only available stock in the selected center is shown</li>
                            <li>System prevents sale of expired medicines</li>
                            <li>Complete traceability maintained for each batch sold</li>
                            <li>Stock is automatically reduced when sale is confirmed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// --- Main function to update totals ---
// This function READS all fields and updates the final total
function calculateTotals() {
    var quantity = parseFloat($('input[name="quantity_sold"]').val()) || 0;
    var unitPrice = parseFloat($('input[name="unit_price"]').val()) || 0;
    var discount = parseFloat($('input[name="discount_amount"]').val()) || 0;
    
    // READ the tax amount from the input field
    var taxAmount = parseFloat($('input[name="tax_amount"]').val()) || 0;

    // Check quantity against max available
    var maxAvailable = parseInt($('input[name="quantity_sold"]').attr('max')) || 0;
    if (quantity > maxAvailable && maxAvailable > 0) {
        alert('Quantity cannot exceed available stock (' + maxAvailable + ').');
        $('input[name="quantity_sold"]').val(maxAvailable);
        quantity = maxAvailable;
    }

    var subtotal = quantity * unitPrice;
    
    // Check discount against subtotal
    if (discount > subtotal) {
        alert('Discount cannot be greater than subtotal (₹' + subtotal.toFixed(2) + ').');
        $('input[name="discount_amount"]').val(subtotal.toFixed(2));
        discount = subtotal;
    }
    
    var totalAfterDiscount = subtotal - discount;
    var total = totalAfterDiscount + taxAmount; // Total = (Subtotal - Discount) + Tax
    
    // Update readonly fields
    $('input[name="subtotal"]').val(subtotal.toFixed(2));
    $('input[name="total"]').val(total.toFixed(2));
}

// --- Function to auto-calculate tax ---
// This function WRITES to the tax field, then calls calculateTotals
function autoCalculateTax(isNewItem = false) {
    var quantity = parseFloat($('input[name="quantity_sold"]').val()) || 0;
    // If we just selected a new item, default quantity to 1 for this calculation
    if (isNewItem) {
        quantity = 1; 
    }
    
    var unitPrice = parseFloat($('input[name="unit_price"]').val()) || 0;
    var discount = parseFloat($('input[name="discount_amount"]').val()) || 0;
    var gst_rate = parseFloat($('select[name="batch_id"] option:selected').data('gst-rate')) || 0;

    if (gst_rate === 0 && !isNewItem) { // Don't alert if it's just a page load
        alert('No GST rate found for this batch. Please enter tax manually.');
    }

    var subtotal = quantity * unitPrice;
    if (discount > subtotal) {
        discount = subtotal;
    }

    var totalAfterDiscount = subtotal - discount;
    var taxAmount = totalAfterDiscount * (gst_rate / 100);

    // --- SET the tax amount ---
    $('input[name="tax_amount"]').val(taxAmount.toFixed(2));
    
    // --- Trigger final total calculation ---
    calculateTotals();
}

// --- Function called on batch select change ---
function loadBatchDetails() {
    var selectedOption = $('select[name="batch_id"] option:selected');
    
    if (selectedOption.val()) {
        $('#medicine_name').text(selectedOption.data('medicine'));
        $('#brand_name').text(selectedOption.data('brand'));
        $('#expiry_date').text(selectedOption.data('expiry'));
        $('#available_qty').text(selectedOption.data('available'));
        $('input[name="unit_price"]').val(selectedOption.data('price'));
        $('#medicine_details').show();
        
        // Set max quantity
        $('input[name="quantity_sold"]').attr('max', selectedOption.data('available'));
        
        // Reset fields
        $('input[name="quantity_sold"]').val(1); // Default to 1
        $('input[name="discount_amount"]').val(''); // Clear discount
        
        // Auto-calculate tax for the new item (with quantity 1)
        autoCalculateTax(true); 
        // calculateTotals() is called inside autoCalculateTax()
        
    } else {
        // Clear all fields
        $('#medicine_details').hide();
        $('input[name="unit_price"]').val('');
        $('input[name="quantity_sold"]').val('').removeAttr('max');
        $('input[name="discount_amount"]').val('');
        $('input[name="tax_amount"]').val('');
        calculateTotals(); // Recalculate (will be 0)
    }
}


$(document).ready(function() {
    // --- Event Listeners ---
    
    // Recalculate total when ANY of the 4 fields change
    $('input[name="quantity_sold"], input[name="unit_price"], input[name="discount_amount"], input[name="tax_amount"]').on('input', function() {
        calculateTotals();
    });
    
    // Load batch details on change
    $('select[name="batch_id"]').on('change', loadBatchDetails);
    
    // Click handler for the new auto-calculate tax button
    $('#calculate_tax_btn').on('click', function() {
        autoCalculateTax(false); // 'false' = use the current quantity in the input
    });
    
    // Initial calculation on page load
    calculateTotals();
});
</script>