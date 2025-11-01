<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
                            <i class="fa fa-exchange-alt"></i> Transfer Stock
                        </a>
                        <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-success">
                            <i class="fa fa-warehouse"></i> View All Stock
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
                        <?php if(!empty($low_stock_alerts)): ?>
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
                                                                <i class="fa fa-exchange-alt"></i> Transfer Stock
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
    $('#lowStockTable').DataTable({
        "pageLength": 25,
        "order": [[ 7, "desc" ], [ 2, "asc" ]], // Sort by priority, then current stock
        "columnDefs": [
            { "orderable": false, "targets": 8 }
        ]
    });
});
</script>

