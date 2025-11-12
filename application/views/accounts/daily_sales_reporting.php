<?php $all_method =&get_instance(); ?>
<div class="card mt-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-envelope"></i> Send Report via Email</h5>
    </div>
    <div class="card-body">
        <form id="emailDailyReportForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="recipient_email"><strong>Recipient Email:</strong></label>
                        <input type="email" id="recipient_email" name="recipient_email" 
                               class="form-control" required 
                               placeholder="Enter email address"
                               value="ghanshyam.it.kr@gmail.com">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email_subject"><strong>Subject:</strong></label>
                        <input type="text" id="email_subject" name="email_subject" 
                               class="form-control" 
                               value="Daily Sales Report - <?php echo $all_method->get_center_name($_SESSION['logged_billing_manager']['center']); echo date('Y-m-d'); ?>">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane"></i> Send Daily Report
            </button>
            
            <div class="mt-2">
                <small class="text-muted">This will send the complete orderbook summary shown above.</small>
            </div>
        </form>
        
        <div id="emailResult" class="mt-3"></div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#emailDailyReportForm').submit(function(e) {
        e.preventDefault();
        
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
        
        // --- THIS IS THE CRITICAL FIX ---
        // 1. Get data from the email form
        var email_form_data = $(this).serialize();
        // 2. Get data from the summary table form
        var summary_data = $('#reportDataForm').serialize();
        // 3. Get the raw HTML of the detailed patient lists
        var details_html = $('.dashboard-2').html();
        
        // 4. Combine all data to be sent
        var final_data = email_form_data + '&' + summary_data + '&details_html=' + encodeURIComponent(details_html);

        $.ajax({
            url: '<?php echo site_url("accounts/send_daily_report_email"); ?>',
            type: 'POST',
            data: final_data, // Send all the combined data
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#emailResult').html(
                        '<div class="alert alert-success alert-dismissible fade show">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<i class="fas fa-check-circle"></i> <strong>Success!</strong> ' + response.message + '<br>' +
                        '<small>Sent to: ' + response.recipient + ' at ' + response.timestamp + '</small>' +
                        '</div>'
                    );
                } else {
                    $('#emailResult').html(
                        '<div class="alert alert-danger alert-dismissible fade show">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<i class="fas fa-exclamation-circle"></i> <strong>Error!</strong> ' + response.message +
                        '</div>'
                    );
                }
            },
            error: function(xhr, status, error) {
                 var errorMsg = xhr.responseText || 'Request Failed! Please check server logs.';
                $('#emailResult').html(
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                    '<i class="fas fa-exclamation-circle"></i> <strong>Request Failed!</strong><br>' +
                    '<small>The server returned this error:</small><br>' +
                    '<pre style="white-space: pre-wrap; border: 1px solid #d4a5a5; padding: 5px; background: #f8d7da;">' + errorMsg + '</pre>' +
                    '</div>'
                );
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
 
    <div class="container-2">
        
        <div class="dashboard">
            <div class="card">
                <div class="card-header">
                    <span>Orderbook Summary</span>
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <form id="reportDataForm">
                <div class="card-content">
                   
                    
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
                                <td><input type="text" id="ivf_cycles_sold_c_count" name="ivf_cycles_sold_c_count"></td>
                                <td><input type="text" id="ivf_cycles_sold_b_count" name="ivf_cycles_sold_b_count"></td>
                                <td class="numeric"><input type="text" id="ivf_cycles_sold_amount" name="ivf_cycles_sold_amount"></td>
                            </tr>
                            <tr>
                                <td>IVF with Bed</td>
                                <td><input type="text" id="ivf_with_bed_c_count" name="ivf_with_bed_c_count"></td>
                                <td><input type="text" id="ivf_with_bed_b_count" name="ivf_with_bed_b_count"></td>
                                <td class="numeric"><input type="text" id="ivf_with_bed_amount" name="ivf_with_bed_amount" ></td>
                            </tr>
                            <tr>
                                <td>Non IVF with Bed</td>
                                <td><input type="text" id="non_ivf_with_bed_c_count" name="non_ivf_with_bed_c_count"></td>
                                <td><input type="text" id="non_ivf_with_bed_b_count" name="non_ivf_with_bed_b_count"></td>
                                <td class="numeric"><input type="text" id="non_ivf_with_bed_amount" name="non_ivf_with_bed_amount"></td>
                            </tr>
                            <tr>
                                <td>Non IVF without Bed</td>
                                <td><input type="text" id="non_ivf_without_bed_c_count" name="non_ivf_without_bed_c_count"></td>
                                <td><input type="text" id="non_ivf_without_bed_b_count" name="non_ivf_without_bed_b_count"></td>
                                <td class="numeric"><input type="text" id="non_ivf_without_bed_amount" name="non_ivf_without_bed_amount"></td>
                            </tr>
                            <tr>
                                <td>(Not Tagged)</td>
                                <td><input type="text" id="not_tagged_c_count" name="not_tagged_c_count"></td>
                                <td><input type="text" id="not_tagged_b_count" name="not_tagged_b_count"></td>
                                <td class="numeric"><input type="text" id="not_tagged_amount" name="not_tagged_amount"></td>
                            </tr>
                           <?php 
            $procedure_net = 0;
            $procedure_receive = 0;
            $procedure_total = 0;
            $procedure_discount = 0;
            foreach($procedure_daily_result as $ky => $vl){
                $procedure_net += round($vl['total_patients'],2);
                $procedure_receive += round($vl['payment_done'],2);
                $procedure_total += round($vl['fees'],2);
                $procedure_discount += round($vl['discount_amount'],2);
            ?>
                    <tr class="sub-header">
                        <td>A. Package Revenue Total</td>
                        <td><input type="text" id="package_customer_count" name="package_customer_count" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                        <td><input type="text" id="package_bill_count" name="package_bill_count" value="<?php echo round($vl['total_fees'],2); ?>"></td>
                        <td><input type="text" id="package_amount" name="package_amount" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                    </tr>
                    <?php } ?>
                             <?php 
            $medicine_net = 0;
            $medicine_receive = 0;
            $medicine_total = 0;
            $medicine_discount = 0;
            foreach($medicine_daily_result as $ky => $vl){
                $medicine_net += round($vl['total_patients'],2);
                $medicine_receive += round($vl['payment_done'],2);
                $medicine_total += round($vl['fees'],2);
                $medicine_discount += round($vl['discount_amount'],2);
            ?>
                    <tr>
                        <td>Medicine</td>
                        <td><input type="text" id="medicine_customer_count" name="medicine_customer_count" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                        <td><input type="text" id="medicine_bill_count" name="medicine_bill_count" value="<?php echo round($vl['total_payment'],2); ?>"></td>
                        <td><input type="text" id="medicine_amount" name="medicine_amount" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                    </tr>
                    <?php } ?>
                            <?php 
            $investigations_net = 0;
            $investigations_receive = 0;
            $investigations_total = 0;
            $investigations_discount = 0;
            foreach($investigations_daily_result as $ky => $vl){
                $investigations_net += round($vl['total_patients'],2);
                $investigations_receive += round($vl['payment_done'],2);
                $investigations_total += round($vl['fees'],2);
                $investigations_discount += round($vl['discount_amount'],2);
            ?>
                    <tr>
                        <td>Diagnosis</td>
                        <td><input type="text" id="diagnosis_customer_count" name="diagnosis_customer_count" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                        <td><input type="text" id="diagnosis_bill_count" name="diagnosis_bill_count" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                        <td><input type="text" id="diagnosis_amount" name="diagnosis_amount" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                    </tr>
                    <?php } ?>
                           <?php 
            $consultation_net = 0;
            $consultation_receive = 0;
            $consultation_total = 0;
            $consultation_discount = 0;
            $registration_payment = 0 ;
            foreach($registration_daily_result as $ky => $vl){
                $registration_payment = round($vl['total_payment'],2);
            } 
            foreach($consultation_daily_result as $ky => $vl){
                $consultation_net += round($vl['total_patients'],2);
                $consultation_receive += round($vl['payment_done'],2);
                $consultation_total += round($vl['fees'],2);
                $consultation_discount += round($vl['discount_amount'],2);
            ?>
                    <tr>
                        <td>Consultation / Registration - Paid</td>
                        <td><input type="text" id="consultation_customer_count" name="consultation_customer_count" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                        <td><input type="text" id="consultation_bill_count" name="consultation_bill_count" value="<?php echo round($vl['total_payment'],2) + $registration_payment; ?>"></td>
                        <td><input type="text" id="consultation_amount" name="consultation_amount" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                    </tr>
                    <?php } ?>
                            <tr>
                                <td>Fellowship</td>
                                <td></td>
                                <td></td>
                                <td class="numeric"></td>
                            </tr>
                            <tr class="total-row">
                                <td>Total Revenue</td>
                                <td></td>
                                <td></td>
                                <td class="numeric"></td>
                            </tr>
                            <tr class="total-row">
                                <td>Status</td>
                                <td></td>
                                <td colspan="2">
                                <div class="approver-item"><div style="display: flex; align-items: center; margin-bottom: 4px;"><a href="javascript:void(0);" class="btn btn-large" onclick="approveProcedure('<?php echo $vl['ID']; ?>')">Approve</a></div><div class="approver-email"><?php echo $_SESSION['logged_billing_manager']['name']?></div></div>
                                <div class="approver-item"><div style="display: flex; align-items: center; margin-bottom: 4px;"><a href="javascript:void(0);" class="btn btn-large" onclick="approveProcedure('<?php echo $vl['ID']; ?>')">Approve</a></div><div class="approver-email"><?php echo $_SESSION['logged_counselor']['name']?></div></div>
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="submit" id="submit">
            </form>
            </div>
        </div>
        
       <div class="dashboard-2">
    <div class="card">
    <div class="card-content">
                 <table>
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>IIC ID</th>
                                <th>Patient Name</th>
                                <th>Category</th>
                                <th>Pkg Code</th>
                                <th>Pkg Description</th>
                                <th>Type </th>
                                <th>mode</th>
                                <th>Collection Amount Inc GST</th>
                                <th>Date</th>
                                <th>Receipts No / Adjustment No</th>
                                <th>Screenshort</th>
                                <th>Fresh/Partial/Advance</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
    
                     <?php 
            foreach($patient_procedure_daily_result as $ky => $vl){
              
            ?>
                    <tr>
                        <td><?php echo $vl['']; ?></td>
                        <td><?php echo $vl['patient_id']; ?></td>
                        <td><?php echo $patient_name = $all_method->get_patient_name($vl['patient_id']); ?></td>
                        <td>Package</td>
                        <td><?php echo $vl['code']; ?></td>
                        <td><?php echo $vl['procedure_name']; ?></td>
                        <td>Booking</td>
                        <td><?php echo $vl['payment_method']; ?></td>
                        <td><?php echo $vl['payment_done']; ?></td>
                        <td><?php echo $vl['on_date']; ?></td>
                        <td><?php echo $vl['receipt_number']; ?></td>
                         <td><?php echo $vl['transaction_img']; ?></td>
                    </tr>
					<?php } ?>
                    <?php 
            foreach($patient_partial_daily_result as $ky => $vl){
              
            ?>
                    <tr>
                        <td><?php echo $vl['']; ?></td>
                        <td><?php echo $vl['patient_id']; ?></td>
                        <td><?php echo $patient_name = $all_method->get_patient_name($vl['patient_id']); ?></td>
                        <td>Partial</td>
                        <td></td>
                        <td></td>
                        <td>Booking</td>
                        <td><?php echo $vl['payment_method']; ?></td>
                        <td><?php echo $vl['payment_done']; ?></td>
                        <td><?php echo $vl['on_date']; ?></td>
                        <td><?php echo $vl['refrence_number']; ?></td>
                         <td><?php echo $vl['transaction_img']; ?></td>
                    </tr>
					<?php } ?>
                    <?php 
            foreach($patient_medicine_daily_result as $ky => $vl){
            ?>
                    <tr>
                        <td><?php echo $vl['']; ?></td>
                        <td><?php echo $vl['patient_id']; ?></td>
                        <td><?php echo $vl['patient_detail_name']; ?></td>
                        <td>OPD Medicines</td>
                        <td></td>
                        <td></td>
                        <td>Sale Receipts</td>
                        <td><?php echo $vl['payment_method']; ?></td>
                        <td><?php echo $vl['payment_done']; ?></td>
                        <td><?php echo $vl['on_date']; ?></td>
                        <td><?php echo $vl['receipt_number']; ?></td>
                         <td><?php echo $vl['transaction_img']; ?></td>
                    </tr>
					<?php } ?>
                     <?php 
            foreach($patient_diagnostic_daily_result as $ky => $vl){
            ?>
                    <tr>
                        <td><?php echo $vl['']; ?></td>
                        <td><?php echo $vl['patient_id']; ?></td>
                        <td><?php echo $patient_name = $all_method->get_patient_name($vl['patient_id']); ?></td>
                        <td>DIAGNOSTIC</td>
                        <td></td>
                        <td></td>
                        <td>Sale Receipts</td>
                        <td><?php echo $vl['payment_method']; ?></td>
                        <td><?php echo $vl['payment_done']; ?></td>
                        <td><?php echo $vl['on_date']; ?></td>
                        <td><?php echo $vl['receipt_number']; ?></td>
                         <td><?php echo $vl['transaction_img']; ?></td>
                    </tr>
					<?php } ?>
                      <?php 
                   foreach($patient_diagnostic_daily_result as $ky => $vl){
                ?>
                    <tr>
                        <td><?php echo $vl['']; ?></td>
                        <td><?php echo $vl['patient_id']; ?></td>
                        <td><?php echo $patient_name = $all_method->get_patient_name($vl['patient_id']); ?></td>
                        <td>DIAGNOSTIC</td>
                        <td></td>
                        <td></td>
                        <td>Sale Receipts</td>
                        <td><?php echo $vl['payment_method']; ?></td>
                        <td><?php echo $vl['payment_done']; ?></td>
                        <td><?php echo $vl['on_date']; ?></td>
                        <td><?php echo $vl['receipt_number']; ?></td>
                         <td><?php echo $vl['transaction_img']; ?></td>
                    </tr>
					<?php } ?>
                      <?php 
                   foreach($patient_consultation_daily_result as $ky => $vl){
                ?>
                    <tr>
                        <td><?php echo $vl['']; ?></td>
                        <td><?php echo $vl['patient_id']; ?></td>
                        <td><?php echo $patient_name = $all_method->get_patient_name($vl['patient_id']); ?></td>
                        <td>OPD consultation</td>
                        <td></td>
                        <td></td>
                        <td>Sale Receipts</td>
                        <td><?php echo $vl['payment_method']; ?></td>
                        <td><?php echo $vl['payment_done']; ?></td>
                        <td><?php echo $vl['on_date']; ?></td>
                        <td><?php echo $vl['receipt_number']; ?></td>
                         <td><?php echo $vl['transaction_img']; ?></td>
                    </tr>
					<?php } ?>
            </tbody>
            </table>
            </div>    
             </div>    
              </div>    

    </div>
    <script>
        // Simple JavaScript to update the date display when the date input is changed
        document.getElementById('date-select').addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            document.querySelector('.date-display').innerHTML = 
                '<i class="fas fa-calendar-alt"></i> ' + selectedDate.toLocaleDateString('en-US', options);
        });
    </script>
	 <style>
        header {
            background: linear-gradient(135deg, #2c3e50, #1a2530);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        
        .logo i {
            margin-right: 10px;
            color: #4CAF50;
        }
        
        .date-display {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .filters {
            display: flex;
            gap: 15px;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .filter-item {
            flex: 1;
        }
        
        .filter-item label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            opacity: 0.8;
        }
        
        .filter-item select, .filter-item input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: white;
            color: #333;
        }
        
        .dashboard {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header i {
            font-size: 20px;
        }
        
        .card-content {
            padding: 20px;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th {
            background-color: #f1f5f9;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #e9ecef;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .numeric {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }
        
        .positive {
            color: #2E7D32;
        }
        
        .section-header {
            background-color: #e3f2fd;
            font-weight: 600;
        }
        
        .total-row {
            font-weight: 700;
            background-color: #f1f8e9;
        }
        
        .chart-container {
            height: 250px;
            padding: 15px 0;
        }
        
        @media (max-width: 900px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .filters {
                flex-direction: column;
            }
        }
    </style>