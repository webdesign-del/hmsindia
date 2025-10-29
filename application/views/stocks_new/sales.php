<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-shopping-cart"></i> Sales Management
                    <small>Medicine sales with FEFO stock allocation</small>
                </h1>
            </div>
        </div>
        
        <?php if(ENVIRONMENT === 'development'): ?>
        <!-- Debug Information -->
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-bug"></i> Debug Information
                    </div>
                    <div class="panel-body">
                        <p><strong>Sales Count:</strong> <?php echo is_array($sales) ? count($sales) : 'Not an array'; ?></p>
                        <p><strong>Centers Count:</strong> <?php echo is_array($centers) ? count($centers) : 'Not an array'; ?></p>
                        <?php if(!empty($sales) && is_array($sales)): ?>
                            <p><strong>First Sale Item:</strong></p>
                            <pre><?php print_r($sales[0]); ?></pre>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div> -->
        <?php endif; ?>
        
        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus"></i> Quick Actions
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo base_url('stocks_new/add_sale'); ?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Create New Sale
                        </a>
                        <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-info">
                            <i class="fa fa-warehouse"></i> View Stock Levels
                        </a>
                        <a href="<?php echo base_url('stocks_new/sales_report'); ?>" class="btn btn-success">
                            <i class="fa fa-chart-bar"></i> Sales Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Panel -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-search"></i> Search & Filter
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo base_url('stocks_new/sales'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Center:</label>
                                <select name="center_id" class="form-control">
                                    <option value="">All Centers</option>
                                    <?php foreach($centers as $center): ?>
                                        <option value="<?php echo $center->ID; ?>" <?php echo $this->input->get('center_id') == $center->ID ? 'selected' : ''; ?>>
                                            <?php echo $center->center_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Patient:</label>
                                <input type="text" name="patient_name" class="form-control" placeholder="Search patient" value="<?php echo $this->input->get('patient_name'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="DRAFT" <?php echo $this->input->get('status') == 'DRAFT' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="CONFIRMED" <?php echo $this->input->get('status') == 'CONFIRMED' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="CANCELLED" <?php echo $this->input->get('status') == 'CANCELLED' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date From:</label>
                                <input type="date" name="date_from" class="form-control" value="<?php echo $this->input->get('date_from'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Date To:</label>
                                <input type="date" name="date_to" class="form-control" value="<?php echo $this->input->get('date_to'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="<?php echo base_url('stocks_new/sales'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sales Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Sales List
                        <span class="badge pull-right"><?php echo count($sales); ?> sales</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="salesTable">
                                <thead>
                                    <tr>
                                        <th>Sale #</th>
                                        <th>Patient</th>
                                        <th>Center</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Quantity</th>
                                        <th>Total Amount</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($sales) && is_array($sales)): ?>
                                        <?php foreach($sales as $sale): ?>
                                            <?php if(isset($sale->sale_number) && !empty($sale->sale_number)): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($sale->sale_number); ?></strong>
                                                </td>
                                                <td><?php echo isset($sale->patient_name) ? htmlspecialchars($sale->patient_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->center_name) ? htmlspecialchars($sale->center_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->sale_date) && !empty($sale->sale_date) ? date('M d, Y', strtotime($sale->sale_date)) : 'N/A'; ?></td>
                                                <td><?php echo isset($sale->total_items) && is_numeric($sale->total_items) ? number_format($sale->total_items) : '0'; ?></td>
                                                <td><?php echo isset($sale->total_quantity) && is_numeric($sale->total_quantity) ? number_format($sale->total_quantity) : '0'; ?></td>
                                                <td>₹<?php echo isset($sale->total_amount) && is_numeric($sale->total_amount) ? number_format($sale->total_amount, 2) : '0.00'; ?></td>
                                                <td>
                                                    <?php if(isset($sale->payment_status) && !empty($sale->payment_status)): ?>
                                                    <span class="badge <?php 
                                                        echo $sale->payment_status == 'PAID' ? 'badge-success' : 
                                                            ($sale->payment_status == 'PARTIAL' ? 'badge-warning' : 'badge-danger'); 
                                                    ?>">
                                                        <?php echo htmlspecialchars($sale->payment_status); ?>
                                                    </span>
                                                    <?php else: ?>
                                                    <span class="badge badge-default">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if(isset($sale->status) && !empty($sale->status)): ?>
                                                    <span class="badge <?php 
                                                        echo $sale->status == 'CONFIRMED' ? 'badge-success' : 
                                                            ($sale->status == 'CANCELLED' ? 'badge-danger' : 'badge-warning'); 
                                                    ?>">
                                                        <?php echo htmlspecialchars($sale->status); ?>
                                                    </span>
                                                    <?php else: ?>
                                                    <span class="badge badge-default">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?php echo base_url('stocks_new/edit_sale/' . (isset($sale->id) ? $sale->id : '')); ?>">
                                                                <i class="fa fa-edit"></i> View/Edit
                                                            </a></li>
                                                            <?php if(isset($sale->status) && $sale->status == 'DRAFT'): ?>
                                                                <li><a href="<?php echo base_url('stocks_new/confirm_sale/' . (isset($sale->id) ? $sale->id : '')); ?>" onclick="return confirm('Are you sure you want to confirm this sale? This will reduce stock.')">
                                                                    <i class="fa fa-check"></i> Confirm Sale
                                                                </a></li>
                                                            <?php endif; ?>
                                                            <li><a href="<?php echo base_url('stocks_new/print_sale/' . (isset($sale->id) ? $sale->id : '')); ?>" target="_blank">
                                                                <i class="fa fa-print"></i> Print Bill
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                    <?php if(empty($sales) || !is_array($sales) || count(array_filter($sales, function($s) { return isset($s->sale_number) && !empty($s->sale_number); })) == 0): ?>
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No sales found. The database table 'sales' may not exist. <a href="<?php echo base_url('stocks_new/add_sale'); ?>">Create your first sale</a>
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
                                <div class="huge"><?php echo count($sales); ?></div>
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
                                <div class="huge"><?php echo count(array_filter($sales, function($s) { return $s->status == 'CONFIRMED'; })); ?></div>
                                <div>Confirmed</div>
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
                                <i class="fa fa-clock fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count(array_filter($sales, function($s) { return $s->status == 'DRAFT'; })); ?></div>
                                <div>Draft</div>
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
                                <div class="huge">₹<?php echo number_format(array_sum(array_column($sales, 'total_amount')), 0); ?></div>
                                <div>Total Revenue</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FEFO Sales Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> FEFO Sales Process
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Sales Process:</h4>
                                <ol>
                                    <li><strong>Create Sale:</strong> Enter patient and center details</li>
                                    <li><strong>Add Items:</strong> Select medicines with FEFO batch priority</li>
                                    <li><strong>Review:</strong> Check quantities and prices</li>
                                    <li><strong>Confirm:</strong> System automatically reduces stock using FEFO</li>
                                    <li><strong>Print Bill:</strong> Generate patient receipt</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h4>FEFO Benefits:</h4>
                                <ul>
                                    <li>Automatic batch selection by expiry date</li>
                                    <li>Reduces medicine wastage</li>
                                    <li>Ensures patient safety</li>
                                    <li>Complies with regulatory requirements</li>
                                    <li>Complete audit trail for all sales</li>
                                </ul>
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
    var table = $('#salesTable');
    var tbody = table.find('tbody');
    var rows = tbody.find('tr');
    var validRows = rows.filter(function() {
        // Check if row has proper number of cells and is not the "no data" row
        return $(this).find('td').length === 10 && !$(this).find('td[colspan]').length;
    });
    
    console.log('Total rows:', rows.length);
    console.log('Valid rows:', validRows.length);
    
    if(validRows.length > 0) {
        try {
            $('#salesTable').DataTable({
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
            console.log('DataTables initialized successfully');
        } catch(e) {
            console.error('DataTables initialization failed:', e);
        }
    } else {
        console.log('No valid data rows found, skipping DataTables initialization');
        // Add basic styling to make it look like a table
        table.addClass('table-striped table-bordered table-hover');
    }
});
</script>

