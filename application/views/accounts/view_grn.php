<div class="col-sm-12 col-xs-12">
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="col-sm-12 col-xs-12" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
            <h4 style="margin: 0; color: #155724;"><?php echo $this->session->flashdata('success'); ?></h4>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-sm-12 col-xs-12 panel panel-piluku">
            <div class="panel-heading text-center">
                <h3 class="heading">Goods Receipt Note (GRN) Details</h3>
            </div>

            <div class="panel-body profile-edit">
                <!-- GRN Information -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>GRN Information</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <strong>GRN Number:</strong><br>
                                        <?php echo $grn['grn_number']; ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <strong>PO Number:</strong><br>
                                        <?php echo $grn['po_number']; ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <strong>GRN Date:</strong><br>
                                        <?php echo date('d M Y', strtotime($grn['grn_date'])); ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <strong>Status:</strong><br>
                                        <?php if ($grn['status'] == '1'): ?>
                                            <span class="label label-success">Active</span>
                                        <?php else: ?>
                                            <span class="label label-default">Inactive</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-sm-3">
                                        <strong>Vendor:</strong><br>
                                        <?php echo $grn['vendor_name']; ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <strong>Centre:</strong><br>
                                        <?php 
                                        $all_method = &get_instance();
                                        echo $all_method->get_center_name_by_number($grn['centre']); 
                                        ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <strong>Department:</strong><br>
                                        <?php echo $grn['department']; ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <strong>Received By:</strong><br>
                                        <?php echo $grn['received_by']; ?>
                                    </div>
                                </div>
                                <?php if (!empty($grn['inspected_by'])): ?>
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-sm-3">
                                        <strong>Inspected By:</strong><br>
                                        <?php echo $grn['inspected_by']; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($grn['remarks'])): ?>
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-sm-12">
                                        <strong>Remarks:</strong><br>
                                        <?php echo nl2br(htmlspecialchars($grn['remarks'])); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- GRN Items -->
                <div class="row">
                    <div class="col-md-12">
                        <h4>GRN Items</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Item Description</th>
                                        <th>PO Quantity</th>
                                        <th>Received Quantity</th>
                                        <th>Rate</th>
                                        <th>GST %</th>
                                        <th>Basic Total</th>
                                        <th>GST Amount</th>
                                        <th>Condition</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $serial = 1;
                                    $total_qty = 0;
                                    $total_received_qty = 0;
                                    $total_basic = 0;
                                    $total_gst = 0;
                                    
                                    if (!empty($grn_items)) {
                                        foreach ($grn_items as $item) {
                                            $total_qty += $item['quantity'];
                                            $total_received_qty += $item['received_quantity'];
                                            $total_basic += $item['basic_total'];
                                            $total_gst += $item['gst_amount'];
                                    ?>
                                    <tr>
                                        <td><?php echo $serial; ?></td>
                                        <td><?php echo htmlspecialchars($item['item_description']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo $item['received_quantity']; ?></td>
                                        <td>₹<?php echo number_format($item['rate'], 2); ?></td>
                                        <td><?php echo $item['gst_rate']; ?>%</td>
                                        <td>₹<?php echo number_format($item['basic_total'], 2); ?></td>
                                        <td>₹<?php echo number_format($item['gst_amount'], 2); ?></td>
                                        <td>
                                            <?php 
                                            $condition_class = 'label-default';
                                            if ($item['item_condition'] == 'Good') $condition_class = 'label-success';
                                            elseif ($item['item_condition'] == 'Damaged') $condition_class = 'label-danger';
                                            elseif ($item['item_condition'] == 'Rejected') $condition_class = 'label-danger';
                                            ?>
                                            <span class="label <?php echo $condition_class; ?>"><?php echo $item['item_condition']; ?></span>
                                        </td>
                                    </tr>
                                    <?php 
                                            $serial++;
                                        }
                                    } else {
                                        echo '<tr><td colspan="9" class="text-center">No items found</td></tr>';
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td><strong><?php echo $total_qty; ?></strong></td>
                                        <td><strong><?php echo $total_received_qty; ?></strong></td>
                                        <td colspan="2"></td>
                                        <td><strong>₹<?php echo number_format($total_basic, 2); ?></strong></td>
                                        <td><strong>₹<?php echo number_format($total_gst, 2); ?></strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-center mt-3">
                        <a href="<?php echo base_url('accounts/grn-list'); ?>" class="btn btn-default">Back to GRN List</a>
                        <a href="<?php echo base_url('accounts/purchase-orders-list'); ?>" class="btn btn-info">View Purchase Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

