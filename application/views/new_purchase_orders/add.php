<?php $all_method =&get_instance(); ?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/select2.min.css">
<script src="<?php echo base_url();?>assets/js/select2.min.js"></script>

<style>
.consumable-select {
    width: 150px !important;
}
</style>
<div class="col-md-12">
    <div class="card" style="margin-bottom:20px;">
      <div class="col-md-12">
          <h3><i class="fa fa-plus-circle"></i> Add New Purchase Order</h3>
      </div>
      <div class="clearfix"></div>
      
      <form method="post" action="<?php echo base_url('new_purchase_orders/save'); ?>" id="add_po_form">
          <div class="card-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="po_number">PO Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="po_number" name="po_number" 
                            value="<?php echo $po_number; ?>" readonly>
                        <small class="text-muted">Auto-generated PO number</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vendor_number">Vendor <span class="text-danger">*</span></label>
                        <select class="form-control" id="vendor_number" name="vendor_number" required>
                            <option value="">-- Select Vendor --</option>
                            <?php if (!empty($vendors)): ?>
                                <?php foreach ($vendors as $vendor): ?>
                                    <option value="<?php echo $vendor['ID']; ?>">
                                        <?php echo $vendor['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ship_to">Ship To <span class="text-danger">*</span></label>
                        <select class="form-control" id="ship_to" name="ship_to" required>
                            <option value="">-- Select Ship To --</option>
                            <?php if (!empty($centers)): ?>
                                <?php foreach ($centers as $center): ?>
                                    <option value="<?php echo $center['center_number']; ?>">
                                        <?php echo $center['center_name']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                            <option value="CENTRAL_WAREHOUSE_NOIDA">Central warehouse Noida</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bill_to">Bill To <span class="text-danger">*</span></label>
                        <select class="form-control" id="bill_to" name="bill_to" required>
                            <option value="">-- Select Bill To --</option>
                            <?php if (!empty($centers)): ?>
                                <?php foreach ($centers as $center): ?>
                                    <option value="<?php echo $center['center_number']; ?>">
                                        <?php echo $center['center_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <option value="CENTRAL_WAREHOUSE_NOIDA">Central warehouse Noida</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="department">Department <span class="text-danger" id="dept_required">*</span></label>
                        <!-- <select name="department" class="form-control"  id="department" required>
                            <option value="">Select Department</option>
                            <?php foreach($departments as $dept): ?>
                                    <option value="<?php echo $dept['department']; ?>" <?php echo set_select('from_department', $dept['department']); ?>>
                                        <?php echo $dept['department']; ?>
                                    </option>
                            <?php endforeach; ?>
                        </select> -->
                        	<select name="department" id="department" class="form-control">
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
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-12">
                    <h4><i class="fa fa-list"></i> Consumables</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="consumables_table">
                            <thead>
                                <tr>
                                    <th width="5%">Sr.</th>
                                    <th width="15%">Item Name</th>
                                    <th width="10%">Quantity (Pack)</th>
                                    <th width="10%">Batch</th>
                                    <!-- <th width="10%">Price</th> -->
                                    <th width="10%">Vendor Price</th>
                                    <th width="10%">Pack Size</th>
                                    <th width="10%">MRP</th>
                                    <th width="10%">Tax %</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="consumables_tbody">
                                <tr class="consumable-row" id="row_1">
                                    <td>1</td>
                                    <td>
                                    <select class="form-control consumable-select" 
                                            name="consumables_name_1" 
                                            id="consumables_name_1" 
                                            onchange="populateItemDetails(1)">
                                        <option value="">-- Select Item --</option>
                                        <?php if (!empty($consumables)): ?>
                                            <?php foreach ($consumables as $consumable): ?>
                                                <?php 
                                                    $itemName   = htmlspecialchars(str_replace('`', "'", $consumable['item_name']), ENT_QUOTES, 'UTF-8');
                                                    $batchNo    = htmlspecialchars(str_replace('`', "'", $consumable['batch_number']), ENT_QUOTES, 'UTF-8');
                                                    $itemNumber = htmlspecialchars($consumable['item_number'], ENT_QUOTES, 'UTF-8');
                                                    $medicine_code = htmlspecialchars($consumable['medicine_code'], ENT_QUOTES, 'UTF-8');
                                                    $price      = htmlspecialchars($consumable['price'], ENT_QUOTES, 'UTF-8');
                                                    $vendorPrice= htmlspecialchars($consumable['vendor_price'], ENT_QUOTES, 'UTF-8');
                                                    $packSize   = htmlspecialchars($consumable['pack_size'], ENT_QUOTES, 'UTF-8');
                                                    $mrp        = htmlspecialchars($consumable['mrp'], ENT_QUOTES, 'UTF-8');
                                                    $tax        = htmlspecialchars($consumable['gstrate'], ENT_QUOTES, 'UTF-8');
                                                    $hsn        = htmlspecialchars($consumable['hsn'], ENT_QUOTES, 'UTF-8');
                                                    $gstDiv     = htmlspecialchars($consumable['gstdivision'], ENT_QUOTES, 'UTF-8');
                                                    $company    = htmlspecialchars($consumable['company'], ENT_QUOTES, 'UTF-8');
                                                    $brand      = htmlspecialchars($consumable['brand_name'], ENT_QUOTES, 'UTF-8');
                                                    $vendorNum  = htmlspecialchars($consumable['vendor_number'], ENT_QUOTES, 'UTF-8');
                                                ?>
                                                <option value="<?= $itemNumber ?>"
                                                    data-item-name="<?= $itemName ?>"
                                                    data-batch="<?= $batchNo ?>"
                                                    data-price="<?= $price ?>"
                                                    data-vendor-price="<?= $vendorPrice ?>"
                                                    data-pack-size="<?= $packSize ?>"
                                                    data-mrp="<?= $mrp ?>"
                                                    data-tax="<?= $tax ?>"
                                                    data-hsn="<?= $hsn ?>"
                                                    data-gst-division="<?= $gstDiv ?>"
                                                    data-company="<?= $company ?>"
                                                    data-brand="<?= $brand ?>"
                                                    data-vendor-number="<?= $vendorNum ?>">
                                                    <?= $itemName ?>(<?= $medicine_code ?>) (<?= $itemNumber ?>) 
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                        <input type="hidden" name="consumables_item_name_1" id="consumables_item_name_1">
                                        <input type="hidden" name="consumables_company_1" id="consumables_company_1">
                                        <input type="hidden" name="consumables_hsn_1" id="consumables_hsn_1">
                                        <input type="hidden" name="consumables_gstdivision_1" id="consumables_gstdivision_1">
                                        <input type="hidden" name="consumables_brand_name_1" id="consumables_brand_name_1">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="consumables_quantity_1" id="consumables_quantity_1" 
                                            value="1" min="1" onchange="updateTotal(1); checkStockLevel(1);">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="consumables_batch_number_1" id="consumables_batch_number_1">
                                    </td>
                                    <!-- <td>
                                        <input type="number" class="form-control" name="consumables_price_1" id="consumables_price_1" 
                                            step="0.01" min="0" onchange="updateTotal(1)">
                                    </td> -->
                                    <td>
                                        <input type="number" class="form-control" name="consumables_vendor_price_1" id="consumables_vendor_price_1" 
                                            step="0.01" min="0" onchange="updateTotal(1)">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="consumables_pack_size_1" id="consumables_pack_size_1" readonly >
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="consumables_mrp_1" id="consumables_mrp_1" 
                                            step="0.01" min="0">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="consumables_gstrate_1" id="consumables_gstrate_1" 
                                            step="0.01" min="0" value="0" onchange="updateTotal(1)">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="removeRow(1)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center">
                        <button type="button" class="btn btn-success" onclick="addNewRow()">
                            <i class="fa fa-plus"></i> Add New Row
                        </button>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 col-md-offset-6">
                    <div class="form-group">
                        <label for="total_amount">Total Amount</label>
                        <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save"></i> Save Purchase Order
                    </button>
                    <a href="<?php echo base_url('new_purchase_orders'); ?>" class="btn btn-default btn-lg">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                </div>
            </div>
          </div>
      </form>
    </div>
</div>

<script>
var rowCounter = 1;
var filteredOptionsHtml = null; // cache for filtered dropdown options

function addNewRow() {
    rowCounter++;
    var newRow = `
        <tr class="consumable-row" id="row_${rowCounter}">
            <td>${rowCounter}</td>
            <td>
                <select class="form-control consumable-select" 
                        name="consumables_name_${rowCounter}" 
                        id="consumables_name_${rowCounter}" 
                        onchange="populateItemDetails(${rowCounter})">
                    <option value="">-- Select Item --</option>
                    <?php if (!empty($consumables)): ?>
                        <?php foreach ($consumables as $consumable): ?>
                            <?php 
                                $itemName   = htmlspecialchars(str_replace('`', "'", $consumable['item_name']), ENT_QUOTES, 'UTF-8');
                                $batchNo    = htmlspecialchars(str_replace('`', "'", $consumable['batch_number']), ENT_QUOTES, 'UTF-8');
                                $itemNumber = htmlspecialchars($consumable['item_number'], ENT_QUOTES, 'UTF-8');
                                $price      = htmlspecialchars($consumable['price'], ENT_QUOTES, 'UTF-8');
                                $vendorPrice= htmlspecialchars($consumable['vendor_price'], ENT_QUOTES, 'UTF-8');
                                $packSize   = htmlspecialchars($consumable['pack_size'], ENT_QUOTES, 'UTF-8');
                                $mrp        = htmlspecialchars($consumable['mrp'], ENT_QUOTES, 'UTF-8');
                                $tax        = htmlspecialchars($consumable['gstrate'], ENT_QUOTES, 'UTF-8');
                                $hsn        = htmlspecialchars($consumable['hsn'], ENT_QUOTES, 'UTF-8');
                                $gstDiv     = htmlspecialchars($consumable['gstdivision'], ENT_QUOTES, 'UTF-8');
                                $company    = htmlspecialchars($consumable['company'], ENT_QUOTES, 'UTF-8');
                                $brand      = htmlspecialchars($consumable['brand_name'], ENT_QUOTES, 'UTF-8');
                            ?>
                            <option value="<?= $itemNumber ?>"
                                data-item-name="<?= $itemName ?>"
                                data-batch="<?= $batchNo ?>"
                                data-price="<?= $price ?>"
                                data-vendor-price="<?= $vendorPrice ?>"
                                data-pack-size="<?= $packSize ?>"
                                data-mrp="<?= $mrp ?>"
                                data-tax="<?= $tax ?>"
                                data-hsn="<?= $hsn ?>"
                                data-gst-division="<?= $gstDiv ?>"
                                data-company="<?= $company ?>"
                                data-brand="<?= $brand ?>">
                                <?= $itemName ?> (<?= $itemNumber ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                 <input type="hidden" name="consumables_item_name_${rowCounter}" id="consumables_item_name_${rowCounter}">
                 <input type="hidden" name="consumables_company_${rowCounter}" id="consumables_company_${rowCounter}">
                 <input type="hidden" name="consumables_hsn_${rowCounter}" id="consumables_hsn_${rowCounter}">
                 <input type="hidden" name="consumables_gstdivision_${rowCounter}" id="consumables_gstdivision_${rowCounter}">
                 <input type="hidden" name="consumables_brand_name_${rowCounter}" id="consumables_brand_name_${rowCounter}">
            </td>
            <td>
                <input type="number" class="form-control" name="consumables_quantity_${rowCounter}" id="consumables_quantity_${rowCounter}" 
                    value="1" min="1" onchange="updateTotal(${rowCounter}); checkStockLevel(${rowCounter});">
            </td>
            <td>
                <input type="text" class="form-control" name="consumables_batch_number_${rowCounter}" id="consumables_batch_number_${rowCounter}" >
            </td>
            <td>
                <input type="number" class="form-control" name="consumables_vendor_price_${rowCounter}" id="consumables_vendor_price_${rowCounter}" 
                    step="0.01" min="0" onchange="updateTotal(${rowCounter})">
            </td>
            <td>
                <input type="text" class="form-control" name="consumables_pack_size_${rowCounter}" id="consumables_pack_size_${rowCounter}" readonly>
            </td>
            <td>
                <input type="number" class="form-control" name="consumables_mrp_${rowCounter}" id="consumables_mrp_${rowCounter}" 
                    step="0.01" min="0">
            </td>
            <td>
                <input type="number" class="form-control" name="consumables_gstrate_${rowCounter}" id="consumables_gstrate_${rowCounter}" 
                    step="0.01" min="0" value="0" onchange="updateTotal(${rowCounter})">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-xs" onclick="removeRow(${rowCounter})">
                    <i class="fa fa-times"></i>
                </button>
            </td>
        </tr>
    `;
    
    $('#consumables_tbody').append(newRow);
    
    // Initialize Select2 for the new row
    $('#consumables_name_' + rowCounter).select2({
        placeholder: 'Search and select item...',
        allowClear: true,
        width: '100%'
    });
    // If we already filtered by vendor, apply filtered options to the new row
    if (filteredOptionsHtml) {
        var $sel = $('#consumables_name_' + rowCounter);
        $sel.html(filteredOptionsHtml);
        $sel.val('');
        $sel.trigger('change');
    }
    
    updateRowNumbers();
}

function removeRow(rowId) {
    if ($('.consumable-row').length > 1) {
        // Destroy Select2 before removing the row
        $('#consumables_name_' + rowId).select2('destroy');
        $('#row_' + rowId).remove();
        updateRowNumbers();
        calculateTotal();
    } else {
        alert('At least one row is required!');
    }
}

function updateRowNumbers() {
    $('.consumable-row').each(function(index) {
        $(this).find('td:first').text(index + 1);
    });
}

function populateItemDetails(rowId) {
    var select = $('#consumables_name_' + rowId);
    var selectedOption = select.find('option:selected');
    
    if (selectedOption.val()) {
        $('#consumables_item_name_' + rowId).val(selectedOption.data('item-name'));
        $('#consumables_batch_number_' + rowId).val(selectedOption.data('batch'));
        $('#consumables_price_' + rowId).val(selectedOption.data('price'));
        $('#consumables_vendor_price_' + rowId).val(selectedOption.data('vendor-price'));
        $('#consumables_pack_size_' + rowId).val(selectedOption.data('pack-size'));
        $('#consumables_mrp_' + rowId).val(selectedOption.data('mrp'));
        $('#consumables_gstrate_' + rowId).val(selectedOption.data('tax'));
        $('#consumables_hsn_' + rowId).val(selectedOption.data('hsn'));
        $('#consumables_gstdivision_' + rowId).val(selectedOption.data('gst-division'));
        $('#consumables_company_' + rowId).val(selectedOption.data('company'));
        $('#consumables_brand_name_' + rowId).val(selectedOption.data('brand'));
        
        // Check stock level when item is selected
        checkStockLevel(rowId);
        
        updateTotal(rowId);
    }
}

// Check stock level for a specific row
function checkStockLevel(rowId) {
    var medicineId = $('#consumables_name_' + rowId).val();
    var quantity = parseInt($('#consumables_quantity_' + rowId).val()) || 0;
    var shipToCenterId = $('#ship_to').val();
    var department = $('#department').val();

    if (!medicineId || quantity <= 0) {
        // Clear any previous error messages
        $('#row_' + rowId).removeClass('has-error');
        $('#stock_error_' + rowId).remove();
        $('#stock_info_' + rowId).remove();
        return;
    }

    $.getJSON('<?php echo base_url('new_purchase_orders/check_stock_level'); ?>', {
        medicine_id: medicineId,
        quantity: quantity,
        ship_to_center_id: shipToCenterId,
        department: department
    })
    .done(function(response) {
        if (response.status === 'success') {
            // Remove previous error/info messages
            $('#row_' + rowId).removeClass('has-error');
            $('#stock_error_' + rowId).remove();
            $('#stock_info_' + rowId).remove();
            
            if (!response.can_order) {
                // Show error message
                $('#row_' + rowId).addClass('has-error');
                var errorMsg = '<small id="stock_error_' + rowId + '" class="text-danger" style="display: block; margin-top: 5px;">' +
                    '<i class="fa fa-exclamation-triangle"></i> ' + response.message +
                    '</small>';
                $('#consumables_quantity_' + rowId).closest('td').append(errorMsg);
            } else if (response.max_stock > 0) {
                // Show info message if max stock is set
                var infoMsg = '<small id="stock_info_' + rowId + '" class="text-muted" style="display: block; margin-top: 5px;">' +
                    'Stock: ' + response.current_stock + ' / Max: ' + response.max_stock +
                    '</small>';
                $('#consumables_quantity_' + rowId).closest('td').append(infoMsg);
            }
        }
    })
    .fail(function() {
        console.error('Error checking stock level');
    });
}

function updateTotal(rowId) {
    // This function is just a trigger. The real work is in calculateTotal().
    // We could calculate the row total here, but calculateTotal() already loops through all rows.
    // So, just call the main function.
    calculateTotal();
}

function calculateTotal() {
    var total = 0;
    $('.consumable-row').each(function() {
        var quantity = parseFloat($(this).find('input[id*="consumables_quantity_"]').val()) || 0;
        var vendorPrice = parseFloat($(this).find('input[id*="consumables_vendor_price_"]').val()) || 0;
        var tax = parseFloat($(this).find('input[id*="consumables_gstrate_"]').val()) || 0;
        
        total += (quantity * vendorPrice) * (1 + tax / 100);
    });
    
    $('#total_amount').val(total.toFixed(2));
}

$(document).ready(function() {
    // Initialize Select2 for consumable dropdowns
    $('.consumable-select').select2({
        placeholder: 'Search and select item...',
        allowClear: true,
        width: '100%'
    });

    // Handle Ship To change - show/hide department field for Central Warehouse Noida
    $('#ship_to').on('change', function() {
        var shipTo = $(this).val();
        if (shipTo === 'CENTRAL_WAREHOUSE_NOIDA') {
            // Hide and make department not required
            $('#department').closest('.form-group').hide();
            $('#department').removeAttr('required');
            $('#dept_required').hide();
        } else {
            // Show and make department required
            $('#department').closest('.form-group').show();
            $('#department').attr('required', 'required');
            $('#dept_required').show();
        }
    });

    // Trigger on page load if value is already selected
    if ($('#ship_to').val() === 'CENTRAL_WAREHOUSE_NOIDA') {
        $('#department').closest('.form-group').hide();
        $('#department').removeAttr('required');
        $('#dept_required').hide();
    }

    // Filter items by selected vendor on add page
    $('#vendor_number').on('change', function() {
        var vendorNumber = $(this).val();
        if (!vendorNumber) { return; }
        // For each row dropdown, reload options via AJAX
        $.getJSON('<?php echo base_url('new_purchase_orders/items_by_vendor'); ?>', { vendor_number: vendorNumber })
            .done(function(resp) {
                if (resp.status === 'success') {
                    var optionsHtml = '<option value="">-- Select Item --</option>';
                    resp.data.forEach(function(item) {
                        var safe = function(v){ return $('<div>').text(v || '').html(); };
                        optionsHtml += '<option value="'+ safe(item.item_number) +'"'
                            + ' data-item-name="'+ safe(item.item_name) +'"'
                            + ' data-batch="'+ safe(item.batch_number) +'"'
                            + ' data-price="'+ safe(item.price) +'"'
                            + ' data-vendor-price="'+ safe(item.vendor_price) +'"'
                            + ' data-pack-size="'+ safe(item.pack_size) +'"'
                            + ' data-mrp="'+ safe(item.mrp) +'"'
                            + ' data-tax="'+ safe(item.gstrate) +'"'
                            + ' data-hsn="'+ safe(item.hsn) +'"'
                            + ' data-gst-division="'+ safe(item.gstdivision) +'"'
                            + ' data-company="'+ safe(item.company) +'"'
                            + ' data-brand="'+ safe(item.brand_name) +'"'
                            + ' data-vendor-number="'+ safe(item.vendor_number) +'"'
                            + ' data-medicine-code="'+ safe(item.medicine_code) +'"'
                            + '>' + safe(item.item_name) + ' ('+ safe(item.medicine_code) + ' - ' + safe(item.item_number) +')</option>';
                    });
                    // cache for newly added rows
                    filteredOptionsHtml = optionsHtml;
                    $('.consumable-select').each(function(){
                        var $sel = $(this);
                        var currentVal = $sel.val();
                        $sel.html(optionsHtml);
                        $sel.val('');
                        $sel.trigger('change');
                    });
                }
            });
    });

    // Auto-select vendor from URL param and auto-filter if present
    function getQueryParam(name) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
        var results = regex.exec(window.location.href);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
    var urlVendor = getQueryParam('vendor_number');
    if (urlVendor) {
        $('#vendor_number').val(urlVendor);
    }
    var initialVendor = $('#vendor_number').val();
    if (initialVendor) {
        $('#vendor_number').trigger('change');
    }
    
    // Initialize form validation
    $('#add_po_form').on('submit', function(e) {
        var isValid = true;
        var stockErrors = [];
        
        // Check department requirement based on ship_to
        var shipTo = $('#ship_to').val();
        if (shipTo !== 'CENTRAL_WAREHOUSE_NOIDA' && !$('#department').val()) {
            alert('Please select a department.');
            isValid = false;
        }
        
        // Check if at least one item is selected
        var hasItems = false;
        $('.consumable-select').each(function() {
            if ($(this).val()) {
                hasItems = true;
                return false;
            }
        });
        
        if (!hasItems) {
            alert('Please add at least one item to the purchase order.');
            isValid = false;
        }
        
        // Check stock levels for all items synchronously
        var checkPromises = [];
        var rowIds = [];
        var shipToCenterId = $('#ship_to').val();
        var department = $('#department').val();
        $('.consumable-row').each(function() {
            var rowId = $(this).attr('id').replace('row_', '');
            var medicineId = $('#consumables_name_' + rowId).val();
            var quantity = parseInt($('#consumables_quantity_' + rowId).val()) || 0;

            if (medicineId && quantity > 0) {
                rowIds.push(rowId);
                var checkPromise = $.getJSON('<?php echo base_url('new_purchase_orders/check_stock_level'); ?>', {
                    medicine_id: medicineId,
                    quantity: quantity,
                    ship_to_center_id: shipToCenterId,
                    department: department
                }).then(function(response) {
                    if (response.status === 'success' && !response.can_order) {
                        var itemName = $('#consumables_item_name_' + rowId).val() || 'Item';
                        stockErrors.push(itemName + ': ' + response.message);
                        return false;
                    }
                    return true;
                });
                checkPromises.push(checkPromise);
            }
        });
        
        // Wait for all stock checks to complete
        if (checkPromises.length > 0) {
            e.preventDefault();
            var form = this;
            $.when.apply($, checkPromises).done(function() {
                var allValid = true;
                var results = arguments.length === 1 ? [arguments[0]] : Array.prototype.slice.call(arguments);
                results.forEach(function(result) {
                    if (result === false) {
                        allValid = false;
                    }
                });
                
                if (!allValid || stockErrors.length > 0) {
                    alert('Cannot create purchase order. Max stock level exceeded:\n\n' + stockErrors.join('\n'));
                    isValid = false;
                }
                
                if (isValid) {
                    // Re-submit the form
                    form.submit();
                }
            });
            return false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Initialize total calculation
    calculateTotal();
});
</script>