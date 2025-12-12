<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-bar-chart-o"></i> Vendor Return Reports
                    <small>Comprehensive reports and analytics for vendor returns</small>
                </h1>
            </div>
        </div>
        
        <!-- Summary Statistics -->
        <?php if(isset($summary_stats) && !empty($summary_stats)): ?>
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-undo fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $summary_stats->total_returns; ?></div>
                                <div>Total Returns</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-check fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $summary_stats->completed_returns; ?></div>
                                <div>Completed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-clock-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $summary_stats->pending_returns; ?></div>
                                <div>Pending</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-times fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $summary_stats->rejected_returns; ?></div>
                                <div>Rejected</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Financial Summary -->
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-money"></i> Total Return Value
                    </div>
                    <div class="panel-body">
                        <h3>₹<?php echo number_format($summary_stats->total_value_returned, 2); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-cubes"></i> Total Items Returned
                    </div>
                    <div class="panel-body">
                        <h3><?php echo number_format($summary_stats->total_items_returned); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-calculator"></i> Average Return Value
                    </div>
                    <div class="panel-body">
                        <h3>₹<?php echo number_format($summary_stats->avg_return_value, 2); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-download"></i> Export Options
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo base_url('stocks_new/vendor_returns'); ?>" class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i> Back to Returns
                        </a>
                        <button class="btn btn-success" onclick="exportToExcel()">
                            <i class="fa fa-file-excel-o"></i> Export to Excel
                        </button>
                        <!-- <button class="btn btn-info" onclick="printReport()">
                            <i class="fa fa-print"></i> Print Report
                        </button> -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Panel -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-search"></i> Filter Reports
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo base_url('stocks_new/vendor_return_reports'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Vendor:</label>
                                <select name="vendor_id" class="form-control">
                                    <option value="">All Vendors</option>
                                    <?php if(isset($vendors) && !empty($vendors)): ?>
                                        <?php foreach($vendors as $vendor): ?>
                                            <option value="<?php echo isset($vendor->ID) ? $vendor->ID : $vendor->id; ?>" <?php echo $this->input->get('vendor_id') == (isset($vendor->ID) ? $vendor->ID : $vendor->id) ? 'selected' : ''; ?>>
                                                <?php echo isset($vendor->vendor_name) ? $vendor->vendor_name : (isset($vendor->name) ? $vendor->name : 'N/A'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="PENDING" <?php echo $this->input->get('status') == 'PENDING' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="APPROVED" <?php echo $this->input->get('status') == 'APPROVED' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="COMPLETED" <?php echo $this->input->get('status') == 'COMPLETED' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="REJECTED" <?php echo $this->input->get('status') == 'REJECTED' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From Date:</label>
                                <input type="date" name="from_date" class="form-control" value="<?php echo $this->input->get('from_date'); ?>">
                            </div>
                            <div class="form-group">
                                <label>To Date:</label>
                                <input type="date" name="to_date" class="form-control" value="<?php echo $this->input->get('to_date'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Filter
                            </button>
                            <a href="<?php echo base_url('stocks_new/vendor_return_reports'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reports Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Detailed Return Reports
                    </div>
                    <div class="panel-body">
                        <?php if(isset($vendor_returns) && !empty($vendor_returns)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="vendorReturnReportsTable">
                                    <thead>
                                        <tr>
                                            <th>Return Number</th>
                                            <th>Vendor</th>
                                            <th>Center</th>
                                            <th>Return Date</th>
                                            <th>Total Items</th>
                                            <th>Total Quantity</th>
                                            <th>Total Value</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($vendor_returns as $return): ?>
                                            <tr>
                                                <td><?php echo $return->return_number; ?></td>
                                                <td><?php echo isset($return->vendor_name) ? $return->vendor_name : 'N/A'; ?></td>
                                                <td><?php echo isset($return->center_name) ? $return->center_name : 'N/A'; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($return->return_date)); ?></td>
                                                <td><?php echo $return->total_items; ?></td>
                                                <td><?php echo $return->total_quantity; ?></td>
                                                <td>₹<?php echo number_format($return->total_value, 2); ?></td>
                                                <td>
                                                    <span class="badge <?php 
                                                        echo $return->status == 'COMPLETED' ? 'badge-success' : 
                                                            ($return->status == 'APPROVED' ? 'badge-info' : 
                                                            ($return->status == 'REJECTED' ? 'badge-danger' : 'badge-warning')); 
                                                    ?>">
                                                        <?php echo $return->status; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('d-m-Y H:i', strtotime($return->created_at)); ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url('stocks_new/view_vendor_return/' . $return->id); ?>" class="btn btn-info btn-sm">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No vendor return reports found for the selected criteria.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTables Script -->
        <?php if(isset($vendor_returns) && !empty($vendor_returns)): ?>
        <script>
        $(document).ready(function() {
            $('#vendorReturnReportsTable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [[ 3, "desc" ]], // Sort by return date descending
                "columnDefs": [
                    { "orderable": false, "targets": 9 } // Disable sorting on Actions column
                ],
                "language": {
                    "emptyTable": "No vendor return reports available",
                    "zeroRecords": "No matching vendor return reports found"
                }
            });
        });

        function exportToExcel() {
            window.location.href = '<?php echo base_url('stocks_new/export_vendor_return_reports'); ?>?' + $('form').serialize();
        }

        function printReport() {
            window.print();
        }
        </script>
        <?php endif; ?>
