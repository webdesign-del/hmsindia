<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-eye"></i> Transfer Details
                    <small>View transfer information and items</small>
                </h1>
            </div>
        </div>
        
        <!-- Transfer Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Transfer Information
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Transfer Number:</strong> <?php echo $transfer->transfer_number; ?></p>
                                <p><strong>Type:</strong> <?php echo str_replace('_', ' ', ucfirst($transfer->transfer_type)); ?></p>
                                <p><strong>From:</strong> <?php echo $transfer->from_center ?: 'Central Warehouse'; ?></p>
                                <p><strong>To:</strong> <?php echo $transfer->to_center; ?></p>
                                <?php if($transfer->from_department): ?>
                                    <p><strong>From Department:</strong> <?php echo $transfer->from_department; ?></p>
                                <?php endif; ?>
                                <?php if($transfer->to_department): ?>
                                    <p><strong>To Department:</strong> <?php echo $transfer->to_department; ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($transfer->transfer_date)); ?></p>
                                <p><strong>Status:</strong> 
                                    <span class="badge <?php 
                                        echo $transfer->status == 'COMPLETED' ? 'badge-success' : 
                                            ($transfer->status == 'APPROVED' ? 'badge-info' : 'badge-warning'); 
                                    ?>">
                                        <?php echo $transfer->status; ?>
                                    </span>
                                </p>
                                <p><strong>Items:</strong> <?php echo count($transfer_items); ?></p>
                                <p><strong>Total Value:</strong> ₹<?php echo number_format(array_sum(array_column($transfer_items, 'total_price')), 2); ?></p>
                                <?php if($transfer->approved_by): ?>
                                    <p><strong>Approved By:</strong> <?php echo $transfer->approved_by; ?></p>
                                <?php endif; ?>
                                <?php if($transfer->approved_date): ?>
                                    <p><strong>Approved Date:</strong> <?php echo date('M d, Y H:i', strtotime($transfer->approved_date)); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if($transfer->remarks): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                    <p><strong>Remarks:</strong> <?php echo $transfer->remarks; ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transfer Items List -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Transfer Items
                        <span class="badge pull-right"><?php echo count($transfer_items); ?> items</span>
                    </div>
                    <div class="panel-body">
                        <?php if(!empty($transfer_items)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Medicine</th>
                                            <th>Brand</th>
                                            <th>Batch</th>
                                            <th>Expiry Date</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total Price</th>
                                            <?php if($transfer->status == 'DRAFT'): ?>
                                                <th>Actions</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1; foreach($transfer_items as $item): ?>
                                            <tr>
                                                <td><?php echo $counter++; ?></td>
                                                <td><?php echo $item->medicine_name; ?></td>
                                                <td><?php echo $item->brand_name; ?></td>
                                                <td><?php echo $item->batch_number; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($item->expiry_date)); ?></td>
                                                <td><?php echo number_format($item->quantity_transferred); ?></td>
                                                <td>₹<?php echo number_format($item->unit_price, 2); ?></td>
                                                <td>₹<?php echo number_format($item->total_price, 2); ?></td>
                                                <?php if($transfer->status == 'DRAFT'): ?>
                                                    <td>
                                                        <a href="<?php echo base_url('stocks_new/edit_transfer/' . $transfer->id); ?>" 
                                                           class="btn btn-primary btn-sm">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="info">
                                            <th colspan="7">Total</th>
                                            <th>₹<?php echo number_format(array_sum(array_column($transfer_items, 'total_price')), 2); ?></th>
                                            <?php if($transfer->status == 'DRAFT'): ?>
                                                <th></th>
                                            <?php endif; ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fa fa-info-circle fa-2x"></i><br>
                                No items found in this transfer.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transfer Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-cog"></i> Actions
                    </div>
                    <div class="panel-body">
                        <?php if($transfer->status == 'DRAFT'): ?>
                            <a href="<?php echo base_url('stocks_new/edit_transfer/' . $transfer->id); ?>" 
                               class="btn btn-primary">
                                <i class="fa fa-edit"></i> Edit Transfer
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo base_url('stocks_new/transfers'); ?>" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Transfers
                        </a>
                        
                        <button onclick="window.print()" class="btn btn-info">
                            <i class="fa fa-print"></i> Print Transfer
                        </button>
                        
                        <?php if($transfer->status == 'APPROVED' || $transfer->status == 'COMPLETED'): ?>
                            <span class="badge badge-info pull-right">
                                <i class="fa fa-check"></i> Transfer Processed
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transfer Summary -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Transfer Summary
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4><?php echo count($transfer_items); ?></h4>
                                    <p class="text-muted">Total Items</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4>₹<?php echo number_format(array_sum(array_column($transfer_items, 'total_price')), 2); ?></h4>
                                    <p class="text-muted">Total Value</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4><?php echo $transfer->status; ?></h4>
                                    <p class="text-muted">Status</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4><?php echo date('M d, Y', strtotime($transfer->transfer_date)); ?></h4>
                                    <p class="text-muted">Transfer Date</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .panel-heading, .btn, .page-header {
        display: none !important;
    }
    .panel {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
    .table {
        font-size: 12px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Add any additional JavaScript functionality here if needed
});
</script>
