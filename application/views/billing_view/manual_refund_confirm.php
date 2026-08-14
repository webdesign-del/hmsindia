<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Refund Execution & Confirmation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background-color: #f4f6f9; padding-top: 30px; font-family: Arial, sans-serif; }
        .card-panel { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
        .amount-card { background: #fff5f5; border-left: 5px solid #d9534f; padding: 15px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-panel">
                <h3 style="color:#d9534f; font-weight:bold; border-bottom:2px solid #d9534f; padding-bottom:10px; margin-top:0;">
                    <i class="fa fa-hand-o-right"></i> Manual Refund Execution & Wallet Deduction
                </h3>

                <!-- Summary Details -->
                <div class="row" style="margin-top:20px;">
                    <div class="col-sm-6">
                        <div class="amount-card">
                            <span class="text-muted">Current Wallet Balance:</span>
                            <h3 style="margin:5px 0; color:#28a745; font-weight:bold;">₹ <?php echo number_format($current_w1, 2); ?></h3>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="amount-card" style="border-left-color: #28a745; background:#f0fff4;">
                            <span class="text-muted">Net Refund to Process:</span>
                            <h3 style="margin:5px 0; color:#d9534f; font-weight:bold;">₹ <?php echo number_format($refund_amount, 2); ?></h3>
                        </div>
                    </div>
                </div>

                <form method="post" action="<?php echo site_url('Accounts/execute_manual_refund'); ?>">
                    <input type="hidden" name="refund_id" value="<?php echo $refund_id; ?>">
                    <input type="hidden" name="patient_id" value="<?php echo $refund_data['patient_id']; ?>">
                    <input type="hidden" name="refund_amount" value="<?php echo $refund_amount; ?>">

                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Patient Details & Bank Info</b></div>
                        <div class="panel-body">
                            <p><b>Patient:</b> <?php echo $refund_data['patient_name']; ?> (ID: <?php echo $refund_data['patient_id']; ?>)</p>
                            <p><b>Receipt No:</b> <?php echo $refund_data['receipt_number']; ?></p>
                            <?php $bank = json_decode($refund_data['bank_details'], true); ?>
                            <p><b>Bank Account:</b> <?php echo !empty($bank['bank_name']) ? $bank['bank_name'] . ' - ' . $bank['account_number'] : 'N/A'; ?></p>
                        </div>
                    </div>

                    <!-- Manual Payment Entry Fields -->
                    <div class="form-group">
                        <label>Select Payment Payout Mode <span class="text-danger">*</span></label>
                        <select name="payment_mode" class="form-control" required>
                            <option value="Bank Transfer (NEFT/RTGS)">Bank Transfer (NEFT/RTGS/IMPS)</option>
                            <option value="UPI / Online">UPI / GPay / PhonePe</option>
                            <option value="Cash Handover">Cash Handover</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Bank Transaction Ref / UTR No.</label>
                        <input type="text" name="txn_reference" class="form-control" placeholder="Enter Transaction UTR No. or Reference ID">
                    </div>

                    <div class="form-group">
                        <label>Accounts Remarks / Notes</label>
                        <textarea name="accounts_note" class="form-control" rows="2" placeholder="Enter any final notes regarding payout..."></textarea>
                    </div>

                    <!-- Confirm Action Button -->
                    <div class="text-center" style="margin-top:25px;">
                        <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Are you sure you want to deduct ₹<?php echo number_format($refund_amount, 2); ?> from patient wallet?');">
                            <i class="fa fa-check-circle"></i> CONFIRM & DEDUCT WALLET BALANCE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>