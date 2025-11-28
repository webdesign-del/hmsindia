<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - <?php echo htmlspecialchars($po['po_number']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .approval-section {
            margin-top: 30px;
            font-size: 13px;
            border-top: 1px solid #ccc;
            padding-top: 15px;
        }
        .approval-section ul {
            list-style-type: none;
            padding-left: 0;
            margin-top: 10px;
        }
        .approval-section li {
            padding: 8px;
            border: 1px solid #eee;
            margin-bottom: 5px;
            background: #f9f9f9;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .approver-status {
            font-weight: bold;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
        }
        .status-approved { background-color: #28a745; }
        .status-rejected { background-color: #dc3545; }
        .status-pending  { background-color: #ffc107; color: #333; }
        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding: 1.5cm;
            margin: 1cm auto;
            border: 1px #D3D3D3 solid;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }
        .header, .footer {
            text-align: center;
        }
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #000;
        }
        .header .po-title {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .company-details {
            text-align: left;
            font-size: 14px;
        }
        .po-details {
            margin-top: 20px;
            margin-bottom: 20px;
            width: 100%;
            font-size: 14px;
        }
        .po-details .vendor-details {
            width: 50%;
            vertical-align: top;
            padding: 10px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }
        .po-details .po-meta {
            width: 50%;
            vertical-align: top;
            padding: 10px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }
        .po-details th {
            text-align: left;
            padding-right: 10px;
            white-space: nowrap;
        }
        
        /* This section replaces the old items table */
        .details-section {
            width: 100%;
            border: 1px solid #000;
            margin-top: 20px;
            min-height: 200px; /* Give space for remarks */
        }
        .details-section-header {
            background-color: #eee;
            padding: 8px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }
        .details-section-body {
            padding: 10px;
            font-size: 14px;
            white-space: pre-wrap; /* This respects new lines in your remarks */
        }
        .items-section {
            width: 100%;
            margin-top: 20px;
            border: 1px solid #000;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #eee;
            font-weight: bold;
            text-align: center;
        }
        .items-table td {
            text-align: left;
        }
        .items-table .text-right {
            text-align: right;
        }
        .items-table .text-center {
            text-align: center;
        }

        .totals-section {
            width: 100%;
            margin-top: 20px;
            font-size: 14px;
        }
        .totals-table {
            width: 40%;
            margin-left: 60%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px;
            border: 1px solid #000;
        }
        .totals-table .label {
            font-weight: bold;
            background-color: #eee;
        }
        .totals-table .value {
            text-align: right;
        }
        .terms-section {
            margin-top: 30px;
            font-size: 12px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .footer {
            margin-top: 50px;
            border-top: 2px solid #000;
            padding-top: 10px;
            font-size: 12px;
        }
        .signature-block {
            margin-top: 80px;
            text-align: right;
            font-size: 14px;
        }
        .signature-line {
            width: 250px;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        /* Print-specific styles */
        @media print {
            body {
                background-color: #fff;
            }
            .page {
                margin: 0;
                border: none;
                width: auto;
                min-height: auto;
                box-shadow: none;
                padding: 0;
            }
            .print-button {
                display: none;
            }
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <button class="print-button" onclick="window.print()">Print This Page</button>
    <div class="page">
        <div class="header">
            <div class="company-details">
                <strong style="font-size: 18px;">Your Company Name</strong><br>
                123 Your Street Address<br>
                Your City, State, ZIP<br>
                Phone: (123) 456-7890<br>
                Email: info@yourcompany.com
            </div>
            <div class="po-title">PURCHASE ORDER</div>
        </div>

        <table class="po-details">
            <tr>
                <td class="vendor-details">
                    <strong>Vendor:</strong><br>
                    <?php echo htmlspecialchars($po['po_name_of_vendor']); ?><br>
                </td>
                <td class="po-meta">
                    <table>
                        <tr>
                            <th>PO Number:</th>
                            <td><?php echo htmlspecialchars($po['po_number']); ?></td>
                        </tr>
                        <tr>
                            <th>PO Date:</th>
                            <td><?php echo date('d M Y', strtotime($po['created_at'])); ?></td>
                        </tr>
                        <tr>
                            <th>Centre:</th>
                            <td><?php echo htmlspecialchars($po['po_centre']); ?></td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td><?php echo htmlspecialchars($po['po_department']); ?></td>
                        </tr>
                        <tr>
                            <th>Expenditure:</th>
                            <td><?php echo htmlspecialchars($po['po_nature_of_expenditure']); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <?php 
        // Initialize calculated totals
        $calculated_total_basic = 0;
        $calculated_total_gst = 0;
        $calculated_total_quantity = 0;
        
        if (!empty($po_items)): ?>
        <div class="items-section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">S.No.</th>
                        <th style="width: 35%;">Item Description</th>
                        <th style="width: 10%;" class="text-center">Quantity</th>
                        <th style="width: 12%;" class="text-right">Rate (Ex. GST)</th>
                        <th style="width: 8%;" class="text-center">GST %</th>
                        <th style="width: 15%;" class="text-right">Basic Total</th>
                        <th style="width: 15%;" class="text-right">GST Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_quantity = 0;
                    $total_basic = 0;
                    $total_gst = 0;
                    $serial = 1;
                    foreach ($po_items as $item): 
                        $quantity = floatval($item['quantity']);
                        $rate = floatval($item['rate']);
                        $gst_rate = isset($item['gst_rate']) ? floatval($item['gst_rate']) : 0;
                        $gst_amount = isset($item['gst_amount']) ? floatval($item['gst_amount']) : 0;
                        $total_amount = isset($item['total_amount']) ? floatval($item['total_amount']) : 0;
                        
                        // Calculate basic total: quantity * rate
                        $basic_total = $quantity * $rate;
                        
                        // If GST amount not stored, calculate it
                        if ($gst_amount == 0 && $gst_rate > 0) {
                            $gst_amount = $basic_total * ($gst_rate / 100);
                        }
                        
                        $total_quantity += $quantity;
                        $total_basic += $basic_total;
                        $total_gst += $gst_amount;
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $serial++; ?></td>
                        <td><?php echo htmlspecialchars($item['item_description']); ?></td>
                        <td class="text-center"><?php echo number_format($quantity, 2); ?></td>
                        <td class="text-right">₹<?php echo number_format($rate, 2); ?></td>
                        <td class="text-center"><?php echo number_format($gst_rate, 2); ?>%</td>
                        <td class="text-right">₹<?php echo number_format($basic_total, 2); ?></td>
                        <td class="text-right">₹<?php echo number_format($gst_amount, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold; background-color: #f0f0f0;">
                        <td colspan="2" class="text-right">TOTAL:</td>
                        <td class="text-center"><?php echo number_format($total_quantity, 2); ?></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">₹<?php echo number_format($total_basic, 2); ?></td>
                        <td class="text-right">₹<?php echo number_format($total_gst, 2); ?></td>
                    </tr>
                </tfoot>
                    </table>
        </div>
        <?php 
        // Store calculated totals for use in totals section
        $calculated_total_basic = $total_basic;
        $calculated_total_gst = $total_gst;
        $calculated_total_quantity = $total_quantity;
        endif; ?>

        <div class="details-section">
            <div class="details-section-header">
                Details / Remarks / Narration
            </div>
            <div class="details-section-body">
                <?php 
                    if (!empty($po['po_remarks_or_comment_or_narration'])) {
                        echo nl2br(htmlspecialchars($po['po_remarks_or_comment_or_narration']));
                    } else {
                        echo "No details provided.";
                    }
                ?>
            </div>
        </div>
        <table class="totals-section">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    </td>
                <td style="width: 40%; vertical-align: top;">
                    <table class="totals-table">
                        <?php
                        // Get stored values from database
                        $basic_amount = isset($po['po_basic_amount']) ? floatval($po['po_basic_amount']) : 0;
                        $gst_amount = isset($po['po_gst_amount']) ? floatval($po['po_gst_amount']) : 0;
                        $other_charges = isset($po['po_other_charges_and_taxes']) ? floatval($po['po_other_charges_and_taxes']) : 0;
                        $po_total = isset($po['po_po_total']) ? floatval($po['po_po_total']) : 0;
                        
                        // If stored values are 0 or empty, use calculated values from items table
                        if (($basic_amount == 0 || $gst_amount == 0 || $po_total == 0) && isset($calculated_total_basic) && isset($calculated_total_gst)) {
                            if ($basic_amount == 0) {
                                $basic_amount = $calculated_total_basic;
                            }
                            if ($gst_amount == 0) {
                                $gst_amount = $calculated_total_gst;
                            }
                            if ($po_total == 0) {
                                $po_total = $basic_amount + $gst_amount + $other_charges;
                            }
                        }
                        ?>
                        <tr>
                            <td class="label">Basic Amount (Ex. GST)</td>
                            <td class="value">₹<?php echo number_format($basic_amount, 2); ?></td>
                        </tr>
                        <tr>
                            <td class="label">GST Amount</td>
                            <td class="value">₹<?php echo number_format($gst_amount, 2); ?></td>
                        </tr>
                        <tr>
                            <td class="label">Other Charges & Taxes</td>
                            <td class="value">₹<?php echo number_format($other_charges, 2); ?></td>
                        </tr>
                        <tr>
                            <td class="label" style="font-size: 16px; font-weight: bold;">PO Total (Inc. All)</td>
                            <td class="value" style="font-size: 16px; font-weight: bold;">₹<?php echo number_format($po_total, 2); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="approval-section">
            <strong>Approval Status:</strong>
            
            <?php
            if (!empty($po['approver_tokens'])) {
                // Decode the JSON string into a PHP array
                $approvers = json_decode($po['approver_tokens'], true);
                
                if (is_array($approvers) && !empty($approvers)) {
                    echo '<ul>';
                    foreach ($approvers as $approver) {
                        
                        // Set status text and style
                        $status_text = 'PENDING';
                        $status_class = 'status-pending';
                        
                        if ($approver['status'] == 'approved') {
                            $status_text = 'APPROVED';
                            $status_class = 'status-approved';
                        } elseif ($approver['status'] == 'rejected') {
                            $status_text = 'REJECTED';
                            $status_class = 'status-rejected';
                        }

                        echo '<li>';
                        
                        // Approver email and date
                        echo '<div class="approver-info">';
                        echo '<strong>' . htmlspecialchars($approver['email']) . '</strong>';
                        
                        if ($approver['status'] == 'approved' && !empty($approver['approved_at'])) {
                            echo '<br><small style="color: #555;">Action on: ' . date('d M Y, H:i', strtotime($approver['approved_at'])) . '</small>';
                        } elseif ($approver['status'] == 'rejected' && !empty($approver['remarks'])) {
                            echo '<br><small style="color: #dc3545;">Reason: ' . htmlspecialchars($approver['remarks']) . '</small>';
                        }
                        echo '</div>';

                        // Status badge
                        echo '<div class="approver-status ' . $status_class . '">' . $status_text . '</div>';
                        
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    // Fallback if JSON is bad
                    echo '<p>Could not read approval data.</p>';
                }
            } else if (!empty($po['po_approved_by'])) {
                // Fallback for the old system (using the 'po_approved_by' field)
                echo '<p style="margin-top: 10px;"><strong>Approved by (Legacy):</strong> ' . htmlspecialchars($po['po_approved_by']) . '</p>';
            } else {
                echo '<p style="margin-top: 10px;">No approver information found.</p>';
            }
            ?>
        </div>
        <div class="terms-section">
            <strong>Terms & Conditions:</strong>
            <ol style="padding-left: 20px; margin-top: 5px;">
                <li>Payment: 30 days from the date of invoice.</li>
                <li>Please mention the PO number on all invoices and delivery challans.</li>
                <li>All disputes are subject to [Your City] jurisdiction.</li>
            </ol>
        </div>
       
        <div class="signature-block">
            <div class="signature-line"></div>
            <strong>For Your Company Name</strong><br>
            (Authorized Signatory)
        </div>

        <div class="footer">
            This is a computer-generated document and does not require a physical signature.
        </div>
    </div>
</body>
</html>