<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-clipboard-check"></i> Audit Reports
                    <small>View stock audit history</small>
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
                        <form action="<?php echo base_url('stocks_new/audit_reports'); ?>" method="get" class="form-inline">
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
                                <label>Audit Type:</label>
                                <select name="audit_type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="FULL_AUDIT" <?php echo $this->input->get('audit_type') == 'FULL_AUDIT' ? 'selected' : ''; ?>>Full Audit</option>
                                    <option value="PARTIAL_AUDIT" <?php echo $this->input->get('audit_type') == 'PARTIAL_AUDIT' ? 'selected' : ''; ?>>Partial Audit</option>
                                    <option value="SPOT_CHECK" <?php echo $this->input->get('audit_type') == 'SPOT_CHECK' ? 'selected' : ''; ?>>Spot Check</option>
                                    <option value="EXPIRY_AUDIT" <?php echo $this->input->get('audit_type') == 'EXPIRY_AUDIT' ? 'selected' : ''; ?>>Expiry Audit</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="DRAFT" <?php echo $this->input->get('status') == 'DRAFT' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="IN_PROGRESS" <?php echo $this->input->get('status') == 'IN_PROGRESS' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="COMPLETED" <?php echo $this->input->get('status') == 'COMPLETED' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="APPROVED" <?php echo $this->input->get('status') == 'APPROVED' ? 'selected' : ''; ?>>Approved</option>
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
                            <a href="<?php echo base_url('stocks_new/audit_reports'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Audit Reports Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Audit Reports
                        <div class="pull-right">
                            <a href="<?php echo base_url('stocks_new/stock_audit'); ?>" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Add New Audit
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php if(isset($audit_reports) && !empty($audit_reports)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="auditReportsTable">
                                    <thead>
                                        <tr>
                                            <th>Audit Number</th>
                                            <th>Center</th>
                                            <th>Audit Date</th>
                                            <th>Audit Type</th>
                                            <th>Auditor</th>
                                            <th>Total Items</th>
                                            <th>Variance Items</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($audit_reports as $report): ?>
                                            <tr>
                                                <td><?php echo $report->audit_number; ?></td>
                                                <td><?php echo isset($report->center_name) ? $report->center_name : 'N/A'; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($report->audit_date)); ?></td>
                                                <td><?php echo $report->audit_type; ?></td>
                                                <td><?php echo $report->auditor_name; ?></td>
                                                <td><?php echo $report->total_items_audited; ?></td>
                                                <td>
                                                    <?php if($report->discrepancies_found > 0): ?>
                                                        <span class="badge badge-warning"><?php echo $report->discrepancies_found; ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-success">0</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?php 
                                                        echo $report->status == 'APPROVED' ? 'badge-success' : 
                                                            ($report->status == 'COMPLETED' ? 'badge-info' : 
                                                            ($report->status == 'IN_PROGRESS' ? 'badge-warning' : 'badge-default')); 
                                                    ?>">
                                                        <?php echo $report->status; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url('stocks_new/view_audit/' . $report->id); ?>" class="btn btn-info btn-sm">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <?php if($report->status == 'DRAFT'): ?>
                                                            <a href="<?php echo base_url('stocks_new/edit_audit/' . $report->id); ?>" class="btn btn-warning btn-sm">
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
                                <i class="fa fa-info-circle"></i> No audit reports found.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTables Script -->
        <?php if(isset($audit_reports) && !empty($audit_reports)): ?>
        <script>
        $(document).ready(function() {
            $('#auditReportsTable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [[ 2, "desc" ]], // Sort by audit date descending
                "columnDefs": [
                    { "orderable": false, "targets": 8 } // Disable sorting on Actions column
                ],
                "language": {
                    "emptyTable": "No audit reports available",
                    "zeroRecords": "No matching audit reports found"
                }
            });
        });
        </script>
        <?php endif; ?>
