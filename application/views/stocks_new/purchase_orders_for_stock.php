<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-shopping-cart"></i> Purchase Orders for Stock Addition
            <small>Add medicines to stock from approved purchase orders</small>
        </h1>
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
                    <i class="fa fa-refresh"></i> Refresh List
                </a>
                <a href="<?php echo base_url('stocks_new/po_stock_history'); ?>" class="btn btn-info">
                    <i class="fa fa-list-alt"></i> View History
                </a>
                <a href="<?php echo base_url('stocks_new/dashboard'); ?>" class="btn btn-default">
                    <i class="fa fa-dashboard"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Purchase Orders Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Purchase Orders Ready for Stock Addition
                <span class="badge pull-right"><?php echo isset($purchase_orders) ? count($purchase_orders) : 0; ?> orders</span>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="purchaseOrdersTable">
                        <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>Vendor</th>
                                <th>PO Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Items Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($purchase_orders) && !empty($purchase_orders)): ?>
                                <?php foreach($purchase_orders as $po): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo isset($po->po_number) ? htmlspecialchars($po->po_number) : 'N/A'; ?></strong>
                                        </td>
                                        <td><?php echo isset($po->vendor_name) ? htmlspecialchars($po->vendor_name) : 'N/A'; ?></td>
                                        <td><?php echo isset($po->created_at) && !empty($po->created_at) ? date('M d, Y', strtotime($po->created_at)) : 'N/A'; ?></td>
                                        <td>
                                            <span class="badge badge-info">₹<?php echo isset($po->total_amount) && is_numeric($po->total_amount) ? number_format($po->total_amount, 2) : '0.00'; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success"><?php echo isset($po->status) ? htmlspecialchars($po->status) : 'N/A'; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary"><?php echo isset($po->items_count) ? intval($po->items_count) : 'N/A'; ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo base_url('stocks_new/add_stock_from_po/' . (isset($po->id) ? $po->id : '')); ?>" class="btn btn-success btn-sm">
                                                    <i class="fa fa-plus"></i> Add Stock
                                                </a>
                                                <a href="<?php echo base_url('new_purchase_orders/view/' . (isset($po->id) ? $po->id : '')); ?>" class="btn btn-info btn-sm" target="_blank">
                                                    <i class="fa fa-eye"></i> View PO
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fa fa-info-circle fa-2x"></i><br>
                                        No purchase orders ready for stock addition.
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

<!-- Information Panel -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> Purchase Order Stock Addition Process
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Process Flow:</h4>
                        <ol>
                            <li><strong>Purchase Order Creation:</strong> Admin creates purchase order</li>
                            <li><strong>Admin Approval:</strong> Purchase order gets approved</li>
                            <li><strong>Stock Addition:</strong> Medicines are added to stock with batch details</li>
                            <li><strong>Vendor Billing:</strong> Billing records are created for accounting</li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <h4>Benefits:</h4>
                        <ul>
                            <li>Automated medicine creation from purchase orders</li>
                            <li>Batch tracking with expiry dates</li>
                            <li>Vendor billing integration</li>
                            <li>Complete audit trail</li>
                            <li>Quality control tracking</li>
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
    var table = $('#purchaseOrdersTable');
    var tbody = table.find('tbody');
    var rows = tbody.find('tr');
    var validRows = rows.filter(function() {
        // Check if row has proper number of cells and is not the "no data" row
        return $(this).find('td').length === 7 && !$(this).find('td[colspan]').length;
    });
    
    if(validRows.length > 0) {
        try {
            $('#purchaseOrdersTable').DataTable({
                "pageLength": 25,
                "order": [[ 2, "desc" ]], // Sort by PO Date descending
                "columnDefs": [
                    { "orderable": false, "targets": 6 }
                ],
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "No purchase orders available",
                    "zeroRecords": "No matching purchase orders found"
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
