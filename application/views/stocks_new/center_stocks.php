<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-building"></i> Center Stocks
            <small>Center-wise inventory management</small>
        </h1>
    </div>
</div>

<!-- Breadcrumb -->
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks_new/dashboard'); ?>">Dashboard</a></li>
            <li class="active">Center Stocks</li>
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
                <form method="get" action="<?php echo base_url('stocks_new/center_stocks'); ?>" class="form-inline">
                    <div class="form-group">
                        <label>Center:</label>
                        <select name="center_id" class="form-control" id ="centerFilter">
                            <option value="">All Centers</option>
                            <?php foreach($centers as $center): ?>
                                <option value="<?php echo $center->ID; ?>" <?php echo ($selected_center_id == $center->ID) ? 'selected' : ''; ?>>
                                    <?php echo $center->center_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Medicine:</label>
                        <select name="medicine_id" class="form-control" id ="medicineFilter">
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
                        <input type="text" name="batch_number" id="batchFilter" class="form-control" placeholder="Batch number" value="<?php echo $selected_batch_number; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Status:</label>
                        <select name="status" class="form-control" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="ACTIVE" <?php echo ($selected_status == 'ACTIVE') ? 'selected' : ''; ?>>Active</option>
                            <option value="INACTIVE" <?php echo ($selected_status == 'INACTIVE') ? 'selected' : ''; ?>>Inactive</option>
                            <option value="QUARANTINE" <?php echo ($selected_status == 'QUARANTINE') ? 'selected' : ''; ?>>Quarantine</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Department:</label>
                        <select name="department" class="form-control" id="departmentFilter">
                            <option value="">All Departments</option>
                            <option value="CASH MEDICINE NOIDA" <?php echo ($selected_department == 'CASH MEDICINE NOIDA') ? 'selected' : ''; ?>>CASH MEDICINE NOIDA</option>
                            <option value="CASH MEDICINE GGN" <?php echo ($selected_department == 'CASH MEDICINE GGN') ? 'selected' : ''; ?>>CASH MEDICINE GGN</option>
                            <option value="CASH MEDICINE BASANT LOK" <?php echo ($selected_department == 'CASH MEDICINE BASANT LOK') ? 'selected' : ''; ?>>CASH MEDICINE BASANT LOK</option>
                            <option value="CASH MEDICINE SRINAGAR" <?php echo ($selected_department == 'CASH MEDICINE SRINAGAR') ? 'selected' : ''; ?>>CASH MEDICINE SRINAGAR</option>
                            <option value="CASH MEDICINE GHAZIABAD" <?php echo ($selected_department == 'CASH MEDICINE GHAZIABAD') ? 'selected' : ''; ?>>CASH MEDICINE GHAZIABAD</option>
                            <option value="CASH MEDICINE  ROHINI" <?php echo ($selected_department == 'CASH MEDICINE  ROHINI') ? 'selected' : ''; ?>>CASH MEDICINE  ROHINI</option>
                            <option value="HORMONAL ROHINI" <?php echo ($selected_department == 'HORMONAL ROHINI') ? 'selected' : ''; ?>>HORMONAL ROHINI</option>
                            <option value="Hormonal Ghaziabad" <?php echo ($selected_department == 'Hormonal Ghaziabad') ? 'selected' : ''; ?>>Hormonal Ghaziabad</option>
                            <option value="HORMONAL SRINAGAR" <?php echo ($selected_department == 'HORMONAL SRINAGAR') ? 'selected' : ''; ?>>HORMONAL SRINAGAR</option>
                            <option value="Hormonal Basant Lok" <?php echo ($selected_department == 'Hormonal Basant Lok') ? 'selected' : ''; ?>>Hormonal Basant Lok</option>
                            <option value="Hormonal Gurgaon" <?php echo ($selected_department == 'Hormonal Gurgaon') ? 'selected' : ''; ?>>Hormonal Gurgaon</option>
                            <option value="Hormonal Noida" <?php echo ($selected_department == 'Hormonal Noida') ? 'selected' : ''; ?>>Hormonal Noida</option>
                            <option value="Embryologist Noida" <?php echo ($selected_department == 'Embryologist Noida') ? 'selected' : ''; ?>>Embryologist Noida</option>
                            <option value="OT Noida" <?php echo ($selected_department == 'OT Noida') ? 'selected' : ''; ?>>OT Noida</option>
                            <option value="OT Basant Lok" <?php echo ($selected_department == 'OT Basant Lok') ? 'selected' : ''; ?>>OT Basant Lok</option>
                            <option value="Embryology Basant Lok" <?php echo ($selected_department == 'Embryology Basant Lok') ? 'selected' : ''; ?>>Embryology Basant Lok</option>
                            <option value="Embryology Srinagar" <?php echo ($selected_department == 'Embryology Srinagar') ? 'selected' : ''; ?>>Embryology Srinagar</option>
                            <option value="OT Srinagar" <?php echo ($selected_department == 'OT Srinagar') ? 'selected' : ''; ?>>OT Srinagar</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Filter
                    </button>
                    <a href="<?php echo base_url('stocks_new/center_stocks'); ?>" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Clear
                    </a>
                    <a onclick="exportCenterStockReport()" class="btn btn-success">
                        <i class="fa fa-download"></i> Export
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Center Stocks Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Center Stocks List
                <span class="badge pull-right"><?php echo count($center_stocks); ?> items</span>
            </div>
            <div class="panel-body">
                <?php if(!empty($center_stocks)): ?>
                    <div class="table-responsive">
                        <table id="centerStocksTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Center</th>
                                    <th>Medicine</th>
                                    <th>Batch Number</th>
                                    <th>Brand</th>
                                    <th>Vendor</th>
                                    <th>Department</th>
                                    <th>Expiry Date</th>
                                    <th>Expiry Days</th>
                                    <th>Pack Size</th>
                                    <th>Quantity</th>
                                    <th>Vendor Price With gst</th>
                                    <th>Mrp</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($center_stocks as $stock): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $stock->center_name; ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo $stock->medicine_name; ?></strong><br>
                                            <small class="text-muted"><?php echo $stock->medicine_code; ?></small>
                                        </td>
                                        <td><?php echo $stock->batch_number; ?></td>
                                        <td><?php echo $stock->brand_name; ?></td>
                                        <td><?php echo $stock->vendor_name; ?></td>
                                        <td><?php echo $stock->department; ?></td>
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
                                            <strong><?php echo isset($stock->pack_size) && $stock->pack_size !== null ? $stock->pack_size : '1'; ?></strong>
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
                                                <button type="button" class="btn btn-xs btn-success" onclick="updateCenterStockStatus(<?php echo $stock->id; ?>, 'ACTIVE')">
                                                    <i class="fa fa-check"></i> Activate
                                                </button>
                                                <button type="button" class="btn btn-xs btn-warning" onclick="updateCenterStockStatus(<?php echo $stock->id; ?>, 'INACTIVE')">
                                                    <i class="fa fa-pause"></i> Deactivate
                                                </button>
                                                <!-- <button type="button" class="btn btn-xs btn-danger" onclick="updateCenterStockStatus(<?php echo $stock->id; ?>, 'QUARANTINE')">
                                                    <i class="fa fa-ban"></i> Quarantine
                                                </button> -->
                                                <button type="button" class="btn btn-xs btn-danger" onclick="deleteCenterStock(<?php echo $stock->id; ?>)">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> No center stocks found matching your criteria.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    <?php if(!empty($center_stocks)): ?>
    $('#centerStocksTable').DataTable({
        "pageLength": 25,
        "order": [[ 6, "asc" ]], // Sort by Expiry Date column (0-based index 6)
        "columnDefs": [
            { "orderable": false, "targets": 12 } // Actions column (0-based index 12)
        ],
        "language": {
            "emptyTable": "No center stocks found",
            "zeroRecords": "No matching center stocks found"
        },
        "responsive": true,
        "autoWidth": false
    });
    <?php endif; ?>
});
function updateCenterStockStatus(stockId, status) {
    if(confirm('Are you sure you want to update the stock status?')) {
        $.ajax({
            url: '<?php echo base_url("stocks_new/update_center_stock_status"); ?>',
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
function deleteCenterStock(stockId) {
    if(confirm('Are you sure you want to delete this center stock? This action cannot be undone.')) {
        $.ajax({
            url: '<?php echo base_url("stocks_new/delete_center_stock"); ?>',
            type: 'POST',
            data: {
                stock_id: stockId
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert('Center stock deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while deleting center stock.');
            }
        });
    }
}
function exportCenterStockReport() {
    var filters = {
        center_id: $('#centerFilter').val(),
        medicine_id: $('#medicineFilter').val(),
        batch_number: $('#batchFilter').val(),
        status: $('#statusFilter').val(),
        department: $('#departmentFilter').val(),
    };
    var queryString = $.param(filters);
    window.open('<?php echo base_url("stocks_new/center_stocks_export"); ?>?' + queryString, '_blank');
}
</script>
