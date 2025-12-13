<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-undo"></i> Medicine Returns
                    <small>Handle patient medicine returns</small>
                </h1>
            </div>
        </div>

        <!-- Return Form -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-undo"></i> Return Medicine
                    </div>
                    <div class="panel-body">
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

                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo base_url('stocks_new/process_return'); ?>" method="post" class="form-horizontal">
                            <input type="hidden" name="action" value="return_medicine">

                            <!-- Patient Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Patient ID *</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="patient_id" class="form-control" placeholder="Enter Patient ID" value="<?php echo set_value('patient_id'); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Patient Name *</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="patient_name" class="form-control" placeholder="Enter Patient Name" value="<?php echo set_value('patient_name'); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Receipt Number *</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="receipt_number" class="form-control" placeholder="Enter Receipt Number" value="<?php echo set_value('receipt_number'); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Return Date *</label>
                                        <div class="col-sm-8">
                                            <input type="date" name="return_date" class="form-control" value="<?php echo set_value('return_date', date('Y-m-d')); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Return Reason *</label>
                                        <div class="col-sm-8">
                                            <select name="return_reason" class="form-control" required>
                                                <option value="">Select Reason</option>
                                                <option value="PATIENT_REQUEST" <?php echo set_select('return_reason', 'PATIENT_REQUEST'); ?>>Patient Request</option>
                                                <option value="DOCTOR_CANCELLATION" <?php echo set_select('return_reason', 'DOCTOR_CANCELLATION'); ?>>Doctor Cancellation</option>
                                                <option value="MEDICINE_DAMAGED" <?php echo set_select('return_reason', 'MEDICINE_DAMAGED'); ?>>Medicine Damaged</option>
                                                <option value="WRONG_MEDICINE" <?php echo set_select('return_reason', 'WRONG_MEDICINE'); ?>>Wrong Medicine</option>
                                                <option value="EXPIRED_MEDICINE" <?php echo set_select('return_reason', 'EXPIRED_MEDICINE'); ?>>Expired Medicine</option>
                                                <option value="OTHER" <?php echo set_select('return_reason', 'OTHER'); ?>>Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Center *</label>
                                        <div class="col-sm-8">
                                            <?php if (isset($selected_center_id) && !empty($selected_center_id)): ?>
                                                <!-- Hidden field to ensure value is submitted when select is disabled -->
                                                <input type="hidden" name="center_id" value="<?php echo $selected_center_id; ?>">
                                                <select class="form-control" id="center_id" disabled style="background-color: #e9ecef; cursor: not-allowed;">
                                                    <option value="">Select Center</option>
                                                    <?php foreach($centers as $center): ?>
                                                        <option value="<?php echo $center->ID; ?>" <?php echo ($center->ID == $selected_center_id) ? 'selected' : ''; ?>>
                                                            <?php echo $center->center_name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <small class="help-block text-muted"><i class="fa fa-info-circle"></i> Center is automatically selected based on your login</small>
                                            <?php else: ?>
                                                <select name="center_id" class="form-control" id="center_id" required>
                                                    <option value="">Select Center</option>
                                                    <?php foreach($centers as $center): ?>
                                                        <option value="<?php echo $center->ID; ?>" <?php echo set_select('center_id', $center->ID); ?>>
                                                            <?php echo $center->center_name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-group" id="department">
                                        <label class="col-sm-4 control-label">Department *</label>
                                        <div class="col-sm-8">
                                            <!-- <select name="department" class="form-control"  id="department">
                                                <option value="">Select Department</option>
                                                <?php foreach($departments as $dept): ?>
                                                    <option value="<?php echo $dept['department']; ?>" <?php echo set_select('department', $dept['department']); ?>>
                                                        <?php echo $dept['department']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select> -->
                                            <select name="department" id="department" class="form-control" required>
                                                <option value="">Select Department</option>
                                                <option value="CASH MEDICINE NOIDA">CASH MEDICINE NOIDA</option>
                                                <option value="CASH MEDICINE GGN">CASH MEDICINE GGN</option>
                                                <option value="CASH MEDICINE GP">CASH MEDICINE BASANT LOK</option>
                                                <option value="CASH MEDICINE SRINAGAR">CASH MEDICINE SRINAGAR</option>
                                                <option value="CASH MEDICINE GHAZIABAD">CASH MEDICINE GHAZIABAD</option>
                                                <option value="CASH MEDICINE GHAZIABAD">CASH MEDICINE  ROHINI</option>
                                                <option value="CASH MEDICINE GHAZIABAD">HORMONAL ROHINI</option>
                                                <option value="Hormonal Ghaziabad">Hormonal Ghaziabad</option>
                                                <option value="HORMONAL SRINAGAR">HORMONAL SRINAGAR</option>
                                                <option value="Hormonal Delhi">Hormonal Basant Lok</option>
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
                            </div>

                            <!-- Return Items -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Return Items</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="return_items_table">
                                            <thead>
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Batch</th>
                                                    <th>Quantity Sold</th>
                                                    <th>Return Quantity</th>
                                                    <th>Quentity Price</th>
                                                    <th>Return Amount</th>
                                                    <th>Discount (%)</th>
                                                    <th>Discount Amount</th>
                                                    <th>Final Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="return_items[0][batch_id]" class="form-control batch_select" required>
                                                            <option value="">Select Medicine</option>
                                                            <?php if(isset($available_batches) && !empty($available_batches)): ?>
                                                                <?php foreach($available_batches as $batch): ?>
                                                                    <option value="<?php echo isset($batch->batch_id) ? $batch->batch_id : $batch->id; ?>"
                                                                            data-medicine="<?php echo isset($batch->medicine_name) ? $batch->medicine_name : 'Unknown'; ?>"
                                                                            data-batch-number="<?php echo isset($batch->batch_number) ? $batch->batch_number : 'N/A'; ?>"
                                                                            data-pack-size="<?php echo isset($batch->pack_size) ? $batch->pack_size : 'N/A'; ?>"
                                                                            data-price="<?php echo isset($batch->selling_price) ? $batch->selling_price : 0; ?>"
                                                                            data-sold-qty="<?php echo isset($batch->quantity_sold) ? $batch->quantity_sold : 0; ?>">
                                                                        <?php echo (isset($batch->medicine_name) ? $batch->medicine_name : 'Unknown') . ' - ' . (isset($batch->batch_number) ? $batch->batch_number : 'N/A'); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <option value="">No medicines available</option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </td>
                                                    <td><span class="batch_number">-</span></td>
                                                    <td><span class="sold_quantity">-</span></td>
                                                    <td>
                                                        <input type="number" name="return_items[0][return_quantity]" class="form-control return_quantity" min="1" required>
                                                    </td>
                                                    <td>
                                                        <span class="unit_price">-</span>
                                                        <input type="hidden" name="return_items[0][price]" class="hidden_price" value="0">
                                                    </td>
                                                    <td><span class="return_amount">-</span></td>
                                                    <td>
                                                        <input type="number" name="return_items[0][discount_percentage]" class="form-control item_discount_percentage" min="0" max="100" step="0.01" value="0" placeholder="0">
                                                        <input type="hidden" name="return_items[0][discount_percentage_hidden]" class="item_discount_percentage_hidden" value="0">
                                                    </td>
                                                    <td><span class="item_discount_amount">₹0.00</span></td>
                                                    <td><span class="item_final_amount">-</span></td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove_row">
                                                            <i class="fa fa-trash-o"></i>
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

                            <!-- Return Summary -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Total Return Amount</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="total_return_amount" class="form-control" id="total_return_amount" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Total Discount Amount</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="total_discount_amount" class="form-control" id="total_discount_amount" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label"><strong>Final Return Amount</strong></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="final_return_amount" class="form-control" id="final_return_amount" readonly style="font-weight: bold; font-size: 16px;">
                                            <input type="hidden" name="final_return_amount_hidden" id="final_return_amount_hidden" value="0">
                                        </div>
                                    </div>
                                    <!-- COMMENTED OUT: Total discount percentage (now using per-item discount)
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Discount (%)</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="discount_percentage" class="form-control" id="discount_percentage" min="0" max="100" step="0.01" value="<?php echo set_value('discount_percentage', '0'); ?>" placeholder="Enter discount percentage">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Discount Amount</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="discount_amount" class="form-control" id="discount_amount" readonly>
                                        </div>
                                    </div>
                                    -->
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea name="remarks" class="form-control" rows="3" placeholder="Enter remarks"><?php echo set_value('remarks'); ?></textarea>
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
                                                <i class="fa fa-undo"></i> Process Return
                                            </button>
                                            <a href="<?php echo base_url('stocks_new/returns'); ?>" class="btn btn-default">
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

        <!-- Return Guidelines -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Return Guidelines
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><strong>Valid Returns:</strong> Only medicines sold within the last 30 days can be returned</li>
                            <li><strong>Condition Check:</strong> Returned medicines must be in good condition</li>
                            <li><strong>Batch Tracking:</strong> Returned stock is added back to the same batch</li>
                            <li><strong>FEFO Compliance:</strong> Returned stock follows FEFO principle for future sales</li>
                            <li><strong>Audit Trail:</strong> All returns are logged for complete traceability</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var rowCount = 1;

    // Add new return item row
    $('#add_return_item').click(function() {
        var newRow = '<tr>' +
            '<td><select name="return_items[' + rowCount + '][batch_id]" class="form-control batch_select" required>' +
            '<option value="">Select Medicine</option>' +
            '<?php if(isset($available_batches) && !empty($available_batches)): ?>' +
            '<?php foreach($available_batches as $batch): ?>' +
            '<option value="<?php echo isset($batch->batch_id) ? $batch->batch_id : $batch->id; ?>" data-medicine="<?php echo isset($batch->medicine_name) ? $batch->medicine_name : 'Unknown'; ?>" data-batch-number="<?php echo isset($batch->batch_number) ? $batch->batch_number : 'N/A'; ?>" data-pack-size="<?php echo isset($batch->pack_size) ? $batch->pack_size : 'N/A'; ?>" data-price="<?php echo isset($batch->selling_price) ? $batch->selling_price : 0; ?>" data-sold-qty="<?php echo isset($batch->quantity_sold) ? $batch->quantity_sold : 0; ?>"><?php echo (isset($batch->medicine_name) ? $batch->medicine_name : 'Unknown') . ' - ' . (isset($batch->batch_number) ? $batch->batch_number : 'N/A'); ?></option>' +
            '<?php endforeach; ?>' +
            '<?php endif; ?>' +
            '</select></td>' +
            '<td><span class="batch_number">-</span></td>' +
            '<td><span class="sold_quantity">-</span></td>' +
            '<td><input type="number" name="return_items[' + rowCount + '][return_quantity]" class="form-control return_quantity" min="1" required></td>' +
            '<td><span class="unit_price">-</span><input type="hidden" name="return_items[' + rowCount + '][price]" class="hidden_price" value="0"></td>' +
            '<td><span class="return_amount">-</span></td>' +
            '<td><input type="number" name="return_items[' + rowCount + '][discount_percentage]" class="form-control item_discount_percentage" min="0" max="100" step="0.01" value="0" placeholder="0"><input type="hidden" name="return_items[' + rowCount + '][discount_percentage_hidden]" class="item_discount_percentage_hidden" value="0"></td>' +
            '<td><span class="item_discount_amount">₹0.00</span></td>' +
            '<td><span class="item_final_amount">-</span></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash-o"></i></button></td>' +
            '</tr>';

        $('#return_items_table tbody').append(newRow);
        rowCount++;
    });

    // Remove row
    $(document).on('click', '.remove_row', function() {
        $(this).closest('tr').remove();
        calculateTotal();
    });

    // Batch selection change
    $(document).on('change', '.batch_select', function() {
        var selectedOption = $(this).find('option:selected');
        var row = $(this).closest('tr');
        if (selectedOption.val()) {
            var price = parseFloat(selectedOption.data('price')) || 0;
            var pack_size = parseFloat(selectedOption.data('pack-size')) || 1;
            var unitPrice_MRP = pack_size > 0 ? (price / pack_size) : price;
            var soldQty = parseFloat(selectedOption.data('sold-qty')) || 0;
            
            row.find('.batch_number').text(selectedOption.data('batch-number'));
            row.find('.sold_quantity').text(soldQty);
            row.find('.unit_price').text('₹' + unitPrice_MRP.toFixed(2));
            row.find('.hidden_price').val(price);
            row.find('.return_quantity').attr('max', soldQty);
            
            // Validate existing return quantity if it exceeds sold quantity
            var currentReturnQty = parseFloat(row.find('.return_quantity').val()) || 0;
            if (currentReturnQty > soldQty && soldQty > 0) {
                row.find('.return_quantity').val(soldQty);
                alert('Return quantity adjusted to sold quantity (' + soldQty + ')');
            }
            
            // Trigger input event to recalculate return amount
            row.find('.return_quantity').trigger('input');
            // Calculate item discount when batch is selected
            calculateItemDiscount(row);
        } else {
            row.find('.batch_number, .sold_quantity, .unit_price').text('-');
            row.find('.hidden_price').val(0);
            row.find('.return_quantity').removeAttr('max');
        }
    });

    // Calculate return amount
    $(document).on('input', '.return_quantity', function() {
        var row = $(this).closest('tr');
        var quantity = parseFloat($(this).val()) || 0;
        var soldQty = parseFloat(row.find('.sold_quantity').text()) || 0;
        
        // Validate: return quantity cannot exceed sold quantity
        if (soldQty > 0 && quantity > soldQty) {
            $(this).val(soldQty);
            quantity = soldQty;
            alert('Return quantity cannot exceed sold quantity (' + soldQty + ')');
        }
        
        var unitPrice = parseFloat(row.find('.unit_price').text().replace('₹', '')) || 0;
        var returnAmount = quantity * unitPrice;

        row.find('.return_amount').text('₹' + returnAmount.toFixed(2));
        calculateItemDiscount(row);
        calculateTotal();
    });

    // Validate on blur (when user leaves the field)
    $(document).on('blur', '.return_quantity', function() {
        var row = $(this).closest('tr');
        var quantity = parseFloat($(this).val()) || 0;
        var soldQty = parseFloat(row.find('.sold_quantity').text()) || 0;
        
        // Validate: return quantity cannot exceed sold quantity
        if (soldQty > 0 && quantity > soldQty) {
            $(this).val(soldQty);
            alert('Return quantity cannot exceed sold quantity (' + soldQty + ')');
            // Recalculate after correction
            $(this).trigger('input');
        }
    });

    // Calculate per-item discount and final amount
    function calculateItemDiscount(row) {
        var returnAmount = parseFloat(row.find('.return_amount').text().replace('₹', '')) || 0;
        var discountPercentage = parseFloat(row.find('.item_discount_percentage').val()) || 0;
        
        // Validate discount percentage
        if (discountPercentage < 0) {
            discountPercentage = 0;
            row.find('.item_discount_percentage').val(0);
        }
        if (discountPercentage > 100) {
            discountPercentage = 100;
            row.find('.item_discount_percentage').val(100);
        }
        
        var discountAmount = (returnAmount * discountPercentage) / 100;
        var finalAmount = returnAmount - discountAmount;
        
        row.find('.item_discount_amount').text('₹' + discountAmount.toFixed(2));
        row.find('.item_final_amount').text('₹' + finalAmount.toFixed(2));
        row.find('.item_discount_percentage_hidden').val(discountPercentage);
    }

    // Calculate discount when item discount percentage changes
    $(document).on('input', '.item_discount_percentage', function() {
        var row = $(this).closest('tr');
        calculateItemDiscount(row);
        calculateTotal();
    });

    // Validate and calculate discount when item discount percentage loses focus
    $(document).on('blur', '.item_discount_percentage', function() {
        var row = $(this).closest('tr');
        var discountPercentage = parseFloat($(this).val()) || 0;
        if (discountPercentage < 0) {
            $(this).val(0);
        }
        if (discountPercentage > 100) {
            $(this).val(100);
        }
        calculateItemDiscount(row);
        calculateTotal();
    });

    // Calculate total return amount
    function calculateTotal() {
        var total = 0;
        var totalDiscount = 0;
        var totalFinal = 0;
        
        $('.return_amount').each(function() {
            var amount = parseFloat($(this).text().replace('₹', '')) || 0;
            total += amount;
        });
        
        $('.item_discount_amount').each(function() {
            var discount = parseFloat($(this).text().replace('₹', '')) || 0;
            totalDiscount += discount;
        });
        
        $('.item_final_amount').each(function() {
            var final = parseFloat($(this).text().replace('₹', '')) || 0;
            totalFinal += final;
        });
        
        $('#total_return_amount').val('₹' + total.toFixed(2));
        $('#total_discount_amount').val('₹' + totalDiscount.toFixed(2));
        $('#final_return_amount').val('₹' + totalFinal.toFixed(2));
        $('#final_return_amount_hidden').val(totalFinal.toFixed(2));
    }

    // COMMENTED OUT: Old total discount calculation (now using per-item discount)
    /*
    // Calculate discount and final return amount
    function calculateDiscount() {
        var totalAmount = parseFloat($('#total_return_amount').val().replace('₹', '')) || 0;
        var discountPercentage = parseFloat($('#discount_percentage').val()) || 0;
        
        // Validate discount percentage
        if (discountPercentage < 0) {
            discountPercentage = 0;
            $('#discount_percentage').val(0);
        }
        if (discountPercentage > 100) {
            discountPercentage = 100;
            $('#discount_percentage').val(100);
        }
        
        var discountAmount = (totalAmount * discountPercentage) / 100;
        var finalAmount = totalAmount - discountAmount;
        
        $('#discount_amount').val('₹' + discountAmount.toFixed(2));
        $('#final_return_amount').val('₹' + finalAmount.toFixed(2));
        $('#final_return_amount_hidden').val(finalAmount.toFixed(2));
    }

    // Calculate discount when discount percentage changes
    $(document).on('input', '#discount_percentage', function() {
        calculateDiscount();
    });

    // Calculate discount when discount percentage loses focus
    $(document).on('blur', '#discount_percentage', function() {
        var discountPercentage = parseFloat($(this).val()) || 0;
        if (discountPercentage < 0) {
            $(this).val(0);
        }
        if (discountPercentage > 100) {
            $(this).val(100);
        }
        calculateDiscount();
    });
    */
});
</script>
<!-- medicine_returns`      | Stores the main information about the return event (who returned it, when, why, etc.). This is the "header" record.                        | A **new row is inserted** for every return transaction.                                                                                                     |
| `medicine_return_items` | Stores details for each specific medicine in that return transaction (which batch, how many, at what price).                               | A **new row is inserted** for each medicine line item on the return form.                                                                                   |
| `medicine_batches`      | This is a master list of all medicine batches ever purchased. `quantity_remaining` tracks the total stock for that batch across the *entire* organization. | The `quantity_remaining` for the specific batch is **increased** by the returned amount.                                                                  |
| `center_stocks`         | Tracks the stock level of a specific batch *at a specific center*. This is the inventory for each location.                                | The `quantity` for the specific batch and center is **increased** by the returned amount. **This is the key step for updating center stock.**                  |
| `stock_movements`       | An audit log of every single stock movement (purchase, transfer, sale, return). This is crucial for tracking and debugging.              | A **new row is inserted** with `movement_type` = `SALE_RETURN`, showing stock moving from a "Patient/Sale" back into a "Center".                          |
| `sales` / `sales_items -->