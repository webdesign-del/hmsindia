<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-edit"></i> Edit Batch
                <small>Editing Batch: <?php echo isset($batch->batch_number) ? htmlspecialchars($batch->batch_number) : 'N/A'; ?></small>
            </h1>
        </div>
    </div>
    
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('stocks_new/batches'); ?>">Batches</a></li>
                <li class="active">Edit Batch</li>
            </ol>
        </div>
    </div>
    
    <!-- Edit Batch Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-archive"></i> Batch Information
                </div>
                <div class="panel-body">
                    
                    <!-- *** NEW: READ-ONLY WARNING *** -->
                    <?php if (isset($is_editable) && $is_editable === false): ?>
                        <div class="alert alert-warning">
                            <i class="fa fa-lock"></i> <strong>This batch is read-only.</strong><br>
                            It cannot be edited because it has already been used in sales, transfers, or other stock movements. 
                            You can only update its "Batch Status" (e.g., to 'INACTIVE' or 'QUARANTINE') or "Remarks".
                        </div>
                    <?php endif; ?>
                    <!-- *** END NEW *** -->
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
                    
                    <!-- The form must point to the update_batch function -->
                    <form action="<?php echo base_url('stocks_new/update_batch'); ?>" method="post" class="form-horizontal">
                        
                        <!-- We must send the batch_id so the controller knows which batch to update -->
                        <input type="hidden" name="batch_id" value="<?php echo isset($batch->id) ? $batch->id : ''; ?>">
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Medicine *</label>
                                    <div class="col-sm-8">
                                        <select name="medicine_id" id="medicine_select" class="form-control" required style="width: 100%;">
                                        <?php 
                                            // The selected_medicine_details is loaded by the controller
                                            if (isset($selected_medicine_details) && $selected_medicine_details): 
                                        ?>
                                            <option value="<?php echo $selected_medicine_details->id; ?>" 
                                                    selected="selected" 
                                                    data-gst="<?php echo $selected_medicine_details->gst_rate; ?>"
                                                    data-pack-size="<?php echo isset($selected_medicine_details->pack_size) ? $selected_medicine_details->pack_size : '1'; ?>">
                                                <?php echo htmlspecialchars($selected_medicine_details->text); ?>
                                            </option>
                                        <?php else: ?>
                                            <option value="">Search and select medicine...</option>
                                        <?php endif; ?>
                                        </select>
                                        <small class="help-block">Type medicine name, generic name, or code to search</small>
                                    </div>
                                </div>
                                
                                <!-- GST Rate (Fetched by JavaScript) -->
                                <input type="hidden" step="0.01" class="form-control" id="gst_rate" name="gst_rate" 
                                       value="<?php echo isset($batch->gst_rate) ? $batch->gst_rate : '0.00'; ?>" readonly>
                                <!-- Pack Size (Fetched by JavaScript) -->
                                <input type="hidden" class="form-control" id="pack_size" name="pack_size" 
                                       value="<?php echo isset($selected_medicine_details->pack_size) ? $selected_medicine_details->pack_size : '1'; ?>" readonly>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Vendor *</label>
                                    <div class="col-sm-8">
                                        <select name="vendor_id" class="form-control" required>
                                            <option value="">Select Vendor</option>
                                            <?php foreach($vendors as $vendor): 
                                                // Check if this vendor is the selected one
                                                $selected = (isset($batch->vendor_id) && $batch->vendor_id == $vendor->ID) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $vendor->ID; ?>" <?php echo $selected; ?>>
                                                    <?php echo $vendor->name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Batch Number *</label>
                                    <div class="col-sm-8">
                                        <!-- Pre-fill the value from the $batch object -->
                                        <input type="text" name="batch_number" class="form-control" 
                                               value="<?php echo isset($batch->batch_number) ? htmlspecialchars($batch->batch_number) : set_value('batch_number'); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Expiry Date *</label>
                                    <div class="col-sm-8">
                                        <!-- Pre-fill the value -->
                                        <input type="date" name="expiry_date" class="form-control" 
                                               value="<?php echo isset($batch->expiry_date) ? htmlspecialchars($batch->expiry_date) : set_value('expiry_date'); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Purchase Date *</label>
                                    <div class="col-sm-8">
                                        <!-- Pre-fill the value -->
                                        <input type="date" name="purchase_date" class="form-control" 
                                               value="<?php echo isset($batch->purchase_date) ? htmlspecialchars($batch->purchase_date) : set_value('purchase_date', date('Y-m-d')); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Purchase Price *</label>
                                    <div class="col-sm-8">
                                        <!-- Pre-fill the value -->
                                        <input type="number" name="purchase_price" id="purchase_price" class="form-control" 
                                               value="<?php echo isset($batch->purchase_price) ? htmlspecialchars($batch->purchase_price) : set_value('purchase_price'); ?>" step="0.01" min="0" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Selling Price *</label>
                                    <div class="col-sm-8">
                                        <!-- Pre-fill the value -->
                                        <input type="number" name="selling_price" id="selling_price" class="form-control" 
                                               value="<?php echo isset($batch->selling_price) ? htmlspecialchars($batch->selling_price) : set_value('selling_price'); ?>" step="0.01" min="0" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">MRP</label>
                                    <div class="col-sm-8">
                                        <!-- Pre-fill the value -->
                                        <input type="number" name="mrp" class="form-control" 
                                               value="<?php echo isset($batch->mrp) ? htmlspecialchars($batch->mrp) : set_value('mrp'); ?>" step="0.01" min="0">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Quantity Purchased</label>
                                    <div class="col-sm-8">
                                        <!-- Quantity should be read-only. It can only be changed via stock movements. -->
                                        <input type="number" name="quantity_purchased" class="form-control" 
                                               value="<?php echo isset($batch->quantity_purchased) ? htmlspecialchars($batch->quantity_purchased) : set_value('quantity_purchased'); ?>" readonly>
                                        <small class="help-block">Cannot be edited. Use Stock Adjustment for changes.</small>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Invoice Number</label>
                                    <div class="col-sm-8">
                                        <!-- Pre-fill the value -->
                                        <input type="text" name="invoice_number" class="form-control" 
                                               value="<?php echo isset($batch->invoice_number) ? htmlspecialchars($batch->invoice_number) : set_value('invoice_number'); ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Invoice Date</label>
                                    <div class="col-sm-8">
                                        <!-- Pre-fill the value -->
                                        <input type="date" name="invoice_date" class="form-control" 
                                               value="<?php echo isset($batch->invoice_date) ? htmlspecialchars($batch->invoice_date) : set_value('invoice_date'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quality and Status -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Quality Status</label>
                                    <div class="col-sm-8">
                                        <select name="quality_status" class="form-control">
                                            <?php $qs = isset($batch->quality_status) ? $batch->quality_status : 'PENDING'; ?>
                                            <option value="PENDING" <?php echo ($qs == 'PENDING') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="APPROVED" <?php echo ($qs == 'APPROVED') ? 'selected' : ''; ?>>Approved</option>
                                            <option value="REJECTED" <?php echo ($qs == 'REJECTED') ? 'selected' : ''; ?>>Rejected</option>
                                            <option value="QUARANTINE" <?php echo ($qs == 'QUARANTINE') ? 'selected' : ''; ?>>Quarantine</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- NEW FIELD: Batch Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Batch Status</label>
                                    <div class="col-sm-8">
                                        <select name="batch_status" class="form-control">
                                            <?php $bs = isset($batch->batch_status) ? $batch->batch_status : 'ACTIVE'; ?>
                                            <option value="ACTIVE" <?php echo ($bs == 'ACTIVE') ? 'selected' : ''; ?>>Active</option>
                                            <option value="INACTIVE" <?php echo ($bs == 'INACTIVE') ? 'selected' : ''; ?>>Inactive</option>
                                            <option value="QUARANTINE" <?php echo ($bs == 'QUARANTINE') ? 'selected' : ''; ?>>Quarantine</option>
                                            <option value="DAMAGED" <?php echo ($bs == 'DAMAGED') ? 'selected' : ''; ?>>Damaged</option>
                                            <option value="RETURNED" <?php echo ($bs == 'RETURNED') ? 'selected' : ''; ?>>Returned</option>
                                            <option value="DISPOSED" <?php echo ($bs == 'DISPOSED') ? 'selected' : ''; ?>>Disposed</to-do>
                                            <option value="EXPIRED" <?php echo ($bs == 'EXPIRED') ? 'selected' : ''; ?>>Expired</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-10">
                                        <!-- Pre-fill the value -->
                                        <textarea name="remarks" class="form-control" rows="3" placeholder="Enter any remarks"><?php echo isset($batch->remarks) ? htmlspecialchars($batch->remarks) : set_value('remarks'); ?></textarea>
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
                                            <i class="fa fa-save"></i> Save Changes
                                        </button>
                                        <a href="<?php echo base_url('stocks_new/batches'); ?>" class="btn btn-default">
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
</div>

