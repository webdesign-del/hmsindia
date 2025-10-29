<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-warehouse"></i> Stock Levels
                    <small>Real-time inventory tracking with FEFO</small>
                    <!-- <div class="pull-right">
                    <a href="<?php echo base_url('stocks_new/multi_transfer'); ?>" class="btn btn-success">
                        <i class="fa fa-exchange-alt"></i> Multi-Item Transfer
                    </a>
                    <a href="<?php echo base_url('stocks_new/department_transfer'); ?>" class="btn btn-info">
                        <i class="fa fa-building"></i> Department Transfer
                    </a>
                    </div> -->
                </h1>
            </div>
        </div>
        
        <?php if(ENVIRONMENT === 'development'): ?>
        <!-- Debug Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-bug"></i> Debug Information
                    </div>
                    <div class="panel-body">
                        <p><strong>Stock Levels Count:</strong> <?php echo is_array($stock_levels) ? count($stock_levels) : 'Not an array'; ?></p>
                        <p><strong>Centers Count:</strong> <?php echo is_array($centers) ? count($centers) : 'Not an array'; ?></p>
                        <?php if(!empty($stock_levels) && is_array($stock_levels)): ?>
                            <p><strong>First Stock Item:</strong></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Filter Panel -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-filter"></i> Filter Options
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo base_url('stocks_new/stock_levels'); ?>" method="get" class="form-inline">
                          <?php
                            $logged_user = null;
                            foreach ($_SESSION as $key => $value) {
                                if (strpos($key, 'logged_') === 0 && is_array($value) && isset($value['role'])) {
                                    $logged_user = $value;
                                    break;
                                }
                            }
                            ?>
                            <?php if ($logged_user && $logged_user['role'] != 'billing_manager'): ?>
                                <div class="form-group">
                                    <label>Center:</label>
                                    <select name="center_id" class="form-control">
                                        <option value="">All Centers</option>
                                        <?php foreach ($centers as $center): ?>
                                            <option value="<?php echo $center->ID; ?>" <?php echo ($selected_center == $center->ID) ? 'selected' : ''; ?>>
                                                <?php echo $center->center_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label>Stock Status:</label>
                                <select name="stock_status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="LOW_STOCK" <?php echo $this->input->get('stock_status') == 'LOW_STOCK' ? 'selected' : ''; ?>>Low Stock</option>
                                    <option value="OUT_OF_STOCK" <?php echo $this->input->get('stock_status') == 'OUT_OF_STOCK' ? 'selected' : ''; ?>>Out of Stock</option>
                                    <option value="EXPIRING_SOON" <?php echo $this->input->get('stock_status') == 'EXPIRING_SOON' ? 'selected' : ''; ?>>Expiring Soon</option>
                                    <option value="EXPIRED" <?php echo $this->input->get('stock_status') == 'EXPIRED' ? 'selected' : ''; ?>>Expired</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Medicine:</label>
                                <input type="text" name="medicine_name" class="form-control" placeholder="Search medicine" value="<?php echo $this->input->get('medicine_name'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Filter
                            </button>
                            <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stock Levels Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Current Stock Levels
                        <span class="badge pull-right"><?php echo count($stock_levels); ?> items</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="stockLevelsTable">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Brand</th>
                                        <th>Batch</th>
                                        <th>Expiry Date</th>
                                        <th>Days Left</th>
                                        <th>Central Stock</th>
                                        <th>Center Stock</th>
                                        <th>Total Stock</th>
                                        <th>FEFO Rank</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($stock_levels) && is_array($stock_levels)): ?>
                                        <?php foreach($stock_levels as $stock): ?>
                                            <?php if(isset($stock->medicine_name) && !empty($stock->medicine_name)): ?>
                                            <tr class="<?php echo isset($stock->expiry_status) && $stock->expiry_status == 'EXPIRED' ? 'danger' : (isset($stock->expiry_status) && $stock->expiry_status == 'EXPIRING_SOON' ? 'warning' : ''); ?>">
                                                <td>
                                                    <strong><?php echo htmlspecialchars($stock->medicine_name); ?></strong><br>
                                                    <small class="text-muted"><?php echo isset($stock->medicine_code) ? htmlspecialchars($stock->medicine_code) : ''; ?></small>
                                                </td>
                                                <td><?php echo isset($stock->brand_name) ? htmlspecialchars($stock->brand_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($stock->batch_number) ? htmlspecialchars($stock->batch_number) : 'N/A'; ?></td>
                                                <td><?php echo isset($stock->expiry_date) && !empty($stock->expiry_date) ? date('M d, Y', strtotime($stock->expiry_date)) : 'N/A'; ?></td>
                                                <td>
                                                    <?php if(isset($stock->expiry_days) && is_numeric($stock->expiry_days)): ?>
                                                    <span class="badge <?php echo $stock->expiry_days < 0 ? 'badge-danger' : ($stock->expiry_days <= 30 ? 'badge-warning' : 'badge-success'); ?>">
                                                        <?php echo $stock->expiry_days; ?> days
                                                    </span>
                                                    <?php else: ?>
                                                    <span class="badge badge-default">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo isset($stock->central_quantity) && is_numeric($stock->central_quantity) ? number_format($stock->central_quantity) : '0'; ?></span>
                                                </td>
                                                <td>
                                                    <?php if(isset($stock->center_quantity) && is_numeric($stock->center_quantity) && $stock->center_quantity > 0): ?>
                                                        <span class="badge badge-success"><?php echo number_format($stock->center_quantity); ?></span>
                                                        <?php if(isset($stock->center_names) && !empty($stock->center_names)): ?>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($stock->center_names); ?></small>
                                                        <?php elseif(isset($stock->center_name) && !empty($stock->center_name)): ?>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($stock->center_name); ?></small>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="badge badge-default">0</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo isset($stock->total_quantity) && is_numeric($stock->total_quantity) ? number_format($stock->total_quantity) : number_format((isset($stock->central_quantity) && is_numeric($stock->central_quantity) ? $stock->central_quantity : 0) + (isset($stock->center_quantity) && is_numeric($stock->center_quantity) ? $stock->center_quantity : 0)); ?></strong>
                                                </td>
                                                <td>
                                                    <?php if(isset($stock->fifo_rank) && is_numeric($stock->fifo_rank)): ?>
                                                    <span class="badge <?php echo $stock->fifo_rank == 1 ? 'badge-warning' : 'badge-default'; ?>">
                                                        #<?php echo $stock->fifo_rank; ?>
                                                    </span>
                                                    <?php else: ?>
                                                    <span class="badge badge-default">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if(isset($stock->expiry_status) && !empty($stock->expiry_status)): ?>
                                                    <span class="badge <?php echo $stock->expiry_status == 'EXPIRED' ? 'badge-danger' : ($stock->expiry_status == 'EXPIRING_SOON' ? 'badge-warning' : 'badge-success'); ?>">
                                                        <?php echo htmlspecialchars($stock->expiry_status); ?>
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
                                                            <li><a href="<?php echo base_url('stocks_new/transfers?batch_id=' . (isset($stock->batch_id) ? $stock->batch_id : '')); ?>">
                                                                <i class="fa fa-exchange-alt"></i> Transfer Stock
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/sales?batch_id=' . (isset($stock->batch_id) ? $stock->batch_id : '')); ?>">
                                                                <i class="fa fa-shopping-cart"></i> Sell Stock
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/stock_movements?batch_id=' . (isset($stock->batch_id) ? $stock->batch_id : '')); ?>">
                                                                <i class="fa fa-history"></i> View History
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                    <?php if(empty($stock_levels) || !is_array($stock_levels) || count(array_filter($stock_levels, function($s) { return isset($s->medicine_name) && !empty($s->medicine_name); })) == 0): ?>
                                        <tr>
                                            <td colspan="11" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No stock data found. The database view 'v_current_stock_levels' may not exist.
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
        
        <!-- Stock Summary Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-boxes fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count($stock_levels); ?></div>
                                <div>Total Items</div>
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
                                <div class="huge"><?php echo count(array_filter($stock_levels, function($s) { return $s->expiry_status == 'FRESH'; })); ?></div>
                                <div>Fresh Stock</div>
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
                                <i class="fa fa-exclamation-triangle fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count(array_filter($stock_levels, function($s) { return $s->expiry_status == 'EXPIRING_SOON'; })); ?></div>
                                <div>Expiring Soon</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-times-circle fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count(array_filter($stock_levels, function($s) { return $s->expiry_status == 'EXPIRED'; })); ?></div>
                                <div>Expired</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FEFO Explanation -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> FEFO (First Expiry First Out) System
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>How FEFO Works:</h4>
                                <ul>
                                    <li><strong>FEFO Rank #1:</strong> Next batch to be sold/transferred (earliest expiry)</li>
                                    <li><strong>FEFO Rank #2:</strong> Second priority batch</li>
                                    <li><strong>Expiry Status:</strong> Fresh (>30 days), Expiring Soon (≤30 days), Expired (<0 days)</li>
                                    <li><strong>Stock Allocation:</strong> System automatically selects batches with earliest expiry first</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4>Benefits:</h4>
                                <ul>
                                    <li>Reduces medicine wastage</li>
                                    <li>Ensures patient safety</li>
                                    <li>Complies with regulatory requirements</li>
                                    <li>Optimizes inventory turnover</li>
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
    var table = $('#stockLevelsTable');
    var tbody = table.find('tbody');
    var rows = tbody.find('tr');
    var validRows = rows.filter(function() {
        // Check if row has proper number of cells and is not the "no data" row
        return $(this).find('td').length === 11 && !$(this).find('td[colspan]').length;
    });
    if(validRows.length > 0) {
        try {
            $('#stockLevelsTable').DataTable({
                "pageLength": 25,
                "order": [[ 8, "asc" ], [ 4, "asc" ]], // Sort by FEFO rank, then expiry days
                "columnDefs": [
                    { "orderable": false, "targets": 10 }
                ],
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "No stock data available",
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

