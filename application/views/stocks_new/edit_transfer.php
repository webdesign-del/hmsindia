<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-edit"></i> Edit Transfer
                    <small>Add items to transfer with FEFO selection</small>
                </h1>
            </div>
        </div>
        
        <!-- Transfer Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Transfer Details
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Transfer Number:</strong> <?php echo $transfer->transfer_number; ?></p>
                                <p><strong>Type:</strong> <?php echo str_replace('_', ' ', $transfer->transfer_type); ?></p>
                                <p><strong>From:</strong> <?php echo $transfer->from_center ?: 'Central Warehouse'; ?></p>
                                <p><strong>To:</strong> <?php echo $transfer->to_center; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($transfer->transfer_date)); ?></p>
                                <p><strong>Status:</strong> 
                                    <span class="badge <?php 
                                        echo $transfer->status == 'COMPLETED' ? 'badge-success' : 
                                            ($transfer->status == 'APPROVED' ? 'badge-info' : 'badge-warning'); 
                                    ?>">
                                        <?php echo $transfer->status; ?>
                                    </span>
                                </p>
                                <p><strong>Items:</strong> <?php echo count($transfer_items); ?></p>
                                <p><strong>Total Value:</strong> ₹<?php echo number_format(array_sum(array_column($transfer_items, 'total_price')), 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Debug Information -->
        <?php if(ENVIRONMENT === 'development'): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-bug"></i> Debug Information
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Transfer Data:</h5>
                                <pre><?php print_r($transfer); ?></pre>
                            </div>
                            <div class="col-md-6">
                                <h5>Batches Data (<?php echo count($batches); ?> found):</h5>
                                <pre><?php print_r($batches); ?></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Add Transfer Item Form -->
        <?php if($transfer->status == 'DRAFT'): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus"></i> Add Transfer Item
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
                        
                        <form action="<?php echo base_url('stocks_new/edit_transfer/' . $transfer->id); ?>" method="post" class="form-horizontal">
                            <input type="hidden" name="action" value="add_transfer_item">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Select Batch *</label>
                                        <div class="col-sm-8">
                                            <select name="batch_id" class="form-control" required onchange="loadBatchDetails()">
                                                <option value="">Select Batch (FEFO Order)</option>
                                                <?php if(!empty($batches)): ?>
                                                    <?php foreach($batches as $batch): ?>
                                                        <option value="<?php echo $batch->batch_id; ?>" 
                                                                data-expiry="<?php echo $batch->expiry_date; ?>"
                                                                data-price="<?php echo isset($batch->selling_price) ? $batch->selling_price : '0'; ?>"
                                                                data-available="<?php echo $batch->quantity_remaining; ?>"
                                                                data-medicine="<?php echo $batch->medicine_name; ?>"
                                                                data-brand="<?php echo $batch->brand_name; ?>">
                                                            <?php echo $batch->medicine_name . ' - ' . $batch->batch_number . ' (Exp: ' . date('M d, Y', strtotime($batch->expiry_date)) . ') - Available: ' . $batch->quantity_remaining; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <option value="" disabled>No batches available for this transfer type</option>
                                                <?php endif; ?>
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
                                            <input type="number" name="quantity_transferred" class="form-control" placeholder="Enter quantity" min="1" required>
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
                                        <label class="col-sm-4 control-label">Total Price</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="total_price" class="form-control" readonly>
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
        
        <!-- Transfer Items List -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Transfer Items
                        <span class="badge pull-right"><?php echo count($transfer_items); ?> items</span>
                    </div>
                    <div class="panel-body">
                        <?php if(!empty($transfer_items)): ?>
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
                                            <th>Total Price</th>
                                            <?php if($transfer->status == 'DRAFT'): ?>
                                                <th>Actions</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($transfer_items as $item): ?>
                                            <tr>
                                                <td><?php echo $item->medicine_name; ?></td>
                                                <td><?php echo $item->brand_name; ?></td>
                                                <td><?php echo $item->batch_number; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($item->expiry_date)); ?></td>
                                                <td><?php echo number_format($item->quantity_transferred); ?></td>
                                                <td>₹<?php echo number_format($item->unit_price, 2); ?></td>
                                                <td>₹<?php echo number_format($item->total_price, 2); ?></td>
                                                <?php if($transfer->status == 'DRAFT'): ?>
                                                    <td>
                                                        <a href="<?php echo base_url('stocks_new/remove_transfer_item/' . $item->id); ?>" 
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
                                            <th colspan="6">Total</th>
                                            <th>₹<?php echo number_format(array_sum(array_column($transfer_items, 'total_price')), 2); ?></th>
                                            <?php if($transfer->status == 'DRAFT'): ?>
                                                <th></th>
                                            <?php endif; ?>
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
        
        <!-- Transfer Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-cog"></i> Transfer Actions
                    </div>
                    <div class="panel-body">
                        <?php if($transfer->status == 'DRAFT'): ?>
                            <?php if(!empty($transfer_items)): ?>
                                <a href="<?php echo base_url('stocks_new/approve_transfer/' . $transfer->id); ?>" 
                                   class="btn btn-success" 
                                   onclick="return confirm('Are you sure you want to approve this transfer? This will move the stock.')">
                                    <i class="fa fa-check"></i> Approve Transfer
                                </a>
                            <?php else: ?>
                                <button class="btn btn-success" disabled>
                                    <i class="fa fa-check"></i> Approve Transfer (Add items first)
                                </button>
                            <?php endif; ?>
                        <?php elseif($transfer->status == 'APPROVED'): ?>
                            <span class="badge badge-info">Transfer approved and stock moved</span>
                        <?php elseif($transfer->status == 'COMPLETED'): ?>
                            <span class="badge badge-success">Transfer completed</span>
                        <?php endif; ?>
                        
                        <a href="<?php echo base_url('stocks_new/transfers'); ?>" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Transfers
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
                        <i class="fa fa-info-circle"></i> FEFO Batch Selection
                    </div>
                    <div class="panel-body">
                        <p><strong>FEFO (First Expiry First Out)</strong> ensures that batches with the earliest expiry dates are selected first for transfer.</p>
                        <ul>
                            <li>Batches are automatically sorted by expiry date (earliest first)</li>
                            <li>Only available stock is shown for selection</li>
                            <li>System prevents over-allocation of stock</li>
                            <li>Complete traceability maintained for each batch</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
        $('input[name="quantity_transferred"]').attr('max', selectedOption.data('available'));
    } else {
        $('#medicine_details').hide();
    }
}

$(document).ready(function() {
    // Calculate total price
    $('input[name="quantity_transferred"], input[name="unit_price"]').on('input', function() {
        var quantity = parseFloat($('input[name="quantity_transferred"]').val()) || 0;
        var unitPrice = parseFloat($('input[name="unit_price"]').val()) || 0;
        var totalPrice = quantity * unitPrice;
        $('input[name="total_price"]').val(totalPrice.toFixed(2));
    });
});
</script>

