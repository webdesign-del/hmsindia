<style>
.package-stock-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
.package-stock-table th,
.package-stock-table td {
    padding: 8px 12px;
    text-align: left;
    border: 1px solid #ddd;
}
.package-stock-table th {
    background-color: #f5f5f5;
    font-weight: bold;
}
.package-stock-table tr:nth-child(even) {
    background-color: #f9f9f9;
}
.package-stock-table tr:hover {
    background-color: #e8f4f8;
}
.center-section {
    margin-bottom: 30px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    padding: 15px;
}
.center-header {
    background-color: #f8f9fa;
    padding: 10px 15px;
    margin: -15px -15px 15px -15px;
    border-bottom: 1px solid #e0e0e0;
    border-radius: 5px 5px 0 0;
}
.summary-cards {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.summary-card {
    flex: 1;
    min-width: 200px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.summary-card h3 {
    margin: 0 0 10px 0;
    font-size: 2em;
    font-weight: bold;
}
.summary-card p {
    margin: 0;
    font-size: 0.9em;
    opacity: 0.9;
}
</style>

<h4>📊 Package System Dashboard</h4>

<!-- Transfer Summary Alert -->
<?php if(!empty($transfer_history)): ?>
<div style="background-color: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
    <h5 style="color: #155724; margin-top: 0;">🎯 कौन सा Package कहाँ Transfer हुआ - Summary:</h5>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 10px; margin-top: 10px;">
        <?php
        $package_transfers = [];
        foreach($transfer_history as $transfer) {
            if ($transfer->movement_type == 'TRANSFER_IN') {
                $key = $transfer->package_name . ' → ' . $transfer->to_center;
                if (!isset($package_transfers[$key])) {
                    $package_transfers[$key] = 0;
                }
                $package_transfers[$key] += abs($transfer->quantity_change);
            }
        }
        $count = 0;
        foreach($package_transfers as $transfer_text => $quantity):
            if ($count >= 6) break; // Show only first 6
        ?>
            <div style="background: white; padding: 8px; border-radius: 3px; border: 1px solid #dee2e6; font-size: 0.9em;">
                <strong><?php echo htmlspecialchars($transfer_text); ?>:</strong> <?php echo $quantity; ?> packages
            </div>
        <?php $count++; endforeach; ?>
    </div>
    <p style="margin: 10px 0 0 0; font-size: 0.9em; color: #155724;">
        <em>नीचे Complete Transfer History देखें...</em>
    </p>
</div>
<?php endif; ?>

<!-- Summary Cards -->
<div class="summary-cards">
    <div class="summary-card">
        <h3><?php echo $total_stocks; ?></h3>
        <p>Total Package Stocks</p>
    </div>
    <div class="summary-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <h3><?php echo $total_centers; ?></h3>
        <p>Centers with Packages</p>
    </div>
    <div class="summary-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <h3><?php echo $total_units; ?></h3>
        <p>Total Package Units</p>
    </div>
    <div class="summary-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <h3><?php echo $unique_packages; ?></h3>
        <p>Unique Packages</p>
    </div>
</div>

<h4>📦 Package Distribution Across Centers</h4>

<?php if(empty($package_stocks)): ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> No package stocks found. Packages need to be assembled first.
    </div>
<?php else: ?>
    <?php
    // Group by center
    $stocks_by_center = [];
    foreach($package_stocks as $stock) {
        $center_key = $stock->center_name;
        if (!isset($stocks_by_center[$center_key])) {
            $stocks_by_center[$center_key] = [];
        }
        $stocks_by_center[$center_key][] = $stock;
    }

    foreach($stocks_by_center as $center_name => $stocks):
    ?>
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-cubes"></i> Package Stock Assembly
            </div>
            <div class="center-section">
                <div class="center-header">
                    <h5 style="margin: 0;">
                        <i class="fa fa-building"></i> <?php echo htmlspecialchars($center_name); ?>
                        <span class="badge badge-primary"><?php echo count($stocks); ?> packages</span>
                    </h5>
                </div>
                <table class="package-stock-table">
                    <thead>
                        <tr>
                            <th>Package Code</th>
                            <th>Package Name</th>
                            <!-- <th>Department</th> -->
                            <th>Quantity</th>
                            <th>Last Movement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($stocks as $stock): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($stock->package_code); ?></td>
                                <td><?php echo htmlspecialchars($stock->package_name); ?></td>
                                <!-- <td>
                                    <?php if($stock->department): ?>
                                        <span class="label label-info"><?php echo htmlspecialchars($stock->department); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td> -->
                                <td>
                                    <span class="badge badge-success"><?php echo $stock->quantity; ?> units</span>
                                </td>
                                <td>
                                    <?php echo $stock->last_movement_date ? date('M d, Y H:i', strtotime($stock->last_movement_date)) : '-'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>



<?php if(empty($package_stocks) && empty($transfer_history)): ?>
    <div class="alert alert-warning">
        <i class="fa fa-exclamation-triangle"></i>
        <strong>No Data Available:</strong> No package stocks or transfer history found.
        <br><br>
        <strong>To get started:</strong>
        <ol>
            <li>Create a package using "Create Package"</li>
            <li>Add stock to the package using "Add Package Stock"</li>
            <li>Transfer packages to centers using "Transfer Package Stock"</li>
        </ol>
    </div>
<?php endif; ?>
