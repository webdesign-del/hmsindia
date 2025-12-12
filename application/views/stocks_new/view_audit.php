<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Include Font Awesome 4 if not in your header -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    /* Simple Working Print Styles */
    @media print {
        /* Basic page setup */
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: black !important;
            background: white !important;
        }
        
        /* Hide buttons and non-essential elements */
        .btn, .print-actions, .panel-heading, .breadcrumb, .fa {
            display: none !important;
        }
        
        /* Page title */
        .page-header {
            text-align: center;
            border-bottom: 2px solid black;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        
        .page-header h1 {
            font-size: 18pt;
            margin: 0;
            color: black !important;
        }
        
        /* Remove panel borders */
        .panel {
            border: none !important;
            box-shadow: none !important;
            margin-bottom: 20px;
        }
        
        .panel-body {
            padding: 0 !important;
        }
        
        /* Fix grid layout */
        .row {
            margin: 0 !important;
        }
        
        .col-md-6, .col-md-12 {
            width: 100% !important;
            float: none !important;
            padding: 0 !important;
        }
        
        /* Table styling */
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 20px;
        }
        
        .table th {
            background-color: #f0f0f0 !important;
            border: 1px solid black !important;
            padding: 8px !important;
            text-align: center;
            font-weight: bold;
            color: black !important;
        }
        
        .table td {
            border: 1px solid black !important;
            padding: 8px !important;
            color: black !important;
        }
        
        /* Labels */
        .label {
            background-color: transparent !important;
            color: black !important;
            border: 1px solid black !important;
            padding: 2px 4px;
            font-weight: bold;
        }
        
        /* Text elements */
        p, div, span, strong {
            color: black !important;
        }
        
        /* Page breaks */
        .table {
            page-break-inside: avoid;
        }
        
        /* Make sure everything is visible and black */
        * {
            color: black !important;
            background-color: transparent !important;
        }
        
        /* Override any hidden elements that should show */
        .table, .panel-body, p, div {
            display: block !important;
        }
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-clipboard-check"></i> View Audit Report
            <small><?php echo isset($audit_report->audit_number) ? htmlspecialchars($audit_report->audit_number) : ''; ?></small>
        </h1>
    </div>
</div>

<!-- Action Buttons (Non-Print) -->
<div class="row print-actions">
    <div class="col-md-12" style="margin-bottom: 20px;">
        <a href="<?php echo base_url('stocks_new/audit_reports'); ?>" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Back to Audit List
        </a>
        <a href="<?php echo base_url('stocks_new/stock_audit'); ?>" class="btn btn-primary">
            <i class="fa fa-plus"></i> New Audit
        </a>
        <a href="<?php echo base_url('stocks_new/print_audit/' . (isset($audit_report->id) ? $audit_report->id : 0)); ?>" target="_blank" class="btn btn-info">
            <i class="fa fa-print"></i> Print Report
        </a>
    </div>
</div>

<!-- Audit Information -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Audit Report Details
            </div>
            <div class="panel-body">
                <?php if (isset($audit_report) && $audit_report): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Audit Number:</strong> <?php echo htmlspecialchars($audit_report->audit_number); ?></p>
                            <p><strong>Location Audited:</strong> <?php echo htmlspecialchars($audit_report->center_name); ?></p>
                            <p><strong>Audit Date:</strong> <?php echo date('M d, Y', strtotime($audit_report->audit_date)); ?></p>
                            <p><strong>Audit Type:</strong>
                                <span class="label label-info"><?php echo htmlspecialchars($audit_report->audit_type); ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                             <p><strong>Status:</strong> 
                                 <span class="label <?php 
                                    if($audit_report->status == 'COMPLETED' || $audit_report->status == 'APPROVED') echo 'label-success';
                                    else if($audit_report->status == 'IN_PROGRESS') echo 'label-warning';
                                    else echo 'label-default';
                                 ?>">
                                     <?php echo htmlspecialchars($audit_report->status); ?>
                                 </span>
                             </p>
                            <p><strong>Total Items Audited:</strong> <?php echo number_format($audit_report->total_items_audited ?? 0); ?></p>
                            <p><strong>Discrepancies Found:</strong> 
                                <?php if($audit_report->discrepancies_found > 0): ?>
                                    <span class="label label-danger"><?php echo number_format($audit_report->discrepancies_found); ?></span>
                                <?php else: ?>
                                     <span class="label label-success">0</span>
                                <?php endif; ?>
                            </p>
                            <p><strong>Processed By:</strong> <?php echo htmlspecialchars($audit_report->created_by_name ?? 'N/A'); ?></p>
                            <p><strong>Processed At:</strong> <?php echo date('M d, Y H:i:s', strtotime($audit_report->created_at)); ?></p>
                             <p><strong>Remarks:</strong> <?php echo nl2br(htmlspecialchars($audit_report->remarks ?: 'N/A')); ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Audit report details not found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Audit Items List (from Stock Log) -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Audit Adjustment Log (Items with Variance)
                <?php if (isset($audit_items)): ?>
                <span class="badge pull-right"><?php echo count($audit_items); ?> adjustments logged</span>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <?php if (!empty($audit_items)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Batch</th>
                                    <th>Movement Type</th>
                                    <th class="text-right">System Qty</th>
                                    <th class="text-right">Change</th>
                                    <th class="text-right">Physical Qty</th>
                                    <th class="text-right">Unit Cost</th>
                                    <th class="text-right">Value of Change</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($audit_items as $item): ?>
                                    <tr class="<?php echo ($item->quantity_change > 0) ? 'success' : 'danger'; ?>">
                                        <td><?php echo htmlspecialchars($item->medicine_name ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($item->batch_number ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if ($item->movement_type == 'ADJUSTMENT_IN'): ?>
                                                <span class="label label-success">SURPLUS</span>
                                            <?php else: ?>
                                                <span class="label label-danger">SHORTAGE</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right"><?php echo number_format($item->quantity_before); ?></td>
                                        <td class="text-right" style="font-weight:bold;">
                                            <?php echo ($item->quantity_change > 0) ? '+' : ''; echo number_format($item->quantity_change); ?>
                                        </td>
                                        <td class="text-right" style="font-weight:bold;"><?php echo number_format($item->quantity_after); ?></td>
                                        <td class="text-right">₹<?php echo number_format($item->unit_price ?? 0, 2); ?></td>
                                        <td class="text-right">₹<?php echo number_format($item->total_value ?? 0, 2); ?></td>
                                        <td><small><?php echo htmlspecialchars($item->movement_remarks ?? ''); ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <i class="fa fa-check-circle fa-2x" style="color: #5cb85c;"></i><br>
                        <p style="margin-top: 10px;">No discrepancies were found, or no items were adjusted.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
