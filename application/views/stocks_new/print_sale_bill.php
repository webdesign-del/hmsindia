<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Invoice - <?php echo htmlspecialchars($sale->sale_number); ?></title>
    <!-- Using Bootstrap 3.x CDN for quick styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .invoice-container {
            max-width: 90%;
            /* padding: 20px; */
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            padding: 20px;
            border-bottom: 2px solid #337ab7; /* Bootstrap primary color */
            text-align: center;
        }
        .invoice-header h2 {
            margin: 0;
            font-weight: bold;
            color: #337ab7;
        }
        .invoice-header p {
            margin: 2px 0;
            font-size: 12px;
        }
        .invoice-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }
        .invoice-details {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .invoice-body {
            padding: 20px;
        }
        .invoice-table {
            margin-bottom: 20px;
        }
        .invoice-table th {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }
        .invoice-table .text-right {
            text-align: right;
        }
        .invoice-totals {
            margin-top: 20px;
        }
        .invoice-totals .totals-table {
            width: 100%;
            max-width: 350px;
            float: right;
        }
        .invoice-totals .totals-table td {
            padding: 5px;
        }
        .invoice-totals .totals-table .total-label {
            font-weight: bold;
            text-align: right;
            width: 60%;
        }
        .invoice-totals .totals-table .total-value {
            text-align: right;
            width: 40%;
        }
        .invoice-totals .grand-total {
            font-size: 1.2em;
            font-weight: bold;
            background-color: #f5f5f5;
            border-top: 2px solid #337ab7;
        }
        .invoice-footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            margin-top: 30px;
        }
        .print-actions {
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
        }

        /* Print-specific styles */
        @media print {
            body {
                background-color: #fff;
            }
            .invoice-container {
                margin: 0;
                border: none;
                box-shadow: none;
                max-width: 100%;
            }
            .print-actions {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-actions">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fa fa-print"></i> Print Invoice
        </button>
        <a href="<?php echo base_url('stocks_new/edit_sale/' . $sale->id); ?>" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Back to Sale
        </a>
    </div>

    <div class="invoice-container" id="invoice-to-print">
        <div class="invoice-header">
            <?php if (isset($center_details) && $center_details): ?>
                <h2><?php echo htmlspecialchars($center_details->center_name); ?></h2>
                <p><?php echo htmlspecialchars($center_details->center_address); ?></p>
                <p>
                    <?php if(!empty($center_details->center_gst)): ?>
                        <strong>GSTN:</strong> <?php echo htmlspecialchars($center_details->center_gst); ?> |
                    <?php endif; ?>
                    <?php if(!empty($center_details->dl_number)): ?>
                        <strong>DL No:</strong> <?php echo htmlspecialchars($center_details->dl_number); ?>
                    <?php endif; ?>
                </p>
            <?php else: ?>
                <h2>Invoice</h2>
            <?php endif; ?>
        </div>

        <h3 class="invoice-title">Sale Invoice / Bill</h3>

        <div class="invoice-details">
            <div class="row">
                <div class="col-xs-6">
                    <h4>Bill To:</h4>
                    <address>
                        <strong><?php echo htmlspecialchars($sale->patient_name); ?></strong><br>
                        Patient ID: <?php echo htmlspecialchars($sale->patient_id); ?><br>
                        <?php // Add more patient details if available ?>
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <h4>Bill Details:</h4>
                    <p><strong>Bill No:</strong> <?php echo htmlspecialchars($sale->sale_number); ?></p>
                    <p><strong>Date:</strong> <?php echo date('d-M-Y', strtotime($sale->sale_date)); ?></p>
                    <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($sale->sale_time)); ?></p>
                    <p><strong>Doctor:</strong> <?php echo htmlspecialchars($sale->doctor_name ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>

        <div class="invoice-body">
            <div class="table-responsive">
                <table class="table table-bordered invoice-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Description</th>
                            <th>HSN</th>
                            <th>Batch</th>
                            <th>Expiry</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">MRP(per unit)</th>
                            <th class="text-right">MRP Value</th>
                            <th class="text-right">Discount %</th>
                            <th class="text-right">Discount Value</th>
                            <th class="text-right">Taxable Value</th>
                            <th class="text-right">GST %</th>
                            <th class="text-right">GST Amount</th>
                            <th class="text-right">IGST</th>
                            <th class="text-right">CGST</th>
                            <th class="text-right">SGST</th>
                            <th class="text-right">Total Amount</th>
                            <!-- <th class="text-right">Price</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-right">Discount</th>
                            <th class="text-right">Tax</th>
                            <th class="text-right">Total</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sale_items)): $i = 1; ?>
                            <?php foreach ($sale_items as $item): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($item->medicine_name ?? 'N/A'); ?></strong>
                                        <small>(<?php echo htmlspecialchars($item->brand_name ?? 'N/A'); ?>)</small>
                                    </td>
                                    <?php
                                    $mrp_value= number_format($item->quantity_sold * ($item->unit_price + $item->tax_amount/$item->quantity_sold),1);
                                    $mrp = $item->quantity_sold *
                                        ($item->unit_price + ($item->tax_amount / $item->quantity_sold));
                                    $discountPercentage = $item->discount_percentage;
                                    $discountAmount = ($mrp * $discountPercentage) / 100;
                                    $mrpAfterDiscount = $mrp - number_format($discountAmount,1);
                                    $total_value= number_format(round($mrp_value)- $discountAmount, 1);
                                    $gstRate = $item->gst_rate / 100;
                                    $taxableValue = ($total_value) / (1 + $gstRate);
                                    $gstAmount = round($mrpAfterDiscount) - number_format($taxableValue,1);
                                     ?>
                                     
                                    <td><?php echo htmlspecialchars($item->hsn_code ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($item->batch_number ?? 'N/A'); ?></td>
                                    <td><?php echo date('m/y', strtotime($item->expiry_date)); ?></td>
                                    <td class="text-right"><?php echo number_format($item->quantity_sold, 1); ?></td>
                                    <td class="text-right">₹<?php echo number_format($item->unit_price + $item->tax_amount/$item->quantity_sold, 1); ?></td>
                                    <td class="text-right">₹<?php echo number_format($mrp_value); ?></td>
                                    <td class="text-right"><?php echo number_format($item->discount_percentage); ?>%</td>
                                    <td class="text-right">₹<?php echo number_format($discountAmount,1); ?></td>
                                    <td class="text-right">₹<?php echo number_format($taxableValue,1); ?></td>
                                    <td class="text-right"><?php echo number_format($item->gst_rate,1); ?>%</td>
                                    <td class="text-right">₹<?php echo number_format($gstAmount,1); ?></td>
                                    <td class="text-right">₹ 0.00</td>
                                    <td class="text-right">₹ <?php echo number_format($gstAmount/2,1); ?></td>
                                    <td class="text-right">₹ <?php echo number_format($gstAmount/2,1); ?></td>
                                    <td class="text-right">₹ <?php echo number_format(round($mrp_value)- $discountAmount,1); ?></td>
                                </tr>
                            <?php endforeach; ?>
                          <tr style="border-top: 2px solid #337ab7; background-color: #f9f9f9;">
                            <td><strong>Subtotal</strong></td>
                            <td></td><td></td><td></td><td></td>

                            <?php
                            $total_quantity = 0;
                            $total_mrp = 0;
                            $total_discount = 0;
                            $total_taxable = 0;
                            $total_gst = 0;
                            $grand_total = 0;

                            if (!empty($sale_items)) {
                                foreach ($sale_items as $item) {
                                    $qty = $item->quantity_sold;
                                    $gstRate = $item->gst_rate / 100;
                                    $mrp = $qty * (number_format($item->unit_price,1) + number_format($item->tax_amount / $qty,1));
                                    $discount = ($mrp * $item->discount_percentage) / 100;
                                    $mrpAfterDiscount = $mrp - $discount;
                                    $taxable = $mrpAfterDiscount / (1 + $gstRate);
                                    $gstAmount = $mrpAfterDiscount - $taxable;
                                    $total_quantity += $qty;
                                    $total_mrp += $mrp;
                                    $total_discount += $discount;
                                    $total_taxable += $taxable;
                                    $total_gst += $gstAmount;
                                    $grand_total += $mrpAfterDiscount;
                                }
                            }
                            ?>

                            <!-- Quantity -->
                            <td class="text-right"><strong><?php echo number_format($total_quantity,1); ?></strong></td>

                            <td></td>

                            <!-- Total MRP -->
                            <td class="text-right"><strong>₹<?php echo number_format($total_mrp,1); ?></strong></td>

                            <td></td>

                            <!-- Total Discount -->
                            <td class="text-right"><strong>₹<?php echo number_format($total_discount,1); ?></strong></td>

                            <!-- Taxable Value -->
                            <td class="text-right"><strong>₹<?php echo number_format($total_taxable,1); ?></strong></td>

                            <td></td>

                            <!-- Total GST -->
                            <td class="text-right"><strong>₹<?php echo number_format($total_gst,1); ?></strong></td>

                            <!-- IGST -->
                            <td class="text-right"><strong>₹0.00</strong></td>

                            <!-- CGST -->
                            <td class="text-right"><strong>₹<?php echo number_format($total_gst / 2,1); ?></strong></td>

                            <!-- SGST -->
                            <td class="text-right"><strong>₹<?php echo number_format($total_gst / 2,1); ?></strong></td>

                            <!-- Grand Total -->
                            <td class="text-right"><strong>₹<?php echo number_format($grand_total,1); ?></strong></td>
                        </tr>

                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">No items found for this sale.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Totals Section -->
        <div class="row invoice-totals">
    <div class="col-xs-12">
        <table class="table totals-table">
            <tbody>

                <?php
                $total_quantity = 0;
                $total_mrp = 0;
                $total_discount = 0;
                $total_taxable = 0;
                $total_gst = 0;
                $grand_total = 0;

                if (!empty($sale_items)) {
                    $mrp_value_total = 0;
                    foreach ($sale_items as $item) {
                        $mrp_value_total += number_format($item->quantity_sold * ($item->unit_price + $item->tax_amount / $item->quantity_sold), 1);
                        $qty = $item->quantity_sold;
                        $gstRate = $item->gst_rate / 100;
                        $mrp = $qty * (number_format($item->unit_price,1) + number_format($item->tax_amount / $qty,1));
                        $discount = ($mrp * $item->discount_percentage) / 100;
                        $mrpAfterDiscount = $mrp - $discount;
                        $taxable = $mrpAfterDiscount / (1 + $gstRate);
                        $gstAmount = $mrpAfterDiscount - $taxable;
                        // $total_quantity += $qty;
                        // $total_mrp += $mrp;
                        $total_discount += $discount;
                        // $total_taxable += $taxable;
                        // $total_gst += $gstAmount;
                        $grand_total += $mrpAfterDiscount;
                    }
                }
                ?>

                <!-- <tr>
                    <td class="total-label">Quantity</td>
                    <td class="total-value"><?php echo number_format($total_quantity,1); ?></td>
                </tr> -->

                <tr>
                    <td class="total-label">MRP Value</td>
                    <td class="total-value">₹<?php echo number_format($mrp_value_total,1); ?></td>
                </tr>

                <!-- <tr>
                    <td class="total-label">GST</td>
                    <td class="total-value">₹<?php echo number_format($total_gst,1); ?></td>
                </tr> -->

                <!-- <tr>
                    <td class="total-label">SGST</td>
                    <td class="total-value">₹<?php echo number_format($total_gst / 2,1); ?></td>
                </tr> -->

                <tr>
                    <td class="total-label">Discount</td>
                    <td class="total-value">- ₹<?php echo number_format($total_discount,1); ?></td>
                </tr>

                <tr class="grand-total">
                    <td class="total-label">Grand Total</td>
                    <td class="total-value">₹<?php echo number_format($grand_total,1); ?></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

            
            <!-- Payment Status and Remarks Section -->
            <div class="row" style="margin-top: 20px;">
                <div class="col-xs-12">
                    <div style="border-top: 1px solid #ddd; padding-top: 15px;">
                        <div class="row">
                            <div class="col-xs-6">
                                <p><strong>Payment Status:</strong> 
                                    <span style="padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; 
                                        <?php 
                                        echo $sale->payment_status == 'PAID' ? 'background: #d4edda; color: #155724;' : 
                                            ($sale->payment_status == 'PARTIAL' ? 'background: #fff3cd; color: #856404;' : 'background: #f8d7da; color: #721c24;'); 
                                        ?>">
                                        <?php echo strtoupper($sale->payment_status ?? 'PENDING'); ?>
                                    </span>
                                </p>
                                <p><strong>Sale Status:</strong> 
                                    <span style="padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; 
                                        <?php 
                                        echo $sale->status == 'CONFIRMED' ? 'background: #d4edda; color: #155724;' : 
                                            ($sale->status == 'CANCELLED' ? 'background: #f8d7da; color: #721c24;' : 'background: #fff3cd; color: #856404;'); 
                                        ?>">
                                        <?php echo strtoupper($sale->status ?? 'DRAFT'); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-xs-6">
                                <?php if (!empty($sale->remarks)): ?>
                                <p><strong>Payment Remarks:</strong></p>
                                <!-- <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; border-left: 4px solid #007bff; font-style: italic;"> -->
                                    <?php echo nl2br(htmlspecialchars($sale->remarks)); ?>
                                <!-- </div> -->
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <p>Thank you for your visit. Get well soon!</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
        </div>
    </div>

</body>
</html>
