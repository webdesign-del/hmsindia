<?php
  // JSON डेटा को अनसीरियलाइज / डीकोड करें
  $bank = json_decode($refund_data['bank_details'], true);
  $return_details = json_decode($refund_data['return_details'], true);
  $deductions = json_decode($refund_data['deduction_details'], true);
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="row">
    <div class="col-md-12">
      <div class="page-header" style="border-bottom: 2px solid #ff9800; margin-bottom: 30px;">
        <h2 style="color: #ff9800; margin: 0; padding-bottom: 10px;">
          <i class="fa fa-calculator" style="margin-right: 10px;"></i>
          Accountant Verification & Review Form
        </h2>
        <p class="text-muted">Verify deductions, add suggestions, and forward for CEO Approval</p>
      </div>
    </div>
  </div>

  <!-- Wallet Display Section -->
  <div class="row" style="margin-bottom: 25px;">
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm" style="border-left: 5px solid #28a745; background: #f8fff9; padding: 20px; border-radius: 5px; border: 1px solid #e1e1e1;">
            <div class="card-body text-center">
                <h5 class="text-success font-weight-bold"><i class="fa fa-money"></i> Original Wallet Balance (W1)</h5>
                <h2 class="display-5" style="margin: 10px 0; color: #28a745;">₹ <?php echo number_format($refund_data['total_amount_paid'], 2); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm" style="border-left: 5px solid #d9534f; background: #fff5f5; padding: 20px; border-radius: 5px; border: 1px solid #e1e1e1;">
            <div class="card-body text-center">
                <h5 class="text-danger font-weight-bold"><i class="fa fa-tag"></i> Current Net Refund Amount</h5>
                <h2 class="display-5" style="margin: 10px 0; color: #d9534f;" id="net_refund_display">₹ <?php echo number_format($refund_data['net_refund_amount'], 2); ?></h2>
            </div>
        </div>
    </div>
  </div>

  <!-- Form Start -->
  <form class="form-horizontal" method="post" action="<?php echo site_url('Accounts/accountant_submit_review'); ?>">
      <input type="hidden" name="refund_id" value="<?php echo $refund_id; ?>">
      <input type="hidden" id="wallet_balance" value="<?php echo $refund_data['total_amount_paid']; ?>">

      <div class="row">
      <div class="col-sm-12">
        
        <!-- Patient Information (Readonly) -->
        <div class="panel panel-default">
          <div class="panel-heading" style="background: #f5f5f5;">
            <h3 class="panel-title" style="font-weight: 600;"><i class="fa fa-user"></i> Patient Information (Readonly)</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="form-group col-sm-6">
                <label>IIC ID:</label>
                <input type="text" class="form-control" value="<?php echo $refund_data['patient_id']; ?>" readonly>
              </div>
              <div class="form-group col-sm-6">
                <label>Patient Name:</label>
                <input type="text" class="form-control" value="<?php echo $refund_data['patient_name']; ?>" readonly>
              </div>
              <div class="form-group col-sm-6">
                <label>Contact Number:</label>
                <input type="text" class="form-control" value="<?php echo $refund_data['contact_number']; ?>" readonly>
              </div>
              <div class="form-group col-sm-6">
                <label>Receipt Number:</label>
                <input type="text" class="form-control" value="<?php echo $refund_data['receipt_number']; ?>" readonly>
              </div>
            </div>
          </div>
        </div>

        <!-- Deductions Adjustments Table (Editable by Accountant) -->
        <div class="panel panel-warning" style="border: 2px solid #ff9800;">
          <div class="panel-heading" style="background-color: #fcf8e3;">
            <h3 class="panel-title" style="font-weight: 600; color: #8a6d3b;"><i class="fa fa-calculator"></i> Verify & Adjust Deductions</h3>
          </div>
          <div class="panel-body">
            <p class="text-muted">आप आवश्यकतानुसार नीचे दी गई कटौतियों (Deductions) को री-चेक या अपडेट कर सकते हैं:</p>
            <table class="table table-bordered table-striped">
              <thead style="background-color: #34495e; color: white;">
                <tr><th>Deduction Category</th><th width="20%">Number</th><th width="30%">Total Amount (₹)</th></tr>
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
                <tr class="info">
                    <th colspan="2" class="text-right">Total Deductions:</th>
                    <th><input type="text" id="total_deductions" name="total_deductions" class="form-control" readonly value="<?php echo number_format($refund_data['total_deductions'], 2, '.', ''); ?>" style="font-weight:bold; color:red;"></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <!-- Accountant Suggestions & Action -->
        <div class="panel panel-info">
          <div class="panel-heading" style="background-color: #d9edf7;">
            <h3 class="panel-title" style="font-weight: 600;"><i class="fa fa-comment"></i> Accountant Remarks & Suggestions</h3>
          </div>
          <div class="panel-body">
            <div class="form-group col-sm-12">
              <label>Accountant Comments / Verification Notes (Required):</label>
              <textarea name="accountant_comments" class="form-control" rows="3" placeholder="Write your comments or suggestions for the CEO..." required><?php echo $refund_data['accountant_comments']; ?></textarea>
            </div>
            
            <div class="text-center mt-3 col-sm-12">
              <button type="submit" class="btn btn-warning btn-lg"><i class="fa fa-paper-plane"></i> Verify & Forward to CEO for Approval</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    // Dynamic Recalculation on Deduction Input Change
    $('.deduct-amount').on('input', function() {
      var total_deductions = 0;
      $('.deduct-amount').each(function() {
        var val = parseFloat($(this).val());
        if (!isNaN(val)) total_deductions += val;
      });
      $('#total_deductions').val(total_deductions.toFixed(2));

      // Calculate Net Refund
      var wallet_balance = parseFloat($('#wallet_balance').val()) || 0;
      var net_refund = wallet_balance - total_deductions;
      if (net_refund < 0) net_refund = 0;

      $('#net_refund_display').html('₹ ' + net_refund.toFixed(2));
    });
  });
</script>