
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"> -->
<Style>
    textarea {
        height: 50px !important;
    }
    /* New styles for the item table */
    .item-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }
    .item-table th, .item-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .item-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .item-table .form-control {
        height: 34px;
        padding: 6px 12px;
    }
    .item-table .btn-danger {
        padding: 6px 10px;
    }
    #grand_total_table {
        width: 40%;
        margin-left: 60%;
        margin-top: 15px;
    }
    #grand_total_table td {
        padding: 8px;
    }
    /* New styles for table footer */
    .item-table tfoot tr {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .item-table tfoot td {
        border-top: 2px solid #333;
    }
    .total-cell {
        text-align: right;
        font-size: 14px;
    }

    /* New styles for Serial Number */
    #po_items_table tbody {
        counter-reset: rowNumber; /* Reset the counter */
    }
    #po_items_table tbody tr {
        counter-increment: rowNumber; /* Increment the counter */
    }
    #po_items_table tbody tr td:first-child::before {
        content: counter(rowNumber); /* Display the counter */
        font-weight: bold;
    }
    #po_items_table tbody tr td:first-child {
        text-align: center;
        width: 5%;
    }


    #grand_total_table {
        width: 40%;
        margin-left: 60%;
        margin-top: 15px;
    }
    #grand_total_table td {
        padding: 8px;
    }
    
    /* Select2 styling for Approved By field */
    .select2-container {
        width: 100% !important;
    }
    #approved_by_select + .select2-container {
        margin-top: 0;
    }
    .select2-container--default .select2-selection--multiple {
        min-height: 34px;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 0;
        background-color: #fff;
        line-height: 1.42857143;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        padding: 2px 8px;
        width: 100%;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #5cb85c;
        border: 1px solid #4cae4c;
        color: #fff;
        padding: 4px 10px;
        margin: 2px 3px 2px 0;
        border-radius: 3px;
        display: inline-block;
        font-size: 13px;
        line-height: 1.5;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 6px;
        cursor: pointer;
        font-weight: bold;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ffcccc;
    }
    .select2-container--default .select2-search--inline {
        width: 100%;
    }
    .select2-container--default .select2-search--inline .select2-search__field {
        margin-top: 2px;
        height: 30px;
        padding: 0 5px;
        border: none;
        outline: none;
        width: 100% !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple,
    .select2-container--default.select2-container--open .select2-selection--multiple {
        border-color: #66afe9;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
    }
    .select2-dropdown {
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .select2-results__option {
        padding: 8px 12px;
    }
    .select2-results__option--highlighted {
        background-color: #337ab7 !important;
        color: #fff !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__clear {
        margin-top: 5px;
        margin-right: 5px;
    }
</Style>
<div class="col-sm-12 col-xs-12">
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="col-sm-12 col-xs-12" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
            <h4 style="margin: 0; color: #155724;"><?php echo $this->session->flashdata('success'); ?></h4>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <div class="col-sm-12 col-xs-12" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
            <h4 style="margin: 0; color: #721c24;"><?php echo $this->session->flashdata('error'); ?></h4>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('warning')) : ?>
        <div class="col-sm-12 col-xs-12" style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
            <h4 style="margin: 0; color: #856404;"><?php echo $this->session->flashdata('warning'); ?></h4>
        </div>
    <?php endif; ?>

    <form action="<?php echo base_url('accounts/save_purchase_order'); ?>" method="post" class="col-sm-12 col-xs-12" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_purchase_orders" />

        <div class="row">
            <div class="col-sm-12 col-xs-12 panel panel-piluku">
                <div class="panel-heading text-center">
                    <h3 class="heading">Purchase Order (PO) / Service Work Order (SO)</h3>
                </div>

                <div class="panel-body profile-edit">
                    <div class="row">

                        <div class="form-group col-sm-4">
                            <label><strong>Centre/Cluster/Region</strong>  * </label>
                            <select name="po_centre" id="po_centre" class="form-control" required>
                                <?php
                                $all_method = &get_instance();
                                $all_method->load->model('center_model');
                                $centers = $all_method->center_model->get_centers();
                                if (!empty($centers)) {
                                    foreach ($centers as $center) {
                                        echo '<option value="' . $center['center_number'] . '">' . $center['center_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-4">
                            <label><strong>Department</strong> * </label>
                            <select name="po_department" class="form-control" required>
                                <option value="">-- Select Department --</option>
                                <option value="Director">Director</option>
                                <option value="Finance & Accounts">Finance & Accounts</option>
                                <option value="Human Resource">Human Resource</option>
                                <option value="IT">IT</option>
                                <option value="Operations">Operations</option>
                                <option value="Telesales">Telesales</option>
                                <option value="B2B">B2B</option>
                                <option value="Sales & Marketing">Sales & Marketing</option>
                                <option value="Business Expansion">Business Expansion</option>
                                <option value="Digital marketing">Digital marketing</option>
                                <option value="Clinical-Operations">Clinical-Operations</option>
                                <option value="Clinical-Pharmacy">Clinical-Pharmacy</option>
                                <option value="Clinical-IVF Coordinator">Clinical-IVF Coordinator</option>
                                <option value="Clinical-OT">Clinical-OT</option>
                                <option value="Clinical-Embryologist">Clinical-Embryologist</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-4">
                            <label><strong>Nature of Expenditure</strong> (Capex/Opex) *</label>
                            <select name="po_nature_of_expenditure" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="Capex">Capex</option>
                                <option value="Opex">Opex</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-4">
                            <label><strong>Budget Head</strong> * </label>
                            <select name="po_budget_head" class="form-control" required>
                                <option value="">-- Select Choose --</option>
                                <option value="Rent Building">Rent Building</option>
                                <option value="Rental Service for Computer">Rental Service for Computer</option>
                                <option value="Rent Agreement Registration Charges">Rent Agreement Registration Charges</option>
                                <option value="Power & Water Charges">Power & Water Charges</option>
                                <option value="Repair and Maintenance">Repair and Maintenance</option>
                                <option value="Employee Benefit Exps.">Employee Benefit Exps.</option>
                                <option value="Office Cleaning & Security Services">Office Cleaning & Security Services</option>
                                <option value="Doctor & Other Medical Professional Charges">Doctor & Other Medical Professional Charges</option>
                                <option value="Human Resource Hiring Exp.">Human Resource Hiring Exp.</option>
                                <option value="Payroll Software Exps">Payroll Software Exps</option>
                                <option value="Consumable & Disposable Opd">Consumable & Disposable Opd</option>
                                <option value="Diagnostic Expenses">Diagnostic Expenses</option>
                                <option value="Healthcare allied Exp.">Healthcare allied Exp.</option>
                                <option value="Legal & Professional Expenses">Legal & Professional Expenses</option>
                                <option value="Licioning & certification Exps">Licioning & certification Exps</option>
                                <option value="Office Expenses">Office Expenses</option>
                                <option value="Electricity & Maintenance">Electricity & Maintenance</option>
                                <option value="Security & House Keeping Charges">Security & House Keeping Charges</option>
                                <option value="Advertisement & Marketing">Advertisement & Marketing</option>
                                <option value="Printing & Stationery">Printing & Stationery</option>
                                <option value="Travelling Exp.">Travelling Exp.</option>
                                <option value="Insurance">Insurance</option>
                                <option value="Vehicle Fuel & Lubricant">Vehicle Fuel & Lubricant</option>
                                <option value="Website Expenses">Website Expenses</option>
                                <option value="IT & Support">IT & Support</option>
                                <option value="Digital Marketing Exp.">Digital Marketing Exp.</option>
                                <option value="Business Promotion Exps.">Business Promotion Exps.</option>
                                <option value="Assets">Assets</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-4">
                            <label><strong>Name of Vendor</strong> * </label>
                            <input type="text" name="po_name_of_vendor" class="form-control" placeholder="Enter Vendor Name" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <label><strong>Budget Item</strong> </label>
                            <input type="text" name="po_budget_item" class="form-control" placeholder="Enter Budget Item" required>
                        </div>
                        <hr>
                     
                        <!-- <div class="row">
                            <div class="col-md-12">
                                <h4>Purchase Order Items</h4>
                                <table class="table item-table" id="po_items_table">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%;">Item Description</th>
                                            <th style="width: 15%;">Quantity</th>
                                            <th style="width: 15%;">Rate (Ex. GST)</th>
                                            <th style="width: 20%;">Total (Ex. GST)</th>
                                            <th style="width: 10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <button type="button" class="btn btn-primary btn-sm" id="add_item_row">
                                                    <i class="glyphicon glyphicon-plus"></i> Add Item
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div> -->
                         <hr>
                        <div class="row">
                        <div class="col-md-12">
                            <h4>Purchase Order Items</h4>
                                <table class="table item-table" id="po_items_table">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th style="width: 30%;">Item Description</th>
                                            <th style="width: 10%;">Quantity</th>
                                            <th style="width: 15%;">Rate (Ex. GST)</th>
                                            <th style="width: 10%;">GST %</th>
                                            <th style="width: 15%;">Basic Total (Ex. GST)</th>
                                            <th style="width: 15%;">GST Amount</th>
                                            <th style="width: 5%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="total-cell">Total</td>
                                            <td id="tfoot_qty_total" class="total-cell">0.00</td>
                                            <td colspan="2"></td>
                                            <td id="tfoot_basic_total" class="total-cell">0.00</td>
                                            <td id="tfoot_gst_total" class="total-cell">0.00</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <button type="button" class="btn btn-primary btn-sm" id="add_item_row">
                                    <i class="glyphicon glyphicon-plus"></i> Add Item
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="grand_total_table" class="table">
                                    <tbody>
                                        <tr>
                                            <td style="width: 50%; font-weight: bold;">Basic Amount (Ex. GST)</td>
                                            <td>
                                                <input type="number" step="0.01" name="po_basic_amount" id="po_basic_amount" class="form-control" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">GST Amount</td>
                                            <td>
                                                <input type="number" step="0.01" name="po_gst_amount" id="po_gst_amount" class="form-control" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Other Charges & Taxes</td>
                                            <td>
                                                <input type="number" step="0.01" name="po_other_charges_and_taxes" id="po_other_charges_and_taxes" class="form-control" placeholder="0.00">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; font-size: 16px;">PO TOTAL (Inc. All)</td>
                                            <td>
                                                <input type="number" step="0.01" name="po_po_total" id="po_po_total" class="form-control" style="font-weight: bold; font-size: 16px;" readonly>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group col-sm-12">
                            <label><strong>Approved By</strong> * </label>
                            <select name="approved_by[]" id="approved_by_select" class="form-control" multiple="multiple" required>
                                <?php
                                $all_method = &get_instance();
                                $all_method->load->model('Employee_model');
                                $approvers = $all_method->Employee_model->get_approvers_for_purchase_order();
                                $preselected_emails = [''];
                                if (!empty($approvers)) {
                                    $unique_approvers = [];
                                    $seen_emails = [];
                                    foreach ($approvers as $approver) {
                                        $email_lower = strtolower(trim($approver['email']));
                                        if (!in_array($email_lower, $seen_emails) && !empty($email_lower)) {
                                            $unique_approvers[] = $approver;
                                            $seen_emails[] = $email_lower;
                                        }
                                    }
                                    foreach ($unique_approvers as $approver) {
                                        $approver_name  = strtoupper($approver['name']);
                                        $approver_email = $approver['email'];
                                        $selected = in_array(strtolower($approver_email), array_map('strtolower', $preselected_emails)) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($approver_email) . '" ' . $selected . '>' . htmlspecialchars($approver_name) . '</option>';
                                    }
                                } else {
                                    $selected = in_array('director@indiaivf.in', $preselected_emails) ? 'selected' : '';
                                    echo '<option value="director@indiaivf.in" ' . $selected . '>DIRECTOR</option>';
                                }
                                ?>
                            </select>
                            <p class="help-block" style="margin-top: 5px;">You can type a name to search and select one or more approvers.</p>
                        </div>
                        <br>
                        <div class="form-group col-sm-12">
                            <label><strong>Remarks / Comment / Narration</strong></label>
                            <textarea name="po_remarks_or_comment_or_narration" class="form-control" rows="2"  placeholder="Enter remarks..." ></textarea>
                        </div>
                        <!-- <div class="form-group col-sm-4">
                            <label><strong>Basic Amount (Ex GST)</strong> * </label>
                            <input type="number" step="0.01" name="po_basic_amount" id="po_basic_amount" class="form-control" placeholder="0.00" required>
                        </div> -->

                        <!-- <div class="form-group col-sm-4">
                            <label><strong>GST Amount</strong> * </label>
                            <input type="number" step="0.01" name="po_gst_amount" id="po_gst_amount" class="form-control" placeholder="0.00" required>
                        </div> -->

                        <!-- <div class="form-group col-sm-4">
                            <label><strong>Other Charges & Taxes</strong> * </label>
                            <input type="number" step="0.01" name="po_other_charges_and_taxes" id="po_other_charges_and_taxes" class="form-control" placeholder="0.00" required>
                        </div> -->

                        <!-- <div class="form-group col-sm-4">
                            <label><strong>PO Total (Inc GST & All Charges)</strong> * </label>
                            <input type="number" step="0.01" name="po_po_total" id="po_po_total" class="form-control" placeholder="0.00" readonly>
                        </div> -->

                        <div class="form-group col-sm-12">
                            <label><strong>Supporting Documents</strong> *</label>
                            <input type="file" name="po_supporting_documents[]" class="form-control" multiple onchange="return validateFileExtension(this)">
                            <p class="help-block">Supporting Documents Upload. Supported formats: PDF, JPG, PNG, WEBP, GIF, BMP. Max 10 MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-xs-12 text-center mt-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-default">Reset</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function validateFileExtension(input) {
        const files = input.files;
        if (!files.length) {
            return true; 
        }
        const allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'doc', 'docx', 'xlsx', 'xls', 'csv', 'txt', 'pdf', 'pdfx', 'pdfa'];
        const maxSize = 10 * 1024 * 1024; 
        for (const file of files) {
            if (file.size > maxSize) {
                alert('File size for "' + file.name + '" exceeds 10MB limit. Please select smaller files.');
                input.value = '';
                return false;
            }
            const fileName = file.name.toLowerCase();
            const fileExtension = fileName.split('.').pop();
            if (!allowedExtensions.includes(fileExtension)) {
                alert('Invalid file type for "' + file.name + '".\n\nAllowed types are: ' + allowedExtensions.join(', '));
                input.value = '';
                return false;
            }
        }
        return true;
    }

    function calculateTotal() {
        let basic = parseFloat(document.getElementById("po_basic_amount").value) || 0;
        let gst = parseFloat(document.getElementById("po_gst_amount").value) || 0;
        let other = parseFloat(document.getElementById("po_other_charges_and_taxes").value) || 0;

        let total = basic + gst + other;
        document.getElementById("po_po_total").value = total.toFixed(2);
    }
    // Add event listeners for calculation
    document.getElementById("po_basic_amount").addEventListener("input", calculateTotal);
    document.getElementById("po_gst_amount").addEventListener("input", calculateTotal);
    document.getElementById("po_other_charges_and_taxes").addEventListener("input", calculateTotal);
    // SCRIPT FOR SELECT2
    $(document).ready(function() {
        // Initialize Select2 for Approved By field
        if ($('#approved_by_select').length) {
            $('#approved_by_select').select2({
                placeholder: 'Search and select approvers...',
                allowClear: true,
                width: '100%',
                closeOnSelect: false,
                tags: false,
                maximumSelectionLength: null,
                language: {
                    noResults: function() {
                        return "No approvers found";
                    },
                    searching: function() {
                        return "Searching...";
                    }
                }
            });
        }
    });
    // $("#add_item_row").click(function() {
    //     var newRow = `
    //         <tr>
    //             <td><input type="text" name="item_description[]" class="form-control" placeholder="Item Description" required></td>
    //             <td><input type="number" step="0.01" name="quantity[]" class="form-control po-item-calc" placeholder="0" required></td>
    //             <td><input type="number" step="0.01" name="rate[]" class="form-control po-item-calc" placeholder="0.00" required></td>
    //             <td><input type="number" step="0.01" name="item_total[]" class="form-control item-total" placeholder="0.00" readonly></td>
    //             <td><button type="button" class="btn btn-danger btn-sm remove-item-row"><i class="glyphicon glyphicon-trash"></i></button></td>
    //         </tr>
    //     `;
    //     $("#po_items_table tbody").append(newRow);
    // });
    // $("#po_items_table").on('click', '.remove-item-row', function() {
    //     $(this).closest('tr').remove();
    //     calculateGrandTotal(); // Recalculate after removing
    // });
    // // 3. Calculate Row Total and Grand Total on input
    // $("#po_items_table").on('input', '.po-item-calc', function() {
    //     var $row = $(this).closest('tr');
    //     var qty = parseFloat($row.find('input[name="quantity[]"]').val()) || 0;
    //     var rate = parseFloat($row.find('input[name="rate[]"]').val()) || 0;
        
    //     var rowTotal = qty * rate;
    //     $row.find('input[name="item_total[]"]').val(rowTotal.toFixed(2));
        
    //     calculateGrandTotal();
    // });
    // $("#po_gst_amount, #po_other_charges_and_taxes").on('input', function() {
    //     calculateGrandTotal();
    // });
    // function calculateGrandTotal() {
    //     var basicAmount = 0;
    //     $(".item-total").each(function() {
    //         basicAmount += parseFloat($(this).val()) || 0;
    //     });
    //     $("#po_basic_amount").val(basicAmount.toFixed(2));
    //     // Get GST and Other
    //     var gst = parseFloat($("#po_gst_amount").val()) || 0;
    //     var other = parseFloat($("#po_other_charges_and_taxes").val()) || 0;
    //     var poTotal = basicAmount + gst + other;
    //     $("#po_po_total").val(poTotal.toFixed(2));
    // }
    // $("#add_item_row").click();
        $("#add_item_row").click(function() {
            var newRow = `
                <tr>
                    <td></td> <td><input type="text" name="item_description[]" class="form-control" placeholder="Item Description" required></td>
                    <td><input type="number" step="0.01" name="quantity[]" class="form-control po-item-calc" placeholder="0" required></td>
                    <td><input type="number" step="0.01" name="rate[]" class="form-control po-item-calc" placeholder="0.00" required></td>
                    <td><input type="number" step="0.01" name="item_gst_rate[]" class="form-control po-item-calc" placeholder="e.g. 18" required></td>
                    <td><input type="number" step="0.01" name="item_basic_total[]" class="form-control item-basic-total" placeholder="0.00" readonly></td>
                    <td><input type="number" step="0.01" name="item_gst_amount[]" class="form-control item-gst-amount" placeholder="0.00" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item-row"><i class="glyphicon glyphicon-trash"></i></button></td>
                </tr>
            `;
            $("#po_items_table tbody").append(newRow);
        });
    // 2. Remove Item Row
        $("#po_items_table").on('click', '.remove-item-row', function() {
            $(this).closest('tr').remove();
            calculateGrandTotal(); // Recalculate after removing
        });
       $("#po_items_table").on('input', '.po-item-calc', function() {
            var $row = $(this).closest('tr');
            var qty = parseFloat($row.find('input[name="quantity[]"]').val()) || 0;
            var rate = parseFloat($row.find('input[name="rate[]"]').val()) || 0;
            var gstRate = parseFloat($row.find('input[name="item_gst_rate[]"]').val()) || 0;
            // Calculate item totals
            var rowBasicTotal = qty * rate;
            var rowGstAmount = rowBasicTotal * (gstRate / 100);
            $row.find('input[name="item_basic_total[]"]').val(rowBasicTotal.toFixed(2));
            $row.find('input[name="item_gst_amount[]"]').val(rowGstAmount.toFixed(2));
            calculateGrandTotal();
        });
        $("#po_other_charges_and_taxes").on('input', function() {
            calculateGrandTotal();
        });
        function calculateGrandTotal() {
            var totalQty = 0;
            var totalBasic = 0;
            var totalGst = 0;
            $("#po_items_table tbody tr").each(function() {
                totalQty += parseFloat($(this).find('input[name="quantity[]"]').val()) || 0;
                totalBasic += parseFloat($(this).find('input[name="item_basic_total[]"]').val()) || 0;
                totalGst += parseFloat($(this).find('input[name="item_gst_amount[]"]').val()) || 0;
            });
            // Update Table Footer (tfoot)
            $("#tfoot_qty_total").text(totalQty.toFixed(2));
            $("#tfoot_basic_total").text(totalBasic.toFixed(2));
            $("#tfoot_gst_total").text(totalGst.toFixed(2));
            // Update Grand Total Table (Bottom)
            $("#po_basic_amount").val(totalBasic.toFixed(2));
            $("#po_gst_amount").val(totalGst.toFixed(2));
            var otherCharges = parseFloat($("#po_other_charges_and_taxes").val()) || 0;
            var poTotal = totalBasic + totalGst + otherCharges;
            $("#po_po_total").val(poTotal.toFixed(2));
        }
        $("#add_item_row").click();
    
</script>
