<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Returns Report - <?php echo date('Y-m-d'); ?></title>
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
        
        .summary-section {
            background-color: #e8f4fd;
            border: 2px solid #007cba;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .summary-section h3 {
            margin: 0 0 10px 0;
            font-size: 14pt;
            text-transform: uppercase;
            color: #007cba;
        }
        
        .summary-item {
            display: inline-block;
            margin-right: 30px;
            font-weight: bold;
            font-size: 12pt;
        }
        
        .returns-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        
        .returns-table th {
            background-color: #f0f0f0;
            border: 2px solid #000;
            padding: 10px 6px;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
            vertical-align: middle;
        }
        
        .returns-table td {
            border: 1px solid #000;
            padding: 8px 6px;
            font-size: 8pt;
            vertical-align: middle;
        }
        
        .returns-table tbody tr:nth-child(even) {
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
            border: 2px solid #007cba;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        
        .print-btn {
            background: #007cba;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 3px;
            margin-right: 10px;
        }
        
        .print-btn:hover {
            background: #005a87;
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
        <a href="<?php echo base_url('stocks_new/returns'); ?>" class="back-btn">← Back to List</a>
    </div>

    <!-- Print Header -->
    <div class="print-header">
        <h1>Medicine Returns Report</h1>
        <p><strong>Hospital Management System - Stock Management</strong></p>
        <p>Generated on: <?php echo $generated_date; ?></p>
    </div>

    <?php if (!empty($returns)): ?>
        <!-- Summary -->
        <div class="summary-section">
            <h3>Summary Statistics:</h3>
            <span class="summary-item">Total Returns: <?php echo count($returns); ?></span>
            <span class="summary-item">Total Items: <?php 
                $total_items = 0;
                foreach ($returns as $return) {
                    $total_items += ($return->total_items ?? 0);
                }
                echo $total_items;
            ?></span>
            <span class="summary-item">Total Amount: ₹<?php 
                $total_amount = 0;
                foreach ($returns as $return) {
                    $total_amount += ($return->total_return_amount ?? 0);
                }
                echo number_format($total_amount, 2);
            ?></span>
        </div>

        <!-- Returns Table -->
        <table class="returns-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Return #</th>
                    <th style="width: 10%;">Patient</th>
                    <th style="width: 8%;">Receipt #</th>
                    <th style="width: 10%;">Center</th>
                    <th style="width: 8%;">Department</th>
                    <th style="width: 18%;">Medicine Names</th>
                    <th style="width: 8%;">Return Date</th>
                    <th style="width: 10%;">Reason</th>
                    <th style="width: 5%;">Items</th>
                    <th style="width: 8%;">Amount (₹)</th>
                    <th style="width: 7%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($returns as $return): ?>
                    <tr>
                        <td class="text-left"><strong><?php echo htmlspecialchars($return->return_number ?? 'N/A'); ?></strong></td>
                        <td class="text-left"><?php echo htmlspecialchars($return->patient_name ?? 'N/A'); ?></td>
                        <td class="text-left"><?php echo htmlspecialchars($return->receipt_number ?? 'N/A'); ?></td>
                        <td class="text-left"><?php echo htmlspecialchars($return->center_name ?? 'N/A'); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($return->department ?? 'N/A'); ?></td>
                        <td class="text-left" style="font-size: 7pt;"><?php echo htmlspecialchars($return->medicine_names ?? 'N/A'); ?></td>
                        <td class="text-center"><?php echo isset($return->return_date) ? date('d-m-Y', strtotime($return->return_date)) : 'N/A'; ?></td>
                        <td class="text-left"><?php echo htmlspecialchars($return->return_reason ?? 'N/A'); ?></td>
                        <td class="text-center"><?php echo $return->total_items ?? 0; ?></td>
                        <td class="text-right"><strong>₹<?php echo number_format($return->total_return_amount ?? 0, 2); ?></strong></td>
                        <td class="text-center">
                            <span class="status-badge status-completed">
                                <?php echo htmlspecialchars($return->status ?? 'COMPLETED'); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <h3>No Returns Found</h3>
            <p>No medicine returns exist in the system.</p>
        </div>
    <?php endif; ?>

    <!-- Print Footer -->
    <div class="print-footer">
        <p><strong>This is a computer-generated report from the Hospital Management System</strong></p>
        <p>Report contains <?php echo count($returns); ?> return records | Generated on <?php echo $generated_date; ?></p>
        <p><strong>Stock Management Module</strong> | Print this page using your browser's print function (Ctrl+P)</p>
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

