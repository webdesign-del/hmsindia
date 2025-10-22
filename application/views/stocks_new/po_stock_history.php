<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-history"></i> Purchase Order Stock History
            <small>View processed purchase orders and stock additions</small>
        </h1>
    </div>
</div>

<!-- Breadcrumb -->
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>"><i class="fa fa-dashboard"></i> Stock Dashboard</a></li>
            <li><a href="<?php echo base_url('stocks_new/purchase_orders_for_stock'); ?>"><i class="fa fa-shopping-cart"></i> Purchase Orders</a></li>
            <li class="active"><i class="fa fa-history"></i> History</li>
        </ol>
    </div>
</div>

<!-- Action Buttons -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> Quick Actions
            </div>
            <div class="panel-body">
                <a href="<?php echo base_url('stocks_new/purchase_orders_for_stock'); ?>" class="btn btn-primary">
                    <i class="fa fa-shopping-cart"></i> Back to Purchase Orders
                </a>
                <a href="<?php echo base_url('stocks_new/po_stock_history'); ?>" class="btn btn-info">
                    <i class="fa fa-refresh"></i> Refresh History
                </a>
                <a href="<?php echo base_url('stocks_new/dashboard'); ?>" class="btn btn-default">
                    <i class="fa fa-dashboard"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Processed Purchase Orders Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Processed Purchase Orders
                <span class="badge pull-right"><?php echo isset($processed_pos) ? count($processed_pos) : 0; ?> orders</span>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="processedPOsTable">
                        <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>Vendor</th>
                                <th>PO Date</th>
                                <th>Stock Added Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($processed_pos) && !empty($processed_pos)): ?>
                                <?php foreach($processed_pos as $po): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo isset($po->po_number) ? htmlspecialchars($po->po_number) : 'N/A'; ?></strong>
                                        </td>
                                        <td><?php echo isset($po->vendor_name) ? htmlspecialchars($po->vendor_name) : 'N/A'; ?></td>
                                        <td><?php echo isset($po->created_at) && !empty($po->created_at) ? date('M d, Y', strtotime($po->created_at)) : 'N/A'; ?></td>
                                        <td>
                                            <span class="badge badge-success">
                                                <?php echo isset($po->stock_added_at) && !empty($po->stock_added_at) ? date('M d, Y H:i', strtotime($po->stock_added_at)) : 'N/A'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">₹<?php echo isset($po->total_amount) && is_numeric($po->total_amount) ? number_format($po->total_amount, 2) : '0.00'; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success"><?php echo isset($po->status) ? htmlspecialchars($po->status) : 'N/A'; ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo base_url('new_purchase_orders/view/' . (isset($po->id) ? $po->id : '')); ?>" class="btn btn-info btn-sm" target="_blank">
                                                    <i class="fa fa-eye"></i> View PO
                                                </a>
                                                <a href="<?php echo base_url('stocks_new/batches?po_id=' . (isset($po->id) ? $po->id : '')); ?>" class="btn btn-success btn-sm">
                                                    <i class="fa fa-boxes"></i> View Batches
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fa fa-info-circle fa-2x"></i><br>
                                        No processed purchase orders found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($processed_pos) ? count($processed_pos) : 0; ?></div>
                        <div>Processed POs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-check-circle fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($processed_pos) ? count(array_filter($processed_pos, function($po) { return isset($po->status) && $po->status == 'completed'; })) : 0; ?></div>
                        <div>Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-rupee fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">₹<?php 
                            $total_amount = 0;
                            if(isset($processed_pos) && !empty($processed_pos)) {
                                foreach($processed_pos as $po) {
                                    if(isset($po->total_amount) && is_numeric($po->total_amount)) {
                                        $total_amount += $po->total_amount;
                                    }
                                }
                            }
                            echo number_format($total_amount, 0);
                        ?></div>
                        <div>Total Value</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-calendar fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php 
                            $this_month = 0;
                            if(isset($processed_pos) && !empty($processed_pos)) {
                                foreach($processed_pos as $po) {
                                    if(isset($po->stock_added_at) && !empty($po->stock_added_at)) {
                                        $added_date = strtotime($po->stock_added_at);
                                        if(date('Y-m', $added_date) == date('Y-m')) {
                                            $this_month++;
                                        }
                                    }
                                }
                            }
                            echo $this_month;
                        ?></div>
                        <div>This Month</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Information Panel -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Purchase Order Stock Processing Information
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Processing Status:</h4>
                        <ul>
                            <li><strong>Completed:</strong> Purchase order has been fully processed</li>
                            <li><strong>Stock Added:</strong> All items have been added to inventory</li>
                            <li><strong>Billing Created:</strong> Vendor billing records generated</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4>Available Actions:</h4>
                        <ul>
                            <li><strong>View PO:</strong> See original purchase order details</li>
                            <li><strong>View Batches:</strong> Check created medicine batches</li>
                            <li><strong>Audit Trail:</strong> Complete processing history</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Check if table has valid data before initializing DataTables
    var table = $('#processedPOsTable');
    var tbody = table.find('tbody');
    var rows = tbody.find('tr');
    var validRows = rows.filter(function() {
        // Check if row has proper number of cells and is not the "no data" row
        return $(this).find('td').length === 7 && !$(this).find('td[colspan]').length;
    });
    
    if(validRows.length > 0) {
        try {
            $('#processedPOsTable').DataTable({
                "pageLength": 25,
                "order": [[ 3, "desc" ]], // Sort by Stock Added Date descending
                "columnDefs": [
                    { "orderable": false, "targets": 6 }
                ],
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "No processed purchase orders available",
                    "zeroRecords": "No matching processed purchase orders found"
                },
                "processing": true,
                "deferRender": true
            });
        } catch(e) {
            console.error('DataTables initialization failed:', e);
        }
    }
});
</script>
