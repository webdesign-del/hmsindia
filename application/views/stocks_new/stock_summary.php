<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">
                    <i class="fa fa-chart-bar"></i> Stock Summary
                    <small>Overview of all medicine stock levels</small>
                </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Medicine Stock Summary</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="stock_summary_table">
                                <thead>
                                    <tr>
                                        <th>Medicine Code</th>
                                        <th>Medicine Name</th>
                                        <th>Brand</th>
                                        <th>Generic Name</th>
                                        <th>Total Batches</th>
                                        <th>Total Quantity</th>
                                        <th>Avg Price</th>
                                        <th>Total Value</th>
                                        <th>Earliest Expiry</th>
                                        <th>Expiring Soon</th>
                                        <th>Expired</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($stock_summary) && !empty($stock_summary)): ?>
                                        <?php foreach($stock_summary as $item): ?>
                                            <tr>
                                                <td><?php echo isset($item->medicine_code) ? htmlspecialchars($item->medicine_code) : 'N/A'; ?></td>
                                                <td>
                                                    <strong><?php echo isset($item->medicine_name) ? htmlspecialchars($item->medicine_name) : 'N/A'; ?></strong>
                                                </td>
                                                <td><?php echo isset($item->brand_name) ? htmlspecialchars($item->brand_name) : 'N/A'; ?></td>
                                                <td><?php echo isset($item->generic_name) ? htmlspecialchars($item->generic_name) : 'N/A'; ?></td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo isset($item->total_batches) ? number_format($item->total_batches) : '0'; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo (isset($item->total_quantity) && $item->total_quantity <= 10) ? 'badge-danger' : 'badge-success'; ?>">
                                                        <?php echo isset($item->total_quantity) ? number_format($item->total_quantity) : '0'; ?>
                                                    </span>
                                                </td>
                                                <td>₹<?php echo isset($item->avg_price) ? number_format($item->avg_price, 2) : '0.00'; ?></td>
                                                <td>₹<?php echo isset($item->total_value) ? number_format($item->total_value, 2) : '0.00'; ?></td>
                                                <td><?php echo isset($item->earliest_expiry) ? date('M d, Y', strtotime($item->earliest_expiry)) : 'N/A'; ?></td>
                                                <td>
                                                    <span class="badge <?php echo (isset($item->expiring_soon_count) && $item->expiring_soon_count > 0) ? 'badge-warning' : 'badge-success'; ?>">
                                                        <?php echo isset($item->expiring_soon_count) ? $item->expiring_soon_count : '0'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo (isset($item->expired_count) && $item->expired_count > 0) ? 'badge-danger' : 'badge-success'; ?>">
                                                        <?php echo isset($item->expired_count) ? $item->expired_count : '0'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url('stocks_new/edit_medicine/' . (isset($item->medicine_id) ? $item->medicine_id : 0)); ?>" 
                                                           class="btn btn-sm btn-primary" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('stocks_new/batches?medicine_id=' . (isset($item->medicine_id) ? $item->medicine_id : 0)); ?>" 
                                                           class="btn btn-sm btn-info" title="View Batches">
                                                            <i class="fa fa-boxes"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="12" class="text-center">No stock data available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#stock_summary_table').DataTable({
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 11 }
        ]
    });
});
</script>
