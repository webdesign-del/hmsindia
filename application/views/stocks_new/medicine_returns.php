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
                                            <select name="center_id" class="form-control" required>
                                                <option value="">Select Center</option>
                                                <?php foreach($centers as $center): ?>
                                                    <option value="<?php echo $center->ID; ?>" <?php echo set_select('center_id', $center->ID); ?>>
                                                        <?php echo $center->center_name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
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
                                                    <th>Unit Price</th>
                                                    <th>Return Amount</th>
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
            '<option value="<?php echo isset($batch->batch_id) ? $batch->batch_id : $batch->id; ?>" data-medicine="<?php echo isset($batch->medicine_name) ? $batch->medicine_name : 'Unknown'; ?>" data-batch-number="<?php echo isset($batch->batch_number) ? $batch->batch_number : 'N/A'; ?>" data-price="<?php echo isset($batch->selling_price) ? $batch->selling_price : 0; ?>" data-sold-qty="<?php echo isset($batch->quantity_sold) ? $batch->quantity_sold : 0; ?>"><?php echo (isset($batch->medicine_name) ? $batch->medicine_name : 'Unknown') . ' - ' . (isset($batch->batch_number) ? $batch->batch_number : 'N/A'); ?></option>' +
            '<?php endforeach; ?>' +
            '<?php endif; ?>' +
            '</select></td>' +
            '<td><span class="batch_number">-</span></td>' +
            '<td><span class="sold_quantity">-</span></td>' +
            '<td><input type="number" name="return_items[' + rowCount + '][return_quantity]" class="form-control return_quantity" min="1" required></td>' +
            '<td><span class="unit_price">-</span><input type="hidden" name="return_items[' + rowCount + '][price]" class="hidden_price" value="0"></td>' +
            '<td><span class="return_amount">-</span></td>' +
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
            var price = selectedOption.data('price');
            var pack_size =selectedOption.data('pack-size');
            var unitPrice_MRP = selectedOption.data('price')/pack_size;
            row.find('.batch_number').text(selectedOption.data('batch-number'));
            row.find('.sold_quantity').text(selectedOption.data('sold-qty'));
            row.find('.unit_price').text('₹' + unitPrice_MRP);
            row.find('.hidden_price').val(price);
            row.find('.return_quantity').attr('max', selectedOption.data('sold-qty'));
        } else {
            row.find('.batch_number, .sold_quantity, .unit_price').text('-');
            row.find('.hidden_price').val(0);
        }
    });

    // Calculate return amount
    $(document).on('input', '.return_quantity', function() {
        var row = $(this).closest('tr');
        var quantity = parseFloat($(this).val()) || 0;
        var unitPrice = parseFloat(row.find('.unit_price').text().replace('₹', '')) || 0;
        var returnAmount = quantity * unitPrice;

        row.find('.return_amount').text('₹' + returnAmount.toFixed(2));
        calculateTotal();
    });

    // Calculate total return amount
    function calculateTotal() {
        var total = 0;
        $('.return_amount').each(function() {
            var amount = parseFloat($(this).text().replace('₹', '')) || 0;
            total += amount;
        });
        $('#total_return_amount').val('₹' + total.toFixed(2));
    }
});
</script>
<!-- medicine_returns`      | Stores the main information about the return event (who returned it, when, why, etc.). This is the "header" record.                        | A **new row is inserted** for every return transaction.                                                                                                     |
| `medicine_return_items` | Stores details for each specific medicine in that return transaction (which batch, how many, at what price).                               | A **new row is inserted** for each medicine line item on the return form.                                                                                   |
| `medicine_batches`      | This is a master list of all medicine batches ever purchased. `quantity_remaining` tracks the total stock for that batch across the *entire* organization. | The `quantity_remaining` for the specific batch is **increased** by the returned amount.                                                                  |
| `center_stocks`         | Tracks the stock level of a specific batch *at a specific center*. This is the inventory for each location.                                | The `quantity` for the specific batch and center is **increased** by the returned amount. **This is the key step for updating center stock.**                  |
| `stock_movements`       | An audit log of every single stock movement (purchase, transfer, sale, return). This is crucial for tracking and debugging.              | A **new row is inserted** with `movement_type` = `SALE_RETURN`, showing stock moving from a "Patient/Sale" back into a "Center".                          |
| `sales` / `sales_items -->