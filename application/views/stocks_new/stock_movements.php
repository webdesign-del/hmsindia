<?php
// Stock Movements History - Batch-specific stock movement tracking
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-list-alt"></i> Stock Movement History
                        <?php if(isset($batch_details) && $batch_details): ?>
                            - Batch: <?php echo htmlspecialchars($batch_details->batch_number); ?>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="panel-body">
                    
                    <!-- Batch Selection -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Batch to View History:</label>
                                <select class="form-control" id="batchSelect" onchange="changeBatch()">
                                    <option value="">Select a batch...</option>
                                    <?php if(isset($batches) && !empty($batches)): ?>
                                        <?php foreach($batches as $batch): ?>
                                            <option value="<?php echo $batch->id; ?>" 
                                                    <?php echo (isset($selected_batch_id) && $selected_batch_id == $batch->id) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($batch->batch_number); ?> 
                                                (<?php echo htmlspecialchars($batch->medicine_name); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <a href="<?php echo base_url('stocks_new/stock_tracking_panel'); ?>" class="btn btn-info">
                                        <i class="fa fa-search"></i> Stock Tracking Panel
                                    </a>
                                    <a href="<?php echo base_url('stocks_new/batches'); ?>" class="btn btn-default">
                                        <i class="fa fa-list"></i> All Batches
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(isset($batch_details) && $batch_details): ?>
                    <!-- Batch Information -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Batch Information</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Batch Number:</strong><br>
                                            <?php echo htmlspecialchars($batch_details->batch_number); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Medicine:</strong><br>
                                            <?php echo htmlspecialchars($batch_details->medicine_name); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Expiry Date:</strong><br>
                                            <?php echo date('d-m-Y', strtotime($batch_details->expiry_date)); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Current Stock:</strong><br>
                                            <span class="label label-success"><?php echo isset($batch_details->current_stock) ? $batch_details->current_stock : ($batch_details->quantity_remaining ?? 0); ?> units</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Stock Movement History Table -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Movement History</h4>
                                </div>
                                <div class="panel-body">
                                    <?php if(isset($batch_movements) && !empty($batch_movements)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date & Time</th>
                                                    <th>Movement Type</th>
                                                    <th>From Center</th>
                                                    <th>To Center</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total Value</th>
                                                    <th>Reference</th>
                                                    <th>Patient</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($batch_movements as $movement): ?>
                                                <tr>
                                                    <td><?php echo date('d-m-Y H:i', strtotime($movement->created_at)); ?></td>
                                                    <td>
                                                        <span class="label label-<?php 
                                                            echo (in_array($movement->movement_type, ['PURCHASE', 'TRANSFER_IN'])) ? 'success' : 
                                                                (in_array($movement->movement_type, ['TRANSFER_OUT', 'SALE']) ? 'danger' : 'info'); 
                                                        ?>">
                                                            <?php echo $movement->movement_type; ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($movement->from_center ?: 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($movement->to_center ?: 'N/A'); ?></td>
                                                    <td>
                                                        <span class="<?php echo ($movement->quantity_change > 0) ? 'text-success' : 'text-danger'; ?>">
                                                            <?php echo ($movement->quantity_change > 0) ? '+' : ''; ?><?php echo $movement->quantity_change; ?>
                                                        </span>
                                                    </td>
                                                    <td>₹<?php echo number_format($movement->unit_price, 2); ?></td>
                                                    <td>₹<?php echo number_format($movement->total_value, 2); ?></td>
                                                    <td><?php echo htmlspecialchars($movement->reference_number ?: 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($movement->patient_name ?: 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($movement->remarks ?: 'N/A'); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i>
                                        <?php if(isset($batch_details) && $batch_details): ?>
                                            No movement history found for this batch.
                                        <?php else: ?>
                                            Please select a batch to view its movement history.
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary Statistics -->
                    <?php if(isset($batch_movements) && !empty($batch_movements)): ?>
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-3">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Total In</h4>
                                </div>
                                <div class="panel-body">
                                    <h3><?php 
                                        $total_in = 0;
                                        foreach($batch_movements as $movement) {
                                            if($movement->movement_type == 'PURCHASE' || $movement->movement_type == 'TRANSFER_IN') {
                                                $total_in += $movement->quantity_change;
                                            }
                                        }
                                        echo $total_in;
                                    ?></h3>
                                    <p>Units received</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Total Out</h4>
                                </div>
                                <div class="panel-body">
                                    <h3><?php 
                                        $total_out = 0;
                                        foreach($batch_movements as $movement) {
                                            if($movement->movement_type == 'TRANSFER_OUT' || $movement->movement_type == 'SALE') {
                                                $total_out += abs($movement->quantity_change);
                                            }
                                        }
                                        echo $total_out;
                                    ?></h3>
                                    <p>Units distributed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Net Movement</h4>
                                </div>
                                <div class="panel-body">
                                    <h3><?php echo $total_in - $total_out; ?></h3>
                                    <p>Net stock change</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Total Movements</h4>
                                </div>
                                <div class="panel-body">
                                    <h3><?php echo count($batch_movements); ?></h3>
                                    <p>Movement records</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeBatch() {
    var batchId = document.getElementById('batchSelect').value;
    if(batchId) {
        window.location.href = '<?php echo base_url("stocks_new/stock_movements"); ?>?batch_id=' + batchId;
    } else {
        window.location.href = '<?php echo base_url("stocks_new/stock_movements"); ?>';
    }
}
</script>
