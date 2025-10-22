<?php
// Stock Tracking Panel - Complete Stock Movement History
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-search"></i> Stock Tracking Panel
                    </h3>
                </div>
                <div class="panel-body">
                    
                    <!-- Search Filters -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Search by Medicine:</label>
                                <select class="form-control" id="medicineFilter">
                                    <option value="">All Medicines</option>
                                    <?php if(isset($medicines) && !empty($medicines)): ?>
                                        <?php foreach($medicines as $medicine): ?>
                                            <option value="<?php echo $medicine->id; ?>">
                                                <?php echo htmlspecialchars($medicine->medicine_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Search by Batch:</label>
                                <select class="form-control" id="batchFilter">
                                    <option value="">All Batches</option>
                                    <?php if(isset($batches) && !empty($batches)): ?>
                                        <?php foreach($batches as $batch): ?>
                                            <option value="<?php echo $batch->id; ?>">
                                                <?php echo htmlspecialchars($batch->batch_number); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Search by Center:</label>
                                <select class="form-control" id="centerFilter">
                                    <option value="">All Centers</option>
                                    <?php if(isset($centers) && !empty($centers)): ?>
                                        <?php foreach($centers as $center): ?>
                                            <option value="<?php echo $center->id; ?>">
                                                <?php echo htmlspecialchars($center->center_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date Range:</label>
                                <input type="date" class="form-control" id="dateFrom" placeholder="From Date">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>To Date:</label>
                                <input type="date" class="form-control" id="dateTo" placeholder="To Date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary btn-block" onclick="searchStockMovements()">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="btn btn-success btn-block" onclick="exportStockReport()">
                                    <i class="fa fa-download"></i> Export Report
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="btn btn-info btn-block" onclick="refreshData()">
                                    <i class="fa fa-refresh"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stock Movement Summary Cards -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Total Transfers</h4>
                                </div>
                                <div class="panel-body">
                                    <h2 id="totalTransfers">0</h2>
                                    <p>Stock transfers between centers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Total Sales</h4>
                                </div>
                                <div class="panel-body">
                                    <h2 id="totalSales">0</h2>
                                    <p>Medicines sold to patients</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Active Batches</h4>
                                </div>
                                <div class="panel-body">
                                    <h2 id="activeBatches">0</h2>
                                    <p>Batches currently in stock</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Expiring Soon</h4>
                                </div>
                                <div class="panel-body">
                                    <h2 id="expiringBatches">0</h2>
                                    <p>Batches expiring in 30 days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stock Movement History Table -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Stock Movement History</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="stockMovementsTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Medicine</th>
                                                    <th>Batch</th>
                                                    <th>Movement Type</th>
                                                    <th>From Center</th>
                                                    <th>To Center</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Reference</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be loaded via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transfer Details -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Recent Transfers</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="transfersTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Transfer #</th>
                                                    <th>Date</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Status</th>
                                                    <th>Items</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be loaded via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sales Details -->
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Recent Sales</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="salesTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Sale #</th>
                                                    <th>Date</th>
                                                    <th>Center</th>
                                                    <th>Patient</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be loaded via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
    // Initialize DataTables
    initializeTables();
    
    // Load initial data
    loadStockMovements();
    loadTransfers();
    loadSales();
    loadSummaryStats();
});

function initializeTables() {
    $('#stockMovementsTable').DataTable({
        "processing": true,
        "serverSide": false,
        "responsive": true,
        "autoWidth": false,
        "order": [[0, "desc"]],
        "language": {
            "emptyTable": "No stock movements found",
            "processing": "Loading stock movements..."
        }
    });
    
    $('#transfersTable').DataTable({
        "processing": true,
        "serverSide": false,
        "responsive": true,
        "autoWidth": false,
        "order": [[1, "desc"]],
        "pageLength": 5,
        "language": {
            "emptyTable": "No transfers found",
            "processing": "Loading transfers..."
        }
    });
    
    $('#salesTable').DataTable({
        "processing": true,
        "serverSide": false,
        "responsive": true,
        "autoWidth": false,
        "order": [[1, "desc"]],
        "pageLength": 5,
        "language": {
            "emptyTable": "No sales found",
            "processing": "Loading sales..."
        }
    });
}

