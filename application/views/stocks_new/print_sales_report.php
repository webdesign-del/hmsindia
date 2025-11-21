<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        
        .header h2 {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 16px;
            font-weight: normal;
        }
        
        .filters {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        
        .filters h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 14px;
        }
        
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        
        .filter-label {
            font-weight: bold;
            color: #555;
        }
        
        .summary {
            background: #e9ecef;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        
        .summary-item {
            display: inline-block;
            margin: 0 20px;
        }
        
        .summary-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        
        .summary-label {
            display: block;
            font-size: 12px;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-confirmed { background: #d4edda; color: #155724; }
        .status-draft { background: #fff3cd; color: #856404; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        
        .payment-paid { background: #d4edda; color: #155724; }
        .payment-pending { background: #f8d7da; color: #721c24; }
        .payment-partial { background: #fff3cd; color: #856404; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .print-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 8px 16px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="print-buttons no-print">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fa fa-print"></i> Print
        </button>
        <button class="btn btn-secondary" onclick="window.close()">
            <i class="fa fa-arrow-left"></i> Back
        </button>
    </div>

    <div class="header">
        <h1>HMS India - Sales Report</h1>
        <h2>Generated on <?php echo $generated_date; ?></h2>
    </div>

    <div class="filters">
        <h3>Report Filters:</h3>
        <div class="filter-item">
            <span class="filter-label">Center:</span> <?php echo htmlspecialchars($center_name); ?>
        </div>
        <?php if (!empty($filters['start_date'])): ?>
            <div class="filter-item">
                <span class="filter-label">From Date:</span> <?php echo date('d-m-Y', strtotime($filters['start_date'])); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($filters['end_date'])): ?>
            <div class="filter-item">
                <span class="filter-label">To Date:</span> <?php echo date('d-m-Y', strtotime($filters['end_date'])); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php
    $total_sales = count($sales);
    $total_amount = 0;
    $total_items = 0;
    $total_quantity = 0;
    $confirmed_sales = 0;
    foreach ($sales as $sale) {
        $total_amount += $sale->total_amount ?? 0;
        $total_items += $sale->total_items ?? 0;
        $total_quantity += $sale->total_quantity ?? 0;
        if ($sale->status == 'CONFIRMED') $confirmed_sales++;
    }
    ?>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-number"><?php echo $total_sales; ?></span>
            <span class="summary-label">Total Sales</span>
        </div>
        <div class="summary-item">
            <span class="summary-number"><?php echo $confirmed_sales; ?></span>
            <span class="summary-label">Confirmed Sales</span>
        </div>
        <div class="summary-item">
            <span class="summary-number"><?php echo number_format($total_items); ?></span>
            <span class="summary-label">Total Items</span>
        </div>
        <div class="summary-item">
            <span class="summary-number"><?php echo number_format($total_quantity); ?></span>
            <span class="summary-label">Total Quantity</span>
        </div>
        <div class="summary-item">
            <span class="summary-number">₹<?php echo number_format($total_amount, 2); ?></span>
            <span class="summary-label">Total Amount</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sale #</th>
                <th>Patient</th>
                <th>Center</th>
                <th>Date</th>
                <th>Items</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
            <tr>
                <td><?php echo htmlspecialchars($sale->sale_number ?? 'N/A'); ?></td>
                <td>
                    <?php echo htmlspecialchars($sale->patient_name ?? 'N/A'); ?>
                    <?php if (!empty($sale->patient_id)): ?>
                        <br><small>ID: <?php echo htmlspecialchars($sale->patient_id); ?></small>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($sale->center_name ?? 'N/A'); ?></td>
                <td><?php echo date('d-m-Y', strtotime($sale->sale_date)); ?></td>
                <td class="text-center"><?php echo $sale->total_items ?? 0; ?></td>
                <td class="text-center"><?php echo $sale->total_quantity ?? 0; ?></td>
                <td class="text-right">₹<?php echo number_format($sale->total_amount ?? 0, 2); ?></td>
                <td class="text-center">
                    <?php 
                    $payment_status = strtolower($sale->payment_status ?? 'pending');
                    $payment_class = 'payment-' . $payment_status;
                    ?>
                    <span class="status-badge <?php echo $payment_class; ?>">
                        <?php echo strtoupper($sale->payment_status ?? 'PENDING'); ?>
                    </span>
                </td>
                <td class="text-center">
                    <?php 
                    $status = strtolower($sale->status ?? 'draft');
                    $status_class = 'status-' . $status;
                    ?>
                    <span class="status-badge <?php echo $status_class; ?>">
                        <?php echo strtoupper($sale->status ?? 'DRAFT'); ?>
                    </span>
                </td>
                <td>
                    <?php if (!empty($sale->remarks)): ?>
                        <?php echo htmlspecialchars($sale->remarks); ?>
                    <?php else: ?>
                        <span style="color: #999;">-</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by HMS India System</p>
        <p>Total Records: <?php echo count($sales); ?> | Generated: <?php echo $generated_date; ?></p>
    </div>
</body>
</html>
