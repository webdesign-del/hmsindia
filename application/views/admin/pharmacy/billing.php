<div class="content-wrapper">
    <section class="content-header">
        <h1>Pharmacy Billing</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                <form action="<?= base_url('admin/pharmacy/billing') ?>" method="post">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Patient ID / UHID</label>
                                <input type="text" name="patient_id" class="form-control" placeholder="Enter Patient ID" required>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h4>Bill Details</h4>
                            <div class="form-group">
                                <label>Bill Amount (₹)</label>
                                <input type="number" name="total_amount" id="main_bill_amt" class="form-control" value="1000" step="0.01" onkeyup="updateTotal()">
                            </div>

                            <div style="background: #fdf7f0; padding: 15px; border: 1px dashed #f39c12; border-radius: 5px;">
                                <label><i class="fa fa-tag"></i> Have a Cashback Code?</label>
                                <div class="input-group">
                                    <input type="text" name="coupon_code" id="coupon_input" class="form-control" placeholder="Enter Code">
                                    <span class="input-group-btn">
                                        <button type="button" id="apply_coupon_btn" class="btn btn-warning">Apply</button>
                                    </span>
                                </div>
                                <small id="coupon_resp_msg" style="display:block; margin-top:5px; font-weight:bold;"></small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-bordered" style="margin-top: 25px;">
                                <tr>
                                    <th>Total Amount</th>
                                    <td>₹<span id="display_total">1000.00</span></td>
                                </tr>
                                
                                <tr id="cashback_row" style="display:none; background: #e8f5e9; color: #2e7d32;">
                                    <th><i class="fa fa-wallet"></i> Cashback Earned</th>
                                    <td>+ ₹<span id="display_cashback">0.00</span></td>
                                </tr>

                                <tr style="font-size: 1.5em; background: #eee;">
                                    <th>Net Payable</th>
                                    <td>₹<span id="display_net">1000.00</span></td>
                                </tr>
                            </table>

                            <input type="hidden" name="cashback_amount" id="hidden_cashback" value="0">
                            <input type="hidden" name="net_payable" id="hidden_net" value="1000">
                            
                            <button type="submit" class="btn btn-success btn-lg btn-block">Generate Bill & Claim Cashback</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
/**
 * Updates display when user types in the Bill Amount
 */
function updateTotal() {
    let amt = parseFloat($('#main_bill_amt').val());
    if(isNaN(amt)) amt = 0;

    // Update the base total and net display (Net = Total for cashback scenarios)
    $('#display_total').text(amt.toFixed(2));
    $('#display_net').text(amt.toFixed(2));
    $('#hidden_net').val(amt.toFixed(2));

    // If they change the amount AFTER applying a coupon, reset the coupon to prevent fraud
    if ($('#hidden_cashback').val() > 0) {
        $('#hidden_cashback').val(0);
        $('#cashback_row').hide();
        $('#coupon_input').val('').attr('readonly', false);
        $('#apply_coupon_btn').attr('disabled', false).text('Apply');
        $('#coupon_resp_msg').text('Bill amount changed. Please re-apply coupon.').css('color', 'orange');
    }
}

$(document).ready(function() {
    /**
     * AJAX Call to validate code and calculate cashback
     */
    $('#apply_coupon_btn').click(function() {
        let code = $('#coupon_input').val();
        let total = parseFloat($('#main_bill_amt').val());
        
        if(isNaN(total) || total <= 0) {
            $('#coupon_resp_msg').html("Please enter a valid bill amount first.").css('color', 'orange');
            return;
        }

        $.ajax({
            url: '<?= base_url("admin/coupon/apply_coupon_ajax") ?>',
            type: 'POST',
            dataType: 'json',
            data: { 
                coupon_code: code, 
                total_amount: total, 
                service_type: 'medicine' 
            },
            success: function(res) {
                if(res.status == 'success') {
                    // 1. Success Message
                    $('#coupon_resp_msg').html('<i class="fa fa-check"></i> Valid! You will earn cashback.').css('color', 'green');
                    
                    // 2. Extract calculations from server response
                    let cashbackEarned = parseFloat(res.cashback_amount);

                    // 3. Update the UI
                    $('#display_cashback').text(cashbackEarned.toFixed(2));
                    $('#cashback_row').show(); // Reveal the hidden cashback row
                    
                    // 4. Update the hidden inputs for the Form POST
                    $('#hidden_cashback').val(cashbackEarned.toFixed(2));
                    
                    // 5. Lock the input
                    $('#coupon_input').attr('readonly', true);
                    $('#apply_coupon_btn').attr('disabled', true).text('Applied');
                } else {
                    // Show Error (Expired, Invalid, Minimum Bill Not Met)
                    $('#coupon_resp_msg').html('<i class="fa fa-times"></i> ' + res.message).css('color', 'red');
                }
            },
            error: function() {
                alert("Server error while validating coupon.");
            }
        });
    });
});
</script>