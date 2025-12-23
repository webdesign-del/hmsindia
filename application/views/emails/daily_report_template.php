<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Report</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { width: 90%; margin: 20px auto; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #f4f4f4; text-align: left; }
        .numeric { text-align: right; }
        h3 { color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h3>Orderbook Summary</h3>
        <p>Here is the daily sales report for <?php echo date('Y-m-d'); ?>.</p>
        
        <table>
            <thead>
                <tr>
                    <th>Type of procedures</th>
                    <th>Customer Count</th>
                    <th>Bill Count</th>
                    <th>Amount (Rs)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>IVF Cycles Sold</td>
                    <td><?php echo $report_data['ivf_cycles_sold_c_count']; ?></td>
                    <td><?php echo $report_data['ivf_cycles_sold_b_count']; ?></td>
                    <td class="numeric"><?php echo $report_data['ivf_cycles_sold_amount']; ?></td>
                </tr>
                <tr>
                    <td>IVF with Bed</td>
                    <td><?php echo $report_data['ivf_with_bed_c_count']; ?></td>
                    <td><?php echo $report_data['ivf_with_bed_b_count']; ?></td>
                    <td class="numeric"><?php echo $report_data['ivf_with_bed_amount']; ?></td>
                </tr>
                <tr>
                    <td>Non IVF with Bed</td>
                    <td><?php echo $report_data['non_ivf_with_bed_c_count']; ?></td>
                    <td><?php echo $report_data['non_ivf_with_bed_b_count']; ?></td>
                    <td class="numeric"><?php echo $report_data['non_ivf_with_bed_amount']; ?></td>
                </tr>
                <tr>
                    <td>Non IVF without Bed</td>
                    <td><?php echo $report_data['non_ivf_without_bed_c_count']; ?></td>
                    <td><?php echo $report_data['non_ivf_without_bed_b_count']; ?></td>
                    <td classs="numeric"><?php echo $report_data['non_ivf_without_bed_amount']; ?></td>
                </tr>
                <tr>
                    <td>(Not Tagged)</td>
                    <td><?php echo $report_data['not_tagged_c_count']; ?></td>
                    <td><?php echo $report_data['not_tagged_b_count']; ?></td>
                    <td class="numeric"><?php echo $report_data['not_tagged_amount']; ?></td>
                </tr>
                <tr style="font-weight: bold; background-color: #f9f9f9;">
                    <td>A. Package Revenue Total</td>
                    <td><?php echo $report_data['package_customer_count']; ?></td>
                    <td><?php echo $report_data['package_amount']; ?></td>
                    <td class="numeric"><?php echo $report_data['package_bill_count']; ?></td>
                </tr>
                 <tr style="font-weight: bold; background-color: #f9f9f9;">
                    <td>Advance Payment</td>
                    <td><?php echo $report_data['package_customer_count']; ?></td>
                    <td><?php echo $report_data['package_amount']; ?></td>
                    <td class="numeric"><?php echo $report_data['advance_bill_count']; ?></td>
                </tr>
                 <tr style="font-weight: bold; background-color: #f9f9f9;">
                    <td>Partial Payment</td>
                    <td><?php echo $report_data['package_customer_count']; ?></td>
                    <td><?php echo $report_data['package_amount']; ?></td>
                    <td class="numeric"><?php echo $report_data['partial_amount']; ?></td>
                </tr>
               <tr>
    <td>Medicine</td>
    <td><?php echo $report_data['medicine_customer_count'] ?? 0; ?></td> 
    <td class="numeric"><?php echo $report_data['medicine_amount'] ?? 0; ?></td>
    <td><?php echo $report_data['medicine_bill_count'] ?? 0; ?></td>
</tr>
                <tr>
                    <td>Diagnosis</td>
                    <td><?php echo $report_data['diagnosis_customer_count']; ?></td>
                    <td><?php echo $report_data['diagnosis_bill_count']; ?></td>
                    <td class="numeric"><?php echo $report_data['diagnosis_amount']; ?></td>
                </tr>
                <tr>
                    <td>Consultation / Registration - Paid</td>
                    <td><?php echo $report_data['consultation_customer_count']; ?></td>
                    <td class="numeric"><?php echo $report_data['consultation_amount']; ?></td>
                    <td><?php echo $report_data['consultation_bill_count']; ?></td>
                </tr>
            </tbody>
        </table>
        
        <hr>
        

        
        <h3>Detailed Patient Lists</h3>
        <?php echo $details_html; ?>
        
    </div>
</body>
</html>