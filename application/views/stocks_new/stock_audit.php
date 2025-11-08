<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Include Select2 CSS/JS if not already included in your header -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-clipboard-check"></i> Stock Audit
            <small>Physical stock verification and adjustment</small>
        </h1>
    </div>
</div>

<!-- Messages -->
<div class="row">
    <div class="col-md-12">
        <?php if(validation_errors()): ?>
            <div class="alert alert-danger"><?php echo validation_errors(); ?></div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Center Selection Filter -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-filter"></i> Select Location to Audit
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url('stocks_new/stock_audit'); ?>" method="get" class="form-inline">
                    <div class="form-group">
                        <label for="center_select_filter">Select Location:</label>
                        <select name="center_id" id="center_select_filter" class="form-control" style="min-width: 300px;">
                            <option value="">-- Select a Center or Warehouse --</option>
                            <option value="central" <?php echo ($selected_center_id == 'central') ? 'selected' : ''; ?>>
                                Central Warehouse
                            </option>
                            <?php foreach($centers as $center): ?>
                                <option value="<?php echo $center->ID; ?>" <?php echo ($selected_center_id == $center->ID) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($center->center_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- *** NEW: Department Filter *** -->
                    <div class="form-group" id="department_filter_group" 
                         style="display: <?php echo (is_numeric($selected_center_id)) ? 'inline-block' : 'none'; ?>;">
                        <label for="department_select_filter">Department:</label>
                        <select name="department" id="department_select_filter" class="form-control" style="min-width: 250px;">
                            <option value="">All Departments</option>
                            <?php if(!empty($departments)): ?>
                                <?php foreach($departments as $dept): ?>
                                    <option value="<?php echo $dept['department']; ?>" <?php echo ($selected_department == $dept['department']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($dept['department']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <!-- *** END NEW *** -->

                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Load Batches</button>
                    <a href="<?php echo base_url('stocks_new/stock_audit'); ?>" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Reset
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php // Only show the audit form if a center has been selected ?>
<?php if (!empty($selected_center_id)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-clipboard-check"></i> Stock Audit Form
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url('stocks_new/process_audit'); ?>" method="post" class="form-horizontal">
                    <input type="hidden" name="action" value="stock_audit">
                    <!-- Pass the selected center_id to the processing function -->
                    <input type="hidden" name="center_id" value="<?php echo htmlspecialchars($selected_center_id); ?>">
                    
                    <!-- *** NEW: Hidden Department Field *** -->
                    <input type="hidden" name="department" value="<?php echo htmlspecialchars($selected_department); ?>">


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
                                <label class="col-sm-4 control-label">Location Audited *</label>
                                <div class="col-sm-8">
                                    <?php
                                        $audited_location_name = 'N/A';
                                        if ($selected_center_id == 'central') {
                                            $audited_location_name = 'Central Warehouse';
                                        } else {
                                            foreach($centers as $center) {
                                                if ($center->ID == $selected_center_id) {
                                                    $audited_location_name = $center->center_name;
                                                    break;
                                                }
                                            }
                                        }
                                        // *** NEW: Add department name ***
                                        if (!empty($selected_department)) {
                                            $audited_location_name .= ' (' . $selected_department . ')';
                                        }
                                    ?>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($audited_location_name); ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Audit Type *</label>
                                <div class="col-sm-8">
                                    <select name="audit_type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="FULL" <?php echo set_select('audit_type', 'FULL'); ?>>Full Audit</option>
                                        <option value="CYCLIC" <?php echo set_select('audit_type', 'CYCLIC'); ?>>Cyclic Count</option>
                                        <option value="RANDOM" <?php echo set_select('audit_type', 'RANDOM'); ?>>Random Spot Check</option>
                                    </select>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-4 control-label">Auditor Name *</label>
                                <div class="col-sm-8">
                                    <input type="text" name="auditor_name" class="form-control" placeholder="Enter Auditor Name" value="<?php echo set_value('auditor_name'); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-12">
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Remarks</label>
                                <div class="col-sm-10">
                                    <textarea name="remarks" class="form-control" rows="2" placeholder="Enter audit remarks"><?php echo set_value('remarks'); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Items -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Audit Items</h4>
                            <p class="text-info"><i class="fa fa-info-circle"></i> Batches with 0 stock are pre-filled. Add any batches found physically but not listed.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="audit_items_table">
                                    <thead>
                                        <tr>
                                            <th style="width: 35%;">Medicine (Batch)</th>
                                            <th>Batch Number</th>
                                            <!-- *** NEW: Department Column *** -->
                                            <?php if(empty($selected_department) && $selected_center_id != 'central'): ?>
                                            <th>Department</th>
                                            <?php endif; ?>
                                            <th>System Qty</th>
                                            <th>Physical Qty *</th>
                                            <th>Variance</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; ?>
                                        <?php if(!empty($available_batches)): ?>
                                            <?php foreach($available_batches as $batch): ?>
                                                <tr>
                                                    <td>
                                                        <select name="audit_items[<?php echo $i; ?>][batch_id]" class="form-control batch_select" required style="width: 100%;">
                                                            <option value="<?php echo $batch->batch_id; ?>" 
                                                                    data-batch-no="<?php echo htmlspecialchars($batch->batch_number); ?>"
                                                                    data-system-qty="<?php echo $batch->system_quantity; ?>"
                                                                    data-department="<?php echo htmlspecialchars($batch->department ?? 'N/A'); ?>"
                                                                    selected>
                                                                <?php echo htmlspecialchars($batch->medicine_name . ' - ' . $batch->batch_number); ?>
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td><span class="batch_number"><?php echo htmlspecialchars($batch->batch_number); ?></span></td>
                                                    
                                                    <!-- *** NEW: Department Column Data *** -->
                                                    <?php if(empty($selected_department) && $selected_center_id != 'central'): ?>
                                                        <td><span class="department"><?php echo htmlspecialchars($batch->department ?? 'N/A'); ?></span></td>
                                                    <?php endif; ?>
                                                    
                                                    <td><span class="system_quantity"><?php echo $batch->system_quantity; ?></span></td>
                                                    <td>
                                                        <input type="number" name="audit_items[<?php echo $i; ?>][physical_quantity]" class="form-control physical_quantity" min="0" required value="0">
                                                    </td>
                                                    <td><span class="variance text-danger">-<?php echo $batch->system_quantity; ?></span></td>
                                                    <td><span class="status"><span class="badge" style="background-color: #d9534f;">Shortage</span></span></td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove_row">
                                                            <i class="fa fa-trash-o"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <!-- Row 0: If no batches found, show one empty row to add -->
                                            <tr>
                                                <td>
                                            <select name="audit_items[0][batch_id]" class="form-control batch_select" required style="width: 100%;">
    <option value="">Select Medicine</option>
    <?php if (!empty($all_batches_list)): ?>
        <?php foreach ($all_batches_list as $batch): ?>
            <?php 
                // Safely extract values with defaults — prevents warnings
                $batch_id = $batch->batch_id ?? '';
                $batch_no = htmlspecialchars($batch->batch_number ?? '');
                $system_qty = htmlspecialchars($batch->system_quantity ?? 0);
                $department = htmlspecialchars($batch->department ?? 'N/A');
                $medicine_name = htmlspecialchars($batch->medicine_name ?? '');
            ?>
            <option 
                value="<?= $batch_id ?>" 
                data-batch-no="<?= $batch_no ?>"
                data-system-qty="<?= $system_qty ?>"
                data-department="<?= $department ?>">
                <?= "{$medicine_name} - {$batch_no} ({$department})" ?>
            </option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>

                                                </td>
                                                <td><span class="batch_number">-</span></td>
                                                
                                                <!-- *** NEW: Department Column *** -->
                                                <?php if(empty($selected_department) && $selected_center_id != 'central'): ?>
                                                    <td><span class="department">-</span></td>
                                                <?php endif; ?>
                                                
                                                <td><span class="system_quantity">0</span></td>
                                                <td>
                                                    <input type="number" name="audit_items[0][physical_quantity]" class="form-control physical_quantity" min="0" required>
                                                </td>
                                                <td><span class="variance">-</span></td>
                                                <td><span class="status">-</span></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove_row">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-success" id="add_audit_item">
                                <i class="fa fa-plus"></i> Add Item (for found stock not listed)
                            </button>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Audit Summary -->
                    <div class="row">
                         <!-- ... (your summary code here) ... -->
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to save this audit? Stock levels will be adjusted.')">
                                        <i class="fa fa-save"></i> Save and Adjust Stock
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
<?php endif; // End check for selected_center_id ?>


<script>
$(document).ready(function() {
    // Initialize Select2 on the filter dropdown
    $('#center_select_filter').select2({
        placeholder: "Select a Center or Warehouse",
        allowClear: true
    });
    
    // Initialize Select2 for all pre-loaded batch dropdowns
    $('.batch_select').each(function() {
        initializeSelect2(this);
    });

    // --- Row counter, starts after the pre-filled rows ---
    var rowCount = <?php echo $i; ?>; // Use the counter from PHP

    // --- Add new audit item row ---
    $('#add_audit_item').click(function() {
        var newRow = `
            <tr>
                <td>
                    <select name="audit_items[${rowCount}][batch_id]" class="form-control batch_select" required style="width: 100%;">
                        <option value="">Select Medicine</option>
                        <?php if(!empty($all_batches_list)):
                            foreach($all_batches_list as $batch): ?>
                            <option value="<?php echo $batch->batch_id; ?>" 
                                    data-batch-no="<?php echo htmlspecialchars($batch->batch_number); ?>"
                                    data-system-qty="<?php echo $batch->system_quantity; ?>"
                                    data-department="<?php echo htmlspecialchars($batch->department ?? 'N/A'); ?>">
                                <?php echo htmlspecialchars($batch->medicine_name . ' - ' . $batch->batch_number . ' (' . ($batch->department ?? 'N/A') . ')'); ?>
                            </option>
                        <?php endforeach;
                            endif; ?>
                    </select>
                </td>
                <td><span class="batch_number">-</span></td>
                
                <!-- *** NEW: Department Column *** -->
                <?php if(empty($selected_department) && $selected_center_id != 'central'): ?>
                    <td><span class="department">-</span></td>
                <?php endif; ?>

                <td><span class="system_quantity">0</span></td>
                <td><input type="number" name="audit_items[${rowCount}][physical_quantity]" class="form-control physical_quantity" min="0" required></td>
                <td><span class="variance">-</span></td>
                <td><span class="status">-</span></td>
                <td><button type="button" class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash-o"></i></button></td>
            </tr>`;
        
        var newRowEl = $(newRow);
        $('#audit_items_table tbody').append(newRowEl);
        initializeSelect2(newRowEl.find('.batch_select')); 
        rowCount++;
    });

    // --- Remove row ---
    $(document).on('click', '.remove_row', function() {
         $(this).closest('tr').find('.batch_select').select2('destroy');
        $(this).closest('tr').remove();
        calculateSummary();
    });

    // --- Batch selection change ---
    $(document).on('change', '.batch_select', function() {
        var selectedOption = $(this).find('option:selected');
        var row = $(this).closest('tr');
        var systemQty = selectedOption.data('system-qty') || 0; 
        
        row.find('.batch_number').text(selectedOption.data('batch-no') || '-');
        row.find('.department').text(selectedOption.data('department') || '-'); // Update department
        row.find('.system_quantity').text(systemQty);
        
        // Trigger calculation on physical quantity
        row.find('.physical_quantity').trigger('input');
    });

    // --- Calculate variance on physical quantity input ---
    $(document).on('input', '.physical_quantity', function() {
        var row = $(this).closest('tr');
        var physicalQty = parseFloat($(this).val()) || 0;
        var systemQty = parseFloat(row.find('.system_quantity').text()) || 0;
        var variance = physicalQty - systemQty;
        
        row.find('.variance').text(variance);
        
        if (variance > 0) {
            row.find('.status').html('<span class="label label-success">Surplus</span>'); // Changed from Access
            row.find('.variance').removeClass('text-danger').addClass('text-success');
        } else if (variance < 0) {
            row.find('.status').html('<span class="label label-danger">Shortage</span>');
            row.find('.variance').removeClass('text-success').addClass('text-danger');
        } else {
            row.find('.status').html('<span class="label label-primary">Match</span>');
            row.find('.variance').removeClass('text-success text-danger');
        }
        
        calculateSummary();
    });

    // --- Calculate summary ---
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
    
    // --- Helper function for Select2 ---
    function initializeSelect2(selector) {
         $(selector).select2({
            placeholder: "Select Batch",
            allowClear: true,
            width: 'resolve'
        });
    }

    // --- Initial calculation on page load ---
    $('.physical_quantity').trigger('input');
    calculateSummary();

    // --- *** NEW: JavaScript for Department Filter *** ---
    $('#center_select_filter').on('change', function() {
        var centerId = $(this).val();
        var $deptFilter = $('#department_filter_group');
        var $deptSelect = $('#department_select_filter');
        $deptSelect.html('<option value="">All Departments</option>'); // Reset
        if (centerId && centerId !== 'central') {
            $deptFilter.show();
            // Fetch departments for this center via AJAX
            $.ajax({
                url: '<?php echo base_url('stocks_new/get_departments_for_center_json'); ?>/' + centerId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.departments) {
                        $.each(response.departments, function(index, dept) {
                            $deptSelect.append(new Option(dept.department, dept.department));
                        });
                    }
                },
                error: function() {
                    console.error('Failed to load departments.');
                    alert('Could not load departments for this center.');
                }
            });
        } else {
            // It's "Central Warehouse" or empty, hide department filter
            $deptFilter.hide();
        }
    });

});
</script>