<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.panel {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.panel-heading {
    border-radius: 8px 8px 0 0;
    padding: 15px;
}

.panel-footer {
    border-radius: 0 0 8px 8px;
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
}

.huge {
    font-size: 40px;
    font-weight: bold;
}

.panel-primary .panel-heading {
    background-color: #337ab7;
    border-color: #337ab7;
    color: white;
}

.panel-green .panel-heading {
    background-color: #5cb85c;
    border-color: #5cb85c;
    color: white;
}

.panel-yellow .panel-heading {
    background-color: #f0ad4e;
    border-color: #f0ad4e;
    color: white;
}

.panel-red .panel-heading {
    background-color: #d9534f;
    border-color: #d9534f;
    color: white;
}

.panel-info .panel-heading {
    background-color: #5bc0de;
    border-color: #5bc0de;
    color: white;
}

.panel-success .panel-heading {
    background-color: #5cb85c;
    border-color: #5cb85c;
    color: white;
}

.panel-warning .panel-heading {
    background-color: #f0ad4e;
    border-color: #f0ad4e;
    color: white;
}

.panel-danger .panel-heading {
    background-color: #d9534f;
    border-color: #d9534f;
    color: white;
}

.badge {
    padding: 4px 8px;
    font-size: 12px;
    border-radius: 4px;
}

.badge-warning {
    background-color: #f0ad4e;
    color: white;
}

.badge-danger {
    background-color: #d9534f;
    color: white;
}

.badge-info {
    background-color: #5bc0de;
    color: white;
}

.badge-success {
    background-color: #5cb85c;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

.table-responsive {
    border-radius: 4px;
}

.page-header {
    border-bottom: 1px solid #eee;
    margin-bottom: 30px;
    padding-bottom: 20px;
}

.btn {
    border-radius: 4px;
}

.fa-5x {
    font-size: 3em;
}
</style>

    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-dashboard"></i> Stocks Dashboard
                <small>Complete inventory management overview</small>
            </h1>
        </div>
    </div>
    
    <!-- Dashboard Summary Cards -->
    <div class="row">
        <div class="col-md-12 text-right">
            <button class="btn btn-primary" onclick="refreshDashboard()">
                <i class="fa fa-refresh"></i> Refresh Dashboard
            </button>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bolt"></i> Quick Actions
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="<?php echo base_url('stocks_new/add_medicine'); ?>" class="btn btn-success btn-block">
                                <i class="fa fa-plus"></i> Add Medicine
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo base_url('stocks_new/add_batch'); ?>" class="btn btn-info btn-block">
                                <i class="fa fa-plus"></i> Add Batch
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo base_url('stocks_new/add_transfer'); ?>" class="btn btn-warning btn-block">
                                <i class="fa fa-plus"></i>  Add Transfer
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo base_url('stocks_new/reports'); ?>" class="btn btn-primary btn-block">
                                <i class="fa fa-chart-bar"></i>  View Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Summary Cards Row 1 -->
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-capsules fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard_summary->total_medicines ?? 0; ?></div>
                            <div>Total Medicines</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('stocks_new/medicines'); ?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-boxes fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard_summary->total_batches ?? 0; ?></div>
                            <div>Active Batches</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('stocks_new/batches'); ?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-exclamation-triangle fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard_summary->low_stock_count ?? 0; ?></div>
                            <div>Low Stock Items</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('stocks_new/low_stock_alerts'); ?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-times-circle fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard_summary->expiring_soon_count ?? 0; ?></div>
                            <div>Expired Items</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('stocks_new/expiry_alerts'); ?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Summary Cards Row 2 -->
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-rupee fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">₹<?php echo number_format($dashboard_summary->total_stock_quantity ?? 0, 0); ?></div>
                            <div>Total Stock Value</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('stocks_new/stock_summary'); ?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-chart-line fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">₹0</div>
                            <div>Today's Sales</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('stocks_new/sales'); ?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-exchange-alt fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">0</div>
                            <div>Today's Transfers</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('stocks_new/transfers'); ?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-clock fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard_summary->expiring_soon_items ?? 0; ?></div>
                            <div>Expiring Soon</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('stocks_new/expiry_alerts'); ?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-chart-line"></i> Sales Analytics (Last 30 Days)
                </div>
                <div class="panel-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-exchange-alt"></i> Transfer Analytics (Last 30 Days)
                </div>
                <div class="panel-body">
                    <canvas id="transferChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alerts Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <i class="fa fa-exclamation-triangle"></i> Low Stock Alerts
                </div>
                <div class="panel-body">
                    <?php if(!empty($low_stock_alerts)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Generic</th>
                                        <th>Current Stock</th>
                                        <th>Min Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach(array_slice($low_stock_alerts, 0, 5) as $alert): ?>
                                        <tr>
                                            <td><?php echo $alert->medicine_name; ?></td>
                                            <td><?php echo $alert->generic_name; ?></td>
                                            <td><span class="badge badge-warning"><?php echo $alert->current_stock; ?></span></td>
                                            <td><?php echo $alert->min_stock_level; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <!-- <a href="<?php echo base_url('stocks_new/low_stock_alerts'); ?>" class="btn btn-warning btn-sm"> -->
                                View All Alerts
                            <!-- </a> -->
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted">
                            <i class="fa fa-check-circle fa-3x"></i>
                            <p>No low stock alerts!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <i class="fa fa-clock"></i> Expiry Alerts
                </div>
                <div class="panel-body">
                    <?php if(!empty($expiry_alerts)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Batch</th>
                                        <th>Expiry Days</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach(array_slice($expiry_alerts, 0, 5) as $alert): ?>
                                        <tr>
                                            <td><?php echo $alert->medicine_name; ?></td>
                                            <td><?php echo $alert->batch_number; ?></td>
                                            <td><?php echo $alert->days_to_expiry; ?></td>
                                            <td>
                                                <span class="badge <?php echo $alert->days_to_expiry <= 0 ? 'badge-danger' : ($alert->days_to_expiry <= 7 ? 'badge-warning' : 'badge-info'); ?>">
                                                    <?php echo $alert->days_to_expiry <= 0 ? 'EXPIRED' : ($alert->days_to_expiry <= 7 ? 'CRITICAL' : 'WARNING'); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="<?php echo base_url('stocks_new/expiry_alerts'); ?>" class="btn btn-danger btn-sm">
                                View All Alerts
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted">
                            <i class="fa fa-check-circle fa-3x"></i>
                            <p>No expiry alerts!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-shopping-cart"></i> Recent Sales
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sale #</th>
                                    <th>Patient</th>
                                    <th>Center</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($recent_sales)): ?>
                                    <?php foreach($recent_sales as $sale): ?>
                                        <tr>
                                            <td><?php echo $sale->sale_number; ?></td>
                                            <td><?php echo $sale->patient_name; ?></td>
                                            <td><?php echo $sale->center_name; ?></td>
                                            <td>₹<?php echo number_format($sale->subtotal, 2); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($sale->sale_date)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No recent sales</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-exchange-alt"></i> Recent Transfers
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Transfer #</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($recent_transfers)): ?>
                                    <?php foreach($recent_transfers as $transfer): ?>
                                        <tr>
                                            <td><?php echo $transfer->transfer_number; ?></td>
                                            <td><?php echo $transfer->from_center ?: 'Central'; ?></td>
                                            <td><?php echo $transfer->to_center; ?></td>
                                            <td>
                                                <span class="badge <?php echo $transfer->status == 'COMPLETED' ? 'badge-success' : 'badge-warning'; ?>">
                                                    <?php echo $transfer->status; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($transfer->transfer_date)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No recent transfers</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Selling Medicines -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-trophy"></i> Top Selling Medicines
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Medicine</th>
                                    <th>Brand</th>
                                    <th>Quantity Sold</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($top_selling_medicines)): ?>
                                    <?php foreach($top_selling_medicines as $index => $medicine): ?>
                                        <tr>
                                            <td>
                                                <span class="badge <?php echo $index < 3 ? 'badge-warning' : 'badge-secondary'; ?>">
                                                    #<?php echo $index + 1; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $medicine->medicine_name; ?></td>
                                            <td><?php echo $medicine->brand_name ?? 'N/A'; ?></td>
                                            <td><?php echo number_format($medicine->total_sold); ?></td>
                                            <td>₹<?php echo number_format($medicine->total_revenue, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No sales data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($sales_analytics, 'sale_date')); ?>,
            datasets: [{
                label: 'Sales Revenue',
                data: <?php echo json_encode(array_column($sales_analytics, 'total_revenue')); ?>,
                borderColor: '#337ab7',
                backgroundColor: 'rgba(51, 122, 183, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    
    // Transfer Chart
    const transferCtx = document.getElementById('transferChart').getContext('2d');
    const transferChart = new Chart(transferCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($transfer_analytics, 'transfer_date')); ?>,
            datasets: [{
                label: 'Transfers',
                data: <?php echo json_encode(array_column($transfer_analytics, 'total_transfers')); ?>,
                backgroundColor: 'rgba(92, 184, 92, 0.8)',
                borderColor: '#000000ff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Refresh Dashboard Function
    function refreshDashboard() {
        location.reload();
    }
    
    // Auto refresh every 5 minutes
    setInterval(function() {
        // You can implement AJAX refresh here if needed
    }, 300000);
</script>
