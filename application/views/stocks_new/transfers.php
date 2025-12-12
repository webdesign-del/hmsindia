<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-exchange"></i> Stock Transfers
                    <small>Transfer inventory between locations with FEFO</small>
                </h1>
            </div>
        </div>
        
        <?php if(ENVIRONMENT === 'development'): ?>
        <!-- Debug Information -->
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-bug"></i>   
                    </div>
                    <div class="panel-body">
                        <p><strong>Transfers Count:</strong> <?php echo is_array($transfers) ? count($transfers) : 'Not an array'; ?></p>
                        <p><strong>Centers Count:</strong> <?php echo is_array($centers) ? count($centers) : 'Not an array'; ?></p>
                        <?php if(!empty($transfers) && is_array($transfers)): ?>
                            <p><strong>First Transfer Item:</strong></p>
                            <pre><?php print_r($transfers[0]); ?></pre>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div> -->
        <?php endif; ?>
        
        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus"></i> Quick Actions
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo base_url('stocks_new/add_transfer'); ?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Create New Transfer
                        </a>
                        <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-info">
                            <i class="fa fa-building-o"></i> View Stock Levels
                        </a>
                        <a href="<?php echo base_url('stocks_new/transfer_report'); ?>" class="btn btn-success">
                            <i class="fa fa-bar-chart-o"></i> Transfer Reports
                        </a>
                        <!-- <button class="btn btn-warning" onclick="approveAllPending()">
                            <i class="fa fa-check-circle"></i> Approve All Pending
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
                        <i class="fa fa-search"></i> Search & Filter
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo base_url('stocks_new/transfers'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Transfer Type:</label>
                                <select name="transfer_type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="CENTRAL_TO_CENTER" <?php echo $this->input->get('transfer_type') == 'CENTRAL_TO_CENTER' ? 'selected' : ''; ?>>Central to Center</option>
                                    <option value="CENTER_TO_CENTER" <?php echo $this->input->get('transfer_type') == 'CENTER_TO_CENTER' ? 'selected' : ''; ?>>Center to Center</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From Center:</label>
                                <select name="from_center_id" class="form-control">
                                    <option value="">All Centers</option>
                                    <?php foreach($centers as $center): ?>
                                        <option value="<?php echo $center->ID; ?>" <?php echo $this->input->get('from_center_id') == $center->ID ? 'selected' : ''; ?>>
                                            <?php echo $center->center_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>To Center:</label>
                                <select name="to_center_id" class="form-control">
                                    <option value="">All Centers</option>
                                    <?php foreach($centers as $center): ?>
                                        <option value="<?php echo $center->ID; ?>" <?php echo $this->input->get('to_center_id') == $center->ID ? 'selected' : ''; ?>>
                                            <?php echo $center->center_name; ?>
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
                                    <option value="COMPLETED" <?php echo $this->input->get('status') == 'COMPLETED' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="CANCELLED" <?php echo $this->input->get('status') == 'CANCELLED' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="<?php echo base_url('stocks_new/transfers'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transfers Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Transfer List
                        <span class="badge pull-right"><?php echo count($transfers); ?> transfers</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="transfersTable">
                                <thead>
                                    <tr>
                                        <th>Transfer #</th>
                                        <th>Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Department</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Quantity</th>
                                        <th>Value</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($transfers) && is_array($transfers)): ?>
                                        <?php foreach($transfers as $transfer): ?>
                                            <?php if(isset($transfer->transfer_number) && !empty($transfer->transfer_number)): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($transfer->transfer_number); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        <?php echo isset($transfer->transfer_type) ? htmlspecialchars(str_replace('_', ' ', $transfer->transfer_type)) : 'N/A'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo isset($transfer->from_center) && !empty($transfer->from_center) ? htmlspecialchars($transfer->from_center) : 'Central Warehouse'; ?></td>
                                                <td><?php echo isset($transfer->to_center) ? htmlspecialchars($transfer->to_center) : 'N/A'; ?></td>
                                                <td><?php echo isset($transfer->to_department) && !empty($transfer->to_department) ? htmlspecialchars($transfer->to_department) : 'N/A'; ?></td>
                                                <td><?php echo isset($transfer->transfer_date) && !empty($transfer->transfer_date) ? date('M d, Y', strtotime($transfer->transfer_date)) : 'N/A'; ?></td>
                                                <td><?php echo isset($transfer->total_items) && is_numeric($transfer->total_items) ? number_format($transfer->total_items) : '0'; ?></td>
                                                <td><?php echo isset($transfer->total_quantity) && is_numeric($transfer->total_quantity) ? number_format($transfer->total_quantity) : '0'; ?></td>
                                                <td>₹<?php echo isset($transfer->total_value) && is_numeric($transfer->total_value) ? number_format($transfer->total_value, 2) : '0.00'; ?></td>
                                                <td>
                                                    <?php if(isset($transfer->status) && !empty($transfer->status)): ?>
                                                    <span class="badge <?php 
                                                        echo $transfer->status == 'COMPLETED' ? 'badge-success' : 
                                                            ($transfer->status == 'APPROVED' ? 'badge-info' : 
                                                            ($transfer->status == 'CANCELLED' ? 'badge-danger' : 'badge-warning')); 
                                                    ?>">
                                                        <?php echo htmlspecialchars($transfer->status); ?>
                                                    </span>
                                                    <?php else: ?>
                                                    <span class="badge badge-default">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?php echo base_url('stocks_new/edit_transfer/' . (isset($transfer->id) ? $transfer->id : '')); ?>">
                                                                <i class="fa fa-edit"></i> View/Edit
                                                            </a></li>
                                                            <?php if(isset($transfer->status) && $transfer->status == 'DRAFT'): ?>
                                                                <li><a href="<?php echo base_url('stocks_new/approve_transfer/' . (isset($transfer->id) ? $transfer->id : '')); ?>" onclick="return confirm('Are you sure you want to approve this transfer?')">
                                                                    <i class="fa fa-check"></i> Approve
                                                                </a></li>
                                                            <?php endif; ?>
                                                            <li><a href="<?php echo base_url('stocks_new/transfer_details/' . (isset($transfer->id) ? $transfer->id : '')); ?>">
                                                                <i class="fa fa-eye"></i> View Details
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                    <?php if(empty($transfers) || !is_array($transfers) || count(array_filter($transfers, function($t) { return isset($t->transfer_number) && !empty($t->transfer_number); })) == 0): ?>
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No transfers found. The database table 'stock_transfers' may not exist. <a href="<?php echo base_url('stocks_new/add_transfer'); ?>">Create your first transfer</a>
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
        
        <!-- Transfer Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-exchange fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count($transfers); ?></div>
                                <div>Total Transfers</div>
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
                                <div class="huge"><?php echo count(array_filter($transfers, function($t) { return $t->status == 'COMPLETED'; })); ?></div>
                                <div>Completed</div>
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
                                <div class="huge"><?php echo count(array_filter($transfers, function($t) { return in_array($t->status, ['DRAFT', 'PENDING', 'APPROVED']); })); ?></div>
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
                                <div class="huge">₹<?php echo number_format(array_sum(array_column($transfers, 'total_value')), 0); ?></div>
                                <div>Total Value</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FEFO Transfer Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-info-circle"></i> FEFO Transfer Process
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Transfer Process:</h4>
                                <ol>
                                    <li><strong>Create Transfer:</strong> Select source and destination locations</li>
                                    <li><strong>Add Items:</strong> Select batches with FEFO priority (earliest expiry first)</li>
                                    <li><strong>Review:</strong> Check quantities and expiry dates</li>
                                    <li><strong>Approve:</strong> Authorize the transfer</li>
                                    <li><strong>Execute:</strong> System automatically updates stock levels</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h4>FEFO Benefits:</h4>
                                <ul>
                                    <li>Automatic batch selection by expiry date</li>
                                    <li>Reduces medicine wastage</li>
                                    <li>Ensures patient safety</li>
                                    <li>Complies with regulatory requirements</li>
                                    <li>Complete audit trail for all movements</li>
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
    // Check if table has valid data before initializing DataTables
    var table = $('#transfersTable');
    var tbody = table.find('tbody');
    var rows = tbody.find('tr');
    var validRows = rows.filter(function() {
        // Check if row has proper number of cells and is not the "no data" row
        return $(this).find('td').length === 10 && !$(this).find('td[colspan]').length;
    });
    
    console.log('Total rows:', rows.length);
    console.log('Valid rows:', validRows.length);
    
    if(validRows.length > 0) {
        try {
            $('#transfersTable').DataTable({
                "pageLength": 25,
                "order": [[ 4, "desc" ]], // Sort by date descending
                "columnDefs": [
                    { "orderable": false, "targets": 9 }
                ],
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "No transfer data available",
                    "zeroRecords": "No matching records found"
                },
                "processing": true,
                "deferRender": true
            });
            console.log('DataTables initialized successfully');
        } catch(e) {
            console.error('DataTables initialization failed:', e);
        }
    } else {
        console.log('No valid data rows found, skipping DataTables initialization');
        // Add basic styling to make it look like a table
        table.addClass('table-striped table-bordered table-hover');
    }
});

function approveAllPending() {
    if(confirm('Are you sure you want to approve ALL pending transfers? This will move stock and cannot be undone.')) {
        window.location.href = '<?php echo base_url("stocks_new/approve_all_pending_transfers"); ?>';
    }
}
</script>

