<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - <?php echo $purchase_order['po_number']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .date-time {
            text-align: left;
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .content-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .left-column {
            width: 48%;
        }
        
        .right-column {
            width: 48%;
        }
        
        .company-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .company-details {
            margin-bottom: 10px;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 15px;
        }
        
        .vendor-details {
            margin-bottom: 15px;
        }
        
        .address-section {
            margin-bottom: 15px;
        }
        
        .address-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .summary-table {
            width: 50%;
            margin-left: auto;
            border-collapse: collapse;
        }
        
        .summary-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
        }
        
        .summary-table .label {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        
        .summary-table .text-right {
            text-align: right;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Print Purchase Order</button>
    
    <div class="date-time">
        <?php echo date('n/j/y, g:i A'); ?>
    </div>
    
    <div class="header">
        <div class="title">Purchase Order</div>
    </div>
    
    <div class="content-container">
        <div class="left-column">
            <div class="company-name">Pashupati Lifecare Pvt. Ltd.</div>
            <div class="company-details">
                <strong>PO No:</strong> <?php echo $purchase_order['po_number']; ?><br>
                <strong>DL Number:</strong> UP16200002826, UP16210002824 & UP1620F000057<br>
                <strong>FSSAI License No:</strong> 22723923000301<br>
                <strong>GSTIN NO:</strong> 09AAHCP5838M1ZP<br>
                <strong>CIN:</strong> U74999DL2014PTC264851<br>
                <strong>Premise Address:</strong> India IVF clinic(A unit of Pashupati Lifecare Pvt. Ltd.) Third Floor, N-26, Captain Vijayant Thapar Marg, Beside Dr Lal PathLabs, Sector 18, Noida, Gautambuddha Nagar, Uttar Pradesh, 201301
            </div>
        </div>
        <div class="right-column">
            <div class="section-title">Purchase Order To</div>
            <div class="vendor-details">
                <strong>Vendor Name:</strong> <?php echo $vendor_data->name; ?><br>
                <strong>Vendor Address:</strong> <?php echo $vendor_data->company_name; ?><br>
                <strong>Vendor GST Number:</strong> <?php echo $vendor_data->gst_no; ?>
            </div>
            
            <div class="address-section">
                <div class="address-title">Bill To:</div>
                <div><?php echo $bill_to_address; ?></div>
            </div>
            
            <div class="address-section">
                <div class="address-title">Ship To:</div>
                <div><?php echo $ship_to_address; ?></div>
            </div>
        </div>
    </div>
    
    <table class="items-table">
        <thead>
            <tr>
                <th>Item name</th>
                <th>Company</th>
                <th>Quantity (Pack)</th>
                <th>MRP (Pack)</th>
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
                <td class="text-right"><?php echo number_format($item_total_without_gst, 3); ?></td>
                <td class="text-right"><?php echo number_format($gst_amount, 3); ?></td>
                <td class="text-right"><?php echo number_format($item_total_with_gst, 1); ?></td>
                <td class="text-center"><?php echo $tax_percentage; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <table class="summary-table">
        <tr>
            <td class="label">Vendor Price Without GST</td>
            <td class="text-right"><?php echo number_format($total_without_gst, 2); ?></td>
        </tr>
        <tr>
            <td class="label">Total GST Amount</td>
            <td class="text-right"><?php echo number_format($total_gst_amount, 2); ?></td>
        </tr>
        <tr>
            <td class="label">Total Vendor Amount</td>
            <td class="text-right"><?php echo number_format($total_with_gst, 1); ?></td>
        </tr>
    </table>
    
    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>
