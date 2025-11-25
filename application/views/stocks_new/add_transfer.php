<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-plus"></i> Create Stock Transfer
                    <small>Transfer inventory between locations</small>
                </h1>
            </div>
        </div>
        
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
                    <li><a href="<?php echo base_url('stocks_new/transfers'); ?>">Transfers</a></li>
                    <li class="active">Create Transfer</li>
                </ol>
            </div>
        </div>
        
        <!-- Transfer Form -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-exchange"></i> Transfer Information
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
                        
                        <form action="<?php echo base_url('stocks_new/add_transfer'); ?>" method="post" class="form-horizontal">
                            <input type="hidden" name="action" value="add_transfer">
                            
                            <!-- Transfer Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Transfer Type *</label>
                                        <div class="col-sm-8">
                                            <select name="transfer_type" class="form-control" required onchange="updateCenterOptions()">
                                                <option value="">Select Transfer Type</option>
                                                <option value="CENTRAL_TO_CENTER" <?php echo set_select('transfer_type', 'CENTRAL_TO_CENTER'); ?>>Central to Center</option>
                                                <option value="CENTER_TO_CENTER" <?php echo set_select('transfer_type', 'CENTER_TO_CENTER'); ?>>Center to Center</option>
                                                <!-- <option value="CENTER_TO_CENTRAL" <?php echo set_select('transfer_type', 'CENTER_TO_CENTRAL'); ?>>Center to Central</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" id="from_center_group" style="display: none;">
                                        <label class="col-sm-4 control-label">From Center *</label>
                                        <div class="col-sm-8">
                                            <select name="from_center_id" class="form-control" id="from_center_select" onchange="loadFromDepartments()">
                                                <option value="">Select Source Center</option>
                                                <?php foreach($centers as $center): ?>
                                                    <option value="<?php echo $center->ID; ?>" <?php echo set_select('from_center_id', $center->ID); ?>>
                                                        <?php echo $center->center_name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" id="from_department_group" style="display: none;">
                                        <label class="col-sm-4 control-label">From Department *</label>
                                        <div class="col-sm-8">
                                            <!-- <select name="from_department" class="form-control"  id="from_department_select">
                                                <option value="">Select Department</option>
                                                <?php foreach($departments as $dept): ?>
                                                    <option value="<?php echo $dept['department']; ?>" <?php echo set_select('from_department', $dept['department']); ?>>
                                                        <?php echo $dept['department']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select> -->
                                                <select name="from_department" id="from_department" class="form-control" >
                                                    <option value="">Select Department</option>
                                                    <option value="CASH MEDICINE NOIDA">CASH MEDICINE NOIDA</option>
                                                    <option value="CASH MEDICINE GGN">CASH MEDICINE GGN</option>
                                                    <option value="CASH MEDICINE GP">CASH MEDICINE BASANT LOK</option>
                                                    <option value="CASH MEDICINE SRINAGAR">CASH MEDICINE SRINAGAR</option>
                                                    <option value="CASH MEDICINE GHAZIABAD">CASH MEDICINE GHAZIABAD</option>
                                                    <option value="CASH MEDICINE GHAZIABAD">CASH MEDICINE  ROHINI</option>
                                                    <option value="CASH MEDICINE GHAZIABAD">HORMONAL ROHINI</option>
                                                    <option value="Hormonal Ghaziabad">Hormonal Ghaziabad</option>
                                                    <option value="HORMONAL SRINAGAR">HORMONAL SRINAGAR</option>
                                                    <option value="Hormonal Delhi">Hormonal Basant Lok</option>
                                                    <option value="Hormonal Gurgaon">Hormonal Gurgaon</option>
                                                    <option value="Hormonal Noida">Hormonal Noida</option>
                                                    <option value="Embryologist Noida">Embryologist Noida</option>
                                                    <option value="OT Noida">OT Noida</option>
                                                    <option value="OT Basant Lok">OT Basant Lok</option>
                                                    <option value="Embryology Basant Lok">Embryology Basant Lok</option>
                                                    <option value="Embryology Srinagar">Embryology Srinagar</option>
                                                    <option value="OT Srinagar">OT Srinagar</option>
                                                </select>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="form-group" id="from_employee_group" style="display: none;">
                                        <label class="col-sm-4 control-label">From Employee *</label>
                                        <div class="col-sm-8">
                                            <select name="from_employee_number" class="form-control" >
                                                <option value="">Select Employee</option>
                                                <?php foreach($all_employees as $employee): ?>
                                                    <option value="<?php echo $employee['employee_number']; ?>" <?php echo set_select('from_employee_number', $employee['employee_number']); ?>>
                                                        <?php echo $employee['name']; ?> (<?php echo $employee['employee_number']; ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div> -->
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">To Center *</label>
                                        <div class="col-sm-8">
                                            <select name="to_center_id" class="form-control" required onchange="loadToDepartments()">
                                                <option value="">Select Destination Center</option>
                                                <?php foreach($centers as $center): ?>
                                                    <option value="<?php echo $center->ID; ?>" <?php echo set_select('to_center_id', $center->ID); ?>>
                                                        <?php echo $center->center_name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">To Department *</label>
                                        <div class="col-sm-8">
                                            <!-- <select name="to_department" class="form-control" >
                                                <option value="">Select Department</option>
                                                <?php foreach($departments as $dept): ?>
                                                    <option value="<?php echo $dept['department']; ?>"><?php echo $dept['department']; ?></option>
                                                <?php endforeach; ?>
                                            </select> -->
                                            <select name="to_department" id="to_department" class="form-control" required>
                                                <option value="">Select Department</option>
                                                <option value="CASH MEDICINE NOIDA">CASH MEDICINE NOIDA</option>
                                                <option value="CASH MEDICINE GGN">CASH MEDICINE GGN</option>
                                                <option value="CASH MEDICINE GP">CASH MEDICINE BASANT LOK</option>
                                                <option value="CASH MEDICINE SRINAGAR">CASH MEDICINE SRINAGAR</option>
                                                <option value="CASH MEDICINE GHAZIABAD">CASH MEDICINE GHAZIABAD</option>
                                                <option value="CASH MEDICINE GHAZIABAD">CASH MEDICINE  ROHINI</option>
                                                <option value="CASH MEDICINE GHAZIABAD">HORMONAL ROHINI</option>
                                                <option value="Hormonal Ghaziabad">Hormonal Ghaziabad</option>
                                                <option value="HORMONAL SRINAGAR">HORMONAL SRINAGAR</option>
                                                <option value="Hormonal Delhi">Hormonal Basant Lok</option>
                                                <option value="Hormonal Gurgaon">Hormonal Gurgaon</option>
                                                <option value="Hormonal Noida">Hormonal Noida</option>
                                                <option value="Embryologist Noida">Embryologist Noida</option>
                                                <option value="OT Noida">OT Noida</option>
                                                <option value="OT Basant Lok">OT Basant Lok</option>
                                                <option value="Embryology Basant Lok">Embryology Basant Lok</option>
                                                <option value="Embryology Srinagar">Embryology Srinagar</option>
                                                <option value="OT Srinagar">OT Srinagar</option>
                                            </select>
                                        </div>
                                    </div>
<!--                                     
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">To Employee *</label>
                                        <div class="col-sm-8">
                                            <select name="to_employee_number" class="form-control" >
                                                <option value="">Select Employee</option>
                                                <?php foreach($all_employees as $employee): ?>
                                                    <option value="<?php echo $employee['employee_number']; ?>"><?php echo $employee['name']; ?> (<?php echo $employee['employee_number']; ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div> -->
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Transfer Date *</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="transfer_date" class="form-control" value="<?php echo set_value('transfer_date', date('Y-m-d')); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Expected Delivery</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="expected_delivery_date" class="form-control" value="<?php echo set_value('expected_delivery_date'); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea name="remarks" class="form-control" rows="3" placeholder="Enter any remarks"><?php echo set_value('remarks'); ?></textarea>
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
                                                <i class="fa fa-save"></i> Create Transfer
                                            </button>
                                            <a href="<?php echo base_url('stocks_new/transfers'); ?>" class="btn btn-default">
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
        
        <!-- Transfer Types Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Transfer Types Explained
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h4><i class="fa fa-building-o"></i> Central to Center</h4>
                                <p>Transfer stock from central warehouse to a specific center/pharmacy.</p>
                                <ul>
                                    <li>Source: Central Warehouse</li>
                                    <li>Destination: Selected Center</li>
                                    <li>Use: Restocking centers</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h4><i class="fa fa-exchange"></i> Center to Center</h4>
                                <p>Transfer stock between two different centers.</p>
                                <ul>
                                    <li>Source: Selected Center</li>
                                    <li>Destination: Another Center</li>
                                    <li>Use: Balancing inventory</li>
                                </ul>
                            </div>
                            <!-- <div class="col-md-4">
                                <h4><i class="fa fa-arrow-up"></i> Center to Central</h4>
                                <p>Return stock from center back to central warehouse.</p>
                                <ul>
                                    <li>Source: Selected Center</li>
                                    <li>Destination: Central Warehouse</li>
                                    <li>Use: Returns, consolidation</li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Available Stocks Section -->
        <div class="row" id="available_stocks_section" style="display: none;">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-cubes"></i> Available Stocks for Transfer
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-primary" onclick="refreshAvailableStocks()">
                                <i class="fa fa-refresh"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="stocks_loading" class="text-center" style="display: none;">
                            <i class="fa fa-spinner fa-spin"></i> Loading available stocks...
                        </div>
                        <div id="stocks_content">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> 
                                Please fill in the transfer details above to see available stocks.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FEFO Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-exclamation-triangle"></i> Important Notes
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><strong>FEFO System:</strong> After creating the transfer, you'll add items where the system will automatically suggest batches with earliest expiry dates first.</li>
                            <li><strong>Stock Availability:</strong> Only available stock will be shown for selection.</li>
                            <li><strong>Approval Required:</strong> Transfers need approval before stock is actually moved.</li>
                            <li><strong>Audit Trail:</strong> All transfers are logged for complete traceability.</li>
                            <li><strong>Batch Tracking:</strong> Each transfer maintains batch-level tracking for expiry management.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateCenterOptions() {
    var transferType = $('select[name="transfer_type"]').val();
    var fromCenterGroup = $('#from_center_group');
    var fromDepartmentGroup = $('#from_department_group');
    var fromEmployeeGroup = $('#from_employee_group');
    var fromCenterSelect = $('#from_center_select');
    var fromDepartmentSelect = $('#from_department_select');
    var fromEmployeeSelect = $('#from_employee_select');
    
    if (transferType === 'CENTRAL_TO_CENTER') {
        fromCenterGroup.hide();
        fromDepartmentGroup.hide();
        fromEmployeeGroup.hide();
        fromCenterSelect.prop('required', false);
        fromDepartmentSelect.prop('required', false);
        fromEmployeeSelect.prop('required', false);
    } else {
        fromCenterGroup.show();
        fromDepartmentGroup.show();
        fromEmployeeGroup.show();
        fromCenterSelect.prop('required', true);
        fromDepartmentSelect.prop('required', true);
        fromEmployeeSelect.prop('required', true);
    }
}

function loadFromDepartments() {
    var centerId = $('#from_center_select').val();
    var departmentSelect = $('#from_department_select');
    var employeeSelect = $('#from_employee_select');
    
    // Clear dependent dropdowns
    // departmentSelect.html('<option value="">Select Department</option>');
    // employeeSelect.html('<option value="">Select Employee</option>');
    
    if (centerId) {
        // Load departments for this center
        loadToDepartments(departmentSelect, centerId);
    }
}


function loadToDepartments() {
    // Departments are already loaded via PHP, no need to reload
}

function loadFromEmployees() {
    // Employees are already loaded via PHP, no need to reload
}

$(document).ready(function() {
    // Initialize center options based on current selection
    updateCenterOptions();
    
    // Set expected delivery date to tomorrow by default
    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    $('input[name="expected_delivery_date"]').val(tomorrow.toISOString().split('T')[0]);
    
    // Update available stocks when form fields change
    $('select[name="transfer_type"], #from_center_select, #from_department_select, #from_employee_select').on('change', function() {
        console.log('Form fields changed, updating available stocks...');
        setTimeout(function() {
            loadAvailableStocks();
        }, 500); // Small delay to allow dependent dropdowns to load
    });
});

// Function to load available stocks
function loadAvailableStocks() {
    var transferType = $('select[name="transfer_type"]').val();
    var fromCenterId = $('#from_center_select').val();
    var fromDepartment = $('#from_department_select').val();
    var fromEmployee = $('#from_employee_select').val();
    console.log(fromDepartment);
    // Show stocks section if transfer type is selected
    if (transferType) {
        $('#available_stocks_section').show();
        
        // Show loading
        $('#stocks_loading').show();
        $('#stocks_content').html('');
        
        // Make AJAX call to get available stocks
        $.ajax({
            url: '<?php echo base_url("stocks_new/get_available_stocks_for_transfer"); ?>',
            type: 'GET',
            data: {
                transfer_type: transferType,
                from_center_id: fromCenterId,
                from_department: fromDepartment,
                from_employee_number: fromEmployee
            },
            dataType: 'json',
            success: function(response) {
                $('#stocks_loading').hide();
                displayAvailableStocks(response);
            },
            error: function() {
                $('#stocks_loading').hide();
                $('#stocks_content').html('<div class="alert alert-danger">Error loading available stocks. Please try again.</div>');
            }
        });
    } else {
        $('#available_stocks_section').hide();
    }
}

// Function to display available stocks
function displayAvailableStocks(stocks) {
    if (stocks.length === 0) {
        $('#stocks_content').html('<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> No available stocks found for the selected criteria.</div>');
        return;
    }
    
    var html = '<div class="table-responsive">';
    html += '<table class="table table-striped table-hover">';
    html += '<thead>';
    html += '<tr>';
    html += '<th>Medicine</th>';
    html += '<th>Batch Number</th>';
    html += '<th>Expiry Date</th>';
    html += '<th>Available Qty</th>';
    html += '<th>Expiry Status</th>';
    html += '<th>Brand</th>';
    html += '<th>Vendor</th>';
    html += '<th>Location</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody>';
    
    $.each(stocks, function(index, stock) {
        var expiryClass = '';
        var expiryIcon = '';
        
        if (stock.expiry_status === 'EXPIRED') {
            expiryClass = 'danger';
            expiryIcon = 'fa-times-circle';
        } else if (stock.expiry_status === 'EXPIRING_SOON') {
            expiryClass = 'warning';
            expiryIcon = 'fa-exclamation-triangle';
        } else {
            expiryClass = 'success';
            expiryIcon = 'fa-check-circle';
        }
        
        html += '<tr>';
        html += '<td><strong>' + stock.medicine_name + '</strong><br><small class="text-muted">' + stock.medicine_code + '</small></td>';
        html += '<td>' + stock.batch_number + '</td>';
        html += '<td>' + stock.expiry_date + '</td>';
        html += '<td><span class="badge badge-primary">' + stock.quantity_remaining + '</span></td>';
        html += '<td><span class="label label-' + expiryClass + '"><i class="fa ' + expiryIcon + '"></i> ' + stock.expiry_status + '</span></td>';
        html += '<td>' + (stock.brand_name || 'N/A') + '</td>';
        html += '<td>' + (stock.vendor_name || 'N/A') + '</td>';
        html += '<td>' + (stock.center_name || 'Central') + '<br><small class="text-muted">' + (stock.department_name || 'N/A') + '</small></td>';
        html += '</tr>';
    });
    
    html += '</tbody>';
    html += '</table>';
    html += '</div>';
    
    $('#stocks_content').html(html);
}

// Function to refresh available stocks
function refreshAvailableStocks() {
    loadAvailableStocks();
}

// Function to select stock for transfer
function selectStockForTransfer(batchId, medicineName, batchNumber, maxQuantity) {
    // Open modal to specify transfer quantity
    var quantity = prompt('Enter quantity to transfer for ' + medicineName + ' (Batch: ' + batchNumber + ')\n\nAvailable Quantity: ' + maxQuantity + '\n\nEnter transfer quantity:', '1');
    
    if (quantity !== null && quantity !== '') {
        var transferQty = parseInt(quantity);
        
        if (transferQty > 0 && transferQty <= maxQuantity) {
            // Add to transfer items
            addToTransferItems(batchId, medicineName, batchNumber, transferQty, maxQuantity);
        } else {
            alert('Please enter a valid quantity between 1 and ' + maxQuantity);
        }
    }
}

function addToTransferItems(batchId, medicineName, batchNumber, transferQty, maxQuantity) {
    // Check if item already exists
    var existingItem = $('#transfer_item_' + batchId);
    if (existingItem.length > 0) {
        alert('This item is already added to transfer list.');
        return;
    }
    
    // Create transfer item row
    var itemHtml = `
        <div class="transfer-item-row" id="transfer_item_${batchId}">
            <div class="row" style="margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <div class="col-md-3">
                    <strong>${medicineName}</strong><br>
                    <small class="text-muted">Batch: ${batchNumber}</small>
                </div>
                <div class="col-md-2">
                    <span class="badge badge-info">${maxQuantity}</span><br>
                    <small>Available</small>
                </div>
                <div class="col-md-2">
                    <input type="number" name="transfer_items[${batchId}][quantity]" 
                           value="${transferQty}" min="1" max="${maxQuantity}" 
                           class="form-control transfer-quantity" required>
                    <input type="hidden" name="transfer_items[${batchId}][batch_id]" value="${batchId}">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeTransferItem(${batchId})">
                        <i class="fa fa-trash-o"></i> Remove
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Add to transfer items container
    if ($('#transfer_items_container').length === 0) {
        $('#available_stocks_section').after(`
            <div class="row" id="transfer_items_container">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <i class="fa fa-shopping-cart"></i> Selected Items for Transfer
                        </div>
                        <div class="panel-body" id="transfer_items_list">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> 
                                Selected items will appear here. You can adjust quantities before submitting.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }
    
    // Remove info message if it exists
    $('#transfer_items_list .alert-info').remove();
    
    // Add the item
    $('#transfer_items_list').append(itemHtml);
}

function removeTransferItem(batchId) {
    $('#transfer_item_' + batchId).remove();
    
    // Show info message if no items left
    if ($('#transfer_items_list .transfer-item-row').length === 0) {
        $('#transfer_items_list').html('<div class="alert alert-info"><i class="fa fa-info-circle"></i> Selected items will appear here. You can adjust quantities before submitting.</div>');
    }
}
</script>