<!-- Include Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- All JavaScript from your add_batch file, unchanged -->
<script>
$(document).ready(function() {
    
    // 1. Initialize Select2
    $('#medicine_select').select2({
        ajax: {
            url: '<?php echo base_url("stocks_new/search_medicines"); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term // search term
                };
            },
            processResults: function (data) {
                var formattedData = $.map(data, function (obj) {
                    obj.text = obj.medicine_name;
                    return obj;
                });
                return {
                    results: formattedData
                };
            },
            cache: true
        },
        minimumInputLength: 2,
        placeholder: 'Search and select medicine...',
        templateResult: formatMedicine,
        templateSelection: formatMedicineSelection
    });
    
    // Function to format the dropdown list
    function formatMedicine (medicine) {
        if (medicine.loading) {
            return medicine.text;
        }
        
        var $container = $(
            '<div class="select2-result-medicine clearfix">' +
                '<div class="medicine-option">' +
                    '<div class="medicine-name"><strong>' + (medicine.medicine_name || 'N/A') + '</strong></div>' +
                    '<div class="medicine-details" style="font-size: 0.9em; color: #555;">' +
                        '<span class="medicine-gst_rate">GST: ' + (medicine.gst_rate || 'N/A') + '%</span>' +
                        ' | <span class="generic-name">Generic: ' + (medicine.generic_name || 'N/A') + '</span>' +
                        ' | <span class="medicine-code">Code: ' + (medicine.medicine_code || 'N/A') + '</span>' +
                        ' | <span class="brand-name">Brand: ' + (medicine.brand_name || 'N/A') + '</span>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );

        return $container;
    }

    // Function to format the selected item in the box
    function formatMedicineSelection (medicine) {
        if(medicine.id) {
            $(medicine.element).data('gst', medicine.gst_rate);
        }
        return medicine.medicine_name || medicine.text;
    }


    // 2. Event: When a medicine is selected
    $('#medicine_select').on('select2:select', function (e) {
        var data = e.params.data;
        var gstRate = data.gst_rate; 
        var packSize = data.pack_size || 1;

        if (gstRate !== undefined) {
            $('#gst_rate').val(gstRate);
            $('#pack_size').val(packSize);
            calculateSellingPrice(); 
        } else {
            var medicineId = data.id;
            $.ajax({
                url: '<?php echo base_url("stocks_new/search_medicines"); ?>',
                type: 'POST',
                data: { id: medicineId },
                dataType: 'json',
                success: function(response) {
                    if(response.success && response.medicine) {
                        $('#gst_rate').val(response.medicine.gst_rate);
                        $('#pack_size').val(response.medicine.pack_size || 1);
                        calculateSellingPrice(); 
                    } else {
                        alert('Could not fetch medicine details.');
                        $('#gst_rate').val('');
                        $('#pack_size').val('1');
                    }
                },
                error: function() {
                    alert('AJAX error fetching medicine details.');
                }
            });
        }
    });

    // 3. Event: When Purchase Price is typed
    $('#purchase_price').on('keyup change', function() {
        calculateSellingPrice();
    });

    // 4. The Calculation Function
    function calculateSellingPrice() {
        var purchasePrice = parseFloat($('#purchase_price').val());
        var gstRate = parseFloat($('#gst_rate').val());

        if (!isNaN(purchasePrice) && !isNaN(gstRate)) {
            var taxAmount = purchasePrice * (gstRate / 100);
            var sellingPrice = purchasePrice + taxAmount;
            $('#selling_price').val(sellingPrice.toFixed(2));
        } else {
             // Don't clear it, just let it be
        }
    }
    
    // 5. Initial Check (if page is loaded with pre-selected medicine)
    // This is CRITICAL for the edit form
    var $selectedOption = $('#medicine_select').find(':selected');
    if ($selectedOption.val()) {
        
        // If the 'data-gst' attribute exists (from a pre-selected option)
        if ($selectedOption.data('gst') !== undefined) {
             $('#gst_rate').val($selectedOption.data('gst'));
        } 
        // If not, use the hidden field's value (already set by PHP)
        // No 'else' needed, as the field is already populated
        
        // Set pack_size from data attribute or hidden field
        var packSize = $selectedOption.data('pack-size') || $('#pack_size').val() || 1;
        $('#pack_size').val(packSize);
        
        // Run calculation in case purchase price is also pre-filled
        calculateSellingPrice();
    }

});
</script>