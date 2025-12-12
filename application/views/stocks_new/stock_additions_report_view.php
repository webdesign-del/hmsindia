<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-plus-square"></i> Stock Additions Report
            <small>Log of all stock increases (purchases, transfers in, adjustments)</small>
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
                <form action="<?php echo base_url('stocks_new/stock_additions_report'); ?>" method="get" class="form-inline">
                    
                    <div class="form-group">
                        <label>Location:</label>
                        <select name="location_id" class="form-control">
                            <option value="">All Locations</option>
                            <option value="central" <?php echo $this->input->get('location_id') == 'central' ? 'selected' : ''; ?>>
                                Central Warehouse
                            </option>
                            <?php foreach($centers as $center): ?>
                                <option value="<?php echo $center->ID; ?>" <?php echo $this->input->get('location_id') == $center->ID ? 'selected' : ''; ?>>
                                    <?php echo $center->center_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                        <!-- *** NEW: Transaction Type Filter *** -->
                    <div class="form-group">
                        <label>Transaction Type:</label>
                        <select name="movement_type" class="form-control">
                            <option value="">All Types</option>
                            <option value="PURCHASE" <?php echo $this->input->get('movement_type') == 'PURCHASE' ? 'selected' : ''; ?>>PURCHASE</option>
                            <option value="TRANSFER_IN" <?php echo $this->input->get('movement_type') == 'TRANSFER_IN' ? 'selected' : ''; ?>>TRANSFER_IN</option>
                            <option value="ADJUSTMENT_IN" <?php echo $this->input->get('movement_type') == 'ADJUSTMENT_IN' ? 'selected' : ''; ?>>ADJUSTMENT_IN</option>
                            <option value="SALE_RETURN" <?php echo $this->input->get('movement_type') == 'SALE_RETURN' ? 'selected' : ''; ?>>SALE_RETURN</option>
                            <option value="AUDIT_IN" <?php echo $this->input->get('movement_type') == 'AUDIT_IN' ? 'selected' : ''; ?>>AUDIT_IN</option>
                        </select>
                    </div>
                    
                    <!-- *** NEW: Batch Number Filter *** -->
                    <div class="form-group">
                        <label>Batch #:</label>
                        <input type="text" name="batch_number" class="form-control" value="<?php echo htmlspecialchars($this->input->get('batch_number')); ?>" placeholder="Enter batch number">
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
                    <a href="<?php echo base_url('stocks_new/stock_additions_report'); ?>" class="btn btn-default">
                        <i class="fa fa-refresh"></i> Reset
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Stock Additions Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-list"></i> Report Results
                <span class="badge pull-right"><?php echo count($stock_additions); ?> records found</span>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="additionsTable">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Medicine</th>
                                <th>Batch #</th>
                                <th>Transaction Type</th>
                                <th>To Location</th>
                                <th>Qty Added</th>
                                <th>Qty Before</th>
                                <th>Qty After</th>
                                <th>User</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($stock_additions)): ?>
                                <?php foreach($stock_additions as $log): ?>
                                    <tr>
                                        <td><?php echo date('d-M-Y H:i A', strtotime($log->created_at)); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($log->medicine_name); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($log->medicine_code); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($log->batch_number); ?></td>
                                        <td>
                                            <span class="badge badge-success"><?php echo htmlspecialchars($log->movement_type); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($log->location_name); ?></td>
                                        <td><strong>+<?php echo number_format($log->quantity_change); ?></strong></td>
                                        <td><?php echo number_format($log->quantity_before); ?></td>
                                        <td><?php echo number_format($log->quantity_after); ?></td>
                                        <td><?php echo htmlspecialchars($log->user_name); ?></td>
                                        <td><?php echo htmlspecialchars($log->remarks); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var table = $('#additionsTable').DataTable({
        "pageLength": 50,
        "order": [[ 0, "desc" ]],
        "responsive": true
    });

    // Prevent initialization errors when only one "no data" row exists
    if ($('#additionsTable tbody tr td').length === 1) {
        table.clear().draw();
    }
});

</script>