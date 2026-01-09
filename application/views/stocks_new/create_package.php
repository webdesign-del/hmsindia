<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css" rel="stylesheet" />

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-plus"></i> Create Medicine Package
            <small>Create a new medicine box/package</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-cube"></i> Package Information
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url('stocks_new/create_package'); ?>" method="post" class="form-horizontal">
                    <input type="hidden" name="action" value="create_package">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Package Code *</label>
                                <div class="col-sm-8">
                                    <input type="text" name="package_code" class="form-control" placeholder="Enter package code" required>
                                    <small class="help-block">Unique code for the package (e.g., PKG001)</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Package Name *</label>
                                <div class="col-sm-8">
                                    <input type="text" name="package_name" class="form-control" placeholder="Enter package name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Description</label>
                                <div class="col-sm-8">
                                    <textarea name="description" class="form-control" rows="3" placeholder="Package description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Selling Price</label>
                                <div class="col-sm-8">
                                    <input type="number" name="selling_price" id="selling_price" class="form-control" placeholder="0.00" step="0.01" min="0" readonly>
                                    <small class="help-block">Auto-calculated from medicine prices</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">MRP</label>
                                <div class="col-sm-8">
                                    <input type="number" name="mrp" id="mrp" class="form-control" placeholder="0.00" step="0.01" min="0" readonly>
                                    <small class="help-block">Auto-calculated from medicine MRPs</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">GST Rate (%)</label>
                                <div class="col-sm-8">
                                    <input type="number" name="gst_rate" class="form-control" placeholder="0.00" step="0.01" min="0" max="100">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h4>Package Items</h4>

                    <!-- Medicine Selection -->
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-8">
                            <select id="medicine_select" class="form-control">
                                <option value="">Select medicine to add...</option>
                                <?php foreach($medicines as $medicine): ?>
                                    <?php
                                    $selling_price = isset($medicine->selling_price) ? floatval($medicine->selling_price) : 0;
                                    $mrp = isset($medicine->mrp) ? floatval($medicine->mrp) : 0;
                                    $medicine_name = isset($medicine->medicine_name) ? trim($medicine->medicine_name) : '';
                                    $medicine_code = isset($medicine->medicine_code) ? trim($medicine->medicine_code) : '';
                                    $brand_name = isset($medicine->brand_name) ? trim($medicine->brand_name) : '';
                                    ?>
                                    <option value="<?php echo $medicine->id; ?>"
                                            data-name="<?php echo htmlspecialchars($medicine_name); ?>"
                                            data-code="<?php echo htmlspecialchars($medicine_code); ?>"
                                            data-price="<?php echo $selling_price; ?>"
                                            data-brand="<?php echo htmlspecialchars($brand_name); ?>">
                                        <?php echo htmlspecialchars($medicine_name . ' (' . $medicine_code . ') - ₹' . number_format($selling_price, 2)); ?>
                                        <?php if($brand_name): ?>
                                            (<?php echo htmlspecialchars($brand_name); ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="add_quantity" class="form-control" placeholder="Qty" min="1" value="1">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="add_medicine" class="btn btn-success btn-block">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </div>
                    </div>

                    <!-- Package Items List -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-list"></i> Package Contents
                        </div>
                        <div class="panel-body">
                            <div id="package-items">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> No medicines added yet. Use the dropdown above to add medicines to this package.
                                </div>
                            </div>
                        </div>
                    </div>


                    <hr>

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Create Package
                            </button>
                            <a href="<?php echo base_url('stocks_new/packages'); ?>" class="btn btn-default">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Store medicine data for quick lookup
var medicineData = {};

<?php foreach($medicines as $medicine): ?>
<?php
$selling_price = isset($medicine->selling_price) ? floatval($medicine->selling_price) : 0;
$mrp = isset($medicine->mrp) ? floatval($medicine->mrp) : 0;
$medicine_name = isset($medicine->medicine_name) ? trim($medicine->medicine_name) : '';
$medicine_code = isset($medicine->medicine_code) ? trim($medicine->medicine_code) : '';
$brand_name = isset($medicine->brand_name) ? trim($medicine->brand_name) : '';
?>
medicineData[<?php echo $medicine->id; ?>] = {
    name: '<?php echo addslashes($medicine_name); ?>',
    code: '<?php echo addslashes($medicine_code); ?>',
    price: <?php echo $selling_price; ?>,
    mrp: <?php echo $mrp; ?>,
    brand: '<?php echo addslashes($brand_name); ?>'
};
<?php endforeach; ?>

$(document).ready(function() {
    // Function to calculate package prices
    function calculatePackagePrices() {
        var totalSellingPrice = 0;
        var totalMRP = 0;

        $('.package-item').each(function() {
            var medicineId = $(this).data('medicine-id');
            var quantity = parseFloat($(this).find('.quantity-input').val()) || 0;

            if (medicineId && medicineData[medicineId]) {
                totalSellingPrice += medicineData[medicineId].price * quantity;
                totalMRP += medicineData[medicineId].mrp * quantity;
            }
        });

        $('#selling_price').val(totalSellingPrice.toFixed(2));
        $('#mrp').val(totalMRP.toFixed(2));
    }

    // Function to add medicine to package
    function addMedicineToPackage(medicineId, quantity = 1) {
        var medicine = medicineData[medicineId];
        if (!medicine) return;

        // Check if medicine already exists in package
        var exists = false;
        $('.package-item').each(function() {
            if ($(this).data('medicine-id') == medicineId) {
                exists = true;
                return false;
            }
        });

        if (exists) {
            alert('This medicine is already in the package. You can modify the quantity instead.');
            $('#medicine_select').val('');
            $('#add_quantity').val('1');
            return;
        }

        // Remove empty state message
        if ($('#package-items .alert').length > 0) {
            $('#package-items').empty();
        }

        var itemHtml = '<div class="package-item" style="border: 1px solid #ddd; border-radius: 4px; padding: 10px; margin-bottom: 10px; background-color: #f9f9f9;" data-medicine-id="' + medicineId + '">' +
            '<div class="row">' +
                '<div class="col-md-1">' +
                    '<button type="button" class="btn btn-danger btn-xs remove-item" style="margin-top: 5px;">' +
                        '<i class="fa fa-times"></i>' +
                    '</button>' +
                '</div>' +
                '<div class="col-md-5">' +
                    '<strong>' + medicine.name + '</strong><br>' +
                    '<small class="text-muted">Code: ' + medicine.code +
                    (medicine.brand ? ' | Brand: ' + medicine.brand : '') + '</small>' +
                    '<input type="hidden" name="medicine_ids[]" value="' + medicineId + '">' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<div class="input-group input-group-sm">' +
                        '<span class="input-group-addon">Qty</span>' +
                        '<input type="number" name="quantities[]" class="form-control quantity-input" min="1" value="' + quantity + '" required>' +
                    '</div>' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<span class="text-muted">₹<span class="unit-price">' + medicine.price + '</span></span><br>' +
                    '<small class="text-muted">per unit</small>' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<strong>₹<span class="line-total">' + (medicine.price * quantity) + '</span></strong><br>' +
                    '<small class="text-muted">subtotal</small>' +
                '</div>' +
            '</div>' +
        '</div>';

        $('#package-items').append(itemHtml);
        calculatePackagePrices();

        // Clear selection
        $('#medicine_select').val('');
        $('#add_quantity').val('1');
    }

    // Initialize Select2 for searchable dropdown
    $('#medicine_select').select2({
        placeholder: 'Search and select medicine...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap',
        matcher: function(params, data) {
            try {
                // Custom matcher for better search
                if ($.trim(params.term) === '') {
                    return data;
                }

                if (!data || !data.element) {
                    return null;
                }

                var term = (params.term || '').toString().toLowerCase();

                // Safely get data attributes with fallbacks
                var name = '';
                var code = '';
                var brand = '';

                try {
                    name = ($(data.element).data('name') || '').toString().toLowerCase();
                } catch(e) {
                    name = '';
                }

                try {
                    code = ($(data.element).data('code') || '').toString().toLowerCase();
                } catch(e) {
                    code = '';
                }

                try {
                    brand = ($(data.element).data('brand') || '').toString().toLowerCase();
                } catch(e) {
                    brand = '';
                }

                var text = name + ' ' + code + ' ' + brand;

                if (text.indexOf(term) > -1) {
                    return data;
                }

                return null;
            } catch(e) {
                console.error('Select2 matcher error:', e);
                return null;
            }
        }
    });

    // Add medicine button click
    $('#add_medicine').on('click', function() {
        var medicineId = $('#medicine_select').val();
        var quantity = parseInt($('#add_quantity').val()) || 1;

        if (!medicineId) {
            alert('Please select a medicine first.');
            return;
        }

        if (quantity < 1) {
            alert('Quantity must be at least 1.');
            return;
        }

        addMedicineToPackage(medicineId, quantity);
    });

    // Allow Enter key in quantity field to add medicine
    $('#add_quantity').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#add_medicine').click();
        }
    });

    // Remove medicine item
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.package-item').remove();
        calculatePackagePrices();

        // Show empty message if no items left
        if ($('.package-item').length === 0) {
            $('#package-items').html('<div class="alert alert-info"><i class="fa fa-info-circle"></i> No medicines added yet. Use the dropdown above to add medicines to this package.</div>');
        }
    });

    // Update line total when quantity changes
    $(document).on('input change', '.quantity-input', function() {
        var $item = $(this).closest('.package-item');
        var medicineId = $item.data('medicine-id');
        var quantity = parseFloat($(this).val()) || 0;
        var unitPrice = parseFloat($item.find('.unit-price').text()) || 0;

        $item.find('.line-total').text((unitPrice * quantity).toFixed(2));
        calculatePackagePrices();
    });
});
</script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
