<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - <?php echo $purchase_order['po_number']; ?></title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            margin: 0;
            padding: 24px;
            font-size: 12px;
            line-height: 1.5;
            background-color: #f2f4f7;
            color: #1f2a37;
        }

        .po-page {
            max-width: 960px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 32px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.15);
        }

        .po-header {
            display: flex;
            justify-content: space-between;
            gap: 32px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .brand-block .brand-name {
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .brand-block .brand-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }

        .meta-stack {
            min-width: 252px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            overflow: hidden;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 14px;
            font-size: 12px;
        }

        .meta-row + .meta-row {
            border-top: 1px solid #e5e7eb;
        }

        .meta-label {
            font-weight: 600;
            color: #4b5563;
        }

        .info-grid {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .info-card {
            flex: 1;
            min-width: 260px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px;
            background-color: #fafafa;
        }

        .section-title {
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #111827;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            padding: 4px 0;
        }

        .info-row span:first-child {
            font-weight: 600;
            color: #4b5563;
            margin-right: 12px;
        }

        .address-grid {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .address-card {
            flex: 1;
            min-width: 260px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px;
            background-color: #fff;
        }

        .address-card p {
            margin: 0;
            color: #374151;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 11px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #e5e7eb;
            padding: 10px;
        }

        .items-table th {
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.4px;
            background: linear-gradient(135deg, #f8fafc, #eef2ff);
            color: #1e293b;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary-grid {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .summary-card {
            flex: 1;
            min-width: 200px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 16px;
            background-color: #111827;
            color: #fff;
        }

        .summary-card .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: rgba(255, 255, 255, 0.7);
        }

        .summary-card .value {
            font-size: 18px;
            font-weight: 700;
            margin-top: 6px;
        }

        .note-block {
            margin-top: 30px;
            padding: 14px 18px;
            border-left: 4px solid #2563eb;
            background-color: #eef2ff;
            font-size: 12px;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-block {
            text-align: center;
        }

        .signature-line {
            width: 220px;
            border-bottom: 1px solid #9ca3af;
            margin-bottom: 6px;
        }

        @media print {
            body {
                padding: 0;
                background-color: #fff;
            }

            .po-page {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 999px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .print-button:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <?php 
        $generated_at = date('n/j/y, g:i A'); 
        $po_created_on = !empty($purchase_order['created_at']) 
            ? date('d M Y, g:i A', strtotime($purchase_order['created_at'])) 
            : 'Not available';
    ?>
    <button class="print-button no-print" onclick="window.print()">Print Purchase Order</button>

    <div class="po-page">
        <div class="po-header">
            <div class="brand-block">
                <div class="brand-name">Pashupati Lifecare Pvt. Ltd.</div>
                <div class="brand-subtitle">Purchase Order</div>
            </div>
            <div class="meta-stack">
                <div class="meta-row">
                    <span class="meta-label"> PO Number     </span>
                    <span>   <?php echo $purchase_order['po_number']; ?></span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Created On</span>
                    <span><?php echo $po_created_on; ?></span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Generated</span>
                    <span><?php echo $generated_at; ?></span>
                </div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="section-title">Compliance & Registration</div>
                <div class="info-row">
                    <span>DL Number</span>
                    <span>UP16200002826 / UP16210002824 / UP1620F000057</span>
                </div>
                <div class="info-row">
                    <span>FSSAI License</span>
                    <span>22723923000301</span>
                </div>
                <div class="info-row">
                    <span>GSTIN</span>
                    <span>09AAHCP5838M1ZP</span>
                </div>
                <div class="info-row">
                    <span>CIN</span>
                    <span>U74999DL2014PTC264851</span>
                </div>
                <div class="info-row">
                    <span>Premise</span>
                    <span>India IVF Clinic, N-26, Sector 18, Noida</span>
                </div>
            </div>

            <div class="info-card">
                <div class="section-title">Supplier Details</div>
                <div class="info-row">
                    <span>Vendor Name</span>
                    <span><?php echo $vendor_data->name; ?></span>
                </div>
                <div class="info-row">
                    <span>Company</span>
                    <span><?php echo $vendor_data->company_name; ?></span>
                </div>
                <div class="info-row">
                    <span>GST Number</span>
                    <span><?php echo $vendor_data->gst_no; ?></span>
                </div>
            </div>
        </div>

        <div class="address-grid">
            <div class="address-card">
                <div class="section-title">Bill To</div>
                <p><?php echo nl2br($bill_to_address); ?></p>
            </div>
            <div class="address-card">
                <div class="section-title">Ship To</div>
                <p><?php echo nl2br($ship_to_address); ?></p>
            </div>
        </div>

        <table class="items-table">
        <thead>
            <tr>
                <th>Item name</th>
                <th>Company</th>
                <th>Quantity (Pack)</th>
                <th>MRP (Pack)</th>
                <th>Pack Size</th>
                <th>Vendor Price Without GST</th>
                <th>GST Amount</th>
                <th>Vendor Price With GST</th>
                <th>GST Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_without_gst = 0;
            $total_gst_amount = 0;
            $total_with_gst = 0;
            
            foreach ($purchase_order_items as $item): 
                $quantity = floatval($item['quantity']);
                $vendor_price = floatval($item['vendor_price']);
                $tax_percentage = floatval($item['tax_percentage']);
                $mrp = floatval($item['mrp']);
                $pack_size = floatval($item['pack_size']);
                
                $item_total_without_gst = $quantity * $vendor_price;
                $gst_amount = $item_total_without_gst * ($tax_percentage / 100);
                $item_total_with_gst = $item_total_without_gst + $gst_amount;
                
                $total_without_gst += $item_total_without_gst;
                $total_gst_amount += $gst_amount;
                $total_with_gst += $item_total_with_gst;
            ?>
            <tr>
                <td><?php echo $item['item_name']; ?></td>
                <td><?php echo $item['company']; ?></td>
                <td class="text-center"><?php echo $quantity; ?></td>
                <td class="text-right"><?php echo number_format($mrp, 2); ?></td>
                 <td class="text-right"><?php echo number_format($pack_size, 2); ?></td>
                <td class="text-right"><?php echo number_format($item_total_without_gst, 3); ?></td>
                <td class="text-right"><?php echo number_format($gst_amount, 3); ?></td>
                <td class="text-right"><?php echo number_format($item_total_with_gst, 1); ?></td>
                <td class="text-center"><?php echo $tax_percentage; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="label">Vendor Price Without GST</div>
                <div class="value"><?php echo number_format($total_without_gst, 2); ?></div>
            </div>
            <div class="summary-card">
                <div class="label">Total GST Amount</div>
                <div class="value"><?php echo number_format($total_gst_amount, 2); ?></div>
            </div>
            <div class="summary-card">
                <div class="label">Total Vendor Amount</div>
                <div class="value"><?php echo number_format($total_with_gst, 1); ?></div>
            </div>
        </div>

        <div class="note-block">
            Ensure rates and quantities are cross-verified with supplier acknowledgement prior to dispatch. Contact procurement for any discrepancies.
        </div>

        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line"></div>
                <div>Authorized Signatory</div>
            </div>
        </div>
    </div>

    <script>
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>
