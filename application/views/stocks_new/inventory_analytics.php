<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">
            <i class="fa fa-bar-chart"></i> Inventory Analytics
            <small>Value, Performance, & Distribution Reports</small>
        </h1>
    </div>
</div>

<div class="row">
    <!-- Center Stock Distribution -->
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                
                <i class="fa fa-pie-chart"></i> Center Stock Value Distribution (Cost Price)
            </div>
            <div class="panel-body">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top 10 Performing Medicines -->
    <div class="col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                <i class="fa fa-star"></i> Top <?php echo count($top_medicines); ?> Performing Medicines (by Revenue)
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Brand</th>
                                <th>Units Sold</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($top_medicines)): ?>
                                <?php foreach ($top_medicines as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item->medicine_name); ?></td>
                                        <td><?php echo htmlspecialchars($item->brand_name); ?></td>
                                        <td><?php echo $item->total_units_sold; ?></td>
                                        <td>₹<?php echo number_format($item->total_revenue, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No sales data found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Performance -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-truck"></i> Vendor Performance Analysis
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Vendor Name</th>
                                <th>Vendor Number</th>
                                <th>Batches Supplied</th>
                                <th>Total Purchase Value</th>
                                <th>Total Returns (Count)</th>
                                <th>Total Wasted Value (Expired/Damaged)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($vendor_performance)): ?>
                                <?php foreach ($vendor_performance as $vendor): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($vendor->vendor_name); ?></td>
                                        <td><?php echo htmlspecialchars($vendor->vendor_number); ?></td>
                                        <td><?php echo $vendor->total_batches_supplied; ?></td>
                                        <td>₹<?php echo number_format($vendor->total_purchase_value, 2); ?></td>
                                        <td><?php echo $vendor->total_returns; ?></td>
                                        <td class="<?php echo ($vendor->total_wasted_value > 0) ? 'danger' : ''; ?>">
                                            ₹<?php echo number_format($vendor->total_wasted_value, 2); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No vendor data found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript for Distribution Chart
document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('distributionChart').getContext('2d');
    var distData = <?php echo json_encode($stock_distribution); ?>;
    
    var labels = distData.map(function(item) { return item.center_name; });
    var data = distData.map(function(item) { return item.total_stock_value; });

    var distributionChart = new Chart(ctx, {
        type: 'pie', // Pie chart
        data: {
            labels: labels,
            datasets: [{
                label: 'Stock Value (₹)',
                data: data,
                backgroundColor: [ // Add more colors if you have more centers
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
             responsive: true,
             plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.parsed || 0;
                            return label + ': ₹' + value.toFixed(2);
                        }
                    }
                }
            }
        }
    });
});
</script>
