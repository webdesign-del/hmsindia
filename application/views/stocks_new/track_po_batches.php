<?php
// Purchase Order Batch Tracking - Where medicine batches from PO are distributed
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-truck"></i> Purchase Order Batch Tracking
                        <?php if(isset($purchase_order) && $purchase_order): ?>
                            - PO #<?php echo htmlspecialchars($purchase_order->po_number); ?>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="panel-body">
                    
                    <!-- Purchase Order Information -->
                    <?php if(isset($purchase_order) && $purchase_order): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Purchase Order Details</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>PO Number:</strong><br>
                                            <?php echo htmlspecialchars($purchase_order->po_number); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>PO Date:</strong><br>
                                            <?php echo date('d-m-Y', strtotime($purchase_order->po_date)); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Status:</strong><br>
                                            <span class="label label-<?php echo ($purchase_order->status == 'completed') ? 'success' : 'warning'; ?>">
                                                <?php echo strtoupper($purchase_order->status); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Total Value:</strong><br>
                                            ₹<?php echo number_format($purchase_order->total_amount, 2); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Summary Statistics -->
                    <?php if(isset($summary_stats) && !empty($summary_stats)): ?>
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-3">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Total Batches</h4>
                                </div>
                                <div class="panel-body">
                                    <h2><?php echo $summary_stats['total_batches']; ?></h2>
                                    <p>Batches created from this PO</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">In Central Stock</h4>
                                </div>
                                <div class="panel-body">
                                    <h2><?php echo $summary_stats['batches_in_central']; ?></h2>
                                    <p>Batches still in central warehouse</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Transferred</h4>
                                </div>
                                <div class="panel-body">
                                    <h2><?php echo $summary_stats['batches_transferred']; ?></h2>
                                    <p>Batches transferred to centers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Sold</h4>
                                </div>
                                <div class="panel-body">
                                    <h2><?php echo $summary_stats['batches_sold']; ?></h2>
                                    <p>Batches sold to patients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quantity Summary -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Quantity Summary</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-condensed">
                                        <tr>
                                            <td><strong>Total Received:</strong></td>
                                            <td><?php echo $summary_stats['total_quantity_received']; ?> units</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Still Available:</strong></td>
                                            <td><?php echo $summary_stats['total_quantity_remaining']; ?> units</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Distributed:</strong></td>
                                            <td><?php echo $summary_stats['total_quantity_distributed']; ?> units</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Value:</strong></td>
                                            <td>₹<?php echo number_format($summary_stats['total_value'], 2); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Distribution Status</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" style="width: <?php echo $summary_stats['total_batches'] > 0 ? ($summary_stats['batches_in_central'] / $summary_stats['total_batches'] * 100) : 0; ?>%">
                                            Central: <?php echo $summary_stats['batches_in_central']; ?>
                                        </div>
                                        <div class="progress-bar progress-bar-warning" style="width: <?php echo $summary_stats['total_batches'] > 0 ? ($summary_stats['batches_transferred'] / $summary_stats['total_batches'] * 100) : 0; ?>%">
                                            Transferred: <?php echo $summary_stats['batches_transferred']; ?>
                                        </div>
                                        <div class="progress-bar progress-bar-danger" style="width: <?php echo $summary_stats['total_batches'] > 0 ? ($summary_stats['batches_sold'] / $summary_stats['total_batches'] * 100) : 0; ?>%">
                                            Sold: <?php echo $summary_stats['batches_sold']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Batch Details and Movements -->
                    <?php if(isset($po_batches) && !empty($po_batches)): ?>
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Batch Details and Movement History</h4>
                                </div>
                                <div class="panel-body">
                                    <?php foreach($po_batches as $batch): ?>
                                    <div class="panel panel-default" style="margin-bottom: 15px;">
                                        <div class="panel-heading">
                                            <h5 class="panel-title">
                                                <a data-toggle="collapse" href="#batch_<?php echo $batch->id; ?>" aria-expanded="false">
                                                    <i class="fa fa-chevron-down"></i>
                                                    <?php echo htmlspecialchars($batch->medicine_name); ?> - 
                                                    Batch: <?php echo htmlspecialchars($batch->batch_number); ?>
                                                    <span class="label label-info pull-right">
                                                        <?php echo $batch->quantity_remaining; ?>/<?php echo $batch->quantity_purchased; ?> units
                                                    </span>
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="batch_<?php echo $batch->id; ?>" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table class="table table-condensed">
                                                            <tr>
                                                                <td><strong>Medicine:</strong></td>
                                                                <td><?php echo htmlspecialchars($batch->medicine_name); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Batch Number:</strong></td>
                                                                <td><?php echo htmlspecialchars($batch->batch_number); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Vendor:</strong></td>
                                                                <td><?php echo htmlspecialchars($batch->vendor_name); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Expiry Date:</strong></td>
                                                                <td><?php echo date('d-m-Y', strtotime($batch->expiry_date)); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Purchase Price:</strong></td>
                                                                <td>₹<?php echo number_format($batch->purchase_price, 2); ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table table-condensed">
                                                            <tr>
                                                                <td><strong>Quantity Received:</strong></td>
                                                                <td><?php echo $batch->quantity_purchased; ?> units</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Quantity Remaining:</strong></td>
                                                                <td><?php echo $batch->quantity_remaining; ?> units</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Quantity Distributed:</strong></td>
                                                                <td><?php echo ($batch->quantity_purchased - $batch->quantity_remaining); ?> units</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Invoice Number:</strong></td>
                                                                <td><?php echo htmlspecialchars($batch->invoice_no ?: 'N/A'); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Status:</strong></td>
                                                                <td>
                                                                    <span class="label label-<?php echo ($batch->batch_status == 'ACTIVE') ? 'success' : 'default'; ?>">
                                                                        <?php echo $batch->batch_status; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                
                                                <!-- Movement History for this batch -->
                                                <?php if(isset($batch_movements[$batch->id]) && !empty($batch_movements[$batch->id])): ?>
                                                <div class="row" style="margin-top: 15px;">
                                                    <div class="col-md-12">
                                                        <h6><strong>Movement History:</strong></h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-condensed">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Date</th>
                                                                        <th>Type</th>
                                                                        <th>From</th>
                                                                        <th>To</th>
                                                                        <th>Quantity</th>
                                                                        <th>Patient/Customer</th>
                                                                        <th>Reference</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach($batch_movements[$batch->id] as $movement): ?>
                                                                    <tr>
                                                                        <td><?php echo date('d-m-Y H:i', strtotime($movement->created_at)); ?></td>
                                                                        <td>
                                                                            <span class="label label-<?php echo ($movement->movement_type == 'In') ? 'success' : 'danger'; ?>">
                                                                                <?php echo $movement->movement_type; ?>
                                                                            </span>
                                                                        </td>
                                                                        <td><?php echo htmlspecialchars($movement->from_center ?: 'Central'); ?></td>
                                                                        <td><?php echo htmlspecialchars($movement->to_center ?: 'Central'); ?></td>
                                                                        <td><?php echo $movement->quantity; ?></td>
                                                                        <td><?php echo htmlspecialchars($movement->patient_name ?: 'N/A'); ?></td>
                                                                        <td><?php echo htmlspecialchars($movement->reference_number ?: 'N/A'); ?></td>
                                                                    </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php else: ?>
                                                <div class="row" style="margin-top: 15px;">
                                                    <div class="col-md-12">
                                                        <div class="alert alert-info">
                                                            <i class="fa fa-info-circle"></i>
                                                            No movement history found for this batch. It may still be in central stock.
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i>
                                No batches found for this purchase order. The medicine may not have been added to stock yet.
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Navigation -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="btn-group">
                                <a href="<?php echo base_url('new_purchase_orders/view/' . $po_id); ?>" class="btn btn-info">
                                    <i class="fa fa-eye"></i> View Purchase Order
                                </a>
                                <a href="<?php echo base_url('stocks_new/stock_tracking_panel'); ?>" class="btn btn-default">
                                    <i class="fa fa-search"></i> Stock Tracking Panel
                                </a>
                                <a href="<?php echo base_url('stocks_new/dashboard'); ?>" class="btn btn-default">
                                    <i class="fa fa-dashboard"></i> Stock Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-expand first batch if only one batch
    <?php if(isset($po_batches) && count($po_batches) == 1): ?>
    $('#batch_<?php echo $po_batches[0]->id; ?>').collapse('show');
    <?php endif; ?>
});
</script>
