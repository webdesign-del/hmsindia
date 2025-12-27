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
                                    <?php if(!empty($sale->remarks)): ?>
                                    <p><strong>Payment Remarks:</strong> <?php echo htmlspecialchars($sale->remarks); ?></p>
                                    <?php endif; ?>
                                    
                                    <!-- Show Accountant Approval Status -->
                                    <?php 
                                    $approval_status = isset($sale->accountant_approval_status) ? $sale->accountant_approval_status : 'PENDING';
                                    if($sale->status == 'CONFIRMED'): 
                                    ?>
                                    <hr>
                                    <p><strong>Accountant Approval:</strong> 
                                        <span class="badge <?php 
                                            echo $approval_status == 'APPROVED' ? 'badge-success' : 
                                                ($approval_status == 'DISAPPROVED' ? 'badge-danger' : 
                                                ($approval_status == 'CANCELLED' ? 'badge-secondary' : 'badge-warning')); 
                                        ?>">
                                            <?php echo $approval_status; ?>
                                        </span>
                                    </p>
                                    <?php if(isset($sale->accountant_approved_by_name) && !empty($sale->accountant_approved_by_name)): ?>
                                    <p><strong>Reviewed By:</strong> <?php echo htmlspecialchars($sale->accountant_approved_by_name); ?></p>
                                    <?php endif; ?>
                                    <?php if(isset($sale->accountant_approved_at) && !empty($sale->accountant_approved_at)): ?>
                                    <p><strong>Reviewed On:</strong> <?php echo date('M d, Y h:i A', strtotime($sale->accountant_approved_at)); ?></p>
                                    <?php endif; ?>
                                    <?php if(isset($sale->accountant_remarks) && !empty($sale->accountant_remarks)): ?>
                                    <p><strong>Remarks:</strong> <?php echo htmlspecialchars($sale->accountant_remarks); ?></p>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Accountant Approval Section - Only for accountants on CONFIRMED sales with PENDING status -->
            <?php 
            $is_accountant = isset($_SESSION['logged_accountant']) && !empty($_SESSION['logged_accountant']);
            $approval_status = isset($sale->accountant_approval_status) ? $sale->accountant_approval_status : 'PENDING';
            
            if($is_accountant && $sale->status == 'CONFIRMED' && $approval_status == 'PENDING'): 
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <i class="fa fa-gavel"></i> <strong>Accountant Review Required</strong>
                        </div>
                        <div class="panel-body">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> 
                                Please review this invoice carefully and then <strong>Approve</strong>, <strong>Disapprove</strong>, or <strong>Cancel</strong> this sale.
                            </div>
                            
                            <form id="accountantApprovalForm" method="post">
                                <input type="hidden" name="sale_id" value="<?php echo $sale->id; ?>">
                                
                                <div class="form-group">
                                    <label><strong>Your Decision:</strong></label>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-4">
                                            <label class="btn btn-success btn-block approval-btn" style="padding: 20px;">
                                                <strong style="color:black">APPROVE</strong><br>
                                                <input type="radio" name="approval_action" value="APPROVED" style="margin-right: 8px;" required>
                                                <i class="fa fa-check fa-2x"></i><br><br>
                                                <small>Confirm this sale is valid</small>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="btn btn-danger btn-block approval-btn" style="padding: 20px;">
                                                <strong style="color:black">DISAPPROVE</strong><br>
                                                <input type="radio" name="approval_action" value="DISAPPROVED" style="margin-right: 8px;">
                                                <i class="fa fa-times fa-2x"></i><br><br>
                                                <small>Reject & restore stock</small>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="btn btn-secondary btn-block approval-btn" style="padding: 20px; background: #6c757d; color: white;">
                                                <strong style="color:black">CANCEL</strong><br>
                                                <input type="radio" name="approval_action" value="CANCELLED" style="margin-right: 8px;">
                                                <i class="fa fa-ban fa-2x"></i><br><br>
                                                <strong>CANCEL</strong><br>
                                                <small>Cancel & restore stock</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="approval_remarks"><strong>Remarks / Reason:</strong> <span class="text-danger">*</span></label>
                                    <textarea name="remarks" id="approval_remarks" class="form-control" rows="3" 
                                              placeholder="Enter your reason for this decision (required)" ></textarea>
                                </div>
                                
                                <div class="form-group text-right">
                                    <a href="<?php echo base_url('stocks_new/sales'); ?>" class="btn btn-default">
                                        <i class="fa fa-arrow-left"></i> Back to Sales
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitApprovalBtn">
                                        <i class="fa fa-check"></i> Submit Decision
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
            $(document).ready(function() {
                // Highlight selected option
                $('input[name="approval_action"]').on('change', function() {
                    $('.approval-btn').removeClass('active').css('opacity', '0.6');
                    $(this).parent().addClass('active').css('opacity', '1');
                });
                
                // Set initial opacity
                $('.approval-btn').css('opacity', '0.6');
                
                // Form submission
                $('#accountantApprovalForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    var action = $('input[name="approval_action"]:checked').val();
                    var remarks = $('#approval_remarks').val().trim();
                    var saleId = $('input[name="sale_id"]').val();
                    
                    if (!action) {
                        alert('Please select an action (Approve, Disapprove, or Cancel)');
                        return false;
                    }
                    // Confirm for disapprove/cancel
                    if (action == 'DISAPPROVED' || action == 'CANCELLED') {
                        if (!confirm('This will restore the stock to inventory. Are you sure?')) {
                            return false;
                        }
                    }
                    
                    var $btn = $('#submitApprovalBtn');
                    $btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
                    
                    $.ajax({
                        url: '<?php echo base_url("stocks_new/accountant_approve_sale"); ?>',
                        type: 'POST',
                        data: {
                            sale_id: saleId,
                            approval_action: action,
                            remarks: remarks
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                window.location.href = '<?php echo base_url("stocks_new/sales"); ?>';
                            } else {
                                alert('Error: ' + response.message);
                                $btn.html('<i class="fa fa-check"></i> Submit Decision').prop('disabled', false);
                            }
                        },
                        error: function() {
                            alert('Error connecting to server. Please try again.');
                            $btn.html('<i class="fa fa-check"></i> Submit Decision').prop('disabled', false);
                        }
                    });
                });
            });
            </script>
            <?php endif; ?>
            
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
                                <!-- Hidden field to store the GST rate -->
                                <input type="hidden" id="gst_rate" name="gst_rate" value="0">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Select Batch *</label>
                                            <div class="col-sm-8">
                                                <select name="batch_id" id="batch_id_select" class="form-control" required>
                                                    <option value="">Select Batch (FEFO Order)</option>
                                                    <?php foreach($batches as $batch): ?>
                                                        <option value="<?php echo $batch->id; ?>" 
                                                                data-expiry="<?php echo $batch->expiry_date; ?>"
                                                                data-price="<?php echo $batch->selling_price; ?>"
                                                                data-available="<?php echo $batch->available_quantity; ?>"
                                                                data-medicine="<?php echo $batch->medicine_name; ?>"
                                                                data-gst-rate="<?php echo $batch->gst_rate; ?>"
                                                                data-pack-size="<?php echo $batch->pack_size; ?>"
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
                                                    
                                                    <!-- *** THIS IS THE NEW CODE *** -->
                                                    <p><strong>GST Rate:</strong> <span id="gst_rate_display" style="font-weight: bold; color: #d9534f;"></span></p>
                                                    <p><strong>Taxable Amount:</strong> <span id="taxable_amount_display" style="font-weight: bold; color: #337ab7;"></span></p>
                                                    <!-- *** END NEW CODE *** -->
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
                                            <label class="col-sm-4 control-label">Unit Price (Excl. Tax) *</label>
                                            <div class="col-sm-8">
                                                <input type="number" name="unit_price" class="form-control" placeholder="Unit price" step="0.0001" min="0" required>
                                                <input type="hidden" name="unit_price_one" class="form-control" placeholder="Unit price" step="0.0001" min="0" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Subtotal</label>
                                            <div class="col-sm-8">
                                                <input type="number" name="subtotal" class="form-control" readonly>
                                            </div>
                                        </div>
                                        
                               
                                        
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Tax Amount (₹)</label>
                                            <div class="col-sm-8">
                                                <input type="number" name="tax_amount" class="form-control" placeholder="0.00" step="0.0001" min="0" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Total</label>
                                            <div class="col-sm-8">
                                                <input type="number" name="total" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Discount (%)</label>
                                            <div class="col-sm-8">
                                                <input type="number" name="discount_percent" class="form-control" placeholder="0.00" step="0.01" min="0" max="100">
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
                                                <th>Mrp Value</th>
                                                <th>Taxable Value</th>
                                                <th>Discount Percentage</th>
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
                                                    
                                                    <!-- This Unit Price in the table is now the (Subtotal / Qty) -->
                                                    <td>₹<?php echo number_format($item->subtotal / $item->quantity_sold, 2); ?></td>
                                                    
                                                    <td>₹<?php echo number_format($item->subtotal, 2); ?></td>
                                                    <td>₹<?php echo number_format($item->taxable_Value, 2); ?></td>
                                                    <td><?php echo number_format($item->discount_percentage); ?>%</td>
                                                    <td>₹<?php echo number_format($item->discount_amount, 2); ?></td>
                                                    <td>₹<?php echo number_format($item->tax_amount, 2); ?></td>
                                                    <td>₹<?php echo number_format($item->total, 2); ?></td>
                                                    
                                                    <?php if($sale->status == 'DRAFT'): ?>
                                                        <td>
                                                            <a href="<?php echo base_url('stocks_new/remove_sale_item/' . $item->id); ?>" 
                                                            class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Are you sure you want to remove this item?')">
                                                                <i class="fa fa-trash-o"></i> Remove
                                                            </a>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="info">
                                                <th colspan="11" style="text-align:right;">Subtotal</th>
                                                <th>₹<?php echo number_format($sale->subtotal, 2); ?></th>
                                                <?php if($sale->status == 'DRAFT'): ?> <th></th> <?php endif; ?>
                                            </tr>
                                            <tr class="info">
                                                <th colspan="11" style="text-align:right;">Total Discount</th>
                                                <th>- ₹<?php echo number_format($sale->discount_amount, 2); ?></th>
                                                <?php if($sale->status == 'DRAFT'): ?> <th></th> <?php endif; ?>
                                            </tr>
                                            <!-- <tr class="info">
                                                <th colspan="9" style="text-align:right;">Total Tax</th>
                                                <th>+ ₹<?php echo number_format($sale->tax_amount, 2); ?></th>
                                                <?php if($sale->status == 'DRAFT'): ?> <th></th> <?php endif; ?>
                                            </tr> -->
                                            <tr class="info" style="font-weight:bold; font-size: 1.1em;">
                                                <th colspan="11" style="text-align:right;">Grand Total</th>
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
                            
                            <!-- <a href="<?php echo base_url('stocks_new/print_sale/' . $sale->id); ?>" target="_blank" class="btn btn-info">
                                <i class="fa fa-print"></i> Print Bill
                            </a> -->
                            
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

    <!-- *** CORRECTED JAVASCRIPT *** -->
    <script>
    
    /**
     * This is the ONLY function that calculates totals.
     * It now uses "Exclusive Tax" logic.
     */
    function calculateTotals() {
        var quantity = parseFloat($('input[name="quantity_sold"]').val()) || 0;
        var unitPrice = parseFloat($('input[name="unit_price"]').val()) || 0;
        var discountPercent = parseFloat($('input[name="discount_percent"]').val()) || 0;
        var gstRate = parseFloat($('#gst_rate').val()) || 0;
        var maxAvailable = parseInt($('input[name="quantity_sold"]').attr('max')) || 0;
        if (quantity > maxAvailable && maxAvailable > 0) {
            alert('Quantity cannot exceed available stock (' + maxAvailable + ').');
            $('input[name="quantity_sold"]').val(maxAvailable);
            quantity = maxAvailable;
        }
        var subtotal = quantity * unitPrice;
        var discountAmount = 0;
        if (discountPercent > 100) {
            alert('Discount cannot be greater than 100%.');
            $('input[name="discount_percent"]').val(100);
            discountPercent = 100;
        }
        discountAmount = subtotal * (discountPercent / 100);
        var finalTaxableAmount = subtotal - discountAmount;
        var taxAmount = 0;
        if (gstRate > 0) {
            taxAmount = finalTaxableAmount * (gstRate / 100);
        }
        var finalTotal = finalTaxableAmount + taxAmount;
        $('input[name="subtotal"]').val(subtotal.toFixed(2));
        $('input[name="tax_amount"]').val(taxAmount.toFixed(4)); // Now readonly
        $('input[name="total"]').val(finalTotal.toFixed(2));
    }
    function loadBatchDetails() {
        var selectedOption = $('#batch_id_select option:selected');
        
        if (selectedOption.val()) {
            var gstRate = selectedOption.data('gst-rate');
            var quentity =selectedOption.data('available');
            var pack_size =selectedOption.data('pack-size');
            var unitPrice_MRP = selectedOption.data('price')/pack_size; 
            var taxableAmount = 0;
            if (gstRate > 0) {
                taxableAmount = unitPrice_MRP / (1 + (gstRate / 100));
            } else {
                taxableAmount = unitPrice_MRP;
            }
            console.log(unitPrice_MRP);
            $('#medicine_name').text(selectedOption.data('medicine'));
            $('#brand_name').text(selectedOption.data('brand'));
            $('#expiry_date').text(selectedOption.data('expiry'));
            $('#available_qty').text(selectedOption.data('available'));
            $('#gst_rate_display').text(gstRate + '%'); 
            $('#taxable_amount_display').text('₹' + taxableAmount.toFixed(2)); 
            $('#medicine_details').show();
            $('input[name="unit_price"]').val(taxableAmount.toFixed(4));
            $('input[name="unit_price_one"]').val(unitPrice_MRP.toFixed(2));
            $('input[name="quantity_sold"]').attr('max', selectedOption.data('available'));
            $('input[name="quantity_sold"]').val(1); // Default to 1
            $('input[name="discount_percent"]').val(''); // Clear discount
            $('#gst_rate').val(gstRate);
            calculateTotals();
        } else {
            // Clear all fields
            $('#medicine_details').hide();
            $('input[name="unit_price"]').val('');
            $('input[name="unit_price_one"]').val('');
            $('input[name="quantity_sold"]').val('').removeAttr('max');
            $('input[name="discount_percent"]').val('');
            $('input[name="tax_amount"]').val('');
            $('#gst_rate').val('0');
            $('#gst_rate_display').text(''); 
            $('#taxable_amount_display').text(''); // Clear the new field
            calculateTotals(); // Recalculate (will be 0)
        }
    }

    $(document).ready(function() {
        // Initialize Select2 for batch dropdown with search functionality
        $('#batch_id_select').select2({
            placeholder: 'Select Batch (FEFO Order)',
            allowClear: true,
            width: '100%'
        });
        
        // --- Event Listeners ---
        
        // Recalculate total when ANY of these 3 fields change
        $('input[name="quantity_sold"], input[name="unit_price"], input[name="discount_percent"]').on('input change', function() {
            calculateTotals();
        });

        // Load batch details on change
        $('#batch_id_select').on('change', loadBatchDetails);
        
        // Remove the calculator button's click event (it's not needed)
        $('#calculate_tax_btn').on('click', function() {
             alert('Tax is calculated automatically based on the item\'s GST rate.');
        });
        
        // Initial calculation on page load
        calculateTotals();
    });
    </script>