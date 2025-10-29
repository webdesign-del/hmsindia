<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-eye"></i> View Vendor Return
            <small>Return details and items</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
            <li><a href="<?php echo base_url('stocks_new/vendor_returns'); // Link to your list page ?>">Vendor Returns</a></li>
            <li class="active"><?php echo isset($return_details->return_number) ? htmlspecialchars($return_details->return_number) : 'View Return'; ?></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Return Details
            </div>
            <div class="panel-body">
                <?php if (isset($return_details) && $return_details): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Return Number:</strong> <?php echo htmlspecialchars($return_details->return_number); ?></p>
                            <p><strong>Return Date:</strong> <?php echo date('M d, Y', strtotime($return_details->return_date)); ?></p>
                            <p><strong>Vendor:</strong> <?php echo htmlspecialchars($return_details->vendor_name ?? 'N/A'); ?></p>
                            <p><strong>Returned From (Center):</strong> <?php echo htmlspecialchars($return_details->center_name ?? 'N/A'); ?></p>
                            <p><strong>Status:</strong>
                                <span class="label label-info"><?php echo htmlspecialchars($return_details->status); ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Items (Batches):</strong> <?php echo number_format($return_details->total_items ?? 0); ?></p>
                            <p><strong>Total Quantity:</strong> <?php echo number_format($return_details->total_quantity ?? 0); ?></p>
                            <p><strong>Total Return Value (Cost):</strong> ₹<?php echo number_format($return_details->total_value ?? 0, 2); ?></p>
                            <p><strong>Reason:</strong> <?php echo htmlspecialchars($return_details->return_reason ?: 'N/A'); ?></p>
                            <p><strong>Processed By:</strong> <?php echo htmlspecialchars($return_details->created_by_name ?? 'N/A'); ?></p>
                            <p><strong>Remarks:</strong> <?php echo nl2br(htmlspecialchars($return_details->remarks ?: 'N/A')); ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">The vendor return report could not be found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Returned Items (from Stock Movement Log)
                <?php if (isset($return_items)): ?>
                <span class="badge pull-right"><?php echo count($return_items); ?> items</span>
                <?php endif; ?>
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
                                    <th>Unit Cost</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($return_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item->medicine_name ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($item->brand_name ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($item->batch_number ?? 'N/A'); ?></td>
                                        <td><?php echo isset($item->expiry_date) ? date('M d, Y', strtotime($item->expiry_date)) : 'N/A'; ?></td>
                                        <td><?php echo number_format(abs($item->quantity_change)); // quantity_change is negative ?></td>
                                        <td>₹<?php echo number_format($item->unit_price ?? 0, 2); ?></td>
                                        <td>₹<?php echo number_format(abs($item->total_value ?? 0), 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="info">
                                    <th colspan="6" style="text-align:right;">Report Total:</th>
                                    <th>₹<?php echo (isset($return_details->total_value)) ? number_format($return_details->total_value, 2) : '0.00'; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <i class="fa fa-info-circle fa-2x"></i><br>
                        No individual items were found in the stock log for this return.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-cog"></i> Actions
            </div>
            <div class="panel-body">
                <a href="<?php echo base_url('stocks_new/vendor_returns'); // Link to your list page ?>" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back to Returns List
                </a>
                <a href="<?php echo base_url('stocks_new/add_vendor_return'); // Link to your create page ?>" class="btn btn-primary">
                    <i class="fa fa-undo"></i> New Vendor Return
                </a>
                <button type="button" class="btn btn-info" onclick="window.print();">
                    <i class="fa fa-print"></i> Print Report
                </button>
            </div>
        </div>
    </div>
</div>