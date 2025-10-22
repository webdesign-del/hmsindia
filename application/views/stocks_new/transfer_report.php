<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-exchange-alt"></i> <?php echo isset($report_title) ? $report_title : 'Transfer Reports'; ?>
                    <small>Comprehensive transfer analytics and reporting</small>
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
                        <a href="<?php echo base_url('stocks_new/transfers'); ?>" class="btn btn-primary">
                            <i class="fa fa-exchange-alt"></i> View All Transfers
                        </a>
                        <a href="<?php echo base_url('stocks_new/add_transfer'); ?>" class="btn btn-success">
                            <i class="fa fa-plus"></i> Create New Transfer
                        </a>
                        <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-info">
                            <i class="fa fa-warehouse"></i> Stock Levels
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Panel -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-filter"></i> Report Filters
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo base_url('stocks_new/transfer_report'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Transfer Type:</label>
                                <select name="transfer_type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="CENTRAL_TO_CENTER" <?php echo (isset($selected_transfer_type) && $selected_transfer_type == 'CENTRAL_TO_CENTER') ? 'selected' : ''; ?>>Central to Center</option>
                                    <option value="CENTER_TO_CENTER" <?php echo (isset($selected_transfer_type) && $selected_transfer_type == 'CENTER_TO_CENTER') ? 'selected' : ''; ?>>Center to Center</option>
                                    <option value="CENTER_TO_CENTRAL" <?php echo (isset($selected_transfer_type) && $selected_transfer_type == 'CENTER_TO_CENTRAL') ? 'selected' : ''; ?>>Center to Central</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From Center:</label>
                                <select name="from_center_id" class="form-control">
                                    <option value="">All Centers</option>
                                    <?php if(isset($centers) && !empty($centers)): ?>
                                        <?php foreach($centers as $center): ?>
                                            <option value="<?php echo isset($center->id) ? $center->id : ''; ?>" <?php echo (isset($selected_from_center) && $selected_from_center == $center->id) ? 'selected' : ''; ?>>
                                                <?php echo isset($center->center_name) ? htmlspecialchars($center->center_name) : 'N/A'; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>To Center:</label>
                                <select name="to_center_id" class="form-control">
                                    <option value="">All Centers</option>
                                    <?php if(isset($centers) && !empty($centers)): ?>
                                        <?php foreach($centers as $center): ?>
                                            <option value="<?php echo isset($center->id) ? $center->id : ''; ?>" <?php echo (isset($selected_to_center) && $selected_to_center == $center->id) ? 'selected' : ''; ?>>
                                                <?php echo isset($center->center_name) ? htmlspecialchars($center->center_name) : 'N/A'; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From Date:</label>
                                <input type="date" name="start_date" class="form-control" value="<?php echo isset($start_date) ? $start_date : date('Y-m-01'); ?>">
                            </div>
                            <div class="form-group">
                                <label>To Date:</label>
                                <input type="date" name="end_date" class="form-control" value="<?php echo isset($end_date) ? $end_date : date('Y-m-d'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Generate Report
                            </button>
                            <a href="<?php echo base_url('stocks_new/transfer_report'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transfer Report Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Transfer Report
                        <span class="badge pull-right"><?php echo isset($transfers) ? count($transfers) : 0; ?> transfers</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="transferReportTable">
                                <thead>
                                    <tr>
                                        <th>Transfer #</th>
                                        <th>Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Quantity</th>
                                        <th>Value</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($transfers) && !empty($transfers)): ?>
                                        <?php foreach($transfers as $transfer): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo isset($transfer->transfer_number) ? htmlspecialchars($transfer->transfer_number) : 'N/A'; ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        <?php echo isset($transfer->transfer_type) ? htmlspecialchars(str_replace('_', ' ', $transfer->transfer_type)) : 'N/A'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo isset($transfer->from_center) && !empty($transfer->from_center) ? htmlspecialchars($transfer->from_center) : 'Central Warehouse'; ?></td>
                                                <td><?php echo isset($transfer->to_center) ? htmlspecialchars($transfer->to_center) : 'N/A'; ?></td>
                                                <td><?php echo isset($transfer->transfer_date) ? date('M d, Y', strtotime($transfer->transfer_date)) : 'N/A'; ?></td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo isset($transfer->total_items) ? number_format($transfer->total_items) : '0'; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-success"><?php echo isset($transfer->total_quantity) ? number_format($transfer->total_quantity) : '0'; ?></span>
                                                </td>
                                                <td>
                                                    <strong>₹<?php echo isset($transfer->total_value) ? number_format($transfer->total_value, 2) : '0.00'; ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge <?php 
                                                        $status = isset($transfer->status) ? $transfer->status : 'DRAFT';
                                                        echo $status == 'COMPLETED' ? 'badge-success' : 
                                                            ($status == 'APPROVED' ? 'badge-info' : 
                                                            ($status == 'CANCELLED' ? 'badge-danger' : 'badge-warning')); 
                                                    ?>">
                                                        <?php echo $status; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?php echo base_url('stocks_new/edit_transfer/' . (isset($transfer->id) ? $transfer->id : 0)); ?>">
                                                                <i class="fa fa-edit"></i> View/Edit
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/transfer_details/' . (isset($transfer->id) ? $transfer->id : 0)); ?>">
                                                                <i class="fa fa-eye"></i> View Details
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No transfer data found for the selected period.
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
        
        <!-- Transfer Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-exchange-alt fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($transfers) ? count($transfers) : 0; ?></div>
                                <div>Total Transfers</div>
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
                                <div class="huge"><?php echo isset($transfers) ? count(array_filter($transfers, function($t) { return isset($t->status) && $t->status == 'COMPLETED'; })) : 0; ?></div>
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
                                <div class="huge">₹<?php echo isset($transfers) ? number_format(array_sum(array_map(function($t) { return isset($t->total_value) ? $t->total_value : 0; }, $transfers)), 0) : '0'; ?></div>
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
                                <i class="fa fa-boxes fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($transfers) ? number_format(array_sum(array_map(function($t) { return isset($t->total_quantity) ? $t->total_quantity : 0; }, $transfers))) : '0'; ?></div>
                                <div>Total Items</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Report Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Report Information
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Report Period:</h4>
                                <p><strong>From:</strong> <?php echo isset($start_date) ? date('M d, Y', strtotime($start_date)) : 'N/A'; ?></p>
                                <p><strong>To:</strong> <?php echo isset($end_date) ? date('M d, Y', strtotime($end_date)) : 'N/A'; ?></p>
                                <p><strong>Type:</strong> <?php echo isset($selected_transfer_type) && $selected_transfer_type ? str_replace('_', ' ', $selected_transfer_type) : 'All Types'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <h4>Data Summary:</h4>
                                <p><strong>Total Transfers:</strong> <?php echo isset($transfers) ? count($transfers) : 0; ?></p>
                                <p><strong>Total Value:</strong> ₹<?php echo isset($transfers) ? number_format(array_sum(array_map(function($t) { return isset($t->total_value) ? $t->total_value : 0; }, $transfers)), 2) : '0.00'; ?></p>
                                <p><strong>Average Value:</strong> ₹<?php echo isset($transfers) && count($transfers) > 0 ? number_format(array_sum(array_map(function($t) { return isset($t->total_value) ? $t->total_value : 0; }, $transfers)) / count($transfers), 2) : '0.00'; ?></p>
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
    // Check if table has valid data before initializing DataTables
    var table = $('#transferReportTable');
    var tbody = table.find('tbody');
    var rows = tbody.find('tr');
    var validRows = rows.filter(function() {
        // Check if row has proper number of cells and is not the "no data" row
        return $(this).find('td').length === 10 && !$(this).find('td[colspan]').length;
    });
    
    if(validRows.length > 0) {
        try {
            $('#transferReportTable').DataTable({
                "pageLength": 25,
                "order": [[ 4, "desc" ]], // Sort by date descending
                "columnDefs": [
                    { "orderable": false, "targets": 9 }
                ],
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "No transfer data available",
                    "zeroRecords": "No matching records found"
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
