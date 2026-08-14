<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Verification & Review Form</title>
    
    <!-- 🎯 1. Bootstrap 3 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- 🎯 2. Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- 🎯 3. jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 50px;
        }
        .main-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 30px;
            margin-top: 30px;
        }
        .header-title {
            color: #d9534f;
            font-weight: 700;
            border-bottom: 2px solid #d9534f;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .wallet-box-w1 {
            background-color: #f0fff4;
            border-left: 5px solid #28a745;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .wallet-box-net {
            background-color: #fff5f5;
            border-left: 5px solid #dc3545;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .amount-display {
            font-size: 26px;
            font-weight: bold;
            margin-top: 5px;
        }
        .panel-custom {
            border-color: #ddd;
        }
        .panel-custom > .panel-heading {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .table-deductions th {
            background-color: #34495e;
            color: #ffffff;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="main-card">
        
        <!-- Header -->
        <div class="row">
            <div class="col-md-12">
                <h2 class="header-title">
                    <i class="fa fa-user-md"></i> Accountant Verification & Review Form
                </h2>
                <p class="text-muted">Verify deductions, add suggestions, and forward for CEO Approval</p>
            </div>
        </div>

        <?php 
            $bank = json_decode($refund_data['bank_details'], true);
            $deductions = json_decode($refund_data['deduction_details'], true);
            $w1_balance = floatval($refund_data['total_amount_paid']);
            $current_deductions = floatval($refund_data['total_deductions']);
            $net_refund = floatval($refund_data['net_refund_amount']);
        ?>

        <!-- Wallet Displays -->
        <div class="row">
            <div class="col-md-6">
                <div class="wallet-box-w1">
                    <span class="text-muted" style="font-weight: 600;">Original Wallet Balance (W1)</span>
                    <div class="amount-display text-success">₹ <?php echo number_format($w1_balance, 2); ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="wallet-box-net">
                    <span class="text-muted" style="font-weight: 600;">Current Net Refund Amount</span>
                    <div class="amount-display text-danger" id="display_net_refund">₹ <?php echo number_format($net_refund, 2); ?></div>
                </div>
            </div>
        </div>

        <form method="post" action="<?php echo site_url('Accounts/accountant_submit_review'); ?>">
            <input type="hidden" name="refund_id" value="<?php echo $refund_id; ?>">
            <input type="hidden" id="w1_balance_val" value="<?php echo $w1_balance; ?>">

            <!-- Patient Information Panel -->
            <div class="panel panel-default panel-custom">
                <div class="panel-heading"><i class="fa fa-id-card"></i> Patient Information (Readonly)</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-md-3 col-sm-6">
                            <label>IIC ID</label>
                            <input type="text" class="form-control" value="<?php echo $refund_data['patient_id']; ?>" readonly>
                        </div>
                        <div class="form-group col-md-3 col-sm-6">
                            <label>Patient Name</label>
                            <input type="text" class="form-control" value="<?php echo $refund_data['patient_name']; ?>" readonly>
                        </div>
                        <div class="form-group col-md-3 col-sm-6">
                            <label>Contact Number</label>
                            <input type="text" class="form-control" value="<?php echo $refund_data['contact_number']; ?>" readonly>
                        </div>
                        <div class="form-group col-md-3 col-sm-6">
                            <label>Receipt Number</label>
                            <input type="text" class="form-control" value="<?php echo $refund_data['receipt_number']; ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deductions Calculation Table -->
            <div class="panel panel-warning">
                <div class="panel-heading" style="background-color: #fcf8e3; color: #8a6d3b; font-weight: bold;">
                    <i class="fa fa-calculator"></i> Verify & Adjust Deductions
                </div>
                <div class="panel-body">
                    <p class="text-info"><i class="fa fa-info-circle"></i> आप आवश्यकतानुसार नीचे दी गई कटौतियों (Deductions) को री-चेक या अपडेट कर सकते हैं:</p>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-deductions">
                            <thead>
                                <tr>
                                    <th>Deduction Category</th>
                                    <th width="20%">Number</th>
                                    <th width="30%">Total Amount (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>OPD Consultations</td>
                                    <td><input type="number" name="deduct_opd_qty" class="form-control input-sm" value="<?php echo isset($deductions['opd']['qty']) ? $deductions['opd']['qty'] : ''; ?>"></td>
                                    <td><input type="number" step="any" name="deduct_opd_total" class="form-control input-sm deduct-amount" value="<?php echo isset($deductions['opd']['total']) ? $deductions['opd']['total'] : 0; ?>"></td>
                                </tr>
                                <tr>
                                    <td>Ultrasounds (USG)</td>
                                    <td><input type="number" name="deduct_usg_qty" class="form-control input-sm" value="<?php echo isset($deductions['usg']['qty']) ? $deductions['usg']['qty'] : ''; ?>"></td>
                                    <td><input type="number" step="any" name="deduct_usg_total" class="form-control input-sm deduct-amount" value="<?php echo isset($deductions['usg']['total']) ? $deductions['usg']['total'] : 0; ?>"></td>
                                </tr>
                                <tr>
                                    <td>File Charges</td>
                                    <td><input type="number" name="deduct_file_qty" class="form-control input-sm" value="<?php echo isset($deductions['file']['qty']) ? $deductions['file']['qty'] : ''; ?>"></td>
                                    <td><input type="number" step="any" name="deduct_file_total" class="form-control input-sm deduct-amount" value="<?php echo isset($deductions['file']['total']) ? $deductions['file']['total'] : 0; ?>"></td>
                                </tr>
                                <tr>
                                    <td>Registration Charges</td>
                                    <td><input type="number" name="deduct_reg_qty" class="form-control input-sm" value="<?php echo isset($deductions['reg']['qty']) ? $deductions['reg']['qty'] : ''; ?>"></td>
                                    <td><input type="number" step="any" name="deduct_reg_total" class="form-control input-sm deduct-amount" value="<?php echo isset($deductions['reg']['total']) ? $deductions['reg']['total'] : 0; ?>"></td>
                                </tr>
                                <tr>
                                    <td>Injection amount (MRP)</td>
                                    <td><input type="number" name="deduct_inj_qty" class="form-control input-sm" value="<?php echo isset($deductions['inj']['qty']) ? $deductions['inj']['qty'] : ''; ?>"></td>
                                    <td><input type="number" step="any" name="deduct_inj_total" class="form-control input-sm deduct-amount" value="<?php echo isset($deductions['inj']['total']) ? $deductions['inj']['total'] : 0; ?>"></td>
                                </tr>
                                <tr>
                                    <td>Consumables details (MRP)</td>
                                    <td><input type="number" name="deduct_con_qty" class="form-control input-sm" value="<?php echo isset($deductions['con']['qty']) ? $deductions['con']['qty'] : ''; ?>"></td>
                                    <td><input type="number" step="any" name="deduct_con_total" class="form-control input-sm deduct-amount" value="<?php echo isset($deductions['con']['total']) ? $deductions['con']['total'] : 0; ?>"></td>
                                </tr>
                                <tr>
                                    <td>Subvention charges (EMI)</td>
                                    <td><input type="number" name="deduct_sub_qty" class="form-control input-sm" value="<?php echo isset($deductions['sub']['qty']) ? $deductions['sub']['qty'] : ''; ?>"></td>
                                    <td><input type="number" step="any" name="deduct_sub_total" class="form-control input-sm deduct-amount" value="<?php echo isset($deductions['sub']['total']) ? $deductions['sub']['total'] : 0; ?>"></td>
                                </tr>
                                <tr>
                                    <td>Miscellaneous</td>
                                    <td><input type="number" name="deduct_misc_qty" class="form-control input-sm" value="<?php echo isset($deductions['misc']['qty']) ? $deductions['misc']['qty'] : ''; ?>"></td>
                                    <td><input type="number" step="any" name="deduct_misc_total" class="form-control input-sm deduct-amount" value="<?php echo isset($deductions['misc']['total']) ? $deductions['misc']['total'] : 0; ?>"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="active">
                                    <th colspan="2" class="text-right" style="font-size: 15px; vertical-align: middle;">Total Deductions:</th>
                                    <th>
                                        <input type="text" id="total_deductions" name="total_deductions" class="form-control" readonly value="<?php echo number_format($current_deductions, 2, '.', ''); ?>" style="font-weight:bold; color:red; font-size:16px;">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Accountant Remarks & Suggestions -->
            <div class="panel panel-info">
                <div class="panel-heading"><i class="fa fa-comments"></i> Accountant Remarks / Suggestions for CEO</div>
                <div class="panel-body">
                    <div class="form-group">
                        <textarea name="accountant_comments" class="form-control" rows="3" placeholder="Add your verification notes, suggestions or comments here..."><?php echo isset($refund_data['accountant_comments']) ? $refund_data['accountant_comments'] : ''; ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center" style="margin-top: 25px;">
                <button type="submit" class="btn btn-success btn-lg" style="padding: 12px 35px; font-weight: bold;">
                    <i class="fa fa-paper-plane"></i> Verify & Forward to CEO
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    $(document).ready(function() {
        // Auto Calculate Total Deductions & Net Refund Live
        $('.deduct-amount').on('input', function() {
            var total_deduct = 0;
            $('.deduct-amount').each(function() {
                var val = parseFloat($(this).val());
                if (!isNaN(val)) total_deduct += val;
            });
            
            $('#total_deductions').val(total_deduct.toFixed(2));

            // Live Update Net Refund Amount
            var w1 = parseFloat($('#w1_balance_val').val()) || 0;
            var net = w1 - total_deduct;
            if (net < 0) net = 0;

            $('#display_net_refund').html('₹ ' + net.toFixed(2));
        });
    });
</script>

</body>
</html>