<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div class="print-section">
            <!-- Print Header -->
            <div class="row no-print" style="margin-bottom: 20px;">
                <div class="col-md-12">
                    <h1 class="page-header">
                        <i class="fa fa-eye"></i> View Medicine Return
                        <small>Return details and items</small>
                    </h1>
                </div>
            </div>
            
            <!-- Print Header for PDF -->
            <div class="row" style="margin-bottom: 20px; border-bottom: 3px solid #000; padding-bottom: 15px;">
                <div class="col-md-12 text-center">
                    <h2 style="margin: 0; font-size: 24pt; font-weight: bold;">MEDICINE RETURN REPORT</h2>
                    <p style="margin: 5px 0; font-size: 12pt;"><strong>Hospital Management System</strong></p>
                    <p style="margin: 5px 0; font-size: 10pt;">Generated on: <?php echo date('M d, Y H:i A'); ?></p>
                </div>
            </div>
            
            <!-- Return Information -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle"></i> Return Details
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Return Number:</strong> <?php echo htmlspecialchars($return->return_number); ?></p>
                                    <p><strong>Patient:</strong> <?php echo htmlspecialchars($return->patient_name); ?></p>
                                    <p><strong>Receipt Number:</strong> <?php echo htmlspecialchars($return->receipt_number); ?></p>
                                    <p><strong>Center:</strong> <?php echo htmlspecialchars($return->center_name ?? 'N/A'); ?></p>
                                    <p><strong>Department:</strong> <?php echo htmlspecialchars($return->department ?? 'N/A'); ?></p>
                                    <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($return->return_date)); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Return Reason:</strong> 
                                        <span class="badge badge-info"><?php echo htmlspecialchars($return->return_reason); ?></span>
                                    </p>
                                    <p><strong>Total Items:</strong> <?php echo $return->total_items ?? 0; ?></p>
                                    <p><strong>Total Quantity:</strong> <?php echo $return->total_quantity ?? 0; ?></p>
                                    <p><strong>Total Return Amount:</strong> ₹<?php echo number_format($return->total_return_amount ?? 0, 2); ?></p>
                                    <p><strong>Remarks:</strong> <?php echo htmlspecialchars($return->remarks ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Return Items List -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-list"></i> Return Items
                            <span class="badge pull-right no-print"><?php echo count($return_items); ?> items</span>
                        </div>
                        <div class="panel-body">
                            <?php if(!empty($return_items)): ?>
                                <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Brand</th>
                                        <th>Batch</th>
                                        <th>Expiry Date</th>
                                        <th>Quantity Returned</th>
                                        <th>MRP Price</th>
                                        <th>MRP Value</th>
                                        <th>Discount %</th>
                                        <th>Discount Amount</th>
                                        <th>Taxable Value</th>
                                        <th>Tax %</th>
                                        <th>Tax Amount</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($return_items as $item): ?>
                                        <?php
                                            $qty        = (float) $item->quantity_returned;
                                            $unit_price = (float) $item->return_price;     // FIXED
                                            $gst_rate   = (float) $item->gst_rate;
                                            $total      = (float) $item->total_amount;     // FIXED

                                            // Display-only MRP value
                                            $mrp_value = $qty * $unit_price;

                                            // Reverse GST calculation (total is GST-inclusive)
                                            $taxable_value = $total / (1 + ($gst_rate / 100));
                                            $tax_amount    = $total - $taxable_value;
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item->medicine_name) ?></td>
                                            <td><?= htmlspecialchars($item->brand_name ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($item->batch_number) ?></td>
                                            <td><?= date('M d, Y', strtotime($item->expiry_date)) ?></td>
                                            <td><?= number_format($qty) ?></td>

                                            <td>₹<?= number_format($unit_price, 2) ?></td>
                                            <td>₹<?= number_format($mrp_value, 2) ?></td>

                                            <td><?= number_format($item->discount_percentage, 2) ?>%</td>
                                            <td>₹<?= number_format($item->discount_amount, 2) ?></td>

                                            <td>₹<?= number_format($taxable_value, 2) ?></td>
                                            <td><?= number_format($gst_rate, 2) ?>%</td>
                                            <td>₹<?= number_format($tax_amount, 2) ?></td>

                                            <td><strong>₹<?= number_format($total, 2) ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="info">
                                        <th colspan="12" style="text-align:right;">Total Return Amount:</th>
                                        <th>₹<?= number_format($return->total_return_amount ?? 0, 2) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted">
                                    <i class="fa fa-info-circle fa-2x"></i><br>
                                    No return items found.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Print Footer -->
            <div class="row" style="margin-top: 30px; border-top: 2px solid #000; padding-top: 15px;">
                <div class="col-md-12 text-center">
                    <p style="font-size: 10pt; margin: 5px 0;"><strong>This is a computer-generated return report</strong></p>
                    <p style="font-size: 9pt; margin: 5px 0;">Return Number: <?php echo htmlspecialchars($return->return_number); ?> | Generated on <?php echo date('M d, Y H:i A'); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Return Actions -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-cog"></i> Actions
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo base_url('stocks_new/returns'); ?>" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Returns List
                        </a>
                        <a href="<?php echo base_url('stocks_new/medicine_returns'); ?>" class="btn btn-primary">
                            <i class="fa fa-undo"></i> New Return
                        </a>
                        <button onclick="window.print();" class="btn btn-success">
                            <i class="fa fa-print"></i> Print Return
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        
        .print-section, .print-section * {
            visibility: visible;
        }
        
        .print-section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        .no-print {
            display: none !important;
        }
        
        .panel {
            border: 1px solid #000;
            page-break-inside: avoid;
        }
        
        .table {
            border-collapse: collapse;
        }
        
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
        }
        
        .table th {
            background-color: #f0f0f0 !important;
            font-weight: bold;
        }
        
        .badge {
            border: 1px solid #000;
            padding: 3px 8px;
        }
        
        @page {
            margin: 15mm;
            size: A4;
        }
        
        h1, h2, h3 {
            page-break-after: avoid;
        }
        
        .table {
            page-break-inside: auto;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    }
    
    /* Screen Styles for Print Button */
    @media screen {
        .print-section {
            /* Normal display for screen */
        }
    }
</style>

<script>
    // Print functionality
    function printReturn() {
        window.print();
    }
    
    // Optional: Auto-print on page load (uncomment if needed)
    // window.onload = function() {
    //     setTimeout(function() { window.print(); }, 500);
    // };
</script>

