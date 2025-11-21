<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-trash-o"></i> Disposal Reports
                    <small>View medicine disposal history</small>
                </h1>
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
                        <form action="<?php echo base_url('stocks_new/disposal_reports'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Center:</label>
                                <select name="center_id" class="form-control">
                                    <option value="">All Centers</option>
                                    <?php if(isset($centers) && !empty($centers)): ?>
                                        <?php foreach($centers as $center): ?>
                                            <option value="<?php echo $center->ID; ?>" <?php echo $this->input->get('center_id') == $center->ID ? 'selected' : ''; ?>>
                                                <?php echo $center->center_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="DRAFT" <?php echo $this->input->get('status') == 'DRAFT' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="APPROVED" <?php echo $this->input->get('status') == 'APPROVED' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="COMPLETED" <?php echo $this->input->get('status') == 'COMPLETED' ? 'selected' : ''; ?>>Completed</option>
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
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="<?php echo base_url('stocks_new/disposal_reports'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                            <?php if(isset($disposal_reports) && !empty($disposal_reports)): ?>
                            <div class="btn-group" style="margin-left: 10px;">
                                <button type="button" class="btn btn-success" onclick="exportDisposalReports('excel')">
                                    <i class="fa fa-file-excel-o"></i> Export Excel
                                </button>
                                <button type="button" class="btn btn-danger" onclick="exportDisposalReports('pdf')">
                                    <i class="fa fa-file-pdf-o"></i> Export PDF
                                </button>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Disposal Reports Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Disposal Reports
                        <div class="pull-right">
                            <a href="<?php echo base_url('stocks_new/medicine_disposal'); ?>" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Add New Disposal
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php if(isset($disposal_reports) && !empty($disposal_reports)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="disposalReportsTable">
                                    <thead>
                                        <tr>
                                            <th>Disposal Number</th>
                                            <th>Center</th>
                                            <th>Disposal Date</th>
                                            <th>Disposal Type</th>
                                            <th>Disposal Method</th>
                                            <th>Total Items</th>
                                            <th>Total Cost</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($disposal_reports as $report): ?>
                                            <tr>
                                                <td><?php echo $report->disposal_number; ?></td>
                                                <td><?php echo isset($report->center_name) ? $report->center_name : 'N/A'; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($report->disposal_date)); ?></td>
                                                <td><?php echo $report->disposal_type; ?></td>
                                                <td><?php echo $report->disposal_method; ?></td>
                                                <td><?php echo $report->total_items; ?></td>
                                                <td>₹<?php echo number_format($report->total_cost, 2); ?></td>
                                                <td>
                                                    <span class="badge <?php 
                                                        echo $report->status == 'COMPLETED' ? 'badge-success' : 
                                                            ($report->status == 'APPROVED' ? 'badge-info' : 'badge-warning'); 
                                                    ?>">
                                                        <?php echo $report->status; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url('stocks_new/view_disposal/' . $report->id); ?>" class="btn btn-info btn-sm">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <?php if($report->status == 'DRAFT'): ?>
                                                            <a href="<?php echo base_url('stocks_new/edit_disposal/' . $report->id); ?>" class="btn btn-warning btn-sm">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No disposal reports found.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTables Script -->
        <?php if(isset($disposal_reports) && !empty($disposal_reports)): ?>
        <script>
        $(document).ready(function() {
            $('#disposalReportsTable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [[ 2, "desc" ]], // Sort by disposal date descending
                "columnDefs": [
                    { "orderable": false, "targets": 8 } // Disable sorting on Actions column
                ],
                "language": {
                    "emptyTable": "No disposal reports available",
                    "zeroRecords": "No matching disposal reports found"
                }
            });

            // Export functionality
            window.exportDisposalReports = function(format) {
                // Get current filter values
                var centerId = $('select[name="center_id"]').val() || '';
                var status = $('select[name="status"]').val() || '';
                var fromDate = $('input[name="from_date"]').val() || '';
                var toDate = $('input[name="to_date"]').val() || '';
                
                // Build export URL with filters
                var url = '<?php echo base_url("stocks_new/export_disposal_reports"); ?>?format=' + format;
                if(centerId) url += '&center_id=' + centerId;
                if(status) url += '&status=' + status;
                if(fromDate) url += '&from_date=' + fromDate;
                if(toDate) url += '&to_date=' + toDate;
                
                // Open in new window for download
                window.open(url, '_blank');
            };
        });
        </script>
        <?php endif; ?>
