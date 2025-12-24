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

<!-- Debug Section -->
<div class="row" style="margin: 10px 0;">
    <div class="col-md-12">
        <div style="background: #f0f0f0; padding: 10px; border: 1px solid #ccc;">
            <strong>Debug Info:</strong><br>
            Centers: <?php echo count($centers ?? []); ?> found<br>
            Medicines: <?php echo count($medicines ?? []); ?> found<br>
            Stock Levels: <?php echo count($stock_levels ?? []); ?> found<br>
            <button onclick="testDropdowns()" class="btn btn-sm btn-info">Test Dropdowns</button>
            <button onclick="openModal()" class="btn btn-sm btn-success">Open Modal</button>
        </div>
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
        <button type="button" class="btn btn-success" onclick="openModal()">
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
                                                <button type="button" class="btn btn-xs btn-primary" onclick="openEditModal(<?php echo $level->id; ?>, <?php echo $level->center_id; ?>, <?php echo $level->medicine_id; ?>, '<?php echo addslashes($level->department); ?>')">
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
                        <a href="#" onclick="openModal()" class="alert-link">Click here to add a new configuration.</a>
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="simple_center_id">Center <span class="text-danger">*</span></label>
                        <select class="form-control" id="simple_center_id" name="center_id" required>
                            <option value="">Select Center</option>
                            <?php foreach($centers as $center): ?>
                                <option value="<?php echo $center->ID; ?>"><?php echo $center->center_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="simple_medicine_id">Medicine <span class="text-danger">*</span></label>
                        <select class="form-control" id="simple_medicine_id" name="medicine_id" required>
                            <option value="">Select Medicine</option>
                            <?php foreach($medicines as $medicine): ?>
                                <option value="<?php echo $medicine->id; ?>"><?php echo $medicine->medicine_name; ?> (<?php echo $medicine->medicine_code; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="simple_department">Department <span class="text-danger">*</span></label>
                <select class="form-control" id="simple_department" name="department" required>
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
                </select>
            </div>
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

    // Initialize Select2 for better dropdown experience (moved to modal open function)
    // Select2 will be initialized when modal opens to avoid conflicts
});

// Simple modal functions
function openModal() {
    console.log('Opening modal...');
    $('#modalOverlay').fadeIn();
    $('#simple_config_id').val('');
    $('#simpleModalTitle').text('Add Stock Level Configuration');
    $('#simpleStockLevelForm')[0].reset();
    $('#simple_center_id, #simple_medicine_id, #simple_department').val('');

    // Debug: Check if dropdowns have options
    console.log('Center options:', $('#simple_center_id option').length);
    console.log('Medicine options:', $('#simple_medicine_id option').length);
    console.log('Department options:', $('#simple_department option').length);

    // Force Select2 to refresh
    setTimeout(function() {
        try {
            if (typeof $.fn.select2 !== 'undefined') {
                $('#simple_center_id').select2('destroy').select2({ width: '100%' });
                $('#simple_medicine_id').select2('destroy').select2({ width: '100%' });
                $('#simple_department').select2('destroy').select2({ width: '100%' });
                console.log('Select2 reinitialized successfully');
            } else {
                console.warn('Select2 not available, using regular select');
            }
        } catch (e) {
            console.error('Select2 initialization failed:', e);
        }
    }, 100);
}

function testDropdowns() {
    console.log('Testing dropdowns...');
    console.log('Center element:', $('#simple_center_id'));
    console.log('Center options count:', $('#simple_center_id option').length);
    console.log('First center option:', $('#simple_center_id option:first').text());
    console.log('Medicine options count:', $('#simple_medicine_id option').length);
    console.log('Department options count:', $('#simple_department option').length);

    // Try to manually trigger Select2
    if (typeof $.fn.select2 !== 'undefined') {
        console.log('Select2 is available');
        $('#simple_center_id').select2('open');
    } else {
        console.log('Select2 is NOT available');
    }
}

function closeModal() {
    $('#modalOverlay').fadeOut();
}

function openEditModal(configId, centerId, medicineId, department) {
    console.log('Opening edit modal for config:', configId);

    // Show modal immediately
    openModal();
    $('#simpleModalTitle').text('Edit Stock Level Configuration');
    $('#simple_config_id').val(configId);

    // Set form values
    $('#simple_center_id').val(centerId);
    $('#simple_medicine_id').val(medicineId);
    $('#simple_department').val(department);

    // Load current values via AJAX
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
            console.log('AJAX Response:', response);
            if (response.success) {
                $('#simple_min_stock_level').val(response.data.min_stock_level);
                $('#simple_max_stock_level').val(response.data.max_stock_level);
                $('#simple_reorder_level').val(response.data.reorder_level);
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

