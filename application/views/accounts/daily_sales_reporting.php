<?php 
$all_method =&get_instance(); 

// --- 1. INITIALIZE DATE VARIABLES ---
// Check if dates are in the URL (e.g. ?start_date=2023-01-01). If not, use today.
$start_date = $this->input->get('start_date') ? $this->input->get('start_date') : date('Y-m-d');
$end_date   = $this->input->get('end_date') ? $this->input->get('end_date') : date('Y-m-d');

// Create a readable date string for the Subject line (e.g. "2023-11-18 to 2023-11-19")
if($start_date == $end_date) {
    $date_label = $start_date;
} else {
    $date_label = $start_date . ' to ' . $end_date;
}
?>
<div class="row mt-3">
    <div class="col-md-6">
        <!-- SAVE DRAFT BUTTON (Billing Manager) -->
        <button type="button" id="btnSaveDraft" class="btn btn-warning btn-lg btn-block">
            <i class="fas fa-save"></i> Save Report (Draft)
        </button>
    </div>
    
    <div class="col-md-6">
        <!-- APPROVE BUTTON (Only show if Counsellor is logged in) -->
        <?php if(isset($_SESSION['logged_counselor'])): ?>
            <button type="button" id="btnApproveSend" class="btn btn-success btn-lg btn-block">
                <i class="fas fa-check-circle"></i> Approve & Send Mail
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-secondary btn-lg btn-block" disabled>
                <i class="fas fa-lock"></i> Approval Pending (Counsellor Only)
            </button>
        <?php endif; ?>
    </div>
</div>

<!-- Result Message Area -->
<div id="actionResult" class="mt-3"></div>
<!-- ============================================================== 
     NEW: DATE FILTER SECTION 
============================================================== -->
<div class="card mt-3 mb-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Report by Date</h5>
    </div>
    <div class="card-body">
        <!-- Submits to current URL with GET method to filter data -->
        <form action="" method="get">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info btn-block">
                        <i class="fas fa-search"></i> Get Report
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== 
     EMAIL SENDING SECTION (Updated Subject Line)
============================================================== -->
<div class="card mt-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-envelope"></i> Send Report via Email</h5>
    </div>
    <div class="card-body">
        <form id="emailDailyReportForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="recipient_email"><strong>Recipient Emails:</strong></label>
                        <input type="text" id="recipient_email" name="recipient_email" 
                               class="form-control" required 
                               placeholder="user1@example.com, user2@example.com"
                               value="deepa.mishra@indiaivf.in, accounts@indiaivf.in, shanky.malhotra@indiaivf.in, pan.singh@indiaivf.in">
                        <small class="text-muted">
                            You can enter multiple emails, separated by a comma.
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email_subject"><strong>Subject:</strong></label>
                        <!-- UPDATED: Uses the dynamic $date_label we created at the top -->
                        <input type="text" id="email_subject" name="email_subject" 
                               class="form-control" 
                               value="Sales Report (<?php echo $date_label; ?>) - <?php echo $all_method->get_center_name($_SESSION['logged_billing_manager']['center']); ?>">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane"></i> Send Report
            </button>
            
            <div class="mt-2">
                <small class="text-muted">This will send the complete orderbook summary shown below.</small>
            </div>
        </form>
        
        <div id="emailResult" class="mt-3"></div>
    </div>
