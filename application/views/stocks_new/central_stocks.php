<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-building-o"></i> Central Stocks
            <small>Central warehouse inventory management</small>
        </h1>
    </div>
</div>

<!-- Breadcrumb -->
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
            <li class="active">Central Stocks</li>
        </ol>
    </div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-filter"></i> Filters
            </div>
            <div class="panel-body">
                <form method="get" action="<?php echo base_url('stocks_new/central_stocks'); ?>" class="form-inline">
                    <div class="form-group">
                        <label>Medicine:</label>
                        <select name="medicine_id" class="form-control">
                            <option value="">All Medicines</option>
                            <?php foreach($medicines as $medicine): ?>
                                <option value="<?php echo $medicine->id; ?>" <?php echo ($selected_medicine_id == $medicine->id) ? 'selected' : ''; ?>>
                                    <?php echo $medicine->medicine_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Batch Number:</label>
                        <input type="text" name="batch_number" class="form-control" placeholder="Batch number" value="<?php echo $selected_batch_number; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Status:</label>
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="ACTIVE" <?php echo ($selected_status == 'ACTIVE') ? 'selected' : ''; ?>>Active</option>
                            <option value="INACTIVE" <?php echo ($selected_status == 'INACTIVE') ? 'selected' : ''; ?>>Inactive</option>
                            <option value="QUARANTINE" <?php echo ($selected_status == 'QUARANTINE') ? 'selected' : ''; ?>>Quarantine</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Filter
                    </button>
                    <a href="<?php echo base_url('stocks_new/central_stocks'); ?>" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Clear
                    </a>
                    <a href="<?php echo base_url('stocks_new/central_stocks_export'); ?>" class="btn btn-success">
                        <i class="fa fa-download"></i> Export
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Central Stocks Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Central Stocks List
                <span class="badge pull-right"><?php echo count($central_stocks); ?> items</span>
            </div>
            <div class="panel-body">
                <?php if(!empty($central_stocks)): ?>
                    <div class="table-responsive">
                        <table id="centralStocksTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Batch Number</th>
                                    <th>Brand</th>
                                    <th>Vendor</th>
                                    <th>Expiry Date</th>
                                    <th>Expiry Days</th>
                                    <th>Quantity</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($central_stocks as $stock): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $stock->medicine_name; ?></strong><br>
                                            <small class="text-muted"><?php echo $stock->medicine_code; ?></small>
                                        </td>
                                        <td><?php echo $stock->batch_number; ?></td>
                                        <td><?php echo $stock->brand_name; ?></td>
                                        <td><?php echo $stock->vendor_name; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($stock->expiry_date)); ?></td>
                                        <td>
                                            <?php if($stock->expiry_days < 0): ?>
                                                <span class="label label-danger">Expired (<?php echo abs($stock->expiry_days); ?> days)</span>
                                            <?php elseif($stock->expiry_days <= 30): ?>
                                                <span class="label label-warning">Expiring Soon (<?php echo $stock->expiry_days; ?> days)</span>
                                            <?php else: ?>
                                                <span class="label label-success"><?php echo $stock->expiry_days; ?> days</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo $stock->quantity; ?></strong>
                                        </td>
                                        <td>₹<?php echo number_format($stock->purchase_price, 2); ?></td>
                                        <td>₹<?php echo number_format($stock->selling_price, 2); ?></td>
                                        <td>
                                            <?php if($stock->status == 'ACTIVE'): ?>
                                                <span class="label label-success">Active</span>
                                            <?php elseif($stock->status == 'INACTIVE'): ?>
                                                <span class="label label-default">Inactive</span>
                                            <?php elseif($stock->status == 'QUARANTINE'): ?>
                                                <span class="label label-warning">Quarantine</span>
                                            <?php else: ?>
                                                <span class="label label-info"><?php echo $stock->status; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-success" onclick="updateStockStatus(<?php echo $stock->id; ?>, 'ACTIVE')">
                                                    <i class="fa fa-check"></i> Activate
                                                </button>
                                                <button type="button" class="btn btn-xs btn-warning" onclick="updateStockStatus(<?php echo $stock->id; ?>, 'INACTIVE')">
                                                    <i class="fa fa-pause"></i> Deactivate
                                                </button>
                                                <!-- <button type="button" class="btn btn-xs btn-danger" onclick="updateStockStatus(<?php echo $stock->id; ?>, 'QUARANTINE')">
                                                    <i class="fa fa-ban"></i> Quarantine
                                                </button> -->
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> No central stocks found matching your criteria.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    <?php if(!empty($central_stocks)): ?>
    $('#centralStocksTable').DataTable({
        "pageLength": 25,
        "order": [[ 6, "asc" ]], // Sort by Expiry Date column (0-based index 4)
        "columnDefs": [
            { "orderable": false, "targets": 10 } // Actions column (0-based index 10)
        ],
        "language": {
            "emptyTable": "No central stocks found",
            "zeroRecords": "No matching central stocks found"
        },
        "responsive": true,
        "autoWidth": false
    });
    <?php endif; ?>
});


function updateStockStatus(stockId, status) {
    if(confirm('Are you sure you want to update the stock status?')) {
        $.ajax({
            url: '<?php echo base_url("stocks_new/update_central_stock_status"); ?>',
            type: 'POST',
            data: {
                stock_id: stockId,
                status: status
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert('Stock status updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating stock status.');
            }
        });
    }
}

</script>
