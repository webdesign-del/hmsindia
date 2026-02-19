<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-building-o"></i> Central Stocks
            <small>Central warehouse inventory management</small>
        </h1>
    </div>
</div>

<!-- Breadcrumb -->
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
            <li class="active">Central Stocks</li>
        </ol>
    </div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-filter"></i> Filters
            </div>
            <div class="panel-body">
                <form method="get" action="<?php echo base_url('stocks_new/central_stocks'); ?>" class="form-inline">
                    <div class="form-group">
                        <label>Medicine:</label>
                        <select name="medicine_id" class="form-control" id="medicineFilter">
                            <option value="">All Medicines</option>
                            <?php foreach($medicines as $medicine): ?>
                                <option value="<?php echo $medicine->id; ?>" <?php echo ($selected_medicine_id == $medicine->id) ? 'selected' : ''; ?>>
                                    <?php echo $medicine->medicine_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group" id="batchFilter">
                        <label>Batch Number:</label>
                    <input type="text" name="batch_number" id="batchFilterInput" class="form-control" placeholder="Batch number" value="<?php echo $selected_batch_number; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Status:</label>
                        <select name="status" class="form-control" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="ACTIVE" <?php echo ($selected_status == 'ACTIVE') ? 'selected' : ''; ?>>Active</option>
                            <option value="INACTIVE" <?php echo ($selected_status == 'INACTIVE') ? 'selected' : ''; ?>>Inactive</option>
                            <option value="QUARANTINE" <?php echo ($selected_status == 'QUARANTINE') ? 'selected' : ''; ?>>Quarantine</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Filter
                    </button>
                    <a href="<?php echo base_url('stocks_new/central_stocks'); ?>" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Clear
                    </a>
                    <a onclick="exportCentralStockReport()" class="btn btn-success">
                        <i class="fa fa-download"></i> Export
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Central Stocks Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Central Stocks List
                <span class="badge pull-right"><?php echo count($central_stocks); ?> items</span>
            </div>
            <div class="panel-body">
                <?php if(!empty($central_stocks)): ?>
                    <div class="table-responsive">
                        <table id="centralStocksTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Batch Number</th>
                                    <th>Brand</th>
                                    <th>Vendor</th>
                                    <th>Expiry Date</th>
                                    <th>Expiry Days</th>
                                    <th>Pack Size</th>
                                    <th>Quantity</th>
                                    <th>Min Quantity</th>
                                    <th>Max Quantity</th>
                                    <th>Reorder</th>
                                    <th>Vendor Price With GST</th>
                                    <th>Mrp</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($central_stocks as $stock): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $stock->medicine_name; ?></strong><br>
                                            <small class="text-muted"><?php echo $stock->medicine_code; ?></small>
                                        </td>
                                        <td><?php echo $stock->batch_number; ?></td>
                                        <td><?php echo $stock->brand_name; ?></td>
                                        <td><?php echo $stock->vendor_name; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($stock->expiry_date)); ?></td>
                                        <td>
                                            <?php if($stock->expiry_days < 0): ?>
                                                <span class="label label-danger">Expired (<?php echo abs($stock->expiry_days); ?> days)</span>
                                            <?php elseif($stock->expiry_days <= 30): ?>
                                                <span class="label label-warning">Expiring Soon (<?php echo $stock->expiry_days; ?> days)</span>
                                            <?php else: ?>
                                                <span class="label label-success"><?php echo $stock->expiry_days; ?> days</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo $stock->pack_size; ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo $stock->quantity; ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo $stock->min_stock_level; ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo $stock->max_stock_level; ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo $stock->reorder_level; ?></strong>
                                        </td>
                                        <td>₹<?php echo number_format($stock->purchase_price, 2); ?></td>
                                        <td>₹<?php echo number_format($stock->selling_price, 2); ?></td>
                                        <td>
                                            <?php if($stock->status == 'ACTIVE'): ?>
                                                <span class="label label-success">Active</span>
                                            <?php elseif($stock->status == 'INACTIVE'): ?>
                                                <span class="label label-default">Inactive</span>
                                            <?php elseif($stock->status == 'QUARANTINE'): ?>
                                                <span class="label label-warning">Quarantine</span>
                                            <?php else: ?>
                                                <span class="label label-info"><?php echo $stock->status; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-success" onclick="updateStockStatus(<?php echo $stock->id; ?>, 'ACTIVE')">
                                                    <i class="fa fa-check"></i> Activate
                                                </button>
                                                <button type="button" class="btn btn-xs btn-warning" onclick="updateStockStatus(<?php echo $stock->id; ?>, 'INACTIVE')">
                                                    <i class="fa fa-pause"></i> Deactivate
                                                </button>
                                                <!-- <button type="button" class="btn btn-xs btn-danger" onclick="updateStockStatus(<?php echo $stock->id; ?>, 'QUARANTINE')">
                                                    <i class="fa fa-ban"></i> Quarantine
                                                </button> -->
                                                <button type="button" class="btn btn-xs btn-primary" onclick="openEditModal(<?php echo $stock->id; ?>)">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> No central stocks found matching your criteria.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<!-- Simple Modal Overlay -->
