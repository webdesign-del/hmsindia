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
                                <label><i class="fa fa-tag"></i> Have a Coupon?</label>
                                <div class="input-group">
                                    <input type="text" id="coupon_input" class="form-control" placeholder="Enter Code">
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
                                <tr class="text-success">
                                    <th>Coupon Discount</th>
                                    <td>- ₹<span id="display_discount">0.00</span></td>
                                </tr>
                                <tr style="font-size: 1.5em; background: #eee;">
                                    <th>Net Payable</th>
                                    <td>₹<span id="display_net">1000.00</span></td>
                                </tr>
                            </table>

                            <input type="hidden" name="coupon_discount" id="hidden_discount" value="0">
                            <input type="hidden" name="net_payable" id="hidden_net" value="1000">
                            
                            <button type="submit" class="btn btn-success btn-lg btn-block">Generate Bill</button>
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
 * Handles math if a coupon is already applied
 */
function updateTotal() {
    let amt = parseFloat($('#main_bill_amt').val());
    if(isNaN(amt)) amt = 0;

    // Update the base total display
    $('#display_total').text(amt.toFixed(2));

    // Get current applied discount from hidden input
    let currentDiscount = parseFloat($('#hidden_discount').val());

    // Calculate new Net
    let newNet = amt - currentDiscount;
    if(newNet < 0) newNet = 0;

    // Update display and hidden net field
    $('#display_net').text(newNet.toFixed(2));
    $('#hidden_net').val(newNet.toFixed(2));
}

$(document).ready(function() {
    /**
     * AJAX Call to validate and apply coupon
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
                    $('#coupon_resp_msg').html('<i class="fa fa-check"></i> ' + res.message).css('color', 'green');
                    
                    // 2. Extract calculations from server response
                    let disc = parseFloat(res.discount);
                    let net = parseFloat(res.final_amount);

                    // 3. Update the UI text
                    $('#display_discount').text(disc.toFixed(2));
                    $('#display_net').text(net.toFixed(2));
                    
                    // 4. Update the hidden inputs for the Form POST
                    $('#hidden_discount').val(disc.toFixed(2));
                    $('#hidden_net').val(net.toFixed(2));
                    
                    // 5. UI Improvements: Readonly mode
                    $('#coupon_input').attr('readonly', true);
                    $('#apply_coupon_btn').attr('disabled', true).text('Applied');
                } else {
                    // Show Error (Expired, Invalid, etc.)
                    $('#coupon_resp_msg').html('<i class="fa fa-times"></i> ' + res.message).css('color', 'red');
                }
            },
            error: function() {
                alert("Server error. Check if Coupon_model is loaded.");
            }
        });
    });
});
</script>