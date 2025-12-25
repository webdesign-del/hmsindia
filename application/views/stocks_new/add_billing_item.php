<?php
// This is a placeholder for your instance variable, as used in your old file.
$all_method =& get_instance(); 

// These are placeholders for the data you are passing from your controller.
// Make sure your controller loads $medicine, $injections, and $consumables.
$medicine = $medicine ?? [];
$injections = $injections ?? [];
$consumables = $consumables ?? [];
?>

<!-- Select2 CSS and JS -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/select2.min.css">
<!-- jQuery (Must be loaded before Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo base_url();?>assets/js/select2.min.js"></script>


<!-- Old Theme Styles -->
<style type="text/css">
    form{
        margin: 20px 0;
    }
    form input, button{
        padding: 5px;
    }
    table{
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }
    table, th, td{
        border: 1px solid #cdcdcd;
    }
    table th, table td{
        padding: 10px;
        text-align: left;
    }
    .heading{margin-bottom:10px;margin-top: 0; padding-top:0px;}
    
    /* Added from your new styles for better form layout */
    .form-group label {
        font-weight: 600;
        color: #2d3748;
    }
    .form-control[readonly] {
        background-color: #eee;
    }
    .is-invalid {
        border-color: #f45c43 !important;
    }
    
    /* Re-style Select2 to fit the old theme */
    .select2-container .select2-selection--single {
        height: 34px !important; /* Default BS3 height */
        padding: 6px 12px !important;
        border: 1px solid #ccc !important;
        border-radius: 4px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 32px !important;
    }
    .select2-dropdown {
        border: 1px solid #ccc !important;
    }
    
    /* Fix for checkbox alignment */
    .active-status {
        width: 20px;
        height: 20px;
        position: relative;
        left: 0;
        opacity: 1;
    }
</style>

<!-- We will use your old HTML structure with panels -->
<div class="col-md-12">
    <div class="panel panel-default" style="margin-bottom:20px;">
        <div class="panel-heading">
            <h3 class="heading"><i class="fa fa-file-medical"></i> Add Patient Billing Items</h3>
        </div>
        <div class="panel-body">
            
            <!-- Flash Messages -->
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fa fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fa fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <form class="billing-form" id="add_billing_form" method="post" action="<?php echo base_url('stocks_new/add_billing_item'); ?>">
                <input type="hidden" name="action" value="add_billing_item" />

                <!-- Patient Details Section -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="patient_id"><i class="fa fa-id-card"></i> IIC ID (Required)</label>
                            <input value="" placeholder="Enter IIC ID" id="patient_id" name="patient_id" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="patient_name"><i class="fa fa-user"></i> Patient Name</label>
                            <input value="" placeholder="Patient Name" readonly id="patient_name" name="patient_name" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="procedure_name"><i class="fa fa-procedures"></i> Procedure Name (Required)</label>
                            <select name="procedure_name" class="form-control" id="procedure_name" required style="width: 100%;">
                                <option value="">Select Procedure</option>
                                <?php 
                                $sql1 = "Select * from ".$this->config->item('db_prefix')."procedures where status='1'"; 
                                $query = $this->db->query($sql1);
                                $select_result1 = $query->result(); 
                                foreach ($select_result1 as $res_val){
                                ?>
                                <option value="<?php echo $res_val->procedure_name; ?> - <?php echo $res_val->code; ?>"><?php echo $res_val->procedure_name; ?> - <?php echo $res_val->code; ?></option>
                                <?php } ?>
                                <!-- Hardcoded options from your old file -->
                                <option value="Embryo Transfer">Embryo Transfer</option>
                                <option value="Embryo Transfer Under GA">Embryo Transfer Under GA</option>
                                <option value="IUI">IUI</option>
                                <option value="tesa">Tesa</option>
                                <option value="OPU">Opu</option>
                                <option value="Ivf">Ivf</option>
                                <option value="FROZEN THAW OOCYTE ICSI">FROZEN THAW OOCYTE ICSI (FTOI)</option>
                                <option value="Embryo Biopsy">Embryo Biopsy</option>
                                <option value="Egg Freezing">Egg Thawing</option>
                                <option value="Blastocyst Culture">Blastocyst Culture</option>
                                <option value="DFI">DFI</option>
                                <option value="Candore">Candore</option>
                                <option value="Sperm Mobile">Sperm Mobile</option>
                                <option value="Oocyte Activation AOA">Oocyte Activation AOA</option>
                                <option value="MICRO TESA">MICRO TESA</option>
                                <option value="DEPARTMENTAL">DEPARTMENTAL</option>
                            </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" value="0" id="receipt_number" name="receipt_number">
                <input type="hidden" class="form-control" value="<?php echo isset($_SESSION['logged_stock_manager']['employee_number']) ? $_SESSION['logged_stock_manager']['employee_number'] : ($_SESSION['logged_billing_manager']['employee_number'] ?? 'EMP123'); ?>" id="employee_number" name="employee_number">
                <input type="hidden" class="form-control" value="<?php echo isset($_SESSION['logged_stock_manager']['center']) ? $_SESSION['logged_stock_manager']['center'] : ($_SESSION['logged_billing_manager']['center'] ?? 'CENTER01'); ?>" id="center_number" name="center_number">

                <!-- Embryology/Medicine Section -->
                <section class="col-sm-12 col-xs-12 medicine_section">
                    <h4 class="heading">Patient Embrology</h4>
                    <div class="clearfix"></div>
                    <input type="button" class="add-medicine-row btn btn-success" value="Add Medicine">
                    <input type="button" class="delete-medicine-row btn btn-danger pull-right" value="Delete Selected">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th width="15%">Serial Number</th>
                                    <th width="25%">Medicine</th>
                                    <th width="10%">Quantity</th>
                                    <th width="15%">Batch Number</th>
                                    <th width="10%">Stock</th>
                                    <th width="10%">Price (₹)</th>
                                    <th width="10%">Total (₹)</th>
                                </tr>
                            </thead>
                            <tbody id="medicine_table_body">
                                <tr class="medicine_row_1" data-index="1">
                                    <td><input type="checkbox" class="active-status" data-type="medicine" data-index="1" style="position: relative;left: 0px !important;opacity: 1 !important;"></td>
                                    <td><input value="" readonly id="medicine_serial_1" class="form-control" name="medicine_serial_1" type="text"></td>
                                    <td>
                                        <select disabled name="medicine_name_1" class="form-control medicine-select" id="medicine_name_1" data-index="1" style="width: 100%;">
                                            <option value="">Select Medicine</option>
                                            <?php foreach($medicine as $key => $val){ ?>
                                                <option value="<?php echo $val['item_number']; ?>"
                                                        data-id="<?php echo $val['ID']; ?>"
                                                        data-medicine_id="<?php echo $val['medicine_id']; ?>"
                                                        data-batch="<?php echo $val['batch_number']; ?>"
                                                        data-quantity="<?php echo $val['quantity']; ?>"
                                                        data-price="<?php echo $val['price']; ?>"
                                                        data-name="<?php echo $val['item_name']; ?>"
                                                        data-gst="<?php echo $val['gstrate']; ?>">
                                                    <?php echo $val['item_name']; ?> (Exp: <?php echo $val['expiry']; ?>)
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input disabled value="" id="medicine_quantity_1" class="form-control medicine-quantity" name="medicine_quantity_1" type="number" min="0" data-index="1">
                                        <input type="hidden" id="medicine_ID_1" name="medicine_ID_1">
                                        <input type="hidden" id="medicine_medicine_id_1" name="medicine_medicine_id_1">
                                        <input type="hidden" id="medicine_batch_1" name="medicine_batch_1">
                                        <input type="hidden" id="medicine_price_1" name="medicine_price_1">
                                        <input type="hidden" id="medicine_gst_1" name="medicine_gst_1">
                                    </td>
                                    <td><input value="" readonly id="medicine_batch_number_1" class="form-control" name="medicine_batch_number_1" type="text"></td>
                                    <td><input value="" readonly id="medicine_stock_1" class="form-control" name="medicine_stock_1" type="text"></td>
                                    <td><input value="" readonly id="medicine_price_display_1" class="form-control" type="text"></td>
                                    <td><input value="" readonly id="medicine_total_1" class="form-control" name="medicine_total_1" type="text"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Injections Section -->
                <section class="col-sm-12 col-xs-12 injections_section">
                    <h4 class="heading">Patient Injections</h4>
                    <div class="clearfix"></div>
                    <input type="button" class="add-injections-row btn btn-success" value="Add Injection">
                    <input type="button" class="delete-injections-row btn btn-danger pull-right" value="Delete Selected">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th width="15%">Serial Number</th>
                                    <th width="25%">Injection</th>
                                    <th width="10%">Quantity</th>
                                    <th width="15%">Batch Number</th>
                                    <th width="10%">Stock</th>
                                    <th width="10%">Price (₹)</th>
                                    <th width="10%">Total (₹)</th>
                                </tr>
                            </thead>
                            <tbody id="injections_table_body">
                                <tr class="injections_row_1" data-index="1">
                                    <td><input type="checkbox" class="active-status" data-type="injections" data-index="1" style="position: relative;left: 0px !important;opacity: 1 !important;"></td>
                                    <td><input value="" readonly id="injections_serial_1" class="form-control" name="injections_serial_1" type="text"></td>
                                    <td>
                                        <select disabled name="injections_name_1" class="form-control injections-select" id="injections_name_1" data-index="1" style="width: 100%;">
                                            <option value="">Select Injection</option>
                                            <?php foreach($injections as $key => $val){ ?>
                                                <option value="<?php echo $val['item_number']; ?>"
                                                        data-id="<?php echo $val['ID']; ?>"
                                                        data-medicine_id="<?php echo $val['medicine_id']; ?>"
                                                        data-batch="<?php echo $val['batch_number']; ?>"
                                                        data-quantity="<?php echo $val['quantity']; ?>"
                                                        data-price="<?php echo $val['price']; ?>"
                                                        data-name="<?php echo $val['item_name']; ?>"
                                                        data-gst="<?php echo $val['gstrate']; ?>">
                                                    <?php echo $val['item_name']; ?> (Exp: <?php echo $val['expiry']; ?>)
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input disabled value="" id="injections_quantity_1" class="form-control injections-quantity" name="injections_quantity_1" type="number" min="0" data-index="1">
                                        <input type="hidden" id="injections_ID_1" name="injections_ID_1">
                                        <input type="hidden" id="injections_medicine_id_1" name="injections_medicine_id_1">
                                        <input type="hidden" id="injections_batch_1" name="injections_batch_1">
                                        <input type="hidden" id="injections_price_1" name="injections_price_1">
                                        <input type="hidden" id="injections_gst_1" name="injections_gst_1">
                                    </td>
                                    <td><input value="" readonly id="injections_batch_number_1" class="form-control" name="injections_batch_number_1" type="text"></td>
                                    <td><input value="" readonly id="injections_stock_1" class="form-control" name="injections_stock_1" type="text"></td>
                                    <td><input value="" readonly id="injections_price_display_1" class="form-control" type="text"></td>
                                    <td><input value="" readonly id="injections_total_1" class="form-control" name="injections_total_1" type="text"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
                
                <!-- Consumables Section -->
                <section class="col-sm-12 col-xs-12 consumables_section">
                    <h4 class="heading">OT Consumables</h4>
                    <div class="clearfix"></div>
                    <input type="button" class="add-consumables-row btn btn-success" value="Add Consumable">
                    <input type="button" class="delete-consumables-row btn btn-danger pull-right" value="Delete Selected">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th width="15%">Serial Number</th>
                                    <th width="25%">Consumable</th>
                                    <th width="10%">Quantity</th>
                                    <th width="15%">Batch Number</th>
                                    <th width="10%">Stock</th>
                                    <th width="10%">Price (₹)</th>
                                    <th width="10%">Total (₹)</th>
                                </tr>
                            </thead>
                            <tbody id="consumables_table_body">
                                <tr class="consumables_row_1" data-index="1">
                                    <td><input type="checkbox" class="active-status" data-type="consumables" data-index="1" style="position: relative;left: 0px !important;opacity: 1 !important;"></td>
                                    <td><input value="" readonly id="consumables_serial_1" class="form-control" name="consumables_serial_1" type="text"></td>
                                    <td>
                                        <select disabled name="consumables_name_1" class="form-control consumables-select" id="consumables_name_1" data-index="1" style="width: 100%;">
                                            <option value="">Select Consumable</option>
                                            <?php foreach($consumables as $key => $val){ ?>
                                                <option value="<?php echo $val['item_number']; ?>"
                                                        data-id="<?php echo $val['ID']; ?>"
                                                        data-medicine_id="<?php echo $val['medicine_id']; ?>"
                                                        data-batch="<?php echo $val['batch_number']; ?>"
                                                        data-quantity="<?php echo $val['quantity']; ?>"
                                                        data-price="<?php echo $val['price']; ?>"
                                                        data-name="<?php echo $val['item_name']; ?>"
                                                        data-gst="<?php echo $val['gstrate']; ?>">
                                                    <?php echo $val['item_name']; ?> (Exp: <?php echo $val['expiry']; ?>)
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input disabled value="" id="consumables_quantity_1" class="form-control consumables-quantity" name="consumables_quantity_1" type="number" min="0" data-index="1">
                                        <input type="hidden" id="consumables_ID_1" name="consumables_ID_1">
                                        <input type="hidden" id="consumables_medicine_id_1" name="consumables_medicine_id_1">
                                        <input type="hidden" id="consumables_batch_1" name="consumables_batch_1">
                                        <input type="hidden" id="consumables_price_1" name="consumables_price_1">
                                        <input type="hidden" id="consumables_gst_1" name="consumables_gst_1">
                                    </td>
                                    <td><input value="" readonly id="consumables_batch_number_1" class="form-control" name="consumables_batch_number_1" type="text"></td>
                                    <td><input value="" readonly id="consumables_stock_1" class="form-control" name="consumables_stock_1" type="text"></td>
                                    <td><input value="" readonly id="consumables_price_display_1" class="form-control" type="text"></td>
                                    <td><input value="" readonly id="consumables_total_1" class="form-control" name="consumables_total_1" type="text"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Submit Button -->
                <div class="col-sm-12 col-xs-12">
                    <hr/>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="create_billing"><i class="fa fa-check-circle"></i> Submit Billing Items</button>
                        <a href="<?php echo base_url('stocks_new/dashboard'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 
================================================================================
MODERN JAVASCRIPT
All inline 'onchange', 'onclick', 'onkeyup' has been removed and replaced below.
================================================================================
-->
<script>
$(document).ready(function() {
    // --- Counters ---
    let medicineCounter = 1;
    let injectionsCounter = 1;
    let consumablesCounter = 1;
    
    // --- Initialize All Select2 ---
    $('#procedure_name').select2({ placeholder: "Select Procedure", width: '100%' });
    $('.medicine-select').select2({ placeholder: "Select Medicine", width: '100%' });
    $('.injections-select').select2({ placeholder: "Select Injection", width: '100%' });
    $('.consumables-select').select2({ placeholder: "Select Consumable", width: '100%' });
    
    // --- Patient ID Lookup ---
    $('#patient_id').on('blur', function() {
        const patientId = $(this).val();
        if (patientId) {
            $('#patient_name').val('Loading...').removeClass('is-invalid');
            
            $.ajax({
                url: '<?php echo base_url("patients/patient_detail_name2"); ?>',
                type: 'POST',
                data: { patient_id: patientId },
                dataType: 'json', 
                success: function(response) {
                    let patientName = (typeof response === 'object' && response.name) ? response.name : response;
                    $('#patient_name').val(patientName);
                },
                error: function() {
                    $('#patient_name').val('Patient not found');
                    $('#patient_name').addClass('is-invalid');
                }
            });
        }
    });

    // --- Row Activation ---
    $(document).on('change', '.active-status', function() {
        const index = $(this).data('index');
        const type = $(this).data('type');
        const isChecked = $(this).is(':checked');
        const row = $(this).closest('tr');

        const $select = $('#' + type + '_name_' + index);
        const $quantity = $('#' + type + '_quantity_' + index);

        if (isChecked) {
            $select.prop('disabled', false).select2({ placeholder: "Select Item", width: '100%' });
            $quantity.prop('disabled', false);
            row.css('background-color', '#f0fff4');
        } else {
            $select.prop('disabled', true).select2('destroy');
            $quantity.prop('disabled', true);
            row.css('background-color', '');
            // Clear all values in the row
            row.find('input[type="text"], input[type="number"], input[type="hidden"], select').not('.active-status').val('');
            // Reset select to its placeholder
            $select.val('').trigger('change');
        }
    });

    // --- Shared Function for Item Selection ---
    function handleItemSelect(event) {
        const $select = $(event.currentTarget);
        const index = $select.data('index');
        const type = $select.attr('id').split('_')[0]; 
        const selected = $select.find('option:selected');
        const data = selected.data();

        if (!$select.val()) {
            // Clear fields if "Select..." is chosen
            $('#' + type + '_serial_' + index).val('');
            $('#' + type + '_ID_' + index).val('');
            $('#' + type + '_medicine_id_' + index).val('');
            $('#' + type + '_batch_' + index).val('');
            $('#' + type + '_batch_number_' + index).val('');
            $('#' + type + '_stock_' + index).val('');
            $('#' + type + '_price_' + index).val('');
            $('#' + type + '_price_display_' + index).val('');
            $('#' + type + '_gst_' + index).val('');
            $('#' + type + '_quantity_' + index).val('').attr('max', 0);
            $('#' + type + '_total_' + index).val('');
            return;
        }

        // Populate all fields from data attributes
        $('#' + type + '_serial_' + index).val($select.val());
        $('#' + type + '_ID_' + index).val(data.id);
        $('#' + type + '_medicine_id_' + index).val(data.medicine_id);
        $('#' + type + '_batch_' + index).val(data.batch);
        $('#' + type + '_batch_number_' + index).val(data.batch);
        $('#' + type + '_stock_' + index).val(data.quantity);
        $('#' + type + '_price_' + index).val(data.price);
        $('#' + type + '_price_display_' + index).val(data.price);
        $('#' + type + '_gst_' + index).val(data.gst);
        $('#' + type + '_quantity_' + index).attr('max', data.quantity).val(1); // Default to 1
        
        // Trigger calculation
        calculateRowTotal($select);
    }
    
    // --- Shared Function for Quantity Change ---
    function calculateRowTotal(element) {
        // Find the row and index
        const $el = $(element);
        const index = $el.data('index');
        
        // Find the type (medicine, injections, consumables)
        let type = '';
        if ($el.hasClass('medicine-quantity') || $el.hasClass('medicine-select')) {
            type = 'medicine';
        } else if ($el.hasClass('injections-quantity') || $el.hasClass('injections-select')) {
            type = 'injections';
        } else if ($el.hasClass('consumables-quantity') || $el.hasClass('consumables-select')) {
            type = 'consumables';
        } else {
            return; // Not a recognized element
        }
        
        const quantity = parseFloat($('#' + type + '_quantity_' + index).val()) || 0;
        const maxStock = parseFloat($('#' + type + '_stock_' + index).val()) || 0;
        
        // Stock Check
        if (quantity > maxStock) {
            alert(`Quantity cannot be more than available stock (${maxStock})`);
            $('#' + type + '_quantity_' + index).val(maxStock);
            quantity = maxStock;
        }

        const price = parseFloat($('#' + type + '_price_' + index).val()) || 0;
        const total = quantity * price;
        $('#' + type + '_total_' + index).val(total.toFixed(2));
    }

    // --- Event Handlers using Delegation ---
    $(document).on('change', '.medicine-select, .injections-select, .consumables-select', handleItemSelect);
    $(document).on('input', '.medicine-quantity, .injections-quantity, .consumables-quantity', function() {
        calculateRowTotal(this);
    });

    // --- Add Row Functions (This is the FIX) ---
    
    // Function to initialize a new row
    function initializeNewRow(row, type, counter) {
        var $select = row.find('.' + type + '-select');
        var $checkbox = row.find('.active-status');
        
        // 1. Check the box
        $checkbox.prop('checked', true);
        
        // 2. Enable the quantity input
        row.find('.' + type + '-quantity').prop('disabled', false);
        
        // 3. Enable and initialize Select2
        $select.prop('disabled', false).select2({
            placeholder: "Select Item",
            width: '100%'
        });
    }

    $('.add-medicine-row').click(function() {
        medicineCounter++;
        var optionsHtml = $('#medicine_name_1').html(); 
        var newRow = `
            <tr class="medicine_row_${medicineCounter}" data-index="${medicineCounter}">
                <td><input type="checkbox" class="active-status" data-type="medicine" data-index="${medicineCounter}" style="position: relative;left: 0px !important;opacity: 1 !important;"></td>
                <td><input value="" readonly id="medicine_serial_${medicineCounter}" class="form-control" name="medicine_serial_${medicineCounter}" type="text"></td>
                <td>
                    <select disabled name="medicine_name_${medicineCounter}" class="form-control medicine-select" id="medicine_name_${medicineCounter}" data-index="${medicineCounter}" style="width: 100%;">
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input disabled value="" id="medicine_quantity_${medicineCounter}" class="form-control medicine-quantity" name="medicine_quantity_${medicineCounter}" type="number" min="0" data-index="${medicineCounter}">
                    <input type="hidden" id="medicine_ID_${medicineCounter}" name="medicine_ID_${medicineCounter}">
                    <input type="hidden" id="medicine_medicine_id_${medicineCounter}" name="medicine_medicine_id_${medicineCounter}">
                    <input type="hidden" id="medicine_batch_${medicineCounter}" name="medicine_batch_${medicineCounter}">
                    <input type="hidden" id="medicine_price_${medicineCounter}" name="medicine_price_${medicineCounter}">
                    <input type="hidden" id="medicine_gst_${medicineCounter}" name="medicine_gst_${medicineCounter}">
                </td>
                <td><input value="" readonly id="medicine_batch_number_${medicineCounter}" class="form-control" name="medicine_batch_number_${medicineCounter}" type="text"></td>
                <td><input value="" readonly id="medicine_stock_${medicineCounter}" class="form-control" name="medicine_stock_${medicineCounter}" type="text"></td>
                <td><input value="" readonly id="medicine_price_display_${medicineCounter}" class="form-control" type="text"></td>
                <td><input value="" readonly id="medicine_total_${medicineCounter}" class="form-control" name="medicine_total_${medicineCounter}" type="text"></td>
            </tr>`;
        var $newRowEl = $(newRow);
        $('#medicine_table_body').append($newRowEl);
        initializeNewRow($newRowEl, 'medicine', medicineCounter); // Initialize the new row
    });
    
    $('.add-injections-row').click(function() {
        injectionsCounter++;
        var optionsHtml = $('#injections_name_1').html(); 
        var newRow = `
            <tr class="injections_row_${injectionsCounter}" data-index="${injectionsCounter}">
                <td><input type="checkbox" class="active-status" data-type="injections" data-index="${injectionsCounter}" style="position: relative;left: 0px !important;opacity: 1 !important;"></td>
                <td><input value="" readonly id="injections_serial_${injectionsCounter}" class="form-control" name="injections_serial_${injectionsCounter}" type="text"></td>
                <td>
                    <select disabled name="injections_name_${injectionsCounter}" class="form-control injections-select" id="injections_name_${injectionsCounter}" data-index="${injectionsCounter}" style="width: 100%;">
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input disabled value="" id="injections_quantity_${injectionsCounter}" class="form-control injections-quantity" name="injections_quantity_${injectionsCounter}" type="number" min="0" data-index="${injectionsCounter}">
                    <input type="hidden" id="injections_ID_${injectionsCounter}" name="injections_ID_${injectionsCounter}">
                    <input type="hidden" id="injections_medicine_id_${injectionsCounter}" name="injections_medicine_id_${injectionsCounter}">
                    <input type="hidden" id="injections_batch_${injectionsCounter}" name="injections_batch_${injectionsCounter}">
                    <input type="hidden" id="injections_price_${injectionsCounter}" name="injections_price_${injectionsCounter}">
                    <input type="hidden" id="injections_gst_${injectionsCounter}" name="injections_gst_${injectionsCounter}">
                </td>
                <td><input value="" readonly id="injections_batch_number_${injectionsCounter}" class="form-control" name="injections_batch_number_${injectionsCounter}" type="text"></td>
                <td><input value="" readonly id="injections_stock_${injectionsCounter}" class="form-control" name="injections_stock_${injectionsCounter}" type="text"></td>
                <td><input value="" readonly id="injections_price_display_${injectionsCounter}" class="form-control" type="text"></td>
                <td><input value="" readonly id="injections_total_${injectionsCounter}" class="form-control" name="injections_total_${injectionsCounter}" type="text"></td>
            </tr>`;
        var $newRowEl = $(newRow);
        $('#injections_table_body').append($newRowEl);
        initializeNewRow($newRowEl, 'injections', injectionsCounter); // Initialize the new row
    });

    $('.add-consumables-row').click(function() {
        consumablesCounter++;
        var optionsHtml = $('#consumables_name_1').html();
        var newRow = `
            <tr class="consumables_row_${consumablesCounter}" data-index="${consumablesCounter}">
                <td><input type="checkbox" class="active-status" data-type="consumables" data-index="${consumablesCounter}" style="position: relative;left: 0px !important;opacity: 1 !important;"></td>
                <td><input value="" readonly id="consumables_serial_${consumablesCounter}" class="form-control" name="consumables_serial_${consumablesCounter}" type="text"></td>
                <td>
                    <select disabled name="consumables_name_${consumablesCounter}" class="form-control consumables-select" id="consumables_name_${consumablesCounter}" data-index="${consumablesCounter}" style="width: 100%;">
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input disabled value="" id="consumables_quantity_${consumablesCounter}" class="form-control consumables-quantity" name="consumables_quantity_${consumablesCounter}" type="number" min="0" data-index="${consumablesCounter}">
                    <input type="hidden" id="consumables_ID_${consumablesCounter}" name="consumables_ID_${consumablesCounter}">
                    <input type="hidden" id="consumables_medicine_id_${consumablesCounter}" name="consumables_medicine_id_${consumablesCounter}">
                    <input type="hidden" id="consumables_batch_${consumablesCounter}" name="consumables_batch_${consumablesCounter}">
                    <input type="hidden" id="consumables_price_${consumablesCounter}" name="consumables_price_${consumablesCounter}">
                    <input type="hidden" id="consumables_gst_${consumablesCounter}" name="consumables_gst_${consumablesCounter}">
                </td>
                <td><input value="" readonly id="consumables_batch_number_${consumablesCounter}" class="form-control" name="consumables_batch_number_${consumablesCounter}" type="text"></td>
                <td><input value="" readonly id="consumables_stock_${consumablesCounter}" class="form-control" name="consumables_stock_${consumablesCounter}" type="text"></td>
                <td><input value="" readonly id="consumables_price_display_${consumablesCounter}" class="form-control" type="text"></td>
                <td><input value="" readonly id="consumables_total_${consumablesCounter}" class="form-control" name="consumables_total_${consumablesCounter}" type="text"></td>
            </tr>`;
        var $newRowEl = $(newRow);
        $('#consumables_table_body').append($newRowEl);
        initializeNewRow($newRowEl, 'consumables', consumablesCounter); // Initialize the new row
    });

    // --- Delete Row Functions (Simplified) ---
    function deleteSelectedRows(type) {
        if ($('.active-status[data-type="' + type + '"]:checked').length === 0) {
            alert('Please check the box next to the row you want to delete.');
            return;
        }

        if (confirm('Are you sure you want to delete the selected rows?')) {
            $('.active-status[data-type="' + type + '"]:checked').each(function() {
                $(this).closest('tr').find('.select2-hidden-accessible').select2('destroy');
                $(this).closest('tr').remove();
            });
        }
    }
    
    $('.delete-medicine-row').click(function() { deleteSelectedRows('medicine'); });
    $('.delete-injections-row').click(function() { deleteSelectedRows('injections'); });
    $('.delete-consumables-row').click(function() { deleteSelectedRows('consumables'); });

    // --- Form Submission ---
    $('#add_billing_form').on('submit', function(e) {
        e.preventDefault(); // Stop submission
        
        let isValid = true;
        let hasItems = false;
        
        // 1. Check required patient fields
        $('.panel-body .form-control[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
             alert('Patient Details Missing: Please fill in all required patient fields (IIC ID and Procedure Name).');
            return;
        }

        // 2. Check for items
        if ($('.medicine-select:enabled option:selected[value!=""]').length > 0 ||
            $('.injections-select:enabled option:selected[value!=""]').length > 0 ||
            $('.consumables-select:enabled option:selected[value!=""]').length > 0) {
            hasItems = true;
        }

        if (!hasItems) {
            isValid = false;
            alert('No Items Selected: Please check the box for at least one item and select it from the list.');
            return;
        }

        // 3. Validate quantities
        $('.medicine-quantity:enabled, .injections-quantity:enabled, .consumables-quantity:enabled').each(function() {
            const $qty = $(this);
            const qty = parseFloat($qty.val());
            const max = parseFloat($qty.attr('max'));
            if (isNaN(qty) || qty <= 0 || qty > max) {
                isValid = false;
                $qty.addClass('is-invalid');
            } else {
                $qty.removeClass('is-invalid');
            }
        });

        if (!isValid) {
            alert('Invalid Quantities: Please enter valid quantities (must be > 0 and not exceed available stock).');
            return;
        }

        // 4. If all checks pass, submit
        this.submit();
    });
});
</script>