<div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:10000;">
    <div id="simpleModal" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border-radius:5px; max-width:600px; width:90%; max-height:80%; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h4 id="simpleModalTitle">Stock Level Configuration</h4>
            <span style="cursor:pointer; font-size:24px;" onclick="closeModal()">&times;</span>
        </div>
        <style>
            /* Ensure Select2 dropdown appears above modal */
            .select2-container--open .select2-dropdown {
                z-index: 10002 !important;
            }
            .select2-container {
                z-index: 10001 !important;
            }
            /* Fix modal overlay covering dropdowns */
            #modalOverlay {
                z-index: 9999;
            }
            #simpleModal {
                z-index: 10000;
            }
        </style>
        <form id="simpleStockLevelForm">
            <input type="hidden" id="simple_config_id" name="config_id" value="">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="simple_min_stock_level">Min Stock Level</label>
                        <input type="number" class="form-control" id="simple_min_stock_level" name="min_stock_level" min="0" value="0">
                        <small class="help-block">Alert when stock falls below this level</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="simple_max_stock_level">Max Stock Level</label>
                        <input type="number" class="form-control" id="simple_max_stock_level" name="max_stock_level" min="0" value="0">
                        <small class="help-block">Prevent ordering above this level</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="simple_reorder_level">Reorder Level</label>
                        <input type="number" class="form-control" id="simple_reorder_level" name="reorder_level" min="0" value="0">
                        <small class="help-block">Suggest reordering at this level</small>
                    </div>
                </div>
            </div>
            <div style="text-align:right; margin-top:20px;">
                <button type="button" class="btn btn-default" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="simpleSaveBtn">Save Configuration</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    <?php if(!empty($central_stocks)): ?>
    $('#centralStocksTable').DataTable({
        "pageLength": 25,
        "order": [[ 6, "asc" ]], // Sort by Expiry Date column (0-based index 4)
        "columnDefs": [
            { "orderable": false, "targets": 10 } // Actions column (0-based index 10)
        ],
        "language": {
            "emptyTable": "No central stocks found",
            "zeroRecords": "No matching central stocks found"
        },
        "responsive": true,
        "autoWidth": false
    });
    <?php endif; ?>
});


function updateStockStatus(stockId, status) {
    if(confirm('Are you sure you want to update the stock status?')) {
        $.ajax({
            url: '<?php echo base_url("stocks_new/update_central_stock_status"); ?>',
            type: 'POST',
            data: {
                stock_id: stockId,
                status: status
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert('Stock status updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating stock status.');
            }
        });
    }
}

function exportCentralStockReport() {
    var filters = {
        medicine_id: $('#medicineFilter').val(),
        status: $('#statusFilter').val(),
        batch_id: $('#batchFilterInput').val(),
    };
    var queryString = $.param(filters);
    window.open('<?php echo base_url("stocks_new/central_stocks_export"); ?>?' + queryString, '_blank');
}


