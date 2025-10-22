<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-chart-bar"></i> Reports & Analytics
                    <small>Comprehensive stock and sales reports</small>
                </h1>
            </div>
        </div>
        
        <!-- Report Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-warehouse fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Stock</div>
                                <div>Reports</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo base_url('stocks_new/stock_report'); ?>">
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
                                <i class="fa fa-shopping-cart fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Sales</div>
                                <div>Reports</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo base_url('stocks_new/sales_report'); ?>">
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
                                <i class="fa fa-exchange-alt fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Transfer</div>
                                <div>Reports</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo base_url('stocks_new/transfer_report'); ?>">
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
                                <i class="fa fa-exclamation-triangle fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Alerts</div>
                                <div>Reports</div>
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
        </div>
        
        <!-- Quick Reports -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tachometer-alt"></i> Quick Reports
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h4><i class="fa fa-warehouse"></i> Stock Reports</h4>
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo base_url('stocks_new/stock_report'); ?>"><i class="fa fa-arrow-right"></i> Current Stock Levels</a></li>
                                    <li><a href="<?php echo base_url('stocks_new/stock_summary'); ?>"><i class="fa fa-arrow-right"></i> Stock Summary</a></li>
                                    <li><a href="<?php echo base_url('stocks_new/low_stock_alerts'); ?>"><i class="fa fa-arrow-right"></i> Low Stock Alerts</a></li>
                                    <li><a href="<?php echo base_url('stocks_new/expiry_alerts'); ?>"><i class="fa fa-arrow-right"></i> Expiry Alerts</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h4><i class="fa fa-shopping-cart"></i> Sales Reports</h4>
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo base_url('stocks_new/sales_report'); ?>"><i class="fa fa-arrow-right"></i> Sales Report</a></li>
                                    <li><a href="<?php echo base_url('stocks_new/sales_report?period=today'); ?>"><i class="fa fa-arrow-right"></i> Today's Sales</a></li>
                                    <li><a href="<?php echo base_url('stocks_new/sales_report?period=week'); ?>"><i class="fa fa-arrow-right"></i> Weekly Sales</a></li>
                                    <li><a href="<?php echo base_url('stocks_new/sales_report?period=month'); ?>"><i class="fa fa-arrow-right"></i> Monthly Sales</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h4><i class="fa fa-exchange-alt"></i> Transfer Reports</h4>
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo base_url('stocks_new/transfer_report'); ?>"><i class="fa fa-arrow-right"></i> Transfer Report</a></li>
                                    <li><a href="<?php echo base_url('stocks_new/transfer_report?period=today'); ?>"><i class="fa fa-arrow-right"></i> Today's Transfers</a></li>
                                    <li><a href="<?php echo base_url('stocks_new/transfer_report?period=week'); ?>"><i class="fa fa-arrow-right"></i> Weekly Transfers</a></li>
                                    <li><a href="<?php echo base_url('stocks_new/transfer_report?period=month'); ?>"><i class="fa fa-arrow-right"></i> Monthly Transfers</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Advanced Reports -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-chart-line"></i> Advanced Analytics
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>FEFO Analytics</h4>
                                <p>Track how effectively the FEFO system is working:</p>
                                <ul>
                                    <li>Batch utilization by expiry date</li>
                                    <li>Wastage reduction metrics</li>
                                    <li>Expiry compliance reports</li>
                                    <li>Stock rotation efficiency</li>
                                </ul>
                                <a href="<?php echo base_url('stocks_new/fefo_analytics'); ?>" class="btn btn-info">
                                    <i class="fa fa-chart-bar"></i> View FEFO Analytics
                                </a>
                            </div>
                            <div class="col-md-6">
                                <h4>Inventory Analytics</h4>
                                <p>Comprehensive inventory insights:</p>
                                <ul>
                                    <li>Stock turnover rates</li>
                                    <li>Center-wise stock distribution</li>
                                    <li>Medicine-wise performance</li>
                                    <li>Vendor performance analysis</li>
                                </ul>
                                <a href="<?php echo base_url('stocks_new/inventory_analytics'); ?>" class="btn btn-success">
                                    <i class="fa fa-chart-pie"></i> View Inventory Analytics
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Export Options -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-download"></i> Export Options
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h5>Stock Reports</h5>
                                <a href="<?php echo base_url('stocks_new/export_stock_report'); ?>" class="btn btn-primary btn-sm">
                                    <i class="fa fa-file-excel-o"></i> Export Stock Report
                                </a>
                            </div>
                            <div class="col-md-3">
                                <h5>Sales Reports</h5>
                                <a href="<?php echo base_url('stocks_new/export_sales_report'); ?>" class="btn btn-success btn-sm">
                                    <i class="fa fa-file-excel-o"></i> Export Sales Report
                                </a>
                            </div>
                            <div class="col-md-3">
                                <h5>Transfer Reports</h5>
                                <a href="<?php echo base_url('stocks_new/export_transfer_report'); ?>" class="btn btn-warning btn-sm">
                                    <i class="fa fa-file-excel-o"></i> Export Transfer Report
                                </a>
                            </div>
                            <div class="col-md-3">
                                <h5>All Reports</h5>
                                <a href="<?php echo base_url('stocks_new/export_all_reports'); ?>" class="btn btn-info btn-sm">
                                    <i class="fa fa-file-zip-o"></i> Export All Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Report Features -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Report Features
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Available Features:</h4>
                                <ul>
                                    <li><strong>Real-time Data:</strong> All reports show current, up-to-date information</li>
                                    <li><strong>FEFO Tracking:</strong> Complete batch-level tracking with expiry management</li>
                                    <li><strong>Filter Options:</strong> Filter by date range, center, medicine, etc.</li>
                                    <li><strong>Export Capabilities:</strong> Export to Excel, PDF, or CSV formats</li>
                                    <li><strong>Drill-down Reports:</strong> Click on summary data to see detailed information</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4>Report Types:</h4>
                                <ul>
                                    <li><strong>Stock Reports:</strong> Current levels, low stock, expiry alerts</li>
                                    <li><strong>Sales Reports:</strong> Revenue, quantity sold, patient details</li>
                                    <li><strong>Transfer Reports:</strong> Inter-location movements, FEFO compliance</li>
                                    <li><strong>Analytics:</strong> Trends, patterns, performance metrics</li>
                                    <li><strong>Audit Reports:</strong> Complete transaction history</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

