<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-exchange"></i> Department Transfer
            <small>Transfer stocks between departments</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Department Transfer Form
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
                
                <form action="<?php echo base_url('stocks_new/department_transfer'); ?>" method="post" class="form-horizontal" id="departmentTransferForm">
                    <input type="hidden" name="action" value="department_transfer">
                    
                    <!-- Source Location -->
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-arrow-left text-primary"></i> Source Location</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Center *</label>
                                <div class="col-sm-8">
                                    <select name="from_center_id" class="form-control" required onchange="loadFromDepartments()">
                                        <option value="">Select Source Center</option>
                                        <?php foreach($centers as $center): ?>
                                            <option value="<?php echo $center->ID; ?>" <?php echo set_select('from_center_id', $center->ID); ?>>
                                                <?php echo $center->center_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Department *</label>
                                <div class="col-sm-8">
                                    <select name="from_department" class="form-control" required onchange="loadFromEmployees()">
                                        <option value="">Select Department</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Employee *</label>
                                <div class="col-sm-8">
                                    <select name="from_employee_number" class="form-control" required onchange="loadSourceStocks()">
                                        <option value="">Select Employee</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Destination Location -->
                        <div class="col-md-6">
                            <h4><i class="fa fa-arrow-right text-success"></i> Destination Location</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Center *</label>
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
                                <label class="col-sm-4 control-label">Department *</label>
                                <div class="col-sm-8">
                                    <select name="to_department" class="form-control" required onchange="loadToEmployees()">
                                        <option value="">Select Department</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Employee *</label>
                                <div class="col-sm-8">
                                    <select name="to_employee_number" class="form-control" required>
                                        <option value="">Select Employee</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Available Stock Items -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-archive text-info"></i> Available Stock Items</h4>
                            <div id="stockItemsContainer">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> Please complete source location details to view available stock items.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transfer Summary -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-list-alt text-warning"></i> Transfer Summary</h4>
                            <div id="transferSummary">
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i> No items selected for transfer.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Remarks -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Remarks</label>
                                <div class="col-sm-10">
                                    <textarea name="remarks" class="form-control" rows="3" placeholder="Enter transfer remarks..."><?php echo set_value('remarks'); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg" disabled>
                                        <i class="fa fa-exchange"></i> Process Department Transfer
                                    </button>
                                    <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-default btn-lg">
                                        <i class="fa fa-arrow-left"></i> Back to Stock Levels
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

<script>
// Department options
var departments = [
    'Embryologist Noida',
    'billing',
    'Hormonal',
    'OT Noida',
    'Embryologist Basant Lok',
    'OT Basant Lok',
    'Nonsaleable',
    'OT Srinagar',
    'Embryology Srinagar',
    'warehouse'
];

var selectedItems = [];

function loadFromDepartments() {
    var centerId = $('select[name="from_center_id"]').val();
    var departmentSelect = $('select[name="from_department"]');
    var employeeSelect = $('select[name="from_employee_number"]');
    
    // Clear dependent dropdowns
    departmentSelect.html('<option value="">Select Department</option>');
    employeeSelect.html('<option value="">Select Employee</option>');
    
    if (centerId) {
        // Load departments for this center
        loadDepartments(departmentSelect, centerId);
    }
}

function loadToDepartments() {
    var centerId = $('select[name="to_center_id"]').val();
    var departmentSelect = $('select[name="to_department"]');
    var employeeSelect = $('select[name="to_employee_number"]');
    
    // Clear dependent dropdowns
    departmentSelect.html('<option value="">Select Department</option>');
    employeeSelect.html('<option value="">Select Employee</option>');
    
    if (centerId) {
        // Load departments for this center
        loadDepartments(departmentSelect, centerId);
    }
}

function loadDepartments(selectElement, centerId) {
    // For now, load all departments. In a real system, you might filter by center
    $.each(departments, function(index, dept) {
        selectElement.append('<option value="' + dept + '">' + dept + '</option>');
    });
}

