<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disposal Reports - <?php echo date('Y-m-d'); ?></title>
    <style>
        /* Print-optimized styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
            color: #000;
            background: #fff;
        }
        
        .print-header {
            text-align: center;
            border-bottom: 3px solid #000;
            margin-bottom: 25px;
            padding-bottom: 15px;
        }
        
        .print-header h1 {
            font-size: 22pt;
            font-weight: bold;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }
        
        .print-header p {
            font-size: 12pt;
            margin: 5px 0;
        }
        
        .filters-section {
            background-color: #f5f5f5;
            border: 2px solid #000;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .filters-section h3 {
            margin: 0 0 10px 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        
        .filter-item {
            display: inline-block;
            margin-right: 25px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .summary-section {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .summary-section h3 {
            margin: 0 0 10px 0;
            font-size: 14pt;
            text-transform: uppercase;
            color: #856404;
        }
        
        .summary-item {
            display: inline-block;
            margin-right: 30px;
            font-weight: bold;
            font-size: 12pt;
        }
        
        .reports-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        
        .reports-table th {
            background-color: #f0f0f0;
            border: 2px solid #000;
            padding: 10px 6px;
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            vertical-align: middle;
        }
        
        .reports-table td {
            border: 1px solid #000;
            padding: 8px 6px;
            font-size: 9pt;
            vertical-align: middle;
        }
        
        .reports-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-left {
            text-align: left;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-draft {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status-approved {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b3d9ff;
        }
        
        .disposal-type {
            font-weight: bold;
            color: #dc3545;
        }
        
        .print-footer {
            margin-top: 30px;
            border-top: 2px solid #000;
            padding-top: 15px;
            text-align: center;
            font-size: 10pt;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            font-style: italic;
            font-size: 14pt;
            border: 2px solid #ccc;
            background-color: #f9f9f9;
        }
        
        /* Print button (will be hidden when printing) */
        .print-controls {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #fff;
            border: 2px solid #dc3545;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        
        .print-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 3px;
            margin-right: 10px;
        }
        
        .print-btn:hover {
            background: #c82333;
        }
        
        .back-btn {
            background: #666;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 3px;
            text-decoration: none;
            display: inline-block;
        }
        
        .back-btn:hover {
            background: #444;
        }
        
        /* Hide controls when printing */
        @media print {
            .print-controls {
                display: none !important;
            }
            
            body {
                padding: 0;
            }
            
            @page {
                margin: 15mm;
                size: A4 landscape;
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls">
        <button class="print-btn" onclick="window.print();">🖨️ Print Report</button>
        <a href="<?php echo base_url('stocks_new/disposal_reports'); ?>" class="back-btn">← Back to List</a>
    </div>

    <!-- Print Header -->
    <div class="print-header">
        <h1>Medicine Disposal Reports</h1>
        <p><strong>Hospital Management System - Stock Management</strong></p>
        <p>Generated on: <?php echo $generated_date; ?></p>
    </div>

    <!-- Applied Filters -->
    <div class="filters-section">
        <h3>Applied Filters:</h3>
        <?php 
        $hasFilters = false;
        if (!empty($filters['center_id'])): 
            $hasFilters = true;
        ?>
            <span class="filter-item">Center ID: <?php echo htmlspecialchars($filters['center_id']); ?></span>
        <?php endif; ?>
        <?php if (!empty($filters['status'])): 
            $hasFilters = true;
        ?>
            <span class="filter-item">Status: <?php echo htmlspecialchars($filters['status']); ?></span>
        <?php endif; ?>
        <?php if (!empty($filters['from_date'])): 
            $hasFilters = true;
        ?>
            <span class="filter-item">From Date: <?php echo date('d-m-Y', strtotime($filters['from_date'])); ?></span>
        <?php endif; ?>
        <?php if (!empty($filters['to_date'])): 
            $hasFilters = true;
        ?>
            <span class="filter-item">To Date: <?php echo date('d-m-Y', strtotime($filters['to_date'])); ?></span>
        <?php endif; ?>
        <?php if (!$hasFilters): ?>
            <span class="filter-item">No filters applied - Showing all disposal reports</span>
        <?php endif; ?>
    </div>

    <?php if (!empty($disposal_reports)): ?>
        <!-- Summary -->
        <div class="summary-section">
            <h3>Summary Statistics:</h3>
            <span class="summary-item">Total Disposals: <?php echo count($disposal_reports); ?></span>
            <span class="summary-item">Total Items Disposed: <?php echo array_sum(array_column($disposal_reports, 'total_items')); ?></span>
            <span class="summary-item">Total Cost: ₹<?php echo number_format(array_sum(array_column($disposal_reports, 'total_cost')), 2); ?></span>
            <span class="summary-item">Completed: <?php echo count(array_filter($disposal_reports, function($r) { return $r->status == 'COMPLETED'; })); ?></span>
        </div>

        <!-- Disposal Reports Table -->
        <table class="reports-table">
            <thead>
                <tr>
                    <th style="width: 12%;">Disposal Number</th>
                    <th style="width: 12%;">Center</th>
                    <th style="width: 10%;">Disposal Date</th>
                    <th style="width: 12%;">Disposal Type</th>
                    <th style="width: 12%;">Method</th>
                    <th style="width: 8%;">Items</th>
                    <th style="width: 10%;">Total Cost</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 14%;">Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disposal_reports as $report): ?>
                    <tr>
                        <td class="text-left"><strong><?php echo htmlspecialchars($report->disposal_number ?? 'N/A'); ?></strong></td>
                        <td class="text-left"><?php echo htmlspecialchars($report->center_name ?? 'N/A'); ?></td>
                        <td class="text-center"><?php echo date('d-m-Y', strtotime($report->disposal_date)); ?></td>
                        <td class="text-center">
                            <span class="disposal-type"><?php echo htmlspecialchars($report->disposal_type ?? 'N/A'); ?></span>
                        </td>
                        <td class="text-center"><?php echo htmlspecialchars($report->disposal_method ?? 'N/A'); ?></td>
                        <td class="text-center"><?php echo $report->total_items ?? 0; ?></td>
                        <td class="text-right"><strong>₹<?php echo number_format($report->total_cost ?? 0, 2); ?></strong></td>
                        <td class="text-center">
                            <span class="status-badge status-<?php echo strtolower($report->status ?? 'draft'); ?>">
                                <?php echo htmlspecialchars($report->status ?? 'N/A'); ?>
                            </span>
                        </td>
                        <td class="text-left"><?php echo htmlspecialchars($report->disposal_reason ?? 'N/A'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <h3>No Disposal Reports Found</h3>
            <p>No disposal reports match the applied filters or no disposals exist in the system.</p>
        </div>
    <?php endif; ?>

    <!-- Print Footer -->
    <div class="print-footer">
        <p><strong>This is a computer-generated report from the Hospital Management System</strong></p>
        <p>Report contains <?php echo count($disposal_reports); ?> disposal records | Generated on <?php echo $generated_date; ?></p>
        <p><strong>Medicine Disposal Management</strong> | Print this page using your browser's print function (Ctrl+P)</p>
    </div>

    <script>
        // Auto-focus for better user experience
        window.onload = function() {
            // Optional: Auto-print when page loads (uncomment if desired)
            // setTimeout(function() { window.print(); }, 1000);
        };
        
        // Handle print completion
        window.onafterprint = function() {
            // Optional: Auto-close or redirect after printing
            // window.close();
        };
    </script>
</body>
</html>
