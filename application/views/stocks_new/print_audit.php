<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Report - <?php echo isset($audit_report->audit_number) ? htmlspecialchars($audit_report->audit_number) : 'N/A'; ?></title>
    <style>
        /* Print-optimized styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #000;
            background: #fff;
        }
        
        .print-header {
            text-align: center;
            border-bottom: 3px solid #000;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }
        
        .print-header h1 {
            font-size: 24pt;
            font-weight: bold;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }
        
        .print-header p {
            font-size: 14pt;
            margin: 5px 0;
        }
        
        .audit-details {
            margin-bottom: 30px;
            border: 2px solid #000;
            padding: 15px;
        }
        
        .audit-details h2 {
            font-size: 16pt;
            margin: 0 0 15px 0;
            text-align: center;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .detail-label {
            font-weight: bold;
            width: 200px;
            display: inline-block;
        }
        
        .detail-value {
            flex: 1;
        }
        
        .status-label {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 3px 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .audit-items {
            margin-bottom: 30px;
        }
        
        .audit-items h2 {
            font-size: 16pt;
            margin: 0 0 15px 0;
            text-align: center;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th {
            background-color: #f0f0f0;
            border: 2px solid #000;
            padding: 10px 5px;
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
        }
        
        .items-table td {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            font-size: 10pt;
        }
        
        .items-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .no-items {
            text-align: center;
            padding: 30px;
            font-style: italic;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        
        .print-footer {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 10px;
            text-align: center;
            font-size: 10pt;
        }
        
        /* Page break controls */
        .page-break {
            page-break-before: always;
        }
        
        .no-break {
            page-break-inside: avoid;
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
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls">
        <button class="print-btn" onclick="window.print();">🖨️ Print Report</button>
        <a href="<?php echo base_url('stocks_new/view_audit/' . (isset($audit_report->id) ? $audit_report->id : '')); ?>" class="back-btn">← Back</a>
    </div>

    <!-- Print Header -->
    <div class="print-header">
        <h1>Stock Audit Report</h1>
        <p><strong>Hospital Management System</strong></p>
        <p>Generated on: <?php echo date('M d, Y H:i A'); ?></p>
    </div>

    <!-- Audit Details -->
    <div class="audit-details no-break">
        <h2>Audit Information</h2>
        <?php if (isset($audit_report) && $audit_report): ?>
            <div class="detail-row">
                <span class="detail-label">Audit Number:</span>
                <span class="detail-value"><?php echo htmlspecialchars($audit_report->audit_number); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Location Audited:</span>
                <span class="detail-value"><?php echo htmlspecialchars($audit_report->center_name); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Audit Date:</span>
                <span class="detail-value"><?php echo date('M d, Y', strtotime($audit_report->audit_date)); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Audit Type:</span>
                <span class="detail-value">
                    <span class="status-label"><?php echo htmlspecialchars($audit_report->audit_type); ?></span>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <span class="status-label"><?php echo htmlspecialchars($audit_report->status); ?></span>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Items Audited:</span>
                <span class="detail-value"><?php echo number_format($audit_report->total_items_audited ?? 0); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Discrepancies Found:</span>
                <span class="detail-value"><?php echo isset($audit_items) ? count($audit_items) : 0; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Processed By:</span>
                <span class="detail-value"><?php echo htmlspecialchars($audit_report->created_by ?? 'System'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Processed At:</span>
                <span class="detail-value"><?php echo isset($audit_report->created_at) ? date('M d, Y H:i A', strtotime($audit_report->created_at)) : 'N/A'; ?></span>
            </div>
        <?php else: ?>
            <p>Audit report details not available.</p>
        <?php endif; ?>
    </div>

    <!-- Audit Items -->
    <div class="audit-items">
        <h2>Audit Adjustment Log</h2>
        <?php if (!empty($audit_items)): ?>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Medicine</th>
                        <th style="width: 12%;">Batch</th>
                        <th style="width: 12%;">Movement Type</th>
                        <th style="width: 10%;">System Qty</th>
                        <th style="width: 10%;">Change</th>
                        <th style="width: 10%;">Physical Qty</th>
                        <th style="width: 10%;">Unit Cost</th>
                        <th style="width: 10%;">Value Change</th>
                        <th style="width: 11%;">Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($audit_items as $item): ?>
                        <tr>
                            <td style="text-align: left;"><?php echo htmlspecialchars($item->medicine_name ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($item->batch_number ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($item->movement_type ?? 'N/A'); ?></td>
                            <td><?php echo number_format($item->quantity_before ?? 0, 2); ?></td>
                            <td><?php echo number_format($item->quantity_change ?? 0, 2); ?></td>
                            <td><?php echo number_format($item->quantity_after ?? 0, 2); ?></td>
                            <td>₹<?php echo number_format($item->unit_cost ?? 0, 2); ?></td>
                            <td>₹<?php echo number_format(($item->quantity_change ?? 0) * ($item->unit_cost ?? 0), 2); ?></td>
                            <td style="text-align: left;"><?php echo htmlspecialchars($item->notes ?? 'Audit adjustment'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-items">
                <p><strong>No discrepancies were found during this audit.</strong></p>
                <p>All stock quantities matched the physical count.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Print Footer -->
    <div class="print-footer">
        <p><strong>This is a computer-generated report from the Hospital Management System</strong></p>
        <p>Report generated on <?php echo date('M d, Y \a\t H:i A'); ?> | System: HMS Stock Management</p>
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