</div>
<script>
$(document).ready(function() {
    
    // 1. Helper function to gather all data
    function getReportData() {
        var email_form = $('#emailDailyReportForm').serialize();
        var summary_data = $('#reportDataForm').serialize();
        var details_html = $('.dashboard-2').html();
        var date_filter = $('input[name="start_date"]').val(); // Grab date from filter form if it exists
        
        var data = email_form + '&' + summary_data + '&details_html=' + encodeURIComponent(details_html);
        if(date_filter) { data += '&start_date=' + date_filter; }
        
        return data;
    }

    // 2. Handle "Save Draft" Click
    $('#btnSaveDraft').click(function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: '<?php echo site_url("accounts/save_draft_report"); ?>',
            type: 'POST',
            data: getReportData(),
            dataType: 'json',
            success: function(response) {
                var color = response.success ? 'success' : 'danger';
                $('#actionResult').html('<div class="alert alert-'+color+'">'+response.message+'</div>');
            },
            complete: function() { btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Report (Draft)'); }
        });
    });

    // 3. Handle "Approve & Send" Click
    $('#btnApproveSend').click(function() {
        var btn = $(this);
        if(!confirm('Are you sure you want to Approve this report and email it?')) return;
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Approving & Sending...');
        
        $.ajax({
            url: '<?php echo site_url("accounts/approve_and_send_email"); ?>',
            type: 'POST',
            data: getReportData(),
            dataType: 'json',
            success: function(response) {
                var color = response.success ? 'success' : 'danger';
                $('#actionResult').html('<div class="alert alert-'+color+'">'+response.message+'</div>');
            },
            error: function(xhr) {
                $('#actionResult').html('<div class="alert alert-danger">Server Error: ' + xhr.responseText + '</div>');
            },
            complete: function() { btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i> Approve & Send Mail'); }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    $('#emailDailyReportForm').submit(function(e) {
        e.preventDefault();
        
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
        
        var email_form_data = $(this).serialize();
        var summary_data = $('#reportDataForm').serialize();
        var details_html = $('.dashboard-2').html();
        
        var final_data = email_form_data + '&' + summary_data + '&details_html=' + encodeURIComponent(details_html);

        $.ajax({
            url: '<?php echo site_url("accounts/send_daily_report_email"); ?>',
            type: 'POST',
            data: final_data,
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
                <!-- UPDATED: Shows dynamic date -->
                <span>Orderbook Summary (<?php echo $date_label; ?>)</span>
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
                   <td><input type="text" id="package_amount" name="package_amount" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                    <td><input type="text" id="package_bill_count" name="package_bill_count" value="<?php echo round($vl['total_payment'],2); ?>"></td>
                    
                </tr>
                <?php } ?>

 <?php 
        $advance_net = 0;
        $advance_receive = 0;
        $advance_total = 0;
        $advance_discount = 0;
        foreach($advance_daily_result as $ky => $vl){
            $advance_net += round($vl['total_patients'],2);
            $advance_receive += round($vl['payment_done'],2);
            $advance_total += round($vl['fees'],2);
            $advance_discount += round($vl['discount_amount'],2);
        ?>
                <tr class="sub-header">
                    <td>Advance Payment</td>
                    <td><input type="text" id="package_customer_count" name="package_customer_count" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                   <td><input type="text" id="package_amount" name="package_amount" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                    <td><input type="text" id="advance_bill_count" name="advance_bill_count" value="<?php echo round($vl['total_payment'],2); ?>"></td>
                    
                </tr>
                <?php } ?>

                 <?php 
        $partial_net = 0;
        $partial_receive = 0;
        $partial_total = 0;
        $partial_discount = 0;
        foreach($partial_daily_result as $ky => $vl){
            $partial_net += round($vl['total_patients'],2);
            $partial_receive += round($vl['payment_done'],2);
            $partial_total += round($vl['fees'],2);
            $partial_discount += round($vl['discount_amount'],2);
        ?>
                <tr class="sub-header">
                    <td>Partial Payment</td>
                    <td><input type="text" id="package_customer_count" name="package_customer_count" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                   <td><input type="text" id="package_amount" name="package_amount" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                    <td><input type="text" id="partial_amount" name="partial_amount" value="<?php echo round($vl['total_payment'],2); ?>"></td>
                    
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
                    <td><input type="text" id="medicine_amount" name="medicine_amount" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                    <td><input type="text" id="medicine_bill_count" name="medicine_bill_count" value="<?php echo round($vl['total_payment'],2); ?>"></td>
                    
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
                   <td><input type="text" id="diagnosis_amount" name="diagnosis_amount" value="<?php echo round($vl['total_payment'],2); ?>"></td>
                    
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
                    <td><input type="text" id="consultation_amount" name="consultation_amount" value="<?php echo round($vl['total_patients'],2); ?>"></td>
                    <td><input type="text" id="consultation_bill_count" name="consultation_bill_count" value="<?php echo round($vl['total_payment'],2) + $registration_payment; ?>"></td>
                    
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

  <div class="card-content">
        <!-- Summary Section -->
        <div class="summary-section">
            <?php
            // Initialize totals for each payment method
            $paymentTotals = [
                'Card' => 0,
                'upi' => 0,
                'Cash' => 0,
                'Check' => 0,
                'IIC-Wallet' => 0,
                'Advance' => 0,
                'NEFT' => 0,
                'Loan' => 0,
                'Other' => 0
            ];
            
            // Collect all data in one array
            $allData = [];
            
            // Process procedure payments
            foreach($patient_procedure_daily_result as $vl) {
                $method = ucfirst(strtolower($vl['payment_method']));
                $paymentTotals[$method] += floatval($vl['payment_done']);
                $allData[] = [
                    'type' => 'Package',
                    'data' => $vl
                ];
            }
            
            // Process partial payments
            foreach($patient_partial_daily_result as $vl) {
                $method = ucfirst(strtolower($vl['payment_method']));
                $paymentTotals[$method] += floatval($vl['payment_done']);
                $allData[] = [
                    'type' => 'Partial',
                    'data' => $vl
                ];
            }
            
            // Process medicine payments
            foreach($patient_medicine_daily_result as $vl) {
                $method = ucfirst(strtolower($vl['payment_method']));
                $paymentTotals[$method] += floatval($vl['payment_done']);
                $allData[] = [
                    'type' => 'OPD Medicines',
                    'data' => $vl
                ];
            }
            
            // Process diagnostic payments
            foreach($patient_diagnostic_daily_result as $vl) {
                $method = ucfirst(strtolower($vl['payment_method']));
                $paymentTotals[$method] += floatval($vl['payment_done']);
                $allData[] = [
                    'type' => 'DIAGNOSTIC',
                    'data' => $vl
                ];
            }
            
            // Process consultation payments
            foreach($patient_consultation_daily_result as $vl) {
                $method = ucfirst(strtolower($vl['payment_method']));
                $paymentTotals[$method] += floatval($vl['payment_done']);
                $allData[] = [
                    'type' => 'OPD consultation',
                    'data' => $vl
                ];
            }
            
            // Calculate grand total
            $grandTotal = array_sum($paymentTotals);
            ?>
            
            <!-- Payment Summary Table -->
            <table class="payment-summary">
                <thead>
                <tr>
                    <th>FTD</th>
                    <th>Reporting Center</th>
                </tr>
                <tr>
                    <th><?php echo date('d/m/Y'); ?></th>
                    <th><?php echo $all_method->get_center_name($_SESSION['logged_billing_manager']['center']); ?></th>
                </tr>
                 <tr>
                    <th>Summary of Collection for <?php echo date('d/m/Y'); ?></th>
                    <th></th>
                </tr>
            </thead>
                <tbody>
                    <tr>
                        <td>Card Receipts</td>
                        <td><?php echo number_format($paymentTotals['Card'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>UPI Receipts</td>
                        <td><?php echo number_format($paymentTotals['Upi'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Cash Receipts</td>
                        <td><?php echo number_format($paymentTotals['Cash'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Check Receipts</td>
                        <td><?php echo number_format($paymentTotals['Check'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>IIC-Wallet Receipts</td>
                        <td><?php echo number_format($paymentTotals['IIC-Wallet'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Advance Receipts</td>
                        <td><?php echo number_format($paymentTotals['Advance'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>NEFT Receipts</td>
                        <td><?php echo number_format($paymentTotals['NEFT'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Loan Receipts</td>
                        <td><?php echo number_format($paymentTotals['Loan'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Other Receipts</td>
                        <td><?php echo number_format($paymentTotals['Other'], 2); ?></td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Total Receipts</strong></td>
                        <td><strong><?php echo number_format($grandTotal, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

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
    <style>
.report-title {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #3498db;
}

.summary-section {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.summary-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #ddd;
}

.report-info {
    display: flex;
    flex-direction: column;
}

.report-info .label {
    font-weight: bold;
    color: #7f8c8d;
    font-size: 12px;
    text-transform: uppercase;
}

.report-info .value {
    font-size: 14px;
    color: #2c3e50;
}

.summary-title {
    color: #27ae60;
    text-align: center;
    margin-bottom: 20px;
    font-size: 18px;
}

.payment-summary {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.payment-summary td {
    padding: 8px 15px;
    border-bottom: 1px solid #ddd;
}

.payment-summary td:first-child {
    width: 60%;
    font-weight: 500;
}

.payment-summary td:last-child {
    text-align: right;
    font-family: monospace;
}

.payment-summary .total-row {
    border-top: 2px solid #2c3e50;
    background: #ecf0f1;
}

.payment-summary .total-row td {
    font-weight: bold;
    font-size: 16px;
    color: #2c3e50;
}

.detailed-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
}

.detailed-table th {
    background: #3498db;
    color: white;
    padding: 12px;
    text-align: left;
    font-weight: 600;
}

.detailed-table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.detailed-table tr:hover {
    background: #f5f5f5;
}
</style>