function loadFromEmployees() {
    var centerId = $('select[name="from_center_id"]').val();
    var department = $('select[name="from_department"]').val();
    var employeeSelect = $('select[name="from_employee_number"]');
    
    // Clear employee dropdown
    employeeSelect.html('<option value="">Select Employee</option>');
    
    if (centerId && department) {
        loadEmployees(employeeSelect, centerId, department);
    }
}

function loadToEmployees() {
    var centerId = $('select[name="to_center_id"]').val();
    var department = $('select[name="to_department"]').val();
    var employeeSelect = $('select[name="to_employee_number"]');
    
    // Clear employee dropdown
    employeeSelect.html('<option value="">Select Employee</option>');
    
    if (centerId && department) {
        loadEmployees(employeeSelect, centerId, department);
    }
}

function loadEmployees(selectElement, centerId, department) {
    // AJAX call to get employees for this center and department
    $.ajax({
        url: '<?php echo base_url("stocks_new/get_employees_by_location"); ?>',
        type: 'GET',
        data: {
            center_id: centerId,
            department: department
        },
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {
                $.each(response, function(index, employee) {
                    selectElement.append('<option value="' + employee.employee_number + '">' + employee.name + ' (' + employee.employee_number + ')</option>');
                });
            } else {
                selectElement.append('<option value="">No employees found</option>');
            }
        },
        error: function() {
            selectElement.append('<option value="">Error loading employees</option>');
        }
    });
}

function loadSourceStocks() {
    var centerId = $('select[name="from_center_id"]').val();
    var department = $('select[name="from_department"]').val();
    var employeeNumber = $('select[name="from_employee_number"]').val();
    
    if(!centerId || !department || !employeeNumber) {
        $('#stockItemsContainer').html('<div class="alert alert-info"><i class="fa fa-info-circle"></i> Please complete source location details.</div>');
        return;
    }
    
    $('#stockItemsContainer').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading stock items...</div>');
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/get_available_stocks"); ?>',
        type: 'GET',
        data: {
            center_id: centerId,
            department: department,
            employee_number: employeeNumber
        },
        dataType: 'json',
        success: function(response) {
            if(response && response.length > 0) {
                var html = '<div class="table-responsive"><table class="table table-bordered table-striped">';
                html += '<thead><tr><th>Select</th><th>Medicine</th><th>Batch</th><th>Expiry</th><th>Available</th><th>Transfer Qty</th><th>Price</th></tr></thead>';
                html += '<tbody>';
                
                $.each(response, function(index, item) {
                    html += '<tr>';
                    html += '<td><input type="checkbox" class="item-checkbox" data-batch-id="' + item.id + '" data-medicine="' + item.medicine_name + '" data-batch="' + item.batch_number + '" data-expiry="' + item.expiry_date + '" data-available="' + item.available_quantity + '" data-price="' + item.purchase_price + '"></td>';
                    html += '<td><strong>' + item.medicine_name + '</strong><br><small class="text-muted">' + item.medicine_code + '</small></td>';
                    html += '<td>' + item.batch_number + '</td>';
                    html += '<td>' + item.expiry_date + '</td>';
                    html += '<td><span class="badge badge-info">' + item.available_quantity + '</span></td>';
                    html += '<td><input type="number" class="form-control transfer-qty" min="1" max="' + item.available_quantity + '" disabled></td>';
                    html += '<td>₹' + parseFloat(item.purchase_price).toFixed(2) + '</td>';
                    html += '</tr>';
                });
                
                html += '</tbody></table></div>';
                $('#stockItemsContainer').html(html);
                
                // Bind checkbox events
                $('.item-checkbox').on('change', function() {
                    var row = $(this).closest('tr');
                    var qtyInput = row.find('.transfer-qty');
                    
                    if($(this).is(':checked')) {
                        qtyInput.prop('disabled', false).val(1);
                        addToTransferSummary($(this));
                    } else {
                        qtyInput.prop('disabled', true).val('');
                        removeFromTransferSummary($(this).data('batch-id'));
                    }
                });
                
                // Bind quantity change events
                $('.transfer-qty').on('change', function() {
                    var checkbox = $(this).closest('tr').find('.item-checkbox');
                    if(checkbox.is(':checked')) {
                        updateTransferSummary(checkbox);
                    }
                });
                
            } else {
                $('#stockItemsContainer').html('<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> No stock items available at the selected location.</div>');
            }
        },
        error: function() {
            $('#stockItemsContainer').html('<div class="alert alert-danger"><i class="fa fa-times"></i> Error loading stock items.</div>');
        }
    });
}

