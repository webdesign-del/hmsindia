<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-eye"></i> View Medicine Return
                    <small>Return details and items</small>
                </h1>
            </div>
        </div>
        
        <!-- Return Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Return Details
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Return Number:</strong> <?php echo $return->return_number; ?></p>
                                <p><strong>Patient:</strong> <?php echo $return->patient_name; ?></p>
                                <p><strong>Receipt Number:</strong> <?php echo $return->receipt_number; ?></p>
                                <p><strong>Center:</strong> <?php echo $return->center_name; ?></p>
                                <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($return->return_date)); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Return Reason:</strong> 
                                    <span class="badge badge-info"><?php echo $return->return_reason; ?></span>
                                </p>
                                <p><strong>Total Items:</strong> <?php echo $return->total_items ?? 0; ?></p>
                                <p><strong>Total Quantity:</strong> <?php echo $return->total_quantity ?? 0; ?></p>
                                <p><strong>Total Return Amount:</strong> ₹<?php echo number_format($return->total_return_amount ?? 0, 2); ?></p>
                                <p><strong>Remarks:</strong> <?php echo $return->remarks; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Return Items List -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Return Items
                        <span class="badge pull-right"><?php echo count($return_items); ?> items</span>
                    </div>
                    <div class="panel-body">
                        <?php if(!empty($return_items)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Brand</th>
                                            <th>Batch</th>
                                            <th>Expiry Date</th>
                                            <th>Quantity Returned</th>
                                            <th>Unit Price</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($return_items as $item): ?>
                                            <tr>
                                                <td><?php echo $item->medicine_name; ?></td>
                                                <td><?php echo $item->brand_name; ?></td>
                                                <td><?php echo $item->batch_number; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($item->expiry_date)); ?></td>
                                                <td><?php echo number_format($item->quantity_returned); ?></td>
                                                <td>₹<?php echo number_format($item->return_price, 2); ?></td>
                                                <td>₹<?php echo number_format($item->total_amount, 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="info">
                                            <th colspan="6">Total</th>
                                            <th>₹<?php echo number_format($return->total_return_amount ?? 0, 2); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fa fa-info-circle fa-2x"></i><br>
                                No return items found.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Return Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-cog"></i> Actions
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo base_url('stocks_new/returns'); ?>" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Returns List
                        </a>
                        <a href="<?php echo base_url('stocks_new/medicine_returns'); ?>" class="btn btn-primary">
                            <i class="fa fa-undo"></i> New Return
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

