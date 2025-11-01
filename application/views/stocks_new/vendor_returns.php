<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-undo"></i> Vendor Returns Management
                    <small>Manage vendor returns and refunds</small>
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
                        <a href="<?php echo base_url('stocks_new/add_vendor_return'); ?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Return
                        </a>
                        <a href="<?php echo base_url('stocks_new/vendor_return_reports'); ?>" class="btn btn-info">
                            <i class="fa fa-bar-chart-o"></i> Return Reports
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
                        <form action="<?php echo base_url('stocks_new/vendor_returns'); ?>" method="get" class="form-inline">
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
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="<?php echo base_url('stocks_new/vendor_returns'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vendor Returns Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Vendor Returns List
                    </div>
                    <div class="panel-body">
                        <?php if(isset($vendor_returns) && !empty($vendor_returns)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="vendorReturnsTable">
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
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url('stocks_new/view_vendor_return/' . $return->id); ?>" class="btn btn-info btn-sm">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <?php if($return->status == 'PENDING'): ?>
                                                            <a href="<?php echo base_url('stocks_new/edit_vendor_return/' . $return->id); ?>" class="btn btn-warning btn-sm">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a>
                                                        <?php endif; ?>
                                                        <!-- <?php if($return->status == 'PENDING' || $return->status == 'APPROVED'): ?>
                                                            <a href="<?php echo base_url('stocks_new/process_vendor_return/' . $return->id); ?>" class="btn btn-success btn-sm">
                                                                <i class="fa fa-check"></i> Process
                                                            </a>
                                                        <?php endif; ?> -->
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No vendor returns found.
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
            $('#vendorReturnsTable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [[ 3, "desc" ]], // Sort by return date descending
                "columnDefs": [
                    { "orderable": false, "targets": 8 } // Disable sorting on Actions column
                ],
                "language": {
                    "emptyTable": "No vendor returns available",
                    "zeroRecords": "No matching vendor returns found"
                }
            });
        });
        </script>
        <?php endif; ?>
