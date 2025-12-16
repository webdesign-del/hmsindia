<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-bar-chart-o"></i> <?php echo isset($report_title) ? $report_title : 'Sales Reports'; ?>
                    <small>Comprehensive sales analytics and reporting</small>
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
                        <a href="<?php echo base_url('stocks_new/sales'); ?>" class="btn btn-primary">
                            <i class="fa fa-shopping-cart"></i> View All Sales
                        </a>
                        <a href="<?php echo base_url('stocks_new/add_sale'); ?>" class="btn btn-success">
                            <i class="fa fa-plus"></i> Create New Sale
                        </a>
                        <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-info">
                            <i class="fa fa-building-o"></i> Stock Levels
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
                        <form action="<?php echo base_url('stocks_new/sales_report'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Center:</label>
                                <select name="center_id" class="form-control">
                                    <option value="">All Centers</option>
                                    <?php if(isset($centers) && !empty($centers)): ?>
                                        <?php foreach($centers as $center): ?>
                                            <option value="<?php echo isset($center->ID) ? $center->ID : ''; ?>" <?php echo (isset($selected_center) && $selected_center == $center->ID) ? 'selected' : ''; ?>>
                                                <?php echo isset($center->center_name) ? htmlspecialchars($center->center_name) : 'N/A'; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From Date:</label>
                                <input type="date" name="start_date" class="form-control" value="<?php echo isset($start_date) ? htmlspecialchars($start_date) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>To Date:</label>
                                <input type="date" name="end_date" class="form-control" value="<?php echo isset($end_date) ? htmlspecialchars($end_date) : ''; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Generate Report
                            </button>
                            <a href="<?php echo base_url('stocks_new/sales_report'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                            <?php if(isset($sales) && !empty($sales)): ?>
                            <div class="btn-group" style="margin-left: 10px;">
                                <button type="button" class="btn btn-success" onclick="exportSalesReport('excel')">
                                    <i class="fa fa-file-excel-o"></i> Export Excel
                                </button>
                                <button type="button" class="btn btn-danger" onclick="exportSalesReport('pdf')">
                                    <i class="fa fa-file-pdf-o"></i> Export PDF
                                </button>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sales Report Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Sales Report
                        <span class="badge pull-right"><?php echo isset($sales) ? count($sales) : 0; ?> sales</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="salesReportTable">
                                <thead>
                                    <tr>
                                        <th>Sale #</th>
                                        <th>Patient</th>
                                        <th>Center</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Quantity</th>
                                        <th>Total Amount</th>
                                        <th>Payment Status</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($sales) && !empty($sales)): ?>
                                        <?php foreach($sales as $sale): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo isset($sale->sale_number) ? htmlspecialchars($sale->sale_number) : 'N/A'; ?></strong>
                                                </td>
                                                <td><?php echo isset($sale->patient_name) ? htmlspecialchars($sale->patient_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->center_name) ? htmlspecialchars($sale->center_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->sale_date) ? date('M d, Y', strtotime($sale->sale_date)) : 'N/A'; ?></td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo isset($sale->total_items) ? number_format($sale->total_items) : '0'; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-success"><?php echo isset($sale->total_quantity) ? number_format($sale->total_quantity) : '0'; ?></span>
                                                </td>
                                                <td>
                                                    <strong>₹<?php echo isset($sale->total_amount) ? number_format($sale->total_amount, 2) : '0.00'; ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge <?php 
                                                        $payment_status = isset($sale->payment_status) ? $sale->payment_status : 'PENDING';
                                                        echo $payment_status == 'PAID' ? 'badge-success' : 
                                                            ($payment_status == 'PARTIAL' ? 'badge-warning' : 'badge-danger'); 
                                                    ?>">
                                                        <?php echo $payment_status; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge <?php 
                                                        $status = isset($sale->status) ? $sale->status : 'DRAFT';
                                                        echo $status == 'CONFIRMED' ? 'badge-success' : 
                                                            ($status == 'CANCELLED' ? 'badge-danger' : 'badge-warning'); 
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
                                                            <li><a href="<?php echo base_url('stocks_new/edit_sale/' . (isset($sale->id) ? $sale->id : 0)); ?>">
                                                                <i class="fa fa-edit"></i> View/Edit
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/print_sale/' . (isset($sale->id) ? $sale->id : 0)); ?>" target="_blank">
                                                                <i class="fa fa-print"></i> Print Bill
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
                                                No sales data found for the selected period.
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
        
        <!-- Sales Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-shopping-cart fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($sales) ? count($sales) : 0; ?></div>
                                <div>Total Sales</div>
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
                                <div class="huge"><?php echo isset($sales) ? count(array_filter($sales, function($s) { return isset($s->status) && $s->status == 'CONFIRMED'; })) : 0; ?></div>
                                <div>Confirmed</div>
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
                                <div class="huge">₹<?php echo isset($sales) ? number_format(array_sum(array_map(function($s) { return isset($s->total_amount) ? $s->total_amount : 0; }, $sales)), 0) : '0'; ?></div>
                                <div>Total Revenue</div>
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
                                <i class="fa fa-archive fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($sales) ? number_format(array_sum(array_map(function($s) { return isset($s->total_quantity) ? $s->total_quantity : 0; }, $sales))) : '0'; ?></div>
                                <div>Total Items Sold</div>
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
                                <p><strong>Center:</strong> <?php echo isset($selected_center) && $selected_center ? 'Specific Center' : 'All Centers'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <h4>Data Summary:</h4>
                                <p><strong>Total Sales:</strong> <?php echo isset($sales) ? count($sales) : 0; ?></p>
                                <p><strong>Total Revenue:</strong> ₹<?php echo isset($sales) ? number_format(array_sum(array_map(function($s) { return isset($s->total_amount) ? $s->total_amount : 0; }, $sales)), 2) : '0.00'; ?></p>
                                <p><strong>Average Sale:</strong> ₹<?php echo isset($sales) && count($sales) > 0 ? number_format(array_sum(array_map(function($s) { return isset($s->total_amount) ? $s->total_amount : 0; }, $sales)) / count($sales), 2) : '0.00'; ?></p>
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
    var table = $('#salesReportTable');
    var tbody = table.find('tbody');
    var rows = tbody.find('tr');
    var validRows = rows.filter(function() {
        // Check if row has proper number of cells and is not the "no data" row
        return $(this).find('td').length === 10 && !$(this).find('td[colspan]').length;
    });
    
    if(validRows.length > 0) {
        try {
            $('#salesReportTable').DataTable({
                "pageLength": 25,
                "order": [[ 3, "desc" ]], // Sort by date descending
                "columnDefs": [
                    { "orderable": false, "targets": 9 }
                ],
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "No sales data available",
                    "zeroRecords": "No matching records found"
                },
                "processing": true,
                "deferRender": true
            });
        } catch(e) {
            console.error('DataTables initialization failed:', e);
        }
    }

    // Export functionality for sales report
    window.exportSalesReport = function(format) {
        // Get current filter values
        var centerId = $('select[name="center_id"]').val() || '';
        var startDate = $('input[name="start_date"]').val() || '';
        var endDate = $('input[name="end_date"]').val() || '';
        
        // Build export URL with filters (using parameter names that match existing method)
        var url = '<?php echo base_url("stocks_new/export_sales_report"); ?>?format=' + format;
        if(centerId) url += '&center_id=' + centerId;
        if(startDate) url += '&date_from=' + startDate;
        if(endDate) url += '&date_to=' + endDate;
        
        // Open in new window for download
        window.open(url, '_blank');
    };
});
</script>
