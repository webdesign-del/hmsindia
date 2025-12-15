<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- DataTables CSS and Extensions -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">

<!-- DataTables JS and Extensions -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.3.0/js/responsive.bootstrap.min.js"></script>

<style>
/* Custom styles for low stock alerts */
.huge {
    font-size: 40px;
}

.badge-danger {
    background-color: #d9534f;
}

.badge-warning {
    background-color: #f0ad4e;
}

.badge-info {
    background-color: #5bc0de;
}

.badge-success {
    background-color: #5cb85c;
}

/* DataTable search styling */
.dataTables_filter input {
    width: 300px !important;
    margin-left: 10px;
}

/* Filter controls styling */
.filter-controls {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
}

/* Responsive table improvements */
@media (max-width: 768px) {
    .table-responsive {
        border: none;
    }
    
    .btn-group {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .btn-group .btn {
        width: 100%;
    }
}

/* Priority badges */
.priority-critical {
    background-color: #d9534f !important;
    color: white;
}

.priority-high {
    background-color: #f0ad4e !important;
    color: white;
}

.priority-medium {
    background-color: #5bc0de !important;
    color: white;
}

.priority-low {
    background-color: #5cb85c !important;
    color: white;
}
</style>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-exclamation-triangle"></i> Low Stock Alerts
                    <small>Medicines requiring immediate attention</small>
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
                        <a href="<?php echo base_url('stocks_new/add_batch'); ?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Batch
                        </a>
                        <a href="<?php echo base_url('stocks_new/transfers'); ?>" class="btn btn-info">
                            <i class="fa fa-exchange"></i> Transfer Stock
                        </a>
                        <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-success">
                            <i class="fa fa-building-o"></i> View All Stock
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Alerts Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-exclamation-triangle"></i> Low Stock Alerts
                        <span class="badge pull-right"><?php echo isset($low_stock_alerts) ? count($low_stock_alerts) : 0; ?> alerts</span>
                    </div>
                    <div class="panel-body">
                        <!-- Search and Filter Controls -->
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <i class="fa fa-filter"></i> Filter Options
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="centerFilter">Filter by Center:</label>
                                                <select id="centerFilter" class="form-control">
                                                    <option value="">All Centers</option>
                                                    <?php if(isset($centers) && !empty($centers)): ?>
                                                        <?php foreach($centers as $center): ?>
                                                            <option value="<?php echo isset($center->id) ? $center->id : (isset($center->ID) ? $center->ID : ''); ?>" 
                                                                <?php echo (isset($selected_center_id) && $selected_center_id == (isset($center->id) ? $center->id : (isset($center->ID) ? $center->ID : ''))) ? 'selected' : ''; ?>>
                                                                <?php echo isset($center->center_name) ? htmlspecialchars($center->center_name) : (isset($center->name) ? htmlspecialchars($center->name) : 'N/A'); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="centralFilter">Filter by Central Stock:</label>
                                                <select id="centralFilter" class="form-control">
                                                    <option value="">All Stock</option>
                                                    <option value="1" <?php echo (isset($selected_central_only) && $selected_central_only == '1') ? 'selected' : ''; ?>>Central Stock Only</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="departmentFilter">Filter by Department:</label>
                                                <select id="departmentFilter" class="form-control">
                                                    <option value="">All Departments</option>
                                                    <?php if(isset($departments) && !empty($departments)): ?>
                                                        <?php foreach($departments as $dept): ?>
                                                            <option value="<?php echo isset($dept['department']) ? htmlspecialchars($dept['department']) : ''; ?>" 
                                                                <?php echo (isset($selected_department) && $selected_department == (isset($dept['department']) ? $dept['department'] : '')) ? 'selected' : ''; ?>>
                                                                <?php echo isset($dept['department']) ? htmlspecialchars($dept['department']) : 'N/A'; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>&nbsp;</label><br>
                                                <button id="applyFilters" class="btn btn-primary">
                                                    <i class="fa fa-filter"></i> Apply Filters
                                                </button>
                                                <button id="clearAllFilters" class="btn btn-default">
                                                    <i class="fa fa-refresh"></i> Clear All
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        <?php if(!empty($low_stock_alerts)): ?>
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-3">
                                    <label for="priorityFilter">Filter by Priority:</label>
                                    <select id="priorityFilter" class="form-control">
                                        <option value="">All Priorities</option>
                                        <option value="CRITICAL">Critical</option>
                                        <option value="HIGH">High</option>
                                        <option value="MEDIUM">Medium</option>
                                        <option value="LOW">Low</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="statusFilter">Filter by Status:</label>
                                    <select id="statusFilter" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="OUT_OF_STOCK">Out of Stock</option>
                                        <option value="LOW_STOCK">Low Stock</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label><br>
                                    <button id="clearFilters" class="btn btn-default">
                                        <i class="fa fa-refresh"></i> Clear Table Filters
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label><br>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-download"></i> Export <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" onclick="exportTable('copy')"><i class="fa fa-copy"></i> Copy</a></li>
                                            <li><a href="#" onclick="exportTable('csv')"><i class="fa fa-file-text-o"></i> CSV</a></li>
                                            <li><a href="#" onclick="exportTable('excel')"><i class="fa fa-file-excel-o"></i> Excel</a></li>
                                            <li><a href="#" onclick="exportTable('pdf')"><i class="fa fa-file-pdf-o"></i> PDF</a></li>
                                            <li><a href="#" onclick="exportTable('print')"><i class="fa fa-print"></i> Print</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="lowStockTable">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Brand</th>
                                            <th>Current Stock</th>
                                            <th>Min Level</th>
                                            <th>Max Level</th>
                                            <th>Reorder Level</th>
                                            <th>Center/Location</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($low_stock_alerts as $alert): ?>
                                            <tr class="<?php echo (isset($alert->stock_status) && $alert->stock_status == 'OUT_OF_STOCK') ? 'danger' : 'warning'; ?>">
                                                <td>
                                                    <strong><?php echo isset($alert->medicine_name) ? htmlspecialchars($alert->medicine_name) : 'N/A'; ?></strong><br>
                                                    <small class="text-muted"><?php echo isset($alert->medicine_code) ? htmlspecialchars($alert->medicine_code) : 'N/A'; ?></small>
                                                </td>
                                                <td><?php echo isset($alert->brand_name) ? htmlspecialchars($alert->brand_name) : 'N/A'; ?></td>
                                                <td>
                                                    <span class="badge <?php echo (isset($alert->current_stock) && $alert->current_stock == 0) ? 'badge-danger' : 'badge-warning'; ?>">
                                                        <?php echo isset($alert->current_stock) ? number_format($alert->current_stock) : '0'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo isset($alert->min_stock_level) ? number_format($alert->min_stock_level) : '0'; ?></td>
                                                <td><?php echo isset($alert->max_stock_level) ? number_format($alert->max_stock_level) : '0'; ?></td>
                                                <td><?php echo isset($alert->reorder_level) ? number_format($alert->reorder_level) : '0'; ?></td>
                                                <td>
                                                    <?php 
                                                    if (isset($alert->center_names) && !empty($alert->center_names)) {
                                                        echo '<span class="badge badge-info">' . htmlspecialchars($alert->center_names) . '</span>';
                                                    } elseif (isset($alert->center_name) && !empty($alert->center_name)) {
                                                        echo '<span class="badge badge-info">' . htmlspecialchars($alert->center_name) . '</span>';
                                                    } elseif (isset($alert->central_stock) && $alert->central_stock > 0) {
                                                        echo '<span class="badge badge-primary">Central Stock</span>';
                                                    } else {
                                                        echo '<span class="badge badge-secondary">N/A</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo (isset($alert->stock_status) && $alert->stock_status == 'OUT_OF_STOCK') ? 'badge-danger' : 'badge-warning'; ?>">
                                                        <?php echo isset($alert->stock_status) ? htmlspecialchars($alert->stock_status) : 'N/A'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $priority = 'LOW';
                                                    $current_stock = isset($alert->current_stock) ? $alert->current_stock : 0;
                                                    $min_level = isset($alert->min_stock_level) ? $alert->min_stock_level : 0;
                                                    
                                                    if($current_stock == 0) $priority = 'CRITICAL';
                                                    elseif($current_stock <= $min_level * 0.5) $priority = 'HIGH';
                                                    elseif($current_stock <= $min_level) $priority = 'MEDIUM';
                                                    ?>
                                                    <span class="badge <?php 
                                                        echo $priority == 'CRITICAL' ? 'badge-danger' : 
                                                            ($priority == 'HIGH' ? 'badge-warning' : 
                                                            ($priority == 'MEDIUM' ? 'badge-info' : 'badge-success')); 
                                                    ?>">
                                                        <?php echo $priority; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?php echo base_url('stocks_new/add_batch?medicine_id=' . (isset($alert->medicine_id) ? $alert->medicine_id : 0)); ?>">
                                                                <i class="fa fa-plus"></i> Add Batch
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/transfers?medicine_id=' . (isset($alert->medicine_id) ? $alert->medicine_id : 0)); ?>">
                                                                <i class="fa fa-exchange"></i> Transfer Stock
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/edit_medicine/' . (isset($alert->medicine_id) ? $alert->medicine_id : 0)); ?>">
                                                                <i class="fa fa-edit"></i> Edit Medicine
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fa fa-check-circle fa-3x"></i><br>
                                <h3>No Low Stock Alerts!</h3>
                                <p>All medicines have sufficient stock levels.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Alert Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-times-circle fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($low_stock_alerts) ? count(array_filter($low_stock_alerts, function($a) { return isset($a->stock_status) && $a->stock_status == 'OUT_OF_STOCK'; })) : 0; ?></div>
                                <div>Out of Stock</div>
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
                                <div class="huge"><?php echo isset($low_stock_alerts) ? count(array_filter($low_stock_alerts, function($a) { return isset($a->stock_status) && $a->stock_status == 'LOW_STOCK'; })) : 0; ?></div>
                                <div>Low Stock</div>
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
                                <i class="fa fa-clock-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($low_stock_alerts) ? count(array_filter($low_stock_alerts, function($a) { 
                                    $current_stock = isset($a->current_stock) ? $a->current_stock : 0;
                                    $min_level = isset($a->min_stock_level) ? $a->min_stock_level : 0;
                                    return $current_stock <= $min_level * 0.5; 
                                })) : 0; ?></div>
                                <div>Critical</div>
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
                                <div class="huge"><?php echo isset($low_stock_alerts) ? count($low_stock_alerts) : 0; ?></div>
                                <div>Total Alerts</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stock Level Guidelines -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Stock Level Guidelines
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Alert Levels:</h4>
                                <ul>
                                    <li><strong>CRITICAL:</strong> Stock is 0 or below 50% of minimum level</li>
                                    <li><strong>HIGH:</strong> Stock is below minimum level</li>
                                    <li><strong>MEDIUM:</strong> Stock is at minimum level</li>
                                    <li><strong>LOW:</strong> Stock is above minimum but below reorder level</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4>Recommended Actions:</h4>
                                <ul>
                                    <li><strong>Out of Stock:</strong> Immediate purchase or transfer required</li>
                                    <li><strong>Low Stock:</strong> Plan purchase within 1-2 days</li>
                                    <li><strong>At Minimum:</strong> Monitor closely, prepare for reorder</li>
                                    <li><strong>Above Minimum:</strong> Normal operation</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FEFO Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-exclamation-triangle"></i> Important Notes
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><strong>FEFO Compliance:</strong> When adding new batches, ensure FEFO principle is maintained</li>
                            <li><strong>Batch Tracking:</strong> All stock movements are tracked at batch level</li>
                            <li><strong>Expiry Management:</strong> Consider expiry dates when planning purchases</li>
                            <li><strong>Center Distribution:</strong> Balance stock across all centers based on demand</li>
                            <li><strong>Regular Monitoring:</strong> Check alerts daily to prevent stockouts</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with enhanced search functionality
    var table = $('#lowStockTable').DataTable({
        "pageLength": 25,
        "order": [[ 7, "desc" ], [ 2, "asc" ]], // Sort by priority, then current stock
        "columnDefs": [
            { "orderable": false, "targets": 8 }
        ],
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "language": {
            "search": "Search medicines:",
            "searchPlaceholder": "Enter medicine name, brand, or status...",
            "emptyTable": "No low stock alerts found",
            "info": "Showing _START_ to _END_ of _TOTAL_ alerts",
            "infoEmpty": "No alerts to display",
            "infoFiltered": "(filtered from _MAX_ total alerts)"
        },
        "responsive": true,
        "processing": true,
        "searchHighlight": true
    });

    // Add custom search filters
    $('#priorityFilter').on('change', function() {
        var priority = $(this).val();
        if (priority === '') {
            table.column(7).search('').draw();
        } else {
            table.column(7).search(priority).draw();
        }
    });

    $('#statusFilter').on('change', function() {
        var status = $(this).val();
        if (status === '') {
            table.column(6).search('').draw();
        } else {
            table.column(6).search(status).draw();
        }
    });

    // Clear all filters
    $('#clearFilters').on('click', function() {
        table.search('').columns().search('').draw();
        $('#priorityFilter').val('');
        $('#statusFilter').val('');
    });
    
    // Apply filters (Center, Central, Department) - reload page with new filters
    $('#applyFilters').on('click', function() {
        var centerId = $('#centerFilter').val();
        var centralOnly = $('#centralFilter').val();
        var department = $('#departmentFilter').val();
        
        var url = '<?php echo base_url("stocks_new/low_stock_alerts"); ?>';
        var params = [];
        
        if (centerId) {
            params.push('center_id=' + encodeURIComponent(centerId));
        }
        
        if (centralOnly) {
            params.push('central_only=' + encodeURIComponent(centralOnly));
        }
        
        if (department) {
            params.push('department=' + encodeURIComponent(department));
        }
        
        if (params.length > 0) {
            url += '?' + params.join('&');
        }
        
        window.location.href = url;
    });
    
    // Clear all filters and reload
    $('#clearAllFilters').on('click', function() {
        window.location.href = '<?php echo base_url("stocks_new/low_stock_alerts"); ?>';
    });
    
    // Allow Enter key to trigger filter apply
    $('#centerFilter, #centralFilter, #departmentFilter').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            $('#applyFilters').click();
        }
    });

    // Export functionality
    window.exportTable = function(type) {
        switch(type) {
            case 'copy':
                table.button('.buttons-copy').trigger();
                break;
            case 'csv':
                table.button('.buttons-csv').trigger();
                break;
            case 'excel':
                table.button('.buttons-excel').trigger();
                break;
            case 'pdf':
                table.button('.buttons-pdf').trigger();
                break;
            case 'print':
                table.button('.buttons-print').trigger();
                break;
        }
    };
});
</script>

