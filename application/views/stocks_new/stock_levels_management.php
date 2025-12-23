<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-cogs"></i> Stock Levels Management
            <small>Configure min/max stock levels by center and department</small>
        </h1>
    </div>
</div>

<!-- Breadcrumb -->
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
            <li class="active">Stock Levels Management</li>
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
                <form method="get" action="<?php echo base_url('stocks_new/stock_levels_management'); ?>" class="form-inline">
                    <div class="form-group">
                        <label>Center:</label>
                        <select name="center_id" class="form-control" id="centerFilter">
                            <option value="">All Centers</option>
                            <?php foreach($centers as $center): ?>
                                <option value="<?php echo $center->ID; ?>" <?php echo ($selected_center_id == $center->ID) ? 'selected' : ''; ?>>
                                    <?php echo $center->center_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Medicine:</label>
                        <select name="medicine_id" class="form-control" id="medicineFilter">
                            <option value="">All Medicines</option>
                            <?php foreach($medicines as $medicine): ?>
                                <option value="<?php echo $medicine->id; ?>" <?php echo ($selected_medicine_id == $medicine->id) ? 'selected' : ''; ?>>
                                    <?php echo $medicine->medicine_name; ?> (<?php echo $medicine->medicine_code; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Department:</label>
                        <select name="department" class="form-control" id="departmentFilter">
                            <option value="">All Departments</option>
                            <option value="CASH MEDICINE NOIDA" <?php echo ($selected_department == 'CASH MEDICINE NOIDA') ? 'selected' : ''; ?>>CASH MEDICINE NOIDA</option>
                            <option value="CASH MEDICINE GGN" <?php echo ($selected_department == 'CASH MEDICINE GGN') ? 'selected' : ''; ?>>CASH MEDICINE GGN</option>
                            <option value="CASH MEDICINE BASANT LOK" <?php echo ($selected_department == 'CASH MEDICINE BASANT LOK') ? 'selected' : ''; ?>>CASH MEDICINE BASANT LOK</option>
                            <option value="CASH MEDICINE SRINAGAR" <?php echo ($selected_department == 'CASH MEDICINE SRINAGAR') ? 'selected' : ''; ?>>CASH MEDICINE SRINAGAR</option>
                            <option value="CASH MEDICINE GHAZIABAD" <?php echo ($selected_department == 'CASH MEDICINE GHAZIABAD') ? 'selected' : ''; ?>>CASH MEDICINE GHAZIABAD</option>
                            <option value="CASH MEDICINE ROHINI" <?php echo ($selected_department == 'CASH MEDICINE ROHINI') ? 'selected' : ''; ?>>CASH MEDICINE ROHINI</option>
                            <option value="HORMONAL ROHINI" <?php echo ($selected_department == 'HORMONAL ROHINI') ? 'selected' : ''; ?>>HORMONAL ROHINI</option>
                            <option value="Hormonal Ghaziabad" <?php echo ($selected_department == 'Hormonal Ghaziabad') ? 'selected' : ''; ?>>Hormonal Ghaziabad</option>
                            <option value="HORMONAL SRINAGAR" <?php echo ($selected_department == 'HORMONAL SRINAGAR') ? 'selected' : ''; ?>>HORMONAL SRINAGAR</option>
                            <option value="Hormonal Basant Lok" <?php echo ($selected_department == 'Hormonal Basant Lok') ? 'selected' : ''; ?>>Hormonal Basant Lok</option>
                            <option value="Hormonal Gurgaon" <?php echo ($selected_department == 'Hormonal Gurgaon') ? 'selected' : ''; ?>>Hormonal Gurgaon</option>
                            <option value="Hormonal Noida" <?php echo ($selected_department == 'Hormonal Noida') ? 'selected' : ''; ?>>Hormonal Noida</option>
                            <option value="Embryologist Noida" <?php echo ($selected_department == 'Embryologist Noida') ? 'selected' : ''; ?>>Embryologist Noida</option>
                            <option value="OT Noida" <?php echo ($selected_department == 'OT Noida') ? 'selected' : ''; ?>>OT Noida</option>
                            <option value="OT Basant Lok" <?php echo ($selected_department == 'OT Basant Lok') ? 'selected' : ''; ?>>OT Basant Lok</option>
                            <option value="Embryology Basant Lok" <?php echo ($selected_department == 'Embryology Basant Lok') ? 'selected' : ''; ?>>Embryology Basant Lok</option>
                            <option value="Embryology Srinagar" <?php echo ($selected_department == 'Embryology Srinagar') ? 'selected' : ''; ?>>Embryology Srinagar</option>
                            <option value="OT Srinagar" <?php echo ($selected_department == 'OT Srinagar') ? 'selected' : ''; ?>>OT Srinagar</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Filter
                    </button>
                    <a href="<?php echo base_url('stocks_new/stock_levels_management'); ?>" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Clear
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add New Configuration Button -->
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addStockLevelModal">
            <i class="fa fa-plus"></i> Add New Stock Level Configuration
        </button>
    </div>
</div>
<br>

<!-- Stock Levels Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Medicine Center Stock Levels
                <span class="badge pull-right"><?php echo count($stock_levels); ?> configurations</span>
            </div>
            <div class="panel-body">
                <?php if(!empty($stock_levels)): ?>
                    <div class="table-responsive">
                        <table id="stockLevelsTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Center</th>
                                    <th>Medicine</th>
                                    <th>Department</th>
                                    <th>Current Stock</th>
                                    <th>Min Stock</th>
                                    <th>Max Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($stock_levels as $level): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $level->center_name; ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo $level->medicine_name; ?></strong><br>
                                            <small class="text-muted"><?php echo $level->medicine_code; ?></small>
                                        </td>
                                        <td><?php echo $level->department; ?></td>
                                        <td>
                                            <span class="badge badge-info"><?php echo number_format($level->current_stock); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning" id="min_stock_<?php echo $level->id; ?>">
                                                <?php echo number_format($level->min_stock_level); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger" id="max_stock_<?php echo $level->id; ?>">
                                                <?php echo number_format($level->max_stock_level); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-default" id="reorder_level_<?php echo $level->id; ?>">
                                                <?php echo number_format($level->reorder_level); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $current_stock = $level->current_stock;
                                            $min_stock = $level->min_stock_level;
                                            $max_stock = $level->max_stock_level;

                                            if ($current_stock <= $min_stock && $min_stock > 0) {
                                                echo '<span class="label label-danger">LOW STOCK</span>';
                                            } elseif ($current_stock >= $max_stock && $max_stock > 0) {
                                                echo '<span class="label label-warning">OVER STOCK</span>';
                                            } else {
                                                echo '<span class="label label-success">NORMAL</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($level->updated_at)); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-primary" onclick="editStockLevel(<?php echo $level->id; ?>, <?php echo $level->center_id; ?>, <?php echo $level->medicine_id; ?>, '<?php echo addslashes($level->department); ?>')">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-xs btn-danger" onclick="deleteStockLevel(<?php echo $level->id; ?>)">
                                                    <i class="fa fa-trash"></i> Delete
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
                        <i class="fa fa-info-circle"></i> No stock level configurations found matching your criteria.
                        <a href="#" data-toggle="modal" data-target="#addStockLevelModal" class="alert-link">Click here to add a new configuration.</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Stock Level Modal -->
<div class="modal fade" id="addStockLevelModal" tabindex="-1" role="dialog" aria-labelledby="addStockLevelModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal-close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addStockLevelModalLabel">Add Stock Level Configuration</h4>
            </div>
            <form id="stockLevelForm">
                <div class="modal-body">
                    <input type="hidden" id="config_id" name="config_id" value="">

                    <div class="form-group">
                        <label for="modal_center_id">Center <span class="text-danger">*</span></label>
                        <select class="form-control" id="modal_center_id" name="center_id" required>
                            <option value="">Select Center</option>
                            <?php foreach($centers as $center): ?>
                                <option value="<?php echo $center->ID; ?>"><?php echo $center->center_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="modal_medicine_id">Medicine <span class="text-danger">*</span></label>
                        <select class="form-control" id="modal_medicine_id" name="medicine_id" required>
                            <option value="">Select Medicine</option>
                            <?php foreach($medicines as $medicine): ?>
                                <option value="<?php echo $medicine->id; ?>"><?php echo $medicine->medicine_name; ?> (<?php echo $medicine->medicine_code; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="modal_department">Department <span class="text-danger">*</span></label>
                        <select class="form-control" id="modal_department" name="department" required>
                            <option value="">Select Department</option>
                            <option value="CASH MEDICINE NOIDA">CASH MEDICINE NOIDA</option>
                            <option value="CASH MEDICINE GGN">CASH MEDICINE GGN</option>
                            <option value="CASH MEDICINE BASANT LOK">CASH MEDICINE BASANT LOK</option>
                            <option value="CASH MEDICINE SRINAGAR">CASH MEDICINE SRINAGAR</option>
                            <option value="CASH MEDICINE GHAZIABAD">CASH MEDICINE GHAZIABAD</option>
                            <option value="CASH MEDICINE ROHINI">CASH MEDICINE ROHINI</option>
                            <option value="HORMONAL ROHINI">HORMONAL ROHINI</option>
                            <option value="Hormonal Ghaziabad">Hormonal Ghaziabad</option>
                            <option value="HORMONAL SRINAGAR">HORMONAL SRINAGAR</option>
                            <option value="Hormonal Basant Lok">Hormonal Basant Lok</option>
                            <option value="Hormonal Gurgaon">Hormonal Gurgaon</option>
                            <option value="Hormonal Noida">Hormonal Noida</option>
                            <option value="Embryologist Noida">Embryologist Noida</option>
                            <option value="OT Noida">OT Noida</option>
                            <option value="OT Basant Lok">OT Basant Lok</option>
                            <option value="Embryology Basant Lok">Embryology Basant Lok</option>
                            <option value="Embryology Srinagar">Embryology Srinagar</option>
                            <option value="OT Srinagar">OT Srinagar</option>
                            <option value="ot noida">ot
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modal_min_stock_level">Min Stock Level</label>
                                <input type="number" class="form-control" id="modal_min_stock_level" name="min_stock_level" min="0" value="0">
                                <small class="help-block">Alert when stock falls below this level</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modal_max_stock_level">Max Stock Level</label>
                                <input type="number" class="form-control" id="modal_max_stock_level" name="max_stock_level" min="0" value="0">
                                <small class="help-block">Prevent ordering above this level</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modal_reorder_level">Reorder Level</label>
                                <input type="number" class="form-control" id="modal_reorder_level" name="reorder_level" min="0" value="0">
                                <small class="help-block">Suggest reordering at this level</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default modal-close">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    <?php if(!empty($stock_levels)): ?>
    $('#stockLevelsTable').DataTable({
        "pageLength": 25,
        "order": [[ 0, "asc" ], [ 1, "asc" ]], // Sort by Center, then Medicine
        "columnDefs": [
            { "orderable": false, "targets": 9 } // Actions column
        ],
        "language": {
            "emptyTable": "No stock level configurations found",
            "zeroRecords": "No matching configurations found"
        },
        "responsive": true,
        "autoWidth": false
    });
    <?php endif; ?>

    // Initialize Select2 for better dropdown experience
    $('#modal_center_id, #modal_medicine_id, #modal_department').select2({
        dropdownParent: $('#addStockLevelModal'),
        width: '100%'
    });

    // Initialize Materialize modal after Materialize JS is loaded
    initializeModal();
});

function initializeModal() {
    // Check if Materialize is loaded
    if (typeof M !== 'undefined' && M.Modal) {
        var modalElement = document.getElementById('addStockLevelModal');
        if (modalElement) {
            var modalInstance = M.Modal.init(modalElement, {
                dismissible: true,
                opacity: 0.5,
                inDuration: 300,
                outDuration: 200,
                complete: function() {
                    // Reset modal when closed
                    $('#stockLevelForm')[0].reset();
                    $('#config_id').val('');
                    $('#addStockLevelModalLabel').text('Add Stock Level Configuration');

                    // Properly reset Select2 elements
                    $('#modal_center_id').val('').trigger('change.select2');
                    $('#modal_medicine_id').val('').trigger('change.select2');
                    $('#modal_department').val('').trigger('change.select2');

                    // Clear any validation states
                    $('.has-error').removeClass('has-error');
                    $('.text-danger').remove();
                }
            });
            // Make modalInstance available globally for other functions
            window.stockLevelModal = modalInstance;
        }
    } else {
        // Retry after a short delay if Materialize isn't loaded yet
        setTimeout(initializeModal, 100);
    }
}

// Edit stock level configuration
function editStockLevel(configId, centerId, medicineId, department) {
    // Set the config ID for editing
    $('#config_id').val(configId);

    // Set select values and trigger change for Select2
    $('#modal_center_id').val(centerId).trigger('change.select2');
    $('#modal_medicine_id').val(medicineId).trigger('change.select2');
    $('#modal_department').val(department).trigger('change.select2');

    // Get current values via AJAX
    $.ajax({
        url: '<?php echo base_url("stocks_new/get_medicine_center_stock_levels"); ?>',
        type: 'POST',
        data: {
            center_id: centerId,
            medicine_id: medicineId,
            department: department
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#modal_min_stock_level').val(response.data.min_stock_level);
                $('#modal_max_stock_level').val(response.data.max_stock_level);
                $('#modal_reorder_level').val(response.data.reorder_level);
                $('#addStockLevelModalLabel').text('Edit Stock Level Configuration');
                if (window.stockLevelModal) {
                    window.stockLevelModal.open();
                }
            } else {
                alert('Error loading configuration: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr, status, error);
            alert('Error loading configuration. Please check console for details.');
        }
    });
}

// Delete stock level configuration
function deleteStockLevel(configId) {
    if (confirm('Are you sure you want to delete this stock level configuration? This action cannot be undone.')) {
        $.ajax({
            url: '<?php echo base_url("stocks_new/delete_medicine_center_stock_config"); ?>',
            type: 'POST',
            data: { config_id: configId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Configuration deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error deleting configuration');
            }
        });
    }
}

// Form submission
$('#stockLevelForm').on('submit', function(e) {
    e.preventDefault();

    // Basic client-side validation
    var centerId = $('#modal_center_id').val();
    var medicineId = $('#modal_medicine_id').val();
    var department = $('#modal_department').val();

    if (!centerId || !medicineId || !department) {
        alert('Please fill in all required fields');
        return false;
    }

    // Show loading state
    var $saveBtn = $('#saveBtn');
    var originalText = $saveBtn.text();
    $saveBtn.prop('disabled', true).text('Saving...');

    var formData = new FormData(this);

    $.ajax({
        url: '<?php echo base_url("stocks_new/edit_medicine_center_stock_levels"); ?>',
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
</script>
