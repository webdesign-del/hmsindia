<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-line-chart"></i> FEFO Analytics
            <small>Stock Rotation & Wastage Report</small>
        </h1>
    </div>
</div>

<!-- Monthly Wastage Chart -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <i class="fa fa-trash"></i> Monthly Wastage Value (Expired, Damaged, Disposed)
            </div>
            <div class="panel-body">
                <canvas id="wastageChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- At-Risk / Slow-Moving Stock Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <i class="fa fa-exclamation-triangle"></i> At-Risk Stock (Not Sold in Last <?php echo $days_not_sold; ?> Days)
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Batch Number</th>
                                <th>Center</th>
                                <th>Stock On Hand</th>
                                <th>Expiry Date</th>
                                <th>Days to Expire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($at_risk_stock)): ?>
                                <?php foreach ($at_risk_stock as $item): ?>
                                    <?php
                                        $row_class = '';
                                        if ($item->days_to_expiry <= 0) $row_class = 'danger'; // Expired
                                        elseif ($item->days_to_expiry <= 30) $row_class = 'warning'; // Expiring soon
                                    ?>
                                    <tr class="<?php echo $row_class; ?>">
                                        <td><?php echo htmlspecialchars($item->medicine_name); ?></td>
                                        <td><?php echo htmlspecialchars($item->batch_number); ?></td>
                                        <td><?php echo htmlspecialchars($item->center_name); ?></td>
                                        <td><?php echo $item->stock_on_hand; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($item->expiry_date)); ?></td>
                                        <td><?php echo $item->days_to_expiry; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <i class="fa fa-check-circle"></i> No slow-moving or at-risk stock found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="well well-sm">
                    <strong>Note:</strong> This table shows active stock that has not been sold in the last <?php echo $days_not_sold; ?> days, ordered by the soonest to expire.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript for Wastage Chart
document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('wastageChart').getContext('2d');
    var wastageData = <?php echo json_encode($wastage_by_month); ?>;
    
    var labels = wastageData.map(function(item) { return item.month; });
    var data = wastageData.map(function(item) { return item.wasted_value; });

    var wastageChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Wasted Stock Value (₹)',
                data: data,
                backgroundColor: 'rgba(217, 83, 79, 0.6)', // Bootstrap danger color
                borderColor: 'rgba(217, 83, 79, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return '₹' + value;
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₹' + context.parsed.y;
                        }
                    }
                }
            }
        }
    });
});
</script>