function addToTransferSummary(checkbox) {
    var batchId = checkbox.data('batch-id');
    var medicine = checkbox.data('medicine');
    var batch = checkbox.data('batch');
    var expiry = checkbox.data('expiry');
    var available = checkbox.data('available');
    var price = checkbox.data('price');
    var qty = checkbox.closest('tr').find('.transfer-qty').val() || 1;
    
    selectedItems[batchId] = {
        batch_id: batchId,
        medicine: medicine,
        batch: batch,
        expiry: expiry,
        available: available,
        price: price,
        quantity: parseInt(qty)
    };
    
    updateTransferSummaryDisplay();
}

function removeFromTransferSummary(batchId) {
    delete selectedItems[batchId];
    updateTransferSummaryDisplay();
}

function updateTransferSummary(checkbox) {
    var batchId = checkbox.data('batch-id');
    var qty = checkbox.closest('tr').find('.transfer-qty').val();
    
    if(selectedItems[batchId]) {
        selectedItems[batchId].quantity = parseInt(qty);
        updateTransferSummaryDisplay();
    }
}

function updateTransferSummaryDisplay() {
    var totalItems = Object.keys(selectedItems).length;
    var totalValue = 0;
    
    if(totalItems === 0) {
        $('#transferSummary').html('<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> No items selected for transfer.</div>');
        $('#submitBtn').prop('disabled', true);
        return;
    }
    
    var html = '<div class="table-responsive"><table class="table table-bordered table-striped">';
    html += '<thead><tr><th>Medicine</th><th>Batch</th><th>Expiry</th><th>Quantity</th><th>Unit Price</th><th>Total</th></tr></thead>';
    html += '<tbody>';
    
    $.each(selectedItems, function(batchId, item) {
        var itemTotal = item.quantity * item.price;
        totalValue += itemTotal;
        
        html += '<tr>';
        html += '<td>' + item.medicine + '</td>';
        html += '<td>' + item.batch + '</td>';
        html += '<td>' + item.expiry + '</td>';
        html += '<td>' + item.quantity + '</td>';
        html += '<td>₹' + parseFloat(item.price).toFixed(2) + '</td>';
        html += '<td>₹' + parseFloat(itemTotal).toFixed(2) + '</td>';
        html += '</tr>';
    });
    
    html += '</tbody></table></div>';
    html += '<div class="alert alert-success"><strong>Total Items: ' + totalItems + ' | Total Value: ₹' + parseFloat(totalValue).toFixed(2) + '</strong></div>';
    
    $('#transferSummary').html(html);
    $('#submitBtn').prop('disabled', false);
    
    // Update hidden inputs for form submission
    updateHiddenInputs();
}

function updateHiddenInputs() {
    var transferItems = [];
    $.each(selectedItems, function(batchId, item) {
        transferItems.push({
            batch_id: item.batch_id,
            quantity: item.quantity,
            remarks: ''
        });
    });
    
    // Remove existing hidden inputs
    $('input[name="transfer_items[]"]').remove();
    
    // Add new hidden inputs
    $.each(transferItems, function(index, item) {
        $('#departmentTransferForm').append('<input type="hidden" name="transfer_items[' + index + '][batch_id]" value="' + item.batch_id + '">');
        $('#departmentTransferForm').append('<input type="hidden" name="transfer_items[' + index + '][quantity]" value="' + item.quantity + '">');
        $('#departmentTransferForm').append('<input type="hidden" name="transfer_items[' + index + '][remarks]" value="' + item.remarks + '">');
    });
}

$(document).ready(function() {
    // Initialize form
    loadSourceStocks();
});
</script>
