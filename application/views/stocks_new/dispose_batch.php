<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-trash"></i> Dispose Batch
            <small>Dispose stock for a specific batch</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if(validation_errors()): ?>
            <div class="alert alert-danger"> <?php echo validation_errors(); ?> </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"> <?php echo $this->session->flashdata('error'); ?> </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success"> <?php echo $this->session->flashdata('success'); ?> </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Batch Details
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Medicine:</strong> <?php echo htmlspecialchars($batch_info['medicine_name']); ?></p>
                        <p><strong>Batch Number:</strong> <?php echo htmlspecialchars($batch_info['batch_number']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Batch ID:</strong> <?php echo $batch_info['batch_id']; ?></p>
                        <p><strong>Expiry Date:</strong> <?php echo date('M d, Y', strtotime($batch_info['expiry_date'])); ?></p>
                        <p><strong>Purchase Cost:</strong> ₹<?php echo number_format($batch_info['purchase_price'], 2); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <i class="fa fa-trash"></i> Disposal Form
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url('stocks_new/dispose_batch/' . $batch_info['batch_id']); ?>" method="post" class="form-horizontal">
                    <input type="hidden" name="action" value="dispose_single_batch">
                    <input type="hidden" name="batch_id" value="<?php echo $batch_info['batch_id']; ?>">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Dispose From (Location) *</label>
                        <div class="col-sm-9">
                            <select name="location_key" id="location_select" class="form-control" required onchange="updateMaxQty()">
                                <option value="">Select Location (Available Stock)</option>
                                <?php foreach($batch_info['locations'] as $loc): ?>
                                    <option value="<?php echo $loc['type'] . '|' . $loc['id']; ?>" data-available="<?php echo $loc['quantity']; ?>">
                                        <?php echo htmlspecialchars($loc['name']) . ' (Available: ' . $loc['quantity'] . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Quantity to Dispose *</label>
                        <div class="col-sm-9">
                            <input type="number" name="quantity_disposed" id="quantity_disposed" class="form-control" placeholder="Enter quantity" min="1" max="0" required>
                            <small id="qty_help_block" class="text-muted" style="display: none;">Max available at this location: <span>0</span></small>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Disposal Date *</label>
                        <div class="col-sm-9">
                            <input type="date" name="disposal_date" class="form-control" value="<?php echo set_value('disposal_date', date('Y-m-d')); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Disposal Reason (Type) *</label>
                        <div class="col-sm-9">
                            <select name="disposal_type" class="form-control" required>
                                <option value="">Select Reason</option>
                                <option value="EXPIRED" <?php echo set_select('disposal_type', 'EXPIRED'); ?>>Expired Medicine</option>
                                <option value="DAMAGED" <?php echo set_select('disposal_type', 'DAMAGED'); ?>>Damaged Medicine</option>
                                <option value="RECALLED" <?php echo set_select('disposal_type', 'RECALLED'); ?>>Recalled Medicine</option>
                                <option value="CONTAMINATED" <?php echo set_select('disposal_type', 'CONTAMINATED'); ?>>Contaminated Medicine</option>
                                <option value="REGULATORY" <?php echo set_select('disposal_type', 'REGULATORY'); ?>>Regulatory Disposal</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Disposal Method *</label>
                        <div class="col-sm-9">
                             <select name="disposal_method" class="form-control" required>
                                <option value="">Select Disposal Method</option>
                                <option value="INCINERATION" <?php echo set_select('disposal_method', 'INCINERATION'); ?>>Incineration</option>
                                <option value="LAND_FILL" <?php echo set_select('disposal_method', 'LAND_FILL'); ?>>Land Fill</option>
                                <option value="RETURN_TO_VENDOR" <?php echo set_select('disposal_method', 'RETURN_TO_VENDOR'); ?>>Return to Vendor</option>
                                <option value="DESTRUCTION" <?php echo set_select('disposal_method', 'DESTRUCTION'); ?>>Destruction</option>
                                <option value="OTHER" <?php echo set_select('disposal_method', 'OTHER'); ?>>Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Authorized By *</label>
                        <div class="col-sm-9">
                            <input type="text" name="authorized_by" class="form-control" placeholder="Enter authorized person's name" value="<?php echo set_value('authorized_by'); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Disposal Company</label>
                        <div class="col-sm-9">
                            <input type="text" name="disposal_company" class="form-control" placeholder="Enter disposal company (if any)" value="<?php echo set_value('disposal_company'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Remarks</label>
                        <div class="col-sm-9">
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Enter remarks"><?php echo set_value('remarks'); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to dispose this stock? This action cannot be undone.')">
                                <i class="fa fa-trash"></i> Process Disposal
                            </button>
                            <a href="<?php echo base_url('stocks_new/batches'); // Link to batch list ?>" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateMaxQty() {
    var selectedOption = $('#location_select').find('option:selected');
    var maxQty = selectedOption.data('available');
    
    if (maxQty > 0) {
        $('#quantity_disposed').attr('max', maxQty);
        $('#qty_help_block').show().find('span').text(maxQty);
    } else {
        $('#quantity_disposed').attr('max', 0);
        $('#qty_help_block').hide();
    }
}

// Add validation to check quantity on input
$(document).on('input', '#quantity_disposed', function() {
    var quantity = parseInt($(this).val()) || 0;
    var maxAvailable = parseInt($(this).attr('max')) || 0;

    if (quantity > maxAvailable) {
        alert('Disposal quantity cannot exceed available quantity (' + maxAvailable + ')!');
        $(this).val(maxAvailable); // Reset to max value
    }
});
</script>