function loadStockMovements() {
    $.ajax({
        url: '<?php echo base_url("stocks_new/get_stock_movements"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var table = $('#stockMovementsTable').DataTable();
            table.clear();
            
            if(data && data.length > 0) {
                $.each(data, function(index, movement) {
                    table.row.add([
                        movement.movement_date || '',
                        movement.medicine_name || '',
                        movement.batch_number || '',
                        movement.movement_type || '',
                        movement.from_center || '',
                        movement.to_center || '',
                        movement.quantity_change || 0,
                        '₹' + (movement.unit_price || 0),
                        movement.reference_number || '',
                        movement.status || ''
                    ]);
                });
            }
            table.draw();
        },
        error: function() {
            console.log('Error loading stock movements');
        }
    });
}

function loadTransfers() {
    $.ajax({
        url: '<?php echo base_url("stocks_new/get_transfers"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var table = $('#transfersTable').DataTable();
            table.clear();
            
            if(data && data.length > 0) {
                $.each(data, function(index, transfer) {
                    table.row.add([
                        transfer.transfer_number || '',
                        transfer.transfer_date || '',
                        transfer.from_center || '',
                        transfer.to_center || '',
                        transfer.status || '',
                        transfer.total_items || 0
                    ]);
                });
            }
            table.draw();
        },
        error: function() {
            console.log('Error loading transfers');
        }
    });
}

function loadSales() {
    $.ajax({
        url: '<?php echo base_url("stocks_new/get_sales"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var table = $('#salesTable').DataTable();
            table.clear();
            
            if(data && data.length > 0) {
                $.each(data, function(index, sale) {
                    table.row.add([
                        sale.sale_number || '',
                        sale.sale_date || '',
                        sale.center_name || '',
                        sale.patient_name || '',
                        '₹' + (sale.total_amount || 0),
                        sale.status || ''
                    ]);
                });
            }
            table.draw();
        },
        error: function() {
            console.log('Error loading sales');
        }
    });
}

function loadSummaryStats() {
    $.ajax({
        url: '<?php echo base_url("stocks_new/get_summary_stats"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if(data) {
                $('#totalTransfers').text(data.total_transfers || 0);
                $('#totalSales').text(data.total_sales || 0);
                $('#activeBatches').text(data.active_batches || 0);
                $('#expiringBatches').text(data.expiring_batches || 0);
            }
        },
        error: function() {
            console.log('Error loading summary stats');
        }
    });
}

function searchStockMovements() {
    var filters = {
        medicine_id: $('#medicineFilter').val(),
        batch_id: $('#batchFilter').val(),
        center_id: $('#centerFilter').val(),
        date_from: $('#dateFrom').val(),
        date_to: $('#dateTo').val()
    };
    
    $.ajax({
        url: '<?php echo base_url("stocks_new/search_stock_movements"); ?>',
        type: 'POST',
        data: filters,
        dataType: 'json',
        success: function(data) {
            var table = $('#stockMovementsTable').DataTable();
            table.clear();
            
            if(data && data.length > 0) {
                $.each(data, function(index, movement) {
                    table.row.add([
                        movement.movement_date || '',
                        movement.medicine_name || '',
                        movement.batch_number || '',
                        movement.movement_type || '',
                        movement.from_center || '',
                        movement.to_center || '',
                        movement.quantity_change || 0,
                        '₹' + (movement.unit_price || 0),
                        movement.reference_number || '',
                        movement.status || ''
                    ]);
                });
            }
            table.draw();
        },
        error: function() {
            console.log('Error searching stock movements');
        }
    });
}

function exportStockReport() {
    var filters = {
        medicine_id: $('#medicineFilter').val(),
        batch_id: $('#batchFilter').val(),
        center_id: $('#centerFilter').val(),
        date_from: $('#dateFrom').val(),
        date_to: $('#dateTo').val()
    };
    
    var queryString = $.param(filters);
    window.open('<?php echo base_url("stocks_new/export_stock_report"); ?>?' + queryString, '_blank');
}

function refreshData() {
    loadStockMovements();
    loadTransfers();
    loadSales();
    loadSummaryStats();
}
</script>
