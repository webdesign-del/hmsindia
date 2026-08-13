<div class="container-fluid">
  <!-- Page Header -->
  <div class="row">
    <div class="col-md-12">
      <div class="page-header" style="border-bottom: 2px solid #d9534f; margin-bottom: 30px;">
        <h2 style="color: #d9534f; margin: 0; padding-bottom: 10px;">
          <i class="fa fa-undo" style="margin-right: 10px;"></i>
          Refund Request Form
        </h2>
        <p class="text-muted">Process patient refunds and calculate deductions</p>
      </div>
    </div>
  </div>z

  <!-- Dynamic Wallet Display Section -->
  <div class="row" id="wallet_section" style="margin-bottom: 25px;">
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm" style="border-left: 5px solid #28a745; background: #f8fff9; padding: 20px; border-radius: 5px; border: 1px solid #e1e1e1;">
            <div class="card-body text-center">
                <h5 class="text-success font-weight-bold"><i class="fa fa-money"></i> Money Wallet (W1)</h5>
                <h2 class="display-5" style="margin: 10px 0; color: #28a745;" id="w1_display">₹ 0.00</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm" style="border-left: 5px solid #ff9800; background: #fffaf2; padding: 20px; border-radius: 5px; border: 1px solid #e1e1e1;">
            <div class="card-body text-center">
                <h5 class="text-warning font-weight-bold"><i class="fa fa-gift"></i> Package Wallet (W2)</h5>
                <h2 class="display-5" style="margin: 10px 0; color: #ff9800;" id="w2_display">₹ 0.00</h2>
            </div>
        </div>
    </div>
  </div>

  <!-- Form Start -->
  <form class="form-horizontal" method="post" action="<?php echo site_url('Accounts/save_refund'); ?>">
      <div class="row">
      <div class="col-sm-12">
        <!-- Patient/Customer Information[cite: 3] -->
        <div class="panel panel-danger">
          <div class="panel-heading" style="background: linear-gradient(135deg, #d9534f, #c9302c); border: none;">
            <h3 class="panel-title" style="color: white; font-weight: 600;"><i class="fa fa-user"></i> Patient/Customer Information</h3>
          </div>
          <div class="panel-body" style="background-color: #fafafa;">
            <div class="row">
              <div class="form-group col-sm-6 col-xs-12">
                <label class="control-label" style="font-weight: 600;">IIC ID <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                  <input type="text" id="patient_id" name="patient_id" class="form-control" placeholder="Enter IIC ID to fetch details" required>
                </div>
              </div>
              <div class="form-group col-sm-6 col-xs-12">
                <label class="control-label" style="font-weight: 600;">Name <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="text" id="patient_name" name="patient_name" class="form-control" required>
                </div>
              </div>
              <div class="form-group col-sm-6 col-xs-12">
                <label class="control-label" style="font-weight: 600;">Contact Number <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                  <input type="text" name="contact_number" class="form-control" required>
                </div>
              </div>
              <div class="form-group col-sm-6 col-xs-12">
                <label class="control-label" style="font-weight: 600;">Email Address</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <input type="email" name="email_address" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Purchase Details[cite: 3] -->
        <div class="panel panel-default">
          <div class="panel-heading" style="background-color: #f5f5f5;">
            <h3 class="panel-title" style="font-weight: 600;"><i class="fa fa-shopping-cart"></i> Purchase Details</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="form-group col-sm-6 col-xs-12">
                <label class="control-label" style="font-weight: 600;">Date of Purchase</label>
                <input type="date" name="purchase_date" class="form-control">
              </div>
              <div class="form-group col-sm-6 col-xs-12">
                <label class="control-label" style="font-weight: 600;">Receipt/Invoice Number <span class="text-danger">*</span></label>
                <input type="text" name="receipt_number" class="form-control" required>
              </div>
              <div class="form-group col-sm-6 col-xs-12">
                <label class="control-label" style="font-weight: 600;">Product/Service Purchased</label>
                <input type="text" name="service_purchased" class="form-control">
              </div>
              <div class="form-group col-sm-6 col-xs-12">
                <label class="control-label" style="font-weight: 600;">Total Amount Paid</label>
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  <input type="number" step="any" name="total_amount_paid" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Reason for Refund Request[cite: 3] -->
        <div class="panel panel-default">
          <div class="panel-heading" style="background-color: #f5f5f5;">
            <h3 class="panel-title" style="font-weight: 600;"><i class="fa fa-question-circle"></i> Reason for Refund Request</h3>
          </div>
          <div class="panel-body">
            <label class="radio-inline"><input type="radio" name="refund_reason" value="Unused Product Return" required> Unused Product Return</label>
            <label class="radio-inline"><input type="radio" name="refund_reason" value="Service Not Rendered"> Service Not Rendered</label>
            <label class="radio-inline"><input type="radio" name="refund_reason" value="Other"> Other</label>
            <input type="text" name="other_reason" class="form-control" placeholder="If Other (Please specify)" style="margin-top: 10px;">
          </div>
        </div>

        <!-- Return Details[cite: 3] -->
        <div class="panel panel-default">
          <div class="panel-heading" style="background-color: #f5f5f5;">
            <h3 class="panel-title" style="font-weight: 600;"><i class="fa fa-refresh"></i> Return Details (If applicable)</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="form-group col-sm-3"><label>Product Name</label><input type="text" name="return_product_name" class="form-control"></div>
              <div class="form-group col-sm-3"><label>Quantity</label><input type="number" name="return_quantity" class="form-control"></div>
              <div class="form-group col-sm-3"><label>Condition</label>
                <select name="product_condition" class="form-control">
                  <option value="">Select</option><option>Unopened/Sealed</option><option>Defective</option><option>Opened</option>
                </select>
              </div>
              <div class="form-group col-sm-3"><label>Expiration Date</label><input type="date" name="expiration_date" class="form-control"></div>
            </div>
          </div>
        </div>

        <!-- Bank Details[cite: 3] -->
        <div class="panel panel-default">
          <div class="panel-heading" style="background-color: #f5f5f5;">
            <h3 class="panel-title" style="font-weight: 600;"><i class="fa fa-bank"></i> Bank Details (If applicable)</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="form-group col-sm-6"><label>Name of Bank</label><input type="text" name="bank_name" class="form-control"></div>
              <div class="form-group col-sm-6"><label>Account Holder Name</label><input type="text" name="account_holder_name" class="form-control"></div>
              <div class="form-group col-sm-6"><label>Account Number</label><input type="text" name="account_number" class="form-control"></div>
              <div class="form-group col-sm-6"><label>IFSC Code</label><input type="text" name="ifsc_code" class="form-control"></div>
            </div>
          </div>
        </div>

        <!-- Deductions Calculation Table[cite: 3] -->
        <div class="panel panel-warning">
          <div class="panel-heading" style="background-color: #fcf8e3;">
            <h3 class="panel-title" style="font-weight: 600;"><i class="fa fa-calculator"></i> Calculating Deductions for Rare Refund Cases</h3>
          </div>
          <div class="panel-body">
            <table class="table table-bordered table-striped">
              <thead style="background-color: #34495e; color: white;">
                <tr><th>Deduction Category</th><th width="20%">Number</th><th width="30%">Total Amount (₹)</th></tr>
              </thead>
              <tbody>
                <tr><td>OPD Consultations: each</td><td><input type="number" name="deduct_opd_qty" class="form-control input-sm"></td><td><input type="number" step="any" name="deduct_opd_total" class="form-control input-sm deduct-amount" value="0"></td></tr>
                <tr><td>Ultrasounds (USG)</td><td><input type="number" name="deduct_usg_qty" class="form-control input-sm"></td><td><input type="number" step="any" name="deduct_usg_total" class="form-control input-sm deduct-amount" value="0"></td></tr>
                <tr><td>File Charges</td><td><input type="number" name="deduct_file_qty" class="form-control input-sm"></td><td><input type="number" step="any" name="deduct_file_total" class="form-control input-sm deduct-amount" value="0"></td></tr>
                <tr><td>Registration Charges</td><td><input type="number" name="deduct_reg_qty" class="form-control input-sm"></td><td><input type="number" step="any" name="deduct_reg_total" class="form-control input-sm deduct-amount" value="0"></td></tr>
                <tr><td>Injection amount (MRP)</td><td><input type="number" name="deduct_inj_qty" class="form-control input-sm"></td><td><input type="number" step="any" name="deduct_inj_total" class="form-control input-sm deduct-amount" value="0"></td></tr>
                <tr><td>Consumables details (MRP)</td><td><input type="number" name="deduct_con_qty" class="form-control input-sm"></td><td><input type="number" step="any" name="deduct_con_total" class="form-control input-sm deduct-amount" value="0"></td></tr>
                <tr><td>Subvention charges (EMI)</td><td><input type="number" name="deduct_sub_qty" class="form-control input-sm"></td><td><input type="number" step="any" name="deduct_sub_total" class="form-control input-sm deduct-amount" value="0"></td></tr>
                <tr><td>Miscellaneous</td><td><input type="number" name="deduct_misc_qty" class="form-control input-sm"></td><td><input type="number" step="any" name="deduct_misc_total" class="form-control input-sm deduct-amount" value="0"></td></tr>
              </tbody>
              <tfoot>
                <tr class="info"><th colspan="2" class="text-right">Total Deductions:</th><th><input type="text" id="total_deductions" name="total_deductions" class="form-control" readonly value="0.00" style="font-weight:bold; color:red;"></th></tr>
              </tfoot>
            </table>
          </div>
        </div>

        <!-- Feedback & Declaration[cite: 3] -->
        <div class="panel panel-info">
          <div class="panel-heading" style="background-color: #d9edf7;">
            <h3 class="panel-title" style="font-weight: 600;"><i class="fa fa-comment"></i> Feedback & Declaration</h3>
          </div>
          <div class="panel-body">
            <div class="form-group col-sm-12">
              <label>Overall Satisfaction with Our Process:</label><br>
              <label class="radio-inline"><input type="radio" name="satisfaction" value="1"> 1 - Very Dissatisfied</label>
              <label class="radio-inline"><input type="radio" name="satisfaction" value="2"> 2 - Dissatisfied</label>
              <label class="radio-inline"><input type="radio" name="satisfaction" value="3"> 3 - Neutral</label>
              <label class="radio-inline"><input type="radio" name="satisfaction" value="4"> 4 - Satisfied</label>
              <label class="radio-inline"><input type="radio" name="satisfaction" value="5"> 5 - Very Satisfied</label>
            </div>
            <div class="form-group col-sm-12"><label>Comments and Suggestions:</label><textarea name="feedback_comments" class="form-control" rows="3"></textarea></div>
            <div class="alert alert-warning" style="margin-top: 15px;"><strong>Declaration:</strong> I hereby declare that the information provided above is true and accurate to the best of my knowledge.</div>
            <div class="text-center mt-3"><button type="submit" class="btn btn-danger btn-lg"><i class="fa fa-check-circle"></i> Submit Refund Request</button></div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Scripts for AJAX & Calculations -->