// Simple modal functions
function openModal() {
    console.log('Opening modal...');
    $('#modalOverlay').fadeIn();
    $('#simple_config_id').val('');
    $('#simpleModalTitle').text('Add Stock Level Configuration');
    $('#simpleStockLevelForm')[0].reset();
    $('#stock_id').val('');

    // Debug: Check if dropdowns have options
    console.log('Medicine options:', $('#stock_id option').length);

    // Force Select2 to refresh safely
    setTimeout(function() {
        try {
            if (typeof $.fn.select2 !== 'undefined') {
                // Helper function to safely initialize Select2
                function safeSelect2Init(selector, config) {
                    var $element = $(selector);
                    if ($element.length === 0) return;

                    try {
                        // Check if already has Select2 by looking for container
                        if ($element.next('.select2-container').length > 0) {
                            $element.select2('destroy');
                        }
                        $element.select2(config);
                    } catch (e) {
                        console.warn('Select2 init failed for', selector, ':', e.message);
                    }
                }

                console.log('Select2 initialized successfully with search');
            } else {
                console.warn('Select2 not available, using regular select');
            }
        } catch (e) {
            console.error('Select2 setup failed:', e);
        }
    }, 100);
}


function closeModal() {
    $('#modalOverlay').fadeOut();
}

function openEditModal(configId) { // Sirf configId lene se bhi kaam chalega
    openModal();
    $('#simpleModalTitle').text('Edit Stock Level Configuration');
    $('#simple_config_id').val(configId); // Hidden input mein ID set karein

    $.ajax({
        url: '<?php echo base_url("stocks_new/get_medicine_central_stock_levels"); ?>',
        type: 'POST',
        data: {
            medicine_id: configId // Controller 'medicine_id' maang raha hai
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#simple_min_stock_level').val(response.data.min_stock_level);
                $('#simple_max_stock_level').val(response.data.max_stock_level);
                $('#simple_reorder_level').val(response.data.reorder_level);
            }
        }
    });
}

// Form submission
$('#stockLevelForm').on('submit', function(e) {
    e.preventDefault();

    // Show loading state
    var $saveBtn = $('#saveBtn');
    var originalText = $saveBtn.text();
    $saveBtn.prop('disabled', true).text('Saving...');

    var formData = new FormData(this);

    $.ajax({
        url: '<?php echo base_url("stocks_new/edit_medicine_central_stock_levels"); ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            // Restore button state
            $saveBtn.prop('disabled', false).text(originalText);

            if (response.success) {
                alert('Stock level configuration saved successfully!');
                if (window.stockLevelModal) {
                    window.stockLevelModal.close();
                }
                // Reload page after modal closes
                setTimeout(function() {
                    location.reload();
                }, 500);
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            // Restore button state
            $saveBtn.prop('disabled', false).text(originalText);
            alert('Error saving configuration: ' + error);
        }
    });
});

// Simple form submission
$('#simpleStockLevelForm').on('submit', function(e) {
    e.preventDefault();
    console.log('Form submitted');

    // Show loading state
    var $saveBtn = $('#simpleSaveBtn');
    var originalText = $saveBtn.text();
    $saveBtn.prop('disabled', true).text('Saving...');

    var formData = new FormData(this);
    console.log('Form data created, sending AJAX request...');

    $.ajax({
        url: '<?php echo base_url("stocks_new/edit_medicine_central_stock_levels"); ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            console.log('AJAX success:', response);
            // Restore button state
            $saveBtn.prop('disabled', false).text(originalText);

            if (response.success) {
                alert('Stock level configuration saved successfully!');
                closeModal();
                // Reload page after modal closes
                setTimeout(function() {
                    location.reload();
                }, 300);
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', xhr, status, error);
            // Restore button state
            $saveBtn.prop('disabled', false).text(originalText);
            alert('Error saving configuration: ' + error);
        }
    });
});

</script>
