<style>
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
    .po-info-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .po-info-box h4 {
        margin-top: 0;
        color: #333;
    }
</style>

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

    <form action="<?php echo base_url('accounts/save_grn'); ?>" method="post" class="col-sm-12 col-xs-12" enctype="multipart/form-data">
        <input type="hidden" name="po_number" value="<?php echo $purchase_order['po_number']; ?>">
        <input type="hidden" name="grn_number" value="<?php echo $grn_number; ?>">

        <div class="row">
            <div class="col-sm-12 col-xs-12 panel panel-piluku">
                <div class="panel-heading text-center">
                    <h3 class="heading">Create Goods Receipt Note (GRN)</h3>
                </div>

                <div class="panel-body profile-edit">
                    <!-- PO Information Box -->
                    <div class="po-info-box">
                        <h4>Purchase Order Information</h4>
                        <div class="row">
                            <div class="col-sm-3">
                                <strong>PO Number:</strong> <?php echo $purchase_order['po_number']; ?>
                            </div>
                            <div class="col-sm-3">
                                <strong>Vendor:</strong> <?php echo $purchase_order['po_name_of_vendor']; ?>
                            </div>
                            <div class="col-sm-3">
                                <strong>Department:</strong> <?php echo $purchase_order['po_department']; ?>
                            </div>
                            <div class="col-sm-3">
                                <strong>PO Total:</strong> ₹<?php echo number_format($purchase_order['po_po_total'], 2); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label><strong>GRN Number</strong> *</label>
                            <input type="text" name="grn_number_display" class="form-control" value="<?php echo $grn_number; ?>" readonly>
                        </div>

                        <div class="form-group col-sm-4">
                            <label><strong>GRN Date</strong> *</label>
                            <input type="date" name="grn_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <div class="form-group col-sm-4">
                            <label><strong>Received By</strong> *</label>
                            <input type="text" name="received_by" class="form-control" placeholder="Enter name" required>
                        </div>

                        <div class="form-group col-sm-4">
                            <label><strong>Inspected By</strong></label>
                            <input type="text" name="inspected_by" class="form-control" placeholder="Enter name">
                        </div>

                        <div class="form-group col-sm-8">
                            <label><strong>Remarks</strong></label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="Enter remarks..."></textarea>
                        </div>
                    </div>

                    <hr>

                    <!-- GRN Items Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4>GRN Items</h4>
                            <table class="table item-table" id="grn_items_table">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th style="width: 25%;">Item Description</th>
                                        <th style="width: 10%;">PO Quantity</th>
                                        <th style="width: 10%;">Received Qty *</th>
                                        <th style="width: 10%;">Rate</th>
                                        <th style="width: 8%;">GST %</th>
                                        <th style="width: 12%;">Basic Total</th>
                                        <th style="width: 12%;">GST Amount</th>
                                        <th style="width: 10%;">Condition</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $serial = 1;
                                    $total_items = 0;
                                    $total_qty = 0;
                                    if (!empty($po_items)) {
                                        foreach ($po_items as $item) {
                                            $total_items++;
                                            $qty = isset($item['quantity']) ? $item['quantity'] : 0;
                                            $rate = isset($item['rate']) ? $item['rate'] : 0;
                                            // Try to get GST rate from various possible field names
                                            $gst_rate = 0;
                                            if (isset($item['item_gst_rate'])) {
                                                $gst_rate = $item['item_gst_rate'];
                                            } elseif (isset($item['gst_rate'])) {
                                                $gst_rate = $item['gst_rate'];
                                            } elseif (isset($item['tax_percentage'])) {
                                                $gst_rate = $item['tax_percentage'];
                                            }
                                            
                                            // Calculate totals
                                            $basic_total = $qty * $rate;
                                            $gst_amount = $basic_total * ($gst_rate / 100);
                                            $total_qty += $qty;
                                            
                                            $item_desc = isset($item['item_description']) ? $item['item_description'] : 
                                                        (isset($item['item_name']) ? $item['item_name'] : 'N/A');
                                    ?>
                                    <tr>
                                        <td><?php echo $serial; ?></td>
                                        <td>
                                            <input type="hidden" name="item_description[]" value="<?php echo htmlspecialchars($item_desc); ?>">
                                            <?php echo htmlspecialchars($item_desc); ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="quantity[]" value="<?php echo $qty; ?>">
                                            <?php echo $qty; ?>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="received_quantity[]" class="form-control grn-item-calc" 
                                                   value="<?php echo $qty; ?>" min="0" max="<?php echo $qty * 1.1; ?>" required>
                                        </td>
                                        <td>
                                            <input type="hidden" name="rate[]" value="<?php echo $rate; ?>">
                                            <?php echo number_format($rate, 2); ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="gst_rate[]" value="<?php echo $gst_rate; ?>">
                                            <?php echo $gst_rate; ?>%
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="basic_total[]" class="form-control item-basic-total" 
                                                   value="<?php echo number_format($basic_total, 2); ?>" readonly>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="gst_amount[]" class="form-control item-gst-amount" 
                                                   value="<?php echo number_format($gst_amount, 2); ?>" readonly>
                                        </td>
                                        <td>
                                            <select name="item_condition[]" class="form-control">
                                                <option value="Good">Good</option>
                                                <option value="Damaged">Damaged</option>
                                                <option value="Partial">Partial</option>
                                                <option value="Rejected">Rejected</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php 
                                            $serial++;
                                        }
                                    } else {
                                        echo '<tr><td colspan="9" class="text-center">No items found in Purchase Order</td></tr>';
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="total-cell"><strong>Total</strong></td>
                                        <td id="tfoot_qty_total" class="total-cell"><strong><?php echo $total_qty; ?></strong></td>
                                        <td id="tfoot_received_qty_total" class="total-cell"><strong>0</strong></td>
                                        <td colspan="2"></td>
                                        <td id="tfoot_basic_total" class="total-cell"><strong>0.00</strong></td>
                                        <td id="tfoot_gst_total" class="total-cell"><strong>0.00</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <input type="hidden" name="total_items" value="<?php echo $total_items; ?>">
                            <input type="hidden" name="total_quantity" value="<?php echo $total_qty; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary">Save GRN</button>
                            <a href="<?php echo base_url('accounts/purchase-orders-list'); ?>" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Calculate totals when received quantity changes
        $("#grn_items_table").on('input', '.grn-item-calc', function() {
            var $row = $(this).closest('tr');
            var receivedQty = parseFloat($(this).val()) || 0;
            var rate = parseFloat($row.find('input[name="rate[]"]').val()) || 0;
            var gstRate = parseFloat($row.find('input[name="gst_rate[]"]').val()) || 0;
            
            // Calculate item totals based on received quantity
            var rowBasicTotal = receivedQty * rate;
            var rowGstAmount = rowBasicTotal * (gstRate / 100);
            
            $row.find('input[name="basic_total[]"]').val(rowBasicTotal.toFixed(2));
            $row.find('input[name="gst_amount[]"]').val(rowGstAmount.toFixed(2));
            
            calculateGrandTotal();
        });
        
        function calculateGrandTotal() {
            var totalQty = 0;
            var totalReceivedQty = 0;
            var totalBasic = 0;
            var totalGst = 0;
            
            $("#grn_items_table tbody tr").each(function() {
                var qty = parseFloat($(this).find('input[name="quantity[]"]').val()) || 0;
                var receivedQty = parseFloat($(this).find('input[name="received_quantity[]"]').val()) || 0;
                var basic = parseFloat($(this).find('input[name="basic_total[]"]').val()) || 0;
                var gst = parseFloat($(this).find('input[name="gst_amount[]"]').val()) || 0;
                
                totalQty += qty;
                totalReceivedQty += receivedQty;
                totalBasic += basic;
                totalGst += gst;
            });
            
            $("#tfoot_qty_total").html('<strong>' + totalQty.toFixed(2) + '</strong>');
            $("#tfoot_received_qty_total").html('<strong>' + totalReceivedQty.toFixed(2) + '</strong>');
            $("#tfoot_basic_total").html('<strong>' + totalBasic.toFixed(2) + '</strong>');
            $("#tfoot_gst_total").html('<strong>' + totalGst.toFixed(2) + '</strong>');
        }
        
        // Calculate initial totals
        calculateGrandTotal();
    });
</script>

