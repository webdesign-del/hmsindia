<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-trash-o"></i> Medicine Disposal
                    <small>Dispose expired and damaged medicines</small>
                </h1>
            </div>
        </div>
        
        <!-- Disposal Form -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-trash-o"></i> Medicine Disposal Form
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
                        
                        <form action="<?php echo base_url('stocks_new/process_disposal'); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="medicine_disposal">
                            
                            <!-- Disposal Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Disposal Date *</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="disposal_date" class="form-control" value="<?php echo set_value('disposal_date', date('Y-m-d')); ?>" required>
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
                                                <option value="CENTRAL_WAREHOUSE_NOIDA" <?php echo set_select('center_id', 'CENTRAL_WAREHOUSE_NOIDA'); ?>>Central warehouse Noida</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Disposal Type *</label>
                                        <div class="col-sm-8">
                                            <select name="disposal_type" class="form-control" required>
                                                <option value="">Select Disposal Type</option>
                                                <option value="EXPIRED" <?php echo set_select('disposal_type', 'EXPIRED'); ?>>Expired Medicine</option>
                                                <option value="DAMAGED" <?php echo set_select('disposal_type', 'DAMAGED'); ?>>Damaged Medicine</option>
                                                <option value="RECALLED" <?php echo set_select('disposal_type', 'RECALLED'); ?>>Recalled Medicine</option>
                                                <option value="CONTAMINATED" <?php echo set_select('disposal_type', 'CONTAMINATED'); ?>>Contaminated Medicine</option>
                                                <option value="REGULATORY" <?php echo set_select('disposal_type', 'REGULATORY'); ?>>Regulatory Disposal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Disposal Method *</label>
                                        <div class="col-sm-8">
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
                                        <label class="col-sm-4 control-label">Disposal Company</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="disposal_company" class="form-control" placeholder="Enter disposal company" value="<?php echo set_value('disposal_company'); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Authorized By *</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="authorized_by" class="form-control" placeholder="Enter authorized person" value="<?php echo set_value('authorized_by'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick Search Filter -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h5><i class="fa fa-search"></i> Quick Search Tips</h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Search by:</strong><br>
                                                • Medicine name<br>
                                                • Batch number<br>
                                                • Expiry date<br>
                                                • Brand name
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Examples:</strong><br>
                                                • "paracetamol"<br>
                                                • "batch001"<br>
                                                • "2024-12"<br>
                                                • "crocin"
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                                    <input type="text" id="quick_search_disposal" class="form-control" placeholder="Type here to quickly filter medicines for disposal...">
                                                    <span class="input-group-btn">
                                                        <button type="button" id="clear_search_disposal" class="btn btn-default">
                                                            <i class="fa fa-times"></i> Clear
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Disposal Items -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Disposal Items</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="disposal_items_table">
                                            <thead>
                                                <tr>
                                                    <th>Search & Select Medicine</th>
                                                    <th>Batch</th>
                                                    <th>Expiry Date</th>
                                                    <th>Available Quantity</th>
                                                    <th>Disposal Quantity</th>
                                                    <th>Unit Cost</th>
                                                    <th>Total Cost</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                    <select name="disposal_items[0][batch_id]" class="form-control batch_select" required>
                                                        <option value="">Select Medicine Batch & Center</option>
                                                        <?php foreach($available_batches as $batch): ?>
                                                            <option value="<?php echo $batch->batch_id; ?>"  
                                                                    data-medicine="<?php echo htmlspecialchars($batch->medicine_name); ?>"
                                                                    data-batch-no="<?php echo htmlspecialchars($batch->batch_number); ?>" 
                                                                    data-expiry="<?php echo $batch->expiry_date; ?>"
                                                                    data-available="<?php echo $batch->available_quantity; ?>"
                                                                    data-cost="<?php echo $batch->purchase_price; ?>"
                                                                    data-center-name="<?php echo htmlspecialchars($batch->center_name); ?>"
                                                                    data-center-id="<?php echo $batch->center_id; ?>">
                                                                <?php 
                                                                    // Display: Medicine Name - Batch Number (Center Name) - Qty: X
                                                                    echo htmlspecialchars($batch->medicine_name) . 
                                                                        ' - ' . htmlspecialchars($batch->batch_number) . 
                                                                        ' (' . htmlspecialchars($batch->center_name) . ')' .
                                                                        ' - Qty: ' . $batch->available_quantity; 
                                                                ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    </td>
                                                    <td><span class="batch_number">-</span></td>
                                                    <td><span class="expiry_date">-</span></td>
                                                    <td><span class="available_quantity">-</span></td>
                                                    <td>
                                                        <input type="number" name="disposal_items[0][quantity_disposed]" class="form-control quantity_disposed" min="1" required>
                                                    </td>
                                                    <td><span class="unit_cost">-</span></td>
                                                    <td><span class="total_cost">-</span></td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove_row">
                                                            <i class="fa fa-trash-o"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-success" id="add_disposal_item">
                                        <i class="fa fa-plus"></i> Add Item
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Disposal Summary -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Total Items</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="total_items" class="form-control" id="total_items" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Total Cost</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="total_cost" class="form-control" id="total_cost" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Information -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Disposal Certificate</label>
                                        <div class="col-sm-10">
                                            <input type="file" name="disposal_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png,">
                                            <small class="text-muted">Upload disposal certificate if available</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Remarks</label>
                                        <div class="col-sm-10">
                                            <textarea name="remarks" class="form-control" rows="3" placeholder="Enter disposal remarks"><?php echo set_value('remarks'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to dispose these medicines? This action cannot be undone.')">
                                                <i class="fa fa-trash-o"></i> Process Disposal
                                            </button>
                                            <a href="<?php echo base_url('stocks_new/disposal_reports'); ?>" class="btn btn-default">
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
        
        <!-- Disposal Guidelines -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <i class="fa fa-exclamation-triangle"></i> Disposal Guidelines
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><strong>Regulatory Compliance:</strong> Follow local regulations for medicine disposal</li>
                            <li><strong>Environmental Safety:</strong> Ensure proper disposal methods to protect environment</li>
                            <li><strong>Documentation:</strong> Maintain complete records of all disposals</li>
                            <li><strong>Authorization:</strong> Only authorized personnel can process disposals</li>
                            <li><strong>Audit Trail:</strong> All disposals are logged for complete traceability</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css" rel="stylesheet" />

<script>
$(document).ready(function() {
    var rowCount = 1;
    var availableBatches = <?php echo json_encode($available_batches); ?>;

    // --- Select2 Initialization Function ---
    function initializeSelect2(selector) {
        $(selector).select2({
            placeholder: "Select Medicine Batch & Center", // Optional placeholder
            allowClear: true // Optional: adds a clear button
        });
    }

    function populateBatchSelect(selectElement) {
        var optionsHtml = '<option value="">Select Medicine Batch & Center</option>';
        if (availableBatches && availableBatches.length > 0) {
            $.each(availableBatches, function(index, batch) {
                var displayText = batch.medicine_name + ' - ' + batch.batch_number +
                                  ' (' + batch.center_name + ')' +
                                  ' - Qty: ' + batch.available_quantity;

                optionsHtml += '<option value="' + batch.batch_id + '" ' +
                               'data-medicine="' + escapeHtml(batch.medicine_name) + '" ' +
                               'data-batch-no="' + escapeHtml(batch.batch_number) + '" ' +
                               'data-expiry="' + batch.expiry_date + '" ' +
                               'data-available="' + batch.available_quantity + '" ' +
                               'data-cost="' + batch.purchase_price + '" ' +
                               'data-center-name="' + escapeHtml(batch.center_name) + '" ' +
                               'data-center-id="' + batch.center_id + '">' +
                               escapeHtml(displayText) +
                               '</option>';
            });
        }
        $(selectElement).html(optionsHtml);
        // --- Initialize Select2 AFTER populating ---
        initializeSelect2(selectElement);
    }

    function escapeHtml(text) {
        if (typeof text !== 'string') return text;
        var map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    $('#add_disposal_item').click(function() {
        var newRowHtml = '<tr>' +
            '<td><select name="disposal_items[' + rowCount + '][batch_id]" class="form-control batch_select" required style="width: 100%;"></select></td>' + // Added style for Select2 width
            '<td><span class="batch_number">-</span></td>' +
            '<td><span class="expiry_date">-</span></td>' +
            '<td><span class="available_quantity">-</span></td>' +
            '<td><input type="number" name="disposal_items[' + rowCount + '][quantity_disposed]" class="form-control quantity_disposed" min="1" required></td>' +
            '<td><span class="unit_cost">-</span></td>' +
            '<td><span class="total_cost">-</span></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash-o"></i></button></td>' +
            '</tr>';
        var newRow = $(newRowHtml);
        $('#disposal_items_table tbody').append(newRow);

        // Populate and initialize Select2 for the NEW row's select
        populateBatchSelect(newRow.find('.batch_select'));

        rowCount++;
    });

    $(document).on('click', '.remove_row', function() {
        // --- Destroy Select2 instance before removing row to prevent memory leaks ---
        $(this).closest('tr').find('.batch_select').select2('destroy');
        $(this).closest('tr').remove();
        calculateSummary();
    });

    // Batch selection change (using Select2's event)
    $(document).on('change', '.batch_select', function() {
        // Get selected data using Select2's method
        var selectedData = $(this).select2('data')[0]; // Gets the selected option's data object
        var row = $(this).closest('tr');
        var selectedOption = $(selectedData.element); // Get the original <option> element

        if (selectedOption && selectedOption.val()) {
            var maxQty = selectedOption.data('available');
            row.find('.batch_number').text(selectedOption.data('batch-no'));
            row.find('.expiry_date').text(selectedOption.data('expiry'));
            row.find('.available_quantity').text(maxQty);
            row.find('.unit_cost').text('₹' + selectedOption.data('cost'));
            row.find('.quantity_disposed').attr('max', maxQty);
            row.find('.quantity_disposed').val('');
            row.find('.total_cost').text('-');
        } else {
            row.find('.batch_number, .expiry_date, .available_quantity, .unit_cost, .total_cost').text('-');
            row.find('.quantity_disposed').removeAttr('max').val('');
        }
        calculateSummary();
    });

    $(document).on('input', '.quantity_disposed', function() {
        var row = $(this).closest('tr');
        var quantityInput = $(this);
        var quantity = parseInt(quantityInput.val()) || 0;
        var maxAvailable = parseInt(row.find('.available_quantity').text()) || 0;
        var unitCost = parseFloat(row.find('.unit_cost').text().replace('₹', '')) || 0;

        if (quantity > maxAvailable) {
            alert('Disposal quantity cannot exceed available quantity (' + maxAvailable + ')!');
            quantityInput.val(maxAvailable);
            quantity = maxAvailable;
        }
        if (quantityInput.val() !== '' && quantity < 1) {
             quantityInput.val(1);
             quantity = 1;
        }

        var totalCost = quantity * unitCost;
        row.find('.total_cost').text('₹' + totalCost.toFixed(2));
        calculateSummary();
    });

    function calculateSummary() {
        var totalItems = 0;
        var totalCost = 0;
         $('#disposal_items_table tbody tr').each(function() {
             var row = $(this);
             var batchSelected = row.find('.batch_select').val();
             if (batchSelected) {
                 totalItems++;
                 var cost = parseFloat(row.find('.total_cost').text().replace('₹', '')) || 0;
                 totalCost += cost;
             }
         });
        $('#total_items').val(totalItems);
        $('#total_cost').val('₹' + totalCost.toFixed(2));
    }

    // --- Initial population AND Select2 initialization for the FIRST row ---
    populateBatchSelect($('select[name="disposal_items[0][batch_id]"]'));

    calculateSummary();
});
</script>
