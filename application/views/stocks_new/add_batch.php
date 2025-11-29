<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-plus"></i> Add New Batch
                <small>Add a new medicine batch to inventory</small>
            </h1>
        </div>
    </div>
    
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('stocks_new/batches'); ?>">Batches</a></li>
                <li class="active">Add Batch</li>
            </ol>
        </div>
    </div>
    
    <!-- Excel Import Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-file-excel-o"></i> Import Batches from Excel
                </div>
                <div class="panel-body">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <strong><i class="fa fa-exclamation-triangle"></i> Error:</strong>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error_details')): ?>
                        <div class="alert alert-danger">
                            <strong><i class="fa fa-exclamation-triangle"></i> Import Errors:</strong>
                            <div style="max-height: 400px; overflow-y: auto; margin-top: 10px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">
                                <?php echo $this->session->flashdata('error_details'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <strong><i class="fa fa-check-circle"></i> Success:</strong>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info">
                        <strong><i class="fa fa-info-circle"></i> Instructions:</strong>
                            <ul style="margin-bottom: 0;">
                                <li>Upload an Excel file (.xlsx, .xls) with batch data</li>
                                <li>First row should contain column headers</li>
                                <li>Required columns: Medicine Code/Name, Vendor Name, Batch Number, Expiry Date, Purchase Date, Purchase Price, Selling Price, Quantity Purchased</li>
                                <li>Optional columns: MRP, Invoice Number, Invoice Date, Quality Status, Remarks</li>
                                <!-- <li>Download <a href="<?php echo base_url('stocks_new/download_batch_template'); ?>" target="_blank"><strong>Excel Template</strong></a> for reference</li> -->
                                <li>Download <a href="<?php echo base_url('stocks_new/download_batch_sample'); ?>" target="_blank"><strong>Sample Excel File</strong></a> with example data</li>
                            </ul>
                    </div>
                    <form action="<?php echo base_url('stocks_new/import_batches_excel'); ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Select Excel File *</label>
                            <div class="col-sm-6">
                                <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
                                <small class="help-block">Supported formats: .xlsx, .xls</small>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-upload"></i> Import Batches
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <a href="<?php echo base_url('stocks_new/download_batch_template'); ?>" class="btn btn-info" target="_blank">
                                    <i class="fa fa-download"></i> Download Template
                                </a>
                                <a href="<?php echo base_url('stocks_new/download_batch_sample'); ?>" class="btn btn-primary" target="_blank">
                                    <i class="fa fa-file-excel-o"></i> Download Sample File
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Batch Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-archive"></i> Batch Information
                </div>
                <div class="panel-body">
                    <?php if(validation_errors()): ?>
                        <div class="alert alert-danger">
                            <?php echo validation_errors(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <strong><i class="fa fa-exclamation-triangle"></i> Error:</strong>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error_details')): ?>
                        <div class="alert alert-danger">
                            <strong><i class="fa fa-exclamation-triangle"></i> Import Errors:</strong>
                            <div style="max-height: 400px; overflow-y: auto; margin-top: 10px;">
                                <?php echo $this->session->flashdata('error_details'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <strong><i class="fa fa-check-circle"></i> Success:</strong>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo base_url('stocks_new/add_batch'); ?>" method="post" class="form-horizontal">
                        <input type="hidden" name="action" value="add_batch">
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Medicine *</label>
                                    <div class="col-sm-8">
                                        <select name="medicine_id" id="medicine_select" class="form-control" required style="width: 100%;">
                                        <?php 
                                            // Check if a medicine is pre-selected from the controller
                                            if (isset($selected_medicine_details) && $selected_medicine_details): 
                                            ?>
                                                <option value="<?php echo $selected_medicine_details->id; ?>" selected="selected" data-gst="<?php echo $selected_medicine_details->gst_rate; ?>" data-pack-size="<?php echo isset($selected_medicine_details->pack_size) ? $selected_medicine_details->pack_size : '1'; ?>">
                                                    <?php echo htmlspecialchars($selected_medicine_details->text); // Use 'text' property ?>
                                                </option>
                                            <?php else: ?>
                                                <option value="">Search and select medicine...</option>
                                            <?php endif; ?>
                                        </select>
                                        <small class="help-block">Type medicine name, generic name, or code to search</small>
                                    </div>
                                </div>
                                <!-- GST Rate (Fetched by JavaScript) -->
                                    <input type="hidden" step="0.01" class="form-control" id="gst_rate" name="gst_rate" placeholder="GST rate will be fetched automatically" readonly>
                                <!-- Pack Size (Fetched by JavaScript) -->
                                    <input type="hidden" class="form-control" id="pack_size" name="pack_size" placeholder="Pack size will be fetched automatically" readonly>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Vendor *</label>
                                    <div class="col-sm-8">
                                        <select name="vendor_id" class="form-control" required>
                                            <option value="">Select Vendor</option>
                                            <?php foreach($vendors as $vendor): ?>
                                                <option value="<?php echo isset($vendor->ID) ? $vendor->ID : $vendor->id; ?>" <?php echo set_select('vendor_id', isset($vendor->ID) ? $vendor->ID : $vendor->id); ?>>
                                                    <?php echo isset($vendor->vendor_name) ? $vendor->vendor_name : (isset($vendor->name) ? $vendor->name : 'N/A'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Batch Number *</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_number" class="form-control" placeholder="Enter batch number" value="<?php echo set_value('batch_number'); ?>" required>
                                    </div>
                                </div>
                                
                                <!-- <div class="form-group">
                                    <label class="col-sm-4 control-label">Manufacturing Date</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="manufacturing_date" class="form-control" value="<?php echo set_value('manufacturing_date'); ?>">
                                    </div>
                                </div> -->
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Expiry Date *</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="expiry_date" class="form-control" value="<?php echo set_value('expiry_date'); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Purchase Date *</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="purchase_date" class="form-control" value="<?php echo set_value('purchase_date', date('Y-m-d')); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Purchase Price (with tax amount) *</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="purchase_price" id="purchase_price" class="form-control" placeholder="Enter purchase price per unit with tax" value="<?php echo set_value('purchase_price'); ?>" step="0.01" min="0" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">MRP</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="mrp" id="mrp" class="form-control" placeholder="Enter MRP" value="<?php echo set_value('mrp'); ?>" step="0.01" min="0">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Selling Price(mrp price) *</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="selling_price" id="selling_price" class="form-control" placeholder="Enter selling price per unit" value="<?php echo set_value('selling_price'); ?>" step="0.01" min="0" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Quantity Purchased *</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="quantity_purchased" class="form-control" placeholder="Enter quantity purchased" value="<?php echo set_value('quantity_purchased'); ?>" min="0" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Invoice Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="invoice_number" class="form-control" placeholder="Enter invoice number" value="<?php echo set_value('invoice_number'); ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Invoice Date</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="invoice_date" class="form-control" value="<?php echo set_value('invoice_date'); ?>">
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
                                            <option value="PENDING" <?php echo set_select('quality_status', 'PENDING'); ?>>Pending</option>
                                            <option value="APPROVED" <?php echo set_select('quality_status', 'APPROVED'); ?>>Approved</option>
                                            <option value="REJECTED" <?php echo set_select('quality_status', 'REJECTED'); ?>>Rejected</option>
                                            <option value="QUARANTINE" <?php echo set_select('quality_status', 'QUARANTINE'); ?>>Quarantine</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
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
                                            <i class="fa fa-save"></i> Add Batch
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
    
    <!-- Help Information -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-info-circle"></i> Important Information
                </div>
                <div class="panel-body">
                    <ul>
                        <li><strong>Batch Number:</strong> Must be unique for each medicine. This helps in tracking and FIFO management.</li>
                        <li><strong>Expiry Date:</strong> Used for FEFO (First Expiry First Out) stock rotation.</li>
                        <li><strong>Purchase Price:</strong> Cost price per unit for inventory valuation.</li>
                        <li><strong>Selling Price:</strong> Price at which medicine will be sold to patients.</li>
                        <li><strong>Quality Status:</strong> Set to 'Pending' initially. Update after quality check.</li>
                        <li><strong>Stock Addition:</strong> Adding a batch automatically adds stock to central warehouse.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
    $('#medicine_select').on('select2:select', function (e) {
        var data = e.params.data;
        var gstRate = data.gst_rate; // Get GST from the selected data
        var packSize = data.pack_size || 1; // Get pack_size from the selected data, default to 1
        
        if (gstRate !== undefined) {
            $('#gst_rate').val(gstRate);
            $('#pack_size').val(packSize);
            calculateSellingPrice(); // Re-calculate price
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
                        calculateSellingPrice(); // Re-calculate price
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
    $('#mrp').on('keyup change', function() {
        calculateSellingPrice();
    });
    function calculateSellingPrice() {
        // var purchasePrice = parseFloat($('#purchase_price').val());
        var mrp = parseFloat($('#mrp').val());
        console.log(mrp);
        var gstRate = parseFloat($('#gst_rate').val());
        if (!isNaN(mrp)) {
        // if (!isNaN(mrp) && !isNaN(gstRate)) {
            // var taxAmount = mrp * (gstRate / 100);
            // var sellingPrice = mrp;
            // var sellingPrice = purchasePrice + taxAmount;
            $('#selling_price').val(mrp.toFixed(2));
        } else {
            $('#selling_price').val('');
        }
    }
    
    // 5. Initial Check (if page is loaded with pre-selected medicine)
    var $selectedOption = $('#medicine_select').find(':selected');
    if ($selectedOption.val() && $selectedOption.data('gst') !== undefined) {
        // Found a pre-selected option with a 'data-gst' attribute
        $('#gst_rate').val($selectedOption.data('gst'));
        var packSize = $selectedOption.data('pack-size') || 1;
        $('#pack_size').val(packSize);
        // Run calculation in case purchase price is also pre-filled
        calculateSellingPrice();
    }

});
</script>
<script>
$(document).ready(function() {
    // Initialize Select2 for medicine search
    // $('#medicine_select').select2({
    //     placeholder: 'Search and select medicine...',
    //     allowClear: true,
    //     width: '100%',
    //     ajax: {
    //         url: '<?php echo base_url("stocks_new/search_medicines"); ?>',
    //         dataType: 'json',
    //         delay: 250,
    //         data: function (params) {
    //             return {
    //                 q: params.term, // search term
    //                 page: params.page
    //             };
    //         },
    //         processResults: function (data, params) {
    //             params.page = params.page || 1;
    //             return {
    //                 results: data,
    //                 pagination: {
    //                     more: (params.page * 30) < data.total_count
    //                 }
    //             };
    //         },
    //         cache: true
    //     },
    //     minimumInputLength: 1,
    //     templateResult: function(medicine) {
    //         if (medicine.loading) {
    //             return medicine.text;
    //         }
            
    //         var $result = $(
    //             '<div class="medicine-option">' +
    //                 '<div class="medicine-name"><strong>' + medicine.medicine_name + '</strong></div>' +
    //                 '<div class="medicine-details">' +
    //                     '<span class="generic-name">Generic: ' + medicine.generic_name + '</span>' +
    //                     '<span class="medicine-code"> | Code: ' + medicine.medicine_code + '</span>' +
    //                     '<span class="brand-name"> | Brand: ' + medicine.brand_name + '</span>' +
    //                 '</div>' +
    //             '</div>'
    //         );
            
    //         return $result;
    //     },
    //     templateSelection: function(medicine) {
    //         if (medicine.id === '' || !medicine.id) {
    //             return medicine.text || 'Search and select medicine...';
    //         }
    //         // Handle both AJAX results and pre-loaded options
    //         if (medicine.medicine_name && medicine.generic_name) {
    //             return medicine.medicine_name + ' (' + medicine.generic_name + ')';
    //         } else if (medicine.text) {
    //             return medicine.text;
    //         } else {
    //             return 'Selected Medicine';
    //         }
    //     },
    //     escapeMarkup: function (markup) {
    //         return markup;
    //     }
    // });
//       $('#medicine_select').select2({
//         ajax: {
//             url: '<?php echo base_url("stocks_new/search_medicines"); ?>',
//             dataType: 'json',
//             delay: 250,
//             data: function (params) {
//                 return {
//                     search: params.term // search term
//                 };
//             },
//             processResults: function (data) {
//                 return {
//                     results: data
//                 };
//             },
//             cache: true
//         },
//         minimumInputLength: 2,
//         placeholder: 'Search and select medicine...',
//         // --- *** NEW: Add functions to format the search results *** ---
//         templateResult: formatMedicine,
//         templateSelection: formatMedicineSelection
//         // --- *** END NEW *** ---
//     });
//     // --- *** NEW: Function to format the dropdown list *** ---
//     function formatMedicine (medicine) {
//         if (medicine.loading) {
//             return medicine.text;
//         }
//         // This is the code you pointed out, now with the CORRECT label
//         var $container = $(
//             '<div class="select2-result-medicine clearfix">' +
//                 '<div class="medicine-option">' +
//                     '<div class="medicine-name"><strong>' + medicine.medicine_name + '</strong></div>' +
//                     '<div class="medicine-details" style="font-size: 0.9em; color: #555;">' +
//                         // *** THIS IS THE FIX: Changed "Name:" to "GST:" ***
//                         '<span class="medicine-gst_rate">GST: ' + (medicine.gst_rate || 'N/A') + '%</span>' +
//                         ' | <span class="generic-name">Generic: ' + (medicine.generic_name || 'N/A') + '</span>' +
//                         ' | <span class="medicine-code">Code: ' + (medicine.medicine_code || 'N/A') + '</span>' +
//                         ' | <span class="brand-name">Brand: ' + (medicine.brand_name || 'N/A') + '</span>' +
//                     '</div>' +
//                 '</div>' +
//             '</div>'
//         );

//         return $container;
//     }
//     // --- *** NEW: Function to format the selected item *** ---
//     function formatMedicineSelection (medicine) {
//         return medicine.medicine_name || medicine.text;
//     }
//     $('#vendor_select').select2({
//         placeholder: 'Select a vendor',
//         allowClear: true,
//         width: '100%'
//     });
//     $('input[name="expiry_date"]').on('change', function() {
//         var expiryDate = new Date($(this).val());
//         var today = new Date();
//         var diffTime = expiryDate - today;
//         var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
//         if (diffDays < 0) {
//             alert('Warning: This batch has already expired!');
//         } else if (diffDays <= 30) {
//             alert('Warning: This batch will expire within 30 days!');
//         }
//     });
//     $('input[name="purchase_price"]').on('input', function() {
//         var purchasePrice = parseFloat($(this).val());
//         if (purchasePrice > 0) {
//             var suggestedSellingPrice = purchasePrice * 1.12; // 12% markup
//             $('input[name="selling_price"]').val(suggestedSellingPrice.toFixed(2));
//         }
//     });
});
</script>

<style>
.medicine-option {
    padding: 5px 0;
}
.medicine-name {
    font-weight: bold;
    color: #333;
}
.medicine-details {
    font-size: 12px;
    color: #666;
    margin-top: 2px;
}
.generic-name {
    color: #2c5aa0;
}
.medicine-code {
    color: #666;
}
.brand-name {
    color: #8b4513;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #007bff;
}
.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #e9ecef;
}
</style>

