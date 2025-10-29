<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-undo"></i> Add New Vendor Return
            <small>Process a return to a vendor</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
            <li><a href="<?php echo base_url('stocks_new/vendor_returns'); // Assuming you have a list page ?>">Vendor Returns</a></li>
            <li class="active">Add Return</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-undo"></i> Vendor Return Information
            </div>
            <div class="panel-body">
                <?php if(validation_errors()): ?>
                    <div class="alert alert-danger"> <?php echo validation_errors(); ?> </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"> <?php echo $this->session->flashdata('error'); ?> </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success"> <?php echo $this->session->flashdata('success'); ?> </div>
                <?php endif; ?>

                <form action="<?php echo base_url('stocks_new/process_vendor_return'); ?>" method="post" class="form-horizontal">
                    <input type="hidden" name="action" value="add_vendor_return">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Vendor *</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="vendor_select" name="vendor_id" required>
                                        <option value="">Select Vendor</option>
                                        <?php if(isset($vendors) && !empty($vendors)): ?>
                                            <?php foreach($vendors as $vendor): ?>
                                                <option value="<?php echo $vendor->ID; ?>" <?php echo set_select('vendor_id', $vendor->ID); ?>>
                                                    <?php echo htmlspecialchars($vendor->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Return Date *</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" name="return_date" value="<?php echo set_value('return_date', date('Y-m-d')); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Center (From) *</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="center_select" name="center_id" required>
                                         <option value="">Select Center</option>
                                         <?php if(isset($centers) && !empty($centers)): ?>
                                             <?php foreach($centers as $center): ?>
                                                 <option value="<?php echo $center->ID; ?>" <?php echo set_select('center_id', $center->ID); ?>>
                                                     <?php echo htmlspecialchars($center->center_name); ?>
                                                 </option>
                                             <?php endforeach; ?>
                                         <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Return Reason</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="return_reason" value="<?php echo set_value('return_reason'); ?>" placeholder="e.g., Expired, Damaged, Recall, Overstock">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <h4>Items to Return <small>(Batches will load after selecting Vendor and Center)</small></h4>
                            <div id="items_loading" style="display: none; color: #31708f; margin-bottom: 10px;">
                                <i class="fa fa-spinner fa-spin"></i> Loading available batches...
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="return_items_table">
                                    <thead>
                                        <tr>
                                            <th style="width: 35%;">Medicine Batch (from Center)</th>
                                            <th>Expiry</th>
                                            <th>Available</th>
                                            <th>Return Qty *</th>
                                            <th>Unit Cost</th>
                                            <th>Total Cost</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="return_items[0][batch_id]" class="form-control batch_select" required style="width: 100%;">
                                                    <option value="">Select Vendor & Center First</option>
                                                    <?php /* Options populated by JS */ ?>
                                                </select>
                                            </td>
                                            <td><span class="expiry_date">-</span></td>
                                            <td><span class="available_quantity">-</span></td>
                                            <td>
                                                <input type="number" name="return_items[0][quantity_returned]" class="form-control return_quantity" min="1" required disabled>
                                                <input type="hidden" name="return_items[0][unit_price]" class="unit_price_hidden">
                                            </td>
                                            <td><span class="unit_cost">-</span></td>
                                            <td><span class="total_cost">-</span></td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove_row" disabled>
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                         </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-success" id="add_return_item">
                                <i class="fa fa-plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Total Items</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="total_items" id="total_items" value="0" readonly>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Total Quantity</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="total_quantity_display" id="total_quantity" value="0" readonly>
                                    <?php // This field is just for display, model calculates actual total_quantity ?>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Total Value (Cost)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="total_value" id="total_value" value="0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                                <label class="col-sm-2 control-label">Remarks</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="remarks" rows="3" placeholder="Additional remarks or notes"><?php echo set_value('remarks'); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Process Return
                                    </button>
                                    <a href="<?php echo base_url('stocks_new/vendor_returns'); ?>" class="btn btn-default">
                                        <i class="fa fa-arrow-left"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div> </div> </div> </div> <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    let rowCount = 1; // Counter for unique input names/IDs
    let availableBatchesData = []; // Holds currently filtered batches

    // --- Initialize Select2 for static dropdowns ---
    $('#vendor_select').select2({ placeholder: "Select Vendor", allowClear: true });
    $('#center_select').select2({ placeholder: "Select Center", allowClear: true });

    // --- Select2 Initialization Function for dynamic batch selects ---
    function initializeBatchSelect2(selector) {
        $(selector).select2({
            placeholder: "Select Batch",
            allowClear: true,
            width: 'resolve'
        });
    }

    // --- Populate Batch Select Options ---
    function populateBatchSelect(selectElement) {
        var optionsHtml = '<option value="">Select Batch</option>'; // Default empty option
        if (availableBatchesData && availableBatchesData.length > 0) {
            $.each(availableBatchesData, function(index, batch) {
                // Ensure available_quantity is a number and greater than 0
                const availableQty = parseInt(batch.available_quantity);
                if (!isNaN(availableQty) && availableQty > 0) {
                    var displayText = `${batch.medicine_name} - ${batch.batch_number} (Qty: ${availableQty})`;

                    optionsHtml += `<option value="${batch.batch_id}"
                                        data-medicine="${escapeHtml(batch.medicine_name)}"
                                        data-batch-no="${escapeHtml(batch.batch_number)}"
                                        data-expiry="${batch.expiry_date || ''}"
                                        data-available="${availableQty}"
                                        data-cost="${batch.purchase_price}"
                                        data-center-name="${escapeHtml(batch.center_name)}"
                                        data-center-id="${batch.center_id}">
                                        ${escapeHtml(displayText)}
                                   </option>`;
                }
            });
        } else {
             optionsHtml = '<option value="">No batches found for this vendor/center</option>';
        }
        $(selectElement).html(optionsHtml); // Replace options
        initializeBatchSelect2(selectElement); // Initialize Select2
    }

    // --- Fetch Batches via AJAX ---
    function fetchBatches() {
        const vendorId = $('#vendor_select').val();
        const centerId = $('#center_select').val();

        // Clear existing items and reset data if vendor or center is not selected
        if (!vendorId || !centerId) {
            availableBatchesData = [];
            $('#return_items_table tbody').find('tr:gt(0)').remove(); // Remove all but the first row
            const firstSelect = $('select[name="return_items[0][batch_id]"]');
            populateBatchSelect(firstSelect); // Populate first row select with empty/message
            resetRow(firstSelect.closest('tr')); // Reset the first row's display
            updateTotals();
            return; // Stop execution
        }

        $('#items_loading').show(); // Show loading indicator
        $('.batch_select').prop('disabled', true); // Disable selects while loading

        $.ajax({
            url: "<?php echo base_url('stocks_new/get_batches_for_vendor_center'); ?>",
            type: "GET", // Or POST if preferred
            data: {
                vendor_id: vendorId,
                center_id: centerId
            },
            dataType: "json",
            success: function(response) {
                availableBatchesData = response || []; // Update global batch data
                // Re-populate all existing batch dropdowns
                $('.batch_select').each(function() {
                    populateBatchSelect(this);
                    resetRow($(this).closest('tr')); // Reset row display after repopulating
                });
                 // Enable quantity input on the first row if batches loaded
                if (availableBatchesData.length > 0) {
                    $('input[name="return_items[0][quantity_returned]"]').prop('disabled', false);
                } else {
                     $('input[name="return_items[0][quantity_returned]"]').prop('disabled', true);
                }
                updateTotals(); // Recalculate totals
            },
            error: function(xhr, status, error) {
                console.error("Error fetching batches:", status, error);
                alert("Error loading batches for the selected vendor/center. Please try again.");
                availableBatchesData = []; // Clear data on error
                $('.batch_select').each(function() { populateBatchSelect(this); }); // Show error message in selects
            },
            complete: function() {
                $('#items_loading').hide(); // Hide loading indicator
                $('.batch_select').prop('disabled', false); // Re-enable selects
            }
        });
    }

    // --- Event Listener for Vendor and Center Change ---
    $('#vendor_select, #center_select').on('change', function() {
        fetchBatches();
    });

    // --- Escape HTML ---
    function escapeHtml(text) {
        if (typeof text !== 'string') return text;
        var map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // --- Add Item Row ---
    $('#add_return_item').click(function() {
        const vendorId = $('#vendor_select').val();
        const centerId = $('#center_select').val();
        if (!vendorId || !centerId) {
             alert('Please select a Vendor and a Center first.');
             return;
        }

        var newRowHtml = `
            <tr>
                <td>
                    <select name="return_items[${rowCount}][batch_id]" class="form-control batch_select" required style="width: 100%;"></select>
                </td>
                <td><span class="expiry_date">-</span></td>
                <td><span class="available_quantity">-</span></td>
                <td>
                    <input type="number" name="return_items[${rowCount}][quantity_returned]" class="form-control return_quantity" min="1" required disabled>
                     <input type="hidden" name="return_items[${rowCount}][unit_price]" class="unit_price_hidden">
                </td>
                <td><span class="unit_cost">-</span></td>
                <td><span class="total_cost">-</span></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove_row">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        var newRow = $(newRowHtml);
        $('#return_items_table tbody').append(newRow);
        populateBatchSelect(newRow.find('.batch_select')); // Populate and init Select2 with current batches
        rowCount++;
        // Do not update totals here, wait for batch selection
    });

    // --- Remove Item Row ---
    $(document).on('click', '.remove_row', function() {
        $(this).closest('tr').find('.batch_select').select2('destroy');
        $(this).closest('tr').remove();
        updateTotals();
    });

    // --- Batch Selection Change ---
    $(document).on('change', '.batch_select', function() {
        var selectedOption = $(this).find('option:selected');
        var row = $(this).closest('tr');
        var quantityInput = row.find('.return_quantity');

        if (selectedOption.val()) {
            var maxQty = selectedOption.data('available');
            var unitCost = parseFloat(selectedOption.data('cost'));
            row.find('.batch_number').text(selectedOption.data('batch-no')); // Display batch number if needed
            row.find('.expiry_date').text(selectedOption.data('expiry') ? formatDate(selectedOption.data('expiry')) : '-');
            row.find('.available_quantity').text(maxQty);
            row.find('.unit_cost').text('₹' + unitCost.toFixed(2));
            row.find('.unit_price_hidden').val(unitCost); // Store unit cost in hidden input
            quantityInput.attr('max', maxQty).val(1).prop('disabled', false); // Set max, default qty to 1, enable input
            calculateRowTotal(row); // Calculate total for the row
        } else {
            resetRow(row); // Clear row data if nothing selected
        }
        updateTotals(); // Recalculate overall summary
    });

     // --- Return Quantity Input Change ---
    $(document).on('input', '.return_quantity', function() {
         var row = $(this).closest('tr');
         calculateRowTotal(row); // Recalculate row total and overall summary
    });

    // --- Calculate Row Total ---
    function calculateRowTotal(rowElement) {
        var row = $(rowElement);
        var quantityInput = row.find('.return_quantity');
        var quantity = parseInt(quantityInput.val()) || 0;
        var maxAvailable = parseInt(quantityInput.attr('max')) || 0; // Read max from attribute
        var unitCostText = row.find('.unit_cost').text().replace(/[^0-9.-]+/g, '');
        var unitCost = parseFloat(unitCostText) || 0;

        // Validate Quantity
        if (quantity > maxAvailable) {
            alert('Return quantity (' + quantity + ') cannot exceed available quantity (' + maxAvailable + ')!');
            quantityInput.val(maxAvailable);
            quantity = maxAvailable;
        }
        if (quantityInput.val() !== '' && quantity < 1) {
             quantityInput.val(1);
             quantity = 1;
        }

        var totalCost = quantity * unitCost;
        row.find('.total_cost').text('₹' + totalCost.toFixed(2));
        updateTotals(); // Update overall summary
    }

    // --- Reset Row Display ---
     function resetRow(rowElement) {
        var row = $(rowElement);
        row.find('.batch_number, .expiry_date, .available_quantity, .unit_cost, .total_cost').text('-');
        row.find('.return_quantity').removeAttr('max').val('').prop('disabled', true);
        row.find('.unit_price_hidden').val('');
     }

    // --- Update Summary Totals ---
    function updateTotals() {
        let totalItems = 0;
        let totalQuantity = 0;
        let totalValue = 0;

        $('#return_items_table tbody tr').each(function() {
            var row = $(this);
            var batchSelected = row.find('.batch_select').val();
            var qty = parseInt(row.find('.return_quantity').val()) || 0;

            // Count only if a batch is selected AND quantity is > 0
            if (batchSelected && qty > 0) {
                 totalItems++;
                 const value = parseFloat(row.find('.total_cost').text().replace(/[^0-9.-]+/g, '')) || 0;
                 totalQuantity += qty;
                 totalValue += value;
            }
        });

        $('#total_items').val(totalItems);
        $('#total_quantity').val(totalQuantity); // Update quantity display
        $('#total_value').val(totalValue.toFixed(2));
    }

    // --- Format Date Helper ---
    function formatDate(dateString) {
        if (!dateString || dateString === '0000-00-00') return '-';
        try {
            const dateParts = dateString.split('-'); // Assumes YYYY-MM-DD
            if (dateParts.length === 3) {
                 const year = dateParts[0];
                 const month = dateParts[1];
                 const day = dateParts[2];
                 // Basic validation
                 if (parseInt(year) > 1900 && parseInt(month) >= 1 && parseInt(month) <= 12 && parseInt(day) >= 1 && parseInt(day) <= 31) {
                     return `${day}-${month}-${year}`;
                 }
            }
             return dateString; // Return original if parsing fails
        } catch(e) {
            return dateString; // Return original on error
        }
    }

    // --- Initialize Select2 for the first row (will be repopulated on vendor/center change) ---
    initializeBatchSelect2($('select[name="return_items[0][batch_id]"]'));

    // --- Initial Calculation ---
    updateTotals(); // Calculate totals on page load

    // --- Trigger initial batch fetch if vendor/center are pre-selected (e.g., after validation error) ---
     if ($('#vendor_select').val() && $('#center_select').val()) {
         fetchBatches();
     }

});
</script>