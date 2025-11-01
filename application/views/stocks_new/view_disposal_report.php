<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-eye"></i> View Medicine Disposal Report
            <small>Disposal details and items</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Disposal Report Details
            </div>
            <div class="panel-body">
                <?php  if (isset($disposal_report) && $disposal_report): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Disposal Number:</strong> <?php echo htmlspecialchars($disposal_report->disposal_number); ?></p>
                            <p><strong>Center:</strong> <?php echo htmlspecialchars($disposal_report->center_name ?? 'N/A'); // Assuming center_name is joined in model ?></p>
                            <p><strong>Disposal Date:</strong> <?php echo date('M d, Y', strtotime($disposal_report->disposal_date)); ?></p>
                            <p><strong>Disposal Type (Reason):</strong>
                                <span class="label label-warning"><?php echo htmlspecialchars($disposal_report->disposal_type); ?></span>
                            </p>
                             <p><strong>Disposal Method:</strong> <?php echo htmlspecialchars($disposal_report->disposal_method); ?></p>
                        </div>
                        <div class="col-md-6">
                             <p><strong>Disposal Company:</strong> <?php echo htmlspecialchars($disposal_report->disposal_company ?: 'N/A'); ?></p>
                            <p><strong>Authorized By:</strong> <?php echo htmlspecialchars($disposal_report->authorized_by); ?></p>
                            <p><strong>Total Items (Batches):</strong> <?php echo number_format($disposal_report->total_items ?? 0); ?></p>
                            <p><strong>Total Cost:</strong> ₹<?php echo number_format($disposal_report->total_cost ?? 0, 2); ?></p>
                             <p><strong>Status:</strong> <span class="label label-default"><?php echo htmlspecialchars($disposal_report->status); ?></span></p>
                            <p><strong>Processed By:</strong> <?php echo htmlspecialchars($disposal_report->created_by_name ?? 'N/A'); // Assuming created_by_name is joined in model ?></p>
                            <p><strong>Processed At:</strong> <?php echo date('M d, Y H:i:s', strtotime($disposal_report->created_at)); ?></p>
                             <p><strong>Remarks:</strong> <?php echo nl2br(htmlspecialchars($disposal_report->remarks ?: 'N/A')); ?></p>
                             <?php if ($disposal_report->disposal_certificate): ?>
                                 <p><strong>Certificate:</strong>
                                     <a href="<?php echo base_url('uploads/disposal_certificates/' . $disposal_report->disposal_certificate); ?>" target="_blank" class="btn btn-xs btn-info">
                                         <i class="fa fa-file"></i> View Certificate
                                     </a>
                                 </p>
                             <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Disposal report details not found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Disposed Items (from Stock Log)
                 <?php  if (isset($disposal_items)): ?>
                <span class="badge pull-right"><?php echo count($disposal_items); ?> entries</span>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <?php if (!empty($disposal_items)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Brand</th>
                                    <th>Batch</th>
                                    <th>Expiry Date</th>
                                    <th>Quantity Disposed</th>
                                    <th>Unit Cost</th>
                                    <th>Total Cost</th>
                                    <th>Log Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($disposal_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item->medicine_name ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($item->brand_name ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($item->batch_number ?? 'N/A'); ?></td>
                                        <td><?php echo isset($item->expiry_date) ? date('M d, Y', strtotime($item->expiry_date)) : 'N/A'; ?></td>
                                        <td><?php echo number_format(abs($item->quantity_change)); // Use abs() as quantity_change is negative ?></td>
                                        <td>₹<?php echo number_format($item->unit_price ?? 0, 2); ?></td>
                                        <td>₹<?php echo number_format($item->total_value ?? 0, 2); ?></td>
                                        <td><?php echo isset($item->log_created_at) ? date('M d, Y H:i', strtotime($item->log_created_at)) : 'N/A'; ?></td>
                                     </tr>
                                <?php endforeach; ?>
                            </tbody>
                             <tfoot>
                                <tr class="danger">
                                    <th colspan="6" style="text-align:right;">Report Total Cost:</th>
                                    <th colspan="2">₹<?php echo number_format($disposal_report->total_cost ?? 0, 2); ?></th>
                                </tr>
                             </tfoot>
                        </table>
                    </div>
                <?php elseif (isset($disposal_report)): ?>
                    <div class="text-center text-muted">
                        <i class="fa fa-info-circle fa-2x"></i><br>
                        No disposed item details found in the stock movement log for this report number (<?php echo htmlspecialchars($disposal_report->disposal_number); ?>).
                    </div>
                 <?php else: ?>
                     <div class="text-center text-muted">Report not found.</div>
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
                <a href="<?php echo base_url('stocks_new/disposal_reports'); // Link to your disposal list page ?>" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back to Disposal List
                </a>
                <a href="<?php echo base_url('stocks_new/medicine_disposal'); // Link to your create disposal page ?>" class="btn btn-danger">
                    <i class="fa fa-trash-o"></i> New Disposal
                </a>
                <!-- <button type="button" class="btn btn-info" onclick="window.print();">
                     <i class="fa fa-print"></i> Print Report
                 </button> -->
            </div>
        </div>
    </div>
</div>