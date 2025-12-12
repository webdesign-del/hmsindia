<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-archive"></i> Batch Management
                    <small>Manage medicine batches and inventory</small>
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
                        <a href="<?php echo base_url('stocks_new/add_medicine'); ?>" class="btn btn-success">
                            <i class="fa fa-capsules"></i> Add New Medicine
                        </a>
                        <a href="<?php echo base_url('stocks_new/stock_levels'); ?>" class="btn btn-info">
                            <i class="fa fa-building-o"></i> View Stock Levels
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
                        <form action="<?php echo base_url('stocks_new/batches'); ?>" method="get" class="form-inline">
                            <div class="form-group">
                                <label>Medicine:</label>
                                <select name="medicine_id" class="form-control">
                                    <option value="">All Medicines</option>
                                    <?php foreach($medicines as $medicine): ?>
                                        <option value="<?php echo $medicine->id; ?>" <?php echo $this->input->get('medicine_id') == $medicine->id ? 'selected' : ''; ?>>
                                            <?php echo $medicine->medicine_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
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
                                <label>Batch Number:</label>
                                <input type="text" name="batch_number" class="form-control" placeholder="Enter batch number" value="<?php echo $this->input->get('batch_number'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="batch_status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="ACTIVE" <?php echo $this->input->get('batch_status') == 'ACTIVE' ? 'selected' : ''; ?>>Active</option>
                                    <option value="INACTIVE" <?php echo $this->input->get('batch_status') == 'INACTIVE' ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="EXPIRED" <?php echo $this->input->get('batch_status') == 'EXPIRED' ? 'selected' : ''; ?>>Expired</option>
                                    <option value="QUARANTINE" <?php echo $this->input->get('batch_status') == 'QUARANTINE' ? 'selected' : ''; ?>>Quarantine</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="<?php echo base_url('stocks_new/batches'); ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Batches Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list"></i> Batches List
                        <span class="badge pull-right"><?php echo count($batches); ?> batches</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="batchesTable">
                                <thead>
                                    <tr>
                                        <th>Batch Number</th>
                                        <th>Medicine</th>
                                        <th>Brand</th>
                                        <th>Vendor</th>
                                        <th>Expiry Date</th>
                                        <th>Days Left</th>
                                        <th>Vendor Price with gst</th>
                                        <th>Mrp</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Quality</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($batches)): ?>
                                        <?php foreach($batches as $batch): ?>
                                            <tr class="<?php echo (isset($batch->expiry_days) && $batch->expiry_days < 0) ? 'danger' : ((isset($batch->expiry_days) && $batch->expiry_days <= 30) ? 'warning' : ''); ?>">
                                                <td><?php echo isset($batch->batch_number) ? $batch->batch_number : 'N/A'; ?></td>
                                                <td><?php echo isset($batch->medicine_name) ? $batch->medicine_name : 'N/A'; ?></td>
                                                <td><?php echo isset($batch->brand_name) ? $batch->brand_name : 'N/A'; ?></td>
                                                <td><?php echo isset($batch->vendor_name) ? $batch->vendor_name : 'N/A'; ?></td>
                                                <td><?php echo isset($batch->expiry_date) ? date('M d, Y', strtotime($batch->expiry_date)) : 'N/A'; ?></td>
                                                <td>
                                                    <?php if(isset($batch->expiry_days)): ?>
                                                        <span class="badge <?php echo $batch->expiry_days < 0 ? 'badge-danger' : ($batch->expiry_days <= 30 ? 'badge-warning' : 'badge-success'); ?>">
                                                            <?php echo $batch->expiry_days; ?> days
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>₹<?php echo isset($batch->purchase_price) ? number_format($batch->purchase_price, 2) : '0.00'; ?></td>
                                                <td>₹<?php echo isset($batch->selling_price) ? number_format($batch->selling_price, 2) : '0.00'; ?></td>
                                                <td><?php echo isset($batch->quantity_remaining) ? number_format($batch->quantity_remaining) : '0'; ?></td>
                                                <td>
                                                    <span class="badge <?php echo (isset($batch->batch_status) && $batch->batch_status == 'ACTIVE') ? 'badge-success' : ((isset($batch->batch_status) && $batch->batch_status == 'EXPIRED') ? 'badge-danger' : 'badge-warning'); ?>">
                                                        <?php echo isset($batch->batch_status) ? $batch->batch_status : 'UNKNOWN'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo (isset($batch->quality_status) && $batch->quality_status == 'APPROVED') ? 'badge-success' : ((isset($batch->quality_status) && $batch->quality_status == 'REJECTED') ? 'badge-danger' : 'badge-warning'); ?>">
                                                        <?php echo isset($batch->quality_status) ? $batch->quality_status : 'PENDING'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo isset($batch->created_at) ? date('M d, Y', strtotime($batch->created_at)) : 'N/A'; ?></td>   
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?php echo base_url('stocks_new/edit_batch/' . $batch->id); ?>">
                                                                <i class="fa fa-edit"></i> Edit Batch
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/stock_levels?batch_id=' . $batch->id); ?>">
                                                                <i class="fa fa-building-o"></i> View Stock
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/transfers?batch_id=' . $batch->id); ?>">
                                                                <i class="fa fa-exchange"></i> Transfer Stock
                                                            </a></li>
                                                            <li><a href="<?php echo base_url('stocks_new/sales?batch_id=' . $batch->id); ?>">
                                                                <i class="fa fa-shopping-cart"></i> Sell Stock
                                                            </a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="#" onclick="updateBatchStatus(<?php echo $batch->id; ?>, 'ACTIVE')">
                                                                <i class="fa fa-check text-success"></i> Activate Batch
                                                            </a></li>
                                                            <li><a href="#" onclick="updateBatchStatus(<?php echo $batch->id; ?>, 'INACTIVE')">
                                                                <i class="fa fa-pause text-warning"></i> Deactivate Batch
                                                            </a></li>
                                                            <!-- <li><a href="#" onclick="updateBatchStatus(<?php echo $batch->id; ?>, 'QUARANTINE')">
                                                                <i class="fa fa-ban text-danger"></i> Quarantine Batch
                                                            </a></li> -->
                                                            <!-- <li><a href="#" onclick="updateBatchStatus(<?php echo $batch->id; ?>, 'DISPOSED')">
                                                                <i class="fa fa-trash-o text-danger"></i> Mark as Disposed
                                                            </a></li> -->
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="12" class="text-center text-muted">
                                                <i class="fa fa-info-circle fa-2x"></i><br>
                                                No batches found. <a href="<?php echo base_url('stocks_new/add_batch'); ?>">Add your first batch</a>
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
        
        <!-- Batch Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-archive fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count($batches); ?></div>
                                <div>Total Batches</div>
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
                                <div class="huge"><?php echo count(array_filter($batches, function($b) { return $b->batch_status == 'ACTIVE'; })); ?></div>
                                <div>Active Batches</div>
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
                                <div class="huge">
                                    <?php 
                                    // Corrected logic: Check status, quantity, AND expiry
                                    echo count(array_filter($batches, function($b) { 
                                        return isset($b->batch_status) && $b->batch_status == 'ACTIVE' &&
                                            isset($b->quantity_remaining) && $b->quantity_remaining > 0 &&
                                            isset($b->expiry_days) && $b->expiry_days <= 30 && $b->expiry_days > 0; 
                                    })); 
                                    ?>
                                </div>
                                <div>Expiring Soon</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-times-circle fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count(array_filter($batches, function($b) { return $b->expiry_days < 0; })); ?></div>
                                <div>Expired</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
$(document).ready(function() {
    <?php if(!empty($batches)): ?>
    $('#batchesTable').DataTable({
        "pageLength": 25,
        "order": [[ 4, "asc" ]], // Sort by expiry date
        "columnDefs": [
            { "orderable": false, "targets": 11 } // Actions column
        ],
        "language": {
            "emptyTable": "No batches found",
            "zeroRecords": "No matching batches found"
        },
        "responsive": true,
        "autoWidth": false
    });
    <?php endif; ?>
});

function updateBatchStatus(batchId, status) {
    var statusText = status.charAt(0) + status.slice(1).toLowerCase();
    if(confirm('Are you sure you want to ' + statusText.toLowerCase() + ' this batch?')) {
        $.ajax({
            url: '<?php echo base_url("stocks_new/update_batch_status"); ?>',
            type: 'POST',
            data: {
                batch_id: batchId,
                status: status
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert('Batch status updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating batch status.');
            }
        });
    }
}
</script>

