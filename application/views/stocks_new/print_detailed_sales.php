<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detailed Sales Report</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
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
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
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

        .sale-group {
            background-color: #f0f8ff;
            border-top: 2px solid #007bff;
        }

        .sale-group:first-child {
            border-top: none;
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
        <h1>HMS India - Detailed Sales Report</h1>
        <h2>Generated on <?php echo $generated_date; ?></h2>
    </div>

    <?php if (!empty(array_filter($filters))): ?>
    <div class="filters">
        <h3>Applied Filters:</h3>
        <?php if (!empty($filters['center_id'])): ?>
            <div class="filter-item">
                <span class="filter-label">Center ID:</span> <?php echo htmlspecialchars($filters['center_id']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($filters['patient_id'])): ?>
            <div class="filter-item">
                <span class="filter-label">Patient ID:</span> <?php echo htmlspecialchars($filters['patient_id']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($filters['patient_name'])): ?>
            <div class="filter-item">
                <span class="filter-label">Patient Name:</span> <?php echo htmlspecialchars($filters['patient_name']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($filters['status'])): ?>
            <div class="filter-item">
                <span class="filter-label">Status:</span> <?php echo htmlspecialchars($filters['status']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($filters['date_from'])): ?>
            <div class="filter-item">
                <span class="filter-label">From Date:</span> <?php echo date('d-m-Y', strtotime($filters['date_from'])); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($filters['date_to'])): ?>
            <div class="filter-item">
                <span class="filter-label">To Date:</span> <?php echo date('d-m-Y', strtotime($filters['date_to'])); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php
    $total_items = count($detailed_sales);
    $total_quantity = 0;
    $total_amount = 0;
    $current_sale = null;
    $sale_groups = [];

    foreach ($detailed_sales as $item) {
        $total_quantity += $item['quantity_sold'] ?? 0;
        $total_amount += $item['item_total'] ?? 0;

        $sale_number = $item['sale_number'];
        if (!isset($sale_groups[$sale_number])) {
            $sale_groups[$sale_number] = [];
        }
        $sale_groups[$sale_number][] = $item;
    }
    ?>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-number"><?php echo $total_items; ?></span>
            <span class="summary-label">Total Items</span>
        </div>
        <div class="summary-item">
            <span class="summary-number"><?php echo $total_quantity; ?></span>
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
                <th>Sale Date</th>
                <th>Patient</th>
                <th>Center</th>
                <th>Medicine</th>
                <th>Batch</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total</th>
                <th>Payment</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sale_groups as $sale_number => $items): ?>
                <?php foreach ($items as $index => $item): ?>
                <tr <?php if ($index === 0): ?>class="sale-group"<?php endif; ?>>
                    <?php if ($index === 0): ?>
                        <td rowspan="<?php echo count($items); ?>"><?php echo htmlspecialchars($item['sale_number'] ?? 'N/A'); ?></td>
                        <td rowspan="<?php echo count($items); ?>"><?php echo date('d-m-Y', strtotime($item['sale_date'])); ?></td>
                        <td rowspan="<?php echo count($items); ?>">
                            <?php echo htmlspecialchars($item['patient_name'] ?? 'N/A'); ?>
                            <?php if (!empty($item['patient_id'])): ?>
                                <br><small>ID: <?php echo htmlspecialchars($item['patient_id']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td rowspan="<?php echo count($items); ?>"><?php echo htmlspecialchars($item['center_name'] ?? 'N/A'); ?></td>
                    <?php endif; ?>
                    <td><?php echo htmlspecialchars($item['medicine_name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($item['batch_number'] ?? 'N/A'); ?></td>
                    <td class="text-center"><?php echo $item['quantity_sold'] ?? 0; ?></td>
                    <td class="text-right">₹<?php echo number_format($item['unit_price'] ?? 0, 2); ?></td>
                    <td class="text-right">₹<?php echo number_format($item['subtotal'] ?? 0, 2); ?></td>
                    <td class="text-right">₹<?php echo number_format($item['tax_amount'] ?? 0, 2); ?></td>
                    <td class="text-right">₹<?php echo number_format($item['item_total'] ?? 0, 2); ?></td>
                    <?php if ($index === 0): ?>
                        <td rowspan="<?php echo count($items); ?>" class="text-center">
                            <?php
                            $payment_status = strtolower($item['payment_status'] ?? 'pending');
                            $payment_class = 'payment-' . $payment_status;
                            ?>
                            <span class="status-badge <?php echo $payment_class; ?>">
                                <?php echo strtoupper($item['payment_status'] ?? 'PENDING'); ?>
                            </span>
                        </td>
                        <td rowspan="<?php echo count($items); ?>" class="text-center">
                            <?php
                            $status = strtolower($item['sale_status'] ?? 'draft');
                            $status_class = 'status-' . $status;
                            ?>
                            <span class="status-badge <?php echo $status_class; ?>">
                                <?php echo strtoupper($item['sale_status'] ?? 'DRAFT'); ?>
                            </span>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>This detailed sales report was generated automatically by HMS India System</p>
        <p>Total Records: <?php echo $total_items; ?> | Total Quantity: <?php echo $total_quantity; ?> | Total Amount: ₹<?php echo number_format($total_amount, 2); ?> | Generated: <?php echo $generated_date; ?></p>
    </div>
</body>
</html>
