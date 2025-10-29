<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-clipboard-check"></i> Stock Audit
                    <small>Physical stock verification and adjustment</small>
                </h1>
            </div>
        </div>
        
        <!-- Audit Form -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-clipboard-check"></i> Stock Audit Form
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
                        
                        <form action="<?php echo base_url('stocks_new/process_audit'); ?>" method="post" class="form-horizontal">
                            <input type="hidden" name="action" value="stock_audit">
                            
                            <!-- Audit Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Audit Date *</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="audit_date" class="form-control" value="<?php echo set_value('audit_date', date('Y-m-d')); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Center *</label>
                                        <div class="col-sm-8">
                                            <select name="center_id" class="form-control" required>
                                                <option value="">Select Center</option>
                                                <?php foreach($centers as $center): ?>
                                                    <option value="<?php echo $center->ID; ?>" <?php echo set_select('center_id', $center->ID); ?>>
                                                        <?php echo $center->center_name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Audit Type *</label>
                                        <div class="col-sm-8">
                                            <select name="audit_type" class="form-control" required>
                                                <option value="">Select Audit Type</option>
                                                <option value="FULL_AUDIT" <?php echo set_select('audit_type', 'FULL_AUDIT'); ?>>Full Audit</option>
                                                <option value="PARTIAL_AUDIT" <?php echo set_select('audit_type', 'PARTIAL_AUDIT'); ?>>Partial Audit</option>
                                                <option value="SPOT_CHECK" <?php echo set_select('audit_type', 'SPOT_CHECK'); ?>>Spot Check</option>
                                                <option value="EXPIRY_AUDIT" <?php echo set_select('audit_type', 'EXPIRY_AUDIT'); ?>>Expiry Audit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Auditor Name *</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="auditor_name" class="form-control" placeholder="Enter Auditor Name" value="<?php echo set_value('auditor_name'); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Audit Purpose</label>
                                        <div class="col-sm-8">
                                            <select name="audit_purpose" class="form-control">
                                                <option value="">Select Purpose</option>
                                                <option value="ROUTINE_CHECK" <?php echo set_select('audit_purpose', 'ROUTINE_CHECK'); ?>>Routine Check</option>
                                                <option value="DISCREPANCY_INVESTIGATION" <?php echo set_select('audit_purpose', 'DISCREPANCY_INVESTIGATION'); ?>>Discrepancy Investigation</option>
                                                <option value="REGULATORY_COMPLIANCE" <?php echo set_select('audit_purpose', 'REGULATORY_COMPLIANCE'); ?>>Regulatory Compliance</option>
                                                <option value="YEAR_END_AUDIT" <?php echo set_select('audit_purpose', 'YEAR_END_AUDIT'); ?>>Year End Audit</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea name="remarks" class="form-control" rows="2" placeholder="Enter remarks"><?php echo set_value('remarks'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Audit Items -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Audit Items</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="audit_items_table">
                                            <thead>
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Batch</th>
                                                    <th>System Quantity</th>
                                                    <th>Physical Quantity</th>
                                                    <th>Variance</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="audit_items[0][batch_id]" class="form-control batch_select" required>
                                                            <option value="">Select Medicine</option>
                                                            <?php foreach($available_batches as $batch): ?>
                                                                <option value="<?php echo $batch->id; ?>" 
                                                                        data-medicine="<?php echo $batch->medicine_name; ?>"
                                                                        data-system-qty="<?php echo $batch->available_quantity; ?>">
                                                                    <?php echo $batch->medicine_name . ' - ' . $batch->batch_number; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td><span class="batch_number">-</span></td>
                                                    <td><span class="system_quantity">-</span></td>
                                                    <td>
                                                        <input type="number" name="audit_items[0][physical_quantity]" class="form-control physical_quantity" min="0" required>
                                                    </td>
                                                    <td><span class="variance">-</span></td>
                                                    <td><span class="status">-</span></td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove_row">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-success" id="add_audit_item">
                                        <i class="fa fa-plus"></i> Add Item
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Audit Summary -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Total Items Audited</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="total_items" class="form-control" id="total_items" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Items with Variance</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="variance_items" class="form-control" id="variance_items" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Save Audit
                                            </button>
                                            <a href="<?php echo base_url('stocks_new/audit_reports'); ?>" class="btn btn-default">
                                                <i class="fa fa-arrow-left"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Audit Guidelines -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Audit Guidelines
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><strong>Physical Count:</strong> Count all physical stock carefully</li>
                            <li><strong>Batch Verification:</strong> Verify batch numbers and expiry dates</li>
                            <li><strong>Variance Analysis:</strong> Investigate any discrepancies found</li>
                            <li><strong>Documentation:</strong> Document all findings and adjustments</li>
                            <li><strong>FEFO Compliance:</strong> Ensure FEFO principle is maintained</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var rowCount = 1;
    
    // Add new audit item row
    $('#add_audit_item').click(function() {
        var newRow = '<tr>' +
            '<td><select name="audit_items[' + rowCount + '][batch_id]" class="form-control batch_select" required>' +
            '<option value="">Select Medicine</option>' +
            '<?php foreach($available_batches as $batch): ?>' +
            '<option value="<?php echo $batch->id; ?>" data-medicine="<?php echo $batch->medicine_name; ?>" data-system-qty="<?php echo $batch->available_quantity; ?>"><?php echo $batch->medicine_name . ' - ' . $batch->batch_number; ?></option>' +
            '<?php endforeach; ?>' +
            '</select></td>' +
            '<td><span class="batch_number">-</span></td>' +
            '<td><span class="system_quantity">-</span></td>' +
            '<td><input type="number" name="audit_items[' + rowCount + '][physical_quantity]" class="form-control physical_quantity" min="0" required></td>' +
            '<td><span class="variance">-</span></td>' +
            '<td><span class="status">-</span></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash"></i></button></td>' +
            '</tr>';
        
        $('#audit_items_table tbody').append(newRow);
        rowCount++;
    });
    
    // Remove row
    $(document).on('click', '.remove_row', function() {
        $(this).closest('tr').remove();
        calculateSummary();
    });
    
    // Batch selection change
    $(document).on('change', '.batch_select', function() {
        var selectedOption = $(this).find('option:selected');
        var row = $(this).closest('tr');
        
        if (selectedOption.val()) {
            row.find('.batch_number').text(selectedOption.data('medicine'));
            row.find('.system_quantity').text(selectedOption.data('system-qty'));
        } else {
            row.find('.batch_number, .system_quantity').text('-');
        }
    });
    
    // Calculate variance
    $(document).on('input', '.physical_quantity', function() {
        var row = $(this).closest('tr');
        var physicalQty = parseFloat($(this).val()) || 0;
        var systemQty = parseFloat(row.find('.system_quantity').text()) || 0;
        var variance = physicalQty - systemQty;
        
        row.find('.variance').text(variance);
        
        if (variance > 0) {
            row.find('.status').html('<span class="badge badge-success">Surplus</span>');
        } else if (variance < 0) {
            row.find('.status').html('<span class="badge badge-danger">Shortage</span>');
        } else {
            row.find('.status').html('<span class="badge badge-success">Match</span>');
        }
        
        calculateSummary();
    });
    
    // Calculate summary
    function calculateSummary() {
        var totalItems = $('#audit_items_table tbody tr').length;
        var varianceItems = 0;
        
        $('.variance').each(function() {
            var variance = parseFloat($(this).text()) || 0;
            if (variance !== 0) {
                varianceItems++;
            }
        });
        
        $('#total_items').val(totalItems);
        $('#variance_items').val(varianceItems);
    }
});
</script>