<!-- Scripts for AJAX & Calculations -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    
    // 1. Auto-Calculate Total Deductions
    $('.deduct-amount').on('input', function() {
      var total = 0;
      $('.deduct-amount').each(function() {
        var val = parseFloat($(this).val());
        if (!isNaN(val)) total += val;
      });
      $('#total_deductions').val(total.toFixed(2));
    });

    // 2. Function to Fetch Patient Data via AJAX
    function fetchPatientWalletInfo(p_id) {
        if(p_id != '') {
            var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
            
            var requestData = { patient_id: p_id };
            if(csrfName && csrfHash) {
                requestData[csrfName] = csrfHash;
            }

            // [FIXED]: Controller ka naam check kijiye. URL ke hisab se ye 'billings' hona chahiye. 
            // Agar aapka function Accounts.php me hai, toh isko Accounts/get_patient_wallet_info hi rehne dein.
            $.ajax({
                url: '<?php echo site_url("accounts/get_patient_wallet_info"); ?>', 
                type: 'POST',
                data: requestData,
                dataType: 'json',
                success: function(response) {
                    if(response.status == 'success') {
                        // Display balance
                        $('#w1_display').html('₹ ' + parseFloat(response.wallet_1).toFixed(2));
                        $('#w2_display').html('₹ ' + parseFloat(response.wallet_2).toFixed(2));
                        
                        // Fill input fields
                        if(response.patient_name) $('#patient_name').val(response.patient_name);
                        if(response.phone) $('input[name="contact_number"]').val(response.phone);
                        if(response.email) $('input[name="email_address"]').val(response.email);
                    } else {
                        console.log("Response error: ", response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error details: ", error);
                    console.log("Server Response: ", xhr.responseText);
                }
            });
        }
    }

    // A. Manually blur par chalega
    $('#patient_id').on('blur', function() {
        fetchPatientWalletInfo($(this).val());
    });

    // B. Page load par chalega (Kyuki ID URL se apne aap aa raha hai)
    var initial_patient_id = $('#patient_id').val();
    if (initial_patient_id !== '') {
        fetchPatientWalletInfo(initial_patient_id);
    }

  });
</script>