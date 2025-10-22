<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-clock"></i> Expiry Alerts
                    <small>Medicines approaching or past expiry date</small>
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
                            <i class="fa fa-shopping-cart"></i> Sell Expiring Stock
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
        
        <!-- Expiry Alerts Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <i class="fa fa-clock"></i> Expiry Alerts
                        <span class="badge pull-right"><?php echo isset($expiry_alerts) ? count($expiry_alerts) : 0; ?> alerts</span>
                    </div>
                    <div class="panel-body">
                        <?php if(!empty($expiry_alerts)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="expiryTable">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Brand</th>
                                            <th>Batch</th>
                                            <th>Expiry Date</th>
                                            <th>Days Left</th>
                                            <th>Central Stock</th>
                                            <th>Center Stock</th>
                                            <th>Center</th>
                                            <th>Alert Level</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($expiry_alerts as $alert): ?>
                                            <tr class="<?php echo (isset($alert->alert_level) && $alert->alert_level == 'EXPIRED') ? 'danger' : ((isset($alert->alert_level) && $alert->alert_level == 'CRITICAL') ? 'warning' : 'info'); ?>">
                                                <td>
                                                    <strong><?php echo isset($alert->medicine_name) ? htmlspecialchars($alert->medicine_name) : 'N/A'; ?></strong><br>
                                                    <small class="text-muted"><?php echo isset($alert->medicine_code) ? htmlspecialchars($alert->medicine_code) : 'N/A'; ?></small>
                                                </td>
                                                <td><?php echo isset($alert->brand_name) ? htmlspecialchars($alert->brand_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($alert->batch_number) ? htmlspecialchars($alert->batch_number) : 'N/A'; ?></td>
                                                <td><?php echo isset($alert->expiry_date) ? date('M d, Y', strtotime($alert->expiry_date)) : 'N/A'; ?></td>
                                                <td>
                                                    <span class="badge <?php 
                                                        $days_to_expiry = isset($alert->days_to_expiry) ? $alert->days_to_expiry : 0;
                                                        echo $days_to_expiry < 0 ? 'badge-danger' : 
                                                            ($days_to_expiry <= 7 ? 'badge-warning' : 'badge-info'); 
                                                    ?>">
                                                        <?php echo $days_to_expiry; ?> days
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $central_quantity = isset($alert->central_quantity) ? $alert->central_quantity : 0;
                                                    if($central_quantity > 0): ?>
                                                        <span class="badge badge-info"><?php echo number_format($central_quantity); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-default">0</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $center_quantity = isset($alert->center_quantity) ? $alert->center_quantity : 0;
                                                    if($center_quantity > 0): ?>
                                                        <span class="badge badge-success"><?php echo number_format($center_quantity); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-default">0</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo isset($alert->center_name) ? htmlspecialchars($alert->center_name) : 'Central'; ?></td>
                                                <td>
                                                    <span class="badge <?php 
                                                        $alert_level = isset($alert->alert_level) ? $alert->alert_level : 'OK';
                                                        echo $alert_level == 'EXPIRED' ? 'badge-danger' : 
                                                            ($alert_level == 'CRITICAL' ? 'badge-warning' : 'badge-info'); 
                                                    ?>">
                                                        <?php echo $alert_level; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <?php if(isset($alert->alert_level) && $alert->alert_level != 'EXPIRED'): ?>
                                                                <li><a href="<?php echo base_url('stocks_new/sales?batch_id=' . (isset($alert->batch_id) ? $alert->batch_id : 0)); ?>">
                                                                    <i class="fa fa-shopping-cart"></i> Sell Stock
                                                                </a></li>
                                                                <li><a href="<?php echo base_url('stocks_new/transfers?batch_id=' . (isset($alert->batch_id) ? $alert->batch_id : 0)); ?>">
                                                                    <i class="fa fa-exchange-alt"></i> Transfer Stock
                                                                </a></li>
                                                            <?php endif; ?>
                                                            <li><a href="<?php echo base_url('stocks_new/dispose_batch/' . (isset($alert->batch_id) ? $alert->batch_id : 0)); ?>" 
                                                                   onclick="return confirm('Are you sure you want to dispose this batch?')">
                                                                <i class="fa fa-trash"></i> Dispose Batch
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
                                <h3>No Expiry Alerts!</h3>
                                <p>All medicines have sufficient shelf life remaining.</p>
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
                                <div class="huge"><?php echo isset($expiry_alerts) ? count(array_filter($expiry_alerts, function($a) { return isset($a->alert_level) && $a->alert_level == 'EXPIRED'; })) : 0; ?></div>
                                <div>Expired</div>
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
                                <div class="huge"><?php echo isset($expiry_alerts) ? count(array_filter($expiry_alerts, function($a) { return isset($a->alert_level) && $a->alert_level == 'CRITICAL'; })) : 0; ?></div>
                                <div>Critical (≤7 days)</div>
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
                                <i class="fa fa-clock fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo isset($expiry_alerts) ? count(array_filter($expiry_alerts, function($a) { return isset($a->alert_level) && $a->alert_level == 'WARNING'; })) : 0; ?></div>
                                <div>Warning (≤30 days)</div>
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
                                <div class="huge"><?php echo isset($expiry_alerts) ? count($expiry_alerts) : 0; ?></div>
                                <div>Total Alerts</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FEFO Expiry Management -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> FEFO Expiry Management
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Alert Levels:</h4>
                                <ul>
                                    <li><strong>EXPIRED:</strong> Medicine has passed expiry date</li>
                                    <li><strong>CRITICAL:</strong> Expires within 7 days</li>
                                    <li><strong>WARNING:</strong> Expires within 30 days</li>
                                    <li><strong>OK:</strong> More than 30 days remaining</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4>Recommended Actions:</h4>
                                <ul>
                                    <li><strong>Expired:</strong> Dispose immediately, do not sell</li>
                                    <li><strong>Critical:</strong> Prioritize sales, consider discounts</li>
                                    <li><strong>Warning:</strong> Monitor closely, plan sales</li>
                                    <li><strong>OK:</strong> Normal operation</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Expiry Prevention Tips -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-lightbulb-o"></i> Expiry Prevention Tips
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Purchase Planning:</h4>
                                <ul>
                                    <li>Order quantities based on consumption patterns</li>
                                    <li>Consider shelf life when purchasing</li>
                                    <li>Rotate stock regularly using FEFO</li>
                                    <li>Monitor expiry alerts daily</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4>Sales Strategy:</h4>
                                <ul>
                                    <li>Prioritize expiring batches in sales</li>
                                    <li>Offer discounts for near-expiry items</li>
                                    <li>Transfer stock between centers if needed</li>
                                    <li>Maintain proper storage conditions</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Important Notes -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <i class="fa fa-exclamation-triangle"></i> Important Notes
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><strong>Patient Safety:</strong> Never sell expired medicines to patients</li>
                            <li><strong>FEFO Compliance:</strong> Always sell batches with earliest expiry first</li>
                            <li><strong>Regulatory Compliance:</strong> Dispose expired medicines according to regulations</li>
                            <li><strong>Audit Trail:</strong> All disposal actions are logged for compliance</li>
                            <li><strong>Regular Monitoring:</strong> Check expiry alerts daily to prevent losses</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#expiryTable').DataTable({
        "pageLength": 25,
        "order": [[ 4, "asc" ]], // Sort by days left (ascending)
        "columnDefs": [
            { "orderable": false, "targets": 9 }
        ]
    });
});
</script>

