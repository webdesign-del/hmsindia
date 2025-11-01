<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-file-invoice"></i> Invoice Management
                    <small>Manage purchase invoices and vendor bills</small>
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
                        <a href="<?php echo base_url('stocks_new/add_invoice'); ?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Invoice
                        </a>
                        <a href="<?php echo base_url('stocks_new/invoice_reports'); ?>" class="btn btn-info">
                            <i class="fa fa-bar-chart-o"></i> Invoice Reports
                        </a>
                        <a href="<?php echo base_url('stocks_new/vendor_returns'); ?>" class="btn btn-warning">
                            <i class="fa fa-undo"></i> Vendor Returns
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
                        <form action="<?php echo base_url('stocks_new/invoices'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Vendor:</label>
                                <select name="vendor_id" class="form-control">
                                    <option value="">All Vendors</option>
                                    <?php foreach($vendors as $vendor): ?>
                                        <option value="<?php echo isset($vendor->ID) ? $vendor->ID : $vendor->id; ?>" <?php echo $this->input->get('vendor_id') == (isset($vendor->ID) ? $vendor->ID : $vendor->id) ? 'selected' : ''; ?>>
                                            <?php echo isset($vendor->vendor_name) ? $vendor->vendor_name : (isset($vendor->name) ? $vendor->name : 'N/A'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="DRAFT" <?php echo $this->input->get('status') == 'DRAFT' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="PENDING" <?php echo $this->input->get('status') == 'PENDING' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="APPROVED" <?php echo $this->input->get('status') == 'APPROVED' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="PAID" <?php echo $this->input->get('status') == 'PAID' ? 'selected' : ''; ?>>Paid</option>
                                    <option value="CANCELLED" <?php echo $this->input->get('status') == 'CANCELLED' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date From:</label>
                                <input type="date" name="date_from" class="form-control" value="<?php echo $this->input->get('date_from'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Date To:</label>
                                <input type="date" name="date_to" class="form-control" value="<?php echo $this->input->get('date_to'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="<?php echo base_url('stocks_new/invoices'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Invoices Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Invoice List
                        <span class="badge pull-right"><?php echo count($invoices); ?> invoices</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="invoicesTable">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Vendor</th>
                                        <th>Invoice Date</th>
                                        <th>Due Date</th>
                                        <th>Items</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($invoices)): ?>
                                        <?php foreach($invoices as $invoice): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo $invoice->invoice_number; ?></strong>
                                                </td>
                                                <td><?php echo $invoice->vendor_name; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($invoice->invoice_date)); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($invoice->due_date)); ?></td>
                                                <td><?php echo $invoice->total_items; ?></td>
                                                <td>₹<?php echo number_format($invoice->total_amount, 2); ?></td>
                                                <td>₹<?php echo number_format($invoice->paid_amount, 2); ?></td>
                                                <td>₹<?php echo number_format($invoice->balance_amount, 2); ?></td>
                                                <td>
                                                    <span class="badge <?php 
                                                        echo $invoice->status == 'PAID' ? 'badge-success' : 
                                                            ($invoice->status == 'APPROVED' ? 'badge-info' : 
                                                            ($invoice->status == 'CANCELLED' ? 'badge-danger' : 'badge-warning')); 
                                                    ?>">
                                                        <?php echo $invoice->status; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?php echo base_url('stocks_new/edit_invoice/' . $invoice->id); ?>">
                                                                <i class="fa fa-edit"></i> View/Edit
                                                            </a></li>
                                                            <?php if($invoice->status == 'DRAFT'): ?>
                                                                <li><a href="<?php echo base_url('stocks_new/approve_invoice/' . $invoice->id); ?>" onclick="return confirm('Are you sure you want to approve this invoice?')">
                                                                    <i class="fa fa-check"></i> Approve
                                                                </a></li>
                                                            <?php endif; ?>
                                                            <li><a href="<?php echo base_url('stocks_new/print_invoice/' . $invoice->id); ?>" target="_blank">
                                                                <i class="fa fa-print"></i> Print Invoice
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No invoices found. <a href="<?php echo base_url('stocks_new/add_invoice'); ?>">Create your first invoice</a>
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
        
        <!-- Invoice Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-file-invoice fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count($invoices); ?></div>
                                <div>Total Invoices</div>
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
                                <div class="huge"><?php echo count(array_filter($invoices, function($i) { return $i->status == 'PAID'; })); ?></div>
                                <div>Paid</div>
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
                                <i class="fa fa-clock-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count(array_filter($invoices, function($i) { return in_array($i->status, ['DRAFT', 'PENDING', 'APPROVED']); })); ?></div>
                                <div>Pending</div>
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
                                <i class="fa fa-rupee fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">₹<?php echo number_format(array_sum(array_column($invoices, 'total_amount')), 0); ?></div>
                                <div>Total Value</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Invoice Process Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> Invoice Process
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Invoice Workflow:</h4>
                                <ol>
                                    <li><strong>Create Invoice:</strong> Enter vendor and invoice details</li>
                                    <li><strong>Add Items:</strong> Add medicines and batches purchased</li>
                                    <li><strong>Review:</strong> Check quantities, prices, and totals</li>
                                    <li><strong>Approve:</strong> Authorize the invoice</li>
                                    <li><strong>Payment:</strong> Record payment when made</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h4>Features:</h4>
                                <ul>
                                    <li>Automatic batch creation for new purchases</li>
                                    <li>FEFO compliance for stock allocation</li>
                                    <li>Complete audit trail for all transactions</li>
                                    <li>Vendor payment tracking</li>
                                    <li>Invoice printing and export</li>
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
    $('#invoicesTable').DataTable({
        "pageLength": 25,
        "order": [[ 2, "desc" ]], // Sort by invoice date descending
        "columnDefs": [
            { "orderable": false, "targets": 9 }
        ]
    });
});
</script>

